<?php

namespace App\Filament\Resources\PromoPages;

use App\Filament\Resources\PromoPages\Pages\CreatePromoPage;
use App\Filament\Resources\PromoPages\Pages\EditPromoPage;
use App\Filament\Resources\PromoPages\Pages\ListPromoPages;
use App\Filament\Support\Fields;
use App\Models\Product;
use App\Models\PromoPage;
use App\Support\PageSlug;
use BackedEnum;
use Filament\Actions\Action;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Actions\ReplicateAction;
use Filament\Forms\Components\Builder;
use Filament\Forms\Components\Builder\Block;
use Filament\Forms\Components\Radio;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\RichEditor;
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
 * Halaman promosi/artikel SEO (/aeolus-tyre, /promo-tiberman, ...). Isinya
 * disusun dari blok: teks, gambar + teks, kartu produk, dan gambar penuh.
 * Tidak ditautkan dari menu situs.
 */
class PromoPageResource extends Resource
{
    protected static ?string $model = PromoPage::class;

    protected static string|UnitEnum|null $navigationGroup = 'Halaman';

    protected static ?string $navigationLabel = 'Halaman Promo';

    protected static ?string $modelLabel = 'halaman promo';

    protected static ?string $pluralModelLabel = 'halaman promo';

    protected static ?int $navigationSort = 9;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedSparkles;

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
                    ->rule(fn (?PromoPage $record) => PageSlug::rule($record))
                    ->helperText('Tidak bisa memakai alamat halaman lain, flipbook PDF, atau landing page.'),
                Fields::image('banner_image', 'Banner atas')
                    ->helperText('Ditampilkan utuh (tidak dipotong). Disarankan lebar ±1600 px.'),
                TextInput::make('banner_alt')->label('Teks alternatif banner'),
                Toggle::make('is_active')->label('Aktif')->default(true)
                    ->helperText('Nonaktif = alamatnya 404.'),
            ]),

            Section::make('Isi halaman')
                ->description('Judul blok pertama tampil sebagai H1 halaman; judul blok berikutnya H2.')
                ->columnSpanFull()->schema([
                    Builder::make('blocks')->hiddenLabel()
                        ->blocks([
                            Block::make('text')->label('Teks')->icon(Heroicon::OutlinedDocumentText)->schema([
                                TextInput::make('heading')->label('Judul'),
                                static::editor('body'),
                            ]),
                            Block::make('image_text')->label('Gambar + teks')->icon(Heroicon::OutlinedPhoto)->columns(2)->schema([
                                Fields::image('image', 'Gambar')->required(),
                                Radio::make('image_side')->label('Posisi gambar')->inline()
                                    ->options(['left' => 'Kiri', 'right' => 'Kanan'])->default('left'),
                                TextInput::make('alt')->label('Teks alternatif gambar')->columnSpanFull(),
                                static::editor('body')->columnSpanFull(),
                            ]),
                            Block::make('products')->label('Kartu produk')->icon(Heroicon::OutlinedSquares2x2)->schema([
                                TextInput::make('button_label')->label('Label tombol')->placeholder('Lihat Produk >>'),
                                Repeater::make('cards')->label('Kartu')
                                    ->schema([
                                        Select::make('product_id')->label('Ambil dari katalog')
                                            ->options(fn () => Product::query()->orderBy('name')->pluck('name', 'id'))
                                            ->searchable()
                                            ->helperText('Opsional. Gambar & tautan kosong = flashcard/foto & halaman produk ini.'),
                                        Fields::image('image', 'Gambar kartu')
                                            ->required(fn (Get $get) => blank($get('product_id'))),
                                        Fields::url('url', 'Tautan tombol'),
                                        TextInput::make('label')->label('Label tombol khusus'),
                                        TextInput::make('alt')->label('Teks alternatif gambar')->columnSpanFull(),
                                    ])
                                    ->columns(2)->reorderable()->collapsible()->grid(1)
                                    ->addActionLabel('Tambah kartu')
                                    ->itemLabel(fn (array $state) => filled($state['product_id'] ?? null)
                                        ? Product::query()->find($state['product_id'])?->name
                                        : ($state['url'] ?? null)),
                            ]),
                            Block::make('image')->label('Gambar penuh')->icon(Heroicon::OutlinedPhoto)->columns(2)->schema([
                                Fields::image('image', 'Gambar')->required(),
                                TextInput::make('alt')->label('Teks alternatif'),
                                Fields::url('url', 'Tautan (opsional)'),
                            ]),
                        ])
                        ->blockNumbers(false)->collapsible()->reorderable()
                        ->addActionLabel('Tambah blok'),
                ]),

            Section::make('Ajakan di bawah')
                ->description('Pita merah dengan maskot dan tombol di akhir halaman. Kosongkan judul untuk menyembunyikannya.')
                ->columns(2)->columnSpanFull()->schema([
                    TextInput::make('cta_heading')->label('Judul'),
                    Fields::image('cta_image', 'Maskot')->helperText('Kosongkan untuk panda bawaan.'),
                    TextInput::make('cta_label')->label('Label tombol')
                        ->helperText('Kosongkan untuk nomor telepon di Pengaturan Situs.'),
                    Fields::url('cta_url', 'Tautan tombol')
                        ->helperText('Kosongkan untuk WhatsApp di Pengaturan Situs.'),
                ]),

            Section::make('SEO')->columns(2)->columnSpanFull()->schema([
                Fields::seoTitle('meta_title'),
                Fields::seoDescription('meta_description'),
                Fields::image('meta_image', 'Gambar saat link dibagikan')->columnSpanFull()
                    ->helperText('Kosongkan untuk memakai banner atas.'),
                Toggle::make('noindex')->label('Sembunyikan halaman ini dari Google (noindex)')->columnSpanFull(),
                Fields::seoPreview('meta_title', 'meta_description', fn (Get $get) => '/'.$get('slug'), fn (Get $get) => $get('title')),
            ]),
        ]);
    }

    private static function editor(string $name): RichEditor
    {
        return RichEditor::make($name)->label('Isi')
            ->toolbarButtons([['bold', 'italic', 'underline', 'link'], ['h3', 'bulletList', 'orderedList', 'blockquote'], ['undo', 'redo']])
            ->extraInputAttributes(['style' => 'min-height: 12rem']);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('title')->label('Nama')->searchable(),
                TextColumn::make('slug')->label('Alamat')->formatStateUsing(fn ($state) => '/'.$state)->color('gray')->searchable(),
                TextColumn::make('blocks_count')->label('Blok')->state(fn (PromoPage $r) => count($r->blocks ?? []))->badge(),
                ToggleColumn::make('is_active')->label('Aktif'),
                TextColumn::make('updated_at')->label('Diubah')->since()->color('gray'),
            ])
            ->recordActions([
                Action::make('open')->label('Buka')->icon(Heroicon::OutlinedArrowTopRightOnSquare)->color('gray')
                    ->url(fn (PromoPage $r) => url($r->slug), shouldOpenInNewTab: true),
                ReplicateAction::make()->label('Duplikat')
                    ->beforeReplicaSaved(function (PromoPage $replica) {
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
            'index' => ListPromoPages::route('/'),
            'create' => CreatePromoPage::route('/create'),
            'edit' => EditPromoPage::route('/{record}/edit'),
        ];
    }
}
