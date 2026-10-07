<?php

namespace Database\Seeders;

use App\Models\LinkPage;
use App\Models\Setting;
use Illuminate\Database\Seeder;

/**
 * Linktree /lp/ (bio media sosial). Isi aslinya tidak diketahui, jadi
 * tombolnya diambil dari kontak & marketplace di Pengaturan Situs — silakan
 * disesuaikan di CMS. Hanya dibuat kalau belum ada. Alamat lama
 * /lp/{slug}.html tidak punya halaman sendiri: diarahkan ke landing page
 * promo (lihat LinkPageController).
 */
class LinkPageSeeder extends Seeder
{
    public function run(): void
    {
        $site = Setting::group('site');
        $wa = 'https://wa.me/'.($site['whatsapp'] ?? '');

        $umum = array_values(array_filter([
            ['label' => 'Chat WhatsApp Tiberman', 'url' => $wa, 'icon' => 'whatsapp', 'highlight' => true],
            ['label' => 'Lihat Semua Produk', 'url' => '/kategori-produk/semua-ban', 'icon' => 'web', 'highlight' => false],
            ['label' => 'Katalog Produk (PDF)', 'url' => '/katalog', 'icon' => 'catalog', 'highlight' => false],
            ['label' => 'SuperArea Terdekat', 'url' => '/cabang-tiberman', 'icon' => 'map', 'highlight' => false],
            ['label' => 'Belanja di Shopee', 'url' => data_get($site, 'marketplace.shopee'), 'icon' => 'shopee', 'highlight' => false],
            ['label' => 'Belanja di Tokopedia', 'url' => data_get($site, 'marketplace.tokopedia'), 'icon' => 'tokopedia', 'highlight' => false],
            ['label' => 'Instagram', 'url' => data_get($site, 'social.instagram'), 'icon' => 'instagram', 'highlight' => false],
            ['label' => 'TikTok', 'url' => data_get($site, 'social.tiktok'), 'icon' => 'tiktok', 'highlight' => false],
            ['label' => 'YouTube', 'url' => data_get($site, 'social.youtube'), 'icon' => 'youtube', 'highlight' => false],
        ], fn ($l) => filled($l['url']) && $l['url'] !== '#')); // tautan '#' = belum diisi di Pengaturan Situs

        LinkPage::query()->firstOrCreate(['slug' => LinkPage::INDEX], [
            'title' => 'Tiberman',
            'subtitle' => 'One Stop Tyre Solutions — ban truk, ban alat berat, velg & tube. Dikirim dari 15 SuperArea di seluruh Indonesia.',
            'links' => $umum,
            'is_active' => true,
        ]);
    }
}
