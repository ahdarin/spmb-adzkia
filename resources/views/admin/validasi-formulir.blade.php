@extends('layouts.admin')

@section('title', 'Validasi Formulir Pendaftaran')

@section('admin-content')
<div class="min-h-screen bg-slate-50 p-6">

    {{-- =============================================
         HEADER
    ============================================== --}}
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-slate-800">Validasi Formulir Pendaftaran</h1>
        <p class="text-sm text-slate-500 mt-1">Tinjau biodata dan kelengkapan berkas dokumen calon mahasiswa baru.</p>
    </div>

    {{-- =============================================
         FLASH MESSAGE
    ============================================== --}}
    @if(session('success'))
    <div class="mb-4 flex items-start gap-3 bg-emerald-50 border border-emerald-200 text-emerald-800 rounded-xl px-4 py-3 text-sm">
        <svg class="w-5 h-5 mt-0.5 shrink-0 text-emerald-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
        <span>{{ session('success') }}</span>
    </div>
    @endif
    @if(session('error'))
    <div class="mb-4 flex items-start gap-3 bg-red-50 border border-red-200 text-red-800 rounded-xl px-4 py-3 text-sm">
        <svg class="w-5 h-5 mt-0.5 shrink-0 text-red-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
        <span>{{ session('error') }}</span>
    </div>
    @endif

    {{-- =============================================
         FILTER & SEARCH
    ============================================== --}}
    <div class="bg-white border border-slate-200 rounded-2xl p-4 mb-5 shadow-sm">
        <form method="GET" action="{{ route('admin.validasi.formulir') }}" class="flex flex-col sm:flex-row gap-3">
            <div class="relative flex-1">
                <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-4.35-4.35M17 11A6 6 0 1 1 5 11a6 6 0 0 1 12 0z"/></svg>
                <input type="text" name="search" value="{{ request('search') }}"
                    placeholder="Cari nama atau no. pendaftaran..."
                    class="w-full pl-9 pr-4 py-2 text-sm border border-slate-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
            </div>
            <select name="status" class="px-3 py-2 text-sm border border-slate-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 bg-white text-slate-700">
                <option value="">Semua Status</option>
                <option value="menunggu verifikasi" @selected(request('status') === 'menunggu verifikasi')>Menunggu Verifikasi</option>
                <option value="Revisi" @selected(request('status') === 'Revisi')>Revisi</option>
                <option value="Selesai" @selected(request('status') === 'Selesai')>Selesai</option>
            </select>
            <button type="submit" class="px-4 py-2 text-sm font-medium bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                Filter
            </button>
            @if(request('search') || request('status'))
            <a href="{{ route('admin.validasi.formulir') }}" class="px-4 py-2 text-sm font-medium bg-slate-100 text-slate-600 rounded-lg hover:bg-slate-200 transition-colors">
                Reset
            </a>
            @endif
        </form>
    </div>

    {{-- =============================================
         TABEL DATA
    ============================================== --}}
    <div class="bg-white border border-slate-200 rounded-2xl shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="bg-slate-50 border-b border-slate-200">
                        <th class="text-left px-5 py-3.5 font-semibold text-slate-600">#</th>
                        <th class="text-left px-5 py-3.5 font-semibold text-slate-600">No. Pendaftaran</th>
                        <th class="text-left px-5 py-3.5 font-semibold text-slate-600">Nama Lengkap</th>
                        <th class="text-left px-5 py-3.5 font-semibold text-slate-600">Pilihan Prodi</th>
                        <th class="text-left px-5 py-3.5 font-semibold text-slate-600">Jalur</th>
                        <th class="text-left px-5 py-3.5 font-semibold text-slate-600">Status</th>
                        <th class="text-center px-5 py-3.5 font-semibold text-slate-600">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                @forelse($pendaftars as $index => $item)
                    {{-- Siapkan data berkas JSON --}}
                    @php
                        $berkas = [];
                        if (!empty($item->berkas_dokumen)) {
                            $decoded = is_array($item->berkas_dokumen)
                                ? $item->berkas_dokumen
                                : json_decode($item->berkas_dokumen, true);
                            $berkas = $decoded ?? [];
                        }

                        // Label ramah untuk tiap key berkas
                        $labelBerkas = [
                            'ktp'           => 'KTP / Kartu Identitas',
                            'kk'            => 'Kartu Keluarga',
                            'rapor'         => 'Rapor / Transkrip Nilai',
                            'ijazah'        => 'Ijazah / SKL',
                            'foto'          => 'Pas Foto',
                            'sertifikat'    => 'Sertifikat Prestasi',
                            'surat_rekomendasi' => 'Surat Rekomendasi',
                        ];

                        $statusColor = match($item->status_pendaftaran) {
                            'menunggu verifikasi' => 'bg-amber-100 text-amber-700 border-amber-200',
                            'Revisi'              => 'bg-red-100 text-red-700 border-red-200',
                            'Selesai'             => 'bg-emerald-100 text-emerald-700 border-emerald-200',
                            default               => 'bg-slate-100 text-slate-600 border-slate-200',
                        };
                        $statusLabel = match($item->status_pendaftaran) {
                            'menunggu verifikasi' => 'Menunggu Verifikasi',
                            default               => $item->status_pendaftaran,
                        };

                        // Encode data untuk Alpine.js
                        $alpineData = json_encode([
                            'id'                 => $item->id,
                            'no_pendaftaran'     => $item->no_pendaftaran,
                            'nama_lengkap'       => $item->nama_lengkap,
                            'nik'                => $item->nik ?? '-',
                            'tempat_lahir'       => $item->tempat_lahir ?? '-',
                            'tanggal_lahir'      => $item->tanggal_lahir ?? '-',
                            'gender'             => $item->gender ?? '-',
                            'agama'              => $item->agama ?? '-',
                            'no_whatsapp'        => $item->no_whatsapp ?? '-',
                            'email'              => $item->email ?? '-',
                            'alamat'             => trim(($item->alamat_rumah ?? '') . ($item->kota_kabupaten ? ', ' . $item->kota_kabupaten : '') . ($item->provinsi ? ', ' . $item->provinsi : ''), ', ') ?: '-',
                            'asal_sekolah'       => $item->sekolah_asal ?? '-',
                            'jurusan_sekolah'    => $item->jurusan_sma ?? '-',
                            'tahun_lulus'        => $item->tahun_lulus ?? '-',
                            'pilihan_jurusan_1'  => $item->pilihan_jurusan_1 ?? '-',
                            'pilihan_jurusan_2'  => $item->pilihan_jurusan_2 ?? '-',
                            'jalur_pendaftaran'  => $item->jalur_pendaftaran ?? '-',
                            'status_pendaftaran' => $item->status_pendaftaran,
                            'pesan_revisi'       => $item->pesan_revisi ?? '',
                            'berkas'             => $berkas,
                            'labelBerkas'        => $labelBerkas,
                        ], JSON_HEX_APOS | JSON_HEX_QUOT);
                    @endphp

                    <tr class="hover:bg-slate-50 transition-colors">
                        <td class="px-5 py-4 text-slate-400 font-mono text-xs">{{ $index + 1 }}</td>
                        <td class="px-5 py-4 font-mono text-xs font-semibold text-slate-700">{{ $item->no_pendaftaran }}</td>
                        <td class="px-5 py-4">
                            <span class="font-medium text-slate-800">{{ $item->nama_lengkap }}</span>
                        </td>
                        <td class="px-5 py-4 text-slate-600">
                            <div>{{ $item->pilihan_jurusan_1 ?? '-' }}</div>
                            @if($item->pilihan_jurusan_2)
                            <div class="text-xs text-slate-400">Pilihan 2: {{ $item->pilihan_jurusan_2 }}</div>
                            @endif
                        </td>
                        <td class="px-5 py-4 text-slate-600">{{ $item->jalur_pendaftaran ?? '-' }}</td>
                        <td class="px-5 py-4">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium border {{ $statusColor }}">
                                {{ $statusLabel }}
                            </span>
                        </td>
                        <td class="px-5 py-4 text-center">
                            <button
                                type="button"
                                x-data
                                @click="$dispatch('open-formulir-modal', {{ $alpineData }})"
                                class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-medium bg-blue-50 text-blue-700 border border-blue-200 rounded-lg hover:bg-blue-100 transition-colors">
                                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                Tinjau
                            </button>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="px-5 py-16 text-center">
                            <div class="flex flex-col items-center gap-2 text-slate-400">
                                <svg class="w-10 h-10" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                <p class="font-medium">Tidak ada formulir yang perlu ditinjau</p>
                                <p class="text-xs">Formulir akan muncul saat pendaftar telah mengisi biodata lengkap</p>
                            </div>
                        </td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>

