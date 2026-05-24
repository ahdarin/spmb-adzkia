<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\DataPendaftar;
use App\Models\Prodi;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class RegisterController extends Controller
{
    // Menampilkan Form Pendaftaran
    public function showRegister()
    {
        $prodiList = \App\Models\Prodi::all(); 
        return view('user.register', compact('prodiList'));
    }

    // Memproses Pendaftaran & Menampilkan Detail Akun
    public function storeRegister(Request $request)
    {
        $request->validate([
            'nama_lengkap' => 'required|string|max:255',
            'nik'          => 'required|numeric|digits:16|unique:data_pendaftars,nik',
            'no_whatsapp'  => 'required|numeric',
            'email'        => 'required|email|unique:data_pendaftars,email',
            'pilihan_jurusan_1' => 'required',
            'pilihan_jurusan_2' => 'required|different:pilihan_jurusan_1',
            'alamat_rumah'      => 'required|string',
        ]);

        $tahun = date('Y');
        $jumlahReguler = DataPendaftar::where('jalur_pendaftaran', 'Reguler')->count() + 1;
        $noPendaftaran = 'REG-' . $tahun . '-' . str_pad($jumlahReguler, 4, '0', STR_PAD_LEFT);
        $rawPassword = Str::random(6);

        $pendaftar = DataPendaftar::create([
            'no_pendaftaran'    => $noPendaftaran,
            'jalur_pendaftaran' => 'Reguler',
            'nama_lengkap'      => $request->nama_lengkap,
            'nik'               => $request->nik,
            'no_whatsapp'       => $request->no_whatsapp,
            'email'             => $request->email,
            'pilihan_jurusan_1' => $request->pilihan_jurusan_1,
            'pilihan_jurusan_2' => $request->pilihan_jurusan_2,
            'alamat_rumah'      => $request->alamat_rumah,
            'password'          => Hash::make($rawPassword),
            'nominal_biaya'     => 250000,
            'status_pembayaran' => 'Belum Bayar',
        ]);

        $this->kirimNotifikasiWA($pendaftar->no_whatsapp, $pendaftar->nama_lengkap, $noPendaftaran, $rawPassword);
        
        return redirect()->route('login')->with([
            'success' => 'Pendaftaran Berhasil!',
            'no_pendaftaran' => $noPendaftaran,
            'password'       => $rawPassword
        ]);
    }

    // Proses Login
    public function loginProses(Request $request)
    {
        $request->validate([
            'login_input' => 'required',
            'password'    => 'required',
        ]);

        $pendaftar = DataPendaftar::where('email', $request->login_input)
            ->orWhere('no_pendaftaran', $request->login_input)
            ->first();

        if ($pendaftar && Hash::check($request->password, $pendaftar->password)) {
             session()->put('pendaftar_id', $pendaftar->id); 
             session()->put('nama_pendaftar', $pendaftar->nama_lengkap);
             
             return redirect()->route('pembayaran.index');
        }
    }

    // Simpan Metode Pembayaran
    public function simpanPembayaran(Request $request)
    {
        $pendaftarId = session('pendaftar_id');
        if (!$pendaftarId) return back()->withErrors(['error' => 'Sesi habis, silakan login kembali.']);

        $pendaftar = DataPendaftar::find($pendaftarId);
        if ($pendaftar) {
            $pendaftar->update([
                'metode_pembayaran' => $request->metode_pembayaran,
                'status_pembayaran' => 'Menunggu Upload'
            ]);
            return redirect()->route('pendaftaran.validasi');
        }
        return back()->withErrors(['error' => 'Data pendaftar tidak ditemukan.']);
    }

    // FUNGSI BARU: Memproses Upload Bukti Bayar
    public function prosesUploadBukti(Request $request)
{
    // Validasi file
    $request->validate(['bukti_bayar' => 'required|image|mimes:jpeg,png,jpg|max:2048']);
    
    // Ambil user dari session
    $pendaftarId = session('pendaftar_id');
    $pendaftar = DataPendaftar::find($pendaftarId);

    // Cek apakah pendaftar ketemu
    if (!$pendaftar) {
        return back()->withErrors(['error' => 'Data tidak ditemukan, coba login ulang!']);
    }

    // Proses upload
    if ($request->hasFile('bukti_bayar')) {
        $path = $request->file('bukti_bayar')->store('bukti_pembayaran', 'public');
        
        // UPDATE DATABASE
        $pendaftar->bukti_bayar = $path;
        $pendaftar->status_pembayaran = 'Menunggu Validasi'; // Sesuaikan status ini
        $pendaftar->save(); // PAKAI SAVE() SUPAYA LEBIH PASTI

        return back()->with('success', 'Berhasil!');
    }
    
    return back()->withErrors(['error' => 'Gagal upload']);
}

    private function kirimNotifikasiWA($nomor, $nama, $username, $password)
    {
        $isiPesan = "Halo {$nama}, akun anda: {$username} Pass: {$password}";
        logger($isiPesan);
    }
}