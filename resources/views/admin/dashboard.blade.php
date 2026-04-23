@extends('layouts.admin')

@section('admin-content')
<div x-data="dashboardController()">
    
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-10 gap-4">
        <div>
            <h1 class="text-3xl font-extrabold text-brand-dark tracking-tight mb-2">
                Selamat Datang, Dika! <span class="text-2xl">👋</span>
            </h1>
            <p class="text-brand-gray text-[14px] font-medium">
                Berikut adalah ringkasan aktivitas penerimaan mahasiswa baru Adzkia.
            </p>
        </div>
        
        <div class="bg-white border border-gray-100 p-1.5 rounded-xl flex items-center shadow-sm">
            <template x-for="filter in ['Hari Ini', 'Minggu Ini', 'Bulan Ini']">
                <button @click="changeFilter(filter)"
                        :class="activeFilter === filter ? 'bg-brand-blue text-white shadow-md' : 'text-gray-500 hover:text-brand-dark hover:bg-gray-50'"
                        class="px-5 py-2.5 rounded-lg text-[12px] font-black tracking-wide transition-all"
                        x-text="filter">
                </button>
            </template>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        
        <div class="bg-white p-6 rounded-[2rem] border border-gray-100 shadow-sm hover:-translate-y-1 transition-transform group">
            <div class="flex justify-between items-start mb-4">
                <div class="w-12 h-12 rounded-2xl bg-blue-50 text-brand-blue flex items-center justify-center group-hover:scale-110 transition-transform">
                    <span class="flex items-center justify-center w-5 h-5"><i data-feather="users"></i></span>
                </div>
                <span class="flex items-center gap-1 text-[11px] font-extrabold px-2.5 py-1 rounded-full transition-colors"
                      :class="stats.pendaftarTrend >= 0 ? 'bg-green-50 text-green-600' : 'bg-red-50 text-red-600'">
                    
                    <span x-show="stats.pendaftarTrend >= 0" class="flex items-center justify-center w-3 h-3"><i data-feather="trending-up"></i></span>
                    <span x-show="stats.pendaftarTrend < 0" class="flex items-center justify-center w-3 h-3" x-cloak><i data-feather="trending-down"></i></span>
                    
                    <span x-text="Math.abs(stats.pendaftarTrend) + '%'"></span>
                </span>
            </div>
            <p class="text-[11px] font-extrabold text-gray-400 uppercase tracking-widest mb-1">Total Pendaftar</p>
            <h3 class="text-3xl font-black text-brand-dark" x-text="stats.pendaftar"></h3>
        </div>

        <div class="bg-white p-6 rounded-[2rem] border border-gray-100 shadow-sm hover:-translate-y-1 transition-transform group">
            <div class="flex justify-between items-start mb-4">
                <div class="w-12 h-12 rounded-2xl bg-amber-50 text-amber-600 flex items-center justify-center group-hover:scale-110 transition-transform">
                    <span class="flex items-center justify-center w-5 h-5"><i data-feather="clock"></i></span>
                </div>
            </div>
            <p class="text-[11px] font-extrabold text-gray-400 uppercase tracking-widest mb-1">Menunggu Validasi</p>
            <h3 class="text-3xl font-black text-brand-dark" x-text="stats.validasi"></h3>
        </div>

        <div class="bg-white p-6 rounded-[2rem] border border-gray-100 shadow-sm hover:-translate-y-1 transition-transform group">
            <div class="flex justify-between items-start mb-4">
                <div class="w-12 h-12 rounded-2xl bg-green-50 text-green-600 flex items-center justify-center group-hover:scale-110 transition-transform">
                    <span class="flex items-center justify-center w-5 h-5"><i data-feather="check-circle"></i></span>
                </div>
            </div>
            <p class="text-[11px] font-extrabold text-gray-400 uppercase tracking-widest mb-1">Lulus Seleksi</p>
            <h3 class="text-3xl font-black text-brand-dark" x-text="stats.lulus"></h3>
        </div>

        <div class="bg-brand-dark p-6 rounded-[2rem] shadow-xl hover:-translate-y-1 transition-transform relative overflow-hidden group">
            <div class="absolute -right-6 -top-6 opacity-10 text-white transform group-hover:scale-110 transition-transform">
                <i data-feather="dollar-sign" class="w-32 h-32"></i>
            </div>
            <div class="relative z-10">
                <div class="w-12 h-12 rounded-2xl bg-white/10 text-white flex items-center justify-center mb-4 backdrop-blur-sm">
                    <span class="flex items-center justify-center w-5 h-5"><i data-feather="credit-card"></i></span>
                </div>
                <p class="text-[11px] font-extrabold text-gray-400 uppercase tracking-widest mb-1">Pendapatan Formulir</p>
                <h3 class="text-3xl font-black text-white" x-text="'Rp ' + stats.pendapatan + ' Jt'"></h3>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 mb-8">
        
        <div class="lg:col-span-8 bg-white p-8 rounded-[2.5rem] shadow-sm border border-gray-100 flex flex-col">
            <div class="flex justify-between items-center mb-8">
                <div>
                    <h3 class="text-[16px] font-extrabold text-brand-dark">Statistik Pendaftaran</h3>
                    <p class="text-[12px] font-medium text-gray-400 mt-1">Grafik masuknya pendaftar berdasarkan waktu.</p>
                </div>
                <div class="text-[12px] font-bold text-brand-blue bg-brand-blue-light px-3 py-1 rounded-lg" x-text="'Filter: ' + activeFilter"></div>
            </div>
            
            <div class="flex-1 flex items-end gap-3 md:gap-6 h-48 mt-auto">
                <template x-for="bar in chartData" :key="bar.label">
                    <div class="flex flex-col items-center flex-1 group">
                        <div class="w-full bg-brand-blue-light rounded-t-xl relative overflow-hidden transition-all duration-500 group-hover:bg-blue-200"
                             :style="`height: ${bar.height}%;`">
                            <div class="absolute bottom-0 w-full bg-brand-blue rounded-t-xl transition-all duration-700 delay-100"
                                 :style="`height: ${bar.fill}%;`"></div>
                        </div>
                        <span class="text-[10px] font-black text-gray-400 mt-3 uppercase" x-text="bar.label"></span>
                    </div>
                </template>
            </div>
        </div>

        <div class="lg:col-span-4 space-y-6">
            <div class="bg-white p-8 rounded-[2.5rem] shadow-sm border border-gray-100 h-full">
                <h3 class="text-[16px] font-extrabold text-brand-dark mb-6">Aksi Cepat</h3>
                <div class="space-y-4">
                    <a href="/admin/validasi-pembayaran" class="flex items-center gap-4 p-4 rounded-2xl bg-gray-50 border border-gray-100 hover:border-brand-blue hover:bg-white transition-all group">
                        <div class="w-10 h-10 rounded-xl bg-white text-brand-dark shadow-sm flex items-center justify-center group-hover:bg-brand-blue-light group-hover:text-brand-blue transition-colors">
                            <span class="flex items-center justify-center w-5 h-5"><i data-feather="check-square"></i></span>
                        </div>
                        <div>
                            <p class="text-[13px] font-black text-brand-dark group-hover:text-brand-blue transition-colors">Validasi Pembayaran</p>
                            <p class="text-[11px] font-medium text-gray-400">Ada <span x-text="stats.validasi"></span> data baru</p>
                        </div>
                    </a>
                    
                    <a href="/admin/pengumuman" class="flex items-center gap-4 p-4 rounded-2xl bg-gray-50 border border-gray-100 hover:border-brand-blue hover:bg-white transition-all group">
                        <div class="w-10 h-10 rounded-xl bg-white text-brand-dark shadow-sm flex items-center justify-center group-hover:bg-brand-blue-light group-hover:text-brand-blue transition-colors">
                            <span class="flex items-center justify-center w-5 h-5"><i data-feather="volume-2"></i></span>
                        </div>
                        <div>
                            <p class="text-[13px] font-black text-brand-dark group-hover:text-brand-blue transition-colors">Buat Pengumuman</p>
                            <p class="text-[11px] font-medium text-gray-400">Publikasi kelulusan</p>
                        </div>
                    </a>

                    <a href="/admin/prodi" class="flex items-center gap-4 p-4 rounded-2xl bg-gray-50 border border-gray-100 hover:border-brand-blue hover:bg-white transition-all group">
                        <div class="w-10 h-10 rounded-xl bg-white text-brand-dark shadow-sm flex items-center justify-center group-hover:bg-brand-blue-light group-hover:text-brand-blue transition-colors">
                            <span class="flex items-center justify-center w-5 h-5"><i data-feather="book-open"></i></span>
                        </div>
                        <div>
                            <p class="text-[13px] font-black text-brand-dark group-hover:text-brand-blue transition-colors">Program Studi</p>
                            <p class="text-[11px] font-medium text-gray-400">Atur daya tampung</p>
                        </div>
                    </a>
                </div>
            </div>
        </div>

    </div>

    <div class="bg-white rounded-[2.5rem] shadow-sm border border-gray-100 overflow-hidden">
        <div class="p-8 border-b border-gray-50 flex justify-between items-center">
            <h3 class="text-[16px] font-extrabold text-brand-dark">Pendaftar Terbaru</h3>
            <a href="/admin/pendaftar" class="text-[12px] font-extrabold text-brand-blue hover:text-brand-dark transition-colors">Lihat Semua Data &rarr;</a>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left whitespace-nowrap">
                <thead class="bg-gray-50/50 text-[10px] font-black text-gray-400 uppercase tracking-widest border-b border-gray-100">
                    <tr>
                        <th class="px-8 py-5">Pendaftar</th>
                        <th class="px-4 py-5">Program Studi</th>
                        <th class="px-4 py-5">Waktu Daftar</th>
                        <th class="px-8 py-5 text-center">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50 text-[13px]">
                    <template x-for="item in recentPendaftar" :key="item.id">
                        <tr class="hover:bg-gray-50/50 transition-colors">
                            <td class="px-8 py-4">
                                <div class="flex items-center gap-3">
                                    <div class="w-9 h-9 rounded-full flex items-center justify-center font-black text-[10px]"
                                         :class="item.color" x-text="item.initial"></div>
                                    <div class="flex flex-col">
                                        <span class="font-bold text-brand-dark" x-text="item.nama"></span>
                                        <span class="text-gray-400 text-[11px]" x-text="item.email"></span>
                                    </div>
                                </div>
                            </td>
                            <td class="px-4 py-4 font-bold text-gray-500" x-text="item.prodi"></td>
                            <td class="px-4 py-4 font-medium text-gray-400" x-text="item.waktu"></td>
                            <td class="px-8 py-4 text-center">
                                <span class="px-3 py-1.5 rounded-lg text-[10px] font-black uppercase tracking-widest inline-flex items-center gap-1.5"
                                      :class="item.status === 'Selesai' ? 'bg-green-50 text-green-600' : 'bg-amber-50 text-amber-600'">
                                    <div class="w-1.5 h-1.5 rounded-full" :class="item.status === 'Selesai' ? 'bg-green-500' : 'bg-amber-500 animate-pulse'"></div>
                                    <span x-text="item.status"></span>
                                </span>
                            </td>
                        </tr>
                    </template>
                </tbody>
            </table>
        </div>
    </div>

