<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Kategori blog. slug mengikuti blog lama (tiberman.com/blog/category/{slug})
 * supaya URL-nya tetap sama; name = label tab, heading = judul section.
 */
#[Fillable(['slug', 'name', 'heading', 'sort_order'])]
class PostCategory extends Model
{
    public function posts(): HasMany
    {
        return $this->hasMany(Post::class);
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order')->orderBy('id');
    }
}
