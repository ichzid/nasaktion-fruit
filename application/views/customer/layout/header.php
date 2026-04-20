<?php
if (!isset($cart_count)) {
    $cart = $this->session->userdata('shop_cart') ? $this->session->userdata('shop_cart') : array();
    $cart_count = array_sum(array_column($cart, 'qty'));
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? 'Nasaktion Fruit' ?> — Toko Buah Segar</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://code.iconify.design/3/3.1.0/iconify.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&family=Sora:wght@700;800&display=swap" rel="stylesheet">
    <script>
    tailwind.config = {
        theme: { extend: {
            colors: {
                primary: { 50:'#f0fdf4',100:'#dcfce7',200:'#bbf7d0',300:'#86efac',400:'#4ade80',500:'#22c55e',600:'#16a34a',700:'#15803d',800:'#166534',900:'#14532d' }
            },
            fontFamily: {
                sans: ['Inter','system-ui','sans-serif'],
                display: ['Sora','system-ui','sans-serif']
            }
        }}
    }
    </script>
    <style>
        body { font-family: 'Inter', sans-serif; }
        .font-display { font-family: 'Sora', sans-serif; }
        html { overflow-y: scroll; }
        body.swal2-shown:not(.swal2-no-backdrop):not(.swal2-toast-shown) {
            overflow-y: scroll !important;
            padding-right: 0 !important;
        }
        /* Nav underline effect */
        .nav-link { position: relative; }
        .nav-link::after {
            content: '';
            position: absolute;
            bottom: -4px; left: 0;
            width: 0; height: 2px;
            background: #16a34a;
            transition: width 0.25s ease;
        }
        .nav-link:hover::after,
        .nav-link.active::after { width: 100%; }

        /* Dropdown */
        .user-dropdown {
            display: none;
            animation: dropIn 0.15s ease;
        }
        .user-dropdown.open { display: block; }
        @keyframes dropIn {
            from { opacity: 0; transform: translateY(-6px); }
            to   { opacity: 1; transform: translateY(0); }
        }

        /* Page transitions */
        main { animation: fadeUp 0.4s ease; }
        @keyframes fadeUp {
            from { opacity: 0; transform: translateY(12px); }
            to   { opacity: 1; transform: translateY(0); }
        }

        /* Smooth card hover */
        .card-hover {
            transition: all 0.3s cubic-bezier(0.34,1.56,0.64,1);
        }
        .card-hover:hover {
            transform: translateY(-6px);
            box-shadow: 0 16px 32px rgba(22,163,74,0.12);
        }
    </style>
</head>
<body class="bg-gray-50 min-h-screen flex flex-col">

    <!-- ===== NAVBAR ===== -->
    <nav class="bg-white/95 backdrop-blur-lg border-b border-gray-100 sticky top-0 z-40 shadow-sm">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 py-3.5 flex items-center justify-between gap-4">

            <!-- Logo -->
            <a href="<?= site_url('customer/dashboard') ?>" class="flex items-center gap-2.5 group flex-shrink-0">
                <div class="w-9 h-9 bg-gradient-to-br from-primary-500 to-primary-700 rounded-xl flex items-center justify-center shadow-md group-hover:scale-110 transition-transform">
                    <span class="text-lg">🍎</span>
                </div>
                <span class="font-display font-bold text-lg text-gray-800 hidden sm:block">Nasaktion<span class="text-primary-600">Fruit</span></span>
            </a>

            <!-- Nav Links (desktop) -->
            <div class="hidden md:flex items-center gap-7">
                <a href="<?= site_url('customer/dashboard') ?>"
                   class="nav-link text-sm font-medium transition-colors <?= (uri_string() == 'customer/dashboard' ? 'text-primary-700 active' : 'text-gray-500 hover:text-gray-800') ?>">
                    Beranda
                </a>
                <a href="<?= site_url('customer/shop') ?>"
                   class="nav-link text-sm font-medium transition-colors <?= (strpos(uri_string(),'customer/shop') !== false ? 'text-primary-700 active' : 'text-gray-500 hover:text-gray-800') ?>">
                    Belanja
                </a>
            </div>

            <!-- Right actions -->
            <div class="flex items-center gap-2 sm:gap-3">

                <!-- Search (mobile trigger) -->
                <a href="<?= site_url('customer/shop') ?>" class="md:hidden w-9 h-9 flex items-center justify-center rounded-xl hover:bg-gray-100 transition text-gray-500">
                    <span class="iconify text-xl" data-icon="lucide:search"></span>
                </a>

                <!-- Cart -->
                <a href="<?= site_url('customer/cart') ?>" class="relative w-9 h-9 flex items-center justify-center rounded-xl hover:bg-primary-50 transition text-gray-500 hover:text-primary-700">
                    <span class="iconify text-xl" data-icon="lucide:shopping-cart"></span>
                    <span id="cartBadge" class="cart-badge absolute -top-1 -right-1 bg-red-500 text-white text-[10px] font-bold rounded-full w-5 h-5 flex items-center justify-center shadow-sm <?= ($cart_count > 0) ? '' : 'hidden' ?>"><?= $cart_count ?></span>
                </a>

                <?php if($this->session->userdata('customer_id')): ?>
                <!-- User menu -->
                <div class="user-menu relative" id="userMenuWrapper">
                    <button id="userMenuBtn" onclick="toggleUserMenu()" class="flex items-center gap-2 bg-gray-50 hover:bg-primary-50 border border-gray-200 hover:border-primary-200 rounded-xl px-3 py-2 transition-all group">
                        <img src="<?= 'https://ui-avatars.com/api/?name='.urlencode($this->session->userdata('customer_nama')).'&background=16a34a&color=fff&size=64' ?>"
                             class="w-7 h-7 rounded-lg object-cover" alt="Avatar">
                        <span class="text-sm font-medium text-gray-700 hidden sm:block max-w-[100px] truncate">
                            <?= $this->session->userdata('customer_nama') ?>
                        </span>
                        <span class="iconify text-gray-400 text-sm" data-icon="lucide:chevron-down" id="userMenuChevron"></span>
                    </button>
                    <!-- Dropdown (click-toggled) -->
                    <div id="userDropdown" class="user-dropdown absolute right-0 top-full mt-2 w-52 bg-white rounded-2xl shadow-2xl border border-gray-100 py-2 z-50">
                        <div class="px-4 py-2 border-b border-gray-100 mb-1">
                            <p class="text-xs text-gray-400">Masuk sebagai</p>
                            <p class="text-sm font-semibold text-gray-800 truncate"><?= $this->session->userdata('customer_nama') ?></p>
                        </div>
                        <a href="<?= site_url('customer/dashboard') ?>" class="flex items-center gap-2.5 px-4 py-2 text-sm text-gray-600 hover:bg-primary-50 hover:text-primary-700 transition-colors">
                            <span class="iconify" data-icon="lucide:layout-dashboard"></span> Dashboard
                        </a>
                        <a href="<?= site_url('customer/shop') ?>" class="flex items-center gap-2.5 px-4 py-2 text-sm text-gray-600 hover:bg-primary-50 hover:text-primary-700 transition-colors">
                            <span class="iconify" data-icon="lucide:package-search"></span> Belanja
                        </a>
                        <a href="<?= site_url('customer/cart') ?>" class="flex items-center justify-between px-4 py-2 text-sm text-gray-600 hover:bg-primary-50 hover:text-primary-700 transition-colors">
                            <div class="flex items-center gap-2.5">
                                <span class="iconify" data-icon="lucide:shopping-bag"></span> Keranjang
                            </div>
                            <span id="cartBadgeDropdown" class="cart-badge bg-red-500 text-white text-[10px] font-bold px-1.5 py-0.5 rounded-full <?= ($cart_count > 0) ? '' : 'hidden' ?>"><?= $cart_count ?></span>
                        </a>
                        <div class="border-t border-gray-100 mt-1 pt-1">
                            <a href="<?= site_url('customer/auth/logout') ?>" class="flex items-center gap-2.5 px-4 py-2 text-sm text-red-500 hover:bg-red-50 transition-colors">
                                <span class="iconify" data-icon="lucide:log-out"></span> Keluar
                            </a>
                        </div>
                    </div>
                </div>
                <?php else: ?>
                <a href="<?= site_url('customer/auth/login') ?>"
                   class="bg-primary-600 hover:bg-primary-700 text-white px-4 py-2 rounded-xl text-sm font-semibold transition-all shadow-md hover:shadow-lg active:scale-95">
                    Login
                </a>
                <?php endif; ?>
            </div>
        </div>
    </nav>

    <?php if($this->session->flashdata('success')): ?>
    <script>
        Swal.fire({
            icon: 'success',
            title: 'Berhasil!',
            text: '<?= addslashes($this->session->flashdata("success")) ?>',
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
            text: '<?= addslashes($this->session->flashdata("error")) ?>',
            timer: 3000,
            showConfirmButton: false,
            toast: true,
            position: 'top-end'
        });
    </script>
    <?php endif; ?>

    <script>
    function toggleUserMenu() {
        const dropdown = document.getElementById('userDropdown');
        const chevron  = document.getElementById('userMenuChevron');
        const isOpen   = dropdown.classList.contains('open');

        if (isOpen) {
            dropdown.classList.remove('open');
            if (chevron) chevron.style.transform = 'rotate(0deg)';
        } else {
            dropdown.classList.add('open');
            if (chevron) chevron.style.transform = 'rotate(180deg)';
        }
    }

    // Close dropdown when clicking outside
    document.addEventListener('click', function(e) {
        const wrapper  = document.getElementById('userMenuWrapper');
        const dropdown = document.getElementById('userDropdown');
        if (!wrapper || !dropdown) return;
        if (!wrapper.contains(e.target)) {
            dropdown.classList.remove('open');
            const chevron = document.getElementById('userMenuChevron');
            if (chevron) chevron.style.transform = 'rotate(0deg)';
        }
    });
    </script>

    <main class="flex-grow w-full max-w-7xl mx-auto px-4 sm:px-6 py-6">