<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ujian CBT Seleksi Masuk - SPMB Adzkia</title>
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
                        'brand-success': '#10B981',
                        'brand-warning': '#F59E0B'
                    }
                }
            }
        }
    </script>
    <style>
        [x-cloak] { display: none !important; }
        /* Kustom scrollbar untuk navigasi soal */
        .custom-scrollbar::-webkit-scrollbar { width: 6px; }
        .custom-scrollbar::-webkit-scrollbar-track { background: #F1F5F9; }
        .custom-scrollbar::-webkit-scrollbar-thumb { background: #CBD5E1; border-radius: 4px; }
    </style>
</head>
<body class="bg-brand-bg antialiased text-brand-dark min-h-screen flex flex-col" x-data="cbtApp()" x-init="startTimer()">

    <header class="w-full bg-white border-b border-gray-100 sticky top-0 z-50 px-6 py-4 shadow-sm">
        <div class="max-w-7xl mx-auto flex flex-col sm:flex-row justify-between items-center gap-4">
            <div class="flex items-center gap-3">
                <div class="p-2 bg-brand-blue-light text-brand-blue rounded-xl">
                    <i data-feather="cpu" class="w-6 h-6"></i>
                </div>
                <div>
                    <h1 class="text-lg font-black tracking-tight">CBT System - Universitas Adzkia</h1>
                    <p class="text-xs font-bold text-brand-gray">Ujian Potensi Akademik & Kemampuan Dasar</p>
                </div>
            </div>
            
            <div class="flex items-center gap-6">
                <div class="text-right hidden md:block">
                    <p class="text-xs font-bold text-brand-gray">Peserta ujian:</p>
                    <p class="text-sm font-black text-brand-dark">{{ auth()->user()->name ?? 'Calon Mahasiswa Adzkia' }}</p>
                </div>
                <div class="flex items-center gap-2.5 px-4 py-2.5 bg-red-50 border border-red-100 rounded-2xl text-red-600 shadow-sm">
                    <i data-feather="clock" class="w-4 h-4 animate-pulse"></i>
                    <span class="text-sm font-black tracking-wider" x-text="formatTime()">00:00:00</span>
                </div>
            </div>
        </div>
    </header>

    <form id="examForm" action="/ujian/submit" method="POST" class="flex-1 max-w-7xl mx-auto w-full px-6 py-8 grid grid-cols-1 lg:grid-cols-3 gap-8 items-start">
        @csrf
        
        <div class="lg:col-span-2 bg-white rounded-3xl border border-gray-100 shadow-xl shadow-brand-dark/5 p-6 md:p-8 space-y-6">
            
            <div class="flex justify-between items-center border-b border-gray-100 pb-4">
                <span class="px-4 py-1.5 bg-brand-blue-light text-brand-blue rounded-xl text-xs font-black tracking-wider">
                    SOAL NOMOR <span class="text-sm" x-text="currentIndex + 1">1</span>
                </span>
                <div class="flex items-center gap-2">
                    <button type="button" @click="toggleRagu()" class="flex items-center gap-2 px-3 py-1.5 rounded-xl text-xs font-bold transition-all border"
                            :class="answers[currentSoalId()].ragu ? 'bg-brand-warning text-white border-brand-warning' : 'bg-white text-brand-gray border-gray-200 hover:border-brand-warning hover:text-brand-warning'">
                        <i data-feather="alert-triangle" class="w-3.5 h-3.5"></i> Ragu-Ragu
                    </button>
                </div>
            </div>

            <div class="text-[15px] font-bold text-brand-dark leading-relaxed space-y-4 min-h-[120px]">
                <p x-text="questions[currentIndex].text"></p>
            </div>

            <div class="space-y-3 pt-4">
                <template x-for="(opt, key) in questions[currentIndex].options" :key="key">
                    <label class="flex items-center p-4 border-2 rounded-2xl cursor-pointer transition-all group relative"
                           :class="answers[currentSoalId()].selected === key ? 'border-brand-blue bg-brand-blue-light/30' : 'border-gray-100 hover:border-gray-200 bg-gray-50/50'">
                        
                        <input type="radio" 
                               :name="'answers[' + currentSoalId() + ']'" 
                               :value="key"
                               :checked="answers[currentSoalId()].selected === key"
                               @change="selectAnswer(key)"
                               class="sr-only">
                        
                        <div class="w-9 h-9 rounded-xl flex items-center justify-center border font-black text-sm transition-all"
                             :class="answers[currentSoalId()].selected === key ? 'bg-brand-blue border-brand-blue text-white shadow-md' : 'bg-white border-gray-200 text-brand-gray group-hover:border-brand-blue group-hover:text-brand-blue'">
                            <span x-text="key.toUpperCase()"></span>
                        </div>
                        
                        <span class="ml-4 text-[14px] font-semibold text-brand-dark" x-text="opt"></span>
                    </label>
                </template>
            </div>

            <div class="flex items-center justify-between pt-6 border-t border-gray-100 mt-8">
                <button type="button" @click="prevSoal()" :disabled="currentIndex === 0"
                        :class="currentIndex === 0 ? 'opacity-40 cursor-not-allowed text-gray-400 bg-gray-50 border-gray-100' : 'text-brand-dark bg-white border-gray-200 hover:border-brand-dark'"
                        class="flex items-center gap-2 px-5 py-3 rounded-xl text-xs font-black border uppercase tracking-wider transition-all">
                    <i data-feather="arrow-left" class="w-4 h-4"></i> Sebelumnya
                </button>

                <button type="button" @click="nextSoal()" x-show="currentIndex < questions.length - 1"
                        class="flex items-center gap-2 px-5 py-3 bg-brand-dark text-white hover:bg-brand-blue rounded-xl text-xs font-black uppercase tracking-wider transition-all shadow-md">
                    Selanjutnya <i data-feather="arrow-right" class="w-4 h-4"></i>
                </button>

                <button type="button" @click="confirmSubmit()" x-show="currentIndex === questions.length - 1"
                        class="flex items-center gap-2 px-6 py-3 bg-brand-success text-white hover:opacity-90 rounded-xl text-xs font-black uppercase tracking-wider transition-all shadow-md">
                    <i data-feather="check-square" class="w-4 h-4"></i> Selesai Ujian
                </button>
            </div>
        </div>

        <div class="bg-white rounded-3xl border border-gray-100 shadow-xl shadow-brand-dark/5 p-6 space-y-6 lg:sticky lg:top-28">
            <div>
                <h3 class="text-sm font-black text-brand-dark tracking-tight">Nomor Navigasi Soal</h3>
                <p class="text-xs font-bold text-brand-gray mt-1">Gunakan nomor grid di bawah untuk melompat ke soal tertentu.</p>
            </div>

            <div class="grid grid-cols-4 sm:grid-cols-5 gap-3 max-h-[320px] overflow-y-auto pr-1 custom-scrollbar">
                <template x-for="(q, index) in questions" :key="q.id">
                    <button type="button" @click="jumpToSoal(index)"
                            class="aspect-square rounded-xl flex flex-col items-center justify-center text-xs font-black transition-all border relative"
                            :class="getGridClass(index)">
                        <span x-text="index + 1"></span>
                        <div x-show="answers[q.id].ragu" class="w-2 h-2 bg-brand-warning rounded-full absolute top-1 right-1"></div>
                    </button>
                </template>
            </div>

            <div class="pt-4 border-t border-gray-100 grid grid-cols-2 gap-3 text-[11px] font-bold text-brand-gray">
                <div class="flex items-center gap-2">
                    <div class="w-4 h-4 bg-brand-blue rounded-md border"></div> <span>Posisi Aktif</span>
                </div>
                <div class="flex items-center gap-2">
                    <div class="w-4 h-4 bg-gray-100 border text-brand-dark flex items-center justify-center rounded-md text-[9px] font-black">✓</div> <span>Sudah Dijawab</span>
                </div>
                <div class="flex items-center gap-2">
                    <div class="w-4 h-4 bg-brand-warning rounded-md"></div> <span>Ragu-Ragu</span>
                </div>
                <div class="flex items-center gap-2">
                    <div class="w-4 h-4 bg-white border border-gray-200 rounded-md"></div> <span>Belum Dijawab</span>
                </div>
            </div>
        </div>
    </form>

    <div x-show="showConfirmModal" x-cloak class="fixed inset-0 z-[100] flex items-center justify-center p-4 bg-brand-dark/60 backdrop-blur-sm" x-transition>
        <div class="bg-white p-6 md:p-8 rounded-3xl max-w-md w-full border border-gray-100 shadow-2xl text-center space-y-5">
            <div class="w-16 h-16 bg-yellow-50 text-brand-warning rounded-full flex items-center justify-center mx-auto border border-yellow-100">
                <i data-feather="alert-circle" class="w-8 h-8"></i>
            </div>
            <div>
                <h4 class="text-xl font-black text-brand-dark tracking-tight">Konfirmasi Selesai Ujian?</h4>
                <p class="text-xs font-semibold text-brand-gray mt-2 leading-relaxed">
                    Pastikan semua jawaban telah terisi dengan benar. Anda tidak dapat mengulang atau mengubah jawaban setelah lembar ujian dikirimkan.
                </p>
            </div>
            <div class="bg-gray-50 rounded-2xl p-4 grid grid-cols-2 gap-2 text-xs font-bold text-brand-gray border">
                <div class="text-left border-r px-2">Sudah Dijawab: <span class="text-brand-dark font-black" x-text="countAnswered()">0</span> Soal</div>
                <div class="text-left px-2">Ragu-Ragu: <span class="text-brand-warning font-black" x-text="countRagu()">0</span> Soal</div>
            </div>
            <div class="grid grid-cols-2 gap-3 pt-2">
                <button type="button" @click="showConfirmModal = false" class="py-3.5 bg-gray-100 hover:bg-gray-200 rounded-xl text-xs font-black uppercase tracking-wider transition-all text-brand-gray">
                    Kembali
                </button>
                <button type="button" @click="submitExam()" class="py-3.5 bg-brand-success text-white rounded-xl text-xs font-black uppercase tracking-wider transition-all shadow-md shadow-brand-success/20">
                    Kirim Lembar Ujian
                </button>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('cbtApp', () => ({
                currentIndex: 0,
                timeLeft: 5400, // Durasi dalam detik (contoh: 1 Jam 30 Menit)
                showConfirmModal: false,

                // Bank Data Soal (Nanti variabel ini bisa di-echo langsung dari PHP Controller)
                questions: [
                    {
                        id: 101,
                        text: "Jika semua menteri adalah pembantu presiden, dan sebagian menteri rajin bekerja, maka kesimpulan yang paling tepat adalah...",
                        options: {
                            a: "Semua pembantu presiden pasti rajin bekerja.",
                            b: "Sebagian pembantu presiden rajin bekerja.",
                            c: "Menteri yang tidak rajin bekerja bukan pembantu presiden.",
                            d: "Presiden tidak menyukai menteri yang malas bekerja.",
                            e: "Tidak ada menteri yang malas bekerja."
                        }
                    },
                    {
                        id: 102,
                        text: "Manakah pecahan di bawah ini yang memiliki nilai paling besar di antara pilihan lainnya?",
                        options: {
                            a: "3/4",
                            b: "5/6",
                            c: "7/8",
                            d: "11/12",
                            e: "13/15"
                        }
                    },
                    {
                        id: 103,
                        text: "Lawan kata atau antonim yang paling tepat dari kata 'EKSODUS' adalah...",
                        options: {
                            a: "Imigrasi",
                            b: "Evakuasi",
                            c: "Pemukiman",
                            d: "Retret",
                            e: "Penyusupan"
                        }
                    },
                    {
                        id: 104,
                        text: "Deret angka: 2, 4, 8, 16, 32, ... Angka berapakah yang paling tepat untuk mengisi kelanjutan deret tersebut?",
                        options: {
                            a: "48",
                            b: "54",
                            c: "60",
                            d: "64",
                            e: "128"
                        }
                    }
                ],

                // Penampung State Jawaban Berdasarkan ID Soal
                answers: {},

                init() {
                    // Inisialisasi setiap id soal dengan struktur jawaban kosong
                    this.questions.forEach(q => {
                        this.answers[q.id] = { selected: '', ragu: false };
                    });
                    this.refreshFeather();
                },

                currentSoalId() {
                    return this.questions[this.currentIndex].id;
                },

                selectAnswer(key) {
                    this.answers[this.currentSoalId()].selected = key;
                },

                toggleRagu() {
                    this.answers[this.currentSoalId()].ragu = !this.answers[this.currentSoalId()].ragu;
                },

                nextSoal() {
                    if (this.currentIndex < this.questions.length - 1) {
                        this.currentIndex++;
                        this.refreshFeather();
                    }
                },

                prevSoal() {
                    if (this.currentIndex > 0) {
                        this.currentIndex--;
                        this.refreshFeather();
                    }
                },

                jumpToSoal(index) {
                    this.currentIndex = index;
                    this.refreshFeather();
                },

                // Menentukan class visual grid nomor di sidebar
                getGridClass(index) {
                    let qId = this.questions[index].id;
                    let isCurrent = (this.currentIndex === index);
                    let isAnswered = (this.answers[qId] && this.answers[qId].selected !== '');
                    let isRagu = (this.answers[qId] && this.answers[qId].ragu);

                    if (isCurrent) {
                        return 'bg-brand-blue border-brand-blue text-white shadow-md scale-105';
                    }
                    if (isRagu) {
                        return 'bg-brand-warning border-brand-warning text-white';
                    }
                    if (isAnswered) {
                        return 'bg-gray-100 text-brand-dark border-gray-200 font-extrabold';
                    }
                    return 'bg-white text-brand-gray border-gray-200 hover:border-brand-blue hover:text-brand-blue';
                },

                countAnswered() {
                    return Object.values(this.answers).filter(a => a.selected !== '').length;
                },

                countRagu() {
                    return Object.values(this.answers).filter(a => a.ragu).length;
                },

                startTimer() {
                    setInterval(() => {
                        if (this.timeLeft > 0) {
                            this.timeLeft--;
                        } else {
                            // Otomatis submit jika waktu habis
                            this.submitExam();
                        }
                    }, 1000);
                },

                formatTime() {
                    let hours = Math.floor(this.timeLeft / 3600);
                    let minutes = Math.floor((this.timeLeft % 3600) / 60);
                    let seconds = this.timeLeft % 60;
                    return [hours, minutes, seconds].map(v => v < 10 ? '0' + v : v).join(':');
                },

                confirmSubmit() {
                    this.showConfirmModal = true;
                    this.$nextTick(() => { feather.replace(); });
                },

                submitExam() {
                    // Kirim form secara native ke Laravel route handler
                    document.getElementById('examForm').submit();
                },

                refreshFeather() {
                    this.$nextTick(() => { if (window.feather) feather.replace(); });
                }
            }));
        });

        document.addEventListener('DOMContentLoaded', () => { feather.replace(); });
    </script>
</body>
</html>