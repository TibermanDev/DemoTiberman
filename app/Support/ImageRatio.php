<?php

namespace App\Support;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;

/**
 * Rasio "lebar/tinggi" gambar di disk public, untuk --ar pada kartu kategori
 * beranda (lebar kartunya mengikuti rasio gambar masing-masing).
 */
class ImageRatio
{
    public static function of(?string $path): ?string
    {
        if (blank($path) || ! Storage::disk('public')->exists($path)) {
            return null;
        }

        $file = Storage::disk('public')->path($path);

        return Cache::rememberForever('cms.ratio.'.sha1($path.filemtime($file)), function () use ($file) {
            $size = @getimagesize($file);

            return $size ? $size[0].'/'.$size[1] : null;
        });
    }
}
