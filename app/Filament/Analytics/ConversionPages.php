<?php

namespace App\Filament\Analytics;

use App\Models\AnalyticsEvent;
use App\Support\Analytics;
use Carbon\CarbonImmutable;
use Illuminate\Support\Collection;

/** Halaman tempat pengunjung mengirim form Contact atau mengklik WhatsApp. */
class ConversionPages extends TopList
{
    protected static ?int $sort = 6;

    protected function heading(): string
    {
        return 'Halaman penghasil kontak';
    }

    protected function visitorsLabel(): string
    {
        return 'Form';
    }

    protected function viewsLabel(): ?string
    {
        return 'WhatsApp';
    }

    protected function rows(CarbonImmutable $from, CarbonImmutable $to): Collection
    {
        // "visitors" = jumlah form, "views" = jumlah klik WhatsApp (label kolomnya disesuaikan)
        return Analytics::query($from, $to, null)
            ->whereIn('type', [AnalyticsEvent::LEAD, AnalyticsEvent::WHATSAPP])
            ->selectRaw("path as label, sum(case when type = 'lead' then 1 else 0 end) as visitors, sum(case when type = 'whatsapp' then 1 else 0 end) as views, count(*) as total")
            ->groupBy('path')->orderByDesc('total')->limit(10)->get()
            ->map(fn ($r) => (object) ['label' => $r->label, 'visitors' => (int) $r->visitors, 'views' => (int) $r->views, 'bar' => (int) $r->total]);
    }
}
