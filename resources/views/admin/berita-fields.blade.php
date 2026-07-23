{{-- 
    Partial: field-field form berita.
    Pakai prefix 'tambah' atau 'edit' agar id tidak bentrok.
    Variable: $prefix (string)
--}}
@php $p = $prefix; @endphp

<div class="space-y-5">

    {{-- Judul --}}
    <div>
        <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-2">
            Judul Berita <span class="text-red-500">*</span>
        </label>
        <input type="text" name="judul" id="{{ $p }}-judul" required
               placeholder="Masukkan judul artikel yang menarik..."
               class="w-full px-4 py-3.5 bg-gray-50 border border-gray-200 rounded-xl outline-none focus:border-brand-blue font-bold text-[14px] transition-colors">
    </div>

    {{-- Kategori & Tanggal --}}
    <div class="grid grid-cols-2 gap-4">
        <div>
            <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-2">
                Kategori <span class="text-red-500">*</span>
            </label>
            <div class="relative">
                <select name="kategori" id="{{ $p }}-kategori" required
                        class="w-full px-4 py-3.5 bg-gray-50 border border-gray-200 rounded-xl outline-none focus:border-brand-blue font-bold text-[13px] appearance-none cursor-pointer transition-colors">
                    <option value="Akademik">Akademik</option>
                    <option value="Beasiswa">Beasiswa</option>
                    <option value="Kegiatan">Kegiatan</option>
                    <option value="Informasi">Informasi</option>
                </select>
                <i data-feather="chevron-down" class="absolute right-4 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400 pointer-events-none"></i>
            </div>
        </div>
        <div>
            <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-2">Tanggal Publish</label>
            <input type="datetime-local" name="tanggal_publish" id="{{ $p }}-tanggal_publish"
                   class="w-full px-4 py-3.5 bg-gray-50 border border-gray-200 rounded-xl outline-none focus:border-brand-blue font-bold text-[13px] cursor-pointer transition-colors">
        </div>
    </div>

    {{-- Status --}}
    <div>
        <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-2">Status <span class="text-red-500">*</span></label>
        <div class="flex gap-2">
            <label class="flex-1 flex items-center gap-2 px-3 py-2.5 border-2 border-green-400 bg-green-50 rounded-xl cursor-pointer transition-all">
                <input type="radio" name="{{ $p }}-status" value="Published" checked class="sr-only"
                       onchange="updateStatusUI('{{ $p }}')">
                <div class="w-2 h-2 rounded-full bg-green-500 shrink-0"></div>
                <span class="text-[12px] font-bold text-brand-dark">Published</span>
            </label>
            <label class="flex-1 flex items-center gap-2 px-3 py-2.5 border-2 border-gray-200 rounded-xl cursor-pointer transition-all">
                <input type="radio" name="{{ $p }}-status" value="Draft" class="sr-only"
                       onchange="updateStatusUI('{{ $p }}')">
                <div class="w-2 h-2 rounded-full bg-gray-400 shrink-0"></div>
                <span class="text-[12px] font-bold text-brand-dark">Draft</span>
            </label>
        </div>
        {{-- Hidden input untuk submit --}}
        <input type="hidden" name="status" id="{{ $p }}-status-hidden" value="Published">
    </div>

    {{-- Ringkasan --}}
    <div>
        <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-2">Ringkasan</label>
        <textarea name="ringkasan" id="{{ $p }}-ringkasan" rows="2" maxlength="500"
                  placeholder="Deskripsi singkat (maks. 500 karakter)..."
                  class="w-full px-4 py-3.5 bg-gray-50 border border-gray-200 rounded-xl outline-none focus:border-brand-blue font-medium text-[13px] resize-none transition-colors"></textarea>
    </div>

    {{-- Thumbnail --}}
    <div>
        <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-2">
            Thumbnail <span class="text-gray-400 font-medium normal-case">(opsional{{ $p==='edit' ? ', kosongkan jika tidak diganti' : '' }})</span>
        </label>
        <div class="relative w-full h-40 border-2 border-dashed border-gray-200 rounded-2xl bg-gray-50 hover:border-brand-blue hover:bg-blue-50/30 transition-all overflow-hidden cursor-pointer group"
             onclick="document.getElementById('{{ $p }}-thumbnail').click()">
            <img id="{{ $p }}-thumb-preview" src="" class="absolute inset-0 w-full h-full object-cover rounded-2xl hidden" alt="">
            <div id="{{ $p }}-thumb-placeholder" class="absolute inset-0 flex flex-col items-center justify-center gap-2 text-center">
                <div class="w-10 h-10 bg-white rounded-xl shadow-sm flex items-center justify-center text-gray-400 group-hover:text-brand-blue transition-colors">
                    <i data-feather="image" class="w-5 h-5"></i>
                </div>
                <p class="font-bold text-brand-dark text-[12px]">Klik untuk unggah foto</p>
                <p class="text-gray-400 text-[10px]">JPG, PNG, WebP · Maks. 5MB</p>
            </div>
            <input type="file" id="{{ $p }}-thumbnail" name="thumbnail"
                   accept="image/jpeg,image/png,image/webp" class="sr-only"
                   onchange="previewThumb(this, '{{ $p }}')">
        </div>
    </div>

    {{-- Konten --}}
    <div>
        <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-2">
            Konten <span class="text-red-500">*</span>
        </label>
        <div class="bg-white border border-gray-200 rounded-2xl overflow-hidden shadow-sm">
            {{-- Toolbar --}}
            <div class="bg-gray-50 px-4 py-2.5 border-b border-gray-100 flex items-center gap-2 flex-wrap">
                <button type="button" onclick="insertTag('{{ $p }}','h2')" class="px-2 py-1 rounded text-[11px] font-black text-gray-600 hover:bg-white transition-all">H2</button>
                <button type="button" onclick="insertTag('{{ $p }}','h3')" class="px-2 py-1 rounded text-[11px] font-black text-gray-400 hover:bg-white transition-all">H3</button>
                <div class="w-px h-4 bg-gray-200"></div>
                <button type="button" onclick="insertTag('{{ $p }}','b')"  class="p-1.5 rounded hover:bg-white transition-colors" title="Bold"><i data-feather="bold"      class="w-3.5 h-3.5 text-gray-600"></i></button>
                <button type="button" onclick="insertTag('{{ $p }}','i')"  class="p-1.5 rounded hover:bg-white transition-colors" title="Italic"><i data-feather="italic"    class="w-3.5 h-3.5 text-gray-400"></i></button>
                <button type="button" onclick="insertTag('{{ $p }}','u')"  class="p-1.5 rounded hover:bg-white transition-colors" title="Underline"><i data-feather="underline" class="w-3.5 h-3.5 text-gray-400"></i></button>
                <div class="w-px h-4 bg-gray-200"></div>
                <button type="button" onclick="insertTag('{{ $p }}','ul')" class="p-1.5 rounded hover:bg-white transition-colors" title="List"><i data-feather="list"        class="w-3.5 h-3.5 text-gray-400"></i></button>
                <button type="button" onclick="insertTag('{{ $p }}','ol')" class="p-1.5 rounded hover:bg-white transition-colors" title="Numbered"><i data-feather="hash"     class="w-3.5 h-3.5 text-gray-400"></i></button>
                <div class="w-px h-4 bg-gray-200"></div>
                <button type="button" onclick="insertTag('{{ $p }}','link')" class="p-1.5 rounded hover:bg-white transition-colors" title="Link"><i data-feather="link"       class="w-3.5 h-3.5 text-gray-400"></i></button>
                <button type="button" onclick="insertTag('{{ $p }}','img')"  class="p-1.5 rounded hover:bg-white transition-colors" title="Gambar"><i data-feather="image"     class="w-3.5 h-3.5 text-gray-400"></i></button>
                <button type="button" onclick="insertTag('{{ $p }}','hr')"   class="p-1.5 rounded hover:bg-white transition-colors" title="Garis"><i data-feather="minus"      class="w-3.5 h-3.5 text-gray-400"></i></button>
            </div>
            <textarea name="konten" id="{{ $p }}-konten" required
                      placeholder="Tulis konten artikel di sini..."
                      class="w-full min-h-[280px] p-5 outline-none resize-y text-[13px] font-medium text-brand-dark placeholder-gray-400 leading-relaxed"></textarea>
        </div>
    </div>

</div>

{{-- Sync hidden status input saat radio berubah --}}
<script>
document.querySelectorAll('input[name="{{ $p }}-status"]').forEach(r => {
    r.addEventListener('change', () => {
        document.getElementById('{{ $p }}-status-hidden').value = r.value;
    });
});
</script>