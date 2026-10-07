<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Seeder;

/**
 * Isi halaman After Sales. Dipisah dari ContentSeeder supaya bisa dijalankan
 * sendiri (db:seed --class=AfterSalesSeeder) tanpa menimpa isi halaman lain.
 */
class AfterSalesSeeder extends Seeder
{
    use CopiesImages;

    public function run(): void
    {
        Setting::put('aftersales', [
            'seo_title' => 'After Sales — Tiberman',
            'seo_description' => 'Layanan purna jual Tiberman: Tyre Lab 24 jam, Site Visit tire engineer, Tyre Repair, Learning Center bersertifikat, dan Privilege Card untuk customer loyal.',

            'hero_eyebrow' => 'Layanan Purna Jual',
            'hero_heading' => "Solutions of\nTiberman",
            'hero_subheading' => "Solusi setiap permasalahan ban\ndi operasional perusahaan Anda.",

            'services_heading' => "<strong>Tak berhenti setelah pembelian.</strong> Dapatkan berbagai layanan\ndan dukungan produk, bahkan setelah ban digunakan.",
            'services' => [
                [
                    'name' => 'Tyre Lab',
                    'desc' => 'Konsultasi online segala permasalahan ban Anda selama 24 jam.',
                    'image' => $this->img('after-sales-4.webp'),
                    'alt' => 'Staf Tiberman melayani konsultasi lewat ponsel di area tambang',
                    'icon' => $this->img('tyre-lab.webp'),
                ],
                [
                    'name' => 'Site Visit',
                    'desc' => 'Kunjungan eksklusif tire engineer profesional ke site customer.',
                    'image' => $this->img('after-sales-3.webp'),
                    'alt' => 'Dua tire engineer meninjau alat berat di site customer',
                    'icon' => $this->img('site-visit.webp'),
                ],
                [
                    'name' => 'Tyre Repair',
                    'desc' => 'Layanan jaminan perbaikan kerusakan ban, sesuai dengan ketentuan yang berlaku.',
                    'image' => $this->img('after-sales-5.webp'),
                    'alt' => 'Teknisi Tiberman memperbaiki tapak ban truk',
                    'icon' => $this->img('tyre-repair.webp'),
                ],
            ],

            'facilities_heading' => "<strong>Bukan sekadar produk,</strong> dapatkan fasilitas yang\nmendukung operasional Anda.",
            'facilities' => [
                [
                    'name' => 'Learning Center',
                    'desc' => 'Layanan pembelajaran online seputar ban bersertifikat.',
                    'image' => $this->img('after-sales-2.webp'),
                    'alt' => 'Peserta mengikuti kelas online Tiberman lewat laptop',
                    'icon' => $this->img('learning-center.webp'),
                ],
                [
                    'name' => 'Privilege Card',
                    'desc' => 'Benefit lebih untuk customer loyal dengan persyaratan khusus.',
                    'image' => $this->img('after-sales-1.webp'),
                    'alt' => 'Kartu Privilege Tiberman tingkat Silver sampai Platinum',
                    'icon' => $this->img('privilege-card.webp'),
                ],
            ],
            'button_label' => 'Konsultasikan sekarang',

            'faq_heading' => 'Do you have questions?',
            'faq_image' => $this->img('panda-contact.webp'),
            'faq' => [
                ['question' => 'Bagaimana cara menggunakan layanan Tiberman Tyre Lab?', 'answer' => 'Hubungi tim kami lewat WhatsApp kapan saja, lalu kirimkan foto ban, ukuran, serta kondisi pemakaiannya. Tire engineer kami akan menganalisis masalahnya dan memberikan rekomendasi penanganan.'],
                ['question' => 'Bagaimana cara mengajukan Site Visit?', 'answer' => 'Sampaikan lokasi site, jenis unit, dan kendala ban yang dihadapi melalui tim kami. Jadwal kunjungan tire engineer akan disesuaikan dengan lokasi dan kebutuhan operasional Anda.'],
                ['question' => 'Kerusakan apa saja yang ditanggung Tyre Repair?', 'answer' => 'Tyre Repair mencakup perbaikan kerusakan ban sesuai ketentuan yang berlaku. Tim kami akan memeriksa kondisi ban terlebih dahulu untuk memastikan kerusakannya masih dapat diperbaiki.'],
                ['question' => 'Bagaimana cara mengikuti Tiberman Learning Center?', 'answer' => 'Kelas diselenggarakan secara online dan peserta mendapatkan sertifikat. Daftarkan diri atau tim Anda melalui WhatsApp kami untuk mendapatkan jadwal kelas terdekat.'],
                ['question' => 'Bagaimana cara mendapatkan Tiberman Privilege Card?', 'answer' => 'Privilege Card tersedia dalam tingkat Silver, Gold, Diamond, dan Platinum untuk customer loyal dengan persyaratan khusus. Hubungi tim kami untuk mengetahui syarat dan benefit setiap tingkatan.'],
            ],
            'faq_foot' => 'Pertanyaan saya tidak ada di sini.',
            'connect_label' => 'Connect us',
            'connect_url' => '',
        ]);
    }
}
