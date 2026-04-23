@extends('layouts.admin')

@section('admin-content')
<div class="flex flex-col items-center justify-center min-h-[60vh] text-center">
    <div class="w-20 h-20 bg-brand-blue-light text-brand-blue rounded-3xl flex items-center justify-center mb-6 shadow-sm">
        <i data-feather="shield" class="w-10 h-10"></i>
    </div>
    <h1 class="text-3xl font-extrabold text-brand-dark mb-2">Pusat Validasi</h1>
    <p class="text-brand-gray max-w-md mb-10 font-medium">
        Kelola dan verifikasi data pendaftar mahasiswa baru secara akurat. Pilih jenis validasi di bawah ini.
    </p>

    <div class="flex gap-6 w-full max-w-2xl">
        <a href="/admin/validasi-pembayaran" class="flex-1 bg-white p-8 rounded-[2rem] border border-gray-100 shadow-sm hover:shadow-xl hover:-translate-y-1 transition-all group">
            <div class="w-12 h-12 bg-green-50 text-green-600 rounded-2xl flex items-center justify-center mb-6 group-hover:bg-green-600 group-hover:text-white transition-all">
                <i data-feather="dollar-sign"></i>
            </div>
            <h3 class="text-lg font-extrabold text-brand-dark mb-2">Pembayaran</h3>
            <p class="text-sm text-brand-gray font-medium">Validasi bukti transfer dan biaya pendaftaran.</p>
        </a>

        <a href="/admin/validasi-daftar-ulang" class="flex-1 bg-white p-8 rounded-[2rem] border border-gray-100 shadow-sm hover:shadow-xl hover:-translate-y-1 transition-all group">
            <div class="w-12 h-12 bg-amber-50 text-amber-600 rounded-2xl flex items-center justify-center mb-6 group-hover:bg-amber-600 group-hover:text-white transition-all">
                <i data-feather="file-text"></i>
            </div>
            <h3 class="text-lg font-extrabold text-brand-dark mb-2">Daftar Ulang</h3>
            <p class="text-sm text-brand-gray font-medium">Verifikasi dokumen dan berkas final mahasiswa.</p>
        </a>
    </div>
</div>
@endsection