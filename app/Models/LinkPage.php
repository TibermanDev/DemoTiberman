<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;

/**
 * Halaman linktree untuk bio media sosial — hanya satu, di /lp/ (slug
 * "index"). /lp/{slug}.html diarahkan ke landing page promo oleh
 * LinkPageController. Tombolnya di kolom JSON links: [{label, url, icon, highlight}].
 */
#[Fillable(['slug', 'title', 'subtitle', 'avatar', 'links', 'meta_title', 'meta_description', 'noindex', 'is_active'])]
class LinkPage extends Model
{
    public const INDEX = 'index';

    /** Ikon yang tersedia untuk tombol (resources/views/partials/link-icon.blade.php). */
    public const ICONS = [
        'link' => 'Tautan biasa',
        'whatsapp' => 'WhatsApp',
        'phone' => 'Telepon',
        'email' => 'Email',
        'web' => 'Website',
        'catalog' => 'Katalog / PDF',
        'map' => 'Lokasi / Maps',
        'shopee' => 'Shopee',
        'tokopedia' => 'Tokopedia',
        'instagram' => 'Instagram',
        'tiktok' => 'TikTok',
        'youtube' => 'YouTube',
        'facebook' => 'Facebook',
        'linkedin' => 'LinkedIn',
    ];

    protected function casts(): array
    {
        return [
            'links' => 'array',
            'noindex' => 'boolean',
            'is_active' => 'boolean',
        ];
    }

    public function url(): string
    {
        return url('/lp/');
    }

    public function path(): string
    {
        return '/lp/';
    }
}
