<?= $this->load->view('admin/layout/header', ['title'=>$title], TRUE) ?>

<!-- Page Header -->
<div class="mb-6 flex flex-col md:flex-row md:items-center justify-between gap-4">
    <div>
        <h2 class="text-2xl font-bold font-display text-gray-900 border-l-4 border-primary-600 pl-3">Daftar Transaksi</h2>
        <p class="text-sm text-gray-500 mt-1 pl-4">Kelola dan pantau seluruh transaksi penjualan Online maupun Offline.</p>
    </div>
</div>

<!-- Main Card -->
<div class="bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden">
    <!-- Toolbar Filters -->
    <div class="p-4 border-b border-gray-100 bg-gray-50 flex flex-col sm:flex-row items-center justify-between gap-4">
        <div class="flex items-center gap-3 w-full sm:w-auto">
            <div class="relative w-full sm:w-72">
                <span class="iconify absolute left-3 top-1/2 -translate-y-1/2 text-gray-400" data-icon="lucide:search"></span>
                <input type="text" id="searchInput" placeholder="Cari invoice atau nama pelanggan..." class="w-full border border-gray-300 rounded-xl pl-9 pr-4 py-2 text-sm focus:ring-2 focus:ring-primary-500 focus:border-primary-500 outline-none transition-shadow">
            </div>
        </div>
        <div class="flex flex-wrap items-center gap-3 w-full sm:w-auto">
            <div class="flex items-center gap-2 bg-white border border-gray-300 rounded-xl px-3 py-1.5 focus-within:ring-2 focus-within:ring-primary-500">
                <span class="iconify text-gray-400" data-icon="lucide:list-filter"></span>
                <select id="filterStatus" class="bg-transparent text-sm text-gray-700 outline-none py-1">
                    <option value="">Semua Status</option>
                    <option value="pending">Tertunda</option>
                    <option value="paid">Dibayar</option>
                    <option value="verified">Di Proses</option>
                    <option value="shipped">Dikirim</option>
                    <option value="completed">Selesai</option>
                    <option value="cancelled">Dibatalkan</option>
                </select>
            </div>
            <div class="flex items-center gap-2 bg-white border border-gray-300 rounded-xl px-3 py-1.5 focus-within:ring-2 focus-within:ring-primary-500">
                <span class="iconify text-gray-400" data-icon="lucide:monitor-smartphone"></span>
                <select id="filterType" class="bg-transparent text-sm text-gray-700 outline-none py-1">
                    <option value="">Semua Filter Jalur</option>
                    <option value="Online">Online</option>
                    <option value="Offline">Offline</option>
                </select>
            </div>
            <div class="flex items-center gap-2 bg-white border border-gray-300 rounded-xl px-3 py-1.5 focus-within:ring-2 focus-within:ring-primary-500">
                <span class="iconify text-gray-400" data-icon="lucide:calendar"></span>
                <input type="date" id="filterDate" class="bg-transparent text-sm text-gray-700 outline-none py-1">
            </div>
            <select id="filterLimit" class="bg-white border border-gray-300 rounded-xl px-3 py-2 text-sm text-gray-700 outline-none focus:ring-2 focus:ring-primary-500 w-full sm:w-auto">
                <option value="10">10 Baris</option>
                <option value="25">25 Baris</option>
                <option value="50">50 Baris</option>
                <option value="100">100 Baris</option>
            </select>
        </div>
    </div>

    <!-- Table -->
    <div class="overflow-x-auto w-full">
        <table class="w-full text-sm text-left">
            <thead class="bg-gray-50/50 text-gray-500 font-semibold uppercase text-xs tracking-wider border-b border-gray-200">
                <tr>
                    <th class="px-6 py-4 rounded-tl-2xl">Info Transaksi</th>
                    <th class="px-6 py-4">Metode & Jenis</th>
                    <th class="px-6 py-4 text-center">Status</th>
                    <th class="px-6 py-4 text-right">Total Akhir</th>
                    <th class="px-6 py-4 text-center rounded-tr-2xl">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                <?php foreach($transactions as $t): ?>
                <tr class="hover:bg-primary-50/50 transition-colors tx-row group" data-status="<?= $t->status ?>" data-type="<?= $t->jenis_order ?>" data-date="<?= date('Y-m-d', strtotime($t->tgl)) ?>" data-invoice="<?= strtolower($t->invoice_no) ?>" data-nama="<?= strtolower($t->nama ?? 'guest') ?>">
                    <td class="px-6 py-4">
                        <div class="flex flex-col">
                            <span class="font-bold text-primary-700 text-base"><?= $t->invoice_no ?></span>
                            <span class="text-gray-600 font-medium mt-0.5"><?= $t->nama ?? 'Pelanggan Umum' ?></span>
                            <span class="text-xs text-gray-400 mt-1 flex items-center gap-1"><span class="iconify" data-icon="lucide:clock"></span> <?= date('d M Y, H:i', strtotime($t->tgl)) ?> WIB</span>
                        </div>
                    </td>
                    <td class="px-6 py-4">
                        <div class="flex flex-col items-start gap-1">
                            <span class="px-2.5 py-1 rounded-md text-[10px] font-bold uppercase tracking-wider <?= $t->jenis_order=='Online'?'bg-blue-100 text-blue-700 border border-blue-200':'bg-emerald-100 text-emerald-700 border border-emerald-200' ?>">
                                <?= $t->jenis_order ?>
                            </span>
                        </div>
                    </td>
                    <td class="px-6 py-4 text-center">
                        <?php 
                            $status_badge = [
                                'pending' => ['Tertunda', 'bg-amber-100 text-amber-700 border-amber-200'],
                                'paid' => ['Dibayar', 'bg-blue-100 text-blue-700 border-blue-200'],
                                'verified' => ['Di Proses', 'bg-indigo-100 text-indigo-700 border-indigo-200'],
                                'shipped' => ['Dikirim', 'bg-cyan-100 text-cyan-700 border-cyan-200'],
                                'completed' => ['Selesai', 'bg-green-100 text-green-700 border-green-200'],
                                'cancelled' => ['Dibatalkan', 'bg-red-100 text-red-700 border-red-200']
                            ];
                            $st_key = $t->status ?? 'pending';
                            $st_label = isset($status_badge[$st_key]) ? $status_badge[$st_key][0] : ucfirst($st_key);
                            $st_color = isset($status_badge[$st_key]) ? $status_badge[$st_key][1] : 'bg-gray-100 text-gray-700 border-gray-200';
                        ?>
                        <span class="px-3 py-1 rounded-full text-xs font-bold border <?= $st_color ?> inline-flex items-center gap-1.5 shadow-sm">
                            <span class="w-1.5 h-1.5 rounded-full currentColor bg-current"></span> <?= $st_label ?>
                        </span>
                    </td>
                    <td class="px-6 py-4 text-right">
                        <span class="font-display font-black text-gray-900 text-lg">Rp <?= number_format($t->total, 0, ',', '.') ?></span>
                    </td>
                    <td class="px-6 py-4 text-center">
                        <a href="<?= site_url('admin/transactions/view/'.$t->id) ?>" class="inline-flex items-center justify-center gap-1 border border-gray-200 bg-white hover:bg-gray-50 hover:border-primary-300 hover:text-primary-600 text-gray-600 px-3 py-2 rounded-lg font-semibold transition-all shadow-sm group-hover:bg-primary-600 group-hover:text-white group-hover:border-primary-600">
                            Detail <span class="iconify" data-icon="lucide:arrow-right text-xs"></span>
                        </a>
                    </td>
                </tr>
                <?php endforeach; ?>
                <?php if(empty($transactions)): ?>
                <tr id="emptyStateRow" style="display:none;">
                    <td colspan="5" class="py-16 text-center">
                        <div class="flex flex-col items-center justify-center">
                            <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mb-3">
                                <span class="iconify text-3xl text-gray-400" data-icon="lucide:file-search"></span>
                            </div>
                            <p class="text-gray-500 font-medium">Data transaksi tidak ditemukan</p>
                            <p class="text-xs text-gray-400 mt-1">Coba sesuaikan filter pencarian di tabel atas</p>
                        </div>
                    </td>
                </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
    
    <!-- Table Footer / Pagination -->
    <div id="paginationContainer" class="px-6 py-4 border-t border-gray-200 flex flex-col md:flex-row items-center justify-between bg-white text-sm"></div>
