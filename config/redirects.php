<?php

/*
| Redirect 301 dari URL situs lama (tiberman.com) yang tidak dibuat ulang.
| Kunci = path lama tanpa garis miring depan/belakang, nilai = tujuan.
| Didaftarkan sebelum route lain, jadi menang atas route dinamis seperti
| /blog/{slug} dan /kategori-produk/{path}.
*/

return [

    // Redirect lama yang sudah ada di situs lama (ditemukan lewat crawl)
    'kategori-produk/ban-10-00-20' => '/kategori-produk/ukuran-ban/ban-10-00r20',
    'kategori-produk/ban-crane' => '/kategori-produk/ban-industri/ban-crane',
    'kategori-produk/ban-reach-stacker' => '/kategori-produk/ban-industri/ban-reach-stacker',
    'tag-produk/ban-11-00-20' => '/kategori-produk/ukuran-ban/ban-11-00-20',
    'penjelasan-lengkap-tentang-pertambangan' => '/blog/penjelasan-lengkap-tentang-pertambangan',
    'blog/standar-ban-aeolus-750r16-harga-lebih-murah-tibermax-aja' => '/blog/ban-truk-750r16-harga-murah-diproduksi-di-pabrik-aeolus',

    // Arsip post situs utama — kosong (artikelnya pindah ke /blog)
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

    // Tag produk uji coba (0 produk)
    'tag-produk/tag-coba' => '/kategori-produk/semua-ban',

    // Tautan langsung ke PDF Katalog Komik yang lama
    'katalog/katalogkomik_tbmaug_compressed.pdf' => '/files/katalog-komik.pdf',

];
