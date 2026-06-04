@extends('layouts.app')

@section('title', 'Program Studi - PMB Adzkia')

@section('content')

@php
    $prodis = \App\Models\Prodi::all();
@endphp

<main x-data="filterProdi(@js($prodis))" class="px-16 py-12 min-h-screen bg-adzkia-bg">
    
    <div class="mb-8 flex items-center text-[13px] font-extrabold text-adzkia-muted">
        <a href="/" class="flex items-center gap-2 hover:text-adzkia-blue transition-colors">
            <i data-feather="arrow-left" class="w-4 h-4"></i>
            Dashboard
        </a>
        <span class="mx-2">›</span>
        <span class="text-adzkia-blue">Program Studi</span>
    </div>

    <div class="mb-10 max-w-2xl">
        <h1 class="text-[3rem] font-extrabold text-adzkia-blue tracking-tight mb-4 leading-none">
            Program Studi
        </h1>
        <p class="text-gray-500 text-[15px] font-medium leading-relaxed">
            Pilih program studi yang sesuai dengan aspirasi masa depan Anda. Dapatkan informasi detail mengenai kuota, akreditasi, dan biaya perkuliahan.
        </p>
    </div>

    <div class="flex flex-col md:flex-row justify-between items-center gap-6 mb-12">
        <div class="relative w-full md:w-[400px]">
            <i data-feather="search" class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 w-5 h-5"></i>
            <input x-model="searchQuery" type="text" placeholder="Cari program studi..." class="w-full pl-12 pr-4 py-3.5 bg-white border border-gray-200 rounded-2xl focus:ring-2 focus:ring-adzkia-blue/20 focus:border-adzkia-blue text-[14px] outline-none font-medium transition-all shadow-sm">
        </div>

        <div class="flex bg-gray-200 p-1.5 rounded-2xl">
            <template x-for="tab in categories" :key="tab">
                <button @click="activeCategory = tab" 
                        class="px-8 py-2.5 text-[13px] font-extrabold rounded-xl transition-all duration-300" 
                        :class="activeCategory === tab ? 'bg-white text-adzkia-blue shadow-sm' : 'text-gray-500 hover:text-adzkia-dark'" 
                        x-text="tab"></button>
            </template>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-20">
        <template x-for="prodi in filteredList" :key="prodi.id">
            
            <div @click="openModal(prodi)" class="bg-white p-8 rounded-3xl border border-gray-100 shadow-sm hover:shadow-xl hover:border-adzkia-blue transition-all duration-300 cursor-pointer group flex flex-col justify-between min-h-[260px]">
                
                <div>
                    <div class="flex justify-between items-start mb-6">
                        <div class="w-12 h-12 bg-adzkia-badge-bg rounded-[14px] flex items-center justify-center group-hover:bg-adzkia-blue transition-colors duration-300"
                             x-html="getIconSvg(prodi.icon, 'w-5 h-5 text-adzkia-blue group-hover:text-white transition-colors')">
                        </div>
                        
                        <div class="px-3 py-1.5 bg-adzkia-blue text-white rounded-lg text-[10px] font-extrabold uppercase tracking-widest flex items-center gap-1.5">
                            <span x-html="getIconSvg('award', 'w-3 h-3')"></span>
                            <span x-text="prodi.akreditasi || 'B'"></span>
                        </div>
                    </div>

                    <div class="mb-1 flex">
                        <span class="px-2 py-1 rounded text-[10px] font-extrabold uppercase tracking-widest bg-blue-50 text-adzkia-blue" x-text="prodi.jenjang || 'S1'"></span>
                    </div>
                    
                    <h3 class="text-xl font-extrabold text-adzkia-blue mb-3 mt-2 group-hover:text-adzkia-red transition-colors" x-text="prodi.nama"></h3>
                    <p class="text-gray-500 text-[13px] font-medium leading-relaxed line-clamp-2" x-text="prodi.deskripsi || 'Program studi unggulan Universitas Adzkia.'"></p>
                </div>

                <div class="mt-8 pt-4 border-t border-gray-50">
                    <p class="text-[13px] font-extrabold text-adzkia-red flex items-center gap-2 group-hover:gap-3 transition-all">
                        Lihat Biaya & Detail <span x-html="getIconSvg('arrow-right', 'w-4 h-4')"></span>
                    </p>
                </div>
            </div>
            
        </template>
    </div>

    <div x-show="filteredList.length === 0" class="text-center py-20" style="display: none;">
        <div class="w-20 h-20 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4 text-adzkia-blue">
            <i data-feather="search" class="w-8 h-8"></i>
        </div>
        <h3 class="text-xl font-extrabold text-adzkia-blue mb-2">Program Studi Tidak Ditemukan</h3>
        <p class="text-gray-500 font-medium">Coba gunakan kata kunci lain atau ubah filter jenjang pendidikan.</p>
    </div>

    <template x-teleport="body">
        <div x-show="isModalOpen" x-cloak class="fixed inset-0 z-[100] flex items-center justify-center p-6">
            <div x-show="isModalOpen" x-transition.opacity @click="closeModal()" class="absolute inset-0 bg-brand-dark/70 backdrop-blur-sm"></div>
            
            <div x-show="isModalOpen" 
                 x-transition:enter="transition ease-out duration-300"
                 x-transition:enter-start="opacity-0 scale-95 translate-y-4"
                 x-transition:enter-end="opacity-100 scale-100 translate-y-0"
                 class="bg-white w-full max-w-2xl rounded-[2.5rem] shadow-2xl relative z-10 overflow-hidden flex flex-col max-h-[90vh]">
                 
                 <div class="p-8 border-b border-gray-100 flex justify-between items-center bg-gray-50/50">
                    <div class="flex items-center gap-5">
                        <div class="w-16 h-16 bg-white shadow-sm border border-gray-100 rounded-2xl flex items-center justify-center text-adzkia-blue shrink-0"
                             x-html="getIconSvg(selectedProdi?.icon, 'w-7 h-7')">
                        </div>
                        <div>
                            <h2 class="text-2xl font-black text-adzkia-blue tracking-tight" x-text="selectedProdi?.nama"></h2>
                            <p class="text-[13px] font-bold text-gray-500 mt-1">Program <span x-text="selectedProdi?.jenjang"></span> • Akreditasi <span class="text-adzkia-blue" x-text="selectedProdi?.akreditasi"></span></p>
                        </div>
                    </div>
                    <button @click="closeModal()" class="w-10 h-10 bg-white border border-gray-200 rounded-full flex items-center justify-center text-gray-400 hover:text-red-500 hover:border-red-500 transition-colors shadow-sm">
                        <i data-feather="x" class="w-4 h-4"></i>
                    </button>
                 </div>

                 <div class="p-8 overflow-y-auto custom-scrollbar">
                    <h4 class="text-[11px] font-black text-gray-400 uppercase tracking-widest mb-3">Deskripsi Program Studi</h4>
                    <p class="text-[14px] text-gray-600 font-medium leading-relaxed mb-8" x-text="selectedProdi?.deskripsi || 'Belum ada deskripsi spesifik yang ditambahkan untuk program studi ini oleh Administrator.'"></p>

                    <div class="grid grid-cols-2 gap-4 mb-8">
                        <div class="p-6 bg-blue-50 border border-blue-100 rounded-3xl">
                            <div class="w-10 h-10 bg-blue-200/50 text-blue-700 rounded-xl flex items-center justify-center mb-4"><i data-feather="users" class="w-5 h-5"></i></div>
                            <p class="text-[10px] font-black text-blue-500 uppercase tracking-widest mb-1">Daya Tampung</p>
                            <p class="text-3xl font-black text-adzkia-blue"><span x-text="selectedProdi?.kuota || 0"></span> <span class="text-sm font-bold text-blue-600/60">Kursi</span></p>
                        </div>
                        <div class="p-6 bg-green-50 border border-green-100 rounded-3xl">
                            <div class="w-10 h-10 bg-green-200/50 text-green-700 rounded-xl flex items-center justify-center mb-4"><i data-feather="credit-card" class="w-5 h-5"></i></div>
                            <p class="text-[10px] font-black text-green-600 uppercase tracking-widest mb-1">Biaya Per Semester</p>
                            <p class="text-2xl font-black text-green-700 mt-1">Rp <span x-text="formatRupiah(selectedProdi?.biaya || 0)"></span></p>
                        </div>
                    </div>

                    <div class="bg-amber-50 p-5 rounded-2xl border border-amber-100 flex gap-4 items-start">
                        <i data-feather="info" class="w-5 h-5 text-amber-500 shrink-0 mt-0.5"></i>
                        <div>
                            <h4 class="text-[13px] font-bold text-amber-900 mb-1">Informasi Dukungan</h4>
                            <p class="text-[12px] text-amber-700/80 leading-relaxed font-medium">Anda bisa mendaftar program studi ini melalui Jalur Reguler, Mandiri, atau Program Beasiswa yang tersedia.</p>
                        </div>
                    </div>
                 </div>

                 <div class="p-6 border-t border-gray-100 bg-gray-50 flex justify-end gap-3">
                    <button @click="closeModal()" class="px-6 py-3.5 bg-white border border-gray-200 text-gray-600 rounded-xl font-bold text-[13px] hover:bg-gray-100 transition-colors">Tutup Jendela</button>
                    <a href="/register" class="px-8 py-3.5 bg-adzkia-red text-white rounded-xl font-bold text-[13px] hover:bg-red-700 transition-colors shadow-lg shadow-red-500/20 flex items-center gap-2">
                        Daftar Prodi Ini <span x-html="getIconSvg('arrow-right', 'w-4 h-4')"></span>
                    </a>
                 </div>
            </div>
        </div>
    </template>

