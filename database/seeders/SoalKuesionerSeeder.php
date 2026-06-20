<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\SoalKuesioner;

class SoalKuesionerSeeder extends Seeder
{
    public function run(): void
    {
        $soals = [
            ['kategori' => 'logika', 'pertanyaan' => 'Saya menikmati aktivitas yang mengharuskan mencari pola atau hubungan antar informasi.'],
            ['kategori' => 'logika', 'pertanyaan' => 'Saya mampu menemukan penyebab suatu masalah melalui analisis yang sistematis.'],
            ['kategori' => 'logika', 'pertanyaan' => 'Saya lebih percaya pada fakta dan data daripada dugaan ketika mengambil keputusan.'],
            ['kategori' => 'logika', 'pertanyaan' => 'Saya senang memecahkan soal yang membutuhkan penalaran logis.'],
            ['kategori' => 'logika', 'pertanyaan' => 'Saya terbiasa membandingkan beberapa alternatif sebelum menentukan solusi terbaik.'],
            
            ['kategori' => 'sosial', 'pertanyaan' => 'Saya mudah bekerja sama dengan orang yang memiliki pendapat berbeda.'],
            ['kategori' => 'sosial', 'pertanyaan' => 'Saya merasa nyaman ketika harus berinteraksi dengan banyak orang baru.'],
            ['kategori' => 'sosial', 'pertanyaan' => 'Saya sering menjadi tempat teman berdiskusi atau meminta bantuan.'],
            ['kategori' => 'sosial', 'pertanyaan' => 'Saya dapat menyesuaikan diri dengan lingkungan sosial yang baru.'],
            ['kategori' => 'sosial', 'pertanyaan' => 'Saya menikmati kegiatan yang melibatkan kerja tim.'],

            ['kategori' => 'kreatif', 'pertanyaan' => 'Saya sering menemukan cara baru untuk menyelesaikan suatu tugas.'],
            ['kategori' => 'kreatif', 'pertanyaan' => 'Saya senang mengembangkan ide yang berbeda dari kebanyakan orang.'],
            ['kategori' => 'kreatif', 'pertanyaan' => 'Saya menikmati kegiatan yang memberi kebebasan untuk berkreasi.'],
            ['kategori' => 'kreatif', 'pertanyaan' => 'Saya tertarik mencoba pendekatan baru meskipun belum pernah dilakukan sebelumnya.'],
            ['kategori' => 'kreatif', 'pertanyaan' => 'Saya sering memiliki banyak alternatif solusi untuk satu masalah.'],

            ['kategori' => 'bisnis', 'pertanyaan' => 'Saya tertarik mempelajari cara memperoleh keuntungan dari suatu produk atau jasa.'],
            ['kategori' => 'bisnis', 'pertanyaan' => 'Saya sering memperhatikan peluang usaha di sekitar saya.'],
            ['kategori' => 'bisnis', 'pertanyaan' => 'Saya tertarik mempelajari strategi pemasaran dan pengembangan bisnis.'],
            ['kategori' => 'bisnis', 'pertanyaan' => 'Saya senang membuat perencanaan untuk mencapai target tertentu.'],
            ['kategori' => 'bisnis', 'pertanyaan' => 'Saya mempertimbangkan aspek biaya dan manfaat sebelum mengambil keputusan.'],

            ['kategori' => 'sains', 'pertanyaan' => 'Saya tertarik mengetahui alasan ilmiah di balik suatu fenomena.'],
            ['kategori' => 'sains', 'pertanyaan' => 'Saya menikmati kegiatan eksperimen atau pengamatan untuk memperoleh informasi.'],
            ['kategori' => 'sains', 'pertanyaan' => 'Saya tertarik membaca atau menonton materi tentang perkembangan ilmu pengetahuan.'],
            ['kategori' => 'sains', 'pertanyaan' => 'Saya senang mempelajari bagaimana sesuatu bekerja secara ilmiah.'],
            ['kategori' => 'sains', 'pertanyaan' => 'Saya tertarik pada mata pelajaran yang berkaitan dengan sains dan teknologi.'],

            ['kategori' => 'komunikatif', 'pertanyaan' => 'Saya dapat menjelaskan ide saya kepada orang lain dengan jelas.'],
            ['kategori' => 'komunikatif', 'pertanyaan' => 'Saya merasa percaya diri saat berbicara di depan banyak orang.'],
            ['kategori' => 'komunikatif', 'pertanyaan' => 'Saya mampu menyampaikan pendapat tanpa menimbulkan kesalahpahaman.'],
            ['kategori' => 'komunikatif', 'pertanyaan' => 'Saya mudah memulai percakapan dengan orang lain.'],
            ['kategori' => 'komunikatif', 'pertanyaan' => 'Saya dapat menyesuaikan cara berbicara sesuai dengan lawan bicara.'],

            ['kategori' => 'teliti', 'pertanyaan' => 'Saya terbiasa memeriksa kembali pekerjaan sebelum dikumpulkan.'],
            ['kategori' => 'teliti', 'pertanyaan' => 'Saya memperhatikan detail kecil yang sering diabaikan orang lain.'],
            ['kategori' => 'teliti', 'pertanyaan' => 'Saya berusaha menghindari kesalahan dengan mengikuti prosedur yang benar.'],
            ['kategori' => 'teliti', 'pertanyaan' => 'Saya menyelesaikan tugas sesuai aturan yang telah ditetapkan.'],
            ['kategori' => 'teliti', 'pertanyaan' => 'Saya merasa tidak nyaman jika pekerjaan saya masih memiliki kesalahan yang belum diperiksa.'],

            ['kategori' => 'empati', 'pertanyaan' => 'Saya berusaha memahami perasaan orang lain sebelum menilai tindakan mereka.'],
            ['kategori' => 'empati', 'pertanyaan' => 'Saya merasa prihatin ketika melihat seseorang mengalami kesulitan.'],
            ['kategori' => 'empati', 'pertanyaan' => 'Saya mempertimbangkan sudut pandang orang lain saat terjadi perbedaan pendapat.'],
            ['kategori' => 'empati', 'pertanyaan' => 'Saya bersedia membantu orang lain meskipun tidak diminta secara langsung.'],
            ['kategori' => 'empati', 'pertanyaan' => 'Saya dapat merasakan ketika seseorang sedang mengalami masalah emosional.'],

            ['kategori' => 'kepemimpinan', 'pertanyaan' => 'Saya bersedia mengambil tanggung jawab ketika bekerja dalam kelompok.'],
            ['kategori' => 'kepemimpinan', 'pertanyaan' => 'Saya mampu mengarahkan anggota tim untuk mencapai tujuan bersama.'],
            ['kategori' => 'kepemimpinan', 'pertanyaan' => 'Saya dapat mengambil keputusan ketika menghadapi situasi yang tidak pasti.'],
            ['kategori' => 'kepemimpinan', 'pertanyaan' => 'Saya sering menjadi penggerak dalam kegiatan kelompok atau organisasi.'],
            ['kategori' => 'kepemimpinan', 'pertanyaan' => 'Saya mampu membagi tugas sesuai kemampuan anggota tim.'],
        ];

        foreach ($soals as $soal) {
            SoalKuesioner::create($soal);
        }
    }
}