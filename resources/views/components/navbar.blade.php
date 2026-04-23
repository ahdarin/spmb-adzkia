<nav x-data="navbarData()" @scroll.window="onScroll()" class="flex items-center justify-between px-16 py-6 bg-adzkia-bg sticky top-0 z-40 shadow-sm transition-all duration-300">
    
    <a href="/" class="flex items-center gap-2 text-2xl font-extrabold text-adzkia-dark cursor-pointer hover:opacity-80 transition-opacity">
        <div class="w-8 h-10 bg-orange-500 rounded-b-xl rounded-t-sm flex items-end justify-center pb-1">
            <div class="w-4 h-4 bg-white rounded-full"></div>
        </div>
        <span>PMB ADZKIA</span>
    </a>

    <div class="hidden lg:flex items-center gap-7 text-[14px] font-bold text-gray-500">
        <a href="/" @click="activeTab = 'beranda'" :class="activeTab === 'beranda' ? 'text-adzkia-dark border-b-[3px] border-adzkia-dark pb-1' : 'hover:text-adzkia-dark transition-colors'">Beranda</a>
        <a href="/#prodi" @click="activeTab = 'prodi'" :class="activeTab === 'prodi' ? 'text-adzkia-dark border-b-[3px] border-adzkia-dark pb-1' : 'hover:text-adzkia-dark transition-colors'">Program Studi</a>
        <a href="/#fasilitas" @click="activeTab = 'fasilitas'" :class="activeTab === 'fasilitas' ? 'text-adzkia-dark border-b-[3px] border-adzkia-dark pb-1' : 'hover:text-adzkia-dark transition-colors'">Fasilitas</a>
        <a href="/#jalur-pendaftaran" @click="activeTab = 'jalur-pendaftaran'" :class="activeTab === 'jalur-pendaftaran' ? 'text-adzkia-dark border-b-[3px] border-adzkia-dark pb-1' : 'hover:text-adzkia-dark transition-colors'">Jalur Pendaftaran</a>
        <a href="/#berita" @click="activeTab = 'berita'" :class="activeTab === 'berita' ? 'text-adzkia-dark border-b-[3px] border-adzkia-dark pb-1' : 'hover:text-adzkia-dark transition-colors'">Berita</a>
        <a href="/#faq" @click="activeTab = 'faq'" :class="activeTab === 'faq' ? 'text-adzkia-dark border-b-[3px] border-adzkia-dark pb-1' : 'hover:text-adzkia-dark transition-colors'">FAQ</a>
        <a href="/#kontak" @click="activeTab = 'kontak'" :class="activeTab === 'kontak' ? 'text-adzkia-dark border-b-[3px] border-adzkia-dark pb-1' : 'hover:text-adzkia-dark transition-colors'">Kontak</a>
    </div>

    <div class="flex items-center gap-3">
        <a href="/login" class="px-5 py-2.5 border border-adzkia-dark/20 text-adzkia-dark font-bold rounded-full hover:bg-gray-50 transition-all text-[14px]">Masuk</a>
        <a href="/register" class="px-5 py-2.5 bg-adzkia-dark text-white font-bold rounded-full hover:bg-opacity-90 transition-all shadow-lg text-[14px]">Daftar</a>
    </div>
</nav>

<script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('navbarData', () => ({
            activeTab: 'beranda',

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
                
                // Urutan section dari yang paling bawah di halaman sampai paling atas
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