@extends('layouts.app')

@section('title', 'PMB Universitas Adzkia')

@section('content')

    <main class="px-16 py-12 flex items-center justify-between">
        <div class="w-1/2 pr-12">
            <div class="inline-block px-4 py-1.5 bg-adzkia-badge-bg text-adzkia-badge-txt text-[13px] font-bold rounded-full tracking-wide mb-6">
                TAHUN AKADEMIK 2024/2025
            </div>
            <h1 class="text-[4.5rem] font-extrabold leading-[1.05] tracking-tight mb-6">
                <span class="text-adzkia-dark block">Penerimaan</span>
                <span class="text-adzkia-muted block">Mahasiswa</span>
                <span class="text-adzkia-muted block">Baru</span>
            </h1>
            <p class="text-gray-500 text-[16px] leading-relaxed max-w-[450px] mb-10 font-medium">
                Wujudkan impian masa depanmu bersama institusi pendidikan yang mengedepankan inovasi, riset, dan karakter unggul.
            </p>
            <div class="flex items-center gap-5">
                <button class="px-8 py-4 bg-adzkia-dark text-white text-[15px] font-bold rounded-full hover:scale-105 transition-all shadow-xl shadow-adzkia-dark/20">
                    Daftar Sekarang
                </button>
                <button class="px-8 py-4 text-adzkia-dark text-[15px] font-bold rounded-full flex items-center gap-2 hover:bg-gray-100 transition-colors">
                    Unduh Brosur <i data-feather="arrow-right" class="w-4 h-4"></i>
                </button>
            </div>
        </div>
        <div class="w-1/2 relative flex justify-end">
            <img src="https://images.unsplash.com/photo-1541339907198-e08756dedf3f?ixlib=rb-4.0.3&auto=format&fit=crop&w=600&q=80" 
                 class="w-[85%] h-[580px] object-cover rounded-[2.5rem] shadow-xl" alt="Kampus">
            
            <div class="absolute bottom-10 left-4 bg-white p-4 rounded-2xl shadow-xl flex items-center gap-4 border border-gray-100 pr-8">
                <div class="w-12 h-12 bg-adzkia-badge-bg rounded-full flex items-center justify-center shrink-0">
                    <i data-feather="award" class="text-adzkia-badge-txt w-6 h-6"></i>
                </div>
                <div>
                    <p class="text-[12px] text-gray-400 font-bold uppercase tracking-wide mb-0.5">Akreditasi</p>
                    <p class="text-xl font-extrabold text-adzkia-dark leading-none">A</p>
                </div>
            </div>
        </div>
    </main>

    <section class="px-16 py-24 bg-white flex items-center justify-between">
        <div class="w-5/12 pr-12">
            <h2 class="text-[2.2rem] font-extrabold text-adzkia-dark leading-tight mb-5">
                Eksplorasi Kampus Kami
            </h2>
            <p class="text-gray-500 text-[15px] leading-relaxed mb-8">
                Lihat lebih dekat fasilitas, lingkungan belajar, dan kehidupan mahasiswa di Universitas Adzkia melalui video dokumenter singkat kami.
            </p>
            <div class="border-l-[4px] border-adzkia-dark pl-5 py-1">
                <p class="text-[14px] italic text-gray-600 font-medium leading-relaxed">
                    "Menjadi universitas terkemuka yang menghasilkan lulusan berkarakter, cerdas, dan siap bersaing di kancah global."
                </p>
            </div>
        </div>
        <div class="w-7/12 flex justify-end">
            <div class="relative w-full max-w-[650px] h-[360px] rounded-[2rem] overflow-hidden shadow-2xl group cursor-pointer">
                <img src="https://images.unsplash.com/photo-1541339907198-e08756dedf3f?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80" 
                     class="w-full h-full object-cover transition duration-700 group-hover:scale-105" 
                     alt="Video Kampus">
                <div class="absolute inset-0 bg-black/20 group-hover:bg-black/40 transition duration-500 flex items-center justify-center">
                    <div class="w-16 h-16 bg-white rounded-full flex items-center justify-center shadow-xl group-hover:scale-110 transition-transform duration-300">
                        <div class="w-0 h-0 border-t-[8px] border-t-transparent border-l-[14px] border-l-adzkia-dark border-b-[8px] border-b-transparent ml-1"></div>
                    </div>
                </div>
            </div>
        </div>
    </section>

