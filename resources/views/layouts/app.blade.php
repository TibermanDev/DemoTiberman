<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
@include('partials.seo')
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,400;1,600;1,700&display=swap" rel="stylesheet">
<script>window.TIBERMAN_DICT=@json($i18nDict ?? ['en' => [], 'zh' => []], JSON_FORCE_OBJECT);</script>
<script>(function(){try{var l=localStorage.getItem('tbm-lang');if(l)document.documentElement.setAttribute('lang',l==='zh'?'zh-CN':l);}catch(e){}})();</script>
@stack('head')
<link rel="stylesheet" href="{{ asset('assets/css/style.css') }}">
@include('partials.tracking-head')
</head>
<body class="@yield('body-class')">
@include('partials.tracking-body')

@section('nav')
<!-- ============================= NAVBAR ============================= -->
<header class="nav">
  <div class="nav__inner">
    <a class="nav__logo" href="{{ route('home') }}"><img src="{{ media(cms('site.logo')) ?? asset('assets/img/logo-white.png') }}" alt="Tiberman"></a>
    <button class="nav__burger" data-burger aria-label="Buka menu"><span></span></button>
    <nav class="nav__links">
      @unless(request()->routeIs('katalog*'))
      <div class="nav__item">
        <a href="{{ $navUnits->first()?->url() ?? '/kategori-produk/semua-ban' }}" @class(['is-active' => request()->routeIs('katalog*')])>Products</a>
        <div class="nav__menu">
          @foreach ($navUnits as $unit)
          <a href="{{ $unit->url() }}">{{ $unit->label }}</a>
          @endforeach
        </div>
      </div>
      @endunless
      <a href="/blog" @class(['is-active' => request()->is('blog*')])>News</a>
      <a href="{{ route('superarea') }}" @class(['is-active' => request()->routeIs('superarea')])>SuperArea</a>
      <a href="{{ route('contact') }}" @class(['is-active' => request()->routeIs('contact')])>Contact Us</a>
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
@show

@yield('content')

