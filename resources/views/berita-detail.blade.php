@extends('layouts.app')

@section('title', $berita->judul . ' - SPMB Adzkia')

@section('content')

@php
    $tgl          = \Carbon\Carbon::parse($berita->tanggal_publish ?? $berita->created_at);
    $badgeColor   = match($berita->kategori) {
        'Beasiswa' => 'bg-green-50 text-green-700',
        'Akademik' => 'bg-adzkia-badge-bg text-adzkia-blue',
        'Kegiatan' => 'bg-purple-50 text-purple-700',
        default    => 'bg-gray-100 text-gray-600',
    };
@endphp

<div class="bg-adzkia-bg min-h-screen">

    {{-- ══ HERO — thumbnail sebagai background ══════════════════ --}}
    @if($berita->thumbnail)
    <div class="relative w-full h-[420px] md:h-[520px] overflow-hidden">
        <img src="{{ asset('uploads/berita/' . $berita->thumbnail) }}"
             alt="{{ $berita->judul }}"
             class="w-full h-full object-cover">
        {{-- Gradient overlay --}}
        <div class="absolute inset-0 bg-gradient-to-t from-adzkia-dark/80 via-adzkia-dark/30 to-transparent"></div>

        {{-- Meta di atas gambar --}}
        <div class="absolute bottom-0 left-0 right-0 px-6 lg:px-0 pb-10">
            <div class="max-w-4xl mx-auto">
                <span class="inline-block px-3 py-1.5 {{ $badgeColor }} rounded-full text-[10px] font-black uppercase tracking-widest mb-4">
                    {{ $berita->kategori }}
                </span>
                <h1 class="text-3xl md:text-5xl font-black text-white leading-tight tracking-tight max-w-3xl">
                    {{ $berita->judul }}
                </h1>
            </div>
        </div>
    </div>
    @endif

    {{-- ══ KONTEN UTAMA ══════════════════════════════════════════ --}}
    <div class="max-w-4xl mx-auto px-6 lg:px-0 {{ $berita->thumbnail ? '-mt-6' : 'pt-12' }}">

        <div class="bg-white rounded-[2rem] shadow-sm border border-gray-100 overflow-hidden">

            {{-- Header artikel (judul jika tidak ada thumbnail) --}}
            @if(!$berita->thumbnail)
            <div class="px-10 pt-12 pb-8 border-b border-gray-100">
                <span class="inline-block px-3 py-1.5 {{ $badgeColor }} rounded-full text-[10px] font-black uppercase tracking-widest mb-5">
                    {{ $berita->kategori }}
                </span>
                <h1 class="text-3xl md:text-4xl font-black text-adzkia-dark leading-tight tracking-tight">
                    {{ $berita->judul }}
                </h1>
            </div>
            @endif

            {{-- Meta info --}}
            <div class="px-8 md:px-12 {{ $berita->thumbnail ? 'pt-8' : 'pt-6' }} pb-6 border-b border-gray-100 flex flex-wrap items-center justify-between gap-4">
                <div class="flex flex-wrap items-center gap-4 text-[13px] font-bold text-adzkia-muted">
                    <span class="flex items-center gap-2">
                        <div class="w-7 h-7 rounded-full bg-adzkia-blue flex items-center justify-center shrink-0">
                            <i data-feather="user" class="w-3.5 h-3.5 text-white"></i>
                        </div>
                        Admin Universitas Adzkia
                    </span>
                    <span class="text-gray-200">|</span>
                    <span class="flex items-center gap-1.5">
                        <i data-feather="calendar" class="w-4 h-4"></i>
                        {{ $tgl->translatedFormat('d F Y') }}
                    </span>
                    <span class="text-gray-200">|</span>
                    <span class="flex items-center gap-1.5">
                        <i data-feather="clock" class="w-4 h-4"></i>
                        {{ $tgl->translatedFormat('H:i') }} WIB
                    </span>
                </div>

                {{-- Share buttons --}}
                <div class="flex items-center gap-2">
                    <span class="text-[11px] font-black text-adzkia-muted uppercase tracking-widest mr-1">Bagikan:</span>
                    <a href="https://wa.me/?text={{ urlencode($berita->judul . ' ' . url()->current()) }}"
                       target="_blank"
                       class="w-8 h-8 rounded-full bg-green-500 text-white flex items-center justify-center hover:bg-green-600 transition-colors" title="WhatsApp">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413Z"/></svg>
                    </a>
                    <a href="https://twitter.com/intent/tweet?text={{ urlencode($berita->judul) }}&url={{ urlencode(url()->current()) }}"
                       target="_blank"
                       class="w-8 h-8 rounded-full bg-sky-500 text-white flex items-center justify-center hover:bg-sky-600 transition-colors" title="Twitter/X">
                        <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 24 24"><path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-4.714-6.231-5.401 6.231H2.744l7.737-8.835L1.254 2.25H8.08l4.254 5.622L18.244 2.25zm-1.161 17.52h1.833L7.084 4.126H5.117z"/></svg>
                    </a>
                </div>
            </div>

            {{-- Ringkasan / Lead paragraph --}}
            @if($berita->ringkasan)
            <div class="px-8 md:px-12 py-7 bg-adzkia-badge-bg border-b border-blue-100">
                <p class="text-adzkia-blue font-bold text-[16px] leading-relaxed italic">
                    "{{ $berita->ringkasan }}"
                </p>
            </div>
            @endif

            {{-- ── KONTEN ARTIKEL ── --}}
            <div class="px-8 md:px-12 py-10">
                <div class="article-content text-adzkia-dark leading-relaxed">
                    {!! $berita->konten !!}
                </div>
            </div>

            {{-- Footer artikel --}}
            <div class="px-8 md:px-12 py-6 bg-gray-50 border-t border-gray-100 flex flex-wrap items-center justify-between gap-4">
                <div class="flex items-center gap-2">
                    <span class="text-[11px] font-black text-adzkia-muted uppercase tracking-widest">Kategori:</span>
                    <span class="px-3 py-1 {{ $badgeColor }} rounded-full text-[11px] font-bold">{{ $berita->kategori }}</span>
                </div>
                <a href="/berita"
                   class="inline-flex items-center gap-2 text-[13px] font-bold text-adzkia-blue hover:text-adzkia-red transition-colors">
                    <i data-feather="arrow-left" class="w-4 h-4"></i> Lihat semua berita
                </a>
            </div>
        </div>

        {{-- ── ARTIKEL TERKAIT ── --}}
        @php
            $terkait = \App\Models\Berita::where('status', 'Published')
                ->where('id', '!=', $berita->id)
                ->where('kategori', $berita->kategori)
                ->latest()
                ->take(3)
                ->get();
            if ($terkait->count() < 3) {
                $ids = $terkait->pluck('id')->push($berita->id);
                $tambahan = \App\Models\Berita::where('status', 'Published')
                    ->whereNotIn('id', $ids)
                    ->latest()
                    ->take(3 - $terkait->count())
                    ->get();
                $terkait = $terkait->concat($tambahan);
            }
        @endphp

        @if($terkait->count() > 0)
        <div class="mt-10 mb-12">
            <h2 class="text-xl font-black text-adzkia-dark mb-6 flex items-center gap-3">
                <div class="w-1 h-6 bg-adzkia-blue rounded-full"></div>
                Berita Terkait
            </h2>
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-5">
                @foreach($terkait as $rel)
                @php
                    $relBadge = match($rel->kategori) {
                        'Beasiswa' => 'bg-green-50 text-green-700',
                        'Akademik' => 'bg-adzkia-badge-bg text-adzkia-blue',
                        'Kegiatan' => 'bg-purple-50 text-purple-700',
                        default    => 'bg-gray-100 text-gray-600',
                    };
                @endphp
                <a href="/berita/{{ $rel->slug }}"
                   class="group bg-white rounded-[1.5rem] overflow-hidden border border-gray-100 shadow-sm hover:shadow-lg hover:-translate-y-1 transition-all duration-300 flex flex-col">
                    <div class="relative h-40 overflow-hidden shrink-0">
                        <img src="{{ $rel->thumbnail ? asset('uploads/berita/'.$rel->thumbnail) : 'https://images.unsplash.com/photo-1523050854058-8df90110c9f1?q=80&w=400' }}"
                             alt="{{ $rel->judul }}"
                             class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                        <span class="absolute top-3 left-3 px-2.5 py-1 {{ $relBadge }} text-[9px] font-black uppercase tracking-widest rounded-full">
                            {{ $rel->kategori }}
                        </span>
                    </div>
                    <div class="p-5 flex flex-col flex-grow">
                        <p class="text-[10px] font-bold text-adzkia-muted mb-2">
                            {{ \Carbon\Carbon::parse($rel->tanggal_publish ?? $rel->created_at)->translatedFormat('d M Y') }}
                        </p>
                        <h3 class="text-[13px] font-extrabold text-adzkia-dark line-clamp-2 leading-snug group-hover:text-adzkia-blue transition-colors">
                            {{ $rel->judul }}
                        </h3>
                    </div>
                </a>
                @endforeach
            </div>
        </div>
        @endif

    </div>
