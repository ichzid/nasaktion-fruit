<?= $this->load->view('admin/layout/header', ['title'=>$title], TRUE) ?>

<div class="flex items-center justify-between mb-6">
    <div class="flex items-center gap-3">
        <select id="filterLimit" class="border border-gray-300 rounded-lg px-3 py-2 text-sm outline-none text-gray-600">
            <option value="10">10 Baris</option>
            <option value="25">25 Baris</option>
            <option value="50">50 Baris</option>
            <option value="100">100 Baris</option>
        </select>
        <input type="text" id="searchInput" placeholder="Cari produk..." class="border border-gray-300 rounded-lg px-4 py-2 text-sm focus:ring-2 focus:ring-primary-500 outline-none w-64">
        <select id="filterJenis" class="border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-primary-500 outline-none">
            <option value="">Semua Jenis</option>
            <option value="Impor">Impor</option>
            <option value="Ekspor">Ekspor</option>
            <option value="Lokal">Lokal</option>
        </select>
    </div>
    <a href="<?= site_url('admin/products/create') ?>" class="bg-primary-600 hover:bg-primary-700 text-white px-4 py-2 rounded-lg text-sm font-medium flex items-center gap-2 transition">
        <span class="iconify" data-icon="lucide:plus"></span> Tambah Produk
    </a>
</div>

<div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="bg-gray-50 border-b border-gray-200">
                <tr>
                    <th class="text-left px-4 py-3 font-medium text-gray-600">Produk</th>
                    <th class="text-left px-4 py-3 font-medium text-gray-600">Kategori</th>
                    <th class="text-left px-4 py-3 font-medium text-gray-600">Jenis</th>
                    <th class="text-right px-4 py-3 font-medium text-gray-600">Harga Beli</th>
                    <th class="text-right px-4 py-3 font-medium text-gray-600">Harga Jual</th>
                    <th class="text-center px-4 py-3 font-medium text-gray-600">Stok</th>
                    <th class="text-center px-4 py-3 font-medium text-gray-600">Status</th>
                    <th class="text-center px-4 py-3 font-medium text-gray-600">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                <?php foreach($products as $p): ?>
                <tr class="hover:bg-gray-50 transition product-row" data-jenis="<?= $p->jenis ?>">
                    <td class="px-4 py-3">
                        <div class="flex items-center gap-3">
                            <?php if($p->foto): ?>
                            <img src="<?= base_url('uploads/products/'.$p->foto) ?>" class="w-10 h-10 rounded-lg object-cover" alt="<?= $p->nama_buah ?>">
                            <?php else: ?>
                            <div class="w-10 h-10 bg-primary-100 rounded-lg flex items-center justify-center">
                                <span class="iconify text-primary-600" data-icon="lucide:apple"></span>
                            </div>
                            <?php endif; ?>
                            <span class="font-medium text-gray-800 product-name"><?= $p->nama_buah ?></span>
                        </div>
                    </td>
                    <td class="px-4 py-3 text-gray-600"><?= $p->nama_kategori ?? '-' ?></td>
                    <td class="px-4 py-3"><span class="px-2 py-0.5 rounded-full text-xs font-medium <?= $p->jenis=='Impor'?'bg-blue-100 text-blue-700':($p->jenis=='Ekspor'?'bg-purple-100 text-purple-700':'bg-green-100 text-green-700') ?>"><?= $p->jenis ?></span></td>
                    <td class="px-4 py-3 text-right text-gray-600">Rp <?= number_format($p->harga_beli, 0, ',', '.') ?></td>
                    <td class="px-4 py-3 text-right font-medium text-gray-800">Rp <?= number_format($p->harga_jual, 0, ',', '.') ?></td>
                    <td class="px-4 py-3 text-center"><span class="<?= $p->stok <= 10 ? 'text-red-600 font-bold' : 'text-gray-800' ?>"><?= $p->stok ?></span> <?= $p->satuan ?></td>
                    <td class="px-4 py-3 text-center">
                        <span class="px-2 py-0.5 rounded-full text-xs font-medium <?= $p->is_active?'bg-green-100 text-green-700':'bg-gray-100 text-gray-500' ?>"><?= $p->is_active?'Aktif':'Nonaktif' ?></span>
                    </td>
                    <td class="px-4 py-3 text-center">
                        <div class="flex items-center justify-center gap-1">
                            <a href="<?= site_url('admin/products/edit/'.$p->id) ?>" class="p-1.5 text-blue-600 hover:bg-blue-50 rounded-lg transition" title="Edit">
                                <span class="iconify" data-icon="lucide:pencil"></span>
                            </a>
                            <a href="<?= site_url('admin/products/delete/'.$p->id) ?>" class="p-1.5 text-red-600 hover:bg-red-50 rounded-lg transition confirm-delete" title="Hapus" onclick="return confirm('Hapus produk ini?')">
                                <span class="iconify" data-icon="lucide:trash-2"></span>
                            </a>
                        </div>
                    </td>
                </tr>
                <?php endforeach; ?>
                <?php if(empty($products)): ?>
                <tr id="emptyStateRow" style="display:none;"><td colspan="8" class="text-center py-8 text-gray-400">Belum ada produk atau data tidak ditemukan</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
    <div id="paginationContainer" class="px-4 py-3 border-t border-gray-200 flex items-center justify-between bg-gray-50 rounded-b-xl"></div>
