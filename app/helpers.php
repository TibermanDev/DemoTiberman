<?php

use App\Models\Setting;

if (! function_exists('cms')) {
    /**
     * Isi CMS lewat dot path: segmen pertama nama grupnya (site, home,
     * contact, news, superarea, catalog), sisanya kunci di dalam grup itu.
     */
    function cms(string $path, mixed $default = null): mixed
    {
        return data_get(Setting::groups(), $path, $default) ?? $default;
    }
}

if (! function_exists('media')) {
    /**
     * URL berkas yang diunggah lewat CMS (disk public). URL absolut dan path
     * yang diawali "/" dibiarkan apa adanya, jadi kolom gambar juga boleh
     * diisi tautan luar.
     */
    function media(?string $path): ?string
    {
        if (blank($path)) {
            return null;
        }
        if (str_starts_with($path, '/') || preg_match('#^https?://#', $path)) {
            return $path;
        }

        return asset('storage/'.$path);
    }
}

if (! function_exists('rich')) {
    /**
     * Teks pendek yang boleh memuat tebal/miring/tautan dan baris baru —
     * dipakai untuk judul dan paragraf halaman yang diisi dari CMS.
     */
    function rich(?string $text): string
    {
        return nl2br(strip_tags((string) $text, '<b><strong><i><em><a><br>'), false);
    }
}
