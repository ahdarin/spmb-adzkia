@extends('layouts.admin')

@section('title', 'Manajemen Berita')

@section('admin-content')

{{-- ── Header ──────────────────────────────────────────────── --}}
<div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-8 gap-4">
    <div>
        <h1 class="text-2xl font-extrabold text-brand-dark tracking-tight">Manajemen Berita</h1>
        <p class="text-brand-gray text-[13px] font-medium mt-1">Kelola pengumuman kampus, berita akademik, dan artikel informatif.</p>
    </div>
    <button onclick="bukaModalTambah()"
        class="flex items-center gap-2 px-5 py-3 bg-brand-dark text-white rounded-xl font-bold text-[13px] hover:bg-brand-blue transition-all shadow-lg shrink-0">
        <i data-feather="plus" class="w-4 h-4"></i> Tambah Berita
    </button>
</div>

{{-- Flash success --}}
@if(session('success'))
<div class="mb-5 p-4 bg-green-50 border border-green-200 text-green-800 font-bold rounded-2xl text-[13px] flex items-center gap-3">
    <i data-feather="check-circle" class="w-5 h-5 shrink-0 text-green-500"></i> {{ session('success') }}
</div>
@endif

{{-- ── Statistik ───────────────────────────────────────────── --}}
<div class="grid grid-cols-3 gap-4 mb-6">
    <div class="bg-blue-50 border border-blue-100 p-5 rounded-2xl flex items-center gap-4">
        <div class="w-10 h-10 bg-white rounded-xl flex items-center justify-center text-brand-blue shadow-sm shrink-0">
            <i data-feather="file-text" class="w-5 h-5"></i>
        </div>
        <div>
            <p class="text-[10px] font-black text-brand-blue uppercase tracking-widest">Total</p>
            <p class="text-2xl font-black text-brand-dark">{{ $data->count() }}</p>
        </div>
    </div>
    <div class="bg-green-50 border border-green-100 p-5 rounded-2xl flex items-center gap-4">
        <div class="w-10 h-10 bg-white rounded-xl flex items-center justify-center text-green-600 shadow-sm shrink-0">
            <i data-feather="check-circle" class="w-5 h-5"></i>
        </div>
        <div>
            <p class="text-[10px] font-black text-green-700 uppercase tracking-widest">Published</p>
            <p class="text-2xl font-black text-brand-dark">{{ $data->where('status','Published')->count() }}</p>
        </div>
    </div>
    <div class="bg-amber-50 border border-amber-100 p-5 rounded-2xl flex items-center gap-4">
        <div class="w-10 h-10 bg-white rounded-xl flex items-center justify-center text-amber-600 shadow-sm shrink-0">
            <i data-feather="edit" class="w-5 h-5"></i>
        </div>
        <div>
            <p class="text-[10px] font-black text-amber-700 uppercase tracking-widest">Draft</p>
            <p class="text-2xl font-black text-brand-dark">{{ $data->where('status','Draft')->count() }}</p>
        </div>
    </div>
</div>

{{-- ── Filter ──────────────────────────────────────────────── --}}
<div class="bg-white border border-gray-100 rounded-2xl p-4 mb-5 shadow-sm flex flex-wrap items-center gap-3">
    <div class="relative flex-1 min-w-[180px]">
        <i data-feather="search" class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400 pointer-events-none"></i>
        <input type="text" id="filter-search" oninput="filterBerita()" placeholder="Cari judul..."
               class="w-full pl-9 pr-4 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-[13px] outline-none focus:border-brand-blue transition-all">
    </div>
    <select id="filter-status" onchange="filterBerita()"
            class="px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-[13px] font-bold text-brand-dark outline-none cursor-pointer">
        <option value="">Semua Status</option>
        <option value="Published">Published</option>
        <option value="Draft">Draft</option>
    </select>
    <select id="filter-kategori" onchange="filterBerita()"
            class="px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-[13px] font-bold text-brand-dark outline-none cursor-pointer">
        <option value="">Semua Kategori</option>
        <option value="Akademik">Akademik</option>
        <option value="Beasiswa">Beasiswa</option>
        <option value="Kegiatan">Kegiatan</option>
        <option value="Informasi">Informasi</option>
    </select>
    <button onclick="resetFilter()" class="p-2.5 bg-gray-50 border border-gray-200 rounded-xl hover:bg-gray-100 transition-colors" title="Reset">
        <i data-feather="refresh-cw" class="w-4 h-4 text-gray-500"></i>
    </button>
