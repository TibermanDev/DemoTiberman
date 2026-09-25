<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use App\Models\CatalogUnit;
use App\Models\Flipbook;
use App\Models\Post;
use App\Models\PostCategory;
use App\Models\Product;
use App\Support\Seo;
use Illuminate\Http\Response;

/**
 * /sitemap.xml dan /robots.txt, dibangun dari isi CMS supaya halaman baru
 * (artikel, produk, flipbook) langsung terdaftar tanpa langkah tambahan.
 * Halaman yang ditandai noindex tidak dimasukkan ke sitemap.
 */
class SeoController extends Controller
{
    public function sitemap(): Response
    {
        $urls = collect();
        $add = fn (string $loc, $lastmod = null, string $priority = '0.5') => $urls->push(compact('loc', 'lastmod', 'priority'));

        if (Seo::indexable()) {
            foreach ([
                [route('home'), 'home', '1.0'],
                [route('contact'), 'contact', '0.8'],
                [route('superarea'), 'superarea', '0.8'],
                [route('blog'), 'news', '0.7'],
            ] as [$loc, $group, $priority]) {
                if (! cms($group.'.seo_noindex')) {
                    $add($loc, null, $priority);
                }
            }

            CatalogUnit::query()->ordered()->get()->each(fn ($u) => $add(url($u->url()), null, '0.8'));
            Brand::query()->ordered()->get()->each(fn ($b) => $add(route('katalog.brand', $b->slug), null, '0.6'));
            Product::query()->active()->where('noindex', false)->get()
                ->each(fn ($p) => $add($p->url(), $p->updated_at, '0.7'));
            PostCategory::query()->ordered()->get()->each(fn ($c) => $add(route('blog.category', $c->slug), null, '0.5'));
            Post::query()->live()->where('noindex', false)->get()
                ->each(fn ($p) => $add($p->url(), $p->updated_at ?? $p->published_at, '0.6'));
            Flipbook::query()->where('is_active', true)->get()->each(fn ($f) => $add(url($f->slug), $f->updated_at, '0.4'));
        }

        $xml = '<?xml version="1.0" encoding="UTF-8"?>'."\n"
            .'<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">'."\n";
        foreach ($urls->unique('loc') as $u) {
            $xml .= '  <url><loc>'.e($u['loc']).'</loc>'
                .($u['lastmod'] ? '<lastmod>'.$u['lastmod']->toAtomString().'</lastmod>' : '')
                .'<priority>'.$u['priority'].'</priority></url>'."\n";
        }
        $xml .= '</urlset>'."\n";

        return response($xml, 200, ['Content-Type' => 'application/xml; charset=UTF-8']);
    }

    /**
     * Situs yang disembunyikan TIDAK di-Disallow di sini: kalau diblokir,
     * Google tidak bisa membaca tag noindex-nya dan URL-nya bisa tetap muncul.
     */
    public function robots(): Response
    {
        $lines = [
            'User-agent: *',
            'Disallow: /admin',
            'Disallow: /livewire',
            'Disallow: /produk/*/modal',
            '',
            'Sitemap: '.url('sitemap.xml'),
        ];

        return response(implode("\n", $lines)."\n", 200, ['Content-Type' => 'text/plain; charset=UTF-8']);
    }
}
