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
            <img src="{{ asset('images/logo-adzkia.png') }}" alt="Logo Adzkia" class="h-10 w-auto">
            <div class="flex flex-col">
                <span class="text-md font-black text-adzkia-blue leading-none">PORTAL PENDAFTAR</span>
                <span class="text-xs font-bold text-adzkia-red">Universitas Adzkia</span>
            </div>
        </div>
        <div class="flex items-center gap-4">
            <div class="text-right hidden sm:block">
                <p class="text-xs font-black text-adzkia-dark leading-none">{{ session('nama_pendaftar') }}</p>
                <p class="text-[10px] font-bold text-gray-400 mt-1 uppercase tracking-widest">No: {{ $pendaftar->no_pendaftaran }}</p>
            </div>
            <form action="{{ route('logout') }}" method="POST" class="inline">
                @csrf
                <button type="submit" class="p-2.5 bg-gray-50 text-gray-400 hover:text-adzkia-red rounded-xl hover:bg-red-50 transition-all">
                    <i data-feather="log-out" class="w-4 h-4"></i>
                </button>
            </form>
        </div>
    </nav>

    <main class="flex-1 max-w-6xl mx-auto w-full px-6 py-12 grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
        
        <div class="lg:col-span-8 space-y-6">
            
            {{-- KONDISI 1: PEMBAYARAN TELAH TERVERIFIKASI (MUNCUL OPSI LANJUTKAN) --}}
            @if($pendaftar->status_pembayaran == 'Terverifikasi')
            <div class="bg-green-50 border border-green-200 rounded-3xl p-6 flex gap-4 items-start shadow-sm shadow-green-600/5">
                <div class="w-10 h-10 rounded-xl bg-green-500 text-white flex items-center justify-center shrink-0 shadow-md shadow-green-500/20">
                    <i data-feather="check-circle" class="w-5 h-5"></i>
                </div>
                <div class="flex-1">
                    <h3 class="text-[15px] font-black text-green-900">Pembayaran Terverifikasi!</h3>
                    <p class="text-xs font-medium text-green-700/90 mt-1 leading-relaxed">
                        Biaya administrasi pendaftaran Anda telah divalidasi oleh sistem. Silakan lanjutkan langkah berikutnya untuk melengkapi berkas biodata, pas foto, KTP, dan Ijazah sekolah asal.
                    </p>
                    <a href="{{ route('pendaftaran.biodata') }}" class="inline-flex items-center gap-2 px-6 py-3 bg-adzkia-red hover:bg-red-700 text-white font-black text-sm rounded-xl mt-4 transition-all shadow-lg shadow-red-600/20 active:scale-[0.98]">
                        Lanjutkan Pengisian Formulir &rarr;
                    </a>
                </div>
            </div>
            @endif

            {{-- KONDISI 2: BELUM MELAKUKAN PEMBAYARAN --}}
            @if($pendaftar->status_pembayaran == 'Belum Bayar')
            <div class="bg-amber-50 border border-amber-200 rounded-3xl p-6 flex gap-4 items-start">
                <div class="w-10 h-10 rounded-xl bg-amber-500 text-white flex items-center justify-center shrink-0 shadow-md shadow-amber-500/20">
                    <i data-feather="credit-card" class="w-5 h-5"></i>
                </div>
                <div class="flex-1">
                    <h3 class="text-[15px] font-black text-amber-900">Selesaikan Pembayaran Biaya Administrasi</h3>
                    <p class="text-xs font-medium text-amber-700/90 mt-1 leading-relaxed">
                        Anda belum menyelesaikan pembayaran registrasi awal sebesar <span class="font-extrabold text-adzkia-dark">Rp 250.000</span>. Pilih metode pembayaran Anda untuk mengaktifkan tahap pengisian formulir berkas.
                    </p>
                    <a href="{{ url('/pembayaran') }}" class="inline-flex items-center gap-2 px-5 py-2.5 bg-amber-600 hover:bg-amber-700 text-white font-extrabold text-xs rounded-xl mt-4 transition-all">
                        Pilih Metode Pembayaran &rarr;
                    </a>
                </div>
            </div>
            @endif

            {{-- KONDISI 3: MENUGGU VALIDASI BUKTI TRANSFER OLEH ADMIN --}}
            @if($pendaftar->status_pembayaran == 'Menunggu Validasi')
            <div class="bg-blue-50 border border-blue-200 rounded-3xl p-6 flex gap-4 items-start">
                <div class="w-10 h-10 rounded-xl bg-adzkia-blue text-white flex items-center justify-center shrink-0 shadow-md shadow-adzkia-blue/20">
                    <i data-feather="clock" class="w-5 h-5 animate-spin"></i>
                </div>
                <div class="flex-1">
                    <h3 class="text-[15px] font-black text-adzkia-blue">Bukti Pembayaran Sedang Diperiksa</h3>
                    <p class="text-xs font-medium text-gray-500 mt-1 leading-relaxed">
                        Bukti pembayaran yang Anda unggah sedang dalam antrean verifikasi oleh admin keuangan Universitas Adzkia. Mohon tunggu maksimal 1x24 jam kerja.
                    </p>
                    <div class="inline-block px-3 py-1 bg-white border border-gray-200 rounded-lg text-[11px] font-bold text-gray-400 mt-3">Proses Validasi Otomatis Sedang Berjalan</div>
                </div>
            </div>
            @endif

            <div class="bg-white rounded-[2rem] p-8 border border-gray-100 shadow-sm">
                <h3 class="text-lg font-black text-adzkia-dark mb-6">Kemajuan Pendaftaran</h3>
                
                <div class="space-y-6 relative before:absolute before:left-5 before:top-2 before:bottom-2 before:w-0.5 before:bg-gray-100">
                    
                    <div class="flex gap-4 items-start relative z-10">
                        <div class="w-10 h-10 rounded-xl bg-green-500 text-white flex items-center justify-center shadow-sm shrink-0">
                            <i data-feather="check" class="w-5 h-5"></i>
                        </div>
                        <div>
                            <h4 class="text-sm font-black text-adzkia-dark">Pengisian Akun Registrasi Awal</h4>
                            <p class="text-xs font-medium text-gray-400 mt-0.5">Berhasil divalidasi dengan ID Sistem: {{ $pendaftar->no_pendaftaran }}</p>
                        </div>
                    </div>

                    <div class="flex gap-4 items-start relative z-10">
                        <div class="w-10 h-10 rounded-xl flex items-center justify-center shadow-sm shrink-0 font-bold text-sm
                            {{ $pendaftar->status_pembayaran == 'Terverifikasi' ? 'bg-green-500 text-white' : ($pendaftar->status_pembayaran == 'Menunggu Validasi' ? 'bg-blue-500 text-white' : 'bg-gray-100 text-gray-400') }}">
                            @if($pendaftar->status_pembayaran == 'Terverifikasi')
                                <i data-feather="check" class="w-5 h-5"></i>
                            @else
                                <span>2</span>
                            @endif
                        </div>
                        <div class="flex-1 flex justify-between items-center">
                            <div>
                                <h4 class="text-sm font-black text-adzkia-dark">Biaya Pendaftaran Administrasi</h4>
                                <p class="text-xs font-medium text-gray-400 mt-0.5">Status Pembayaran: <span class="font-bold text-adzkia-blue">{{ $pendaftar->status_pembayaran }}</span></p>
                            </div>
                        </div>
                    </div>

                    <div class="flex gap-4 items-start relative z-10">
                        <div class="w-10 h-10 rounded-xl flex items-center justify-center shadow-sm shrink-0 font-bold text-sm
                            {{ $pendaftar->status_pendaftaran == 'menunggu verifikasi' ? 'bg-green-500 text-white' : ($pendaftar->status_pembayaran == 'Terverifikasi' ? 'bg-adzkia-blue text-white ring-4 ring-blue-50 scale-105' : 'bg-gray-100 text-gray-400') }}">
                            @if($pendaftar->status_pendaftaran == 'menunggu verifikasi')
                                <i data-feather="check" class="w-5 h-5"></i>
                            @else
                                <span>3</span>
                            @endif
                        </div>
                        <div class="flex-1 flex justify-between items-center">
                            <div>
                                <h4 class="text-sm font-black text-adzkia-dark">Pengisian Berkas Biodata Calon Mahasiswa</h4>
                                <p class="text-xs font-medium text-gray-400 mt-0.5">Mengisi data kependudukan, riwayat pendidikan, upload Pas Foto, KTP, dan Ijazah.</p>
                            </div>
                        </div>
                    </div>

                    <div class="flex gap-4 items-start relative z-10">
                        <div class="w-10 h-10 rounded-xl bg-gray-100 text-gray-400 flex items-center justify-center shadow-sm shrink-0 font-bold text-sm">
                            <span>4</span>
                        </div>
                        <div>
                            <h4 class="text-sm font-black text-adzkia-dark">Validasi Dokumen Akhir & Kelulusan</h4>
                            <p class="text-xs font-medium text-gray-400 mt-0.5">Penetapan kelulusan berkas administrasi PMB oleh dewan pimpinan Universitas Adzkia.</p>
                        </div>
                    </div>

                </div>
            </div>

        </div>

        <div class="lg:col-span-4 space-y-6">
            <div class="bg-white rounded-[2rem] p-6 border border-gray-100 shadow-sm">
                <div class="text-center pb-6 border-b border-gray-50">
                    <div class="w-20 h-20 bg-gray-50 border border-gray-100 rounded-2xl mx-auto flex items-center justify-center mb-3 overflow-hidden">
                        <i data-feather="user" class="w-8 h-8 text-gray-300"></i>
                    </div>
                    <h3 class="font-black text-md text-adzkia-dark leading-snug">{{ $pendaftar->nama_lengkap }}</h3>
                    <div class="mt-1.5 inline-block px-3 py-1 bg-adzkia-badge-bg text-adzkia-blue text-[10px] font-black uppercase tracking-widest rounded-md">
                        Jalur {{ $pendaftar->jalur_pendaftaran }}
                    </div>
                </div>

                <div class="pt-6 space-y-4">
                    <div>
                        <p class="text-[9px] font-black text-gray-400 uppercase tracking-widest">Pilihan Jurusan 1</p>
                        <p class="text-xs font-bold text-adzkia-dark mt-0.5">{{ $pendaftar->pilihan_jurusan_1 }}</p>
                    </div>
                    <div>
                        <p class="text-[9px] font-black text-gray-400 uppercase tracking-widest">Pilihan Jurusan 2</p>
                        <p class="text-xs font-bold text-adzkia-dark mt-0.5">{{ $pendaftar->pilihan_jurusan_2 }}</p>
                    </div>
                    <div>
                        <p class="text-[9px] font-black text-gray-400 uppercase tracking-widest">Alamat Rumah</p>
                        <p class="text-xs font-bold text-adzkia-dark mt-0.5 leading-relaxed">{{ $pendaftar->alamat_rumah }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-gradient-to-br from-adzkia-blue to-blue-800 text-white rounded-[2rem] p-6 shadow-md">
                <i data-feather="help-circle" class="w-6 h-6 text-blue-200 mb-3"></i>
                <h4 class="font-extrabold text-sm">Butuh Bantuan Teknis?</h4>
                <p class="text-xs text-blue-100 font-medium mt-1 leading-relaxed">Apabila mengalami kendala verifikasi berkas atau kesalahan input data, hubungi sekretariat PMB Universitas Adzkia.</p>
                <a href="#" class="block w-full text-center py-2.5 bg-white text-adzkia-blue font-black text-xs rounded-xl mt-4 hover:bg-blue-50 transition-all shadow-sm">Hubungi via WhatsApp</a>
            </div>
        </div>

    </main>

    <script>
        window.addEventListener('load', function() {
            if(window.feather) feather.replace();
        });
    </script>
</body>
</html>