<?php

namespace App\Filament\Pages;

use App\Filament\Support\Fields;
use BackedEnum;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\TagsInput;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;

class ContactContent extends ContentPage
{
    protected static string $settingKey = 'contact';

    protected static ?string $navigationLabel = 'Contact Us';

    protected static ?string $title = 'Konten Contact Us';

    protected static ?int $navigationSort = 2;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedPhone;

    protected function publicUrl(): string
    {
        return route('contact');
    }

    public function form(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('Form "Become Our Partner"')
                ->description('Kiriman form masuk ke menu Permintaan Masuk.')
                ->columns(2)->schema([
                    TextInput::make('eyebrow')->label('Baris kecil di atas judul'),
                    Fields::richText('heading', 'Judul', 2),
                    TagsInput::make('unit_options')->label('Pilihan "Kebutuhan Unit"')->reorderable()->columnSpanFull(),
                    TextInput::make('submit_label')->label('Label tombol kirim'),
                    Fields::image('form_image', 'Gambar di samping form'),
                ]),
            Section::make('FAQ')->columns(2)->schema([
                TextInput::make('faq_heading')->label('Judul'),
                Fields::image('faq_image', 'Gambar maskot'),
                Repeater::make('faq')->label('Pertanyaan')
                    ->schema([
                        TextInput::make('question')->label('Pertanyaan')->required(),
                        Fields::richText('answer', 'Jawaban', 3)->required(),
                    ])
                    ->reorderable()->collapsible()->columnSpanFull()
                    ->itemLabel(fn (array $state) => $state['question'] ?? null),
                TextInput::make('faq_foot')->label('Teks di bawah FAQ'),
                TextInput::make('connect_label')->label('Label tombol'),
                Fields::url('connect_url', 'Tautan tombol')
                    ->helperText('Kosongkan untuk memakai nomor WhatsApp di Pengaturan Situs.'),
            ]),
            Section::make('SEO')->columns(2)->schema(Fields::seo()),
        ]);
    }
}
