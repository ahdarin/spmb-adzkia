<?php $__env->startSection('admin-content'); ?>
<div x-data="faqManager()">
    
    
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-10 gap-4">
        <div class="max-w-2xl">
            <h1 class="text-3xl font-extrabold text-brand-dark tracking-tight mb-2">Pusat Bantuan Akademik</h1>
            <p class="text-brand-gray text-[15px] font-medium leading-relaxed">
                Kelola daftar pertanyaan yang sering diajukan untuk mempermudah pengunjung umum dan pendaftar.
            </p>
        </div>
        <button @click="bukaModalTambah()" class="flex items-center gap-2 px-6 py-3.5 bg-brand-dark text-white rounded-xl font-bold text-[13px] hover:bg-brand-blue transition-all shadow-lg shadow-brand-dark/20 active:scale-95">
            <i data-feather="plus" class="w-4 h-4"></i> Tambah FAQ
        </button>
    </div>

    
    <?php if(session('success')): ?>
        <div class="mb-6 max-w-4xl p-4 bg-green-50 border-l-4 border-green-500 rounded-r-xl flex items-start gap-3">
            <i data-feather="check-circle" class="w-5 h-5 text-green-600 shrink-0"></i>
            <p class="text-[13px] font-bold text-green-800"><?php echo e(session('success')); ?></p>
        </div>
    <?php endif; ?>

    
    <div class="space-y-4 max-w-4xl mb-10">
        <?php $__empty_1 = true; $__currentLoopData = $faqs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $faq): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
        <div class="bg-white rounded-2xl shadow-sm border overflow-hidden transition-all duration-300"
             :class="activeFaq === <?php echo e($faq->id); ?> ? 'border-l-4 border-l-brand-dark border-t-gray-100 border-r-gray-100 border-b-gray-100' : 'border-gray-100 border-l-transparent hover:border-l-gray-300'">
            <div class="p-6 cursor-pointer flex items-start gap-4" @click="activeFaq = activeFaq === <?php echo e($faq->id); ?> ? null : <?php echo e($faq->id); ?>">
                <div class="mt-1 text-gray-300 cursor-grab hover:text-gray-400"><i data-feather="grid" class="w-5 h-5"></i></div>
                <div class="flex-1">
                    <div class="flex justify-between items-center mb-3">
                        
                        <span class="px-3 py-1.5 rounded-lg text-[10px] font-black uppercase tracking-widest 
                            <?php echo e($faq->kategori == 'Dashboard Utama' ? 'bg-blue-50 text-brand-blue' : 'bg-indigo-50 text-indigo-600'); ?>">
                            <?php echo e($faq->kategori); ?>

                        </span>
                        
                        <div class="flex items-center gap-3 text-gray-400">
                            
                            <button @click.stop='bukaModalEdit(<?php echo e($faq->id); ?>, <?php echo e(json_encode($faq->pertanyaan)); ?>, <?php echo e(json_encode($faq->jawaban)); ?>, "<?php echo e($faq->kategori); ?>")' class="hover:text-brand-dark transition-colors" title="Edit FAQ">
                                <i data-feather="edit-2" class="w-4 h-4"></i>
                            </button>
                            
                            
                            <form action="/admin/faq/<?php echo e($faq->id); ?>" method="POST" class="inline" @click.stop onsubmit="return confirm('Apakah Anda yakin ingin menghapus FAQ ini?');">
                                <?php echo csrf_field(); ?>
                                <?php echo method_field('DELETE'); ?>
                                <button type="submit" class="hover:text-red-500 transition-colors" title="Hapus FAQ">
                                    <i data-feather="trash-2" class="w-4 h-4"></i>
                                </button>
                            </form>
                            
                            <i data-feather="chevron-down" class="w-5 h-5 transition-transform duration-300 ml-2" :class="activeFaq === <?php echo e($faq->id); ?> ? 'rotate-180' : ''"></i>
                        </div>
                    </div>
                    <h3 class="text-[16px] font-extrabold text-brand-dark leading-snug pr-8"><?php echo e($faq->pertanyaan); ?></h3>
                    <div x-show="activeFaq === <?php echo e($faq->id); ?>" x-collapse x-cloak>
                        <div class="mt-5 bg-gray-50/80 p-6 rounded-2xl border border-gray-100 text-[13px] font-medium text-gray-600 leading-relaxed whitespace-pre-line">
                            <?php echo e($faq->jawaban); ?>

                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
        <div class="text-center py-12 border-2 border-dashed border-gray-200 rounded-2xl bg-gray-50/50">
            <i data-feather="help-circle" class="w-10 h-10 text-gray-300 mx-auto mb-3"></i>
            <h3 class="text-sm font-bold text-gray-500">Belum ada FAQ</h3>
            <p class="text-[12px] text-gray-400 mt-1">Tambahkan pertanyaan umum pertama Anda untuk membantu pendaftar.</p>
        </div>
        <?php endif; ?>
    </div>

    
    <template x-teleport="body">
        <div x-show="modalOpen" class="fixed inset-0 z-[999] flex items-center justify-center p-4" x-cloak>
            
            <div x-show="modalOpen" x-transition.opacity @click="modalOpen = false" class="absolute inset-0 bg-[#060B15]/80 backdrop-blur-sm cursor-pointer"></div>
            
            <div x-show="modalOpen" 
                 x-transition:enter="transition ease-out duration-300"
                 x-transition:enter-start="opacity-0 scale-95 translate-y-4"
                 x-transition:enter-end="opacity-100 scale-100 translate-y-0"
                 class="bg-white w-full max-w-2xl rounded-3xl shadow-2xl relative z-10 flex flex-col overflow-hidden">
                
                
                <form :action="formAction" method="POST">
                    <?php echo csrf_field(); ?>
                    
                    <template x-if="formMethod === 'PUT'">
                        <input type="hidden" name="_method" value="PUT">
                    </template>
                    
                    
                    <input type="hidden" name="kategori" x-model="formData.kategori">

                    <div class="px-8 py-6 flex justify-between items-center border-b border-gray-50">
                        <h2 class="text-xl font-black text-brand-dark" x-text="modalTitle"></h2>
                        <button type="button" @click="modalOpen = false" class="text-gray-400 hover:text-brand-dark transition-colors">
                            <i data-feather="x" class="w-5 h-5"></i>
                        </button>
                    </div>

                    <div class="p-8 space-y-6">
                        
                        
                        <div>
                            <label class="block text-[11px] font-black text-gray-500 uppercase tracking-widest mb-3">Tampilkan Di:</label>
                            <div class="flex flex-wrap gap-2">
                                <template x-for="kategori in ['Dashboard Utama', 'Dashboard User']">
                                    <button type="button" 
                                            @click="formData.kategori = kategori"
                                            :class="formData.kategori === kategori ? 'bg-brand-dark text-white shadow-md' : 'bg-[#F1F5F9] text-gray-500 hover:bg-gray-200'"
                                            class="px-5 py-2.5 rounded-xl text-[12px] font-bold transition-all flex items-center gap-2">
                                        <i :data-feather="kategori === 'Dashboard Utama' ? 'globe' : 'lock'" class="w-3.5 h-3.5"></i>
                                        <span x-text="kategori"></span>
                                    </button>
                                </template>
                            </div>
                        </div>

                        <div>
                            <label class="block text-[11px] font-black text-gray-500 uppercase tracking-widest mb-2">Pertanyaan</label>
                            <input type="text" name="pertanyaan" x-model="formData.pertanyaan" placeholder="Contoh: Kapan pengumuman kelulusan keluar?" required
                                   class="w-full px-5 py-4 bg-[#F8FAFC] border-none rounded-xl text-[14px] font-semibold text-brand-dark placeholder-gray-400 outline-none focus:ring-2 focus:ring-brand-blue/20 transition-all">
                        </div>

                        <div>
                            <label class="block text-[11px] font-black text-gray-500 uppercase tracking-widest mb-2">Jawaban</label>
                            <div class="bg-[#F8FAFC] rounded-xl overflow-hidden border border-gray-50 focus-within:ring-2 focus-within:ring-brand-blue/20 transition-all">
                                <textarea name="jawaban" x-model="formData.jawaban" rows="5" required placeholder="Berikan jawaban yang jelas dan detail..." 
                                          class="w-full px-5 py-4 bg-transparent border-none text-[14px] font-medium text-brand-dark placeholder-gray-400 outline-none resize-none"></textarea>
                            </div>
                        </div>

                    </div>

                    <div class="px-8 py-6 bg-white border-t border-gray-50 flex items-center justify-end gap-4">
                        <button type="button" @click="modalOpen = false" class="px-6 py-2.5 text-gray-500 font-bold text-[13px] hover:text-brand-dark transition-colors">
                            Batal
                        </button>
                        <button type="submit" class="px-8 py-3 bg-brand-dark text-white rounded-xl font-bold text-[13px] hover:bg-brand-blue shadow-lg shadow-brand-dark/20 transition-all">
                            Simpan FAQ
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </template>
</div>

