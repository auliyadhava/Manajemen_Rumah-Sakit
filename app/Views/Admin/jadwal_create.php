<?= $this->extend('admin/layout/main') ?>

<?= $this->section('content') ?>
<div class="container-fluid">

    <div class="card shadow mb-4" style="max-width: 800px; margin: auto;">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Tambah Jadwal Dokter Baru</h6>
        </div>
        <div class="card-body">

            <form action="<?= base_url('admin/jadwal/store') ?>" method="post">
                <?= csrf_field() ?>

                <div class="mb-3">
                    <label class="form-label">Nama Dokter</label>
                    <select name="doctor_id" class="form-select" required>
                        <option value="">-- Pilih Dokter --</option>
                        <?php foreach ($doctors as $doc) : ?>
                            <option value="<?= $doc['doctor_id'] ?>">
                                <?= $doc['full_name'] ?> - (Poli <?= $doc['poli_name'] ?>)
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Hari Praktek</label>
                        <select name="day" class="form-select" required>
                            <option value="Senin">Senin</option>
                            <option value="Selasa">Selasa</option>
                            <option value="Rabu">Rabu</option>
                            <option value="Kamis">Kamis</option>
                            <option value="Jumat">Jumat</option>
                            <option value="Sabtu">Sabtu</option>
                            <option value="Minggu">Minggu</option>
                        </select>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label">Pilih Sif (Shift)</label>
                        <select name="shift" class="form-select" required>
                            <option value="Pagi">Pagi (08:00 - 12:00)</option>
                            <option value="Siang">Siang (13:00 - 17:00)</option>
                            <option value="Malam">Malam (18:00 - 21:00)</option>
                        </select>
                        <small class="text-muted">Pilih kategori waktu untuk memudahkan pasien.</small>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-4 mb-3">
                        <label class="form-label">Jam Mulai</label>
                        <input type="time" name="start_time" class="form-control" required>
                    </div>

                    <div class="col-md-4 mb-3">
                        <label class="form-label">Jam Selesai</label>
                        <input type="time" name="end_time" class="form-control" required>
                    </div>

                    <div class="col-md-4 mb-3">
                        <label class="form-label">Kuota Pasien</label>
                        <input type="number" name="quota" class="form-control" value="20">
                    </div>
                </div>

                <hr>
                <div class="d-flex justify-content-end">
                    <a href="<?= base_url('admin/jadwal') ?>" class="btn btn-secondary me-2">Batal</a>
                    <button type="submit" class="btn btn-primary">Simpan Jadwal</button>
                </div>

            </form>
        </div>
    </div>

</div>
<?= $this->endSection() ?>