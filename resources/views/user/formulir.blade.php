@if ($errors->any())
    <div class="bg-red-100 p-4 mb-4 text-red-700 rounded-lg">
        <ul>
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
    <style>
        [x-cloak] { display: none !important; }
        /* Kustomisasi scrollbar untuk text area */
        textarea::-webkit-scrollbar { width: 6px; }
        textarea::-webkit-scrollbar-thumb { background-color: #cbd5e1; border-radius: 10px; }
    </style>
</head>
<body class="bg-brand-bg antialiased text-brand-dark min-h-screen flex flex-col" x-data="formulirApp()">

    <nav class="w-full bg-white py-8 border-b border-gray-100 sticky top-0 z-50">
        <div class="max-w-5xl mx-auto px-6">
            <div class="flex items-center justify-between relative">
                <div class="absolute top-5 left-0 w-full h-0.5 bg-gray-100 z-0"></div>
                <div class="absolute top-5 left-0 h-0.5 bg-brand-dark z-0 transition-all duration-500" style="width: 50%;"></div>
                
                <template x-for="step in steps" :key="step.id">
                    <div class="relative z-10 flex flex-col items-center gap-3 w-20">
                        <div class="w-10 h-10 rounded-full flex items-center justify-center font-extrabold text-[13px] transition-all duration-300"
                             :class="{
                                 'bg-brand-dark text-white': step.id < currentStep,
                                 'bg-brand-blue text-white shadow-lg shadow-brand-blue/30 scale-110 ring-4 ring-brand-blue-light': step.id === currentStep,
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
                              :class="step.id === currentStep ? 'text-brand-dark' : 'text-gray-400'"
                              x-text="step.title"></span>
                    </div>
                </template>
            </div>
        </div>
    </nav>

    <main class="flex-1 max-w-4xl mx-auto w-full px-6 py-12">
        <div class="bg-white p-8 md:p-12 rounded-[2.5rem] shadow-xl shadow-brand-dark/5 border border-gray-100">
            
            <!-- Header -->
            <div class="mb-10 border-b border-gray-100 pb-8">
                <h1 class="text-3xl font-black text-brand-dark tracking-tight mb-3">Step 4: Formulir Pendaftaran</h1>
                <p class="text-[14px] font-medium text-gray-500 leading-relaxed">
                    Lengkapi detail profil akademik dan data pribadi Anda untuk melanjutkan proses kurasi pendaftaran.
                </p>
            </div>

            <!-- Form -->
            <form action="{{ route('simpan-biodata', $pendaftar->id) }}" method="POST"> 
                @csrf
                @method('PUT')
                
                @if ($errors->any())
                    <div class="bg-red-50 border-l-4 border-red-500 p-4 mb-8 rounded-r-2xl">
                        <h3 class="text-sm font-black text-red-800 uppercase tracking-widest mb-2">Oops! Ada yang kurang:</h3>
                        <ul class="text-[13px] font-bold text-red-600 list-disc list-inside">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
                
                <!-- Data Diri -->
                <section>
                    <div class="flex items-center gap-3 mb-6">
                        <div class="w-10 h-10 bg-brand-blue-light text-brand-blue rounded-xl flex items-center justify-center">
                            <i data-feather="user" class="w-5 h-5"></i>
                        </div>
                        <h2 class="text-lg font-black text-brand-dark">Data Diri</h2>
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="md:col-span-2">
                            <label class="block text-[11px] font-black text-gray-400 uppercase tracking-widest mb-2 px-1">Nama Lengkap</label>
                            <input type="text" name="nama_lengkap" value="{{ old('nama_lengkap', $pendaftar->nama_lengkap ?? '') }}" placeholder="Masukkan nama sesuai Ijazah" required class="w-full px-5 py-4 bg-gray-50 border border-transparent rounded-2xl outline-none focus:border-brand-blue focus:bg-white transition-all font-bold text-[14px] text-brand-dark">
                        </div>
                        
                        <div>
                            <label class="block text-[11px] font-black text-gray-400 uppercase tracking-widest mb-2 px-1">NIK (National ID)</label>
                            <input type="text" name="nik" value="{{ old('nik', $pendaftar->nik ?? '') }}" placeholder="16 digit nomor induk" required class="w-full px-5 py-4 bg-gray-50 border border-transparent rounded-2xl outline-none focus:border-brand-blue focus:bg-white transition-all font-bold text-[14px] text-brand-dark">
                        </div>
                        
                        <div>
                            <label class="block text-[11px] font-black text-gray-400 uppercase tracking-widest mb-2 px-1">Agama</label>
                            <div class="relative">
                                <select name="agama" required class="w-full px-5 py-4 bg-gray-50 border border-transparent rounded-2xl outline-none focus:border-brand-blue focus:bg-white transition-all font-bold text-[14px] text-brand-dark appearance-none cursor-pointer">
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
                            <input type="text" name="tempat_lahir" value="{{ old('tempat_lahir', $pendaftar->tempat_lahir ?? '') }}" placeholder="Kota Kelahiran" class="w-full px-5 py-4 bg-gray-50 border border-transparent rounded-2xl outline-none focus:border-brand-blue focus:bg-white transition-all font-bold text-[14px] text-brand-dark">
                        </div>

                        <div>
                            <label class="block text-[11px] font-black text-gray-400 uppercase tracking-widest mb-2 px-1">Tanggal Lahir</label>
                            <input type="date" name="tanggal_lahir" value="{{ old('tanggal_lahir', $pendaftar->tanggal_lahir ?? '') }}" required class="w-full px-5 py-4 bg-gray-50 border border-transparent rounded-2xl outline-none focus:border-brand-blue focus:bg-white transition-all font-bold text-[14px] text-gray-500">
                        </div>

                        <div class="md:col-span-2">
                            <label class="block text-[11px] font-black text-gray-400 uppercase tracking-widest mb-3 px-1">Jenis Kelamin</label>
                            <div class="flex gap-6 px-1">
                                @foreach(['Laki-laki', 'Perempuan'] as $gender)
                                    <label class="flex items-center gap-3 cursor-pointer group">
                                        <div class="w-5 h-5 rounded-full border-2 border-gray-300 flex items-center justify-center group-hover:border-brand-blue transition-colors relative">
                                            <input type="radio" name="gender" value="{{ $gender }}" class="peer sr-only" {{ old('gender', $pendaftar->gender ?? '') == $gender ? 'checked' : '' }}>
                                            <div class="w-2.5 h-2.5 bg-brand-blue rounded-full opacity-0 peer-checked:opacity-100 transition-opacity"></div>
                                        </div>
                                        <span class="text-[14px] font-bold text-brand-dark">{{ $gender }}</span>
                                    </label>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </section>

                <!-- Informasi Kontak -->
                <section>
                    <div class="flex items-center gap-3 mb-6">
                        <div class="w-10 h-10 bg-indigo-50 text-indigo-500 rounded-xl flex items-center justify-center">
                            <i data-feather="map-pin" class="w-5 h-5"></i>
                        </div>
                        <h2 class="text-lg font-black text-brand-dark">Informasi Kontak</h2>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-[11px] font-black text-gray-400 uppercase tracking-widest mb-2 px-1">Email</label>
                            <input type="email" name="email" value="{{ old('email', $pendaftar->email ?? '') }}" placeholder="example@email.com" class="w-full px-5 py-4 bg-gray-50 border border-transparent rounded-2xl outline-none focus:border-brand-blue focus:bg-white transition-all font-bold text-[14px] text-brand-dark">
                        </div>
                        
                        <div>
                            <label class="block text-[11px] font-black text-gray-400 uppercase tracking-widest mb-2 px-1">No. HP (WhatsApp)</label>
                            <input type="text" name="no_whatsapp" value="{{ old('no_whatsapp', $pendaftar->no_whatsapp ?? '') }}" placeholder="0812xxxx" class="w-full px-5 py-4 bg-gray-50 border border-transparent rounded-2xl outline-none focus:border-brand-blue focus:bg-white transition-all font-bold text-[14px] text-brand-dark">
                        </div>

                        <div class="md:col-span-2">
                            <label class="block text-[11px] font-black text-gray-400 uppercase tracking-widest mb-2 px-1">Alamat Lengkap</label>
                            <textarea name="alamat_rumah" rows="3" class="w-full px-5 py-4 bg-gray-50 border border-transparent rounded-2xl outline-none focus:border-brand-blue focus:bg-white transition-all font-bold text-[14px] text-brand-dark resize-none">{{ old('alamat_rumah', $pendaftar->alamat_rumah ?? '') }}</textarea>
                        </div>

                        <!-- Provinsi -->
                        <div>
                            <label class="block text-[11px] font-black text-gray-400 uppercase tracking-widest mb-2 px-1">Provinsi</label>
                            <div class="relative">
                                <select name="provinsi" class="w-full px-5 py-4 bg-gray-50 border border-transparent rounded-2xl outline-none focus:border-brand-blue focus:bg-white transition-all font-bold text-[14px] text-brand-dark appearance-none cursor-pointer">
                                    <option value="" disabled selected>Pilih Provinsi</option>
                                    @foreach(['Sumatra Barat', 'Riau', 'Jambi'] as $prov)
                                        <option value="{{ $prov }}" {{ old('provinsi', $pendaftar->provinsi ?? '') == $prov ? 'selected' : '' }}>
                                            {{ $prov }}
                                        </option>
                                    @endforeach
                                </select>
                                <i data-feather="chevron-down" class="w-4 h-4 text-gray-400 absolute right-5 top-1/2 -translate-y-1/2 pointer-events-none"></i>
                            </div>
                        </div>

                        <!-- Kota/Kabupaten -->
                        <div>
                            <label class="block text-[11px] font-black text-gray-400 uppercase tracking-widest mb-2 px-1">Kota/Kabupaten</label>
                            <div class="relative">
                                <select name="kota_kabupaten" class="w-full px-5 py-4 bg-gray-50 border border-transparent rounded-2xl outline-none focus:border-brand-blue focus:bg-white transition-all font-bold text-[14px] text-brand-dark appearance-none cursor-pointer">
                                    <option value="" disabled selected>Pilih Kota/Kabupaten</option>
                                    @foreach(['Padang', 'Pekanbaru', 'Jambi'] as $kota)
                                        <option value="{{ $kota }}" {{ old('kota_kabupaten', $pendaftar->kota_kabupaten ?? '') == $kota ? 'selected' : '' }}>
                                            {{ $kota }}
                                        </option>
                                    @endforeach
                                </select>
                                <i data-feather="chevron-down" class="w-4 h-4 text-gray-400 absolute right-5 top-1/2 -translate-y-1/2 pointer-events-none"></i>
                            </div>
                        </div>
                    </div>
                </section>

                <!-- Latar Belakang Pendidikan -->
                <section>
                    <div class="flex items-center gap-3 mb-6">
                        <div class="w-10 h-10 bg-amber-50 text-amber-500 rounded-xl flex items-center justify-center">
                            <i data-feather="book-open" class="w-5 h-5"></i>
                        </div>
                        <h2 class="text-lg font-black text-brand-dark">Latar Belakang Pendidikan</h2>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-12 gap-6">
                        <div class="md:col-span-12">
                            <label class="block text-[11px] font-black text-gray-400 uppercase tracking-widest mb-2 px-1">Asal Sekolah</label>
                            <input type="text" name="sekolah_asal" value="{{ old('sekolah_asal', $pendaftar->sekolah_asal ?? '') }}" placeholder="SMAN / SMK / MA..." class="w-full px-5 py-4 bg-gray-50 border border-transparent rounded-2xl outline-none focus:border-brand-blue focus:bg-white transition-all font-bold text-[14px] text-brand-dark">
                        </div>

                        <div class="md:col-span-6">
                            <label class="block text-[11px] font-black text-gray-400 uppercase tracking-widest mb-2 px-1">Jurusan</label>
                            <input type="text" name="jurusan_sma" value="{{ old('jurusan_sma', $pendaftar->jurusan_sma ?? '') }}" placeholder="IPA / IPS / Teknik..." class="w-full px-5 py-4 bg-gray-50 border border-transparent rounded-2xl outline-none focus:border-brand-blue focus:bg-white transition-all font-bold text-[14px] text-brand-dark">
                        </div>

                        <div class="md:col-span-3">
                            <label class="block text-[11px] font-black text-gray-400 uppercase tracking-widest mb-2 px-1">Tahun Lulus</label>
                            <input type="number" name="tahun_lulus" value="{{ old('tahun_lulus', $pendaftar->tahun_lulus ?? '') }}" placeholder="2023" class="w-full px-5 py-4 bg-gray-50 border border-transparent rounded-2xl outline-none focus:border-brand-blue focus:bg-white transition-all font-bold text-[14px] text-brand-dark">
                        </div>

                        <div class="md:col-span-3">
                            <label class="block text-[11px] font-black text-gray-400 uppercase tracking-widest mb-2 px-1">Rata-rata Nilai</label>
                            <input type="number" step="0.01" name="nilai_akhir" value="{{ old('nilai_akhir', $pendaftar->nilai_akhir ?? '') }}" placeholder="85.50" class="w-full px-5 py-4 bg-gray-50 border border-transparent rounded-2xl outline-none focus:border-brand-blue focus:bg-white transition-all font-bold text-[14px] text-brand-dark">
                        </div>
                    </div>
                </section>
        

                
                <section class="bg-[#F8FAFC] p-8 md:p-10 rounded-[2rem] border border-gray-100">
                    <div class="flex items-center gap-3 mb-6">
                        <div class="w-10 h-10 bg-white shadow-sm text-brand-dark rounded-xl flex items-center justify-center">
                            <i data-feather="search" class="w-5 h-5"></i>
                        </div>
                        <h2 class="text-lg font-black text-brand-dark">Pilihan Program Studi</h2>
                    </div>

                    <div>
                        <label class="block text-[11px] font-black text-gray-400 uppercase tracking-widest mb-2 px-1">Cari Program Studi</label>
                        <div class="relative mb-4">
                            <i data-feather="book" class="absolute left-5 top-1/2 -translate-y-1/2 text-gray-400 w-5 h-5"></i>
                            <select name="prodi_id" class="w-full pl-14 pr-10 py-4 bg-white border border-transparent rounded-2xl outline-none focus:border-brand-blue transition-all font-bold text-[14px] text-brand-dark appearance-none cursor-pointer shadow-sm">
                                <option value="" disabled selected>Pilih Program Studi...</option>
                                @foreach($prodis as $prodi)
                                    <option value="{{ $prodi->id }}" {{ old('prodi_id', $pendaftar->prodi_id ?? '') == $prodi->id ? 'selected' : '' }}>
                                        {{ $prodi->nama }}
                                    </option>
                                @endforeach
                            </select>
                            <i data-feather="chevron-down" class="w-5 h-5 text-gray-400 absolute right-5 top-1/2 -translate-y-1/2 pointer-events-none"></i>
                        </div>

                        <div class="flex flex-wrap gap-2">
                            @foreach($prodis->take(3) as $prodi)
                                <span class="px-3 py-1.5 bg-brand-blue-light text-brand-blue rounded-lg text-[11px] font-black tracking-wide cursor-pointer hover:bg-blue-100 transition-colors">
                                    {{ $prodi->nama_prodi }}
                                </span>
                            @endforeach
                        </div>
                    </div>
                </section>

                <section>
                    <div class="flex items-center gap-3 mb-6">
                        <div class="w-10 h-10 bg-emerald-50 text-emerald-500 rounded-xl flex items-center justify-center">
                            <i data-feather="upload-cloud" class="w-5 h-5"></i>
                        </div>
                        <h2 class="text-lg font-black text-brand-dark">Unggah Dokumen</h2>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6" x-data="{ files: { foto: null, ktp: null, ijazah: null } }">
                        
                        <!-- Pas Foto -->
                        <div class="border-2 border-dashed border-gray-200 rounded-2xl p-6 flex flex-col items-center justify-center text-center cursor-pointer hover:border-brand-blue hover:bg-brand-blue-light/50 transition-all group"
                            @click="$refs.fileFoto.click()">
                            <input type="file" name="pas_foto" x-ref="fileFoto" @change="files.foto = $event.target.files[0]?.name" class="hidden" accept=".jpg,.png">
                            
                            <div x-show="!files.foto" class="flex flex-col items-center">
                                <i data-feather="camera" class="w-6 h-6 text-gray-400 mb-3 group-hover:text-brand-blue transition-colors"></i>
                                <h4 class="text-[13px] font-extrabold text-brand-dark mb-1">Pas Foto 4x6</h4>
                                <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-3">JPG, Max 2MB</p>
                                <span class="text-[12px] font-black text-brand-blue underline underline-offset-2">Pilih File</span>
                            </div>
                            
                            <div x-show="files.foto" class="flex flex-col items-center" x-cloak>
                                <div class="w-10 h-10 bg-brand-blue text-white rounded-full flex items-center justify-center mb-2">
                                    <i data-feather="check" class="w-5 h-5"></i>
                                </div>
                                <h4 class="text-[12px] font-extrabold text-brand-dark line-clamp-1 px-2" x-text="files.foto"></h4>
                            </div>
                        </div>

                        <!-- KTP -->
                        <div class="border-2 border-dashed border-gray-200 rounded-2xl p-6 flex flex-col items-center justify-center text-center cursor-pointer hover:border-brand-blue hover:bg-brand-blue-light/50 transition-all group"
                            @click="$refs.fileKtp.click()">
                            <input type="file" name="scan_ktp" x-ref="fileKtp" @change="files.ktp = $event.target.files[0]?.name" class="hidden" accept=".jpg,.pdf">
                            
                            <div x-show="!files.ktp" class="flex flex-col items-center">
                                <i data-feather="credit-card" class="w-6 h-6 text-gray-400 mb-3 group-hover:text-brand-blue transition-colors"></i>
                                <h4 class="text-[13px] font-extrabold text-brand-dark mb-1">Scan KTP</h4>
                                <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-3">PDF/JPG, Max 2MB</p>
                                <span class="text-[12px] font-black text-brand-blue underline underline-offset-2">Pilih File</span>
                            </div>

                            <div x-show="files.ktp" class="flex flex-col items-center" x-cloak>
                                <div class="w-10 h-10 bg-brand-blue text-white rounded-full flex items-center justify-center mb-2">
                                    <i data-feather="check" class="w-5 h-5"></i>
                                </div>
                                <h4 class="text-[12px] font-extrabold text-brand-dark line-clamp-1 px-2" x-text="files.ktp"></h4>
                            </div>
                        </div>

                        <!-- Ijazah -->
                        <div class="border-2 border-dashed border-gray-200 rounded-2xl p-6 flex flex-col items-center justify-center text-center cursor-pointer hover:border-brand-blue hover:bg-brand-blue-light/50 transition-all group"
                            @click="$refs.fileIjazah.click()">
                            <input type="file" name="ijazah_skl" x-ref="fileIjazah" @change="files.ijazah = $event.target.files[0]?.name" class="hidden" accept=".pdf">
                            
                            <div x-show="!files.ijazah" class="flex flex-col items-center">
                                <i data-feather="file-text" class="w-6 h-6 text-gray-400 mb-3 group-hover:text-brand-blue transition-colors"></i>
                                <h4 class="text-[13px] font-extrabold text-brand-dark mb-1">Ijazah / SKL</h4>
                                <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-3">PDF, Max 2MB</p>
                                <span class="text-[12px] font-black text-brand-blue underline underline-offset-2">Pilih File</span>
                            </div>

                            <div x-show="files.ijazah" class="flex flex-col items-center" x-cloak>
                                <div class="w-10 h-10 bg-brand-blue text-white rounded-full flex items-center justify-center mb-2">
                                    <i data-feather="check" class="w-5 h-5"></i>
                                </div>
                                <h4 class="text-[12px] font-extrabold text-brand-dark line-clamp-1 px-2" x-text="files.ijazah"></h4>
                            </div>
                        </div>
                    </div>
                </section>

                <div class="pt-6 border-t border-gray-100 flex flex-col items-center gap-6">
                    <div class="flex items-center gap-2 text-[12px] font-bold text-green-600 bg-green-50 px-4 py-2 rounded-lg w-full md:w-auto justify-center">
                        <i data-feather="check-circle" class="w-4 h-4"></i> Data tersimpan otomatis
                    </div>

                    <button type="submit" class="w-full py-4 bg-brand-dark text-white rounded-2xl font-black text-[15px] hover:bg-brand-blue shadow-lg shadow-brand-dark/20 transition-all active:scale-[0.98]">
                        Simpan & Lanjutkan
                    </button>

                    <a href="/validasi-pembayaran" class="text-[13px] font-extrabold text-gray-500 hover:text-brand-dark transition-colors py-2">
                        Kembali
                    </a>
                </div>
            </from>
        </div>
    </main>

    <footer class="w-full bg-brand-bg py-8 flex flex-col md:flex-row justify-center md:justify-between items-center gap-4 px-10">
        <p class="text-[11px] font-bold text-gray-400">© 2026 Universitas Adzkia. All Rights Reserved.</p>
        <div class="flex gap-8 text-[11px] font-bold text-brand-gray">
            <a href="#" class="hover:text-brand-blue transition-colors">Privacy Policy</a>
            <a href="#" class="hover:text-brand-blue transition-colors">Contact Support</a>
            <a href="#" class="hover:text-brand-blue transition-colors">Terms of Service</a>
        </div>
    </footer>

    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('formulirApp', () => ({
                currentStep: 4,
                
                // State untuk menyimpan nama file yang diunggah
                files: {
                    foto: null,
                    ktp: null,
                    ijazah: null
                },

                steps: [
                    { id: 1, title: 'Pendaftaran' },
                    { id: 2, title: 'Biaya Pendaftaran' },
                    { id: 3, title: 'Validasi Pembayaran' },
                    { id: 4, title: 'Biodata' },
                    { id: 5, title: 'Dokumen' },
                    { id: 6, title: 'Ujian' },
                    { id: 7, title: 'Hasil' }
                ],

                submitForm() {
                    // Cek sederhana jika file belum lengkap (opsional)
                    // if(!this.files.foto || !this.files.ktp || !this.files.ijazah) {
                    //    alert('Mohon lengkapi semua unggahan dokumen!');
                    //    return;
                    // }
                    
                    alert("Data berhasil disimpan! Melanjutkan ke Step 5: Konfirmasi Data...");
                    window.location.href = '/konfirmasi-data';
                }
            }));
        });

        // Initialize Feather Icons
        document.addEventListener('DOMContentLoaded', () => {
            feather.replace();
        });
    </script>
</body>
</html>