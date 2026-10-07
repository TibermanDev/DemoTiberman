<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Tag produk untuk SEO di /tag-produk/{slug} (URL toko lama). Produknya
     * dipilih manual per tag; tag tidak tampil di sidebar katalog.
     */
    public function up(): void
    {
        Schema::create('product_tags', function (Blueprint $table) {
            $table->id();
            $table->string('slug')->unique();
            $table->string('heading');
            $table->string('meta_title')->nullable();
            $table->text('meta_description')->nullable();
            $table->boolean('noindex')->default(false);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('product_product_tag', function (Blueprint $table) {
            $table->foreignId('product_tag_id')->constrained()->cascadeOnDelete();
            $table->foreignId('product_id')->constrained()->cascadeOnDelete();
            $table->primary(['product_tag_id', 'product_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('product_product_tag');
        Schema::dropIfExists('product_tags');
    }
};
