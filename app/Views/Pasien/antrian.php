<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Status Antrian | Portal Pasien</title>

    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

    <style>
        /* Animasi Pulse untuk Status 'Calling' */
        @keyframes soft-pulse {

            0%,
            100% {
                opacity: 1;
                transform: scale(1);
            }

            50% {
                opacity: 0.8;
                transform: scale(1.05);
            }
        }

        .animate-soft-pulse {
            animation: soft-pulse 2s infinite;
        }
    </style>
</head>

<body class="bg-gray-900 text-gray-100 font-sans antialiased">

    <div class="min-h-screen flex flex-col">

        <nav class="bg-gray-800 border-b border-gray-700 px-6 py-4 flex justify-between items-center sticky top-0 z-50 shadow-lg">
            <div class="flex items-center gap-3">
                <a href="<?= base_url('pasien/dashboard') ?>" class="bg-gray-700 p-2 rounded-lg hover:bg-gray-600 transition group" title="Kembali ke Dashboard">
                    <i class="fas fa-arrow-left text-white group-hover:-translate-x-1 transition-transform"></i>
                </a>
                <div>
                    <h1 class="text-xl font-bold tracking-tight leading-none">Status <span class="text-cyan-400">Antrian</span></h1>
                    <p class="text-xs text-gray-400">Portal Pasien</p>
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
            <div class="max-w-5xl mx-auto">

                <div class="mb-8 flex flex-col md:flex-row md:items-end justify-between gap-4">
                    <div>
                        <h2 class="text-3xl font-bold text-white mb-2">Tiket Antrian Anda</h2>
                        <div class="flex items-center gap-2 text-gray-400 text-sm">
                            <i class="fas fa-info-circle"></i>
                            <span>Pantau nomor antrian Anda secara real-time di sini.</span>
                        </div>
                    </div>
                </div>

                <div class="grid grid-cols-1 gap-6">

                    <?php if (!empty($queues)): ?>
                        <?php foreach ($queues as $q): ?>
                            <?php
                            $statusColor = 'gray';
                            $statusIcon = 'fa-clock';
                            $cardBorder = 'border-gray-700';

                            if ($q->status == 'calling') {
                                $statusColor = 'blue'; // Tailwind color name logic
                                $statusIcon = 'fa-volume-up';
                                $cardBorder = 'border-blue-500 shadow-[0_0_15px_rgba(59,130,246,0.5)]';
                            } elseif ($q->status == 'done') {
                                $statusColor = 'emerald';
                                $statusIcon = 'fa-check-circle';
                                $cardBorder = 'border-emerald-500/50';
                            } else {
                                // Waiting
                                $statusColor = 'amber';
                                $statusIcon = 'fa-hourglass-half';
                                $cardBorder = 'border-amber-500/50';
                            }
                            ?>

                            <div class="bg-gray-800 rounded-2xl overflow-hidden shadow-xl border <?= $cardBorder ?> transition-all duration-300 hover:transform hover:-translate-y-1 relative">

                                <div class="absolute -left-3 top-1/2 -translate-y-1/2 w-6 h-6 bg-gray-900 rounded-full border-r border-gray-700"></div>
                                <div class="absolute -right-3 top-1/2 -translate-y-1/2 w-6 h-6 bg-gray-900 rounded-full border-l border-gray-700"></div>

                                <div class="flex flex-col md:flex-row">

                                    <div class="bg-gray-700/30 p-6 md:w-1/4 flex flex-col items-center justify-center border-b md:border-b-0 md:border-r border-gray-700 border-dashed">
                                        <p class="text-gray-400 text-xs uppercase tracking-widest mb-1">Nomor Antrian</p>
                                        <h3 class="text-4xl md:text-5xl font-mono font-bold text-white tracking-tighter">
                                            <?= str_pad($q->queue_number, 3, '0', STR_PAD_LEFT) ?>
                                        </h3>
                                        <?php if ($q->status == 'calling'): ?>
                                            <span class="mt-2 inline-flex items-center gap-1 bg-blue-500 text-white px-2 py-1 rounded text-xs font-bold animate-pulse">
                                                <i class="fas fa-volume-up"></i> Dipanggil
                                            </span>
                                        <?php endif; ?>
                                    </div>

                                    <div class="p-6 md:w-3/4 flex flex-col md:flex-row justify-between items-center gap-4">

                                        <div class="space-y-2 text-center md:text-left w-full">
                                            <div>
                                                <p class="text-xs text-gray-500 uppercase">Poliklinik</p>
                                                <p class="text-xl font-bold text-cyan-400"><?= esc($q->department) ?></p>
                                            </div>
                                            <div class="flex items-center justify-center md:justify-start gap-4 text-sm text-gray-400">
                                                <span class="flex items-center gap-2">
                                                    <i class="far fa-calendar-alt"></i> <?= date('d M Y', strtotime($q->schedule_date)) ?>
                                                </span>
                                                <span class="flex items-center gap-2">
                                                    <i class="far fa-clock"></i> <?= date('H:i', strtotime($q->created_at ?? 'now')) ?> WIB
                                                </span>
                                            </div>
                                        </div>

                                        <div class="flex flex-col items-center md:items-end gap-3 w-full md:w-auto">

                                            <?php if ($q->status == 'waiting'): ?>
                                                <span class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-amber-500/10 text-amber-400 border border-amber-500/20 text-sm font-bold">
                                                    <span class="w-2 h-2 rounded-full bg-amber-500 animate-pulse"></span>
                                                    Menunggu Giliran
                                                </span>
                                            <?php elseif ($q->status == 'calling'): ?>
                                                <span class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-blue-500/10 text-blue-400 border border-blue-500/20 text-sm font-bold animate-soft-pulse">
                                                    <i class="fas fa-bullhorn"></i> Sedang Dipanggil
                                                </span>
                                            <?php elseif ($q->status == 'done'): ?>
                                                <span class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-emerald-500/10 text-emerald-400 border border-emerald-500/20 text-sm font-bold">
                                                    <i class="fas fa-check"></i> Selesai
                                                </span>
                                            <?php endif; ?>

                                            <?php if ($q->status == 'done'): ?>
                                                <a href="<?= base_url('pasien/detail_pemeriksaan/' . $q->appointment_id) ?>"
                                                    class="inline-flex items-center gap-2 bg-emerald-600 hover:bg-emerald-500 text-white px-5 py-2.5 rounded-lg font-semibold transition shadow-lg shadow-emerald-900/20 active:scale-95 border border-emerald-500/50 w-full md:w-auto justify-center">
                                                    <i class="fas fa-file-medical-alt"></i>
                                                    Lihat Hasil
                                                </a>
                                            <?php else: ?>
                                                <button disabled class="inline-flex items-center gap-2 bg-gray-700 text-gray-500 px-5 py-2.5 rounded-lg font-semibold cursor-not-allowed border border-gray-600 w-full md:w-auto justify-center">
                                                    <i class="fas fa-hourglass-half"></i>
                                                    Mohon Tunggu...
                                                </button>
                                            <?php endif; ?>

                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>

                    <?php else: ?>
                        <div class="bg-gray-800 rounded-2xl shadow-xl border border-gray-700 p-12 text-center flex flex-col items-center justify-center">
                            <div class="bg-gray-700/50 p-6 rounded-full mb-4">
                                <i class="fas fa-ticket-alt text-4xl text-gray-500"></i>
                            </div>
                            <h3 class="text-xl font-bold text-white mb-2">Tidak Ada Antrian Aktif</h3>
                            <p class="text-gray-400 mb-6 max-w-md mx-auto">Anda belum mendaftar antrian hari ini. Silakan lakukan pendaftaran booking terlebih dahulu.</p>
                            <a href="<?= base_url('pasien/booking') ?>" class="inline-flex items-center gap-2 bg-cyan-600 hover:bg-cyan-500 text-white px-6 py-3 rounded-lg font-bold transition shadow-lg shadow-cyan-900/20">
                                <i class="fas fa-plus-circle"></i> Buat Janji Temu
                            </a>
                        </div>
                    <?php endif; ?>

                </div>
            </div>
        </main>
    </div>

</body>

</html>