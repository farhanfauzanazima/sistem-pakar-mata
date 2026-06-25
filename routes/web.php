<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\User\BerandaController;
use App\Http\Controllers\User\DiagnosaController;
use App\Http\Controllers\User\HasilController;

// Beranda
Route::get('/', [BerandaController::class, 'index'])->name('beranda');

// Diagnosa
Route::prefix('diagnosa')->name('user.diagnosa.')->group(function () {
    Route::get('/',        [DiagnosaController::class, 'form'])->name('form');
    Route::post('/',       [DiagnosaController::class, 'prosesForm'])->name('proses-form');
    Route::get('/gejala',  [DiagnosaController::class, 'gejala'])->name('gejala');
    Route::post('/gejala', [DiagnosaController::class, 'prosesGejala'])->name('proses-gejala');
    Route::get('/cek-hp',  [DiagnosaController::class, 'cekHp'])->name('cek-hp');
});

// Hasil
Route::prefix('hasil')->name('user.hasil.')->group(function () {
    Route::get('/',           [HasilController::class, 'show'])->name('show');
    Route::get('/{id}/pdf',   [HasilController::class, 'downloadPdf'])->name('pdf');
});

// Placeholder
Route::get('/riwayat', fn() => 'coming soon')->name('user.riwayat.index');
Route::get('/tentang', fn() => 'coming soon')->name('user.tentang');