</div>

{{-- ── Tabel ───────────────────────────────────────────────── --}}
<div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-left">
            <thead class="bg-gray-50 border-b border-gray-100">
                <tr>
                    <th class="px-6 py-3.5 text-[10px] font-black text-gray-400 uppercase tracking-widest">Berita</th>
                    <th class="px-4 py-3.5 text-[10px] font-black text-gray-400 uppercase tracking-widest">Kategori</th>
                    <th class="px-4 py-3.5 text-[10px] font-black text-gray-400 uppercase tracking-widest">Tanggal</th>
                    <th class="px-4 py-3.5 text-[10px] font-black text-gray-400 uppercase tracking-widest">Status</th>
                    <th class="px-6 py-3.5 text-[10px] font-black text-gray-400 uppercase tracking-widest text-center">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50 text-[13px]">
                @forelse($data as $item)
                @php
                    $tgl = $item->tanggal_publish ? \Carbon\Carbon::parse($item->tanggal_publish)->translatedFormat('d M Y') : '-';
                    $badge = match($item->kategori) {
                        'Beasiswa' => 'bg-green-50 text-green-700',
                        'Akademik' => 'bg-blue-50 text-brand-blue',
                        'Kegiatan' => 'bg-purple-50 text-purple-700',
                        default    => 'bg-gray-100 text-gray-600',
                    };
                    $thumb = $item->thumbnail ? asset('uploads/berita/'.$item->thumbnail) : null;
                @endphp
                <tr class="hover:bg-gray-50/50 transition-colors group berita-row"
                    data-judul="{{ strtolower($item->judul) }}"
                    data-ringkasan="{{ strtolower($item->ringkasan ?? '') }}"
                    data-status="{{ $item->status }}"
                    data-kategori="{{ $item->kategori }}">

                    <td class="px-6 py-4">
                        <div class="flex items-center gap-4">
                            <img src="{{ $thumb ?? 'https://images.unsplash.com/photo-1523050854058-8df90110c9f1?q=80&w=100' }}"
                                 class="w-14 h-14 rounded-xl object-cover border border-gray-100 shadow-sm {{ $item->status==='Draft' ? 'opacity-50 grayscale' : '' }}" alt="">
                            <div class="min-w-0">
                                <p class="font-extrabold text-brand-dark text-[14px] leading-tight mb-1 line-clamp-1">{{ $item->judul }}</p>
                                <p class="text-gray-400 text-[11px] line-clamp-1">{{ $item->ringkasan }}</p>
                            </div>
                        </div>
                    </td>
                    <td class="px-4 py-4">
                        <span class="px-3 py-1.5 rounded-lg text-[10px] font-black uppercase tracking-widest {{ $badge }}">{{ $item->kategori }}</span>
                    </td>
                    <td class="px-4 py-4 font-bold text-gray-600 text-[12px] whitespace-nowrap">{{ $tgl }}</td>
                    <td class="px-4 py-4">
                        <span class="inline-flex items-center gap-1.5 font-bold text-[12px] {{ $item->status==='Published' ? 'text-green-600' : 'text-gray-400' }}">
                            <div class="w-1.5 h-1.5 rounded-full {{ $item->status==='Published' ? 'bg-green-500' : 'bg-gray-300' }}"></div>
                            {{ $item->status }}
                        </span>
                    </td>
                    <td class="px-6 py-4 text-center">
                        <div class="flex items-center justify-center gap-2 opacity-0 group-hover:opacity-100 transition-opacity">
                            <button type="button"
                                onclick="bukaModalEdit(
                                    {{ $item->id }},
                                    {{ json_encode($item->judul) }},
                                    {{ json_encode($item->kategori) }},
                                    {{ json_encode($item->ringkasan ?? '') }},
                                    {{ json_encode($item->konten ?? '') }},
                                    {{ json_encode($item->status) }},
                                    {{ json_encode($item->tanggal_publish ? \Carbon\Carbon::parse($item->tanggal_publish)->format('Y-m-d\TH:i') : '') }},
                                    {{ json_encode($item->thumbnail ? asset('uploads/berita/'.$item->thumbnail) : '') }}
                                )"
                                class="p-2 text-brand-gray hover:text-brand-blue rounded-lg hover:bg-blue-50 transition-colors" title="Edit">
                                <i data-feather="edit-3" class="w-4 h-4"></i>
                            </button>
                            <form action="{{ route('admin.berita.destroy', $item->id) }}" method="POST"
                                  class="inline" onsubmit="return confirm('Hapus berita ini?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="p-2 text-brand-gray hover:text-red-500 rounded-lg hover:bg-red-50 transition-colors" title="Hapus">
                                    <i data-feather="trash-2" class="w-4 h-4"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="px-6 py-16 text-center">
                        <div class="flex flex-col items-center gap-3">
                            <div class="w-14 h-14 rounded-2xl bg-gray-100 flex items-center justify-center">
                                <i data-feather="rss" class="w-7 h-7 text-gray-300"></i>
                            </div>
                            <p class="text-gray-400 font-bold">Belum ada berita.</p>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
        <div id="row-not-found" class="hidden px-6 py-12 text-center">
            <p class="text-gray-400 font-bold">Tidak ada berita yang cocok.</p>
            <button onclick="resetFilter()" class="text-brand-blue text-[13px] font-bold hover:underline mt-1">Reset Filter</button>
        </div>
    </div>
