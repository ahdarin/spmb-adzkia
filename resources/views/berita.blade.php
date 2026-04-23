@extends('layouts.app')

@section('title', 'Berita & Informasi - PMB Adzkia')

@section('content')

<main x-data="halamanBerita()" class="px-16 py-12 min-h-screen bg-adzkia-bg">
    
    <div class="mb-8 flex items-center text-[13px] font-extrabold text-gray-500">
        <a href="/" class="flex items-center gap-2 hover:text-adzkia-dark transition-colors">
            <i data-feather="arrow-left" class="w-4 h-4"></i>
            Dashboard
        </a>
        <span class="mx-2">›</span>
        <span class="text-adzkia-dark">Berita</span>
    </div>

    <div class="mb-10 max-w-3xl">
        <h1 class="text-[3.5rem] font-extrabold text-adzkia-dark tracking-tight mb-4 leading-none">
            Berita & Informasi
        </h1>
        <p class="text-gray-500 text-[15px] font-medium leading-relaxed">
            Update terbaru seputar kegiatan, pengumuman akademik, dan berbagai informasi inspiratif dari seluruh civitas akademika kampus.
        </p>
    </div>

    <div class="flex flex-col md:flex-row justify-between items-center gap-6 mb-12">
        <div class="flex bg-gray-100 p-1.5 rounded-full">
            <template x-for="tab in categories" :key="tab">
                <button 
                    @click="activeCategory = tab"
                    class="px-6 py-2.5 text-[13px] font-bold rounded-full transition-all duration-300"
                    :class="activeCategory === tab ? 'bg-adzkia-dark text-white shadow-md' : 'text-gray-500 hover:text-adzkia-dark'"
                    x-text="tab"
                ></button>
            </template>
        </div>

        <div class="relative w-full md:w-[350px]">
            <i data-feather="search" class="absolute left-5 top-1/2 -translate-y-1/2 text-gray-400 w-4 h-4"></i>
            <input 
                x-model="searchQuery" 
                type="text" 
                placeholder="Cari berita..." 
                class="w-full pl-12 pr-4 py-3 bg-white rounded-full focus:ring-2 focus:ring-adzkia-badge-bg focus:outline-none text-[13px] font-medium transition-all shadow-sm"
            >
        </div>
    </div>

    <div x-show="activeCategory === 'Semua' && searchQuery === ''" class="mb-16">
        <template x-if="highlightNews">
            <div class="bg-white rounded-[2.5rem] overflow-hidden flex flex-col md:flex-row border border-gray-100 shadow-sm hover:shadow-xl transition-shadow cursor-pointer">
                <div class="md:w-[60%] h-[300px] md:h-[450px]">
                    <img :src="highlightNews.image" class="w-full h-full object-cover">
                </div>
                <div class="md:w-[40%] p-10 md:p-14 flex flex-col justify-center">
                    <div class="flex items-center gap-4 mb-4">
                        <span class="bg-adzkia-badge-bg text-adzkia-badge-txt text-[10px] font-extrabold px-3 py-1.5 rounded-full uppercase tracking-widest">HIGHLIGHT</span>
                        <span class="text-[12px] font-bold text-gray-400 uppercase tracking-widest" x-text="highlightNews.date"></span>
                    </div>
                    <h2 class="text-3xl font-extrabold text-adzkia-dark mb-4 leading-tight" x-text="highlightNews.title"></h2>
                    <p class="text-gray-500 font-medium leading-relaxed mb-8" x-text="highlightNews.excerpt"></p>
                    <div>
                        <button class="bg-adzkia-badge-bg text-adzkia-badge-txt text-[13px] font-bold px-6 py-2.5 rounded-full hover:bg-adzkia-badge-txt hover:text-white transition-colors">
                            Baca
                        </button>
                    </div>
                </div>
            </div>
        </template>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8 mb-16">
        <template x-for="item in filteredNews" :key="item.id">
            
            <div class="bg-white rounded-3xl overflow-hidden border border-gray-100 shadow-sm hover:shadow-xl transition-all cursor-pointer flex flex-col group">
                <div class="relative w-full h-56 overflow-hidden">
                    <div class="absolute top-4 left-4 bg-white/90 backdrop-blur text-adzkia-dark text-[10px] font-extrabold px-4 py-1.5 rounded-full uppercase tracking-widest z-10" x-text="item.category"></div>
                    <img :src="item.image" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-700">
                </div>
                <div class="p-8 flex flex-col flex-grow">
                    <p class="text-[11px] font-extrabold text-gray-400 uppercase tracking-widest mb-3" x-text="item.date"></p>
                    <h3 class="text-xl font-extrabold text-adzkia-dark mb-4 line-clamp-2 leading-snug" x-text="item.title"></h3>
                    <p class="text-[14px] text-gray-500 font-medium leading-relaxed line-clamp-3 mb-8" x-text="item.excerpt"></p>
                    
                    <div class="mt-auto">
                        <button class="bg-adzkia-badge-bg text-adzkia-badge-txt text-[12px] font-bold px-5 py-2 rounded-full hover:bg-adzkia-badge-txt hover:text-white transition-colors">
                            Baca
                        </button>
                    </div>
                </div>
            </div>
            
        </template>
    </div>

    <div x-show="filteredNews.length === 0" class="text-center py-20" style="display: none;">
        <div class="w-20 h-20 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
            <i data-feather="search" class="w-8 h-8 text-gray-400"></i>
        </div>
        <h3 class="text-xl font-extrabold text-adzkia-dark mb-2">Berita Tidak Ditemukan</h3>
        <p class="text-gray-500 font-medium">Coba gunakan kata kunci lain atau ubah filter kategori Anda.</p>
    </div>

    <div class="flex flex-col items-center justify-center pt-8 border-t border-gray-200">
        <button class="bg-gray-100 text-adzkia-dark text-[13px] font-bold px-8 py-3 rounded-full hover:bg-gray-200 transition-colors flex items-center gap-2 mb-6">
            Lihat Berita Lainnya <i data-feather="chevron-down" class="w-4 h-4"></i>
        </button>
    </div>

</main>

<script>
    function halamanBerita() {
        return {
            searchQuery: '',
            activeCategory: 'Semua',
            categories: ['Semua', 'Akademik', 'Prestasi', 'Event'],
            
            // Ambil data highlight khusus
            get highlightNews() {
                return Alpine.store('kampus').berita.find(b => b.isHighlight);
            },

            // Filter data berita untuk Grid
            get filteredNews() {
                let result = Alpine.store('kampus').berita;
                
                // Jika sedang di mode 'Semua' dan pencarian kosong, hilangkan highlight dari grid
                if (this.activeCategory === 'Semua' && this.searchQuery === '') {
                    result = result.filter(b => !b.isHighlight);
                }
                
                // Filter Kategori
                if (this.activeCategory !== 'Semua') {
                    result = result.filter(b => b.category === this.activeCategory);
                }
                
                // Filter Pencarian
                if (this.searchQuery.trim() !== '') {
                    const q = this.searchQuery.toLowerCase();
                    result = result.filter(b => 
                        b.title.toLowerCase().includes(q) || 
                        b.excerpt.toLowerCase().includes(q)
                    );
                }
                
                return result;
            }
        }
    }
</script>
@endsection