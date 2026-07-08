<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pembayaran Daftar Ulang - SPMB Adzkia</title>
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
                        'adzkia-red':      '#d9241c',
                        'adzkia-blue':     '#2c7ebd',
                        'adzkia-dark':     '#1e293b',
                        'adzkia-badge-bg': '#eff6ff',
                    }
                }
            }
        }
    </script>
    <style>
        [x-cloak] { display: none !important; }
    </style>
</head>
<body class="bg-gray-50 antialiased text-adzkia-dark min-h-screen flex flex-col"
      x-data="{
          fileName: '',
          fileSize: '',
          fileValid: false,
          onFileChange(e) {
              const file = e.target.files[0];
              if (!file) return;
              if (file.size > 2 * 1024 * 1024) {
                  alert('Ukuran file maksimal 2MB!');
                  e.target.value = '';
                  this.fileName = '';
                  this.fileValid = false;
                  return;
              }
              const allowed = ['image/jpeg','image/jpg','image/png','application/pdf'];
              if (!allowed.includes(file.type)) {
                  alert('Format file harus JPG, PNG, atau PDF!');
                  e.target.value = '';
                  this.fileName = '';
                  this.fileValid = false;
                  return;
              }
              this.fileName  = file.name;
              this.fileSize  = (file.size / 1024).toFixed(0) + ' KB';
              this.fileValid = true;
          }
      }">

    {{-- ============================================================ --}}
    {{-- NAVBAR                                                        --}}
    {{-- ============================================================ --}}
    <nav class="bg-white border-b border-gray-200 py-3 sm:py-4 px-4 sm:px-6 md:px-10 flex justify-between items-center sticky top-0 z-30">
        <a href="{{ route('dashboard.user') }}" class="flex items-center gap-2 sm:gap-3 group">
            <img src="{{ asset('images/logo-adzkia.png') }}" alt="Logo" class="h-8 sm:h-10 w-auto group-hover:scale-105 transition-transform">
            <div class="hidden sm:flex flex-col">
                <span class="text-[14px] sm:text-[16px] font-black text-adzkia-blue leading-none">Dasbor</span>
                <span class="text-[11px] sm:text-[12px] font-bold text-adzkia-red">Calon Mahasiswa</span>
            </div>
        </a>
        <div class="flex items-center gap-2 sm:gap-4 md:gap-6">
            <a href="{{ route('daftar-ulang.data-ortu') }}" class="flex items-center gap-1.5 sm:gap-2 text-[11px] sm:text-[13px] font-bold text-gray-500 hover:text-adzkia-blue transition-colors bg-gray-50 px-3 sm:px-4 py-2 rounded-lg">
                <i data-feather="arrow-left" class="w-3.5 h-3.5 sm:w-4 sm:h-4"></i>
                <span class="hidden sm:inline">Kembali ke Data Ortu</span>
            </a>
            <div class="hidden md:block w-px h-6 bg-gray-200"></div>
            <div class="flex items-center gap-2 sm:gap-3">
                <div class="text-right hidden md:block">
                    <p class="text-[13px] font-extrabold text-adzkia-dark">{{ session('nama_pendaftar') }}</p>
                    <p class="text-[11px] font-bold text-gray-400">ID: {{ $pendaftar->no_pendaftaran ?? 'ID Kosong' }}</p>
                </div>
                <img src="https://ui-avatars.com/api/?name={{ urlencode(session('nama_pendaftar')) }}&background=1e293b&color=fff"
                     class="w-8 h-8 sm:w-10 sm:h-10 rounded-full border-2 border-gray-100">
            </div>
        </div>
    </nav>

    {{-- ============================================================ --}}
    {{-- STEP PROGRESS TRACKER                                         --}}
    {{-- ============================================================ --}}
    <div class="w-full bg-white py-4 sm:py-6 border-b border-gray-100 z-20">
        <div class="max-w-5xl mx-auto px-4 sm:px-6">
            <div class="flex items-center justify-between relative">
                <div class="absolute top-[18px] sm:top-1/2 left-0 w-full h-0.5 bg-gray-100 -translate-y-1/2 z-0"></div>
                @php
                    $steps = [
                        ['id' => 1, 'title' => 'Lulus Seleksi'],
                        ['id' => 2, 'title' => 'Pengumuman'],
                        ['id' => 3, 'title' => 'Data Ortu'],
                        ['id' => 4, 'title' => 'Pembayaran'],
                        ['id' => 5, 'title' => 'Selesai'],
                    ];
                    $currentStep = 4;
                @endphp
                @foreach($steps as $step)
                    <div class="relative z-10 flex flex-col items-center gap-1 sm:gap-2">
                        <div class="w-8 h-8 sm:w-10 sm:h-10 rounded-full flex items-center justify-center font-bold text-[11px] sm:text-[13px] transition-all duration-300
                            {{ $step['id'] == $currentStep
                                ? 'bg-adzkia-blue text-white shadow-lg shadow-blue-600/30 scale-110'
                                : ($step['id'] < $currentStep
                                    ? 'bg-green-500 text-white border-2 border-green-500'
                                    : 'bg-white border-2 border-gray-100 text-gray-400') }}">
                            @if($step['id'] < $currentStep)
                                <i data-feather="check" class="w-3 h-3 sm:w-4 sm:h-4"></i>
                            @else
                                {{ $step['id'] }}
                            @endif
                        </div>
                        <span class="text-[9px] font-black uppercase tracking-widest hidden md:block
                            {{ $step['id'] == $currentStep
                                ? 'text-adzkia-blue'
                                : ($step['id'] < $currentStep ? 'text-green-500' : 'text-gray-400') }}">
                            {{ $step['title'] }}
                        </span>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    {{-- ============================================================ --}}
    {{-- MAIN                                                          --}}
    {{-- ============================================================ --}}
    <main class="flex-1 max-w-5xl mx-auto w-full px-4 sm:px-6 py-6 sm:py-10">

        <div class="mb-6 sm:mb-8">
            <span class="inline-block px-3 py-1 bg-adzkia-badge-bg text-adzkia-blue rounded-lg text-[10px] sm:text-[11px] font-black uppercase tracking-widest mb-2 sm:mb-3">STEP 04 / 05 — DAFTAR ULANG</span>
            <h1 class="text-2xl sm:text-3xl font-black text-adzkia-dark tracking-tight">Pembayaran Daftar Ulang</h1>
            <p class="text-[13px] sm:text-[14px] font-medium text-gray-500 mt-1.5 sm:mt-2">Selesaikan pembayaran dan unggah bukti transfer untuk konfirmasi.</p>
        </div>

        {{-- Notifikasi --}}
        @if (session('success'))
            <div class="bg-green-50 border-l-4 border-green-500 p-4 mb-6 rounded-r-2xl flex items-center gap-3">
                <i data-feather="check-circle" class="w-5 h-5 text-green-500 shrink-0"></i>
                <p class="text-[13px] font-bold text-green-700">{{ session('success') }}</p>
            </div>
        @endif

        @if (session('error'))
            <div class="bg-red-50 border-l-4 border-adzkia-red p-4 mb-6 rounded-r-2xl flex items-center gap-3">
                <i data-feather="alert-circle" class="w-5 h-5 text-adzkia-red shrink-0"></i>
                <p class="text-[13px] font-bold text-red-700">{{ session('error') }}</p>
            </div>
        @endif

        @if ($errors->any())
            <div class="bg-red-50 border-l-4 border-adzkia-red p-4 mb-6 rounded-r-2xl">
                <h3 class="text-xs font-black text-adzkia-red uppercase tracking-widest mb-2">Oops! Ada yang kurang:</h3>
                <ul class="text-[12px] font-bold text-red-600 list-disc list-inside space-y-1">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        {{-- ====================================================
             STATUS: Sudah Upload Bukti → Tampil Banner Menunggu
        ===================================================== --}}
        @if($pendaftar->status_daftar_ulang === 'Menunggu Validasi')
            <div class="bg-amber-50 border border-amber-200 rounded-2xl sm:rounded-[2rem] p-6 sm:p-8 mb-6 flex flex-col sm:flex-row items-start sm:items-center gap-4">
                <div class="w-12 h-12 bg-amber-100 rounded-2xl flex items-center justify-center shrink-0">
                    <i data-feather="clock" class="w-6 h-6 text-amber-500"></i>
                </div>
                <div>
                    <h3 class="text-[14px] sm:text-[15px] font-black text-amber-800 mb-1">Bukti Pembayaran Sudah Dikirim</h3>
                    <p class="text-[12px] sm:text-[13px] font-medium text-amber-700/80 leading-relaxed">
                        Bukti transfer Anda sedang dalam proses validasi oleh Admin. Silakan tunggu konfirmasi melalui dashboard atau WhatsApp.
                    </p>
                </div>
            </div>
        @endif

        {{-- ====================================================
             LAYOUT 2 KOLOM: Invoice kiri, Form kanan
        ===================================================== --}}
        <div class="grid grid-cols-1 lg:grid-cols-5 gap-5 sm:gap-6 items-start">

            {{-- ============================================
                 KOLOM KIRI — INVOICE CARD
            ============================================= --}}
            <div class="lg:col-span-3 space-y-4">

                {{-- Card Invoice Utama --}}
                <div class="bg-white rounded-2xl sm:rounded-[2rem] shadow-sm border border-gray-100 overflow-hidden">

                    {{-- Header Invoice --}}
                    <div class="bg-adzkia-blue px-6 sm:px-8 py-5 sm:py-6">
                        <div class="flex items-start justify-between">
                            <div>
                                <p class="text-[10px] font-black text-blue-200 uppercase tracking-widest mb-1">Tagihan Daftar Ulang</p>
                                <h3 class="text-lg sm:text-xl font-black text-white">{{ $pendaftar->nama_lengkap }}</h3>
                                <p class="text-[12px] font-bold text-blue-200 mt-0.5">{{ $pendaftar->no_pendaftaran }} · {{ $pendaftar->pilihan_jurusan_1 ?? '-' }}</p>
                            </div>
                            <div class="w-10 h-10 bg-white/10 rounded-xl flex items-center justify-center">
                                <i data-feather="file-text" class="w-5 h-5 text-white"></i>
                            </div>
                        </div>
                    </div>

                    {{-- Rincian Biaya --}}
                    <div class="px-6 sm:px-8 py-5 sm:py-6 space-y-0">

                        @if($biaya)

                        {{-- Baris SPP --}}
                        <div class="flex items-center justify-between py-3.5 border-b border-gray-100">
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 bg-blue-50 rounded-xl flex items-center justify-center shrink-0">
                                    <i data-feather="book-open" class="w-3.5 h-3.5 text-adzkia-blue"></i>
                                </div>
                                <div>
                                    <p class="text-[13px] font-bold text-adzkia-dark">SPP Semester</p>
                                    <p class="text-[11px] font-medium text-gray-400">Biaya per semester</p>
                                </div>
                            </div>
                            <p class="text-[13px] sm:text-[14px] font-black text-adzkia-dark">
                                Rp {{ number_format($biaya->spp_semester, 0, ',', '.') }}
                            </p>
                        </div>

                        {{-- Baris Sarpras --}}
                        <div class="flex items-center justify-between py-3.5 border-b border-gray-100">
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 bg-purple-50 rounded-xl flex items-center justify-center shrink-0">
                                    <i data-feather="home" class="w-3.5 h-3.5 text-purple-500"></i>
                                </div>
                                <div>
                                    <p class="text-[13px] font-bold text-adzkia-dark">Biaya Sarpras</p>
                                    <p class="text-[11px] font-medium text-gray-400">Sarana & prasarana</p>
                                </div>
                            </div>
                            <p class="text-[13px] sm:text-[14px] font-black text-adzkia-dark">
                                Rp {{ number_format($biaya->biaya_sarpras, 0, ',', '.') }}
                            </p>
                        </div>

                        {{-- Baris Seragam / Orientasi --}}
                        <div class="flex items-center justify-between py-3.5 border-b border-gray-100">
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 bg-amber-50 rounded-xl flex items-center justify-center shrink-0">
                                    <i data-feather="star" class="w-3.5 h-3.5 text-amber-500"></i>
                                </div>
                                <div>
                                    <p class="text-[13px] font-bold text-adzkia-dark">Seragam & Orientasi</p>
                                    <p class="text-[11px] font-medium text-gray-400">Biaya seragam dan PKKMB</p>
                                </div>
                            </div>
                            <p class="text-[13px] sm:text-[14px] font-black text-adzkia-dark">
                                Rp {{ number_format($biaya->biaya_seragam_orientasi, 0, ',', '.') }}
                            </p>
                        </div>

                        {{-- Total --}}
                        <div class="flex items-center justify-between pt-5 pb-1">
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 bg-green-50 rounded-xl flex items-center justify-center shrink-0">
                                    <i data-feather="check-circle" class="w-3.5 h-3.5 text-green-500"></i>
                                </div>
                                <p class="text-[13px] sm:text-[14px] font-black text-adzkia-dark uppercase tracking-wide">Total yang Harus Dibayar</p>
                            </div>
                        </div>

                        {{-- Total besar --}}
                        <div class="bg-adzkia-badge-bg rounded-2xl px-5 py-4 flex items-center justify-between mt-1">
                            <div class="flex items-center gap-2">
                                <i data-feather="dollar-sign" class="w-5 h-5 text-adzkia-blue"></i>
                                <span class="text-[11px] font-black text-adzkia-blue uppercase tracking-widest">Total Tagihan</span>
                            </div>
                            <p class="text-xl sm:text-2xl font-black text-adzkia-blue">
                                Rp {{ number_format($biaya->total_biaya ?? ($biaya->spp_semester + $biaya->biaya_sarpras + $biaya->biaya_seragam_orientasi), 0, ',', '.') }}
                            </p>
                        </div>

                        @else
                        {{-- Fallback: data biaya belum dikonfigurasi admin --}}
                        <div class="flex flex-col items-center justify-center py-10 text-center gap-3">
                            <div class="w-12 h-12 bg-amber-50 rounded-2xl flex items-center justify-center">
                                <i data-feather="alert-circle" class="w-6 h-6 text-amber-500"></i>
                            </div>
                            <div>
                                <p class="text-[14px] font-black text-adzkia-dark">Biaya Belum Dikonfigurasi</p>
                                <p class="text-[12px] font-medium text-gray-400 mt-1 leading-relaxed">
                                    Admin belum mengatur biaya daftar ulang untuk jalur Anda.<br>
                                    Silakan hubungi panitia PMB untuk informasi lebih lanjut.
                                </p>
                            </div>
                        </div>
                        @endif

                    </div>
                </div>

                {{-- Card Info Rekening --}}
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5 sm:p-6">
                    <div class="flex items-center gap-2 mb-4">
                        <i data-feather="info" class="w-4 h-4 text-adzkia-blue shrink-0"></i>
                        <h4 class="text-[12px] sm:text-[13px] font-black text-adzkia-dark uppercase tracking-widest">Informasi Rekening Tujuan</h4>
                    </div>
                    <div class="space-y-3">
                        @foreach([
                            ['bank' => 'BCA',    'no' => '1234567890', 'atas' => 'Yayasan Adzkia'],
                            ['bank' => 'Mandiri', 'no' => '9876543210', 'atas' => 'Yayasan Adzkia'],
                            ['bank' => 'BSI',     'no' => '1122334455', 'atas' => 'Yayasan Adzkia'],
                        ] as $rek)
                            <div class="flex items-center justify-between bg-gray-50 rounded-xl px-4 py-3">
                                <div>
                                    <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest">{{ $rek['bank'] }}</p>
                                    <p class="text-[13px] sm:text-[14px] font-black text-adzkia-dark font-mono tracking-wider">{{ $rek['no'] }}</p>
                                    <p class="text-[11px] font-bold text-gray-500">a/n {{ $rek['atas'] }}</p>
                                </div>
                                <button type="button"
                                        onclick="navigator.clipboard.writeText('{{ $rek['no'] }}').then(() => this.innerHTML = '<span class=\'text-green-600\'>Disalin!</span>').catch(() => {}); setTimeout(() => this.innerHTML = '<i data-feather=\'copy\' class=\'w-4 h-4\'></i>', 2000)"
                                        class="w-8 h-8 bg-white border border-gray-200 rounded-xl flex items-center justify-center text-gray-400 hover:text-adzkia-blue hover:border-adzkia-blue transition-colors">
                                    <i data-feather="copy" class="w-4 h-4"></i>
                                </button>
                            </div>
                        @endforeach
                    </div>
                    <p class="text-[11px] font-medium text-gray-400 mt-4 leading-relaxed">
                        * Transfer sesuai nominal total. Sertakan nomor pendaftaran <strong class="text-adzkia-dark">{{ $pendaftar->no_pendaftaran }}</strong> pada berita/keterangan transfer.
                    </p>
                </div>

            </div>

            {{-- ============================================
                 KOLOM KANAN — FORM UPLOAD BUKTI
            ============================================= --}}
            <div class="lg:col-span-2">
                <div class="bg-white rounded-2xl sm:rounded-[2rem] shadow-sm border border-gray-100 overflow-hidden sticky top-24">

                    {{-- Header form --}}
                    <div class="flex items-center gap-3 px-5 sm:px-7 py-5 border-b border-gray-100 bg-gray-50/60">
                        <div class="w-9 h-9 bg-adzkia-badge-bg text-adzkia-blue rounded-xl flex items-center justify-center shrink-0">
                            <i data-feather="upload-cloud" class="w-4 h-4 sm:w-5 sm:h-5"></i>
                        </div>
                        <div>
                            <h3 class="text-[13px] sm:text-[14px] font-black text-adzkia-dark">Upload Bukti Transfer</h3>
                            <p class="text-[11px] font-medium text-gray-400">Maks. 2MB · JPG, PNG, PDF</p>
                        </div>
                    </div>

                    <form action="{{ route('daftar-ulang.proses-bukti') }}" method="POST" enctype="multipart/form-data"
                          class="p-5 sm:p-7 space-y-5">
                        @csrf

                        {{-- Metode Pembayaran --}}
                        <div>
                            <label class="block text-[10px] sm:text-[11px] font-black text-gray-400 uppercase tracking-widest mb-1.5 px-1">
                                Bank Tujuan / Metode Transfer
                            </label>
                            <div class="relative">
                                <select name="metode_daftar_ulang"
                                        required
                                        class="w-full px-4 py-3.5 bg-gray-50 border border-transparent rounded-2xl outline-none focus:border-adzkia-blue focus:bg-white transition-all font-bold text-[13px] text-adzkia-dark appearance-none cursor-pointer">
                                    <option value="" disabled {{ empty(old('metode_daftar_ulang')) ? 'selected' : '' }}>Pilih bank yang digunakan</option>
                                    @foreach(['BCA','Mandiri','BSI','BNI','BRI','BTN','CIMB Niaga','Permata','Transfer Lainnya'] as $bank)
                                        <option value="{{ $bank }}" {{ old('metode_daftar_ulang') == $bank ? 'selected' : '' }}>{{ $bank }}</option>
                                    @endforeach
                                </select>
                                <i data-feather="chevron-down" class="w-4 h-4 text-gray-400 absolute right-4 top-1/2 -translate-y-1/2 pointer-events-none"></i>
                            </div>
                        </div>

                        {{-- Upload File --}}
                        <div>
                            <label class="block text-[10px] sm:text-[11px] font-black text-gray-400 uppercase tracking-widest mb-1.5 px-1">
                                Bukti Transfer <span class="text-adzkia-red">*</span>
                            </label>

                            {{-- Drop area --}}
                            <div class="relative border-2 border-dashed rounded-2xl p-5 text-center transition-all cursor-pointer"
                                 :class="fileValid
                                    ? 'border-green-400 bg-green-50'
                                    : 'border-gray-200 bg-gray-50 hover:border-adzkia-blue hover:bg-blue-50'"
                                 @click="$refs.fileInput.click()">

                                {{-- State: belum ada file --}}
                                <div x-show="!fileValid" class="flex flex-col items-center gap-2 py-3">
                                    <div class="w-12 h-12 bg-white border border-gray-200 rounded-2xl flex items-center justify-center shadow-sm">
                                        <i data-feather="upload" class="w-5 h-5 text-gray-400"></i>
                                    </div>
                                    <div>
                                        <p class="text-[13px] font-black text-adzkia-dark">Klik atau drop file di sini</p>
                                        <p class="text-[11px] font-medium text-gray-400 mt-0.5">Foto / scan struk transfer, format JPG, PNG, atau PDF</p>
                                    </div>
                                    <span class="text-[12px] font-black text-adzkia-blue underline underline-offset-2">Pilih File</span>
                                </div>

                                {{-- State: file sudah dipilih --}}
                                <div x-show="fileValid" x-cloak class="flex flex-col items-center gap-2 py-1">
                                    <div class="w-12 h-12 bg-green-500 rounded-2xl flex items-center justify-center">
                                        <i data-feather="check" class="w-6 h-6 text-white"></i>
                                    </div>
                                    <div>
                                        <p class="text-[13px] font-black text-green-800 break-all" x-text="fileName"></p>
                                        <p class="text-[11px] font-medium text-green-600" x-text="fileSize"></p>
                                    </div>
                                    <button type="button" @click.stop="$refs.fileInput.click()"
                                            class="text-[11px] font-black text-gray-500 bg-white border border-gray-200 px-3 py-1.5 rounded-lg hover:bg-gray-100 transition-colors">
                                        Ganti File
                                    </button>
                                </div>

                                {{-- Hidden input --}}
                                <input type="file"
                                       name="bukti_daftar_ulang"
                                       x-ref="fileInput"
                                       @change="onFileChange($event)"
                                       accept=".jpg,.jpeg,.png,.pdf"
                                       class="hidden"
                                       required>
                            </div>
                        </div>

                        {{-- Bukti lama jika sudah pernah upload --}}
                        @if($pendaftar->bukti_daftar_ulang)
                            <div class="flex items-center gap-3 bg-blue-50 border border-blue-100 rounded-xl px-4 py-3">
                                <i data-feather="file" class="w-4 h-4 text-adzkia-blue shrink-0"></i>
                                <div class="flex-1 min-w-0">
                                    <p class="text-[11px] font-black text-adzkia-blue">Bukti sebelumnya:</p>
                                    <p class="text-[11px] font-medium text-gray-600 truncate">{{ basename($pendaftar->bukti_daftar_ulang) }}</p>
                                </div>
                                <a href="/{{ $pendaftar->bukti_daftar_ulang }}" target="_blank"
                                   class="text-[11px] font-black text-adzkia-blue bg-white border border-blue-200 px-3 py-1.5 rounded-lg hover:bg-blue-100 transition-colors shrink-0">
                                    Lihat
                                </a>
                            </div>
                        @endif

                        {{-- Catatan --}}
                        <div class="bg-amber-50 border border-amber-100 rounded-xl px-4 py-3 flex gap-2.5 items-start">
                            <i data-feather="alert-triangle" class="w-4 h-4 text-amber-500 shrink-0 mt-0.5"></i>
                            <p class="text-[11px] font-medium text-amber-700 leading-relaxed">
                                Pastikan bukti transfer terlihat jelas, memuat tanggal, jumlah, dan nama bank pengirim. Admin akan memvalidasi dalam 1×24 jam.
                            </p>
                        </div>

                        {{-- Tombol Submit --}}
                        <button type="submit"
                                :disabled="!fileValid"
                                class="w-full py-3.5 sm:py-4 bg-adzkia-blue text-white rounded-2xl font-black text-[14px] hover:bg-blue-700 shadow-lg shadow-blue-600/20 transition-all active:scale-[0.98] flex items-center justify-center gap-2 disabled:opacity-50 disabled:cursor-not-allowed disabled:shadow-none disabled:active:scale-100">
                            <i data-feather="send" class="w-4 h-4"></i>
                            Kirim Bukti Pembayaran
                        </button>

                    </form>
                </div>
            </div>

        </div>{{-- /.grid --}}

        {{-- Spacer bawah --}}
        <div class="pb-10"></div>

    </main>

    <script>
        document.addEventListener('DOMContentLoaded', () => { feather.replace(); });
    </script>
</body>
</html>