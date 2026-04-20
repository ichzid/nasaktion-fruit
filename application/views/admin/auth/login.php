<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Admin - Nasaktion Fruit CRM</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://code.iconify.design/3/3.1.0/iconify.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        tailwind.config = {
            theme: { extend: { colors: {
                primary: { 50:'#f0fdf4',100:'#dcfce7',200:'#bbf7d0',300:'#86efac',400:'#4ade80',500:'#22c55e',600:'#16a34a',700:'#15803d',800:'#166534',900:'#14532d' }
            }}}
        }
    </script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap');
        html { overflow-y: scroll; }
        body { font-family: 'Inter', sans-serif; }
        /* Prevent SweetAlert from modifying scrollbar and making the page jump */
        body.swal2-shown:not(.swal2-no-backdrop):not(.swal2-toast-shown) {
            overflow-y: scroll !important;
            padding-right: 0 !important;
        }
    </style>
</head>
<body class="bg-gradient-to-br from-primary-50 to-green-100 min-h-screen flex items-center justify-center">
    <div class="w-full max-w-md mx-4">
        <div class="bg-white rounded-2xl shadow-xl p-8">
            <div class="text-center mb-8">
                <div class="inline-flex items-center justify-center w-16 h-16 bg-primary-100 rounded-full mb-4">
                    <span class="iconify text-primary-600 text-3xl" data-icon="lucide:fruit"></span>
                </div>
                <h1 class="text-2xl font-bold text-gray-800">Nasaktion<span class="text-primary-600">Fruit</span></h1>
            </div>

            <?php if($this->session->flashdata('error')): ?>
            <script>
                Swal.fire({
                    icon: 'error',
                    title: 'Login Gagal!',
                    text: '<?= addslashes($this->session->flashdata("error")) ?>',
                    timer: 3000,
                    showConfirmButton: false,
                    toast: true,
                    position: 'top-end'
                });
            </script>
            <?php endif; ?>

            <form action="<?= site_url('admin/login') ?>" method="post" class="space-y-5">
                <input type="hidden" name="<?= $this->security->get_csrf_token_name(); ?>" value="<?= $this->security->get_csrf_hash(); ?>">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Username</label>
                    <div class="relative">
                        <span class="iconify absolute left-3 top-1/2 -translate-y-1/2 text-gray-400" data-icon="lucide:user"></span>
                        <input type="text" name="username" class="w-full pl-10 pr-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 outline-none transition" placeholder="Masukkan username" required>
                    </div>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Password</label>
                    <div class="relative">
                        <span class="iconify absolute left-3 top-1/2 -translate-y-1/2 text-gray-400" data-icon="lucide:lock"></span>
                        <input type="password" name="password" class="w-full pl-10 pr-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 outline-none transition" placeholder="Masukkan password" required>
                    </div>
                </div>
                <button type="submit" class="w-full bg-primary-600 hover:bg-primary-700 text-white font-medium py-2.5 rounded-lg transition flex items-center justify-center gap-2">
                    <span class="iconify" data-icon="lucide:log-in"></span> Masuk
                </button>
            </form>

            <div class="mt-6 text-center">
                <a href="<?= site_url() ?>" class="text-sm text-gray-500 hover:text-primary-600 transition">
                    <span class="iconify inline" data-icon="lucide:arrow-left"></span> Kembali ke Halaman Utama
                </a>
            </div>
        </div>
        <p class="text-center text-xs text-gray-400 mt-4">&copy; <?= date('Y') ?> Nasaktion Fruit. All rights reserved.</p>
    </div>
</body>
</html>