<?= $this->load->view('customer/layout/header', ['title'=> $title], TRUE) ?>

<div class="mb-4">
    <a href="<?= site_url('customer/shop') ?>" class="text-sm text-gray-500 hover:text-primary-600 inline-flex items-center gap-1">
        <span class="iconify" data-icon="lucide:arrow-left"></span> Kembali ke Belanja
    </a>
</div>

<div class="bg-white rounded-3xl border border-gray-100 shadow-sm overflow-hidden mb-12">
    <div class="grid grid-cols-1 md:grid-cols-2">
        
        <!-- Product Image -->
        <div class="bg-primary-50 min-h-[300px] md:min-h-full flex items-center justify-center p-8 relative">
            <?php if($product->foto): ?>
            <img src="<?= base_url('uploads/products/'.$product->foto) ?>" alt="<?= $product->nama_buah ?>" class="max-w-full max-h-[400px] object-contain drop-shadow-2xl hover:scale-105 transition-transform duration-500">
            <?php else: ?>
            <span class="text-9xl drop-shadow-xl">🍎</span>
            <?php endif; ?>
            
            <div class="absolute top-6 left-6 flex flex-col gap-2">
                <span class="text-xs font-bold px-3 py-1 rounded-full shadow-sm <?= $product->jenis=='Impor' ? 'bg-blue-100 text-blue-700' : ($product->jenis=='Ekspor' ? 'bg-purple-100 text-purple-700' : 'bg-green-100 text-green-700') ?>">
                    <?= $product->jenis ?>
                </span>
                <?php if($product->stok <= 0): ?>
                <span class="bg-red-100 text-red-600 text-xs font-bold px-3 py-1 rounded-full shadow-sm">Habis</span>
                <?php endif; ?>
            </div>
        </div>

        <!-- Product Details -->
        <div class="p-8 md:p-12 flex flex-col justify-center">
            <h1 class="font-display font-black text-3xl md:text-4xl text-gray-900 mb-2"><?= $product->nama_buah ?></h1>
            
            <div class="flex items-center gap-4 mb-6">
                <!-- Rating -->
                <div class="flex items-center gap-1 bg-yellow-50 px-2 py-1 rounded-lg">
                    <span class="iconify text-yellow-400" data-icon="lucide:star"></span>
                    <span class="text-sm font-bold text-yellow-700"><?= number_format($avg_rating ?? 0, 1) ?></span>
                    <span class="text-xs text-yellow-600 ml-1">(<?= count($reviews) ?> ulasan)</span>
                </div>
                <div class="w-1.5 h-1.5 rounded-full bg-gray-300"></div>
                <p class="text-sm text-gray-500 font-medium">Stok: <span class="<?= $product->stok>0 ? 'text-green-600':'text-red-500' ?>"><?= $product->stok ?> <?= $product->satuan ?? 'kg' ?></span></p>
            </div>

            <p class="text-4xl font-black text-primary-600 mb-6 drop-shadow-sm">Rp <?= number_format($product->harga_jual,0,',','.') ?> <span class="text-lg text-gray-400 font-normal">/ <?= $product->satuan ?? 'kg' ?></span></p>

            <div class="prose prose-sm text-gray-600 mb-8 max-w-none">
                <h4 class="text-gray-800 font-semibold mb-2">Deskripsi</h4>
                <p><?= nl2br($product->deskripsi ?? 'Buah segar kualitas premium pilihan Nasaktion. Dipetik pada saat yang tepat untuk menjamin kesegaran dan cita rasa terbaik untuk keluarga Anda.') ?></p>
            </div>

            <!-- Add to Cart Action -->
            <div class="mt-auto">
                <?php if($product->stok > 0): ?>
                <div class="flex items-center gap-4">
                    <!-- Qty Control -->
                    <div class="flex items-center bg-gray-50 border border-gray-200 rounded-xl p-1 h-14">
                        <button type="button" onclick="updateQty(-1)" class="w-10 h-10 flex items-center justify-center rounded-lg hover:bg-white hover:shadow-sm text-gray-500 transition-all">-</button>
                        <input type="number" id="qtyInput" value="1" min="1" max="<?= $product->stok ?>"
                               onblur="validateQty()" onkeydown="if(event.key === 'Enter') { event.preventDefault(); this.blur(); }"
                               class="w-16 text-center bg-white border border-gray-200 rounded-lg font-bold text-gray-800 focus:border-primary-500 focus:ring-2 focus:ring-primary-200 outline-none appearance-none">
                        <button type="button" onclick="updateQty(1)" class="w-10 h-10 flex items-center justify-center rounded-lg hover:bg-white hover:shadow-sm text-gray-500 transition-all">+</button>
                    </div>
                    
                    <button type="button" onclick="addToCartDetail(<?= $product->id ?>, this)" class="flex-1 h-14 bg-primary-600 hover:bg-primary-700 text-white rounded-xl font-bold flex items-center justify-center gap-2 transition-all shadow-lg hover:shadow-primary-300 active:scale-[0.98]">
                        <span class="iconify text-xl" data-icon="lucide:shopping-cart"></span> Masukkan Keranjang
                    </button>
                </div>
                <?php else: ?>
                <div class="h-14 bg-gray-100 rounded-xl flex items-center justify-center text-gray-500 font-bold border border-gray-200 cursor-not-allowed">
                    Mohon Maaf, Stok Sedang Habis
                </div>
                <?php endif; ?>
            </div>
            
        </div>
    </div>
