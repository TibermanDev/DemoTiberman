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
            $path = trim($request->path(), '/');
            $target = Redirect::map()[$path] ?? null;

            if ($target !== null) {
                return redirect($target[0], $target[1]);
            }

            // Paginasi WordPress lama (/produk/page/2/, /blog/category/x/page/3/)
            // diarahkan ke halaman induknya. Tag produk dikecualikan karena
            // nanti punya halaman sendiri.
            if (preg_match('#^(?:(.+)/)?page/\d+$#', $path, $m) && ! str_starts_with($path, 'tag-produk/')) {
                return redirect('/'.($m[1] ?? ''), 301);
            }
        }

        return $next($request);
    }
}
