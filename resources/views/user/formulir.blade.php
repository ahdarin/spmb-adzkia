@if ($errors->any())
    <div class="bg-red-50 border-l-4 border-adzkia-red p-4 mb-4 text-adzkia-red rounded-lg shadow-sm">
        <ul class="font-bold text-sm">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Formulir Pendaftaran - SPMB Adzkia</title>
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
                        'adzkia-badge-txt': '#2c7ebd',
                        'adzkia-bg': '#FAFBFC',
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
<body class="bg-adzkia-bg antialiased text-adzkia-dark min-h-screen flex flex-col" x-data="formulirApp()">

    <nav class="w-full bg-white py-8 border-b border-gray-100 sticky top-0 z-50">
        <div class="max-w-5xl mx-auto px-6">
            <div class="flex items-center justify-between relative">
                <div class="absolute top-5 left-0 w-full h-0.5 bg-gray-100 z-0"></div>
                <div class="absolute top-5 left-0 h-0.5 bg-adzkia-blue z-0 transition-all duration-500" style="width: 50%;"></div>
                
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

    <main class="flex-1 max-w-4xl mx-auto w-full px-6 py-12">
        <div class="bg-white p-8 md:p-12 rounded-[2.5rem] shadow-xl shadow-adzkia-dark/5 border border-gray-100">
            
            <div class="mb-10 border-b border-gray-100 pb-8">
                <h1 class="text-3xl font-black text-adzkia-blue tracking-tight mb-3">Step 4: Formulir Pendaftaran</h1>
                <p class="text-[14px] font-medium text-gray-500 leading-relaxed">
                    Lengkapi detail profil akademik dan data pribadi Anda untuk melanjutkan proses kurasi pendaftaran.
                </p>
            </div>

            <form action="{{ route('simpan-biodata') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="pendaftar_id" value="{{ $pendaftar->id }}">
                
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
                            <input type="text" name="nama_lengkap" value="{{ old('nama_lengkap', $pendaftar->nama_lengkap ?? '') }}" placeholder="Masukkan nama sesuai Ijazah" required class="w-full px-5 py-4 bg-gray-50 border border-transparent rounded-2xl outline-none focus:border-adzkia-blue focus:bg-white transition-all font-bold text-[14px] text-adzkia-dark">
                        </div>
                        
                        <div>
                            <label class="block text-[11px] font-black text-gray-400 uppercase tracking-widest mb-2 px-1">NIK (National ID)</label>
                            <input type="text" name="nik" value="{{ old('nik', $pendaftar->nik ?? '') }}" placeholder="16 digit nomor induk" required class="w-full px-5 py-4 bg-gray-50 border border-transparent rounded-2xl outline-none focus:border-adzkia-blue focus:bg-white transition-all font-bold text-[14px] text-adzkia-dark">
                        </div>
                        
                        <div>
                            <label class="block text-[11px] font-black text-gray-400 uppercase tracking-widest mb-2 px-1">Agama</label>
                            <div class="relative">
                                <select name="agama" required class="w-full px-5 py-4 bg-gray-50 border border-transparent rounded-2xl outline-none focus:border-adzkia-blue focus:bg-white transition-all font-bold text-[14px] text-adzkia-dark appearance-none cursor-pointer">
                                    <option value="" disabled {{ empty($pendaftar->agama) ? 'selected' : '' }}>Pilih Agama</option>
                                    @foreach(['Islam', 'Kristen Protestan', 'Katolik', 'Hindu', 'Buddha', 'Konghucu'] as $agama)
                                        <option value="{{ $agama }}" {{ old('agama', $pendaftar->agama ?? '') == $agama ? 'selected' : '' }}>{{ $agama }}</option>
                                    @endforeach
                                </select>
                                <i data-feather="chevron-down" class="w-4 h-4 text-gray-400 absolute right-5 top-1/2 -translate-y-1/2 pointer-events-none"></i>
                            </div>
                        </div>

                        <div>
                            <label class="block text-[11px] font-black text-gray-400 uppercase tracking-widest mb-2 px-1">Tempat Lahir</label>
                            <input type="text" name="tempat_lahir" value="{{ old('tempat_lahir', $pendaftar->tempat_lahir ?? '') }}" placeholder="Kota Kelahiran" class="w-full px-5 py-4 bg-gray-50 border border-transparent rounded-2xl outline-none focus:border-adzkia-blue focus:bg-white transition-all font-bold text-[14px] text-adzkia-dark">
                        </div>

                        <div>
                            <label class="block text-[11px] font-black text-gray-400 uppercase tracking-widest mb-2 px-1">Tanggal Lahir</label>
                            <input type="date" name="tanggal_lahir" value="{{ old('tanggal_lahir', $pendaftar->tanggal_lahir ?? '') }}" required class="w-full px-5 py-4 bg-gray-50 border border-transparent rounded-2xl outline-none focus:border-adzkia-blue focus:bg-white transition-all font-bold text-[14px] text-gray-500">
                        </div>

                        <div class="md:col-span-2">
                            <label class="block text-[11px] font-black text-gray-400 uppercase tracking-widest mb-3 px-1">Jenis Kelamin</label>
                            <div class="flex gap-6 px-1">
                                @foreach(['Laki-laki', 'Perempuan'] as $gender)
                                    <label class="flex items-center gap-3 cursor-pointer group">
                                        <div class="w-5 h-5 rounded-full border-2 border-gray-300 flex items-center justify-center group-hover:border-adzkia-blue transition-colors relative">
                                            <input type="radio" name="gender" value="{{ $gender }}" class="peer sr-only" {{ old('gender', $pendaftar->gender ?? '') == $gender ? 'checked' : '' }}>
                                            <div class="w-2.5 h-2.5 bg-adzkia-blue rounded-full opacity-0 peer-checked:opacity-100 transition-opacity"></div>
                                        </div>
                                        <span class="text-[14px] font-bold text-adzkia-dark">{{ $gender }}</span>
                                    </label>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </section>

