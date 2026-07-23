<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Faq;

class FaqSeeder extends Seeder
{
    public function run(): void
    {
        $faqs = [

            // ══ DASHBOARD UTAMA ═════════════════════════════════════════
            [
                'kategori'   => 'Dashboard Utama',
                'pertanyaan' => 'Apa itu SPMB Adzkia dan bagaimana cara mendaftar?',
                'jawaban'    => 'SPMB (Seleksi Penerimaan Mahasiswa Baru) Adzkia adalah sistem pendaftaran online resmi Universitas Adzkia. Untuk mendaftar, klik tombol "Daftar Sekarang" di halaman utama, isi formulir pendaftaran, lalu lakukan pembayaran biaya pendaftaran. Setelah itu, Anda akan mendapatkan nomor pendaftaran dan dapat memantau status seleksi secara online.',
            ],
            [
                'kategori'   => 'Dashboard Utama',
                'pertanyaan' => 'Kapan batas akhir pendaftaran mahasiswa baru 2026?',
                'jawaban'    => 'Pendaftaran mahasiswa baru Universitas Adzkia Tahun Akademik 2026/2027 dibuka dalam beberapa gelombang. Gelombang 1 ditutup 31 Mei 2026, Gelombang 2 ditutup 31 Juli 2026, dan Gelombang 3 (jika kuota masih tersedia) ditutup 15 Agustus 2026. Disarankan mendaftar di gelombang awal karena biaya pendaftaran dan kemungkinan mendapat beasiswa lebih besar.',
            ],
            [
                'kategori'   => 'Dashboard Utama',
                'pertanyaan' => 'Program studi apa saja yang tersedia di Universitas Adzkia?',
                'jawaban'    => 'Universitas Adzkia menyediakan berbagai program studi jenjang S1, D3, S2, dan Profesi, antara lain: Pendidikan Guru SD (PGSD), Pendidikan Guru PAUD, Informatika, Sistem Informasi, Teknik Industri, Gizi, Hukum Bisnis, Agribisnis, dan banyak lagi. Informasi lengkap tersedia di halaman Program Studi.',
            ],
            [
                'kategori'   => 'Dashboard Utama',
                'pertanyaan' => 'Apakah Universitas Adzkia sudah terakreditasi?',
                'jawaban'    => 'Ya, Universitas Adzkia telah mendapatkan akreditasi dari BAN-PT (Badan Akreditasi Nasional Perguruan Tinggi) dengan predikat Unggul. Sebagian besar program studi kami juga telah terakreditasi dengan peringkat A atau Unggul dari BAN-PT maupun LAMDIK.',
            ],
            [
                'kategori'   => 'Dashboard Utama',
                'pertanyaan' => 'Berapa biaya kuliah di Universitas Adzkia per semester?',
                'jawaban'    => 'Biaya kuliah di Universitas Adzkia bervariasi tergantung program studi yang dipilih. Secara umum, biaya SPP berkisar antara Rp 2.500.000 – Rp 5.000.000 per semester. Terdapat juga program cicilan dan berbagai pilihan beasiswa yang dapat mengurangi biaya tersebut. Informasi biaya lengkap per prodi dapat ditanyakan langsung ke Biro Keuangan atau saat konsultasi pra-pendaftaran.',
            ],

            // ══ PENDAFTARAN ═════════════════════════════════════════════
            [
                'kategori'   => 'Pendaftaran',
                'pertanyaan' => 'Apa saja persyaratan untuk mendaftar ke Universitas Adzkia?',
                'jawaban'    => 'Persyaratan umum pendaftaran S1: (1) Lulusan SMA/SMK/MA sederajat atau yang setara, (2) Usia maksimal 25 tahun saat mendaftar, (3) Memiliki ijazah atau Surat Keterangan Lulus (SKL), (4) Foto berwarna terbaru, (5) Fotokopi KTP/KK. Untuk program D3 dan S2 terdapat persyaratan tambahan yang dapat dilihat di halaman masing-masing program studi.',
            ],
            [
                'kategori'   => 'Pendaftaran',
                'pertanyaan' => 'Bagaimana cara memilih program studi saat mendaftar?',
                'jawaban'    => 'Saat mengisi formulir pendaftaran online, Anda dapat memilih hingga 2 (dua) pilihan program studi sesuai minat dan kemampuan. Pilihan pertama adalah program studi utama, sedangkan pilihan kedua adalah alternatif. Jika tidak lolos seleksi di pilihan pertama, Anda akan dipertimbangkan untuk pilihan kedua.',
            ],
            [
                'kategori'   => 'Pendaftaran',
                'pertanyaan' => 'Bisakah saya mengubah pilihan program studi setelah mendaftar?',
                'jawaban'    => 'Perubahan pilihan program studi hanya dapat dilakukan maksimal 7 hari setelah tanggal pendaftaran dan sebelum proses seleksi dimulai. Untuk melakukan perubahan, hubungi Biro Penerimaan Mahasiswa Baru melalui WhatsApp atau datang langsung ke kampus dengan membawa kartu pendaftaran.',
            ],
            [
                'kategori'   => 'Pendaftaran',
                'pertanyaan' => 'Apakah ada tes seleksi masuk Universitas Adzkia?',
                'jawaban'    => 'Tergantung jalur pendaftaran yang dipilih. Jalur Reguler menggunakan seleksi berkas (nilai rapor dan ijazah). Jalur Prestasi menggunakan bukti prestasi akademik dan non-akademik. Jalur Tes mengharuskan peserta mengikuti ujian tertulis yang meliputi Tes Potensi Akademik (TPA) dan tes bidang studi. Peserta dapat memilih jalur yang paling sesuai saat mendaftar.',
            ],
            [
                'kategori'   => 'Pendaftaran',
                'pertanyaan' => 'Berapa lama proses seleksi berlangsung?',
                'jawaban'    => 'Proses seleksi berlangsung sekitar 7–14 hari kerja setelah berkas dinyatakan lengkap. Hasil seleksi akan diumumkan melalui portal SPMB Adzkia dan dikirimkan via email yang didaftarkan. Anda dapat memantau status pendaftaran secara real-time melalui akun SPMB Anda.',
            ],

            // ══ PEMBAYARAN ══════════════════════════════════════════════
            [
                'kategori'   => 'Pembayaran',
                'pertanyaan' => 'Berapa biaya pendaftaran dan bagaimana cara membayarnya?',
                'jawaban'    => 'Biaya pendaftaran adalah Rp 250.000 (dua ratus lima puluh ribu rupiah) dan tidak dapat dikembalikan. Pembayaran dapat dilakukan melalui: (1) Transfer bank ke rekening resmi Universitas Adzkia, (2) Pembayaran melalui minimarket (Indomaret/Alfamart) dengan kode bayar, atau (3) Dompet digital (OVO, GoPay, Dana). Bukti pembayaran wajib diunggah ke portal SPMB.',
            ],
            [
                'kategori'   => 'Pembayaran',
                'pertanyaan' => 'Apakah biaya pendaftaran bisa dikembalikan jika saya tidak jadi melanjutkan?',
                'jawaban'    => 'Biaya pendaftaran bersifat tidak dapat dikembalikan (non-refundable) dalam kondisi apapun, termasuk jika calon mahasiswa mengundurkan diri atau tidak lulus seleksi. Namun, biaya daftar ulang (setelah dinyatakan lulus) dapat dikembalikan sebagian jika pembatalan dilakukan sebelum batas waktu yang ditentukan.',
            ],
            [
                'kategori'   => 'Pembayaran',
                'pertanyaan' => 'Kapan batas waktu pembayaran setelah dinyatakan lulus seleksi?',
                'jawaban'    => 'Setelah dinyatakan lulus seleksi, calon mahasiswa memiliki waktu 7 hari kalender untuk melakukan pembayaran biaya daftar ulang. Jika melewati batas waktu tersebut tanpa konfirmasi, kursi akan otomatis gugur dan diberikan kepada peserta cadangan. Jika ada kendala, segera hubungi Biro Keuangan untuk perpanjangan.',
            ],
            [
                'kategori'   => 'Pembayaran',
                'pertanyaan' => 'Apakah ada cicilan untuk biaya daftar ulang?',
                'jawaban'    => 'Ya, Universitas Adzkia menyediakan skema cicilan untuk biaya daftar ulang bagi mahasiswa yang membutuhkan. Cicilan dapat dilakukan dalam 2–3 tahap sesuai kesepakatan dengan Biro Keuangan. Untuk mengajukan cicilan, hubungi Biro Keuangan dengan membawa surat permohonan dan bukti kondisi keuangan keluarga.',
            ],

            // ══ BEASISWA ════════════════════════════════════════════════
            [
                'kategori'   => 'Beasiswa',
                'pertanyaan' => 'Beasiswa apa saja yang tersedia di Universitas Adzkia?',
                'jawaban'    => 'Universitas Adzkia menyediakan berbagai program beasiswa: (1) KIP Kuliah dari pemerintah — menanggung biaya kuliah penuh + biaya hidup, (2) Beasiswa Prestasi Adzkia — potongan SPP 50–100% berdasarkan nilai akademik, (3) Beasiswa Hafidz Quran — bagi mahasiswa penghafal Al-Quran minimal 10 juz, (4) Beasiswa Afirmasi Daerah — untuk mahasiswa dari daerah 3T. Informasi lengkap di halaman Beasiswa.',
            ],
            [
                'kategori'   => 'Beasiswa',
                'pertanyaan' => 'Bagaimana cara mendapatkan beasiswa KIP Kuliah di Universitas Adzkia?',
                'jawaban'    => 'Untuk mendapatkan KIP Kuliah: (1) Daftar di portal kip-kuliah.kemdikbud.go.id menggunakan NIK dan NISN, (2) Pilih Universitas Adzkia sebagai perguruan tinggi tujuan, (3) Ikuti proses seleksi yang dilakukan oleh pihak Kemdikbud, (4) Jika lolos, konfirmasi ke Biro Akademik Adzkia dengan membawa surat penetapan KIP Kuliah. Pastikan Anda memenuhi kriteria ekonomi yang ditetapkan.',
            ],
            [
                'kategori'   => 'Beasiswa',
                'pertanyaan' => 'Apakah mahasiswa yang sudah kuliah bisa mengajukan beasiswa?',
                'jawaban'    => 'Ya, mahasiswa aktif dapat mengajukan beasiswa prestasi internal setiap semester dengan syarat IPK minimal 3.50 dan tidak memiliki nilai E. Selain itu, tersedia juga beasiswa eksternal seperti Bidikmisi, beasiswa perusahaan, dan lembaga filantropi yang dapat diakses melalui koordinasi dengan Kemahasiswaan Universitas Adzkia.',
            ],

            // ══ KAMPUS & FASILITAS ══════════════════════════════════════
            [
                'kategori'   => 'Kampus & Fasilitas',
                'pertanyaan' => 'Di mana lokasi kampus Universitas Adzkia?',
                'jawaban'    => 'Universitas Adzkia berlokasi di Padang, Sumatera Barat. Kampus utama mudah dijangkau dengan kendaraan pribadi maupun transportasi umum. Untuk alamat lengkap dan petunjuk arah, kunjungi halaman Kontak di website kami atau gunakan Google Maps dengan kata kunci "Universitas Adzkia Padang".',
            ],
            [
                'kategori'   => 'Kampus & Fasilitas',
                'pertanyaan' => 'Fasilitas apa saja yang tersedia di kampus Adzkia?',
                'jawaban'    => 'Universitas Adzkia menyediakan fasilitas lengkap meliputi: Perpustakaan digital dengan koleksi 15.000+ e-book, laboratorium komputer dan sains modern, aula serbaguna berkapasitas 500 orang, kantin dan kafetaria, area wifi kampus, masjid kampus, lapangan olahraga, klinik kesehatan mahasiswa, dan pusat karir & kewirausahaan. Semua fasilitas dapat diakses oleh mahasiswa aktif.',
            ],
            [
                'kategori'   => 'Kampus & Fasilitas',
                'pertanyaan' => 'Apakah ada asrama mahasiswa di Universitas Adzkia?',
                'jawaban'    => 'Saat ini Universitas Adzkia belum memiliki asrama kampus. Namun, kami bekerja sama dengan beberapa pengelola kos dan apartemen di sekitar kampus yang menawarkan harga khusus untuk mahasiswa Adzkia. Informasi kos rekanan dapat diperoleh melalui Biro Kemahasiswaan.',
            ],

            // ══ KONTAK & LAINNYA ════════════════════════════════════════
            [
                'kategori'   => 'Kontak & Lainnya',
                'pertanyaan' => 'Bagaimana cara menghubungi tim SPMB Adzkia?',
                'jawaban'    => 'Anda dapat menghubungi tim SPMB Adzkia melalui: (1) WhatsApp resmi di nomor yang tertera di halaman Kontak, tersedia Senin–Jumat 08.00–16.00 WIB, (2) Email ke pmb@adzkia.ac.id, (3) Datang langsung ke kampus di gedung BAAK Lantai 1, (4) Ikuti akun Instagram @spmb.adzkia untuk informasi terbaru. Respons WhatsApp biasanya dalam 1–2 jam pada hari kerja.',
            ],
            [
                'kategori'   => 'Kontak & Lainnya',
                'pertanyaan' => 'Apakah ada konsultasi gratis sebelum mendaftar?',
                'jawaban'    => 'Ya! Universitas Adzkia menyediakan layanan konsultasi gratis untuk calon mahasiswa dan orang tua. Konsultasi dapat dilakukan secara online via WhatsApp, video call Zoom (dengan perjanjian), atau tatap muka langsung di kampus. Tim konselor kami siap membantu Anda memilih program studi yang tepat sesuai minat dan kemampuan.',
            ],
            [
                'kategori'   => 'Kontak & Lainnya',
                'pertanyaan' => 'Saya lupa password akun SPMB, bagaimana cara mengatasinya?',
                'jawaban'    => 'Jika lupa password, klik "Lupa Password?" di halaman login SPMB. Masukkan email yang terdaftar, dan tautan reset password akan dikirim ke email tersebut. Jika email tidak lagi aktif atau tidak menerima email reset, hubungi tim IT SPMB melalui WhatsApp resmi dengan menyertakan nomor pendaftaran dan foto KTP untuk verifikasi identitas.',
            ],
            [
                'kategori'   => 'Kontak & Lainnya',
                'pertanyaan' => 'Apakah mahasiswa pindahan dari universitas lain bisa mendaftar?',
                'jawaban'    => 'Ya, Universitas Adzkia menerima mahasiswa pindahan (transfer) dari perguruan tinggi lain yang terakreditasi. Syarat utamanya adalah: (1) Surat keterangan pindah dari PT asal, (2) Transkrip nilai resmi, (3) Surat persetujuan program studi tujuan di Adzkia. Konversi mata kuliah akan ditentukan setelah evaluasi transkrip oleh koordinator program studi.',
            ],
        ];

        foreach ($faqs as $faq) {
            Faq::updateOrCreate(
                ['pertanyaan' => $faq['pertanyaan']],
                $faq
            );
        }
    }
}
