<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <title>LoA - {{ $pendaftar->nama_lengkap }}</title>
    <style>
        @page { margin: 22mm 20mm 22mm 20mm; }
        * { box-sizing: border-box; }
        body {
            font-family: "Times New Roman", Times, serif;
            font-size: 12pt;
            color: #000;
            line-height: 1.5;
            margin: 0;
        }

        /* KOP SURAT */
        table.kop { width: 100%; border-bottom: 3px solid #000; padding-bottom: 8px; margin-bottom: 20px; }
        table.kop td { vertical-align: middle; }
        table.kop td.logo { width: 90px; }
        table.kop td.logo img { width: 80px; height: auto; }
        table.kop td.teks { text-align: center; }
        table.kop h1 { font-size: 15pt; margin: 0; text-transform: uppercase; letter-spacing: 1px; }
        table.kop h2 { font-size: 19pt; margin: 2px 0; text-transform: uppercase; color: #1e40af; }
        table.kop p  { font-size: 9.5pt; margin: 1px 0; }

        .judul { text-align: center; margin-bottom: 22px; }
        .judul h3 { font-size: 14pt; text-decoration: underline; margin: 0 0 4px 0; }
        .judul p  { font-size: 11pt; margin: 0; }

        .isi p { text-align: justify; margin: 0 0 12px 0; }

        table.identitas { margin: 6px 0 6px 30px; border-collapse: collapse; }
        table.identitas td { padding: 2px 0; vertical-align: top; }
        table.identitas td.label { width: 160px; }
        table.identitas td.sep   { width: 12px; }
        table.identitas td.val   { font-weight: bold; }

        .prodi-box { text-align: center; border: 2px solid #000; background: #f3f4f6; padding: 12px; margin: 18px 0; }
        .prodi-box .small { font-size: 11pt; margin: 0 0 4px 0; }
        .prodi-box .nama  { font-size: 16pt; font-weight: bold; text-transform: uppercase; margin: 0; }

        table.ttd { width: 100%; margin-top: 28px; }
        table.ttd td.kanan { width: 45%; text-align: center; }
        .spasi-ttd { height: 75px; }

        /* Footer mengulang di setiap halaman */
        .footer-note {
            position: fixed;
            bottom: 8mm; left: 0; right: 0;
            text-align: center;
            font-size: 8pt; color: #888;
            border-top: 1px solid #ccc; padding-top: 4px;
        }
    </style>
</head>
<body>

    <div class="footer-note">
        Surat ini digenerate otomatis oleh sistem SPMB Universitas Adzkia. Validitas dapat dicek melalui portal resmi.
    </div>

    {{-- KOP SURAT --}}
    <table class="kop">
        <tr>
            <td class="logo">
                @if($logoBase64)
                    <img src="{{ $logoBase64 }}" alt="Logo Universitas Adzkia">
                @endif
            </td>
            <td class="teks">
                <h1>Panitia Penerimaan Mahasiswa Baru</h1>
                <h2>Universitas Adzkia</h2>
                <p>Jl. Raya Taratak Paneh No. 7, Korong Gadang, Kec. Kuranji, Kota Padang</p>
                <p>Email: pmb@adzkia.ac.id &nbsp;|&nbsp; Website: pmb.adzkia.ac.id</p>
            </td>
            <td class="logo">&nbsp;</td> {{-- penyeimbang agar teks benar-benar di tengah --}}
        </tr>
    </table>

    {{-- JUDUL --}}
    <div class="judul">
        <h3>SURAT KETERANGAN LULUS SELEKSI</h3>
        <p>Nomor: {{ date('Y') }}/SPMB/{{ substr($pendaftar->no_pendaftaran, -4) }}</p>
    </div>

    {{-- ISI --}}
    <div class="isi">
        <p>Berdasarkan hasil evaluasi dan penilaian Panitia Penerimaan Mahasiswa Baru Universitas Adzkia Tahun Akademik {{ date('Y') }}/{{ date('Y')+1 }}, dengan ini Rektor Universitas Adzkia menerangkan bahwa:</p>

        <table class="identitas">
            <tr>
                <td class="label">Nama Lengkap</td>
                <td class="sep">:</td>
                <td class="val" style="text-transform: uppercase;">{{ $pendaftar->nama_lengkap }}</td>
            </tr>
            <tr>
                <td class="label">Nomor Registrasi</td>
                <td class="sep">:</td>
                <td class="val">{{ $pendaftar->no_pendaftaran }}</td>
            </tr>
            <tr>
                <td class="label">Jalur Pendaftaran</td>
                <td class="sep">:</td>
                <td>{{ $pendaftar->jalur_pendaftaran }}</td>
            </tr>
        </table>

        <p>Dinyatakan <strong>LULUS SELEKSI</strong> dan diterima sebagai Calon Mahasiswa Baru Universitas Adzkia pada:</p>

        <div class="prodi-box">
            <p class="small">Program Studi:</p>
            <p class="nama">{{ $prodiDiterima }}</p>
        </div>

        <p>Kami mengucapkan selamat atas keberhasilan Saudara/i. Selanjutnya, Saudara/i diwajibkan segera menyelesaikan proses <strong>Daftar Ulang (Registrasi Ulang)</strong> sesuai jadwal dan ketentuan yang tertera pada portal SPMB Universitas Adzkia.</p>

        <p>Apabila Saudara/i tidak melakukan daftar ulang pada batas waktu yang ditentukan, maka kelulusan ini dianggap <strong>GUGUR</strong>.</p>
    </div>

    {{-- TTD (pakai tabel, bukan float, agar stabil di dompdf) --}}
    <table class="ttd">
        <tr>
            <td>&nbsp;</td>
            <td class="kanan">
                <p style="margin:0;">Padang, {{ \Carbon\Carbon::now()->translatedFormat('d F Y') }}</p>
                <p style="margin:0; font-weight:bold;">Ketua Panitia SPMB</p>
                <div class="spasi-ttd"></div>
                <p style="margin:0; font-weight:bold; text-decoration: underline;">Prof. Dr. Ir. H. Syukri Arief, M.Sc</p>
                <p style="margin:0;">NIDN. 196609181993031003</p>
            </td>
        </tr>
    </table>

</body>
</html>