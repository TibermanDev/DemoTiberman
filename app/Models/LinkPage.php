<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;

/**
 * Halaman linktree untuk bio media sosial: /lp/ (slug "index") dan
 * /lp/{slug}.html (URL lama, mis. /lp/bus.html). Tombolnya di kolom JSON
 * links: [{label, url, icon, highlight}].
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
        return $this->slug === self::INDEX ? url('/lp/') : url('/lp/'.$this->slug.'.html');
    }

    public function path(): string
    {
        return $this->slug === self::INDEX ? '/lp/' : '/lp/'.$this->slug.'.html';
    }
}
