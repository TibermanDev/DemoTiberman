<?php

namespace App\Filament\Pages;

use App\Filament\Support\Fields;
use BackedEnum;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;

class AfterSalesContent extends ContentPage
{
    protected static string $settingKey = 'aftersales';

    protected static ?string $navigationLabel = 'After Sales';

    protected static ?string $title = 'Konten After Sales';

    protected static ?int $navigationSort = 2;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedWrenchScrewdriver;

    protected function publicUrl(): string
    {
        return route('aftersales');
    }

    public function form(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('Banner')
                ->description('Deretan foto melengkung dan ikon bulat di banner diambil otomatis dari daftar Layanan & Fasilitas di bawah.')
                ->columns(2)->schema([
                    TextInput::make('hero_eyebrow')->label('Baris kecil di atas judul'),
                    Fields::richText('hero_heading', 'Judul', 2),
                    Fields::richText('hero_subheading', 'Teks di atas ikon', 2),
                ]),
            Section::make('Layanan')->columns(2)->schema([
                Fields::richText('services_heading', 'Judul', 2)
                    ->helperText('Bagian yang ditebalkan (<strong>) tampil merah.')
                    ->columnSpanFull(),
                $this->items('services', 'Kartu layanan (3 kolom)'),
            ]),
            Section::make('Fasilitas')->columns(2)->schema([
                Fields::richText('facilities_heading', 'Judul', 2)
                    ->helperText('Bagian yang ditebalkan (<strong>) tampil merah.')
                    ->columnSpanFull(),
                $this->items('facilities', 'Kartu fasilitas (2 kolom)'),
                TextInput::make('button_label')->label('Label tombol di setiap kartu')
                    ->helperText('Tautannya diatur per kartu; kosong = WhatsApp dengan pesan otomatis.'),
            ]),
            Section::make('FAQ After Sales')
                ->description('Terpisah dari FAQ di halaman Contact Us.')
                ->columns(2)->schema([
                    TextInput::make('faq_heading')->label('Judul'),
                    Fields::image('faq_image', 'Gambar maskot'),
                    Repeater::make('faq')->label('Pertanyaan')
                        ->schema([
                            TextInput::make('question')->label('Pertanyaan')->required(),
                            Fields::richText('answer', 'Jawaban', 3)->required(),
                        ])
                        ->reorderable()->collapsible()->columnSpanFull()
                        ->itemLabel(fn (array $state) => $state['question'] ?? null),
                    TextInput::make('faq_foot')->label('Teks di bawah FAQ'),
                    TextInput::make('connect_label')->label('Label tombol'),
                    Fields::url('connect_url', 'Tautan tombol')
                        ->helperText('Kosongkan untuk memakai nomor WhatsApp di Pengaturan Situs.'),
                ]),
            Section::make('SEO')->columns(2)->schema(Fields::seo('/after-sales-service', 'After Sales — Tiberman')),
        ]);
    }

    /** Kartu layanan/fasilitas: foto untuk kartu & banner, ikon untuk deret ikon di banner. */
    private function items(string $name, string $label): Repeater
    {
        return Repeater::make($name)->label($label)
            ->schema([
                TextInput::make('name')->label('Nama layanan')->required()
                    ->helperText('Ditampilkan setelah kata "Tiberman", mis. "Tyre Lab".'),
                Fields::richText('desc', 'Deskripsi', 2),
                Fields::image('image', 'Foto'),
                Fields::image('icon', 'Ikon bulat (banner)'),
                TextInput::make('alt')->label('Teks alternatif foto'),
                Fields::url('button_url', 'Tautan tombol')
                    ->helperText('Kosongkan untuk WhatsApp dengan pesan otomatis.'),
            ])
            ->columns(2)->reorderable()->collapsible()->columnSpanFull()
            ->itemLabel(fn (array $state) => filled($state['name'] ?? null) ? 'Tiberman '.$state['name'] : null);
    }
}
