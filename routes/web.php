<?php

use App\Http\Controllers\AnalyticsController;
use App\Http\Controllers\BlogController;
use App\Http\Controllers\CatalogController;
use App\Http\Controllers\InquiryController;
use App\Http\Controllers\LinkPageController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\SeoController;
use App\Http\Controllers\SlugPageController;
use Illuminate\Foundation\Http\Middleware\PreventRequestForgery;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Route;

// Redirect 301 dari URL situs lama dikelola di CMS (menu Redirect) dan
// dijalankan middleware RedirectLegacyUrls sebelum routing.

Route::get('/', [PageController::class, 'home'])->name('home');

// Dibangun dari isi CMS. public/robots.txt sengaja dihapus supaya route ini
// yang melayani (web server menyajikan file statis lebih dulu).
Route::get('/sitemap.xml', [SeoController::class, 'sitemap'])->name('sitemap');
Route::get('/robots.txt', [SeoController::class, 'robots'])->name('robots');

// Beacon analitik pengunjung (menu Analitik Pengunjung di CMS). Tanpa token
// CSRF karena dikirim navigator.sendBeacon; isinya cuma dicatat, tidak
// mengubah apa pun, dan dibatasi 120 kiriman/menit per IP.
Route::post('/_a', AnalyticsController::class)
    ->withoutMiddleware(PreventRequestForgery::class)
    ->middleware('throttle:120,1')
    ->name('analytics.collect');

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
// Tag SEO toko lama: halaman katalog berisi produk pilihan (menu Tag Produk di CMS).
Route::get('/tag-produk/{slug}', [CatalogController::class, 'tag'])->name('katalog.tag');

Route::get('/produk', [ProductController::class, 'index'])->name('produk');
Route::get('/produk/{slug}', [ProductController::class, 'show'])->name('produk.show');
Route::get('/produk/{slug}/modal', [ProductController::class, 'modal'])->name('produk.modal');

// Slug mengikuti blog lama di tiberman.com/blog/ supaya URL artikel tetap sama.
Route::get('/blog', [BlogController::class, 'index'])->name('blog');
Route::get('/blog/category/{category}', [BlogController::class, 'index'])->name('blog.category');
Route::get('/blog/{slug}', [BlogController::class, 'show'])->name('blog.show');

Route::get('/cabang-tiberman', [PageController::class, 'superarea'])->name('superarea');

// Alamat mengikuti situs lama (tiberman.com/after-sales-service/); /after-sales
// hanya pintasan yang diarahkan ke sana.
Route::get('/after-sales-service', [PageController::class, 'aftersales'])->name('aftersales');
Route::permanentRedirect('/after-sales', '/after-sales-service');

Route::get('/tentang-kami', [PageController::class, 'about'])->name('about');

// Linktree bio media sosial: /lp/ dan /lp/{slug}.html (URL lama) — menu Linktree (/lp) di CMS.
Route::get('/lp/{page?}', LinkPageController::class)->where('page', '[A-Za-z0-9_-]+\.html')->name('linktree');

Route::get('/privacy-policy', [PageController::class, 'privacy'])->name('privacy');
Route::get('/disclaimer', [PageController::class, 'disclaimer'])->name('disclaimer');

Route::get('/kontak', [PageController::class, 'contact'])->name('contact');
Route::post('/kontak', [InquiryController::class, 'store'])->middleware('throttle:5,1')->name('contact.store');

// Landing page promo (/shopee-banjarbaru, ...) lalu flipbook PDF (/katalog,
// /company-profile, ...) — slug keduanya dari CMS.
Route::fallback(SlugPageController::class);
