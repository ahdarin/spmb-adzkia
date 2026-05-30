@extends('layouts.admin')

@section('admin-content')
<div x-data="manajemenPengumuman()">
    <div class="flex flex-col lg:flex-row justify-between items-start lg:items-center gap-6 mb-8">
        <div class="max-w-xl">
            <h1 class="text-3xl font-extrabold text-brand-dark tracking-tight mb-2">Pengumuman Kelulusan</h1>
            <p class="text-brand-gray text-[14px] font-medium leading-relaxed">
                Tentukan dan publikasikan hasil seleksi pendaftar untuk gelombang akademik berjalan.
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
        
        <button @click="resetFilters()" class="p-2.5 bg-gray-50 border border-gray-100 rounded-xl text-brand-gray hover:bg-gray-100 transition-colors" title="Reset Filter">
            <i data-feather="refresh-cw" class="w-4 h-4"></i>
        </button>
    </div>

    {{-- Alert Notifikasi --}}
    @if(session('success'))
        <div class="mb-6 p-4 bg-green-50 border-l-4 border-green-500 rounded-r-xl flex items-start gap-3">
            <i data-feather="check-circle" class="w-5 h-5 text-green-600 shrink-0"></i>
            <p class="text-[13px] font-bold text-green-800">{{ session('success') }}</p>
        </div>
    @endif

    <div x-show="selected.length > 0" x-transition.opacity class="bg-blue-50/50 border border-blue-100 p-4 rounded-2xl mb-6 flex items-center justify-between shadow-sm" x-cloak>
        <div class="flex items-center gap-6">
            <span class="text-[13px] font-bold text-brand-blue">
                <span x-text="selected.length"></span> Pendaftar Terpilih
            </span>
            <div class="flex gap-2">
                <button class="px-4 py-2 bg-green-600 text-white rounded-lg text-[11px] font-black uppercase flex items-center gap-2 hover:bg-green-700 transition-colors shadow-sm shadow-green-600/20">
                    <i data-feather="check-circle" class="w-3.5 h-3.5"></i> Set Lulus
                </button>
                <button class="px-4 py-2 bg-red-600 text-white rounded-lg text-[11px] font-black uppercase flex items-center gap-2 hover:bg-red-700 transition-colors shadow-sm shadow-red-600/20">
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
                            <input type="checkbox" x-model="allSelected" @change="toggleAll()" class="rounded border-gray-300 text-brand-blue">
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
                    @foreach($pengumuman as $p)
                    <tr x-show="matchesFilter('{{ $p->nama_lengkap }}', '{{ $p->no_pendaftaran }}', '{{ $p->status_kelulusan ?? 'Belum Ditentukan' }}')" 
                        class="hover:bg-gray-50/50 transition-colors">
                        <td class="px-8 py-5">
                            <input type="checkbox" x-model="selected" value="{{ $p->id }}" class="rounded border-gray-300 text-brand-blue">
                        </td>
                        <td class="px-4 py-5">
                            <div class="flex items-center gap-3">
                                <div class="w-9 h-9 rounded-full bg-blue-50 text-brand-blue flex items-center justify-center font-black text-[10px]">
                                    {{ strtoupper(substr($p->nama_lengkap, 0, 2)) }}
                                </div>
                                <div class="flex flex-col">
                                    <span class="font-bold text-brand-dark">{{ $p->nama_lengkap }}</span>
                                    <span class="text-gray-400 text-[11px] font-bold tracking-tight">{{ $p->no_pendaftaran }}</span>
                                </div>
                            </div>
                        </td>
                        <td class="px-4 py-5 font-bold text-gray-500">{{ $p->pilihan_jurusan_1 ?? '-' }}</td>
                        <td class="px-4 py-5">
                            <span class="px-2.5 py-1 rounded text-[10px] font-black uppercase tracking-widest bg-blue-50 text-brand-blue">
                                {{ Str::limit($p->jalur_pendaftaran, 15) }}
                            </span>
                        </td>
                        <td class="px-4 py-5 font-black text-brand-dark">{{ number_format($p->nilai_akhir ?? 0, 2) }}</td>
                        <td class="px-4 py-5">
                            @if($p->status_kelulusan == 'Lulus')
                                <span class="flex items-center gap-2 font-bold text-green-600">
                                    <div class="w-1.5 h-1.5 rounded-full bg-green-500"></div> Lulus
                                </span>
                            @elseif($p->status_kelulusan == 'Tidak Lulus')
                                <span class="flex items-center gap-2 font-bold text-red-500">
                                    <div class="w-1.5 h-1.5 rounded-full bg-red-500"></div> Tidak Lulus
                                </span>
                            @else
                                <span class="flex items-center gap-2 font-bold text-gray-400">
                                    <div class="w-1.5 h-1.5 rounded-full bg-gray-300"></div> Belum Ditentukan
                                </span>
                            @endif
                        </td>
                        <td class="px-8 py-5 text-center">
                            <button @click="bukaModal({
                                id: '{{ $p->id }}',
                                nama: '{{ $p->nama_lengkap }}',
                                prodi: '{{ $p->pilihan_jurusan_1 }}',
                                nilai: '{{ number_format($p->nilai_akhir ?? 0, 2) }}',
                                url: '{{ route('admin.update-kelulusan', $p->id) }}'
                            })" class="px-5 py-2.5 bg-brand-blue-light text-brand-blue rounded-xl font-black text-[11px] hover:bg-brand-blue hover:text-white transition-all shadow-sm">
                                @if(!$p->status_kelulusan || $p->status_kelulusan == 'Belum Ditentukan') Tentukan @else <i data-feather="edit-3" class="w-4 h-4 mx-auto"></i> @endif
                            </button>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            
            @if($pengumuman->isEmpty())
                <div class="p-10 text-center text-gray-400 font-bold">
                    <i data-feather="inbox" class="w-10 h-10 mx-auto mb-3 opacity-50"></i>
                    Belum ada pendaftar yang menyelesaikan tahap verifikasi berkas.
                </div>
            @endif
        </div>
    </div>

    <template x-teleport="body">
        <div x-show="modalOpen" class="fixed inset-0 z-[999] flex items-center justify-center p-4" x-cloak>
            <div x-show="modalOpen" x-transition.opacity @click="modalOpen = false" class="absolute inset-0 bg-brand-dark/70 backdrop-blur-sm"></div>
            
            <div x-show="modalOpen" x-transition:enter="transition ease-out duration-300" 
                 x-transition:enter-start="opacity-0 scale-95 translate-y-4"
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
                                <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1">Nilai Akhir</p>
                                <p class="text-xl font-black text-brand-blue" x-text="dataSiswa.nilai"></p>
                            </div>
                        </div>
                        <div class="p-5 bg-gray-50 rounded-2xl border border-gray-100">
                            <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1">Pilihan Program Studi</p>
                            <p class="text-[14px] font-extrabold text-brand-dark" x-text="dataSiswa.prodi"></p>
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <form :action="dataSiswa.url" method="POST">
                            @csrf
                            <input type="hidden" name="status_kelulusan" value="Tidak Lulus">
                            <button type="submit" class="w-full py-4 bg-red-600 text-white rounded-2xl font-black text-[12px] uppercase tracking-widest hover:bg-red-700 shadow-xl shadow-red-600/20 transition-all active:scale-95">TIDAK LULUS</button>
                        </form>
                        <form :action="dataSiswa.url" method="POST">
                            @csrf
                            <input type="hidden" name="status_kelulusan" value="Lulus">
                            <button type="submit" class="w-full py-4 bg-green-600 text-white rounded-2xl font-black text-[12px] uppercase tracking-widest hover:bg-green-700 shadow-xl shadow-green-600/20 transition-all active:scale-95">SET LULUS</button>
                        </form>
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
        selected: [],
        allSelected: false,
        modalOpen: false,
        dataSiswa: { id: '', nama: '', prodi: '', nilai: 0, url: '' },

        matchesFilter(nama, no_reg, status) {
            const q = this.searchQuery.toLowerCase();
            const matchSearch = nama.toLowerCase().includes(q) || no_reg.toLowerCase().includes(q);
            const matchStatus = this.filterStatus === 'Semua Status' || status === this.filterStatus;
            return matchSearch && matchStatus;
        },

        toggleAll() {
            if (this.allSelected) {
                this.selected = Array.from(document.querySelectorAll('input[type="checkbox"][value]'))
                                     .map(el => el.value);
            } else {
                this.selected = [];
            }
        },

        resetFilters() {
            this.searchQuery = '';
            this.filterStatus = 'Semua Status';
        },

        bukaModal(siswa) {
            this.dataSiswa = siswa;
            this.modalOpen = true;
            setTimeout(() => { if(window.feather) feather.replace(); }, 10);
        }
    }));
});
</script>
@endsection