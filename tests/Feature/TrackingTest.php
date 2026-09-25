<?php

namespace Tests\Feature;

use App\Filament\Pages\TrackingSettings;
use App\Models\Setting;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;
use Livewire\Livewire;
use Tests\TestCase;

/** Pixel & tag iklan dari menu Pengaturan → Tracking & Iklan. */
class TrackingTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        Storage::fake('public');
        $this->seed();
    }

    private function tracking(array $values): void
    {
        Setting::put('site', ['tracking' => $values] + Setting::group('site'));
    }

    public function test_no_tags_when_ids_are_empty(): void
    {
        $html = $this->get('/')->assertOk()->getContent();

        $this->assertStringNotContainsString('googletagmanager.com', $html);
        $this->assertStringNotContainsString('fbevents.js', $html);
        $this->assertStringNotContainsString('analytics.tiktok.com', $html);
    }

    public function test_filled_ids_render_their_tags_on_every_public_page(): void
    {
        $this->tracking([
            'ga4_id' => 'G-TEST123', 'gtm_id' => 'GTM-ABC1', 'google_ads_id' => 'AW-111',
            'google_ads_lead_label' => 'lbl_lead', 'meta_pixel_id' => '123456789012345',
            'meta_domain_verification' => 'metaverif', 'tiktok_pixel_id' => 'C4ABCDEFGH12345',
            'linkedin_partner_id' => '7654321', 'custom_head' => '<!-- kode-kustom -->',
        ]);

        foreach (['/', '/kontak', '/blog'] as $url) {
            $this->get($url)
                ->assertSee('gtag/js?id=G-TEST123', false)
                ->assertSee("gtag('config', \"AW-111\")", false)
                ->assertSee('GTM-ABC1', false)
                ->assertSee('ns.html?id=GTM-ABC1', false)
                ->assertSee("fbq('init', \"123456789012345\")", false)
                ->assertSee('<meta name="facebook-domain-verification" content="metaverif">', false)
                ->assertSee('ttq.load("C4ABCDEFGH12345")', false)
                ->assertSee('"adsLead":"lbl_lead"', false)
                ->assertSee('<!-- kode-kustom -->', false);
        }
    }

    public function test_master_switch_disables_all_tags(): void
    {
        $this->tracking(['ga4_id' => 'G-TEST123', 'meta_pixel_id' => '123456789012345', 'enabled' => false]);

        $this->get('/')->assertDontSee('G-TEST123', false)->assertDontSee('fbevents.js', false);
    }

    public function test_admin_rejects_malformed_ids(): void
    {
        $this->actingAs(User::query()->firstOrFail());

        Livewire::test(TrackingSettings::class)
            ->set('data.tracking.ga4_id', 'UA-12345')
            ->set('data.tracking.meta_pixel_id', '<script>')
            ->call('save')
            ->assertHasErrors(['data.tracking.ga4_id', 'data.tracking.meta_pixel_id']);
    }
}
