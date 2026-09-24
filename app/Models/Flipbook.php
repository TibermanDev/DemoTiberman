<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;

/**
 * Halaman flipbook PDF di /{slug}. PDF-nya boleh diunggah (pdf_file) atau
 * ditautkan (pdf_url) — pdf.js butuh CORS kalau tautannya beda domain.
 */
#[Fillable(['slug', 'title', 'pdf_file', 'pdf_url', 'is_active'])]
class Flipbook extends Model
{
    protected function casts(): array
    {
        return ['is_active' => 'boolean'];
    }

    public function pdf(): ?string
    {
        return media($this->pdf_file) ?? $this->pdf_url;
    }
}
