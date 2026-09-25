<?php

namespace App\Providers\Filament;

use App\Support\Favicon;
use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\AuthenticateSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Navigation\NavigationGroup;
use Filament\Navigation\NavigationItem;
use Filament\Pages\Dashboard;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Filament\Support\Icons\Heroicon;
use Filament\View\PanelsRenderHook;
use Filament\Widgets\AccountWidget;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\PreventRequestForgery;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\Support\HtmlString;
use Illuminate\View\Middleware\ShareErrorsFromSession;

/** CMS situs Tiberman di /admin. */
class AdminPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->default()
            ->id('admin')
            ->path('admin')
            ->login()
            ->brandName('Tiberman CMS')
            ->brandLogo(fn () => view('filament.brand-logo'))
            ->brandLogoHeight('1.75rem')
            // klik pratinjau gambar -> popup besar, bukan tab baru
            ->renderHook(PanelsRenderHook::BODY_END, fn () => view('filament.image-lightbox'))
            // Scrollbar sidebar dibuat tipis dan samar (jalurnya transparan).
            // scrollbar-width/-color standar dipakai Chrome 121+, Edge &
            // Firefox; ::-webkit-scrollbar untuk Safari. Di desktop sidebar
            // Filament tidak berlatar maupun bergaris tepi, jadi diberi garis
            // tipis sebagai pembatas dengan konten. Di layar kecil sidebar jadi
            // laci melayang yang sudah punya bayangan sendiri.
            ->renderHook(PanelsRenderHook::HEAD_END, fn () => new HtmlString(
                '<style>.fi-sidebar-nav{scrollbar-width:thin;scrollbar-color:color-mix(in oklab,var(--gray-950) 20%,transparent) transparent}'
                .'.dark .fi-sidebar-nav{scrollbar-color:color-mix(in oklab,#fff 18%,transparent) transparent}'
                .'.fi-sidebar-nav::-webkit-scrollbar{width:6px}'
                .'.fi-sidebar-nav::-webkit-scrollbar-track{background:transparent}'
                .'.fi-sidebar-nav::-webkit-scrollbar-thumb{border-radius:6px;background:color-mix(in oklab,var(--gray-950) 20%,transparent)}'
                .'.dark .fi-sidebar-nav::-webkit-scrollbar-thumb{background:color-mix(in oklab,#fff 18%,transparent)}'
                .'@media (min-width:64rem){.fi-sidebar{border-inline-end:1px solid color-mix(in oklab,var(--gray-950) 8%,transparent)}'
                .'.dark .fi-sidebar{border-inline-end-color:color-mix(in oklab,#fff 10%,transparent)}}</style>'
            ))
            // rescue(): tabel settings belum ada saat migrate pertama kali
            ->favicon(rescue(fn () => Favicon::url(), null, false))
            ->colors([
                'primary' => Color::Red,
            ])
            ->sidebarCollapsibleOnDesktop()
            ->navigationGroups([
                NavigationGroup::make('Halaman'),
                NavigationGroup::make('Blog'),
                NavigationGroup::make('Katalog'),
                NavigationGroup::make('Pengaturan'),
            ])
            ->navigationItems([
                NavigationItem::make('Lihat situs')
                    ->url('/', shouldOpenInNewTab: true)
                    ->icon(Heroicon::OutlinedGlobeAlt)
                    ->sort(99),
            ])
            ->discoverResources(in: app_path('Filament/Resources'), for: 'App\Filament\Resources')
            ->discoverPages(in: app_path('Filament/Pages'), for: 'App\Filament\Pages')
            ->pages([
                Dashboard::class,
            ])
            ->discoverWidgets(in: app_path('Filament/Widgets'), for: 'App\Filament\Widgets')
            ->widgets([
                AccountWidget::class,
            ])
            ->middleware([
                EncryptCookies::class,
                AddQueuedCookiesToResponse::class,
                StartSession::class,
                AuthenticateSession::class,
                ShareErrorsFromSession::class,
                PreventRequestForgery::class,
                SubstituteBindings::class,
                DisableBladeIconComponents::class,
                DispatchServingFilamentEvent::class,
            ])
            ->authMiddleware([
                Authenticate::class,
            ]);
    }
}
