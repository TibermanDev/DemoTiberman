<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Prunable;

/** Satu tampilan halaman / event konversi (lihat App\Support\Analytics). */
#[Fillable([
    'type', 'day', 'path', 'referrer', 'visitor', 'device', 'browser', 'os', 'country',
    'utm_source', 'utm_medium', 'utm_campaign',
])]
class AnalyticsEvent extends Model
{
    use Prunable;

    public const PAGEVIEW = 'pageview';

    public const LEAD = 'lead';

    public const WHATSAPP = 'whatsapp';

    public const TYPES = [self::PAGEVIEW, self::LEAD, self::WHATSAPP];

    /** Data disimpan 13 bulan: cukup untuk membandingkan bulan yang sama tahun lalu. */
    public const KEEP_MONTHS = 13;

    const UPDATED_AT = null;

    protected function casts(): array
    {
        return ['day' => 'date'];
    }

    public function prunable(): Builder
    {
        return static::query()->where('day', '<', now('Asia/Jakarta')->subMonths(self::KEEP_MONTHS)->toDateString());
    }
}
