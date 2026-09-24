<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;

/**
 * Satu SuperArea. map_key = nama kota di tabel pin assets/js/superarea-spots.js
 * (SURABAYA, GRESIK, ...) — posisi pin menempel ke gambar peta, jadi yang
 * diatur di sini hanya isi keterangannya.
 */
#[Fillable([
    'name', 'note', 'region', 'address', 'maps_url', 'image', 'map_key', 'code', 'alias', 'lat', 'lng',
    'show_in_footer', 'is_active', 'sort_order',
])]
class Location extends Model
{
    protected function casts(): array
    {
        return [
            'lat' => 'float',
            'lng' => 'float',
            'show_in_footer' => 'boolean',
            'is_active' => 'boolean',
        ];
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true)->orderBy('sort_order')->orderBy('id');
    }

    /**
     * Tujuan klik kartu: tautan yang diisi di CMS, kalau kosong dibuatkan dari
     * koordinat pin, kalau itu pun kosong cari berdasarkan alamat.
     */
    public function mapsUrl(): string
    {
        if (filled($this->maps_url)) {
            return $this->maps_url;
        }

        $query = $this->lat !== null && $this->lng !== null
            ? $this->lat.','.$this->lng
            : 'Tiberman '.$this->name.', '.$this->address;

        return 'https://www.google.com/maps/search/?api=1&query='.rawurlencode($query);
    }

    public function label(): string
    {
        return $this->note ? "{$this->name} ( {$this->note} )" : $this->name;
    }
}
