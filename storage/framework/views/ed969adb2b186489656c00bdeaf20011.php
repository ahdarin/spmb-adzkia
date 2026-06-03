<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Pendaftar - SPMB Adzkia</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/feather-icons/dist/feather.min.js"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: { sans: ['Manrope', 'sans-serif'] },
                    colors: {
                        'adzkia-red': '#d9241c',
                        'adzkia-blue': '#2c7ebd',
                        'adzkia-dark': '#1e293b',
                        'adzkia-muted': '#64748b',
                        'adzkia-badge-bg': '#eff6ff',
                        'adzkia-bg': '#FAFBFC',
                    }
                }
            }
        }
    </script>
</head>
<body class="bg-adzkia-bg antialiased text-adzkia-dark min-h-screen flex flex-col">

    <nav class="w-full bg-white py-5 px-6 md:px-16 border-b border-gray-100 flex items-center justify-between sticky top-0 z-50 shadow-sm">
        <div class="flex items-center gap-3">
            <img src="<?php echo e(asset('images/logo-adzkia.png')); ?>" alt="Logo Adzkia" class="h-10 w-auto">
            <div class="flex flex-col">
                <span class="text-md font-black text-adzkia-blue leading-none">PORTAL PENDAFTAR</span>
                <span class="text-xs font-bold text-adzkia-red">Universitas Adzkia</span>
            </div>
        </div>
        <div class="flex items-center gap-4">
            <div class="text-right hidden sm:block">
                <p class="text-xs font-black text-adzkia-dark leading-none"><?php echo e(session('nama_pendaftar') ?? $pendaftar->nama_lengkap); ?></p>
                <p class="text-[10px] font-bold text-gray-400 mt-1 uppercase tracking-widest">No: <?php echo e($pendaftar->no_pendaftaran ?? '-'); ?></p>
            </div>
            <form action="<?php echo e(route('logout')); ?>" method="POST" class="inline">
                <?php echo csrf_field(); ?>
                <button type="submit" class="p-2.5 bg-gray-50 text-gray-400 hover:text-adzkia-red rounded-xl hover:bg-red-50 transition-all">
                    <i data-feather="log-out" class="w-4 h-4"></i>
                </button>
            </form>
        </div>
    </nav>

    <main class="flex-1 max-w-6xl mx-auto w-full px-6 py-12 grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
        
        <div class="lg:col-span-8 space-y-8">
            

            <?php if(in_array($pendaftar->status_kelulusan, ['Lulus Pilihan 1', 'Lulus Pilihan 2'])): ?>
                
                <?php
                    // Menentukan jurusan mana yang lulus untuk ditampilkan
                    $jurusanDiterima = ($pendaftar->status_kelulusan == 'Lulus Pilihan 1') 
                                        ? $pendaftar->pilihan_jurusan_1 
                                        : $pendaftar->pilihan_jurusan_2;
                ?>

                <div class="bg-gradient-to-r from-green-500 to-emerald-600 rounded-3xl p-6 md:p-8 flex flex-col md:flex-row gap-6 items-center md:items-start shadow-xl shadow-green-500/20 text-white text-center md:text-left relative overflow-hidden">
                    <div class="absolute -right-10 -top-10 opacity-10"><i data-feather="award" class="w-48 h-48"></i></div>
                    <div class="w-16 h-16 rounded-2xl bg-white text-green-600 flex items-center justify-center shrink-0 shadow-lg relative z-10">
                        <i data-feather="check-circle" class="w-8 h-8"></i>
                    </div>
                    <div class="flex-1 relative z-10">
                        <span class="inline-block px-3 py-1 bg-white/20 rounded-full text-[10px] font-black uppercase tracking-widest mb-3 backdrop-blur-sm border border-white/30">Pengumuman Hasil Akhir</span>
                        <h3 class="text-2xl font-black mb-2">Selamat, Anda Dinyatakan LULUS!</h3>
                        <p class="text-[13px] font-medium text-green-50 leading-relaxed mb-5">
                            Selamat datang di kampus Universitas Adzkia! Berdasarkan hasil seleksi, Anda dinyatakan lulus dan diterima di program studi <strong class="text-white bg-green-700/50 px-2 py-0.5 rounded"><?php echo e($jurusanDiterima); ?></strong> (<?php echo e(str_replace('Lulus ', '', $pendaftar->status_kelulusan)); ?>).
                        </p>
                        <a href="<?php echo e(route('cetak.loa')); ?>" target="_blank" class="inline-flex items-center justify-center gap-2 px-6 py-3.5 bg-white text-green-600 hover:bg-gray-50 font-black text-[13px] rounded-xl transition-all shadow-md w-full md:w-auto active:scale-[0.98]">
                            <i data-feather="download" class="w-4 h-4"></i> Unduh Surat Kelulusan
                        </a>
                    </div>
                </div>

            <?php elseif($pendaftar->status_kelulusan == 'Tidak Lulus'): ?>
                <div class="bg-adzkia-red rounded-3xl p-6 md:p-8 flex flex-col md:flex-row gap-6 items-center md:items-start shadow-xl shadow-red-600/20 text-white text-center md:text-left relative overflow-hidden">
                    <div class="w-16 h-16 rounded-2xl bg-white/10 text-white flex items-center justify-center shrink-0 shadow-lg relative z-10 border border-white/20 backdrop-blur-sm">
                        <i data-feather="x-circle" class="w-8 h-8"></i>
                    </div>
                    <div class="flex-1 relative z-10">
                        <span class="inline-block px-3 py-1 bg-white/20 rounded-full text-[10px] font-black uppercase tracking-widest mb-3 backdrop-blur-sm border border-white/30">Pengumuman Hasil Akhir</span>
                        <h3 class="text-xl font-black mb-2">Mohon Maaf, Anda Tidak Lulus</h3>
                        <p class="text-[13px] font-medium text-red-100 leading-relaxed">
                            Terima kasih atas partisipasi Anda dalam seleksi PMB Universitas Adzkia. Jangan patah semangat, Anda masih bisa mencoba mendaftar kembali pada gelombang atau jalur pendaftaran berikutnya.
                        </p>
                    </div>
                </div>

            <?php elseif($pendaftar->status_pendaftaran == 'Selesai'): ?>
                
                <div class="bg-gradient-to-r from-blue-600 to-indigo-700 rounded-3xl p-6 md:p-8 flex flex-col md:flex-row gap-6 items-center md:items-start shadow-xl shadow-blue-600/20 text-white text-center md:text-left relative overflow-hidden">
                    <div class="w-16 h-16 rounded-2xl bg-white/20 text-white flex items-center justify-center shrink-0 shadow-lg relative z-10 border border-white/20 backdrop-blur-sm">
                        <i data-feather="calendar" class="w-8 h-8"></i>
                    </div>
                    <div class="flex-1 relative z-10">
                        <span class="inline-block px-3 py-1 bg-white/20 rounded-full text-[10px] font-black uppercase tracking-widest mb-3 backdrop-blur-sm border border-white/30">Tahap Akhir</span>
                        <h3 class="text-xl font-black mb-2">Menunggu Pengumuman Kelulusan</h3>
                        <p class="text-[13px] font-medium text-blue-100 leading-relaxed">
                            Berkas dan data Anda telah dinyatakan <strong>Valid</strong>. Saat ini Panitia PMB sedang melakukan kurasi akhir. Harap pantau dashboard ini secara berkala untuk melihat hasil kelulusan Anda.
                        </p>
                    </div>
                </div>

            <?php elseif($pendaftar->status_pendaftaran == 'Revisi'): ?>
                
                <div class="bg-red-50 border border-red-200 rounded-3xl p-6 flex gap-4 items-start shadow-sm shadow-red-600/5">
                    <div class="w-10 h-10 rounded-xl bg-adzkia-red text-white flex items-center justify-center shrink-0 shadow-md shadow-red-500/20">
                        <i data-feather="alert-triangle" class="w-5 h-5"></i>
                    </div>
                    <div class="flex-1">
                        <h3 class="text-[15px] font-black text-red-900">Perbaikan Berkas Diperlukan!</h3>
                        <p class="text-xs font-medium text-red-700/90 mt-1 leading-relaxed">
                            Mohon maaf, terdapat ketidaksesuaian pada data atau dokumen yang Anda unggah. Admin meninggalkan pesan untuk Anda:
                        </p>
                        <div class="mt-3 p-4 bg-white border border-red-100 rounded-xl shadow-sm relative">
                            <div class="absolute -left-1.5 top-5 w-3 h-3 bg-white border-l border-b border-red-100 rotate-45"></div>
                            <p class="text-sm font-bold text-adzkia-dark"><span class="text-adzkia-red font-black">Catatan Admin:</span> <br> "<?php echo e($pendaftar->pesan_revisi ?? 'Mohon periksa kembali kelengkapan dokumen Anda.'); ?>"</p>
                        </div>
                        <a href="<?php echo e(route('pendaftaran.biodata')); ?>" class="inline-flex items-center gap-2 px-6 py-3 bg-adzkia-red hover:bg-red-700 text-white font-black text-sm rounded-xl mt-4 transition-all shadow-lg shadow-red-600/20 active:scale-[0.98]">
                            <i data-feather="edit-2" class="w-4 h-4"></i> Perbaiki Berkas Sekarang &rarr;
                        </a>
                    </div>
                </div>

            <?php elseif($pendaftar->status_pendaftaran == 'menunggu verifikasi'): ?>
                
                <div class="bg-blue-50 border border-blue-200 rounded-3xl p-6 flex gap-4 items-start">
                    <div class="w-10 h-10 rounded-xl bg-adzkia-blue text-white flex items-center justify-center shrink-0 shadow-md shadow-adzkia-blue/20">
                        <i data-feather="search" class="w-5 h-5"></i>
                    </div>
                    <div class="flex-1">
                        <h3 class="text-[15px] font-black text-adzkia-blue">Berkas Sedang Dalam Pengecekan</h3>
                        <p class="text-xs font-medium text-gray-500 mt-1 leading-relaxed">
                            Terima kasih telah melengkapi formulir. Data dan dokumen Anda saat ini sedang diperiksa kelengkapannya oleh tim admin PMB.
                        </p>
                    </div>
                </div>

            <?php elseif($pendaftar->status_pembayaran == 'Terverifikasi'): ?>
                
                <div class="bg-green-50 border border-green-200 rounded-3xl p-6 flex gap-4 items-start shadow-sm shadow-green-600/5">
                    <div class="w-10 h-10 rounded-xl bg-green-500 text-white flex items-center justify-center shrink-0 shadow-md shadow-green-500/20">
                        <i data-feather="check-circle" class="w-5 h-5"></i>
                    </div>
                    <div class="flex-1">
                        <h3 class="text-[15px] font-black text-green-900">Pembayaran Terverifikasi!</h3>
                        <p class="text-xs font-medium text-green-700/90 mt-1 leading-relaxed">
                            Biaya administrasi pendaftaran Anda telah divalidasi. Silakan lanjutkan langkah berikutnya untuk melengkapi berkas biodata Anda.
                        </p>
                        <a href="<?php echo e(route('pendaftaran.biodata')); ?>" class="inline-flex items-center gap-2 px-6 py-3 bg-adzkia-red hover:bg-red-700 text-white font-black text-sm rounded-xl mt-4 transition-all shadow-lg shadow-red-600/20 active:scale-[0.98]">
                            Lanjutkan Pengisian Formulir &rarr;
                        </a>
                    </div>
                </div>

            <?php elseif($pendaftar->status_pembayaran == 'Menunggu Validasi'): ?>
                
                <div class="bg-blue-50 border border-blue-200 rounded-3xl p-6 flex gap-4 items-start">
                    <div class="w-10 h-10 rounded-xl bg-adzkia-blue text-white flex items-center justify-center shrink-0 shadow-md shadow-adzkia-blue/20">
                        <i data-feather="clock" class="w-5 h-5 animate-spin"></i>
                    </div>
                    <div class="flex-1">
                        <h3 class="text-[15px] font-black text-adzkia-blue">Bukti Pembayaran Sedang Diperiksa</h3>
                        <p class="text-xs font-medium text-gray-500 mt-1 leading-relaxed">
                            Bukti pembayaran yang Anda unggah sedang dalam antrean verifikasi oleh admin keuangan. Mohon tunggu maksimal 1x24 jam kerja.
                        </p>
                    </div>
                </div>

            <?php else: ?>
                
                <div class="bg-amber-50 border border-amber-200 rounded-3xl p-6 flex gap-4 items-start">
                    <div class="w-10 h-10 rounded-xl bg-amber-500 text-white flex items-center justify-center shrink-0 shadow-md shadow-amber-500/20">
                        <i data-feather="credit-card" class="w-5 h-5"></i>
                    </div>
                    <div class="flex-1">
                        <h3 class="text-[15px] font-black text-amber-900">Selesaikan Pembayaran Administrasi</h3>
                        <p class="text-xs font-medium text-amber-700/90 mt-1 leading-relaxed">
                            Anda belum menyelesaikan pembayaran registrasi awal. Pilih metode pembayaran Anda untuk mengaktifkan tahap pengisian formulir.
                        </p>
                        <a href="<?php echo e(url('/pembayaran')); ?>" class="inline-flex items-center gap-2 px-5 py-2.5 bg-amber-600 hover:bg-amber-700 text-white font-extrabold text-xs rounded-xl mt-4 transition-all">
                            Pilih Metode Pembayaran &rarr;
                        </a>
                    </div>
                </div>
            <?php endif; ?>

            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-8">
                
                
                <div class="bg-white rounded-[2rem] p-6 border border-gray-100 shadow-sm flex flex-col">
                    <div class="flex items-center justify-between mb-5">
                        <h3 class="text-md font-black text-adzkia-dark">Berita Terkini</h3>
                    </div>
                    
                    <div class="flex-1 flex flex-col gap-4">
                        <?php if(isset($berita) && count($berita) > 0): ?>
                            <?php $__currentLoopData = $berita; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $b): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <a href="#" class="flex gap-4 group">
                                <div class="w-20 h-20 bg-gray-100 rounded-xl overflow-hidden shrink-0 border border-gray-100">
                                    <img src="<?php echo e(asset('storage/' . $b->gambar)); ?>" alt="<?php echo e($b->judul); ?>" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
                                </div>
                                <div class="flex flex-col justify-center">
                                    <p class="text-[10px] font-bold text-gray-400 mb-1 uppercase tracking-widest"><?php echo e(\Carbon\Carbon::parse($b->created_at)->format('d M Y')); ?></p>
                                    <h4 class="text-[13px] font-black text-adzkia-dark group-hover:text-adzkia-blue transition-colors line-clamp-2 leading-snug"><?php echo e($b->judul); ?></h4>
                                </div>
                            </a>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        <?php else: ?>
                            <div class="flex-1 flex flex-col items-center justify-center text-center p-6 border-2 border-dashed border-gray-100 rounded-2xl">
                                <i data-feather="inbox" class="w-6 h-6 text-gray-300 mb-2"></i>
                                <p class="text-[11px] font-bold text-gray-400">Belum ada informasi terbaru saat ini.</p>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>

                

                <div class="bg-white rounded-[2rem] p-6 border border-gray-100 shadow-sm" x-data="{ active: null }">
                    <h3 class="text-md font-black text-adzkia-dark mb-5">FAQ Bantuan</h3>
                    
                    <div class="space-y-3">
                        <?php $__empty_1 = true; $__currentLoopData = $faqs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $faq): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <div class="border border-gray-100 rounded-xl overflow-hidden">
                            
                            <button @click="active = active === <?php echo e($faq->id); ?> ? null : <?php echo e($faq->id); ?>" class="w-full px-4 py-3 flex items-center justify-between bg-gray-50 hover:bg-gray-100 transition-colors">
                                <span class="text-[12px] font-black text-adzkia-dark text-left"><?php echo e($faq->pertanyaan); ?></span>
                                <i data-feather="chevron-down" class="w-4 h-4 text-gray-400 transition-transform" :class="active === <?php echo e($faq->id); ?> ? 'rotate-180' : ''"></i>
                            </button>
                            <div x-show="active === <?php echo e($faq->id); ?>" x-collapse x-cloak>
                                <div class="p-4 text-[12px] font-medium text-gray-500 leading-relaxed bg-white border-t border-gray-100">
                                    <?php echo e($faq->jawaban); ?>

                                </div>
                            </div>
                        </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <div class="text-center py-6 border-2 border-dashed border-gray-100 rounded-xl">
                            <p class="text-[11px] font-bold text-gray-400">Belum ada pertanyaan umum yang ditambahkan.</p>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>

            </div>
        </div>

        <div class="lg:col-span-4 space-y-6">
        
            <div class="bg-white rounded-[2rem] p-6 border border-gray-100 shadow-sm relative overflow-hidden">
                
                <div class="absolute top-0 left-0 w-full h-24 bg-gradient-to-b from-blue-50 to-white"></div>
                
                <div class="text-center pb-6 border-b border-gray-50 relative z-10 pt-4">
                    <div class="w-24 h-24 bg-white border-4 border-white shadow-md rounded-2xl mx-auto flex items-center justify-center mb-4 overflow-hidden">
                        <?php if($pendaftar->pas_foto): ?>
                            <img src="<?php echo e(asset('storage/' . $pendaftar->pas_foto)); ?>" alt="Foto" class="w-full h-full object-cover">
                        <?php else: ?>
                            <i data-feather="user" class="w-8 h-8 text-gray-300"></i>
                        <?php endif; ?>
                    </div>
                    <h3 class="font-black text-[15px] text-adzkia-dark leading-snug"><?php echo e($pendaftar->nama_lengkap ?? 'Nama Belum Diisi'); ?></h3>
                    
                    
                    <p class="text-[11px] font-bold text-gray-400 mt-1 uppercase tracking-widest"><?php echo e($pendaftar->no_pendaftaran ?? 'No. Reg Belum Ada'); ?></p>
                    
                    <p class="text-[10px] font-bold text-adzkia-blue mt-2 bg-blue-50 px-3 py-1 rounded-full inline-block"><?php echo e($pendaftar->jalur_pendaftaran ?? 'Jalur Reguler'); ?></p>
                </div>

                <div class="pt-6 space-y-4">
                    <div>
                        <p class="text-[9px] font-black text-gray-400 uppercase tracking-widest">Pilihan Jurusan 1</p>
                        <p class="text-xs font-bold text-adzkia-dark mt-0.5"><?php echo e($pendaftar->pilihan_jurusan_1 ?? 'Belum diisi'); ?></p>
                    </div>
                    
                    
                    <div>
                        <p class="text-[9px] font-black text-gray-400 uppercase tracking-widest">Pilihan Jurusan 2</p>
                        <p class="text-xs font-bold text-adzkia-dark mt-0.5"><?php echo e($pendaftar->pilihan_jurusan_2 ?? 'Belum diisi'); ?></p>
                    </div>

                    <div>
                        <p class="text-[9px] font-black text-gray-400 uppercase tracking-widest">No. WhatsApp</p>
                        <p class="text-xs font-bold text-adzkia-dark mt-0.5"><?php echo e($pendaftar->no_whatsapp ?? 'Belum diisi'); ?></p>
                    </div>
                    <div>
                        <p class="text-[9px] font-black text-gray-400 uppercase tracking-widest">Alamat Domisili</p>
                        <p class="text-xs font-bold text-adzkia-dark mt-0.5 leading-relaxed"><?php echo e($pendaftar->alamat_rumah ?? 'Belum diisi'); ?></p>
                    </div>
                </div>
            </div>

            <div class="bg-gradient-to-br from-adzkia-blue to-blue-800 text-white rounded-[2rem] p-6 shadow-md relative overflow-hidden group hover:shadow-lg transition-all">
                <div class="absolute -right-4 -bottom-4 opacity-10 group-hover:scale-110 transition-transform"><i data-feather="phone-call" class="w-24 h-24 text-white"></i></div>
                <i data-feather="help-circle" class="w-6 h-6 text-blue-200 mb-3 relative z-10"></i>
                <h4 class="font-extrabold text-sm relative z-10">Pusat Bantuan PMB</h4>
                <p class="text-[11px] text-blue-100 font-medium mt-1.5 leading-relaxed relative z-10">Apabila mengalami kendala sistem atau kesalahan input data, segera hubungi admin panitia kami.</p>
                <a href="#" class="block w-full text-center py-3 bg-white text-adzkia-blue font-black text-[12px] rounded-xl mt-5 hover:bg-blue-50 transition-all shadow-sm relative z-10">Hubungi via WhatsApp</a>
            </div>
        </div>

    </main>

    <footer class="w-full bg-adzkia-bg py-8 flex flex-col md:flex-row justify-center md:justify-between items-center gap-4 px-6 md:px-16 border-t border-gray-100">
        <p class="text-[11px] font-bold text-gray-400">© 2026 Universitas Adzkia. All Rights Reserved.</p>
        <div class="flex gap-6 text-[11px] font-bold text-gray-500">
            <a href="#" class="hover:text-adzkia-blue transition-colors">Privacy Policy</a>
            <a href="#" class="hover:text-adzkia-blue transition-colors">Terms of Service</a>
        </div>
    </footer>

    <script>
        window.addEventListener('load', function() {
            if(window.feather) feather.replace();
        });
    </script>
</body>
</html><?php /**PATH D:\Database\spmb-adzkia\resources\views/user/dashboard-user.blade.php ENDPATH**/ ?>