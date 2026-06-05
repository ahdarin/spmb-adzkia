@extends('layouts.app')

@section('title', 'Hasil Rekomendasi Program Studi - SPMB Adzkia')

@section('content')
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
    $top1 = $hasil[0] ?? ['jurusan' => 'Belum ada data', 'score' => 0];
@endphp

<div class="bg-adzkia-bg antialiased text-adzkia-dark min-h-screen relative" x-data="rekomendasiResult()">

    <div class="min-h-screen flex flex-col">
        
        <header class="w-full max-w-6xl mx-auto px-6 py-8 flex flex-col gap-8">
            <a href="{{ url('/') }}" class="w-10 h-10 flex items-center justify-center text-adzkia-dark hover:bg-gray-200 bg-white rounded-full shadow-sm transition-colors">
                <i data-feather="arrow-left" class="w-6 h-6"></i>
            </a>
            
            <div class="max-w-2xl">
                <span class="inline-block px-4 py-1.5 bg-adzkia-badge-bg text-adzkia-badge-txt text-[11px] font-black rounded-full uppercase tracking-widest mb-3 border border-blue-100">Analisis Kecerdasan Buatan Selesai</span>
                <h1 class="text-3xl md:text-4xl font-black text-adzkia-dark tracking-tight mb-3">Hasil Rekomendasi Jurusan Kamu</h1>
                <p class="text-[14px] md:text-[15px] font-medium text-adzkia-muted leading-relaxed">
                    Berdasarkan analisis mendalam terhadap minat, bakat, dan pola jawaban yang telah kamu berikan, tim kurasi kami telah merumuskan jalur akademik yang paling sesuai untuk potensi masa depanmu.
                </p>
            </div>
        </header>

        <main class="flex-1 w-full max-w-6xl mx-auto px-6 pb-20 grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
            
            <div class="lg:col-span-8 space-y-6">
                
                <div class="bg-white p-8 md:p-10 rounded-[2.5rem] shadow-sm border border-gray-100 relative overflow-hidden group">
                    <div class="absolute top-0 right-0 p-8 opacity-5 transition-transform duration-500 group-hover:scale-110">
                        <i data-feather="award" class="w-48 h-48"></i>
                    </div>
                    
                    <div class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-adzkia-badge-bg text-adzkia-blue rounded-lg text-[11px] font-black tracking-widest mb-6 relative z-10">
                        <i data-feather="star" class="w-3.5 h-3.5 fill-current"></i> {{ $top1['score'] }}% Match
                    </div>
                    
                    <h2 class="text-4xl md:text-5xl font-black text-adzkia-dark tracking-tight mb-6 relative z-10">{{ $top1['jurusan'] }}</h2>
                    
                    <p class="text-[15px] font-medium text-gray-500 leading-relaxed mb-10 relative z-10 max-w-2xl">
                        Karakteristik Anda sangat selaras dengan lingkungan akademis dan prospek karir pada program studi ini. Sistem kami mengidentifikasi potensi besar Anda untuk berkembang secara optimal di bidang ini.
                    </p>
                    
                    <div class="flex flex-wrap items-center gap-4 relative z-10">
                        <a href="{{ route('register') }}" class="px-8 py-4 bg-adzkia-dark text-white rounded-2xl font-black text-[14px] hover:bg-adzkia-blue shadow-lg shadow-adzkia-dark/20 transition-all hover:-translate-y-1">
                            Daftar Sekarang
                        </a>
                        <button @click="showChatbot = true" class="px-8 py-4 bg-gray-100 text-adzkia-dark rounded-2xl font-black text-[14px] hover:bg-gray-200 transition-all flex items-center gap-2">
                            <i data-feather="message-circle" class="w-4 h-4"></i> Analisis Mendalam via AI
                        </button>
                    </div>
                </div>

                <div class="bg-[#F8FAFC] p-8 md:p-10 rounded-[2.5rem] border border-gray-200">
                    <div class="flex items-center gap-4 mb-8">
                        <div class="w-12 h-12 bg-adzkia-dark text-white rounded-2xl flex items-center justify-center shadow-md">
                            <i data-feather="user" class="w-6 h-6"></i>
                        </div>
                        <h3 class="text-xl font-black text-adzkia-dark">Profil Karakteristik Dominan</h3>
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        @foreach(array_slice($topTraits ?? [], 0, 4) as $trait)
                        <div>
                            <h4 class="text-[13px] font-black text-adzkia-dark mb-2 flex items-center gap-2 uppercase tracking-wide">
                                <i data-feather="check-square" class="w-4 h-4 text-adzkia-blue"></i> {{ $trait }}
                            </h4>
                            <p class="text-[12px] font-medium text-gray-500 leading-relaxed">
                                Skor rata-rata Anda pada aspek ini adalah <strong class="text-adzkia-dark">{{ session('skor_kategori')[$trait] ?? '-' }}</strong>. Hal ini menunjukkan kekuatan fundamental Anda di bidang tersebut.
                            </p>
                        </div>
                        @endforeach
                    </div>
                </div>

            </div>

            <div class="lg:col-span-4 space-y-4">
                <h3 class="text-[16px] font-black text-adzkia-dark mb-4">Alternatif Terbaik</h3>
                
                @for($i = 1; $i < min(3, count($hasil)); $i++)
                    @php 
                        $rek = $hasil[$i];
                        $prodi = isset($sortedTopProdis) ? $sortedTopProdis->firstWhere('nama', $rek['jurusan']) : null;
                        $icon = $prodi && isset($prodi->icon) ? $prodi->icon : 'book-open';
                    @endphp
                    <div class="bg-white p-6 rounded-3xl border border-gray-100 shadow-sm hover:shadow-md transition-all cursor-pointer group">
                        <div class="flex justify-between items-start mb-4">
                            <div class="w-10 h-10 bg-adzkia-badge-bg text-adzkia-blue rounded-xl flex items-center justify-center">
                                <i data-feather="{{ $icon }}" class="w-5 h-5"></i>
                            </div>
                        </div>
                        <h4 class="text-[15px] font-black text-adzkia-dark mb-2">{{ $rek['jurusan'] }}</h4>
                    </div>
                @endfor
            </div>

        </main>
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

            <div class="flex-1 overflow-y-auto p-6 bg-adzkia-bg space-y-4" id="chat-container">
                <div class="flex gap-3 max-w-[85%]">
                    <div class="w-8 h-8 bg-adzkia-blue text-white rounded-full flex items-center justify-center shrink-0 mt-1">
                        <i data-feather="cpu" class="w-4 h-4"></i>
                    </div>
                    <div class="bg-white p-4 rounded-2xl rounded-tl-none shadow-sm border border-gray-100 text-[13px] font-medium text-gray-600 leading-relaxed">
                        Halo! Saya adalah Konsultan Pendidikan AI Adzkia. Berdasarkan data kuesioner Anda, saya merekomendasikan program studi <strong>{{ $top1['jurusan'] }}</strong>.<br><br>
                        Apakah Anda ingin mengetahui prospek kerja, materi kuliah, atau alasan spesifik mengapa jurusan ini cocok untuk Anda?
                    </div>
                </div>

                <template x-for="(msg, index) in chatMessages" :key="index">
                    <div :class="msg.role === 'user' ? 'flex gap-3 max-w-[85%] ml-auto justify-end' : 'flex gap-3 max-w-[85%]'">
                        <div x-show="msg.role === 'ai'" class="w-8 h-8 bg-adzkia-blue text-white rounded-full flex items-center justify-center shrink-0 mt-1">
                            <i data-feather="cpu" class="w-4 h-4"></i>
                        </div>
                        
                        <div :class="msg.role === 'user' ? 'bg-adzkia-dark text-white rounded-tr-none' : 'bg-white text-gray-600 rounded-tl-none border border-gray-100'" 
                             class="p-4 rounded-2xl shadow-sm text-[13px] font-medium leading-relaxed whitespace-pre-wrap" x-text="msg.text">
                        </div>
                    </div>
                </template>

                <div x-show="isTyping" class="flex gap-3 max-w-[85%]">
                    <div class="w-8 h-8 bg-adzkia-blue text-white rounded-full flex items-center justify-center shrink-0 mt-1">
                        <i data-feather="cpu" class="w-4 h-4"></i>
                    </div>
                    <div class="bg-white px-5 py-4 rounded-2xl rounded-tl-none shadow-sm border border-gray-100 flex gap-1 items-center">
                        <div class="w-2 h-2 bg-gray-400 rounded-full animate-bounce"></div>
                        <div class="w-2 h-2 bg-gray-400 rounded-full animate-bounce" style="animation-delay: 0.2s"></div>
                        <div class="w-2 h-2 bg-gray-400 rounded-full animate-bounce" style="animation-delay: 0.4s"></div>
                    </div>
                </div>
            </div>

            <div class="p-4 border-t border-gray-100 bg-white rounded-b-[2rem]">
                <form @submit.prevent="sendMessage" class="flex gap-2 relative">
                    <input type="text" x-model="userInput" :disabled="isTyping" placeholder="Tanyakan seputar jurusan, prospek kerja, atau biaya..." class="w-full px-5 py-3.5 bg-gray-50 border border-gray-200 rounded-xl text-[13px] font-bold text-adzkia-dark focus:ring-2 focus:ring-adzkia-blue focus:border-adzkia-blue transition-all pr-14 disabled:opacity-50">
                    
                    <button type="submit" :disabled="isTyping || userInput.trim() === ''" class="absolute right-2 top-2 h-10 w-10 flex items-center justify-center bg-adzkia-blue text-white rounded-lg hover:bg-blue-700 transition-colors disabled:opacity-50 disabled:cursor-not-allowed">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="22" y1="2" x2="11" y2="13"></line><polygon points="22 2 15 22 11 13 2 9 22 2"></polygon></svg>
                    </button>
                </form>
            </div>
        </div>
    </div>

