<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pendaftaran Akun - SPMB Adzkia</title>
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
    </style>
</head>
<body class="bg-brand-bg antialiased text-brand-dark min-h-screen flex flex-col" x-data="registerApp()">

    <nav class="w-full bg-white py-8 border-b border-gray-100 sticky top-0 z-50">
        <div class="max-w-5xl mx-auto px-6">
            <div class="flex items-center justify-between relative">
                <div class="absolute top-1/2 left-0 w-full h-0.5 bg-gray-100 -translate-y-1/2 z-0"></div>
                <template x-for="step in steps" :key="step.id">
                    <div class="relative z-10 flex flex-col items-center gap-2">
                        <div class="w-10 h-10 rounded-full flex items-center justify-center font-bold text-[13px] transition-all duration-300"
                             :class="currentStep === step.id ? 'bg-brand-blue text-white shadow-lg shadow-brand-blue/30 scale-110' : 'bg-white border-2 border-gray-100 text-gray-400'">
                            <span x-text="step.id"></span>
                        </div>
                        <span class="text-[9px] font-black uppercase tracking-widest hidden md:block" :class="currentStep === step.id ? 'text-brand-dark' : 'text-gray-400'" x-text="step.title"></span>
                    </div>
                </template>
            </div>
        </div>
    </nav>

    <main class="flex-1 max-w-7xl mx-auto w-full px-6 py-16 grid grid-cols-1 lg:grid-cols-2 gap-16 items-start">
        
        <div class="space-y-10 lg:sticky lg:top-32">
            <div class="inline-block">
                <a href="/" class="inline-flex items-center gap-2.5 px-4 py-2 bg-white border border-gray-200 hover:border-brand-dark rounded-xl text-xs font-black text-brand-gray hover:text-brand-dark transition-all uppercase tracking-widest shadow-sm group">
                    <i data-feather="home" class="w-4 h-4 text-brand-gray group-hover:text-brand-dark transition-colors"></i> 
                    Kembali ke Beranda
                </a>
            </div>

            <div>
                <span class="block w-max px-3 py-1 bg-brand-blue-light text-brand-blue rounded-lg text-[11px] font-black uppercase tracking-widest">STEP 01 / 07</span>
                <h1 class="text-5xl md:text-6xl font-black text-brand-dark tracking-tight leading-[1.1] mt-6">Mulai Perjalanan <br> <span class="text-brand-blue">Akademik Anda.</span></h1>
                <p class="text-[16px] font-medium text-brand-gray leading-relaxed mt-8 max-w-md">Isi data diri Anda dengan benar untuk membuka gerbang seleksi penerimaan mahasiswa baru Universitas Adzkia.</p>
            </div>
            <div class="flex items-center gap-4">
                <div class="flex -space-x-3">
                    <img src="https://ui-avatars.com/api/?name=Andi&background=0F172A&color=fff" class="w-10 h-10 rounded-full border-2 border-white">
                    <img src="https://ui-avatars.com/api/?name=Siti&background=2563EB&color=fff" class="w-10 h-10 rounded-full border-2 border-white">
                    <img src="https://ui-avatars.com/api/?name=Budi&background=64748B&color=fff" class="w-10 h-10 rounded-full border-2 border-white">
                </div>
                <p class="text-[13px] font-bold text-brand-gray">Gabung bersama <span class="text-brand-dark font-black">2,400+</span> calon mahasiswa tahun ini.</p>
            </div>
        </div>

        <div class="bg-white p-8 md:p-12 rounded-[2.5rem] shadow-xl shadow-brand-dark/5 border border-gray-100 w-full">
            
            <div x-show="stage === 1" x-transition:enter="transition ease-out duration-300">
                <div class="mb-8">
                    <h2 class="text-2xl font-black text-brand-dark tracking-tight">Pilih Jalur Pendaftaran</h2>
                    <p class="text-[13px] font-medium text-brand-gray mt-1">Tentukan metode seleksi masuk yang ingin Anda ikuti.</p>
                </div>

                <div class="space-y-4">
                    <label class="block p-5 border-2 rounded-2xl cursor-pointer transition-all" :class="selectedJalur === 'Reguler' ? 'border-brand-dark bg-gray-50' : 'border-gray-100 hover:border-brand-blue'">
                        <div class="flex items-center gap-4">
                            <input type="radio" name="jalur_radio" value="Reguler" x-model="selectedJalur" @change="specificJalur = ''" class="w-4 h-4 text-brand-blue">
                            <div>
                                <h4 class="font-extrabold text-[15px] text-brand-dark">Jalur Mandiri Reguler</h4>
                                <p class="text-[12px] text-brand-gray font-medium mt-0.5">Seleksi menggunakan ujian tertulis berbasis komputer (CBT).</p>
                            </div>
                        </div>
                    </label>

                    <label class="block p-5 border-2 rounded-2xl cursor-pointer transition-all" :class="selectedJalur === 'Khusus' ? 'border-brand-dark bg-gray-50' : 'border-gray-100 hover:border-brand-blue'">
                        <div class="flex items-center gap-4">
                            <input type="radio" name="jalur_radio" value="Khusus" x-model="selectedJalur" class="w-4 h-4 text-brand-blue">
                            <div>
                                <h4 class="font-extrabold text-[15px] text-brand-dark">Jalur Kemitraan / Khusus / Prestasi</h4>
                                <p class="text-[12px] text-brand-gray font-medium mt-0.5">Jalur tanpa ujian tertulis untuk siswa berprestasi atau rekomendasi yayasan.</p>
                            </div>
                        </div>
                    </label>

                    <div x-show="selectedJalur === 'Khusus'" x-collapse x-cloak class="mt-4 p-5 bg-brand-blue-light/50 border border-brand-blue/20 rounded-2xl space-y-3">
                        <label class="block text-[10px] font-black text-brand-blue uppercase tracking-widest px-1">Spesifikasi Jalur Non-Reguler</label>
                        <div class="relative">
                            <select x-model="specificJalur" class="w-full px-5 py-4 bg-white border border-gray-200 rounded-xl outline-none focus:border-brand-blue transition-all font-bold text-[14px] text-brand-dark appearance-none cursor-pointer">
                                <option value="" disabled selected>-- Pilih Kategori Program Khusus --</option>
                                
                                @foreach($jalurKhusus as $category => $items)
                                    <optgroup label="{{ $category }}">
                                        @foreach($items as $jalur)
                                            <option value="{{ $jalur->name }}">{{ $jalur->name }}</option>
                                        @endforeach
                                    </optgroup>
                                @endforeach

                            </select>
                            <i data-feather="chevron-down" class="w-4 h-4 text-gray-400 absolute right-5 top-1/2 -translate-y-1/2 pointer-events-none"></i>
                        </div>
                    </div>

                    <div class="pt-6 flex justify-end">
                        <button type="button" @click="handleJalurSubmit()" :disabled="!selectedJalur || (selectedJalur === 'Khusus' && !specificJalur)" :class="selectedJalur && !(selectedJalur === 'Khusus' && !specificJalur) ? 'bg-brand-dark text-white hover:bg-brand-blue' : 'bg-gray-200 text-gray-400 cursor-not-allowed'" class="px-8 py-4 rounded-2xl font-black text-[14px] transition-all flex items-center gap-2 shadow-md">
                            Lanjutkan ke Formulir <i data-feather="arrow-right" class="w-4 h-4"></i>
                        </button>
                    </div>
                </div>
            </div>

            <div x-show="stage === 2" x-cloak x-transition:enter="transition ease-out duration-300">
                <div class="mb-8 flex justify-between items-start">
                    <div>
                        <h2 class="text-2xl font-black text-brand-dark tracking-tight">Data Akun Pendaftar</h2>
                        <p class="text-[13px] font-medium text-brand-gray mt-1">Lengkapi seluruh kolom di bawah ini dengan valid.</p>
                    </div>
                    <span class="px-3 py-1 bg-brand-blue-light text-brand-blue rounded-md text-[10px] font-black uppercase tracking-widest" x-text="selectedJalur === 'Reguler' ? 'Jalur Reguler' : 'Khusus: ' + specificJalur"></span>
                </div>

                <form action="{{ route('register.store') }}" method="POST" class="space-y-5">
                    @csrf
                    
                    <input type="hidden" name="jalur" x-bind:value="selectedJalur">
                    <input type="hidden" name="jalur_detail" x-bind:value="specificJalur">

                    <div>
                        <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1.5 px-1">Nama Lengkap (Sesuai Ijazah)</label>
                        <input type="text" name="name" required placeholder="Masukkan nama lengkap Anda" class="w-full px-5 py-3.5 bg-gray-50 border border-transparent rounded-xl outline-none focus:border-brand-blue focus:bg-white transition-all font-bold text-[14px]">
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1.5 px-1">NIK (Nomor Induk Kependudukan)</label>
                            <input type="text" name="nik" required placeholder="16 Digit NIK" class="w-full px-5 py-3.5 bg-gray-50 border border-transparent rounded-xl outline-none focus:border-brand-blue focus:bg-white transition-all font-bold text-[14px]">
                        </div>
                        <div>
                            <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1.5 px-1">No. WhatsApp</label>
                            <input type="text" name="no_hp" required placeholder="Contoh: 08123456789" class="w-full px-5 py-3.5 bg-gray-50 border border-transparent rounded-xl outline-none focus:border-brand-blue focus:bg-white transition-all font-bold text-[14px]">
                        </div>
                    </div>

                    <div>
                        <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1.5 px-1">Alamat Email</label>
                        <input type="email" name="email" required placeholder="nama@email.com" class="w-full px-5 py-3.5 bg-gray-50 border border-transparent rounded-xl outline-none focus:border-brand-blue focus:bg-white transition-all font-bold text-[14px]">
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1.5 px-1">Pilihan Jurusan 1</label>
                            <select name="program_studi_1" required class="w-full px-5 py-3.5 bg-gray-50 border border-transparent rounded-xl outline-none focus:border-brand-blue focus:bg-white transition-all font-bold text-[14px] text-brand-dark cursor-pointer">
                                <option value="" disabled selected>Pilih Jurusan Utama</option>
                                @foreach($prodis as $prodi)
                                    <option value="{{ $prodi->id }}">{{ $prodi->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1.5 px-1">Pilihan Jurusan 2</label>
                            <select name="program_studi_2" required class="w-full px-5 py-3.5 bg-gray-50 border border-transparent rounded-xl outline-none focus:border-brand-blue focus:bg-white transition-all font-bold text-[14px] text-brand-dark cursor-pointer">
                                <option value="" disabled selected>Pilih Jurusan Cadangan</option>
                                @foreach($prodis as $prodi)
                                    <option value="{{ $prodi->id }}">{{ $prodi->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div>
                        <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1.5 px-1">Alamat Rumah Lengkap</label>
                        <textarea name="alamat" required rows="2" placeholder="Nama jalan, RT/RW, Kecamatan, Kota/Kabupaten..." class="w-full px-5 py-3.5 bg-gray-50 border border-transparent rounded-xl outline-none focus:border-brand-blue focus:bg-white transition-all font-bold text-[14px] resize-none"></textarea>
                    </div>

                    <div class="flex items-center justify-between pt-4 border-t border-gray-100">
                        <button type="button" @click="stage = 1" class="flex items-center gap-2 text-[12px] font-black text-gray-400 hover:text-brand-dark transition-colors uppercase tracking-widest">
                            <i data-feather="arrow-left" class="w-4 h-4"></i> Kembali
                        </button>
                        <button type="submit" class="px-8 py-4 bg-brand-dark text-white rounded-2xl font-black text-[14px] hover:bg-brand-blue shadow-lg shadow-brand-dark/20 transition-all flex items-center gap-3 group">
                            Daftar & Bayar <i data-feather="arrow-right" class="w-4 h-4 group-hover:translate-x-1 transition-transform"></i>
                        </button>
                    </div>
                </form>
            </div>

        </div>
    </main>

    <footer class="w-full bg-brand-bg py-8 flex flex-col md:flex-row justify-center md:justify-between items-center gap-4 px-10 border-t border-gray-100">
        <p class="text-[11px] font-bold text-gray-400">© 2026 Universitas Adzkia. All Rights Reserved.</p>
    </footer>

    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('registerApp', () => ({
                currentStep: 1,
                stage: 1, 
                selectedJalur: null,
                specificJalur: '',
                
                steps: [
                    { id: 1, title: 'Pendaftaran' }, { id: 2, title: 'Biaya Pendaftaran' },
                    { id: 3, title: 'Validasi Pembayaran' }, { id: 4, title: 'Biodata' },
                    { id: 5, title: 'Dokumen' }, { id: 6, title: 'Ujian' }, { id: 7, title: 'Hasil' }
                ],

                handleJalurSubmit() {
                    if (this.selectedJalur === 'Reguler' || (this.selectedJalur === 'Khusus' && this.specificJalur)) {
                        this.stage = 2;
                    }
                    this.$nextTick(() => { if(window.feather) feather.replace(); });
                }
            }));
        });
        document.addEventListener('DOMContentLoaded', () => { feather.replace(); });
    </script>
</body>
</html>