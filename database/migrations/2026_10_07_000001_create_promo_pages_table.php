<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Halaman promosi/artikel SEO di /{slug} (/aeolus-tyre, /promo-tiberman,
     * ...). Isinya disusun dari blok (kolom JSON blocks) lewat Builder di CMS.
     */
    public function up(): void
    {
        Schema::create('promo_pages', function (Blueprint $table) {
            $table->id();
            $table->string('slug')->unique();
            $table->string('title');
            $table->string('banner_image')->nullable();
            $table->string('banner_alt')->nullable();
            $table->json('blocks')->nullable();
            $table->string('cta_heading')->nullable();
            $table->string('cta_label')->nullable();
            $table->string('cta_url')->nullable();
            $table->string('cta_image')->nullable();
            $table->string('meta_title')->nullable();
            $table->text('meta_description')->nullable();
            $table->string('meta_image')->nullable();
            $table->boolean('noindex')->default(false);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('promo_pages');
    }
};