</div>

<script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('rekomendasiResult', () => ({
            showChatbot: false,
            userInput: '',
            isTyping: false,
            chatMessages: [],
            jurusanRekomendasi: '{{ $top1['jurusan'] }}',

            async sendMessage() {
                if (this.userInput.trim() === '') return;

                const message = this.userInput;
                this.chatMessages.push({ role: 'user', text: message });
                this.userInput = ''; 
                this.isTyping = true; 
                this.scrollToBottom();

                try {
                    const response = await fetch('{{ route('rekomendasi.chat.ai') }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: JSON.stringify({
                            message: message,
                            jurusan: this.jurusanRekomendasi
                        })
                    });

                    const data = await response.json();

                    if (data.success) {
                        this.chatMessages.push({ role: 'ai', text: data.reply });
                    } else {
                        this.chatMessages.push({ role: 'ai', text: 'Maaf, sistem AI gagal merespon: ' + data.message });
                    }
                } catch (error) {
                    this.chatMessages.push({ role: 'ai', text: 'Koneksi terputus. Pastikan server Ollama sedang berjalan di terminal.' });
                } finally {
                    this.isTyping = false;
                    this.scrollToBottom();
                    this.$nextTick(() => { if(window.feather) feather.replace(); });
                }
            },

            scrollToBottom() {
                this.$nextTick(() => {
                    const container = document.getElementById('chat-container');
                    if(container) container.scrollTop = container.scrollHeight;
                });
            }
        }));
    });

    document.addEventListener('DOMContentLoaded', () => {
        if(window.feather) feather.replace();
    });
</script>
@endsection