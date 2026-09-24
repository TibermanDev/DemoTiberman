<?php

namespace Database\Seeders;

use App\Models\Post;
use App\Models\PostCategory;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

/**
 * Kategori dan artikel yang dulu ditulis langsung di news.blade.php. Hanya
 * "Fleet Tire Management" yang punya isi artikel lengkap; artikel lain baru
 * berisi ringkasannya dan tinggal dilengkapi dari CMS.
 */
class BlogSeeder extends Seeder
{
    use CopiesImages;

    public function run(): void
    {
        $categories = [
            ['alat-berat', 'Alat Berat', 'Dunia Alat Berat'],
            ['pengetahuan-ban', 'Ban', 'Pengetahuan Ban'],
            ['pertambangan', 'Pertambangan', 'Dunia Pertambangan'],
            ['tips-dan-trik', 'Tips', 'Tips & Panduan'],
            ['info-produk', 'Info Produk', 'Info Produk'],
            ['informasi-umum', 'Info Lain', 'Info Lain'],
        ];

        $cat = [];
        foreach ($categories as $i => [$slug, $name, $heading]) {
            $cat[$slug] = PostCategory::query()->updateOrCreate(['slug' => $slug], [
                'name' => $name, 'heading' => $heading, 'sort_order' => $i + 1,
            ]);
        }

        // [kategori, judul, tanggal, gambar, alt, ringkasan, flag]
        $posts = [
            ['alat-berat', 'Perbedaan Ban Truk dan Ban Mobil dari Konstruksi hingga Penggunaannya', '2026-09-02', 'tire-554.webp', 'Ban truk Uninest TiberMAX', 'Ban adalah penghubung antara kendaraan dan permukaan jalan. Konstruksi ban truk dan ban mobil dirancang untuk beban dan medan yang sama sekali berbeda.'],
            ['alat-berat', 'Dump Truck: Fungsi, Jenis, Komponen, dan Tips Memilih Ban yang Tepat untuk Operasional', '2026-08-13', 'news-3.webp', 'Dump truck di lokasi proyek', 'Dump truck dirancang untuk mengangkut sekaligus menurunkan material dalam jumlah besar. Simak jenis, komponen, dan cara memilih bannya.'],
            ['pengetahuan-ban', 'Ban Tubeless atau Tube Type? Ban Radial atau Bias?', '2026-08-28', 'tires-strip.webp', 'Deretan ban siap kirim', 'Empat istilah yang paling sering tertukar waktu memilih ban. Perbedaannya bukan sekadar nama, tapi menentukan daya angkut, umur pakai, dan biaya perawatan.'],
            ['pengetahuan-ban', 'Dump Truck di Tambang: Lebih Baik Pakai Ban Bias atau Radial?', '2025-10-07', 'banner-tyre.webp', 'Ban OTR untuk dump truck tambang', 'Di tengah deru mesin dan debu yang mengepul, pilihan konstruksi ban menentukan berapa rit yang sanggup ditempuh sebelum unit harus masuk bengkel.'],
            ['pertambangan', 'Dampak Naiknya Harga Emas pada Industri Ban Alat Berat', '2025-09-04', 'news-2.webp', 'Pemandangan udara area tambang', 'Dari pasar global ke ban alat berat di tambang: kenaikan harga emas mendorong produksi, dan produksi yang naik langsung terasa pada kebutuhan ban OTR.', 'featured'],
            ['pertambangan', 'Inilah 10 Perusahaan Tambang Terbesar di Indonesia', '2026-08-21', 'warehouse-dark.webp', 'Stok ban di gudang Tiberman', 'Dari batu bara di Kalimantan sampai nikel di Sulawesi, sepuluh nama ini menggerakkan sebagian besar aktivitas pertambangan nasional.', 'popular'],
            ['pertambangan', 'Daftar Pertambangan di Kalimantan: Emas, Batu Bara, dan Nikel', '2026-08-14', 'plb-stock.webp', 'Gudang stok ban alat berat', 'Kalimantan menyimpan tiga komoditas sekaligus, dan tiap komoditas menuntut spesifikasi armada serta ban yang berbeda.', 'popular'],
            ['tips-dan-trik', 'Mengenal Arti Warna Baju Proyek dan Helm Proyek di Lapangan', '2026-09-10', 'after-sales-4.webp', 'Pekerja dengan atribut keselamatan di lapangan', 'Warna helm dan rompi di area proyek bukan soal selera. Tiap warna menandai peran, dan salah baca bisa berakibat fatal saat keadaan darurat.'],
            ['tips-dan-trik', 'Di Balik Jalan Kokoh & Rahasia Pemilihan Ban Alat Berat Compactor', '2026-08-29', 'delivery-forklift.webp', 'Alat berat compactor di proyek jalan', 'Jalan yang padat dan rata berawal dari compactor. Ban yang dipakainya menentukan kerataan hasil pemadatan sekaligus kenyamanan operator.'],
            ['tips-dan-trik', '11 Macam Alat Berat Tambang dan Kegunaannya', '2026-08-05', 'after-sales-3.webp', 'Alat berat di medan berbatu', 'Excavator, bulldozer, wheel loader, sampai articulated dump truck — kenali fungsi tiap unit sebelum menentukan ban yang dipasang.', 'popular'],
            ['info-produk', 'Motor Grader, Penjaga Kelancaran Hauling Road', '2026-08-28', 'dumptruck.webp', 'Motor grader di hauling road', 'Hauling road yang mulus memangkas waktu siklus angkut. Di sinilah motor grader dan ban yang tepat memegang peran.'],
            ['info-produk', '7 Truk Tambang Terbesar di Dunia, Jangan Ngeri Lihat Ukurannya!', '2026-08-18', 'truck-tiberman.webp', 'Truk tambang berukuran besar', 'Tujuh raksasa pengangkut material ini punya ban setinggi orang dewasa — dan tiap satu bannya seharga sebuah mobil.', 'popular'],
            ['info-produk', 'Memilih Velg & Tube yang Sepadan dengan Ban Alat Berat Anda', '2026-08-02', 'velg-heavy.webp', 'Velg untuk alat berat', 'Ban yang benar tapi velg yang tidak sepadan tetap berujung pada umur pakai yang pendek. Ini patokan memilih pasangannya.'],
            ['informasi-umum', 'Tiberman Sabet Dua Rekor MURI di Tiberman Expo 2026', '2026-07-31', 'news-1.webp', 'Penyerahan dua Rekor MURI di Tiberman Expo 2026', 'PT Tiga Berlian Mandiri menorehkan prestasi tingkat nasional dengan memecahkan dua Rekor MURI sekaligus di ajang Tiberman Expo 2026.', 'featured'],
            ['informasi-umum', 'Perbedaan Geografis Tambang di Indonesia dan Strategi Pemilihan Ban Alat Berat', '2026-08-08', 'news-2.webp', 'Pemandangan udara area tambang di Indonesia', 'Kondisi geografis tiap lokasi tambang menentukan spesifikasi ban OTR yang dipakai — dari rawa Kalimantan sampai bukit berbatu Sulawesi.'],
            ['informasi-umum', 'Jenis Bahan Galian Tambang (Golongan A, B, dan C) di Indonesia', '2026-07-24', 'plb-gresik.webp', 'Pusat Logistik Berikat Tiberman', 'Golongan A, B, dan C membedakan bahan galian menurut kepentingannya bagi negara. Ini yang membedakan ketiganya di lapangan.', 'popular'],
        ];

        foreach ($posts as $p) {
            [$slug, $title, $date, $image, $alt, $excerpt] = $p;
            $flag = $p[6] ?? null;

            Post::query()->updateOrCreate(['slug' => Str::slug($title)], [
                'post_category_id' => $cat[$slug]->id,
                'title' => $title,
                'excerpt' => $excerpt,
                'cover_image' => $this->img($image),
                'cover_alt' => $alt,
                'body' => '<p>'.e($excerpt).'</p>',
                'published_at' => $date.' 08:00:00',
                'is_featured' => $flag === 'featured',
                'is_popular' => $flag === 'popular',
            ]);
        }

        $fleet = Post::query()->updateOrCreate(['slug' => 'fleet-tire-management-cara-mengontrol-biaya-ban-puluhan-hingga-ratusan-truk'], [
            'post_category_id' => $cat['alat-berat']->id,
            'title' => 'Fleet Tire Management: Cara Mengontrol Biaya Ban Puluhan hingga Ratusan Truk',
            'excerpt' => 'Mengelola lima hingga sepuluh unit truk mungkin masih bisa dilakukan dengan pengawasan kasat mata dan pencatatan sederhana. Namun bagaimana jika armadanya sudah ratusan unit?',
            'cover_image' => $this->img('plb-truck.webp'),
            'cover_alt' => 'Barisan truk pengangkut menunggu bongkar di area pergudangan',
            'cover_caption' => 'Semakin besar armada, semakin kecil peluang mengawasi kondisi ban satu per satu secara manual.',
            'body' => $this->fleetBody(),
            'published_at' => '2026-09-10 09:00:00',
            'is_featured' => true,
            'meta_description' => 'Begitu armada tumbuh dari sepuluh menjadi ratusan unit, biaya ban tidak lagi bisa diawasi kasat mata. Ini kerangka fleet tire management yang dipakai pengelola armada besar.',
        ]);
        $fleet->tags()->sync([$cat['alat-berat']->id, $cat['pengetahuan-ban']->id, $cat['tips-dan-trik']->id]);
    }

