<?php

namespace Tests\Feature;

use App\Filament\Pages\FooterContent;
use App\Filament\Pages\HomeContent;
use App\Filament\Pages\SiteSettings;
use App\Filament\Resources\Inquiries\Pages\ManageInquiries;
use App\Filament\Resources\Locations\Pages\ManageLocations;
use App\Filament\Resources\Posts\Pages\CreatePost;
use App\Models\Inquiry;
use App\Models\Location;
use App\Models\Post;
use App\Models\PostCategory;
use App\Models\Setting;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Livewire\Livewire;
use PHPUnit\Framework\Attributes\DataProvider;
use Tests\TestCase;

/** Panel admin CMS di /admin. */
class CmsAdminTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        Storage::fake('public');
        $this->seed();
        $this->actingAs(User::query()->firstOrFail());
    }

    public static function adminPages(): array
    {
        return array_map(fn ($u) => [$u], [
            '/admin', '/admin/home-content', '/admin/contact-content', '/admin/news-content',
            '/admin/superarea-content', '/admin/catalog-content', '/admin/site-settings', '/admin/footer-content', '/admin/seo-settings',
            '/admin/posts', '/admin/posts/create', '/admin/post-categories',
            '/admin/products', '/admin/products/create', '/admin/catalog-units', '/admin/brands', '/admin/tire-sizes',
            '/admin/locations', '/admin/flipbooks', '/admin/redirects', '/admin/translations',
            '/admin/inquiries', '/admin/users',
        ]);
    }

    #[DataProvider('adminPages')]
    public function test_admin_pages_render(string $url): void
    {
        $this->get($url)->assertOk();
    }

    public function test_replacing_background_video_clears_old_webm(): void
    {
        $this->assertNotNull(Setting::group('home')['importir']['video_webm']);

        Livewire::test(HomeContent::class)
            ->set('data.importir.video', [UploadedFile::fake()->create('baru.mp4', 1024, 'video/mp4')])
            ->call('save')
            ->assertHasNoErrors();

        $importir = Setting::group('home')['importir'];
        $this->assertStringEndsWith('.mp4', $importir['video']);
        $this->assertNull($importir['video_webm']);

        $this->get('/')->assertSee('/storage/'.$importir['video'], false)
            ->assertDontSee('tires-moving.webm', false);
    }

    public function test_location_maps_link_is_editable_in_cms(): void
    {
        $location = Location::query()->firstOrFail();

        Livewire::test(ManageLocations::class)
            ->assertSee('Google Maps')
            ->mountTableAction('edit', $location)
            ->setTableActionData(['maps_url' => 'https://maps.app.goo.gl/abc'])
            ->callMountedTableAction()
            ->assertHasNoTableActionErrors();

        $this->assertSame('https://maps.app.goo.gl/abc', $location->fresh()->maps_url);
    }

    public function test_viewing_an_inquiry_marks_it_read(): void
    {
        $inquiry = Inquiry::query()->create(['name' => 'Budi', 'email' => 'b@x.com', 'phone' => '0812']);

        Livewire::test(ManageInquiries::class)->mountTableAction('view', $inquiry);

        $this->assertNotNull($inquiry->fresh()->read_at);
    }

    public function test_image_lightbox_is_on_admin_pages(): void
    {
        $this->get('/admin/home-content')->assertOk()->assertSee('data-tbm-lightbox', false);
    }

    public function test_guests_are_sent_to_login(): void
    {
        auth()->logout();

        $this->get('/admin/posts')->assertRedirect('/admin/login');
    }

    public function test_home_content_page_loads_and_saves_settings(): void
    {
        Livewire::test(HomeContent::class)
            ->assertSet('data.superarea.eyebrow', 'siap melayani Anda lebih dekat dengan')
            ->set('data.superarea.eyebrow', 'Eyebrow baru')
            ->call('save')
            ->assertHasNoErrors();

        $this->assertSame('Eyebrow baru', Setting::group('home')['superarea']['eyebrow']);
        // grup lain di baris yang sama tidak ikut hilang
        $this->assertNotEmpty(Setting::group('home')['aftersales']['items']);

        $this->get('/')->assertSee('Eyebrow baru');
    }

    public function test_site_settings_validate_whatsapp_number(): void
    {
        Livewire::test(SiteSettings::class)
            ->set('data.whatsapp', '+62 812')
            ->call('save')
            ->assertHasErrors(['data.whatsapp']);
    }

    public function test_footer_content_saves_without_wiping_site_settings(): void
    {
        Livewire::test(FooterContent::class)
            ->set('data.footer.nav_title', 'Menu Cepat')
            ->set('data.footer.shopee_label', 'Toko Shopee')
            ->call('save')
            ->assertHasNoErrors();

        $this->get('/')
            ->assertSee('Menu Cepat')
            ->assertSee('Toko Shopee')
            ->assertSee(cms('site.email'));

        $this->assertSame('6281283258200', cms('site.whatsapp'));
    }

    public function test_post_can_be_created_from_admin(): void
    {
        Livewire::test(CreatePost::class)
            ->fillForm([
                'title' => 'Artikel Uji Baru',
                'slug' => 'artikel-uji-baru',
                'excerpt' => 'Ringkasan uji.',
                'body' => '<p>Isi artikel uji.</p>',
                'post_category_id' => PostCategory::query()->first()->id,
                'is_published' => true,
                'published_at' => now()->subMinute(),
            ])
            ->call('create')
            ->assertHasNoFormErrors();

        $post = Post::query()->where('slug', 'artikel-uji-baru')->firstOrFail();
        $this->get($post->url())->assertOk()->assertSee('Isi artikel uji.');
    }
}
