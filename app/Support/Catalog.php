<?php

namespace App\Support;

use App\Models\Brand;
use App\Models\CatalogUnit;
use App\Models\Product;
use App\Models\TireSize;
use Illuminate\Support\Collection;

/**
 * Menerjemahkan URL toko lama (unit, merk, ukuran di CMS) menjadi keadaan
 * awal filter katalog, dan sebaliknya menyediakan URL tiap tombol/chip untuk JS.
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
        return once(function () {
            $paths = [];

            foreach (self::units() as $u) {
                foreach ([$u->path, ...($u->aliases ?? [])] as $path) {
                    $paths[trim($path, '/')] = ['unit' => $u->key, 'brand' => 'all', 'size' => 'all'];
                }
            }

            foreach (self::brands() as $brand) {
                $state = ['unit' => CatalogUnit::ALL, 'brand' => $brand->slug, 'size' => 'all'];
                $paths['brand/'.$brand->slug] = $state;
                $paths['kategori-produk/merek-ban/'.$brand->slug] = $state;
            }

            foreach (TireSize::query()->get() as $size) {
                $paths['kategori-produk/ukuran-ban/'.$size->slug] = ['unit' => CatalogUnit::ALL, 'brand' => 'all', 'size' => $size->label];
            }

            return $paths;
        });
    }

    public static function stateFor(string $path): ?array
    {
        return self::paths()[trim($path, '/')] ?? null;
    }

    /** @return Collection<int, CatalogUnit> */
    public static function units(): Collection
    {
        return once(fn () => CatalogUnit::query()->ordered()->get());
    }

    /** @return Collection<int, Brand> */
    public static function brands(): Collection
    {
        return once(fn () => Brand::query()->ordered()->get());
    }

    /** Judul halaman untuk keadaan filter tertentu. */
    public static function title(array $state): string
    {
        if ($state['brand'] !== 'all') {
            return 'Ban '.self::brands()->firstWhere('slug', $state['brand'])?->name;
        }
        if ($state['size'] !== 'all') {
            return 'Ban '.$state['size'];
        }

        return self::units()->firstWhere('key', $state['unit'])?->label ?? '';
    }

    /**
     * Data produk untuk assets/js/main.js: unit => [{size, items: [...]}],
     * dikelompokkan per ukuran dengan urutan kemunculan pertamanya.
     */
    public static function products(): array
    {
        $out = [];

        $products = Product::query()->active()->with(['unit', 'brand'])
            ->orderBy('sort_order')->orderBy('id')->get();

        foreach ($products->groupBy(fn (Product $p) => $p->unit->key) as $unit => $items) {
            $out[$unit] = $items->groupBy('size')->map(fn ($group, $size) => [
                'size' => (string) $size,
                'items' => $group->map(fn (Product $p) => [
                    'name' => $p->name,
                    'compat' => $p->compat,
                    'img' => $p->imageUrl(),
                    'brand' => $p->brand?->slug,
                    'url' => $p->url(),
                ])->values(),
            ])->values()->all();
        }

        return $out;
    }

    /**
     * URL kanonik tiap tombol & chip untuk main.js, plus peta path => keadaan
     * untuk tombol Back/Forward.
     */
    public static function forJs(): array
    {
        $paths = [];
        foreach (self::paths() as $path => $state) {
            $paths['/'.$path] = $state;
        }

        return [
            'units' => self::units()->mapWithKeys(fn (CatalogUnit $u) => [$u->key => $u->url()])->all(),
            'brands' => self::brands()->mapWithKeys(fn (Brand $b) => [$b->slug => '/brand/'.$b->slug])->all(),
            'sizes' => TireSize::query()->get()->mapWithKeys(fn (TireSize $s) => [$s->label => '/kategori-produk/ukuran-ban/'.$s->slug])->all(),
            'paths' => $paths,
        ];
    }
}
