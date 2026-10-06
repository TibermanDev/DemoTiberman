<?php

namespace Database\Seeders;

use App\Models\LinkPage;
use App\Models\Setting;
use Illuminate\Database\Seeder;

/**
 * Linktree /lp/ dan /lp/{slug}.html dari situs lama (bio media sosial).
 * Isi aslinya tidak diketahui, jadi tombolnya diambil dari kontak &
 * marketplace di Pengaturan Situs plus satu tombol sesuai topik slug —
 * silakan disesuaikan di CMS. Hanya dibuat kalau slug-nya belum ada.
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

        $topik = [
            LinkPage::INDEX => null,
            '1' => null,
            'bus' => ['label' => 'Ban Truk & Bus', 'url' => '/kategori-produk/ban-truk', 'icon' => 'web', 'highlight' => false],
            'velg' => ['label' => 'Velg & Tube Truk', 'url' => '/kategori-produk/velg-truk', 'icon' => 'web', 'highlight' => false],
            't318' => ['label' => 'Ban Truk (T318)', 'url' => '/kategori-produk/ban-truk', 'icon' => 'web', 'highlight' => false],
        ];

        foreach ($topik as $slug => $extra) {
            // tombol topik ditaruh tepat setelah WhatsApp
            $links = $extra ? [$umum[0], $extra, ...array_slice($umum, 1)] : $umum;

            LinkPage::query()->firstOrCreate(['slug' => (string) $slug], [
                'title' => 'Tiberman',
                'subtitle' => 'One Stop Tyre Solutions — ban truk, ban alat berat, velg & tube. Dikirim dari 15 SuperArea di seluruh Indonesia.',
                'links' => $links,
                'is_active' => true,
            ]);
        }
    }
}
