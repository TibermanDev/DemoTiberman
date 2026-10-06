<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\ProductTag;
use Illuminate\Database\Seeder;

/**
 * Satu contoh tag produk SEO. Tag lain dimasukkan admin sendiri lewat menu
 * Katalog > Tag Produk (SEO) sesuai daftar URL lama. Hanya dibuat kalau
 * slug-nya belum ada, jadi isian dari CMS tidak tertimpa.
 */
class ProductTagSeeder extends Seeder
{
    public function run(): void
    {
        $tag = ProductTag::query()->firstOrCreate(['slug' => 'ban-dump-truck-terex-tr60'], [
            'heading' => 'ban dumptruk terex TR60',
            'meta_title' => 'Ban Dump Truck Terex TR60 — Tiberman',
            'is_active' => true,
        ]);

        // Contoh: produk aktif yang menyebut dump truck. Di CMS produknya
        // dipilih manual per tag.
        if ($tag->wasRecentlyCreated || $tag->products()->doesntExist()) {
            $tag->products()->sync(
                Product::query()->active()
                    ->where(fn ($q) => $q->where('compat', 'like', '%dump%')
                        ->orWhere('description', 'like', '%dump truck%')
                        ->orWhere('meta_description', 'like', '%dump truck%'))
                    ->pluck('id')
            );
        }
    }
}
