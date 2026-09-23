<?php

namespace App\Support;

/**
 * Menerjemahkan URL toko lama (lihat config/catalog.php) menjadi keadaan awal
 * filter katalog, dan sebaliknya menyediakan URL tiap tombol/chip untuk JS.
 */
class Catalog
{
    /**
     * Semua path lama (tanpa garis miring depan/belakang) => keadaan filter.
     *
     * @return array<string, array{unit: string, brand: string, size: string}>
     */
    public static function paths(): array
    {
        $paths = [];

        foreach (config('catalog.units') as $unit => $u) {
            foreach ([$u['path'], ...$u['aliases']] as $path) {
                $paths[$path] = ['unit' => $unit, 'brand' => 'all', 'size' => 'all'];
            }
        }

        foreach (array_keys(config('catalog.brands')) as $brand) {
            $state = ['unit' => 'all', 'brand' => $brand, 'size' => 'all'];
            $paths['brand/'.$brand] = $state;
            $paths['kategori-produk/merek-ban/'.$brand] = $state;
        }

        foreach (config('catalog.sizes') as $slug => $label) {
            $paths['kategori-produk/ukuran-ban/'.$slug] = ['unit' => 'all', 'brand' => 'all', 'size' => $label];
        }

        return $paths;
    }

    public static function stateFor(string $path): ?array
    {
        return self::paths()[trim($path, '/')] ?? null;
    }

    /** Judul halaman untuk keadaan filter tertentu. */
    public static function title(array $state): string
    {
        if ($state['brand'] !== 'all') {
            return 'Ban '.config('catalog.brands')[$state['brand']];
        }
        if ($state['size'] !== 'all') {
            return 'Ban '.$state['size'];
        }

        return config('catalog.units')[$state['unit']]['label'];
    }

    /**
     * Data untuk assets/js/main.js: URL kanonik tiap tombol & chip, plus
     * peta path => keadaan untuk tombol Back/Forward.
     */
    public static function forJs(): array
    {
        $paths = [];
        foreach (self::paths() as $path => $state) {
            $paths['/'.$path] = $state;
        }

        return [
            'units' => array_map(fn ($u) => '/'.$u['path'], config('catalog.units')),
            'brands' => collect(config('catalog.brands'))->map(fn ($_, $slug) => '/brand/'.$slug)->all(),
            'sizes' => collect(config('catalog.sizes'))->flip()->map(fn ($slug) => '/kategori-produk/ukuran-ban/'.$slug)->all(),
            'paths' => $paths,
        ];
    }
}
