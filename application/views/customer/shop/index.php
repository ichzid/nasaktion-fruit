<?= $this->load->view('customer/layout/header', ['title'=>'Belanja'], TRUE) ?>

<!-- ===== SHOP HEADER ===== -->
<div class="mb-6">
    <h2 class="font-display font-black text-2xl text-gray-900 mb-1">Daftar Produk</h2>
    <p class="text-sm text-gray-500">Temukan buah segar pilihan terbaik kami</p>
</div>

<!-- ===== SEARCH & FILTER BAR ===== -->
<div class="flex flex-col sm:flex-row gap-3 mb-6">
    <!-- Search -->
    <div class="relative flex-1">
        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
            <span class="iconify text-gray-400 text-lg" data-icon="lucide:search"></span>
        </div>
        <input type="text" id="searchProduct"
               placeholder="Cari nama buah..."
               class="w-full pl-10 pr-4 py-3 bg-white border border-gray-200 rounded-xl text-sm text-gray-800 placeholder-gray-400 outline-none focus:ring-2 focus:ring-primary-300 focus:border-primary-400 transition-all shadow-sm">
    </div>

    <!-- Filter -->
    <div class="relative">
        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
            <span class="iconify text-gray-400" data-icon="lucide:filter"></span>
        </div>
        <select id="filterJenis"
                class="pl-9 pr-8 py-3 bg-white border border-gray-200 rounded-xl text-sm text-gray-700 outline-none focus:ring-2 focus:ring-primary-300 focus:border-primary-400 transition-all shadow-sm appearance-none cursor-pointer">
            <option value="">Semua Jenis</option>
            <option value="Lokal">Lokal</option>
            <option value="Impor">Impor</option>
            <option value="Ekspor">Ekspor</option>
        </select>
    </div>

    <!-- Sort -->
    <div class="relative">
        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
            <span class="iconify text-gray-400" data-icon="lucide:arrow-up-down"></span>
        </div>
        <select id="sortProduct"
                class="pl-9 pr-8 py-3 bg-white border border-gray-200 rounded-xl text-sm text-gray-700 outline-none focus:ring-2 focus:ring-primary-300 focus:border-primary-400 transition-all shadow-sm appearance-none cursor-pointer">
            <option value="">Urutkan</option>
            <option value="price-asc">Harga: Murah ke Mahal</option>
            <option value="price-desc">Harga: Mahal ke Murah</option>
            <option value="name-asc">Nama: A → Z</option>
        </select>
    </div>
</div>

<!-- ===== RESULTS COUNT ===== -->
<div class="flex items-center justify-between mb-4">
    <p id="resultsCount" class="text-sm text-gray-500 font-medium"></p>
    <div class="flex items-center gap-2">
        <button id="viewGrid" onclick="setView('grid')" class="w-8 h-8 flex items-center justify-center rounded-lg bg-primary-600 text-white transition-all" title="Grid View">
            <span class="iconify" data-icon="lucide:layout-grid"></span>
        </button>
        <button id="viewList" onclick="setView('list')" class="w-8 h-8 flex items-center justify-center rounded-lg bg-white border border-gray-200 text-gray-500 hover:border-primary-300 hover:text-primary-600 transition-all" title="List View">
            <span class="iconify" data-icon="lucide:list"></span>
        </button>
    </div>
</div>

