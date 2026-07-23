<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Admin Portal') - SPMB Adzkia</title>

    {{-- ✅ FIX M1: Baca state sidebar dari localStorage SEBELUM Alpine mount
         Mencegah flash/kedip konten overlap sidebar saat halaman pertama load --}}
    <script>
        (function () {
            var c = localStorage.getItem('sidebar_collapsed') === 'true';
            document.documentElement.setAttribute('data-sidebar', c ? 'collapsed' : 'expanded');
        })();
    </script>

    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/feather-icons/dist/feather.min.js"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: { sans: ['Manrope', 'sans-serif'] },
                    colors: {
                        'brand-bg':         '#F8FAFC',
                        'brand-dark':       '#0F172A',
                        'brand-gray':       '#64748B',
                        'brand-blue':       '#2563EB',
                        'brand-blue-light': '#EFF6FF',
                    }
                }
            }
        }
    </script>

    <style>
        [x-cloak] { display: none !important; }

        .custom-scrollbar::-webkit-scrollbar       { width: 4px; }
        .custom-scrollbar::-webkit-scrollbar-thumb { background: #E2E8F0; border-radius: 10px; }

        /* ✅ FIX C1: HAPUS overflow-x: hidden dari html/body
           Dulu: html, body { max-width: 100%; overflow-x: hidden; }
           overflow-x: hidden pada html/body memindahkan scroll root dari
           viewport ke body → position: sticky TIDAK BEKERJA.
           Sekarang overflow-x hanya ada di .main-content (aman). */
        html, body { max-width: 100%; }

        /* ✅ FIX M1: CSS pre-Alpine untuk sidebar margin — cegah flash saat load */
        [data-sidebar="collapsed"] .main-content { margin-left: 72px; }
        [data-sidebar="expanded"]  .main-content { margin-left: 272px; }
        @media (max-width: 1023px) {
            [data-sidebar="collapsed"] .main-content,
            [data-sidebar="expanded"]  .main-content { margin-left: 0 !important; }
        }

        aside.sidebar-expanded  { width: 272px; min-width: 272px; }
        aside.sidebar-collapsed { width: 72px;  min-width: 72px; }

        .sidebar-collapsed .nav-label,
        .sidebar-collapsed .nav-category,
        .sidebar-collapsed .nav-badge,
        .sidebar-collapsed .sidebar-footer-text,
        .sidebar-collapsed .logo-text     { display: none !important; }
        .sidebar-collapsed .nav-item      { justify-content: center; gap: 0; padding-left: 0; padding-right: 0; }
        .sidebar-collapsed .nav-icon      { margin: 0; }
        .sidebar-collapsed .sidebar-header { justify-content: center; padding-left: 0; padding-right: 0; }
        .sidebar-collapsed .logo-wrapper  { justify-content: center; width: 100%; }

        .nav-icon, .nav-icon svg, .nav-icon i { width: 20px !important; height: 20px !important; flex-shrink: 0; }
        .logo-wrapper img { flex-shrink: 0; }
        aside { transition: width 0.25s ease; }

        /* ✅ FIX C1 + C3:
           C1 → overflow-x: hidden DIPINDAH ke sini (aman, bukan di html/body)
           C3 → HAPUS min-h-screen dari sini
                Dulu: min-h-screen di body (flex) + min-h-screen di .main-content
                = dua elemen bersaing untuk 100vh → layout "terpotong" / split.
                flex-1 sudah cukup untuk mengisi sisa ruang body. */
        .main-content { transition: margin-left 0.25s ease; min-width: 0; overflow-x: hidden; }

        /* ✅ FIX C2: HAPUS overflow-x: auto dari <main>
           Dulu: main { min-width:0; max-width:100%; overflow-x: auto; }
           overflow-x: auto menjadikan <main> scroll container baru →
           elemen sticky di dalamnya menempel ke <main>, bukan viewport.
           Sekarang: scroll horizontal ditangani per-halaman via .table-scroll */
        main { min-width: 0; }

        /* Helper untuk tabel / konten lebar — pakai di masing-masing halaman:
           <div class="table-scroll"><table ...></table></div> */
        .table-scroll { overflow-x: auto; -webkit-overflow-scrolling: touch; }

        /* ✅ FIX M3: Tambah position: relative pada nav agar tidak
           konflik overflow antara aside (hidden) dan nav (auto).
           Nav selalu overflow-y-auto; override ke visible hanya saat
           collapsed supaya tooltip tidak terpotong. */
        nav.sidebar-nav { position: relative; }

        .nav-tooltip { display: none; }
    </style>

    <script>
        (function () {
            var css = [
                '@keyframes spin-pulse { 0%,100% { opacity:1; } 50% { opacity:.4; } }'
            ].join('\n');
            var style = document.createElement('style');
            style.textContent = css;
            document.head.appendChild(style);
        })();
    </script>
</head>

{{-- body: flex + min-h-screen sudah CUKUP. Tidak perlu min-h-screen lagi di child. --}}
<body class="bg-brand-bg antialiased text-brand-dark flex min-h-screen"
      x-data="adminLayout()">

@php
    $user         = auth()->user();
    $isSuperAdmin = $user && $user->role === 'super_admin';
    $divisi       = $user ? ($user->divisi ?? '') : '';

    $pendingPembayaran = \App\Models\DataPendaftar::where('status_pembayaran', 'Menunggu Validasi')->count();
    $pendingFormulir   = \App\Models\DataPendaftar::where('status_pendaftaran', 'menunggu verifikasi')->count();
    $pendingBerkas     = \App\Models\DataPendaftar::whereIn('status_daftar_ulang', ['Menunggu Validasi'])->whereNotNull('bukti_daftar_ulang')->count();
    $totalPending      = $pendingPembayaran + $pendingFormulir + $pendingBerkas;
@endphp

{{-- Mobile overlay --}}
<div x-show="mobileSidebar"
     x-cloak
     x-transition.opacity
     x-on:click="mobileSidebar = false"
     class="fixed inset-0 bg-black/40 z-30 lg:hidden"></div>

{{-- ══ SIDEBAR ══════════════════════════════════════════════════════════ --}}
{{-- FIX M4: aside pakai overflow-hidden permanen; tooltip bebas via JS popup --}}
<aside :class="[
           mobileSidebar ? 'translate-x-0' : '-translate-x-full lg:translate-x-0',
           collapsed ? 'sidebar-collapsed' : 'sidebar-expanded'
       ]"
       class="bg-white border-r border-gray-100 flex flex-col fixed h-screen z-40 overflow-hidden">

    {{-- Logo + tombol collapse --}}
    <div class="sidebar-header h-16 flex items-center justify-between px-4 shrink-0 border-b border-gray-50"
         style="background: white; position: relative; z-index: 41;">
        <div class="logo-wrapper flex items-center gap-3 overflow-hidden min-w-0">
            <img src="{{ asset('images/logo-adzkia.png') }}"
                 alt="Logo" class="h-8 w-8 object-contain shrink-0 rounded-lg">
            <div class="logo-text flex flex-col whitespace-nowrap min-w-0">
                <span class="font-extrabold text-[15px] tracking-tight leading-tight text-brand-dark">SPMB Portal</span>
                <span class="text-[10px] font-semibold text-brand-gray">Adzkia Admin</span>
            </div>
        </div>
        <button x-on:click="collapsed = !collapsed; saveCollapsed(collapsed)"
            class="hidden lg:flex items-center justify-center w-7 h-7 rounded-lg hover:bg-gray-100 text-brand-gray hover:text-brand-dark transition-colors shrink-0"
            :title="collapsed ? 'Perluas sidebar' : 'Ciutkan sidebar'">
            <svg class="w-4 h-4 transition-transform duration-300" :class="collapsed ? 'rotate-180' : ''"
                 fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"
                 stroke-linecap="round" stroke-linejoin="round">
                <path d="M11 17l-5-5 5-5M18 17l-5-5 5-5"/>
            </svg>
        </button>
    </div>

    {{-- ✅ FIX M3: Nav selalu overflow-y-auto; hanya override ke visible saat
         collapsed agar tooltip JS popup tidak terpotong oleh aside --}}
    <nav class="sidebar-nav flex-1 px-2 py-3 custom-scrollbar space-y-0.5 overflow-y-auto"
         :style="collapsed ? 'overflow: visible' : ''">

        <p class="nav-category px-3 pt-1 pb-1.5 text-[9px] font-black uppercase tracking-widest text-gray-400">Umum</p>

        <a href="/admin"
           class="nav-item group relative flex items-center gap-3 px-3 py-2.5 font-bold rounded-xl transition-all
               {{ request()->is('admin') && !request()->is('admin/*') ? 'bg-brand-blue-light text-brand-blue' : 'text-brand-gray hover:bg-gray-50 hover:text-brand-dark' }}">
            <span class="nav-icon flex items-center justify-center shrink-0"><i data-feather="grid" class="w-5 h-5"></i></span>
            <span class="nav-label text-[13px] whitespace-nowrap">Dashboard</span>
            @if($totalPending > 0)
            <span class="nav-badge ml-auto px-1.5 py-0.5 bg-red-500 text-white text-[9px] font-black rounded-full">{{ $totalPending }}</span>
            @endif
            <span class="nav-tooltip">Dashboard</span>
        </a>

        <a href="/admin/pendaftar"
           class="nav-item group relative flex items-center gap-3 px-3 py-2.5 font-bold rounded-xl transition-all
               {{ request()->is('admin/pendaftar*') ? 'bg-brand-blue-light text-brand-blue' : 'text-brand-gray hover:bg-gray-50 hover:text-brand-dark' }}">
            <span class="nav-icon flex items-center justify-center shrink-0"><i data-feather="users" class="w-5 h-5"></i></span>
            <span class="nav-label text-[13px] whitespace-nowrap">Data Pendaftar</span>
            <span class="nav-tooltip">Data Pendaftar</span>
        </a>

        <a href="/admin/activity-log"
           class="nav-item group relative flex items-center gap-3 px-3 py-2.5 font-bold rounded-xl transition-all
               {{ request()->is('admin/activity-log') ? 'bg-brand-blue-light text-brand-blue' : 'text-brand-gray hover:bg-gray-50 hover:text-brand-dark' }}">
            <span class="nav-icon flex items-center justify-center shrink-0"><i data-feather="activity" class="w-5 h-5"></i></span>
            <span class="nav-label text-[13px] whitespace-nowrap">Log Aktivitas</span>
            <span class="nav-tooltip">Log Aktivitas</span>
        </a>

        @if($isSuperAdmin || in_array($divisi, ['Keuangan', 'Verifikator Berkas']))
        <p class="nav-category px-3 pt-3.5 pb-1.5 text-[9px] font-black uppercase tracking-widest text-gray-400">Verifikasi</p>

        @if($isSuperAdmin || $divisi === 'Keuangan')
        <a href="/admin/validasi-pembayaran"
           class="nav-item group relative flex items-center gap-3 px-3 py-2.5 font-bold rounded-xl transition-all
               {{ request()->is('admin/validasi-pembayaran') ? 'bg-brand-blue-light text-brand-blue' : 'text-brand-gray hover:bg-gray-50 hover:text-brand-dark' }}">
            <span class="nav-icon flex items-center justify-center shrink-0 relative">
                <i data-feather="credit-card" class="w-5 h-5"></i>
                @if($pendingPembayaran > 0)<span class="absolute -top-1.5 -right-1.5 w-4 h-4 bg-red-500 text-white text-[7px] font-black rounded-full flex items-center justify-center">{{ $pendingPembayaran }}</span>@endif
            </span>
            <span class="nav-label text-[13px] whitespace-nowrap">Validasi Pembayaran</span>
            @if($pendingPembayaran > 0)<span class="nav-badge ml-auto px-1.5 py-0.5 bg-red-500 text-white text-[9px] font-black rounded-full">{{ $pendingPembayaran }}</span>@endif
            <span class="nav-tooltip">Validasi Pembayaran</span>
        </a>
        @endif

        @if($isSuperAdmin || $divisi === 'Verifikator Berkas')
        <a href="/admin/validasi-formulir"
           class="nav-item group relative flex items-center gap-3 px-3 py-2.5 font-bold rounded-xl transition-all
               {{ request()->is('admin/validasi-formulir') ? 'bg-brand-blue-light text-brand-blue' : 'text-brand-gray hover:bg-gray-50 hover:text-brand-dark' }}">
            <span class="nav-icon flex items-center justify-center shrink-0 relative">
                <i data-feather="file-text" class="w-5 h-5"></i>
                @if($pendingFormulir > 0)<span class="absolute -top-1.5 -right-1.5 w-4 h-4 bg-red-500 text-white text-[7px] font-black rounded-full flex items-center justify-center">{{ $pendingFormulir }}</span>@endif
            </span>
            <span class="nav-label text-[13px] whitespace-nowrap">Validasi Formulir</span>
            @if($pendingFormulir > 0)<span class="nav-badge ml-auto px-1.5 py-0.5 bg-red-500 text-white text-[9px] font-black rounded-full">{{ $pendingFormulir }}</span>@endif
            <span class="nav-tooltip">Validasi Formulir</span>
        </a>

        <a href="/admin/validasi-daftar-ulang"
           class="nav-item group relative flex items-center gap-3 px-3 py-2.5 font-bold rounded-xl transition-all
               {{ request()->is('admin/validasi-daftar-ulang') ? 'bg-brand-blue-light text-brand-blue' : 'text-brand-gray hover:bg-gray-50 hover:text-brand-dark' }}">
            <span class="nav-icon flex items-center justify-center shrink-0 relative">
                <i data-feather="check-square" class="w-5 h-5"></i>
                @if($pendingBerkas > 0)<span class="absolute -top-1.5 -right-1.5 w-4 h-4 bg-red-500 text-white text-[7px] font-black rounded-full flex items-center justify-center">{{ $pendingBerkas }}</span>@endif
            </span>
            <span class="nav-label text-[13px] whitespace-nowrap">Berkas Daftar Ulang</span>
            @if($pendingBerkas > 0)<span class="nav-badge ml-auto px-1.5 py-0.5 bg-red-500 text-white text-[9px] font-black rounded-full">{{ $pendingBerkas }}</span>@endif
            <span class="nav-tooltip">Berkas Daftar Ulang</span>
        </a>
        @endif

        <a href="/admin/pengumuman"
           class="nav-item group relative flex items-center gap-3 px-3 py-2.5 font-bold rounded-xl transition-all
               {{ request()->is('admin/pengumuman*') ? 'bg-brand-blue-light text-brand-blue' : 'text-brand-gray hover:bg-gray-50 hover:text-brand-dark' }}">
            <span class="nav-icon flex items-center justify-center shrink-0"><i data-feather="award" class="w-5 h-5"></i></span>
            <span class="nav-label text-[13px] whitespace-nowrap">Pengumuman</span>
            <span class="nav-tooltip">Pengumuman</span>
        </a>
        @endif

        @if($isSuperAdmin || $divisi === 'Humas & Informasi')
        <p class="nav-category px-3 pt-3.5 pb-1.5 text-[9px] font-black uppercase tracking-widest text-gray-400">Konten</p>
        <a href="/admin/berita"
           class="nav-item group relative flex items-center gap-3 px-3 py-2.5 font-bold rounded-xl transition-all
               {{ request()->is('admin/berita*') ? 'bg-brand-blue-light text-brand-blue' : 'text-brand-gray hover:bg-gray-50 hover:text-brand-dark' }}">
            <span class="nav-icon flex items-center justify-center shrink-0"><i data-feather="rss" class="w-5 h-5"></i></span>
            <span class="nav-label text-[13px] whitespace-nowrap">Berita</span>
            <span class="nav-tooltip">Berita</span>
        </a>
        <a href="/admin/faq"
           class="nav-item group relative flex items-center gap-3 px-3 py-2.5 font-bold rounded-xl transition-all
               {{ request()->is('admin/faq*') ? 'bg-brand-blue-light text-brand-blue' : 'text-brand-gray hover:bg-gray-50 hover:text-brand-dark' }}">
            <span class="nav-icon flex items-center justify-center shrink-0"><i data-feather="help-circle" class="w-5 h-5"></i></span>
            <span class="nav-label text-[13px] whitespace-nowrap">FAQ</span>
            <span class="nav-tooltip">FAQ</span>
        </a>
        @endif

        @if($isSuperAdmin)
        <p class="nav-category px-3 pt-3.5 pb-1.5 text-[9px] font-black uppercase tracking-widest text-gray-400">Master Data</p>
        <a href="/admin/prodi"
           class="nav-item group relative flex items-center gap-3 px-3 py-2.5 font-bold rounded-xl transition-all
               {{ request()->is('admin/prodi*') ? 'bg-brand-blue-light text-brand-blue' : 'text-brand-gray hover:bg-gray-50 hover:text-brand-dark' }}">
            <span class="nav-icon flex items-center justify-center shrink-0"><i data-feather="book-open" class="w-5 h-5"></i></span>
            <span class="nav-label text-[13px] whitespace-nowrap">Program Studi</span>
            <span class="nav-tooltip">Program Studi</span>
        </a>
        <a href="{{ route('admin.master.biaya-daftar-ulang.index') }}"
           class="nav-item group relative flex items-center gap-3 px-3 py-2.5 font-bold rounded-xl transition-all
               {{ request()->is('admin/master/biaya-daftar-ulang*') ? 'bg-brand-blue-light text-brand-blue' : 'text-brand-gray hover:bg-gray-50 hover:text-brand-dark' }}">
            <span class="nav-icon flex items-center justify-center shrink-0"><i data-feather="dollar-sign" class="w-5 h-5"></i></span>
            <span class="nav-label text-[13px] whitespace-nowrap">Biaya Daftar Ulang</span>
            <span class="nav-tooltip">Biaya Daftar Ulang</span>
        </a>
        <a href="{{ route('admin.master.jalur.index') }}"
           class="nav-item group relative flex items-center gap-3 px-3 py-2.5 font-bold rounded-xl transition-all
               {{ request()->is('admin/master/jalur*') ? 'bg-brand-blue-light text-brand-blue' : 'text-brand-gray hover:bg-gray-50 hover:text-brand-dark' }}">
            <span class="nav-icon flex items-center justify-center shrink-0"><i data-feather="git-commit" class="w-5 h-5"></i></span>
            <span class="nav-label text-[13px] whitespace-nowrap">Jalur Pendaftaran</span>
            <span class="nav-tooltip">Jalur Pendaftaran</span>
        </a>
        <a href="{{ route('admin.master.sekolah.index') }}"
           class="nav-item group relative flex items-center gap-3 px-3 py-2.5 font-bold rounded-xl transition-all
               {{ request()->is('admin/master/sekolah*') ? 'bg-brand-blue-light text-brand-blue' : 'text-brand-gray hover:bg-gray-50 hover:text-brand-dark' }}">
            <span class="nav-icon flex items-center justify-center shrink-0"><i data-feather="map" class="w-5 h-5"></i></span>
            <span class="nav-label text-[13px] whitespace-nowrap">Sekolah</span>
            <span class="nav-tooltip">Sekolah</span>
        </a>

        <p class="nav-category px-3 pt-3.5 pb-1.5 text-[9px] font-black uppercase tracking-widest text-gray-400">Sistem</p>
        <a href="/admin/tugas"
           class="nav-item group relative flex items-center gap-3 px-3 py-2.5 font-bold rounded-xl transition-all
               {{ request()->is('admin/tugas*') ? 'bg-brand-blue-light text-brand-blue' : 'text-brand-gray hover:bg-gray-50 hover:text-brand-dark' }}">
            <span class="nav-icon flex items-center justify-center shrink-0"><i data-feather="shield" class="w-5 h-5"></i></span>
            <span class="nav-label text-[13px] whitespace-nowrap">Manajemen Divisi</span>
            <span class="nav-tooltip">Manajemen Divisi</span>
        </a>
        <a href="/admin/settings"
           class="nav-item group relative flex items-center gap-3 px-3 py-2.5 font-bold rounded-xl transition-all
               {{ request()->is('admin/settings*') ? 'bg-brand-blue-light text-brand-blue' : 'text-brand-gray hover:bg-gray-50 hover:text-brand-dark' }}">
            <span class="nav-icon flex items-center justify-center shrink-0"><i data-feather="settings" class="w-5 h-5"></i></span>
            <span class="nav-label text-[13px] whitespace-nowrap">Pengaturan</span>
            <span class="nav-tooltip">Pengaturan</span>
        </a>
        @endif

    </nav>

    <div class="p-3 shrink-0 border-t border-gray-100" style="background: white; position: relative; z-index: 41;">
        <div class="flex items-center gap-2.5 px-3 py-2.5 bg-brand-bg rounded-xl">
            <div class="w-2 h-2 bg-emerald-500 rounded-full shrink-0 animate-pulse"></div>
            <div class="sidebar-footer-text min-w-0">
                <p class="text-[10px] font-extrabold uppercase tracking-widest text-brand-gray leading-none">System Active</p>
                @if($totalPending > 0)
                <p class="text-[11px] text-red-500 font-bold mt-0.5 truncate">{{ $totalPending }} menunggu</p>
                @else
                <p class="text-[11px] text-brand-gray font-medium mt-0.5 truncate">Semua beres</p>
                @endif
            </div>
        </div>
    </div>

