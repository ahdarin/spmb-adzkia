@extends('layouts.admin')

@section('title', 'Master Biaya Daftar Ulang')

@section('admin-content')
<div x-data="masterBiaya()" class="space-y-6">

    {{-- ══ HEADER ══════════════════════════════════════════════════ --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <div class="flex items-center gap-2 text-[11px] font-black uppercase tracking-widest text-gray-400 mb-2">
                <a href="/admin" class="hover:text-brand-dark transition-colors">Dashboard</a>
                <svg class="w-3 h-3" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 18l6-6-6-6"/></svg>
                <span class="text-brand-dark">Master Biaya Daftar Ulang</span>
            </div>
            <h1 class="text-2xl font-black text-brand-dark tracking-tight">Master Biaya Daftar Ulang</h1>
            <p class="text-[13px] text-gray-500 font-medium mt-1">
                Kelola biaya daftar ulang per kombinasi Program Studi, Jalur, dan Gelombang.
            </p>
        </div>
        <button @click="bukaModalTambah()"
            class="inline-flex items-center gap-2 px-5 py-3 bg-brand-dark text-white rounded-xl font-bold text-[13px] hover:bg-brand-blue transition-all shadow-lg whitespace-nowrap">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg>
            Tambah Data Biaya
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

    {{-- ══ PANEL FILTER ════════════════════════════════════════════ --}}
    <div class="bg-white border border-gray-100 rounded-2xl shadow-sm p-6">
        <p class="text-[11px] font-black text-gray-400 uppercase tracking-widest mb-4">Filter Data</p>
        <form method="GET" action="{{ route('admin.master.biaya-daftar-ulang.index') }}"
              class="grid grid-cols-2 lg:grid-cols-4 gap-4">

            <div>
                <label class="block text-[11px] font-bold text-gray-500 mb-1.5">Tahun</label>
                <div class="relative">
                    <select name="tahun" class="w-full appearance-none bg-gray-50 border border-gray-200 rounded-xl px-4 py-2.5 text-[13px] font-bold text-brand-dark outline-none focus:border-brand-blue cursor-pointer pr-9">
                        @foreach($tahunList as $t)
                            <option value="{{ $t }}" {{ $tahunFilter == $t ? 'selected' : '' }}>{{ $t }}</option>
                        @endforeach
                    </select>
                    <svg class="absolute right-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400 pointer-events-none" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/></svg>
                </div>
            </div>

            <div>
                <label class="block text-[11px] font-bold text-gray-500 mb-1.5">Gelombang</label>
                <div class="relative">
                    <select name="gelombang_id" class="w-full appearance-none bg-gray-50 border border-gray-200 rounded-xl px-4 py-2.5 text-[13px] font-bold text-brand-dark outline-none focus:border-brand-blue cursor-pointer pr-9">
                        <option value="">Semua Gelombang</option>
                        @foreach($gelombangs as $g)
                            <option value="{{ $g->id }}" {{ $gelombangFilter == $g->id ? 'selected' : '' }}>
                                {{ $g->nama_gelombang }}
                            </option>
                        @endforeach
                    </select>
                    <svg class="absolute right-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400 pointer-events-none" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/></svg>
                </div>
            </div>

            <div>
                <label class="block text-[11px] font-bold text-gray-500 mb-1.5">Program Studi</label>
                <div class="relative">
                    <select name="prodi_filter" class="w-full appearance-none bg-gray-50 border border-gray-200 rounded-xl px-4 py-2.5 text-[13px] font-bold text-brand-dark outline-none focus:border-brand-blue cursor-pointer pr-9">
                        <option value="">Semua Prodi</option>
                        @foreach($prodis as $p)
                            <option value="{{ $p->id }}" {{ ($prodiFilter ?? '') == $p->id ? 'selected' : '' }}>
                                {{ $p->nama }}
                            </option>
                        @endforeach
                    </select>
                    <svg class="absolute right-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400 pointer-events-none" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/></svg>
                </div>
            </div>

            <div>
                <label class="block text-[11px] font-bold text-gray-500 mb-1.5">Jalur</label>
                <div class="relative">
                    <select name="jalur_filter" class="w-full appearance-none bg-gray-50 border border-gray-200 rounded-xl px-4 py-2.5 text-[13px] font-bold text-brand-dark outline-none focus:border-brand-blue cursor-pointer pr-9">
                        <option value="">Semua Jalur</option>
                        @foreach($jalurs as $j)
                            <option value="{{ $j->id }}" {{ ($jalurFilter ?? '') == $j->id ? 'selected' : '' }}>
                                {{ $j->nama_jalur }}
                            </option>
                        @endforeach
                    </select>
                    <svg class="absolute right-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400 pointer-events-none" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/></svg>
                </div>
            </div>

            <div class="col-span-2 lg:col-span-4 flex gap-3 pt-1">
                <button type="submit"
                    class="px-6 py-2.5 bg-brand-blue text-white rounded-xl font-bold text-[13px] hover:bg-blue-700 transition-all flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2a1 1 0 01-.293.707L13 13.414V19a1 1 0 01-.553.894l-4 2A1 1 0 017 21v-7.586L3.293 6.707A1 1 0 013 6V4z"/></svg>
                    Terapkan Filter
                </button>
                <a href="{{ route('admin.master.biaya-daftar-ulang.index') }}"
                    class="px-5 py-2.5 border border-gray-200 text-gray-500 bg-white hover:bg-gray-50 rounded-xl font-bold text-[13px] transition-colors flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                    Reset
                </a>
                {{-- Expand / Collapse semua accordion --}}
                <button type="button" @click="toggleSemua()"
                    class="ml-auto px-5 py-2.5 border border-gray-200 text-gray-500 bg-white hover:bg-gray-50 rounded-xl font-bold text-[13px] transition-colors flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16"/></svg>
                    <span x-text="semuaTerbuka ? 'Tutup Semua' : 'Buka Semua'"></span>
                </button>
            </div>
        </form>
    </div>

    {{-- ══ INFO HASIL FILTER ═══════════════════════════════════════ --}}
    <div class="flex items-center justify-between">
        <p class="text-[13px] text-gray-500 font-medium">
            @if($biayaPerProdi->isEmpty())
                Tidak ada data untuk filter yang dipilih.
            @else
                Menampilkan <span class="font-black text-brand-dark">{{ $biayaPerProdi->count() }} program studi</span>
                dengan total <span class="font-black text-brand-dark">{{ $biayaList->count() }} baris</span> data biaya
                @if($jalurFilter || $prodiFilter || $gelombangFilter)
                    <span class="ml-2 px-2.5 py-1 bg-brand-blue-light text-brand-blue rounded-lg text-[11px] font-black">Filter aktif</span>
                @endif
            @endif
        </p>
    </div>

    {{-- ══ ACCORDION PER PRODI ═════════════════════════════════════ --}}
    @if($biayaPerProdi->isEmpty())
    <div class="bg-white border border-gray-100 rounded-2xl shadow-sm">
        <div class="flex flex-col items-center justify-center py-20 text-center">
            <div class="w-16 h-16 bg-gray-100 rounded-2xl flex items-center justify-center text-gray-300 mb-4">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><ellipse cx="12" cy="5" rx="9" ry="3"/><path d="M21 12c0 1.66-4.03 3-9 3S3 13.66 3 12"/><path d="M3 5v14c0 1.66 4.03 3 9 3s9-1.34 9-3V5"/></svg>
            </div>
            <p class="text-[15px] font-extrabold text-gray-400 mb-1">Belum ada data biaya</p>
            <p class="text-[13px] text-gray-400 font-medium">Klik "Tambah Data Biaya" untuk mulai mengisi, atau coba ubah filter.</p>
        </div>
    </div>
    @else
    <div class="space-y-3">
        @foreach($biayaPerProdi as $prodiId => $group)
        @php
            $prodi        = $group['prodi'];
            $items        = $group['items'];
            $totalTerisi  = $group['total_terisi'];
            $totalBaris   = $group['total_baris'];
            $persenTerisi = $totalBaris > 0 ? round(($totalTerisi / $totalBaris) * 100) : 0;
            $semuaTerisi  = $totalTerisi === $totalBaris && $totalBaris > 0;
            $adaYangTerisi = $totalTerisi > 0;
        @endphp

        <div x-data="{ buka: accordionState[{{ $prodiId }}] ?? false }"
             x-init="$watch('semuaTerbuka', v => buka = v)"
             class="bg-white border border-gray-100 rounded-2xl shadow-sm overflow-hidden transition-all">

            {{-- ── Header Accordion ── --}}
            <button @click="buka = !buka; accordionState[{{ $prodiId }}] = buka"
                class="w-full flex items-center gap-4 px-6 py-5 text-left hover:bg-gray-50/60 transition-colors">

                {{-- Badge jenjang --}}
                <span class="shrink-0 px-2.5 py-1 rounded-lg text-[10px] font-black uppercase tracking-widest
                    {{ $prodi->jenjang === 'S2' ? 'bg-purple-100 text-purple-700' : ($prodi->jenjang === 'D3' ? 'bg-orange-100 text-orange-700' : 'bg-brand-blue-light text-brand-blue') }}">
                    {{ $prodi->jenjang ?? 'S1' }}
                </span>

                {{-- Nama prodi --}}
                <div class="flex-1 min-w-0">
                    <p class="text-[14px] font-extrabold text-brand-dark truncate">{{ $prodi->nama ?? '—' }}</p>
                    <p class="text-[11px] text-gray-400 font-medium mt-0.5">
                        {{ $totalTerisi }} dari {{ $totalBaris }} jalur sudah diisi
                    </p>
                </div>

                {{-- Progress bar mini --}}
                <div class="hidden sm:flex items-center gap-3 shrink-0">
                    <div class="w-24 h-1.5 bg-gray-100 rounded-full overflow-hidden">
                        <div class="h-full rounded-full transition-all duration-500
                            {{ $semuaTerisi ? 'bg-emerald-500' : ($adaYangTerisi ? 'bg-amber-400' : 'bg-gray-200') }}"
                            style="width: {{ $persenTerisi }}%"></div>
                    </div>
                    <span class="text-[11px] font-black
                        {{ $semuaTerisi ? 'text-emerald-600' : ($adaYangTerisi ? 'text-amber-600' : 'text-gray-400') }}">
                        {{ $persenTerisi }}%
                    </span>
                </div>

                {{-- Status badge --}}
                <span class="shrink-0 px-3 py-1 rounded-lg text-[11px] font-black
                    {{ $semuaTerisi ? 'bg-emerald-50 text-emerald-700' : ($adaYangTerisi ? 'bg-amber-50 text-amber-700' : 'bg-gray-100 text-gray-400') }}">
                    {{ $semuaTerisi ? 'Lengkap' : ($adaYangTerisi ? 'Sebagian' : 'Belum diisi') }}
                </span>

                {{-- Chevron --}}
                <svg class="w-5 h-5 text-gray-400 shrink-0 transition-transform duration-300"
                     :class="buka ? 'rotate-180' : ''"
                     fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/>
                </svg>
            </button>

            {{-- ── Isi Accordion: Tabel Data ── --}}
            <div x-show="buka"
                 x-transition:enter="transition ease-out duration-200"
                 x-transition:enter-start="opacity-0 -translate-y-2"
                 x-transition:enter-end="opacity-100 translate-y-0"
                 x-transition:leave="transition ease-in duration-150"
                 x-transition:leave-start="opacity-100 translate-y-0"
                 x-transition:leave-end="opacity-0 -translate-y-2"
                 style="display: none;">

                <div class="border-t border-gray-100 overflow-x-auto">
                    <table class="w-full text-[13px]">
                        <thead class="bg-gray-50/50 text-[11px] font-black text-brand-dark uppercase tracking-widest border-b border-gray-100">
                            <tr>
                                <th class="text-left px-6 py-3 w-12">No.</th>
                                <th class="text-left px-4 py-3">Jalur</th>
                                <th class="text-left px-4 py-3">Gelombang</th>
                                <th class="text-right px-4 py-3">SPP Semester</th>
                                <th class="text-right px-4 py-3">Sarpras</th>
                                <th class="text-right px-4 py-3">Seragam & Orientasi</th>
                                <th class="text-right px-4 py-3">Total Biaya</th>
                                <th class="text-center px-4 py-3 w-20">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-50 text-[13px]">
                            @foreach($items as $b)
                            @php $belumDiisi = $b->total_biaya == 0; @endphp
                            <tr class="hover:bg-gray-50/50 transition-colors group">
                                <td class="px-6 py-3.5 text-gray-400 font-bold text-[12px]">{{ $loop->iteration }}</td>
                                <td class="px-4 py-3.5">
                                    <span class="px-2.5 py-1 bg-brand-blue-light text-brand-blue rounded-lg text-[11px] font-extrabold">
                                        {{ $b->jalur->nama_jalur ?? '—' }}
                                    </span>
                                </td>
                                <td class="px-4 py-3.5 text-gray-600 font-medium">
                                    {{ $b->gelombang->nama_gelombang ?? '—' }}
                                    <span class="text-[11px] text-gray-400 ml-1">{{ $b->tahun }}</span>
                                </td>
                                <td class="px-4 py-3.5 text-right {{ $belumDiisi ? 'text-gray-300' : 'text-gray-700 font-bold' }}">
                                    Rp {{ number_format($b->spp_semester, 0, ',', '.') }}
                                </td>
                                <td class="px-4 py-3.5 text-right {{ $belumDiisi ? 'text-gray-300' : 'text-gray-700 font-bold' }}">
                                    Rp {{ number_format($b->biaya_sarpras, 0, ',', '.') }}
                                </td>
                                <td class="px-4 py-3.5 text-right {{ $belumDiisi ? 'text-gray-300' : 'text-gray-700 font-bold' }}">
                                    Rp {{ number_format($b->biaya_seragam_orientasi, 0, ',', '.') }}
                                </td>
                                <td class="px-4 py-3.5 text-right">
                                    @if($belumDiisi)
                                        <span class="text-[11px] font-black text-gray-300 italic">Belum diisi</span>
                                    @else
                                        <span class="font-black text-brand-dark">
                                            Rp {{ number_format($b->total_biaya, 0, ',', '.') }}
                                        </span>
                                    @endif
                                </td>
                                <td class="px-4 py-3.5">
                                    <div class="flex items-center justify-center gap-1.5">
                                        {{-- Edit --}}
                                        <button @click="bukaModalEdit(
                                            {{ $b->id }},
                                            '{{ addslashes(($prodi->nama ?? '').' — '.($b->jalur->nama_jalur ?? '')) }}',
                                            {{ $b->spp_semester }},
                                            {{ $b->biaya_sarpras }},
                                            {{ $b->biaya_seragam_orientasi }}
                                        )"
                                            class="p-2 rounded-lg transition-colors
                                                {{ $belumDiisi
                                                    ? 'text-brand-blue bg-brand-blue-light hover:bg-blue-100'
                                                    : 'text-brand-blue bg-brand-blue-light hover:bg-blue-100 opacity-0 group-hover:opacity-100' }}"
                                            title="{{ $belumDiisi ? 'Isi Biaya' : 'Edit Biaya' }}">
                                            @if($belumDiisi)
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg>
                                            @else
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                            @endif
                                        </button>
                                        {{-- Hapus --}}
                                        <form action="{{ route('admin.master.biaya-daftar-ulang.destroy', $b->id) }}"
                                              method="POST"
                                              onsubmit="return confirm('Yakin hapus data biaya ini?')"
                                              class="opacity-0 group-hover:opacity-100 transition-opacity">
                                            @csrf @method('DELETE')
                                            <button type="submit"
                                                class="p-2 text-red-500 bg-red-50 hover:bg-red-100 rounded-lg transition-colors"
                                                title="Hapus">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><polyline points="3 6 5 6 21 6"/><path d="M19 6l-1 14a2 2 0 01-2 2H8a2 2 0 01-2-2L5 6m5 0V4a1 1 0 011-1h2a1 1 0 011 1v2"/></svg>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                        {{-- Footer subtotal per prodi --}}
                        @if($totalTerisi > 0)
                        <tfoot>
                            <tr class="bg-gray-50 border-t border-gray-200">
                                <td colspan="6" class="px-6 py-3 text-[11px] font-black text-gray-400 uppercase tracking-wider">
                                    Rata-rata total biaya ({{ $totalTerisi }} jalur terisi)
                                </td>
                                <td class="px-4 py-3 text-right font-black text-brand-dark">
                                    Rp {{ number_format($items->where('total_biaya', '>', 0)->avg('total_biaya'), 0, ',', '.') }}
                                </td>
                                <td></td>
                            </tr>
                        </tfoot>
                        @endif
                    </table>
                </div>
            </div>
        </div>
        @endforeach
    </div>
    @endif


    {{-- ══ MODAL TAMBAH ════════════════════════════════════════════ --}}
    <div x-show="modalTambah" class="fixed inset-0 z-50 flex items-center justify-center p-4" style="display:none;">
        <div x-show="modalTambah" x-transition.opacity @click="modalTambah = false"
             class="absolute inset-0 bg-brand-dark/60 backdrop-blur-sm cursor-pointer"></div>

        <div x-show="modalTambah"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 scale-95 translate-y-4"
             x-transition:enter-end="opacity-100 scale-100 translate-y-0"
             class="bg-white w-full max-w-xl rounded-[2rem] shadow-2xl relative z-10 flex flex-col"
             style="max-height: 90vh;">

            <div class="px-7 py-5 border-b border-gray-100 flex items-center justify-between bg-gray-50/50 shrink-0 rounded-t-[2rem]">
                <div>
                    <h2 class="text-[17px] font-extrabold text-brand-dark tracking-tight">Tambah Biaya Daftar Ulang</h2>
                    <p class="text-[12px] text-gray-400 font-medium mt-0.5">Pilih kombinasi prodi, jalur, dan gelombang</p>
                </div>
                <button @click="modalTambah = false"
                    class="p-2 bg-white border border-gray-200 hover:bg-gray-100 rounded-full transition-colors shrink-0">
                    <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>

            <form action="{{ route('admin.master.biaya-daftar-ulang.store') }}" method="POST" class="flex flex-col flex-1 min-h-0">
                @csrf
                <div class="px-7 py-6 space-y-4 overflow-y-auto flex-1">

                    <div>
                        <label class="block text-[11px] font-black text-gray-400 uppercase tracking-widest mb-2">Program Studi</label>
                        <div class="relative">
                            <select name="prodi_id" required class="w-full appearance-none bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 text-[13px] font-bold text-brand-dark outline-none focus:border-brand-blue focus:bg-white cursor-pointer pr-9">
                                <option value="">Pilih Program Studi...</option>
                                @foreach($prodis as $p)
                                    <option value="{{ $p->id }}" {{ old('prodi_id') == $p->id ? 'selected' : '' }}>{{ $p->nama }} ({{ $p->jenjang }})</option>
                                @endforeach
                            </select>
                            <svg class="absolute right-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400 pointer-events-none" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/></svg>
                        </div>
                        @error('prodi_id')<p class="text-red-500 text-[11px] font-bold mt-1">{{ $message }}</p>@enderror
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-[11px] font-black text-gray-400 uppercase tracking-widest mb-2">Jalur</label>
                            <div class="relative">
                                <select name="jalur_id" required class="w-full appearance-none bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 text-[13px] font-bold text-brand-dark outline-none focus:border-brand-blue focus:bg-white cursor-pointer pr-9">
                                    <option value="">Pilih...</option>
                                    @foreach($jalurs as $j)
                                        <option value="{{ $j->id }}" {{ old('jalur_id') == $j->id ? 'selected' : '' }}>{{ $j->nama_jalur }}</option>
                                    @endforeach
                                </select>
                                <svg class="absolute right-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400 pointer-events-none" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/></svg>
                            </div>
                            @error('jalur_id')<p class="text-red-500 text-[11px] font-bold mt-1">{{ $message }}</p>@enderror
                        </div>
                        <div>
                            <label class="block text-[11px] font-black text-gray-400 uppercase tracking-widest mb-2">Gelombang</label>
                            <div class="relative">
                                <select name="gelombang_id" required class="w-full appearance-none bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 text-[13px] font-bold text-brand-dark outline-none focus:border-brand-blue focus:bg-white cursor-pointer pr-9">
                                    <option value="">Pilih...</option>
                                    @foreach($gelombangs as $g)
                                        <option value="{{ $g->id }}" {{ old('gelombang_id') == $g->id ? 'selected' : '' }}>{{ $g->nama_gelombang }} ({{ $g->tahun }})</option>
                                    @endforeach
                                </select>
                                <svg class="absolute right-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400 pointer-events-none" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/></svg>
                            </div>
                            @error('gelombang_id')<p class="text-red-500 text-[11px] font-bold mt-1">{{ $message }}</p>@enderror
                        </div>
                    </div>

                    <div>
                        <label class="block text-[11px] font-black text-gray-400 uppercase tracking-widest mb-2">Tahun Akademik</label>
                        <input type="number" name="tahun" value="{{ old('tahun', date('Y')) }}" min="2020" max="2040" required
                            class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 text-[13px] font-bold text-brand-dark outline-none focus:border-brand-blue focus:bg-white transition-all">
                        @error('tahun')<p class="text-red-500 text-[11px] font-bold mt-1">{{ $message }}</p>@enderror
                    </div>

                    <div class="border-t border-gray-100 pt-4">
                        <p class="text-[11px] font-black text-gray-400 uppercase tracking-widest mb-4">Komponen Biaya</p>

                        {{-- SPP --}}
                        <div class="mb-4">
                            <label class="block text-[12px] font-extrabold text-gray-600 mb-2">
                                <span class="w-5 h-5 inline-flex items-center justify-center bg-brand-blue-light text-brand-blue rounded-md text-[10px] font-black mr-1.5">1</span>
                                SPP Mahasiswa Baru per Semester
                            </label>
                            <div class="relative">
                                <span class="absolute left-4 top-1/2 -translate-y-1/2 text-[13px] font-black text-gray-400 select-none">Rp</span>
                                <input type="text" placeholder="0" inputmode="numeric"
                                    x-model="form.sppDisplay"
                                    @input="form.sppDisplay = formatInput($event.target.value); form.spp = parseRupiah(form.sppDisplay)"
                                    class="w-full bg-gray-50 border border-gray-200 rounded-xl pl-12 pr-4 py-3 text-[13px] font-bold text-brand-dark outline-none focus:border-brand-blue focus:bg-white transition-all">
                                <input type="hidden" name="spp_semester" :value="form.spp">
                            </div>
                        </div>

                        {{-- Sarpras --}}
                        <div class="mb-4">
                            <label class="block text-[12px] font-extrabold text-gray-600 mb-2">
                                <span class="w-5 h-5 inline-flex items-center justify-center bg-brand-blue-light text-brand-blue rounded-md text-[10px] font-black mr-1.5">2</span>
                                Biaya Sarana dan Prasarana
                            </label>
                            <div class="relative">
                                <span class="absolute left-4 top-1/2 -translate-y-1/2 text-[13px] font-black text-gray-400 select-none">Rp</span>
                                <input type="text" placeholder="0" inputmode="numeric"
                                    x-model="form.sarprasDisplay"
                                    @input="form.sarprasDisplay = formatInput($event.target.value); form.sarpras = parseRupiah(form.sarprasDisplay)"
                                    class="w-full bg-gray-50 border border-gray-200 rounded-xl pl-12 pr-4 py-3 text-[13px] font-bold text-brand-dark outline-none focus:border-brand-blue focus:bg-white transition-all">
                                <input type="hidden" name="biaya_sarpras" :value="form.sarpras">
                            </div>
                        </div>

                        {{-- Seragam --}}
                        <div class="mb-4">
                            <label class="block text-[12px] font-extrabold text-gray-600 mb-2">
                                <span class="w-5 h-5 inline-flex items-center justify-center bg-brand-blue-light text-brand-blue rounded-md text-[10px] font-black mr-1.5">3</span>
                                Biaya Seragam dan Orientasi
                            </label>
                            <div class="relative">
                                <span class="absolute left-4 top-1/2 -translate-y-1/2 text-[13px] font-black text-gray-400 select-none">Rp</span>
                                <input type="text" placeholder="0" inputmode="numeric"
                                    x-model="form.seragamDisplay"
                                    @input="form.seragamDisplay = formatInput($event.target.value); form.seragam = parseRupiah(form.seragamDisplay)"
                                    class="w-full bg-gray-50 border border-gray-200 rounded-xl pl-12 pr-4 py-3 text-[13px] font-bold text-brand-dark outline-none focus:border-brand-blue focus:bg-white transition-all">
                                <input type="hidden" name="biaya_seragam_orientasi" :value="form.seragam">
                            </div>
                        </div>

                        {{-- Preview Total --}}
                        <div class="bg-brand-blue-light border border-blue-100 rounded-xl px-5 py-4 flex items-center justify-between">
                            <span class="text-[12px] font-black text-brand-blue uppercase tracking-wider">Total Biaya Daftar Ulang</span>
                            <span class="text-[20px] font-black text-brand-dark"
                                  x-text="'Rp\u00a0' + formatRupiah(form.spp + form.sarpras + form.seragam)"></span>
                        </div>
                    </div>
                </div>

                <div class="px-7 py-5 border-t border-gray-100 bg-gray-50/50 shrink-0 flex gap-3 rounded-b-[2rem]">
                    <button type="button" @click="modalTambah = false"
                        class="flex-1 py-3 border border-gray-200 text-gray-600 bg-white hover:bg-gray-50 rounded-xl font-bold text-[13px] transition-colors">Batal</button>
                    <button type="submit"
                        class="flex-1 py-3 bg-brand-dark text-white hover:bg-brand-blue rounded-xl font-bold text-[13px] transition-all shadow-lg">Simpan Data</button>
                </div>
            </form>
        </div>
    </div>


    {{-- ══ MODAL EDIT ══════════════════════════════════════════════ --}}
    <div x-show="modalEdit" class="fixed inset-0 z-50 flex items-center justify-center p-4" style="display:none;">
        <div x-show="modalEdit" x-transition.opacity @click="modalEdit = false"
             class="absolute inset-0 bg-brand-dark/60 backdrop-blur-sm cursor-pointer"></div>

        <div x-show="modalEdit"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 scale-95 translate-y-4"
             x-transition:enter-end="opacity-100 scale-100 translate-y-0"
             class="bg-white w-full max-w-md rounded-[2rem] shadow-2xl relative z-10 flex flex-col"
             style="max-height: 90vh;">

            <div class="px-7 py-5 border-b border-gray-100 flex items-center justify-between bg-gray-50/50 shrink-0 rounded-t-[2rem]">
                <div>
                    <h2 class="text-[17px] font-extrabold text-brand-dark tracking-tight">Edit Biaya</h2>
                    <p class="text-[12px] text-gray-400 font-medium mt-0.5 leading-relaxed" x-text="editLabel"></p>
                </div>
                <button @click="modalEdit = false" class="p-2 bg-white border border-gray-200 hover:bg-gray-100 rounded-full transition-colors shrink-0">
                    <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>

            <form :action="'/admin/master/biaya-daftar-ulang/' + editId" method="POST" class="flex flex-col flex-1 min-h-0">
                @csrf
                @method('PUT')

                <div class="px-7 py-6 space-y-4 overflow-y-auto flex-1">

                    <div>
                        <label class="block text-[12px] font-extrabold text-gray-600 mb-2">
                            <span class="w-5 h-5 inline-flex items-center justify-center bg-brand-blue-light text-brand-blue rounded-md text-[10px] font-black mr-1.5">1</span>
                            SPP Mahasiswa Baru per Semester
                        </label>
                        <div class="relative">
                            <span class="absolute left-4 top-1/2 -translate-y-1/2 text-[13px] font-black text-gray-400 select-none">Rp</span>
                            <input type="text" placeholder="0" inputmode="numeric"
                                x-model="editForm.sppDisplay"
                                @input="editForm.sppDisplay = formatInput($event.target.value); editForm.spp = parseRupiah(editForm.sppDisplay)"
                                class="w-full bg-gray-50 border border-gray-200 rounded-xl pl-12 pr-4 py-3 text-[13px] font-bold text-brand-dark outline-none focus:border-brand-blue focus:bg-white transition-all">
                            <input type="hidden" name="spp_semester" :value="editForm.spp">
                        </div>
                    </div>

                    <div>
                        <label class="block text-[12px] font-extrabold text-gray-600 mb-2">
                            <span class="w-5 h-5 inline-flex items-center justify-center bg-brand-blue-light text-brand-blue rounded-md text-[10px] font-black mr-1.5">2</span>
                            Biaya Sarana dan Prasarana
                        </label>
                        <div class="relative">
                            <span class="absolute left-4 top-1/2 -translate-y-1/2 text-[13px] font-black text-gray-400 select-none">Rp</span>
                            <input type="text" placeholder="0" inputmode="numeric"
                                x-model="editForm.sarprasDisplay"
                                @input="editForm.sarprasDisplay = formatInput($event.target.value); editForm.sarpras = parseRupiah(editForm.sarprasDisplay)"
                                class="w-full bg-gray-50 border border-gray-200 rounded-xl pl-12 pr-4 py-3 text-[13px] font-bold text-brand-dark outline-none focus:border-brand-blue focus:bg-white transition-all">
                            <input type="hidden" name="biaya_sarpras" :value="editForm.sarpras">
                        </div>
                    </div>

                    <div>
                        <label class="block text-[12px] font-extrabold text-gray-600 mb-2">
                            <span class="w-5 h-5 inline-flex items-center justify-center bg-brand-blue-light text-brand-blue rounded-md text-[10px] font-black mr-1.5">3</span>
                            Biaya Seragam dan Orientasi
                        </label>
                        <div class="relative">
                            <span class="absolute left-4 top-1/2 -translate-y-1/2 text-[13px] font-black text-gray-400 select-none">Rp</span>
                            <input type="text" placeholder="0" inputmode="numeric"
                                x-model="editForm.seragamDisplay"
                                @input="editForm.seragamDisplay = formatInput($event.target.value); editForm.seragam = parseRupiah(editForm.seragamDisplay)"
                                class="w-full bg-gray-50 border border-gray-200 rounded-xl pl-12 pr-4 py-3 text-[13px] font-bold text-brand-dark outline-none focus:border-brand-blue focus:bg-white transition-all">
                            <input type="hidden" name="biaya_seragam_orientasi" :value="editForm.seragam">
                        </div>
                    </div>

                    <div class="bg-brand-blue-light border border-blue-100 rounded-xl px-5 py-4 flex items-center justify-between">
                        <span class="text-[12px] font-black text-brand-blue uppercase tracking-wider">Total Biaya</span>
                        <span class="text-[20px] font-black text-brand-dark"
                              x-text="'Rp\u00a0' + formatRupiah(editForm.spp + editForm.sarpras + editForm.seragam)"></span>
                    </div>
                </div>

                <div class="px-7 py-5 border-t border-gray-100 bg-gray-50/50 shrink-0 flex gap-3 rounded-b-[2rem]">
                    <button type="button" @click="modalEdit = false"
                        class="flex-1 py-3 border border-gray-200 text-gray-600 bg-white hover:bg-gray-50 rounded-xl font-bold text-[13px] transition-colors">Batal</button>
                    <button type="submit"
                        class="flex-1 py-3 bg-brand-dark text-white hover:bg-brand-blue rounded-xl font-bold text-[13px] transition-all shadow-lg">Simpan Perubahan</button>
                </div>
            </form>
        </div>
    </div>

</div>

<script>
function masterBiaya() {
    return {
        modalTambah:   {{ $errors->any() ? 'true' : 'false' }},
        modalEdit:     false,
        editId:        null,
        editLabel:     '',
        semuaTerbuka:  false,
        accordionState: {},

        form: {
            spp: 0, sppDisplay: '',
            sarpras: 0, sarprasDisplay: '',
            seragam: 0, seragamDisplay: '',
        },
        editForm: {
            spp: 0, sppDisplay: '',
            sarpras: 0, sarprasDisplay: '',
            seragam: 0, seragamDisplay: '',
        },

        toggleSemua() {
            this.semuaTerbuka = !this.semuaTerbuka;
        },

        bukaModalTambah() {
            this.form = { spp:0, sppDisplay:'', sarpras:0, sarprasDisplay:'', seragam:0, seragamDisplay:'' };
            this.modalTambah = true;
        },

        bukaModalEdit(id, label, spp, sarpras, seragam) {
            this.editId    = id;
            this.editLabel = label;
            this.editForm  = {
                spp,     sppDisplay:     this.formatRupiah(spp),
                sarpras, sarprasDisplay: this.formatRupiah(sarpras),
                seragam, seragamDisplay: this.formatRupiah(seragam),
            };
            this.modalEdit = true;
        },

        // Format angka mentah → "3.500.000"
        formatInput(val) {
            const angka = val.replace(/\D/g, '');
            if (!angka) return '';
            return new Intl.NumberFormat('id-ID').format(parseInt(angka, 10));
        },

        // "3.500.000" → 3500000
        parseRupiah(val) {
            return parseInt((val || '0').replace(/\./g, ''), 10) || 0;
        },

        // Angka murni → "3.500.000"
        formatRupiah(angka) {
            return new Intl.NumberFormat('id-ID').format(angka || 0);
        },
    };
}
</script>
@endsection