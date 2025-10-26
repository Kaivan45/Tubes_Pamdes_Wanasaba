<?php

namespace App\Http\Controllers;
use App\Models\Transaksi;
use Midtrans\Snap;
use Midtrans\Config;

use Illuminate\Http\Request;

class PaymentController extends Controller
{
    public function __construct()
    {
        Config::$serverKey = config('midtrans.server_key');
        Config::$isProduction = config('midtrans.is_production');
        Config::$isSanitized = config('midtrans.is_sanitized');
        Config::$is3ds = config('midtrans.is_3ds');
    }

    public function pay($id)
    {
        $transaksi = Transaksi::findOrFail($id);

        $user = $transaksi->data->user;

        $customerEmail = 'user' . $user->id . '@example.com';

        $params = [
            'transaction_details' => [
                'order_id' => $transaksi->id,
                'gross_amount' => intval($transaksi->data->harga),
            ],
            'customer_details' => [
                'first_name' => $user->name ?? 'Pelanggan',
                'email' => $customerEmail,
            ]
        ];

        $snapToken = Snap::getSnapToken($params);

        if (!$snapToken) {
            dd('Snap token gagal dibuat', $params);
        }

        return view('payment.qris', compact('snapToken', 'transaksi'));
    }

    public function callback(Request $request)
    {
        $serverKey = config('midtrans.server_key');
        $hashed = hash('sha512',
        $request->order_id .
        $request->status_code .
        $request->gross_amount .
        $serverKey
    );

    if ($hashed === $request->signature_key) {
        $transaksi = Transaksi::find($request->order_id);

        if ($request->transaction_status == 'settlement') {
            $transaksi->status = 'Lunas';
            $transaksi->tanggalbayar = now();
            $transaksi->save();
        }
    }

    return response()->json(['status' => 'Lunas']);
    }
}
