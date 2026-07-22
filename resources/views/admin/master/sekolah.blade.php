@extends('layouts.admin')

@section('title', 'Master Sekolah')

@section('admin-content')

<div x-data="sekolahPage()" x-init="init()">

    {{-- ── Header ──────────────────────────────────────────────── --}}
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-8">
        <div>
            <h1 class="text-2xl font-extrabold text-brand-dark tracking-tight">Master Sekolah</h1>
            <p class="text-brand-gray text-[13px] font-medium mt-1">
                Data sekolah asal pendaftar. Cari via NPSN untuk mengambil data otomatis dari PDDikti.
            </p>
        </div>
        <button @click="bukaModalTambah()"
                class="flex items-center gap-2 px-5 py-2.5 bg-brand-dark text-white rounded-xl font-bold text-[13px] hover:bg-brand-blue transition-all shadow-lg">
            <i data-feather="plus" class="w-4 h-4"></i>
            Tambah Sekolah
        </button>
    </div>

    {{-- Flash --}}
    @if(session('success'))
    <div class="mb-5 flex items-center gap-3 bg-emerald-50 border border-emerald-200 text-emerald-800 rounded-xl px-4 py-3 text-[13px] font-bold">
        <i data-feather="check-circle" class="w-4 h-4 text-emerald-500 shrink-0"></i>
        {{ session('success') }}
    </div>
    @endif
    @if(session('error'))
    <div class="mb-5 flex items-center gap-3 bg-red-50 border border-red-200 text-red-800 rounded-xl px-4 py-3 text-[13px] font-bold">
        <i data-feather="alert-circle" class="w-4 h-4 text-red-500 shrink-0"></i>
        {{ session('error') }}
    </div>
    @endif

    {{-- ── Filter & Search ─────────────────────────────────────── --}}
    <form method="GET" action="{{ route('admin.master.sekolah.index') }}"
          class="bg-white border border-gray-100 rounded-2xl p-4 mb-6 flex gap-3 shadow-sm">
        <div class="relative flex-1">
            <i data-feather="search" class="absolute left-3.5 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400 pointer-events-none"></i>
            <input type="text" name="search" value="{{ $search ?? '' }}"
                   placeholder="Cari nama sekolah, NPSN, atau kota..."
                   class="w-full pl-10 pr-4 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-[13px] outline-none focus:border-brand-blue focus:bg-white transition-all">
        </div>
        <button type="submit"
                class="px-5 py-2.5 bg-brand-blue text-white rounded-xl font-bold text-[13px] hover:bg-blue-700 transition-colors">
            Cari
        </button>
        @if($search)
        <a href="{{ route('admin.master.sekolah.index') }}"
           class="px-4 py-2.5 bg-gray-100 text-gray-600 rounded-xl font-bold text-[13px] hover:bg-gray-200 transition-colors">
            Reset
        </a>
        @endif
    </form>

    {{-- ── Tabel ───────────────────────────────────────────────── --}}
    <div class="bg-white border border-gray-100 rounded-2xl shadow-sm overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
            <p class="text-[13px] font-bold text-brand-gray">
                Total: <span class="text-brand-dark">{{ $sekolahs->total() }}</span> sekolah
            </p>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="bg-gray-50 border-b border-gray-100 text-left">
                        <th class="px-5 py-3 text-[11px] font-black text-gray-400 uppercase tracking-widest w-10">#</th>
                        <th class="px-5 py-3 text-[11px] font-black text-gray-400 uppercase tracking-widest w-32">NPSN</th>
                        <th class="px-5 py-3 text-[11px] font-black text-gray-400 uppercase tracking-widest">Nama Sekolah</th>
                        <th class="px-5 py-3 text-[11px] font-black text-gray-400 uppercase tracking-widest">Kota / Provinsi</th>
                        <th class="px-5 py-3 text-[11px] font-black text-gray-400 uppercase tracking-widest w-24 text-center">Bentuk</th>
                        <th class="px-5 py-3 text-[11px] font-black text-gray-400 uppercase tracking-widest w-24 text-center">Status</th>
                        <th class="px-5 py-3 text-[11px] font-black text-gray-400 uppercase tracking-widest w-24 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @forelse($sekolahs as $s)
                    <tr class="hover:bg-gray-50/60 transition-colors group">
                        <td class="px-5 py-3.5 text-gray-400 text-[12px]">
                            {{ ($sekolahs->currentPage() - 1) * $sekolahs->perPage() + $loop->iteration }}
                        </td>
                        <td class="px-5 py-3.5">
                            <span class="font-mono font-bold text-[13px] text-brand-blue bg-blue-50 px-2 py-1 rounded-lg">
                                {{ $s->npsn }}
                            </span>
                        </td>
                        <td class="px-5 py-3.5">
                            <p class="font-bold text-brand-dark text-[13px]">{{ $s->nama_sekolah }}</p>
                            @if($s->alamat)
                            <p class="text-[11px] text-gray-400 font-medium mt-0.5 truncate max-w-xs">{{ $s->alamat }}</p>
                            @endif
                        </td>
                        <td class="px-5 py-3.5">
                            @if($s->kota || $s->provinsi)
                            <p class="text-[13px] font-medium text-brand-dark">{{ $s->kota }}</p>
                            <p class="text-[11px] text-gray-400">{{ $s->provinsi }}</p>
                            @else
                            <span class="text-gray-300 text-[12px]">—</span>
                            @endif
                        </td>
                        <td class="px-5 py-3.5 text-center">
                            @if($s->bentuk)
                            <span class="px-2 py-1 bg-purple-50 text-purple-700 rounded-lg text-[11px] font-bold">
                                {{ $s->bentuk }}
                            </span>
                            @else
                            <span class="text-gray-300 text-[12px]">—</span>
                            @endif
                        </td>
                        <td class="px-5 py-3.5 text-center">
                            @if($s->status)
                            <span class="px-2 py-1 rounded-lg text-[11px] font-bold
                                {{ strtolower($s->status) === 'negeri'
                                    ? 'bg-emerald-50 text-emerald-700'
                                    : 'bg-amber-50 text-amber-700' }}">
                                {{ $s->status }}
                            </span>
                            @else
                            <span class="text-gray-300 text-[12px]">—</span>
                            @endif
                        </td>
                        <td class="px-5 py-3.5 text-right">
                            <div class="flex items-center justify-end gap-1.5 opacity-0 group-hover:opacity-100 transition-opacity">
                                <button @click="bukaModalEdit({{ $s }})"
                                        class="p-1.5 rounded-lg text-brand-gray hover:text-brand-blue hover:bg-blue-50 transition-all"
                                        title="Edit">
                                    <i data-feather="edit-2" class="w-3.5 h-3.5"></i>
                                </button>
                                <button @click="konfirmasiHapus({{ $s->id }}, '{{ addslashes($s->nama_sekolah) }}')"
                                        class="p-1.5 rounded-lg text-brand-gray hover:text-red-500 hover:bg-red-50 transition-all"
                                        title="Hapus">
                                    <i data-feather="trash-2" class="w-3.5 h-3.5"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="px-5 py-16 text-center">
                            <div class="flex flex-col items-center gap-3">
                                <i data-feather="database" class="w-10 h-10 text-gray-200"></i>
                                <p class="text-gray-400 font-bold text-[14px]">
                                    {{ $search ? 'Sekolah tidak ditemukan.' : 'Belum ada data sekolah.' }}
                                </p>
                                @if(!$search)
                                <button @click="bukaModalTambah()"
                                        class="mt-1 px-4 py-2 bg-brand-blue text-white rounded-xl text-[12px] font-bold hover:bg-blue-700 transition-colors">
                                    Tambah Sekolah Pertama
                                </button>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        @if($sekolahs->hasPages())
        <div class="px-6 py-4 border-t border-gray-100">
            {{ $sekolahs->links() }}
        </div>
        @endif
    </div>

    {{-- ══ MODAL TAMBAH ════════════════════════════════════════ --}}
    <div x-show="modalTambah" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4">
        <div x-show="modalTambah" x-transition.opacity @click="modalTambah = false"
             class="absolute inset-0 bg-black/50 backdrop-blur-sm"></div>

        <div x-show="modalTambah"
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0 scale-95"
             x-transition:enter-end="opacity-100 scale-100"
             class="bg-white rounded-2xl shadow-2xl w-full max-w-lg relative z-10 flex flex-col max-h-[90vh]">

            {{-- Header --}}
            <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between shrink-0">
                <h2 class="font-extrabold text-brand-dark text-[16px]">Tambah Sekolah</h2>
                <button @click="modalTambah = false" class="w-8 h-8 rounded-full hover:bg-gray-100 flex items-center justify-center text-gray-400 hover:text-brand-dark transition-colors">
                    <i data-feather="x" class="w-4 h-4"></i>
                </button>
            </div>

            {{-- Body --}}
            <div class="px-6 py-5 overflow-y-auto flex-1">

                {{-- Cari via NPSN --}}
                <div class="mb-5 p-4 bg-blue-50 border border-blue-100 rounded-xl">
                    <p class="text-[12px] font-black text-brand-blue uppercase tracking-widest mb-3">
                        🔍 Cari Otomatis via NPSN (PDDikti)
                    </p>
                    <div class="flex gap-2">
                        <input type="text" x-model="npsn_cari"
                               placeholder="Masukkan NPSN (8-10 digit)..."
                               maxlength="10"
                               class="flex-1 px-3 py-2.5 bg-white border border-blue-200 rounded-xl text-[13px] outline-none focus:border-brand-blue transition-all font-mono"
                               @keydown.enter.prevent="cariNpsn()">
                        <button type="button" @click="cariNpsn()"
                                :disabled="loading_npsn"
                                class="px-4 py-2.5 bg-brand-blue text-white rounded-xl font-bold text-[13px] hover:bg-blue-700 transition-colors disabled:opacity-50 flex items-center gap-2">
                            <span x-show="loading_npsn" class="w-3.5 h-3.5 border-2 border-white/30 border-t-white rounded-full animate-spin"></span>
                            <span x-text="loading_npsn ? 'Mencari...' : 'Cari'"></span>
                        </button>
                    </div>
                    <p x-show="pesan_npsn" x-text="pesan_npsn"
                       :class="status_npsn === 'error' || status_npsn === 'not_found' ? 'text-red-600' : 'text-emerald-600'"
                       class="text-[12px] font-bold mt-2"></p>
                </div>

                {{-- Form Input --}}
                <form id="formTambah" method="POST" action="{{ route('admin.master.sekolah.store') }}">
                    @csrf
                    <div class="space-y-4">
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-[11px] font-black text-gray-500 uppercase tracking-widest mb-1.5">NPSN <span class="text-red-500">*</span></label>
                                <input type="text" name="npsn" x-model="form.npsn"
                                       placeholder="12345678"
                                       maxlength="10"
                                       class="w-full px-3 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-[13px] outline-none focus:border-brand-blue focus:bg-white transition-all font-mono"
                                       required>
                            </div>
                            <div>
                                <label class="block text-[11px] font-black text-gray-500 uppercase tracking-widest mb-1.5">Bentuk</label>
                                <input type="text" name="bentuk" x-model="form.bentuk"
                                       placeholder="SMA / SMK / MA"
                                       class="w-full px-3 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-[13px] outline-none focus:border-brand-blue focus:bg-white transition-all">
                            </div>
                        </div>
                        <div>
                            <label class="block text-[11px] font-black text-gray-500 uppercase tracking-widest mb-1.5">Nama Sekolah <span class="text-red-500">*</span></label>
                            <input type="text" name="nama_sekolah" x-model="form.nama_sekolah"
                                   placeholder="SMA Negeri 1 Padang"
                                   class="w-full px-3 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-[13px] outline-none focus:border-brand-blue focus:bg-white transition-all"
                                   required>
                        </div>
                        <div>
                            <label class="block text-[11px] font-black text-gray-500 uppercase tracking-widest mb-1.5">Alamat</label>
                            <input type="text" name="alamat" x-model="form.alamat"
                                   placeholder="Jl. Jenderal Sudirman No.1"
                                   class="w-full px-3 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-[13px] outline-none focus:border-brand-blue focus:bg-white transition-all">
                        </div>
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-[11px] font-black text-gray-500 uppercase tracking-widest mb-1.5">Kota / Kabupaten</label>
                                <input type="text" name="kota" x-model="form.kota"
                                       placeholder="Kota Padang"
                                       class="w-full px-3 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-[13px] outline-none focus:border-brand-blue focus:bg-white transition-all">
                            </div>
                            <div>
                                <label class="block text-[11px] font-black text-gray-500 uppercase tracking-widest mb-1.5">Provinsi</label>
                                <input type="text" name="provinsi" x-model="form.provinsi"
                                       placeholder="Sumatera Barat"
                                       class="w-full px-3 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-[13px] outline-none focus:border-brand-blue focus:bg-white transition-all">
                            </div>
                        </div>
                        <div>
                            <label class="block text-[11px] font-black text-gray-500 uppercase tracking-widest mb-1.5">Status</label>
                            <select name="status" x-model="form.status"
                                    class="w-full px-3 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-[13px] outline-none focus:border-brand-blue focus:bg-white transition-all">
                                <option value="">— Pilih Status —</option>
                                <option value="Negeri">Negeri</option>
                                <option value="Swasta">Swasta</option>
                            </select>
                        </div>
                    </div>
                </form>
            </div>

            {{-- Footer --}}
            <div class="px-6 py-4 border-t border-gray-100 flex justify-end gap-3 shrink-0">
                <button type="button" @click="modalTambah = false"
                        class="px-5 py-2.5 bg-gray-100 text-gray-600 rounded-xl font-bold text-[13px] hover:bg-gray-200 transition-colors">
                    Batal
                </button>
                <button type="submit" form="formTambah"
                        class="px-6 py-2.5 bg-brand-dark text-white rounded-xl font-bold text-[13px] hover:bg-brand-blue transition-colors flex items-center gap-2">
                    <i data-feather="save" class="w-4 h-4"></i>
                    Simpan Sekolah
                </button>
            </div>
        </div>
    </div>

    {{-- ══ MODAL EDIT ══════════════════════════════════════════ --}}
    <div x-show="modalEdit" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4">
        <div x-show="modalEdit" x-transition.opacity @click="modalEdit = false"
             class="absolute inset-0 bg-black/50 backdrop-blur-sm"></div>

        <div x-show="modalEdit"
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0 scale-95"
             x-transition:enter-end="opacity-100 scale-100"
             class="bg-white rounded-2xl shadow-2xl w-full max-w-lg relative z-10 flex flex-col max-h-[90vh]">

            <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between shrink-0">
                <h2 class="font-extrabold text-brand-dark text-[16px]">Edit Sekolah</h2>
                <button @click="modalEdit = false" class="w-8 h-8 rounded-full hover:bg-gray-100 flex items-center justify-center text-gray-400 transition-colors">
                    <i data-feather="x" class="w-4 h-4"></i>
                </button>
            </div>

            <div class="px-6 py-5 overflow-y-auto flex-1">
                <form :id="'formEdit-' + editData.id" :action="'/admin/master/sekolah/' + editData.id" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="space-y-4">
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-[11px] font-black text-gray-500 uppercase tracking-widest mb-1.5">NPSN <span class="text-red-500">*</span></label>
                                <input type="text" name="npsn" x-model="editData.npsn"
                                       maxlength="10"
                                       class="w-full px-3 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-[13px] outline-none focus:border-brand-blue focus:bg-white transition-all font-mono"
                                       required>
                            </div>
                            <div>
                                <label class="block text-[11px] font-black text-gray-500 uppercase tracking-widest mb-1.5">Bentuk</label>
                                <input type="text" name="bentuk" x-model="editData.bentuk"
                                       placeholder="SMA / SMK / MA"
                                       class="w-full px-3 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-[13px] outline-none focus:border-brand-blue focus:bg-white transition-all">
                            </div>
                        </div>
                        <div>
                            <label class="block text-[11px] font-black text-gray-500 uppercase tracking-widest mb-1.5">Nama Sekolah <span class="text-red-500">*</span></label>
                            <input type="text" name="nama_sekolah" x-model="editData.nama_sekolah"
                                   class="w-full px-3 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-[13px] outline-none focus:border-brand-blue focus:bg-white transition-all"
                                   required>
                        </div>
                        <div>
                            <label class="block text-[11px] font-black text-gray-500 uppercase tracking-widest mb-1.5">Alamat</label>
                            <input type="text" name="alamat" x-model="editData.alamat"
                                   class="w-full px-3 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-[13px] outline-none focus:border-brand-blue focus:bg-white transition-all">
                        </div>
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-[11px] font-black text-gray-500 uppercase tracking-widest mb-1.5">Kota / Kabupaten</label>
                                <input type="text" name="kota" x-model="editData.kota"
                                       class="w-full px-3 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-[13px] outline-none focus:border-brand-blue focus:bg-white transition-all">
                            </div>
                            <div>
                                <label class="block text-[11px] font-black text-gray-500 uppercase tracking-widest mb-1.5">Provinsi</label>
                                <input type="text" name="provinsi" x-model="editData.provinsi"
                                       class="w-full px-3 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-[13px] outline-none focus:border-brand-blue focus:bg-white transition-all">
                            </div>
                        </div>
                        <div>
                            <label class="block text-[11px] font-black text-gray-500 uppercase tracking-widest mb-1.5">Status</label>
                            <select name="status" x-model="editData.status"
                                    class="w-full px-3 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-[13px] outline-none focus:border-brand-blue focus:bg-white transition-all">
                                <option value="">— Pilih Status —</option>
                                <option value="Negeri">Negeri</option>
                                <option value="Swasta">Swasta</option>
                            </select>
                        </div>
                    </div>
                </form>
            </div>

            <div class="px-6 py-4 border-t border-gray-100 flex justify-end gap-3 shrink-0">
                <button type="button" @click="modalEdit = false"
                        class="px-5 py-2.5 bg-gray-100 text-gray-600 rounded-xl font-bold text-[13px] hover:bg-gray-200 transition-colors">
                    Batal
                </button>
                <button type="submit" :form="'formEdit-' + editData.id"
                        class="px-6 py-2.5 bg-brand-blue text-white rounded-xl font-bold text-[13px] hover:bg-blue-700 transition-colors flex items-center gap-2">
                    <i data-feather="save" class="w-4 h-4"></i>
                    Simpan Perubahan
                </button>
            </div>
        </div>
    </div>

    {{-- ══ MODAL HAPUS ════════════════════════════════════════ --}}
    <div x-show="modalHapus" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4">
        <div x-show="modalHapus" x-transition.opacity @click="modalHapus = false"
             class="absolute inset-0 bg-black/50 backdrop-blur-sm"></div>
        <div x-show="modalHapus"
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0 scale-95"
             x-transition:enter-end="opacity-100 scale-100"
             class="bg-white rounded-2xl shadow-2xl w-full max-w-sm relative z-10 p-6 text-center">
            <div class="w-14 h-14 bg-red-100 rounded-full flex items-center justify-center mx-auto mb-4">
                <i data-feather="trash-2" class="w-6 h-6 text-red-500"></i>
            </div>
            <h3 class="font-extrabold text-brand-dark text-[16px] mb-2">Hapus Sekolah?</h3>
            <p class="text-brand-gray text-[13px] mb-6">
                Sekolah <span class="font-bold text-brand-dark" x-text="hapusNama"></span> akan dihapus permanen.
            </p>
            <div class="flex gap-3">
                <button @click="modalHapus = false"
                        class="flex-1 py-2.5 bg-gray-100 text-gray-600 rounded-xl font-bold text-[13px] hover:bg-gray-200 transition-colors">
                    Batal
                </button>
                <form :action="'/admin/master/sekolah/' + hapusId" method="POST" class="flex-1">
                    @csrf
                    @method('DELETE')
                    <button type="submit"
                            class="w-full py-2.5 bg-red-500 text-white rounded-xl font-bold text-[13px] hover:bg-red-600 transition-colors">
                        Ya, Hapus
                    </button>
                </form>
            </div>
        </div>
    </div>

