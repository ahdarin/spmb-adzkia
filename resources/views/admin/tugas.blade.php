@extends('layouts.admin')

@section('admin-content')
<div class="py-6 max-w-5xl">
    
    <div class="mb-8">
        <h1 class="text-2xl font-black text-brand-dark tracking-tight">Manajemen Divisi Admin</h1>
        <p class="text-[13px] font-medium text-brand-gray mt-1">Tentukan peran dan tanggung jawab setiap admin di dalam sistem SPMB.</p>
    </div>

    @if(session('success'))
    <div class="mb-6 p-4 bg-green-50 border border-green-100 text-green-600 text-[13px] font-bold rounded-xl flex items-center gap-2">
        <i data-feather="check-circle" class="w-4 h-4"></i> {{ session('success') }}
    </div>
    @endif

    <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="px-6 py-5 border-b border-gray-100 bg-gray-50/50 flex justify-between items-center">
            <h3 class="text-[14px] font-black text-brand-dark">Daftar Admin & Hak Akses</h3>
        </div>
        
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-white border-b border-gray-100 text-[10px] font-black text-gray-400 uppercase tracking-widest">
                        <th class="p-4 pl-6">Profil Admin</th>
                        <th class="p-4">Email / Kontak</th>
                        <th class="p-4">Divisi & Tanggung Jawab</th>
                        <th class="p-4 pr-6 text-right">Aksi Simpan</th>
                    </tr>
                </thead>
                <tbody class="text-[13px] text-brand-dark">
                    @foreach($admins as $admin)
                    <tr class="border-b border-gray-50 hover:bg-gray-50/50 transition-colors">
                        <td class="p-4 pl-6 flex items-center gap-3">
                            <div class="w-10 h-10 rounded-full bg-brand-dark text-white flex items-center justify-center font-black text-[14px]">
                                {{ substr($admin->name, 0, 1) }}
                            </div>
                            <div>
                                <p class="font-bold text-brand-dark">{{ $admin->name }}</p>
                                <p class="text-[11px] font-black uppercase text-brand-blue">{{ $admin->role }}</p>
                            </div>
                        </td>
                        <td class="p-4 font-medium text-brand-gray">
                            {{ $admin->email }}
                        </td>
                        
                        <form action="{{ route('admin.tugas.update', $admin->id) }}" method="POST">
                            @csrf
                            @method('PUT')
                            <td class="p-4">
                                <select name="tanggung_jawab" class="w-full px-3 py-2 bg-white border border-gray-200 rounded-lg outline-none focus:border-brand-blue font-bold text-[12px] text-brand-dark">
                                    <option value="Belum Ditentukan" {{ $admin->tanggung_jawab == 'Belum Ditentukan' ? 'selected' : '' }}>-- Belum Ditentukan --</option>
                                    <option value="Super Admin (Full Akses)" {{ $admin->tanggung_jawab == 'Super Admin (Full Akses)' ? 'selected' : '' }}>Super Admin (Full Akses)</option>
                                    <option value="Keuangan (Validasi Pembayaran)" {{ $admin->tanggung_jawab == 'Keuangan (Validasi Pembayaran)' ? 'selected' : '' }}>Keuangan (Validasi Pembayaran)</option>
                                    <option value="Akademik (Prodi & Daftar Ulang)" {{ $admin->tanggung_jawab == 'Akademik (Prodi & Daftar Ulang)' ? 'selected' : '' }}>Akademik (Prodi & Daftar Ulang)</option>
                                    <option value="Humas (Berita, FAQ, Pengumuman)" {{ $admin->tanggung_jawab == 'Humas (Berita, FAQ, Pengumuman)' ? 'selected' : '' }}>Humas (Berita, FAQ, Pengumuman)</option>
                                </select>
                            </td>
                            <td class="p-4 pr-6 text-right">
                                <button type="submit" class="px-4 py-2 bg-brand-blue-light text-brand-blue hover:bg-brand-blue hover:text-white transition-all rounded-lg text-[11px] font-black uppercase tracking-widest flex items-center gap-1 ml-auto">
                                    <i data-feather="save" class="w-3 h-3"></i> Simpan
                                </button>
                            </td>
                        </form>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection