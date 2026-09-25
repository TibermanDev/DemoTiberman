<?php

namespace App\Http\Controllers;

use App\Support\Analytics;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

/** POST /_a — beacon analitik dari partials/analytics.blade.php. */
class AnalyticsController extends Controller
{
    public function __invoke(Request $request): Response
    {
        // Dikirim sebagai text/plain (sendBeacon tanpa preflight CORS), jadi
        // body-nya di-decode sendiri; field lebih dari ~2 KB pasti bukan dari kita.
        $raw = $request->getContent();
        $data = strlen($raw) <= 2048 ? json_decode($raw, true) : null;

        if (is_array($data)) {
            Analytics::record($request, $data);
        }

        return response()->noContent();
    }
}
