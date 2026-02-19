<?= $this->extend('Pendaftaran/layout/main') ?>
<?= $this->section('content') ?>

<h3 class="text-xl font-semibold text-gray-200 mb-6">
    Pendaftaran Pasien Online
</h3>

<div class="bg-gray-800 rounded-2xl border border-gray-700 overflow-hidden shadow-xl">

    <table class="w-full text-left">
        <thead class="bg-gray-700 text-gray-400 text-xs uppercase tracking-wider">
            <tr>
                <th class="px-6 py-4">Nama Pasien</th>
                <th class="px-6 py-4">Poli</th>
                <th class="px-6 py-4">Status</th>
                <th class="px-6 py-4 text-center">Aksi</th>
            </tr>
        </thead>

        <tbody class="divide-y divide-gray-700">
            <?php if (!empty($dataPasien)): ?>
                <?php foreach ($dataPasien as $row): ?>
                <tr class="hover:bg-gray-700/40 transition duration-200">

                    <!-- NAMA -->
                    <td class="px-6 py-4 text-gray-200">
                        <?= esc($row['full_name']) ?>
                    </td>

                    <!-- POLI -->
                    <td class="px-6 py-4 text-gray-300">
                        <?= esc($row['department_name']) ?>
                    </td>

                    <!-- STATUS -->
                    <td class="px-6 py-4">
                        <span class="px-3 py-1 rounded-full text-xs font-semibold
                            <?= $row['status']=='waiting'
                                ? 'bg-yellow-500/10 text-yellow-400'
                                : 'bg-green-500/10 text-green-400' ?>">
                            <?= ucfirst($row['status']) ?>
                        </span>
                    </td>

                    <!-- AKSI -->
                    <td class="px-6 py-4 text-center">
                        <?php if ($row['status']=='waiting'): ?>
                            <a href="<?= base_url('pendaftaran/konfirmasi/'.$row['appointment_id']) ?>"
                               class="bg-emerald-600 hover:bg-emerald-500 transition px-4 py-2 rounded-lg text-sm font-semibold text-white shadow-md">
                                ✅ Konfirmasi
                            </a>
                        <?php else: ?>
                            <span class="bg-gray-600 px-4 py-2 rounded-lg text-sm text-gray-300 cursor-not-allowed">
                                Sudah dikonfirmasi
                            </span>
                        <?php endif ?>
                    </td>

                </tr>
                <?php endforeach ?>
            <?php else: ?>
                <tr>
                    <td colspan="4" class="text-center text-gray-400 py-8">
                        Belum ada pasien yang mendaftar
                    </td>
                </tr>
            <?php endif ?>
        </tbody>

    </table>

</div>

<?= $this->endSection() ?>
