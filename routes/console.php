<?php

use App\Models\AnalyticsEvent;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// Hapus data analitik pengunjung yang lebih tua dari 13 bulan
// (AnalyticsEvent::prunable). Butuh cron `php artisan schedule:run` tiap menit.
Schedule::command('model:prune', ['--model' => [AnalyticsEvent::class]])->daily();
