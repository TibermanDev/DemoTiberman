<?php

namespace App\Support;

use App\Models\AnalyticsEvent;
use Carbon\CarbonImmutable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

/**
 * Analitik pengunjung bawaan.
 *
 * Pencatatan dikirim oleh partials/analytics.blade.php (navigator.sendBeacon)
 * ke POST /_a, bukan dicatat middleware: bot yang tidak menjalankan
 * JavaScript otomatis tidak terhitung, dan halaman tetap bisa di-cache.
 *
 * Privasi: IP tidak pernah disimpan. `visitor` = hash (tanggal + APP_KEY + IP
 * + user agent) yang berganti tiap hari, jadi pengunjung bisa dihitung unik per
 * hari tanpa bisa dilacak lintas hari. "Pengunjung" untuk satu periode adalah
 * jumlah pengunjung unik harian.
 */
class Analytics
{
    public const TZ = 'Asia/Jakarta';

    public const PERIODS = [
        '7' => '7 hari terakhir',
        '30' => '30 hari terakhir',
        '90' => '90 hari terakhir',
        '365' => '12 bulan terakhir',
    ];

    private const BOTS = '/bot|crawl|spider|slurp|facebookexternalhit|embedly|preview|headless|lighthouse|pingdom|uptime|curl|wget|python|java\/|go-http|axios|node-fetch|phantom|puppeteer|playwright|selenium/i';

    public static function enabled(): bool
    {
        return (bool) cms('site.analytics.enabled', true);
    }

    /** Catat satu event dari beacon. Mengembalikan false kalau diabaikan (bot, admin, data tak valid). */
    public static function record(Request $request, array $data): bool
    {
        $type = $data['t'] ?? null;
        $path = $data['p'] ?? null;
        $ua = (string) $request->userAgent();

        if (! static::enabled() || ! in_array($type, AnalyticsEvent::TYPES, true)
            || ! is_string($path) || ! str_starts_with($path, '/') || str_starts_with($path, '/admin')
            || $ua === '' || preg_match(self::BOTS, $ua)
            || $request->user() !== null) {   // admin yang sedang login tidak dihitung
            return false;
        }

        $now = CarbonImmutable::now(self::TZ);
        parse_str(ltrim((string) ($data['q'] ?? ''), '?'), $query);

        AnalyticsEvent::query()->create([
            'type' => $type,
            'day' => $now->toDateString(),
            'path' => mb_substr(rawurldecode($path), 0, 255),
            'referrer' => static::referrer($data['r'] ?? null, $request->getHost()),
            'visitor' => substr(hash('sha256', $now->toDateString().config('app.key').$request->ip().$ua), 0, 16),
            'device' => static::device($ua),
            'browser' => static::browser($ua),
            'os' => static::os($ua),
            'country' => static::country($request),
            'utm_source' => static::clip($query['utm_source'] ?? null, 100),
            'utm_medium' => static::clip($query['utm_medium'] ?? null, 100),
            'utm_campaign' => static::clip($query['utm_campaign'] ?? null, 150),
        ]);

        return true;
    }

    /** @return array{0: CarbonImmutable, 1: CarbonImmutable} tanggal awal & akhir (WIB), inklusif */
    public static function range(?string $period): array
    {
        $days = (int) (array_key_exists((string) $period, self::PERIODS) ? $period : 30);
        $to = CarbonImmutable::now(self::TZ)->startOfDay();

        return [$to->subDays($days - 1), $to];
    }

    /** Periode sebelumnya dengan panjang sama, untuk perbandingan naik/turun. */
    public static function previousRange(?string $period): array
    {
        [$from, $to] = static::range($period);
        $len = $from->diffInDays($to) + 1;

        return [$from->subDays($len), $from->subDay()];
    }

    public static function query(CarbonImmutable $from, CarbonImmutable $to, ?string $type = AnalyticsEvent::PAGEVIEW): Builder
    {
        return AnalyticsEvent::query()
            // batas akhir sampai 23:59:59: di SQLite cast 'date' tersimpan sebagai
            // "Y-m-d 00:00:00", jadi "<= Y-m-d" saja melewatkan hari terakhir
            ->whereBetween('day', [$from->toDateString(), $to->toDateString().' 23:59:59'])
            ->when($type, fn ($q) => $q->where('type', $type));
    }

