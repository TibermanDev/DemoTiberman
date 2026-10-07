<?php

namespace Database\Seeders;

use App\Models\LandingPage;
use Illuminate\Database\Seeder;

/**
 * Satu contoh landing page promo Shopee (/shortlink-shopee); landing page lain
 * (mis. /shopee-banjarbaru, /shopee-jabodetabek dari situs lama) dibuat admin
 * lewat CMS. Hanya dibuat kalau slug-nya belum ada, jadi isian dari CMS tidak
 * tertimpa. Halaman ini juga tujuan /lp/{slug}.html (lihat LinkPageController).
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
