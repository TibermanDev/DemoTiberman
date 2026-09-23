@extends('layouts.app')

@section('title', 'Tiberman — Importir Ban Truk & Alat Berat Terpercaya')
@section('description', 'Tiberman, importir ban truk & alat berat terpercaya dengan 15 SuperArea di seluruh Indonesia, 2 Pusat Logistik Berikat, dan armada delivery sendiri.')

@push('head')
<!-- Intro diputar saat home dibuka DARI LUAR situs (alamat diketik, bookmark,
     tab baru, link dari mesin pencari atau chat) dan setiap kali home
     di-REFRESH. Yang TIDAK memutarnya hanya perpindahan di dalam situs ini —
     produk / news / contact / superarea -> home — termasuk lewat tombol Back.
     Keputusannya diambil sebelum paint pertama supaya halaman tidak sempat
     berkedip duluan.
     Timer di bawah jaring pengaman kalau main.js gagal dimuat; main.js akan
     mengambil alih dan membatalkannya begitu video benar-benar mulai jalan. -->
<script>(function(){var d=document.documentElement;
try{if(window.matchMedia&&window.matchMedia('(prefers-reduced-motion: reduce)').matches)return;}catch(e){}
/* Tidak ada penanda di localStorage lagi (dulu 'tbm-intro-seen', sekali
   seumur pengunjung). Yang menentukan sekarang cuma DARI MANA halaman ini
   dibuka, dan urutan pemeriksaannya penting:

     1) reload -> SELALU putar, dan dicek paling awal. Pada refresh browser
        tetap membawa referrer navigasi aslinya, jadi kalau referrer diperiksa
        lebih dulu, refresh home sesudah datang dari /produk.html akan salah
        dianggap perpindahan internal dan intronya hilang.
     2) back_forward -> lewati. Sampai di home lewat Back/Forward berarti
        pengunjung memang sudah ada di dalam situs.
     3) referrer satu origin DAN halamannya berbeda -> lewati (pindah internal).
        '/' dan '/index.html' disamakan supaya home -> home (klik logo waktu
        sudah di home) tetap dihitung seperti refresh, bukan pindah internal.

   Kalau apa pun di sini tidak tersedia atau melempar, jatuhnya ke PUTAR —
   lebih baik intronya muncul daripada hilang diam-diam. */
var lewati=false;
try{
  var p=window.performance,
      e=p&&p.getEntriesByType&&p.getEntriesByType('navigation')[0],
      tipe=e?e.type:(p&&p.navigation?['navigate','reload','back_forward','prerender'][p.navigation.type]:'');
  if(tipe==='back_forward'){lewati=true;}
  else if(tipe!=='reload'&&document.referrer){
    var n=function(x){return x.replace(/\/index\.html$/,'/');},
        r=new URL(document.referrer,location.href);
    lewati=r.origin===location.origin&&n(r.pathname)!==n(location.pathname);
  }
}catch(err){lewati=false;}
if(lewati)return;
d.setAttribute('data-intro','on');
window.__tbmIntroBail=setTimeout(function(){
  d.removeAttribute('data-intro');
  var i=document.querySelector('[data-intro-root]');
  if(i)i.remove();
},12000);})();</script>
@endpush

@section('content')

<!-- ============================= INTRO ============================= -->
<div class="intro" data-intro-root>
  <video class="intro__video" data-intro-video
         autoplay muted playsinline preload="auto" disablepictureinpicture>
    <!-- WebM beralpha (Chrome/Edge/Firefox): latarnya transparan dan tidak
         memuat adegan gudang sama sekali, jadi halaman aslinya bisa muncul
         selagi logonya masih beranimasi. -->
    <source src="{{ asset('assets/img/animation-transparent.webm') }}" type="video/webm">
    <!-- Safari/iOS tidak bisa memutar WebM sama sekali, jadi otomatis jatuh
         ke sini: versi latar hitam, gudangnya ikut terbakar di videonya. -->
    <source src="{{ asset('assets/img/animation-black.mp4') }}" type="video/mp4">
  </video>
  <img class="intro__wordmark" data-intro-wordmark src="{{ asset('assets/img/logo-white.png') }}" alt="" aria-hidden="true">
  <div class="intro__bar" data-intro-bar><span data-intro-bar-fill></span></div>
