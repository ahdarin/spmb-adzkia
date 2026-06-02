@extends('layouts.admin')

@section('admin-content')
<form action="{{ route('admin.berita.update', $berita->id) }}" method="POST" enctype="multipart/form-data" 
      x-data="beritaEditor(@js(old('konten', $berita->konten)), '{{ $berita->thumbnail ? asset('uploads/berita/' . $berita->thumbnail) : '' }}')">
    @csrf
    @method('PUT') 

    <div class="flex items-center justify-between mb-8">
        <div class="flex items-center gap-4">
            <a href="{{ route('admin.berita.index') }}" class="p-2 bg-white border border-gray-200 rounded-full text-brand-dark hover:bg-gray-50 transition-colors shadow-sm">
                <i data-feather="arrow-left" class="w-5 h-5"></i>
            </a>
            <div class="flex items-center gap-2 text-[11px] font-black uppercase tracking-widest text-gray-400">
                <a href="{{ route('admin.dashboard') }}" class="hover:text-brand-dark transition-colors">Dashboard</a>
                <i data-feather="chevron-right" class="w-3 h-3"></i>
                <a href="{{ route('admin.berita.index') }}" class="hover:text-brand-dark transition-colors">Manajemen Berita</a>
                <i data-feather="chevron-right" class="w-3 h-3"></i>
                <span class="text-brand-dark">Edit Berita</span>
            </div>
        </div>
        <button type="button" @click="showPreview = true" class="flex items-center gap-2 px-5 py-2.5 bg-brand-blue-light text-brand-blue rounded-xl font-bold text-[12px] hover:bg-blue-100 transition-all shadow-sm">
            <i data-feather="eye" class="w-4 h-4"></i> Preview Artikel
        </button>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
        
        <div class="lg:col-span-8 space-y-8">
            
            <div>
                <label class="block text-[11px] font-black text-gray-400 uppercase tracking-widest mb-3 px-1">Judul Berita</label>
                <textarea x-ref="judul" name="judul" rows="2" required class="w-full bg-white border border-gray-100 rounded-3xl p-6 text-3xl font-extrabold text-brand-dark placeholder-gray-300 outline-none focus:ring-2 focus:ring-brand-blue/10 resize-none shadow-sm">{{ old('judul', $berita->judul) }}</textarea>
            </div>

            <div class="grid grid-cols-2 gap-6">
                <div>
                    <label class="block text-[11px] font-black text-gray-400 uppercase tracking-widest mb-3 px-1">Kategori</label>
                    <div class="relative">
                        <select x-ref="kategori" name="kategori" required class="w-full bg-gray-50/80 border border-gray-100 rounded-2xl px-5 py-4 text-[14px] font-bold text-brand-dark outline-none focus:ring-2 focus:ring-brand-blue/10 appearance-none shadow-sm">
                            <option value="Akademik" {{ $berita->kategori == 'Akademik' ? 'selected' : '' }}>Akademik</option>
                            <option value="Beasiswa" {{ $berita->kategori == 'Beasiswa' ? 'selected' : '' }}>Beasiswa</option>
                            <option value="Kegiatan" {{ $berita->kategori == 'Kegiatan' ? 'selected' : '' }}>Kegiatan</option>
                            <option value="Informasi" {{ $berita->kategori == 'Informasi' ? 'selected' : '' }}>Informasi</option>
                        </select>
                        <i data-feather="chevron-down" class="absolute right-5 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400 pointer-events-none"></i>
                    </div>
                </div>
                <div>
                    <label class="block text-[11px] font-black text-gray-400 uppercase tracking-widest mb-3 px-1">Jadwal Publikasi</label>
                    <div class="relative">
                        <input type="datetime-local" name="tanggal_publish" value="{{ $berita->tanggal_publish ? \Carbon\Carbon::parse($berita->tanggal_publish)->format('Y-m-d\TH:i') : '' }}" class="w-full bg-gray-50/80 border border-gray-100 rounded-2xl px-5 py-4 text-[14px] font-bold text-brand-dark outline-none focus:ring-2 focus:ring-brand-blue/10 shadow-sm cursor-pointer">
                    </div>
                </div>
            </div>

            <div>
                <label class="block text-[11px] font-black text-gray-400 uppercase tracking-widest mb-3 px-1">Ringkasan Artikel</label>
                <textarea name="ringkasan" rows="3" required class="w-full bg-gray-50/80 border border-gray-100 rounded-3xl p-5 text-[14px] font-medium text-brand-dark placeholder-gray-400 outline-none focus:ring-2 focus:ring-brand-blue/10 resize-none shadow-sm">{{ old('ringkasan', $berita->ringkasan) }}</textarea>
            </div>

            <div>
                <label class="block text-[11px] font-black text-gray-400 uppercase tracking-widest mb-3 px-1">Thumbnail Berita (Abaikan jika tidak diganti)</label>
                <label class="relative flex flex-col items-center justify-center w-full h-64 border-2 border-dashed border-gray-200 rounded-[2rem] bg-gray-50/50 hover:bg-gray-50 hover:border-brand-blue transition-all group overflow-hidden cursor-pointer">
                    
                    <input type="file" name="thumbnail" accept="image/*" @change="fileChosen" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-20">
                    
                    <div class="flex flex-col items-center justify-center text-center z-10" x-show="!imageUrl">
                        <div class="w-12 h-12 bg-white rounded-xl shadow-sm flex items-center justify-center text-brand-dark mb-4 group-hover:scale-110 transition-transform">
                            <i data-feather="image" class="w-5 h-5"></i>
                        </div>
                        <h4 class="font-extrabold text-brand-dark text-[15px] mb-1">Ganti Foto Utama</h4>
                        <p class="text-[12px] font-medium text-gray-400 px-4">Klik untuk mengunggah gambar baru.<br>Maksimal ukuran file 5MB.</p>
                    </div>

                    <template x-if="imageUrl">
                        <img :src="imageUrl" class="absolute inset-0 w-full h-full object-cover z-10 rounded-[2rem]">
                    </template>
                </label>
            </div>

            <div>
                <label class="block text-[11px] font-black text-gray-400 uppercase tracking-widest mb-3 px-1">Konten Artikel (Mendukung Format HTML)</label>
                <div class="bg-white border border-gray-100 rounded-[2rem] shadow-sm overflow-hidden flex flex-col min-h-[400px]">
                    <div class="bg-gray-50/80 p-4 border-b border-gray-100 flex items-center gap-4 flex-wrap">
                        <div class="flex items-center gap-1">
                            <button type="button" @click="insertTag('h1')" class="px-3 py-1.5 rounded-lg text-[13px] font-black text-brand-dark hover:bg-white shadow-sm transition-colors">H1</button>
                            <button type="button" @click="insertTag('h2')" class="px-3 py-1.5 rounded-lg text-[13px] font-black text-gray-500 hover:bg-white transition-colors">H2</button>
                            <button type="button" @click="insertTag('h3')" class="px-3 py-1.5 rounded-lg text-[13px] font-black text-gray-500 hover:bg-white transition-colors">H3</button>
                        </div>
                        <div class="w-px h-6 bg-gray-200"></div>
                        <div class="flex items-center gap-1">
                            <button type="button" @click="insertTag('b')" class="p-2 rounded-lg text-brand-dark hover:bg-white transition-colors"><i data-feather="bold" class="w-4 h-4"></i></button>
                            <button type="button" @click="insertTag('i')" class="p-2 rounded-lg text-gray-500 hover:bg-white transition-colors"><i data-feather="italic" class="w-4 h-4"></i></button>
                            <button type="button" @click="insertTag('u')" class="p-2 rounded-lg text-gray-500 hover:bg-white transition-colors"><i data-feather="underline" class="w-4 h-4"></i></button>
                        </div>
                        <div class="w-px h-6 bg-gray-200"></div>
                        <div class="flex items-center gap-1">
                            <button type="button" @click="insertTag('ul')" class="p-2 rounded-lg text-gray-500 hover:bg-white transition-colors"><i data-feather="list" class="w-4 h-4"></i></button>
                        </div>
                        <div class="w-px h-6 bg-gray-200"></div>
                        <div class="flex items-center gap-1">
                            <button type="button" @click="insertTag('link')" class="p-2 rounded-lg text-gray-500 hover:bg-white transition-colors"><i data-feather="link" class="w-4 h-4"></i></button>
                            <button type="button" @click="insertTag('img')" class="p-2 rounded-lg text-gray-500 hover:bg-white transition-colors"><i data-feather="image" class="w-4 h-4"></i></button>
                        </div>
                    </div>
                    
                    <textarea x-ref="editor" x-model="konten" name="konten" required class="w-full flex-1 p-8 outline-none resize-none text-[15px] font-medium text-brand-dark placeholder-gray-400 leading-relaxed" placeholder="Mulai menulis di sini..."></textarea>
                </div>
            </div>

        </div>

        <div class="lg:col-span-4 space-y-6 sticky top-32">
            
            <div class="bg-gray-50/50 border border-gray-100 p-6 rounded-[2rem] shadow-sm">
                <div class="flex justify-between items-center mb-6">
                    <h3 class="text-[14px] font-extrabold text-brand-dark">Status Publikasi</h3>
                    <span class="px-3 py-1 bg-brand-blue-light text-brand-blue rounded-full text-[9px] font-black uppercase tracking-widest">{{ $berita->status }}</span>
                </div>

                <div class="space-y-3">
                    <button type="submit" name="status" value="Published" class="w-full py-3.5 bg-brand-dark text-white rounded-xl font-black text-[13px] hover:bg-brand-blue transition-all shadow-md shadow-brand-dark/20">
                        Update & Publish
                    </button>
                    <button type="submit" name="status" value="Draft" class="w-full py-3.5 bg-gray-200 text-brand-dark rounded-xl font-black text-[13px] hover:bg-gray-300 transition-all">
                        Simpan sebagai Draft
                    </button>
                </div>
            </div>

            <div class="bg-gray-50/50 border border-gray-100 p-6 rounded-[2rem] shadow-sm">
                <h3 class="text-[14px] font-extrabold text-brand-dark mb-5">Pengaturan SEO</h3>
                <div class="mb-5">
                    <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-2 px-1">Permalink</label>
                    <input type="text" value="/berita/{{ $berita->slug }}" class="w-full px-4 py-3 bg-white border border-gray-200 rounded-xl outline-none focus:border-brand-blue text-[12px] font-mono text-gray-500 shadow-sm" readonly>
                </div>
            </div>

        </div>

    </div>

    <div x-show="showPreview" class="fixed inset-0 z-[999] flex items-center justify-center p-4 bg-brand-dark/80 backdrop-blur-sm" x-cloak>
        <div class="bg-white w-full max-w-4xl max-h-[90vh] rounded-3xl overflow-y-auto p-10 relative">
            <button @click="showPreview = false" type="button" class="absolute top-6 right-6 p-2 bg-gray-100 rounded-full hover:bg-red-500 hover:text-white transition-colors">
                <i data-feather="x"></i>
            </button>
            
            <span class="px-3 py-1 bg-brand-blue-light text-brand-blue rounded-lg text-[10px] font-black uppercase tracking-widest" x-text="$refs.kategori.value || 'Kategori'"></span>
            
            <h1 class="text-4xl font-black text-brand-dark mt-4 mb-8 leading-tight" x-text="$refs.judul.value || 'Judul Belum Diisi'"></h1>
            
            <template x-if="imageUrl">
                <img :src="imageUrl" class="w-full h-auto max-h-[400px] object-cover rounded-2xl mb-8 border border-gray-100">
            </template>
            
            <div class="prose max-w-none text-gray-700 leading-relaxed font-medium" x-html="konten || '<i>Konten masih kosong...</i>'"></div>
        </div>
    </div>
