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
                        'adzkia-badge-txt': '#2c7ebd',
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
            <img src="{{ asset('images/gedung-adzkia.png') }}" 
                 alt="Kampus Adzkia" 
                 class="absolute inset-0 w-full h-full object-cover">
            
            <div class="absolute inset-0 bg-adzkia-blue/80 mix-blend-multiply"></div>
            <div class="absolute inset-0 bg-gradient-to-t from-adzkia-blue via-adzkia-blue/80 to-transparent"></div>

            <div class="relative z-10">
                <h1 class="text-5xl font-extrabold text-white mb-6 leading-tight">
                    Selamat Datang <br> Kembali
                </h1>
                <p class="text-lg text-blue-100 font-medium leading-relaxed max-w-md mb-12">
                    Masuk untuk melanjutkan proses pendaftaran Anda dan bergabung dengan komunitas akademik kami yang prestisius.
                </p>

                <div class="inline-flex items-center gap-2.5 px-5 py-2.5 rounded-full border border-white/20 bg-white/10 backdrop-blur-md text-white">
                    <i data-feather="check-circle" class="w-4 h-4"></i>
                    <span class="text-[11px] font-extrabold uppercase tracking-widest">Portalisasi Akademik Terkurasi</span>
                </div>
            </div>
        </div>

        <div class="w-full lg:w-1/2 flex flex-col justify-center items-center bg-white px-8 py-20 relative">
            
            <a href="/" class="absolute top-8 right-12 text-gray-400 hover:text-adzkia-red transition-colors flex items-center gap-2 text-sm font-bold">
                <i data-feather="x" class="w-5 h-5"></i> Tutup
            </a>

            <div class="w-full max-w-md">
                
                <div class="mb-10">
                    <h2 class="text-4xl font-extrabold text-adzkia-blue mb-2">Login</h2>
                    <p class="text-gray-500 font-medium text-[15px]">Masuk ke akun Anda</p>
                </div>

                @if(session('no_pendaftaran'))
                <div class="bg-green-50 border-l-4 border-green-500 p-4 mb-6 rounded-r-xl">
                    <p class="font-bold text-green-700">{{ session('success') }}</p>
                    <p class="text-sm text-green-600 mt-1">Simpan data akun Anda untuk login:</p>
                    <ul class="mt-2 text-sm text-green-800 bg-green-100/50 p-3 rounded-lg border border-green-200">
                        <li>No. Pendaftaran: <strong class="text-adzkia-blue">{{ session('no_pendaftaran') }}</strong></li>
                        <li>Password: <strong class="text-adzkia-blue">{{ session('password') }}</strong></li>
                    </ul>
                </div>
                @endif

                {{-- Alert Error jika Validasi Login Gagal atau Akses Ditolak --}}
                @if($errors->any() || session('error'))
                    <div class="mb-6 p-4 bg-red-50 border-l-4 border-adzkia-red rounded-r-xl text-sm text-red-700 font-semibold shadow-sm flex items-start gap-3">
                        <i data-feather="alert-circle" class="w-5 h-5 shrink-0"></i>
                        <span>{{ $errors->first() ?: session('error') }}</span>
                    </div>
                @endif

                {{-- FORM ASLI MENGARAH KE AUTH CONTROLLER --}}
                <form action="{{ route('login.post') }}" method="POST">
                    @csrf
                    
                    <div>
                        <label class="block text-[11px] font-extrabold text-gray-500 uppercase tracking-widest mb-2">
                            No. Registrasi Pendaftar
                        </label>
                        <input type="text" 
                               name="login_input" 
                               required 
                               value="{{ old('login_input', session('username')) }}"
                               placeholder="REG-2026-0000" 
                               class="w-full bg-gray-50 text-adzkia-dark px-5 py-4 rounded-xl border-2 border-transparent focus:bg-white focus:border-adzkia-blue focus:ring-0 outline-none transition-all font-medium placeholder-gray-400 text-[14px]">
                    </div>

                    <div class="mt-6">
                        <div class="flex justify-between items-center mb-2">
                            <label class="block text-[11px] font-extrabold text-gray-500 uppercase tracking-widest">
                                Password
                            </label>
                        </div>
                        <input type="password" 
                               name="password" 
                               required 
                               value="{{ session('password') }}"
                               placeholder="••••••••" 
                               class="w-full bg-gray-50 text-adzkia-dark px-5 py-4 rounded-xl border-2 border-transparent focus:bg-white focus:border-adzkia-blue focus:ring-0 outline-none transition-all font-medium placeholder-gray-400 text-[14px]">
                    </div>

                    <div class="flex items-center gap-3 pt-4 pb-6">
                        <input type="checkbox" id="remember" name="remember" class="w-4 h-4 rounded border-gray-300 text-adzkia-blue focus:ring-adzkia-blue cursor-pointer">
                        <label for="remember" class="text-[13px] font-medium text-gray-600 cursor-pointer hover:text-adzkia-dark">
                            Ingat Saya
                        </label>
                    </div>

                    <button type="submit" class="w-full bg-adzkia-red text-white font-extrabold py-4 rounded-xl shadow-xl shadow-red-600/20 hover:bg-red-700 active:scale-[0.98] transition-all flex items-center justify-center gap-2">
                        Masuk <i data-feather="arrow-right" class="w-4 h-4"></i>
                    </button>

                </form>

                <div class="mt-8 pt-8 border-t border-gray-100 text-center">
                    <p class="text-[13px] font-medium text-gray-500">
                        Belum punya akun? <a href="{{ route('register') }}" class="font-extrabold text-adzkia-red hover:underline hover:text-red-700 transition-colors">Daftar sekarang</a>
                    </p>
                </div>

            </div>
        </div>
    </div>

    <footer class="bg-gray-50 py-6 px-12 border-t border-gray-200 w-full flex flex-col md:flex-row justify-between items-center gap-4">
        <p class="text-[12px] font-extrabold text-gray-400">
            © {{ date('Y') }} Universitas Adzkia. All Rights Reserved.
        </p>
        <div class="flex items-center gap-6 text-[12px] font-extrabold text-gray-500">
            <a href="#" class="hover:text-adzkia-blue transition-colors">Privacy Policy</a>
            <a href="#" class="hover:text-adzkia-blue transition-colors">Terms of Service</a>
            <a href="#" class="hover:text-adzkia-blue transition-colors">Accessibility</a>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/feather-icons/dist/feather.min.js"></script>
    <script>
        window.addEventListener('load', function() {
            feather.replace();
        });
    </script>
</body>
</html>