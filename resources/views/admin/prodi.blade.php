@extends('layouts.admin')

@section('admin-content')
<div x-data="{ modalOpen: false }">
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-10 gap-4">
        <div>
            <h1 class="text-3xl font-extrabold text-brand-dark tracking-tight mb-2">Manajemen Program Studi</h1>
            <p class="text-brand-gray text-[14px] font-medium">Kelola daftar program studi, akreditasi, dan daya tampung mahasiswa.</p>
        </div>
        <button @click="modalOpen = true" class="flex items-center gap-2 px-6 py-3 bg-brand-dark text-white rounded-2xl font-bold text-[13px] hover:bg-brand-blue transition-all shadow-lg shadow-brand-dark/10">
            <i data-feather="plus" class="w-4 h-4"></i> Tambah Prodi Baru
        </button>
    </div>

    <div class="bg-white rounded-[2.5rem] shadow-sm border border-gray-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left whitespace-nowrap">
                <thead class="bg-gray-50/50 text-[11px] font-black text-brand-gray uppercase tracking-widest border-b">
                    <tr>
                        <th class="px-8 py-6">Program Studi</th>
                        <th class="px-4 py-6">Jenjang</th>
                        <th class="px-4 py-6">Akreditasi</th>
                        <th class="px-4 py-6 text-center">Daya Tampung</th>
                        <th class="px-4 py-6">Biaya (Smt)</th>
                        <th class="px-8 py-6 text-right">Aksi</th>
                    </tr>
                </thead>
