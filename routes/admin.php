<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\DashboardController;

// Route publik admin (tanpa auth)
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');

// Route admin yang butuh autentikasi
Route::middleware('admin.auth')->group(function () {

    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Placeholder — akan diisi controller asli di sesi berikutnya
    Route::get('/penyakit', fn() => 'coming soon')->name('penyakit.index');
    Route::get('/gejala',   fn() => 'coming soon')->name('gejala.index');
    Route::get('/aturan',   fn() => 'coming soon')->name('aturan.index');
    Route::get('/diagnosa', fn() => 'coming soon')->name('diagnosa.index');
    Route::get('/admin',    fn() => 'coming soon')->name('admin.index');

});