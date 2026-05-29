<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Konfirmasi Data - SPMB Adzkia</title>
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
                        'adzkia-badge-txt': '#2c7ebd',
                        'adzkia-bg': '#FAFBFC',
                    }
                }
            }
        }
    </script>
    <style>
        [x-cloak] { display: none !important; }
    </style>
</head>
<body class="bg-adzkia-bg antialiased text-adzkia-dark min-h-screen flex flex-col" x-data="konfirmasiApp()">

    <nav class="w-full bg-white py-8 border-b border-gray-100 sticky top-0 z-50">
        <div class="max-w-5xl mx-auto px-6">
            <div class="flex items-center justify-between relative">
                <div class="absolute top-5 left-0 w-full h-0.5 bg-gray-100 z-0"></div>
                <div class="absolute top-5 left-0 h-0.5 bg-adzkia-blue z-0 transition-all duration-500" style="width: 66.66%;"></div>
                
                <template x-for="step in steps" :key="step.id">
                    <div class="relative z-10 flex flex-col items-center gap-3 w-20">
                        <div class="w-10 h-10 rounded-full flex items-center justify-center font-extrabold text-[13px] transition-all duration-300"
                             :class="{
                                 'bg-adzkia-blue text-white': step.id < currentStep,
                                 'bg-adzkia-red text-white shadow-lg shadow-red-600/30 scale-110 ring-4 ring-red-50': step.id === currentStep,
                                 'bg-white border-2 border-gray-200 text-gray-300': step.id > currentStep
                             }">
                            <template x-if="step.id < currentStep">
                                <i data-feather="check" class="w-5 h-5"></i>
                            </template>
                            <template x-if="step.id >= currentStep">
                                <span x-text="step.id"></span>
                            </template>
                        </div>
                        <span class="text-[9px] font-black uppercase tracking-widest text-center"
                              :class="step.id === currentStep ? 'text-adzkia-blue' : 'text-gray-400'"
                              x-text="step.title"></span>
                    </div>
                </template>
            </div>
        </div>
    </nav>

    <main class="flex-1 max-w-6xl mx-auto w-full px-6 py-12">
        
        <div class="mb-10 text-center md:text-left">
            <h1 class="text-3xl md:text-4xl font-black text-adzkia-blue tracking-tight mb-3">Konfirmasi Data Pendaftaran</h1>
            <p class="text-[15px] font-medium text-gray-500 leading-relaxed">
                Periksa kembali data Anda sebelum melanjutkan ke tahap seleksi akhir.
            </p>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
            
            <div class="lg:col-span-4 space-y-6">
                <div class="bg-white rounded-[2rem] p-8 border border-gray-100 shadow-sm flex flex-col items-center text-center">
                    
                    <img src="{{ $pendaftar->pas_foto ? asset('storage/' . $pendaftar->pas_foto) : 'https://ui-avatars.com/api/?name=' . urlencode($pendaftar->nama_lengkap) . '&background=F1F5F9&color=1e293b&size=128' }}" 
                         alt="Foto Profil" class="w-24 h-24 rounded-2xl mb-4 shadow-sm border border-gray-100 object-cover">
                     
                    <h2 class="text-2xl font-black text-adzkia-dark mb-1">{{ $pendaftar->nama_lengkap }}</h2>
                    <p class="text-[12px] font-bold text-gray-400 uppercase tracking-widest mb-6">No. Registrasi: #{{ $pendaftar->no_pendaftaran ?? $pendaftar->id }}</p>
    
                    <div class="w-full bg-adzkia-blue rounded-2xl p-6 relative overflow-hidden shadow-lg shadow-adzkia-blue/20">
                        <div class="absolute top-0 right-0 p-4 opacity-10">
                            <i data-feather="book-open" class="w-20 h-20 text-white"></i>
                        </div>
                        
                        <p class="text-[10px] font-black text-blue-100 uppercase tracking-widest mb-1 relative z-10 text-left">Pilihan Jurusan 1</p>
                        <h3 class="text-lg font-extrabold text-white relative z-10 text-left mb-4 leading-tight">{{ $pendaftar->pilihan_jurusan_1 ?? '-' }}</h3>
                        
                        <p class="text-[10px] font-black text-blue-100 uppercase tracking-widest mb-1 relative z-10 text-left">Pilihan Jurusan 2</p>
                        <h3 class="text-lg font-extrabold text-white relative z-10 text-left leading-tight">{{ $pendaftar->pilihan_jurusan_2 ?? '-' }}</h3>
                    </div>
                </div>

                <div class="flex gap-4 border-l-4 border-adzkia-red bg-red-50/50 rounded-r-xl pl-5 pr-4 py-3">
                    <i data-feather="info" class="w-5 h-5 text-adzkia-red shrink-0 mt-0.5"></i>
                    <div>
                        <h4 class="text-[14px] font-extrabold text-adzkia-red mb-1">Penting</h4>
                        <p class="text-[12px] font-medium text-red-900/80 leading-relaxed">Data tidak dapat diubah setelah tahap ini. Pastikan semua informasi sudah benar sebelum melakukan konfirmasi akhir.</p>
                    </div>
                </div>
            </div>

            <div class="lg:col-span-8 space-y-8">
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    
                    <div class="bg-white rounded-[2rem] p-8 border border-gray-100 shadow-sm relative group">
                        <a href="{{ route('pendaftaran.biodata') }}" 
                            class="absolute top-8 right-8 text-[12px] font-extrabold text-gray-400 underline underline-offset-2 hover:text-adzkia-red transition-colors">
                            Edit
                        </a>
                        <div class="flex items-center gap-2 mb-6">
                            <i data-feather="user" class="w-4 h-4 text-adzkia-blue"></i>
                            <h3 class="text-[15px] font-extrabold text-adzkia-dark">Data Diri</h3>
                        </div>
                        <div class="space-y-4">
                            <div>
                                <p class="text-[9px] font-black text-gray-400 uppercase tracking-widest mb-1">Nama Lengkap</p>
                                <p class="text-[14px] font-bold text-adzkia-dark">{{ $pendaftar->nama_lengkap }}</p>
                            </div>
                            <div>
                                <p class="text-[9px] font-black text-gray-400 uppercase tracking-widest mb-1">NIK</p>
                                <p class="text-[14px] font-bold text-adzkia-dark">{{ $pendaftar->nik }}</p>
                            </div>
                            <div class="flex gap-8">
                                <div>
                                    <p class="text-[9px] font-black text-gray-400 uppercase tracking-widest mb-1">Tanggal Lahir</p>
                                    <p class="text-[14px] font-bold text-adzkia-dark">
                                        {{ $pendaftar->tanggal_lahir ? \Carbon\Carbon::parse($pendaftar->tanggal_lahir)->translatedFormat('d F Y') : '-' }}
                                    </p>
                                </div>
                                <div>
                                    <div>
                                        <p class="text-[9px] font-black text-gray-400 uppercase tracking-widest mb-1">Gender</p>
                                        <p class="text-[14px] font-bold text-adzkia-dark">{{ $pendaftar->gender ?? '-' }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white rounded-[2rem] p-8 border border-gray-100 shadow-sm relative group">
                        <a href="{{ route('pendaftaran.biodata') }}" 
                            class="absolute top-8 right-8 text-[12px] font-extrabold text-gray-400 underline underline-offset-2 hover:text-adzkia-red transition-colors">
                            Edit
                        </a>
                        <div class="flex items-center gap-2 mb-6">
                            <i data-feather="map-pin" class="w-4 h-4 text-adzkia-blue"></i>
                            <h3 class="text-[15px] font-extrabold text-adzkia-dark">Kontak</h3>
                        </div>
                        <div class="space-y-4">
                            <div>
                                <p class="text-[9px] font-black text-gray-400 uppercase tracking-widest mb-1">Email</p>
                                <p class="text-[14px] font-bold text-adzkia-dark">{{ $pendaftar->email }}</p>
                            </div>
                            <div>
                                <p class="text-[9px] font-black text-gray-400 uppercase tracking-widest mb-1">No HP</p>
                                <p class="text-[14px] font-bold text-adzkia-dark">{{ $pendaftar->no_whatsapp ?? '-' }}</p>
                            </div>
                            <div>
                                <p class="text-[9px] font-black text-gray-400 uppercase tracking-widest mb-1">Alamat</p>
                                <p class="text-[14px] font-bold text-adzkia-dark leading-snug">{{ $pendaftar->alamat_rumah ?? '-' }}</p>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white rounded-[2rem] p-8 border border-gray-100 shadow-sm relative group md:col-span-2 lg:col-span-1">
                        <a href="{{ route('pendaftaran.biodata') }}" 
                            class="absolute top-8 right-8 text-[12px] font-extrabold text-gray-400 underline underline-offset-2 hover:text-adzkia-red transition-colors">
                            Edit
                        </a>
                        <div class="flex items-center gap-2 mb-6">
                            <i data-feather="book-open" class="w-4 h-4 text-adzkia-blue"></i>
                            <h3 class="text-[15px] font-extrabold text-adzkia-dark">Pendidikan</h3>
                        </div>
                        <div class="space-y-4">
                            <div>
                                <p class="text-[9px] font-black text-gray-400 uppercase tracking-widest mb-1">Sekolah Asal</p>
                                <p class="text-[14px] font-bold text-adzkia-dark">{{ $pendaftar->sekolah_asal ?? '-' }}</p>
                            </div>
                            <div class="flex gap-8">
                                <div>
                                    <p class="text-[9px] font-black text-gray-400 uppercase tracking-widest mb-1">Tahun Lulus</p>
                                    <p class="text-[14px] font-bold text-adzkia-dark">{{ $pendaftar->tahun_lulus ?? '-' }}</p>
                                </div>
                                <div>
                                    <p class="text-[9px] font-black text-gray-400 uppercase tracking-widest mb-1">Nilai Akhir</p>
                                    <p class="text-[14px] font-bold text-adzkia-dark">{{ $pendaftar->nilai_akhir ?? '-' }}</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white rounded-[2rem] p-8 border border-gray-100 shadow-sm relative group md:col-span-2 lg:col-span-1">
                        <p class="text-[9px] font-black text-gray-400 uppercase tracking-widest mb-4">ID Database: {{ $pendaftar->id }}</p>
                        
                        <a href="{{ route('pendaftaran.biodata') }}" 
                            class="absolute top-8 right-8 text-[12px] font-extrabold text-gray-400 underline underline-offset-2 hover:text-adzkia-red transition-colors">
                            Edit
                        </a>
                        <div class="flex items-center gap-2 mb-6">
                            <i data-feather="folder" class="w-4 h-4 text-adzkia-blue"></i>
                            <h3 class="text-[15px] font-extrabold text-adzkia-dark">Dokumen</h3>
                        </div>
                        
                        <div class="flex gap-4">
                            <!-- PAS FOTO -->
                            @if(!empty($pendaftar->pas_foto))
                                <a href="{{ asset('storage/' . $pendaftar->pas_foto) }}" target="_blank" class="flex flex-col items-center gap-2 group/doc">
                                    <div class="w-14 h-14 bg-blue-50 rounded-xl flex items-center justify-center text-adzkia-blue border border-blue-200 group-hover/doc:bg-adzkia-blue group-hover/doc:text-white transition-colors relative">
                                        <i data-feather="image" class="w-5 h-5"></i>
                                        <div class="absolute -top-1.5 -right-1.5 bg-green-500 rounded-full border-2 border-white text-white p-0.5 shadow-sm">
                                            <i data-feather="check" class="w-2.5 h-2.5"></i>
                                        </div>
                                    </div>
                                    <p class="text-[9px] font-bold text-adzkia-blue group-hover/doc:text-adzkia-dark transition-colors">Lihat Foto</p>
                                </a>
                            @else
                                <div class="flex flex-col items-center gap-2 opacity-60">
                                    <div class="w-14 h-14 bg-gray-50 rounded-xl flex items-center justify-center text-gray-400 border border-gray-200">
                                        <i data-feather="image" class="w-5 h-5"></i>
                                    </div>
                                    <p class="text-[9px] font-extrabold text-red-500">Kosong</p>
                                </div>
                            @endif

                            <!-- KTP -->
                            @if(!empty($pendaftar->scan_ktp))
                                <a href="{{ asset('storage/' . $pendaftar->scan_ktp) }}" target="_blank" class="flex flex-col items-center gap-2 group/doc">
                                    <div class="w-14 h-14 bg-blue-50 rounded-xl flex items-center justify-center text-adzkia-blue border border-blue-200 group-hover/doc:bg-adzkia-blue group-hover/doc:text-white transition-colors relative">
                                        <i data-feather="file-text" class="w-5 h-5"></i>
                                        <div class="absolute -top-1.5 -right-1.5 bg-green-500 rounded-full border-2 border-white text-white p-0.5 shadow-sm">
                                            <i data-feather="check" class="w-2.5 h-2.5"></i>
                                        </div>
                                    </div>
                                    <p class="text-[9px] font-bold text-adzkia-blue group-hover/doc:text-adzkia-dark transition-colors">Lihat KTP</p>
                                </a>
                            @else
                                <div class="flex flex-col items-center gap-2 opacity-60">
                                    <div class="w-14 h-14 bg-gray-50 rounded-xl flex items-center justify-center text-gray-400 border border-gray-200">
                                        <i data-feather="file-text" class="w-5 h-5"></i>
                                    </div>
                                    <p class="text-[9px] font-extrabold text-red-500">Kosong</p>
                                </div>
                            @endif

                            <!-- IJAZAH -->
                            @if(!empty($pendaftar->ijazah_skl))
                                <a href="{{ asset('storage/' . $pendaftar->ijazah_skl) }}" target="_blank" class="flex flex-col items-center gap-2 group/doc">
                                    <div class="w-14 h-14 bg-blue-50 rounded-xl flex items-center justify-center text-adzkia-blue border border-blue-200 group-hover/doc:bg-adzkia-blue group-hover/doc:text-white transition-colors relative">
                                        <i data-feather="award" class="w-5 h-5"></i>
                                        <div class="absolute -top-1.5 -right-1.5 bg-green-500 rounded-full border-2 border-white text-white p-0.5 shadow-sm">
                                            <i data-feather="check" class="w-2.5 h-2.5"></i>
                                        </div>
                                    </div>
                                    <p class="text-[9px] font-bold text-adzkia-blue group-hover/doc:text-adzkia-dark transition-colors">Lihat Ijazah</p>
                                </a>
                            @else
                                <div class="flex flex-col items-center gap-2 opacity-60">
                                    <div class="w-14 h-14 bg-gray-50 rounded-xl flex items-center justify-center text-gray-400 border border-gray-200">
                                        <i data-feather="award" class="w-5 h-5"></i>
                                    </div>
                                    <p class="text-[9px] font-extrabold text-red-500">Kosong</p>
                                </div>
                            @endif
                        </div>
                    </div>

                </div>

                <div class="bg-white rounded-3xl p-8 border border-gray-100 shadow-sm space-y-4">
                    <label class="flex items-start gap-4 cursor-pointer group">
                        <div class="w-6 h-6 rounded flex items-center justify-center transition-colors shrink-0 mt-0.5 border-2"
                             :class="agreements.dataCorrect ? 'bg-adzkia-blue border-adzkia-blue' : 'bg-white border-gray-300 group-hover:border-adzkia-blue'">
                            <i data-feather="check" class="w-4 h-4 text-white" x-show="agreements.dataCorrect" x-cloak></i>
                            <input type="checkbox" x-model="agreements.dataCorrect" class="sr-only">
                        </div>
                        <span class="text-[14px] font-medium text-gray-600 leading-relaxed select-none group-hover:text-adzkia-dark transition-colors">
                            Saya menyatakan bahwa seluruh data yang saya isi di atas adalah benar dan sesuai dengan dokumen aslinya.
                        </span>
                    </label>

                    <label class="flex items-start gap-4 cursor-pointer group">
                        <div class="w-6 h-6 rounded flex items-center justify-center transition-colors shrink-0 mt-0.5 border-2"
                             :class="agreements.termsRead ? 'bg-adzkia-blue border-adzkia-blue' : 'bg-white border-gray-300 group-hover:border-adzkia-blue'">
                            <i data-feather="check" class="w-4 h-4 text-white" x-show="agreements.termsRead" x-cloak></i>
                            <input type="checkbox" x-model="agreements.termsRead" class="sr-only">
                        </div>
                        <span class="text-[14px] font-medium text-gray-600 leading-relaxed select-none group-hover:text-adzkia-dark transition-colors">
                            Saya telah membaca dan menyetujui seluruh <span class="font-extrabold text-adzkia-dark">syarat & ketentuan</span> pendaftaran mahasiswa baru.
                        </span>
                    </label>
                </div>

                <div class="flex flex-col items-center gap-4 pt-4">
                    <form action="{{ route('proses.konfirmasi', $pendaftar->id) }}" method="POST" class="w-full">
                        @csrf
                        <button type="submit"
                                :disabled="!canProceed"
                                class="w-full py-4 rounded-2xl font-black text-[15px] transition-all"
                                :class="canProceed ? 'bg-adzkia-blue text-white hover:bg-blue-700 shadow-xl shadow-blue-600/20 active:scale-[0.98]' : 'bg-gray-200 text-gray-400 cursor-not-allowed'">
                            Konfirmasi & Kirim Data
                        </button>
                    </form>
                    
                    <a href="{{ route('pendaftaran.biodata') }}" class="text-[13px] font-extrabold text-gray-500 hover:text-adzkia-blue transition-colors py-2">
                        Kembali Edit Formulir
                    </a>
                </div>
            </div>
        </div>
    </main>

    <footer class="w-full bg-adzkia-bg py-8 flex flex-col md:flex-row justify-center md:justify-between items-center gap-4 px-10 border-t border-gray-100">
        <p class="text-[11px] font-bold text-gray-400">© 2026 Universitas Adzkia. All Rights Reserved.</p>
        <div class="flex gap-8 text-[11px] font-bold text-gray-500">
            <a href="#" class="hover:text-adzkia-blue transition-colors">Privacy Policy</a>
            <a href="#" class="hover:text-adzkia-blue transition-colors">Contact Support</a>
            <a href="#" class="hover:text-adzkia-blue transition-colors">Terms of Service</a>
        </div>
    </footer>

    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('konfirmasiApp', () => ({
                currentStep: 5,
                
                agreements: {
                    dataCorrect: false,
                    termsRead: false
                },
                
                steps: [
                    { id: 1, title: 'Pendaftaran' },
                    { id: 2, title: 'Biaya Pendaftaran' },
                    { id: 3, title: 'Validasi Pembayaran' },
                    { id: 4, title: 'Biodata' },
                    { id: 5, title: 'Daftar Ulang' },
                    { id: 6, title: 'Validasi' },
                    { id: 7, title: 'Selesai' }
                ],

                get canProceed() {
                    return this.agreements.dataCorrect && this.agreements.termsRead;
                }
            }));
        });

        document.addEventListener('DOMContentLoaded', () => {
            feather.replace();
        });
    </script>
</body>
</html>