</div>

<script>
document.addEventListener('alpine:init', () => {
    Alpine.data('dashboardController', () => ({
        activeFilter: 'Bulan Ini',
        stats: { pendaftar: '0', pendaftarTrend: 0, validasi: '0', lulus: '0', pendapatan: '0' },
        chartData: [],
        recentPendaftar: [],
        
        dataSource: {
            'Hari Ini': {
                stats: { pendaftar: '24', pendaftarTrend: 12, validasi: '5', lulus: '0', pendapatan: '6.5' },
                chart: [
                    { label: '08:00', height: 40, fill: 20 }, { label: '10:00', height: 60, fill: 45 },
                    { label: '12:00', height: 80, fill: 60 }, { label: '14:00', height: 100, fill: 85 },
                    { label: '16:00', height: 50, fill: 30 }, { label: '18:00', height: 20, fill: 10 }
                ],
                recent: [
                    { id: 1, initial: 'AN', color: 'bg-blue-50 text-brand-blue', nama: 'Andi Nugraha', email: 'andi@email.com', prodi: 'S1 Informatika', waktu: '10 menit yang lalu', status: 'Proses' },
                    { id: 2, initial: 'SR', color: 'bg-amber-50 text-amber-600', nama: 'Siti Rahma', email: 'siti@email.com', prodi: 'S1 Teknik Sipil', waktu: '1 jam yang lalu', status: 'Selesai' },
                ]
            },
            'Minggu Ini': {
                stats: { pendaftar: '156', pendaftarTrend: -5, validasi: '24', lulus: '85', pendapatan: '42.8' },
                chart: [
                    { label: 'Sen', height: 60, fill: 40 }, { label: 'Sel', height: 80, fill: 70 },
                    { label: 'Rab', height: 100, fill: 90 }, { label: 'Kam', height: 50, fill: 30 },
                    { label: 'Jum', height: 70, fill: 50 }, { label: 'Sab', height: 40, fill: 20 }, { label: 'Min', height: 30, fill: 10 }
                ],
                recent: [
                    { id: 1, initial: 'FA', color: 'bg-purple-50 text-purple-600', nama: 'Fajar Alfian', email: 'fajar@email.com', prodi: 'S1 Manajemen Bisnis', waktu: 'Kemarin, 14:00', status: 'Selesai' },
                    { id: 2, initial: 'DK', color: 'bg-pink-50 text-pink-600', nama: 'Dinda Kirana', email: 'dinda@email.com', prodi: 'S1 Hukum', waktu: 'Selasa, 09:15', status: 'Proses' },
                    { id: 3, initial: 'BW', color: 'bg-indigo-50 text-indigo-600', nama: 'Bambang Wijaya', email: 'bambang@email.com', prodi: 'S1 Informatika', waktu: 'Senin, 16:30', status: 'Selesai' },
                ]
            },
            'Bulan Ini': {
                stats: { pendaftar: '1,284', pendaftarTrend: 18, validasi: '84', lulus: '412', pendapatan: '345.5' },
                chart: [
                    { label: 'Mgg 1', height: 60, fill: 50 }, { label: 'Mgg 2', height: 100, fill: 85 },
                    { label: 'Mgg 3', height: 80, fill: 60 }, { label: 'Mgg 4', height: 40, fill: 20 }
                ],
                recent: [
                    { id: 1, initial: 'ZA', color: 'bg-emerald-50 text-emerald-600', nama: 'Zahra Amalia', email: 'zahra@email.com', prodi: 'S1 Gizi', waktu: '12 Okt 2023', status: 'Selesai' },
                    { id: 2, initial: 'RG', color: 'bg-slate-100 text-slate-700', nama: 'Rizky Gunawan', email: 'rizky@email.com', prodi: 'S1 Teknik Industri', waktu: '10 Okt 2023', status: 'Selesai' },
                    { id: 3, initial: 'LM', color: 'bg-blue-50 text-brand-blue', nama: 'Lina Marlina', email: 'lina@email.com', prodi: 'S1 DKV', waktu: '08 Okt 2023', status: 'Proses' },
                    { id: 4, initial: 'BP', color: 'bg-amber-50 text-amber-600', nama: 'Budi Prakoso', email: 'budi@email.com', prodi: 'S1 Arsitektur', waktu: '05 Okt 2023', status: 'Selesai' },
                ]
            }
        },

        init() {
            this.changeFilter(this.activeFilter);
        },

        changeFilter(filter) {
            this.activeFilter = filter;
            const data = this.dataSource[filter];
            
            this.stats = { ...data.stats };
            
            this.chartData = data.chart.map(c => ({ ...c, fill: 0 }));
            setTimeout(() => {
                this.chartData = [...data.chart];
            }, 50);

            this.recentPendaftar = [...data.recent];

            // Render Ikon Aman
            setTimeout(() => {
                if (window.feather) feather.replace();
            }, 50);
        }
    }));
});
</script>
@endsection