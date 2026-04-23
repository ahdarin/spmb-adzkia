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
                <option>September 2023</option>
                <option>Oktober 2023</option>
            </select>
        </div>

        <div class="flex-1 min-w-[150px]">
            <select class="w-full border border-gray-200 rounded-xl px-4 py-3 outline-none focus:border-brand-blue bg-gray-50 text-[13px] font-bold text-brand-dark cursor-pointer appearance-none">
                <option>Semua Prodi</option>
                <option>Informatika</option>
                <option>Arsitektur</option>
                <option>Manajemen Bisnis</option>
            </select>
        </div>

        <div class="flex-1 min-w-[150px]">
            <select class="w-full border border-gray-200 rounded-xl px-4 py-3 outline-none focus:border-brand-blue bg-gray-50 text-[13px] font-bold text-brand-dark cursor-pointer appearance-none">
                <option>Semua Jalur</option>
                <option>Prestasi</option>
                <option>Reguler Pagi</option>
                <option>Mandiri</option>
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
                    
                    <tr class="hover:bg-gray-50/50 transition-colors">
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-4">
                                <div class="w-10 h-10 rounded-xl bg-brand-dark text-white flex items-center justify-center font-black text-[12px] shadow-sm">FA</div>
                                <div class="flex flex-col">
                                    <span class="font-bold text-brand-dark text-[14px]">Farhan Adi Pratama</span>
                                    <span class="text-gray-400 text-[11px] font-extrabold tracking-wider">REG-2024-012</span>
                                </div>
                            </div>
                        </td>
                        <td class="px-4 py-4">
                            <span class="px-4 py-1.5 bg-brand-blue-light text-brand-blue rounded-full text-[11px] font-extrabold tracking-wide">Informatika</span>
                        </td>
                        <td class="px-4 py-4">
                            <span class="px-4 py-1.5 bg-gray-100 text-gray-600 rounded-full text-[11px] font-extrabold tracking-wide">Prestasi</span>
                        </td>
                        <td class="px-4 py-4">
                            <span class="inline-flex items-center gap-1.5 text-amber-500 text-[11px] font-black uppercase tracking-wider">
                                <div class="w-2 h-2 rounded-full bg-amber-500 animate-pulse"></div> TIDAK LENGKAP
                            </span>
                        </td>
                        <td class="px-4 py-4">
                            <div class="flex flex-col">
                                <span class="font-bold text-gray-600">12 Sep 2023</span>
                                <span class="text-gray-400 text-[11px]">14:30 WIB</span>
                            </div>
                        </td>
                        <td class="px-6 py-4 text-center">
                            <button @click="bukaModal('Farhan Adi Pratama', 'REG-2024-012', 'Informatika')" class="px-5 py-2 bg-brand-dark text-white rounded-lg font-bold text-[11px] hover:bg-brand-blue transition-colors shadow-sm">
                                Lihat Detail
                            </button>
                        </td>
                    </tr>

                    <tr class="hover:bg-gray-50/50 transition-colors opacity-70">
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-4">
                                <div class="w-10 h-10 rounded-xl bg-teal-800 text-teal-100 flex items-center justify-center font-black text-[12px] shadow-sm">SA</div>
                                <div class="flex flex-col">
                                    <span class="font-bold text-brand-dark text-[14px]">Siti Aminah Zahra</span>
                                    <span class="text-gray-400 text-[11px] font-extrabold tracking-wider">REG-2024-034</span>
                                </div>
                            </div>
                        </td>
                        <td class="px-4 py-4">
                            <span class="px-4 py-1.5 bg-gray-100 text-gray-600 rounded-full text-[11px] font-extrabold tracking-wide">Arsitektur</span>
                        </td>
                        <td class="px-4 py-4">
                            <span class="px-4 py-1.5 bg-gray-100 text-gray-600 rounded-full text-[11px] font-extrabold tracking-wide">Reguler Pagi</span>
                        </td>
                        <td class="px-4 py-4">
                            <span class="inline-flex items-center gap-1.5 text-green-500 text-[11px] font-black uppercase tracking-wider">
                                <div class="w-2 h-2 rounded-full bg-green-500"></div> TERVERIFIKASI
                            </span>
                        </td>
                        <td class="px-4 py-4">
                            <div class="flex flex-col">
                                <span class="font-bold text-gray-600">11 Sep 2023</span>
                                <span class="text-gray-400 text-[11px]">09:15 WIB</span>
                            </div>
                        </td>
                        <td class="px-6 py-4 flex justify-center">
                            <button class="w-8 h-8 rounded-lg bg-gray-100 flex items-center justify-center text-gray-500 hover:bg-gray-200 transition-colors">
                                <i data-feather="more-horizontal" class="w-4 h-4"></i>
                            </button>
                        </td>
                    </tr>

                    <tr class="hover:bg-gray-50/50 transition-colors">
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-4">
                                <div class="w-10 h-10 rounded-xl bg-red-800 text-red-100 flex items-center justify-center font-black text-[12px] shadow-sm">BI</div>
                                <div class="flex flex-col">
                                    <span class="font-bold text-brand-dark text-[14px]">Budi Irawadi</span>
                                    <span class="text-gray-400 text-[11px] font-extrabold tracking-wider">REG-2024-055</span>
                                </div>
                            </div>
                        </td>
                        <td class="px-4 py-4">
                            <span class="px-4 py-1.5 bg-gray-100 text-gray-600 rounded-full text-[11px] font-extrabold tracking-wide">Manajemen Bisnis</span>
                        </td>
                        <td class="px-4 py-4">
                            <span class="px-4 py-1.5 bg-gray-100 text-gray-600 rounded-full text-[11px] font-extrabold tracking-wide">Mandiri</span>
                        </td>
                        <td class="px-4 py-4">
                            <span class="inline-flex items-center gap-1.5 text-red-500 text-[11px] font-black uppercase tracking-wider">
                                <div class="w-2 h-2 rounded-full bg-red-500"></div> BELUM UNGGAH
                            </span>
                        </td>
                        <td class="px-4 py-4">
                            <div class="flex flex-col">
                                <span class="font-bold text-gray-600">10 Sep 2023</span>
                                <span class="text-gray-400 text-[11px]">16:20 WIB</span>
                            </div>
                        </td>
                        <td class="px-6 py-4 flex justify-center">
                            <button class="w-8 h-8 rounded-lg bg-gray-100 flex items-center justify-center text-gray-500 hover:bg-gray-200 transition-colors">
                                <i data-feather="more-horizontal" class="w-4 h-4"></i>
                            </button>
                        </td>
                    </tr>

                </tbody>
            </table>
        </div>
    </div>

    <div x-show="modalOpen" class="fixed inset-0 z-50 flex items-center justify-center px-4" style="display: none;">
        <div x-show="modalOpen" x-transition.opacity @click="modalOpen = false" class="absolute inset-0 bg-brand-dark/60 backdrop-blur-sm cursor-pointer"></div>
        
        <div x-show="modalOpen" 
             x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 scale-95 translate-y-4" x-transition:enter-end="opacity-100 scale-100 translate-y-0" 
             x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100 scale-100 translate-y-0" x-transition:leave-end="opacity-0 scale-95 translate-y-4" 
             class="bg-white w-full max-w-3xl rounded-[2rem] shadow-2xl relative z-10 overflow-hidden flex flex-col max-h-[90vh]">
            
            <div class="p-6 border-b border-gray-100 flex justify-between items-center bg-gray-50/50">
                <div>
                    <h2 class="text-xl font-extrabold text-brand-dark tracking-tight">Verifikasi Berkas Daftar Ulang</h2>
                    <p class="text-[12px] font-bold text-gray-400 mt-1 uppercase tracking-widest">
                        <span x-text="dataSiswa.nama"></span> • <span x-text="dataSiswa.id"></span>
                    </p>
                </div>
                <button @click="modalOpen = false" class="p-2 bg-white border border-gray-200 hover:bg-gray-100 rounded-full transition-colors">
                    <i data-feather="x" class="w-4 h-4 text-gray-500"></i>
                </button>
            </div>

            <div class="p-8 overflow-y-auto custom-scrollbar flex-grow space-y-6">
                
                <div class="flex items-center gap-3 p-4 bg-amber-50 rounded-2xl border border-amber-100 mb-2">
                    <i data-feather="alert-triangle" class="w-5 h-5 text-amber-600 shrink-0"></i>
                    <p class="text-[12px] text-amber-800 font-bold leading-relaxed">
                        Pendaftar ini berstatus "TIDAK LENGKAP". Silakan periksa dokumen mana yang bermasalah dan kirimkan permintaan revisi.
                    </p>
                </div>

                <div class="flex items-center justify-between p-4 bg-white border border-gray-200 rounded-2xl hover:border-brand-blue transition-colors group">
                    <div class="flex items-center gap-4">
                        <div class="w-10 h-10 rounded-full bg-green-50 text-green-600 flex items-center justify-center">
                            <i data-feather="check" class="w-5 h-5"></i>
                        </div>
                        <div>
                            <p class="text-[14px] font-extrabold text-brand-dark">Scan Ijazah / SKL Asli</p>
                            <p class="text-[12px] font-medium text-gray-400">ijazah_farhan_final.pdf (2.4 MB)</p>
                        </div>
                    </div>
                    <button class="px-4 py-2 bg-gray-50 text-brand-dark rounded-lg font-bold text-[11px] hover:bg-gray-100 transition-colors">Lihat File</button>
                </div>

                <div class="flex items-center justify-between p-4 bg-white border border-gray-200 rounded-2xl hover:border-brand-blue transition-colors group">
                    <div class="flex items-center gap-4">
                        <div class="w-10 h-10 rounded-full bg-green-50 text-green-600 flex items-center justify-center">
                            <i data-feather="check" class="w-5 h-5"></i>
                        </div>
                        <div>
                            <p class="text-[14px] font-extrabold text-brand-dark">Pas Foto Berwarna (4x6)</p>
                            <p class="text-[12px] font-medium text-gray-400">foto_formal.jpg (1.1 MB)</p>
                        </div>
                    </div>
                    <button class="px-4 py-2 bg-gray-50 text-brand-dark rounded-lg font-bold text-[11px] hover:bg-gray-100 transition-colors">Lihat File</button>
                </div>

                <div class="flex items-center justify-between p-4 bg-red-50 border border-red-200 rounded-2xl group relative overflow-hidden">
                    <div class="absolute left-0 top-0 bottom-0 w-1 bg-red-500"></div>
                    <div class="flex items-center gap-4 pl-2">
                        <div class="w-10 h-10 rounded-full bg-white text-red-500 flex items-center justify-center shadow-sm">
                            <i data-feather="x" class="w-5 h-5"></i>
                        </div>
                        <div>
                            <p class="text-[14px] font-extrabold text-red-900">Surat Keterangan Bebas Narkoba</p>
                            <p class="text-[12px] font-medium text-red-600">Dokumen buram dan terpotong</p>
                        </div>
                    </div>
                    <div class="flex gap-2">
                        <button class="px-4 py-2 bg-white text-brand-dark rounded-lg font-bold text-[11px] hover:bg-gray-50 shadow-sm">Lihat File</button>
                    </div>
                </div>

                <div class="mt-4">
                    <label class="block text-[11px] font-extrabold text-gray-500 uppercase tracking-widest mb-2">Pesan Revisi ke Pendaftar</label>
                    <textarea placeholder="Contoh: Mohon scan ulang Surat Keterangan Bebas Narkoba karena hasil scan sebelumnya buram dan tidak bisa dibaca." class="w-full p-4 bg-gray-50 border border-gray-200 rounded-xl text-[13px] font-medium focus:ring-2 focus:ring-brand-blue outline-none resize-none h-24">Mohon scan ulang Surat Keterangan Bebas Narkoba karena hasil scan sebelumnya terpotong di bagian tanda tangan dokter.</textarea>
                </div>

            </div>

            <div class="p-6 border-t border-gray-100 flex justify-between bg-gray-50/50">
                <button @click="mintaRevisi()" class="px-6 py-3 border border-amber-200 text-amber-700 bg-amber-50 hover:bg-amber-100 rounded-xl font-bold text-[13px] transition-colors flex items-center gap-2">
                    <i data-feather="edit-2" class="w-4 h-4"></i> Minta Perbaikan Berkas
                </button>
                <div class="flex gap-3">
                    <button @click="modalOpen = false" class="px-6 py-3 border border-gray-200 text-gray-600 bg-white hover:bg-gray-50 rounded-xl font-bold text-[13px] transition-colors">
                        Tutup
                    </button>
                    <button @click="verifikasiFinal()" class="px-6 py-3 bg-brand-dark text-white hover:bg-brand-blue rounded-xl font-bold text-[13px] transition-colors shadow-lg flex items-center gap-2">
                        <i data-feather="check-square" class="w-4 h-4"></i> Verifikasi Paksa (Override)
                    </button>
                </div>
            </div>
        </div>
    </div>

</div>

<script>
    function validasiDaftarUlang() {
        return {
            modalOpen: false,
            dataSiswa: { nama: '', id: '', prodi: '' },
            
            bukaModal(nama, id, prodi) {
                this.dataSiswa = { nama, id, prodi };
                this.modalOpen = true;
                setTimeout(() => feather.replace(), 50);
            },

            mintaRevisi() {
                alert(`Pesan revisi berkas telah dikirim ke email ${this.dataSiswa.nama}. Status tetap TIDAK LENGKAP.`);
                this.modalOpen = false;
            },

            verifikasiFinal() {
                let confirmOverride = confirm(`Peringatan! Anda melakukan Verifikasi Paksa untuk ${this.dataSiswa.nama} meskipun ada dokumen yang bermasalah. Lanjutkan?`);
                if(confirmOverride) {
                    this.modalOpen = false;
                }
            }
        }
    }
</script>
@endsection