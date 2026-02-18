<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Dokter | Manajemen RS</title>

    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

    <script>
        const BASE_URL = "<?= base_url() ?>";
    </script>

    <style>
        @keyframes wiggle {

            0%,
            100% {
                transform: rotate(0deg);
            }

            25% {
                transform: rotate(-15deg);
            }

            75% {
                transform: rotate(15deg);
            }
        }

        .group:hover .group-hover\:animate-wiggle {
            animation: wiggle 0.5s ease-in-out infinite;
        }

        /* Cursor wait saat memanggil */
        .cursor-wait {
            cursor: wait;
        }
    </style>
</head>

<body class="bg-gray-900 text-gray-100 font-sans antialiased">

    <div class="min-h-screen flex flex-col">

        <nav class="bg-gray-800 border-b border-gray-700 px-6 py-4 flex justify-between items-center sticky top-0 z-50 shadow-lg">
            <div class="flex items-center gap-3">
                <div class="bg-blue-600 p-2 rounded-lg">
                    <i class="fas fa-hospital-user text-white text-xl"></i>
                </div>
                <div>
                    <h1 class="text-xl font-bold tracking-tight leading-none">RS <span class="text-blue-500">Rawat Jalan</span></h1>
                    <p class="text-xs text-gray-400">Panel Dokter</p>
                </div>
            </div>

            <div class="flex items-center gap-6">
                <div class="text-right hidden md:block">
                    <p class="text-sm font-semibold text-white"><?= session()->get('full_name') ?></p>
                    <p class="text-xs text-blue-400">Spesialis Dokter</p>
                </div>

                <div class="h-10 w-10 rounded-full bg-gray-700 flex items-center justify-center border-2 border-blue-500 shadow-blue-500/20 shadow-lg">
                    <i class="fas fa-user-md text-blue-300"></i>
                </div>

                <a href="<?= base_url('logout') ?>"
                    onclick="return confirm('Yakin ingin logout?')"
                    class="bg-red-600/10 hover:bg-red-600 text-red-500 hover:text-white px-4 py-2 rounded-lg text-sm font-semibold transition duration-300 border border-red-600/30 hover:border-red-600">
                    <i class="fas fa-sign-out-alt mr-2"></i> Keluar
                </a>
            </div>
        </nav>

        <main class="flex-grow p-6 lg:p-8">
            <div class="max-w-7xl mx-auto">

                <?php if (session()->getFlashdata('success')) : ?>
                    <div class="mb-6 p-4 bg-green-900/20 border border-green-500/50 text-green-400 rounded-xl flex items-center gap-3 shadow-lg animate-fade-in-down">
                        <i class="fas fa-check-circle text-xl"></i>
                        <div>
                            <p class="font-bold">Berhasil!</p>
                            <p class="text-sm opacity-90"><?= session()->getFlashdata('success') ?></p>
                        </div>
                    </div>
                <?php endif; ?>

                <div class="flex flex-col md:flex-row md:items-end justify-between mb-8 gap-6">
                    <div>
                        <h2 class="text-3xl font-bold text-white mb-2">Antrean Pasien</h2>
                        <div class="flex items-center gap-2 text-gray-400 text-sm">
                            <i class="far fa-calendar-alt"></i>
                            <span><?= date('l, d F Y') ?></span>
                        </div>
                    </div>

                    <div class="bg-gray-800 p-4 rounded-xl border border-gray-700 flex items-center gap-4 shadow-xl">
                        <div class="p-3 bg-blue-500/10 rounded-lg text-blue-400">
                            <i class="fas fa-users text-2xl"></i>
                        </div>
                        <div>
                            <p class="text-xs text-gray-400 uppercase font-bold tracking-wider">Total Menunggu</p>
                            <p class="text-2xl font-bold text-white"><?= count($patients) ?></p>
                        </div>
                    </div>
                </div>

                <div class="bg-gray-800 rounded-2xl shadow-2xl border border-gray-700 overflow-hidden">

                    <div class="p-6 border-b border-gray-700 flex justify-between items-center bg-gray-800/80 backdrop-blur-sm">
                        <h3 class="font-bold text-lg text-blue-400 flex items-center gap-2">
                            <i class="fas fa-clipboard-list"></i> Daftar Tunggu Poliklinik
                        </h3>
                        <button onclick="location.reload()" class="text-gray-400 hover:text-white transition flex items-center gap-2 text-sm font-medium hover:bg-gray-700 px-3 py-1.5 rounded-lg">
                            <i class="fas fa-sync-alt"></i> Refresh Data
                        </button>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse">
                            <thead>
                                <tr class="bg-gray-700/30 text-gray-400 text-xs uppercase tracking-wider border-b border-gray-700">
                                    <th class="px-6 py-4 font-bold">No. Urut</th>
                                    <th class="px-6 py-4 font-bold">Informasi Pasien</th>
                                    <th class="px-6 py-4 font-bold">Waktu Daftar</th>
                                    <th class="px-6 py-4 font-bold">Status</th>
                                    <th class="px-6 py-4 font-bold text-right">Aksi Dokter</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-700">
                                <?php if (!empty($patients)) : ?>
                                    <?php foreach ($patients as $p) : ?>
                                        <?php $nomorBersih = str_pad($p['queue_number'], 3, '0', STR_PAD_LEFT); ?>

                                        <tr class="hover:bg-gray-700/30 transition-colors group">
                                            <td class="px-6 py-5">
                                                <span class="inline-block bg-blue-900/30 text-blue-400 px-3 py-1.5 rounded-lg font-mono font-bold text-xl border border-blue-500/30 shadow-[0_0_10px_rgba(59,130,246,0.1)]">
                                                    <?= $nomorBersih; ?>
                                                </span>
                                            </td>

                                            <td class="px-6 py-5">
                                                <p class="text-white font-bold text-lg"><?= esc($p['patient_name']); ?></p>
                                                <div class="flex items-center gap-2 mt-1">
                                                    <span class="px-2 py-0.5 rounded text-[10px] bg-gray-700 text-gray-300 border border-gray-600">
                                                        #<?= $p['appointment_id']; ?>
                                                    </span>
                                                </div>
                                            </td>

                                            <td class="px-6 py-5">
                                                <div class="flex items-center gap-2 text-gray-300">
                                                    <i class="far fa-clock text-gray-500"></i>
                                                    <span class="font-mono text-sm"><?= date('H:i', strtotime($p['created_at'])); ?></span>
                                                </div>
                                            </td>

                                            <td class="px-6 py-5">
                                                <span class="inline-flex items-center gap-1.5 bg-yellow-500/10 text-yellow-500 px-3 py-1 rounded-full text-xs font-bold border border-yellow-500/20">
                                                    <span class="h-1.5 w-1.5 rounded-full bg-yellow-500 animate-pulse"></span>
                                                    Menunggu
                                                </span>
                                            </td>

                                            <td class="px-6 py-5 text-right">
                                                <div class="flex justify-end items-center gap-2">

                                                    <button type="button"
                                                        onclick="panggilPasien('<?= $nomorBersih ?>', this)"
                                                        class="group relative inline-flex items-center justify-center gap-2 bg-indigo-600 hover:bg-indigo-500 text-white px-4 py-2 rounded-lg font-semibold transition-all shadow-lg shadow-indigo-900/20 active:scale-95 border border-indigo-500/50"
                                                        title="Putar Audio Panggilan">
                                                        <i class="fas fa-bullhorn text-sm group-hover:animate-wiggle"></i>
                                                        <span class="btn-text">Panggil</span>

                                                        <span class="btn-loader hidden absolute inset-0 flex items-center justify-center bg-indigo-700 rounded-lg">
                                                            <i class="fas fa-wave-square animate-pulse"></i>
                                                        </span>
                                                    </button>

                                                    <a href="<?= base_url('dokter/examine/' . $p['appointment_id']) ?>"
                                                        class="inline-flex items-center gap-2 bg-emerald-600 hover:bg-emerald-500 text-white px-4 py-2 rounded-lg font-semibold transition-all shadow-lg shadow-emerald-900/20 active:scale-95 border border-emerald-500/50">
                                                        <i class="fas fa-stethoscope text-sm"></i>
                                                        <span>Periksa</span>
                                                    </a>

                                                </div>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php else : ?>
                                    <tr>
                                        <td colspan="5" class="px-6 py-24 text-center">
                                            <div class="flex flex-col items-center justify-center opacity-50">
                                                <div class="bg-gray-800 p-6 rounded-full mb-4">
                                                    <i class="fas fa-mug-hot text-4xl text-gray-500"></i>
                                                </div>
                                                <p class="text-xl font-bold text-gray-400">Tidak ada antrean saat ini</p>
                                                <p class="text-sm text-gray-500 mt-1">Silakan istirahat sejenak, Dok.</p>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="mt-6 flex items-start gap-3 p-4 bg-blue-900/20 border border-blue-800 rounded-lg text-sm text-blue-300">
                    <i class="fas fa-info-circle mt-0.5"></i>
                    <div>
                        <p class="font-bold mb-1">Panduan Penggunaan:</p>
                        <ul class="list-disc list-inside space-y-1 opacity-80">
                            <li>Klik tombol <strong class="text-indigo-400">Panggil</strong> untuk memanggil pasien melalui pengeras suara (Audio).</li>
                            <li>Klik tombol <strong class="text-emerald-400">Periksa</strong> untuk mulai memasukkan rekam medis pasien.</li>
                        </ul>
                    </div>
                </div>

            </div>
        </main>

        <footer class="bg-gray-800 border-t border-gray-700 py-6 px-6 text-center">
            <p class="text-sm text-gray-500">&copy; 2026 RS Rawat Jalan System. Dibuat dengan <i class="fas fa-heart text-red-500 mx-1"></i> oleh Dhava Project.</p>
        </footer>
    </div>

    <script>
        // Fungsi Utama Memanggil Pasien
        function panggilPasien(nomorAntrian, btnElement) {
            // 1. Simpan State Tombol
            const btnText = btnElement.querySelector('.btn-text');
            const btnIcon = btnElement.querySelector('i.fa-bullhorn');
            const originalContent = btnText.innerHTML;

            // 2. Ubah Tampilan Tombol (Visual Feedback)
            btnText.innerHTML = "Memanggil...";
            btnIcon.classList.remove('group-hover:animate-wiggle');
            btnIcon.classList.add('animate-pulse');

            // Disable tombol agar tidak spam
            btnElement.disabled = true;
            btnElement.classList.add('opacity-75', 'cursor-wait');

            // 3. Siapkan Playlist Audio
            // Pastikan file intro.mp3 dan angka (0.mp3 - 9.mp3) ada di folder public/assets/audio/
            let playlist = ['intro.mp3'];
            let digits = nomorAntrian.toString().split(''); // Pecah string "001" jadi array ['0', '0', '1']

            digits.forEach(digit => {
                playlist.push(digit + '.mp3');
            });

            // 4. Player Logic (Recursive)
            let audioIndex = 0;
            const audioPath = BASE_URL + '/assets/audio/';

            function playNext() {
                if (audioIndex < playlist.length) {
                    let audio = new Audio(audioPath + playlist[audioIndex]);

                    // Audio Selesai -> Lanjut next file
                    audio.onended = function() {
                        audioIndex++;
                        playNext();
                    };

                    // Audio Error (File hilang) -> Skip & Log error
                    audio.onerror = function() {
                        console.error("Audio tidak ditemukan: " + playlist[audioIndex]);
                        audioIndex++;
                        playNext();
                    };

                    // Mulai Play
                    audio.play().catch(e => {
                        console.warn("Autoplay dicegah browser:", e);
                        // Jika error autoplay, reset tombol segera
                        resetButton();
                    });

                } else {
                    // 5. Semua Audio Selesai Diputar
                    resetButton();
                }
            }

            // Fungsi Mengembalikan Tombol ke Semula
            function resetButton() {
                btnText.innerHTML = originalContent;
                btnIcon.classList.remove('animate-pulse');
                btnIcon.classList.add('group-hover:animate-wiggle');

                btnElement.disabled = false;
                btnElement.classList.remove('opacity-75', 'cursor-wait');
            }

            // Mulai Putaran Pertama
            playNext();
        }
    </script>

</body>

</html>