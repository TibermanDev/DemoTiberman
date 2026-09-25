{{--
    Tag SEO di <head>, dipasang layouts/app. Tiap halaman cukup mengisi:
      @section('title'), @section('description')  — teks di hasil Google
      @section('og_image')                        — gambar saat link dibagikan
      @section('og_type', 'article')              — default 'website'
      @section('noindex', '1')                    — sembunyikan dari Google
      @push('jsonld')                             — structured data tambahan
    Yang kosong jatuh ke pengaturan bawaan di menu Pengaturan → SEO.
--}}
@php
    use App\Support\Seo;

    // @section('x', $nilai) sudah di-escape Blade; di-decode dulu supaya
    // {{ }} di bawah tidak meng-escape dua kali ("&amp;amp;").
    $section = fn (string $name) => trim(html_entity_decode($__env->yieldContent($name), ENT_QUOTES | ENT_HTML5));
    $seoTitle = $section('title') ?: (cms('site.seo_title') ?: Seo::siteName());
    $seoDesc = trim(preg_replace('/\s+/', ' ', strip_tags($section('description'))))
        ?: (string) cms('site.seo_description');
    $seoDesc = \Illuminate\Support\Str::limit($seoDesc, 300, '…');
    $seoImage = Seo::absolute($section('og_image') ?: Seo::defaultImage());
    $seoIndex = Seo::indexable() && trim($__env->yieldContent('noindex')) === '';
    // Canonical tanpa query string, kecuali nomor halaman (paginasi blog).
    $canonical = url()->current().(request()->integer('page') > 1 ? '?page='.request()->integer('page') : '');
@endphp
<title>{{ $seoTitle }}</title>
@if ($seoDesc !== '')
<meta name="description" content="{{ $seoDesc }}">
@endif
<meta name="robots" content="{{ $seoIndex ? 'index, follow, max-image-preview:large, max-snippet:-1' : 'noindex, follow' }}">
<link rel="canonical" href="{{ $canonical }}">
@if ($favicon = \App\Support\Favicon::url())
<link rel="icon" href="{{ $favicon }}">
<link rel="apple-touch-icon" href="{{ $favicon }}">
@endif
<meta name="theme-color" content="#1d1d1f">
@if (filled(cms('site.seo.google_verification')))
<meta name="google-site-verification" content="{{ cms('site.seo.google_verification') }}">
@endif
@if (filled(cms('site.seo.bing_verification')))
<meta name="msvalidate.01" content="{{ cms('site.seo.bing_verification') }}">
@endif

{{-- Open Graph (WhatsApp, Facebook, LinkedIn) & Twitter/X --}}
<meta property="og:site_name" content="{{ Seo::siteName() }}">
<meta property="og:type" content="{{ trim($__env->yieldContent('og_type')) ?: 'website' }}">
<meta property="og:title" content="{{ $seoTitle }}">
@if ($seoDesc !== '')
<meta property="og:description" content="{{ $seoDesc }}">
@endif
<meta property="og:url" content="{{ $canonical }}">
<meta property="og:locale" content="id_ID">
@if ($seoImage)
<meta property="og:image" content="{{ $seoImage }}">
@endif
<meta name="twitter:card" content="{{ $seoImage ? 'summary_large_image' : 'summary' }}">
<meta name="twitter:title" content="{{ $seoTitle }}">
@if ($seoDesc !== '')
<meta name="twitter:description" content="{{ $seoDesc }}">
@endif
@if ($seoImage)
<meta name="twitter:image" content="{{ $seoImage }}">
@endif

{{-- Structured data: nama situs + profil bisnis (semua halaman), plus tambahan per halaman --}}
<script type="application/ld+json">{!! Seo::json(Seo::graph()) !!}</script>
@stack('jsonld')
