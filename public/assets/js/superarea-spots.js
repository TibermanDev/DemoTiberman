/* =============================================================
   SuperArea — peta statis (earth-maps.webp) + titik interaktif.

   Pin-nya sudah tercetak di gambar, jadi tombolnya transparan dan
   diposisikan dalam persen supaya tetap pas saat gambar diperbesar
   atau diperkecil. Koordinat persen diambil dari deteksi blob merah
   pada gambarnya, bukan dikira-kira.

   Keterangannya muncul saat pin DIHOVER (di perangkat yang punya
   kursor), bukan harus diklik dulu — supaya pengunjung langsung tahu
   petanya bisa dipakai. Di layar sentuh hover tidak ada, jadi di sana
   tetap ketuk untuk buka/tutup.
   ============================================================= */
(function () {
  'use strict';

  var AREAS = {
    SURABAYA:   { kode: '01', alias: 'HO',  lat: -7.2927133348724675, lng: 112.7447667292062,  addr: 'Jl. Mustika No.10, Ngagel, Kec. Wonokromo, Surabaya' },
    MOJOKERTO:  { kode: '02', alias: 'MJK', lat: -7.512656365240839,  lng: 112.47431900567061, addr: 'Pergudangan Fie Min Logistics, Jl. Raya Pacing, Mojokerto' },
    GRESIK:     { kode: '03', alias: 'GRS', lat: -7.039682723656871,  lng: 112.57357287668198, addr: 'Pergudangan Fie Min Logistics, Jl. Raya Bungah, Gresik' },
    JAKARTA:    { kode: '04', alias: 'JKT', lat: -6.308233955535468,  lng: 106.98128080952068, addr: 'Komplek Pergudangan Cahaya, Jl. Nurul Huda No.34' },
    BANJARBARU: { kode: '05', alias: 'BJM', lat: -3.457763489259076,  lng: 114.70026753264581, addr: 'Pergudangan Kalimantan Kencana Blok D No.13' },
    BALIKPAPAN: { kode: '06', alias: 'BLP', lat: -1.1739508958906903, lng: 116.88050496945647, addr: 'Jl. Soekarno Hatta Km. 11, RW.115, Karang Joang' },
    MANADO:     { kode: '07', alias: 'MND', lat: 1.4756042813215693,  lng: 124.9095256292551,  addr: 'Kawasan Pergudangan, Jl. Raya Manado - Bitung' },
    KENDARI:    { kode: '08', alias: 'KDR', lat: -3.9674439731384497, lng: 122.52371640076153, addr: 'Jl. Pajak, Korumba, Kec. Mandonga, Kota Kendari' },
    MOROWALI:   { kode: '09', alias: 'MRW', lat: -2.5110381889652817, lng: 121.9510623664606,  addr: 'Jl. Trans Sulawesi, Bahoruru, Kec. Bungku Tengah' },
    TERNATE:    { kode: '10', alias: 'TNT', lat: 0.7566079355207939,  lng: 127.33336067021476, addr: 'Jl. Pertamina, Gambesi, Kec. Ternate Selatan' },
    LUWUK:      { kode: '11', alias: 'LWK', lat: -0.960613604695442,  lng: 122.79628783732583, addr: 'Jl. Tanjung Malaka No.1, Kelurahan Kraton, Luwuk' },
    SOFIFI:     { kode: '12', alias: 'SFF', lat: 0.7230643999924186,  lng: 127.58013539621747, addr: 'Jl. Trans Halmahera, Bukit Durian, Oba Utara' },
    WEDA:       { kode: '13', alias: 'WDA', lat: 0.4721350115859624,  lng: 127.95582399983205, addr: 'Lelilef Sawai, Halmahera Tengah, Maluku Utara' },
    PONTIANAK:  { kode: '14', alias: 'PTK', lat: -0.0051165440176069, lng: 109.31440332935568, addr: 'Pergudangan Primaco, Jl. Komodor Yos Sudarso No.2' },
    PALEMBANG:  { kode: '15', alias: 'PBG', lat: -3.00996008204691,   lng: 104.72304592921462, addr: 'Jl. Musi 2, Kel. Karang Jaya, Kec. Gandus, Palembang' }
  };

  /* Keterangan kota dari CMS (menu SuperArea) disuntikkan halaman sebagai
     window.TIBERMAN_AREAS dan menggantikan tabel bawaan di atas; tabel itu
     tinggal jadi cadangan kalau datanya tidak ada. Kota yang dinonaktifkan di
     CMS tidak ikut, jadi pinnya tidak dibuatkan keterangan. */
  if (window.TIBERMAN_AREAS) AREAS = window.TIBERMAN_AREAS;

  /* x/y = posisi pusat pin dalam persen gambar.
     lebar/tinggi = pengali ukuran tombol, dipakai untuk pin yang menumpuk.

     Dua tabel karena dua gambar yang bingkai buminya berbeda, jadi pin yang
     sama jatuh di persen yang berbeda pula. Dipilih lewat nilai atribut
     data-superarea. */

  /* earth-maps.webp (beranda). Bingkai buminya PERSIS sama dengan maps-new.webp
     yang dulu dipakai — ukuran (7868x3200 pada PNG sumbernya) dan profil alfanya
     identik, jadi --sa-globe-h dan --sa-glow-cut di style.css tidak ikut berubah.
     Yang berganti cuma grafik pin-nya: dari pin kecil rata jadi pin 3D besar,
     2.6x lebih besar (131x182 px pada mask skala 50%, dulu 25x36).

     Karena pin-nya membesar sementara UJUNGNYA tetap menancap di titik geografis
     yang sama, pusat KEPALA pin naik ~3-5% tinggi gambar. Semua y di bawah sudah
     digeser mengikuti itu. Angkanya hasil deteksi blob merah pada earth-maps.png
     (color-threshold merah -> connected-components), bukan kira-kira: untuk pin
     yang berdiri sendiri dipakai centroid LUBANG di kepala pin, dan untuk pin
     yang menumpuk dipakai kotak batas blobnya dengan kepala di 37% tinggi pin —
     dua cara itu cocok sampai ~0.05% di semua pin tunggal.

     CATATAN: gambar ini cuma punya DUA pin di Sulawesi — Morowali dan Kendari.
     Pin LUWUK tidak ikut tercetak (sama seperti maps-new), jadi tombolnya tidak
     dibuat di beranda (datanya tetap dipakai SPOTS_PAGE di superarea.html).
     Sama seperti gambar lama, ada satu pin di Papua barat (~76.7% / 43.9%) yang
     tidak punya pasangan di daftar 15 SuperArea — dibiarkan jadi bagian gambar
     saja, tanpa tombol. */
  var SPOTS_GLOBE = [
    { x: 18.89, y: 52.62, kota: ['PALEMBANG'] },
    { x: 24.74, y: 66.74, kota: ['JAKARTA'] },
    { x: 31.90, y: 44.11, kota: ['PONTIANAK'] },
    { x: 36.90, y: 68.96, lebar: 1.8, kota: ['SURABAYA', 'GRESIK', 'MOJOKERTO'] },
    { x: 36.33, y: 49.89, kota: ['BANJARBARU'] },
    { x: 43.90, y: 42.99, kota: ['BALIKPAPAN'] },
    { x: 55.14, y: 48.86, kota: ['MOROWALI'] },
    { x: 56.92, y: 53.49, kota: ['KENDARI'] },
    { x: 60.78, y: 35.24, kota: ['MANADO'] },
    { x: 66.20, y: 36.08, lebar: 1.3, tinggi: 1.5, kota: ['TERNATE', 'SOFIFI', 'WEDA'] }
  ];

  /* TIDAK DIPAKAI untuk sementara. Tabel ini milik earth-superarea.webp, dan
     superarea.html sekarang memakai maps-superarea.webp yang TIDAK punya pin
     tercetak sama sekali — lapisan titiknya dimatikan di sana (atributnya
     diubah jadi data-superarea-off). Tabelnya sengaja ditahan, bukan dihapus,
     supaya tinggal dihidupkan lagi kalau ada gambar bumi bertepi pin.

     Koordinatnya hasil deteksi blob merah pada earth-superarea.webp, bukan
     kira-kira — sama caranya dengan tabel di atas.
     CATATAN: gambar itu punya SATU pin lagi di Papua barat (~77,5% / 78,4%)
     yang tidak punya pasangan di daftar 15 SuperArea, jadi pin itu dibiarkan
     sebagai bagian gambar saja dan tidak dibuatkan tombol. */
  var SPOTS_PAGE = [
    { x: 21.48, y: 83.79, kota: ['PALEMBANG'] },
    { x: 26.10, y: 88.39, kota: ['JAKARTA'] },
    { x: 32.61, y: 79.26, kota: ['PONTIANAK'] },
    { x: 37.33, y: 88.81, lebar: 1.8, kota: ['SURABAYA', 'GRESIK', 'MOJOKERTO'] },
    { x: 40.05, y: 81.57, kota: ['BANJARBARU'] },
    { x: 45.74, y: 78.17, kota: ['BALIKPAPAN'] },
    { x: 55.82, y: 79.91, kota: ['MOROWALI'] },
    { x: 57.31, y: 78.16, kota: ['LUWUK'] },
    { x: 57.71, y: 82.22, kota: ['KENDARI'] },
    { x: 61.25, y: 75.01, kota: ['MANADO'] },
    { x: 68.30, y: 74.84, tinggi: 1.4, kota: ['TERNATE', 'SOFIFI', 'WEDA'] }
  ];

  var LABEL = {
    id: { maps: 'Buka di Google Maps', tutup: 'Tutup', titik: 'Lihat lokasi' },
    en: { maps: 'Open in Google Maps', tutup: 'Close', titik: 'View location' },
    zh: { maps: '在 Google 地图打开', tutup: '关闭', titik: '查看地点' }
  };

  function bahasa() {
    var l = window.TIBERMAN_I18N && window.TIBERMAN_I18N.getLang && window.TIBERMAN_I18N.getLang();
    return LABEL[l] ? LABEL[l] : LABEL.id;
  }

  function esc(s) {
    return String(s).replace(/[&<>"]/g, function (c) {
      return { '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;' }[c];
    });
  }

  var wrap = document.querySelector('[data-superarea]');
  if (!wrap) return;

  var SPOTS = wrap.getAttribute('data-superarea') === 'page' ? SPOTS_PAGE : SPOTS_GLOBE;

  var spotsBox = document.createElement('div');
  spotsBox.className = 'sa-spots';
  wrap.appendChild(spotsBox);

  var pop = document.createElement('div');
  pop.className = 'sa-pop';
  pop.setAttribute('role', 'dialog');
  pop.hidden = true;
  wrap.appendChild(pop);

  var aktif = null;

  function tutup() {
    pop.hidden = true;
    if (aktif) { aktif.setAttribute('aria-expanded', 'false'); aktif.classList.remove('is-on'); }
    aktif = null;
  }

  function buka(btn, spot) {
    var t = bahasa();
    pop.innerHTML =
      '<button type="button" class="sa-pop__x" aria-label="' + esc(t.tutup) + '">&times;</button>' +
      spot.kota.filter(function (nama) { return AREAS[nama]; }).map(function (nama) {
        var a = AREAS[nama];
        return '<div class="sa-pop__item">' +
          '<span class="sa-pop__kode">' + esc(a.kode) + ' &middot; ' + esc(a.alias) + '</span>' +
          '<strong class="sa-pop__nama">' + esc(a.nama || nama) + '</strong>' +
          '<span class="sa-pop__addr">' + esc(a.addr) + '</span>' +
          '<a class="sa-pop__maps" target="_blank" rel="noopener"' +
          ' href="https://www.google.com/maps/search/?api=1&query=' + a.lat + ',' + a.lng + '">' +
          esc(t.maps) + ' &rarr;</a>' +
          '</div>';
      }).join('');

    /* posisi dihitung dalam piksel lalu dijepit di dalam kotak gambar,
       supaya tidak pernah terpotong oleh overflow:hidden milik section */
    pop.hidden = false;
    pop.style.left = '0px';
    pop.style.top = '0px';

    var w = wrap.getBoundingClientRect();
    var pw = pop.offsetWidth, ph = pop.offsetHeight;
    var cx = spot.x / 100 * w.width;
    var cy = spot.y / 100 * w.height;

    var left = cx - pw / 2;
    var top = cy - ph - 22;                 /* default: di atas pin */
    if (top < 8) top = cy + 26;             /* mepet atas -> pindah ke bawah pin */

    /* Dijepit DUA kali: di dalam kotak gambar dan di dalam layar. Di
       superarea.html kotak gambarnya sengaja dilebarkan melewati tepi layar
       waktu di ponsel, jadi jepitan ke kotak saja masih menyisakan popup yang
       separuhnya di luar layar. */
    var minL = Math.max(8, 8 - w.left);
    var maxL = Math.min(w.width - pw - 8, window.innerWidth - 8 - w.left - pw);
    left = maxL < minL ? minL : Math.max(minL, Math.min(left, maxL));
    top = Math.max(8, Math.min(top, w.height - ph - 8));

    pop.style.left = Math.round(left) + 'px';
    pop.style.top = Math.round(top) + 'px';

    if (aktif && aktif !== btn) { aktif.setAttribute('aria-expanded', 'false'); aktif.classList.remove('is-on'); }
    aktif = btn;
    btn.setAttribute('aria-expanded', 'true');
    btn.classList.add('is-on');
  }

  /* Hover cuma dipakai di perangkat yang benar-benar punya kursor. Di layar
     sentuh, (hover:hover) palsu bisa membuat popup terbuka lalu nyangkut
     setelah satu ketukan, jadi di sana alurnya tetap ketuk untuk buka/tutup. */
  var kursor = window.matchMedia('(hover:hover) and (pointer:fine)');
  function pakaiHover() { return kursor.matches; }

  /* Popup dicetak di ATAS pin dengan jarak 22px dari PUSAT pin, sementara
     tombolnya sendiri beradius ~23px — jadi tepi bawah popup praktis menempel
     ke tepi atas tombol dan kursor tidak pernah jatuh ke celah kosong.
     Tapi kalau popupnya kena jepitan tepi layar dia bergeser menyamping dan
     celah itu muncul, jadi menutupnya selalu DITUNDA sebentar: cukup untuk
     menyeberang, tidak cukup untuk terasa nyangkut. */
  var jeda = null;
  function jadwalTutup() { clearTimeout(jeda); jeda = setTimeout(tutup, 260); }
  function batalTutup() { clearTimeout(jeda); }

  SPOTS.forEach(function (spot, i) {
    /* pin yang semua kotanya dinonaktifkan di CMS tidak dibuatkan tombol */
    if (!spot.kota.some(function (nama) { return AREAS[nama]; })) return;
    var btn = document.createElement('button');
    btn.type = 'button';
    btn.className = 'sa-spot';
    btn.style.left = spot.x + '%';
    btn.style.top = spot.y + '%';
    if (spot.lebar) btn.style.setProperty('--w', spot.lebar);
    if (spot.tinggi) btn.style.setProperty('--h', spot.tinggi);
    /* Denyut cincinnya digilir, bukan serempak — kalau semuanya berdenyut
       bersamaan hasilnya berkedip seperti lampu strobo, bukan undangan. */
    btn.style.setProperty('--d', (i * 0.26).toFixed(2) + 's');
    btn.setAttribute('aria-expanded', 'false');
    btn.setAttribute('aria-label', bahasa().titik + ': ' + spot.kota.join(', '));

    btn.addEventListener('mouseenter', function () {
      if (!pakaiHover()) return;
      batalTutup();
      /* Kalau popupnya memang sudah milik pin ini, jangan digambar ulang:
         innerHTML baru bikin isinya berkedip dan animasi masuknya jalan lagi. */
      if (aktif === btn && !pop.hidden) return;
      buka(btn, spot);
    });
    btn.addEventListener('mouseleave', function () {
      if (pakaiHover()) jadwalTutup();
    });
    /* Keyboard: Tab ke pin membuka keterangannya, sama seperti hover. */
    btn.addEventListener('focus', function () {
      batalTutup();
      if (aktif === btn && !pop.hidden) return;
      buka(btn, spot);
    });
    btn.addEventListener('click', function (e) {
      e.stopPropagation();
      /* Di perangkat berkursor popupnya sudah terbuka karena hover, jadi klik
         hanya dipakai untuk "memakukan" — bukan menutup, karena menutup di
         bawah kursor yang masih menempel langsung terbuka lagi oleh hover. */
      if (pakaiHover()) { batalTutup(); buka(btn, spot); return; }
      if (aktif === btn && !pop.hidden) tutup(); else buka(btn, spot);
    });
    spotsBox.appendChild(btn);
  });

  /* Kursor yang pindah dari pin ke dalam popup membatalkan jadwal tutup,
     supaya tautan Google Maps di dalamnya bisa benar-benar diklik. */
  pop.addEventListener('mouseenter', batalTutup);
  pop.addEventListener('mouseleave', function () {
    if (pakaiHover()) jadwalTutup();
  });

  pop.addEventListener('click', function (e) {
    if (e.target.closest('.sa-pop__x')) tutup();
    else e.stopPropagation();
  });
  /* Fokus keluar dari seluruh blok peta (Tab sampai lewat) -> tutup. Dicek
     lewat setTimeout karena saat focusout berlangsung document.activeElement
     masih body, belum elemen tujuannya. */
  wrap.addEventListener('focusout', function () {
    setTimeout(function () {
      if (!wrap.contains(document.activeElement)) jadwalTutup();
    }, 0);
  });
  document.addEventListener('click', function (e) {
    if (!pop.hidden && !e.target.closest('.sa-spot') && !e.target.closest('.sa-pop')) tutup();
  });
  document.addEventListener('keydown', function (e) { if (e.key === 'Escape') tutup(); });
  window.addEventListener('resize', tutup);

  /* Denyut cincin hanya jalan saat petanya kelihatan: sepuluh animasi tak
     berujung yang berputar di luar layar cuma membuang baterai. */
  if ('IntersectionObserver' in window) {
    new IntersectionObserver(function (baris) {
      baris.forEach(function (b) { spotsBox.classList.toggle('is-live', b.isIntersecting); });
    }, { rootMargin: '80px' }).observe(wrap);
  } else {
    spotsBox.classList.add('is-live');
  }
})();
