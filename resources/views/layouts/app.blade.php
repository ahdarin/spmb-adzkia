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
                        'adzkia-dark': '#0A1B3A',
                        'adzkia-muted': '#889CB5',
                        'adzkia-badge-bg': '#EEF4FF',
                        'adzkia-badge-txt': '#2D68F8',
                        'adzkia-bg': '#FAFBFC',
                    }
                }
            }
        }
    </script>

    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.store('kampus', {
                
                // --- STATE MODAL PRODI ---
                modalOpen: false,
                activeProgram: {},
                
                openModal(program) {
                    this.activeProgram = program;
                    this.modalOpen = true;
                    setTimeout(() => feather.replace(), 50); 
                },
                
                closeModal() {
                    this.modalOpen = false;
                },

                // --- DATA 1: PROGRAM STUDI ---
                programs: [
                    { id: 1, name: 'Informatika', level: 'S1', acc: 'Akreditasi Unggul', desc: 'Fokus pada rekayasa perangkat lunak, AI, dan keamanan siber masa depan.', price: 'Rp 8.500.000', icon: 'terminal' },
                    { id: 2, name: 'Sistem Informasi', level: 'S1', acc: 'Akreditasi Unggul', desc: 'Menjembatani bisnis dan teknologi melalui tata kelola data yang efisien.', price: 'Rp 8.000.000', icon: 'layout' },
                    { id: 3, name: 'Teknik Industri', level: 'S1', acc: 'Akreditasi A', desc: 'Optimalisasi sistem produksi dan manajemen rantai pasok global.', price: 'Rp 8.500.000', icon: 'settings' },
                    { id: 4, name: 'S1 DKV', level: 'S1', acc: 'Akreditasi Unggul', desc: 'Eksplorasi kreativitas visual dan komunikasi melalui teknologi desain mutakhir.', price: 'Rp 8.000.000', icon: 'pen-tool' },
                    { id: 5, name: 'Teknik Sipil', level: 'S1', acc: 'Akreditasi A', desc: 'Membangun infrastruktur masa depan yang berkelanjutan dan aman.', price: 'Rp 8.500.000', icon: 'map' },
                    { id: 6, name: 'PGSD', level: 'S1', acc: 'Akreditasi A', desc: 'Pendidikan Guru Sekolah Dasar dengan kurikulum inovatif berbasis karakter.', price: 'Rp 6.000.000', icon: 'users' },
                    { id: 7, name: 'Kewirausahaan', level: 'S1', acc: 'Akreditasi Unggul', desc: 'Melahirkan entrepreneur muda melalui inkubasi bisnis dan praktik nyata.', price: 'Rp 7.500.000', icon: 'briefcase' },
                    { id: 8, name: 'Pendidikan Fisika', level: 'S1', acc: 'Akreditasi A', desc: 'Memahami hukum alam semesta dan mengaplikasikannya dalam teknologi pembelajaran.', price: 'Rp 6.500.000', icon: 'zap' },
                    { id: 9, name: 'Pendidikan Bahasa Indonesia', level: 'S1', acc: 'Akreditasi A', desc: 'Studi mendalam sastra dan bahasa untuk kemajuan literasi bangsa.', price: 'Rp 6.000.000', icon: 'edit-3' },
                    { id: 10, name: 'Pendidikan Matematika', level: 'S1', acc: 'Akreditasi A', desc: 'Mencetak tenaga pendidik matematika yang logis, kritis, dan analitis.', price: 'Rp 6.500.000', icon: 'pie-chart' },
                    { id: 11, name: 'Agribisnis', level: 'S1', acc: 'Akreditasi A', desc: 'Integrasi teknologi pertanian dan strategi ekonomi berkelanjutan.', price: 'Rp 7.000.000', icon: 'sun' },
                    { id: 12, name: 'PG-PAUD', level: 'S1', acc: 'Akreditasi A', desc: 'Mempelajari psikologi dan metode pengajaran terbaik untuk anak usia dini.', price: 'Rp 6.000.000', icon: 'smile' },
                    { id: 13, name: 'Gizi', level: 'S1', acc: 'Akreditasi Unggul', desc: 'Studi kesehatan masyarakat melalui nutrisi dan manajemen pangan komprehensif.', price: 'Rp 7.500.000', icon: 'heart' },
                    { id: 14, name: 'Hukum Bisnis', level: 'S1', acc: 'Akreditasi Unggul', desc: 'Menganalisis regulasi komersial dan etika hukum dalam korporasi global.', price: 'Rp 7.500.000', icon: 'shield' },
                    { id: 15, name: 'Manajemen Ritel', level: 'S1', acc: 'Akreditasi A', desc: 'Mempelajari ekosistem perdagangan modern dan perilaku konsumen.', price: 'Rp 7.000.000', icon: 'shopping-cart' },
                    { id: 16, name: 'Pendidikan Khusus', level: 'S1', acc: 'Akreditasi Unggul', desc: 'Didedikasikan untuk pendidikan inklusif bagi individu berkebutuhan khusus.', price: 'Rp 6.000.000', icon: 'user-check' },
                    { id: 17, name: 'Pendidikan Profesi Guru', level: 'Profesi', acc: 'Akreditasi A', desc: 'Program pendidikan profesional untuk mencetak guru bersertifikat nasional.', price: 'Rp 5.500.000', icon: 'book-open' },
                    { id: 18, name: 'S2 Pendidikan Dasar', level: 'S2', acc: 'Akreditasi Unggul', desc: 'Tingkat lanjut dalam penelitian dan metodologi pengajaran sekolah dasar.', price: 'Rp 12.000.000', icon: 'award' },
                ],

                // --- DATA 2: BERITA TERKINI ---
                berita: [
                    { id: 1, isHighlight: true, title: 'Academic Curator Universitas Berhasil Meraih Peringkat 10 Besar Nasional', category: 'Akademik', date: '12 Oktober 2026', excerpt: 'Pencapaian luar biasa ini merupakan hasil kerja keras seluruh civitas akademika dalam meningkatkan kualitas riset, inovasi, dan pengajaran yang berstandar internasional.', image: 'https://images.unsplash.com/photo-1523240795612-9a054b0db644?auto=format&fit=crop&w=800&q=80' },
                    { id: 2, isHighlight: false, title: 'Inovasi Teknologi AI Mahasiswa Teknik di Pameran Global', category: 'Akademik', date: '10 Oktober 2026', excerpt: 'Mahasiswa fakultas teknik memperkenalkan solusi AI terbaru untuk efisiensi energi bangunan masa depan di ajang pameran.', image: 'https://images.unsplash.com/photo-1573164713988-8665fc963095?auto=format&fit=crop&w=600&q=80' },
                    { id: 3, isHighlight: false, title: 'Tim Debat Universitas Melaju ke Final Kompetisi Internasional', category: 'Prestasi', date: '08 Oktober 2026', excerpt: 'Prestasi gemilang kembali ditorehkan oleh tim debat bahasa Inggris yang berhasil menyisihkan ratusan peserta dari berbagai negara.', image: 'https://images.unsplash.com/photo-1528605248644-14dd04022da1?auto=format&fit=crop&w=600&q=80' },
                    { id: 4, isHighlight: false, title: 'Persiapan Pekan Kreativitas Mahasiswa 2026 Dimulai', category: 'Event', date: '05 Oktober 2026', excerpt: 'Berbagai rangkaian acara menarik telah disiapkan untuk menyambut festival kreativitas mahasiswa terbesar tahun ini.', image: 'https://images.unsplash.com/photo-1523580494863-6f3031224c94?auto=format&fit=crop&w=600&q=80' },
                    { id: 5, isHighlight: false, title: 'Hibah Riset Internasional Untuk Laboratorium Bioteknologi', category: 'Akademik', date: '03 Oktober 2026', excerpt: 'Penerimaan dana hibah riset senilai 2 Miliar Rupiah akan digunakan untuk mempercepat penelitian vaksin generasi terbaru.', image: 'https://images.unsplash.com/photo-1532094349884-543bc11b234d?auto=format&fit=crop&w=600&q=80' },
                    { id: 6, isHighlight: false, title: 'Seminar Nasional: Masa Depan Ekonomi Digital Indonesia', category: 'Akademik', date: '01 Oktober 2026', excerpt: 'Menghadirkan pembicara dari industri teknologi ternama untuk membahas peluang karir dan tren ekonomi digital.', image: 'https://images.unsplash.com/photo-1540317580384-e5d43616b9aa?auto=format&fit=crop&w=600&q=80' },
                    { id: 7, isHighlight: false, title: 'Program Pengabdian Masyarakat: Literasi Digital Desa', category: 'Event', date: '28 September 2026', excerpt: 'Relawan mahasiswa memberikan pelatihan penggunaan platform digital untuk pemasaran UMKM di wilayah pedesaan.', image: 'https://images.unsplash.com/photo-1552664730-d307ca884978?auto=format&fit=crop&w=600&q=80' },
                ]
            });
        });
    </script>
