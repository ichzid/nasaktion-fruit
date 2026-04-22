<?= $this->load->view('customer/layout/header', ['title' => $title], TRUE) ?>

<div class="mb-8">
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 mb-6">
        <div>
            <h2 class="font-display font-black text-2xl text-gray-900">Pesanan Saya</h2>
            <p class="text-sm text-gray-500">Pantau status dan riwayat seluruh pesanan buah segar Anda.</p>
        </div>
        <a href="<?= site_url('customer/shop') ?>" class="bg-primary-50 text-primary-600 px-5 py-2.5 rounded-xl font-bold hover:bg-primary-100 transition inline-flex items-center gap-2 text-sm">
            <span class="iconify" data-icon="lucide:plus"></span> Belanja Lagi
        </a>
    </div>

    <?php if(empty($transactions)): ?>
    <div class="bg-white rounded-3xl border border-gray-100 p-12 text-center shadow-sm">
        <div class="w-20 h-20 bg-gray-50 rounded-full flex items-center justify-center mx-auto mb-4">
            <span class="iconify text-4xl text-gray-300" data-icon="lucide:database"></span>
        </div>
        <p class="text-gray-500 font-medium">Belum ada riwayat pesanan.</p>
        <p class="text-sm text-gray-400 mt-1 mb-6">Mulai belanja pertama Anda hari ini!</p>
        <a href="<?= site_url('customer/shop') ?>" class="bg-primary-600 text-white px-6 py-2.5 rounded-xl font-bold hover:bg-primary-700 transition shadow-lg shadow-primary-200">Lihat Produk</a>
    </div>
    <?php else: ?>
    <div class="grid grid-cols-1 gap-4">
        <?php foreach($transactions as $t): ?>
        <div class="bg-white rounded-2xl border border-gray-100 p-4 sm:p-5 hover:border-primary-200 transition-colors shadow-sm group">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                <div class="flex items-start gap-4">
                    <div class="w-12 h-12 bg-primary-50 rounded-xl flex items-center justify-center flex-shrink-0 text-primary-600">
                        <span class="iconify text-2xl" data-icon="lucide:shopping-bag"></span>
                    </div>
                    <div>
                        <div class="flex items-center gap-2 mb-1">
                            <span class="font-display font-bold text-gray-900"><?= $t->invoice_no ?></span>
                            <?php
                                $st_map = [
                                    'pending' => ['Tertunda', 'bg-amber-100 text-amber-700'],
                                    'paid' => ['Dibayar', 'bg-blue-100 text-blue-700'],
                                    'verified' => ['Di Proses', 'bg-indigo-100 text-indigo-700'],
                                    'shipped' => ['Dikirim', 'bg-cyan-100 text-cyan-700'],
                                    'completed' => ['Selesai', 'bg-green-100 text-green-700'],
                                    'cancelled' => ['Dibatalkan', 'bg-red-100 text-red-700']
                                ];
                                $st = $t->status ?? 'pending';
                                $st_label = $st_map[$st][0] ?? ucfirst($st);
                                $st_col = $st_map[$st][1] ?? 'bg-gray-100 text-gray-700';
                            ?>
                            <span class="text-[10px] sm:text-xs px-2 py-0.5 rounded-full font-bold uppercase <?= $st_col ?>">
                                <?= $st_label ?>
                            </span>
                        </div>
                        <p class="text-xs text-gray-400"><?= date('d M Y, H:i', strtotime($t->tgl)) ?> WIB</p>
                    </div>
                </div>
                
                <div class="flex items-center justify-between sm:justify-end gap-6 border-t sm:border-t-0 pt-4 sm:pt-0">
                    <div class="text-right">
                        <p class="text-xs text-gray-400 mb-0.5">Total Bayar</p>
                        <p class="font-display font-black text-primary-600">Rp <?= number_format($t->total, 0, ',', '.') ?></p>
                    </div>
                    <a href="<?= site_url('customer/dashboard/order_detail/'.$t->id) ?>" class="bg-white border border-gray-200 text-gray-600 px-5 py-2.5 rounded-xl text-sm font-bold hover:bg-gray-50 hover:border-gray-300 transition shrink-0">
                        Detail
                    </a>
                </div>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
    <?php endif; ?>
</div>

<?= $this->load->view('customer/layout/footer', [], TRUE) ?>
