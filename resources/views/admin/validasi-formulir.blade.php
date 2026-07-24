@extends('layouts.admin')

@section('title', 'Validasi Formulir Pendaftaran')

@section('admin-content')
<div>
    <div class="mb-8">
        <h1 class="text-3xl font-extrabold text-brand-dark tracking-tight mb-2">Validasi Formulir Pendaftaran</h1>
        <p class="text-brand-gray text-[14px] font-medium">Tinjau biodata dan kelengkapan berkas dokumen calon mahasiswa baru.</p>
    </div>

    {{-- FLASH MESSAGE --}}
    @if(session('success'))
        <div class="bg-green-50 border-l-4 border-green-500 p-4 mb-6 rounded-xl flex items-start gap-3">
            <i data-feather="check-circle" class="w-5 h-5 text-green-600"></i>
            <p class="text-sm font-bold text-green-700">{{ session('success') }}</p>
        </div>
    @endif
    @if(session('error'))
        <div class="bg-red-50 border-l-4 border-red-500 p-4 mb-6 rounded-xl flex items-start gap-3">
            <i data-feather="alert-circle" class="w-5 h-5 text-red-600"></i>
            <p class="text-sm font-bold text-red-700">{{ session('error') }}</p>
        </div>
    @endif

    {{-- FILTER & SEARCH --}}
    <form method="GET" action="{{ route('admin.validasi.formulir') }}" class="bg-white p-4 rounded-2xl shadow-sm border border-gray-100 mb-6 flex flex-wrap items-center gap-4">
        <div class="relative flex-1 min-w-[220px]">
            <i data-feather="search" class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400"></i>
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama atau no. pendaftaran..."
                class="w-full pl-10 pr-4 py-2 border border-gray-200 rounded-lg text-[13px] font-medium focus:ring-2 focus:ring-brand-blue outline-none transition-all placeholder-gray-400">
        </div>
        <div class="flex items-center gap-3">
            <span class="text-[12px] font-extrabold text-gray-400 uppercase tracking-widest">Status:</span>
            <select name="status" class="border border-gray-200 rounded-lg px-3 py-2 outline-none focus:border-brand-blue bg-gray-50 text-[13px] font-bold text-brand-dark cursor-pointer min-w-[180px]">
                <option value="">Semua Status</option>
                <option value="menunggu verifikasi" @selected(request('status') === 'menunggu verifikasi')>Menunggu Verifikasi</option>
                <option value="Revisi" @selected(request('status') === 'Revisi')>Revisi</option>
                <option value="Selesai" @selected(request('status') === 'Selesai')>Selesai</option>
            </select>
        </div>
        <button type="submit" class="px-5 py-2.5 bg-brand-dark text-white rounded-xl font-bold text-[13px] hover:bg-brand-blue transition-colors shadow-sm">
            Filter
        </button>
        @if(request('search') || request('status'))
        <a href="{{ route('admin.validasi.formulir') }}" class="text-[13px] font-extrabold text-brand-blue hover:text-brand-dark transition-colors whitespace-nowrap flex items-center gap-1.5">
            <i data-feather="refresh-ccw" class="w-3.5 h-3.5"></i> Reset
        </a>
        @endif
    </form>

    {{-- TABEL DATA --}}
    <div class="bg-white rounded-[2rem] shadow-sm border border-gray-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left whitespace-nowrap">
                <thead class="bg-gray-50/50 text-[11px] font-black text-brand-dark uppercase tracking-widest border-b border-gray-100">
                    <tr>
                        <th class="px-6 py-5">No.</th>
                        <th class="px-4 py-5">No. Pendaftaran</th>
                        <th class="px-4 py-5">Nama Lengkap</th>
                        <th class="px-4 py-5">Pilihan Prodi</th>
                        <th class="px-4 py-5">Jalur</th>
                        <th class="px-4 py-5 text-center">Status</th>
                        <th class="px-6 py-5 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50 text-[13px]">
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
                            'menunggu verifikasi' => 'bg-amber-50 text-amber-600',
                            'Revisi'              => 'bg-red-50 text-red-600',
                            'Selesai'             => 'bg-green-50 text-green-600',
                            default               => 'bg-gray-100 text-gray-500',
                        };
                        $statusLabel = match($item->status_pendaftaran) {
                            'menunggu verifikasi' => 'Menunggu Verifikasi',
                            default               => $item->status_pendaftaran,
                        };

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

                    <tr class="hover:bg-gray-50/50 transition-colors">
                        <td class="px-6 py-4 text-gray-400 font-bold">{{ $index + 1 }}</td>
                        <td class="px-4 py-4 text-gray-400 text-[11px] font-extrabold tracking-wider">{{ $item->no_pendaftaran }}</td>
                        <td class="px-4 py-4">
                            <span class="font-bold text-brand-dark text-[14px]">{{ $item->nama_lengkap }}</span>
                        </td>
                        <td class="px-4 py-4">
                            <div class="flex flex-col gap-1">
                                <span class="text-[11px] font-bold text-gray-700"><span class="text-gray-400">1.</span> {{ $item->pilihan_jurusan_1 ?? '-' }}</span>
                                @if($item->pilihan_jurusan_2)
                                <span class="text-[11px] font-bold text-gray-700"><span class="text-gray-400">2.</span> {{ $item->pilihan_jurusan_2 }}</span>
                                @endif
                            </div>
                        </td>
                        <td class="px-4 py-4 font-bold text-gray-700 text-[13px]">{{ $item->jalur_pendaftaran ?? '-' }}</td>
                        <td class="px-4 py-4 text-center">
                            <span class="px-3 py-1 {{ $statusColor }} rounded-full text-[10px] font-black uppercase">{{ $statusLabel }}</span>
                        </td>
                        <td class="px-6 py-4 text-center">
                            <button
                                type="button"
                                x-data
                                @click="$dispatch('open-formulir-modal', {{ $alpineData }})"
                                class="px-5 py-2 bg-brand-dark text-white rounded-lg font-bold text-[11px] hover:bg-brand-blue transition-colors shadow-sm">
                                Tinjau
                            </button>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="px-6 py-10 text-center text-gray-500 font-bold">Tidak ada formulir yang perlu ditinjau.</td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>

