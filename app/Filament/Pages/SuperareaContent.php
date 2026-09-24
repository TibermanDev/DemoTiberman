<?php

namespace App\Filament\Pages;

use App\Filament\Support\Fields;
use BackedEnum;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;

class SuperareaContent extends ContentPage
{
    protected static string $settingKey = 'superarea';

    protected static ?string $navigationLabel = 'SuperArea';

    protected static ?string $title = 'Konten Halaman SuperArea';

    protected static ?int $navigationSort = 4;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedMapPin;

    protected function publicUrl(): string
    {
        return route('superarea');
    }

    public function form(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('Halaman')
                ->description('Daftar kota & alamatnya diatur di menu Lokasi SuperArea.')
                ->schema([
                    TextInput::make('kicker')->label('Teks di bawah logo'),
                    Repeater::make('regions')->label('Tab pulau')
                        ->helperText('Kunci dipakai untuk mengelompokkan lokasi; tab pertama terbuka saat halaman dimuat.')
                        ->schema([
                            TextInput::make('key')->label('Kunci')->required()->alphaDash(),
                            TextInput::make('label')->label('Label')->required(),
                        ])
                        ->columns(2)->reorderable()
                        ->itemLabel(fn (array $state) => $state['label'] ?? null),
                ]),
            Section::make('SEO')->columns(2)->schema(Fields::seo()),
        ]);
    }
}