<tbody class="divide-y divide-gray-50 text-[13px]">
    <tr class="hover:bg-gray-50/50 transition-colors group">
        <td class="px-8 py-5">
            <div class="flex items-center gap-4">
                <div class="w-10 h-10 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center">
                    <i data-feather="terminal" class="w-5 h-5"></i>
                </div>
                <span class="font-bold text-brand-dark text-[15px]">S1 Informatika</span>
            </div>
        </td>
        <td class="px-4 py-5 font-bold text-gray-500">S1</td>
        <td class="px-4 py-5"><span class="px-3 py-1 bg-green-50 text-green-600 rounded-lg text-[10px] font-black uppercase">Unggul</span></td>
        <td class="px-4 py-5 text-center font-black text-brand-dark">120</td>
        <td class="px-4 py-5 font-bold text-gray-600">Rp 8.500.000</td>
        <td class="px-8 py-5 text-right">
            <div class="flex justify-end gap-2 opacity-0 group-hover:opacity-100 transition-opacity">
                <button class="p-2 text-brand-gray hover:text-brand-blue"><i data-feather="edit-3" class="w-4 h-4"></i></button>
                <button class="p-2 text-brand-gray hover:text-red-500"><i data-feather="trash-2" class="w-4 h-4"></i></button>
            </div>
        </td>
    </tr>

    <tr class="hover:bg-gray-50/50 transition-colors group">
        <td class="px-8 py-5">
            <div class="flex items-center gap-4">
                <div class="w-10 h-10 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center">
                    <i data-feather="layout" class="w-5 h-5"></i>
                </div>
                <span class="font-bold text-brand-dark text-[15px]">S1 Sistem Informasi</span>
            </div>
        </td>
        <td class="px-4 py-5 font-bold text-gray-500">S1</td>
        <td class="px-4 py-5"><span class="px-3 py-1 bg-green-50 text-green-600 rounded-lg text-[10px] font-black uppercase">Unggul</span></td>
        <td class="px-4 py-5 text-center font-black text-brand-dark">80</td>
        <td class="px-4 py-5 font-bold text-gray-600">Rp 8.000.000</td>
        <td class="px-8 py-5 text-right">
            <div class="flex justify-end gap-2 opacity-0 group-hover:opacity-100 transition-opacity">
                <button class="p-2 text-brand-gray hover:text-brand-blue"><i data-feather="edit-3" class="w-4 h-4"></i></button>
                <button class="p-2 text-brand-gray hover:text-red-500"><i data-feather="trash-2" class="w-4 h-4"></i></button>
            </div>
        </td>
    </tr>

    <tr class="hover:bg-gray-50/50 transition-colors group">
        <td class="px-8 py-5">
            <div class="flex items-center gap-4">
                <div class="w-10 h-10 rounded-xl bg-purple-50 text-purple-600 flex items-center justify-center">
                    <i data-feather="settings" class="w-5 h-5"></i>
                </div>
                <span class="font-bold text-brand-dark text-[15px]">S1 Teknik Industri</span>
            </div>
        </td>
        <td class="px-4 py-5 font-bold text-gray-500">S1</td>
        <td class="px-4 py-5"><span class="px-3 py-1 bg-blue-50 text-blue-600 rounded-lg text-[10px] font-black uppercase">A</span></td>
        <td class="px-4 py-5 text-center font-black text-brand-dark">60</td>
        <td class="px-4 py-5 font-bold text-gray-600">Rp 8.500.000</td>
        <td class="px-8 py-5 text-right">
            <div class="flex justify-end gap-2 opacity-0 group-hover:opacity-100 transition-opacity">
                <button class="p-2 text-brand-gray hover:text-brand-blue"><i data-feather="edit-3" class="w-4 h-4"></i></button>
                <button class="p-2 text-brand-gray hover:text-red-500"><i data-feather="trash-2" class="w-4 h-4"></i></button>
            </div>
        </td>
    </tr>

    <tr class="hover:bg-gray-50/50 transition-colors group">
        <td class="px-8 py-5">
            <div class="flex items-center gap-4">
                <div class="w-10 h-10 rounded-xl bg-pink-50 text-pink-600 flex items-center justify-center">
                    <i data-feather="pen-tool" class="w-5 h-5"></i>
                </div>
                <span class="font-bold text-brand-dark text-[15px]">S1 DKV</span>
            </div>
        </td>
        <td class="px-4 py-5 font-bold text-gray-500">S1</td>
        <td class="px-4 py-5"><span class="px-3 py-1 bg-green-50 text-green-600 rounded-lg text-[10px] font-black uppercase">Unggul</span></td>
        <td class="px-4 py-5 text-center font-black text-brand-dark">100</td>
        <td class="px-4 py-5 font-bold text-gray-600">Rp 8.000.000</td>
        <td class="px-8 py-5 text-right">
            <div class="flex justify-end gap-2 opacity-0 group-hover:opacity-100 transition-opacity">
                <button class="p-2 text-brand-gray hover:text-brand-blue"><i data-feather="edit-3" class="w-4 h-4"></i></button>
                <button class="p-2 text-brand-gray hover:text-red-500"><i data-feather="trash-2" class="w-4 h-4"></i></button>
            </div>
        </td>
    </tr>

    <tr class="hover:bg-gray-50/50 transition-colors group">
        <td class="px-8 py-5">
            <div class="flex items-center gap-4">
                <div class="w-10 h-10 rounded-xl bg-orange-50 text-orange-600 flex items-center justify-center">
                    <i data-feather="users" class="w-5 h-5"></i>
                </div>
                <span class="font-bold text-brand-dark text-[15px]">S1 PGSD</span>
            </div>
        </td>
        <td class="px-4 py-5 font-bold text-gray-500">S1</td>
        <td class="px-4 py-5"><span class="px-3 py-1 bg-blue-50 text-blue-600 rounded-lg text-[10px] font-black uppercase">A</span></td>
        <td class="px-4 py-5 text-center font-black text-brand-dark">200</td>
        <td class="px-4 py-5 font-bold text-gray-600">Rp 6.000.000</td>
        <td class="px-8 py-5 text-right">
            <div class="flex justify-end gap-2 opacity-0 group-hover:opacity-100 transition-opacity">
                <button class="p-2 text-brand-gray hover:text-brand-blue"><i data-feather="edit-3" class="w-4 h-4"></i></button>
                <button class="p-2 text-brand-gray hover:text-red-500"><i data-feather="trash-2" class="w-4 h-4"></i></button>
            </div>
        </td>
    </tr>

    <tr class="hover:bg-gray-50/50 transition-colors group">
        <td class="px-8 py-5">
            <div class="flex items-center gap-4">
                <div class="w-10 h-10 rounded-xl bg-red-50 text-red-600 flex items-center justify-center">
                    <i data-feather="heart" class="w-5 h-5"></i>
                </div>
                <span class="font-bold text-brand-dark text-[15px]">S1 Gizi</span>
            </div>
        </td>
        <td class="px-4 py-5 font-bold text-gray-500">S1</td>
        <td class="px-4 py-5"><span class="px-3 py-1 bg-green-50 text-green-600 rounded-lg text-[10px] font-black uppercase">Unggul</span></td>
        <td class="px-4 py-5 text-center font-black text-brand-dark">75</td>
        <td class="px-4 py-5 font-bold text-gray-600">Rp 7.500.000</td>
        <td class="px-8 py-5 text-right">
            <div class="flex justify-end gap-2 opacity-0 group-hover:opacity-100 transition-opacity">
                <button class="p-2 text-brand-gray hover:text-brand-blue"><i data-feather="edit-3" class="w-4 h-4"></i></button>
                <button class="p-2 text-brand-gray hover:text-red-500"><i data-feather="trash-2" class="w-4 h-4"></i></button>
            </div>
        </td>
    </tr>

    <tr class="hover:bg-gray-50/50 transition-colors group">
        <td class="px-8 py-5">
            <div class="flex items-center gap-4">
                <div class="w-10 h-10 rounded-xl bg-slate-100 text-slate-700 flex items-center justify-center">
                    <i data-feather="shield" class="w-5 h-5"></i>
                </div>
                <span class="font-bold text-brand-dark text-[15px]">S1 Hukum Bisnis</span>
            </div>
        </td>
        <td class="px-4 py-5 font-bold text-gray-500">S1</td>
        <td class="px-4 py-5"><span class="px-3 py-1 bg-green-50 text-green-600 rounded-lg text-[10px] font-black uppercase">Unggul</span></td>
        <td class="px-4 py-5 text-center font-black text-brand-dark">100</td>
        <td class="px-4 py-5 font-bold text-gray-600">Rp 7.500.000</td>
        <td class="px-8 py-5 text-right">
            <div class="flex justify-end gap-2 opacity-0 group-hover:opacity-100 transition-opacity">
                <button class="p-2 text-brand-gray hover:text-brand-blue"><i data-feather="edit-3" class="w-4 h-4"></i></button>
                <button class="p-2 text-brand-gray hover:text-red-500"><i data-feather="trash-2" class="w-4 h-4"></i></button>
            </div>
        </td>
    </tr>

    <tr class="hover:bg-gray-50/50 transition-colors group">
        <td class="px-8 py-5">
            <div class="flex items-center gap-4">
                <div class="w-10 h-10 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center">
                    <i data-feather="briefcase" class="w-5 h-5"></i>
                </div>
                <span class="font-bold text-brand-dark text-[15px]">S1 Kewirausahaan</span>
            </div>
        </td>
        <td class="px-4 py-5 font-bold text-gray-500">S1</td>
        <td class="px-4 py-5"><span class="px-3 py-1 bg-green-50 text-green-600 rounded-lg text-[10px] font-black uppercase">Unggul</span></td>
        <td class="px-4 py-5 text-center font-black text-brand-dark">50</td>
        <td class="px-4 py-5 font-bold text-gray-600">Rp 7.500.000</td>
        <td class="px-8 py-5 text-right">
            <div class="flex justify-end gap-2 opacity-0 group-hover:opacity-100 transition-opacity">
                <button class="p-2 text-brand-gray hover:text-brand-blue"><i data-feather="edit-3" class="w-4 h-4"></i></button>
                <button class="p-2 text-brand-gray hover:text-red-500"><i data-feather="trash-2" class="w-4 h-4"></i></button>
            </div>
        </td>
    </tr>

    <tr class="hover:bg-gray-50/50 transition-colors group">
        <td class="px-8 py-5">
            <div class="flex items-center gap-4">
                <div class="w-10 h-10 rounded-xl bg-indigo-50 text-indigo-600 flex items-center justify-center">
                    <i data-feather="award" class="w-5 h-5"></i>
                </div>
                <span class="font-bold text-brand-dark text-[15px]">S2 Pendidikan Dasar</span>
            </div>
        </td>
        <td class="px-4 py-5 font-bold text-gray-500">S2</td>
        <td class="px-4 py-5"><span class="px-3 py-1 bg-green-50 text-green-600 rounded-lg text-[10px] font-black uppercase">Unggul</span></td>
        <td class="px-4 py-5 text-center font-black text-brand-dark">40</td>
        <td class="px-4 py-5 font-bold text-gray-600">Rp 12.000.000</td>
        <td class="px-8 py-5 text-right">
            <div class="flex justify-end gap-2 opacity-0 group-hover:opacity-100 transition-opacity">
                <button class="p-2 text-brand-gray hover:text-brand-blue"><i data-feather="edit-3" class="w-4 h-4"></i></button>
                <button class="p-2 text-brand-gray hover:text-red-500"><i data-feather="trash-2" class="w-4 h-4"></i></button>
            </div>
        </td>
    </tr>
