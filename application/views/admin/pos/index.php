<?= $this->load->view('admin/layout/header', ['title'=>'Point of Sale'], TRUE) ?>

<style>
.pos-scroll::-webkit-scrollbar { width: 5px; }
.pos-scroll::-webkit-scrollbar-track { background: transparent; }
.pos-scroll::-webkit-scrollbar-thumb { background-color: #cbd5e1; border-radius: 10px; }
.pos-product.active-in-cart { border-color: #4f46e5; background: #eef2ff; }
</style>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6 items-start">

    <!-- ================== KIRI: Daftar Produk ================== -->
    <div class="lg:col-span-2">
        <!-- Filter Bar -->
        <div class="flex flex-wrap items-center gap-3 mb-4">
            <input type="text" id="posSearch" placeholder="Cari produk..." class="flex-1 border border-gray-300 rounded-lg px-4 py-2 text-sm focus:ring-2 focus:ring-primary-500 outline-none min-w-40">
            <select id="posFilterJenis" class="border border-gray-300 rounded-lg px-3 py-2 text-sm outline-none">
                <option value="">Semua Jenis</option>
                <option value="Lokal">Lokal</option>
                <option value="Impor">Impor</option>
                <option value="Ekspor">Ekspor</option>
            </select>
            <select id="posFilterCat" class="border border-gray-300 rounded-lg px-3 py-2 text-sm outline-none">
                <option value="">Semua Kategori</option>
                <?php foreach($categories as $cat): ?>
                <option value="<?= $cat->id ?>"><?= $cat->nama_kategori ?></option>
                <?php endforeach; ?>
            </select>
        </div>

        <!-- Product Grid -->
        <div class="grid grid-cols-2 md:grid-cols-3 xl:grid-cols-4 gap-3" id="productGrid">
            <?php foreach($products as $p): ?>
            <div class="pos-product bg-white rounded-xl border-2 border-gray-200 p-3 cursor-pointer hover:border-primary-400 hover:shadow-md transition"
                 data-id="<?= $p->id ?>"
                 data-nama="<?= htmlspecialchars($p->nama_buah) ?>"
                 data-harga="<?= (int)$p->harga_jual ?>"
                 data-stok="<?= (int)$p->stok ?>"
                 data-satuan="<?= $p->satuan ?>"
                 data-jenis="<?= $p->jenis ?>"
                 data-cat="<?= $p->category_id ?>"
                 onclick="addToCart(<?= $p->id ?>)">
                <div class="w-full h-24 bg-primary-50 rounded-lg flex items-center justify-center mb-2 overflow-hidden">
                    <?php if($p->foto): ?>
                    <img src="<?= base_url('uploads/products/'.$p->foto) ?>" class="w-full h-full object-cover" alt="">
                    <?php else: ?>
                    <span class="iconify text-primary-400 text-4xl" data-icon="lucide:apple"></span>
                    <?php endif; ?>
                </div>
                <p class="text-xs font-semibold text-gray-800 truncate"><?= $p->nama_buah ?></p>
                <p class="text-xs text-gray-400"><?= $p->jenis ?> · Stok: <span id="stok-display-<?= $p->id ?>"><?= $p->stok ?></span></p>
                <p class="text-sm font-bold text-primary-600 mt-1">Rp <?= number_format($p->harga_jual, 0, ',', '.') ?></p>
            </div>
            <?php endforeach; ?>
        </div>
    </div>

    <!-- ================== KANAN: Kasir (Sticky) ================== -->
    <div class="lg:col-span-1 lg:sticky lg:top-4 space-y-3 pos-scroll">

        <!-- Customer -->
        <div class="bg-white rounded-xl border border-gray-200 p-4 relative">
            <label class="text-sm font-semibold text-gray-700 mb-2 block">Pelanggan (Opsional)</label>
            <div class="flex gap-2">
                <input type="text" id="customerSearch" placeholder="Cari nama/no. HP..." class="flex-1 border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-primary-500 outline-none">
                <button onclick="searchCustomer()" class="bg-primary-600 text-white px-3 py-2 rounded-lg text-sm hover:bg-primary-700 transition">
                    <span class="iconify" data-icon="lucide:search"></span>
                </button>
            </div>
            <!-- Search Results Dropdown -->
            <div id="searchResults" class="hidden mt-1 border border-gray-200 rounded-lg max-h-56 overflow-y-auto bg-white shadow-lg z-50 absolute left-0 right-0"></div>
            <!-- Selected Customer -->
            <div id="customerResult" class="hidden mt-2">
                <div class="flex items-center justify-between bg-green-50 border border-green-200 rounded-lg p-2">
                    <div>
                        <p id="custName" class="text-sm font-semibold text-green-800"></p>
                        <p id="custPhone" class="text-xs text-green-600"></p>
                    </div>
                    <button onclick="clearCustomer()" class="text-red-500 text-xs hover:underline">✕ Hapus</button>
                </div>
            </div>
            <!-- New Customer Form -->
            <div id="newCustomerForm" class="hidden mt-3 bg-blue-50 border border-blue-200 rounded-xl p-4">
                <div class="flex items-center gap-2 mb-3">
                    <span class="iconify text-blue-600 text-lg" data-icon="lucide:user-plus"></span>
                    <p class="text-sm font-bold text-blue-800">Daftarkan Pelanggan Baru</p>
                </div>
                <div class="space-y-2">
                    <div>
                        <label class="text-xs font-semibold text-gray-600 mb-1 block">Nama Lengkap <span class="text-red-500">*</span></label>
                        <input type="text" id="newCustNama" placeholder="Cth: Budi Santoso"
                               class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-400 outline-none bg-white">
                    </div>
                    <div>
                        <label class="text-xs font-semibold text-gray-600 mb-1 block">No. WhatsApp <span class="text-gray-400">(Opsional)</span></label>
                        <input type="text" id="newCustWA" placeholder="Cth: 0812345678"
                               class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-400 outline-none bg-white">
                        <p class="text-[10px] text-gray-400 mt-1">Jika no. HP sudah terdaftar, data member lama akan digunakan</p>
                    </div>
                </div>
                <div class="flex gap-2 mt-3">
                    <button id="btnRegisterCust" onclick="registerNewCustomer()"
                            class="flex-1 bg-blue-600 hover:bg-blue-700 text-white text-sm font-semibold py-2 rounded-lg transition flex items-center justify-center gap-1.5">
                        <span class="iconify" data-icon="lucide:check"></span> Daftarkan & Pilih
                    </button>
                    <button onclick="document.getElementById('newCustomerForm').classList.add('hidden'); document.getElementById('customerSearch').focus();"
                            class="px-3 py-2 text-sm text-gray-500 border border-gray-200 rounded-lg hover:bg-gray-50 transition">
                        Batal
                    </button>
                </div>
            </div>
        </div>

        <!-- Cart -->
        <div class="bg-white rounded-xl border border-gray-200 p-4">
            <h3 class="font-semibold text-gray-800 mb-3 flex items-center gap-2">
                <span class="iconify" data-icon="lucide:shopping-cart"></span> Keranjang
                <span id="cartCount" class="bg-primary-100 text-primary-700 text-xs rounded-full px-2 py-0.5">0</span>
                <button onclick="clearCart()" class="ml-auto text-xs text-red-400 hover:text-red-600 hover:underline">Kosongkan</button>
            </h3>
            <div id="cartItems" class="space-y-1 max-h-52 overflow-y-auto pos-scroll">
                <p class="text-gray-400 text-sm text-center py-6">Klik produk untuk menambahkan</p>
            </div>
            <!-- Voucher -->
            <div class="mt-3 flex gap-2">
                <input type="text" id="voucherCode" placeholder="Kode voucher" class="flex-1 border border-gray-300 rounded-lg px-3 py-1.5 text-sm outline-none uppercase">
                <button onclick="applyVoucher()" class="bg-gray-100 hover:bg-gray-200 text-gray-700 text-xs px-3 py-1.5 rounded-lg transition">Pasang</button>
            </div>
            <div id="voucherInfo" class="hidden mt-1 text-xs text-green-600 font-medium"></div>
            <!-- Summary -->
            <div class="border-t border-gray-200 mt-3 pt-3 space-y-1.5">
                <div class="flex justify-between text-sm"><span class="text-gray-500">Subtotal</span><span id="subtotal" class="font-medium">Rp 0</span></div>
                <div class="flex justify-between text-sm"><span class="text-gray-500">Diskon</span><span id="discountAmount" class="text-red-500">- Rp 0</span></div>
                <div class="flex justify-between text-base font-bold border-t border-gray-200 pt-2 mt-2"><span>TOTAL</span><span id="totalAmount" class="text-primary-600">Rp 0</span></div>
            </div>
        </div>

        <!-- Payment -->
        <div class="bg-white rounded-xl border border-gray-200 p-4">
            <div id="cashSection" class="mb-3">
                <label class="text-sm font-semibold text-gray-700 mb-1 block">Uang Diterima</label>
                <input type="number" id="cashReceived" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm outline-none" placeholder="0" oninput="calcChange()">
                <p class="text-sm mt-1.5">Kembalian: <span id="changeAmount" class="font-bold text-primary-600">Rp 0</span></p>
            </div>
            <div class="mb-3">
                <label class="text-sm font-semibold text-gray-700 mb-1 block">Catatan</label>
                <input type="text" id="posNote" placeholder="Opsional..." class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm outline-none">
            </div>
            <button id="btnProcess" onclick="processTransaction()" class="w-full bg-primary-600 hover:bg-primary-700 disabled:opacity-60 disabled:cursor-not-allowed text-white font-semibold py-3 rounded-lg transition flex items-center justify-center gap-2">
                <span class="iconify" data-icon="lucide:check-circle"></span> Proses Transaksi
            </button>
        </div>
    </div>
</div>

<!-- ================== Receipt Modal ================== -->
<div id="receiptModal" class="fixed inset-0 bg-black/60 z-50 hidden items-center justify-center p-4" style="display:none">
    <div class="bg-white rounded-2xl max-w-sm w-full p-6 shadow-2xl max-h-[90vh] overflow-y-auto">
        <div id="receiptContent" class="text-center">
            <div class="w-12 h-12 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-3">
                <span class="iconify text-green-600 text-2xl" data-icon="lucide:check-circle-2"></span>
            </div>
            <h3 class="font-bold text-lg text-gray-800 mb-1">Nasaktion Fruit</h3>
            <p class="text-xs text-gray-400 mb-3">Struk Pembayaran</p>
            <div id="receiptBody" class="text-left text-sm space-y-1 border-t border-dashed border-gray-300 pt-3"></div>
        </div>
        <div class="mt-5 flex gap-2">
            <button onclick="printReceipt()" class="flex-1 bg-primary-600 text-white py-2 rounded-lg text-sm flex items-center justify-center gap-1 hover:bg-primary-700 transition">
                <span class="iconify" data-icon="lucide:printer"></span> Cetak
            </button>
            <button onclick="closeReceipt()" class="flex-1 border border-gray-300 text-gray-700 py-2 rounded-lg text-sm hover:bg-gray-50 transition">Tutup & Lanjut</button>
        </div>
    </div>
</div>

<!-- ================== Loading Overlay ================== -->
<div id="loadingOverlay" class="fixed inset-0 bg-black/40 z-50 hidden items-center justify-center" style="display:none">
    <div class="bg-white rounded-2xl p-6 flex flex-col items-center gap-3 shadow-xl">
        <svg class="animate-spin h-8 w-8 text-primary-600" viewBox="0 0 24 24" fill="none">
            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"/>
        </svg>
        <p class="text-sm text-gray-600 font-medium">Memproses transaksi...</p>
    </div>
</div>

<script>
// ========== State ==========
let posCart = {};        // { productId: { nama, harga, qty, stok, satuan } }
let selectedCustomerId  = null;
let currentDiscount     = 0;
let currentDiscountId   = null;
let appliedVoucherCode  = '';

const CSRF_NAME = '<?= $this->security->get_csrf_token_name() ?>';

// ========== Fresh CSRF token helper ==========
function getCsrfToken() {
    return fetch('<?= site_url("admin/pos/index") ?>', { method: 'HEAD' })
        .then(() => {
            // We'll just read it from a meta tag or re-fetch a lightweight endpoint
            return document.querySelector('meta[name="csrf-token"]')?.content || '';
        });
}

// ========== Cart ==========
function addToCart(id) {
    const el = document.querySelector(`.pos-product[data-id="${id}"]`);
    if (!el) return;
    const stok  = parseInt(el.dataset.stok);
    const harga = parseInt(el.dataset.harga);
    const nama  = el.dataset.nama;
    const satuan= el.dataset.satuan;

    if (stok <= 0) { showToast('Stok habis!', 'error'); return; }

    if (posCart[id]) {
        if (posCart[id].qty >= stok) { showToast('Stok tidak mencukupi!', 'error'); return; }
        posCart[id].qty++;
    } else {
        posCart[id] = { nama, harga, qty: 1, stok, satuan };
    }
    el.classList.add('active-in-cart');
    renderCart();
}

function removeFromCart(id) {
    delete posCart[id];
    const el = document.querySelector(`.pos-product[data-id="${id}"]`);
    if (el) el.classList.remove('active-in-cart');
    renderCart();
}

function updateQty(id, delta) {
    if (!posCart[id]) return;
    posCart[id].qty += delta;
    if (posCart[id].qty <= 0) { removeFromCart(id); return; }
    if (posCart[id].qty > posCart[id].stok) {
        posCart[id].qty = posCart[id].stok;
        showToast('Stok maksimal!', 'error');
    }
    renderCart();
}

function clearCart() {
    if (Object.keys(posCart).length === 0) return;
    if (!confirm('Kosongkan semua keranjang?')) return;
    posCart = {};
    currentDiscount = 0; currentDiscountId = null; appliedVoucherCode = '';
    document.getElementById('voucherCode').value = '';
    document.getElementById('voucherInfo').classList.add('hidden');
    document.querySelectorAll('.pos-product').forEach(el => el.classList.remove('active-in-cart'));
    renderCart();
}

function renderCart() {
    const container = document.getElementById('cartItems');
    const keys = Object.keys(posCart);
    if (keys.length === 0) {
        container.innerHTML = '<p class="text-gray-400 text-sm text-center py-6">Klik produk untuk menambahkan</p>';
        document.getElementById('cartCount').textContent = '0';
        updateSummary(0);
        return;
    }
    let html = '', subtotal = 0, count = 0;
    for (let id in posCart) {
        const item = posCart[id];
        const sub = item.harga * item.qty;
        subtotal += sub;
        count    += item.qty;
        html += `
        <div class="flex items-center gap-2 py-1.5 border-b border-gray-100 last:border-0">
            <div class="flex-1 min-w-0">
                <p class="text-sm font-medium text-gray-800 truncate">${item.nama}</p>
                <p class="text-xs text-gray-400">Rp ${fmt(item.harga)} / ${item.satuan}</p>
            </div>
            <div class="flex items-center gap-1 shrink-0">
                <button onclick="updateQty(${id},-1)" class="w-6 h-6 bg-gray-100 rounded text-sm font-bold hover:bg-red-100 hover:text-red-600 transition">−</button>
                <span class="text-sm w-6 text-center font-semibold">${item.qty}</span>
                <button onclick="updateQty(${id},1)" class="w-6 h-6 bg-gray-100 rounded text-sm font-bold hover:bg-green-100 hover:text-green-600 transition">+</button>
            </div>
            <div class="text-right shrink-0 w-20">
                <p class="text-sm font-semibold text-gray-800">Rp ${fmt(sub)}</p>
                <button onclick="removeFromCart(${id})" class="text-xs text-red-400 hover:text-red-600">hapus</button>
            </div>
        </div>`;
    }
    container.innerHTML = html;
    document.getElementById('cartCount').textContent = count;
    updateSummary(subtotal);
}

function updateSummary(subtotal) {
    const total = Math.max(0, subtotal - currentDiscount);
    document.getElementById('subtotal').textContent      = 'Rp ' + fmt(subtotal);
    document.getElementById('discountAmount').textContent = '- Rp ' + fmt(currentDiscount);
    document.getElementById('totalAmount').textContent   = 'Rp ' + fmt(total);
    calcChange();
}

function getSubtotal() { let t = 0; for (let id in posCart) t += posCart[id].harga * posCart[id].qty; return t; }
function getTotal()    { return Math.max(0, getSubtotal() - currentDiscount); }
function fmt(n)        { return parseInt(n).toLocaleString('id-ID'); }
function escHtml(str)  { return str ? str.replace(/[&<>"']/g, m => ({'&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;',"'":'&#39;'}[m])) : ''; }

// ========== Cash Change ==========
function calcChange() {
    const cash = parseInt(document.getElementById('cashReceived').value) || 0;
    const kembalian = Math.max(0, cash - getTotal());
    document.getElementById('changeAmount').textContent = 'Rp ' + fmt(kembalian);
}

// ========== Customer Search ==========
let searchTimeout = null;
document.getElementById('customerSearch').addEventListener('input', function() {
    clearTimeout(searchTimeout);
    searchTimeout = setTimeout(searchCustomer, 400);
});

function searchCustomer() {
    const q = document.getElementById('customerSearch').value.trim();
    const resultsDiv = document.getElementById('searchResults');
    if (!q) { resultsDiv.classList.add('hidden'); return; }
    fetch('<?= site_url("admin/pos/search_customer") ?>?q=' + encodeURIComponent(q))
        .then(r => r.json())
        .then(data => {
            if (!data.results || data.results.length === 0) {
                resultsDiv.innerHTML = '<div class="px-3 py-2 text-xs text-gray-400 cursor-pointer hover:bg-gray-50" onclick="showNewCustomerForm()">Pelanggan tidak ditemukan. <span class="text-primary-600 font-semibold">Daftarkan baru?</span></div>';
                resultsDiv.classList.remove('hidden');
            } else {
                let html = '';
                data.results.forEach(c => {
                    const segColor = c.segment === 'Platinum' ? 'bg-purple-100 text-purple-700' :
                                     c.segment === 'Gold'     ? 'bg-yellow-100 text-yellow-700' :
                                     c.segment === 'Silver'   ? 'bg-slate-100 text-slate-600' :
                                                                'bg-gray-100 text-gray-600';
                    html += `<div class="customer-suggestion flex items-center justify-between px-4 py-3 hover:bg-primary-50 cursor-pointer border-b border-gray-100 last:border-0 transition-colors group"
                                 data-id="${c.id}"
                                 data-nama="${escHtml(c.nama)}"
                                 data-hp="${escHtml(c.no_hp||'')}">
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 bg-primary-100 rounded-full flex items-center justify-center shrink-0">
                                <span class="text-primary-700 text-xs font-black">${escHtml(c.nama.charAt(0).toUpperCase())}</span>
                            </div>
                            <div>
                                <p class="text-sm font-bold text-gray-800 group-hover:text-primary-700">${escHtml(c.nama)}</p>
                                <p class="text-xs text-gray-400">${escHtml(c.no_hp||'-')}</p>
                            </div>
                        </div>
                        <span class="text-[10px] font-bold px-2 py-0.5 rounded-full uppercase tracking-wide ${segColor}">${c.segment}</span>
                    </div>`;
                });
                // Add "register new" option at bottom
                html += `<div class="px-4 py-2.5 text-xs text-gray-400 cursor-pointer hover:bg-gray-50 border-t border-gray-100 flex items-center gap-2" onclick="showNewCustomerForm()">
                    <span class="iconify text-sm text-primary-500" data-icon="lucide:user-plus"></span>
                    Tidak ada? <span class="text-primary-600 font-semibold">Daftarkan pelanggan baru</span>
                </div>`;
                resultsDiv.innerHTML = html;
                resultsDiv.classList.remove('hidden');

                // Attach click handlers via JS (safe for names with special chars)
                resultsDiv.querySelectorAll('.customer-suggestion').forEach(el => {
                    el.addEventListener('click', function() {
                        selectCustomer(
                            parseInt(this.dataset.id),
                            this.dataset.nama,
                            this.dataset.hp
                        );
                    });
                });
            }
        }).catch(() => { resultsDiv.classList.add('hidden'); });
}

function showNewCustomerForm() {
    document.getElementById('searchResults').classList.add('hidden');
    document.getElementById('newCustomerForm').classList.remove('hidden');
}

function selectCustomer(id, nama, phone) {
    selectedCustomerId = id;
    // Fill the search input with phone number only
    document.getElementById('customerSearch').value = phone || '';
    // Show the selected customer card below
    document.getElementById('custName').textContent  = nama;
    document.getElementById('custPhone').textContent = phone || 'Pelanggan terdaftar';
    document.getElementById('customerResult').classList.remove('hidden');
    document.getElementById('searchResults').classList.add('hidden');
    document.getElementById('newCustomerForm').classList.add('hidden');
    showToast('Pelanggan dipilih: ' + nama, 'success');
}

function clearCustomer() {
    selectedCustomerId = null;
    document.getElementById('customerResult').classList.add('hidden');
    document.getElementById('searchResults').classList.add('hidden');
    document.getElementById('customerSearch').value = '';
    document.getElementById('customerSearch').focus();
}

function registerNewCustomer() {
    const nama = document.getElementById('newCustNama').value.trim();
    const wa   = document.getElementById('newCustWA').value.trim();
    if (!nama) { showToast('Nama wajib diisi!', 'error'); return; }

    const btn = document.getElementById('btnRegisterCust');
    if (btn) { btn.disabled = true; btn.textContent = 'Menyimpan...'; }

    const resetBtn = () => {
        if (btn) {
            btn.disabled = false;
            btn.innerHTML = '<span class="iconify" data-icon="lucide:check"></span> Daftarkan & Pilih';
        }
    };

    fetch('<?= site_url("admin/pos/register_customer") ?>', {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: 'nama=' + encodeURIComponent(nama) + '&no_hp=' + encodeURIComponent(wa)
    })
    .then(r => r.text())
    .then(text => {
        resetBtn();
        let data;
        try { data = JSON.parse(text); }
        catch(e) {
            console.error('Server response (not JSON):', text);
            showToast('Gagal: server tidak merespons JSON. Cek console.', 'error');
            return;
        }

        if (data.success) {
            // Update UI
            selectedCustomerId = data.id;
            const el_name  = document.getElementById('custName');
            const el_phone = document.getElementById('custPhone');
            const el_result= document.getElementById('customerResult');
            const el_form  = document.getElementById('newCustomerForm');
            const el_search= document.getElementById('customerSearch');
            const el_sres  = document.getElementById('searchResults');

            if (el_name)   el_name.textContent  = data.nama || '';
            if (el_phone)  el_phone.textContent  = data.no_hp || 'Pelanggan terdaftar';
            if (el_result) el_result.classList.remove('hidden');
            if (el_sres)   el_sres.classList.add('hidden');
            if (el_form)   el_form.classList.add('hidden');
            if (el_search) el_search.value = data.no_hp || '';

            // Reset form fields
            document.getElementById('newCustNama').value = '';
            document.getElementById('newCustWA').value = '';
            showToast('✓ Pelanggan didaftarkan: ' + data.nama, 'success');
        } else {
            showToast(data.message || 'Gagal mendaftarkan!', 'error');
        }
    })
    .catch(err => {
        resetBtn();
        showToast('Koneksi ke server gagal!', 'error');
        console.error('registerNewCustomer fetch error:', err);
    });
}

// ========== Voucher ==========
function applyVoucher() {
    const code = document.getElementById('voucherCode').value.trim().toUpperCase();
    if (!code) { showToast('Masukkan kode voucher!', 'error'); return; }
    const total = getSubtotal();
    if (total === 0) { showToast('Tambahkan produk terlebih dahulu!', 'error'); return; }

    const btnVoucher = document.querySelector('button[onclick="applyVoucher()"]');
    if (btnVoucher) { btnVoucher.disabled = true; btnVoucher.textContent = '...'; }

    fetch(`<?= site_url("admin/pos/apply_voucher") ?>?code=${encodeURIComponent(code)}&total=${total}`)
    .then(r => r.text())
    .then(text => {
        if (btnVoucher) { btnVoucher.disabled = false; btnVoucher.textContent = 'Pasang'; }
        let data;
        try { data = JSON.parse(text); }
        catch(e) {
            console.error('Voucher response:', text);
            showToast('Server error saat validasi voucher!', 'error');
            return;
        }
        if (data.valid) {
            currentDiscount    = parseFloat(data.discount) || 0;
            currentDiscountId  = data.discount_id;
            appliedVoucherCode = code;
            updateSummary(getSubtotal());
            const info = document.getElementById('voucherInfo');
            if (info) {
                info.innerHTML = `✓ <strong>${data.nama_promo}</strong> — Diskon Rp ${fmt(data.discount)}
                    <span class="ml-2 cursor-pointer text-red-400 hover:text-red-600" onclick="removeVoucher()">✕ Hapus</span>`;
                info.classList.remove('hidden');
            }
            showToast('✓ Voucher ' + code + ' berhasil dipasang!', 'success');
        } else {
            showToast(data.message || 'Voucher tidak valid!', 'error');
        }
    })
    .catch(err => {
        if (btnVoucher) { btnVoucher.disabled = false; btnVoucher.textContent = 'Pasang'; }
        showToast('Koneksi gagal saat cek voucher!', 'error');
        console.error(err);
    });
}

function removeVoucher() {
    currentDiscount    = 0;
    currentDiscountId  = null;
    appliedVoucherCode = '';
    document.getElementById('voucherCode').value = '';
    const info = document.getElementById('voucherInfo');
    if (info) { info.classList.add('hidden'); info.innerHTML = ''; }
    updateSummary(getSubtotal());
    showToast('Voucher dihapus', 'success');
}

// ========== Process Transaction ==========
function processTransaction() {
    const keys = Object.keys(posCart);
    if (keys.length === 0) { showToast('Keranjang masih kosong!', 'error'); return; }

    const cash = parseInt(document.getElementById('cashReceived').value) || 0;
    if (cash < getTotal()) { showToast('Uang diterima kurang dari total!', 'error'); return; }

    const cartData = keys.map(id => ({
        product_id: parseInt(id),
        qty:        posCart[id].qty,
        harga:      posCart[id].harga
    }));

    showLoading(true);
    document.getElementById('btnProcess').disabled = true;

    // Build form body without CSRF (CSRF may be disabled for AJAX or use header)
    let body = 'items=' + encodeURIComponent(JSON.stringify(cartData));
    body += '&customer_id=' + (selectedCustomerId || '');
    body += '&payment_method=cash';
    body += '&discount=' + currentDiscount;
    body += '&discount_id=' + (currentDiscountId || '');
    body += '&catatan=' + encodeURIComponent(document.getElementById('posNote').value);

    fetch('<?= site_url("admin/pos/process") ?>', {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: body
    })
    .then(r => {
        if (!r.ok) throw new Error('HTTP ' + r.status);
        return r.text(); // text first to debug if not valid JSON
    })
    .then(text => {
        let data;
        try { data = JSON.parse(text); }
        catch(e) { throw new Error('Server response bukan JSON: ' + text.substring(0, 200)); }

        showLoading(false);
        document.getElementById('btnProcess').disabled = false;

        if (data.success) {
            showReceipt(data);
            // Reset cart
            posCart = {}; currentDiscount = 0; currentDiscountId = null; appliedVoucherCode = '';
            selectedCustomerId = null;
            document.getElementById('voucherCode').value = '';
            document.getElementById('voucherInfo').classList.add('hidden');
            document.getElementById('cashReceived').value = '';
            document.getElementById('changeAmount').textContent = 'Rp 0';
            document.getElementById('posNote').value = '';
            clearCustomer();
            document.querySelectorAll('.pos-product').forEach(el => el.classList.remove('active-in-cart'));
            renderCart();
        } else {
            showToast(data.message || 'Gagal memproses transaksi!', 'error');
        }
    })
    .catch(err => {
        showLoading(false);
        document.getElementById('btnProcess').disabled = false;
        showToast('Error: ' + err.message, 'error');
        console.error(err);
    });
}

// ========== Receipt ==========
function showReceipt(data) {
    let html = `<p class="text-center text-xs text-gray-500 font-mono">${data.invoice}</p>`;
    html += `<p class="text-center text-xs text-gray-400 mb-2">${data.tanggal}</p>`;
    html += '<div class="border-t border-dashed border-gray-300 my-2"></div>';
    data.items.forEach(i => {
        html += `<div class="flex justify-between text-xs py-0.5">
            <span>${i.nama} ×${i.qty}</span>
            <span>Rp ${fmt(i.subtotal)}</span>
        </div>`;
    });
    html += '<div class="border-t border-dashed border-gray-300 my-2"></div>';
    html += `<div class="flex justify-between text-sm"><span>Subtotal</span><span>Rp ${fmt(data.subtotal)}</span></div>`;
    if (data.diskon > 0) { html += `<div class="flex justify-between text-sm text-red-500"><span>Diskon</span><span>-Rp ${fmt(data.diskon)}</span></div>`; }
    html += `<div class="flex justify-between text-base font-bold mt-1 text-primary-700"><span>TOTAL</span><span>Rp ${fmt(data.total)}</span></div>`;
    html += `<p class="text-center mt-3 text-xs text-gray-400">Terima kasih telah berbelanja! 🙏</p>`;
    document.getElementById('receiptBody').innerHTML = html;
    showModal('receiptModal');
}

function printReceipt() {
    const content = document.getElementById('receiptContent').innerHTML;
    const w = window.open('','','width=300,height=600');
    w.document.write('<html><head><title>Struk</title></head><body style="font-family:monospace;font-size:12px;padding:15px;">' + content + '</body></html>');
    w.document.close();
    w.focus();
    w.print();
}

function closeReceipt() { hideModal('receiptModal'); }

// ========== UI Helpers ==========
function showModal(id)  { document.getElementById(id).style.display = 'flex'; }
function hideModal(id)  { document.getElementById(id).style.display = 'none'; }
function showLoading(b) { document.getElementById('loadingOverlay').style.display = b ? 'flex' : 'none'; }

function showToast(msg, type='info') {
    const existing = document.getElementById('pos-toast');
    if (existing) existing.remove();
    const colors = { success: 'bg-green-500', error: 'bg-red-500', info: 'bg-blue-500' };
    const t = document.createElement('div');
    t.id = 'pos-toast';
    t.className = `fixed bottom-6 right-6 z-[9999] ${colors[type]||colors.info} text-white text-sm font-medium px-5 py-3 rounded-xl shadow-lg transition-all`;
    t.textContent = msg;
    document.body.appendChild(t);
    setTimeout(() => { t.style.opacity='0'; setTimeout(()=>t.remove(), 300); }, 3000);
}

function escHtml(str) {
    return str ? str.replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;').replace(/"/g,'&quot;') : '';
}

// ========== Filters ==========
document.getElementById('posSearch').addEventListener('input', function(e) {
    const q = e.target.value.toLowerCase();
    const cat = document.getElementById('posFilterCat').value;
    const jenis = document.getElementById('posFilterJenis').value;
    applyFilters(q, cat, jenis);
});
document.getElementById('posFilterJenis').addEventListener('change', function() {
    const q = document.getElementById('posSearch').value.toLowerCase();
    const cat = document.getElementById('posFilterCat').value;
    applyFilters(q, cat, this.value);
});
document.getElementById('posFilterCat').addEventListener('change', function() {
    const q = document.getElementById('posSearch').value.toLowerCase();
    const jenis = document.getElementById('posFilterJenis').value;
    applyFilters(q, this.value, jenis);
});
function applyFilters(q, cat, jenis) {
    document.querySelectorAll('.pos-product').forEach(el => {
        const matchQ    = !q     || el.dataset.nama.toLowerCase().includes(q);
        const matchCat  = !cat   || el.dataset.cat === cat;
        const matchJenis= !jenis || el.dataset.jenis === jenis;
        el.style.display = (matchQ && matchCat && matchJenis) ? '' : 'none';
    });
}

// Close search results on outside click
document.addEventListener('click', function(e) {
    if (!e.target.closest('#customerSearch') && !e.target.closest('#searchResults')) {
        document.getElementById('searchResults').classList.add('hidden');
    }
});

</script>

<?= $this->load->view('admin/layout/footer', [], TRUE) ?>