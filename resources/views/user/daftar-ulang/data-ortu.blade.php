<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Orang Tua - Daftar Ulang SPMB Adzkia</title>
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
      x-data="{ waliExpanded: {{ !empty($dataOrtu['wali']['nama'] ?? '') ? 'true' : 'false' }} }">

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
    <x-step-tracker :current-step="4" />

    {{-- ============================================================ --}}
    {{-- MAIN                                                          --}}
    {{-- ============================================================ --}}
    <main class="flex-1 max-w-4xl mx-auto w-full px-4 sm:px-6 py-6 sm:py-10">

        <div class="mb-6 sm:mb-8">
            <span class="inline-block px-3 py-1 bg-adzkia-badge-bg text-adzkia-blue rounded-lg text-[10px] sm:text-[11px] font-black uppercase tracking-widest mb-2 sm:mb-3">STEP 03 / 05 — DAFTAR ULANG</span>
            <h1 class="text-2xl sm:text-3xl font-black text-adzkia-dark tracking-tight">Data Orang Tua / Wali</h1>
            <p class="text-[13px] sm:text-[14px] font-medium text-gray-500 mt-1.5 sm:mt-2">Lengkapi data orang tua atau wali yang bertanggung jawab. Data wali bersifat opsional.</p>
        </div>

        {{-- Error --}}
        @if ($errors->any())
            <div class="bg-red-50 border-l-4 border-adzkia-red p-4 mb-6 rounded-r-2xl">
                <h3 class="text-xs sm:text-sm font-black text-adzkia-red uppercase tracking-widest mb-2">Oops! Ada yang kurang:</h3>
                <ul class="text-[12px] sm:text-[13px] font-bold text-red-600 list-disc list-inside space-y-1">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        {{-- Success --}}
        @if (session('success'))
            <div class="bg-green-50 border-l-4 border-green-500 p-4 mb-6 rounded-r-2xl flex items-center gap-3">
                <i data-feather="check-circle" class="w-5 h-5 text-green-500 shrink-0"></i>
                <p class="text-[13px] font-bold text-green-700">{{ session('success') }}</p>
            </div>
        @endif

        <form action="{{ route('daftar-ulang.simpan-ortu') }}" method="POST">
            @csrf

            {{-- ================================================ --}}
            {{-- BAGIAN 1: DATA AYAH                              --}}
            {{-- ================================================ --}}
            <div class="bg-white p-5 sm:p-8 rounded-2xl sm:rounded-[2rem] shadow-sm border border-gray-100 mb-5">

                <div class="flex items-center gap-2 sm:gap-3 mb-5 sm:mb-7">
                    <div class="w-9 h-9 sm:w-11 sm:h-11 bg-blue-50 text-adzkia-blue rounded-xl flex items-center justify-center shrink-0">
                        <i data-feather="user" class="w-4 h-4 sm:w-5 sm:h-5"></i>
                    </div>
                    <div>
                        <h2 class="text-base sm:text-lg font-black text-adzkia-dark">Data Ayah</h2>
                        <p class="text-[11px] sm:text-[12px] font-medium text-gray-400">Informasi orang tua kandung (Ayah)</p>
                    </div>
                    <span class="ml-auto text-[10px] font-black text-adzkia-red bg-red-50 px-2.5 py-1 rounded-lg">WAJIB</span>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 sm:gap-5">

                    {{-- Nama Ayah --}}
                    <div class="sm:col-span-2">
                        <label class="block text-[10px] sm:text-[11px] font-black text-gray-400 uppercase tracking-widest mb-1.5 px-1">Nama Lengkap Ayah</label>
                        <input type="text" name="ayah_nama"
                               value="{{ old('ayah_nama', $dataOrtu['ayah']['nama'] ?? '') }}"
                               placeholder="Nama sesuai KTP"
                               required
                               class="w-full px-4 sm:px-5 py-3.5 sm:py-4 bg-gray-50 border border-transparent rounded-2xl outline-none focus:border-adzkia-blue focus:bg-white transition-all font-bold text-[13px] sm:text-[14px] text-adzkia-dark">
                    </div>

                    {{-- Pekerjaan & No HP --}}
                    <div>
                        <label class="block text-[10px] sm:text-[11px] font-black text-gray-400 uppercase tracking-widest mb-1.5 px-1">Pekerjaan</label>
                        <input type="text" name="ayah_pekerjaan"
                               value="{{ old('ayah_pekerjaan', $dataOrtu['ayah']['pekerjaan'] ?? '') }}"
                               placeholder="Contoh: PNS, Wirausaha"
                               class="w-full px-4 sm:px-5 py-3.5 sm:py-4 bg-gray-50 border border-transparent rounded-2xl outline-none focus:border-adzkia-blue focus:bg-white transition-all font-bold text-[13px] sm:text-[14px] text-adzkia-dark">
                    </div>

                    <div>
                        <label class="block text-[10px] sm:text-[11px] font-black text-gray-400 uppercase tracking-widest mb-1.5 px-1">No. HP / WhatsApp</label>
                        <input type="text" name="ayah_no_hp"
                               value="{{ old('ayah_no_hp', $dataOrtu['ayah']['no_hp'] ?? '') }}"
                               placeholder="0812xxxx"
                               class="w-full px-4 sm:px-5 py-3.5 sm:py-4 bg-gray-50 border border-transparent rounded-2xl outline-none focus:border-adzkia-blue focus:bg-white transition-all font-bold text-[13px] sm:text-[14px] text-adzkia-dark">
                    </div>

                    {{-- Penghasilan & Pendidikan --}}
                    <div>
                        <label class="block text-[10px] sm:text-[11px] font-black text-gray-400 uppercase tracking-widest mb-1.5 px-1">Penghasilan / Bulan (Rp)</label>
                        <input type="number" name="ayah_penghasilan"
                               value="{{ old('ayah_penghasilan', $dataOrtu['ayah']['penghasilan'] ?? '') }}"
                               placeholder="3000000"
                               min="0"
                               class="w-full px-4 sm:px-5 py-3.5 sm:py-4 bg-gray-50 border border-transparent rounded-2xl outline-none focus:border-adzkia-blue focus:bg-white transition-all font-bold text-[13px] sm:text-[14px] text-adzkia-dark">
                    </div>

                    <div>
                        <label class="block text-[10px] sm:text-[11px] font-black text-gray-400 uppercase tracking-widest mb-1.5 px-1">Pendidikan Terakhir</label>
                        <div class="relative">
                            <select name="ayah_pendidikan_terakhir"
                                    class="w-full px-4 sm:px-5 py-3.5 sm:py-4 bg-gray-50 border border-transparent rounded-2xl outline-none focus:border-adzkia-blue focus:bg-white transition-all font-bold text-[13px] sm:text-[14px] text-adzkia-dark appearance-none cursor-pointer">
                                <option value="" disabled {{ empty($dataOrtu['ayah']['pendidikan_terakhir'] ?? '') ? 'selected' : '' }}>Pilih Pendidikan</option>
                                @foreach(['SD','SMP','SMA/SMK','D3','S1','S2','S3','Lainnya'] as $pend)
                                    <option value="{{ $pend }}" {{ old('ayah_pendidikan_terakhir', $dataOrtu['ayah']['pendidikan_terakhir'] ?? '') == $pend ? 'selected' : '' }}>{{ $pend }}</option>
                                @endforeach
                            </select>
                            <i data-feather="chevron-down" class="w-4 h-4 text-gray-400 absolute right-4 sm:right-5 top-1/2 -translate-y-1/2 pointer-events-none"></i>
                        </div>
                    </div>

                    {{-- Alamat --}}
                    <div class="sm:col-span-2">
                        <label class="block text-[10px] sm:text-[11px] font-black text-gray-400 uppercase tracking-widest mb-1.5 px-1">Alamat (jika berbeda)</label>
                        <textarea name="ayah_alamat" rows="2"
                                  placeholder="Kosongkan jika sama dengan alamat calon mahasiswa"
                                  class="w-full px-4 sm:px-5 py-3.5 sm:py-4 bg-gray-50 border border-transparent rounded-2xl outline-none focus:border-adzkia-blue focus:bg-white transition-all font-bold text-[13px] sm:text-[14px] text-adzkia-dark resize-none">{{ old('ayah_alamat', $dataOrtu['ayah']['alamat'] ?? '') }}</textarea>
                    </div>

                </div>
            </div>

            {{-- ================================================ --}}
            {{-- BAGIAN 2: DATA IBU                               --}}
            {{-- ================================================ --}}
            <div class="bg-white p-5 sm:p-8 rounded-2xl sm:rounded-[2rem] shadow-sm border border-gray-100 mb-5">

                <div class="flex items-center gap-2 sm:gap-3 mb-5 sm:mb-7">
                    <div class="w-9 h-9 sm:w-11 sm:h-11 bg-pink-50 text-pink-500 rounded-xl flex items-center justify-center shrink-0">
                        <i data-feather="heart" class="w-4 h-4 sm:w-5 sm:h-5"></i>
                    </div>
                    <div>
                        <h2 class="text-base sm:text-lg font-black text-adzkia-dark">Data Ibu</h2>
                        <p class="text-[11px] sm:text-[12px] font-medium text-gray-400">Informasi orang tua kandung (Ibu)</p>
                    </div>
                    <span class="ml-auto text-[10px] font-black text-adzkia-red bg-red-50 px-2.5 py-1 rounded-lg">WAJIB</span>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 sm:gap-5">

                    <div class="sm:col-span-2">
                        <label class="block text-[10px] sm:text-[11px] font-black text-gray-400 uppercase tracking-widest mb-1.5 px-1">Nama Lengkap Ibu</label>
                        <input type="text" name="ibu_nama"
                               value="{{ old('ibu_nama', $dataOrtu['ibu']['nama'] ?? '') }}"
                               placeholder="Nama sesuai KTP"
                               required
                               class="w-full px-4 sm:px-5 py-3.5 sm:py-4 bg-gray-50 border border-transparent rounded-2xl outline-none focus:border-adzkia-blue focus:bg-white transition-all font-bold text-[13px] sm:text-[14px] text-adzkia-dark">
                    </div>

                    <div>
                        <label class="block text-[10px] sm:text-[11px] font-black text-gray-400 uppercase tracking-widest mb-1.5 px-1">Pekerjaan</label>
                        <input type="text" name="ibu_pekerjaan"
                               value="{{ old('ibu_pekerjaan', $dataOrtu['ibu']['pekerjaan'] ?? '') }}"
                               placeholder="Contoh: Ibu Rumah Tangga, Guru"
                               class="w-full px-4 sm:px-5 py-3.5 sm:py-4 bg-gray-50 border border-transparent rounded-2xl outline-none focus:border-adzkia-blue focus:bg-white transition-all font-bold text-[13px] sm:text-[14px] text-adzkia-dark">
                    </div>

                    <div>
                        <label class="block text-[10px] sm:text-[11px] font-black text-gray-400 uppercase tracking-widest mb-1.5 px-1">No. HP / WhatsApp</label>
                        <input type="text" name="ibu_no_hp"
                               value="{{ old('ibu_no_hp', $dataOrtu['ibu']['no_hp'] ?? '') }}"
                               placeholder="0812xxxx"
                               class="w-full px-4 sm:px-5 py-3.5 sm:py-4 bg-gray-50 border border-transparent rounded-2xl outline-none focus:border-adzkia-blue focus:bg-white transition-all font-bold text-[13px] sm:text-[14px] text-adzkia-dark">
                    </div>

                    <div>
                        <label class="block text-[10px] sm:text-[11px] font-black text-gray-400 uppercase tracking-widest mb-1.5 px-1">Penghasilan / Bulan (Rp)</label>
                        <input type="number" name="ibu_penghasilan"
                               value="{{ old('ibu_penghasilan', $dataOrtu['ibu']['penghasilan'] ?? '') }}"
                               placeholder="0 jika tidak bekerja"
                               min="0"
                               class="w-full px-4 sm:px-5 py-3.5 sm:py-4 bg-gray-50 border border-transparent rounded-2xl outline-none focus:border-adzkia-blue focus:bg-white transition-all font-bold text-[13px] sm:text-[14px] text-adzkia-dark">
                    </div>

                    <div>
                        <label class="block text-[10px] sm:text-[11px] font-black text-gray-400 uppercase tracking-widest mb-1.5 px-1">Pendidikan Terakhir</label>
                        <div class="relative">
                            <select name="ibu_pendidikan_terakhir"
                                    class="w-full px-4 sm:px-5 py-3.5 sm:py-4 bg-gray-50 border border-transparent rounded-2xl outline-none focus:border-adzkia-blue focus:bg-white transition-all font-bold text-[13px] sm:text-[14px] text-adzkia-dark appearance-none cursor-pointer">
                                <option value="" disabled {{ empty($dataOrtu['ibu']['pendidikan_terakhir'] ?? '') ? 'selected' : '' }}>Pilih Pendidikan</option>
                                @foreach(['SD','SMP','SMA/SMK','D3','S1','S2','S3','Lainnya'] as $pend)
                                    <option value="{{ $pend }}" {{ old('ibu_pendidikan_terakhir', $dataOrtu['ibu']['pendidikan_terakhir'] ?? '') == $pend ? 'selected' : '' }}>{{ $pend }}</option>
                                @endforeach
                            </select>
                            <i data-feather="chevron-down" class="w-4 h-4 text-gray-400 absolute right-4 sm:right-5 top-1/2 -translate-y-1/2 pointer-events-none"></i>
                        </div>
                    </div>

                    <div class="sm:col-span-2">
                        <label class="block text-[10px] sm:text-[11px] font-black text-gray-400 uppercase tracking-widest mb-1.5 px-1">Alamat (jika berbeda)</label>
                        <textarea name="ibu_alamat" rows="2"
                                  placeholder="Kosongkan jika sama dengan alamat calon mahasiswa"
                                  class="w-full px-4 sm:px-5 py-3.5 sm:py-4 bg-gray-50 border border-transparent rounded-2xl outline-none focus:border-adzkia-blue focus:bg-white transition-all font-bold text-[13px] sm:text-[14px] text-adzkia-dark resize-none">{{ old('ibu_alamat', $dataOrtu['ibu']['alamat'] ?? '') }}</textarea>
                    </div>

                </div>
            </div>

            {{-- ================================================ --}}
            {{-- BAGIAN 3: DATA WALI (OPSIONAL, collapsible)      --}}
            {{-- ================================================ --}}
            <div class="bg-white rounded-2xl sm:rounded-[2rem] shadow-sm border border-gray-100 mb-8 overflow-hidden">

                {{-- Toggle Header --}}
                <button type="button"
                        @click="waliExpanded = !waliExpanded"
                        class="w-full flex items-center gap-2 sm:gap-3 p-5 sm:p-8 text-left hover:bg-gray-50 transition-colors">
                    <div class="w-9 h-9 sm:w-11 sm:h-11 bg-amber-50 text-amber-500 rounded-xl flex items-center justify-center shrink-0">
                        <i data-feather="users" class="w-4 h-4 sm:w-5 sm:h-5"></i>
                    </div>
                    <div class="flex-1">
                        <h2 class="text-base sm:text-lg font-black text-adzkia-dark">Data Wali</h2>
                        <p class="text-[11px] sm:text-[12px] font-medium text-gray-400">Isi jika calon mahasiswa tinggal bersama wali</p>
                    </div>
                    <span class="text-[10px] font-black text-gray-400 bg-gray-100 px-2.5 py-1 rounded-lg mr-2">OPSIONAL</span>
                    <div class="w-8 h-8 rounded-xl bg-gray-100 flex items-center justify-center transition-transform duration-300"
                         :class="waliExpanded ? 'rotate-180' : ''">
                        <i data-feather="chevron-down" class="w-4 h-4 text-gray-500"></i>
                    </div>
                </button>

                {{-- Collapsible Content --}}
                <div x-show="waliExpanded"
                     x-transition:enter="transition ease-out duration-200"
                     x-transition:enter-start="opacity-0 -translate-y-2"
                     x-transition:enter-end="opacity-100 translate-y-0"
                     x-transition:leave="transition ease-in duration-150"
                     x-transition:leave-start="opacity-100 translate-y-0"
                     x-transition:leave-end="opacity-0 -translate-y-2"
                     x-cloak
                     class="px-5 sm:px-8 pb-5 sm:pb-8 border-t border-gray-100">

                    <div class="pt-5 sm:pt-7 grid grid-cols-1 sm:grid-cols-2 gap-4 sm:gap-5">

                        {{-- Hubungan Wali --}}
                        <div class="sm:col-span-2">
                            <label class="block text-[10px] sm:text-[11px] font-black text-gray-400 uppercase tracking-widest mb-1.5 px-1">Hubungan dengan Calon Mahasiswa</label>
                            <div class="relative">
                                <select name="wali_hubungan"
                                        class="w-full px-4 sm:px-5 py-3.5 sm:py-4 bg-gray-50 border border-transparent rounded-2xl outline-none focus:border-adzkia-blue focus:bg-white transition-all font-bold text-[13px] sm:text-[14px] text-adzkia-dark appearance-none cursor-pointer">
                                    <option value="" {{ empty($dataOrtu['wali']['hubungan'] ?? '') ? 'selected' : '' }}>Pilih Hubungan</option>
                                    @foreach(['Kakek','Nenek','Paman','Bibi','Kakak Kandung','Saudara Kandung','Lainnya'] as $hub)
                                        <option value="{{ $hub }}" {{ old('wali_hubungan', $dataOrtu['wali']['hubungan'] ?? '') == $hub ? 'selected' : '' }}>{{ $hub }}</option>
                                    @endforeach
                                </select>
                                <i data-feather="chevron-down" class="w-4 h-4 text-gray-400 absolute right-4 sm:right-5 top-1/2 -translate-y-1/2 pointer-events-none"></i>
                            </div>
                        </div>

                        <div class="sm:col-span-2">
                            <label class="block text-[10px] sm:text-[11px] font-black text-gray-400 uppercase tracking-widest mb-1.5 px-1">Nama Lengkap Wali</label>
                            <input type="text" name="wali_nama"
                                   value="{{ old('wali_nama', $dataOrtu['wali']['nama'] ?? '') }}"
                                   placeholder="Nama sesuai KTP"
                                   class="w-full px-4 sm:px-5 py-3.5 sm:py-4 bg-gray-50 border border-transparent rounded-2xl outline-none focus:border-adzkia-blue focus:bg-white transition-all font-bold text-[13px] sm:text-[14px] text-adzkia-dark">
                        </div>

                        <div>
                            <label class="block text-[10px] sm:text-[11px] font-black text-gray-400 uppercase tracking-widest mb-1.5 px-1">Pekerjaan</label>
                            <input type="text" name="wali_pekerjaan"
                                   value="{{ old('wali_pekerjaan', $dataOrtu['wali']['pekerjaan'] ?? '') }}"
                                   placeholder="Pekerjaan wali"
                                   class="w-full px-4 sm:px-5 py-3.5 sm:py-4 bg-gray-50 border border-transparent rounded-2xl outline-none focus:border-adzkia-blue focus:bg-white transition-all font-bold text-[13px] sm:text-[14px] text-adzkia-dark">
                        </div>

                        <div>
                            <label class="block text-[10px] sm:text-[11px] font-black text-gray-400 uppercase tracking-widest mb-1.5 px-1">No. HP / WhatsApp</label>
                            <input type="text" name="wali_no_hp"
                                   value="{{ old('wali_no_hp', $dataOrtu['wali']['no_hp'] ?? '') }}"
                                   placeholder="0812xxxx"
                                   class="w-full px-4 sm:px-5 py-3.5 sm:py-4 bg-gray-50 border border-transparent rounded-2xl outline-none focus:border-adzkia-blue focus:bg-white transition-all font-bold text-[13px] sm:text-[14px] text-adzkia-dark">
                        </div>

                        <div>
                            <label class="block text-[10px] sm:text-[11px] font-black text-gray-400 uppercase tracking-widest mb-1.5 px-1">Penghasilan / Bulan (Rp)</label>
                            <input type="number" name="wali_penghasilan"
                                   value="{{ old('wali_penghasilan', $dataOrtu['wali']['penghasilan'] ?? '') }}"
                                   placeholder="0"
                                   min="0"
                                   class="w-full px-4 sm:px-5 py-3.5 sm:py-4 bg-gray-50 border border-transparent rounded-2xl outline-none focus:border-adzkia-blue focus:bg-white transition-all font-bold text-[13px] sm:text-[14px] text-adzkia-dark">
                        </div>

                        <div>
                            <label class="block text-[10px] sm:text-[11px] font-black text-gray-400 uppercase tracking-widest mb-1.5 px-1">Pendidikan Terakhir</label>
                            <div class="relative">
                                <select name="wali_pendidikan_terakhir"
                                        class="w-full px-4 sm:px-5 py-3.5 sm:py-4 bg-gray-50 border border-transparent rounded-2xl outline-none focus:border-adzkia-blue focus:bg-white transition-all font-bold text-[13px] sm:text-[14px] text-adzkia-dark appearance-none cursor-pointer">
                                    <option value="" {{ empty($dataOrtu['wali']['pendidikan_terakhir'] ?? '') ? 'selected' : '' }}>Pilih Pendidikan</option>
                                    @foreach(['SD','SMP','SMA/SMK','D3','S1','S2','S3','Lainnya'] as $pend)
                                        <option value="{{ $pend }}" {{ old('wali_pendidikan_terakhir', $dataOrtu['wali']['pendidikan_terakhir'] ?? '') == $pend ? 'selected' : '' }}>{{ $pend }}</option>
                                    @endforeach
                                </select>
                                <i data-feather="chevron-down" class="w-4 h-4 text-gray-400 absolute right-4 sm:right-5 top-1/2 -translate-y-1/2 pointer-events-none"></i>
                            </div>
                        </div>

                        <div class="sm:col-span-2">
                            <label class="block text-[10px] sm:text-[11px] font-black text-gray-400 uppercase tracking-widest mb-1.5 px-1">Alamat Wali</label>
                            <textarea name="wali_alamat" rows="2"
                                      placeholder="Alamat lengkap wali"
                                      class="w-full px-4 sm:px-5 py-3.5 sm:py-4 bg-gray-50 border border-transparent rounded-2xl outline-none focus:border-adzkia-blue focus:bg-white transition-all font-bold text-[13px] sm:text-[14px] text-adzkia-dark resize-none">{{ old('wali_alamat', $dataOrtu['wali']['alamat'] ?? '') }}</textarea>
                        </div>

                    </div>
                </div>
            </div>

            {{-- ================================================ --}}
            {{-- TOMBOL SUBMIT                                     --}}
            {{-- ================================================ --}}
            <div class="flex flex-col sm:flex-row items-center justify-between gap-4 pb-10">
                <a href="{{ route('dashboard.user') }}"
                   class="w-full sm:w-auto flex items-center justify-center gap-2 px-6 py-3.5 bg-gray-100 text-adzkia-dark rounded-2xl font-black text-[13px] sm:text-[14px] hover:bg-gray-200 transition-all">
                    <i data-feather="arrow-left" class="w-4 h-4"></i>
                    Kembali
                </a>
                <button type="submit"
                        class="w-full sm:w-auto flex items-center justify-center gap-2 px-8 py-3.5 sm:py-4 bg-adzkia-blue text-white rounded-2xl font-black text-[14px] sm:text-[15px] hover:bg-blue-700 shadow-lg shadow-blue-600/20 transition-all active:scale-[0.98]">
                    Simpan & Lanjut ke Pembayaran
                    <i data-feather="arrow-right" class="w-4 h-4"></i>
                </button>
            </div>

        </form>
    </main>

    <script>
        document.addEventListener('DOMContentLoaded', () => { feather.replace(); });
    </script>
</body>
</html>
