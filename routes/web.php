<?php

use App\Http\Controllers\BersihController;
use App\Http\Controllers\JasaCustomController;
use App\Http\Controllers\PindahanController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('pages.beranda.index');
})->name('index');
Route::get('/ojek', function () {
    return view('pages.transportasi.index');
})->name('ojek');

Route::get('/bersih-bersih', [BersihController::class, 'index'])->name('bersih');    
Route::post('/bersih-bersih/pesan', [BersihController::class, 'pesan'])->name('bersih.pesan');    

Route::get('/pindahan', [PindahanController::class, 'index'])->name('pindahan');
Route::post('/pindahan/pesan', [PindahanController::class, 'pesan'])->name('pindahan.pesan');

Route::get('/jasa-kustom', [JasaCustomController::class, 'index'])->name('kustom');
Route::post('/jasa-kustom/pesan', [JasaCustomController::class, 'pesan'])->name('kustom.pesan');
