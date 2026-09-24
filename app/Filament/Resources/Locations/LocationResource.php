<?php

namespace App\Filament\Resources\Locations;

use App\Filament\Resources\Locations\Pages\ManageLocations;
use App\Filament\Support\Fields;
use App\Models\Location;
use BackedEnum;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use UnitEnum;

class LocationResource extends Resource
{
    protected static ?string $model = Location::class;

    protected static string|UnitEnum|null $navigationGroup = 'Halaman';

    protected static ?string $navigationLabel = 'Lokasi SuperArea';

    protected static ?string $modelLabel = 'lokasi';

    protected static ?string $pluralModelLabel = 'lokasi SuperArea';

    protected static ?int $navigationSort = 6;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedBuildingStorefront;

    protected static ?string $recordTitleAttribute = 'name';

    /** @return array<string, string> kunci => label, dari tab pulau di Konten SuperArea */
    public static function regions(): array
    {
        return collect(cms('superarea.regions', []))->pluck('label', 'key')->all();
    }

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            TextInput::make('name')->label('Nama kota')->required(),
            TextInput::make('note')->label('Keterangan')->placeholder('Head Office'),
            Select::make('region')->label('Pulau')->options(fn () => static::regions())->required(),
            Fields::image('image', 'Foto'),
            Textarea::make('address')->label('Alamat')->rows(2)->required()->columnSpanFull(),
            Section::make('Pin peta beranda')
                ->description('Posisi pin menempel di gambar peta. Kunci pin harus sama dengan nama kota di tabel pin (JAKARTA, SURABAYA, ...); kota baru tanpa pin tercetak cukup dikosongkan.')
                ->columns(3)->collapsible()->columnSpanFull()
                ->schema([
                    TextInput::make('map_key')->label('Kunci pin')->dehydrateStateUsing(fn ($state) => $state ? strtoupper($state) : null),
                    TextInput::make('code')->label('Kode')->placeholder('01'),
                    TextInput::make('alias')->label('Singkatan')->placeholder('HO'),
                    TextInput::make('lat')->label('Latitude')->numeric(),
                    TextInput::make('lng')->label('Longitude')->numeric(),
                ]),
            Toggle::make('show_in_footer')->label('Tampil di footer')->default(true),
            Toggle::make('is_active')->label('Aktif')->default(true),
        ])->columns(2);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->reorderable('sort_order')
            ->defaultSort('sort_order')
            ->columns([
                ImageColumn::make('image')->label('')->disk('public')->square(),
                TextColumn::make('name')->label('Kota')->description(fn (Location $r) => $r->note)->searchable(),
                TextColumn::make('region')->label('Pulau')->formatStateUsing(fn ($state) => static::regions()[$state] ?? $state)->badge(),
                TextColumn::make('address')->label('Alamat')->limit(50)->wrap()->toggleable(),
                ToggleColumn::make('show_in_footer')->label('Footer'),
                ToggleColumn::make('is_active')->label('Aktif'),
            ])
            ->filters([
                SelectFilter::make('region')->label('Pulau')->options(fn () => static::regions()),
            ])
            ->recordActions([
                EditAction::make(),
                DeleteAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => ManageLocations::route('/'),
        ];
    }
}
