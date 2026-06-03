<?php $__env->startSection('admin-content'); ?>
<div x-data="pengumumanApp()">
    <div class="mb-8">
        <h1 class="text-3xl font-extrabold text-[#0B1C39] tracking-tight mb-2">Penetapan Kelulusan</h1>
        <p class="text-brand-gray text-[14px] font-medium">Tetapkan status Lulus / Tidak Lulus untuk calon mahasiswa melalui form popup.</p>
    </div>

    <?php if(session('success')): ?>
        <div class="bg-green-50 border-l-4 border-green-500 p-4 mb-6 rounded-xl flex items-start gap-3">
            <i data-feather="check-circle" class="w-5 h-5 text-green-600"></i>
            <p class="text-sm font-bold text-green-700"><?php echo e(session('success')); ?></p>
        </div>
    <?php endif; ?>

    <div class="bg-white rounded-[2rem] shadow-sm border border-gray-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left whitespace-nowrap">
                <thead class="bg-gray-50/50 text-[11px] font-black text-brand-dark uppercase tracking-widest border-b border-gray-100">
                    <tr>
                        <th class="px-6 py-5">Nama & No. Daftar</th>
                        <th class="px-4 py-5">Pilihan Jurusan</th>
                        <th class="px-4 py-5 text-center">Status Saat Ini</th>
                        <th class="px-6 py-5 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50 text-[13px]">
                    <?php $__empty_1 = true; $__currentLoopData = $pendaftar; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $data): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <tr class="hover:bg-gray-50/50 transition-colors">
                        <td class="px-6 py-4">
                            <div class="flex flex-col">
                                <span class="font-bold text-brand-dark text-[14px]"><?php echo e($data->nama_lengkap); ?></span>
                                <span class="text-gray-400 text-[11px] font-extrabold tracking-wider"><?php echo e($data->no_pendaftaran); ?></span>
                            </div>
                        </td>
                        <td class="px-4 py-4">
                            <div class="flex flex-col gap-1">
                                <span class="text-[11px] font-bold text-gray-700"><span class="text-gray-400">1.</span> <?php echo e($data->pilihan_jurusan_1 ?? '-'); ?></span>
                                <span class="text-[11px] font-bold text-gray-700"><span class="text-gray-400">2.</span> <?php echo e($data->pilihan_jurusan_2 ?? '-'); ?></span>
                            </div>
                        </td>
                        <td class="px-4 py-4 text-center">
                            <?php if(empty($data->status_kelulusan)): ?>
                                <span class="px-3 py-1 bg-gray-100 text-gray-500 rounded-full text-[10px] font-black uppercase">Belum Ditetapkan</span>
                            <?php elseif($data->status_kelulusan == 'Tidak Lulus'): ?>
                                <span class="px-3 py-1 bg-red-50 text-red-600 rounded-full text-[10px] font-black uppercase"><?php echo e($data->status_kelulusan); ?></span>
                            <?php else: ?>
                                <span class="px-3 py-1 bg-green-50 text-green-600 rounded-full text-[10px] font-black uppercase"><?php echo e($data->status_kelulusan); ?></span>
                            <?php endif; ?>
                        </td>
                        <td class="px-6 py-4 text-center">
                            <button @click="bukaModal('<?php echo e($data->id); ?>', '<?php echo e(addslashes($data->nama_lengkap)); ?>', '<?php echo e($data->no_pendaftaran); ?>', '<?php echo e($data->pilihan_jurusan_1); ?>', '<?php echo e($data->pilihan_jurusan_2); ?>', '<?php echo e($data->status_kelulusan); ?>')" 
                                    class="px-5 py-2 bg-brand-dark text-white rounded-lg font-bold text-[11px] hover:bg-brand-blue transition-colors shadow-sm">
                                Tetapkan Kelulusan
                            </button>
                        </td>
                    </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr>
                        <td colspan="4" class="px-6 py-10 text-center text-gray-500 font-bold">Tidak ada data pendaftar.</td>
                    </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    
    <div x-show="modalOpen" class="fixed inset-0 z-50 flex items-center justify-center px-4" style="display: none;" x-cloak>
        <div x-show="modalOpen" x-transition.opacity @click="modalOpen = false" class="absolute inset-0 bg-brand-dark/60 backdrop-blur-sm cursor-pointer"></div>
        
        <div x-show="modalOpen" class="bg-white w-full max-w-lg rounded-[2rem] shadow-2xl relative z-10 overflow-hidden flex flex-col">
            <div class="p-6 border-b border-gray-100 flex justify-between items-center bg-gray-50/50">
                <h2 class="text-xl font-extrabold text-brand-dark tracking-tight">Penetapan Kelulusan</h2>
                <button @click="modalOpen = false" class="p-2 bg-white border border-gray-200 hover:bg-gray-100 rounded-full transition-colors">
                    <i data-feather="x" class="w-4 h-4 text-gray-500"></i>
                </button>
            </div>

            <div class="p-8">
                <div class="mb-6 text-center">
                    <p class="text-[12px] font-bold text-gray-400 uppercase tracking-widest mb-1" x-text="mhs.noReg"></p>
                    <h3 class="text-2xl font-black text-brand-dark" x-text="mhs.nama"></h3>
                </div>

                <form :action="'/admin/pengumuman/tetapkan/' + mhs.id" method="POST">
                    <?php echo csrf_field(); ?>
                    <div class="space-y-4 mb-8">
                        <label class="block border-2 rounded-xl p-4 cursor-pointer transition-all" :class="pilihan == 'Lulus Pilihan 1' ? 'border-green-500 bg-green-50' : 'border-gray-100 hover:border-brand-blue'">
                            <input type="radio" name="status_kelulusan" value="Lulus Pilihan 1" x-model="pilihan" class="hidden">
                            <div class="flex justify-between items-center">
                                <div>
                                    <p class="text-[14px] font-extrabold text-brand-dark">LULUS Pilihan 1</p>
                                    <p class="text-[12px] font-bold text-gray-500 mt-1" x-text="mhs.prodi1"></p>
                                </div>
                                <i data-feather="check-circle" class="text-green-500 w-6 h-6" x-show="pilihan == 'Lulus Pilihan 1'"></i>
                            </div>
                        </label>

                        <label class="block border-2 rounded-xl p-4 cursor-pointer transition-all" :class="pilihan == 'Lulus Pilihan 2' ? 'border-green-500 bg-green-50' : 'border-gray-100 hover:border-brand-blue'">
                            <input type="radio" name="status_kelulusan" value="Lulus Pilihan 2" x-model="pilihan" class="hidden">
                            <div class="flex justify-between items-center">
                                <div>
                                    <p class="text-[14px] font-extrabold text-brand-dark">LULUS Pilihan 2</p>
                                    <p class="text-[12px] font-bold text-gray-500 mt-1" x-text="mhs.prodi2"></p>
                                </div>
                                <i data-feather="check-circle" class="text-green-500 w-6 h-6" x-show="pilihan == 'Lulus Pilihan 2'"></i>
                            </div>
                        </label>

                        <label class="block border-2 rounded-xl p-4 cursor-pointer transition-all" :class="pilihan == 'Tidak Lulus' ? 'border-red-500 bg-red-50' : 'border-gray-100 hover:border-red-300'">
                            <input type="radio" name="status_kelulusan" value="Tidak Lulus" x-model="pilihan" class="hidden">
                            <div class="flex justify-between items-center">
                                <div>
                                    <p class="text-[14px] font-extrabold text-red-600">TIDAK LULUS</p>
                                    <p class="text-[12px] font-bold text-red-400 mt-1">Maaf, pendaftar tidak lolos seleksi.</p>
                                </div>
                                <i data-feather="x-circle" class="text-red-500 w-6 h-6" x-show="pilihan == 'Tidak Lulus'"></i>
                            </div>
                        </label>
                    </div>

                    <div class="flex gap-3">
                        <button type="button" @click="modalOpen = false" class="w-1/3 py-3.5 border border-gray-200 text-gray-600 bg-white hover:bg-gray-50 rounded-xl font-bold text-[13px] transition-colors">Batal</button>
                        <button type="submit" :disabled="!pilihan" :class="pilihan ? 'bg-brand-dark hover:bg-brand-blue text-white shadow-lg' : 'bg-gray-200 text-gray-400 cursor-not-allowed'" class="w-2/3 py-3.5 rounded-xl font-bold text-[13px] transition-colors flex justify-center items-center gap-2">
                            <i data-feather="save" class="w-4 h-4"></i> Simpan Keputusan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    function pengumumanApp() {
        return {
            modalOpen: false,
            pilihan: '',
            mhs: { id: '', nama: '', noReg: '', prodi1: '', prodi2: '' },
            
            bukaModal(id, nama, noReg, prodi1, prodi2, statusAwal) {
                this.mhs = { id, nama, noReg, prodi1, prodi2 };
                this.pilihan = statusAwal || '';
                this.modalOpen = true;
                setTimeout(() => { if(window.feather) feather.replace(); }, 50);
            }
        }
    }
</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\Database\spmb-adzkia\resources\views/admin/pengumuman.blade.php ENDPATH**/ ?>