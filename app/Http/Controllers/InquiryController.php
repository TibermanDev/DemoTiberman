<?php

namespace App\Http\Controllers;

use App\Models\Inquiry;
use App\Rules\Turnstile;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

/** Form "Become Our Partner" di halaman Contact; hasilnya masuk menu Permintaan di CMS. */
class InquiryController extends Controller
{
    public function store(Request $request): JsonResponse|RedirectResponse
    {
        $data = $request->validate([
            'nama' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255'],
            'telepon' => ['required', 'string', 'max:50'],
            'unit' => ['nullable', 'string', 'max:255'],
            'tanggal' => ['nullable', 'date'],
            'jumlah' => ['nullable', 'string', 'max:255'],
            'pesan' => ['nullable', 'string', 'max:5000'],
            'cf-turnstile-response' => [new Turnstile($request->ip())],
        ]);

        Inquiry::query()->create([
            'name' => $data['nama'],
            'email' => $data['email'],
            'phone' => $data['telepon'],
            'unit' => $data['unit'] ?? null,
            'needed_at' => $data['tanggal'] ?? null,
            'quantity' => $data['jumlah'] ?? null,
            'message' => $data['pesan'] ?? null,
        ]);

        return $request->expectsJson()
            ? response()->json(['ok' => true])
            : back()->with('inquiry_sent', true);
    }
}
