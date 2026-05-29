<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\DataPendaftar;
use App\Models\MetodePembayaran; 
use Carbon\Carbon;
use App\Models\Prodi;

class DashboardUserController extends Controller
{
    public function index() 
    {
        $pendaftarId = session('pendaftar_id');
        if (!$pendaftarId) return redirect('/login')->withErrors(['error' => 'Silakan login terlebih dahulu!']);

        $mahasiswa = DataPendaftar::find($pendaftarId);
        if (!$mahasiswa) return redirect('/login')->withErrors(['error' => 'Data pendaftar tidak ditemukan!']);

        $semuaMetode = MetodePembayaran::where('is_active', true)->get();
        $bankTransfer   = $semuaMetode->where('kategori', 'Bank Transfer');
        $virtualAccount = $semuaMetode->where('kategori', 'Virtual Account');
        $eWallet        = $semuaMetode->where('kategori', 'E-Wallet');
        
        $batasWaktu = now()->addDays(1)->format('M d, Y (23:59 \W\I\B)');

        return view('user.pembayaran', compact('mahasiswa', 'bankTransfer', 'virtualAccount', 'eWallet', 'batasWaktu'));
    }

    public function pembayaranIndex()
    {
        $pendaftarId = session('pendaftar_id');
        if (!$pendaftarId) return redirect('/login');
        
        $mahasiswa = DataPendaftar::find($pendaftarId);

        $virtualAccount = [
            (object)['nama_provider' => 'MANDIRI', 'nama_bank_lengkap' => 'Bank Mandiri', 'nomor_tujuan' => '1234567890', 'atas_nama' => 'Universitas Adzkia'],
            (object)['nama_provider' => 'BCA', 'nama_bank_lengkap' => 'Bank BCA', 'nomor_tujuan' => '0987654321', 'atas_nama' => 'Universitas Adzkia'],
        ];

        $bankTransfer = [
            (object)['nama_provider' => 'BCA_MANUAL', 'nama_bank_lengkap' => 'BCA (Transfer Manual)', 'nomor_tujuan' => '0987654321', 'atas_nama' => 'Universitas Adzkia'],
            (object)['nama_provider' => 'BNI_MANUAL', 'nama_bank_lengkap' => 'BNI (Transfer Manual)', 'nomor_tujuan' => '1122334455', 'atas_nama' => 'Universitas Adzkia'],
        ];

        $batasWaktu = Carbon::now()->addDays(1)->format('d F Y, H:i');

        return view('user.pembayaran', compact('virtualAccount', 'bankTransfer', 'batasWaktu', 'mahasiswa'));
    }

    public function prosesPembayaran(Request $request)
    {
        $pendaftarId = session('pendaftar_id');
        $pendaftar = \App\Models\DataPendaftar::find($pendaftarId);
        
        // PERBAIKAN: Ganti $user->update jadi $pendaftar->update
        $pendaftar->update([
            'metode_pembayaran' => $request->metode_pembayaran, 
            'nominal_biaya'     => 250000, 
            'status_pembayaran' => 'Validasi', 
        ]);

        return redirect()->route('pendaftaran.validasi')->with('success', 'Pembayaran diproses!');
    }

    public function prosesValidasi(Request $request)
    {
        $request->validate(['pendaftar_id' => 'required|exists:data_pendaftars,id']);
        $pendaftar = \App\Models\DataPendaftar::find($request->pendaftar_id);
        
        if ($pendaftar) {
            $pendaftar->update(['status_pembayaran' => 'Terverifikasi']);
            return back()->with('success', 'Pembayaran berhasil diverifikasi!');
        }

        return back()->with('error', 'Data tidak ditemukan.');
    }

public function dashboardUser()
{
    $pendaftar = \App\Models\DataPendaftar::find(session('pendaftar_id'));

    if (!$pendaftar) {
        return redirect()->route('login')->withErrors(['error' => 'Silakan login kembali.']);
    }

    // Mengarahkan ke halaman dashboard-user bawaan dengan menyertakan data pendaftar
    return view('user.dashboard-user', compact('pendaftar'));
}

    public function validasiUser()
    {
        $pendaftar = \App\Models\DataPendaftar::find(session('pendaftar_id'));
        if (!$pendaftar) return redirect()->route('login')->withErrors(['error' => 'Silakan login kembali.']);

        return view('user.validasi-pembayaran', compact('pendaftar'));
    }

    public function prosesUploadBukti(Request $request)
        {
            $request->validate(['bukti_bayar' => 'required|image|mimes:jpeg,png,jpg|max:2048']);
            $pendaftar = \App\Models\DataPendaftar::find(session('pendaftar_id'));
            
            $path = $request->file('bukti_bayar')->store('bukti_pembayaran', 'public');
            
            $pendaftar->update([
                'bukti_bayar' => $path,
                'status_pembayaran' => 'Menunggu Validasi' // KUNCI: Status ini mengunci agar admin harus melakukan verifikasi manual
            ]);

            return redirect()->route('pendaftaran.validasi')->with('success', 'Bukti berhasil diunggah!');
        }

