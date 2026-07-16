@extends('layouts.admin')

@section('admin-content')

    <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-8 gap-4">
        <div>
            <h1 class="text-3xl font-extrabold text-[#0B1C39] tracking-tight mb-2">Data Pendaftar</h1>
            <p class="text-brand-gray text-[14px] font-medium">
                Kelola seluruh data pendaftar dan pantau status admisi secara real-time.
            </p>
        </div>
        <div class="flex items-center gap-3">
            <button class="flex items-center gap-2 px-5 py-2.5 bg-white border border-gray-200 text-brand-dark rounded-xl font-bold text-[13px] hover:bg-gray-50 transition-all shadow-sm">
                <i data-feather="filter" class="w-4 h-4"></i> Filter
            </button>
            <a href="{{ route('admin.export.csv') }}" class="flex items-center gap-2 px-5 py-2.5 bg-brand-dark text-white rounded-xl font-bold text-[13px] hover:bg-opacity-90 transition-all shadow-md">
                <i data-feather="download" class="w-4 h-4"></i> Export CSV
            </a>
        </div>
    </div>

    {{-- Statistik Atas --}}
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
        <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 flex flex-col justify-center text-center hover:shadow-md transition-shadow">
            <p class="text-[11px] font-extrabold text-gray-400 uppercase tracking-widest mb-2">Total Pendaftar</p>
            <h3 class="text-4xl font-black text-brand-dark mb-2">{{ number_format($totalPendaftar) }}</h3> 
        </div>
        <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 flex flex-col justify-center text-center hover:shadow-md transition-shadow">
            <p class="text-[11px] font-extrabold text-gray-400 uppercase tracking-widest mb-2">Menunggu Validasi</p>
            <h3 class="text-4xl font-black text-amber-500 mb-2">{{ number_format($menungguValidasi) }}</h3> 
        </div>
        <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 flex flex-col justify-center text-center hover:shadow-md transition-shadow">
            <p class="text-[11px] font-extrabold text-gray-400 uppercase tracking-widest mb-2">Lulus Seleksi</p>
            <h3 class="text-4xl font-black text-green-500 mb-2">{{ number_format($lulusSeleksi) }}</h3> 
        </div>
        <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 flex flex-col justify-center text-center hover:shadow-md transition-shadow">
            <p class="text-[11px] font-extrabold text-gray-400 uppercase tracking-widest mb-2">Pembayaran Belum</p>
            <h3 class="text-4xl font-black text-gray-300 mb-2">{{ number_format($pembayaranBelum) }}</h3> 
        </div>
    </div>

    <div class="bg-white rounded-[2rem] shadow-sm border border-gray-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left min-w-[750px]">
                <thead class="bg-gray-50/50 text-[11px] font-black text-brand-gray uppercase tracking-widest border-b border-gray-100">
                    <tr>
                        <th class="px-5 py-4">No</th>
                        <th class="px-4 py-4">Nama & Email</th>
                        <th class="px-4 py-4">No. WA</th>
                        <th class="px-4 py-4">Prodi Diterima / Pilihan</th>
                        <th class="px-4 py-4">Jalur</th>
                        <th class="px-4 py-4">Pembayaran</th>
                        <th class="px-4 py-4">Pendaftaran</th>
                        <th class="px-4 py-4">Kelulusan</th>
                        <th class="px-5 py-4 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50 text-[13px]">
                    @forelse($users as $index => $data)
                    @php
                        // ── Status Pembayaran ────────────────────────────────
                        $bayarClass = match($data->status_pembayaran) {
                            'Terverifikasi'      => 'bg-green-50 text-green-600',
                            'Menunggu Validasi'  => 'bg-amber-50 text-amber-500',
                            'Belum Bayar'        => 'bg-gray-100 text-gray-500',
                            default              => 'bg-gray-50 text-gray-400',
                        };

                        // ── Status Pendaftaran ───────────────────────────────
                        $daftarClass = match($data->status_pendaftaran) {
                            'Selesai'              => 'bg-emerald-50 text-emerald-600',
                            'menunggu verifikasi'  => 'bg-blue-50 text-blue-500',
                            'Revisi'               => 'bg-red-50 text-red-500',
                            'Draft'                => 'bg-gray-100 text-gray-400',
                            default                => 'bg-gray-50 text-gray-400',
                        };
                        $daftarLabel = match($data->status_pendaftaran) {
                            'menunggu verifikasi'  => 'Menunggu',
                            default                => $data->status_pendaftaran ?? 'Draft',
                        };

                        // ── Status Kelulusan ─────────────────────────────────
                        $lulusClass = match($data->status_kelulusan ?? '') {
                            'Lulus Pilihan 1', 'Lulus Pilihan 2' => 'bg-emerald-50 text-emerald-600',
                            'Tidak Lulus'                         => 'bg-red-50 text-red-500',
                            default                               => 'bg-gray-100 text-gray-400',
                        };
                        $lulusLabel = match($data->status_kelulusan ?? '') {
                            'Lulus Pilihan 1' => 'Lulus P1',
                            'Lulus Pilihan 2' => 'Lulus P2',
                            'Tidak Lulus'     => 'Tidak Lulus',
                            default           => 'Belum',
                        };

                        // ── Prodi: jika sudah lulus tampilkan prodi diterima ─
                        $sudahLulus = in_array($data->status_kelulusan, ['Lulus Pilihan 1', 'Lulus Pilihan 2']);
                        $prodiDiterima = $sudahLulus
                            ? ($data->status_kelulusan === 'Lulus Pilihan 1' ? $data->pilihan_jurusan_1 : $data->pilihan_jurusan_2)
                            : null;

                        // ── NIM ──────────────────────────────────────────────
                        $sudahJadiMahasiswa = !empty($data->nim) && strtolower($data->status_daftar_ulang ?? '') === 'selesai';
                    @endphp
                    <tr class="hover:bg-gray-50/50 transition-colors group">
                        {{-- No --}}
                        <td class="px-5 py-4 text-gray-400 font-bold text-[12px]">{{ $index + 1 }}</td>

                        {{-- Nama & Email & NIM --}}
                        <td class="px-4 py-4">
                            <div class="flex flex-col gap-0.5">
                                <span class="font-bold text-brand-dark text-[13px] leading-snug">{{ $data->nama_lengkap }}</span>
                                <span class="text-gray-400 text-[11px]">{{ $data->email ?? '-' }}</span>
                                @if($sudahJadiMahasiswa)
                                <span class="text-[10px] font-black text-emerald-600">NIM: {{ $data->nim }}</span>
                                @endif
                            </div>
                        </td>

                        {{-- No WA --}}
                        <td class="px-4 py-4 text-gray-500 font-medium text-[12px] whitespace-nowrap">{{ $data->no_whatsapp ?? '-' }}</td>

                        {{-- Prodi: diterima jika lulus, pilihan 1&2 jika belum --}}
                        <td class="px-4 py-4">
                            @if($sudahLulus && $prodiDiterima)
                                <span class="px-2.5 py-1 bg-emerald-50 text-emerald-700 rounded-lg text-[11px] font-bold block leading-snug">
                                    {{ $prodiDiterima }}
                                </span>
                                <span class="text-[10px] text-gray-400 mt-0.5 block">{{ str_replace('Lulus ', '', $data->status_kelulusan) }}</span>
                            @else
                                <span class="px-2.5 py-1 bg-blue-50 text-blue-600 rounded-lg text-[11px] font-bold block leading-snug">{{ $data->pilihan_jurusan_1 ?? '-' }}</span>
                                @if($data->pilihan_jurusan_2)
                                <span class="px-2.5 py-1 bg-purple-50 text-purple-600 rounded-lg text-[11px] font-bold block leading-snug mt-1">{{ $data->pilihan_jurusan_2 }}</span>
                                @endif
                            @endif
                        </td>

                        {{-- Jalur --}}
                        <td class="px-4 py-4 font-medium text-gray-500 text-[12px] whitespace-nowrap">{{ $data->jalur_pendaftaran ?? '-' }}</td>

                        {{-- Status Pembayaran --}}
                        <td class="px-4 py-4">
                            <span class="inline-flex px-2.5 py-1 {{ $bayarClass }} rounded-full text-[10px] font-black uppercase tracking-wide whitespace-nowrap">
                                {{ $data->status_pembayaran ?? 'Belum Bayar' }}
                            </span>
                        </td>

                        {{-- Status Pendaftaran --}}
                        <td class="px-4 py-4">
                            <span class="inline-flex px-2.5 py-1 {{ $daftarClass }} rounded-full text-[10px] font-black uppercase tracking-wide whitespace-nowrap">
                                {{ $daftarLabel }}
                            </span>
                        </td>

                        {{-- Status Kelulusan --}}
                        <td class="px-4 py-4">
                            <span class="inline-flex px-2.5 py-1 {{ $lulusClass }} rounded-full text-[10px] font-black uppercase tracking-wide whitespace-nowrap">
                                {{ $lulusLabel }}
                            </span>
                        </td>

                        {{-- Aksi --}}
                        <td class="px-5 py-4 text-center">
                            <div class="flex items-center justify-center gap-1.5 opacity-0 group-hover:opacity-100 transition-opacity">
                                <button class="w-8 h-8 rounded-lg bg-gray-100 flex items-center justify-center text-gray-500 hover:bg-brand-blue hover:text-white transition-colors" title="Lihat Detail">
                                    <i data-feather="eye" class="w-3.5 h-3.5"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="9" class="px-5 py-16 text-center">
                            <div class="flex flex-col items-center gap-2 text-gray-400">
                                <i data-feather="users" class="w-10 h-10"></i>
                                <p class="font-bold text-sm">Belum ada data pendaftar</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

@endsection