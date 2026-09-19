/* =============================================================
   Form "Become Our Partner" di contact.html.

   Belum ada endpoint pengiriman, jadi submit-nya ditahan supaya
   halaman tidak reload dan isian tidak hilang begitu saja —
   diganti pesan konfirmasi di bawah tombol. Ganti isi handler ini
   dengan fetch() ke endpoint yang sebenarnya saat sudah tersedia.
   ============================================================= */
(function () {
  'use strict';

  var form = document.querySelector('[data-contact-form]');
  if (!form) return;

  var note = form.querySelector('[data-form-note]');

  var PESAN = {
    id: 'Terima kasih. Permintaan Anda kami terima, tim kami akan menghubungi dalam 1x24 jam.',
    en: 'Thank you. We have received your request and will get back to you within 1x24 hours.',
    zh: '感谢您的留言，我们会在 24 小时内与您联系。'
  };

  function bahasa() {
    var l = window.TIBERMAN_I18N && window.TIBERMAN_I18N.getLang && window.TIBERMAN_I18N.getLang();
    return PESAN[l] ? PESAN[l] : PESAN.id;
  }

  form.addEventListener('submit', function (e) {
    e.preventDefault();
    /* reportValidity dipakai supaya pesan "wajib diisi" bawaan browser tetap
       muncul walau form-nya novalidate */
    if (!form.reportValidity()) return;
    if (note) {
      note.textContent = bahasa();
      note.classList.add('is-on');
    }
    form.reset();
  });
})();
