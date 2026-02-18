<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pembayaran Pasien | Manajemen RS</title>

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

        /* Modal Backdrop Blur */
        .modal-backdrop {
            background-color: rgba(0, 0, 0, 0.7);
            backdrop-filter: blur(4px);
        }
    </style>
</head>

<body class="bg-gray-900 text-gray-100 font-sans antialiased">

    <div class="min-h-screen flex flex-col">

        <nav class="bg-gray-800 border-b border-gray-700 px-6 py-4 flex justify-between items-center sticky top-0 z-50 shadow-lg">
            <div class="flex items-center gap-3">
                <a href="<?= base_url('kasir/dashboard') ?>" class="bg-amber-600 p-2 rounded-lg hover:bg-amber-500 transition group" title="Kembali ke Dashboard">
                    <i class="fas fa-arrow-left text-white text-xl group-hover:-translate-x-1 transition-transform"></i>
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

                <?php if (session()->getFlashdata('msg')) : ?>
                    <div class="mb-6 p-4 bg-emerald-900/20 border border-emerald-500/50 text-emerald-400 rounded-xl flex items-center gap-3 shadow-lg animate-fade-in-down">
                        <i class="fas fa-check-circle text-xl"></i>
                        <div>
                            <p class="font-bold">Transaksi Berhasil!</p>
                            <p class="text-sm opacity-90"><?= session()->getFlashdata('msg') ?></p>
                        </div>
                    </div>
                <?php endif; ?>

                <div class="flex flex-col md:flex-row md:items-end justify-between mb-8 gap-6">
                    <div>
                        <h2 class="text-3xl font-bold text-white mb-2">Antrian Pembayaran</h2>
                        <div class="flex items-center gap-2 text-gray-400 text-sm">
                            <i class="far fa-calendar-alt"></i>
                            <span><?= date('l, d F Y') ?></span>
                        </div>
                    </div>

                    <div class="bg-gray-800 p-4 rounded-xl border border-gray-700 flex items-center gap-4 shadow-xl">
                        <div class="p-3 bg-emerald-500/10 rounded-lg text-emerald-400">
                            <i class="fas fa-receipt text-2xl"></i>
                        </div>
                        <div>
                            <p class="text-xs text-gray-400 uppercase font-bold tracking-wider">Total Selesai</p>
                            <p class="text-2xl font-bold text-white"><?= $total_bayar ?? 0 ?></p>
                        </div>
                    </div>
                </div>

                <div class="bg-gray-800 rounded-2xl shadow-2xl border border-gray-700 overflow-hidden">

                    <div class="p-6 border-b border-gray-700 bg-gray-800/80 backdrop-blur-sm flex justify-between items-center">
                        <h3 class="font-bold text-lg text-amber-400 flex items-center gap-2">
                            <i class="fas fa-file-invoice-dollar"></i> Tagihan Pasien
                        </h3>
                        <span class="bg-gray-700 text-gray-300 px-3 py-1 rounded-full text-xs font-mono">
                            <?= !empty($pending) ? count($pending) : 0 ?> Pending
                        </span>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse">
                            <thead>
                                <tr class="bg-gray-700/30 text-gray-400 text-xs uppercase tracking-wider border-b border-gray-700">
                                    <th class="px-6 py-4 font-bold">Nama Pasien</th>
                                    <th class="px-6 py-4 font-bold">Poliklinik</th>
                                    <th class="px-6 py-4 font-bold">Estimasi Tagihan</th>
                                    <th class="px-6 py-4 font-bold">Status</th>
                                    <th class="px-6 py-4 font-bold text-right">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-700">
                                <?php if (!empty($pending)): ?>
                                    <?php foreach ($pending as $p): ?>
                                        <tr class="hover:bg-gray-700/30 transition-colors group">

                                            <td class="px-6 py-5">
                                                <div class="flex items-center gap-3">
                                                    <div class="h-8 w-8 rounded-full bg-gray-700 flex items-center justify-center text-gray-400 font-bold border border-gray-600">
                                                        <?= substr($p['full_name'], 0, 1) ?>
                                                    </div>
                                                    <p class="font-bold text-white text-lg"><?= esc($p['full_name']) ?></p>
                                                </div>
                                            </td>

                                            <td class="px-6 py-5">
                                                <span class="text-gray-300 font-medium"><?= esc($p['poli']) ?></span>
                                            </td>

                                            <td class="px-6 py-5">
                                                <span class="text-amber-400 font-mono font-bold text-lg">
                                                    Rp <?= number_format(($p['total_amount'] ?? 50000), 0, ',', '.'); ?>
                                                </span>
                                            </td>

                                            <td class="px-6 py-5">
                                                <span class="inline-flex items-center gap-1.5 bg-yellow-500/10 text-yellow-500 px-3 py-1 rounded-full text-xs font-bold border border-yellow-500/20">
                                                    <span class="h-1.5 w-1.5 rounded-full bg-yellow-500 animate-pulse"></span>
                                                    <?= strtoupper($p['payment_status'] ?? 'PENDING'); ?>
                                                </span>
                                            </td>

                                            <td class="px-6 py-5 text-right">
                                                <button onclick="openModal('modal-<?= $p['appointment_id'] ?>')"
                                                    class="inline-flex items-center gap-2 bg-emerald-600 hover:bg-emerald-500 text-white px-4 py-2 rounded-lg font-semibold transition-all shadow-lg shadow-emerald-900/20 active:scale-95 border border-emerald-500/50">
                                                    <i class="fas fa-cash-register"></i>
                                                    <span>Proses Bayar</span>
                                                </button>
                                            </td>

                                        </tr>

                                        <div id="modal-<?= $p['appointment_id'] ?>" class="fixed inset-0 z-50 hidden overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
                                            <div class="fixed inset-0 bg-gray-900/75 backdrop-blur-sm transition-opacity" onclick="closeModal('modal-<?= $p['appointment_id'] ?>')"></div>

                                            <div class="flex min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0">
                                                <div class="relative transform overflow-hidden rounded-2xl bg-gray-800 text-left shadow-2xl transition-all sm:my-8 sm:w-full sm:max-w-lg border border-gray-700">

                                                    <div class="bg-gray-700 px-4 py-3 sm:px-6 flex justify-between items-center border-b border-gray-600">
                                                        <h3 class="text-lg font-bold leading-6 text-white" id="modal-title">
                                                            <i class="fas fa-wallet text-amber-500 mr-2"></i> Konfirmasi Pembayaran
                                                        </h3>
                                                        <button type="button" onclick="closeModal('modal-<?= $p['appointment_id'] ?>')" class="text-gray-400 hover:text-white transition">
                                                            <i class="fas fa-times"></i>
                                                        </button>
                                                    </div>

                                                    <form action="<?= base_url('kasir/prosesBayar'); ?>" method="post">
                                                        <?= csrf_field(); ?>
                                                        <input type="hidden" name="appointment_id" value="<?= $p['appointment_id']; ?>">

                                                        <div class="px-4 py-5 sm:p-6 space-y-4">

                                                            <div class="bg-gray-900/50 p-4 rounded-xl border border-gray-700">
                                                                <p class="text-sm text-gray-400 mb-1">Nama Pasien</p>
                                                                <p class="text-xl font-bold text-white"><?= $p['full_name']; ?></p>
                                                            </div>

                                                            <div>
                                                                <p class="text-sm text-gray-400 mb-1">Total Tagihan (Jasa + Obat)</p>
                                                                <p class="text-3xl font-bold text-emerald-400 font-mono">
                                                                    Rp <?= number_format($p['total_amount'] ?? 50000, 0, ',', '.'); ?>
                                                                </p>
                                                            </div>

                                                            <div>
                                                                <label class="block text-sm font-medium text-gray-300 mb-2">Pilih Metode Pembayaran</label>
                                                                <div class="relative">
                                                                    <select name="metode" required class="appearance-none bg-gray-900 border border-gray-600 text-white text-sm rounded-lg focus:ring-amber-500 focus:border-amber-500 block w-full p-3 pr-8">
                                                                        <option value="cash">💵 Tunai (Cash)</option>
                                                                        <option value="card">💳 Kartu (Debit/Credit)</option>
                                                                        <option value="insurance">🛡️ Asuransi (Insurance)</option>
                                                                    </select>
                                                                    <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-4 text-gray-400">
                                                                        <i class="fas fa-chevron-down"></i>
                                                                    </div>
                                                                </div>
                                                            </div>

                                                        </div>

                                                        <div class="bg-gray-700/50 px-4 py-3 sm:flex sm:flex-row-reverse sm:px-6 gap-2">
                                                            <button type="submit" class="inline-flex w-full justify-center rounded-lg bg-emerald-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-emerald-500 sm:w-auto transition border border-emerald-500/50">
                                                                <i class="fas fa-check mr-2 mt-0.5"></i> Selesaikan
                                                            </button>
                                                            <button type="button" onclick="closeModal('modal-<?= $p['appointment_id'] ?>')" class="mt-3 inline-flex w-full justify-center rounded-lg bg-gray-600 px-3 py-2 text-sm font-semibold text-white shadow-sm ring-1 ring-inset ring-gray-500 hover:bg-gray-500 sm:mt-0 sm:w-auto transition">
                                                                Batal
                                                            </button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="5" class="px-6 py-24 text-center">
                                            <div class="flex flex-col items-center justify-center opacity-50">
                                                <div class="bg-gray-800 p-6 rounded-full mb-4">
                                                    <i class="fas fa-mug-hot text-4xl text-gray-500"></i>
                                                </div>
                                                <p class="text-xl font-bold text-gray-400">Tidak ada antrian</p>
                                                <p class="text-sm text-gray-500 mt-1">Semua pembayaran telah diselesaikan.</p>
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
    </div>

    <script>
        function openModal(modalId) {
            document.getElementById(modalId).classList.remove('hidden');
            // Prevent body scroll
            document.body.style.overflow = 'hidden';
        }

        function closeModal(modalId) {
            document.getElementById(modalId).classList.add('hidden');
            // Restore body scroll
            document.body.style.overflow = 'auto';
        }

        // Close modal on escape key
        document.addEventListener('keydown', function(event) {
            if (event.key === "Escape") {
                const modals = document.querySelectorAll('[id^="modal-"]');
                modals.forEach(modal => {
                    if (!modal.classList.contains('hidden')) {
                        modal.classList.add('hidden');
                        document.body.style.overflow = 'auto';
                    }
                });
            }
        });
    </script>

</body>

</html>