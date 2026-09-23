@extends('layouts.app')

@section('title', 'Uninest TiberMAX 800 — Tiberman')
@section('description', 'Uninest Tibermax 800: ban radial all-steel dengan telapak lebih tebal, sidewall kuat, dan umur pakai lebih panjang untuk dump truck, off-road, dan muatan berat.')

@section('nav')
<!-- ============================= NAVBAR KATEGORI ============================= -->
<header class="nav nav--center nav--grouped">
  <div class="nav__inner">
    <a class="nav__logo" href="{{ route('home') }}"><img src="{{ asset('assets/img/logo-white.png') }}" alt="Tiberman"></a>
    <button class="nav__burger" data-burger aria-label="Buka menu"><span></span></button>
    <nav class="nav__links">
      <a href="/katalog" class="is-active">Truk &amp; Bus</a>
      <a href="/katalog">Mining Truck</a>
      <a href="/katalog">Loader-Grader</a>
      <a href="/katalog">Traktor</a>
      <a href="/katalog">Forklift</a>
      <a href="/katalog">Velg &amp; Tube</a>
    </nav>
    <div class="nav__tools">
      <div class="lang">
        <button class="tool-btn" type="button" data-lang-btn aria-haspopup="true" aria-expanded="false">
          <span data-lang-label>ID</span>
          <svg class="lang__caret" viewBox="0 0 12 12" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path d="M2 4l4 4 4-4"/></svg>
        </button>
        <div class="lang__menu" data-lang-menu>
          <button type="button" data-lang="id">Indonesia</button>
          <button type="button" data-lang="en">English</button>
          <button type="button" data-lang="zh">中文</button>
        </div>
      </div>
    </div>
  </div>
</header>
@endsection

@section('content')

