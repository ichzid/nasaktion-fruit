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
                        <?php 
                            $st_badge = [
                                'pending' => ['Tertunda', 'bg-amber-100 text-amber-700'],
                                'paid' => ['Dibayar', 'bg-blue-100 text-blue-700'],
                                'verified' => ['Di Proses', 'bg-indigo-100 text-indigo-700'],
                                'shipped' => ['Dikirim', 'bg-cyan-100 text-cyan-700'],
                                'completed' => ['Selesai', 'bg-green-100 text-green-700'],
                                'cancelled' => ['Dibatalkan', 'bg-red-100 text-red-700']
                            ];
                            $st_key = $t->status ?? 'pending';
                            $st_label = isset($st_badge[$st_key]) ? $st_badge[$st_key][0] : ucfirst($st_key);
                            $st_color = isset($st_badge[$st_key]) ? $st_badge[$st_key][1] : 'bg-gray-100 text-gray-700';
                        ?>
                        <span class="text-[10px] uppercase tracking-wider px-2 py-0.5 rounded-full font-bold <?= $st_color ?>"><?= $st_label ?></span>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
            <?php else: ?>
            <p class="text-gray-400 text-sm text-center py-4">Belum ada transaksi</p>
            <?php endif; ?>
        </div>
    </div>

    <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden flex flex-col">
        <div class="p-4 border-b border-gray-200 flex items-center justify-between bg-gray-50/50">
            <div class="flex items-center gap-2">
                <span class="iconify text-red-600" data-icon="lucide:alert-circle"></span>
                <h3 class="font-bold text-gray-800">Stok Menipis & Habis</h3>
            </div>
            <a href="<?= site_url('admin/products') ?>" class="text-xs font-bold text-primary-600 hover:text-primary-700 uppercase tracking-wider">Kelola Stok →</a>
        </div>
        <div class="p-4 flex-1">
            <?php if(!empty($low_stock_products)): ?>
            <div class="space-y-4">
                <?php foreach($low_stock_products as $p): ?>
                <div class="flex items-center justify-between group">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 <?= $p->stok <= 0 ? 'bg-red-100 text-red-600' : 'bg-amber-100 text-amber-600' ?> rounded-xl flex items-center justify-center transition-transform group-hover:scale-110 shadow-sm">
                            <span class="iconify text-xl font-bold" data-icon="<?= $p->stok <= 0 ? 'lucide:octagon-x' : 'lucide:alert-triangle' ?>"></span>
                        </div>
                        <div>
                            <p class="text-sm font-bold text-gray-800 group-hover:text-primary-700 transition-colors"><?= $p->nama_buah ?></p>
                            <p class="text-[10px] text-gray-400 uppercase tracking-widest font-bold"><?= $p->jenis ?></p>
                        </div>
                    </div>
                    <div class="text-right">
                        <div class="flex flex-col items-end gap-1">
                            <span class="text-sm font-black <?= $p->stok <= 0 ? 'text-red-600' : ($p->stok <= 5 ? 'text-orange-600' : 'text-amber-600') ?>">
                                <?= $p->stok ?> <?= $p->satuan ?>
                            </span>
                            <?php if($p->stok <= 0): ?>
                                <span class="px-2 py-0.5 rounded text-[9px] font-bold uppercase bg-red-600 text-white shadow-sm ring-1 ring-red-700">Habis</span>
                            <?php elseif($p->stok <= 5): ?>
                                <span class="px-2 py-0.5 rounded text-[9px] font-bold uppercase bg-orange-100 text-orange-700 border border-orange-200">Kritis</span>
                            <?php else: ?>
                                <span class="px-2 py-0.5 rounded text-[9px] font-bold uppercase bg-amber-100 text-amber-700 border border-amber-200">Menipis</span>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
            <?php else: ?>
            <div class="flex flex-col items-center justify-center py-8 opacity-40">
                <span class="iconify text-4xl text-gray-300" data-icon="lucide:check-circle"></span>
                <p class="text-gray-500 text-xs font-bold uppercase tracking-wider mt-2">Semua stok aman</p>
            </div>
            <?php endif; ?>
        </div>
        <!-- Legend -->
        <div class="px-4 py-3 bg-gray-50/80 border-t border-gray-100 flex flex-wrap gap-x-4 gap-y-2">
            <div class="flex items-center gap-1.5">
                <span class="w-1.5 h-1.5 rounded-full bg-red-600"></span>
                <span class="text-[10px] font-bold text-gray-500 uppercase tracking-tighter">Habis: 0</span>
            </div>
            <div class="flex items-center gap-1.5">
                <span class="w-1.5 h-1.5 rounded-full bg-orange-500"></span>
                <span class="text-[10px] font-bold text-gray-500 uppercase tracking-tighter">Kritis: 1-5</span>
            </div>
            <div class="flex items-center gap-1.5">
                <span class="w-1.5 h-1.5 rounded-full bg-amber-400"></span>
                <span class="text-[10px] font-bold text-gray-500 uppercase tracking-tighter">Menipis: 6-10</span>
            </div>
        </div>
    </div>
</div>

<?= $this->load->view('admin/layout/footer', [], TRUE) ?>