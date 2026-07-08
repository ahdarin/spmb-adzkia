@extends('layouts.app')

@section('title', 'Kuesioner Minat & Bakat - SPMB Adzkia')

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

{{-- Ubah: padding lebih kecil di mobile --}}
<div class="max-w-4xl mx-auto px-4 sm:px-6 py-8 sm:py-12 md:py-16">
    
    {{-- HEADER PROGRESS --}}
    <div class="mb-7 sm:mb-10 text-center">
        <h2 class="text-xl sm:text-2xl font-black text-adzkia-dark mb-1.5 sm:mb-2">Kuesioner Minat & Bakat</h2>
        <p class="text-[12px] sm:text-[13px] font-bold text-adzkia-blue mb-3 sm:mb-4 uppercase tracking-widest">Bagian {{ $page }} dari {{ $totalPages }}</p>
        
        <div class="w-full bg-gray-100 rounded-full h-2 sm:h-2.5 max-w-xl mx-auto overflow-hidden">
            <div class="bg-gradient-to-r from-adzkia-blue to-indigo-500 h-2 sm:h-2.5 rounded-full transition-all duration-500 ease-out" style="width: {{ ($page / $totalPages) * 100 }}%"></div>
        </div>
    </div>

    <form action="{{ route('rekomendasi.kuesioner.submit') }}" method="POST">
        @csrf
        <input type="hidden" name="page" value="{{ $page }}">
        
        <div class="space-y-4 sm:space-y-6">
            @foreach($pagedQuestions as $index => $q)
                {{-- Ubah: padding lebih kecil di mobile --}}
                <div class="bg-white rounded-2xl sm:rounded-[2rem] p-4 sm:p-6 md:p-8 border border-gray-100 shadow-sm transition-all hover:shadow-md">
                    <h3 class="text-[14px] sm:text-[16px] font-extrabold text-adzkia-dark leading-relaxed mb-4 sm:mb-5">
                        <span class="text-adzkia-blue mr-1.5 sm:mr-2">{{ ($page - 1) * 5 + $loop->iteration }}.</span> {{ $q['text'] }}
                    </h3>
                    
                    {{-- Ubah: kotak pilihan skala lebih kompak di mobile --}}
                    <div class="grid grid-cols-5 gap-1.5 sm:gap-2 md:gap-4">
                        @for($i = 1; $i <= 5; $i++)
                            @php
                                $isChecked = isset($savedAnswers[$q['id']]) && $savedAnswers[$q['id']] == $i;
                            @endphp
                            <label class="cursor-pointer">
                                <input type="radio" name="jawaban[{{ $q['id'] }}]" value="{{ $i }}" class="peer sr-only" required {{ $isChecked ? 'checked' : '' }}>
                                {{-- Ubah: tinggi kotak lebih kecil di mobile --}}
                                <div class="h-10 sm:h-12 md:h-14 flex items-center justify-center rounded-lg sm:rounded-xl border-2 border-gray-100 bg-gray-50 
                                            peer-checked:bg-adzkia-blue peer-checked:border-adzkia-blue peer-checked:text-white 
                                            hover:border-adzkia-blue/30 hover:bg-blue-50 transition-all text-xs sm:text-sm md:text-lg font-black text-gray-400">
                                    {{ $i }}
                                </div>
                                {{-- Ubah: label skala lebih kecil, hanya di ujung --}}
                                @if($i == 1)
                                    <p class="text-[8px] sm:text-[9px] md:text-[10px] font-bold text-gray-400 text-center mt-1.5 sm:mt-2 uppercase leading-tight">Sangat Tidak Setuju</p>
                                @elseif($i == 5)
                                    <p class="text-[8px] sm:text-[9px] md:text-[10px] font-bold text-gray-400 text-center mt-1.5 sm:mt-2 uppercase leading-tight">Sangat Setuju</p>
                                @endif
                            </label>
                        @endfor
                    </div>
                </div>
            @endforeach
        </div>
        
        {{-- TOMBOL NAVIGASI --}}
        {{-- Ubah: di mobile tombol full width dan disusun vertikal jika perlu --}}
        <div class="mt-7 sm:mt-10 flex flex-col-reverse sm:flex-row justify-between items-stretch sm:items-center gap-3 sm:gap-4 max-w-4xl mx-auto">
            @if($page > 1)
                <a href="{{ route('rekomendasi.kuesioner', ['page' => $page - 1]) }}" 
                   class="px-5 sm:px-6 py-3.5 sm:py-4 bg-white border border-gray-200 text-adzkia-dark font-black text-[13px] sm:text-[14px] rounded-xl hover:bg-gray-50 transition-all flex items-center justify-center gap-2">
                    <i data-feather="arrow-left" class="w-4 h-4"></i> Kembali
                </a>
            @else
                {{-- Spacer agar tombol submit tetap di kanan pada desktop --}}
                <div class="hidden sm:block"></div>
            @endif

            <button type="submit" 
                    class="px-6 sm:px-8 py-3.5 sm:py-4 bg-adzkia-red text-white font-black text-[13px] sm:text-[14px] rounded-xl shadow-lg shadow-red-500/20 hover:scale-105 active:scale-95 transition-all flex items-center justify-center gap-2 w-full sm:w-auto">
                {{ $page == $totalPages ? 'Lihat Hasil Prediksi' : 'Lanjut ke Pertanyaan Berikutnya' }} 
                <i data-feather="{{ $page == $totalPages ? 'check-circle' : 'arrow-right' }}" class="w-4 h-4"></i>
            </button>
        </div>
    </form>
</div>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        if(window.feather) feather.replace();
    });
</script>
@endsection