</div>
<noscript><style>.intro{display:none!important}</style></noscript>
<!-- Kalau intronya tidak jadi diputar, elemennya langsung dibuang di sini juga
     — video 6 MB itu tetap diunduh browser walau CSS-nya display:none. -->
<script>if(!document.documentElement.hasAttribute('data-intro')){var i=document.querySelector('[data-intro-root]');if(i)i.remove();}</script>

<main>

  <!-- hero dipaku (position:sticky) sampai .superarea lewat; SuperArea yang
       opaque itulah yang naik menutupinya, jadi TIDAK ada jarak scroll tambahan. -->
  <div class="hero-stack">
    <!-- ============================= HERO ============================= -->
    <section class="hero">
      <div class="hero__stage">
        <div class="hero__media">
          <img src="{{ asset('assets/img/hero-warehouse-2.webp') }}" alt="Tim Tiberman di gudang ban" fetchpriority="high">
          <div class="hero__wordmark"><img src="{{ asset('assets/img/logo-white.png') }}" alt="Tiberman"></div>
        </div>
      </div>
    </section>

    <!-- Tutup lengkung: menjiplak lengkung yang sudah tercetak di
         hero-warehouse-2.webp (parabola puncak 13,94% tinggi gambar di tengah,
         3,71% di pinggir). Waktu belum discroll dia bertindih PERSIS di atas
         lengkung cetakan itu — hitam di atas hitam, jadi tidak kelihatan; begitu
         SuperArea naik, tepi yang menutupi gambar jadi melengkung, bukan lurus.

         Ditaruh di pembungkus, bukan di dalam .superarea, karena section itu
         overflow:hidden — elemen yang menjorok ke atas tepinya bakal kepotong.

         margin-bottom:-1px BUKAN kosmetik: tepi bawah SVG dan tepi atas section
         jatuh di baris piksel pecahan yang sama, dua-duanya di-antialias ~72%
         hitam dan tumpukannya cuma ~92% — muncul garis tipis selebar layar.
         Ditindihkan 1px supaya baris itu ketutup hitam penuh. -->
    <div class="sa-cover">
      <svg class="sa-cover__cap" viewBox="0 0 1000 61.3" preserveAspectRatio="none" aria-hidden="true" focusable="false">
        <path d="M0 45 Q500 -45 1000 45 L1000 61.3 L0 61.3 Z"/>
      </svg>
      <!-- ============================= 15 SUPERAREA ============================= -->
      <!-- Sorot ditaruh di LUAR .superarea dan paling akhir: (a) section itu
           overflow:hidden, jadi kalau di dalam, ekor yang naik ke gambar hero
           bakal kepotong rata di tepi lengkung; (b) ditaruh paling akhir supaya
           tercetak di atas tutup lengkung dan gambar hero, sehingga
           mix-blend-mode:screen-nya benar-benar mencampur cahaya dengan
           gambarnya — bukan berhenti mendadak di batas section. -->
      <section class="superarea" id="superarea">
        <div class="superarea__copy">
          <p class="eyebrow reveal">siap melayani Anda lebih dekat dengan</p>
          <h2 class="reveal" data-delay="80">15 SuperArea yang tersebar di<br>seluruh Indonesia</h2>
          <a class="btn btn--primary reveal" data-delay="160" href="{{ route('contact') }}">Check it !</a>
        </div>
      </section>
      <div class="superarea__glow" aria-hidden="true"></div>
      <!-- Bumi sengaja di LUAR <section> dan sesudah sorot: yang terakhir di DOM
           tercetak paling atas, jadi bumi menutupi tepi bawah sorot. Potongan
           PNG sorot yang mentok di sudut itu jadi tersembunyi di balik bumi,
           bukan berhenti sebagai garis lurus di tengah angkasa. -->
      <div class="superarea__globe" data-superarea>
        <!-- pin sudah tercetak di gambar; tombolnya dibuat assets/js/superarea-spots.js.
             Tombolnya membuka keterangan saat DIHOVER (bukan diklik), dan ada
             cincin yang berdenyut pelan di tiap pin supaya kelihatan bisa
             dipakai — bukan gambar biasa. -->
        <img class="superarea__earth" src="{{ asset('assets/img/earth-maps.webp') }}" width="2880" height="1171" loading="lazy" decoding="async" alt="Peta sebaran SuperArea Tiberman di Indonesia">
      </div>
    </div>
  </div>

  <!-- ============================= IMPORTIR + KATEGORI ============================= -->
  <section class="importir" id="produk">
    <!-- Latar section ini video. Posternya diambil dari frame pertama video
         itu sendiri (bukan category-section.webp yang bannya sudah terlanjur
         di depan), supaya tidak ada lompatan gambar waktu playback mulai —
         dan tetap jadi latar kalau videonya gagal dimuat.

         Dua sumber, sama seperti video intro: AV1/WebM dulu (jauh lebih kecil,
         didukung Chrome/Edge/Firefox dan Safari 17+ di perangkat yang mampu),
         sisanya otomatis jatuh ke H.264/MP4.

         Sengaja TANPA atribut autoplay: atribut itu bikin Chrome tetap
         mengunduh videonya walau preload="none", jadi pemutarannya dipicu
         dari main.js begitu sectionnya masuk viewport. -->
    <video class="importir__bg" data-bg-video
           muted playsinline preload="none"
           poster="{{ asset('assets/img/tires-moving-poster.webp') }}"
           aria-hidden="true" tabindex="-1" disablepictureinpicture>
      <source src="{{ asset('assets/img/tires-moving.webm') }}" type="video/webm">
      <source src="{{ asset('assets/img/tires-moving.mp4') }}" type="video/mp4">
    </video>
    <div class="importir__scrim" aria-hidden="true"></div>
    <div class="importir__inner">
      <h2 class="reveal">Importir Ban Truk &amp; Alat Berat<br>TERPERCAYA</h2>
      <div class="pills reveal" data-delay="120">
        <a class="pill" href="/kategori-produk/ban-truk">Truck &amp; Bus</a>
        <a class="pill" href="/kategori-produk/ban-truk/ban-truk-off-the-road">Mining Truck</a>
        <a class="pill" href="/kategori-produk/ban-loader">Loader</a>
        <a class="pill" href="/kategori-produk/ban-grader">Grader</a>
        <a class="pill" href="/kategori-produk/ban-forklift">Forklift</a>
        <a class="pill" href="/kategori-produk/ban-traktor">Tractor</a>
      </div>
    </div>
  </section>

  <!-- ============================= AKSESORIS ============================= -->
  <section class="accessories">
    <!-- Tutup lengkung yang menindih bagian bawah section video di atasnya.
         DUA path dengan bentuk identik, yang kedua digeser 12 satuan ke bawah:
         yang putih tercetak duluan, yang abu menimpanya, jadi yang tersisa di
         antara keduanya adalah pita putih setebal 12/80 tinggi kotak yang
         mengikuti lengkungnya persis.

         Titik ujung (a) dan titik kendali (c) tiap path simetris: puncak = (a+c)/2
         dan kedalaman busur = (a-c)/2. Sekarang 22/-22 (puncak 0) dan 40/-4
         (puncak 18) — kedalaman 22 satuan, pita putih 18 satuan. Puncak path
         putih HARUS >= 0; kalau negatif, bagian tengahnya terpotong tepi viewBox
         dan pita putihnya menipis di tengah sementara tetap tebal di pinggir. -->
    <svg class="accessories__cap" viewBox="0 0 1000 80" preserveAspectRatio="none" aria-hidden="true" focusable="false">
      <path class="accessories__cap-gap" d="M0 30 Q500 -30 1000 30 L1000 80 L0 80 Z"/>
      <path d="M0 48 Q500 -12 1000 48 L1000 80 L0 80 Z"/>
    </svg>
    <!-- Sengaja BUKAN .container: kelas itu dipakai hampir semua section dan
         mengunci lebar ke 1160px. Section ini perlu melebar sampai mepet tepi
         layar, jadi pembungkusnya sendiri supaya section lain tidak ikut
         berubah. -->
    <div class="accessories__wrap">
      <div class="accessories__grid">
        <div class="accessories__art reveal">
          <img src="{{ asset('assets/img/accessories-2.webp') }}" alt="Ban, velg, dan aksesoris pendukung" loading="lazy">
        </div>
        <div class="accessories__copy reveal" data-delay="100">
          <h2 class="h2">Lengkapi kebutuhan<br>Anda di satu tempat.</h2>
          <p class="lead">Tak hanya ban, kami juga menyediakan aksesoris pendukung seperti velg, ban dalam, flap, marset, dan O-ring dengan stok siap kirim.</p>
        </div>
      </div>
    </div>
  </section>

  <!-- Rel kartu kategori. Sengaja di luar .container supaya bisa full-bleed:
       kartu di ujung kiri/kanan memang boleh terpotong tepi layar, itu yang
       memberi sinyal "masih ada lanjutannya". Gerak otomatisnya dipasang
       main.js lewat data-velg-rail. -->
  <div class="velg-wrap">
    <div class="velg-cards" data-velg-rail>
      <article class="velg-card velg-card--tyre reveal" data-delay="200" style="--ar:1720/952">
        <div class="velg-card__img"><img src="{{ asset('assets/img/otr-tyre.png') }}" alt="Ban OTR untuk alat berat" loading="lazy"></div>
        <div class="velg-card__body">
          <h3>OTR TYRE<span>(Off The Road)</span></h3>
          <a class="btn btn--primary" href="/kategori-produk/ban-truk/ban-truk-off-the-road">Check it !</a>
        </div>
      </article>
      <article class="velg-card velg-card--tyre reveal" data-delay="300" style="--ar:1348/920">
        <div class="velg-card__img"><img src="{{ asset('assets/img/tbr-tyre.png') }}" alt="Ban TBR untuk truk dan bus" loading="lazy"></div>
        <div class="velg-card__body">
          <h3>TBR TYRE<span>(Truck Bus)</span></h3>
          <a class="btn btn--primary" href="/kategori-produk/ban-truk">Check it !</a>
        </div>
      </article>
      <article class="velg-card reveal" style="--ar:1203/999">
        <div class="velg-card__img"><img src="{{ asset('assets/img/velg-heavy-2.webp') }}" alt="Velg heavy duty" loading="lazy"></div>
        <div class="velg-card__body">
          <h3>VELG<span>Heavy-Duty</span></h3>
          <a class="btn btn--primary" href="/kategori-produk/velg-truk">Check it !</a>
        </div>
      </article>
      <article class="velg-card reveal" data-delay="100" style="--ar:1234/919">
        <div class="velg-card__img"><img src="{{ asset('assets/img/velg-light-2.webp') }}" alt="Velg light truck" loading="lazy"></div>
        <div class="velg-card__body">
          <h3>VELG<span>Light Truck</span></h3>
          <a class="btn btn--primary" href="/kategori-produk/velg-truk">Check it !</a>
        </div>
      </article>
    </div>
  </div>

  <!-- ============================= KENAPA HARUS TIBERMAN =============================
       Video gudang yang menyusut jadi isi tulisan, digerakkan oleh scroll.

       .why__track yang tinggi itu jarak scroll animasinya; .why__stage dipaku
       sticky setinggi layar selama track itu lewat. main.js cuma menghitung
       kemajuan scroll jadi tiga custom property; seluruh geraknya transform +
       opacity, jadi tidak ada layout ulang per frame.

       Teksnya HTML biasa (bukan <text> SVG) supaya bisa wrap dan ikut clamp()
       di layar sempit. Efek "video di dalam huruf" datang dari mix-blend-mode:
       lapisan .why__mask berwarna latar halaman menutupi video, dan huruf
       hitamnya jadi lubang — screen dengan putih = putih (menutup), screen
       dengan hitam = biarkan backdrop (video tembus). Di mode gelap dibalik:
       lapisan hitam + huruf putih + multiply. -->
  <section class="why" id="kenapa" data-why>
    <div class="why__track">
      <div class="why__stage">
        <video class="why__video" data-bg-video
               muted playsinline loop preload="none"
               poster="{{ asset('assets/img/warehouse-dark.webp') }}"
               aria-hidden="true" tabindex="-1" disablepictureinpicture>
          <!-- versi web: 1920x1080 CRF 30, 8 MB (aslinya 2560x1440 / 28,4 MB).
               1440p sempat dicoba dan hasilnya pecah: di layar 1920 dia harus
               di-upscale 1,33x, artefak kompresinya jadi kelihatan. -->
          <source src="{{ asset('assets/img/warehouse-loop-web.mp4') }}" type="video/mp4">
        </video>
        <!-- Kedua lapisan isinya SAMA PERSIS, termasuk baris kecil di atas judul.
             Di lapisan penutup baris itu diberi warna latar (jadi tak terlihat
             dan tidak ikut jadi lubang), tapi tetap memakan ruang — kalau cuma
             ada di salah satu lapisan, posisi judulnya bergeser dan pergantian
             ke teks merah terlihat meloncat. -->
        <div class="why__mask" aria-hidden="true">
          <div class="why__inner">
            <p>Dari sekian banyak supplier lain</p>
            <span>Kenapa Harus Tiberman ?</span>
          </div>
        </div>
        <!-- Salinan tulisan yang sama, berwarna merah, dimunculkan di ujung
             animasi. Jadi hurufnya berisi video selama menyusut, lalu berubah
             jadi merah begitu sampai ukuran akhir — pola yang sama dipakai
             halaman MacBook Pro (video di dalam huruf -> teks biru muda). -->
        <div class="why__tint" aria-hidden="true">
          <div class="why__inner">
            <p>Dari sekian banyak supplier lain</p>
            <span>Kenapa Harus Tiberman ?</span>
          </div>
        </div>
        <h2 class="sr-only">Kenapa Harus Tiberman ?</h2>
      </div>
    </div>
  </section>

  <!-- Stok Aman -->
  <section class="pilar">
    <div class="container">
      <h2 class="pilar__title reveal">Stok Aman</h2>

      <article class="feature-card reveal" data-delay="120">
        <div class="feature-card__body">
          <div class="feature-card__brand">
            <img src="{{ asset('assets/img/logo-fiemin.png') }}" alt="PT Fie Min Logistics">
            <span>PT Fie Min Logistics</span>
          </div>
          <h3>Memiliki 2 Pusat Logistik<br>Berikat (PLB) sendiri</h3>
          <p>Tiberman Group didukung oleh 2 Pusat Logistik Berikat (PLB) yang dikelola <b>PT Fiemin Logistics</b>. yang berlokasi di <b>Gresik</b> dan <b>Mojokerto</b></p>
          <a class="btn btn--primary" href="{{ route('contact') }}">Check it !</a>
        </div>
        <div class="feature-card__media">
          <img src="{{ asset('assets/img/plb-stock.webp') }}" alt="Gudang stok ban Pusat Logistik Berikat" loading="lazy">
        </div>
      </article>

      <div class="plb-grid">
        <article class="plb reveal">
          <div class="plb__img"><img src="{{ asset('assets/img/plb-mojokerto.webp') }}" alt="PLB Mojokerto" loading="lazy"></div>
          <div class="plb__foot">
            <div class="plb__name">Mojokerto</div>
            <div class="plb__stats">
              <div class="plb__stat"><span>kapasitas :</span><strong>150 kontainer</strong></div>
            </div>
          </div>
        </article>
        <article class="plb reveal" data-delay="150">
          <div class="plb__img"><img src="{{ asset('assets/img/plb-gresik.webp') }}" alt="PLB Gresik" loading="lazy"></div>
          <div class="plb__foot">
            <div class="plb__name">Gresik</div>
            <div class="plb__stats">
              <div class="plb__stat"><span>kapasitas :</span><strong>250 kontainer</strong></div>
            </div>
          </div>
        </article>
      </div>
    </div>
  </section>

  <!-- Pengiriman Aman -->
  <section class="pilar" style="padding-top:0">
    <div class="container">
      <h2 class="pilar__title reveal">Pengiriman Aman</h2>

      <article class="feature-card feature-card--illus reveal" data-delay="120">
        <div class="feature-card__body">
          <div class="feature-card__brand feature-card__brand--halilintar">
            <img src="{{ asset('assets/img/logo-halilintar.png') }}" alt="Halilintar">
          </div>
          <h3>Aman sampai tujuan dengan<br>armada delivery sendiri</h3>
          <p>Tiberman Group didukung oleh layanan distribusi yang dikelola oleh PT Hantar Lintas Nusantara (Halilintar) memastikan setiap pengiriman aman hingga sampai ke tangan anda.</p>
          <a class="btn btn--primary" href="{{ route('contact') }}">Check it !</a>
        </div>
        <div class="feature-card__media">
          <img src="{{ asset('assets/img/truck-tiberman.webp') }}" alt="Ilustrasi truk pengiriman Tiberman bermuatan ban" loading="lazy">
        </div>
      </article>

      <div class="photo-duo">
        <img class="reveal" src="{{ asset('assets/img/plb-truck.webp') }}" alt="Truk kontainer Tiberman di gudang" loading="lazy">
        <img class="reveal" data-delay="150" src="{{ asset('assets/img/delivery-forklift.webp') }}" alt="Forklift memuat ban ke kontainer" loading="lazy">
      </div>
    </div>
  </section>

  <!-- ============================= AFTER SALES ============================= -->
  <section class="aftersales">
    <div class="container">
      <h2 class="pilar__title reveal">After Sales</h2>
      <!-- Judul & deskripsi tiap layanan ditempel di slide-nya sendiri
           (data-title/data-desc); main.js menyalinnya ke caption di bawah
           setiap kali slide tengah berganti. Caption di HTML diisi layanan
           yang pertama kali jadi slide tengah, jadi tanpa JS tetap nyambung. -->
      <div class="coverflow" data-coverflow>
        <div class="coverflow__track">
          <div class="coverflow__item" data-title="Tyre Repair" data-desc="Layanan jaminan perbaikan kerusakan ban, sesuai dengan ketentuan yang berlaku.">
            <img src="{{ asset('assets/img/after-sales-5.webp') }}" alt="Teknisi Tiberman memperbaiki tapak ban truk" loading="lazy" decoding="async">
          </div>
          <div class="coverflow__item" data-title="Tyre Lab" data-desc="Konsultasi online segala permasalahan ban 24 jam.">
            <img src="{{ asset('assets/img/after-sales-4.webp') }}" alt="Staf Tiberman melayani konsultasi lewat ponsel di area tambang" loading="lazy" decoding="async">
          </div>
          <div class="coverflow__item" data-title="Site Visit" data-desc="Kunjungan eksklusif tire engineer profesional ke site customer.">
            <img src="{{ asset('assets/img/after-sales-3.webp') }}" alt="Dua tire engineer meninjau alat berat di site customer" loading="lazy" decoding="async">
          </div>
          <div class="coverflow__item" data-title="Learning Center" data-desc="Layanan pembelajaran online seputar ban bersertifikat.">
            <img src="{{ asset('assets/img/after-sales-2.webp') }}" alt="Peserta mengikuti kelas online Tiberman lewat laptop" loading="lazy" decoding="async">
          </div>
          <div class="coverflow__item" data-title="Privilege Card" data-desc="Benefit lebih untuk customer loyal dengan persyaratan khusus.">
            <img src="{{ asset('assets/img/after-sales-1.webp') }}" alt="Kartu Privilege Tiberman tingkat Silver sampai Platinum" loading="lazy" decoding="async">
          </div>
        </div>
      </div>
      <div class="dots" data-coverflow-dots></div>
      <div class="aftersales__foot">
        <button class="coverflow__nav coverflow__nav--prev" type="button" aria-label="Sebelumnya">
          <svg viewBox="0 0 12 12" fill="currentColor" aria-hidden="true"><path d="M9 0 3 6l6 6z"/></svg>
        </button>
        <div class="aftersales__caption reveal">
          <h3><span>Tiberman</span><span data-coverflow-title>Site Visit</span></h3>
          <p data-coverflow-desc>Kunjungan eksklusif tire engineer profesional ke site customer.</p>
        </div>
        <button class="coverflow__nav coverflow__nav--next" type="button" aria-label="Berikutnya">
          <svg viewBox="0 0 12 12" fill="currentColor" aria-hidden="true"><path d="M3 0l6 6-6 6z"/></svg>
        </button>
      </div>
    </div>
  </section>

  <!-- ============================= TESTIMONI ============================= -->
  <section class="testi">
    <div class="testi__grid">
      <div class="testi__intro reveal">
        <h2 class="h2">Testimoni<br>Pelanggan</h2>
        <p>Berikut beberapa testimoni dari pelanggan yang telah menggunakan produk ban kami.</p>
      </div>
      <div class="testi__rail" data-drag-rail>
        <article class="testi-card">
          <div class="testi-card__quote">&ldquo;</div>
          <div class="testi-card__avatar">AR</div>
          <p>Bannya sangat bagus, sampai saya pengen beli lagi walau gatau buat apa, terimakasih Tiberman!</p>
          <div class="testi-card__who"><strong>Agnes Remi</strong><span>Mbak2 tambang</span></div>
        </article>
        <article class="testi-card">
          <div class="testi-card__quote">&ldquo;</div>
          <div class="testi-card__avatar">BS</div>
          <p>Stok selalu ada dan pengiriman cepat. Armada kami tidak pernah menunggu ban lagi.</p>
          <div class="testi-card__who"><strong>Bagus Santosa</strong><span>Fleet Manager</span></div>
        </article>
        <article class="testi-card">
          <div class="testi-card__quote">&ldquo;</div>
          <div class="testi-card__avatar">DP</div>
          <p>Layanan free tyre repair-nya benar-benar terpakai. Support after sales-nya cepat tanggap.</p>
          <div class="testi-card__who"><strong>Dimas Prakoso</strong><span>Owner Dump Truck</span></div>
        </article>
        <article class="testi-card">
          <div class="testi-card__quote">&ldquo;</div>
          <div class="testi-card__avatar">RN</div>
          <p>Sidewall-nya kuat untuk jalur tambang. Umur pakainya jauh lebih panjang dari ban sebelumnya.</p>
          <div class="testi-card__who"><strong>Rizky Nugraha</strong><span>Supervisor Hauling</span></div>
        </article>
      </div>
    </div>
  </section>

</main>

@endsection

@push('scripts')
<script src="{{ asset('assets/js/superarea-spots.js') }}" defer></script>
@endpush
