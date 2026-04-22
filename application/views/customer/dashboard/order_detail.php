<?= $this->load->view('customer/layout/header', ['title' => $title], TRUE) ?>

<div class="mb-8">
    <a href="<?= site_url('customer/dashboard/orders') ?>" class="inline-flex items-center gap-2 text-sm text-gray-500 hover:text-primary-600 transition mb-6 group">
        <span class="iconify group-hover:-translate-x-1 transition-transform" data-icon="lucide:arrow-left"></span> Kembali ke Pesanan Saya
    </a>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Main Content -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Header Card -->
            <div class="bg-white rounded-3xl border border-gray-100 p-6 shadow-sm flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
                <div>
                    <h2 class="font-display font-black text-2xl text-gray-900 mb-1"><?= $transaction->invoice_no ?></h2>
                    <p class="text-sm text-gray-500"><?= date('d F Y, H:i', strtotime($transaction->tgl)) ?> WIB</p>
                </div>
                <?php
                    $st_map = [
                        'pending' => ['Tertunda', 'bg-amber-100 text-amber-700'],
                        'paid' => ['Dibayar', 'bg-blue-100 text-blue-700'],
                        'verified' => ['Di Proses', 'bg-indigo-100 text-indigo-700'],
                        'shipped' => ['Dikirim', 'bg-cyan-100 text-cyan-700'],
                        'completed' => ['Selesai', 'bg-green-100 text-green-700'],
                        'cancelled' => ['Dibatalkan', 'bg-red-100 text-red-700']
                    ];
                    $st = $transaction->status ?? 'pending';
                    $st_label = $st_map[$st][0] ?? ucfirst($st);
                    $st_col = $st_map[$st][1] ?? 'bg-gray-100 text-gray-700';
                ?>
                <span class="px-4 py-1.5 rounded-full font-bold uppercase text-xs tracking-wider <?= $st_col ?>">
                    Status: <?= $st_label ?>
                </span>
            </div>

            <!-- Items List -->
            <div class="bg-white rounded-3xl border border-gray-100 overflow-hidden shadow-sm">
                <div class="p-6 border-b border-gray-50">
                    <h3 class="font-bold text-gray-800">Daftar Produk</h3>
                </div>
                <div class="divide-y divide-gray-50">
                    <?php foreach($items as $item): ?>
                    <div class="p-6 flex items-center gap-4">
                        <div class="w-16 h-16 bg-primary-50 rounded-xl overflow-hidden flex-shrink-0">
                            <?php if($item->foto): ?>
                            <img src="<?= base_url('uploads/products/'.$item->foto) ?>" class="w-full h-full object-cover">
                            <?php else: ?>
                            <div class="w-full h-full flex items-center justify-center text-3xl">🍎</div>
                            <?php endif; ?>
                        </div>
                        <div class="flex-1 min-w-0">
                            <h4 class="font-bold text-gray-800 truncate"><?= $item->nama_buah ?></h4>
                            <p class="text-xs text-gray-400">Rp <?= number_format($item->harga, 0, ',', '.') ?> / <?= $item->satuan ?></p>
                        </div>
                        <div class="text-right">
                            <p class="text-sm font-medium text-gray-500">x<?= $item->qty ?></p>
                            <p class="font-bold text-gray-900">Rp <?= number_format($item->subtotal, 0, ',', '.') ?></p>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>

            <!-- Shipping Info -->
            <div class="bg-white rounded-3xl border border-gray-100 p-6 shadow-sm">
                <h3 class="font-bold text-gray-800 mb-4 flex items-center gap-2">
                    <span class="iconify text-primary-600" data-icon="lucide:map-pin"></span> Informasi Pengiriman
                </h3>
                <div class="bg-gray-50 rounded-2xl p-4 text-sm text-gray-600 leading-relaxed whitespace-pre-line"><?= $transaction->alamat_pengiriman ?: 'Alamat tidak tersedia' ?></div>
            </div>
        </div>

        <!-- Sidebar Summary -->
        <div class="lg:col-span-1 space-y-6">
            <div class="bg-white rounded-3xl border border-gray-100 p-6 shadow-sm">
                <h3 class="font-bold text-gray-800 mb-6">Ringkasan Pembayaran</h3>
                <div class="space-y-4 text-sm">
                    <div class="flex justify-between text-gray-500">
                        <span>Subtotal</span>
                        <span class="font-medium text-gray-800">Rp <?= number_format($transaction->subtotal, 0, ',', '.') ?></span>
                    </div>
                    <?php if($transaction->diskon_amount > 0): ?>
                    <div class="flex justify-between text-red-500">
                        <span>Diskon Voucher</span>
                        <span class="font-medium">-Rp <?= number_format($transaction->diskon_amount, 0, ',', '.') ?></span>
                    </div>
                    <?php endif; ?>
                    <div class="border-t border-gray-50 pt-4 flex justify-between">
                        <span class="font-bold text-gray-900 text-lg">Total</span>
                        <span class="font-black text-primary-600 text-xl">Rp <?= number_format($transaction->total, 0, ',', '.') ?></span>
                    </div>
                </div>

                <?php if($transaction->status == 'pending'): ?>
                <div class="mt-8 border-t border-gray-50 pt-6">
                    <h4 class="font-bold text-sm text-gray-800 mb-4">Konfirmasi Pembayaran</h4>
                    <p class="text-xs text-gray-500 mb-4 leading-relaxed">Silakan transfer sesuai nominal di atas, lalu unggah bukti transfer Anda di bawah ini:</p>
                    
                    <?= form_open_multipart('customer/dashboard/upload_payment/'.$transaction->id, ['class' => 'space-y-3']) ?>
                        <div class="relative">
                            <input type="file" name="bukti_transfer" id="bukti_transfer" class="hidden" onchange="document.getElementById('file-name').textContent = this.files[0].name" required>
                            <label for="bukti_transfer" class="cursor-pointer w-full flex flex-col items-center justify-center py-4 px-2 border-2 border-dashed border-gray-200 rounded-2xl hover:border-primary-400 hover:bg-primary-50 transition-colors">
                                <span class="iconify text-2xl text-gray-400 mb-1" data-icon="lucide:upload-cloud"></span>
                                <span id="file-name" class="text-[10px] text-gray-400 font-medium">Pilih File Bukti</span>
                            </label>
                        </div>
                        <button type="submit" class="w-full bg-primary-600 text-white font-bold py-3 rounded-xl hover:bg-primary-700 transition shadow-lg shadow-primary-100 flex items-center justify-center gap-2">
                             Kirim Bukti
                        </button>
                    </form>
                    
                    <a href="<?= site_url('customer/dashboard/cancel_order/'.$transaction->id) ?>" 
                       onclick="return confirm('Apakah Anda yakin ingin membatalkan pesanan ini?')"
                       class="mt-3 block w-full text-center bg-white text-red-500 border border-red-100 font-bold py-3 rounded-xl hover:bg-red-50 transition flex items-center justify-center gap-2">
                         <span class="iconify" data-icon="lucide:trash-2"></span> Batalkan Pesanan
                    </a>
                </div>
                <?php elseif($transaction->bukti_tf): ?>
                <div class="mt-8 border-t border-gray-50 pt-6">
                    <h4 class="font-bold text-sm text-gray-800 mb-3">Bukti Pembayaran</h4>
                    <a href="<?= base_url('uploads/payments/'.$transaction->bukti_tf) ?>" target="_blank" class="block rounded-2xl overflow-hidden border border-gray-100">
                        <img src="<?= base_url('uploads/payments/'.$transaction->bukti_tf) ?>" class="w-full h-auto object-cover opacity-70 hover:opacity-100 transition-opacity">
                    </a>
                </div>
                <?php endif; ?>
                
                <?php if($transaction->status == 'shipped'): ?>
                <div class="mt-8 border-t border-gray-50 pt-6">
                    <h4 class="font-bold text-sm text-gray-800 mb-3">Pesanan Tiba</h4>
                    <p class="text-xs text-gray-500 mb-4 leading-relaxed">Silakan klik tombol di bawah jika pesanan buah Anda sudah sampai dan diterima dalam keadaan baik.</p>
                    <a href="<?= site_url('customer/dashboard/complete_order/'.$transaction->id) ?>" 
                       onclick="return confirm('Konfirmasi bahwa pesanan sudah Anda terima dengan baik?')"
                       class="block w-full text-center bg-emerald-500 text-white font-bold py-3 rounded-xl hover:bg-emerald-600 transition flex items-center justify-center gap-2 shadow-lg shadow-emerald-100">
                         <span class="iconify text-lg" data-icon="lucide:check-circle"></span> Pesanan Diterima
                    </a>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<?= $this->load->view('customer/layout/footer', [], TRUE) ?>
