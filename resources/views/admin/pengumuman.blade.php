@extends('layouts.admin')

@section('admin-content')
<div x-data="manajemenPengumuman()">
    <div class="flex flex-col lg:flex-row justify-between items-start lg:items-center gap-6 mb-8">
        <div class="max-w-xl">
            <h1 class="text-3xl font-extrabold text-brand-dark tracking-tight mb-2">Pengumuman Kelulusan</h1>
            <p class="text-brand-gray text-[14px] font-medium leading-relaxed">
                Tentukan dan publikasikan hasil seleksi pendaftar untuk gelombang akademik 2024/2025.
            </p>
        </div>
        
        <div class="bg-brand-dark text-white p-6 rounded-2xl flex items-center justify-between w-full lg:w-[450px] shadow-xl relative overflow-hidden group">
            <div class="relative z-10">
                <p class="font-extrabold text-[14px] mb-1">Publikasi Hasil</p>
                <p class="text-[11px] text-gray-400">Hasil seleksi dapat segera diumumkan.</p>
            </div>
            <button class="relative z-10 bg-white text-brand-dark px-6 py-3 rounded-xl font-black text-[12px] hover:bg-gray-100 transition-all shadow-lg active:scale-95">
                Publish Sekarang
            </button>
            <div class="absolute -right-6 -bottom-6 opacity-10 text-white transform group-hover:scale-110 transition-transform">
                <i data-feather="megaphone" class="w-24 h-24"></i>
            </div>
        </div>
    </div>

    <div class="bg-white p-4 rounded-2xl shadow-sm border border-gray-100 mb-6 flex flex-wrap items-center gap-4">
        <div class="relative flex-grow max-w-md">
            <span class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-400">
                <i data-feather="search" class="w-4 h-4"></i>
            </span>
            <input type="text" x-model="searchQuery" placeholder="Cari nama atau nomor pendaftaran..." 
                   class="w-full pl-12 pr-4 py-2.5 bg-gray-50 border border-gray-100 rounded-xl text-[13px] outline-none focus:ring-2 focus:ring-brand-blue/10 transition-all">
        </div>
        
        <select x-model="filterStatus" class="px-4 py-2.5 bg-gray-50 border border-gray-100 rounded-xl text-[13px] font-bold text-brand-dark outline-none cursor-pointer">
            <option value="Semua Status">Semua Status</option>
            <option value="Lulus">Lulus</option>
            <option value="Tidak Lulus">Tidak Lulus</option>
            <option value="Belum Ditentukan">Belum Ditentukan</option>
        </select>
        
        <select x-model="filterProdi" class="px-4 py-2.5 bg-gray-50 border border-gray-100 rounded-xl text-[13px] font-bold text-brand-dark outline-none cursor-pointer">
            <option value="Semua Prodi">Semua Prodi</option>
            <option value="S1 Informatika">S1 Informatika</option>
            <option value="S1 Teknik Sipil">S1 Teknik Sipil</option>
        </select>
        
        <select x-model="filterJalur" class="px-4 py-2.5 bg-gray-50 border border-gray-100 rounded-xl text-[13px] font-bold text-brand-dark outline-none cursor-pointer">
            <option value="Semua Jalur">Semua Jalur</option>
            <option value="Mandiri">Mandiri</option>
            <option value="Prestasi">Prestasi</option>
        </select>
        
        <button @click="resetFilters()" class="p-2.5 bg-gray-50 border border-gray-100 rounded-xl text-brand-gray hover:bg-gray-100 transition-colors" title="Reset Filter">
            <i data-feather="refresh-cw" class="w-4 h-4"></i>
        </button>
    </div>

    <div x-show="selected.length > 0" 
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0 -translate-y-2"
         x-transition:enter-end="opacity-100 translate-y-0"
         class="bg-blue-50/50 border border-blue-100 p-4 rounded-2xl mb-6 flex items-center justify-between shadow-sm" x-cloak>
        <div class="flex items-center gap-6">
            <span class="text-[13px] font-bold text-brand-blue">
                <span x-text="selected.length"></span> Pendaftar Terpilih
            </span>
            <div class="flex gap-2">
                <button class="px-4 py-2 bg-green-600 text-white rounded-lg text-[11px] font-black uppercase flex items-center gap-2 hover:bg-green-700 transition-colors">
                    <i data-feather="check-circle" class="w-3.5 h-3.5"></i> Set Lulus
                </button>
                <button class="px-4 py-2 bg-red-600 text-white rounded-lg text-[11px] font-black uppercase flex items-center gap-2 hover:bg-red-700 transition-colors">
                    <i data-feather="x-circle" class="w-3.5 h-3.5"></i> Set Tidak Lulus
                </button>
            </div>
        </div>
        <button @click="selected = []; allSelected = false" class="text-[12px] font-bold text-brand-gray hover:text-brand-dark transition-colors">
            Batalkan Seleksi
        </button>
    </div>

    <div class="bg-white rounded-[2.5rem] shadow-sm border border-gray-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left whitespace-nowrap">
                <thead class="bg-white text-[11px] font-black text-brand-gray uppercase tracking-widest border-b border-gray-100">
                    <tr>
                        <th class="px-8 py-6 w-10">
                            <input type="checkbox" x-model="allSelected" @change="toggleAll()" class="rounded border-gray-300 text-brand-blue focus:ring-brand-blue">
                        </th>
                        <th class="px-4 py-6 text-brand-dark">Nama & No. Pendaftaran</th>
                        <th class="px-4 py-6">Program Studi</th>
                        <th class="px-4 py-6">Jalur</th>
                        <th class="px-4 py-6">Nilai Seleksi</th>
                        <th class="px-4 py-6">Status Kelulusan</th>
                        <th class="px-8 py-6 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50 text-[13px]">
                    <template x-for="item in filteredPendaftar" :key="item.id">
                        <tr class="hover:bg-gray-50/50 transition-colors">
                            <td class="px-8 py-5">
                                <input type="checkbox" x-model="selected" :value="item.id" class="rounded border-gray-300 text-brand-blue">
                            </td>
                            <td class="px-4 py-5">
                                <div class="flex items-center gap-3">
                                    <div class="w-9 h-9 rounded-full flex items-center justify-center font-black text-[10px]"
                                         :class="item.bgClass" x-text="item.initials"></div>
                                    <div class="flex flex-col">
                                        <span class="font-bold text-brand-dark" x-text="item.nama"></span>
                                        <span class="text-gray-400 text-[11px] font-bold tracking-tight" x-text="item.id"></span>
                                    </div>
                                </div>
                            </td>
                            <td class="px-4 py-5 font-bold text-gray-500" x-text="item.prodi"></td>
                            <td class="px-4 py-5">
                                <span class="px-2.5 py-1 rounded text-[10px] font-black uppercase tracking-widest"
                                      :class="item.jalur === 'Mandiri' ? 'bg-blue-50 text-brand-blue' : 'bg-brand-blue-light text-brand-blue'" 
                                      x-text="item.jalur"></span>
                            </td>
                            <td class="px-4 py-5 font-black text-brand-dark" x-text="item.nilai.toFixed(2)"></td>
                            <td class="px-4 py-5">
                                <span class="flex items-center gap-2 font-bold" 
                                      :class="{
                                          'text-gray-400': item.status === 'Belum Ditentukan',
                                          'text-green-600': item.status === 'Lulus',
                                          'text-red-500': item.status === 'Tidak Lulus'
                                      }">
                                    <div class="w-1.5 h-1.5 rounded-full"
                                         :class="{
                                             'bg-gray-300': item.status === 'Belum Ditentukan',
                                             'bg-green-500': item.status === 'Lulus',
                                             'bg-red-500': item.status === 'Tidak Lulus'
                                         }"></div> 
                                    <span x-text="item.status"></span>
                                </span>
                            </td>
                            <td class="px-8 py-5 text-center">
                                <template x-if="item.status === 'Belum Ditentukan'">
                                    <button @click="bukaModal(item)" class="px-5 py-2.5 bg-brand-blue-light text-brand-blue rounded-xl font-black text-[11px] hover:bg-brand-blue hover:text-white transition-all shadow-sm">
                                        Tentukan
                                    </button>
                                </template>
                                <template x-if="item.status !== 'Belum Ditentukan'">
                                    <button @click="bukaModal(item)" class="p-2 text-gray-300 hover:text-brand-dark transition-colors" title="Edit Hasil">
                                        <i data-feather="edit-3" class="w-4 h-4"></i>
                                    </button>
                                </template>
                            </td>
                        </tr>
                    </template>
                    
                    <tr x-show="filteredPendaftar.length === 0" x-cloak>
                        <td colspan="7" class="px-8 py-10 text-center text-gray-400 font-bold">
                            Data pendaftar tidak ditemukan dengan filter saat ini.
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
        
        <div class="px-8 py-6 bg-gray-50/50 flex justify-between items-center border-t border-gray-100">
            <span class="text-[12px] font-bold text-gray-400">Menampilkan hasil pencarian</span>
        </div>
    </div>

    <template x-teleport="body">
        <div x-show="modalOpen" class="fixed inset-0 z-[999] flex items-center justify-center p-4" x-cloak>
            <div x-show="modalOpen" x-transition.opacity @click="modalOpen = false" class="absolute inset-0 bg-brand-dark/70 backdrop-blur-sm"></div>
            
            <div x-show="modalOpen" 
                 x-transition:enter="transition ease-out duration-300"
                 x-transition:enter-start="opacity-0 scale-95 translate-y-4"
                 x-transition:enter-end="opacity-100 scale-100 translate-y-0"
                 class="bg-white w-full max-w-lg rounded-[2.5rem] shadow-2xl relative z-10 overflow-hidden">
                
                <div class="p-10">
                    <div class="text-center mb-10">
                        <div class="w-20 h-20 bg-brand-blue-light text-brand-blue rounded-3xl flex items-center justify-center mx-auto mb-6 shadow-sm">
                            <i data-feather="award" class="w-10 h-10"></i>
                        </div>
                        <h2 class="text-2xl font-black text-brand-dark tracking-tight">Tentukan Kelulusan</h2>
                        <p class="text-brand-gray text-sm font-medium mt-1">Status seleksi untuk pendaftar berikut</p>
                    </div>

                    <div class="space-y-4 mb-10">
                        <div class="flex justify-between p-5 bg-gray-50 rounded-2xl border border-gray-100">
                            <div>
                                <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1">Nama Pendaftar</p>
                                <p class="text-[14px] font-extrabold text-brand-dark" x-text="dataSiswa.nama"></p>
                            </div>
                            <div class="text-right">
                                <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1">Nilai Seleksi</p>
                                <p class="text-xl font-black text-brand-blue" x-text="dataSiswa.nilai"></p>
                            </div>
                        </div>
                        <div class="p-5 bg-gray-50 rounded-2xl border border-gray-100">
                            <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1">Program Studi</p>
                            <p class="text-[14px] font-extrabold text-brand-dark" x-text="dataSiswa.prodi"></p>
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <button @click="simpanStatus('Tidak Lulus')" class="py-4 bg-red-600 text-white rounded-2xl font-black text-[12px] uppercase tracking-widest hover:bg-red-700 shadow-xl shadow-red-600/20 transition-all active:scale-95">TIDAK LULUS</button>
                        <button @click="simpanStatus('Lulus')" class="py-4 bg-green-600 text-white rounded-2xl font-black text-[12px] uppercase tracking-widest hover:bg-green-700 shadow-xl shadow-green-600/20 transition-all active:scale-95">SET LULUS</button>
                    </div>
                </div>

                <button @click="modalOpen = false" class="absolute top-6 right-6 text-gray-300 hover:text-brand-dark transition-colors">
                    <i data-feather="x" class="w-6 h-6"></i>
                </button>
            </div>
        </div>
    </template>
