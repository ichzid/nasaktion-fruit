<?= $this->load->view('admin/layout/header', ['title'=>$title], TRUE) ?>

<!-- CRM Stats -->
<div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
    <div class="bg-white rounded-xl p-6 border border-gray-200 shadow-sm">
        <div class="flex items-center justify-between">
            <div><p class="text-sm text-gray-500">Total Pelanggan</p><p class="text-2xl font-bold text-gray-800 mt-1"><?= $total_customers ?></p></div>
            <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center"><span class="iconify text-blue-600 text-2xl" data-icon="lucide:users"></span></div>
        </div>
    </div>
    <div class="bg-white rounded-xl p-6 border border-gray-200 shadow-sm">
        <div class="flex items-center justify-between">
            <div><p class="text-sm text-gray-500">Rata-rata Rating</p><p class="text-2xl font-bold text-gray-800 mt-1"><?= number_format($avg_rating, 1) ?> ⭐</p></div>
            <div class="w-12 h-12 bg-yellow-100 rounded-lg flex items-center justify-center"><span class="iconify text-yellow-600 text-2xl" data-icon="lucide:star"></span></div>
        </div>
    </div>
    <div class="bg-white rounded-xl p-6 border border-gray-200 shadow-sm">
        <div class="flex items-center justify-between">
            <div><p class="text-sm text-gray-500">Total Feedback</p><p class="text-2xl font-bold text-gray-800 mt-1"><?= $total_feedback ?></p></div>
            <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center"><span class="iconify text-green-600 text-2xl" data-icon="lucide:message-square"></span></div>
        </div>
    </div>
    <div class="bg-white rounded-xl p-6 border border-gray-200 shadow-sm">
        <div class="flex items-center justify-between">
            <div><p class="text-sm text-gray-500">Voucher Aktif</p><p class="text-2xl font-bold text-gray-800 mt-1"><?= $active_vouchers ?></p></div>
            <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center"><span class="iconify text-purple-600 text-2xl" data-icon="lucide:tag"></span></div>
        </div>
    </div>
</div>

<!-- Segments -->
<div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
    <div class="bg-gradient-to-r from-gray-50 to-gray-100 rounded-xl p-4 border border-gray-200 text-center">
        <p class="text-3xl font-bold text-gray-700"><?= $segment_baru ?></p><p class="text-sm text-gray-500">🆕 Baru</p>
    </div>
    <div class="bg-gradient-to-r from-gray-100 to-gray-200 rounded-xl p-4 border border-gray-300 text-center">
        <p class="text-3xl font-bold text-gray-600"><?= $segment_silver ?></p><p class="text-sm text-gray-500">🥈 Silver</p>
    </div>
    <div class="bg-gradient-to-r from-yellow-50 to-yellow-100 rounded-xl p-4 border border-yellow-200 text-center">
        <p class="text-3xl font-bold text-yellow-600"><?= $segment_gold ?></p><p class="text-sm text-gray-500">🥇 Gold</p>
    </div>
    <div class="bg-gradient-to-r from-purple-50 to-purple-100 rounded-xl p-4 border border-purple-200 text-center">
        <p class="text-3xl font-bold text-purple-600"><?= $segment_platinum ?></p><p class="text-sm text-gray-500">💎 Platinum</p>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
    <!-- Passive Customers -->
    <div class="bg-white rounded-xl border border-gray-200 shadow-sm">
        <div class="p-4 border-b border-gray-200 flex items-center justify-between">
            <h3 class="font-semibold text-gray-800">⚠️ Pelanggan Pasif</h3>
            <a href="<?= site_url('admin/crm/passive_customers') ?>" class="text-sm text-primary-600 hover:underline">Lihat Semua</a>
        </div>
        <div class="p-4">
            <?php if(!empty($passive_customers)): ?>
            <div class="space-y-3">
                <?php foreach(array_slice($passive_customers, 0, 5) as $c): ?>
                <div class="flex items-center justify-between py-2 border-b border-gray-100 last:border-0">
                    <div>
                        <p class="text-sm font-medium text-gray-800"><?= $c->nama ?></p>
                        <p class="text-xs text-gray-500">Terakhir belanja: <?= !empty($c->last_transaction_at) ? date('d M Y', strtotime($c->last_transaction_at)) : 'Belum pernah' ?></p>
                    </div>
                    <a href="<?= site_url('admin/crm/send_promo/'.$c->id) ?>" class="text-xs bg-primary-100 text-primary-700 px-3 py-1 rounded-full hover:bg-primary-200 transition" onclick="return confirm('Kirim promo ke pelanggan ini?')">Kirim Promo</a>
                </div>
                <?php endforeach; ?>
            </div>
            <?php else: ?>
            <p class="text-gray-400 text-sm text-center py-4">Tidak ada pelanggan pasif</p>
            <?php endif; ?>
        </div>
    </div>

    <!-- Quick Links -->
    <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-6">
        <h3 class="font-semibold text-gray-800 mb-4">🔗 Menu CRM</h3>
        <div class="grid grid-cols-2 gap-3">
            <a href="<?= site_url('admin/crm/loyalty') ?>" class="flex items-center gap-3 p-4 rounded-xl border border-gray-200 hover:border-primary-400 hover:shadow-md transition">
                <span class="iconify text-2xl text-yellow-500" data-icon="lucide:trophy"></span>
                <div><p class="text-sm font-medium text-gray-800">Analisis Loyalitas</p><p class="text-xs text-gray-500">Segmentasi pelanggan</p></div>
            </a>
            <a href="<?= site_url('admin/crm/segments') ?>" class="flex items-center gap-3 p-4 rounded-xl border border-gray-200 hover:border-primary-400 hover:shadow-md transition">
                <span class="iconify text-2xl text-blue-500" data-icon="lucide:pie-chart"></span>
                <div><p class="text-sm font-medium text-gray-800">Segmen Pelanggan</p><p class="text-xs text-gray-500">Kelompok pelanggan</p></div>
            </a>
            <a href="<?= site_url('admin/discounts') ?>" class="flex items-center gap-3 p-4 rounded-xl border border-gray-200 hover:border-primary-400 hover:shadow-md transition">
                <span class="iconify text-2xl text-green-500" data-icon="lucide:tag"></span>
                <div><p class="text-sm font-medium text-gray-800">Diskon & Voucher</p><p class="text-xs text-gray-500">Kelola promo</p></div>
            </a>
            <a href="<?= site_url('admin/feedback') ?>" class="flex items-center gap-3 p-4 rounded-xl border border-gray-200 hover:border-primary-400 hover:shadow-md transition">
                <span class="iconify text-2xl text-purple-500" data-icon="lucide:message-square"></span>
                <div><p class="text-sm font-medium text-gray-800">Feedback</p><p class="text-xs text-gray-500">Ulasan pelanggan</p></div>
            </a>
        </div>
    </div>
</div>

<?= $this->load->view('admin/layout/footer', [], TRUE) ?>