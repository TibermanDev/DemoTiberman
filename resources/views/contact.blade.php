@extends('layouts.app')

@section('title', data_get($page, 'seo_title') ?: 'Contact Us — Tiberman')
@section('description', data_get($page, 'seo_description'))
@section('body-class', 'subpage')

@section('content')

<main class="ctc">

  <!-- ============================= JADI MITRA + FORM ============================= -->
  <section class="ctc-partner">
    <div class="container">
      <div class="ctc-partner__head reveal">
        <p class="ctc-eyebrow">{{ data_get($page, 'eyebrow') }}</p>
        <h1>{!! rich(data_get($page, 'heading')) !!}</h1>
      </div>

      <div class="ctc-partner__grid">
        <!-- Kiriman masuk ke menu Permintaan di CMS. Dengan JS dikirim lewat
             fetch tanpa reload (assets/js/contact-form.js); tanpa JS tetap
             terkirim sebagai POST biasa. -->
        <form class="ctc-form reveal" method="post" action="{{ route('contact.store') }}" data-contact-form novalidate>
          @csrf
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
                @foreach (data_get($page, 'unit_options', []) as $option)
                <option>{{ $option }}</option>
                @endforeach
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
            <button class="btn btn--primary" type="submit">{{ data_get($page, 'submit_label', 'Kirim Permintaan') }}</button>
            <p @class(['ctc-form__note', 'is-on' => session('inquiry_sent')]) data-form-note role="status">@if (session('inquiry_sent'))Terima kasih. Permintaan Anda kami terima, tim kami akan menghubungi dalam 1x24 jam.@endif</p>
          </div>
        </form>

        <!-- Satu gambar accessories (ban + velg lengkap), bukan lagi tumpukan
             beberapa PNG yang disusun sendiri. -->
        <div class="ctc-stack" aria-hidden="true">
          <img src="{{ media(data_get($page, 'form_image')) }}" alt="" loading="lazy">
        </div>
      </div>
    </div>
  </section>

  <!-- ============================= FAQ ============================= -->
  <section class="faq" id="contact">
    <div class="container">
      <div class="faq__grid">
        <div class="faq__art reveal">
          <img src="{{ media(data_get($page, 'faq_image')) }}" alt="Maskot panda Tiberman siap membantu" loading="lazy">
        </div>
        <div class="faq__panel reveal" data-delay="100">
          <h2>{{ data_get($page, 'faq_heading') }}</h2>
          <div data-accordion>
            @foreach (data_get($page, 'faq', []) as $item)
            <div @class(['acc__item', 'is-open' => $loop->first])>
              <button class="acc__q" aria-expanded="{{ $loop->first ? 'true' : 'false' }}">{{ $item['question'] ?? '' }}
                <svg viewBox="0 0 16 16" fill="none" stroke="currentColor" stroke-width="1.6"><path d="M3 6l5 5 5-5"/></svg>
              </button>
              <div class="acc__a"><p>{!! rich($item['answer'] ?? '') !!}</p></div>
            </div>
            @endforeach
          </div>
          <div class="faq__foot">
            <span>{{ data_get($page, 'faq_foot') }}</span>
            <a class="btn btn--connect" href="{{ data_get($page, 'connect_url') ?: 'https://wa.me/'.cms('site.whatsapp') }}">{{ data_get($page, 'connect_label') }}
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
