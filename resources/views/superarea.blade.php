@extends('layouts.app')

@section('title', 'SuperArea — Tiberman')
@section('description', '15 SuperArea Tiberman yang tersebar dari Sumatra sampai Maluku & Papua, siap melayani kebutuhan ban truk & alat berat lebih dekat.')
@section('body-class', 'subpage')

@section('content')

<!-- ============================= SUPERAREA =============================
     Latar bumi (maps-superarea): pita cakrawala yang dipaku ke DASAR
     halaman dan selebar layar, isinya mengambang di atasnya.
     CATATAN: gambar lama (earth-superarea) adalah komposit yang sekaligus
     memuat ban kiri-kanan dan 16 pin merah yang tercetak. maps-superarea
     tidak punya keduanya — cuma bumi — jadi ban dan pin itu hilang dari
     halaman ini. -->
<main class="sa">
  <!-- Sorot merah di tepi atas: aset top-shadow (PNG beralpha), bukan
       radial-gradient buatan CSS. -->
  <img class="sa__glow" src="{{ asset('assets/img/top-shadow.webp') }}" width="1920" height="1181" alt="" aria-hidden="true">
  <div class="sa__bg" aria-hidden="true">
    <!-- webp langsung, sama seperti earth-maps di index.html: PNG aslinya
         disimpan sebagai file sumber saja dan tidak dirujuk dari markup. -->
    <img src="{{ asset('assets/img/maps-superarea.webp') }}" width="2880" height="412" alt="" fetchpriority="high">
  </div>
  <!-- Lapisan titik yang bisa diklik (assets/js/superarea-spots.js) DIMATIKAN
       sementara: pin-nya dulu tercetak di earth-superarea.webp, dan
       maps-superarea.webp tidak punya pin sama sekali — di pita cakrawala yang
       cuma setinggi 412px itu Indonesia tinggal sesobek tipis di tepi bawah,
       jadi tombolnya akan menyorot laut kosong.

       Kalau nanti ada gambar bumi bertepi pin lagi, hidupkan kembali dengan
       mengganti data-superarea-off menjadi data-superarea (tabel koordinat
       SPOTS_PAGE-nya masih utuh di assets/js/superarea-spots.js) dan
       kembalikan aspect-ratio .sa__pins ke rasio gambar yang dipakai. -->
  <div class="sa__pins" data-superarea-off="page"></div>

  <div class="sa__inner">
    <div class="sa__head">
      <img class="sa__wordmark" src="{{ asset('assets/img/logo-white.png') }}" alt="Tiberman" width="1000" height="180">
      <p class="sa__kicker">SUPERAREA</p>
    </div>

    <!-- Tab pulau: memfilter kartu di bawahnya (assets/js/superarea-page.js) -->
    <div class="sa__tabs" data-sa-tabs role="tablist" aria-label="Pilih pulau">
      <button class="sa-tab is-active" type="button" role="tab" aria-selected="true"  data-region="jawa">Jawa</button>
      <button class="sa-tab"           type="button" role="tab" aria-selected="false" data-region="sumatra">Sumatra</button>
      <button class="sa-tab"           type="button" role="tab" aria-selected="false" data-region="kalimantan">Kalimantan</button>
      <button class="sa-tab"           type="button" role="tab" aria-selected="false" data-region="sulawesi">Sulawesi</button>
      <button class="sa-tab"           type="button" role="tab" aria-selected="false" data-region="maluku-papua">Maluku &amp; Papua</button>
    </div>

    <!-- CATATAN: foto tiap kartu masih memakai stok foto yang sudah ada di
         repo sebagai placeholder — tinggal ganti src-nya dengan foto kota /
         kantor masing-masing SuperArea. -->
    <div class="sa__grid" data-sa-grid>

      <article class="sa-card" data-region="jawa">
        <span class="sa-card__img"><img src="{{ asset('assets/img/plb-truck.webp') }}" alt="SuperArea Jabodetabek" loading="lazy"></span>
        <strong>Jabodetabek</strong>
        <span>Komplek Pergudangan Cahaya, Jl. Nurul Huda No.34, Jatimulya, Bekasi</span>
      </article>

      <article class="sa-card" data-region="jawa">
        <span class="sa-card__img"><img src="{{ asset('assets/img/warehouse-dark.webp') }}" alt="SuperArea Surabaya" loading="lazy"></span>
        <strong>Surabaya <em>( Head Office )</em></strong>
        <span>Jl. Mustika No.10, Ngagel, Kec. Wonokromo, Kota Surabaya</span>
      </article>

      <article class="sa-card" data-region="jawa">
        <span class="sa-card__img"><img src="{{ asset('assets/img/plb-mojokerto.webp') }}" alt="SuperArea Mojokerto" loading="lazy"></span>
        <strong>Mojokerto</strong>
        <span>Pergudangan Fie Min Logistics, Jl. Raya Pacing, Kec. Bangsal, Mojokerto</span>
      </article>

      <article class="sa-card" data-region="jawa">
        <span class="sa-card__img"><img src="{{ asset('assets/img/plb-gresik.webp') }}" alt="SuperArea Gresik" loading="lazy"></span>
        <strong>Gresik</strong>
        <span>Pergudangan Fie Min Logistics, Jl. Raya Bungah, Kec. Bungah, Gresik</span>
      </article>

      <article class="sa-card" data-region="sumatra">
        <span class="sa-card__img"><img src="{{ asset('assets/img/plb-stock.webp') }}" alt="SuperArea Palembang" loading="lazy"></span>
        <strong>Palembang</strong>
        <span>Jl. Musi 2, Kel. Karang Jaya, Kec. Gandus, Kota Palembang</span>
      </article>

      <article class="sa-card" data-region="kalimantan">
        <span class="sa-card__img"><img src="{{ asset('assets/img/plb-truck.webp') }}" alt="SuperArea Pontianak" loading="lazy"></span>
        <strong>Pontianak</strong>
        <span>Pergudangan Primaco, Jl. Komodor Yos Sudarso No.2, Pontianak</span>
      </article>

      <article class="sa-card" data-region="kalimantan">
        <span class="sa-card__img"><img src="{{ asset('assets/img/warehouse-dark.webp') }}" alt="SuperArea Banjarbaru" loading="lazy"></span>
        <strong>Banjarbaru</strong>
        <span>Pergudangan Kalimantan Kencana Blok D No.13, Banjarbaru</span>
      </article>

      <article class="sa-card" data-region="kalimantan">
        <span class="sa-card__img"><img src="{{ asset('assets/img/plb-mojokerto.webp') }}" alt="SuperArea Balikpapan" loading="lazy"></span>
        <strong>Balikpapan</strong>
        <span>Jl. Soekarno Hatta Km. 11, RW.115, Karang Joang, Balikpapan</span>
      </article>

      <article class="sa-card" data-region="sulawesi">
        <span class="sa-card__img"><img src="{{ asset('assets/img/plb-gresik.webp') }}" alt="SuperArea Manado" loading="lazy"></span>
        <strong>Manado</strong>
        <span>Kawasan Pergudangan, Jl. Raya Manado - Bitung, Sulawesi Utara</span>
      </article>

      <article class="sa-card" data-region="sulawesi">
        <span class="sa-card__img"><img src="{{ asset('assets/img/delivery-forklift.webp') }}" alt="SuperArea Kendari" loading="lazy"></span>
        <strong>Kendari</strong>
        <span>Jl. Pajak, Korumba, Kec. Mandonga, Kota Kendari</span>
      </article>

      <article class="sa-card" data-region="sulawesi">
        <span class="sa-card__img"><img src="{{ asset('assets/img/plb-stock.webp') }}" alt="SuperArea Morowali" loading="lazy"></span>
        <strong>Morowali</strong>
        <span>Jl. Trans Sulawesi, Bahoruru, Kec. Bungku Tengah, Morowali</span>
      </article>

      <article class="sa-card" data-region="sulawesi">
        <span class="sa-card__img"><img src="{{ asset('assets/img/truck-tiberman.webp') }}" alt="SuperArea Luwuk Banggai" loading="lazy"></span>
        <strong>Luwuk Banggai</strong>
        <span>Jl. Tanjung Malaka No.1, Kelurahan Kraton, Luwuk, Banggai</span>
      </article>

      <article class="sa-card" data-region="maluku-papua">
        <span class="sa-card__img"><img src="{{ asset('assets/img/plb-truck.webp') }}" alt="SuperArea Ternate" loading="lazy"></span>
        <strong>Ternate</strong>
        <span>Jl. Pertamina, Gambesi, Kec. Ternate Selatan, Maluku Utara</span>
      </article>

      <article class="sa-card" data-region="maluku-papua">
        <span class="sa-card__img"><img src="{{ asset('assets/img/warehouse-dark.webp') }}" alt="SuperArea Sofifi" loading="lazy"></span>
        <strong>Sofifi</strong>
        <span>Jl. Trans Halmahera, Bukit Durian, Oba Utara, Kota Tidore Kepulauan</span>
      </article>

      <article class="sa-card" data-region="maluku-papua">
        <span class="sa-card__img"><img src="{{ asset('assets/img/plb-stock.webp') }}" alt="SuperArea Weda" loading="lazy"></span>
        <strong>Weda</strong>
        <span>Lelilef Sawai, Kec. Weda Tengah, Halmahera Tengah, Maluku Utara</span>
      </article>

    </div>
  </div>
</main>

@endsection

@push('scripts')
<script src="{{ asset('assets/js/superarea-spots.js') }}" defer></script>
<script src="{{ asset('assets/js/superarea-page.js') }}" defer></script>
@endpush
