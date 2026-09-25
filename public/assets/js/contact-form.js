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
  var toast = document.querySelector('[data-form-toast]');
  var toastTimer;

  var PESAN = {
    id: {
      ok: 'Terima kasih. Permintaan Anda kami terima, tim kami akan menghubungi dalam 1x24 jam.',
      toastJudul: 'Permintaan berhasil dikirim',
      toastTeks: 'Tim kami akan menghubungi Anda dalam 1x24 jam.',
      gagal: 'Maaf, permintaan belum terkirim. Periksa isian Anda lalu coba lagi.',
      captcha: 'Selesaikan verifikasi captcha terlebih dahulu.'
    },
    en: {
      ok: 'Thank you. We have received your request and will get back to you within 1x24 hours.',
      toastJudul: 'Request sent successfully',
      toastTeks: 'Our team will contact you within 1x24 hours.',
      gagal: 'Sorry, your request was not sent. Please check the form and try again.',
      captcha: 'Please complete the captcha first.'
    },
    zh: {
      ok: '感谢您的留言，我们会在 24 小时内与您联系。',
      toastJudul: '提交成功',
      toastTeks: '我们的团队会在 24 小时内与您联系。',
      gagal: '抱歉，提交失败，请检查填写内容后重试。',
      captcha: '请先完成人机验证。'
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

  /* Popup sukses; hilang sendiri setelah 6 detik atau saat ditutup */
  function tutupToast() {
    if (toast) toast.classList.remove('is-on');
    clearTimeout(toastTimer);
  }

  function bukaToast() {
    if (!toast) return;
    var p = bahasa();
    toast.querySelector('[data-toast-title]').textContent = p.toastJudul;
    toast.querySelector('[data-toast-text]').textContent = p.toastTeks;
    toast.classList.add('is-on');
    clearTimeout(toastTimer);
    toastTimer = setTimeout(tutupToast, 6000);
  }

  if (toast) {
    toast.querySelector('[data-toast-close]').addEventListener('click', tutupToast);
    /* dimunculkan server (kiriman tanpa JS): tetap ditutup otomatis */
    if (toast.classList.contains('is-on')) bukaToast();
  }

  form.addEventListener('submit', function (e) {
    e.preventDefault();
    /* reportValidity dipakai supaya pesan "wajib diisi" bawaan browser tetap
       muncul walau form-nya novalidate */
    if (!form.reportValidity()) return;
    /* Token Turnstile belum ada = widget belum selesai (atau masih memuat). */
    var captcha = form.querySelector('[data-captcha]');
    var token = form.querySelector('[name="cf-turnstile-response"]');
    if (captcha && (!token || !token.value)) { tampil(bahasa().captcha); return; }
    if (tombol) tombol.disabled = true;

    fetch(form.action, {
      method: 'POST',
      headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' },
      body: new FormData(form)
    }).then(function (res) {
      if (!res.ok) throw new Error(res.status);
      tampil(bahasa().ok);
      bukaToast();
      form.reset();
    }).catch(function () {
      tampil(bahasa().gagal);
    }).then(function () {
      if (tombol) tombol.disabled = false;
      /* Token hanya berlaku sekali: berhasil atau gagal, minta yang baru. */
      if (captcha && window.turnstile) window.turnstile.reset(captcha);
    });
  });
})();
