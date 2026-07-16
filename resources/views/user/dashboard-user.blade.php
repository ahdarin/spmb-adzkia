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

    {{-- NAVBAR --}}
    <nav class="w-full bg-white py-3 md:py-5 px-4 sm:px-6 md:px-16 border-b border-gray-100 flex items-center justify-between sticky top-0 z-50 shadow-sm">
        <div class="flex items-center gap-2 sm:gap-3">
            <img src="{{ asset('images/logo-adzkia.png') }}" alt="Logo Adzkia" class="h-8 sm:h-10 w-auto">
            <div class="flex flex-col">
                <span class="text-sm sm:text-md font-black text-adzkia-blue leading-none">PORTAL PENDAFTAR</span>
                <span class="text-[10px] sm:text-xs font-bold text-adzkia-red">Universitas Adzkia</span>
            </div>
        </div>
        <div class="flex items-center gap-2 sm:gap-4">
            <div class="text-right hidden sm:block">
                <p class="text-xs font-black text-adzkia-dark leading-none">{{ session('nama_pendaftar') ?? $pendaftar->nama_lengkap }}</p>
                <p class="text-[10px] font-bold text-gray-400 mt-1 uppercase tracking-widest">No: {{ $pendaftar->no_pendaftaran ?? '-' }}</p>
            </div>
            <form action="{{ route('logout') }}" method="POST" class="inline">
                @csrf
                <button type="submit" class="p-2 sm:p-2.5 bg-gray-50 text-gray-400 hover:text-adzkia-red rounded-xl hover:bg-red-50 transition-all">
                    <i data-feather="log-out" class="w-4 h-4"></i>
                </button>
            </form>
        </div>
    </nav>

    {{-- MAIN CONTENT --}}
    {{-- Ubah: grid 12-col diganti gap lebih kecil di mobile, padding berkurang --}}
    <main class="flex-1 max-w-6xl mx-auto w-full px-4 sm:px-6 py-6 sm:py-10 lg:py-12 grid grid-cols-1 lg:grid-cols-12 gap-6 lg:gap-8 items-start">
        
        {{-- KOLOM KIRI (konten utama) --}}
        {{-- Ubah: di mobile full width, di lg jadi 8 kolom --}}
        <div class="lg:col-span-8 space-y-5 sm:space-y-8">
            
            {{-- BLOK STATUS UTAMA --}}
            @if(!empty($pendaftar->nim) && strtolower($pendaftar->status_daftar_ulang ?? '') === 'selesai')
                {{-- MAHASISWA RESMI: daftar ulang selesai + NIM sudah ada --}}
                <div class="bg-gradient-to-r from-emerald-600 to-teal-700 rounded-2xl sm:rounded-3xl p-5 sm:p-8 shadow-xl shadow-emerald-500/20 text-white relative overflow-hidden">
                    <div class="absolute -right-8 -top-8 opacity-10"><i data-feather="award" class="w-40 h-40"></i></div>
                    <div class="flex flex-col sm:flex-row gap-4 sm:gap-6 items-center sm:items-start relative z-10">
                        <div class="w-14 h-14 sm:w-16 sm:h-16 rounded-2xl bg-white/20 flex items-center justify-center shrink-0 border border-white/30">
                            <i data-feather="award" class="w-7 h-7 sm:w-8 sm:h-8"></i>
                        </div>
                        <div class="flex-1 text-center sm:text-left">
                            <span class="inline-block px-3 py-1 bg-white/20 rounded-full text-[10px] font-black uppercase tracking-widest mb-2 border border-white/30">Status Mahasiswa Resmi</span>
                            <h3 class="text-xl sm:text-2xl font-black mb-1">Selamat Datang, Mahasiswa Baru!</h3>
                            <p class="text-[12px] sm:text-[13px] font-medium text-emerald-50 mb-4">
                                Anda telah resmi terdaftar sebagai mahasiswa Universitas Adzkia pada program studi
                                <strong class="text-white">
                                    @if($pendaftar->status_kelulusan === 'Lulus Pilihan 1') {{ $pendaftar->pilihan_jurusan_1 }}
                                    @elseif($pendaftar->status_kelulusan === 'Lulus Pilihan 2') {{ $pendaftar->pilihan_jurusan_2 }}
                                    @endif
                                </strong>.
                            </p>
                            <div class="inline-flex items-center gap-3 bg-white/15 border border-white/30 rounded-xl px-4 py-3">
                                <i data-feather="credit-card" class="w-5 h-5 shrink-0 text-emerald-200"></i>
                                <div class="text-left">
                                    <p class="text-[9px] font-black uppercase tracking-widest text-emerald-200">Nomor Induk Mahasiswa (NIM)</p>
                                    <p class="text-lg sm:text-xl font-black tracking-wider">{{ $pendaftar->nim }}</p>
                                    <p class="text-[10px] text-emerald-200 mt-0.5">
                                        @if($pendaftar->status_kelulusan === 'Lulus Pilihan 1') {{ $pendaftar->pilihan_jurusan_1 }}
                                        @elseif($pendaftar->status_kelulusan === 'Lulus Pilihan 2') {{ $pendaftar->pilihan_jurusan_2 }}
                                        @endif
                                    </p>
                                </div>
                            </div>
                            <p class="text-[11px] text-emerald-100 mt-3">Simpan NIM ini untuk keperluan akademik selanjutnya.</p>
                        </div>
                    </div>
                </div>

            @elseif(in_array($pendaftar->status_kelulusan, ['Lulus Pilihan 1', 'Lulus Pilihan 2']))
                
                @php
                    $jurusanDiterima = ($pendaftar->status_kelulusan == 'Lulus Pilihan 1') 
                                        ? $pendaftar->pilihan_jurusan_1 
                                        : $pendaftar->pilihan_jurusan_2;
                @endphp

                {{-- Ubah: flex-col di mobile, flex-row di md --}}
                <div class="bg-gradient-to-r from-green-500 to-emerald-600 rounded-2xl sm:rounded-3xl p-5 sm:p-6 md:p-8 flex flex-col gap-4 sm:gap-6 shadow-xl shadow-green-500/20 text-white text-center relative overflow-hidden">
                    <div class="absolute -right-10 -top-10 opacity-10"><i data-feather="award" class="w-36 sm:w-48 h-36 sm:h-48"></i></div>
                    {{-- Ubah: ikon dan teks rata tengah di mobile --}}
                    <div class="flex flex-col sm:flex-row gap-4 sm:gap-6 items-center sm:items-start sm:text-left relative z-10">
                        <div class="w-14 h-14 sm:w-16 sm:h-16 rounded-2xl bg-white text-green-600 flex items-center justify-center shrink-0 shadow-lg">
                            <i data-feather="check-circle" class="w-7 h-7 sm:w-8 sm:h-8"></i>
                        </div>
                        <div class="flex-1">
                            <span class="inline-block px-3 py-1 bg-white/20 rounded-full text-[10px] font-black uppercase tracking-widest mb-2 sm:mb-3 backdrop-blur-sm border border-white/30">Pengumuman Hasil Akhir</span>
                            <h3 class="text-xl sm:text-2xl font-black mb-2">Selamat, Anda Dinyatakan LULUS!</h3>
                            <p class="text-[12px] sm:text-[13px] font-medium text-green-50 leading-relaxed mb-4 sm:mb-5">
                                Selamat datang di kampus Universitas Adzkia! Berdasarkan hasil seleksi, Anda dinyatakan lulus dan diterima di program studi <strong class="text-white bg-green-700/50 px-2 py-0.5 rounded">{{ $jurusanDiterima }}</strong> ({{ str_replace('Lulus ', '', $pendaftar->status_kelulusan) }}).
                            </p>
                            {{-- Tombol aksi --}}
                            <div class="flex flex-col sm:flex-row gap-3">
                                <a href="{{ route('cetak.loa') }}" target="_blank"
                                   class="inline-flex items-center justify-center gap-2 px-5 sm:px-6 py-3 sm:py-3.5 bg-white text-green-600 hover:bg-gray-50 font-black text-[13px] rounded-xl transition-all shadow-md w-full sm:w-auto active:scale-[0.98]">
                                    <i data-feather="download" class="w-4 h-4"></i> Unduh Surat Kelulusan
                                </a>

                                @php $statusDU = strtolower($pendaftar->status_daftar_ulang ?? 'belum'); @endphp

                                @if(in_array($statusDU, ['selesai', 'terverifikasi', 'disetujui']))
                                    <span class="inline-flex items-center justify-center gap-2 px-5 sm:px-6 py-3 sm:py-3.5 bg-white/10 border border-white/20 text-white/70 font-black text-[13px] rounded-xl w-full sm:w-auto cursor-default">
                                        <i data-feather="check" class="w-4 h-4"></i> Daftar Ulang Selesai
                                    </span>
                                @else
                                    <a href="{{ route('daftar-ulang.data-ortu') }}"
                                       class="inline-flex items-center justify-center gap-2 px-5 sm:px-6 py-3 sm:py-3.5 bg-white/20 hover:bg-white/30 border border-white/40 text-white font-black text-[13px] rounded-xl transition-all w-full sm:w-auto active:scale-[0.98] backdrop-blur-sm">
                                        <i data-feather="user-check" class="w-4 h-4"></i>
                                        @if(in_array($statusDU, ['menunggu validasi', 'menunggu verifikasi']))
                                            Cek Status Daftar Ulang
                                        @elseif($statusDU === 'revisi')
                                            Perbaiki Berkas Daftar Ulang
                                        @else
                                            Daftar Ulang Sekarang
                                        @endif
                                    </a>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

            @elseif($pendaftar->status_kelulusan == 'Tidak Lulus')
                <div class="bg-adzkia-red rounded-2xl sm:rounded-3xl p-5 sm:p-6 md:p-8 flex flex-col sm:flex-row gap-4 sm:gap-6 items-center sm:items-start shadow-xl shadow-red-600/20 text-white text-center sm:text-left relative overflow-hidden">
                    <div class="w-14 h-14 sm:w-16 sm:h-16 rounded-2xl bg-white/10 text-white flex items-center justify-center shrink-0 shadow-lg relative z-10 border border-white/20 backdrop-blur-sm">
                        <i data-feather="x-circle" class="w-7 h-7 sm:w-8 sm:h-8"></i>
                    </div>
                    <div class="flex-1 relative z-10">
                        <span class="inline-block px-3 py-1 bg-white/20 rounded-full text-[10px] font-black uppercase tracking-widest mb-2 sm:mb-3 backdrop-blur-sm border border-white/30">Pengumuman Hasil Akhir</span>
                        <h3 class="text-lg sm:text-xl font-black mb-2">Mohon Maaf, Anda Tidak Lulus</h3>
                        <p class="text-[12px] sm:text-[13px] font-medium text-red-100 leading-relaxed">
                            Terima kasih atas partisipasi Anda dalam seleksi PMB Universitas Adzkia. Jangan patah semangat, Anda masih bisa mencoba mendaftar kembali pada gelombang atau jalur pendaftaran berikutnya.
                        </p>
                    </div>
                </div>

            @elseif($pendaftar->status_pendaftaran == 'Selesai')
                <div class="bg-gradient-to-r from-blue-600 to-indigo-700 rounded-2xl sm:rounded-3xl p-5 sm:p-6 md:p-8 flex flex-col sm:flex-row gap-4 sm:gap-6 items-center sm:items-start shadow-xl shadow-blue-600/20 text-white text-center sm:text-left relative overflow-hidden">
                    <div class="w-14 h-14 sm:w-16 sm:h-16 rounded-2xl bg-white/20 text-white flex items-center justify-center shrink-0 shadow-lg relative z-10 border border-white/20 backdrop-blur-sm">
                        <i data-feather="calendar" class="w-7 h-7 sm:w-8 sm:h-8"></i>
                    </div>
                    <div class="flex-1 relative z-10">
                        <span class="inline-block px-3 py-1 bg-white/20 rounded-full text-[10px] font-black uppercase tracking-widest mb-2 sm:mb-3 backdrop-blur-sm border border-white/30">Tahap Akhir</span>
                        <h3 class="text-lg sm:text-xl font-black mb-2">Menunggu Pengumuman Kelulusan</h3>
                        <p class="text-[12px] sm:text-[13px] font-medium text-blue-100 leading-relaxed">
                            Berkas dan data Anda telah dinyatakan <strong>Valid</strong>. Saat ini Panitia PMB sedang melakukan kurasi akhir. Harap pantau dashboard ini secara berkala untuk melihat hasil kelulusan Anda.
                        </p>
                    </div>
                </div>

            @elseif($pendaftar->status_pendaftaran == 'Revisi')
                <div class="bg-red-50 border border-red-200 rounded-2xl sm:rounded-3xl p-4 sm:p-6 flex gap-3 sm:gap-4 items-start shadow-sm shadow-red-600/5">
                    <div class="w-9 h-9 sm:w-10 sm:h-10 rounded-xl bg-adzkia-red text-white flex items-center justify-center shrink-0 shadow-md shadow-red-500/20">
                        <i data-feather="alert-triangle" class="w-4 h-4 sm:w-5 sm:h-5"></i>
                    </div>
                    <div class="flex-1 min-w-0">
                        <h3 class="text-sm sm:text-[15px] font-black text-red-900">Perbaikan Berkas Diperlukan!</h3>
                        <p class="text-[11px] sm:text-xs font-medium text-red-700/90 mt-1 leading-relaxed">
                            Mohon maaf, terdapat ketidaksesuaian pada data atau dokumen yang Anda unggah. Admin meninggalkan pesan untuk Anda:
                        </p>
                        <div class="mt-3 p-3 sm:p-4 bg-white border border-red-100 rounded-xl shadow-sm relative">
                            <div class="absolute -left-1.5 top-5 w-3 h-3 bg-white border-l border-b border-red-100 rotate-45"></div>
                            <p class="text-xs sm:text-sm font-bold text-adzkia-dark"><span class="text-adzkia-red font-black">Catatan Admin:</span> <br> "{{ $pendaftar->pesan_revisi ?? 'Mohon periksa kembali kelengkapan dokumen Anda.' }}"</p>
                        </div>
                        <a href="{{ route('pendaftaran.biodata') }}" class="inline-flex items-center gap-2 px-4 sm:px-6 py-2.5 sm:py-3 bg-adzkia-red hover:bg-red-700 text-white font-black text-xs sm:text-sm rounded-xl mt-4 transition-all shadow-lg shadow-red-600/20 active:scale-[0.98] w-full sm:w-auto justify-center sm:justify-start">
                            <i data-feather="edit-2" class="w-4 h-4"></i> Perbaiki Berkas Sekarang &rarr;
                        </a>
                    </div>
                </div>

            @elseif($pendaftar->status_pendaftaran == 'menunggu verifikasi')
                <div class="bg-blue-50 border border-blue-200 rounded-2xl sm:rounded-3xl p-4 sm:p-6 flex gap-3 sm:gap-4 items-start">
                    <div class="w-9 h-9 sm:w-10 sm:h-10 rounded-xl bg-adzkia-blue text-white flex items-center justify-center shrink-0 shadow-md shadow-adzkia-blue/20">
                        <i data-feather="search" class="w-4 h-4 sm:w-5 sm:h-5"></i>
                    </div>
                    <div class="flex-1">
                        <h3 class="text-sm sm:text-[15px] font-black text-adzkia-blue">Berkas Sedang Dalam Pengecekan</h3>
                        <p class="text-[11px] sm:text-xs font-medium text-gray-500 mt-1 leading-relaxed">
                            Terima kasih telah melengkapi formulir. Data dan dokumen Anda saat ini sedang diperiksa kelengkapannya oleh tim admin PMB.
                        </p>
                    </div>
                </div>

            @elseif($pendaftar->status_pembayaran == 'Terverifikasi')
                <div class="bg-green-50 border border-green-200 rounded-2xl sm:rounded-3xl p-4 sm:p-6 flex gap-3 sm:gap-4 items-start shadow-sm shadow-green-600/5">
                    <div class="w-9 h-9 sm:w-10 sm:h-10 rounded-xl bg-green-500 text-white flex items-center justify-center shrink-0 shadow-md shadow-green-500/20">
                        <i data-feather="check-circle" class="w-4 h-4 sm:w-5 sm:h-5"></i>
                    </div>
                    <div class="flex-1">
                        <h3 class="text-sm sm:text-[15px] font-black text-green-900">Pembayaran Terverifikasi!</h3>
                        <p class="text-[11px] sm:text-xs font-medium text-green-700/90 mt-1 leading-relaxed">
                            Biaya administrasi pendaftaran Anda telah divalidasi. Silakan lanjutkan langkah berikutnya untuk melengkapi berkas biodata Anda.
                        </p>
                        <a href="{{ route('pendaftaran.biodata') }}" class="inline-flex items-center gap-2 px-4 sm:px-6 py-2.5 sm:py-3 bg-adzkia-red hover:bg-red-700 text-white font-black text-xs sm:text-sm rounded-xl mt-4 transition-all shadow-lg shadow-red-600/20 active:scale-[0.98] w-full sm:w-auto justify-center sm:justify-start">
                            Lanjutkan Pengisian Formulir &rarr;
                        </a>
                    </div>
                </div>

            @elseif($pendaftar->status_pembayaran == 'Menunggu Validasi')
                <div class="bg-blue-50 border border-blue-200 rounded-2xl sm:rounded-3xl p-4 sm:p-6 flex gap-3 sm:gap-4 items-start">
                    <div class="w-9 h-9 sm:w-10 sm:h-10 rounded-xl bg-adzkia-blue text-white flex items-center justify-center shrink-0 shadow-md shadow-adzkia-blue/20">
                        <i data-feather="clock" class="w-4 h-4 sm:w-5 sm:h-5 animate-spin"></i>
                    </div>
                    <div class="flex-1">
                        <h3 class="text-sm sm:text-[15px] font-black text-adzkia-blue">Bukti Pembayaran Sedang Diperiksa</h3>
                        <p class="text-[11px] sm:text-xs font-medium text-gray-500 mt-1 leading-relaxed">
                            Bukti pembayaran yang Anda unggah sedang dalam antrean verifikasi oleh admin keuangan. Mohon tunggu maksimal 1x24 jam kerja.
                        </p>
                    </div>
                </div>

            @else
                <div class="bg-amber-50 border border-amber-200 rounded-2xl sm:rounded-3xl p-4 sm:p-6 flex gap-3 sm:gap-4 items-start">
                    <div class="w-9 h-9 sm:w-10 sm:h-10 rounded-xl bg-amber-500 text-white flex items-center justify-center shrink-0 shadow-md shadow-amber-500/20">
                        <i data-feather="credit-card" class="w-4 h-4 sm:w-5 sm:h-5"></i>
                    </div>
                    <div class="flex-1">
                        <h3 class="text-sm sm:text-[15px] font-black text-amber-900">Selesaikan Pembayaran Administrasi</h3>
                        <p class="text-[11px] sm:text-xs font-medium text-amber-700/90 mt-1 leading-relaxed">
                            Anda belum menyelesaikan pembayaran registrasi awal. Pilih metode pembayaran Anda untuk mengaktifkan tahap pengisian formulir.
                        </p>
                        <a href="{{ url('/pembayaran') }}" class="inline-flex items-center gap-2 px-4 sm:px-5 py-2 sm:py-2.5 bg-amber-600 hover:bg-amber-700 text-white font-extrabold text-xs rounded-xl mt-4 transition-all w-full sm:w-auto justify-center sm:justify-start">
                            Pilih Metode Pembayaran &rarr;
                        </a>
                    </div>
                </div>
            @endif

            {{-- SECTION BERITA DAN FAQ --}}
            {{-- Ubah: 2 kolom di md, 1 kolom di mobile --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 sm:gap-6 mt-4 sm:mt-8">
                
                {{-- BERITA TERKINI --}}
                <div class="bg-white rounded-2xl sm:rounded-[2rem] p-4 sm:p-6 border border-gray-100 shadow-sm flex flex-col">
                    <div class="flex items-center justify-between mb-4 sm:mb-5">
                        <h3 class="text-sm sm:text-md font-black text-adzkia-dark">Berita Terkini</h3>
                    </div>
                    
                    <div class="flex-1 flex flex-col gap-3 sm:gap-4">
                        @if(isset($berita) && count($berita) > 0)
                            @foreach($berita as $b)
                            <a href="#" class="flex gap-3 sm:gap-4 group">
                                {{-- Ubah: gambar sedikit lebih kecil di mobile --}}
                                <div class="w-16 h-16 sm:w-20 sm:h-20 bg-gray-100 rounded-xl overflow-hidden shrink-0 border border-gray-100">
                                    <img src="{{ $b->thumbnail ? asset('uploads/berita/' . $b->thumbnail) : asset('images/default-news.jpg') }}" alt="{{ $b->judul }}" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
                                </div>
                                <div class="flex flex-col justify-center min-w-0">
                                    <p class="text-[10px] font-bold text-gray-400 mb-1 uppercase tracking-widest">{{ \Carbon\Carbon::parse($b->created_at)->format('d M Y') }}</p>
                                    <h4 class="text-[12px] sm:text-[13px] font-black text-adzkia-dark group-hover:text-adzkia-blue transition-colors line-clamp-2 leading-snug">{{ $b->judul }}</h4>
                                </div>
                            </a>
                            @endforeach
                        @else
                            <div class="flex-1 flex flex-col items-center justify-center text-center p-4 sm:p-6 border-2 border-dashed border-gray-100 rounded-2xl">
                                <i data-feather="inbox" class="w-6 h-6 text-gray-300 mb-2"></i>
                                <p class="text-[11px] font-bold text-gray-400">Belum ada informasi terbaru saat ini.</p>
                            </div>
                        @endif
                    </div>
                </div>

                {{-- FAQ --}}
                <div class="bg-white rounded-2xl sm:rounded-[2rem] p-4 sm:p-6 border border-gray-100 shadow-sm" x-data="{ active: null }">
                    <h3 class="text-sm sm:text-md font-black text-adzkia-dark mb-4 sm:mb-5">FAQ Bantuan</h3>
                    
                    <div class="space-y-2 sm:space-y-3">
                        @forelse($faqs as $faq)
                        <div class="border border-gray-100 rounded-xl overflow-hidden">
                            <button @click="active = active === {{ $faq->id }} ? null : {{ $faq->id }}" class="w-full px-3 sm:px-4 py-2.5 sm:py-3 flex items-center justify-between bg-gray-50 hover:bg-gray-100 transition-colors">
                                <span class="text-[11px] sm:text-[12px] font-black text-adzkia-dark text-left pr-2">{{ $faq->pertanyaan }}</span>
                                <i data-feather="chevron-down" class="w-4 h-4 text-gray-400 transition-transform shrink-0" :class="active === {{ $faq->id }} ? 'rotate-180' : ''"></i>
                            </button>
                            <div x-show="active === {{ $faq->id }}" x-collapse x-cloak>
                                <div class="p-3 sm:p-4 text-[11px] sm:text-[12px] font-medium text-gray-500 leading-relaxed bg-white border-t border-gray-100">
                                    {{ $faq->jawaban }}
                                </div>
                            </div>
                        </div>
                        @empty
                        <div class="text-center py-4 sm:py-6 border-2 border-dashed border-gray-100 rounded-xl">
                            <p class="text-[11px] font-bold text-gray-400">Belum ada pertanyaan umum yang ditambahkan.</p>
                        </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>

        {{-- KOLOM KANAN (sidebar profil) --}}
        {{-- Ubah: di mobile muncul di bawah (order-first utk profil), di lg jadi kolom kanan --}}
        <div class="lg:col-span-4 space-y-4 sm:space-y-6">

            {{-- KOTAK PROFIL USER --}}
            <div class="bg-white rounded-2xl sm:rounded-[2rem] p-4 sm:p-6 border border-gray-100 shadow-sm relative overflow-hidden">
                <div class="absolute top-0 left-0 w-full h-20 sm:h-24 bg-gradient-to-b from-blue-50 to-white"></div>
                
                <div class="text-center pb-4 sm:pb-6 border-b border-gray-50 relative z-10 pt-3 sm:pt-4">
                    @php
                        // Foto: cek pas_foto → berkas_dokumen → avatar inisial
                        $fotoUrl = null;
                        if (!empty($pendaftar->pas_foto)) {
                            $fotoUrl = asset($pendaftar->pas_foto);
                        } elseif (!empty($pendaftar->berkas_dokumen)) {
                            $berkas = is_array($pendaftar->berkas_dokumen)
                                ? $pendaftar->berkas_dokumen
                                : (json_decode($pendaftar->berkas_dokumen, true) ?? []);
                            foreach ($berkas as $namaDoc => $pathDoc) {
                                if (!empty($pathDoc) && (stripos($namaDoc, 'foto') !== false || stripos($namaDoc, 'pas') !== false)) {
                                    $fotoUrl = asset($pathDoc);
                                    break;
                                }
                            }
                        }
                        // Inisial untuk avatar fallback
                        $namaWords = explode(' ', trim($pendaftar->nama_lengkap ?? 'U'));
                        $inisial = strtoupper(substr($namaWords[0], 0, 1) . (isset($namaWords[1]) ? substr($namaWords[1], 0, 1) : ''));

                        // Jurusan yang diterima (jika sudah lulus)
                        $sudahLulus = in_array($pendaftar->status_kelulusan, ['Lulus Pilihan 1', 'Lulus Pilihan 2']);
                        $jurusanDiterima = null;
                        if ($sudahLulus) {
                            $jurusanDiterima = ($pendaftar->status_kelulusan === 'Lulus Pilihan 1')
                                ? $pendaftar->pilihan_jurusan_1
                                : $pendaftar->pilihan_jurusan_2;
                        }
                        $sudahJadiMahasiswa = !empty($pendaftar->nim) && strtolower($pendaftar->status_daftar_ulang ?? '') === 'selesai';
                    @endphp

                    {{-- Avatar / Foto --}}
                    <div class="w-20 h-20 sm:w-24 sm:h-24 bg-white border-4 border-white shadow-md rounded-2xl mx-auto flex items-center justify-center mb-3 sm:mb-4 overflow-hidden">
                        @if($fotoUrl)
                            <img src="{{ $fotoUrl }}" alt="Foto Profil" class="w-full h-full object-cover"
                                 onerror="this.parentElement.innerHTML='<div class=\'w-full h-full bg-gradient-to-br from-adzkia-blue to-blue-700 flex items-center justify-center\'><span class=\'text-white font-black text-xl\'>{{ $inisial }}</span></div>'">
                        @else
                            <div class="w-full h-full bg-gradient-to-br from-adzkia-blue to-blue-700 flex items-center justify-center">
                                <span class="text-white font-black text-xl sm:text-2xl">{{ $inisial }}</span>
                            </div>
                        @endif
                    </div>

                    <h3 class="font-black text-[14px] sm:text-[15px] text-adzkia-dark leading-snug">{{ $pendaftar->nama_lengkap ?? 'Nama Belum Diisi' }}</h3>
                    <p class="text-[10px] sm:text-[11px] font-bold text-gray-400 mt-1 uppercase tracking-widest">{{ $pendaftar->no_pendaftaran ?? 'No. Reg Belum Ada' }}</p>

                    {{-- NIM — tampil setelah daftar ulang selesai --}}
                    @if($sudahJadiMahasiswa)
                    <div class="mt-2 px-3 py-1.5 bg-emerald-50 border border-emerald-200 rounded-xl inline-block">
                        <p class="text-[12px] font-black text-emerald-800 tracking-wider">{{ $pendaftar->nim }}</p>
                    </div>
                    @endif

                    <p class="text-[10px] font-bold text-adzkia-blue mt-2 bg-blue-50 px-3 py-1 rounded-full inline-block">{{ $pendaftar->jalur_pendaftaran ?? 'Jalur Reguler' }}</p>
                </div>

                {{-- Ubah: grid 2 kolom di mobile agar info lebih ringkas --}}
                <div class="pt-4 sm:pt-6 grid grid-cols-2 sm:grid-cols-1 gap-3 sm:gap-4">
                    @if($sudahLulus && $jurusanDiterima)
                    {{-- Sudah lulus: tampilkan prodi diterima, sembunyikan pilihan 1 & 2 --}}
                    <div class="col-span-2 sm:col-span-1">
                        <p class="text-[9px] font-black text-emerald-600 uppercase tracking-widest">Program Studi Diterima</p>
                        <p class="text-[12px] sm:text-sm font-black text-emerald-700 mt-0.5 leading-snug">{{ $jurusanDiterima }}</p>
                        <p class="text-[9px] text-gray-400 mt-0.5">{{ str_replace('Lulus ', '', $pendaftar->status_kelulusan) }}</p>
                    </div>
                    @else
                    {{-- Belum lulus: tampilkan pilihan jurusan biasa --}}
                    <div>
                        <p class="text-[9px] font-black text-gray-400 uppercase tracking-widest">Pilihan Jurusan 1</p>
                        <p class="text-[11px] sm:text-xs font-bold text-adzkia-dark mt-0.5 leading-snug">{{ $pendaftar->pilihan_jurusan_1 ?? 'Belum diisi' }}</p>
                    </div>
                    <div>
                        <p class="text-[9px] font-black text-gray-400 uppercase tracking-widest">Pilihan Jurusan 2</p>
                        <p class="text-[11px] sm:text-xs font-bold text-adzkia-dark mt-0.5 leading-snug">{{ $pendaftar->pilihan_jurusan_2 ?? 'Belum diisi' }}</p>
                    </div>
                    @endif
                    <div>
                        <p class="text-[9px] font-black text-gray-400 uppercase tracking-widest">No. WhatsApp</p>
                        <p class="text-[11px] sm:text-xs font-bold text-adzkia-dark mt-0.5">{{ $pendaftar->no_whatsapp ?? 'Belum diisi' }}</p>
                    </div>
                    <div>
                        <p class="text-[9px] font-black text-gray-400 uppercase tracking-widest">Alamat Domisili</p>
                        <p class="text-[11px] sm:text-xs font-bold text-adzkia-dark mt-0.5 leading-relaxed">{{ $pendaftar->alamat_rumah ?? 'Belum diisi' }}</p>
                    </div>
                </div>
            </div>

            {{-- KARTU BANTUAN --}}
            <div class="bg-gradient-to-br from-adzkia-blue to-blue-800 text-white rounded-2xl sm:rounded-[2rem] p-4 sm:p-6 shadow-md relative overflow-hidden group hover:shadow-lg transition-all">
                <div class="absolute -right-4 -bottom-4 opacity-10 group-hover:scale-110 transition-transform"><i data-feather="phone-call" class="w-20 sm:w-24 h-20 sm:h-24 text-white"></i></div>
                <i data-feather="help-circle" class="w-5 h-5 sm:w-6 sm:h-6 text-blue-200 mb-2 sm:mb-3 relative z-10"></i>
                <h4 class="font-extrabold text-sm relative z-10">Pusat Bantuan PMB</h4>
                <p class="text-[11px] text-blue-100 font-medium mt-1.5 leading-relaxed relative z-10">Apabila mengalami kendala sistem atau kesalahan input data, segera hubungi admin panitia kami.</p>
                <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $globalSetting->telepon ?? '6281234567890') }}" class="block w-full text-center py-2.5 sm:py-3 bg-white text-adzkia-blue font-black text-[12px] rounded-xl mt-4 sm:mt-5 hover:bg-blue-50 transition-all shadow-sm relative z-10">Hubungi via WhatsApp</a>
            </div>
        </div>

    </main>

    {{-- FOOTER --}}
    <footer class="w-full bg-adzkia-bg py-5 sm:py-8 flex flex-col items-center gap-3 sm:gap-4 px-4 sm:px-6 md:px-16 border-t border-gray-100 text-center">
        <p class="text-[11px] font-bold text-gray-400">© 2026 Universitas Adzkia. All Rights Reserved.</p>
        <div class="flex gap-4 sm:gap-6 text-[11px] font-bold text-gray-500">
            <a href="#" class="hover:text-adzkia-blue transition-colors">Privacy Policy</a>
            <a href="#" class="hover:text-adzkia-blue transition-colors">Terms of Service</a>
        </div>
    </footer>

    <script>
        window.addEventListener('load', function() {
            if(window.feather) feather.replace();
        });
    </script>
</body>
</html>