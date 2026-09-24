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
        // Batas upload sementara Livewire (bawaan 12 MB) dinaikkan untuk video
        // latar di CMS; batas per field tetap diatur di FileUpload::maxSize().
        config([
            'livewire.temporary_file_upload.rules' => ['required', 'file', 'max:51200'],
            'livewire.temporary_file_upload.max_upload_time' => 15,
        ]);

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
