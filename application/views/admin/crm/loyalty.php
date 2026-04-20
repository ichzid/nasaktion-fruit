<?= $this->load->view('admin/layout/header', ['title'=>$title], TRUE) ?>

<!-- Stat Segmen -->
<div class="grid grid-cols-4 gap-4 mb-6">
    <?php
    $segments = [
        ['label'=>'Baru',     'count'=>$segment_baru,     'color'=>'bg-blue-50 text-blue-700',   'border'=>'border-blue-200'],
        ['label'=>'Silver',   'count'=>$segment_silver,   'color'=>'bg-gray-50 text-gray-700',   'border'=>'border-gray-200'],
        ['label'=>'Gold',     'count'=>$segment_gold,     'color'=>'bg-yellow-50 text-yellow-700','border'=>'border-yellow-200'],
        ['label'=>'Platinum', 'count'=>$segment_platinum, 'color'=>'bg-purple-50 text-purple-700','border'=>'border-purple-200'],
    ];
    foreach($segments as $s): ?>
    <div class="bg-white rounded-xl border <?= $s['border'] ?> p-4 text-center shadow-sm">
        <p class="text-2xl font-bold <?= $s['color'] ?> rounded-lg py-1"><?= $s['count'] ?></p>
        <p class="text-sm text-gray-500 mt-1">Segmen <?= $s['label'] ?></p>
    </div>
    <?php endforeach; ?>
</div>

<!-- Tabel Loyalitas -->
<div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">
    <div class="p-4 border-b border-gray-100 flex items-center justify-between">
        <h3 class="font-semibold text-gray-800">Semua Pelanggan — Peringkat Loyalitas</h3>
        <span class="text-sm text-gray-400"><?= count($customers) ?> pelanggan</span>
    </div>
    <table class="w-full text-sm">
        <thead class="bg-gray-50 border-b border-gray-200">
            <tr>
                <th class="text-center px-4 py-3 font-medium text-gray-600 w-10">#</th>
                <th class="text-left px-4 py-3 font-medium text-gray-600">Pelanggan</th>
                <th class="text-center px-4 py-3 font-medium text-gray-600">Segmen</th>
                <th class="text-center px-4 py-3 font-medium text-gray-600">Total Transaksi</th>
                <th class="text-right px-4 py-3 font-medium text-gray-600">Total Belanja</th>
                <th class="text-center px-4 py-3 font-medium text-gray-600">Poin</th>
                <th class="text-left px-4 py-3 font-medium text-gray-600">Terakhir Belanja</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-100">
            <?php if(!empty($customers)): ?>
            <?php foreach($customers as $i => $c): ?>
            <tr class="hover:bg-gray-50">
                <td class="px-4 py-3 text-center text-gray-400 font-medium"><?= $i+1 ?></td>
                <td class="px-4 py-3">
                    <div class="font-medium text-gray-800"><?= $c->nama ?></div>
                    <div class="text-xs text-gray-400"><?= $c->no_hp ?? '-' ?></div>
                </td>
                <td class="px-4 py-3 text-center">
                    <?php
                    $seg_color = [
                        'Platinum' => 'bg-purple-100 text-purple-700',
                        'Gold'     => 'bg-yellow-100 text-yellow-700',
                        'Silver'   => 'bg-gray-100 text-gray-600',
                        'Baru'     => 'bg-blue-100 text-blue-700',
                    ];
                    $sc = $seg_color[$c->segment ?? 'Baru'] ?? 'bg-gray-100 text-gray-600';
                    ?>
                    <span class="px-2 py-0.5 rounded-full text-xs font-semibold <?= $sc ?>"><?= $c->segment ?? 'Baru' ?></span>
                </td>
                <td class="px-4 py-3 text-center font-medium text-gray-700"><?= number_format($c->total_transaksi) ?></td>
                <td class="px-4 py-3 text-right font-semibold text-primary-600">Rp <?= number_format($c->total_belanja, 0, ',', '.') ?></td>
                <td class="px-4 py-3 text-center">
                    <span class="bg-amber-50 text-amber-700 text-xs font-semibold px-2 py-0.5 rounded-full border border-amber-200">
                        ⭐ <?= number_format($c->point_loyalitas) ?>
                    </span>
                </td>
                <td class="px-4 py-3 text-xs text-gray-500">
                    <?= !empty($c->last_order) ? date('d M Y', strtotime($c->last_order)) : '-' ?>
                </td>
            </tr>
            <?php endforeach; ?>
            <?php else: ?>
            <tr>
                <td colspan="7" class="text-center py-10 text-gray-400">Belum ada data pelanggan</td>
            </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<?= $this->load->view('admin/layout/footer', [], TRUE) ?>
