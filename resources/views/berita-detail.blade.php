@extends('layouts.app')

@section('title', $berita->judul)

@section('content')
<main class="w-full bg-adzkia-bg py-24 min-h-screen">
    <div class="max-w-4xl mx-auto px-6 lg:px-16">
        
        <a href="/#berita" class="inline-flex items-center gap-2 text-adzkia-blue font-bold mb-10 hover:text-adzkia-red transition-colors">
            <i data-feather="arrow-left" class="w-4 h-4"></i> Kembali
        </a>

        <div class="bg-white rounded-[3rem] p-8 md:p-14 shadow-sm border border-gray-100">
            <span class="px-4 py-1.5 bg-adzkia-badge-bg text-adzkia-blue rounded-lg text-[12px] font-black uppercase tracking-widest">
                {{ $berita->kategori }}
            </span>
            
            <h1 class="text-3xl md:text-5xl font-black text-adzkia-dark mt-6 mb-8 leading-tight tracking-tight">
                {{ $berita->judul }}
            </h1>
            
            <div class="flex items-center gap-4 text-gray-400 text-[14px] font-bold mb-10 pb-10 border-b border-gray-100">
                <span class="flex items-center gap-2">
                    <i data-feather="calendar" class="w-4 h-4"></i> 
                    {{ \Carbon\Carbon::parse($berita->tanggal_publish ?? $berita->created_at)->translatedFormat('d F Y') }}
                </span>
                <span>•</span>
                <span>Admin Universitas</span>
            </div>
            
            @if($berita->thumbnail)
                <div class="w-full h-auto max-h-[500px] overflow-hidden rounded-[2rem] mb-12 shadow-sm">
                    <img src="{{ asset('uploads/berita/' . $berita->thumbnail) }}" alt="{{ $berita->judul }}" class="w-full h-full object-cover">
                </div>
            @endif
            
            <div class="prose prose-lg max-w-none text-gray-700 leading-relaxed font-medium">
                {!! $berita->konten !!}
            </div>
        </div>
        
    </div>
</main>
@endsection