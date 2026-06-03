@extends('layouts.app')

@section('title', 'Hasil Rekomendasi Program Studi - SPMB Adzkia')

@section('content')
@php
    $top1 = $hasil[0];
@endphp

<div class="max-w-6xl mx-auto px-6 py-12" x-data="{ showChatbot: false }">
    
    <div class="text-center mb-10">
        <span class="inline-block px-4 py-1.5 bg-adzkia-badge-bg text-adzkia-badge-txt text-[11px] font-extrabold rounded-full uppercase tracking-widest mb-3">Analisis Kecerdasan Buatan Selesai</span>
        <h2 class="text-3xl font-black text-adzkia-dark">Hasil Rekomendasi AI</h2>
    </div>

    <div class="bg-white rounded-3xl p-6 md:p-8 flex flex-col md:flex-row gap-6 items-center md:items-start border border-gray-100 shadow-sm mb-8">
        <div class="w-16 h-16 rounded-2xl bg-blue-50 text-adzkia-blue flex items-center justify-center shrink-0">
            <i data-feather="pie-chart" class="w-8 h-8"></i>
        </div>
        <div class="flex-1 text-center md:text-left">
            <h3 class="text-[16px] font-black text-adzkia-dark mb-2">Profil Karakteristik Anda</h3>
            <p class="text-[13px] font-medium text-gray-500 leading-relaxed">
                Berdasarkan hasil kuesioner, Anda menunjukkan kecenderungan kuat pada aspek 
                <strong class="text-adzkia-blue uppercase tracking-wide">{{ $topTraits[0] }}</strong>, 
                <strong class="text-adzkia-blue uppercase tracking-wide">{{ $topTraits[1] }}</strong>, dan 
                <strong class="text-adzkia-blue uppercase tracking-wide">{{ $topTraits[2] }}</strong>. 
                Karakteristik ini sangat selaras dengan lingkungan akademis dan prospek karir pada program studi <strong>{{ $top1['jurusan'] }}</strong>.
            </p>
        </div>
    </div>

    <div class="bg-gradient-to-r from-blue-600 to-indigo-700 rounded-[2rem] p-8 md:p-10 text-white text-center shadow-xl shadow-blue-600/20 relative overflow-hidden mb-8">
        <div class="absolute -right-10 -bottom-10 opacity-10"><i data-feather="award" class="w-64 h-64"></i></div>
        
        <p class="text-[11px] font-black uppercase tracking-widest text-blue-200 mb-2 relative z-10">Rekomendasi Utama (Top 1)</p>
        <h3 class="text-4xl md:text-5xl font-black mb-4 relative z-10">{{ $top1['jurusan'] }}</h3>
        
        <div class="inline-flex items-center gap-2 px-4 py-2 bg-white/20 backdrop-blur-sm rounded-xl border border-white/30 relative z-10">
            <i data-feather="check-circle" class="w-5 h-5 text-green-300"></i>
            <span class="text-sm font-extrabold text-white">Confidence Score: {{ $top1['score'] }}%</span>
        </div>
    </div>

    <div class="flex items-center justify-between mb-5 mt-12">
        <h3 class="text-lg font-black text-adzkia-dark">Rekomendasi Alternatif</h3>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-12">
        @foreach($sortedTopProdis->slice(1, 2) as $index => $prodi)
            @php 
                $rank = $index + 1; // +1 karena index collection array shift
                $aiScore = collect($hasil)->firstWhere('jurusan', $prodi->nama_prodi)['score'];
            @endphp
            <div class="bg-white rounded-[2rem] p-6 border border-gray-100 shadow-sm flex items-center gap-5 hover:border-adzkia-blue/30 hover:shadow-md transition-all">
                <div class="w-16 h-16 bg-gray-50 rounded-xl flex items-center justify-center text-adzkia-dark font-black text-2xl border border-gray-100 shrink-0">
                    #{{ $rank + 1 }}
                </div>
                <div>
                    <h4 class="text-[16px] font-black text-adzkia-dark">{{ $prodi->nama_prodi }}</h4>
                    <p class="text-[11px] font-bold text-gray-400 mt-1 uppercase tracking-widest">Kecocokan AI: {{ $aiScore }}%</p>
                </div>
            </div>
        @endforeach
    </div>

    <div class="flex flex-col sm:flex-row justify-center gap-4 mt-8 border-t border-gray-100 pt-10">
        <button @click="showChatbot = true" class="px-8 py-4 bg-gradient-to-br from-amber-400 to-orange-500 text-white font-black text-[14px] rounded-xl shadow-lg shadow-orange-500/20 hover:scale-105 active:scale-95 transition-all flex justify-center items-center gap-2">
            <i data-feather="message-circle" class="w-5 h-5"></i> Analisis Mendalam via Chatbot AI
        </button>
        <a href="{{ route('register') }}" class="px-8 py-4 bg-adzkia-dark text-white font-black text-[14px] rounded-xl shadow-xl hover:scale-105 active:scale-95 transition-all flex justify-center items-center gap-2">
            Daftar Sekarang <i data-feather="arrow-right" class="w-4 h-4"></i>
        </a>
    </div>

    <div x-show="showChatbot" class="fixed inset-0 z-[100] flex items-center justify-center px-4" style="display: none;" x-cloak>
        <div x-show="showChatbot" x-transition.opacity @click="showChatbot = false" class="absolute inset-0 bg-adzkia-dark/80 backdrop-blur-sm cursor-pointer"></div>
        
        <div x-show="showChatbot" 
             x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 scale-95 translate-y-4" x-transition:enter-end="opacity-100 scale-100 translate-y-0" 
             x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100 scale-100 translate-y-0" x-transition:leave-end="opacity-0 scale-95 translate-y-4" 
             class="bg-white w-full max-w-2xl rounded-[2rem] shadow-2xl relative z-10 flex flex-col h-[80vh]">
            
            <div class="p-6 border-b border-gray-100 flex justify-between items-center bg-gray-50 rounded-t-[2rem]">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-adzkia-blue text-white rounded-full flex items-center justify-center shadow-md">
                        <i data-feather="cpu" class="w-5 h-5"></i>
                    </div>
                    <div>
                        <h3 class="text-md font-black text-adzkia-dark leading-none">Konsultan AI Adzkia</h3>
                        <span class="text-[10px] font-bold text-green-500 uppercase tracking-widest">Online</span>
                    </div>
                </div>
                <button @click="showChatbot = false" class="p-2 hover:bg-gray-200 rounded-full transition-colors">
                    <i data-feather="x" class="text-gray-500 w-5 h-5"></i>
                </button>
            </div>

            <div class="flex-1 overflow-y-auto p-6 bg-adzkia-bg space-y-4">
                <div class="flex gap-3 max-w-[85%]">
                    <div class="w-8 h-8 bg-adzkia-blue text-white rounded-full flex items-center justify-center shrink-0 mt-1">
                        <i data-feather="cpu" class="w-4 h-4"></i>
                    </div>
                    <div class="bg-white p-4 rounded-2xl rounded-tl-none shadow-sm border border-gray-100 text-[13px] font-medium text-gray-600 leading-relaxed">
                        Halo! Saya adalah Konsultan Pendidikan AI Adzkia. Berdasarkan data kuesioner Anda, saya merekomendasikan program studi <strong>{{ $top1['jurusan'] }}</strong>.<br><br>
                        Tingkat kemampuan logika Anda berada di angka rata-rata {{ session('skor_kategori')['logika'] ?? '-' }}, yang sangat cocok untuk menyelesaikan tantangan teknis di program studi ini. Apakah Anda ingin mengetahui materi kuliah apa saja yang akan dipelajari di jurusan ini?
                    </div>
                </div>
                
                </div>

            <div class="p-4 border-t border-gray-100 bg-white rounded-b-[2rem]">
                <form onsubmit="event.preventDefault(); alert('Fitur koneksi API LLM belum diimplementasikan.');" class="flex gap-2 relative">
                    <input type="text" placeholder="Tanyakan seputar jurusan, prospek kerja, atau biaya..." class="w-full px-5 py-3.5 bg-gray-50 border border-gray-200 rounded-xl text-[13px] font-bold text-adzkia-dark focus:ring-2 focus:ring-adzkia-blue focus:border-adzkia-blue transition-all pr-14">
                    <button type="submit" class="absolute right-2 top-2 p-2 bg-adzkia-blue text-white rounded-lg hover:bg-blue-700 transition-colors">
                        <i data-feather="send" class="w-4 h-4"></i>
                    </button>
                </form>
            </div>
        </div>
    </div>

</div>
@endsection