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

    <div class="bg-white p-4 rounded-2xl shadow-sm border border-gray-100 mb-6 flex flex-wrap items-center gap-4">
        <div class="flex items-center gap-3">
            <span class="text-[12px] font-extrabold text-gray-400 uppercase tracking-widest">Jalur:</span>
            <select class="border border-gray-200 rounded-lg px-3 py-2 outline-none focus:border-brand-blue bg-gray-50 text-[13px] font-bold text-brand-dark cursor-pointer min-w-[150px]">
                <option>Semua Jalur</option>
                <option>Mandiri Reguler</option>
                <option>Prestasi Akademik</option>
            </select>
        </div>
        
        <div class="flex items-center gap-3">
            <span class="text-[12px] font-extrabold text-gray-400 uppercase tracking-widest">Status:</span>
            <select class="border border-gray-200 rounded-lg px-3 py-2 outline-none focus:border-brand-blue bg-gray-50 text-[13px] font-bold text-brand-dark cursor-pointer min-w-[180px]">
                <option>Menunggu Validasi</option>
                <option>Terverifikasi</option>
                <option>Ditolak</option>
            </select>
        </div>

        <div class="relative flex-grow max-w-md ml-auto flex items-center gap-4">
            <div class="relative w-full">
                <i data-feather="search" class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400"></i>
                <input type="text" placeholder="Cari No. Pendaftaran atau Nama..." class="w-full pl-10 pr-4 py-2 border border-gray-200 rounded-lg text-[13px] font-medium focus:ring-2 focus:ring-brand-blue outline-none transition-all placeholder-gray-400">
            </div>
            <button class="text-[13px] font-extrabold text-brand-blue hover:text-brand-dark transition-colors whitespace-nowrap flex items-center gap-1.5">
                <i data-feather="refresh-ccw" class="w-3.5 h-3.5"></i> Reset
            </button>
        </div>
    </div>

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
                    
                    <tr class="hover:bg-gray-50/50 transition-colors">
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-full bg-blue-100 text-brand-blue flex items-center justify-center font-black text-[12px]">BS</div>
                                <div class="flex flex-col">
                                    <span class="font-bold text-brand-dark text-[14px]">Budi Santoso</span>
                                    <span class="text-gray-400 text-[11px] font-extrabold tracking-wider">REG-2024-001</span>
                                </div>
                            </div>
                        </td>
                        <td class="px-4 py-4 font-bold text-gray-600">Teknik Industri</td>
                        <td class="px-4 py-4 font-black text-brand-dark">Rp 1.500.000</td>
                        <td class="px-4 py-4">
                            <div class="flex flex-col">
                                <span class="font-bold text-gray-700">Bank Mandiri</span>
                                <span class="text-gray-400 text-[11px]">12 Jun 2024</span>
                            </div>
                        </td>
                        <td class="px-4 py-4">
                            <span class="inline-flex items-center gap-1.5 px-3 py-1 bg-amber-50 text-amber-600 rounded-full text-[10px] font-black uppercase tracking-wider">
                                <div class="w-1.5 h-1.5 rounded-full bg-amber-500 animate-pulse"></div> Menunggu
                            </span>
                        </td>
                        <td class="px-6 py-4 text-center">
                            <button @click="bukaModal('Budi Santoso', 'REG-2024-001', 'Teknik Industri', 'Bank Mandiri', 'Rp 1.500.000', '12 Jun 2024 14:30')" class="px-4 py-2 bg-brand-dark text-white rounded-lg font-bold text-[11px] hover:bg-brand-blue transition-colors shadow-sm">
                                Validasi
                            </button>
                        </td>
                    </tr>

                    <tr class="hover:bg-gray-50/50 transition-colors">
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-full bg-indigo-100 text-indigo-600 flex items-center justify-center font-black text-[12px]">AS</div>
                                <div class="flex flex-col">
                                    <span class="font-bold text-brand-dark text-[14px]">Aditya Saputra</span>
                                    <span class="text-gray-400 text-[11px] font-extrabold tracking-wider">REG-2024-089</span>
                                </div>
                            </div>
                        </td>
                        <td class="px-4 py-4 font-bold text-gray-600">Informatika</td>
                        <td class="px-4 py-4 font-black text-brand-dark">Rp 1.500.000</td>
                        <td class="px-4 py-4">
                            <div class="flex flex-col">
                                <span class="font-bold text-gray-700">Bank BSI</span>
                                <span class="text-gray-400 text-[11px]">12 Jun 2024</span>
                            </div>
                        </td>
                        <td class="px-4 py-4">
                            <span class="inline-flex items-center gap-1.5 px-3 py-1 bg-amber-50 text-amber-600 rounded-full text-[10px] font-black uppercase tracking-wider">
                                <div class="w-1.5 h-1.5 rounded-full bg-amber-500 animate-pulse"></div> Menunggu
                            </span>
                        </td>
                        <td class="px-6 py-4 text-center">
                            <button @click="bukaModal('Aditya Saputra', 'REG-2024-089', 'Informatika', 'Bank BSI', 'Rp 1.500.000', '12 Jun 2024 09:15')" class="px-4 py-2 bg-brand-dark text-white rounded-lg font-bold text-[11px] hover:bg-brand-blue transition-colors shadow-sm">
                                Validasi
                            </button>
                        </td>
                    </tr>

                    <tr class="hover:bg-gray-50/50 transition-colors opacity-70">
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3">
                                <img src="https://images.unsplash.com/photo-1438761681033-6461ffad8d80?w=100&h=100&fit=crop" class="w-10 h-10 rounded-full object-cover">
                                <div class="flex flex-col">
                                    <span class="font-bold text-brand-dark text-[14px]">Siti Aminah</span>
                                    <span class="text-gray-400 text-[11px] font-extrabold tracking-wider">REG-2024-112</span>
                                </div>
                            </div>
                        </td>
                        <td class="px-4 py-4 font-bold text-gray-600">Arsitektur</td>
                        <td class="px-4 py-4 font-black text-brand-dark">Rp 1.500.000</td>
                        <td class="px-4 py-4">
                            <div class="flex flex-col">
                                <span class="font-bold text-gray-700">Bank Nagari</span>
                                <span class="text-gray-400 text-[11px]">10 Jun 2024</span>
                            </div>
                        </td>
                        <td class="px-4 py-4">
                            <span class="inline-flex items-center gap-1.5 px-3 py-1 bg-green-50 text-green-600 rounded-full text-[10px] font-black uppercase tracking-wider">
                                <i data-feather="check" class="w-3 h-3"></i> Terverifikasi
                            </span>
                        </td>
                        <td class="px-6 py-4 flex justify-center">
                            <button class="w-8 h-8 rounded-lg bg-gray-100 flex items-center justify-center text-gray-500 hover:bg-gray-200 transition-colors">
                                <i data-feather="eye" class="w-4 h-4"></i>
                            </button>
                        </td>
                    </tr>

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
        
        <div x-show="modalOpen" 
             x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 scale-95 translate-y-4" x-transition:enter-end="opacity-100 scale-100 translate-y-0" 
             x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100 scale-100 translate-y-0" x-transition:leave-end="opacity-0 scale-95 translate-y-4" 
             class="bg-white w-full max-w-2xl rounded-[2rem] shadow-2xl relative z-10 overflow-hidden flex flex-col max-h-[90vh]">
            
            <div class="p-6 border-b border-gray-100 flex justify-between items-center bg-gray-50/50">
                <div>
                    <h2 class="text-xl font-extrabold text-brand-dark tracking-tight">Detail Validasi Pembayaran</h2>
                    <p class="text-[12px] font-bold text-gray-400 mt-1 uppercase tracking-widest" x-text="dataPendaftar.id"></p>
                </div>
                <button @click="modalOpen = false" class="p-2 bg-white border border-gray-200 hover:bg-gray-100 rounded-full transition-colors">
                    <i data-feather="x" class="w-4 h-4 text-gray-500"></i>
                </button>
            </div>

            <div class="p-8 overflow-y-auto custom-scrollbar flex-grow">
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
                        <div class="p-4 bg-gray-50 rounded-xl border border-gray-100 space-y-3">
                            <div class="flex justify-between items-center">
                                <span class="text-[12px] font-bold text-gray-500">Nominal Transfer</span>
                                <span class="text-[14px] font-black text-brand-dark" x-text="dataPendaftar.nominal"></span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="text-[12px] font-bold text-gray-500">Bank Asal</span>
                                <span class="text-[13px] font-bold text-brand-dark" x-text="dataPendaftar.bank"></span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="text-[12px] font-bold text-gray-500">Waktu Upload</span>
                                <span class="text-[12px] font-bold text-gray-500" x-text="dataPendaftar.tanggal"></span>
                            </div>
                        </div>
                    </div>

                    <div>
                        <p class="text-[10px] font-extrabold text-gray-400 uppercase tracking-widest mb-2 flex justify-between items-center">
                            Bukti Transfer 
                            <a href="#" class="text-brand-blue hover:underline normal-case tracking-normal"><i data-feather="external-link" class="w-3 h-3 inline"></i> Buka Penuh</a>
                        </p>
                        <div class="w-full h-64 bg-gray-100 rounded-2xl border-2 border-dashed border-gray-300 flex items-center justify-center p-2 relative group overflow-hidden cursor-pointer">
                            <img src="https://images.unsplash.com/photo-1620241608701-94efadcf58cb?w=400&h=600&fit=crop" class="w-full h-full object-cover rounded-xl opacity-80 group-hover:opacity-100 transition-opacity">
                            <div class="absolute inset-0 bg-brand-dark/20 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity">
                                <i data-feather="zoom-in" class="w-8 h-8 text-white"></i>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="mt-8">
                    <label class="block text-[11px] font-extrabold text-gray-500 uppercase tracking-widest mb-2">Catatan Validasi (Opsional)</label>
                    <textarea placeholder="Tambahkan catatan jika pembayaran ditolak (misal: nominal kurang, bukti buram)..." class="w-full p-4 bg-gray-50 border border-gray-200 rounded-xl text-[13px] font-medium focus:ring-2 focus:ring-brand-blue outline-none resize-none h-24"></textarea>
                </div>
            </div>

            <div class="p-6 border-t border-gray-100 flex items-center justify-between bg-gray-50/50">
                <button @click="tolakValidasi()" class="px-6 py-3 border border-red-200 text-red-600 bg-red-50 hover:bg-red-100 rounded-xl font-bold text-[13px] transition-colors flex items-center gap-2">
                    <i data-feather="x-circle" class="w-4 h-4"></i> Tolak Pembayaran
                </button>
                <div class="flex gap-3">
                    <button @click="modalOpen = false" class="px-6 py-3 border border-gray-200 text-gray-600 bg-white hover:bg-gray-50 rounded-xl font-bold text-[13px] transition-colors">
                        Batal
                    </button>
                    <button @click="setujuiValidasi()" class="px-6 py-3 bg-brand-blue text-white hover:bg-opacity-90 rounded-xl font-bold text-[13px] transition-colors shadow-lg shadow-brand-blue/20 flex items-center gap-2">
                        <i data-feather="check-circle" class="w-4 h-4"></i> Setujui & Verifikasi
                    </button>
                </div>
            </div>
        </div>
    </div>

</div>

<script>
    function validasiPembayaran() {
        return {
            modalOpen: false,
            dataPendaftar: {
                nama: '', id: '', prodi: '', bank: '', nominal: '', tanggal: ''
            },
            
            bukaModal(nama, id, prodi, bank, nominal, tanggal) {
                // Masukkan data dari baris yang diklik ke state Modal
                this.dataPendaftar = { nama, id, prodi, bank, nominal, tanggal };
                this.modalOpen = true;
                
                // Render ulang ikon feather di dalam modal
                setTimeout(() => feather.replace(), 50);
            },

            setujuiValidasi() {
                alert(`Pembayaran atas nama ${this.dataPendaftar.nama} berhasil DISETUJUI.`);
                this.modalOpen = false;
                // Di sini tim Backend akan menambahkan script AJAX untuk update database
            },

            tolakValidasi() {
                let konfirmasi = confirm(`Yakin ingin MENOLAK pembayaran ${this.dataPendaftar.nama}?`);
                if (konfirmasi) {
                    this.modalOpen = false;
                }
            }
        }
    }
</script>
@endsection