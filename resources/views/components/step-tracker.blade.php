@props([
    'currentStep',
    'steps' => null, // kalau tidak dikirim, pakai default 7-step di bawah
])

@php
    $steps = $steps ?? [
        ['id' => 1, 'title' => 'Pendaftaran'],
        ['id' => 2, 'title' => 'Biaya'],
        ['id' => 3, 'title' => 'Validasi'],
        ['id' => 4, 'title' => 'Biodata'],
        ['id' => 5, 'title' => 'Konfirmasi'],
        ['id' => 6, 'title' => 'Cek Admin'],
        ['id' => 7, 'title' => 'Hasil'],
    ];
@endphp

<div class="w-full bg-white py-4 sm:py-6 border-b border-gray-100 z-20">
    <div class="max-w-5xl mx-auto px-4 sm:px-6">
        <div class="flex items-center justify-between relative">
            <div class="absolute top-[18px] sm:top-1/2 left-0 w-full h-0.5 bg-gray-100 -translate-y-1/2 z-0"></div>

            @foreach($steps as $step)
                @php
                    $isDone    = $step['id'] < $currentStep;
                    $isCurrent = $step['id'] == $currentStep;
                @endphp
                <div class="relative z-10 flex flex-col items-center gap-1 sm:gap-2">
                    <div class="w-8 h-8 sm:w-10 sm:h-10 rounded-full flex items-center justify-center font-bold text-[11px] sm:text-[13px] transition-all duration-300
                        {{ $isCurrent
                            ? 'bg-adzkia-blue text-white shadow-lg shadow-adzkia-blue/30 scale-110'
                            : ($isDone
                                ? 'bg-green-500 text-white border-2 border-green-500'
                                : 'bg-white border-2 border-gray-100 text-gray-400') }}">
                        @if($isDone)
                            <i data-feather="check" class="w-3 h-3 sm:w-4 sm:h-4"></i>
                        @else
                            {{ $step['id'] }}
                        @endif
                    </div>
                    <span class="text-[9px] font-black uppercase tracking-widest hidden md:block
                        {{ $isCurrent ? 'text-adzkia-blue' : ($isDone ? 'text-green-500' : 'text-gray-400') }}">
                        {{ $step['title'] }}
                    </span>
                </div>
            @endforeach
        </div>
    </div>
</div>