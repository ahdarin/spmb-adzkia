@extends('layouts.admin')

@section('title', 'Validasi Daftar Ulang')

@section('admin-content')
<div class="min-h-screen bg-slate-50 p-6">

    {{-- =============================================
         HEADER
    ============================================== --}}
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-slate-800">Validasi Pembayaran Daftar Ulang</h1>
        <p class="text-sm text-slate-500 mt-1">Verifikasi bukti transfer biaya daftar ulang (SPP, Sarpras, Orientasi) calon mahasiswa yang telah dinyatakan lulus seleksi.</p>
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
                        <th class="text-left px-5 py-3.5 font-semibold text-slate-600">Program Studi</th>
                        <th class="text-left px-5 py-3.5 font-semibold text-slate-600">Total Tagihan</th>
                        <th class="text-left px-5 py-3.5 font-semibold text-slate-600">Status</th>
                        <th class="text-center px-5 py-3.5 font-semibold text-slate-600">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                @forelse($pendaftarDaftarUlang as $index => $item)
                    @php
                        // ============================================
                        // QUERY BIAYA DAFTAR ULANG
                        // Tabel: biaya_daftar_ulangs
                        // FK: prodi_id (join Prodi by nama), jalur_id, gelombang_id
                        // Kolom biaya: spp_semester, biaya_sarpras, biaya_seragam_orientasi
                        // total_biaya = generated column (dihitung otomatis DB)
                        // ============================================

                        // 1. Gelombang aktif
                        $gelombangAktif = \App\Models\Gelombang::aktif()->first()
                                       ?? \App\Models\Gelombang::where('is_active', true)->latest()->first();

                        // 2. Prodi record berdasarkan nama pilihan jurusan pendaftar
                        $prodiRecord = \App\Models\Prodi::where('nama', $item->pilihan_jurusan_1)->first();

                        // 3. Jalur record berdasarkan nama jalur pendaftar
                        $jalurRecord = \App\Models\Jalur::where('nama', $item->jalur_pendaftaran)->first();

                        // 4. Query BiayaDaftarUlang dengan kombinasi FK yang lengkap
                        $biaya = null;
                        if ($prodiRecord && $jalurRecord && $gelombangAktif) {
                            $biaya = \App\Models\BiayaDaftarUlang::where('prodi_id', $prodiRecord->id)
                                        ->where('jalur_id', $jalurRecord->id)
                                        ->where('gelombang_id', $gelombangAktif->id)
                                        ->first();
                        }
                        // Fallback 1: prodi + gelombang saja (tanpa filter jalur)
                        if (!$biaya && $prodiRecord && $gelombangAktif) {
                            $biaya = \App\Models\BiayaDaftarUlang::where('prodi_id', $prodiRecord->id)
                                        ->where('gelombang_id', $gelombangAktif->id)
                                        ->first();
                        }
                        // Fallback 2: prodi saja
                        if (!$biaya && $prodiRecord) {
                            $biaya = \App\Models\BiayaDaftarUlang::where('prodi_id', $prodiRecord->id)->first();
                        }

                        // 5. Ambil nilai dari kolom yang benar
                        $biayaSpp              = $biaya->spp_semester            ?? 0;
                        $biayaSarpras          = $biaya->biaya_sarpras           ?? 0;
                        $biayaSeragamOrientasi = $biaya->biaya_seragam_orientasi ?? 0;
                        $totalTagihan          = $biaya->total_biaya             ?? ($biayaSpp + $biayaSarpras + $biayaSeragamOrientasi);

                        $statusColor = match($item->status_daftar_ulang) {
                            'Menunggu Validasi' => 'bg-amber-100 text-amber-700 border-amber-200',
                            'Revisi'            => 'bg-red-100 text-red-700 border-red-200',
                            'Selesai'           => 'bg-emerald-100 text-emerald-700 border-emerald-200',
                            default             => 'bg-slate-100 text-slate-600 border-slate-200',
                        };

                        // Path gambar: kolom hanya berisi nama file
                        $urlBukti = $item->bukti_daftar_ulang
                            ? asset('uploads/bukti_daftar_ulang/' . $item->bukti_daftar_ulang)
                            : null;

                        // Data Alpine.js
                        $alpineData = json_encode([
                            'id'                  => $item->id,
                            'no_pendaftaran'      => $item->no_pendaftaran,
                            'nama_lengkap'        => $item->nama_lengkap,
                            'no_whatsapp'         => $item->no_whatsapp ?? '-',
                            'pilihan_jurusan_1'   => $item->pilihan_jurusan_1 ?? '-',
                            'jalur_pendaftaran'   => $item->jalur_pendaftaran ?? '-',
                            'status_kelulusan'    => $item->status_kelulusan ?? '-',
                            'status_daftar_ulang' => $item->status_daftar_ulang,
                            'pesan_revisi'        => $item->pesan_revisi ?? '',
                            'bukti_url'                => $urlBukti,
                            'biaya_spp'                => $biayaSpp,
                            'biaya_sarpras'            => $biayaSarpras,
                            'biaya_seragam_orientasi'  => $biayaSeragamOrientasi,
                            'total_tagihan'            => $totalTagihan,
                        ], JSON_HEX_APOS | JSON_HEX_QUOT);
                    @endphp

                    <tr class="hover:bg-slate-50 transition-colors">
                        <td class="px-5 py-4 text-slate-400 font-mono text-xs">{{ $index + 1 }}</td>
                        <td class="px-5 py-4 font-mono text-xs font-semibold text-slate-700">{{ $item->no_pendaftaran }}</td>
                        <td class="px-5 py-4">
                            <div class="font-medium text-slate-800">{{ $item->nama_lengkap }}</div>
                            <div class="text-xs text-slate-400">{{ $item->no_whatsapp ?? '-' }}</div>
                        </td>
                        <td class="px-5 py-4 text-slate-600">{{ $item->pilihan_jurusan_1 ?? '-' }}</td>
                        <td class="px-5 py-4">
                            @if($totalTagihan > 0)
                                <span class="font-semibold text-slate-800">Rp {{ number_format($totalTagihan, 0, ',', '.') }}</span>
                                <div class="text-xs text-slate-400">{{ $biaya ? '3 komponen' : 'Tarif belum diatur' }}</div>
                            @else
                                <span class="text-xs text-slate-400 italic">Tarif belum diatur</span>
                            @endif
                        </td>
                        <td class="px-5 py-4">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium border {{ $statusColor }}">
                                {{ $item->status_daftar_ulang }}
                            </span>
                        </td>
                        <td class="px-5 py-4 text-center">
                            <button
                                type="button"
                                x-data
                                @click="$dispatch('open-daftar-ulang-modal', {{ $alpineData }})"
                                class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-medium bg-indigo-50 text-indigo-700 border border-indigo-200 rounded-lg hover:bg-indigo-100 transition-colors">
                                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                Verifikasi
                            </button>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="px-5 py-16 text-center">
                            <div class="flex flex-col items-center gap-2 text-slate-400">
                                <svg class="w-10 h-10" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                                <p class="font-medium">Belum ada pembayaran daftar ulang masuk</p>
                                <p class="text-xs">Mahasiswa yang lulus dan mengunggah bukti transfer akan muncul di sini</p>
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
     MODAL VERIFIKASI DAFTAR ULANG (Alpine.js)
