{{--
    Analitik pengunjung bawaan (lihat App\Support\Analytics). Satu beacon per
    tampilan halaman; window.tbmAnalytics('lead' | 'whatsapp') dipanggil
    window.tbmTrack di partials/tracking-head untuk event konversi.
    Admin yang sedang login tidak dihitung (dicek di server).
--}}
@if (\App\Support\Analytics::enabled())
<script>
  (function () {
    var url = @json(route('analytics.collect', absolute: false));
    window.tbmAnalytics = function (type) {
      try {
        var body = JSON.stringify({ t: type, p: location.pathname, r: document.referrer, q: location.search });
        if (navigator.sendBeacon && navigator.sendBeacon(url, new Blob([body], { type: 'text/plain' }))) return;
        fetch(url, { method: 'POST', body: body, keepalive: true, credentials: 'same-origin', headers: { 'Content-Type': 'text/plain' } });
      } catch (e) {}
    };
    window.tbmAnalytics('pageview');
  })();
</script>
@endif
