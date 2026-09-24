<?php

namespace App\Filament\Resources\CatalogUnits\Pages;

use App\Filament\Resources\CatalogUnits\CatalogUnitResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ManageRecords;

class ManageCatalogUnits extends ManageRecords
{
    protected static string $resource = CatalogUnitResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
