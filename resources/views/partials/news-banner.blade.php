  <!-- Banner disusun dari EMPAT lapis, bukan satu gambar jadi: tinggi
       bandnya bisa ditentukan sendiri, lockup "Tiberman News" punya ukuran
       minimum sendiri, dan ban kiri-kanan bisa dikecilkan terpisah di layar
       sempit. Dengan satu gambar utuh semuanya ikut menyusut bersama rasio
       9,5:1 dan lockup-nya tinggal belasan piksel di ponsel. -->
  <div class="nwp__banner">
    <img class="nwp__banner-bg" src="{{ asset('assets/img/warehouse-trans-news.webp') }}" width="2400" height="252" alt="" aria-hidden="true" fetchpriority="high">
    <img class="nwp__banner-tyre nwp__banner-tyre--l" src="{{ asset('assets/img/tyre-left.webp') }}" width="700" height="520" alt="" aria-hidden="true">
    <img class="nwp__banner-tyre nwp__banner-tyre--r" src="{{ asset('assets/img/tyre-right.webp') }}" width="700" height="520" alt="" aria-hidden="true">
    <img class="nwp__banner-logo" src="{{ asset('assets/img/tbm-news.webp') }}" width="1200" height="173" alt="Tiberman News">
  </div>