</div>

<style>
/* ── Styling konten artikel ── */
.article-content { font-size: 15px; font-weight: 500; color: #1e293b; }
.article-content h1 { font-size: 1.875rem; font-weight: 900; margin: 2rem 0 1rem; color: #0A1B3A; line-height: 1.3; }
.article-content h2 { font-size: 1.5rem;   font-weight: 800; margin: 1.75rem 0 0.75rem; color: #0A1B3A; line-height: 1.35; }
.article-content h3 { font-size: 1.25rem;  font-weight: 700; margin: 1.5rem 0 0.6rem;  color: #0A1B3A; }
.article-content p  { margin: 1rem 0; line-height: 1.85; }
.article-content ul { list-style: disc;    padding-left: 1.75rem; margin: 1rem 0; }
.article-content ol { list-style: decimal; padding-left: 1.75rem; margin: 1rem 0; }
.article-content li { margin: 0.4rem 0; line-height: 1.7; }
.article-content a  { color: #2c7ebd; text-decoration: underline; font-weight: 600; }
.article-content a:hover { color: #d9241c; }
.article-content img { width: 100%; border-radius: 1rem; margin: 1.5rem 0; box-shadow: 0 4px 20px rgba(0,0,0,0.08); }
.article-content b, .article-content strong { font-weight: 800; color: #0A1B3A; }
.article-content i  { font-style: italic; }
.article-content u  { text-decoration: underline; }
.article-content hr { border: none; border-top: 2px solid #f1f5f9; margin: 2rem 0; }
.article-content blockquote { border-left: 4px solid #2c7ebd; padding: 1rem 1.5rem; margin: 1.5rem 0; background: #eff6ff; border-radius: 0 0.75rem 0.75rem 0; font-style: italic; color: #2c7ebd; font-weight: 600; }
</style>

@endsection