<?php

namespace App\Http\Controllers;

use App\Models\Location;
use Illuminate\View\View;

class PageController extends Controller
{
    public function home(): View
    {
        return view('home', [
            'home' => cms('home', []),
            'areas' => $this->areas(),
        ]);
    }

    public function superarea(): View
    {
        $locations = Location::query()->active()->get();

        return view('superarea', [
            'page' => cms('superarea', []),
            'locations' => $locations,
            'areas' => $this->areas($locations),
        ]);
    }

    public function contact(): View
    {
        return view('contact', ['page' => cms('contact', [])]);
    }

    /**
     * Keterangan pin peta untuk assets/js/superarea-spots.js, dikunci nama kota
     * di tabel pinnya (map_key). Posisi pin tetap di JS karena menempel ke gambar.
     */
    private function areas($locations = null): array
    {
        return ($locations ?? Location::query()->active()->get())
            ->filter(fn (Location $l) => filled($l->map_key))
            ->mapWithKeys(fn (Location $l) => [$l->map_key => [
                'nama' => $l->name,
                'kode' => (string) $l->code,
                'alias' => (string) $l->alias,
                'lat' => $l->lat,
                'lng' => $l->lng,
                'addr' => $l->address,
            ]])
            ->all();
    }
}
