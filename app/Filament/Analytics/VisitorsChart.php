<?php

namespace App\Filament\Analytics;

use App\Support\Analytics;
use Filament\Widgets\ChartWidget;
use Filament\Widgets\Concerns\InteractsWithPageFilters;

class VisitorsChart extends ChartWidget
{
    use InteractsWithPageFilters;

    protected ?string $heading = 'Pengunjung per hari';

    protected ?string $pollingInterval = null;

    protected int|string|array $columnSpan = 'full';

    protected ?string $maxHeight = '300px';

    protected static ?int $sort = 2;

    protected function getType(): string
    {
        return 'line';
    }

    protected function getData(): array
    {
        [$from, $to] = Analytics::range($this->pageFilters['period'] ?? '30');
        $daily = Analytics::daily($from, $to);

        return [
            'labels' => $daily['labels'],
            'datasets' => [
                [
                    'label' => 'Pengunjung',
                    'data' => $daily['visitors'],
                    'borderColor' => '#ef4444',
                    'backgroundColor' => 'rgba(239, 68, 68, .12)',
                    'fill' => true,
                    'tension' => .3,
                ],
                [
                    'label' => 'Tampilan halaman',
                    'data' => $daily['pageviews'],
                    'borderColor' => '#9ca3af',
                    'backgroundColor' => 'transparent',
                    'borderDash' => [4, 4],
                    'tension' => .3,
                ],
            ],
        ];
    }
}
