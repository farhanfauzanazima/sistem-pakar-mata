<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\PenyakitController;
use App\Http\Controllers\Admin\GejalaController;
use App\Http\Controllers\Admin\AturanController;

// Route publik admin
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');

// Route admin yang butuh autentikasi
Route::middleware('admin.auth')->group(function () {

    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Penyakit
    Route::resource('penyakit', PenyakitController::class);

    // Gejala
    Route::resource('gejala', GejalaController::class);

    // Aturan CF
    Route::resource('aturan', AturanController::class)->except(['show']);

    // Placeholder sesi berikutnya
    Route::get('/diagnosa', fn() => 'coming soon')->name('diagnosa.index');
    Route::get('/admin',    fn() => 'coming soon')->name('admin.index');

});