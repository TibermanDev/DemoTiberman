{{--
    Tag iklan & analitik dari menu Pengaturan → Tracking & Iklan. Tiap tag
    hanya dimuat kalau ID-nya diisi. window.tbmTrack('lead' | 'whatsapp')
    meneruskan event konversi ke semua tag yang aktif; dipanggil oleh
    contact-form.js (form terkirim) dan oleh listener klik WhatsApp di bawah.
--}}
@php
    $t = (array) cms('site.tracking', []);
    $on = ($t['enabled'] ?? true) && ! request()->is('admin*');
    $v = fn (string $k) => $on && filled($t[$k] ?? null) ? $t[$k] : null;
    $ga4 = $v('ga4_id');
    $ads = $v('google_ads_id');
    $gtag = $ga4 ?: $ads;
    // Satu baris @json(...) saja: Blade tidak bisa mem-parse @json yang argumennya multi-baris.
    $cfg = [
        'gtm' => (bool) $v('gtm_id'),
        'ga4' => (bool) $ga4,
        'ads' => $ads,
        'adsLead' => $v('google_ads_lead_label'),
        'adsWa' => $v('google_ads_whatsapp_label'),
        'liLead' => $v('linkedin_lead_conversion_id'),
    ];
@endphp
@if ($v('meta_domain_verification'))
<meta name="facebook-domain-verification" content="{{ $v('meta_domain_verification') }}">
@endif
@if ($on)
@if ($v('gtm_id'))
<script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src='https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);})(window,document,'script','dataLayer',@json($v('gtm_id')));</script>
@endif
@if ($gtag)
<script async src="https://www.googletagmanager.com/gtag/js?id={{ $gtag }}"></script>
<script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag('js', new Date());
  @if ($ga4) gtag('config', @json($ga4)); @endif
  @if ($ads) gtag('config', @json($ads)); @endif
</script>
@endif
@if ($v('meta_pixel_id'))
<script>
  !function(f,b,e,v,n,t,s){if(f.fbq)return;n=f.fbq=function(){n.callMethod?n.callMethod.apply(n,arguments):n.queue.push(arguments)};if(!f._fbq)f._fbq=n;n.push=n;n.loaded=!0;n.version='2.0';n.queue=[];t=b.createElement(e);t.async=!0;t.src=v;s=b.getElementsByTagName(e)[0];s.parentNode.insertBefore(t,s)}(window,document,'script','https://connect.facebook.net/en_US/fbevents.js');
  fbq('init', @json($v('meta_pixel_id')));
  fbq('track', 'PageView');
</script>
@endif
@if ($v('tiktok_pixel_id'))
<script>
  !function (w, d, t) {w.TiktokAnalyticsObject=t;var ttq=w[t]=w[t]||[];ttq.methods=["page","track","identify","instances","debug","on","off","once","ready","alias","group","enableCookie","disableCookie","holdConsent","revokeConsent","grantConsent"],ttq.setAndDefer=function(t,e){t[e]=function(){t.push([e].concat(Array.prototype.slice.call(arguments,0)))}};for(var i=0;i<ttq.methods.length;i++)ttq.setAndDefer(ttq,ttq.methods[i]);ttq.instance=function(t){for(var e=ttq._i[t]||[],n=0;n<ttq.methods.length;n++)ttq.setAndDefer(e,ttq.methods[n]);return e},ttq.load=function(e,n){var r="https://analytics.tiktok.com/i18n/pixel/events.js",o=n&&n.partner;ttq._i=ttq._i||{},ttq._i[e]=[],ttq._i[e]._u=r,ttq._t=ttq._t||{},ttq._t[e]=+new Date,ttq._o=ttq._o||{},ttq._o[e]=n||{};n=document.createElement("script");n.type="text/javascript",n.async=!0,n.src=r+"?sdkid="+e+"&lib="+t;e=document.getElementsByTagName("script")[0];e.parentNode.insertBefore(n,e)};
  ttq.load(@json($v('tiktok_pixel_id')));
  ttq.page();
  }(window, document, 'ttq');
</script>
@endif
@if ($v('linkedin_partner_id'))
<script>
  window._linkedin_partner_id = @json($v('linkedin_partner_id'));
  window._linkedin_data_partner_ids = window._linkedin_data_partner_ids || [];
  window._linkedin_data_partner_ids.push(window._linkedin_partner_id);
  (function(l){if(!l){window.lintrk=function(a,b){window.lintrk.q.push([a,b])};window.lintrk.q=[]}var s=document.getElementsByTagName("script")[0];var b=document.createElement("script");b.type="text/javascript";b.async=true;b.src="https://snap.licdn.com/li.lms-analytics/insight.min.js";s.parentNode.insertBefore(b,s);})(window.lintrk);
</script>
@endif
@endif
{{-- Selalu dipasang (walau semua pixel mati) supaya event konversi tetap
     tercatat di analitik bawaan CMS lewat window.tbmAnalytics. --}}
<script>
  window.tbmTrack = (function (cfg) {
    return function (name) {
      var lead = name === 'lead';
      try {
        if (window.tbmAnalytics) window.tbmAnalytics(lead ? 'lead' : 'whatsapp');
        if (cfg.gtm) (window.dataLayer = window.dataLayer || []).push({ event: lead ? 'generate_lead' : 'contact_whatsapp' });
        if (window.gtag && cfg.ga4) gtag('event', lead ? 'generate_lead' : 'contact', { method: lead ? 'contact_form' : 'whatsapp' });
        var label = lead ? cfg.adsLead : cfg.adsWa;
        if (window.gtag && cfg.ads && label) gtag('event', 'conversion', { send_to: cfg.ads + '/' + label });
        if (window.fbq) fbq('track', lead ? 'Lead' : 'Contact');
        if (window.ttq) ttq.track(lead ? 'SubmitForm' : 'Contact');
        if (window.lintrk && lead && cfg.liLead) lintrk('track', { conversion_id: +cfg.liLead });
      } catch (e) {}
    };
  })(@json($cfg));
  /* Klik tautan WhatsApp mana pun di situs (footer, tombol produk, Contact). */
  document.addEventListener('click', function (e) {
    var a = e.target.closest && e.target.closest('a[href*="wa.me/"], a[href*="api.whatsapp.com/"]');
    if (a) window.tbmTrack('whatsapp');
  }, true);
</script>
@if ($v('custom_head'))
{!! $v('custom_head') !!}
@endif
