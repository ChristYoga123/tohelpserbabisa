<?php

use App\Http\Controllers\BersihController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('pages.beranda.index');
})->name('index');
Route::get('/ojek', function () {
    return view('pages.transportasi.index');
})->name('ojek');

Route::get('/bersih-bersih', [BersihController::class, 'index'])->name('bersih');    
Route::post('/bersih-bersih/pesan', [BersihController::class, 'pesan'])->name('bersih.pesan');    

Route::get('/pindahan', function () {
    return view('pages.pindahan.index');
})->name('pindahan');
Route::get('/jasa-kustom', function () {
    return view('pages.jasa-kustom.index');
})->name('kustom');