<section id="prodi" x-data class="px-16 py-20 bg-adzkia-bg">
        <div class="flex justify-between items-end mb-12">
            <div>
                <h2 class="text-[2.2rem] font-extrabold text-adzkia-dark mb-2">Program Studi</h2>
                <p class="text-gray-500 text-[15px] font-medium">Pilih disiplin ilmu yang sesuai dengan minat dan bakatmu.</p>
            </div>
            <a href="/program-studi" class="text-[14px] font-extrabold text-adzkia-dark hover:text-adzkia-badge-txt transition-colors">Lihat Semua &rarr;</a>
        </div>

        <div class="grid md:grid-cols-3 gap-6 mb-12">
            <template x-for="prodi in $store.kampus.programs.slice(0, 3)" :key="prodi.id">
                
                <div @click="$store.kampus.openModal(prodi)" class="p-8 bg-white border border-gray-100 rounded-[2rem] shadow-sm hover:shadow-xl hover:-translate-y-1 transition-all duration-300 cursor-pointer group flex flex-col justify-between min-h-[260px]">
                    <div>
                        <div class="flex justify-between items-start mb-6">
                            <div class="w-12 h-12 bg-adzkia-badge-bg rounded-[14px] flex items-center justify-center group-hover:bg-adzkia-dark transition-colors duration-300"
                                 x-html="feather.icons[prodi.icon].toSvg({ class: 'w-5 h-5 text-adzkia-badge-txt group-hover:text-white transition-colors' })">
                            </div>
                            
                            <div class="px-3 py-1.5 bg-adzkia-dark text-white rounded-lg text-[10px] font-extrabold uppercase tracking-widest flex items-center gap-1.5">
                                <span x-html="feather.icons['award'].toSvg({ class: 'w-3 h-3' })"></span>
                                <span x-text="prodi.acc"></span>
                            </div>
                        </div>
                        
                        <div class="text-[12px] font-extrabold text-gray-400 mb-1" x-text="prodi.level"></div>
                        <h3 class="text-xl font-extrabold text-adzkia-dark mb-3" x-text="prodi.name"></h3>
                        <p class="text-gray-500 text-[13px] leading-relaxed mb-6 font-medium line-clamp-2" x-text="prodi.desc"></p>
                    </div>
                    
                    <p class="text-[13px] font-extrabold text-adzkia-dark flex items-center gap-2 group-hover:gap-3 transition-all">
                        Detail Prodi <span x-html="feather.icons['arrow-right'].toSvg({ class: 'w-4 h-4' })"></span>
                    </p>
                </div>

            </template>
        </div>

        <div class="bg-white rounded-[2rem] p-8 flex items-center justify-between border border-gray-100 shadow-sm relative overflow-hidden">
            <div class="absolute right-16 bottom-[-20px] text-[150px] font-black text-gray-50 leading-none pointer-events-none select-none z-0">?</div>
            <div class="w-[35%] relative z-10">
                <img src="https://images.unsplash.com/photo-1591991564021-0662a8573199?ixlib=rb-4.0.3&auto=format&fit=crop&w=500&q=80" 
                     class="rounded-3xl h-[200px] w-full object-cover shadow-md" alt="Ilustrasi Tes Bakat">
            </div>
            <div class="w-[65%] pl-12 relative z-10 flex flex-col items-start justify-center">
                <div class="px-3 py-1 bg-red-100 text-red-600 text-[10px] font-extrabold rounded-md mb-4 tracking-wider">IKUTI TES KAMI</div>
                <h3 class="text-3xl font-extrabold text-adzkia-dark mb-3">Bingung Pilih Jurusan?</h3>
                <p class="text-gray-500 text-[14px] mb-6 max-w-[85%] font-medium leading-relaxed">
                    Temukan program studi yang paling sesuai dengan kepribadian, minat, dan potensi karir masa depanmu.
                </p>
                <button class="px-8 py-3 bg-adzkia-dark text-white text-[14px] font-bold rounded-full hover:bg-opacity-90 transition-all shadow-lg shadow-adzkia-dark/20">
                    Mulai Tes Sekarang
                </button>
            </div>
        </div>
    </section>

    <section id="fasilitas" class="px-16 py-24 bg-adzkia-dark text-white">
        <div class="text-center max-w-2xl mx-auto mb-16">
            <h2 class="text-[2.2rem] font-extrabold mb-4">Fasilitas Standar Internasional</h2>
            <p class="text-gray-400 text-[15px] font-medium leading-relaxed">
                Kami menyediakan lingkungan belajar yang nyaman dan mendukung kreativitas serta inovasi mahasiswa dengan fasilitas berstandar global.
            </p>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="relative h-[260px] rounded-[2rem] overflow-hidden group cursor-pointer">
                <img src="https://images.unsplash.com/photo-1562774053-701939374585?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80" 
                     class="w-full h-full object-cover transition duration-700 group-hover:scale-110" alt="Laboratorium">
                <div class="absolute inset-0 bg-gradient-to-t from-[#040e22] via-[#040e22]/40 to-transparent opacity-90"></div>
                <div class="absolute bottom-8 left-8">
                    <h3 class="text-[1.3rem] font-extrabold text-white">Laboratorium Terpadu</h3>
                </div>
            </div>
            <div class="relative h-[260px] rounded-[2rem] overflow-hidden group cursor-pointer">
                <img src="https://images.unsplash.com/photo-1541339907198-e08756dedf3f?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80" 
                     class="w-full h-full object-cover transition duration-700 group-hover:scale-110" alt="Perpustakaan">
                <div class="absolute inset-0 bg-gradient-to-t from-[#040e22] via-[#040e22]/40 to-transparent opacity-90"></div>
                <div class="absolute bottom-8 left-8">
                    <h3 class="text-[1.3rem] font-extrabold text-white">Perpustakaan Digital</h3>
                </div>
            </div>
            <div class="relative h-[260px] rounded-[2rem] overflow-hidden group cursor-pointer">
                <img src="https://images.unsplash.com/photo-1497366216548-37526070297c?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80" 
                     class="w-full h-full object-cover transition duration-700 group-hover:scale-110" alt="Coworking Space">
                <div class="absolute inset-0 bg-gradient-to-t from-[#040e22] via-[#040e22]/40 to-transparent opacity-90"></div>
                <div class="absolute bottom-8 left-8">
                    <h3 class="text-[1.3rem] font-extrabold text-white">Area Diskusi & Lounge</h3>
                </div>
            </div>
        </div>
    </section>

    <section id="jalur-pendaftaran" class="px-16 py-24 bg-white">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-x-12 gap-y-16 max-w-6xl mx-auto">
            <div>
                <div class="w-12 h-12 bg-adzkia-badge-bg text-adzkia-badge-txt rounded-full flex items-center justify-center font-extrabold text-lg mb-5">1</div>
                <h3 class="text-lg font-extrabold text-adzkia-dark mb-2">Tahun Akademik</h3>
                <p class="text-gray-500 text-[14px] font-medium leading-relaxed">Penerapan kurikulum berbasis industri dan berstandar internasional untuk mencetak lulusan siap kerja.</p>
            </div>
            <div>
                <div class="w-12 h-12 bg-adzkia-badge-bg text-adzkia-badge-txt rounded-full flex items-center justify-center font-extrabold text-lg mb-5">2</div>
                <h3 class="text-lg font-extrabold text-adzkia-dark mb-2">Dosen Profesional</h3>
                <p class="text-gray-500 text-[14px] font-medium leading-relaxed">Didampingi oleh tenaga pendidik berpengalaman dari kalangan praktisi dan akademisi unggul.</p>
            </div>
            <div>
                <div class="w-12 h-12 bg-adzkia-badge-bg text-adzkia-badge-txt rounded-full flex items-center justify-center font-extrabold text-lg mb-5">3</div>
                <h3 class="text-lg font-extrabold text-adzkia-dark mb-2">Fasilitas Modern</h3>
                <p class="text-gray-500 text-[14px] font-medium leading-relaxed">Infrastruktur dan laboratorium mutakhir yang mendukung riset serta inovasi mahasiswa.</p>
            </div>
            <div>
                <div class="w-12 h-12 bg-adzkia-badge-bg text-adzkia-badge-txt rounded-full flex items-center justify-center font-extrabold text-lg mb-5">4</div>
                <h3 class="text-lg font-extrabold text-adzkia-dark mb-2">Lulusan Berkualitas</h3>
                <p class="text-gray-500 text-[14px] font-medium leading-relaxed">Jaringan alumni yang kuat dan tersebar di berbagai instansi pemerintahan dan perusahaan swasta.</p>
            </div>
            <div>
                <div class="w-12 h-12 bg-adzkia-badge-bg text-adzkia-badge-txt rounded-full flex items-center justify-center font-extrabold text-lg mb-5">5</div>
                <h3 class="text-lg font-extrabold text-adzkia-dark mb-2">Program Beasiswa</h3>
                <p class="text-gray-500 text-[14px] font-medium leading-relaxed">Berbagai macam jalur beasiswa yang tersedia untuk mendukung mahasiswa berprestasi.</p>
            </div>
            <div>
                <div class="w-12 h-12 bg-adzkia-badge-bg text-adzkia-badge-txt rounded-full flex items-center justify-center font-extrabold text-lg mb-5">6</div>
                <h3 class="text-lg font-extrabold text-adzkia-dark mb-2">Lingkungan Kampus Asri</h3>
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
                    {
                        group: 'Beasiswa',
                        paths: [
                            { name: 'Beasiswa Adzkia Unggul (BAU)', desc: 'Beasiswa penuh bagi calon mahasiswa berprestasi akademik dan non-akademik tingkat nasional.' },
                            { name: 'Beasiswa PMDK', desc: 'Penelusuran Minat dan Kemampuan bagi siswa berprestasi di sekolah mitra.' },
                            { name: 'Beasiswa Prestasi', desc: 'Program khusus bagi penghafal Al-Quran atau juara kompetisi nasional.' },
                            { name: 'Beasiswa KIP-K', desc: 'Dukungan biaya pendidikan dari pemerintah bagi mahasiswa dari keluarga kurang mampu.' }
                        ]
                    },
                    {
                        group: 'Rekognisi Pembelajaran Lampau (RPL)',
                        paths: [
                            { name: 'RPL Afirmasi YASB', desc: 'Jalur khusus untuk alumni yayasan mitra dengan konversi pengalaman kerja.' },
                            { name: 'RPL Afirmasi JSIT', desc: 'Khusus bagi guru/pegawai di bawah naungan jaringan sekolah JSIT.' },
                            { name: 'RPL Kelas Khusus', desc: 'Konversi SKS bagi praktisi profesional dengan pengalaman minimal 3 tahun.' }
                        ]
                    }
                ],
                get filteredPaths() {
                    if (this.searchQuery === '') return this.khususPaths;
                    const query = this.searchQuery.toLowerCase();
                    return this.khususPaths.map(group => {
                        return {
                            ...group,
                            paths: group.paths.filter(p => p.name.toLowerCase().includes(query) || p.desc.toLowerCase().includes(query))
                        };
                    }).filter(group => group.paths.length > 0);
                }
            }" 
            class="bg-adzkia-dark rounded-[3rem] p-16 shadow-2xl relative"> 
            
            <h2 class="text-4xl font-extrabold mb-8 text-white tracking-tight">Pilih Jalur Pendaftaranmu</h2>
            
            <div class="flex p-1.5 bg-white/10 backdrop-blur rounded-2xl w-fit mb-12 relative z-20">
                <button @click="mode = 'reguler'; dropdownOpen = false" :class="mode === 'reguler' ? 'bg-white text-adzkia-dark shadow-lg' : 'text-white hover:bg-white/10'" class="px-8 py-3 font-bold rounded-xl transition-all">
                    REGULER
                </button>
                <button @click="mode = 'khusus'" :class="mode === 'khusus' ? 'bg-white text-adzkia-dark shadow-lg' : 'text-white hover:bg-white/10'" class="px-8 py-3 font-bold rounded-xl transition-all flex items-center gap-2">
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
                        <p class="text-gray-300 leading-relaxed font-medium">Terbuka untuk lulusan SMA/SMK sederajat melalui seleksi berbasis komputer. Pendaftaran sepenuhnya dilakukan secara daring.</p>
                        <button class="px-10 py-4 bg-white text-adzkia-dark font-extrabold rounded-2xl hover:scale-105 transition-all shadow-xl">Daftar Sekarang</button>
                    </div>
                    <div class="bg-white/5 p-8 rounded-2xl border border-white/10">
                        <p class="text-xs font-bold uppercase tracking-widest text-gray-400 mb-4">Gelombang Pendaftaran</p>
                        <div class="space-y-3 font-medium">
                            <div class="flex justify-between border-b border-white/10 pb-3"><span>Gelombang 1</span><span class="font-bold text-adzkia-badge-bg">Jan - Mar</span></div>
                            <div class="flex justify-between border-b border-white/10 pb-3"><span>Gelombang 2</span><span class="font-bold text-adzkia-badge-bg">Apr - Jun</span></div>
                            <div class="flex justify-between"><span>Gelombang 3</span><span class="font-bold text-adzkia-badge-bg">Jul - Agt</span></div>
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
                            <label class="block text-sm font-bold uppercase tracking-wider text-gray-400">Cari & Pilih Program Khusus</label>
                            <div class="relative">
                                <button @click="dropdownOpen = !dropdownOpen" @click.outside="dropdownOpen = false" class="w-full bg-white text-adzkia-dark py-4 px-6 rounded-2xl flex justify-between items-center text-left shadow-xl">
                                    <span class="font-bold" x-text="selectedPath ? selectedPath : 'Pilih jalur pendaftaran...'"></span>
                                    <i data-feather="chevron-down" class="transition-transform" :class="dropdownOpen ? 'rotate-180' : ''"></i>
                                </button>
                                
                                <div x-show="dropdownOpen" 
                                     x-transition.opacity.duration.200ms
                                     style="display: none;"
                                     class="absolute top-full left-0 right-0 mt-3 bg-white text-adzkia-dark rounded-2xl shadow-2xl border border-gray-100 max-h-[260px] overflow-y-auto z-[60] custom-scrollbar">
                                    
                                    <div class="p-4 border-b border-gray-100 sticky top-0 bg-white z-10">
                                        <div class="relative">
                                            <i data-feather="search" class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 w-4 h-4"></i>
                                            <input x-model="searchQuery" type="text" placeholder="Cari jalur..." class="w-full pl-10 pr-4 py-3 bg-gray-50 rounded-xl border-none focus:ring-2 focus:ring-adzkia-badge-bg text-sm outline-none font-medium">
                                        </div>
                                    </div>

                                    <div class="pb-2">
                                        <template x-for="(group, index) in filteredPaths" :key="index">
                                            <div :class="index > 0 ? 'border-t border-gray-100 mt-2 pt-2' : ''">
                                                <p class="text-[10px] font-extrabold text-gray-400 uppercase tracking-widest px-5 py-2" x-text="group.group"></p>
                                                <template x-for="path in group.paths" :key="path.name">
                                                    <button @click="selectedPath = path.name; selectedPathDesc = path.desc; dropdownOpen = false" class="w-full text-left px-5 py-3 hover:bg-adzkia-badge-bg transition-colors flex flex-col group/btn">
                                                        <span class="font-bold text-[14px] text-adzkia-dark group-hover/btn:text-adzkia-badge-txt transition-colors" x-text="path.name"></span>
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
                            <div class="bg-white/10 p-8 rounded-2xl min-h-[140px] border border-white/10 flex flex-col justify-center transition-all duration-300">
                                <p class="text-white leading-relaxed font-medium" :class="!selectedPath ? 'italic text-center text-gray-400' : 'text-left'" x-text="selectedPathDesc ? selectedPathDesc : 'Silakan pilih jalur di samping untuk melihat detail persyaratan.'"></p>
                            </div>
                            <button class="w-full py-4 bg-white text-adzkia-dark font-extrabold rounded-2xl shadow-xl hover:scale-105 active:scale-95 transition-all disabled:opacity-50 disabled:hover:scale-100 disabled:cursor-not-allowed" :disabled="!selectedPath">
                                Daftar Program Khusus
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

