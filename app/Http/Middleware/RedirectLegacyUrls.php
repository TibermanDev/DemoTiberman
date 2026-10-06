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

            // Halaman produk toko lama /product/{slug}/ -> /produk/{slug}: produk
            // diinput dengan slug yang sama, tidak perlu entri Redirect URL.
            if (preg_match('#^product/([^/]+)$#', $path, $m)) {
                return redirect('/produk/'.$m[1], 301);
            }

            // Halaman akun WooCommerce lama (/my-account/, /my-account/edit-akun/,
            // ...) ke beranda: situs baru tidak punya fitur akun pelanggan.
            if ($path === 'my-account' || str_starts_with($path, 'my-account/')) {
                return redirect('/', 301);
            }

            // Paginasi arsip penulis blog lama (/blog/author/x/page/2/) langsung
            // ke beranda: situs baru tidak punya halaman penulis.
            if (preg_match('#^blog/author/[^/]+/page/\d+$#', $path)) {
                return redirect('/', 301);
            }

            // Paginasi WordPress lama (/produk/page/2/, /blog/category/x/page/3/,
            // /tag-produk/x/page/2/, /kategori-produk/x/page/2/) diarahkan ke
            // halaman induknya — halaman baru tidak berhalaman, semua isinya
            // tampil di halaman induk.
            if (preg_match('#^(?:(.+)/)?page/\d+$#', $path, $m)) {
                return redirect('/'.($m[1] ?? ''), 301);
            }
        }

        return $next($request);
    }
}
