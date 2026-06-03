<?php $__env->startSection('admin-content'); ?>
<div x-data="prodiManager()">
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-10 gap-4">
        <div>
            <h1 class="text-3xl font-extrabold text-brand-dark tracking-tight mb-2">Manajemen Program Studi</h1>
            <p class="text-brand-gray text-[14px] font-medium">Kelola daftar program studi, akreditasi, dan daya tampung mahasiswa.</p>
        </div>
        <button @click="openAddModal()" class="flex items-center gap-2 px-6 py-3 bg-brand-dark text-white rounded-2xl font-bold text-[13px] hover:bg-brand-blue transition-all shadow-lg shadow-brand-dark/10">
            <i data-feather="plus" class="w-4 h-4"></i> Tambah Prodi Baru
        </button>
    </div>

    <div class="bg-white rounded-[2.5rem] shadow-sm border border-gray-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left whitespace-nowrap">
                <thead class="bg-gray-50/50 text-[11px] font-black text-brand-gray uppercase tracking-widest border-b">
                    <tr>
                        <th class="px-8 py-6">Program Studi</th>
                        <th class="px-4 py-6">Jenjang</th>
                        <th class="px-4 py-6">Akreditasi</th>
                        <th class="px-4 py-6 text-center">Daya Tampung</th>
                        <th class="px-4 py-6">Biaya (Smt)</th>
                        <th class="px-8 py-6 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50 text-[13px]">
                    <?php $__empty_1 = true; $__currentLoopData = $data; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <tr class="hover:bg-gray-50/50 transition-colors group">
                        <td class="px-8 py-5">
                            <div class="flex items-center gap-4">
                                <div class="w-10 h-10 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center">
                                    <i data-feather="book" class="w-5 h-5"></i>
                                </div>
                                <div class="flex flex-col">
                                    <span class="font-bold text-brand-dark text-[15px]"><?php echo e($item->nama); ?></span>
                                    <span class="text-[11px] font-medium text-gray-400 mt-0.5 truncate max-w-[200px]" title="<?php echo e($item->deskripsi); ?>"><?php echo e($item->deskripsi ?? 'Belum ada deskripsi.'); ?></span>
                                </div>
                            </div>
                        </td>
                        <td class="px-4 py-5 font-bold text-gray-500"><?php echo e($item->jenjang); ?></td>
                        <td class="px-4 py-5">
                            <span class="px-3 py-1 <?php echo e($item->akreditasi == 'Unggul' ? 'bg-green-50 text-green-600' : 'bg-blue-50 text-blue-600'); ?> rounded-lg text-[10px] font-black uppercase">
                                <?php echo e($item->akreditasi); ?>

                            </span>
                        </td>
                        <td class="px-4 py-5 text-center font-black text-brand-dark"><?php echo e($item->kuota); ?></td>
                        <td class="px-4 py-5 font-bold text-gray-600">Rp <?php echo e(number_format($item->biaya, 0, ',', '.')); ?></td>
                        <td class="px-8 py-5 text-right">
                            <div class="flex justify-end gap-2 opacity-0 group-hover:opacity-100 transition-opacity">
                                <button @click="openEditModal(<?php echo e(json_encode($item)); ?>)" class="p-2 text-brand-gray hover:text-brand-blue">
                                    <i data-feather="edit-3" class="w-4 h-4"></i>
                                </button>
                                <form action="<?php echo e(route('admin.prodi.destroy', $item->id)); ?>" method="POST" class="inline" onsubmit="return confirm('Hapus prodi ini?')">
                                    <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
                                    <button type="submit" class="p-2 text-brand-gray hover:text-red-500">
                                        <i data-feather="trash-2" class="w-4 h-4"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr>
                        <td colspan="6" class="px-8 py-10 text-center text-gray-400 font-bold">Belum ada data prodi.</td>
                    </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    <template x-teleport="body">
        <div x-show="modalOpen" class="fixed inset-0 z-[999] flex items-center justify-center p-4" x-cloak>
            <div x-show="modalOpen" x-transition.opacity @click="modalOpen = false" class="absolute inset-0 bg-brand-dark/70 backdrop-blur-sm"></div>
            
            <div x-show="modalOpen" 
                 x-transition:enter="transition ease-out duration-300"
                 x-transition:enter-start="opacity-0 scale-95 translate-y-4"
                 x-transition:enter-end="opacity-100 scale-100 translate-y-0"
                 class="bg-white w-full max-w-2xl rounded-[2.5rem] shadow-2xl relative z-10 overflow-hidden">
                
                <form :action="isEdit ? `<?php echo e(url('admin/prodi')); ?>/${form.id}` : '<?php echo e(route('admin.prodi.store')); ?>'" method="POST" class="p-10">
                    <?php echo csrf_field(); ?>
                    <template x-if="isEdit">
                        <input type="hidden" name="_method" value="PUT">
                    </template>

                    <div class="flex justify-between items-center mb-8">
                        <h2 class="text-2xl font-black text-brand-dark tracking-tight" x-text="isEdit ? 'Edit Program Studi' : 'Tambah Program Studi'"></h2>
                        <button type="button" @click="modalOpen = false" class="text-gray-400 hover:text-brand-dark"><i data-feather="x"></i></button>
                    </div>

                    <div class="grid grid-cols-2 gap-6 mb-8">
                        <div class="col-span-2">
                            <label class="block text-[11px] font-black text-gray-400 uppercase tracking-widest mb-2">Nama Program Studi</label>
                            <input type="text" name="nama" x-model="form.nama" placeholder="Misal: Teknik Elektro" required class="w-full px-5 py-4 bg-gray-50 border border-gray-100 rounded-2xl outline-none focus:ring-2 focus:ring-brand-blue/20 transition-all font-bold text-sm">
                        </div>
                        
                        <div class="col-span-2">
                            <label class="block text-[11px] font-black text-gray-400 uppercase tracking-widest mb-2">Deskripsi Singkat Prodi</label>
                            <textarea name="deskripsi" x-model="form.deskripsi" rows="3" placeholder="Tuliskan penjelasan singkat mengenai prodi ini..." class="w-full px-5 py-4 bg-gray-50 border border-gray-100 rounded-2xl outline-none focus:ring-2 focus:ring-brand-blue/20 transition-all font-bold text-sm resize-none"></textarea>
                        </div>

                        <div>
                            <label class="block text-[11px] font-black text-gray-400 uppercase tracking-widest mb-2">Jenjang</label>
                            <select name="jenjang" x-model="form.jenjang" class="w-full px-5 py-4 bg-gray-50 border border-gray-100 rounded-2xl outline-none font-bold text-sm appearance-none">
                                <option value="S1">Sarjana (S1)</option>
                                <option value="D3">Diploma (D3)</option>
                                <option value="S2">Magister (S2)</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-[11px] font-black text-gray-400 uppercase tracking-widest mb-2">Akreditasi</label>
                            <select name="akreditasi" x-model="form.akreditasi" class="w-full px-5 py-4 bg-gray-50 border border-gray-100 rounded-2xl outline-none font-bold text-sm appearance-none">
                                <option value="Unggul">Unggul</option>
                                <option value="A">A</option>
                                <option value="B">B</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-[11px] font-black text-gray-400 uppercase tracking-widest mb-2">Daya Tampung</label>
                            <input type="number" name="kuota" x-model="form.kuota" placeholder="0" required class="w-full px-5 py-4 bg-gray-50 border border-gray-100 rounded-2xl outline-none font-bold text-sm">
                        </div>
                        <div>
                            <label class="block text-[11px] font-black text-gray-400 uppercase tracking-widest mb-2">Biaya per Semester</label>
                            <input type="number" name="biaya" x-model="form.biaya" placeholder="0" required class="w-full px-5 py-4 bg-gray-50 border border-gray-100 rounded-2xl outline-none font-bold text-sm">
                        </div>
                    </div>

                    <div class="flex gap-4">
                        <button type="button" @click="modalOpen = false" class="flex-1 py-4 bg-gray-100 text-brand-gray rounded-2xl font-black text-[12px] uppercase tracking-widest hover:bg-gray-200 transition-all">Batal</button>
                        <button type="submit" class="flex-1 py-4 bg-brand-dark text-white rounded-2xl font-black text-[12px] uppercase tracking-widest hover:bg-brand-blue shadow-xl shadow-brand-dark/10 transition-all">Simpan Prodi</button>
                    </div>
                </form>
            </div>
        </div>
    </template>
</div>

<script>
document.addEventListener('alpine:init', () => {
    Alpine.data('prodiManager', () => ({
        modalOpen: false,
        isEdit: false,
        form: {
            id: '',
            nama: '',
            jenjang: 'S1',
            akreditasi: 'Unggul',
            kuota: '',
            biaya: '',
            deskripsi: '' // Tambahan state deskripsi
        },

        openAddModal() {
            this.isEdit = false;
            this.form = { id: '', nama: '', jenjang: 'S1', akreditasi: 'Unggul', kuota: '', biaya: '', deskripsi: '' };
            this.modalOpen = true;
        },

        openEditModal(data) {
            this.isEdit = true;
            this.form = { ...data };
            // Jika data dari database deskripsinya null, kita jadikan string kosong agar rapi di form
            if (!this.form.deskripsi) {
                this.form.deskripsi = '';
            }
            this.modalOpen = true;
        }
    }));
});
</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\Database\spmb-adzkia\resources\views/admin/prodi.blade.php ENDPATH**/ ?>