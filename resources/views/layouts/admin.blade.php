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

    @php
        // Ambil data user yang sedang login
        $user = auth()->user();
        $isSuperAdmin = $user && $user->role === 'super_admin';
        $divisi = $user ? $user->divisi : '';

        // Hitung notifikasi validasi
        $pendingPembayaran = \App\Models\DataPendaftar::where('status_pembayaran', 'Menunggu Validasi')->count();
        $pendingBerkas = \App\Models\DataPendaftar::where('status_pendaftaran', 'menunggu verifikasi')->count();
        $totalPending = $pendingPembayaran + $pendingBerkas;
    @endphp

    <aside class="w-[280px] bg-white border-r border-gray-100 flex flex-col fixed h-screen z-20">
        
        <div class="h-24 flex items-center px-8 gap-3">
            <img src="{{ asset('images/logo-adzkia.png') }}" alt="Logo Universitas Adzkia" class="h-11 w-auto transition-transform group-hover:scale-105 duration-300">
            <div class="flex flex-col">
                <span class="font-extrabold text-[18px] tracking-tight leading-tight text-brand-dark">SPMB Portal</span>
                <span class="text-[12px] font-semibold text-brand-gray">Adzkia Admin</span>
            </div>
        </div>

        <nav class="flex-1 px-4 py-4 space-y-1 overflow-y-auto custom-scrollbar">
            
            {{-- MENU UMUM (Dilihat Semua Admin) --}}
            <a href="/admin" @class(['flex items-center gap-3 px-4 py-3 font-bold rounded-xl transition-all relative', 'bg-brand-blue-light text-brand-blue' => request()->is('admin'), 'text-brand-gray hover:bg-gray-50' => !request()->is('admin')])>
                <span class="flex items-center justify-center"><i data-feather="grid" class="w-5 h-5"></i></span>
                <span class="text-[14px]">Dashboard</span>
            </a>

            <a href="/admin/pendaftar" @class(['flex items-center gap-3 px-4 py-3 font-bold rounded-xl transition-all relative', 'bg-brand-blue-light text-brand-blue' => request()->is('admin/pendaftar*'), 'text-brand-gray hover:bg-gray-50' => !request()->is('admin/pendaftar*')])>
                <span class="flex items-center justify-center"><i data-feather="users" class="w-5 h-5"></i></span>
                <span class="text-[14px]">Data Pendaftar</span>
            </a>

            {{-- MENU VALIDASI (Dilihat Super Admin, Keuangan, & Verifikator Berkas) --}}
            @if($isSuperAdmin || in_array($divisi, ['Keuangan', 'Verifikator Berkas']))
            <div x-data="{ open: {{ request()->is('admin/validasi*') ? 'true' : 'false' }} }">
                <button @click="open = !open" 
                   :class="open || {{ request()->is('admin/validasi*') ? 'true' : 'false' }} ? 'text-brand-blue bg-brand-blue-light' : 'text-brand-gray hover:bg-gray-50'"
                   class="w-full flex items-center justify-between px-4 py-3 font-bold rounded-xl transition-all outline-none">
                    <div class="flex items-center gap-3">
                        <span class="flex items-center justify-center"><i data-feather="check-circle" class="w-5 h-5"></i></span>
                        <div class="flex items-center gap-2">
                            <span class="text-[14px]">Validasi</span>
                            @if($totalPending > 0)
                                <span class="bg-red-500 text-white text-[9px] font-black px-1.5 py-0.5 rounded-full shadow-sm" x-show="!open">{{ $totalPending }}</span>
                            @endif
                        </div>
                    </div>
                    <span class="flex items-center justify-center transition-transform duration-300" :class="open ? 'rotate-180' : ''">
                        <i data-feather="chevron-down" class="w-4 h-4"></i>
                    </span>
                </button>
                <div x-show="open" x-cloak class="mt-1 ml-9 space-y-1">
                    
                    @if($isSuperAdmin || $divisi === 'Keuangan')
                    <a href="/admin/validasi-pembayaran" class="flex items-center justify-between pr-4 py-2 text-[13px] font-bold {{ request()->is('admin/validasi-pembayaran') ? 'text-brand-blue' : 'text-brand-gray hover:text-brand-dark' }}">
                        <span>Pembayaran</span>
                        @if($pendingPembayaran > 0)
                            <span class="bg-red-500 text-white text-[9px] font-black px-1.5 py-0.5 rounded-full shadow-sm">{{ $pendingPembayaran }}</span>
                        @endif
                    </a>
                    @endif

                    @if($isSuperAdmin || $divisi === 'Verifikator Berkas')
                    <a href="/admin/validasi-daftar-ulang" class="flex items-center justify-between pr-4 py-2 text-[13px] font-bold {{ request()->is('admin/validasi-daftar-ulang') ? 'text-brand-blue' : 'text-brand-gray hover:text-brand-dark' }}">
                        <span>Daftar Ulang</span>
                        @if($pendingBerkas > 0)
                            <span class="bg-red-500 text-white text-[9px] font-black px-1.5 py-0.5 rounded-full shadow-sm">{{ $pendingBerkas }}</span>
                        @endif
                    </a>
                    @endif
                </div>
            </div>
            @endif

