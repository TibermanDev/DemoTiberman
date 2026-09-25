<?php

namespace App\Filament\Pages;

use App\Filament\Support\Fields;
use BackedEnum;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Tabs;
use Filament\Schemas\Components\Tabs\Tab;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;

class HomeContent extends ContentPage
{
    protected static string $settingKey = 'home';

    protected static ?string $navigationLabel = 'Beranda';

    protected static ?string $title = 'Konten Beranda';

    protected static ?int $navigationSort = 1;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedHome;

    protected function publicUrl(): string
    {
        return route('home');
    }

    public function form(Schema $schema): Schema
    {
        return $schema->components([
            Tabs::make()->persistTabInQueryString()->columnSpanFull()->tabs([
                Tab::make('Hero & SuperArea')->schema([
                    Section::make('Hero')->columns(2)->schema([
                        Fields::image('hero.image', 'Foto hero')->columnSpanFull(),
                        Fields::alt('hero.alt'),
                    ]),
                    Section::make('Blok "15 SuperArea"')
                        ->description('Peta & pin-nya tetap; keterangan tiap kota diatur di menu SuperArea.')
                        ->columns(2)->schema([
                            TextInput::make('superarea.eyebrow')->label('Baris kecil di atas judul'),
                            Fields::richText('superarea.heading', 'Judul', 2),
                            TextInput::make('superarea.button_label')->label('Label tombol'),
                            Fields::url('superarea.button_url', 'Tautan tombol'),
                        ]),
                ]),

                Tab::make('Kategori')->schema([
                    Section::make('Importir (latar video)')->schema([
                        Fields::richText('importir.heading', 'Judul', 2),
                        Grid::make(3)->schema(Fields::backgroundVideo('importir')),
                        Repeater::make('importir.pills')->label('Tombol kategori')
                            ->schema([
                                TextInput::make('label')->required(),
                                Fields::url('url')->required(),
                            ])
                            ->columns(2)->reorderable()->collapsible()
                            ->itemLabel(fn (array $state) => $state['label'] ?? null),
                    ]),
                    Section::make('Aksesoris')->columns(2)->schema([
                        Fields::richText('accessories.heading', 'Judul', 2),
                        Fields::richText('accessories.lead', 'Paragraf', 3),
                        Fields::image('accessories.image'),
                        Fields::alt('accessories.alt'),
                    ]),
                    Section::make('Kartu kategori (bergeser otomatis)')->schema([
                        Repeater::make('cards')->hiddenLabel()
                            ->schema([
                                TextInput::make('title')->label('Judul')->required(),
                                TextInput::make('subtitle')->label('Sub judul'),
                                Fields::image('image')->required(),
                                Fields::alt(),
                                Fields::url('url'),
                                TextInput::make('button_label')->label('Label tombol')->placeholder('Check it !'),
                                Toggle::make('is_tyre')->label('Gambar ban (tampilan kartu ban)'),
                            ])
                            ->columns(2)->reorderable()->collapsible()
                            ->itemLabel(fn (array $state) => trim(($state['title'] ?? '').' '.($state['subtitle'] ?? ''))),
                    ]),
                ]),

                Tab::make('Kenapa Tiberman')->schema([
                    Section::make('Judul animasi & video')->columns(2)->schema([
                        TextInput::make('why.kicker')->label('Baris kecil'),
                        TextInput::make('why.title')->label('Judul'),
                        Grid::make(3)->columnSpanFull()->schema(Fields::backgroundVideo('why')),
                    ]),
                    Section::make('Stok Aman')->columns(2)->schema([
                        TextInput::make('stock.title')->label('Judul section'),
                        TextInput::make('stock.brand_name')->label('Nama perusahaan'),
                        Fields::image('stock.brand_logo', 'Logo perusahaan'),
                        Fields::image('stock.image', 'Foto'),
                        Fields::richText('stock.heading', 'Judul kartu', 2),
                        Fields::richText('stock.body', 'Paragraf', 3),
                        TextInput::make('stock.button_label')->label('Label tombol'),
                        Fields::url('stock.button_url', 'Tautan tombol'),
                        Fields::alt('stock.alt'),
                        Repeater::make('stock.warehouses')->label('Gudang PLB')
                            ->schema([
                                TextInput::make('name')->label('Nama')->required(),
                                TextInput::make('capacity')->label('Kapasitas')->placeholder('150 kontainer'),
                                Fields::image('image')->columnSpanFull(),
                            ])
                            ->columns(2)->reorderable()->collapsible()->columnSpanFull()
                            ->itemLabel(fn (array $state) => $state['name'] ?? null),
                    ]),
                    Section::make('Pengiriman Aman')->columns(2)->schema([
                        TextInput::make('delivery.title')->label('Judul section'),
                        TextInput::make('delivery.brand_name')->label('Nama perusahaan'),
                        Fields::image('delivery.brand_logo', 'Logo perusahaan'),
                        Fields::image('delivery.image', 'Ilustrasi'),
                        Fields::richText('delivery.heading', 'Judul kartu', 2),
                        Fields::richText('delivery.body', 'Paragraf', 3),
                        TextInput::make('delivery.button_label')->label('Label tombol'),
                        Fields::url('delivery.button_url', 'Tautan tombol'),
                        Fields::alt('delivery.alt'),
                        Repeater::make('delivery.photos')->label('Dua foto di bawah kartu')
                            ->schema([Fields::image('image')->required(), Fields::alt()])
                            ->columns(2)->reorderable()->maxItems(2)->columnSpanFull(),
                    ]),
                ]),

                Tab::make('After Sales')->schema([
                    TextInput::make('aftersales.title')->label('Judul section'),
                    Repeater::make('aftersales.items')->label('Layanan (slide coverflow)')
                        ->helperText('Slide ke-3 yang tampil di tengah saat halaman dibuka.')
                        ->schema([
                            TextInput::make('title')->label('Nama layanan')->required(),
                            TextInput::make('desc')->label('Deskripsi')->required(),
                            Fields::image('image')->required(),
                            Fields::alt(),
                        ])
                        ->columns(2)->reorderable()->collapsible()
                        ->itemLabel(fn (array $state) => $state['title'] ?? null),
                ]),

                Tab::make('Testimoni')->schema([
                    Grid::make(2)->schema([
                        Fields::richText('testimonials.heading', 'Judul', 2),
                        Fields::richText('testimonials.intro', 'Paragraf pembuka', 2),
                    ]),
                    Repeater::make('testimonials.items')->label('Testimoni')
                        ->helperText('Inisial avatar dibuat otomatis dari nama.')
                        ->schema([
                            TextInput::make('name')->label('Nama')->required(),
                            TextInput::make('role')->label('Jabatan / keterangan'),
                            Textarea::make('quote')->label('Testimoni')->rows(3)->required()->columnSpanFull(),
                        ])
                        ->columns(2)->reorderable()->collapsible()
                        ->itemLabel(fn (array $state) => $state['name'] ?? null),
                ]),

                Tab::make('SEO')->columns(2)->schema(Fields::seo('/', cms('site.seo_title'))),
            ]),
        ]);
    }
}
