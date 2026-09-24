<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

/**
 * Isi halaman & pengaturan situs, satu baris per grup: site, home, contact,
 * news, superarea, catalog. Dibaca lewat helper cms('home.hero.image').
 */
#[Fillable(['key', 'value'])]
class Setting extends Model
{
    public const CACHE_KEY = 'cms.settings';

    protected function casts(): array
    {
        return ['value' => 'array'];
    }

    protected static function booted(): void
    {
        static::saved(fn () => Cache::forget(self::CACHE_KEY));
        static::deleted(fn () => Cache::forget(self::CACHE_KEY));
    }

    /** @return array<string, array> */
    public static function groups(): array
    {
        return Cache::rememberForever(self::CACHE_KEY, fn () => static::query()->get()
            ->mapWithKeys(fn (self $s) => [$s->key => $s->value ?? []])
            ->all());
    }

    public static function group(string $key): array
    {
        return static::groups()[$key] ?? [];
    }

    public static function put(string $key, array $value): void
    {
        static::query()->updateOrCreate(['key' => $key], ['value' => $value]);
    }
}
