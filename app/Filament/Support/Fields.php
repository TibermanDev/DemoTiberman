<?php

namespace App\Filament\Support;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;

/** Field yang dipakai berulang di form CMS, supaya perilakunya seragam. */
class Fields
{
    /** Gambar di disk public (storage/app/public/cms), dibaca helper media(). */
    public static function image(string $name, string $label = 'Gambar'): FileUpload
    {
        return FileUpload::make($name)
            ->label($label)
            ->image()
            ->disk('public')
            ->directory('cms')
            ->visibility('public')
            ->maxSize(10240)
            ->imagePreviewHeight('120')
            ->openable()
            ->downloadable();
    }

    /** Teks yang boleh memuat <b>, <i>, <a> dan baris baru (dirender helper rich()). */
    public static function richText(string $name, string $label, int $rows = 3): Textarea
    {
        return Textarea::make($name)
            ->label($label)
            ->rows($rows)
            ->helperText('Enter = pindah baris. Boleh memakai <b>tebal</b>, <i>miring</i>, dan <a href="...">tautan</a>.');
    }

    public static function url(string $name, string $label = 'Tautan'): TextInput
    {
        return TextInput::make($name)
            ->label($label)
            ->placeholder('/kontak atau https://...')
            ->maxLength(500);
    }

    public static function alt(string $name = 'alt'): TextInput
    {
        return TextInput::make($name)
            ->label('Teks alternatif gambar')
            ->helperText('Deskripsi singkat isi gambar untuk pembaca layar & SEO.')
            ->maxLength(255);
    }

    /** @return array<TextInput|Textarea> */
    public static function seo(): array
    {
        return [
            TextInput::make('seo_title')->label('Judul halaman (tab browser & Google)')->maxLength(255),
            Textarea::make('seo_description')->label('Deskripsi meta')->rows(2)->maxLength(500),
        ];
    }
}
