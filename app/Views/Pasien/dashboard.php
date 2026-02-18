<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Pasien | Manajemen RS</title>

    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

    <style>
        /* Animasi Fade In */
        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(10px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .animate-fade-in {
            animation: fadeIn 0.5s ease-out forwards;
        }
    </style>
</head>

<body class="bg-gray-900 text-gray-100 font-sans antialiased">

    <div class="min-h-screen flex flex-col">

        <nav class="bg-gray-800 border-b border-gray-700 px-6 py-4 flex justify-between items-center sticky top-0 z-50 shadow-lg">
            <div class="flex items-center gap-3">
                <div class="bg-cyan-600 p-2 rounded-lg">
                    <i class="fas fa-hospital-user text-white text-xl"></i>
                </div>
                <div>
                    <h1 class="text-xl font-bold tracking-tight leading-none">Portal <span class="text-cyan-400">Pasien</span></h1>
                    <p class="text-xs text-gray-400">Panel Utama</p>
                </div>
            </div>

            <div class="flex items-center gap-6">
                <div class="text-right hidden md:block">
                    <p class="text-sm font-semibold text-white"><?= session()->get('full_name') ?></p>
                    <p class="text-xs text-cyan-400">Pasien Umum</p>
                </div>
                <div class="h-10 w-10 rounded-full bg-gray-700 flex items-center justify-center border-2 border-cyan-500 shadow-cyan-500/20 shadow-lg">
                    <i class="fas fa-user text-cyan-300"></i>
                </div>
                <a href="<?= base_url('logout') ?>"
                    onclick="return confirm('Yakin ingin logout?')"
                    class="bg-red-600/10 hover:bg-red-600 text-red-500 hover:text-white px-4 py-2 rounded-lg text-sm font-semibold transition duration-300 border border-red-600/30 hover:border-red-600">
                    <i class="fas fa-sign-out-alt mr-2"></i> Keluar
                </a>
            </div>
        </nav>

        <main class="flex-grow p-6 lg:p-10 flex flex-col justify-center animate-fade-in">
            <div class="max-w-6xl mx-auto w-full">

                <div class="text-center mb-10">
                    <h2 class="text-3xl md:text-4xl font-bold text-white mb-2">
                        Selamat Datang, <span class="text-cyan-400"><?= session()->get('full_name') ?></span> 👋
                    </h2>
                    <p class="text-gray-400 text-lg">Apa yang ingin Anda lakukan hari ini?</p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">

                    <a href="<?= base_url('pasien/booking') ?>" class="group bg-gray-800 rounded-2xl p-8 border border-gray-700 hover:border-cyan-500 hover:shadow-[0_0_20px_rgba(6,182,212,0.15)] transition-all duration-300 transform hover:-translate-y-1 relative overflow-hidden">
                        <div class="absolute top-0 right-0 p-4 opacity-10 group-hover:opacity-20 transition-opacity">
                            <i class="fas fa-calendar-plus text-9xl text-cyan-500 transform rotate-12"></i>
                        </div>

                        <div class="relative z-10">
                            <div class="w-16 h-16 bg-cyan-900/50 rounded-2xl flex items-center justify-center mb-6 border border-cyan-500/30 group-hover:scale-110 transition-transform">
                                <i class="fas fa-calendar-plus text-3xl text-cyan-400"></i>
                            </div>
                            <h3 class="text-xl font-bold text-white mb-2 group-hover:text-cyan-400 transition-colors">Booking Periksa</h3>
                            <p class="text-gray-400 text-sm mb-4">Buat janji temu baru dengan dokter spesialis pilihan Anda secara online.</p>
                            <span class="text-cyan-400 text-sm font-bold flex items-center gap-2">
                                Mulai Daftar <i class="fas fa-arrow-right group-hover:translate-x-1 transition-transform"></i>
                            </span>
                        </div>
                    </a>

                    <a href="<?= base_url('pasien/antrian') ?>" class="group bg-gray-800 rounded-2xl p-8 border border-gray-700 hover:border-amber-500 hover:shadow-[0_0_20px_rgba(245,158,11,0.15)] transition-all duration-300 transform hover:-translate-y-1 relative overflow-hidden">
                        <div class="absolute top-0 right-0 p-4 opacity-10 group-hover:opacity-20 transition-opacity">
                            <i class="fas fa-clock text-9xl text-amber-500 transform rotate-12"></i>
                        </div>

                        <div class="relative z-10">
                            <div class="w-16 h-16 bg-amber-900/50 rounded-2xl flex items-center justify-center mb-6 border border-amber-500/30 group-hover:scale-110 transition-transform">
                                <i class="fas fa-stopwatch text-3xl text-amber-400"></i>
                            </div>
                            <h3 class="text-xl font-bold text-white mb-2 group-hover:text-amber-400 transition-colors">Monitor Antrian</h3>
                            <p class="text-gray-400 text-sm mb-4">Pantau nomor antrian Anda secara real-time dari gadget Anda.</p>
                            <span class="text-amber-400 text-sm font-bold flex items-center gap-2">
                                Cek Status <i class="fas fa-arrow-right group-hover:translate-x-1 transition-transform"></i>
                            </span>
                        </div>
                    </a>

                    <a href="<?= base_url('pasien/riwayat') ?>" class="group bg-gray-800 rounded-2xl p-8 border border-gray-700 hover:border-purple-500 hover:shadow-[0_0_20px_rgba(168,85,247,0.15)] transition-all duration-300 transform hover:-translate-y-1 relative overflow-hidden">
                        <div class="absolute top-0 right-0 p-4 opacity-10 group-hover:opacity-20 transition-opacity">
                            <i class="fas fa-clipboard-list text-9xl text-purple-500 transform rotate-12"></i>
                        </div>

                        <div class="relative z-10">
                            <div class="w-16 h-16 bg-purple-900/50 rounded-2xl flex items-center justify-center mb-6 border border-purple-500/30 group-hover:scale-110 transition-transform">
                                <i class="fas fa-file-medical text-3xl text-purple-400"></i>
                            </div>
                            <h3 class="text-xl font-bold text-white mb-2 group-hover:text-purple-400 transition-colors">Riwayat Medis</h3>
                            <p class="text-gray-400 text-sm mb-4">Lihat riwayat kunjungan, diagnosa dokter, dan resep obat sebelumnya.</p>
                            <span class="text-purple-400 text-sm font-bold flex items-center gap-2">
                                Lihat Data <i class="fas fa-arrow-right group-hover:translate-x-1 transition-transform"></i>
                            </span>
                        </div>
                    </a>

                </div>

                <div class="mt-12 grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="bg-blue-900/20 border border-blue-800 rounded-xl p-4 flex items-center gap-4">
                        <i class="fas fa-info-circle text-2xl text-blue-400 ml-2"></i>
                        <div>
                            <h4 class="font-bold text-white">Butuh Bantuan?</h4>
                            <p class="text-sm text-gray-400">Hubungi Call Center RS di (021) 123-4567.</p>
                        </div>
                    </div>

                    <a href="<?= base_url('logout') ?>" onclick="return confirm('Yakin ingin logout?')" class="bg-red-900/20 border border-red-800 rounded-xl p-4 flex items-center gap-4 hover:bg-red-900/40 transition group cursor-pointer">
                        <i class="fas fa-sign-out-alt text-2xl text-red-400 ml-2 group-hover:scale-110 transition-transform"></i>
                        <div>
                            <h4 class="font-bold text-white group-hover:text-red-300">Keluar Aplikasi</h4>
                            <p class="text-sm text-gray-400">Akhiri sesi Anda dengan aman.</p>
                        </div>
                    </a>
                </div>

            </div>
        </main>

        <footer class="bg-gray-800 border-t border-gray-700 py-6 px-6 text-center text-xs text-gray-500">
            &copy; 2026 RS Rawat Jalan System. Dibuat dengan <i class="fas fa-heart text-red-500 mx-1"></i> oleh Dhava Project.
        </footer>

    </div>

</body>

</html>