<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\VoucherClaimController;
use App\Http\Controllers\VoucherController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Authentication Routes
Route::group([
    'middleware' => 'api',
    'prefix' => 'auth'
], function ($router) {
    Route::post('login', [AuthController::class, 'login']);
    Route::post('logout', [AuthController::class, 'logout']);
    Route::post('refresh', [AuthController::class, 'refresh']);
});

Route::post('register', [AuthController::class, 'register']);   

// Feature Routes
Route::group([
    'middleware' => 'api',
], function ($router) {
    Route::get('voucher', [VoucherController::class, 'index']);
    Route::get('history', [VoucherClaimController::class, 'index']);
    Route::post('voucher-claim', [VoucherClaimController::class, 'store']);
    Route::delete('voucher-claim/{id}', [VoucherClaimController::class, 'destroy']);
});
