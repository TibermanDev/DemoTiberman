<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Tombol "Telusuri berdasarkan unit" di katalog. path = URL kategori toko
 * lama yang dipakai tombolnya; aliases = kategori lama lain yang membuka unit
 * yang sama. Unit berkunci 'all' = semua unit sekaligus (halaman merk &
 * ukuran) dan tidak boleh dihapus.
 */
#[Fillable(['key', 'label', 'path', 'aliases', 'show_in_nav', 'sort_order'])]
class CatalogUnit extends Model
{
    public const ALL = 'all';

    protected function casts(): array
    {
        return ['aliases' => 'array', 'show_in_nav' => 'boolean'];
    }

    public function products(): HasMany
    {
        return $this->hasMany(Product::class);
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order')->orderBy('id');
    }

    public function url(): string
    {
        return '/'.trim($this->path, '/');
    }
}
