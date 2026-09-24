<?php

namespace App\Filament\Resources\PostCategories;

use App\Filament\Resources\PostCategories\Pages\ManagePostCategories;
use App\Models\PostCategory;
use BackedEnum;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Support\Str;
use UnitEnum;

class PostCategoryResource extends Resource
{
    protected static ?string $model = PostCategory::class;

    protected static string|UnitEnum|null $navigationGroup = 'Blog';

    protected static ?string $navigationLabel = 'Kategori';

    protected static ?string $modelLabel = 'kategori';

    protected static ?string $pluralModelLabel = 'kategori';

    protected static ?int $navigationSort = 2;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedFolder;

    protected static ?string $recordTitleAttribute = 'name';

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            TextInput::make('name')->label('Label tab')->required()
                ->live(onBlur: true)
                ->afterStateUpdated(fn ($state, $set, $get) => blank($get('slug')) ? $set('slug', Str::slug($state)) : null),
            TextInput::make('heading')->label('Judul section')->required()
                ->helperText('Mis. "Dunia Alat Berat" — tampil di halaman News & breadcrumb artikel.'),
            TextInput::make('slug')->required()->alphaDash()->unique(ignoreRecord: true)
                ->helperText('URL: /blog/category/{slug} — samakan dengan blog lama kalau ada.'),
        ])->columns(1);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->reorderable('sort_order')
            ->defaultSort('sort_order')
            ->columns([
                TextColumn::make('name')->label('Tab')->searchable(),
                TextColumn::make('heading')->label('Judul section'),
                TextColumn::make('slug')->color('gray'),
                TextColumn::make('posts_count')->label('Artikel')->counts('posts'),
            ])
            ->recordActions([
                EditAction::make(),
                DeleteAction::make()->hidden(fn (PostCategory $record) => $record->posts()->exists()),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => ManagePostCategories::route('/'),
        ];
    }
}
