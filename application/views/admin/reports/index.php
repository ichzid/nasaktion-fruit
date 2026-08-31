<?= $this->load->view('admin/layout/header', ['title'=>$title], TRUE) ?>

<div class="flex gap-2 mb-6">
    <a href="<?= site_url('admin/reports/sales') ?>" class="px-4 py-2 rounded-lg text-sm font-medium <?= $report_type === 'sales' ? 'bg-primary-600 text-white' : 'bg-white border text-gray-700' ?>">Penjualan</a>
    <a href="<?= site_url('admin/reports/stock') ?>" class="px-4 py-2 rounded-lg text-sm font-medium <?= $report_type === 'stock' ? 'bg-primary-600 text-white' : 'bg-white border text-gray-700' ?>">Stok</a>
</div>

<?php if ($report_type === 'sales'): ?>
<form method="get" action="<?= site_url('admin/reports/sales') ?>" class="bg-white border border-gray-200 rounded-xl p-4 mb-6 flex flex-wrap items-end gap-4">
    <div><label class="block text-sm font-medium mb-1">Tanggal Mulai</label><input type="date" name="start_date" value="<?= html_escape($start_date) ?>" class="border rounded-lg px-3 py-2"></div>
    <div><label class="block text-sm font-medium mb-1">Tanggal Selesai</label><input type="date" name="end_date" value="<?= html_escape($end_date) ?>" class="border rounded-lg px-3 py-2"></div>
    <button class="bg-primary-600 text-white px-4 py-2 rounded-lg">Terapkan</button>
    <a href="<?= site_url('admin/reports/export-sales') . '?' . http_build_query(['start_date'=>$start_date, 'end_date'=>$end_date]) ?>" class="bg-green-700 text-white px-4 py-2 rounded-lg">Export Excel</a>
</form>
<div class="bg-white border border-gray-200 rounded-xl overflow-x-auto">
<table class="w-full text-sm"><thead class="bg-gray-50"><tr><th class="p-3 text-left">Invoice</th><th class="p-3 text-left">Tanggal</th><th class="p-3 text-left">Pelanggan</th><th class="p-3 text-left">Jenis</th><th class="p-3 text-left">Status</th><th class="p-3 text-right">Total</th></tr></thead><tbody>
<?php $grand_total = 0; foreach ($sales as $item): $grand_total += $item->total; ?><tr class="border-t"><td class="p-3"><?= html_escape($item->invoice_no) ?></td><td class="p-3"><?= date('d-m-Y H:i', strtotime($item->tgl)) ?></td><td class="p-3"><?= html_escape($item->nama ?: '-') ?></td><td class="p-3"><?= html_escape($item->jenis_order) ?></td><td class="p-3"><?= html_escape(ucfirst($item->status)) ?></td><td class="p-3 text-right">Rp <?= number_format($item->total, 0, ',', '.') ?></td></tr><?php endforeach; ?>
<?php if (!$sales): ?><tr><td colspan="6" class="p-8 text-center text-gray-400">Tidak ada data pada rentang tanggal ini.</td></tr><?php endif; ?>
</tbody><tfoot class="bg-gray-50 font-bold"><tr><td colspan="5" class="p-3 text-right">Total Penjualan</td><td class="p-3 text-right">Rp <?= number_format($grand_total, 0, ',', '.') ?></td></tr></tfoot></table></div>
<?php else: ?>
<div class="flex justify-end mb-4"><a href="<?= site_url('admin/reports/export-stock') ?>" class="bg-green-700 text-white px-4 py-2 rounded-lg">Export Excel</a></div>
<div class="bg-white border border-gray-200 rounded-xl overflow-x-auto"><table class="w-full text-sm"><thead class="bg-gray-50"><tr><th class="p-3 text-left">Produk</th><th class="p-3 text-left">Kategori</th><th class="p-3 text-left">Jenis</th><th class="p-3 text-right">Stok</th><th class="p-3 text-right">Harga</th><th class="p-3 text-left">Status</th></tr></thead><tbody>
<?php foreach ($stock as $item): ?><tr class="border-t"><td class="p-3"><?= html_escape($item->nama_buah) ?></td><td class="p-3"><?= html_escape($item->nama_kategori ?: '-') ?></td><td class="p-3"><?= html_escape($item->jenis) ?></td><td class="p-3 text-right <?= $item->stok <= 5 ? 'text-red-600 font-bold' : '' ?>"><?= number_format($item->stok) . ' ' . html_escape($item->satuan) ?></td><td class="p-3 text-right">Rp <?= number_format($item->harga, 0, ',', '.') ?></td><td class="p-3"><?= $item->is_active ? 'Aktif' : 'Nonaktif' ?></td></tr><?php endforeach; ?>
</tbody></table></div>
<?php endif; ?>

<?= $this->load->view('admin/layout/footer', [], TRUE) ?>
