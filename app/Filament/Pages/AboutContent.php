<?php

namespace App\Filament\Pages;

use App\Filament\Support\Fields;
use BackedEnum;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;

class AboutContent extends ContentPage
{
    protected static string $settingKey = 'about';

    protected static ?string $navigationLabel = 'About Us';

    protected static ?string $title = 'Konten About Us';

    protected static ?int $navigationSort = 2;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedBuildingOffice2;

    protected function publicUrl(): string
    {
        return route('about');
    }

    public function form(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('Banner')->columns(2)->schema([
                TextInput::make('hero_eyebrow')->label('Baris kecil di atas judul'),
                Fields::richText('hero_heading', 'Judul', 2),
                Fields::image('hero_background', 'Gambar latar (langit-langit gudang)'),
                Fields::image('hero_image', 'Foto tim (PNG/WebP transparan)')
                    ->helperText('Bagian atas gambar harus transparan supaya latar di belakangnya terlihat.'),
                TextInput::make('hero_button_label')->label('Label tombol'),
                Fields::url('hero_button_url', 'Tautan tombol')
                    ->helperText('Mis. /company-profile (flipbook) atau tautan file PDF.'),
            ]),
            Section::make('Angka')->schema([
                Repeater::make('stats')->label('Kolom')
                    ->schema([
                        Fields::richText('title', 'Judul', 2)
                            ->helperText('Bagian yang ditebalkan (<strong>) tampil merah.'),
                        Fields::richText('text', 'Keterangan', 3),
                    ])
                    ->columns(2)->reorderable()->maxItems(3)
                    ->itemLabel(fn (array $state) => strip_tags($state['title'] ?? '') ?: null),
            ]),
            Section::make('Visi & Misi')->columns(2)->schema([
                TextInput::make('vision_heading')->label('Judul visi'),
                TextInput::make('mission_heading')->label('Judul misi'),
                Fields::richText('vision_text', 'Visi', 3),
                Fields::richText('mission_text', 'Misi', 3),
                Fields::image('mascot_image', 'Gambar maskot'),
            ]),
            Section::make('Milestone')->columns(2)->schema([
                TextInput::make('milestone_eyebrow')->label('Baris kecil di atas judul'),
                Fields::richText('milestone_heading', 'Judul', 1)
                    ->helperText('Bagian yang ditebalkan (<strong>) tampil merah.'),
                Repeater::make('milestones')->label('Tahapan')
                    ->helperText('Tahapan terakhir ditandai merah.')
                    ->schema([
                        TextInput::make('label')->label('Tahun / label')->required(),
                        Fields::richText('text', 'Keterangan', 3),
                    ])
                    ->reorderable()->collapsible()->columnSpanFull()
                    ->itemLabel(fn (array $state) => $state['label'] ?? null),
                Fields::image('milestone_image', 'Gambar di bawah milestone (PNG/WebP transparan)'),
            ]),
            Section::make('Ajakan di bawah')->columns(2)->schema([
                TextInput::make('cta_heading')->label('Judul kiri'),
                Fields::richText('cta_title', 'Judul kanan', 2),
                Fields::richText('cta_text', 'Teks kiri', 3),
                TextInput::make('cta_button_label')->label('Label tombol'),
                Fields::url('cta_button_url', 'Tautan tombol')
                    ->helperText('Kosongkan untuk memakai nomor WhatsApp di Pengaturan Situs.'),
            ]),
            Section::make('SEO')->columns(2)->schema(Fields::seo('/tentang-kami', 'About Us — Tiberman')),
        ]);
    }
}
