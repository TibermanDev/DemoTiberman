<?php

namespace App\Filament\Resources\PromoPages\Pages;

use App\Filament\Resources\PromoPages\PromoPageResource;
use Filament\Actions\Action;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;
use Filament\Support\Icons\Heroicon;

class EditPromoPage extends EditRecord
{
    protected static string $resource = PromoPageResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Action::make('view')->label('Lihat halaman')->icon(Heroicon::OutlinedArrowTopRightOnSquare)->color('gray')
                ->url(fn () => url($this->record->slug), shouldOpenInNewTab: true),
            DeleteAction::make(),
        ];
    }
}