    private function fleetBody(): string
    {
        $tread = '/storage/'.$this->img('tire-tread.webp');

        return <<<HTML
<p class="art__lead">Mengelola lima hingga sepuluh unit truk mungkin masih bisa dilakukan dengan pengawasan kasat mata dan pencatatan sederhana. Namun begitu armada tumbuh menjadi puluhan bahkan ratusan unit, biaya ban berubah dari pengeluaran rutin menjadi pos yang diam-diam menggerus margin.</p>
<p>Di banyak perusahaan angkutan, ban adalah komponen biaya operasional terbesar kedua setelah bahan bakar. Bedanya, konsumsi bahan bakar biasanya sudah dicatat harian, sementara ban baru diperhatikan ketika sudah pecah atau botak. Padahal justru di antara dua titik itulah sebagian besar biaya terbuang.</p>
<h2>Kenapa pencatatan manual berhenti bekerja</h2>
<p>Selama armadanya kecil, kepala bengkel biasanya masih hafal unit mana yang bannya baru diganti. Begitu jumlah unit naik, tiga hal langsung terjadi sekaligus:</p>
<ul>
<li>Riwayat ban tercampur antar unit karena ban dipindah-pindah tanpa dicatat posisinya.</li>
<li>Tekanan angin hanya diperiksa saat unit masuk bengkel, bukan secara berkala.</li>
<li>Keputusan ganti ban diambil oleh masing-masing driver, bukan berdasarkan ambang batas yang sama.</li>
</ul>
<p>Akibatnya biaya per kilometer tidak pernah benar-benar diketahui. Yang terlihat di laporan hanya total belanja ban per bulan — angka yang naik-turun tanpa bisa dijelaskan penyebabnya.</p>
<blockquote>Biaya ban yang tidak bisa diukur per unit tidak bisa ditekan. Yang bisa dilakukan hanya menunda pembeliannya, dan itu justru memperbesar risiko di jalan.</blockquote>
<h2>Empat angka yang wajib dicatat</h2>
<p>Fleet tire management tidak harus dimulai dengan perangkat mahal. Untuk armada di bawah 100 unit, empat angka berikut sudah cukup untuk membuat keputusan yang jauh lebih baik:</p>
<ol>
<li><strong>Posisi ban.</strong> Tiap ban diberi nomor dan dicatat ada di unit mana, di posisi roda keberapa. Tanpa ini, angka lain kehilangan konteks.</li>
<li><strong>Kedalaman telapak.</strong> Diukur rutin di titik yang sama. Laju keausan per bulan lebih berguna daripada nilai sesaat.</li>
<li><strong>Tekanan angin.</strong> Penyebab kerusakan ban terbesar yang paling murah dicegah. Selisih 10% saja sudah memperpendek umur pakai secara signifikan.</li>
<li><strong>Kilometer tempuh.</strong> Pembagi dari semuanya. Biaya ban per kilometer inilah angka yang akhirnya dipakai membandingkan merek, pola telapak, dan kebijakan rotasi.</li>
</ol>
<figure class="art__figure art__figure--inline">
<img src="{$tread}" alt="Pengukuran kedalaman telapak ban truk" loading="lazy">
<figcaption>Kedalaman telapak yang diukur di titik yang konsisten membuat laju keausan bisa dibandingkan antar unit.</figcaption>
</figure>
<h2>Rotasi dan vulkanisir: dua penghematan yang sering dilewatkan</h2>
<p>Ban pada posisi steer, drive, dan trailer mengalami beban yang berbeda, sehingga ausnya juga tidak sama. Rotasi yang terjadwal memindahkan ban ke posisi yang sesuai dengan sisa telapaknya, bukan membiarkannya aus habis di satu titik.</p>
<p>Untuk ban radial dengan casing yang masih sehat, vulkanisir bisa menambah satu hingga dua siklus pakai dengan biaya jauh di bawah harga ban baru. Kuncinya adalah menarik ban dari operasi <em>sebelum</em> casing-nya rusak — dan itu hanya mungkin kalau kedalaman telapaknya dipantau.</p>
<h2>Mulai dari mana</h2>
<p>Ambil satu rute dan sepuluh unit sebagai pilot. Catat empat angka di atas selama tiga bulan, lalu hitung biaya ban per kilometer untuk tiap unit. Selisih antar unit pada rute yang sama biasanya langsung menunjukkan di mana masalahnya: tekanan, gaya mengemudi, atau spesifikasi ban yang tidak cocok dengan medannya.</p>
<p>Tim Tiberman biasa membantu pelanggan menyusun tabel pemantauan seperti ini sekaligus menyesuaikan spesifikasi ban dengan rute dan beban armadanya. Silakan hubungi SuperArea terdekat untuk berdiskusi lebih lanjut.</p>
HTML;
    }
}
