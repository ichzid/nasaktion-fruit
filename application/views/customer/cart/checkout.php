<?= $this->load->view('customer/layout/header', ['title'=>'Checkout'], TRUE) ?>

<div class="mb-6">
    <a href="<?= site_url('customer/cart') ?>" class="text-sm text-gray-500 hover:text-primary-600 mb-2 inline-flex items-center gap-1">
        <span class="iconify" data-icon="lucide:arrow-left"></span> Kembali ke Keranjang
    </a>
    <h2 class="font-display font-black text-2xl text-gray-900">Checkout</h2>
    <p class="text-sm text-gray-500">Lengkapi detail pengiriman pesanan Anda</p>
</div>

<form action="<?= site_url('customer/cart/process_checkout') ?>" method="POST" class="grid grid-cols-1 lg:grid-cols-3 gap-8">
    <input type="hidden" name="<?= $this->security->get_csrf_token_name(); ?>" value="<?= $this->security->get_csrf_hash(); ?>">
    
    <div class="lg:col-span-2 space-y-6">
        <!-- Informasi Penerima -->
        <div class="bg-white rounded-2xl border border-gray-100 p-6 shadow-sm">
            <h3 class="font-semibold text-gray-800 flex items-center gap-2 mb-4">
                <span class="iconify text-primary-600" data-icon="lucide:user"></span> Informasi Penerima
            </h3>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Nama Lengkap</label>
                    <input type="text" disabled value="<?= $customer_obj->nama ?>" class="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-2.5 text-sm text-gray-500 cursor-not-allowed">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Nomor Telepon (WhatsApp) <span class="text-red-500">*</span></label>
                    <input type="text" name="no_hp_penerima" required value="<?= $customer_obj->no_hp ?>" class="w-full bg-white border border-gray-200 rounded-xl px-4 py-2.5 text-sm text-gray-800 outline-none focus:ring-2 focus:ring-primary-300 focus:border-primary-400 transition-all">
                </div>
            </div>
        </div>

        <!-- Detail Pengiriman -->
        <div class="bg-white rounded-2xl border border-gray-100 p-6 shadow-sm">
            <h3 class="font-semibold text-gray-800 flex items-center gap-2 mb-4">
                <span class="iconify text-primary-600" data-icon="lucide:map-pin"></span> Detail Pengiriman
            </h3>
            
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Alamat Lengkap Pengiriman <span class="text-red-500">*</span></label>
                    <textarea name="alamat_pengiriman" rows="3" required placeholder="Jalan, RT/RW, Kelurahan, Kecamatan, Kota, Kode Pos" class="w-full bg-white border border-gray-200 rounded-xl px-4 py-3 text-sm text-gray-800 outline-none focus:ring-2 focus:ring-primary-300 focus:border-primary-400 transition-all resize-none"></textarea>
                    <?php echo form_error('alamat_pengiriman', '<div class="text-red-500 text-xs mt-1">', '</div>'); ?>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Catatan Tambahan (Opsional)</label>
                    <textarea name="catatan" rows="2" placeholder="Cth: Titip di pos satpam, atau warna buah yang diinginkan" class="w-full bg-white border border-gray-200 rounded-xl px-4 py-3 text-sm text-gray-800 outline-none focus:ring-2 focus:ring-primary-300 focus:border-primary-400 transition-all resize-none"></textarea>
                </div>
            </div>
        </div>
    </div>

    <div class="lg:col-span-1">
        <!-- Ringkasan Pesanan -->
        <div class="bg-white rounded-2xl border border-gray-100 p-6 shadow-sm sticky top-24">
            <h3 class="font-semibold text-gray-800 mb-4 pb-3 border-b border-gray-100 flex items-center gap-2">
                <span class="iconify text-primary-600" data-icon="lucide:shopping-bag"></span> Ringkasan Pesanan
            </h3>
            
            <div class="space-y-3 mb-6 max-h-64 overflow-y-auto pr-2">
                <?php 
                $subtotal = 0;
                foreach($cart as $item): 
                    $subtotal += $item['subtotal'];
                ?>
                <div class="flex gap-3">
                    <div class="w-16 h-16 bg-primary-50 rounded-lg flex items-center justify-center flex-shrink-0">
                        <?php if($item['foto']): ?>
                        <img src="<?= base_url('uploads/products/'.$item['foto']) ?>" class="w-full h-full object-cover rounded-lg">
                        <?php else: ?>
                        <span class="text-2xl">🍎</span>
                        <?php endif; ?>
                    </div>
                    <div class="flex-1">
                        <p class="text-sm font-semibold text-gray-800 leading-tight"><?= $item['nama_buah'] ?></p>
                        <p class="text-xs text-gray-500 mt-1"><?= $item['qty'] ?> x Rp <?= number_format($item['harga'],0,',','.') ?></p>
                        <p class="text-sm font-bold text-gray-900 mt-0.5">Rp <?= number_format($item['subtotal'],0,',','.') ?></p>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>

            <div class="space-y-2 border-t border-gray-100 pt-4 mb-4">
                <div class="flex justify-between text-sm">
                    <span class="text-gray-500">Subtotal</span>
                    <span class="font-medium">Rp <?= number_format($subtotal,0,',','.') ?></span>
                </div>
                
                <!-- Voucher Input -->
                <?php if(!empty($vouchers)): ?>
                <div class="mt-4 mb-2">
                    <label class="block text-xs font-semibold text-gray-700 mb-1.5 flex items-center gap-1.5"><span class="iconify" data-icon="lucide:ticket-percent"></span> Kode Voucher Anda</label>
                    <select name="kode_voucher" id="kode_voucher" class="w-full bg-gray-50 border border-gray-200 rounded-xl px-3 py-2 text-sm text-gray-700 outline-none focus:ring-2 focus:ring-primary-300">
                        <option value="">-- Pilih Voucher Jika Ada --</option>
                        <?php foreach($vouchers as $v): ?>
                        <option value="<?= $v->kode_voucher ?>" data-tipe="<?= $v->tipe_diskon ?>" data-nilai="<?= $v->nilai_diskon ?>" data-min="<?= $v->min_belanja ?>">
                            <?= $v->kode_voucher ?> - Diskon <?= $v->tipe_diskon=='persen' ? $v->nilai_diskon.'%' : 'Rp '.number_format($v->nilai_diskon,0,',','.') ?>
                        </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <!-- Tempat tampil diskon -->
                <div id="diskonRow" class="flex justify-between text-sm text-primary-600 hidden">
                    <span class="font-medium">Diskon Voucher</span>
                    <span id="diskonAmount" class="font-bold">- Rp 0</span>
                </div>
                <?php endif; ?>
            </div>

            <div class="flex justify-between items-center border-t border-gray-100 pt-4 mb-6">
                <span class="font-bold text-gray-800">Total Pembayaran</span>
                <span class="font-black text-xl text-primary-600" id="totalPayment">Rp <?= number_format($subtotal,0,',','.') ?></span>
            </div>

            <button type="submit" class="w-full bg-primary-600 hover:bg-primary-700 text-white font-semibold py-3.5 rounded-xl transition-all shadow-md hover:shadow-lg flex items-center justify-center gap-2">
                Proses Pesanan Sekarang <span class="iconify" data-icon="lucide:arrow-right"></span>
            </button>
            <p class="text-xs text-center text-gray-400 mt-3">Pembayaran dilakukan secara transfer setelah pesanan dibuat.</p>
        </div>
    </div>
