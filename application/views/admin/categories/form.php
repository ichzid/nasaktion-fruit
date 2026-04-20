<?= $this->load->view('admin/layout/header', ['title'=>$title], TRUE) ?>

<div class="max-w-2xl mx-auto bg-white rounded-xl border border-gray-200 shadow-sm p-6">
    <div class="flex items-center justify-between mb-6">
        <h3 class="font-semibold text-gray-800 text-lg"><?= $title ?></h3>
        <a href="<?= site_url('admin/categories') ?>" class="text-gray-400 hover:text-gray-600">
            <span class="iconify text-xl" data-icon="lucide:x"></span>
        </a>
    </div>

    <?php if(validation_errors()): ?>
    <div class="bg-red-50 text-red-600 p-3 rounded-lg mb-4 text-sm">
        <?= validation_errors() ?>
    </div>
    <?php endif; ?>

    <form action="<?= isset($category) ? site_url('admin/categories/edit/'.$category->id) : site_url('admin/categories/create') ?>" method="post" class="space-y-4">
        <input type="hidden" name="<?= $this->security->get_csrf_token_name(); ?>" value="<?= $this->security->get_csrf_hash(); ?>">
        
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Nama Kategori *</label>
            <input type="text" name="nama_kategori" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm outline-none focus:ring-2 focus:ring-primary-500" value="<?= set_value('nama_kategori', isset($category) ? $category->nama_kategori : '') ?>" required>
        </div>
        
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Deskripsi</label>
            <textarea name="deskripsi" rows="4" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm outline-none focus:ring-2 focus:ring-primary-500"><?= set_value('deskripsi', isset($category) ? $category->deskripsi : '') ?></textarea>
        </div>
        
        <div class="pt-4 flex justify-end">
            <button type="submit" class="bg-primary-600 hover:bg-primary-700 text-white font-medium px-6 py-2 rounded-lg transition">
                Simpan
            </button>
        </div>
    </form>
</div>

<?= $this->load->view('admin/layout/footer', [], TRUE) ?>
