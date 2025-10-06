<?php

use App\Models\Data;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DataController;
use App\Http\Controllers\UserController;

Route::middleware(['auth'])->group(function () {
    Route::get('/', function () {
        if(Auth::user()->role == 'admin'){
            return view('haladmin', ['title' => 'Dashboard Admin']);
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
                        ->lastperUser()
                        ->simplePaginate(10)
                        ->withQueryString()
        ]);
    });

    Route::post('/data/store', [DataController::class, 'store'])->name('data.store');

    Route::get('/pelanggan', function () {
        return view('home', ['title' => 'Dashboard Pelanggan']);
    });

    Route::get('/data/{data:slug}', [DataController::class, 'show'])->name('data.show');

    Route::resource('users', UserController::class);
    Route::resource('data', DataController::class);

    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
});

Route::get('/login', function () {
    return view('login');
})->name('login');

Route::post('/login', [AuthController::class, 'login'])->name('login.post');
