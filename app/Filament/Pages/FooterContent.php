<?php

namespace App\Filament\Pages;

use App\Filament\Support\Fields;
use BackedEnum;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use UnitEnum;

/**
 * Isi footer. Sengaja memakai baris Setting yang sama dengan Pengaturan Situs
 * ('site'): field lamanya (about, social, footer_links, ...) sudah tersimpan di
 * sana, dan save() di ContentPage hanya menimpa kunci yang ada di form ini,
 * jadi kedua halaman tidak saling menghapus isinya.
 */
class FooterContent extends ContentPage
{
    protected static string $settingKey = 'site';

    protected static string|UnitEnum|null $navigationGroup = 'Pengaturan';

    protected static ?string $navigationLabel = 'Footer';

    protected static ?string $title = 'Footer';

    protected static ?int $navigationSort = 2;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleGroup;

    protected function publicUrl(): string
    {
        return route('home').'#footer';
    }

    public function form(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('Kolom perusahaan')
                ->description('Logo dan nama perusahaan diambil dari Pengaturan Situs.')
                ->schema([
                    Textarea::make('about')->label('Tentang perusahaan')->rows(3),
                ]),
            Section::make('Judul kolom')
                ->description('Kalau diubah, tambahkan juga terjemahannya di menu Terjemahan EN / 中文.')
                ->columns(2)->schema([
                    TextInput::make('footer.contact_title')->label('Kolom kontak')->placeholder('Hubungi Kami'),
                    TextInput::make('footer.marketplace_title')->label('Kolom marketplace')->placeholder('Marketplace'),
                    TextInput::make('footer.superarea_title')->label('Kolom Super Area')->placeholder('Super Area')
                        ->helperText('Daftar lokasinya diatur di menu Lokasi (centang "tampil di footer").'),
                    TextInput::make('footer.nav_title')->label('Kolom navigasi')->placeholder('Navigasi'),
                ]),
            Section::make('Media sosial')
                ->description('Isi dengan URL lengkap. Kosongkan untuk menyembunyikan ikonnya.')
                ->columns(2)->schema([
                    TextInput::make('social.facebook')->label('Facebook'),
                    TextInput::make('social.youtube')->label('YouTube'),
                    TextInput::make('social.instagram')->label('Instagram'),
                    TextInput::make('social.tiktok')->label('TikTok'),
                    TextInput::make('social.linkedin')->label('LinkedIn'),
                ]),
            Section::make('Marketplace')->columns(2)->schema([
                TextInput::make('footer.tokopedia_label')->label('Teks tombol Tokopedia')->placeholder('tokopedia'),
                TextInput::make('marketplace.tokopedia')->label('URL Tokopedia'),
                TextInput::make('footer.shopee_label')->label('Teks tombol Shopee')->placeholder('Shopee'),
                TextInput::make('marketplace.shopee')->label('URL Shopee'),
            ]),
            Section::make('Kolom navigasi')->schema([
                Repeater::make('footer_links')->hiddenLabel()
                    ->schema([
                        TextInput::make('label')->required(),
                        Fields::url('url')->required(),
                    ])
                    ->columns(2)->reorderable()
                    ->itemLabel(fn (array $state) => $state['label'] ?? null),
            ]),
            Section::make('Copyright')->schema([
                TextInput::make('copyright')->label('Teks copyright'),
            ]),
        ]);
    }
}
