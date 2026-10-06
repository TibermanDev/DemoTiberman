<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

/**
 * Tag produk untuk SEO di /tag-produk/{slug} (URL toko lama, mis.
 * /tag-produk/ban-dump-truck-terex-tr60). Halaman katalog yang hanya berisi
 * produk pilihan tag ini; tidak muncul di sidebar katalog.
 */
#[Fillable(['slug', 'heading', 'meta_title', 'meta_description', 'noindex', 'is_active'])]
class ProductTag extends Model
{
    protected function casts(): array
    {
        return [
            'noindex' => 'boolean',
            'is_active' => 'boolean',
        ];
    }

    public function products(): BelongsToMany
    {
        return $this->belongsToMany(Product::class);
    }

    public function url(): string
    {
        return route('katalog.tag', $this->slug);
    }
}
