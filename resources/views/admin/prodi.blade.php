@extends('layouts.admin')

@section('admin-content')
<div x-data="prodiManager()">
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-10 gap-4">
        <div>
            <h1 class="text-3xl font-extrabold text-brand-dark tracking-tight mb-2">Manajemen Program Studi</h1>
            <p class="text-brand-gray text-[14px] font-medium">Kelola daftar program studi, akreditasi, dan daya tampung mahasiswa.</p>
        </div>
        <button @click="openAddModal()" class="flex items-center gap-2 px-6 py-3 bg-brand-dark text-white rounded-2xl font-bold text-[13px] hover:bg-brand-blue transition-all shadow-lg shadow-brand-dark/10">
            <i data-feather="plus" class="w-4 h-4"></i> Tambah Prodi Baru
        </button>
    </div>

    <div class="bg-white rounded-[2.5rem] shadow-sm border border-gray-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left min-w-[600px]">
                <thead class="bg-gray-50/50 text-[11px] font-black text-brand-gray uppercase tracking-widest border-b">
                    <tr>
                        <th class="px-5 py-4">Program Studi</th>
                        <th class="px-3 py-4">Jenjang</th>
                        <th class="px-3 py-4">Akreditasi</th>
                        <th class="px-3 py-4 text-center">Kuota</th>
                        <th class="px-3 py-4">Biaya/Smt</th>
                        <th class="px-5 py-4 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50 text-[13px]">
                    @forelse($data as $item)
                    <tr class="hover:bg-gray-50/50 transition-colors group">
                        <td class="px-5 py-4">
                            <div class="flex items-center gap-3">
                                <div class="w-9 h-9 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center shrink-0">
                                    <i data-feather="{{ $item->icon ?? 'book' }}" class="w-4 h-4"></i>
                                </div>
                                <span class="font-bold text-brand-dark leading-snug">{{ $item->nama }}</span>
                            </div>
                        </td>
                        <td class="px-3 py-4 font-bold text-gray-500">{{ $item->jenjang }}</td>
                        <td class="px-3 py-4">
                            <span class="px-2.5 py-1 {{ $item->akreditasi == 'Unggul' ? 'bg-green-50 text-green-600' : 'bg-blue-50 text-blue-600' }} rounded-lg text-[10px] font-black uppercase whitespace-nowrap">
                                {{ $item->akreditasi }}
                            </span>
                        </td>
                        <td class="px-3 py-4 text-center font-black text-brand-dark">{{ $item->kuota }}</td>
                        <td class="px-3 py-4 font-bold text-gray-600 whitespace-nowrap">Rp {{ number_format($item->biaya, 0, ',', '.') }}</td>
                        <td class="px-5 py-4 text-right">
                            <div class="flex justify-end gap-1 opacity-0 group-hover:opacity-100 transition-opacity">
                                <button @click="openEditModal({{ json_encode($item) }})" class="p-2 text-brand-gray hover:text-brand-blue rounded-lg hover:bg-blue-50 transition-colors">
                                    <i data-feather="edit-3" class="w-4 h-4"></i>
                                </button>
                                <form action="{{ route('admin.prodi.destroy', $item->id) }}" method="POST" class="inline" onsubmit="return confirm('Hapus prodi ini?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="p-2 text-brand-gray hover:text-red-500 rounded-lg hover:bg-red-50 transition-colors">
                                        <i data-feather="trash-2" class="w-4 h-4"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-8 py-10 text-center text-gray-400 font-bold">Belum ada data prodi.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <template x-teleport="body">
        <div x-show="modalOpen" class="fixed inset-0 z-[999] flex items-center justify-center p-4" x-cloak>
            <div x-show="modalOpen" x-transition.opacity @click="modalOpen = false" class="absolute inset-0 bg-brand-dark/70 backdrop-blur-sm"></div>
            
            <div x-show="modalOpen" 
                 x-transition:enter="transition ease-out duration-300"
                 x-transition:enter-start="opacity-0 scale-95 translate-y-4"
                 x-transition:enter-end="opacity-100 scale-100 translate-y-0"
                 class="bg-white w-full max-w-2xl rounded-[2.5rem] shadow-2xl relative z-10 overflow-hidden">
                
                <form :action="isEdit ? `{{ url('admin/prodi') }}/${form.id}` : '{{ route('admin.prodi.store') }}'" method="POST" class="p-10">
                    @csrf
                    <template x-if="isEdit">
                        <input type="hidden" name="_method" value="PUT">
                    </template>

                    <div class="flex justify-between items-center mb-8">
                        <h2 class="text-2xl font-black text-brand-dark tracking-tight" x-text="isEdit ? 'Edit Program Studi' : 'Tambah Program Studi'"></h2>
                        <button type="button" @click="modalOpen = false" class="text-gray-400 hover:text-brand-dark"><i data-feather="x"></i></button>
                    </div>

                    <div class="grid grid-cols-2 gap-6 mb-8">
                        
                        <div class="col-span-2">
                            <label class="block text-[11px] font-black text-gray-400 uppercase tracking-widest mb-2">Pilih Ikon Prodi</label>
                            <div class="flex items-center gap-4">
                                <div class="w-16 h-16 bg-blue-50 rounded-2xl flex items-center justify-center text-blue-600 shrink-0 shadow-inner" 
                                     x-html="`<i data-feather='${form.icon}' class='w-7 h-7'></i>`">
                                </div>
                                <div class="relative flex-1">
                                    <select name="icon" x-model="form.icon" @change="updatePreview()" required class="w-full px-5 py-4 bg-gray-50 border border-gray-100 rounded-2xl outline-none focus:ring-2 focus:ring-brand-blue/20 transition-all font-bold text-sm appearance-none capitalize cursor-pointer">
                                        <template x-for="icon in iconList" :key="icon">
                                            <option :value="icon" x-text="icon.replace('-', ' ')"></option>
                                        </template>
                                    </select>
                                    <i data-feather="chevron-down" class="absolute right-5 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400 pointer-events-none"></i>
                                </div>
                            </div>
                        </div>

                        <div class="col-span-2">
                            <label class="block text-[11px] font-black text-gray-400 uppercase tracking-widest mb-2">Nama Program Studi</label>
                            <input type="text" name="nama" x-model="form.nama" placeholder="Misal: Teknik Elektro" required class="w-full px-5 py-4 bg-gray-50 border border-gray-100 rounded-2xl outline-none focus:ring-2 focus:ring-brand-blue/20 transition-all font-bold text-sm">
                        </div>

                        <div>
                            <label class="block text-[11px] font-black text-gray-400 uppercase tracking-widest mb-2">Jenjang</label>
                            <select name="jenjang" x-model="form.jenjang" class="w-full px-5 py-4 bg-gray-50 border border-gray-100 rounded-2xl outline-none font-bold text-sm appearance-none cursor-pointer">
                                <option value="S1">Sarjana (S1)</option>
                                <option value="D3">Diploma (D3)</option>
                                <option value="S2">Magister (S2)</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-[11px] font-black text-gray-400 uppercase tracking-widest mb-2">Akreditasi</label>
                            <select name="akreditasi" x-model="form.akreditasi" class="w-full px-5 py-4 bg-gray-50 border border-gray-100 rounded-2xl outline-none font-bold text-sm appearance-none cursor-pointer">
                                <option value="Unggul">Unggul</option>
                                <option value="A">A</option>
                                <option value="B">B</option>
                                <option value="C">C</option>
                                <option value="Baik Sekali">Baik Sekali</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-[11px] font-black text-gray-400 uppercase tracking-widest mb-2">Daya Tampung</label>
                            <input type="number" name="kuota" x-model="form.kuota" placeholder="0" required class="w-full px-5 py-4 bg-gray-50 border border-gray-100 rounded-2xl outline-none font-bold text-sm">
                        </div>
                        <div>
                            <label class="block text-[11px] font-black text-gray-400 uppercase tracking-widest mb-2">Biaya per Semester</label>
                            <input type="number" name="biaya" x-model="form.biaya" placeholder="0" required class="w-full px-5 py-4 bg-gray-50 border border-gray-100 rounded-2xl outline-none font-bold text-sm">
                        </div>
                    </div>

                    <div class="flex gap-4">
                        <button type="button" @click="modalOpen = false" class="flex-1 py-4 bg-gray-100 text-brand-gray rounded-2xl font-black text-[12px] uppercase tracking-widest hover:bg-gray-200 transition-all">Batal</button>
                        <button type="submit" class="flex-1 py-4 bg-brand-dark text-white rounded-2xl font-black text-[12px] uppercase tracking-widest hover:bg-brand-blue shadow-xl shadow-brand-dark/10 transition-all">Simpan Prodi</button>
                    </div>
                </form>
            </div>
        </div>
    </template>
