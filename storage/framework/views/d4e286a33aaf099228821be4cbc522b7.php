<?php $__env->startSection('admin-content'); ?>
<form action="<?php echo e(route('admin.berita.store')); ?>" method="POST" enctype="multipart/form-data" x-data="beritaEditor()">
    <?php echo csrf_field(); ?>
    
    <input type="hidden" name="status" x-model="status">

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
        <button type="button" @click="previewArticle()" class="flex items-center gap-2 px-5 py-2.5 bg-brand-blue-light text-brand-blue rounded-xl font-bold text-[12px] hover:bg-blue-100 transition-all shadow-sm">
            <i data-feather="eye" class="w-4 h-4"></i> Preview Artikel
        </button>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
        
        <div class="lg:col-span-8 space-y-8">
            
            <div>
                <label class="block text-[11px] font-black text-gray-400 uppercase tracking-widest mb-3 px-1">Judul Berita</label>
                <textarea name="judul" rows="2" placeholder="Masukkan judul artikel yang menarik..." required
                          class="w-full bg-white border border-gray-100 rounded-3xl p-6 text-3xl font-extrabold text-brand-dark placeholder-gray-300 outline-none focus:ring-2 focus:ring-brand-blue/10 resize-none shadow-sm"></textarea>
            </div>

            <div class="grid grid-cols-2 gap-6">
                <div>
                    <label class="block text-[11px] font-black text-gray-400 uppercase tracking-widest mb-3 px-1">Kategori</label>
                    <div class="relative">
                        <select name="kategori" required class="w-full bg-gray-50/80 border border-gray-100 rounded-2xl px-5 py-4 text-[14px] font-bold text-brand-dark outline-none focus:ring-2 focus:ring-brand-blue/10 appearance-none shadow-sm">
                            <option value="Akademik">Akademik</option>
                            <option value="Beasiswa">Beasiswa</option>
                            <option value="Kegiatan">Kegiatan</option>
                        </select>
                        <i data-feather="chevron-down" class="absolute right-5 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400 pointer-events-none"></i>
                    </div>
                </div>
                <div>
                    <label class="block text-[11px] font-black text-gray-400 uppercase tracking-widest mb-3 px-1">Jadwal Publikasi</label>
                    <div class="relative">
                        <input type="datetime-local" name="tanggal_publish" class="w-full bg-gray-50/80 border border-gray-100 rounded-2xl px-5 py-4 text-[14px] font-bold text-brand-dark outline-none focus:ring-2 focus:ring-brand-blue/10 shadow-sm cursor-pointer">
                    </div>
                </div>
            </div>

            <div>
                <label class="block text-[11px] font-black text-gray-400 uppercase tracking-widest mb-3 px-1">Ringkasan Artikel</label>
                <textarea name="ringkasan" rows="3" placeholder="Berikan deskripsi singkat untuk menarik minat pembaca..." required
                          class="w-full bg-gray-50/80 border border-gray-100 rounded-3xl p-5 text-[14px] font-medium text-brand-dark placeholder-gray-400 outline-none focus:ring-2 focus:ring-brand-blue/10 resize-none shadow-sm"></textarea>
            </div>

            <div>
                <label class="block text-[11px] font-black text-gray-400 uppercase tracking-widest mb-3 px-1">Thumbnail Berita</label>
                <label class="relative flex flex-col items-center justify-center w-full h-64 border-2 border-dashed border-gray-200 rounded-[2rem] bg-gray-50/50 hover:bg-gray-50 hover:border-brand-blue transition-all group overflow-hidden cursor-pointer">
                    
                    <input type="file" name="thumbnail" accept="image/*" @change="fileChosen" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-20">
                    
                    <div class="flex flex-col items-center justify-center text-center z-10" x-show="!imageUrl">
                        <div class="w-12 h-12 bg-white rounded-xl shadow-sm flex items-center justify-center text-brand-dark mb-4 group-hover:scale-110 transition-transform">
                            <i data-feather="image" class="w-5 h-5"></i>
                        </div>
                        <h4 class="font-extrabold text-brand-dark text-[15px] mb-1">Unggah Foto Utama</h4>
                        <p class="text-[12px] font-medium text-gray-400 px-4">Rasio 16:9 disarankan.<br>Maksimal ukuran file 5MB (JPG, PNG).</p>
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
                    
                    <textarea x-ref="editor" x-model="konten" name="konten" required class="w-full flex-1 p-8 outline-none resize-none text-[15px] font-medium text-brand-dark placeholder-gray-400 leading-relaxed" placeholder="Ketik konten artikel di sini. Blok teks lalu tekan tombol di atas untuk menebalkan (Bold) atau mengubah menjadi judul (H1)..."></textarea>
                </div>
            </div>

        </div>

        <div class="lg:col-span-4 space-y-6 sticky top-32">
            
            <div class="bg-gray-50/50 border border-gray-100 p-6 rounded-[2rem] shadow-sm">
                <div class="flex justify-between items-center mb-6">
                    <h3 class="text-[14px] font-extrabold text-brand-dark">Publikasi</h3>
                    <span class="px-3 py-1 bg-brand-blue-light text-brand-blue rounded-full text-[9px] font-black uppercase tracking-widest" x-text="status"></span>
                </div>

                <div class="space-y-3">
                    <button type="submit" @click="status = 'Published'" class="w-full py-3.5 bg-brand-dark text-white rounded-xl font-black text-[13px] hover:bg-brand-blue transition-all shadow-md shadow-brand-dark/20">
                        Publish Sekarang
                    </button>
                    <button type="submit" @click="status = 'Draft'" class="w-full py-3.5 bg-gray-200 text-brand-dark rounded-xl font-black text-[13px] hover:bg-gray-300 transition-all">
                        Simpan sebagai Draft
                    </button>
                </div>
            </div>

            <div class="bg-gray-50/50 border border-gray-100 p-6 rounded-[2rem] shadow-sm">
                <h3 class="text-[14px] font-extrabold text-brand-dark mb-5">Pengaturan SEO</h3>
                <div class="mb-5">
                    <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-2 px-1">Permalink (Otomatis)</label>
                    <input type="text" value="/berita/judul-artikel" class="w-full px-4 py-3 bg-white border border-gray-200 rounded-xl outline-none focus:border-brand-blue text-[12px] font-mono text-gray-500 shadow-sm" readonly>
                </div>
            </div>

        </div>

    </div>