</head>
<body class="bg-adzkia-bg antialiased text-adzkia-dark">

    <x-navbar />

    @yield('content')

    <x-footer />

    <div x-data x-show="$store.kampus.modalOpen" class="fixed inset-0 z-[100] flex items-center justify-center px-4" style="display: none;">
        <div x-show="$store.kampus.modalOpen" x-transition.opacity @click="$store.kampus.closeModal()" class="absolute inset-0 bg-adzkia-dark/80 backdrop-blur-sm cursor-pointer"></div>
        
        <div x-show="$store.kampus.modalOpen" 
             x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 scale-95 translate-y-4" x-transition:enter-end="opacity-100 scale-100 translate-y-0" 
             x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100 scale-100 translate-y-0" x-transition:leave-end="opacity-0 scale-95 translate-y-4" 
             class="bg-white w-full max-w-lg rounded-3xl shadow-2xl relative z-10 overflow-hidden">
            <div class="p-10">
                <div class="flex justify-between items-start mb-6">
                    <div>
                        <h2 class="text-3xl font-extrabold text-adzkia-dark tracking-tight" x-text="$store.kampus.activeProgram.name"></h2>
                        <span class="mt-3 inline-block px-3 py-1 bg-adzkia-badge-bg text-adzkia-badge-txt text-[11px] font-extrabold rounded-full uppercase" x-text="$store.kampus.activeProgram.acc"></span>
                    </div>
                    <button @click="$store.kampus.closeModal()" class="p-2 hover:bg-gray-100 rounded-full transition-colors">
                        <i data-feather="x" class="text-gray-500 w-5 h-5"></i>
                    </button>
                </div>
                <div class="space-y-6">
                    <div>
                        <h4 class="text-xs font-extrabold uppercase tracking-widest text-gray-400 mb-2">Deskripsi Program</h4>
                        <p class="text-gray-600 font-medium leading-relaxed text-[15px]" x-text="$store.kampus.activeProgram.desc"></p>
                    </div>
                    <div class="bg-adzkia-bg p-6 rounded-2xl border border-gray-100">
                        <h4 class="text-xs font-extrabold uppercase tracking-widest text-gray-400 mb-2">Estimasi Biaya Kuliah</h4>
                        <div class="flex items-baseline gap-2">
                            <span class="text-sm font-medium text-gray-500">Mulai dari</span>
                            <span class="text-2xl font-black text-adzkia-dark" x-text="$store.kampus.activeProgram.price"></span>
                            <span class="text-xs text-gray-500">/semester</span>
                        </div>
                    </div>
                    <button class="w-full py-4 bg-adzkia-dark text-white font-extrabold rounded-full shadow-xl hover:scale-105 active:scale-95 transition-all">
                        Daftar Program Ini
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script>
        feather.replace();
    </script>
</body>
</html>