<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Pemeriksaan | Portal Pasien</title>

    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>

<body class="bg-gray-900 text-gray-100 font-sans antialiased">

    <div class="min-h-screen flex flex-col">

        <nav class="bg-gray-800 border-b border-gray-700 px-6 py-4 flex justify-between items-center sticky top-0 z-50 shadow-lg">
            <div class="flex items-center gap-3">
                <a href="<?= base_url('pasien/riwayat') ?>" class="bg-gray-700 p-2 rounded-lg hover:bg-gray-600 transition group" title="Kembali">
                    <i class="fas fa-arrow-left text-white group-hover:-translate-x-1 transition-transform"></i>
                </a>
                <div>
                    <h1 class="text-xl font-bold tracking-tight leading-none">Detail <span class="text-cyan-400">Pemeriksaan</span></h1>
                    <p class="text-xs text-gray-400">Rekam Medis</p>
                </div>
            </div>

            <div class="flex items-center gap-4">
                <div class="text-right hidden md:block">
                    <p class="text-sm font-semibold text-white"><?= session()->get('full_name') ?></p>
                    <p class="text-xs text-cyan-400">Pasien Umum</p>
                </div>
                <div class="h-10 w-10 rounded-full bg-gray-700 flex items-center justify-center border-2 border-cyan-500 shadow-cyan-500/20">
                    <i class="fas fa-user text-cyan-300"></i>
                </div>
            </div>
        </nav>

        <main class="flex-grow p-6 lg:p-8">
            <div class="max-w-5xl mx-auto space-y-6">

                <div class="flex items-center justify-between">
                    <div>
                        <h2 class="text-2xl font-bold text-white">Laporan Medis</h2>
                        <p class="text-sm text-gray-400">ID Kunjungan: #<?= $pemeriksaan->appointment_id ?? '-' ?></p>
                    </div>
                    <a href="<?= base_url('pasien/riwayat') ?>" class="text-sm text-gray-400 hover:text-white flex items-center gap-2 transition">
                        <i class="fas fa-history"></i> Kembali ke Riwayat
                    </a>
                </div>

                <section class="bg-gray-800 rounded-2xl shadow-xl border border-gray-700 overflow-hidden relative">
                    <div class="absolute top-0 left-0 w-1.5 h-full bg-blue-500"></div>

                    <div class="p-6 border-b border-gray-700 bg-gray-800/80 backdrop-blur-sm flex items-center gap-3">
                        <div class="bg-blue-500/10 p-2 rounded-lg text-blue-400">
                            <i class="fas fa-stethoscope text-xl"></i>
                        </div>
                        <h3 class="font-bold text-lg text-white">Hasil Diagnosa</h3>
                    </div>

                    <div class="p-6">
                        <?php if ($pemeriksaan): ?>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <p class="text-xs text-gray-500 uppercase tracking-wider mb-1">Diagnosis Dokter</p>
                                    <p class="text-xl font-semibold text-blue-300 leading-relaxed">
                                        <?= esc($pemeriksaan->diagnosis); ?>
                                    </p>
                                </div>
                                <div class="bg-gray-700/30 p-4 rounded-xl border border-gray-700">
                                    <p class="text-xs text-gray-500 uppercase tracking-wider mb-2">Catatan Medis</p>
                                    <p class="text-gray-300 italic">
                                        "<?= esc($pemeriksaan->notes); ?>"
                                    </p>
                                </div>
                            </div>
                        <?php else: ?>
                            <div class="flex flex-col items-center justify-center py-6 text-center">
                                <i class="fas fa-user-clock text-4xl text-gray-600 mb-3"></i>
                                <p class="text-gray-400">Hasil pemeriksaan belum diinput oleh Dokter.</p>
                            </div>
                        <?php endif; ?>
                    </div>
                </section>

                <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

                    <section class="lg:col-span-2 bg-gray-800 rounded-2xl shadow-xl border border-gray-700 overflow-hidden relative">
                        <div class="absolute top-0 left-0 w-1.5 h-full bg-emerald-500"></div>

                        <div class="p-6 border-b border-gray-700 bg-gray-800/80 backdrop-blur-sm flex items-center gap-3">
                            <div class="bg-emerald-500/10 p-2 rounded-lg text-emerald-400">
                                <i class="fas fa-pills text-xl"></i>
                            </div>
                            <h3 class="font-bold text-lg text-white">Resep & Obat</h3>
                        </div>

                        <div class="p-0">
                            <?php if (!empty($resep)): ?>
                                <div class="overflow-x-auto">
                                    <table class="w-full text-left border-collapse">
                                        <thead>
                                            <tr class="bg-gray-700/50 text-gray-400 text-xs uppercase tracking-wider border-b border-gray-700">
                                                <th class="px-6 py-3 font-semibold">Nama Obat</th>
                                                <th class="px-6 py-3 font-semibold">Dosis</th>
                                                <th class="px-6 py-3 font-semibold">Instruksi</th>
                                            </tr>
                                        </thead>
                                        <tbody class="divide-y divide-gray-700">
                                            <?php foreach ($resep as $r): ?>
                                                <tr class="hover:bg-gray-700/20 transition">
                                                    <td class="px-6 py-4 font-bold text-white"><?= esc($r->name); ?></td>
                                                    <td class="px-6 py-4 text-emerald-300 font-mono text-sm"><?= esc($r->dosage); ?></td>
                                                    <td class="px-6 py-4 text-gray-300 text-sm"><?= esc($r->instructions); ?></td>
                                                </tr>
                                            <?php endforeach; ?>
                                        </tbody>
                                    </table>
                                </div>
                            <?php else: ?>
                                <div class="p-8 text-center text-gray-500">
                                    <i class="fas fa-prescription-bottle text-3xl mb-2 opacity-50"></i>
                                    <p>Tidak ada resep obat untuk kunjungan ini.</p>
                                </div>
                            <?php endif; ?>
                        </div>
                    </section>

                    <section class="lg:col-span-1 bg-gray-800 rounded-2xl shadow-xl border border-gray-700 overflow-hidden relative">
                        <div class="absolute top-0 left-0 w-1.5 h-full bg-amber-500"></div>

                        <div class="p-6 border-b border-gray-700 bg-gray-800/80 backdrop-blur-sm flex items-center gap-3">
                            <div class="bg-amber-500/10 p-2 rounded-lg text-amber-400">
                                <i class="fas fa-file-invoice-dollar text-xl"></i>
                            </div>
                            <h3 class="font-bold text-lg text-white">Tagihan</h3>
                        </div>

                        <div class="p-6 flex flex-col justify-center h-full min-h-[200px]">
                            <?php if ($pembayaran): ?>
                                <div class="text-center">
                                    <p class="text-xs text-gray-400 uppercase mb-1">Total Biaya</p>
                                    <h2 class="text-3xl font-mono font-bold text-white mb-4">
                                        Rp <?= number_format($pembayaran->amount, 0, ',', '.'); ?>
                                    </h2>

                                    <?php
                                    $badgeColor = 'bg-gray-700 text-gray-300';
                                    $icon = 'fa-question';

                                    if ($pembayaran->status == 'paid') {
                                        $badgeColor = 'bg-emerald-500/20 text-emerald-400 border border-emerald-500/30';
                                        $icon = 'fa-check-circle';
                                    } elseif ($pembayaran->status == 'pending') {
                                        $badgeColor = 'bg-amber-500/20 text-amber-400 border border-amber-500/30';
                                        $icon = 'fa-clock';
                                    }
                                    ?>

                                    <span class="inline-flex items-center gap-2 px-4 py-2 rounded-full text-sm font-bold <?= $badgeColor ?>">
                                        <i class="fas <?= $icon ?>"></i> <?= strtoupper($pembayaran->status); ?>
                                    </span>

                                    <?php if ($pembayaran->status == 'pending'): ?>
                                        <p class="text-xs text-gray-500 mt-4">Silakan menuju kasir untuk melakukan pembayaran.</p>
                                    <?php else: ?>
                                        <p class="text-xs text-emerald-500/70 mt-4">Terima kasih, pembayaran telah lunas.</p>
                                    <?php endif; ?>
                                </div>
                            <?php else: ?>
                                <div class="text-center text-gray-500">
                                    <i class="fas fa-calculator text-3xl mb-2 opacity-50"></i>
                                    <p>Tagihan sedang diproses.</p>
                                </div>
                            <?php endif; ?>
                        </div>
                    </section>

                </div>

            </div>
        </main>

        <footer class="bg-gray-800 border-t border-gray-700 py-6 px-6 text-center text-xs text-gray-500">
            &copy; 2026 RS Rawat Jalan System. Dibuat dengan <i class="fas fa-heart text-red-500 mx-1"></i> oleh Dhava Project.
        </footer>

    </div>

</body>

</html>