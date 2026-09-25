<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/** Judul khusus Google + opsi noindex per artikel & produk (tab/section SEO di CMS). */
return new class extends Migration
{
    public function up(): void
    {
        foreach (['posts', 'products'] as $table) {
            Schema::table($table, function (Blueprint $t) {
                $t->string('meta_title')->nullable()->after('meta_description');
                $t->boolean('noindex')->default(false)->after('meta_title');
            });
        }
    }

    public function down(): void
    {
        foreach (['posts', 'products'] as $table) {
            Schema::table($table, function (Blueprint $t) {
                $t->dropColumn(['meta_title', 'noindex']);
            });
        }
    }
};
