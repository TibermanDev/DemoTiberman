/* =============================================================
   Date picker untuk <input type="date" data-datepicker>.

   Tampilannya meniru date picker CMS (Filament, native(false)): panel
   dengan pilihan bulan + tahun di atas, nama hari, lalu kisi tanggal.
   Picker bawaan browser beda-beda di tiap platform dan tidak bisa diberi
   gaya, jadi digambar sendiri.

   Input aslinya diubah jadi type="hidden" dan tetap membawa nilai
   YYYY-MM-DD ke server, jadi InquiryController tidak perlu tahu apa-apa.
   Tanpa JS, input date bawaan browser yang tampil.
   ============================================================= */
(function () {
  'use strict';

  var TEKS = {
    id: { pilih: 'Pilih tanggal', hapus: 'Hapus', hariIni: 'Hari ini', prev: 'Bulan sebelumnya', next: 'Bulan berikutnya' },
    en: { pilih: 'Select a date', hapus: 'Clear', hariIni: 'Today', prev: 'Previous month', next: 'Next month' },
    zh: { pilih: '选择日期', hapus: '清除', hariIni: '今天', prev: '上个月', next: '下个月' }
  };
  var LOCALE = { id: 'id-ID', en: 'en-GB', zh: 'zh-CN' };

  function lang() {
    var l = window.TIBERMAN_I18N && window.TIBERMAN_I18N.getLang && window.TIBERMAN_I18N.getLang();
    return TEKS[l] ? l : 'id';
  }

  function pad(n) { return (n < 10 ? '0' : '') + n; }
  function iso(d) { return d.getFullYear() + '-' + pad(d.getMonth() + 1) + '-' + pad(d.getDate()); }
  function parse(s) {
    var m = /^(\d{4})-(\d{2})-(\d{2})$/.exec(s || '');
    return m ? new Date(+m[1], +m[2] - 1, +m[3]) : null;
  }
  function sameDay(a, b) { return a && b && iso(a) === iso(b); }
  function today() { var d = new Date(); return new Date(d.getFullYear(), d.getMonth(), d.getDate()); }

  function el(tag, cls, text) {
    var e = document.createElement(tag);
    if (cls) e.className = cls;
    if (text != null) e.textContent = text;
    return e;
  }

  function init(input) {
    var min = parse(input.getAttribute('min'));
    var field = input.parentNode;
    var id = input.id || ('dp-' + Math.random().toString(36).slice(2));

    /* Yang terlihat & bisa difokus: input teks readonly berisi tanggal yang
       sudah diformat. Nilai sebenarnya tetap di input asli (hidden). */
    var shown = el('input', 'dp-display');
    shown.type = 'text';
    shown.readOnly = true;
    shown.id = id;
    shown.setAttribute('aria-haspopup', 'dialog');
    shown.setAttribute('aria-expanded', 'false');
    input.removeAttribute('id');
    input.type = 'hidden';
    field.insertBefore(shown, input);
    field.classList.add('dp-field');

    var panel = el('div', 'dp-panel');
    panel.setAttribute('role', 'dialog');
    panel.hidden = true;
    field.appendChild(panel);

    var view = null;   /* bulan yang sedang ditampilkan (tanggal 1) */
    var focusDay = null;

    function selected() { return parse(input.value); }

    function syncShown() {
      var t = TEKS[lang()];
      var d = selected();
      shown.placeholder = t.pilih;
      shown.value = d ? new Intl.DateTimeFormat(LOCALE[lang()], { day: 'numeric', month: 'long', year: 'numeric' }).format(d) : '';
    }

    function disabled(d) { return min && d < min; }

    function render() {
      var t = TEKS[lang()];
      var loc = LOCALE[lang()];
      panel.innerHTML = '';

      var head = el('div', 'dp-head');
      var prev = el('button', 'dp-nav dp-nav--prev');
      prev.type = 'button';
      prev.setAttribute('aria-label', t.prev);
      prev.innerHTML = '<svg viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M12.5 15l-5-5 5-5"/></svg>';
      var next = el('button', 'dp-nav dp-nav--next');
      next.type = 'button';
      next.setAttribute('aria-label', t.next);
      next.innerHTML = '<svg viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M7.5 5l5 5-5 5"/></svg>';

      var month = el('select', 'dp-month');
      var monthFmt = new Intl.DateTimeFormat(loc, { month: 'long' });
      for (var m = 0; m < 12; m++) {
        var o = el('option', null, monthFmt.format(new Date(2000, m, 1)));
        o.value = m;
        if (m === view.getMonth()) o.selected = true;
        month.appendChild(o);
      }
      var year = el('input', 'dp-year');
      year.type = 'number';
      year.value = view.getFullYear();
      year.min = min ? min.getFullYear() : 1900;
      year.max = 2100;

      prev.disabled = !!(min && new Date(view.getFullYear(), view.getMonth(), 0) < min);
      prev.addEventListener('click', function () { go(new Date(view.getFullYear(), view.getMonth() - 1, 1)); });
      next.addEventListener('click', function () { go(new Date(view.getFullYear(), view.getMonth() + 1, 1)); });
      month.addEventListener('change', function () { go(new Date(view.getFullYear(), +month.value, 1)); });
      year.addEventListener('change', function () {
        var y = parseInt(year.value, 10);
        if (y >= 1900 && y <= 2100) go(new Date(y, view.getMonth(), 1));
      });

      head.appendChild(prev);
      head.appendChild(month);
      head.appendChild(year);
      head.appendChild(next);
      panel.appendChild(head);

      /* Nama hari dimulai Senin, sama seperti CMS (firstDayOfWeek Filament). */
      var dow = el('div', 'dp-grid dp-dow');
      var dowFmt = new Intl.DateTimeFormat(loc, { weekday: 'short' });
      for (var i = 0; i < 7; i++) dow.appendChild(el('span', null, dowFmt.format(new Date(2024, 0, 1 + i))));
      panel.appendChild(dow);

      var grid = el('div', 'dp-grid dp-days');
      grid.setAttribute('role', 'grid');
      var lead = (view.getDay() + 6) % 7;
      var days = new Date(view.getFullYear(), view.getMonth() + 1, 0).getDate();
      for (var b = 0; b < lead; b++) grid.appendChild(el('span'));
      var sel = selected(), now = today();
      for (var day = 1; day <= days; day++) {
        var d = new Date(view.getFullYear(), view.getMonth(), day);
        var btn = el('button', 'dp-day', day);
        btn.type = 'button';
        btn.dataset.date = iso(d);
        if (sameDay(d, now)) btn.classList.add('is-today');
        if (sameDay(d, sel)) { btn.classList.add('is-selected'); btn.setAttribute('aria-selected', 'true'); }
        if (disabled(d)) btn.disabled = true;
        btn.tabIndex = sameDay(d, focusDay) ? 0 : -1;
        grid.appendChild(btn);
      }
      grid.addEventListener('click', function (e) {
        var btn = e.target.closest('.dp-day');
        if (btn && !btn.disabled) pick(parse(btn.dataset.date));
      });
      grid.addEventListener('keydown', onGridKey);
      panel.appendChild(grid);

      var foot = el('div', 'dp-foot');
      var clear = el('button', 'dp-link', t.hapus);
      clear.type = 'button';
      clear.addEventListener('click', function () { pick(null); });
      var todayBtn = el('button', 'dp-link', t.hariIni);
      todayBtn.type = 'button';
      todayBtn.disabled = disabled(now);
      todayBtn.addEventListener('click', function () { pick(now); });
      foot.appendChild(clear);
      foot.appendChild(todayBtn);
      panel.appendChild(foot);
    }

    function go(month, keepFocusDay) {
      view = new Date(month.getFullYear(), month.getMonth(), 1);
      if (!keepFocusDay) {
        var s = selected();
        focusDay = s && s.getMonth() === view.getMonth() && s.getFullYear() === view.getFullYear() ? s : view;
      }
      render();
    }

    function focusCurrent() {
      var b = panel.querySelector('.dp-day[tabindex="0"]');
      if (b) b.focus();
    }

    function onGridKey(e) {
      var step = { ArrowLeft: -1, ArrowRight: 1, ArrowUp: -7, ArrowDown: 7 }[e.key];
      if (!step) return;
      e.preventDefault();
      var d = new Date(focusDay.getFullYear(), focusDay.getMonth(), focusDay.getDate() + step);
      if (disabled(d)) return;
      focusDay = d;
      go(d, true);
      focusCurrent();
    }

    function pick(d) {
      input.value = d ? iso(d) : '';
      input.dispatchEvent(new Event('change', { bubbles: true }));
      syncShown();
      close(true);
    }

    function open() {
      if (!panel.hidden) return;
      var s = selected();
      go(s || (min && today() < min ? min : today()));
      panel.hidden = false;
      shown.setAttribute('aria-expanded', 'true');
      document.addEventListener('pointerdown', onOutside, true);
    }

    function close(refocus) {
      if (panel.hidden) return;
      panel.hidden = true;
      shown.setAttribute('aria-expanded', 'false');
      document.removeEventListener('pointerdown', onOutside, true);
      if (refocus) shown.focus();
    }

    function onOutside(e) {
      if (!field.contains(e.target)) close(false);
    }

    shown.addEventListener('click', function () { panel.hidden ? open() : close(false); });
    shown.addEventListener('keydown', function (e) {
      if (e.key === 'Enter' || e.key === ' ' || e.key === 'ArrowDown') {
        e.preventDefault();
        open();
        focusCurrent();
      }
    });
    panel.addEventListener('keydown', function (e) {
      if (e.key === 'Escape') { e.preventDefault(); close(true); }
    });

    /* form.reset() tidak mengosongkan input hidden; tampilannya ikut disegarkan. */
    if (input.form) input.form.addEventListener('reset', function () {
      setTimeout(function () { input.value = ''; syncShown(); close(false); });
    });
    /* Ganti bahasa di navbar -> placeholder & format tanggal ikut berubah. */
    document.addEventListener('tiberman:lang', syncShown);

    syncShown();
    /* i18n.js baru membaca bahasa tersimpan saat DOMContentLoaded (dan saat
       itu juga menimpa placeholder), jadi disegarkan sekali lagi sesudahnya. */
    if (document.readyState === 'loading') document.addEventListener('DOMContentLoaded', syncShown);
  }

  var inputs = document.querySelectorAll('input[type="date"][data-datepicker]');
  for (var i = 0; i < inputs.length; i++) init(inputs[i]);
})();
