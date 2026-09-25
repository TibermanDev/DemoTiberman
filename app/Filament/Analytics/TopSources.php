<?php

namespace App\Filament\Analytics;

use App\Support\Analytics;
use Carbon\CarbonImmutable;
use Illuminate\Support\Collection;

/** Situs asal pengunjung (google.com, facebook.com, ...), dari document.referrer. */
class TopSources extends TopList
{
    protected static ?int $sort = 4;

    protected function heading(): string
    {
        return 'Sumber kunjungan';
    }

    protected function rows(CarbonImmutable $from, CarbonImmutable $to): Collection
    {
        return Analytics::top($from, $to, 'referrer', 10);
    }

    protected function label(?string $value): string
    {
        return $value ?? 'Langsung / bookmark / aplikasi chat';
    }
}
