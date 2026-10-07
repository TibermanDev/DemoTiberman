<?php

namespace App\Filament\Resources\LinkPages;

use App\Filament\Resources\LinkPages\Pages\CreateLinkPage;
use App\Filament\Resources\LinkPages\Pages\EditLinkPage;
use App\Filament\Resources\LinkPages\Pages\ListLinkPages;
use App\Filament\Support\Fields;
use App\Models\LinkPage;
use BackedEnum;
use Filament\Actions\Action;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Table;
use UnitEnum;

/**
 * Linktree untuk bio media sosial — hanya satu halaman, /lp/ (slug "index").
 * Tidak bisa menambah/duplikat/hapus: /lp/{slug}.html diarahkan ke landing
 * page promo oleh LinkPageController.
 */
class LinkPageResource extends Resource
{
    protected static ?string $model = LinkPage::class;

    protected static string|UnitEnum|null $navigationGroup = 'Halaman';

    protected static ?string $navigationLabel = 'Linktree (/lp)';

    protected static ?string $modelLabel = 'linktree';

    protected static ?string $pluralModelLabel = 'linktree';

    protected static ?int $navigationSort = 10;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedLink;

    protected static ?string $recordTitleAttribute = 'title';

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('Halaman')->columns(2)->columnSpanFull()->schema([
                Hidden::make('slug')->default(LinkPage::INDEX),
                TextInput::make('title')->label('Judul')->required()->default('Tiberman'),
                TextInput::make('subtitle')->label('Teks di bawah judul')->columnSpanFull(),
                Fields::image('avatar', 'Foto profil (bulat)')
                    ->helperText('Kosongkan untuk logo Tiberman.'),
                Toggle::make('is_active')->label('Aktif')->default(true)
                    ->helperText('Nonaktif = alamatnya 404.'),
            ]),
            Section::make('Tombol')->columnSpanFull()->schema([
                Repeater::make('links')->hiddenLabel()
                    ->schema([
                        TextInput::make('label')->label('Teks tombol')->required(),
                        Fields::url('url', 'Tautan')->required()
                            ->helperText('Boleh tautan luar (https://...) atau halaman situs (/kontak).'),
                        Select::make('icon')->label('Ikon')->options(LinkPage::ICONS)->default('link')->required(),
                        Toggle::make('highlight')->label('Sorot (tombol merah)')->inline(false),
                    ])
                    ->columns(4)->reorderable()->collapsible()
                    ->addActionLabel('Tambah tombol')
                    ->itemLabel(fn (array $state) => $state['label'] ?? null),
            ]),
            Section::make('SEO')->columns(2)->columnSpanFull()->schema([
                Fields::seoTitle('meta_title'),
                Fields::seoDescription('meta_description', 'Kosong = teks di bawah judul.'),
                Toggle::make('noindex')->label('Sembunyikan halaman ini dari Google (noindex)')->columnSpanFull(),
                Fields::seoPreview('meta_title', 'meta_description',
                    fn () => '/lp/',
                    fn (Get $get) => $get('title').' — Tiberman'),
            ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->paginated(false)
            ->columns([
                TextColumn::make('title')->label('Judul'),
                TextColumn::make('slug')->label('Alamat')->formatStateUsing(fn () => '/lp/')->color('gray'),
                TextColumn::make('links_count')->label('Tombol')->state(fn (LinkPage $r) => count($r->links ?? []))->badge(),
                ToggleColumn::make('is_active')->label('Aktif'),
            ])
            ->recordActions([
                Action::make('open')->label('Buka')->icon(Heroicon::OutlinedArrowTopRightOnSquare)->color('gray')
                    ->url(fn (LinkPage $r) => $r->url(), shouldOpenInNewTab: true),
                EditAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListLinkPages::route('/'),
            'create' => CreateLinkPage::route('/create'),
            'edit' => EditLinkPage::route('/{record}/edit'),
        ];
    }
}
