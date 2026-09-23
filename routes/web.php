<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('home');
})->name('home');

Route::get('/katalog', function () {
    return view('katalog');
})->name('katalog');

Route::get('/produk', function () {
    return view('produk');
})->name('produk');

Route::get('/news', function () {
    return view('news');
})->name('news');

Route::get('/news-detail', function () {
    return view('news-detail');
})->name('news-detail');

Route::get('/superarea', function () {
    return view('superarea');
})->name('superarea');

Route::get('/contact', function () {
    return view('contact');
})->name('contact');
