<?php

namespace App\Http\Controllers;

use App\Models\Data;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class DataController extends Controller
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
    public function destroy(Data $data)
    {
        $data->user->delete();
        $data->delete();
        return redirect('/tampil')->with('success', 'Data berhasil dihapus!');
    }
}
