<?php

namespace App\Http\Controllers;

use App\Models\ProductTag;
use App\Support\Catalog;
use Illuminate\View\View;

/** URL kategori & merk mengikuti toko lama (tiberman.com) — petanya dari CMS. */
class CatalogController extends Controller
{
    public function category(string $path): View
    {
        return $this->page('kategori-produk/'.$path);
    }

    public function brand(string $brand): View
    {
        return $this->page('brand/'.$brand);
    }

    /**
     * Tag SEO (/tag-produk/{slug}): tampilan katalog yang hanya berisi produk
     * pilihan tag. Sidebar tetap sama dan tag tidak ditambahkan ke sana.
     */
    public function tag(string $slug): View
    {
        $tag = ProductTag::query()->where('is_active', true)->where('slug', $slug)->firstOrFail();

        return view('katalog', [
            'state' => ['unit' => 'all', 'brand' => 'all', 'size' => 'all'],
            'tag' => $tag,
        ]);
    }

    private function page(string $path): View
    {
        $state = Catalog::stateFor($path);
        abort_if($state === null, 404);

        return view('katalog', ['state' => $state]);
    }
}
