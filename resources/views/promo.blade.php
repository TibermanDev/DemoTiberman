@extends('layouts.app')

@php
  $blocks = $promo->blocks ?? [];
  $headingSeen = false; // judul blok pertama = H1, sisanya H2
@endphp

@section('title', $promo->meta_title ?: $promo->title.' — Tiberman')
@section('description', (string) $promo->meta_description)
@section('og_image', (string) (media($promo->meta_image) ?? media($promo->banner_image)))
@section('noindex', $promo->noindex ? '1' : '')
@section('body-class', 'subpage')

@section('content')

<!-- ============================= HALAMAN PROMO =============================
     /{slug} dari menu Halaman Promo di CMS. Isinya deretan blok Builder:
     text | image_text | products | image. Tidak ditautkan dari menu situs. -->
<main class="prm">
  <div class="container">
    @if ($banner = media($promo->banner_image))
    <img class="prm__banner" src="{{ $banner }}" alt="{{ $promo->banner_alt ?: $promo->title }}" fetchpriority="high">
    @endif

    @foreach ($blocks as $block)
      @php($data = $block['data'] ?? [])
      @switch($block['type'] ?? null)

        @case('text')
        <section class="prm-block prm-text">
          @if (filled($data['heading'] ?? null))
            @if (! $headingSeen)
            <h1>{{ $data['heading'] }}</h1>
            @else
            <h2>{{ $data['heading'] }}</h2>
            @endif
            @php($headingSeen = true)
          @endif
          <div class="prm-prose">{!! $data['body'] ?? '' !!}</div>
        </section>
        @break

        @case('image_text')
        <section @class(['prm-block', 'prm-split', 'prm-split--right' => ($data['image_side'] ?? 'left') === 'right'])>
          <div class="prm-split__img">
            <img src="{{ media($data['image'] ?? null) }}" alt="{{ $data['alt'] ?? '' }}" loading="lazy">
          </div>
          <div class="prm-prose">{!! $data['body'] ?? '' !!}</div>
        </section>
        @break

        @case('products')
        @php($cards = \App\Models\PromoPage::resolveCards($data['cards'] ?? [], ($data['button_label'] ?? null) ?: 'Lihat Produk >>'))
        @if (count($cards))
        <section class="prm-block prm-cards">
          @foreach ($cards as $card)
          <article class="prm-card">
            <a class="prm-card__img" href="{{ $card['url'] }}" tabindex="-1" aria-hidden="true">
              <img src="{{ $card['image'] }}" alt="{{ $card['alt'] }}" loading="lazy">
            </a>
            <a class="prm-card__btn" href="{{ $card['url'] }}">{{ $card['label'] }}</a>
          </article>
          @endforeach
        </section>
        @endif
        @break

        @case('image')
        <figure class="prm-block prm-figure">
          @if (filled($data['url'] ?? null))<a href="{{ $data['url'] }}">@endif
          <img src="{{ media($data['image'] ?? null) }}" alt="{{ $data['alt'] ?? '' }}" loading="lazy">
          @if (filled($data['url'] ?? null))</a>@endif
        </figure>
        @break

      @endswitch
    @endforeach
  </div>

  @if (filled($promo->cta_heading))
  <!-- Pita ajakan: maskot menyembul di atas pita merah. -->
  <section class="prm-cta">
    <div class="container">
      <div class="prm-cta__grid">
        <img class="prm-cta__art" src="{{ media($promo->cta_image) ?? asset(ltrim(\App\Models\PromoPage::DEFAULT_CTA_IMAGE, '/')) }}" alt="Maskot panda Tiberman" width="895" height="1467" decoding="async">
        <div class="prm-cta__copy">
          <h2>{{ $promo->cta_heading }}</h2>
          <a class="prm-cta__btn" href="{{ $promo->cta_url ?: 'https://wa.me/'.cms('site.whatsapp') }}">{{ $promo->cta_label ?: cms('site.phone') }}</a>
        </div>
      </div>
    </div>
  </section>
  @endif
</main>

@endsection
