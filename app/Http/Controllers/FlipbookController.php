<?php

namespace App\Http\Controllers;

use App\Models\Flipbook;
use Illuminate\Http\Request;
use Illuminate\View\View;

/**
 * Halaman flipbook PDF di /{slug} (/katalog, /company-profile, /proposal).
 * Dipasang sebagai route fallback supaya slug baru dari CMS langsung hidup
 * tanpa bisa menimpa route lain.
 */
class FlipbookController extends Controller
{
    public function __invoke(Request $request): View
    {
        $book = Flipbook::query()->where('is_active', true)
            ->where('slug', trim($request->path(), '/'))
            ->first();

        abort_if($book === null || $book->pdf() === null, 404);

        return view('flipbook', ['book' => ['title' => $book->title, 'pdf' => $book->pdf()]]);
    }
}
