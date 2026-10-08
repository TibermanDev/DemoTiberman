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

        // Ditimpa juga kalau isinya beda: storage/app/public tidak ikut di-deploy
        // ulang, jadi kalau aset di public/assets/img diganti dengan nama yang
        // sama, salinan lama di seed/ bakal terus dipakai tanpa pengecekan ini.
        $target = 'seed/'.$file;
        $disk = Storage::disk('public');
        if (! $disk->exists($target) || md5_file($disk->path($target)) !== md5_file($source)) {
            $disk->put($target, file_get_contents($source));
        }

        return $target;
    }
}
