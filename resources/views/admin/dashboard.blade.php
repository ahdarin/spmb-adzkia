@extends('layouts.admin')

@section('title', 'Dashboard')

@section('admin-content')

@php
    $chartLabelsBulan   = $labelsBulan ?? ['Jan','Feb','Mar','Apr','Mei','Jun','Jul','Ags','Sep','Okt','Nov','Des'];
    $chartDataBulan     = $dataBulan   ?? [0,0,0,0,0,0,0,0,0,0,0,0];
    $chartLabelsJurusan = (isset($jurusanData) && $jurusanData->count() > 0)
                          ? $jurusanData->keys()->toArray()
                          : ['Belum Ada Data'];
    $chartDataJurusan   = (isset($jurusanData) && $jurusanData->count() > 0)
                          ? $jurusanData->values()->toArray()
                          : [0];
@endphp

<div>

    {{-- ── Header ──────────────────────────────────────────────── --}}
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-10 gap-4">
        <div>
            <h1 class="text-3xl font-extrabold text-brand-dark tracking-tight mb-2">
                Selamat Datang, {{ Auth::user()->name }}!
            </h1>
            <p class="text-brand-gray text-[14px] font-medium">
                Ringkasan aktivitas pendaftaran berdasarkan:
                <span class="text-brand-blue font-bold">{{ $filter ?? 'Keseluruhan' }}</span>
            </p>
        </div>
        <div class="flex items-center gap-3">
            <div class="bg-white border border-gray-100 p-1.5 rounded-xl flex items-center shadow-sm">
                @foreach(['Hari Ini', 'Minggu Ini', 'Bulan Ini'] as $f)
                <a href="{{ route('admin.dashboard', ['filter' => $f]) }}"
                   class="px-5 py-2.5 rounded-lg text-[12px] font-black tracking-wide transition-all
                       {{ (isset($filter) && $filter == $f) ? 'bg-brand-blue text-white shadow-md' : 'text-gray-500 hover:text-brand-dark hover:bg-gray-50' }}">
                    {{ $f }}
                </a>
                @endforeach
            </div>
            <a href="{{ route('admin.export.csv') }}"
               class="px-5 py-3 bg-green-600 text-white rounded-xl font-extrabold text-[12px] hover:bg-green-700 transition-colors shadow-lg flex items-center gap-2 h-[42px]">
                <i data-feather="download" class="w-4 h-4"></i> Export Data
            </a>
        </div>
    </div>

    {{-- ── Kartu Statistik ─────────────────────────────────────── --}}
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">

        <div class="bg-white p-6 rounded-[2rem] border border-gray-100 shadow-sm hover:-translate-y-1 transition-transform group">
            <div class="w-12 h-12 rounded-2xl bg-blue-50 text-brand-blue flex items-center justify-center group-hover:scale-110 transition-transform mb-4">
                <i data-feather="users" class="w-6 h-6"></i>
            </div>
            <p class="text-[11px] font-extrabold text-gray-400 uppercase tracking-widest mb-1">Total Pendaftar</p>
            <h3 class="text-3xl font-black text-brand-dark">{{ number_format($stats['totalPendaftar'] ?? $totalPendaftar ?? 0) }}</h3>
        </div>

        <div class="bg-white p-6 rounded-[2rem] border border-gray-100 shadow-sm hover:-translate-y-1 transition-transform group">
            <div class="w-12 h-12 rounded-2xl bg-amber-50 text-amber-600 flex items-center justify-center group-hover:scale-110 transition-transform mb-4">
                <i data-feather="clock" class="w-6 h-6"></i>
            </div>
            <p class="text-[11px] font-extrabold text-gray-400 uppercase tracking-widest mb-1">Menunggu Validasi</p>
            <h3 class="text-3xl font-black text-brand-dark">{{ number_format($stats['menungguValidasi'] ?? $menungguValidasi ?? 0) }}</h3>
        </div>

        <div class="bg-white p-6 rounded-[2rem] border border-gray-100 shadow-sm hover:-translate-y-1 transition-transform group">
            <div class="w-12 h-12 rounded-2xl bg-green-50 text-green-600 flex items-center justify-center group-hover:scale-110 transition-transform mb-4">
                <i data-feather="check-circle" class="w-6 h-6"></i>
            </div>
            <p class="text-[11px] font-extrabold text-gray-400 uppercase tracking-widest mb-1">Lulus Seleksi</p>
            <h3 class="text-3xl font-black text-brand-dark">{{ number_format($stats['lulusSeleksi'] ?? $totalLulus ?? 0) }}</h3>
        </div>

        <div class="bg-brand-dark p-6 rounded-[2rem] shadow-xl hover:-translate-y-1 transition-transform relative overflow-hidden group">
            <div class="absolute -right-6 -top-6 opacity-10 text-white group-hover:scale-110 transition-transform">
                <i data-feather="dollar-sign" class="w-32 h-32"></i>
            </div>
            <div class="relative z-10">
                <div class="w-12 h-12 rounded-2xl bg-white/10 text-white flex items-center justify-center mb-4">
                    <i data-feather="credit-card" class="w-6 h-6"></i>
                </div>
                <p class="text-[11px] font-extrabold text-gray-400 uppercase tracking-widest mb-1">Pendapatan Formulir</p>
                <h3 class="text-3xl font-black text-white">Rp {{ number_format($stats['pendapatan'] ?? 0, 1) }} Jt</h3>
            </div>
        </div>

    </div>

    {{-- ── Grafik + Aksi Cepat ──────────────────────────────────── --}}
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 mb-8">

        <div class="lg:col-span-8 bg-white p-8 rounded-[2.5rem] shadow-sm border border-gray-100 flex flex-col">
            <div class="mb-6">
                <h3 class="text-[16px] font-extrabold text-brand-dark">Statistik Pendaftaran</h3>
                <p class="text-[12px] font-medium text-gray-400 mt-1">Grafik masuknya pendaftar per bulan.</p>
            </div>
            <div class="relative flex-1 w-full" style="min-height:220px;">
                <canvas id="chartBulan"></canvas>
            </div>
        </div>

        <div class="lg:col-span-4">
            <div class="bg-white p-8 rounded-[2.5rem] shadow-sm border border-gray-100 h-full">
                <h3 class="text-[16px] font-extrabold text-brand-dark mb-6">Aksi Cepat</h3>
                <div class="space-y-4">
                    <a href="/admin/validasi-pembayaran"
                       class="flex items-center gap-4 p-4 rounded-2xl bg-gray-50 border border-gray-100 hover:border-brand-blue hover:bg-white transition-all group">
                        <div class="w-10 h-10 rounded-xl bg-white text-brand-dark shadow-sm flex items-center justify-center group-hover:bg-brand-blue-light group-hover:text-brand-blue transition-colors">
                            <i data-feather="check-square" class="w-5 h-5"></i>
                        </div>
                        <div>
                            <p class="text-[13px] font-black text-brand-dark group-hover:text-brand-blue transition-colors">Validasi Pembayaran</p>
                            <p class="text-[11px] font-medium text-gray-400">Cek pendaftar baru</p>
                        </div>
                    </a>
                    <a href="/admin/pengumuman"
                       class="flex items-center gap-4 p-4 rounded-2xl bg-gray-50 border border-gray-100 hover:border-brand-blue hover:bg-white transition-all group">
                        <div class="w-10 h-10 rounded-xl bg-white text-brand-dark shadow-sm flex items-center justify-center group-hover:bg-brand-blue-light group-hover:text-brand-blue transition-colors">
                            <i data-feather="volume-2" class="w-5 h-5"></i>
                        </div>
                        <div>
                            <p class="text-[13px] font-black text-brand-dark group-hover:text-brand-blue transition-colors">Buat Pengumuman</p>
                            <p class="text-[11px] font-medium text-gray-400">Publikasi kelulusan</p>
                        </div>
                    </a>
                    <a href="{{ route('admin.master.biaya-daftar-ulang.index') }}"
                       class="flex items-center gap-4 p-4 rounded-2xl bg-gray-50 border border-gray-100 hover:border-brand-blue hover:bg-white transition-all group">
                        <div class="w-10 h-10 rounded-xl bg-white text-brand-dark shadow-sm flex items-center justify-center group-hover:bg-brand-blue-light group-hover:text-brand-blue transition-colors">
                            <i data-feather="dollar-sign" class="w-5 h-5"></i>
                        </div>
                        <div>
                            <p class="text-[13px] font-black text-brand-dark group-hover:text-brand-blue transition-colors">Master Biaya</p>
                            <p class="text-[11px] font-medium text-gray-400">Kelola biaya daftar ulang</p>
                        </div>
                    </a>
                    <a href="/admin/settings"
                       class="flex items-center gap-4 p-4 rounded-2xl bg-gray-50 border border-gray-100 hover:border-brand-blue hover:bg-white transition-all group">
                        <div class="w-10 h-10 rounded-xl bg-white text-brand-dark shadow-sm flex items-center justify-center group-hover:bg-brand-blue-light group-hover:text-brand-blue transition-colors">
                            <i data-feather="settings" class="w-5 h-5"></i>
                        </div>
                        <div>
                            <p class="text-[13px] font-black text-brand-dark group-hover:text-brand-blue transition-colors">Pengaturan</p>
                            <p class="text-[11px] font-medium text-gray-400">Gelombang & sistem</p>
                        </div>
                    </a>
                </div>
            </div>
        </div>

    </div>

    {{-- ── Grafik Minat Prodi ───────────────────────────────────── --}}
    <div class="bg-white p-8 rounded-[2.5rem] shadow-sm border border-gray-100 mb-8">
        <div class="flex justify-between items-center mb-6">
            <div>
                <h3 class="text-[16px] font-extrabold text-brand-dark">Minat Program Studi</h3>
                <p class="text-[12px] font-medium text-gray-400 mt-1">Distribusi pendaftar berdasarkan pilihan program studi.</p>
            </div>
            <i data-feather="pie-chart" class="w-5 h-5 text-brand-blue"></i>
        </div>
        <div class="relative w-full" style="height:280px;">
            <canvas id="chartJurusan"></canvas>
        </div>
    </div>

