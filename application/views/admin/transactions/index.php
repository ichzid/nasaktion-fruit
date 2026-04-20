<?= $this->load->view('admin/layout/header', ['title'=>$title], TRUE) ?>

<div class="flex items-center justify-between mb-6">
    <div class="flex items-center gap-3">
        <select id="filterLimit" class="border border-gray-300 rounded-lg px-3 py-2 text-sm outline-none text-gray-600">
            <option value="10">10 Baris</option>
            <option value="25">25 Baris</option>
            <option value="50">50 Baris</option>
            <option value="100">100 Baris</option>
        </select>
        <input type="text" id="searchInput" placeholder="Cari invoice/nama..." class="border border-gray-300 rounded-lg px-4 py-2 text-sm focus:ring-2 focus:ring-primary-500 outline-none w-64">
        <select id="filterType" class="border border-gray-300 rounded-lg px-3 py-2 text-sm outline-none">
            <option value="">Semua Jenis</option>
            <option value="Online">Online</option>
            <option value="Offline">Offline</option>
        </select>
        <input type="date" id="filterDate" class="border border-gray-300 rounded-lg px-3 py-2 text-sm outline-none">
    </div>
</div>

<div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">
    <table class="w-full text-sm">
        <thead class="bg-gray-50 border-b border-gray-200">
            <tr>
                <th class="text-left px-4 py-3 font-medium text-gray-600">Invoice</th>
                <th class="text-left px-4 py-3 font-medium text-gray-600">Pelanggan</th>
                <th class="text-center px-4 py-3 font-medium text-gray-600">Jenis</th>
                <th class="text-left px-4 py-3 font-medium text-gray-600">Pembayaran</th>
                <th class="text-right px-4 py-3 font-medium text-gray-600">Total</th>
                <th class="text-left px-4 py-3 font-medium text-gray-600">Tanggal</th>
                <th class="text-center px-4 py-3 font-medium text-gray-600">Status</th>
                <th class="text-center px-4 py-3 font-medium text-gray-600">Aksi</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-100">
            <?php foreach($transactions as $t): ?>
            <tr class="hover:bg-gray-50 tx-row" data-type="<?= $t->jenis_order ?>" data-date="<?= date('Y-m-d', strtotime($t->tgl)) ?>" data-invoice="<?= strtolower($t->invoice_no) ?>" data-nama="<?= strtolower($t->nama ?? 'guest') ?>">
                <td class="px-4 py-3 font-medium text-primary-600"><?= $t->invoice_no ?></td>
                <td class="px-4 py-3 text-gray-700"><?= $t->nama ?? 'Guest' ?></td>
                <td class="px-4 py-3 text-center">
                    <span class="px-2 py-0.5 rounded-full text-xs font-medium <?= $t->jenis_order=='Online'?'bg-blue-100 text-blue-700':'bg-green-100 text-green-700' ?>"><?= $t->jenis_order ?></span>
                </td>
                <td class="px-4 py-3 text-gray-600"><?= ucfirst($t->payment_method ?? 'cash') ?></td>
                <td class="px-4 py-3 text-right font-medium">Rp <?= number_format($t->total, 0, ',', '.') ?></td>
                <td class="px-4 py-3 text-gray-500"><?= date('d M Y H:i', strtotime($t->tgl)) ?></td>
                <td class="px-4 py-3 text-center">
                    <?php 
                        $status_badge = [
                            'pending' => ['Tertunda', 'bg-yellow-100 text-yellow-700'],
                            'paid' => ['Lunas', 'bg-blue-100 text-blue-700'],
                            'verified' => ['Terverifikasi', 'bg-green-100 text-green-700'],
                            'completed' => ['Selesai', 'bg-green-100 text-green-700'],
                            'cancelled' => ['Dibatalkan', 'bg-red-100 text-red-700']
                        ];
                        $st_key = $t->status ?? 'pending';
                        $st_label = isset($status_badge[$st_key]) ? $status_badge[$st_key][0] : ucfirst($st_key);
                        $st_color = isset($status_badge[$st_key]) ? $status_badge[$st_key][1] : 'bg-gray-100 text-gray-700';
                    ?>
                    <span class="px-2 py-0.5 rounded-full text-xs font-medium <?= $st_color ?>"><?= $st_label ?></span>
                </td>
                <td class="px-4 py-3 text-center">
                    <a href="<?= site_url('admin/transactions/view/'.$t->id) ?>" class="text-primary-600 hover:underline text-xs">Detail</a>
                </td>
            </tr>
            <?php endforeach; ?>
            <?php if(empty($transactions)): ?>
            <tr id="emptyStateRow" style="display:none;"><td colspan="8" class="text-center py-8 text-gray-400">Belum ada transaksi atau data tidak ditemukan</td></tr>
            <?php endif; ?>
        </tbody>
    </table>
    
    <div id="paginationContainer" class="px-4 py-3 border-t border-gray-200 flex items-center justify-between bg-gray-50 rounded-b-xl"></div>
</div>

<script>
let rowsPerPage = 10;
let currentPage = 1;
const allRows = Array.from(document.querySelectorAll('.tx-row'));

function renderPagination(totalRows, page) {
    const container = document.getElementById('paginationContainer');
    const totalPages = Math.ceil(totalRows / rowsPerPage);
    
    if (totalRows === 0) {
        container.innerHTML = '<span class="text-sm text-gray-500">Menampilkan 0 data</span><div></div>';
        return;
    }
    
    const startIdx = (page - 1) * rowsPerPage + 1;
    const endIdx = Math.min(page * rowsPerPage, totalRows);
    
    let html = '<span class="text-sm text-gray-500">Menampilkan ' + startIdx + ' - ' + endIdx + ' dari ' + totalRows + ' transaksi</span>';
    
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
    const type = document.getElementById('filterType').value;
    const date = document.getElementById('filterDate').value;
    
    let filtered = [];
    allRows.forEach(row => {
        const mt = !type || row.dataset.type === type;
        const md = !date || row.dataset.date === date;
        const mq = !q || row.dataset.invoice.includes(q) || row.dataset.nama.includes(q);
        
        if (mt && md && mq) {
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
document.getElementById('filterDate')?.addEventListener('change', () => { currentPage = 1; applyFilters(); });
document.getElementById('filterLimit')?.addEventListener('change', (e) => { rowsPerPage = parseInt(e.target.value); currentPage = 1; applyFilters(); });

applyFilters();
</script>

<?= $this->load->view('admin/layout/footer', [], TRUE) ?>