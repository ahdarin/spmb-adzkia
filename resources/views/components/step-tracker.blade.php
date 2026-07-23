{{--
    ✅ FIX M5: Tambah `sticky` dan `top-[var(--navbar-h)]` pada wrapper.
    
    Sebelumnya: class="w-full bg-white py-4 sm:py-6 border-b border-gray-100 z-20"
    z-20 ada tapi TIDAK ADA positioning → sticky tidak aktif.
    
    Sesudah:
    - `sticky top-[64px]` → menempel tepat di bawah navbar (navbar height = 64px)
      Gunakan CSS variable --navbar-h jika tinggi navbar dinamis.
    - `z-20` tetap ada agar tidak tertutup konten halaman.
    - `shadow-sm` ditambah agar ada visual separator saat sticky aktif.
--}}
@props(['currentStep' => 1])

@php
    $steps = [
        1 => 'Pendaftaran',
        2 => 'Bayar Pendaftaran',
        3 => 'Formulir',
        4 => 'Daftar Ulang',
        5 => 'Selesai',
    ];
@endphp

<div class="w-full bg-white py-4 sm:py-6 border-b border-gray-100 sticky top-[64px] z-20 shadow-sm">
    <div class="max-w-5xl mx-auto px-4 sm:px-6">
        <div class="flex items-center justify-between relative">

            {{-- Garis penghubung antar step --}}
            <div class="absolute top-[18px] sm:top-1/2 left-0 w-full h-0.5 bg-gray-100 -translate-y-1/2 z-0"></div>

            @foreach($steps as $id => $title)
                <div class="relative z-10 flex flex-col items-center gap-1 sm:gap-2">
                    <div class="w-8 h-8 sm:w-10 sm:h-10 rounded-full flex items-center justify-center font-bold text-[11px] sm:text-[13px] transition-all duration-300
                        {{ $id == $currentStep
                            ? 'bg-adzkia-blue text-white shadow-lg shadow-adzkia-blue/30 scale-110'
                            : ($id < $currentStep
                                ? 'bg-green-500 text-white border-2 border-green-500'
                                : 'bg-white border-2 border-gray-100 text-gray-400') }}">
                        @if($id < $currentStep)
                            <i data-feather="check" class="w-3 h-3 sm:w-4 sm:h-4"></i>
                        @else
                            {{ $id }}
                        @endif
                    </div>
                    <span class="text-[9px] font-black uppercase tracking-widest hidden md:block
                        {{ $id == $currentStep
                            ? 'text-adzkia-blue'
                            : ($id < $currentStep ? 'text-green-500' : 'text-gray-400') }}">
                        {{ $title }}
                    </span>
                </div>
            @endforeach

        </div>
    </div>
</div>