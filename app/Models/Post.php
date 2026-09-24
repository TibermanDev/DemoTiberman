<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

#[Fillable([
    'post_category_id', 'title', 'slug', 'excerpt', 'cover_image', 'cover_alt', 'cover_caption',
    'body', 'author', 'published_at', 'is_published', 'is_featured', 'is_popular', 'meta_description',
])]
class Post extends Model
{
    protected function casts(): array
    {
        return [
            'published_at' => 'datetime',
            'is_published' => 'boolean',
            'is_featured' => 'boolean',
            'is_popular' => 'boolean',
        ];
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(PostCategory::class, 'post_category_id');
    }

    public function tags(): BelongsToMany
    {
        return $this->belongsToMany(PostCategory::class, 'post_tag');
    }

    /** Terbit dan tanggal terbitnya sudah lewat, urut terbaru. */
    public function scopeLive(Builder $query): Builder
    {
        return $query->where('is_published', true)
            ->where(fn ($q) => $q->whereNull('published_at')->orWhere('published_at', '<=', now()))
            ->orderByDesc('published_at')
            ->orderByDesc('id');
    }

    public function url(): string
    {
        return route('blog.show', $this->slug);
    }

    /** "10 September 2026" — nama bulan Indonesia, seperti blog lama. */
    public function dateLabel(): string
    {
        return $this->published_at?->locale('id')->translatedFormat('j F Y') ?? '';
    }

    public function readingMinutes(): int
    {
        return max(1, (int) ceil(str_word_count(strip_tags((string) $this->body)) / 200));
    }
}
