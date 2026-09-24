<?php

namespace App\Http\Controllers;

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

    private function page(string $path): View
    {
        $state = Catalog::stateFor($path);
        abort_if($state === null, 404);

        return view('katalog', ['state' => $state]);
    }
}