{{-- MENU PENGUMUMAN KELULUSAN --}}
            @if($isSuperAdmin || $divisi === 'Verifikator Berkas')
            <a href="/admin/pengumuman" @class(['flex items-center gap-3 px-4 py-3 font-bold rounded-xl transition-all relative', 'bg-brand-blue-light text-brand-blue' => request()->is('admin/pengumuman*'), 'text-brand-gray hover:bg-gray-50' => !request()->is('admin/pengumuman*')])>
                <span class="flex items-center justify-center"><i data-feather="award" class="w-5 h-5"></i></span>
                <span class="text-[14px]">Pengumuman</span>
            </a>
            @endif

            {{-- MENU INFORMASI LAINNYA --}}
            @if($isSuperAdmin || $divisi === 'Humas & Informasi')
            <a href="/admin/berita" @class(['flex items-center gap-3 px-4 py-3 font-bold rounded-xl transition-all relative', 'bg-brand-blue-light text-brand-blue' => request()->is('admin/berita*'), 'text-brand-gray hover:bg-gray-50' => !request()->is('admin/berita*')])>
                <span class="flex items-center justify-center"><i data-feather="file-text" class="w-5 h-5"></i></span>
                <span class="text-[14px]">Berita</span>
            </a>
            <a href="/admin/faq" @class(['flex items-center gap-3 px-4 py-3 font-bold rounded-xl transition-all relative', 'bg-brand-blue-light text-brand-blue' => request()->is('admin/faq*'), 'text-brand-gray hover:bg-gray-50' => !request()->is('admin/faq*')])>
                <span class="flex items-center justify-center"><i data-feather="help-circle" class="w-5 h-5"></i></span>
                <span class="text-[14px]">FAQ</span>
            </a>
            @endif

            {{-- EKSKLUSIF SUPER ADMIN --}}
            @if($isSuperAdmin)
            <a href="/admin/prodi" @class(['flex items-center gap-3 px-4 py-3 font-bold rounded-xl transition-all relative', 'bg-brand-blue-light text-brand-blue' => request()->is('admin/prodi*'), 'text-brand-gray hover:bg-gray-50' => !request()->is('admin/prodi*')])>
                <span class="flex items-center justify-center"><i data-feather="book-open" class="w-5 h-5"></i></span>
                <span class="text-[14px]">Program Studi</span>
            </a>
            <a href="/admin/tugas" @class(['flex items-center gap-3 px-4 py-3 font-bold rounded-xl transition-all relative', 'bg-brand-blue-light text-brand-blue' => request()->is('admin/tugas*'), 'text-brand-gray hover:bg-gray-50' => !request()->is('admin/tugas*')])>
                <span class="flex items-center justify-center"><i data-feather="shield" class="w-5 h-5"></i></span>
                <span class="text-[14px]">Manajemen Divisi</span>
            </a>
            <a href="/admin/settings" @class(['flex items-center gap-3 px-4 py-3 font-bold rounded-xl transition-all relative', 'bg-brand-blue-light text-brand-blue' => request()->is('admin/settings*'), 'text-brand-gray hover:bg-gray-50' => !request()->is('admin/settings*')])>
                <span class="flex items-center justify-center"><i data-feather="settings" class="w-5 h-5"></i></span>
                <span class="text-[14px]">Pengaturan</span>
            </a>
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
                        @if($totalPending > 0)
                            <div class="absolute -top-1 -right-1 w-2.5 h-2.5 bg-red-500 rounded-full border-2 border-brand-bg"></div>
                        @endif
                    </span>
                </div>
                <div class="h-8 w-px bg-gray-200"></div>
                
                {{-- PROFIL ADMIN & TOMBOL LOGOUT --}}
                <div class="flex items-center gap-4">
                    <div class="text-right">
                        <p class="text-[13px] font-bold text-brand-dark">{{ $user->name ?? 'Admin' }}</p>
                        <p class="text-[11px] font-semibold text-brand-gray">{{ $isSuperAdmin ? 'Super Admin' : ($divisi ?? 'Staff') }}</p>
                    </div>
                    <img src="https://ui-avatars.com/api/?name={{ urlencode($user->name ?? 'Admin') }}&background=0F172A&color=fff" class="w-10 h-10 rounded-full border border-gray-200">
                    
                    {{-- Tombol Logout --}}
                    <form action="{{ route('logout') }}" method="POST" class="ml-2">
                        @csrf
                        <button type="submit" class="p-2.5 text-red-500 bg-red-50 hover:bg-red-500 hover:text-white rounded-xl transition-all shadow-sm flex items-center justify-center" title="Keluar dari Sistem">
                            <i data-feather="power" class="w-4 h-4"></i>
                        </button>
                    </form>
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