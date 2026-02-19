<?= $this->extend('Pendaftaran/layout/main') ?>
<?= $this->section('content') ?>

<h3 class="text-xl font-semibold text-gray-200 mb-6">
    Dashboard Pendaftaran
</h3>

<!-- ================= SUMMARY CARDS ================= -->
<div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">

    <div class="bg-gray-800 p-6 rounded-2xl border border-gray-700 shadow-md">
        <p class="text-sm text-gray-400">Total Pendaftaran Hari Ini</p>
        <h2 class="text-3xl font-bold text-blue-400"><?= $totalPendaftaran ?></h2>
    </div>

    <div class="bg-gray-800 p-6 rounded-2xl border border-gray-700 shadow-md">
        <p class="text-sm text-gray-400">Menunggu Verifikasi</p>
        <h2 class="text-3xl font-bold text-yellow-400"><?= $totalWaiting ?></h2>
    </div>

    <div class="bg-gray-800 p-6 rounded-2xl border border-gray-700 shadow-md">
        <p class="text-sm text-gray-400">Total Antrian Hari Ini</p>
        <h2 class="text-3xl font-bold text-green-400"><?= $totalQueue ?></h2>
    </div>

</div>

<!-- ================= PENDAFTARAN ONLINE TERBARU ================= -->
<div class="bg-gray-800 rounded-2xl border border-gray-700 overflow-hidden mb-8 shadow-xl">
    <h4 class="font-bold mb-4 text-blue-400 px-6 pt-6">Pendaftaran Online Terbaru</h4>

    <table class="w-full text-left">
        <thead class="bg-gray-700 text-gray-400 text-xs uppercase tracking-wider">
            <tr>
                <th class="px-6 py-3">Nama Pasien</th>
                <th class="px-6 py-3">Poli</th>
                <th class="px-6 py-3">Status</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-700">
            <?php if (!empty($pendaftaranTerbaru)): ?>
                <?php foreach ($pendaftaranTerbaru as $item): ?>
                <tr class="hover:bg-gray-700/40 transition duration-200">
                    <td class="px-6 py-3 text-gray-200"><?= esc($item['full_name']) ?></td>
                    <td class="px-6 py-3 text-gray-300"><?= esc($item['department_name']) ?></td>
                    <td class="px-6 py-3">
                        <span class="px-3 py-1 rounded-full text-xs font-semibold
                            <?= $item['status']=='waiting'
                                ? 'bg-yellow-500/10 text-yellow-400'
                                : 'bg-green-500/10 text-green-400' ?>">
                            <?= ucfirst($item['status']) ?>
                        </span>
                    </td>
                </tr>
                <?php endforeach ?>
            <?php else: ?>
                <tr>
                    <td colspan="3" class="text-center text-gray-400 py-6">
                        Belum ada pendaftaran
                    </td>
                </tr>
            <?php endif ?>
        </tbody>
    </table>
</div>

<!-- ================= ANTRIAN TERBARU ================= -->
<div class="bg-gray-800 rounded-2xl border border-gray-700 overflow-hidden shadow-xl">
    <h4 class="font-bold mb-4 text-green-400 px-6 pt-6">Antrian Terbaru</h4>

    <table class="w-full text-left">
        <thead class="bg-gray-700 text-gray-400 text-xs uppercase tracking-wider">
            <tr>
                <th class="px-6 py-3">No Antrian</th>
                <th class="px-6 py-3">Nama Pasien</th>
                <th class="px-6 py-3">Poli</th>
                <th class="px-6 py-3">Status</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-700">
            <?php if (!empty($antrianTerbaru)): ?>
                <?php foreach ($antrianTerbaru as $queue): ?>
                <tr class="hover:bg-gray-700/40 transition duration-200">
                    <td class="px-6 py-3 font-mono font-bold text-blue-400">
                        <?= str_pad($queue['queue_number'], 3, '0', STR_PAD_LEFT) ?>
                    </td>
                    <td class="px-6 py-3 text-gray-200"><?= esc($queue['full_name']) ?></td>
                    <td class="px-6 py-3 text-gray-300"><?= esc($queue['department_name']) ?></td>
                    <td class="px-6 py-3">
                        <span class="px-3 py-1 rounded-full text-xs font-semibold
                            <?= $queue['status']=='waiting'
                                ? 'bg-yellow-500/10 text-yellow-400'
                                : ($queue['status']=='done'
                                    ? 'bg-green-500/10 text-green-400'
                                    : 'bg-gray-600 text-gray-300') ?>">
                            <?= ucfirst($queue['status']) ?>
                        </span>
                    </td>
                </tr>
                <?php endforeach ?>
            <?php else: ?>
                <tr>
                    <td colspan="4" class="text-center text-gray-400 py-6">
                        Belum ada antrian
                    </td>
                </tr>
            <?php endif ?>
        </tbody>
    </table>
</div>

<?= $this->endSection() ?>
