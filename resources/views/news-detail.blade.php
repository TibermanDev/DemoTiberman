@extends('layouts.app')

@section('title', $post->title.' — Tiberman News')
@section('description', $post->meta_description ?: $post->excerpt)
@section('body-class', 'subpage')

@section('content')

<main class="nwp">

  <!-- Banner sama dengan halaman News (empat lapis) supaya halaman detail
       tetap terasa satu kesatuan dengan indeksnya. -->
  @include('partials.news-banner')

  <!-- Tab di halaman detail: tautan ke halaman kategori, dengan kategori
       artikel ini yang ditandai aktif. -->
  <nav class="nwp__tabs" aria-label="Kategori artikel">
    <a class="nwp-tab" href="{{ route('blog') }}">Home</a>
    @foreach ($categories as $c)
      <a @class(['nwp-tab', 'is-active' => $c->id === $post->post_category_id]) href="{{ route('blog.category', $c->slug) }}">{{ $c->name }}</a>
    @endforeach
  </nav>

  <section class="nwp-art">
    <div class="container nwp-art__grid">

      <article class="art">
        <nav class="art__crumb" aria-label="Breadcrumb">
          <a href="{{ route('blog') }}">News</a>
          <span aria-hidden="true">/</span>
          <a href="{{ route('blog.category', $post->category->slug) }}">{{ $post->category->heading }}</a>
        </nav>

        <h1>{{ $post->title }}</h1>

        <div class="art__meta">
          <span class="art__author">{{ $post->author }}</span>
          @if ($post->published_at)
          <span aria-hidden="true">&middot;</span>
          <time datetime="{{ $post->published_at->toDateString() }}">{{ $post->dateLabel() }}</time>
          @endif
          <span aria-hidden="true">&middot;</span>
          <span>{{ $post->readingMinutes() }} menit baca</span>
        </div>

        @if ($post->cover_image)
        <figure class="art__figure">
          <img src="{{ media($post->cover_image) }}" alt="{{ $post->cover_alt }}" fetchpriority="high">
          @if ($post->cover_caption)
          <figcaption>{{ $post->cover_caption }}</figcaption>
          @endif
        </figure>
        @endif

        {{-- Isi artikel dari rich editor CMS; hanya admin yang bisa menulisnya. --}}
        <div class="art__body">
          {!! $post->body !!}
        </div>

        @if ($post->tags->isNotEmpty())
        <div class="art__tags">
          @foreach ($post->tags as $tag)
          <a href="{{ route('blog.category', $tag->slug) }}">{{ $tag->heading }}</a>
          @endforeach
        </div>
        @endif

        <div class="art__foot">
          <a class="btn btn--primary" href="{{ route('blog') }}">&larr; Kembali ke News</a>
        </div>
      </article>

      @if ($popular->isNotEmpty())
      <aside class="nwp-latest nwp-art__side">
        <h2 class="nwp-latest__head">{{ cms('news.popular_heading', 'Populer Bulan Ini') }}</h2>
        @foreach ($popular as $item)
          @include('partials.news-mini', ['post' => $item])
        @endforeach
      </aside>
      @endif

    </div>
  </section>

  <!-- ===================== ARTIKEL TERKAIT ===================== -->
  @if ($related->isNotEmpty())
  <section class="nwp-cat">
    <div class="container">
      <h2 class="nwp-cat__head">{{ cms('news.related_heading', 'Artikel Terkait') }}</h2>
      <div class="news__grid">
        @foreach ($related as $item)
          @include('partials.news-card', ['post' => $item])
        @endforeach
      </div>
    </div>
  </section>
  @endif

</main>

@endsection
