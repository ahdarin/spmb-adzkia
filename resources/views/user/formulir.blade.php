<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Formulir Pendaftaran - Dasbor SPMB Adzkia</title>
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
        textarea::-webkit-scrollbar { width: 6px; }
        textarea::-webkit-scrollbar-thumb { background-color: #cbd5e1; border-radius: 10px; }
    </style>
</head>
<body class="bg-gray-50 antialiased text-adzkia-dark min-h-screen flex flex-col"
      x-data="formulirApp()" x-init="init()">

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
            <a href="{{ route('dashboard.user') }}" class="flex items-center gap-1.5 sm:gap-2 text-[11px] sm:text-[13px] font-bold text-gray-500 hover:text-adzkia-blue transition-colors bg-gray-50 px-3 sm:px-4 py-2 rounded-lg">
                <i data-feather="arrow-left" class="w-3.5 h-3.5 sm:w-4 sm:h-4"></i>
                <span class="hidden sm:inline">Kembali ke Dasbor</span>
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
                <template x-for="step in steps" :key="step.id">
                    <div class="relative z-10 flex flex-col items-center gap-1 sm:gap-2">
                        <div class="w-8 h-8 sm:w-10 sm:h-10 rounded-full flex items-center justify-center font-bold text-[11px] sm:text-[13px] transition-all duration-300"
                             :class="currentStep === step.id
                                ? 'bg-adzkia-blue text-white shadow-lg shadow-adzkia-blue/30 scale-110'
                                : (step.id < currentStep
                                    ? 'bg-green-500 text-white border-2 border-green-500'
                                    : 'bg-white border-2 border-gray-100 text-gray-400')">
                            <span x-show="step.id < currentStep"><i data-feather="check" class="w-3 h-3 sm:w-4 sm:h-4"></i></span>
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

    {{-- ============================================================ --}}
    {{-- MAIN                                                          --}}
    {{-- ============================================================ --}}
    <main class="flex-1 max-w-4xl mx-auto w-full px-4 sm:px-6 py-6 sm:py-10">

        <div class="mb-6 sm:mb-8">
            <span class="inline-block px-3 py-1 bg-adzkia-badge-bg text-adzkia-blue rounded-lg text-[10px] sm:text-[11px] font-black uppercase tracking-widest mb-2 sm:mb-3">STEP 04 / 07</span>
            <h1 class="text-2xl sm:text-3xl font-black text-adzkia-dark tracking-tight">Formulir Biodata Diri</h1>
            <p class="text-[13px] sm:text-[14px] font-medium text-gray-500 mt-1.5 sm:mt-2">Lengkapi data pribadi, pilihan program studi, jalur pendaftaran, dan unggah dokumen syarat.</p>
        </div>

        {{-- FORM CARD --}}
        <div class="bg-white p-5 sm:p-8 md:p-12 rounded-2xl sm:rounded-[2rem] shadow-sm border border-gray-100 mb-8 sm:mb-10">

            @php $isLocked = $pendaftar->status_pendaftaran !== 'Draft' && $pendaftar->status_pendaftaran !== 'Revisi'; @endphp

            @if($isLocked)
                <div class="bg-amber-50 border-l-4 border-amber-500 p-4 sm:p-5 rounded-r-2xl mb-6 sm:mb-8 flex items-start gap-3 sm:gap-4">
                    <i data-feather="lock" class="w-5 h-5 sm:w-6 sm:h-6 text-amber-500 shrink-0 mt-0.5"></i>
                    <div>
                        <h3 class="text-[13px] sm:text-[14px] font-black text-amber-800 uppercase tracking-widest mb-1">Biodata Terkunci</h3>
                        <p class="text-[12px] sm:text-[13px] font-medium text-amber-700/80 leading-relaxed">
                            Data biodata Anda sudah dikunci dan masuk dalam tahap verifikasi/kelulusan.
                        </p>
                    </div>
                </div>
            @endif

            <form action="{{ route('simpan-biodata') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="pendaftar_id" value="{{ $pendaftar->id ?? '' }}">

                @if ($errors->any())
                    <div class="bg-red-50 border-l-4 border-adzkia-red p-4 mb-6 sm:mb-8 rounded-r-2xl">
                        <h3 class="text-xs sm:text-sm font-black text-adzkia-red uppercase tracking-widest mb-2">Oops! Ada yang kurang:</h3>
                        <ul class="text-[12px] sm:text-[13px] font-bold text-red-600 list-disc list-inside">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                {{-- ================================================ --}}
                {{-- BAGIAN 1: DATA DIRI                               --}}
                {{-- ================================================ --}}
                <section>
                    <div class="flex items-center gap-2 sm:gap-3 mb-4 sm:mb-6">
                        <div class="w-8 h-8 sm:w-10 sm:h-10 bg-adzkia-badge-bg text-adzkia-blue rounded-xl flex items-center justify-center shrink-0">
                            <i data-feather="user" class="w-4 h-4 sm:w-5 sm:h-5"></i>
                        </div>
                        <h2 class="text-base sm:text-lg font-black text-adzkia-dark">Data Diri</h2>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 sm:gap-6">
                        <div class="md:col-span-2">
                            <label class="block text-[10px] sm:text-[11px] font-black text-gray-400 uppercase tracking-widest mb-1.5 sm:mb-2 px-1">Nama Lengkap</label>
                            <input type="text" name="nama_lengkap"
                                   value="{{ old('nama_lengkap', $pendaftar->nama_lengkap ?? '') }}"
                                   placeholder="Masukkan nama sesuai Ijazah" required
                                   class="w-full px-4 sm:px-5 py-3.5 sm:py-4 bg-gray-50 border border-transparent rounded-2xl outline-none focus:border-adzkia-blue focus:bg-white transition-all font-bold text-[13px] sm:text-[14px] text-adzkia-dark disabled:opacity-50 disabled:cursor-not-allowed"
                                   {{ $isLocked ? 'disabled' : '' }}>
                        </div>

                        <div>
                            <label class="block text-[10px] sm:text-[11px] font-black text-gray-400 uppercase tracking-widest mb-1.5 sm:mb-2 px-1">NIK (Nomor Induk Kependudukan)</label>
                            <input type="text" name="nik"
                                   value="{{ old('nik', $pendaftar->nik ?? '') }}"
                                   placeholder="16 digit NIK" required
                                   class="w-full px-4 sm:px-5 py-3.5 sm:py-4 bg-gray-50 border border-transparent rounded-2xl outline-none focus:border-adzkia-blue focus:bg-white transition-all font-bold text-[13px] sm:text-[14px] text-adzkia-dark disabled:opacity-50 disabled:cursor-not-allowed"
                                   {{ $isLocked ? 'disabled' : '' }}>
                        </div>

                        <div>
                            <label class="block text-[10px] sm:text-[11px] font-black text-gray-400 uppercase tracking-widest mb-1.5 sm:mb-2 px-1">Agama</label>
                            <div class="relative">
                                <select name="agama" required
                                        class="w-full px-4 sm:px-5 py-3.5 sm:py-4 bg-gray-50 border border-transparent rounded-2xl outline-none focus:border-adzkia-blue focus:bg-white transition-all font-bold text-[13px] sm:text-[14px] text-adzkia-dark appearance-none cursor-pointer disabled:opacity-50 disabled:cursor-not-allowed"
                                        {{ $isLocked ? 'disabled' : '' }}>
                                    <option value="" disabled {{ empty($pendaftar->agama) ? 'selected' : '' }}>Pilih Agama</option>
                                    @foreach(['Islam','Kristen Protestan','Katolik','Hindu','Buddha','Konghucu'] as $agama)
                                        <option value="{{ $agama }}" {{ old('agama', $pendaftar->agama ?? '') == $agama ? 'selected' : '' }}>{{ $agama }}</option>
                                    @endforeach
                                </select>
                                @if(!$isLocked)
                                    <i data-feather="chevron-down" class="w-4 h-4 text-gray-400 absolute right-4 sm:right-5 top-1/2 -translate-y-1/2 pointer-events-none"></i>
                                @endif
                            </div>
                        </div>

                        <div>
                            <label class="block text-[10px] sm:text-[11px] font-black text-gray-400 uppercase tracking-widest mb-1.5 sm:mb-2 px-1">Tempat Lahir</label>
                            <input type="text" name="tempat_lahir"
                                   value="{{ old('tempat_lahir', $pendaftar->tempat_lahir ?? '') }}"
                                   placeholder="Kota Kelahiran"
                                   class="w-full px-4 sm:px-5 py-3.5 sm:py-4 bg-gray-50 border border-transparent rounded-2xl outline-none focus:border-adzkia-blue focus:bg-white transition-all font-bold text-[13px] sm:text-[14px] text-adzkia-dark disabled:opacity-50 disabled:cursor-not-allowed"
                                   {{ $isLocked ? 'disabled' : '' }}>
                        </div>

                        <div>
                            <label class="block text-[10px] sm:text-[11px] font-black text-gray-400 uppercase tracking-widest mb-1.5 sm:mb-2 px-1">Tanggal Lahir</label>
                            <input type="date" name="tanggal_lahir"
                                   value="{{ old('tanggal_lahir', $pendaftar->tanggal_lahir ?? '') }}" required
                                   class="w-full px-4 sm:px-5 py-3.5 sm:py-4 bg-gray-50 border border-transparent rounded-2xl outline-none focus:border-adzkia-blue focus:bg-white transition-all font-bold text-[13px] sm:text-[14px] text-gray-500 disabled:opacity-50 disabled:cursor-not-allowed"
                                   {{ $isLocked ? 'disabled' : '' }}>
                        </div>

                        <div class="md:col-span-2">
                            <label class="block text-[10px] sm:text-[11px] font-black text-gray-400 uppercase tracking-widest mb-2 sm:mb-3 px-1">Jenis Kelamin</label>
                            <div class="flex gap-4 sm:gap-6 px-1">
                                @foreach(['Laki-laki', 'Perempuan'] as $gender)
                                    <label class="flex items-center gap-2 sm:gap-3 cursor-pointer group {{ $isLocked ? 'cursor-not-allowed opacity-50' : '' }}">
                                        <div class="w-5 h-5 rounded-full border-2 border-gray-300 flex items-center justify-center transition-colors relative {{ !$isLocked ? 'group-hover:border-adzkia-blue' : '' }}">
                                            <input type="radio" name="gender" value="{{ $gender }}" class="peer sr-only"
                                                   {{ old('gender', $pendaftar->gender ?? '') == $gender ? 'checked' : '' }}
                                                   {{ $isLocked ? 'disabled' : '' }}>
                                            <div class="w-2.5 h-2.5 bg-adzkia-blue rounded-full opacity-0 peer-checked:opacity-100 transition-opacity"></div>
                                        </div>
                                        <span class="text-[13px] sm:text-[14px] font-bold text-adzkia-dark">{{ $gender }}</span>
                                    </label>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </section>

                <hr class="border-gray-100 my-7 sm:my-10">

                {{-- ================================================ --}}
                {{-- BAGIAN 2: KONTAK & WILAYAH                        --}}
                {{-- ================================================ --}}
                <section>
                    <div class="flex items-center gap-2 sm:gap-3 mb-4 sm:mb-6">
                        <div class="w-8 h-8 sm:w-10 sm:h-10 bg-adzkia-badge-bg text-adzkia-blue rounded-xl flex items-center justify-center shrink-0">
                            <i data-feather="map-pin" class="w-4 h-4 sm:w-5 sm:h-5"></i>
                        </div>
                        <h2 class="text-base sm:text-lg font-black text-adzkia-dark">Kontak & Wilayah</h2>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 sm:gap-6">
                        <div>
                            <label class="block text-[10px] sm:text-[11px] font-black text-gray-400 uppercase tracking-widest mb-1.5 sm:mb-2 px-1">Email Aktif</label>
                            <input type="email" name="email"
                                   value="{{ old('email', $pendaftar->email ?? '') }}" placeholder="example@email.com"
                                   class="w-full px-4 sm:px-5 py-3.5 sm:py-4 bg-gray-50 border border-transparent rounded-2xl outline-none focus:border-adzkia-blue focus:bg-white transition-all font-bold text-[13px] sm:text-[14px] text-adzkia-dark disabled:opacity-50 disabled:cursor-not-allowed"
                                   {{ $isLocked ? 'disabled' : '' }}>
                        </div>

                        <div>
                            <label class="block text-[10px] sm:text-[11px] font-black text-gray-400 uppercase tracking-widest mb-1.5 sm:mb-2 px-1">No. HP / WhatsApp</label>
                            <input type="text" name="no_whatsapp"
                                   value="{{ old('no_whatsapp', $pendaftar->no_whatsapp ?? '') }}" placeholder="0812xxxx"
                                   class="w-full px-4 sm:px-5 py-3.5 sm:py-4 bg-gray-50 border border-transparent rounded-2xl outline-none focus:border-adzkia-blue focus:bg-white transition-all font-bold text-[13px] sm:text-[14px] text-adzkia-dark disabled:opacity-50 disabled:cursor-not-allowed"
                                   {{ $isLocked ? 'disabled' : '' }}>
                        </div>

                        <div>
                            <label class="block text-[10px] sm:text-[11px] font-black text-gray-400 uppercase tracking-widest mb-1.5 sm:mb-2 px-1">Provinsi</label>
                            <div class="relative">
                                <select name="provinsi" x-model="selectedProv" @change="loadCities(selectedProv)" required
                                        class="w-full px-4 sm:px-5 py-3.5 sm:py-4 bg-gray-50 border border-transparent rounded-2xl outline-none focus:border-adzkia-blue focus:bg-white transition-all font-bold text-[13px] sm:text-[14px] text-adzkia-dark appearance-none cursor-pointer disabled:opacity-50 disabled:cursor-not-allowed"
                                        {{ $isLocked ? 'disabled' : '' }}>
                                    <option value="" disabled>Pilih Provinsi</option>
                                    <template x-for="prov in provinces" :key="prov.code">
                                        <option :value="prov.code" x-text="prov.name"></option>
                                    </template>
                                </select>
                                @if(!$isLocked)
                                    <i data-feather="chevron-down" class="w-4 h-4 text-gray-400 absolute right-4 sm:right-5 top-1/2 -translate-y-1/2 pointer-events-none"></i>
                                @endif
                            </div>
                        </div>

                        <div>
                            <label class="block text-[10px] sm:text-[11px] font-black text-gray-400 uppercase tracking-widest mb-1.5 sm:mb-2 px-1">Kota / Kabupaten</label>
                            <div class="relative">
                                <select name="kota_kabupaten" x-model="selectedCity" required
                                        class="w-full px-4 sm:px-5 py-3.5 sm:py-4 bg-gray-50 border border-transparent rounded-2xl outline-none focus:border-adzkia-blue focus:bg-white transition-all font-bold text-[13px] sm:text-[14px] text-adzkia-dark appearance-none cursor-pointer disabled:opacity-50 disabled:cursor-not-allowed"
                                        {{ $isLocked ? 'disabled' : '' }}>
                                    <option value="" disabled>Pilih Kota</option>
                                    <template x-for="city in cities" :key="city.code">
                                        <option :value="city.name" x-text="city.name"></option>
                                    </template>
                                </select>
                                @if(!$isLocked)
                                    <i data-feather="chevron-down" class="w-4 h-4 text-gray-400 absolute right-4 sm:right-5 top-1/2 -translate-y-1/2 pointer-events-none"></i>
                                @endif
                            </div>
                        </div>

                        <div class="md:col-span-2">
                            <label class="block text-[10px] sm:text-[11px] font-black text-gray-400 uppercase tracking-widest mb-1.5 sm:mb-2 px-1">Alamat Lengkap</label>
                            <textarea name="alamat_rumah" rows="3" placeholder="Jl. Sudirman No 123, RT 01 RW 02..."
                                      class="w-full px-4 sm:px-5 py-3.5 sm:py-4 bg-gray-50 border border-transparent rounded-2xl outline-none focus:border-adzkia-blue focus:bg-white transition-all font-bold text-[13px] sm:text-[14px] text-adzkia-dark resize-none disabled:opacity-50 disabled:cursor-not-allowed"
                                      {{ $isLocked ? 'disabled' : '' }}>{{ old('alamat_rumah', $pendaftar->alamat_rumah ?? '') }}</textarea>
                        </div>
                    </div>
                </section>

                <hr class="border-gray-100 my-7 sm:my-10">

                {{-- ================================================ --}}
                {{-- BAGIAN 3: ASAL PENDIDIKAN                         --}}
                {{-- ================================================ --}}
                <section>
                    <div class="flex items-center gap-2 sm:gap-3 mb-4 sm:mb-6">
                        <div class="w-8 h-8 sm:w-10 sm:h-10 bg-adzkia-badge-bg text-adzkia-blue rounded-xl flex items-center justify-center shrink-0">
                            <i data-feather="book" class="w-4 h-4 sm:w-5 sm:h-5"></i>
                        </div>
                        <h2 class="text-base sm:text-lg font-black text-adzkia-dark">Asal Pendidikan</h2>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 sm:gap-6">
                        <div class="sm:col-span-2">
                            <label class="block text-[10px] sm:text-[11px] font-black text-gray-400 uppercase tracking-widest mb-1.5 sm:mb-2 px-1">Asal Sekolah</label>
                            <input type="text" name="sekolah_asal"
                                   value="{{ old('sekolah_asal', $pendaftar->sekolah_asal ?? '') }}" placeholder="Contoh: SMAN 1 Padang"
                                   class="w-full px-4 sm:px-5 py-3.5 sm:py-4 bg-gray-50 border border-transparent rounded-2xl outline-none focus:border-adzkia-blue focus:bg-white transition-all font-bold text-[13px] sm:text-[14px] text-adzkia-dark disabled:opacity-50 disabled:cursor-not-allowed"
                                   {{ $isLocked ? 'disabled' : '' }}>
                        </div>

                        <div>
                            <label class="block text-[10px] sm:text-[11px] font-black text-gray-400 uppercase tracking-widest mb-1.5 sm:mb-2 px-1">Jurusan di Sekolah</label>
                            <input type="text" name="jurusan_sma"
                                   value="{{ old('jurusan_sma', $pendaftar->jurusan_sma ?? '') }}" placeholder="MIPA / IPS / RPL"
                                   class="w-full px-4 sm:px-5 py-3.5 sm:py-4 bg-gray-50 border border-transparent rounded-2xl outline-none focus:border-adzkia-blue focus:bg-white transition-all font-bold text-[13px] sm:text-[14px] text-adzkia-dark disabled:opacity-50 disabled:cursor-not-allowed"
                                   {{ $isLocked ? 'disabled' : '' }}>
                        </div>

                        <div>
                            <label class="block text-[10px] sm:text-[11px] font-black text-gray-400 uppercase tracking-widest mb-1.5 sm:mb-2 px-1">Tahun Lulus</label>
                            <input type="number" name="tahun_lulus"
                                   value="{{ old('tahun_lulus', $pendaftar->tahun_lulus ?? '') }}" placeholder="2024"
                                   class="w-full px-4 sm:px-5 py-3.5 sm:py-4 bg-gray-50 border border-transparent rounded-2xl outline-none focus:border-adzkia-blue focus:bg-white transition-all font-bold text-[13px] sm:text-[14px] text-adzkia-dark disabled:opacity-50 disabled:cursor-not-allowed"
                                   {{ $isLocked ? 'disabled' : '' }}>
                        </div>

                        <div class="sm:col-span-2">
                            <label class="block text-[10px] sm:text-[11px] font-black text-gray-400 uppercase tracking-widest mb-1.5 sm:mb-2 px-1">Rata-rata Nilai Akhir</label>
                            <input type="number" name="nilai_akhir"
                                   value="{{ old('nilai_akhir', $pendaftar->nilai_akhir ?? '') }}"
                                   placeholder="85.50" required min="0" max="100" step="0.01"
                                   class="w-full px-4 sm:px-5 py-3.5 sm:py-4 bg-gray-50 border border-transparent rounded-2xl outline-none focus:border-adzkia-blue focus:bg-white transition-all font-bold text-[13px] sm:text-[14px] text-adzkia-dark disabled:opacity-50 disabled:cursor-not-allowed"
                                   {{ $isLocked ? 'disabled' : '' }}>
                        </div>
                    </div>
                </section>

                {{-- ================================================ --}}
                {{-- BAGIAN 4: PILIHAN PRODI & JALUR                   --}}
                {{-- ================================================ --}}
                <section class="bg-[#F8FAFC] p-5 sm:p-8 md:p-10 rounded-2xl sm:rounded-[2rem] border border-gray-100 my-7 sm:my-10">
                    <div class="flex items-center gap-2 sm:gap-3 mb-4 sm:mb-6">
                        <div class="w-8 h-8 sm:w-10 sm:h-10 bg-white shadow-sm text-adzkia-blue rounded-xl flex items-center justify-center shrink-0">
                            <i data-feather="target" class="w-4 h-4 sm:w-5 sm:h-5"></i>
                        </div>
                        <h2 class="text-base sm:text-lg font-black text-adzkia-dark">Pilihan Program Studi & Jalur</h2>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 sm:gap-6">
                        {{-- Pilihan Jurusan 1 --}}
                        <div>
                            <label class="block text-[10px] sm:text-[11px] font-black text-gray-400 uppercase tracking-widest mb-1.5 sm:mb-2 px-1">Pilihan Jurusan 1 (Utama)</label>
                            <div class="relative">
                                <select name="pilihan_jurusan_1" required
                                        class="w-full px-4 sm:px-5 py-3.5 sm:py-4 bg-white border border-gray-200 rounded-2xl outline-none focus:border-adzkia-blue transition-all font-bold text-[13px] sm:text-[14px] text-adzkia-dark appearance-none shadow-sm disabled:opacity-50 disabled:cursor-not-allowed"
                                        {{ $isLocked ? 'disabled' : 'cursor-pointer' }}>
                                    <option value="" disabled>Pilih Jurusan Utama</option>
                                    @foreach($prodis as $prodi)
                                        <option value="{{ $prodi->nama }}" {{ old('pilihan_jurusan_1', $pendaftar->pilihan_jurusan_1 ?? '') == $prodi->nama ? 'selected' : '' }}>{{ $prodi->nama }}</option>
                                    @endforeach
                                </select>
                                @if(!$isLocked)
                                    <i data-feather="chevron-down" class="w-4 h-4 sm:w-5 sm:h-5 text-gray-400 absolute right-4 sm:right-5 top-1/2 -translate-y-1/2 pointer-events-none"></i>
                                @endif
                            </div>
                        </div>

                        {{-- Pilihan Jurusan 2 --}}
                        <div>
                            <label class="block text-[10px] sm:text-[11px] font-black text-gray-400 uppercase tracking-widest mb-1.5 sm:mb-2 px-1">Pilihan Jurusan 2 (Cadangan)</label>
                            <div class="relative">
                                <select name="pilihan_jurusan_2" required
                                        class="w-full px-4 sm:px-5 py-3.5 sm:py-4 bg-white border border-gray-200 rounded-2xl outline-none focus:border-adzkia-blue transition-all font-bold text-[13px] sm:text-[14px] text-adzkia-dark appearance-none shadow-sm disabled:opacity-50 disabled:cursor-not-allowed"
                                        {{ $isLocked ? 'disabled' : 'cursor-pointer' }}>
                                    <option value="" disabled>Pilih Jurusan Alternatif</option>
                                    @foreach($prodis as $prodi)
                                        <option value="{{ $prodi->nama }}" {{ old('pilihan_jurusan_2', $pendaftar->pilihan_jurusan_2 ?? '') == $prodi->nama ? 'selected' : '' }}>{{ $prodi->nama }}</option>
                                    @endforeach
                                </select>
                                @if(!$isLocked)
                                    <i data-feather="chevron-down" class="w-4 h-4 sm:w-5 sm:h-5 text-gray-400 absolute right-4 sm:right-5 top-1/2 -translate-y-1/2 pointer-events-none"></i>
                                @endif
                            </div>
                        </div>

                        {{-- -------- JALUR PENDAFTARAN (BARU) -------- --}}
                        <div class="md:col-span-2">
                            <label class="block text-[10px] sm:text-[11px] font-black text-gray-400 uppercase tracking-widest mb-1.5 sm:mb-2 px-1">Jalur Pendaftaran</label>
                            <div class="relative">
                                <select name="jalur_id" x-model="selectedJalurId"
                                        @change="onJalurChange()"
                                        required
                                        class="w-full px-4 sm:px-5 py-3.5 sm:py-4 bg-white border border-gray-200 rounded-2xl outline-none focus:border-adzkia-blue transition-all font-bold text-[13px] sm:text-[14px] text-adzkia-dark appearance-none shadow-sm disabled:opacity-50 disabled:cursor-not-allowed"
                                        {{ $isLocked ? 'disabled' : 'cursor-pointer' }}>
                                    <option value="">— Pilih Jalur Pendaftaran —</option>
                                    @foreach($jalurs as $jalur)
                                        <option value="{{ $jalur->id }}"
                                                {{ old('jalur_id', $pendaftar->jalur_id ?? '') == $jalur->id ? 'selected' : '' }}>
                                            {{ $jalur->nama_jalur }}
                                            {{ $jalur->is_free_registration ? '(Gratis)' : '' }}
                                        </option>
                                    @endforeach
                                </select>
                                @if(!$isLocked)
                                    <i data-feather="chevron-down" class="w-4 h-4 sm:w-5 sm:h-5 text-gray-400 absolute right-4 sm:right-5 top-1/2 -translate-y-1/2 pointer-events-none"></i>
                                @endif
                            </div>

                            {{-- Info badge jalur yang dipilih --}}
                            <div x-show="selectedJalur" x-cloak class="mt-3 space-y-2">

                                {{-- Baris dokumen --}}
                                <div class="flex items-center gap-2 text-[12px] font-bold text-adzkia-blue bg-adzkia-badge-bg px-4 py-2.5 rounded-xl">
                                    <i data-feather="info" class="w-4 h-4 shrink-0"></i>
                                    <span>Jalur ini memerlukan <strong x-text="selectedJalur ? selectedJalur.dokumen_syarat.length : 0"></strong> dokumen syarat. Pastikan semua dokumen Anda siap sebelum mengunggah.</span>
                                </div>

                                {{-- Badge: Gratis --}}
                                <div x-show="selectedJalur && selectedJalur.is_free_registration" x-cloak
                                     class="flex items-center gap-2 text-[12px] font-bold text-green-700 bg-green-50 border border-green-200 px-4 py-2.5 rounded-xl">
                                    <i data-feather="check-circle" class="w-4 h-4 shrink-0 text-green-500"></i>
                                    <span>Jalur ini <strong>GRATIS</strong> — setelah biodata tersimpan, Anda langsung diarahkan ke halaman konfirmasi data.</span>
                                </div>

                                {{-- Badge: Berbayar --}}
                                <div x-show="selectedJalur && !selectedJalur.is_free_registration" x-cloak
                                     class="flex items-center gap-2 text-[12px] font-bold text-amber-700 bg-amber-50 border border-amber-200 px-4 py-2.5 rounded-xl">
                                    <i data-feather="credit-card" class="w-4 h-4 shrink-0 text-amber-500"></i>
                                    <span>Jalur ini <strong>berbayar</strong> — setelah biodata tersimpan, Anda akan diarahkan ke halaman unggah bukti pembayaran.</span>
                                </div>

                                {{-- Badge: Ada ujian --}}
                                <div x-show="selectedJalur && selectedJalur.has_exam" x-cloak
                                     class="flex items-center gap-2 text-[12px] font-bold text-purple-700 bg-purple-50 border border-purple-200 px-4 py-2.5 rounded-xl">
                                    <i data-feather="edit-3" class="w-4 h-4 shrink-0 text-purple-500"></i>
                                    <span>Jalur ini mensyaratkan <strong>ujian masuk</strong>. Jadwal ujian akan diinformasikan setelah verifikasi berkas.</span>
                                </div>

                            </div>
                        </div>
                    </div>
                </section>

                {{-- ================================================ --}}
                {{-- BAGIAN 5: UPLOAD DOKUMEN DINAMIS (Alpine.js)      --}}
                {{-- ================================================ --}}
                <section>
                    <div class="flex items-center gap-2 sm:gap-3 mb-1 sm:mb-2">
                        <div class="w-8 h-8 sm:w-10 sm:h-10 bg-adzkia-badge-bg text-adzkia-blue rounded-xl flex items-center justify-center shrink-0">
                            <i data-feather="upload-cloud" class="w-4 h-4 sm:w-5 sm:h-5"></i>
                        </div>
                        <h2 class="text-base sm:text-lg font-black text-adzkia-dark">Unggah Dokumen Syarat</h2>
                    </div>
                    <p class="text-[12px] font-medium text-gray-400 mb-4 sm:mb-6 pl-11 sm:pl-14">
                        Daftar dokumen berubah otomatis sesuai jalur pendaftaran yang dipilih.
                    </p>

                    {{-- State: Belum pilih jalur --}}
                    <div x-show="!selectedJalur" x-cloak
                         class="border-2 border-dashed border-gray-200 rounded-2xl p-10 flex flex-col items-center justify-center text-center text-gray-400">
                        <i data-feather="layers" class="w-8 h-8 mb-3 text-gray-300"></i>
                        <p class="text-[13px] font-bold">Pilih jalur pendaftaran terlebih dahulu</p>
                        <p class="text-[11px] font-medium mt-1">Dokumen yang harus diunggah akan muncul di sini.</p>
                    </div>

                    {{-- State: Sudah pilih jalur → render kartu upload per dokumen --}}
                    <div x-show="selectedJalur" x-cloak
                         class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 sm:gap-6">

                        <template x-for="(dokumen, index) in selectedJalur ? selectedJalur.dokumen_syarat : []" :key="dokumen">
                            <div class="border-2 border-dashed rounded-2xl p-4 sm:p-6 flex flex-col items-center justify-center text-center transition-all group overflow-hidden relative"
                                 :class="uploadedFiles[dokumen]
                                    ? 'bg-gray-50 border-gray-300'
                                    : '{{ $isLocked ? 'border-gray-200 cursor-not-allowed opacity-60' : 'border-gray-200 hover:border-adzkia-blue hover:bg-blue-50 cursor-pointer' }}'"
                                 @click="if(!uploadedFiles[dokumen] && !{{ $isLocked ? 'true' : 'false' }}) triggerUpload(dokumen)">

                                {{-- Hidden file input --}}
                                <input type="file"
                                       :name="'doc_' + slugify(dokumen)"
                                       :id="'input_' + slugify(dokumen)"
                                       @change="handleDynamicUpload($event, dokumen)"
                                       class="hidden"
                                       accept=".jpg,.jpeg,.png,.pdf"
                                       {{ $isLocked ? 'disabled' : '' }}>

                                {{-- Sebelum upload --}}
                                <div x-show="!uploadedFiles[dokumen]" class="flex flex-col items-center">
                                    <i data-feather="file-plus" class="w-6 h-6 text-gray-400 mb-2 sm:mb-3 {{ !$isLocked ? 'group-hover:text-adzkia-blue transition-colors' : '' }}"></i>
                                    <h4 class="text-[12px] sm:text-[13px] font-extrabold text-adzkia-dark mb-1" x-text="dokumen"></h4>
                                    <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-2 sm:mb-3">PDF/JPG/PNG · Max 2MB</p>
                                    @if(!$isLocked)
                                        <span class="text-[12px] font-black text-adzkia-blue underline underline-offset-2">Pilih File</span>
                                    @endif
                                </div>

                                {{-- Sesudah upload --}}
                                <div x-show="uploadedFiles[dokumen]" class="flex flex-col items-center w-full min-w-0" x-cloak>
                                    <div class="w-9 h-9 sm:w-10 sm:h-10 bg-adzkia-blue text-white rounded-full flex items-center justify-center mb-2 shrink-0">
                                        <i data-feather="check" class="w-4 h-4 sm:w-5 sm:h-5"></i>
                                    </div>
                                    <h4 class="text-[11px] font-extrabold text-adzkia-dark w-full px-2 text-center truncate" x-text="uploadedFiles[dokumen]"></h4>
                                    <div class="flex gap-2 mt-2 sm:mt-3">
                                        {{-- Tombol "Lihat" hanya tampil jika file sudah ada di DB --}}
                                        <template x-if="berkasDb[dokumen]">
                                            <a :href="'/' + berkasDb[dokumen]" target="_blank"
                                               class="text-[11px] font-bold text-adzkia-blue bg-blue-100 px-3 py-1.5 rounded-lg hover:bg-blue-200 transition-colors"
                                               @click.stop>Lihat File</a>
                                        </template>
                                        @if(!$isLocked)
                                            <button type="button"
                                                    @click.stop="triggerUpload(dokumen)"
                                                    class="text-[11px] font-bold text-gray-600 bg-gray-200 px-3 py-1.5 rounded-lg hover:bg-gray-300 transition-colors">Ganti</button>
                                        @endif
                                    </div>
                                </div>

                            </div>
                        </template>
                    </div>
                </section>

                {{-- ================================================ --}}
                {{-- TOMBOL SUBMIT                                      --}}
                {{-- ================================================ --}}
                <div class="pt-7 sm:pt-10 border-t border-gray-100 flex flex-col items-center gap-3 sm:gap-6 mt-7 sm:mt-10">
                    @if(!$isLocked)
                        {{-- Label tombol berubah dinamis sesuai jenis jalur --}}
                        <button type="submit"
                                class="w-full py-3.5 sm:py-4 bg-adzkia-blue text-white rounded-2xl font-black text-[14px] sm:text-[15px] hover:bg-blue-700 shadow-lg shadow-blue-600/20 transition-all flex justify-center items-center gap-2 active:scale-[0.98]">
                            <span x-show="!selectedJalur">Simpan Biodata & Lanjutkan</span>
                            <span x-show="selectedJalur && selectedJalur.is_free_registration" x-cloak>
                                Simpan &amp; Lanjut ke Konfirmasi
                            </span>
                            <span x-show="selectedJalur && !selectedJalur.is_free_registration" x-cloak>
                                Simpan &amp; Lanjut ke Pembayaran
                            </span>
                            <i data-feather="arrow-right" class="w-4 h-4"></i>
                        </button>
                    @else
                        <a href="{{ route('konfirmasi-data', $pendaftar->id) }}"
                           class="w-full py-3.5 sm:py-4 bg-gray-100 text-adzkia-dark rounded-2xl font-black text-[14px] sm:text-[15px] hover:bg-gray-200 transition-all flex justify-center items-center gap-2">
                            Kembali ke Halaman Konfirmasi
                            <i data-feather="arrow-right" class="w-4 h-4"></i>
                        </a>
                    @endif
                </div>

            </form>
        </div>
    </main>

    {{-- ============================================================ --}}
    {{-- ALPINE.JS DATA & LOGIC                                        --}}
    {{-- ============================================================ --}}
    <script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('formulirApp', () => ({
            currentStep: 4,

            // --- Step progress ---
            steps: [
                { id: 1, title: 'Pendaftaran' }, { id: 2, title: 'Biaya' },
                { id: 3, title: 'Validasi' },    { id: 4, title: 'Biodata' },
                { id: 5, title: 'Dokumen' },     { id: 6, title: 'Ujian' },
                { id: 7, title: 'Hasil' }
            ],

            // --- Provinsi / kota ---
            provinces:    [],
            cities:       [],
            selectedProv: '',
            selectedCity: '',

            // --- JALUR PENDAFTARAN ---
            // jalursData dikirim dari controller (sudah di-map dengan key 'nama')
            // key: id, nama, is_free_registration, has_exam, dokumen_syarat
            jalursData:      {!! $jalursJson !!},
            selectedJalurId: '{{ old('jalur_id', $pendaftar->jalur_id ?? '') }}',
            selectedJalur:   null,

            // uploadedFiles: { "KTP": "ktp_file.jpg", "Rapor": "rapor.pdf", ... }
            uploadedFiles: {},

            // berkasDb: berkas yang sudah tersimpan di database (untuk tombol "Lihat File")
            berkasDb: {!! json_encode(
                is_array(json_decode($pendaftar->berkas_dokumen ?? '{}', true))
                    ? json_decode($pendaftar->berkas_dokumen ?? '{}', true)
                    : []
            ) !!},

            // --------------------------------------------------------
            // INIT
            // --------------------------------------------------------
            async init() {
                // Set jalur aktif berdasarkan nilai yang sudah tersimpan
                if (this.selectedJalurId) {
                    this.onJalurChange();
                }

                // Provinsi
                const dbProv = '{{ old('provinsi', $pendaftar->provinsi ?? '') }}';
                const dbCity = '{{ old('kota_kabupaten', $pendaftar->kota_kabupaten ?? '') }}';

                try {
                    const res = await fetch('/data/provinsi.json');
                    if (res.ok) {
                        const json = await res.json();
                        this.provinces = json.data || [];

                        this.$nextTick(async () => {
                            if (dbProv) {
                                this.selectedProv = dbProv;
                                await this.loadCities(dbProv);
                                this.$nextTick(() => { this.selectedCity = dbCity; });
                            }
                        });
                    }
                } catch (e) { console.error('Gagal load provinsi:', e); }
            },

            // --------------------------------------------------------
            // JALUR CHANGE — update selectedJalur & siapkan uploadedFiles
            // --------------------------------------------------------
            onJalurChange() {
                const id = parseInt(this.selectedJalurId);
                this.selectedJalur = this.jalursData.find(j => j.id === id) || null;

                if (!this.selectedJalur) return;

                // Inisialisasi uploadedFiles: isi dari DB jika ada, kosong jika belum
                // berkasDb key = nama dokumen (misal: "KTP", "Rapor Semester 3-5")
                this.uploadedFiles = {};
                this.selectedJalur.dokumen_syarat.forEach(dok => {
                    this.uploadedFiles[dok] = this.berkasDb[dok]
                        ? 'Sudah Diunggah'
                        : null;
                });

                // Rerender ikon feather setelah DOM update
                this.$nextTick(() => { feather.replace(); });
            },

            // --------------------------------------------------------
            // TRIGGER KLIK PADA INPUT FILE TERSEMBUNYI
            // --------------------------------------------------------
            triggerUpload(dokumen) {
                const inputId = 'input_' + this.slugify(dokumen);
                document.getElementById(inputId)?.click();
            },

            // --------------------------------------------------------
            // HANDLER UPLOAD DINAMIS
            // --------------------------------------------------------
            handleDynamicUpload(event, dokumen) {
                const file = event.target.files[0];
                if (!file) return;

                if (file.size > 2 * 1024 * 1024) {
                    alert('Ukuran file "' + dokumen + '" maksimal 2MB!');
                    event.target.value = '';
                    return;
                }

                const allowed = ['image/jpeg', 'image/png', 'image/jpg', 'application/pdf'];
                if (!allowed.includes(file.type)) {
                    alert('Format file "' + dokumen + '" harus JPG, PNG, atau PDF!');
                    event.target.value = '';
                    return;
                }

                this.uploadedFiles[dokumen] = file.name;
            },

            // --------------------------------------------------------
            // HELPER: slugify nama dokumen → cocok dengan field name di controller
            // "Rapor Semester 3-5" → "rapor_semester_3_5"
            // --------------------------------------------------------
            slugify(str) {
                return str.toLowerCase().replace(/[^a-z0-9]+/g, '_');
            },

            // --------------------------------------------------------
            // PROVINSI & KOTA
            // --------------------------------------------------------
            async loadCities(provinceId) {
                if (!provinceId) { this.cities = []; return; }
                try {
                    const res = await fetch(`/data/kabkota/${provinceId}.json`);
                    if (res.ok) {
                        const json = await res.json();
                        this.cities = json.data || [];
                    }
                } catch (e) { this.cities = []; }
            },
        }));
    });

    document.addEventListener('DOMContentLoaded', () => { feather.replace(); });
    </script>
</body>
</html>