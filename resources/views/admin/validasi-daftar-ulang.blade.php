@extends('layouts.admin')

@section('admin-content')

<div x-data="validasiDaftarUlang()" x-cloak>

    {{-- ===== PAGE HEADER ===== --}}
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-8 gap-4">
        <div>
            <h1 class="text-3xl font-extrabold text-[#0B1C39] tracking-tight mb-1">Validasi Berkas</h1>
            <p class="text-gray-400 text-[14px] font-medium">
                Tinjau dan verifikasi berkas daftar ulang mahasiswa baru tahun akademik 2024/2025.
            </p>
        </div>
        {{-- Badge ringkasan --}}
        <div class="flex items-center gap-3">
            <div class="flex items-center gap-2 px-4 py-2.5 bg-amber-50 border border-amber-200 rounded-xl">
                <div class="w-2 h-2 rounded-full bg-amber-500 animate-pulse"></div>
                <span class="text-amber-700 font-extrabold text-[12px]">PERLU TINDAKAN: {{ $pendaftarDaftarUlang->where('status_daftar_ulang', 'menunggu')->count() ?? 0 }}</span>
            </div>
            <div class="flex items-center gap-2 px-4 py-2.5 bg-green-50 border border-green-200 rounded-xl">
                <div class="w-2 h-2 rounded-full bg-green-500"></div>
                <span class="text-green-700 font-extrabold text-[12px]">DISETUJUI: {{ $pendaftarDaftarUlang->where('status_daftar_ulang', 'disetujui')->count() ?? 0 }}</span>
            </div>
        </div>
    </div>

    {{-- ===== FLASH MESSAGES ===== --}}
    @if(session('success'))
        <div class="bg-green-50 border-l-4 border-green-500 p-4 mb-6 rounded-r-xl flex items-start gap-3">
            <i data-feather="check-circle" class="w-5 h-5 text-green-600 shrink-0 mt-0.5"></i>
            <p class="text-sm font-bold text-green-700">{{ session('success') }}</p>
        </div>
    @endif
    @if(session('error'))
        <div class="bg-red-50 border-l-4 border-red-500 p-4 mb-6 rounded-r-xl flex items-start gap-3">
            <i data-feather="alert-circle" class="w-5 h-5 text-red-600 shrink-0 mt-0.5"></i>
            <p class="text-sm font-bold text-red-700">{{ session('error') }}</p>
        </div>
    @endif

    {{-- ===== TABEL UTAMA ===== --}}
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">

        {{-- Toolbar tabel --}}
        <div class="px-6 py-4 border-b border-gray-100 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-3">
            <p class="text-[13px] font-bold text-gray-500">
                Total <span class="text-[#0B1C39]">{{ $pendaftarDaftarUlang->count() }}</span> pendaftar
            </p>
            <div class="relative">
                <i data-feather="search" class="w-4 h-4 text-gray-400 absolute left-3 top-1/2 -translate-y-1/2 pointer-events-none"></i>
                <input type="text" placeholder="Cari nama / no. pendaftaran…"
                    @input="cariData($event.target.value)"
                    class="pl-9 pr-4 py-2.5 border border-gray-200 rounded-xl text-[13px] font-medium bg-gray-50 focus:outline-none focus:border-blue-400 focus:bg-white transition-all w-64">
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left whitespace-nowrap">
                <thead class="bg-gray-50 text-[11px] font-black text-gray-500 uppercase tracking-widest border-b border-gray-100">
                    <tr>
                        <th class="px-6 py-4 w-8">#</th>
                        <th class="px-4 py-4">No. Pendaftaran</th>
                        <th class="px-4 py-4">Nama Mahasiswa</th>
                        <th class="px-4 py-4">Status Daftar Ulang</th>
                        <th class="px-6 py-4 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50 text-[13px]">
                    @forelse($pendaftarDaftarUlang as $index => $item)
                    <tr class="hover:bg-gray-50/60 transition-colors group"
                        x-show="filterRow('{{ addslashes($item->nama_lengkap ?? '') }}', '{{ $item->no_pendaftaran ?? '' }}')"
                        data-row>

                        {{-- Nomor urut --}}
                        <td class="px-6 py-4 text-gray-400 font-bold text-[12px]">{{ $index + 1 }}</td>

                        {{-- No. Pendaftaran --}}
                        <td class="px-4 py-4">
                            <span class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-gray-100 text-gray-700 rounded-lg font-mono font-bold text-[12px] tracking-wide">
                                <i data-feather="hash" class="w-3 h-3 text-gray-400"></i>
                                {{ $item->no_pendaftaran ?? '-' }}
                            </span>
                        </td>

                        {{-- Nama Mahasiswa --}}
                        <td class="px-4 py-4">
                            <div class="flex items-center gap-3">
                                <div class="w-9 h-9 rounded-xl bg-[#0B1C39] text-white flex items-center justify-center font-black text-[11px] shadow-sm shrink-0">
                                    {{ strtoupper(substr($item->nama_lengkap ?? '??', 0, 2)) }}
                                </div>
                                <span class="font-bold text-[#0B1C39] text-[14px]">
                                    {{ $item->nama_lengkap ?? 'Nama Tidak Ditemukan' }}
                                </span>
                            </div>
                        </td>

                        {{-- Status Daftar Ulang --}}
                        <td class="px-4 py-4">
                            @php $status = strtolower($item->status_daftar_ulang ?? ''); @endphp
                            @if(in_array($status, ['menunggu', 'menunggu verifikasi', 'pending']))
                                <span class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-amber-50 text-amber-600 rounded-full text-[11px] font-black uppercase tracking-wide border border-amber-200">
                                    <div class="w-1.5 h-1.5 rounded-full bg-amber-500 animate-pulse"></div> Menunggu Verifikasi
                                </span>
                            @elseif(in_array($status, ['revisi', 'perlu revisi']))
                                <span class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-red-50 text-red-600 rounded-full text-[11px] font-black uppercase tracking-wide border border-red-200">
                                    <div class="w-1.5 h-1.5 rounded-full bg-red-500"></div> Perlu Revisi
                                </span>
                            @elseif(in_array($status, ['disetujui', 'selesai', 'valid', 'terverifikasi']))
                                <span class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-green-50 text-green-600 rounded-full text-[11px] font-black uppercase tracking-wide border border-green-200">
                                    <div class="w-1.5 h-1.5 rounded-full bg-green-500"></div> Disetujui
                                </span>
                            @else
                                <span class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-gray-100 text-gray-500 rounded-full text-[11px] font-black uppercase tracking-wide border border-gray-200">
                                    <div class="w-1.5 h-1.5 rounded-full bg-gray-400"></div> Belum Lengkap
                                </span>
                            @endif
                        </td>

                        {{-- Aksi --}}
                        <td class="px-6 py-4 text-center">
                            <button
                                @click="bukaModal({
                                    id: {{ $item->id }},
                                    nama: '{{ addslashes($item->nama_lengkap ?? 'N/A') }}',
                                    noPendaftaran: '{{ $item->no_pendaftaran ?? '-' }}',
                                    statusDaftarUlang: '{{ $item->status_daftar_ulang ?? '-' }}',
                                    dataOrtu: {{ json_encode($item->data_ortu ?? 'null') }},
                                    buktiBayar: '{{ $item->bukti_daftar_ulang ? asset('uploads/bukti_daftar_ulang/' . $item->bukti_daftar_ulang) : '' }}'
                                })"
                                class="inline-flex items-center gap-2 px-4 py-2 bg-[#0B1C39] text-white rounded-xl font-bold text-[12px] hover:bg-blue-600 transition-all shadow-sm group-hover:shadow-md">
                                <i data-feather="eye" class="w-3.5 h-3.5"></i>
                                Lihat Detail
                            </button>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-6 py-16 text-center">
                            <div class="flex flex-col items-center gap-3 text-gray-400">
                                <i data-feather="inbox" class="w-10 h-10"></i>
                                <p class="font-bold text-[14px]">Belum ada data daftar ulang yang masuk.</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Paginasi --}}
        @if(method_exists($pendaftarDaftarUlang, 'links'))
            <div class="px-6 py-4 border-t border-gray-100">
                {{ $pendaftarDaftarUlang->links() }}
            </div>
        @endif
    </div>

    {{-- ===================================================== --}}
    {{-- MODAL DETAIL DAFTAR ULANG                             --}}
    {{-- ===================================================== --}}
    <div x-show="modalOpen"
         class="fixed inset-0 z-50 flex items-center justify-center px-4 py-6"
         style="display: none;">

        {{-- Backdrop --}}
        <div x-show="modalOpen"
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="transition ease-in duration-150"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             @click="tutupModal()"
             class="absolute inset-0 bg-[#0B1C39]/60 backdrop-blur-sm cursor-pointer">
        </div>

        {{-- Panel Modal --}}
        <div x-show="modalOpen"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 scale-95 translate-y-4"
             x-transition:enter-end="opacity-100 scale-100 translate-y-0"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100 scale-100 translate-y-0"
             x-transition:leave-end="opacity-0 scale-95 translate-y-4"
             class="bg-white w-full max-w-2xl rounded-2xl shadow-2xl relative z-10 flex flex-col max-h-[88vh] overflow-hidden">

            {{-- ── Modal Header ── --}}
            <div class="px-6 py-5 border-b border-gray-100 flex justify-between items-start bg-gray-50/60 shrink-0">
                <div>
                    <h2 class="text-[17px] font-extrabold text-[#0B1C39] tracking-tight">Detail Daftar Ulang</h2>
                    <div class="flex items-center gap-2 mt-1.5">
                        <span class="text-[12px] font-bold text-gray-400 uppercase tracking-widest" x-text="dataSiswa.nama"></span>
                        <span class="text-gray-200 font-bold">·</span>
                        <span class="font-mono text-[11px] font-bold text-gray-400" x-text="'#' + dataSiswa.noPendaftaran"></span>
                    </div>
                </div>
                <button @click="tutupModal()"
                        class="p-2 bg-white border border-gray-200 hover:bg-gray-100 rounded-full transition-colors shrink-0">
                    <i data-feather="x" class="w-4 h-4 text-gray-500"></i>
                </button>
            </div>

            {{-- ── Modal Body (scrollable) ── --}}
            <div class="overflow-y-auto flex-grow px-6 py-6 space-y-5">

                {{-- ── 1. DATA ORANG TUA ── --}}
                <div>
                    <div class="flex items-center gap-2 mb-3">
                        <div class="w-7 h-7 rounded-lg bg-blue-50 text-blue-600 flex items-center justify-center shrink-0">
                            <i data-feather="users" class="w-4 h-4"></i>
                        </div>
                        <h3 class="text-[13px] font-black text-[#0B1C39] uppercase tracking-widest">Data Orang Tua / Wali</h3>
                    </div>

                    <div class="space-y-3">

                        {{-- Ayah --}}
                        <template x-if="ortu && ortu.ayah">
                            <div class="rounded-xl border border-gray-100 overflow-hidden">
                                <div class="px-4 py-2.5 bg-blue-50 border-b border-blue-100 flex items-center gap-2">
                                    <i data-feather="user" class="w-3.5 h-3.5 text-blue-500"></i>
                                    <span class="text-[11px] font-black text-blue-700 uppercase tracking-widest">Ayah</span>
                                </div>
                                <div class="p-4 grid grid-cols-1 sm:grid-cols-2 gap-x-6 gap-y-3">
                                    <div>
                                        <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-0.5">Nama Lengkap</p>
                                        <p class="text-[13px] font-bold text-[#0B1C39]" x-text="ortu.ayah.nama || '-'"></p>
                                    </div>
                                    <div>
                                        <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-0.5">No. HP / Telepon</p>
                                        <p class="text-[13px] font-bold text-[#0B1C39]" x-text="ortu.ayah.no_hp || ortu.ayah.telepon || '-'"></p>
                                    </div>
                                    <div>
                                        <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-0.5">Pekerjaan</p>
                                        <p class="text-[13px] font-bold text-[#0B1C39]" x-text="ortu.ayah.pekerjaan || '-'"></p>
                                    </div>
                                    <div>
                                        <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-0.5">Penghasilan</p>
                                        <p class="text-[13px] font-bold text-[#0B1C39]" x-text="ortu.ayah.penghasilan || '-'"></p>
                                    </div>
                                </div>
                            </div>
                        </template>

                        {{-- Ibu --}}
                        <template x-if="ortu && ortu.ibu">
                            <div class="rounded-xl border border-gray-100 overflow-hidden">
                                <div class="px-4 py-2.5 bg-pink-50 border-b border-pink-100 flex items-center gap-2">
                                    <i data-feather="user" class="w-3.5 h-3.5 text-pink-500"></i>
                                    <span class="text-[11px] font-black text-pink-700 uppercase tracking-widest">Ibu</span>
                                </div>
                                <div class="p-4 grid grid-cols-1 sm:grid-cols-2 gap-x-6 gap-y-3">
                                    <div>
                                        <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-0.5">Nama Lengkap</p>
                                        <p class="text-[13px] font-bold text-[#0B1C39]" x-text="ortu.ibu.nama || '-'"></p>
                                    </div>
                                    <div>
                                        <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-0.5">No. HP / Telepon</p>
                                        <p class="text-[13px] font-bold text-[#0B1C39]" x-text="ortu.ibu.no_hp || ortu.ibu.telepon || '-'"></p>
                                    </div>
                                    <div>
                                        <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-0.5">Pekerjaan</p>
                                        <p class="text-[13px] font-bold text-[#0B1C39]" x-text="ortu.ibu.pekerjaan || '-'"></p>
                                    </div>
                                    <div>
                                        <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-0.5">Penghasilan</p>
                                        <p class="text-[13px] font-bold text-[#0B1C39]" x-text="ortu.ibu.penghasilan || '-'"></p>
                                    </div>
                                </div>
                            </div>
                        </template>

                        {{-- Wali (opsional) --}}
                        <template x-if="ortu && ortu.wali">
                            <div class="rounded-xl border border-gray-100 overflow-hidden">
                                <div class="px-4 py-2.5 bg-purple-50 border-b border-purple-100 flex items-center gap-2">
                                    <i data-feather="user-check" class="w-3.5 h-3.5 text-purple-500"></i>
                                    <span class="text-[11px] font-black text-purple-700 uppercase tracking-widest">Wali</span>
                                </div>
                                <div class="p-4 grid grid-cols-1 sm:grid-cols-2 gap-x-6 gap-y-3">
                                    <div>
                                        <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-0.5">Nama Lengkap</p>
                                        <p class="text-[13px] font-bold text-[#0B1C39]" x-text="ortu.wali.nama || '-'"></p>
                                    </div>
                                    <div>
                                        <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-0.5">Hubungan</p>
                                        <p class="text-[13px] font-bold text-[#0B1C39]" x-text="ortu.wali.hubungan || '-'"></p>
                                    </div>
                                    <div>
                                        <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-0.5">No. HP / Telepon</p>
                                        <p class="text-[13px] font-bold text-[#0B1C39]" x-text="ortu.wali.no_hp || ortu.wali.telepon || '-'"></p>
                                    </div>
                                    <div>
                                        <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-0.5">Pekerjaan</p>
                                        <p class="text-[13px] font-bold text-[#0B1C39]" x-text="ortu.wali.pekerjaan || '-'"></p>
                                    </div>
                                </div>
                            </div>
                        </template>

                        {{-- Fallback jika data_ortu kosong / null --}}
                        <template x-if="!ortu">
                            <div class="flex items-center gap-3 p-4 bg-gray-50 rounded-xl border border-dashed border-gray-200">
                                <i data-feather="alert-circle" class="w-4 h-4 text-gray-400 shrink-0"></i>
                                <p class="text-[13px] font-medium text-gray-400">Data orang tua belum tersedia atau belum diisi pendaftar.</p>
                            </div>
                        </template>
                    </div>
                </div>

                {{-- Divider --}}
                <hr class="border-gray-100">

                {{-- ── 2. BUKTI PEMBAYARAN DAFTAR ULANG ── --}}
                <div>
                    <div class="flex items-center gap-2 mb-3">
                        <div class="w-7 h-7 rounded-lg bg-green-50 text-green-600 flex items-center justify-center shrink-0">
                            <i data-feather="credit-card" class="w-4 h-4"></i>
                        </div>
                        <h3 class="text-[13px] font-black text-[#0B1C39] uppercase tracking-widest">Bukti Pembayaran Daftar Ulang</h3>
                    </div>

                    <template x-if="dataSiswa.buktiBayar">
                        <div class="flex items-center justify-between p-4 bg-green-50 border border-green-100 rounded-xl">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-xl bg-white border border-green-200 flex items-center justify-center shadow-sm">
                                    <i data-feather="file" class="w-5 h-5 text-green-600"></i>
                                </div>
                                <div>
                                    <p class="text-[13px] font-extrabold text-green-800">Bukti Pembayaran Tersedia</p>
                                    <p class="text-[11px] text-green-600 font-medium mt-0.5">Klik tombol untuk membuka file gambar / PDF</p>
                                </div>
                            </div>
                            <a :href="dataSiswa.buktiBayar"
                               target="_blank"
                               rel="noopener noreferrer"
                               class="inline-flex items-center gap-2 px-4 py-2.5 bg-green-600 text-white rounded-xl font-bold text-[12px] hover:bg-green-700 transition-colors shadow-sm shrink-0">
                                <i data-feather="external-link" class="w-3.5 h-3.5"></i>
                                Buka File
                            </a>
                        </div>
                    </template>

                    <template x-if="!dataSiswa.buktiBayar">
                        <div class="flex items-center gap-3 p-4 bg-red-50 border border-red-100 rounded-xl">
                            <i data-feather="alert-triangle" class="w-5 h-5 text-red-400 shrink-0"></i>
                            <div>
                                <p class="text-[13px] font-extrabold text-red-700">Bukti Pembayaran Belum Diunggah</p>
                                <p class="text-[11px] text-red-400 font-medium mt-0.5">Pendaftar belum melampirkan bukti transfer / pembayaran.</p>
                            </div>
                        </div>
                    </template>
                </div>

                {{-- Divider --}}
                <hr class="border-gray-100">

                {{-- ── 3. AKSI ADMIN ── --}}
                <div>
                    <div class="flex items-center gap-2 mb-4">
                        <div class="w-7 h-7 rounded-lg bg-[#0B1C39]/10 text-[#0B1C39] flex items-center justify-center shrink-0">
                            <i data-feather="shield" class="w-4 h-4"></i>
                        </div>
                        <h3 class="text-[13px] font-black text-[#0B1C39] uppercase tracking-widest">Tindakan Admin</h3>
                    </div>

                    <div class="space-y-3">

                        {{-- FORM SETUJUI --}}
                        <form :action="`{{ url('admin/setujui-daftar-ulang') }}/${dataSiswa.id}`" method="POST">
                            @csrf
                            <button type="submit"
                                    class="w-full flex items-center justify-center gap-2.5 px-5 py-3.5 bg-[#0B1C39] hover:bg-blue-700 text-white rounded-xl font-extrabold text-[13px] transition-all shadow-sm hover:shadow-md">
                                <i data-feather="check-circle" class="w-4 h-4"></i>
                                Setujui & Verifikasi Daftar Ulang
                            </button>
                        </form>

                        {{-- ACCORDION REVISI --}}
                        <div x-data="{ revisiOpen: false }">
                            <button type="button"
                                    @click="revisiOpen = !revisiOpen"
                                    class="w-full flex items-center justify-between gap-2.5 px-5 py-3.5 border-2 rounded-xl font-extrabold text-[13px] transition-all"
                                    :class="revisiOpen
                                        ? 'border-amber-400 bg-amber-50 text-amber-800'
                                        : 'border-amber-200 bg-amber-50/50 text-amber-700 hover:border-amber-300'">
                                <span class="flex items-center gap-2.5">
                                    <i data-feather="edit-3" class="w-4 h-4"></i>
                                    Minta Revisi Berkas
                                </span>
                                <i data-feather="chevron-down" class="w-4 h-4 transition-transform duration-200"
                                   :class="revisiOpen ? 'rotate-180' : ''"></i>
                            </button>

                            <div x-show="revisiOpen"
                                 x-transition:enter="transition ease-out duration-200"
                                 x-transition:enter-start="opacity-0 -translate-y-2"
                                 x-transition:enter-end="opacity-100 translate-y-0"
                                 class="mt-2">
                                <form :action="`{{ url('admin/revisi-daftar-ulang') }}/${dataSiswa.id}`" method="POST"
                                      class="p-4 bg-amber-50 border border-amber-200 rounded-xl space-y-3">
                                    @csrf
                                    <div>
                                        <label class="block text-[11px] font-black text-amber-700 uppercase tracking-widest mb-1.5">
                                            Pesan Revisi untuk Pendaftar <span class="text-red-500">*</span>
                                        </label>
                                        <textarea name="pesan_revisi"
                                                  required
                                                  rows="3"
                                                  placeholder="Contoh: Bukti pembayaran tidak terbaca, mohon unggah ulang dengan kualitas yang lebih jelas..."
                                                  class="w-full px-4 py-3 bg-white border border-amber-200 rounded-xl text-[13px] font-medium text-gray-700 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-amber-400 focus:border-transparent resize-none transition-all">
                                        </textarea>
                                    </div>
                                    <button type="submit"
                                            class="w-full flex items-center justify-center gap-2 px-5 py-3 bg-amber-600 hover:bg-amber-700 text-white rounded-xl font-extrabold text-[13px] transition-colors shadow-sm">
                                        <i data-feather="send" class="w-4 h-4"></i>
                                        Kirim Pesan Revisi
                                    </button>
                                </form>
                            </div>
                        </div>

                    </div>
                </div>

            </div>{{-- /Modal Body --}}

            {{-- ── Modal Footer ── --}}
            <div class="px-6 py-4 border-t border-gray-100 bg-gray-50/40 flex justify-end shrink-0">
                <button type="button"
                        @click="tutupModal()"
                        class="inline-flex items-center gap-2 px-5 py-2.5 border border-gray-200 bg-white hover:bg-gray-50 text-gray-600 rounded-xl font-bold text-[13px] transition-colors">
                    <i data-feather="x" class="w-4 h-4"></i>
                    Tutup
                </button>
            </div>

        </div>{{-- /Panel Modal --}}
    </div>{{-- /Modal Wrapper --}}

