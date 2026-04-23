@extends('layouts.admin')

@section('admin-content')
<div class="flex items-center justify-between mb-8">
    <div class="flex items-center gap-4">
        <a href="/admin/berita" class="p-2 bg-white border border-gray-200 rounded-full text-brand-dark hover:bg-gray-50 transition-colors shadow-sm">
            <i data-feather="arrow-left" class="w-5 h-5"></i>
        </a>
        <div class="flex items-center gap-2 text-[11px] font-black uppercase tracking-widest text-gray-400">
            <a href="/admin" class="hover:text-brand-dark transition-colors">Dashboard</a>
            <i data-feather="chevron-right" class="w-3 h-3"></i>
            <a href="/admin/berita" class="hover:text-brand-dark transition-colors">Manajemen Berita</a>
            <i data-feather="chevron-right" class="w-3 h-3"></i>
            <span class="text-brand-dark">Tambah Berita</span>
        </div>
    </div>
    <button class="flex items-center gap-2 px-5 py-2.5 bg-brand-blue-light text-brand-blue rounded-xl font-bold text-[12px] hover:bg-blue-100 transition-all shadow-sm">
        <i data-feather="eye" class="w-4 h-4"></i> Preview Artikel
    </button>
</div>

<div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
    
    <div class="lg:col-span-8 space-y-8">
        
        <div>
            <label class="block text-[11px] font-black text-gray-400 uppercase tracking-widest mb-3 px-1">Judul Berita</label>
            <textarea rows="2" placeholder="Masukkan judul artikel yang menarik..." 
                      class="w-full bg-white border border-gray-100 rounded-3xl p-6 text-3xl font-extrabold text-brand-dark placeholder-gray-300 outline-none focus:ring-2 focus:ring-brand-blue/10 resize-none shadow-sm"></textarea>
        </div>

        <div class="grid grid-cols-2 gap-6">
            <div>
                <label class="block text-[11px] font-black text-gray-400 uppercase tracking-widest mb-3 px-1">Kategori</label>
                <div class="relative">
                    <select class="w-full bg-gray-50/80 border border-gray-100 rounded-2xl px-5 py-4 text-[14px] font-bold text-brand-dark outline-none focus:ring-2 focus:ring-brand-blue/10 appearance-none shadow-sm">
                        <option>Akademik</option>
                        <option>Beasiswa</option>
                        <option>Kegiatan</option>
                    </select>
                    <i data-feather="chevron-down" class="absolute right-5 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400 pointer-events-none"></i>
                </div>
            </div>
            <div>
                <label class="block text-[11px] font-black text-gray-400 uppercase tracking-widest mb-3 px-1">Jadwal Publikasi</label>
                <div class="relative">
                    <i data-feather="calendar" class="absolute left-5 top-1/2 -translate-y-1/2 w-4 h-4 text-brand-dark"></i>
                    <input type="text" value="Publikasikan Segera" class="w-full bg-gray-50/80 border border-gray-100 rounded-2xl pl-12 pr-5 py-4 text-[14px] font-bold text-brand-dark outline-none focus:ring-2 focus:ring-brand-blue/10 shadow-sm cursor-pointer" readonly>
                </div>
            </div>
        </div>

        <div>
            <label class="block text-[11px] font-black text-gray-400 uppercase tracking-widest mb-3 px-1">Ringkasan Artikel</label>
            <textarea rows="3" placeholder="Berikan deskripsi singkat untuk menarik minat pembaca..." 
                      class="w-full bg-gray-50/80 border border-gray-100 rounded-3xl p-5 text-[14px] font-medium text-brand-dark placeholder-gray-400 outline-none focus:ring-2 focus:ring-brand-blue/10 resize-none shadow-sm"></textarea>
        </div>

        <div>
            <label class="block text-[11px] font-black text-gray-400 uppercase tracking-widest mb-3 px-1">Thumbnail Berita</label>
            <div class="w-full h-64 border-2 border-dashed border-gray-200 rounded-[2rem] bg-gray-50/50 flex flex-col items-center justify-center cursor-pointer hover:bg-gray-50 hover:border-brand-blue transition-all group">
                <div class="w-12 h-12 bg-white rounded-xl shadow-sm flex items-center justify-center text-brand-dark mb-4 group-hover:scale-110 transition-transform">
                    <i data-feather="image" class="w-5 h-5"></i>
                </div>
                <h4 class="font-extrabold text-brand-dark text-[15px] mb-1">Unggah Foto Utama</h4>
                <p class="text-[12px] font-medium text-gray-400 text-center px-4">Rasio 16:9 disarankan. Maksimal ukuran file<br>5MB (JPG, PNG).</p>
            </div>
        </div>

        <div>
            <label class="block text-[11px] font-black text-gray-400 uppercase tracking-widest mb-3 px-1">Konten Artikel</label>
            <div class="bg-white border border-gray-100 rounded-[2rem] shadow-sm overflow-hidden flex flex-col min-h-[400px]">
                <div class="bg-gray-50/80 p-4 border-b border-gray-100 flex items-center gap-4 flex-wrap">
                    <div class="flex items-center gap-1">
                        <button class="px-3 py-1.5 rounded-lg text-[13px] font-black text-brand-dark hover:bg-white shadow-sm transition-colors">H1</button>
                        <button class="px-3 py-1.5 rounded-lg text-[13px] font-black text-gray-500 hover:bg-white transition-colors">H2</button>
                        <button class="px-3 py-1.5 rounded-lg text-[13px] font-black text-gray-500 hover:bg-white transition-colors">H3</button>
                    </div>
                    <div class="w-px h-6 bg-gray-200"></div>
                    <div class="flex items-center gap-1">
                        <button class="p-2 rounded-lg text-brand-dark hover:bg-white transition-colors"><i data-feather="bold" class="w-4 h-4"></i></button>
                        <button class="p-2 rounded-lg text-gray-500 hover:bg-white transition-colors"><i data-feather="italic" class="w-4 h-4"></i></button>
                        <button class="p-2 rounded-lg text-gray-500 hover:bg-white transition-colors"><i data-feather="underline" class="w-4 h-4"></i></button>
                    </div>
                    <div class="w-px h-6 bg-gray-200"></div>
                    <div class="flex items-center gap-1">
                        <button class="p-2 rounded-lg text-gray-500 hover:bg-white transition-colors"><i data-feather="list" class="w-4 h-4"></i></button>
                        <button class="p-2 rounded-lg text-gray-500 hover:bg-white transition-colors"><i data-feather="align-left" class="w-4 h-4"></i></button>
                    </div>
                    <div class="w-px h-6 bg-gray-200"></div>
                    <div class="flex items-center gap-1">
                        <button class="p-2 rounded-lg text-gray-500 hover:bg-white transition-colors"><i data-feather="link" class="w-4 h-4"></i></button>
                        <button class="p-2 rounded-lg text-gray-500 hover:bg-white transition-colors"><i data-feather="image" class="w-4 h-4"></i></button>
                        <button class="p-2 rounded-lg text-gray-500 hover:bg-white transition-colors"><i data-feather="code" class="w-4 h-4"></i></button>
                    </div>
                </div>
                <textarea class="w-full flex-1 p-8 outline-none resize-none text-[15px] font-medium text-brand-dark placeholder-gray-400 italic" placeholder="Mulai menulis di sini..."></textarea>
            </div>
        </div>

    </div>

    <div class="lg:col-span-4 space-y-6 sticky top-32">
        
        <div class="bg-gray-50/50 border border-gray-100 p-6 rounded-[2rem] shadow-sm">
            <div class="flex justify-between items-center mb-6">
                <h3 class="text-[14px] font-extrabold text-brand-dark">Publikasi</h3>
                <span class="px-3 py-1 bg-brand-blue-light text-brand-blue rounded-full text-[9px] font-black uppercase tracking-widest">Draft</span>
            </div>
            
            <div class="flex justify-between items-center mb-6">
                <span class="flex items-center gap-2 text-[12px] font-bold text-gray-500">
                    <i data-feather="refresh-ccw" class="w-3.5 h-3.5"></i> Auto-save draft
                </span>
                <span class="text-[11px] font-bold text-gray-400">2 mins ago</span>
            </div>

            <div class="space-y-3">
                <button class="w-full py-3.5 bg-brand-dark text-white rounded-xl font-black text-[13px] hover:bg-brand-blue transition-all shadow-md shadow-brand-dark/20">
                    Publish Sekarang
                </button>
                <button class="w-full py-3.5 bg-gray-200 text-brand-dark rounded-xl font-black text-[13px] hover:bg-gray-300 transition-all">
                    Simpan sebagai Draft
                </button>
            </div>
        </div>

        <div class="bg-gray-50/50 border border-gray-100 p-6 rounded-[2rem] shadow-sm">
            <h3 class="text-[14px] font-extrabold text-brand-dark mb-5">Pengaturan SEO</h3>
            
            <div class="mb-5">
                <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-2 px-1">Permalink</label>
                <input type="text" value="/berita/akademik/judul-artikel..." class="w-full px-4 py-3 bg-white border border-gray-200 rounded-xl outline-none focus:border-brand-blue text-[12px] font-mono text-gray-500 shadow-sm" readonly>
            </div>

            <div>
                <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-2 px-1">Tag Berita</label>
                <div class="flex flex-wrap gap-2 mb-3">
                    <span class="px-3 py-1.5 bg-brand-blue-light text-brand-blue rounded-lg text-[11px] font-bold">#akademik</span>
                    <span class="px-3 py-1.5 bg-brand-blue-light text-brand-blue rounded-lg text-[11px] font-bold">#pendaftaran</span>
                    <button class="w-7 h-7 bg-white border border-gray-200 text-gray-500 rounded-lg flex items-center justify-center hover:bg-gray-50 transition-colors">
                        <i data-feather="plus" class="w-3 h-3"></i>
                    </button>
                </div>
            </div>
        </div>

        <div class="bg-gray-50/50 border border-gray-100 p-6 rounded-[2rem] shadow-sm flex items-start gap-4">
            <div class="w-8 h-8 rounded-full bg-white border border-gray-200 text-brand-dark flex items-center justify-center shrink-0">
                <i data-feather="message-circle" class="w-4 h-4"></i>
            </div>
            <div>
                <h4 class="text-[12px] font-extrabold text-brand-dark mb-1">Tips Kurator</h4>
                <p class="text-[11px] font-medium text-gray-500 leading-relaxed">
                    Gunakan gambar dengan resolusi tinggi dan judul yang informatif untuk meningkatkan engagement sebesar 40%.
                </p>
            </div>
        </div>

    </div>

</div>
@endsection