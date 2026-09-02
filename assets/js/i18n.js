/* =============================================================
   Tiberman — tema (terang/gelap) + alih bahasa ID / EN / ZH.

   Kamus dikunci memakai teks Indonesia yang ada di HTML, jadi
   markup tidak perlu ditandai satu per satu. String yang tidak
   ada di kamus otomatis tetap tampil apa adanya.
   ============================================================= */
window.TIBERMAN_I18N = (function () {
  'use strict';

  var STORE_LANG = 'tbm-lang';
  var STORE_THEME = 'tbm-theme';

  var EN = {
    /* --- navigasi --- */
    'Truk & Bus': 'Truck & Bus',
    'Velg & Tube': 'Rims & Tubes',
    'Traktor': 'Tractor',
    'Loader-Grader': 'Loader & Grader',
    'Buka menu': 'Open menu',

    /* --- hero & beranda --- */
    'siap melayani Anda lebih dekat dengan': 'ready to serve you closer through',
    '15 SuperArea yang tersebar di': '15 SuperAreas spread across',
    'seluruh Indonesia': 'the whole of Indonesia',
    'Importir Ban Truk & Alat Berat': 'Importer of Truck & Heavy Equipment Tyres',
    'TERPERCAYA': 'YOU CAN TRUST',
    'Lengkapi kebutuhan': 'Everything you need',
    'Anda di satu tempat.': 'in one place.',
    'Tak hanya ban, kami juga menyediakan aksesoris pendukung seperti velg, ban dalam, flap, marset, dan O-ring dengan stok siap kirim.':
      'Beyond tyres we also supply supporting accessories such as rims, inner tubes, flaps, mudguards and O-rings, all in stock and ready to ship.',
    'VELG': 'RIMS',

    /* --- kenapa tiberman --- */
    'Kenapa Harus Tiberman ?': 'Why Tiberman?',
    'Stok Aman': 'Stock Secured',
    'Memiliki 2 Pusat Logistik': 'Two bonded logistics',
    'Berikat (PLB) sendiri': 'centres (PLB) of our own',
    'Tiberman Group didukung oleh 2 Pusat Logistik Berikat (PLB) yang dikelola':
      'Tiberman Group is backed by two Bonded Logistics Centres (PLB) operated by',
    '. yang berlokasi di': '. located in',
    'dan': 'and',
    'luas :': 'area :',
    'kapasitas :': 'capacity :',
    '150 kontainer': '150 containers',
    '250 kontainer': '250 containers',
    'Pengiriman Aman': 'Delivery Secured',
    'Aman sampai tujuan dengan': 'Safe to its destination with',
    'armada delivery sendiri': 'our own delivery fleet',
    'Tiberman Group didukung oleh layanan distribusi yang dikelola oleh PT Hantar Lintas Nusantara (Halilintar) memastikan setiap pengiriman aman hingga sampai ke tangan anda.':
      'Tiberman Group is backed by a distribution service run by PT Hantar Lintas Nusantara (Halilintar), making sure every shipment arrives safely in your hands.',
    'Layanan jaminan perbaikan kerusakan ban, gratis sesuai dengan ketentuan yang berlaku.':
      'A tyre damage repair guarantee, free of charge subject to the applicable terms.',

    /* --- testimoni --- */
    'Testimoni': 'Customer',
    'Pelanggan': 'Testimonials',
    'Berikut beberapa testimoni dari pelanggan yang telah menggunakan produk ban kami.':
      'Here is what some of the customers using our tyres have to say.',
    'Bannya sangat bagus, sampai saya pengen beli lagi walau gatau buat apa, terimakasih Tiberman!':
      'The tyres are so good I want to buy more even when I have nothing to fit them to. Thank you, Tiberman!',
    'Mbak2 tambang': 'Mining crew',
    'Stok selalu ada dan pengiriman cepat. Armada kami tidak pernah menunggu ban lagi.':
      'Stock is always there and delivery is fast. Our fleet never waits for tyres anymore.',
    'Layanan free tyre repair-nya benar-benar terpakai. Support after sales-nya cepat tanggap.':
      'The free tyre repair service really does get used. After sales support responds quickly.',
    'Owner Dump Truck': 'Dump Truck Owner',
    'Sidewall-nya kuat untuk jalur tambang. Umur pakainya jauh lebih panjang dari ban sebelumnya.':
      'The sidewalls hold up on mining routes. They last far longer than our previous tyres.',

    /* --- berita --- */
    'Kabar terbaru dan wawasan seputar ban truk & alat berat':
      'Latest news and insight on truck & heavy equipment tyres',
    'Mengukir Sejarah di Industri Alat Berat : Tiberman Sabet Dua Rekor MURI di Tiberman Expo 2026':
      'Making History in Heavy Equipment: Tiberman Claims Two MURI Records at Tiberman Expo 2026',
    'Industri ban komersial dan alat berat di Indonesia baru saja menyaksikan sebuah terobosan bersejarah. PT Tiga Berlian Mandiri (Tiberman) berhasil menorehkan prestasi gemilang tingkat nasional dengan memecahkan dua Rekor MURI sekaligus.':
      'Indonesia’s commercial and heavy equipment tyre industry has just witnessed a historic breakthrough. PT Tiga Berlian Mandiri (Tiberman) earned national recognition by breaking two MURI Records at once.',
    'Perbedaan Geografis Tambang di Indonesia dan Strategi Pemilihan Ban Alat Berat':
      'Indonesia’s Varied Mining Terrain and How to Choose the Right OTR Tyre',
    'Indonesia dikenal sebagai salah satu raksasa komoditas global. Namun memindahkan material tambang dari perut bumi ke pelabuhan bukanlah perkara mudah, dan kondisi geografis tiap lokasi menentukan spesifikasi ban OTR yang dipakai.':
      'Indonesia is known as one of the world’s commodity giants. Yet moving mined material from the ground to the port is no simple task, and the terrain at each site dictates the OTR tyre specification required.',
    'Dump Truck: Fungsi, Jenis, Komponen, dan Tips Memilih Ban yang Tepat untuk Operasional':
      'Dump Trucks: Function, Types, Components and Tips for Picking the Right Tyre',
    'Dump truck punya peran penting dalam berbagai aktivitas industri karena dirancang untuk mengangkut sekaligus menurunkan material dalam jumlah besar. Simak jenis, komponen, dan cara memilih bannya.':
      'Dump trucks play a key role across industry because they are built to haul and unload large volumes of material. Here are the types, components and how to choose their tyres.',

    /* --- footer --- */
    'Kami adalah One Stop Supplier ban alat berat yang telah dipercaya oleh ribuan customer di seluruh Indonesia. Sejak berdiri pada tahun 2008, jaringan distribusi kami telah tersebar di berbagai titik Super Area yang dapat menjangkau hingga pelosok negeri.':
      'We are a one stop supplier of heavy equipment tyres, trusted by thousands of customers across Indonesia. Since 2008 our distribution network has spread across Super Area points, reaching even the remotest regions.',
    'Hubungi Kami': 'Contact Us',
    'Navigasi': 'Navigation',
    'Berita & Artikel': 'News & Articles',
    'Karir': 'Careers',
    'Semua Produk': 'All Products',

    /* --- halaman produk --- */
    'Dirancang khusus untuk memberikan cengkraman maksimal tanpa kompromi. Dengan telapak yang lebih tebal, ban ini nggak cuma tangguh, tapi juga punya umur pakai yang lebih panjang.':
      'Engineered for maximum grip with no compromise. With a thicker tread this tyre is not only tough, it also lasts considerably longer.',
    'Kenapa Harus Ban Ini ?': 'Why This Tyre?',
    'Kuat': 'Strength',
    'Konstruksi all-steel radial dengan bahu ban lebih tebal, tahan benturan batu dan beban lateral di jalur tambang.':
      'All-steel radial construction with thicker shoulders, resisting rock impact and lateral loads on mining routes.',
    'Telapak': 'Thick',
    'Tebal': 'Tread',
    'Kedalaman tapak 25.5 mm dengan blok besar memberi traksi maksimal dan umur pakai yang jauh lebih panjang.':
      'A 25.5 mm tread depth with large blocks delivers maximum traction and a far longer service life.',
    'Muatan Berat': 'Heavy Loads',
    'Muatan berat': 'Heavy loads',
    'Medan off-road': 'Off-road terrain',
    'E-Katalog': 'E-Catalogue',
    'Spesifikasi': 'Specifications',
    ': 10 km/jam': ': 10 km/h',
    'Shoppe': 'Shopee',

    /* --- katalog --- */
    'Lagi cari ban apa?': 'Which tyre are you after?',
    'Telusuri berdasarkan unit': 'Browse by unit',
    'Temukan Produk Promo': 'Find Promo Products',
    'Klik untuk melihat produk promo kita': 'Tap to see our promo products',
    'Lihat Testimoni Video': 'Watch Video Testimonials',
    'Klik untuk melihat testimoni video dari customer kita': 'Tap to watch video testimonials from our customers',
    'Cari ban': 'Search tyres',
    'Jenis Unit': 'Unit Type',
    'Ukuran Ban': 'Tyre Size',
    'Ukuran': 'Size',
    'compatible for :': 'compatible for :',
    'Dumptruck': 'Dump truck',
    'Truk & Bus': 'Truck & Bus',
    'Rigid Dumptruck': 'Rigid dump truck',
    'Wheel Loader': 'Wheel loader',
    'Motor Grader': 'Motor grader',
    'Traktor Roda 4': '4-wheel tractor',
    'Forklift 3 Ton': '3-tonne forklift',
    'Forklift 5 Ton': '5-tonne forklift',
    'Forklift Solid Tyre': 'Solid-tyre forklift',
    'Light Truck': 'Light truck',
    'Produk tidak ditemukan. Coba kata kunci atau filter lain.': 'No products found. Try another keyword or filter.',
    'Dump truck di area tambang': 'Dump truck at a mining site'
  };

  var ZH = {
    /* --- navigasi --- */
    'Products': '产品',
    'Truk & Bus': '卡车与客车',
    'Mining Truck': '矿用卡车',
    'Loader-Grader': '装载机与平地机',
    'Traktor': '拖拉机',
    'Forklift': '叉车',
    'Velg & Tube': '轮辋与内胎',
    'News': '新闻',
    'SuperArea': '服务网点',
    'Contact Us': '联系我们',
    'Buka menu': '打开菜单',

    /* --- hero & beranda --- */
    'siap melayani Anda lebih dekat dengan': '为您提供更贴近的服务',
    '15 SuperArea yang tersebar di': '15 个服务网点遍布',
    'seluruh Indonesia': '印尼全国',
    'Check it !': '查看详情',
    'Importir Ban Truk & Alat Berat': '卡车与工程机械轮胎进口商',
    'TERPERCAYA': '值得信赖',
    'Truck & Bus': '卡车与客车',
    'Tractor': '拖拉机',
    'Lengkapi kebutuhan': '一站式满足',
    'Anda di satu tempat.': '您的全部需求。',
    'Tak hanya ban, kami juga menyediakan aksesoris pendukung seperti velg, ban dalam, flap, marset, dan O-ring dengan stok siap kirim.':
      '除轮胎外，我们还供应轮辋、内胎、垫带、挡泥板和 O 型圈等配件，现货充足，随时发运。',
    'VELG': '轮辋',
    'Heavy-Duty': '重载型',
    'Light Truck': '轻型卡车',

    /* --- kenapa tiberman --- */
    'Kenapa Harus Tiberman ?': '为什么选择 Tiberman？',
    'Stok Aman': '库存无忧',
    'Memiliki 2 Pusat Logistik': '自有 2 座',
    'Berikat (PLB) sendiri': '保税物流中心（PLB）',
    'Tiberman Group didukung oleh 2 Pusat Logistik Berikat (PLB) yang dikelola':
      'Tiberman Group 拥有 2 座保税物流中心（PLB），由',
    '. yang berlokasi di': '运营，分别位于',
    'dan': '和',
    'luas :': '面积：',
    'kapasitas :': '容量：',
    '150 kontainer': '150 个集装箱',
    '250 kontainer': '250 个集装箱',
    'Pengiriman Aman': '配送无忧',
    'Aman sampai tujuan dengan': '由自有车队',
    'armada delivery sendiri': '安全送达目的地',
    'Tiberman Group didukung oleh layanan distribusi yang dikelola oleh PT Hantar Lintas Nusantara (Halilintar) memastikan setiap pengiriman aman hingga sampai ke tangan anda.':
      'Tiberman Group 的配送服务由 PT Hantar Lintas Nusantara（Halilintar）运营，确保每一批货物安全送达您手中。',
    'After Sales': '售后服务',
    'Free Tyre Repair': '免费轮胎维修',
    'Layanan jaminan perbaikan kerusakan ban, gratis sesuai dengan ketentuan yang berlaku.':
      '轮胎损坏维修保障服务，符合相关条款者免费。',

    /* --- testimoni --- */
    'Testimoni': '客户',
    'Pelanggan': '评价',
    'Berikut beberapa testimoni dari pelanggan yang telah menggunakan produk ban kami.':
      '以下是部分已使用我们轮胎产品的客户评价。',
    'Bannya sangat bagus, sampai saya pengen beli lagi walau gatau buat apa, terimakasih Tiberman!':
      '轮胎非常好，好到我就算用不上也还想再买，谢谢 Tiberman！',
    'Mbak2 tambang': '矿区工作人员',
    'Stok selalu ada dan pengiriman cepat. Armada kami tidak pernah menunggu ban lagi.':
      '库存充足，发货迅速。我们的车队再也不用等轮胎了。',
    'Fleet Manager': '车队经理',
    'Layanan free tyre repair-nya benar-benar terpakai. Support after sales-nya cepat tanggap.':
      '免费轮胎维修服务确实用得上，售后响应也很快。',
    'Owner Dump Truck': '自卸车车主',
    'Sidewall-nya kuat untuk jalur tambang. Umur pakainya jauh lebih panjang dari ban sebelumnya.':
      '胎侧足以应对矿区路况，使用寿命比以前的轮胎长很多。',
    'Supervisor Hauling': '运输主管',

    /* --- faq (sumbernya memang berbahasa inggris) --- */
    'Do you have questions?': '还有疑问吗？',
    'What is your return policy?': '你们的退货政策是什么？',
    'We offer a 15-day return window for a full refund or exchange on unused items. Returns must include original packaging and proof of purchase for processing.':
      '未使用的商品可在 15 天内退货，全额退款或换货。退货须包含原包装及购买凭证方可受理。',
    'Do you offer international shipping?': '你们提供国际配送吗？',
    'Yes, we offer international shipping to select countries. Please refer to our shipping policies or contact customer support for specific details regarding international shipments.':
      '是的，我们向部分国家提供国际配送。具体事宜请参阅配送政策或联系客服。',
    'What if I receive a damaged or defective product?': '收到破损或有瑕疵的商品怎么办？',
    'If you receive a damaged or defective product, please contact our customer support team immediately for assistance with returns or exchanges.':
      '若收到破损或有瑕疵的商品，请立即联系客服团队协助办理退换。',
    'Are the product colors on the website accurate?': '网站上的产品颜色准确吗？',
    'Our website strives to accurately depict product colors, but slight variations may occur due to screen settings. We recommend referring to product descriptions for additional details.':
      '我们力求准确呈现产品颜色，但因屏幕设置不同可能略有差异，建议同时参阅产品说明。',
    'How do I contact customer support?': '如何联系客服？',
    'My question is not here.': '这里没有我的问题。',
    'Connect us': '联系我们',

    /* --- berita --- */
    'Kabar terbaru dan wawasan seputar ban truk & alat berat': '卡车与工程机械轮胎的最新资讯与洞察',
    'Mengukir Sejarah di Industri Alat Berat : Tiberman Sabet Dua Rekor MURI di Tiberman Expo 2026':
      '书写工程机械行业新篇：Tiberman 在 2026 年 Tiberman 博览会上斩获两项 MURI 纪录',
    'Industri ban komersial dan alat berat di Indonesia baru saja menyaksikan sebuah terobosan bersejarah. PT Tiga Berlian Mandiri (Tiberman) berhasil menorehkan prestasi gemilang tingkat nasional dengan memecahkan dua Rekor MURI sekaligus.':
      '印尼商用与工程机械轮胎行业刚刚见证了一次历史性突破。PT Tiga Berlian Mandiri（Tiberman）一举打破两项 MURI 纪录，取得全国级佳绩。',
    'Perbedaan Geografis Tambang di Indonesia dan Strategi Pemilihan Ban Alat Berat':
      '印尼矿区地理差异与工程机械轮胎选型策略',
    'Indonesia dikenal sebagai salah satu raksasa komoditas global. Namun memindahkan material tambang dari perut bumi ke pelabuhan bukanlah perkara mudah, dan kondisi geografis tiap lokasi menentukan spesifikasi ban OTR yang dipakai.':
      '印尼是全球大宗商品巨头之一。但把矿产从地下运到港口并非易事，各地的地理条件决定了所需 OTR 轮胎的规格。',
    'Dump Truck: Fungsi, Jenis, Komponen, dan Tips Memilih Ban yang Tepat untuk Operasional':
      '自卸车：功能、类型、部件，以及如何选对轮胎',
    'Dump truck punya peran penting dalam berbagai aktivitas industri karena dirancang untuk mengangkut sekaligus menurunkan material dalam jumlah besar. Simak jenis, komponen, dan cara memilih bannya.':
      '自卸车能够大批量装运并卸载物料，在各类工业作业中举足轻重。本文介绍其类型、部件及轮胎选择方法。',

    /* --- footer --- */
    'Kami adalah One Stop Supplier ban alat berat yang telah dipercaya oleh ribuan customer di seluruh Indonesia. Sejak berdiri pada tahun 2008, jaringan distribusi kami telah tersebar di berbagai titik Super Area yang dapat menjangkau hingga pelosok negeri.':
      '我们是工程机械轮胎的一站式供应商，深受印尼各地数千家客户信赖。自 2008 年成立以来，我们的分销网络已覆盖各个服务网点，触达全国偏远地区。',
    'Hubungi Kami': '联系我们',
    'Marketplace': '电商平台',
    'Super Area': '服务网点',
    'Surabaya ( Head Office )': '泗水（总部）',
    'Navigasi': '导航',
    'Berita & Artikel': '新闻与文章',
    'Karir': '招聘',
    'Semua Produk': '全部产品',
    'After Sales Service': '售后服务',

    /* --- halaman produk --- */
    'Dirancang khusus untuk memberikan cengkraman maksimal tanpa kompromi. Dengan telapak yang lebih tebal, ban ini nggak cuma tangguh, tapi juga punya umur pakai yang lebih panjang.':
      '专为极致抓地力而设计，毫不妥协。更厚的胎面不仅带来强悍性能，也带来更长的使用寿命。',
    'Kenapa Harus Ban Ini ?': '为什么选择这条轮胎？',
    'Sidewall': '胎侧',
    'Kuat': '强韧',
    'Konstruksi all-steel radial dengan bahu ban lebih tebal, tahan benturan batu dan beban lateral di jalur tambang.':
      '全钢子午线结构，胎肩更厚，可抵御矿区路面的石击与侧向负荷。',
    'Telapak': '胎面',
    'Tebal': '厚实',
    'Kedalaman tapak 25.5 mm dengan blok besar memberi traksi maksimal dan umur pakai yang jauh lebih panjang.':
      '25.5 毫米胎面深度搭配大块花纹，提供极致牵引力与更长使用寿命。',
    'Perfect Pair For': '最佳搭档',
    'Dump Truck': '自卸车',
    'Off-Road': '非公路路况',
    'Muatan Berat': '重载运输',
    'E-Katalog': '电子样册',
    'Flash Card': '产品卡',
    'Spesifikasi': '规格参数',
    'Ply Rating': '层级',
    'Overall Diameter': '总直径',
    'Tread Depth': '胎面深度',
    'Max Load': '最大负荷',
    'Standard Rim': '标准轮辋',
    'Max Speed': '最高速度',
    ': 10 km/jam': '： 10 km/h',
    'Section Width': '断面宽度',
    'Pressure': '气压',
    'Available Size :': '可选规格：',
    'Contact us :': '联系我们：',
    'Whatsapp': 'WhatsApp',
    'Shoppe': 'Shopee',

    /* --- katalog --- */
    'Lagi cari ban apa?': '您在找什么轮胎？',
    'Telusuri berdasarkan unit': '按车型浏览',
    'Temukan Produk Promo': '查看促销产品',
    'Klik untuk melihat produk promo kita': '点击查看我们的促销产品',
    'Lihat Testimoni Video': '观看视频评价',
    'Klik untuk melihat testimoni video dari customer kita': '点击观看客户视频评价',
    'Search': '搜索',
    'Cari ban': '搜索轮胎',
    'Jenis Unit': '车型',
    'Ukuran Ban': '轮胎规格',
    'Ukuran': '规格',
    'compatible for :': '适用于：',
    'Dumptruck': '自卸车',
    'Rigid Dumptruck': '刚性自卸车',
    'Wheel Loader': '轮式装载机',
    'Motor Grader': '平地机',
    'Traktor Roda 4': '四轮拖拉机',
    'Forklift 3 Ton': '3 吨叉车',
    'Forklift 5 Ton': '5 吨叉车',
    'Forklift Solid Tyre': '实心胎叉车',
    'Produk tidak ditemukan. Coba kata kunci atau filter lain.': '未找到产品，请更换关键词或筛选条件。',
    'Dump truck di area tambang': '矿区中的自卸车'
  };

  var DICT = { id: {}, en: EN, zh: ZH };
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

  /* ---------- tema ---------- */
  function currentTheme() {
    return document.documentElement.getAttribute('data-theme') === 'dark' ? 'dark' : 'light';
  }

  function setTheme(next) {
    if (next === 'dark') document.documentElement.setAttribute('data-theme', 'dark');
    else document.documentElement.removeAttribute('data-theme');
    try { localStorage.setItem(STORE_THEME, next); } catch (e) {}
    syncControls();
  }

  /* ---------- kontrol di navbar ---------- */
  var LABEL = { id: 'ID', en: 'EN', zh: '中文' };
  var TIP = {
    id: { dark: 'Aktifkan tema gelap', light: 'Aktifkan tema terang' },
    en: { dark: 'Switch to dark theme', light: 'Switch to light theme' },
    zh: { dark: '切换到深色主题', light: '切换到浅色主题' }
  };

  function syncControls() {
    var t = currentTheme();
    var tip = TIP[lang] || TIP.id;
    Array.prototype.forEach.call(document.querySelectorAll('[data-theme-toggle]'), function (b) {
      b.setAttribute('aria-pressed', t === 'dark' ? 'true' : 'false');
      b.setAttribute('aria-label', t === 'dark' ? tip.light : tip.dark);
    });
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
      var toggle = t.closest && t.closest('[data-theme-toggle]');
      if (toggle) { setTheme(currentTheme() === 'dark' ? 'light' : 'dark'); return; }

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
    setTheme: setTheme,
    getLang: function () { return lang; }
  };
})();
