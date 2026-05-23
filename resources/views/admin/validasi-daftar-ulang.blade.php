@extends('layouts.admin')

@section('admin-content')

<div x-data="validasiDaftarUlang()">

    <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-8 gap-4">
        <div>
            <h1 class="text-3xl font-extrabold text-[#0B1C39] tracking-tight mb-2">Validasi Daftar Ulang</h1>
            <p class="text-brand-gray text-[14px] font-medium">
                Verifikasi data akhir pendaftar untuk tahun akademik 2024/2025.
            </p>
        </div>
        <div class="flex gap-2">
            <button class="px-5 py-2.5 bg-white border border-gray-200 text-brand-dark rounded-xl font-bold text-[13px] hover:bg-gray-50 transition-all shadow-sm">
                Tahun Lalu
            </button>
            <button class="px-5 py-2.5 bg-brand-dark text-white rounded-xl font-bold text-[13px] hover:bg-opacity-90 transition-all shadow-sm">
                Tahun Ini
            </button>
        </div>
    </div>

    <div class="bg-white p-5 rounded-2xl shadow-sm border border-gray-100 mb-6 flex flex-wrap items-center gap-4">
        <div class="flex-1 min-w-[150px]">
            <select class="w-full border border-gray-200 rounded-xl px-4 py-3 outline-none focus:border-brand-blue bg-gray-50 text-[13px] font-bold text-brand-dark cursor-pointer appearance-none">
                <option>Semua Bulan</option>
            </select>
        </div>
        <div class="flex-1 min-w-[150px]">
            <select class="w-full border border-gray-200 rounded-xl px-4 py-3 outline-none focus:border-brand-blue bg-gray-50 text-[13px] font-bold text-brand-dark cursor-pointer appearance-none">
                <option>Semua Prodi</option>
            </select>
        </div>
        <div class="flex-1 min-w-[150px]">
            <select class="w-full border border-gray-200 rounded-xl px-4 py-3 outline-none focus:border-brand-blue bg-gray-50 text-[13px] font-bold text-brand-dark cursor-pointer appearance-none">
                <option>Semua Jalur</option>
            </select>
        </div>
        <button class="px-6 py-3 bg-brand-dark text-white rounded-xl font-extrabold text-[13px] hover:bg-brand-blue transition-colors flex items-center gap-2 shadow-md">
            <i data-feather="filter" class="w-4 h-4"></i> Terapkan Filter
        </button>
    </div>

    <div class="bg-white rounded-[2rem] shadow-sm border border-gray-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left whitespace-nowrap">
                <thead class="bg-gray-50/50 text-[11px] font-black text-brand-dark uppercase tracking-widest border-b border-gray-100">
                    <tr>
                        <th class="px-6 py-5">Nama & No. Daftar</th>
                        <th class="px-4 py-5">Program Studi</th>
                        <th class="px-4 py-5">Jalur</th>
                        <th class="px-4 py-5">Status</th>
                        <th class="px-4 py-5">Tanggal Submit</th>
                        <th class="px-6 py-5 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50 text-[13px]">
                    @forelse($pendaftarDaftarUlang as $data)
                    <tr class="hover:bg-gray-50/50 transition-colors">
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-4">
                                <div class="w-10 h-10 rounded-xl bg-brand-dark text-white flex items-center justify-center font-black text-[12px] shadow-sm">
                                    {{ strtoupper(substr($data->nama_lengkap ?? '??', 0, 2)) }}
                                </div>
                                <div class="flex flex-col">
                                    <span class="font-bold text-brand-dark text-[14px]">
                                        {{ $data->nama_lengkap ?? 'Nama Tidak Ditemukan' }}
                                    </span>
                                    <span class="text-gray-400 text-[11px] font-extrabold tracking-wider">{{ $data->no_pendaftaran }}</span>
                                </div>
                            </div>
                        </td>
                        <td class="px-4 py-4">
                            <span class="px-4 py-1.5 bg-brand-blue-light text-brand-blue rounded-full text-[11px] font-extrabold tracking-wide">{{ $data->pilihan_jurusan_1 ?? $data->program_studi }}</span>
                        </td>
                        <td class="px-4 py-4">
                            <span class="px-4 py-1.5 bg-gray-100 text-gray-600 rounded-full text-[11px] font-extrabold tracking-wide">{{ $data->jalur_pendaftaran ?? 'Mandiri' }}</span>
                        </td>
                        <td class="px-4 py-4">
                            @if($data->status_pembayaran == 'Terverifikasi' || $data->status == 'Terverifikasi')
                                <span class="inline-flex items-center gap-1.5 text-green-500 text-[11px] font-black uppercase tracking-wider">
                                    <div class="w-2 h-2 rounded-full bg-green-500"></div> TERVERIFIKASI
                                </span>
                            @else
                                <span class="inline-flex items-center gap-1.5 text-amber-500 text-[11px] font-black uppercase tracking-wider">
                                    <div class="w-2 h-2 rounded-full bg-amber-500 animate-pulse"></div> TIDAK LENGKAP
                                </span>
                            @endif
                        </td>
                        <td class="px-4 py-4">
                            <div class="flex flex-col">
                                <span class="font-bold text-gray-600">{{ $data->updated_at->format('d M Y') }}</span>
                                <span class="text-gray-400 text-[11px]">{{ $data->updated_at->format('H:i') }} WIB</span>
                            </div>
                        </td>
                        <td class="px-6 py-4 text-center">
                            <button @click="bukaModal(
                                '{{ $data->nama_lengkap ?? 'Nama Tidak Ditemukan' }}', 
                                '{{ $data->no_pendaftaran }}', 
                                '{{ $data->id }}', 
                                '{{ $data->pilihan_jurusan_1 ?? $data->program_studi }}', 
                                '{{ $data->status_pembayaran ?? $data->status }}',
                                '{{ asset('storage/' . $data->pas_foto) }}',
                                '{{ asset('storage/' . $data->scan_ktp) }}',
                                '{{ asset('storage/' . $data->ijazah_skl) }}'
                            )" class="px-5 py-2 bg-brand-dark text-white rounded-lg font-bold text-[11px] hover:bg-brand-blue transition-colors shadow-sm">
                                Lihat Detail
                            </button>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-6 py-10 text-center text-gray-500 font-bold">Belum ada data daftar ulang yang masuk.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- MODAL --}}
    <div x-show="modalOpen" class="fixed inset-0 z-50 flex items-center justify-center px-4" style="display: none;">
        <div x-show="modalOpen" x-transition.opacity @click="modalOpen = false" class="absolute inset-0 bg-brand-dark/60 backdrop-blur-sm cursor-pointer"></div>
        
        <div x-show="modalOpen" 
             x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 scale-95 translate-y-4" x-transition:enter-end="opacity-100 scale-100 translate-y-0" 
             class="bg-white w-full max-w-3xl rounded-[2rem] shadow-2xl relative z-10 overflow-hidden flex flex-col max-h-[90vh]">
            
            <div class="p-6 border-b border-gray-100 flex justify-between items-center bg-gray-50/50">
                <div>
                    <h2 class="text-xl font-extrabold text-brand-dark tracking-tight">Verifikasi Berkas Daftar Ulang</h2>
                    <p class="text-[12px] font-bold text-gray-400 mt-1 uppercase tracking-widest">
                        <span x-text="dataSiswa.nama"></span> • <span x-text="dataSiswa.noReg"></span>
                    </p>
                </div>
                <button @click="modalOpen = false" class="p-2 bg-white border border-gray-200 hover:bg-gray-100 rounded-full transition-colors">
                    <i data-feather="x" class="w-4 h-4 text-gray-500"></i>
                </button>
            </div>

            <div class="p-8 overflow-y-auto custom-scrollbar flex-grow space-y-6">
                
                <div class="flex items-center justify-between p-4 bg-white border border-gray-200 rounded-2xl hover:border-brand-blue transition-colors group">
                    <div class="flex items-center gap-4">
                        <div class="w-10 h-10 rounded-full bg-green-50 text-green-600 flex items-center justify-center"><i data-feather="file-text" class="w-5 h-5"></i></div>
                        <div><p class="text-[14px] font-extrabold text-brand-dark">Scan Ijazah / SKL</p></div>
                    </div>
                    <a :href="dataSiswa.ijazah_skl && dataSiswa.ijazah_skl.includes('http') ? dataSiswa.ijazah_skl : '/storage/' + dataSiswa.ijazah_skl" 
                        target="_blank" 
                        class="px-4 py-2 bg-gray-50 text-brand-dark rounded-lg font-bold text-[11px] hover:bg-gray-100 transition-colors">
                        Lihat File
                    </a>
                </div>

                <div class="flex items-center justify-between p-4 bg-white border border-gray-200 rounded-2xl hover:border-brand-blue transition-colors group">
                    <div class="flex items-center gap-4">
                        <div class="w-10 h-10 rounded-full bg-green-50 text-green-600 flex items-center justify-center"><i data-feather="image" class="w-5 h-5"></i></div>
                        <div><p class="text-[14px] font-extrabold text-brand-dark">Pas Foto 4x6</p></div>
                    </div>
                    <a :href="dataSiswa.pas_foto && dataSiswa.pas_foto.includes('http') ? dataSiswa.pas_foto : '/storage/' + dataSiswa.pas_foto" 
                        target="_blank" 
                        class="px-4 py-2 bg-gray-50 text-brand-dark rounded-lg font-bold text-[11px] hover:bg-gray-100 transition-colors">
                        Lihat File
                    </a>
                </div>

                <div class="flex items-center justify-between p-4 bg-white border border-gray-200 rounded-2xl hover:border-brand-blue transition-colors group">
                    <div class="flex items-center gap-4">
                        <div class="w-10 h-10 rounded-full bg-green-50 text-green-600 flex items-center justify-center"><i data-feather="credit-card" class="w-5 h-5"></i></div>
                        <div><p class="text-[14px] font-extrabold text-brand-dark">Scan KTP</p></div>
                    </div>
                    <a :href="dataSiswa.scan_ktp && dataSiswa.scan_ktp.includes('http') ? dataSiswa.scan_ktp : '/storage/' + dataSiswa.scan_ktp" 
                        target="_blank" 
                        class="px-4 py-2 bg-gray-50 text-brand-dark rounded-lg font-bold text-[11px] hover:bg-gray-100 transition-colors">
                        Lihat File
                    </a>
                </div>

                <div class="mt-4">
                    <label class="block text-[11px] font-extrabold text-gray-500 uppercase tracking-widest mb-2">Pesan Revisi ke Pendaftar</label>
                    <textarea id="pesanRevisi" placeholder="Tulis catatan revisi di sini..." class="w-full p-4 bg-gray-50 border border-gray-200 rounded-xl text-[13px] font-medium focus:ring-2 focus:ring-brand-blue outline-none resize-none h-24"></textarea>
                </div>
            </div>

            <div class="p-6 border-t border-gray-100 flex justify-between bg-gray-50/50">
                <button @click="mintaRevisi()" class="px-6 py-3 border border-amber-200 text-amber-700 bg-amber-50 hover:bg-amber-100 rounded-xl font-bold text-[13px] transition-colors flex items-center gap-2">
                    <i data-feather="edit-2" class="w-4 h-4"></i> Minta Perbaikan Berkas
                </button>
                <div class="flex gap-3">
                    <button @click="modalOpen = false" class="px-6 py-3 border border-gray-200 text-gray-600 bg-white hover:bg-gray-50 rounded-xl font-bold text-[13px] transition-colors">Tutup</button>
                    <form :action="`/admin/setujui-daftar-ulang/${dataSiswa.dbId}`" method="POST">
                        @csrf
                        <button type="submit" 
                                class="px-6 py-3 bg-brand-dark text-white hover:bg-brand-blue rounded-xl font-bold text-[13px] transition-colors shadow-lg flex items-center gap-2">
                            <i data-feather="check-square" class="w-4 h-4"></i> 
                            Verifikasi
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    function validasiDaftarUlang() {
        return {
            modalOpen: false,
            dataSiswa: { nama: '', noReg: '', dbId: '', prodi: '', status: '', foto: '', ktp: '', ijazah: '' },
            
            bukaModal(nama, noReg, dbId, prodi, status, foto, ktp, ijazah) {
                this.dataSiswa = { nama, noReg, dbId, prodi, status, foto, ktp, ijazah };
                this.modalOpen = true;
                setTimeout(() => feather.replace(), 50);
            },

            mintaRevisi() {
                const pesan = document.getElementById('pesanRevisi').value;
                if(!pesan) return alert('Harap isi pesan revisi terlebih dahulu!');
                alert(`Pesan revisi berkas telah dikirim ke ${this.dataSiswa.nama}.`);
                this.modalOpen = false;
            }
        }
    }
</script>
@endsection