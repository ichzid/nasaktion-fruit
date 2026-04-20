<?= $this->load->view('admin/layout/header', ['title'=>$title], TRUE) ?>

<div class="flex items-center gap-3 mb-6">
    <select id="filterRating" class="border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-primary-500 outline-none">
        <option value="">Semua Rating</option>
        <option value="5">⭐⭐⭐⭐⭐</option>
        <option value="4">⭐⭐⭐⭐</option>
        <option value="3">⭐⭐⭐</option>
        <option value="2">⭐⭐</option>
        <option value="1">⭐</option>
    </select>
</div>

<div class="space-y-4">
    <?php foreach($feedbacks as $fb): ?>
    <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-5 feedback-card" data-rating="<?= $fb->rating ?>">
        <div class="flex items-start justify-between">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 bg-primary-100 rounded-full flex items-center justify-center">
                    <span class="text-primary-600 text-sm font-bold"><?= strtoupper(substr($fb->nama ?? 'A', 0, 1)) ?></span>
                </div>
                <div>
                    <p class="font-medium text-gray-800"><?= $fb->nama ?? 'Anonim' ?></p>
                    <p class="text-xs text-gray-400"><?= date('d M Y H:i', strtotime($fb->created_at)) ?> · <?= !empty($fb->product_nama) ? 'Produk: '.$fb->product_nama : 'Ulasan Umum' ?></p>
                </div>
            </div>
            <div class="flex items-center gap-2">
                <span class="text-yellow-500"><?= str_repeat('⭐', $fb->rating) ?></span>
                <?php if(!$fb->balasan_admin): ?>
                <span class="px-2 py-0.5 bg-yellow-100 text-yellow-700 text-xs rounded-full">Belum dibalas</span>
                <?php else: ?>
                <span class="px-2 py-0.5 bg-green-100 text-green-700 text-xs rounded-full">Dibalas</span>
                <?php endif; ?>
            </div>
        </div>
        <p class="mt-3 text-gray-700 text-sm"><?= $fb->isi_ulasan ?></p>
        
        <?php if($fb->balasan_admin): ?>
        <div class="mt-3 bg-primary-50 rounded-lg p-3 border-l-4 border-primary-500">
            <p class="text-xs text-primary-600 font-medium mb-1">Balasan Admin</p>
            <p class="text-sm text-gray-700"><?= $fb->balasan_admin ?></p>
        </div>
        <?php endif; ?>

        <div class="mt-3 flex items-center gap-2">
            <button onclick="toggleReply(<?= $fb->id ?>)" class="text-sm text-primary-600 hover:underline flex items-center gap-1">
                <span class="iconify" data-icon="lucide:reply"></span> <?= $fb->balasan_admin ? 'Edit Balasan' : 'Balas' ?>
            </button>
        </div>

        <div id="replyForm-<?= $fb->id ?>" class="mt-3 hidden">
            <form action="<?= site_url('admin/feedback/reply/'.$fb->id) ?>" method="post" class="flex gap-2">
                <input type="hidden" name="<?= $this->security->get_csrf_token_name(); ?>" value="<?= $this->security->get_csrf_hash(); ?>">
                <textarea name="balasan_admin" rows="2" class="flex-1 border border-gray-300 rounded-lg px-3 py-2 text-sm outline-none focus:ring-2 focus:ring-primary-500" placeholder="Tulis balasan..."><?= $fb->balasan_admin ?? '' ?></textarea>
                <button type="submit" class="bg-primary-600 text-white px-4 py-2 rounded-lg text-sm hover:bg-primary-700 transition self-end">Kirim</button>
            </form>
        </div>
    </div>
    <?php endforeach; ?>
    <?php if(empty($feedbacks)): ?>
    <div class="bg-white rounded-xl border border-gray-200 p-8 text-center text-gray-400">Belum ada feedback</div>
    <?php endif; ?>
</div>

<script>
function toggleReply(id) { document.getElementById('replyForm-'+id).classList.toggle('hidden'); }
document.getElementById('filterRating')?.addEventListener('change', function(e) {
    const val = e.target.value;
    document.querySelectorAll('.feedback-card').forEach(card => {
        card.style.display = (!val || card.dataset.rating === val) ? '' : 'none';
    });
});
</script>

<?= $this->load->view('admin/layout/footer', [], TRUE) ?>