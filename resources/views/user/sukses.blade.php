<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pendaftaran Berhasil - SPMB Adzkia</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <script src="https://unpkg.com/feather-icons"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: { sans: ['Manrope', 'sans-serif'] },
                    colors: {
                        'brand-bg': '#F8FAFC',
                        'brand-dark': '#0F172A',
                        'brand-gray': '#64748B',
                        'brand-blue': '#2563EB',
                        'brand-blue-light': '#EFF6FF',
                    }
                }
            }
        }
    </script>
</head>
<body class="bg-brand-bg antialiased text-brand-dark min-h-screen flex flex-col" x-data="suksesApp()">

    <nav class="w-full bg-white py-8 border-b border-gray-100 sticky top-0 z-50">
        <div class="max-w-5xl mx-auto px-6">
            <div class="flex items-center justify-between relative">
                <div class="absolute top-5 left-0 w-full h-0.5 bg-brand-dark z-0"></div>
                
                <template x-for="step in steps" :key="step.id">
                    <div class="relative z-10 flex flex-col items-center gap-3 w-20">
                        <div class="w-10 h-10 rounded-full flex items-center justify-center font-extrabold text-[13px] bg-brand-dark text-white transition-all duration-300 shadow-sm"
                             :class="step.id === 7 ? 'bg-green-500 shadow-green-500/30 scale-110 ring-4 ring-green-100' : ''">
                            <i data-feather="check" class="w-5 h-5"></i>
                        </div>
                        <span class="text-[9px] font-black uppercase tracking-widest text-center text-brand-dark" x-text="step.title"></span>
                    </div>
                </template>
            </div>
        </div>
    </nav>

    <main class="flex-1 max-w-3xl mx-auto w-full px-6 py-12 flex flex-col justify-center">
        <div class="bg-white p-10 md:p-14 rounded-[3rem] shadow-xl shadow-brand-dark/5 border border-gray-100 text-center relative overflow-hidden">
            
            <div class="absolute top-0 right-0 p-8 opacity-5 pointer-events-none">
                <i data-feather="award" class="w-48 h-48"></i>
            </div>

            <div class="w-20 h-20 bg-green-50 text-green-500 rounded-full flex items-center justify-center mx-auto mb-6 shadow-sm ring-8 ring-green-50/50">
                <i data-feather="check-circle" class="w-10 h-10"></i>
            </div>
            
            <h1 class="text-3xl md:text-4xl font-black text-brand-dark tracking-tight mb-4 relative z-10">Pendaftaran Berhasil!</h1>
            <p class="text-[14px] font-medium text-gray-500 leading-relaxed max-w-md mx-auto mb-10 relative z-10">
                Selamat! Anda telah menyelesaikan seluruh proses pendaftaran di Universitas Adzkia.
            </p>

<div class="bg-[#F8FAFC] rounded-3xl p-6 md:p-8 mb-10 border border-gray-100 grid grid-cols-2 gap-y-6 gap-x-4 text-left relative z-10">
    <div>
        <p class="text-[9px] font-black text-gray-400 uppercase tracking-widest mb-1.5">Nama Pendaftar</p>
        <h4 class="text-[14px] md:text-[16px] font-extrabold text-brand-dark">{{ $pendaftar->nama_lengkap }}</h4>
    </div>
    <div>
        <p class="text-[9px] font-black text-gray-400 uppercase tracking-widest mb-1.5">Program Studi</p>
        <h4 class="text-[14px] md:text-[16px] font-extrabold text-brand-dark">{{ $pendaftar->pilihan_jurusan_1 }}</h4>
    </div>
    <div>
        <p class="text-[9px] font-black text-gray-400 uppercase tracking-widest mb-1.5">Nomor Pendaftaran</p>
        <h4 class="text-[15px] md:text-[18px] font-black text-brand-blue tracking-wider">{{ $pendaftar->no_pendaftaran }}</h4>
    </div>
    <div>
        <p class="text-[9px] font-black text-gray-400 uppercase tracking-widest mb-1.5">Tanggal Selesai</p>
        <h4 class="text-[14px] md:text-[16px] font-extrabold text-brand-dark">
            {{ \Carbon\Carbon::parse($pendaftar->updated_at)->format('d F Y') }}
        </h4>
    </div>
</div>

            <div class="mb-12 relative z-10">
                <div class="flex items-center justify-center gap-4 mb-6">
                    <div class="h-px bg-gray-200 flex-1"></div>
                    <span class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Langkah Selanjutnya</span>
                    <div class="h-px bg-gray-200 flex-1"></div>
                </div>
                
                <div class="space-y-4 max-w-md mx-auto text-left">
                    <div class="flex items-start gap-3">
                        <i data-feather="mail" class="w-5 h-5 text-gray-400 shrink-0"></i>
                        <p class="text-[13px] font-bold text-gray-600">Silakan cek email untuk informasi selanjutnya.</p>
                    </div>
                    <div class="flex items-start gap-3">
                        <i data-feather="layout" class="w-5 h-5 text-gray-400 shrink-0"></i>
                        <p class="text-[13px] font-bold text-gray-600">Pantau dashboard untuk update terbaru.</p>
                    </div>
                    <div class="flex items-start gap-3">
                        <i data-feather="calendar" class="w-5 h-5 text-gray-400 shrink-0"></i>
                        <p class="text-[13px] font-bold text-gray-600">Ikuti jadwal seleksi / pengumuman.</p>
                    </div>
                </div>
            </div>

            <p class="text-[15px] font-black text-brand-dark italic mb-8">"Selamat bergabung bersama kami!"</p>

            <div class="flex flex-col items-center gap-4 relative z-10">
                <button onclick="window.location.href='/'" class="w-full py-4 bg-brand-dark text-white rounded-2xl font-black text-[15px] hover:bg-brand-blue shadow-lg shadow-brand-dark/20 transition-all active:scale-[0.98]">
                    Ke Dashboard
                </button>
                <button class="flex items-center gap-2 text-[13px] font-extrabold text-brand-blue hover:text-brand-dark transition-colors py-2">
                    <i data-feather="download" class="w-4 h-4"></i> Download Bukti Pendaftaran
                </button>
            </div>

        </div>
    </main>

    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('suksesApp', () => ({
                currentStep: 7,
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