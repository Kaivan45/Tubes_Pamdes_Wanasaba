<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DataController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\UserController;
use App\Models\Data;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth'])->group(function () {

    Route::get('/', function () {
        if (Auth::user()->role == 'admin') {
            return redirect()->route('user.dashboard');
        } else {
            return redirect('/pelanggan');
        }
    });

    /* ------------------ ROUTE KHUSUS ADMIN ------------------ */
    Route::middleware(['admin'])->group(function () {

        Route::get('/haladmin', [UserController::class, 'dashboard'])->name('user.dashboard');
        Route::get('/haltambah', fn () => view('haltambah', ['title' => 'Tambah Data']));

        Route::get('/tampil', fn () => view('tampil', [
            'title' => 'Tampil Data',
            'data' => Data::with('user')->filter()->belumLunasFirst()->simplePaginate(10)->withQueryString(),
        ]));

        // Route::post('/data/store', [DataController::class, 'store'])->name('data.store');
        Route::get('/data/{data:slug}/edit', [DataController::class, 'edit'])->name('data.edit');
        Route::put('/data/{data:slug}', [DataController::class, 'update'])->name('data.update');
        Route::get('/data/{data:slug}', [DataController::class, 'show'])->name('data.show');
        Route::get('/datauser', [DataController::class, 'index'])->name('data.index');
        Route::get('/data/create/{username}', [DataController::class, 'create'])->name('data.create');
        Route::post('/data/store', [DataController::class, 'store2'])->name('data.store2');
        Route::delete('/users/{username}', [UserController::class, 'destroy'])->name('user.destroy');
        Route::delete('/data/{data:slug}', [DataController::class, 'destroy'])->name('data.destroy');

        Route::resource('users', UserController::class);
    });
    /* --------------------------------------------------------- */
    // ROUTE YANG BOLEH DIAKSES USER BIASA
    Route::get('/pelanggan', [UserController::class, 'index'])->name('pelanggan.index');

    Route::get('/pay/{id}', [PaymentController::class, 'pay'])->name('payment.pay');
    Route::post('/payment/method', [PaymentController::class, 'storeMethod'])->name('payment.method');

    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
});

Route::get('/login', fn () => view('login'))->name('login');

Route::post('/login', [AuthController::class, 'login'])->name('login.post');
