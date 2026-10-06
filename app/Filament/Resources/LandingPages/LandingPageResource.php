<?php

namespace App\Filament\Resources\LandingPages;

use App\Filament\Resources\LandingPages\Pages\CreateLandingPage;
use App\Filament\Resources\LandingPages\Pages\EditLandingPage;
use App\Filament\Resources\LandingPages\Pages\ListLandingPages;
use App\Filament\Support\Fields;
use App\Models\Flipbook;
use App\Models\LandingPage;
use BackedEnum;
use Filament\Actions\Action;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Actions\ReplicateAction;
use Filament\Forms\Components\Repeater;
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
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Str;
use UnitEnum;

/**
 * Landing page promo marketplace (/shopee-banjarbaru, /shopee-jabodetabek,
 * ...). Tidak ditautkan dari menu situs — dipakai untuk SEO & iklan.
 */
class LandingPageResource extends Resource
{
    protected static ?string $model = LandingPage::class;

    protected static string|UnitEnum|null $navigationGroup = 'Halaman';

    protected static ?string $navigationLabel = 'Landing Page Promo';

    protected static ?string $modelLabel = 'landing page';

    protected static ?string $pluralModelLabel = 'landing page promo';

    protected static ?int $navigationSort = 8;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedMegaphone;

    protected static ?string $recordTitleAttribute = 'title';

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('Halaman')->columns(2)->columnSpanFull()->schema([
                TextInput::make('title')->label('Nama halaman')->required()
                    ->helperText('Untuk daftar di CMS, juga judul tab bila judul SEO kosong.')
                    ->live(onBlur: true)
                    ->afterStateUpdated(fn (string $operation, $state, $set) => $operation === 'create' ? $set('slug', Str::slug($state)) : null),
                TextInput::make('slug')->label('Alamat')->required()->alphaDash()->prefix('/')
                    ->unique(ignoreRecord: true)
                    ->helperText('Tidak bisa memakai alamat halaman lain atau flipbook PDF.')
                    ->rule(fn () => function (string $attribute, $value, $fail) {
                        $taken = collect(Route::getRoutes())->contains(fn ($r) => ! $r->isFallback && trim($r->uri(), '/') === $value)
                            || Flipbook::query()->where('slug', $value)->exists();
                        if ($taken) {
                            $fail('Alamat ini sudah dipakai halaman lain.');
                        }
                    }),
                Fields::richText('heading', 'Judul besar', 2)
                    ->helperText('Logo marketplace ditaruh di akhir baris terakhir.'),
                Fields::image('logo', 'Logo marketplace')
                    ->helperText('Kosongkan untuk logo Shopee bawaan.'),
                TextInput::make('subheading')->label('Teks di bawah judul')->columnSpanFull(),
                Toggle::make('is_active')->label('Aktif')->default(true),
            ]),
            Section::make('Kartu produk')->columnSpanFull()->schema([
                Repeater::make('cards')->hiddenLabel()
                    ->schema([
                        TextInput::make('title')->label('Nama produk')->required(),
                        TextInput::make('subtitle')->label('Keterangan')
                            ->helperText('Mis. "Ukuran 7.50R16".'),
                        Fields::image('image', 'Foto'),
                        Fields::url('url', 'Tautan produk di marketplace')
                            ->helperText('Kosongkan untuk tautan toko Shopee di Pengaturan Situs.'),
                        TextInput::make('button_label')->label('Label tombol')->placeholder('Lihat Produk >>'),
                    ])
                    ->columns(2)->reorderable()->collapsible()->defaultItems(3)
                    ->addActionLabel('Tambah kartu')
                    ->itemLabel(fn (array $state) => $state['title'] ?? null),
            ]),
            Section::make('SEO')->columns(2)->columnSpanFull()->schema([
                Fields::seoTitle('meta_title'),
                Fields::seoDescription('meta_description'),
                Fields::image('meta_image', 'Gambar saat link dibagikan')->columnSpanFull()
                    ->helperText('Kosongkan untuk memakai foto kartu pertama.'),
                Toggle::make('noindex')->label('Sembunyikan halaman ini dari Google (noindex)')->columnSpanFull(),
                Fields::seoPreview('meta_title', 'meta_description', fn (Get $get) => '/'.$get('slug'), fn (Get $get) => $get('title')),
            ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('title')->label('Nama')->searchable(),
                TextColumn::make('slug')->label('Alamat')->formatStateUsing(fn ($state) => '/'.$state)->color('gray')->searchable(),
                TextColumn::make('cards_count')->label('Kartu')->state(fn (LandingPage $r) => count($r->cards ?? []))->badge(),
                ToggleColumn::make('is_active')->label('Aktif'),
            ])
            ->recordActions([
                Action::make('open')->label('Buka')->icon(Heroicon::OutlinedArrowTopRightOnSquare)->color('gray')
                    ->url(fn (LandingPage $r) => url($r->slug), shouldOpenInNewTab: true),
                ReplicateAction::make()->label('Duplikat')
                    ->beforeReplicaSaved(function (LandingPage $replica) {
                        $replica->slug .= '-salinan';
                        $replica->title .= ' (salinan)';
                        $replica->is_active = false;
                    }),
                EditAction::make(),
                DeleteAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListLandingPages::route('/'),
            'create' => CreateLandingPage::route('/create'),
            'edit' => EditLandingPage::route('/{record}/edit'),
        ];
    }
}