</form>

<script>
    const subtotal = <?= $subtotal ?>;
    const selectVoucher = document.getElementById('kode_voucher');
    const diskonRow = document.getElementById('diskonRow');
    const diskonAmountText = document.getElementById('diskonAmount');
    const totalPaymentText = document.getElementById('totalPayment');

    if(selectVoucher) {
        selectVoucher.addEventListener('change', function() {
            const selected = this.options[this.selectedIndex];
            if(!selected.value) {
                diskonRow.classList.add('hidden');
                totalPaymentText.innerText = 'Rp ' + formatRupiah(subtotal);
                return;
            }

            const minBelanja = parseInt(selected.dataset.min);
            if(subtotal < minBelanja) {
                Swal.fire({icon:'warning', title:'Gagal', text:'Minimal belanja untuk voucher ini adalah Rp ' + formatRupiah(minBelanja)});
                this.value = '';
                diskonRow.classList.add('hidden');
                totalPaymentText.innerText = 'Rp ' + formatRupiah(subtotal);
                return;
            }

            const tipe = selected.dataset.tipe;
            const nilai = parseInt(selected.dataset.nilai);
            let diskon = 0;

            if(tipe === 'persen') {
                diskon = (subtotal * nilai) / 100;
            } else {
                diskon = nilai;
            }

            // pastikan diskon tidak merugikan
            if(diskon > subtotal) diskon = subtotal;

            diskonRow.classList.remove('hidden');
            diskonAmountText.innerText = '- Rp ' + formatRupiah(diskon);
            totalPaymentText.innerText = 'Rp ' + formatRupiah(subtotal - diskon);
        });
    }

    function formatRupiah(angka) {
        return new Intl.NumberFormat('id-ID').format(angka);
    }
</script>

<?= $this->load->view('customer/layout/footer', [], TRUE) ?>
