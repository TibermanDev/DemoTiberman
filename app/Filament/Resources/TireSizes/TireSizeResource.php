<?php

namespace App\Filament\Resources\TireSizes;

use App\Filament\Resources\TireSizes\Pages\ManageTireSizes;
use App\Models\TireSize;
use BackedEnum;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Support\Str;
use UnitEnum;

class TireSizeResource extends Resource
{
    protected static ?string $model = TireSize::class;

    protected static string|UnitEnum|null $navigationGroup = 'Katalog';

    protected static ?string $navigationLabel = 'URL Ukuran';

    protected static ?string $modelLabel = 'ukuran';

    protected static ?string $pluralModelLabel = 'URL ukuran';

    protected static ?int $navigationSort = 4;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedScale;

    protected static ?string $recordTitleAttribute = 'label';

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            TextInput::make('label')->label('Ukuran')->required()->unique(ignoreRecord: true)
                ->helperText('Harus sama persis dengan kolom Ukuran di produk, mis. 11.00-20.')
                ->live(onBlur: true)
                ->afterStateUpdated(fn ($state, $set, $get) => blank($get('slug')) ? $set('slug', 'ban-'.Str::slug(str_replace(['.', '/'], '-', (string) $state))) : null),
            TextInput::make('slug')->required()->alphaDash()->unique(ignoreRecord: true)
                ->helperText('URL: /kategori-produk/ukuran-ban/{slug}'),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->description('Ukuran yang punya halaman sendiri di toko lama. Chip ukuran di katalog menjadi tautan ke URL ini.')
            ->defaultSort('label')
            ->columns([
                TextColumn::make('label')->label('Ukuran')->searchable()->sortable(),
                TextColumn::make('slug')->label('URL')->formatStateUsing(fn ($state) => '/kategori-produk/ukuran-ban/'.$state)->color('gray'),
            ])
            ->recordActions([
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([DeleteBulkAction::make()]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => ManageTireSizes::route('/'),
        ];
    }
}
