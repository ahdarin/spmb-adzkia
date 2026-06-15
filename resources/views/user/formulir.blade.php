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
                        'adzkia-red': '#d9241c',
                        'adzkia-blue': '#2c7ebd',
                        'adzkia-dark': '#1e293b',
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
<body class="bg-gray-50 antialiased text-adzkia-dark min-h-screen flex flex-col" x-data="formulirApp()">

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
                    <p class="text-[11px] font-bold text-gray-400">ID: {{ $pendaftar->no_pendaftaran ?? 'ID Kosong' }}</p>
                </div>
                <img src="https://ui-avatars.com/api/?name={{ urlencode(session('nama_pendaftar')) }}&background=1e293b&color=fff" class="w-10 h-10 rounded-full border-2 border-gray-100">
            </div>
        </div>
    </nav>

    {{-- STEP PROGRESS TRACKER --}}
    <div class="w-full bg-white py-6 border-b border-gray-100 z-20">
        <div class="max-w-5xl mx-auto px-6">
            <div class="flex items-center justify-between relative">
                <div class="absolute top-1/2 left-0 w-full h-0.5 bg-gray-100 -translate-y-1/2 z-0"></div>
                <template x-for="step in steps" :key="step.id">
                    <div class="relative z-10 flex flex-col items-center gap-2">
                        <div class="w-10 h-10 rounded-full flex items-center justify-center font-bold text-[13px] transition-all duration-300"
                             :class="currentStep === step.id ? 'bg-adzkia-blue text-white shadow-lg shadow-adzkia-blue/30 scale-110' : (step.id < currentStep ? 'bg-green-500 text-white border-2 border-green-500' : 'bg-white border-2 border-gray-100 text-gray-400')">
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
        <div class="mb-8 text-center md:text-left">
            <span class="inline-block px-3 py-1 bg-adzkia-badge-bg text-adzkia-blue rounded-lg text-[11px] font-black uppercase tracking-widest mb-3">STEP 04 / 07</span>
            <h1 class="text-3xl font-black text-adzkia-dark tracking-tight">Formulir Biodata Diri</h1>
            <p class="text-[14px] font-medium text-gray-500 mt-2">Lengkapi detail profil akademik dan data pribadi Anda dengan valid dan benar.</p>
        </div>

