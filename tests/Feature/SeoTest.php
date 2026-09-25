<?php

namespace Tests\Feature;

use App\Models\Post;
use App\Models\Product;
use App\Models\Setting;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

/** Tag SEO di <head>, sitemap.xml, dan robots.txt. */
class SeoTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        Storage::fake('public');
        $this->seed();
    }

    public function test_head_has_meta_open_graph_canonical_and_structured_data(): void
    {
        $html = $this->get('/kontak')->assertOk()->getContent();

        // judul berisi "&" tidak boleh ter-escape dua kali
        $this->assertStringNotContainsString('&amp;amp;', $this->get('/')->getContent());
        $this->assertStringContainsString('<link rel="canonical" href="'.url('/kontak').'">', $html);
        $this->assertStringContainsString('<meta name="robots" content="index, follow', $html);
        $this->assertStringContainsString('<meta property="og:site_name" content="Tiberman">', $html);
        $this->assertStringContainsString('<meta property="og:url" content="'.url('/kontak').'">', $html);

        preg_match('#<script type="application/ld\+json">(.*?)</script>#s', $html, $m);
        $graph = json_decode($m[1], true)['@graph'];
        $this->assertSame('WebSite', $graph[0]['@type']);
        $this->assertSame('Tiberman', $graph[0]['name']);
        $this->assertSame('TireShop', $graph[1]['@type']);
        $this->assertSame('Surabaya', $graph[1]['address']['addressLocality']);
        $this->assertSame(['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday'], $graph[1]['openingHoursSpecification'][0]['dayOfWeek']);
    }

    public function test_verification_codes_and_page_noindex(): void
    {
        $site = Setting::group('site');
        $site['seo']['google_verification'] = 'kode-google';
        Setting::put('site', $site);
        Setting::put('contact', ['seo_noindex' => true] + Setting::group('contact'));

        $this->get('/kontak')
            ->assertSee('<meta name="google-site-verification" content="kode-google">', false)
            ->assertSee('<meta name="robots" content="noindex, follow">', false);

        $this->get('/sitemap.xml')->assertDontSee('<loc>'.url('/kontak').'</loc>', false);
    }

    public function test_site_wide_noindex_hides_everything(): void
    {
        $site = Setting::group('site');
        $site['seo']['indexable'] = false;
        Setting::put('site', $site);

        $this->get('/')->assertSee('<meta name="robots" content="noindex, follow">', false);
        $this->get('/sitemap.xml')->assertDontSee('<url>', false);
    }

    public function test_post_and_product_seo_overrides(): void
    {
        $post = Post::query()->live()->firstOrFail();
        $post->update(['meta_title' => 'Judul Khusus Google']);
        $html = $this->get($post->url())
            ->assertSee('<title>Judul Khusus Google</title>', false)
            ->assertSee('<meta property="og:type" content="article">', false)
            ->getContent();

        preg_match_all('#<script type="application/ld\+json">(.*?)</script>#s', $html, $m);
        $blocks = array_map(fn ($j) => json_decode($j, true, flags: JSON_THROW_ON_ERROR), $m[1]);
        $article = collect($blocks)->firstWhere('@type', 'BlogPosting');
        $this->assertSame($post->url(), $article['mainEntityOfPage']);
        $this->assertSame($post->published_at->toIso8601String(), $article['datePublished']);

        $product = Product::query()->active()->firstOrFail();
        $product->update(['noindex' => true]);
        $this->get($product->url())->assertSee('<meta name="robots" content="noindex, follow">', false);
        $this->get('/sitemap.xml')->assertDontSee('<loc>'.$product->url().'</loc>', false);
    }

    public function test_sitemap_lists_content_and_robots_points_to_it(): void
    {
        $post = Post::query()->live()->firstOrFail();

        $this->get('/sitemap.xml')->assertOk()
            ->assertHeader('Content-Type', 'application/xml; charset=UTF-8')
            ->assertSee('<loc>'.url('/').'</loc>', false)
            ->assertSee('<loc>'.$post->url().'</loc>', false);

        $this->get('/robots.txt')->assertOk()
            ->assertSee('Disallow: /admin')
            ->assertSee('Sitemap: '.url('sitemap.xml'));
    }
}
