<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;

/**
 * Halaman promosi/artikel SEO di /{slug} (mis. /aeolus-tyre). Dilayani
 * route fallback (SlugPageController) dan tidak ditautkan dari menu.
 *
 * Isi halaman = kolom blocks dari Builder Filament: daftar
 * {type, data} dengan type text | image_text | products | image.
 */
#[Fillable([
    'slug', 'title', 'banner_image', 'banner_alt', 'blocks',
    'cta_heading', 'cta_label', 'cta_url', 'cta_image',
    'meta_title', 'meta_description', 'meta_image', 'noindex', 'is_active',
])]
class PromoPage extends Model
{
    /** Maskot di pita ajakan bawah selama kolom cta_image kosong. */
    public const DEFAULT_CTA_IMAGE = '/assets/img/panda-contact.webp';

    protected function casts(): array
    {
        return [
            'blocks' => 'array',
            'noindex' => 'boolean',
            'is_active' => 'boolean',
        ];
    }

    /**
     * Kartu produk siap tampil untuk blok products: kartu yang memilih
     * produk katalog memakai flashcard/foto & halaman produknya bila
     * gambar/tautannya sendiri kosong.
     *
     * @param  array<int, array<string, mixed>>  $cards
     * @return array<int, array{image: ?string, url: string, label: string, alt: string}>
     */
    public static function resolveCards(array $cards, string $defaultLabel): array
    {
        $products = Product::query()
            ->whereIn('id', collect($cards)->pluck('product_id')->filter()->all())
            ->get()->keyBy('id');

        return collect($cards)->map(function (array $card) use ($products, $defaultLabel) {
            $product = $products->get($card['product_id'] ?? null);

            return [
                'image' => media($card['image'] ?? null)
                    ?? ($product ? (media($product->flashcard_image) ?? $product->imageUrl()) : null),
                'url' => ($card['url'] ?? null) ?: ($product?->url() ?? '#'),
                'label' => ($card['label'] ?? null) ?: $defaultLabel,
                'alt' => (string) (($card['alt'] ?? null) ?: $product?->name),
            ];
        })->filter(fn ($c) => $c['image'] !== null)->values()->all();
    }
}
