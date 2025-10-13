<?php

use App\Models\Data;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DataController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\PelangganController;

Route::middleware(['auth'])->group(function () {
    Route::get('/', function () {
        if(Auth::user()->role == 'admin'){
            $jumlahUser = User::totalRegularUsers();
            return view('haladmin', ['title' => 'Dashboard Admin', 'jumlahUser' => $jumlahUser]);
        } else {
            return redirect('/pelanggan');
        }
    });

    Route::get('/haltambah', function () {
        return view('haltambah', ['title' => 'Tambah Data']);
    });

    Route::get('/tampil', function () {
        return view('tampil', [
            'title' => 'Tampil Data',
            'data' => Data::with('user')
                        ->filter()
                        ->belumLunasFirst()
                        ->simplePaginate(10)
                        ->withQueryString()
        ]);
    });

    Route::post('/data/store', [DataController::class, 'store'])->name('data.store');

    Route::get('/pelanggan', [PelangganController::class, 'index'])->name('pelanggan.index');

    Route::get('/data/{data:slug}/edit', [DataController::class, 'edit'])->name('data.edit');

    Route::put('/data/{data:slug}', [DataController::class, 'update'])->name('data.update');

    Route::get('/data/{data:slug}', [DataController::class, 'show'])->name('data.show');

    Route::get('/datauser', [DataController::class, 'index'])->name('data.index');
    
    Route::get('/data/create/{username}', [DataController::class, 'create'])->name('data.create');

    Route::post('/data/store', [DataController::class, 'store2'])->name('data.store2');

    Route::put('/data/{data:slug}', [DataController::class, 'update'])->name('data.update');

    Route::resource('users', UserController::class);
    
    Route::resource('data', DataController::class);

    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
});

Route::get('/login', function () {
    return view('login');
})->name('login');

Route::post('/login', [AuthController::class, 'login'])->name('login.post');
