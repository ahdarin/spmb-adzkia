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

                @if(session('success_register'))
                @php $reg = session('success_register'); @endphp
                <div x-data="{
                        copied: null,
                        copyText(text, key) {
                            navigator.clipboard.writeText(text).then(() => {
                                this.copied = key;
                                setTimeout(() => this.copied = null, 2000);
                            });
                        }
                     }"
                     class="mb-8 rounded-2xl border border-adzkia-blue/20 bg-adzkia-badge-bg overflow-hidden shadow-sm">

                    {{-- Header --}}
                    <div class="flex items-center gap-3 px-5 py-4 bg-adzkia-blue">
                        <div class="w-8 h-8 rounded-full bg-white/20 flex items-center justify-center shrink-0">
                            <i data-feather="check-circle" class="w-4 h-4 text-white"></i>
                        </div>
                        <div>
                            <p class="text-white font-extrabold text-[14px] leading-tight">Pendaftaran Berhasil!</p>
                            <p class="text-blue-100 text-[11px] font-medium mt-0.5">Data akun Anda telah dikirim via WhatsApp</p>
                        </div>
                    </div>

                    {{-- Body --}}
                    <div class="px-5 py-4 space-y-3">
                        <p class="text-[12px] font-semibold text-adzkia-muted leading-relaxed">
                            Simpan kredensial berikut untuk masuk. Gunakan tombol salin agar tidak salah ketik.
                        </p>

                        {{-- No. Pendaftaran --}}
                        <div class="flex items-center justify-between gap-3 bg-white rounded-xl px-4 py-3 border border-adzkia-blue/10 shadow-sm">
                            <div class="min-w-0">
                                <p class="text-[10px] font-black text-adzkia-muted uppercase tracking-widest mb-0.5">No. Pendaftaran</p>
                                <p class="text-adzkia-blue font-extrabold text-[15px] tracking-wide truncate">{{ $reg['username'] }}</p>
                            </div>
                            <button type="button"
                                    @click="copyText('{{ $reg['username'] }}', 'username')"
                                    class="shrink-0 flex items-center gap-1.5 px-3 py-2 rounded-lg text-[11px] font-bold transition-all"
                                    :class="copied === 'username'
                                        ? 'bg-green-100 text-green-700 border border-green-300'
                                        : 'bg-gray-100 text-gray-600 hover:bg-adzkia-blue hover:text-white border border-transparent'">
                                <i data-feather="copy" class="w-3.5 h-3.5" x-show="copied !== 'username'"></i>
                                <i data-feather="check" class="w-3.5 h-3.5" x-show="copied === 'username'"></i>
                                <span x-text="copied === 'username' ? 'Tersalin!' : 'Salin'"></span>
                            </button>
                        </div>

                        {{-- Password --}}
                        <div class="flex items-center justify-between gap-3 bg-white rounded-xl px-4 py-3 border border-adzkia-blue/10 shadow-sm">
                            <div class="min-w-0">
                                <p class="text-[10px] font-black text-adzkia-muted uppercase tracking-widest mb-0.5">Password</p>
                                <p class="text-adzkia-blue font-extrabold text-[15px] tracking-widest truncate font-mono">{{ $reg['password'] }}</p>
                            </div>
                            <button type="button"
                                    @click="copyText('{{ $reg['password'] }}', 'password')"
                                    class="shrink-0 flex items-center gap-1.5 px-3 py-2 rounded-lg text-[11px] font-bold transition-all"
                                    :class="copied === 'password'
                                        ? 'bg-green-100 text-green-700 border border-green-300'
                                        : 'bg-gray-100 text-gray-600 hover:bg-adzkia-blue hover:text-white border border-transparent'">
                                <i data-feather="copy" class="w-3.5 h-3.5" x-show="copied !== 'password'"></i>
                                <i data-feather="check" class="w-3.5 h-3.5" x-show="copied === 'password'"></i>
                                <span x-text="copied === 'password' ? 'Tersalin!' : 'Salin'"></span>
                            </button>
                        </div>

                        {{-- Tip WA --}}
                        <div class="flex items-start gap-2 pt-1">
                            <i data-feather="info" class="w-3.5 h-3.5 text-adzkia-blue shrink-0 mt-0.5"></i>
                            <p class="text-[11px] font-medium text-adzkia-muted">
                                Detail ini juga sudah dikirimkan ke nomor WhatsApp Anda. Jangan bagikan password kepada siapapun.
                            </p>
                        </div>
                    </div>
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

                    <div class="mt-6" x-data="{ showPass: false }">
                        <label class="block text-[11px] font-extrabold text-gray-500 uppercase tracking-widest mb-2">
                            Password
                        </label>
                        <div class="relative">
                            <input :type="showPass ? 'text' : 'password'"
                                   name="password" 
                                   required 
                                   placeholder="••••••••" 
                                   class="w-full bg-gray-50 text-adzkia-dark px-5 py-4 pr-14 rounded-xl border-2 border-transparent focus:bg-white focus:border-adzkia-blue focus:ring-0 outline-none transition-all font-medium placeholder-gray-400 text-[14px]">
                            <button type="button"
                                    @click="showPass = !showPass"
                                    class="absolute right-4 top-1/2 -translate-y-1/2 text-gray-400 hover:text-adzkia-blue transition-colors p-1"
                                    :title="showPass ? 'Sembunyikan password' : 'Tampilkan password'">
                                <i x-show="!showPass" data-feather="eye"      class="w-5 h-5 pointer-events-none"></i>
                                <i x-show="showPass"  data-feather="eye-off"  class="w-5 h-5 pointer-events-none"></i>
                            </button>
                        </div>
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

    {{-- Alpine HARUS dimuat sebelum feather agar x-show bekerja di render pertama --}}
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/feather-icons/dist/feather.min.js"></script>
    <script>
        // Jalankan feather.replace() setelah Alpine selesai mount
        document.addEventListener('alpine:init', function () {
            setTimeout(() => feather.replace(), 0);
        });
        // Fallback untuk halaman tanpa Alpine
        document.addEventListener('DOMContentLoaded', function () {
            if (window.feather) feather.replace();
        });
    </script>
</body>
</html>