<section>
                    <div class="flex items-center gap-3 mb-6 mt-10">
                        <div class="w-10 h-10 bg-adzkia-badge-bg text-adzkia-blue rounded-xl flex items-center justify-center">
                            <i data-feather="map-pin" class="w-5 h-5"></i>
                        </div>
                        <h2 class="text-lg font-black text-adzkia-dark">Informasi Kontak & Wilayah</h2>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-[11px] font-black text-gray-400 uppercase tracking-widest mb-2 px-1">Email</label>
                            <input type="email" name="email" value="{{ old('email', $pendaftar->email ?? '') }}" placeholder="example@email.com" class="w-full px-5 py-4 bg-gray-50 border border-transparent rounded-2xl outline-none focus:border-adzkia-blue focus:bg-white transition-all font-bold text-[14px] text-adzkia-dark">
                        </div>
                        
                        <div>
                            <label class="block text-[11px] font-black text-gray-400 uppercase tracking-widest mb-2 px-1">No. HP (WhatsApp)</label>
                            <input type="text" name="no_whatsapp" value="{{ old('no_whatsapp', $pendaftar->no_whatsapp ?? '') }}" placeholder="0812xxxx" class="w-full px-5 py-4 bg-gray-50 border border-transparent rounded-2xl outline-none focus:border-adzkia-blue focus:bg-white transition-all font-bold text-[14px] text-adzkia-dark">
                        </div>

                        <!-- PROVINSI DAN KOTA (Dipindah Ke Atas Alamat) -->
                        <div>
                            <label class="block text-[11px] font-black text-gray-400 uppercase tracking-widest mb-2 px-1">Provinsi</label>
                            <div class="relative">
                                <select name="provinsi" x-model="selectedProv" @change="loadCities(selectedProv)" required class="w-full px-5 py-4 bg-gray-50 border border-transparent rounded-2xl outline-none focus:border-adzkia-blue focus:bg-white transition-all font-bold text-[14px] text-adzkia-dark appearance-none cursor-pointer">
                                    <option value="" disabled>Pilih Provinsi</option>
                                    <template x-for="prov in provinces" :key="prov.code">
                                        <option :value="prov.code" x-text="prov.name"></option>
                                    </template>
                                </select>
                                <i data-feather="chevron-down" class="w-4 h-4 text-gray-400 absolute right-5 top-1/2 -translate-y-1/2 pointer-events-none"></i>
                            </div>
                        </div>

                        <div>
                            <label class="block text-[11px] font-black text-gray-400 uppercase tracking-widest mb-2 px-1">Kota / Kabupaten</label>
                            <div class="relative">
                                <select name="kota_kabupaten" x-model="selectedCity" required class="w-full px-5 py-4 bg-gray-50 border border-transparent rounded-2xl outline-none focus:border-adzkia-blue focus:bg-white transition-all font-bold text-[14px] text-adzkia-dark appearance-none cursor-pointer">
                                    <option value="" disabled>Pilih Kota</option>
                                    <template x-for="city in cities" :key="city.code">
                                        <option :value="city.name" x-text="city.name"></option>
                                    </template>
                                </select>
                                <i data-feather="chevron-down" class="w-4 h-4 text-gray-400 absolute right-5 top-1/2 -translate-y-1/2 pointer-events-none"></i>
                            </div>
                        </div>

                        <!-- ALAMAT LENGKAP -->
                        <div class="md:col-span-2">
                            <label class="block text-[11px] font-black text-gray-400 uppercase tracking-widest mb-2 px-1">Alamat Lengkap (Jl, RT/RW, Kecamatan)</label>
                            <textarea name="alamat_rumah" rows="3" class="w-full px-5 py-4 bg-gray-50 border border-transparent rounded-2xl outline-none focus:border-adzkia-blue focus:bg-white transition-all font-bold text-[14px] text-adzkia-dark resize-none">{{ old('alamat_rumah', $pendaftar->alamat_rumah ?? '') }}</textarea>
                        </div>
                    </div>
                </section>

                <section>
                    <div class="flex items-center gap-3 mb-6 mt-10">
                        <div class="w-10 h-10 bg-adzkia-badge-bg text-adzkia-blue rounded-xl flex items-center justify-center">
                            <i data-feather="book-open" class="w-5 h-5"></i>
                        </div>
                        <h2 class="text-lg font-black text-adzkia-dark">Latar Belakang Pendidikan</h2>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-12 gap-6">
                        <div class="md:col-span-12">
                            <label class="block text-[11px] font-black text-gray-400 uppercase tracking-widest mb-2 px-1">Asal Sekolah</label>
                            <input type="text" name="sekolah_asal" value="{{ old('sekolah_asal', $pendaftar->sekolah_asal ?? '') }}" placeholder="SMAN / SMK / MA..." class="w-full px-5 py-4 bg-gray-50 border border-transparent rounded-2xl outline-none focus:border-adzkia-blue focus:bg-white transition-all font-bold text-[14px] text-adzkia-dark">
                        </div>

                        <div class="md:col-span-6">
                            <label class="block text-[11px] font-black text-gray-400 uppercase tracking-widest mb-2 px-1">Jurusan</label>
                            <input type="text" name="jurusan_sma" value="{{ old('jurusan_sma', $pendaftar->jurusan_sma ?? '') }}" placeholder="IPA / IPS / Teknik..." class="w-full px-5 py-4 bg-gray-50 border border-transparent rounded-2xl outline-none focus:border-adzkia-blue focus:bg-white transition-all font-bold text-[14px] text-adzkia-dark">
                        </div>

                        <div class="md:col-span-3">
                            <label class="block text-[11px] font-black text-gray-400 uppercase tracking-widest mb-2 px-1">Tahun Lulus</label>
                            <input type="number" name="tahun_lulus" value="{{ old('tahun_lulus', $pendaftar->tahun_lulus ?? '') }}" placeholder="2023" class="w-full px-5 py-4 bg-gray-50 border border-transparent rounded-2xl outline-none focus:border-adzkia-blue focus:bg-white transition-all font-bold text-[14px] text-adzkia-dark">
                        </div>

                        <div class="md:col-span-3">
                            <label class="block text-[11px] font-black text-gray-400 uppercase tracking-widest mb-2 px-1">Rata-rata Nilai</label>
                            <input type="number" step="0.01" name="nilai_akhir" value="{{ old('nilai_akhir', $pendaftar->nilai_akhir ?? '') }}" placeholder="85.50" class="w-full px-5 py-4 bg-gray-50 border border-transparent rounded-2xl outline-none focus:border-adzkia-blue focus:bg-white transition-all font-bold text-[14px] text-adzkia-dark">
                        </div>
                    </div>
                </section>
        
                <section class="bg-[#F8FAFC] p-8 md:p-10 rounded-[2rem] border border-gray-100 my-10">
                    <div class="flex items-center gap-3 mb-6">
                        <div class="w-10 h-10 bg-white shadow-sm text-adzkia-blue rounded-xl flex items-center justify-center">
                            <i data-feather="book-open" class="w-5 h-5"></i>
                        </div>
                        <h2 class="text-lg font-black text-adzkia-dark">Pilihan Program Studi</h2>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-[11px] font-black text-gray-400 uppercase tracking-widest mb-2 px-1">Pilihan Jurusan 1</label>
                            <div class="relative">
                                <select name="pilihan_jurusan_1" required class="w-full px-5 py-4 bg-white border border-gray-200 rounded-2xl outline-none focus:border-adzkia-blue transition-all font-bold text-[14px] text-adzkia-dark appearance-none cursor-pointer shadow-sm">
                                    <option value="" disabled>Pilih Jurusan 1</option>
                                    @foreach($prodis as $prodi)
                                        <option value="{{ $prodi->nama }}" {{ old('pilihan_jurusan_1', $pendaftar->pilihan_jurusan_1 ?? '') == $prodi->nama ? 'selected' : '' }}>
                                            {{ $prodi->nama }}
                                        </option>
                                    @endforeach
                                </select>
                                <i data-feather="chevron-down" class="w-5 h-5 text-gray-400 absolute right-5 top-1/2 -translate-y-1/2 pointer-events-none"></i>
                            </div>
                        </div>

                        <div>
                            <label class="block text-[11px] font-black text-gray-400 uppercase tracking-widest mb-2 px-1">Pilihan Jurusan 2</label>
                            <div class="relative">
                                <select name="pilihan_jurusan_2" required class="w-full px-5 py-4 bg-white border border-gray-200 rounded-2xl outline-none focus:border-adzkia-blue transition-all font-bold text-[14px] text-adzkia-dark appearance-none cursor-pointer shadow-sm">
                                    <option value="" disabled>Pilih Jurusan 2</option>
                                    @foreach($prodis as $prodi)
                                        <option value="{{ $prodi->nama }}" {{ old('pilihan_jurusan_2', $pendaftar->pilihan_jurusan_2 ?? '') == $prodi->nama ? 'selected' : '' }}>
                                            {{ $prodi->nama }}
                                        </option>
                                    @endforeach
                                </select>
                                <i data-feather="chevron-down" class="w-5 h-5 text-gray-400 absolute right-5 top-1/2 -translate-y-1/2 pointer-events-none"></i>
                            </div>
                        </div>
                    </div>
                </section>

