<?php

namespace App\Filament\Resources\Products\Schemas;

use App\Filament\Support\Fields;
use App\Models\CatalogUnit;
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
                    Fields::image('image', 'Foto kartu (PNG transparan)')
                        ->helperText('Kosong = gambar ban bawaan.'),
                    Toggle::make('is_active')->label('Tampil di katalog')->default(true),
                ]),

                Tab::make('Halaman detail')->schema([
                    Section::make('Hero')->columns(2)->schema([
                        Fields::image('hero_image', 'Foto hero (latar gelap)'),
                        Fields::image('logo_light', 'Logo untuk latar gelap'),
                        Fields::image('logo', 'Logo untuk latar terang'),
                        Fields::richText('description', 'Deskripsi', 4),
                    ]),
                    Section::make('Kenapa Harus Ban Ini ?')
                        ->description('Keunggulan pertama juga tampil di slide ke-2 modal katalog.')
                        ->schema([
                            Repeater::make('features')->hiddenLabel()
                                ->schema([
                                    Fields::richText('title', 'Judul', 2)->required(),
                                    Textarea::make('body')->label('Penjelasan')->rows(3),
                                    Fields::image('image'),
                                    Toggle::make('contain')->label('Gambar utuh (tanpa dipotong)'),
                                ])
                                ->columns(2)->reorderable()->collapsible()
                                ->itemLabel(fn (array $state) => str_replace("\n", ' ', $state['title'] ?? '')),
                        ]),
                    Section::make('Perfect Pair For')->schema([
                        Repeater::make('pairs')->hiddenLabel()
                            ->schema([
                                TextInput::make('title')->label('Unit / medan')->required(),
                                Fields::image('image')->required(),
                            ])
                            ->columns(2)->reorderable()->grid(3)
                            ->itemLabel(fn (array $state) => $state['title'] ?? null),
                    ]),
                ]),

                Tab::make('Spesifikasi')->schema([
                    FileUpload::make('gallery')->label('Galeri foto')
                        ->image()->multiple()->reorderable()->appendFiles()
                        ->disk('public')->directory('cms')->visibility('public')
                        ->panelLayout('grid')
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
                    TextInput::make('ecatalog_url')->label('E-Katalog')->placeholder('https://…'),
                    TextInput::make('flashcard_url')->label('Flash Card')->placeholder('https://…'),
                    TextInput::make('whatsapp_url')->label('WhatsApp')
                        ->helperText('Kosong = nomor WhatsApp di Pengaturan Situs, dengan pesan berisi nama produk.'),
                    TextInput::make('shopee_url')->label('Shopee')->helperText('Kosong = tautan Shopee di Pengaturan Situs.'),
                    TextInput::make('tokopedia_url')->label('Tokopedia')->helperText('Kosong = tautan Tokopedia di Pengaturan Situs.'),
                    Textarea::make('meta_description')->label('Deskripsi meta (SEO)')->rows(2)->columnSpanFull(),
                ]),
            ]),
        ]);
    }
}
