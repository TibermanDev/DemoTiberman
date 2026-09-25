<?php

namespace App\Filament\Resources\Posts\Schemas;

use App\Filament\Support\Fields;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Group;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Illuminate\Support\Str;

class PostForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->columns(3)
            ->components([
                Group::make()->columnSpan(2)->schema([
                    Section::make()->schema([
                        TextInput::make('title')->label('Judul')->required()->maxLength(255)
                            ->live(onBlur: true)
                            ->afterStateUpdated(fn (string $operation, $state, $set) => $operation === 'create' ? $set('slug', Str::slug($state)) : null),
                        TextInput::make('slug')->required()->alphaDash()->unique(ignoreRecord: true)->prefix('/blog/')
                            ->helperText('Samakan dengan slug blog lama (tiberman.com/blog/...) supaya URL-nya tidak putus.'),
                        Textarea::make('excerpt')->label('Ringkasan')->rows(3)
                            ->helperText('Tampil di kartu artikel & sorotan halaman News.'),
                        RichEditor::make('body')->label('Isi artikel')
                            ->fileAttachmentsDisk('public')
                            ->fileAttachmentsDirectory('posts')
                            ->fileAttachmentsVisibility('public')
                            ->extraInputAttributes(['style' => 'min-height: 28rem']),
                    ]),
                ]),
                Group::make()->schema([
                    Section::make('Terbit')->schema([
                        Toggle::make('is_published')->label('Terbit')->default(true),
                        DateTimePicker::make('published_at')->label('Tanggal terbit')->default(now())->native(false)
                            ->helperText('Tanggal di masa depan = terjadwal.'),
                        TextInput::make('author')->label('Penulis')->default('tiberman'),
                    ]),
                    Section::make('Kategori')->schema([
                        Select::make('post_category_id')->label('Kategori utama')
                            ->relationship('category', 'heading', fn ($query) => $query->ordered())
                            ->required()->preload(),
                        Select::make('tags')->label('Tag (kategori lain)')
                            ->relationship('tags', 'heading', fn ($query) => $query->ordered())
                            ->multiple()->preload(),
                        Toggle::make('is_featured')->label('Sorotan')
                            ->helperText('Sorotan terbaru jadi artikel besar di atas halaman News dan headline kategorinya.'),
                        Toggle::make('is_popular')->label('Populer')
                            ->helperText('Tampil di "Populer Bulan Ini" di samping artikel.'),
                    ]),
                    Section::make('Gambar sampul')->schema([
                        Fields::image('cover_image', 'Gambar'),
                        Fields::alt('cover_alt'),
                        TextInput::make('cover_caption')->label('Keterangan di bawah gambar'),
                    ]),
                    Section::make('SEO')->collapsed()->schema([
                        Fields::seoTitle('meta_title', null, 'Kosong = judul artikel + " — Tiberman News".'),
                        Fields::seoDescription('meta_description', 'Kosong = memakai ringkasan.'),
                        Toggle::make('noindex')->label('Sembunyikan dari Google (noindex)'),
                        Fields::seoPreview('meta_title', 'meta_description',
                            fn ($get) => '/blog/'.$get('slug'),
                            fn ($get) => $get('title') ? $get('title').' — Tiberman News' : null,
                            fn ($get) => $get('excerpt'),
                        ),
                    ]),
                ]),
            ]);
    }
}
