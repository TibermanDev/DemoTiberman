<?php

namespace App\Support;

use App\Models\Flipbook;
use App\Models\LandingPage;
use App\Models\PromoPage;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Route;

/**
 * Slug /{slug} yang dilayani route fallback dipakai bersama oleh flipbook
 * PDF, landing page promo, dan halaman promo. Satu slug hanya boleh dipakai
 * satu halaman — dan tidak boleh menabrak route biasa (blog, kontak, ...).
 */
class PageSlug
{
    public static function isTaken(string $slug, ?Model $ignore = null): bool
    {
        if (collect(Route::getRoutes())->contains(fn ($r) => ! $r->isFallback && trim($r->uri(), '/') === $slug)) {
            return true;
        }

        foreach ([Flipbook::class, LandingPage::class, PromoPage::class] as $model) {
            $taken = $model::query()->where('slug', $slug)
                ->when($ignore instanceof $model, fn ($q) => $q->whereKeyNot($ignore->getKey()))
                ->exists();
            if ($taken) {
                return true;
            }
        }

        return false;
    }

    /** Aturan validasi Filament untuk kolom slug. */
    public static function rule(?Model $record): \Closure
    {
        return function (string $attribute, $value, \Closure $fail) use ($record) {
            if (static::isTaken((string) $value, $record)) {
                $fail('Alamat ini sudah dipakai halaman lain.');
            }
        };
    }
}
