@extends('layouts.admin')

@section('title', 'Master Program Studi')

@section('admin-content')

{{-- ── Header ──────────────────────────────────────────────── --}}
<div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-8 gap-4">
    <div>
        <div class="flex items-center gap-1.5 text-[11px] font-black uppercase tracking-widest text-gray-400 mb-2">
            <a href="/admin" class="hover:text-brand-dark transition-colors">Dashboard</a>
            <i data-feather="chevron-right" class="w-3 h-3"></i>
            <span class="text-brand-dark">Master Data</span>
            <i data-feather="chevron-right" class="w-3 h-3"></i>
            <span class="text-brand-blue">Program Studi</span>
        </div>
        <h1 class="text-2xl font-extrabold text-brand-dark tracking-tight">Program Studi</h1>
        <p class="text-brand-gray text-[13px] font-medium mt-1">Kelola daftar program studi, akreditasi, dan daya tampung mahasiswa.</p>
    </div>
    <button onclick="bukaModalTambah()"
        class="flex items-center gap-2 px-5 py-3 bg-brand-dark text-white rounded-xl font-bold text-[13px] hover:bg-brand-blue transition-all shadow-lg shrink-0">
        <i data-feather="plus" class="w-4 h-4"></i> Tambah Prodi
    </button>
</div>

{{-- Flash --}}
@if(session('success'))
<div class="mb-5 p-4 bg-green-50 border border-green-200 text-green-800 font-bold rounded-2xl text-[13px] flex items-center gap-3">
    <i data-feather="check-circle" class="w-5 h-5 shrink-0 text-green-500"></i> {{ session('success') }}
</div>
@endif
@if(session('error'))
<div class="mb-5 p-4 bg-red-50 border border-red-200 text-red-800 font-bold rounded-2xl text-[13px] flex items-center gap-3">
    <i data-feather="alert-circle" class="w-5 h-5 shrink-0 text-red-500"></i> {{ session('error') }}
</div>
@endif

