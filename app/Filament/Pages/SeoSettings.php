<?php

namespace App\Filament\Pages;

use App\Filament\Support\Fields;
use App\Support\Seo;
use BackedEnum;
use Filament\Forms\Components\CheckboxList;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\TimePicker;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Text;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use UnitEnum;

/**
 * Pengaturan SEO seluruh situs. Memakai baris Setting 'site' yang sama dengan
 * Pengaturan Situs & Footer (judul/deskripsi bawaan sudah tersimpan di sana
 * sebagai seo_title / seo_description); pengaturan barunya di kunci 'seo'.
 * save() di ContentPage hanya menimpa kunci yang ada di form ini.
 */
class SeoSettings extends ContentPage
{
    protected static string $settingKey = 'site';

    protected static string|UnitEnum|null $navigationGroup = 'Pengaturan';

    protected static ?string $navigationLabel = 'SEO';

    protected static ?string $title = 'SEO';

    protected static ?int $navigationSort = 3;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedMagnifyingGlass;

    protected function publicUrl(): string
    {
        return route('home');
    }

    public function form(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('Tampilan di Google')
                ->description('Nama situs dan ikon (favicon) yang muncul di atas URL, plus judul & deskripsi bawaan. Judul & deskripsi dipakai halaman yang tidak mengisi SEO-nya sendiri (tiap halaman diatur di menu Halaman).')
                ->columns(2)->schema([
                    TextInput::make('seo.site_name')->label('Nama situs')->placeholder('Tiberman')
                        ->helperText('Nama pendek merek, tanpa "PT." atau slogan. Google menampilkannya di atas URL.'),
                    TextInput::make('seo.alternate_names')->label('Nama lain (opsional)')
                        ->placeholder('Tiberman Indonesia, PT Tiga Berlian Mandiri')
                        ->helperText('Pisahkan dengan koma. Cadangan kalau Google tidak memakai nama utama.'),
                    Text::make('Favicon (ikon bulat di hasil Google) diatur di Pengaturan Situs → Identitas. Pakai gambar persegi minimal 192×192 px.')
                        ->columnSpanFull(),
                    Fields::seoTitle('seo_title', 'Tiberman'),
                    Fields::seoDescription('seo_description'),
                    Fields::seoPreview('seo_title', 'seo_description', '/', 'Tiberman'),
                    Fields::image('seo_image', 'Gambar bawaan saat link dibagikan')
                        ->columnSpanFull()
                        ->helperText('Tampil di WhatsApp, Facebook, LinkedIn, dll. untuk halaman yang tidak punya gambar sendiri. Ideal 1200×630 px.'),
                ]),

            Section::make('Profil bisnis (structured data)')
                ->description('Dibaca Google untuk logo, kontak, alamat, dan jam buka. Nama perusahaan, telepon, email, dan media sosial diambil dari Pengaturan Situs & Footer.')
                ->columns(2)->schema([
                    Select::make('seo.business_type')->label('Jenis bisnis')
                        ->options(Seo::TYPES)->default('Organization')->native(false)
                        ->helperText('Pilih "Toko ban" supaya alamat & jam buka ikut terbaca.'),
                    TextInput::make('seo.founding_year')->label('Tahun berdiri')->numeric()->minValue(1900)->maxValue(2100)->placeholder('2008'),
                    Fields::image('seo.logo', 'Logo untuk Google')
                        ->columnSpanFull()
                        ->helperText('Logo berwarna di atas latar putih/transparan, persegi, minimal 112×112 px. Logo putih navbar tidak cocok di sini. Kosong = pakai favicon.'),
                    TextInput::make('seo.address.street')->label('Alamat jalan')->placeholder('Jl. Mustika No.10, Ngagel, Kec. Wonokromo')->columnSpanFull(),
                    TextInput::make('seo.address.city')->label('Kota')->placeholder('Surabaya'),
                    TextInput::make('seo.address.region')->label('Provinsi')->placeholder('Jawa Timur'),
                    TextInput::make('seo.address.postal_code')->label('Kode pos')->placeholder('60246'),
                    Repeater::make('seo.opening_hours')->label('Jam buka')
                        ->columnSpanFull()->columns(3)->defaultItems(0)->addActionLabel('Tambah jam buka')
                        ->schema([
                            CheckboxList::make('days')->label('Hari')->options(Seo::DAYS)->columns(4)->columnSpanFull()->required(),
                            TimePicker::make('opens')->label('Buka')->seconds(false)->required(),
                            TimePicker::make('closes')->label('Tutup')->seconds(false)->required(),
                        ]),
                ]),

            Section::make('Google Search Console & Bing')
                ->description('Kode verifikasi kepemilikan situs. Di Search Console pilih metode "Tag HTML", lalu salin isi content="..." saja.')
                ->columns(2)->schema([
                    TextInput::make('seo.google_verification')->label('Google Search Console')->placeholder('abc123XYZ...'),
                    TextInput::make('seo.bing_verification')->label('Bing Webmaster Tools')->placeholder('1A2B3C...'),
                    Text::make(fn () => 'Sitemap untuk didaftarkan: '.url('sitemap.xml'))->columnSpanFull(),
                ]),

            Section::make('Pengindeksan')->schema([
                Toggle::make('seo.indexable')->label('Izinkan mesin pencari mengindeks situs ini')->default(true)
                    ->helperText('Matikan hanya untuk server uji coba/staging. Kalau mati, SELURUH situs hilang dari Google.'),
            ]),
        ]);
    }
}
