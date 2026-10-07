<?php

namespace App\Filament\Resources\LinkPages\Pages;

use App\Filament\Resources\LinkPages\LinkPageResource;
use App\Models\LinkPage;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListLinkPages extends ListRecords
{
    protected static string $resource = LinkPageResource::class;

    protected function getHeaderActions(): array
    {
        // Linktree cuma satu (/lp/): tombol tambah hanya muncul kalau belum ada.
        return [
            CreateAction::make()->visible(fn () => ! LinkPage::query()->where('slug', LinkPage::INDEX)->exists()),
        ];
    }
}