<main>

  <!-- ============================= HERO PRODUK ============================= -->
  <section class="pdp-hero">
    <div class="pdp-hero__inner">
      <div class="pdp-hero__img reveal">
        <img src="{{ asset('assets/img/tire-hero-dark.webp') }}" alt="Uninest TiberMAX 800 ukuran 12.00R24" fetchpriority="high">
      </div>
      <div class="reveal" data-delay="120">
        <h1 class="pdp-hero__logo"><img src="{{ asset('assets/img/tibermax-logo-light.png') }}" alt="Uninest TiberMAX 800" width="2004" height="356" fetchpriority="high"></h1>
        <p><b>Uninest Tibermax 800</b> Dirancang khusus untuk memberikan cengkraman maksimal tanpa kompromi. Dengan telapak yang lebih tebal, ban ini nggak cuma tangguh, tapi juga punya umur pakai yang lebih panjang.</p>
      </div>
    </div>
  </section>

  <!-- ============================= KENAPA HARUS BAN INI ============================= -->
  <section class="pdp-why">
    <h2 class="reveal">Kenapa Harus Ban Ini ?</h2>

    <div class="bento">
      <article class="bento__cell reveal">
        <h3>Sidewall<br>Kuat</h3>
        <p>Konstruksi all-steel radial dengan bahu ban lebih tebal, tahan benturan batu dan beban lateral di jalur tambang.</p>
      </article>
      <div class="bento__cell bento__cell--img bento__cell--contain reveal" data-delay="100">
        <img src="{{ asset('assets/img/tyre-90.png') }}" alt="Sidewall TiberMAX 800" loading="lazy">
      </div>
    </div>

    <div class="bento">
      <div class="bento__cell bento__cell--img reveal">
        <img src="{{ asset('assets/img/tire-tread.webp') }}" alt="Pola telapak TiberMAX 800" loading="lazy">
      </div>
      <article class="bento__cell reveal" data-delay="100">
        <h3>Telapak<br>Tebal</h3>
        <p>Kedalaman tapak 25.5 mm dengan blok besar memberi traksi maksimal dan umur pakai yang jauh lebih panjang.</p>
      </article>
    </div>
  </section>

  <!-- ============================= PERFECT PAIR FOR ============================= -->
  <section class="pdp-pair">
    <div class="container">
      <h2 class="reveal">Perfect Pair For</h2>
      <div class="pair-grid">
        <article class="pair-card reveal">
          <img src="{{ asset('assets/img/dumptruck.webp') }}" alt="Dump truck" loading="lazy">
          <strong>Dump Truck</strong>
        </article>
        <article class="pair-card reveal" data-delay="100">
          <img src="{{ asset('assets/img/tire-tread.webp') }}" alt="Medan off-road" loading="lazy">
          <strong>Off-Road</strong>
        </article>
        <article class="pair-card reveal" data-delay="200">
          <img src="{{ asset('assets/img/plb-stock.webp') }}" alt="Muatan berat" loading="lazy">
          <strong>Muatan Berat</strong>
        </article>
      </div>
    </div>
  </section>

  <!-- ============================= DETAIL + SPESIFIKASI ============================= -->
  <section class="pdp-detail">
    <div class="container">
      <div class="pdp-detail__grid">

        <!-- Galeri -->
        <div data-gallery>
          <div class="gallery__main">
            <img src="{{ asset('assets/img/tyre-preview.png') }}" alt="Uninest TiberMAX 800" data-gallery-main>
          </div>
          <div class="gallery__thumbs">
            <button type="button" class="is-active" data-gallery-thumb data-full="{{ asset('assets/img/tyre-preview.png') }}">
              <img src="{{ asset('assets/img/tyre-preview.png') }}" alt="Tampak serong TiberMAX 800">
            </button>
            <button type="button" data-gallery-thumb data-full="{{ asset('assets/img/tyre-diameter.png') }}">
              <img src="{{ asset('assets/img/tyre-diameter.png') }}" alt="Tampak depan TiberMAX 800">
            </button>
            <button type="button" data-gallery-thumb data-full="{{ asset('assets/img/tapak-ban.png') }}">
              <img src="{{ asset('assets/img/tapak-ban.png') }}" alt="Pola telapak TiberMAX 800">
            </button>
          </div>
          <div class="doc-btns">
            <a href="#">E-Katalog</a>
            <a href="#">Flash Card</a>
          </div>
        </div>

        <!-- Info -->
        <div class="pdp-info">
          <span class="pdp-logo">
            <img class="pdp-logo__a" src="{{ asset('assets/img/tibermax-logo.png') }}" alt="Uninest TiberMAX 800" width="584" height="97">
          </span>
          <p><b>Uninest Tibermax 800</b> Dirancang khusus untuk memberikan cengkraman maksimal tanpa kompromi. Dengan telapak yang lebih tebal, ban ini nggak cuma tangguh, tapi juga punya umur pakai yang lebih panjang.</p>

          <h2 class="subhead">Spesifikasi</h2>
          <table class="spec-table">
            <tbody>
              <tr><td>Ply Rating</td><td>: 16PR</td><td>Overall Diameter</td><td>: 1500 mm</td></tr>
              <tr><td>Tread Depth</td><td>: 25.5 mm</td><td>Max Load</td><td>: 6150 kg</td></tr>
              <tr><td>Standard Rim</td><td>: DW20</td><td>Max Speed</td><td>: 10 km/jam</td></tr>
              <tr><td>Section Width</td><td>: 595 mm</td><td>Pressure</td><td>: 38 Psi</td></tr>
            </tbody>
          </table>

          <h2 class="subhead">Available Size :</h2>
          <div class="size-chips" data-size-chips>
            <a class="size-chip is-active" href="#">11.00R20</a>
            <a class="size-chip" href="#">12.00R20</a>
            <a class="size-chip" href="#">12.00R24</a>
            <a class="size-chip" href="#">14.00R25</a>
          </div>

          <h2 class="subhead">Contact us :</h2>
          <div class="contact-btns">
            <a class="contact-btn contact-btn--wa" href="https://wa.me/6281234567890">
              <svg viewBox="0 0 24 24" fill="currentColor"><path d="M12 2a10 10 0 0 0-8.6 15.1L2 22l5-1.3A10 10 0 1 0 12 2zm5.3 14.1c-.2.6-1.3 1.2-1.8 1.2-.5.1-1 .1-1.7-.1-1-.3-2.6-1-4-2.5-1.3-1.3-2-2.7-2.3-3.4-.2-.6-.3-1.2-.2-1.7.1-.4.7-1.4 1.2-1.6.3-.1.6 0 .8.2l.9 1.5c.1.2.1.5 0 .7l-.4.6c-.1.2-.1.4 0 .6.3.5.8 1.2 1.4 1.7.6.5 1.2.9 1.7 1.1.2.1.5 0 .6-.1l.6-.6c.2-.2.4-.2.6-.1l1.6.8c.2.1.4.4.3.7l-.1.2z"/></svg>
              Whatsapp
            </a>
            <a class="contact-btn contact-btn--shopee" href="#">
              <img src="{{ asset('assets/img/shopee.png') }}" alt="">
              Shoppe
            </a>
            <a class="contact-btn contact-btn--tokped" href="#">
              <img src="{{ asset('assets/img/tokopedia.png') }}" alt="">
              Tokopedia
            </a>
          </div>
        </div>

      </div>
    </div>
  </section>

</main>

@endsection
