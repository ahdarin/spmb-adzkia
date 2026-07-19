<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Validasi Berkas - Dasbor SPMB Adzkia</title>
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
<body class="bg-gray-50 antialiased text-adzkia-dark min-h-screen flex flex-col" x-data="validasiApp()">

    {{-- NAVBAR DASHBOARD USER (Konsisten) --}}
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

    {{-- STEP PROGRESS TRACKER (Konsisten) --}}
    <x-step-tracker :current-step="3" />

    <main class="flex-1 max-w-3xl mx-auto w-full px-6 py-10">
        
        {{-- NOTIFIKASI SUKSES DARI CONTROLLER --}}
        @if(session('success'))
            <div class="mb-6 p-4 bg-green-50 border-l-4 border-green-500 rounded-r-xl flex items-start gap-3">
                <i data-feather="check-circle" class="w-5 h-5 text-green-600 shrink-0"></i>
                <p class="text-[13px] font-bold text-green-800">{{ session('success') }}</p>
            </div>
        @endif

        <div class="bg-white p-8 md:p-12 rounded-[2.5rem] shadow-sm border border-gray-100">
            
            <div class="flex flex-col items-center text-center mb-10 border-b border-gray-50 pb-8">
                <div class="w-20 h-20 bg-adzkia-badge-bg text-adzkia-blue rounded-full flex items-center justify-center mb-6 animate-pulse">
                    <i data-feather="hourglass" class="w-10 h-10"></i>
                </div>
                <h1 class="text-3xl font-black text-adzkia-dark tracking-tight mb-3">Verifikasi Berkas Admin</h1>
                <span class="px-4 py-1.5 bg-blue-50 text-adzkia-blue rounded-full text-[11px] font-black uppercase tracking-widest border border-blue-100">
                    Proses Pemeriksaan
                </span>
            </div>

            <div class="mb-10 px-4 md:px-10">
                <div class="relative space-y-6 before:absolute before:inset-0 before:ml-2.5 before:-translate-x-px md:before:mx-auto md:before:translate-x-0 before:h-full before:w-0.5 before:bg-gradient-to-b before:from-adzkia-blue before:via-gray-200 before:to-transparent">
                    
                    {{-- TIMELINE 1: DATA DIKIRIM --}}
                    <div class="relative flex items-center justify-between md:justify-normal md:odd:flex-row-reverse group is-active">
                        <div class="flex items-center justify-center w-6 h-6 rounded-full border-2 border-white bg-green-500 text-white shadow shrink-0 md:order-1 md:group-odd:-translate-x-1/2 md:group-even:translate-x-1/2 z-10">
                            <i data-feather="check" class="w-3 h-3"></i>
                        </div>
                        <div class="w-[calc(100%-3rem)] md:w-[calc(50%-1.5rem)] md:group-odd:text-right font-extrabold text-[14px] text-green-600">Formulir Dikirim</div>
                    </div>
                    
                    {{-- TIMELINE 2: MENUNGGU VERIFIKASI (STATUS SAAT INI) --}}
                    <div class="relative flex items-center justify-between md:justify-normal md:odd:flex-row-reverse group is-active">
                        <div class="flex items-center justify-center w-6 h-6 rounded-full border-2 border-white bg-adzkia-blue text-white shadow shrink-0 md:order-1 md:group-odd:-translate-x-1/2 md:group-even:translate-x-1/2 z-10">
                            <i data-feather="loader" class="w-3 h-3 animate-spin"></i>
                        </div>
                        <div class="w-[calc(100%-3rem)] md:w-[calc(50%-1.5rem)] md:group-odd:text-right font-extrabold text-[14px] text-adzkia-blue">Menunggu Pengecekan Admin</div>
                    </div>

                    {{-- TIMELINE 3: SELESAI --}}
                    <div class="relative flex items-center justify-between md:justify-normal md:odd:flex-row-reverse group">
                        <div class="flex items-center justify-center w-6 h-6 rounded-full border-2 border-gray-200 bg-white text-gray-400 shadow shrink-0 md:order-1 md:group-odd:-translate-x-1/2 md:group-even:translate-x-1/2 z-10">
                            <i data-feather="check" class="w-3 h-3"></i>
                        </div>
                        <div class="w-[calc(100%-3rem)] md:w-[calc(50%-1.5rem)] md:group-odd:text-right font-extrabold text-[14px] text-gray-400">
                            Hasil Kelulusan
                        </div>
                    </div>

                </div>
            </div>

            <div class="bg-gray-50 rounded-3xl p-6 md:p-8 mb-6 border border-gray-100 grid grid-cols-2 gap-y-6 gap-x-4">
                <div>
                    <p class="text-[9px] font-black text-gray-400 uppercase tracking-widest mb-1.5">Nama Pendaftar</p>
                    <h4 class="text-[14px] font-extrabold text-adzkia-dark">{{ $pendaftar->nama_lengkap }}</h4>
                </div>
                <div>
                    <p class="text-[9px] font-black text-gray-400 uppercase tracking-widest mb-1.5">Program Studi 1</p>
                    <h4 class="text-[14px] font-extrabold text-adzkia-dark">{{ $pendaftar->pilihan_jurusan_1 }}</h4>
                    <p class="text-[9px] font-black text-gray-400 uppercase tracking-widest mb-1.5">Program Studi 2</p>
                    <h4 class="text-[14px] font-extrabold text-adzkia-dark">{{ $pendaftar->pilihan_jurusan_2 }}</h4>
                </div>
                <div>
                    <p class="text-[9px] font-black text-gray-400 uppercase tracking-widest mb-1.5">Tanggal Kirim</p>
                    <h4 class="text-[14px] font-extrabold text-adzkia-dark">{{ \Carbon\Carbon::parse($pendaftar->updated_at)->translatedFormat('d F Y') }}</h4>
                </div>
                <div>
                    <p class="text-[9px] font-black text-gray-400 uppercase tracking-widest mb-1.5">Estimasi Verifikasi</p>
                    <h4 class="text-[14px] font-extrabold text-adzkia-dark">1 - 2 Hari Kerja</h4>
                </div>
            </div>

            <div class="bg-blue-50/50 border border-blue-100 rounded-2xl p-5 flex gap-4 items-start mb-8">
                <i data-feather="message-square" class="w-5 h-5 text-adzkia-blue shrink-0 mt-0.5"></i>
                <p class="text-[13px] font-medium text-gray-600 leading-relaxed">
                    Data Anda sedang dalam antrean pemeriksaan oleh tim akademik. Mohon periksa dasbor secara berkala untuk melihat hasil pengumuman.
                </p>
            </div>

            <div class="flex flex-col items-center gap-4">
                <a href="{{ route('dashboard.user') }}" class="w-full py-4 bg-adzkia-blue text-white rounded-2xl font-black text-[15px] hover:bg-blue-700 shadow-lg shadow-blue-600/20 transition-all active:scale-[0.98] text-center">
                    Kembali ke Dashboard Utama
                </a>
                <button onclick="window.location.reload()" class="text-[13px] font-extrabold text-gray-500 hover:text-adzkia-dark transition-colors py-2">
                    Muat Ulang Halaman
                </button>
            </div>

        </div>
    </main>

    <footer class="w-full bg-adzkia-bg py-8 flex flex-col md:flex-row justify-center md:justify-between items-center gap-4 px-10 border-t border-gray-100">
        <p class="text-[11px] font-bold text-gray-400">© 2026 Universitas Adzkia. All Rights Reserved.</p>
        <div class="flex gap-8 text-[11px] font-bold text-gray-500">
            <a href="#" class="hover:text-adzkia-blue transition-colors">Contact Support</a>
        </div>
    </footer>

    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('validasiApp', () => ({
            }));
        });
        document.addEventListener('DOMContentLoaded', () => { feather.replace(); });
    </script>
</body>
</html>