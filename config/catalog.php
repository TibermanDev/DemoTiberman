<?php

/*
| Peta katalog -> slug toko lama (tiberman.com, WooCommerce) supaya semua URL
| kategori, merk, dan ukuran yang lama tetap hidup di katalog baru.
|
| - units:  tombol "Telusuri berdasarkan unit". 'path' = URL yang dipakai
|           tombolnya; 'aliases' = kategori lama lain yang membuka unit yang
|           sama (tetap dirender langsung, bukan redirect).
| - brands: tombol "Telusuri berdasarkan Merk" -> /brand/{slug}; alias
|           /kategori-produk/merek-ban/{slug} membuka merk yang sama.
| - sizes:  chip ukuran -> /kategori-produk/ukuran-ban/{slug}. Nilainya label
|           ukuran persis seperti di data produk (assets/js/products.js).
|
| Unit 'all' = semua unit sekaligus (dipakai halaman merk & ukuran).
*/

return [

    'units' => [
        'all' => [
            'label' => 'Semua Ban',
            'path' => 'kategori-produk/semua-ban',
            'aliases' => [
                'kategori-produk/ukuran-ban',
                'kategori-produk/merek-ban',
                // merk tanpa produk di toko lama — tidak dibuatkan tombol
                'kategori-produk/merek-ban/winda',
            ],
        ],
        'truk-bus' => [
            'label' => 'Truk & Bus',
            'path' => 'kategori-produk/ban-truk',
            'aliases' => [
                'kategori-produk/ban-bus',
                'kategori-produk/ban-truk/ban-truk-on-the-road',
                'kategori-produk/ban-truk/ban-medium-truck',
                'kategori-produk/ban-truk/ban-heavy-truck',
                'kategori-produk/ban-light-truck',
                'kategori-produk/ban-light-truck/ban-truk-canter',
            ],
        ],
        'mining-truck' => [
            'label' => 'Mining Truck',
            'path' => 'kategori-produk/ban-truk/ban-truk-off-the-road',
            'aliases' => [
                'kategori-produk/ban-truk/ban-hd-rigid-dump-truck',
                'kategori-produk/ban-truk/ban-articulated-dump-truck',
            ],
        ],
        'loader' => [
            'label' => 'Loader',
            'path' => 'kategori-produk/ban-loader',
            'aliases' => [],
        ],
        'grader' => [
            'label' => 'Grader',
            'path' => 'kategori-produk/ban-grader',
            'aliases' => [],
        ],
        'traktor' => [
            'label' => 'Traktor',
            'path' => 'kategori-produk/ban-traktor',
            'aliases' => [],
        ],
        'forklift' => [
            'label' => 'Forklift',
            'path' => 'kategori-produk/ban-forklift',
            'aliases' => [
                'kategori-produk/ban-forklift/ban-pneumatic',
                'kategori-produk/ban-forklift/ban-solid',
            ],
        ],
        'compactor' => [
            'label' => 'Compactor',
            'path' => 'kategori-produk/ban-compactor',
            'aliases' => ['kategori-produk/ban-compactor/ban-vibro'],
        ],
        'industri' => [
            'label' => 'Industri',
            'path' => 'kategori-produk/ban-industri',
            'aliases' => [
                'kategori-produk/ban-industri/ban-crane',
                'kategori-produk/ban-industri/ban-reach-stacker',
            ],
        ],
        'velg-tube' => [
            'label' => 'Velg & Tube',
            'path' => 'kategori-produk/velg-truk',
            'aliases' => [
                'kategori-produk/velg-truk/velg-pelek-alat-berat',
                'kategori-produk/velg-truk/velg-pelek-bus',
                'kategori-produk/velg-truk/velg-pelek-pickup',
                'kategori-produk/velg-truk/velg-pelek-truk-medium',
                'kategori-produk/velg-truk/velg-truk-canter',
                'kategori-produk/steelking',
                'kategori-produk/ban-dalam',
                'kategori-produk/flap-marset',
                'kategori-produk/o-ring',
            ],
        ],
    ],

    'brands' => [
        'uninest' => 'Uninest',
        'tutric' => 'Tutric',
        'tianli' => 'Tianli',
        'hengli' => 'Hengli',
        'bontyre' => 'Bontyre',
        'durun' => 'Durun',
        'aeolus' => 'Aeolus',
        'eced' => 'Eced',
        'wingood' => 'Wingood',
    ],

    'sizes' => [
        'ban-7-50-16' => '7.50-16',
        'ban-10-00r20' => '10.00R20',
        'ban-11-00-20' => '11.00-20',
        'ban-11r22-5' => '11R22.5',
        'ban-12-00-20' => '12.00-20',
        'ban-12-00-24' => '12.00-24',
        'ban-13-00-24' => '13.00-24',
        'ban-14-00-24' => '14.00-24',
        'ban-14-00-25' => '14.00-25',
        'ban-14-00r20' => '14.00R20',
        'ban-16-00-25' => '16.00-25',
        'ban-16-70-20' => '16/70-20',
        'ban-17-5-25' => '17.5-25',
        'ban-18-00-25' => '18.00-25',
        'ban-18-4-24' => '18.4-24',
        'ban-20-5-25' => '20.5-25',
        'ban-20-5-70-16' => '20.5/70-16',
        'ban-21-00-35' => '21.00-35',
        'ban-23-1-26' => '23.1-26',
        'ban-23-5-25' => '23.5-25',
        'ban-24-00-35' => '24.00-35',
        'ban-26-5-25' => '26.5-25',
        'ban-27-00-49' => '27.00-49',
        'ban-29-5-25' => '29.5-25',
        'ban-29-5-29' => '29.5-29',
        'ban-30-00-51' => '30.00-51',
        'ban-325-95-24' => '325/95-24',
        'ban-33-00-51' => '33.00-51',
        'ban-33-25-25' => '33.25-25',
        'ban-33-25r29' => '33.25R29',
        'ban-35-65-33' => '35/65-33',
        'ban-45-65-45' => '45/65-45',
    ],

];
