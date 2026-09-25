<?php

namespace App\Filament\Analytics;

use App\Support\Analytics;
use Carbon\CarbonImmutable;
use Illuminate\Support\Collection;

class TopPages extends TopList
{
    protected static ?int $sort = 3;

    protected function heading(): string
    {
        return 'Halaman terpopuler';
    }

    protected function rows(CarbonImmutable $from, CarbonImmutable $to): Collection
    {
        return Analytics::top($from, $to, 'path', 10);
    }
}
