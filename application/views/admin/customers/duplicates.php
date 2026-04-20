<?php $this->load->view('admin/layout/header'); ?>

<div class="px-6 py-6 max-w-7xl mx-auto">
    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Deteksi Pelanggan Duplikat</h1>
            <p class="text-sm text-gray-500 mt-1">Gabungkan data pelanggan yang sama agar riwayat belanja online & offline terintegrasi</p>
        </div>
        <a href="<?= site_url('admin/customers') ?>" class="text-sm text-gray-500 hover:text-gray-700 flex items-center gap-1">
            ← Kembali ke Daftar Pelanggan
        </a>
    </div>



    <?php if(empty($duplicates)): ?>
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-12 text-center">
        <span class="text-5xl">✅</span>
        <h3 class="text-lg font-semibold text-gray-700 mt-4">Tidak Ada Duplikat</h3>
        <p class="text-gray-500 mt-2">Semua data pelanggan sudah unik. Tidak ada yang perlu digabungkan.</p>
    </div>
    <?php else: ?>
    <div class="space-y-4">
        <?php foreach($duplicates as $idx => $dup): ?>
        <div class="bg-white rounded-xl shadow-sm border border-yellow-200 overflow-hidden">
            <div class="bg-yellow-50 px-5 py-3 border-b border-yellow-200">
                <div class="flex items-center gap-2">
                    <span class="text-yellow-600 text-lg">⚠️</span>
                    <span class="text-sm font-semibold text-yellow-800">Kemungkinan Duplikat #<?= $idx + 1 ?></span>
                </div>
                <div class="mt-1">
                    <?php foreach($dup['reasons'] as $reason): ?>
                    <span class="inline-block bg-yellow-100 text-yellow-700 text-xs px-2 py-1 rounded-full mr-2 mb-1"><?= $reason ?></span>
                    <?php endforeach; ?>
                </div>
            </div>

            <div class="p-5">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <!-- Customer A -->
                    <div class="border border-gray-200 rounded-lg p-4" id="cust-a-<?= $idx ?>">
                        <div class="flex items-center justify-between mb-3">
                            <span class="text-xs font-semibold text-gray-500 uppercase">Pelanggan A</span>
                            <?php
                            $tipe_a = isset($dup['customer_a']->tipe_customer) ? $dup['customer_a']->tipe_customer : 'Offline';
                            $tipe_color_a = $tipe_a == 'Online' ? 'blue' : ($tipe_a == 'Unified' ? 'purple' : 'gray');
                            ?>
                            <span class="text-xs font-medium px-2 py-0.5 rounded-full bg-<?= $tipe_color_a ?>-100 text-<?= $tipe_color_a ?>-700"><?= $tipe_a ?></span>
                        </div>
                        <h4 class="font-semibold text-gray-800"><?= $dup['customer_a']->nama ?></h4>
                        <div class="mt-2 space-y-1 text-sm text-gray-600">
                            <?php if($dup['customer_a']->no_hp): ?><p>📱 <?= $dup['customer_a']->no_hp ?></p><?php endif; ?>
                            <p>🏷️ Segment: <span class="font-medium"><?= $dup['customer_a']->segment ?></span></p>
                            <p>⭐ Poin: <span class="font-medium"><?= $dup['customer_a']->point_loyalitas ?></span></p>
                            <p>🛒 Total Belanja: <span class="font-medium">Rp <?= number_format($dup['customer_a']->total_belanja, 0, ',', '.') ?></span></p>
                            <p>📊 Transaksi: <span class="font-medium"><?= $dup['customer_a']->total_transaksi ?>x</span></p>
                        </div>
                    </div>

                    <!-- Customer B -->
                    <div class="border border-gray-200 rounded-lg p-4" id="cust-b-<?= $idx ?>">
                        <div class="flex items-center justify-between mb-3">
                            <span class="text-xs font-semibold text-gray-500 uppercase">Pelanggan B</span>
                            <?php
                            $tipe_b = isset($dup['customer_b']->tipe_customer) ? $dup['customer_b']->tipe_customer : 'Offline';
                            $tipe_color_b = $tipe_b == 'Online' ? 'blue' : ($tipe_b == 'Unified' ? 'purple' : 'gray');
                            ?>
                            <span class="text-xs font-medium px-2 py-0.5 rounded-full bg-<?= $tipe_color_b ?>-100 text-<?= $tipe_color_b ?>-700"><?= $tipe_b ?></span>
                        </div>
                        <h4 class="font-semibold text-gray-800"><?= $dup['customer_b']->nama ?></h4>
                        <div class="mt-2 space-y-1 text-sm text-gray-600">
                            <?php if($dup['customer_b']->no_hp): ?><p>📱 <?= $dup['customer_b']->no_hp ?></p><?php endif; ?>
                            <p>🏷️ Segment: <span class="font-medium"><?= $dup['customer_b']->segment ?></span></p>
                            <p>⭐ Poin: <span class="font-medium"><?= $dup['customer_b']->point_loyalitas ?></span></p>
                            <p>🛒 Total Belanja: <span class="font-medium">Rp <?= number_format($dup['customer_b']->total_belanja, 0, ',', '.') ?></span></p>
                            <p>📊 Transaksi: <span class="font-medium"><?= $dup['customer_b']->total_transaksi ?>x</span></p>
                        </div>
                    </div>
                </div>

                <div class="mt-4 pt-4 border-t border-gray-100">
                    <form action="<?= site_url('admin/customers/merge') ?>" method="POST" onsubmit="return confirm('Yakin ingin menggabungkan pelanggan ini? Data transaksi dan poin akan disatukan.')">
                        <input type="hidden" name="<?= $this->security->get_csrf_token_name(); ?>" value="<?= $this->security->get_csrf_hash(); ?>">
                        <input type="hidden" name="primary_id" value="<?= $dup['customer_a']->id ?>">
                        <input type="hidden" name="secondary_id" value="<?= $dup['customer_b']->id ?>">
                        <div class="flex items-center gap-3">
                            <label class="text-sm text-gray-600">Pilih utama:</label>
                            <label class="flex items-center gap-1 text-sm cursor-pointer">
                                <input type="radio" name="primary_id" value="<?= $dup['customer_a']->id ?>" checked onchange="this.form.secondary_id.value=<?= $dup['customer_b']->id ?>"> <?= $dup['customer_a']->nama ?>
                            </label>
                            <label class="flex items-center gap-1 text-sm cursor-pointer">
                                <input type="radio" name="primary_id" value="<?= $dup['customer_b']->id ?>" onchange="this.form.secondary_id.value=<?= $dup['customer_a']->id ?>"> <?= $dup['customer_b']->nama ?>
                            </label>
                            <button type="submit" class="ml-auto bg-primary-600 text-white px-4 py-2 rounded-lg text-sm font-medium hover:bg-primary-700 transition">
                                🔗 Gabungkan Pelanggan
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
    <?php endif; ?>
</div>

<?php $this->load->view('admin/layout/footer'); ?>