{{-- ── Tabel ─────────────────────────────────────────────────── --}}
<div class="bg-white rounded-[2rem] shadow-sm border border-gray-100 overflow-hidden">
    <div class="px-6 py-4 border-b border-gray-100">
        <p class="text-[13px] font-bold text-brand-gray">
            Total: <span class="text-brand-dark font-black">{{ $data->count() }}</span> program studi
        </p>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full text-left min-w-[600px]">
            <thead class="bg-gray-50/50 text-[11px] font-black text-brand-dark uppercase tracking-widest border-b border-gray-100">
                <tr>
                    <th class="px-6 py-5 w-12">No.</th>
                    <th class="px-4 py-5">Program Studi</th>
                    <th class="px-4 py-5">Jenjang</th>
                    <th class="px-4 py-5">Akreditasi</th>
                    <th class="px-4 py-5 text-center">Kuota</th>
                    <th class="px-4 py-5 text-center">Biaya Daftar Ulang</th>
                    <th class="px-6 py-5 text-right">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50 text-[13px]">
                @forelse($data as $item)
                @php
                    $akr         = trim($item->akreditasi ?? '');
                    $jumlahBiaya = \App\Models\BiayaDaftarUlang::where('prodi_id', $item->id)->count();
                    $akrGreen    = in_array($akr, ['Unggul', 'A', 'Baik Sekali']);
                @endphp
                <tr class="hover:bg-gray-50/50 transition-colors group">

                    {{-- No. --}}
                    <td class="px-6 py-4 text-gray-400 font-bold text-[12px]">{{ $loop->iteration }}</td>

                    {{-- Nama --}}
                    <td class="px-4 py-4">
                        <div class="flex items-center gap-3">
                            <div class="w-9 h-9 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center shrink-0">
                                <i data-feather="{{ $item->icon ?? 'book' }}" class="w-4 h-4"></i>
                            </div>
                            <span class="font-bold text-brand-dark">{{ $item->nama }}</span>
                        </div>
                    </td>

                    {{-- Jenjang --}}
                    <td class="px-4 py-4">
                        <span class="px-2.5 py-1 rounded-lg text-[10px] font-black uppercase tracking-widest
                            {{ $item->jenjang === 'S2' ? 'bg-purple-50 text-purple-700' : ($item->jenjang === 'D3' ? 'bg-orange-50 text-orange-700' : 'bg-blue-50 text-blue-700') }}">
                            {{ $item->jenjang }}
                        </span>
                    </td>

                    {{-- Akreditasi --}}
                    <td class="px-4 py-4">
                        <span class="px-2.5 py-1 rounded-lg text-[10px] font-black uppercase whitespace-nowrap
                            {{ $akrGreen ? 'bg-green-50 text-green-700' : 'bg-blue-50 text-blue-600' }}">
                            {{ $akr ?: '-' }}
                        </span>
                    </td>

                    {{-- Kuota --}}
                    <td class="px-4 py-4 text-center font-black text-brand-dark">{{ number_format($item->kuota ?? 0) }}</td>

                    {{-- Biaya Daftar Ulang --}}
                    <td class="px-4 py-4 text-center">
                        <a href="{{ route('admin.master.biaya-daftar-ulang.index', ['prodi_filter' => $item->id]) }}"
                           class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-[11px] font-bold transition-all
                               {{ $jumlahBiaya > 0 ? 'bg-emerald-50 text-emerald-700 border border-emerald-200 hover:bg-emerald-100' : 'bg-amber-50 text-amber-700 border border-amber-200 hover:bg-amber-100' }}">
                            @if($jumlahBiaya > 0)
                                <i data-feather="check-circle" class="w-3.5 h-3.5"></i> {{ $jumlahBiaya }} data
                            @else
                                <i data-feather="alert-circle" class="w-3.5 h-3.5"></i> Belum diatur
                            @endif
                        </a>
                    </td>

                    {{-- Aksi --}}
                    <td class="px-6 py-4 text-right">
                        <div class="flex justify-end gap-1 opacity-0 group-hover:opacity-100 transition-opacity">
                            <button type="button" onclick="bukaModalEdit({{ $item->id }}, '{{ addslashes($item->nama) }}', '{{ $item->jenjang }}', '{{ addslashes($akr) }}', '{{ $item->kuota ?? 0 }}', '{{ $item->icon ?? 'book-open' }}')"
                                class="p-2 text-brand-gray hover:text-brand-blue rounded-lg hover:bg-blue-50 transition-colors" title="Edit">
                                <i data-feather="edit-3" class="w-4 h-4"></i>
                            </button>
                            <a href="{{ route('admin.master.biaya-daftar-ulang.index', ['prodi_filter' => $item->id]) }}"
                               class="p-2 text-brand-gray hover:text-emerald-600 rounded-lg hover:bg-emerald-50 transition-colors" title="Kelola Biaya">
                                <i data-feather="dollar-sign" class="w-4 h-4"></i>
                            </a>
                            <form action="{{ route('admin.prodi.destroy', $item->id) }}" method="POST"
                                  class="inline" onsubmit="return confirm('Hapus program studi ini?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="p-2 text-brand-gray hover:text-red-500 rounded-lg hover:bg-red-50 transition-colors" title="Hapus">
                                    <i data-feather="trash-2" class="w-4 h-4"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="px-6 py-16 text-center">
                        <div class="flex flex-col items-center gap-3">
                            <div class="w-14 h-14 rounded-2xl bg-gray-100 flex items-center justify-center">
                                <i data-feather="book-open" class="w-7 h-7 text-gray-300"></i>
                            </div>
                            <p class="text-gray-400 font-bold">Belum ada data program studi.</p>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<div class="mt-4 flex items-start gap-2 text-[12px] text-gray-400 font-medium">
    <i data-feather="info" class="w-3.5 h-3.5 shrink-0 mt-0.5"></i>
    <span>Kolom <strong class="text-gray-600">Biaya Daftar Ulang</strong> menunjukkan jumlah konfigurasi biaya yang sudah diatur. Klik untuk langsung mengelolanya.</span>
</div>

