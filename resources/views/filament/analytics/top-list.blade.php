{{-- Daftar peringkat untuk widget App\Filament\Analytics\TopList. --}}
<x-filament-widgets::widget>
    <x-filament::section :heading="$heading">
        @if ($rows->isEmpty())
            <p style="margin:0;font-size:.875rem;opacity:.6">Belum ada data di periode ini.</p>
        @else
            <table style="width:100%;border-collapse:collapse;font-size:.875rem">
                <thead>
                    <tr style="text-align:left;opacity:.6;font-size:.75rem">
                        <th style="padding:0 0 8px;font-weight:500"></th>
                        <th style="padding:0 0 8px 12px;font-weight:500;text-align:right;white-space:nowrap">{{ $visitorsLabel }}</th>
                        @if ($viewsLabel)
                        <th style="padding:0 0 8px 12px;font-weight:500;text-align:right;white-space:nowrap">{{ $viewsLabel }}</th>
                        @endif
                    </tr>
                </thead>
                <tbody>
                    @foreach ($rows as $row)
                    <tr>
                        <td style="position:relative;padding:6px 8px;max-width:0;width:100%">
                            {{-- batang proporsi di belakang label --}}
                            <span style="position:absolute;inset:2px auto 2px 0;width:{{ round($row->bar / $max * 100) }}%;border-radius:6px;background:color-mix(in oklab, var(--primary-500) 14%, transparent)"></span>
                            <span style="position:relative;display:block;white-space:nowrap;overflow:hidden;text-overflow:ellipsis" title="{{ $row->label }}">{{ $row->label }}</span>
                        </td>
                        <td style="padding:6px 0 6px 12px;text-align:right;font-variant-numeric:tabular-nums;font-weight:600">{{ number_format($row->visitors, 0, ',', '.') }}</td>
                        @if ($viewsLabel)
                        <td style="padding:6px 0 6px 12px;text-align:right;font-variant-numeric:tabular-nums;opacity:.7">{{ number_format($row->views, 0, ',', '.') }}</td>
                        @endif
                    </tr>
                    @endforeach
                </tbody>
            </table>
        @endif
    </x-filament::section>
</x-filament-widgets::widget>
