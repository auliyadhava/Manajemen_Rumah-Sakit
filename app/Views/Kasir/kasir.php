<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Kasir | Manajemen RS</title>

    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

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
                <div class="bg-amber-600 p-2 rounded-lg">
                    <i class="fas fa-cash-register text-white text-xl"></i>
                </div>
                <div>
                    <h1 class="text-xl font-bold tracking-tight leading-none">RS <span class="text-amber-500">Rawat Jalan</span></h1>
                    <p class="text-xs text-gray-400">Panel Kasir</p>
                </div>
            </div>

            <div class="flex items-center gap-6">
                <div class="text-right hidden md:block">
                    <p class="text-sm font-semibold text-white"><?= session()->get('full_name') ?></p>
                    <p class="text-xs text-amber-400">Keuangan & Billing</p>
                </div>

                <div class="h-10 w-10 rounded-full bg-gray-700 flex items-center justify-center border-2 border-amber-500 shadow-amber-500/20 shadow-lg">
                    <i class="fas fa-wallet text-amber-300"></i>
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
                    <div class="mb-6 p-4 bg-amber-900/20 border border-amber-500/50 text-amber-400 rounded-xl flex items-center gap-3 shadow-lg animate-fade-in-down">
                        <i class="fas fa-check-circle text-xl"></i>
                        <div>
                            <p class="font-bold">Transaksi Berhasil!</p>
                            <p class="text-sm opacity-90"><?= session()->getFlashdata('success') ?></p>
                        </div>
                    </div>
                <?php endif; ?>

                <div class="flex flex-col md:flex-row md:items-end justify-between mb-8 gap-6">
                    <div>
                        <h2 class="text-3xl font-bold text-white mb-2">Kasir Dashboard</h2>
                        <div class="flex items-center gap-2 text-gray-400 text-sm">
                            <i class="far fa-calendar-alt"></i>
                            <span><?= date('l, d F Y') ?></span>
                        </div>
                    </div>

                    <div class="bg-gray-800 p-4 rounded-xl border border-gray-700 flex items-center gap-4 shadow-xl">
                        <div class="p-3 bg-red-500/10 rounded-lg text-red-400">
                            <i class="fas fa-file-invoice-dollar text-2xl"></i>
                        </div>
                        <div>
                            <p class="text-xs text-gray-400 uppercase font-bold tracking-wider">Total Pending</p>
                            <p class="text-2xl font-bold text-white"><?= $count_unpaid ?? 0 ?></p>
                        </div>
                    </div>
                </div>

                <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

                    <div class="lg:col-span-1">
                        <div class="bg-gray-800 rounded-2xl shadow-2xl border border-gray-700 p-6 h-full">
                            <h3 class="font-bold text-lg text-amber-400 mb-6 border-b border-gray-700 pb-2">
                                <i class="fas fa-chart-pie mr-2"></i> Status Tagihan
                            </h3>

                            <div class="relative h-64 w-full flex justify-center">
                                <canvas id="pembayaranChart"></canvas>
                            </div>

                            <div class="mt-6 flex justify-center gap-4 text-sm">
                                <div class="flex items-center gap-2">
                                    <span class="w-3 h-3 rounded-full bg-red-500"></span>
                                    <span class="text-gray-300">Belum Bayar</span>
                                </div>
                                <div class="flex items-center gap-2">
                                    <span class="w-3 h-3 rounded-full bg-emerald-500"></span>
                                    <span class="text-gray-300">Lunas</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="lg:col-span-2">
                        <div class="bg-gray-800 rounded-2xl shadow-2xl border border-gray-700 overflow-hidden">

                            <div class="p-6 border-b border-gray-700 bg-gray-800/80 backdrop-blur-sm">
                                <h3 class="font-bold text-lg text-amber-400 flex items-center gap-2">
                                    <i class="fas fa-money-bill-wave"></i> Daftar Tagihan Pasien
                                </h3>
                            </div>

                            <div class="overflow-x-auto">
                                <table class="w-full text-left border-collapse">
                                    <thead>
                                        <tr class="bg-gray-700/30 text-gray-400 text-xs uppercase tracking-wider border-b border-gray-700">
                                            <th class="px-6 py-4 font-bold">Pasien</th>
                                            <th class="px-6 py-4 font-bold">Poli</th>
                                            <th class="px-6 py-4 font-bold">Status</th>
                                            <th class="px-6 py-4 font-bold">Metode & Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-gray-700">
                                        <?php if (!empty($tagihan)): ?>
                                            <?php foreach ($tagihan as $t): ?>
                                                <tr class="hover:bg-gray-700/30 transition-colors group">

                                                    <td class="px-6 py-5">
                                                        <div class="flex items-center gap-3">
                                                            <div class="h-8 w-8 rounded-full bg-gray-700 flex items-center justify-center text-gray-400 font-bold">
                                                                <?= substr($t['full_name'], 0, 1) ?>
                                                            </div>
                                                            <div>
                                                                <p class="font-bold text-white"><?= esc($t['full_name']) ?></p>
                                                                <p class="text-xs text-gray-500">ID: #<?= $t['appointment_id'] ?></p>
                                                            </div>
                                                        </div>
                                                    </td>

                                                    <td class="px-6 py-5">
                                                        <span class="text-gray-300 font-medium"><?= esc($t['poli']) ?></span>
                                                    </td>

                                                    <td class="px-6 py-5">
                                                        <span class="inline-flex items-center gap-1.5 bg-red-500/10 text-red-400 px-3 py-1 rounded-full text-xs font-bold border border-red-500/20">
                                                            <i class="fas fa-clock text-[10px]"></i>
                                                            Belum Bayar
                                                        </span>
                                                    </td>

                                                    <td class="px-6 py-5">
                                                        <form action="/kasir/bayar/<?= $t['appointment_id'] ?>" method="POST" class="flex flex-col sm:flex-row gap-2 items-start sm:items-center">

                                                            <div class="relative">
                                                                <select name="metode" required class="appearance-none bg-gray-900 border border-gray-600 text-gray-300 text-sm rounded-lg focus:ring-amber-500 focus:border-amber-500 block w-full pl-3 pr-8 py-2">
                                                                    <option value="cash">💵 Tunai</option>
                                                                    <option value="card">💳 Kartu</option>
                                                                    <option value="insurance">🛡️ Asuransi</option>
                                                                </select>
                                                                <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-gray-400">
                                                                    <i class="fas fa-chevron-down text-xs"></i>
                                                                </div>
                                                            </div>

                                                            <button type="submit"
                                                                onclick="return confirm('Proses pembayaran untuk <?= $t['full_name'] ?>?')"
                                                                class="inline-flex items-center gap-2 bg-amber-600 hover:bg-amber-500 text-white px-4 py-2 rounded-lg font-semibold transition-all shadow-lg shadow-amber-900/20 active:scale-95 border border-amber-500/50 text-sm whitespace-nowrap">
                                                                <i class="fas fa-check"></i> Bayar
                                                            </button>
                                                        </form>
                                                    </td>

                                                </tr>
                                            <?php endforeach; ?>
                                        <?php else: ?>
                                            <tr>
                                                <td colspan="4" class="px-6 py-24 text-center">
                                                    <div class="flex flex-col items-center justify-center opacity-50">
                                                        <div class="bg-gray-800 p-6 rounded-full mb-4">
                                                            <i class="fas fa-check-double text-4xl text-emerald-500"></i>
                                                        </div>
                                                        <p class="text-xl font-bold text-gray-400">Semua Lunas</p>
                                                        <p class="text-sm text-gray-500 mt-1">Tidak ada tagihan pending saat ini.</p>
                                                    </div>
                                                </td>
                                            </tr>
                                        <?php endif; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </main>

        <footer class="bg-gray-800 border-t border-gray-700 py-6 px-6 text-center">
            <p class="text-sm text-gray-500">&copy; 2026 RS Rawat Jalan System. Dibuat dengan <i class="fas fa-heart text-red-500 mx-1"></i> oleh Dhava Project.</p>
        </footer>
    </div>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const ctx = document.getElementById('pembayaranChart').getContext('2d');

            // Konfigurasi Data (Mengambil dari PHP)
            const countUnpaid = <?= $count_unpaid ?? 0 ?>;
            const countPaid = <?= $count_paid ?? 0 ?>;

            new Chart(ctx, {
                type: 'doughnut',
                data: {
                    labels: ['Belum Bayar', 'Lunas'],
                    datasets: [{
                        data: [countUnpaid, countPaid],
                        backgroundColor: [
                            '#ef4444', // Red-500 (Tailwind)
                            '#10b981' // Emerald-500 (Tailwind)
                        ],
                        borderWidth: 0,
                        hoverOffset: 10
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'bottom',
                            labels: {
                                color: '#9ca3af', // Gray-400
                                padding: 20,
                                font: {
                                    family: "'Segoe UI', sans-serif",
                                    size: 12
                                }
                            }
                        }
                    },
                    cutout: '70%',
                }
            });
        });
    </script>

</body>

</html>