</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
(function() {
    var labelsBulan   = {!! json_encode($chartLabelsBulan) !!};
    var dataBulan     = {!! json_encode($chartDataBulan) !!};
    var labelsJurusan = {!! json_encode($chartLabelsJurusan) !!};
    var dataJurusan   = {!! json_encode($chartDataJurusan) !!};

    // Destroy dulu jika canvas sudah dipakai sebelumnya
    var c1 = Chart.getChart('chartBulan');
    var c2 = Chart.getChart('chartJurusan');
    if (c1) c1.destroy();
    if (c2) c2.destroy();

    new Chart(document.getElementById('chartBulan'), {
        type: 'line',
        data: {
            labels: labelsBulan,
            datasets: [{
                label: 'Pendaftar',
                data: dataBulan,
                borderColor: '#2563EB',
                backgroundColor: 'rgba(37,99,235,0.08)',
                borderWidth: 3,
                fill: true,
                tension: 0.4,
                pointBackgroundColor: '#fff',
                pointBorderColor: '#2563EB',
                pointBorderWidth: 2,
                pointRadius: 4,
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: { legend: { display: false } },
            scales: {
                y: { beginAtZero: true, grid: { color: 'rgba(0,0,0,0.04)' } },
                x: { grid: { display: false } }
            }
        }
    });

    new Chart(document.getElementById('chartJurusan'), {
        type: 'bar',
        data: {
            labels: labelsJurusan,
            datasets: [{
                label: 'Peminat',
                data: dataJurusan,
                backgroundColor: ['#2563EB','#3B82F6','#60A5FA','#93C5FD','#1E40AF','#BFDBFE'],
                borderRadius: 8,
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: { legend: { display: false } },
            scales: {
                y: { beginAtZero: true, grid: { color: 'rgba(0,0,0,0.04)' } },
                x: { grid: { display: false } }
            }
        }
    });

    // Re-run feather untuk icon yang baru di-yield
    if (typeof feather !== 'undefined') feather.replace({ 'stroke-width': 1.75 });
})();
</script>
@endpush