<?php

use App\Http\Controllers\BlogController;
use App\Http\Controllers\CatalogController;
use App\Http\Controllers\FlipbookController;
use App\Http\Controllers\InquiryController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\ProductController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Route;

// Redirect 301 dari URL situs lama dikelola di CMS (menu Redirect) dan
// dijalankan middleware RedirectLegacyUrls sebelum routing.

Route::get('/', [PageController::class, 'home'])->name('home');

// SEMENTARA: PDF Katalog Komik masih di server lama, yang tidak mengirim
// header CORS, jadi pdf.js tidak bisa membacanya langsung. Route ini
// meneruskannya (termasuk Range request) tanpa menyimpan apa pun.
// Setelah file ditaruh di public/files/katalog-komik.pdf, web server
// menyajikannya langsung dan route ini tidak terpakai lagi — WAJIB dilakukan
// sebelum domain dipindah, karena URL sumbernya ikut hilang bersama server lama.
Route::get('/files/katalog-komik.pdf', function (Request $request) {
    $upstream = Http::withOptions(['stream' => true, 'allow_redirects' => false])
        ->withHeaders(array_filter(['Range' => $request->header('Range')]))
        ->timeout(120)
        ->get('https://tiberman.com/katalog/katalogkomik_tbmaug_compressed.pdf');

    abort_unless(in_array($upstream->status(), [200, 206]), 502);

    $body = $upstream->toPsrResponse()->getBody();

    return response()->stream(function () use ($body) {
        while (! $body->eof() && ! connection_aborted()) {
            echo $body->read(256 * 1024);
            flush();
        }
    }, $upstream->status(), array_filter([
        'Content-Type' => 'application/pdf',
        'Content-Length' => $upstream->header('Content-Length'),
        'Content-Range' => $upstream->header('Content-Range'),
        'Accept-Ranges' => 'bytes',
    ]));
});

// URL kategori & merk mengikuti toko lama (tiberman.com) — petanya di CMS (Katalog).
Route::get('/kategori-produk/{path}', [CatalogController::class, 'category'])->where('path', '.*')->name('katalog.kategori');
Route::get('/brand/{brand}', [CatalogController::class, 'brand'])->name('katalog.brand');

Route::get('/produk', [ProductController::class, 'index'])->name('produk');
Route::get('/produk/{slug}', [ProductController::class, 'show'])->name('produk.show');
Route::get('/produk/{slug}/modal', [ProductController::class, 'modal'])->name('produk.modal');

// Slug mengikuti blog lama di tiberman.com/blog/ supaya URL artikel tetap sama.
Route::get('/blog', [BlogController::class, 'index'])->name('blog');
Route::get('/blog/category/{category}', [BlogController::class, 'index'])->name('blog.category');
Route::get('/blog/{slug}', [BlogController::class, 'show'])->name('blog.show');

Route::get('/cabang-tiberman', [PageController::class, 'superarea'])->name('superarea');

Route::get('/kontak', [PageController::class, 'contact'])->name('contact');
Route::post('/kontak', [InquiryController::class, 'store'])->middleware('throttle:5,1')->name('contact.store');

// Flipbook PDF (/katalog, /company-profile, /proposal, ...) — slug-nya dari CMS.
Route::fallback(FlipbookController::class);
