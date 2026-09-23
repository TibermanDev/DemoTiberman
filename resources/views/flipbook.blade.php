<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Tiberman - {{ $book['title'] }}</title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;600;700&display=swap" rel="stylesheet">
<!-- Halaman ini sengaja berdiri sendiri (tanpa layout situs), sama seperti
     flipbook di situs lama: seluruh layar untuk bukunya. PDF dirender pdf.js
     per halaman ke <canvas>, lalu dibalik oleh StPageFlip. Hanya halaman di
     sekitar posisi baca yang dirender supaya PDF puluhan MB tetap ringan. -->
<style>
  :root { --bg: #2f2d2f; --bar: #1d1c1e; --text: #f2f2f3; --muted: rgba(255,255,255,.62); --red: #ef3936; --red-dark: #cf2a27; }
  * { box-sizing: border-box; }
  html, body { margin: 0; height: 100%; background: var(--bg); color: var(--text); font-family: 'Montserrat', -apple-system, 'Segoe UI', Roboto, sans-serif; }
  body { display: flex; flex-direction: column; overflow: hidden; }

  .fb-bar { flex: none; display: flex; align-items: center; gap: 12px; height: 56px; padding: 0 16px; background: var(--bar); }
  .fb-bar__logo img { display: block; height: 22px; }
  .fb-bar__title { flex: 1; min-width: 0; margin: 0; font-size: 14px; font-weight: 600; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; color: var(--muted); }
  .fb-bar__count { font-size: 13px; font-variant-numeric: tabular-nums; color: var(--muted); white-space: nowrap; }
  .fb-btn { display: inline-flex; align-items: center; justify-content: center; gap: 6px; height: 36px; min-width: 36px; padding: 0 10px; border: 0; border-radius: 8px; background: rgba(255,255,255,.08); color: var(--text); font: inherit; font-size: 13px; font-weight: 600; text-decoration: none; cursor: pointer; }
  .fb-btn:hover { background: rgba(255,255,255,.16); }
  .fb-btn:disabled { opacity: .35; cursor: default; }
  .fb-btn--primary { background: var(--red); }
  .fb-btn--primary:hover { background: var(--red-dark); }
  .fb-btn svg { width: 16px; height: 16px; }

  .fb-stage { flex: 1; min-height: 0; display: flex; align-items: center; justify-content: center; padding: 16px; }
  .fb-book { width: 100%; }
  .fb-page { background: #fff; overflow: hidden; }
  .fb-page canvas { display: block; width: 100%; height: 100%; }

  .fb-status { position: fixed; inset: 56px 0 0; display: flex; flex-direction: column; align-items: center; justify-content: center; gap: 14px; padding: 16px; text-align: center; color: var(--muted); font-size: 14px; }
  .fb-status[hidden] { display: none; }
  .fb-progress { width: min(280px, 70vw); height: 4px; border-radius: 2px; background: rgba(255,255,255,.12); overflow: hidden; }
  .fb-progress span { display: block; height: 100%; width: 0; background: var(--red); transition: width .2s; }

  @media (max-width: 560px) {
    .fb-bar { gap: 8px; padding: 0 10px; }
    .fb-bar__title, .fb-btn__label { display: none; }
    .fb-stage { padding: 8px; }
  }
</style>
</head>
<body>

<header class="fb-bar">
  <a class="fb-bar__logo" href="{{ route('home') }}"><img src="{{ asset('assets/img/logo-white.png') }}" alt="Tiberman"></a>
  <h1 class="fb-bar__title">{{ $book['title'] }}</h1>
  <span class="fb-bar__count" data-fb-count></span>
  <button class="fb-btn" type="button" data-fb-prev aria-label="Halaman sebelumnya" disabled>
    <svg viewBox="0 0 16 16" fill="none" stroke="currentColor" stroke-width="2"><path d="M10 3L5 8l5 5"/></svg>
  </button>
  <button class="fb-btn" type="button" data-fb-next aria-label="Halaman berikutnya" disabled>
    <svg viewBox="0 0 16 16" fill="none" stroke="currentColor" stroke-width="2"><path d="M6 3l5 5-5 5"/></svg>
  </button>
  <a class="fb-btn fb-btn--primary" href="{{ $book['pdf'] }}" target="_blank" rel="noopener" download>
    <svg viewBox="0 0 16 16" fill="none" stroke="currentColor" stroke-width="2"><path d="M8 2v8m-3-3l3 3 3-3M3 13h10"/></svg>
    <span class="fb-btn__label">PDF</span>
  </a>
</header>

<main class="fb-stage" data-fb-stage>
  <div class="fb-book" data-fb-book></div>
</main>

<div class="fb-status" data-fb-status>
  <p data-fb-message>Memuat {{ $book['title'] }}…</p>
  <div class="fb-progress"><span data-fb-progress></span></div>
</div>

<script src="https://cdn.jsdelivr.net/npm/pdfjs-dist@3.11.174/build/pdf.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/page-flip@2.0.7/dist/js/page-flip.browser.js"></script>
<script>
(function () {
  'use strict';

  var PDF_URL = @json($book['pdf']);
  var stage = document.querySelector('[data-fb-stage]');
  var bookEl = document.querySelector('[data-fb-book]');
  var statusEl = document.querySelector('[data-fb-status]');
  var messageEl = document.querySelector('[data-fb-message]');
  var progressEl = document.querySelector('[data-fb-progress]');
  var countEl = document.querySelector('[data-fb-count]');
  var prevBtn = document.querySelector('[data-fb-prev]');
  var nextBtn = document.querySelector('[data-fb-next]');

  /* Halaman yang dirender = posisi baca ± RENDER_AHEAD; yang lebih jauh dari
     KEEP dibuang kanvasnya supaya memori tidak menumpuk. */
  var RENDER_AHEAD = 3;
  var KEEP = 6;

  function fail(err) {
    console.error(err);
    messageEl.innerHTML = 'Flipbook gagal dimuat. <a href="' + PDF_URL + '" style="color:#fff">Buka PDF-nya langsung</a>.';
    progressEl.parentNode.hidden = true;
    statusEl.hidden = false;
  }

  if (!window.pdfjsLib || !window.St) return fail(new Error('Library flipbook gagal dimuat'));

  pdfjsLib.GlobalWorkerOptions.workerSrc = 'https://cdn.jsdelivr.net/npm/pdfjs-dist@3.11.174/build/pdf.worker.min.js';

  /* disableAutoFetch: hanya bagian PDF yang dibutuhkan yang diunduh (lewat
     Range request), bukan seluruh file di belakang layar. */
  var task = pdfjsLib.getDocument({ url: PDF_URL, disableAutoFetch: true, rangeChunkSize: 1024 * 1024 });
  task.onProgress = function (p) {
    if (p.total) progressEl.style.width = Math.min(100, p.loaded / p.total * 100) + '%';
  };

  task.promise.then(function (pdf) {
    return pdf.getPage(1).then(function (first) {
      var vp = first.getViewport({ scale: 1 });
      build(pdf, vp.width, vp.height);
    });
  }).catch(fail);

  function build(pdf, pageW, pageH) {
    var total = pdf.numPages;
    var ratio = pageH / pageW;

    /* Ukuran maksimum satu halaman supaya dua halaman berdampingan muat di
       layar (tinggi area baca jadi batasnya). */
    var maxH = stage.clientHeight - 32;
    var maxW = Math.round(maxH / ratio);

    var pages = [];
    for (var i = 0; i < total; i++) {
      var el = document.createElement('div');
      el.className = 'fb-page';
      bookEl.appendChild(el);
      pages.push({ el: el, canvas: null, rendering: null });
    }

    var flip = new St.PageFlip(bookEl, {
      width: maxW,
      height: maxH,
      size: 'stretch',
      minWidth: 200,
      maxWidth: maxW,
      minHeight: Math.round(200 * ratio),
      maxHeight: maxH,
      showCover: true,
      usePortrait: true,
      mobileScrollSupport: false,
      maxShadowOpacity: 0.4,
      flippingTime: 700
    });
    flip.loadFromHTML(bookEl.querySelectorAll('.fb-page'));

    /* Resolusi render: selebar halaman terbesar × devicePixelRatio (maks 2). */
    var renderScale = Math.min(maxW, stage.clientWidth) * Math.min(window.devicePixelRatio || 1, 2) / pageW;

    function renderPage(idx) {
      var p = pages[idx];
      if (!p || p.canvas || p.rendering) return;
      p.rendering = pdf.getPage(idx + 1).then(function (page) {
        var vp = page.getViewport({ scale: renderScale });
        var canvas = document.createElement('canvas');
        canvas.width = Math.round(vp.width);
        canvas.height = Math.round(vp.height);
        return page.render({ canvasContext: canvas.getContext('2d'), viewport: vp }).promise.then(function () {
          p.el.appendChild(canvas);
          p.canvas = canvas;
          p.rendering = null;
        });
      }).catch(function (err) { p.rendering = null; console.error(err); });
    }

    function around(idx) {
      for (var i = idx - 1; i <= idx + RENDER_AHEAD; i++) renderPage(i);
      pages.forEach(function (p, i) {
        if (p.canvas && Math.abs(i - idx) > KEEP) {
          p.canvas.width = p.canvas.height = 0;
          p.canvas.remove();
          p.canvas = null;
        }
      });
    }

    function update(idx) {
      countEl.textContent = (idx + 1) + ' / ' + total;
      prevBtn.disabled = idx <= 0;
      nextBtn.disabled = idx >= total - 1;
      around(idx);
    }

    flip.on('flip', function (e) { update(e.data); });
    prevBtn.addEventListener('click', function () { flip.flipPrev(); });
    nextBtn.addEventListener('click', function () { flip.flipNext(); });
    document.addEventListener('keydown', function (e) {
      if (e.key === 'ArrowLeft') flip.flipPrev();
      if (e.key === 'ArrowRight') flip.flipNext();
    });

    update(0);
    statusEl.hidden = true;
  }
})();
</script>
</body>
</html>
