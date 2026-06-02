@extends('layouts.admin')

@section('admin-content')
<div x-data="{ 
    activeTab: '{{ session('tab', 'umum') }}',
    maintenanceMode: {{ $setting->maintenance_mode ? 'true' : 'false' }},
    pendaftaranBuka: {{ $setting->pendaftaran_aktif ? 'true' : 'false' }}
}">
    
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-8 gap-4">
        <div class="max-w-2xl">
            <h1 class="text-3xl font-extrabold text-brand-dark tracking-tight mb-2">Pengaturan Sistem</h1>
            <p class="text-brand-gray text-[14px] font-medium leading-relaxed">
                Konfigurasi profil portal, jadwal gelombang pendaftaran, dan keamanan akun.
            </p>
        </div>
        
        <button type="submit" :form="activeTab === 'keamanan' ? 'passwordForm' : 'settingsForm'" class="flex items-center gap-2 px-6 py-3 bg-brand-dark text-white rounded-xl font-bold text-[13px] hover:bg-brand-blue transition-all shadow-lg shadow-brand-dark/20 active:scale-95">
            <i data-feather="save" class="w-4 h-4"></i> Simpan Perubahan
        </button>
    </div>

    @if(session('success'))
        <div class="mb-6 p-4 bg-green-50 border border-green-200 text-green-700 font-bold rounded-2xl text-[13px] flex items-center gap-3 shadow-sm">
            <i data-feather="check-circle" class="w-5 h-5"></i> {{ session('success') }}
        </div>
    @endif
    @if($errors->any())
        <div class="mb-6 p-4 bg-red-50 border border-red-200 text-red-700 font-bold rounded-2xl text-[13px] flex items-center gap-3 shadow-sm">
            <i data-feather="alert-circle" class="w-5 h-5 shrink-0"></i> 
            <ul class="list-disc list-inside">
                @foreach($errors->all() as $error) <li>{{ $error }}</li> @endforeach
            </ul>
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
        
        <div class="lg:col-span-4 space-y-2 sticky top-32">
            <button type="button" @click="activeTab = 'umum'" :class="activeTab === 'umum' ? 'bg-brand-blue text-white shadow-lg shadow-brand-blue/20' : 'bg-white text-gray-500 hover:bg-gray-50 border border-transparent hover:border-gray-100'" class="w-full flex items-center gap-4 p-4 rounded-2xl font-bold text-[14px] transition-all duration-300 group">
                <div class="w-10 h-10 rounded-xl flex items-center justify-center transition-colors" :class="activeTab === 'umum' ? 'bg-white/20 text-white' : 'bg-gray-100 text-gray-400 group-hover:bg-brand-blue-light group-hover:text-brand-blue'"><i data-feather="monitor" class="w-5 h-5"></i></div>
                <div class="text-left"><p class="mb-0.5">Informasi Umum</p><p class="text-[11px] font-medium opacity-70" :class="activeTab === 'umum' ? 'text-blue-100' : 'text-gray-400'">Profil, Kontak & Identitas</p></div>
            </button>

            <button type="button" @click="activeTab = 'media'" :class="activeTab === 'media' ? 'bg-brand-blue text-white shadow-lg shadow-brand-blue/20' : 'bg-white text-gray-500 hover:bg-gray-50 border border-transparent hover:border-gray-100'" class="w-full flex items-center gap-4 p-4 rounded-2xl font-bold text-[14px] transition-all duration-300 group">
                <div class="w-10 h-10 rounded-xl flex items-center justify-center transition-colors" :class="activeTab === 'media' ? 'bg-white/20 text-white' : 'bg-gray-100 text-gray-400 group-hover:bg-brand-blue-light group-hover:text-brand-blue'"><i data-feather="youtube" class="w-5 h-5"></i></div>
                <div class="text-left"><p class="mb-0.5">Media & Dokumen</p><p class="text-[11px] font-medium opacity-70" :class="activeTab === 'media' ? 'text-blue-100' : 'text-gray-400'">Video, Brosur, & Maps</p></div>
            </button>

            <button type="button" @click="activeTab = 'gelombang'" :class="activeTab === 'gelombang' ? 'bg-brand-blue text-white shadow-lg shadow-brand-blue/20' : 'bg-white text-gray-500 hover:bg-gray-50 border border-transparent hover:border-gray-100'" class="w-full flex items-center gap-4 p-4 rounded-2xl font-bold text-[14px] transition-all duration-300 group">
                <div class="w-10 h-10 rounded-xl flex items-center justify-center transition-colors" :class="activeTab === 'gelombang' ? 'bg-white/20 text-white' : 'bg-gray-100 text-gray-400 group-hover:bg-brand-blue-light group-hover:text-brand-blue'"><i data-feather="calendar" class="w-5 h-5"></i></div>
                <div class="text-left"><p class="mb-0.5">Periode Pendaftaran</p><p class="text-[11px] font-medium opacity-70" :class="activeTab === 'gelombang' ? 'text-blue-100' : 'text-gray-400'">Jadwal Gelombang Aktif</p></div>
            </button>

            <button type="button" @click="activeTab = 'keamanan'" :class="activeTab === 'keamanan' ? 'bg-brand-blue text-white shadow-lg shadow-brand-blue/20' : 'bg-white text-gray-500 hover:bg-gray-50 border border-transparent hover:border-gray-100'" class="w-full flex items-center gap-4 p-4 rounded-2xl font-bold text-[14px] transition-all duration-300 group">
                <div class="w-10 h-10 rounded-xl flex items-center justify-center transition-colors" :class="activeTab === 'keamanan' ? 'bg-white/20 text-white' : 'bg-gray-100 text-gray-400 group-hover:bg-brand-blue-light group-hover:text-brand-blue'"><i data-feather="shield" class="w-5 h-5"></i></div>
                <div class="text-left"><p class="mb-0.5">Keamanan Akun</p><p class="text-[11px] font-medium opacity-70" :class="activeTab === 'keamanan' ? 'text-blue-100' : 'text-gray-400'">Ganti Password Admin</p></div>
            </button>
        </div>

        <div class="lg:col-span-8">
            
            <form id="settingsForm" action="{{ route('admin.settings.update') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="active_tab" x-model="activeTab">
                <input type="hidden" name="maintenance_mode" :value="maintenanceMode ? 1 : 0">
                <input type="hidden" name="pendaftaran_aktif" :value="pendaftaranBuka ? 1 : 0">

                <div x-show="activeTab === 'umum'" class="bg-white rounded-[2.5rem] p-10 shadow-sm border border-gray-100" x-cloak>
                    <div class="mb-8 border-b border-gray-100 pb-6"><h2 class="text-xl font-extrabold text-brand-dark tracking-tight">Informasi Umum</h2></div>

                    <div class="space-y-6">
                        <div class="grid grid-cols-2 gap-6">
                            <div>
                                <label class="block text-[11px] font-black text-gray-500 uppercase tracking-widest mb-2">Email Helpdesk</label>
                                <input type="email" name="email" value="{{ old('email', $setting->email) }}" required class="w-full px-5 py-4 bg-gray-50 border border-gray-100 rounded-2xl text-[14px] font-bold text-brand-dark outline-none focus:ring-2 focus:ring-brand-blue/20">
                            </div>
                            <div>
                                <label class="block text-[11px] font-black text-gray-500 uppercase tracking-widest mb-2">WhatsApp CS</label>
                                <input type="text" name="telepon" value="{{ old('telepon', $setting->telepon) }}" required class="w-full px-5 py-4 bg-gray-50 border border-gray-100 rounded-2xl text-[14px] font-bold text-brand-dark outline-none focus:ring-2 focus:ring-brand-blue/20">
                            </div>
                        </div>

                        <div class="grid grid-cols-2 gap-6">
                            <div>
                                <label class="block text-[11px] font-black text-gray-500 uppercase tracking-widest mb-2">Tahun Akademik</label>
                                <input type="text" name="tahun_akademik" value="{{ old('tahun_akademik', $setting->tahun_akademik) }}" required class="w-full px-5 py-4 bg-gray-50 border border-gray-100 rounded-2xl text-[14px] font-bold text-brand-dark outline-none focus:ring-2 focus:ring-brand-blue/20">
                            </div>
                            <div>
                                <label class="block text-[11px] font-black text-gray-500 uppercase tracking-widest mb-2">Akreditasi</label>
                                <select name="akreditasi" class="w-full px-5 py-4 bg-gray-50 border border-gray-100 rounded-2xl text-[14px] font-bold text-brand-dark outline-none cursor-pointer">
                                    <option value="Unggul" {{ $setting->akreditasi == 'Unggul' ? 'selected' : '' }}>Unggul</option>
                                    <option value="A" {{ $setting->akreditasi == 'A' ? 'selected' : '' }}>A</option>
                                    <option value="B" {{ $setting->akreditasi == 'B' ? 'selected' : '' }}>B</option>
                                </select>
                            </div>
                        </div>

                        <div>
                            <label class="block text-[11px] font-black text-gray-500 uppercase tracking-widest mb-2">Alamat Kampus</label>
                            <textarea name="alamat" rows="3" required class="w-full px-5 py-4 bg-gray-50 border border-gray-100 rounded-2xl text-[14px] font-bold text-brand-dark outline-none focus:ring-2 focus:ring-brand-blue/20 resize-none">{{ old('alamat', $setting->alamat) }}</textarea>
                        </div>

                        <div class="p-6 bg-red-50 border border-red-100 rounded-2xl flex items-center justify-between mt-8">
                            <div>
                                <h4 class="text-[14px] font-extrabold text-red-900 mb-1">Maintenance Mode</h4>
                                <p class="text-[12px] font-medium text-red-700">Aktifkan untuk menutup web dari publik sementara.</p>
                            </div>
                            <button type="button" @click="maintenanceMode = !maintenanceMode" class="w-14 h-8 rounded-full transition-colors relative flex items-center focus:outline-none shrink-0" :class="maintenanceMode ? 'bg-red-500' : 'bg-gray-300'">
                                <div class="w-6 h-6 bg-white rounded-full shadow-md transform transition-transform duration-300 absolute left-1" :class="maintenanceMode ? 'translate-x-6' : 'translate-x-0'"></div>
                            </button>
                        </div>
                    </div>
                </div>

                <div x-show="activeTab === 'media'" x-cloak style="display: none;" class="bg-white rounded-[2.5rem] p-10 shadow-sm border border-gray-100">
                    <div class="mb-8 border-b border-gray-100 pb-6"><h2 class="text-xl font-extrabold text-brand-dark tracking-tight">Media & Dokumen Publik</h2></div>
                    <div class="space-y-6">
                        <div>
                            <label class="block text-[11px] font-black text-gray-500 uppercase tracking-widest mb-2">ID Video YouTube (Profil)</label>
                            <div class="flex gap-2">
                                <span class="px-5 py-4 bg-gray-100 text-gray-400 border border-gray-100 rounded-2xl font-medium text-sm flex items-center">youtube.com/embed/</span>
                                <input type="text" name="video_profil" value="{{ old('video_profil', $setting->video_profil) }}" class="flex-1 w-full px-5 py-4 bg-gray-50 border border-gray-100 rounded-2xl text-[14px] font-bold text-brand-dark outline-none focus:ring-2 focus:ring-brand-blue/20">
                            </div>
                        </div>

                        <div>
                            <label class="block text-[11px] font-black text-gray-500 uppercase tracking-widest mb-2">Unggah Brosur Pendaftaran (PDF)</label>
                            <input type="file" name="brosur" accept="application/pdf" class="w-full px-5 py-4 bg-gray-50 border border-gray-100 rounded-2xl text-[13px] font-bold text-brand-dark cursor-pointer">
                            @if($setting->brosur_path)
                                <p class="text-[11px] text-green-600 mt-2 font-bold"><i data-feather="check" class="w-3 h-3 inline"></i> File Brosur Aktif Tersimpan</p>
                            @endif
                        </div>

                        <div>
                            <label class="block text-[11px] font-black text-gray-500 uppercase tracking-widest mb-2">URL Google Maps Iframe</label>
                            <textarea name="link_maps" rows="3" class="w-full px-5 py-4 bg-gray-50 border border-gray-100 rounded-2xl text-[13px] font-mono text-gray-600 outline-none focus:ring-2 focus:ring-brand-blue/20 resize-none">{{ old('link_maps', $setting->link_maps) }}</textarea>
                        </div>
                    </div>
                </div>

                <div x-show="activeTab === 'gelombang'" x-cloak style="display: none;" class="bg-white rounded-[2.5rem] p-10 shadow-sm border border-gray-100">
                    <div class="mb-8 border-b border-gray-100 pb-6 flex justify-between items-end">
                        <div><h2 class="text-xl font-extrabold text-brand-dark tracking-tight">Periode Pendaftaran</h2></div>
                        <div class="flex items-center gap-3">
                            <span class="text-[12px] font-bold text-gray-500" x-text="pendaftaranBuka ? 'Pendaftaran Buka' : 'Pendaftaran Tutup'"></span>
                            <button type="button" @click="pendaftaranBuka = !pendaftaranBuka" class="w-12 h-6 rounded-full transition-colors relative flex items-center focus:outline-none shrink-0" :class="pendaftaranBuka ? 'bg-green-500' : 'bg-gray-300'">
                                <div class="w-4 h-4 bg-white rounded-full shadow-md transform transition-transform duration-300 absolute left-1" :class="pendaftaranBuka ? 'translate-x-6' : 'translate-x-0'"></div>
                            </button>
                        </div>
                    </div>

                    <div class="space-y-6">
                        <div class="p-6 bg-blue-50/50 border border-blue-100 rounded-2xl relative overflow-hidden">
                            <div class="absolute top-0 left-0 w-1 h-full bg-brand-blue"></div>
                            <h4 class="text-[15px] font-black text-brand-dark mb-4">Gelombang 1</h4>
                            <div class="grid grid-cols-2 gap-4">
                                <div><label class="block text-[10px] font-black text-gray-500 uppercase mb-1.5">Tgl Buka</label><input type="date" name="gelombang_1_buka" value="{{ $setting->gelombang_1_buka }}" class="w-full px-4 py-3 bg-white border border-gray-200 rounded-xl text-[13px] font-bold text-brand-dark outline-none"></div>
                                <div><label class="block text-[10px] font-black text-gray-500 uppercase mb-1.5">Tgl Tutup</label><input type="date" name="gelombang_1_tutup" value="{{ $setting->gelombang_1_tutup }}" class="w-full px-4 py-3 bg-white border border-gray-200 rounded-xl text-[13px] font-bold text-brand-dark outline-none"></div>
                            </div>
                        </div>

                        <div class="p-6 bg-blue-50/50 border border-blue-100 rounded-2xl relative overflow-hidden">
                            <div class="absolute top-0 left-0 w-1 h-full bg-brand-blue"></div>
                            <h4 class="text-[15px] font-black text-brand-dark mb-4">Gelombang 2</h4>
                            <div class="grid grid-cols-2 gap-4">
                                <div><label class="block text-[10px] font-black text-gray-500 uppercase mb-1.5">Tgl Buka</label><input type="date" name="gelombang_2_buka" value="{{ $setting->gelombang_2_buka }}" class="w-full px-4 py-3 bg-white border border-gray-200 rounded-xl text-[13px] font-bold text-brand-dark outline-none"></div>
                                <div><label class="block text-[10px] font-black text-gray-500 uppercase mb-1.5">Tgl Tutup</label><input type="date" name="gelombang_2_tutup" value="{{ $setting->gelombang_2_tutup }}" class="w-full px-4 py-3 bg-white border border-gray-200 rounded-xl text-[13px] font-bold text-brand-dark outline-none"></div>
                            </div>
                        </div>

                        <div class="p-6 bg-blue-50/50 border border-blue-100 rounded-2xl relative overflow-hidden">
                            <div class="absolute top-0 left-0 w-1 h-full bg-brand-blue"></div>
                            <h4 class="text-[15px] font-black text-brand-dark mb-4">Gelombang 3</h4>
                            <div class="grid grid-cols-2 gap-4">
                                <div><label class="block text-[10px] font-black text-gray-500 uppercase mb-1.5">Tgl Buka</label><input type="date" name="gelombang_3_buka" value="{{ $setting->gelombang_3_buka }}" class="w-full px-4 py-3 bg-white border border-gray-200 rounded-xl text-[13px] font-bold text-brand-dark outline-none"></div>
                                <div><label class="block text-[10px] font-black text-gray-500 uppercase mb-1.5">Tgl Tutup</label><input type="date" name="gelombang_3_tutup" value="{{ $setting->gelombang_3_tutup }}" class="w-full px-4 py-3 bg-white border border-gray-200 rounded-xl text-[13px] font-bold text-brand-dark outline-none"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </form>

            <form id="passwordForm" action="{{ route('admin.settings.password') }}" method="POST">
                @csrf
                <div x-show="activeTab === 'keamanan'" x-cloak style="display: none;" class="bg-white rounded-[2.5rem] p-10 shadow-sm border border-gray-100">
                    <div class="mb-8 border-b border-gray-100 pb-6">
                        <h2 class="text-xl font-extrabold text-brand-dark tracking-tight">Keamanan Akun</h2>
                        <p class="text-[13px] font-medium text-gray-400 mt-1">Ganti password Admin Anda di sini.</p>
                    </div>

                    <div class="space-y-6">
                        <div>
                            <label class="block text-[11px] font-black text-gray-500 uppercase tracking-widest mb-2">Password Saat Ini</label>
                            <input type="password" name="current_password" required placeholder="••••••••" class="w-full px-5 py-4 bg-gray-50 border border-gray-100 rounded-2xl text-[14px] font-bold text-brand-dark outline-none focus:ring-2 focus:ring-brand-blue/20">
                            @error('current_password') <span class="text-red-500 text-xs font-bold mt-1 block">{{ $message }}</span> @enderror
                        </div>
                        
                        <div class="w-full h-px bg-gray-100 my-4"></div>

                        <div>
                            <label class="block text-[11px] font-black text-gray-500 uppercase tracking-widest mb-2">Password Baru</label>
                            <input type="password" name="new_password" required placeholder="Masukkan password baru" class="w-full px-5 py-4 bg-gray-50 border border-gray-100 rounded-2xl text-[14px] font-bold text-brand-dark outline-none focus:ring-2 focus:ring-brand-blue/20">
                            @error('new_password') <span class="text-red-500 text-xs font-bold mt-1 block">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <label class="block text-[11px] font-black text-gray-500 uppercase tracking-widest mb-2">Konfirmasi Password Baru</label>
                            <input type="password" name="new_password_confirmation" required placeholder="Ketik ulang password baru" class="w-full px-5 py-4 bg-gray-50 border border-gray-100 rounded-2xl text-[14px] font-bold text-brand-dark outline-none focus:ring-2 focus:ring-brand-blue/20">
                        </div>
                    </div>
                </div>
            </form>

        </div>
    </div>
</div>

<script>
    document.addEventListener('alpine:initialized', () => {
        const observer = new MutationObserver(() => { if (typeof feather !== 'undefined') feather.replace(); });
        observer.observe(document.body, { childList: true, subtree: true });
        setTimeout(() => { feather.replace(); }, 50);
    });
</script>
@endsection