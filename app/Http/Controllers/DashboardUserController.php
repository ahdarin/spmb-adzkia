<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\DataPendaftar;
use App\Models\Prodi;

class DashboardUserController extends Controller
{
    // ==========================================
    // 1. DASHBOARD UTAMA
    // ==========================================
    public function dashboardUser()
    {
        $pendaftar = \App\Models\DataPendaftar::find(session('pendaftar_id'));

        if (!$pendaftar) {
            return redirect()->route('login')->withErrors(['error' => 'Silakan login kembali.']);
        }

        // Ambil 2 Berita terbaru untuk ditampilkan di dashboard
        $berita = \App\Models\Berita::latest()->take(2)->get();
        $faqs = \App\Models\Faq::where('kategori', 'Dashboard User')->get();

        // Kirim $pendaftar dan $berita ke tampilan
        return view('user.dashboard-user', compact('pendaftar', 'berita', 'faqs'));
    }

    // ==========================================
    // 2. PEMBAYARAN (STEP 2 & 3)
    // ==========================================
    public function pembayaranIndex()
    {
        $pendaftar = DataPendaftar::find(session('pendaftar_id'));
        if (!$pendaftar) return redirect('/login')->with('error', 'Sesi Anda telah habis.');

        return view('user.pembayaran', compact('pendaftar'));
    }

    public function prosesUploadBukti(Request $request)
    {
        $request->validate([
            'bukti_pembayaran' => 'required|image|mimes:jpg,jpeg,png,pdf|max:2048', 
        ], [
            'bukti_pembayaran.required' => 'File bukti pembayaran wajib diisi.',
            'bukti_pembayaran.max' => 'Ukuran file maksimal 2MB.'
        ]);

        $pendaftar = DataPendaftar::find(session('pendaftar_id'));

        // Simpan nama bank/qris yang dipilih
        if ($request->filled('metode_pembayaran')) {
            $pendaftar->metode_pembayaran = $request->metode_pembayaran;
        }

        if ($request->hasFile('bukti_pembayaran')) {
            $file = $request->file('bukti_pembayaran');
            $filename = $pendaftar->id . '_' . time() . '_' . str_replace(' ', '_', $file->getClientOriginalName());
            
            $file->move(public_path('uploads/bukti_bayar'), $filename);
            
            $pendaftar->bukti_pembayaran = $filename;
            $pendaftar->status_pembayaran = 'Menunggu Validasi';
        }

        $pendaftar->save();
        return back()->with('success', 'Bukti pembayaran berhasil diunggah! Harap tunggu proses validasi oleh Admin.');
    }

    // ==========================================
    // 3. BIODATA & DOKUMEN (STEP 4)
    // ==========================================
    public function biodataIndex()
    {
        $pendaftar = DataPendaftar::find(session('pendaftar_id'));
        if (!$pendaftar) return redirect('/login');

        if (strtolower($pendaftar->status_pembayaran) !== 'terverifikasi') {
            return redirect()->route('pembayaran.index')->with('error', 'Selesaikan pembayaran terlebih dahulu.');
        }

        $prodis = Prodi::all(); 
        return view('user.formulir', compact('pendaftar', 'prodis')); 
    }

    public function simpanBiodata(Request $request)
    {
        $pendaftarId = session('pendaftar_id');
        $pendaftar = \App\Models\DataPendaftar::findOrFail($pendaftarId);
        
        $request->validate([
            'nama_lengkap'      => 'required|string|max:255',
            'nik'               => 'required|string|max:20',
            'gender'            => 'required',
            'pilihan_jurusan_1' => 'required|string',
            'pilihan_jurusan_2' => 'required|string|different:pilihan_jurusan_1',
            'pas_foto'          => ($pendaftar->pas_foto ? 'nullable' : 'required') . '|file|image|max:2048',
            'scan_ktp'          => ($pendaftar->scan_ktp ? 'nullable' : 'required') . '|file|mimes:jpg,jpeg,png,pdf|max:2048',
            'ijazah_skl'        => ($pendaftar->ijazah_skl ? 'nullable' : 'required') . '|file|mimes:pdf,jpg,png|max:2048',
        ]);

        // Mencegah input 'pendaftar_id' dari form HTML ikut ter-save ke database
        $pendaftar->update($request->except(['_token', 'pas_foto', 'scan_ktp', 'ijazah_skl', 'pendaftar_id']));

        if ($request->hasFile('pas_foto')) {
            $file = $request->file('pas_foto');
            $namaFile = 'foto_' . time() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('uploads/foto'), $namaFile);
            $pendaftar->update(['pas_foto' => 'uploads/foto/' . $namaFile]);
        }

