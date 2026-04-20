<?= $this->load->view('admin/layout/header', ['title'=>$title], TRUE) ?>

<div class="flex items-center justify-between mb-6">
    <div class="flex items-center gap-3">
        <input type="text" id="searchCat" placeholder="Cari kategori..." class="border border-gray-300 rounded-lg px-4 py-2 text-sm focus:ring-2 focus:ring-primary-500 outline-none w-64">
    </div>
    <a href="<?= site_url('admin/categories/create') ?>" class="bg-primary-600 hover:bg-primary-700 text-white px-4 py-2 rounded-lg text-sm font-medium flex items-center gap-2 transition">
        <span class="iconify" data-icon="lucide:plus"></span> Tambah Kategori
    </a>
</div>

<div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">
    <table class="w-full text-sm">
        <thead class="bg-gray-50 border-b border-gray-200">
            <tr>
                <th class="text-left px-4 py-3 font-medium text-gray-600 w-16">No</th>
                <th class="text-left px-4 py-3 font-medium text-gray-600">Nama Kategori</th>
                <th class="text-left px-4 py-3 font-medium text-gray-600">Deskripsi</th>
                <th class="text-center px-4 py-3 font-medium text-gray-600 w-24">Aksi</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-100">
            <?php $no = 1; foreach($categories as $c): ?>
            <tr class="hover:bg-gray-50 transition cat-row">
                <td class="px-4 py-3 text-gray-600"><?= $no++ ?></td>
                <td class="px-4 py-3">
                    <p class="font-medium text-gray-800 cat-name"><?= $c->nama_kategori ?></p>
                </td>
                <td class="px-4 py-3 text-gray-600">
                    <?= $c->deskripsi ?: '-' ?>
                </td>
                <td class="px-4 py-3 text-center">
                    <div class="flex items-center justify-center gap-2">
                        <a href="<?= site_url('admin/categories/edit/'.$c->id) ?>" class="p-1.5 text-blue-600 hover:bg-blue-50 rounded-lg transition" title="Edit"><span class="iconify text-lg" data-icon="lucide:pencil"></span></a>
                        <a href="<?= site_url('admin/categories/delete/'.$c->id) ?>" class="p-1.5 text-red-600 hover:bg-red-50 rounded-lg transition" onclick="return confirm('Yakin ingin menghapus kategori ini?')" title="Hapus"><span class="iconify text-lg" data-icon="lucide:trash-2"></span></a>
                    </div>
                </td>
            </tr>
            <?php endforeach; ?>
            <?php if(empty($categories)): ?>
            <tr><td colspan="4" class="text-center py-8 text-gray-400">Belum ada kategori</td></tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<script>
document.getElementById('searchCat').addEventListener('input', function(e) {
    const q = e.target.value.toLowerCase();
    document.querySelectorAll('.cat-row').forEach(row => {
        const name = row.querySelector('.cat-name').textContent.toLowerCase();
        row.style.display = name.includes(q) ? '' : 'none';
    });
});
</script>

<?= $this->load->view('admin/layout/footer', [], TRUE) ?>