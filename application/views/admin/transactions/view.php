<?= $this->load->view('admin/layout/header', ['title'=>$title], TRUE) ?>

<div class="mb-6 flex space-x-2 items-center">
    <a href="<?= site_url('admin/transactions') ?>" class="text-primary-600 hover:text-primary-700 bg-primary-50 px-3 py-1.5 rounded-lg text-sm transition">
        ← Kembali
    </a>
    <span class="text-sm text-gray-500">/ Detail: <?= $transaction->invoice_no ?></span>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    
    <!-- Informasi Utama -->
    <div class="lg:col-span-2 space-y-6">
        
        <!-- Header Info -->
        <div class="bg-white border border-gray-200 rounded-xl shadow-sm p-6">
            <div class="flex justify-between items-start mb-6">
                <div>
                    <h2 class="text-xl font-bold text-gray-800">Detail Invoice</h2>
                    <p class="text-sm text-gray-500">No: <span class="font-semibold text-primary-600"><?= $transaction->invoice_no ?></span></p>
                </div>
                <div class="text-right">
                    <?php 
                        $status_badge = [
                            'pending' => ['Tertunda', 'bg-yellow-100 text-yellow-700'],
                            'paid' => ['Lunas', 'bg-blue-100 text-blue-700'],
                            'verified' => ['Terverifikasi', 'bg-green-100 text-green-700'],
                            'completed' => ['Selesai', 'bg-green-100 text-green-700'],
                            'cancelled' => ['Dibatalkan', 'bg-red-100 text-red-700']
                        ];
                        $st_key = $transaction->status ?? 'pending';
                        $st_label = isset($status_badge[$st_key]) ? $status_badge[$st_key][0] : ucfirst($st_key);
                        $st_color = isset($status_badge[$st_key]) ? $status_badge[$st_key][1] : 'bg-gray-100 text-gray-700';
                    ?>
                    <span class="inline-block px-3 py-1 rounded-full text-sm font-medium <?= $st_color ?>">
                        Status: <?= $st_label ?>
                    </span>
                    <p class="text-sm mt-2 text-gray-500"><?= date('d F Y, H:i', strtotime($transaction->tgl)) ?></p>
                </div>
            </div>
            
            <div class="grid grid-cols-2 gap-4 text-sm mt-4 p-4 bg-gray-50 rounded-lg">
                <div>
                    <p class="text-gray-500 mb-1">Pelanggan</p>
                    <p class="font-medium text-gray-800"><?= $transaction->nama ?? 'Guest (Non-member)' ?></p>
                    <?php if(!empty($transaction->no_hp)): ?>
                        <p class="text-gray-600 text-xs mt-0.5"><?= $transaction->no_hp ?></p>
                    <?php endif; ?>
                </div>
                <div>
                    <p class="text-gray-500 mb-1">Dibuat Oleh (Admin)</p>
                    <p class="font-medium text-gray-800"><?= $transaction->admin_name ?? '-' ?></p>
                </div>
                <div>
                    <p class="text-gray-500 mb-1">Metode Pembayaran</p>
                    <p class="font-medium text-gray-800 capitalize"><?= $transaction->payment_method ?? 'cash' ?></p>
                </div>
                <div>
                    <p class="text-gray-500 mb-1">Jenis Transaksi</p>
                    <span class="inline-block px-2 py-0.5 rounded text-xs font-semibold <?= $transaction->jenis_order=='Online'?'bg-blue-100 text-blue-700':'bg-purple-100 text-purple-700' ?>">
                        <?= $transaction->jenis_order ?>
                    </span>
                </div>
            </div>
        </div>

        <!-- Tabel Produk -->
        <div class="bg-white border border-gray-200 rounded-xl shadow-sm overflow-hidden">
            <div class="p-4 border-b border-gray-200 bg-gray-50">
                <h3 class="font-semibold text-gray-700">Barang yang dibeli</h3>
            </div>
            <table class="w-full text-sm">
                <thead class="bg-white border-b border-gray-200">
                    <tr>
                        <th class="text-left px-4 py-3 font-medium text-gray-600">Produk</th>
                        <th class="text-right px-4 py-3 font-medium text-gray-600">Harga</th>
                        <th class="text-center px-4 py-3 font-medium text-gray-600">Qty</th>
                        <th class="text-right px-4 py-3 font-medium text-gray-600">Subtotal</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    <?php foreach($items as $item): ?>
                    <tr class="hover:bg-gray-50">
                        <td class="px-4 py-3 font-medium text-gray-800"><?= $item->nama_buah ?></td>
                        <td class="px-4 py-3 text-right text-gray-600">Rp <?= number_format($item->harga, 0, ',', '.') ?></td>
                        <td class="px-4 py-3 text-center"><?= $item->qty ?></td>
                        <td class="px-4 py-3 text-right font-semibold text-primary-600">Rp <?= number_format($item->subtotal, 0, ',', '.') ?></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        
        <?php if(!empty($transaction->catatan)): ?>
        <div class="bg-yellow-50 border border-yellow-200 p-4 rounded-xl">
            <h3 class="font-semibold text-yellow-800 text-sm mb-1">Catatan Pesanan:</h3>
            <p class="text-sm text-yellow-700"><?= nl2br(htmlspecialchars($transaction->catatan)) ?></p>
        </div>
        <?php endif; ?>
        
    </div>

    <!-- Ringkasan Angka & Tombol Aksi -->
    <div class="lg:col-span-1 space-y-4">
        <div class="bg-white border border-gray-200 rounded-xl shadow-sm p-6">
            <h3 class="font-semibold text-gray-800 mb-4 pb-2 border-b border-gray-100">Ringkasan Biaya</h3>
            <div class="space-y-3 text-sm">
                <div class="flex justify-between">
                    <span class="text-gray-500">Subtotal Belanja</span>
                    <span class="font-medium">Rp <?= number_format($transaction->subtotal, 0, ',', '.') ?></span>
                </div>
                <?php if($transaction->diskon_amount > 0): ?>
                <div class="flex justify-between text-red-500">
                    <span>Diskon / Promo</span>
                    <span>- Rp <?= number_format($transaction->diskon_amount, 0, ',', '.') ?></span>
                </div>
                <?php endif; ?>
                
                <div class="border-t border-gray-200 pt-3 mt-3 flex justify-between items-center bg-primary-50 -mx-4 px-4 py-2">
                    <span class="font-bold text-gray-700">Total Akhir</span>
                    <span class="text-lg font-black text-primary-600">Rp <?= number_format($transaction->total, 0, ',', '.') ?></span>
                </div>
            </div>
        </div>
        
        <!-- Tombol Cetak / Aksi -->
        <div class="bg-gray-50 border border-gray-200 rounded-xl p-4 flex flex-col gap-2">
            <button onclick="window.open('<?= site_url('admin/pos/print_receipt/'.$transaction->id) ?>', '_blank', 'width=350,height=600')" class="w-full bg-white border-2 border-primary-600 text-primary-700 py-2.5 rounded-lg font-semibold hover:bg-primary-50 transition flex items-center justify-center gap-2 text-sm">
                <span class="iconify" data-icon="lucide:printer"></span> Cetak Struk
            </button>
            
            <?php if($transaction->status == 'pending'): ?>
            <a href="<?= site_url('admin/transactions/verify/'.$transaction->id) ?>" onclick="return confirm('Konfirmasi kelulusan pembayaran ini dan potong stok gudang?')" class="w-full bg-green-500 hover:bg-green-600 text-white text-center py-2.5 rounded-lg font-semibold transition text-sm">
                ✓ Tandai Sudah Dibayar
            </a>
            <?php endif; ?>
            
            <?php if($transaction->status != 'cancelled'): ?>
            <a href="<?= site_url('admin/transactions/cancel/'.$transaction->id) ?>" onclick="return confirm('Batalkan transaksi ini secara permanen? Stok akan dikembalikan jika sebelumnya sudah terpotong.')" class="w-full bg-red-100 hover:bg-red-200 text-red-600 text-center py-2.5 rounded-lg font-medium transition text-sm">
                Batalkan Transaksi
            </a>
            <?php endif; ?>
        </div>
    </div>
    
</div>

<?= $this->load->view('admin/layout/footer', [], TRUE) ?>
