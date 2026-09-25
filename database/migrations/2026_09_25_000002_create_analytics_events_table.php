<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Analitik pengunjung bawaan (menu Analitik Pengunjung di CMS). Satu baris per
 * tampilan halaman / event konversi. Tidak ada IP maupun data pribadi yang
 * disimpan: `visitor` adalah hash harian yang berganti tiap tengah malam.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('analytics_events', function (Blueprint $table) {
            $table->id();
            $table->string('type', 16);              // pageview | lead | whatsapp
            $table->date('day');                     // tanggal WIB, untuk grafik harian
            $table->string('path', 255);
            $table->string('referrer', 255)->nullable(); // host situs asal; null = langsung/internal
            $table->char('visitor', 16);
            $table->string('device', 16)->nullable();
            $table->string('browser', 32)->nullable();
            $table->string('os', 32)->nullable();
            $table->char('country', 2)->nullable();
            $table->string('utm_source', 100)->nullable();
            $table->string('utm_medium', 100)->nullable();
            $table->string('utm_campaign', 150)->nullable();
            $table->timestamp('created_at')->useCurrent();

            $table->index(['day', 'type']);
            $table->index(['type', 'day', 'visitor']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('analytics_events');
    }
};