{{-- MODAL DETAIL & VALIDASI FORMULIR (Alpine.js) --}}
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
                setTimeout(() => { if(window.feather) feather.replace(); }, 50);
            });
        },
        getStatusClass(status) {
            const map = {
                'menunggu verifikasi': 'bg-amber-50 text-amber-600',
                'Revisi':              'bg-red-50 text-red-600',
                'Selesai':             'bg-green-50 text-green-600',
            };
            return map[status] || 'bg-gray-100 text-gray-500';
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
        x-show="open" x-transition.opacity
        class="fixed inset-0 bg-brand-dark/60 backdrop-blur-sm cursor-pointer"
        @click="open = false"
    ></div>

    {{-- Panel Modal --}}
    <div
        x-show="open"
        x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0 scale-95 translate-y-4"
        x-transition:enter-end="opacity-100 scale-100 translate-y-0"
        class="bg-white w-full max-w-3xl rounded-[2rem] shadow-2xl relative z-10 overflow-hidden flex flex-col"
        @click.stop
    >
        {{-- Modal Header --}}
        <div class="p-6 border-b border-gray-100 flex justify-between items-center bg-gray-50/50">
            <div>
                <h2 class="text-xl font-extrabold text-brand-dark tracking-tight" x-text="pendaftar.nama_lengkap"></h2>
                <div class="flex items-center gap-2 mt-1">
                    <span class="text-gray-400 text-[11px] font-extrabold tracking-wider" x-text="pendaftar.no_pendaftaran"></span>
                    <span
                        class="px-3 py-1 rounded-full text-[10px] font-black uppercase"
                        :class="getStatusClass(pendaftar.status_pendaftaran)"
                        x-text="getStatusLabel(pendaftar.status_pendaftaran)"
                    ></span>
                </div>
            </div>
            <button @click="open = false" class="p-2 bg-white border border-gray-200 hover:bg-gray-100 rounded-full transition-colors">
                <i data-feather="x" class="w-4 h-4 text-gray-500"></i>
            </button>
        </div>

        {{-- Tab Nav --}}
        <div class="flex gap-1 px-6 pt-4 border-b border-gray-100">
            <button
                @click="activeTab = 'biodata'"
                :class="activeTab === 'biodata' ? 'border-b-2 border-brand-blue text-brand-blue font-extrabold' : 'text-gray-500 hover:text-brand-dark font-bold'"
                class="px-4 py-2 text-[13px] transition-colors -mb-px">
                Biodata
            </button>
            <button
                @click="activeTab = 'berkas'"
                :class="activeTab === 'berkas' ? 'border-b-2 border-brand-blue text-brand-blue font-extrabold' : 'text-gray-500 hover:text-brand-dark font-bold'"
                class="px-4 py-2 text-[13px] transition-colors -mb-px">
                Berkas Dokumen
            </button>
            <button
                @click="activeTab = 'aksi'"
                :class="activeTab === 'aksi' ? 'border-b-2 border-brand-blue text-brand-blue font-extrabold' : 'text-gray-500 hover:text-brand-dark font-bold'"
                class="px-4 py-2 text-[13px] transition-colors -mb-px">
                Keputusan
            </button>
        </div>

        {{-- Modal Body --}}
        <div class="px-6 py-5 max-h-[60vh] overflow-y-auto">

            {{-- ===== TAB: BIODATA ===== --}}
            <div x-show="activeTab === 'biodata'" x-cloak>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">

                    <div class="sm:col-span-2">
                        <p class="text-[11px] font-extrabold text-gray-400 uppercase tracking-widest mb-3">Informasi Pribadi</p>
                    </div>

                    <div>
                        <p class="text-[10px] font-extrabold text-gray-400 uppercase tracking-widest mb-1">NIK</p>
                        <p class="text-[14px] font-bold text-brand-dark" x-text="pendaftar.nik"></p>
                    </div>
                    <div>
                        <p class="text-[10px] font-extrabold text-gray-400 uppercase tracking-widest mb-1">Jenis Kelamin</p>
                        <p class="text-[14px] font-bold text-brand-dark" x-text="pendaftar.gender"></p>
                    </div>
                    <div>
                        <p class="text-[10px] font-extrabold text-gray-400 uppercase tracking-widest mb-1">Tempat Lahir</p>
                        <p class="text-[14px] font-bold text-brand-dark" x-text="pendaftar.tempat_lahir"></p>
                    </div>
                    <div>
                        <p class="text-[10px] font-extrabold text-gray-400 uppercase tracking-widest mb-1">Tanggal Lahir</p>
                        <p class="text-[14px] font-bold text-brand-dark" x-text="pendaftar.tanggal_lahir"></p>
                    </div>
                    <div>
                        <p class="text-[10px] font-extrabold text-gray-400 uppercase tracking-widest mb-1">Agama</p>
                        <p class="text-[14px] font-bold text-brand-dark" x-text="pendaftar.agama"></p>
                    </div>
                    <div>
                        <p class="text-[10px] font-extrabold text-gray-400 uppercase tracking-widest mb-1">No. WhatsApp</p>
                        <p class="text-[14px] font-bold text-brand-dark" x-text="pendaftar.no_whatsapp"></p>
                    </div>
                    <div class="sm:col-span-2">
                        <p class="text-[10px] font-extrabold text-gray-400 uppercase tracking-widest mb-1">Email</p>
                        <p class="text-[14px] font-bold text-brand-dark" x-text="pendaftar.email"></p>
                    </div>
                    <div class="sm:col-span-2">
                        <p class="text-[10px] font-extrabold text-gray-400 uppercase tracking-widest mb-1">Alamat Lengkap</p>
                        <p class="text-[14px] font-bold text-brand-dark" x-text="pendaftar.alamat"></p>
                    </div>

                    {{-- Divider --}}
                    <div class="sm:col-span-2 border-t border-gray-100 pt-4">
                        <p class="text-[11px] font-extrabold text-gray-400 uppercase tracking-widest mb-3">Asal Sekolah</p>
                    </div>

                    <div class="sm:col-span-2">
                        <p class="text-[10px] font-extrabold text-gray-400 uppercase tracking-widest mb-1">Nama Sekolah</p>
                        <p class="text-[14px] font-bold text-brand-dark" x-text="pendaftar.asal_sekolah"></p>
                    </div>
                    <div>
                        <p class="text-[10px] font-extrabold text-gray-400 uppercase tracking-widest mb-1">Jurusan / Program</p>
                        <p class="text-[14px] font-bold text-brand-dark" x-text="pendaftar.jurusan_sekolah"></p>
                    </div>
                    <div>
                        <p class="text-[10px] font-extrabold text-gray-400 uppercase tracking-widest mb-1">Tahun Lulus</p>
                        <p class="text-[14px] font-bold text-brand-dark" x-text="pendaftar.tahun_lulus"></p>
                    </div>

                    {{-- Divider --}}
                    <div class="sm:col-span-2 border-t border-gray-100 pt-4">
                        <p class="text-[11px] font-extrabold text-gray-400 uppercase tracking-widest mb-3">Pilihan Program Studi</p>
                    </div>

                    <div>
                        <p class="text-[10px] font-extrabold text-gray-400 uppercase tracking-widest mb-1">Pilihan 1</p>
                        <p class="text-[14px] font-bold text-brand-blue" x-text="pendaftar.pilihan_jurusan_1"></p>
                    </div>
                    <div>
                        <p class="text-[10px] font-extrabold text-gray-400 uppercase tracking-widest mb-1">Pilihan 2</p>
                        <p class="text-[14px] font-bold text-brand-dark" x-text="pendaftar.pilihan_jurusan_2 || '-'"></p>
                    </div>
                    <div>
                        <p class="text-[10px] font-extrabold text-gray-400 uppercase tracking-widest mb-1">Jalur Pendaftaran</p>
                        <p class="text-[14px] font-bold text-brand-dark" x-text="pendaftar.jalur_pendaftaran"></p>
                    </div>
                </div>
            </div>

            {{-- ===== TAB: BERKAS DOKUMEN ===== --}}
            <div x-show="activeTab === 'berkas'" x-cloak>
                <template x-if="berkasEntries().length === 0">
                    <div class="flex flex-col items-center justify-center py-12 text-gray-400">
                        <i data-feather="file-text" class="w-10 h-10 mb-2"></i>
                        <p class="text-[13px] font-bold">Belum ada berkas diunggah</p>
                        <p class="text-[11px] mt-1">Pendaftar belum mengunggah dokumen apapun</p>
                    </div>
                </template>
                <template x-if="berkasEntries().length > 0">
                    <div class="space-y-2">
                        <p class="text-[11px] text-gray-400 font-bold mb-3">
                            <span x-text="berkasEntries().length"></span> berkas ditemukan
                        </p>
                        <template x-for="([key, path], idx) in berkasEntries()" :key="idx">
                            <div class="flex items-center justify-between px-4 py-3 bg-gray-50/50 border border-gray-100 rounded-xl">
                                <div class="flex items-center gap-3">
                                    <div class="w-8 h-8 rounded-lg bg-brand-blue-light flex items-center justify-center shrink-0">
                                        <i data-feather="file-text" class="w-4 h-4 text-brand-blue"></i>
                                    </div>
                                    <div>
                                        <p class="text-[13px] font-bold text-brand-dark" x-text="getBerkasLabel(key)"></p>
                                        <p class="text-[11px] text-gray-400 font-bold" x-text="path.split('/').pop()"></p>
                                    </div>
                                </div>
                                <a
                                    :href="getBerkasUrl(path)"
                                    target="_blank"
                                    rel="noopener noreferrer"
                                    class="px-3 py-1.5 text-[11px] font-bold bg-white border border-gray-200 text-gray-600 rounded-lg hover:bg-gray-100 transition-colors shrink-0 flex items-center gap-1.5">
                                    <i data-feather="external-link" class="w-3.5 h-3.5"></i> Buka File
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
                    <div class="mb-4 p-4 bg-amber-50 border-l-4 border-amber-500 rounded-r-xl">
                        <p class="text-[12px] font-extrabold text-amber-700 mb-1 uppercase tracking-widest">Catatan Revisi Sebelumnya</p>
                        <p class="text-[13px] font-bold text-amber-800" x-text="pendaftar.pesan_revisi"></p>
                    </div>
                </template>

                {{-- Sudah Selesai --}}
                <template x-if="pendaftar.status_pendaftaran === 'Selesai'">
                    <div class="flex flex-col items-center justify-center py-10 text-center">
                        <div class="w-14 h-14 rounded-full bg-green-50 flex items-center justify-center mb-3">
                            <i data-feather="check-circle" class="w-7 h-7 text-green-600"></i>
                        </div>
                        <p class="font-extrabold text-brand-dark">Formulir Telah Diverifikasi</p>
                        <p class="text-[13px] font-medium text-gray-500 mt-1">Tidak ada tindakan lebih lanjut yang diperlukan.</p>
                    </div>
                </template>

                {{-- Form Aksi untuk status selain Selesai --}}
                <template x-if="pendaftar.status_pendaftaran !== 'Selesai'">
                    <div class="space-y-4">

                        {{-- Setujui Formulir --}}
                        <div x-show="!showRevisiForm">
                            <div class="p-4 bg-green-50 border-l-4 border-green-500 rounded-r-xl mb-4">
                                <p class="text-[13px] font-extrabold text-green-800 mb-1">Setujui Formulir</p>
                                <p class="text-[12px] font-bold text-green-700">Formulir pendaftar dinyatakan lengkap dan benar. Status akan berubah menjadi <strong>Selesai</strong> dan notifikasi WhatsApp akan dikirim.</p>
                            </div>
                            <form :action="'/admin/setujui-formulir/' + pendaftar.id" method="POST">
                                @csrf
                                <button type="submit"
                                    onclick="return confirm('Setujui formulir pendaftar ini?')"
                                    class="w-full py-3.5 bg-brand-dark text-white rounded-xl font-bold text-[13px] hover:bg-brand-blue transition-colors shadow-lg">
                                    Setujui Formulir Ini
                                </button>
                            </form>
                        </div>

                        {{-- Divider --}}
                        <div x-show="!showRevisiForm" class="flex items-center gap-3">
                            <div class="flex-1 h-px bg-gray-100"></div>
                            <span class="text-[11px] text-gray-400 font-bold">atau</span>
                            <div class="flex-1 h-px bg-gray-100"></div>
                        </div>

                        {{-- Tombol Minta Revisi --}}
                        <div x-show="!showRevisiForm">
                            <button
                                @click="showRevisiForm = true"
                                class="w-full py-3.5 bg-white border border-red-200 text-red-600 rounded-xl font-bold text-[13px] hover:bg-red-50 transition-colors">
                                Minta Revisi Formulir
                            </button>
                        </div>

                        {{-- Form Revisi --}}
                        <div x-show="showRevisiForm"
                            x-transition:enter="transition ease-out duration-200"
                            x-transition:enter-start="opacity-0 translate-y-2"
                            x-transition:enter-end="opacity-100 translate-y-0">
                            <div class="p-4 bg-red-50 border-l-4 border-red-500 rounded-r-xl mb-4">
                                <p class="text-[13px] font-extrabold text-red-800 mb-1">Minta Revisi</p>
                                <p class="text-[12px] font-bold text-red-700">Jelaskan secara spesifik dokumen atau data apa yang perlu diperbaiki pendaftar.</p>
                            </div>
                            <form :action="'/admin/revisi-formulir/' + pendaftar.id" method="POST" class="space-y-3">
                                @csrf
                                <div>
                                    <label class="block text-[11px] font-extrabold text-gray-500 uppercase tracking-widest mb-2">Pesan Revisi untuk Pendaftar <span class="text-red-500">*</span></label>
                                    <textarea
                                        name="pesan_revisi"
                                        x-model="pesanRevisi"
                                        rows="4"
                                        placeholder="Contoh: Foto KTP yang diunggah buram, mohon unggah ulang dengan foto yang jelas..."
                                        required
                                        class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-[13px] font-bold focus:border-brand-blue focus:bg-white outline-none transition-all resize-none"
                                    ></textarea>
                                </div>
                                <div class="flex gap-3">
                                    <button type="button" @click="showRevisiForm = false"
                                        class="flex-1 py-3 border border-gray-200 text-gray-600 bg-white hover:bg-gray-50 rounded-xl font-bold text-[13px] transition-colors">
                                        Batal
                                    </button>
                                    <button type="submit"
                                        :disabled="!pesanRevisi.trim()"
                                        :class="pesanRevisi.trim() ? 'bg-red-600 hover:bg-red-700 text-white' : 'bg-gray-200 text-gray-400 cursor-not-allowed'"
                                        class="flex-1 py-3 rounded-xl font-bold text-[13px] transition-colors">
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
        <div class="p-6 border-t border-gray-100 flex justify-end bg-gray-50/50">
            <button @click="open = false"
                class="px-6 py-3 border border-gray-200 text-gray-600 bg-white hover:bg-gray-50 rounded-xl font-bold text-[13px] transition-colors">
                Tutup
            </button>
        </div>
    </div>
</div>
@endsection