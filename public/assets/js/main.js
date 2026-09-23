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

  /* ---------- 4. Coverflow "After Sales" ----------
     Kipas 3D: satu slide di tengah, dua di kiri, dua di kanan. Berputar
     sendiri tiap 3 detik dan TIDAK pernah menyisakan sisi kosong.

     Supaya kedua sisi selalu terisi dua, jarak slide ke tengah dihitung
     MELINGKAR: selisih indeks dibungkus ke rentang -n/2..n/2, jadi slide
     yang keluar di satu ujung otomatis dianggap masuk di ujung seberangnya.

     Daftar slide juga DIGANDAKAN. Tanpa itu, dengan lima slide untuk lima
     posisi, tiap langkah ada satu slide yang harus lompat dari ujung kanan
     ke ujung kiri — dan karena kelimanya kelihatan, lompatan itu ikut
     terlihat sebagai kartu yang melesat menyeberang. Dengan sepuluh slide,
     yang melompat selalu slide di posisi jauh yang opacity-nya sudah 0, jadi
     penyeberangannya terjadi di luar pandangan. */
  var flow = document.querySelector('[data-coverflow]');
  if (flow) {
    var track = flow.querySelector('.coverflow__track');
    var real = Array.prototype.slice.call(track.querySelectorAll('.coverflow__item'));
    var nReal = real.length;

    /* Salinan hanya untuk mata: aria-hidden supaya daftar layanannya tidak
       dibacakan dua kali oleh pembaca layar. */
    real.forEach(function (el) {
      var c = el.cloneNode(true);
      c.setAttribute('aria-hidden', 'true');
      track.appendChild(c);
    });

    var items = Array.prototype.slice.call(track.querySelectorAll('.coverflow__item'));
    var half = Math.floor(items.length / 2);
    var dotsWrap = document.querySelector('[data-coverflow-dots]');
    var capTitle = document.querySelector('[data-coverflow-title]');
    var capDesc = document.querySelector('[data-coverflow-desc]');
    var calm = window.matchMedia && window.matchMedia('(prefers-reduced-motion: reduce)').matches;
    /* mulai dari slide tengah supaya kipasnya simetris (2 slide di tiap sisi) */
    var current = Math.floor(nReal / 2);
    var flowTimer = null;

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
        /* jarak melingkar: -half..half, bukan sekadar i - current */
        var d = (i - current) % items.length;
        if (d < -half) d += items.length;
        if (d > half) d -= items.length;
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
      /* Titik penanda tetap lima: slide ke-6..10 cuma salinan slide ke-1..5. */
      var active = current % nReal;
      if (dotsWrap) {
        dotsWrap.querySelectorAll('button').forEach(function (b, i) {
          b.classList.toggle('is-active', i === active);
        });
      }
      var src = items[current];
      if (capTitle && src.dataset.title) capTitle.textContent = src.dataset.title;
      if (capDesc && src.dataset.desc) capDesc.textContent = src.dataset.desc;
    };

    var go = function (n) {
      current = (n + items.length) % items.length;
      layout();
    };

    var flowStop = function () { if (flowTimer) { clearInterval(flowTimer); flowTimer = null; } };
    var flowPlay = function () {
      flowStop();
      if (calm) return;                     /* hemat gerak: diam saja */
      /* current - 1 = isinya bergeser ke KANAN, slide paling kanan yang
         berpindah mengisi sisi kiri. */
      flowTimer = setInterval(function () { go(current - 1); }, 3000);
    };

    /* Ditahan selama disentuh/di-hover atau ada fokus keyboard di dalamnya —
       kalau tidak, layanan yang sedang dibaca keburu berganti sendiri. */
    /* Dipasang di seluruh section, bukan cuma di kipasnya: caption dan panah
       di bawah ikut bagian yang dibaca/dipakai orang. */
    var flowZone = document.querySelector('.aftersales') || flow;
    ['mouseenter', 'focusin', 'touchstart'].forEach(function (ev) {
      flowZone.addEventListener(ev, flowStop, { passive: true });
    });
    ['mouseleave', 'focusout', 'touchend'].forEach(function (ev) {
      flowZone.addEventListener(ev, flowPlay, { passive: true });
    });
    /* Tab yang tidak terlihat tidak perlu diputar. */
    document.addEventListener('visibilitychange', function () {
      if (document.hidden) flowStop(); else flowPlay();
    });

    if (dotsWrap) {
      real.forEach(function (_, i) {
        var b = document.createElement('button');
        b.type = 'button';
        b.setAttribute('aria-label', 'Slide ' + (i + 1));
        /* Dipilih dari dua slide berjudul sama yang jaraknya paling dekat,
           supaya kipasnya bergeser seperlunya, bukan memutar setengah lingkaran. */
        b.addEventListener('click', function () {
          var a = i, bIdx = i + nReal;
          var da = Math.abs(((a - current + half) % items.length + items.length) % items.length - half);
          var db = Math.abs(((bIdx - current + half) % items.length + items.length) % items.length - half);
          go(da <= db ? a : bIdx);
          flowPlay();
        });
        dotsWrap.appendChild(b);
      });
    }
    var prev = document.querySelector('.coverflow__nav--prev');
    var next = document.querySelector('.coverflow__nav--next');
    if (prev) prev.addEventListener('click', function () { go(current - 1); flowPlay(); });
    if (next) next.addEventListener('click', function () { go(current + 1); flowPlay(); });
    items.forEach(function (item, i) {
      item.addEventListener('click', function () { go(i); flowPlay(); });
    });
    window.addEventListener('resize', layout);
    layout();
    flowPlay();
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

  /* ---------- 6. Katalog: filter unit + merk + ukuran + pencarian ----------
     Tombol unit & merk (dan chip ukuran) adalah tautan ke URL kategori toko
     lama — petanya di config/catalog.php, dikirim ke sini sebagai
     window.TIBERMAN_CATALOG_URLS. Keadaan awal dirender server lewat
     data-unit / data-brand / data-size; klik berikutnya disaring di tempat
     tanpa reload dan URL-nya diganti lewat pushState, jadi halaman yang
     dimuat ulang menampilkan hasil yang sama. */
  var catalog = document.querySelector('[data-catalog]');
  if (catalog) {
    var DATA = window.TIBERMAN_PRODUCTS || {};
    var URLS = window.TIBERMAN_CATALOG_URLS || { units: {}, brands: {}, sizes: {}, paths: {} };
    var grid = catalog.querySelector('[data-catalog-body]');
    var chipsWrap = catalog.querySelector('[data-chips]');
    var DEFAULT = { unit: 'truk-bus', brand: 'all', size: 'all' };

    var state = {
      unit: catalog.dataset.unit || DEFAULT.unit,
      brand: catalog.dataset.brand || 'all',
      size: catalog.dataset.size || 'all',
      q: ''
    };

    /* Unit 'all' = semua unit digabung, dikelompokkan per ukuran. Dipakai
       halaman merk (merk dijual di banyak unit) dan halaman ukuran. */
    var groupsOf = function (unit) {
      if (unit !== 'all') return DATA[unit] || [];
      var bySize = {}, order = [];
      Object.keys(DATA).forEach(function (u) {
        DATA[u].forEach(function (g) {
          if (!bySize[g.size]) { bySize[g.size] = []; order.push(g.size); }
          bySize[g.size] = bySize[g.size].concat(g.items);
        });
      });
      return order.map(function (s) { return { size: s, items: bySize[s] }; });
    };

    /* Merk = bagian nama sebelum " - " ("UNINEST - TIBERMAX 554"). */
    var brandOf = function (p) { return p.name.toLowerCase().split(' - ')[0].trim(); };

    /* chip ukuran dibangun dari unit (dan merk) yang aktif; chip yang punya
       URL toko lama jadi tautan, sisanya (mis. ukuran velg) tetap tombol */
    var renderChips = function () {
      if (!chipsWrap) return;
      var sizes = groupsOf(state.unit).filter(function (g) {
        return state.brand === 'all' || g.items.some(function (p) { return brandOf(p) === state.brand; });
      }).map(function (g) { return g.size; });
      /* ukuran dari URL tetap punya chip walau belum ada produknya */
      if (state.size !== 'all' && sizes.indexOf(state.size) < 0) sizes.push(state.size);

      var chip = function (size, label) {
        var cls = 'chip' + (state.size === size ? ' is-active' : '');
        var href = size === 'all' ? URLS.units[state.unit] : URLS.sizes[size];
        return href
          ? '<a class="' + cls + '" href="' + href + '" data-size="' + size + '">' + label + '</a>'
          : '<button type="button" class="' + cls + '" data-size="' + size + '">' + label + '</button>';
      };
      chipsWrap.innerHTML = chip('all', 'All Size') + sizes.map(function (s) { return chip(s, s); }).join('');
    };

    var render = function () {
      var groups = groupsOf(state.unit).map(function (g) {
        var items = g.items.filter(function (p) {
          var matchSize = state.size === 'all' || g.size === state.size;
          var matchBrand = state.brand === 'all' || brandOf(p) === state.brand;
          var hay = (p.name + ' ' + p.compat + ' ' + g.size).toLowerCase();
          return matchSize && matchBrand && hay.indexOf(state.q) > -1;
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
                  '<a class="product-card" href="/produk">' +
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

    var unitBtns = catalog.querySelectorAll('[data-unit]:not([data-catalog])');
    var brandBtns = catalog.querySelectorAll('[data-brand]:not([data-catalog])');

    var syncSidebar = function () {
      unitBtns.forEach(function (b) {
        b.classList.toggle('is-active', state.brand === 'all' && b.dataset.unit === state.unit);
      });
      brandBtns.forEach(function (b) {
        b.classList.toggle('is-active', b.dataset.brand === state.brand);
      });
    };

    /* URL untuk keadaan sekarang: filter yang paling spesifik yang menang. */
    var urlFor = function () {
      if (state.brand !== 'all') return URLS.brands[state.brand];
      if (state.size !== 'all' && URLS.sizes[state.size]) return URLS.sizes[state.size];
      return URLS.units[state.unit];
    };

    var titleFor = function () {
      var label;
      if (state.brand !== 'all') {
        brandBtns.forEach(function (b) { if (b.dataset.brand === state.brand) label = 'Ban ' + b.textContent.trim(); });
      } else if (state.size !== 'all') {
        label = 'Ban ' + state.size;
      } else {
        unitBtns.forEach(function (b) { if (b.dataset.unit === state.unit) label = b.textContent.trim(); });
      }
      return (label || 'Katalog Ban') + ' — Tiberman';
    };

    var apply = function (next, push) {
      state.unit = next.unit;
      state.brand = next.brand;
      state.size = next.size;
      renderChips();
      render();
      syncSidebar();
      if (push) {
        var url = urlFor();
        if (url && url !== location.pathname) history.pushState(null, '', url);
        document.title = titleFor();
      }
      if (window.TIBERMAN_I18N) window.TIBERMAN_I18N.refresh();
    };

    /* Ctrl/Cmd/Shift/klik tengah dibiarkan jalan sebagai tautan biasa. */
    var plainClick = function (e) {
      return !(e.metaKey || e.ctrlKey || e.shiftKey || e.altKey || e.button !== 0);
    };

    unitBtns.forEach(function (btn) {
      btn.addEventListener('click', function (e) {
        if (!plainClick(e)) return;
        e.preventDefault();
        apply({ unit: btn.dataset.unit, brand: 'all', size: 'all' }, true);
      });
    });

    /* Merk menyaring di semua unit. Tidak ada tombol "semua merk" di
       desainnya, jadi klik ulang merk yang aktif kembali ke Semua Ban. */
    brandBtns.forEach(function (btn) {
      btn.addEventListener('click', function (e) {
        if (!plainClick(e)) return;
        e.preventDefault();
        var lepas = state.brand === btn.dataset.brand;
        apply({ unit: 'all', brand: lepas ? 'all' : btn.dataset.brand, size: 'all' }, true);
      });
    });

    /* delegasi: chip ukuran dibangun ulang tiap filter berganti */
    if (chipsWrap) {
      chipsWrap.addEventListener('click', function (e) {
        var btn = e.target.closest('[data-size]');
        if (!btn || !plainClick(e)) return;
        e.preventDefault();
        apply({ unit: state.unit, brand: state.brand, size: btn.dataset.size }, true);
      });
    }

    window.addEventListener('popstate', function () {
      var path = location.pathname.replace(/\/+$/, '');
      apply(URLS.paths[path] || DEFAULT, false);
      document.title = titleFor();
    });

    var input = catalog.querySelector('[data-search]');
    if (input) {
      input.addEventListener('input', function () {
        state.q = input.value.trim().toLowerCase();
        render();
        if (window.TIBERMAN_I18N) window.TIBERMAN_I18N.refresh();
      });
    }

    apply(state, false);
  }

  /* ---------- 6b. Modal detail produk ----------
     Kartu katalog tetap <a href="produk.html"> supaya tanpa JS, klik kanan,
     atau klik tengah tetap membuka halaman produk. Klik biasa dicegat di sini
     dan dialihkan ke modal.

     Semua slide memakai permukaan terang, jadi modalnya tidak punya varian
     warna — fotonya PNG beralpha dan logonya versi untuk latar terang. */
  var pmodal = document.querySelector('[data-pmodal]');
  if (pmodal) {
    var pmTrack = pmodal.querySelector('[data-pmodal-track]');
    var pmSlides = Array.prototype.slice.call(pmTrack.children);
    var pmDots = pmodal.querySelector('[data-pmodal-dots]');
    var pmIdx = 0, pmPemanggil = null;

    var pmShow = function (i) {
      pmIdx = (i + pmSlides.length) % pmSlides.length;
      pmTrack.style.transform = 'translateX(' + (-pmIdx * 100) + '%)';
      if (pmDots) {
        pmDots.querySelectorAll('button').forEach(function (b, n) {
          b.classList.toggle('is-active', n === pmIdx);
        });
      }
      /* Slide yang tidak tampil disembunyikan dari pembaca layar dan dari
         urutan Tab — kalau tidak, Tab "menghilang" ke slide di luar layar. */
      pmSlides.forEach(function (sl, n) {
        sl.setAttribute('aria-hidden', n === pmIdx ? 'false' : 'true');
        sl.querySelectorAll('a,button').forEach(function (el) { el.tabIndex = n === pmIdx ? 0 : -1; });
      });
    };

    if (pmDots) {
      pmSlides.forEach(function (_, i) {
        var b = document.createElement('button');
        b.type = 'button';
        b.setAttribute('aria-label', 'Slide ' + (i + 1));
        b.addEventListener('click', function () { pmShow(i); });
        pmDots.appendChild(b);
      });
    }

    var pmClose = function () {
      pmodal.hidden = true;
      document.body.style.overflow = '';
      /* fokus dikembalikan ke kartu yang membukanya */
      if (pmPemanggil) { pmPemanggil.focus(); pmPemanggil = null; }
    };

    var pmOpen = function (pemanggil) {
      pmPemanggil = pemanggil || null;
      pmodal.hidden = false;
      /* halaman di belakang dikunci supaya scroll tidak bocor ke katalog */
      document.body.style.overflow = 'hidden';
      pmShow(0);
      var tutup = pmodal.querySelector('[data-pmodal-close]');
      if (tutup) tutup.focus();
    };

    pmodal.querySelectorAll('[data-pmodal-close]').forEach(function (el) {
      el.addEventListener('click', pmClose);
    });
    var pmNext = pmodal.querySelector('[data-pmodal-next]');
    if (pmNext) pmNext.addEventListener('click', function () { pmShow(pmIdx + 1); });

    document.addEventListener('keydown', function (e) {
      if (pmodal.hidden) return;
      if (e.key === 'Escape') pmClose();
      else if (e.key === 'ArrowRight') pmShow(pmIdx + 1);
      else if (e.key === 'ArrowLeft') pmShow(pmIdx - 1);
    });

    /* Delegasi: kartu dibangun ulang tiap ganti unit/filter. */
    document.addEventListener('click', function (e) {
      var kartu = e.target.closest && e.target.closest('.product-card');
      if (!kartu) return;
      /* klik tengah / ctrl-klik dibiarkan membuka tab baru seperti biasa */
      if (e.metaKey || e.ctrlKey || e.shiftKey || e.button !== 0) return;
      e.preventDefault();
      pmOpen(kartu);
    });

    pmShow(0);
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

    /* --- klik gambar utama -> tampilan penuh ---------------------------
       Kotaknya dibuat di sini, bukan ditulis di HTML, karena galerinya muncul
       di tiga halaman (produk.html + modal di katalog.html & katalog-topnav.html)
       dan markup yang sama tidak perlu diulang tiga kali.

       z-index-nya di ATAS .pmodal (200), sebab di katalog galerinya sendiri
       berada di dalam modal itu. Latarnya panel terang, bukan gelap: keempat
       PNG ban itu berlatar TRANSPARAN, jadi di atas kain gelap ban yang
       memang hitam nyaris tidak kelihatan. */
    var lb = document.createElement('div');
    lb.className = 'lbox';
    lb.hidden = true;
    lb.setAttribute('role', 'dialog');
    lb.setAttribute('aria-modal', 'true');
    lb.innerHTML =
      '<div class="lbox__scrim" data-lbox-close></div>' +
      '<div class="lbox__panel">' +
        '<button type="button" class="lbox__close" data-lbox-close aria-label="Tutup">' +
          '<svg viewBox="0 0 14 14" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round">' +
          '<path d="M1 1l12 12M13 1L1 13"/></svg>' +
        '</button>' +
        '<img class="lbox__img" alt="">' +
      '</div>';
    document.body.appendChild(lb);

    var lbImg = lb.querySelector('.lbox__img');
    var lbTutup = lb.querySelector('.lbox__close');
    var lbOverflowLama = '';

    function lbBuka() {
      lbImg.src = main.currentSrc || main.src;
      lbImg.alt = main.alt || '';
      /* Batas lebar dipasang dari ukuran ASLI gambarnya: tyre-preview.png cuma
         592px, kalau dipaksa memenuhi layar hasilnya pecah. 1,5x masih terlihat
         bersih, dan 1040px menahan tyre-90.png (2192px) supaya tidak raksasa. */
      var pasangBatas = function () {
        var n = lbImg.naturalWidth || 0;
        lbImg.style.setProperty('--lbox-max', (n ? Math.round(Math.min(n * 1.5, 1040)) : 1040) + 'px');
      };
      if (lbImg.complete && lbImg.naturalWidth) pasangBatas();
      else lbImg.addEventListener('load', pasangBatas, { once: true });

      /* Nilai lama disimpan, bukan dikosongkan waktu menutup: di katalog,
         .pmodal sudah lebih dulu mengunci scroll halaman — kalau dikosongkan,
         menutup tampilan penuh ini ikut membuka kunci katalog di belakang
         modal yang masih terbuka. */
      lbOverflowLama = document.body.style.overflow;
      document.body.style.overflow = 'hidden';
      lb.hidden = false;
      lbTutup.focus();
    }

    function lbTutupkan() {
      lb.hidden = true;
      lbImg.removeAttribute('src');
      document.body.style.overflow = lbOverflowLama;
      main.focus();
    }

    lb.querySelectorAll('[data-lbox-close]').forEach(function (el) {
      el.addEventListener('click', lbTutupkan);
    });

    /* Gambar <img> bukan elemen yang bisa difokus, jadi perannya dipasang di
       sini — bukan di HTML — supaya tanpa JS dia tetap gambar biasa dan tidak
       menawarkan tombol yang tidak berfungsi. */
    main.setAttribute('role', 'button');
    main.setAttribute('tabindex', '0');
    main.setAttribute('aria-label', 'Lihat gambar ukuran penuh');
    main.classList.add('is-zoomable');
    if (main.parentElement) main.parentElement.classList.add('has-zoom');
    main.addEventListener('click', lbBuka);
    main.addEventListener('keydown', function (e) {
      if (e.key === 'Enter' || e.key === ' ' || e.key === 'Spacebar') {
        e.preventDefault();
        lbBuka();
      }
    });

    /* Fase CAPTURE + stopPropagation: penangan .pmodal juga memasang keydown
       di document (fase bubble) dan menutup modal begitu Escape ditekan.
       Tanpa ini, satu Escape menutup tampilan penuh DAN modal di belakangnya
       sekaligus. Panah kiri/kanan ikut ditahan supaya slide modal di balik
       kain tidak diam-diam bergeser. */
    document.addEventListener('keydown', function (e) {
      if (lb.hidden) return;
      if (e.key === 'Escape') { e.stopPropagation(); lbTutupkan(); }
      else if (e.key === 'ArrowLeft' || e.key === 'ArrowRight') e.stopPropagation();
    }, true);
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
      /* DUA pengamat dengan tugas berbeda — ini kuncinya supaya playback tidak
         terasa telat:

         1. PREFETCH (rootMargin) cuma MENGUNDUH, sekitar satu layar sebelum
            sectionnya sampai. Dulu unduhan ~2 MB baru dimulai pada saat yang
            sama dengan perintah putar, jadi yang terlihat pengguna adalah poster
            diam selama videonya masih dijemput — dan kalau scroll-nya cepat,
            sectionnya sudah lewat sebelum frame pertama sempat jalan.
         2. PLAY (rasio, bukan rootMargin) baru MEMUTAR ketika sectionnya memang
            sudah di layar. Rasio dipakai karena dengan rootMargin videonya mulai
            sebelum sectionnya kelihatan, dan durasinya cuma 6 detik — pada scroll
            pelan dia bisa habis sebelum sectionnya benar-benar terlihat.

         PLAY_AT diturunkan 0.35 -> 0.08: begitu sectionnya mulai mengintip di
         tepi layar, videonya sudah jalan. */
      var PLAY_AT = 0.08;
      var bgOn = new WeakSet();   /* videonya sedang "dikunjungi" atau belum */

      var jemput = function (v) {
        if (v.preload !== 'auto') { v.preload = 'auto'; v.load(); }
      };

      /* Kapan unduhannya dimulai.

         Videonya 1920x1080 / 2,1 MB: di jaringan 10 Mbps saja perlu ~1,7 detik
         sebelum frame pertama siap. Ancang-ancang "satu layar sebelum sampai"
         tidak cukup kalau scroll-nya cepat — makanya dulu terasa telat.

         Karena section ini yang KEDUA di halaman depan (hampir semua pengunjung
         yang men-scroll pasti melewatinya), unduhannya dimulai begitu halaman
         selesai sibuk memuat yang terlihat lebih dulu — bukan menunggu
         sectionnya mendekat. Waktu siapnya jadi hitungan detik lebih awal.

         Kecualinya: Save-Data dan jaringan lambat. Di sana 2 MB lebih mahal
         daripada animasinya, jadi mereka kembali ke pola lama (baru dijemput
         waktu sectionnya sudah dekat). */
      var koneksi = navigator.connection || {};
      var hematData = koneksi.saveData === true ||
                      /(^|-)2g$/.test(koneksi.effectiveType || '');

      if (!hematData) {
        var mulaiJemput = function () { bgVideos.forEach(jemput); };
        if ('requestIdleCallback' in window) {
          requestIdleCallback(mulaiJemput, { timeout: 2500 });
        } else {
          setTimeout(mulaiJemput, 1200);
        }
      } else {
        var bgPre = new IntersectionObserver(function (entries) {
          entries.forEach(function (e) {
            if (!e.isIntersecting) return;
            jemput(e.target);
            bgPre.unobserve(e.target);   /* sekali jemput, cukup */
          });
        }, { rootMargin: '0px 0px 1200px 0px' });   /* ~1,3 layar ancang-ancang */
        bgVideos.forEach(function (v) { bgPre.observe(v); });
      }

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

  /* ---------- 9. Rel kartu: carousel otomatis ----------
     Dipakai dua tempat: rel kategori (velg/ban) dan rel testimoni. Karena
     perilakunya sama persis, kodenya satu dan elemennya dioper lewat
     parameter — sekalian membereskan bentrok nama: dulu `var rail` di sini
     memakai binding yang sama dengan `var rail` milik drag testimoni di
     bagian 5, jadi begitu bagian ini jalan, drag testimoni ikut menggeser
     rel kategori.

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
  var autoRail = function (rail, period) {
    if (!rail) return;
    var RAIL_MOVE = 620;                        /* lama perpindahan satu kartu, ms */
    /* period = jarak antar geseran, dihitung dari AWAL satu geseran ke awal
       geseran berikutnya. Waktu diamnya sisa periode setelah dikurangi lama
       animasi, jadi "geser tiap 3 detik" benar-benar 3 detik, bukan 3 detik
       diam + 0,62 detik bergerak. */
    var RAIL_WAIT = Math.max(period - RAIL_MOVE, 300);
    var railDir = 1, railIdx = 0, railPaused = false, railSeen = true;
    var railAnim = null, railTimer = 0;

    var railMax = function () { return rail.scrollWidth - rail.clientWidth; };

    /* Posisi scroll supaya kartu ke-i rata kiri.

       Dulu dipakai offsetLeft, dan itu keliru: offsetLeft dihitung dari
       offsetParent, sedangkan rel-nya tidak position:relative — jadi angkanya
       ikut membawa posisi rel di dalam halaman. Di rel kategori kebetulan
       tidak kelihatan karena relnya full-bleed (posisinya ~0), tapi rel
       testimoni menjorok 24px+ ke kanan, sehingga SEMUA targetnya meleset
       sebesar itu: posisi paling kiri tidak pernah kembali ke 0 dan kartu
       pertama tinggal terpotong sedikit di tepi.

       Diukur langsung dari selisih rect kartu terhadap rect rel, ditambah
       geseran yang sudah terjadi — angka ini benar berapa pun letak relnya. */
    var railTarget = function (i) {
      var card = rail.children[i];
      if (!card) return null;
      var pad = parseFloat(getComputedStyle(rail).paddingLeft) || 0;
      var x = card.getBoundingClientRect().left - rail.getBoundingClientRect().left + rail.scrollLeft;
      return Math.min(Math.max(x - pad, 0), railMax());
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
      var now = rail.scrollLeft;
      var to = railTarget(railIdx + railDir);
      if (to === null || (railDir > 0 && to <= now + 1) || (railDir < 0 && to >= now - 1)) {
        /* Sudah mentok. Dulu di sini arahnya dibalik, railIdx dilempar ke ujung
           seberang, lalu targetnya ternyata sama dengan posisi sekarang — satu
           siklus penuh habis untuk diam di tempat sebelum benar-benar bergerak
           balik. Sekarang langsung melangkah satu kartu ke arah baru. */
        railDir = -railDir;
        to = railTarget(railIdx + railDir);
        if (to === null || Math.abs(to - now) < 1) { railQueue(); return; }
      }
      railIdx += railDir;
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

    /* scroll-snap x mandatory berkelahi dengan scrollLeft yang digerakkan
       per frame: tiap frame browser menarik posisinya balik ke titik snap
       terdekat. Selama carousel-nya aktif, snap dimatikan lewat kelas ini —
       rel tetap punya snap kalau JS mati atau pengguna minta hemat gerak. */
    rail.classList.add('is-auto');
    railQueue();
  };

  if (!(window.matchMedia && window.matchMedia('(prefers-reduced-motion: reduce)').matches)) {
    autoRail(document.querySelector('[data-velg-rail]'), 2820);
    autoRail(document.querySelector('[data-drag-rail]'), 3000);
  }

  /* ---------- 10. "Kenapa Harus Tiberman": video menyusut jadi isi tulisan ----------
     Digerakkan scroll, bukan waktu: posisi .why__track terhadap layar diubah
     jadi angka 0..1, lalu angka itu dipetakan ke tiga custom property yang
     dipakai CSS. Yang bergerak cuma transform + opacity — tidak ada properti
     yang memicu layout, jadi aman dijalankan tiap frame.

     Pembacaan (getBoundingClientRect) dan penulisan (style.setProperty) sengaja
     dikumpulkan di satu callback requestAnimationFrame: kalau dikerjakan
     langsung di handler scroll, baca-tulis bergantian memaksa browser
     menghitung ulang layout berkali-kali dalam satu frame. */
  var why = document.querySelector('[data-why]');
  if (why) {
    var whyTrack = why.querySelector('.why__track');
    var whyStage = why.querySelector('.why__stage');
    var whyCalm = window.matchMedia && window.matchMedia('(prefers-reduced-motion: reduce)').matches;
    var whySpan = why.querySelector('.why__mask span');
    var whyInner = why.querySelector('.why__mask .why__inner');

    /* Skala awal tulisan DIHITUNG, bukan dipatok. Syaratnya cuma satu: pada skala
       itu batang huruf jangkarnya harus lebih lebar dari layar, supaya di awal
       yang terlihat murni video. Angka tetap selalu salah di salah satu kondisi —
       210 cukup untuk batang "T" (~0,13em) tapi kurang untuk "a" (~0,09em), dan
       lebar layar sendiri berubah-ubah. Dihitung ulang tiap kali jangkarnya
       diukur (resize, font selesai dimuat). */
    var WHY_MARGIN = 1.25;    /* dilebihkan 25% biar tepinya tidak mepet layar */
    var WHY_TEXT_S = 210;     /* nilai awal; dihitung ulang oleh whyAnchor() */
    var WHY_VIDEO_S = 1.05;   /* video ikut menyusut sedikit, biar terasa "mundur" */
    var WHY_TINT_AT = 0.72;   /* teks merah mulai muncul di 72% perjalanan */
    var whyP = -1, whyTick = false;

    /* Mencari titik TERBAIK di dalam satu huruf untuk dijadikan pusat zoom, dan
       sekaligus skala minimum yang dibutuhkan — dengan MENGUKUR piksel glyph-nya,
       bukan menebak.

       Kenapa tidak boleh ditebak: tiap huruf beda bentuk. Batang "T" lewat tengah
       kotak glyph, "a" tidak — tengahnya justru lubang, dan sisi kanannya (yang
       sempat saya pakai, 0,84 lebar) ternyata cuma menyisakan 2px tinta ke kanan
       sehingga sebagian layar tetap putih.

       Yang dicari: kotak TERBESAR yang seluruh isinya tinta, seukuran rasio
       layar, berpusat di satu titik. Kalau layar diperbesar S kali dengan pusat
       di titik itu, yang mengisi layar persis bayangan balik kotak tersebut —
       jadi kotak yang utuh tinta = layar yang utuh video.

       Versi sebelumnya cuma mengukur DUA GARIS lewat titik itu: panjang tinta
       mendatar dan menurun. Dua garis boleh melewati layar sementara sudut-sudut
       di antaranya jatuh di luar goresan huruf — dan memang itu yang terjadi:
       terukur 13,9% bidang layar tidak tertutup tinta, muncul sebagai bidang
       putih di kiri-atas dan pita putih di tepi kanan.

       Pemeriksaan "seluruh kotak ini tinta?" dibuat O(1) memakai tabel jumlah
       kumulatif (summed-area table): sebuah kotak utuh tinta kalau jumlah
       pikselnya sama dengan luasnya. Tanpa itu, memeriksa tiap titik untuk tiap
       calon pusat terlalu mahal. Seluruh pencarian ~3 ms. */
    var whyInk = function (fontStr, ch, fpx, vw, vh) {
      var N = 120, c = document.createElement('canvas'), x = c.getContext('2d', { willReadFrequently: true });
      var probe = fontStr.replace(/\d+(\.\d+)?px/, N + 'px');
      x.font = probe;
      var m = x.measureText(ch);
      var adv = Math.ceil(m.width);
      var asc = Math.ceil(m.actualBoundingBoxAscent), desc = Math.ceil(m.actualBoundingBoxDescent);
      if (!adv || !(asc + desc)) return null;
      c.width = adv + 4; c.height = asc + desc + 4;
      x.font = probe; x.textBaseline = 'alphabetic'; x.fillStyle = '#000';
      x.fillText(ch, 2, asc + 2);
      var W = c.width, H = c.height;
      var d = x.getImageData(0, 0, W, H).data;

      /* I[y][x] = jumlah piksel tinta di seluruh kotak dari pojok kiri-atas
         sampai (x-1, y-1). Disimpan dengan satu baris & kolom nol di depan
         supaya rumus empat-sudut di bawah tidak perlu menjaga batas. */
      var I = new Int32Array((W + 1) * (H + 1));
      var px, py;
      for (py = 0; py < H; py++) {
        for (px = 0; px < W; px++) {
          I[(py + 1) * (W + 1) + px + 1] = (d[(py * W + px) * 4 + 3] > 128 ? 1 : 0)
            + I[py * (W + 1) + px + 1] + I[(py + 1) * (W + 1) + px] - I[py * (W + 1) + px];
        }
      }
      var solid = function (x0, y0, x1, y1) {
        if (x0 < 0 || y0 < 0 || x1 >= W || y1 >= H) return false;
        var n = I[(y1 + 1) * (W + 1) + x1 + 1] - I[y0 * (W + 1) + x1 + 1]
              - I[(y1 + 1) * (W + 1) + x0] + I[y0 * (W + 1) + x0];
        return n === (x1 - x0 + 1) * (y1 - y0 + 1);
      };

      var k = fpx / N;          /* 1 satuan canvas = k piksel di layar */
      var r = vw / vh;          /* kotaknya harus serasio layar: skalanya seragam */
      var best = null, cx, cy, hw, hh, nw, nh, perlu;
      for (cy = 0; cy < H; cy++) {
        for (cx = 0; cx < W; cx++) {
          if (!solid(cx, cy, cx, cy)) continue;       /* pusatnya sendiri harus tinta */
          hw = 0;
          for (;;) {
            nw = hw + 1; nh = Math.max(1, Math.round(nw / r));
            if (!solid(cx - nw, cy - nh, cx + nw, cy + nh)) break;
            hw = nw;
          }
          if (!hw) continue;
          hh = Math.max(1, Math.round(hw / r));
          /* Skala minimum: separuh layar harus muat di separuh kotak. */
          perlu = Math.max(vw / (2 * hw * k), vh / (2 * hh * k));
          if (!best || perlu < best.perlu) {
            best = { perlu: perlu, ax: (cx - 2) / adv, ay: (cy - 2) / (asc + desc) };
          }
        }
      }
      return best;
    };

    /* Titik pusat zoom diukur dari posisi huruf "a" TERAKHIR pada "Kenapa".
       Range dipakai karena itu satu-satunya cara mengukur letak satu glyph di
       dalam teks yang mengalir — ukurannya ikut clamp() dan wrap-nya beda per
       lebar layar.

       Skalanya dinolkan dulu sebelum mengukur: getBoundingClientRect memberi
       kotak SETELAH transform, jadi kalau diukur saat teks masih membesar,
       hasilnya ikut terkali skala itu. */
    var whyAnchor = function () {
      var node = whySpan.firstChild;
      if (!node) return;
      /* +5 = huruf "a" terakhir pada "Kenapa" (K-e-n-a-p-a). */
      var i = whySpan.textContent.indexOf('Kenapa');
      if (i < 0) return;
      i += 5;
      var keep = why.style.getPropertyValue('--why-ts');
      why.style.setProperty('--why-ts', '1');

      var rng = document.createRange();
      rng.setStart(node, i);
      rng.setEnd(node, i + 1);
      /* Kotaknya diukur terhadap .why__inner, bukan <span>, karena yang
         diskalakan sekarang elemen itu — transform-origin harus berada di ruang
         koordinat elemen yang ditransform. */
      var g = rng.getBoundingClientRect(), box = whyInner.getBoundingClientRect();

      if (g.width) {
        var cs = getComputedStyle(whySpan);
        var fpx = parseFloat(cs.fontSize);
        var font = cs.fontWeight + ' ' + cs.fontSize + ' ' + cs.fontFamily;
        var spot = whyInk(font, whySpan.textContent.charAt(i), fpx, window.innerWidth, window.innerHeight);

        /* Kotak Range itu tinggi BARIS, bukan tinggi huruf. Posisi garis alas
           dihitung dari metrik font supaya pecahan vertikal hasil pemindaian
           bisa dipetakan ke tempat yang benar. */
        var cv = document.createElement('canvas').getContext('2d');
        cv.font = font;
        var mm = cv.measureText(whySpan.textContent.charAt(i));
        var fbA = mm.fontBoundingBoxAscent || mm.actualBoundingBoxAscent;
        var fbD = mm.fontBoundingBoxDescent || mm.actualBoundingBoxDescent;
        var alas = (g.height - (fbA + fbD)) / 2 + fbA;          /* relatif g.top */
        var tintaAtas = alas - mm.actualBoundingBoxAscent;
        var tintaTinggi = mm.actualBoundingBoxAscent + mm.actualBoundingBoxDescent;

        var ax = spot ? spot.ax : 0.5, ay = spot ? spot.ay : 0.5;
        why.style.setProperty('--why-ox', (g.left - box.left + g.width * ax).toFixed(1) + 'px');
        why.style.setProperty('--why-oy', (g.top - box.top + tintaAtas + tintaTinggi * ay).toFixed(1) + 'px');
        if (spot) WHY_TEXT_S = Math.max(40, Math.min(spot.perlu * WHY_MARGIN, 600));
      }
      if (keep) why.style.setProperty('--why-ts', keep);
    };

    var whyApply = function () {
      whyTick = false;
      var travel = whyTrack.offsetHeight - whyStage.offsetHeight;
      var p = travel > 0 ? (-whyTrack.getBoundingClientRect().top) / travel : 1;
      p = p < 0 ? 0 : p > 1 ? 1 : p;
      if (p === whyP) return;
      whyP = p;

      /* smoothstep: mulai dan berhenti pelan, tengahnya cepat. */
      var e = p * p * (3 - 2 * p);

      /* Skalanya diinterpolasi EKSPONENSIAL (26^(1-e)), bukan linear. Mata
         menilai perubahan ukuran secara rasio, bukan selisih: dengan linear,
         separuh perjalanan masih di skala 13x lalu sisanya terjun ke 1x — itu
         yang terasa patah. Eksponensial membuat tiap langkah scroll mengecilkan
         dengan faktor yang sama, jadi lajunya terbaca rata. */
      why.style.setProperty('--why-ts', Math.pow(WHY_TEXT_S, 1 - e).toFixed(3));
      why.style.setProperty('--why-vs', (WHY_VIDEO_S - e * (WHY_VIDEO_S - 1)).toFixed(4));
      /* Teks merah menyusul di ujung, waktu ukurannya sudah hampir final. */
      why.style.setProperty('--why-t', Math.max(0, Math.min((p - WHY_TINT_AT) / (1 - WHY_TINT_AT), 1)).toFixed(3));
    };

    var whyOnScroll = function () {
      if (!whyTick) { whyTick = true; requestAnimationFrame(whyApply); }
    };

    if (whyCalm) {
      /* Hemat gerak: langsung keadaan akhir, tanpa listener sama sekali. */
      why.style.setProperty('--why-ts', '1');
      why.style.setProperty('--why-vs', '1');
      why.style.setProperty('--why-t', '1');
    } else {
      whyAnchor();
      window.addEventListener('scroll', whyOnScroll, { passive: true });
      window.addEventListener('resize', function () { whyAnchor(); whyApply(); });
      /* Font web baru mengubah lebar glyph setelah dimuat — titik pusatnya
         diukur ulang begitu font siap. */
      if (document.fonts && document.fonts.ready) { document.fonts.ready.then(whyAnchor); }
      whyApply();
    }
  }

  /* ---------- 11. Intro: video pembuka + serah-terima ke hero ----------
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

    /* Tombol "Lewati" sudah dibuang; Escape dipertahankan sebagai jalan
       keluar — overlay selayar penuh yang sama sekali tidak bisa ditutup itu
       menjebak orang kalau videonya macet. */
    document.addEventListener('keydown', function (e) {
      if (e.key === 'Escape') closeIntro();
    });

    if (video) {
      var bar = intro.querySelector('[data-intro-bar]');
      var fill = intro.querySelector('[data-intro-bar-fill]');
      var ghost = intro.querySelector('[data-intro-wordmark]');

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
