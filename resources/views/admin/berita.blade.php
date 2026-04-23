@extends('layouts.admin')

@section('admin-content')
<div x-data="manajemenBerita()">
    
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-8 gap-4">
        <div class="max-w-xl">
            <h1 class="text-3xl font-extrabold text-brand-dark tracking-tight mb-2">Manajemen Berita</h1>
            <p class="text-brand-gray text-[14px] font-medium leading-relaxed">
                Kelola pengumuman kampus, berita akademik, dan artikel informatif untuk calon mahasiswa baru.
            </p>
        </div>
        <a href="/admin/berita/create" class="flex items-center gap-2 px-6 py-3 bg-brand-dark text-white rounded-xl font-bold text-[13px] hover:bg-brand-blue transition-all shadow-lg shadow-brand-dark/20 active:scale-95">
            <i data-feather="plus" class="w-4 h-4"></i> Tambah Berita
        </a>
    </div>

    <div class="bg-white p-4 rounded-2xl shadow-sm border border-gray-100 mb-6 flex flex-wrap items-center gap-4">
        <div class="relative flex-grow max-w-md">
            <span class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-400">
                <i data-feather="search" class="w-4 h-4"></i>
            </span>
            <input type="text" x-model="searchQuery" placeholder="Cari judul berita..." 
                   class="w-full pl-12 pr-4 py-2.5 bg-white border border-gray-100 rounded-xl text-[13px] outline-none focus:ring-2 focus:ring-brand-blue/10 transition-all shadow-sm">
        </div>
        
        <select x-model="filterStatus" class="px-4 py-2.5 bg-white border border-gray-100 rounded-xl text-[13px] font-bold text-brand-dark outline-none cursor-pointer shadow-sm">
            <option value="Semua Status">Semua Status</option>
            <option value="Published">Published</option>
            <option value="Draft">Draft</option>
        </select>
        
        <select x-model="filterKategori" class="px-4 py-2.5 bg-white border border-gray-100 rounded-xl text-[13px] font-bold text-brand-dark outline-none cursor-pointer shadow-sm">
            <option value="Kategori">Kategori</option>
            <option value="Beasiswa">Beasiswa</option>
            <option value="Akademik">Akademik</option>
            <option value="Kegiatan">Kegiatan</option>
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
                                    <img :src="item.image" alt="Thumbnail" class="w-14 h-14 rounded-xl object-cover border border-gray-100 shadow-sm" :class="item.status === 'Draft' ? 'opacity-60' : ''">
                                    <div class="flex flex-col whitespace-normal max-w-sm">
                                        <span class="font-extrabold text-brand-dark text-[14px] leading-tight mb-1" x-text="item.judul"></span>
                                        <span class="text-gray-400 text-[11px] font-medium leading-snug line-clamp-1" x-text="item.excerpt"></span>
                                    </div>
                                </div>
                            </td>
                            <td class="px-4 py-5">
                                <span class="px-3 py-1.5 rounded-lg text-[10px] font-black uppercase tracking-widest"
                                      :class="{
                                          'bg-blue-50 text-brand-blue': item.kategori === 'Beasiswa' || item.kategori === 'Akademik',
                                          'bg-purple-50 text-purple-600': item.kategori === 'Kegiatan'
                                      }" x-text="item.kategori"></span>
                            </td>
                            <td class="px-4 py-5 font-bold text-gray-600" x-text="item.tanggal"></td>
                            <td class="px-4 py-5">
                                <span class="flex items-center gap-2 font-bold text-[12px]" :class="item.status === 'Published' ? 'text-green-600' : 'text-gray-400'">
                                    <div class="w-1.5 h-1.5 rounded-full" :class="item.status === 'Published' ? 'bg-green-500' : 'bg-gray-300'"></div> 
                                    <span x-text="item.status"></span>
                                </span>
                            </td>
                            <td class="px-8 py-5 text-center">
                                <div class="flex items-center justify-center gap-3">
                                    <a href="/admin/berita/create" class="text-gray-400 hover:text-brand-dark transition-colors" title="Edit"><i data-feather="edit-2" class="w-4 h-4"></i></a>
                                    
                                    <template x-if="item.status === 'Draft'">
                                        <button class="text-brand-blue hover:text-brand-dark transition-colors" title="Publish"><i data-feather="upload" class="w-4 h-4"></i></button>
                                    </template>
                                    <template x-if="item.status === 'Published'">
                                        <button class="text-gray-400 hover:text-brand-dark transition-colors" title="Sembunyikan"><i data-feather="eye-off" class="w-4 h-4"></i></button>
                                    </template>
                                    
                                    <button class="text-gray-400 hover:text-red-500 transition-colors" title="Hapus"><i data-feather="trash-2" class="w-4 h-4"></i></button>
                                </div>
                            </td>
                        </tr>
                    </template>
                    
                    <tr x-show="filteredBerita.length === 0">
                        <td colspan="5" class="px-8 py-10 text-center text-gray-400 font-bold">
                            Tidak ada berita yang cocok dengan filter pencarian Anda.
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="bg-[#EAF2FF] p-6 rounded-[2rem] border border-[#D1E3FF] flex items-center gap-6 shadow-sm">
            <div class="w-14 h-14 bg-white rounded-2xl flex items-center justify-center text-brand-blue shadow-sm"><i data-feather="file-text" class="w-6 h-6"></i></div>
            <div><p class="text-[10px] font-extrabold text-brand-blue uppercase tracking-widest mb-1">Total Artikel</p><h3 class="text-3xl font-black text-[#0B1C39] leading-none" x-text="berita.length"></h3></div>
        </div>
        <div class="bg-white p-6 rounded-[2rem] border border-gray-100 flex items-center gap-6 shadow-sm">
            <div class="w-14 h-14 bg-gray-50 rounded-2xl flex items-center justify-center text-gray-400 border border-gray-100 shadow-sm"><i data-feather="eye" class="w-6 h-6"></i></div>
            <div><p class="text-[10px] font-extrabold text-gray-400 uppercase tracking-widest mb-1">Total Pembaca</p><h3 class="text-3xl font-black text-[#0B1C39] leading-none">12.5k</h3></div>
        </div>
        <div class="bg-[#FFEAEA] p-6 rounded-[2rem] border border-[#FFD1D1] flex items-center gap-6 shadow-sm">
            <div class="w-14 h-14 bg-white rounded-2xl flex items-center justify-center text-red-600 shadow-sm"><i data-feather="edit" class="w-6 h-6"></i></div>
            <div><p class="text-[10px] font-extrabold text-red-800 uppercase tracking-widest mb-1">Draft Menunggu</p><h3 class="text-3xl font-black text-red-950 leading-none" x-text="berita.filter(b => b.status === 'Draft').length"></h3></div>
        </div>
    </div>
