@extends('layouts.app')

@section('title', 'SPMB Universitas Adzkia')

@section('content')
<!-- MENGAMBIL DATA SETTING GLOBAL DARI DATABASE -->
@php
    $globalSetting = \App\Models\Setting::firstOrCreate(['id' => 1]);
@endphp

<section class="px-16 py-8 bg-adzkia-bg" x-data="{ 
    activeSlide: 0, 
    slides: [
        { image: 'https://images.unsplash.com/photo-1541339907198-e08756dedf3f?q=80&w=1280&h=720&auto=format&fit=crop', title: 'Selamat Datang di Universitas Adzkia', subtitle: 'Kampus Karakter, Mencetak Generasi Unggul dan Berakhlak Mulia' },
        { image: 'https://images.unsplash.com/photo-1523050335102-c6744729ea24?q=80&w=1280&h=720&auto=format&fit=crop', title: 'Pendaftaran Mahasiswa Baru {{ $globalSetting->tahun_akademik ?? '2024/2025' }}', subtitle: 'Dapatkan Beasiswa Adzkia Unggul hingga 100% untuk Siswa Berprestasi' },
        { image: 'https://images.unsplash.com/photo-1562774053-701939374585?q=80&w=1280&h=720&auto=format&fit=crop', title: 'Fasilitas Modern & Terpadu', subtitle: 'Lingkungan belajar yang nyaman didukung dengan laboratorium berstandar industri' }
    ],
    next() { this.activeSlide = (this.activeSlide + 1) % this.slides.length },
    prev() { this.activeSlide = (this.activeSlide - 1 + this.slides.length) % this.slides.length },
    init() { setInterval(() => this.next(), 6000) }
}">
    <div class="max-w-7xl mx-auto relative group">
        <div class="relative w-full aspect-[21/9] rounded-[2.5rem] overflow-hidden shadow-2xl border-4 border-white">
            <template x-for="(slide, index) in slides" :key="index">
                <div x-show="activeSlide === index" 
                     x-transition:enter="transition ease-out duration-700"
                     x-transition:enter-start="opacity-0 transform scale-105"
                     x-transition:enter-end="opacity-100 transform scale-100"
                     class="absolute inset-0 w-full h-full">
                    
                    <img :src="slide.image" :alt="slide.title" class="w-full h-full object-cover">
                    <div class="absolute inset-0 bg-gradient-to-t from-adzkia-blue via-adzkia-blue/40 to-transparent opacity-90"></div>
                    
                    <div class="absolute bottom-16 left-16 right-16 text-white translate-y-0 transition-all duration-700">
                        <h2 class="text-5xl font-black mb-4 leading-tight tracking-tight" x-text="slide.title"></h2>
                        <p class="text-xl font-medium text-gray-200 max-w-2xl" x-text="slide.subtitle"></p>
                    </div>
                </div>
            </template>
        </div>

        <button @click="prev()" class="absolute left-6 top-1/2 -translate-y-1/2 w-14 h-14 bg-white/20 backdrop-blur-md hover:bg-adzkia-red text-white hover:shadow-lg rounded-full flex items-center justify-center transition-all opacity-0 group-hover:opacity-100">
            <i data-feather="chevron-left" class="w-8 h-8"></i>
        </button>
        <button @click="next()" class="absolute right-6 top-1/2 -translate-y-1/2 w-14 h-14 bg-white/20 backdrop-blur-md hover:bg-adzkia-red text-white hover:shadow-lg rounded-full flex items-center justify-center transition-all opacity-0 group-hover:opacity-100">
            <i data-feather="chevron-right" class="w-8 h-8"></i>
        </button>

        <div class="absolute bottom-8 left-1/2 -translate-x-1/2 flex gap-3">
            <template x-for="(slide, index) in slides" :key="index">
                <button @click="activeSlide = index" 
                        :class="activeSlide === index ? 'bg-adzkia-red w-10' : 'bg-white/60 w-3'"
                        class="h-3 rounded-full transition-all duration-300"></button>
            </template>
        </div>
    </div>
</section>

