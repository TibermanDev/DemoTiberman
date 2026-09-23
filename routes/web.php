<?php

use App\Support\Catalog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Route;

// Redirect 301 dari URL situs lama — didaftarkan paling awal supaya menang
// atas route dinamis di bawah. Daftarnya di config/redirects.php.
foreach (config('redirects') as $from => $to) {
    Route::permanentRedirect($from, $to);
}

Route::get('/', function () {
    return view('home');
})->name('home');

// Flipbook PDF (/katalog, /company-profile, /proposal) — config/flipbooks.php.
foreach (config('flipbooks') as $slug => $book) {
    Route::get('/'.$slug, fn () => view('flipbook', ['book' => $book]))->name('flipbook.'.$slug);
}

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

// URL kategori & merk mengikuti toko lama (tiberman.com) — petanya di config/catalog.php.
$catalogPage = function (string $path) {
    $state = Catalog::stateFor($path);
    abort_if($state === null, 404);

    return view('katalog', ['state' => $state]);
};

Route::get('/kategori-produk/{path}', fn (string $path) => $catalogPage('kategori-produk/'.$path))->where('path', '.*')->name('katalog.kategori');

Route::get('/brand/{brand}', fn (string $brand) => $catalogPage('brand/'.$brand))->name('katalog.brand');

Route::get('/produk', function () {
    return view('produk');
})->name('produk');

// Slug mengikuti blog lama di tiberman.com/blog/ supaya URL artikel tetap sama.
Route::get('/blog', function () {
    return view('news');
})->name('blog');

Route::get('/blog/category/{category}', function (string $category) {
    return view('news', ['category' => $category]);
})->whereIn('category', array_keys(config('blog.categories')))->name('blog.category');

Route::get('/blog/{slug}', function (string $slug) {
    return view('news-detail');
})->name('blog.show');

Route::get('/cabang-tiberman', function () {
    return view('superarea');
})->name('superarea');

Route::get('/kontak', function () {
    return view('contact');
})->name('contact');