</div>

<script>
document.addEventListener('alpine:init', () => {
    Alpine.data('manajemenBerita', () => ({
        searchQuery: '',
        filterStatus: 'Semua Status',
        filterKategori: 'Kategori',
        berita: [
            { id: 1, judul: 'Pendaftaran Beasiswa Unggul Semester Ganjil 2024', excerpt: 'Dibuka kuota terbatas untuk mahasiswa baru yang berprestasi di bidang akademik maupun non-akademik.', kategori: 'Beasiswa', tanggal: '12 Okt 2023', status: 'Published', image: 'https://images.unsplash.com/photo-1523240795612-9a054b0db644?auto=format&fit=crop&w=150&q=80' },
            { id: 2, judul: 'Panduan Tes Potensi Akademik Jalur Mandiri', excerpt: 'Materi yang akan diujikan mencakup penalaran logis, numerik, dan pemahaman bacaan.', kategori: 'Akademik', tanggal: '10 Okt 2023', status: 'Draft', image: 'https://images.unsplash.com/photo-1573164713988-8665fc963095?auto=format&fit=crop&w=150&q=80' },
            { id: 3, judul: 'Puncak Perayaan Dies Natalis Ke-50', excerpt: 'Menampilkan pertunjukan seni dari berbagai unit kegiatan mahasiswa dan konser musik.', kategori: 'Kegiatan', tanggal: '05 Okt 2023', status: 'Published', image: 'https://images.unsplash.com/photo-1528605248644-14dd04022da1?auto=format&fit=crop&w=150&q=80' }
        ],
        
        get filteredBerita() {
            let filtered = this.berita;
            
            // Filter Pencarian Text
            if (this.searchQuery) {
                const q = this.searchQuery.toLowerCase();
                filtered = filtered.filter(b => b.judul.toLowerCase().includes(q) || b.excerpt.toLowerCase().includes(q));
            }
            // Filter Status
            if (this.filterStatus !== 'Semua Status') {
                filtered = filtered.filter(b => b.status === this.filterStatus);
            }
            // Filter Kategori
            if (this.filterKategori !== 'Kategori') {
                filtered = filtered.filter(b => b.kategori === this.filterKategori);
            }
            
            // Panggil render icons setiap kali list berubah
            setTimeout(() => { if(window.feather) feather.replace(); }, 50);
            
            return filtered;
        },

        resetFilters() {
            this.searchQuery = '';
            this.filterStatus = 'Semua Status';
            this.filterKategori = 'Kategori';
        }
    }));
});
</script>
@endsection