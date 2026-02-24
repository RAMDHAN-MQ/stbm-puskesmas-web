<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\StbmController;
use App\Http\Controllers\BerandaController;
use App\Http\Controllers\RekomendasiController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/test', function () {
    return response()->json([
        'status' => 'success',
        'message' => 'API works!'
    ]);
});


// login
Route::post('/loginHP', [AuthController::class, 'loginHP']);


// beranda
Route::get('/dashboard', [BerandaController::class, 'indexHP']);

// statistik
Route::get('/statistik', [RekomendasiController::class, 'statistik']);

// riwayat
Route::get('/stbm', [StbmController::class, 'indexHP']);
Route::post('/stbm', [StbmController::class, 'storeHP']);
Route::get('/stbm/{id}', [StbmController::class, 'showHP']);

// STBM MOBILE
// wilayah dropdown
Route::get('/wilayah', [StbmController::class, 'wilayah']);

// pertanyaan
Route::get('/pertanyaan', [StbmController::class, 'pertanyaan']);

// simpan stbm
Route::post('/simpanSTBM', [StbmController::class, 'storeSTBM']);
