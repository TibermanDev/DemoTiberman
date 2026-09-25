@extends('layouts.app')

@section('title', \App\Support\Catalog::title($state).' — Tiberman')
@section('description', (string) (cms('catalog.seo_description')))
@section('body-class', 'catalog')

@section('footer')
@endsection

@section('content')

<!-- Keadaan awal filter datang dari URL (menu Katalog di CMS); JS memakainya
     sebagai titik mulai lalu mengganti URL tiap kali filter diklik. -->
<div class="catalog__layout" data-catalog data-unit="{{ $state['unit'] }}" data-brand="{{ $state['brand'] }}" data-size="{{ $state['size'] }}">

  <!-- ============================= SIDEBAR ============================= -->
  <aside class="catalog__sidebar">
    <p class="side-label">Lagi cari ban apa?</p>
    <div class="search">
      <svg viewBox="0 0 16 16" fill="none" stroke="currentColor" stroke-width="1.6"><circle cx="7" cy="7" r="4.5"/><path d="M10.5 10.5L14 14"/></svg>
      <input type="search" placeholder="Search" aria-label="Cari ban" data-search>
    </div>
    <div class="side-divider"></div>

    <p class="side-label">Telusuri berdasarkan unit</p>
    <!-- Tautan ke URL kategori lama; klik biasa disaring di tempat oleh
         assets/js/main.js tanpa reload. -->
    <div class="unitlist">
      @foreach (\App\Support\Catalog::units() as $u)
        <a @class(['is-active' => $state['unit'] === $u->key && $state['brand'] === 'all']) href="{{ $u->url() }}" data-unit="{{ $u->key }}">{{ $u->label }}</a>
      @endforeach
    </div>

    <p class="side-label">Telusuri berdasarkan Merk</p>
    <!-- Menyaring berdasarkan awalan nama produk ("UNINEST - TIBERMAX 554")
         di semua unit. Klik lagi pada merk yang sedang aktif untuk melepas
         saringannya. -->
    <div class="unitlist">
      @foreach (\App\Support\Catalog::brands() as $b)
        <a @class(['is-active' => $state['brand'] === $b->slug]) href="{{ route('katalog.brand', $b->slug) }}" data-brand="{{ $b->slug }}">{{ $b->name }}</a>
      @endforeach
    </div>
  </aside>

  <!-- ============================= MAIN ============================= -->
  <main class="catalog__main">
    <div class="catalog__banner">
      <img src="{{ media(cms('catalog.banner')) }}" alt="{{ cms('catalog.banner_alt') }}" fetchpriority="high">
    </div>

    <!-- chip ukuran dibangun otomatis dari unit yang aktif (assets/js/main.js) -->
    <div class="chips" data-chips></div>

    <div class="catalog__body" data-catalog-body></div>

    <!-- Footer tinggal di DALAM kolom isi supaya tidak menutupi sidebar.
         Supaya tidak menggantung waktu produknya sedikit, .catalog__main
         dijadikan flex kolom dan footer ini didorong ke dasarnya (CSS). -->
    <footer class="footer footer--slim">
      <div class="container">
        <p class="footer__note">{{ cms('site.copyright') }}</p>
      </div>
    </footer>
  </main>
</div>

<!-- ===================== MODAL DETAIL PRODUK =====================
     Kartu katalog membuka modal ini, bukan pindah ke halaman produk. Empat
     slide-nya diambil dari /produk/{slug}/modal saat kartu diklik
     (resources/views/partials/product-modal.blade.php). -->
<div class="pmodal" data-pmodal hidden>
  <div class="pmodal__scrim" data-pmodal-close></div>
  <div class="pmodal__dialog" role="dialog" aria-modal="true" aria-labelledby="pmodal-judul">
    <button class="pmodal__close" type="button" data-pmodal-close aria-label="Tutup">
      <svg viewBox="0 0 16 16" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true"><path d="M3 3l10 10M13 3L3 13"/></svg>
    </button>

    <div class="pmodal__viewport">
      <div class="pmodal__track" data-pmodal-track></div>
    </div>

    <div class="pmodal__nav">
      <div class="dots pmodal__dots" data-pmodal-dots></div>
      <button class="pmodal__next" type="button" data-pmodal-next aria-label="Slide berikutnya">
        <svg viewBox="0 0 12 12" fill="currentColor" aria-hidden="true"><path d="M3 0l6 6-6 6z"/></svg>
      </button>
    </div>
  </div>
</div>

@endsection

@push('scripts')
<script>window.TIBERMAN_CATALOG_URLS = @json(\App\Support\Catalog::forJs());</script>
<script>window.TIBERMAN_PRODUCTS = @json(\App\Support\Catalog::products());</script>
@endpush
