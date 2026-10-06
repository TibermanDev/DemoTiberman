<?php

namespace Database\Seeders;

use App\Models\PromoPage;
use Illuminate\Database\Seeder;

/**
 * Halaman promo/SEO dari situs lama. Hanya dibuat kalau slug-nya belum ada,
 * jadi isian dari CMS tidak tertimpa saat seeder dijalankan ulang.
 *
 * /aeolus-tyre berisi teks & gambar dari desain (banner, bagan perusahaan,
 * flashcard 7.50R16). Tiga slug promo lain dibuat sebagai draf nonaktif:
 * isinya belum ada, jadi baru diaktifkan setelah diisi.
 */
class PromoPageSeeder extends Seeder
{
    use CopiesImages;

    public function run(): void
    {
        $cta = [
            'cta_heading' => 'Hubungi kami sekarang dan dapatkan penawaran terbaik!',
            'cta_label' => null,
            'cta_url' => null,
        ];

        PromoPage::query()->firstOrCreate(['slug' => 'aeolus-tyre'], [
            'title' => 'Ban Produksi Aeolus Tyre Co., Ltd.',
            'banner_image' => $this->img('aeolus-tyre.webp'),
            'banner_alt' => 'Ban produksi Aeolus Tyre Co., Ltd: Aeolus, Hengli, dan Pirelli',
            'meta_title' => 'Ban Aeolus & Hengli — Produksi Aeolus Tyre Co., Ltd | Tiberman',
            'meta_description' => 'Mengenal Aeolus Tyre Co., Ltd., produsen ban AEOLUS dan HENGLI. Dapatkan ban HENGLI hanya di Tiberman, importir resmi di Indonesia.',
            'is_active' => true,
            'blocks' => [
                ['type' => 'text', 'data' => [
                    'heading' => 'Ban Produksi Aeolus Tyre Co., Ltd.',
                    'body' => '<p>AEOLUS adalah merek ban asal China yang telah dipasarkan secara global. Lebih dari sekadar merek, AEOLUS merupakan produsen ban yang beroperasi di bawah perusahaan <a href="https://www.aeolustyre.com/">Aeolus Tyre Co., Ltd.</a> dan bahkan kini telah menjadi top 20 produsen ban di dunia. Aeolus Tyre sendiri sejak didirikan tahun 1965 dengan nama awal Henan Tyre juga telah mengakuisisi beberapa perusahaan produsen ban lainnya. Salah satunya adalah di tahun 2022, Aeolus Tyre Co., Ltd. dipercayai oleh perusahaan induknya, China National Tire &amp; Rubber Co. untuk memegang 100% kepemilikan Prometeon Tyre Group, produsen ban Pirelli untuk sektor <a href="/kategori-produk/ban-truk">ban truk</a>, <a href="/kategori-produk/ban-truk">ban bus</a>, dan <a href="/kategori-produk/ban-truk/ban-truk-off-the-road">ban OTR</a>. Selain itu, di tahun 2016 Aeolus Tyre mengakuisisi Double Happiness tyre dan mengubahnya menjadi Aeolus Tyre (Taiyuan) Co., Ltd.</p><p>Dari Aeolus Tyre (Taiyuan) Co., Ltd. inilah selain memproduksi <a href="/brand/aeolus">ban AEOLUS</a> juga memproduksi ban dari asal Double Happiness yang direbranding dengan nama <a href="/brand/hengli">HENGLI</a>. Tipe-tipenya masih sama, namun disempurnakan dengan teknologi Aeolus sendiri dan memiliki kualitas yang sama dengan ban AEOLUS, tetapi dengan harga yang lebih ekonomis karena merupakan merek yang masih baru dikembangkan.</p>',
                ]],
                ['type' => 'image_text', 'data' => [
                    'image' => $this->img('bagan-konten-optional.webp'),
                    'image_side' => 'left',
                    'alt' => 'Bagan Aeolus Tyre Co., Ltd.: Prometeon Tyre Group (Pirelli) dan Aeolus Tyre (Taiyuan) Co., Ltd. (Aeolus, Hengli)',
                    'body' => '<p>Ban AEOLUS telah lama hadir dan dikenal di pasar Indonesia, sementara HENGLI kini memasuki pasar Indonesia melalui distribusi eksklusif oleh <a href="/">Tiberman</a>, <a href="/kategori-produk/ban-truk">supplier ban truk</a> dan <a href="/kategori-produk/semua-ban">ban alat berat</a> terlengkap.</p><p>Diluncurkan secara besar-besaran dan diperkenalkan pada Pameran Mining Indonesia 2022 di JIEXPO, HENGLI diharapkan mampu meraih kesuksesan seperti pendahulunya dan bersaing kuat di pasar Indonesia.</p>',
                ]],
                ['type' => 'text', 'data' => [
                    'heading' => 'Dapatkan Ban HENGLI hanya di Tiberman',
                    'body' => '<p>Tiberman adalah satu-satunya importir resmi <a href="/brand/hengli">ban HENGLI</a> di Indonesia, sehingga Anda bisa mendapatkan harga terbaik langsung dari kami. Dapatkan ban berkualitas tinggi yang diproduksi di pabrik yang sama dengan AEOLUS, dengan harga yang lebih terjangkau.<br>Saat ini, Tiberman juga ada <a href="/kategori-produk/ban-truk">promo ban 750R16</a> untuk truk engkel. Jelas kualitasnya sama dengan merek Aeolus, tetapi harga lebih murah, lebih terjangkau, lebih nyaman di kantong.</p>',
                ]],
                ['type' => 'products', 'data' => [
                    'button_label' => 'Lihat Produk >>',
                    'cards' => array_fill(0, 6, [
                        'product_id' => null,
                        'image' => $this->img('gambar-konten.webp'),
                        'url' => '/brand/hengli',
                        'label' => null,
                        'alt' => 'Ban truk Canter 7.50R16 Hengli Tibermax DR930',
                    ]),
                ]],
            ],
            ...$cta,
        ]);

        foreach ([
            'promo-tiberman' => 'Promo Tiberman',
            'beli-ban-dapat-motor' => 'Beli Ban Dapat Motor',
            'diskon-brutal' => 'Diskon Brutal',
        ] as $slug => $title) {
            PromoPage::query()->firstOrCreate(['slug' => $slug], [
                'title' => $title,
                'is_active' => false,
                'blocks' => [
                    ['type' => 'text', 'data' => ['heading' => $title, 'body' => '<p>Isi halaman promo ini dari CMS.</p>']],
                ],
                ...$cta,
            ]);
        }
    }
}
