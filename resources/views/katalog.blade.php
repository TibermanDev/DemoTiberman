@extends('layouts.app')

@section('title', \App\Support\Catalog::title($state).' — Tiberman')
@section('description', 'Telusuri katalog ban truk, mining truck, loader-grader, traktor, forklift, velg & tube Tiberman berdasarkan unit dan ukuran.')
@section('body-class', 'catalog')

@section('footer')
@endsection

@section('content')

<!-- Keadaan awal filter datang dari URL (config/catalog.php); JS memakainya
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
      @foreach (config('catalog.units') as $unit => $u)
        <a @class(['is-active' => $state['unit'] === $unit && $state['brand'] === 'all']) href="/{{ $u['path'] }}" data-unit="{{ $unit }}">{{ $u['label'] }}</a>
      @endforeach
    </div>

    <p class="side-label">Telusuri berdasarkan Merk</p>
    <!-- Menyaring berdasarkan awalan nama produk ("UNINEST - TIBERMAX 554")
         di semua unit. Klik lagi pada merk yang sedang aktif untuk melepas
         saringannya. -->
    <div class="unitlist">
      @foreach (config('catalog.brands') as $brand => $label)
        <a @class(['is-active' => $state['brand'] === $brand]) href="{{ route('katalog.brand', $brand) }}" data-brand="{{ $brand }}">{{ $label }}</a>
      @endforeach
    </div>
  </aside>

  <!-- ============================= MAIN ============================= -->
  <main class="catalog__main">
    <div class="catalog__banner">
      <img src="{{ asset('assets/img/dumptruck.webp') }}" alt="Dump truck di area tambang" fetchpriority="high">
    </div>

    <!-- chip ukuran dibangun otomatis dari unit yang aktif (assets/js/main.js) -->
    <div class="chips" data-chips></div>

    <div class="catalog__body" data-catalog-body></div>

    <!-- Footer tinggal di DALAM kolom isi supaya tidak menutupi sidebar.
         Supaya tidak menggantung waktu produknya sedikit, .catalog__main
         dijadikan flex kolom dan footer ini didorong ke dasarnya (CSS). -->
    <footer class="footer footer--slim">
      <div class="container">
        <p class="footer__note">Copyright &copy; 2021 PT. Tiga Berlian Mandiri</p>
      </div>
    </footer>
  </main>
</div>

<!-- ===================== MODAL DETAIL PRODUK =====================
     Kartu katalog membuka modal ini, bukan pindah ke produk.html. Empat slide,
     isinya sama dengan produk.html. -->
