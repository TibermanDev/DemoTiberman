<?php

namespace App\Filament\Pages;

use App\Filament\Analytics\Browsers;
use App\Filament\Analytics\Campaigns;
use App\Filament\Analytics\ConversionPages;
use App\Filament\Analytics\Devices;
use App\Filament\Analytics\StatsOverview;
use App\Filament\Analytics\TopPages;
use App\Filament\Analytics\TopSources;
use App\Filament\Analytics\VisitorsChart;
use App\Support\Analytics;
use BackedEnum;
use Filament\Forms\Components\Select;
use Filament\Pages\Dashboard;
use Filament\Pages\Dashboard\Concerns\HasFiltersForm;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;

/**
 * Dashboard analitik pengunjung bawaan (data dari tabel analytics_events,
 * lihat App\Support\Analytics). Widget-nya sengaja di App\Filament\Analytics,
 * di luar folder yang di-discover panel, supaya tidak ikut ke Dashboard utama.
 */
class VisitorAnalytics extends Dashboard
{
    use HasFiltersForm;

    protected static string $routePath = '/analytics';

    protected static ?string $title = 'Analitik Pengunjung';

    protected static ?string $navigationLabel = 'Analitik Pengunjung';

    protected static ?int $navigationSort = -1;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedPresentationChartLine;

    public function filtersForm(Schema $schema): Schema
    {
        return $schema->components([
            Select::make('period')->label('Periode')
                ->options(Analytics::PERIODS)->default('30')->selectablePlaceholder(false),
        ]);
    }

    public function getWidgets(): array
    {
        return [
            StatsOverview::class,
            VisitorsChart::class,
            TopPages::class,
            TopSources::class,
            ConversionPages::class,
            Campaigns::class,
            Devices::class,
            Browsers::class,
        ];
    }

    public function getColumns(): int|array
    {
        return ['md' => 2];
    }
}
