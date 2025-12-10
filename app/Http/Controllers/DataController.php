<?php

namespace App\Http\Controllers;

use App\Models\Data;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Contracts\View\View; // Import untuk return type View
use Illuminate\Http\RedirectResponse; // Import untuk return type RedirectResponse
use Illuminate\Pagination\SimplePaginate; // Import untuk type hint SimplePaginate

class DataController extends Controller
{
    /**
     * Display a listing of the resource.
     * @param Request $request
     * @return View 
     */
    public function index(Request $request): View
    {
        // Query akan mengembalikan Builder, yang bisa diakses sebagai Model User
        $query = User::where('role', 'pelanggan');

        if ($request->has('search')) {
            $search = $request->string('search')->toString();

            $query->where(function ($q) use ($search) {
                // Sekarang $search dijamin sebagai string
                $q->where('name', 'like', "%$search%")
                    ->orWhere('alamat', 'like', "%$search%")
                    ->orWhere('noHp', 'like', "%$search%");
            });
        }

        // SimplePaginate<User>
        $data = $query->Simplepaginate(10);

        return view('datauser', [
            'title' => 'Data Pelanggan',
            'data' => $data,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     * @param string $username 
     * @return View 
     */
    public function create(string $username): View
    {
        // firstOrFail() menjamin $user adalah instance User, bukan null
        $user = User::where('username', $username)->firstOrFail();

        return view('data', [
            'title' => 'Input Meteran',
            'user' => $user,
        ]);
    }

    /**
     * @param Request $request
     * @return RedirectResponse 
     */
    public function store2(Request $request): RedirectResponse
    {
        // Validasi input
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'meteran' => 'required|numeric|min:0',
            'harga' => 'required|numeric|min:0',
            'tanggal' => ['required', 'date', 'after_or_equal:today'],
        ]);

        // Simpan ke tabel Data
        Data::create([
            'user_id' => $validated['user_id'],
            'meteran' => $validated['meteran'],
            'harga' => $validated['harga'],
            'status' => 'Belum Lunas',
            'tanggal' => $validated['tanggal'],
        ]);

        return redirect()->route('data.index')->with('success', 'Data meteran berhasil ditambahkan!');
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return RedirectResponse 
     */
    public function store(Request $request): RedirectResponse
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
     * @param Data $data
     * @return View 
     */
    public function show(Data $data): View
    {
        return view('data', [
            'title' => 'Input Meter',
            'data' => $data,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     * @param Data $data
     * @return View 
     */
    public function edit(Data $data): View
    {
        return view('edit', [
            'title' => 'Edit Data',
            'data' => $data,
        ]);
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param Data $data
     * @return RedirectResponse 
     */
    public function update(Request $request, Data $data): RedirectResponse
    {
        $validated = $request->validate([
            'status' => 'required|string',
        ]);

        $data->update($validated);

        return redirect('/tampil')->with('success', 'Data berhasil diperbarui!');
    }

    /**
     * Remove the specified resource from storage.
     * @param Data $data
     * @return RedirectResponse
     */
    public function destroy(Data $data): RedirectResponse
    {
        $data->delete();

        return redirect('/tampil')->with('success', 'Data berhasil dihapus!');
    }

    /**
     * Remove the specified user resource from storage.
     * @param string $username
     * @return RedirectResponse 
     */
    public function destroy2(string $username): RedirectResponse
    {
        // first() bisa mengembalikan User (Model) atau null (jika tidak ditemukan)
        $user = User::where('username', $username)->first();

        if ($user !== null) { 
            $user->delete();
            return redirect()->back()->with('success', 'User berhasil dihapus.');
        }

        // Tambahkan return jika user tidak ditemukan, biasanya redirect dengan error.
        return redirect()->back()->with('error', 'User tidak ditemukan.');
    }
}