{{-- ══════════════════════════════════════════════════════════
     MODAL — satu modal untuk tambah & edit, murni HTML+JS
     Tidak pakai Alpine x-model / x-teleport sama sekali
═══════════════════════════════════════════════════════════ --}}
<div id="modal-prodi"
     class="fixed inset-0 z-[999] flex items-center justify-center p-4 hidden">
    <div class="absolute inset-0 bg-brand-dark/70 backdrop-blur-sm" onclick="tutupModal()"></div>

    <div class="bg-white w-full max-w-xl rounded-[2rem] shadow-2xl relative z-10 flex flex-col max-h-[90vh]">

        {{-- Header --}}
        <div class="flex justify-between items-center px-8 pt-7 pb-5 shrink-0 border-b border-gray-100">
            <div>
                <h2 id="modal-title" class="text-xl font-black text-brand-dark tracking-tight">Tambah Program Studi</h2>
                <p class="text-[12px] text-gray-400 font-medium mt-0.5">Isi semua field yang diperlukan</p>
            </div>
            <button type="button" onclick="tutupModal()"
                class="p-2 text-gray-400 hover:text-brand-dark hover:bg-gray-100 rounded-xl transition-colors">
                <i data-feather="x" class="w-5 h-5"></i>
            </button>
        </div>

        {{-- Scrollable body --}}
        <div class="overflow-y-auto flex-1 px-8 py-6">

            {{-- FORM TAMBAH (method POST) --}}
            <form id="form-tambah" action="{{ route('admin.prodi.store') }}" method="POST">
                @csrf
                <div class="space-y-5">

                    {{-- Ikon dengan preview --}}
                    <div>
                        <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-2">Ikon Prodi</label>
                        <div class="flex items-center gap-4">
                            <div id="tambah-icon-preview"
                                 class="w-14 h-14 bg-blue-50 rounded-2xl flex items-center justify-center text-blue-600 shrink-0 shadow-inner">
                                <i data-feather="book-open" class="w-6 h-6"></i>
                            </div>
                            <div class="relative flex-1">
                                <select name="icon" id="tambah-icon" onchange="updatePreviewTambah()"
                                    class="w-full px-4 py-3.5 bg-gray-50 border border-gray-200 rounded-xl outline-none focus:border-brand-blue font-bold text-sm appearance-none cursor-pointer">
                                    @foreach(['book-open','monitor','cpu','briefcase','heart','activity','code','database','globe','layout','pen-tool','users','bar-chart-2','tool','zap','award','target','compass','book','layers','pie-chart','settings','camera','dollar-sign'] as $ic)
                                    <option value="{{ $ic }}">{{ str_replace('-', ' ', $ic) }}</option>
                                    @endforeach
                                </select>
                                <i data-feather="chevron-down" class="absolute right-4 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400 pointer-events-none"></i>
                            </div>
                        </div>
                    </div>

                    {{-- Nama --}}
                    <div>
                        <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-2">Nama Program Studi</label>
                        <input type="text" name="nama" id="tambah-nama" placeholder="Misal: Teknik Informatika" required
                            class="w-full px-4 py-3.5 bg-gray-50 border border-gray-200 rounded-xl outline-none focus:border-brand-blue font-bold text-sm">
                    </div>

                    {{-- Jenjang & Akreditasi --}}
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-2">Jenjang</label>
                            <div class="relative">
                                <select name="jenjang" id="tambah-jenjang"
                                    class="w-full px-4 py-3.5 bg-gray-50 border border-gray-200 rounded-xl outline-none focus:border-brand-blue font-bold text-sm appearance-none cursor-pointer">
                                    <option value="S1">Sarjana (S1)</option>
                                    <option value="S2">Magister (S2)</option>
                                    <option value="D3">Diploma (D3)</option>
                                    <option value="Profesi">Profesi</option>
                                </select>
                                <i data-feather="chevron-down" class="absolute right-4 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400 pointer-events-none"></i>
                            </div>
                        </div>
                        <div>
                            <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-2">Akreditasi</label>
                            <div class="relative">
                                <select name="akreditasi" id="tambah-akreditasi"
                                    class="w-full px-4 py-3.5 bg-gray-50 border border-gray-200 rounded-xl outline-none focus:border-brand-blue font-bold text-sm appearance-none cursor-pointer">
                                    <option value="Unggul">Unggul</option>
                                    <option value="A">A</option>
                                    <option value="Baik Sekali">Baik Sekali</option>
                                    <option value="B">B</option>
                                    <option value="Baik">Baik</option>
                                    <option value="C">C</option>
                                    <option value="Terakreditasi Sementara">Terakreditasi Sementara</option>
                                </select>
                                <i data-feather="chevron-down" class="absolute right-4 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400 pointer-events-none"></i>
                            </div>
                        </div>
                    </div>

                    {{-- Kuota --}}
                    <div>
                        <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-2">Daya Tampung</label>
                        <input type="number" name="kuota" id="tambah-kuota" placeholder="0" required min="0"
                            class="w-full px-4 py-3.5 bg-gray-50 border border-gray-200 rounded-xl outline-none focus:border-brand-blue font-bold text-sm">
                    </div>

                </div>
            </form>

            {{-- FORM EDIT (method PUT via _method) --}}
            <form id="form-edit" action="" method="POST" class="hidden">
                @csrf
                @method('PUT')
                <div class="space-y-5">
                    <div>
                        <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-2">Ikon Prodi</label>
                        <div class="flex items-center gap-4">
                            <div id="edit-icon-preview"
                                 class="w-14 h-14 bg-blue-50 rounded-2xl flex items-center justify-center text-blue-600 shrink-0 shadow-inner">
                                <i data-feather="book-open" class="w-6 h-6"></i>
                            </div>
                            <div class="relative flex-1">
                                <select name="icon" id="edit-icon" onchange="updatePreviewEdit()"
                                    class="w-full px-4 py-3.5 bg-gray-50 border border-gray-200 rounded-xl outline-none focus:border-brand-blue font-bold text-sm appearance-none cursor-pointer">
                                    @foreach(['book-open','monitor','cpu','briefcase','heart','activity','code','database','globe','layout','pen-tool','users','bar-chart-2','tool','zap','award','target','compass','book','layers','pie-chart','settings','camera','dollar-sign'] as $ic)
                                    <option value="{{ $ic }}">{{ str_replace('-', ' ', $ic) }}</option>
                                    @endforeach
                                </select>
                                <i data-feather="chevron-down" class="absolute right-4 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400 pointer-events-none"></i>
                            </div>
                        </div>
                    </div>
                    <div>
                        <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-2">Nama Program Studi</label>
                        <input type="text" name="nama" id="edit-nama" placeholder="Misal: Teknik Informatika" required
                            class="w-full px-4 py-3.5 bg-gray-50 border border-gray-200 rounded-xl outline-none focus:border-brand-blue font-bold text-sm">
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-2">Jenjang</label>
                            <select name="jenjang" id="edit-jenjang"
                                class="w-full px-4 py-3.5 bg-gray-50 border border-gray-200 rounded-xl outline-none focus:border-brand-blue font-bold text-sm appearance-none cursor-pointer">
                                <option value="S1">Sarjana (S1)</option>
                                <option value="S2">Magister (S2)</option>
                                <option value="D3">Diploma (D3)</option>
                                <option value="Profesi">Profesi</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-2">Akreditasi</label>
                            <select name="akreditasi" id="edit-akreditasi"
                                class="w-full px-4 py-3.5 bg-gray-50 border border-gray-200 rounded-xl outline-none focus:border-brand-blue font-bold text-sm appearance-none cursor-pointer">
                                <option value="Unggul">Unggul</option>
                                <option value="A">A</option>
                                <option value="Baik Sekali">Baik Sekali</option>
                                <option value="B">B</option>
                                <option value="Baik">Baik</option>
                                <option value="C">C</option>
                                <option value="Terakreditasi Sementara">Terakreditasi Sementara</option>
                            </select>
                        </div>
                    </div>
                    <div>
                        <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-2">Daya Tampung</label>
                        <input type="number" name="kuota" id="edit-kuota" placeholder="0" required min="0"
                            class="w-full px-4 py-3.5 bg-gray-50 border border-gray-200 rounded-xl outline-none focus:border-brand-blue font-bold text-sm">
                    </div>
                    <div class="p-4 bg-amber-50 border border-amber-200 rounded-xl flex items-start gap-3">
                        <i data-feather="info" class="w-4 h-4 text-amber-600 shrink-0 mt-0.5"></i>
                        <div>
                            <p class="text-[12px] font-bold text-amber-800">Perlu ubah biaya daftar ulang?</p>
                            <p class="text-[11px] text-amber-700 font-medium mt-0.5">
                                Biaya dikelola per jalur &amp; gelombang di halaman
                                <a id="link-biaya" href="#" class="underline font-black hover:text-amber-900">Master Biaya Daftar Ulang →</a>
                            </p>
                        </div>
                    </div>
                </div>
            </form>

        </div>

        {{-- Footer tombol --}}
        <div class="flex gap-3 px-8 py-5 shrink-0 border-t border-gray-100 bg-gray-50/50 rounded-b-[2rem]">
            <button type="button" onclick="tutupModal()"
                class="flex-1 py-3.5 bg-white border border-gray-200 text-brand-gray rounded-xl font-bold text-[13px] hover:bg-gray-100 transition-colors">
                Batal
            </button>
            <button type="button" id="btn-simpan" onclick="submitAktif()"
                class="flex-1 py-3.5 bg-brand-dark text-white rounded-xl font-bold text-[13px] hover:bg-brand-blue shadow-lg transition-colors">
                Simpan
            </button>
        </div>
    </div>
