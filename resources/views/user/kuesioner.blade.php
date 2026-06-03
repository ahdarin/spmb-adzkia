@extends('layouts.app')

@section('title', 'Kuesioner Minat & Bakat - SPMB Adzkia')

@section('content')
<div class="max-w-4xl mx-auto px-6 py-12 md:py-16">
    
    <div class="mb-10 text-center">
        <h2 class="text-2xl font-black text-adzkia-dark mb-2">Kuesioner Minat & Bakat</h2>
        <p class="text-[13px] font-bold text-adzkia-blue mb-4 uppercase tracking-widest">Bagian {{ $page }} dari {{ $totalPages }}</p>
        
        <div class="w-full bg-gray-100 rounded-full h-2.5 max-w-xl mx-auto overflow-hidden">
            <div class="bg-gradient-to-r from-adzkia-blue to-indigo-500 h-2.5 rounded-full transition-all duration-500 ease-out" style="width: {{ ($page / $totalPages) * 100 }}%"></div>
        </div>
    </div>

    <form action="{{ route('rekomendasi.kuesioner.submit') }}" method="POST">
        @csrf
        <input type="hidden" name="page" value="{{ $page }}">
        
        <div class="space-y-6">
            @foreach($currentQuestions as $index => $q)
                <div class="bg-white rounded-[2rem] p-6 md:p-8 border border-gray-100 shadow-sm transition-all hover:shadow-md">
                    <h3 class="text-[16px] font-extrabold text-adzkia-dark leading-relaxed mb-5">
                        <span class="text-adzkia-blue mr-2">{{ ($page - 1) * 5 + $loop->iteration }}.</span> {{ $q['text'] }}
                    </h3>
                    
                    <div class="grid grid-cols-5 gap-2 md:gap-4">
                        @for($i = 1; $i <= 5; $i++)
                            <label class="cursor-pointer">
                                <input type="radio" name="jawaban[{{ $q['id'] }}]" value="{{ $i }}" class="peer sr-only" required>
                                <div class="h-12 md:h-14 flex items-center justify-center rounded-xl border-2 border-gray-100 bg-gray-50 
                                            peer-checked:bg-adzkia-blue peer-checked:border-adzkia-blue peer-checked:text-white 
                                            hover:border-adzkia-blue/30 hover:bg-blue-50 transition-all text-sm md:text-lg font-black text-gray-400">
                                    {{ $i }}
                                </div>
                                @if($i == 1)
                                    <p class="text-[9px] md:text-[10px] font-bold text-gray-400 text-center mt-2 uppercase">Sangat Tidak Setuju</p>
                                @elseif($i == 5)
                                    <p class="text-[9px] md:text-[10px] font-bold text-gray-400 text-center mt-2 uppercase">Sangat Setuju</p>
                                @endif
                            </label>
                        @endfor
                    </div>
                </div>
            @endforeach
        </div>
        
        <div class="mt-10 flex justify-between items-center max-w-4xl mx-auto">
            @if($page > 1)
                <button type="button" onclick="history.back()" class="px-6 py-4 bg-white border border-gray-200 text-adzkia-dark font-black text-[14px] rounded-xl hover:bg-gray-50 transition-all flex items-center gap-2">
                    <i data-feather="arrow-left" class="w-4 h-4"></i> Kembali
                </button>
            @else
                <div></div>
            @endif

            <button type="submit" class="px-8 py-4 bg-adzkia-red text-white font-black text-[14px] rounded-xl shadow-lg shadow-red-500/20 hover:scale-105 active:scale-95 transition-all flex items-center gap-2">
                {{ $page == $totalPages ? 'Lihat Hasil Prediksi' : 'Lanjut ke Pertanyaan Berikutnya' }} <i data-feather="{{ $page == $totalPages ? 'check-circle' : 'arrow-right' }}" class="w-4 h-4"></i>
            </button>
        </div>
    </form>
</div>
@endsection