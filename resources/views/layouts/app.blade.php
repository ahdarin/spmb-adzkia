<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'PMB Universitas Adzkia')</title>

    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <script src="https://unpkg.com/feather-icons"></script>

    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: { sans: ['Manrope', 'sans-serif'] },
                    colors: {
                        'adzkia-red':          '#d9241c',
                        'adzkia-blue':         '#2c7ebd',
                        'adzkia-bg':           '#FAFBFC',
                        'adzkia-dark':         '#1e293b',
                        'adzkia-muted':        '#64748b',
                        'adzkia-badge-bg':     '#eff6ff',
                        'adzkia-badge-txt':    '#2c7ebd',
                        'adzkia-badge-red-bg': '#fef2f2',
                        'adzkia-badge-red-txt':'#d9241c',
                    }
                }
            }
        }
    </script>

    <style>
        {{--
            ✅ FIX m5: HAPUS overflow-x: hidden dari <body>
            Sebelumnya: body { ... overflow-x-hidden }
            overflow-x: hidden pada body memindahkan scroll root → sama seperti
            bug C1 di admin layout. Pada public layout ini navbar sudah `sticky top-0`
            dan akan tetap bekerja HANYA jika body tidak punya overflow.
            Overflow-x dicegah via wrapper per-section jika diperlukan.

            ✅ FIX m5 bonus: Definisikan --navbar-h sebagai CSS variable
            agar step-tracker dan elemen sticky lain bisa pakai top: var(--navbar-h)
            tanpa hardcode angka.
        --}}
        :root {
            --navbar-h: 64px; /* Sesuaikan jika tinggi navbar berubah */
        }
        html, body {
            max-width: 100%;
            /* TIDAK ada overflow-x: hidden di sini */
        }
        [x-cloak] { display: none !important; }
    </style>

    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.store('kampus', {
                modalOpen: false,
                activeProgram: {},

                openModal(program) {
                    this.activeProgram = program;
                    this.modalOpen = true;
                    setTimeout(() => feather.replace(), 50);
                },

                closeModal() {
                    this.modalOpen = false;
                }
            });
        });
    </script>
</head>

{{--
    ✅ FIX m5: Hapus overflow-x-hidden dari class body.
    Ganti dengan bg-adzkia-bg eksplisit + flex flex-col min-h-screen saja.
    overflow-x dicegah di level wrapper konten masing-masing section jika perlu.
--}}
<body class="bg-adzkia-bg antialiased text-adzkia-dark w-full m-0 p-0 flex flex-col min-h-screen">

    <x-navbar />

    {{--
        ✅ FIX m5: Tambah bg-adzkia-bg eksplisit pada <main>
        Sebelumnya tidak ada background → area kosong di bawah konten
        bisa terlihat berbeda warna di beberapa browser.
    --}}
    <main class="flex-grow w-full m-0 p-0 bg-adzkia-bg">
        @yield('content')
    </main>

    <x-footer />

    {{-- MODAL PROGRAM STUDI --}}
    <div x-data x-show="$store.kampus.modalOpen"
         class="fixed inset-0 z-[100] flex items-end sm:items-center justify-center sm:px-4"
         style="display: none;" x-cloak>

        {{-- Backdrop --}}
        <div x-show="$store.kampus.modalOpen"
             x-transition.opacity
             @click="$store.kampus.closeModal()"
             class="absolute inset-0 bg-adzkia-dark/80 backdrop-blur-sm cursor-pointer"></div>

        {{-- Modal Panel --}}
        <div x-show="$store.kampus.modalOpen"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 translate-y-8 sm:scale-95"
             x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
             x-transition:leave-end="opacity-0 translate-y-8 sm:scale-95"
             class="bg-white w-full sm:max-w-lg rounded-t-3xl sm:rounded-3xl shadow-2xl relative z-10 overflow-hidden max-h-[92dvh] overflow-y-auto">

            <div class="p-6 sm:p-10">
                {{-- Handle bar mobile --}}
                <div class="w-10 h-1 bg-gray-200 rounded-full mx-auto mb-5 sm:hidden"></div>

                <div class="flex justify-between items-start mb-5 sm:mb-6">
                    <div class="flex-1 pr-3">
                        <h2 class="text-2xl sm:text-3xl font-extrabold text-adzkia-dark tracking-tight"
                            x-text="$store.kampus.activeProgram.nama_prodi"></h2>
                        <span class="mt-2 sm:mt-3 inline-block px-3 py-1 bg-adzkia-badge-bg text-adzkia-badge-txt text-[11px] font-extrabold rounded-full uppercase"
                              x-text="$store.kampus.activeProgram.akreditasi"></span>
                    </div>
                    <button @click="$store.kampus.closeModal()"
                            class="p-2 hover:bg-gray-100 rounded-full transition-colors flex-shrink-0">
                        <i data-feather="x" class="text-gray-500 w-5 h-5"></i>
                    </button>
                </div>

                <div class="space-y-5 sm:space-y-6">
                    <div>
                        <h4 class="text-xs font-extrabold uppercase tracking-widest text-gray-400 mb-2">Deskripsi Program</h4>
                        <p class="text-gray-600 font-medium leading-relaxed text-[14px] sm:text-[15px]"
                           x-text="$store.kampus.activeProgram.deskripsi"></p>
                    </div>
                    <div class="bg-adzkia-bg p-4 sm:p-6 rounded-2xl border border-gray-100">
                        <h4 class="text-xs font-extrabold uppercase tracking-widest text-gray-400 mb-2">Estimasi Biaya Kuliah</h4>
                        <div class="flex items-baseline gap-2 flex-wrap">
                            <span class="text-sm font-medium text-gray-500">Mulai dari</span>
                            <span class="text-xl sm:text-2xl font-black text-adzkia-dark"
                                  x-text="'Rp ' + new Intl.NumberFormat('id-ID').format($store.kampus.activeProgram.biaya || 0)"></span>
                            <span class="text-xs text-gray-500">/semester</span>
                        </div>
                    </div>
                    <a href="/register"
                       class="w-full py-3.5 sm:py-4 bg-adzkia-dark text-white font-extrabold rounded-full shadow-xl hover:scale-105 active:scale-95 transition-all flex justify-center items-center text-[14px] sm:text-base">
                        Daftar Program Ini
                    </a>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/feather-icons/dist/feather.min.js"></script>
    <script>
        window.addEventListener('load', function () {
            feather.replace();
        });
    </script>
</body>
</html>