    public function biodataIndex()
    {
        $pendaftarId = session('pendaftar_id');
        $pendaftar = \App\Models\DataPendaftar::find($pendaftarId);

        if (!$pendaftar) {
            return redirect('/login');
        }

        if (strtolower($pendaftar->status_pembayaran) !== 'terverifikasi') {
            return redirect()->route('pembayaran.index')->with('error', 'Status pembayaran belum terverifikasi.');
        }

        // PERBAIKAN: Menghapus spasi pada Prodi::all()
        $prodis = \App\Models\Prodi::all(); 
        return view('user.formulir', compact('pendaftar', 'prodis')); 
    }

public function simpanBiodata(Request $request)
    {
        // 1. CARI PENDAFTAR BERDASARKAN ID YANG DIKIRIM DARI FORM
        $pendaftar = \App\Models\DataPendaftar::findOrFail($request->pendaftar_id);

        // 2. Validasi
        $request->validate([
            'nama_lengkap'      => 'required|string|max:255',
            'nik'               => 'required|string|max:20',
            'gender'            => 'required',
            'pilihan_jurusan_1' => 'required|string',
            'pilihan_jurusan_2' => 'required|string|different:pilihan_jurusan_1',
            'provinsi'          => 'required|string',
            'kota_kabupaten'    => 'required|string',
            'pas_foto'          => ($pendaftar->pas_foto ? 'nullable' : 'required') . '|file|image|max:2048',
            'scan_ktp'          => ($pendaftar->scan_ktp ? 'nullable' : 'required') . '|file|mimes:jpg,jpeg,png,pdf|max:2048',
            'ijazah_skl'        => ($pendaftar->ijazah_skl ? 'nullable' : 'required') . '|file|mimes:pdf,jpg,png|max:2048',
        ]);

        // 3. Simpan SEMUA teks ke database
        $pendaftar->update([
            'nama_lengkap'      => $request->nama_lengkap,
            'nik'               => $request->nik,
            'agama'             => $request->agama,
            'tempat_lahir'      => $request->tempat_lahir,
            'tanggal_lahir'     => $request->tanggal_lahir,
            'gender'            => $request->gender,
            'email'             => $request->email,
            'no_whatsapp'       => $request->no_whatsapp,
            'alamat_rumah'      => $request->alamat_rumah,
            'provinsi'          => $request->provinsi,
            'kota_kabupaten'    => $request->kota_kabupaten,
            'sekolah_asal'      => $request->sekolah_asal,
            'jurusan_sma'       => $request->jurusan_sma,
            'tahun_lulus'       => $request->tahun_lulus,
            'nilai_akhir'       => $request->nilai_akhir,
            'pilihan_jurusan_1' => $request->pilihan_jurusan_1,
            'pilihan_jurusan_2' => $request->pilihan_jurusan_2,
        ]);

        // 4. Simpan file HANYA JIKA user mengunggah file baru
        if ($request->hasFile('pas_foto')) {
            $pendaftar->update(['pas_foto' => $request->file('pas_foto')->store('dokumen/foto', 'public')]);
        }
        if ($request->hasFile('scan_ktp')) {
            $pendaftar->update(['scan_ktp' => $request->file('scan_ktp')->store('dokumen/ktp', 'public')]);
        }
        if ($request->hasFile('ijazah_skl')) {
            $pendaftar->update(['ijazah_skl' => $request->file('ijazah_skl')->store('dokumen/ijazah', 'public')]);
        }

        // Lanjut ke Konfirmasi
        return redirect()->route('konfirmasi-data', $pendaftar->id)->with('success', 'Data tersimpan!');
    }

    public function tampilkanKonfirmasi($id)
    {
        $pendaftar = \App\Models\DataPendaftar::findOrFail($id);

        if (!$pendaftar) {
            return redirect()->route('pendaftaran.biodata')->with('error', 'Silakan lengkapi biodata terlebih dahulu.');
        }

        return view('user.konfirmasi-data', compact('pendaftar'));
    }

// FUNGSI UNTUK MENAMPILKAN HALAMAN EDIT (Memperbaiki View Not Found)
    public function editBiodata($id)
    {
        $pendaftar = \App\Models\DataPendaftar::findOrFail($id);
        $prodis = \App\Models\Prodi::all();
        
        // Arahkan kembali ke halaman formulir, namun dengan membawa data lama
        return view('user.formulir', compact('pendaftar', 'prodis'));
    }

    public function update(Request $request, $id)
    {
        $pendaftar = \App\Models\DataPendaftar::findOrFail($id);

        $request->validate([
            'nama_lengkap' => 'required',
            'nik'          => 'required|unique:data_pendaftars,nik,' . $pendaftar->id, 
        ]);

        $pendaftar->update($request->all());

        return redirect()->route('konfirmasi-data', ['id' => $pendaftar->id])
                         ->with('success', 'Data berhasil diperbarui!');
    } 

    public function tampilkanValidasiAkhir($id)
    {
        $pendaftar = \App\Models\DataPendaftar::findOrFail($id);
        return view('user.validasi-akhir', compact('pendaftar'));
    }

    public function prosesKonfirmasi($id)
    {
        $pendaftar = \App\Models\DataPendaftar::findOrFail($id);
        $pendaftar->status_pendaftaran = 'menunggu verifikasi'; 
        $pendaftar->save();

        return redirect()->route('pendaftaran.validasiakhir', ['id' => $id]);
    }

    public function tampilkanSukses()
    {
        $pendaftarId = session('pendaftar_id');
        $pendaftar = \App\Models\DataPendaftar::find($pendaftarId);

        if (!$pendaftar) {
            return redirect()->route('dashboard')->with('error', 'Data pendaftaran tidak ditemukan.');
        }
        
        return view('user.sukses', compact('pendaftar'));
    }
}