</main>

<script>
    // Inisialisasi feather icons statis saat halaman diload
    document.addEventListener("DOMContentLoaded", () => {
        setTimeout(() => { if (typeof feather !== 'undefined') feather.replace(); }, 50);
    });

    function filterProdi(dataFromDb) {
        return {
            prodis: dataFromDb, 
            searchQuery: '',
            activeCategory: 'Semua',
            categories: ['Semua', 'S1', 'S2', 'D3'],
            
            isModalOpen: false,
            selectedProdi: null,
            
            get filteredList() {
                let result = this.prodis;
                
                if (this.activeCategory !== 'Semua') {
                    result = result.filter(p => p.jenjang === this.activeCategory);
                }
                
                if (this.searchQuery.trim() !== '') {
                    const q = this.searchQuery.toLowerCase();
                    result = result.filter(p => 
                        p.nama.toLowerCase().includes(q) || 
                        (p.deskripsi && p.deskripsi.toLowerCase().includes(q))
                    );
                }
                
                return result;
            },

            openModal(prodi) {
                this.selectedProdi = prodi;
                this.isModalOpen = true;
                
                // Minta feather icon merender ulang ikon non-dinamis di modal
                this.$nextTick(() => {
                    if(typeof feather !== 'undefined') feather.replace();
                });
            },

            closeModal() {
                this.isModalOpen = false;
                setTimeout(() => {
                    this.selectedProdi = null;
                }, 300);
            },

            formatRupiah(angka) {
                return new Intl.NumberFormat('id-ID').format(angka);
            },

            // Helper Cerdas untuk menghasilkan SVG Feather Icons
            getIconSvg(iconName, customClass) {
                let name = iconName || 'book-open';
                
                // Cek apakah script feather tersedia
                if (typeof feather !== 'undefined') {
                    // Jika ikon tidak ada di library feather, gunakan ikon default
                    if (!feather.icons[name]) {
                        name = 'book-open';
                    }
                    return feather.icons[name].toSvg({ class: customClass });
                }
                return '';
            }
        }
    }
</script>
@endsection