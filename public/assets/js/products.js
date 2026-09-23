/* =============================================================
   Data produk katalog (contoh) — dipakai oleh katalog.blade.php.
   Struktur:
   unit -> [ { size, items:[ {name, compat, img} ] } ]

   Kunci unit = kunci 'units' di config/catalog.php, dan label ukuran
   yang ada di 'sizes' di sana otomatis mendapat URL toko lama
   (/kategori-produk/ukuran-ban/...). Nama & ukurannya diambil dari
   produk asli tiberman.com supaya filter merk & ukuran ada isinya.
   ============================================================= */
window.TIBERMAN_PRODUCTS = (function () {
  /* Path absolut: katalog sekarang juga tampil di URL bertingkat
     (/kategori-produk/ban-truk/...), path relatif akan salah alamat.
     PNG berlatar transparan supaya kartu ikut tema terang/gelap. */
  var W = '/assets/img/tire-transparent-soft.png';

  /* helper: bikin n kartu dengan nama & kompatibilitas yang sama */
  function row(n, name, compat, img) {
    var out = [];
    for (var i = 0; i < n; i++) out.push({ name: name, compat: compat, img: img || W });
    return out;
  }

  return {
    'truk-bus': [
      { size: '7.50-16', items: row(2, 'HENGLI - DR908', 'Truk Canter').concat(row(2, 'HENGLI - DR930', 'Truk Canter')) },
      { size: '10.00R20', items: row(3, 'DURUN - YTH3', 'Truk Medium').concat(row(2, 'HENGLI - TIBERMAX DR930', 'Truk Medium')) },
      { size: '11.00-20', items: row(3, 'UNINEST - TIBERMAX 554', 'Dumptruck').concat(row(2, 'WINGOOD - WG128', 'Truk Tronton')) },
      { size: '11R22.5', items: row(4, 'HENGLI - DR930', 'Bus &amp; Trailer') },
      { size: '12.00-20', items: row(3, 'UNINEST - TIBERMAX 800', 'Truk &amp; Bus').concat(row(2, 'BONTYRE - BT906', 'Truk Berat')) }
    ],
    'mining-truck': [
      { size: '14.00R20', items: row(4, 'AEOLUS - AE33', 'Articulated Dump Truck') },
      { size: '21.00-35', items: row(3, 'TUTRIC - TUE402 PRO', 'Rigid Dump Truck') },
      { size: '24.00-35', items: row(3, 'AEOLUS - AE419', 'Rigid Dump Truck').concat(row(2, 'TUTRIC - TUE402', 'Rigid Dump Truck')) },
      { size: '27.00-49', items: row(3, 'TIANLI - TUE400', 'HD Rigid Dump Truck').concat(row(2, 'AEOLUS - AE46', 'HD Rigid Dump Truck')) },
      { size: '33.00-51', items: row(3, 'TUTRIC - TUE402', 'HD Rigid Dump Truck') }
    ],
    'loader': [
      { size: '20.5-25', items: row(4, 'UNINEST - L3 LOADER', 'Wheel Loader') },
      { size: '23.5-25', items: row(3, 'ECED - GCA7', 'Wheel Loader').concat(row(2, 'TIANLI - T318', 'Wheel Loader')) },
      { size: '29.5-25', items: row(3, 'TUTRIC - TUL400', 'Wheel Loader').concat(row(2, 'AEOLUS - AE47', 'Wheel Loader')) },
      { size: '45/65-45', items: row(2, 'TUTRIC - TUL510', 'Wheel Loader Besar') }
    ],
    'grader': [
      { size: '13.00-24', items: row(3, 'UNINEST - G2 GRADER', 'Motor Grader') },
      { size: '14.00-24', items: row(4, 'UNINEST - G2 GRADER', 'Motor Grader') },
      { size: '17.5-25', items: row(3, 'UNINEST - G2 GRADER', 'Motor Grader') }
    ],
    'traktor': [
      { size: '18.4-24', items: row(3, 'TUTRIC - SLGII', 'Traktor Roda 4') },
      { size: '23.1-26', items: row(3, 'UNINEST - R1 TRAKTOR', 'Traktor Roda 4') }
    ],
    'forklift': [
      { size: '7.00-12', items: row(4, 'UNINEST - ROBUST SOLID TYRE', 'Forklift 3 Ton') },
      { size: '8.25-15', items: row(4, 'UNINEST - ROBUST SOLID TYRE', 'Forklift 5 Ton') },
      { size: '28x9-15', items: row(3, 'UNINEST - ROBUST SOLID TYRE', 'Forklift Solid Tyre') }
    ],
    'compactor': [
      { size: '23.1-26', items: row(3, 'UNINEST - VIBRO', 'Vibro Roller') }
    ],
    'industri': [
      { size: '16.00-25', items: row(3, 'AEOLUS - AE401', 'Mobile Crane') },
      { size: '18.00-25', items: row(3, 'UNINEST - E4 PORT', 'Reach Stacker') }
    ],
    'velg-tube': [
      { size: 'DW20 - 11.00', items: row(5, 'VELG STEELKING', 'Dumptruck', '/assets/img/velg-heavy.webp') },
      { size: 'DW25 - 20.5', items: row(5, 'VELG LIGHT TRUCK', 'Light Truck', '/assets/img/velg-light.webp') },
      { size: 'Ban Dalam 11.00R20', items: row(5, 'TUBE &amp; FLAP SET', 'Truk &amp; Bus') }
    ]
  };
})();