<main class="px-16 py-12 flex items-center justify-between bg-adzkia-bg">
    <div class="w-1/2 pr-12">
        <div class="inline-block px-4 py-1.5 bg-adzkia-badge-bg text-adzkia-badge-txt text-[13px] font-bold rounded-full tracking-wide mb-6">
            TAHUN AKADEMIK {{ $globalSetting->tahun_akademik ?? '2026/2027' }}
        </div>
        <h1 class="text-[4.5rem] font-extrabold leading-[1.05] tracking-tight mb-6">
            <span class="text-adzkia-blue block">Penerimaan</span>
            <span class="text-adzkia-muted block">Mahasiswa</span>
            <span class="text-adzkia-muted block">Baru</span>
        </h1>
        <p class="text-gray-500 text-[16px] leading-relaxed max-w-[450px] mb-10 font-medium">
            Wujudkan impian masa depanmu bersama institusi pendidikan yang mengedepankan inovasi, riset, dan karakter unggul.
        </p>
        <div class="flex items-center gap-5">
            <a href="/register" class="px-8 py-4 bg-adzkia-red text-white text-[15px] font-bold rounded-full hover:bg-red-700 transition-all shadow-xl shadow-red-600/20 active:scale-95">
                Daftar Sekarang
            </a>
            <a href="{{ $globalSetting->brosur_path ? asset('uploads/docs/' . $globalSetting->brosur_path) : '#' }}" target="_blank" class="px-8 py-4 text-adzkia-blue text-[15px] font-bold rounded-full flex items-center gap-2 hover:bg-adzkia-badge-bg transition-colors">
                Unduh Brosur 
                <i data-feather="arrow-right" class="w-4 h-4"></i>
            </a>
        </div>
    </div>
    <div class="w-1/2 relative flex justify-end">
        <img src="{{ asset('images/gedung-adzkia.png') }}" 
             class="w-[85%] h-[580px] object-cover rounded-[2.5rem] shadow-xl border-4 border-white" alt="Kampus">
        
        <div class="absolute bottom-10 left-4 bg-white p-4 rounded-2xl shadow-xl flex items-center gap-4 border border-gray-100 pr-8">
            <div class="w-12 h-12 bg-yellow-100 rounded-full flex items-center justify-center shrink-0">
                <i data-feather="award" class="text-yellow-600 w-6 h-6"></i>
            </div>
            <div>
                <p class="text-[12px] text-gray-400 font-bold uppercase tracking-wide mb-0.5">Akreditasi</p>
                <p class="text-xl font-extrabold text-adzkia-blue leading-none">{{ $globalSetting->akreditasi ?? 'B' }}</p>
            </div>
        </div>
    </div>
</main>

<section class="px-16 py-24 bg-white flex items-center justify-between border-t border-gray-50" x-data="{ openVideo: false }">
    <div class="w-5/12 pr-12">
        <h2 class="text-[2.2rem] font-extrabold text-adzkia-blue leading-tight mb-5">
            Eksplorasi Kampus Kami
        </h2>
        <p class="text-gray-500 text-[15px] leading-relaxed mb-8">
            Universitas Adzkia Padang adalah hasil konversi STKIP Adzkia Padang menjadi Universitas yang pada mulanya bernama Sekolah Tinggi Keguruan dan Ilmu Pendidikan (STKIP) Adzkia. Universitas Adzkia Padang terdiri dari 18 Program Studi. Universitas Adzkia terakreditasi Institusi dari BAN-PT.
        </p>
        <div class="border-l-[4px] border-adzkia-red pl-5 py-1">
            <p class="text-[14px] italic text-gray-600 font-medium leading-relaxed">
                "Membangun masa depan melalui pendidikan berbasis karakter dan teknologi global."
            </p>
        </div>
    </div>

    <div class="w-7/12 flex justify-end">
        <div @click="openVideo = true" class="relative w-full max-w-[650px] h-[360px] rounded-[2rem] overflow-hidden shadow-2xl group cursor-pointer border-4 border-white">
            <img src="https://i.ytimg.com/vi/{{ $globalSetting->video_profil ?? 'q-r5HNQrCG0' }}/maxresdefault.jpg" 
                 class="w-full h-full object-cover transition duration-700 group-hover:scale-105" 
                 alt="Video Profil Adzkia">
            <div class="absolute inset-0 bg-adzkia-blue/20 group-hover:bg-adzkia-blue/40 transition duration-500 flex items-center justify-center">
                <div class="w-16 h-16 bg-white rounded-full flex items-center justify-center shadow-xl group-hover:scale-110 transition-transform duration-300">
                    <div class="w-0 h-0 border-t-[8px] border-t-transparent border-l-[14px] border-l-adzkia-red border-b-[8px] border-b-transparent ml-1"></div>
                </div>
            </div>
        </div>
    </div>

    <div x-show="openVideo" x-cloak class="fixed inset-0 z-[100] flex items-center justify-center bg-black/80 p-6" @click.away="openVideo = false">
        <div class="relative w-full max-w-4xl md:aspect-video bg-black rounded-2xl overflow-hidden shadow-2xl">
            <button @click="openVideo = false" class="absolute top-4 right-4 z-10 p-2 bg-white/20 hover:bg-adzkia-red transition-colors rounded-full text-white">
                <i data-feather="x"></i>
            </button>
            <iframe class="w-full h-full" src="https://www.youtube.com/embed/{{ $globalSetting->video_profil ?? 'q-r5HNQrCG0' }}?" allow="fullscreen" allowfullscreen></iframe>
        </div>
    </div>
</section>

