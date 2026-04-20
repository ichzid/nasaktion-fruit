<?= $this->load->view('admin/layout/header', ['title'=>$title], TRUE) ?>

<div class="flex items-center justify-between mb-6">
    <div class="flex items-center gap-3">
        <input type="text" id="searchCust" placeholder="Cari pelanggan..." class="border border-gray-300 rounded-lg px-4 py-2 text-sm focus:ring-2 focus:ring-primary-500 outline-none w-64">
    </div>
    <div class="flex items-center gap-3">
        <a href="<?= site_url('admin/customers/find_duplicates') ?>" class="bg-yellow-500 text-white px-4 py-2 rounded-lg text-sm font-medium hover:bg-yellow-600 transition flex items-center gap-2">
            🔗 Deteksi Duplikat
        </a>
    </div>
</div>

<!-- Stats Cards -->
<div class="grid grid-cols-4 gap-4 mb-6">
    <div class="bg-white rounded-lg border border-gray-200 p-4">
        <p class="text-xs text-gray-500">Total Pelanggan</p>
        <p class="text-2xl font-bold text-gray-800"><?= count($customers) ?></p>
    </div>
    <div class="bg-blue-50 rounded-lg border border-blue-200 p-4">
        <p class="text-xs text-blue-600">Online</p>
        <p class="text-2xl font-bold text-blue-700"><?php $on=0; foreach($customers as $c) if(isset($c->tipe_customer) && $c->tipe_customer=='Online') $on++; echo $on; ?></p>
    </div>
    <div class="bg-gray-50 rounded-lg border border-gray-200 p-4">
        <p class="text-xs text-gray-600">Offline</p>
        <p class="text-2xl font-bold text-gray-700"><?php $off=0; foreach($customers as $c) if(!isset($c->tipe_customer) || $c->tipe_customer=='Offline') $off++; echo $off; ?></p>
    </div>
    <div class="bg-purple-50 rounded-lg border border-purple-200 p-4">
        <p class="text-xs text-purple-600">Unified (Online+Offline)</p>
        <p class="text-2xl font-bold text-purple-700"><?php $uni=0; foreach($customers as $c) if(isset($c->tipe_customer) && $c->tipe_customer=='Unified') $uni++; echo $uni; ?></p>
    </div>
</div>

<div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">
    <table class="w-full text-sm">
        <thead class="bg-gray-50 border-b border-gray-200">
            <tr>
                <th class="text-left px-4 py-3 font-medium text-gray-600">Pelanggan</th>
                <th class="text-left px-4 py-3 font-medium text-gray-600">Kontak</th>
                <th class="text-center px-4 py-3 font-medium text-gray-600">Tipe</th>
                <th class="text-center px-4 py-3 font-medium text-gray-600">Transaksi</th>
                <th class="text-right px-4 py-3 font-medium text-gray-600">Total Belanja</th>
                <th class="text-center px-4 py-3 font-medium text-gray-600">Poin</th>
                <th class="text-center px-4 py-3 font-medium text-gray-600">Segmen</th>
                <th class="text-left px-4 py-3 font-medium text-gray-600">Terakhir Belanja</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-100">
            <?php foreach($customers as $c): ?>
            <?php $tipe = isset($c->tipe_customer) ? $c->tipe_customer : 'Offline'; ?>
            <tr class="hover:bg-gray-50 transition cust-row">
                <td class="px-4 py-3">
                    <div class="flex items-center gap-3">
                        <div class="w-9 h-9 bg-primary-100 rounded-full flex items-center justify-center">
                            <?php if(isset($c->foto) && $c->foto): ?>
                            <img src="<?= $c->foto ?>" class="w-9 h-9 rounded-full object-cover">
                            <?php else: ?>
                            <span class="text-primary-600 text-sm font-bold"><?= strtoupper(substr($c->nama, 0, 1)) ?></span>
                            <?php endif; ?>
                        </div>
                        <div>
                            <p class="font-medium text-gray-800 cust-name"><?= $c->nama ?></p>
                        </div>
                    </div>
                </td>
                <td class="px-4 py-3">
                    <p class="text-gray-600"><?= $c->no_hp ?? '-' ?></p>
                </td>
                <td class="px-4 py-3 text-center">
                    <?php if($tipe == 'Online'): ?>
                    <span class="px-2 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-700">🌐 Online</span>
                    <?php elseif($tipe == 'Unified'): ?>
                    <span class="px-2 py-0.5 rounded-full text-xs font-medium bg-purple-100 text-purple-700">🔗 Unified</span>
                    <?php else: ?>
                    <span class="px-2 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-600">🏪 Offline</span>
                    <?php endif; ?>
                </td>
                <td class="px-4 py-3 text-center font-medium"><?= $c->total_transaksi ?? 0 ?>x</td>
                <td class="px-4 py-3 text-right font-medium">Rp <?= number_format($c->total_belanja ?? 0, 0, ',', '.') ?></td>
                <td class="px-4 py-3 text-center"><span class="bg-primary-100 text-primary-700 px-2 py-0.5 rounded-full text-xs font-medium"><?= $c->point_loyalitas ?></span></td>
                <td class="px-4 py-3 text-center">
                    <?php $seg = $c->segment ?? 'Baru'; ?>
                    <span class="px-2 py-0.5 rounded-full text-xs font-medium <?= $seg=='Platinum'?'bg-purple-100 text-purple-700':($seg=='Gold'?'bg-yellow-100 text-yellow-700':($seg=='Silver'?'bg-gray-200 text-gray-700':'bg-blue-100 text-blue-700')) ?>"><?= $seg ?></span>
                </td>
                <td class="px-4 py-3 text-sm text-gray-500"><?= $c->last_transaction_at ? date('d M Y', strtotime($c->last_transaction_at)) : '-' ?></td>
            </tr>
            <?php endforeach; ?>
            <?php if(empty($customers)): ?>
            <tr><td colspan="8" class="text-center py-8 text-gray-400">Belum ada pelanggan</td></tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<script>
document.getElementById('searchCust').addEventListener('input', function(e) {
    const q = e.target.value.toLowerCase();
    document.querySelectorAll('.cust-row').forEach(row => {
        const name = row.querySelector('.cust-name').textContent.toLowerCase();
        row.style.display = name.includes(q) ? '' : 'none';
    });
});
</script>

<?= $this->load->view('admin/layout/footer', [], TRUE) ?>