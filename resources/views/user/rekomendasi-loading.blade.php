@extends('layouts.app')

@section('title', 'Menganalisis Jawaban - SPMB Adzkia')

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

<div class="bg-adzkia-bg antialiased min-h-screen relative flex flex-col items-center justify-center px-6" x-data="rekomendasiLoading()">
    
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
            Sistem kurasi kami sedang memetakan profil kognitif Anda ke dalam profil akademik terbaik untuk memberikan rekomendasi yang paling akurat.
        </p>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 w-full max-w-2xl mb-12">
        <div class="bg-white py-5 rounded-2xl shadow-sm border border-gray-100 flex flex-col items-center text-center">
            <h3 class="text-xl font-black text-adzkia-dark mb-1">AI</h3>
            <p class="text-[10px] font-extrabold text-gray-400 uppercase tracking-widest">Matriks Profil</p>
        </div>
        <div class="bg-white py-5 rounded-2xl shadow-sm border border-gray-100 flex flex-col items-center text-center">
            <h3 class="text-xl font-black text-adzkia-dark mb-1">Prediksi</h3>
            <p class="text-[10px] font-extrabold text-gray-400 uppercase tracking-widest">Metode Kurasi</p>
        </div>
        <div class="bg-white py-5 rounded-2xl shadow-sm border border-gray-100 flex flex-col items-center text-center">
            <h3 class="text-xl font-black text-adzkia-dark mb-1" x-text="progress + '%'"></h3>
            <p class="text-[10px] font-extrabold text-gray-400 uppercase tracking-widest">Kurasi Data</p>
        </div>
    </div>

    <p class="text-[12px] font-bold text-gray-400 animate-pulse" x-text="pesanStatus"></p>

</div>

<script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('rekomendasiLoading', () => ({
            progress: 0,
            pesanStatus: 'Tunggu sebentar, kami sedang membangun masa depan akademis Anda.',

            init() {
                // 1. Animasi bayangan sampai 90%
                let interval = setInterval(() => {
                    if (this.progress < 90) {
                        this.progress += Math.floor(Math.random() * 5) + 2;
                    }
                }, 500);

                // 2. Request AJAX eksekusi AI backend
                fetch('{{ route('rekomendasi.proses') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if(data.success) {
                        clearInterval(interval);
                        this.progress = 100;
                        this.pesanStatus = 'Analisis Selesai! Mengarahkan ke hasil...';
                        
                        setTimeout(() => {
                            window.location.href = '{{ route('rekomendasi.hasil') }}';
                        }, 500);
                    } else {
                        alert('Error: ' + data.message);
                        window.location.href = '{{ route('rekomendasi.kuesioner', ['page' => 1]) }}';
                    }
                })
                .catch(error => {
                    alert('Terjadi kesalahan sistem saat memproses AI.');
                    window.location.href = '{{ route('rekomendasi.kuesioner', ['page' => 1]) }}';
                });
            }
        }));
    });

    document.addEventListener('DOMContentLoaded', () => {
        if(window.feather) feather.replace();
    });
</script>
@endsection