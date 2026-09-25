<?php

namespace App\Filament\Analytics;

use App\Support\Analytics;
use Carbon\CarbonImmutable;
use Illuminate\Support\Collection;

/** Kunjungan dari link iklan/kampanye yang memakai parameter utm_source. */
class Campaigns extends TopList
{
    protected static ?int $sort = 5;

    protected function heading(): string
    {
        return 'Kampanye iklan (utm_source)';
    }

    protected function rows(CarbonImmutable $from, CarbonImmutable $to): Collection
    {
        return Analytics::top($from, $to, 'utm_source', 10)->filter(fn ($r) => $r->label !== null)->values();
    }
}
