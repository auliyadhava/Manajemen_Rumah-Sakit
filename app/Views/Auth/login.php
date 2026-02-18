<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Sistem | RS Rawat Jalan</title>

    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

    <style>
        @keyframes fade-in-up {
            from {
                opacity: 0;
                transform: translateY(20px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .animate-fade-in-up {
            animation: fade-in-up 0.5s ease-out forwards;
        }
    </style>
</head>

<body class="bg-gray-900 text-gray-100 font-sans antialiased min-h-screen flex items-center justify-center p-4">

    <div class="w-full max-w-md bg-gray-800 rounded-2xl shadow-2xl border border-gray-700 overflow-hidden animate-fade-in-up">

        <div class="p-8 text-center bg-gray-800/50 border-b border-gray-700">
            <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-cyan-900/50 mb-4 ring-2 ring-cyan-500/50 shadow-lg shadow-cyan-500/20">
                <i class="fas fa-hospital-user text-3xl text-cyan-400"></i>
            </div>
            <h2 class="text-2xl font-bold text-white tracking-tight">RS Rawat Jalan</h2>
            <p class="text-sm text-gray-400 mt-1">Sistem Informasi Manajemen Rumah Sakit</p>
        </div>

        <div class="p-8">

            <?php if (session()->getFlashdata('msg')): ?>
                <div class="mb-6 p-4 bg-red-900/20 border border-red-500/50 text-red-400 rounded-xl flex items-center gap-3 shadow-lg animate-pulse">
                    <i class="fas fa-exclamation-circle text-xl"></i>
                    <div class="text-sm font-medium"><?= session()->getFlashdata('msg') ?></div>
                </div>
            <?php endif; ?>

            <form action="/login/auth" method="post" class="space-y-6">
                <?= csrf_field() ?>

                <div>
                    <label for="username" class="block text-sm font-medium text-gray-300 mb-2">Username</label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-500">
                            <i class="fas fa-user"></i>
                        </span>
                        <input type="text" name="username" id="username" required autofocus placeholder="Masukkan username"
                            class="pl-10 w-full bg-gray-900 border border-gray-600 text-white text-sm rounded-lg focus:ring-cyan-500 focus:border-cyan-500 block p-3 transition placeholder-gray-500">
                    </div>
                </div>

                <div>
                    <label for="password" class="block text-sm font-medium text-gray-300 mb-2">Password</label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-500">
                            <i class="fas fa-lock"></i>
                        </span>
                        <input type="password" name="password" id="password" required placeholder="Masukkan password"
                            class="pl-10 w-full bg-gray-900 border border-gray-600 text-white text-sm rounded-lg focus:ring-cyan-500 focus:border-cyan-500 block p-3 transition placeholder-gray-500">
                    </div>
                </div>

                <button type="submit" class="w-full bg-cyan-600 hover:bg-cyan-500 text-white font-bold py-3 px-4 rounded-xl transition shadow-lg shadow-cyan-900/30 transform hover:scale-[1.02] flex items-center justify-center gap-2">
                    <i class="fas fa-sign-in-alt"></i> Masuk
                </button>

            </form>

            <div class="mt-6 text-center border-t border-gray-700 pt-6">
                <p class="text-sm text-gray-400">
                    Pasien baru?
                    <a href="<?= base_url('pasien/register') ?>" class="text-cyan-400 hover:text-cyan-300 font-semibold hover:underline decoration-cyan-500/30 transition">
                        Daftar akun di sini
                    </a>
                </p>
            </div>
        </div>

        <div class="bg-gray-900 p-4 text-center">
            <p class="text-xs text-gray-600 font-mono">
                &copy; <?= date('Y') ?> Kelompok Pemrograman Web
            </p>
        </div>

    </div>

</body>

</html>