<?php

namespace App\Filament\Resources\Products\Tables;

use App\Models\Product;
use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ReplicateAction;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Support\Str;

class ProductsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->reorderable('sort_order')
            ->defaultSort('sort_order')
            ->columns([
                ImageColumn::make('image')->label('')->disk('public')
                    ->defaultImageUrl(Product::FALLBACK_IMAGE),
                TextColumn::make('name')->label('Nama')->searchable()->description(fn (Product $r) => $r->compat),
                TextColumn::make('size')->label('Ukuran')->searchable()->sortable(),
                TextColumn::make('unit.label')->label('Unit')->badge(),
                TextColumn::make('brand.name')->label('Merk')->placeholder('—'),
                IconColumn::make('description')->label('Detail')->boolean()
                    ->state(fn (Product $r) => filled($r->description))
                    ->tooltip('Sudah punya deskripsi halaman detail'),
                ToggleColumn::make('is_active')->label('Tampil'),
            ])
            ->filters([
                SelectFilter::make('catalog_unit_id')->label('Unit')->relationship('unit', 'label'),
                SelectFilter::make('brand_id')->label('Merk')->relationship('brand', 'name'),
            ])
            ->recordActions([
                Action::make('open')->label('Lihat')->icon(Heroicon::OutlinedArrowTopRightOnSquare)->color('gray')
                    ->url(fn (Product $r) => $r->url(), shouldOpenInNewTab: true),
                ReplicateAction::make()->label('Duplikat')
                    ->beforeReplicaSaved(function (Product $replica) {
                        $replica->slug = $replica->slug.'-'.Str::lower(Str::random(4));
                        $replica->is_active = false;
                    })
                    ->successRedirectUrl(fn (Product $replica) => route('filament.admin.resources.products.edit', $replica)),
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
