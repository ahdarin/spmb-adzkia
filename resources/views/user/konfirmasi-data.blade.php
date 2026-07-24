<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Konfirmasi Data - Dasbor SPMB Adzkia</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/feather-icons/dist/feather.min.js"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: { sans: ['Manrope', 'sans-serif'] },
                    colors: {
                        'adzkia-red': '#d9241c',
                        'adzkia-blue': '#2c7ebd',
                        'adzkia-dark': '#1e293b',
                        'adzkia-badge-bg': '#eff6ff',
                    }
                }
            }
        }
    </script>
    <style> [x-cloak] { display: none !important; } </style>
</head>
<body class="bg-gray-50 antialiased text-adzkia-dark min-h-screen flex flex-col" x-data="konfirmasiApp()">

    {{-- NAVBAR DASHBOARD USER --}}
    <nav class="bg-white border-b border-gray-200 py-4 px-6 md:px-10 flex justify-between items-center sticky top-0 z-30">
        <a href="{{ route('dashboard.user') }}" class="flex items-center gap-3 group">
            <img src="{{ asset('images/logo-adzkia.png') }}" alt="Logo" class="h-10 w-auto group-hover:scale-105 transition-transform">
            <div class="hidden md:flex flex-col">
                <span class="text-[16px] font-black text-adzkia-blue leading-none">Dasbor</span>
                <span class="text-[12px] font-bold text-adzkia-red">Calon Mahasiswa</span>
            </div>
        </a>
        
        <div class="flex items-center gap-4 md:gap-6">
            <a href="{{ route('dashboard.user') }}" class="flex items-center gap-2 text-[12px] md:text-[13px] font-bold text-gray-500 hover:text-adzkia-blue transition-colors bg-gray-50 px-4 py-2 rounded-lg">
                <i data-feather="arrow-left" class="w-4 h-4"></i> Kembali ke Dasbor
            </a>
            <div class="hidden md:block w-px h-6 bg-gray-200"></div>
            <div class="flex items-center gap-3">
                <div class="text-right hidden md:block">
                    <p class="text-[13px] font-extrabold text-adzkia-dark">{{ session('nama_pendaftar') }}</p>
                    <p class="text-[11px] font-bold text-gray-400">ID: {{ $pendaftar->no_pendaftaran ?? 'ID Kosong' }}</p>
                </div>
                <img src="https://ui-avatars.com/api/?name={{ urlencode(session('nama_pendaftar')) }}&background=1e293b&color=fff" class="w-10 h-10 rounded-full border-2 border-gray-100">
            </div>
        </div>
    </nav>

    {{-- STEP PROGRESS TRACKER --}}
    <x-step-tracker :current-step="3" />

    <main class="flex-1 max-w-6xl mx-auto w-full px-6 py-12">

        @php
            // ── Resolusi berkas dokumen (dinamis sesuai jalur) ──────────────
            // Upload dokumen disimpan dinamis di kolom berkas_dokumen (json),
            // BUKAN di kolom pas_foto/scan_ktp/ijazah_skl (kolom lama, sudah
            // tidak diisi oleh proses simpanPendaftaran()).
            $berkas = is_array($pendaftar->berkas_dokumen)
                ? $pendaftar->berkas_dokumen
                : (json_decode($pendaftar->berkas_dokumen ?? '{}', true) ?? []);

            // Cari path foto profil: cek kolom pas_foto dulu, fallback ke
            // berkas_dokumen dengan key yang mengandung kata "foto" / "pas".
            $fotoProfilPath = $pendaftar->pas_foto ?? null;
            if (empty($fotoProfilPath)) {
                foreach ($berkas as $namaDoc => $pathDoc) {
                    if (!empty($pathDoc) && (stripos($namaDoc, 'foto') !== false || stripos($namaDoc, 'pas') !== false)) {
                        $fotoProfilPath = $pathDoc;
                        break;
                    }
                }
            }

            $isPdf = fn(?string $path) => $path && str_ends_with(strtolower($path), '.pdf');
        @endphp

        <div class="mb-10 text-center md:text-left">
            <span class="inline-block px-3 py-1 bg-adzkia-badge-bg text-adzkia-blue rounded-lg text-[11px] font-black uppercase tracking-widest mb-3">STEP 05 / 07</span>
            <h1 class="text-3xl md:text-4xl font-black text-adzkia-dark tracking-tight mb-2">Konfirmasi Data Pendaftaran</h1>
            <p class="text-[15px] font-medium text-gray-500 leading-relaxed">
                Periksa kembali data Anda sebelum melanjutkan ke tahap validasi berkas oleh Admin.
            </p>
        </div>

        {{-- PERINGATAN — dipindah ke atas, sebelum kolom profil --}}
        <div class="flex gap-4 border-l-4 border-adzkia-red bg-red-50 rounded-r-xl pl-5 pr-4 py-4 shadow-sm mb-8">
            <i data-feather="info" class="w-5 h-5 text-adzkia-red shrink-0 mt-0.5"></i>
            <div>
                <h4 class="text-[14px] font-extrabold text-adzkia-red mb-1">Peringatan Penting</h4>
                <p class="text-[12px] font-medium text-red-900/80 leading-relaxed">Data tidak dapat diubah setelah tahap ini. Pastikan semua informasi sudah benar sebelum menekan tombol konfirmasi.</p>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
            
            {{-- KOLOM KIRI (Profil & Info) --}}
            <div class="lg:col-span-4 space-y-6">
                <div class="bg-white rounded-[2rem] p-8 border border-gray-100 shadow-sm flex flex-col items-center text-center">

                    <img src="{{ !empty($fotoProfilPath) ? asset($fotoProfilPath) : 'https://ui-avatars.com/api/?name=' . urlencode($pendaftar->nama_lengkap) . '&background=F1F5F9&color=1e293b&size=128' }}"
                         alt="Foto Profil" class="w-24 h-24 rounded-2xl mb-4 shadow-sm border border-gray-100 object-cover">

                    <h2 class="text-2xl font-black text-adzkia-dark mb-1">{{ $pendaftar->nama_lengkap }}</h2>
                    <p class="text-[12px] font-bold text-gray-400 uppercase tracking-widest mb-6">No. Registrasi: {{ $pendaftar->no_pendaftaran }}</p>
    
                    <div class="w-full bg-adzkia-blue rounded-2xl p-6 relative overflow-hidden shadow-lg shadow-adzkia-blue/20">
                        <div class="absolute top-0 right-0 p-4 opacity-10">
                            <i data-feather="book-open" class="w-20 h-20 text-white"></i>
                        </div>
                        
                        <p class="text-[10px] font-black text-blue-100 uppercase tracking-widest mb-1 relative z-10 text-left">Pilihan Jurusan 1</p>
                        <h3 class="text-lg font-extrabold text-white relative z-10 text-left mb-4 leading-tight">{{ $pendaftar->pilihan_jurusan_1 ?? '-' }}</h3>
                        
                        <p class="text-[10px] font-black text-blue-100 uppercase tracking-widest mb-1 relative z-10 text-left">Pilihan Jurusan 2</p>
                        <h3 class="text-lg font-extrabold text-white relative z-10 text-left leading-tight">{{ $pendaftar->pilihan_jurusan_2 ?? '-' }}</h3>
                    </div>
                </div>
            </div>

            {{-- KOLOM KANAN (Detail Data & Dokumen) --}}
            <div class="lg:col-span-8 space-y-6">
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    {{-- DATA DIRI --}}
                    <div class="bg-white rounded-[2rem] p-8 border border-gray-100 shadow-sm relative group">
                        <a href="{{ route('pendaftaran.biodata') }}" class="absolute top-8 right-8 text-[12px] font-extrabold text-gray-400 underline underline-offset-2 hover:text-adzkia-red transition-colors">Edit</a>
                        <div class="flex items-center gap-2 mb-6">
                            <i data-feather="user" class="w-4 h-4 text-adzkia-blue"></i>
                            <h3 class="text-[15px] font-extrabold text-adzkia-dark">Data Diri</h3>
                        </div>
                        <div class="space-y-4">
                            <div>
                                <p class="text-[9px] font-black text-gray-400 uppercase tracking-widest mb-1">Nama Lengkap</p>
                                <p class="text-[14px] font-bold text-adzkia-dark">{{ $pendaftar->nama_lengkap }}</p>
                            </div>
                            <div>
                                <p class="text-[9px] font-black text-gray-400 uppercase tracking-widest mb-1">NIK</p>
                                <p class="text-[14px] font-bold text-adzkia-dark">{{ $pendaftar->nik }}</p>
                            </div>
                            <div class="flex gap-8">
                                <div>
                                    <p class="text-[9px] font-black text-gray-400 uppercase tracking-widest mb-1">Tanggal Lahir</p>
                                    <p class="text-[14px] font-bold text-adzkia-dark">{{ $pendaftar->tanggal_lahir ? \Carbon\Carbon::parse($pendaftar->tanggal_lahir)->translatedFormat('d F Y') : '-' }}</p>
                                </div>
                                <div>
                                    <p class="text-[9px] font-black text-gray-400 uppercase tracking-widest mb-1">Gender</p>
                                    <p class="text-[14px] font-bold text-adzkia-dark">{{ $pendaftar->gender ?? '-' }}</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- KONTAK --}}
                    <div class="bg-white rounded-[2rem] p-8 border border-gray-100 shadow-sm relative group">
                        <a href="{{ route('pendaftaran.biodata') }}" class="absolute top-8 right-8 text-[12px] font-extrabold text-gray-400 underline underline-offset-2 hover:text-adzkia-red transition-colors">Edit</a>
                        <div class="flex items-center gap-2 mb-6">
                            <i data-feather="map-pin" class="w-4 h-4 text-adzkia-blue"></i>
                            <h3 class="text-[15px] font-extrabold text-adzkia-dark">Kontak & Wilayah</h3>
                        </div>
                        <div class="space-y-4">
                            <div>
                                <p class="text-[9px] font-black text-gray-400 uppercase tracking-widest mb-1">Email</p>
                                <p class="text-[14px] font-bold text-adzkia-dark">{{ $pendaftar->email }}</p>
                            </div>
                            <div>
                                <p class="text-[9px] font-black text-gray-400 uppercase tracking-widest mb-1">No WhatsApp</p>
                                <p class="text-[14px] font-bold text-adzkia-dark">{{ $pendaftar->no_whatsapp ?? '-' }}</p>
                            </div>
                            <div>
                                <p class="text-[9px] font-black text-gray-400 uppercase tracking-widest mb-1">Alamat</p>
                                <p class="text-[14px] font-bold text-adzkia-dark leading-snug">
                                    {{ $pendaftar->alamat_rumah ?? '-' }}{{ $pendaftar->kota_kabupaten ? ', ' . $pendaftar->kota_kabupaten : '' }}{{ $pendaftar->provinsi ? ', ' . $pendaftar->provinsi : '' }}
                                </p>
                            </div>
                        </div>
                    </div>

                    {{-- PENDIDIKAN --}}
                    <div class="bg-white rounded-[2rem] p-8 border border-gray-100 shadow-sm relative group md:col-span-2 lg:col-span-2">
                        <a href="{{ route('pendaftaran.biodata') }}" class="absolute top-8 right-8 text-[12px] font-extrabold text-gray-400 underline underline-offset-2 hover:text-adzkia-red transition-colors">Edit</a>
                        <div class="flex items-center gap-2 mb-6">
                            <i data-feather="book-open" class="w-4 h-4 text-adzkia-blue"></i>
                            <h3 class="text-[15px] font-extrabold text-adzkia-dark">Pendidikan Asal</h3>
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <p class="text-[9px] font-black text-gray-400 uppercase tracking-widest mb-1">Sekolah Asal</p>
                                <p class="text-[14px] font-bold text-adzkia-dark">
                                    {{ $pendaftar->sekolah_asal ?? '-' }}
                                    @if(!empty($pendaftar->npsn_sekolah))
                                        <span class="block text-[11px] font-mono text-gray-400 mt-0.5">NPSN: {{ $pendaftar->npsn_sekolah }}</span>
                                    @endif
                                </p>
                            </div>
                            <div>
                                <p class="text-[9px] font-black text-gray-400 uppercase tracking-widest mb-1">Tahun Lulus</p>
                                <p class="text-[14px] font-bold text-adzkia-dark">{{ $pendaftar->tahun_lulus ?? '-' }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- BLOK REVIEW DOKUMEN — dinamis sesuai isi berkas_dokumen --}}
                <div class="bg-white rounded-[2rem] p-8 border border-gray-100 shadow-sm relative">
                    <h3 class="text-[15px] font-extrabold text-adzkia-dark mb-6 flex items-center gap-2">
                        <i data-feather="paperclip" class="w-4 h-4 text-adzkia-blue"></i> Dokumen Terlampir
                    </h3>

                    @if(count($berkas) > 0)
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        @foreach($berkas as $namaDokumen => $pathDokumen)
                            @continue(empty($pathDokumen))
                            <div class="border border-gray-200 rounded-xl p-3 flex items-center gap-4 hover:border-adzkia-blue transition-colors group">
                                <div class="w-14 h-14 bg-gray-50 rounded-lg overflow-hidden shrink-0 flex items-center justify-center border border-gray-100">
                                    @if($isPdf($pathDokumen))
                                        {{-- File PDF: tampilkan ikon saja, tidak bisa dijadikan thumbnail gambar --}}
                                        <i data-feather="file-text" class="w-6 h-6 text-gray-400"></i>
                                    @else
                                        {{-- File gambar: tampilkan thumbnail kecil sesuai ukuran box --}}
                                        <img src="{{ asset($pathDokumen) }}" alt="{{ $namaDokumen }}" class="w-full h-full object-cover">
                                    @endif
                                </div>
                                <div class="flex flex-col min-w-0">
                                    <span class="text-[11px] font-black text-adzkia-dark uppercase tracking-widest truncate">{{ $namaDokumen }}</span>
                                    <a href="{{ asset($pathDokumen) }}" target="_blank" class="text-[11px] font-bold text-adzkia-blue hover:text-adzkia-red transition-colors mt-0.5 flex items-center gap-1">
                                        Lihat File <i data-feather="external-link" class="w-3 h-3"></i>
                                    </a>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    @else
                    <div class="border-2 border-dashed border-gray-100 rounded-2xl p-6 text-center">
                        <i data-feather="inbox" class="w-6 h-6 text-gray-300 mb-2 mx-auto"></i>
                        <p class="text-[12px] font-bold text-gray-400">Belum ada dokumen yang diunggah.</p>
                    </div>
                    @endif
                </div>

                {{-- PERSETUJUAN --}}
                <div class="bg-white rounded-[2rem] p-8 border border-gray-100 shadow-sm space-y-5">
                    <label class="flex items-start gap-4 cursor-pointer group">
                        <div class="w-6 h-6 rounded flex items-center justify-center transition-colors shrink-0 mt-0.5 border-2"
                             :class="agreements.dataCorrect ? 'bg-adzkia-blue border-adzkia-blue' : 'bg-white border-gray-300 group-hover:border-adzkia-blue'">
                            <i data-feather="check" class="w-4 h-4 text-white" x-show="agreements.dataCorrect" x-cloak></i>
                            <input type="checkbox" x-model="agreements.dataCorrect" class="sr-only">
                        </div>
                        <span class="text-[14px] font-medium text-gray-600 leading-relaxed select-none group-hover:text-adzkia-dark transition-colors">
                            Saya menyatakan bahwa seluruh data yang saya isi di atas adalah benar dan sesuai dengan dokumen aslinya.
                        </span>
                    </label>

                    <label class="flex items-start gap-4 cursor-pointer group">
                        <div class="w-6 h-6 rounded flex items-center justify-center transition-colors shrink-0 mt-0.5 border-2"
                             :class="agreements.termsRead ? 'bg-adzkia-blue border-adzkia-blue' : 'bg-white border-gray-300 group-hover:border-adzkia-blue'">
                            <i data-feather="check" class="w-4 h-4 text-white" x-show="agreements.termsRead" x-cloak></i>
                            <input type="checkbox" x-model="agreements.termsRead" class="sr-only">
                        </div>
                        <span class="text-[14px] font-medium text-gray-600 leading-relaxed select-none group-hover:text-adzkia-dark transition-colors">
                            Saya telah membaca dan menyetujui seluruh <span class="font-extrabold text-adzkia-dark">syarat & ketentuan</span> seleksi penerimaan mahasiswa baru.
                        </span>
                    </label>
                </div>

                {{-- TOMBOL AKSI --}}
                <div class="flex flex-col items-center gap-4 pt-2">
                    <form action="{{ route('proses.konfirmasi', $pendaftar->id) }}" method="POST" class="w-full">
                        @csrf
                        <button type="submit"
                                :disabled="!canProceed"
                                class="w-full py-4 rounded-2xl font-black text-[15px] transition-all"
                                :class="canProceed ? 'bg-adzkia-blue text-white hover:bg-blue-700 shadow-xl shadow-blue-600/20 active:scale-[0.98]' : 'bg-gray-200 text-gray-400 cursor-not-allowed'">
                            Konfirmasi & Kirim Data
                        </button>
                    </form>
                    
                    <a href="{{ route('pendaftaran.biodata') }}" class="text-[13px] font-extrabold text-gray-500 hover:text-adzkia-blue transition-colors py-2 flex items-center gap-2">
                        <i data-feather="edit-2" class="w-3.5 h-3.5"></i> Kembali Edit Formulir
                    </a>
                </div>

            </div>
        </div>
    </main>

    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('konfirmasiApp', () => ({
                
                agreements: {
                    dataCorrect: false,
                    termsRead: false
                },

                get canProceed() {
                    return this.agreements.dataCorrect && this.agreements.termsRead;
                }
            }));
        });

        document.addEventListener('DOMContentLoaded', () => {
            feather.replace();
        });
    </script>
</body>
</html>