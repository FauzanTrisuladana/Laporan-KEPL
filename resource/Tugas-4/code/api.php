<?php

use App\Http\Controllers\Api\LinkController;
use Illuminate\Support\Facades\Route;

Route::name('api.')->group(function () {
    Route::get('/links/stats/count', [LinkController::class, 'stats'])->name('links.stats');
    Route::post('/links/shorten', [LinkController::class, 'shorten'])->name('links.shorten');
    Route::apiResource('links', LinkController::class);
});

Route::get('/health', function() {
    return response()->json([
        'message' => 'ok'
    ]);
});
