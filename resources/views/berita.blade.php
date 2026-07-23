@extends('layouts.app')

@section('title', 'Berita & Informasi - SPMB Adzkia')

@section('content')

@php
    $items = $data ?? $beritas ?? collect();
    $beritaData = $items->map(function($item, $index) {
        return [
            'id'          => $item->id,
            'slug'        => $item->slug ?? '',
            'title'       => $item->judul,
            'category'    => $item->kategori ?? 'Informasi',
            'image'       => $item->thumbnail
                                ? asset('uploads/berita/' . $item->thumbnail)
                                : 'https://images.unsplash.com/photo-1523050854058-8df90110c9f1?q=80&w=800',
            'date'        => \Carbon\Carbon::parse($item->tanggal_publish ?? $item->created_at)->translatedFormat('d F Y'),
            'excerpt'     => $item->ringkasan ?? '',
            'isHighlight' => $index === 0,
        ];
    });
@endphp

<div x-data="halamanBerita()" class="bg-adzkia-bg min-h-screen">

    {{-- ══ HERO HEADER ══════════════════════════════════════════ --}}
    <div class="bg-white border-b border-gray-100 py-14 px-6 lg:px-16">
        <div class="max-w-7xl mx-auto">
            {{-- Breadcrumb --}}
            <nav class="flex items-center gap-2 text-[12px] font-bold text-adzkia-muted mb-6">
                <a href="/" class="hover:text-adzkia-blue transition-colors">Beranda</a>
                <span class="text-gray-300">›</span>
                <span class="text-adzkia-blue">Berita & Informasi</span>
            </nav>

            <div class="flex flex-col md:flex-row md:items-end justify-between gap-6">
                <div>
                    <span class="inline-block bg-adzkia-badge-bg text-adzkia-blue text-[10px] font-black uppercase tracking-widest px-3 py-1.5 rounded-full mb-4">
                        Universitas Adzkia
                    </span>
                    <h1 class="text-4xl md:text-5xl font-black text-adzkia-dark tracking-tight leading-tight mb-3">
                        Berita & Informasi
                    </h1>
                    <p class="text-adzkia-muted text-[15px] font-medium leading-relaxed max-w-xl">
                        Update terbaru seputar kegiatan, pengumuman akademik, dan berbagai informasi inspiratif dari kampus.
                    </p>
                </div>

                {{-- Search --}}
                <div class="relative w-full md:w-[300px] shrink-0">
                    <i data-feather="search" class="absolute left-4 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400 pointer-events-none"></i>
                    <input x-model="searchQuery" type="text" placeholder="Cari berita..."
                        class="w-full pl-11 pr-4 py-3 bg-gray-50 border border-gray-200 rounded-2xl text-[13px] font-medium outline-none focus:border-adzkia-blue focus:bg-white focus:ring-2 focus:ring-adzkia-blue/10 transition-all">
                </div>
            </div>

            {{-- Tab Kategori --}}
            <div class="flex flex-wrap gap-2 mt-8">
                <template x-for="tab in categories" :key="tab">
                    <button @click="activeCategory = tab"
                        class="px-5 py-2 text-[12px] font-bold rounded-full border transition-all duration-200"
                        :class="activeCategory === tab
                            ? 'bg-adzkia-blue text-white border-adzkia-blue shadow-sm shadow-adzkia-blue/20'
                            : 'bg-white text-adzkia-muted border-gray-200 hover:border-adzkia-blue hover:text-adzkia-blue'"
                        x-text="tab">
                    </button>
                </template>
            </div>
        </div>
    </div>

    {{-- ══ KONTEN ════════════════════════════════════════════════ --}}
    <div class="max-w-7xl mx-auto px-6 lg:px-16 py-12">

        {{-- ── HIGHLIGHT (berita pertama, tab Semua & tanpa search) ── --}}
        <template x-if="activeCategory === 'Semua' && searchQuery.trim() === '' && highlightNews">
            <a :href="'/berita/' + highlightNews.slug" class="block mb-12 group">
                <div class="relative bg-white rounded-[2.5rem] overflow-hidden border border-gray-100 shadow-sm hover:shadow-2xl transition-all duration-500 flex flex-col lg:flex-row">

                    {{-- Badge HIGHLIGHT --}}
                    <div class="absolute top-6 left-6 z-10 flex items-center gap-2">
                        <span class="bg-adzkia-red text-white text-[9px] font-black px-3 py-1.5 rounded-full uppercase tracking-widest shadow-sm">
                            ✦ Highlight
                        </span>
                    </div>

                    {{-- Gambar --}}
                    <div class="lg:w-[55%] h-[280px] lg:h-[440px] overflow-hidden shrink-0">
                        <img :src="highlightNews.image" :alt="highlightNews.title"
                             class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-700">
                    </div>

                    {{-- Konten --}}
                    <div class="flex-1 p-8 lg:p-14 flex flex-col justify-center">
                        <div class="flex items-center gap-3 mb-5">
                            <span class="bg-adzkia-badge-bg text-adzkia-blue text-[10px] font-black px-3 py-1.5 rounded-full uppercase tracking-widest"
                                  x-text="highlightNews.category"></span>
                            <span class="text-adzkia-muted text-[12px] font-bold" x-text="highlightNews.date"></span>
                        </div>
                        <h2 class="text-2xl lg:text-3xl font-black text-adzkia-dark leading-tight mb-4 group-hover:text-adzkia-blue transition-colors"
                            x-text="highlightNews.title"></h2>
                        <p class="text-adzkia-muted font-medium leading-relaxed mb-8 line-clamp-3 text-[15px]"
                           x-text="highlightNews.excerpt || 'Klik untuk membaca berita selengkapnya.'"></p>
                        <div class="flex items-center gap-2 text-adzkia-blue font-bold text-[14px]">
                            <span>Baca Selengkapnya</span>
                            <div class="w-7 h-7 rounded-full bg-adzkia-badge-bg flex items-center justify-center group-hover:bg-adzkia-blue group-hover:text-white transition-all">
                                <i data-feather="arrow-right" class="w-3.5 h-3.5"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </a>
        </template>

        {{-- ── Judul section grid ── --}}
        <div x-show="filteredNews.length > 0" class="flex items-center justify-between mb-6">
            <h2 class="text-[13px] font-black text-adzkia-muted uppercase tracking-widest">
                <span x-show="activeCategory === 'Semua' && searchQuery === ''">Berita Lainnya</span>
                <span x-show="activeCategory !== 'Semua'" x-text="'Kategori: ' + activeCategory"></span>
                <span x-show="searchQuery.trim() !== ''" x-text="'Hasil pencarian: &quot;' + searchQuery + '&quot;'"></span>
            </h2>
            <span class="text-[12px] font-bold text-adzkia-muted" x-text="filteredNews.length + ' artikel'"></span>
        </div>

        {{-- ── GRID BERITA ── --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
            <template x-for="item in filteredNews" :key="item.id">
                <a :href="'/berita/' + item.slug"
                   class="group bg-white rounded-[1.5rem] overflow-hidden border border-gray-100 shadow-sm hover:shadow-xl hover:-translate-y-1 transition-all duration-300 flex flex-col">

                    {{-- Gambar --}}
                    <div class="relative w-full h-52 overflow-hidden shrink-0">
                        <span class="absolute top-3 left-3 z-10 bg-white/95 backdrop-blur-sm text-adzkia-blue text-[9px] font-black px-3 py-1 rounded-full uppercase tracking-widest shadow-sm"
                              x-text="item.category"></span>
                        <img :src="item.image" :alt="item.title"
                             class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                    </div>

                    {{-- Konten --}}
                    <div class="p-6 flex flex-col flex-grow">
                        <p class="text-[10px] font-extrabold text-adzkia-muted uppercase tracking-widest mb-2.5 flex items-center gap-1.5">
                            <i data-feather="calendar" class="w-3 h-3"></i>
                            <span x-text="item.date"></span>
                        </p>
                        <h3 class="text-[15px] font-extrabold text-adzkia-dark mb-3 line-clamp-2 leading-snug group-hover:text-adzkia-blue transition-colors"
                            x-text="item.title"></h3>
                        <p class="text-[13px] text-adzkia-muted font-medium leading-relaxed line-clamp-2 flex-grow mb-5"
                           x-text="item.excerpt || 'Klik untuk membaca berita selengkapnya.'"></p>
                        <div class="flex items-center justify-between pt-4 border-t border-gray-50">
                            <span class="text-adzkia-blue text-[12px] font-bold flex items-center gap-1.5 group-hover:gap-2.5 transition-all">
                                Baca Berita <i data-feather="arrow-right" class="w-3.5 h-3.5"></i>
                            </span>
                        </div>
                    </div>
                </a>
            </template>
        </div>

        {{-- ── EMPTY STATE ── --}}
        <div x-show="isDataEmpty" x-cloak class="text-center py-24">
            <div class="w-20 h-20 bg-gray-100 rounded-3xl flex items-center justify-center mx-auto mb-5 text-gray-300">
                <i data-feather="rss" class="w-9 h-9"></i>
            </div>
            <h3 class="text-xl font-extrabold text-adzkia-dark mb-2">
                <template x-if="searchQuery.trim() !== ''">
                    <span>Tidak ada hasil untuk "<span x-text="searchQuery" class="text-adzkia-blue"></span>"</span>
                </template>
                <template x-if="searchQuery.trim() === ''">
                    <span>Belum ada berita di kategori ini</span>
                </template>
            </h3>
            <p class="text-adzkia-muted font-medium text-[14px] mb-6">Coba kata kunci lain atau pilih kategori yang berbeda.</p>
            <button @click="searchQuery = ''; activeCategory = 'Semua'"
                    class="px-6 py-2.5 bg-adzkia-blue text-white rounded-full text-[13px] font-bold hover:bg-blue-700 transition-colors shadow-sm shadow-adzkia-blue/20">
                Tampilkan semua berita
            </button>
        </div>

    </div>
</div>

<script>
function halamanBerita() {
    const db   = @json($beritaData);
    const cats = ['Semua', ...new Set(db.map(b => b.category).filter(Boolean))];

    return {
        searchQuery:    '',
        activeCategory: 'Semua',
        categories:     cats.length > 1 ? cats : ['Semua', 'Akademik', 'Beasiswa', 'Kegiatan', 'Informasi'],
        berita:         db,

        get highlightNews() {
            return this.berita.find(b => b.isHighlight) ?? null;
        },

        get filteredNews() {
            let list = this.berita;

            // Sembunyikan highlight dari grid saat tidak ada filter aktif
            if (this.activeCategory === 'Semua' && this.searchQuery.trim() === '') {
                list = list.filter(b => !b.isHighlight);
            }

            if (this.activeCategory !== 'Semua') {
                list = list.filter(b => b.category === this.activeCategory);
            }

            if (this.searchQuery.trim() !== '') {
                const q = this.searchQuery.toLowerCase().trim();
                list = list.filter(b =>
                    b.title.toLowerCase().includes(q) ||
                    (b.excerpt && b.excerpt.toLowerCase().includes(q))
                );
            }

            return list;
        },

        get isDataEmpty() {
            if (this.activeCategory === 'Semua' && this.searchQuery.trim() === '') {
                return !this.highlightNews && this.filteredNews.length === 0;
            }
            return this.filteredNews.length === 0;
        },
    };
}

document.addEventListener('DOMContentLoaded', () => {
    if (typeof feather !== 'undefined') feather.replace();
});
</script>

@endsection