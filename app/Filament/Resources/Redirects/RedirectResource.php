<?php

namespace App\Filament\Resources\Redirects;

use App\Filament\Resources\Redirects\Pages\ManageRedirects;
use App\Models\Redirect;
use BackedEnum;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use UnitEnum;

class RedirectResource extends Resource
{
    protected static ?string $model = Redirect::class;

    protected static string|UnitEnum|null $navigationGroup = 'Pengaturan';

    protected static ?string $navigationLabel = 'Redirect URL';

    protected static ?string $modelLabel = 'redirect';

    protected static ?string $pluralModelLabel = 'redirect URL';

    protected static ?int $navigationSort = 3;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedArrowUturnRight;

    protected static ?string $recordTitleAttribute = 'from_path';

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            TextInput::make('from_path')->label('Dari (URL lama)')->required()->prefix('/')
                ->unique(ignoreRecord: true)
                ->dehydrateStateUsing(fn ($state) => trim((string) $state, '/'))
                ->helperText('Tanpa domain, mis. kategori/alat-berat'),
            TextInput::make('to_path')->label('Ke')->required()
                ->helperText('Path (/blog) atau URL lengkap.'),
            Select::make('status_code')->label('Jenis')->options([
                301 => '301 — permanen (SEO ikut pindah)',
                302 => '302 — sementara',
            ])->default(301)->required(),
        ])->columns(1);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->description('Dicek sebelum halaman lain, jadi menang atas URL yang sama di situs baru.')
            ->defaultSort('from_path')
            ->columns([
                TextColumn::make('from_path')->label('Dari')->formatStateUsing(fn ($state) => '/'.$state)->searchable()->sortable(),
                TextColumn::make('to_path')->label('Ke')->searchable(),
                TextColumn::make('status_code')->label('Kode')->badge(),
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
            'index' => ManageRedirects::route('/'),
        ];
    }
}
