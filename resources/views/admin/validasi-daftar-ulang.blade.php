@extends('layouts.admin')

@section('title', 'Validasi Daftar Ulang')

@section('admin-content')
<div>

    {{-- =============================================
         HEADER
    ============================================== --}}
    <div class="mb-8">
        <h1 class="text-3xl font-extrabold text-[#0B1C39] tracking-tight mb-2">Validasi Pembayaran Daftar Ulang</h1>
        <p class="text-brand-gray text-[14px] font-medium">Verifikasi bukti transfer biaya daftar ulang (SPP, Sarpras, Orientasi) calon mahasiswa yang telah dinyatakan lulus seleksi.</p>
    </div>

    {{-- =============================================
         FLASH MESSAGE
    ============================================== --}}
    @if(session('success'))
        <div class="mb-6 p-4 bg-green-50 border-l-4 border-green-500 rounded-r-xl flex items-start gap-3">
            <i data-feather="check-circle" class="w-5 h-5 text-green-600 shrink-0"></i>
            <p class="text-[13px] font-bold text-green-800">{{ session('success') }}</p>
        </div>
    @endif
    @if(session('error'))
        <div class="mb-6 p-4 bg-red-50 border-l-4 border-red-500 rounded-r-xl flex items-start gap-3">
            <i data-feather="alert-circle" class="w-5 h-5 text-red-600 shrink-0"></i>
            <p class="text-[13px] font-bold text-red-800">{{ session('error') }}</p>
        </div>
    @endif

    {{-- =============================================
         TABEL DATA
    ============================================== --}}
    <div class="bg-white rounded-[2rem] shadow-sm border border-gray-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left whitespace-nowrap">
                <thead class="bg-gray-50/50 text-[11px] font-black text-brand-dark uppercase tracking-widest border-b border-gray-100">
                    <tr>
                        <th class="px-6 py-5">No.</th>
                        <th class="px-4 py-5">No. Pendaftaran</th>
                        <th class="px-4 py-5">Nama Lengkap</th>
                        <th class="px-4 py-5">Program Studi</th>
                        <th class="px-4 py-5">Total Tagihan</th>
                        <th class="px-4 py-5 text-center">Status</th>
                        <th class="px-6 py-5 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50 text-[13px]">
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

                        // Badge status mengikuti palet warna konsisten (bg-X-50 / text-X-600)
                        $statusBadge = match($item->status_daftar_ulang) {
                            'Menunggu Validasi' => 'bg-amber-50 text-amber-600',
                            'Revisi'            => 'bg-red-50 text-red-600',
                            'Selesai'           => 'bg-green-50 text-green-600',
                            default             => 'bg-gray-100 text-gray-500',
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

                    <tr class="hover:bg-gray-50/50 transition-colors">
                        <td class="px-6 py-4 text-gray-400 font-bold text-[12px]">{{ $index + 1 }}</td>
                        <td class="px-4 py-4">
                            <span class="text-gray-400 text-[11px] font-extrabold tracking-wider">{{ $item->no_pendaftaran }}</span>
                        </td>
                        <td class="px-4 py-4">
                            <div class="flex flex-col">
                                <span class="font-bold text-brand-dark text-[14px]">{{ $item->nama_lengkap }}</span>
                                <span class="text-gray-400 text-[11px] font-medium">{{ $item->no_whatsapp ?? '-' }}</span>
                            </div>
                        </td>
                        <td class="px-4 py-4">
                            <span class="text-[11px] font-bold text-gray-700">{{ $item->pilihan_jurusan_1 ?? '-' }}</span>
                        </td>
                        <td class="px-4 py-4">
                            @if($totalTagihan > 0)
                                <span class="font-black text-brand-dark text-[13px]">Rp {{ number_format($totalTagihan, 0, ',', '.') }}</span>
                                <div class="text-gray-400 text-[11px] font-medium">{{ $biaya ? '3 komponen' : 'Tarif belum diatur' }}</div>
                            @else
                                <span class="text-gray-400 text-[11px] font-medium italic">Tarif belum diatur</span>
                            @endif
                        </td>
                        <td class="px-4 py-4 text-center">
                            <span class="px-3 py-1 {{ $statusBadge }} rounded-full text-[10px] font-black uppercase">
                                {{ $item->status_daftar_ulang }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-center">
                            <button
                                type="button"
                                x-data
                                @click="$dispatch('open-daftar-ulang-modal', {{ $alpineData }})"
                                class="px-5 py-2 bg-brand-dark text-white rounded-lg font-bold text-[11px] hover:bg-brand-blue transition-colors shadow-sm">
                                Verifikasi
                            </button>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="px-6 py-10 text-center text-gray-500 font-bold">Belum ada pembayaran daftar ulang masuk.</td>
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
                setTimeout(() => { if(window.feather) feather.replace(); }, 50);
            });
        },
        formatRupiah(n) {
            if (!n || n === 0) return 'Rp 0';
            return 'Rp ' + Number(n).toLocaleString('id-ID');
        },
        getStatusClass(s) {
            const m = {
                'Menunggu Validasi': 'bg-amber-50 text-amber-600',
                'Revisi':            'bg-red-50 text-red-600',
                'Selesai':           'bg-green-50 text-green-600',
            };
            return m[s] || 'bg-gray-100 text-gray-500';
        }
    }"
    x-show="open"
    x-cloak
    class="fixed inset-0 z-50 flex items-start justify-center pt-6 pb-6 px-4 overflow-y-auto"
    style="display: none;"
