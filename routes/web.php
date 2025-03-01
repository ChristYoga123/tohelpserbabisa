<?php

use App\Http\Controllers\BersihController;
use App\Http\Controllers\JasaCustomController;
use App\Http\Controllers\MobilController;
use App\Http\Controllers\OjekController;
use App\Http\Controllers\PindahanController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('pages.beranda.index');
})->name('index');

Route::get('/ojek', [OjekController::class, 'index'])->name('ojek');
Route::post('/ojek/pesan', [OjekController::class, 'pesan'])->name('ojek.pesan');

Route::get('/mobil', [MobilController::class, 'index'])->name('taxi');
Route::post('/mobil/pesan', [MobilController::class, 'pesan'])->name('taxi.pesan');

Route::get('/bersih-bersih', [BersihController::class, 'index'])->name('bersih');    
Route::post('/bersih-bersih/pesan', [BersihController::class, 'pesan'])->name('bersih.pesan');    

Route::get('/pindahan', [PindahanController::class, 'index'])->name('pindahan');
Route::post('/pindahan/pesan', [PindahanController::class, 'pesan'])->name('pindahan.pesan');

Route::get('/jasa-kustom', [JasaCustomController::class, 'index'])->name('kustom');
Route::post('/jasa-kustom/pesan', [JasaCustomController::class, 'pesan'])->name('kustom.pesan');
