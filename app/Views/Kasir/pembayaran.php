<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pembayaran Pasien | Manajemen RS</title>

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
                <a href="<?= base_url('kasir/dashboard') ?>" class="bg-amber-600 p-2 rounded-lg hover:bg-amber-500 transition">
                    <i class="fas fa-arrow-left text-white text-xl"></i>
                </a>
                <div>
                    <h1 class="text-xl font-bold tracking-tight leading-none">Menu <span class="text-amber-500">Pembayaran</span></h1>
                    <p class="text-xs text-gray-400">Kasir Rawat Jalan</p>
                </div>
            </div>

            <div class="flex items-center gap-6">
                <div class="text-right hidden md:block">
                    <p class="text-sm font-semibold text-white"><?= session()->get('full_name') ?></p>
                    <p class="text-xs text-amber-400">Kasir</p>
                </div>
                <div class="h-10 w-10 rounded-full bg-gray-700 flex items-center justify-center border-2 border-amber-500 shadow-amber-500/20">
                    <i class="fas fa-wallet text-amber-300"></i>
                </div>
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

                <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

                    <div class="lg:col-span-1 space-y-6">

                        <div class="bg-gray-800 rounded-2xl shadow-xl border border-gray-700 p-6">
                            <h3 class="font-bold text-lg text-amber-400 mb-4 border-b border-gray-700 pb-2">
                                <i class="fas fa-chart-pie mr-2"></i> Ringkasan Hari Ini
                            </h3>

                            <div class="relative h-56 w-full flex justify-center">
                                <canvas id="pembayaranChart"></canvas>
                            </div>

                            <div class="mt-4 grid grid-cols-2 gap-4 text-center">
                                <div class="bg-gray-700/50 p-3 rounded-lg border border-red-500/30">
                                    <p class="text-xs text-gray-400 uppercase">Belum Bayar</p>
                                    <p class="text-xl font-bold text-red-400"><?= $count_unpaid ?></p>
                                </div>
                                <div class="bg-gray-700/50 p-3 rounded-lg border border-emerald-500/30">
                                    <p class="text-xs text-gray-400 uppercase">Lunas</p>
                                    <p class="text-xl font-bold text-emerald-400"><?= $count_paid ?></p>
                                </div>
                            </div>
                        </div>

                        <div class="bg-blue-900/20 p-4 rounded-xl border border-blue-800 flex items-start gap-3">
                            <i class="fas fa-info-circle text-blue-400 mt-1"></i>
                            <div class="text-sm text-blue-200">
                                <p class="font-bold">Instruksi:</p>
                                <p class="opacity-80">Pilih metode pembayaran yang sesuai (Tunai/Kartu/Asuransi) sebelum menekan tombol proses.</p>
                            </div>
                        </div>

                    </div>

                    <div class="lg:col-span-2">
                        <div class="bg-gray-800 rounded-2xl shadow-xl border border-gray-700 overflow-hidden">

                            <div class="p-6 border-b border-gray-700 bg-gray-800/80 backdrop-blur-sm flex justify-between items-center">
                                <h3 class="font-bold text-lg text-white flex items-center gap-2">
                                    <i class="fas fa-file-invoice-dollar text-amber-500"></i> Tagihan Pasien
                                </h3>
                                <span class="bg-gray-700 text-gray-300 px-3 py-1 rounded-full text-xs font-mono">
                                    <?= date('d M Y') ?>
                                </span>
                            </div>

                            <div class="overflow-x-auto">
                                <table class="w-full text-left border-collapse">
                                    <thead>
                                        <tr class="bg-gray-700/30 text-gray-400 text-xs uppercase tracking-wider border-b border-gray-700">
                                            <th class="px-6 py-4 font-bold">Pasien</th>
                                            <th class="px-6 py-4 font-bold">Poli</th>
                                            <th class="px-6 py-4 font-bold">Status</th>
                                            <th class="px-6 py-4 font-bold text-center">Proses Pembayaran</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-gray-700">
                                        <?php if (!empty($tagihan)): ?>
                                            <?php foreach ($tagihan as $t): ?>
                                                <tr class="hover:bg-gray-700/30 transition-colors">

                                                    <td class="px-6 py-5">
                                                        <div>
                                                            <p class="font-bold text-white text-lg"><?= esc($t['full_name']) ?></p>
                                                            <p class="text-xs text-gray-500 font-mono">ID: #<?= $t['appointment_id'] ?></p>
                                                        </div>
                                                    </td>

                                                    <td class="px-6 py-5">
                                                        <span class="bg-gray-700 text-gray-300 px-2 py-1 rounded text-sm border border-gray-600">
                                                            <?= esc($t['poli']) ?>
                                                        </span>
                                                    </td>

                                                    <td class="px-6 py-5">
                                                        <span class="inline-flex items-center gap-1.5 bg-red-500/10 text-red-400 px-3 py-1 rounded-full text-xs font-bold border border-red-500/20">
                                                            <i class="fas fa-clock"></i> Pending
                                                        </span>
                                                    </td>

                                                    <td class="px-6 py-5">
                                                        <form action="<?= base_url('kasir/bayar/' . $t['appointment_id']) ?>" method="POST" class="flex flex-col sm:flex-row gap-2 justify-end">

                                                            <div class="relative min-w-[140px]">
                                                                <select name="metode" required class="appearance-none bg-gray-900 border border-gray-600 text-gray-300 text-sm rounded-lg focus:ring-amber-500 focus:border-amber-500 block w-full pl-3 pr-8 py-2 cursor-pointer hover:border-amber-500 transition">
                                                                    <option value="cash">💵 Tunai</option>
                                                                    <option value="card">💳 Kartu</option>
                                                                    <option value="insurance">🛡️ Asuransi</option>
                                                                </select>
                                                                <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-gray-400">
                                                                    <i class="fas fa-chevron-down text-xs"></i>
                                                                </div>
                                                            </div>

                                                            <button type="submit"
                                                                onclick="return confirm('Konfirmasi pembayaran untuk <?= $t['full_name'] ?>?')"
                                                                class="bg-amber-600 hover:bg-amber-500 text-white px-4 py-2 rounded-lg font-semibold transition shadow-lg shadow-amber-900/20 text-sm flex items-center justify-center gap-2">
                                                                <span>Bayar</span>
                                                                <i class="fas fa-paper-plane text-xs"></i>
                                                            </button>
                                                        </form>
                                                    </td>
                                                </tr>
                                            <?php endforeach; ?>
                                        <?php else: ?>
                                            <tr>
                                                <td colspan="4" class="px-6 py-16 text-center text-gray-500">
                                                    <i class="fas fa-check-double text-4xl mb-3 opacity-30"></i>
                                                    <p>Tidak ada tagihan pending saat ini.</p>
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
    </div>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const ctx = document.getElementById('pembayaranChart').getContext('2d');

            const dataUnpaid = <?= $count_unpaid ?>;
            const dataPaid = <?= $count_paid ?>;

            new Chart(ctx, {
                type: 'doughnut',
                data: {
                    labels: ['Belum Bayar', 'Lunas'],
                    datasets: [{
                        data: [dataUnpaid, dataPaid],
                        backgroundColor: [
                            '#ef4444', // Red-500
                            '#10b981' // Emerald-500
                        ],
                        borderWidth: 0,
                        hoverOffset: 15
                    }]
                },
                options: {
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'bottom',
                            labels: {
                                color: '#9ca3af',
                                padding: 20,
                                usePointStyle: true
                            }
                        }
                    },
                    cutout: '75%',
                }
            });
        });
    </script>

</body>

</html>