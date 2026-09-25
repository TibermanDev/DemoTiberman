<?php

namespace App\Filament\Pages;

use App\Filament\Support\Fields;
use BackedEnum;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;

class CatalogContent extends ContentPage
{
    protected static string $settingKey = 'catalog';

    protected static ?string $navigationLabel = 'Katalog';

    protected static ?string $title = 'Konten Halaman Katalog';

    protected static ?int $navigationSort = 5;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedSquares2x2;

    protected function publicUrl(): string
    {
        return url('/kategori-produk/semua-ban');
    }

    public function form(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('Halaman katalog')
                ->description('Produk, unit, merk, dan ukuran diatur di grup menu Katalog.')
                ->columns(2)->schema([
                    Fields::image('banner', 'Banner atas'),
                    Fields::alt('banner_alt'),
                    Fields::seoDescription('seo_description'),
                ]),
        ]);
    }
}
