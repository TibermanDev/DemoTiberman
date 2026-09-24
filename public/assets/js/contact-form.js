/* =============================================================
   Form "Become Our Partner" di halaman Contact.

   Dikirim lewat fetch() ke POST /kontak supaya halaman tidak reload;
   kirimannya masuk ke menu Permintaan di CMS. Tanpa JS form ini tetap
   jalan sebagai POST biasa (lihat InquiryController).
   ============================================================= */
(function () {
  'use strict';

  var form = document.querySelector('[data-contact-form]');
  if (!form) return;

  var note = form.querySelector('[data-form-note]');
  var tombol = form.querySelector('[type="submit"]');

  var PESAN = {
    id: {
      ok: 'Terima kasih. Permintaan Anda kami terima, tim kami akan menghubungi dalam 1x24 jam.',
      gagal: 'Maaf, permintaan belum terkirim. Periksa isian Anda lalu coba lagi.'
    },
    en: {
      ok: 'Thank you. We have received your request and will get back to you within 1x24 hours.',
      gagal: 'Sorry, your request was not sent. Please check the form and try again.'
    },
    zh: {
      ok: '感谢您的留言，我们会在 24 小时内与您联系。',
      gagal: '抱歉，提交失败，请检查填写内容后重试。'
    }
  };

  function bahasa() {
    var l = window.TIBERMAN_I18N && window.TIBERMAN_I18N.getLang && window.TIBERMAN_I18N.getLang();
    return PESAN[l] ? PESAN[l] : PESAN.id;
  }

  function tampil(teks) {
    if (!note) return;
    note.textContent = teks;
    note.classList.add('is-on');
  }

  form.addEventListener('submit', function (e) {
    e.preventDefault();
    /* reportValidity dipakai supaya pesan "wajib diisi" bawaan browser tetap
       muncul walau form-nya novalidate */
    if (!form.reportValidity()) return;
    if (tombol) tombol.disabled = true;

    fetch(form.action, {
      method: 'POST',
      headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' },
      body: new FormData(form)
    }).then(function (res) {
      if (!res.ok) throw new Error(res.status);
      tampil(bahasa().ok);
      form.reset();
    }).catch(function () {
      tampil(bahasa().gagal);
    }).then(function () {
      if (tombol) tombol.disabled = false;
    });
  });
})();
