@extends('layouts.admin')

@section('admin-content')
<div x-data="{ 
    activeTab: 'umum',
    maintenanceMode: false,
    pendaftaranBuka: true
}">
    
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-8 gap-4">
        <div class="max-w-2xl">
            <h1 class="text-3xl font-extrabold text-brand-dark tracking-tight mb-2">Pengaturan Sistem</h1>
            <p class="text-brand-gray text-[14px] font-medium leading-relaxed">
                Konfigurasi profil portal, jadwal gelombang pendaftaran, dan keamanan akun.
            </p>
        </div>
        <button class="flex items-center gap-2 px-6 py-3 bg-brand-dark text-white rounded-xl font-bold text-[13px] hover:bg-brand-blue transition-all shadow-lg shadow-brand-dark/20 active:scale-95">
            <i data-feather="save" class="w-4 h-4"></i> Simpan Perubahan
        </button>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
        
        <div class="lg:col-span-4 space-y-2 sticky top-32">
            <button @click="activeTab = 'umum'" 
                    :class="activeTab === 'umum' ? 'bg-brand-blue text-white shadow-lg shadow-brand-blue/20' : 'bg-white text-gray-500 hover:bg-gray-50 border border-transparent hover:border-gray-100'"
                    class="w-full flex items-center gap-4 p-4 rounded-2xl font-bold text-[14px] transition-all duration-300 group">
                <div class="w-10 h-10 rounded-xl flex items-center justify-center transition-colors"
                     :class="activeTab === 'umum' ? 'bg-white/20 text-white' : 'bg-gray-100 text-gray-400 group-hover:bg-brand-blue-light group-hover:text-brand-blue'">
                    <i data-feather="monitor" class="w-5 h-5"></i>
                </div>
                <div class="text-left">
                    <p class="mb-0.5">Informasi Umum</p>
                    <p class="text-[11px] font-medium opacity-70" :class="activeTab === 'umum' ? 'text-blue-100' : 'text-gray-400'">Profil & Kontak Portal</p>
                </div>
            </button>

            <button @click="activeTab = 'gelombang'" 
                    :class="activeTab === 'gelombang' ? 'bg-brand-blue text-white shadow-lg shadow-brand-blue/20' : 'bg-white text-gray-500 hover:bg-gray-50 border border-transparent hover:border-gray-100'"
                    class="w-full flex items-center gap-4 p-4 rounded-2xl font-bold text-[14px] transition-all duration-300 group">
                <div class="w-10 h-10 rounded-xl flex items-center justify-center transition-colors"
                     :class="activeTab === 'gelombang' ? 'bg-white/20 text-white' : 'bg-gray-100 text-gray-400 group-hover:bg-brand-blue-light group-hover:text-brand-blue'">
                    <i data-feather="calendar" class="w-5 h-5"></i>
                </div>
                <div class="text-left">
                    <p class="mb-0.5">Periode Pendaftaran</p>
                    <p class="text-[11px] font-medium opacity-70" :class="activeTab === 'gelombang' ? 'text-blue-100' : 'text-gray-400'">Jadwal & Gelombang Aktif</p>
                </div>
            </button>

            <button @click="activeTab = 'keamanan'" 
                    :class="activeTab === 'keamanan' ? 'bg-brand-blue text-white shadow-lg shadow-brand-blue/20' : 'bg-white text-gray-500 hover:bg-gray-50 border border-transparent hover:border-gray-100'"
                    class="w-full flex items-center gap-4 p-4 rounded-2xl font-bold text-[14px] transition-all duration-300 group">
                <div class="w-10 h-10 rounded-xl flex items-center justify-center transition-colors"
                     :class="activeTab === 'keamanan' ? 'bg-white/20 text-white' : 'bg-gray-100 text-gray-400 group-hover:bg-brand-blue-light group-hover:text-brand-blue'">
                    <i data-feather="shield" class="w-5 h-5"></i>
                </div>
                <div class="text-left">
                    <p class="mb-0.5">Keamanan Akun</p>
                    <p class="text-[11px] font-medium opacity-70" :class="activeTab === 'keamanan' ? 'text-blue-100' : 'text-gray-400'">Password & Akses Admin</p>
                </div>
            </button>
        </div>

        <div class="lg:col-span-8">
            
            <div x-show="activeTab === 'umum'" x-transition:enter="transition ease-out duration-300 delay-100" x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0" class="bg-white rounded-[2.5rem] p-10 shadow-sm border border-gray-100" x-cloak>
                <div class="mb-8 border-b border-gray-100 pb-6">
                    <h2 class="text-xl font-extrabold text-brand-dark tracking-tight">Informasi Umum</h2>
                    <p class="text-[13px] font-medium text-gray-400 mt-1">Atur nama sistem dan kontak yang ditampilkan ke calon mahasiswa.</p>
                </div>

                <div class="space-y-6">
                    <div>
                        <label class="block text-[11px] font-black text-gray-500 uppercase tracking-widest mb-2">Nama Portal</label>
                        <input type="text" value="SPMB Portal Adzkia" class="w-full px-5 py-4 bg-gray-50 border border-gray-100 rounded-2xl text-[14px] font-bold text-brand-dark outline-none focus:ring-2 focus:ring-brand-blue/20 transition-all">
                    </div>
                    
                    <div class="grid grid-cols-2 gap-6">
                        <div>
                            <label class="block text-[11px] font-black text-gray-500 uppercase tracking-widest mb-2">Email Bantuan (Helpdesk)</label>
                            <input type="email" value="admissions@adzkia.ac.id" class="w-full px-5 py-4 bg-gray-50 border border-gray-100 rounded-2xl text-[14px] font-bold text-brand-dark outline-none focus:ring-2 focus:ring-brand-blue/20 transition-all">
                        </div>
                        <div>
                            <label class="block text-[11px] font-black text-gray-500 uppercase tracking-widest mb-2">Nomor WhatsApp CS</label>
                            <input type="text" value="+62 811-2233-4455" class="w-full px-5 py-4 bg-gray-50 border border-gray-100 rounded-2xl text-[14px] font-bold text-brand-dark outline-none focus:ring-2 focus:ring-brand-blue/20 transition-all">
                        </div>
                    </div>

                    <div>
                        <label class="block text-[11px] font-black text-gray-500 uppercase tracking-widest mb-2">Tahun Akademik Aktif</label>
                        <select class="w-full px-5 py-4 bg-gray-50 border border-gray-100 rounded-2xl text-[14px] font-bold text-brand-dark outline-none focus:ring-2 focus:ring-brand-blue/20 transition-all appearance-none cursor-pointer">
                            <option>2023/2024</option>
                            <option selected>2024/2025</option>
                            <option>2025/2026</option>
                        </select>
                    </div>

                    <div class="p-6 bg-red-50/50 border border-red-100 rounded-2xl flex items-center justify-between mt-8">
                        <div>
                            <h4 class="text-[14px] font-extrabold text-red-900 mb-1">Maintenance Mode</h4>
                            <p class="text-[12px] font-medium text-red-700/80">Tutup portal sementara dari publik jika ada perbaikan sistem.</p>
                        </div>
                        <button @click="maintenanceMode = !maintenanceMode" 
                                class="w-14 h-8 rounded-full transition-colors relative flex items-center focus:outline-none shrink-0"
                                :class="maintenanceMode ? 'bg-red-500' : 'bg-gray-300'">
                            <div class="w-6 h-6 bg-white rounded-full shadow-md transform transition-transform duration-300 absolute left-1"
                                 :class="maintenanceMode ? 'translate-x-6' : 'translate-x-0'"></div>
                        </button>
                    </div>
                </div>
            </div>

            <div x-show="activeTab === 'gelombang'" x-transition:enter="transition ease-out duration-300 delay-100" x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0" class="bg-white rounded-[2.5rem] p-10 shadow-sm border border-gray-100" x-cloak>
                <div class="mb-8 border-b border-gray-100 pb-6 flex justify-between items-end">
                    <div>
                        <h2 class="text-xl font-extrabold text-brand-dark tracking-tight">Periode Pendaftaran</h2>
                        <p class="text-[13px] font-medium text-gray-400 mt-1">Atur jadwal pembukaan dan penutupan gelombang seleksi.</p>
                    </div>
                    <div class="flex items-center gap-3">
                        <span class="text-[12px] font-bold text-gray-500" x-text="pendaftaranBuka ? 'Pendaftaran Buka' : 'Pendaftaran Tutup'"></span>
                        <button @click="pendaftaranBuka = !pendaftaranBuka" 
                                class="w-12 h-6 rounded-full transition-colors relative flex items-center focus:outline-none shrink-0"
                                :class="pendaftaranBuka ? 'bg-green-500' : 'bg-gray-300'">
                            <div class="w-4 h-4 bg-white rounded-full shadow-md transform transition-transform duration-300 absolute left-1"
                                 :class="pendaftaranBuka ? 'translate-x-6' : 'translate-x-0'"></div>
                        </button>
                    </div>
                </div>

                <div class="space-y-6">
                    <div class="p-6 bg-gray-50 border border-gray-100 rounded-2xl">
                        <div class="flex justify-between items-center mb-4">
                            <h4 class="text-[15px] font-black text-brand-dark">Gelombang 1 (Jalur Prestasi)</h4>
                            <span class="px-3 py-1 bg-gray-200 text-gray-500 rounded-full text-[10px] font-black uppercase">Tutup</span>
                        </div>
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1.5">Tgl Buka</label>
                                <input type="date" value="2023-11-01" class="w-full px-4 py-3 bg-white border border-gray-100 rounded-xl text-[13px] font-bold text-gray-500 outline-none" disabled>
                            </div>
                            <div>
                                <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1.5">Tgl Tutup</label>
                                <input type="date" value="2023-12-31" class="w-full px-4 py-3 bg-white border border-gray-100 rounded-xl text-[13px] font-bold text-gray-500 outline-none" disabled>
                            </div>
                        </div>
                    </div>

                    <div class="p-6 bg-blue-50/50 border border-blue-100 rounded-2xl relative overflow-hidden">
                        <div class="absolute top-0 left-0 w-1 h-full bg-brand-blue"></div>
                        <div class="flex justify-between items-center mb-4">
                            <h4 class="text-[15px] font-black text-brand-dark">Gelombang 2 (Jalur Mandiri)</h4>
                            <span class="px-3 py-1 bg-green-50 text-green-600 rounded-full text-[10px] font-black uppercase flex items-center gap-1">
                                <div class="w-1.5 h-1.5 bg-green-500 rounded-full animate-pulse"></div> Aktif
                            </span>
                        </div>
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-[10px] font-black text-gray-500 uppercase tracking-widest mb-1.5">Tgl Buka</label>
                                <input type="date" value="2024-01-15" class="w-full px-4 py-3 bg-white border border-gray-200 rounded-xl text-[13px] font-bold text-brand-dark outline-none focus:border-brand-blue transition-all">
                            </div>
                            <div>
                                <label class="block text-[10px] font-black text-gray-500 uppercase tracking-widest mb-1.5">Tgl Tutup</label>
                                <input type="date" value="2024-04-30" class="w-full px-4 py-3 bg-white border border-gray-200 rounded-xl text-[13px] font-bold text-brand-dark outline-none focus:border-brand-blue transition-all">
                            </div>
                        </div>
                    </div>

                    <button class="w-full py-4 border-2 border-dashed border-gray-200 text-gray-400 hover:bg-gray-50 hover:border-brand-blue hover:text-brand-blue rounded-2xl font-bold text-[13px] transition-all flex items-center justify-center gap-2">
                        <i data-feather="plus" class="w-4 h-4"></i> Tambah Gelombang Baru
                    </button>
                </div>
            </div>

            <div x-show="activeTab === 'keamanan'" x-transition:enter="transition ease-out duration-300 delay-100" x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0" class="bg-white rounded-[2.5rem] p-10 shadow-sm border border-gray-100" x-cloak>
                <div class="mb-8 border-b border-gray-100 pb-6">
                    <h2 class="text-xl font-extrabold text-brand-dark tracking-tight">Keamanan Akun</h2>
                    <p class="text-[13px] font-medium text-gray-400 mt-1">Perbarui password Anda secara berkala untuk menjaga keamanan sistem.</p>
                </div>

                <div class="space-y-6">
                    <div class="p-5 bg-amber-50 border border-amber-100 rounded-2xl flex items-start gap-4 mb-8">
                        <div class="mt-0.5 text-amber-600"><i data-feather="alert-triangle" class="w-5 h-5"></i></div>
                        <div>
                            <h4 class="text-[13px] font-bold text-amber-900 mb-1">Gunakan Password Kuat</h4>
                            <p class="text-[12px] text-amber-700/80 leading-relaxed">Password harus terdiri dari minimal 8 karakter, mengandung huruf besar, huruf kecil, angka, dan simbol khusus (!@#$%^&*).</p>
                        </div>
                    </div>

                    <div>
                        <label class="block text-[11px] font-black text-gray-500 uppercase tracking-widest mb-2">Password Saat Ini</label>
                        <input type="password" placeholder="••••••••" class="w-full px-5 py-4 bg-gray-50 border border-gray-100 rounded-2xl text-[14px] font-bold text-brand-dark outline-none focus:ring-2 focus:ring-brand-blue/20 transition-all">
                    </div>
                    
                    <div class="w-full h-px bg-gray-100 my-4"></div>

                    <div>
                        <label class="block text-[11px] font-black text-gray-500 uppercase tracking-widest mb-2">Password Baru</label>
                        <input type="password" placeholder="Masukkan password baru" class="w-full px-5 py-4 bg-gray-50 border border-gray-100 rounded-2xl text-[14px] font-bold text-brand-dark outline-none focus:ring-2 focus:ring-brand-blue/20 transition-all">
                    </div>
                    <div>
                        <label class="block text-[11px] font-black text-gray-500 uppercase tracking-widest mb-2">Konfirmasi Password Baru</label>
                        <input type="password" placeholder="Ketik ulang password baru" class="w-full px-5 py-4 bg-gray-50 border border-gray-100 rounded-2xl text-[14px] font-bold text-brand-dark outline-none focus:ring-2 focus:ring-brand-blue/20 transition-all">
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>

<script>
    document.addEventListener('alpine:initialized', () => {
        // Render ikon saat tab berpindah
        feather.replace();
    });
</script>
@endsection