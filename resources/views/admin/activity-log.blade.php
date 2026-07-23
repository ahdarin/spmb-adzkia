@extends('layouts.admin')

@section('title', 'Log Aktivitas')

@section('admin-content')

{{-- ── Header ────────────────────────────────────────────────── --}}
<div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-8">
    <div>
        <h1 class="text-2xl font-extrabold text-brand-dark tracking-tight">Log Aktivitas</h1>
        <p class="text-brand-gray text-[13px] font-medium mt-1">
            Riwayat lengkap tindakan admin & pendaftar di sistem SPMB.
        </p>
    </div>
    {{-- Tombol bersihkan log lama (opsional, super admin saja) --}}
</div>


{{-- ── Form Filter ─────────────────────────────────────────────── --}}
<form method="GET" action="{{ route('admin.activity-log') }}"
      class="bg-white border border-gray-100 rounded-2xl p-4 mb-6 shadow-sm">
    <div class="grid grid-cols-1 md:grid-cols-5 gap-3">

        {{-- Cari --}}
        <div class="md:col-span-2 relative">
            <i data-feather="search" class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400 pointer-events-none"></i>
            <input type="text" name="search" value="{{ request('search') }}"
                   placeholder="Cari nama, deskripsi, atau modul..."
                   class="w-full pl-9 pr-4 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-[13px] outline-none focus:border-brand-blue focus:bg-white transition-all">
        </div>

        {{-- Kategori Aktor --}}
        <select name="actor_type"
                class="px-3.5 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-[13px] outline-none focus:border-brand-blue cursor-pointer">
            <option value="">Semua Aktor</option>
            <option value="admin"     {{ request('actor_type') === 'admin'     ? 'selected' : '' }}>Admin</option>
            <option value="pendaftar" {{ request('actor_type') === 'pendaftar' ? 'selected' : '' }}>Pendaftar</option>
            <option value="system"    {{ request('actor_type') === 'system'    ? 'selected' : '' }}>Sistem</option>
        </select>

        {{-- Jenis Aktivitas --}}
        <select name="aktivitas"
                class="px-3.5 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-[13px] outline-none focus:border-brand-blue cursor-pointer">
            <option value="">Semua Aktivitas</option>
            @foreach($daftarAktivitas as $a)
            <option value="{{ $a }}" {{ request('aktivitas') === $a ? 'selected' : '' }}>
                {{ str_replace('_', ' ', ucfirst($a)) }}
            </option>
            @endforeach
        </select>

        {{-- Rentang Tanggal (satu input) --}}
        <div class="relative">
            <i data-feather="calendar" class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400 pointer-events-none z-10"></i>
            <input type="text" id="date-range-picker"
                   placeholder="Pilih rentang tanggal..."
                   autocomplete="off" readonly
                   class="w-full pl-9 pr-4 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-[12px] outline-none focus:border-brand-blue focus:bg-white transition-all cursor-pointer">
            <input type="hidden" name="dari"   id="input-dari"   value="{{ request('dari') }}">
            <input type="hidden" name="sampai" id="input-sampai" value="{{ request('sampai') }}">
        </div>
    </div>

    <div class="flex gap-2 justify-end mt-3">
        <a href="{{ route('admin.activity-log') }}"
           class="px-4 py-2.5 bg-gray-100 text-gray-600 rounded-xl font-bold text-[13px] hover:bg-gray-200 transition-colors flex items-center gap-1.5">
            <i data-feather="x" class="w-3.5 h-3.5"></i> Reset
        </a>
        <button type="submit"
                class="px-5 py-2.5 bg-brand-blue text-white rounded-xl font-bold text-[13px] hover:bg-blue-700 transition-colors flex items-center gap-1.5">
            <i data-feather="filter" class="w-3.5 h-3.5"></i> Terapkan Filter
        </button>
    </div>
</form>

