<?php

namespace App\Filament\Resources\ProductTags\Pages;

use App\Filament\Resources\ProductTags\ProductTagResource;
use Filament\Actions\Action;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;
use Filament\Support\Icons\Heroicon;

class EditProductTag extends EditRecord
{
    protected static string $resource = ProductTagResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Action::make('view')->label('Lihat halaman')->icon(Heroicon::OutlinedArrowTopRightOnSquare)->color('gray')
                ->url(fn () => $this->record->url(), shouldOpenInNewTab: true),
            DeleteAction::make(),
        ];
    }
}
