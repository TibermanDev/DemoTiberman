<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Isi halaman & pengaturan situs: satu baris per grup (site, page.home, ...)
        Schema::create('settings', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique();
            $table->json('value')->nullable();
            $table->timestamps();
        });

        Schema::create('post_categories', function (Blueprint $table) {
            $table->id();
            $table->string('slug')->unique();
            $table->string('name');
            $table->string('heading');
            $table->unsignedInteger('sort_order')->default(0);
            $table->timestamps();
        });

        Schema::create('posts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('post_category_id')->constrained()->restrictOnDelete();
            $table->string('title');
            $table->string('slug')->unique();
            $table->text('excerpt')->nullable();
            $table->string('cover_image')->nullable();
            $table->string('cover_alt')->nullable();
            $table->string('cover_caption')->nullable();
            $table->longText('body')->nullable();
            $table->string('author')->default('tiberman');
            $table->timestamp('published_at')->nullable();
            $table->boolean('is_published')->default(true);
            $table->boolean('is_featured')->default(false);
            $table->boolean('is_popular')->default(false);
            $table->string('meta_description', 500)->nullable();
            $table->timestamps();
        });

        // Tag artikel = kategori lain yang ikut ditautkan di bawah artikel.
        Schema::create('post_tag', function (Blueprint $table) {
            $table->foreignId('post_id')->constrained()->cascadeOnDelete();
            $table->foreignId('post_category_id')->constrained()->cascadeOnDelete();
            $table->primary(['post_id', 'post_category_id']);
        });

        Schema::create('catalog_units', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique();
            $table->string('label');
            $table->string('path')->unique();
            $table->json('aliases')->nullable();
            $table->boolean('show_in_nav')->default(true);
            $table->unsignedInteger('sort_order')->default(0);
            $table->timestamps();
        });

        Schema::create('brands', function (Blueprint $table) {
            $table->id();
            $table->string('slug')->unique();
            $table->string('name');
            $table->unsignedInteger('sort_order')->default(0);
            $table->timestamps();
        });

        Schema::create('tire_sizes', function (Blueprint $table) {
            $table->id();
            $table->string('slug')->unique();
            $table->string('label')->unique();
            $table->timestamps();
        });

        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->foreignId('catalog_unit_id')->constrained()->restrictOnDelete();
            $table->foreignId('brand_id')->nullable()->constrained()->nullOnDelete();
            $table->string('name');
            $table->string('slug')->unique();
            $table->string('size');
            $table->string('compat')->nullable();
            $table->string('image')->nullable();
            $table->unsignedInteger('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            // halaman detail & modal katalog
            $table->string('logo')->nullable();
            $table->string('logo_light')->nullable();
            $table->string('hero_image')->nullable();
            $table->text('description')->nullable();
            $table->json('features')->nullable();
            $table->json('pairs')->nullable();
            $table->json('gallery')->nullable();
            $table->json('specs')->nullable();
            $table->json('available_sizes')->nullable();
            $table->string('ecatalog_url')->nullable();
            $table->string('flashcard_url')->nullable();
            $table->string('whatsapp_url')->nullable();
            $table->string('shopee_url')->nullable();
            $table->string('tokopedia_url')->nullable();
            $table->string('meta_description', 500)->nullable();
            $table->timestamps();
        });

        Schema::create('locations', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('note')->nullable();
            $table->string('region');
            $table->string('address');
            $table->string('image')->nullable();
            $table->string('map_key')->nullable();
            $table->string('code', 10)->nullable();
            $table->string('alias', 10)->nullable();
            $table->decimal('lat', 17, 14)->nullable();
            $table->decimal('lng', 17, 14)->nullable();
            $table->boolean('show_in_footer')->default(true);
            $table->boolean('is_active')->default(true);
            $table->unsignedInteger('sort_order')->default(0);
            $table->timestamps();
        });

        Schema::create('flipbooks', function (Blueprint $table) {
            $table->id();
            $table->string('slug')->unique();
            $table->string('title');
            $table->string('pdf_file')->nullable();
            $table->string('pdf_url')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('redirects', function (Blueprint $table) {
            $table->id();
            $table->string('from_path')->unique();
            $table->string('to_path');
            $table->unsignedSmallInteger('status_code')->default(301);
            $table->timestamps();
        });

        // Kamus alih bahasa assets/js/i18n.js, dikunci teks Indonesia. Kolom
        // text tidak bisa di-unique-kan di MySQL, jadi yang unik hash-nya.
        Schema::create('translations', function (Blueprint $table) {
            $table->id();
            $table->char('source_hash', 40)->unique();
            $table->text('source');
            $table->text('en')->nullable();
            $table->text('zh')->nullable();
            $table->timestamps();
        });

        Schema::create('inquiries', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email');
            $table->string('phone');
            $table->string('unit')->nullable();
            $table->date('needed_at')->nullable();
            $table->string('quantity')->nullable();
            $table->text('message')->nullable();
            $table->timestamp('read_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        foreach (['inquiries', 'translations', 'redirects', 'flipbooks', 'locations', 'products', 'tire_sizes', 'brands', 'catalog_units', 'post_tag', 'posts', 'post_categories', 'settings'] as $table) {
            Schema::dropIfExists($table);
        }
    }
};