</div>

<script>
document.addEventListener('alpine:init', () => {
    Alpine.data('prodiManager', () => ({
        modalOpen: false,
        isEdit: false,
        iconList: [
            'book-open', 'monitor', 'cpu', 'briefcase', 'heart', 'activity', 
            'code', 'database', 'globe', 'layout', 'pen-tool', 'users', 
            'bar-chart-2', 'tool', 'zap', 'award', 'target', 'compass', 
            'book', 'layers', 'pie-chart', 'settings', 'camera', 'dollar-sign'
        ],
        form: {
            id: '',
            nama: '',
            jenjang: 'S1',
            akreditasi: 'Unggul',
            kuota: '',
            biaya: '',
            icon: 'book-open' // Tambahan state ikon (default)
        },

        updatePreview() {
            // Merender ulang ikon setiap kali dropdown dipilih
            this.$nextTick(() => {
                if (typeof feather !== 'undefined') feather.replace();
            });
        },

        openAddModal() {
            this.isEdit = false;
            this.form = { id: '', nama: '', jenjang: 'S1', akreditasi: 'Unggul', kuota: '', biaya: '', icon: 'book-open' };
            this.modalOpen = true;
            this.updatePreview();
        },

        openEditModal(data) {
            this.isEdit = true;
            this.form = { ...data };
            if (!this.form.icon) this.form.icon = 'book-open';
            this.modalOpen = true;
            this.updatePreview();
        }
    }));
});
</script>

<script>
    document.addEventListener("DOMContentLoaded", () => {
        setTimeout(() => { if (typeof feather !== 'undefined') feather.replace(); }, 50);
    });
</script>
@endsection