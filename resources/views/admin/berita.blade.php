@extends('layouts.admin')

@section('admin-content')
<div x-data="{ 
    searchQuery: '', 
    filterStatus: 'Semua Status', 
    filterKategori: 'Kategori',
    beritaList: @js($data), // Memuat semua data database ke dalam JavaScript
    
    // Fungsi untuk memfilter data secara dinamis
    get filteredBerita() {
        return this.beritaList.filter(item => {
            const matchesSearch = item.judul.toLowerCase().includes(this.searchQuery.toLowerCase()) || 
                                  (item.ringkasan && item.ringkasan.toLowerCase().includes(this.searchQuery.toLowerCase()));
            const matchesStatus = this.filterStatus === 'Semua Status' || item.status === this.filterStatus;
            const matchesKategori = this.filterKategori === 'Kategori' || item.kategori === this.filterKategori;
            
            return matchesSearch && matchesStatus && matchesKategori;
        });
    },

    resetFilters() {
        this.searchQuery = '';
        this.filterStatus = 'Semua Status';
        this.filterKategori = 'Kategori';
    },
    
    formatDate(dateString) {
        if(!dateString) return '-';
        const options = { day: '2-digit', month: 'short', year: 'numeric' };
        return new Date(dateString).toLocaleDateString('id-ID', options);
    }
}">
    
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-8 gap-4">
        <div class="max-w-xl">
            <h1 class="text-3xl font-extrabold text-brand-dark tracking-tight mb-2">Manajemen Berita</h1>
            <p class="text-brand-gray text-[14px] font-medium leading-relaxed">
                Kelola pengumuman kampus, berita akademik, dan artikel informatif.
            </p>
        </div>
        <a href="{{ route('admin.berita.create') }}" class="flex items-center gap-2 px-6 py-3 bg-brand-dark text-white rounded-xl font-bold text-[13px] hover:bg-brand-blue transition-all shadow-lg shadow-brand-dark/20 active:scale-95">
            <i data-feather="plus" class="w-4 h-4"></i> Tambah Berita
        </a>
    </div>

    <div class="bg-white p-4 rounded-2xl shadow-sm border border-gray-100 mb-6 flex flex-wrap items-center gap-4">
        <div class="relative flex-grow max-w-md">
            <span class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-400">
                <i data-feather="search" class="w-4 h-4"></i>
            </span>
            <input type="text" x-model="searchQuery" placeholder="Cari judul atau ringkasan berita..." 
                   class="w-full pl-12 pr-4 py-2.5 bg-white border border-gray-100 rounded-xl text-[13px] outline-none focus:ring-2 focus:ring-brand-blue/10 transition-all shadow-sm">
        </div>
        
        <select x-model="filterStatus" class="px-4 py-2.5 bg-white border border-gray-100 rounded-xl text-[13px] font-bold text-brand-dark outline-none cursor-pointer shadow-sm">
            <option value="Semua Status">Semua Status</option>
            <option value="Published">Published</option>
            <option value="Draft">Draft</option>
        </select>
        
        <select x-model="filterKategori" class="px-4 py-2.5 bg-white border border-gray-100 rounded-xl text-[13px] font-bold text-brand-dark outline-none cursor-pointer shadow-sm">
            <option value="Kategori">Kategori</option>
            <option value="Akademik">Akademik</option>
            <option value="Beasiswa">Beasiswa</option>
            <option value="Kegiatan">Kegiatan</option>
            <option value="Informasi">Informasi</option>
        </select>
        
        <button @click="resetFilters()" class="p-2.5 bg-white border border-gray-100 rounded-xl text-brand-dark hover:bg-gray-50 shadow-sm transition-colors" title="Reset Filter">
            <i data-feather="refresh-cw" class="w-4 h-4"></i>
        </button>
    </div>

    <div class="bg-white rounded-[2rem] shadow-sm border border-gray-100 overflow-hidden mb-8">
        <div class="overflow-x-auto">
            <table class="w-full text-left whitespace-nowrap">
                <thead class="bg-white text-[11px] font-black text-brand-gray uppercase tracking-widest border-b border-gray-100">
                    <tr>
                        <th class="px-8 py-6 text-brand-dark">Berita</th>
                        <th class="px-4 py-6">Kategori</th>
                        <th class="px-4 py-6">Tanggal Publish</th>
                        <th class="px-4 py-6">Status</th>
                        <th class="px-8 py-6 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50 text-[13px]">
                    
                    <template x-for="item in filteredBerita" :key="item.id">
                        <tr class="hover:bg-gray-50/50 transition-colors group">
                            
                            <td class="px-8 py-5">
                                <div class="flex items-center gap-4">
                                    <img :src="item.thumbnail ? `/uploads/berita/${item.thumbnail}` : 'https://images.unsplash.com/photo-1523050854058-8df90110c9f1?q=80&w=100'" 
                                         alt="Thumbnail" 
                                         class="w-14 h-14 rounded-xl object-cover border border-gray-100 shadow-sm"
                                         :class="item.status === 'Draft' ? 'opacity-50 grayscale' : ''">
                                    <div class="flex flex-col whitespace-normal max-w-sm">
                                        <span class="font-extrabold text-brand-dark text-[14px] leading-tight mb-1" x-text="item.judul"></span>
                                        <span class="text-gray-400 text-[11px] font-medium leading-snug line-clamp-1" x-text="item.ringkasan"></span>
                                    </div>
                                </div>
                            </td>
                            
                            <td class="px-4 py-5">
                                <span class="px-3 py-1.5 rounded-lg text-[10px] font-black uppercase tracking-widest"
                                      :class="{
                                          'bg-green-50 text-green-600': item.kategori === 'Beasiswa',
                                          'bg-blue-50 text-brand-blue': item.kategori === 'Akademik',
                                          'bg-purple-50 text-purple-600': item.kategori !== 'Beasiswa' && item.kategori !== 'Akademik'
                                      }" x-text="item.kategori">
                                </span>
                            </td>
                            
                            <td class="px-4 py-5 font-bold text-gray-600" x-text="formatDate(item.tanggal_publish || item.created_at)"></td>
                            
                            <td class="px-4 py-5">
                                <span class="flex items-center gap-2 font-bold text-[12px]" :class="item.status === 'Published' ? 'text-green-600' : 'text-gray-400'">
                                    <div class="w-1.5 h-1.5 rounded-full" :class="item.status === 'Published' ? 'bg-green-500' : 'bg-gray-300'"></div> 
                                    <span x-text="item.status"></span>
                                </span>
                            </td>
                            
                            <td class="px-8 py-5 text-center">
                                <div class="flex items-center justify-center gap-3">
                                    <a :href="`/admin/berita/${item.id}/edit`" class="text-gray-400 hover:text-brand-dark transition-colors" title="Edit Berita">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17 3a2.828 2.828 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5L17 3z"></path></svg>
                                    </a>
                                    
                                    <form :action="`/admin/berita/${item.id}`" method="POST" onsubmit="return confirm('Hapus berita ini selamanya?')" class="inline-block m-0 p-0">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="text-gray-400 hover:text-red-500 transition-colors" title="Hapus Berita">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path><line x1="10" y1="11" x2="10" y2="17"></line><line x1="14" y1="11" x2="14" y2="17"></line></svg>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    </template>
                    
                    <tr x-show="filteredBerita.length === 0" x-cloak>
                        <td colspan="5" class="px-8 py-10 text-center text-gray-400 font-bold">
                            Tidak ada berita yang ditemukan.
                        </td>
                    </tr>

                </tbody>
            </table>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="bg-[#EAF2FF] p-6 rounded-[2rem] border border-[#D1E3FF] flex items-center gap-6 shadow-sm">
            <div class="w-14 h-14 bg-white rounded-2xl flex items-center justify-center text-brand-blue shadow-sm"><i data-feather="file-text" class="w-6 h-6"></i></div>
            <div>
                <p class="text-[10px] font-extrabold text-brand-blue uppercase tracking-widest mb-1">Total Artikel</p>
                <h3 class="text-3xl font-black text-[#0B1C39] leading-none">{{ $data->count() }}</h3>
            </div>
        </div>
        <div class="bg-white p-6 rounded-[2rem] border border-gray-100 flex items-center gap-6 shadow-sm">
            <div class="w-14 h-14 bg-green-50 rounded-2xl flex items-center justify-center text-green-500 border border-gray-100 shadow-sm"><i data-feather="check-circle" class="w-6 h-6"></i></div>
            <div>
                <p class="text-[10px] font-extrabold text-gray-400 uppercase tracking-widest mb-1">Berita Aktif</p>
                <h3 class="text-3xl font-black text-[#0B1C39] leading-none">{{ $data->where('status', 'Published')->count() }}</h3>
            </div>
        </div>
        <div class="bg-[#FFEAEA] p-6 rounded-[2rem] border border-[#FFD1D1] flex items-center gap-6 shadow-sm">
            <div class="w-14 h-14 bg-white rounded-2xl flex items-center justify-center text-red-600 shadow-sm"><i data-feather="edit" class="w-6 h-6"></i></div>
            <div>
                <p class="text-[10px] font-extrabold text-red-800 uppercase tracking-widest mb-1">Draft Menunggu</p>
                <h3 class="text-3xl font-black text-red-950 leading-none">{{ $data->where('status', 'Draft')->count() }}</h3>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener("DOMContentLoaded", () => {
        setTimeout(() => { if (typeof feather !== 'undefined') feather.replace(); }, 50);
    });
</script>
@endsection