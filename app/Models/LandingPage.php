<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;

/**
 * Landing page promo marketplace di /{slug} (mis. /shopee-banjarbaru).
 * Dilayani route fallback (SlugPageController) dan tidak ditautkan dari
 * menu — tujuannya SEO & iklan. Kartu produk disimpan di kolom JSON cards.
 */
#[Fillable([
    'slug', 'title', 'heading', 'logo', 'subheading', 'cards',
    'meta_title', 'meta_description', 'meta_image', 'noindex', 'is_active',
])]
class LandingPage extends Model
{
    /** Logo marketplace bawaan selama kolom logo kosong. */
    public const DEFAULT_LOGO = '/assets/img/shopee-wide.png';

    protected function casts(): array
    {
        return [
            'cards' => 'array',
            'noindex' => 'boolean',
            'is_active' => 'boolean',
        ];
    }

    public function logoUrl(): string
    {
        return media($this->logo) ?? self::DEFAULT_LOGO;
    }
}
