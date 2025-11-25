<?php

namespace App\Http\Controllers;

use App\Models\Data;
use App\Models\Transaksi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Illuminate\View\View;
use Midtrans\Config;
use Midtrans\Snap;
use Illuminate\Http\JsonResponse;

class PaymentController extends Controller
{
    /**
     * Set up Midtrans configuration.
     * Larastan Fix: Removed :void return type from constructor.
     */
    public function __construct()
    {
        Config::$serverKey = config('midtrans.server_key');
        Config::$isProduction = config('midtrans.is_production');
        Config::$isSanitized = config('midtrans.is_sanitized');
        Config::$is3ds = config('midtrans.is_3ds');
    }

    /**
     * Memproses pemilihan metode pembayaran dan membuat transaksi.
     * @param Request $request
     * @return JsonResponse
     */
    public function storeMethod(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'id' => 'required|integer',
            'method' => 'required|string',
        ]);

        // Ambil data meteran dari tabel data
        /** @var Data|null $tagihan */
        $tagihan = Data::find($validated['id']);

        if (! $tagihan) {
            return response()->json(['success' => false, 'message' => 'Data meteran tidak ditemukan.'], 404);
        }

        // Update status dan metode di tabel data
        $tagihan->metode_pembayaran = $validated['method'];
        $tagihan->status = $validated['method'] === 'Tunai' ? 'Menunggu Konfirmasi' : 'Menunggu Pembayaran';
        $tagihan->save();

        // Buat entri baru di tabel transaksis
        /** @var Transaksi $transaksi */
        $transaksi = new Transaksi;
        
        // Larastan Fix: Explicitly cast $tagihan->id to (int) for $id_meteran property
        $transaksi->id_meteran = (int) $tagihan->id;
        $transaksi->status = $tagihan->status;

        // Larastan Fix: Explicitly cast $tagihan->harga to (int) for $totalbayar property
        // Assuming $tagihan->harga is the amount. Use int for currency to avoid float issues.
        $transaksi->totalbayar = (int) $tagihan->harga;
        
        $transaksi->save();

        // Jika Non Tunai, redirect ke Midtrans
        if ($validated['method'] === 'Non Tunai') {
            return response()->json([
                'success' => true,
                'redirect' => route('payment.pay', ['id' => $transaksi->id]),
            ]);
        }

        return response()->json(['success' => true]);
    }

    /**
     * Menampilkan halaman pembayaran Midtrans SNAP.
     * @param int $id
     * @return View
     */
    public function pay(int $id): View
    {
        /** @var Transaksi $transaksi */
        $transaksi = Transaksi::findOrFail($id);
        
        // Relasi data() harus ada di Model Transaksi
        /** @var Data|null $dataMeteran */
        $dataMeteran = $transaksi->data;

        if ($dataMeteran === null) {
            abort(404, 'Data meteran terkait tidak ditemukan.');
        }

        // Relasi user() harus ada di Model Data
        /** @var \App\Models\User|null $user */
        $user = $dataMeteran->user;

        if ($user === null) {
            abort(404, 'User terkait tidak ditemukan.');
        }

        // Midtrans Order ID format: {transaksi_id}-{random_string}
        $orderId = (string) $transaksi->id.'-'.Str::random(6);
        /** @var string|null $userName */
        $userName = $user->name;

        /** @var int|null $userId */
        $userId = $user->id;

        $params = [
            'transaction_details' => [
                'order_id' => $orderId,
                // Pastikan totalbayar adalah int
                'gross_amount' => (int) $transaksi->totalbayar,
            ],
            'customer_details' => [
                'first_name' => $userName ?? 'Pelanggan',
                'email'      => 'user' . strval($userId) . '@example.com',
            ],
        ];

        $snapToken = Snap::getSnapToken($params);

        return view('payment.qris', compact('snapToken', 'transaksi'));
    }

    /**
     * Callback dari Midtrans.
     * @param Request $request
     * @return JsonResponse
     */
    public function callback(Request $request): JsonResponse
    {
        Log::info('=== MIDTRANS CALLBACK DITERIMA ===', $request->all());

        /** @var string $serverKey */
        $serverKey = config('midtrans.server_key') ?? '';

        /** 
         * Pastikan semua values adalah string aman sebelum digabung.
         */
        /** @var string $orderId */
        $orderId = $request->input('order_id', '');

        /** @var string $statusCode */
        $statusCode = $request->input('status_code', '');

        /** @var string $grossAmount */
        $grossAmount = $request->input('gross_amount', '');

        /** @var string $signatureKey */
        $signatureKey = $request->input('signature_key', '');


        // Hash dari Midtrans
        $hashed = hash('sha512', $orderId . $statusCode . $grossAmount . $serverKey);

        if ($hashed !== $signatureKey) {
            return response()->json(['message' => 'Invalid signature'], 403);
        }

        // Ekstrak ID transaksi dari "12345-xxxxxx"
        $transaksiId = intval(explode('-', $orderId)[0]);

        /** @var Transaksi|null $transaksi */
        $transaksi = Transaksi::find($transaksiId);

        if (! $transaksi) {
            return response()->json(['message' => 'Transaksi tidak ditemukan'], 404);
        }

        /** @var Data|null $data */
        $data = $transaksi->data;

        if (! $data) {
            return response()->json(['message' => 'Data meteran terkait Transaksi tidak ditemukan'], 404);
        }

        switch ($request->input('transaction_status')) {
            case 'capture':
            case 'settlement':
                $transaksi->status = 'Lunas';
                $transaksi->tanggalbayar = now();
                $data->status = 'Lunas';
                break;

            case 'pending':
                $transaksi->status = 'Menunggu Pembayaran';
                $data->status = 'Menunggu Pembayaran';
                break;

            case 'deny':
            case 'expire':
            case 'cancel':
                $transaksi->status = 'Gagal';
                $data->status = 'Gagal';
                break;
        }

        $transaksi->save();
        $data->save();

        return response()->json(['message' => 'Callback processed successfully']);
}

}