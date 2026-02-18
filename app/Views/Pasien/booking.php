<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Booking Online - RS Sejahtera</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f0f4f8;
            margin: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            /* Gunakan min-height agar tidak terpotong saat konten panjang */
            padding: 20px 0;
        }

        .form-container {
            background: white;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 500px;
            /* Lebarkan sedikit agar muat kartu jadwal */
        }

        h2 {
            color: #2c3e50;
            margin-top: 0;
            text-align: center;
            border-bottom: 2px solid #27ae60;
            padding-bottom: 10px;
        }

        .alert {
            padding: 10px;
            border-radius: 6px;
            margin-bottom: 15px;
            text-align: center;
            font-size: 14px;
        }

        .alert-error {
            background: #fdecea;
            color: #c0392b;
        }

        .alert-success {
            background: #eafaf1;
            color: #27ae60;
        }

        .form-group {
            margin-bottom: 20px;
        }

        label {
            display: block;
            margin-bottom: 8px;
            font-weight: 600;
            color: #34495e;
        }

        input[type="date"],
        select {
            width: 100%;
            padding: 12px;
            border: 1px solid #dcdfe6;
            border-radius: 6px;
            box-sizing: border-box;
            font-size: 14px;
        }

        /* --- CSS BARU UNTUK JADWAL --- */
        .schedule-container {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(130px, 1fr));
            gap: 10px;
            margin-top: 10px;
        }

        .schedule-card {
            border: 2px solid #e0e0e0;
            border-radius: 8px;
            padding: 10px;
            text-align: center;
            cursor: pointer;
            transition: all 0.3s;
            background-color: #fafafa;
        }

        .schedule-card:hover {
            border-color: #27ae60;
            background-color: #f0fff4;
        }

        .schedule-card.selected {
            border-color: #27ae60;
            background-color: #27ae60;
            color: white;
            font-weight: bold;
        }

        .schedule-card .day {
            font-size: 0.9em;
            text-transform: uppercase;
        }

        .schedule-card .shift {
            font-size: 1.1em;
            margin: 5px 0;
            font-weight: bold;
        }

        .schedule-card .time {
            font-size: 0.8em;
            opacity: 0.8;
        }

        button {
            width: 100%;
            background-color: #27ae60;
            color: white;
            padding: 12px;
            border: none;
            border-radius: 6px;
            font-size: 16px;
            font-weight: bold;
            cursor: pointer;
            margin-top: 10px;
        }

        button:hover {
            background-color: #219150;
        }

        .btn-cancel {
            display: block;
            text-align: center;
            margin-top: 15px;
            text-decoration: none;
            color: #7f8c8d;
            font-size: 14px;
        }

        .loading {
            font-style: italic;
            color: #999;
            font-size: 0.9em;
        }
    </style>
</head>