<script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('faqManager', () => ({
            activeFaq: null,
            modalOpen: false,
            modalTitle: 'Tambah FAQ',
            formAction: '/admin/faq', // Pastikan Route web.php Anda merespon URL ini
            formMethod: 'POST',
            formData: {
                pertanyaan: '',
                jawaban: '',
                kategori: 'Dashboard Utama'
            },
            
            bukaModalTambah() {
                this.modalTitle = 'Tambah FAQ Baru';
                this.formAction = '/admin/faq'; // URL untuk proses Simpan (Store)
                this.formMethod = 'POST';
                this.formData = { pertanyaan: '', jawaban: '', kategori: 'Dashboard Utama' };
                this.modalOpen = true;
                this.refreshIcons();
            },
            
            bukaModalEdit(id, pertanyaan, jawaban, kategori) {
                this.modalTitle = 'Edit Data FAQ';
                this.formAction = '/admin/faq/' + id; // URL untuk proses Update
                this.formMethod = 'PUT';
                this.formData = { pertanyaan, jawaban, kategori };
                this.modalOpen = true;
                this.refreshIcons();
            },

            refreshIcons() {
                setTimeout(() => {
                    if(window.feather) feather.replace();
                }, 10);
            }
        }));
    });

    document.addEventListener('DOMContentLoaded', () => {
        if(window.feather) feather.replace();
    });
</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\Database\spmb-adzkia\resources\views/admin/faq.blade.php ENDPATH**/ ?>