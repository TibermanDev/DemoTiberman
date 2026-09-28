<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Flash Card sekarang gambar JPG per produk yang tampil di popup, bukan
 * tautan keluar — kolom tautannya diganti kolom file gambar.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('products', function (Blueprint $t) {
            $t->string('flashcard_image')->nullable()->after('ecatalog_url');
            $t->dropColumn('flashcard_url');
        });
    }

    public function down(): void
    {
        Schema::table('products', function (Blueprint $t) {
            $t->string('flashcard_url')->nullable()->after('ecatalog_url');
            $t->dropColumn('flashcard_image');
        });
    }
};
