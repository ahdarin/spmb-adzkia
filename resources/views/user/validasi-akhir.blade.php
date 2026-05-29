<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Validasi Daftar Ulang - SPMB Adzkia</title>
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
<body class="bg-adzkia-bg antialiased text-adzkia-dark min-h-screen flex flex-col" x-data="validasiApp()">

    <nav class="w-full bg-white py-8 border-b border-gray-100 sticky top-0 z-50">
        <div class="max-w-5xl mx-auto px-6">
            <div class="flex items-center justify-between relative">
                <div class="absolute top-5 left-0 w-full h-0.5 bg-gray-100 z-0"></div>
                <div class="absolute top-5 left-0 h-0.5 bg-adzkia-blue z-0 transition-all duration-500" style="width: 83.33%;"></div>
                
                <template x-for="step in steps" :key="step.id">
                    <div class="relative z-10 flex flex-col items-center gap-3 w-20">
                        <div class="w-10 h-10 rounded-full flex items-center justify-center font-extrabold text-[13px] transition-all duration-300"
                             :class="{
                                 'bg-adzkia-blue text-white': step.id < currentStep,
                                 'bg-adzkia-red text-white shadow-lg shadow-red-600/30 scale-110 ring-4 ring-red-50': step.id === currentStep,
                                 'bg-white border-2 border-gray-200 text-gray-300': step.id > currentStep
                             }">
                            <template x-if="step.id < currentStep"><i data-feather="check" class="w-5 h-5"></i></template>
                            <template x-if="step.id >= currentStep"><span x-text="step.id"></span></template>
                        </div>
                        <span class="text-[9px] font-black uppercase tracking-widest text-center"
                              :class="step.id === currentStep ? 'text-adzkia-blue' : 'text-gray-400'"
                              x-text="step.title"></span>
                    </div>
                </template>
            </div>
        </div>
    </nav>

    <main class="flex-1 max-w-3xl mx-auto w-full px-6 py-12">
        <div class="bg-white p-8 md:p-12 rounded-[2.5rem] shadow-xl shadow-adzkia-dark/5 border border-gray-100">
            
            <div class="flex flex-col items-center text-center mb-10 border-b border-gray-50 pb-8">
                <div class="w-16 h-16 bg-adzkia-badge-bg text-adzkia-blue rounded-full flex items-center justify-center mb-4 animate-pulse">
                    <i data-feather="hourglass" class="w-8 h-8"></i>
                </div>
                <h1 class="text-3xl font-black text-adzkia-blue tracking-tight mb-3">Validasi Daftar Ulang</h1>
                <span class="px-4 py-1.5 bg-blue-50 text-adzkia-blue rounded-full text-[11px] font-black uppercase tracking-widest border border-blue-100">
                    Sedang Diverifikasi
                </span>
            </div>

            <div class="mb-10 px-4 md:px-10">
                <div class="relative space-y-6 before:absolute before:inset-0 before:ml-2.5 before:-translate-x-px md:before:mx-auto md:before:translate-x-0 before:h-full before:w-0.5 before:bg-gradient-to-b before:from-transparent before:via-gray-200 before:to-transparent">
                    
                    <div class="relative flex items-center justify-between md:justify-normal md:odd:flex-row-reverse group is-active">
                        <div class="flex items-center justify-center w-6 h-6 rounded-full border-2 border-white bg-adzkia-blue text-white shadow shrink-0 md:order-1 md:group-odd:-translate-x-1/2 md:group-even:translate-x-1/2 z-10">
                            <i data-feather="check" class="w-3 h-3"></i>
                        </div>
                        <div class="w-[calc(100%-3rem)] md:w-[calc(50%-1.5rem)] md:group-odd:text-right font-extrabold text-[14px] text-adzkia-dark">Data Dikirim</div>
                    </div>
                    
                    <div class="relative flex items-center justify-between md:justify-normal md:odd:flex-row-reverse group is-active">
                        <div class="flex items-center justify-center w-6 h-6 rounded-full border-2 border-white bg-adzkia-blue text-white shadow shrink-0 md:order-1 md:group-odd:-translate-x-1/2 md:group-even:translate-x-1/2 z-10">
                            <i data-feather="check" class="w-3 h-3"></i>
                        </div>
                        <div class="w-[calc(100%-3rem)] md:w-[calc(50%-1.5rem)] md:group-odd:text-right font-extrabold text-[14px] text-adzkia-dark">Menunggu Verifikasi</div>
                    </div>

                    <div class="relative flex items-center justify-between md:justify-normal md:odd:flex-row-reverse group {{ in_array($pendaftar->status_pendaftaran, ['Sedang Diproses', 'Selesai']) ? 'is-active' : '' }}">
                        <div class="flex items-center justify-center w-6 h-6 rounded-full border-2 border-white 
                            {{ $pendaftar->status_pendaftaran == 'Sedang Diproses' ? 'bg-adzkia-red ring-4 ring-red-100' : 'bg-adzkia-blue' }} 
                            text-white shadow shrink-0 md:order-1 md:group-odd:-translate-x-1/2 md:group-even:translate-x-1/2 z-10">
                            
                            @if($pendaftar->status_pendaftaran == 'Sedang Diproses')
                                <i data-feather="loader" class="w-3 h-3 animate-spin"></i>
                            @else
                                <i data-feather="check" class="w-3 h-3"></i>
                            @endif
                        </div>
                        <div class="w-[calc(100%-3rem)] md:w-[calc(50%-1.5rem)] md:group-odd:text-right font-extrabold text-[14px] 
                            {{ $pendaftar->status_pendaftaran == 'Sedang Diproses' ? 'text-adzkia-red' : 'text-adzkia-dark' }}">Sedang Diproses</div>
                    </div>

                    <div class="relative flex items-center justify-between md:justify-normal md:odd:flex-row-reverse group {{ $pendaftar->status_pendaftaran == 'Selesai' ? 'is-active' : '' }}">
                        <div class="flex items-center justify-center w-6 h-6 rounded-full border-2 border-white 
                            {{ $pendaftar->status_pendaftaran == 'Selesai' ? 'bg-adzkia-blue' : 'bg-white border-gray-200' }} 
                            text-white shadow shrink-0 md:order-1 md:group-odd:-translate-x-1/2 md:group-even:translate-x-1/2 z-10">
                            
                            @if($pendaftar->status_pendaftaran == 'Selesai')
                                <i data-feather="check" class="w-3 h-3"></i>
                            @endif
                        </div>
                        <div class="w-[calc(100%-3rem)] md:w-[calc(50%-1.5rem)] md:group-odd:text-right font-extrabold text-[14px] 
                            {{ $pendaftar->status_pendaftaran == 'Selesai' ? 'text-adzkia-blue' : 'text-gray-400' }}">
                            Selesai
                        </div>
                    </div>

                </div>
            </div>

            <div class="bg-gray-50 rounded-3xl p-6 md:p-8 mb-6 border border-gray-100 grid grid-cols-2 gap-y-6 gap-x-4">
                <div>
                    <p class="text-[9px] font-black text-gray-400 uppercase tracking-widest mb-1.5">Nama Pendaftar</p>
                    <h4 class="text-[14px] font-extrabold text-adzkia-dark">{{ $pendaftar->nama_lengkap }}</h4>
                </div>
                <div>
                    <p class="text-[9px] font-black text-gray-400 uppercase tracking-widest mb-1.5">Program Studi</p>
                    <h4 class="text-[14px] font-extrabold text-adzkia-dark">{{ $pendaftar->pilihan_jurusan_1 }}</h4>
                </div>
                <div>
                    <p class="text-[9px] font-black text-gray-400 uppercase tracking-widest mb-1.5">Tanggal Kirim</p>
                    <h4 class="text-[14px] font-extrabold text-adzkia-dark">{{ $pendaftar->updated_at->format('d F Y') }}</h4>
                </div>
                <div>
                    <p class="text-[9px] font-black text-gray-400 uppercase tracking-widest mb-1.5">Estimasi Verifikasi</p>
                    <h4 class="text-[14px] font-extrabold text-adzkia-dark">1x24 jam</h4>
                </div>
            </div>

            <div class="bg-blue-50/50 border border-blue-100 rounded-2xl p-5 flex gap-4 items-start mb-8">
                <i data-feather="message-square" class="w-5 h-5 text-adzkia-blue shrink-0 mt-0.5"></i>
                <p class="text-[13px] font-medium text-gray-600 leading-relaxed">
                    Data Anda sedang diperiksa oleh admin. Mohon menunggu proses validasi berkas administrasi Anda.
                </p>
            </div>

            <div class="flex flex-col items-center gap-4">
                <button onclick="window.location.href='/sukses'" class="w-full py-4 bg-adzkia-blue text-white rounded-2xl font-black text-[15px] hover:bg-blue-700 shadow-lg shadow-blue-600/20 transition-all active:scale-[0.98]">
                    Kembali ke Dashboard
                </button>
                <button onclick="window.location.reload()" class="text-[13px] font-extrabold text-gray-500 hover:text-adzkia-dark transition-colors py-2">
                    Muat Ulang Halaman
                </button>
                <button class="text-[11px] font-extrabold text-adzkia-blue hover:text-adzkia-red transition-colors pt-2">
                    Butuh bantuan? Hubungi Admin
                </button>
            </div>

        </div>
    </main>

    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('validasiApp', () => ({
                currentStep: 6,
                steps: [
                    { id: 1, title: 'Pendaftaran' }, { id: 2, title: 'Biaya Pendaftaran' },
                    { id: 3, title: 'Validasi Pembayaran' }, { id: 4, title: 'Biodata' },
                    { id: 5, title: 'Daftar Ulang' }, { id: 6, title: 'Validasi' },
                    { id: 7, title: 'Selesai' }
                ]
            }));
        });
        document.addEventListener('DOMContentLoaded', () => { feather.replace(); });
    </script>
</body>
</html>