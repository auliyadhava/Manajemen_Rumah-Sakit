<?= $this->extend('Admin/Layout/main') ?>

<?= $this->section('content') ?>

<div class="p-6">

    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-8">
        <div>
            <h2 class="text-3xl font-bold text-white mb-1">Hasil Pemeriksaan</h2>
            <p class="text-gray-400 text-sm">Rekapitulasi diagnosa dan pemeriksaan pasien.</p>
        </div>

        <div class="flex flex-wrap gap-3">
            <a href="<?= base_url('admin/hasil/print') ?>"
                target="_blank"
                class="inline-flex items-center gap-2 bg-gray-700 hover:bg-gray-600 text-gray-200 px-4 py-2.5 rounded-lg font-semibold transition border border-gray-600 shadow-lg">
                <i class="fas fa-print"></i>
                <span>Print</span>
            </a>

            <a href="<?= base_url('admin/hasil/export') ?>"
                class="inline-flex items-center gap-2 bg-emerald-600 hover:bg-emerald-500 text-white px-4 py-2.5 rounded-lg font-semibold transition shadow-lg shadow-emerald-900/20 active:scale-95 border border-emerald-500/50">
                <i class="fas fa-file-excel"></i>
                <span>Export Excel</span>
            </a>
        </div>
    </div>

    <div class="bg-gray-800 rounded-2xl shadow-xl border border-gray-700 overflow-hidden">

        <div class="h-1 bg-gradient-to-r from-blue-500 to-purple-500"></div>

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-gray-700/50 text-gray-400 text-xs uppercase tracking-wider border-b border-gray-700">
                        <th class="px-6 py-4 font-bold">Tanggal</th>
                        <th class="px-6 py-4 font-bold">Nama Pasien</th>
                        <th class="px-6 py-4 font-bold">Dokter & Poli</th>
                        <th class="px-6 py-4 font-bold">Diagnosa</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-700">
                    <?php if (!empty($data)): ?>
                        <?php foreach ($data as $row): ?>
                            <tr class="hover:bg-gray-700/30 transition-colors group">

                                <td class="px-6 py-5 whitespace-nowrap">
                                    <div class="flex items-center gap-3">
                                        <div class="bg-gray-700/50 p-2 rounded text-gray-400 group-hover:text-blue-400 transition">
                                            <i class="far fa-calendar-alt"></i>
                                        </div>
                                        <span class="text-gray-300 font-medium">
                                            <?= date('d M Y', strtotime($row->exam_date)) ?>
                                        </span>
                                    </div>
                                </td>

                                <td class="px-6 py-5">
                                    <div class="font-bold text-white text-base mb-0.5">
                                        <?= esc($row->patient_name) ?>
                                    </div>
                                </td>

                                <td class="px-6 py-5">
                                    <div class="flex flex-col">
                                        <span class="text-white font-medium text-sm">
                                            <i class="fas fa-user-md text-blue-400 mr-1.5"></i> <?= esc($row->doctor_name) ?>
                                        </span>
                                        <span class="text-gray-500 text-xs ml-5 mt-1">
                                            <?= esc($row->department_name) ?>
                                        </span>
                                    </div>
                                </td>

                                <td class="px-6 py-5">
                                    <div class="bg-blue-900/20 text-blue-300 px-3 py-2 rounded-lg border border-blue-500/20 text-sm inline-block max-w-xs truncate" title="<?= esc($row->diagnosis) ?>">
                                        <?= esc($row->diagnosis) ?>
                                    </div>
                                </td>

                            </tr>
                        <?php endforeach ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="5" class="px-6 py-16 text-center">
                                <div class="flex flex-col items-center justify-center opacity-50">
                                    <div class="bg-gray-700 p-6 rounded-full mb-4">
                                        <i class="fas fa-folder-open text-4xl text-gray-400"></i>
                                    </div>
                                    <h3 class="text-xl font-bold text-gray-300">Belum ada data</h3>
                                    <p class="text-sm text-gray-500 mt-1">Data hasil pemeriksaan pasien akan muncul di sini.</p>
                                </div>
                            </td>
                        </tr>
                    <?php endif ?>
                </tbody>
            </table>
        </div>

        <div class="p-4 border-t border-gray-700 bg-gray-800/50 text-xs text-gray-500 flex justify-end">
            Total Data: <?= count($data) ?>
        </div>
    </div>
</div>

<?= $this->endSection() ?>