<div class="pmodal" data-pmodal hidden>
  <div class="pmodal__scrim" data-pmodal-close></div>
  <div class="pmodal__dialog" role="dialog" aria-modal="true" aria-labelledby="pmodal-judul">
    <button class="pmodal__close" type="button" data-pmodal-close aria-label="Tutup">
      <svg viewBox="0 0 16 16" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true"><path d="M3 3l10 10M13 3L3 13"/></svg>
    </button>

    <div class="pmodal__viewport">
      <div class="pmodal__track" data-pmodal-track>

        <!-- 1. Hero -->
        <section class="pmodal__slide pmodal__slide--hero">
          <!-- tire-554 dipakai, bukan tire-hero-dark: yang terakhir itu
                 berlatar hitam pekat, jadi tepinya kelihatan sebagai kotak di
                 atas modal. Yang ini beralpha, bannya melayang. -->
            <div class="pmodal__art"><img src="{{ asset('assets/img/tire-554.webp') }}" alt="Uninest TiberMAX 800" loading="lazy"></div>
          <div class="pmodal__copy">
            <!-- Versi -trim: kanvas PNG aslinya 584x97 tapi tulisannya cuma
                 414x73 — sisanya transparan. Dengan file asli, width:100%
                 membuat yang selebar kolom itu KANVASnya, jadi logonya
                 kelihatan 29% lebih pendek dari paragraf di bawahnya.
                 (width/height juga dibetulkan: 2004x356 itu sisa dari
                 file -light yang dulu dipakai di sini.) -->
            <img class="pmodal__logo" id="pmodal-judul" src="{{ asset('assets/img/tibermax-logo-trim.png') }}" alt="Uninest TiberMAX 800" width="414" height="73">
            <p><b>Uninest Tibermax 800</b> Dirancang khusus untuk memberikan <b>cengkraman maksimal tanpa kompromi</b>. Dengan telapak yang lebih tebal, ban ini nggak cuma tangguh, tapi juga punya <b>umur pakai yang lebih panjang</b>.</p>
          </div>
        </section>

        <!-- 2. Kenapa harus ban ini -->
        <section class="pmodal__slide pmodal__slide--why">
          <!-- Judulnya keluar dari .pmodal__copy supaya bisa jadi baris sendiri
               selebar modal dan ditengahkan, seperti di desain. -->
          <h2 class="pmodal__head">Kenapa Harus Ban Ini ?</h2>
          <div class="pmodal__copy">
            <h3>Sidewall<br>Kuat</h3>
            <p>Konstruksi all-steel radial dengan bahu ban lebih tebal, tahan benturan batu dan beban lateral di jalur tambang.</p>
          </div>
          <div class="pmodal__art pmodal__art--bleed"><img src="{{ asset('assets/img/tyre-slice-left.png') }}" alt="Sidewall TiberMAX 800" loading="lazy"></div>
        </section>

        <!-- 3. Perfect pair for -->
        <section class="pmodal__slide pmodal__slide--pair">
          <h2 class="pmodal__head">Perfect pair for</h2>
          <div class="pair-grid">
            <article class="pair-card"><img src="{{ asset('assets/img/dumptruck.webp') }}" alt="Dump truck di area quarry" loading="lazy"><strong>Dump Truck</strong></article>
            <article class="pair-card"><img src="{{ asset('assets/img/after-sales-3.webp') }}" alt="Alat berat di medan berbatu" loading="lazy"><strong>Off-Road</strong></article>
            <article class="pair-card"><img src="{{ asset('assets/img/plb-truck.webp') }}" alt="Truk kontainer bermuatan penuh" loading="lazy"><strong>Muatan Berat</strong></article>
          </div>
        </section>

        <!-- 4. Spesifikasi -->
        <section class="pmodal__slide pmodal__slide--spec">
          <div data-gallery>
            <div class="gallery__main"><img src="{{ asset('assets/img/tyre-preview.png') }}" alt="Uninest TiberMAX 800" data-gallery-main></div>
            <div class="gallery__thumbs">
              <button type="button" class="is-active" data-gallery-thumb data-full="{{ asset('assets/img/tyre-preview.png') }}"><img src="{{ asset('assets/img/tyre-preview.png') }}" alt="Tampak serong TiberMAX 800"></button>
              <button type="button" data-gallery-thumb data-full="{{ asset('assets/img/tyre-diameter.png') }}"><img src="{{ asset('assets/img/tyre-diameter.png') }}" alt="Tampak depan TiberMAX 800"></button>
              <button type="button" data-gallery-thumb data-full="{{ asset('assets/img/tyre-90.png') }}"><img src="{{ asset('assets/img/tyre-90.png') }}" alt="Sidewall TiberMAX 800"></button>
              <button type="button" data-gallery-thumb data-full="{{ asset('assets/img/tapak-ban.png') }}"><img src="{{ asset('assets/img/tapak-ban.png') }}" alt="Pola telapak TiberMAX 800"></button>
            </div>
            <div class="doc-btns">
              <a href="#">E-Katalog</a>
              <a href="#">Flash Card</a>
            </div>
          </div>
          <div class="pmodal__info">
            <img class="pmodal__logo" src="{{ asset('assets/img/tibermax-logo-trim.png') }}" alt="Uninest TiberMAX 800" width="414" height="73">
            <h3 class="subhead">Spesifikasi :</h3>
            <table class="spec-table"><tbody>
              <tr><td>Ply Rating</td><td>: 16PR</td><td>Overall Diameter</td><td>: 1500 mm</td></tr>
              <tr><td>Tread Depth</td><td>: 25.5 mm</td><td>Max Load</td><td>: 6150 kg</td></tr>
              <tr><td>Standard Rim</td><td>: DW20</td><td>Max Speed</td><td>: 10 km/jam</td></tr>
              <tr><td>Section Width</td><td>: 595 mm</td><td>Pressure</td><td>: 38 Psi</td></tr>
            </tbody></table>
            <h3 class="subhead">Available Size :</h3>
            <div class="size-chips" data-size-chips>
              <a class="size-chip is-active" href="#">11.00R20</a>
              <a class="size-chip" href="#">12.00R20</a>
              <a class="size-chip" href="#">12.00R24</a>
              <a class="size-chip" href="#">14.00R25</a>
            </div>
            <h3 class="subhead">Contact us :</h3>
            <div class="contact-btns">
              <a class="contact-btn contact-btn--wa" href="https://wa.me/6281234567890">
                <svg viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M12 2a10 10 0 0 0-8.6 15.1L2 22l5-1.3A10 10 0 1 0 12 2zm5.3 14.1c-.2.6-1.3 1.2-1.8 1.2-.5.1-1 .1-1.7-.1-1-.3-2.6-1-4-2.5-1.3-1.3-2-2.7-2.3-3.4-.2-.6-.3-1.2-.2-1.7.1-.4.7-1.4 1.2-1.6.3-.1.6 0 .8.2l.9 1.5c.1.2.1.5 0 .7l-.4.6c-.1.2-.1.4 0 .6.3.5.8 1.2 1.4 1.7.6.5 1.2.9 1.7 1.1.2.1.5 0 .6-.1l.6-.6c.2-.2.4-.2.6-.1l1.6.8c.2.1.4.4.3.7l-.1.2z"/></svg>
                Whatsapp
              </a>
              <a class="contact-btn contact-btn--shopee" href="#"><img src="{{ asset('assets/img/shopee.png') }}" alt="">Shoppe</a>
              <a class="contact-btn contact-btn--tokped" href="#"><img src="{{ asset('assets/img/tokopedia.png') }}" alt="">Tokopedia</a>
            </div>
          </div>
        </section>

      </div>
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
<script src="{{ asset('assets/js/products.js') }}"></script>
@endpush
