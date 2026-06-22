<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? 'Admin Panel' ?> - Nasaktion Fruit CRM</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://code.iconify.design/3/3.1.0/iconify.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: { 50:'#f0fdf4',100:'#dcfce7',200:'#bbf7d0',300:'#86efac',400:'#4ade80',500:'#22c55e',600:'#16a34a',700:'#15803d',800:'#166534',900:'#14532d' },
                        accent: { 50:'#fff7ed',100:'#ffedd5',200:'#fed7aa',300:'#fdba74',400:'#fb923c',500:'#f97316',600:'#ea580c',700:'#c2410c',800:'#9a3412',900:'#7c2d12' }
                    }
                }
            }
        }
    </script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap');
        body { font-family: 'Inter', sans-serif; }
        html { overflow-y: scroll !important; }
        .sidebar-link { transition: all 0.2s; }
        .sidebar-link:hover, .sidebar-link.active { background: rgba(22,163,74,0.1); color: #16a34a; }
        /* Prevent SweetAlert from modifying scrollbar and making the page jump */
        body.swal2-shown:not(.swal2-no-backdrop):not(.swal2-toast-shown) {
            overflow-y: scroll !important;
            padding-right: 0 !important;
        }
    </style>
</head>
<body class="bg-gray-50 min-h-screen">
    <div class="flex min-h-screen">
        <!-- Sidebar -->
        <aside class="w-64 bg-white border-r border-gray-200 fixed h-full overflow-y-auto z-30" id="sidebar">
            <div class="p-4 border-b border-gray-200">
                <a href="<?= site_url('admin/dashboard') ?>" class="flex items-center gap-2">
                    <span class="iconify text-primary-600 text-2xl" data-icon="lucide:store"></span>
                    <span class="font-bold text-lg text-gray-800">Nasaktion<span class="text-primary-600">Fruit</span></span>
                </a>
            </div>
            <nav class="p-3 space-y-1">
                <?php if($this->session->userdata('admin_role') === 'admin'): ?>
                <a href="<?= site_url('admin/dashboard') ?>" class="sidebar-link flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm text-gray-700 <?= ($this->uri->segment(2)=='dashboard'||$this->uri->segment(2)=='') ?'active':'' ?>">
                    <span class="iconify text-lg" data-icon="lucide:layout-dashboard"></span> Dashboard
                </a>
                <p class="text-xs text-gray-400 uppercase tracking-wider mt-4 mb-2 px-3">Master Data</p>
                <a href="<?= site_url('admin/products') ?>" class="sidebar-link flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm text-gray-700 <?= $this->uri->segment(2)=='products'?'active':'' ?>">
                    <span class="iconify text-lg" data-icon="lucide:package-search"></span> Produk
                </a>
                <a href="<?= site_url('admin/categories') ?>" class="sidebar-link flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm text-gray-700 <?= $this->uri->segment(2)=='categories'?'active':'' ?>">
                    <span class="iconify text-lg" data-icon="lucide:boxes"></span> Kategori
                </a>
                <a href="<?= site_url('admin/users') ?>" class="sidebar-link flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm text-gray-700 <?= $this->uri->segment(2)=='users'?'active':'' ?>">
                    <span class="iconify text-lg" data-icon="lucide:user-cog"></span> User Admin
                </a>
                <?php endif; ?>

                <p class="text-xs text-gray-400 uppercase tracking-wider mt-4 mb-2 px-3">Transaksi</p>
                <a href="<?= site_url('admin/pos') ?>" class="sidebar-link flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm text-gray-700 <?= $this->uri->segment(2)=='pos'?'active':'' ?>">
                    <span class="iconify text-lg" data-icon="lucide:shopping-basket"></span> Kasir
                </a>
                <a href="<?= site_url('admin/transactions') ?>" class="sidebar-link flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm text-gray-700 <?= $this->uri->segment(2)=='transactions'?'active':'' ?>">
                    <span class="iconify text-lg" data-icon="lucide:receipt-text"></span> Transaksi
                </a>

                <?php if($this->session->userdata('admin_role') === 'admin'): ?>
                <p class="text-xs text-gray-400 uppercase tracking-wider mt-4 mb-2 px-3">CRM Engine</p>
                <a href="<?= site_url('admin/crm') ?>" class="sidebar-link flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm text-gray-700 <?= $this->uri->segment(2)=='crm'?'active':'' ?>">
                    <span class="iconify text-lg" data-icon="lucide:heart-handshake"></span> CRM Dashboard
                </a>
                <a href="<?= site_url('admin/customers') ?>" class="sidebar-link flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm text-gray-700 <?= $this->uri->segment(2)=='customers'?'active':'' ?>">
                    <span class="iconify text-lg" data-icon="lucide:users"></span> Pelanggan
                </a>
                <a href="<?= site_url('admin/feedback') ?>" class="sidebar-link flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm text-gray-700 <?= $this->uri->segment(2)=='feedback'?'active':'' ?>">
                    <span class="iconify text-lg" data-icon="lucide:message-square-text"></span> Feedback
                    <?php $unread = $this->Feedback_model->count_unread(); if($unread>0): ?>
                    <span class="bg-red-500 text-white text-xs rounded-full px-1.5 py-0.5"><?= $unread ?></span>
                    <?php endif; ?>
                </a>
                <a href="<?= site_url('admin/discounts') ?>" class="sidebar-link flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm text-gray-700 <?= $this->uri->segment(2)=='discounts'?'active':'' ?>">
                    <span class="iconify text-lg" data-icon="lucide:ticket-percent"></span> Diskon & Voucher
                </a>
                <?php endif; ?>
            </nav>
            <div class="p-3 border-t border-gray-200 mt-auto">
                <a href="<?= site_url('admin/auth/logout') ?>" class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm text-red-600 hover:bg-red-50">
                    <span class="iconify text-lg" data-icon="lucide:log-out"></span> Logout
                </a>
            </div>
        </aside>

        <!-- Main Content -->
        <main class="flex-1 ml-64">
            <!-- Top Bar -->
            <header class="bg-white border-b border-gray-200 px-6 py-3 flex items-center justify-between sticky top-0 z-20">
                <h1 class="text-lg font-semibold text-gray-800"><?= $title ?? 'Dashboard' ?></h1>
                <div class="flex items-center gap-2">
                    <div class="w-8 h-8 bg-primary-100 rounded-full flex items-center justify-center">
                        <span class="iconify text-primary-600" data-icon="lucide:user"></span>
                    </div>
                    <span class="text-sm font-medium text-gray-700"><?= $this->session->userdata('admin_nama') ?></span>
                </div>
            </header>

            <!-- Flash Messages -->
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
                    title: 'Gagal!',
                    text: '<?= addslashes($this->session->flashdata("error")) ?>',
                    timer: 3000,
                    showConfirmButton: false,
                    toast: true,
                    position: 'top-end'
                });
            </script>
            <?php endif; ?>

            <!-- Page Content -->
            <div class="p-6">