        if ($request->hasFile('scan_ktp')) {
            $file = $request->file('scan_ktp');
            $namaFile = 'ktp_' . time() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('uploads/ktp'), $namaFile);
            $pendaftar->update(['scan_ktp' => 'uploads/ktp/' . $namaFile]);
        }

        if ($request->hasFile('ijazah_skl')) {
            $file = $request->file('ijazah_skl');
            $namaFile = 'ijazah_' . time() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('uploads/ijazah'), $namaFile);
            $pendaftar->update(['ijazah_skl' => 'uploads/ijazah/' . $namaFile]);
        }

        return redirect()->route('konfirmasi-data', $pendaftar->id)->with('success', 'Data biodata berhasil tersimpan!');
    }

    // ==========================================
    // 4. KONFIRMASI (STEP 5)
    // ==========================================
public function tampilkanKonfirmasi($id)
    {
        // KEAMANAN TINGKAT 2: Cegah pendaftar melihat konfirmasi milik orang lain
        if ($id != session('pendaftar_id')) {
            abort(403, 'Akses Ditolak. Anda tidak bisa melihat data orang lain.');
        }

        $pendaftar = \App\Models\DataPendaftar::findOrFail($id);

        // MENCEGAH LOMPAT TAHAP: Jika nama lengkap/NIK belum diisi, kembalikan ke form biodata
        if (empty($pendaftar->nama_lengkap) || empty($pendaftar->nik)) {
            return redirect()->route('pendaftaran.biodata')
                             ->with('error', 'Silakan lengkapi formulir biodata terlebih dahulu sebelum melakukan konfirmasi.');
        }

        return view('user.konfirmasi-data', compact('pendaftar'));
    }

    public function prosesKonfirmasi($id)
    {
        // KEAMANAN TINGKAT 3: Cegah pendaftar menekan tombol konfirmasi untuk akun lain
        if ($id != session('pendaftar_id')) {
            abort(403, 'Akses Ditolak.');
        }

        $pendaftar = \App\Models\DataPendaftar::findOrFail($id);
        $pendaftar->status_pendaftaran = 'menunggu verifikasi'; 
        $pendaftar->save();

        return redirect()->route('pendaftaran.validasiakhir', ['id' => $id]);
    }

    // ==========================================
    // 5. VALIDASI AKHIR & HASIL (STEP 6 & 7)
    // ==========================================
public function tampilkanValidasiAkhir($id)
    {
        // KEAMANAN TINGKAT 4: Kunci akses ke halaman Validasi Akhir
        if ($id != session('pendaftar_id')) {
            abort(403, 'Akses Ditolak.');
        }

        $pendaftar = \App\Models\DataPendaftar::findOrFail($id);
        return view('user.validasi-akhir', compact('pendaftar'));
    }

    public function tampilkanSukses()
    {
        $pendaftar = DataPendaftar::find(session('pendaftar_id'));
        if (!$pendaftar) return redirect()->route('dashboard')->with('error', 'Data tidak ditemukan.');
        return view('user.sukses', compact('pendaftar'));
    }
    public function cetakLoA()
    {
        $pendaftar = DataPendaftar::find(session('pendaftar_id'));
        
        // Mencegah pendaftar yang belum lulus mencetak surat
        if (!$pendaftar || strpos($pendaftar->status_kelulusan, 'Lulus') === false || $pendaftar->status_kelulusan == 'Tidak Lulus') {
            return redirect()->route('dashboard.user')->with('error', 'Surat Keterangan Lulus belum tersedia.');
        }

        return view('user.cetak-loa', compact('pendaftar'));
    }
}