============================================== --}}
<div
    x-data="{
        open: false,
        showRevisiForm: false,
        pesanRevisi: '',
        d: {},
        init() {
            window.addEventListener('open-daftar-ulang-modal', (e) => {
                this.d              = e.detail;
                this.showRevisiForm = false;
                this.pesanRevisi    = '';
                this.open           = true;
            });
        },
        formatRupiah(n) {
            if (!n || n === 0) return 'Rp 0';
            return 'Rp ' + Number(n).toLocaleString('id-ID');
        },
        getStatusClass(s) {
            const m = {
                'Menunggu Validasi': 'bg-amber-100 text-amber-700 border-amber-200',
                'Revisi':            'bg-red-100 text-red-700 border-red-200',
                'Selesai':           'bg-emerald-100 text-emerald-700 border-emerald-200',
            };
            return m[s] || 'bg-slate-100 text-slate-600 border-slate-200';
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
        class="relative w-full max-w-2xl bg-white rounded-2xl shadow-xl z-10"
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
                <h2 class="text-lg font-bold text-slate-800" x-text="d.nama_lengkap"></h2>
                <div class="flex items-center gap-2 mt-1">
                    <span class="font-mono text-xs text-slate-500" x-text="d.no_pendaftaran"></span>
                    <span
                        class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium border"
                        :class="getStatusClass(d.status_daftar_ulang)"
                        x-text="d.status_daftar_ulang"
                    ></span>
                </div>
            </div>
            <button @click="open = false" class="p-2 rounded-lg text-slate-400 hover:bg-slate-100 hover:text-slate-600 transition-colors">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>

        {{-- Modal Body --}}
        <div class="px-6 py-5 space-y-5 max-h-[70vh] overflow-y-auto">

            {{-- ===== INFO MAHASISWA ===== --}}
            <div class="grid grid-cols-2 gap-3 p-4 bg-slate-50 rounded-xl border border-slate-200">
                <div>
                    <p class="text-xs text-slate-400 mb-0.5">Program Studi (Lulus)</p>
                    <p class="text-sm font-semibold text-indigo-700" x-text="d.pilihan_jurusan_1"></p>
                </div>
                <div>
                    <p class="text-xs text-slate-400 mb-0.5">Jalur Pendaftaran</p>
                    <p class="text-sm text-slate-800" x-text="d.jalur_pendaftaran"></p>
                </div>
                <div>
                    <p class="text-xs text-slate-400 mb-0.5">Status Kelulusan</p>
                    <p class="text-sm text-slate-800" x-text="d.status_kelulusan"></p>
                </div>
                <div>
                    <p class="text-xs text-slate-400 mb-0.5">No. WhatsApp</p>
                    <p class="text-sm text-slate-800" x-text="d.no_whatsapp"></p>
                </div>
            </div>

            {{-- ===== RINCIAN TAGIHAN 3 KOMPONEN ===== --}}
            <div>
                <p class="text-xs font-semibold text-slate-500 uppercase tracking-wider mb-3">Rincian Biaya Daftar Ulang</p>

                <template x-if="d.total_tagihan > 0">
                    <div class="border border-slate-200 rounded-xl overflow-hidden">
                        {{-- SPP Semester 1 --}}
                        <div class="flex items-center justify-between px-4 py-3 border-b border-slate-100">
                            <div class="flex items-center gap-2.5">
                                <div class="w-2 h-2 rounded-full bg-blue-500"></div>
                                <span class="text-sm text-slate-700">SPP Semester 1</span>
                            </div>
                            <span class="text-sm font-semibold text-slate-800" x-text="formatRupiah(d.biaya_spp)"></span>
                        </div>
                        {{-- Sarpras --}}
                        <div class="flex items-center justify-between px-4 py-3 border-b border-slate-100">
                            <div class="flex items-center gap-2.5">
                                <div class="w-2 h-2 rounded-full bg-violet-500"></div>
                                <span class="text-sm text-slate-700">Sarana & Prasarana</span>
                            </div>
                            <span class="text-sm font-semibold text-slate-800" x-text="formatRupiah(d.biaya_sarpras)"></span>
                        </div>
                        {{-- Seragam & Orientasi (digabung sesuai kolom DB) --}}
                        <div class="flex items-center justify-between px-4 py-3">
                            <div class="flex items-center gap-2.5">
                                <div class="w-2 h-2 rounded-full bg-teal-500"></div>
                                <span class="text-sm text-slate-700">Seragam & Orientasi</span>
                            </div>
                            <span class="text-sm font-semibold text-slate-800" x-text="formatRupiah(d.biaya_seragam_orientasi)"></span>
                        </div>
                        {{-- Total --}}
                        <div class="flex items-center justify-between px-4 py-3.5 bg-indigo-50 border-t border-indigo-100">
                            <span class="text-sm font-bold text-indigo-900">Total yang Harus Dibayar</span>
                            <span class="text-base font-bold text-indigo-700" x-text="formatRupiah(d.total_tagihan)"></span>
                        </div>
                    </div>
                </template>

                <template x-if="!d.total_tagihan || d.total_tagihan === 0">
                    <div class="p-4 bg-slate-50 border border-dashed border-slate-300 rounded-xl text-center">
                        <p class="text-sm text-slate-500">Tarif untuk program studi ini belum diatur di sistem.</p>
                        <p class="text-xs text-slate-400 mt-1">Hubungi administrator untuk menginput data <code class="bg-slate-200 px-1 rounded">biaya_daftar_ulangs</code>.</p>
                    </div>
                </template>
            </div>

            {{-- ===== BUKTI TRANSFER ===== --}}
            <div>
                <p class="text-xs font-semibold text-slate-500 uppercase tracking-wider mb-3">Bukti Transfer yang Diunggah</p>

                <template x-if="d.bukti_url">
                    <div class="rounded-xl border border-slate-200 overflow-hidden">
                        {{-- Preview Gambar --}}
                        <div class="bg-slate-100 flex items-center justify-center p-2 min-h-[200px] max-h-[380px]">
                            <img
                                :src="d.bukti_url"
                                alt="Bukti Transfer"
                                class="max-h-[360px] max-w-full object-contain rounded-lg shadow-sm"
                                onerror="this.parentElement.innerHTML = '<div class=\'p-8 text-center text-slate-400\'><svg class=\'w-10 h-10 mx-auto mb-2\' fill=\'none\' viewBox=\'0 0 24 24\' stroke=\'currentColor\'><path stroke-linecap=\'round\' stroke-linejoin=\'round\' stroke-width=\'1.5\' d=\'M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z\'/></svg><p class=\'text-sm\'>Gambar tidak dapat dimuat</p><p class=\'text-xs mt-1\'>Path: ' + this.src + '</p></div>'"
                            />
                        </div>
                        {{-- Tombol Buka Full --}}
                        <div class="px-4 py-2.5 bg-white border-t border-slate-100 flex justify-end">
                            <a
                                :href="d.bukti_url"
                                target="_blank"
                                rel="noopener noreferrer"
                                class="inline-flex items-center gap-1.5 text-xs font-medium text-indigo-600 hover:text-indigo-800 transition-colors">
                                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                                Buka di Tab Baru
                            </a>
                        </div>
                    </div>
                </template>

                <template x-if="!d.bukti_url">
                    <div class="p-8 bg-slate-50 border border-dashed border-slate-300 rounded-xl text-center text-slate-400">
                        <svg class="w-10 h-10 mx-auto mb-2" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                        <p class="text-sm">Bukti transfer belum diunggah</p>
                    </div>
                </template>
            </div>

            {{-- Pesan Revisi Sebelumnya --}}
            <template x-if="d.pesan_revisi">
                <div class="p-4 bg-amber-50 border border-amber-200 rounded-xl">
                    <p class="text-xs font-semibold text-amber-700 mb-1">Catatan Revisi Sebelumnya</p>
                    <p class="text-sm text-amber-800" x-text="d.pesan_revisi"></p>
                </div>
            </template>

            {{-- ===== AKSI ADMIN ===== --}}
            <div>
                <p class="text-xs font-semibold text-slate-500 uppercase tracking-wider mb-3">Keputusan Admin</p>

                {{-- Sudah Selesai --}}
                <template x-if="d.status_daftar_ulang === 'Selesai'">
                    <div class="flex flex-col items-center justify-center py-8 text-center bg-emerald-50 border border-emerald-200 rounded-xl">
                        <div class="w-12 h-12 rounded-full bg-emerald-100 flex items-center justify-center mb-2">
                            <svg class="w-6 h-6 text-emerald-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                        </div>
                        <p class="font-semibold text-emerald-800">Pembayaran Telah Diverifikasi</p>
                        <p class="text-xs text-emerald-600 mt-1">Tidak ada tindakan lebih lanjut.</p>
                    </div>
                </template>

                {{-- Form Aksi untuk status selain Selesai --}}
                <template x-if="d.status_daftar_ulang !== 'Selesai'">
                    <div class="space-y-3">

                        {{-- Tombol Setujui --}}
                        <div x-show="!showRevisiForm">
                            <form :action="'/admin/setujui-daftar-ulang/' + d.id" method="POST">
                                @csrf
                                <button
                                    type="submit"
                                    onclick="return confirm('Konfirmasi: Setujui pembayaran daftar ulang ini?')"
                                    class="w-full py-3 text-sm font-bold bg-emerald-600 text-white rounded-xl hover:bg-emerald-700 transition-colors shadow-sm">
                                    Konfirmasi Pembayaran Sah
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
                                Bukti Tidak Valid — Minta Upload Ulang
                            </button>
                        </div>

                        {{-- Form Revisi --}}
                        <div
                            x-show="showRevisiForm"
                            x-transition:enter="transition ease-out duration-200"
                            x-transition:enter-start="opacity-0 translate-y-2"
                            x-transition:enter-end="opacity-100 translate-y-0"
                        >
                            <div class="p-4 bg-red-50 border border-red-200 rounded-xl mb-3">
                                <p class="text-sm font-semibold text-red-800">Alasan Penolakan</p>
                                <p class="text-xs text-red-600 mt-0.5">Mahasiswa akan diminta mengunggah ulang bukti transfer yang valid.</p>
                            </div>
                            <form :action="'/admin/revisi-daftar-ulang/' + d.id" method="POST" class="space-y-3">
                                @csrf
                                <textarea
                                    name="pesan_revisi"
                                    x-model="pesanRevisi"
                                    rows="3"
                                    placeholder="Contoh: Bukti transfer tidak terbaca, nominal tidak sesuai (Rp 5.150.000), atau nama rekening tidak cocok..."
                                    required
                                    class="w-full px-3 py-2.5 text-sm border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-transparent resize-none"
                                ></textarea>
                                <div class="flex gap-2">
                                    <button type="button" @click="showRevisiForm = false"
                                        class="flex-1 py-2.5 text-sm font-medium bg-slate-100 text-slate-600 rounded-xl hover:bg-slate-200 transition-colors">
                                        Batal
                                    </button>
                                    <button
                                        type="submit"
                                        :disabled="!pesanRevisi.trim()"
                                        class="flex-1 py-2.5 text-sm font-bold bg-red-600 text-white rounded-xl hover:bg-red-700 transition-colors disabled:opacity-50 disabled:cursor-not-allowed">
                                        Kirim & Tolak
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