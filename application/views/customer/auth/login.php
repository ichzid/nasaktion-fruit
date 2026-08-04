<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Masuk / Daftar - Nasaktion Fruit</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://code.iconify.design/3/3.1.0/iconify.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <!-- Alpine JS for smooth toggle animations -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <script>
    tailwind.config = {
        theme: { extend: {
            colors: { primary: { 50:'#f0fdf4',100:'#dcfce7',200:'#bbf7d0',300:'#86efac',400:'#4ade80',500:'#22c55e',600:'#16a34a',700:'#15803d',800:'#166534',900:'#14532d' } },
            fontFamily: { sans: ['Inter','system-ui','sans-serif'] }
        }}
    }
    </script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap');
        html { overflow-y: auto; }
        body { font-family: 'Inter', sans-serif; }
        /* Prevent SweetAlert jump */
        body.swal2-shown:not(.swal2-no-backdrop):not(.swal2-toast-shown) {
            overflow-y: auto !important;
            padding-right: 0 !important;
        }
        .glass-panel {
            background: rgba(255, 255, 255, 0.85);
            backdrop-filter: blur(16px);
            border: 1px solid rgba(255, 255, 255, 0.3);
        }
    </style>
</head>
<body class="bg-gradient-to-br from-primary-50 via-white to-primary-100 min-h-screen flex items-center justify-center p-4 relative overflow-hidden" x-data="{ mode: 'login' }">
    
    <!-- Decorative background blobs -->
    <div class="absolute top-0 right-0 -mr-20 -mt-20 w-80 h-80 rounded-full bg-primary-200 opacity-50 blur-3xl mix-blend-multiply pointer-events-none"></div>
    <div class="absolute bottom-0 left-0 -ml-20 -mb-20 w-72 h-72 rounded-full bg-yellow-200 opacity-50 blur-3xl mix-blend-multiply pointer-events-none"></div>

    <div class="max-w-md w-full relative z-10 transition-all duration-300 transform">
        <!-- Logo Header -->
        <div class="text-center mb-6">
            <div class="w-16 h-16 bg-white rounded-full shadow-md flex items-center justify-center mx-auto mb-3 border border-primary-100">
                <span class="text-3xl">🍎</span>
            </div>
            <h1 class="text-2xl font-bold text-gray-800 tracking-tight">Nasaktion Fruit</h1>
            <p class="text-gray-500 font-medium text-sm mt-1">Kesegaran Alami di Genggaman Anda</p>
        </div>

        <div class="glass-panel rounded-2xl shadow-xl p-8 relative overflow-hidden">
            
            <?php if($this->session->flashdata('success')): ?>
            <script>
                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil!',
                    text: '<?= htmlspecialchars(addslashes($this->session->flashdata("success"))) ?>',
                    timer: 3000,
                    showConfirmButton: false,
                    toast: true,
                    position: 'top-end'
                });
            </script>
            <?php endif; ?>
            <?php if($this->session->flashdata('error')): ?>
            <script>
                Swal.fire({
                    icon: 'error',
                    title: 'Oops!',
                    text: '<?= htmlspecialchars(addslashes($this->session->flashdata("error"))) ?>',
                    timer: 3000,
                    showConfirmButton: false,
                    toast: true,
                    position: 'top-end'
                });
            </script>
            <?php endif; ?>

            <!-- Tabs -->
            <div class="flex p-1 bg-gray-100 rounded-xl mb-8 relative">
                <button @click="mode = 'login'" 
                        :class="mode === 'login' ? 'text-gray-800 shadow-sm bg-white' : 'text-gray-500 hover:text-gray-700'"
                        class="flex-1 py-2 text-sm font-medium rounded-lg transition-all duration-200 z-10">
                    Masuk
                </button>
                <button @click="mode = 'register'" 
                        :class="mode === 'register' ? 'text-gray-800 shadow-sm bg-white' : 'text-gray-500 hover:text-gray-700'"
                        class="flex-1 py-2 text-sm font-medium rounded-lg transition-all duration-200 z-10">
                    Daftar Baru
                </button>
            </div>

            <!-- Unified Login Form -->
            <form x-show="mode === 'login'"
                  x-transition:enter="transition ease-out duration-300"
                  x-transition:enter-start="opacity-0 translate-x-4"
                  x-transition:enter-end="opacity-100 translate-x-0"
                  action="<?= site_url('customer/auth/login_process') ?>" method="POST" class="space-y-5">
                
                <input type="hidden" name="<?= $this->security->get_csrf_token_name(); ?>" value="<?= $this->security->get_csrf_hash(); ?>">
                
                <div class="text-center mb-6">
                    <h2 class="text-xl font-bold text-gray-800">Selamat Datang Kembali!</h2>
                    <p class="text-sm text-gray-500 mt-1">Login pelanggan, kasir, dan admin dalam satu halaman</p>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Nomor HP atau Username</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <span class="iconify text-gray-400 text-lg" data-icon="lucide:user-round"></span>
                        </div>
                        <input type="text" name="identity" required autocomplete="username" placeholder="Nomor HP pelanggan / username staf"
                               class="pl-10 w-full rounded-xl border border-gray-200 px-4 py-3 text-gray-800 bg-gray-50 focus:bg-white focus:border-primary-500 focus:ring-2 focus:ring-primary-200 transition-all outline-none">
                    </div>
                    <p class="text-[11px] text-gray-400 mt-1.5">Pelanggan menggunakan nomor HP. Admin dan kasir menggunakan username.</p>
                </div>

                <div>
                    <div class="flex items-center justify-between mb-1.5">
                        <label class="block text-sm font-medium text-gray-700">Password</label>
                        <a href="#" onclick="Swal.fire('Lupa Password?','Silakan hubungi admin kami via WhatsApp untuk mereset password Anda.','info')" class="text-xs text-primary-600 hover:underline font-medium">Lupa Password?</a>
                    </div>
                    <div class="relative" x-data="{ show: false }">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <span class="iconify text-gray-400 text-lg" data-icon="lucide:lock"></span>
                        </div>
                        <input :type="show ? 'text' : 'password'" name="password" required placeholder="••••••••" 
                               class="pl-10 pr-10 w-full rounded-xl border border-gray-200 px-4 py-3 text-gray-800 bg-gray-50 focus:bg-white focus:border-primary-500 focus:ring-2 focus:ring-primary-200 transition-all outline-none">
                        <button type="button" @click="show = !show" class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400 hover:text-gray-600">
                            <span class="iconify text-lg" :data-icon="show ? 'lucide:eye-off' : 'lucide:eye'"></span>
                        </button>
                    </div>
                </div>

                <button type="submit" class="w-full bg-primary-600 hover:bg-primary-700 text-white font-medium rounded-xl px-4 py-3.5 mt-2 transition-colors shadow-md hover:shadow-lg active:scale-[0.98]">
                    Masuk Sekarang
                </button>
            </form>

            <!-- Register Form -->
            <form x-show="mode === 'register'" style="display: none;"
                  x-transition:enter="transition ease-out duration-300"
                  x-transition:enter-start="opacity-0 -translate-x-4"
                  x-transition:enter-end="opacity-100 translate-x-0"
                  action="<?= site_url('customer/auth/register_process') ?>" method="POST" class="space-y-4">
                
                <input type="hidden" name="<?= $this->security->get_csrf_token_name(); ?>" value="<?= $this->security->get_csrf_hash(); ?>">
                
                <div class="text-center mb-5">
                    <h2 class="text-xl font-bold text-gray-800">Buat Akun Member</h2>
                    <p class="text-sm text-gray-500 mt-1">Gabung sekarang dan nikmati keuntungannya</p>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Nama Lengkap</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <span class="iconify text-gray-400 text-lg" data-icon="lucide:user"></span>
                        </div>
                        <input type="text" name="nama" required placeholder="Nama Anda" 
                               class="pl-10 w-full rounded-xl border border-gray-200 px-4 py-2.5 text-gray-800 bg-gray-50 focus:bg-white focus:border-primary-500 focus:ring-2 focus:ring-primary-200 transition-all outline-none">
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Nomor HP Aktif</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <span class="iconify text-gray-400 text-lg" data-icon="lucide:phone"></span>
                        </div>
                        <input type="tel" name="no_hp" required placeholder="08..." 
                               class="pl-10 w-full rounded-xl border border-gray-200 px-4 py-2.5 text-gray-800 bg-gray-50 focus:bg-white focus:border-primary-500 focus:ring-2 focus:ring-primary-200 transition-all outline-none">
                    </div>
                    <p class="text-[11px] text-gray-400 mt-1 ml-1">*Bila Anda sudah terdaftar di toko offline, gunakan nomor HP yang sama.</p>
                </div>

                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Buat Password</label>
                        <input type="password" name="password" required placeholder="Sandi Bebas" 
                               class="w-full rounded-xl border border-gray-200 px-4 py-2.5 text-gray-800 bg-gray-50 focus:bg-white focus:border-primary-500 focus:ring-2 focus:ring-primary-200 transition-all outline-none">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Konfirmasi</label>
                        <input type="password" name="password_conf" required placeholder="Ulangi Sandi" 
                               class="w-full rounded-xl border border-gray-200 px-4 py-2.5 text-gray-800 bg-gray-50 focus:bg-white focus:border-primary-500 focus:ring-2 focus:ring-primary-200 transition-all outline-none">
                    </div>
                </div>

                <button type="submit" class="w-full bg-gray-800 hover:bg-gray-900 text-white font-medium rounded-xl px-4 py-3.5 mt-4 transition-colors shadow-md hover:shadow-lg active:scale-[0.98]">
                    Daftar Akun Baru
                </button>
            </form>

        </div>

    </div>
</body>
</html>