<?php

namespace Tests\Feature;

use App\Filament\Analytics\ConversionPages;
use App\Filament\Analytics\StatsOverview;
use App\Filament\Analytics\TopSources;
use App\Models\AnalyticsEvent;
use App\Models\Setting;
use App\Models\User;
use App\Support\Analytics;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;
use Livewire\Livewire;
use Tests\TestCase;

/** Analitik pengunjung bawaan: beacon POST /_a + dashboard di CMS. */
class AnalyticsTest extends TestCase
{
    use RefreshDatabase;

    private const CHROME = 'Mozilla/5.0 (Linux; Android 14; Pixel 8) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/126.0 Mobile Safari/537.36';

    protected function setUp(): void
    {
        parent::setUp();
        Storage::fake('public');
        $this->seed();
    }

    private function beacon(array $data, string $ua = self::CHROME, string $ip = '203.0.113.7')
    {
        return $this->call('POST', '/_a', [], [], [], [
            'HTTP_USER_AGENT' => $ua, 'REMOTE_ADDR' => $ip, 'CONTENT_TYPE' => 'text/plain',
        ], json_encode($data));
    }

    public function test_pageview_is_recorded_without_storing_ip(): void
    {
        $this->beacon(['t' => 'pageview', 'p' => '/kontak', 'r' => 'https://www.google.com/search?q=ban', 'q' => '?utm_source=meta&utm_campaign=promo'])
            ->assertNoContent();

        $e = AnalyticsEvent::query()->sole();
        $this->assertSame(['pageview', '/kontak', 'google.com', 'mobile', 'Chrome', 'Android', 'meta', 'promo'],
            [$e->type, $e->path, $e->referrer, $e->device, $e->browser, $e->os, $e->utm_source, $e->utm_campaign]);
        $this->assertSame(16, strlen($e->visitor));
        $this->assertStringNotContainsString('203.0.113.7', json_encode($e->getAttributes()));
    }

    public function test_bots_admins_admin_paths_and_junk_are_ignored(): void
    {
        $this->beacon(['t' => 'pageview', 'p' => '/'], 'Mozilla/5.0 (compatible; Googlebot/2.1)');
        $this->beacon(['t' => 'pageview', 'p' => '/admin/posts']);
        $this->beacon(['t' => 'hack', 'p' => '/']);
        $this->beacon(['t' => 'pageview', 'p' => 'https://evil.test/']);
        $this->actingAs(User::query()->firstOrFail());
        $this->beacon(['t' => 'pageview', 'p' => '/']);

        $this->assertSame(0, AnalyticsEvent::query()->count());
    }

    public function test_same_visitor_counts_once_per_day_and_disable_switch_works(): void
    {
        $this->beacon(['t' => 'pageview', 'p' => '/']);
        $this->beacon(['t' => 'pageview', 'p' => '/kontak']);
        $this->beacon(['t' => 'pageview', 'p' => '/'], self::CHROME, '198.51.100.9');

        [$from, $to] = Analytics::range('7');
        $this->assertSame(2, Analytics::visitors($from, $to));
        $this->assertSame(3, Analytics::query($from, $to)->count());

        Setting::put('site', ['analytics' => ['enabled' => false]] + Setting::group('site'));
        $this->beacon(['t' => 'pageview', 'p' => '/']);
        $this->assertSame(3, AnalyticsEvent::query()->count());
        $this->get('/')->assertDontSee('tbmAnalytics(\'pageview\')', false);
    }

    public function test_public_pages_embed_the_beacon_and_conversions_reach_it(): void
    {
        $this->get('/')->assertSee("window.tbmAnalytics('pageview')", false)
            ->assertSee("if (window.tbmAnalytics) window.tbmAnalytics(lead ? 'lead' : 'whatsapp')", false);
    }

    public function test_dashboard_widgets_show_the_numbers(): void
    {
        $this->beacon(['t' => 'pageview', 'p' => '/kontak', 'r' => 'https://www.facebook.com/']);
        $this->beacon(['t' => 'lead', 'p' => '/kontak']);
        $this->beacon(['t' => 'whatsapp', 'p' => '/produk/abc']);
        $this->actingAs(User::query()->firstOrFail());

        $this->get('/admin/analytics')->assertOk()->assertSee('Analitik Pengunjung');

        Livewire::test(StatsOverview::class, ['pageFilters' => ['period' => '7']])
            ->assertSee('Pengunjung')->assertSee('Form Contact terkirim')->assertSee('Klik WhatsApp');
        Livewire::test(TopSources::class, ['pageFilters' => ['period' => '7']])->assertSee('facebook.com');
        Livewire::test(ConversionPages::class, ['pageFilters' => ['period' => '7']])
            ->assertSee('/kontak')->assertSee('/produk/abc');
    }

    public function test_old_events_are_pruned(): void
    {
        AnalyticsEvent::query()->create(['type' => 'pageview', 'day' => now()->subMonths(14)->toDateString(), 'path' => '/', 'visitor' => str_repeat('a', 16)]);
        AnalyticsEvent::query()->create(['type' => 'pageview', 'day' => now()->toDateString(), 'path' => '/', 'visitor' => str_repeat('b', 16)]);

        $this->artisan('model:prune', ['--model' => [AnalyticsEvent::class]]);

        $this->assertSame(1, AnalyticsEvent::query()->count());
    }
}
