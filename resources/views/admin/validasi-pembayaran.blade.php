@extends('layouts.admin')

@section('admin-content')

<div x-data="validasiPembayaran()">

    <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-8 gap-4">
        <div>
            <h1 class="text-3xl font-extrabold text-[#0B1C39] tracking-tight mb-2">Validasi Pembayaran</h1>
            <p class="text-brand-gray text-[14px] font-medium">
                Kelola dan verifikasi pembayaran pendaftar mahasiswa baru secara efisien.
            </p>
        </div>
        <div>
            <button class="flex items-center gap-2 px-5 py-2.5 bg-white border border-gray-200 text-brand-dark rounded-xl font-bold text-[13px] hover:bg-gray-50 transition-all shadow-sm">
                <i data-feather="download" class="w-4 h-4"></i> Export Data
            </button>
        </div>
    </div>

    <form action="{{ route('admin.pembayaran') }}" method="GET" class="bg-white p-4 rounded-2xl shadow-sm border border-gray-100 mb-6 flex flex-wrap items-center gap-4">
        <div class="flex items-center gap-3">
            <span class="text-[12px] font-extrabold text-gray-400 uppercase tracking-widest">Jalur:</span>
            <select name="jalur" onchange="this.form.submit()" class="border border-gray-200 rounded-lg px-3 py-2 outline-none focus:border-brand-blue bg-gray-50 text-[13px] font-bold text-brand-dark cursor-pointer min-w-[150px]">
                <option value="Semua Jalur" {{ request('jalur') == 'Semua Jalur' ? 'selected' : '' }}>Semua Jalur</option>
                <option value="Mandiri Reguler" {{ request('jalur') == 'Mandiri Reguler' ? 'selected' : '' }}>Mandiri Reguler</option>
                <option value="Prestasi Akademik" {{ request('jalur') == 'Prestasi Akademik' ? 'selected' : '' }}>Prestasi Akademik</option>
            </select>
        </div>
        
        <div class="flex items-center gap-3">
            <span class="text-[12px] font-extrabold text-gray-400 uppercase tracking-widest">Status:</span>
            <select name="status" onchange="this.form.submit()" class="border border-gray-200 rounded-lg px-3 py-2 outline-none focus:border-brand-blue bg-gray-50 text-[13px] font-bold text-brand-dark cursor-pointer min-w-[180px]">
                <option value="Belum Bayar" {{ request('status') == 'Belum Bayar' ? 'selected' : '' }}>Menunggu Validasi</option>
                <option value="Terverifikasi" {{ request('status') == 'Terverifikasi' ? 'selected' : '' }}>Terverifikasi</option>
                <option value="Ditolak" {{ request('status') == 'Ditolak' ? 'selected' : '' }}>Ditolak</option>
            </select>
        </div>

        <div class="relative flex-grow max-w-md ml-auto flex items-center gap-4">
            <div class="relative w-full">
                <i data-feather="search" class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400"></i>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari No. Pendaftaran atau Nama..." class="w-full pl-10 pr-4 py-2 border border-gray-200 rounded-lg text-[13px] font-medium focus:ring-2 focus:ring-brand-blue outline-none transition-all placeholder-gray-400">
            </div>
            <a href="{{ route('admin.pembayaran') }}" class="text-[13px] font-extrabold text-brand-blue hover:text-brand-dark transition-colors whitespace-nowrap flex items-center gap-1.5">
                <i data-feather="refresh-ccw" class="w-3.5 h-3.5"></i> Reset
            </a>
        </div>
    </form>

    <div class="bg-white rounded-[2rem] shadow-sm border border-gray-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left whitespace-nowrap">
                <thead class="bg-gray-50/50 text-[11px] font-black text-brand-dark uppercase tracking-widest border-b border-gray-100">
                    <tr>
                        <th class="px-6 py-5">Nama Pendaftar</th>
                        <th class="px-4 py-5">Program Studi</th>
                        <th class="px-4 py-5">Nominal</th>
                        <th class="px-4 py-5">Bank Asal</th>
                        <th class="px-4 py-5">Status</th>
                        <th class="px-6 py-5 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50 text-[13px]">
                    
                    @forelse($pendaftarPending as $data)
                    <tr class="hover:bg-gray-50/50 transition-colors">
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-full bg-blue-100 text-brand-blue flex items-center justify-center font-black text-[12px]">
                                    {{ strtoupper(substr($data->nama_lengkap ?? 'AA', 0, 2)) }}
                                </div>
                                <div class="flex flex-col">
                                    <span class="font-bold text-brand-dark text-[14px]">{{ $data->nama_lengkap }}</span>
                                    <span class="text-gray-400 text-[11px] font-extrabold tracking-wider">REG-2026-00{{ $data->id }}</span>
                                </div>
                            </div>
                        </td>
                        <td class="px-4 py-4 font-bold text-gray-600">{{ $data->pilihan_jurusan_1 }}</td>
                        <td class="px-4 py-4 font-black text-brand-dark">Rp {{ number_format($data->nominal_biaya ?? 0, 0, ',', '.') }}</td>
                        <td class="px-4 py-4">
                            <div class="flex flex-col">
                                <span class="font-bold text-gray-700">{{ $data->metode_pembayaran }}</span>
                                <span class="text-gray-400 text-[11px]">{{ $data->created_at->format('d M Y') }}</span>
                            </div>
                        </td>
                        <td class="px-4 py-4">
                            <span class="inline-flex items-center gap-1.5 px-3 py-1 {{ $data->status_pembayaran == 'Terverifikasi' ? 'bg-green-50 text-green-600' : 'bg-amber-50 text-amber-600' }} rounded-full text-[10px] font-black uppercase tracking-wider">
                                <div class="w-1.5 h-1.5 rounded-full {{ $data->status_pembayaran == 'Terverifikasi' ? 'bg-green-500' : 'bg-amber-500 animate-pulse' }}"></div> {{ $data->status_pembayaran }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-center">
                            <button @click="bukaModal(
                                        '{{ $data->nama_lengkap }}', 
                                        '{{ $data->id }}', 
                                        '{{ $data->pilihan_jurusan_1 }}', 
                                        '{{ $data->metode_pembayaran }}', 
                                        'Rp {{ number_format($data->nominal_biaya ?? 0, 0, ',', '.') }}', 
                                        '{{ $data->created_at->format('d M Y H:i') }}',
                                        '{{ $data->jalur_pendaftaran }}',
                                        '{{ $data->bukti_bayar ? asset('storage/' . $data->bukti_bayar) : '' }}'
                                    )" 
                                        class="px-4 py-2 bg-brand-dark text-white rounded-lg font-bold text-[11px] hover:bg-brand-blue transition-colors shadow-sm">
                                Validasi
                            </button>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-6 py-10 text-center text-gray-500 font-bold">Tidak ada antrian pembayaran.</td>
                    </tr>
                    @endforelse

                </tbody>
            </table>
        </div>
        
        <div class="p-6 border-t border-gray-100 flex justify-end gap-2">
            <button class="w-8 h-8 rounded-lg flex items-center justify-center text-gray-400 bg-gray-50 hover:bg-gray-100 transition-colors"><i data-feather="chevron-left" class="w-4 h-4"></i></button>
            <button class="w-8 h-8 rounded-lg bg-brand-dark text-white font-bold text-[13px] flex items-center justify-center">1</button>
            <button class="w-8 h-8 rounded-lg flex items-center justify-center text-gray-400 bg-gray-50 hover:bg-gray-100 transition-colors"><i data-feather="chevron-right" class="w-4 h-4"></i></button>
        </div>
    </div>

    <div x-show="modalOpen" class="fixed inset-0 z-50 flex items-center justify-center px-4" style="display: none;">
        <div x-show="modalOpen" x-transition.opacity @click="modalOpen = false" class="absolute inset-0 bg-brand-dark/60 backdrop-blur-sm cursor-pointer"></div>
        
        <div x-show="modalOpen" class="bg-white w-full max-w-2xl rounded-[2rem] shadow-2xl relative z-10 overflow-hidden flex flex-col max-h-[90vh]">
            <div class="p-6 border-b border-gray-100 flex justify-between items-center bg-gray-50/50">
                <div>
                    <h2 class="text-xl font-extrabold text-brand-dark tracking-tight">Detail Validasi Pembayaran</h2>
                    <p class="text-[12px] font-bold text-gray-400 mt-1 uppercase tracking-widest">REG-2026-00<span x-text="dataPendaftar.id"></span></p>
                </div>
                <button @click="modalOpen = false" class="p-2 bg-white border border-gray-200 hover:bg-gray-100 rounded-full transition-colors">
                    <i data-feather="x" class="w-4 h-4 text-gray-500"></i>
                </button>
            </div>

            <div class="p-8 overflow-y-auto flex-grow">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <div class="space-y-6">
                        <div>
                            <p class="text-[10px] font-extrabold text-gray-400 uppercase tracking-widest mb-1">Nama Lengkap</p>
                            <p class="text-[15px] font-bold text-brand-dark" x-text="dataPendaftar.nama"></p>
                        </div>
                        <div>
                            <p class="text-[10px] font-extrabold text-gray-400 uppercase tracking-widest mb-1">Program Studi Pilihan</p>
                            <span class="px-3 py-1 bg-brand-blue-light text-brand-blue rounded-lg text-[12px] font-bold inline-block" x-text="dataPendaftar.prodi"></span>
                        </div>
                        <div>
                            <p class="text-[10px] font-extrabold text-gray-400 uppercase tracking-widest mb-1">Jalur Pendaftaran</p>
                            <p class="text-[14px] font-bold text-gray-700" x-text="dataPendaftar.jalurLengkap"></p>
                        </div>
                        <div class="p-4 bg-gray-50 rounded-xl border border-gray-100 space-y-3">
                            <div class="flex justify-between items-center">
                                <span class="text-[12px] font-bold text-gray-500">Nominal Transfer</span>
                                <span class="text-[14px] font-black text-brand-dark" x-text="dataPendaftar.nominal"></span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="text-[12px] font-bold text-gray-500">Metode Pembayaran</span>
                                <span class="text-[13px] font-bold text-brand-dark" x-text="dataPendaftar.bank"></span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="text-[12px] font-bold text-gray-500">Waktu Upload</span>
                                <span class="text-[12px] font-bold text-gray-500" x-text="dataPendaftar.tanggal"></span>
                            </div>
                        </div>
                    </div>
                    <div>
                        <p class="text-[10px] font-extrabold text-gray-400 uppercase tracking-widest mb-2">Bukti Transfer</p>
                        <div class="w-full h-64 bg-gray-100 rounded-2xl border-2 border-dashed border-gray-300 flex items-center justify-center p-2">
                            <img :src="dataPendaftar.buktiUrl" class="w-full h-full object-contain rounded-xl" alt="Bukti Pembayaran">
                        </div>
                    </div>
                </div>
            </div>

            <div class="p-6 border-t border-gray-100 flex items-center justify-between bg-gray-50/50">
                <button @click="modalOpen = false" class="px-6 py-3 border border-red-200 text-red-600 bg-red-50 hover:bg-red-100 rounded-xl font-bold text-[13px] transition-colors">Tolak</button>
                <div class="flex gap-3">
                    <button @click="modalOpen = false" class="px-6 py-3 border border-gray-200 text-gray-600 bg-white hover:bg-gray-50 rounded-xl font-bold text-[13px]">Batal</button>
<form action="{{ route('admin.proses.validasi') }}" method="POST">
    @csrf
    <!-- Gunakan x-bind untuk mengisi ID pendaftar dari dataPendaftar yang sedang aktif di modal -->
    <input type="hidden" name="pendaftar_id" x-bind:value="dataPendaftar.id">
    
    <button type="submit" class="bg-green-600 text-white px-6 py-3 rounded-xl font-bold text-[13px] hover:bg-green-700 transition-colors">
        Setujui & Verifikasi
    </button>
</form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    function validasiPembayaran() {
    return {
        modalOpen: false,
        // Pastikan 'buktiUrl' ada di sini!
        dataPendaftar: { nama: '', id: '', prodi: '', bank: '', nominal: '', tanggal: '', jalurLengkap: '', buktiUrl: '' },
        
        bukaModal(nama, id, prodi, bank, nominal, tanggal, jalurLengkap, buktiUrl) {
    console.log("Path Gambar yang diterima:", buktiUrl); // Lihat ini di Console browser (F12)
    this.dataPendaftar = { nama, id, prodi, bank, nominal, tanggal, jalurLengkap, buktiUrl };
    this.modalOpen = true;
        }
    }
}
</script>
@endsection