@extends('layouts.admin')

@section('title', 'Pengaturan Sistem')

@section('admin-content')

@php
    $activeTabInit   = session('tab', 'umum');
    $maintenanceInit = $setting->maintenance_mode ? 'true' : 'false';
    $pendaftaranInit = $setting->pendaftaran_aktif ? 'true' : 'false';
    $tahunSekarang   = date('Y');
    $countJson       = json_encode($countPerTahun);
@endphp

<div x-data="settingsApp()">

{{-- ══ HEADER ════════════════════════════════════════════════ --}}
<div class="mb-8">
    <h1 class="text-3xl font-extrabold text-brand-dark tracking-tight mb-1">Pengaturan Sistem</h1>
    <p class="text-brand-gray text-[14px] font-medium">Konfigurasi profil portal, gelombang pendaftaran, dan keamanan akun.</p>
</div>

{{-- Flash --}}
@if(session('success'))
<div class="mb-6 p-4 bg-green-50 border border-green-200 text-green-700 font-bold rounded-2xl text-[13px] flex items-center gap-3">
    <i data-feather="check-circle" class="w-5 h-5 shrink-0"></i> {{ session('success') }}
</div>
@endif
@if(session('error'))
<div class="mb-6 p-4 bg-red-50 border border-red-200 text-red-700 font-bold rounded-2xl text-[13px] flex items-center gap-3">
    <i data-feather="alert-circle" class="w-5 h-5 shrink-0"></i> {{ session('error') }}
</div>
@endif
@if($errors->any())
<div class="mb-6 p-4 bg-red-50 border border-red-200 text-red-700 font-bold rounded-2xl text-[13px] flex items-start gap-3">
    <i data-feather="alert-circle" class="w-5 h-5 shrink-0 mt-0.5"></i>
    <ul class="list-disc list-inside space-y-0.5">
        @foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach
    </ul>
</div>
@endif