</tbody>
            </table>
        </div>
    </div>

    <template x-teleport="body">
        <div x-show="modalOpen" class="fixed inset-0 z-[999] flex items-center justify-center p-4" x-cloak>
            <div x-show="modalOpen" x-transition.opacity @click="modalOpen = false" class="absolute inset-0 bg-brand-dark/70 backdrop-blur-sm"></div>
            
            <div x-show="modalOpen" 
                 x-transition:enter="transition ease-out duration-300"
                 x-transition:enter-start="opacity-0 scale-95 translate-y-4"
                 x-transition:enter-end="opacity-100 scale-100 translate-y-0"
                 class="bg-white w-full max-w-2xl rounded-[2.5rem] shadow-2xl relative z-10 overflow-hidden">
                
                <form class="p-10">
                    <div class="flex justify-between items-center mb-8">
                        <h2 class="text-2xl font-black text-brand-dark tracking-tight">Tambah Program Studi</h2>
                        <button type="button" @click="modalOpen = false" class="text-gray-400 hover:text-brand-dark"><i data-feather="x"></i></button>
                    </div>

                    <div class="grid grid-cols-2 gap-6 mb-8">
                        <div class="col-span-2">
                            <label class="block text-[11px] font-black text-gray-400 uppercase tracking-widest mb-2">Nama Program Studi</label>
                            <input type="text" placeholder="Misal: Teknik Elektro" class="w-full px-5 py-4 bg-gray-50 border border-gray-100 rounded-2xl outline-none focus:ring-2 focus:ring-brand-blue/20 transition-all font-bold text-sm">
                        </div>
                        <div>
                            <label class="block text-[11px] font-black text-gray-400 uppercase tracking-widest mb-2">Jenjang</label>
                            <select class="w-full px-5 py-4 bg-gray-50 border border-gray-100 rounded-2xl outline-none font-bold text-sm appearance-none">
                                <option>Sarjana (S1)</option>
                                <option>Diploma (D3)</option>
                                <option>Magister (S2)</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-[11px] font-black text-gray-400 uppercase tracking-widest mb-2">Akreditasi</label>
                            <select class="w-full px-5 py-4 bg-gray-50 border border-gray-100 rounded-2xl outline-none font-bold text-sm appearance-none">
                                <option>Unggul</option>
                                <option>A</option>
                                <option>B</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-[11px] font-black text-gray-400 uppercase tracking-widest mb-2">Daya Tampung</label>
                            <input type="number" placeholder="0" class="w-full px-5 py-4 bg-gray-50 border border-gray-100 rounded-2xl outline-none font-bold text-sm">
                        </div>
                        <div>
                            <label class="block text-[11px] font-black text-gray-400 uppercase tracking-widest mb-2">Biaya per Semester</label>
                            <input type="text" placeholder="Rp 0" class="w-full px-5 py-4 bg-gray-50 border border-gray-100 rounded-2xl outline-none font-bold text-sm">
                        </div>
                    </div>

                    <div class="flex gap-4">
                        <button type="button" @click="modalOpen = false" class="flex-1 py-4 bg-gray-100 text-brand-gray rounded-2xl font-black text-[12px] uppercase tracking-widest hover:bg-gray-200 transition-all">Batal</button>
                        <button type="submit" class="flex-1 py-4 bg-brand-dark text-white rounded-2xl font-black text-[12px] uppercase tracking-widest hover:bg-brand-blue shadow-xl shadow-brand-dark/10 transition-all">Simpan Prodi</button>
                    </div>
                </form>
            </div>
        </div>
    </template>
</div>
@endsection