<?php

namespace App\Http\Controllers;

use App\Models\Data;
use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
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

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required',
            'username' => 'required|unique:users',
            'password' => 'required|min:5|confirmed',
            'alamat' => 'required',
            'noHp' => 'required',
        ],
        [
            'password.confirmed' => 'Password tidak cocok.',
            'name.required' => 'Nama wajib diisi.',
            'username.required' => 'Username wajib diisi.',
            'username.unique' => 'Username sudah digunakan, silakan pilih yang lain.',
            'password.required' => 'Password wajib diisi.',
            'password.min' => 'Password minimal harus 5 karakter.',
            'alamat.required' => 'Alamat wajib diisi.',
            'noHp.required' => 'Nomor HP wajib diisi.',
        ]);

        $validated['password'] = Hash::make($validated['password']);
        $validated['role'] = 'pelanggan';

        $user = User::create($validated);

        return redirect('/datauser')->with('success', 'Data berhasil ditambahkan!');
    }

    public function destroy($username)
    {
        $user = User::where('username', $username)->first();

        $user->delete();

        return redirect()->back()->with('success', 'User berhasil dihapus.');
    }
}
