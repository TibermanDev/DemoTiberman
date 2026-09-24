<?php

namespace App\Filament\Resources\TireSizes\Pages;

use App\Filament\Resources\TireSizes\TireSizeResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ManageRecords;

class ManageTireSizes extends ManageRecords
{
    protected static string $resource = TireSizeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
