<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pembayaran - Dasbor SPMB Adzkia</title>
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
                        'adzkia-badge-bg': '#eff6ff',
                    }
                }
            }
        }
    </script>
    <style> [x-cloak] { display: none !important; } </style>
</head>
<body class="bg-gray-50 antialiased text-adzkia-dark min-h-screen flex flex-col">

    {{-- NAVBAR DASHBOARD USER --}}
    <nav class="bg-white border-b border-gray-200 py-4 px-6 md:px-10 flex justify-between items-center sticky top-0 z-30">
        <a href="{{ route('dashboard.user') }}" class="flex items-center gap-3 group">
            <img src="{{ asset('images/logo-adzkia.png') }}" alt="Logo" class="h-10 w-auto group-hover:scale-105 transition-transform">
            <div class="hidden md:flex flex-col">
                <span class="text-[16px] font-black text-adzkia-blue leading-none">Dasbor</span>
                <span class="text-[12px] font-bold text-adzkia-red">Calon Mahasiswa</span>
            </div>
        </a>
        
        <div class="flex items-center gap-4 md:gap-6">
            <a href="{{ route('dashboard.user') }}" class="flex items-center gap-2 text-[12px] md:text-[13px] font-bold text-gray-500 hover:text-adzkia-blue transition-colors bg-gray-50 px-4 py-2 rounded-lg">
                <i data-feather="arrow-left" class="w-4 h-4"></i> Kembali ke Dasbor
            </a>
            <div class="hidden md:block w-px h-6 bg-gray-200"></div>
            <div class="flex items-center gap-3">
                <div class="text-right hidden md:block">
                    <p class="text-[13px] font-extrabold text-adzkia-dark">{{ session('nama_pendaftar') }}</p>
                    <p class="text-[11px] font-bold text-gray-400">ID: {{ $pendaftar->no_pendaftaran }}</p>
                </div>
                <img src="https://ui-avatars.com/api/?name={{ urlencode(session('nama_pendaftar')) }}&background=1e293b&color=fff" class="w-10 h-10 rounded-full border-2 border-gray-100">
            </div>
        </div>
    </nav>

    {{-- STEP PROGRESS TRACKER --}}
    <div class="w-full bg-white py-6 border-b border-gray-100 z-20" x-data="{ 
        currentStep: 2, 
        steps: [
            { id: 1, title: 'Pendaftaran' }, { id: 2, title: 'Biaya' },
            { id: 3, title: 'Validasi' }, { id: 4, title: 'Biodata' },
            { id: 5, title: 'Dokumen' }, { id: 6, title: 'Ujian' }, { id: 7, title: 'Hasil' }
        ] 
    }">
        <div class="max-w-5xl mx-auto px-6">
            <div class="flex items-center justify-between relative">
                <div class="absolute top-1/2 left-0 w-full h-0.5 bg-gray-100 -translate-y-1/2 z-0"></div>
                <template x-for="step in steps" :key="step.id">
                    <div class="relative z-10 flex flex-col items-center gap-2">
                        <div class="w-10 h-10 rounded-full flex items-center justify-center font-bold text-[13px] transition-all duration-300"
                             :class="currentStep === step.id ? 'bg-adzkia-blue text-white shadow-lg shadow-adzkia-blue/30 scale-110' : (step.id < currentStep ? 'bg-green-500 text-white border-2 border-green-500' : 'bg-white border-2 border-gray-100 text-gray-400')">
                            
                            {{-- Tampilkan icon Check jika step sudah terlewati --}}
                            <span x-show="step.id < currentStep"><i data-feather="check" class="w-4 h-4"></i></span>
                            <span x-show="step.id >= currentStep" x-text="step.id"></span>
                        </div>
                        <span class="text-[9px] font-black uppercase tracking-widest hidden md:block" 
                              :class="currentStep === step.id ? 'text-adzkia-blue' : (step.id < currentStep ? 'text-green-500' : 'text-gray-400')" 
                              x-text="step.title"></span>
                    </div>
                </template>
            </div>
        </div>
    </div>

    <main class="flex-1 max-w-4xl mx-auto w-full px-6 py-10">
        
        {{-- JUDUL HALAMAN --}}
        <div class="mb-10 text-center md:text-left">
            <span class="inline-block px-3 py-1 bg-adzkia-badge-bg text-adzkia-blue rounded-lg text-[11px] font-black uppercase tracking-widest mb-3">STEP 02 / 07</span>
            <h1 class="text-3xl font-black text-adzkia-dark tracking-tight">Penyelesaian Administrasi</h1>
            <p class="text-[14px] font-medium text-gray-500 mt-2">Selesaikan pembayaran pendaftaran Anda untuk dapat mengakses formulir biodata lengkap.</p>
        </div>

        {{-- ALERT NOTIFIKASI --}}
        @if(session('success'))
            <div class="mb-6 p-4 bg-green-50 border-l-4 border-green-500 rounded-r-xl flex items-start gap-3">
                <i data-feather="check-circle" class="w-5 h-5 text-green-600 shrink-0"></i>
                <p class="text-[13px] font-bold text-green-800">{{ session('success') }}</p>
            </div>
        @endif
        @if($errors->any())
            <div class="mb-6 p-4 bg-red-50 border-l-4 border-adzkia-red rounded-r-xl flex items-start gap-3">
                <i data-feather="alert-circle" class="w-5 h-5 text-red-600 shrink-0"></i>
                <ul class="text-[13px] font-bold text-red-800 space-y-1">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        {{-- STATUS TERVERIFIKASI JALUR KHUSUS / LUNAS --}}
        @if($pendaftar->status_pembayaran === 'Terverifikasi' && $pendaftar->nominal_biaya == 0)
            <div class="bg-white rounded-3xl p-10 text-center shadow-lg shadow-gray-200/50 border border-gray-100">
                <div class="w-20 h-20 bg-green-100 text-green-500 rounded-full flex items-center justify-center mx-auto mb-6">
                    <i data-feather="check" class="w-10 h-10"></i>
                </div>
                <h2 class="text-2xl font-black text-adzkia-dark mb-2">Pembayaran Terverifikasi Otomatis</h2>
                <p class="text-gray-500 font-medium max-w-md mx-auto mb-8">Anda mendaftar melalui jalur <strong>{{ $pendaftar->jalur_pendaftaran }}</strong> yang dibebaskan dari biaya pendaftaran awal.</p>
                <a href="{{ route('pendaftaran.biodata') }}" class="inline-flex items-center gap-2 px-8 py-4 bg-adzkia-blue text-white rounded-xl font-bold hover:bg-blue-700 transition-colors">
                    Lanjut ke Step 4 (Biodata) <i data-feather="arrow-right" class="w-4 h-4"></i>
                </a>
            </div>
        
        {{-- JIKA SUDAH UPLOAD TAPI MASIH MENUNGGU VALIDASI --}}
        {{-- JIKA SUDAH UPLOAD TAPI MASIH MENUNGGU VALIDASI --}}
        @elseif($pendaftar->status_pembayaran === 'Menunggu Validasi')
            <div class="bg-white rounded-3xl p-10 text-center shadow-lg shadow-gray-200/50 border border-gray-100 relative overflow-hidden">
                <div class="absolute top-0 left-0 w-full h-1 bg-amber-400"></div>
                <div class="w-20 h-20 bg-amber-50 text-amber-500 rounded-full flex items-center justify-center mx-auto mb-6">
                    <i data-feather="clock" class="w-10 h-10"></i>
                </div>
                <h2 class="text-2xl font-black text-adzkia-dark mb-2">Bukti Pembayaran Sedang Diproses</h2>
                <p class="text-gray-500 font-medium max-w-md mx-auto mb-8">Terima kasih! Kami telah menerima bukti pembayaran Anda. Tim Keuangan sedang melakukan verifikasi (Step 3) maksimal 1x24 jam.</p>
                
                {{-- TAMBAHAN TOMBOL KEMBALI KE DASHBOARD --}}
                <a href="{{ route('dashboard.user') }}" class="inline-flex items-center gap-2 px-8 py-3.5 bg-gray-100 text-adzkia-dark rounded-xl font-bold hover:bg-gray-200 transition-colors shadow-sm">
                    <i data-feather="arrow-left" class="w-4 h-4"></i> Kembali ke Dasbor Calon Mahasiswa
                </a>
            </div>
            
        {{-- JIKA SUDAH TERVERIFIKASI (REGULER) --}}
        @elseif($pendaftar->status_pembayaran === 'Terverifikasi')
            <div class="bg-white rounded-3xl p-10 text-center shadow-lg shadow-gray-200/50 border border-gray-100">
                <div class="w-20 h-20 bg-green-100 text-green-500 rounded-full flex items-center justify-center mx-auto mb-6">
                    <i data-feather="check-circle" class="w-10 h-10"></i>
                </div>
                <h2 class="text-2xl font-black text-adzkia-dark mb-2">Pembayaran Lunas!</h2>
                <p class="text-gray-500 font-medium max-w-md mx-auto mb-8">Validasi pembayaran berhasil dilakukan. Akun Anda telah aktif.</p>
                <a href="{{ route('pendaftaran.biodata') }}" class="inline-flex items-center gap-2 px-8 py-4 bg-adzkia-blue text-white rounded-xl font-bold hover:bg-blue-700 transition-colors">
                    Lanjut ke Step 3 (Biodata) <i data-feather="arrow-right" class="w-4 h-4"></i>
                </a>
            </div>

        {{-- JIKA BELUM BAYAR (DENGAN PILIHAN METODE) --}}
        @else
            <div x-data="paymentFlow()">
                
                {{-- TAHAP 1: PILIH METODE PEMBAYARAN --}}
                <div x-show="step === 1" x-transition:enter="transition ease-out duration-300" class="bg-white rounded-3xl p-8 shadow-sm border border-gray-100">
                    <h2 class="text-xl font-black text-adzkia-dark mb-2">Pilih Metode Pembayaran</h2>
                    <p class="text-[13px] font-medium text-gray-500 mb-8">Total tagihan pendaftaran Anda adalah <strong class="text-adzkia-dark">Rp {{ number_format($pendaftar->nominal_biaya, 0, ',', '.') }}</strong>. Silakan pilih metode pembayaran yang Anda inginkan.</p>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-8">
                        {{-- Opsi BSI --}}
                        <label class="block p-5 border-2 rounded-2xl cursor-pointer transition-all" :class="selectedBank === 'bsi' ? 'border-adzkia-blue bg-blue-50/30' : 'border-gray-100 hover:border-adzkia-blue'">
                            <input type="radio" name="bank" value="bsi" x-model="selectedBank" class="hidden">
                            <div class="flex flex-col items-center text-center gap-3">
                                <div class="h-12 flex items-center justify-center">
                                    <span class="font-black text-adzkia-dark text-xl tracking-tight">BSI</span>
                                </div>
                                <div>
                                    <h4 class="font-extrabold text-[14px] text-adzkia-dark">Bank Syariah Indonesia</h4>
                                    <p class="text-[11px] text-gray-500 font-bold mt-1">Transfer Bank / m-Banking</p>
                                </div>
                            </div>
                        </label>

                        {{-- Opsi Mandiri --}}
                        <label class="block p-5 border-2 rounded-2xl cursor-pointer transition-all" :class="selectedBank === 'mandiri' ? 'border-adzkia-blue bg-blue-50/30' : 'border-gray-100 hover:border-adzkia-blue'">
                            <input type="radio" name="bank" value="mandiri" x-model="selectedBank" class="hidden">
                            <div class="flex flex-col items-center text-center gap-3">
                                <div class="h-12 flex items-center justify-center">
                                    <span class="font-black text-blue-700 text-xl tracking-tight">MANDIRI</span>
                                </div>
                                <div>
                                    <h4 class="font-extrabold text-[14px] text-adzkia-dark">Bank Mandiri</h4>
                                    <p class="text-[11px] text-gray-500 font-bold mt-1">Transfer Bank / Livin'</p>
                                </div>
                            </div>
                        </label>

                        {{-- Opsi QRIS --}}
                        <label class="block p-5 border-2 rounded-2xl cursor-pointer transition-all" :class="selectedBank === 'qris' ? 'border-adzkia-blue bg-blue-50/30' : 'border-gray-100 hover:border-adzkia-blue'">
                            <input type="radio" name="bank" value="qris" x-model="selectedBank" class="hidden">
                            <div class="flex flex-col items-center text-center gap-3">
                                <div class="h-12 flex items-center justify-center">
                                    <span class="font-black text-red-500 text-xl tracking-tight">QRIS</span>
                                </div>
                                <div>
                                    <h4 class="font-extrabold text-[14px] text-adzkia-dark">E-Wallet & QRIS</h4>
                                    <p class="text-[11px] text-gray-500 font-bold mt-1">Gopay, OVO, Dana, LinkAja</p>
                                </div>
                            </div>
                        </label>
                    </div>

                    <div class="flex justify-end pt-4 border-t border-gray-100">
                        <button type="button" @click="step = 2" :disabled="!selectedBank" :class="selectedBank ? 'bg-adzkia-blue text-white hover:bg-blue-700 shadow-lg shadow-blue-500/30' : 'bg-gray-200 text-gray-400 cursor-not-allowed'" class="px-8 py-4 rounded-xl font-extrabold text-[14px] transition-all flex items-center gap-2">
                            Lanjutkan Pembayaran <i data-feather="arrow-right" class="w-4 h-4"></i>
                        </button>
                    </div>
                </div>

                {{-- TAHAP 2: INSTRUKSI & UPLOAD BUKTI --}}
                <div x-show="step === 2" x-cloak x-transition:enter="transition ease-out duration-300" class="grid grid-cols-1 md:grid-cols-5 gap-8">
                    
                    {{-- KOTAK INFO TRANSFER DINAMIS --}}
                    <div class="md:col-span-2 space-y-6">
                        <div class="bg-adzkia-dark rounded-3xl p-6 text-white relative overflow-hidden h-full">
                            <div class="absolute -right-10 -bottom-10 w-40 h-40 bg-white/5 rounded-full"></div>
                            
                            <button @click="step = 1" class="absolute top-6 right-6 text-white/50 hover:text-white transition-colors" title="Ganti Metode Pembayaran">
                                <i data-feather="edit-2" class="w-4 h-4"></i>
                            </button>

                            <h3 class="text-[12px] font-extrabold text-white/60 uppercase tracking-widest mb-1">Total Tagihan</h3>
                            <div class="text-3xl font-black mb-6">Rp {{ number_format($pendaftar->nominal_biaya, 0, ',', '.') }}</div>
                            
                            <div class="bg-white/10 rounded-2xl p-4 backdrop-blur-sm border border-white/10">
                                <p class="text-[11px] font-bold text-white/60 mb-1">Metode Dipilih:</p>
                                <p class="font-extrabold text-[15px] mb-3" x-text="bankDetails[selectedBank]?.name"></p>
                                
                                <p class="text-[11px] font-bold text-white/60 mb-1" x-text="selectedBank === 'qris' ? 'Instruksi:' : 'Nomor Rekening:'"></p>
                                <div class="flex items-center justify-between bg-white text-adzkia-dark px-3 py-2 rounded-lg font-black tracking-widest text-[15px]">
                                    <span x-text="bankDetails[selectedBank]?.acc"></span>
                                </div>
                                
                                <p class="text-[11px] font-bold text-white/60 mt-3 mb-1">Atas Nama:</p>
                                <p class="font-extrabold text-[14px]" x-text="bankDetails[selectedBank]?.owner"></p>
                            </div>
                        </div>
                    </div>

                    {{-- KOTAK UPLOAD BUKTI --}}
                    <div class="md:col-span-3">
                        <div class="bg-white rounded-3xl p-8 shadow-sm border border-gray-100 h-full">
                            <h3 class="text-xl font-black text-adzkia-dark mb-2">Upload Bukti Pembayaran</h3>
                            <p class="text-[13px] font-medium text-gray-500 mb-6">Silakan unggah foto/screenshot struk bukti transfer Anda.</p>
                            
                            <form action="{{ route('user.upload-bukti') }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                <input type="hidden" name="metode_pembayaran" x-bind:value="selectedBank">
                                
                                <div class="relative w-full h-48 border-2 border-dashed border-gray-300 rounded-2xl hover:border-adzkia-blue hover:bg-blue-50/50 transition-colors flex flex-col items-center justify-center overflow-hidden cursor-pointer" @click="$refs.fileInput.click()">
                                    
                                    <div x-show="!imageUrl" class="flex flex-col items-center justify-center pointer-events-none">
                                        <div class="w-12 h-12 bg-gray-50 text-gray-400 rounded-full flex items-center justify-center mb-3">
                                            <i data-feather="upload-cloud" class="w-6 h-6"></i>
                                        </div>
                                        <p class="text-[13px] font-bold text-adzkia-dark">Klik untuk memilih file</p>
                                        <p class="text-[11px] font-medium text-gray-400 mt-1">Maks 2MB (JPG, JPEG, PNG)</p>
                                    </div>

                                    <img x-show="imageUrl" :src="imageUrl" class="absolute inset-0 w-full h-full object-cover z-10" style="display: none;">
                                    
                                    <div x-show="imageUrl" class="absolute inset-0 bg-black/50 z-20 flex items-center justify-center opacity-0 hover:opacity-100 transition-opacity">
                                        <span class="text-white font-bold text-[12px] px-4 py-2 border border-white rounded-lg backdrop-blur-sm">Ganti Gambar</span>
                                    </div>
                                    
                                    <input type="file" name="bukti_pembayaran" x-ref="fileInput" @change="fileChosen" accept="image/jpeg, image/png, image/jpg" class="hidden">
                                </div>

                                <button type="submit" :disabled="!imageUrl" :class="imageUrl ? 'bg-adzkia-red text-white hover:bg-red-700 shadow-lg shadow-red-500/30' : 'bg-gray-200 text-gray-400 cursor-not-allowed'" class="w-full mt-6 py-4 rounded-xl font-extrabold text-[14px] transition-all flex items-center justify-center gap-2">
                                    Kirim Bukti Pembayaran <i data-feather="send" class="w-4 h-4"></i>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>

            </div>
        @endif

    </main>

    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('paymentFlow', () => ({
                step: 1, 
                selectedBank: '',
                imageUrl: null,
                
                bankDetails: {
                    'bsi': { name: 'Bank Syariah Indonesia (BSI)', acc: '7123 4567 89', owner: 'PMB Universitas Adzkia' },
                    'mandiri': { name: 'Bank Mandiri', acc: '111 222 333 444', owner: 'Universitas Adzkia' },
                    'qris': { name: 'QRIS / E-Wallet', acc: 'Scan Barcode pada Brosur', owner: 'Universitas Adzkia' }
                },

                fileChosen(event) {
                    const file = event.target.files[0];
                    if (!file) return;
                    
                    const reader = new FileReader();
                    reader.onload = (e) => {
                        this.imageUrl = e.target.result;
                    };
                    reader.readAsDataURL(file);
                }
            }));
        });
        
        // Memastikan Feather icons dimuat ulang ketika AlpineJS merender bagian baru
        document.addEventListener('alpine:initialized', () => {
            feather.replace();
        });
    </script>
</body>
</html>