</div>

<script>
let rowsPerPage = 10;
let currentPage = 1;
const allRows = Array.from(document.querySelectorAll('.tx-row'));

function renderPagination(totalRows, page) {
    const container = document.getElementById('paginationContainer');
    const totalPages = Math.ceil(totalRows / rowsPerPage);
    
    if (totalRows === 0) {
        container.innerHTML = '<span class="text-sm font-medium text-gray-500">Menampilkan 0 data</span><div></div>';
        return;
    }
    
    const startIdx = (page - 1) * rowsPerPage + 1;
    const endIdx = Math.min(page * rowsPerPage, totalRows);
    
    let html = '<span class="text-sm font-medium text-gray-600">Menampilkan <span class="font-bold text-gray-900">' + startIdx + ' - ' + endIdx + '</span> dari <span class="font-bold text-gray-900">' + totalRows + '</span> transaksi</span>';
    
    if (totalPages > 1) {
        html += '<div class="flex items-center gap-1 mt-4 md:mt-0">';
        html += `<button onclick="goToPage(${page - 1})" class="px-3 py-1.5 text-sm border border-gray-200 rounded-lg font-medium hover:bg-gray-50 hover:text-primary-600 transition ${page === 1 ? 'opacity-50 cursor-not-allowed bg-gray-50 text-gray-400 hover:text-gray-400' : ''}" ${page === 1 ? 'disabled' : ''}>Prev</button>`;
        
        for (let i = 1; i <= totalPages; i++) {
            if (i === 1 || i === totalPages || (i >= page - 1 && i <= page + 1)) {
                html += `<button onclick="goToPage(${i})" class="px-3.5 py-1.5 text-sm border rounded-lg font-semibold transition ${i === page ? 'bg-primary-600 text-white border-primary-600 shadow-sm shadow-primary-200/50' : 'border-gray-200 hover:bg-gray-50 text-gray-600 hover:text-primary-600'}">${i}</button>`;
            } else if (i === page - 2 || i === page + 2) {
                html += `<span class="px-2 text-gray-400">...</span>`;
            }
        }
        
        html += `<button onclick="goToPage(${page + 1})" class="px-3 py-1.5 text-sm border border-gray-200 rounded-lg font-medium hover:bg-gray-50 hover:text-primary-600 transition ${page === totalPages ? 'opacity-50 cursor-not-allowed bg-gray-50 text-gray-400 hover:text-gray-400' : ''}" ${page === totalPages ? 'disabled' : ''}>Next</button>`;
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
    const type = document.getElementById('filterType').value;
    const status = document.getElementById('filterStatus').value;
    const date = document.getElementById('filterDate').value;
    
    let filtered = [];
    allRows.forEach(row => {
        const mt = !type || row.dataset.type === type;
        const ms = !status || row.dataset.status === status;
        const md = !date || row.dataset.date === date;
        const mq = !q || row.dataset.invoice.includes(q) || row.dataset.nama.includes(q);
        
        if (mt && ms && md && mq) {
            filtered.push(row);
        } else {
            row.style.display = 'none';
        }
    });
    
    const emptyState = document.getElementById('emptyStateRow');
    if(emptyState) emptyState.style.display = filtered.length === 0 ? '' : 'none';
    
    const start = (currentPage - 1) * rowsPerPage;
    const end = start + rowsPerPage;
    
    filtered.forEach((row, index) => {
        row.style.display = (index >= start && index < end) ? '' : 'none';
    });
    
    renderPagination(filtered.length, currentPage);
}

document.getElementById('searchInput')?.addEventListener('input', () => { currentPage = 1; applyFilters(); });
document.getElementById('filterType')?.addEventListener('change', () => { currentPage = 1; applyFilters(); });
document.getElementById('filterStatus')?.addEventListener('change', () => { currentPage = 1; applyFilters(); });
document.getElementById('filterDate')?.addEventListener('change', () => { currentPage = 1; applyFilters(); });
document.getElementById('filterLimit')?.addEventListener('change', (e) => { rowsPerPage = parseInt(e.target.value); currentPage = 1; applyFilters(); });

applyFilters();
</script>

<?= $this->load->view('admin/layout/footer', [], TRUE) ?>