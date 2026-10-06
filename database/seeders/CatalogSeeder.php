<?php

namespace Database\Seeders;

use App\Models\Brand;
use App\Models\CatalogUnit;
use App\Models\Product;
use App\Models\TireSize;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

/**
 * Unit, merk, dan ukuran katalog — dulu di config/catalog.php. Slug-nya
 * mengikuti toko lama (tiberman.com, WooCommerce) supaya URL lamanya tetap
 * hidup. Ditambah satu produk contoh; produk lain diisi lewat CMS dengan
 * slug produk tiberman.com.
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

        $order = 0;
        foreach ($units as $key => [$label, $path, $nav, $aliases]) {
            CatalogUnit::query()->updateOrCreate(['key' => $key], [
                'label' => $label, 'path' => $path, 'aliases' => $aliases, 'show_in_nav' => $nav, 'sort_order' => $order++,
            ]);
        }

        $order = 0;
        foreach (['uninest' => 'Uninest', 'tutric' => 'Tutric', 'tianli' => 'Tianli', 'hengli' => 'Hengli', 'bontyre' => 'Bontyre', 'durun' => 'Durun', 'aeolus' => 'Aeolus', 'eced' => 'Eced', 'wingood' => 'Wingood'] as $slug => $name) {
            Brand::query()->updateOrCreate(['slug' => $slug], ['name' => $name, 'sort_order' => $order++]);
        }

        foreach (['7.50-16', '10.00R20', '11.00-20', '11R22.5', '12.00-20', '12.00-24', '13.00-24', '14.00-24', '14.00-25', '14.00R20', '16.00-25', '16/70-20', '17.5-25', '18.00-25', '18.4-24', '20.5-25', '20.5/70-16', '21.00-35', '23.1-26', '23.5-25', '24.00-35', '26.5-25', '27.00-49', '29.5-25', '29.5-29', '30.00-51', '325/95-24', '33.00-51', '33.25-25', '33.25R29', '35/65-33', '45/65-45'] as $label) {
            TireSize::query()->updateOrCreate(['label' => $label], ['slug' => 'ban-'.Str::slug(str_replace(['.', '/'], '-', $label))]);
        }

        // Satu produk contoh yang lengkap dengan halaman detailnya. Slug-nya produk
        // tiberman.com (/product/... dialihkan ke sini lewat SiteSeeder); spesifikasi
        // di bawah hanya contoh, koreksi lewat CMS. Produk lain diinput lewat CMS.
        $truck = CatalogUnit::query()->where('key', 'truk-bus')->firstOrFail();
        $uninest = Brand::query()->where('slug', 'uninest')->firstOrFail();

        Product::query()->updateOrCreate(['slug' => 'uninest-tibermax-851-12-00r20-20pr'], [
            'catalog_unit_id' => $truck->id,
            'brand_id' => $uninest->id,
            'name' => 'UNINEST - TIBERMAX 851',
            'size' => '12.00R20',
            'compat' => 'Truk & Bus',
            'sort_order' => 0,
            'logo' => $this->img('tibermax-logo.png'),
            'logo_light' => $this->img('tibermax-logo-light.png'),
            'hero_image' => $this->img('tire-hero-dark.webp'),
            'image' => $this->img('tire-554.webp'),
            'description' => '<b>Uninest Tibermax 851</b> Dirancang khusus untuk memberikan cengkraman maksimal tanpa kompromi. Dengan telapak yang lebih tebal, ban ini nggak cuma tangguh, tapi juga punya umur pakai yang lebih panjang.',
            'features' => [
                ['title' => "Sidewall\nKuat", 'body' => 'Konstruksi all-steel radial dengan bahu ban lebih tebal, tahan benturan batu dan beban lateral di jalur tambang.', 'image' => $this->img('tyre-90.png'), 'contain' => true],
                ['title' => "Telapak\nTebal", 'body' => 'Kedalaman tapak 25.5 mm dengan blok besar memberi traksi maksimal dan umur pakai yang jauh lebih panjang.', 'image' => $this->img('tire-tread.webp'), 'contain' => false],
            ],
            'pairs' => [
                ['title' => 'Dump Truck', 'image' => $this->img('dumptruck.webp')],
                ['title' => 'Off-Road', 'image' => $this->img('tire-tread.webp')],
                ['title' => 'Muatan Berat', 'image' => $this->img('plb-stock.webp')],
            ],
            'gallery' => [
                $this->img('tyre-preview.png'),
                $this->img('tyre-diameter.png'),
                $this->img('tyre-90.png'),
                $this->img('tapak-ban.png'),
            ],
            'specs' => [
                ['label' => 'Ply Rating', 'value' => '20PR'],
                ['label' => 'Overall Diameter', 'value' => '1500 mm'],
                ['label' => 'Tread Depth', 'value' => '25.5 mm'],
                ['label' => 'Max Load', 'value' => '6150 kg'],
                ['label' => 'Standard Rim', 'value' => 'DW20'],
                ['label' => 'Max Speed', 'value' => '10 km/jam'],
                ['label' => 'Section Width', 'value' => '595 mm'],
                ['label' => 'Pressure', 'value' => '38 Psi'],
            ],
            'available_sizes' => ['11.00R20', '12.00R20', '12.00R24', '14.00R25'],
            'meta_description' => 'Uninest Tibermax 851: ban radial all-steel dengan telapak lebih tebal, sidewall kuat, dan umur pakai lebih panjang untuk dump truck, off-road, dan muatan berat.',
        ]);
    }
}
