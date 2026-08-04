<?= $this->load->view('customer/layout/header', ['title'=>'Keranjang'], TRUE) ?>

<?php if(empty($cart_items)): ?>
<div class="text-center py-24 flex flex-col items-center justify-center bg-white rounded-3xl border border-gray-100 shadow-sm mt-4">
    <div class="w-24 h-24 bg-gray-50 rounded-full flex items-center justify-center mb-6">
        <span class="iconify text-5xl text-gray-400" data-icon="lucide:shopping-bag"></span>
    </div>
    <h2 class="text-2xl font-display font-black text-gray-900 mb-2">Keranjang Belanja Kosong</h2>
    <p class="text-sm text-gray-500 mb-8 max-w-sm mx-auto">Sepertinya Anda belum menambahkan kesegaran apa pun ke sini. Yuk eksplorasi produk kami!</p>
    <a href="<?= site_url('customer/shop') ?>" class="bg-primary-600 text-white px-8 py-3.5 rounded-xl font-bold hover:bg-primary-700 transition shadow-lg hover:shadow-primary-300/50 hover:-translate-y-0.5 inline-flex items-center gap-2 text-sm">
        <span class="iconify" data-icon="lucide:shopping-cart"></span> Mulai Belanja
    </a>
</div>
<?php else: ?>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <!-- Cart Items -->
    <div class="lg:col-span-2 space-y-3">
        <div class="flex items-center justify-between mb-4">
            <h2 class="font-display font-black text-xl text-gray-900">Keranjang Belanja</h2>
            <a href="<?= site_url('customer/cart/clear') ?>" onclick="return confirm('Apakah Anda yakin ingin mengosongkan keranjang?')" class="text-xs text-red-500 hover:text-red-700 font-medium flex items-center gap-1 bg-red-50 hover:bg-red-100 px-3 py-1.5 rounded-lg transition">
                <span class="iconify" data-icon="lucide:trash-2"></span> Kosongkan
            </a>
        </div>
        <?php foreach($cart_items as $item): ?>
        <div class="bg-white rounded-xl border border-gray-200 p-4 flex items-center gap-4">
            <div class="w-20 h-20 bg-primary-50 rounded-lg flex items-center justify-center overflow-hidden flex-shrink-0">
                <?php if($item->foto): ?>
                <img src="<?= base_url('uploads/products/'.$item->foto) ?>" class="w-full h-full object-cover">
                <?php else: ?>
                <span class="text-3xl">🍎</span>
                <?php endif; ?>
            </div>
            <div class="flex-1 min-w-0">
                <h3 class="font-medium text-gray-800 truncate"><?= $item->nama_buah ?></h3>
                <p class="text-sm text-primary-600 font-semibold">Rp <?= number_format($item->harga_jual, 0, ',', '.') ?></p>
            </div>
            <form action="<?= site_url('customer/cart/update/'.$item->id) ?>" method="post" class="flex items-center bg-gray-100 rounded-xl p-1">
                <button type="button" onclick="this.form.qty.value = Math.max(1, parseInt(this.form.qty.value, 10) - 1); this.form.submit();" class="w-8 h-8 flex items-center justify-center text-gray-500 hover:text-red-500 transition">−</button>
                <input type="number" name="qty" value="<?= $item->qty ?>" min="1" max="<?= isset($item->stok) ? $item->stok : $item->qty ?>"
                       onchange="this.form.submit()" onkeydown="if(event.key === 'Enter') { event.preventDefault(); this.form.submit(); }"
                       class="w-14 h-8 text-center text-sm font-bold bg-white border border-gray-200 rounded-lg outline-none focus:border-primary-500 focus:ring-1 focus:ring-primary-200 px-1"
                       aria-label="Kuantitas <?= html_escape($item->nama_buah) ?>">
                <button type="button" onclick="this.form.qty.value = parseInt(this.form.qty.value, 10) + 1; this.form.submit();" class="w-8 h-8 flex items-center justify-center text-gray-500 hover:text-primary-600 transition">+</button>
            </form>
            <p class="font-bold text-gray-800 w-28 text-right">Rp <?= number_format($item->harga_jual * $item->qty, 0, ',', '.') ?></p>
            <a href="<?= site_url('customer/cart/remove/'.$item->id) ?>" class="text-red-400 hover:text-red-600 p-1"><span class="iconify" data-icon="lucide:trash-2"></span></a>
        </div>
        <?php endforeach; ?>
    </div>

    <!-- Checkout Summary -->
    <div class="lg:col-span-1">
        <div class="bg-white rounded-xl border border-gray-200 p-5 sticky top-20">
            <h3 class="font-semibold text-gray-800 mb-4">Ringkasan Pesanan</h3>
            <div class="space-y-2 text-sm">
                <div class="flex justify-between"><span class="text-gray-500">Subtotal (<?= count($cart_items) ?> item)</span><span class="font-medium">Rp <?= number_format($subtotal, 0, ',', '.') ?></span></div>

                <div class="flex justify-between"><span class="text-gray-500">Ongkir</span><span class="font-medium text-green-600">Gratis</span></div>
            </div>
            <div class="border-t border-gray-200 mt-3 pt-3 flex justify-between text-lg font-bold">
                <span>Total</span><span class="text-primary-600">Rp <?= number_format($total, 0, ',', '.') ?></span>
            </div>

            <div class="mt-4">
                <a href="<?= site_url('customer/cart/checkout') ?>" class="w-full bg-primary-600 hover:bg-primary-700 text-white font-medium py-3 rounded-lg transition flex items-center justify-center gap-2">
                    <span class="iconify" data-icon="lucide:credit-card"></span> Lanjut ke Checkout
                </a>
            </div>
        </div>
    </div>
</div>


<?php endif; ?>

<?= $this->load->view('customer/layout/footer', [], TRUE) ?>