<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\User\BerandaController;
use App\Http\Controllers\User\DiagnosaController;
use App\Http\Controllers\User\HasilController;
use App\Http\Controllers\User\RiwayatController;
use App\Http\Controllers\User\TentangController;

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
    Route::get('/',         [HasilController::class, 'show'])->name('show');
    Route::get('/{id}/pdf', [HasilController::class, 'downloadPdf'])->name('pdf');
});

// Riwayat
Route::prefix('riwayat')->name('user.riwayat.')->group(function () {
    Route::get('/',            [RiwayatController::class, 'index'])->name('index');
    Route::post('/cek-hp',     [RiwayatController::class, 'cekHp'])->name('cek-hp');
    Route::get('/pin',         [RiwayatController::class, 'formPin'])->name('pin');
    Route::post('/verifikasi', [RiwayatController::class, 'verifikasiPin'])->name('verifikasi');
    Route::get('/daftar',      [RiwayatController::class, 'daftar'])->name('daftar');
    Route::get('/reset-pin',   [RiwayatController::class, 'formResetPin'])->name('reset-pin');
    Route::post('/reset-pin',  [RiwayatController::class, 'resetPin'])->name('reset-pin.proses');
    Route::get('/{id}',        [RiwayatController::class, 'detail'])->name('detail');
    Route::get('/{id}/pdf',    [RiwayatController::class, 'downloadPdf'])->name('pdf');
});

// Tentang
Route::get('/tentang', [TentangController::class, 'index'])->name('user.tentang');