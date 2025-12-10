<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Data;
use App\Models\User;
use Illuminate\View\View;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\RedirectResponse;

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
            ->where('tanggal', '>=', Carbon::now()->subYear())
            ->orderBy('created_at', 'desc');

        // Jika data terakhir ada dan belum lunas, jangan tampilkan di dataSemua
        if ($dataTerakhir !== null && $dataTerakhir->status !== 'Lunas') {
            $dataSemuaQuery->where('id', '!=', $dataTerakhir->id);
        }

        $dataSemua = $dataSemuaQuery->Simplepaginate(1);

        return view('home', [
            'title' => 'Dashboard Pelanggan',
            'dataTerakhir' => $dataTerakhir,
            'dataSemua' => $dataSemua,
        ]);
        
    }

    public function dashboard(): View
    {
        $tahun = now()->year;

        $pemasukan = Data::select(
                DB::raw('MONTH(updated_at) as bulan'),
                DB::raw('SUM(harga) as total')
            )
            ->where('status', 'Lunas')
            ->whereYear('updated_at', $tahun)
            ->groupBy(DB::raw('MONTH(updated_at)'))
            ->pluck('total', 'bulan');

        // Buat array 12 bulan (default 0)
        $dataBulanan = [];
        for ($i = 1; $i <= 12; $i++) {
            $dataBulanan[] = $pemasukan[$i] ?? 0;
        }

        return view('haladmin', [
            'title' => 'Dashboard Admin',
            'jumlahUser' => User::where('role', 'pelanggan')->count(),
            'pemasukanBulanan' => $dataBulanan,
            'tahun' => $tahun
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
