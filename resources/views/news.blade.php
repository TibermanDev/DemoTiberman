@extends('layouts.app')

{{-- $category: 'all' = /blog, selain itu slug kategori dari /blog/category/{slug} --}}
@php($active = $categories->firstWhere('slug', $category))

@section('title', $active ? $active->name.' — Tiberman News' : (cms('news.seo_title') ?: 'News — Tiberman'))
@section('description', (string) (cms('news.seo_description')))
@section('og_image', (string) (media(cms('news.seo_image'))))
@section('noindex', cms('news.seo_noindex') ? '1' : '')
@section('body-class', 'subpage')

@section('content')

<main class="nwp nwp--flat">

  @include('partials.news-banner')

  <!-- Tab kategori: tautan ke /blog/category/{slug} (slug blog lama).
       Kalau JS jalan, kliknya disaring di tempat tanpa reload dan URL-nya
       ikut diganti (assets/js/news-page.js). "Home" menampilkan semuanya. -->
  <nav class="nwp__tabs" data-news-tabs aria-label="Kategori artikel">
    <a @class(['nwp-tab', 'is-active' => $category === 'all']) href="{{ route('blog') }}" data-cat="all">Home</a>
    @foreach ($categories as $c)
      <a @class(['nwp-tab', 'is-active' => $category === $c->slug]) href="{{ route('blog.category', $c->slug) }}" data-cat="{{ $c->slug }}">{{ $c->name }}</a>
    @endforeach
  </nav>

  <!-- ===================== SOROTAN + POSTINGAN TERBARU ===================== -->
  @if ($feature)
  <section class="nwp-top" data-news-top @if($category !== 'all') hidden @endif>
    <div class="container nwp-top__grid">

      <article class="nwp-feature">
        <a class="nwp-feature__thumb" href="{{ $feature->url() }}">
          <img src="{{ media($feature->cover_image) }}" alt="{{ $feature->cover_alt }}" fetchpriority="high">
        </a>
        <span class="nwp-feature__date">{{ $feature->dateLabel() }}</span>
        <h2><a href="{{ $feature->url() }}">{{ $feature->title }}</a></h2>
        <p>{{ $feature->excerpt }}</p>
      </article>

      <aside class="nwp-latest">
        <h2 class="nwp-latest__head">{{ cms('news.latest_heading', 'Latest Post') }}</h2>
        @foreach ($latest as $post)
          @include('partials.news-mini', ['post' => $post])
        @endforeach
      </aside>

    </div>
  </section>
  @endif

  @foreach ($sections as $section)
  @php($c = $section['category'])
  <!-- ===================== {{ mb_strtoupper($c->heading) }} ===================== -->
  <section @class(['nwp-cat', 'is-solo' => $category === $c->slug]) data-cat="{{ $c->slug }}" @if($category !== 'all' && $category !== $c->slug) hidden @endif>
    <div class="container">
      <!-- Headline utama: hanya tampil kalau kategori ini yang dipilih
           (kelas is-solo dari assets/js/news-page.js). Di tab Home
           perannya sudah diambil kartu sorotan + "Latest Post". -->
      <a class="nwp-hero" href="{{ $section['hero']->url() }}">
        <img src="{{ media($section['hero']->cover_image) }}" alt="" aria-hidden="true" loading="lazy">
        <span class="nwp-hero__body">
          <span class="nwp-hero__kicker">{{ $c->heading }}</span>
          <strong>{{ $section['hero']->title }}</strong>
          <span class="nwp-hero__cta">Selengkapnya &rarr;</span>
        </span>
      </a>
      <h2 class="nwp-cat__head">{{ $c->heading }}</h2>
      <div class="news__grid">
        @foreach ($section['posts'] as $post)
          @include('partials.news-card', ['post' => $post])
        @endforeach
      </div>
    </div>
  </section>
  @endforeach

  @if ($sections->isEmpty())
  <section class="nwp-cat">
    <div class="container"><p class="empty">Belum ada artikel.</p></div>
  </section>
  @endif

</main>

@endsection

@push('scripts')
<script src="{{ asset('assets/js/news-page.js') }}" defer></script>
@endpush
