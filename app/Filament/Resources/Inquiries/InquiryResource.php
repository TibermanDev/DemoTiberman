<?php

namespace App\Filament\Resources\Inquiries;

use App\Filament\Resources\Inquiries\Pages\ManageInquiries;
use App\Models\Inquiry;
use BackedEnum;
use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\ViewAction;
use Filament\Infolists\Components\TextEntry;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;

class InquiryResource extends Resource
{
    protected static ?string $model = Inquiry::class;

    protected static ?string $navigationLabel = 'Permintaan Masuk';

    protected static ?string $modelLabel = 'permintaan';

    protected static ?string $pluralModelLabel = 'permintaan masuk';

    protected static ?int $navigationSort = 1;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedInbox;

    protected static ?string $recordTitleAttribute = 'name';

    public static function getNavigationBadge(): ?string
    {
        $unread = Inquiry::query()->whereNull('read_at')->count();

        return $unread ? (string) $unread : null;
    }

    public static function canCreate(): bool
    {
        return false;
    }

    public static function infolist(Schema $schema): Schema
    {
        return $schema->components([
            TextEntry::make('name')->label('Nama'),
            TextEntry::make('created_at')->label('Dikirim')->dateTime('j M Y H:i'),
            TextEntry::make('email')->label('Email')->copyable()->url(fn (Inquiry $r) => 'mailto:'.$r->email),
            TextEntry::make('phone')->label('Telepon')->copyable()
                ->url(fn (Inquiry $r) => 'https://wa.me/'.preg_replace('/^0/', '62', preg_replace('/\D/', '', $r->phone)), shouldOpenInNewTab: true),
            TextEntry::make('unit')->label('Kebutuhan unit')->placeholder('—'),
            TextEntry::make('quantity')->label('Perkiraan jumlah')->placeholder('—'),
            TextEntry::make('needed_at')->label('Perkiraan waktu')->date('j M Y')->placeholder('—'),
            TextEntry::make('message')->label('Pesan')->placeholder('—')->columnSpanFull(),
        ])->columns(2);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->defaultSort('created_at', 'desc')
            ->columns([
                IconColumn::make('read_at')->label('')->boolean()
                    ->trueIcon(Heroicon::OutlinedEnvelopeOpen)->falseIcon(Heroicon::Envelope)
                    ->trueColor('gray')->falseColor('primary'),
                TextColumn::make('name')->label('Nama')->searchable()->weight(fn (Inquiry $r) => $r->read_at ? null : 'bold'),
                TextColumn::make('phone')->label('Telepon')->searchable(),
                TextColumn::make('email')->searchable()->toggleable(),
                TextColumn::make('unit')->label('Unit')->badge()->placeholder('—'),
                TextColumn::make('created_at')->label('Dikirim')->since()->sortable(),
            ])
            ->filters([
                TernaryFilter::make('read_at')->label('Status')->nullable()
                    ->trueLabel('Sudah dibaca')->falseLabel('Belum dibaca'),
            ])
            ->recordActions([
                ViewAction::make()->after(fn (Inquiry $record) => $record->read_at ?: $record->update(['read_at' => now()])),
                Action::make('unread')->label('Tandai belum dibaca')->icon(Heroicon::OutlinedEnvelope)->color('gray')
                    ->visible(fn (Inquiry $r) => $r->read_at !== null)
                    ->action(fn (Inquiry $r) => $r->update(['read_at' => null])),
                DeleteAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([DeleteBulkAction::make()]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => ManageInquiries::route('/'),
        ];
    }
}
