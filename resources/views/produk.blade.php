@extends('layouts.app')

@section('title', $product->name.' '.$product->size.' — Tiberman')
@section('description', $product->meta_description ?: strip_tags((string) $product->description) ?: $product->name.' '.$product->size.' — '.$product->compat)

@section('nav')
<!-- ============================= NAVBAR KATEGORI ============================= -->
<header class="nav nav--center nav--grouped">
  <div class="nav__inner">
    <a class="nav__logo" href="{{ route('home') }}"><img src="{{ media(cms('site.logo')) ?? asset('assets/img/logo-white.png') }}" alt="Tiberman"></a>
    <button class="nav__burger" data-burger aria-label="Buka menu"><span></span></button>
    <nav class="nav__links">
      @foreach ($navUnits as $unit)
      <a href="{{ $unit->url() }}" @class(['is-active' => $unit->id === $product->catalog_unit_id])>{{ $unit->label }}</a>
      @endforeach
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

@php($description = $product->description ? rich($product->description) : e($product->name.' — ukuran '.$product->size.', cocok untuk '.$product->compat.'.'))

<main>

  <!-- ============================= HERO PRODUK ============================= -->
  <section class="pdp-hero">
    <div class="pdp-hero__inner">
      <div class="pdp-hero__img reveal">
        <img src="{{ media($product->hero_image) ?? $product->imageUrl() }}" alt="{{ $product->name }} ukuran {{ $product->size }}" fetchpriority="high">
      </div>
      <div class="reveal" data-delay="120">
        @if ($product->logo_light)
        <h1 class="pdp-hero__logo"><img src="{{ media($product->logo_light) }}" alt="{{ $product->name }}" fetchpriority="high"></h1>
        @else
        <h1 class="pdp-hero__title">{{ $product->name }}</h1>
        @endif
        <p>{!! $description !!}</p>
      </div>
    </div>
  </section>

  <!-- ============================= KENAPA HARUS BAN INI ============================= -->
  @if (filled($product->features))
  <section class="pdp-why">
    <h2 class="reveal">Kenapa Harus Ban Ini ?</h2>

    @foreach ($product->features as $feature)
    @php($text = '<article class="bento__cell reveal"'.($loop->even ? ' data-delay="100"' : '').'><h3>'.rich($feature['title'] ?? '').'</h3><p>'.rich($feature['body'] ?? '').'</p></article>')
    <div class="bento">
      @if ($loop->odd){!! $text !!}@endif
      @if (filled($feature['image'] ?? null))
      <div @class(['bento__cell', 'bento__cell--img', 'bento__cell--contain' => ! empty($feature['contain']), 'reveal']) @if ($loop->odd) data-delay="100" @endif>
        <img src="{{ media($feature['image']) }}" alt="{{ strip_tags(str_replace("\n", ' ', $feature['title'] ?? '')) }} {{ $product->shortName() }}" loading="lazy">
      </div>
      @endif
      @if ($loop->even){!! $text !!}@endif
    </div>
    @endforeach
  </section>
  @endif

  <!-- ============================= PERFECT PAIR FOR ============================= -->
  @if (filled($product->pairs))
  <section class="pdp-pair">
    <div class="container">
      <h2 class="reveal">Perfect Pair For</h2>
      <div class="pair-grid">
        @foreach ($product->pairs as $pair)
        <article class="pair-card reveal" @if ($loop->index) data-delay="{{ $loop->index * 100 }}" @endif>
          <img src="{{ media($pair['image'] ?? null) }}" alt="{{ $pair['title'] ?? '' }}" loading="lazy">
          <strong>{{ $pair['title'] ?? '' }}</strong>
        </article>
        @endforeach
      </div>
    </div>
  </section>
  @endif

  <!-- ============================= DETAIL + SPESIFIKASI ============================= -->
  <section class="pdp-detail">
    <div class="container">
      <div class="pdp-detail__grid">

        @include('partials.product-parts', ['part' => 'gallery'])

        <div class="pdp-info">
          @if ($product->logo)
          <span class="pdp-logo">
            <img class="pdp-logo__a" src="{{ media($product->logo) }}" alt="{{ $product->name }}">
          </span>
          @else
          <h2 class="pdp-info__name">{{ $product->name }}</h2>
          @endif
          <p>{!! $description !!}</p>

          @if (filled($product->specs))
          <h2 class="subhead">Spesifikasi</h2>
          @include('partials.product-parts', ['part' => 'specs'])
          @endif

          <h2 class="subhead">Available Size :</h2>
          @include('partials.product-parts', ['part' => 'sizes'])

          <h2 class="subhead">Contact us :</h2>
          @include('partials.product-parts', ['part' => 'contact'])
        </div>

      </div>
    </div>
  </section>

</main>

@endsection
