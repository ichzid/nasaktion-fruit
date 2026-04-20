<?= $this->load->view('admin/layout/header', ['title'=>$title], TRUE) ?>

<div class="mb-6">
    <a href="<?= site_url('admin/feedback') ?>" class="text-sm text-primary-600 hover:underline flex items-center gap-1 w-fit">
        ← Kembali ke Daftar Feedback
    </a>
</div>

<div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden max-w-3xl">
    <div class="p-6">
        <div class="flex items-start justify-between mb-6">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 bg-primary-100 rounded-full flex items-center justify-center">
                    <span class="text-primary-600 font-bold text-lg"><?= strtoupper(substr($feedback->nama ?? 'A', 0, 1)) ?></span>
                </div>
                <div>
                    <h2 class="font-bold text-gray-800 text-lg"><?= $feedback->nama ?? 'Anonim' ?></h2>
                    <p class="text-sm text-gray-500"><?= $feedback->no_hp ?? 'Tidak ada No. HP' ?></p>
                </div>
            </div>
            <div class="text-right">
                <div class="text-lg text-yellow-500 mb-1"><?= str_repeat('⭐', $feedback->rating) ?></div>
                <div class="text-xs text-gray-400"><?= date('d M Y, H:i', strtotime($feedback->created_at)) ?></div>
            </div>
        </div>

        <div class="bg-gray-50 rounded-xl p-5 border border-gray-100 mb-6">
            <p class="text-gray-800 whitespace-pre-wrap"><?= $feedback->isi_ulasan ?></p>
        </div>

        <hr class="border-gray-100 mb-6">

        <h3 class="font-bold text-gray-800 mb-4">Balasan Admin</h3>
        
        <?php if($feedback->balasan_admin): ?>
        <div class="bg-primary-50 border border-primary-100 rounded-xl p-5 mb-6">
            <div class="flex items-center justify-between mb-2">
                <span class="text-sm font-bold text-primary-700">NasaktionFruit</span>
                <span class="text-xs text-primary-400"><?= date('d M Y, H:i', strtotime($feedback->balasan_at ?? $feedback->created_at)) ?></span>
            </div>
            <p class="text-gray-700 whitespace-pre-wrap"><?= $feedback->balasan_admin ?></p>
        </div>
        <?php endif; ?>

        <!-- Form Balasan -->
        <form action="<?= site_url('admin/feedback/reply/'.$feedback->id) ?>" method="post">
            <input type="hidden" name="<?= $this->security->get_csrf_token_name(); ?>" value="<?= $this->security->get_csrf_hash(); ?>">
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    <?= $feedback->balasan_admin ? 'Edit Balasan' : 'Tulis Balasan Baru' ?>
                </label>
                <textarea name="balasan_admin" rows="4" class="w-full border border-gray-300 rounded-xl p-4 text-sm outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500 resize-y" placeholder="Tulis tanggapan Anda di sini..."><?= $feedback->balasan_admin ?? '' ?></textarea>
                <?php if(form_error('balasan_admin')): ?>
                <span class="text-xs text-red-500 mt-1 block"><?= form_error('balasan_admin') ?></span>
                <?php endif; ?>
            </div>
            <div class="flex items-center gap-3">
                <button type="submit" class="bg-primary-600 hover:bg-primary-700 text-white px-6 py-2.5 rounded-lg text-sm font-medium transition flex items-center gap-2">
                    <span class="iconify" data-icon="lucide:send"></span> Kirim Balasan
                </button>
                <a href="<?= site_url('admin/feedback/delete/'.$feedback->id) ?>" onclick="return confirm('Hapus feedback ini permanen?')" class="text-red-500 hover:bg-red-50 px-4 py-2.5 rounded-lg text-sm font-medium transition">
                    Hapus
                </a>
            </div>
        </form>

    </div>
</div>

<?= $this->load->view('admin/layout/footer', [], TRUE) ?>