{{-- ── Tabel Log ───────────────────────────────────────────────── --}}
<div class="bg-white border border-gray-100 rounded-2xl shadow-sm overflow-hidden">

    {{-- Jumlah hasil --}}
    <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between gap-3">
        <p class="text-[13px] font-bold text-brand-gray">
            Menampilkan
            <span class="text-brand-dark">{{ $logs->firstItem() ?? 0 }}–{{ $logs->lastItem() ?? 0 }}</span>
            dari
            <span class="text-brand-dark">{{ number_format($logs->total()) }}</span>
            aktivitas
        </p>
        @if(request()->hasAny(['search','actor_type','aktivitas','dari','sampai']))
        <span class="px-3 py-1 bg-amber-50 text-amber-700 border border-amber-200 rounded-full text-[11px] font-bold">
            Filter aktif
        </span>
        @endif
    </div>

    <div class="overflow-x-auto">
        <table class="w-full">
            <thead>
                <tr class="bg-gray-50 border-b border-gray-100 text-left">
                    <th class="px-5 py-3.5 text-[10px] font-black text-gray-400 uppercase tracking-widest w-44">Waktu</th>
                    <th class="px-5 py-3.5 text-[10px] font-black text-gray-400 uppercase tracking-widest w-52">Pelaku</th>
                    <th class="px-5 py-3.5 text-[10px] font-black text-gray-400 uppercase tracking-widest w-40">Aktivitas</th>
                    <th class="px-5 py-3.5 text-[10px] font-black text-gray-400 uppercase tracking-widest">Keterangan</th>
                    <th class="px-5 py-3.5 text-[10px] font-black text-gray-400 uppercase tracking-widest w-32">IP Address</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                @forelse($logs as $log)
                <tr class="hover:bg-gray-50/60 transition-colors">

                    {{-- Waktu --}}
                    <td class="px-5 py-4 whitespace-nowrap">
                        <p class="text-[12px] font-semibold text-gray-700">
                            {{ $log->created_at->format('d M Y') }}
                        </p>
                        <p class="text-[11px] text-gray-400 font-medium">
                            {{ $log->created_at->format('H:i:s') }}
                        </p>
                    </td>

                    {{-- Pelaku --}}
                    <td class="px-5 py-4">
                        <p class="font-bold text-brand-dark text-[13px] truncate max-w-[180px]">
                            {{ $log->actor_nama ?? '—' }}
                        </p>
                        <div class="flex items-center gap-1.5 mt-1">
                            <span class="inline-flex items-center gap-1 text-[10px] font-black uppercase tracking-widest px-2 py-0.5 rounded
                                {{ $log->actor_type === 'admin'
                                    ? 'bg-blue-50 text-blue-700'
                                    : ($log->actor_type === 'pendaftar'
                                        ? 'bg-emerald-50 text-emerald-700'
                                        : 'bg-gray-100 text-gray-500') }}">
                                @if($log->actor_type === 'admin')
                                    <i data-feather="shield" class="w-2.5 h-2.5"></i>
                                @elseif($log->actor_type === 'pendaftar')
                                    <i data-feather="user" class="w-2.5 h-2.5"></i>
                                @else
                                    <i data-feather="cpu" class="w-2.5 h-2.5"></i>
                                @endif
                                {{ $log->actor_type }}
                            </span>
                            @if($log->actor_role)
                            <span class="text-[10px] text-gray-400 font-medium">· {{ $log->actor_role }}</span>
                            @endif
                        </div>
                    </td>

                    {{-- Aktivitas (kode) --}}
                    <td class="px-5 py-4">
                        @php
                            $badgeColor = match(true) {
                                str_starts_with($log->aktivitas, 'login')      => 'bg-blue-50 text-blue-700 border-blue-100',
                                str_starts_with($log->aktivitas, 'logout')     => 'bg-gray-100 text-gray-600 border-gray-200',
                                str_starts_with($log->aktivitas, 'setujui')    => 'bg-emerald-50 text-emerald-700 border-emerald-100',
                                str_starts_with($log->aktivitas, 'tolak')      => 'bg-red-50 text-red-700 border-red-100',
                                str_starts_with($log->aktivitas, 'revisi')     => 'bg-amber-50 text-amber-700 border-amber-100',
                                str_starts_with($log->aktivitas, 'tambah')     => 'bg-purple-50 text-purple-700 border-purple-100',
                                str_starts_with($log->aktivitas, 'hapus')      => 'bg-red-50 text-red-600 border-red-100',
                                str_starts_with($log->aktivitas, 'edit')       => 'bg-indigo-50 text-indigo-700 border-indigo-100',
                                str_starts_with($log->aktivitas, 'tetapkan')   => 'bg-teal-50 text-teal-700 border-teal-100',
                                str_starts_with($log->aktivitas, 'daftar')     => 'bg-cyan-50 text-cyan-700 border-cyan-100',
                                default                                         => 'bg-gray-50 text-gray-600 border-gray-200',
                            };
                        @endphp
                        <span class="inline-block px-2.5 py-1 border rounded-lg text-[10px] font-black font-mono leading-none {{ $badgeColor }}">
                            {{ $log->aktivitas }}
                        </span>
                        @if($log->modul)
                        <p class="text-[10px] text-gray-400 font-medium mt-1.5">{{ $log->modul }}</p>
                        @endif
                    </td>

                    {{-- Deskripsi --}}
                    <td class="px-5 py-4">
                        <p class="text-[13px] text-brand-dark font-medium leading-snug">
                            {{ $log->deskripsi ?? '—' }}
                        </p>
                    </td>

                    {{-- IP --}}
                    <td class="px-5 py-4">
                        <span class="text-[11px] text-gray-400 font-mono">
                            {{ $log->ip_address ?? '—' }}
                        </span>
                    </td>

                </tr>
                @empty
                <tr>
                    <td colspan="5" class="px-5 py-20 text-center">
                        <div class="flex flex-col items-center gap-3">
                            <div class="w-14 h-14 rounded-2xl bg-gray-100 flex items-center justify-center">
                                <i data-feather="activity" class="w-7 h-7 text-gray-300"></i>
                            </div>
                            <p class="text-gray-400 font-bold text-[14px]">Belum ada aktivitas tercatat.</p>
                            @if(request()->hasAny(['search','actor_type','aktivitas','dari','sampai']))
                            <p class="text-gray-400 text-[12px]">Coba ubah atau hapus filter yang aktif.</p>
                            @endif
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Pagination --}}
    @if($logs->hasPages())
    <div class="px-6 py-4 border-t border-gray-100">
        {{ $logs->links() }}
    </div>
    @endif

