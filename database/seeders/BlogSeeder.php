<?php

namespace Database\Seeders;

use App\Models\Post;
use App\Models\PostCategory;
use Illuminate\Database\Seeder;

/**
 * Kategori blog (slug mengikuti tiberman.com/blog/category/) dan satu artikel
 * contoh yang lengkap, "Fleet Tire Management".
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

        // Satu artikel contoh yang lengkap; artikel lain diinput lewat CMS dengan
        // slug artikel tiberman.com (lihat rekap slug).
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