</div>

<script>
let rowsPerPage = 10;
let currentPage = 1;
const allRows = Array.from(document.querySelectorAll('.product-row'));

function renderPagination(totalRows, page) {
    const container = document.getElementById('paginationContainer');
    const totalPages = Math.ceil(totalRows / rowsPerPage);
    
    if (totalRows === 0) {
        container.innerHTML = '<span class="text-sm text-gray-500">Menampilkan 0 data</span><div></div>';
        return;
    }
    
    const startIdx = (page - 1) * rowsPerPage + 1;
    const endIdx = Math.min(page * rowsPerPage, totalRows);
    
    let html = '<span class="text-sm text-gray-500">Menampilkan ' + startIdx + ' - ' + endIdx + ' dari ' + totalRows + ' produk</span>';
    
    if (totalPages > 1) {
        html += '<div class="flex items-center gap-1">';
        html += `<button onclick="goToPage(${page - 1})" class="px-2 py-1 text-sm border rounded hover:bg-gray-100 transition ${page === 1 ? 'opacity-50 cursor-not-allowed' : ''}" ${page === 1 ? 'disabled' : ''}>Prev</button>`;
        
        for (let i = 1; i <= totalPages; i++) {
            if (i === 1 || i === totalPages || (i >= page - 1 && i <= page + 1)) {
                html += `<button onclick="goToPage(${i})" class="px-3 py-1 text-sm border rounded transition ${i === page ? 'bg-primary-600 text-white border-primary-600' : 'hover:bg-gray-100'}">${i}</button>`;
            } else if (i === page - 2 || i === page + 2) {
                html += `<span class="px-2 text-gray-400">...</span>`;
            }
        }
        
        html += `<button onclick="goToPage(${page + 1})" class="px-2 py-1 text-sm border rounded hover:bg-gray-100 transition ${page === totalPages ? 'opacity-50 cursor-not-allowed' : ''}" ${page === totalPages ? 'disabled' : ''}>Next</button>`;
        html += '</div>';
    } else {
        html += '<div></div>'; 
    }
    
    container.innerHTML = html;
}

window.goToPage = function(page) {
    currentPage = page;
    applyFilters();
}

function applyFilters() {
    const q = document.getElementById('searchInput').value.toLowerCase();
    const val = document.getElementById('filterJenis').value;
    
    let filtered = [];
    allRows.forEach(row => {
        const name = row.querySelector('.product-name').textContent.toLowerCase();
        const jenisMatch = (!val || row.dataset.jenis === val);
        const nameMatch = name.includes(q);
        
        if (jenisMatch && nameMatch) {
            filtered.push(row);
        } else {
            row.style.display = 'none';
        }
    });
    
    // Toggle empty state
    const emptyState = document.getElementById('emptyStateRow');
    if(emptyState) emptyState.style.display = filtered.length === 0 ? '' : 'none';
    
    const start = (currentPage - 1) * rowsPerPage;
    const end = start + rowsPerPage;
    
    filtered.forEach((row, index) => {
        row.style.display = (index >= start && index < end) ? '' : 'none';
    });
    
    renderPagination(filtered.length, currentPage);
}

document.getElementById('searchInput').addEventListener('input', () => { currentPage = 1; applyFilters(); });
document.getElementById('filterJenis').addEventListener('change', () => { currentPage = 1; applyFilters(); });
document.getElementById('filterLimit')?.addEventListener('change', (e) => { rowsPerPage = parseInt(e.target.value); currentPage = 1; applyFilters(); });

// Init
applyFilters();
</script>

<?= $this->load->view('admin/layout/footer', [], TRUE) ?>