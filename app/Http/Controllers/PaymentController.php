<?php

namespace App\Http\Controllers;
use Midtrans\Snap;
use App\Models\Data;
use Midtrans\Config;

use App\Models\Transaksi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;

class PaymentController extends Controller
{
    public function __construct()
    {
        Config::$serverKey = config('midtrans.server_key');
        Config::$isProduction = config('midtrans.is_production');
        Config::$isSanitized = config('midtrans.is_sanitized');
        Config::$is3ds = config('midtrans.is_3ds');
    }

    public function storeMethod(Request $request)
    {
        $validated = $request->validate([
            'id' => 'required|integer',
            'method' => 'required|string'
        ]);

        // Ambil data meteran dari tabel data
        $tagihan = Data::find($validated['id']);
        if (!$tagihan) {
            return response()->json(['success' => false, 'message' => 'Data meteran tidak ditemukan.']);
        }

        // Update status dan metode di tabel data
        $tagihan->metode_pembayaran = $validated['method'];
        $tagihan->status = $validated['method'] === 'Tunai' ? 'Menunggu Konfirmasi' : 'Menunggu Pembayaran';
        $tagihan->save();

        // Buat entri baru di tabel transaksis
        $transaksi = new Transaksi();
        $transaksi->id_meteran = $tagihan->id;
        $transaksi->status = $tagihan->status;
        $transaksi->totalbayar = $tagihan->harga; // ambil total dari harga di data
        $transaksi->save();

        // Jika Non Tunai, redirect ke Midtrans
        if ($validated['method'] === 'Non Tunai') {
            return response()->json([
                'success' => true,
                'redirect' => route('payment.pay', ['id' => $transaksi->id])
            ]);
        }

        return response()->json(['success' => true]);
    }

    // === Proses Midtrans ===
    public function pay($id)
    {
        $transaksi = Transaksi::findOrFail($id);
        $dataMeteran = $transaksi->data; // relasi ke tabel data
        $user = $dataMeteran->user; // relasi user dari data

        $params = [
            'transaction_details' => [
                'order_id' => $transaksi->id,
                'gross_amount' => intval($transaksi->totalbayar),
            ],
            'customer_details' => [
                'first_name' => $user->name ?? 'Pelanggan',
                'email' => 'user' . $user->id . '@example.com',
            ],
        ];

        $snapToken = Snap::getSnapToken($params);

        return view('payment.qris', compact('snapToken', 'transaksi'));
    }

    // === Callback Midtrans ===
    public function callback(Request $request)
    {
        Log::info('=== MIDTRANS CALLBACK DITERIMA ===', $request->all());
        $serverKey = config('midtrans.server_key');
        $hashed = hash('sha512',
        $request->order_id .
        $request->status_code .
        $request->gross_amount .
        $serverKey
        );

        if ($hashed !== $request->signature_key) {
            return response()->json(['message' => 'Invalid signature'], 403);
        }

        $transaksi = Transaksi::find($request->order_id);

        if (!$transaksi) {
            return response()->json(['message' => 'Transaksi tidak ditemukan'], 404);
        }

        // Ambil data terkait
        $data = $transaksi->data;

        switch ($request->transaction_status) {
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
