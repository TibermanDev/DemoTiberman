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

if (! function_exists('bg_video')) {
    /**
     * Sumber video latar dari CMS ({video, video_webm, poster}). Selama MP4
     * belum diisi dipakai file bawaan di public/assets/img. WebM hanya ikut
     * kalau memang diisi — kalau MP4 sudah diganti, WebM bawaan tidak boleh
     * ikut karena browser memutarnya lebih dulu (video lama yang tampil).
     *
     * @param  array{video?: ?string, video_webm?: ?string, poster?: ?string}|null  $cms
     * @return array{mp4: string, webm: ?string, poster: ?string}
     */
    function bg_video(?array $cms, string $mp4, ?string $webm, string $poster): array
    {
        $custom = filled($cms['video'] ?? null);

        return [
            'mp4' => $custom ? media($cms['video']) : asset('assets/img/'.$mp4),
            'webm' => $custom ? media($cms['video_webm'] ?? null) : ($webm ? asset('assets/img/'.$webm) : null),
            'poster' => media($cms['poster'] ?? null) ?? asset('assets/img/'.$poster),
        ];
    }
}