<body>

    <div class="form-container">
        <h2>🏥 Booking Online</h2>

        <?php if (session()->getFlashdata('error')): ?>
            <div class="alert alert-error">
                <?= session()->getFlashdata('error') ?>
            </div>
        <?php endif; ?>

        <?php if (session()->getFlashdata('success')): ?>
            <div class="alert alert-success">
                <?= session()->getFlashdata('success') ?>
            </div>
        <?php endif; ?>

        <form method="post" action="<?= base_url('pasien/booking/store') ?>">
            <?= csrf_field() ?>

            <div class="form-group">
                <label>Tanggal Rencana Kunjungan</label>
                <input
                    type="date"
                    id="appointment_date"
                    name="schedule_date" min="<?= date('Y-m-d') ?>"
                    required>
                <small style="color: #666; font-size: 12px;">*Pastikan tanggal sesuai dengan hari praktek dokter</small>
            </div>

            <div class="form-group">
                <label>Pilih Poliklinik</label>
                <select name="department_id" id="department" required>
                    <option value="">-- Pilih Poli --</option>
                    <?php foreach ($departments as $d): ?>
                        <option value="<?= $d->department_id ?>">
                            <?= esc($d->name) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="form-group">
                <label>Pilih Dokter</label>
                <select name="doctor_id" id="doctor" disabled required>
                    <option value="">-- Pilih Poli Terlebih Dahulu --</option>
                </select>
            </div>

            <div class="form-group">
                <label>Pilih Jadwal Sif</label>
                <div id="schedule_loading" class="loading" style="display:none;">Memuat jadwal...</div>
                <div id="schedule_container" class="schedule-container">
                    <p style="color:#aaa; font-size:14px; grid-column: 1/-1;">Silakan pilih dokter untuk melihat jadwal.</p>
                </div>

                <input type="hidden" name="schedule_id" id="schedule_id" required>
            </div>

            <button type="submit">Konfirmasi Pendaftaran</button>
            <a href="<?= base_url('pasien/riwayat') ?>" class="btn-cancel">
                Batal & Kembali
            </a>
        </form>
    </div>

    <script>
        // Konfigurasi Base URL (sesuaikan jika ada folder sub-project)
        const BASE_URL = "<?= base_url() ?>";

        const deptSelect = document.getElementById('department');
        const docSelect = document.getElementById('doctor');
        const schedContainer = document.getElementById('schedule_container');
        const schedInput = document.getElementById('schedule_id');
        const loadingText = document.getElementById('schedule_loading');

        // === 1. Saat Poli Dipilih ===
        deptSelect.addEventListener('change', function() {
            const deptId = this.value;

            // Reset Dropdown Dokter & Jadwal
            docSelect.innerHTML = '<option value="">Memuat...</option>';
            docSelect.disabled = true;
            resetSchedule();

            if (deptId) {
                // Panggil Controller getDoctorsByDept
                fetch(`${BASE_URL}/appointment/getDoctorsByDept/${deptId}`)
                    .then(response => response.json())
                    .then(data => {
                        let options = '<option value="">-- Pilih Dokter --</option>';
                        data.forEach(doc => {
                            options += `<option value="${doc.doctor_id}">${doc.full_name}</option>`;
                        });
                        docSelect.innerHTML = options;
                        docSelect.disabled = false;
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        docSelect.innerHTML = '<option value="">Gagal memuat dokter</option>';
                    });
            } else {
                docSelect.innerHTML = '<option value="">-- Pilih Poli Terlebih Dahulu --</option>';
            }
        });

        // === 2. Saat Dokter Dipilih ===
        docSelect.addEventListener('change', function() {
            const docId = this.value;
            resetSchedule();

            if (docId) {
                loadingText.style.display = 'block';
                schedContainer.innerHTML = '';

                // Panggil Controller getSchedulesByDoctor
                fetch(`${BASE_URL}/appointment/getSchedulesByDoctor/${docId}`)
                    .then(response => response.json())
                    .then(data => {
                        loadingText.style.display = 'none';

                        if (data.length === 0) {
                            schedContainer.innerHTML = '<p style="color:#e74c3c; grid-column: 1/-1;">Dokter ini belum memiliki jadwal aktif.</p>';
                            return;
                        }

                        // Render Kartu Jadwal
                        data.forEach(sch => {
                            // Potong detik (08:00:00 -> 08:00)
                            let start = sch.start_time.substring(0, 5);
                            let end = sch.end_time.substring(0, 5);

                            const card = document.createElement('div');
                            card.className = 'schedule-card';
                            card.innerHTML = `
                        <div class="day">${sch.day}</div>
                        <div class="shift">${sch.shift}</div>
                        <div class="time"><i class="far fa-clock"></i> ${start}-${end}</div>
                    `;

                            // Event Klik Kartu
                            card.addEventListener('click', function() {
                                // Hapus selected dari kartu lain
                                document.querySelectorAll('.schedule-card').forEach(c => c.classList.remove('selected'));
                                // Tambah selected ke kartu ini
                                this.classList.add('selected');
                                // Simpan ke input hidden
                                schedInput.value = sch.schedule_id;
                            });

                            schedContainer.appendChild(card);
                        });
                    })
                    .catch(error => {
                        loadingText.style.display = 'none';
                        schedContainer.innerHTML = '<p style="color:red">Gagal memuat jadwal.</p>';
                    });
            }
        });

        function resetSchedule() {
            schedContainer.innerHTML = '<p style="color:#aaa; font-size:14px; grid-column: 1/-1;">Silakan pilih dokter untuk melihat jadwal.</p>';
            schedInput.value = '';
            loadingText.style.display = 'none';
        }
    </script>

</body>

</html>