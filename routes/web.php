<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('pages.beranda.index');
})->name('index');
Route::get('/ojek', function () {
    return view('pages.transportasi.index');
})->name('ojek');
Route::get('/bersih-bersih', function () {
    return view('pages.bersih.index');
})->name('bersih');    
Route::get('/pindahan', function () {
    return view('pages.pindahan.index');
})->name('pindahan');