</div>

<script>
document.addEventListener('alpine:init', () => {
    Alpine.data('manajemenPengumuman', () => ({
        searchQuery: '',
        filterStatus: 'Semua Status',
        filterProdi: 'Semua Prodi',
        filterJalur: 'Semua Jalur',
        
        selected: [],
        allSelected: false,
        modalOpen: false,
        dataSiswa: { id: '', nama: '', prodi: '', nilai: 0 },
        
        pendaftar: [
            { id: 'REG-2024-00129', nama: 'Farhan Adi Pratama', prodi: 'S1 Informatika', jalur: 'Mandiri', nilai: 88.50, status: 'Belum Ditentukan', initials: 'FP', bgClass: 'bg-blue-50 text-brand-blue' },
            { id: 'REG-2024-00135', nama: 'Anisa Salsabila', prodi: 'S1 Teknik Sipil', jalur: 'Prestasi', nilai: 92.15, status: 'Lulus', initials: 'AS', bgClass: 'bg-amber-50 text-amber-600' },
            { id: 'REG-2024-00142', nama: 'Bambang Wijaya', prodi: 'S1 Informatika', jalur: 'Mandiri', nilai: 65.00, status: 'Tidak Lulus', initials: 'BW', bgClass: 'bg-indigo-50 text-indigo-600' },
            { id: 'REG-2024-00150', nama: 'Citra Kirana', prodi: 'S1 Teknik Sipil', jalur: 'Prestasi', nilai: 89.00, status: 'Belum Ditentukan', initials: 'CK', bgClass: 'bg-pink-50 text-pink-600' }
        ],
        
        get filteredPendaftar() {
            let result = this.pendaftar;
            
            if (this.searchQuery) {
                const q = this.searchQuery.toLowerCase();
                result = result.filter(p => p.nama.toLowerCase().includes(q) || p.id.toLowerCase().includes(q));
            }
            if (this.filterStatus !== 'Semua Status') {
                result = result.filter(p => p.status === this.filterStatus);
            }
            if (this.filterProdi !== 'Semua Prodi') {
                result = result.filter(p => p.prodi === this.filterProdi);
            }
            if (this.filterJalur !== 'Semua Jalur') {
                result = result.filter(p => p.jalur === this.filterJalur);
            }
            
            // Refresh Feather icons setelah re-render Alpine
            setTimeout(() => { if(window.feather) feather.replace(); }, 50);
            return result;
        },
        
        resetFilters() {
            this.searchQuery = '';
            this.filterStatus = 'Semua Status';
            this.filterProdi = 'Semua Prodi';
            this.filterJalur = 'Semua Jalur';
        },
        
        toggleAll() {
            if (this.allSelected) {
                this.selected = this.filteredPendaftar.map(p => p.id);
            } else {
                this.selected = [];
            }
        },
        
        bukaModal(siswa) {
            this.dataSiswa = siswa;
            this.modalOpen = true;
        },
        
        simpanStatus(statusBaru) {
            // Update status siswa di local state array
            const index = this.pendaftar.findIndex(p => p.id === this.dataSiswa.id);
            if (index !== -1) {
                this.pendaftar[index].status = statusBaru;
            }
            this.modalOpen = false;
        }
    }));
});
</script>
@endsection