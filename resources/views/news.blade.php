@extends('layouts.app')

@section('title', 'News — Tiberman')
@section('description', 'Kabar terbaru dan wawasan seputar ban truk, alat berat, dan dunia pertambangan dari Tiberman.')
@section('body-class', 'subpage')

@section('content')

<main class="nwp nwp--flat">

  <!-- Banner disusun dari EMPAT lapis, bukan satu gambar jadi: tinggi
       bandnya bisa ditentukan sendiri, lockup "Tiberman News" punya ukuran
       minimum sendiri, dan ban kiri-kanan bisa dikecilkan terpisah di layar
       sempit. Dengan satu gambar utuh semuanya ikut menyusut bersama rasio
       9,5:1 dan lockup-nya tinggal belasan piksel di ponsel. -->
  <div class="nwp__banner">
    <img class="nwp__banner-bg" src="{{ asset('assets/img/warehouse-trans-news.webp') }}" width="2400" height="252" alt="" aria-hidden="true" fetchpriority="high">
    <img class="nwp__banner-tyre nwp__banner-tyre--l" src="{{ asset('assets/img/tyre-left.webp') }}" width="700" height="520" alt="" aria-hidden="true">
    <img class="nwp__banner-tyre nwp__banner-tyre--r" src="{{ asset('assets/img/tyre-right.webp') }}" width="700" height="520" alt="" aria-hidden="true">
    <img class="nwp__banner-logo" src="{{ asset('assets/img/tbm-news.webp') }}" width="1200" height="173" alt="Tiberman News">
  </div>

  <!-- Tab kategori: memilih salah satunya menyembunyikan section kategori
       lain (assets/js/news-page.js). "Home" menampilkan semuanya. -->
  <nav class="nwp__tabs" data-news-tabs aria-label="Kategori artikel">
    <button class="nwp-tab is-active" type="button" data-cat="all">Home</button>
    <button class="nwp-tab" type="button" data-cat="alat-berat">Alat Berat</button>
    <button class="nwp-tab" type="button" data-cat="ban">Ban</button>
    <button class="nwp-tab" type="button" data-cat="pertambangan">Pertambangan</button>
    <button class="nwp-tab" type="button" data-cat="tips">Tips</button>
    <button class="nwp-tab" type="button" data-cat="info-produk">Info Produk</button>
    <button class="nwp-tab" type="button" data-cat="info-lain">Info Lain</button>
  </nav>

  <!-- ===================== SOROTAN + POSTINGAN TERBARU ===================== -->
  <section class="nwp-top" data-news-top>
    <div class="container nwp-top__grid">

      <article class="nwp-feature">
        <a class="nwp-feature__thumb" href="/news-detail">
          <img src="{{ asset('assets/img/plb-truck.webp') }}" alt="Barisan truk di area pergudangan Tiberman" fetchpriority="high">
        </a>
        <span class="nwp-feature__date">10 September 2026</span>
        <h2><a href="/news-detail">Fleet Tire Management: Cara Mengontrol Biaya Ban Puluhan hingga Ratusan Truk</a></h2>
        <p>Mengelola lima hingga sepuluh unit truk mungkin masih bisa dilakukan dengan pengawasan kasat mata dan pencatatan sederhana. Namun bagaimana jika armadanya sudah ratusan unit?</p>
      </article>

      <aside class="nwp-latest">
        <h2 class="nwp-latest__head">Latest Post</h2>

        <a class="nwp-mini" href="/news-detail">
          <span class="nwp-mini__thumb"><img src="{{ asset('assets/img/after-sales-4.webp') }}" alt="" loading="lazy"></span>
          <span class="nwp-mini__body">
            <strong>Mengenal Arti Warna Baju Proyek dan Helm Proyek di Lapangan</strong>
            <em>10 September 2026</em>
          </span>
        </a>

        <a class="nwp-mini" href="/news-detail">
          <span class="nwp-mini__thumb"><img src="{{ asset('assets/img/tire-554.webp') }}" alt="" loading="lazy"></span>
          <span class="nwp-mini__body">
            <strong>Perbedaan Ban Truk dan Ban Mobil dari Konstruksi hingga Penggunaannya</strong>
            <em>2 September 2026</em>
          </span>
        </a>

        <a class="nwp-mini" href="/news-detail">
          <span class="nwp-mini__thumb"><img src="{{ asset('assets/img/delivery-forklift.webp') }}" alt="" loading="lazy"></span>
          <span class="nwp-mini__body">
            <strong>Di Balik Jalan Kokoh &amp; Rahasia Pemilihan Ban Alat Berat Compactor</strong>
            <em>29 Agustus 2026</em>
          </span>
        </a>

        <a class="nwp-mini" href="/news-detail">
          <span class="nwp-mini__thumb"><img src="{{ asset('assets/img/dumptruck.webp') }}" alt="" loading="lazy"></span>
          <span class="nwp-mini__body">
            <strong>Motor Grader, Penjaga Kelancaran Hauling Road</strong>
            <em>28 Agustus 2026</em>
          </span>
        </a>
      </aside>

    </div>
  </section>

  <!-- ===================== DUNIA ALAT BERAT ===================== -->
  <section class="nwp-cat" data-cat="alat-berat">
    <div class="container">
      <!-- Headline utama: hanya tampil kalau kategori ini yang dipilih
           (kelas is-solo dari assets/js/news-page.js). Di tab Home
           perannya sudah diambil kartu sorotan + "Latest Post". -->
      <a class="nwp-hero" href="/news-detail">
        <img src="{{ asset('assets/img/plb-truck.webp') }}" alt="" aria-hidden="true" loading="lazy">
        <span class="nwp-hero__body">
          <span class="nwp-hero__kicker">Dunia Alat Berat</span>
          <strong>Fleet Tire Management: Cara Mengontrol Biaya Ban Puluhan hingga Ratusan Truk</strong>
          <span class="nwp-hero__cta">Selengkapnya &rarr;</span>
        </span>
      </a>
      <h2 class="nwp-cat__head">Dunia Alat Berat</h2>
      <div class="news__grid">
        <a class="news-card" href="/news-detail">
          <div class="news-card__thumb"><img src="{{ asset('assets/img/plb-truck.webp') }}" alt="Armada truk milik pelanggan Tiberman" loading="lazy"></div>
          <span class="news-card__date">10 September 2026</span>
          <h3>Fleet Tire Management: Cara Mengontrol Biaya Ban Puluhan hingga Ratusan Truk</h3>
          <p>Mengelola lima hingga sepuluh unit truk masih bisa diawasi kasat mata. Begitu armadanya ratusan, biaya ban hanya bisa dikendalikan lewat pencatatan yang rapi.</p>
        </a>
        <a class="news-card" href="/news-detail">
          <div class="news-card__thumb"><img src="{{ asset('assets/img/tire-554.webp') }}" alt="Ban truk Uninest TiberMAX" loading="lazy"></div>
          <span class="news-card__date">2 September 2026</span>
          <h3>Perbedaan Ban Truk dan Ban Mobil dari Konstruksi hingga Penggunaannya</h3>
          <p>Ban adalah penghubung antara kendaraan dan permukaan jalan. Konstruksi ban truk dan ban mobil dirancang untuk beban dan medan yang sama sekali berbeda.</p>
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

  <!-- ===================== PENGETAHUAN BAN ===================== -->
  <section class="nwp-cat" data-cat="ban">
    <div class="container">
      <!-- Headline utama: hanya tampil kalau kategori ini yang dipilih
           (kelas is-solo dari assets/js/news-page.js). Di tab Home
           perannya sudah diambil kartu sorotan + "Latest Post". -->
      <a class="nwp-hero" href="/news-detail">
        <img src="{{ asset('assets/img/tires-strip.webp') }}" alt="" aria-hidden="true" loading="lazy">
        <span class="nwp-hero__body">
          <span class="nwp-hero__kicker">Pengetahuan Ban</span>
          <strong>Ban Tubeless atau Tube Type? Ban Radial atau Bias?</strong>
          <span class="nwp-hero__cta">Selengkapnya &rarr;</span>
        </span>
      </a>
      <h2 class="nwp-cat__head">Pengetahuan Ban</h2>
      <div class="news__grid">
        <a class="news-card" href="/news-detail">
          <div class="news-card__thumb"><img src="{{ asset('assets/img/tires-strip.webp') }}" alt="Deretan ban siap kirim" loading="lazy"></div>
          <span class="news-card__date">28 Agustus 2026</span>
          <h3>Ban Tubeless atau Tube Type? Ban Radial atau Bias?</h3>
          <p>Empat istilah yang paling sering tertukar waktu memilih ban. Perbedaannya bukan sekadar nama, tapi menentukan daya angkut, umur pakai, dan biaya perawatan.</p>
        </a>
        <a class="news-card" href="/news-detail">
          <div class="news-card__thumb"><img src="{{ asset('assets/img/banner-tyre.webp') }}" alt="Ban OTR untuk dump truck tambang" loading="lazy"></div>
          <span class="news-card__date">7 Oktober 2025</span>
          <h3>Dump Truck di Tambang: Lebih Baik Pakai Ban Bias atau Radial?</h3>
          <p>Di tengah deru mesin dan debu yang mengepul, pilihan konstruksi ban menentukan berapa rit yang sanggup ditempuh sebelum unit harus masuk bengkel.</p>
        </a>
        <a class="news-card" href="/news-detail">
          <div class="news-card__thumb"><img src="{{ asset('assets/img/tire-tread.webp') }}" alt="Pola telapak ban" loading="lazy"></div>
          <span class="news-card__date">10 September 2026</span>
          <h3>Fleet Tire Management: Cara Mengontrol Biaya Ban Puluhan hingga Ratusan Truk</h3>
          <p>Umur pakai ban adalah komponen biaya terbesar kedua setelah bahan bakar. Mengukurnya per unit adalah langkah pertama menekan biaya armada.</p>
        </a>
      </div>
    </div>
  </section>

  <!-- ===================== DUNIA PERTAMBANGAN ===================== -->
  <section class="nwp-cat" data-cat="pertambangan">
    <div class="container">
      <!-- Headline utama: hanya tampil kalau kategori ini yang dipilih
           (kelas is-solo dari assets/js/news-page.js). Di tab Home
           perannya sudah diambil kartu sorotan + "Latest Post". -->
      <a class="nwp-hero" href="/news-detail">
        <img src="{{ asset('assets/img/news-2.webp') }}" alt="" aria-hidden="true" loading="lazy">
        <span class="nwp-hero__body">
          <span class="nwp-hero__kicker">Dunia Pertambangan</span>
          <strong>Dampak Naiknya Harga Emas pada Industri Ban Alat Berat</strong>
          <span class="nwp-hero__cta">Selengkapnya &rarr;</span>
        </span>
      </a>
      <h2 class="nwp-cat__head">Dunia Pertambangan</h2>
      <div class="news__grid">
        <a class="news-card" href="/news-detail">
          <div class="news-card__thumb"><img src="{{ asset('assets/img/news-2.webp') }}" alt="Pemandangan udara area tambang" loading="lazy"></div>
          <span class="news-card__date">4 September 2025</span>
          <h3>Dampak Naiknya Harga Emas pada Industri Ban Alat Berat</h3>
          <p>Dari pasar global ke ban alat berat di tambang: kenaikan harga emas mendorong produksi, dan produksi yang naik langsung terasa pada kebutuhan ban OTR.</p>
        </a>
        <a class="news-card" href="/news-detail">
          <div class="news-card__thumb"><img src="{{ asset('assets/img/warehouse-dark.webp') }}" alt="Stok ban di gudang Tiberman" loading="lazy"></div>
          <span class="news-card__date">21 Agustus 2026</span>
          <h3>Inilah 10 Perusahaan Tambang Terbesar di Indonesia</h3>
          <p>Dari batu bara di Kalimantan sampai nikel di Sulawesi, sepuluh nama ini menggerakkan sebagian besar aktivitas pertambangan nasional.</p>
        </a>
        <a class="news-card" href="/news-detail">
          <div class="news-card__thumb"><img src="{{ asset('assets/img/plb-stock.webp') }}" alt="Gudang stok ban alat berat" loading="lazy"></div>
          <span class="news-card__date">14 Agustus 2026</span>
          <h3>Daftar Pertambangan di Kalimantan: Emas, Batu Bara, dan Nikel</h3>
          <p>Kalimantan menyimpan tiga komoditas sekaligus, dan tiap komoditas menuntut spesifikasi armada serta ban yang berbeda.</p>
        </a>
      </div>
    </div>
  </section>

  <!-- ===================== TIPS & PANDUAN ===================== -->
  <section class="nwp-cat" data-cat="tips">
    <div class="container">
      <!-- Headline utama: hanya tampil kalau kategori ini yang dipilih
           (kelas is-solo dari assets/js/news-page.js). Di tab Home
           perannya sudah diambil kartu sorotan + "Latest Post". -->
      <a class="nwp-hero" href="/news-detail">
        <img src="{{ asset('assets/img/after-sales-4.webp') }}" alt="" aria-hidden="true" loading="lazy">
        <span class="nwp-hero__body">
          <span class="nwp-hero__kicker">Tips &amp; Panduan</span>
          <strong>Mengenal Arti Warna Baju Proyek dan Helm Proyek di Lapangan</strong>
          <span class="nwp-hero__cta">Selengkapnya &rarr;</span>
        </span>
      </a>
      <h2 class="nwp-cat__head">Tips &amp; Panduan</h2>
      <div class="news__grid">
        <a class="news-card" href="/news-detail">
          <div class="news-card__thumb"><img src="{{ asset('assets/img/after-sales-4.webp') }}" alt="Pekerja dengan atribut keselamatan di lapangan" loading="lazy"></div>
          <span class="news-card__date">10 September 2026</span>
          <h3>Mengenal Arti Warna Baju Proyek dan Helm Proyek di Lapangan</h3>
          <p>Warna helm dan rompi di area proyek bukan soal selera. Tiap warna menandai peran, dan salah baca bisa berakibat fatal saat keadaan darurat.</p>
        </a>
        <a class="news-card" href="/news-detail">
          <div class="news-card__thumb"><img src="{{ asset('assets/img/delivery-forklift.webp') }}" alt="Alat berat compactor di proyek jalan" loading="lazy"></div>
          <span class="news-card__date">29 Agustus 2026</span>
          <h3>Di Balik Jalan Kokoh &amp; Rahasia Pemilihan Ban Alat Berat Compactor</h3>
          <p>Jalan yang padat dan rata berawal dari compactor. Ban yang dipakainya menentukan kerataan hasil pemadatan sekaligus kenyamanan operator.</p>
        </a>
        <a class="news-card" href="/news-detail">
          <div class="news-card__thumb"><img src="{{ asset('assets/img/after-sales-3.webp') }}" alt="Alat berat di medan berbatu" loading="lazy"></div>
          <span class="news-card__date">5 Agustus 2026</span>
          <h3>11 Macam Alat Berat Tambang dan Kegunaannya</h3>
          <p>Excavator, bulldozer, wheel loader, sampai articulated dump truck — kenali fungsi tiap unit sebelum menentukan ban yang dipasang.</p>
        </a>
      </div>
    </div>
  </section>

  <!-- ===================== INFO PRODUK ===================== -->
  <section class="nwp-cat" data-cat="info-produk">
    <div class="container">
      <!-- Headline utama: hanya tampil kalau kategori ini yang dipilih
           (kelas is-solo dari assets/js/news-page.js). Di tab Home
           perannya sudah diambil kartu sorotan + "Latest Post". -->
      <a class="nwp-hero" href="/news-detail">
        <img src="{{ asset('assets/img/dumptruck.webp') }}" alt="" aria-hidden="true" loading="lazy">
        <span class="nwp-hero__body">
          <span class="nwp-hero__kicker">Info Produk</span>
          <strong>Motor Grader, Penjaga Kelancaran Hauling Road</strong>
          <span class="nwp-hero__cta">Selengkapnya &rarr;</span>
        </span>
      </a>
      <h2 class="nwp-cat__head">Info Produk</h2>
      <div class="news__grid">
        <a class="news-card" href="/news-detail">
          <div class="news-card__thumb"><img src="{{ asset('assets/img/dumptruck.webp') }}" alt="Motor grader di hauling road" loading="lazy"></div>
          <span class="news-card__date">28 Agustus 2026</span>
          <h3>Motor Grader, Penjaga Kelancaran Hauling Road</h3>
          <p>Hauling road yang mulus memangkas waktu siklus angkut. Di sinilah motor grader dan ban yang tepat memegang peran.</p>
        </a>
        <a class="news-card" href="/news-detail">
          <div class="news-card__thumb"><img src="{{ asset('assets/img/truck-tiberman.webp') }}" alt="Truk tambang berukuran besar" loading="lazy"></div>
          <span class="news-card__date">18 Agustus 2026</span>
          <h3>7 Truk Tambang Terbesar di Dunia, Jangan Ngeri Lihat Ukurannya!</h3>
          <p>Tujuh raksasa pengangkut material ini punya ban setinggi orang dewasa — dan tiap satu bannya seharga sebuah mobil.</p>
        </a>
        <a class="news-card" href="/news-detail">
          <div class="news-card__thumb"><img src="{{ asset('assets/img/velg-heavy.webp') }}" alt="Velg untuk alat berat" loading="lazy"></div>
          <span class="news-card__date">2 Agustus 2026</span>
          <h3>Memilih Velg &amp; Tube yang Sepadan dengan Ban Alat Berat Anda</h3>
          <p>Ban yang benar tapi velg yang tidak sepadan tetap berujung pada umur pakai yang pendek. Ini patokan memilih pasangannya.</p>
        </a>
      </div>
    </div>
  </section>

  <!-- ===================== INFO LAIN ===================== -->
  <section class="nwp-cat" data-cat="info-lain">
    <div class="container">
      <!-- Headline utama: hanya tampil kalau kategori ini yang dipilih
           (kelas is-solo dari assets/js/news-page.js). Di tab Home
           perannya sudah diambil kartu sorotan + "Latest Post". -->
      <a class="nwp-hero" href="/news-detail">
        <img src="{{ asset('assets/img/news-1.webp') }}" alt="" aria-hidden="true" loading="lazy">
        <span class="nwp-hero__body">
          <span class="nwp-hero__kicker">Info Lain</span>
          <strong>Tiberman Sabet Dua Rekor MURI di Tiberman Expo 2026</strong>
          <span class="nwp-hero__cta">Selengkapnya &rarr;</span>
        </span>
      </a>
      <h2 class="nwp-cat__head">Info Lain</h2>
      <div class="news__grid">
        <a class="news-card" href="/news-detail">
          <div class="news-card__thumb"><img src="{{ asset('assets/img/news-1.webp') }}" alt="Penyerahan dua Rekor MURI di Tiberman Expo 2026" loading="lazy"></div>
          <span class="news-card__date">31 Juli 2026</span>
          <h3>Tiberman Sabet Dua Rekor MURI di Tiberman Expo 2026</h3>
          <p>PT Tiga Berlian Mandiri menorehkan prestasi tingkat nasional dengan memecahkan dua Rekor MURI sekaligus di ajang Tiberman Expo 2026.</p>
        </a>
        <a class="news-card" href="/news-detail">
          <div class="news-card__thumb"><img src="{{ asset('assets/img/news-2.webp') }}" alt="Pemandangan udara area tambang di Indonesia" loading="lazy"></div>
          <span class="news-card__date">8 Agustus 2026</span>
          <h3>Perbedaan Geografis Tambang di Indonesia dan Strategi Pemilihan Ban Alat Berat</h3>
          <p>Kondisi geografis tiap lokasi tambang menentukan spesifikasi ban OTR yang dipakai — dari rawa Kalimantan sampai bukit berbatu Sulawesi.</p>
        </a>
        <a class="news-card" href="/news-detail">
          <div class="news-card__thumb"><img src="{{ asset('assets/img/plb-gresik.webp') }}" alt="Pusat Logistik Berikat Tiberman" loading="lazy"></div>
          <span class="news-card__date">24 Juli 2026</span>
          <h3>Jenis Bahan Galian Tambang (Golongan A, B, dan C) di Indonesia</h3>
          <p>Golongan A, B, dan C membedakan bahan galian menurut kepentingannya bagi negara. Ini yang membedakan ketiganya di lapangan.</p>
        </a>
      </div>
    </div>
  </section>

</main>

@endsection

@push('scripts')
<script src="{{ asset('assets/js/news-page.js') }}" defer></script>
@endpush
