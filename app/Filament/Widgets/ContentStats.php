<?php

namespace App\Filament\Widgets;

use App\Filament\Resources\Inquiries\InquiryResource;
use App\Filament\Resources\Posts\PostResource;
use App\Filament\Resources\Products\ProductResource;
use App\Models\Inquiry;
use App\Models\Location;
use App\Models\Post;
use App\Models\Product;
use Filament\Support\Icons\Heroicon;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class ContentStats extends StatsOverviewWidget
{
    protected static ?int $sort = 1;

    protected function getStats(): array
    {
        $unread = Inquiry::query()->whereNull('read_at')->count();

        return [
            Stat::make('Permintaan belum dibaca', $unread)
                ->description(Inquiry::query()->count().' total permintaan')
                ->icon(Heroicon::OutlinedInbox)
                ->color($unread ? 'warning' : 'gray')
                ->url(InquiryResource::getUrl()),
            Stat::make('Artikel terbit', Post::query()->live()->count())
                ->description(Post::query()->where('is_published', false)->count().' draf')
                ->icon(Heroicon::OutlinedNewspaper)
                ->url(PostResource::getUrl()),
            Stat::make('Produk di katalog', Product::query()->active()->count())
                ->description(Product::query()->whereNotNull('description')->count().' punya halaman detail')
                ->icon(Heroicon::OutlinedCube)
                ->url(ProductResource::getUrl()),
            Stat::make('SuperArea aktif', Location::query()->where('is_active', true)->count())
                ->icon(Heroicon::OutlinedMapPin),
        ];
    }
}
