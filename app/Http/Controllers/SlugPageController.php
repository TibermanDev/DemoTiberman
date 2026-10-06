<?php

namespace App\Http\Controllers;

use App\Models\LandingPage;
use App\Models\PromoPage;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Route fallback /{slug}: landing page marketplace, halaman promo, lalu
 * flipbook PDF. Slug baru dari CMS langsung hidup tanpa bisa menimpa route
 * lain (keunikan lintas tabel dijaga App\Support\PageSlug).
 */
class SlugPageController extends Controller
{
    public function __invoke(Request $request): Response|\Illuminate\View\View
    {
        $slug = trim($request->path(), '/');

        if ($landing = LandingPage::query()->where('is_active', true)->where('slug', $slug)->first()) {
            return view('landing', ['landing' => $landing]);
        }

        if ($promo = PromoPage::query()->where('is_active', true)->where('slug', $slug)->first()) {
            return view('promo', ['promo' => $promo]);
        }

        return app(FlipbookController::class)($request);
    }
}
