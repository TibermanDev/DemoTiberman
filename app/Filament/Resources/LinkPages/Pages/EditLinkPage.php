<?php

namespace App\Filament\Resources\LinkPages\Pages;

use App\Filament\Resources\LinkPages\LinkPageResource;
use Filament\Actions\Action;
use Filament\Resources\Pages\EditRecord;
use Filament\Support\Icons\Heroicon;

class EditLinkPage extends EditRecord
{
    protected static string $resource = LinkPageResource::class;

    // Tanpa tombol hapus: /lp/ satu-satunya linktree, cukup dinonaktifkan.
    protected function getHeaderActions(): array
    {
        return [
            Action::make('view')->label('Lihat halaman')->icon(Heroicon::OutlinedArrowTopRightOnSquare)->color('gray')
                ->url(fn () => $this->record->url(), shouldOpenInNewTab: true),
        ];
    }
}
