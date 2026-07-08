@extends('layouts.admin')

@section('admin-content')
<div class="container mx-auto px-4 py-6">
    <h1 class="text-2xl font-bold text-gray-800 mb-6">Master Biaya Program Studi</h1>

    @if(session('success'))
        <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6">
            {{ session('success') }}
        </div>
    @endif

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @foreach($prodis as $prodi)
        <div class="bg-white rounded-lg shadow-md p-6">
            <h2 class="text-xl font-bold text-gray-800 mb-2">{{ $prodi->nama_prodi }}</h2>
            <p class="text-sm text-gray-500 mb-4">Update biaya standar untuk prodi ini.</p>
            
            <form action="{{ route('admin.master.biaya.update', $prodi->id) }}" method="POST">
                @csrf
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Biaya SPP / Semester (Rp)</label>
                    <input type="number" name="spp" value="{{ $prodi->komponenBiaya->spp ?? 0 }}" required class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                </div>
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Uang Pangkal / Bangunan (Rp)</label>
                    <input type="number" name="uang_pangkal" value="{{ $prodi->komponenBiaya->uang_pangkal ?? 0 }}" required class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                </div>
                
                <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded transition">
                    Simpan Biaya
                </button>
            </form>
        </div>
        @endforeach
    </div>
</div>
@endsection