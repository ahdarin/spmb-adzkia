{{--
    ✅ FIX m4: Tambah `relative` pada <nav>
    Sebelumnya <nav> tidak punya position: relative, sehingga mobile dropdown
    yang pakai `absolute top-full left-0` bisa anchoring ke elemen lain
    di atasnya tergantung DOM context. Dengan `relative`, dropdown selalu
    anchoring tepat di bawah navbar ini.
--}}
<nav x-data="navbarData()"
     @scroll.window="onScroll()"
     class="relative flex items-center justify-between px-4 sm:px-6 md:px-16 py-3 md:py-5 bg-white sticky top-0 z-50 shadow-sm border-b border-gray-100 transition-all duration-300">

    {{-- LOGO --}}
    <a href="/" class="flex items-center gap-2 sm:gap-3 group z-50 flex-shrink-0">
        <img src="{{ asset('images/logo-adzkia.png') }}" alt="Logo Universitas Adzkia" class="h-9 sm:h-11 w-auto transition-transform group-hover:scale-105 duration-300">
        <div class="flex flex-col">
            <span class="text-base sm:text-[18px] md:text-xl font-black text-adzkia-blue tracking-tight leading-none">SPMB</span>
            <span class="text-[11px] sm:text-[13px] md:text-[15px] font-bold text-adzkia-red tracking-tight">Universitas Adzkia</span>
        </div>
    </a>

    {{-- NAV LINKS (Desktop) --}}
    <div class="hidden lg:flex items-center gap-5 xl:gap-7 text-[13px] xl:text-[14px] font-bold text-adzkia-muted mt-1">
        <a href="/" @click="activeTab = 'beranda'" :class="activeTab === 'beranda' ? 'text-adzkia-blue border-b-[3px] border-adzkia-blue pb-1.5' : 'hover:text-adzkia-blue transition-colors pb-1.5'">Beranda</a>
        <a href="/#prodi" @click="activeTab = 'prodi'" :class="activeTab === 'prodi' ? 'text-adzkia-blue border-b-[3px] border-adzkia-blue pb-1.5' : 'hover:text-adzkia-blue transition-colors pb-1.5'">Program Studi</a>
        <a href="/#fasilitas" @click="activeTab = 'fasilitas'" :class="activeTab === 'fasilitas' ? 'text-adzkia-blue border-b-[3px] border-adzkia-blue pb-1.5' : 'hover:text-adzkia-blue transition-colors pb-1.5'">Fasilitas</a>
        <a href="/#jalur-pendaftaran" @click="activeTab = 'jalur-pendaftaran'" :class="activeTab === 'jalur-pendaftaran' ? 'text-adzkia-blue border-b-[3px] border-adzkia-blue pb-1.5' : 'hover:text-adzkia-blue transition-colors pb-1.5'">Jalur Pendaftaran</a>
        <a href="/#berita" @click="activeTab = 'berita'" :class="activeTab === 'berita' ? 'text-adzkia-blue border-b-[3px] border-adzkia-blue pb-1.5' : 'hover:text-adzkia-blue transition-colors pb-1.5'">Berita</a>
        <a href="/#faq" @click="activeTab = 'faq'" :class="activeTab === 'faq' ? 'text-adzkia-blue border-b-[3px] border-adzkia-blue pb-1.5' : 'hover:text-adzkia-blue transition-colors pb-1.5'">FAQ</a>
        <a href="/#kontak" @click="activeTab = 'kontak'" :class="activeTab === 'kontak' ? 'text-adzkia-blue border-b-[3px] border-adzkia-blue pb-1.5' : 'hover:text-adzkia-blue transition-colors pb-1.5'">Kontak</a>
    </div>

    {{-- TOMBOL AUTH DESKTOP --}}
    <div class="hidden lg:flex items-center gap-3 flex-shrink-0">
        @if(session('is_pendaftar'))
            <a href="/dashboard-user" class="px-5 xl:px-6 py-2.5 bg-adzkia-blue text-white font-bold rounded-full hover:bg-blue-700 shadow-lg shadow-blue-600/20 active:scale-95 transition-all text-[13px] xl:text-[14px]">Dasbor Saya</a>
        @else
            <a href="/login" class="px-5 xl:px-6 py-2.5 border-2 border-adzkia-blue text-adzkia-blue font-bold rounded-full hover:bg-adzkia-badge-bg transition-all text-[13px] xl:text-[14px]">Masuk</a>
            <a href="/register" class="px-5 xl:px-6 py-2.5 bg-adzkia-red text-white font-bold rounded-full hover:bg-red-700 hover:shadow-lg hover:shadow-red-600/20 active:scale-95 transition-all text-[13px] xl:text-[14px]">Daftar</a>
        @endif
    </div>

    {{-- HAMBURGER (Mobile) --}}
    <div class="lg:hidden flex items-center z-50">
        <button @click="mobileMenuOpen = !mobileMenuOpen" class="text-adzkia-blue focus:outline-none p-2 bg-gray-50 rounded-xl hover:bg-gray-100 transition-colors">
            <svg x-show="!mobileMenuOpen" class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M4 6h16M4 12h16M4 18h16"></path>
            </svg>
            <svg x-show="mobileMenuOpen" class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="display: none;">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"></path>
            </svg>
        </button>
    </div>

    {{--
        MOBILE MENU DROPDOWN
        ✅ FIX m4: Karena <nav> sudah `relative`, `absolute top-full left-0`
        sekarang selalu anchoring tepat di bawah navbar — tidak terpengaruh
        context elemen lain di DOM.
    --}}
    <div x-show="mobileMenuOpen"
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0 -translate-y-4"
         x-transition:enter-end="opacity-100 translate-y-0"
         x-transition:leave="transition ease-in duration-150"
         x-transition:leave-start="opacity-100 translate-y-0"
         x-transition:leave-end="opacity-0 -translate-y-4"
         class="absolute top-full left-0 w-full bg-white border-t border-gray-100 shadow-xl px-5 py-4 flex flex-col gap-3 lg:hidden"
         style="display: none;"
         @click.outside="mobileMenuOpen = false">

        <div class="flex flex-col gap-0.5 font-bold text-[14px] text-adzkia-muted">
            <a href="/" @click="mobileMenuOpen = false" class="py-3 border-b border-gray-100 text-adzkia-blue">Beranda</a>
            <a href="/#prodi" @click="mobileMenuOpen = false" class="py-3 border-b border-gray-100 hover:text-adzkia-blue transition-colors">Program Studi</a>
            <a href="/#fasilitas" @click="mobileMenuOpen = false" class="py-3 border-b border-gray-100 hover:text-adzkia-blue transition-colors">Fasilitas</a>
            <a href="/#jalur-pendaftaran" @click="mobileMenuOpen = false" class="py-3 border-b border-gray-100 hover:text-adzkia-blue transition-colors">Jalur Pendaftaran</a>
            <a href="/#berita" @click="mobileMenuOpen = false" class="py-3 border-b border-gray-100 hover:text-adzkia-blue transition-colors">Berita</a>
            <a href="/#faq" @click="mobileMenuOpen = false" class="py-3 border-b border-gray-100 hover:text-adzkia-blue transition-colors">FAQ</a>
            <a href="/#kontak" @click="mobileMenuOpen = false" class="py-3 hover:text-adzkia-blue transition-colors">Kontak</a>
        </div>

        {{-- TOMBOL AUTH MOBILE --}}
        <div class="flex flex-col gap-2.5 pt-1">
            @if(session('is_pendaftar'))
                <a href="/dashboard-user" class="text-center w-full py-3 bg-adzkia-blue text-white font-bold rounded-full hover:bg-blue-700 transition-all shadow-md text-[14px]">Dasbor Saya</a>
            @else
                <a href="/login" class="text-center w-full py-3 border-2 border-adzkia-blue text-adzkia-blue font-bold rounded-full hover:bg-adzkia-badge-bg transition-all text-[14px]">Masuk</a>
                <a href="/register" class="text-center w-full py-3 bg-adzkia-red text-white font-bold rounded-full hover:bg-red-700 transition-all shadow-md text-[14px]">Daftar Sekarang</a>
            @endif
        </div>
    </div>