<!-- ===== PRODUCT GRID ===== -->
<div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-4 mb-4" id="productGrid">
    <?php foreach($products as $p): ?>
    <div class="product-card group bg-white rounded-2xl border border-gray-100 overflow-hidden shadow-sm hover:shadow-xl hover:border-primary-200 transition-all duration-300 hover:-translate-y-1"
         data-nama="<?= strtolower($p->nama_buah) ?>"
         data-jenis="<?= $p->jenis ?>"
         data-price="<?= $p->harga_jual ?>">

        <!-- Product image -->
        <a href="<?= site_url('customer/shop/product/'.$p->id) ?>" class="block relative w-full h-44 bg-gradient-to-br from-primary-50 to-primary-100 flex items-center justify-center overflow-hidden">
            <?php if($p->foto): ?>
            <img src="<?= base_url('uploads/products/'.$p->foto) ?>"
                 class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500"
                 alt="<?= $p->nama_buah ?>">
            <?php else: ?>
            <span class="text-6xl group-hover:scale-110 transition-transform duration-300">🍎</span>
            <?php endif; ?>

            <!-- Badge jenis -->
            <div class="absolute top-2.5 left-2.5">
                <span class="text-[10px] font-bold px-2 py-0.5 rounded-full <?= $p->jenis=='Impor'?'bg-blue-100 text-blue-700':($p->jenis=='Ekspor'?'bg-purple-100 text-purple-700':'bg-green-100 text-green-700') ?>">
                    <?= $p->jenis ?>
                </span>
            </div>

            <!-- Stok habis overlay -->
            <?php if($p->stok <= 0): ?>
            <div class="absolute inset-0 bg-gray-900/50 flex items-center justify-center">
                <span class="bg-white text-gray-700 text-xs font-bold px-3 py-1.5 rounded-full">Stok Habis</span>
            </div>
            <?php elseif($p->stok < 5): ?>
            <div class="absolute top-2.5 right-2.5">
                <span class="bg-red-100 text-red-600 text-[10px] font-bold px-2 py-0.5 rounded-full">Sisa <?= $p->stok ?></span>
            </div>
            <?php endif; ?>
        </a>

        <!-- Product info -->
        <div class="p-3.5">
            <a href="<?= site_url('customer/shop/product/'.$p->id) ?>" class="block group/link">
                <h3 class="text-sm font-semibold text-gray-800 truncate mb-0.5 group-hover/link:text-primary-600 transition"><?= $p->nama_buah ?></h3>
                <p class="text-[11px] text-gray-400 mb-2"><?= $p->satuan ?? 'per kg' ?> · Stok <?= $p->stok ?></p>
            </a>
            <div class="flex items-center justify-between">
                <p class="text-base font-bold text-primary-600">Rp <?= number_format($p->harga_jual,0,',','.') ?></p>
                <?php if($p->stok > 0): ?>
                <button onclick="addToCart(<?= $p->id ?>, this)"
                        class="h-9 px-3 bg-primary-600 hover:bg-primary-700 active:scale-95 text-white rounded-xl flex items-center justify-center gap-1.5 transition-all shadow-sm hover:shadow-md text-xs font-bold btn-add-cart">
                    <span class="iconify" data-icon="lucide:plus"></span>
                    <span>Keranjang</span>
                </button>
                <?php else: ?>
                <button disabled class="w-9 h-9 bg-gray-100 text-gray-400 rounded-xl flex items-center justify-center cursor-not-allowed">
                    <span class="iconify" data-icon="lucide:x"></span>
                </button>
                <?php endif; ?>
            </div>
        </div>
    </div>
    <?php endforeach; ?>
</div>

<!-- Empty state -->
<div id="emptyState" style="display:none;" class="text-center py-16 col-span-full">
    <span class="text-6xl block mb-4">😔</span>
    <p class="text-gray-500 font-medium mb-1">Produk tidak ditemukan</p>
    <p class="text-sm text-gray-400">Coba kata kunci lain atau ubah filter</p>
</div>

<?php if(empty($products)): ?>
<div class="text-center py-16 col-span-full">
    <span class="text-6xl block mb-4">🌱</span>
    <p class="text-gray-500 font-medium">Belum ada produk tersedia</p>
</div>
<?php endif; ?>

<!-- ===== PAGINATION ===== -->
<div id="paginationContainer" class="mt-6 flex items-center justify-between bg-white px-4 py-3 border border-gray-100 rounded-2xl shadow-sm hidden"></div>

<!-- ===== CART TOAST (mini) ===== -->
<div id="cartToast" class="fixed bottom-6 right-6 z-50 hidden">
    <div class="bg-gray-900 text-white px-4 py-3 rounded-2xl shadow-2xl flex items-center gap-3 text-sm font-medium">
        <span class="text-green-400 text-lg">✓</span>
        <span>Ditambahkan ke keranjang!</span>
    </div>
</div>

<script>
let currentView = 'grid';

function setView(v) {
    currentView = v;
    const grid = document.getElementById('productGrid');
    const btnGrid = document.getElementById('viewGrid');
    const btnList = document.getElementById('viewList');
    if (v === 'grid') {
        grid.className = 'grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-4 mb-4';
        btnGrid.className = 'w-8 h-8 flex items-center justify-center rounded-lg bg-primary-600 text-white transition-all';
        btnList.className = 'w-8 h-8 flex items-center justify-center rounded-lg bg-white border border-gray-200 text-gray-500 hover:border-primary-300 hover:text-primary-600 transition-all';
    } else {
        grid.className = 'flex flex-col gap-3 mb-4';
        btnList.className = 'w-8 h-8 flex items-center justify-center rounded-lg bg-primary-600 text-white transition-all';
        btnGrid.className = 'w-8 h-8 flex items-center justify-center rounded-lg bg-white border border-gray-200 text-gray-500 hover:border-primary-300 hover:text-primary-600 transition-all';
        // adjust card layout for list
        document.querySelectorAll('.product-card').forEach(card => {
            if(v === 'list') {
                card.style.display = card.style.display === 'none' ? 'none' : 'flex';
                card.classList.add('flex-row');
            }
        });
    }
    applyFilters();
}

function showCartToast() {
    const t = document.getElementById('cartToast');
    t.classList.remove('hidden');
    setTimeout(() => t.classList.add('hidden'), 2000);
}