>
    {{-- Backdrop --}}
    <div
        class="fixed inset-0 bg-brand-dark/60 backdrop-blur-sm"
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
        class="relative w-full max-w-2xl bg-white rounded-[2rem] shadow-2xl z-10 overflow-hidden"
        x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="opacity-0 scale-95"
        x-transition:enter-end="opacity-100 scale-100"
        x-transition:leave="transition ease-in duration-150"
        x-transition:leave-start="opacity-100 scale-100"
        x-transition:leave-end="opacity-0 scale-95"
        @click.stop
    >
        {{-- Modal Header --}}
        <div class="p-6 border-b border-gray-100 flex justify-between items-center bg-gray-50/50">
            <div>
                <h2 class="text-xl font-extrabold text-brand-dark tracking-tight" x-text="d.nama_lengkap"></h2>
                <div class="flex items-center gap-2 mt-1">
                    <span class="text-gray-400 text-[11px] font-extrabold tracking-wider" x-text="d.no_pendaftaran"></span>
                    <span
                        class="px-3 py-1 rounded-full text-[10px] font-black uppercase"
                        :class="getStatusClass(d.status_daftar_ulang)"
                        x-text="d.status_daftar_ulang"
                    ></span>
                </div>
            </div>
            <button @click="open = false" class="p-2 bg-white border border-gray-200 hover:bg-gray-100 rounded-full transition-colors">
                <i data-feather="x" class="w-4 h-4 text-gray-500"></i>
            </button>
        </div>

        {{-- Modal Body --}}
        <div class="p-8 space-y-5 max-h-[65vh] overflow-y-auto">

            {{-- ===== INFO MAHASISWA ===== --}}
            <div class="grid grid-cols-2 gap-4 p-4 bg-gray-50 rounded-xl border border-gray-100">
                <div>
                    <p class="text-[10px] font-extrabold text-gray-400 uppercase tracking-widest mb-1">Program Studi (Lulus)</p>
                    <p class="text-[13px] font-bold text-brand-blue" x-text="d.pilihan_jurusan_1"></p>
                </div>
                <div>
                    <p class="text-[10px] font-extrabold text-gray-400 uppercase tracking-widest mb-1">Jalur Pendaftaran</p>
                    <p class="text-[13px] font-bold text-brand-dark" x-text="d.jalur_pendaftaran"></p>
                </div>
                <div>
                    <p class="text-[10px] font-extrabold text-gray-400 uppercase tracking-widest mb-1">Status Kelulusan</p>
                    <p class="text-[13px] font-bold text-brand-dark" x-text="d.status_kelulusan"></p>
                </div>
                <div>
                    <p class="text-[10px] font-extrabold text-gray-400 uppercase tracking-widest mb-1">No. WhatsApp</p>
                    <p class="text-[13px] font-bold text-brand-dark" x-text="d.no_whatsapp"></p>
                </div>
            </div>

            {{-- ===== RINCIAN TAGIHAN 3 KOMPONEN ===== --}}
            <div>
                <p class="text-[10px] font-extrabold text-gray-400 uppercase tracking-widest mb-3">Rincian Biaya Daftar Ulang</p>

                <template x-if="d.total_tagihan > 0">
                    <div class="border border-gray-100 rounded-xl overflow-hidden">
                        {{-- SPP Semester 1 --}}
                        <div class="flex items-center justify-between px-4 py-3 border-b border-gray-50">
                            <div class="flex items-center gap-2.5">
                                <div class="w-2 h-2 rounded-full bg-brand-blue"></div>
                                <span class="text-[13px] font-bold text-gray-700">SPP Semester 1</span>
                            </div>
                            <span class="text-[13px] font-bold text-brand-dark" x-text="formatRupiah(d.biaya_spp)"></span>
                        </div>
                        {{-- Sarpras --}}
                        <div class="flex items-center justify-between px-4 py-3 border-b border-gray-50">
                            <div class="flex items-center gap-2.5">
                                <div class="w-2 h-2 rounded-full bg-purple-500"></div>
                                <span class="text-[13px] font-bold text-gray-700">Sarana & Prasarana</span>
                            </div>
                            <span class="text-[13px] font-bold text-brand-dark" x-text="formatRupiah(d.biaya_sarpras)"></span>
                        </div>
                        {{-- Seragam & Orientasi (digabung sesuai kolom DB) --}}
                        <div class="flex items-center justify-between px-4 py-3">
                            <div class="flex items-center gap-2.5">
                                <div class="w-2 h-2 rounded-full bg-teal-500"></div>
                                <span class="text-[13px] font-bold text-gray-700">Seragam & Orientasi</span>
                            </div>
                            <span class="text-[13px] font-bold text-brand-dark" x-text="formatRupiah(d.biaya_seragam_orientasi)"></span>
                        </div>
                        {{-- Total --}}
                        <div class="flex items-center justify-between px-4 py-3.5 bg-gray-50 border-t border-gray-100">
                            <span class="text-[13px] font-black text-brand-dark">Total yang Harus Dibayar</span>
                            <span class="text-[15px] font-black text-brand-blue" x-text="formatRupiah(d.total_tagihan)"></span>
                        </div>
                    </div>
                </template>

                <template x-if="!d.total_tagihan || d.total_tagihan === 0">
                    <div class="p-4 bg-gray-50 border border-dashed border-gray-300 rounded-xl text-center">
                        <p class="text-[13px] font-bold text-gray-500">Tarif untuk program studi ini belum diatur di sistem.</p>
                        <p class="text-[11px] text-gray-400 font-medium mt-1">Hubungi administrator untuk menginput data <code class="bg-gray-200 px-1 rounded">biaya_daftar_ulangs</code>.</p>
                    </div>
                </template>
            </div>

            {{-- ===== BUKTI TRANSFER ===== --}}
            <div>
                <p class="text-[10px] font-extrabold text-gray-400 uppercase tracking-widest mb-3">Bukti Transfer yang Diunggah</p>

                <template x-if="d.bukti_url">
                    <div class="rounded-xl border border-gray-100 overflow-hidden">
                        {{-- Preview Gambar --}}
                        <div class="bg-gray-100 flex items-center justify-center p-2 min-h-[200px] max-h-[380px]">
                            <img
                                :src="d.bukti_url"
                                alt="Bukti Transfer"
                                class="max-h-[360px] max-w-full object-contain rounded-lg shadow-sm"
                                onerror="this.parentElement.innerHTML = '<div class=\'p-8 text-center text-gray-400\'><p class=\'text-[13px] font-bold\'>Gambar tidak dapat dimuat</p></div>'"
                            />
                        </div>
                        {{-- Tombol Buka Full --}}
                        <div class="px-4 py-2.5 bg-white border-t border-gray-100 flex justify-end">
                            <a
                                :href="d.bukti_url"
                                target="_blank"
                                rel="noopener noreferrer"
                                class="inline-flex items-center gap-1.5 text-[11px] font-bold text-brand-blue hover:text-brand-dark transition-colors">
                                <i data-feather="external-link" class="w-3.5 h-3.5"></i>
                                Buka di Tab Baru
                            </a>
                        </div>
                    </div>
                </template>

                <template x-if="!d.bukti_url">
                    <div class="p-8 bg-gray-50 border border-dashed border-gray-300 rounded-xl text-center text-gray-400">
                        <i data-feather="image" class="w-8 h-8 mx-auto mb-2"></i>
                        <p class="text-[13px] font-bold">Bukti transfer belum diunggah</p>
                    </div>
                </template>
            </div>

            {{-- Pesan Revisi Sebelumnya --}}
            <template x-if="d.pesan_revisi">
                <div class="p-4 bg-amber-50 border-l-4 border-amber-500 rounded-r-xl">
                    <p class="text-[11px] font-extrabold text-amber-700 uppercase tracking-widest mb-1">Catatan Revisi Sebelumnya</p>
                    <p class="text-[13px] font-bold text-amber-800" x-text="d.pesan_revisi"></p>
                </div>
            </template>

            {{-- ===== AKSI ADMIN ===== --}}
            <div>
                <p class="text-[10px] font-extrabold text-gray-400 uppercase tracking-widest mb-3">Keputusan Admin</p>

                {{-- Sudah Selesai --}}
                <template x-if="d.status_daftar_ulang === 'Selesai'">
                    <div class="flex flex-col items-center justify-center py-8 text-center bg-green-50 border border-green-100 rounded-xl">
                        <i data-feather="check-circle" class="w-8 h-8 text-green-600 mb-2"></i>
                        <p class="font-bold text-green-700 text-[14px]">Pembayaran Telah Diverifikasi</p>
                        <p class="text-[11px] text-green-600 font-medium mt-1">Tidak ada tindakan lebih lanjut.</p>
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
                                    class="w-full py-3.5 text-[13px] font-bold bg-green-600 text-white rounded-xl hover:bg-green-700 transition-colors shadow-sm">
                                    Konfirmasi Pembayaran Sah
                                </button>
                            </form>
                        </div>

                        {{-- Divider --}}
                        <div x-show="!showRevisiForm" class="flex items-center gap-3">
                            <div class="flex-1 h-px bg-gray-100"></div>
                            <span class="text-[11px] font-bold text-gray-400">atau</span>
                            <div class="flex-1 h-px bg-gray-100"></div>
                        </div>

                        {{-- Tombol Minta Revisi --}}
                        <div x-show="!showRevisiForm">
                            <button
                                @click="showRevisiForm = true"
                                class="w-full py-3 text-[13px] font-bold bg-white border border-red-200 text-red-600 rounded-xl hover:bg-red-50 transition-colors">
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
                            <div class="p-4 bg-red-50 border-l-4 border-red-500 rounded-r-xl mb-3">
                                <p class="text-[13px] font-bold text-red-800">Alasan Penolakan</p>
                                <p class="text-[11px] text-red-600 font-medium mt-0.5">Mahasiswa akan diminta mengunggah ulang bukti transfer yang valid.</p>
                            </div>
                            <form :action="'/admin/revisi-daftar-ulang/' + d.id" method="POST" class="space-y-3">
                                @csrf
                                <textarea
                                    name="pesan_revisi"
                                    x-model="pesanRevisi"
                                    rows="3"
                                    placeholder="Contoh: Bukti transfer tidak terbaca, nominal tidak sesuai (Rp 5.150.000), atau nama rekening tidak cocok..."
                                    required
                                    class="w-full px-3 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-[13px] outline-none focus:border-brand-blue focus:bg-white transition-all resize-none"
                                ></textarea>
                                <div class="flex gap-2">
                                    <button type="button" @click="showRevisiForm = false"
                                        class="flex-1 py-2.5 text-[13px] font-bold bg-gray-100 text-gray-600 rounded-xl hover:bg-gray-200 transition-colors">
                                        Batal
                                    </button>
                                    <button
                                        type="submit"
                                        :disabled="!pesanRevisi.trim()"
                                        class="flex-1 py-2.5 text-[13px] font-bold bg-red-600 text-white rounded-xl hover:bg-red-700 transition-colors disabled:opacity-50 disabled:cursor-not-allowed">
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
        <div class="p-6 border-t border-gray-100 flex justify-end bg-gray-50/50">
            <button @click="open = false"
                class="px-6 py-2.5 border border-gray-200 text-gray-600 bg-white hover:bg-gray-50 rounded-xl font-bold text-[13px] transition-colors">
                Tutup
            </button>
        </div>
    </div>
</div>
@endsection