<section id="prodi" class="px-16 py-20 bg-adzkia-bg">
    <div class="flex justify-between items-end mb-12">
        <div>
            <h2 class="text-[2.2rem] font-extrabold text-adzkia-blue mb-2">Program Studi</h2>
            <p class="text-gray-500 text-[15px] font-medium">Pilih disiplin ilmu yang sesuai dengan minat dan bakatmu.</p>
        </div>
        <a href="/program-studi" class="text-[14px] font-extrabold text-adzkia-red hover:text-red-700 transition-colors">Lihat Semua &rarr;</a>
    </div>

    <div class="grid md:grid-cols-3 gap-6 mb-12">
        @forelse($prodis as $prodi)
            <a href="/program-studi" class="p-8 bg-white border border-gray-100 rounded-[2rem] shadow-sm hover:shadow-xl hover:-translate-y-1 transition-all duration-300 cursor-pointer group flex flex-col justify-between min-h-[260px]">
                <div>
                    <div class="flex justify-between items-start mb-6">
                        <div class="w-12 h-12 bg-adzkia-badge-bg rounded-[14px] flex items-center justify-center group-hover:bg-adzkia-blue transition-colors duration-300">
                            <i data-feather="{{ $prodi->icon ?? 'book-open' }}" class="w-5 h-5 text-adzkia-blue group-hover:text-white transition-colors"></i>                        
                        </div>
                        
                        <div class="px-3 py-1.5 bg-adzkia-blue text-white rounded-lg text-[10px] font-extrabold uppercase tracking-widest flex items-center gap-1.5">
                            <i data-feather="award" class="w-3 h-3"></i>
                            <span>{{ $prodi->akreditasi ?? 'B' }}</span>
                        </div>
                    </div>
                    
                    <div class="text-[12px] font-extrabold text-gray-400 mb-1">{{ $prodi->jenjang ?? 'S1' }}</div>
                    <h3 class="text-xl font-extrabold text-adzkia-blue mb-3 group-hover:text-adzkia-red transition-colors">{{ $prodi->nama_prodi }}</h3>
                    <p class="text-gray-500 text-[13px] leading-relaxed mb-6 font-medium line-clamp-2">{{ $prodi->deskripsi ?? 'Program studi unggulan yang siap mencetak generasi profesional.' }}</p>
                </div>
                
                <p class="text-[13px] font-extrabold text-adzkia-red flex items-center gap-2 group-hover:gap-3 transition-all">
                    Detail Prodi <i data-feather="arrow-right" class="w-4 h-4"></i>
                </p>
            </a>
        @empty
            <div class="col-span-3 text-center py-8 text-gray-500 font-bold">Data Program Studi belum ditambahkan.</div>
        @endforelse
    </div>
</section>

<!-- BANNER REKOMENDASI JURUSAN -->
<section class="w-full py-12 px-6 lg:px-16 max-w-7xl mx-auto">
    <div class="bg-gradient-to-r from-adzkia-blue to-blue-600 rounded-[3rem] p-10 lg:p-14 text-white relative overflow-hidden shadow-2xl flex flex-col md:flex-row items-center justify-between gap-8">
        
        <div class="absolute top-0 right-0 w-64 h-64 bg-white opacity-10 rounded-full -translate-y-1/2 translate-x-1/4 blur-2xl"></div>
        <div class="absolute bottom-0 left-10 w-40 h-40 bg-blue-300 opacity-20 rounded-full translate-y-1/2 blur-xl"></div>

        <div class="relative z-10 max-w-2xl text-center md:text-left">
            <span class="inline-block px-4 py-1.5 bg-white/20 backdrop-blur-md text-white rounded-xl text-[11px] font-black uppercase tracking-widest mb-4 border border-white/30">
                Fitur AI Interaktif
            </span>
            <h2 class="text-3xl lg:text-4xl font-black mb-4 leading-tight">
                Bingung Pilih Program Studi?
            </h2>
            <p class="text-blue-100 text-[15px] font-medium leading-relaxed mb-8">
                Jawab beberapa pertanyaan singkat mengenai minat dan bakatmu, dan biarkan sistem kami merekomendasikan program studi yang paling cocok untuk masa depanmu di Universitas Adzkia!
            </p>
            
            <a href="/rekomendasi/mulai" class="inline-flex items-center gap-3 px-8 py-4 bg-white text-adzkia-blue rounded-2xl font-extrabold text-[14px] hover:bg-gray-50 transition-all shadow-xl active:scale-95 group">
                Mulai Tes Minat Bakat
                <i data-feather="arrow-right" class="w-4 h-4 group-hover:translate-x-1 transition-transform"></i>
            </a>
        </div>

        <div class="relative z-10 hidden md:block">
            <div class="w-48 h-48 bg-white/10 backdrop-blur-md rounded-full border border-white/20 flex items-center justify-center shadow-inner">
                <i data-feather="compass" class="w-20 h-20 text-white opacity-90"></i>
            </div>
        </div>

    </div>
</section>

