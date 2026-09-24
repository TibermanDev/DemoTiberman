<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;

/** Kiriman form "Become Our Partner" di halaman Contact. */
#[Fillable(['name', 'email', 'phone', 'unit', 'needed_at', 'quantity', 'message', 'read_at'])]
class Inquiry extends Model
{
    protected function casts(): array
    {
        return ['needed_at' => 'date', 'read_at' => 'datetime'];
    }
}
