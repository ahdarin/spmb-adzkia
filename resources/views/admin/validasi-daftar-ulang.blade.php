@extends('layouts.admin')

@section('admin-content')

<div x-data="validasiBerkas()" x-cloak>

    <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-8 gap-4">
        <div>
            <h1 class="text-3xl font-extrabold text-[#0B1C39] tracking-tight mb-1">Validasi Berkas</h1>
            <p class="text-brand-gray text-[14px] font-medium">Verifikasi berkas daftar ulang dan terbitkan NIM bagi mahasiswa yang dinyatakan lulus.</p>
        </div>
        <div class="flex items-center gap-3">
            <div class="flex items-center gap-2 px-4 py-2.5 bg-amber-50 border border-amber-200 rounded-xl">
                <div class="w-2 h-2 rounded-full bg-amber-500 animate-pulse"></div>
                <span class="text-amber-700 font-extrabold text-[12px]">MENUNGGU: {{ $pendaftarDaftarUlang->where('status_daftar_ulang', 'Menunggu Validasi')->count() }}</span>
            </div>
            <div class="flex items-center gap-2 px-4 py-2.5 bg-green-50 border border-green-200 rounded-xl">
                <div class="w-2 h-2 rounded-full bg-green-500"></div>
                <span class="text-green-700 font-extrabold text-[12px]">SELESAI: {{ $pendaftarDaftarUlang->where('status_daftar_ulang', 'Selesai')->count() }}</span>
            </div>
        </div>
    </div>

    @if(session('success'))
        <div class="mb-6 p-4 bg-green-50 border-l-4 border-green-500 rounded-r-xl flex items-start gap-3">
            <i data-feather="check-circle" class="w-5 h-5 text-green-600 shrink-0 mt-0.5"></i>
            <p class="text-[13px] font-bold text-green-800">{{ session('success') }}</p>
        </div>
    @endif
    @if(session('error'))
        <div class="mb-6 p-4 bg-red-50 border-l-4 border-red-500 rounded-r-xl flex items-start gap-3">
            <i data-feather="alert-circle" class="w-5 h-5 text-red-600 shrink-0 mt-0.5"></i>
            <p class="text-[13px] font-bold text-red-800">{{ session('error') }}</p>
        </div>
    @endif

    <div class="bg-white rounded-[2rem] shadow-sm border border-gray-100 overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-100 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-3">
            <p class="text-[13px] font-bold text-gray-500">Total <span class="text-[#0B1C39]">{{ $pendaftarDaftarUlang->count() }}</span> pendaftar</p>
            <div class="relative">
                <i data-feather="search" class="w-4 h-4 text-gray-400 absolute left-3 top-1/2 -translate-y-1/2 pointer-events-none"></i>
                <input type="text" placeholder="Cari nama / no. pendaftaran"
                       @input="cari = $event.target.value.toLowerCase()"
                       class="pl-9 pr-4 py-2.5 border border-gray-200 rounded-xl text-[13px] font-medium bg-gray-50 focus:outline-none focus:border-brand-blue focus:bg-white transition-all w-64">
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left whitespace-nowrap">
                <thead class="bg-gray-50/50 text-[11px] font-black text-brand-dark uppercase tracking-widest border-b border-gray-100">
                    <tr>
                        <th class="px-6 py-5">Pendaftar</th>
                        <th class="px-4 py-5">Prodi Diterima</th>
                        <th class="px-4 py-5">No. Pendaftaran</th>
                        <th class="px-4 py-5">Status Berkas</th>
                        <th class="px-4 py-5">NIM</th>
                        <th class="px-6 py-5 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50 text-[13px]">
                    @forelse($pendaftarDaftarUlang as $item)
                    @php
                        $prodiDiterima = $item->status_kelulusan === 'Lulus Pilihan 2' ? $item->pilihan_jurusan_2 : $item->pilihan_jurusan_1;
                        $pasFoto = $item->pas_foto ? asset($item->pas_foto) : null;
                        if (!$pasFoto && !empty($item->berkas_dokumen)) {
                            $berkas = is_array($item->berkas_dokumen) ? $item->berkas_dokumen : (json_decode($item->berkas_dokumen ?? '{}', true) ?? []);
                            foreach ($berkas as $nama => $path) {
                                if (stripos($nama, 'foto') !== false || stripos($nama, 'pas') !== false) { $pasFoto = asset($path); break; }
                            }
                        }
                        $statusDU   = strtolower($item->status_daftar_ulang ?? 'belum');
                        $buktiBayar = $item->bukti_daftar_ulang ? asset('uploads/bukti_daftar_ulang/' . $item->bukti_daftar_ulang) : '';
                        $nimVal     = $item->nim ?? '';
                        $pasFotoVal = $pasFoto ?? '';
                        $prodiVal   = addslashes($prodiDiterima ?? '-');
                        $namaVal    = addslashes($item->nama_lengkap ?? '');
                        $dataOrtuJson = json_encode($item->data_ortu ?? null);
                    @endphp
                    <tr class="hover:bg-gray-50/50 transition-colors group"
                        x-show="!cari || '{{ strtolower($item->nama_lengkap ?? '') }}'.includes(cari) || '{{ strtolower($item->no_pendaftaran ?? '') }}'.includes(cari)">

                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3">
                                @if($pasFoto)
                                    <img src="{{ $pasFoto }}" class="w-10 h-10 rounded-full object-cover border-2 border-gray-100 shrink-0">
                                @else
                                    <div class="w-10 h-10 rounded-full bg-[#0B1C39] text-white flex items-center justify-center font-black text-[11px] shrink-0">
                                        {{ strtoupper(substr($item->nama_lengkap ?? '??', 0, 2)) }}
                                    </div>
                                @endif
                                <div>
                                    <p class="font-bold text-brand-dark text-[14px]">{{ $item->nama_lengkap }}</p>
                                    <p class="text-gray-400 text-[11px] font-bold">{{ $item->no_whatsapp }}</p>
                                </div>
                            </div>
                        </td>

                        <td class="px-4 py-4">
                            <span class="inline-flex items-center px-2.5 py-1 bg-green-50 text-green-700 rounded-lg text-[11px] font-bold">{{ $prodiDiterima ?? '-' }}</span>
                            <p class="text-[10px] font-bold text-gray-400 mt-1">{{ $item->status_kelulusan }}</p>
                        </td>

                        <td class="px-4 py-4">
                            <span class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-gray-100 text-gray-700 rounded-lg font-mono font-bold text-[12px]">
                                <i data-feather="hash" class="w-3 h-3 text-gray-400"></i>{{ $item->no_pendaftaran }}
                            </span>
                        </td>

                        <td class="px-4 py-4">
                            @if($statusDU === 'selesai')
                                <span class="inline-flex items-center gap-1.5 px-3 py-1 bg-green-50 text-green-600 rounded-full text-[10px] font-black uppercase tracking-wider border border-green-200">
                                    <div class="w-1.5 h-1.5 rounded-full bg-green-500"></div> Disetujui
                                </span>
                            @elseif($statusDU === 'revisi')
                                <span class="inline-flex items-center gap-1.5 px-3 py-1 bg-red-50 text-red-600 rounded-full text-[10px] font-black uppercase tracking-wider border border-red-200">
                                    <div class="w-1.5 h-1.5 rounded-full bg-red-500"></div> Perlu Revisi
                                </span>
                            @elseif($statusDU === 'menunggu validasi')
                                <span class="inline-flex items-center gap-1.5 px-3 py-1 bg-amber-50 text-amber-600 rounded-full text-[10px] font-black uppercase tracking-wider border border-amber-200">
                                    <div class="w-1.5 h-1.5 rounded-full bg-amber-500 animate-pulse"></div> Menunggu
                                </span>
                            @else
                                <span class="inline-flex items-center gap-1.5 px-3 py-1 bg-gray-100 text-gray-500 rounded-full text-[10px] font-black uppercase tracking-wider">
                                    <div class="w-1.5 h-1.5 rounded-full bg-gray-400"></div> Belum Upload
                                </span>
                            @endif
                        </td>

                        <td class="px-4 py-4">
                            @if($item->nim)
                                <span class="font-mono font-black text-brand-blue text-[13px]">{{ $item->nim }}</span>
                            @else
                                <span class="text-gray-300 font-bold text-[12px]">Belum ada</span>
                            @endif
                        </td>

                        <td class="px-6 py-4 text-center">
                            <button
                                @click="bukaModal({{ $item->id }}, '{{ $namaVal }}', '{{ $item->no_pendaftaran }}', '{{ $prodiVal }}', '{{ $item->status_kelulusan }}', '{{ $item->status_daftar_ulang }}', '{{ $nimVal }}', '{{ $pasFotoVal }}', '{{ $item->no_whatsapp }}', {!! $dataOrtuJson !!}, '{{ $buktiBayar }}')"
                                class="inline-flex items-center gap-2 px-4 py-2 bg-[#0B1C39] text-white rounded-xl font-bold text-[12px] hover:bg-brand-blue transition-all shadow-sm">
                                <i data-feather="eye" class="w-3.5 h-3.5"></i> Tinjau
                            </button>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-6 py-16 text-center">
                            <div class="flex flex-col items-center gap-3 text-gray-400">
                                <i data-feather="inbox" class="w-10 h-10"></i>
                                <p class="font-bold text-[14px]">Belum ada berkas daftar ulang yang masuk.</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- MODAL --}}
    <div x-show="modalOpen" class="fixed inset-0 z-50 flex items-center justify-center px-4 py-6" style="display:none;">
        <div x-show="modalOpen" x-transition.opacity @click="modalOpen = false"
             class="absolute inset-0 bg-[#0B1C39]/60 backdrop-blur-sm cursor-pointer"></div>

        <div x-show="modalOpen"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 scale-95"
             x-transition:enter-end="opacity-100 scale-100"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100 scale-100"
             x-transition:leave-end="opacity-0 scale-95"
             class="bg-white w-full max-w-2xl rounded-[2rem] shadow-2xl relative z-10 flex flex-col max-h-[88vh] overflow-hidden">

            {{-- Header --}}
            <div class="px-6 py-5 border-b border-gray-100 flex justify-between items-start bg-gray-50/60 shrink-0">
                <div class="flex items-center gap-3">
                    <template x-if="data.pasFoto">
                        <img :src="data.pasFoto" class="w-11 h-11 rounded-xl object-cover border-2 border-gray-200 shrink-0">
                    </template>
                    <template x-if="!data.pasFoto">
                        <div class="w-11 h-11 rounded-xl bg-[#0B1C39] text-white flex items-center justify-center font-black text-[13px] shrink-0">
                            <span x-text="data.nama ? data.nama.substring(0,2).toUpperCase() : '??'"></span>
                        </div>
                    </template>
                    <div>
                        <h2 class="text-[16px] font-extrabold text-[#0B1C39]" x-text="data.nama"></h2>
                        <div class="flex items-center gap-2 mt-0.5">
                            <p class="text-[11px] font-bold text-gray-400 font-mono" x-text="'#' + data.noPendaftaran"></p>
                            <template x-if="data.nim">
                                <span class="inline-flex items-center gap-1 px-2 py-0.5 bg-brand-blue text-white rounded-md text-[10px] font-black">
                                    NIM: <span x-text="data.nim"></span>
                                </span>
                            </template>
                        </div>
                    </div>
                </div>
                <button @click="modalOpen = false" class="p-2 bg-white border border-gray-200 hover:bg-gray-100 rounded-full transition-colors shrink-0">
                    <i data-feather="x" class="w-4 h-4 text-gray-500"></i>
                </button>
            </div>

            {{-- Body --}}
            <div class="overflow-y-auto flex-grow px-6 py-6 space-y-5">

                {{-- Info kelulusan --}}
                <div class="flex items-center gap-3 p-4 bg-green-50 border border-green-200 rounded-xl">
                    <i data-feather="check-circle" class="w-5 h-5 text-green-600 shrink-0"></i>
                    <div>
                        <p class="text-[12px] font-black text-green-800 uppercase tracking-widest" x-text="data.statusKelulusan"></p>
                        <p class="text-[13px] font-bold text-green-700 mt-0.5" x-text="'Program Studi: ' + data.prodi"></p>
                    </div>
                </div>

                {{-- Data Ortu --}}
                <div>
                    <div class="flex items-center gap-2 mb-3">
                        <div class="w-7 h-7 rounded-lg bg-blue-50 text-brand-blue flex items-center justify-center shrink-0">
                            <i data-feather="users" class="w-4 h-4"></i>
                        </div>
                        <h3 class="text-[12px] font-black text-[#0B1C39] uppercase tracking-widest">Data Orang Tua</h3>
                    </div>
                    <template x-if="ortu && ortu.ayah">
                        <div class="space-y-2">
                            <div class="rounded-xl border border-gray-100 overflow-hidden">
                                <div class="px-4 py-2 bg-blue-50 border-b border-blue-100">
                                    <span class="text-[11px] font-black text-blue-700 uppercase tracking-widest">Ayah</span>
                                </div>
                                <div class="p-4 grid grid-cols-2 gap-x-6 gap-y-2.5">
                                    <div><p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-0.5">Nama</p><p class="text-[13px] font-bold text-[#0B1C39]" x-text="ortu.ayah.nama || '-'"></p></div>
                                    <div><p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-0.5">No. HP</p><p class="text-[13px] font-bold text-[#0B1C39]" x-text="ortu.ayah.no_hp || '-'"></p></div>
                                    <div><p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-0.5">Pekerjaan</p><p class="text-[13px] font-bold text-[#0B1C39]" x-text="ortu.ayah.pekerjaan || '-'"></p></div>
                                    <div><p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-0.5">Penghasilan</p><p class="text-[13px] font-bold text-[#0B1C39]" x-text="ortu.ayah.penghasilan ? 'Rp ' + Number(ortu.ayah.penghasilan).toLocaleString('id-ID') : '-'"></p></div>
                                </div>
                            </div>
                            <div class="rounded-xl border border-gray-100 overflow-hidden">
                                <div class="px-4 py-2 bg-pink-50 border-b border-pink-100">
                                    <span class="text-[11px] font-black text-pink-700 uppercase tracking-widest">Ibu</span>
                                </div>
                                <div class="p-4 grid grid-cols-2 gap-x-6 gap-y-2.5">
                                    <div><p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-0.5">Nama</p><p class="text-[13px] font-bold text-[#0B1C39]" x-text="ortu.ibu.nama || '-'"></p></div>
                                    <div><p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-0.5">No. HP</p><p class="text-[13px] font-bold text-[#0B1C39]" x-text="ortu.ibu.no_hp || '-'"></p></div>
                                    <div><p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-0.5">Pekerjaan</p><p class="text-[13px] font-bold text-[#0B1C39]" x-text="ortu.ibu.pekerjaan || '-'"></p></div>
                                    <div><p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-0.5">Penghasilan</p><p class="text-[13px] font-bold text-[#0B1C39]" x-text="ortu.ibu.penghasilan ? 'Rp ' + Number(ortu.ibu.penghasilan).toLocaleString('id-ID') : '-'"></p></div>
                                </div>
                            </div>
                        </div>
                    </template>
                    <template x-if="!ortu || !ortu.ayah">
                        <div class="flex items-center gap-3 p-4 bg-gray-50 rounded-xl border border-dashed border-gray-200">
                            <i data-feather="alert-circle" class="w-4 h-4 text-gray-400 shrink-0"></i>
                            <p class="text-[13px] font-medium text-gray-400">Data orang tua belum diisi.</p>
                        </div>
                    </template>
                </div>

                <hr class="border-gray-100">

                {{-- Bukti Bayar --}}
                <div>
                    <div class="flex items-center gap-2 mb-3">
                        <div class="w-7 h-7 rounded-lg bg-green-50 text-green-600 flex items-center justify-center shrink-0">
                            <i data-feather="credit-card" class="w-4 h-4"></i>
                        </div>
                        <h3 class="text-[12px] font-black text-[#0B1C39] uppercase tracking-widest">Bukti Pembayaran Daftar Ulang</h3>
                    </div>
                    <template x-if="data.buktiBayar">
                        <div>
                            <div class="w-full h-52 bg-gray-100 rounded-2xl border-2 border-dashed border-gray-300 overflow-hidden relative group mb-3">
                                <img :src="data.buktiBayar" class="w-full h-full object-contain" alt="Bukti" @error="$el.style.display='none'">
                                <a :href="data.buktiBayar" target="_blank"
                                   class="absolute inset-0 bg-brand-dark/50 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity">
                                    <span class="bg-white text-brand-dark px-4 py-2 rounded-lg font-bold text-[11px] flex items-center gap-2">
                                        <i data-feather="maximize-2" class="w-3.5 h-3.5"></i> Lihat Penuh
                                    </span>
                                </a>
                            </div>
                            <a :href="data.buktiBayar" target="_blank"
                               class="inline-flex items-center gap-2 px-4 py-2.5 bg-green-600 text-white rounded-xl font-bold text-[12px] hover:bg-green-700 transition-colors">
                                <i data-feather="external-link" class="w-3.5 h-3.5"></i> Buka File Asli
                            </a>
                        </div>
                    </template>
                    <template x-if="!data.buktiBayar">
                        <div class="flex items-center gap-3 p-4 bg-red-50 border border-red-100 rounded-xl">
                            <i data-feather="alert-triangle" class="w-5 h-5 text-red-400 shrink-0"></i>
                            <p class="text-[13px] font-bold text-red-600">Bukti pembayaran belum diunggah.</p>
                        </div>
                    </template>
                </div>

                <hr class="border-gray-100">

                {{-- Aksi Admin --}}
                <div>
                    <div class="flex items-center gap-2 mb-4">
                        <div class="w-7 h-7 rounded-lg bg-[#0B1C39]/10 text-[#0B1C39] flex items-center justify-center shrink-0">
                            <i data-feather="shield" class="w-4 h-4"></i>
                        </div>
                        <h3 class="text-[12px] font-black text-[#0B1C39] uppercase tracking-widest">Tindakan Admin</h3>
                    </div>

                    <template x-if="data.statusDaftarUlang === 'Selesai'">
                        <div class="flex items-center gap-3 p-4 bg-green-50 border border-green-200 rounded-xl">
                            <i data-feather="check-circle" class="w-5 h-5 text-green-600 shrink-0"></i>
                            <div>
                                <p class="text-[13px] font-extrabold text-green-800">Berkas Sudah Diverifikasi</p>
                                <p class="text-[12px] font-medium text-green-600 mt-0.5">NIM: <strong x-text="data.nim"></strong></p>
                            </div>
                        </div>
                    </template>

                    <template x-if="data.statusDaftarUlang !== 'Selesai'">
                        <div class="space-y-3">
                            <form x-bind:action="setujuiUrl" method="POST">
                                @csrf
                                <button type="submit" class="w-full flex items-center justify-center gap-2.5 px-5 py-3.5 bg-[#0B1C39] hover:bg-brand-blue text-white rounded-xl font-extrabold text-[13px] transition-all shadow-sm">
                                    <i data-feather="award" class="w-4 h-4"></i> Setujui & Terbitkan NIM
                                </button>
                            </form>
                            <div x-data="{ revisiOpen: false }">
                                <button type="button" @click="revisiOpen = !revisiOpen"
                                        class="w-full flex items-center justify-between gap-2.5 px-5 py-3.5 border-2 rounded-xl font-extrabold text-[13px] transition-all"
                                        :class="revisiOpen ? 'border-amber-400 bg-amber-50 text-amber-800' : 'border-amber-200 bg-amber-50/50 text-amber-700 hover:border-amber-300'">
                                    <span class="flex items-center gap-2.5"><i data-feather="edit-3" class="w-4 h-4"></i> Minta Revisi Berkas</span>
                                    <i data-feather="chevron-down" class="w-4 h-4 transition-transform" :class="revisiOpen ? 'rotate-180' : ''"></i>
                                </button>
                                <div x-show="revisiOpen" x-transition class="mt-2">
                                    <form x-bind:action="revisiUrl" method="POST"
                                          class="p-4 bg-amber-50 border border-amber-200 rounded-xl space-y-3">
                                        @csrf
                                        <label class="block text-[11px] font-black text-amber-700 uppercase tracking-widest">Pesan Revisi <span class="text-red-500">*</span></label>
                                        <textarea name="pesan_revisi" required rows="3"
                                                  placeholder="Contoh: Bukti pembayaran tidak terbaca..."
                                                  class="w-full px-4 py-3 bg-white border border-amber-200 rounded-xl text-[13px] font-medium placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-amber-400 resize-none"></textarea>
                                        <button type="submit" class="w-full flex items-center justify-center gap-2 px-5 py-3 bg-amber-600 hover:bg-amber-700 text-white rounded-xl font-extrabold text-[13px] transition-colors">
                                            <i data-feather="send" class="w-4 h-4"></i> Kirim Pesan Revisi
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </template>
                </div>

            </div>

            <div class="px-6 py-4 border-t border-gray-100 bg-gray-50/40 flex justify-end shrink-0">
                <button type="button" @click="modalOpen = false"
                        class="inline-flex items-center gap-2 px-5 py-2.5 border border-gray-200 bg-white hover:bg-gray-50 text-gray-600 rounded-xl font-bold text-[13px] transition-colors">
                    <i data-feather="x" class="w-4 h-4"></i> Tutup
                </button>
            </div>
        </div>
    </div>

</div>

<script>
var baseSetujui = "{{ rtrim(url('admin/setujui-daftar-ulang'), '/') }}";
var baseRevisi  = "{{ rtrim(url('admin/revisi-daftar-ulang'), '/') }}";

function validasiBerkas() {
    return {
        modalOpen: false,
        cari: '',
        data: {},

        get ortu() {
            const raw = this.data.dataOrtu;
            if (!raw) return null;
            if (typeof raw === 'object') return raw;
            try { return JSON.parse(raw); } catch(e) { return null; }
        },

        get setujuiUrl() {
            return baseSetujui + '/' + this.data.id;
        },

        get revisiUrl() {
            return baseRevisi + '/' + this.data.id;
        },

        bukaModal(id, nama, noPendaftaran, prodi, statusKelulusan, statusDaftarUlang, nim, pasFoto, noWa, dataOrtu, buktiBayar) {
            this.data = { id, nama, noPendaftaran, prodi, statusKelulusan, statusDaftarUlang, nim, pasFoto, noWa, dataOrtu, buktiBayar };
            this.modalOpen = true;
            this.$nextTick(() => { if (window.feather) feather.replace(); });
        }
    }
}
</script>

@endsection