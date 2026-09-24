<?php

namespace App\Filament\Pages;

use App\Filament\Support\Fields;
use BackedEnum;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use UnitEnum;

class SiteSettings extends ContentPage
{
    protected static string $settingKey = 'site';

    protected static string|UnitEnum|null $navigationGroup = 'Pengaturan';

    protected static ?string $navigationLabel = 'Pengaturan Situs';

    protected static ?string $title = 'Pengaturan Situs';

    protected static ?int $navigationSort = 1;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedCog6Tooth;

    protected function publicUrl(): string
    {
        return route('home');
    }

    public function form(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('Identitas')->columns(2)->schema([
                TextInput::make('company_name')->label('Nama perusahaan')->required(),
                Fields::image('logo', 'Logo putih (navbar, hero, footer)')
                    ->helperText('PNG/WebP berlatar transparan; tampil di atas latar gelap.'),
                Fields::image('favicon', 'Ikon tab browser (favicon)')
                    ->acceptedFileTypes(['image/png', 'image/x-icon', 'image/vnd.microsoft.icon', 'image/svg+xml', 'image/webp'])
                    ->helperText('Gambar persegi, mis. 512×512 PNG. Juga dipakai sebagai ikon di panel admin.'),
                Toggle::make('favicon_round')->label('Potong favicon jadi lingkaran')->default(true)
                    ->helperText('Sudut di luar lingkaran dibuat transparan.'),
                Textarea::make('about')->label('Tentang perusahaan (footer)')->rows(3)->columnSpanFull(),
                TextInput::make('copyright')->label('Teks copyright')->columnSpanFull(),
            ]),
            Section::make('Kontak')->columns(3)->schema([
                TextInput::make('phone')->label('Nomor telepon (tampilan)')->placeholder('+62 812 8325 8200'),
                TextInput::make('whatsapp')->label('Nomor WhatsApp')
                    ->helperText('Angka saja dengan kode negara, mis. 6281283258200.')
                    ->regex('/^\d{8,15}$/'),
                TextInput::make('email')->label('Email')->email(),
            ]),
            Section::make('Media sosial & marketplace')
                ->description('Kosongkan untuk menyembunyikan ikonnya.')
                ->columns(2)->schema([
                    TextInput::make('social.facebook')->label('Facebook'),
                    TextInput::make('social.youtube')->label('YouTube'),
                    TextInput::make('social.instagram')->label('Instagram'),
                    TextInput::make('social.tiktok')->label('TikTok'),
                    TextInput::make('social.linkedin')->label('LinkedIn'),
                    TextInput::make('marketplace.tokopedia')->label('Tokopedia'),
                    TextInput::make('marketplace.shopee')->label('Shopee'),
                ]),
            Section::make('Footer — kolom Navigasi')->schema([
                Repeater::make('footer_links')->hiddenLabel()
                    ->schema([
                        TextInput::make('label')->required(),
                        Fields::url('url')->required(),
                    ])
                    ->columns(2)->reorderable()
                    ->itemLabel(fn (array $state) => $state['label'] ?? null),
            ]),
            Section::make('Title & deskripsi bawaan')
                ->description('Title = teks di tab browser & judul di Google. Dipakai halaman yang tidak mengisi title-nya sendiri; title tiap halaman diatur di tab/section SEO halaman itu (menu Halaman).')
                ->columns(2)->schema(Fields::seo()),
        ]);
    }
}
