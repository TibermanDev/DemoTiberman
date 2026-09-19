/* =============================================================
   SuperArea — peta statis (maps-new.webp) + titik yang bisa diklik.

   Pin-nya sudah tercetak di gambar, jadi tombolnya transparan dan
   diposisikan dalam persen supaya tetap pas saat gambar diperbesar
   atau diperkecil. Koordinat persen diambil dari deteksi blob merah
   pada gambarnya, bukan dikira-kira.
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

  /* x/y = posisi pusat pin dalam persen gambar.
     lebar/tinggi = pengali ukuran tombol, dipakai untuk pin yang menumpuk.

     Dua tabel karena dua gambar yang bingkai buminya berbeda, jadi pin yang
     sama jatuh di persen yang berbeda pula. Dipilih lewat nilai atribut
     data-superarea. */

  /* maps-new.webp (beranda). Bingkainya lebih zoom daripada earth-location.webp
     yang lama: planetnya naik, jadi semua y bergeser ~4-5% ke atas dan pin-nya
     tercetak lebih kecil. Angka di bawah hasil deteksi blob merah pada
     maps-new.webp (2880x1171).
     CATATAN: gambar ini cuma punya DUA pin di Sulawesi — Morowali dan Kendari.
     Pin LUWUK yang ada di gambar lama tidak ikut tercetak di maps-new, jadi
     tombolnya tidak dibuat di beranda (datanya tetap dipakai SPOTS_PAGE di
     superarea.html). Kalau pin Luwuk ditambahkan lagi ke gambarnya, posisinya
     jatuh di sekitar x 55.84 / y 47.39.
     Sama seperti gambar lama, ada satu pin di Papua barat (~76.6% / 48.8%) yang
     tidak punya pasangan di daftar 15 SuperArea — dibiarkan jadi bagian gambar
     saja, tanpa tombol. */
  var SPOTS_GLOBE = [
    { x: 18.41, y: 55.62, kota: ['PALEMBANG'] },
    { x: 23.90, y: 70.70, kota: ['JAKARTA'] },
    { x: 31.89, y: 48.33, kota: ['PONTIANAK'] },
    { x: 36.26, y: 71.79, lebar: 1.8, kota: ['SURABAYA', 'GRESIK', 'MOJOKERTO'] },
    { x: 36.75, y: 54.62, kota: ['BANJARBARU'] },
    { x: 43.98, y: 46.96, kota: ['BALIKPAPAN'] },
    { x: 54.90, y: 51.84, kota: ['MOROWALI'] },
    { x: 56.43, y: 54.62, kota: ['KENDARI'] },
    { x: 60.61, y: 40.11, kota: ['MANADO'] },
    { x: 66.45, y: 40.69, lebar: 1.3, tinggi: 1.5, kota: ['TERNATE', 'SOFIFI', 'WEDA'] }
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
      spot.kota.map(function (nama) {
        var a = AREAS[nama];
        return '<div class="sa-pop__item">' +
          '<span class="sa-pop__kode">' + esc(a.kode) + ' &middot; ' + esc(a.alias) + '</span>' +
          '<strong class="sa-pop__nama">' + esc(nama) + '</strong>' +
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

  SPOTS.forEach(function (spot) {
    var btn = document.createElement('button');
    btn.type = 'button';
    btn.className = 'sa-spot';
    btn.style.left = spot.x + '%';
    btn.style.top = spot.y + '%';
    if (spot.lebar) btn.style.setProperty('--w', spot.lebar);
    if (spot.tinggi) btn.style.setProperty('--h', spot.tinggi);
    btn.setAttribute('aria-expanded', 'false');
    btn.setAttribute('aria-label', bahasa().titik + ': ' + spot.kota.join(', '));
    btn.addEventListener('click', function (e) {
      e.stopPropagation();
      if (aktif === btn && !pop.hidden) tutup(); else buka(btn, spot);
    });
    spotsBox.appendChild(btn);
  });

  pop.addEventListener('click', function (e) {
    if (e.target.closest('.sa-pop__x')) tutup();
    else e.stopPropagation();
  });
  document.addEventListener('click', function (e) {
    if (!pop.hidden && !e.target.closest('.sa-spot')) tutup();
  });
  document.addEventListener('keydown', function (e) { if (e.key === 'Escape') tutup(); });
  window.addEventListener('resize', tutup);
})();
