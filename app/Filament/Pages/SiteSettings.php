<?php

namespace App\Filament\Pages;

use App\Filament\Support\Fields;
use BackedEnum;
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
                    ->helperText('Gambar persegi minimal 192×192 px (mis. 512×512 PNG). Tampil di tab browser, di samping nama situs pada hasil Google, dan di panel admin.'),
                Toggle::make('favicon_round')->label('Potong favicon jadi lingkaran')->default(true)
                    ->helperText('Sudut di luar lingkaran dibuat transparan.'),
            ]),
            Section::make('Kontak')->columns(3)->schema([
                TextInput::make('phone')->label('Nomor telepon (tampilan)')->placeholder('+62 812 8325 8200'),
                TextInput::make('whatsapp')->label('Nomor WhatsApp')
                    ->helperText('Angka saja dengan kode negara, mis. 6281283258200.')
                    ->regex('/^\d{8,15}$/'),
                TextInput::make('email')->label('Email')->email(),
            ]),
        ]);
    }
}
