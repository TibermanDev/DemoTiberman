<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Support\Facades\Http;
use Throwable;

/**
 * Token Cloudflare Turnstile (field cf-turnstile-response) diverifikasi ke
 * server Cloudflare. Token hanya berlaku sekali dan ±5 menit, jadi form yang
 * gagal harus meminta token baru (lihat contact-form.js).
 */
class Turnstile implements ValidationRule
{
    /** Tetap dijalankan walau field-nya tidak dikirim sama sekali. */
    public bool $implicit = true;

    public function __construct(private ?string $ip = null) {}

    /** Pengecekan mati kalau secret key belum diisi (mis. di lingkungan lokal). */
    public static function enabled(): bool
    {
        return filled(config('services.turnstile.secret_key'));
    }

    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (! static::enabled()) {
            return;
        }

        if (blank($value) || ! is_string($value)) {
            $fail('Selesaikan verifikasi captcha terlebih dahulu.');

            return;
        }

        try {
            $ok = Http::asForm()->timeout(10)
                ->post('https://challenges.cloudflare.com/turnstile/v0/siteverify', array_filter([
                    'secret' => config('services.turnstile.secret_key'),
                    'response' => $value,
                    'remoteip' => $this->ip,
                ]))
                ->json('success') === true;
        } catch (Throwable) {
            $ok = false;
        }

        if (! $ok) {
            $fail('Verifikasi captcha gagal. Silakan coba lagi.');
        }
    }
}
