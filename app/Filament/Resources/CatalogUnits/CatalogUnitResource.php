<?php

namespace App\Filament\Resources\CatalogUnits;

use App\Filament\Resources\CatalogUnits\Pages\ManageCatalogUnits;
use App\Models\CatalogUnit;
use BackedEnum;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\TagsInput;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use UnitEnum;

class CatalogUnitResource extends Resource
{
    protected static ?string $model = CatalogUnit::class;

    protected static string|UnitEnum|null $navigationGroup = 'Katalog';

    protected static ?string $navigationLabel = 'Unit / Kategori';

    protected static ?string $modelLabel = 'unit';

    protected static ?string $pluralModelLabel = 'unit';

    protected static ?int $navigationSort = 2;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedFolder;

    protected static ?string $recordTitleAttribute = 'label';

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            TextInput::make('label')->label('Label tombol')->required(),
            TextInput::make('key')->label('Kunci')->required()->alphaDash()->unique(ignoreRecord: true)
                ->disabled(fn (?CatalogUnit $record) => $record?->key === CatalogUnit::ALL)
                ->helperText('Kunci "all" = semua unit sekaligus (dipakai halaman merk & ukuran).'),
            TextInput::make('path')->label('URL')->required()->unique(ignoreRecord: true)
                ->prefix('/')
                ->dehydrateStateUsing(fn ($state) => trim((string) $state, '/'))
                ->helperText('URL kategori toko lama, mis. kategori-produk/ban-truk'),
            TagsInput::make('aliases')->label('URL lama lain yang membuka unit ini')
                ->placeholder('kategori-produk/ban-bus')
                ->helperText('Tekan Enter setelah tiap URL.'),
            Toggle::make('show_in_nav')->label('Tampil di menu Products (navbar)')->default(true),
        ])->columns(1);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->reorderable('sort_order')
            ->defaultSort('sort_order')
            ->columns([
                TextColumn::make('label')->label('Label')->searchable(),
                TextColumn::make('path')->label('URL')->formatStateUsing(fn ($state) => '/'.$state)->color('gray'),
                TextColumn::make('products_count')->label('Produk')->counts('products'),
                IconColumn::make('show_in_nav')->label('Di navbar')->boolean(),
            ])
            ->recordActions([
                EditAction::make(),
                DeleteAction::make()
                    ->hidden(fn (CatalogUnit $record) => $record->key === CatalogUnit::ALL || $record->products()->exists()),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => ManageCatalogUnits::route('/'),
        ];
    }
}
