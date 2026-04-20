<?= $this->load->view('admin/layout/header', ['title'=>$title], TRUE) ?>

<div class="flex items-center justify-between mb-6">
    <div>
        <p class="text-sm text-gray-500 mt-0.5">Pelanggan yang tidak bertransaksi dalam 30 hari terakhir</p>
    </div>
    <a href="<?= site_url('admin/crm') ?>" class="text-sm text-primary-600 hover:underline flex items-center gap-1">
        ← Kembali ke CRM
    </a>
</div>

<div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">
    <table class="w-full text-sm">
        <thead class="bg-gray-50 border-b border-gray-200">
            <tr>
                <th class="text-left px-4 py-3 font-medium text-gray-600">Pelanggan</th>
                <th class="text-left px-4 py-3 font-medium text-gray-600">No. HP</th>
                <th class="text-center px-4 py-3 font-medium text-gray-600">Segmen</th>
                <th class="text-center px-4 py-3 font-medium text-gray-600">Total Transaksi</th>
                <th class="text-left px-4 py-3 font-medium text-gray-600">Terakhir Belanja</th>
                <th class="text-center px-4 py-3 font-medium text-gray-600">Aksi</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-100">
            <?php if(!empty($passive_customers)): ?>
            <?php foreach($passive_customers as $c): ?>
            <tr class="hover:bg-gray-50">
                <td class="px-4 py-3">
                    <div class="font-medium text-gray-800"><?= $c->nama ?></div>
                </td>
                <td class="px-4 py-3 text-gray-600"><?= $c->no_hp ?? '-' ?></td>
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
                    <span class="px-2 py-0.5 rounded-full text-xs font-medium <?= $sc ?>"><?= $c->segment ?? 'Baru' ?></span>
                </td>
                <td class="px-4 py-3 text-center font-medium text-gray-700"><?= $c->total_transaksi ?? 0 ?></td>
                <td class="px-4 py-3 text-gray-500 text-xs">
                    <?= !empty($c->last_order) ? date('d M Y', strtotime($c->last_order)) : 'Belum pernah' ?>
                </td>
                <td class="px-4 py-3 text-center">
                    <a href="<?= site_url('admin/crm/send_promo/'.$c->id) ?>"
                       onclick="return confirm('Buat voucher promo 15% khusus untuk <?= addslashes($c->nama) ?>?')"
                       class="inline-block bg-primary-600 hover:bg-primary-700 text-white text-xs px-3 py-1.5 rounded-lg transition">
                        Kirim Promo
                    </a>
                </td>
            </tr>
            <?php endforeach; ?>
            <?php else: ?>
            <tr>
                <td colspan="6" class="text-center py-12 text-gray-400">
                    <span class="iconify text-4xl mb-2 block" data-icon="lucide:users-round"></span>
                    Tidak ada pelanggan pasif saat ini 🎉
                </td>
            </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<?= $this->load->view('admin/layout/footer', [], TRUE) ?>
