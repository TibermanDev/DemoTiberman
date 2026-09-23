/* =============================================================
   Halaman News — tab kategori.

   "Home" menampilkan sorotan + semua section kategori; tab lain
   menyembunyikan sorotan dan menyisakan satu section saja.

   Section-nya ditulis langsung di news.html (bukan dirender dari
   data) supaya semua artikel tetap ada dan terindeks kalau skrip
   ini gagal dimuat — yang hilang cuma penyaringnya.
   ============================================================= */
(function () {
  'use strict';

  var tabsBox = document.querySelector('[data-news-tabs]');
  if (!tabsBox) return;

  var tabs = [].slice.call(tabsBox.querySelectorAll('[data-cat]'));
  var seksi = [].slice.call(document.querySelectorAll('.nwp-cat[data-cat]'));
  var top = document.querySelector('[data-news-top]');

  function pilih(cat) {
    tabs.forEach(function (t) {
      t.classList.toggle('is-active', t.getAttribute('data-cat') === cat);
    });
    if (top) top.hidden = cat !== 'all';
    seksi.forEach(function (s) {
      var sendiri = cat !== 'all' && s.getAttribute('data-cat') === cat;
      s.hidden = cat !== 'all' && !sendiri;
      /* is-solo = section ini satu-satunya yang tampil. Headline utamanya
         baru dimunculkan di keadaan itu; di tab Home perannya sudah diambil
         kartu sorotan di atas. */
      s.classList.toggle('is-solo', sendiri);
    });
  }

  /* Tab-nya tautan ke /blog atau /blog/category/{slug}. Klik biasa disaring
     di tempat lalu URL-nya diganti lewat pushState, jadi alamat di browser
     tetap sama dengan yang dirender server kalau halamannya dimuat ulang.
     Ctrl/Cmd/Shift-klik dibiarkan supaya buka-di-tab-baru tetap jalan. */
  tabsBox.addEventListener('click', function (e) {
    var tab = e.target.closest('[data-cat]');
    if (!tab || e.metaKey || e.ctrlKey || e.shiftKey || e.button !== 0) return;
    e.preventDefault();
    pilih(tab.getAttribute('data-cat'));
    if (tab.href && tab.href !== location.href) history.pushState(null, '', tab.href);
  });

  /* Tombol Back/Forward: cocokkan path sekarang dengan href tab. */
  function dariPath() {
    var path = location.pathname.replace(/\/+$/, '');
    for (var i = 0; i < tabs.length; i++) {
      if (tabs[i].pathname.replace(/\/+$/, '') === path) return tabs[i].getAttribute('data-cat');
    }
    return null;
  }

  /* Keadaan awal sudah dirender server (is-active, hidden, is-solo);
     ini cuma menyamakan ulang kalau ada yang berbeda. */
  var awal = tabsBox.querySelector('.is-active') || tabs[0];
  pilih((awal && awal.getAttribute('data-cat')) || 'all');

  window.addEventListener('popstate', function () {
    pilih(dariPath() || 'all');
  });
})();