</div>

<!-- Tabs (Ulasan & Produk Terkait) -->
<div class="grid grid-cols-1 lg:grid-cols-3 gap-8 mb-12">
    <!-- Reviews -->
    <div class="lg:col-span-2">
        <h3 class="font-display font-bold text-2xl text-gray-900 mb-6 flex items-center gap-2">
            <span class="iconify text-yellow-500" data-icon="lucide:message-square"></span> Ulasan Pelanggan
        </h3>
        
        <?php if(!empty($reviews)): ?>
        <div class="space-y-4">
            <?php foreach($reviews as $r): ?>
            <div class="bg-white p-5 rounded-2xl border border-gray-100 shadow-sm">
                <div class="flex items-center justify-between mb-3">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 bg-primary-100 rounded-full flex items-center justify-center text-primary-700 font-bold">
                            <?= substr($r->nama ?? 'A', 0, 1) ?>
                        </div>
                        <div>
                            <p class="text-sm font-semibold text-gray-800"><?= $r->nama ?? 'Anonim' ?></p>
                            <p class="text-xs text-gray-400"><?= date('d M Y', strtotime($r->created_at)) ?></p>
                        </div>
                    </div>
                    <div class="flex gap-0.5">
                        <?php for($i=1; $i<=5; $i++): ?>
                        <span class="text-sm <?= $i <= $r->rating ? 'text-yellow-400' : 'text-gray-200' ?>">★</span>
                        <?php endfor; ?>
                    </div>
                </div>
                <p class="text-sm text-gray-600 leading-relaxed"><?= nl2br($r->isi_ulasan) ?></p>
                <?php if($r->balasan_admin): ?>
                <div class="mt-4 bg-gray-50 rounded-xl p-4 border-l-4 border-primary-500">
                    <p class="text-xs font-bold text-gray-700 mb-1">Balasan Admin:</p>
                    <p class="text-sm text-gray-600"><?= nl2br($r->balasan_admin) ?></p>
                </div>
                <?php endif; ?>
            </div>
            <?php endforeach; ?>
        </div>
        <?php else: ?>
        <div class="bg-white p-8 rounded-2xl border border-gray-100 text-center shadow-sm">
            <span class="text-4xl mb-2 block">💬</span>
            <p class="text-gray-500 font-medium">Belum ada ulasan untuk produk ini.</p>
            <p class="text-sm text-gray-400">Jadilah yang pertama untuk memberikan ulasan!</p>
        </div>
        <?php endif; ?>
    </div>

    <!-- Related Products -->
    <div>
        <h3 class="font-display font-bold text-xl text-gray-900 mb-6 pb-2 border-b border-gray-100">Produk Serupa</h3>
        <?php if(!empty($related)): ?>
        <div class="flex flex-col gap-4">
            <?php foreach($related as $rel): ?>
            <a href="<?= site_url('customer/shop/product/'.$rel->id) ?>" class="flex items-center gap-4 bg-white p-3 rounded-2xl border border-gray-100 hover:border-primary-200 hover:shadow-md transition-all group">
                <div class="w-20 h-20 bg-primary-50 rounded-xl flex items-center justify-center p-1 flex-shrink-0 overflow-hidden">
                    <?php if($rel->foto): ?>
                    <img src="<?= base_url('uploads/products/'.$rel->foto) ?>" class="w-full h-full object-cover group-hover:scale-110 transition-transform">
                    <?php else: ?>
                    <span class="text-3xl">🍎</span>
                    <?php endif; ?>
                </div>
                <div class="flex-1">
                    <h4 class="text-sm font-semibold text-gray-800 line-clamp-2 mb-1 group-hover:text-primary-600 transition-colors"><?= $rel->nama_buah ?></h4>
                    <p class="text-xs text-gray-500 mb-1">Stok: <?= $rel->stok ?></p>
                    <p class="font-bold text-primary-600">Rp <?= number_format($rel->harga_jual,0,',','.') ?></p>
                </div>
            </a>
            <?php endforeach; ?>
        </div>
        <?php else: ?>
        <p class="text-sm text-gray-500 italic bg-gray-50 p-4 rounded-xl text-center">Belum ada produk serupa.</p>
        <?php endif; ?>
    </div>