{{-- =============================================
     MODAL DETAIL & VALIDASI FORMULIR (Alpine.js)
============================================== --}}
<div
    x-data="{
        open: false,
        activeTab: 'biodata',
        showRevisiForm: false,
        pesanRevisi: '',
        pendaftar: {},
        init() {
            window.addEventListener('open-formulir-modal', (e) => {
                this.pendaftar    = e.detail;
                this.activeTab    = 'biodata';
                this.showRevisiForm = false;
                this.pesanRevisi  = '';
                this.open         = true;
            });
        },
        getStatusClass(status) {
            const map = {
                'menunggu verifikasi': 'bg-amber-100 text-amber-700 border-amber-200',
                'Revisi':              'bg-red-100 text-red-700 border-red-200',
                'Selesai':             'bg-emerald-100 text-emerald-700 border-emerald-200',
            };
            return map[status] || 'bg-slate-100 text-slate-600 border-slate-200';
        },
        getStatusLabel(status) {
            return status === 'menunggu verifikasi' ? 'Menunggu Verifikasi' : (status || 'Draft');
        },
        getBerkasUrl(path) {
            return '/' + path;
        },
        getBerkasLabel(key) {
            const labels = {
                'ktp': 'KTP / Kartu Identitas',
                'kk': 'Kartu Keluarga',
                'rapor': 'Rapor / Transkrip Nilai',
                'ijazah': 'Ijazah / SKL',
                'foto': 'Pas Foto',
                'sertifikat': 'Sertifikat Prestasi',
                'surat_rekomendasi': 'Surat Rekomendasi',
            };
            return labels[key] || key.replace(/_/g, ' ').replace(/\b\w/g, l => l.toUpperCase());
        },
        berkasEntries() {
            if (!this.pendaftar.berkas || typeof this.pendaftar.berkas !== 'object') return [];
            return Object.entries(this.pendaftar.berkas).filter(([k, v]) => v);
        }
    }"
    x-show="open"
    x-cloak
    class="fixed inset-0 z-50 flex items-start justify-center pt-6 pb-6 px-4 overflow-y-auto"
    style="display: none;"
