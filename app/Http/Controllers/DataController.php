<?php

namespace App\Http\Controllers;

use App\Models\Data;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class DataController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = User::where('role', 'pelanggan');

        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%$search%")
                ->orWhere('alamat', 'like', "%$search%")
                ->orWhere('noHp', 'like', "%$search%");
            });
        }

        $data = $query->Simplepaginate(10);

        return view('datauser', [
            'title' => 'Data Pelanggan',
            'data' => $data
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create($username)
    {
        $user = User::where('username', $username)->firstOrFail();

        return view('data', [
            'title' => 'Input Meteran',
            'user' => $user
        ]);
    }

    public function store2(Request $request)
    {
        // Validasi input
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'meteran' => 'required|numeric|min:0',
            'harga'   => 'required|numeric|min:0',
            'tanggal' => 'required|date',
        ]);

        // Simpan ke tabel Data
        Data::create([
            'user_id' => $validated['user_id'],
            'meteran' => $validated['meteran'],
            'harga'   => $validated['harga'],
            'status'  => 'Belum Lunas',
            'tanggal' => $validated['tanggal'],
        ]);

        return redirect()->route('data.index')->with('success', 'Data meteran berhasil ditambahkan!');
    }
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'meteran' => 'required|numeric|min:0',
            'harga' => 'required|numeric|min:0',
        ]);

        Data::create($validated + [
            'status' => 'Belum Lunas',
            'tanggal' => now(),
        ]);
        return redirect('/tampil')->with('success', 'berhasil di inputkan');
    }

    /**
     * Display the specified resource.
     */
    public function show(Data $data)
    {
        return view('data', [
        'title' => 'Input Meter',
        'data' => $data,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Data $data)
    {
        return view('edit', [
            'title' => 'Edit Data',
            'data' => $data
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Data $data)
    {
        $validated = $request->validate([
            'status' => 'required|string'
        ]);

        $data->update($validated);

        return redirect('/tampil')->with('success', 'Data berhasil diperbarui!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Data $data)
    {
        $data->delete();
        return redirect('/tampil')->with('success', 'Data berhasil dihapus!');
    }

    public function destroy2($username)
    {
        $user = User::where('username', $username)->first();

        $user->delete();

        return redirect()->back()->with('success', 'User berhasil dihapus.');
    }
}
