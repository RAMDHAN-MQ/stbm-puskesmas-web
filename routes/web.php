<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\BerandaController;
use App\Http\Controllers\PegawaiController;
use App\Http\Controllers\StbmController;
use App\Http\Controllers\WilayahController;
use App\Http\Controllers\PetaController;
use App\Http\Controllers\RekomendasiController;
use App\Http\Controllers\KKController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('login');
})->name('login');

// login
Route::controller(AuthController::class)->group(function () {
    Route::post('login','login')->name('auth.login');
    Route::post('logout','logout')->name('auth.logout');
});


// WEBSITE ADMIN
Route::middleware(['auth','role:admin'])->group(function() {

    // Beranda
    Route::controller(BerandaController::class)->group(function () {
        Route::get('beranda', 'index')->name('admin.beranda.index');
    });
    
    // Pegawai
    Route::controller(PegawaiController::class)->group(function () {
        Route::get('pegawai', 'index')->name('admin.pegawai.index');
        Route::get('pegawai/create', 'create')->name('admin.pegawai.create');
        Route::post('pegawai/create/store', 'store')->name('admin.pegawai.store');
        Route::delete('pegawai/delete/{id}', 'destroy')->name('admin.pegawai.destroy');
        Route::get('pegawai/view/{id}', 'view')->name('admin.pegawai.view');
        Route::get('pegawai/edit/{id}', 'edit')->name('admin.pegawai.edit');
        Route::put('pegawai/update/{id}', 'update')->name('admin.pegawai.update');
    });

    Route::controller(StbmController::class)->group(function () {
        Route::get('stbm', 'index')->name('admin.stbm.index');
        Route::get('stbm/view/{id}', 'view')->name('admin.stbm.view');
        Route::put('stbm/view/verifikasi/{id}', 'selesai')->name('admin.stbm.selesai');
        Route::get('stbm/export', 'export')->name('admin.stbm.export');
        Route::delete('stbm/destroy/{id}', 'destroy')->name('admin.stbm.destroy');
    });

    Route::controller(WilayahController::class)->group(function () {
        Route::get('wilayah', 'index')->name('admin.wilayah.index');
        Route::get('wilayah/create', 'create')->name('admin.wilayah.create');
        Route::post('wilayah/create/store', 'store')->name('admin.wilayah.store');
        Route::delete('wilayah/delete/{id}', 'destroy')->name('admin.wilayah.destroy');
        Route::get('wilayah/edit/{id}', 'edit')->name('admin.wilayah.edit');
        Route::put('wilayah/update/{id}', 'update')->name('admin.wilayah.update');
    });

    Route::controller(PetaController::class)->group(function () {
        Route::get('peta', 'index')->name('admin.peta.index');
    });

    Route::controller(RekomendasiController::class)->group(function () {
        Route::get('rekomendasi', 'index')->name('admin.rekomendasi.index');
        Route::get('rekomendasi/perdesa', 'perdesa')->name('admin.rekomendasi.perdesa');
    });

    Route::controller(KKController::class)->group(function () {
        Route::get('KK', 'index')->name('admin.kk.index');
        Route::get('KK/create', 'create')->name('admin.kk.create');
        Route::post('KK/create/store', 'store')->name('admin.kk.store');
        Route::get('KK/edit/{no_kk}', 'edit')->name('admin.kk.edit');
        Route::put('KK/update/{no_kk}', 'update')->name('admin.kk.update');
        Route::delete('KK/destroy/{no_kk}', 'destroy')->name('admin.kk.destroy');
    });
});