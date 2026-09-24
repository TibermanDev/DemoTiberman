<?php

namespace Database\Seeders;

use App\Models\Redirect;
use App\Models\Setting;
use App\Models\Translation;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Cache;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Akun admin CMS awal — WAJIB ganti password setelah login pertama.
        User::query()->firstOrCreate(['email' => 'admin@tiberman.com'], [
            'name' => 'Admin Tiberman',
            'password' => 'password',
        ]);

        $this->call([
            ContentSeeder::class,
            BlogSeeder::class,
            CatalogSeeder::class,
            LocationSeeder::class,
            SiteSeeder::class,
        ]);

        // Model event dimatikan selama seeding, jadi cache CMS dibersihkan manual.
        foreach ([Setting::CACHE_KEY, Redirect::CACHE_KEY, Translation::CACHE_KEY] as $key) {
            Cache::forget($key);
        }
    }
}
