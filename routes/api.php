<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PaymentController;

Route::post('/midtrans/callback', [PaymentController::class, 'callback']);
