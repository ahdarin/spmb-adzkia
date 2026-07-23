<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Berita;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\File;
use Carbon\Carbon;

class BeritaSeeder extends Seeder
{
    /**
     * Download gambar dari URL dan simpan ke public/uploads/berita/
     * Kembalikan nama file, atau null jika gagal.
     */
    private function downloadImage(string $url, string $namaFile): ?string
    {
        try {
            $dir = public_path('uploads/berita');
            if (!File::exists($dir)) {
                File::makeDirectory($dir, 0755, true);
            }

            $path = $dir . '/' . $namaFile;

            // Skip jika file sudah ada
            if (File::exists($path)) {
                return $namaFile;
            }

            $response = Http::timeout(15)->get($url);
            if ($response->successful()) {
                File::put($path, $response->body());
                return $namaFile;
            }
        } catch (\Exception $e) {
            $this->command->warn("Gagal download gambar: {$namaFile} — {$e->getMessage()}");
        }
        return null;
    }

    public function run(): void
    {
        // Pastikan folder ada
        $dir = public_path('uploads/berita');
        if (!File::exists($dir)) {
            File::makeDirectory($dir, 0755, true);
        }

        $beritas = [

            // ── AKADEMIK ────────────────────────────────────────────────
            [
                'judul'     => 'Universitas Adzkia Raih Akreditasi Unggul dari BAN-PT',
                'kategori'  => 'Akademik',
                'ringkasan' => 'Universitas Adzkia berhasil meraih akreditasi Unggul dari Badan Akreditasi Nasional Perguruan Tinggi (BAN-PT), menjadikannya salah satu kampus swasta terbaik di Sumatera Barat.',
                'konten'    => '<p>Universitas Adzkia dengan bangga mengumumkan keberhasilan meraih predikat <b>Akreditasi Unggul</b> dari Badan Akreditasi Nasional Perguruan Tinggi (BAN-PT). Pencapaian ini merupakan hasil kerja keras seluruh sivitas akademika selama beberapa tahun terakhir.</p>
<h2>Penilaian yang Komprehensif</h2>
<p>Tim asesor BAN-PT melakukan visitasi selama tiga hari dan mengevaluasi berbagai aspek, mulai dari kualitas pengajaran, sarana prasarana, penelitian, hingga pengabdian masyarakat.</p>
<h2>Komitmen terhadap Kualitas</h2>
<p>Rektor Universitas Adzkia menyatakan bahwa akreditasi Unggul ini adalah bukti nyata komitmen institusi dalam memberikan pendidikan berkualitas tinggi kepada mahasiswa.</p>',
                'status'    => 'Published',
                'tanggal_publish' => Carbon::now()->subDays(2),
                'img_url'   => 'https://images.unsplash.com/photo-1607237138185-eedd9c632b0b?w=800&q=80',
                'img_file'  => 'akreditasi-unggul.jpg',
            ],
            [
                'judul'     => 'Jadwal Ujian Akhir Semester Genap 2025/2026 Telah Dirilis',
                'kategori'  => 'Akademik',
                'ringkasan' => 'Biro Akademik Universitas Adzkia resmi merilis jadwal Ujian Akhir Semester (UAS) Genap Tahun Akademik 2025/2026. Mahasiswa diharapkan mempersiapkan diri dengan baik.',
                'konten'    => '<p>Biro Akademik Universitas Adzkia telah resmi merilis jadwal <b>Ujian Akhir Semester (UAS) Genap</b> Tahun Akademik 2025/2026. Ujian akan berlangsung mulai tanggal 4 Agustus hingga 16 Agustus 2026.</p>
<h2>Ketentuan Ujian</h2>
<ul>
  <li>Mahasiswa wajib hadir 15 menit sebelum ujian dimulai</li>
  <li>Membawa kartu peserta ujian yang telah ditandatangani</li>
  <li>Berpakaian rapi dan sopan sesuai aturan kampus</li>
  <li>Minimal kehadiran 75% untuk dapat mengikuti UAS</li>
</ul>',
                'status'    => 'Published',
                'tanggal_publish' => Carbon::now()->subDays(5),
                'img_url'   => 'https://images.unsplash.com/photo-1523050854058-8df90110c9f?w=800&q=80',
                'img_file'  => 'jadwal-uas.jpg',
            ],
            [
                'judul'     => 'Program Studi Informatika Membuka Kelas Internasional Tahun 2026',
                'kategori'  => 'Akademik',
                'ringkasan' => 'Program Studi Informatika Universitas Adzkia resmi membuka kelas internasional dengan kurikulum berbasis industri teknologi global mulai semester ganjil 2026/2027.',
                'konten'    => '<p>Program Studi Informatika Universitas Adzkia mengambil langkah strategis dengan membuka <b>Kelas Internasional</b> mulai semester ganjil tahun akademik 2026/2027.</p>
<h2>Keunggulan Kelas Internasional</h2>
<ul>
  <li>Kurikulum berbasis standar IEEE dan ACM</li>
  <li>Pengajaran dalam dwi bahasa (Indonesia & Inggris)</li>
  <li>Kesempatan pertukaran mahasiswa ke universitas mitra di Malaysia dan Singapura</li>
  <li>Sertifikasi internasional yang diakui industri (AWS, Google, Microsoft)</li>
</ul>',
                'status'    => 'Published',
                'tanggal_publish' => Carbon::now()->subDays(10),
                'img_url'   => 'https://images.unsplash.com/photo-1517694712202-14dd9538aa97?w=800&q=80',
                'img_file'  => 'kelas-internasional.jpg',
            ],

            // ── BEASISWA ────────────────────────────────────────────────
            [
                'judul'     => 'Beasiswa Penuh KIP Kuliah 2026 Kini Dibuka untuk Mahasiswa Baru',
                'kategori'  => 'Beasiswa',
                'ringkasan' => 'Universitas Adzkia menerima pendaftar KIP Kuliah 2026 untuk mahasiswa baru. Beasiswa ini menanggung biaya kuliah penuh dan biaya hidup bulanan bagi mahasiswa kurang mampu berprestasi.',
                'konten'    => '<p>Kabar gembira bagi calon mahasiswa baru! Universitas Adzkia resmi menerima pendaftar <b>KIP Kuliah 2026</b> yang diselenggarakan oleh Kementerian Pendidikan, Kebudayaan, Riset, dan Teknologi Republik Indonesia.</p>
<h2>Manfaat KIP Kuliah</h2>
<ul>
  <li>Pembebasan biaya pendaftaran dan uang kuliah penuh</li>
  <li>Bantuan biaya hidup sebesar Rp 700.000 – Rp 1.400.000 per bulan</li>
  <li>Berlaku selama masa studi normal (4 tahun untuk S1)</li>
</ul>
<h2>Cara Mendaftar</h2>
<p>Pendaftaran dilakukan melalui portal resmi KIP Kuliah di <b>kip-kuliah.kemdikbud.go.id</b>, kemudian pilih Universitas Adzkia sebagai perguruan tinggi tujuan. Batas pendaftaran hingga 31 Juli 2026.</p>',
                'status'    => 'Published',
                'tanggal_publish' => Carbon::now()->subDays(3),
                'img_url'   => 'https://images.unsplash.com/photo-1532619187608-e5375cab36aa?w=800&q=80',
                'img_file'  => 'kip-kuliah-2026.jpg',
            ],
            [
                'judul'     => 'Beasiswa Prestasi Adzkia 2026: Potongan SPP hingga 100%',
                'kategori'  => 'Beasiswa',
                'ringkasan' => 'Universitas Adzkia membuka program beasiswa prestasi internal untuk calon mahasiswa baru dengan prestasi akademik dan non-akademik yang unggul. Potongan SPP hingga 100%.',
                'konten'    => '<p>Universitas Adzkia kembali membuka <b>Program Beasiswa Prestasi Adzkia 2026</b> sebagai wujud apresiasi terhadap calon mahasiswa berprestasi.</p>
<h2>Kategori Beasiswa</h2>
<ul>
  <li><b>Beasiswa Platinum</b> — Potongan SPP 100% (gratis kuliah penuh)</li>
  <li><b>Beasiswa Gold</b> — Potongan SPP 75%</li>
  <li><b>Beasiswa Silver</b> — Potongan SPP 50%</li>
</ul>
<p>Daftarkan dirimu sekarang melalui portal SPMB Adzkia dan pilih jalur "Beasiswa Prestasi".</p>',
                'status'    => 'Published',
                'tanggal_publish' => Carbon::now()->subDays(7),
                'img_url'   => 'https://images.unsplash.com/photo-1434030216411-0b793f4b4173?w=800&q=80',
                'img_file'  => 'beasiswa-prestasi.jpg',
            ],

            // ── KEGIATAN ────────────────────────────────────────────────
            [
                'judul'     => 'Ospek Mahasiswa Baru 2026: "AKSELERASI — Berkarakter, Berprestasi, Berdampak"',
                'kategori'  => 'Kegiatan',
                'ringkasan' => 'Universitas Adzkia akan menggelar Orientasi Studi dan Pengenalan Kampus (Ospek) untuk mahasiswa baru angkatan 2026 pada 18–22 Agustus 2026.',
                'konten'    => '<p>Universitas Adzkia akan menyelenggarakan <b>Orientasi Studi dan Pengenalan Kampus (Ospek)</b> Mahasiswa Baru Angkatan 2026 yang mengusung tema <em>"AKSELERASI — Berkarakter, Berprestasi, Berdampak"</em>.</p>
<h2>Jadwal Pelaksanaan</h2>
<ul>
  <li><b>Hari 1 (18 Agustus)</b> — Pembukaan & Pengenalan Civitas Akademika</li>
  <li><b>Hari 2 (19 Agustus)</b> — Pengenalan Sistem Akademik & UKM</li>
  <li><b>Hari 3 (20 Agustus)</b> — Workshop Softskill & Kepemimpinan</li>
  <li><b>Hari 4 (21 Agustus)</b> — Kegiatan per Program Studi</li>
  <li><b>Hari 5 (22 Agustus)</b> — Penutupan & Malam Keakraban</li>
</ul>
<p>Kehadiran Ospek bersifat <b>wajib</b> bagi seluruh mahasiswa baru.</p>',
                'status'    => 'Published',
                'tanggal_publish' => Carbon::now()->subDays(1),
                'img_url'   => 'https://images.unsplash.com/photo-1540575467063-178a50c2df87?w=800&q=80',
                'img_file'  => 'ospek-2026.jpg',
            ],
            [
                'judul'     => 'Seminar Nasional "Teknologi AI dalam Dunia Pendidikan" — Gratis untuk Mahasiswa',
                'kategori'  => 'Kegiatan',
                'ringkasan' => 'Fakultas Ilmu Komputer Universitas Adzkia mengadakan Seminar Nasional bertema kecerdasan buatan dalam pendidikan. Terbuka gratis untuk seluruh mahasiswa Adzkia.',
                'konten'    => '<p>Fakultas Ilmu Komputer Universitas Adzkia menyelenggarakan <b>Seminar Nasional "Teknologi AI dalam Dunia Pendidikan"</b>.</p>
<h2>Detail Acara</h2>
<ul>
  <li><b>Tanggal:</b> Sabtu, 2 Agustus 2026</li>
  <li><b>Waktu:</b> 08.00 – 16.00 WIB</li>
  <li><b>Lokasi:</b> Aula Utama Universitas Adzkia, Lantai 4</li>
  <li><b>HTM:</b> Gratis untuk mahasiswa Adzkia</li>
</ul>
<p>Sertifikat akan diberikan kepada seluruh peserta.</p>',
                'status'    => 'Published',
                'tanggal_publish' => Carbon::now()->subDays(4),
                'img_url'   => 'https://images.unsplash.com/photo-1485827404703-89b55fcc595e?w=800&q=80',
                'img_file'  => 'seminar-ai.jpg',
            ],

            // ── INFORMASI ───────────────────────────────────────────────
            [
                'judul'     => 'Prosedur dan Syarat Pendaftaran Ulang Mahasiswa Baru 2026',
                'kategori'  => 'Informasi',
                'ringkasan' => 'Bagi calon mahasiswa yang telah dinyatakan lulus seleksi, berikut adalah prosedur lengkap pendaftaran ulang beserta dokumen yang perlu disiapkan.',
                'konten'    => '<p>Selamat kepada seluruh calon mahasiswa baru Universitas Adzkia yang telah dinyatakan <b>Lulus Seleksi</b>. Berikut adalah informasi lengkap mengenai prosedur pendaftaran ulang.</p>
<h2>Dokumen yang Diperlukan</h2>
<ul>
  <li>Fotokopi Ijazah/SKL yang telah dilegalisir (3 lembar)</li>
  <li>Fotokopi KTP/Kartu Keluarga (3 lembar)</li>
  <li>Pas foto 3×4 latar merah (5 lembar)</li>
  <li>Surat Keterangan Kesehatan dari dokter/puskesmas</li>
  <li>Bukti pembayaran biaya daftar ulang</li>
</ul>
<h2>Jadwal Daftar Ulang</h2>
<ul>
  <li><b>Gelombang 1:</b> 28 Juli – 5 Agustus 2026</li>
  <li><b>Gelombang 2:</b> 6–12 Agustus 2026</li>
</ul>',
                'status'    => 'Published',
                'tanggal_publish' => Carbon::now()->subDays(6),
                'img_url'   => 'https://images.unsplash.com/photo-1568992688065-536aad8a12f6?w=800&q=80',
                'img_file'  => 'daftar-ulang.jpg',
            ],
            [
                'judul'     => 'Kalender Akademik 2026/2027 Universitas Adzkia Telah Ditetapkan',
                'kategori'  => 'Informasi',
                'ringkasan' => 'Universitas Adzkia telah menetapkan Kalender Akademik Tahun 2026/2027. Mahasiswa dan dosen diharapkan menyesuaikan kegiatan akademik dengan jadwal yang telah ditetapkan.',
                'konten'    => '<p>Rektor Universitas Adzkia telah menetapkan <b>Kalender Akademik Tahun 2026/2027</b>.</p>
<h2>Semester Ganjil 2026/2027</h2>
<ul>
  <li><b>Registrasi Mahasiswa Lama:</b> 25 Juli – 8 Agustus 2026</li>
  <li><b>Ospek Mahasiswa Baru:</b> 18–22 Agustus 2026</li>
  <li><b>Awal Perkuliahan:</b> 25 Agustus 2026</li>
  <li><b>Ujian Tengah Semester:</b> 13–24 Oktober 2026</li>
  <li><b>Ujian Akhir Semester:</b> 1–12 Desember 2026</li>
  <li><b>Libur Semester Ganjil:</b> 15 Desember 2026 – 4 Januari 2027</li>
</ul>',
                'status'    => 'Published',
                'tanggal_publish' => Carbon::now()->subDays(14),
                'img_url'   => 'https://images.unsplash.com/photo-1506784983877-45594efa4cbe?w=800&q=80',
                'img_file'  => 'kalender-akademik.jpg',
            ],
            [
                'judul'     => 'Perpustakaan Digital Adzkia Kini Tersedia 24 Jam Secara Online',
                'kategori'  => 'Informasi',
                'ringkasan' => 'Universitas Adzkia meluncurkan layanan perpustakaan digital yang dapat diakses 24 jam sehari. Ribuan koleksi buku, jurnal, dan skripsi tersedia secara online.',
                'konten'    => '<p>Universitas Adzkia meluncurkan <b>Perpustakaan Digital Adzkia</b> yang dapat diakses kapan saja dan di mana saja.</p>
<h2>Koleksi yang Tersedia</h2>
<ul>
  <li>Lebih dari 15.000 judul e-book</li>
  <li>Akses ke jurnal internasional terindeks Scopus</li>
  <li>Repositori skripsi, tesis, dan disertasi mahasiswa Adzkia</li>
  <li>Modul ajar dan bahan perkuliahan digital</li>
</ul>
<h2>Cara Mengakses</h2>
<ol>
  <li>Kunjungi library.adzkia.ac.id</li>
  <li>Login menggunakan NIM dan password portal akademik</li>
  <li>Nikmati akses tidak terbatas ke seluruh koleksi digital</li>
</ol>',
                'status'    => 'Published',
                'tanggal_publish' => Carbon::now()->subDays(20),
                'img_url'   => 'https://images.unsplash.com/photo-1481627834876-b7833e8f5570?w=800&q=80',
                'img_file'  => 'perpustakaan-digital.jpg',
            ],

            // ── DRAFT ────────────────────────────────────────────────────
            [
                'judul'     => 'Workshop Kewirausahaan Mahasiswa: Membangun Startup dari Nol',
                'kategori'  => 'Kegiatan',
                'ringkasan' => 'UKM Kewirausahaan Universitas Adzkia akan mengadakan workshop intensif membangun startup untuk mahasiswa yang berminat di dunia bisnis dan teknologi.',
                'konten'    => '<p>UKM Kewirausahaan Universitas Adzkia mengundang seluruh mahasiswa untuk bergabung dalam <b>Workshop Intensif Kewirausahaan: Membangun Startup dari Nol</b>.</p>
<h2>Materi Workshop</h2>
<ul>
  <li>Ideasi dan validasi ide bisnis</li>
  <li>Business Model Canvas</li>
  <li>Pitching kepada investor</li>
  <li>Digital marketing untuk startup</li>
</ul>',
                'status'    => 'Draft',
                'tanggal_publish' => Carbon::now()->addDays(7),
                'img_url'   => 'https://images.unsplash.com/photo-1556761175-4b46a572b786?w=800&q=80',
                'img_file'  => 'workshop-startup.jpg',
            ],
        ];

        foreach ($beritas as $data) {
            $slug = Str::slug($data['judul']);

            // Download thumbnail
            $thumbnail = null;
            if (!empty($data['img_url']) && !empty($data['img_file'])) {
                $thumbnail = $this->downloadImage($data['img_url'], $data['img_file']);
            }

            Berita::updateOrCreate(
                ['slug' => $slug],
                [
                    'judul'           => $data['judul'],
                    'kategori'        => $data['kategori'],
                    'slug'            => $slug,
                    'ringkasan'       => $data['ringkasan'],
                    'konten'          => $data['konten'],
                    'status'          => $data['status'],
                    'thumbnail'       => $thumbnail,
                    'tanggal_publish' => $data['tanggal_publish'],
                ]
            );

            $this->command->info("✓ {$data['judul']}" . ($thumbnail ? " [gambar: {$thumbnail}]" : " [tanpa gambar]"));
        }

        $this->command->info('');
        $this->command->info('BeritaSeeder selesai!');
    }
}