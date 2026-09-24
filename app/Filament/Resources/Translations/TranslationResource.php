<?php

namespace App\Filament\Resources\Translations;

use App\Filament\Resources\Translations\Pages\ManageTranslations;
use App\Models\Translation;
use BackedEnum;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Textarea;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use UnitEnum;

class TranslationResource extends Resource
{
    protected static ?string $model = Translation::class;

    protected static string|UnitEnum|null $navigationGroup = 'Pengaturan';

    protected static ?string $navigationLabel = 'Terjemahan EN / 中文';

    protected static ?string $modelLabel = 'terjemahan';

    protected static ?string $pluralModelLabel = 'terjemahan';

    protected static ?int $navigationSort = 2;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedLanguage;

    protected static ?string $recordTitleAttribute = 'source';

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            Textarea::make('source')->label('Teks Indonesia (persis seperti di halaman)')->required()->rows(3)
                ->helperText('Satu entri = satu potongan teks. Judul yang dipisah baris (Enter) diterjemahkan per baris.')
                ->rule(fn (?Translation $record) => function (string $attribute, $value, $fail) use ($record) {
                    $exists = Translation::query()->where('source_hash', sha1(Translation::normalize($value)))
                        ->when($record, fn ($q) => $q->whereKeyNot($record->id))->exists();
                    if ($exists) {
                        $fail('Teks ini sudah punya terjemahan.');
                    }
                }),
            Textarea::make('en')->label('English')->rows(3),
            Textarea::make('zh')->label('中文')->rows(3),
        ])->columns(1);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->description('Kamus tombol bahasa di navbar. Teks yang tidak ada di sini tetap tampil dalam bahasa Indonesia.')
            ->defaultSort('source')
            ->columns([
                TextColumn::make('source')->label('Indonesia')->searchable()->limit(70)->wrap(),
                TextColumn::make('en')->label('English')->searchable()->limit(70)->wrap()->placeholder('—'),
                TextColumn::make('zh')->label('中文')->searchable()->limit(40)->wrap()->placeholder('—'),
            ])
            ->filters([
                Filter::make('incomplete')->label('Belum lengkap')
                    ->query(fn (Builder $q) => $q->where(fn ($q) => $q->whereNull('en')->orWhere('en', '')->orWhereNull('zh')->orWhere('zh', ''))),
            ])
            ->recordActions([
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([DeleteBulkAction::make()]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => ManageTranslations::route('/'),
        ];
    }
}
