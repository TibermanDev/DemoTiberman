@extends('layouts.app')

@section('title', data_get($page, 'seo_title') ?: 'About Us — Tiberman')
@section('description', (string) (data_get($page, 'seo_description')))
@section('og_image', (string) (media(data_get($page, 'seo_image'))))
@section('noindex', data_get($page, 'seo_noindex') ? '1' : '')
@section('body-class', 'subpage')

@section('content')

<main class="abt">

  <!-- ============================= BANNER =============================
       Dua lapis: langit-langit gudang di belakang, foto tim (PNG/WebP yang
       bagian atasnya transparan) di depan, judul di antara keduanya. -->
  <section class="abt-hero">
    @if ($bg = media(data_get($page, 'hero_background')))
    <img class="abt-hero__bg" src="{{ $bg }}" alt="" aria-hidden="true" fetchpriority="high">
    @endif
    <div class="abt-hero__head">
      <p class="abt-hero__eyebrow">{{ data_get($page, 'hero_eyebrow') }}</p>
      <h1>{!! rich(data_get($page, 'hero_heading')) !!}</h1>
    </div>
    @if ($team = media(data_get($page, 'hero_image')))
    <img class="abt-hero__team" src="{{ $team }}" alt="Tim Tiberman" width="2880" height="1626" fetchpriority="high">
    @endif
    @if (filled(data_get($page, 'hero_button_label')))
    <a class="abt-hero__btn" href="{{ data_get($page, 'hero_button_url') ?: '/company-profile' }}">{{ data_get($page, 'hero_button_label') }}</a>
    @endif
  </section>

  <!-- ============================= ANGKA ============================= -->
  <section class="abt-stats">
    <div class="container">
      <div class="abt-stats__grid">
        @foreach (data_get($page, 'stats', []) as $stat)
        <div class="abt-stat reveal" data-delay="{{ $loop->index * 100 }}">
          <h2>{!! rich($stat['title'] ?? '') !!}</h2>
          <p>{!! rich($stat['text'] ?? '') !!}</p>
        </div>
        @endforeach
      </div>
    </div>
  </section>

  <!-- ============================= VISI & MISI ============================= -->
  <section class="abt-vm">
    <div class="container">
      <div class="abt-vm__grid">
        <div class="abt-vm__card abt-vm__card--vision reveal">
          <h2>{{ data_get($page, 'vision_heading') }}</h2>
          <p>{!! rich(data_get($page, 'vision_text')) !!}</p>
        </div>
        @if ($mascot = media(data_get($page, 'mascot_image')))
        <img class="abt-vm__mascot" src="{{ $mascot }}" alt="Maskot panda Tiberman" width="1100" height="939" loading="lazy">
        @endif
        <div class="abt-vm__card abt-vm__card--mission reveal" data-delay="100">
          <h2>{{ data_get($page, 'mission_heading') }}</h2>
          <p>{!! rich(data_get($page, 'mission_text')) !!}</p>
        </div>
      </div>
    </div>
  </section>

  <!-- ============================= MILESTONE ============================= -->
  <section class="abt-ms">
    <div class="container">
      <div class="abt-ms__head reveal">
        <p>{{ data_get($page, 'milestone_eyebrow') }}</p>
        <h2>{!! rich(data_get($page, 'milestone_heading')) !!}</h2>
      </div>
      <ol class="abt-ms__line">
        @foreach (data_get($page, 'milestones', []) as $step)
        <li class="abt-ms__step reveal" data-delay="{{ $loop->index * 100 }}">
          <span class="abt-ms__pill">{{ $step['label'] ?? '' }}</span>
          <p>{!! rich($step['text'] ?? '') !!}</p>
        </li>
        @endforeach
      </ol>
    </div>
    @if ($msImg = media(data_get($page, 'milestone_image')))
    <img class="abt-ms__img" src="{{ $msImg }}" alt="Tim Tiberman memeriksa ban OTR" width="2880" height="1124" loading="lazy">
    @endif
  </section>

  <!-- ============================= AJAKAN ============================= -->
  <section class="abt-cta">
    <div class="container">
      <div class="abt-cta__grid">
        <div class="abt-cta__left">
          <h2>{{ data_get($page, 'cta_heading') }}</h2>
          <p>{!! rich(data_get($page, 'cta_text')) !!}</p>
        </div>
        <div class="abt-cta__right">
          <h2>{!! rich(data_get($page, 'cta_title')) !!}</h2>
          <a class="abt-cta__btn" href="{{ data_get($page, 'cta_button_url') ?: 'https://wa.me/'.cms('site.whatsapp') }}">{{ data_get($page, 'cta_button_label') }}</a>
        </div>
      </div>
    </div>
  </section>

</main>

@endsection
