<?php

use App\Models\Setting;
use Database\Seeders\CopiesImages;
use Illuminate\Database\Migrations\Migration;

/**
 * Video latar beranda (Importir & Kenapa Tiberman) kini diatur dari CMS.
 * Isi datanya dengan video bawaan supaya langsung tampil di panel; nilai
 * yang sudah ada tidak ditimpa.
 */
return new class extends Migration
{
    use CopiesImages;

    public function up(): void
    {
        $home = Setting::group('home');
        if ($home === []) {
            return; // belum di-seed; ContentSeeder yang akan mengisinya
        }

        $defaults = [
            'importir' => ['video' => 'tires-moving.mp4', 'video_webm' => 'tires-moving.webm', 'poster' => 'tires-moving-poster.webp'],
            'why' => ['video' => 'warehouse-loop-web.mp4', 'video_webm' => null, 'poster' => 'warehouse-dark.webp'],
        ];

        foreach ($defaults as $group => $files) {
            foreach ($files as $field => $file) {
                if (! array_key_exists($field, $home[$group] ?? [])) {
                    $home[$group][$field] = $this->img($file);
                }
            }
        }

        Setting::put('home', $home);
    }
};
