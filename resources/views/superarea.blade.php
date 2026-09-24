@extends('layouts.app')

@section('title', data_get($page, 'seo_title') ?: 'SuperArea — Tiberman')
@section('description', data_get($page, 'seo_description'))
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
      <img class="sa__wordmark" src="{{ media(cms('site.logo')) ?? asset('assets/img/logo-white.png') }}" alt="Tiberman" width="1000" height="180">
      <p class="sa__kicker">{{ data_get($page, 'kicker') }}</p>
    </div>

    <!-- Tab pulau: memfilter kartu di bawahnya (assets/js/superarea-page.js) -->
    <div class="sa__tabs" data-sa-tabs role="tablist" aria-label="Pilih pulau">
      @foreach (data_get($page, 'regions', []) as $region)
      <button @class(['sa-tab', 'is-active' => $loop->first]) type="button" role="tab" aria-selected="{{ $loop->first ? 'true' : 'false' }}" data-region="{{ $region['key'] }}">{{ $region['label'] }}</button>
      @endforeach
    </div>

    <div class="sa__grid" data-sa-grid>
      @foreach ($locations as $location)
      <a class="sa-card" data-region="{{ $location->region }}" href="{{ $location->mapsUrl() }}" target="_blank" rel="noopener" title="Buka di Google Maps">
        <span class="sa-card__img"><img src="{{ media($location->image) }}" alt="SuperArea {{ $location->name }}" loading="lazy"></span>
        <strong>{{ $location->name }}@if ($location->note) <em>( {{ $location->note }} )</em>@endif</strong>
        <span>{{ $location->address }}</span>
      </a>
      @endforeach
    </div>
  </div>
</main>

@endsection

@push('scripts')
<script>window.TIBERMAN_AREAS = @json($areas, JSON_FORCE_OBJECT);</script>
<script src="{{ asset('assets/js/superarea-spots.js') }}" defer></script>
<script src="{{ asset('assets/js/superarea-page.js') }}" defer></script>
@endpush
