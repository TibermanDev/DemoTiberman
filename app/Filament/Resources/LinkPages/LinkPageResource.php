<?php

namespace App\Filament\Resources\LinkPages;

use App\Filament\Resources\LinkPages\Pages\CreateLinkPage;
use App\Filament\Resources\LinkPages\Pages\EditLinkPage;
use App\Filament\Resources\LinkPages\Pages\ListLinkPages;
use App\Filament\Support\Fields;
use App\Models\LinkPage;
use BackedEnum;
use Filament\Actions\Action;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Actions\ReplicateAction;
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
 * Linktree untuk bio media sosial: /lp/ (slug "index") dan /lp/{slug}.html
 * (URL lama, mis. /lp/bus.html).
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

    private static function pathFor(?string $slug): string
    {
        return $slug === LinkPage::INDEX ? '/lp/' : '/lp/'.$slug.'.html';
    }

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('Halaman')->columns(2)->columnSpanFull()->schema([
                TextInput::make('slug')->label('Slug')->required()->alphaDash()->unique(ignoreRecord: true)
                    ->prefix('/lp/')->suffix('.html')
                    ->helperText('Isi "index" untuk alamat /lp/. Selain itu jadi /lp/{slug}.html, mis. bus → /lp/bus.html.'),
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
                    fn (Get $get) => self::pathFor($get('slug')),
                    fn (Get $get) => $get('title').' — Tiberman'),
            ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->defaultSort('slug')
            ->columns([
                TextColumn::make('title')->label('Judul')->searchable(),
                TextColumn::make('slug')->label('Alamat')->formatStateUsing(fn ($state) => self::pathFor($state))
                    ->color('gray')->searchable()->sortable(),
                TextColumn::make('links_count')->label('Tombol')->state(fn (LinkPage $r) => count($r->links ?? []))->badge(),
                ToggleColumn::make('is_active')->label('Aktif'),
            ])
            ->recordActions([
                Action::make('open')->label('Buka')->icon(Heroicon::OutlinedArrowTopRightOnSquare)->color('gray')
                    ->url(fn (LinkPage $r) => $r->url(), shouldOpenInNewTab: true),
                ReplicateAction::make()->label('Duplikat')
                    ->beforeReplicaSaved(function (LinkPage $replica) {
                        $replica->slug .= '-salinan';
                        $replica->is_active = false;
                    }),
                EditAction::make(),
                DeleteAction::make(),
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
