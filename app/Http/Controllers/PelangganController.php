<?php

namespace App\Http\Controllers;

use App\Models\Data;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class PelangganController extends Controller
{
    public function index()
    {
        $userId = Auth::id();

        // Ambil data terakhir
        $dataTerakhir = Data::where('user_id', $userId)
                            ->latest()
                            ->first();

        // Query semua data
        $dataSemuaQuery = Data::where('user_id', $userId)
                              ->orderBy('created_at', 'desc');

        // Jika data terakhir ada dan belum lunas, jangan tampilkan di dataSemua
        if ($dataTerakhir && $dataTerakhir->status !== 'Lunas') {
            $dataSemuaQuery->where('id', '!=', $dataTerakhir->id);
        }

        $dataSemua = $dataSemuaQuery->get();

        return view('home', [
            'title' => 'Dashboard Pelanggan',
            'dataTerakhir' => $dataTerakhir,
            'dataSemua' => $dataSemua
        ]);
    }
}
