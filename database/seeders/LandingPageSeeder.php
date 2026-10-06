<?php

namespace Database\Seeders;

use App\Models\LandingPage;
use Illuminate\Database\Seeder;

/**
 * Landing page promo Shopee dari situs lama. Hanya dibuat kalau slug-nya
 * belum ada, jadi isian dari CMS tidak tertimpa saat seeder dijalankan ulang.
 * Foto kartu masih gambar produk bawaan — ganti dengan foto asli di CMS.
 */
class LandingPageSeeder extends Seeder
{
    use CopiesImages;

    public function run(): void
    {
        $cards = [
            ['title' => 'Ban truk Canter', 'subtitle' => 'Ukuran 7.50R16', 'image' => $this->img('tire-554.webp'), 'url' => null, 'button_label' => 'Lihat Produk >>'],
            ['title' => 'Ban truk', 'subtitle' => 'Ukuran 10.00R20', 'image' => $this->img('tbr-tyre.webp'), 'url' => null, 'button_label' => 'Lihat Produk >>'],
            ['title' => 'Velg truk Canter', 'subtitle' => 'Ukuran 12mm Oval Ventilation', 'image' => $this->img('velg-light.webp'), 'url' => null, 'button_label' => 'Lihat Produk >>'],
        ];

        $pages = [
            'shopee-banjarbaru' => ['Shopee Tiberman Banjarbaru', 'Beli ban dan velg truk Tiberman di Shopee untuk wilayah Banjarbaru. Nikmati voucher, promo, dan cashback-nya.'],
            'shopee-jabodetabek' => ['Shopee Tiberman Jabodetabek', 'Beli ban dan velg truk Tiberman di Shopee untuk wilayah Jabodetabek. Nikmati voucher, promo, dan cashback-nya.'],
            'shortlink-shopee' => ['Shopee Tiberman', 'Beli ban dan velg truk Tiberman di Shopee. Nikmati voucher, promo, dan cashback-nya.'],
        ];

        foreach ($pages as $slug => [$title, $description]) {
            LandingPage::query()->firstOrCreate(['slug' => $slug], [
                'title' => $title,
                'heading' => "Yuk beli ban dan velg\nTiberman di",
                'subheading' => 'Nikmati Voucher, Promo dan cashbacknya',
                'cards' => $cards,
                'meta_title' => $title.' — Ban & Velg Truk',
                'meta_description' => $description,
                'is_active' => true,
            ]);
        }
    }
}