</form>

<script>
document.addEventListener('alpine:init', () => {
    Alpine.data('beritaEditor', () => ({
        status: 'Published',
        imageUrl: null,
        konten: '',

        // Fungsi Preview Gambar Upload
        fileChosen(event) {
            const file = event.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = (e) => {
                    this.imageUrl = e.target.result;
                };
                reader.readAsDataURL(file);
            }
        },

        // Fungsi Memasukkan Tag HTML ke dalam Textarea
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
                    const url = prompt('Masukkan URL Link (Contoh: https://google.com):');
                    if(url) formatted = `<a href="${url}" target="_blank" style="color: blue; text-decoration: underline;">${selectedText || 'Klik di sini'}</a>`;
                    else return;
                    break;
                case 'img': 
                    const imgUrl = prompt('Masukkan URL Gambar dari Web:');
                    if(imgUrl) formatted = `<br><img src="${imgUrl}" alt="image" style="width: 100%; border-radius: 12px; margin-top: 15px; margin-bottom: 15px;"><br>`;
                    else return;
                    break;
            }

            // Ganti teks di dalam textarea
            this.konten = text.substring(0, start) + formatted + text.substring(end);
            
            // Kembalikan kursor ke dalam textarea
            setTimeout(() => {
                textarea.focus();
            }, 50);
        },

        previewArticle() {
            alert('Fitur Preview Berhasil! Nanti kita akan buat sistem membuka Tab Baru untuk melihat hasil artikelnya ya.');
        }
    }));
});
</script>


<script>
    document.addEventListener("DOMContentLoaded", () => {
        setTimeout(() => { if (typeof feather !== 'undefined') feather.replace(); }, 50);
    });
</script>
<?php $__env->stopSection(); ?> 
<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\Database\spmb-adzkia\resources\views/admin/berita-create.blade.php ENDPATH**/ ?>