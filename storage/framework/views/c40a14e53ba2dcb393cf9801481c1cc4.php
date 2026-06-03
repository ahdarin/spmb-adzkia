<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $__env->yieldContent('title', 'PMB Universitas Adzkia'); ?></title>
    
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <script src="https://unpkg.com/feather-icons"></script>

    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: { sans: ['Manrope', 'sans-serif'] },
                    colors: {
                        'adzkia-red': '#d9241c',
                        'adzkia-blue': '#2c7ebd',
                        'adzkia-bg': '#FAFBFC', 
                        'adzkia-dark': '#1e293b', 
                        'adzkia-muted': '#64748b', 
                        'adzkia-badge-bg': '#eff6ff', 
                        'adzkia-badge-txt': '#2c7ebd', 
                        'adzkia-badge-red-bg': '#fef2f2', 
                        'adzkia-badge-red-txt': '#d9241c', 
                    }
                }
            }
        }
    </script>

    <script>
        document.addEventListener('alpine:init', () => {
            // Alpine Store sekarang hanya mengurus UI (Buka/Tutup Modal)
            // Data sudah sepenuhnya diurus oleh Laravel Database! 🚀
            Alpine.store('kampus', {
                modalOpen: false,
                activeProgram: {},
                
                openModal(program) {
                    this.activeProgram = program;
                    this.modalOpen = true;
                    setTimeout(() => feather.replace(), 50); 
                },
                
                closeModal() {
                    this.modalOpen = false;
                }
            });
        });
    </script>
</head>

<body class="bg-adzkia-bg antialiased text-adzkia-dark w-full m-0 p-0 flex flex-col min-h-screen overflow-x-hidden">
    <?php if (isset($component)) { $__componentOriginala591787d01fe92c5706972626cdf7231 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginala591787d01fe92c5706972626cdf7231 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.navbar','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('navbar'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginala591787d01fe92c5706972626cdf7231)): ?>
<?php $attributes = $__attributesOriginala591787d01fe92c5706972626cdf7231; ?>
<?php unset($__attributesOriginala591787d01fe92c5706972626cdf7231); ?>
<?php endif; ?>
<?php if (isset($__componentOriginala591787d01fe92c5706972626cdf7231)): ?>
<?php $component = $__componentOriginala591787d01fe92c5706972626cdf7231; ?>
<?php unset($__componentOriginala591787d01fe92c5706972626cdf7231); ?>
<?php endif; ?>

    <main class="flex-grow w-full m-0 p-0">
        <?php echo $__env->yieldContent('content'); ?>
    </main>

    <?php if (isset($component)) { $__componentOriginal8a8716efb3c62a45938aca52e78e0322 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal8a8716efb3c62a45938aca52e78e0322 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.footer','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('footer'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal8a8716efb3c62a45938aca52e78e0322)): ?>
<?php $attributes = $__attributesOriginal8a8716efb3c62a45938aca52e78e0322; ?>
<?php unset($__attributesOriginal8a8716efb3c62a45938aca52e78e0322); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal8a8716efb3c62a45938aca52e78e0322)): ?>
<?php $component = $__componentOriginal8a8716efb3c62a45938aca52e78e0322; ?>
<?php unset($__componentOriginal8a8716efb3c62a45938aca52e78e0322); ?>
<?php endif; ?>

    <div x-data x-show="$store.kampus.modalOpen" class="fixed inset-0 z-[100] flex items-center justify-center px-4" style="display: none;" x-cloak>
        <div x-show="$store.kampus.modalOpen" x-transition.opacity @click="$store.kampus.closeModal()" class="absolute inset-0 bg-adzkia-dark/80 backdrop-blur-sm cursor-pointer"></div>
        
        <div x-show="$store.kampus.modalOpen" 
             x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 scale-95 translate-y-4" x-transition:enter-end="opacity-100 scale-100 translate-y-0" 
             x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100 scale-100 translate-y-0" x-transition:leave-end="opacity-0 scale-95 translate-y-4" 
             class="bg-white w-full max-w-lg rounded-3xl shadow-2xl relative z-10 overflow-hidden">
            <div class="p-10">
                <div class="flex justify-between items-start mb-6">
                    <div>
                        <h2 class="text-3xl font-extrabold text-adzkia-dark tracking-tight" x-text="$store.kampus.activeProgram.nama_prodi"></h2>
                        <span class="mt-3 inline-block px-3 py-1 bg-adzkia-badge-bg text-adzkia-badge-txt text-[11px] font-extrabold rounded-full uppercase" x-text="$store.kampus.activeProgram.akreditasi"></span>
                    </div>
                    <button @click="$store.kampus.closeModal()" class="p-2 hover:bg-gray-100 rounded-full transition-colors">
                        <i data-feather="x" class="text-gray-500 w-5 h-5"></i>
                    </button>
                </div>
                <div class="space-y-6">
                    <div>
                        <h4 class="text-xs font-extrabold uppercase tracking-widest text-gray-400 mb-2">Deskripsi Program</h4>
                        <p class="text-gray-600 font-medium leading-relaxed text-[15px]" x-text="$store.kampus.activeProgram.deskripsi"></p>
                    </div>
                    <div class="bg-adzkia-bg p-6 rounded-2xl border border-gray-100">
                        <h4 class="text-xs font-extrabold uppercase tracking-widest text-gray-400 mb-2">Estimasi Biaya Kuliah</h4>
                        <div class="flex items-baseline gap-2">
                            <span class="text-sm font-medium text-gray-500">Mulai dari</span>
                            <span class="text-2xl font-black text-adzkia-dark" x-text="'Rp ' + new Intl.NumberFormat('id-ID').format($store.kampus.activeProgram.biaya || 0)"></span>
                            <span class="text-xs text-gray-500">/semester</span>
                        </div>
                    </div>
                    <a href="/register" class="w-full py-4 bg-adzkia-dark text-white font-extrabold rounded-full shadow-xl hover:scale-105 active:scale-95 transition-all flex justify-center items-center">
                        Daftar Program Ini
                    </a>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/feather-icons/dist/feather.min.js"></script>
    
    <script>
        window.addEventListener('load', function() {
            feather.replace();
        });
    </script>
</body>
</html><?php /**PATH D:\Database\spmb-adzkia\resources\views/layouts/app.blade.php ENDPATH**/ ?>