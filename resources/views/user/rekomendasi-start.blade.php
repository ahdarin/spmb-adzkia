@extends('layouts.app')

@section('title', 'Mulai Tes Rekomendasi - SPMB Adzkia')

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

<div class="bg-adzkia-bg antialiased text-adzkia-dark min-h-screen flex flex-col relative" x-data="{ selected: null }">

    <header class="w-full px-8 py-6 flex items-center">
        <a href="{{ url('/') }}" class="p-2 text-adzkia-blue hover:bg-adzkia-badge-bg rounded-full transition-colors">
            <i data-feather="arrow-left" class="w-6 h-6"></i>
        </a>
    </header>

    <main class="flex-1 flex flex-col items-center justify-start px-6 pt-4 pb-20 w-full max-w-5xl mx-auto">
        
        <div class="text-center mb-12">
            <h1 class="text-[32px] md:text-[40px] font-extrabold text-adzkia-blue tracking-tight mb-4">Pilih Jurusan yang Anda Minati</h1>
            <p class="text-[15px] font-medium text-gray-500 max-w-lg mx-auto leading-relaxed">
                Pilih satu program studi <span class="font-extrabold text-adzkia-blue">S1</span> yang ada di Universitas Adzkia yang paling menarik bagi Anda sebelum memulai tes rekomendasi.
            </p>
        </div>

        <form action="{{ route('rekomendasi.start.submit') }}" method="POST" class="w-full">
            @csrf
            <input type="hidden" name="minat_jurusan" :value="selected">

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 w-full mb-12">
                @foreach($prodis->where('jenjang', 'S1') as $prodi)
                    <div @click="selected = '{{ $prodi->id }}'" 
                         class="relative p-8 bg-white border-2 rounded-[2rem] cursor-pointer transition-all duration-300 flex flex-col items-center text-center group shadow-sm"
                         :class="selected == '{{ $prodi->id }}' ? 'border-adzkia-blue shadow-xl -translate-y-1' : 'border-gray-100 hover:border-adzkia-blue hover:shadow-xl hover:-translate-y-1'">
                        
                        <div x-show="selected == '{{ $prodi->id }}'" 
                             x-transition.scale.origin.center
                             class="absolute top-4 right-4 w-6 h-6 bg-adzkia-red text-white rounded-full flex items-center justify-center shadow-md" style="display: none;">
                            <i data-feather="check" class="w-3.5 h-3.5"></i>
                        </div>

                        <div class="w-14 h-14 rounded-[14px] flex items-center justify-center mb-5 transition-colors duration-300"
                             :class="selected == '{{ $prodi->id }}' ? 'bg-adzkia-blue text-white' : 'bg-adzkia-badge-bg text-adzkia-dark group-hover:bg-adzkia-blue group-hover:text-white'">
                            <i data-feather="{{ $prodi->icon ?? 'book-open' }}" class="w-6 h-6 transition-colors"></i>
                        </div>

                        <h3 class="text-[15px] font-extrabold transition-colors"
                            :class="selected == '{{ $prodi->id }}' ? 'text-adzkia-red' : 'text-adzkia-dark group-hover:text-adzkia-red'">
                            {{ $prodi->nama }}
                        </h3>
                    </div>
                @endforeach
                {{-- asdf --}}
            </div>

            <div class="flex flex-col items-center gap-4">
                <p class="text-[13px] font-extrabold text-adzkia-red flex items-center gap-1.5 transition-opacity duration-300"
                   :class="selected ? 'opacity-0' : 'opacity-100'">
                    <i data-feather="info" class="w-4 h-4"></i> Silakan pilih 1 program studi
                </p>

                <button type="submit" 
                        :disabled="!selected"
                        :class="selected ? 'bg-adzkia-red text-white hover:bg-red-700 hover:shadow-lg hover:-translate-y-0.5' : 'bg-gray-200 text-gray-400 cursor-not-allowed'"
                        class="px-10 py-4 rounded-2xl font-black text-[15px] transition-all duration-300 w-full sm:w-auto min-w-[200px] flex justify-center items-center gap-2">
                    Selanjutnya <i data-feather="arrow-right" class="w-4 h-4"></i>
                </button>

                <div class="px-4 py-1.5 bg-adzkia-badge-bg text-adzkia-badge-txt rounded-full text-[11px] font-black uppercase tracking-widest transition-opacity"
                     x-show="selected" style="display: none;">
                    1 Jurusan Terpilih
                </div>
            </div>
        </form>

    </main>

    <section class="w-full bg-white py-16 mt-10 border-t border-gray-100">
        <div class="max-w-6xl mx-auto px-6 grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
            <div class="max-w-md">
                <h2 class="text-[2rem] font-extrabold text-adzkia-blue leading-tight mb-4">Masa depan dimulai dengan pilihan yang tepat.</h2>
                <p class="text-[14px] font-medium text-gray-500 leading-relaxed">
                    Tim kurator AI kami telah menyusun instrumen evaluasi berdasarkan karakteristik akademik dan non-akademik. Pilihan awal Anda akan menjadi baseline bagi sistem untuk menganalisis kecocokan Anda di tahapan selanjutnya.
                </p>
            </div>
            <div class="grid grid-cols-2 gap-4">
                <img src="{{ asset('images/gedung-adzkia.png') }}" alt="Kampus Adzkia" class="rounded-[2rem] rounded-tr-[4rem] w-full h-64 object-cover shadow-sm">
                <img src="https://images.unsplash.com/photo-1523240795612-9a054b0db644?auto=format&fit=crop&w=400&q=80" alt="Mahasiswa" class="rounded-[2rem] rounded-bl-[4rem] w-full h-64 object-cover shadow-sm mt-8">
            </div>
        </div>
    </section>
</div>

<script>
    // Inisialisasi Feather Icons
    document.addEventListener('DOMContentLoaded', () => {
        feather.replace();
    });
</script>
@endsection