<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

/**
 * Satu entri kamus assets/js/i18n.js. source = teks Indonesia persis seperti
 * tampil di halaman (spasi berlebih dirapikan), en/zh = terjemahannya.
 */
#[Fillable(['source', 'source_hash', 'en', 'zh'])]
class Translation extends Model
{
    public const CACHE_KEY = 'cms.translations';

    protected static function booted(): void
    {
        static::saving(function (self $t) {
            $t->source = self::normalize($t->source);
            $t->source_hash = sha1($t->source);
        });
        static::saved(fn () => Cache::forget(self::CACHE_KEY));
        static::deleted(fn () => Cache::forget(self::CACHE_KEY));
    }

    /** Sama dengan pick() di i18n.js: spasi beruntun jadi satu, ujung dipangkas. */
    public static function normalize(?string $text): string
    {
        return trim(preg_replace('/\s+/u', ' ', (string) $text));
    }

    /** @return array{en: array<string, string>, zh: array<string, string>} */
    public static function dictionary(): array
    {
        return Cache::rememberForever(self::CACHE_KEY, function () {
            $dict = ['en' => [], 'zh' => []];
            foreach (static::query()->get(['source', 'en', 'zh']) as $t) {
                foreach (['en', 'zh'] as $lang) {
                    if (filled($t->$lang)) {
                        $dict[$lang][$t->source] = $t->$lang;
                    }
                }
            }

            return $dict;
        });
    }
}
