@extends('layouts.app')

@section('title', 'Berita & Informasi - PMB Adzkia')

@section('content')

@php
    // Pindahkan proses pemetaan data ke blok PHP agar compiler Blade tidak error
    $beritaData = $beritas->map(function($item, $index) {
        return [
            'id'          => $item->id,
            'slug'        => $item->slug,
            'title'       => $item->judul,
            'category'    => $item->kategori ?? 'Informasi',
            'image'       => $item->thumbnail ? asset('uploads/berita/' . $item->thumbnail) : 'https://images.unsplash.com/photo-1523050854058-8df90110c9f1?q=80&w=500',
            'date'        => \Carbon\Carbon::parse($item->created_at)->translatedFormat('d F Y'),
            'excerpt'     => $item->ringkasan,
            'isHighlight' => $index === 0 // Menjadikan berita paling baru sebagai highlight
        ];
    });
@endphp

<main x-data="halamanBerita()" class="px-16 py-12 min-h-screen bg-adzkia-bg">
    
    <div class="mb-8 flex items-center text-[13px] font-extrabold text-adzkia-muted">
        <a href="/" class="flex items-center gap-2 hover:text-adzkia-blue transition-colors">
            <i data-feather="arrow-left" class="w-4 h-4"></i>
            Dashboard
        </a>
        <span class="mx-2">›</span>
        <span class="text-adzkia-blue">Berita</span>
    </div>

    <div class="mb-10 max-w-3xl">
        <h1 class="text-[3.5rem] font-extrabold text-adzkia-blue tracking-tight mb-4 leading-none">
            Berita & Informasi
        </h1>
        <p class="text-gray-500 text-[15px] font-medium leading-relaxed">
            Update terbaru seputar kegiatan, pengumuman akademik, dan berbagai informasi inspiratif dari seluruh civitas akademika kampus.
        </p>
    </div>

    <div class="flex flex-col md:flex-row justify-between items-center gap-6 mb-12">
        <div class="flex bg-gray-200 p-1.5 rounded-full">
            <template x-for="tab in categories" :key="tab">
                <button 
                    @click="activeCategory = tab"
                    class="px-6 py-2.5 text-[13px] font-bold rounded-full transition-all duration-300"
                    :class="activeCategory === tab ? 'bg-adzkia-blue text-white shadow-md' : 'text-gray-600 hover:text-adzkia-blue'"
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
                class="w-full pl-12 pr-4 py-3 bg-white rounded-full focus:ring-2 focus:ring-adzkia-blue/20 focus:border-adzkia-blue focus:outline-none text-[13px] font-medium transition-all shadow-sm border border-gray-200"
            >
        </div>
    </div>

    <div x-show="activeCategory === 'Semua' && searchQuery === ''" class="mb-16">
        <template x-if="highlightNews">
            <div class="bg-white rounded-[2.5rem] overflow-hidden flex flex-col md:flex-row border border-gray-100 shadow-sm hover:shadow-xl transition-shadow cursor-pointer group">
                <div class="md:w-[60%] h-[300px] md:h-[450px] overflow-hidden">
                    <img :src="highlightNews.image" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-700">
                </div>
                <div class="md:w-[40%] p-10 md:p-14 flex flex-col justify-center">
                    <div class="flex items-center gap-4 mb-4">
                        <span class="bg-adzkia-red text-white text-[10px] font-extrabold px-3 py-1.5 rounded-full uppercase tracking-widest">HIGHLIGHT</span>
                        <span class="text-[12px] font-bold text-gray-400 uppercase tracking-widest" x-text="highlightNews.date"></span>
                    </div>
                    <h2 class="text-3xl font-extrabold text-adzkia-blue mb-4 leading-tight group-hover:text-adzkia-red transition-colors" x-text="highlightNews.title"></h2>
                    <p class="text-gray-500 font-medium leading-relaxed mb-8" x-text="highlightNews.excerpt"></p>
                    <div>
                        <a :href="'/berita/' + highlightNews.slug" class="bg-adzkia-badge-bg text-adzkia-blue text-[13px] font-bold px-6 py-2.5 rounded-full hover:bg-adzkia-blue hover:text-white transition-colors inline-block">
                            Baca Selengkapnya
                        </a>
                    </div>
                </div>
            </div>
        </template>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8 mb-16">
        <template x-for="item in filteredNews" :key="item.id">
            <div class="bg-white rounded-3xl overflow-hidden border border-gray-100 shadow-sm hover:shadow-xl hover:border-adzkia-blue transition-all cursor-pointer flex flex-col group">
                <div class="relative w-full h-56 overflow-hidden">
                    <div class="absolute top-4 left-4 bg-white/90 backdrop-blur text-adzkia-blue text-[10px] font-extrabold px-4 py-1.5 rounded-full uppercase tracking-widest z-10" x-text="item.category"></div>
                    <img :src="item.image" :alt="item.title" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-700">
                </div>
                <div class="p-8 flex flex-col flex-grow">
                    <p class="text-[11px] font-extrabold text-gray-400 uppercase tracking-widest mb-3" x-text="item.date"></p>
                    <h3 class="text-lg font-extrabold text-adzkia-blue mb-4 line-clamp-2 leading-snug group-hover:text-adzkia-red transition-colors" x-text="item.title"></h3>
                    <p class="text-[14px] text-gray-500 font-medium leading-relaxed line-clamp-3 mb-8" x-text="item.excerpt"></p>
                    
                    <div class="mt-auto">
                        <a :href="'/berita/' + item.slug" class="bg-adzkia-badge-bg text-adzkia-blue text-[12px] font-bold px-5 py-2 rounded-full hover:bg-adzkia-blue hover:text-white transition-colors inline-block">
                            Baca Berita
                        </a>
                    </div>
                </div>
            </div>
        </template>
    </div>

    <div x-show="isDataEmpty" class="text-center py-20" style="display: none;">
        <div class="w-20 h-20 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4 text-adzkia-blue">
            <i data-feather="search" class="w-8 h-8"></i>
        </div>
        <h3 class="text-xl font-extrabold text-adzkia-blue mb-2">Berita Tidak Ditemukan</h3>
        <p class="text-gray-500 font-medium">Coba gunakan kata kunci lain atau ubah filter kategori Anda.</p>
    </div>

    <div class="flex flex-col items-center justify-center pt-8 border-t border-gray-200">
        <button class="bg-gray-100 text-adzkia-blue text-[13px] font-bold px-8 py-3 rounded-full hover:bg-adzkia-blue hover:text-white transition-colors flex items-center gap-2 mb-6">
            Lihat Berita Lainnya <i data-feather="chevron-down" class="w-4 h-4"></i>
        </button>
    </div>

