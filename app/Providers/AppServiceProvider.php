<?php

namespace App\Providers;

use App\Models\CatalogUnit;
use App\Models\Location;
use App\Models\Translation;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Menu Products di navbar, daftar SuperArea di footer, dan kamus i18n
        // dipakai di semua halaman — diambil sekali per render layout.
        View::composer(['layouts.app', 'produk'], function ($view) {
            $view->with([
                'navUnits' => CatalogUnit::query()->ordered()->where('show_in_nav', true)
                    ->where('key', '!=', CatalogUnit::ALL)->get(),
                'footerLocations' => Location::query()->active()->where('show_in_footer', true)->get(),
                'i18nDict' => Translation::dictionary(),
            ]);
        });
    }
}
