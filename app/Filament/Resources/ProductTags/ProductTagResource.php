<?php

namespace App\Filament\Resources\ProductTags;

use App\Filament\Resources\ProductTags\Pages\CreateProductTag;
use App\Filament\Resources\ProductTags\Pages\EditProductTag;
use App\Filament\Resources\ProductTags\Pages\ListProductTags;
use App\Filament\Support\Fields;
use App\Models\ProductTag;
use BackedEnum;
use Filament\Actions\Action;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Table;
use Illuminate\Support\Str;
use UnitEnum;

/**
 * Tag produk SEO (/tag-produk/{slug}, URL toko lama). Tiap tag = halaman
 * katalog berisi produk yang dipilih manual di sini. Tag tidak ditambahkan
 * ke sidebar katalog.
 */
class ProductTagResource extends Resource
{
    protected static ?string $model = ProductTag::class;

    protected static string|UnitEnum|null $navigationGroup = 'Katalog';

    protected static ?string $navigationLabel = 'Tag Produk (SEO)';

    protected static ?string $modelLabel = 'tag produk';

    protected static ?string $pluralModelLabel = 'tag produk';

    protected static ?int $navigationSort = 9;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedHashtag;

    protected static ?string $recordTitleAttribute = 'heading';

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('Tag')->columns(2)->columnSpanFull()->schema([
                TextInput::make('slug')->label('Slug')->required()->alphaDash()->unique(ignoreRecord: true)
                    ->prefix('/tag-produk/')
                    ->helperText('Salin dari daftar URL lama, tanpa "/tag-produk/" dan garis miring, mis. ban-dump-truck-terex-tr60.')
                    ->live(onBlur: true)
                    ->afterStateUpdated(fn (string $operation, $state, Get $get, $set) => $operation === 'create' && blank($get('heading'))
                        ? $set('heading', Str::of($state)->replace('-', ' ')->toString())
                        : null),
                TextInput::make('heading')->label('Teks pencarian')->required()
                    ->helperText('Tampil sebagai: Showing all result of : “…”. Terisi otomatis dari slug, silakan dirapikan.'),
                Select::make('products')->label('Produk yang ditampilkan')
                    ->relationship('products', 'name', fn ($query) => $query->orderBy('name'))
                    ->getOptionLabelFromRecordUsing(fn ($record) => $record->name.' — '.$record->size)
                    ->multiple()->searchable()->preload()->columnSpanFull()
                    ->helperText('Hanya produk aktif yang tampil di halaman.'),
                Toggle::make('is_active')->label('Aktif')->default(true)
                    ->helperText('Nonaktif = alamatnya 404.'),
            ]),
            Section::make('SEO')->columns(2)->columnSpanFull()->schema([
                Fields::seoTitle('meta_title'),
                Fields::seoDescription('meta_description', 'Kosong = dibuat otomatis dari teks pencarian & nama produk.'),
                Toggle::make('noindex')->label('Sembunyikan halaman ini dari Google (noindex)')->columnSpanFull(),
                Fields::seoPreview('meta_title', 'meta_description',
                    fn (Get $get) => '/tag-produk/'.$get('slug'),
                    fn (Get $get) => Str::ucfirst((string) $get('heading')).' — Tiberman'),
            ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->defaultSort('slug')
            ->columns([
                TextColumn::make('heading')->label('Teks pencarian')->searchable(),
                TextColumn::make('slug')->label('Alamat')->formatStateUsing(fn ($state) => '/tag-produk/'.$state)
                    ->color('gray')->searchable()->sortable(),
                TextColumn::make('products_count')->label('Produk')->counts('products')->badge()
                    ->color(fn ($state) => $state ? 'primary' : 'danger'),
                ToggleColumn::make('is_active')->label('Aktif'),
            ])
            ->recordActions([
                Action::make('open')->label('Buka')->icon(Heroicon::OutlinedArrowTopRightOnSquare)->color('gray')
                    ->url(fn (ProductTag $r) => $r->url(), shouldOpenInNewTab: true),
                EditAction::make(),
                DeleteAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListProductTags::route('/'),
            'create' => CreateProductTag::route('/create'),
            'edit' => EditProductTag::route('/{record}/edit'),
        ];
    }
}
