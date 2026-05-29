<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Portal - SPMB Adzkia</title>
    
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <script src="https://unpkg.com/feather-icons"></script>

    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: { sans: ['Manrope', 'sans-serif'] },
                    colors: {
                        'brand-bg': '#F8FAFC',
                        'brand-dark': '#0F172A',
                        'brand-gray': '#64748B',
                        'brand-blue': '#2563EB',
                        'brand-blue-light': '#EFF6FF',
                    }
                }
            }
        }
    </script>
    <style>
        [x-cloak] { display: none !important; }
        .custom-scrollbar::-webkit-scrollbar { width: 4px; }
        .custom-scrollbar::-webkit-scrollbar-thumb { background: #E2E8F0; border-radius: 10px; }
    </style>
</head>
<body class="bg-brand-bg antialiased text-brand-dark flex min-h-screen">

    <aside class="w-[280px] bg-white border-r border-gray-100 flex flex-col fixed h-screen z-20">
        
        {{-- PENGHITUNG OTOMATIS DATA YANG PERLU DIVALIDASI --}}
        @php
            $pendingPembayaran = \App\Models\DataPendaftar::where('status_pembayaran', 'Menunggu Validasi')->count();
            $pendingBerkas = \App\Models\DataPendaftar::where('status_pendaftaran', 'menunggu verifikasi')->count();
            $totalPending = $pendingPembayaran + $pendingBerkas;
        @endphp

        <div class="h-24 flex items-center px-8 gap-3">
        <img src="{{ asset('images/logo-adzkia.png') }}" alt="Logo Universitas Adzkia" class="h-11 w-auto transition-transform group-hover:scale-105 duration-300">

            <div class="flex flex-col">
                <span class="font-extrabold text-[18px] tracking-tight leading-tight text-brand-dark">SPMB Portal</span>
                <span class="text-[12px] font-semibold text-brand-gray">Adzkia Admin</span>
            </div>
        </div>

<nav class="flex-1 px-4 py-4 space-y-1 overflow-y-auto custom-scrollbar">
            
            @php
                $user = auth()->user();
                $isSuperAdmin = $user->role === 'super_admin';
                $divisi = $user->divisi;
            @endphp

            {{-- MENU UMUM (Semua Admin Bisa Lihat Dashboard & Data Pendaftar) --}}
            <a href="/admin" ...> <!-- Kode menu Dashboard --> </a>
            <a href="/admin/pendaftar" ...> <!-- Kode menu Data Pendaftar --> </a>

            {{-- MENU VALIDASI (Hanya Super Admin, Keuangan, & Verifikator) --}}
            @if($isSuperAdmin || in_array($divisi, ['Keuangan', 'Verifikator Berkas']))
            <div x-data="{ open: {{ request()->is('admin/validasi*') ? 'true' : 'false' }} }">
                <!-- Tombol Dropdown Validasi -->
                
                <div x-show="open" x-cloak class="mt-1 ml-9 space-y-1">
                    @if($isSuperAdmin || $divisi === 'Keuangan')
                        <a href="/admin/validasi-pembayaran" ...>Pembayaran</a>
                    @endif
                    
                    @if($isSuperAdmin || $divisi === 'Verifikator Berkas')
                        <a href="/admin/validasi-daftar-ulang" ...>Daftar Ulang</a>
                    @endif
                </div>
            </div>
            @endif

            {{-- MENU HUMAS (Hanya Super Admin & Humas) --}}
            @if($isSuperAdmin || $divisi === 'Humas & Informasi')
                <a href="/admin/pengumuman" ...>Pengumuman</a>
                <a href="/admin/berita" ...>Berita</a>
                <a href="/admin/faq" ...>FAQ</a>
            @endif

            {{-- MENU SUPER ADMIN EKSKLUSIF --}}
            @if($isSuperAdmin)
                <a href="/admin/prodi" ...>Program Studi</a>
                <a href="/admin/tugas" ...>Manajemen Divisi</a>
                <a href="/admin/settings" ...>Settings</a>
            @endif

        </nav>

        <div class="p-6 m-4 bg-brand-bg rounded-2xl border border-gray-100">
            <div class="flex items-center gap-2 mb-2">
                <div class="w-2 h-2 bg-green-500 rounded-full animate-pulse"></div>
                <span class="text-[10px] font-extrabold uppercase tracking-widest text-brand-gray">System: Active</span>
            </div>
            <p class="text-[11px] text-brand-gray font-medium leading-relaxed">No pending maintenance.</p>
        </div>
    </aside>

    <div class="ml-[280px] flex-1 flex flex-col min-h-screen">
        
        <header class="h-24 px-10 flex items-center justify-between sticky top-0 bg-brand-bg/80 backdrop-blur-md z-10">
            <div class="relative w-96">
                <i data-feather="search" class="absolute left-4 top-1/2 -translate-y-1/2 w-4 h-4 text-brand-gray"></i>
                <input type="text" placeholder="Search pendaftar..." class="w-full pl-11 pr-4 py-2.5 bg-white border border-gray-100 rounded-full text-[13px] outline-none shadow-sm">
            </div>

            <div class="flex items-center gap-6">
                <div class="flex items-center gap-4 text-brand-gray relative">
                    <span class="flex items-center justify-center cursor-pointer hover:text-brand-blue transition-colors">
                        <i data-feather="bell" class="w-5 h-5"></i>
                        {{-- Notifikasi Lonceng --}}
                        @if($totalPending > 0)
                            <div class="absolute -top-1 -right-1 w-2.5 h-2.5 bg-red-500 rounded-full border-2 border-brand-bg"></div>
                        @endif
                    </span>
                    <span class="flex items-center justify-center cursor-pointer hover:text-brand-blue transition-colors"><i data-feather="help-circle" class="w-5 h-5"></i></span>
                </div>
                <div class="h-8 w-px bg-gray-200"></div>
                <div class="flex items-center gap-3">
                    <div class="text-right">
                        <p class="text-[13px] font-bold text-brand-dark">Admin - Budi Santoso</p>
                        <p class="text-[11px] font-semibold text-brand-gray">Head of Admissions</p>
                    </div>
                    <img src="https://ui-avatars.com/api/?name=Budi+Santoso&background=0F172A&color=fff" class="w-10 h-10 rounded-full border border-gray-200">
                </div>
            </div>
        </header>

        <main class="flex-1 px-10 pb-10">
            @yield('admin-content')
        </main>
        
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            feather.replace();
        });
    </script>
</body>
</html>