</div>

{{-- ══════════════════════════════════════════════════════════
     MODAL BERITA — AJAX submit agar data tidak hilang
══════════════════════════════════════════════════════════ --}}
<div id="modal-berita" class="fixed inset-0 z-[999] flex items-center justify-center p-4 hidden">
    <div class="absolute inset-0 bg-brand-dark/70 backdrop-blur-sm" onclick="tutupModal()"></div>

    <div class="bg-white w-full max-w-3xl rounded-[2rem] shadow-2xl relative z-10 flex flex-col max-h-[92vh]">

        {{-- Header --}}
        <div class="flex justify-between items-center px-8 pt-7 pb-5 shrink-0 border-b border-gray-100">
            <div>
                <h2 id="modal-title" class="text-xl font-black text-brand-dark">Tambah Berita</h2>
                <p class="text-[12px] text-gray-400 font-medium mt-0.5">Isi semua field yang diperlukan</p>
            </div>
            <button onclick="tutupModal()" class="p-2 text-gray-400 hover:text-brand-dark hover:bg-gray-100 rounded-xl transition-colors">
                <i data-feather="x" class="w-5 h-5"></i>
            </button>
        </div>

        {{-- Scrollable body --}}
        <div class="overflow-y-auto flex-1 px-8 py-6">

            {{-- FORM (satu form, action & method diubah JS saat submit) --}}
            <form id="form-berita" method="POST" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="_method" id="form-method" value="POST">
                <input type="hidden" name="status" id="f-status" value="Published">

                <div class="space-y-5">

                    {{-- Judul --}}
                    <div id="wrap-judul">
                        <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-2">
                            Judul <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="judul" id="f-judul" required
                               placeholder="Masukkan judul artikel yang menarik..."
                               class="w-full px-4 py-3.5 bg-gray-50 border border-gray-200 rounded-xl outline-none focus:border-brand-blue font-bold text-[14px] transition-colors"
                               oninput="clearFieldError('judul')">
                        <p id="err-judul" class="hidden mt-1.5 text-[11px] font-bold text-red-500 flex items-center gap-1">
                            <svg class="w-3.5 h-3.5 shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
                            <span></span>
                        </p>
                    </div>

                    {{-- Kategori & Tanggal --}}
                    <div class="grid grid-cols-2 gap-4">
                        <div id="wrap-kategori">
                            <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-2">
                                Kategori <span class="text-red-500">*</span>
                            </label>
                            <div class="relative">
                                <select name="kategori" id="f-kategori" required
                                        class="w-full px-4 py-3.5 bg-gray-50 border border-gray-200 rounded-xl outline-none focus:border-brand-blue font-bold text-[13px] appearance-none cursor-pointer"
                                        onchange="clearFieldError('kategori')">
                                    <option value="Akademik">Akademik</option>
                                    <option value="Beasiswa">Beasiswa</option>
                                    <option value="Kegiatan">Kegiatan</option>
                                    <option value="Informasi">Informasi</option>
                                </select>
                                <i data-feather="chevron-down" class="absolute right-4 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400 pointer-events-none"></i>
                            </div>
                            <p id="err-kategori" class="hidden mt-1.5 text-[11px] font-bold text-red-500 flex items-center gap-1">
                                <svg class="w-3.5 h-3.5 shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
                                <span></span>
                            </p>
                        </div>
                        <div>
                            <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-2">Tanggal Publish</label>
                            <input type="datetime-local" name="tanggal_publish" id="f-tanggal"
                                   class="w-full px-4 py-3.5 bg-gray-50 border border-gray-200 rounded-xl outline-none focus:border-brand-blue font-bold text-[13px] cursor-pointer">
                        </div>
                    </div>

                    {{-- Ringkasan --}}
                    <div id="wrap-ringkasan">
                        <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-2">Ringkasan</label>
                        <textarea name="ringkasan" id="f-ringkasan" rows="2" maxlength="500"
                                  placeholder="Deskripsi singkat (maks. 500 karakter)..."
                                  class="w-full px-4 py-3.5 bg-gray-50 border border-gray-200 rounded-xl outline-none focus:border-brand-blue font-medium text-[13px] resize-none"
                                  oninput="clearFieldError('ringkasan')"></textarea>
                        <p id="err-ringkasan" class="hidden mt-1.5 text-[11px] font-bold text-red-500 flex items-center gap-1">
                            <svg class="w-3.5 h-3.5 shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
                            <span></span>
                        </p>
                    </div>

                    {{-- Thumbnail --}}
                    <div id="wrap-thumbnail">
                        <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-2">
                            Thumbnail <span id="thumb-hint" class="text-gray-400 font-medium normal-case">(opsional)</span>
                        </label>
                        <div id="thumb-dropzone"
                             class="relative w-full h-40 border-2 border-dashed border-gray-200 rounded-2xl bg-gray-50 hover:border-brand-blue hover:bg-blue-50/30 transition-all overflow-hidden cursor-pointer group"
                             onclick="document.getElementById('f-thumbnail').click()">
                            <img id="f-thumb-preview" src="" class="absolute inset-0 w-full h-full object-cover rounded-2xl hidden" alt="">
                            <div id="f-thumb-placeholder" class="absolute inset-0 flex flex-col items-center justify-center gap-2 text-center">
                                <div class="w-10 h-10 bg-white rounded-xl shadow-sm flex items-center justify-center text-gray-400 group-hover:text-brand-blue transition-colors">
                                    <i data-feather="image" class="w-5 h-5"></i>
                                </div>
                                <p class="font-bold text-brand-dark text-[12px]">Klik untuk unggah foto</p>
                                <p class="text-gray-400 text-[10px]">JPG, PNG, WebP · Maks. 5MB</p>
                            </div>
                            <input type="file" id="f-thumbnail" name="thumbnail"
                                   accept="image/jpeg,image/png,image/webp" class="sr-only"
                                   onchange="previewThumb(this); clearFieldError('thumbnail')">
                        </div>
                        <p id="err-thumbnail" class="hidden mt-1.5 text-[11px] font-bold text-red-500 flex items-center gap-1">
                            <svg class="w-3.5 h-3.5 shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
                            <span></span>
                        </p>
                    </div>

                    {{-- Konten --}}
                    <div id="wrap-konten">
                        <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-2">
                            Konten <span class="text-red-500">*</span>
                        </label>
                        <div id="konten-editor-wrap" class="bg-white border border-gray-200 rounded-2xl overflow-hidden shadow-sm">
                            <div class="bg-gray-50 px-4 py-2.5 border-b border-gray-100 flex items-center gap-2 flex-wrap">
                                <button type="button" onclick="insertTag('h2')" class="px-2 py-1 rounded text-[11px] font-black text-gray-600 hover:bg-white">H2</button>
                                <button type="button" onclick="insertTag('h3')" class="px-2 py-1 rounded text-[11px] font-black text-gray-400 hover:bg-white">H3</button>
                                <div class="w-px h-4 bg-gray-200"></div>
                                <button type="button" onclick="insertTag('b')"    class="p-1.5 rounded hover:bg-white" title="Bold"><i data-feather="bold"      class="w-3.5 h-3.5 text-gray-600"></i></button>
                                <button type="button" onclick="insertTag('i')"    class="p-1.5 rounded hover:bg-white" title="Italic"><i data-feather="italic"    class="w-3.5 h-3.5 text-gray-400"></i></button>
                                <button type="button" onclick="insertTag('u')"    class="p-1.5 rounded hover:bg-white" title="Underline"><i data-feather="underline" class="w-3.5 h-3.5 text-gray-400"></i></button>
                                <div class="w-px h-4 bg-gray-200"></div>
                                <button type="button" onclick="insertTag('ul')"   class="p-1.5 rounded hover:bg-white" title="List"><i data-feather="list"        class="w-3.5 h-3.5 text-gray-400"></i></button>
                                <button type="button" onclick="insertTag('ol')"   class="p-1.5 rounded hover:bg-white" title="Numbered"><i data-feather="hash"    class="w-3.5 h-3.5 text-gray-400"></i></button>
                                <div class="w-px h-4 bg-gray-200"></div>
                                <button type="button" onclick="insertTag('link')" class="p-1.5 rounded hover:bg-white" title="Link"><i data-feather="link"        class="w-3.5 h-3.5 text-gray-400"></i></button>
                                <button type="button" onclick="insertTag('img')"  class="p-1.5 rounded hover:bg-white" title="Gambar"><i data-feather="image"     class="w-3.5 h-3.5 text-gray-400"></i></button>
                                <button type="button" onclick="insertTag('hr')"   class="p-1.5 rounded hover:bg-white" title="Garis"><i data-feather="minus"      class="w-3.5 h-3.5 text-gray-400"></i></button>
                            </div>
                            <textarea name="konten" id="f-konten" required
                                      placeholder="Tulis konten artikel di sini..."
                                      class="w-full min-h-[280px] p-5 outline-none resize-y text-[13px] font-medium text-brand-dark placeholder-gray-400 leading-relaxed"
                                      oninput="clearFieldError('konten')"></textarea>
                        </div>
                        <p id="err-konten" class="hidden mt-1.5 text-[11px] font-bold text-red-500 flex items-center gap-1">
                            <svg class="w-3.5 h-3.5 shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
                            <span></span>
                        </p>
                    </div>

                </div>
            </form>
        </div>

        {{-- Footer --}}
        <div class="flex gap-2 px-8 py-5 shrink-0 border-t border-gray-100 bg-gray-50/50 rounded-b-[2rem]">
            <button type="button" onclick="tutupModal()"
                class="py-3.5 px-5 bg-white border border-gray-200 text-brand-gray rounded-xl font-bold text-[13px] hover:bg-gray-100 transition-colors">
                Batal
            </button>
            <button type="button" onclick="togglePreview()"
                class="py-3.5 px-4 bg-brand-blue/10 text-brand-blue rounded-xl font-bold text-[13px] hover:bg-blue-100 transition-colors flex items-center gap-2">
                <i data-feather="eye" class="w-4 h-4"></i> Preview
            </button>
            <div class="flex-1"></div>
            {{-- Simpan Draft --}}
            <button type="button" id="btn-draft" onclick="submitForm('Draft')"
                class="py-3.5 px-5 bg-gray-200 text-brand-dark rounded-xl font-bold text-[13px] hover:bg-gray-300 transition-colors flex items-center gap-2">
                <i data-feather="save" class="w-4 h-4"></i>
                <span id="btn-draft-text">Simpan Draft</span>
            </button>
            {{-- Publish --}}
            <button type="button" id="btn-publish" onclick="submitForm('Published')"
                class="py-3.5 px-5 bg-brand-dark text-white rounded-xl font-bold text-[13px] hover:bg-brand-blue shadow-lg transition-colors flex items-center gap-2">
                <i data-feather="send" class="w-4 h-4"></i>
                <span id="btn-publish-text">Publish</span>
                <svg id="btn-spinner" class="hidden animate-spin w-4 h-4" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
                </svg>
            </button>
        </div>
    </div>
