<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;

/**
 * URL ukuran toko lama: /kategori-produk/ukuran-ban/{slug}. label harus sama
 * persis dengan kolom size produk supaya chip ukurannya jadi tautan.
 */
#[Fillable(['slug', 'label'])]
class TireSize extends Model {}
