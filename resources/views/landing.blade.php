@extends('layouts.app')

@php
  $cards = $landing->cards ?? [];
  $shopeeUrl = cms('site.marketplace.shopee', '#');
@endphp

@section('title', $landing->meta_title ?: $landing->title.' — Tiberman')
@section('description', (string) $landing->meta_description)
@section('og_image', (string) (media($landing->meta_image) ?? media($cards[0]['image'] ?? null)))
@section('noindex', $landing->noindex ? '1' : '')
@section('body-class', 'subpage')

@section('content')

<!-- ============================= LANDING PROMO =============================
     Halaman /{slug} dari menu Landing Page Promo di CMS. Tidak ditautkan
     dari menu situs; dipakai untuk SEO & iklan marketplace. -->
<main class="lp">
  <div class="container">
    <div class="lp__head">
      <h1>{!! rich($landing->heading) !!}<span class="lp__logo"><img src="{{ $landing->logoUrl() }}" alt="Shopee" width="281" height="96"></span></h1>
      @if (filled($landing->subheading))
      <p class="lp__sub">{{ $landing->subheading }}</p>
      @endif
    </div>

    <div @class(['lp__grid', 'lp__grid--few' => count($cards) < 3])>
      @foreach ($cards as $card)
      <article class="lp-card reveal" data-delay="{{ $loop->index * 100 }}">
        <a class="lp-card__img" href="{{ ($card['url'] ?? null) ?: $shopeeUrl }}" target="_blank" rel="noopener" tabindex="-1" aria-hidden="true">
          @if ($img = media($card['image'] ?? null))
          <img src="{{ $img }}" alt="{{ $card['title'] ?? '' }}" loading="{{ $loop->index < 3 ? 'eager' : 'lazy' }}">
          @endif
        </a>
        <h2>{{ $card['title'] ?? '' }}</h2>
        @if (filled($card['subtitle'] ?? null))
        <p>{{ $card['subtitle'] }}</p>
        @endif
        <a class="lp-card__btn" href="{{ ($card['url'] ?? null) ?: $shopeeUrl }}" target="_blank" rel="noopener">{{ ($card['button_label'] ?? null) ?: 'Lihat Produk >>' }}</a>
      </article>
      @endforeach
    </div>
  </div>
</main>

@endsection
