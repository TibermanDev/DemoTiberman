@extends('layouts.app')

{{-- Privacy Policy & Disclaimer — isi dari CMS (Halaman > Privacy Policy / Disclaimer). --}}
@section('title', data_get($page, 'seo_title') ?: data_get($page, 'hero_title', $fallbackTitle).' — Tiberman')
@section('description', (string) (data_get($page, 'seo_description')))
@section('og_image', (string) (media(data_get($page, 'seo_image'))))
@section('noindex', data_get($page, 'seo_noindex') ? '1' : '')
@section('body-class', 'subpage')

@section('content')

<main class="lgl">
  <!-- Banner: latar gelap bersorot merah (CSS) + ban kiri-kanan, aset yang
       sama dengan banner halaman News. -->
  <section class="lgl-hero">
    <img class="lgl-hero__tyre lgl-hero__tyre--l" src="{{ asset('assets/img/tyre-left.webp') }}" width="700" height="520" alt="" aria-hidden="true">
    <img class="lgl-hero__tyre lgl-hero__tyre--r" src="{{ asset('assets/img/tyre-right.webp') }}" width="700" height="520" alt="" aria-hidden="true">
    <div class="lgl-hero__head">
      <h1>{{ data_get($page, 'hero_title', $fallbackTitle) }}</h1>
      @if (filled(data_get($page, 'hero_subtitle')))
      <p>{{ data_get($page, 'hero_subtitle') }}</p>
      @endif
    </div>
  </section>

  <div class="lgl-body">
    <div class="container">
      @foreach (data_get($page, 'sections', []) as $section)
      <section class="lgl-section">
        <h2>{{ $section['heading'] ?? '' }}</h2>
        <p>{!! rich($section['body'] ?? '') !!}</p>
      </section>
      @endforeach
    </div>
  </div>
</main>

@endsection
