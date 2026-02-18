<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Apoteker | Manajemen RS</title>

    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

    <style>
        /* Animasi Custom */
        @keyframes fade-in-down {
            0% {
                opacity: 0;
                transform: translateY(-10px);
            }

            100% {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .animate-fade-in-down {
            animation: fade-in-down 0.5s ease-out;
        }
    </style>
</head>

<body class="bg-gray-900 text-gray-100 font-sans antialiased">

    <div class="min-h-screen flex flex-col">

        <nav class="bg-gray-800 border-b border-gray-700 px-6 py-4 flex justify-between items-center sticky top-0 z-50 shadow-lg">
            <div class="flex items-center gap-3">
                <div class="bg-emerald-600 p-2 rounded-lg">
                    <i class="fas fa-prescription-bottle-alt text-white text-xl"></i>
                </div>
                <div>
                    <h1 class="text-xl font-bold tracking-tight leading-none">RS <span class="text-emerald-500">Rawat Jalan</span></h1>
                    <p class="text-xs text-gray-400">Panel Apoteker</p>
                </div>
            </div>

            <div class="flex items-center gap-6">
                <div class="text-right hidden md:block">
                    <p class="text-sm font-semibold text-white"><?= session()->get('full_name') ?></p>
                    <p class="text-xs text-emerald-400">Farmasi & Obat</p>
                </div>

                <div class="h-10 w-10 rounded-full bg-gray-700 flex items-center justify-center border-2 border-emerald-500 shadow-emerald-500/20 shadow-lg">
                    <i class="fas fa-user-nurse text-emerald-300"></i>
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
                    <div class="mb-6 p-4 bg-emerald-900/20 border border-emerald-500/50 text-emerald-400 rounded-xl flex items-center gap-3 shadow-lg animate-fade-in-down">
                        <i class="fas fa-check-circle text-xl"></i>
                        <div>
                            <p class="font-bold">Berhasil!</p>
                            <p class="text-sm opacity-90"><?= session()->getFlashdata('success') ?></p>
                        </div>
                    </div>
                <?php endif; ?>

                <?php if (session()->getFlashdata('error')) : ?>
                    <div class="mb-6 p-4 bg-red-900/20 border border-red-500/50 text-red-400 rounded-xl flex items-center gap-3 shadow-lg animate-fade-in-down">
                        <i class="fas fa-exclamation-circle text-xl"></i>
                        <div>
                            <p class="font-bold">Gagal!</p>
                            <p class="text-sm opacity-90"><?= session()->getFlashdata('error') ?></p>
                        </div>
                    </div>
                <?php endif; ?>

                <div class="flex flex-col md:flex-row md:items-end justify-between mb-8 gap-6">
                    <div>
                        <h2 class="text-3xl font-bold text-white mb-2">Penyerahan Obat</h2>
                        <div class="flex items-center gap-2 text-gray-400 text-sm">
                            <i class="far fa-calendar-alt"></i>
                            <span><?= date('l, d F Y') ?></span>
                        </div>
                    </div>

                    <div class="bg-gray-800 p-4 rounded-xl border border-gray-700 flex items-center gap-4 shadow-xl">
                        <div class="p-3 bg-emerald-500/10 rounded-lg text-emerald-400">
                            <i class="fas fa-pills text-2xl"></i>
                        </div>
                        <div>
                            <p class="text-xs text-gray-400 uppercase font-bold tracking-wider">Siap Diambil</p>
                            <p class="text-2xl font-bold text-white"><?= !empty($resep) ? count($resep) : 0 ?></p>
                        </div>
                    </div>
                </div>

                <div class="bg-gray-800 rounded-2xl shadow-2xl border border-gray-700 overflow-hidden">

                    <div class="p-6 border-b border-gray-700 flex justify-between items-center bg-gray-800/80 backdrop-blur-sm">
                        <h3 class="font-bold text-lg text-emerald-400 flex items-center gap-2">
                            <i class="fas fa-receipt"></i> Daftar Resep Lunas
                        </h3>
                        <button onclick="location.reload()" class="text-gray-400 hover:text-white transition flex items-center gap-2 text-sm font-medium hover:bg-gray-700 px-3 py-1.5 rounded-lg">
                            <i class="fas fa-sync-alt"></i> Refresh Data
                        </button>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse">
                            <thead>
                                <tr class="bg-gray-700/30 text-gray-400 text-xs uppercase tracking-wider border-b border-gray-700">
                                    <th class="px-6 py-4 font-bold">ID Resep</th>
                                    <th class="px-6 py-4 font-bold">Informasi Pasien</th>
                                    <th class="px-6 py-4 font-bold">Status Pembayaran</th>
                                    <th class="px-6 py-4 font-bold text-right">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-700">
                                <?php if (!empty($resep)): ?>
                                    <?php foreach ($resep as $r): ?>
                                        <tr class="hover:bg-gray-700/30 transition-colors group">

                                            <td class="px-6 py-5">
                                                <span class="font-mono text-emerald-400 font-bold bg-emerald-900/30 px-2 py-1 rounded border border-emerald-800">
                                                    #<?= $r['prescription_id'] ?>
                                                </span>
                                            </td>

                                            <td class="px-6 py-5">
                                                <div class="flex items-center gap-3">
                                                    <div class="h-8 w-8 rounded-full bg-gray-700 flex items-center justify-center text-gray-400">
                                                        <i class="fas fa-user"></i>
                                                    </div>
                                                    <div>
                                                        <p class="font-bold text-white"><?= esc($r['patient_name']) ?></p>
                                                        <p class="text-xs text-gray-500">Pasien Rawat Jalan</p>
                                                    </div>
                                                </div>
                                            </td>

                                            <td class="px-6 py-5">
                                                <span class="inline-flex items-center gap-1.5 bg-green-500/10 text-green-400 px-3 py-1 rounded-full text-xs font-bold border border-green-500/20">
                                                    <i class="fas fa-check-circle text-[10px]"></i>
                                                    Lunas (Siap Ambil)
                                                </span>
                                            </td>

                                            <td class="px-6 py-5 text-right">
                                                <?php if (empty($r['pickup_id'])): ?>
                                                    <form action="<?= base_url('apoteker/pickup/' . $r['prescription_id']) ?>" method="post" class="inline">
                                                        <?= csrf_field() ?>
                                                        <button type="submit"
                                                            onclick="return confirm('Konfirmasi penyerahan obat kepada pasien?')"
                                                            class="inline-flex items-center gap-2 bg-emerald-600 hover:bg-emerald-500 text-white px-4 py-2 rounded-lg font-semibold transition-all shadow-lg shadow-emerald-900/20 active:scale-95 border border-emerald-500/50">
                                                            <i class="fas fa-hand-holding-medical"></i>
                                                            <span>Berikan Obat</span>
                                                        </button>
                                                    </form>
                                                <?php else: ?>
                                                    <button disabled class="inline-flex items-center gap-2 bg-gray-700 text-gray-400 px-4 py-2 rounded-lg font-semibold cursor-not-allowed opacity-50 border border-gray-600">
                                                        <i class="fas fa-check-double"></i>
                                                        <span>Selesai</span>
                                                    </button>
                                                <?php endif; ?>
                                            </td>

                                        </tr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="4" class="px-6 py-24 text-center">
                                            <div class="flex flex-col items-center justify-center opacity-50">
                                                <div class="bg-gray-800 p-6 rounded-full mb-4">
                                                    <i class="fas fa-box-open text-4xl text-gray-500"></i>
                                                </div>
                                                <p class="text-xl font-bold text-gray-400">Belum ada resep masuk</p>
                                                <p class="text-sm text-gray-500 mt-1">Daftar resep yang sudah dibayar di kasir akan muncul di sini.</p>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </main>

        <footer class="bg-gray-800 border-t border-gray-700 py-6 px-6 text-center">
            <p class="text-sm text-gray-500">&copy; 2026 RS Rawat Jalan System. Dibuat dengan <i class="fas fa-heart text-red-500 mx-1"></i> oleh Dhava Project.</p>
        </footer>
    </div>

</body>

</html>