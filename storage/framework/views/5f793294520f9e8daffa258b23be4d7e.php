<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Portal - Universitas Adzkia</title>
    
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
                        'adzkia-dark': '#0A1B3A',
                        'adzkia-badge-bg': '#EEF4FF',
                        'adzkia-badge-txt': '#2D68F8',
                    }
                }
            }
        }
    </script>
</head>
<body class="bg-[#F3F6F9] min-h-screen flex items-center justify-center p-6 antialiased text-adzkia-dark">

    <div class="w-full max-w-[440px] bg-white rounded-[2.5rem] shadow-2xl shadow-adzkia-dark/5 overflow-hidden border border-gray-100">
        
        <div class="bg-adzkia-dark p-10 text-center relative overflow-hidden">
            <div class="absolute top-0 right-0 w-32 h-32 bg-white/5 rounded-full -mr-16 -mt-16"></div>
            
            <div class="inline-flex items-center justify-center w-16 h-16 bg-white rounded-2xl mb-6 shadow-xl">
                <img src="<?php echo e(asset('images/logo-adzkia.png')); ?>" alt="Logo Universitas Adzkia" class="h-11 w-auto transition-transform group-hover:scale-105 duration-300">
            </div>
            
            <h1 class="text-white text-2xl font-extrabold tracking-tight mb-2">Portal Admin</h1>
            <p class="text-white/60 text-sm font-medium">Silakan masuk untuk mengelola sistem pendaftaran</p>
        </div>

        <div class="p-10" x-data="{ showPassword: false, isLoading: false }">
            
            
            <?php if($errors->any() || session('error')): ?>
                <div class="mb-6 p-4 bg-red-50 border border-red-200 rounded-2xl flex items-start gap-3">
                    <i data-feather="x-circle" class="w-5 h-5 text-red-500 shrink-0"></i>
                    <p class="text-[12px] font-bold text-red-700">
                        <?php echo e($errors->first() ?: session('error')); ?>

                    </p>
                </div>
            <?php endif; ?>

            
            <form action="<?php echo e(route('login.post')); ?>" method="POST" @submit="isLoading = true" class="space-y-6">
                <?php echo csrf_field(); ?>
                
                <div>
                    <label class="block text-[11px] font-extrabold text-gray-400 uppercase tracking-widest mb-2.5">
                        Alamat Email Admin
                    </label>
                    <div class="relative group">
                        <div class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-300 group-focus-within:text-adzkia-badge-txt transition-colors">
                            <i data-feather="user" class="w-4 h-4"></i>
                        </div>
                        <input type="email" name="email" required value="<?php echo e(old('email')); ?>"
                               placeholder="admin@adzkia.ac.id" 
                               class="w-full pl-12 pr-5 py-4 bg-gray-50 border-2 border-transparent rounded-2xl focus:bg-white focus:border-adzkia-badge-bg focus:ring-0 outline-none transition-all font-bold text-[14px] placeholder-gray-300">
                    </div>
                </div>

                <div>
                    <label class="block text-[11px] font-extrabold text-gray-400 uppercase tracking-widest mb-2.5">
                        Security Password
                    </label>
                    <div class="relative group">
                        <div class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-300 group-focus-within:text-adzkia-badge-txt transition-colors">
                            <i data-feather="lock" class="w-4 h-4"></i>
                        </div>
                        
                        <input :type="showPassword ? 'text' : 'password'" name="password" required
                               placeholder="••••••••" 
                               class="w-full pl-12 pr-12 py-4 bg-gray-50 border-2 border-transparent rounded-2xl focus:bg-white focus:border-adzkia-badge-bg focus:ring-0 outline-none transition-all font-bold text-[14px] placeholder-gray-300">
                        
                        <button type="button" 
                                @click="showPassword = !showPassword"
                                class="absolute right-4 top-1/2 -translate-y-1/2 text-gray-300 hover:text-adzkia-dark transition-colors">
                            <i x-show="!showPassword" data-feather="eye" class="w-4 h-4"></i>
                            <i x-show="showPassword" data-feather="eye-off" class="w-4 h-4"></i>
                        </button>
                    </div>
                </div>

                <div class="flex items-center justify-between text-[12px] font-bold text-gray-400">
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input type="checkbox" name="remember" class="w-4 h-4 rounded border-gray-300 text-adzkia-dark focus:ring-adzkia-dark">
                        <span>Ingat Sesi Ini</span>
                    </label>
                    <a href="#" class="text-adzkia-badge-txt hover:underline">Bantuan Login</a>
                </div>

                <button type="submit" 
                        :disabled="isLoading"
                        class="w-full bg-adzkia-dark text-white font-extrabold py-4 rounded-2xl shadow-xl shadow-adzkia-dark/10 hover:shadow-2xl hover:-translate-y-0.5 active:translate-y-0 transition-all flex items-center justify-center gap-3 disabled:opacity-70 disabled:cursor-not-allowed">
                    
                    <span x-show="!isLoading" class="flex items-center gap-3">
                        Otoritasi Masuk <i data-feather="shield" class="w-4 h-4"></i>
                    </span>

                    <span x-show="isLoading" class="flex items-center gap-3" x-cloak>
                        <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        Memproses...
                    </span>
                </button>

            </form>
        </div>

        <div class="px-10 pb-10">
            <div class="flex items-center gap-3 p-4 bg-amber-50 rounded-2xl border border-amber-100">
                <i data-feather="alert-circle" class="w-5 h-5 text-amber-600 shrink-0"></i>
                <p class="text-[11px] text-amber-800 font-bold leading-relaxed">
                    Akses ini diawasi secara sistem. Dilarang memberikan akses kepada pihak yang tidak berwenang.
                </p>
            </div>
        </div>
    </div>

    <script>
        feather.replace();
    </script>
</body>
</html><?php /**PATH D:\Database\spmb-adzkia\resources\views/login-admin.blade.php ENDPATH**/ ?>