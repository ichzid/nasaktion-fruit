<?= $this->load->view('admin/layout/header', ['title'=>$title], TRUE) ?>

<div class="max-w-2xl mx-auto">
    <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-6">
        <form action="<?= isset($product) ? site_url('admin/products/edit/'.$product->id) : site_url('admin/products/create') ?>" method="post" enctype="multipart/form-data" class="space-y-5">
            <input type="hidden" name="<?= $this->security->get_csrf_token_name(); ?>" value="<?= $this->security->get_csrf_hash(); ?>">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Nama Buah <span class="text-red-500">*</span></label>
                    <input type="text" name="nama_buah" value="<?= set_value('nama_buah', isset($product)?$product->nama_buah:'') ?>" class="w-full border border-gray-300 rounded-lg px-4 py-2 text-sm focus:ring-2 focus:ring-primary-500 outline-none" required>
                    <?= form_error('nama_buah', '<p class="text-red-500 text-xs mt-1">', '</p>') ?>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Kategori</label>
                    <select name="category_id" class="w-full border border-gray-300 rounded-lg px-4 py-2 text-sm focus:ring-2 focus:ring-primary-500 outline-none">
                        <option value="">-- Pilih Kategori --</option>
                        <?php foreach($categories as $c): ?>
                        <option value="<?= $c->id ?>" <?= set_select('category_id', $c->id, isset($product)&&$product->category_id==$c->id) ?>><?= $c->nama_kategori ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Jenis <span class="text-red-500">*</span></label>
                    <select name="jenis" class="w-full border border-gray-300 rounded-lg px-4 py-2 text-sm focus:ring-2 focus:ring-primary-500 outline-none" required>
                        <option value="Lokal" <?= set_select('jenis', 'Lokal', isset($product)&&$product->jenis=='Lokal') ?>>Lokal</option>
                        <option value="Impor" <?= set_select('jenis', 'Impor', isset($product)&&$product->jenis=='Impor') ?>>Impor</option>
                        <option value="Ekspor" <?= set_select('jenis', 'Ekspor', isset($product)&&$product->jenis=='Ekspor') ?>>Ekspor</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Harga Beli <span class="text-red-500">*</span></label>
                    <input type="number" name="harga_beli" value="<?= set_value('harga_beli', isset($product)?$product->harga_beli:'') ?>" class="w-full border border-gray-300 rounded-lg px-4 py-2 text-sm focus:ring-2 focus:ring-primary-500 outline-none" required min="0">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Harga Jual <span class="text-red-500">*</span></label>
                    <input type="number" name="harga_jual" value="<?= set_value('harga_jual', isset($product)?$product->harga_jual:'') ?>" class="w-full border border-gray-300 rounded-lg px-4 py-2 text-sm focus:ring-2 focus:ring-primary-500 outline-none" required min="0">
                </div>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Stok <span class="text-red-500">*</span></label>
                    <input type="number" name="stok" value="<?= set_value('stok', isset($product)?$product->stok:'0') ?>" class="w-full border border-gray-300 rounded-lg px-4 py-2 text-sm focus:ring-2 focus:ring-primary-500 outline-none" required min="0">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Satuan</label>
                    <select name="satuan" class="w-full border border-gray-300 rounded-lg px-4 py-2 text-sm focus:ring-2 focus:ring-primary-500 outline-none">
                        <option value="kg" <?= set_select('satuan', 'kg', isset($product)&&$product->satuan=='kg') ?>>Kilogram (kg)</option>
                        <option value="buah" <?= set_select('satuan', 'buah', isset($product)&&$product->satuan=='buah') ?>>Buah</option>
                        <option value="ikat" <?= set_select('satuan', 'ikat', isset($product)&&$product->satuan=='ikat') ?>>Ikat</option>
                        <option value="pack" <?= set_select('satuan', 'pack', isset($product)&&$product->satuan=='pack') ?>>Pack</option>
                    </select>
                </div>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Foto Produk</label>
                <?php if(isset($product) && $product->foto): ?>
                <div class="mb-2"><img src="<?= base_url('uploads/products/'.$product->foto) ?>" class="w-32 h-32 rounded-lg object-cover"></div>
                <?php endif; ?>
                <input type="file" name="foto" accept="image/*" class="w-full border border-gray-300 rounded-lg px-4 py-2 text-sm file:mr-4 file:py-1 file:px-3 file:rounded-lg file:border-0 file:text-sm file:font-medium file:bg-primary-50 file:text-primary-700 hover:file:bg-primary-100">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Deskripsi</label>
                <textarea name="deskripsi" rows="3" class="w-full border border-gray-300 rounded-lg px-4 py-2 text-sm focus:ring-2 focus:ring-primary-500 outline-none"><?= set_value('deskripsi', isset($product)?$product->deskripsi:'') ?></textarea>
            </div>
            <div class="flex items-center gap-2">
                <input type="checkbox" name="is_active" id="is_active" value="1" <?= set_checkbox('is_active', '1', !isset($product)||$product->is_active) ?> class="w-4 h-4 text-primary-600 rounded border-gray-300">
                <label for="is_active" class="text-sm text-gray-700">Produk Aktif (Tampil di toko online)</label>
            </div>
            <div class="flex items-center gap-3 pt-4 border-t border-gray-200">
                <button type="submit" class="bg-primary-600 hover:bg-primary-700 text-white px-6 py-2 rounded-lg text-sm font-medium flex items-center gap-2 transition">
                    <span class="iconify" data-icon="lucide:save"></span> Simpan
                </button>
                <a href="<?= site_url('admin/products') ?>" class="border border-gray-300 text-gray-700 px-6 py-2 rounded-lg text-sm font-medium hover:bg-gray-50 transition">Batal</a>
            </div>
        </form>
    </div>
</div>

<?= $this->load->view('admin/layout/footer', [], TRUE) ?>