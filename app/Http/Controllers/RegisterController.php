<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\DataPendaftar;
use App\Models\Prodi;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Http;

class RegisterController extends Controller
{
    // Menampilkan halaman pendaftaran (Tahap 1 SPMB)
    public function showRegister()
    {
        $prodiList = Prodi::all(); 
        
        // Data Dummy untuk Pilihan Jalur Khusus (Jika belum ada dari database)
        $jalurKhusus = [
            'Prestasi' => [
                (object)['name' => 'Prestasi Akademik (Juara Kelas/Olimpiade)'],
                (object)['name' => 'Prestasi Non-Akademik (Olahraga/Seni)'],
                (object)['name' => 'Tahfidz Al-Quran']
            ],
            'Kemitraan' => [
                (object)['name' => 'Kemitraan Instansi Pemerintah'],
                (object)['name' => 'Rekomendasi Yayasan Adzkia']
            ]
        ];

        return view('user.register', compact('prodiList', 'jalurKhusus'));
    }

    // Memproses data yang dikirim dari form
    public function storeRegister(Request $request)
    {
        // 1. Validasi Input dari Form
        $request->validate([
            'jalur_pendaftaran' => 'required|string',
            'nama_lengkap'      => 'required|string|max:255',
            'nik'               => 'required|numeric|digits:16|unique:data_pendaftars,nik',
            'no_whatsapp'       => 'required|numeric',
            'email'             => 'required|email|unique:data_pendaftars,email',
            'pilihan_jurusan_1' => 'required|string',
            'pilihan_jurusan_2' => 'required|string|different:pilihan_jurusan_1',
            'alamat_rumah'      => 'required|string',
        ], [
            'nik.unique' => 'NIK ini sudah terdaftar. Silakan hubungi admin jika merasa ini kesalahan.',
            'nik.digits' => 'NIK harus terdiri dari 16 angka.',
            'email.unique' => 'Email ini sudah digunakan. Silakan gunakan email lain.',
            'pilihan_jurusan_2.different' => 'Pilihan Jurusan 2 tidak boleh sama dengan Pilihan Jurusan 1.'
        ]);

        // 2. Tentukan Biaya berdasarkan Jalur
        $nominalBiaya = ($request->jalur_pendaftaran === 'Reguler') ? 250000 : 0;
        $statusPembayaran = ($nominalBiaya == 0) ? 'Terverifikasi' : 'Belum Bayar';

        // 3. Generate Nomor Pendaftaran Otomatis berdasarkan Jalur
        $tahun = date('Y');
        $kode = $this->getKodeJalur($request->jalur_pendaftaran);

        $pendaftarTerakhir = DataPendaftar::where('no_pendaftaran', 'like', "%{$kode}-{$tahun}%")
                                        ->orderBy('id', 'desc')
                                        ->first();

        $urutan = $pendaftarTerakhir ? ((int) substr($pendaftarTerakhir->no_pendaftaran, -4) + 1) : 1;
        $noPendaftaran = $kode . '-' . $tahun . '-' . str_pad($urutan, 4, '0', STR_PAD_LEFT);

        // 4. Generate Password Acak 6 Karakter
        $rawPassword = Str::upper(Str::random(6));

        // 5. Simpan ke Database
        $pendaftar = DataPendaftar::create([
            'no_pendaftaran'    => $noPendaftaran,
            'jalur_pendaftaran' => $request->jalur_pendaftaran . ($request->spesifikasi_jalur ? ' - ' . $request->spesifikasi_jalur : ''),
            'nama_lengkap'      => $request->nama_lengkap,
            'nik'               => $request->nik,
            'no_whatsapp'       => $request->no_whatsapp,
            'email'             => $request->email,
            'pilihan_jurusan_1' => $request->pilihan_jurusan_1,
            'pilihan_jurusan_2' => $request->pilihan_jurusan_2,
            'alamat_rumah'      => $request->alamat_rumah,
            'password'          => Hash::make($rawPassword),
            'nominal_biaya'     => $nominalBiaya,
            'status_pembayaran' => $statusPembayaran,
            'status_pendaftaran' => 'Draft'
        ]);

        // 6. KIRIM WA NOTIFIKASI
        if ($pendaftar->no_whatsapp) {
            // Bersihkan nomor (mengubah 08... menjadi 628...)
            $nomorHp = preg_replace('/^0/', '62', $pendaftar->no_whatsapp);
            
            $this->kirimNotifikasiWA(
                $nomorHp, 
                $pendaftar->nama_lengkap, 
                $noPendaftaran, 
                $rawPassword
            );
        }

        // 7. AUTO-LOGIN SESSIONS
        session([
            'pendaftar_id' => $pendaftar->id,
            'nama_pendaftar' => $pendaftar->nama_lengkap,
            'role' => 'user' 
        ]);

        // 8. ARAHKAN KE LOGIN DENGAN DATA AKUN DI SESSION
        return redirect()->route('login')->with('success_register', [
            'username' => $noPendaftaran,
            'password' => $rawPassword,
        ]);
    }

private function kirimNotifikasiWA($nomor, $nama, $username, $password)
    {
        // Ambil token dari .env
        $token = env('FONNTE_TOKEN', 'oNrEA5wZL2XwgeMtvQwV');
        $url   = 'https://api.fonnte.com/send';

        $pesan = "*Pendaftaran SPMB Adzkia Berhasil!* 🎓\n\n" .
                 "Halo *{$nama}*,\n\n" .
                 "Selamat! Data Anda telah kami terima. Berikut adalah detail akun untuk melengkapi biodata dan pembayaran:\n\n" .
                 "👤 *No. Pendaftaran:* {$username}\n" .
                 "🔑 *Password:* *{$password}*\n\n" .
                 "Silakan login melalui link berikut:\n" .
                 "http://spmb.adzkia.ac.id/login\n\n" .
                 "⚠️ *PENTING:* Jangan berikan password ini kepada siapapun.\n" .
                 "Terima kasih.";

        try {
            // Tembak API Fonnte dengan timeout 5 detik
            $response = \Illuminate\Support\Facades\Http::withoutVerifying()
                ->timeout(5) 
                ->withHeaders([
                    'Authorization' => $token,
                ])->post($url, [
                    'target'  => $nomor,
                    'message' => $pesan,
                ]);

            if ($response->failed()) {
                \Illuminate\Support\Facades\Log::warning('Fonnte gagal mengirim WA Registrasi', [
                    'nomor'  => $nomor,
                    'status' => $response->status()
                ]);
                return false;
            }
            
            return true;

        } catch (\Throwable $e) {
            // Jika Fonnte Mati / Timeout, error ditangkap diam-diam di sini!
            \Illuminate\Support\Facades\Log::error('Fonnte Error di Register: ' . $e->getMessage());
            return false;
        }
    }

    private function getKodeJalur($jalur)
{
    // Mengambil kata pertama atau singkatan
    switch ($jalur) {
        case 'Beasiswa Adzkia Unggul (BAU)': return 'BAU';
        case 'Beasiswa PMDK': return 'PMD';
        case 'Beasiswa Prestasi': return 'PRS';
        case 'Beasiswa KIP-K': return 'KIP';
        case 'RPL Afirmasi YASB': return 'RPL-YASB';
        case 'RPL Afirmasi JSIT': return 'RPL-JSIT';
        case 'RPL Kelas Khusus': return 'RPL-KK';
        default: return 'REG'; 
    }
}
}