<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

/**
 * Redirect dari URL situs lama (tiberman.com) yang tidak dibuat ulang.
 * from_path disimpan tanpa garis miring depan/belakang.
 */
#[Fillable(['from_path', 'to_path', 'status_code'])]
class Redirect extends Model
{
    public const CACHE_KEY = 'cms.redirects';

    protected static function booted(): void
    {
        static::saving(fn (self $r) => $r->from_path = trim(parse_url($r->from_path, PHP_URL_PATH) ?? '', '/'));
        static::saved(fn () => Cache::forget(self::CACHE_KEY));
        static::deleted(fn () => Cache::forget(self::CACHE_KEY));
    }

    /** @return array<string, array{0: string, 1: int}> */
    public static function map(): array
    {
        return Cache::rememberForever(self::CACHE_KEY, fn () => static::query()->get()
            ->mapWithKeys(fn (self $r) => [$r->from_path => [$r->to_path, $r->status_code]])
            ->all());
    }
}
