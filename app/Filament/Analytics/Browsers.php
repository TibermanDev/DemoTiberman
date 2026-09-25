<?php

namespace App\Filament\Analytics;

use App\Support\Analytics;
use Carbon\CarbonImmutable;
use Illuminate\Support\Collection;

class Browsers extends TopList
{
    protected static ?int $sort = 8;

    protected function heading(): string
    {
        return 'Browser & sistem operasi';
    }

    protected function rows(CarbonImmutable $from, CarbonImmutable $to): Collection
    {
        return Analytics::top($from, $to, 'browser', 5)
            ->concat(Analytics::top($from, $to, 'os', 5)->map(fn ($r) => (object) [...(array) $r, 'label' => $r->label ? 'OS: '.$r->label : null]));
    }
}
