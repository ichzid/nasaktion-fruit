<?= $this->load->view('admin/layout/header', ['title'=>$title], TRUE) ?>

<!-- Stats Cards -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
    <div class="bg-white rounded-xl p-6 border border-gray-200 shadow-sm">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm text-gray-500">Total Produk</p>
                <p class="text-2xl font-bold text-gray-800 mt-1"><?= $total_products ?? 0 ?></p>
            </div>
            <div class="w-12 h-12 bg-primary-100 rounded-lg flex items-center justify-center">
                <span class="iconify text-primary-600 text-2xl" data-icon="lucide:apple"></span>
            </div>
        </div>
    </div>
    <div class="bg-white rounded-xl p-6 border border-gray-200 shadow-sm">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm text-gray-500">Transaksi Hari Ini</p>
                <p class="text-2xl font-bold text-gray-800 mt-1"><?= $today_transactions ?? 0 ?></p>
            </div>
            <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                <span class="iconify text-blue-600 text-2xl" data-icon="lucide:receipt"></span>
            </div>
        </div>
    </div>
    <div class="bg-white rounded-xl p-6 border border-gray-200 shadow-sm">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm text-gray-500">Pelanggan Aktif</p>
                <p class="text-2xl font-bold text-gray-800 mt-1"><?= $total_customers ?? 0 ?></p>
            </div>
            <div class="w-12 h-12 bg-accent-100 rounded-lg flex items-center justify-center">
                <span class="iconify text-accent-600 text-2xl" data-icon="lucide:users"></span>
            </div>
        </div>
    </div>
    <div class="bg-white rounded-xl p-6 border border-gray-200 shadow-sm">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm text-gray-500">Pendapatan Hari Ini</p>
                <p class="text-2xl font-bold text-gray-800 mt-1">Rp <?= number_format($today_revenue ?? 0, 0, ',', '.') ?></p>
            </div>
            <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                <span class="iconify text-green-600 text-2xl" data-icon="lucide:banknote"></span>
            </div>
        </div>
    </div>
</div>

<!-- Recent & Low Stock -->
<div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
    <div class="bg-white rounded-xl border border-gray-200 shadow-sm">
        <div class="p-4 border-b border-gray-200 flex items-center justify-between">
            <h3 class="font-semibold text-gray-800">Transaksi Terbaru</h3>
            <a href="<?= site_url('admin/transactions') ?>" class="text-sm text-primary-600 hover:underline">Lihat Semua</a>
        </div>
        <div class="p-4">
            <?php if(!empty($recent_transactions)): ?>
            <div class="space-y-3">
                <?php foreach($recent_transactions as $t): ?>
                <div class="flex items-center justify-between py-2 border-b border-gray-100 last:border-0">
                    <div>
                        <p class="text-sm font-medium text-gray-800"><?= $t->invoice_no ?></p>
                        <p class="text-xs text-gray-500"><?= $t->nama ?? 'Guest' ?> · <?= $t->jenis_order ?></p>
                    </div>
                    <div class="text-right">
                        <p class="text-sm font-semibold text-gray-800">Rp <?= number_format($t->total, 0, ',', '.') ?></p>
                        <span class="text-xs px-2 py-0.5 rounded-full <?= $t->status=='completed'?'bg-green-100 text-green-700':($t->status=='pending'?'bg-yellow-100 text-yellow-700':'bg-blue-100 text-blue-700') ?>"><?= ucfirst($t->status) ?></span>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
            <?php else: ?>
            <p class="text-gray-400 text-sm text-center py-4">Belum ada transaksi</p>
            <?php endif; ?>
        </div>
    </div>

    <div class="bg-white rounded-xl border border-gray-200 shadow-sm">
        <div class="p-4 border-b border-gray-200 flex items-center justify-between">
            <h3 class="font-semibold text-gray-800">Stok Menipis</h3>
            <a href="<?= site_url('admin/products') ?>" class="text-sm text-primary-600 hover:underline">Kelola Produk</a>
        </div>
        <div class="p-4">
            <?php if(!empty($low_stock_products)): ?>
            <div class="space-y-3">
                <?php foreach($low_stock_products as $p): ?>
                <div class="flex items-center justify-between py-2 border-b border-gray-100 last:border-0">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 bg-red-100 rounded-lg flex items-center justify-center">
                            <span class="iconify text-red-500" data-icon="lucide:alert-triangle"></span>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-800"><?= $p->nama_buah ?></p>
                            <p class="text-xs text-gray-500"><?= $p->jenis ?></p>
                        </div>
                    </div>
                    <span class="text-sm font-bold text-red-600"><?= $p->stok ?> <?= $p->satuan ?></span>
                </div>
                <?php endforeach; ?>
            </div>
            <?php else: ?>
            <p class="text-gray-400 text-sm text-center py-4">Semua stok aman</p>
            <?php endif; ?>
        </div>
    </div>
</div>

<?= $this->load->view('admin/layout/footer', [], TRUE) ?>