<section id="fasilitas" class="px-16 py-24 bg-adzkia-blue text-white">
    <div class="text-center max-w-2xl mx-auto mb-16">
        <h2 class="text-[2.2rem] font-extrabold mb-4">Fasilitas Standar Internasional</h2>
        <p class="text-blue-100 text-[15px] font-medium leading-relaxed">
            Kami menyediakan lingkungan belajar yang nyaman dan mendukung kreativitas serta inovasi mahasiswa dengan fasilitas berstandar global.
        </p>
    </div>
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="relative h-[260px] rounded-[2rem] overflow-hidden group cursor-pointer border-2 border-white/10 hover:border-white/30 transition-colors">
            <img src="https://images.unsplash.com/photo-1562774053-701939374585?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80" 
                 class="w-full h-full object-cover transition duration-700 group-hover:scale-110" alt="Laboratorium">
            <div class="absolute inset-0 bg-gradient-to-t from-adzkia-blue via-adzkia-blue/40 to-transparent opacity-90"></div>
            <div class="absolute bottom-8 left-8">
                <h3 class="text-[1.3rem] font-extrabold text-white group-hover:text-red-300 transition-colors">Laboratorium Terpadu</h3>
            </div>
        </div>
        <div class="relative h-[260px] rounded-[2rem] overflow-hidden group cursor-pointer border-2 border-white/10 hover:border-white/30 transition-colors">
            <img src="https://images.unsplash.com/photo-1541339907198-e08756dedf3f?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80" 
                 class="w-full h-full object-cover transition duration-700 group-hover:scale-110" alt="Perpustakaan">
            <div class="absolute inset-0 bg-gradient-to-t from-adzkia-blue via-adzkia-blue/40 to-transparent opacity-90"></div>
            <div class="absolute bottom-8 left-8">
                <h3 class="text-[1.3rem] font-extrabold text-white group-hover:text-red-300 transition-colors">Perpustakaan Digital</h3>
            </div>
        </div>
        <div class="relative h-[260px] rounded-[2rem] overflow-hidden group cursor-pointer border-2 border-white/10 hover:border-white/30 transition-colors">
            <img src="https://images.unsplash.com/photo-1497366216548-37526070297c?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80" 
                 class="w-full h-full object-cover transition duration-700 group-hover:scale-110" alt="Coworking Space">
            <div class="absolute inset-0 bg-gradient-to-t from-adzkia-blue via-adzkia-blue/40 to-transparent opacity-90"></div>
            <div class="absolute bottom-8 left-8">
                <h3 class="text-[1.3rem] font-extrabold text-white group-hover:text-red-300 transition-colors">Area Diskusi & Lounge</h3>
            </div>
        </div>
    </div>
</section>

<section id="jalur-pendaftaran" class="px-16 py-24 bg-white">
    <div class="grid grid-cols-1 md:grid-cols-3 gap-x-12 gap-y-16 max-w-6xl mx-auto">
        <div>
            <div class="w-12 h-12 bg-adzkia-badge-bg text-adzkia-blue rounded-full flex items-center justify-center font-extrabold text-lg mb-5">1</div>
            <h3 class="text-lg font-extrabold text-adzkia-blue mb-2">Tahun Akademik</h3>
            <p class="text-gray-500 text-[14px] font-medium leading-relaxed">Penerapan kurikulum berbasis industri dan berstandar internasional untuk mencetak lulusan siap kerja.</p>
        </div>
        <div>
            <div class="w-12 h-12 bg-adzkia-badge-bg text-adzkia-blue rounded-full flex items-center justify-center font-extrabold text-lg mb-5">2</div>
            <h3 class="text-lg font-extrabold text-adzkia-blue mb-2">Dosen Profesional</h3>
            <p class="text-gray-500 text-[14px] font-medium leading-relaxed">Didampingi oleh tenaga pendidik berpengalaman dari kalangan praktisi dan akademisi unggul.</p>
        </div>
        <div>
            <div class="w-12 h-12 bg-adzkia-badge-bg text-adzkia-blue rounded-full flex items-center justify-center font-extrabold text-lg mb-5">3</div>
            <h3 class="text-lg font-extrabold text-adzkia-blue mb-2">Fasilitas Modern</h3>
            <p class="text-gray-500 text-[14px] font-medium leading-relaxed">Infrastruktur dan laboratorium mutakhir yang mendukung riset serta inovasi mahasiswa.</p>
        </div>
        <div>
            <div class="w-12 h-12 bg-adzkia-badge-bg text-adzkia-blue rounded-full flex items-center justify-center font-extrabold text-lg mb-5">4</div>
            <h3 class="text-lg font-extrabold text-adzkia-blue mb-2">Lulusan Berkualitas</h3>
            <p class="text-gray-500 text-[14px] font-medium leading-relaxed">Jaringan alumni yang kuat dan tersebar di berbagai instansi pemerintahan dan perusahaan swasta.</p>
        </div>
        <div>
            <div class="w-12 h-12 bg-adzkia-badge-bg text-adzkia-blue rounded-full flex items-center justify-center font-extrabold text-lg mb-5">5</div>
            <h3 class="text-lg font-extrabold text-adzkia-blue mb-2">Program Beasiswa</h3>
            <p class="text-gray-500 text-[14px] font-medium leading-relaxed">Berbagai macam jalur beasiswa yang tersedia untuk mendukung mahasiswa berprestasi.</p>
        </div>
        <div>
            <div class="w-12 h-12 bg-adzkia-badge-bg text-adzkia-blue rounded-full flex items-center justify-center font-extrabold text-lg mb-5">6</div>
            <h3 class="text-lg font-extrabold text-adzkia-blue mb-2">Lingkungan Kampus Asri</h3>
            <p class="text-gray-500 text-[14px] font-medium leading-relaxed">Suasana kampus yang hijau, bersih, dan aman memberikan kenyamanan optimal saat belajar.</p>
        </div>
    </div>
