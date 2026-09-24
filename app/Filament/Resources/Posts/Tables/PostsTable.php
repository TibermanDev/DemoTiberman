<?php

namespace App\Filament\Resources\Posts\Tables;

use App\Models\Post;
use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;

class PostsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->defaultSort('published_at', 'desc')
            ->columns([
                ImageColumn::make('cover_image')->label('')->disk('public'),
                TextColumn::make('title')->label('Judul')->searchable()->limit(60)->wrap()
                    ->description(fn (Post $r) => '/blog/'.$r->slug),
                TextColumn::make('category.heading')->label('Kategori')->badge(),
                TextColumn::make('published_at')->label('Terbit')->date('j M Y')->sortable(),
                ToggleColumn::make('is_published')->label('Terbit'),
                ToggleColumn::make('is_featured')->label('Sorotan'),
                ToggleColumn::make('is_popular')->label('Populer')->toggleable(),
            ])
            ->filters([
                SelectFilter::make('post_category_id')->label('Kategori')->relationship('category', 'heading'),
                TernaryFilter::make('is_published')->label('Terbit'),
            ])
            ->recordActions([
                Action::make('open')->label('Lihat')->icon(Heroicon::OutlinedArrowTopRightOnSquare)->color('gray')
                    ->url(fn (Post $r) => $r->url(), shouldOpenInNewTab: true),
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