</form>

<script>
document.addEventListener('alpine:init', () => {
    // PERUBAHAN DI SINI: Menerima inisialisasi konten
    Alpine.data('beritaEditor', (initialKonten, initialImage) => ({
        showPreview: false,
        imageUrl: initialImage,
        konten: initialKonten || '',

        fileChosen(event) {
            const file = event.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = (e) => { this.imageUrl = e.target.result; };
                reader.readAsDataURL(file);
            }
        },

        insertTag(tag) {
            const textarea = this.$refs.editor;
            const start = textarea.selectionStart;
            const end = textarea.selectionEnd;
            const text = this.konten;
            const selectedText = text.substring(start, end);
            let formatted = '';

            switch(tag) {
                case 'h1': formatted = `<h1>${selectedText || 'Judul H1'}</h1>`; break;
                case 'h2': formatted = `<h2>${selectedText || 'Judul H2'}</h2>`; break;
                case 'h3': formatted = `<h3>${selectedText || 'Judul H3'}</h3>`; break;
                case 'b': formatted = `<b>${selectedText || 'Teks Tebal'}</b>`; break;
                case 'i': formatted = `<i>${selectedText || 'Teks Miring'}</i>`; break;
                case 'u': formatted = `<u>${selectedText || 'Teks Garis Bawah'}</u>`; break;
                case 'ul': formatted = `<ul>\n  <li>${selectedText || 'List Item'}</li>\n</ul>`; break;
                case 'link': 
                    const url = prompt('Masukkan URL Link:');
                    if(url) formatted = `<a href="${url}" target="_blank" style="color: blue; text-decoration: underline;">${selectedText || 'Klik di sini'}</a>`;
                    break;
                case 'img': 
                    const imgUrl = prompt('Masukkan URL Gambar dari Web:');
                    if(imgUrl) formatted = `<br><img src="${imgUrl}" alt="image" style="width: 100%; border-radius: 12px; margin-top: 15px; margin-bottom: 15px;"><br>`;
                    break;
            }

            if(formatted) {
                this.konten = text.substring(0, start) + formatted + text.substring(end);
            }
            setTimeout(() => { textarea.focus(); }, 50);
        }
    }));
});
</script>

<script>
    document.addEventListener("DOMContentLoaded", () => {
        setTimeout(() => { if (typeof feather !== 'undefined') feather.replace(); }, 50);
    });
</script>
@endsection