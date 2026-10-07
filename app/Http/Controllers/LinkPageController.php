<?php

namespace App\Http\Controllers;

use App\Models\LandingPage;
use App\Models\LinkPage;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

/**
 * Linktree hanya satu halaman: /lp/ (menu Linktree (/lp) di CMS).
 *
 * Alamat lama /lp/{slug}.html (mis. /lp/bus.html dari bio media sosial)
 * tidak punya halaman sendiri lagi: diarahkan ke landing page promo aktif
 * yang pertama dibuat. Tujuan per alamat bisa diganti lewat menu Redirect
 * URL — middleware RedirectLegacyUrls dicek lebih dulu dari route ini.
 */
class LinkPageController extends Controller
{
    public function __invoke(?string $page = null): View|RedirectResponse
    {
        if ($page !== null) {
            $landing = LandingPage::query()->where('is_active', true)->orderBy('id')->first();

            return redirect($landing ? '/'.$landing->slug : '/lp/', 301);
        }

        $linkPage = LinkPage::query()->where('is_active', true)->where('slug', LinkPage::INDEX)->firstOrFail();

        return view('linktree', ['page' => $linkPage]);
    }
}
