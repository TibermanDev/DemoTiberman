<?php

namespace App\Filament\Analytics;

use App\Support\Analytics;
use Carbon\CarbonImmutable;
use Illuminate\Support\Collection;

class Devices extends TopList
{
    protected static ?int $sort = 7;

    private const NAMES = ['desktop' => 'Desktop / laptop', 'mobile' => 'HP', 'tablet' => 'Tablet'];

    protected function heading(): string
    {
        return 'Perangkat';
    }

    protected function rows(CarbonImmutable $from, CarbonImmutable $to): Collection
    {
        return Analytics::top($from, $to, 'device', 5);
    }

    protected function label(?string $value): string
    {
        return self::NAMES[$value] ?? '(tidak diketahui)';
    }
}
