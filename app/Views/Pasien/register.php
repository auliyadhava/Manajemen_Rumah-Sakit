<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrasi Pasien | Portal Pasien</title>

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

        input[type="date"] {
            color-scheme: dark;
        }
    </style>
</head>

<body class="bg-gray-900 text-gray-100 font-sans antialiased flex items-center justify-center min-h-screen p-4 md:p-8">

    <div class="w-full max-w-4xl bg-gray-800 rounded-2xl shadow-2xl border border-gray-700 overflow-hidden animate-fade-in-up">

        <div class="p-6 md:p-8 bg-gray-800/80 backdrop-blur-sm border-b border-gray-700 text-center">
            <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-cyan-900/50 mb-4 ring-2 ring-cyan-500/50">
                <i class="fas fa-user-plus text-3xl text-cyan-400"></i>
            </div>
            <h2 class="text-3xl font-bold text-white mb-2">Registrasi Pasien Baru</h2>
            <p class="text-gray-400">Isi data diri Anda dengan lengkap dan benar untuk membuat akun.</p>
        </div>

        <div class="p-6 md:p-8">

            <?php if (session()->getFlashdata('errors')): ?>
                <div class="mb-6 p-4 bg-red-900/20 border border-red-500/50 text-red-400 rounded-xl flex items-start gap-3 shadow-lg">
                    <i class="fas fa-exclamation-triangle mt-1"></i>
                    <div class="text-sm">
                        <p class="font-bold mb-1">Terjadi Kesalahan:</p>
                        <ul class="list-disc list-inside opacity-90 space-y-1">
                            <?php foreach (session()->getFlashdata('errors') as $error): ?>
                                <li><?= esc($error) ?></li>
                            <?php endforeach ?>
                        </ul>
                    </div>
                </div>
            <?php endif; ?>

            <form action="<?= base_url('pasien/register/process') ?>" method="post">
                <?= csrf_field() ?>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 lg:gap-8">

                    <div class="space-y-6">
                        <h3 class="text-lg font-bold text-cyan-400 border-b border-gray-700 pb-2 flex items-center gap-2">
                            <i class="fas fa-lock"></i> Data Akun
                        </h3>

                        <div>
                            <label class="block text-sm font-medium text-gray-300 mb-1">Username</label>
                            <div class="relative">
                                <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-500">
                                    <i class="fas fa-at"></i>
                                </span>
                                <input type="text" name="username" required placeholder="Contoh: dhava2026"
                                    class="pl-10 w-full bg-gray-900 border border-gray-600 text-white text-sm rounded-lg focus:ring-cyan-500 focus:border-cyan-500 block p-3 transition">
                            </div>
                            <p class="text-xs text-gray-500 mt-1">Gunakan untuk login nanti.</p>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-300 mb-1">Password</label>
                            <div class="relative">
                                <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-500">
                                    <i class="fas fa-key"></i>
                                </span>
                                <input type="password" name="password" required placeholder="••••••••"
                                    class="pl-10 w-full bg-gray-900 border border-gray-600 text-white text-sm rounded-lg focus:ring-cyan-500 focus:border-cyan-500 block p-3 transition">
                            </div>
                            <p class="text-xs text-gray-500 mt-1">Minimal 6 karakter.</p>
                        </div>
                    </div>

                    <div class="space-y-6">
                        <h3 class="text-lg font-bold text-cyan-400 border-b border-gray-700 pb-2 flex items-center gap-2">
                            <i class="fas fa-id-card"></i> Data Pribadi
                        </h3>

                        <div>
                            <label class="block text-sm font-medium text-gray-300 mb-1">Nama Lengkap</label>
                            <input type="text" name="full_name" required placeholder="Sesuai KTP"
                                class="w-full bg-gray-900 border border-gray-600 text-white text-sm rounded-lg focus:ring-cyan-500 focus:border-cyan-500 block p-3 transition">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-300 mb-1">NIK (Nomor Induk Kependudukan)</label>
                            <input type="number" name="nik" required placeholder="16 digit angka"
                                class="w-full bg-gray-900 border border-gray-600 text-white text-sm rounded-lg focus:ring-cyan-500 focus:border-cyan-500 block p-3 transition">
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-300 mb-1">Jenis Kelamin</label>
                                <select name="gender" class="w-full bg-gray-900 border border-gray-600 text-white text-sm rounded-lg focus:ring-cyan-500 focus:border-cyan-500 block p-3 transition">
                                    <option value="L">Laki-laki</option>
                                    <option value="P">Perempuan</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-300 mb-1">Tanggal Lahir</label>
                                <input type="date" name="birth_date" required
                                    class="w-full bg-gray-900 border border-gray-600 text-white text-sm rounded-lg focus:ring-cyan-500 focus:border-cyan-500 block p-3 transition">
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-300 mb-1">No. HP / WhatsApp</label>
                            <div class="relative">
                                <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-500">
                                    <i class="fas fa-phone"></i>
                                </span>
                                <input type="number" name="phone" required placeholder="08xxxxxxxxxx"
                                    class="pl-10 w-full bg-gray-900 border border-gray-600 text-white text-sm rounded-lg focus:ring-cyan-500 focus:border-cyan-500 block p-3 transition">
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-300 mb-1">Alamat Lengkap</label>
                            <textarea name="address" rows="3" required placeholder="Nama jalan, RT/RW, Kelurahan, Kecamatan..."
                                class="w-full bg-gray-900 border border-gray-600 text-white text-sm rounded-lg focus:ring-cyan-500 focus:border-cyan-500 block p-3 transition resize-none"></textarea>
                        </div>

                    </div>
                </div>

                <div class="mt-8 pt-6 border-t border-gray-700">
                    <button type="submit" class="w-full bg-cyan-600 hover:bg-cyan-500 text-white font-bold py-4 px-6 rounded-xl transition shadow-lg shadow-cyan-900/30 transform hover:scale-[1.01] flex items-center justify-center gap-2">
                        <span>Daftar Sekarang</span>
                        <i class="fas fa-arrow-right"></i>
                    </button>

                    <p class="text-center mt-6 text-gray-400 text-sm">
                        Sudah punya akun?
                        <a href="<?= base_url('/') ?>" class="text-cyan-400 hover:text-cyan-300 font-semibold underline decoration-cyan-500/30 hover:decoration-cyan-300 transition">
                            Login di sini
                        </a>
                    </p>
                </div>

            </form>
        </div>
    </div>

</body>

</html>