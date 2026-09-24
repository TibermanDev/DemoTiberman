<?php

namespace Database\Seeders;

use App\Models\Brand;
use App\Models\CatalogUnit;
use App\Models\Product;
use App\Models\TireSize;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

/**
 * Unit, merk, ukuran, dan produk katalog — dulu di config/catalog.php dan
 * assets/js/products.js. Slug unit/merk/ukuran mengikuti toko lama
 * (tiberman.com, WooCommerce) supaya URL lamanya tetap hidup.
 */
class CatalogSeeder extends Seeder
{
    use CopiesImages;

    public function run(): void
    {
        $units = [
            'all' => ['Semua Ban', 'kategori-produk/semua-ban', false, [
                'kategori-produk/ukuran-ban',
                'kategori-produk/merek-ban',
                // merk tanpa produk di toko lama — tidak dibuatkan tombol
                'kategori-produk/merek-ban/winda',
            ]],
            'truk-bus' => ['Truk & Bus', 'kategori-produk/ban-truk', true, [
                'kategori-produk/ban-bus',
                'kategori-produk/ban-truk/ban-truk-on-the-road',
                'kategori-produk/ban-truk/ban-medium-truck',
                'kategori-produk/ban-truk/ban-heavy-truck',
                'kategori-produk/ban-light-truck',
                'kategori-produk/ban-light-truck/ban-truk-canter',
            ]],
            'mining-truck' => ['Mining Truck', 'kategori-produk/ban-truk/ban-truk-off-the-road', true, [
                'kategori-produk/ban-truk/ban-hd-rigid-dump-truck',
                'kategori-produk/ban-truk/ban-articulated-dump-truck',
            ]],
            'loader' => ['Loader', 'kategori-produk/ban-loader', true, []],
            'grader' => ['Grader', 'kategori-produk/ban-grader', true, []],
            'traktor' => ['Traktor', 'kategori-produk/ban-traktor', true, []],
            'forklift' => ['Forklift', 'kategori-produk/ban-forklift', true, [
                'kategori-produk/ban-forklift/ban-pneumatic',
                'kategori-produk/ban-forklift/ban-solid',
            ]],
            'compactor' => ['Compactor', 'kategori-produk/ban-compactor', false, ['kategori-produk/ban-compactor/ban-vibro']],
            'industri' => ['Industri', 'kategori-produk/ban-industri', false, [
                'kategori-produk/ban-industri/ban-crane',
                'kategori-produk/ban-industri/ban-reach-stacker',
            ]],
            'velg-tube' => ['Velg & Tube', 'kategori-produk/velg-truk', true, [
                'kategori-produk/velg-truk/velg-pelek-alat-berat',
                'kategori-produk/velg-truk/velg-pelek-bus',
                'kategori-produk/velg-truk/velg-pelek-pickup',
                'kategori-produk/velg-truk/velg-pelek-truk-medium',
                'kategori-produk/velg-truk/velg-truk-canter',
                'kategori-produk/steelking',
                'kategori-produk/ban-dalam',
                'kategori-produk/flap-marset',
                'kategori-produk/o-ring',
            ]],
        ];

        $unit = [];
        $order = 0;
        foreach ($units as $key => [$label, $path, $nav, $aliases]) {
            $unit[$key] = CatalogUnit::query()->updateOrCreate(['key' => $key], [
                'label' => $label, 'path' => $path, 'aliases' => $aliases, 'show_in_nav' => $nav, 'sort_order' => $order++,
            ]);
        }

        $brand = [];
        foreach (['uninest' => 'Uninest', 'tutric' => 'Tutric', 'tianli' => 'Tianli', 'hengli' => 'Hengli', 'bontyre' => 'Bontyre', 'durun' => 'Durun', 'aeolus' => 'Aeolus', 'eced' => 'Eced', 'wingood' => 'Wingood'] as $i => $name) {
            $brand[$i] = Brand::query()->updateOrCreate(['slug' => $i], ['name' => $name, 'sort_order' => count($brand)]);
        }

        foreach (['7.50-16', '10.00R20', '11.00-20', '11R22.5', '12.00-20', '12.00-24', '13.00-24', '14.00-24', '14.00-25', '14.00R20', '16.00-25', '16/70-20', '17.5-25', '18.00-25', '18.4-24', '20.5-25', '20.5/70-16', '21.00-35', '23.1-26', '23.5-25', '24.00-35', '26.5-25', '27.00-49', '29.5-25', '29.5-29', '30.00-51', '325/95-24', '33.00-51', '33.25-25', '33.25R29', '35/65-33', '45/65-45'] as $label) {
            TireSize::query()->updateOrCreate(['label' => $label], ['slug' => 'ban-'.Str::slug(str_replace(['.', '/'], '-', $label))]);
        }

        // unit => [ukuran => [[nama, cocok untuk, gambar?], ...]]
        $products = [
            'truk-bus' => [
                '7.50-16' => [['HENGLI - DR908', 'Truk Canter'], ['HENGLI - DR930', 'Truk Canter']],
                '10.00R20' => [['DURUN - YTH3', 'Truk Medium'], ['HENGLI - TIBERMAX DR930', 'Truk Medium']],
                '11.00-20' => [['UNINEST - TIBERMAX 554', 'Dumptruck'], ['WINGOOD - WG128', 'Truk Tronton']],
                '11R22.5' => [['HENGLI - DR930', 'Bus & Trailer']],
                '12.00-20' => [['UNINEST - TIBERMAX 800', 'Truk & Bus'], ['BONTYRE - BT906', 'Truk Berat']],
            ],
            'mining-truck' => [
                '14.00R20' => [['AEOLUS - AE33', 'Articulated Dump Truck']],
                '21.00-35' => [['TUTRIC - TUE402 PRO', 'Rigid Dump Truck']],
                '24.00-35' => [['AEOLUS - AE419', 'Rigid Dump Truck'], ['TUTRIC - TUE402', 'Rigid Dump Truck']],
                '27.00-49' => [['TIANLI - TUE400', 'HD Rigid Dump Truck'], ['AEOLUS - AE46', 'HD Rigid Dump Truck']],
                '33.00-51' => [['TUTRIC - TUE402', 'HD Rigid Dump Truck']],
            ],
            'loader' => [
                '20.5-25' => [['UNINEST - L3 LOADER', 'Wheel Loader']],
                '23.5-25' => [['ECED - GCA7', 'Wheel Loader'], ['TIANLI - T318', 'Wheel Loader']],
                '29.5-25' => [['TUTRIC - TUL400', 'Wheel Loader'], ['AEOLUS - AE47', 'Wheel Loader']],
                '45/65-45' => [['TUTRIC - TUL510', 'Wheel Loader Besar']],
            ],
            'grader' => [
                '13.00-24' => [['UNINEST - G2 GRADER', 'Motor Grader']],
                '14.00-24' => [['UNINEST - G2 GRADER', 'Motor Grader']],
                '17.5-25' => [['UNINEST - G2 GRADER', 'Motor Grader']],
            ],
            'traktor' => [
                '18.4-24' => [['TUTRIC - SLGII', 'Traktor Roda 4']],
                '23.1-26' => [['UNINEST - R1 TRAKTOR', 'Traktor Roda 4']],
            ],
            'forklift' => [
                '7.00-12' => [['UNINEST - ROBUST SOLID TYRE', 'Forklift 3 Ton']],
                '8.25-15' => [['UNINEST - ROBUST SOLID TYRE', 'Forklift 5 Ton']],
                '28x9-15' => [['UNINEST - ROBUST SOLID TYRE', 'Forklift Solid Tyre']],
            ],
            'compactor' => [
                '23.1-26' => [['UNINEST - VIBRO', 'Vibro Roller']],
            ],
            'industri' => [
                '16.00-25' => [['AEOLUS - AE401', 'Mobile Crane']],
                '18.00-25' => [['UNINEST - E4 PORT', 'Reach Stacker']],
            ],
            'velg-tube' => [
                'DW20 - 11.00' => [['VELG STEELKING', 'Dumptruck', 'velg-heavy.webp']],
                'DW25 - 20.5' => [['VELG LIGHT TRUCK', 'Light Truck', 'velg-light.webp']],
                'Ban Dalam 11.00R20' => [['TUBE & FLAP SET', 'Truk & Bus']],
            ],
        ];

        $sort = 0;
        foreach ($products as $unitKey => $sizes) {
            foreach ($sizes as $size => $items) {
                foreach ($items as $item) {
                    [$name, $compat] = $item;
                    $prefix = Str::lower(trim(explode(' - ', $name)[0]));

                    Product::query()->updateOrCreate(['slug' => Str::slug($name.' '.str_replace(['.', '/'], '-', $size))], [
                        'catalog_unit_id' => $unit[$unitKey]->id,
                        'brand_id' => str_contains($name, ' - ') ? ($brand[$prefix]->id ?? null) : null,
                        'name' => $name,
                        'size' => $size,
                        'compat' => $compat,
                        'image' => isset($item[2]) ? $this->img($item[2]) : null,
                        'sort_order' => $sort++,
                    ]);
                }
            }
        }

        // Satu-satunya produk yang sudah punya halaman detail lengkap (dulu /produk).
        Product::query()->where('slug', 'uninest-tibermax-800-12-00-20')->update([
            'logo' => $this->img('tibermax-logo.png'),
            'logo_light' => $this->img('tibermax-logo-light.png'),
            'hero_image' => $this->img('tire-hero-dark.webp'),
            'image' => $this->img('tire-554.webp'),
            'description' => '<b>Uninest Tibermax 800</b> Dirancang khusus untuk memberikan cengkraman maksimal tanpa kompromi. Dengan telapak yang lebih tebal, ban ini nggak cuma tangguh, tapi juga punya umur pakai yang lebih panjang.',
            'features' => json_encode([
                ['title' => "Sidewall\nKuat", 'body' => 'Konstruksi all-steel radial dengan bahu ban lebih tebal, tahan benturan batu dan beban lateral di jalur tambang.', 'image' => $this->img('tyre-90.png'), 'contain' => true],
                ['title' => "Telapak\nTebal", 'body' => 'Kedalaman tapak 25.5 mm dengan blok besar memberi traksi maksimal dan umur pakai yang jauh lebih panjang.', 'image' => $this->img('tire-tread.webp'), 'contain' => false],
            ]),
            'pairs' => json_encode([
                ['title' => 'Dump Truck', 'image' => $this->img('dumptruck.webp')],
                ['title' => 'Off-Road', 'image' => $this->img('tire-tread.webp')],
                ['title' => 'Muatan Berat', 'image' => $this->img('plb-stock.webp')],
            ]),
            'gallery' => json_encode([
                $this->img('tyre-preview.png'),
                $this->img('tyre-diameter.png'),
                $this->img('tyre-90.png'),
                $this->img('tapak-ban.png'),
            ]),
            'specs' => json_encode([
                ['label' => 'Ply Rating', 'value' => '16PR'],
                ['label' => 'Overall Diameter', 'value' => '1500 mm'],
                ['label' => 'Tread Depth', 'value' => '25.5 mm'],
                ['label' => 'Max Load', 'value' => '6150 kg'],
                ['label' => 'Standard Rim', 'value' => 'DW20'],
                ['label' => 'Max Speed', 'value' => '10 km/jam'],
                ['label' => 'Section Width', 'value' => '595 mm'],
                ['label' => 'Pressure', 'value' => '38 Psi'],
            ]),
            'available_sizes' => json_encode(['11.00R20', '12.00R20', '12.00R24', '14.00R25']),
            'ecatalog_url' => '#',
            'flashcard_url' => '#',
            'meta_description' => 'Uninest Tibermax 800: ban radial all-steel dengan telapak lebih tebal, sidewall kuat, dan umur pakai lebih panjang untuk dump truck, off-road, dan muatan berat.',
        ]);
    }
}
