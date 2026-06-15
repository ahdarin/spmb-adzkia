<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class FonnteService
{
    /**
     * Kirim pesan WhatsApp via Fonnte.
     * Selalu mengembalikan boolean dan TIDAK PERNAH melempar exception,
     * agar proses utama (update status di DB) tidak ikut gagal/crash.
     */
    public function kirim(?string $nomor, string $pesan): bool
    {
        // Guard 1: nomor kosong → lewati dengan aman
        if (empty($nomor)) {
            return false;
        }

        $token = config('services.fonnte.token');
        $url   = config('services.fonnte.url', 'https://api.fonnte.com/send');

        // Guard 2: token belum diset → catat log, jangan crash
        if (empty($token)) {
            Log::warning('FONNTE_TOKEN belum diset. Notifikasi WA dilewati.');
            return false;
        }

        $nomor = $this->normalisasiNomor($nomor);

        try {
            $response = Http::withoutVerifying()   // konsisten dgn pola register Anda
                ->timeout(10)
                ->withHeaders(['Authorization' => $token])
                ->post($url, [
                    'target'  => $nomor,
                    'message' => $pesan,
                ]);

            if ($response->failed()) {
                Log::warning('Fonnte gagal mengirim WA', [
                    'nomor'  => $nomor,
                    'status' => $response->status(),
                    'body'   => $response->body(),
                ]);
                return false;
            }

            return true;
        } catch (\Throwable $e) {
            // Timeout / koneksi mati / DNS error semuanya tertangkap di sini
            Log::error('Fonnte exception: ' . $e->getMessage(), ['nomor' => $nomor]);
            return false;
        }
    }

    /**
     * Ubah 08xxxx → 628xxxx dan bersihkan karakter non-digit.
     */
    private function normalisasiNomor(string $nomor): string
    {
        $nomor = preg_replace('/[^0-9]/', '', $nomor);

        if (str_starts_with($nomor, '0')) {
            $nomor = '62' . substr($nomor, 1);
        }

        return $nomor;
    }
}