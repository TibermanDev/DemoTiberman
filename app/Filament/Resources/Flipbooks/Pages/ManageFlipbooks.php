<?php

namespace App\Filament\Resources\Flipbooks\Pages;

use App\Filament\Resources\Flipbooks\FlipbookResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ManageRecords;

class ManageFlipbooks extends ManageRecords
{
    protected static string $resource = FlipbookResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
