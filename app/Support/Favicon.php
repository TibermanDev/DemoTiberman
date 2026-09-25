<?php

namespace App\Support;

use Illuminate\Support\Facades\Storage;

/**
 * URL favicon dari Pengaturan Situs. Kalau opsi "bulat" aktif, gambarnya
 * dipotong jadi lingkaran 192px bersudut transparan — browser menampilkan
 * favicon apa adanya, jadi gambar persegi berlatar putih tampil sebagai kotak.
 * Hasil potongan disimpan sekali per gambar sumber (favicon/{hash}.png).
 */
class Favicon
{
    // Kelipatan 48 px: syarat favicon di hasil pencarian Google.
    private const SIZE = 192;

    public static function url(): ?string
    {
        $path = cms('site.favicon');
        if (blank($path)) {
            return null;
        }
        if (! cms('site.favicon_round', true)) {
            return media($path);
        }

        $disk = Storage::disk('public');
        if (! $disk->exists($path)) {
            return media($path);
        }

        $target = 'favicon/'.sha1($path.$disk->lastModified($path).self::SIZE).'.png';
        if (! $disk->exists($target)) {
            $png = self::circle($disk->get($path));
            if ($png === null) {
                return media($path);
            }
            $disk->put($target, $png);
        }

        return media($target);
    }

    private static function circle(string $bytes): ?string
    {
        $src = @imagecreatefromstring($bytes);
        if ($src === false) {
            return null; // mis. .ico / .svg — tidak bisa dibaca GD, pakai aslinya
        }

        // ambil persegi di tengah, lalu kecilkan ke SIZE
        $w = imagesx($src);
        $h = imagesy($src);
        $side = min($w, $h);
        $size = self::SIZE;

        $out = imagecreatetruecolor($size, $size);
        imagealphablending($out, false);
        imagesavealpha($out, true);
        imagefill($out, 0, 0, imagecolorallocatealpha($out, 0, 0, 0, 127));
        imagecopyresampled($out, $src, 0, 0, intdiv($w - $side, 2), intdiv($h - $side, 2), $size, $size, $side, $side);

        // topeng lingkaran; tepinya dihaluskan 1px supaya tidak bergerigi
        $r = $size / 2;
        for ($y = 0; $y < $size; $y++) {
            for ($x = 0; $x < $size; $x++) {
                $d = sqrt(($x + .5 - $r) ** 2 + ($y + .5 - $r) ** 2);
                $cover = max(0.0, min(1.0, $r - $d));
                if ($cover >= 1) {
                    continue;
                }
                $c = imagecolorsforindex($out, imagecolorat($out, $x, $y));
                $alpha = (int) round(127 - (127 - $c['alpha']) * $cover);
                imagesetpixel($out, $x, $y, imagecolorallocatealpha($out, $c['red'], $c['green'], $c['blue'], $alpha));
            }
        }

        ob_start();
        imagepng($out);

        return ob_get_clean();
    }
}