</section>

<section class="px-16 py-20 bg-adzkia-bg relative z-30">
    <div x-data="{ 
            mode: 'reguler',
            dropdownOpen: false,
            selectedPath: null,
            selectedPathDesc: '',
            searchQuery: '',
            khususPaths: [
                { group: 'Beasiswa', paths: [ { name: 'Beasiswa Adzkia Unggul (BAU)', desc: 'Beasiswa penuh bagi calon mahasiswa berprestasi akademik dan non-akademik tingkat nasional.' }, { name: 'Beasiswa PMDK', desc: 'Penelusuran Minat dan Kemampuan bagi siswa berprestasi di sekolah mitra.' }, { name: 'Beasiswa Prestasi', desc: 'Program khusus bagi penghafal Al-Quran atau juara kompetisi nasional.' }, { name: 'Beasiswa KIP-K', desc: 'Dukungan biaya pendidikan dari pemerintah bagi mahasiswa dari keluarga kurang mampu.' } ] },
                { group: 'Rekognisi Pembelajaran Lampau (RPL)', paths: [ { name: 'RPL Afirmasi YASB', desc: 'Jalur khusus untuk alumni yayasan mitra dengan konversi pengalaman kerja.' }, { name: 'RPL Afirmasi JSIT', desc: 'Khusus bagi guru/pegawai di bawah naungan jaringan sekolah JSIT.' }, { name: 'RPL Kelas Khusus', desc: 'Konversi SKS bagi praktisi profesional dengan pengalaman minimal 3 tahun.' } ] }
            ],
            get filteredPaths() {
                if (this.searchQuery === '') return this.khususPaths;
                const query = this.searchQuery.toLowerCase();
                return this.khususPaths.map(group => {
                    return { ...group, paths: group.paths.filter(p => p.name.toLowerCase().includes(query) || p.desc.toLowerCase().includes(query)) };
                }).filter(group => group.paths.length > 0);
            }
        }" 
        class="bg-adzkia-blue rounded-[3rem] p-16 shadow-2xl relative"> 
        
        <h2 class="text-4xl font-extrabold mb-8 text-white tracking-tight">Pilih Jalur Pendaftaranmu</h2>
        
        <div class="flex p-1.5 bg-white/10 backdrop-blur rounded-2xl w-fit mb-12 relative z-20">
            <button @click="mode = 'reguler'; dropdownOpen = false" :class="mode === 'reguler' ? 'bg-white text-adzkia-blue shadow-lg' : 'text-white hover:bg-white/10'" class="px-8 py-3 font-bold rounded-xl transition-all">
                REGULER
            </button>
            <button @click="mode = 'khusus'" :class="mode === 'khusus' ? 'bg-white text-adzkia-blue shadow-lg' : 'text-white hover:bg-white/10'" class="px-8 py-3 font-bold rounded-xl transition-all flex items-center gap-2">
                KHUSUS <i data-feather="star" class="w-4 h-4"></i>
            </button>
        </div>

        <div class="relative min-h-[340px]">
            <div x-show="mode === 'reguler'" 
                 x-transition:enter="transition ease-out duration-300 delay-100" 
                 x-transition:enter-start="opacity-0 translate-y-4" 
                 x-transition:enter-end="opacity-100 translate-y-0" 
                 x-transition:leave="transition ease-in duration-200" 
                 x-transition:leave-start="opacity-100 translate-y-0" 
                 x-transition:leave-end="opacity-0 -translate-y-4"
                 class="grid md:grid-cols-2 gap-12 items-center text-white absolute w-full top-0 left-0">
                <div class="space-y-6">
                    <h3 class="text-2xl font-extrabold">Jalur Mandiri Reguler</h3>
                    <p class="text-blue-100 leading-relaxed font-medium">Terbuka untuk lulusan SMA/SMK sederajat melalui seleksi berbasis komputer. Pendaftaran sepenuhnya dilakukan secara daring.</p>
                    <a href="/register" class="inline-block px-10 py-4 bg-adzkia-red text-white font-extrabold rounded-2xl hover:bg-red-700 hover:scale-105 active:scale-95 transition-all shadow-xl">Daftar Sekarang</a>
                </div>
                <div class="bg-white/10 p-8 rounded-2xl border border-white/20">
                    <p class="text-xs font-bold uppercase tracking-widest text-blue-200 mb-4">Gelombang Pendaftaran</p>
                    <div class="space-y-3 font-medium">
                        
                        <div class="flex justify-between border-b border-white/10 pb-3">
                            <span>Gelombang 1</span>
                            <span class="font-bold text-white bg-white/20 px-3 py-1 rounded-md text-xs">
                                {{ $globalSetting->gelombang_1_buka && $globalSetting->gelombang_1_tutup ? \Carbon\Carbon::parse($globalSetting->gelombang_1_buka)->translatedFormat('M') . ' - ' . \Carbon\Carbon::parse($globalSetting->gelombang_1_tutup)->translatedFormat('M') : 'Segera Dibuka' }}
                            </span>
                        </div>
                        
                        <div class="flex justify-between border-b border-white/10 pb-3">
                            <span>Gelombang 2</span>
                            <span class="font-bold text-white bg-white/20 px-3 py-1 rounded-md text-xs">
                                {{ $globalSetting->gelombang_2_buka && $globalSetting->gelombang_2_tutup ? \Carbon\Carbon::parse($globalSetting->gelombang_2_buka)->translatedFormat('M') . ' - ' . \Carbon\Carbon::parse($globalSetting->gelombang_2_tutup)->translatedFormat('M') : 'Segera Dibuka' }}
                            </span>
                        </div>
                        
                        <div class="flex justify-between">
                            <span>Gelombang 3</span>
                            <span class="font-bold text-white bg-white/20 px-3 py-1 rounded-md text-xs">
                                {{ $globalSetting->gelombang_3_buka && $globalSetting->gelombang_3_tutup ? \Carbon\Carbon::parse($globalSetting->gelombang_3_buka)->translatedFormat('M') . ' - ' . \Carbon\Carbon::parse($globalSetting->gelombang_3_tutup)->translatedFormat('M') : 'Segera Dibuka' }}
                            </span>
                        </div>
                        
                    </div>
                </div>
            </div>

            <div x-show="mode === 'khusus'" 
                 x-transition:enter="transition ease-out duration-300 delay-100" 
                 x-transition:enter-start="opacity-0 translate-y-4" 
                 x-transition:enter-end="opacity-100 translate-y-0"
                 x-transition:leave="transition ease-in duration-200" 
                 x-transition:leave-start="opacity-100 translate-y-0" 
                 x-transition:leave-end="opacity-0 -translate-y-4"
                 class="absolute w-full top-0 left-0" style="display: none;">
                
                <div class="grid md:grid-cols-2 gap-12 items-start">
                    <div class="space-y-4 relative z-50">
                        <label class="block text-sm font-bold uppercase tracking-wider text-blue-200">Cari & Pilih Program Khusus</label>
                        <div class="relative">
                            <button @click="dropdownOpen = !dropdownOpen" @click.outside="dropdownOpen = false" class="w-full bg-white text-adzkia-blue py-4 px-6 rounded-2xl flex justify-between items-center text-left shadow-xl">
                                <span class="font-bold" x-text="selectedPath ? selectedPath : 'Pilih jalur pendaftaran...'"></span>
                                <i data-feather="chevron-down" class="transition-transform" :class="dropdownOpen ? 'rotate-180' : ''"></i>
                            </button>
                            
                            <div x-show="dropdownOpen" 
                                 x-transition.opacity.duration.200ms
                                 style="display: none;"
                                 class="absolute top-full left-0 right-0 mt-3 bg-white text-adzkia-blue rounded-2xl shadow-2xl border border-gray-100 max-h-[260px] overflow-y-auto z-[60] custom-scrollbar">
                                
                                <div class="p-4 border-b border-gray-100 sticky top-0 bg-white z-10">
                                    <div class="relative">
                                        <i data-feather="search" class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 w-4 h-4"></i>
                                        <input x-model="searchQuery" type="text" placeholder="Cari jalur..." class="w-full pl-10 pr-4 py-3 bg-gray-50 rounded-xl border-none focus:ring-2 focus:ring-adzkia-badge-bg text-sm outline-none font-medium">
                                    </div>
                                </div>

                                <div class="pb-2">
                                    <template x-for="(group, index) in filteredPaths" :key="index">
                                        <div :class="index > 0 ? 'border-t border-gray-100 mt-2 pt-2' : ''">
                                            <p class="text-[10px] font-extrabold text-adzkia-red uppercase tracking-widest px-5 py-2" x-text="group.group"></p>
                                            <template x-for="path in group.paths" :key="path.name">
                                                <button @click="selectedPath = path.name; selectedPathDesc = path.desc; dropdownOpen = false" class="w-full text-left px-5 py-3 hover:bg-adzkia-badge-bg transition-colors flex flex-col group/btn">
                                                    <span class="font-bold text-[14px] text-adzkia-blue group-hover/btn:text-adzkia-red transition-colors" x-text="path.name"></span>
                                                    <span class="text-[12px] text-gray-500 line-clamp-1 mt-0.5 font-medium" x-text="path.desc"></span>
                                                </button>
                                            </template>
                                        </div>
                                    </template>
                                    <div x-show="filteredPaths.length === 0" class="p-6 text-center text-sm text-gray-400 font-medium">
                                        Jalur tidak ditemukan.
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="space-y-6 relative z-10">
                        <div class="bg-white/10 p-8 rounded-2xl min-h-[140px] border border-white/20 flex flex-col justify-center transition-all duration-300">
                            <p class="text-white leading-relaxed font-medium" :class="!selectedPath ? 'italic text-center text-blue-200' : 'text-left'" x-text="selectedPathDesc ? selectedPathDesc : 'Silakan pilih jalur di samping untuk melihat detail persyaratan.'"></p>
                        </div>
                        <button class="w-full py-4 bg-adzkia-red text-white font-extrabold rounded-2xl shadow-xl hover:bg-red-700 hover:scale-105 active:scale-95 transition-all disabled:opacity-50 disabled:hover:scale-100 disabled:cursor-not-allowed" :disabled="!selectedPath">
                            Daftar Program Khusus
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<section id="berita" class="w-full bg-white relative z-30 border-t border-b border-gray-100 py-20">
    <div class="max-w-[90rem] mx-auto px-6 lg:px-16">
        <div class="flex flex-col md:flex-row justify-between items-start md:items-end mb-12 gap-4">
            <div>
                <h2 class="text-3xl lg:text-4xl font-extrabold text-adzkia-blue tracking-tight">Berita Terkini</h2>
                <p class="text-gray-500 mt-2 font-medium">Kabar terbaru seputar prestasi dan kegiatan kampus.</p>
            </div>
            <a href="/berita" class="px-8 py-3 border-2 border-adzkia-blue text-adzkia-blue font-bold rounded-full hover:bg-adzkia-blue hover:text-white transition-all">Lihat Semua</a>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            @forelse($beritas as $item)
                <a href="/berita/{{ $item->slug }}" class="group bg-white rounded-3xl overflow-hidden border border-gray-100 shadow-sm hover:shadow-xl transition-all cursor-pointer flex flex-col">
                    <div class="relative w-full h-48 overflow-hidden bg-gray-100">
                        <div class="absolute top-4 left-4 bg-white/90 backdrop-blur text-adzkia-blue text-[10px] font-extrabold px-3 py-1.5 rounded-full uppercase tracking-widest z-10">
                            {{ $item->kategori ?? 'Informasi' }}
                        </div>
                        <img src="{{ $item->thumbnail ? asset('uploads/berita/' . $item->thumbnail) : 'https://images.unsplash.com/photo-1523050854058-8df90110c9f1?q=80&w=500' }}" 
                             alt="{{ $item->judul }}" 
                             class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                    </div>
                    <div class="p-6 flex flex-col flex-grow">
                        <p class="text-[11px] font-extrabold text-gray-400 uppercase tracking-widest mb-3">
                            {{ \Carbon\Carbon::parse($item->created_at)->translatedFormat('d F Y') }}
                        </p>
                        <h3 class="text-lg font-extrabold text-adzkia-blue group-hover:text-adzkia-red transition-colors mb-4 line-clamp-2">
                            {{ $item->judul }}
                        </h3>
                        <p class="text-[13px] text-gray-500 font-medium line-clamp-2 mb-4">
                            {{ $item->ringkasan }}
                        </p>
                        <div class="mt-auto pt-4">
                            <p class="text-[12px] font-extrabold text-adzkia-red flex items-center gap-2 group-hover:gap-3 transition-all">
                                Baca <i data-feather="arrow-right" class="w-3 h-3"></i>
                            </p>
                        </div>
                    </div>
                </a>
            @empty
                <div class="col-span-1 md:col-span-3 text-center py-8 text-gray-500 font-bold">Belum ada berita terbaru.</div>
            @endforelse
        </div>
    </div>
