{{-- Isi .pmodal__track di katalog. Selalu EMPAT slide (hero, kenapa, pair,
     spesifikasi) supaya titik navigasinya tetap sama untuk semua produk; slide
     yang datanya belum diisi di CMS menampilkan info dasar produknya. --}}
@php($description = $product->description ? rich($product->description) : e($product->name.' — ukuran '.$product->size.', cocok untuk '.$product->compat.'.'))
@php($feature = $product->features[0] ?? null)

        <!-- 1. Hero -->
        <section class="pmodal__slide pmodal__slide--hero">
          <div class="pmodal__art"><img src="{{ $product->imageUrl() }}" alt="{{ $product->name }}" loading="lazy"></div>
          <div class="pmodal__copy">
            @if ($product->logo)
            <img class="pmodal__logo" id="pmodal-judul" src="{{ media($product->logo) }}" alt="{{ $product->name }}">
            @else
            <h2 class="pmodal__name" id="pmodal-judul">{{ $product->name }}</h2>
            @endif
            <p>{!! $description !!}</p>
          </div>
        </section>

        <!-- 2. Kenapa harus ban ini -->
        <section class="pmodal__slide pmodal__slide--why">
          <h2 class="pmodal__head">Kenapa Harus Ban Ini ?</h2>
          <div class="pmodal__copy">
            @if ($feature)
            <h3>{!! rich($feature['title'] ?? '') !!}</h3>
            <p>{!! rich($feature['body'] ?? '') !!}</p>
            @else
            <h3>{{ $product->size }}</h3>
            <p>Cocok untuk {{ $product->compat }}. Hubungi tim kami untuk rekomendasi spesifikasi yang paling sesuai dengan unit dan medan Anda.</p>
            @endif
          </div>
          <div class="pmodal__art pmodal__art--bleed"><img src="{{ media($feature['image'] ?? null) ?? $product->imageUrl() }}" alt="{{ $product->name }}" loading="lazy"></div>
        </section>

        <!-- 3. Perfect pair for -->
        <section class="pmodal__slide pmodal__slide--pair">
          <h2 class="pmodal__head">Perfect pair for</h2>
          <div class="pair-grid">
            @forelse ($product->pairs ?? [] as $pair)
            <article class="pair-card"><img src="{{ media($pair['image'] ?? null) }}" alt="{{ $pair['title'] ?? '' }}" loading="lazy"><strong>{{ $pair['title'] ?? '' }}</strong></article>
            @empty
            <article class="pair-card"><img src="{{ $product->imageUrl() }}" alt="{{ $product->compat }}" loading="lazy"><strong>{{ $product->compat }}</strong></article>
            @endforelse
          </div>
        </section>

        <!-- 4. Spesifikasi -->
        <section class="pmodal__slide pmodal__slide--spec">
          @include('partials.product-parts', ['part' => 'gallery'])
          <div class="pmodal__info">
            @if ($product->logo)
            <img class="pmodal__logo" src="{{ media($product->logo) }}" alt="{{ $product->name }}">
            @else
            <h3 class="pmodal__name">{{ $product->name }}</h3>
            @endif
            @if (filled($product->specs))
            <h3 class="subhead">Spesifikasi :</h3>
            @include('partials.product-parts', ['part' => 'specs'])
            @endif
            <h3 class="subhead">Available Size :</h3>
            @include('partials.product-parts', ['part' => 'sizes'])
            <h3 class="subhead">Contact us :</h3>
            @include('partials.product-parts', ['part' => 'contact'])
            <p class="pmodal__more"><a href="{{ $product->url() }}">Lihat halaman produk &rarr;</a></p>
          </div>
        </section>
