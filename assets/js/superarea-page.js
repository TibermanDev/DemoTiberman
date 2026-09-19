/* =============================================================
   Halaman SuperArea — tab pulau menyaring kartu di bawahnya.

   Kartunya ditulis langsung di superarea.html (bukan dirender dari
   data seperti katalog) supaya isinya tetap ada dan terindeks walau
   skrip ini gagal dimuat; di situ semua kartu tampil, penyaringnya
   saja yang hilang.

   Ganti tab memang mengubah JUMLAH BARIS kartu (Jawa 4 kartu = 2 baris,
   Sumatra 1 kartu = 1 baris). Kalau kartunya cuma di-hidden begitu saja,
   tinggi halaman terpotong dalam satu frame dan bumi yang terpaku di dasar
   halaman ikut melompat — terlihat patah. Jadi tinggi .sa__grid diukur
   sebelum & sesudah penyaringan lalu dianimasikan dari nilai lama ke nilai
   baru, dan kartu yang baru muncul ikut memudar naik.
   ============================================================= */
(function () {
  'use strict';

  var tabsBox = document.querySelector('[data-sa-tabs]');
  var grid = document.querySelector('[data-sa-grid]');
  if (!tabsBox || !grid) return;

  var tabs = [].slice.call(tabsBox.querySelectorAll('[data-region]'));
  var cards = [].slice.call(grid.querySelectorAll('.sa-card'));

  /* Kalau pengguna minta hemat gerak, penyaringannya tetap jalan —
     yang dilepas cuma animasinya. */
  var hematGerak = window.matchMedia &&
    window.matchMedia('(prefers-reduced-motion:reduce)').matches;

  /* Samakan dengan transition-duration .sa__grid di style.css. */
  var DURASI = 420;
  var JEDA_KARTU = 45;          /* selang munculnya kartu, per kartu */

  var aktif = null;
  var timer = 0;

  function tandaiTab(region) {
    tabs.forEach(function (t) {
      var on = t.getAttribute('data-region') === region;
      t.classList.toggle('is-active', on);
      t.setAttribute('aria-selected', on ? 'true' : 'false');
    });
  }

  /* Menyaring kartu, mengembalikan yang tampil supaya bisa dianimasikan. */
  function saring(region) {
    var tampil = [];
    cards.forEach(function (c) {
      var on = c.getAttribute('data-region') === region;
      c.hidden = !on;
      if (on) tampil.push(c);
    });
    return tampil;
  }

  function masuk(tampil) {
    /* Dibersihkan dari SEMUA kartu, bukan cuma yang akan tampil: kartu yang
       tersembunyi di tengah animasi jadi display:none, animasinya dibatalkan,
       dan animationend-nya tidak pernah terkirim — kelasnya akan tertinggal
       kalau tidak disapu di sini. */
    cards.forEach(function (c) {
      c.classList.remove('is-enter');
      c.style.removeProperty('--sa-enter-delay');
    });
    tampil.forEach(function (c, i) {
      void c.offsetWidth;                       /* restart animasinya */
      c.style.setProperty('--sa-enter-delay', (i * JEDA_KARTU) + 'ms');
      c.classList.add('is-enter');
    });
  }

  /* Kebersihan tambahan; yang menjamin hover tetap hidup adalah fill-mode
     backwards di CSS, bukan penghapusan kelas ini. */
  grid.addEventListener('animationend', function (e) {
    if (e.animationName === 'sa-card-in') {
      e.target.classList.remove('is-enter');
      e.target.style.removeProperty('--sa-enter-delay');
    }
  });

  function lepasTinggi() {
    clearTimeout(timer);
    grid.classList.remove('is-anim');
    grid.style.height = '';
  }

  function pilih(region, animasi) {
    if (region === aktif) return;
    aktif = region;
    tandaiTab(region);

    if (!animasi || hematGerak) { saring(region); return; }

    /* Diukur dari rect, bukan dari tinggi alaminya: kalau tab diklik cepat
       berkali-kali, nilai ini adalah tinggi yang SEDANG dianimasikan, jadi
       animasi berikutnya menyambung mulus dari situ. */
    var dari = grid.getBoundingClientRect().height;

    var tampil = saring(region);

    /* Tinggi inline dilepas sebentar untuk membaca tinggi alami susunan
       barunya. Semuanya di dalam satu task, jadi tidak pernah sempat
       tergambar — tidak ada kedipan. */
    grid.style.height = '';
    var ke = grid.getBoundingClientRect().height;

    masuk(tampil);

    if (Math.abs(ke - dari) < 1) { lepasTinggi(); return; }

    grid.classList.add('is-anim');
    grid.style.height = dari + 'px';
    void grid.offsetHeight;                     /* paksa reflow: transisi butuh titik awal */
    grid.style.height = ke + 'px';

    /* Pakai timer, bukan transitionend: kejadian itu bisa terlewat kalau
       tabnya diklik lagi di tengah animasi, dan gridnya akan terkunci pada
       tinggi tetap. */
    clearTimeout(timer);
    timer = setTimeout(lepasTinggi, DURASI + 80);
  }

  tabsBox.addEventListener('click', function (e) {
    var tab = e.target.closest('[data-region]');
    if (tab) pilih(tab.getAttribute('data-region'), true);
  });

  var awal = tabsBox.querySelector('.is-active') || tabs[0];
  if (awal) pilih(awal.getAttribute('data-region'), false);
})();