</div>

@push('scripts')
<script>
function sekolahPage() {
    return {
        // State modal
        modalTambah: false,
        modalEdit:   false,
        modalHapus:  false,

        // Tambah
        npsn_cari:    '',
        loading_npsn: false,
        pesan_npsn:   '',
        status_npsn:  '',
        form: {
            npsn: '', nama_sekolah: '', alamat: '', kota: '', provinsi: '', bentuk: '', status: ''
        },

        // Edit
        editData: {},

        // Hapus
        hapusId:   null,
        hapusNama: '',

        init() {
            if (typeof feather !== 'undefined') feather.replace({ 'stroke-width': 1.75 });
        },

        bukaModalTambah() {
            this.form       = { npsn: '', nama_sekolah: '', alamat: '', kota: '', provinsi: '', bentuk: '', status: '' };
            this.npsn_cari  = '';
            this.pesan_npsn = '';
            this.status_npsn = '';
            this.modalTambah = true;
            this.$nextTick(() => { if (typeof feather !== 'undefined') feather.replace({ 'stroke-width': 1.75 }); });
        },

        bukaModalEdit(data) {
            this.editData = { ...data };
            this.modalEdit = true;
            this.$nextTick(() => { if (typeof feather !== 'undefined') feather.replace({ 'stroke-width': 1.75 }); });
        },

        konfirmasiHapus(id, nama) {
            this.hapusId   = id;
            this.hapusNama = nama;
            this.modalHapus = true;
            this.$nextTick(() => { if (typeof feather !== 'undefined') feather.replace({ 'stroke-width': 1.75 }); });
        },

        async cariNpsn() {
            const npsn = this.npsn_cari.trim();
            if (!npsn || npsn.length < 8) {
                this.pesan_npsn  = 'NPSN minimal 8 digit.';
                this.status_npsn = 'error';
                return;
            }

            this.loading_npsn = true;
            this.pesan_npsn   = '';

            try {
                const res  = await fetch(`/admin/master/sekolah/cari-npsn?npsn=${npsn}`, {
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    }
                });
                const json = await res.json();

                if (json.status === 'found') {
                    this.form        = { ...json.data };
                    this.pesan_npsn  = '✓ Data ditemukan dan otomatis diisi.';
                    this.status_npsn = 'found';
                } else if (json.status === 'exists') {
                    this.pesan_npsn  = '⚠ NPSN ini sudah terdaftar di database.';
                    this.status_npsn = 'error';
                } else {
                    this.pesan_npsn  = '✗ NPSN tidak ditemukan di PDDikti. Isi manual.';
                    this.status_npsn = 'not_found';
                    this.form.npsn   = npsn;
                }
            } catch (e) {
                this.pesan_npsn  = '✗ Gagal menghubungi API. Cek koneksi internet.';
                this.status_npsn = 'error';
            } finally {
                this.loading_npsn = false;
            }
        },
    }
}
</script>
@endpush

@endsection