<?= $this->load->view('admin/layout/header', ['title'=>$title], TRUE) ?>

<div class="max-w-md mx-auto">
    <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-6">
        <form action="<?= isset($user) ? site_url('admin/users/edit/'.$user->id) : site_url('admin/users/create') ?>" method="post" class="space-y-4">
            <input type="hidden" name="<?= $this->security->get_csrf_token_name(); ?>" value="<?= $this->security->get_csrf_hash(); ?>">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Nama Lengkap *</label>
                <input type="text" name="nama_lengkap" value="<?= set_value('nama_lengkap', isset($user)?$user->nama_lengkap:'') ?>" class="w-full border border-gray-300 rounded-lg px-4 py-2 text-sm outline-none focus:ring-2 focus:ring-primary-500" required>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Username *</label>
                <input type="text" name="username" value="<?= set_value('username', isset($user)?$user->username:'') ?>" class="w-full border border-gray-300 rounded-lg px-4 py-2 text-sm outline-none focus:ring-2 focus:ring-primary-500" required>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Password <?= isset($user)?'(Kosongkan jika tidak diubah)':'*' ?></label>
                <input type="password" name="password" class="w-full border border-gray-300 rounded-lg px-4 py-2 text-sm outline-none focus:ring-2 focus:ring-primary-500" <?= isset($user)?'':'required' ?>>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Role *</label>
                <select name="role" class="w-full border border-gray-300 rounded-lg px-4 py-2 text-sm outline-none">
                    <option value="admin" <?= set_select('role', 'admin', isset($user)&&$user->role=='admin') ?>>Admin</option>
                    <option value="kasir" <?= set_select('role', 'kasir', isset($user)&&$user->role=='kasir') ?>>Kasir</option>
                </select>
            </div>
            <?php if(isset($user) && $user->id != $this->session->userdata('admin_id')): ?>
            <div class="flex items-center gap-2 mt-4">
                <input type="checkbox" name="is_active" id="is_active" value="1" <?= isset($user) && $user->is_active ? 'checked' : '' ?> class="w-4 h-4 text-primary-600 focus:ring-primary-500 border-gray-300 rounded">
                <label for="is_active" class="text-sm font-medium text-gray-700">Akun Aktif</label>
            </div>
            <?php elseif(!isset($user)): ?>
            <input type="hidden" name="is_active" value="1">
            <?php endif; ?>
            <div class="flex gap-3 pt-3">
                <button type="submit" class="bg-primary-600 hover:bg-primary-700 text-white px-6 py-2 rounded-lg text-sm font-medium transition">Simpan</button>
                <a href="<?= site_url('admin/users') ?>" class="border border-gray-300 text-gray-700 px-6 py-2 rounded-lg text-sm hover:bg-gray-50 transition">Batal</a>
            </div>
        </form>
    </div>
</div>

<?= $this->load->view('admin/layout/footer', [], TRUE) ?>