<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Letter of Acceptance - <?php echo e($pendaftar->nama_lengkap); ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Times+New+Roman&display=swap');
        
        body {
            background-color: #f3f4f6;
            font-family: 'Times New Roman', Times, serif; /* Font resmi persuratan */
        }
        
        .a4-container {
            width: 210mm;
            min-height: 297mm;
            background: white;
            margin: 2rem auto;
            padding: 20mm 20mm;
            box-shadow: 0 10px 25px rgba(0,0,0,0.1);
            position: relative;
        }

        /* Menyembunyikan tombol saat di-print */
        @media print {
            body { background: white; margin: 0; padding: 0; }
            .a4-container { box-shadow: none; margin: 0; padding: 10mm; }
            .no-print { display: none !important; }
        }
    </style>
</head>
<body class="text-black">

    
    <div class="text-center mt-8 mb-4 no-print flex justify-center gap-4">
        <a href="<?php echo e(route('dashboard.user')); ?>" class="px-6 py-2.5 bg-gray-500 text-white rounded-lg font-sans font-bold hover:bg-gray-600 transition-colors">Kembali</a>
        <button onclick="window.print()" class="px-6 py-2.5 bg-blue-600 text-white rounded-lg font-sans font-bold hover:bg-blue-700 transition-colors flex items-center gap-2">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
            Cetak / Simpan PDF
        </button>
    </div>

    
    <div class="a4-container">
        
        
        <div class="flex items-center border-b-4 border-black pb-4 mb-8">
            <img src="<?php echo e(asset('images/logo-adzkia.png')); ?>" alt="Logo Universitas" class="w-24 h-auto mr-6">
            <div class="flex-1 text-center">
                <h1 class="text-[22px] font-bold uppercase tracking-wide">Panitia Penerimaan Mahasiswa Baru</h1>
                <h2 class="text-[28px] font-black uppercase text-blue-800 mt-1 mb-1">Universitas Adzkia</h2>
                <p class="text-[13px]">Jl. Raya Taratak Paneh No. 7, Korong Gadang, Kec. Kuranji, Kota Padang</p>
                <p class="text-[13px]">Email: pmb@adzkia.ac.id | Website: pmb.adzkia.ac.id</p>
            </div>
            <div class="w-24"></div> 
        </div>

        
        <div class="text-center mb-8">
            <h3 class="text-[18px] font-bold underline mb-1">SURAT KETERANGAN LULUS SELEKSI</h3>
            <p class="text-[14px]">Nomor: <?php echo e(date('Y')); ?>/SPMB/<?php echo e(substr($pendaftar->no_pendaftaran, -4)); ?></p>
        </div>

        
        <div class="text-[15px] leading-relaxed text-justify mb-8 space-y-4">
            <p>Berdasarkan hasil evaluasi dan penilaian dari Panitia Penerimaan Mahasiswa Baru Universitas Adzkia Tahun Akademik <?php echo e(date('Y')); ?>/<?php echo e(date('Y')+1); ?>, dengan ini Rektor Universitas Adzkia menerangkan bahwa:</p>
            
            <div class="pl-8 mb-4">
                <table class="w-full">
                    <tr>
                        <td class="w-40 py-1">Nama Lengkap</td>
                        <td class="w-4">:</td>
                        <td class="font-bold uppercase"><?php echo e($pendaftar->nama_lengkap); ?></td>
                    </tr>
                    <tr>
                        <td class="py-1">Nomor Registrasi</td>
                        <td>:</td>
                        <td class="font-bold"><?php echo e($pendaftar->no_pendaftaran); ?></td>
                    </tr>
                    <tr>
                        <td class="py-1">Jalur Pendaftaran</td>
                        <td>:</td>
                        <td><?php echo e($pendaftar->jalur_pendaftaran); ?></td>
                    </tr>
                </table>
            </div>

            <p>Dinyatakan <strong>LULUS SELEKSI</strong> dan diterima sebagai Calon Mahasiswa Baru Universitas Adzkia pada:</p>

            <div class="text-center my-6 p-4 border-2 border-black rounded bg-gray-50">
                <p class="text-[14px] mb-1">Program Studi:</p>
                <h3 class="text-[20px] font-bold uppercase">
                    <?php echo e($pendaftar->status_kelulusan == 'Lulus Pilihan 1' ? $pendaftar->pilihan_jurusan_1 : $pendaftar->pilihan_jurusan_2); ?>

                </h3>
            </div>

            <p>Kami mengucapkan selamat atas keberhasilan Saudara/i. Selanjutnya, Saudara/i diwajibkan untuk segera menyelesaikan proses <strong>Daftar Ulang (Registrasi Ulang)</strong> sesuai dengan jadwal dan ketentuan yang tertera pada portal SPMB Universitas Adzkia.</p>
            <p>Apabila Saudara/i tidak melakukan daftar ulang pada batas waktu yang telah ditentukan, maka kelulusan ini dianggap <strong>GUGUR</strong>.</p>
        </div>

        
        <div class="flex justify-end mt-12">
            <div class="text-center w-64">
                <p class="mb-1">Padang, <?php echo e(\Carbon\Carbon::now()->translatedFormat('d F Y')); ?></p>
                <p class="font-bold mb-20">Ketua Panitia SPMB</p>
                
                <p class="font-bold underline">Prof. Dr. Ir. H. Syukri Arief, M.Sc</p>
                <p class="text-[14px]">NIDN. 196609181993031003</p>
            </div>
        </div>

        
        <div class="absolute bottom-10 left-10 right-10 text-center text-[10px] text-gray-400 border-t border-gray-300 pt-2">
            <p>Surat ini digenerate secara otomatis oleh sistem SPMB Universitas Adzkia. Validitas dapat dicek melalui portal resmi.</p>
        </div>

    </div>

</body>
</html><?php /**PATH D:\Database\spmb-adzkia\resources\views/user/cetak-loa.blade.php ENDPATH**/ ?>