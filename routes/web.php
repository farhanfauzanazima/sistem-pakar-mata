<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('user.beranda.index');
})->name('beranda');

// Placeholder — akan diganti di sesi berikutnya
Route::get('/diagnosa', function () {
    return view('user.beranda.index');
})->name('user.diagnosa.form');

Route::get('/riwayat', function () {
    return view('user.beranda.index');
})->name('user.riwayat.index');

Route::get('/tentang', function () {
    return view('user.beranda.index');
})->name('user.tentang');