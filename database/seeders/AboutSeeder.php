<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Seeder;

/**
 * Isi halaman About Us. Dipisah dari ContentSeeder supaya bisa dijalankan
 * sendiri (db:seed --class=AboutSeeder) tanpa menimpa isi halaman lain.
 */
class AboutSeeder extends Seeder
{
    use CopiesImages;

    public function run(): void
    {
        Setting::put('about', [
            'seo_title' => 'About Us — Tiberman',
            'seo_description' => 'Mengenal Tiberman (PT Tiga Berlian Mandiri): one stop tyre solutions sejak 2008, dipercaya 2.600+ mitra dan didukung 15 SuperArea di seluruh Indonesia.',

            'hero_eyebrow' => 'Mari lebih mengenal kami',
            'hero_heading' => "Be Part of Our\nJourney",
            'hero_background' => $this->img('hero-warehouse-2.webp'),
            'hero_image' => $this->img('about-us-banner.webp'),
            'hero_button_label' => 'Download Company Profile',
            'hero_button_url' => '/company-profile',

            'stats' => [
                ['title' => "<strong>18 Tahun</strong> Lebih\nMelayani Anda", 'text' => 'Kami telah tumbuh, melayani, dan terus memberikan yang terbaik sejak 2008.'],
                ['title' => "Dipercaya Oleh\n<strong>2.600+ Mitra</strong>", 'text' => 'Dipercaya oleh lebih dari 2.600 pelanggan, baik individu maupun perusahaan, dan jumlahnya terus bertumbuh hingga hari ini.'],
                ['title' => "Didukung Dengan\n<strong>15 SUPER AREA</strong>", 'text' => 'Dengan 15 Super Area yang tersebar di berbagai kota di Indonesia, kami terus memperluas jangkauan untuk melayani lebih banyak pelanggan.'],
            ],

            'vision_heading' => 'Our Vision',
            'vision_text' => 'Menjadi one stop supplier terbesar dan terkemuka untuk kebutuhan ban industri maupun komersial di seluruh wilayah Indonesia.',
            'mission_heading' => 'Our Mission',
            'mission_text' => 'Menyediakan beraneka ukuran ban berkualitas dengan pelayanan purna jual yang handal guna meningkatkan efisiensi dan efektivitas operasional para pelanggan dengan jaringan logistik yang tersebar di seluruh wilayah Indonesia.',
            'mascot_image' => $this->img('flying-panda.webp'),

            'milestone_eyebrow' => 'Perjalanan bisnis kami dari awal',
            'milestone_heading' => 'Milestone of <strong>Tiberman</strong>',
            'milestones' => [
                ['label' => '1998', 'text' => 'Berawal dari Surabaya pada tahun 1997, <strong>TIBERMAN memulai perjalanan</strong> dengan mengimpor dan memasarkan ban truk.'],
                ['label' => '2008', 'text' => 'Seiring perkembangan bisnis, pada tahun 2008 <strong>kami resmi menjadi PT Tiga Berlian Mandiri</strong>, dengan komitmen "WE PROVIDE SOLUTIONS" dalam menghadirkan solusi ban yang terpercaya dan berkelanjutan.'],
                ['label' => 'Saat Ini', 'text' => 'Kini, TIBERMAN hadir sebagai <strong>One Stop Tyre Solutions</strong> dengan jaringan di berbagai wilayah Indonesia, melayani kebutuhan industri logistik, pertambangan, perkebunan, pelabuhan, migas, manufaktur, dan konstruksi.'],
            ],
            'milestone_image' => $this->img('about-us-bottom.webp'),

            'cta_heading' => 'Murah Belum Tentu Hemat',
            'cta_text' => 'Jangan hanya lihat harga beli. Hitung juga biaya per kilometer. Ban yang tepat membantu menekan biaya operasional dan memaksimalkan setiap rupiah investasi Anda.',
            'cta_title' => "Dapatkan Ban Paling Hemat\nBiaya Per Kilometernya",
            'cta_button_label' => 'Hubungi Sekarang',
            'cta_button_url' => '/kontak',
        ]);
    }
}
