@extends('layouts.app')

@section('title', 'Contact Us — Tiberman')
@section('description', 'Pertanyaan yang sering diajukan seputar pemesanan, pengiriman, dan garansi ban Tiberman — beserta jalur kontak langsung ke tim kami.')
@section('body-class', 'subpage')

@section('content')

<main class="ctc">

  <!-- ============================= JADI MITRA + FORM ============================= -->
  <section class="ctc-partner">
    <div class="container">
      <div class="ctc-partner__head reveal">
        <p class="ctc-eyebrow">Become Our Partner!</p>
        <h1>Stronger Business Start<br>with the Right Partner</h1>
      </div>

      <div class="ctc-partner__grid">
        <!-- Form demo: belum ada endpoint, jadi submit-nya ditahan dan diganti
             pesan konfirmasi (assets/js/contact-form.js). -->
        <form class="ctc-form reveal" data-contact-form novalidate>
          <div class="ctc-form__row">
            <label class="ctc-field">
              <span>Nama</span>
              <input type="text" name="nama" placeholder="Nama lengkap Anda" autocomplete="name" required>
            </label>
            <label class="ctc-field">
              <span>Email</span>
              <input type="email" name="email" placeholder="anda@contoh.com" autocomplete="email" required>
            </label>
          </div>

          <div class="ctc-form__row">
            <label class="ctc-field">
              <span>Nomor Telepon</span>
              <input type="tel" name="telepon" placeholder="+62 812 8325 8200" autocomplete="tel" required>
            </label>
            <label class="ctc-field">
              <span>Kebutuhan Unit</span>
              <select name="unit" required>
                <option value="" selected disabled>Pilih jenis unit&hellip;</option>
                <option>Truk &amp; Bus</option>
                <option>Mining Truck</option>
                <option>Loader-Grader</option>
                <option>Traktor</option>
                <option>Forklift</option>
                <option>Velg &amp; Tube</option>
              </select>
            </label>
          </div>

          <div class="ctc-form__row">
            <label class="ctc-field">
              <span>Perkiraan Waktu Kebutuhan</span>
              <input type="date" name="tanggal">
            </label>
            <label class="ctc-field">
              <span>Perkiraan Jumlah</span>
              <input type="text" name="jumlah" placeholder="mis. 40 ban, 2 set velg">
            </label>
          </div>

          <label class="ctc-field">
            <span>Pesan / Permintaan Khusus</span>
            <textarea name="pesan" rows="4" placeholder="Ada hal lain yang perlu kami tahu?"></textarea>
          </label>

          <div class="ctc-form__foot">
            <button class="btn btn--primary" type="submit">Kirim Permintaan</button>
            <p class="ctc-form__note" data-form-note role="status"></p>
          </div>
        </form>

        <!-- Satu gambar accessories (ban + velg lengkap), bukan lagi tumpukan
             beberapa PNG yang disusun sendiri. -->
        <div class="ctc-stack" aria-hidden="true">
          <img src="{{ asset('assets/img/accessories.webp') }}" width="991" height="646" alt="" loading="lazy">
        </div>
      </div>
    </div>
  </section>

  <!-- ============================= FAQ ============================= -->
  <section class="faq" id="contact">
    <div class="container">
      <div class="faq__grid">
        <div class="faq__art reveal">
          <img src="{{ asset('assets/img/panda-contact.webp') }}" alt="Maskot panda Tiberman siap membantu" loading="lazy">
        </div>
        <div class="faq__panel reveal" data-delay="100">
          <h2>Do you have questions?</h2>
          <div data-accordion>
            <div class="acc__item is-open">
              <button class="acc__q" aria-expanded="true">Apakah Tiberman melayani pembelian dalam jumlah besar?
                <svg viewBox="0 0 16 16" fill="none" stroke="currentColor" stroke-width="1.6"><path d="M3 6l5 5 5-5"/></svg>
              </button>
              <div class="acc__a"><p>Ya. Sebagian besar pelanggan kami adalah perusahaan angkutan, kontraktor, dan perusahaan tambang dengan kebutuhan puluhan hingga ratusan ban per pengadaan. Tim kami akan membantu menyesuaikan spesifikasi dengan rute dan beban armada Anda.</p></div>
            </div>
            <div class="acc__item">
              <button class="acc__q" aria-expanded="false">Berapa lama proses pengirimannya?
                <svg viewBox="0 0 16 16" fill="none" stroke="currentColor" stroke-width="1.6"><path d="M3 6l5 5 5-5"/></svg>
              </button>
              <div class="acc__a"><p>Untuk stok yang tersedia di SuperArea terdekat, pengiriman umumnya dilakukan dalam 24 jam. Distribusi dikelola sendiri oleh PT Hantar Lintas Nusantara (Halilintar), jadi jadwalnya bisa kami pantau sampai barang diterima.</p></div>
            </div>
            <div class="acc__item">
              <button class="acc__q" aria-expanded="false">Bagaimana kalau ban yang saya terima cacat produksi?
                <svg viewBox="0 0 16 16" fill="none" stroke="currentColor" stroke-width="1.6"><path d="M3 6l5 5 5-5"/></svg>
              </button>
              <div class="acc__a"><p>Hubungi SuperArea tempat Anda membeli beserta foto dan nomor seri bannya. Klaim garansi cacat produksi kami proses tanpa biaya, termasuk penggantian unit bila hasil pemeriksaan memenuhi syarat.</p></div>
            </div>
            <div class="acc__item">
              <button class="acc__q" aria-expanded="false">Apakah tersedia layanan konsultasi pemilihan ban?
                <svg viewBox="0 0 16 16" fill="none" stroke="currentColor" stroke-width="1.6"><path d="M3 6l5 5 5-5"/></svg>
              </button>
              <div class="acc__a"><p>Tersedia dan tanpa biaya. Sampaikan jenis unit, medan, dan beban rata-rata Anda — tim teknis kami akan merekomendasikan ukuran, pola telapak, dan konstruksi yang paling sesuai.</p></div>
            </div>
            <div class="acc__item">
              <button class="acc__q" aria-expanded="false">Di mana saja lokasi SuperArea Tiberman?
                <svg viewBox="0 0 16 16" fill="none" stroke="currentColor" stroke-width="1.6"><path d="M3 6l5 5 5-5"/></svg>
              </button>
              <div class="acc__a"><p>Ada 15 SuperArea dari Sumatra sampai Maluku &amp; Papua. Daftar lengkap beserta alamatnya bisa dilihat di halaman <a href="{{ route('superarea') }}">SuperArea</a>.</p></div>
            </div>
          </div>
          <div class="faq__foot">
            <span>Pertanyaan saya tidak ada di sini.</span>
            <a class="btn btn--connect" href="https://wa.me/6281283258200">Connect us
              <svg width="14" height="14" viewBox="0 0 14 14" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M3 11L11 3M5 3h6v6"/></svg>
            </a>
          </div>
        </div>
      </div>
    </div>
  </section>

</main>

@endsection

@push('scripts')
<script src="{{ asset('assets/js/contact-form.js') }}" defer></script>
@endpush
