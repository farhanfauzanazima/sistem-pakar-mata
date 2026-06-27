<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\PenyakitController;
use App\Http\Controllers\Admin\GejalaController;
use App\Http\Controllers\Admin\AturanController;
use App\Http\Controllers\Admin\DiagnosaController;
use App\Http\Controllers\Admin\AdminController;

// Route publik admin
Route::get('/login',  [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');

// Route admin yang butuh autentikasi
Route::middleware('admin.auth')->group(function () {

    Route::post('/logout',   [AuthController::class, 'logout'])->name('logout');
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Penyakit
    Route::resource('penyakit', PenyakitController::class);

    // Gejala
    Route::resource('gejala', GejalaController::class);

    // Aturan CF
    Route::resource('aturan', AturanController::class)->except(['show']);

    // Diagnosa
    Route::prefix('diagnosa')->name('diagnosa.')->group(function () {
        Route::get('/',        [DiagnosaController::class, 'index'])->name('index');
        Route::get('/export',  [DiagnosaController::class, 'export'])->name('export');
        Route::get('/{id}',    [DiagnosaController::class, 'show'])->name('show');
        Route::delete('/{id}', [DiagnosaController::class, 'destroy'])->name('destroy');
    });

    // Manajemen Admin — hanya Super Admin
    // Gunakan prefix 'kelola-admin' agar tidak bentrok dengan nama route
    Route::middleware('super.admin')
        ->prefix('kelola-admin')
        ->name('admin.')
        ->group(function () {
            Route::get('/',             [AdminController::class, 'index'])->name('index');
            Route::get('/tambah',       [AdminController::class, 'create'])->name('create');
            Route::post('/',            [AdminController::class, 'store'])->name('store');
            Route::get('/{admin}/edit', [AdminController::class, 'edit'])->name('edit');
            Route::put('/{admin}',      [AdminController::class, 'update'])->name('update');
            Route::delete('/{admin}',   [AdminController::class, 'destroy'])->name('destroy');
        });

});