</aside>

{{-- Tooltip popup via JS — bebas dari overflow container sidebar --}}
<div id="sidebar-tooltip-popup"
     style="display:none; position:fixed; z-index:9999; background:#0F172A; color:#fff;
            font-size:11px; font-weight:700; padding:6px 14px; border-radius:8px;
            white-space:nowrap; box-shadow:0 4px 16px rgba(0,0,0,0.3);
            pointer-events:none; transform:translateY(-50%);">
</div>

{{-- ══ KONTEN UTAMA ══════════════════════════════════════════════════════ --}}
{{-- ✅ FIX C3: HAPUS min-h-screen dari sini → gunakan flex-1 saja.
     min-h-screen di body + min-h-screen di sini = konflik height → layout split.
     flex-1 sudah cukup mengisi sisa tinggi body yang min-h-screen. --}}
<div class="main-content flex-1 flex flex-col w-full ml-0"
     :class="collapsed ? 'lg:ml-[72px]' : 'lg:ml-[272px]'">

    {{-- ✅ TOPBAR: sticky top-0 BEKERJA sekarang karena C1 sudah fix scroll root.
         shrink-0 → header tidak ikut terkompresi flex saat konten panjang. --}}
    <header class="h-16 px-4 lg:px-8 flex items-center justify-between sticky top-0 bg-brand-bg/95 backdrop-blur-md z-20 border-b border-gray-100 gap-4 shrink-0">

        <button x-on:click="mobileSidebar = true"
            class="lg:hidden flex items-center justify-center p-2 rounded-xl bg-white border border-gray-100 shadow-sm text-brand-gray hover:text-brand-blue transition-colors shrink-0">
            <i data-feather="menu" class="w-5 h-5"></i>
        </button>

        <div class="relative flex-1 max-w-sm hidden sm:block">
            <i data-feather="search" class="absolute left-3.5 top-1/2 -translate-y-1/2 w-4 h-4 text-brand-gray pointer-events-none"></i>
            <input type="text" placeholder="Cari pendaftar..."
                class="w-full pl-10 pr-4 py-2.5 bg-white border border-gray-100 rounded-full text-[13px] outline-none shadow-sm focus:border-brand-blue transition-all">
        </div>

        <div class="flex items-center gap-2.5 ml-auto">
            <div class="relative">
                <button class="flex items-center justify-center w-9 h-9 rounded-xl bg-white border border-gray-100 shadow-sm text-brand-gray hover:text-brand-blue transition-all">
                    <i data-feather="bell" class="w-4 h-4"></i>
                </button>
                @if($totalPending > 0)
                <span class="absolute -top-1 -right-1 w-4 h-4 bg-red-500 text-white text-[8px] font-black rounded-full flex items-center justify-center border-2 border-brand-bg">
                    {{ $totalPending > 9 ? '9+' : $totalPending }}
                </span>
                @endif
            </div>
            <div class="h-6 w-px bg-gray-200 hidden sm:block"></div>
            <div class="hidden sm:flex flex-col text-right">
                <p class="text-[12px] font-bold text-brand-dark leading-tight">{{ $user->name ?? 'Admin' }}</p>
                <p class="text-[10px] font-semibold text-brand-gray">{{ $isSuperAdmin ? 'Super Admin' : ($divisi ?: 'Staff') }}</p>
            </div>
            <img src="https://ui-avatars.com/api/?name={{ urlencode($user->name ?? 'Admin') }}&background=0F172A&color=fff&size=80"
                 class="w-8 h-8 rounded-full border border-gray-200 shrink-0 object-cover">
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit"
                    class="flex items-center justify-center w-9 h-9 text-red-500 bg-red-50 hover:bg-red-500 hover:text-white rounded-xl transition-all shadow-sm"
                    title="Keluar">
                    <i data-feather="log-out" class="w-4 h-4"></i>
                </button>
            </form>
        </div>
    </header>

    {{-- ✅ FIX C2 + minor: bg-brand-bg eksplisit agar halaman pendek tidak
         tampilkan artefak transparansi dari backdrop-blur header --}}
    <main class="flex-1 px-4 lg:px-8 py-8 bg-brand-bg">
        @yield('admin-content')
    </main>