<div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">

    {{-- ══ SIDEBAR ════════════════════════════════════════════ --}}
    <div class="lg:col-span-4 space-y-2 sticky top-32">

        <button type="button" @click="activeTab = 'umum'"
            :class="activeTab === 'umum' ? 'bg-brand-blue text-white shadow-lg shadow-brand-blue/20' : 'bg-white text-gray-500 hover:bg-gray-50 border border-transparent hover:border-gray-100'"
            class="w-full flex items-center gap-4 p-4 rounded-2xl font-bold text-[14px] transition-all duration-200 group text-left">
            <div class="w-10 h-10 rounded-xl flex items-center justify-center shrink-0 transition-colors"
                :class="activeTab === 'umum' ? 'bg-white/20 text-white' : 'bg-gray-100 text-gray-400 group-hover:bg-brand-blue-light group-hover:text-brand-blue'">
                <i data-feather="monitor" class="w-5 h-5"></i>
            </div>
            <div>
                <p class="leading-tight">Informasi Umum</p>
                <p class="text-[11px] font-medium mt-0.5" :class="activeTab === 'umum' ? 'text-blue-200' : 'text-gray-400'">Profil, Kontak & Identitas</p>
            </div>
        </button>

        <button type="button" @click="activeTab = 'media'"
            :class="activeTab === 'media' ? 'bg-brand-blue text-white shadow-lg shadow-brand-blue/20' : 'bg-white text-gray-500 hover:bg-gray-50 border border-transparent hover:border-gray-100'"
            class="w-full flex items-center gap-4 p-4 rounded-2xl font-bold text-[14px] transition-all duration-200 group text-left">
            <div class="w-10 h-10 rounded-xl flex items-center justify-center shrink-0 transition-colors"
                :class="activeTab === 'media' ? 'bg-white/20 text-white' : 'bg-gray-100 text-gray-400 group-hover:bg-brand-blue-light group-hover:text-brand-blue'">
                <i data-feather="youtube" class="w-5 h-5"></i>
            </div>
            <div>
                <p class="leading-tight">Media & Dokumen</p>
                <p class="text-[11px] font-medium mt-0.5" :class="activeTab === 'media' ? 'text-blue-200' : 'text-gray-400'">Video, Brosur & Maps</p>
            </div>
        </button>

        <button type="button" @click="activeTab = 'gelombang'"
            :class="activeTab === 'gelombang' ? 'bg-brand-blue text-white shadow-lg shadow-brand-blue/20' : 'bg-white text-gray-500 hover:bg-gray-50 border border-transparent hover:border-gray-100'"
            class="w-full flex items-center gap-4 p-4 rounded-2xl font-bold text-[14px] transition-all duration-200 group text-left">
            <div class="w-10 h-10 rounded-xl flex items-center justify-center shrink-0 transition-colors"
                :class="activeTab === 'gelombang' ? 'bg-white/20 text-white' : 'bg-gray-100 text-gray-400 group-hover:bg-brand-blue-light group-hover:text-brand-blue'">
                <i data-feather="calendar" class="w-5 h-5"></i>
            </div>
            <div>
                <p class="leading-tight">Periode Pendaftaran</p>
                <p class="text-[11px] font-medium mt-0.5" :class="activeTab === 'gelombang' ? 'text-blue-200' : 'text-gray-400'">Kelola Gelombang Aktif</p>
            </div>
        </button>

        <button type="button" @click="activeTab = 'keamanan'"
            :class="activeTab === 'keamanan' ? 'bg-brand-blue text-white shadow-lg shadow-brand-blue/20' : 'bg-white text-gray-500 hover:bg-gray-50 border border-transparent hover:border-gray-100'"
            class="w-full flex items-center gap-4 p-4 rounded-2xl font-bold text-[14px] transition-all duration-200 group text-left">
            <div class="w-10 h-10 rounded-xl flex items-center justify-center shrink-0 transition-colors"
                :class="activeTab === 'keamanan' ? 'bg-white/20 text-white' : 'bg-gray-100 text-gray-400 group-hover:bg-brand-blue-light group-hover:text-brand-blue'">
                <i data-feather="shield" class="w-5 h-5"></i>
            </div>
            <div>
                <p class="leading-tight">Keamanan Akun</p>
                <p class="text-[11px] font-medium mt-0.5" :class="activeTab === 'keamanan' ? 'text-blue-200' : 'text-gray-400'">Ganti Password Admin</p>
            </div>
        </button>

    </div>

    {{-- ══ KONTEN ══════════════════════════════════════════════ --}}
    <div class="lg:col-span-8">


        {{-- ─── TAB UMUM ──────────────────────────────────────── --}}
        <div x-show="activeTab === 'umum'" x-cloak>
            <form action="{{ route('admin.settings.update') }}" method="POST"
                  class="bg-white rounded-[2.5rem] p-10 shadow-sm border border-gray-100 space-y-6">
                @csrf
                <input type="hidden" name="active_tab"        value="umum">
                <input type="hidden" name="maintenance_mode"  :value="maintenanceMode ? 1 : 0">
                <input type="hidden" name="pendaftaran_aktif" :value="pendaftaranBuka ? 1 : 0">

                <div class="border-b border-gray-100 pb-6 flex items-center justify-between">
                    <div>
                        <h2 class="text-xl font-extrabold text-brand-dark">Informasi Umum</h2>
                        <p class="text-[13px] text-gray-400 font-medium mt-1">Data kontak dan identitas portal SPMB.</p>
                    </div>
                    <button type="submit"
                        class="flex items-center gap-2 px-5 py-2.5 bg-brand-dark text-white rounded-xl font-bold text-[13px] hover:bg-brand-blue transition-all shadow-lg active:scale-95 shrink-0">
                        <i data-feather="save" class="w-4 h-4"></i> Simpan
                    </button>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                    <div>
                        <label class="block text-[11px] font-black text-gray-500 uppercase tracking-widest mb-2">Email Helpdesk</label>
                        <input type="email" name="email"
                            value="{{ old('email', $setting->email) }}"
                            placeholder="pmb@adzkia.ac.id"
                            class="w-full px-5 py-4 bg-gray-50 border border-gray-100 rounded-2xl text-[14px] font-bold text-brand-dark outline-none focus:ring-2 focus:ring-brand-blue/20 focus:border-brand-blue transition-all">
                    </div>
                    <div>
                        <label class="block text-[11px] font-black text-gray-500 uppercase tracking-widest mb-2">Nomor WhatsApp CS</label>
                        <input type="text" name="telepon"
                            value="{{ old('telepon', $setting->telepon) }}"
                            placeholder="08xx-xxxx-xxxx"
                            class="w-full px-5 py-4 bg-gray-50 border border-gray-100 rounded-2xl text-[14px] font-bold text-brand-dark outline-none focus:ring-2 focus:ring-brand-blue/20 focus:border-brand-blue transition-all">
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                    <div>
                        <label class="block text-[11px] font-black text-gray-500 uppercase tracking-widest mb-2">Tahun Akademik</label>
                        <input type="text" name="tahun_akademik"
                            value="{{ old('tahun_akademik', $setting->tahun_akademik) }}"
                            placeholder="2025/2026"
                            class="w-full px-5 py-4 bg-gray-50 border border-gray-100 rounded-2xl text-[14px] font-bold text-brand-dark outline-none focus:ring-2 focus:ring-brand-blue/20 focus:border-brand-blue transition-all">
                    </div>
                    <div>
                        <label class="block text-[11px] font-black text-gray-500 uppercase tracking-widest mb-2">Akreditasi Institusi</label>
                        <div class="relative">
                            <select name="akreditasi"
                                class="w-full appearance-none px-5 py-4 bg-gray-50 border border-gray-100 rounded-2xl text-[14px] font-bold text-brand-dark outline-none focus:ring-2 focus:ring-brand-blue/20 cursor-pointer pr-10 transition-all">
                                @foreach(['Unggul','Baik Sekali','Baik','A','B','C'] as $ak)
                                <option value="{{ $ak }}" {{ $setting->akreditasi == $ak ? 'selected' : '' }}>{{ $ak }}</option>
                                @endforeach
                            </select>
                            <i data-feather="chevron-down" class="absolute right-4 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400 pointer-events-none"></i>
                        </div>
                    </div>
                </div>

                <div>
                    <label class="block text-[11px] font-black text-gray-500 uppercase tracking-widest mb-2">Alamat Kampus</label>
                    <textarea name="alamat" rows="3"
                        placeholder="Jl. Raya Taratak Paneh No. 7, Korong Gadang, Kec. Kuranji, Kota Padang"
                        class="w-full px-5 py-4 bg-gray-50 border border-gray-100 rounded-2xl text-[14px] font-bold text-brand-dark outline-none focus:ring-2 focus:ring-brand-blue/20 resize-none transition-all">{{ old('alamat', $setting->alamat) }}</textarea>
                </div>

                <div class="p-5 bg-emerald-50 border border-emerald-100 rounded-2xl flex items-center justify-between">
                    <div>
                        <h4 class="text-[14px] font-extrabold text-emerald-900">Status Pendaftaran</h4>
                        <p class="text-[12px] font-medium text-emerald-700 mt-0.5"
                           x-text="pendaftaranBuka ? 'Pendaftaran mahasiswa baru sedang dibuka.' : 'Pendaftaran mahasiswa baru sedang ditutup.'"></p>
                    </div>
                    <button type="button" @click="pendaftaranBuka = !pendaftaranBuka"
                        class="w-14 h-8 rounded-full transition-colors relative flex items-center focus:outline-none shrink-0"
                        :class="pendaftaranBuka ? 'bg-emerald-500' : 'bg-gray-300'">
                        <div class="w-6 h-6 bg-white rounded-full shadow-md transform transition-transform duration-300 absolute left-1"
                             :class="pendaftaranBuka ? 'translate-x-6' : 'translate-x-0'"></div>
                    </button>
                </div>

                <div class="p-5 bg-red-50 border border-red-100 rounded-2xl flex items-center justify-between">
                    <div>
                        <h4 class="text-[14px] font-extrabold text-red-900">Maintenance Mode</h4>
                        <p class="text-[12px] font-medium text-red-700 mt-0.5">Aktifkan untuk menutup website dari publik sementara.</p>
                    </div>
                    <button type="button" @click="maintenanceMode = !maintenanceMode"
                        class="w-14 h-8 rounded-full transition-colors relative flex items-center focus:outline-none shrink-0"
                        :class="maintenanceMode ? 'bg-red-500' : 'bg-gray-300'">
                        <div class="w-6 h-6 bg-white rounded-full shadow-md transform transition-transform duration-300 absolute left-1"
                             :class="maintenanceMode ? 'translate-x-6' : 'translate-x-0'"></div>
                    </button>
                </div>

            </form>
        </div>{{-- /tab umum --}}


        {{-- ─── TAB MEDIA ─────────────────────────────────────── --}}
        <div x-show="activeTab === 'media'" x-cloak style="display:none;">
            <form action="{{ route('admin.settings.update') }}" method="POST"
                  enctype="multipart/form-data"
                  class="bg-white rounded-[2.5rem] p-10 shadow-sm border border-gray-100 space-y-6">
                @csrf
                <input type="hidden" name="active_tab" value="media">

                <div class="border-b border-gray-100 pb-6 flex items-center justify-between">
                    <div>
                        <h2 class="text-xl font-extrabold text-brand-dark">Media & Dokumen Publik</h2>
                        <p class="text-[13px] text-gray-400 font-medium mt-1">Video profil, brosur, dan embed peta kampus.</p>
                    </div>
                    <button type="submit"
                        class="flex items-center gap-2 px-5 py-2.5 bg-brand-dark text-white rounded-xl font-bold text-[13px] hover:bg-brand-blue transition-all shadow-lg active:scale-95 shrink-0">
                        <i data-feather="save" class="w-4 h-4"></i> Simpan
                    </button>
                </div>

                <div>
                    <label class="block text-[11px] font-black text-gray-500 uppercase tracking-widest mb-2">ID Video YouTube Profil</label>
                    <div class="flex rounded-2xl overflow-hidden border border-gray-100">
                        <span class="px-5 py-4 bg-gray-100 text-gray-400 font-medium text-[13px] flex items-center shrink-0 border-r border-gray-200">
                            youtube.com/embed/
                        </span>
                        <input type="text" name="video_profil"
                            value="{{ old('video_profil', $setting->video_profil) }}"
                            placeholder="q-r5HNQrCG0"
                            class="flex-1 px-5 py-4 bg-gray-50 text-[14px] font-bold text-brand-dark outline-none focus:ring-2 focus:ring-brand-blue/20 transition-all">
                    </div>
                    @if($setting->video_profil)
                    <p class="text-[11px] text-gray-400 mt-2 font-medium flex items-center gap-1.5">
                        <i data-feather="play" class="w-3 h-3"></i>
                        Video aktif: <span class="font-black text-brand-blue">{{ $setting->video_profil }}</span>
                    </p>
                    @endif
                </div>

                <div>
                    <label class="block text-[11px] font-black text-gray-500 uppercase tracking-widest mb-2">Brosur Pendaftaran (PDF)</label>
                    <label class="flex items-center gap-4 p-5 bg-gray-50 border-2 border-dashed border-gray-200 rounded-2xl cursor-pointer hover:border-brand-blue hover:bg-blue-50/30 transition-all group">
                        <div class="w-11 h-11 bg-white rounded-xl border border-gray-200 flex items-center justify-center text-gray-400 group-hover:text-brand-blue group-hover:border-brand-blue shrink-0 transition-colors">
                            <i data-feather="file-text" class="w-5 h-5"></i>
                        </div>
                        <div class="flex-1 min-w-0">
                            @if($setting->brosur_path)
                            <p class="text-[13px] font-extrabold text-emerald-700 flex items-center gap-1.5">
                                <i data-feather="check-circle" class="w-3.5 h-3.5"></i> Brosur aktif tersimpan
                            </p>
                            <p class="text-[11px] text-gray-400 font-medium mt-0.5 truncate">{{ $setting->brosur_path }}</p>
                            @else
                            <p class="text-[13px] font-extrabold text-gray-600">Unggah Brosur PDF</p>
                            <p class="text-[11px] text-gray-400 font-medium mt-0.5">Belum ada file. Klik untuk memilih PDF (maks. 5MB).</p>
                            @endif
                        </div>
                        <input type="file" name="brosur" accept="application/pdf" class="sr-only">
                    </label>
                    @if($setting->brosur_path)
                    <div class="mt-2 flex items-center justify-between">
                        <p class="text-[11px] text-gray-400 font-medium">Upload baru untuk mengganti file lama.</p>
                        <a href="{{ asset('uploads/docs/' . $setting->brosur_path) }}" target="_blank"
                           class="text-[11px] font-black text-brand-blue hover:underline flex items-center gap-1">
                            <i data-feather="external-link" class="w-3 h-3"></i> Lihat Brosur
                        </a>
                    </div>
                    @endif
                </div>

                <div>
                    <label class="block text-[11px] font-black text-gray-500 uppercase tracking-widest mb-2">Embed Google Maps (URL src)</label>
                    <textarea name="link_maps" rows="3"
                        placeholder="https://maps.google.com/maps?q=Universitas+Adzkia+Padang&output=embed"
                        class="w-full px-5 py-4 bg-gray-50 border border-gray-100 rounded-2xl text-[13px] font-mono text-gray-600 outline-none focus:ring-2 focus:ring-brand-blue/20 resize-none transition-all">{{ old('link_maps', $setting->link_maps) }}</textarea>
                    <p class="text-[11px] text-gray-400 font-medium mt-1.5">
                        Google Maps &rarr; Bagikan &rarr; Sematkan peta &rarr; salin nilai atribut <code class="bg-gray-100 px-1.5 py-0.5 rounded">src</code>
                    </p>
                </div>

            </form>
        </div>{{-- /tab media --}}


        {{-- ─── TAB GELOMBANG ─────────────────────────────────── --}}
        <div x-show="activeTab === 'gelombang'" x-cloak style="display:none;"
             class="bg-white rounded-[2.5rem] shadow-sm border border-gray-100 overflow-hidden">

            <div class="px-10 py-7 border-b border-gray-100 flex items-center justify-between gap-4">
                <div>
                    <h2 class="text-xl font-extrabold text-brand-dark">Periode Pendaftaran</h2>
                    <p class="text-[13px] text-gray-400 font-medium mt-1">Kelola gelombang penerimaan. Hanya satu yang bisa aktif per tahun.</p>
                </div>
                <button type="button" @click="bukaModalTambah()"
                    class="flex items-center gap-2 px-5 py-2.5 bg-brand-dark text-white rounded-xl font-bold text-[13px] hover:bg-brand-blue transition-all shadow-lg active:scale-95 shrink-0">
                    <i data-feather="plus" class="w-4 h-4"></i> Tambah Gelombang
                </button>
            </div>

            @php $aktif = $gelombangs->firstWhere('is_active', true); @endphp
            @if($aktif)
            @php $sisaHari = max(0, (int) now()->diffInDays(\Carbon\Carbon::parse($aktif->tanggal_selesai), false)); @endphp
            <div class="mx-8 mt-6 bg-brand-blue rounded-2xl p-6 text-white">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                    <div class="flex items-center gap-4">
                        <div class="w-11 h-11 bg-white/20 rounded-xl flex items-center justify-center shrink-0">
                            <i data-feather="calendar" class="w-5 h-5"></i>
                        </div>
                        <div>
                            <p class="text-[10px] font-black uppercase tracking-widest text-blue-200 mb-0.5">Sedang Aktif</p>
                            <p class="text-[16px] font-black">{{ $aktif->nama_gelombang }} &middot; {{ $aktif->tahun }}</p>
                            <p class="text-[12px] text-blue-200 font-medium mt-0.5">
                                {{ \Carbon\Carbon::parse($aktif->tanggal_mulai)->translatedFormat('d F Y') }}
                                &rarr;
                                {{ \Carbon\Carbon::parse($aktif->tanggal_selesai)->translatedFormat('d F Y') }}
                            </p>
                        </div>
                    </div>
                    <div class="flex gap-3 shrink-0">
                        <div class="bg-white/15 rounded-xl px-4 py-3 text-center">
                            <p class="text-[10px] font-black text-blue-200 uppercase">Data Biaya</p>
                            <p class="text-2xl font-black mt-0.5">{{ $aktif->biayaDaftarUlang()->count() }}</p>
                        </div>
                        <div class="bg-white/15 rounded-xl px-4 py-3 text-center">
                            <p class="text-[10px] font-black text-blue-200 uppercase">Sisa Hari</p>
                            <p class="text-2xl font-black mt-0.5 {{ $sisaHari <= 7 ? 'text-yellow-300' : '' }}">
                                {{ $sisaHari > 0 ? $sisaHari : '—' }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>
            @else
            <div class="mx-8 mt-6 bg-amber-50 border border-amber-200 rounded-2xl p-4 flex items-center gap-3">
                <i data-feather="alert-triangle" class="w-5 h-5 text-amber-500 shrink-0"></i>
                <div>
                    <p class="text-[13px] font-extrabold text-amber-900">Tidak ada gelombang yang sedang aktif</p>
                    <p class="text-[12px] text-amber-700 font-medium mt-0.5">Aktifkan salah satu gelombang di bawah.</p>
                </div>
            </div>
            @endif

            <div class="p-8 space-y-3">
                @forelse($gelombangs as $g)
                @php
                    $mulai    = \Carbon\Carbon::parse($g->tanggal_mulai);
                    $selesai  = \Carbon\Carbon::parse($g->tanggal_selesai);
                    $durasi   = $mulai->diffInDays($selesai);
                    $jmlBiaya = $g->biayaDaftarUlang()->count();
                @endphp
                <div class="border rounded-2xl p-5 transition-all {{ $g->is_active ? 'border-brand-blue bg-blue-50/40' : 'border-gray-100 hover:border-gray-200' }}">
                    <div class="flex flex-col sm:flex-row sm:items-center gap-4">

                        <div class="flex items-center gap-3 flex-1 min-w-0">
                            <div class="w-2.5 h-2.5 rounded-full shrink-0 {{ $g->is_active ? 'bg-emerald-500 animate-pulse' : 'bg-gray-300' }}"></div>
                            <div class="min-w-0">
                                <div class="flex items-center gap-2 flex-wrap">
                                    <p class="font-extrabold text-brand-dark">{{ $g->nama_gelombang }}</p>
                                    <span class="text-[11px] font-black text-gray-400">{{ $g->tahun }}</span>
                                    @if($g->is_active)
                                    <span class="px-2 py-0.5 bg-emerald-100 text-emerald-700 rounded-full text-[10px] font-black">Aktif</span>
                                    @endif
                                </div>
                                <p class="text-[12px] text-gray-500 font-medium mt-0.5">
                                    {{ $mulai->translatedFormat('d M Y') }} &rarr; {{ $selesai->translatedFormat('d M Y') }}
                                    <span class="text-gray-400">({{ $durasi }} hari)</span>
                                </p>
                            </div>
                        </div>

                        <div class="shrink-0">
                            @if($jmlBiaya > 0)
                            <a href="{{ route('admin.master.biaya-daftar-ulang.index', ['gelombang_id' => $g->id, 'tahun' => $g->tahun]) }}"
                               class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-emerald-100 text-emerald-700 rounded-lg text-[11px] font-bold hover:bg-emerald-200 transition-colors">
                                <i data-feather="dollar-sign" class="w-3 h-3"></i>
                                {{ $jmlBiaya }} data biaya
                                <i data-feather="external-link" class="w-3 h-3"></i>
                            </a>
                            @else
                            <a href="{{ route('admin.master.biaya-daftar-ulang.index', ['gelombang_id' => $g->id, 'tahun' => $g->tahun]) }}"
                               class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-amber-50 text-amber-600 border border-amber-200 rounded-lg text-[11px] font-bold hover:bg-amber-100 transition-colors">
                                <i data-feather="alert-circle" class="w-3 h-3"></i> Belum ada biaya
                            </a>
                            @endif
                        </div>

                        <div class="flex items-center gap-1.5 shrink-0">
                            <button type="button" @click="bukaModalEdit({{ $g->id }}, {{ json_encode($g) }})"
                                class="p-2 text-brand-blue bg-brand-blue-light hover:bg-blue-100 rounded-lg transition-colors">
                                <i data-feather="edit-2" class="w-4 h-4"></i>
                            </button>
                            @if(!$g->is_active && $jmlBiaya === 0)
                            <form action="{{ route('admin.settings.gelombang.destroy', $g->id) }}" method="POST"
                                  onsubmit="return confirm('Yakin hapus {{ addslashes($g->nama_gelombang) }}?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="p-2 text-red-500 bg-red-50 hover:bg-red-100 rounded-lg transition-colors">
                                    <i data-feather="trash-2" class="w-4 h-4"></i>
                                </button>
                            </form>
                            @else
                            <span class="p-2 text-gray-300 cursor-not-allowed rounded-lg"
                                  title="{{ $g->is_active ? 'Nonaktifkan dulu' : 'Hapus data biaya dulu' }}">
                                <i data-feather="trash-2" class="w-4 h-4"></i>
                            </span>
                            @endif
                        </div>

                    </div>
                </div>
                @empty
                <div class="text-center py-14 text-gray-400">
                    <div class="w-14 h-14 bg-gray-100 rounded-2xl flex items-center justify-center mx-auto mb-3">
                        <i data-feather="calendar" class="w-6 h-6"></i>
                    </div>
                    <p class="text-[14px] font-extrabold mb-1">Belum ada gelombang</p>
                    <p class="text-[12px]">Klik tombol Tambah Gelombang untuk membuat periode pendaftaran.</p>
                </div>
                @endforelse
            </div>

        </div>{{-- /tab gelombang --}}


        {{-- ─── TAB KEAMANAN ──────────────────────────────────── --}}
        <div x-show="activeTab === 'keamanan'" x-cloak style="display:none;">
            <form action="{{ route('admin.settings.password') }}" method="POST"
                  class="bg-white rounded-[2.5rem] p-10 shadow-sm border border-gray-100 space-y-6">
                @csrf

                <div class="border-b border-gray-100 pb-6 flex items-center justify-between">
                    <div>
                        <h2 class="text-xl font-extrabold text-brand-dark">Keamanan Akun</h2>
                        <p class="text-[13px] text-gray-400 font-medium mt-1">Perbarui password akun admin.</p>
                    </div>
                    <button type="submit"
                        class="flex items-center gap-2 px-5 py-2.5 bg-brand-dark text-white rounded-xl font-bold text-[13px] hover:bg-brand-blue transition-all shadow-lg active:scale-95 shrink-0">
                        <i data-feather="lock" class="w-4 h-4"></i> Perbarui Password
                    </button>
                </div>

                <div>
                    <label class="block text-[11px] font-black text-gray-500 uppercase tracking-widest mb-2">Password Saat Ini</label>
                    <input type="password" name="current_password" required
                        placeholder="Masukkan password saat ini"
                        class="w-full px-5 py-4 bg-gray-50 border border-gray-100 rounded-2xl text-[14px] font-bold text-brand-dark outline-none focus:ring-2 focus:ring-brand-blue/20 focus:border-brand-blue transition-all">
                    @error('current_password')
                    <p class="text-red-500 text-[12px] font-bold mt-2 flex items-center gap-1">
                        <i data-feather="alert-circle" class="w-3.5 h-3.5"></i> {{ $message }}
                    </p>
                    @enderror
                </div>

                <div class="h-px bg-gray-100"></div>

                <div>
                    <label class="block text-[11px] font-black text-gray-500 uppercase tracking-widest mb-2">Password Baru</label>
                    <input type="password" name="new_password" required
                        placeholder="Minimal 8 karakter"
                        class="w-full px-5 py-4 bg-gray-50 border border-gray-100 rounded-2xl text-[14px] font-bold text-brand-dark outline-none focus:ring-2 focus:ring-brand-blue/20 focus:border-brand-blue transition-all">
                    @error('new_password')
                    <p class="text-red-500 text-[12px] font-bold mt-2 flex items-center gap-1">
                        <i data-feather="alert-circle" class="w-3.5 h-3.5"></i> {{ $message }}
                    </p>
                    @enderror
                </div>

                <div>
                    <label class="block text-[11px] font-black text-gray-500 uppercase tracking-widest mb-2">Konfirmasi Password Baru</label>
                    <input type="password" name="new_password_confirmation" required
                        placeholder="Ketik ulang password baru"
                        class="w-full px-5 py-4 bg-gray-50 border border-gray-100 rounded-2xl text-[14px] font-bold text-brand-dark outline-none focus:ring-2 focus:ring-brand-blue/20 focus:border-brand-blue transition-all">
                </div>

                <div class="p-4 bg-blue-50 border border-blue-100 rounded-2xl text-[12px] font-medium text-blue-700 flex items-start gap-2">
                    <i data-feather="info" class="w-4 h-4 shrink-0 mt-0.5"></i>
                    <span>Password baru minimal 8 karakter. Gunakan kombinasi huruf, angka, dan simbol untuk keamanan terbaik.</span>
                </div>

            </form>
        </div>{{-- /tab keamanan --}}


    </div>{{-- /col-span-8 --}}
</div>{{-- /grid --}}


{{-- ══ MODAL TAMBAH / EDIT GELOMBANG ════════════════════════════ --}}
<div x-show="modalGelombang" class="fixed inset-0 z-[999] flex items-center justify-center p-4" style="display:none;">
    <div x-show="modalGelombang" x-transition.opacity @click="modalGelombang = false"
         class="absolute inset-0 bg-brand-dark/60 backdrop-blur-sm cursor-pointer"></div>

    <div x-show="modalGelombang"
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0 scale-95 translate-y-4"
         x-transition:enter-end="opacity-100 scale-100 translate-y-0"
         class="bg-white w-full max-w-md rounded-[2rem] shadow-2xl relative z-10 flex flex-col"
         style="max-height: 90vh;">

        <div class="px-7 py-5 border-b border-gray-100 flex items-center justify-between bg-gray-50/50 shrink-0 rounded-t-[2rem]">
            <div>
                <h2 class="text-[17px] font-extrabold text-brand-dark"
                    x-text="isEditGelombang ? 'Ubah Gelombang' : 'Tambah Gelombang Baru'"></h2>
                <p class="text-[12px] text-gray-400 font-medium mt-0.5">Isi detail periode gelombang pendaftaran</p>
            </div>
            <button type="button" @click="modalGelombang = false"
                class="p-2 bg-white border border-gray-200 hover:bg-gray-100 rounded-full transition-colors shrink-0">
                <i data-feather="x" class="w-4 h-4 text-gray-500"></i>
            </button>
        </div>

        <form :action="isEditGelombang
                ? '/admin/settings/gelombang/' + gelombangForm.id
                : '{{ route('admin.settings.gelombang.store') }}'"
              method="POST" class="flex flex-col flex-1 min-h-0">
            @csrf
            <template x-if="isEditGelombang">
                <input type="hidden" name="_method" value="PUT">
            </template>

            <div class="px-7 py-6 space-y-5 overflow-y-auto flex-1">

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-[11px] font-black text-gray-400 uppercase tracking-widest mb-2">Nama Gelombang</label>
                        <input type="text" name="nama_gelombang"
                            x-model="gelombangForm.nama_gelombang" required
                            class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 text-[13px] font-bold text-brand-dark outline-none focus:border-brand-blue focus:bg-white transition-all">
                        <p x-show="!isEditGelombang" class="text-[10px] text-gray-400 mt-1">Otomatis, bisa diubah manual</p>
                    </div>
                    <div>
                        <label class="block text-[11px] font-black text-gray-400 uppercase tracking-widest mb-2">Tahun</label>
                        <input type="number" name="tahun"
                            x-model="gelombangForm.tahun" required min="2020" max="2040"
                            @change="if(!isEditGelombang) gelombangForm.nama_gelombang = getNamaOtomatis($event.target.value)"
                            class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 text-[13px] font-bold text-brand-dark outline-none focus:border-brand-blue focus:bg-white transition-all">
                    </div>
                </div>

                <div>
                    <label class="block text-[11px] font-black text-gray-400 uppercase tracking-widest mb-2">Periode Pendaftaran</label>
                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <p class="text-[11px] text-gray-400 font-bold mb-1.5">Tanggal Mulai</p>
                            <input type="date" name="tanggal_mulai"
                                x-model="gelombangForm.tanggal_mulai" required
                                class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 text-[13px] font-bold text-brand-dark outline-none focus:border-brand-blue focus:bg-white transition-all cursor-pointer">
                        </div>
                        <div>
                            <p class="text-[11px] text-gray-400 font-bold mb-1.5">Tanggal Berakhir</p>
                            <input type="date" name="tanggal_selesai"
                                x-model="gelombangForm.tanggal_selesai" required
                                class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 text-[13px] font-bold text-brand-dark outline-none focus:border-brand-blue focus:bg-white transition-all cursor-pointer">
                        </div>
                    </div>
                    <p class="text-[11px] text-brand-blue font-bold mt-2 text-center"
                       x-show="gelombangForm.tanggal_mulai && gelombangForm.tanggal_selesai"
                       x-text="'Durasi: ' + hitungDurasi()"></p>
                </div>

                <div>
                    <label class="block text-[11px] font-black text-gray-400 uppercase tracking-widest mb-3">Status</label>
                    <label class="flex items-center gap-4 p-4 rounded-xl border-2 cursor-pointer transition-all"
                           :class="gelombangForm.is_active ? 'border-emerald-400 bg-emerald-50' : 'border-gray-200 bg-gray-50 hover:border-gray-300'">
                        <input type="hidden" name="is_active" :value="gelombangForm.is_active ? 1 : 0">
                        <input type="checkbox" x-model="gelombangForm.is_active" class="sr-only">
                        <div class="w-10 h-6 rounded-full relative shrink-0 transition-colors"
                             :class="gelombangForm.is_active ? 'bg-emerald-500' : 'bg-gray-300'">
                            <div class="w-4 h-4 bg-white rounded-full absolute top-1 shadow transition-transform"
                                 :class="gelombangForm.is_active ? 'translate-x-5' : 'translate-x-1'"></div>
                        </div>
                        <div>
                            <p class="text-[13px] font-extrabold"
                               :class="gelombangForm.is_active ? 'text-emerald-700' : 'text-gray-500'"
                               x-text="gelombangForm.is_active ? 'Aktifkan gelombang ini' : 'Nonaktif'"></p>
                            <p class="text-[11px] text-gray-400 font-medium mt-0.5">
                                Mengaktifkan akan menonaktifkan gelombang lain di tahun yang sama
                            </p>
                        </div>
                    </label>
                </div>

            </div>

            <div class="px-7 py-5 border-t border-gray-100 bg-gray-50/50 shrink-0 flex gap-3 rounded-b-[2rem]">
                <button type="button" @click="modalGelombang = false"
                    class="flex-1 py-3 border border-gray-200 text-gray-600 bg-white hover:bg-gray-50 rounded-xl font-bold text-[13px] transition-colors">
                    Batal
                </button>
                <button type="submit"
                    class="flex-1 py-3 bg-brand-dark text-white hover:bg-brand-blue rounded-xl font-bold text-[13px] transition-all shadow-lg"
                    x-text="isEditGelombang ? 'Simpan Perubahan' : 'Tambah Gelombang'">
                </button>
            </div>
        </form>

    </div>
</div>{{-- /modal --}}

</div>{{-- /x-data --}}


<script>
function settingsApp() {
    return {
        activeTab:      '{{ $activeTabInit }}',
        maintenanceMode: {{ $maintenanceInit }},
        pendaftaranBuka: {{ $pendaftaranInit }},
        modalGelombang:  false,
        isEditGelombang: false,
        countPerTahun:   {!! $countJson !!},

        gelombangForm: {
            id: null, nama_gelombang: '', tahun: {{ $tahunSekarang }},
            tanggal_mulai: '', tanggal_selesai: '', is_active: false,
        },

        getNamaOtomatis(tahun) {
            const jumlah = parseInt(this.countPerTahun[tahun] || 0);
            return 'Gelombang ' + (jumlah + 1);
        },

        bukaModalTambah() {
            this.isEditGelombang = false;
            const tahun = {{ $tahunSekarang }};
            this.gelombangForm = {
                id: null,
                nama_gelombang: this.getNamaOtomatis(tahun),
                tahun: tahun,
                tanggal_mulai: '',
                tanggal_selesai: '',
                is_active: false,
            };
            this.modalGelombang = true;
            this.$nextTick(() => { if (window.feather) feather.replace(); });
        },

        bukaModalEdit(id, data) {
            this.isEditGelombang = true;
            this.gelombangForm = {
                id:              id,
                nama_gelombang:  data.nama_gelombang,
                tahun:           data.tahun,
                tanggal_mulai:   data.tanggal_mulai   ? data.tanggal_mulai.substring(0, 10)   : '',
                tanggal_selesai: data.tanggal_selesai ? data.tanggal_selesai.substring(0, 10) : '',
                is_active:       !!data.is_active,
            };
            this.modalGelombang = true;
            this.$nextTick(() => { if (window.feather) feather.replace(); });
        },

        hitungDurasi() {
            if (!this.gelombangForm.tanggal_mulai || !this.gelombangForm.tanggal_selesai) return '';
            const a = new Date(this.gelombangForm.tanggal_mulai);
            const b = new Date(this.gelombangForm.tanggal_selesai);
            const d = Math.max(0, Math.round((b - a) / 86400000));
            return d + ' hari';
        },
    };
}

document.addEventListener('alpine:initialized', () => {
    const obs = new MutationObserver(() => { if (window.feather) feather.replace(); });
    obs.observe(document.body, { childList: true, subtree: true });
    setTimeout(() => { if (window.feather) feather.replace(); }, 50);
});
</script>

@endsection