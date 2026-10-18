<?php

use App\Http\Controllers\AkunController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\HistoryRiilController;
use App\Http\Controllers\LaporanController;
use App\Http\Controllers\MutasiRekeningController;
use App\Http\Controllers\PenanggungJawabController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TransaksiController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

// Status check route (/api/status)
Route::get('/status', function () {
    return response()->json(['status' => 'ok']);
});

// Route autentikasi (/api/auth)
Route::prefix('auth')
    ->controller(AuthController::class)
    ->group(function () {
        Route::post('/login', 'login');
        Route::post('/logout', 'logout')->middleware('auth:sanctum');
    });

Route::middleware('auth:sanctum')->group(function () {

    Route::prefix('profile')->controller(ProfileController::class)->group(function () {
        Route::get('/me', 'me');
        Route::put('/update', 'update');
        Route::put('/update-password', 'updatePassword');
        Route::delete('/delete', 'delete');
    });

    Route::prefix('laporan')->controller(LaporanController::class)->group(function () {
        Route::get('/', 'index');
        Route::get('/pdf', 'pdf');
    });

    Route::prefix('dashboard')->controller(DashboardController::class)->group(function () {
        Route::get('/', 'index');
    });

    Route::middleware('role:bendahara')->group(function () {
        Route::put('/user/{id}/toggle-status', [UserController::class, 'toggleStatus']);
        Route::apiResource('user', UserController::class)->except(['show']);

        Route::get('/penanggung-jawab/dropdown', [PenanggungJawabController::class, 'dropdown']);
        Route::apiResource('penanggung-jawab', PenanggungJawabController::class);

        Route::get('/akun/dropdown', [AkunController::class, 'dropdown']);
        Route::apiResource('akun', AkunController::class);

        Route::prefix('history-riil')->controller(HistoryRiilController::class)->group(function () {
            Route::get('/', 'index');
            Route::put('/{id}/verify', 'verify');
        });

        Route::apiResource('mutasi-rekening', MutasiRekeningController::class)->except(['show']);
        Route::apiResource('transaksi', TransaksiController::class)->except(['show']);
    });
});
