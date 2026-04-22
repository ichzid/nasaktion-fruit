<?= $this->load->view('admin/layout/header', ['title'=>$title], TRUE) ?>

<div class="flex items-center justify-between mb-6">
    <div></div>
    <button onclick="document.getElementById('modalForm').classList.remove('hidden')" class="bg-primary-600 hover:bg-primary-700 text-white px-4 py-2 rounded-lg text-sm font-medium flex items-center gap-2 transition">
        <span class="iconify" data-icon="lucide:plus"></span> Buat Voucher
    </button>
</div>

<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
    <?php foreach($discounts as $d): ?>
    <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">
        <div class="p-4">
            <div class="flex items-center justify-between mb-2">
                <span class="px-2 py-0.5 rounded-full text-xs font-medium <?= $d->is_active?'bg-green-100 text-green-700':'bg-gray-100 text-gray-500' ?>"><?= $d->is_active?'Aktif':'Nonaktif' ?></span>
                <span class="text-xs text-gray-400"><?= date('d M Y', strtotime($d->created_at)) ?></span>
            </div>
            <h3 class="text-lg font-bold text-primary-600 mb-1"><?= $d->kode_voucher ?></h3>
            <p class="text-sm text-gray-600 mb-2"><?= $d->nama_promo ?? '-' ?></p>
            <div class="bg-primary-50 rounded-lg p-3 text-center mb-3">
                <?php if($d->tipe == 'persen'): ?>
                <p class="text-2xl font-bold text-primary-600"><?= $d->nilai ?>%</p>
                <?php else: ?>
                <p class="text-2xl font-bold text-primary-600">Rp <?= number_format($d->nilai, 0, ',', '.') ?></p>
                <?php endif; ?>
                <p class="text-xs text-gray-500">Min. belanja Rp <?= number_format($d->min_belanja, 0, ',', '.') ?></p>
            </div>
            <div class="flex items-center justify-between text-xs text-gray-500">
                <?php if($d->tanggal_mulai && $d->tanggal_selesai): ?>
                <span>Berlaku: <?= date('d M Y', strtotime($d->tanggal_mulai)) ?> - <?= date('d M Y', strtotime($d->tanggal_selesai)) ?></span>
                <?php else: ?>
                <span>Berlaku: Selamanya</span>
                <?php endif; ?>
            </div>
        </div>
        <div class="border-t border-gray-100 p-3 flex items-center justify-between">
            <a href="<?= site_url('admin/discounts/toggle/'.$d->id) ?>" class="text-xs <?= $d->is_active?'text-red-500':'text-green-600' ?> hover:underline"><?= $d->is_active?'Nonaktifkan':'Aktifkan' ?></a>
            <a href="<?= site_url('admin/discounts/delete/'.$d->id) ?>" class="text-xs text-red-500 hover:underline" onclick="return confirm('Hapus voucher ini?')">Hapus</a>
        </div>
    </div>
    <?php endforeach; ?>
    <?php if(empty($discounts)): ?>
    <div class="col-span-3 bg-white rounded-xl border border-gray-200 p-8 text-center text-gray-400">Belum ada voucher/diskon</div>
    <?php endif; ?>
</div>

<!-- Create Modal -->
<div id="modalForm" class="fixed inset-0 bg-black/50 z-50 hidden flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl max-w-md w-full p-6 max-h-[90vh] overflow-y-auto">
        <div class="flex items-center justify-between mb-4">
            <h3 class="font-semibold text-gray-800">Buat Voucher Baru</h3>
            <button onclick="document.getElementById('modalForm').classList.add('hidden')" class="text-gray-400 hover:text-gray-600">
                <span class="iconify text-xl" data-icon="lucide:x"></span>
            </button>
        </div>
        <form action="<?= site_url('admin/discounts/create') ?>" method="post" class="space-y-4">
            <input type="hidden" name="<?= $this->security->get_csrf_token_name(); ?>" value="<?= $this->security->get_csrf_hash(); ?>">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Nama Promo *</label>
                <input type="text" name="nama_promo" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm outline-none focus:ring-2 focus:ring-primary-500" required>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Kode Voucher *</label>
                <input type="text" name="kode_voucher" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm outline-none focus:ring-2 focus:ring-primary-500 uppercase" required>
            </div>
            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Tipe</label>
                    <select name="tipe" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm outline-none">
                        <option value="persen">Persen (%)</option>
                        <option value="nominal">Nominal (Rp)</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Nilai *</label>
                    <input type="number" name="nilai" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm outline-none" required min="0">
                </div>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Min. Belanja</label>
                <input type="number" name="min_belanja" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm outline-none" value="0" min="0">
            </div>
            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Mulai</label>
                    <input type="date" name="tanggal_mulai" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm outline-none">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Selesai</label>
                    <input type="date" name="tanggal_selesai" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm outline-none">
                </div>
            </div>
            <p class="text-xs text-gray-400">Kosongkan tanggal untuk voucher berlaku selamanya</p>
            <button type="submit" class="w-full bg-primary-600 hover:bg-primary-700 text-white font-medium py-2.5 rounded-lg transition">Simpan Voucher</button>
        </form>
    </div>
</div>

<?= $this->load->view('admin/layout/footer', [], TRUE) ?>