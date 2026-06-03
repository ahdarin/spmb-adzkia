@extends('layouts.app')

@section('title', 'Sistem Rekomendasi Program Studi - SPMB Adzkia')

@section('content')
<div class="max-w-3xl mx-auto px-6 py-12 md:py-20">
    <div class="bg-white rounded-[2rem] p-8 md:p-12 border border-gray-100 shadow-sm relative overflow-hidden text-center">
        <div class="absolute top-0 left-0 w-full h-32 bg-gradient-to-b from-blue-50 to-white"></div>
        
        <div class="relative z-10">
            <div class="w-20 h-20 bg-adzkia-blue text-white rounded-2xl mx-auto flex items-center justify-center shadow-lg shadow-blue-500/20 mb-6">
                <i data-feather="cpu" class="w-10 h-10"></i>
            </div>
            
            <h1 class="text-3xl font-black text-adzkia-dark mb-4">Sistem Rekomendasi Program Studi Berbasis AI</h1>
            <p class="text-[14px] font-medium text-gray-500 leading-relaxed max-w-xl mx-auto mb-8">
                Bingung memilih program studi yang tepat? Jawab beberapa pertanyaan singkat, dan kecerdasan buatan (AI) kami akan menganalisis profil Anda berdasarkan logika, kreativitas, kepemimpinan, dan aspek lainnya untuk merekomendasikan jurusan yang paling cocok.
            </p>

            <hr class="border-gray-100 mb-8">

            <form action="{{ route('rekomendasi.start.submit') }}" method="POST" class="max-w-md mx-auto text-left">
                @csrf
                <div class="mb-6">
                    <label for="minat_jurusan" class="block text-xs font-extrabold uppercase tracking-widest text-gray-400 mb-2">Program Studi Minat Awal Anda</label>
                    <select name="minat_jurusan" id="minat_jurusan" class="w-full px-4 py-3.5 bg-gray-50 border border-gray-200 rounded-xl text-[14px] font-bold text-adzkia-dark focus:ring-2 focus:ring-adzkia-blue focus:border-adzkia-blue transition-all" required>
                        <option value="">-- Pilih Jurusan --</option>
                        @foreach($prodis as $prodi)
                            <option value="{{ $prodi->id }}">{{ $prodi->nama }}</option>
                        @endforeach
                    </select>
                    <p class="text-[11px] text-gray-400 mt-2 font-medium">*Data ini digunakan untuk membantu AI mempelajari tren minat mahasiswa (dataset).</p>
                </div>

                <button type="submit" class="w-full py-4 bg-adzkia-dark text-white font-black text-[14px] rounded-xl shadow-xl hover:scale-105 active:scale-95 transition-all flex justify-center items-center gap-2">
                    Mulai Kuesioner <i data-feather="arrow-right" class="w-4 h-4"></i>
                </button>
            </form>
        </div>
    </div>
</div>
@endsection