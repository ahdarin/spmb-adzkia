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
        
        <div class="h-24 flex items-center px-8 gap-3">
            <div class="w-10 h-12 bg-orange-500 rounded-b-xl rounded-t-sm flex items-end justify-center pb-1 relative">
                <div class="absolute top-0 w-full h-3 bg-red-600 rounded-t-sm"></div>
                <div class="w-3 h-3 bg-white rounded-full"></div>
            </div>
            <div class="flex flex-col">
                <span class="font-extrabold text-[18px] tracking-tight leading-tight text-brand-dark">SPMB Portal</span>
                <span class="text-[12px] font-semibold text-brand-gray">Adzkia Admin</span>
            </div>
        </div>

        <nav class="flex-1 px-4 py-4 space-y-1 overflow-y-auto custom-scrollbar">
            
            <a href="/admin" @class(['flex items-center gap-3 px-4 py-3 font-bold rounded-xl transition-all relative', 'bg-brand-blue-light text-brand-blue' => request()->is('admin'), 'text-brand-gray hover:bg-gray-50' => !request()->is('admin')])>
                <span class="flex items-center justify-center"><i data-feather="grid" class="w-5 h-5"></i></span>
                <span class="text-[14px]">Dashboard</span>
            </a>

            <a href="/admin/pendaftar" @class(['flex items-center gap-3 px-4 py-3 font-bold rounded-xl transition-all relative', 'bg-brand-blue-light text-brand-blue' => request()->is('admin/pendaftar*'), 'text-brand-gray hover:bg-gray-50' => !request()->is('admin/pendaftar*')])>
                <span class="flex items-center justify-center"><i data-feather="users" class="w-5 h-5"></i></span>
                <span class="text-[14px]">Data Pendaftar</span>
            </a>

            <div x-data="{ open: {{ request()->is('admin/validasi*') ? 'true' : 'false' }} }">
                <button @click="open = !open" 
                   :class="open || {{ request()->is('admin/validasi*') ? 'true' : 'false' }} ? 'text-brand-blue bg-brand-blue-light' : 'text-brand-gray hover:bg-gray-50'"
                   class="w-full flex items-center justify-between px-4 py-3 font-bold rounded-xl transition-all outline-none">
                    <div class="flex items-center gap-3">
                        <span class="flex items-center justify-center"><i data-feather="check-circle" class="w-5 h-5"></i></span>
                        <span class="text-[14px]">Validasi</span>
                    </div>
                    <span class="flex items-center justify-center transition-transform duration-300" :class="open ? 'rotate-180' : ''">
                        <i data-feather="chevron-down" class="w-4 h-4"></i>
                    </span>
                </button>
                <div x-show="open" x-cloak class="mt-1 ml-9 space-y-1">
                    <a href="/admin/validasi-pembayaran" class="block py-2 text-[13px] font-bold {{ request()->is('admin/validasi-pembayaran') ? 'text-brand-blue' : 'text-brand-gray hover:text-brand-dark' }}">Pembayaran</a>
                    <a href="/admin/validasi-daftar-ulang" class="block py-2 text-[13px] font-bold {{ request()->is('admin/validasi-daftar-ulang') ? 'text-brand-blue' : 'text-brand-gray hover:text-brand-dark' }}">Daftar Ulang</a>
                </div>
            </div>

            <a href="/admin/pengumuman" @class(['flex items-center gap-3 px-4 py-3 font-bold rounded-xl transition-all relative', 'bg-brand-blue-light text-brand-blue' => request()->is('admin/pengumuman*'), 'text-brand-gray hover:bg-gray-50 hover:text-brand-dark' => !request()->is('admin/pengumuman*')])>
                <span class="flex items-center justify-center w-5 h-5 shrink-0">
                    <i data-feather="volume-2"></i>
                </span>
                <span class="text-[14px]">Pengumuman</span>
            </a>

            <a href="/admin/prodi" @class(['flex items-center gap-3 px-4 py-3 font-bold rounded-xl transition-all relative', 'bg-brand-blue-light text-brand-blue' => request()->is('admin/prodi*'), 'text-brand-gray hover:bg-gray-50' => !request()->is('admin/prodi*')])>
                <span class="flex items-center justify-center"><i data-feather="book-open" class="w-5 h-5"></i></span>
                <span class="text-[14px]">Program Studi</span>
            </a>

            <a href="/admin/berita" @class(['flex items-center gap-3 px-4 py-3 font-bold rounded-xl transition-all relative', 'bg-brand-blue-light text-brand-blue' => request()->is('admin/berita*'), 'text-brand-gray hover:bg-gray-50' => !request()->is('admin/berita*')])>
                <span class="flex items-center justify-center"><i data-feather="file-text" class="w-5 h-5"></i></span>
                <span class="text-[14px]">Berita</span>
            </a>

            <a href="/admin/faq" @class(['flex items-center gap-3 px-4 py-3 font-bold rounded-xl transition-all relative', 'bg-brand-blue-light text-brand-blue' => request()->is('admin/faq*'), 'text-brand-gray hover:bg-gray-50' => !request()->is('admin/faq*')])>
                <span class="flex items-center justify-center"><i data-feather="help-circle" class="w-5 h-5"></i></span>
                <span class="text-[14px]">FAQ</span>
            </a>

            <a href="/admin/settings" @class(['flex items-center gap-3 px-4 py-3 font-bold rounded-xl transition-all relative', 'bg-brand-blue-light text-brand-blue' => request()->is('admin/settings*'), 'text-brand-gray hover:bg-gray-50' => !request()->is('admin/settings*')])>
                <span class="flex items-center justify-center"><i data-feather="settings" class="w-5 h-5"></i></span>
                <span class="text-[14px]">Settings</span>
            </a>
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
                <div class="flex items-center gap-4 text-brand-gray">
                    <span class="flex items-center justify-center"><i data-feather="bell" class="w-5 h-5"></i></span>
                    <span class="flex items-center justify-center"><i data-feather="help-circle" class="w-5 h-5"></i></span>
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