# Tiberman — HTML dari desain PDF

Hasil implementasi HTML/CSS/JS dari `tiberman_removed.pdf` (4 artboard, lebar 1440px).
Semua gambar diambil dari folder aset `F:\sasa\aset tiberman` lalu dikompres ke `assets/img`.

## Halaman

| File | Artboard di PDF | Isi |
|---|---|---|
| `index.html` | Halaman 1 (1440 × 11051) | Homepage: hero, 15 SuperArea, kategori unit, aksesoris + VELG, "Kenapa Harus Tiberman" (Stok Aman / Pengiriman Aman / After Sales), testimoni, FAQ, News |
| `katalog.html` | Halaman 2 (1440 × 966) | Katalog dengan sidebar kiri: search, daftar unit, box promo, banner, chip ukuran, grid produk 5 kolom |
| `katalog-topnav.html` | Halaman 3 (1440 × 966) | Varian katalog tanpa sidebar — kategori unit di navbar atas |
| `produk.html` | Halaman 4 (1440 × 4096) | Halaman produk Uninest TiberMAX 800: hero hitam, bento "Kenapa Harus Ban Ini", "Perfect Pair For", galeri + spesifikasi + available size + contact |

## Struktur

```
web/
├── index.html, katalog.html, katalog-topnav.html, produk.html
└── assets/
    ├── css/style.css      design system (token warna, komponen, responsive)
    ├── js/main.js         nav mobile, reveal on scroll, accordion FAQ, coverflow
    │                      After Sales, drag testimoni, filter katalog, galeri PDP
    ├── js/products.js     data contoh produk katalog (per unit → per ukuran)
    └── img/               21 gambar hasil kompresi (webp untuk foto, png untuk logo)
```

## Warna (di-sample langsung dari PDF)

| Token | Nilai | Dipakai untuk |
|---|---|---|
| `--blue` | `#0071e3` | tombol "Check it !", chip aktif, item unit aktif |
| `--ink` | `#1d1d1f` | navbar, background katalog, footer |
| `--ink-2` | `#121214` | sidebar katalog |
| `--red` | `#ef3936` | wordmark TIBERMAX |
| `--sky-1/2/card` | `#dcedf7` / `#eef3f7` / `#d6ebf7` | section aksesoris & kartu VELG |
| `--grey-50/100/200` | `#f5f5f7` / `#f2f2f2` / `#ebebeb` | kartu, panel FAQ, kartu testimoni |
| `--spec` | `#6c6f89` | heading "Spesifikasi", "Available Size", "Contact us" |

Font: **Plus Jakarta Sans** (Google Fonts) sebagai padanan terdekat dari font geometris di desain.

## Pemetaan aset

| File di `assets/img` | Sumber di folder aset | Dipakai di |
|---|---|---|
| `hero-warehouse.webp` | ChatGPT Image Jul 8, 2026 | hero homepage |
| `earth.webp` | earth.png | section 15 SuperArea (15 pin lokasi) |
| `warehouse-dark.webp` | vlcsnap-2025-03-03 | background "Importir Ban Truk & Alat Berat" |
| `tires-strip.webp` | ban banyak.png | strip ban antar-section |
| `accessories.webp` | Ban Ban.png | section aksesoris + ilustrasi FAQ |
| `velg-heavy.webp` / `velg-light.webp` | velg 4.png / DSC03206.png | kartu VELG Heavy-Duty & Light Truck |
| `plb-stock.webp` | vlcsnap-2025-11-04 | kartu PT Fie Min Logistics |
| `plb-mojokerto.webp` / `plb-gresik.webp` | VLC ss00019 / VLC ss00217 | kartu PLB Mojokerto & Gresik |
| `delivery-forklift.webp` | 20260306_111616.jpg | section Pengiriman Aman |
| `tire-hero-dark.webp` | ChatGPT Image Aug 21 09_53_34 | hero halaman produk |
| `tire-white.webp` | ChatGPT Image Aug 21 09_53_18 | kartu katalog & galeri produk |
| `tire-tread.webp` | ChatGPT Image Jul 30 | bento "Telapak Tebal", galeri |
| `tire-554.webp` | Tibermax 554 12.00R24 2.png | bento "Sidewall Kuat", galeri utama |
| `dumptruck.webp` | ChatGPT Image Jun 9 | banner katalog, kartu "Dump Truck" |
| `logo-white/black/red.png` | TBM putih / LOGO TIBERMAN - HITAM / LOGO TIBERMAN | navbar, hero, footer |
| `logo-fiemin.png`, `logo-halilintar.png` | fml logo.png, logo halilintar FIX.png | kartu Stok Aman & Pengiriman Aman |

## Catatan penyesuaian dari desain

- **Footer**: artboard PDF berakhir setelah section News tanpa footer. Ditambahkan footer minimal (logo + menu + copyright) supar setiap halaman punya penutup.
- **Aset yang tidak ada di folder**: foto teknisi carousel After Sales, avatar testimoni, thumbnail artikel News, dan ilustrasi truk+maskot Halilintar. Diganti dengan foto ban/gudang yang tersedia, avatar inisial, dan thumbnail gradien.
- **Chip ukuran katalog** dibuat dinamis mengikuti unit yang dipilih (di desain masih placeholder "Ukuran Ban").
- **Data produk** di `products.js` adalah contoh; ganti dengan data asli atau sambungkan ke backend.
- Teks FAQ dan News masih memakai placeholder bahasa Inggris seperti di desain.

## Menjalankan

Buka `index.html` langsung di browser, atau jalankan static server dari folder `web/`:

```bash
npx serve "F:/sasa/aset tiberman/web"
```
