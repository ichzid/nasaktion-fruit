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
                            'pending' => ['Tertunda', 'bg-amber-100 text-amber-700 border-amber-200'],
                            'paid' => ['Dibayar', 'bg-blue-100 text-blue-700 border-blue-200'],
                            'verified' => ['Di Proses', 'bg-indigo-100 text-indigo-700 border-indigo-200'],
                            'shipped' => ['Dikirim', 'bg-cyan-100 text-cyan-700 border-cyan-200'],
                            'completed' => ['Selesai', 'bg-green-100 text-green-700 border-green-200'],
                            'cancelled' => ['Dibatalkan', 'bg-red-100 text-red-700 border-red-200']
                        ];
                        $st_key = $transaction->status ?? 'pending';
                        $st_label = isset($status_badge[$st_key]) ? $status_badge[$st_key][0] : ucfirst($st_key);
                        $st_color = isset($status_badge[$st_key]) ? $status_badge[$st_key][1] : 'bg-gray-100 text-gray-700 border-gray-200';
                    ?>
                    <span class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full text-sm font-bold border <?= $st_color ?> shadow-sm">
                        <span class="w-2 h-2 rounded-full currentColor bg-current"></span> <?= $st_label ?>
                    </span>
                    <p class="text-sm mt-2 text-gray-500"><?= date('d F Y, H:i', strtotime($transaction->tgl)) ?></p>
                </div>
            </div>
            
            <div class="grid grid-cols-2 gap-4 text-sm mt-4 p-4 bg-gray-50 rounded-lg">
                <div>
                    <p class="text-gray-500 mb-1">Pelanggan</p>
                    <p class="font-medium text-gray-800"><?= $transaction->nama ?? 'Pelanggan Umum' ?></p>
                    <?php if(!empty($transaction->no_hp)): ?>
                        <p class="text-gray-600 text-xs mt-0.5"><?= $transaction->no_hp ?></p>
                    <?php endif; ?>
                </div>
                <div>
                    <p class="text-gray-500 mb-1">Dibuat Oleh (Admin)</p>
                    <p class="font-medium text-gray-800"><?= $transaction->admin_name ?? '-' ?></p>
                </div>
                <div class="col-span-2">
                    <p class="text-gray-500 mb-1">Jenis Transaksi</p>
                    <span class="inline-block px-2 py-0.5 rounded text-xs font-semibold <?= $transaction->jenis_order=='Online'?'bg-blue-100 text-blue-700':'bg-emerald-100 text-emerald-700' ?>">
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

        <?php if(!empty($transaction->bukti_tf)): ?>
        <div class="bg-blue-50 border border-blue-200 p-4 rounded-xl">
            <h3 class="font-semibold text-blue-800 text-sm mb-2">Bukti Pembayaran (Transfer):</h3>
            <a href="<?= base_url('uploads/payments/'.$transaction->bukti_tf) ?>" target="_blank" class="block">
                <img src="<?= base_url('uploads/payments/'.$transaction->bukti_tf) ?>" alt="Bukti Transfer" class="w-full max-w-sm rounded-lg border border-blue-200 shadow-sm mt-2">
            </a>
            <p class="text-xs text-blue-600 mt-2">Klik gambar untuk melihat resolusi penuh.</p>
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
            <?php if($transaction->status != 'cancelled'): ?>
            <button onclick="window.open('<?= site_url('admin/pos/print_receipt/'.$transaction->id) ?>', '_blank', 'width=350,height=600')" class="w-full bg-white border-2 border-primary-600 text-primary-700 py-2.5 rounded-lg font-semibold hover:bg-primary-50 transition flex items-center justify-center gap-2 text-sm">
                <span class="iconify" data-icon="lucide:printer"></span> Cetak Struk
            </button>
            <?php endif; ?>
            <?php if($transaction->jenis_order != 'Offline'): ?>
            
                <?php if($transaction->status == 'pending' || $transaction->status == 'paid'): ?>
                <a href="#" onclick="Swal.fire({title:'Konfirmasi Verifikasi',text:'Konfirmasi kelulusan pembayaran ini dan potong stok gudang?',icon:'question',showCancelButton:true,confirmButtonColor:'#22c55e',cancelButtonColor:'#6b7280',confirmButtonText:'Ya, Verifikasi!',cancelButtonText:'Batal'}).then((r)=>{if(r.isConfirmed)window.location='<?= site_url('admin/transactions/verify/'.$transaction->id) ?>'});return false;" class="w-full bg-green-500 hover:bg-green-600 text-white text-center py-2.5 rounded-lg font-semibold transition text-sm block">
                    <?= $transaction->status == 'paid' ? '✓ Verifikasi Pembayaran' : '✓ Tandai Sudah Dibayar' ?>
                </a>
                <?php endif; ?>
                
                <?php if($transaction->status == 'verified'): ?>
                <a href="#" onclick="Swal.fire({title:'Kirim Barang?',text:'Konfirmasi bahwa barang sudah dipickup kurir atau siap dikirim?',icon:'info',showCancelButton:true,confirmButtonColor:'#3b82f6',cancelButtonColor:'#6b7280',confirmButtonText:'Ya, Kirim!',cancelButtonText:'Batal'}).then((r)=>{if(r.isConfirmed)window.location='<?= site_url('admin/transactions/ship/'.$transaction->id) ?>'});return false;" class="w-full bg-blue-500 hover:bg-blue-600 text-white text-center py-2.5 rounded-lg font-semibold transition text-sm flex items-center justify-center gap-2 block">
                    <span class="iconify" data-icon="lucide:truck"></span> Kirim Barang
                </a>
                <?php endif; ?>
                
                <?php if($transaction->status != 'cancelled' && $transaction->status != 'completed'): ?>
                <a href="#" onclick="Swal.fire({title:'Batalkan Transaksi?',text:'Batalkan transaksi ini secara permanen? Stok akan dikembalikan jika sebelumnya sudah terpotong.',icon:'warning',showCancelButton:true,confirmButtonColor:'#ef4444',cancelButtonColor:'#6b7280',confirmButtonText:'Ya, Batalkan!',cancelButtonText:'Batal'}).then((r)=>{if(r.isConfirmed)window.location='<?= site_url('admin/transactions/cancel/'.$transaction->id) ?>'});return false;" class="w-full bg-red-100 hover:bg-red-200 text-red-600 text-center py-2.5 rounded-lg font-medium transition text-sm block">
                    Batalkan Transaksi
                </a>
                <?php endif; ?>

            <?php endif; ?>
        </div>
    </div>
    
</div>

<?= $this->load->view('admin/layout/footer', [], TRUE) ?>
