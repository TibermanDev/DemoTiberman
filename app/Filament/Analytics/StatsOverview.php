<?php

namespace App\Filament\Analytics;

use App\Models\AnalyticsEvent;
use App\Support\Analytics;
use Filament\Support\Icons\Heroicon;
use Filament\Widgets\Concerns\InteractsWithPageFilters;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

/**
 * Angka ringkas periode terpilih + perbandingan dengan periode sebelumnya.
 * Juga tampil di Dashboard utama (tanpa filter = 30 hari terakhir).
 */
class StatsOverview extends StatsOverviewWidget
{
    use InteractsWithPageFilters;

    protected ?string $pollingInterval = null;

    protected static ?int $sort = 1;

    protected function getStats(): array
    {
        $period = $this->pageFilters['period'] ?? '30';
        [$from, $to] = Analytics::range($period);
        [$pFrom, $pTo] = Analytics::previousRange($period);
        $daily = Analytics::daily($from, $to);

        $visitors = Analytics::visitors($from, $to);
        $views = Analytics::query($from, $to)->count();
        $leads = Analytics::query($from, $to, AnalyticsEvent::LEAD)->count();
        $wa = Analytics::query($from, $to, AnalyticsEvent::WHATSAPP)->count();

        return [
            $this->stat('Pengunjung', $visitors, Analytics::visitors($pFrom, $pTo), Heroicon::OutlinedUsers)
                ->chart($daily['visitors']),
            $this->stat('Tampilan halaman', $views, Analytics::query($pFrom, $pTo)->count(), Heroicon::OutlinedEye)
                ->chart($daily['pageviews']),
            $this->stat('Form Contact terkirim', $leads, Analytics::query($pFrom, $pTo, AnalyticsEvent::LEAD)->count(), Heroicon::OutlinedEnvelope),
            $this->stat('Klik WhatsApp', $wa, Analytics::query($pFrom, $pTo, AnalyticsEvent::WHATSAPP)->count(), Heroicon::OutlinedChatBubbleLeftRight),
            Stat::make('Tingkat konversi', $visitors ? number_format(($leads + $wa) / $visitors * 100, 1, ',', '.').'%' : '–')
                ->description('(form + WhatsApp) ÷ pengunjung')
                ->icon(Heroicon::OutlinedArrowTrendingUp),
        ];
    }

    private function stat(string $label, int $now, int $before, Heroicon $icon): Stat
    {
        $stat = Stat::make($label, number_format($now, 0, ',', '.'))->icon($icon);

        if ($before === 0) {
            return $stat->description($now ? 'Belum ada data periode sebelumnya' : 'Belum ada data');
        }

        $change = ($now - $before) / $before * 100;

        return $stat
            ->description(($change >= 0 ? '+' : '').number_format($change, 0, ',', '.').'% dari periode sebelumnya')
            ->descriptionIcon($change >= 0 ? Heroicon::ArrowTrendingUp : Heroicon::ArrowTrendingDown)
            ->color($change >= 0 ? 'success' : 'danger');
    }
}