    /** Jumlah pengunjung unik harian dalam rentang. */
    public static function visitors(CarbonImmutable $from, CarbonImmutable $to): int
    {
        // Subquery group by, bukan count(distinct day, visitor): yang terakhir
        // tidak didukung SQLite (dipakai test).
        return DB::query()->fromSub(
            static::query($from, $to)->select('day', 'visitor')->groupBy('day', 'visitor')->toBase(), 'v'
        )->count();
    }

    /**
     * Deret harian untuk grafik.
     *
     * @return array{labels: array<string>, visitors: array<int>, pageviews: array<int>}
     */
    public static function daily(CarbonImmutable $from, CarbonImmutable $to): array
    {
        $rows = static::query($from, $to)
            ->selectRaw('day, count(*) as views, count(distinct visitor) as visitors')
            ->groupBy('day')->get()
            ->keyBy(fn ($r) => $r->day->toDateString());

        $out = ['labels' => [], 'visitors' => [], 'pageviews' => []];
        for ($d = $from; $d->lte($to); $d = $d->addDay()) {
            $row = $rows->get($d->toDateString());
            $out['labels'][] = $d->locale('id')->translatedFormat('j M');
            $out['visitors'][] = (int) ($row->visitors ?? 0);
            $out['pageviews'][] = (int) ($row->views ?? 0);
        }

        return $out;
    }

    /**
     * Peringkat nilai satu kolom (halaman, sumber, perangkat, ...).
     *
     * @return Collection<int, object{label: ?string, views: int, visitors: int}>
     */
    public static function top(CarbonImmutable $from, CarbonImmutable $to, string $column, int $limit = 10, ?string $type = AnalyticsEvent::PAGEVIEW): Collection
    {
        return static::query($from, $to, $type)
            ->selectRaw("{$column} as label, count(*) as views, count(distinct visitor) as visitors")
            ->groupBy($column)->orderByDesc('views')->limit($limit)->get()
            ->map(fn ($r) => (object) ['label' => $r->label, 'views' => (int) $r->views, 'visitors' => (int) $r->visitors]);
    }

    private static function referrer(mixed $url, string $ownHost): ?string
    {
        $host = is_string($url) ? parse_url($url, PHP_URL_HOST) : null;
        if (! $host) {
            return null;
        }
        $host = preg_replace('/^(www|m|l|lm)\./', '', strtolower($host));

        return $host === preg_replace('/^www\./', '', strtolower($ownHost)) ? null : mb_substr($host, 0, 255);
    }

    private static function device(string $ua): string
    {
        return match (true) {
            (bool) preg_match('/ipad|tablet|playbook|silk|(android(?!.*mobile))/i', $ua) => 'tablet',
            (bool) preg_match('/mobi|iphone|ipod|android|blackberry|opera mini|iemobile/i', $ua) => 'mobile',
            default => 'desktop',
        };
    }

    private static function browser(string $ua): ?string
    {
        foreach ([
            'Edge' => '/edg(e|a|ios)?\//i', 'Opera' => '/opr\/|opera/i', 'Samsung Internet' => '/samsungbrowser/i',
            'UC Browser' => '/ucbrowser/i', 'Firefox' => '/firefox|fxios/i', 'Chrome' => '/chrome|crios/i', 'Safari' => '/safari/i',
        ] as $name => $re) {
            if (preg_match($re, $ua)) {
                return $name;
            }
        }

        return null;
    }

    private static function os(string $ua): ?string
    {
        foreach ([
            'Android' => '/android/i', 'iOS' => '/iphone|ipad|ipod/i', 'Windows' => '/windows/i',
            'macOS' => '/mac os x|macintosh/i', 'Linux' => '/linux/i',
        ] as $name => $re) {
            if (preg_match($re, $ua)) {
                return $name;
            }
        }

        return null;
    }

    /** Negara dari header CDN (Cloudflare / CloudFront) kalau ada; tidak ada database GeoIP. */
    private static function country(Request $request): ?string
    {
        $c = strtoupper((string) ($request->header('CF-IPCountry') ?? $request->header('CloudFront-Viewer-Country')));

        return preg_match('/^[A-Z]{2}$/', $c) && $c !== 'XX' ? $c : null;
    }

    private static function clip(mixed $value, int $max): ?string
    {
        return is_string($value) && trim($value) !== '' ? mb_substr(trim($value), 0, $max) : null;
    }
}
