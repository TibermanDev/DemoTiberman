<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\View\View;

class ProductController extends Controller
{
    /**
     * /produk lama: tampilkan produk pertama yang sudah punya halaman detail
     * (deskripsi terisi), supaya tautan lama tetap mendarat di halaman produk.
     */
    public function index(): View
    {
        $product = Product::query()->active()->whereNotNull('description')->orderBy('sort_order')->first()
            ?? Product::query()->active()->orderBy('sort_order')->firstOrFail();

        return $this->show($product->slug);
    }

    public function show(string $slug): View
    {
        return view('produk', ['product' => $this->find($slug)]);
    }

    /** Isi slide modal detail di katalog, diambil main.js saat kartu diklik. */
    public function modal(string $slug): View
    {
        return view('partials.product-modal', ['product' => $this->find($slug)]);
    }

    private function find(string $slug): Product
    {
        return Product::query()->active()->where('slug', $slug)->with(['unit', 'brand'])->firstOrFail();
    }
}