<section id="berita" x-data class="px-16 py-20 bg-white relative z-30">
        <div class="flex justify-between items-end mb-12">
            <div>
                <h2 class="text-4xl font-extrabold text-adzkia-dark tracking-tight">Berita Terkini</h2>
                <p class="text-gray-500 mt-2 font-medium">Kabar terbaru seputar prestasi dan kegiatan kampus.</p>
            </div>
            <a href="/berita" class="px-8 py-3 border border-adzkia-dark text-adzkia-dark font-bold rounded-full hover:bg-adzkia-dark hover:text-white transition-all">Lihat Semua</a>
        </div>
        
        <div class="grid md:grid-cols-3 gap-8">
            <template x-for="item in $store.kampus.berita.filter(b => !b.isHighlight).slice(0, 3)" :key="item.id">
                <div class="group bg-white rounded-3xl overflow-hidden border border-gray-100 shadow-sm hover:shadow-xl transition-all cursor-pointer flex flex-col">
                    <div class="relative w-full h-48 overflow-hidden">
                        <div class="absolute top-4 left-4 bg-white/90 backdrop-blur text-adzkia-dark text-[10px] font-extrabold px-3 py-1.5 rounded-full uppercase tracking-widest z-10" x-text="item.category"></div>
                        <img :src="item.image" :alt="item.title" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                    </div>
                    <div class="p-6 flex flex-col flex-grow">
                        <p class="text-[11px] font-extrabold text-gray-400 uppercase tracking-widest mb-3" x-text="item.date"></p>
                        <h3 class="text-lg font-extrabold text-adzkia-dark mb-4 line-clamp-2" x-text="item.title"></h3>
                        <div class="mt-auto pt-4">
                            <p class="text-[12px] font-extrabold text-adzkia-badge-txt flex items-center gap-2 group-hover:gap-3 transition-all">
                                Baca <span x-html="feather.icons['arrow-right'].toSvg({ class: 'w-3 h-3' })"></span>
                            </p>
                        </div>
                    </div>
                </div>
            </template>
        </div>
    </section>

    <section id="faq" class="px-16 py-20 bg-adzkia-bg">
        <div class="max-w-[800px] mx-auto">
            <h2 class="text-3xl font-extrabold text-adzkia-dark text-center mb-10">Pertanyaan Populer</h2>
            <div class="space-y-4">
                <div x-data="{ open: false }" class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 cursor-pointer" @click="open = !open">
                    <div class="flex justify-between items-center text-left">
                        <span class="font-extrabold text-adzkia-dark">Apa saja syarat pendaftaran jalur reguler?</span>
                        <i data-feather="chevron-down" class="text-adzkia-dark transition-transform" :class="open ? 'rotate-180' : ''"></i>
                    </div>
                    <div x-show="open" x-collapse class="mt-4 text-gray-500 font-medium text-sm">
                        Syarat utama meliputi ijazah/SKL SMA sederajat, pas foto terbaru, dan KK. Seleksi dilakukan melalui tes tertulis atau nilai UTBK.
                    </div>
                </div>
                <div x-data="{ open: false }" class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 cursor-pointer" @click="open = !open">
                    <div class="flex justify-between items-center text-left">
                        <span class="font-extrabold text-adzkia-dark">Apakah tersedia beasiswa penuh (full scholarship)?</span>
                        <i data-feather="chevron-down" class="text-adzkia-dark transition-transform" :class="open ? 'rotate-180' : ''"></i>
                    </div>
                    <div x-show="open" x-collapse style="display:none;" class="mt-4 text-gray-500 font-medium text-sm">
                        Ya, kami menyediakan Beasiswa Adzkia Unggul (BAU) yang mencakup 100% biaya pendidikan hingga lulus bagi siswa berprestasi.
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section id="kontak" class="px-16 py-20 bg-white">
        <div class="grid md:grid-cols-2 gap-16 items-center max-w-7xl mx-auto">
            <div class="space-y-12 pr-8">
                <div>
                    <h2 class="text-4xl font-extrabold text-adzkia-dark tracking-tight mb-5">Hubungi Kami</h2>
                    <p class="text-gray-500 font-medium leading-relaxed text-[15px]">
                        Tim admisi kami siap membantu menjawab pertanyaan Anda seputar proses pendaftaran, jadwal seleksi, hingga beasiswa. Jangan ragu untuk menghubungi kami melalui kontak di bawah ini.
                    </p>
                </div>
                <div class="space-y-8">
                    <div class="flex gap-6 items-start group cursor-pointer">
                        <div class="w-12 h-12 bg-adzkia-badge-bg rounded-2xl flex items-center justify-center shrink-0 mt-1 group-hover:bg-adzkia-dark transition-colors duration-300">
                            <i data-feather="map-pin" class="text-adzkia-badge-txt group-hover:text-white transition-colors duration-300"></i>
                        </div>
                        <div>
                            <h4 class="font-extrabold text-adzkia-dark text-lg mb-1">Alamat Kampus</h4>
                            <p class="text-gray-500 text-[14px] font-medium leading-relaxed">
                                Jl. Raya Taratak Paneh No.7, Korong Gadang, Kec. Kuranji,<br>Kota Padang, Sumatera Barat 25147
                            </p>
                        </div>
                    </div>
                    <div class="flex gap-6 items-start group cursor-pointer">
                        <div class="w-12 h-12 bg-adzkia-badge-bg rounded-2xl flex items-center justify-center shrink-0 mt-1 group-hover:bg-adzkia-dark transition-colors duration-300">
                            <i data-feather="phone" class="text-adzkia-badge-txt group-hover:text-white transition-colors duration-300"></i>
                        </div>
                        <div>
                            <h4 class="font-extrabold text-adzkia-dark text-lg mb-1">Telepon / WhatsApp</h4>
                            <p class="text-gray-500 text-[14px] font-medium">(0751) 482121 / +62 812-3456-7890</p>
                        </div>
                    </div>
                    <div class="flex gap-6 items-start group cursor-pointer">
                        <div class="w-12 h-12 bg-adzkia-badge-bg rounded-2xl flex items-center justify-center shrink-0 mt-1 group-hover:bg-adzkia-dark transition-colors duration-300">
                            <i data-feather="mail" class="text-adzkia-badge-txt group-hover:text-white transition-colors duration-300"></i>
                        </div>
                        <div>
                            <h4 class="font-extrabold text-adzkia-dark text-lg mb-1">Email Admisi</h4>
                            <p class="text-gray-500 text-[14px] font-medium">pmb@adzkia.ac.id</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="relative w-full h-[450px] rounded-[3rem] overflow-hidden shadow-2xl border-8 border-gray-50 group">
                <img src="https://images.unsplash.com/photo-1524661135-423995f22d0b?auto=format&fit=crop&w=800&q=80" 
                     alt="Peta Lokasi" 
                     class="w-full h-full object-cover grayscale opacity-80 group-hover:grayscale-0 group-hover:opacity-100 transition-all duration-700 group-hover:scale-105">
                <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 flex flex-col items-center">
                    <div class="bg-adzkia-dark text-white text-[12px] font-extrabold px-5 py-2.5 rounded-xl mb-3 shadow-xl whitespace-nowrap relative">
                        Universitas Adzkia
                        <div class="absolute -bottom-1.5 left-1/2 -translate-x-1/2 w-3 h-3 bg-adzkia-dark rotate-45"></div>
                    </div>
                    <div class="w-16 h-16 bg-adzkia-badge-txt text-white rounded-full flex items-center justify-center shadow-2xl ring-8 ring-adzkia-badge-txt/30 animate-bounce cursor-pointer">
                        <i data-feather="map-pin" class="w-7 h-7"></i>
                    </div>
                </div>
            </div>
        </div>
    </section>

@endsection