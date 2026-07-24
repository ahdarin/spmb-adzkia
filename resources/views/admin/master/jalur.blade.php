@extends('layouts.admin')

@section('title', 'Master Jalur Pendaftaran')

@section('admin-content')
<div x-data="masterJalur()" class="space-y-6">

    {{-- ══ HEADER ══════════════════════════════════════════════════ --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <div class="flex items-center gap-2 text-[11px] font-black uppercase tracking-widest text-gray-400 mb-2">
                <a href="/admin" class="hover:text-brand-dark transition-colors">Dashboard</a>
                <svg class="w-3 h-3" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 18l6-6-6-6"/></svg>
                <span class="text-brand-dark">Master Jalur Pendaftaran</span>
            </div>
            <h1 class="text-2xl font-black text-brand-dark tracking-tight">Master Jalur Pendaftaran</h1>
            <p class="text-[13px] text-gray-500 font-medium mt-1">
                Kelola jalur penerimaan mahasiswa baru beserta dokumen syarat dan kode NIM.
            </p>
        </div>
        <button @click="bukaModalTambah()"
            class="inline-flex items-center gap-2 px-5 py-3 bg-brand-dark text-white rounded-xl font-bold text-[13px] hover:bg-brand-blue transition-all shadow-lg whitespace-nowrap">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg>
            Tambah Jalur
        </button>
    </div>

    {{-- ══ FLASH MESSAGE ═══════════════════════════════════════════ --}}
    @if(session('success'))
    <div class="flex items-center gap-3 bg-emerald-50 border border-emerald-200 text-emerald-800 rounded-2xl px-5 py-4 text-[13px] font-medium">
        <svg class="w-5 h-5 text-emerald-500 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
        {{ session('success') }}
    </div>
    @endif
    @if(session('error'))
    <div class="flex items-center gap-3 bg-red-50 border border-red-200 text-red-800 rounded-2xl px-5 py-4 text-[13px] font-medium">
        <svg class="w-5 h-5 text-red-500 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
        {{ session('error') }}
    </div>
    @endif

    {{-- ══ PANEL FILTER & SEARCH ═══════════════════════════════════ --}}
    <div class="bg-white border border-gray-100 rounded-2xl shadow-sm p-5">
        <form method="GET" action="{{ route('admin.master.jalur.index') }}"
              class="flex flex-wrap items-end gap-3">

            {{-- Filter Tipe --}}
            <div class="min-w-[180px]">
                <label class="block text-[11px] font-bold text-gray-500 mb-1.5">Tipe Jalur</label>
                <div class="relative">
                    <select name="tipe" onchange="this.form.submit()"
                        class="w-full appearance-none bg-gray-50 border border-gray-200 rounded-xl px-4 py-2.5 text-[13px] font-bold text-brand-dark outline-none focus:border-brand-blue cursor-pointer pr-9">
                        <option value="">Semua Tipe</option>
                        @foreach($tipes as $t)
                            <option value="{{ $t }}" {{ $tipeFilter === $t ? 'selected' : '' }}>{{ $t }}</option>
                        @endforeach
                    </select>
                    <svg class="absolute right-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400 pointer-events-none" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/></svg>
                </div>
            </div>

            {{-- Search --}}
            <div class="flex flex-1 min-w-[220px]">
                <input type="text" name="search" value="{{ $search }}"
                    placeholder="Cari nama jalur atau kode NIM..."
                    class="flex-1 bg-gray-50 border border-gray-200 border-r-0 rounded-l-xl px-4 py-2.5 text-[13px] font-medium text-brand-dark outline-none focus:border-brand-blue">
                <button type="submit"
                    class="px-4 py-2.5 bg-brand-blue text-white rounded-r-xl hover:bg-blue-700 transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><circle cx="11" cy="11" r="8"/><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-4.35-4.35"/></svg>
                </button>
            </div>

            @if($search || $tipeFilter)
            <a href="{{ route('admin.master.jalur.index') }}"
                class="px-4 py-2.5 border border-gray-200 text-gray-500 bg-white hover:bg-gray-50 rounded-xl text-[13px] font-bold transition-colors flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                Reset
            </a>
            @endif

            <p class="ml-auto text-[12px] text-gray-400 font-medium self-center">
                {{ $jalurs->total() }} jalur ditemukan
            </p>
        </form>
    </div>

    {{-- ══ TABEL ════════════════════════════════════════════════════ --}}
    <div class="bg-white border border-gray-100 rounded-[2rem] shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-[13px]">
                <thead class="bg-gray-50/50 text-[11px] font-black text-brand-dark uppercase tracking-widest border-b border-gray-100">
                    <tr>
                        <th class="text-left px-5 py-4 w-10">No.</th>
                        <th class="text-left px-5 py-4">Nama Jalur</th>
                        <th class="text-center px-4 py-4 w-28">Kode NIM</th>
                        <th class="text-center px-4 py-4 w-32">Tipe Jalur</th>
                        <th class="text-center px-4 py-4 w-24">Gratis</th>
                        <th class="text-center px-4 py-4 w-24">Ada Ujian</th>
                        <th class="text-center px-4 py-4 w-24">Status</th>
                        <th class="text-center px-4 py-4 w-24">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50 text-[13px]">
                    @forelse($jalurs as $j)
                    <tr class="hover:bg-gray-50/50 transition-colors group">
                        <td class="px-5 py-3.5 text-gray-400 font-bold text-[12px]">
                            {{ ($jalurs->currentPage() - 1) * $jalurs->perPage() + $loop->iteration }}
                        </td>
                        <td class="px-5 py-3.5">
                            <p class="font-bold text-brand-dark">{{ $j->nama_jalur }}</p>
                            @if(!empty($j->dokumen_syarat))
                            <p class="text-[11px] text-gray-400 font-medium mt-0.5">
                                @php
                                    $docs = is_array($j->dokumen_syarat)
                                        ? $j->dokumen_syarat
                                        : json_decode($j->dokumen_syarat, true) ?? [];
                                @endphp
                                {{ count($docs) }} dokumen syarat
                            </p>
                            @endif
                        </td>
                        <td class="px-4 py-3.5 text-center">
                            <span class="px-2.5 py-1 bg-gray-100 text-gray-600 rounded-lg text-[11px] font-black tracking-wider font-mono">
                                {{ $j->kode_nim }}
                            </span>
                        </td>
                        <td class="px-4 py-3.5 text-center">
                            <span class="px-2.5 py-1 rounded-lg text-[11px] font-bold
                                {{ $j->tipe_jalur === 'RPL'
                                    ? 'bg-purple-100 text-purple-700'
                                    : ($j->tipe_jalur === 'Mitra Nagari'
                                        ? 'bg-green-100 text-green-700'
                                        : 'bg-blue-100 text-blue-700') }}">
                                {{ $j->tipe_jalur }}
                            </span>
                        </td>
                        <td class="px-4 py-3.5 text-center">
                            @if($j->is_free_registration)
                                <span class="inline-flex items-center gap-1 text-emerald-600 text-[11px] font-black">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                                    Gratis
                                </span>
                            @else
                                <span class="text-gray-300 text-[11px] font-bold">—</span>
                            @endif
                        </td>
                        <td class="px-4 py-3.5 text-center">
                            @if($j->has_exam)
                                <span class="inline-flex items-center gap-1 text-amber-600 text-[11px] font-black">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                                    Ada Ujian
                                </span>
                            @else
                                <span class="text-gray-300 text-[11px] font-bold">—</span>
                            @endif
                        </td>
                        <td class="px-4 py-3.5 text-center">
                            <span class="px-2.5 py-1 rounded-lg text-[11px] font-bold
                                {{ $j->is_active ? 'bg-emerald-100 text-emerald-700' : 'bg-gray-100 text-gray-400' }}">
                                {{ $j->is_active ? 'Aktif' : 'Nonaktif' }}
                            </span>
                        </td>
                        <td class="px-4 py-3.5">
                            <div class="flex items-center justify-center gap-1.5 opacity-0 group-hover:opacity-100 transition-opacity">
                                {{-- Edit --}}
                                <button @click="bukaModalEdit({{ $j->id }}, {{ $j->toJson() }})"
                                    class="p-2 text-brand-blue bg-brand-blue-light hover:bg-blue-100 rounded-lg transition-colors" title="Edit">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                </button>
                                {{-- Hapus --}}
                                <form action="{{ route('admin.master.jalur.destroy', $j->id) }}"
                                      method="POST"
                                      onsubmit="return confirm('Yakin hapus jalur \'{{ addslashes($j->nama_jalur) }}\'? Aksi ini tidak bisa dibatalkan.')">
                                    @csrf @method('DELETE')
                                    <button type="submit"
                                        class="p-2 text-red-500 bg-red-50 hover:bg-red-100 rounded-lg transition-colors" title="Hapus">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><polyline points="3 6 5 6 21 6"/><path d="M19 6l-1 14a2 2 0 01-2 2H8a2 2 0 01-2-2L5 6m5 0V4a1 1 0 011-1h2a1 1 0 011 1v2"/></svg>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="px-5 py-16 text-center">
                            <div class="flex flex-col items-center gap-3 text-gray-400">
                                <svg class="w-12 h-12" fill="none" stroke="currentColor" stroke-width="1" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                                <p class="text-[14px] font-extrabold">Tidak ada data jalur</p>
                                <p class="text-[12px]">Coba reset filter atau tambah jalur baru.</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        @if($jalurs->hasPages())
        <div class="px-5 py-4 border-t border-gray-100">
            {{ $jalurs->links() }}
        </div>
        @endif
    </div>


    {{-- ══ MODAL TAMBAH / EDIT ══════════════════════════════════════ --}}
    <div x-show="modalOpen" class="fixed inset-0 z-50 flex items-center justify-center p-4" style="display:none;">
        <div x-show="modalOpen" x-transition.opacity @click="modalOpen = false"
             class="absolute inset-0 bg-brand-dark/60 backdrop-blur-sm cursor-pointer"></div>

        <div x-show="modalOpen"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 scale-95 translate-y-4"
             x-transition:enter-end="opacity-100 scale-100 translate-y-0"
             class="bg-white w-full max-w-lg rounded-[2rem] shadow-2xl relative z-10 flex flex-col"
             style="max-height: 92vh;">

            {{-- Header --}}
            <div class="px-7 py-5 border-b border-gray-100 flex items-center justify-between bg-gray-50/50 shrink-0 rounded-t-[2rem]">
                <div>
                    <h2 class="text-[17px] font-extrabold text-brand-dark tracking-tight"
                        x-text="isEdit ? 'Edit Jalur' : 'Tambah Jalur Baru'"></h2>
                    <p class="text-[12px] text-gray-400 font-medium mt-0.5"
                        x-text="isEdit ? 'Perbarui data jalur pendaftaran' : 'Isi data jalur pendaftaran baru'"></p>
                </div>
                <button @click="modalOpen = false"
                    class="p-2 bg-white border border-gray-200 hover:bg-gray-100 rounded-full transition-colors shrink-0">
                    <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>

            {{-- Form --}}
            <form :action="isEdit ? '/admin/master/jalur/' + form.id : '{{ route('admin.master.jalur.store') }}'"
                  method="POST"
                  class="flex flex-col flex-1 min-h-0">
                @csrf
                <template x-if="isEdit"><input type="hidden" name="_method" value="PUT"></template>

                <div class="px-7 py-6 space-y-4 overflow-y-auto flex-1">

                    {{-- Nama Jalur --}}
                    <div>
                        <label class="block text-[11px] font-black text-gray-400 uppercase tracking-widest mb-2">Nama Jalur</label>
                        <input type="text" name="nama_jalur" x-model="form.nama_jalur" required
                            placeholder="Contoh: Reguler, Beasiswa PMDK..."
                            class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 text-[13px] font-bold text-brand-dark outline-none focus:border-brand-blue focus:bg-white transition-all">
                    </div>

                    {{-- Kode NIM & Tipe --}}
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-[11px] font-black text-gray-400 uppercase tracking-widest mb-2">Kode NIM</label>
                            <input type="text" name="kode_nim" x-model="form.kode_nim" required maxlength="10"
                                placeholder="REG, BAU, RPL-KK..."
                                class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 text-[13px] font-black font-mono text-brand-dark outline-none focus:border-brand-blue focus:bg-white transition-all uppercase"
                                style="text-transform:uppercase">
                            <p class="text-[10px] text-gray-400 mt-1 font-medium">Maks 10 karakter, huruf & tanda -</p>
                        </div>
                        <div>
                            <label class="block text-[11px] font-black text-gray-400 uppercase tracking-widest mb-2">Tipe Jalur</label>
                            <div class="relative">
                                <select name="tipe_jalur" x-model="form.tipe_jalur" required
                                    class="w-full appearance-none bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 text-[13px] font-bold text-brand-dark outline-none focus:border-brand-blue focus:bg-white cursor-pointer pr-9">
                                    <option value="Umum">Umum</option>
                                    <option value="RPL">RPL</option>
                                    <option value="Mitra Nagari">Mitra Nagari</option>
                                </select>
                                <svg class="absolute right-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400 pointer-events-none" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/></svg>
                            </div>
                        </div>
                    </div>

                    {{-- Toggle: Gratis & Ujian & Status --}}
                    <div class="grid grid-cols-3 gap-3">
                        {{-- Gratis --}}
                        <label class="flex flex-col items-center gap-2 p-3 bg-gray-50 border border-gray-200 rounded-xl cursor-pointer hover:border-emerald-300 hover:bg-emerald-50/50 transition-all"
                               :class="form.is_free_registration ? 'border-emerald-400 bg-emerald-50' : ''">
                            <input type="hidden" name="is_free_registration" :value="form.is_free_registration ? 1 : 0">
                            <input type="checkbox" x-model="form.is_free_registration" class="sr-only">
                            <svg class="w-5 h-5" :class="form.is_free_registration ? 'text-emerald-500' : 'text-gray-300'"
                                 fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            <span class="text-[10px] font-black uppercase tracking-wide"
                                  :class="form.is_free_registration ? 'text-emerald-600' : 'text-gray-400'">
                                Gratis Daftar
                            </span>
                        </label>

                        {{-- Ada Ujian --}}
                        <label class="flex flex-col items-center gap-2 p-3 bg-gray-50 border border-gray-200 rounded-xl cursor-pointer hover:border-amber-300 hover:bg-amber-50/50 transition-all"
                               :class="form.has_exam ? 'border-amber-400 bg-amber-50' : ''">
                            <input type="hidden" name="has_exam" :value="form.has_exam ? 1 : 0">
                            <input type="checkbox" x-model="form.has_exam" class="sr-only">
                            <svg class="w-5 h-5" :class="form.has_exam ? 'text-amber-500' : 'text-gray-300'"
                                 fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                            <span class="text-[10px] font-black uppercase tracking-wide"
                                  :class="form.has_exam ? 'text-amber-600' : 'text-gray-400'">
                                Ada Ujian
                            </span>
                        </label>

                        {{-- Status Aktif --}}
                        <label class="flex flex-col items-center gap-2 p-3 bg-gray-50 border border-gray-200 rounded-xl cursor-pointer hover:border-brand-blue hover:bg-blue-50/50 transition-all"
                               :class="form.is_active ? 'border-brand-blue bg-brand-blue-light' : ''">
                            <input type="hidden" name="is_active" :value="form.is_active ? 1 : 0">
                            <input type="checkbox" x-model="form.is_active" class="sr-only">
                            <svg class="w-5 h-5" :class="form.is_active ? 'text-brand-blue' : 'text-gray-300'"
                                 fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            <span class="text-[10px] font-black uppercase tracking-wide"
                                  :class="form.is_active ? 'text-brand-blue' : 'text-gray-400'">
                                Aktif
                            </span>
                        </label>
                    </div>

                    {{-- Dokumen Syarat --}}
                    <div>
                        <div class="flex items-center justify-between mb-2">
                            <label class="block text-[11px] font-black text-gray-400 uppercase tracking-widest">Dokumen Syarat</label>
                            <button type="button" @click="tambahDokumen()"
                                class="text-[11px] font-black text-brand-blue hover:text-blue-700 flex items-center gap-1">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg>
                                Tambah Dokumen
                            </button>
                        </div>
                        <div class="space-y-2">
                            <template x-for="(dok, i) in form.dokumen_syarat" :key="i">
                                <div class="flex items-center gap-2">
                                    <input type="text" :name="'dokumen_syarat[' + i + ']'"
                                        x-model="form.dokumen_syarat[i]"
                                        placeholder="Nama dokumen..."
                                        class="flex-1 bg-gray-50 border border-gray-200 rounded-xl px-4 py-2.5 text-[13px] font-medium text-brand-dark outline-none focus:border-brand-blue focus:bg-white transition-all">
                                    <button type="button" @click="hapusDokumen(i)"
                                        class="p-2 text-red-400 hover:text-red-600 hover:bg-red-50 rounded-lg transition-colors shrink-0">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                                    </button>
                                </div>
                            </template>
                            <template x-if="form.dokumen_syarat.length === 0">
                                <p class="text-[12px] text-gray-400 font-medium italic text-center py-3 border border-dashed border-gray-200 rounded-xl">
                                    Belum ada dokumen syarat. Klik "+ Tambah Dokumen".
                                </p>
                            </template>
                        </div>
                    </div>

                </div>

                {{-- Footer --}}
                <div class="px-7 py-5 border-t border-gray-100 bg-gray-50/50 shrink-0 flex gap-3 rounded-b-[2rem]">
                    <button type="button" @click="modalOpen = false"
                        class="flex-1 py-3 border border-gray-200 text-gray-600 bg-white hover:bg-gray-50 rounded-xl font-bold text-[13px] transition-colors">
                        Batal
                    </button>
                    <button type="submit"
                        class="flex-1 py-3 bg-brand-dark text-white hover:bg-brand-blue rounded-xl font-bold text-[13px] transition-all shadow-lg"
                        x-text="isEdit ? 'Simpan Perubahan' : 'Tambah Jalur'">
                    </button>
                </div>
            </form>
        </div>
    </div>

</div>

<script>
function masterJalur() {
    return {
        modalOpen: false,
        isEdit:    false,

        form: {
            id: null,
            nama_jalur:           '',
            kode_nim:             '',
            tipe_jalur:           'Umum',
            is_free_registration: false,
            has_exam:             false,
            is_active:            true,
            dokumen_syarat:       [],
        },

        bukaModalTambah() {
            this.isEdit = false;
            this.form = {
                id: null, nama_jalur: '', kode_nim: '', tipe_jalur: 'Umum',
                is_free_registration: false, has_exam: false, is_active: true,
                dokumen_syarat: [],
            };
            this.modalOpen = true;
        },

        bukaModalEdit(id, data) {
            this.isEdit = true;
            this.form = {
                id:                   id,
                nama_jalur:           data.nama_jalur,
                kode_nim:             data.kode_nim,
                tipe_jalur:           data.tipe_jalur || 'Umum',
                is_free_registration: !!data.is_free_registration,
                has_exam:             !!data.has_exam,
                is_active:            !!data.is_active,
                dokumen_syarat:       Array.isArray(data.dokumen_syarat)
                                          ? data.dokumen_syarat
                                          : (data.dokumen_syarat ? JSON.parse(data.dokumen_syarat) : []),
            };
            this.modalOpen = true;
        },

        tambahDokumen() {
            this.form.dokumen_syarat.push('');
        },

        hapusDokumen(index) {
            this.form.dokumen_syarat.splice(index, 1);
        },
    };
}
</script>
@endsection