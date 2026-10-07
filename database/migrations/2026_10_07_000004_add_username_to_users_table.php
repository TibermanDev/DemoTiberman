<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * Login CMS memakai username, bukan email. Email tetap disimpan tapi
 * opsional. Akun yang sudah ada diberi username dari bagian depan emailnya
 * (admin@tiberman.com -> admin), ditambah id kalau bentrok.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('username')->nullable()->after('name');
            $table->string('email')->nullable()->change();
        });

        $taken = [];
        foreach (DB::table('users')->orderBy('id')->get(['id', 'email']) as $user) {
            $base = strtolower(preg_replace('/[^A-Za-z0-9_.-]/', '', strstr((string) $user->email, '@', true) ?: 'admin')) ?: 'admin';
            $username = isset($taken[$base]) ? $base.$user->id : $base;
            $taken[$username] = true;
            DB::table('users')->where('id', $user->id)->update(['username' => $username]);
        }

        Schema::table('users', function (Blueprint $table) {
            $table->string('username')->nullable(false)->unique()->change();
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropUnique(['username']);
            $table->dropColumn('username');
        });
    }
};