</main>

<script>
    function halamanBerita() {
        // Ambil data langsung dari Controller (Database) dan parsing menjadi JSON JavaScript
        const databaseBerita = @json($beritaData);

        // Secara otomatis mengambil kategori yang tersedia di database
        const uniqueCategories = ['Semua', ...new Set(databaseBerita.map(item => item.category))];

        return {
            searchQuery: '',
            activeCategory: 'Semua',
            categories: uniqueCategories.length > 1 ? uniqueCategories : ['Semua', 'Akademik', 'Prestasi', 'Event'],
            beritaDataAsli: databaseBerita,
            
            get highlightNews() {
                // Mengambil berita highlight dari data database
                return this.beritaDataAsli.find(b => b.isHighlight);
            },

            get filteredNews() {
                // Mengambil sisa berita dari data database
                let result = this.beritaDataAsli;
                
                if (this.activeCategory === 'Semua' && this.searchQuery === '') {
                    result = result.filter(b => !b.isHighlight);
                }
                
                if (this.activeCategory !== 'Semua') {
                    result = result.filter(b => b.category === this.activeCategory);
                }
                
                if (this.searchQuery.trim() !== '') {
                    const q = this.searchQuery.toLowerCase();
                    result = result.filter(b => 
                        b.title.toLowerCase().includes(q) || 
                        b.excerpt.toLowerCase().includes(q)
                    );
                }
                return result;
            },

            // --- TAMBAHKAN FUNGSI INI ---
            get isDataEmpty() {
                // Jika sedang di tab "Semua" dan tidak mencari apa-apa
                if (this.activeCategory === 'Semua' && this.searchQuery === '') {
                    // Cek apakah highlight kosong DAN sisa berita kosong
                    return !this.highlightNews && this.filteredNews.length === 0;
                }
                // Jika sedang mencari atau di tab kategori tertentu
                return this.filteredNews.length === 0;
            },
            
            init() {
                this.$nextTick(() => {
                    if (window.feather) feather.replace();
                });
            }
        }
    }
</script>
@endsection