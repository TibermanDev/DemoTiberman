/* ==========================================================================
   Tiberman — Peta 15 SuperArea
   Koordinat diambil dari tabel `area` (kode_area 01-15).
   Butuh Leaflet 1.9.x (dimuat lewat CDN di index.html).
   ========================================================================== */
(function () {
  'use strict';

  /* Ganti ke 'dark' kalau mau basemap gelap polos, bukan citra satelit. */
  var BASEMAP = 'satellite';

  var TILES = {
    satellite: {
      url: 'https://server.arcgisonline.com/ArcGIS/rest/services/World_Imagery/MapServer/tile/{z}/{y}/{x}',
      attribution: 'Tiles &copy; Esri &mdash; Esri, Maxar, Earthstar Geographics',
      labels: 'https://server.arcgisonline.com/ArcGIS/rest/services/Reference/World_Boundaries_and_Places/MapServer/tile/{z}/{y}/{x}'
    },
    dark: {
      url: 'https://{s}.basemaps.cartocdn.com/dark_all/{z}/{x}/{y}{r}.png',
      attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> &copy; <a href="https://carto.com/attributions">CARTO</a>',
      labels: null
    }
  };

  var AREAS = [
    { kode:'01', name:'SURABAYA',   alias:'HO',   lat:-7.2927133348724675, lng:112.7447667292062,  addr:'Jl. Mustika No.10, Ngagel, Kec. Wonokromo, Surabaya' },
    { kode:'02', name:'MOJOKERTO',  alias:'MJK',  lat:-7.512656365240839,  lng:112.47431900567061, addr:'Pergudangan Fie Min Logistics, Jl. Raya Pacing, Mojokerto' },
    { kode:'03', name:'GRESIK',     alias:'GRS',  lat:-7.039682723656871,  lng:112.57357287668198, addr:'Pergudangan Fie Min Logistics, Jl. Raya Bungah, Gresik' },
    { kode:'04', name:'JAKARTA',    alias:'JKT',  lat:-6.308233955535468,  lng:106.98128080952068, addr:'Komplek Pergudangan Cahaya, Jl. Nurul Huda No.34' },
    { kode:'05', name:'BANJARBARU', alias:'BJM',  lat:-3.457763489259076,  lng:114.70026753264581, addr:'Pergudangan Kalimantan Kencana Blok D No.13' },
    { kode:'06', name:'BALIKPAPAN', alias:'BLP',  lat:-1.1739508958906903, lng:116.88050496945647, addr:'Jl. Soekarno Hatta Km. 11, RW.115, Karang Joang' },
    { kode:'07', name:'MANADO',     alias:'MND',  lat:1.4756042813215693,  lng:124.9095256292551,  addr:'Kawasan Pergudangan, Jl. Raya Manado - Bitung' },
    { kode:'08', name:'KENDARI',    alias:'KDR',  lat:-3.9674439731384497, lng:122.52371640076153, addr:'Jl. Pajak, Korumba, Kec. Mandonga, Kota Kendari' },
    { kode:'09', name:'MOROWALI',   alias:'MRW',  lat:-2.5110381889652817, lng:121.9510623664606,  addr:'Jl. Trans Sulawesi, Bahoruru, Kec. Bungku Tengah' },
    { kode:'10', name:'TERNATE',    alias:'TNT',  lat:0.7566079355207939,  lng:127.33336067021476, addr:'Jl. Pertamina, Gambesi, Kec. Ternate Selatan' },
    { kode:'11', name:'LUWUK',      alias:'LWK',  lat:-0.960613604695442,  lng:122.79628783732583, addr:'Jl. Tanjung Malaka No.1, Kelurahan Kraton, Luwuk' },
    { kode:'12', name:'SOFIFI',     alias:'SFF',  lat:0.7230643999924186,  lng:127.58013539621747, addr:'Jl. Trans Halmahera, Bukit Durian, Oba Utara' },
    { kode:'13', name:'WEDA',       alias:'WDA',  lat:0.4721350115859624,  lng:127.95582399983205, addr:'Lelilef Sawai, Halmahera Tengah, Maluku Utara' },
    { kode:'14', name:'PONTIANAK',  alias:'PTK',  lat:-0.0051165440176069, lng:109.31440332935568, addr:'Pergudangan Primaco, Jl. Komodor Yos Sudarso No.2' },
    { kode:'15', name:'PALEMBANG',  alias:'PBG',  lat:-3.00996008204691,   lng:104.72304592921462, addr:'Jl. Musi 2, Kel. Karang Jaya, Kec. Gandus, Palembang' }
  ];

  var PIN_SVG = '<svg viewBox="0 0 24 24" fill="#e03434" aria-hidden="true"><path d="M12 1.5c-4.1 0-7.5 3.3-7.5 7.4 0 5.6 7.5 13.6 7.5 13.6s7.5-8 7.5-13.6c0-4.1-3.4-7.4-7.5-7.4zm0 10.3a2.9 2.9 0 1 1 0-5.8 2.9 2.9 0 0 1 0 5.8z"/></svg>';

  function esc(s) {
    return String(s).replace(/[&<>"]/g, function (c) {
      return { '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;' }[c];
    });
  }

  var ro;

  function init() {
    var host = document.getElementById('superareaMap');
    if (!host || typeof L === 'undefined') return;   /* Leaflet gagal dimuat -> fallback gambar tetap tampil */

    var fallback = document.querySelector('.superarea__fallback');
    if (fallback) fallback.remove();
    host.hidden = false;

    var shell = host.closest('.superarea__globe');
    if (shell) shell.classList.add('is-map');

    var tile = TILES[BASEMAP] || TILES.satellite;

    var map = L.map(host, {
      zoomControl: false,
      scrollWheelZoom: false,      /* biar scroll halaman nggak "kesangkut" di peta */
      zoomSnap: 0.25,              /* zoom pecahan -> Indonesia mengisi frame lebih rapat */
      attributionControl: true,
      minZoom: 3,
      maxZoom: 12
    });

    L.tileLayer(tile.url, { attribution: tile.attribution, maxZoom: 12 }).addTo(map);
    if (tile.labels) L.tileLayer(tile.labels, { maxZoom: 12, opacity: .85 }).addTo(map);
    L.control.zoom({ position: 'bottomright' }).addTo(map);

    var bounds = [];
    AREAS.forEach(function (a, i) {
      var icon = L.divIcon({
        className: 'sa-pin-wrap',
        html: '<span class="sa-pin" style="animation-delay:' + (i * 60) + 'ms">' + PIN_SVG + '</span>',
        iconSize: [24, 24],
        iconAnchor: [12, 24],
        popupAnchor: [0, -22]
      });

      L.marker([a.lat, a.lng], { icon: icon, title: a.name, riseOnHover: true })
        .addTo(map)
        .bindPopup(
          '<span class="sa-pop__kode">Area ' + esc(a.kode) + ' &middot; ' + esc(a.alias) + '</span>' +
          '<strong class="sa-pop__name">' + esc(a.name) + '</strong>' +
          '<span class="sa-pop__addr">' + esc(a.addr) + '</span>' +
          '<a class="sa-pop__link" target="_blank" rel="noopener"' +
          ' href="https://www.google.com/maps/search/?api=1&query=' + a.lat + ',' + a.lng + '">Buka di Google Maps &rarr;</a>'
        );

      bounds.push([a.lat, a.lng]);
    });

    function fit() {
      map.invalidateSize({ animate: false });
      var s = map.getSize();
      /* padding ikut ukuran layar: di HP 50px itu seperempat lebar peta */
      var pad = L.point(Math.min(50, Math.round(s.x * 0.09)), Math.min(50, Math.round(s.y * 0.11)));
      map.fitBounds(bounds, { padding: pad, animate: false });
    }
    fit();

    map.setMaxBounds(L.latLngBounds(bounds).pad(1.2));   /* nggak bisa digeser sampai lepas dari Indonesia */

    /* Refit setiap ukuran berubah: resize window, rotate layar, atau kontainer berubah tinggi */
    var t;
    function refit() { clearTimeout(t); t = setTimeout(fit, 150); }
    window.addEventListener('resize', refit);
    window.addEventListener('orientationchange', refit);
    if ('ResizeObserver' in window) {
      ro = new ResizeObserver(refit);   /* referensi disimpan di scope modul biar nggak kena GC */
      ro.observe(host);
    }

    /* Scroll-zoom baru aktif setelah peta di-klik, mati lagi saat kursor keluar. */
    map.on('click', function () { map.scrollWheelZoom.enable(); });
    host.addEventListener('mouseleave', function () { map.scrollWheelZoom.disable(); });
  }

  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', init);
  } else {
    init();
  }
})();
