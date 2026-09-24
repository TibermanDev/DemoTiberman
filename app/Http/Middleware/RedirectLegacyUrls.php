<?php

namespace App\Http\Middleware;

use App\Models\Redirect;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Redirect dari URL situs lama (menu Redirect di CMS). Dipasang sebagai
 * middleware global, jadi dicek SEBELUM routing dan menang atas route dinamis
 * seperti /blog/{slug} dan /kategori-produk/{path}.
 */
class RedirectLegacyUrls
{
    public function handle(Request $request, Closure $next): Response
    {
        if ($request->isMethodSafe()) {
            $target = Redirect::map()[trim($request->path(), '/')] ?? null;

            if ($target !== null) {
                return redirect($target[0], $target[1]);
            }
        }

        return $next($request);
    }
}
