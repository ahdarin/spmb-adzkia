@extends('layouts.admin')

@section('admin-content')
<div class="container mx-auto px-4 py-6">
    <h1 class="text-2xl font-bold text-gray-800 mb-6">Master Data Gelombang</h1>

    @if(session('success'))
        <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6">
            {{ session('success') }}
        </div>
    @endif

    <div class="bg-white rounded-lg shadow-md p-6 mb-8">
        <h2 class="text-lg font-semibold mb-4">Tambah Gelombang Baru</h2>
        <form action="{{ route('admin.master.gelombang.store') }}" method="POST" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-4 items-end">
            @csrf
            <div>
                <label class="block text-sm font-medium text-gray-700">Nama Gelombang</label>
                <input type="text" name="nama_gelombang" required placeholder="Contoh: Gelombang 1" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700">Diskon SPP (Rp)</label>
                <input type="number" name="diskon_spp" value="0" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700">Diskon Uang Pangkal (Rp)</label>
                <input type="number" name="diskon_uang_pangkal" value="0" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700">Status</label>
                <select name="is_active" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                    <option value="1">Aktif</option>
                    <option value="0">Tidak Aktif</option>
                </select>
            </div>
            <div>
                <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                    Simpan
                </button>
            </div>
        </form>
    </div>

    <div class="bg-white rounded-lg shadow-md overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Diskon SPP</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Diskon Pangkal</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($gelombangs as $g)
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $g->nama_gelombang }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">Rp {{ number_format($g->diskon_spp, 0, ',', '.') }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">Rp {{ number_format($g->diskon_uang_pangkal, 0, ',', '.') }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm">
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $g->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                {{ $g->is_active ? 'Aktif' : 'Nonaktif' }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium flex gap-2">
                            <form action="{{ route('admin.master.gelombang.destroy', $g->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus?');">
                                @csrf @method('DELETE')
                                <button type="submit" class="text-red-600 hover:text-red-900">Hapus</button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection