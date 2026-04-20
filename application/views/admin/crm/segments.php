<?= $this->load->view('admin/layout/header', ['title'=>$title], TRUE) ?>

<?php
$all_segments = [
    'Platinum' => ['data' => $platinum, 'color' => 'purple', 'icon' => 'lucide:gem',      'desc' => 'Transaksi & belanja tertinggi'],
    'Gold'     => ['data' => $gold,     'color' => 'yellow', 'icon' => 'lucide:star',      'desc' => 'Pelanggan setia & rutin belanja'],
    'Silver'   => ['data' => $silver,   'color' => 'gray',   'icon' => 'lucide:award',     'desc' => 'Pelanggan aktif yang berkembang'],
    'Baru'     => ['data' => $baru,     'color' => 'blue',   'icon' => 'lucide:user-plus', 'desc' => 'Baru bergabung / transaksi pertama'],
];
$color_map = [
    'purple' => ['tab_active' => 'border-purple-500 text-purple-700 bg-purple-50', 'tab_inactive' => 'text-gray-500 hover:text-purple-600', 'badge' => 'bg-purple-100 text-purple-700'],
    'yellow' => ['tab_active' => 'border-yellow-500 text-yellow-700 bg-yellow-50', 'tab_inactive' => 'text-gray-500 hover:text-yellow-600', 'badge' => 'bg-yellow-100 text-yellow-700'],
    'gray'   => ['tab_active' => 'border-gray-500 text-gray-700 bg-gray-50',       'tab_inactive' => 'text-gray-500 hover:text-gray-700',   'badge' => 'bg-gray-200 text-gray-700'],
    'blue'   => ['tab_active' => 'border-blue-500 text-blue-700 bg-blue-50',       'tab_inactive' => 'text-gray-500 hover:text-blue-600',   'badge' => 'bg-blue-100 text-blue-700'],
];
?>

<!-- Info Kriteria Segmen -->
<div class="bg-blue-50 border border-blue-100 rounded-xl p-4 mb-6 flex gap-4">
    <div class="mt-0.5 text-blue-500">
        <span class="iconify text-xl" data-icon="lucide:info"></span>
    </div>
    <div>
        <h4 class="font-semibold text-blue-800 mb-2">Kriteria Klasifikasi Segmen Pelanggan</h4>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 text-sm mt-1">
            <div class="bg-white/60 rounded-lg p-2.5 border border-purple-100/50">
                <p class="font-bold text-purple-700 flex items-center gap-1.5"><span class="iconify" data-icon="lucide:gem"></span> Platinum</p>
                <p class="text-blue-700/80 text-xs mt-1">> 10 Transaksi <br>Atau > Rp 5 Juta total belanja</p>
            </div>
            <div class="bg-white/60 rounded-lg p-2.5 border border-yellow-100/50">
                <p class="font-bold text-yellow-600 flex items-center gap-1.5"><span class="iconify" data-icon="lucide:star"></span> Gold</p>
                <p class="text-blue-700/80 text-xs mt-1">> 6 Transaksi <br>Atau > Rp 2 Juta total belanja</p>
            </div>
            <div class="bg-white/60 rounded-lg p-2.5 border border-gray-200/50">
                <p class="font-bold text-gray-600 flex items-center gap-1.5"><span class="iconify" data-icon="lucide:award"></span> Silver</p>
                <p class="text-blue-700/80 text-xs mt-1">> 3 Transaksi <br>Atau > Rp 500 Ribu total belanja</p>
            </div>
            <div class="bg-white/60 rounded-lg p-2.5 border border-blue-200/50">
                <p class="font-bold text-blue-600 flex items-center gap-1.5"><span class="iconify" data-icon="lucide:user-plus"></span> Baru</p>
                <p class="text-blue-700/80 text-xs mt-1">Pelanggan default<br>Di bawah standar Silver</p>
            </div>
        </div>
    </div>
</div>

<!-- Tab Buttons -->
<div class="flex gap-1 mb-6 bg-white border border-gray-200 rounded-xl p-1.5 shadow-sm w-fit">
    <?php foreach($all_segments as $name => $seg):
        $c = $color_map[$seg['color']];
        $count = count($seg['data']);
    ?>
    <button
        onclick="switchTab('<?= $name ?>')"
        id="tab-btn-<?= $name ?>"
        class="tab-btn flex items-center gap-2 px-4 py-2 rounded-lg text-sm font-medium transition-all border-b-2 border-transparent <?= $name === 'Platinum' ? $c['tab_active'] . ' border-b-2' : $c['tab_inactive'] ?>">
        <span class="iconify" data-icon="<?= $seg['icon'] ?>"></span>
        <?= $name ?>
        <span class="px-1.5 py-0.5 rounded-full text-xs font-bold <?= $c['badge'] ?>"><?= $count ?></span>
    </button>
    <?php endforeach; ?>
</div>

<!-- Tab Panels -->
<?php foreach($all_segments as $name => $seg):
    $customers = $seg['data'];
    $c = $color_map[$seg['color']];
?>
<div id="tab-<?= $name ?>" class="tab-panel <?= $name !== 'Platinum' ? 'hidden' : '' ?>">
    <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">
        <div class="px-5 py-4 border-b border-gray-100 flex items-center justify-between">
            <div>
                <h3 class="font-semibold text-gray-800">Segmen <span class="<?= explode(' ', $c['badge'])[1] ?>"><?= $name ?></span></h3>
                <p class="text-xs text-gray-400 mt-0.5"><?= $seg['desc'] ?></p>
            </div>
            <span class="text-sm text-gray-500"><?= count($customers) ?> pelanggan</span>
        </div>

        <?php if(!empty($customers)): ?>
        <table class="w-full text-sm">
            <thead class="bg-gray-50 border-b border-gray-100">
                <tr>
                    <th class="text-left px-4 py-3 font-medium text-gray-500">Nama</th>
                    <th class="text-left px-4 py-3 font-medium text-gray-500">Kontak</th>
                    <th class="text-center px-4 py-3 font-medium text-gray-500">Transaksi</th>
                    <th class="text-right px-4 py-3 font-medium text-gray-500">Total Belanja</th>
                    <th class="text-center px-4 py-3 font-medium text-gray-500">Poin</th>
                    <th class="text-left px-4 py-3 font-medium text-gray-500">Terakhir Belanja</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                <?php foreach($customers as $cust): ?>
                <tr class="hover:bg-gray-50">
                    <td class="px-4 py-3">
                        <span class="font-medium text-gray-800"><?= $cust->nama ?></span>
                        <?php if(!empty($cust->tipe_customer)): ?>
                        <span class="ml-1 text-xs px-1.5 py-0.5 rounded bg-gray-100 text-gray-400"><?= $cust->tipe_customer ?></span>
                        <?php endif; ?>
                    </td>
                    <td class="px-4 py-3 text-xs text-gray-500">
                        <?= $cust->no_hp ?? '-' ?>
                    </td>
                    <td class="px-4 py-3 text-center font-medium text-gray-700"><?= $cust->total_transaksi ?? 0 ?></td>
                    <td class="px-4 py-3 text-right font-semibold text-primary-600">Rp <?= number_format($cust->total_belanja ?? 0, 0, ',', '.') ?></td>
                    <td class="px-4 py-3 text-center text-xs font-semibold text-amber-600">⭐ <?= number_format($cust->point_loyalitas ?? 0) ?></td>
                    <td class="px-4 py-3 text-xs text-gray-500">
                        <?= !empty($cust->last_transaction_at) ? date('d M Y', strtotime($cust->last_transaction_at)) : '-' ?>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <?php else: ?>
        <div class="py-12 text-center">
            <span class="iconify text-4xl text-gray-300 mb-2 block" data-icon="lucide:users-round"></span>
            <p class="text-gray-400 text-sm">Belum ada pelanggan di segmen ini</p>
        </div>
        <?php endif; ?>
    </div>
</div>
<?php endforeach; ?>

<script>
const tabColors = {
    'Platinum': 'border-purple-500 text-purple-700 bg-purple-50',
    'Gold':     'border-yellow-500 text-yellow-700 bg-yellow-50',
    'Silver':   'border-gray-500 text-gray-700 bg-gray-50',
    'Baru':     'border-blue-500 text-blue-700 bg-blue-50',
};
const tabInactive = 'text-gray-500 border-transparent bg-transparent';

function switchTab(name) {
    // Hide all panels
    document.querySelectorAll('.tab-panel').forEach(p => p.classList.add('hidden'));
    // Reset all buttons
    document.querySelectorAll('.tab-btn').forEach(b => {
        b.className = b.className
            .replace(/border-\S+-500/g, 'border-transparent')
            .replace(/text-\S+-700/g, 'text-gray-500')
            .replace(/bg-\S+-50/g, 'bg-transparent');
    });

    // Show target panel
    document.getElementById('tab-' + name).classList.remove('hidden');
    // Activate button
    const btn = document.getElementById('tab-btn-' + name);
    const active = tabColors[name];
    active.split(' ').forEach(cls => btn.classList.add(cls));
    btn.classList.remove('text-gray-500', 'border-transparent', 'bg-transparent');
}
</script>

<?= $this->load->view('admin/layout/footer', [], TRUE) ?>
