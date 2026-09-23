@extends('layouts.app')

@section('title', 'Fleet Tire Management: Cara Mengontrol Biaya Ban Puluhan hingga Ratusan Truk — Tiberman News')
@section('description', 'Begitu armada tumbuh dari sepuluh menjadi ratusan unit, biaya ban tidak lagi bisa diawasi kasat mata. Ini kerangka fleet tire management yang dipakai pengelola armada besar.')
@section('body-class', 'subpage')

@section('content')

<main class="nwp">

  <!-- Banner sama dengan news.html (empat lapis) supaya halaman detail tetap
       terasa satu kesatuan dengan indeksnya. -->
  <div class="nwp__banner">
    <img class="nwp__banner-bg" src="{{ asset('assets/img/warehouse-trans-news.webp') }}" width="2400" height="252" alt="" aria-hidden="true" fetchpriority="high">
    <img class="nwp__banner-tyre nwp__banner-tyre--l" src="{{ asset('assets/img/tyre-left.webp') }}" width="700" height="520" alt="" aria-hidden="true">
    <img class="nwp__banner-tyre nwp__banner-tyre--r" src="{{ asset('assets/img/tyre-right.webp') }}" width="700" height="520" alt="" aria-hidden="true">
    <img class="nwp__banner-logo" src="{{ asset('assets/img/tbm-news.webp') }}" width="1200" height="173" alt="Tiberman News">
  </div>

  <!-- Di halaman detail tab-nya jadi TAUTAN ke indeks, bukan tombol filter.
       news.html membaca hash-nya dan langsung membuka kategori itu. -->
  <nav class="nwp__tabs" aria-label="Kategori artikel">
    <a class="nwp-tab" href="/news">Home</a>
    <a class="nwp-tab is-active" href="/news#alat-berat">Alat Berat</a>
    <a class="nwp-tab" href="/news#ban">Ban</a>
    <a class="nwp-tab" href="/news#pertambangan">Pertambangan</a>
    <a class="nwp-tab" href="/news#tips">Tips</a>
    <a class="nwp-tab" href="/news#info-produk">Info Produk</a>
    <a class="nwp-tab" href="/news#info-lain">Info Lain</a>
  </nav>

  <section class="nwp-art">
    <div class="container nwp-art__grid">

      <article class="art">
        <nav class="art__crumb" aria-label="Breadcrumb">
          <a href="/news">News</a>
          <span aria-hidden="true">/</span>
          <a href="/news#alat-berat">Dunia Alat Berat</a>
        </nav>

        <h1>Fleet Tire Management: Cara Mengontrol Biaya Ban Puluhan hingga Ratusan Truk</h1>

        <div class="art__meta">
          <span class="art__author">tiberman</span>
          <span aria-hidden="true">&middot;</span>
          <time datetime="2026-09-10">10 September 2026</time>
          <span aria-hidden="true">&middot;</span>
          <span>7 menit baca</span>
        </div>

        <figure class="art__figure">
          <img src="{{ asset('assets/img/plb-truck.webp') }}" alt="Barisan truk pengangkut menunggu bongkar di area pergudangan" fetchpriority="high">
          <figcaption>Semakin besar armada, semakin kecil peluang mengawasi kondisi ban satu per satu secara manual.</figcaption>
        </figure>

        <div class="art__body">
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
            <img src="{{ asset('assets/img/tire-tread.webp') }}" alt="Pengukuran kedalaman telapak ban truk" loading="lazy">
            <figcaption>Kedalaman telapak yang diukur di titik yang konsisten membuat laju keausan bisa dibandingkan antar unit.</figcaption>
          </figure>

          <h2>Rotasi dan vulkanisir: dua penghematan yang sering dilewatkan</h2>

          <p>Ban pada posisi steer, drive, dan trailer mengalami beban yang berbeda, sehingga ausnya juga tidak sama. Rotasi yang terjadwal memindahkan ban ke posisi yang sesuai dengan sisa telapaknya, bukan membiarkannya aus habis di satu titik.</p>

          <p>Untuk ban radial dengan casing yang masih sehat, vulkanisir bisa menambah satu hingga dua siklus pakai dengan biaya jauh di bawah harga ban baru. Kuncinya adalah menarik ban dari operasi <em>sebelum</em> casing-nya rusak — dan itu hanya mungkin kalau kedalaman telapaknya dipantau.</p>

          <h2>Mulai dari mana</h2>

          <p>Ambil satu rute dan sepuluh unit sebagai pilot. Catat empat angka di atas selama tiga bulan, lalu hitung biaya ban per kilometer untuk tiap unit. Selisih antar unit pada rute yang sama biasanya langsung menunjukkan di mana masalahnya: tekanan, gaya mengemudi, atau spesifikasi ban yang tidak cocok dengan medannya.</p>

          <p>Tim Tiberman biasa membantu pelanggan menyusun tabel pemantauan seperti ini sekaligus menyesuaikan spesifikasi ban dengan rute dan beban armadanya. Silakan hubungi SuperArea terdekat untuk berdiskusi lebih lanjut.</p>
        </div>

        <div class="art__tags">
          <a href="/news#alat-berat">Dunia Alat Berat</a>
          <a href="/news#ban">Pengetahuan Ban</a>
          <a href="/news#tips">Tips &amp; Panduan</a>
        </div>

        <div class="art__foot">
          <a class="btn btn--primary" href="/news">&larr; Kembali ke News</a>
        </div>
      </article>

      <aside class="nwp-latest nwp-art__side">
        <h2 class="nwp-latest__head">Populer Bulan Ini</h2>

        <a class="nwp-mini" href="/news-detail">
          <span class="nwp-mini__thumb"><img src="{{ asset('assets/img/warehouse-dark.webp') }}" alt="" loading="lazy"></span>
          <span class="nwp-mini__body">
            <strong>Inilah 10 Perusahaan Tambang Terbesar di Indonesia</strong>
            <em>21 Agustus 2026</em>
          </span>
        </a>

        <a class="nwp-mini" href="/news-detail">
          <span class="nwp-mini__thumb"><img src="{{ asset('assets/img/plb-gresik.webp') }}" alt="" loading="lazy"></span>
          <span class="nwp-mini__body">
            <strong>Jenis Bahan Galian Tambang (Golongan A, B, dan C) di Indonesia</strong>
            <em>24 Juli 2026</em>
          </span>
        </a>

        <a class="nwp-mini" href="/news-detail">
          <span class="nwp-mini__thumb"><img src="{{ asset('assets/img/truck-tiberman.webp') }}" alt="" loading="lazy"></span>
          <span class="nwp-mini__body">
            <strong>7 Truk Tambang Terbesar di Dunia, Jangan Ngeri Lihat Ukurannya!</strong>
            <em>18 Agustus 2026</em>
          </span>
        </a>

        <a class="nwp-mini" href="/news-detail">
          <span class="nwp-mini__thumb"><img src="{{ asset('assets/img/after-sales-3.webp') }}" alt="" loading="lazy"></span>
          <span class="nwp-mini__body">
            <strong>11 Macam Alat Berat Tambang dan Kegunaannya</strong>
            <em>5 Agustus 2026</em>
          </span>
        </a>

        <a class="nwp-mini" href="/news-detail">
          <span class="nwp-mini__thumb"><img src="{{ asset('assets/img/plb-stock.webp') }}" alt="" loading="lazy"></span>
          <span class="nwp-mini__body">
            <strong>Daftar Pertambangan di Kalimantan: Emas, Batu Bara, dan Nikel</strong>
            <em>14 Agustus 2026</em>
          </span>
        </a>
      </aside>

    </div>
  </section>

  <!-- ===================== ARTIKEL TERKAIT ===================== -->
  <section class="nwp-cat">
    <div class="container">
      <h2 class="nwp-cat__head">Artikel Terkait</h2>
      <div class="news__grid">
        <a class="news-card" href="/news-detail">
          <div class="news-card__thumb"><img src="{{ asset('assets/img/tire-554.webp') }}" alt="Ban truk Uninest TiberMAX" loading="lazy"></div>
          <span class="news-card__date">2 September 2026</span>
          <h3>Perbedaan Ban Truk dan Ban Mobil dari Konstruksi hingga Penggunaannya</h3>
          <p>Ban adalah penghubung antara kendaraan dan permukaan jalan. Konstruksi ban truk dan ban mobil dirancang untuk beban dan medan yang sama sekali berbeda.</p>
        </a>
        <a class="news-card" href="/news-detail">
          <div class="news-card__thumb"><img src="{{ asset('assets/img/tires-strip.webp') }}" alt="Deretan ban siap kirim" loading="lazy"></div>
          <span class="news-card__date">28 Agustus 2026</span>
          <h3>Ban Tubeless atau Tube Type? Ban Radial atau Bias?</h3>
          <p>Empat istilah yang paling sering tertukar waktu memilih ban. Perbedaannya menentukan daya angkut, umur pakai, dan biaya perawatan.</p>
        </a>
        <a class="news-card" href="/news-detail">
          <div class="news-card__thumb"><img src="{{ asset('assets/img/news-3.webp') }}" alt="Dump truck di lokasi proyek" loading="lazy"></div>
          <span class="news-card__date">13 Agustus 2026</span>
          <h3>Dump Truck: Fungsi, Jenis, Komponen, dan Tips Memilih Ban yang Tepat untuk Operasional</h3>
          <p>Dump truck dirancang untuk mengangkut sekaligus menurunkan material dalam jumlah besar. Simak jenis, komponen, dan cara memilih bannya.</p>
        </a>
      </div>
    </div>
  </section>

</main>

@endsection