function addToCart(productId, btn) {
    <?php if(!$this->session->userdata('customer_id')): ?>
    window.location.href = '<?= site_url('customer/auth/login') ?>';
    return;
    <?php endif; ?>

    const origHTML = btn.innerHTML;
    btn.innerHTML = '<span class="iconify animate-spin" data-icon="lucide:loader-2"></span> <span>...</span>';
    btn.disabled = true;

    fetch('<?= site_url('customer/cart/add') ?>', {
        method: 'POST',
        headers: {'Content-Type':'application/x-www-form-urlencoded'},
        body: 'product_id=' + productId + '&qty=1&<?= $this->security->get_csrf_token_name() ?>=<?= $this->security->get_csrf_hash() ?>'
    })
    .then(r => r.json())
    .then(data => {
        if (data.success) {
            // Update all badges
            document.querySelectorAll('.cart-badge').forEach(badge => {
                badge.textContent = data.cart_count;
                badge.classList.remove('hidden');
            });

            btn.innerHTML = '<span class="iconify" data-icon="lucide:check"></span> <span>Berhasil</span>';
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
            }, 1200);
        } else {
            btn.innerHTML = origHTML;
            btn.disabled = false;
            Swal.fire({icon:'error', title:'Gagal', text: data.message || 'Gagal menambahkan ke keranjang', timer:2000, showConfirmButton:false});
        }
    })
    .catch(() => {
        btn.innerHTML = origHTML;
        btn.disabled = false;
    });
}

const rowsPerPage = 12;
let currentPage = 1;
const allCards = () => Array.from(document.querySelectorAll('.product-card'));

function renderPagination(totalItems, page) {
    const container = document.getElementById('paginationContainer');
    const totalPages = Math.ceil(totalItems / rowsPerPage);
    const countEl = document.getElementById('resultsCount');
    
    if (totalItems === 0) {
        container.classList.add('hidden');
        countEl.textContent = 'Tidak ada produk ditemukan';
        return;
    }

    const startIdx = (page - 1) * rowsPerPage + 1;
    const endIdx = Math.min(page * rowsPerPage, totalItems);
    countEl.textContent = 'Menampilkan ' + startIdx + '–' + endIdx + ' dari ' + totalItems + ' produk';

    if (totalPages <= 1) { container.classList.add('hidden'); return; }
    container.classList.remove('hidden');

    let html = '<span class="text-sm text-gray-500">Hal. ' + page + ' / ' + totalPages + '</span>';
    html += '<div class="flex items-center gap-1.5">';
    html += `<button onclick="goToPage(${page-1})" class="w-8 h-8 flex items-center justify-center rounded-lg border text-sm transition ${page===1?'opacity-40 cursor-not-allowed border-gray-100':'border-gray-200 hover:bg-gray-100'}" ${page===1?'disabled':''}>
        <span class="iconify" data-icon="lucide:chevron-left"></span></button>`;

    for (let i = 1; i <= totalPages; i++) {
        if (i === 1 || i === totalPages || (i >= page-1 && i <= page+1)) {
            html += `<button onclick="goToPage(${i})" class="w-8 h-8 flex items-center justify-center rounded-lg text-sm border transition ${i===page?'bg-primary-600 text-white border-primary-600':'border-gray-200 hover:bg-gray-50'}">${i}</button>`;
        } else if (i === page-2 || i === page+2) {
            html += `<span class="text-gray-400 text-sm px-1">…</span>`;
        }
    }

    html += `<button onclick="goToPage(${page+1})" class="w-8 h-8 flex items-center justify-center rounded-lg border text-sm transition ${page===totalPages?'opacity-40 cursor-not-allowed border-gray-100':'border-gray-200 hover:bg-gray-100'}" ${page===totalPages?'disabled':''}>
        <span class="iconify" data-icon="lucide:chevron-right"></span></button>`;
    html += '</div>';
    container.innerHTML = html;
}

window.goToPage = function(page) { currentPage = page; applyFilters(); window.scrollTo({top:0,behavior:'smooth'}); }

function applyFilters() {
    const q   = document.getElementById('searchProduct').value.toLowerCase();
    const jen = document.getElementById('filterJenis').value;
    const srt = document.getElementById('sortProduct').value;

    let cards = allCards();

    // Hide all first
    cards.forEach(c => c.style.display = 'none');

    let filtered = cards.filter(c => {
        const nameMatch = c.dataset.nama.includes(q);
        const jenisMatch = (!jen || c.dataset.jenis === jen);
        return nameMatch && jenisMatch;
    });

    // Sort
    if (srt === 'price-asc')  filtered.sort((a,b) => +a.dataset.price - +b.dataset.price);
    if (srt === 'price-desc') filtered.sort((a,b) => +b.dataset.price - +a.dataset.price);
    if (srt === 'name-asc')   filtered.sort((a,b) => a.dataset.nama.localeCompare(b.dataset.nama));

    document.getElementById('emptyState').style.display = filtered.length === 0 ? 'block' : 'none';

    const start = (currentPage - 1) * rowsPerPage;
    const end   = start + rowsPerPage;
    filtered.forEach((c, i) => {
        c.style.display = (i >= start && i < end) ? '' : 'none';
    });

    renderPagination(filtered.length, currentPage);
}

document.getElementById('searchProduct').addEventListener('input', () => { currentPage = 1; applyFilters(); });
document.getElementById('filterJenis').addEventListener('change', () => { currentPage = 1; applyFilters(); });
document.getElementById('sortProduct').addEventListener('change', () => { currentPage = 1; applyFilters(); });

// Init
applyFilters();
</script>

<?= $this->load->view('customer/layout/footer', [], TRUE) ?>