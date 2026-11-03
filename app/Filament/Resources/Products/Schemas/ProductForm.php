<?php

namespace App\Filament\Resources\Products\Schemas;

use App\Filament\Support\Fields;
use App\Models\CatalogUnit;
use App\Models\Product;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TagsInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Tabs;
use Filament\Schemas\Components\Tabs\Tab;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;
use Illuminate\Support\Str;

class ProductForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            Tabs::make()->columnSpanFull()->persistTabInQueryString()->tabs([
                Tab::make('Katalog')->columns(2)->schema([
                    TextInput::make('name')->label('Nama produk')->required()
                        ->helperText('Format "MERK - SERI", mis. UNINEST - TIBERMAX 800.')
                        ->live(onBlur: true)
                        ->afterStateUpdated(fn (string $operation, $state, $get, $set) => $operation === 'create'
                            ? $set('slug', Str::slug($state.' '.str_replace(['.', '/'], '-', (string) $get('size'))))
                            : null),
                    TextInput::make('size')->label('Ukuran')->required()
                        ->helperText('Kartu dikelompokkan per ukuran. Samakan dengan menu URL Ukuran supaya chip-nya jadi tautan.')
                        ->live(onBlur: true)
                        ->afterStateUpdated(fn (string $operation, $state, $get, $set) => $operation === 'create'
                            ? $set('slug', Str::slug($get('name').' '.str_replace(['.', '/'], '-', (string) $state)))
                            : null),
                    Select::make('catalog_unit_id')->label('Unit')
                        ->relationship('unit', 'label', fn ($query) => $query->where('key', '!=', CatalogUnit::ALL)->ordered())
                        ->required()->preload(),
                    Select::make('brand_id')->label('Merk')
                        ->relationship('brand', 'name', fn ($query) => $query->ordered())
                        ->preload()
                        ->helperText('Untuk filter "Telusuri berdasarkan Merk".'),
                    TextInput::make('compat')->label('Cocok untuk')->placeholder('Dump Truck'),
                    TextInput::make('slug')->required()->alphaDash()->unique(ignoreRecord: true)->prefix('/produk/'),
                    Fields::image('image', 'Foto kartu (PNG transparan)')->hint(Fields::size('2:3', 800))
                        ->helperText('Kosong = gambar ban bawaan.'),
                    Toggle::make('is_active')->label('Tampil di katalog')->default(true),
                ]),

                Tab::make('Halaman detail')->schema([
                    Section::make('Hero')->columns(2)->schema([
                        Fields::image('hero_image', 'Foto hero (latar gelap)')->hint(Fields::size('3:2', 1400)),
                        Fields::image('logo_light', 'Logo untuk latar gelap')->hint(Fields::size('6:1', 1340)),
                        Fields::image('logo', 'Logo untuk latar terang')->hint(Fields::size('6:1', 1000)),
                        Fields::richText('description', 'Deskripsi', 4),
                    ]),
                    Section::make('Kenapa Harus Ban Ini ?')
                        ->description('Satu keunggulan yang disorot juga tampil di slide ke-2 popup katalog.')
                        ->schema([
                            Repeater::make('features')->hiddenLabel()
                                ->schema([
                                    Fields::richText('title', 'Judul', 2)->required(),
                                    Textarea::make('body')->label('Penjelasan')->rows(3),
                                    Fields::image('image')->hint(Fields::size('16:10', 1400)),
                                    Toggle::make('popup')->label('Sorot di popup katalog')
                                        ->helperText('Tampil di slide ke-2 popup. Tidak ada yang ditandai = keunggulan paling atas.')
                                        ->live()
                                        // hanya satu yang disorot: menandai item ini melepas tanda di item lain
                                        ->afterStateUpdated(function (?bool $state, Toggle $component, Get $get, Set $set) {
                                            if (! $state) {
                                                return;
                                            }
                                            $key = Str::of($component->getStatePath())->beforeLast('.')->afterLast('.')->toString();
                                            $items = $get('../../features') ?? [];
                                            foreach ($items as $k => $item) {
                                                $items[$k]['popup'] = (string) $k === $key;
                                            }
                                            $set('../../features', $items);
                                        }),
                                    Select::make('popup_position')->label('Letak gambar di popup')
                                        ->options(Product::POPUP_POSITIONS)
                                        ->default('bottom')->selectablePlaceholder(false)
                                        ->visible(fn (Get $get) => (bool) $get('popup')),
                                ])
                                ->columns(2)->reorderable()->collapsible()
                                ->itemLabel(fn (array $state) => str_replace("\n", ' ', $state['title'] ?? '')),
                        ]),
                    Section::make('Perfect Pair For')->schema([
                        Repeater::make('pairs')->hiddenLabel()
                            ->schema([
                                TextInput::make('title')->label('Unit / medan')->required(),
                                Fields::image('image')->hint(Fields::size('4:3', 1200))->required(),
                            ])
                            ->columns(2)->reorderable()->grid(3)
                            ->itemLabel(fn (array $state) => $state['title'] ?? null),
                    ]),
                ]),

                Tab::make('Spesifikasi')->schema([
                    FileUpload::make('gallery')->label('Galeri foto')->hint(Fields::size('1:1', 1200))
                        ->image()->multiple()->reorderable()->appendFiles()
                        ->disk('public')->directory('cms')->visibility('public')
                        ->panelLayout('grid')->openable()
                        ->helperText('Foto pertama jadi gambar utama. Kosong = foto kartu.'),
                    Repeater::make('specs')->label('Tabel spesifikasi')
                        ->schema([
                            TextInput::make('label')->label('Nama')->required(),
                            TextInput::make('value')->label('Nilai')->required(),
                        ])
                        ->columns(2)->reorderable()->grid(2)->defaultItems(0),
                    TagsInput::make('available_sizes')->label('Available size')
                        ->helperText('Kosong = ukuran produk ini saja.')->reorderable(),
                ]),

                Tab::make('Tautan')->columns(2)->schema([
                    TextInput::make('ecatalog_url')->label('E-Katalog')->placeholder('https://…')
                        ->helperText('Kosong = flipbook katalog di /katalog.'),
                    Fields::image('flashcard_image', 'Flash Card (JPG)')->hint(Fields::size('1:1', 1080))
                        ->helperText('Tampil di popup saat tombol Flash Card diklik. Kosong = tombolnya disembunyikan.'),
                    TextInput::make('whatsapp_url')->label('WhatsApp')
                        ->helperText('Kosong = nomor WhatsApp di Pengaturan Situs, dengan pesan berisi nama produk.'),
                    TextInput::make('shopee_url')->label('Shopee')->helperText('Kosong = tautan Shopee di Pengaturan Situs.'),
                    TextInput::make('tokopedia_url')->label('Tokopedia')->helperText('Kosong = tautan Tokopedia di Pengaturan Situs.'),
                ]),

                Tab::make('SEO')->schema([
                    Fields::seoTitle('meta_title', null, 'Kosong = nama + ukuran produk + " — Tiberman".'),
                    Fields::seoDescription('meta_description', 'Kosong = diambil dari deskripsi produk.'),
                    Toggle::make('noindex')->label('Sembunyikan dari Google (noindex)'),
                    Fields::seoPreview('meta_title', 'meta_description',
                        fn ($get) => '/produk/'.$get('slug'),
                        fn ($get) => trim($get('name').' '.$get('size')).' — Tiberman',
                    ),
                ]),
            ]),
        ]);
    }
}
