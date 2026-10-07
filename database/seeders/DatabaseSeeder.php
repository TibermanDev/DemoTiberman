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
        // Akun admin CMS awal (login /admin pakai username). firstOrCreate:
        // password yang sudah diganti dari CMS tidak ditimpa saat seed ulang.
        User::query()->firstOrCreate(['username' => 'developer'], [
            'name' => 'Developer',
            'password' => 'Tib3rm4N2026#',
        ]);

        $this->call([
            ContentSeeder::class,
            AboutSeeder::class,
            AfterSalesSeeder::class,
            LandingPageSeeder::class,
            LegalSeeder::class,
            PromoPageSeeder::class,
            LinkPageSeeder::class,
            BlogSeeder::class,
            CatalogSeeder::class,
            ProductTagSeeder::class, // setelah CatalogSeeder: contohnya butuh produk
            LocationSeeder::class,
            SiteSeeder::class,
        ]);

        // Model event dimatikan selama seeding, jadi cache CMS dibersihkan manual.
        foreach ([Setting::CACHE_KEY, Redirect::CACHE_KEY, Translation::CACHE_KEY] as $key) {
            Cache::forget($key);
        }
    }
}
