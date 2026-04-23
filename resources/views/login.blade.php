<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - PMB Universitas Adzkia</title>
    
    <script src="https://cdn.tailwindcss.com"></script>
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
                        'adzkia-muted': '#889CB5',
                        'adzkia-badge-bg': '#EEF4FF',
                        'adzkia-badge-txt': '#2D68F8',
                        'adzkia-bg': '#FAFBFC',
                    }
                }
            }
        }
    </script>
</head>
<body class="bg-gray-50 antialiased text-adzkia-dark min-h-screen flex flex-col">

    <div class="flex-grow flex flex-col lg:flex-row">
        
        <div class="hidden lg:flex w-1/2 relative flex-col justify-center px-20">
            <img src="https://images.unsplash.com/photo-1541339907198-e08756dedf3f?ixlib=rb-4.0.3&auto=format&fit=crop&w=1000&q=80" 
                 alt="Kampus Adzkia" 
                 class="absolute inset-0 w-full h-full object-cover">
            
            <div class="absolute inset-0 bg-adzkia-dark/80 mix-blend-multiply"></div>
            <div class="absolute inset-0 bg-gradient-to-t from-adzkia-dark via-adzkia-dark/80 to-transparent"></div>

            <div class="relative z-10">
                <h1 class="text-5xl font-extrabold text-white mb-6 leading-tight">
                    Selamat Datang <br> Kembali
                </h1>
                <p class="text-lg text-gray-300 font-medium leading-relaxed max-w-md mb-12">
                    Masuk untuk melanjutkan proses pendaftaran Anda dan bergabung dengan komunitas akademik kami yang prestisius.
                </p>

                <div class="inline-flex items-center gap-2.5 px-5 py-2.5 rounded-full border border-white/20 bg-white/10 backdrop-blur-md text-white">
                    <i data-feather="check-circle" class="w-4 h-4"></i>
                    <span class="text-[11px] font-extrabold uppercase tracking-widest">Portalisasi Akademik Terkurasi</span>
                </div>
            </div>
        </div>

        <div class="w-full lg:w-1/2 flex flex-col justify-center items-center bg-white px-8 py-20 relative">
            
            <a href="/" class="absolute top-8 right-12 text-gray-400 hover:text-adzkia-dark transition-colors flex items-center gap-2 text-sm font-bold">
                <i data-feather="x" class="w-5 h-5"></i> Tutup
            </a>

            <div class="w-full max-w-md">
                
                <div class="mb-10">
                    <h2 class="text-4xl font-extrabold text-adzkia-dark mb-2">Login</h2>
                    <p class="text-gray-500 font-medium text-[15px]">Masuk ke akun Anda</p>
                </div>

                <form action="#" method="POST" class="space-y-6">
                    
                    <div>
                        <label class="block text-[11px] font-extrabold text-gray-500 uppercase tracking-widest mb-2">
                            Email / No. Pendaftaran
                        </label>
                        <input type="text" 
                               placeholder="nama@email.com atau 2026XXXX" 
                               class="w-full bg-gray-100 text-adzkia-dark px-5 py-4 rounded-xl border-2 border-transparent focus:bg-white focus:border-adzkia-badge-bg focus:ring-0 outline-none transition-all font-medium placeholder-gray-400 text-[14px]">
                    </div>

                    <div>
                        <div class="flex justify-between items-center mb-2">
                            <label class="block text-[11px] font-extrabold text-gray-500 uppercase tracking-widest">
                                Password
                            </label>
                            <a href="#" class="text-[11px] font-extrabold text-adzkia-dark hover:underline">
                                Lupa Password?
                            </a>
                        </div>
                        <input type="password" 
                               placeholder="••••••••" 
                               class="w-full bg-gray-100 text-adzkia-dark px-5 py-4 rounded-xl border-2 border-transparent focus:bg-white focus:border-adzkia-badge-bg focus:ring-0 outline-none transition-all font-medium placeholder-gray-400 text-[14px]">
                    </div>

                    <div class="flex items-center gap-3 pt-2 pb-4">
                        <input type="checkbox" id="remember" class="w-4 h-4 rounded border-gray-300 text-adzkia-dark focus:ring-adzkia-dark cursor-pointer">
                        <label for="remember" class="text-[13px] font-medium text-gray-600 cursor-pointer">
                            Ingat Saya
                        </label>
                    </div>

                    <button type="button" class="w-full bg-adzkia-dark text-white font-extrabold py-4 rounded-xl shadow-xl shadow-adzkia-dark/20 hover:bg-opacity-90 active:scale-[0.98] transition-all flex items-center justify-center gap-2">
                        Masuk <i data-feather="arrow-right" class="w-4 h-4"></i>
                    </button>

                </form>

                <div class="mt-8 pt-8 border-t border-gray-100 text-center">
                    <p class="text-[13px] font-medium text-gray-500">
                        Belum punya akun? <a href="#" class="font-extrabold text-adzkia-dark hover:underline">Daftar sekarang</a>
                    </p>
                </div>

            </div>
        </div>
    </div>

    <footer class="bg-gray-50 py-6 px-12 border-t border-gray-200 w-full flex flex-col md:flex-row justify-between items-center gap-4">
        <p class="text-[12px] font-extrabold text-gray-400">
            © 2026 Universitas Adzkia. All Rights Reserved.
        </p>
        <div class="flex items-center gap-6 text-[12px] font-extrabold text-gray-500">
            <a href="#" class="hover:text-adzkia-dark transition-colors">Privacy Policy</a>
            <a href="#" class="hover:text-adzkia-dark transition-colors">Terms of Service</a>
            <a href="#" class="hover:text-adzkia-dark transition-colors">Accessibility</a>
        </div>
    </footer>

    <script>
        feather.replace();
    </script>
</body>
</html>