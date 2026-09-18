/* =============================================================
   Halaman SuperArea — tab pulau menyaring kartu di bawahnya.

   Kartunya ditulis langsung di superarea.html (bukan dirender dari
   data seperti katalog) supaya isinya tetap ada dan terindeks walau
   skrip ini gagal dimuat; di situ semua kartu tampil, penyaringnya
   saja yang hilang.
   ============================================================= */
(function () {
  'use strict';

  var tabsBox = document.querySelector('[data-sa-tabs]');
  var grid = document.querySelector('[data-sa-grid]');
  if (!tabsBox || !grid) return;

  var tabs = [].slice.call(tabsBox.querySelectorAll('[data-region]'));
  var cards = [].slice.call(grid.querySelectorAll('.sa-card'));

  function pilih(region) {
    tabs.forEach(function (t) {
      var on = t.getAttribute('data-region') === region;
      t.classList.toggle('is-active', on);
      t.setAttribute('aria-selected', on ? 'true' : 'false');
    });
    cards.forEach(function (c) {
      c.hidden = c.getAttribute('data-region') !== region;
    });
  }

  tabsBox.addEventListener('click', function (e) {
    var tab = e.target.closest('[data-region]');
    if (tab) pilih(tab.getAttribute('data-region'));
  });

  var awal = tabsBox.querySelector('.is-active') || tabs[0];
  if (awal) pilih(awal.getAttribute('data-region'));
})();