@section('footer')
<!-- ============================= FOOTER ============================= -->
<footer class="footer" id="footer">
  <div class="container">
    <div class="footer__grid">

      <div class="footer__brand">
        <img class="footer__logo" src="{{ media(cms('site.logo')) ?? asset('assets/img/logo-white.png') }}" alt="Tiberman">
        <strong>{{ cms('site.company_name') }}</strong>
        <p>{{ cms('site.about') }}</p>
        <div class="footer__social">
          @if (filled(cms('site.social.facebook')))
          <a class="soc soc--fb" href="{{ cms('site.social.facebook') }}" aria-label="Facebook"><svg viewBox="0 0 24 24" fill="currentColor"><path d="M13.5 21v-8h2.7l.4-3.1h-3.1V7.9c0-.9.25-1.5 1.55-1.5h1.65V3.63c-.29-.04-1.27-.13-2.41-.13-2.39 0-4.02 1.46-4.02 4.13V9.9H7.5V13h2.77v8h3.23z"/></svg></a>
          @endif
          @if (filled(cms('site.social.youtube')))
          <a class="soc soc--yt" href="{{ cms('site.social.youtube') }}" aria-label="YouTube"><svg viewBox="0 0 24 24" fill="currentColor"><path d="M21.6 7.2s-.2-1.4-.8-2c-.75-.8-1.6-.8-2-.85C16 4.2 12 4.2 12 4.2h-.01s-4 0-6.8.15c-.4.05-1.25.05-2 .85-.6.6-.8 2-.8 2S2.2 8.8 2.2 10.5v1.6c0 1.65.2 3.3.2 3.3s.2 1.4.8 2c.75.8 1.75.78 2.2.86 1.6.15 6.8.2 6.8.2s4 0 6.8-.16c.4-.05 1.25-.05 2-.85.6-.6.8-2 .8-2s.2-1.65.2-3.3v-1.6c0-1.7-.2-3.35-.2-3.35zM9.95 14.2V8.85l5.15 2.68-5.15 2.67z"/></svg></a>
          @endif
          @if (filled(cms('site.social.instagram')))
          <a class="soc soc--ig" href="{{ cms('site.social.instagram') }}" aria-label="Instagram"><svg viewBox="0 0 24 24" fill="currentColor"><path d="M12 2.16c3.2 0 3.58.01 4.85.07 1.17.05 1.8.25 2.23.41.56.22.96.48 1.38.9.42.42.68.82.9 1.38.16.42.36 1.06.41 2.23.06 1.27.07 1.65.07 4.85s-.01 3.58-.07 4.85c-.05 1.17-.25 1.8-.41 2.23-.22.56-.48.96-.9 1.38-.42.42-.82.68-1.38.9-.42.16-1.06.36-2.23.41-1.27.06-1.65.07-4.85.07s-3.58-.01-4.85-.07c-1.17-.05-1.8-.25-2.23-.41-.56-.22-.96-.48-1.38-.9-.42-.42-.68-.82-.9-1.38-.16-.42-.36-1.06-.41-2.23C2.17 15.58 2.16 15.2 2.16 12s.01-3.58.07-4.85c.05-1.17.25-1.8.41-2.23.22-.56.48-.96.9-1.38.42-.42.82-.68 1.38-.9.42-.16 1.06-.36 2.23-.41C8.42 2.17 8.8 2.16 12 2.16zm0 3.88a5.96 5.96 0 100 11.92 5.96 5.96 0 000-11.92zm0 9.83a3.87 3.87 0 110-7.74 3.87 3.87 0 010 7.74zm7.58-10.06a1.39 1.39 0 11-2.78 0 1.39 1.39 0 012.78 0z"/></svg></a>
          @endif
          @if (filled(cms('site.social.tiktok')))
          <a class="soc soc--tt" href="{{ cms('site.social.tiktok') }}" aria-label="TikTok"><svg viewBox="0 0 24 24" fill="currentColor"><path d="M16.6 5.82A4.28 4.28 0 0115.54 3h-3.09v12.4a2.59 2.59 0 01-2.59 2.5 2.59 2.59 0 01-2.59-2.6 2.59 2.59 0 013.19-2.51v-3.1a5.66 5.66 0 00-.6-.03A5.68 5.68 0 004.2 15.3 5.68 5.68 0 009.86 21a5.68 5.68 0 005.66-5.7V8.9a7.35 7.35 0 004.28 1.37V7.18a4.29 4.29 0 01-3.2-1.36z"/></svg></a>
          @endif
          @if (filled(cms('site.social.linkedin')))
          <a class="soc soc--li" href="{{ cms('site.social.linkedin') }}" aria-label="LinkedIn"><svg viewBox="0 0 24 24" fill="currentColor"><path d="M6.94 8.5v10.6H3.6V8.5h3.34zM5.27 3.4c1.07 0 1.93.87 1.93 1.94S6.34 7.28 5.27 7.28 3.34 6.41 3.34 5.34 4.2 3.4 5.27 3.4zM20.66 19.1h-3.33v-5.16c0-1.23-.02-2.82-1.72-2.82-1.72 0-1.98 1.34-1.98 2.73v5.25H10.3V8.5h3.2v1.45h.05c.44-.84 1.53-1.73 3.15-1.73 3.37 0 3.99 2.22 3.99 5.1v5.78z"/></svg></a>
          @endif
        </div>
      </div>

      <div class="footer__col">
        <h3>{{ cms('site.footer.contact_title') ?: 'Hubungi Kami' }}</h3>
        <ul class="footer__contact">
          <li>
            <svg viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M6.6 10.8a15.1 15.1 0 006.6 6.6l2.2-2.2c.28-.28.68-.36 1.03-.24 1.13.37 2.35.57 3.6.57.55 0 1 .45 1 1V20c0 .55-.45 1-1 1A17 17 0 013 4c0-.55.45-1 1-1h3.5c.55 0 1 .45 1 1 0 1.25.2 2.47.57 3.6.11.35.03.75-.25 1.03l-2.22 2.17z"/></svg>
            <a href="tel:{{ preg_replace('/[^\d+]/', '', cms('site.phone')) }}">{{ cms('site.phone') }}</a>
          </li>
          <li>
            <svg viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M20 4H4a2 2 0 00-2 2v12a2 2 0 002 2h16a2 2 0 002-2V6a2 2 0 00-2-2zm0 4l-8 5-8-5V6l8 5 8-5v2z"/></svg>
            <a href="mailto:{{ cms('site.email') }}">{{ cms('site.email') }}</a>
          </li>
          <li>
            <svg viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M12.04 2c-5.5 0-9.96 4.46-9.96 9.96 0 1.76.46 3.48 1.34 5L2 22l5.2-1.36a9.9 9.9 0 004.84 1.24h.01c5.5 0 9.96-4.46 9.96-9.96 0-2.66-1.04-5.16-2.92-7.04A9.9 9.9 0 0012.04 2zm5.83 14.24c-.25.7-1.44 1.33-2 1.42-.51.08-1.16.11-1.87-.12-.43-.14-.99-.32-1.7-.63-2.99-1.29-4.94-4.3-5.09-4.5-.15-.2-1.22-1.62-1.22-3.09s.77-2.19 1.04-2.49c.27-.3.59-.37.79-.37h.57c.18 0 .43-.7.67.51.25.6.84 2.07.91 2.22.07.15.12.32.02.52-.1.2-.15.32-.3.5-.15.17-.31.39-.44.52-.15.15-.3.31-.13.61.17.3.76 1.25 1.63 2.03 1.12 1 2.06 1.3 2.36 1.45.3.15.47.13.65-.08.17-.2.75-.87.95-1.17.2-.3.4-.25.67-.15.27.1 1.72.81 2.02.96.3.15.5.22.57.35.07.12.07.72-.18 1.42z"/></svg>
            <a href="https://wa.me/{{ cms('site.whatsapp') }}">{{ cms('site.phone') }}</a>
          </li>
        </ul>

        <h3>{{ cms('site.footer.marketplace_title') ?: 'Marketplace' }}</h3>
        <div class="footer__market">
          <a class="market market--tokped" href="{{ cms('site.marketplace.tokopedia', '#') }}">
            <svg viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M5 8h14l-1 11a2 2 0 01-2 1.8H8A2 2 0 016 19L5 8zm3.5 0V6.5a3.5 3.5 0 017 0V8h-2V6.5a1.5 1.5 0 00-3 0V8h-2z"/></svg>
            <span>{{ cms('site.footer.tokopedia_label') ?: 'tokopedia' }}</span>
          </a>
          <a class="market market--shopee" href="{{ cms('site.marketplace.shopee', '#') }}">
            <svg viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M5 8h14l-1 11a2 2 0 01-2 1.8H8A2 2 0 016 19L5 8zm3.5 0V6.5a3.5 3.5 0 017 0V8h-2V6.5a1.5 1.5 0 00-3 0V8h-2z"/></svg>
            <span>{{ cms('site.footer.shopee_label') ?: 'Shopee' }}</span>
          </a>
        </div>
      </div>

      <div class="footer__col">
        <h3>{{ cms('site.footer.superarea_title') ?: 'Super Area' }}</h3>
        <ul class="footer__links">
          @php $superareaHref = request()->routeIs('home') ? '#superarea' : route('home').'#superarea'; @endphp
          @foreach ($footerLocations as $location)
          <li><a href="{{ $superareaHref }}">{{ $location->label() }}</a></li>
          @endforeach
        </ul>
      </div>

      <div class="footer__col">
        <h3>{{ cms('site.footer.nav_title') ?: 'Navigasi' }}</h3>
        <ul class="footer__links">
          @foreach (cms('site.footer_links', []) as $link)
          <li><a href="{{ $link['url'] }}">{{ $link['label'] }}</a></li>
          @endforeach
        </ul>
      </div>

    </div>
    <p class="footer__note">{{ cms('site.copyright') }}</p>
  </div>
</footer>
@show

<script src="{{ asset('assets/js/i18n.js') }}" defer></script>
<script src="{{ asset('assets/js/main.js') }}" defer></script>
@stack('scripts')
</body>
</html>
