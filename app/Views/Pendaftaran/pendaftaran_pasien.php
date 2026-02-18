<?= $this->extend('Pendaftaran/layout/main') ?>
<?= $this->section('content') ?>

<div class="container-fluid">

    <div class="mb-4">
        <h3 class="fw-bold">Pendaftaran Pasien</h3>
        <p class="text-muted">Daftar pasien yang melakukan pendaftaran online & pemilihan jadwal</p>
    </div>

    <div class="card shadow-sm">
        <div class="card-body">

            <div class="row mb-3">
                <div class="col-md-4">
                    <input type="text" class="form-control" placeholder="Cari pasien...">
                </div>
            </div>

            <div class="table-responsive">
                <table class="table table-bordered table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>Nama Pasien</th>
                            <th>Dokter Tujuan</th>
                            <th>Jadwal Sif</th>
                            <th>Poli</th>
                            <th>Status</th>
                            <th width="180">Aksi</th>
                        </tr>
                    </thead>

                    <tbody>
                        <?php if (!empty($dataPasien)): ?>
                            <?php foreach ($dataPasien as $row): ?>
                                <tr>
                                    <td>
                                        <span class="fw-bold"><?= esc($row['patient_name']) ?></span><br>
                                        <small class="text-muted">No. Booking: #<?= $row['booking_queue'] ?></small>
                                    </td>

                                    <td><?= esc($row['doctor_name']) ?></td>

                                    <td>
                                        <span class="badge bg-info text-dark mb-1"><?= $row['day'] ?> - <?= $row['shift'] ?></span><br>
                                        <small><?= date('d M Y', strtotime($row['appointment_date'])) ?></small><br>
                                        <small class="text-muted">
                                            <?= substr($row['start_time'], 0, 5) ?> - <?= substr($row['end_time'], 0, 5) ?>
                                        </small>
                                    </td>

                                    <td><?= esc($row['department_name']) ?></td>

                                    <td>
                                        <span class="badge bg-<?= $row['status'] === 'waiting' ? 'warning text-dark' : 'success' ?>">
                                            <?= ucfirst($row['status']) ?>
                                        </span>
                                    </td>

                                    <td>
                                        <?php if ($row['status'] === 'waiting'): ?>
                                            <a href="<?= base_url('pendaftaran/konfirmasi/' . $row['appointment_id']) ?>"
                                               class="btn btn-success btn-sm"
                                               onclick="return confirm('Konfirmasi pasien ini dan buat nomor antrian?')">
                                                <i class="bi bi-check-circle"></i> Konfirmasi
                                            </a>
                                        <?php else: ?>
                                            <button class="btn btn-secondary btn-sm" disabled>
                                                <i class="bi bi-check-all"></i> Terkonfirmasi
                                            </button>
                                        <?php endif ?>
                                    </td>
                                </tr>
                            <?php endforeach ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="6" class="text-center text-muted py-4">
                                    <i class="bi bi-inbox display-6"></i><br>
                                    Belum ada data pendaftaran hari ini.
                                </td>
                            </tr>
                        <?php endif ?>
                    </tbody>
                </table>
            </div>

        </div>
    </div>

</div>

<?php if (session()->getFlashdata('queue_ticket')): 
    $ticket = session()->getFlashdata('queue_ticket'); 
?>

<div class="modal fade" id="ticketModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">

            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title">
                    <i class="bi bi-ticket-perforated"></i> Kartu Antrian Fisik
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body text-center p-4">
                <p class="text-uppercase text-muted mb-0">Nomor Antrian</p>
                
                <h1 class="display-2 fw-bold text-primary my-2">
                    <?= $ticket['queue_number'] ?> </h1>

                <h4 class="fw-bold mb-1"><?= esc($ticket['full_name']) ?></h4>
                <p class="mb-3 badge bg-light text-dark border p-2">
                    Poli: <strong><?= esc($ticket['department']) ?></strong>
                </p>

                <div class="border-top pt-3">
                    <small class="text-muted d-block">Tanggal Kunjungan</small>
                    <strong><?= date('d F Y', strtotime($ticket['schedule_date'])) ?></strong>
                </div>
            </div>

            <div class="modal-footer justify-content-center">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                <button type="button" class="btn btn-primary" onclick="window.print()">
                    <i class="bi bi-printer"></i> Cetak Karcis
                </button>
            </div>

        </div>
    </div>
</div>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        var myModal = new bootstrap.Modal(document.getElementById('ticketModal'));
        myModal.show();
    });
</script>

<?php endif ?>

<?= $this->endSection() ?>