</div>

<!-- ===== CART TOAST ===== -->
<div id="cartToast" class="fixed bottom-6 right-6 z-50 hidden">
    <div class="bg-gray-900 text-white px-5 py-4 rounded-2xl shadow-2xl flex items-center gap-3 font-medium">
        <span class="text-green-400 text-xl border-2 border-green-400 rounded-full w-6 h-6 flex items-center justify-center drop-shadow-md">✓</span>
        <span>Berhasil ditambahkan ke keranjang!</span>
    </div>
</div>

<script>
    const maxQty = <?= $product->stok ?>;
    const qtyInput = document.getElementById('qtyInput');

    function validateQty() {
        if (!qtyInput) return 1;

        let val = parseInt(qtyInput.value, 10);
        if (Number.isNaN(val) || val < 1) val = 1;
        if (val > maxQty) val = maxQty;
        qtyInput.value = val;
        return val;
    }

    function updateQty(change) {
        if (!qtyInput) return;
        qtyInput.value = validateQty() + change;
        validateQty();
    }

    function showCartToast() {
        const t = document.getElementById('cartToast');
        t.classList.remove('hidden');
        setTimeout(() => t.classList.add('hidden'), 3000);
    }

    function addToCartDetail(productId, btn) {
        <?php if(!$this->session->userdata('customer_id')): ?>
        window.location.href = '<?= site_url('customer/auth/login') ?>';
        return;
        <?php endif; ?>

        const qty = qtyInput ? validateQty() : 1;
        const origHTML = btn.innerHTML;
        
        btn.innerHTML = '<span class="iconify animate-spin text-xl" data-icon="lucide:loader-2"></span> Memproses...';
        btn.disabled = true;

        fetch('<?= site_url('customer/cart/add') ?>', {
            method: 'POST',
            headers: {'Content-Type':'application/x-www-form-urlencoded'},
            body: 'product_id=' + productId + '&qty=' + qty + '&<?= $this->security->get_csrf_token_name() ?>=<?= $this->security->get_csrf_hash() ?>'
        })
        .then(r => r.json())
        .then(data => {
            if (data.success) {
                // Update all badges
                document.querySelectorAll('.cart-badge').forEach(badge => {
                    badge.textContent = data.cart_count;
                    badge.classList.remove('hidden');
                });

                btn.innerHTML = '<span class="iconify text-xl" data-icon="lucide:check-circle-2"></span> Berhasil Ditambahkan';
                btn.classList.remove('bg-primary-600','hover:bg-primary-700');
                btn.classList.add('bg-green-500');
                
                // Notification
                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil!',
                    text: data.message,
                    showConfirmButton: false,
                    timer: 1500,
                    toast: true,
                    position: 'top-end'
                });

                setTimeout(() => {
                    btn.innerHTML = origHTML;
                    btn.classList.add('bg-primary-600','hover:bg-primary-700');
                    btn.classList.remove('bg-green-500');
                    btn.disabled = false;
                }, 2000);
            } else {
                btn.innerHTML = origHTML;
                btn.disabled = false;
                Swal.fire({icon:'error', title:'Gagal', text: data.message || 'Gagal menambahkan ke keranjang'});
            }
        })
        .catch(() => {
            btn.innerHTML = origHTML;
            btn.disabled = false;
        });
    }
</script>

<?= $this->load->view('customer/layout/footer', [], TRUE) ?>
