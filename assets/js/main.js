/* =============================================================
   Tiberman — interaksi UI
   ============================================================= */
(function () {
  'use strict';

  /* ---------- 1. Navbar mobile ---------- */
  document.querySelectorAll('[data-burger]').forEach(function (btn) {
    btn.addEventListener('click', function () {
      btn.closest('.nav').classList.toggle('is-open');
    });
  });

  /* ---------- 2. Reveal on scroll ---------- */
  var reveals = document.querySelectorAll('.reveal, .pin');
  if ('IntersectionObserver' in window && reveals.length) {
    var io = new IntersectionObserver(function (entries) {
      entries.forEach(function (e) {
        if (!e.isIntersecting) return;
        var el = e.target;
        var delay = parseInt(el.dataset.delay || '0', 10);
        setTimeout(function () { el.classList.add('is-in'); }, delay);
        io.unobserve(el);
      });
    }, { rootMargin: '0px 0px -8% 0px', threshold: 0.12 });
    reveals.forEach(function (el) { io.observe(el); });
  } else {
    reveals.forEach(function (el) { el.classList.add('is-in'); });
  }

  /* ---------- 3. Accordion FAQ ---------- */
  document.querySelectorAll('[data-accordion]').forEach(function (acc) {
    var items = acc.querySelectorAll('.acc__item');
    items.forEach(function (item) {
      var q = item.querySelector('.acc__q');
      var a = item.querySelector('.acc__a');
      var open = function (yes) {
        item.classList.toggle('is-open', yes);
        q.setAttribute('aria-expanded', yes ? 'true' : 'false');
        a.style.height = yes ? a.firstElementChild.offsetHeight + 'px' : '0px';
      };
      if (item.classList.contains('is-open')) open(true);
      q.addEventListener('click', function () {
        var willOpen = !item.classList.contains('is-open');
        items.forEach(function (other) {
          if (other === item) return;
          other.classList.remove('is-open');
          other.querySelector('.acc__q').setAttribute('aria-expanded', 'false');
          other.querySelector('.acc__a').style.height = '0px';
        });
        open(willOpen);
      });
    });
    window.addEventListener('resize', function () {
      acc.querySelectorAll('.acc__item.is-open .acc__a').forEach(function (a) {
        a.style.height = a.firstElementChild.offsetHeight + 'px';
      });
    });
  });

  /* ---------- 4. Coverflow "After Sales" ---------- */
  var flow = document.querySelector('[data-coverflow]');
  if (flow) {
    var items = Array.prototype.slice.call(flow.querySelectorAll('.coverflow__item'));
    var dotsWrap = document.querySelector('[data-coverflow-dots]');
    /* mulai dari slide tengah supaya kipasnya simetris (2 slide di tiap sisi) */
    var current = Math.floor(items.length / 2);

    var layout = function () {
      var narrow = window.innerWidth < 720;
      /* Slide samping diputar 3D (rotateY) memakai perspective dari .coverflow__track,
         jadi bentuknya trapesium mengipas seperti di Figma. Jaraknya dihitung dari
         lebar semu slide yang sudah miring (W * cos angle) supaya tidak saling tumpuk. */
      var angle = 55;                      /* sudut sama di semua ukuran -> faktor 1.28 di bawah tetap valid */
      var gap = narrow ? 10 : 26;
      var W = items[0].offsetWidth || 250;
      var sideW = W * Math.cos(angle * Math.PI / 180);
      /* slide yang makin jauh dari titik perspective ikut melar, jadi jarak antar
         slide terluar dikasih faktor 1.28 biar tetap ada sela dan tidak saling tindih */
      var stepOut = sideW * 1.28 + gap;
      items.forEach(function (item, i) {
        var d = i - current;
        var abs = Math.abs(d);
        var dir = d < 0 ? -1 : 1;
        var x = d === 0 ? 0 : dir * (W / 2 + sideW / 2 + gap + (abs - 1) * stepOut);
        var rot = d === 0 ? 0 : -dir * angle;
        item.style.transform =
          'translate(-50%,-50%) translateX(' + x + 'px) rotateY(' + rot + 'deg)';
        item.style.opacity = abs > 2 ? 0 : 1;
        item.style.zIndex = String(20 - abs);
        item.style.filter = d === 0 ? 'none' : 'grayscale(1) brightness(.72)';
        item.setAttribute('aria-hidden', d === 0 ? 'false' : 'true');
      });
      if (dotsWrap) {
        dotsWrap.querySelectorAll('button').forEach(function (b, i) {
          b.classList.toggle('is-active', i === current);
        });
      }
    };

    var go = function (n) {
      current = (n + items.length) % items.length;
      layout();
    };

    if (dotsWrap) {
      items.forEach(function (_, i) {
        var b = document.createElement('button');
        b.type = 'button';
        b.setAttribute('aria-label', 'Slide ' + (i + 1));
        b.addEventListener('click', function () { go(i); });
        dotsWrap.appendChild(b);
      });
    }
    var prev = document.querySelector('.coverflow__nav--prev');
    var next = document.querySelector('.coverflow__nav--next');
    if (prev) prev.addEventListener('click', function () { go(current - 1); });
    if (next) next.addEventListener('click', function () { go(current + 1); });
    items.forEach(function (item, i) {
      item.addEventListener('click', function () { go(i); });
    });
    window.addEventListener('resize', layout);
    layout();
  }

  /* ---------- 5. Testimoni: drag to scroll ---------- */
  var rail = document.querySelector('[data-drag-rail]');
  if (rail) {
    var down = false, startX = 0, startLeft = 0;
    rail.addEventListener('pointerdown', function (e) {
      down = true; startX = e.clientX; startLeft = rail.scrollLeft;
      rail.classList.add('is-dragging');
    });
    rail.addEventListener('pointermove', function (e) {
      if (!down) return;
      rail.scrollLeft = startLeft - (e.clientX - startX);
    });
    ['pointerup', 'pointerleave', 'pointercancel'].forEach(function (ev) {
      rail.addEventListener(ev, function () {
        down = false; rail.classList.remove('is-dragging');
      });
    });
  }

  /* ---------- 6. Katalog: filter unit + ukuran + pencarian ---------- */
  var catalog = document.querySelector('[data-catalog]');
  if (catalog) {
    var DATA = window.TIBERMAN_PRODUCTS || {};
    var grid = catalog.querySelector('[data-catalog-body]');
    var chipsWrap = catalog.querySelector('[data-chips]');
    /* unit awal boleh ditentukan lewat ?unit= (dipakai dropdown Products) */
    var wanted = (new URLSearchParams(location.search).get('unit') || '').trim();
    if (!DATA[wanted]) wanted = '';

    var state = {
      unit: wanted || catalog.dataset.unit || 'truk-bus',
      size: 'all',
      q: ''
    };

    if (wanted) {
      catalog.dataset.unit = wanted;
      catalog.querySelectorAll('[data-unit]').forEach(function (b) {
        b.classList.toggle('is-active', b.dataset.unit === wanted);
      });
    }

    /* chip ukuran dibangun dari data unit yang aktif */
    var renderChips = function () {
      if (!chipsWrap) return;
      var sizes = (DATA[state.unit] || []).map(function (g) { return g.size; });
      chipsWrap.innerHTML =
        '<button type="button" class="chip is-active" data-size="all">Jenis Unit</button>' +
        sizes.map(function (s) {
          return '<button type="button" class="chip" data-size="' + s + '"><span>Ukuran Ban</span> ' + s + '</button>';
        }).join('');
    };

    var render = function () {
      var groups = (DATA[state.unit] || []).map(function (g) {
        var items = g.items.filter(function (p) {
          var matchSize = state.size === 'all' || g.size === state.size;
          var hay = (p.name + ' ' + p.compat + ' ' + g.size).toLowerCase();
          return matchSize && hay.indexOf(state.q) > -1;
        });
        return { size: g.size, items: items };
      }).filter(function (g) { return g.items.length; });

      if (!groups.length) {
        grid.innerHTML = '<p class="empty">Produk tidak ditemukan. Coba kata kunci atau filter lain.</p>';
        if (window.TIBERMAN_I18N) window.TIBERMAN_I18N.refresh();
        return;
      }

      grid.innerHTML = groups.map(function (g) {
        return '' +
          '<section class="size-group">' +
            '<h2><span>Ukuran</span> ' + g.size + '</h2>' +
            '<div class="product-grid">' +
              g.items.map(function (p) {
                return '' +
                  '<a class="product-card" href="produk.html">' +
                    '<span class="product-card__img"><img src="' + p.img + '" alt="' + p.name + ' ' + g.size + '" loading="lazy"></span>' +
                    '<span class="product-card__body">' +
                      '<strong>' + p.name + '</strong>' +
                      '<span><span>compatible for :</span> ' + p.compat + '</span>' +
                    '</span>' +
                  '</a>';
              }).join('') +
            '</div>' +
          '</section>';
      }).join('');
    };

    catalog.querySelectorAll('[data-unit]').forEach(function (btn) {
      btn.addEventListener('click', function (e) {
        if (btn.tagName === 'A') e.preventDefault();
        catalog.querySelectorAll('[data-unit]').forEach(function (b) {
          b.classList.toggle('is-active', b === btn);
        });
        state.unit = btn.dataset.unit;
        state.size = 'all';
        renderChips();
        render();
        if (window.TIBERMAN_I18N) window.TIBERMAN_I18N.refresh();
      });
    });

    /* delegasi: chip ukuran bisa dibangun ulang saat unit berganti */
    if (chipsWrap) {
      chipsWrap.addEventListener('click', function (e) {
        var btn = e.target.closest('[data-size]');
        if (!btn) return;
        chipsWrap.querySelectorAll('[data-size]').forEach(function (b) {
          b.classList.toggle('is-active', b === btn);
        });
        state.size = btn.dataset.size;
        render();
        if (window.TIBERMAN_I18N) window.TIBERMAN_I18N.refresh();
      });
    }

    var input = catalog.querySelector('[data-search]');
    if (input) {
      input.addEventListener('input', function () {
        state.q = input.value.trim().toLowerCase();
        render();
        if (window.TIBERMAN_I18N) window.TIBERMAN_I18N.refresh();
      });
    }

    renderChips();
    render();
    if (window.TIBERMAN_I18N) window.TIBERMAN_I18N.refresh();
  }

  /* ---------- 7. Halaman produk: galeri + pilihan ukuran ---------- */
  var gallery = document.querySelector('[data-gallery]');
  if (gallery) {
    var main = gallery.querySelector('[data-gallery-main]');
    gallery.querySelectorAll('[data-gallery-thumb]').forEach(function (btn) {
      btn.addEventListener('click', function () {
        gallery.querySelectorAll('[data-gallery-thumb]').forEach(function (b) {
          b.classList.toggle('is-active', b === btn);
        });
        main.src = btn.dataset.full || btn.querySelector('img').src;
        main.alt = btn.querySelector('img').alt;
      });
    });
  }

  document.querySelectorAll('[data-size-chips]').forEach(function (wrap) {
    wrap.querySelectorAll('.size-chip').forEach(function (chip) {
      chip.addEventListener('click', function (e) {
        e.preventDefault();
        wrap.querySelectorAll('.size-chip').forEach(function (c) {
          c.classList.toggle('is-active', c === chip);
        });
      });
    });
  });

  /* ---------- 8. Video latar section (Importir) ----------
     Videonya preload="none" dan baru dimuat + diputar begitu sectionnya masuk
     viewport — supaya kunjungan yang tidak pernah scroll sampai sini tidak ikut
     mengunduh videonya. Sampai itu terjadi yang tampil adalah poster-nya, yaitu
     frame pertama video itu sendiri, jadi mulainya playback tidak kelihatan
     melompat.

     TIDAK looping (durasinya 6 detik): sekali jalan lalu <video> menahan frame
     terakhir sebagai latar diam. Yang memulai ulang adalah KEDATANGAN pengguna —
     tiap kali section ini masuk layar lagi, videonya di-rewind ke 0 dan diputar
     dari awal. */
  var bgVideos = document.querySelectorAll('[data-bg-video]');
  if (bgVideos.length) {
    var noMotion = window.matchMedia && window.matchMedia('(prefers-reduced-motion: reduce)').matches;
    var startBg = function (v) {
      if (v.preload !== 'auto') { v.preload = 'auto'; v.load(); }
      var pr = v.play();
      if (pr && pr.catch) { pr.catch(function () {}); }
    };
    if (noMotion) {
      /* Hemat gerak: cukup posternya, videonya tidak diunduh sama sekali. */
    } else if (!('IntersectionObserver' in window)) {
      bgVideos.forEach(startBg);
    } else {
      /* Ambangnya rasio, bukan rootMargin: dengan rootMargin videonya mulai
         200px SEBELUM sectionnya kelihatan, dan karena durasinya cuma 6 detik,
         pada scroll pelan dia bisa habis sebelum sectionnya benar-benar terlihat.
         PLAY_AT 0.35 = baru diputar ketika sepertiga section sudah di layar. */
      var PLAY_AT = 0.35;
      var bgOn = new WeakSet();   /* videonya sedang "dikunjungi" atau belum */

      var bgIO = new IntersectionObserver(function (entries) {
        entries.forEach(function (e) {
          var v = e.target;
          if (e.intersectionRatio >= PLAY_AT) {
            /* Hanya sekali tiap kedatangan: tanpa penjaga ini, setiap entry baru
               (mis. saat rasio naik-turun di ambang) akan me-rewind videonya di
               tengah jalan. */
            if (bgOn.has(v)) return;
            bgOn.add(v);
            v.currentTime = 0;
            startBg(v);
          } else if (!e.isIntersecting) {
            /* Sudah keluar layar sepenuhnya -> "kunjungan" dianggap selesai,
               jadi kedatangan berikutnya memutar ulang dari awal. */
            bgOn.delete(v);
            if (!v.paused) v.pause();
          }
        });
      }, { threshold: [0, PLAY_AT] });
      bgVideos.forEach(function (v) { bgIO.observe(v); });

      /* Chrome menjeda sendiri media video-only (video ini tanpa track audio)
         waktu tab-nya dianggap latar belakang. Karena status IntersectionObserver
         tidak berubah saat pindah tab, tanpa ini videonya diam selamanya begitu
         pengguna kembali — inilah yang bikin terasa "kadang jalan kadang tidak".
         Dilanjutkan dari posisi terakhir, bukan diulang, supaya tidak menyentak. */
      document.addEventListener('visibilitychange', function () {
        if (document.visibilityState !== 'visible') return;
        bgVideos.forEach(function (v) {
          if (bgOn.has(v) && v.paused && !v.ended) startBg(v);
        });
      });
    }
  }

  /* ---------- 9. Rel kartu kategori: carousel otomatis ----------
     Bukan geseran halus terus-menerus, tapi per KARTU: diam sebentar, lompat
     satu kartu dengan animasi pendek, diam lagi. Begitu kartu terakhir sudah
     mentok kanan, arahnya dibalik ke kiri, dan seterusnya (ping-pong).

     Perpindahannya dianimasikan sendiri per frame, bukan lewat
     scrollTo({behavior:'smooth'}): durasi smooth bawaan browser tidak bisa
     diatur dan beda-beda antar mesin, sedangkan ritme carousel butuh durasi
     yang pasti.

     Yang menghentikan sementara: kursor/jari di atas rel, ada elemen di dalamnya
     yang dapat fokus keyboard, dan rel sedang di luar layar. Kalau pengguna
     minta hemat gerak, seluruh fiturnya tidak dipasang — relnya tetap bisa
     digeser manual. */
  var rail = document.querySelector('[data-velg-rail]');
  if (rail && !(window.matchMedia && window.matchMedia('(prefers-reduced-motion: reduce)').matches)) {
    var RAIL_WAIT = 2200;   /* diam di tiap kartu, ms */
    var RAIL_MOVE = 620;    /* lama perpindahan satu kartu, ms */
    var railDir = 1, railIdx = 0, railPaused = false, railSeen = true;
    var railAnim = null, railTimer = 0;

    var railMax = function () { return rail.scrollWidth - rail.clientWidth; };

    /* Posisi scroll supaya kartu ke-i rata kiri. offsetLeft dihitung dari tepi
       dalam rel (sudah termasuk padding), jadi paddingnya dikurangi lagi biar
       kartunya benar-benar sejajar tepi kiri area yang terlihat. */
    var railTarget = function (i) {
      var card = rail.children[i];
      if (!card) return null;
      var pad = parseFloat(getComputedStyle(rail).paddingLeft) || 0;
      return Math.min(Math.max(card.offsetLeft - pad, 0), railMax());
    };

    var easeInOut = function (t) { return t < .5 ? 4 * t * t * t : 1 - Math.pow(-2 * t + 2, 3) / 2; };

    var railGlide = function (to) {
      var from = rail.scrollLeft, delta = to - from, t0 = 0;
      if (Math.abs(delta) < 1) { railQueue(); return; }
      var frame = function (now) {
        if (!t0) t0 = now;
        var p = Math.min((now - t0) / RAIL_MOVE, 1);
        rail.scrollLeft = from + delta * easeInOut(p);
        if (p < 1) railAnim = requestAnimationFrame(frame);
        else { railAnim = null; railQueue(); }
      };
      railAnim = requestAnimationFrame(frame);
    };

    /* Kartu berikutnya searah railDir. Kalau kartu itu tidak ada, atau posisinya
       sama saja dengan sekarang (kartu-kartu terakhir sudah muat semua di layar),
       berarti sudah mentok: arah dibalik. */
    var railNext = function () {
      if (railPaused || !railSeen || railMax() <= 1) { railQueue(); return; }
      var to = railTarget(railIdx + railDir);
      var now = rail.scrollLeft;
      if (to === null || (railDir > 0 && to <= now + 1)) {
        railDir = -railDir;
        railIdx = railDir > 0 ? 0 : rail.children.length - 1;
        to = railTarget(railIdx);
        if (to === null || Math.abs(to - now) < 1) { railQueue(); return; }
      } else {
        railIdx += railDir;
      }
      railGlide(to);
    };

    function railQueue() {
      clearTimeout(railTimer);
      railTimer = setTimeout(railNext, RAIL_WAIT);
    }

    /* Geseran manual (jari/trackpad) menghentikan animasi yang sedang jalan dan
       menyamakan railIdx dengan kartu terdekat, supaya lompatan berikutnya
       berangkat dari tempat pengguna berhenti. */
    var railSync = function () {
      var best = 0, bestD = Infinity;
      for (var i = 0; i < rail.children.length; i++) {
        var t = railTarget(i);
        if (t === null) continue;
        var d = Math.abs(t - rail.scrollLeft);
        if (d < bestD) { bestD = d; best = i; }
      }
      railIdx = best;
      if (rail.scrollLeft >= railMax() - 1) railDir = -1;
      else if (rail.scrollLeft <= 1) railDir = 1;
    };

    var railPause = function () {
      railPaused = true;
      if (railAnim) { cancelAnimationFrame(railAnim); railAnim = null; }
    };
    var railResume = function () { railPaused = false; railSync(); railQueue(); };

    rail.addEventListener('pointerenter', railPause);
    rail.addEventListener('pointerleave', railResume);
    rail.addEventListener('pointerdown', railPause);
    rail.addEventListener('pointercancel', railResume);
    rail.addEventListener('touchstart', railPause, { passive: true });
    rail.addEventListener('touchend', railResume, { passive: true });
    rail.addEventListener('focusin', railPause);
    rail.addEventListener('focusout', railResume);

    if ('IntersectionObserver' in window) {
      new IntersectionObserver(function (entries) {
        railSeen = entries[0].isIntersecting;
      }, { threshold: 0 }).observe(rail);
    }

    railQueue();
  }

  /* ---------- 10. Intro: video pembuka + serah-terima ke hero ----------
     data-intro di <html> sudah dipasang script inline di <head>.

     Ada dua sumber video dengan sifat yang berbeda, jadi jadwalnya juga beda:

     - animation-transparent.webm (Chrome/Edge/Firefox) beralpha dan TIDAK
       memuat adegan gudang. Karena itu tirai hitamnya boleh diangkat selagi
       logonya masih beranimasi: halaman aslinya muncul di belakang logo yang
       masih hidup, jadi tidak ada momen "video selesai lalu web muncul".
     - animation-black.mp4 (Safari/iOS) adegan gudangnya ikut terbakar di
       video. Tirainya baru boleh diangkat SETELAH videonya disembunyikan,
       sebab gudang versi video dan gudang asli tampil pada skala yang beda
       (video di-cover seluruh viewport, .hero__media cuma pita rasio 2.314) —
       kalau keduanya sempat terlihat bersamaan hasilnya bayangan ganda.

     Angka waktunya diukur dari channel alpha animation-transparent.mov:
     logo memuncak di 2,6s, menyusut, lalu berhenti di 4,4s. Pusatnya selalu
     di tengah frame (cx 0,499 / cy 0,498). Kalau videonya diganti, ukur ulang.

     WM_* dipakai hanya kalau pengukuran runtime gagal (mis. canvas ditolak). */
  var ALPHA = { reveal: 2.6, handoff: 3.4 };   /* tirai naik selagi logo hidup */
  var BLACK = { reveal: 3.2, handoff: 3.2 };   /* tirai naik setelah video hilang */
  var TRAVEL_MS = 900;               /* lama tulisan berjalan ke posisi hero */
  var WM_CX = 0.4990, WM_CY = 0.4981, WM_W = 0.3271;

  var intro = document.querySelector('[data-intro-root]');
  if (intro && document.documentElement.hasAttribute('data-intro')) {
    var video = intro.querySelector('[data-intro-video]');
    var wordmark = document.querySelector('.hero__wordmark');
    var closed = false;
    var cut;

    var finish = function () {
      /* Wordmark hero dipulihkan di sini, bukan cuma di jalur serah-terima:
         lift() menyembunyikannya, jadi kalau intro dilewati setelah tirai naik
         tulisannya akan hilang permanen. Dipulihkan sebelum overlay dibuang
         supaya tidak ada frame tanpa tulisan sama sekali. */
      if (wordmark) wordmark.style.opacity = '';
      document.documentElement.removeAttribute('data-intro');
      intro.remove();
    };

    /* dipakai kalau intro dilewati, videonya gagal, atau geometri tidak
       terhitung: overlay-nya sekadar memudar tanpa serah-terima tulisan */
    var closeIntro = function () {
      if (closed) return;
      closed = true;
      intro.classList.add('is-out');
      clearTimeout(window.__tbmIntroBail);
      clearTimeout(cut);
      if (video) { try { video.pause(); } catch (e) {} }
      intro.addEventListener('transitionend', finish, { once: true });
      setTimeout(finish, 900);   /* kalau transisi tidak pernah jalan */
    };

    intro.querySelector('[data-intro-skip]').addEventListener('click', closeIntro);
    document.addEventListener('keydown', function (e) {
      if (e.key === 'Escape') closeIntro();
    });

    if (video) {
      var bar = intro.querySelector('[data-intro-bar]');
      var fill = intro.querySelector('[data-intro-bar-fill]');
      var ghost = intro.querySelector('[data-intro-wordmark]');
      var skipBtn = intro.querySelector('[data-intro-skip]');

      /* selagi buffering, garisnya ikut seberapa banyak video yang sudah siap */
      var showBuffer = function () {
        if (!fill || !video.duration || !video.buffered.length) return;
        var pct = Math.min(video.buffered.end(video.buffered.length - 1) / video.duration, 1);
        fill.style.width = (pct * 100) + '%';
      };
      video.addEventListener('progress', showBuffer);
      video.addEventListener('loadedmetadata', showBuffer);

      /* Ukur kotak tulisan putih pada frame video yang SEDANG tampil, supaya
         serah-terimanya tetap pas walau potongannya meleset beberapa frame
         (dan tidak perlu diukur ulang manual kalau videonya diganti). Kalau
         hasilnya tidak masuk akal atau canvas-nya gagal, pakai WM_* di atas. */
      var measureWordmark = function () {
        try {
          var cv = document.createElement('canvas');
          cv.width = 320; cv.height = 180;
          var g = cv.getContext('2d', { willReadFrequently: true });
          g.drawImage(video, 0, 0, cv.width, cv.height);
          var d = g.getImageData(0, 0, cv.width, cv.height).data;
          var x0 = 1e9, y0 = 1e9, x1 = -1, y1 = -1;
          for (var i = 0; i < d.length; i += 4) {
            /* alpha ikut diperiksa: di WebM beralpha, tepi logo separuh
               transparan dan tidak boleh ikut menggeser kotaknya */
            if (d[i + 3] > 128 && d[i] > 210 && d[i + 1] > 210 && d[i + 2] > 210) {
              var n = i / 4, px = n % cv.width, py = (n - px) / cv.width;
              if (px < x0) x0 = px;
              if (px > x1) x1 = px;
              if (py < y0) y0 = py;
              if (py > y1) y1 = py;
            }
          }
          var w = (x1 - x0) / cv.width;
          if (w < 0.15 || w > 0.6) return null;   /* kemungkinan bukan tulisan */
          return { cx: (x0 + x1) / 2 / cv.width, cy: (y0 + y1) / 2 / cv.height, w: w };
        } catch (e) {
          return null;    /* canvas bisa gagal, mis. kalau videonya beda origin */
        }
      };

      /* Posisi tulisan video dalam koordinat layar. Ikut object-fit dan
         transform elemennya, jadi tetap benar di layar potret (contain+scale). */
      var videoWordmark = function () {
        var cs = getComputedStyle(video);
        var w = video.clientWidth, h = video.clientHeight;
        var nw = video.videoWidth, nh = video.videoHeight;
        if (!w || !h || !nw || !nh) return null;
        var fit = cs.objectFit === 'contain'
          ? Math.min(w / nw, h / nh)
          : Math.max(w / nw, h / nh);
        var k = 1;
        try { k = new DOMMatrixReadOnly(cs.transform).a || 1; } catch (e) {}
        var m = measureWordmark() || { cx: WM_CX, cy: WM_CY, w: WM_W };
        var r = video.getBoundingClientRect();   /* skala terpusat: titik tengah tetap */
        return {
          cx: r.left + r.width / 2 + (m.cx - 0.5) * nw * fit * k,
          cy: r.top + r.height / 2 + (m.cy - 0.5) * nh * fit * k,
          w: m.w * nw * fit * k
        };
      };

      var handoff = function () {
        if (closed) return;
        var from = wordmark && ghost && videoWordmark();
        var rest = wordmark && wordmark.getBoundingClientRect();
        if (!from || !rest || !rest.width) { closeIntro(); return; }

        closed = true;
        clearTimeout(window.__tbmIntroBail);
        clearTimeout(cut);
        try { video.pause(); } catch (e) {}

        /* Videonya langsung disembunyikan, bukan ikut memudar. Gudang di video
           dan di hero tampil pada skala yang berbeda (video di-cover seluruh
           viewport, .hero__media cuma pita rasio 2.314), jadi kalau keduanya
           sempat terlihat bersamaan hasilnya bayangan ganda. Di detik potong
           frame-nya tinggal ~4% terang, jadi menyembunyikannya tidak kelihatan
           dan yang tersisa cuma tirai hitam polos. */
        video.style.visibility = 'hidden';
        if (bar) bar.hidden = true;
        if (skipBtn) skipBtn.hidden = true;
        lift();                              /* di jalur alpha biasanya sudah naik */
        intro.style.pointerEvents = 'none';  /* klik lolos ke halaman selama travel */

        /* ghost ditaruh di tujuan (posisi istirahat wordmark hero), lalu
           dilempar balik ke posisi tulisan terakhir di video */
        ghost.style.left = rest.left + 'px';
        ghost.style.top = rest.top + 'px';
        ghost.style.width = rest.width + 'px';
        ghost.style.transform =
          'translate(' + (from.cx - (rest.left + rest.width / 2)) + 'px,' +
          (from.cy - (rest.top + rest.height / 2)) + 'px) ' +
          'scale(' + (from.w / rest.width) + ')';
        ghost.style.opacity = '1';

        void ghost.offsetWidth;              /* pastikan posisi awal terpasang dulu */
        ghost.style.transition = 'transform ' + TRAVEL_MS + 'ms var(--ease)';
        ghost.style.transform = 'none';

        /* finish() menukar ghost ke wordmark asli; posisinya sudah sama persis */
        setTimeout(finish, TRAVEL_MS);
      };

      /* Jadwalnya ditentukan sumber mana yang benar-benar dipilih browser.
         currentSrc baru terisi setelah <source> diresolusi, jadi dibaca malas. */
      var T = null;
      var timings = function () {
        if (!T) T = /\.webm(\?|$)/i.test(video.currentSrc || '') ? ALPHA : BLACK;
        return T;
      };

      var lifted = false;
      var lift = function () {
        if (lifted) return;
        lifted = true;
        /* Wordmark hero WAJIB disembunyikan bersamaan tirainya, bukan nanti
           saat serah-terima: begitu tirai hilang halamannya terlihat, dan
           wordmark hero akan tampil berbarengan dengan logo video yang masih
           jalan -- dua tulisan sekaligus di layar. */
        if (wordmark) wordmark.style.opacity = '0';
        intro.classList.add('is-lifting');   /* tirai hitamnya memudar */
      };

      var started = false;
      var onStart = function () {
        if (started || closed) return;
        started = true;
        if (bar) bar.classList.add('is-done');
        /* buffering sudah lewat: timer kasar di <head> diganti jaring pengaman
           yang mengikuti jam video, jadi koneksi lambat tidak memotong animasi */
        clearTimeout(window.__tbmIntroBail);
        cut = setTimeout(handoff, (timings().handoff - video.currentTime + 2) * 1000);
      };

      /* Potongnya dipatok ke jam videonya sendiri, bukan jam dinding: kalau
         playback tersendat, cut-nya ikut mundur dan tidak memotong animasi di
         tengah. requestVideoFrameCallback akurat per frame; timeupdate dipakai
         kalau browsernya belum punya (mis. Firefox).

         Sengaja TIDAK cuma bersandar pada event 'playing': videonya autoplay
         sedangkan main.js dimuat defer, jadi playback sering sudah mulai
         sebelum baris ini jalan (paling sering saat dibuka lewat file://).
         Kalau begitu 'playing' sudah lewat, potongannya tidak pernah
         terjadwal, dan videonya jalan sampai habis. */
      var reached = function () {
        if (closed) return true;
        if (video.currentTime <= 0) return false;
        onStart();
        var t = timings();
        if (video.currentTime >= t.reveal) lift();
        if (video.currentTime >= t.handoff) { handoff(); return true; }
        return false;
      };
      video.addEventListener('playing', onStart);
      if (video.requestVideoFrameCallback) {
        var tick = function () {
          if (!reached()) video.requestVideoFrameCallback(tick);
        };
        video.requestVideoFrameCallback(tick);
      } else {
        video.addEventListener('timeupdate', reached);
      }
      reached();                    /* kalau videonya sudah keburu jalan */

      /* handoff, bukan closeIntro: kalau videonya keburu habis sebelum cut
         (mis. file dipangkas lebih pendek), tulisannya tetap menyeberang.
         handoff sendiri jatuh ke closeIntro kalau geometrinya tak terhitung. */
      video.addEventListener('ended', handoff);
      video.addEventListener('error', closeIntro);
      /* Autoplay bisa ditolak — daripada layar diam, intro dibuka. Tapi
         penolakannya TIDAK boleh langsung menutup: videonya tanpa track audio,
         dan Chrome menghentikan media video-only kalau halamannya dianggap
         background ("paused to save power"), jadi play() ditolak sekali
         padahal begitu tab-nya dilihat playback-nya tetap jalan. Karena itu
         diberi tenggang; menyerah hanya kalau benar-benar tidak ada yang jalan. */
      var play = video.play();
      if (play && play.catch) {
        play.catch(function () {
          setTimeout(function () { if (!started) closeIntro(); }, 1500);
        });
      }
    } else {
      closeIntro();
    }
  }
})();
