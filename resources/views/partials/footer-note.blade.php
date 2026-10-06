{{-- Baris paling bawah footer: copyright + tautan halaman legal. Dipakai
     footer utama (layouts/app) dan footer ringkas halaman katalog. --}}
<div class="footer__note">
  <span>{{ cms('site.copyright') }}</span>
  <nav class="footer__legal" aria-label="Kebijakan">
    <a href="{{ route('privacy') }}">Privacy Policy</a>
    <a href="{{ route('disclaimer') }}">Disclaimer</a>
  </nav>
</div>