</div>

<script>
function adminLayout() {
    return {
        mobileSidebar: false,
        collapsed: localStorage.getItem('sidebar_collapsed') === 'true',

        // ✅ FIX M1: saveCollapsed juga update data-sidebar attribute
        // agar CSS pre-Alpine selalu sinkron dengan state Alpine
        saveCollapsed(val) {
            localStorage.setItem('sidebar_collapsed', val);
            document.documentElement.setAttribute('data-sidebar', val ? 'collapsed' : 'expanded');
        },
    };
}

// ── Tooltip via JS — bebas dari overflow container sidebar ──────────
(function () {
    const popup = document.getElementById('sidebar-tooltip-popup');
    let hideTimer = null;

    function isCollapsed() {
        return document.querySelector('aside.sidebar-collapsed') !== null;
    }

    document.addEventListener('mouseover', function (e) {
        if (!isCollapsed()) return;
        const item = e.target.closest('.nav-item');
        if (!item) return;
        const tooltipEl = item.querySelector('.nav-tooltip');
        if (!tooltipEl) return;
        const text = tooltipEl.textContent.trim();
        if (!text) return;
        clearTimeout(hideTimer);
        const rect = item.getBoundingClientRect();
        popup.textContent = text;
        popup.style.display = 'block';
        popup.style.top  = (rect.top + rect.height / 2) + 'px';
        popup.style.left = (rect.right + 10) + 'px';
    });

    document.addEventListener('mouseout', function (e) {
        const item = e.target.closest('.nav-item');
        if (!item) return;
        hideTimer = setTimeout(() => { popup.style.display = 'none'; }, 80);
    });
})();
</script>

{{-- ✅ FIX M2: @stack('scripts') SEBELUM feather.replace()
     Dulu: feather.replace() jalan duluan → Chart.js belum load → race condition
     Sekarang: semua script halaman (@push) load dulu, baru feather.replace() --}}
@stack('scripts')

<script>
    if (typeof feather !== 'undefined') feather.replace({ 'stroke-width': 1.75 });
    document.addEventListener('alpine:initialized', function () {
        if (typeof feather !== 'undefined') feather.replace({ 'stroke-width': 1.75 });
    });
</script>

</body>
</html>