<?php

namespace App\Filament\Resources\Flipbooks;

use App\Filament\Resources\Flipbooks\Pages\ManageFlipbooks;
use App\Models\Flipbook;
use BackedEnum;
use Filament\Actions\Action;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Str;
use UnitEnum;

class FlipbookResource extends Resource
{
    protected static ?string $model = Flipbook::class;

    protected static string|UnitEnum|null $navigationGroup = 'Halaman';

    protected static ?string $navigationLabel = 'Flipbook PDF';

    protected static ?string $modelLabel = 'flipbook';

    protected static ?string $pluralModelLabel = 'flipbook PDF';

    protected static ?int $navigationSort = 7;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedBookOpen;

    protected static ?string $recordTitleAttribute = 'title';

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            TextInput::make('title')->label('Judul')->required()
                ->live(onBlur: true)
                ->afterStateUpdated(fn ($state, $set, $get) => blank($get('slug')) ? $set('slug', Str::slug($state)) : null),
            TextInput::make('slug')->required()->alphaDash()->unique(ignoreRecord: true)->prefix('/')
                ->helperText('Alamat halamannya. Tidak bisa memakai alamat yang sudah dipakai halaman lain (blog, produk, kontak, ...).')
                ->rule(fn () => function (string $attribute, $value, $fail) {
                    $taken = collect(Route::getRoutes())->contains(fn ($r) => ! $r->isFallback && trim($r->uri(), '/') === $value);
                    if ($taken) {
                        $fail('Alamat ini sudah dipakai halaman lain.');
                    }
                }),
            FileUpload::make('pdf_file')->label('Unggah PDF')
                ->acceptedFileTypes(['application/pdf'])
                ->disk('public')->directory('flipbooks')->visibility('public')
                ->maxSize(51200)
                ->helperText('Maks. 50 MB. Kalau diisi, dipakai menggantikan tautan di bawah.'),
            TextInput::make('pdf_url')->label('…atau tautan PDF')
                ->helperText('Server tujuannya harus mengizinkan CORS (jsDelivr sudah).')
                ->requiredWithout('pdf_file'),
            Toggle::make('is_active')->label('Aktif')->default(true),
        ])->columns(1);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('title')->label('Judul')->searchable(),
                TextColumn::make('slug')->label('Alamat')->formatStateUsing(fn ($state) => '/'.$state)->color('gray'),
                TextColumn::make('source')->label('Sumber PDF')
                    ->state(fn (Flipbook $r) => $r->pdf_file ? 'Unggahan' : 'Tautan')->badge(),
                ToggleColumn::make('is_active')->label('Aktif'),
            ])
            ->recordActions([
                Action::make('open')->label('Buka')->icon(Heroicon::OutlinedArrowTopRightOnSquare)->color('gray')
                    ->url(fn (Flipbook $r) => url($r->slug), shouldOpenInNewTab: true),
                EditAction::make(),
                DeleteAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => ManageFlipbooks::route('/'),
        ];
    }
}
