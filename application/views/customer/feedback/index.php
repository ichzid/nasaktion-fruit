<?= $this->load->view('customer/layout/header', ['title'=>'Feedback'], TRUE) ?>

<div class="max-w-2xl mx-auto">
    <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-6">
        <h2 class="text-xl font-bold text-gray-800 mb-1">Berikan Ulasan Anda 💬</h2>
        <p class="text-sm text-gray-500 mb-6">Pendapat Anda sangat berarti untuk kami</p>


        <form action="<?= site_url('customer/feedback/submit') ?>" method="post" class="space-y-5">
            <input type="hidden" name="<?= $this->security->get_csrf_token_name(); ?>" value="<?= $this->security->get_csrf_hash(); ?>">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Rating *</label>
                <div class="flex items-center gap-1" id="starRating">
                    <?php for($i=1; $i<=5; $i++): ?>
                    <button type="button" class="star-btn text-3xl text-gray-300 hover:text-yellow-400 transition" data-rating="<?= $i ?>" onclick="setRating(<?= $i ?>)">★</button>
                    <?php endfor; ?>
                    <input type="hidden" name="rating" id="ratingValue" value="0">
                </div>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Produk (opsional)</label>
                <select name="product_id" class="w-full border border-gray-300 rounded-lg px-4 py-2 text-sm outline-none focus:ring-2 focus:ring-primary-500">
                    <option value="">-- Pilih Produk --</option>
                    <?php foreach($products as $p): ?>
                    <option value="<?= $p->id ?>"><?= $p->nama_buah ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Isi Ulasan *</label>
                <textarea name="isi_ulasan" rows="4" class="w-full border border-gray-300 rounded-lg px-4 py-2 text-sm outline-none focus:ring-2 focus:ring-primary-500" required placeholder="Ceritakan pengalaman Anda..."></textarea>
            </div>

            <button type="submit" class="bg-primary-600 hover:bg-primary-700 text-white px-6 py-2.5 rounded-lg text-sm font-medium transition">Kirim Ulasan</button>
        </form>
    </div>

    <!-- Previous Feedback -->
    <?php if(!empty($my_feedbacks)): ?>
    <div class="mt-6">
        <h3 class="font-semibold text-gray-800 mb-3">Ulasan Anda Sebelumnya</h3>
        <div class="space-y-3">
            <?php foreach($my_feedbacks as $fb): ?>
            <div class="bg-white rounded-xl border border-gray-200 p-4">
                <div class="flex items-center justify-between mb-2">
                    <span class="text-yellow-500"><?= str_repeat('★', $fb->rating) ?></span>
                    <span class="text-xs text-gray-400"><?= date('d M Y', strtotime($fb->created_at)) ?></span>
                </div>
                <p class="text-sm text-gray-700"><?= $fb->isi_ulasan ?></p>
                <?php if($fb->balasan_admin): ?>
                <div class="mt-2 bg-primary-50 rounded-lg p-3 border-l-4 border-primary-500">
                    <p class="text-xs text-primary-600 font-medium">Balasan Admin</p>
                    <p class="text-sm text-gray-700"><?= $fb->balasan_admin ?></p>
                </div>
                <?php endif; ?>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
    <?php endif; ?>
</div>

<script>
function setRating(val) {
    document.getElementById('ratingValue').value = val;
    document.querySelectorAll('.star-btn').forEach((btn, i) => {
        btn.style.color = (i < val) ? '#facc15' : '#d1d5db';
    });
}
</script>

<?= $this->load->view('customer/layout/footer', [], TRUE) ?>