<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Seeder;

/**
 * Isi halaman (beranda, contact, news, superarea, katalog) dan pengaturan
 * situs — disalin dari markup statis sebelum ada CMS.
 */
class ContentSeeder extends Seeder
{
    use CopiesImages;

    public function run(): void
    {
        Setting::put('site', [
            'logo' => $this->img('logo-white.png'),
            'company_name' => 'PT. Tiga Berlian Mandiri',
            'about' => 'Kami adalah One Stop Supplier ban alat berat yang telah dipercaya oleh ribuan customer di seluruh Indonesia. Sejak berdiri pada tahun 2008, jaringan distribusi kami telah tersebar di berbagai titik Super Area yang dapat menjangkau hingga pelosok negeri.',
            'phone' => '+62 812 8325 8200',
            'email' => 'info@tiberman.com',
            'whatsapp' => '6281283258200',
            'social' => [
                'facebook' => '#',
                'youtube' => '#',
                'instagram' => '#',
                'tiktok' => '#',
                'linkedin' => '#',
            ],
            'marketplace' => [
                'tokopedia' => '#',
                'shopee' => '#',
            ],
            'footer_links' => [
                ['label' => 'Berita & Artikel', 'url' => '/blog'],
                ['label' => 'Karir', 'url' => '#'],
                ['label' => 'Semua Produk', 'url' => '/kategori-produk/semua-ban'],
                ['label' => 'After Sales Service', 'url' => '/#kenapa'],
            ],
            'copyright' => 'Copyright © 2021 PT. Tiga Berlian Mandiri',
            'seo_title' => 'Tiberman — Importir Ban Truk & Alat Berat Terpercaya',
            'seo_description' => 'Tiberman, importir ban truk & alat berat terpercaya dengan 15 SuperArea di seluruh Indonesia, 2 Pusat Logistik Berikat, dan armada delivery sendiri.',
        ]);

        Setting::put('home', [
            'seo_title' => 'Tiberman — Importir Ban Truk & Alat Berat Terpercaya',
            'seo_description' => 'Tiberman, importir ban truk & alat berat terpercaya dengan 15 SuperArea di seluruh Indonesia, 2 Pusat Logistik Berikat, dan armada delivery sendiri.',
            'hero' => [
                'image' => $this->img('hero-warehouse-2.webp'),
                'alt' => 'Tim Tiberman di gudang ban',
            ],
            'superarea' => [
                'eyebrow' => 'siap melayani Anda lebih dekat dengan',
                'heading' => "15 SuperArea yang tersebar di\nseluruh Indonesia",
                'button_label' => 'Check it !',
                'button_url' => '/kontak',
            ],
            'importir' => [
                'heading' => "Importir Ban Truk & Alat Berat\nTERPERCAYA",
                'video' => $this->img('tires-moving.mp4'),
                'video_webm' => $this->img('tires-moving.webm'),
                'poster' => $this->img('tires-moving-poster.webp'),
                'pills' => [
                    ['label' => 'Truck & Bus', 'url' => '/kategori-produk/ban-truk'],
                    ['label' => 'Mining Truck', 'url' => '/kategori-produk/ban-truk/ban-truk-off-the-road'],
                    ['label' => 'Loader', 'url' => '/kategori-produk/ban-loader'],
                    ['label' => 'Grader', 'url' => '/kategori-produk/ban-grader'],
                    ['label' => 'Forklift', 'url' => '/kategori-produk/ban-forklift'],
                    ['label' => 'Tractor', 'url' => '/kategori-produk/ban-traktor'],
                ],
            ],
            'accessories' => [
                'heading' => "Lengkapi kebutuhan\nAnda di satu tempat.",
                'lead' => 'Tak hanya ban, kami juga menyediakan aksesoris pendukung seperti velg, ban dalam, flap, marset, dan O-ring dengan stok siap kirim.',
                'image' => $this->img('accessories-2.webp'),
                'alt' => 'Ban, velg, dan aksesoris pendukung',
            ],
            'cards' => [
                ['title' => 'OTR TYRE', 'subtitle' => '(Off The Road)', 'image' => $this->img('otr-tyre.png'), 'alt' => 'Ban OTR untuk alat berat', 'url' => '/kategori-produk/ban-truk/ban-truk-off-the-road', 'is_tyre' => true],
                ['title' => 'TBR TYRE', 'subtitle' => '(Truck Bus)', 'image' => $this->img('tbr-tyre.png'), 'alt' => 'Ban TBR untuk truk dan bus', 'url' => '/kategori-produk/ban-truk', 'is_tyre' => true],
                ['title' => 'VELG', 'subtitle' => 'Heavy-Duty', 'image' => $this->img('velg-heavy-2.webp'), 'alt' => 'Velg heavy duty', 'url' => '/kategori-produk/velg-truk', 'is_tyre' => false],
                ['title' => 'VELG', 'subtitle' => 'Light Truck', 'image' => $this->img('velg-light-2.webp'), 'alt' => 'Velg light truck', 'url' => '/kategori-produk/velg-truk', 'is_tyre' => false],
            ],
            'why' => [
                'kicker' => 'Dari sekian banyak supplier lain',
                'title' => 'Kenapa Harus Tiberman ?',
                'video' => $this->img('warehouse-loop-web.mp4'),
                'video_webm' => null,
                'poster' => $this->img('warehouse-dark.webp'),
            ],
            'stock' => [
                'title' => 'Stok Aman',
                'brand_logo' => $this->img('logo-fiemin.png'),
                'brand_name' => 'PT Fie Min Logistics',
                'heading' => "Memiliki 2 Pusat Logistik\nBerikat (PLB) sendiri",
                'body' => 'Tiberman Group didukung oleh 2 Pusat Logistik Berikat (PLB) yang dikelola <b>PT Fiemin Logistics</b>. yang berlokasi di <b>Gresik</b> dan <b>Mojokerto</b>',
                'button_label' => 'Check it !',
                'button_url' => '/kontak',
                'image' => $this->img('plb-stock.webp'),
                'alt' => 'Gudang stok ban Pusat Logistik Berikat',
                'warehouses' => [
                    ['name' => 'Mojokerto', 'capacity' => '150 kontainer', 'image' => $this->img('plb-mojokerto.webp')],
                    ['name' => 'Gresik', 'capacity' => '250 kontainer', 'image' => $this->img('plb-gresik.webp')],
                ],
            ],
            'delivery' => [
                'title' => 'Pengiriman Aman',
                'brand_logo' => $this->img('logo-halilintar.png'),
                'brand_name' => 'Halilintar',
                'heading' => "Aman sampai tujuan dengan\narmada delivery sendiri",
                'body' => 'Tiberman Group didukung oleh layanan distribusi yang dikelola oleh PT Hantar Lintas Nusantara (Halilintar) memastikan setiap pengiriman aman hingga sampai ke tangan anda.',
                'button_label' => 'Check it !',
                'button_url' => '/kontak',
                'image' => $this->img('truck-tiberman.webp'),
                'alt' => 'Ilustrasi truk pengiriman Tiberman bermuatan ban',
                'photos' => [
                    ['image' => $this->img('plb-truck.webp'), 'alt' => 'Truk kontainer Tiberman di gudang'],
                    ['image' => $this->img('delivery-forklift.webp'), 'alt' => 'Forklift memuat ban ke kontainer'],
                ],
            ],
            'aftersales' => [
                'title' => 'After Sales',
                'items' => [
                    ['title' => 'Tyre Repair', 'desc' => 'Layanan jaminan perbaikan kerusakan ban, sesuai dengan ketentuan yang berlaku.', 'image' => $this->img('after-sales-5.webp'), 'alt' => 'Teknisi Tiberman memperbaiki tapak ban truk'],
                    ['title' => 'Tyre Lab', 'desc' => 'Konsultasi online segala permasalahan ban 24 jam.', 'image' => $this->img('after-sales-4.webp'), 'alt' => 'Staf Tiberman melayani konsultasi lewat ponsel di area tambang'],
                    ['title' => 'Site Visit', 'desc' => 'Kunjungan eksklusif tire engineer profesional ke site customer.', 'image' => $this->img('after-sales-3.webp'), 'alt' => 'Dua tire engineer meninjau alat berat di site customer'],
                    ['title' => 'Learning Center', 'desc' => 'Layanan pembelajaran online seputar ban bersertifikat.', 'image' => $this->img('after-sales-2.webp'), 'alt' => 'Peserta mengikuti kelas online Tiberman lewat laptop'],
                    ['title' => 'Privilege Card', 'desc' => 'Benefit lebih untuk customer loyal dengan persyaratan khusus.', 'image' => $this->img('after-sales-1.webp'), 'alt' => 'Kartu Privilege Tiberman tingkat Silver sampai Platinum'],
                ],
            ],
            'testimonials' => [
                'heading' => "Testimoni\nPelanggan",
                'intro' => 'Berikut beberapa testimoni dari pelanggan yang telah menggunakan produk ban kami.',
                'items' => [
                    ['name' => 'Agnes Remi', 'role' => 'Mbak2 tambang', 'quote' => 'Bannya sangat bagus, sampai saya pengen beli lagi walau gatau buat apa, terimakasih Tiberman!'],
                    ['name' => 'Bagus Santosa', 'role' => 'Fleet Manager', 'quote' => 'Stok selalu ada dan pengiriman cepat. Armada kami tidak pernah menunggu ban lagi.'],
                    ['name' => 'Dimas Prakoso', 'role' => 'Owner Dump Truck', 'quote' => 'Layanan free tyre repair-nya benar-benar terpakai. Support after sales-nya cepat tanggap.'],
                    ['name' => 'Rizky Nugraha', 'role' => 'Supervisor Hauling', 'quote' => 'Sidewall-nya kuat untuk jalur tambang. Umur pakainya jauh lebih panjang dari ban sebelumnya.'],
                ],
            ],
        ]);

        Setting::put('contact', [
            'seo_title' => 'Contact Us — Tiberman',
            'seo_description' => 'Pertanyaan yang sering diajukan seputar pemesanan, pengiriman, dan garansi ban Tiberman — beserta jalur kontak langsung ke tim kami.',
            'eyebrow' => 'Become Our Partner!',
            'heading' => "Stronger Business Start\nwith the Right Partner",
            'unit_options' => ['Truk & Bus', 'Mining Truck', 'Loader-Grader', 'Traktor', 'Forklift', 'Velg & Tube'],
            'submit_label' => 'Kirim Permintaan',
            'form_image' => $this->img('accessories.webp'),
            'faq_heading' => 'Do you have questions?',
            'faq_image' => $this->img('panda-contact.webp'),
            'faq' => [
                ['question' => 'Apakah Tiberman melayani pembelian dalam jumlah besar?', 'answer' => 'Ya. Sebagian besar pelanggan kami adalah perusahaan angkutan, kontraktor, dan perusahaan tambang dengan kebutuhan puluhan hingga ratusan ban per pengadaan. Tim kami akan membantu menyesuaikan spesifikasi dengan rute dan beban armada Anda.'],
                ['question' => 'Berapa lama proses pengirimannya?', 'answer' => 'Untuk stok yang tersedia di SuperArea terdekat, pengiriman umumnya dilakukan dalam 24 jam. Distribusi dikelola sendiri oleh PT Hantar Lintas Nusantara (Halilintar), jadi jadwalnya bisa kami pantau sampai barang diterima.'],
                ['question' => 'Bagaimana kalau ban yang saya terima cacat produksi?', 'answer' => 'Hubungi SuperArea tempat Anda membeli beserta foto dan nomor seri bannya. Klaim garansi cacat produksi kami proses tanpa biaya, termasuk penggantian unit bila hasil pemeriksaan memenuhi syarat.'],
                ['question' => 'Apakah tersedia layanan konsultasi pemilihan ban?', 'answer' => 'Tersedia dan tanpa biaya. Sampaikan jenis unit, medan, dan beban rata-rata Anda — tim teknis kami akan merekomendasikan ukuran, pola telapak, dan konstruksi yang paling sesuai.'],
                ['question' => 'Di mana saja lokasi SuperArea Tiberman?', 'answer' => 'Ada 15 SuperArea dari Sumatra sampai Maluku & Papua. Daftar lengkap beserta alamatnya bisa dilihat di halaman <a href="/cabang-tiberman">SuperArea</a>.'],
            ],
            'faq_foot' => 'Pertanyaan saya tidak ada di sini.',
            'connect_label' => 'Connect us',
            'connect_url' => 'https://wa.me/6281283258200',
        ]);

        Setting::put('news', [
            'seo_title' => 'News — Tiberman',
            'seo_description' => 'Kabar terbaru dan wawasan seputar ban truk, alat berat, dan dunia pertambangan dari Tiberman.',
            'latest_heading' => 'Latest Post',
            'popular_heading' => 'Populer Bulan Ini',
            'related_heading' => 'Artikel Terkait',
        ]);

        Setting::put('superarea', [
            'seo_title' => 'SuperArea — Tiberman',
            'seo_description' => '15 SuperArea Tiberman yang tersebar dari Sumatra sampai Maluku & Papua, siap melayani kebutuhan ban truk & alat berat lebih dekat.',
            'kicker' => 'SUPERAREA',
            'regions' => [
                ['key' => 'jawa', 'label' => 'Jawa'],
                ['key' => 'sumatra', 'label' => 'Sumatra'],
                ['key' => 'kalimantan', 'label' => 'Kalimantan'],
                ['key' => 'sulawesi', 'label' => 'Sulawesi'],
                ['key' => 'maluku-papua', 'label' => 'Maluku & Papua'],
            ],
        ]);

        Setting::put('catalog', [
            'seo_description' => 'Telusuri katalog ban truk, mining truck, loader-grader, traktor, forklift, velg & tube Tiberman berdasarkan unit dan ukuran.',
            'banner' => $this->img('dumptruck.webp'),
            'banner_alt' => 'Dump truck di area tambang',
        ]);
    }
}
