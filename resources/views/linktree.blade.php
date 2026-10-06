@extends('layouts.app')

{{-- Linktree bio media sosial (/lp/, /lp/{slug}.html) — menu Linktree (/lp) di CMS.
     Berdiri sendiri: tanpa navbar & footer situs, satu kolom seperti linktree. --}}
@section('title', $page->meta_title ?: (str_contains($page->title, 'Tiberman') ? $page->title.' — Link Resmi' : $page->title.' — Tiberman'))
@section('description', (string) ($page->meta_description ?: $page->subtitle))
@section('og_image', (string) media($page->avatar))
@section('noindex', $page->noindex ? '1' : '')
@section('body-class', 'lt-body')

@section('nav')
@endsection

@section('footer')
@endsection

@section('content')

<main class="lt">
  <img class="lt__glow" src="{{ asset('assets/img/top-shadow.webp') }}" alt="" aria-hidden="true">

  <div class="lt__inner">
    <header class="lt__head">
      <a class="lt__avatar" href="{{ route('home') }}">
        @if ($avatar = media($page->avatar))
        <img src="{{ $avatar }}" alt="{{ $page->title }}">
        @else
        <img class="lt__logo" src="{{ media(cms('site.logo')) ?? asset('assets/img/logo-white.png') }}" alt="Tiberman">
        @endif
      </a>
      <h1>{{ $page->title }}</h1>
      @if (filled($page->subtitle))
      <p>{{ $page->subtitle }}</p>
      @endif
    </header>

    <ul class="lt__links">
      @foreach ($page->links ?? [] as $link)
      @php($href = $link['url'] ?? '#')
      <li>
        <a @class(['lt-link', 'lt-link--hl' => $link['highlight'] ?? false, 'lt-link--'.($link['icon'] ?? 'link')])
           href="{{ $href }}" @if (str_starts_with($href, 'http')) target="_blank" rel="noopener" @endif>
          <span class="lt-link__icon">@include('partials.link-icon', ['icon' => $link['icon'] ?? 'link'])</span>
          <span class="lt-link__label">{{ $link['label'] ?? '' }}</span>
          <svg class="lt-link__arrow" viewBox="0 0 16 16" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true"><path d="M6 3l5 5-5 5"/></svg>
        </a>
      </li>
      @endforeach
    </ul>

    <footer class="lt__foot">
      <a href="{{ route('home') }}">tiberman.com</a>
      <span>{{ cms('site.copyright') }}</span>
    </footer>
  </div>
</main>

@endsection
