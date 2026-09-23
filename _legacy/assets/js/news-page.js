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

  tabsBox.addEventListener('click', function (e) {
    var tab = e.target.closest('[data-cat]');
    if (tab) pilih(tab.getAttribute('data-cat'));
  });

  /* Kategori awal boleh ditentukan dari hash, mis. news.html#alat-berat —
     itu yang dipakai tab & tautan kategori di news-detail.html supaya
     kembalinya langsung ke kategori yang benar. */
  function dariHash() {
    var h = (location.hash || '').replace('#', '');
    return tabs.some(function (t) { return t.getAttribute('data-cat') === h; }) ? h : null;
  }

  var awal = tabsBox.querySelector('.is-active') || tabs[0];
  pilih(dariHash() || (awal && awal.getAttribute('data-cat')) || 'all');

  window.addEventListener('hashchange', function () {
    var h = dariHash();
    if (h) pilih(h);
  });
})();
