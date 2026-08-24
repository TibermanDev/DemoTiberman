/* =============================================================
   Data produk katalog (contoh) — dipakai oleh katalog.html
   dan katalog-topnav.html. Struktur:
   unit -> [ { size, items:[ {name, compat, img} ] } ]
   ============================================================= */
window.TIBERMAN_PRODUCTS = (function () {
  var W = 'assets/img/tire-white.webp';
  var D = 'assets/img/tire-554.webp';

  /* helper: bikin n kartu dengan nama & kompatibilitas yang sama */
  function row(n, name, compat, img) {
    var out = [];
    for (var i = 0; i < n; i++) out.push({ name: name, compat: compat, img: img || W });
    return out;
  }

  return {
    'truk-bus': [
      { size: '11.00R20', items: row(5, 'UNINEST - TIBERMAX 554', 'Dumptruck') },
      { size: '11.00R24', items: row(5, 'UNINEST - TIBERMAX 554', 'Dumptruck', D) },
      { size: '12.00R20', items: row(5, 'UNINEST - TIBERMAX 800', 'Truk &amp; Bus') }
    ],
    'mining-truck': [
      { size: '12.00R24', items: row(5, 'UNINEST - TIBERMAX 800', 'Mining Truck', D) },
      { size: '14.00R25', items: row(5, 'UNINEST - TIBERMAX 900', 'Mining Truck') },
      { size: '18.00R33', items: row(5, 'TUTRIC - MINEPRO', 'Rigid Dumptruck') }
    ],
    'loader-grader': [
      { size: '17.5R25', items: row(5, 'AEOLUS - AL37', 'Wheel Loader') },
      { size: '20.5R25', items: row(5, 'AEOLUS - AL37', 'Wheel Loader', D) },
      { size: '23.5R25', items: row(5, 'TIBERPLUS - GRD500', 'Motor Grader') },
      { size: '29.5R25', items: row(5, 'TUTRIC - LDR700', 'Wheel Loader') }
    ],
    'traktor': [
      { size: '12.4-24', items: row(5, 'TUBEAGRO - AG100', 'Traktor Roda 4') },
      { size: '16.9-30', items: row(5, 'TUBEAGRO - AG200', 'Traktor Roda 4', D) }
    ],
    'forklift': [
      { size: '7.00-12', items: row(5, 'UNINEST - FLT300', 'Forklift 3 Ton') },
      { size: '8.25-15', items: row(5, 'UNINEST - FLT500', 'Forklift 5 Ton', D) },
      { size: '28x9-15', items: row(5, 'TUTRIC - SOLID', 'Forklift Solid Tyre') }
    ],
    'velg-tube': [
      { size: 'DW20 - 11.00', items: row(5, 'VELG HEAVY-DUTY', 'Dumptruck', 'assets/img/velg-heavy.webp') },
      { size: 'DW25 - 20.5', items: row(5, 'VELG LIGHT TRUCK', 'Light Truck', 'assets/img/velg-light.webp') },
      { size: 'Ban Dalam 11.00R20', items: row(5, 'TUBE &amp; FLAP SET', 'Truk &amp; Bus') }
    ]
  };
})();
