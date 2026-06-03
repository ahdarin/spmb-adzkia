<?php $__env->startSection('admin-content'); ?>
<div x-data="manajemenDivisi()">

    <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-8 gap-4">
        <div>
            <h1 class="text-3xl font-extrabold text-brand-dark tracking-tight mb-2">Manajemen Divisi & Admin</h1>
            <p class="text-brand-gray text-[14px] font-medium">
                Atur akun dan hak akses staf admin SPMB Universitas Adzkia.
            </p>
        </div>
        <button @click="bukaModalTambah()" class="px-6 py-3 bg-brand-dark text-white rounded-xl font-bold text-[13px] hover:bg-brand-blue transition-colors shadow-lg flex items-center gap-2">
            <i data-feather="plus-circle" class="w-4 h-4"></i> Tambah Staf Baru
        </button>
    </div>

    <?php if(session('success')): ?>
        <div class="bg-green-50 border-l-4 border-green-500 p-4 mb-6 rounded-r-xl">
            <p class="text-sm font-bold text-green-700"><?php echo e(session('success')); ?></p>
        </div>
    <?php endif; ?>

    <div class="bg-white rounded-[2rem] shadow-sm border border-gray-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left whitespace-nowrap">
                <thead class="bg-gray-50/50 text-[11px] font-black text-brand-dark uppercase tracking-widest border-b border-gray-100">
                    <tr>
                        <th class="px-6 py-5">Informasi Staf</th>
                        <th class="px-4 py-5">Divisi / Tugas</th>
                        <th class="px-4 py-5">Status Akun</th>
                        <th class="px-6 py-5 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50 text-[13px]">
                    <?php $__empty_1 = true; $__currentLoopData = $admins; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $admin): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <tr class="hover:bg-gray-50/50 transition-colors group">
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-4">
                                <img src="https://ui-avatars.com/api/?name=<?php echo e(urlencode($admin->name)); ?>&background=EFF6FF&color=2563EB" class="w-10 h-10 rounded-full border border-gray-200">
                                <div class="flex flex-col">
                                    <span class="font-bold text-brand-dark text-[14px]"><?php echo e($admin->name); ?></span>
                                    <span class="text-gray-400 text-[11px] font-extrabold tracking-wider"><?php echo e($admin->email); ?></span>
                                </div>
                            </div>
                        </td>
                        <td class="px-4 py-4">
                            <span class="px-4 py-1.5 bg-brand-blue-light text-brand-blue rounded-full text-[11px] font-extrabold tracking-wide">
                                <?php echo e($admin->divisi ?? 'Belum Ditentukan'); ?>

                            </span>
                        </td>
                        <td class="px-4 py-4">
                            <span class="inline-flex items-center gap-1.5 text-green-500 text-[11px] font-black uppercase tracking-wider">
                                <div class="w-2 h-2 rounded-full bg-green-500"></div> AKTIF
                            </span>
                        </td>
                        <td class="px-6 py-4 text-right">
                            <div class="flex items-center justify-end gap-2 opacity-0 group-hover:opacity-100 transition-opacity">
                                <button @click="bukaModalEdit('<?php echo e($admin->id); ?>', '<?php echo e(addslashes($admin->name)); ?>', '<?php echo e(addslashes($admin->email)); ?>', '<?php echo e($admin->divisi); ?>')" 
                                    class="p-2 text-brand-blue bg-brand-blue-light hover:bg-blue-100 rounded-lg transition-colors">
                                    <i data-feather="edit-2" class="w-4 h-4"></i>
                                </button>
                                <form action="<?php echo e(url('/admin/tugas/'.$admin->id)); ?>" method="POST" class="inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus staf ini?');">
                                    <?php echo csrf_field(); ?>
                                    <?php echo method_field('DELETE'); ?>
                                    <button type="submit" class="p-2 text-red-600 bg-red-50 hover:bg-red-100 rounded-lg transition-colors">
                                        <i data-feather="trash-2" class="w-4 h-4"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr>
                        <td colspan="4" class="px-6 py-10 text-center text-gray-500 font-bold">Belum ada akun staf yang ditambahkan.</td>
                    </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    
    <div x-show="modalOpen" class="fixed inset-0 z-50 flex items-center justify-center px-4" style="display: none;">
        <div x-show="modalOpen" x-transition.opacity @click="modalOpen = false" class="absolute inset-0 bg-brand-dark/60 backdrop-blur-sm cursor-pointer"></div>
        
        <div x-show="modalOpen" 
             x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 scale-95 translate-y-4" x-transition:enter-end="opacity-100 scale-100 translate-y-0" 
             class="bg-white w-full max-w-lg rounded-[2rem] shadow-2xl relative z-10 overflow-hidden flex flex-col">
            
            <div class="p-6 border-b border-gray-100 flex justify-between items-center bg-gray-50/50">
                <h2 class="text-xl font-extrabold text-brand-dark tracking-tight" x-text="isEdit ? 'Edit Data Staf' : 'Tambah Staf Baru'"></h2>
                <button @click="modalOpen = false" class="p-2 bg-white border border-gray-200 hover:bg-gray-100 rounded-full transition-colors">
                    <i data-feather="x" class="w-4 h-4 text-gray-500"></i>
                </button>
            </div>

            <form :action="isEdit ? `/admin/tugas/${formData.id}` : '/admin/tugas'" method="POST" class="p-8 space-y-5">
                <?php echo csrf_field(); ?>
                <template x-if="isEdit">
                    <input type="hidden" name="_method" value="PUT">
                </template>

                <div>
                    <label class="block text-[11px] font-extrabold text-gray-500 uppercase tracking-widest mb-2">Nama Lengkap</label>
                    <input type="text" name="name" x-model="formData.name" required class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-[13px] font-bold focus:border-brand-blue focus:bg-white outline-none transition-all">
                </div>

                <div>
                    <label class="block text-[11px] font-extrabold text-gray-500 uppercase tracking-widest mb-2">Email / Username</label>
                    <input type="email" name="email" x-model="formData.email" required class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-[13px] font-bold focus:border-brand-blue focus:bg-white outline-none transition-all">
                </div>

                <div>
                    <label class="block text-[11px] font-extrabold text-gray-500 uppercase tracking-widest mb-2">
                        Password <span x-show="isEdit" class="text-gray-400 lowercase font-medium tracking-normal">(Kosongkan jika tidak ingin diubah)</span>
                    </label>
                    <input type="password" name="password" :required="!isEdit" class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-[13px] font-bold focus:border-brand-blue focus:bg-white outline-none transition-all">
                </div>

                <div>
                    <label class="block text-[11px] font-extrabold text-gray-500 uppercase tracking-widest mb-2">Divisi Tugas</label>
                    <div class="relative">
                        <select name="divisi" x-model="formData.divisi" required class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-[13px] font-bold focus:border-brand-blue focus:bg-white outline-none transition-all appearance-none cursor-pointer">
                            <option value="" disabled>Pilih Divisi</option>
                            <option value="Keuangan">Keuangan (Validasi Pembayaran)</option>
                            <option value="Verifikator Berkas">Verifikator Berkas (Daftar Ulang)</option>
                            <option value="Humas & Informasi">Humas & Informasi (Berita/Pengumuman)</option>
                        </select>
                        <i data-feather="chevron-down" class="w-4 h-4 text-gray-400 absolute right-4 top-1/2 -translate-y-1/2 pointer-events-none"></i>
                    </div>
                </div>

                <div class="pt-4 border-t border-gray-100 flex gap-3 mt-4">
                    <button type="button" @click="modalOpen = false" class="flex-1 py-3 border border-gray-200 text-gray-600 bg-white hover:bg-gray-50 rounded-xl font-bold text-[13px] transition-colors">Batal</button>
                    <button type="submit" class="flex-1 py-3 bg-brand-dark text-white hover:bg-brand-blue rounded-xl font-bold text-[13px] transition-colors shadow-lg" x-text="isEdit ? 'Simpan Perubahan' : 'Buat Akun Staf'"></button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    function manajemenDivisi() {
        return {
            modalOpen: false,
            isEdit: false,
            formData: { id: '', name: '', email: '', divisi: '' },

            bukaModalTambah() {
                this.isEdit = false;
                this.formData = { id: '', name: '', email: '', divisi: '' };
                this.modalOpen = true;
                setTimeout(() => feather.replace(), 50);
            },

            bukaModalEdit(id, name, email, divisi) {
                this.isEdit = true;
                this.formData = { id, name, email, divisi };
                this.modalOpen = true;
                setTimeout(() => feather.replace(), 50);
            }
        }
    }
</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\Database\spmb-adzkia\resources\views/admin/tugas.blade.php ENDPATH**/ ?>