</div>

{{-- ── Preview Modal ───────────────────────────────────────── --}}
<div id="modal-preview" class="fixed inset-0 z-[9999] flex items-center justify-center p-4 bg-brand-dark/80 backdrop-blur-sm hidden">
    <div class="bg-white w-full max-w-4xl max-h-[90vh] rounded-3xl overflow-y-auto p-10 relative">
        <button onclick="tutupPreview()" class="absolute top-5 right-5 p-2 bg-gray-100 hover:bg-red-500 hover:text-white rounded-full transition-colors">
            <i data-feather="x" class="w-5 h-5"></i>
        </button>
        <span id="prev-kategori" class="px-3 py-1 bg-brand-blue/10 text-brand-blue rounded-lg text-[10px] font-black uppercase tracking-widest"></span>
        <h1 id="prev-judul" class="text-4xl font-black text-brand-dark mt-4 mb-6 leading-tight"></h1>
        <img id="prev-thumb" src="" class="w-full max-h-[400px] object-cover rounded-2xl mb-8 hidden">
        <div id="prev-konten" class="prose max-w-none text-gray-700 leading-relaxed"></div>
    </div>
</div>

<style>
.prose h2{font-size:1.4rem;font-weight:800;margin:1.25rem 0 .5rem}
.prose h3{font-size:1.15rem;font-weight:700;margin:1rem 0 .4rem}
.prose p{margin:.75rem 0}
.prose ul,.prose ol{padding-left:1.5rem;margin:.75rem 0}
.prose ul{list-style:disc}.prose ol{list-style:decimal}
.prose a{color:#2c7ebd;text-decoration:underline}
.prose img{width:100%;border-radius:12px;margin:1rem 0}
.prose hr{border:none;border-top:2px solid #e5e7eb;margin:1.5rem 0}
.prose b,.prose strong{font-weight:800}
</style>

<script>
const STORE_URL  = '{{ route("admin.berita.store") }}';
const UPDATE_URL = '{{ url("admin/berita") }}';
const CSRF       = '{{ csrf_token() }}';
let   modeAktif  = 'tambah';
let   editId     = null;

// ── Buka modal TAMBAH ───────────────────────────────────────────
function bukaModalTambah() {
    modeAktif = 'tambah'; editId = null;
    document.getElementById('modal-title').textContent = 'Tambah Berita';
    document.getElementById('thumb-hint').textContent  = '(opsional)';
    // Reset form
    document.getElementById('form-berita').reset();
    document.getElementById('f-status').value = 'Published';
    document.getElementById('f-thumb-preview').classList.add('hidden');
    document.getElementById('f-thumb-placeholder').classList.remove('hidden');
    document.getElementById('f-thumbnail').value = '';
    sembunyikanError(); // reset semua error inline
    document.getElementById('modal-berita').classList.remove('hidden');
    if (typeof feather !== 'undefined') feather.replace();
}

// ── Buka modal EDIT ─────────────────────────────────────────────
function bukaModalEdit(id, judul, kategori, ringkasan, konten, status, tglPublish, thumbUrl) {
    modeAktif = 'edit'; editId = id;
    document.getElementById('modal-title').textContent = 'Edit Berita';
    document.getElementById('thumb-hint').textContent  = '(kosongkan jika tidak diganti)';
    document.getElementById('f-status').value = status || 'Published';

    document.getElementById('f-judul').value     = judul     || '';
    document.getElementById('f-ringkasan').value = ringkasan || '';
    document.getElementById('f-konten').value    = konten    || '';
    document.getElementById('f-tanggal').value   = tglPublish|| '';

    // Kategori
    const sel = document.getElementById('f-kategori');
    for (let i = 0; i < sel.options.length; i++) {
        if (sel.options[i].value === kategori) { sel.selectedIndex = i; break; }
    }

    // Thumbnail
    const img = document.getElementById('f-thumb-preview');
    const ph  = document.getElementById('f-thumb-placeholder');
    if (thumbUrl) {
        img.src = thumbUrl; img.classList.remove('hidden'); ph.classList.add('hidden');
    } else {
        img.classList.add('hidden'); ph.classList.remove('hidden');
    }
    // Reset file input agar tidak kirim file lama
    document.getElementById('f-thumbnail').value = '';

    sembunyikanError();
    document.getElementById('modal-berita').classList.remove('hidden');
    if (typeof feather !== 'undefined') feather.replace();
}

function tutupModal() { document.getElementById('modal-berita').classList.add('hidden'); }

// ── Submit via AJAX — error tampil di modal, bukan redirect ────
function submitForm(status) {
    // Set status ke hidden input sebelum ambil FormData
    document.getElementById('f-status').value = status;

    const form = document.getElementById('form-berita');
    const data = new FormData(form);
    const url  = modeAktif === 'edit' ? `${UPDATE_URL}/${editId}` : STORE_URL;

    if (modeAktif === 'edit') data.set('_method', 'PUT');
    else                       data.set('_method', 'POST');
    data.set('_token', CSRF);

    // Loading state — disable kedua tombol
    const btnD = document.getElementById('btn-draft');
    const btnP = document.getElementById('btn-publish');
    btnD.disabled = btnP.disabled = true;
    if (status === 'Draft') {
        document.getElementById('btn-draft-text').textContent = 'Menyimpan...';
    } else {
        document.getElementById('btn-publish-text').textContent = 'Menyimpan...';
        document.getElementById('btn-spinner').classList.remove('hidden');
    }
    sembunyikanError();

    fetch(url, {
        method: 'POST',
        body:   data,
        headers: { 'X-Requested-With': 'XMLHttpRequest', 'X-CSRF-TOKEN': CSRF },
    })
    .then(r => r.json())
    .then(res => {
        if (res.success) {
            // Berhasil — reload halaman
            window.location.reload();
        } else if (res.errors) {
            // Validasi gagal — tampilkan error di dalam modal
            tampilkanError(res.errors);
            // Scroll ke atas modal
            document.querySelector('#modal-berita .overflow-y-auto').scrollTop = 0;
        } else {
            tampilkanError({ umum: [res.message || 'Terjadi kesalahan.'] });
        }
    })
    .catch(() => {
        tampilkanError({ umum: ['Gagal terhubung ke server. Silakan coba lagi.'] });
    })
    .finally(() => {
        btnD.disabled = btnP.disabled = false;
        document.getElementById('btn-draft-text').textContent   = 'Simpan Draft';
        document.getElementById('btn-publish-text').textContent = 'Publish';
        document.getElementById('btn-spinner').classList.add('hidden');
    });
}

// Map nama field Laravel → id error element
const fieldErrorMap = {
    judul:           'err-judul',
    kategori:        'err-kategori',
    ringkasan:       'err-ringkasan',
    konten:          'err-konten',
    thumbnail:       'err-thumbnail',
    tanggal_publish: null, // tidak ada error inline untuk ini
};

// Field input yang perlu border merah
const fieldInputMap = {
    judul:     'f-judul',
    kategori:  'f-kategori',
    ringkasan: 'f-ringkasan',
    konten:    'konten-editor-wrap',
    thumbnail: 'thumb-dropzone',
};

function tampilkanError(errors) {
    sembunyikanError(); // reset dulu

    let firstField = null;

    Object.entries(errors).forEach(([field, messages]) => {
        const errId   = fieldErrorMap[field];
        const inputId = fieldInputMap[field];
        const msg     = Array.isArray(messages) ? messages[0] : messages;

        if (errId) {
            const errEl = document.getElementById(errId);
            if (errEl) {
                errEl.querySelector('span').textContent = msg;
                errEl.classList.remove('hidden');
                if (!firstField) firstField = errEl;
            }
        }

        if (inputId) {
            const inputEl = document.getElementById(inputId);
            if (inputEl) {
                inputEl.classList.add('border-red-400', 'bg-red-50/30');
                inputEl.classList.remove('border-gray-200', 'bg-gray-50');
            }
        }
    });

    // Scroll ke field error pertama
    if (firstField) {
        firstField.closest('div[id^="wrap-"]')?.scrollIntoView({ behavior: 'smooth', block: 'center' });
    }
}

function sembunyikanError() {
    // Sembunyikan semua error inline
    Object.values(fieldErrorMap).forEach(errId => {
        if (!errId) return;
        const el = document.getElementById(errId);
        if (el) { el.classList.add('hidden'); el.querySelector('span').textContent = ''; }
    });
    // Reset border merah
    Object.values(fieldInputMap).forEach(inputId => {
        const el = document.getElementById(inputId);
        if (el) {
            el.classList.remove('border-red-400', 'bg-red-50/30');
            el.classList.add('border-gray-200');
            if (inputId === 'f-judul' || inputId === 'f-ringkasan') el.classList.add('bg-gray-50');
        }
    });
}

function clearFieldError(field) {
    const errId   = fieldErrorMap[field];
    const inputId = fieldInputMap[field];
    if (errId) {
        const el = document.getElementById(errId);
        if (el) { el.classList.add('hidden'); el.querySelector('span').textContent = ''; }
    }
    if (inputId) {
        const el = document.getElementById(inputId);
        if (el) {
            el.classList.remove('border-red-400', 'bg-red-50/30');
            el.classList.add('border-gray-200');
            if (inputId === 'f-judul' || inputId === 'f-ringkasan') el.classList.add('bg-gray-50');
        }
    }
}

// ── Reset form ──────────────────────────────────────────────────
function resetForm() {
    document.getElementById('form-berita').reset();
    document.querySelectorAll('input[name="status"]').forEach((r,i) => r.checked = i===0);
    updateStatusUI();
    document.getElementById('f-thumb-preview').classList.add('hidden');
    document.getElementById('f-thumb-placeholder').classList.remove('hidden');
    document.getElementById('f-thumbnail').value = '';
}

// ── Thumbnail preview ───────────────────────────────────────────
function previewThumb(input) {
    if (!input.files || !input.files[0]) return;
    const reader = new FileReader();
    reader.onload = e => {
        document.getElementById('f-thumb-preview').src = e.target.result;
        document.getElementById('f-thumb-preview').classList.remove('hidden');
        document.getElementById('f-thumb-placeholder').classList.add('hidden');
    };
    reader.readAsDataURL(input.files[0]);
}

// ── Toolbar konten ──────────────────────────────────────────────
function insertTag(tag) {
    const ta    = document.getElementById('f-konten');
    const start = ta.selectionStart, end = ta.selectionEnd;
    const sel   = ta.value.substring(start, end);
    let out = '';
    switch(tag) {
        case 'h2':   out = `<h2>${sel||'Judul Bagian'}</h2>\n`; break;
        case 'h3':   out = `<h3>${sel||'Sub Judul'}</h3>\n`;   break;
        case 'b':    out = `<b>${sel||'teks tebal'}</b>`;       break;
        case 'i':    out = `<i>${sel||'teks miring'}</i>`;      break;
        case 'u':    out = `<u>${sel||'garis bawah'}</u>`;      break;
        case 'ul':   out = `<ul>\n  <li>${sel||'Item'}</li>\n</ul>\n`; break;
        case 'ol':   out = `<ol>\n  <li>${sel||'Item'}</li>\n</ol>\n`; break;
        case 'hr':   out = `\n<hr>\n`; break;
        case 'link': { const url=prompt('URL:'); if(!url)return; out=`<a href="${url}" target="_blank">${sel||url}</a>`; break; }
        case 'img':  { const src=prompt('URL Gambar:'); if(!src)return; out=`\n<img src="${src}" alt="gambar">\n`; break; }
    }
    ta.value = ta.value.substring(0,start)+out+ta.value.substring(end);
    ta.focus(); ta.selectionStart = ta.selectionEnd = start+out.length;
}

// ── Preview artikel ─────────────────────────────────────────────
function togglePreview() {
    document.getElementById('prev-judul').textContent    = document.getElementById('f-judul').value    || 'Judul belum diisi';
    document.getElementById('prev-kategori').textContent = document.getElementById('f-kategori').value || '';
    document.getElementById('prev-konten').innerHTML     = document.getElementById('f-konten').value   || '<i>Konten masih kosong.</i>';
    const thumbEl = document.getElementById('f-thumb-preview');
    const pt = document.getElementById('prev-thumb');
    if (thumbEl.src && !thumbEl.classList.contains('hidden')) { pt.src=thumbEl.src; pt.classList.remove('hidden'); }
    else pt.classList.add('hidden');
    document.getElementById('modal-preview').classList.remove('hidden');
    if (typeof feather !== 'undefined') feather.replace();
}
function tutupPreview() { document.getElementById('modal-preview').classList.add('hidden'); }

// ── Filter ──────────────────────────────────────────────────────
function filterBerita() {
    const q=document.getElementById('filter-search').value.toLowerCase();
    const st=document.getElementById('filter-status').value;
    const kat=document.getElementById('filter-kategori').value;
    const rows=document.querySelectorAll('.berita-row');
    let vis=0;
    rows.forEach(row=>{
        const ok=(!q||row.dataset.judul.includes(q)||row.dataset.ringkasan.includes(q))&&(!st||row.dataset.status===st)&&(!kat||row.dataset.kategori===kat);
        row.classList.toggle('hidden',!ok); if(ok)vis++;
    });
    document.getElementById('row-not-found').classList.toggle('hidden',vis>0||rows.length===0);
}
function resetFilter(){
    ['filter-search','filter-status','filter-kategori'].forEach(id=>{const e=document.getElementById(id);if(e)e.value='';});
    filterBerita();
}

document.addEventListener('DOMContentLoaded',()=>{ if(typeof feather!=='undefined')feather.replace(); });
</script>

@endsection