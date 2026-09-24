<?php

namespace Database\Seeders;

use App\Models\Flipbook;
use App\Models\Redirect;
use App\Models\Translation;
use Illuminate\Database\Seeder;

/**
 * Flipbook PDF, redirect URL lama, dan kamus terjemahan — dulu di
 * config/flipbooks.php, config/redirects.php, dan assets/js/i18n.js.
 */
class SiteSeeder extends Seeder
{
    public function run(): void
    {
        $flipbooks = [
            // PDF-nya (±25 MB) terlalu besar untuk jsDelivr; selama file belum
            // ditaruh di public/files/, route proxy di routes/web.php
            // meneruskannya dari server lama.
            'katalog' => ['Katalog Komik', '/files/katalog-komik.pdf'],
            'company-profile' => ['Company Profile', 'https://cdn.jsdelivr.net/gh/TibermanDev/bizpro/compro2026.pdf'],
            'proposal' => ['Proposal Bisnis', 'https://cdn.jsdelivr.net/gh/TibermanDev/bizpro/TBIZPRO2025-V5.pdf'],
        ];
        foreach ($flipbooks as $slug => [$title, $url]) {
            Flipbook::query()->updateOrCreate(['slug' => $slug], ['title' => $title, 'pdf_url' => $url]);
        }

        $redirects = [
            'kategori-produk/ban-10-00-20' => '/kategori-produk/ukuran-ban/ban-10-00r20',
            'kategori-produk/ban-crane' => '/kategori-produk/ban-industri/ban-crane',
            'kategori-produk/ban-reach-stacker' => '/kategori-produk/ban-industri/ban-reach-stacker',
            'tag-produk/ban-11-00-20' => '/kategori-produk/ukuran-ban/ban-11-00-20',
            'penjelasan-lengkap-tentang-pertambangan' => '/blog/penjelasan-lengkap-tentang-pertambangan',
            'blog/standar-ban-aeolus-750r16-harga-lebih-murah-tibermax-aja' => '/blog/ban-truk-750r16-harga-murah-diproduksi-di-pabrik-aeolus',
            'kategori/alat-berat' => '/blog/category/alat-berat',
            'kategori/pertambangan' => '/blog/category/pertambangan',
            'kategori/info-produk' => '/blog/category/info-produk',
            'kategori/informasi-umum' => '/blog/category/informasi-umum',
            'kategori/pengetahuan-ban' => '/blog/category/pengetahuan-ban',
            'kategori/tips-dan-trik' => '/blog/category/tips-dan-trik',
            'kategori/artikel-form' => '/blog',
            'kategori/info-brand' => '/blog',
            'kategori/semua-artikel' => '/blog',
            'kategori/uncategorized' => '/blog',
            'tag/semua-ban' => '/blog',
            'tag/tes' => '/blog',
            'author/admintiberman' => '/blog',
            'author/fehabutar' => '/blog',
            'tag-produk/tag-coba' => '/kategori-produk/semua-ban',
            'katalog/katalogkomik_tbmaug_compressed.pdf' => '/files/katalog-komik.pdf',
        ];
        foreach ($redirects as $from => $to) {
            Redirect::query()->updateOrCreate(['from_path' => $from], ['to_path' => $to]);
        }

        $rows = json_decode(file_get_contents(__DIR__.'/data/translations.json'), true);
        foreach ($rows as $row) {
            $source = Translation::normalize($row['source']);
            Translation::query()->updateOrCreate(['source_hash' => sha1($source)], [
                'source' => $source, 'en' => $row['en'], 'zh' => $row['zh'],
            ]);
        }
    }
}
