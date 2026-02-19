<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title><?= $title ?? 'Pendaftaran' ?></title>

    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- Font Awesome -->
    <link rel="stylesheet"
          href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Custom CSS -->
    <link rel="stylesheet" href="<?= base_url('assets/css/pendaftaran/sidebar.css') ?>">
    <link rel="stylesheet" href="<?= base_url('assets/css/pendaftaran/dashboard.css') ?>">
</head>

<body class="bg-gray-900 text-gray-100 min-h-screen flex">

<!-- ================= SIDEBAR ================= -->
<aside class="w-64 bg-gray-800 border-r border-gray-700 p-6">
    <h2 class="text-xl font-bold text-blue-400 mb-8">
        RS Rawat Jalan
    </h2>

    <?php $segment = service('uri')->getSegment(2); ?>

    <nav class="space-y-2 text-sm">
        <!-- DASHBOARD -->
        <a href="<?= base_url('pendaftaran') ?>"
           class="flex items-center px-4 py-2 rounded-lg transition
           <?= ($segment == '' || $segment == null)
                ? 'bg-blue-600 text-white shadow-lg'
                : 'hover:bg-gray-700 text-gray-300' ?>">
            <i class="fas fa-chart-line mr-3"></i>
            Dashboard
        </a>

        <!-- PENDAFTARAN PASIEN -->
        <a href="<?= base_url('pendaftaran/pasien') ?>"
           class="flex items-center px-4 py-2 rounded-lg transition
           <?= ($segment == 'pasien')
                ? 'bg-blue-600 text-white shadow-lg'
                : 'hover:bg-gray-700 text-gray-300' ?>">
            <i class="fas fa-user-plus mr-3"></i>
            Pendaftaran Pasien
        </a>

        <!-- ANTRIAN -->
        <a href="<?= base_url('pendaftaran/antrian') ?>"
           class="flex items-center px-4 py-2 rounded-lg transition
           <?= ($segment == 'antrian')
                ? 'bg-blue-600 text-white shadow-lg'
                : 'hover:bg-gray-700 text-gray-300' ?>">
            <i class="fas fa-list-ol mr-3"></i>
            Antrian
        </a>
    </nav>
</aside>

<!-- ================= MAIN CONTENT ================= -->
<div class="flex-1 flex flex-col">

    <!-- ================= TOPBAR ================= -->
    <header class="bg-gradient-to-r from-gray-800 via-gray-900 to-gray-800 
                   border-b border-gray-700 px-8 py-4 
                   flex justify-between items-center">

        <h3 class="text-lg font-semibold">
            <?= $title ?? 'Halaman' ?>
        </h3>

        <div class="flex items-center gap-6">
            <!-- USER INFO -->
            <div class="text-right leading-tight">
                <div class="font-semibold text-gray-200">
                    <?= session()->get('full_name') ?>
                </div>
                <div class="text-sm text-yellow-400">
                    <?= session()->get('role') ?? 'Kasir' ?>
                </div>
            </div>

            <!-- ICON BULAT -->
            <div class="w-10 h-10 rounded-full border-2 border-yellow-400 
                        flex items-center justify-center text-yellow-400">
                <i class="fas fa-id-badge"></i>
            </div>

            <!-- BUTTON LOGOUT -->
            <button onclick="confirmLogout()"
                class="flex items-center gap-2 px-5 py-2 
                       border border-red-500 text-red-400 
                       rounded-xl hover:bg-red-500 hover:text-white 
                       transition duration-300">
                <i class="fas fa-sign-out-alt"></i>
                Keluar
            </button>
        </div>
    </header>

    <!-- ================= PAGE CONTENT ================= -->
    <main class="p-6 flex-1">
        <?= $this->renderSection('content') ?>
    </main>

    <footer class="bg-gray-800 border-t border-gray-700 py-6 px-6 text-center">
        <p class="text-sm text-gray-500">&copy; 2026 RS Rawat Jalan System. Dibuat dengan 
        <i class="fas fa-heart text-red-500 mx-1"></i> oleh Ani Marfuah Ramadhani.</p>
    </footer>

</div>

<!-- ================= SCRIPT ================= -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
function confirmLogout() {
    if (confirm("Yakin mau keluar dari sistem?")) {
        window.location.href = "<?= base_url('logout') ?>";
    }
}
</script>

</body>
</html>