</div>

<script>
const BASE_PRODI_URL = '{{ url("admin/prodi") }}';
const BIAYA_URL      = '{{ url("admin/master/biaya-daftar-ulang") }}';
let modeAktif = 'tambah'; // 'tambah' | 'edit'

function bukaModalTambah() {
    modeAktif = 'tambah';
    document.getElementById('modal-title').textContent = 'Tambah Program Studi';
    document.getElementById('btn-simpan').textContent  = 'Tambah Prodi';

    // Reset form tambah
    document.getElementById('form-tambah').reset();
    document.getElementById('form-tambah').classList.remove('hidden');
    document.getElementById('form-edit').classList.add('hidden');

    document.getElementById('modal-prodi').classList.remove('hidden');
    if (typeof feather !== 'undefined') feather.replace();
}

function updatePreviewEdit() {
    const icon = document.getElementById('edit-icon').value;
    const preview = document.getElementById('edit-icon-preview');
    preview.innerHTML = `<i data-feather="${icon}" class="w-6 h-6"></i>`;
    if (typeof feather !== 'undefined') feather.replace();
}

function bukaModalEdit(id, nama, jenjang, akreditasi, kuota, icon) {
    modeAktif = 'edit';
    document.getElementById('modal-title').textContent = 'Edit Program Studi';
    document.getElementById('btn-simpan').textContent  = 'Simpan Perubahan';

    // Set action form edit ke route yang benar
    document.getElementById('form-edit').action = `${BASE_PRODI_URL}/${id}`;
    document.getElementById('link-biaya').href   = `${BIAYA_URL}?prodi_filter=${id}`;

    // Isi nilai field
    document.getElementById('edit-nama').value  = nama;
    document.getElementById('edit-kuota').value = kuota;

    // Set select dengan value yang benar
    setSelectValue('edit-icon',       icon);
    setSelectValue('edit-jenjang',    jenjang);
    setSelectValue('edit-akreditasi', akreditasi.trim());

    // Update preview ikon
    const preview = document.getElementById('edit-icon-preview');
    preview.innerHTML = `<i data-feather="${icon || 'book-open'}" class="w-6 h-6"></i>`;

    document.getElementById('form-tambah').classList.add('hidden');
    document.getElementById('form-edit').classList.remove('hidden');

    document.getElementById('modal-prodi').classList.remove('hidden');
    if (typeof feather !== 'undefined') feather.replace();
}

function setSelectValue(id, value) {
    const sel = document.getElementById(id);
    // Coba exact match dulu
    for (let i = 0; i < sel.options.length; i++) {
        if (sel.options[i].value === value) {
            sel.selectedIndex = i;
            return;
        }
    }
    // Fallback: case-insensitive
    const lower = (value || '').toLowerCase().trim();
    for (let i = 0; i < sel.options.length; i++) {
        if (sel.options[i].value.toLowerCase() === lower) {
            sel.selectedIndex = i;
            return;
        }
    }
}

function submitAktif() {
    if (modeAktif === 'edit') {
        document.getElementById('form-edit').submit();
    } else {
        document.getElementById('form-tambah').submit();
    }
}

function tutupModal() {
    document.getElementById('modal-prodi').classList.add('hidden');
}

function updatePreviewTambah() {
    const icon = document.getElementById('tambah-icon').value;
    const preview = document.getElementById('tambah-icon-preview');
    preview.innerHTML = `<i data-feather="${icon}" class="w-6 h-6"></i>`;
    if (typeof feather !== 'undefined') feather.replace();
}

document.addEventListener('DOMContentLoaded', () => {
    if (typeof feather !== 'undefined') feather.replace();
});
</script>

@endsection