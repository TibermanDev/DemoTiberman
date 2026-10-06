@extends('layouts.app')

@section('title', data_get($page, 'seo_title') ?: 'After Sales — Tiberman')
@section('description', (string) (data_get($page, 'seo_description')))
@section('og_image', (string) (media(data_get($page, 'seo_image'))))
@section('noindex', data_get($page, 'seo_noindex') ? '1' : '')
@section('body-class', 'subpage')

@php
  $services = data_get($page, 'services', []);
  $facilities = data_get($page, 'facilities', []);
  // Banner (foto melengkung + ikon) memakai semua layanan & fasilitas berurutan.
  $all = array_merge($services, $facilities);
  $anchor = fn (array $item) => 'layanan-'.\Illuminate\Support\Str::slug($item['name'] ?? '');
  $buttonUrl = fn (array $item) => ($item['button_url'] ?? null)
      ?: 'https://wa.me/'.cms('site.whatsapp').'?text='.rawurlencode('Halo Tiberman, saya ingin konsultasi tentang Tiberman '.($item['name'] ?? '').'.');
@endphp

@section('content')

<main class="asl">

  <!-- ============================= BANNER =============================
       Foto layanan disusun seperti layar melengkung: foto paling luar
       dimiringkan bertahap seperti dinding silinder (lihat .asl-curve). -->
  <section class="asl-hero">
    <div class="asl-hero__head">
      <p class="asl-hero__eyebrow">{{ data_get($page, 'hero_eyebrow') }}</p>
      <h1>{!! rich(data_get($page, 'hero_heading')) !!}</h1>
    </div>

    <div class="asl-curve" aria-hidden="true">
      @foreach ($all as $item)
      <div class="asl-curve__item" style="--a: {{ (($loop->count - 1) / 2 - $loop->index) * 24 }}deg">
        <img src="{{ media($item['image'] ?? null) }}" alt="" width="640" height="1164">
      </div>
      @endforeach
    </div>

    <div class="container">
      <p class="asl-hero__sub">{!! rich(data_get($page, 'hero_subheading')) !!}</p>
      <ul class="asl-icons">
        @foreach ($all as $item)
        <li>
          <a href="#{{ $anchor($item) }}">
            <span class="asl-icons__img"><img src="{{ media($item['icon'] ?? null) }}" alt="" width="208" height="208"></span>
            <span class="asl-icons__label">Tiberman<br>{{ $item['name'] ?? '' }}</span>
          </a>
        </li>
        @endforeach
      </ul>
    </div>
  </section>

  <!-- ============================= LAYANAN & FASILITAS ============================= -->
  @foreach ([['services_heading', $services, 'asl-group--3'], ['facilities_heading', $facilities, 'asl-group--2']] as [$headingKey, $items, $modifier])
  @if (count($items))
  <section class="asl-group {{ $modifier }}">
    <div class="container">
      <h2 class="asl-group__title reveal">{!! rich(data_get($page, $headingKey)) !!}</h2>
      <div class="asl-group__grid">
        @foreach ($items as $item)
        <article class="asl-card reveal" id="{{ $anchor($item) }}" data-delay="{{ $loop->index * 100 }}">
          <div class="asl-card__img"><img src="{{ media($item['image'] ?? null) }}" alt="{{ $item['alt'] ?? '' }}" width="640" height="1164" loading="lazy"></div>
          <h3><span>Tiberman</span> {{ $item['name'] ?? '' }}</h3>
          <p>{!! rich($item['desc'] ?? '') !!}</p>
          @if (filled(data_get($page, 'button_label')))
          <a class="asl-card__btn" href="{{ $buttonUrl($item) }}" target="_blank" rel="noopener">{{ data_get($page, 'button_label') }}</a>
          @endif
        </article>
        @endforeach
      </div>
    </div>
  </section>
  @endif
  @endforeach

  <!-- ============================= FAQ AFTER SALES =============================
       Isinya terpisah dari FAQ Contact Us (menu After Sales di CMS);
       buka-tutupnya tetap memakai [data-accordion] di main.js. -->
  <section class="asl-faq">
    <div class="container">
      <div class="asl-faq__grid">
        <div class="asl-faq__art reveal">
          <img src="{{ media(data_get($page, 'faq_image')) }}" alt="Maskot panda Tiberman siap membantu" loading="lazy">
        </div>
        <div class="faq__panel asl-faq__panel reveal" data-delay="100">
          <h2>{{ data_get($page, 'faq_heading') }}</h2>
          <div data-accordion>
            @foreach (data_get($page, 'faq', []) as $item)
            <div @class(['acc__item', 'is-open' => $loop->first])>
              <button class="acc__q" aria-expanded="{{ $loop->first ? 'true' : 'false' }}">{{ $item['question'] ?? '' }}
                <svg viewBox="0 0 16 16" fill="none" stroke="currentColor" stroke-width="1.6"><path d="M3 6l5 5 5-5"/></svg>
              </button>
              <div class="acc__a"><p>{!! rich($item['answer'] ?? '') !!}</p></div>
            </div>
            @endforeach
          </div>
          <div class="faq__foot">
            <span>{{ data_get($page, 'faq_foot') }}</span>
            <a class="btn btn--connect" href="{{ data_get($page, 'connect_url') ?: 'https://wa.me/'.cms('site.whatsapp') }}">{{ data_get($page, 'connect_label') }}
              <svg width="14" height="14" viewBox="0 0 14 14" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M3 11L11 3M5 3h6v6"/></svg>
            </a>
          </div>
        </div>
      </div>
    </div>
  </section>

</main>

@endsection
