<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('pages.beranda.index');
})->name('index');
Route::get('/ojek', function () {
    return view('pages.transportasi.index');
})->name('ojek');
