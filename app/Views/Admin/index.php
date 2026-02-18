<?= $this->extend('Admin/Layout/main') ?>

<?= $this->section('content') ?>

<div class="container-fluid">
    
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="mb-0 fw-bold text-dark">Dashboard Utama</h2>
            <p class="text-muted">Ringkasan aktivitas rumah sakit hari ini</p>
        </div>
        <span class="badge bg-primary px-3 py-2 fs-6 shadow-sm">
            <i class="far fa-calendar-alt me-2"></i> <?= date('d M Y') ?>
        </span>
    </div>

    <div class="row mb-4 g-3">
        <div class="col-md-3">
            <div class="card card-stat p-3 bg-white border-start border-primary border-4">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <small class="text-muted fw-bold text-uppercase">Pasien Hari Ini</small>
                        <h2 class="text-primary mb-0 fw-bold"><?= number_format($stats['pasien']) ?></h2>
                    </div>
                    <div class="bg-primary bg-opacity-10 p-3 rounded-circle text-primary">
                        <i class="fas fa-user-injured fa-lg"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card card-stat p-3 bg-white border-start border-success border-4">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <small class="text-muted fw-bold text-uppercase">Pemeriksaan</small>
                        <h2 class="text-success mb-0 fw-bold"><?= number_format($stats['pemeriksaan']) ?></h2>
                    </div>
                    <div class="bg-success bg-opacity-10 p-3 rounded-circle text-success">
                        <i class="fas fa-stethoscope fa-lg"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card card-stat p-3 bg-white border-start border-warning border-4">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <small class="text-muted fw-bold text-uppercase">Pembayaran</small>
                        <h2 class="text-warning mb-0 fw-bold"><?= number_format($stats['pembayaran']) ?></h2>
                    </div>
                    <div class="bg-warning bg-opacity-10 p-3 rounded-circle text-warning">
                        <i class="fas fa-file-invoice-dollar fa-lg"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card card-stat p-3 bg-white border-start border-info border-4">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <small class="text-muted fw-bold text-uppercase">Obat Keluar</small>
                        <h2 class="text-info mb-0 fw-bold"><?= number_format($stats['obat']) ?></h2>
                    </div>
                    <div class="bg-info bg-opacity-10 p-3 rounded-circle text-info">
                        <i class="fas fa-pills fa-lg"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-8 mb-4">
            <div class="card card-stat shadow-sm h-100">
                <div class="card-header bg-white py-3 border-0 d-flex justify-content-between align-items-center">
                    <h5 class="mb-0 fw-bold text-gray-800">Aktivitas Pemeriksaan Terakhir</h5>
                    <a href="<?= base_url('admin/hasil-pemeriksaan') ?>" class="btn btn-sm btn-light text-primary fw-bold">Lihat Semua</a>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="bg-light">
                                <tr>
                                    <th class="ps-4">Pasien</th>
                                    <th>Poliklinik</th>
                                    <th>Diagnosa</th>
                                    <th class="text-end pe-4">Waktu</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (!empty($riwayat)): ?>
                                    <?php foreach ($riwayat as $r): ?>
                                        <tr>
                                            <td class="ps-4 fw-bold text-dark"><?= esc($r['pasien']) ?></td>
                                            <td>
                                                <span class="badge bg-primary bg-opacity-10 text-primary badge-poli border border-primary border-opacity-25">
                                                    <?= esc($r['poli']) ?>
                                                </span>
                                            </td>
                                            <td><?= esc($r['diagnosis']) ?></td>
                                            <td class="text-end pe-4 text-muted">
                                                <i class="far fa-clock me-1"></i> <?= date('H:i', strtotime($r['exam_date'])) ?>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="4" class="text-center text-muted py-5">
                                            <i class="fas fa-clipboard-list fa-2x mb-3 text-secondary"></i><br>
                                            Belum ada aktivitas pemeriksaan hari ini
                                        </td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-4 mb-4">
            <div class="card card-stat shadow-sm h-100">
                <div class="card-header bg-white py-3 border-0">
                    <h5 class="mb-0 fw-bold text-gray-800">Statistik Kunjungan Poli</h5>
                </div>
                <div class="card-body">
                    <div style="position: relative; height:250px;">
                        <canvas id="myChart"></canvas>
                    </div>
                    <hr class="my-4">
                    <div class="small text-muted d-flex align-items-start">
                        <i class="fas fa-info-circle me-2 mt-1 text-primary"></i> 
                        <span>Data ini menampilkan persentase kunjungan pasien berdasarkan Poliklinik hari ini.</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        const ctx = document.getElementById('myChart');

        // Data dinamis dari Controller (Jika ada), kalau null pakai dummy
        // Pastikan controller mengirim variabel $chartLabels dan $chartData
        const labels = <?= json_encode($chartLabels ?? ['Umum', 'Gigi', 'Anak', 'THT']) ?>;
        const dataValues = <?= json_encode($chartData ?? [10, 5, 8, 3]) ?>;

        new Chart(ctx, {
            type: 'doughnut',
            data: {
                labels: labels,
                datasets: [{
                    data: dataValues,
                    backgroundColor: ['#4e73df', '#1cc88a', '#36b9cc', '#f6c23e', '#e74a3b'],
                    hoverOffset: 4,
                    borderWidth: 2,
                    borderColor: '#ffffff'
                }]
            },
            options: {
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: {
                            padding: 20,
                            usePointStyle: true,
                            pointStyle: 'circle'
                        }
                    },
                    tooltip: {
                        backgroundColor: "rgb(255,255,255)",
                        bodyColor: "#858796",
                        borderColor: '#dddfeb',
                        borderWidth: 1,
                        xPadding: 15,
                        yPadding: 15,
                        displayColors: false,
                        caretPadding: 10,
                    },
                },
                cutout: '75%',
            }
        });
    });
</script>

<?= $this->endSection() ?>