<div class="bg-white p-8 md:p-12 rounded-[2rem] shadow-sm border border-gray-100 mb-10">
            
            @php
                // Tentukan status terkunci: Jika bukan 'Draft', berarti form terkunci
                $isLocked = $pendaftar->status_pendaftaran !== 'Draft';
            @endphp

            @if($isLocked)
                {{-- BANNER PERINGATAN BILA DATA SUDAH DIKUNCI --}}
                <div class="bg-amber-50 border-l-4 border-amber-500 p-5 rounded-r-2xl mb-8 flex items-start gap-4">
                    <i data-feather="lock" class="w-6 h-6 text-amber-500 shrink-0 mt-0.5"></i>
                    <div>
                        <h3 class="text-[14px] font-black text-amber-800 uppercase tracking-widest mb-1">Biodata Terkunci</h3>
                        <p class="text-[13px] font-medium text-amber-700/80 leading-relaxed">
                            Data biodata Anda sudah dikunci dan masuk dalam tahap verifikasi/kelulusan. Anda tidak dapat lagi mengubah data diri maupun mengunggah ulang berkas persyaratan.
                        </p>
                    </div>
                </div>
            @endif

            <form action="{{ route('simpan-biodata') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="pendaftar_id" value="{{ $pendaftar->id ?? '' }}">
                
                @if ($errors->any())
                    <div class="bg-red-50 border-l-4 border-adzkia-red p-4 mb-8 rounded-r-2xl">
                        <h3 class="text-sm font-black text-adzkia-red uppercase tracking-widest mb-2">Oops! Ada yang kurang:</h3>
                        <ul class="text-[13px] font-bold text-red-600 list-disc list-inside">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
                
                {{-- BAGIAN 1: DATA DIRI --}}
                <section>
                    <div class="flex items-center gap-3 mb-6">
                        <div class="w-10 h-10 bg-adzkia-badge-bg text-adzkia-blue rounded-xl flex items-center justify-center">
                            <i data-feather="user" class="w-5 h-5"></i>
                        </div>
                        <h2 class="text-lg font-black text-adzkia-dark">Data Diri</h2>
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="md:col-span-2">
                            <label class="block text-[11px] font-black text-gray-400 uppercase tracking-widest mb-2 px-1">Nama Lengkap</label>
                            <input type="text" name="nama_lengkap" value="{{ old('nama_lengkap', $pendaftar->nama_lengkap ?? '') }}" placeholder="Masukkan nama sesuai Ijazah" required class="w-full px-5 py-4 bg-gray-50 border border-transparent rounded-2xl outline-none focus:border-adzkia-blue focus:bg-white transition-all font-bold text-[14px] text-adzkia-dark disabled:opacity-50 disabled:cursor-not-allowed" {{ $isLocked ? 'disabled' : '' }}>
                        </div>
                        
                        <div>
                            <label class="block text-[11px] font-black text-gray-400 uppercase tracking-widest mb-2 px-1">NIK (Nomor Induk Kependudukan)</label>
                            <input type="text" name="nik" value="{{ old('nik', $pendaftar->nik ?? '') }}" placeholder="16 digit NIK" required class="w-full px-5 py-4 bg-gray-50 border border-transparent rounded-2xl outline-none focus:border-adzkia-blue focus:bg-white transition-all font-bold text-[14px] text-adzkia-dark disabled:opacity-50 disabled:cursor-not-allowed" {{ $isLocked ? 'disabled' : '' }}>
                        </div>
                        
                        <div>
                            <label class="block text-[11px] font-black text-gray-400 uppercase tracking-widest mb-2 px-1">Agama</label>
                            <div class="relative">
                                <select name="agama" required class="w-full px-5 py-4 bg-gray-50 border border-transparent rounded-2xl outline-none focus:border-adzkia-blue focus:bg-white transition-all font-bold text-[14px] text-adzkia-dark appearance-none cursor-pointer disabled:opacity-50 disabled:cursor-not-allowed" {{ $isLocked ? 'disabled' : '' }}>
                                    <option value="" disabled {{ empty($pendaftar->agama) ? 'selected' : '' }}>Pilih Agama</option>
                                    @foreach(['Islam', 'Kristen Protestan', 'Katolik', 'Hindu', 'Buddha', 'Konghucu'] as $agama)
                                        <option value="{{ $agama }}" {{ old('agama', $pendaftar->agama ?? '') == $agama ? 'selected' : '' }}>{{ $agama }}</option>
                                    @endforeach
                                </select>
                                @if(!$isLocked)
                                    <i data-feather="chevron-down" class="w-4 h-4 text-gray-400 absolute right-5 top-1/2 -translate-y-1/2 pointer-events-none"></i>
                                @endif
                            </div>
                        </div>

                        <div>
                            <label class="block text-[11px] font-black text-gray-400 uppercase tracking-widest mb-2 px-1">Tempat Lahir</label>
                            <input type="text" name="tempat_lahir" value="{{ old('tempat_lahir', $pendaftar->tempat_lahir ?? '') }}" placeholder="Kota Kelahiran" class="w-full px-5 py-4 bg-gray-50 border border-transparent rounded-2xl outline-none focus:border-adzkia-blue focus:bg-white transition-all font-bold text-[14px] text-adzkia-dark disabled:opacity-50 disabled:cursor-not-allowed" {{ $isLocked ? 'disabled' : '' }}>
                        </div>

                        <div>
                            <label class="block text-[11px] font-black text-gray-400 uppercase tracking-widest mb-2 px-1">Tanggal Lahir</label>
                            <input type="date" name="tanggal_lahir" value="{{ old('tanggal_lahir', $pendaftar->tanggal_lahir ?? '') }}" required class="w-full px-5 py-4 bg-gray-50 border border-transparent rounded-2xl outline-none focus:border-adzkia-blue focus:bg-white transition-all font-bold text-[14px] text-gray-500 disabled:opacity-50 disabled:cursor-not-allowed" {{ $isLocked ? 'disabled' : '' }}>
                        </div>

                        <div class="md:col-span-2">
                            <label class="block text-[11px] font-black text-gray-400 uppercase tracking-widest mb-3 px-1">Jenis Kelamin</label>
                            <div class="flex gap-6 px-1">
                                @foreach(['Laki-laki', 'Perempuan'] as $gender)
                                    <label class="flex items-center gap-3 cursor-pointer group {{ $isLocked ? 'cursor-not-allowed opacity-50' : '' }}">
                                        <div class="w-5 h-5 rounded-full border-2 border-gray-300 flex items-center justify-center transition-colors relative {{ !$isLocked ? 'group-hover:border-adzkia-blue' : '' }}">
                                            <input type="radio" name="gender" value="{{ $gender }}" class="peer sr-only" {{ old('gender', $pendaftar->gender ?? '') == $gender ? 'checked' : '' }} {{ $isLocked ? 'disabled' : '' }}>
                                            <div class="w-2.5 h-2.5 bg-adzkia-blue rounded-full opacity-0 peer-checked:opacity-100 transition-opacity"></div>
                                        </div>
                                        <span class="text-[14px] font-bold text-adzkia-dark">{{ $gender }}</span>
                                    </label>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </section>

                <hr class="border-gray-100 my-10">

                {{-- BAGIAN 2: KONTAK & ALAMAT --}}
                <section>
                    <div class="flex items-center gap-3 mb-6">
                        <div class="w-10 h-10 bg-adzkia-badge-bg text-adzkia-blue rounded-xl flex items-center justify-center">
                            <i data-feather="map-pin" class="w-5 h-5"></i>
                        </div>
                        <h2 class="text-lg font-black text-adzkia-dark">Kontak & Wilayah</h2>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-[11px] font-black text-gray-400 uppercase tracking-widest mb-2 px-1">Email Aktif</label>
                            <input type="email" name="email" value="{{ old('email', $pendaftar->email ?? '') }}" placeholder="example@email.com" class="w-full px-5 py-4 bg-gray-50 border border-transparent rounded-2xl outline-none focus:border-adzkia-blue focus:bg-white transition-all font-bold text-[14px] text-adzkia-dark disabled:opacity-50 disabled:cursor-not-allowed" {{ $isLocked ? 'disabled' : '' }}>
                        </div>
                        
                        <div>
                            <label class="block text-[11px] font-black text-gray-400 uppercase tracking-widest mb-2 px-1">No. HP / WhatsApp</label>
                            <input type="text" name="no_whatsapp" value="{{ old('no_whatsapp', $pendaftar->no_whatsapp ?? '') }}" placeholder="0812xxxx" class="w-full px-5 py-4 bg-gray-50 border border-transparent rounded-2xl outline-none focus:border-adzkia-blue focus:bg-white transition-all font-bold text-[14px] text-adzkia-dark disabled:opacity-50 disabled:cursor-not-allowed" {{ $isLocked ? 'disabled' : '' }}>
                        </div>

                        <div>
                            <label class="block text-[11px] font-black text-gray-400 uppercase tracking-widest mb-2 px-1">Provinsi</label>
                            <div class="relative">
                                <select name="provinsi" x-model="selectedProv" @change="loadCities(selectedProv)" required class="w-full px-5 py-4 bg-gray-50 border border-transparent rounded-2xl outline-none focus:border-adzkia-blue focus:bg-white transition-all font-bold text-[14px] text-adzkia-dark appearance-none cursor-pointer disabled:opacity-50 disabled:cursor-not-allowed" {{ $isLocked ? 'disabled' : '' }}>
                                    <option value="" disabled>Pilih Provinsi</option>
                                    <template x-for="prov in provinces" :key="prov.code">
                                        <option :value="prov.code" x-text="prov.name"></option>
                                    </template>
                                </select>
                                @if(!$isLocked)
                                <i data-feather="chevron-down" class="w-4 h-4 text-gray-400 absolute right-5 top-1/2 -translate-y-1/2 pointer-events-none"></i>
                                @endif
                            </div>
                        </div>

                        <div>
                            <label class="block text-[11px] font-black text-gray-400 uppercase tracking-widest mb-2 px-1">Kota / Kabupaten</label>
                            <div class="relative">
                                <select name="kota_kabupaten" x-model="selectedCity" required class="w-full px-5 py-4 bg-gray-50 border border-transparent rounded-2xl outline-none focus:border-adzkia-blue focus:bg-white transition-all font-bold text-[14px] text-adzkia-dark appearance-none cursor-pointer disabled:opacity-50 disabled:cursor-not-allowed" {{ $isLocked ? 'disabled' : '' }}>
                                    <option value="" disabled>Pilih Kota</option>
                                    <template x-for="city in cities" :key="city.code">
                                        <option :value="city.name" x-text="city.name"></option>
                                    </template>
                                </select>
                                @if(!$isLocked)
                                <i data-feather="chevron-down" class="w-4 h-4 text-gray-400 absolute right-5 top-1/2 -translate-y-1/2 pointer-events-none"></i>
                                @endif
                            </div>
                        </div>

                        <div class="md:col-span-2">
                            <label class="block text-[11px] font-black text-gray-400 uppercase tracking-widest mb-2 px-1">Alamat Lengkap Rumah</label>
                            <textarea name="alamat_rumah" rows="3" placeholder="Jl. Sudirman No 123, RT 01 RW 02, Kec. Padang Barat..." class="w-full px-5 py-4 bg-gray-50 border border-transparent rounded-2xl outline-none focus:border-adzkia-blue focus:bg-white transition-all font-bold text-[14px] text-adzkia-dark resize-none disabled:opacity-50 disabled:cursor-not-allowed" {{ $isLocked ? 'disabled' : '' }}>{{ old('alamat_rumah', $pendaftar->alamat_rumah ?? '') }}</textarea>
                        </div>
                    </div>
                </section>

                <hr class="border-gray-100 my-10">

                {{-- BAGIAN 3: PENDIDIKAN --}}
                <section>
                    <div class="flex items-center gap-3 mb-6">
                        <div class="w-10 h-10 bg-adzkia-badge-bg text-adzkia-blue rounded-xl flex items-center justify-center">
                            <i data-feather="book" class="w-5 h-5"></i>
                        </div>
                        <h2 class="text-lg font-black text-adzkia-dark">Asal Pendidikan</h2>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-12 gap-6">
                        <div class="md:col-span-12">
                            <label class="block text-[11px] font-black text-gray-400 uppercase tracking-widest mb-2 px-1">Asal Sekolah</label>
                            <input type="text" name="sekolah_asal" value="{{ old('sekolah_asal', $pendaftar->sekolah_asal ?? '') }}" placeholder="Contoh: SMAN 1 Padang" class="w-full px-5 py-4 bg-gray-50 border border-transparent rounded-2xl outline-none focus:border-adzkia-blue focus:bg-white transition-all font-bold text-[14px] text-adzkia-dark disabled:opacity-50 disabled:cursor-not-allowed" {{ $isLocked ? 'disabled' : '' }}>
                        </div>

                        <div class="md:col-span-6">
                            <label class="block text-[11px] font-black text-gray-400 uppercase tracking-widest mb-2 px-1">Jurusan di Sekolah</label>
                            <input type="text" name="jurusan_sma" value="{{ old('jurusan_sma', $pendaftar->jurusan_sma ?? '') }}" placeholder="MIPA / IPS / RPL" class="w-full px-5 py-4 bg-gray-50 border border-transparent rounded-2xl outline-none focus:border-adzkia-blue focus:bg-white transition-all font-bold text-[14px] text-adzkia-dark disabled:opacity-50 disabled:cursor-not-allowed" {{ $isLocked ? 'disabled' : '' }}>
                        </div>

                        <div class="md:col-span-3">
                            <label class="block text-[11px] font-black text-gray-400 uppercase tracking-widest mb-2 px-1">Tahun Lulus</label>
                            <input type="number" name="tahun_lulus" value="{{ old('tahun_lulus', $pendaftar->tahun_lulus ?? '') }}" placeholder="2024" class="w-full px-5 py-4 bg-gray-50 border border-transparent rounded-2xl outline-none focus:border-adzkia-blue focus:bg-white transition-all font-bold text-[14px] text-adzkia-dark disabled:opacity-50 disabled:cursor-not-allowed" {{ $isLocked ? 'disabled' : '' }}>
                        </div>

                        <div class="md:col-span-3">
                            <label class="block text-[11px] font-black text-gray-400 uppercase tracking-widest mb-2 px-1">Rata-rata Nilai</label>
                            <input type="number" 
                                name="nilai_akhir" 
                                value="{{ old('nilai_akhir', $pendaftar->nilai_akhir ?? '') }}" 
                                placeholder="85.50" 
                                required 
                                min="0" 
                                max="100" 
                                step="0.01" 
                                class="w-full px-5 py-4 bg-gray-50 border border-transparent rounded-2xl outline-none focus:border-adzkia-blue focus:bg-white transition-all font-bold text-[14px] text-adzkia-dark disabled:opacity-50 disabled:cursor-not-allowed" 
                                {{ $isLocked ? 'disabled' : '' }}>
                        </div>
                    </div>
                </section>
        
                {{-- BAGIAN 4: PRODI (Dalam Kotak Khusus Biru Muda) --}}
                <section class="bg-[#F8FAFC] p-8 md:p-10 rounded-[2rem] border border-gray-100 my-10">
                    <div class="flex items-center gap-3 mb-6">
                        <div class="w-10 h-10 bg-white shadow-sm text-adzkia-blue rounded-xl flex items-center justify-center">
                            <i data-feather="target" class="w-5 h-5"></i>
                        </div>
                        <h2 class="text-lg font-black text-adzkia-dark">Pilihan Program Studi</h2>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-[11px] font-black text-gray-400 uppercase tracking-widest mb-2 px-1">Pilihan Jurusan 1 (Utama)</label>
                            <div class="relative">
                                <select name="pilihan_jurusan_1" required class="w-full px-5 py-4 bg-white border border-gray-200 rounded-2xl outline-none focus:border-adzkia-blue transition-all font-bold text-[14px] text-adzkia-dark appearance-none shadow-sm disabled:opacity-50 disabled:cursor-not-allowed" {{ $isLocked ? 'disabled' : 'cursor-pointer' }}>
                                    <option value="" disabled>Pilih Jurusan Utama</option>
                                    @foreach($prodis as $prodi)
                                        <option value="{{ $prodi->nama }}" {{ old('pilihan_jurusan_1', $pendaftar->pilihan_jurusan_1 ?? '') == $prodi->nama ? 'selected' : '' }}>
                                            {{ $prodi->nama }}
                                        </option>
                                    @endforeach
                                </select>
                                @if(!$isLocked)
                                <i data-feather="chevron-down" class="w-5 h-5 text-gray-400 absolute right-5 top-1/2 -translate-y-1/2 pointer-events-none"></i>
                                @endif
                            </div>
                        </div>

                        <div>
                            <label class="block text-[11px] font-black text-gray-400 uppercase tracking-widest mb-2 px-1">Pilihan Jurusan 2 (Cadangan)</label>
                            <div class="relative">
                                <select name="pilihan_jurusan_2" required class="w-full px-5 py-4 bg-white border border-gray-200 rounded-2xl outline-none focus:border-adzkia-blue transition-all font-bold text-[14px] text-adzkia-dark appearance-none shadow-sm disabled:opacity-50 disabled:cursor-not-allowed" {{ $isLocked ? 'disabled' : 'cursor-pointer' }}>
                                    <option value="" disabled>Pilih Jurusan Alternatif</option>
                                    @foreach($prodis as $prodi)
                                        <option value="{{ $prodi->nama }}" {{ old('pilihan_jurusan_2', $pendaftar->pilihan_jurusan_2 ?? '') == $prodi->nama ? 'selected' : '' }}>
                                            {{ $prodi->nama }}
                                        </option>
                                    @endforeach
                                </select>
                                @if(!$isLocked)
                                <i data-feather="chevron-down" class="w-5 h-5 text-gray-400 absolute right-5 top-1/2 -translate-y-1/2 pointer-events-none"></i>
                                @endif
                            </div>
                        </div>
                    </div>
                </section>

                {{-- BAGIAN 5: UPLOAD BERKAS --}}
                <section>
                    <div class="flex items-center gap-3 mb-6">
                        <div class="w-10 h-10 bg-adzkia-badge-bg text-adzkia-blue rounded-xl flex items-center justify-center">
                            <i data-feather="upload-cloud" class="w-5 h-5"></i>
                        </div>
                        <h2 class="text-lg font-black text-adzkia-dark">Unggah Dokumen Syarat</h2>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        
                        <div class="border-2 border-dashed rounded-2xl p-6 flex flex-col items-center justify-center text-center transition-all group overflow-hidden relative"
                            :class="!files.foto ? 'border-gray-200 hover:border-adzkia-blue hover:bg-blue-50 {{ $isLocked ? 'cursor-not-allowed' : 'cursor-pointer' }}' : 'bg-gray-50 border-gray-300'"
                            @click="if(!files.foto && !{{ $isLocked ? 'true' : 'false' }}) $refs.fileFoto.click()">
                            <input type="file" name="pas_foto" x-ref="fileFoto" @change="handleFileUpload($event, 'foto')" class="hidden" accept=".jpg,.png,.jpeg" {{ $isLocked ? 'disabled' : '' }}>
                            
                            <div x-show="!files.foto" class="flex flex-col items-center">
                                <i data-feather="camera" class="w-6 h-6 text-gray-400 mb-3 transition-colors {{ !$isLocked ? 'group-hover:text-adzkia-blue' : '' }}"></i>
                                <h4 class="text-[13px] font-extrabold text-adzkia-dark mb-1">Pas Foto 4x6</h4>
                                <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-3">JPG/PNG, Max 2MB</p>
                                @if(!$isLocked)
                                    <span class="text-[12px] font-black text-adzkia-blue underline underline-offset-2">Pilih File</span>
                                @endif
                            </div>
                            
                            <div x-show="files.foto" class="flex flex-col items-center w-full min-w-0" x-cloak>
                                <div class="w-10 h-10 bg-adzkia-blue text-white rounded-full flex items-center justify-center mb-2 shrink-0">
                                    <i data-feather="check" class="w-5 h-5"></i>
                                </div>
                                <h4 class="text-[12px] font-extrabold text-adzkia-dark w-full px-2 text-center truncate" x-text="files.foto"></h4>
                                <div class="flex gap-2 mt-3">
                                    @if(!empty($pendaftar->pas_foto))
                                    <a href="{{ asset($pendaftar->pas_foto) }}" target="_blank" x-show="files.foto === 'Sudah Diunggah'" class="text-[11px] font-bold text-adzkia-blue bg-blue-100 px-3 py-1.5 rounded-lg hover:bg-blue-200 transition-colors" @click.stop>
                                        Lihat File
                                    </a>
                                    @endif
                                    @if(!$isLocked)
                                    <button type="button" @click.stop="$refs.fileFoto.click()" class="text-[11px] font-bold text-gray-600 bg-gray-200 px-3 py-1.5 rounded-lg hover:bg-gray-300 transition-colors">Ganti</button>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <div class="border-2 border-dashed rounded-2xl p-6 flex flex-col items-center justify-center text-center transition-all group overflow-hidden relative"
                            :class="!files.ktp ? 'border-gray-200 hover:border-adzkia-blue hover:bg-blue-50 {{ $isLocked ? 'cursor-not-allowed' : 'cursor-pointer' }}' : 'bg-gray-50 border-gray-300'"
                            @click="if(!files.ktp && !{{ $isLocked ? 'true' : 'false' }}) $refs.fileKtp.click()">
                            <input type="file" name="scan_ktp" x-ref="fileKtp" @change="handleFileUpload($event, 'ktp')" class="hidden" accept=".jpg,.jpeg,.png,.pdf" {{ $isLocked ? 'disabled' : '' }}>
                            
                            <div x-show="!files.ktp" class="flex flex-col items-center">
                                <i data-feather="credit-card" class="w-6 h-6 text-gray-400 mb-3 transition-colors {{ !$isLocked ? 'group-hover:text-adzkia-blue' : '' }}"></i>
                                <h4 class="text-[13px] font-extrabold text-adzkia-dark mb-1">Scan KTP/KK</h4>
                                <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-3">PDF/JPG, Max 2MB</p>
                                @if(!$isLocked)
                                    <span class="text-[12px] font-black text-adzkia-blue underline underline-offset-2">Pilih File</span>
                                @endif
                            </div>

                            <div x-show="files.ktp" class="flex flex-col items-center w-full min-w-0" x-cloak>
                                <div class="w-10 h-10 bg-adzkia-blue text-white rounded-full flex items-center justify-center mb-2 shrink-0">
                                    <i data-feather="check" class="w-5 h-5"></i>
                                </div>
                                <h4 class="text-[12px] font-extrabold text-adzkia-dark w-full px-2 text-center truncate" x-text="files.ktp"></h4>
                                <div class="flex gap-2 mt-3">
                                    @if(!empty($pendaftar->scan_ktp))
                                    <a href="{{ asset($pendaftar->scan_ktp) }}" target="_blank" x-show="files.ktp === 'Sudah Diunggah'" class="text-[11px] font-bold text-adzkia-blue bg-blue-100 px-3 py-1.5 rounded-lg hover:bg-blue-200 transition-colors" @click.stop>
                                        Lihat File 
                                    </a>
                                    @endif
                                    @if(!$isLocked)
                                    <button type="button" @click.stop="$refs.fileKtp.click()" class="text-[11px] font-bold text-gray-600 bg-gray-200 px-3 py-1.5 rounded-lg hover:bg-gray-300 transition-colors">Ganti</button>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <div class="border-2 border-dashed rounded-2xl p-6 flex flex-col items-center justify-center text-center transition-all group overflow-hidden relative"
                            :class="!files.ijazah ? 'border-gray-200 hover:border-adzkia-blue hover:bg-blue-50 {{ $isLocked ? 'cursor-not-allowed' : 'cursor-pointer' }}' : 'bg-gray-50 border-gray-300'"
                            @click="if(!files.ijazah && !{{ $isLocked ? 'true' : 'false' }}) $refs.fileIjazah.click()">
                            <input type="file" name="ijazah_skl" x-ref="fileIjazah" @change="handleFileUpload($event, 'ijazah')" class="hidden" accept=".pdf" {{ $isLocked ? 'disabled' : '' }}>
                            
                            <div x-show="!files.ijazah" class="flex flex-col items-center">
                                <i data-feather="file-text" class="w-6 h-6 text-gray-400 mb-3 transition-colors {{ !$isLocked ? 'group-hover:text-adzkia-blue' : '' }}"></i>
                                <h4 class="text-[13px] font-extrabold text-adzkia-dark mb-1">Ijazah / SKL</h4>
                                <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-3">PDF, Max 2MB</p>
                                @if(!$isLocked)
                                    <span class="text-[12px] font-black text-adzkia-blue underline underline-offset-2">Pilih File</span>
                                @endif
                            </div>

                            <div x-show="files.ijazah" class="flex flex-col items-center w-full min-w-0" x-cloak>
                                <div class="w-10 h-10 bg-adzkia-blue text-white rounded-full flex items-center justify-center mb-2 shrink-0">
                                    <i data-feather="check" class="w-5 h-5"></i>
                                </div>
                                <h4 class="text-[12px] font-extrabold text-adzkia-dark w-full px-2 text-center truncate" x-text="files.ijazah"></h4>
                                <div class="flex gap-2 mt-3">
                                    @if(!empty($pendaftar->ijazah_skl))
                                    <a href="{{ asset($pendaftar->ijazah_skl) }}" target="_blank" x-show="files.ijazah === 'Sudah Diunggah'" class="text-[11px] font-bold text-adzkia-blue bg-blue-100 px-3 py-1.5 rounded-lg hover:bg-blue-200 transition-colors" @click.stop>
                                        Lihat File
                                    </a>
                                    @endif
                                    @if(!$isLocked)
                                    <button type="button" @click.stop="$refs.fileIjazah.click()" class="text-[11px] font-bold text-gray-600 bg-gray-200 px-3 py-1.5 rounded-lg hover:bg-gray-300 transition-colors">Ganti</button>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </section>

                <div class="pt-10 border-t border-gray-100 flex flex-col items-center gap-6 mt-10">
                    @if(!$isLocked)
                        <div class="flex items-center gap-2 text-[12px] font-bold text-green-600 bg-green-50 px-4 py-2 rounded-lg w-full md:w-auto justify-center">
                            <i data-feather="save" class="w-4 h-4"></i> Data otomatis tersimpan sebagai draft di browser Anda
                        </div>

                        <button type="submit" class="w-full py-4 bg-adzkia-blue text-white rounded-2xl font-black text-[15px] hover:bg-blue-700 shadow-lg shadow-blue-600/20 transition-all flex justify-center items-center gap-2 active:scale-[0.98]">
                            Simpan Biodata & Lanjutkan <i data-feather="arrow-right" class="w-4 h-4"></i>
                        </button>
                    @else
                        {{-- Tombol alternatif saat data dikunci --}}
                        <a href="{{ route('konfirmasi-data', $pendaftar->id) }}" class="w-full py-4 bg-gray-100 text-adzkia-dark rounded-2xl font-black text-[15px] hover:bg-gray-200 transition-all flex justify-center items-center gap-2">
                            Kembali ke Halaman Konfirmasi <i data-feather="arrow-right" class="w-4 h-4"></i>
                        </a>
                    @endif
                </div>
            </form>
        </div>
    </main>

