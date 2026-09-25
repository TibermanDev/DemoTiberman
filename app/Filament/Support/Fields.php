<?php

namespace App\Filament\Support;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Components\View;

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

    /**
     * Field SEO satu halaman + pratinjau tampilannya di hasil Google.
     *
     * Batas 60/160 karakter itu kira-kira lebar yang ditampilkan Google di
     * desktop; lebih dari itu teksnya dipotong jadi "..." (seperti judul
     * "... Dump Truck ..." di hasil pencarian sekarang).
     *
     * @param  string  $path  URL halaman ini, untuk pratinjau
     * @param  string|null  $fallbackTitle  judul yang dipakai kalau kolomnya kosong
     * @return array<int, mixed>
     */
    public static function seo(string $path = '/', ?string $fallbackTitle = null): array
    {
        return [
            static::seoTitle('seo_title', $fallbackTitle),
            static::seoDescription('seo_description'),
            static::image('seo_image', 'Gambar saat link dibagikan (WhatsApp, Facebook, LinkedIn)')
                ->columnSpanFull()
                ->helperText('Ideal 1200×630 px. Kosongkan untuk memakai gambar bawaan di menu SEO.'),
            Toggle::make('seo_noindex')->label('Sembunyikan halaman ini dari Google (noindex)')
                ->columnSpanFull()
                ->helperText('Halaman tetap bisa dibuka, hanya tidak dimunculkan di hasil pencarian.'),
            static::seoPreview('seo_title', 'seo_description', $path, $fallbackTitle),
        ];
    }

    public static function seoTitle(string $name, ?string $fallback = null, string $hint = 'Taruh kata kunci utama di depan.'): TextInput
    {
        return TextInput::make($name)->label('Judul di Google & tab browser')
            ->maxLength(255)->live(onBlur: true)->columnSpanFull()
            ->placeholder($fallback)
            ->helperText(fn (?string $state) => static::charCount($state, 60).' '.$hint);
    }

    public static function seoDescription(string $name, string $hint = 'Ringkasan isi halaman yang mengundang klik.'): Textarea
    {
        return Textarea::make($name)->label('Deskripsi di Google')
            ->rows(2)->maxLength(500)->live(onBlur: true)->columnSpanFull()
            ->helperText(fn (?string $state) => static::charCount($state, 160).' '.$hint);
    }

    /**
     * Kotak pratinjau hasil Google (resources/views/filament/seo-preview.blade.php).
     *
     * $path, $fallbackTitle, dan $fallbackDescription boleh berupa closure(Get)
     * untuk nilai yang bergantung isian lain (slug, judul artikel, ringkasan).
     */
    public static function seoPreview(
        string $titleField,
        string $descriptionField,
        string|\Closure $path,
        string|\Closure|null $fallbackTitle = null,
        string|\Closure|null $fallbackDescription = null,
    ): View {
        $value = fn ($v, Get $get) => $v instanceof \Closure ? $v($get) : $v;

        return View::make('filament.seo-preview')
            ->columnSpanFull()
            ->viewData(fn (Get $get) => [
                'title' => $get($titleField) ?: $value($fallbackTitle, $get),
                'description' => $get($descriptionField) ?: $value($fallbackDescription, $get),
                'url' => url($value($path, $get)),
            ]);
    }

    private static function charCount(?string $state, int $max): string
    {
        $n = mb_strlen((string) $state);

        return $n.' / '.$max.' karakter'.($n > $max ? ' — kepanjangan, akan dipotong Google.' : '.');
    }
}
