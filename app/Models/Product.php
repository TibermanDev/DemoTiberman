<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable([
    'catalog_unit_id', 'brand_id', 'name', 'slug', 'size', 'compat', 'image', 'sort_order', 'is_active',
    'logo', 'logo_light', 'hero_image', 'description', 'features', 'pairs', 'gallery', 'specs',
    'available_sizes', 'ecatalog_url', 'flashcard_url', 'whatsapp_url', 'shopee_url', 'tokopedia_url',
    'meta_description',
])]
class Product extends Model
{
    /** Gambar kartu kalau produk belum punya foto sendiri. */
    public const FALLBACK_IMAGE = '/assets/img/tire-transparent-soft.png';

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
            'features' => 'array',
            'pairs' => 'array',
            'gallery' => 'array',
            'specs' => 'array',
            'available_sizes' => 'array',
        ];
    }

    public function unit(): BelongsTo
    {
        return $this->belongsTo(CatalogUnit::class, 'catalog_unit_id');
    }

    public function brand(): BelongsTo
    {
        return $this->belongsTo(Brand::class);
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function url(): string
    {
        return route('produk.show', $this->slug);
    }

    public function imageUrl(): string
    {
        return media($this->image) ?? self::FALLBACK_IMAGE;
    }

    /** Nama tanpa awalan merk: "UNINEST - TIBERMAX 800" -> "TIBERMAX 800". */
    public function shortName(): string
    {
        return trim(str_contains($this->name, ' - ') ? explode(' - ', $this->name, 2)[1] : $this->name);
    }
}
