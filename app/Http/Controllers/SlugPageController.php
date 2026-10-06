<?php

namespace App\Http\Controllers;

use App\Models\LandingPage;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Route fallback /{slug}: landing page promo dulu, lalu flipbook PDF.
 * Slug baru dari CMS langsung hidup tanpa bisa menimpa route lain.
 */
class SlugPageController extends Controller
{
    public function __invoke(Request $request): Response|\Illuminate\View\View
    {
        $landing = LandingPage::query()->where('is_active', true)
            ->where('slug', trim($request->path(), '/'))
            ->first();

        if ($landing !== null) {
            return view('landing', ['landing' => $landing]);
        }

        return app(FlipbookController::class)($request);
    }
}
