<?php

namespace Tests\Feature;

use App\Models\Flipbook;
use App\Models\Inquiry;
use App\Models\Location;
use App\Models\Post;
use App\Models\Product;
use App\Models\Redirect;
use App\Models\Setting;
use App\Models\Translation;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;
use PHPUnit\Framework\Attributes\DataProvider;
use Tests\TestCase;

/** Situs publik membaca isinya dari CMS. */
class CmsSiteTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        Storage::fake('public');
        $this->seed();
    }

    public static function publicPages(): array
    {
        return [
            ['/'],
            ['/kontak'],
            ['/cabang-tiberman'],
            ['/blog'],
            ['/blog/category/pertambangan'],
            ['/blog/fleet-tire-management-cara-mengontrol-biaya-ban-puluhan-hingga-ratusan-truk'],
            ['/kategori-produk/ban-truk'],
            ['/kategori-produk/ban-bus'],
            ['/kategori-produk/semua-ban'],
            ['/brand/uninest'],
            ['/kategori-produk/merek-ban/aeolus'],
            ['/kategori-produk/ukuran-ban/ban-11-00-20'],
            ['/produk'],
            ['/produk/uninest-tibermax-800-12-00-20'],
            ['/produk/hengli-dr908-7-50-16'],
            ['/produk/uninest-tibermax-800-12-00-20/modal'],
            ['/company-profile'],
        ];
    }

    #[DataProvider('publicPages')]
    public function test_public_pages_render(string $url): void
    {
        $this->get($url)->assertOk();
    }

    public function test_unknown_urls_are_404(): void
    {
        $this->get('/tidak-ada')->assertNotFound();
        $this->get('/blog/category/tidak-ada')->assertNotFound();
        $this->get('/kategori-produk/tidak-ada')->assertNotFound();
        $this->get('/produk/tidak-ada')->assertNotFound();
    }

    public function test_home_shows_cms_content(): void
    {
        $home = Setting::group('home');
        $home['superarea']['eyebrow'] = 'Teks eyebrow dari CMS';
        $home['testimonials']['items'][] = ['name' => 'Budi Hartono', 'role' => 'Owner', 'quote' => 'Mantap sekali.'];
        Setting::put('home', $home);

        $this->get('/')
            ->assertSee('Teks eyebrow dari CMS')
            ->assertSee('Mantap sekali.')
            ->assertSee('BH');
    }

    public function test_site_settings_reach_the_footer(): void
    {
        $site = Setting::group('site');
        $site['phone'] = '+62 811 0000 1111';
        $site['social']['youtube'] = '';
        Setting::put('site', $site);

        $this->get('/kontak')
            ->assertSee('+62 811 0000 1111')
            ->assertSee('tel:+6281100001111', false)
            ->assertDontSee('aria-label="YouTube"', false);
    }

    public function test_blog_only_lists_live_posts(): void
    {
        $post = Post::query()->where('slug', 'motor-grader-penjaga-kelancaran-hauling-road')->firstOrFail();

        $this->get('/blog')->assertSee($post->title);

        $post->update(['is_published' => false]);
        $this->get('/blog')->assertDontSee($post->title);
        $this->get($post->url())->assertNotFound();

        $post->update(['is_published' => true, 'published_at' => now()->addWeek()]);
        $this->get($post->url())->assertNotFound();
    }

    public function test_catalog_page_embeds_products_from_database(): void
    {
        Product::query()->create([
            'catalog_unit_id' => Product::query()->first()->catalog_unit_id,
            'name' => 'UNINEST - PRODUK BARU',
            'slug' => 'uninest-produk-baru',
            'size' => '99.99-99',
            'compat' => 'Truk Uji',
        ]);

        $this->get('/kategori-produk/ban-truk')
            ->assertSee('UNINEST - PRODUK BARU')
            ->assertSee('\/produk\/uninest-produk-baru', false);

        $this->get('/produk/uninest-produk-baru/modal')->assertOk()->assertSee('Truk Uji');
    }

    public function test_inactive_products_are_hidden(): void
    {
        $product = Product::query()->where('slug', 'hengli-dr908-7-50-16')->firstOrFail();
        $product->update(['is_active' => false]);

        $this->get('/kategori-produk/ban-truk')->assertDontSee('HENGLI - DR908');
        $this->get('/produk/hengli-dr908-7-50-16')->assertNotFound();
    }

    public function test_redirects_are_managed_in_cms_and_win_over_routes(): void
    {
        $this->get('/kategori/alat-berat')->assertRedirect('/blog/category/alat-berat')->assertStatus(301);

        Redirect::query()->create(['from_path' => '/blog/artikel-lama/', 'to_path' => '/blog', 'status_code' => 302]);

        $this->get('/blog/artikel-lama')->assertRedirect('/blog')->assertStatus(302);
    }

    public function test_flipbooks_come_from_cms(): void
    {
        Flipbook::query()->create(['slug' => 'brosur', 'title' => 'Brosur Baru', 'pdf_url' => 'https://example.com/brosur.pdf']);

        $this->get('/brosur')->assertOk()->assertSee('Brosur Baru')->assertSee('https:\/\/example.com\/brosur.pdf', false);

        Flipbook::query()->where('slug', 'brosur')->update(['is_active' => false]);
        $this->get('/brosur')->assertNotFound();
    }

    public function test_translations_are_injected_for_i18n(): void
    {
        Translation::query()->create(['source' => "Teks  baru\n dari CMS", 'en' => 'New text from CMS', 'zh' => null]);

        $this->get('/')->assertSee('"Teks baru dari CMS":"New text from CMS"', false);
    }

    public function test_contact_form_is_stored_as_inquiry(): void
    {
        $this->postJson('/kontak', [
            'nama' => 'Siti',
            'email' => 'siti@example.com',
            'telepon' => '08123456789',
            'unit' => 'Forklift',
            'jumlah' => '20 ban',
            'pesan' => 'Mohon penawaran.',
        ])->assertOk()->assertJson(['ok' => true]);

        $this->assertDatabaseHas('inquiries', ['name' => 'Siti', 'unit' => 'Forklift', 'read_at' => null]);

        $this->postJson('/kontak', ['nama' => 'Tanpa email'])->assertUnprocessable();
        $this->assertSame(1, Inquiry::query()->count());
    }

    public function test_superarea_cards_link_to_google_maps(): void
    {
        $custom = Location::query()->active()->firstOrFail();
        $custom->update(['maps_url' => 'https://maps.app.goo.gl/contoh']);
        $other = Location::query()->active()->where('id', '!=', $custom->id)->firstOrFail();

        $this->get('/cabang-tiberman')
            ->assertSee('href="https://maps.app.goo.gl/contoh"', false)
            ->assertSee('query='.rawurlencode($other->lat.','.$other->lng), false);
    }
}
