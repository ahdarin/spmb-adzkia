@extends('layouts.admin')

@section('admin-content')
<div>
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-10 gap-4">
        <div>
            <h1 class="text-3xl font-extrabold text-brand-dark tracking-tight mb-2">Manajemen Berita</h1>
            <p class="text-brand-gray text-[14px] font-medium">Publikasi kabar terbaru, prestasi, dan pengumuman untuk halaman depan.</p>
        </div>
        
        <a href="{{ route('admin.berita.create') }}" class="flex items-center gap-2 px-6 py-3 bg-brand-dark text-white rounded-2xl font-bold text-[13px] hover:bg-brand-blue transition-all shadow-lg shadow-brand-dark/10">
            <i data-feather="plus" class="w-4 h-4"></i> Tulis Berita Baru
        </a>
    </div>

    <div class="bg-white rounded-[2.5rem] shadow-sm border border-gray-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left whitespace-nowrap">
                <thead class="bg-gray-50/50 text-[11px] font-black text-brand-gray uppercase tracking-widest border-b">
                    <tr>
                        <th class="px-8 py-6">Judul Berita</th>
                        <th class="px-4 py-6">Kategori</th>
                        <th class="px-4 py-6">Tanggal Publikasi</th>
                        <th class="px-8 py-6 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50 text-[13px]">
                    @forelse($data as $item)
                    <tr class="hover:bg-gray-50/50 transition-colors group">
                        <td class="px-8 py-5">
                            <div class="flex items-center gap-4">
                                <div class="w-12 h-12 rounded-xl bg-gray-100 overflow-hidden shrink-0">
                                    <img src="{{ $item->thumbnail ? asset('uploads/berita/' . $item->thumbnail) : 'https://images.unsplash.com/photo-1523050854058-8df90110c9f1?q=80&w=100' }}" class="w-full h-full object-cover">
                                </div>
                                <div class="flex flex-col">
                                    <span class="font-bold text-brand-dark text-[15px] max-w-[300px] truncate">{{ $item->judul }}</span>
                                    <span class="text-[11px] font-medium text-gray-400 mt-0.5 max-w-[300px] truncate">{{ Str::limit($item->ringkasan ?? $item->isi, 50) }}</span>
                                </div>
                            </div>
                        </td>
                        <td class="px-4 py-5">
                            <span class="px-3 py-1 bg-blue-50 text-brand-blue rounded-lg text-[10px] font-black uppercase">{{ $item->kategori }}</span>
                        </td>
                        <td class="px-4 py-5 font-bold text-gray-500">
                            {{ \Carbon\Carbon::parse($item->tanggal_publish ?? $item->created_at)->translatedFormat('d M Y') }}
                        </td>
                        <td class="px-8 py-5 text-right">
                            <div class="flex justify-end gap-2 opacity-0 group-hover:opacity-100 transition-opacity">
                                <a href="{{ route('admin.berita.edit', $item->id) }}" class="p-2 text-brand-gray hover:text-brand-blue">
                                    <i data-feather="edit-3" class="w-4 h-4"></i>
                                </a>
                                <form action="{{ route('admin.berita.destroy', $item->id) }}" method="POST" class="inline" onsubmit="return confirm('Hapus berita ini beserta gambarnya?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="p-2 text-brand-gray hover:text-red-500">
                                        <i data-feather="trash-2" class="w-4 h-4"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="4" class="px-8 py-10 text-center text-gray-400 font-bold">Belum ada berita yang dipublikasikan.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection