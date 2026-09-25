<?php

namespace App\Filament\Pages;

use BackedEnum;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Text;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use UnitEnum;

/**
 * ID pixel/tag iklan & analitik (cms('site.tracking.*')), dipasang oleh
 * resources/views/partials/tracking-*.blade.php di semua halaman publik —
 * tidak di panel admin. Kosong = tag itu tidak dimuat sama sekali.
 *
 * Seperti Footer & SEO, memakai baris Setting 'site' tapi hanya menimpa kunci
 * 'tracking'.
 */
class TrackingSettings extends ContentPage
{
    protected static string $settingKey = 'site';

    protected static string|UnitEnum|null $navigationGroup = 'Pengaturan';

    protected static ?string $navigationLabel = 'Tracking & Iklan';

    protected static ?string $title = 'Tracking & Iklan';

    protected static ?int $navigationSort = 4;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedChartBar;

    protected function publicUrl(): string
    {
        return route('home');
    }

    public function form(Schema $schema): Schema
    {
        return $schema->components([
            Text::make('Isi ID-nya saja (bukan seluruh kode script). Kosongkan untuk mematikan. Event konversi otomatis dikirim ke semua tag yang aktif: "Lead" saat form Contact berhasil terkirim, dan "Contact" saat tombol/tautan WhatsApp diklik.'),

            Section::make('Google')->columns(2)->schema([
                TextInput::make('tracking.ga4_id')->label('Google Analytics 4 — Measurement ID')
                    ->placeholder('G-XXXXXXXXXX')->regex('/^G-[A-Z0-9]+$/')
                    ->helperText('Analytics → Admin → Data streams → Web.'),
                TextInput::make('tracking.gtm_id')->label('Google Tag Manager — Container ID')
                    ->placeholder('GTM-XXXXXXX')->regex('/^GTM-[A-Z0-9]+$/')
                    ->helperText('Opsional. Kalau GA4/Ads sudah dipasang lewat GTM, kosongkan kolom GA4 & Ads di sini supaya tidak tercatat dua kali.'),
                TextInput::make('tracking.google_ads_id')->label('Google Ads — Conversion ID')
                    ->placeholder('AW-123456789')->regex('/^AW-\d+$/')
                    ->helperText('Google Ads → Goals → Conversions → Tag setup.'),
                TextInput::make('tracking.google_ads_lead_label')->label('Google Ads — Label konversi form Contact')
                    ->placeholder('AbC-D_efG-h12_34-567')->regex('/^[\w-]+$/')
                    ->helperText('Bagian setelah "/" di send_to: AW-123456789/INI_LABELNYA.'),
                TextInput::make('tracking.google_ads_whatsapp_label')->label('Google Ads — Label konversi klik WhatsApp')
                    ->regex('/^[\w-]+$/')->helperText('Opsional.'),
            ]),

            Section::make('Meta (Facebook & Instagram Ads)')->columns(2)->schema([
                TextInput::make('tracking.meta_pixel_id')->label('Meta Pixel ID')
                    ->placeholder('123456789012345')->regex('/^\d{10,20}$/')
                    ->helperText('Events Manager → Data sources → pilih pixel → angka Pixel ID.'),
                TextInput::make('tracking.meta_domain_verification')->label('Verifikasi domain Meta')
                    ->placeholder('abc123def456...')->regex('/^[a-z0-9]+$/')
                    ->helperText('Business settings → Brand safety → Domains → Meta-tag: isi content="..." saja.'),
            ]),

            Section::make('TikTok Ads')->columns(2)->schema([
                TextInput::make('tracking.tiktok_pixel_id')->label('TikTok Pixel ID')
                    ->placeholder('C4ABCDEFGH1234567890')->regex('/^[A-Z0-9]{10,30}$/')
                    ->helperText('TikTok Ads Manager → Tools → Events → Web events.'),
            ]),

            Section::make('LinkedIn Ads')->columns(2)->schema([
                TextInput::make('tracking.linkedin_partner_id')->label('LinkedIn Insight Tag — Partner ID')
                    ->placeholder('1234567')->regex('/^\d+$/'),
                TextInput::make('tracking.linkedin_lead_conversion_id')->label('LinkedIn — Conversion ID form Contact')
                    ->placeholder('12345678')->regex('/^\d+$/')->helperText('Opsional.'),
            ]),

            Section::make('Kode kustom')
                ->description('Untuk layanan lain (Hotjar, Microsoft Clarity, chat widget, dll.). Kode ini dijalankan apa adanya di semua halaman — hanya tempel kode dari sumber resmi, kode yang salah bisa merusak tampilan situs.')
                ->collapsed()->schema([
                    Textarea::make('tracking.custom_head')->label('Sebelum </head>')->rows(5)
                        ->extraInputAttributes(['style' => 'font-family:monospace;font-size:12px']),
                    Textarea::make('tracking.custom_body')->label('Setelah <body>')->rows(5)
                        ->extraInputAttributes(['style' => 'font-family:monospace;font-size:12px']),
                ]),

            Section::make('Lainnya')->schema([
                Toggle::make('tracking.enabled')->label('Aktifkan semua tag tracking')->default(true)
                    ->helperText('Matikan sementara (mis. di server uji coba) tanpa menghapus ID-nya.'),
                Toggle::make('analytics.enabled')->label('Aktifkan analitik pengunjung bawaan')->default(true)
                    ->helperText('Mencatat kunjungan untuk menu Analitik Pengunjung. Tidak menyimpan IP atau data pribadi; data lebih dari 13 bulan dihapus otomatis.'),
            ]),
        ]);
    }
}