</section>

<section id="faq" class="px-16 py-20 bg-adzkia-bg border-b border-gray-100">
    <div class="max-w-[800px] mx-auto">
        <h2 class="text-3xl font-extrabold text-adzkia-blue text-center mb-10">Pertanyaan Populer</h2>
        <div class="space-y-4">
            @forelse($faqs as $faq)
                <div x-data="{ open: false }" class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 cursor-pointer hover:border-adzkia-blue transition-colors" @click="open = !open">
                    <div class="flex justify-between items-center text-left">
                        <span class="font-extrabold text-adzkia-blue">{{ $faq->pertanyaan }}</span>
                        <i data-feather="chevron-down" class="text-adzkia-red transition-transform" :class="open ? 'rotate-180' : ''"></i>
                    </div>
                    <div x-show="open" x-collapse style="display:none;" class="mt-4 text-gray-500 font-medium text-sm leading-relaxed">
                        {{ $faq->jawaban }}
                    </div>
                </div>
            @empty
                <div class="text-center text-gray-500 font-bold py-4">Belum ada data FAQ yang ditambahkan admin.</div>
            @endforelse
        </div>
    </div>
</section>

<section id="kontak" class="px-16 py-20 bg-white">
    <div class="grid md:grid-cols-2 gap-16 items-center max-w-7xl mx-auto">
        <div class="space-y-12 pr-8">
            <div>
                <h2 class="text-4xl font-extrabold text-adzkia-blue tracking-tight mb-5">Hubungi Kami</h2>
                <p class="text-gray-500 font-medium leading-relaxed text-[15px]">
                    Tim admisi kami siap membantu menjawab pertanyaan Anda seputar proses pendaftaran, jadwal seleksi, hingga beasiswa. Jangan ragu untuk menghubungi kami melalui kontak di bawah ini.
                </p>
            </div>
            
            <div class="space-y-8">
                <div class="flex gap-6 items-start group">
                    <div class="w-12 h-12 bg-adzkia-badge-bg rounded-2xl flex items-center justify-center shrink-0 mt-1 group-hover:bg-adzkia-blue transition-colors duration-300">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-5 h-5 text-adzkia-blue group-hover:text-white transition-colors duration-300">
                            <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path>
                            <circle cx="12" cy="10" r="3"></circle>
                        </svg>
                    </div>
                    <div>
                        <h4 class="font-extrabold text-adzkia-blue text-lg mb-1 group-hover:text-adzkia-red transition-colors">Alamat Kampus</h4>
                        <p class="text-gray-500 text-[14px] font-medium leading-relaxed">
                            {!! $globalSetting->alamat ? nl2br(e($globalSetting->alamat)) : 'Jl. Raya Taratak Paneh No.7, Korong Gadang, Kec. Kuranji,<br>Kota Padang, Sumatera Barat 25147' !!}
                        </p>
                    </div>
                </div>

                <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $globalSetting->telepon ?? '6281234567890') }}" target="_blank" class="flex gap-6 items-start group cursor-pointer">
                    <div class="w-12 h-12 bg-adzkia-badge-bg rounded-2xl flex items-center justify-center shrink-0 mt-1 group-hover:bg-adzkia-blue transition-colors duration-300">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-5 h-5 text-adzkia-blue group-hover:text-white transition-colors duration-300">
                            <path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"></path>
                        </svg>
                    </div>
                    <div>
                        <h4 class="font-extrabold text-adzkia-blue text-lg mb-1 group-hover:text-adzkia-red transition-colors">Telepon / WhatsApp</h4>
                        <p class="text-gray-500 text-[14px] font-medium">{{ $globalSetting->telepon ?? '(0751) 482121 / +62 812-3456-7890' }}</p>
                    </div>
                </a>

                <a href="mailto:{{ $globalSetting->email ?? 'pmb@adzkia.ac.id' }}" class="flex gap-6 items-start group cursor-pointer">
                    <div class="w-12 h-12 bg-adzkia-badge-bg rounded-2xl flex items-center justify-center shrink-0 mt-1 group-hover:bg-adzkia-blue transition-colors duration-300">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-5 h-5 text-adzkia-blue group-hover:text-white transition-colors duration-300">
                            <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"></path>
                            <polyline points="22,6 12,13 2,6"></polyline>
                        </svg>
                    </div>
                    <div>
                        <h4 class="font-extrabold text-adzkia-blue text-lg mb-1 group-hover:text-adzkia-red transition-colors">Email Admisi</h4>
                        <p class="text-gray-500 text-[14px] font-medium">{{ $globalSetting->email ?? 'pmb@adzkia.ac.id' }}</p>
                    </div>
                </a>
            </div>
        </div>

        <div class="relative w-full h-[450px] rounded-[3rem] overflow-hidden shadow-2xl border-8 border-gray-50 group">
            <iframe 
                src="{{ $globalSetting->link_maps ?? 'https://maps.google.com/maps?q=Universitas+Adzkia+Padang&t=&z=15&ie=UTF8&iwloc=&output=embed' }}" 
                width="100%" 
                height="100%" 
                style="border:0;" 
                allowfullscreen="" 
                loading="lazy" 
                referrerpolicy="no-referrer-when-downgrade">
            </iframe>
        </div>
    </div>
</section>
@endsection