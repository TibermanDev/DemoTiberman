{{-- Pasangan tracking-head: versi <noscript> tiap tag + kode kustom awal <body>. --}}
@php
    $t = (array) cms('site.tracking', []);
    $on = ($t['enabled'] ?? true) && ! request()->is('admin*');
    $v = fn (string $k) => $on && filled($t[$k] ?? null) ? $t[$k] : null;
@endphp
@if ($v('gtm_id'))
<noscript><iframe src="https://www.googletagmanager.com/ns.html?id={{ $v('gtm_id') }}" height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
@endif
@if ($v('meta_pixel_id'))
<noscript><img height="1" width="1" style="display:none" alt="" src="https://www.facebook.com/tr?id={{ $v('meta_pixel_id') }}&ev=PageView&noscript=1"></noscript>
@endif
@if ($v('linkedin_partner_id'))
<noscript><img height="1" width="1" style="display:none" alt="" src="https://px.ads.linkedin.com/collect/?pid={{ $v('linkedin_partner_id') }}&fmt=gif"></noscript>
@endif
@if ($v('custom_body'))
{!! $v('custom_body') !!}
@endif
