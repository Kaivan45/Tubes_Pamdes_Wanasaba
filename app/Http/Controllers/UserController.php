<?php

namespace App\Http\Controllers;

use App\Models\Data;
use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required',
            'username' => 'required|unique:users',
            'password' => 'required|min:5',
            'alamat' => 'required',
            'noHp' => 'required',
        ],
        [
       
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

        // Data::create([
        //     'user_id' => $user->id,         
        //     'meteran' => 0,                 
        //     'harga' => 0,                   
        //     'tanggal' => now(),             
        //     'status' => 'Belum Lunas',
        //     'slug' => $user->id . uniqid(),
        // ]);

        return redirect('/haltambah')->with('success', 'Data berhasil ditambahkan!');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        // $user->delete();
        // return redirect('/tampil')->with('success', 'User berhasil dihapus!');
    }
}
