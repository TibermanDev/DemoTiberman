{{-- Potongan yang dipakai bersama halaman produk dan modal katalog.
     $part: gallery | specs | sizes | contact --}}
@switch($part)
  @case('gallery')
    @php($images = collect($product->gallery ?: [$product->image])->filter()->values())
    <div data-gallery>
      <div class="gallery__main"><img src="{{ media($images->first()) ?? $product->imageUrl() }}" alt="{{ $product->name }}" data-gallery-main></div>
      @if ($images->count() > 1)
      <div class="gallery__thumbs">
        @foreach ($images as $img)
        <button type="button" @class(['is-active' => $loop->first]) data-gallery-thumb data-full="{{ media($img) }}"><img src="{{ media($img) }}" alt="{{ $product->name }} — foto {{ $loop->iteration }}"></button>
        @endforeach
      </div>
      @endif
      @if ($product->ecatalog_url || $product->flashcard_url)
      <div class="doc-btns">
        @if ($product->ecatalog_url)<a href="{{ $product->ecatalog_url }}">E-Katalog</a>@endif
        @if ($product->flashcard_url)<a href="{{ $product->flashcard_url }}">Flash Card</a>@endif
      </div>
      @endif
    </div>
    @break

  @case('specs')
    @if (filled($product->specs))
    <table class="spec-table"><tbody>
      @foreach (array_chunk($product->specs, 2) as $row)
      <tr>
        @foreach ($row as $spec)
        <td>{{ $spec['label'] ?? '' }}</td><td>: {{ $spec['value'] ?? '' }}</td>
        @endforeach
      </tr>
      @endforeach
    </tbody></table>
    @endif
    @break

  @case('sizes')
    <div class="size-chips" data-size-chips>
      @foreach ($product->available_sizes ?: [$product->size] as $size)
      <a @class(['size-chip', 'is-active' => $loop->first]) href="#">{{ $size }}</a>
      @endforeach
    </div>
    @break

  @case('contact')
    <div class="contact-btns">
      <a class="contact-btn contact-btn--wa" href="{{ $product->whatsapp_url ?: 'https://wa.me/'.cms('site.whatsapp').'?text='.rawurlencode('Halo Tiberman, saya tertarik dengan '.$product->name.' '.$product->size) }}">
        <svg viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M12 2a10 10 0 0 0-8.6 15.1L2 22l5-1.3A10 10 0 1 0 12 2zm5.3 14.1c-.2.6-1.3 1.2-1.8 1.2-.5.1-1 .1-1.7-.1-1-.3-2.6-1-4-2.5-1.3-1.3-2-2.7-2.3-3.4-.2-.6-.3-1.2-.2-1.7.1-.4.7-1.4 1.2-1.6.3-.1.6 0 .8.2l.9 1.5c.1.2.1.5 0 .7l-.4.6c-.1.2-.1.4 0 .6.3.5.8 1.2 1.4 1.7.6.5 1.2.9 1.7 1.1.2.1.5 0 .6-.1l.6-.6c.2-.2.4-.2.6-.1l1.6.8c.2.1.4.4.3.7l-.1.2z"/></svg>
        Whatsapp
      </a>
      <a class="contact-btn contact-btn--shopee" href="{{ $product->shopee_url ?: cms('site.marketplace.shopee', '#') }}"><img src="{{ asset('assets/img/shopee.png') }}" alt="">Shoppe</a>
      <a class="contact-btn contact-btn--tokped" href="{{ $product->tokopedia_url ?: cms('site.marketplace.tokopedia', '#') }}"><img src="{{ asset('assets/img/tokopedia.png') }}" alt="">Tokopedia</a>
    </div>
    @break
@endswitch