<section>
                    <div class="flex items-center gap-3 mb-6">
                        <div class="w-10 h-10 bg-adzkia-badge-bg text-adzkia-blue rounded-xl flex items-center justify-center">
                            <i data-feather="upload-cloud" class="w-5 h-5"></i>
                        </div>
                        <h2 class="text-lg font-black text-adzkia-dark">Unggah Dokumen</h2>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        
                        <!-- PAS FOTO -->
                        <div class="border-2 border-dashed rounded-2xl p-6 flex flex-col items-center justify-center text-center transition-all group overflow-hidden relative"
                            :class="!files.foto ? 'border-gray-200 cursor-pointer hover:border-adzkia-blue hover:bg-blue-50' : 'bg-gray-50 border-gray-300'"
                            @click="if(!files.foto) $refs.fileFoto.click()">
                            
                            <input type="file" name="pas_foto" x-ref="fileFoto" @change="handleFileUpload($event, 'foto')" class="hidden" accept=".jpg,.png,.jpeg">
                            
                            <!-- Saat Kosong -->
                            <div x-show="!files.foto" class="flex flex-col items-center">
                                <i data-feather="camera" class="w-6 h-6 text-gray-400 mb-3 group-hover:text-adzkia-blue transition-colors"></i>
                                <h4 class="text-[13px] font-extrabold text-adzkia-dark mb-1">Pas Foto 4x6</h4>
                                <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-3">JPG/PNG, Max 2MB</p>
                                <span class="text-[12px] font-black text-adzkia-blue underline underline-offset-2">Pilih File</span>
                            </div>
                            
                            <!-- Saat Sudah Diunggah -->
                            <div x-show="files.foto" class="flex flex-col items-center w-full min-w-0" x-cloak>
                                <div class="w-10 h-10 bg-adzkia-blue text-white rounded-full flex items-center justify-center mb-2 shrink-0">
                                    <i data-feather="check" class="w-5 h-5"></i>
                                </div>
                                <h4 class="text-[12px] font-extrabold text-adzkia-dark w-full px-2 text-center" style="display: block; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;" x-text="files.foto"></h4>
                                
                                <div class="flex gap-2 mt-3">
                                    @if(!empty($pendaftar->pas_foto))
                                    <a href="{{ asset('storage/' . $pendaftar->pas_foto) }}" target="_blank" x-show="files.foto === 'Sudah Diunggah'" class="text-[11px] font-bold text-adzkia-blue bg-blue-100 px-3 py-1.5 rounded-lg hover:bg-blue-200 transition-colors" @click.stop>
                                        Lihat File
                                    </a>
                                    @endif
                                    <button type="button" @click.stop="$refs.fileFoto.click()" class="text-[11px] font-bold text-gray-600 bg-gray-200 px-3 py-1.5 rounded-lg hover:bg-gray-300 transition-colors">
                                        Ganti
                                    </button>
                                </div>
                            </div>
                        </div>

                        <!-- SCAN KTP -->
                        <div class="border-2 border-dashed rounded-2xl p-6 flex flex-col items-center justify-center text-center transition-all group overflow-hidden relative"
                            :class="!files.ktp ? 'border-gray-200 cursor-pointer hover:border-adzkia-blue hover:bg-blue-50' : 'bg-gray-50 border-gray-300'"
                            @click="if(!files.ktp) $refs.fileKtp.click()">
                            
                            <input type="file" name="scan_ktp" x-ref="fileKtp" @change="handleFileUpload($event, 'ktp')" class="hidden" accept=".jpg,.jpeg,.png,.pdf">
                            
                            <!-- Saat Kosong -->
                            <div x-show="!files.ktp" class="flex flex-col items-center">
                                <i data-feather="credit-card" class="w-6 h-6 text-gray-400 mb-3 group-hover:text-adzkia-blue transition-colors"></i>
                                <h4 class="text-[13px] font-extrabold text-adzkia-dark mb-1">Scan KTP</h4>
                                <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-3">PDF/JPG, Max 2MB</p>
                                <span class="text-[12px] font-black text-adzkia-blue underline underline-offset-2">Pilih File</span>
                            </div>

                            <!-- Saat Sudah Diunggah -->
                            <div x-show="files.ktp" class="flex flex-col items-center w-full min-w-0" x-cloak>
                                <div class="w-10 h-10 bg-adzkia-blue text-white rounded-full flex items-center justify-center mb-2 shrink-0">
                                    <i data-feather="check" class="w-5 h-5"></i>
                                </div>
                                <h4 class="text-[12px] font-extrabold text-adzkia-dark w-full px-2 text-center" style="display: block; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;" x-text="files.ktp"></h4>
                                
                                <div class="flex gap-2 mt-3">
                                    @if(!empty($pendaftar->scan_ktp))
                                    <a href="{{ asset('storage/' . $pendaftar->scan_ktp) }}" target="_blank" x-show="files.ktp === 'Sudah Diunggah'" class="text-[11px] font-bold text-adzkia-blue bg-blue-100 px-3 py-1.5 rounded-lg hover:bg-blue-200 transition-colors" @click.stop>
                                        Lihat File
                                    </a>
                                    @endif
                                    <button type="button" @click.stop="$refs.fileKtp.click()" class="text-[11px] font-bold text-gray-600 bg-gray-200 px-3 py-1.5 rounded-lg hover:bg-gray-300 transition-colors">
                                        Ganti
                                    </button>
                                </div>
                            </div>
                        </div>

                        <!-- IJAZAH / SKL -->
                        <div class="border-2 border-dashed rounded-2xl p-6 flex flex-col items-center justify-center text-center transition-all group overflow-hidden relative"
                            :class="!files.ijazah ? 'border-gray-200 cursor-pointer hover:border-adzkia-blue hover:bg-blue-50' : 'bg-gray-50 border-gray-300'"
                            @click="if(!files.ijazah) $refs.fileIjazah.click()">
                            
                            <input type="file" name="ijazah_skl" x-ref="fileIjazah" @change="handleFileUpload($event, 'ijazah')" class="hidden" accept=".pdf">
                            
                            <!-- Saat Kosong -->
                            <div x-show="!files.ijazah" class="flex flex-col items-center">
                                <i data-feather="file-text" class="w-6 h-6 text-gray-400 mb-3 group-hover:text-adzkia-blue transition-colors"></i>
                                <h4 class="text-[13px] font-extrabold text-adzkia-dark mb-1">Ijazah / SKL</h4>
                                <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-3">PDF, Max 2MB</p>
                                <span class="text-[12px] font-black text-adzkia-blue underline underline-offset-2">Pilih File</span>
                            </div>

                            <!-- Saat Sudah Diunggah -->
                            <div x-show="files.ijazah" class="flex flex-col items-center w-full min-w-0" x-cloak>
                                <div class="w-10 h-10 bg-adzkia-blue text-white rounded-full flex items-center justify-center mb-2 shrink-0">
                                    <i data-feather="check" class="w-5 h-5"></i>
                                </div>
                                <h4 class="text-[12px] font-extrabold text-adzkia-dark w-full px-2 text-center" style="display: block; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;" x-text="files.ijazah"></h4>
                                
                                <div class="flex gap-2 mt-3">
                                    @if(!empty($pendaftar->ijazah_skl))
                                    <a href="{{ asset('storage/' . $pendaftar->ijazah_skl) }}" target="_blank" x-show="files.ijazah === 'Sudah Diunggah'" class="text-[11px] font-bold text-adzkia-blue bg-blue-100 px-3 py-1.5 rounded-lg hover:bg-blue-200 transition-colors" @click.stop>
                                        Lihat File
                                    </a>
                                    @endif
                                    <button type="button" @click.stop="$refs.fileIjazah.click()" class="text-[11px] font-bold text-gray-600 bg-gray-200 px-3 py-1.5 rounded-lg hover:bg-gray-300 transition-colors">
                                        Ganti
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>

                <div class="pt-10 border-t border-gray-100 flex flex-col items-center gap-6 mt-10">
                    <div class="flex items-center gap-2 text-[12px] font-bold text-green-600 bg-green-50 px-4 py-2 rounded-lg w-full md:w-auto justify-center">
                        <i data-feather="check-circle" class="w-4 h-4"></i> Data tersimpan otomatis
                    </div>

                    <button type="submit" class="w-full py-4 bg-adzkia-blue text-white rounded-2xl font-black text-[15px] hover:bg-blue-700 shadow-lg shadow-blue-600/20 transition-all active:scale-[0.98]">
                        Simpan & Lanjutkan
                    </button>

                    <a href="/validasi-pembayaran" class="text-[13px] font-extrabold text-gray-500 hover:text-adzkia-blue transition-colors py-2">
                        Kembali
                    </a>
                </div>
            </form>
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
            Alpine.data('formulirApp', () => ({
                currentStep: 4,
                provinces: [],
                cities: [],
                
                selectedProv: '{{ old('provinsi', $pendaftar->provinsi ?? '') }}',
                selectedCity: '{{ old('kota_kabupaten', $pendaftar->kota_kabupaten ?? '') }}',

files: {
                    foto: '{{ !empty($pendaftar->pas_foto) ? basename($pendaftar->pas_foto) : "" }}',
                    ktp: '{{ !empty($pendaftar->scan_ktp) ? basename($pendaftar->scan_ktp) : "" }}',
                    ijazah: '{{ !empty($pendaftar->ijazah_skl) ? basename($pendaftar->ijazah_skl) : "" }}'
                },

                // Deteksi file baru yang diunggah (belum tersimpan di DB)
                newUploads: {
                    foto: false,
                    ktp: false,
                    ijazah: false
                },

                steps: [
                    { id: 1, title: 'Pendaftaran' }, { id: 2, title: 'Biaya' },
                    { id: 3, title: 'Validasi' }, { id: 4, title: 'Biodata' },
                    { id: 5, title: 'Dokumen' }, { id: 6, title: 'Ujian' }, { id: 7, title: 'Hasil' }
                ],

                async init() {
                    try {
                        const res = await fetch('/data/provinsi.json');
                        const json = await res.json();
                        this.provinces = json.data || [];
                        
                        if (this.selectedProv) {
                            await this.loadCities(this.selectedProv);
                            this.$nextTick(() => {
                                this.selectedCity = '{{ old('kota_kabupaten', $pendaftar->kota_kabupaten ?? '') }}';
                            });
                        }
                    } catch (error) {
                        console.error('Gagal memuat data provinsi:', error);
                    }
                },

                async loadCities(provinceId) {
                    if (!provinceId) { this.cities = []; return; }
                    try {
                        const res = await fetch(`/data/kabkota/${provinceId}.json`);
                        const json = await res.json();
                        this.cities = json.data || [];
                    } catch (error) {
                        console.error('Gagal memuat data kota:', error);
                        this.cities = [];
                    }
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
                    this.newUploads[type] = true; // Tandai bahwa ini file baru
                }
            }));
        });

        document.addEventListener('DOMContentLoaded', () => {
            feather.replace();
        });

        document.addEventListener('DOMContentLoaded', function() {
            const form = document.querySelector('form');
            const formElements = form.querySelectorAll('input:not([type="file"]), select, textarea');
            const savedData = JSON.parse(localStorage.getItem('draftFormulir') || '{}');
            
            formElements.forEach(el => {
                if (el.name && savedData[el.name] && !el.value) {
                    if(el.type === 'radio') {
                        if(el.value === savedData[el.name]) el.checked = true;
                    } else {
                        el.value = savedData[el.name];
                    }
                }
            });

            form.addEventListener('input', function(e) {
                if (e.target.name && e.target.type !== 'file' && e.target.name !== '_token') {
                    savedData[e.target.name] = e.target.value;
                    localStorage.setItem('draftFormulir', JSON.stringify(savedData));
                }
            });

            form.addEventListener('submit', function() {
                localStorage.removeItem('draftFormulir');
            });
        });
    </script>
</body>
</html>