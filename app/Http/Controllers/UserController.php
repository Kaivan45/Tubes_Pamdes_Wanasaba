<?php

namespace App\Http\Controllers;

use App\Models\Data;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return View
     */
    public function index(): View
    {
        // Dapatkan ID user yang sedang login
        $userId = Auth::id();

        // Ambil data terakhir
        /** @var Data|null $dataTerakhir */
        $dataTerakhir = Data::where('user_id', $userId)
            ->latest()
            ->first();

        // Query semua data
        $dataSemuaQuery = Data::where('user_id', $userId)
            ->orderBy('created_at', 'desc');

        // Jika data terakhir ada dan belum lunas, jangan tampilkan di dataSemua
        if ($dataTerakhir !== null && $dataTerakhir->status !== 'Lunas') {
            $dataSemuaQuery->where('id', '!=', $dataTerakhir->id);
        }

        $dataSemua = $dataSemuaQuery->get();

        return view('home', [
            'title' => 'Dashboard Pelanggan',
            'dataTerakhir' => $dataTerakhir,
            'dataSemua' => $dataSemua,
        ]);
    }

    /**
     * Store a newly created resource.
     *
     * @return RedirectResponse
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required',
            'username' => 'required|unique:users',
            'password' => 'required|min:5|confirmed',
            'alamat' => 'required',
            'noHp' => 'required',
        ], [
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

        User::create($validated);

        return redirect('/datauser')->with('success', 'Data berhasil ditambahkan!');
    }

    /**
     * Remove the specified user.
     *
     * @param string $username
     * @return RedirectResponse
     */
    public function destroy(string $username): RedirectResponse
    {
        /** @var User|null $user */
        $user = User::where('username', $username)->first();

        if ($user === null) {
            return redirect()->back()->with('error', 'User tidak ditemukan.');
        }

        $user->delete();

        return redirect()->back()->with('success', 'User berhasil dihapus.');
    }
}