</div>

{{-- Flatpickr date range --}}
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
<style>
    /* Sesuaikan warna flatpickr dengan tema brand */
    .flatpickr-calendar { font-family: 'Manrope', sans-serif; border-radius: 1rem; box-shadow: 0 10px 40px rgba(0,0,0,.12); border: 1px solid #e5e7eb; }
    .flatpickr-day.selected, .flatpickr-day.startRange, .flatpickr-day.endRange { background: #2c7ebd !important; border-color: #2c7ebd !important; }
    .flatpickr-day.inRange { background: #dbeafe !important; border-color: #dbeafe !important; color: #1e40af !important; }
    .flatpickr-day:hover { background: #eff6ff; border-color: #eff6ff; }
    .flatpickr-months .flatpickr-month, .flatpickr-weekdays, span.flatpickr-weekday { background: #2c7ebd; color: #fff; }
    .flatpickr-current-month select, .flatpickr-current-month .numInputWrapper input { color: #fff; }
    .flatpickr-current-month .numInputWrapper span { border-color: rgba(255,255,255,.3); }
    .flatpickr-current-month .numInputWrapper span svg path { fill: rgba(255,255,255,.8); }
    .numInput { color: #fff !important; }
</style>

<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script src="https://cdn.jsdelivr.net/npm/flatpickr/dist/l10n/id.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    if (typeof feather !== 'undefined') feather.replace();

    const inputDari   = document.getElementById('input-dari');
    const inputSampai = document.getElementById('input-sampai');
    const picker      = document.getElementById('date-range-picker');

    // Tampilkan nilai filter aktif saat halaman dimuat
    const initDari   = inputDari.value;
    const initSampai = inputSampai.value;

    flatpickr(picker, {
        mode: 'range',
        dateFormat: 'Y-m-d',          // format yang dikirim ke controller
        altInput: true,
        altFormat: 'd M Y',           // format tampilan di input
        locale: 'id',
        defaultDate: (initDari && initSampai) ? [initDari, initSampai]
                   : (initDari ? [initDari] : []),
        onReady(selectedDates, dateStr, instance) {
            // Pastikan feather icons tetap aktif setelah flatpickr inject DOM
            if (typeof feather !== 'undefined') feather.replace();
        },
        onChange(selectedDates) {
            if (selectedDates.length === 2) {
                const fmt = d => d.toISOString().slice(0, 10);
                inputDari.value   = fmt(selectedDates[0]);
                inputSampai.value = fmt(selectedDates[1]);
            } else if (selectedDates.length === 0) {
                inputDari.value   = '';
                inputSampai.value = '';
            }
        },
    });
});
</script>

@endsection