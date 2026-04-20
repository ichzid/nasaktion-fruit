<?= $this->load->view('admin/layout/header', ['title'=>$title], TRUE) ?>

<div class="flex items-center justify-between mb-6">
    <div></div>
    <a href="<?= site_url('admin/users/create') ?>" class="bg-primary-600 hover:bg-primary-700 text-white px-4 py-2 rounded-lg text-sm font-medium flex items-center gap-2 transition">
        <span class="iconify" data-icon="lucide:plus"></span> Tambah User
    </a>
</div>

<div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">
    <table class="w-full text-sm">
        <thead class="bg-gray-50 border-b border-gray-200">
            <tr>
                <th class="text-left px-4 py-3 font-medium text-gray-600">User</th>
                <th class="text-left px-4 py-3 font-medium text-gray-600">Username</th>
                <th class="text-center px-4 py-3 font-medium text-gray-600">Role</th>
                <th class="text-center px-4 py-3 font-medium text-gray-600">Status</th>
                <th class="text-center px-4 py-3 font-medium text-gray-600">Aksi</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-100">
            <?php foreach($users as $u): ?>
            <tr class="hover:bg-gray-50">
                <td class="px-4 py-3">
                    <div class="flex items-center gap-3">
                        <div class="w-9 h-9 <?= $u->role=='admin'?'bg-purple-100':'bg-blue-100' ?> rounded-full flex items-center justify-center">
                            <span class="<?= $u->role=='admin'?'text-purple-600':'text-blue-600' ?> text-sm font-bold"><?= strtoupper(substr($u->nama_lengkap, 0, 1)) ?></span>
                        </div>
                        <span class="font-medium text-gray-800"><?= $u->nama_lengkap ?></span>
                    </div>
                </td>
                <td class="px-4 py-3 text-gray-600"><?= $u->username ?></td>
                <td class="px-4 py-3 text-center">
                    <span class="px-2 py-0.5 rounded-full text-xs font-medium <?= $u->role=='admin'?'bg-purple-100 text-purple-700':'bg-blue-100 text-blue-700' ?>"><?= ucfirst($u->role) ?></span>
                </td>
                <td class="px-4 py-3 text-center">
                    <span class="px-2 py-0.5 rounded-full text-xs font-medium <?= $u->is_active ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-500' ?>"><?= $u->is_active ? 'Aktif' : 'Nonaktif' ?></span>
                </td>
                <td class="px-4 py-3 text-center">
                    <div class="flex items-center justify-center gap-1">
                        <a href="<?= site_url('admin/users/edit/'.$u->id) ?>" class="p-1.5 text-blue-600 hover:bg-blue-50 rounded-lg"><span class="iconify" data-icon="lucide:pencil"></span></a>
                        <?php if($u->id != $this->session->userdata('admin_id')): ?>
                        <a href="<?= site_url('admin/users/delete/'.$u->id) ?>" class="p-1.5 text-red-600 hover:bg-red-50 rounded-lg" onclick="return confirm('Hapus user ini?')"><span class="iconify" data-icon="lucide:trash-2"></span></a>
                        <?php endif; ?>
                    </div>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<?= $this->load->view('admin/layout/footer', [], TRUE) ?>