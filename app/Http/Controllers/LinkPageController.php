<?php

namespace App\Http\Controllers;

use App\Models\LinkPage;
use Illuminate\View\View;

/** Linktree /lp/ dan /lp/{slug}.html — isinya dari menu Linktree (/lp) di CMS. */
class LinkPageController extends Controller
{
    public function __invoke(?string $page = null): View
    {
        $slug = $page === null ? LinkPage::INDEX : substr($page, 0, -strlen('.html'));

        $linkPage = LinkPage::query()->where('is_active', true)->where('slug', $slug)->firstOrFail();

        return view('linktree', ['page' => $linkPage]);
    }
}
