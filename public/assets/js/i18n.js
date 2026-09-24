/* =============================================================
   Tiberman — alih bahasa ID / EN / ZH.

   Kamus dikunci memakai teks Indonesia yang ada di HTML, jadi
   markup tidak perlu ditandai satu per satu. String yang tidak
   ada di kamus otomatis tetap tampil apa adanya.

   Isi kamusnya dikelola di CMS (menu Terjemahan) dan disuntikkan
   layout sebagai window.TIBERMAN_DICT sebelum skrip ini dimuat.
   ============================================================= */
window.TIBERMAN_I18N = (function () {
  'use strict';

  var STORE_LANG = 'tbm-lang';

  var SRC = window.TIBERMAN_DICT || {};

  var DICT = { id: {}, en: SRC.en || {}, zh: SRC.zh || {} };
  var ATTRS = ['placeholder', 'alt', 'aria-label', 'title'];
  var lang = 'id';

  /* ---------- kumpulkan node yang bisa diterjemahkan ---------- */
  var textNodes = [];
  var attrNodes = [];
  var SKIP = { SCRIPT: 1, STYLE: 1, NOSCRIPT: 1 };

  function collect(root) {
    var walker = document.createTreeWalker(root, NodeFilter.SHOW_TEXT, {
      acceptNode: function (n) {
        if (!n.nodeValue || !n.nodeValue.trim()) return NodeFilter.FILTER_REJECT;
        var p = n.parentNode;
        while (p && p.nodeType === 1) {
          if (SKIP[p.nodeName.toUpperCase()]) return NodeFilter.FILTER_REJECT;
          p = p.parentNode;
        }
        return NodeFilter.FILTER_ACCEPT;
      }
    });
    var n;
    while ((n = walker.nextNode())) {
      if (n.__i18nSrc === undefined) { n.__i18nSrc = n.nodeValue; textNodes.push(n); }
    }
    var els = root.querySelectorAll('[' + ATTRS.join('],[') + ']');
    Array.prototype.forEach.call(els, function (el) {
      ATTRS.forEach(function (a) {
        if (!el.hasAttribute(a)) return;
        var k = '__i18n_' + a;
        if (el[k] === undefined) { el[k] = el.getAttribute(a); attrNodes.push({ el: el, attr: a }); }
      });
    });
  }

  /* ---------- terjemahkan ---------- */
  function pick(dict, raw) {
    var key = raw.replace(/\s+/g, ' ').trim();
    return key ? dict[key] : null;
  }

  function apply() {
    var dict = DICT[lang] || {};
    textNodes.forEach(function (n) {
      var raw = n.__i18nSrc, t = pick(dict, raw);
      if (t == null) { if (n.nodeValue !== raw) n.nodeValue = raw; return; }
      /* spasi di ujung node dijaga supaya kata tidak menempel ke tag sebelah */
      n.nodeValue = (/^\s/.test(raw) ? ' ' : '') + t + (/\s$/.test(raw) ? ' ' : '');
    });
    attrNodes.forEach(function (r) {
      var raw = r.el['__i18n_' + r.attr], t = pick(dict, raw);
      r.el.setAttribute(r.attr, t == null ? raw : t);
    });
    document.documentElement.setAttribute('lang', lang === 'zh' ? 'zh-CN' : lang);
  }

  /* konten katalog dibangun ulang oleh main.js -> node baru perlu dipungut lagi */
  function refresh(root) {
    collect(root || document.body);
    apply();
  }

  function setLang(next) {
    if (!DICT[next]) return;
    lang = next;
    try { localStorage.setItem(STORE_LANG, next); } catch (e) {}
    apply();
    syncControls();
  }

  /* ---------- kontrol di navbar ---------- */
  var LABEL = { id: 'ID', en: 'EN', zh: '中文' };

  function syncControls() {
    Array.prototype.forEach.call(document.querySelectorAll('[data-lang-label]'), function (s) {
      s.textContent = LABEL[lang];
    });
    Array.prototype.forEach.call(document.querySelectorAll('[data-lang]'), function (b) {
      b.classList.toggle('is-active', b.getAttribute('data-lang') === lang);
    });
  }

  function closeMenus(except) {
    Array.prototype.forEach.call(document.querySelectorAll('.lang.is-open'), function (l) {
      if (l !== except) {
        l.classList.remove('is-open');
        var b = l.querySelector('[data-lang-btn]');
        if (b) b.setAttribute('aria-expanded', 'false');
      }
    });
  }

  function wire() {
    document.addEventListener('click', function (e) {
      var t = e.target;

      var choice = t.closest && t.closest('[data-lang]');
      if (choice) { setLang(choice.getAttribute('data-lang')); closeMenus(); return; }

      var btn = t.closest && t.closest('[data-lang-btn]');
      var wrap = btn && btn.closest('.lang');
      closeMenus(wrap);
      if (wrap) {
        var open = !wrap.classList.contains('is-open');
        wrap.classList.toggle('is-open', open);
        btn.setAttribute('aria-expanded', open ? 'true' : 'false');
      }
    });

    document.addEventListener('keydown', function (e) {
      if (e.key === 'Escape') closeMenus();
    });
  }

  function init() {
    try { lang = localStorage.getItem(STORE_LANG) || 'id'; } catch (e) { lang = 'id'; }
    if (!DICT[lang]) lang = 'id';
    collect(document.body);
    apply();
    wire();
    syncControls();
  }

  if (document.readyState === 'loading') document.addEventListener('DOMContentLoaded', init);
  else init();

  return {
    apply: apply,
    refresh: refresh,
    setLang: setLang,
    getLang: function () { return lang; }
  };
})();
