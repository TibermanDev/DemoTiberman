<?php

namespace App\Filament\Support;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Utilities\Set;

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

    /**
     * Video latar di disk public. Satu sumber MP4 (H.264) wajib karena hanya
     * format itu yang pasti jalan di semua browser, termasuk Safari/iPhone.
     */
    public static function video(string $name, string $label = 'Video (MP4)'): FileUpload
    {
        return FileUpload::make($name)
            ->label($label)
            ->acceptedFileTypes(['video/mp4'])
            ->disk('public')
            ->directory('cms/video')
            ->visibility('public')
            ->maxSize(51200)
            ->openable()
            ->downloadable()
            ->helperText('Format MP4, maks. 50 MB. Disarankan ≤ 10 MB (1920×1080, tanpa suara) supaya halaman tetap cepat.');
    }

    /**
     * Versi WebM opsional dari video yang sama: jauh lebih kecil, diputar lebih
     * dulu oleh Chrome/Edge/Firefox.
     */
    public static function webm(string $name): FileUpload
    {
        return static::video($name, 'Video WebM (opsional)')
            ->acceptedFileTypes(['video/webm'])
            ->helperText('Versi lebih ringan dari video yang SAMA. Otomatis dikosongkan saat video MP4 diganti.');
    }

    /**
     * MP4 + WebM + sampul untuk satu video latar. Mengganti MP4 mengosongkan
     * WebM, karena browser memutar WebM lebih dulu — kalau tertinggal, video
     * lama yang tetap tampil.
     *
     * @return array<FileUpload>
     */
    public static function backgroundVideo(string $prefix): array
    {
        return [
            static::video($prefix.'.video')
                ->live()
                ->afterStateUpdated(fn (Set $set) => $set($prefix.'.video_webm', null)),
            static::webm($prefix.'.video_webm'),
            static::image($prefix.'.poster', 'Gambar sampul video')
                ->helperText('Tampil selama video dimuat. Ambil satu frame dari videonya.'),
        ];
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
