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
    var current = 0;

    var layout = function () {
      var narrow = window.innerWidth < 720;
      var step = narrow ? 150 : 235;
      items.forEach(function (item, i) {
        var d = i - current;
        var abs = Math.abs(d);
        var scale = d === 0 ? 1 : Math.max(0.72, 1 - abs * 0.14);
        item.style.transform =
          'translate(-50%,-50%) translateX(' + (d * step) + 'px) scale(' + scale + ')';
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
    var prev = flow.parentNode.querySelector('.coverflow__nav--prev');
    var next = flow.parentNode.querySelector('.coverflow__nav--next');
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
          return '<button type="button" class="chip" data-size="' + s + '">Ukuran Ban ' + s + '</button>';
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
        return;
      }

      grid.innerHTML = groups.map(function (g) {
        return '' +
          '<section class="size-group">' +
            '<h2>Ukuran ' + g.size + '</h2>' +
            '<div class="product-grid">' +
              g.items.map(function (p) {
                return '' +
                  '<a class="product-card" href="produk.html">' +
                    '<span class="product-card__img"><img src="' + p.img + '" alt="' + p.name + ' ' + g.size + '" loading="lazy"></span>' +
                    '<span class="product-card__body">' +
                      '<strong>' + p.name + '</strong>' +
                      '<span>compatible for : ' + p.compat + '</span>' +
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
      });
    }

    var input = catalog.querySelector('[data-search]');
    if (input) {
      input.addEventListener('input', function () {
        state.q = input.value.trim().toLowerCase();
        render();
      });
    }

    renderChips();
    render();
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
})();
