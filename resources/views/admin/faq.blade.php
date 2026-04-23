@extends('layouts.admin')

@section('admin-content')
<div x-data="{ 
    activeFaq: 1,
    modalOpen: false,
    modalTitle: 'Tambah FAQ',
    formKategori: 'Prosedur',
    
    bukaModalTambah() {
        this.modalTitle = 'Tambah FAQ';
        this.formKategori = 'Prosedur';
        this.modalOpen = true;
    },
    
    bukaModalEdit(kategori) {
        this.modalTitle = 'Edit FAQ';
        this.formKategori = kategori;
        this.modalOpen = true;
    }
}">
    
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-10 gap-4">
        <div class="max-w-2xl">
            <h1 class="text-3xl font-extrabold text-brand-dark tracking-tight mb-2">Pusat Bantuan Akademik</h1>
            <p class="text-brand-gray text-[15px] font-medium leading-relaxed">
                Kelola daftar pertanyaan yang sering diajukan oleh calon mahasiswa untuk mempermudah proses admisi.
            </p>
        </div>
        <button @click="bukaModalTambah()" class="flex items-center gap-2 px-6 py-3.5 bg-brand-dark text-white rounded-xl font-bold text-[13px] hover:bg-brand-blue transition-all shadow-lg shadow-brand-dark/20 active:scale-95">
            <i data-feather="plus" class="w-4 h-4"></i> Tambah FAQ
        </button>
    </div>

    <div class="space-y-4 max-w-4xl mb-10">
        
        <div class="bg-white rounded-2xl shadow-sm border overflow-hidden transition-all duration-300"
             :class="activeFaq === 1 ? 'border-l-4 border-l-brand-dark border-t-gray-100 border-r-gray-100 border-b-gray-100' : 'border-gray-100 border-l-transparent hover:border-l-gray-300'">
            <div class="p-6 cursor-pointer flex items-start gap-4" @click="activeFaq = activeFaq === 1 ? null : 1">
                <div class="mt-1 text-gray-300 cursor-grab hover:text-gray-400"><i data-feather="grid" class="w-5 h-5"></i></div>
                <div class="flex-1">
                    <div class="flex justify-between items-center mb-3">
                        <span class="px-3 py-1.5 bg-blue-50 text-brand-blue rounded-lg text-[10px] font-black uppercase tracking-widest">Prosedur Pendaftaran</span>
                        <div class="flex items-center gap-3 text-gray-400">
                            <button @click.stop="bukaModalEdit('Prosedur')" class="hover:text-brand-dark transition-colors"><i data-feather="edit-2" class="w-4 h-4"></i></button>
                            <button @click.stop class="hover:text-red-500 transition-colors"><i data-feather="trash-2" class="w-4 h-4"></i></button>
                            <i data-feather="chevron-down" class="w-5 h-5 transition-transform duration-300 ml-2" :class="activeFaq === 1 ? 'rotate-180' : ''"></i>
                        </div>
                    </div>
                    <h3 class="text-[16px] font-extrabold text-brand-dark leading-snug pr-8">Bagaimana cara melakukan pendaftaran mahasiswa baru secara online?</h3>
                    <div x-show="activeFaq === 1" x-collapse x-cloak>
                        <div class="mt-5 bg-gray-50/80 p-6 rounded-2xl border border-gray-100 text-[13px] font-medium text-gray-600 leading-relaxed">
                            <p class="mb-3 text-brand-dark">Calon mahasiswa dapat mendaftar melalui portal SPMB dengan mengikuti langkah berikut:</p>
                            <ul class="space-y-2.5">
                                <li class="flex items-start gap-2"><div class="mt-1.5 w-1.5 h-1.5 rounded-full bg-brand-blue shrink-0"></div><span>Membuat akun pada halaman registrasi.</span></li>
                                <li class="flex items-start gap-2"><div class="mt-1.5 w-1.5 h-1.5 rounded-full bg-brand-blue shrink-0"></div><span>Melakukan verifikasi email yang dikirimkan.</span></li>
                                <li class="flex items-start gap-2"><div class="mt-1.5 w-1.5 h-1.5 rounded-full bg-brand-blue shrink-0"></div><span>Mengisi data diri, data sekolah, dan pilihan program studi.</span></li>
                                <li class="flex items-start gap-2"><div class="mt-1.5 w-1.5 h-1.5 rounded-full bg-brand-blue shrink-0"></div><span>Mengunggah dokumen pendukung (Scan Ijazah, KTP, KK).</span></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-2xl shadow-sm border overflow-hidden transition-all duration-300"
             :class="activeFaq === 2 ? 'border-l-4 border-l-brand-dark border-t-gray-100 border-r-gray-100 border-b-gray-100' : 'border-gray-100 border-l-transparent hover:border-l-gray-300'">
            <div class="p-6 cursor-pointer flex items-start gap-4" @click="activeFaq = activeFaq === 2 ? null : 2">
                <div class="mt-1 text-gray-300 cursor-grab hover:text-gray-400"><i data-feather="grid" class="w-5 h-5"></i></div>
                <div class="flex-1">
                    <div class="flex justify-between items-center mb-3">
                        <span class="px-3 py-1.5 bg-indigo-50 text-indigo-600 rounded-lg text-[10px] font-black uppercase tracking-widest">Biaya Kuliah</span>
                        <div class="flex items-center gap-3 text-gray-400">
                            <button @click.stop="bukaModalEdit('Biaya')" class="hover:text-brand-dark transition-colors"><i data-feather="edit-2" class="w-4 h-4"></i></button>
                            <button @click.stop class="hover:text-red-500 transition-colors"><i data-feather="trash-2" class="w-4 h-4"></i></button>
                            <i data-feather="chevron-down" class="w-5 h-5 transition-transform duration-300 ml-2" :class="activeFaq === 2 ? 'rotate-180' : ''"></i>
                        </div>
                    </div>
                    <h3 class="text-[16px] font-extrabold text-brand-dark leading-snug pr-8">Apa saja syarat untuk mendapatkan beasiswa penuh?</h3>
                    <div x-show="activeFaq === 2" x-collapse x-cloak>
                        <div class="mt-5 bg-gray-50/80 p-6 rounded-2xl border border-gray-100 text-[13px] font-medium text-gray-600 leading-relaxed">
                            <p>Syarat untuk mendapatkan beasiswa penuh meliputi nilai rapor rata-rata di atas 85, melampirkan sertifikat prestasi tingkat nasional atau internasional, dan lulus tahapan wawancara khusus.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>

    <template x-teleport="body">
        <div x-show="modalOpen" class="fixed inset-0 z-[999] flex items-center justify-center p-4" x-cloak>
            
            <div x-show="modalOpen" 
                 x-transition.opacity 
                 @click="modalOpen = false" 
                 class="absolute inset-0 bg-[#060B15]/80 backdrop-blur-sm cursor-pointer"></div>
            
            <div x-show="modalOpen" 
                 x-transition:enter="transition ease-out duration-300"
                 x-transition:enter-start="opacity-0 scale-95 translate-y-4"
                 x-transition:enter-end="opacity-100 scale-100 translate-y-0"
                 class="bg-white w-full max-w-2xl rounded-3xl shadow-2xl relative z-10 flex flex-col overflow-hidden">
                
                <div class="px-8 py-6 flex justify-between items-center border-b border-gray-50">
                    <h2 class="text-xl font-black text-brand-dark" x-text="modalTitle"></h2>
                    <button @click="modalOpen = false" class="text-gray-400 hover:text-brand-dark transition-colors">
                        <i data-feather="x" class="w-5 h-5"></i>
                    </button>
                </div>

                <div class="p-8 space-y-6">
                    
                    <div>
                        <label class="block text-[11px] font-black text-gray-500 uppercase tracking-widest mb-2">Pertanyaan</label>
                        <input type="text" placeholder="Masukkan pertanyaan yang sering diajukan..." 
                               class="w-full px-5 py-4 bg-[#F8FAFC] border-none rounded-xl text-[14px] font-semibold text-brand-dark placeholder-gray-400 outline-none focus:ring-2 focus:ring-brand-blue/20 transition-all">
                    </div>

                    <div>
                        <label class="block text-[11px] font-black text-gray-500 uppercase tracking-widest mb-2">Jawaban</label>
                        <div class="bg-[#F8FAFC] rounded-xl overflow-hidden border border-gray-50 focus-within:ring-2 focus-within:ring-brand-blue/20 transition-all">
                            <div class="flex items-center gap-2 px-4 py-3 border-b border-gray-100/60 text-gray-500">
                                <button class="hover:text-brand-dark transition-colors"><i data-feather="bold" class="w-4 h-4"></i></button>
                                <button class="hover:text-brand-dark transition-colors"><i data-feather="italic" class="w-4 h-4"></i></button>
                                <button class="hover:text-brand-dark transition-colors"><i data-feather="list" class="w-4 h-4"></i></button>
                                <div class="w-px h-4 bg-gray-200 mx-1"></div>
                                <button class="hover:text-brand-dark transition-colors"><i data-feather="link" class="w-4 h-4"></i></button>
                            </div>
                            <textarea rows="4" placeholder="Berikan jawaban yang jelas dan detail..." 
                                      class="w-full px-5 py-4 bg-transparent border-none text-[14px] font-medium text-brand-dark placeholder-gray-400 outline-none resize-none"></textarea>
                        </div>
                    </div>

                    <div>
                        <label class="block text-[11px] font-black text-gray-500 uppercase tracking-widest mb-3">Kategori</label>
                        <div class="flex flex-wrap gap-2">
                            <template x-for="kategori in ['Prosedur', 'Biaya', 'Akademik', 'Dokumen']">
                                <button type="button" 
                                        @click="formKategori = kategori"
                                        :class="formKategori === kategori ? 'bg-brand-dark text-white' : 'bg-[#F1F5F9] text-gray-500 hover:bg-gray-200'"
                                        class="px-5 py-2 rounded-full text-[12px] font-bold transition-colors"
                                        x-text="kategori">
                                </button>
                            </template>
                        </div>
                    </div>

                </div>

                <div class="px-8 py-6 bg-white border-t border-gray-50 flex items-center justify-end gap-4">
                    <button @click="modalOpen = false" class="px-6 py-2.5 text-gray-500 font-bold text-[13px] hover:text-brand-dark transition-colors">
                        Batal
                    </button>
                    <button class="px-8 py-3 bg-brand-dark text-white rounded-xl font-bold text-[13px] hover:bg-brand-blue shadow-lg shadow-brand-dark/20 transition-all">
                        Simpan FAQ
                    </button>
                </div>
                
            </div>
        </div>
    </template>

</div>

<script>
    document.addEventListener('alpine:initialized', () => {
        feather.replace();
    });
</script>
@endsection