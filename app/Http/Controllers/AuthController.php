<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
   /**
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function login(Request $request): \Illuminate\Http\RedirectResponse // Tambahkan return type
    {
        $credentials = $request->validate([
            'username' => 'required',
            'password' => 'required',
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();

            // Lakukan pengecekan NULL pada Auth::user()
            $user = Auth::user();

            if ($user !== null) { // Pengecekan eksplisit untuk User model
                if ($user->role === 'admin') {
                    return redirect('/');
                } else {
                    return redirect('/pelanggan');
                }
            }
            // Walaupun tidak mungkin sampai di sini jika Auth::attempt berhasil,
            // secara teknis tetap perlu ditangani jika Auth::user() mengembalikan null.
        }

        return back()->withErrors([
            'username' => 'Username atau password salah',
        ]);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function logout(Request $request): \Illuminate\Http\RedirectResponse // Tambahkan return type
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login');
    }
}
