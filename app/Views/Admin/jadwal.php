<?= $this->extend('admin/layout/main') ?>

<?= $this->section('content') ?>
<div class="container-fluid">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3 class="h3 mb-0 text-gray-800">Manajemen Jadwal Dokter</h3>
        <a href="<?= base_url('admin/jadwal/create') ?>" class="btn btn-primary">
            <i class="bi bi-plus-circle"></i> Tambah Jadwal
        </a>
    </div>

    <?php if (session()->getFlashdata('success')) : ?>
        <div class="alert alert-success"><?= session()->getFlashdata('success') ?></div>
    <?php endif; ?>

    <div class="card shadow mb-4">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" width="100%" cellspacing="0">
                    <thead class="table-light">
                        <tr>
                            <th>No</th>
                            <th>Nama Dokter</th>
                            <th>Poli</th>
                            <th>Hari</th>
                            <th>Sif</th>
                            <th>Jam Praktek</th>
                            <th>Kuota</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $i = 1; foreach ($jadwal as $row) : ?>
                            <tr>
                                <td><?= $i++ ?></td>
                                <td><?= esc($row['doctor_name']) ?></td>
                                <td><?= esc($row['poli_name']) ?></td>
                                <td>
                                    <span class="badge bg-secondary"><?= $row['day'] ?></span>
                                </td>
                                <td>
                                    <?php 
                                        $badge = $row['shift'] == 'Pagi' ? 'info' : ($row['shift'] == 'Siang' ? 'warning' : 'dark');
                                    ?>
                                    <span class="badge bg-<?= $badge ?>"><?= $row['shift'] ?></span>
                                </td>
                                <td>
                                    <?= substr($row['start_time'], 0, 5) ?> - <?= substr($row['end_time'], 0, 5) ?>
                                </td>
                                <td><?= $row['quota'] ?> Pasien</td>
                                <td>
                                    <form action="<?= base_url('admin/jadwal/delete/' . $row['schedule_id']) ?>" method="post" class="d-inline">
                                        <?= csrf_field() ?>
                                        <input type="hidden" name="_method" value="DELETE">
                                        <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Hapus jadwal ini?')">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</div>
<?= $this->endSection() ?>