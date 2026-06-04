@extends('layouts.app')

@section('title', 'Hasil Rekomendasi Program Studi - SPMB Adzkia')

@section('content')
<!-- Script Color Palette Tailwind -->
<script>
    tailwind.config = {
        theme: {
            extend: {
                fontFamily: { sans: ['Manrope', 'sans-serif'] },
                colors: {
                    'adzkia-red': '#d9241c',
                    'adzkia-blue': '#2c7ebd',
                    'adzkia-dark': '#1e293b',
                    'adzkia-muted': '#64748b',
                    'adzkia-badge-bg': '#eff6ff',
                    'adzkia-badge-txt': '#2c7ebd',
                    'adzkia-bg': '#FAFBFC',
                }
            }
        }
    }
</script>

<script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

@php
    $top1 = $hasil[0];
@endphp

<div class="bg-adzkia-bg antialiased text-adzkia-dark min-h-screen relative" x-data="rekomendasiResult()">

    <!-- ==========================================
         LOADING INTERFACE 
    =========================================== -->
    <div x-show="isLoading" 
         x-transition:leave="transition ease-in duration-500"
         x-transition:leave-start="opacity-100 scale-100"
         x-transition:leave-end="opacity-0 scale-95"
         class="fixed inset-0 z-50 flex flex-col items-center justify-center bg-gradient-to-b from-white to-[#F0F4F8] px-6">
        
        <div class="w-16 h-16 bg-adzkia-dark rounded-2xl text-white flex items-center justify-center mb-6 shadow-xl shadow-adzkia-dark/20 animate-bounce">
            <i data-feather="cpu" class="w-8 h-8"></i>
        </div>
        <h2 class="text-xl font-black text-adzkia-dark mb-12">Universitas Adzkia</h2>

        <div class="w-full max-w-md mb-6">
            <div class="h-1.5 w-full bg-gray-200 rounded-full overflow-hidden">
                <div class="h-full bg-adzkia-dark transition-all duration-300 ease-out" :style="`width: ${progress}%`"></div>
            </div>
        </div>

        <div class="flex items-center gap-6 text-[10px] font-black uppercase tracking-widest text-gray-400 mb-10">
            <span class="flex items-center gap-1.5" :class="progress > 10 ? 'text-adzkia-blue' : ''">
                <i data-feather="activity" class="w-3.5 h-3.5"></i> Menganalisis Kognitif
            </span>
            <span class="flex items-center gap-1.5" :class="progress > 60 ? 'text-adzkia-blue' : ''">
                <i data-feather="cpu" class="w-3.5 h-3.5"></i> Memetakan Profil
            </span>
        </div>

        <div class="text-center max-w-lg mb-12">
            <h1 class="text-3xl md:text-4xl font-black text-adzkia-dark tracking-tight mb-4">Sedang Menganalisis Jawaban Kamu...</h1>
            <p class="text-[14px] font-medium text-gray-500 leading-relaxed">
                Sistem kurasi kami sedang memetakan profil kognitif Anda ke dalam 16 profil akademik terbaik untuk memberikan rekomendasi yang paling akurat.
            </p>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 w-full max-w-2xl mb-12">
            <div class="bg-white py-5 rounded-2xl shadow-sm border border-gray-100 flex flex-col items-center text-center">
                <h3 class="text-xl font-black text-adzkia-dark mb-1">16</h3>
                <p class="text-[10px] font-extrabold text-gray-400 uppercase tracking-widest">Matriks Profil</p>
            </div>
            <div class="bg-white py-5 rounded-2xl shadow-sm border border-gray-100 flex flex-col items-center text-center">
                <h3 class="text-xl font-black text-adzkia-dark mb-1">AI-Powered</h3>
                <p class="text-[10px] font-extrabold text-gray-400 uppercase tracking-widest">Metode Kurasi</p>
            </div>
            <div class="bg-white py-5 rounded-2xl shadow-sm border border-gray-100 flex flex-col items-center text-center">
                <h3 class="text-xl font-black text-adzkia-dark mb-1" x-text="Math.floor(progress * 0.98) + '%'"></h3>
                <p class="text-[10px] font-extrabold text-gray-400 uppercase tracking-widest">Akurasi Prediksi</p>
            </div>
        </div>

        <p class="text-[12px] font-bold text-gray-400 animate-pulse">Tunggu sebentar, kami sedang membangun masa depan akademis Anda.</p>
    </div>


    <!-- ==========================================
         MAIN CONTENT (HASIL REKOMENDASI) 
    =========================================== -->
    <div x-show="!isLoading" 
         x-transition:enter="transition ease-out duration-700 delay-300"
         x-transition:enter-start="opacity-0 translate-y-10"
         x-transition:enter-end="opacity-100 translate-y-0"
         class="max-w-6xl mx-auto px-6 py-12" x-cloak>
        
        <div class="text-center mb-10">
            <span class="inline-block px-4 py-1.5 bg-adzkia-badge-bg text-adzkia-badge-txt text-[11px] font-extrabold rounded-full uppercase tracking-widest mb-3">Analisis Kecerdasan Buatan Selesai</span>
            <h2 class="text-3xl font-black text-adzkia-dark">Hasil Rekomendasi AI</h2>
        </div>

        <!-- Section Profil Karakteristik -->
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

        <!-- Top 1 Rekomendasi -->
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

        <!-- Top 2 & 3 Rekomendasi Alternatif -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-12">
            <!-- Gunakan ->values() agar index kembali menjadi 0 & 1 setelah di-slice -->
            @foreach($sortedTopProdis->slice(1, 2)->values() as $index => $prodi)
                @php 
                    $rank = $index + 2; // Rank 2 dan 3
                    $aiScoreData = collect($hasil)->firstWhere('jurusan', $prodi->nama);
                    $aiScore = $aiScoreData ? $aiScoreData['score'] : 'N/A';
                @endphp
                <div class="bg-white rounded-[2rem] p-6 border border-gray-100 shadow-sm flex items-center gap-5 hover:border-adzkia-blue/30 hover:shadow-md transition-all">
                    <div class="w-16 h-16 bg-gray-50 rounded-xl flex items-center justify-center text-adzkia-dark font-black text-2xl border border-gray-100 shrink-0">
                        #{{ $rank }}
                    </div>
                    <div>
                        <h4 class="text-[16px] font-black text-adzkia-dark">{{ $prodi->nama }}</h4>
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

        <!-- ==========================================
             MODAL CHATBOT 
        =========================================== -->
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
                            Tingkat kemampuan <strong>{{ $topTraits[0] }}</strong> Anda berada di angka rata-rata {{ session('skor_kategori')[$topTraits[0]] ?? '-' }}, yang sangat cocok untuk menyelesaikan tantangan di program studi ini. Apakah Anda ingin mengetahui materi kuliah apa saja yang akan dipelajari di jurusan ini?
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

</div>

<script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('rekomendasiResult', () => ({
            isLoading: true,
            progress: 0,
            showChatbot: false,

            init() {
                // Simulasi proses analisis AI
                let interval = setInterval(() => {
                    this.progress += Math.floor(Math.random() * 15) + 5; 
                    
                    if (this.progress >= 100) {
                        this.progress = 100;
                        clearInterval(interval);
                        
                        setTimeout(() => {
                            this.isLoading = false;
                            
                            this.$nextTick(() => {
                                if(window.feather) feather.replace();
                            });
                            
                        }, 500); 
                    }
                }, 300); 
            }
        }));
    });

    document.addEventListener('DOMContentLoaded', () => {
        if(window.feather) feather.replace();
    });
</script>
@endsection