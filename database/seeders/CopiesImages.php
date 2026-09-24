<?php

namespace Database\Seeders;

use Illuminate\Support\Facades\Storage;

/**
 * Gambar bawaan situs disalin ke disk public (storage/app/public/seed) supaya
 * semua gambar yang dikelola CMS tinggal di tempat yang sama dan bisa diganti
 * atau dihapus dari panel admin tanpa menyentuh public/assets.
 */
trait CopiesImages
{
    protected function img(?string $file): ?string
    {
        if (! $file) {
            return null;
        }

        $source = public_path('assets/img/'.$file);
        if (! is_file($source)) {
            return null;
        }

        $target = 'seed/'.$file;
        if (! Storage::disk('public')->exists($target)) {
            Storage::disk('public')->put($target, file_get_contents($source));
        }

        return $target;
    }
}
