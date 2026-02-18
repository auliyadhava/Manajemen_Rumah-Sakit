<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Booking Online | Portal Pasien</title>

    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

    <style>
        /* Agar Date Picker bawaan browser mengikuti tema gelap */
        input[type="date"] {
            color-scheme: dark;
        }

        /* Animasi Fade In */
        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(10px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .animate-fade-in {
            animation: fadeIn 0.4s ease-out forwards;
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
                    <h1 class="text-xl font-bold tracking-tight leading-none">Booking <span class="text-cyan-400">Online</span></h1>
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

        <main class="flex-grow p-6 lg:p-8 flex items-center justify-center">
            <div class="w-full max-w-2xl">

                <?php if (session()->getFlashdata('error')): ?>
                    <div class="mb-6 p-4 bg-red-900/20 border border-red-500/50 text-red-400 rounded-xl flex items-center gap-3 shadow-lg animate-fade-in">
                        <i class="fas fa-exclamation-circle text-xl"></i>
                        <div><?= session()->getFlashdata('error') ?></div>
                    </div>
                <?php endif; ?>

                <?php if (session()->getFlashdata('success')): ?>
                    <div class="mb-6 p-4 bg-emerald-900/20 border border-emerald-500/50 text-emerald-400 rounded-xl flex items-center gap-3 shadow-lg animate-fade-in">
                        <i class="fas fa-check-circle text-xl"></i>
                        <div><?= session()->getFlashdata('success') ?></div>
                    </div>
                <?php endif; ?>

                <div class="bg-gray-800 rounded-2xl shadow-2xl border border-gray-700 overflow-hidden">

                    <div class="p-6 border-b border-gray-700 bg-gray-800/80 backdrop-blur-sm">
                        <h2 class="text-2xl font-bold text-white flex items-center gap-2">
                            <i class="fas fa-calendar-plus text-cyan-400"></i> Buat Janji Temu
                        </h2>
                        <p class="text-gray-400 text-sm mt-1">Isi formulir di bawah untuk mendaftar antrian dokter.</p>
                    </div>

                    <div class="p-6 md:p-8">
                        <form method="post" action="<?= base_url('pasien/booking/store') ?>" class="space-y-6">
                            <?= csrf_field() ?>

                            <div>
                                <label class="block text-sm font-medium text-gray-300 mb-2">Tanggal Rencana Kunjungan</label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <i class="far fa-calendar-alt text-gray-500"></i>
                                    </div>
                                    <input type="date"
                                        id="appointment_date"
                                        name="schedule_date"
                                        min="<?= date('Y-m-d') ?>"
                                        required
                                        class="pl-10 w-full bg-gray-900 border border-gray-600 text-white text-sm rounded-lg focus:ring-cyan-500 focus:border-cyan-500 block p-3 transition placeholder-gray-400">
                                </div>
                                <p class="mt-1 text-xs text-gray-500">*Pastikan tanggal sesuai dengan hari praktek dokter.</p>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-300 mb-2">Pilih Poliklinik</label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <i class="fas fa-hospital text-gray-500"></i>
                                    </div>
                                    <select name="department_id" id="department" required
                                        class="pl-10 w-full bg-gray-900 border border-gray-600 text-white text-sm rounded-lg focus:ring-cyan-500 focus:border-cyan-500 block p-3 transition appearance-none">
                                        <option value="">-- Pilih Poli --</option>
                                        <?php foreach ($departments as $d): ?>
                                            <option value="<?= $d->department_id ?>">
                                                <?= esc($d->name) ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                    <div class="absolute inset-y-0 right-0 flex items-center px-4 pointer-events-none text-gray-400">
                                        <i class="fas fa-chevron-down text-xs"></i>
                                    </div>
                                </div>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-300 mb-2">Pilih Dokter</label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <i class="fas fa-user-md text-gray-500"></i>
                                    </div>
                                    <select name="doctor_id" id="doctor" disabled required
                                        class="pl-10 w-full bg-gray-900 border border-gray-600 text-gray-400 text-sm rounded-lg focus:ring-cyan-500 focus:border-cyan-500 block p-3 transition appearance-none disabled:opacity-50 disabled:cursor-not-allowed">
                                        <option value="">-- Pilih Poli Terlebih Dahulu --</option>
                                    </select>
                                    <div class="absolute inset-y-0 right-0 flex items-center px-4 pointer-events-none text-gray-400">
                                        <i class="fas fa-chevron-down text-xs"></i>
                                    </div>
                                </div>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-300 mb-2">Pilih Jadwal Sif</label>

                                <div id="schedule_loading" class="hidden text-cyan-400 text-sm italic mb-2">
                                    <i class="fas fa-spinner fa-spin mr-2"></i> Memuat jadwal...
                                </div>

                                <div id="schedule_container" class="grid grid-cols-2 sm:grid-cols-3 gap-3">
                                    <div class="col-span-full text-center py-8 border-2 border-dashed border-gray-700 rounded-xl text-gray-500">
                                        <i class="far fa-calendar-times text-2xl mb-2"></i>
                                        <p class="text-sm">Silakan pilih dokter untuk melihat jadwal tersedia.</p>
                                    </div>
                                </div>

                                <input type="hidden" name="schedule_id" id="schedule_id" required>
                            </div>

                            <div class="pt-4 flex flex-col sm:flex-row gap-3">
                                <button type="submit" class="w-full sm:w-2/3 bg-cyan-600 hover:bg-cyan-500 text-white font-bold py-3 px-4 rounded-lg transition shadow-lg shadow-cyan-900/20 active:scale-95 flex items-center justify-center gap-2">
                                    <i class="fas fa-paper-plane"></i> Konfirmasi Pendaftaran
                                </button>
                                <a href="<?= base_url('pasien/riwayat') ?>" class="w-full sm:w-1/3 bg-gray-700 hover:bg-gray-600 text-white font-semibold py-3 px-4 rounded-lg transition text-center border border-gray-600">
                                    Batal
                                </a>
                            </div>

                        </form>
                    </div>
                </div>

            </div>
        </main>
    </div>

    <script>
        const BASE_URL = "<?= base_url() ?>";

        const deptSelect = document.getElementById('department');
        const docSelect = document.getElementById('doctor');
        const schedContainer = document.getElementById('schedule_container');
        const schedInput = document.getElementById('schedule_id');
        const loadingText = document.getElementById('schedule_loading');

        // === 1. Saat Poli Dipilih ===
        deptSelect.addEventListener('change', function() {
            const deptId = this.value;

            // Reset state
            docSelect.innerHTML = '<option value="">Memuat...</option>';
            docSelect.disabled = true;
            docSelect.classList.add('text-gray-400');
            docSelect.classList.remove('text-white');
            resetSchedule();

            if (deptId) {
                fetch(`${BASE_URL}/appointment/getDoctorsByDept/${deptId}`)
                    .then(response => response.json())
                    .then(data => {
                        let options = '<option value="">-- Pilih Dokter --</option>';
                        data.forEach(doc => {
                            options += `<option value="${doc.doctor_id}">${doc.full_name}</option>`;
                        });
                        docSelect.innerHTML = options;
                        docSelect.disabled = false;
                        docSelect.classList.remove('text-gray-400');
                        docSelect.classList.add('text-white');
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
                loadingText.classList.remove('hidden');
                schedContainer.innerHTML = ''; // Clear placeholder

                fetch(`${BASE_URL}/appointment/getSchedulesByDoctor/${docId}`)
                    .then(response => response.json())
                    .then(data => {
                        loadingText.classList.add('hidden');

                        if (data.length === 0) {
                            schedContainer.innerHTML = `
                            <div class="col-span-full text-center py-4 bg-red-900/20 border border-red-500/30 rounded-lg text-red-400 text-sm">
                                <i class="fas fa-times-circle mr-1"></i> Dokter ini belum memiliki jadwal aktif.
                            </div>`;
                            return;
                        }

                        // Render Kartu Jadwal
                        data.forEach(sch => {
                            let start = sch.start_time.substring(0, 5);
                            let end = sch.end_time.substring(0, 5);

                            // Buat Element Kartu dengan Tailwind classes
                            const card = document.createElement('div');
                            // Base classes
                            card.className = 'schedule-card cursor-pointer p-3 rounded-xl border border-gray-600 bg-gray-700 hover:border-cyan-400 hover:bg-gray-600 transition-all text-center group relative';

                            card.innerHTML = `
                            <div class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-1 group-hover:text-cyan-300 transition-colors">${sch.day}</div>
                            <div class="text-lg font-bold text-white mb-1">${sch.shift}</div>
                            <div class="text-xs text-gray-400 bg-gray-800/50 py-1 px-2 rounded-lg inline-block group-hover:bg-gray-900/50">
                                <i class="far fa-clock mr-1"></i> ${start}-${end}
                            </div>
                            
                            <div class="check-icon hidden absolute top-2 right-2 text-cyan-400">
                                <i class="fas fa-check-circle"></i>
                            </div>
                        `;

                            // Event Klik
                            card.addEventListener('click', function() {
                                // 1. Reset semua kartu lain
                                document.querySelectorAll('.schedule-card').forEach(c => {
                                    // Hapus style 'selected'
                                    c.classList.remove('border-cyan-500', 'ring-2', 'ring-cyan-500', 'bg-gray-800');
                                    c.classList.add('border-gray-600', 'bg-gray-700');
                                    // Sembunyikan icon
                                    c.querySelector('.check-icon').classList.add('hidden');
                                });

                                // 2. Set style kartu ini (Selected)
                                this.classList.remove('border-gray-600', 'bg-gray-700');
                                this.classList.add('border-cyan-500', 'ring-2', 'ring-cyan-500', 'bg-gray-800');
                                // Tampilkan icon
                                this.querySelector('.check-icon').classList.remove('hidden');

                                // 3. Simpan nilai
                                schedInput.value = sch.schedule_id;
                            });

                            schedContainer.appendChild(card);
                        });
                    })
                    .catch(error => {
                        loadingText.classList.add('hidden');
                        schedContainer.innerHTML = '<p class="col-span-full text-red-400 text-sm text-center">Gagal memuat jadwal.</p>';
                    });
            }
        });

        function resetSchedule() {
            schedContainer.innerHTML = `
                <div class="col-span-full text-center py-8 border-2 border-dashed border-gray-700 rounded-xl text-gray-500">
                    <i class="far fa-calendar-times text-2xl mb-2"></i>
                    <p class="text-sm">Silakan pilih dokter untuk melihat jadwal tersedia.</p>
                </div>`;
            schedInput.value = '';
            loadingText.classList.add('hidden');
        }
    </script>

</body>

</html>