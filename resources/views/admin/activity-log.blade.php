@extends('layouts.admin')

@section('title', 'Log Aktivitas')

@section('admin-content')

<div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-8">
    <div>
        <h1 class="text-2xl font-extrabold text-brand-dark tracking-tight">Log Aktivitas</h1>
        <p class="text-brand-gray text-[13px] font-medium mt-1">
            Riwayat aktivitas admin & pendaftar di sistem SPMB.
        </p>
    </div>
</div>

{{-- ── Filter ───────────────────────────────────────────────── --}}
<form method="GET" action="{{ route('admin.activity-log') }}"
      class="bg-white border border-gray-100 rounded-2xl p-4 mb-6 grid grid-cols-1 md:grid-cols-5 gap-3 shadow-sm">
    <div class="md:col-span-2">
        <input type="text" name="search" value="{{ request('search') }}"
               placeholder="Cari nama, deskripsi, atau modul..."
               class="w-full px-3.5 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-[13px] outline-none focus:border-brand-blue focus:bg-white transition-all">
    </div>

    <select name="actor_type" class="px-3.5 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-[13px] outline-none focus:border-brand-blue">
        <option value="">Kategori</option>
        <option value="admin"     {{ request('actor_type') === 'admin' ? 'selected' : '' }}>Admin</option>
        <option value="pendaftar" {{ request('actor_type') === 'pendaftar' ? 'selected' : '' }}>Pendaftar</option>
        <option value="system"    {{ request('actor_type') === 'system' ? 'selected' : '' }}>Sistem</option>
    </select>

    <select name="aktivitas" class="px-3.5 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-[13px] outline-none focus:border-brand-blue">
        <option value="">Jenis Aktivitas</option>
        @foreach($daftarAktivitas as $a)
        <option value="{{ $a }}" {{ request('aktivitas') === $a ? 'selected' : '' }}>{{ $a }}</option>
        @endforeach
    </select>

    <div class="flex gap-2">
        <input type="date" name="dari" value="{{ request('dari') }}"
               class="w-full px-3 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-[12px] outline-none focus:border-brand-blue">
        <input type="date" name="sampai" value="{{ request('sampai') }}"
               class="w-full px-3 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-[12px] outline-none focus:border-brand-blue">
    </div>

    <div class="md:col-span-5 flex gap-2 justify-end">
        <button type="submit"
                class="px-5 py-2.5 bg-brand-blue text-white rounded-xl font-bold text-[13px] hover:bg-blue-700 transition-colors">
            Terapkan Filter
        </button>
        <a href="{{ route('admin.activity-log') }}"
           class="px-4 py-2.5 bg-gray-100 text-gray-600 rounded-xl font-bold text-[13px] hover:bg-gray-200 transition-colors">
            Reset
        </a>
    </div>
</form>

{{-- ── Tabel ───────────────────────────────────────────────── --}}
<div class="bg-white border border-gray-100 rounded-2xl shadow-sm overflow-hidden">
    <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
        <p class="text-[13px] font-bold text-brand-gray">
            Total: <span class="text-brand-dark">{{ $logs->total() }}</span> aktivitas
        </p>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full">
            <thead>
                <tr class="bg-gray-50 border-b border-gray-100 text-left">
                    <th class="px-5 py-3 text-[11px] font-black text-gray-400 uppercase tracking-widest w-40">Waktu</th>
                    <th class="px-5 py-3 text-[11px] font-black text-gray-400 uppercase tracking-widest w-48">Pelaku</th>
                    <th class="px-5 py-3 text-[11px] font-black text-gray-400 uppercase tracking-widest w-36">Aktivitas</th>
                    <th class="px-5 py-3 text-[11px] font-black text-gray-400 uppercase tracking-widest">Deskripsi</th>
                    <th class="px-5 py-3 text-[11px] font-black text-gray-400 uppercase tracking-widest w-28">IP</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                @forelse($logs as $log)
                <tr class="hover:bg-gray-50/60 transition-colors">
                    <td class="px-5 py-3.5 text-[12px] text-gray-500 font-medium whitespace-nowrap">
                        {{ $log->created_at->format('d M Y, H:i') }}
                    </td>
                    <td class="px-5 py-3.5">
                        <p class="font-bold text-brand-dark text-[13px]">{{ $log->actor_nama ?? '—' }}</p>
                        <span class="text-[10px] font-black uppercase tracking-widest px-1.5 py-0.5 rounded
                            {{ $log->actor_type === 'admin' ? 'bg-blue-50 text-blue-700'
                               : ($log->actor_type === 'pendaftar' ? 'bg-emerald-50 text-emerald-700' : 'bg-gray-100 text-gray-500') }}">
                            {{ $log->actor_type }}{{ $log->actor_role ? ' · ' . $log->actor_role : '' }}
                        </span>
                    </td>
                    <td class="px-5 py-3.5">
                        <span class="px-2 py-1 bg-purple-50 text-purple-700 rounded-lg text-[11px] font-bold font-mono">
                            {{ $log->aktivitas }}
                        </span>
                    </td>
                    <td class="px-5 py-3.5 text-[13px] text-brand-dark">
                        {{ $log->deskripsi }}
                        @if($log->modul)
                        <span class="block text-[11px] text-gray-400 mt-0.5">Modul: {{ $log->modul }}</span>
                        @endif
                    </td>
                    <td class="px-5 py-3.5 text-[11px] text-gray-400 font-mono">{{ $log->ip_address }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="px-5 py-16 text-center">
                        <div class="flex flex-col items-center gap-3">
                            <i data-feather="activity" class="w-10 h-10 text-gray-200"></i>
                            <p class="text-gray-400 font-bold text-[14px]">Belum ada aktivitas tercatat.</p>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($logs->hasPages())
    <div class="px-6 py-4 border-t border-gray-100">
        {{ $logs->links() }}
    </div>
    @endif
</div>

@endsection
