@extends('layouts.app')

@section('title', 'Program Studi - PMB Adzkia')

@section('content')

<main x-data="filterProdi()" class="px-16 py-12 min-h-screen">
    
    <div class="mb-8 flex items-center text-[13px] font-extrabold text-gray-500">
        <a href="/" class="flex items-center gap-2 hover:text-adzkia-dark transition-colors">
            <i data-feather="arrow-left" class="w-4 h-4"></i>
            Dashboard
        </a>
        <span class="mx-2">›</span>
        <span class="text-adzkia-dark">Program Studi</span>
    </div>

    <div class="mb-10 max-w-2xl">
        <h1 class="text-[3rem] font-extrabold text-adzkia-dark tracking-tight mb-4 leading-none">
            Program Studi
        </h1>
        <p class="text-gray-500 text-[15px] font-medium leading-relaxed">
            Pilih program studi yang sesuai dengan aspirasi masa depan Anda melalui kurikulum berbasis industri dan riset global.
        </p>
    </div>

    <div class="flex flex-col md:flex-row justify-between items-center gap-6 mb-12">
        <div class="relative w-full md:w-[400px]">
            <i data-feather="search" class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 w-5 h-5"></i>
            <input x-model="searchQuery" type="text" placeholder="Cari program studi..." class="w-full pl-12 pr-4 py-3.5 bg-white border border-gray-200 rounded-2xl focus:ring-2 focus:ring-adzkia-badge-bg focus:border-adzkia-badge-txt text-[14px] outline-none font-medium transition-all shadow-sm">
        </div>

        <div class="flex bg-gray-100 p-1.5 rounded-2xl">
            <template x-for="tab in categories" :key="tab">
                <button @click="activeCategory = tab" class="px-8 py-2.5 text-[13px] font-extrabold rounded-xl transition-all duration-300" :class="activeCategory === tab ? 'bg-white text-adzkia-dark shadow-sm' : 'text-gray-500 hover:text-adzkia-dark'" x-text="tab"></button>
            </template>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-20">
        <template x-for="prodi in filteredList" :key="prodi.id">
            
            <div @click="$store.kampus.openModal(prodi)" class="bg-white p-8 rounded-3xl border border-gray-100 shadow-sm hover:shadow-xl transition-all duration-300 cursor-pointer group flex flex-col justify-between min-h-[260px]">
                
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

                    <div class="mb-1 flex">
                        <span class="px-2 py-1 rounded text-[10px] font-extrabold uppercase tracking-widest"
                              :class="{
                                  'bg-red-50 text-red-600': prodi.level === 'Profesi',
                                  'bg-blue-50 text-blue-600': prodi.level === 'S1',
                                  'bg-indigo-50 text-indigo-600': prodi.level === 'S2',
                              }"
                              x-text="prodi.level"></span>
                    </div>
                    
                    <h3 class="text-xl font-extrabold text-adzkia-dark mb-3 mt-2" x-text="prodi.name"></h3>
                    <p class="text-gray-500 text-[13px] font-medium leading-relaxed line-clamp-2" x-text="prodi.desc"></p>
                </div>

                <div class="mt-8">
                    <p class="text-[13px] font-extrabold text-adzkia-dark flex items-center gap-2 group-hover:gap-3 transition-all">
                        Lihat Biaya & Detail <span x-html="feather.icons['arrow-right'].toSvg({ class: 'w-4 h-4' })"></span>
                    </p>
                </div>
            </div>
            
        </template>
    </div>

    <div x-show="filteredList.length === 0" class="text-center py-20" style="display: none;">
        <div class="w-20 h-20 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
            <i data-feather="search" class="w-8 h-8 text-gray-400"></i>
        </div>
        <h3 class="text-xl font-extrabold text-adzkia-dark mb-2">Program Studi Tidak Ditemukan</h3>
        <p class="text-gray-500 font-medium">Coba gunakan kata kunci lain atau ubah filter kategori Anda.</p>
    </div>

</main>

<script>
    function filterProdi() {
        return {
            searchQuery: '',
            activeCategory: 'Semua',
            categories: ['Semua', 'S1', 'S2', 'Profesi'],
            
            get filteredList() {
                // Mengambil data murni dari Global Store di app.blade.php
                let result = Alpine.store('kampus').programs;
                
                if (this.activeCategory !== 'Semua') {
                    result = result.filter(p => p.level === this.activeCategory);
                }
                
                if (this.searchQuery.trim() !== '') {
                    const q = this.searchQuery.toLowerCase();
                    result = result.filter(p => 
                        p.name.toLowerCase().includes(q) || 
                        p.desc.toLowerCase().includes(q)
                    );
                }
                
                return result;
            }
        }
    }
</script>
@endsection