</nav>

<script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('navbarData', () => ({
            activeTab: 'beranda',
            mobileMenuOpen: false,

            init() {
                this.checkActiveRoute();
            },

            checkActiveRoute() {
                const path = window.location.pathname;
                const hash = window.location.hash;

                if (path.includes('program-studi')) {
                    this.activeTab = 'prodi';
                } else if (hash === '#kontak') {
                    this.activeTab = 'kontak';
                } else if (hash === '#faq') {
                    this.activeTab = 'faq';
                } else if (hash === '#berita') {
                    this.activeTab = 'berita';
                } else if (hash === '#jalur-pendaftaran') {
                    this.activeTab = 'jalur-pendaftaran';
                } else if (hash === '#fasilitas') {
                    this.activeTab = 'fasilitas';
                } else if (hash === '#prodi') {
                    this.activeTab = 'prodi';
                } else {
                    this.activeTab = 'beranda';
                }
            },

            onScroll() {
                if (window.location.pathname !== '/') return;

                const scrollPosition = window.scrollY + 150;
                const sections = ['kontak', 'faq', 'berita', 'jalur-pendaftaran', 'fasilitas', 'prodi'];
                let currentSection = 'beranda';

                for (let section of sections) {
                    const el = document.getElementById(section);
                    if (el && scrollPosition >= el.offsetTop) {
                        currentSection = section;
                        break;
                    }
                }
                this.activeTab = currentSection;
            }
        }));
    });
</script>