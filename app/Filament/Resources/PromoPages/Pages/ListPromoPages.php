<?php

namespace App\Filament\Resources\PromoPages\Pages;

use App\Filament\Resources\PromoPages\PromoPageResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListPromoPages extends ListRecords
{
    protected static string $resource = PromoPageResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
