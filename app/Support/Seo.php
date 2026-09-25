<?php

namespace App\Support;

use App\Models\Post;
use Illuminate\Support\Str;

/**
 * Pengaturan SEO seluruh situs (menu Pengaturan → SEO, disimpan di
 * cms('site.seo.*')) dan structured data JSON-LD-nya.
 *
 * Structured data inilah yang dibaca Google untuk "nama situs" di atas URL
 * hasil pencarian (WebSite.name) dan untuk logo/kontak/alamat bisnis
 * (Organization / LocalBusiness).
 */
class Seo
{
    /** Pilihan jenis bisnis schema.org, dari yang paling umum. */
    public const TYPES = [
        'Organization' => 'Organisasi / perusahaan (umum)',
        'LocalBusiness' => 'Bisnis lokal',
        'Store' => 'Toko',
        'AutoPartsStore' => 'Toko suku cadang otomotif',
        'TireShop' => 'Toko ban',
    ];

    public const DAYS = [
        'Monday' => 'Senin', 'Tuesday' => 'Selasa', 'Wednesday' => 'Rabu', 'Thursday' => 'Kamis',
        'Friday' => 'Jumat', 'Saturday' => 'Sabtu', 'Sunday' => 'Minggu',
    ];

    public static function siteName(): string
    {
        return cms('site.seo.site_name') ?: 'Tiberman';
    }

    /** Seluruh situs boleh diindeks? Dimatikan misalnya di server staging. */
    public static function indexable(): bool
    {
        return (bool) cms('site.seo.indexable', true);
    }

    public static function defaultImage(): ?string
    {
        return media(cms('site.seo_image'));
    }

    /** URL absolut; media() bisa mengembalikan path relatif /storage/... */
    public static function absolute(?string $url): ?string
    {
        if (blank($url)) {
            return null;
        }

        return preg_match('#^https?://#i', $url) ? $url : url($url);
    }

    /** Akun media sosial yang benar-benar diisi (bukan "#") untuk sameAs. */
    public static function sameAs(): array
    {
        return collect([...array_values((array) cms('site.social', [])), ...array_values((array) cms('site.marketplace', []))])
            ->filter(fn ($u) => is_string($u) && preg_match('#^https?://#i', $u))
            ->values()->all();
    }

    /** @return array<string, mixed> */
    public static function graph(): array
    {
        $home = url('/').'/';
        $org = array_filter([
            '@type' => cms('site.seo.business_type') ?: 'Organization',
            '@id' => $home.'#organization',
            'name' => static::siteName(),
            'legalName' => cms('site.company_name'),
            'url' => $home,
            'logo' => static::absolute(media(cms('site.seo.logo')) ?? Favicon::url()),
            'image' => static::absolute(static::defaultImage()),
            'description' => cms('site.seo_description'),
            'telephone' => cms('site.phone'),
            'email' => cms('site.email'),
            'address' => static::address(),
            'openingHoursSpecification' => static::openingHours(),
            'sameAs' => static::sameAs(),
            'foundingDate' => cms('site.seo.founding_year'),
        ]);

        $site = array_filter([
            '@type' => 'WebSite',
            '@id' => $home.'#website',
            'name' => static::siteName(),
            'alternateName' => array_values(array_filter(array_map('trim', explode(',', (string) cms('site.seo.alternate_names'))))) ?: null,
            'url' => $home,
            'inLanguage' => 'id-ID',
            'publisher' => ['@id' => $home.'#organization'],
        ]);

        return ['@context' => 'https://schema.org', '@graph' => [$site, $org]];
    }

    /**
     * Structured data satu artikel blog. Sengaja dibangun di sini, bukan di
     * view: Blade membaca teks '@context' di template sebagai directive
     *
     * @context dan merusak JSON-nya.
     *
     * @return array<string, mixed>
     */
    public static function article(Post $post): array
    {
        $org = ['@id' => url('/').'/#organization'];

        return array_filter([
            '@context' => 'https://schema.org',
            '@type' => 'BlogPosting',
            'headline' => Str::limit($post->title, 110, ''),
            'description' => $post->meta_description ?: $post->excerpt,
            'image' => static::absolute(media($post->cover_image)),
            'datePublished' => $post->published_at?->toIso8601String(),
            'dateModified' => $post->updated_at?->toIso8601String(),
            'author' => $post->author ? ['@type' => 'Person', 'name' => $post->author] : $org,
            'publisher' => $org,
            'mainEntityOfPage' => $post->url(),
            'articleSection' => $post->category?->name,
        ]);
    }

    /** JSON aman untuk ditaruh di <script> (JSON_HEX_TAG mencegah "</script>" di dalam teks). */
    public static function json(array $data): string
    {
        return json_encode($data, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_HEX_TAG);
    }

    private static function address(): ?array
    {
        $a = (array) cms('site.seo.address', []);
        if (blank($a['street'] ?? null) && blank($a['city'] ?? null)) {
            return null;
        }

        return array_filter([
            '@type' => 'PostalAddress',
            'streetAddress' => $a['street'] ?? null,
            'addressLocality' => $a['city'] ?? null,
            'addressRegion' => $a['region'] ?? null,
            'postalCode' => $a['postal_code'] ?? null,
            'addressCountry' => 'ID',
        ]);
    }

    private static function openingHours(): ?array
    {
        $rows = collect((array) cms('site.seo.opening_hours', []))
            ->filter(fn ($r) => filled($r['days'] ?? null) && filled($r['opens'] ?? null) && filled($r['closes'] ?? null))
            ->map(fn ($r) => [
                '@type' => 'OpeningHoursSpecification',
                'dayOfWeek' => array_values((array) $r['days']),
                'opens' => substr($r['opens'], 0, 5),
                'closes' => substr($r['closes'], 0, 5),
            ])->values()->all();

        return $rows ?: null;
    }
}