</div>{{-- /x-data --}}

<script>
function validasiDaftarUlang() {
    return {
        modalOpen: false,
        kataCari: '',

        dataSiswa: {
            id: null,
            nama: '',
            noPendaftaran: '',
            statusDaftarUlang: '',
            dataOrtu: null,
            buktiBayar: ''
        },

        // Computed: parse data_ortu (bisa berupa JSON string atau sudah object)
        get ortu() {
            const raw = this.dataSiswa.dataOrtu;
            if (!raw) return null;
            if (typeof raw === 'object') return raw;
            try { return JSON.parse(raw); }
            catch (e) { return null; }
        },

        bukaModal(data) {
            this.dataSiswa = data;
            this.modalOpen = true;
            // Re-init feather icons setelah modal render
            this.$nextTick(() => {
                if (window.feather) feather.replace();
            });
        },

        tutupModal() {
            this.modalOpen = false;
        },

        // Filter tabel secara client-side
        cariData(keyword) {
            this.kataCari = keyword.toLowerCase();
        },

        filterRow(nama, noPendaftaran) {
            if (!this.kataCari) return true;
            return nama.toLowerCase().includes(this.kataCari)
                || noPendaftaran.toLowerCase().includes(this.kataCari);
        }
    }
}
</script>

@endsection