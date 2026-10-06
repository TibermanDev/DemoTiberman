<?php

namespace App\Filament\Pages;

use App\Filament\Support\Fields;
use BackedEnum;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;

/**
 * Halaman legal (Privacy Policy, Disclaimer): banner berjudul lalu
 * deretan bagian berjudul. Kelas turunannya cuma menentukan kunci, route,
 * dan label — keduanya dirender resources/views/legal.blade.php.
 */
abstract class LegalContent extends ContentPage
{
    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedShieldCheck;

    /** Nama route publik halaman ini. */
    abstract protected static function routeName(): string;

    protected function publicUrl(): string
    {
        return route(static::routeName());
    }

    public function form(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('Banner')->columns(2)->schema([
                TextInput::make('hero_title')->label('Judul')->required(),
                TextInput::make('hero_subtitle')->label('Teks di bawah judul')
                    ->helperText('Boleh dikosongkan.'),
            ]),
            Section::make('Isi')->schema([
                Repeater::make('sections')->hiddenLabel()
                    ->schema([
                        TextInput::make('heading')->label('Judul bagian')->required(),
                        Fields::richText('body', 'Isi', 6)
                            ->helperText('Enter = baris baru. Boleh memakai <b>, <i>, dan <a href="...">tautan</a>.'),
                    ])
                    ->reorderable()->collapsible()
                    ->addActionLabel('Tambah bagian')
                    ->itemLabel(fn (array $state) => $state['heading'] ?? null),
            ]),
            Section::make('SEO')->columns(2)->schema(Fields::seo(
                parse_url(route(static::routeName()), PHP_URL_PATH),
                static::$title.' — Tiberman',
            )),
        ]);
    }
}
