<?php

namespace Database\Seeders;

use App\Models\Brand;
use App\Models\CatalogUnit;
use App\Models\Product;
use App\Models\TireSize;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

/**
 * Data katalog awal untuk deploy: HANYA contoh sesuai desain katalog — 6 unit
 * (+ "Semua Ban", wajib untuk /kategori-produk/semua-ban), 6 merk, 2 ukuran, dan
 * 1 produk lengkap. Unit/merk/ukuran/produk lain diinput admin lewat CMS sesuai
 * file "checklist slug admin" (slug-nya mengikuti toko lama tiberman.com).
 */
class CatalogSeeder extends Seeder
{
    use CopiesImages;

    public function run(): void
    {
        // [label, path URL lama utama, tampil di menu Products, URL lama lain]
        // URL lama lain = pemetaan alamat toko lama ke unit ini (bukan data tampilan).
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
            'loader-grader' => ['Loader-Grader', 'kategori-produk/ban-loader', true, ['kategori-produk/ban-grader']],
            'traktor' => ['Traktor', 'kategori-produk/ban-traktor', true, []],
            'forklift' => ['Forklift', 'kategori-produk/ban-forklift', true, [
                'kategori-produk/ban-forklift/ban-pneumatic',
                'kategori-produk/ban-forklift/ban-solid',
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
        foreach (['uninest' => 'Uninest', 'tutric' => 'Tutric', 'tianli' => 'Tianli', 'hengli' => 'Hengli', 'bontyre' => 'Bontyre', 'durun' => 'Durun'] as $slug => $name) {
            Brand::query()->updateOrCreate(['slug' => $slug], ['name' => $name, 'sort_order' => $order++]);
        }

        // Label harus sama persis dengan kolom "Ukuran" produk supaya chip-nya jadi tautan.
        foreach (['11.00R20', '11.00R24'] as $label) {
            TireSize::query()->updateOrCreate(['label' => $label], ['slug' => 'ban-'.Str::slug(str_replace(['.', '/'], '-', $label))]);
        }

        // Satu produk contoh yang lengkap dengan halaman detailnya (sesuai desain
        // katalog). Spesifikasinya hanya contoh — koreksi/ganti lewat CMS.
        $truck = CatalogUnit::query()->where('key', 'truk-bus')->firstOrFail();
        $uninest = Brand::query()->where('slug', 'uninest')->firstOrFail();

        Product::query()->updateOrCreate(['slug' => 'uninest-tibermax-554-11-00r20'], [
            'catalog_unit_id' => $truck->id,
            'brand_id' => $uninest->id,
            'name' => 'UNINEST - TIBERMAX 554',
            'size' => '11.00R20',
            'compat' => 'Dumptruck',
            'sort_order' => 0,
            'logo' => $this->img('tibermax-logo.png'),
            'logo_light' => $this->img('tibermax-logo-light.png'),
            'hero_image' => $this->img('tire-hero-dark.webp'),
            'image' => $this->img('tire-554.webp'),
            'description' => '<b>Uninest Tibermax 554</b> Dirancang khusus untuk memberikan cengkraman maksimal tanpa kompromi. Dengan telapak yang lebih tebal, ban ini nggak cuma tangguh, tapi juga punya umur pakai yang lebih panjang.',
            'features' => [
                ['title' => "Sidewall\nKuat", 'body' => 'Konstruksi all-steel radial dengan bahu ban lebih tebal, tahan benturan batu dan beban lateral di jalur tambang.', 'image' => $this->img('tyre-slice-left.png'), 'fit' => 'edge'],
                ['title' => "Telapak\nTebal", 'body' => 'Kedalaman tapak 25.5 mm dengan blok besar memberi traksi maksimal dan umur pakai yang jauh lebih panjang.', 'image' => $this->img('tire-tread.webp'), 'fit' => 'cover'],
            ],
            'pairs' => [
                ['title' => 'Dump Truck', 'image' => $this->img('dumptruck.webp')],
                ['title' => 'Off-Road', 'image' => $this->img('tire-tread.webp')],
                ['title' => 'Muatan Berat', 'image' => $this->img('plb-stock.webp')],
            ],
            'gallery' => [
                $this->img('tyre-preview.png'),
                $this->img('tyre-diameter.png'),
                $this->img('tyre-slice-left.png'),
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
            'available_sizes' => ['11.00R20', '11.00R24'],
            'flashcard_image' => $this->img('gambar-konten.webp'),
            'meta_description' => 'Uninest Tibermax 554: ban radial all-steel dengan telapak lebih tebal, sidewall kuat, dan umur pakai lebih panjang untuk dump truck, off-road, dan muatan berat.',
        ]);
    }
}
