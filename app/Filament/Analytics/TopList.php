<?php

namespace App\Filament\Analytics;

use App\Support\Analytics;
use Carbon\CarbonImmutable;
use Filament\Widgets\Concerns\InteractsWithPageFilters;
use Filament\Widgets\Widget;
use Illuminate\Support\Collection;

/**
 * Daftar peringkat sederhana (label, pengunjung, tampilan + batang proporsi).
 * Bukan TableWidget: datanya hasil GROUP BY, bukan baris model.
 */
abstract class TopList extends Widget
{
    use InteractsWithPageFilters;

    protected string $view = 'filament.analytics.top-list';

    abstract protected function heading(): string;

    /** Judul kolom angka kedua; null = sembunyikan kolom "tampilan". */
    protected function viewsLabel(): ?string
    {
        return 'Tampilan';
    }

    protected function visitorsLabel(): string
    {
        return 'Pengunjung';
    }

    abstract protected function rows(CarbonImmutable $from, CarbonImmutable $to): Collection;

    protected function label(?string $value): string
    {
        return $value ?? '(tidak diketahui)';
    }

    protected function getViewData(): array
    {
        [$from, $to] = Analytics::range($this->pageFilters['period'] ?? '30');
        $rows = $this->rows($from, $to);

        return [
            'heading' => $this->heading(),
            'viewsLabel' => $this->viewsLabel(),
            'visitorsLabel' => $this->visitorsLabel(),
            // panjang batang = kolom "bar" kalau ada (mis. total konversi), selain itu pengunjung
            'max' => max(1, (int) $rows->max(fn ($r) => $r->bar ?? $r->visitors)),
            'rows' => $rows->map(fn ($r) => (object) ['bar' => $r->visitors, ...(array) $r, 'label' => $this->label($r->label)]),
        ];
    }
}