>
    {{-- Backdrop --}}
    <div
        class="fixed inset-0 bg-slate-900/50 backdrop-blur-sm"
        @click="open = false"
        x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="transition ease-in duration-150"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
    ></div>

    {{-- Panel Modal --}}
    <div
        class="relative w-full max-w-3xl bg-white rounded-2xl shadow-xl z-10"
        x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="opacity-0 scale-95"
        x-transition:enter-end="opacity-100 scale-100"
        x-transition:leave="transition ease-in duration-150"
        x-transition:leave-start="opacity-100 scale-100"
        x-transition:leave-end="opacity-0 scale-95"
        @click.stop
    >
        {{-- Modal Header --}}
        <div class="flex items-start justify-between px-6 py-5 border-b border-slate-100">
            <div>
                <h2 class="text-lg font-bold text-slate-800" x-text="pendaftar.nama_lengkap"></h2>
                <div class="flex items-center gap-2 mt-1">
                    <span class="font-mono text-xs text-slate-500" x-text="pendaftar.no_pendaftaran"></span>
                    <span
                        class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium border"
                        :class="getStatusClass(pendaftar.status_pendaftaran)"
                        x-text="getStatusLabel(pendaftar.status_pendaftaran)"
                    ></span>
                </div>
            </div>
            <button @click="open = false" class="p-2 rounded-lg text-slate-400 hover:bg-slate-100 hover:text-slate-600 transition-colors">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>

        {{-- Tab Nav --}}
        <div class="flex gap-1 px-6 pt-4 border-b border-slate-100">
            <button
                @click="activeTab = 'biodata'"
                :class="activeTab === 'biodata' ? 'border-b-2 border-blue-600 text-blue-600 font-semibold' : 'text-slate-500 hover:text-slate-700'"
                class="px-4 py-2 text-sm transition-colors -mb-px">
                Biodata
            </button>
            <button
                @click="activeTab = 'berkas'"
                :class="activeTab === 'berkas' ? 'border-b-2 border-blue-600 text-blue-600 font-semibold' : 'text-slate-500 hover:text-slate-700'"
                class="px-4 py-2 text-sm transition-colors -mb-px">
                Berkas Dokumen
            </button>
            <button
                @click="activeTab = 'aksi'"
                :class="activeTab === 'aksi' ? 'border-b-2 border-blue-600 text-blue-600 font-semibold' : 'text-slate-500 hover:text-slate-700'"
                class="px-4 py-2 text-sm transition-colors -mb-px">
                Keputusan
            </button>
        </div>

        {{-- Modal Body --}}
        <div class="px-6 py-5 max-h-[60vh] overflow-y-auto">

            {{-- ===== TAB: BIODATA ===== --}}
            <div x-show="activeTab === 'biodata'" x-cloak>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">

                    <div class="sm:col-span-2">
                        <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider mb-3">Informasi Pribadi</p>
                    </div>

                    <div>
                        <p class="text-xs text-slate-400 mb-0.5">NIK</p>
                        <p class="text-sm font-mono font-medium text-slate-800" x-text="pendaftar.nik"></p>
                    </div>
                    <div>
                        <p class="text-xs text-slate-400 mb-0.5">Jenis Kelamin</p>
                        <p class="text-sm text-slate-800" x-text="pendaftar.gender"></p>
                    </div>
                    <div>
                        <p class="text-xs text-slate-400 mb-0.5">Tempat Lahir</p>
                        <p class="text-sm text-slate-800" x-text="pendaftar.tempat_lahir"></p>
                    </div>
                    <div>
                        <p class="text-xs text-slate-400 mb-0.5">Tanggal Lahir</p>
                        <p class="text-sm text-slate-800" x-text="pendaftar.tanggal_lahir"></p>
                    </div>
                    <div>
                        <p class="text-xs text-slate-400 mb-0.5">Agama</p>
                        <p class="text-sm text-slate-800" x-text="pendaftar.agama"></p>
                    </div>
                    <div>
                        <p class="text-xs text-slate-400 mb-0.5">No. WhatsApp</p>
                        <p class="text-sm text-slate-800" x-text="pendaftar.no_whatsapp"></p>
                    </div>
                    <div class="sm:col-span-2">
                        <p class="text-xs text-slate-400 mb-0.5">Email</p>
                        <p class="text-sm text-slate-800" x-text="pendaftar.email"></p>
                    </div>
                    <div class="sm:col-span-2">
                        <p class="text-xs text-slate-400 mb-0.5">Alamat Lengkap</p>
                        <p class="text-sm text-slate-800" x-text="pendaftar.alamat"></p>
                    </div>

                    {{-- Divider --}}
                    <div class="sm:col-span-2 border-t border-slate-100 pt-4">
                        <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider mb-3">Asal Sekolah</p>
                    </div>

                    <div class="sm:col-span-2">
                        <p class="text-xs text-slate-400 mb-0.5">Nama Sekolah</p>
                        <p class="text-sm text-slate-800" x-text="pendaftar.asal_sekolah"></p>
                    </div>
                    <div>
                        <p class="text-xs text-slate-400 mb-0.5">Jurusan / Program</p>
                        <p class="text-sm text-slate-800" x-text="pendaftar.jurusan_sekolah"></p>
                    </div>
                    <div>
                        <p class="text-xs text-slate-400 mb-0.5">Tahun Lulus</p>
                        <p class="text-sm text-slate-800" x-text="pendaftar.tahun_lulus"></p>
                    </div>

                    {{-- Divider --}}
                    <div class="sm:col-span-2 border-t border-slate-100 pt-4">
                        <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider mb-3">Pilihan Program Studi</p>
                    </div>

                    <div>
                        <p class="text-xs text-slate-400 mb-0.5">Pilihan 1</p>
                        <p class="text-sm font-semibold text-blue-700" x-text="pendaftar.pilihan_jurusan_1"></p>
                    </div>
                    <div>
                        <p class="text-xs text-slate-400 mb-0.5">Pilihan 2</p>
                        <p class="text-sm text-slate-800" x-text="pendaftar.pilihan_jurusan_2 || '-'"></p>
                    </div>
                    <div>
                        <p class="text-xs text-slate-400 mb-0.5">Jalur Pendaftaran</p>
                        <p class="text-sm text-slate-800" x-text="pendaftar.jalur_pendaftaran"></p>
                    </div>
                </div>
            </div>

            {{-- ===== TAB: BERKAS DOKUMEN ===== --}}
            <div x-show="activeTab === 'berkas'" x-cloak>
                <template x-if="berkasEntries().length === 0">
                    <div class="flex flex-col items-center justify-center py-12 text-slate-400">
                        <svg class="w-10 h-10 mb-2" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 13h6m-3-3v6m5 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                        <p class="text-sm font-medium">Belum ada berkas diunggah</p>
                        <p class="text-xs mt-1">Pendaftar belum mengunggah dokumen apapun</p>
                    </div>
                </template>
                <template x-if="berkasEntries().length > 0">
                    <div class="space-y-2">
                        <p class="text-xs text-slate-400 mb-3">
                            <span x-text="berkasEntries().length"></span> berkas ditemukan
                        </p>
                        <template x-for="([key, path], idx) in berkasEntries()" :key="idx">
                            <div class="flex items-center justify-between px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl">
                                <div class="flex items-center gap-3">
                                    <div class="w-8 h-8 rounded-lg bg-blue-100 flex items-center justify-center shrink-0">
                                        <svg class="w-4 h-4 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                    </div>
                                    <div>
                                        <p class="text-sm font-medium text-slate-700" x-text="getBerkasLabel(key)"></p>
                                        <p class="text-xs text-slate-400 font-mono" x-text="path.split('/').pop()"></p>
                                    </div>
                                </div>
                                <a
                                    :href="getBerkasUrl(path)"
                                    target="_blank"
                                    rel="noopener noreferrer"
                                    class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-medium bg-white border border-slate-200 text-slate-600 rounded-lg hover:bg-slate-100 transition-colors shrink-0">
                                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                                    Buka File
                                </a>
                            </div>
                        </template>
                    </div>
                </template>
            </div>

            {{-- ===== TAB: KEPUTUSAN ADMIN ===== --}}
            <div x-show="activeTab === 'aksi'" x-cloak>

                {{-- Pesan Revisi Sebelumnya --}}
                <template x-if="pendaftar.pesan_revisi">
                    <div class="mb-4 p-4 bg-amber-50 border border-amber-200 rounded-xl">
                        <p class="text-xs font-semibold text-amber-700 mb-1">Catatan Revisi Sebelumnya</p>
                        <p class="text-sm text-amber-800" x-text="pendaftar.pesan_revisi"></p>
                    </div>
                </template>

                {{-- Sudah Selesai --}}
                <template x-if="pendaftar.status_pendaftaran === 'Selesai'">
                    <div class="flex flex-col items-center justify-center py-10 text-center">
                        <div class="w-14 h-14 rounded-full bg-emerald-100 flex items-center justify-center mb-3">
                            <svg class="w-7 h-7 text-emerald-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                        </div>
                        <p class="font-semibold text-slate-800">Formulir Telah Diverifikasi</p>
                        <p class="text-sm text-slate-500 mt-1">Tidak ada tindakan lebih lanjut yang diperlukan.</p>
                    </div>
                </template>

                {{-- Form Aksi untuk status selain Selesai --}}
                <template x-if="pendaftar.status_pendaftaran !== 'Selesai'">
                    <div class="space-y-4">

                        {{-- Setujui Formulir --}}
                        <div x-show="!showRevisiForm">
                            <div class="p-4 bg-emerald-50 border border-emerald-200 rounded-xl mb-4">
                                <p class="text-sm font-semibold text-emerald-800 mb-1">Setujui Formulir</p>
                                <p class="text-xs text-emerald-700">Formulir pendaftar dinyatakan lengkap dan benar. Status akan berubah menjadi <strong>Selesai</strong> dan notifikasi WhatsApp akan dikirim.</p>
                            </div>
                            <form :action="'/admin/setujui-formulir/' + pendaftar.id" method="POST">
                                @csrf
                                <button type="submit"
                                    onclick="return confirm('Setujui formulir pendaftar ini?')"
                                    class="w-full py-2.5 text-sm font-semibold bg-emerald-600 text-white rounded-xl hover:bg-emerald-700 transition-colors">
                                    Setujui Formulir Ini
                                </button>
                            </form>
                        </div>

                        {{-- Divider --}}
                        <div x-show="!showRevisiForm" class="flex items-center gap-3">
                            <div class="flex-1 h-px bg-slate-200"></div>
                            <span class="text-xs text-slate-400">atau</span>
                            <div class="flex-1 h-px bg-slate-200"></div>
                        </div>

                        {{-- Tombol Minta Revisi --}}
                        <div x-show="!showRevisiForm">
                            <button
                                @click="showRevisiForm = true"
                                class="w-full py-2.5 text-sm font-semibold bg-white border border-red-300 text-red-600 rounded-xl hover:bg-red-50 transition-colors">
                                Minta Revisi Formulir
                            </button>
                        </div>

                        {{-- Form Revisi --}}
                        <div x-show="showRevisiForm"
                            x-transition:enter="transition ease-out duration-200"
                            x-transition:enter-start="opacity-0 translate-y-2"
                            x-transition:enter-end="opacity-100 translate-y-0">
                            <div class="p-4 bg-red-50 border border-red-200 rounded-xl mb-4">
                                <p class="text-sm font-semibold text-red-800 mb-1">Minta Revisi</p>
                                <p class="text-xs text-red-700">Jelaskan secara spesifik dokumen atau data apa yang perlu diperbaiki pendaftar.</p>
                            </div>
                            <form :action="'/admin/revisi-formulir/' + pendaftar.id" method="POST" class="space-y-3">
                                @csrf
                                <div>
                                    <label class="block text-xs font-medium text-slate-700 mb-1.5">Pesan Revisi untuk Pendaftar <span class="text-red-500">*</span></label>
                                    <textarea
                                        name="pesan_revisi"
                                        x-model="pesanRevisi"
                                        rows="4"
                                        placeholder="Contoh: Foto KTP yang diunggah buram, mohon unggah ulang dengan foto yang jelas..."
                                        required
                                        class="w-full px-3 py-2.5 text-sm border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-transparent resize-none"
                                    ></textarea>
                                </div>
                                <div class="flex gap-2">
                                    <button type="button" @click="showRevisiForm = false"
                                        class="flex-1 py-2.5 text-sm font-medium bg-slate-100 text-slate-600 rounded-xl hover:bg-slate-200 transition-colors">
                                        Batal
                                    </button>
                                    <button type="submit"
                                        :disabled="!pesanRevisi.trim()"
                                        class="flex-1 py-2.5 text-sm font-semibold bg-red-600 text-white rounded-xl hover:bg-red-700 transition-colors disabled:opacity-50 disabled:cursor-not-allowed">
                                        Kirim Revisi
                                    </button>
                                </div>
                            </form>
                        </div>

                    </div>
                </template>
            </div>
        </div>

        {{-- Modal Footer --}}
        <div class="px-6 py-4 border-t border-slate-100 flex justify-end">
            <button @click="open = false"
                class="px-4 py-2 text-sm font-medium text-slate-600 bg-slate-100 rounded-lg hover:bg-slate-200 transition-colors">
                Tutup
            </button>
        </div>
    </div>
</div>
@endsection