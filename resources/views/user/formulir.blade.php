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
        .autocomplete-scroll::-webkit-scrollbar { width: 4px; }
        .autocomplete-scroll::-webkit-scrollbar-thumb { background: #E2E8F0; border-radius: 10px; }
        /* Sembunyikan tombol spinner bawaan browser di input number tahun lulus */
        input[name="tahun_lulus"]::-webkit-outer-spin-button,
        input[name="tahun_lulus"]::-webkit-inner-spin-button { opacity: 1; }
    </style>
</head>
<body class="bg-gray-50 antialiased text-adzkia-dark min-h-screen flex flex-col"
      x-data="formulirApp()" x-init="init()">

    {{-- ── NAVBAR ──────────────────────────────────────────────── --}}
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

    {{-- ── STEP TRACKER ────────────────────────────────────────── --}}
    <x-step-tracker :current-step="3" />

    {{-- ── MAIN ────────────────────────────────────────────────── --}}
    <main class="flex-1 max-w-4xl mx-auto w-full px-4 sm:px-6 py-6 sm:py-10">

        <div class="mb-6 sm:mb-8">
            <span class="inline-block px-3 py-1 bg-adzkia-badge-bg text-adzkia-blue rounded-lg text-[10px] sm:text-[11px] font-black uppercase tracking-widest mb-2 sm:mb-3">STEP 03 / 05</span>
            <h1 class="text-2xl sm:text-3xl font-black text-adzkia-dark tracking-tight">Formulir Biodata Diri</h1>
            <p class="text-[13px] sm:text-[14px] font-medium text-gray-500 mt-1.5 sm:mt-2">Lengkapi data pribadi dan unggah dokumen syarat.</p>
        </div>

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

                @if($errors->any())
                <div class="bg-red-50 border-l-4 border-adzkia-red p-4 mb-6 sm:mb-8 rounded-r-2xl">
                    <h3 class="text-xs sm:text-sm font-black text-adzkia-red uppercase tracking-widest mb-2">Oops! Ada yang kurang:</h3>
                    <ul class="text-[12px] sm:text-[13px] font-bold text-red-600 list-disc list-inside">
                        @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
                @endif

                {{-- ── BAGIAN 1: DATA DIRI ──────────────────────────── --}}
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

                {{-- ── BAGIAN 2: KONTAK & WILAYAH ───────────────────── --}}
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
                                <select x-model="selectedProv"
                                        @change="loadCities(selectedProv); selectedProvName = (provinces.find(p => p.code === selectedProv) || {}).name || ''"
                                        required
                                        class="w-full px-4 sm:px-5 py-3.5 sm:py-4 bg-gray-50 border border-transparent rounded-2xl outline-none focus:border-adzkia-blue focus:bg-white transition-all font-bold text-[13px] sm:text-[14px] text-adzkia-dark appearance-none cursor-pointer disabled:opacity-50 disabled:cursor-not-allowed"
                                        {{ $isLocked ? 'disabled' : '' }}>
                                    <option value="" disabled>Pilih Provinsi</option>
                                    <template x-for="prov in provinces" :key="prov.code">
                                        <option :value="prov.code" x-text="prov.name"></option>
                                    </template>
                                </select>
                                {{-- Nilai sebenarnya yang dikirim ke server: NAMA provinsi, bukan kode --}}
                                <input type="hidden" name="provinsi" x-model="selectedProvName">
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

                {{-- ── BAGIAN 3: ASAL PENDIDIKAN ────────────────────── --}}
                <section>
                    <div class="flex items-center gap-2 sm:gap-3 mb-4 sm:mb-6">
                        <div class="w-8 h-8 sm:w-10 sm:h-10 bg-adzkia-badge-bg text-adzkia-blue rounded-xl flex items-center justify-center shrink-0">
                            <i data-feather="book" class="w-4 h-4 sm:w-5 sm:h-5"></i>
                        </div>
                        <h2 class="text-base sm:text-lg font-black text-adzkia-dark">Asal Pendidikan</h2>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 sm:gap-6">

                        {{-- ── SEKOLAH ASAL — Autocomplete PDDikti ─── --}}
                        <div class="sm:col-span-2">
                            <label class="block text-[10px] sm:text-[11px] font-black text-gray-400 uppercase tracking-widest mb-1.5 sm:mb-2 px-1">
                                Asal Sekolah <span class="text-red-400">*</span>
                            </label>

                            @if($isLocked)
                                {{-- Mode terkunci: tampil sebagai teks --}}
                                <div class="w-full px-4 sm:px-5 py-3.5 sm:py-4 bg-gray-50 border border-transparent rounded-2xl font-bold text-[13px] sm:text-[14px] text-adzkia-dark opacity-70">
                                    {{ $pendaftar->sekolah_asal ?? '—' }}
                                </div>
                                <input type="hidden" name="sekolah_asal"  value="{{ $pendaftar->sekolah_asal ?? '' }}">
                                <input type="hidden" name="sekolah_id"    value="{{ $pendaftar->sekolah_id ?? '' }}">
                            @else
                                {{-- Mode edit: autocomplete dari DB lokal + fallback API PDDikti --}}
                                <div x-data="sekolahAC(
                                        '{{ old('sekolah_asal', $pendaftar->sekolah_asal ?? '') }}',
                                        '{{ old('sekolah_id',   $pendaftar->sekolah_id ?? '') }}',
                                        '{{ old('npsn_sekolah', $pendaftar->npsn_sekolah ?? '') }}'
                                     )"
                                     class="relative">

                                    <div class="relative">
                                        <input type="text"
                                               x-model="query"
                                               x-on:input.debounce.350ms="cari()"
                                               x-on:focus="if(query.length >= 2 && !dipilih) buka = true"
                                               x-on:keydown.arrow-down.prevent="nav(1)"
                                               x-on:keydown.arrow-up.prevent="nav(-1)"
                                               x-on:keydown.enter.prevent="pilihAktif()"
                                               x-on:keydown.escape="tutup()"
                                               placeholder="Ketik nama atau NPSN sekolah..."
                                               autocomplete="off"
                                               required
                                               class="w-full px-4 sm:px-5 py-3.5 sm:py-4 border rounded-2xl outline-none transition-all font-bold text-[13px] sm:text-[14px] text-adzkia-dark pr-12"
                                               :class="{
                                                   'bg-gray-50 border-transparent': !dipilih && !query,
                                                   'bg-white border-adzkia-blue':   !dipilih && query.length > 0,
                                                   'bg-emerald-50 border-emerald-400': dipilih
                                               }">

                                        {{-- Icon kanan --}}
                                        <div class="absolute right-4 top-1/2 -translate-y-1/2 flex items-center gap-1">
                                            <template x-if="loading">
                                                <div class="w-4 h-4 border-2 border-gray-300 border-t-adzkia-blue rounded-full animate-spin"></div>
                                            </template>
                                            <template x-if="!loading && dipilih">
                                                <div class="flex items-center gap-1">
                                                    <svg class="w-4 h-4 text-emerald-500" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><polyline points="20 6 9 17 4 12"/></svg>
                                                    <button type="button" x-on:click="reset()" class="text-gray-300 hover:text-red-400" title="Ganti sekolah">
                                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
                                                    </button>
                                                </div>
                                            </template>
                                            <template x-if="!loading && !dipilih && query.length > 0">
                                                <button type="button" x-on:click="reset()">
                                                    <svg class="w-4 h-4 text-gray-400 hover:text-red-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
                                                </button>
                                            </template>
                                        </div>
                                    </div>

                                    {{-- Dropdown --}}
                                    <div x-show="buka && (hasil.length > 0 || (query.length >= 2 && !loading))"
                                         x-cloak
                                         x-on:click.away="tutup()"
                                         x-transition:enter="transition ease-out duration-150"
                                         x-transition:enter-start="opacity-0 -translate-y-1"
                                         x-transition:enter-end="opacity-100 translate-y-0"
                                         class="absolute top-full left-0 right-0 mt-1.5 bg-white border border-gray-200 rounded-2xl shadow-2xl z-50 max-h-64 overflow-y-auto autocomplete-scroll"
                                         style="display:none;">

                                        {{-- Dari DB lokal --}}
                                        <template x-if="lokal.length > 0">
                                            <div>
                                                <p class="px-4 pt-3 pb-1 text-[10px] font-black text-gray-400 uppercase tracking-widest">Data Tersimpan</p>
                                                <template x-for="(item, i) in lokal" :key="'l'+i">
                                                    <button type="button" x-on:click="pilih(item)"
                                                            class="w-full px-4 py-3 text-left hover:bg-blue-50 transition-colors flex items-center gap-3 border-t border-gray-50"
                                                            :class="idx === i ? 'bg-blue-50' : ''">
                                                        <div class="w-8 h-8 bg-adzkia-badge-bg rounded-xl flex items-center justify-center shrink-0 text-adzkia-blue">
                                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M3 9l9-7 9 7v11a2 2 0 01-2 2H5a2 2 0 01-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/></svg>
                                                        </div>
                                                        <div class="min-w-0 flex-1">
                                                            <p class="font-bold text-[13px] text-adzkia-dark truncate" x-text="item.nama_sekolah"></p>
                                                            <p class="text-[11px] text-gray-400 font-medium truncate">
                                                                <span x-text="item.bentuk || ''"></span>
                                                                <span x-show="item.kota"> · <span x-text="item.kota"></span></span>
                                                                <span x-show="item.npsn" class="font-mono"> · <span x-text="item.npsn"></span></span>
                                                            </p>
                                                        </div>
                                                        <span x-show="item.status" x-text="item.status"
                                                              class="text-[10px] font-bold px-2 py-0.5 rounded-full shrink-0"
                                                              :class="item.status==='Negeri' ? 'bg-emerald-100 text-emerald-700' : 'bg-amber-100 text-amber-700'">
                                                        </span>
                                                    </button>
                                                </template>
                                            </div>
                                        </template>

                                        {{-- Tidak ditemukan → opsi tambah data baru manual --}}
                                        <template x-if="!loading && hasil.length === 0 && query.length >= 2">
                                            <div class="px-4 py-5 text-center">
                                                <p class="text-[13px] text-gray-400 font-medium mb-2">Sekolah tidak ditemukan di database.</p>
                                                <button type="button" x-on:click="tambahBaru()"
                                                        class="inline-flex items-center gap-1.5 text-[12px] font-black text-white bg-adzkia-blue px-4 py-2 rounded-xl hover:bg-blue-700 transition-colors">
                                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
                                                    Tambah Data Baru
                                                </button>
                                                <p class="text-[11px] text-gray-400 font-medium mt-2">
                                                    Nama sekolah akan memakai "<span x-text="query" class="font-bold text-adzkia-dark"></span>". Isi NPSN secara manual di bawah.
                                                </p>
                                            </div>
                                        </template>
                                    </div>

                                    {{-- Hidden fields form (sekolah) --}}
                                    <input type="hidden" name="sekolah_asal"  x-model="query">
                                    <input type="hidden" name="sekolah_id"    x-model="sid">

                                    {{-- ── NPSN SEKOLAH — field terlihat & editable, tetap
                                         di dalam x-data="sekolahAC(...)" supaya x-model="npsn"
                                         terhubung langsung dan ikut ter-submit sebagai
                                         name="npsn_sekolah". Otomatis terisi saat memilih
                                         sekolah dari dropdown, tapi tetap bisa diedit manual
                                         kalau sekolahnya tidak ditemukan di database/API. ── --}}
                                    <div class="mt-3 sm:mt-4">
                                        <label class="block text-[10px] sm:text-[11px] font-black text-gray-400 uppercase tracking-widest mb-1.5 sm:mb-2 px-1">
                                            NPSN Sekolah <span class="text-gray-300 normal-case font-medium">(otomatis terisi, bisa diedit)</span>
                                        </label>
                                        <input type="text" name="npsn_sekolah"
                                               x-model="npsn"
                                               maxlength="10"
                                               inputmode="numeric"
                                               placeholder="Nomor Pokok Sekolah Nasional"
                                               class="w-full px-4 sm:px-5 py-3.5 sm:py-4 bg-gray-50 border border-transparent rounded-2xl outline-none focus:border-adzkia-blue focus:bg-white transition-all font-bold text-[13px] sm:text-[14px] text-adzkia-dark">
                                    </div>
                                </div>
                            @endif
                        </div>

                        <div>
                            <label class="block text-[10px] sm:text-[11px] font-black text-gray-400 uppercase tracking-widest mb-1.5 sm:mb-2 px-1">Jurusan di Sekolah</label>
                            <input type="text" name="jurusan_sma"
                                   value="{{ old('jurusan_sma', $pendaftar->jurusan_sma ?? '') }}" placeholder="MIPA / IPS / RPL"
                                   class="w-full px-4 sm:px-5 py-3.5 sm:py-4 bg-gray-50 border border-transparent rounded-2xl outline-none focus:border-adzkia-blue focus:bg-white transition-all font-bold text-[13px] sm:text-[14px] text-adzkia-dark disabled:opacity-50 disabled:cursor-not-allowed"
                                   {{ $isLocked ? 'disabled' : '' }}>
                        </div>

                        {{-- ── TAHUN LULUS — 4 digit, rentang 2020 s.d. tahun sekarang ── --}}
                        <div x-data="{
                                tahun: '{{ old('tahun_lulus', $pendaftar->tahun_lulus ?? '') }}',
                                error: '',
                                tahunMin: 2020,
                                tahunMax: {{ date('Y') }},
                                validasi() {
                                    // batasi maksimal 4 digit
                                    this.tahun = this.tahun.toString().replace(/[^0-9]/g, '').slice(0, 4);
                                    if (this.tahun.length === 0) { this.error = ''; return; }
                                    const val = parseInt(this.tahun);
                                    if (this.tahun.length < 4 || val < this.tahunMin || val > this.tahunMax) {
                                        this.error = `Tahun lulus harus antara ${this.tahunMin} - ${this.tahunMax}`;
                                    } else {
                                        this.error = '';
                                    }
                                }
                             }">
                            <label class="block text-[10px] sm:text-[11px] font-black text-gray-400 uppercase tracking-widest mb-1.5 sm:mb-2 px-1">Tahun Lulus</label>

                            <p x-show="error" x-cloak x-text="error"
                               class="text-[11px] font-bold text-adzkia-red mb-1.5 px-1"></p>

                            <input type="number" name="tahun_lulus"
                                   x-model="tahun"
                                   @input="validasi()"
                                   @blur="validasi()"
                                   min="2020" :max="tahunMax"
                                   placeholder="2024"
                                   :class="error ? 'border-adzkia-red bg-red-50' : 'border-transparent bg-gray-50'"
                                   class="w-full px-4 sm:px-5 py-3.5 sm:py-4 border rounded-2xl outline-none focus:border-adzkia-blue focus:bg-white transition-all font-bold text-[13px] sm:text-[14px] text-adzkia-dark disabled:opacity-50 disabled:cursor-not-allowed"
                                   {{ $isLocked ? 'disabled' : '' }}>
                        </div>
                    </div>
                </section>

                {{-- ── BAGIAN 5: UPLOAD DOKUMEN ─────────────────────── --}}
                <section class="bg-[#F8FAFC] p-5 sm:p-8 md:p-10 rounded-2xl sm:rounded-[2rem] border border-gray-100 my-7 sm:my-10">
                    <div class="flex items-center gap-2 sm:gap-3 mb-1 sm:mb-2">
                        <div class="w-8 h-8 sm:w-10 sm:h-10 bg-adzkia-badge-bg text-adzkia-blue rounded-xl flex items-center justify-center shrink-0">
                            <i data-feather="upload-cloud" class="w-4 h-4 sm:w-5 sm:h-5"></i>
                        </div>
                        <h2 class="text-base sm:text-lg font-black text-adzkia-dark">Unggah Dokumen Syarat</h2>
                    </div>
                    <p class="text-[12px] font-medium text-gray-400 mb-4 sm:mb-6 pl-11 sm:pl-14">
                        Daftar dokumen berubah otomatis sesuai jalur pendaftaran yang dipilih.
                    </p>

                    <div x-show="!selectedJalur" x-cloak
                         class="border-2 border-dashed border-gray-200 rounded-2xl p-10 flex flex-col items-center justify-center text-center text-gray-400">
                        <i data-feather="layers" class="w-8 h-8 mb-3 text-gray-300"></i>
                        <p class="text-[13px] font-bold">Pilih jalur pendaftaran terlebih dahulu</p>
                        <p class="text-[11px] font-medium mt-1">Dokumen yang harus diunggah akan muncul di sini.</p>
                    </div>

                    <div x-show="selectedJalur" x-cloak
                         class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 sm:gap-6">
                        <template x-for="(dokumen, index) in selectedJalur ? selectedJalur.dokumen_syarat : []" :key="dokumen">
                            <div class="border-2 border-dashed rounded-2xl p-4 sm:p-6 flex flex-col items-center justify-center text-center transition-all group overflow-hidden relative"
                                 :class="uploadedFiles[dokumen]
                                    ? 'bg-gray-50 border-gray-300'
                                    : '{{ $isLocked ? 'border-gray-200 cursor-not-allowed opacity-60' : 'border-gray-200 hover:border-adzkia-blue hover:bg-blue-50 cursor-pointer' }}'"
                                 @click="if(!uploadedFiles[dokumen] && !{{ $isLocked ? 'true' : 'false' }}) triggerUpload(dokumen)">

                                <input type="file"
                                       :name="'doc_' + slugify(dokumen)"
                                       :id="'input_' + slugify(dokumen)"
                                       @change="handleDynamicUpload($event, dokumen)"
                                       class="hidden"
                                       accept=".jpg,.jpeg,.png,.pdf"
                                       {{ $isLocked ? 'disabled' : '' }}>

                                <div x-show="!uploadedFiles[dokumen]" class="flex flex-col items-center">
                                    <i data-feather="file-plus" class="w-6 h-6 text-gray-400 mb-2 sm:mb-3 {{ !$isLocked ? 'group-hover:text-adzkia-blue transition-colors' : '' }}"></i>
                                    <h4 class="text-[12px] sm:text-[13px] font-extrabold text-adzkia-dark mb-1" x-text="dokumen"></h4>
                                    <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-2 sm:mb-3">PDF/JPG/PNG · Max 2MB</p>
                                    @if(!$isLocked)
                                    <span class="text-[12px] font-black text-adzkia-blue underline underline-offset-2">Pilih File</span>
                                    @endif
                                </div>

                                <div x-show="uploadedFiles[dokumen]" class="flex flex-col items-center w-full min-w-0" x-cloak>
                                    <div class="w-9 h-9 sm:w-10 sm:h-10 bg-adzkia-blue text-white rounded-full flex items-center justify-center mb-2 shrink-0">
                                        <i data-feather="check" class="w-4 h-4 sm:w-5 sm:h-5"></i>
                                    </div>
                                    <h4 class="text-[11px] font-extrabold text-adzkia-dark w-full px-2 text-center truncate" x-text="uploadedFiles[dokumen]"></h4>
                                    <div class="flex gap-2 mt-2 sm:mt-3">
                                        <template x-if="berkasDb[dokumen]">
                                            <a :href="'/' + berkasDb[dokumen]" target="_blank"
                                               class="text-[11px] font-bold text-adzkia-blue bg-blue-100 px-3 py-1.5 rounded-lg hover:bg-blue-200 transition-colors"
                                               @click.stop>Lihat File</a>
                                        </template>
                                        @if(!$isLocked)
                                        <button type="button" @click.stop="triggerUpload(dokumen)"
                                                class="text-[11px] font-bold text-gray-600 bg-gray-200 px-3 py-1.5 rounded-lg hover:bg-gray-300 transition-colors">Ganti</button>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </template>
                    </div>
                </section>

                {{-- ── TOMBOL SUBMIT ────────────────────────────────── --}}
                <div class="pt-7 sm:pt-10 border-t border-gray-100 flex flex-col items-center gap-3 sm:gap-6 mt-7 sm:mt-10">
                    @if(!$isLocked)
                    <button type="submit"
                            class="w-full py-3.5 sm:py-4 bg-adzkia-blue text-white rounded-2xl font-black text-[14px] sm:text-[15px] hover:bg-blue-700 shadow-lg shadow-blue-600/20 transition-all flex justify-center items-center gap-2 active:scale-[0.98]">
                        <span x-show="!selectedJalur">Simpan Biodata & Lanjutkan</span>
                        <span x-show="selectedJalur && selectedJalur.is_free_registration" x-cloak>Simpan &amp; Lanjut ke Konfirmasi</span>
                        <span x-show="selectedJalur && !selectedJalur.is_free_registration" x-cloak>Simpan &amp; Lanjut ke Pembayaran</span>
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

    {{-- ── SCRIPTS ──────────────────────────────────────────────── --}}
    <script>
    // ── Formulir utama (provinsi, jalur, upload) ─────────────────
    document.addEventListener('alpine:init', () => {
        Alpine.data('formulirApp', () => ({
            provinces:    [],
            cities:       [],
            selectedProv: '',
            selectedProvName: '',
            selectedCity: '',
            jalursData:      {!! $jalursJson !!},
            selectedJalurId: '{{ old('jalur_id', $pendaftar->jalur_id ?? '') }}',
            selectedJalur:   null,
            uploadedFiles: {},
            berkasDb: {!! json_encode(
                is_array(json_decode($pendaftar->berkas_dokumen ?? '{}', true))
                    ? json_decode($pendaftar->berkas_dokumen ?? '{}', true)
                    : []
            ) !!},

            async init() {
                if (this.selectedJalurId) this.onJalurChange();

                const dbProv = '{{ old('provinsi', $pendaftar->provinsi ?? '') }}';
                const dbCity = '{{ old('kota_kabupaten', $pendaftar->kota_kabupaten ?? '') }}';

                try {
                    const res = await fetch('/data/provinsi.json');
                    if (res.ok) {
                        const json = await res.json();
                        this.provinces = json.data || [];
                        this.$nextTick(async () => {
                            if (dbProv) {
                                // dbProv sekarang berisi NAMA provinsi (bukan kode angka).
                                // Cari kode yang cocok supaya dropdown & daftar kota terisi benar.
                                const match = this.provinces.find(p => p.name === dbProv);
                                if (match) {
                                    this.selectedProv     = match.code;
                                    this.selectedProvName = match.name;
                                    await this.loadCities(match.code);
                                    this.$nextTick(() => { this.selectedCity = dbCity; });
                                } else {
                                    // Data lama yang sempat tersimpan sebagai kode angka (sebelum
                                    // perbaikan ini) — dropdown provinsi akan kosong, user perlu
                                    // pilih ulang provinsinya satu kali saja.
                                    this.selectedProvName = dbProv;
                                }
                            }
                        });
                    }
                } catch(e) {}
            },

            onJalurChange() {
                const id = parseInt(this.selectedJalurId);
                this.selectedJalur = this.jalursData.find(j => j.id === id) || null;
                if (!this.selectedJalur) return;
                this.uploadedFiles = {};
                this.selectedJalur.dokumen_syarat.forEach(dok => {
                    this.uploadedFiles[dok] = this.berkasDb[dok] ? 'Sudah Diunggah' : null;
                });
                this.$nextTick(() => { feather.replace(); });
            },

            triggerUpload(dokumen) {
                document.getElementById('input_' + this.slugify(dokumen))?.click();
            },

            handleDynamicUpload(event, dokumen) {
                const file = event.target.files[0];
                if (!file) return;
                if (file.size > 2 * 1024 * 1024) {
                    alert('Ukuran file "' + dokumen + '" maksimal 2MB!');
                    event.target.value = '';
                    return;
                }
                const allowed = ['image/jpeg','image/png','image/jpg','application/pdf'];
                if (!allowed.includes(file.type)) {
                    alert('Format file "' + dokumen + '" harus JPG, PNG, atau PDF!');
                    event.target.value = '';
                    return;
                }
                this.uploadedFiles[dokumen] = file.name;
            },

            slugify(str) { return str.toLowerCase().replace(/[^a-z0-9]+/g, '_'); },

            async loadCities(provinceId) {
                if (!provinceId) { this.cities = []; return; }
                try {
                    const res = await fetch(`/data/kabkota/${provinceId}.json`);
                    if (res.ok) {
                        const json = await res.json();
                        this.cities = json.data || [];
                    }
                } catch(e) { this.cities = []; }
            },
        }));
    });

    // ── Autocomplete sekolah ─────────────────────────────────────
    function sekolahAC(q0, id0, npsn0) {
        return {
            query:   q0   || '',
            sid:     id0  || '',
            npsn:    npsn0|| '',
            hasil:   [],
            loading: false,
            buka:    false,
            dipilih: !!(q0),
            idx:     -1,
            baru:    false,

            get lokal() { return this.hasil.filter(h => h.source === 'local'); },

            async cari() {
                if (this.query.length < 2) { this.hasil = []; this.buka = false; return; }
                this.loading = true;
                this.dipilih = false;
                this.sid = ''; this.npsn = '';
                try {
                    const r = await fetch(`/api/sekolah/search?q=${encodeURIComponent(this.query)}`);
                    this.hasil = await r.json();
                    this.buka  = true;
                    this.idx   = -1;
                } catch(e) { this.hasil = []; }
                finally { this.loading = false; }
            },

            async pilih(item) {
                this.query   = item.nama_sekolah;
                this.npsn    = item.npsn || '';
                this.buka    = false;
                this.dipilih = true;
                this.baru    = false;

                if (item.source === 'local' && item.id) {
                    this.sid = item.id;
                } else {
                    this.sid = '';
                    try {
                        const r = await fetch('/api/sekolah/simpan', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                            },
                            body: JSON.stringify(item),
                        });
                        const j = await r.json();
                        this.sid  = j.id   || '';
                        this.npsn = j.npsn || item.npsn;
                        this.baru = true;
                        setTimeout(() => this.baru = false, 4000);
                    } catch(e) {}
                }
            },

            pilihAktif() {
                if (this.idx >= 0 && this.idx < this.hasil.length) this.pilih(this.hasil[this.idx]);
            },

            // Dipanggil saat user klik "+ Tambah Data Baru" (sekolah tidak
            // ketemu di database). Nama sekolah dipakai dari ketikan, NPSN
            // dikosongkan supaya user isi manual di field NPSN di bawahnya.
            // sid dikosongkan → saat form disubmit, resolveSekolah() di
            // DashboardUserController akan otomatis membuat record baru di
            // tabel sekolahs berdasarkan nama + NPSN yang diisi manual.
            tambahBaru() {
                this.dipilih = true;
                this.sid     = '';
                this.npsn    = '';
                this.buka    = false;
                this.$nextTick(() => {
                    document.querySelector('input[name="npsn_sekolah"]')?.focus();
                });
            },

            nav(d) { this.idx = Math.max(-1, Math.min(this.hasil.length - 1, this.idx + d)); },

            tutup() { setTimeout(() => this.buka = false, 200); },

            reset() {
                this.query = ''; this.hasil = []; this.dipilih = false;
                this.sid = ''; this.npsn = ''; this.buka = false;
            },
        };
    }

    document.addEventListener('DOMContentLoaded', () => { feather.replace(); });
    </script>
</body>
</html>