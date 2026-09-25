<?php

namespace App\Filament\Pages;

use App\Filament\Support\Fields;
use BackedEnum;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;

class NewsContent extends ContentPage
{
    protected static string $settingKey = 'news';

    protected static ?string $navigationLabel = 'News';

    protected static ?string $title = 'Konten Halaman News';

    protected static ?int $navigationSort = 3;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedNewspaper;

    protected function publicUrl(): string
    {
        return route('blog');
    }

    public function form(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('Judul blok')
                ->description('Artikel & kategorinya diatur di grup menu Blog.')
                ->columns(3)->schema([
                    TextInput::make('latest_heading')->label('Samping sorotan')->placeholder('Latest Post'),
                    TextInput::make('popular_heading')->label('Samping artikel')->placeholder('Populer Bulan Ini'),
                    TextInput::make('related_heading')->label('Bawah artikel')->placeholder('Artikel Terkait'),
                ]),
            Section::make('SEO')->columns(2)->schema(Fields::seo('/blog', 'News — Tiberman')),
        ]);
    }
}
