<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Riwayat Appointment | Portal Pasien</title>

    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>

<body class="bg-gray-900 text-gray-100 font-sans antialiased">

    <div class="min-h-screen flex flex-col">

        <nav class="bg-gray-800 border-b border-gray-700 px-6 py-4 flex justify-between items-center sticky top-0 z-50 shadow-lg">
            <div class="flex items-center gap-3">
                <a href="<?= base_url('Pasien/dashboard') ?>" class="bg-gray-700 p-2 rounded-lg hover:bg-gray-600 transition group" title="Kembali ke Dashboard">
                    <i class="fas fa-arrow-left text-white group-hover:-translate-x-1 transition-transform"></i>
                </a>
                <div>
                    <h1 class="text-xl font-bold tracking-tight leading-none">Riwayat <span class="text-cyan-400">Appointment</span></h1>
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
            <div class="max-w-6xl mx-auto">

                <div class="flex flex-col md:flex-row md:items-end justify-between mb-8 gap-4">
                    <div>
                        <h2 class="text-3xl font-bold text-white mb-2">Daftar Kunjungan</h2>
                        <div class="flex items-center gap-2 text-gray-400 text-sm">
                            <i class="fas fa-history"></i>
                            <span>Semua riwayat pemeriksaan medis Anda tercatat di sini.</span>
                        </div>
                    </div>
                </div>

                <div class="bg-gray-800 rounded-2xl shadow-2xl border border-gray-700 overflow-hidden">

                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse">
                            <thead>
                                <tr class="bg-gray-700/30 text-gray-400 text-xs uppercase tracking-wider border-b border-gray-700">
                                    <th class="px-6 py-4 font-bold">Tanggal</th>
                                    <th class="px-6 py-4 font-bold">Poliklinik</th>
                                    <th class="px-6 py-4 font-bold">Dokter</th>
                                    <th class="px-6 py-4 font-bold">Status</th>
                                    <th class="px-6 py-4 font-bold text-right">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-700">
                                <?php if (!empty($appointments)): ?>
                                    <?php foreach ($appointments as $a): ?>
                                        <tr class="hover:bg-gray-700/30 transition-colors group">

                                            <td class="px-6 py-5">
                                                <div class="flex items-center gap-3">
                                                    <div class="bg-gray-700 p-2 rounded-lg text-gray-400 border border-gray-600">
                                                        <i class="far fa-calendar-alt"></i>
                                                    </div>
                                                    <div>
                                                        <p class="font-bold text-white"><?= date('d M Y', strtotime($a->schedule_date)) ?></p>
                                                        <p class="text-xs text-gray-500"><?= date('H:i', strtotime($a->created_at ?? 'now')) ?> WIB</p>
                                                    </div>
                                                </div>
                                            </td>

                                            <td class="px-6 py-5">
                                                <span class="text-gray-300 font-medium"><?= esc($a->department) ?></span>
                                            </td>

                                            <td class="px-6 py-5">
                                                <span class="text-gray-300 font-medium"><?= esc($a->doctor_name ?? 'Dokter Jaga') ?></span>
                                            </td>

                                            <td class="px-6 py-5">
                                                <?php
                                                $statusClasses = 'bg-gray-700 text-gray-400 border-gray-600';
                                                $icon = 'fa-circle';

                                                if ($a->status == 'waiting') {
                                                    $statusClasses = 'bg-amber-500/10 text-amber-400 border-amber-500/20';
                                                    $icon = 'fa-clock';
                                                } elseif ($a->status == 'confirmed') {
                                                    $statusClasses = 'bg-blue-500/10 text-blue-400 border-blue-500/20';
                                                    $icon = 'fa-thumbs-up';
                                                } elseif ($a->status == 'done') {
                                                    $statusClasses = 'bg-emerald-500/10 text-emerald-400 border-emerald-500/20';
                                                    $icon = 'fa-check-circle';
                                                } elseif ($a->status == 'cancelled') {
                                                    $statusClasses = 'bg-red-500/10 text-red-400 border-red-500/20';
                                                    $icon = 'fa-times-circle';
                                                }
                                                ?>
                                                <span class="inline-flex items-center gap-2 px-3 py-1 rounded-full text-xs font-bold border <?= $statusClasses ?>">
                                                    <i class="fas <?= $icon ?>"></i> <?= strtoupper($a->status) ?>
                                                </span>
                                            </td>

                                            <td class="px-6 py-5 text-right">
                                                <?php if ($a->status == 'done'): ?>
                                                    <a href="<?= base_url('pasien/detail_pemeriksaan/' . $a->appointment_id) ?>"
                                                        class="inline-flex items-center gap-2 bg-cyan-600 hover:bg-cyan-500 text-white px-4 py-2 rounded-lg font-semibold transition shadow-lg shadow-cyan-900/20 active:scale-95 border border-cyan-500/50 text-sm">
                                                        <i class="fas fa-file-medical-alt"></i>
                                                        Lihat Hasil
                                                    </a>
                                                <?php elseif ($a->status == 'waiting'): ?>
                                                    <span class="text-gray-500 text-xs italic flex items-center justify-end gap-1">
                                                        <i class="fas fa-hourglass-start"></i> Menunggu Antrian
                                                    </span>
                                                <?php else: ?>
                                                    <span class="text-gray-500 text-xs italic">-</span>
                                                <?php endif; ?>
                                            </td>

                                        </tr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="5" class="px-6 py-24 text-center">
                                            <div class="flex flex-col items-center justify-center opacity-50">
                                                <div class="bg-gray-800 p-6 rounded-full mb-4">
                                                    <i class="fas fa-history text-4xl text-gray-500"></i>
                                                </div>
                                                <p class="text-xl font-bold text-gray-400">Belum ada riwayat</p>
                                                <p class="text-sm text-gray-500 mt-1">Anda belum pernah melakukan pemeriksaan medis di sini.</p>
                                                <a href="<?= base_url('pasien/booking') ?>" class="mt-4 text-cyan-400 hover:text-cyan-300 underline">Buat Janji Baru</a>
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

</body>

</html>