<script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('formulirApp', () => ({
                currentStep: 4,
                provinces: [],
                cities: [],
                
                // 1. KOSONGKAN DULU, Jangan langsung panggil variabel PHP di sini
                selectedProv: '',
                selectedCity: '',

                files: {
                    foto: '{{ !empty($pendaftar->pas_foto) ? "Sudah Diunggah" : "" }}',
                    ktp: '{{ !empty($pendaftar->scan_ktp) ? "Sudah Diunggah" : "" }}',
                    ijazah: '{{ !empty($pendaftar->ijazah_skl) ? "Sudah Diunggah" : "" }}'
                },

                steps: [
                    { id: 1, title: 'Pendaftaran' }, { id: 2, title: 'Biaya' },
                    { id: 3, title: 'Validasi' }, { id: 4, title: 'Biodata' },
                    { id: 5, title: 'Dokumen' }, { id: 6, title: 'Ujian' }, { id: 7, title: 'Hasil' }
                ],

                async init() {
                    // 2. Simpan data dari database ke memori JS sementara
                    const dbProv = '{{ old('provinsi', $pendaftar->provinsi ?? '') }}';
                    const dbCity = '{{ old('kota_kabupaten', $pendaftar->kota_kabupaten ?? '') }}';

                    try {
                        const res = await fetch('/data/provinsi.json');
                        if(res.ok) {
                            const json = await res.json();
                            this.provinces = json.data || [];
                            
                            // 3. TUNGGU sampai HTML <option> selesai digambar oleh browser
                            this.$nextTick(async () => {
                                if (dbProv) {
                                    this.selectedProv = dbProv; // Set Provinsi sesuai DB
                                    await this.loadCities(dbProv); // Load Kota
                                    
                                    // 4. TUNGGU sampai HTML <option> Kota selesai digambar
                                    this.$nextTick(() => {
                                        this.selectedCity = dbCity; // Set Kota sesuai DB
                                    });
                                }
                            });
                        }
                    } catch (error) { console.error('Data provinsi tidak ditemukan:', error); }
                },

                async loadCities(provinceId) {
                    if (!provinceId) { this.cities = []; return; }
                    try {
                        const res = await fetch(`/data/kabkota/${provinceId}.json`);
                        if(res.ok) {
                            const json = await res.json();
                            this.cities = json.data || [];
                        }
                    } catch (error) { this.cities = []; }
                },

                handleFileUpload(event, type) {
                    const file = event.target.files[0];
                    if (!file) return;

                    if (file.size > 2 * 1024 * 1024) {
                        alert('Ukuran file maksimal 2MB!');
                        event.target.value = ''; return;
                    }
                    if (type === 'foto' && !['image/jpeg', 'image/png', 'image/jpg'].includes(file.type)) {
                        alert('Pas Foto harus berformat JPG/PNG!');
                        event.target.value = ''; return;
                    }
                    if (type === 'ktp' && !['image/jpeg', 'image/png', 'image/jpg', 'application/pdf'].includes(file.type)) {
                        alert('KTP harus berformat JPG/PNG atau PDF!');
                        event.target.value = ''; return;
                    }
                    if (type === 'ijazah' && file.type !== 'application/pdf') {
                        alert('Ijazah harus berformat PDF!');
                        event.target.value = ''; return;
                    }
                    this.files[type] = file.name;
                }
            }));
        });

        document.addEventListener('DOMContentLoaded', () => { feather.replace(); });
    </script>
</body>
</html>