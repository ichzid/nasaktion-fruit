<?= $this->load->view('admin/layout/header', ['title'=>$title], TRUE) ?>

<div class="flex flex-col md:flex-row items-center justify-between gap-4 mb-6">
    <div class="flex flex-wrap items-center gap-3 w-full md:w-auto">
        <select id="filterLimit" class="border border-gray-300 rounded-lg px-3 py-2 text-sm outline-none text-gray-600 bg-white">
            <option value="10">10 Baris</option>
            <option value="25">25 Baris</option>
            <option value="50">50 Baris</option>
            <option value="100">100 Baris</option>
        </select>
        <div class="relative flex-1 md:flex-none">
            <span class="iconify absolute left-3 top-1/2 -translate-y-1/2 text-gray-400" data-icon="lucide:search"></span>
            <input type="text" id="searchInput" placeholder="Cari nama atau username..." class="w-full md:w-64 border border-gray-300 rounded-lg pl-9 pr-4 py-2 text-sm focus:ring-2 focus:ring-primary-500 outline-none transition">
        </div>
    </div>
    <a href="<?= site_url('admin/users/create') ?>" class="w-full md:w-auto bg-primary-600 hover:bg-primary-700 text-white px-4 py-2 rounded-lg text-sm font-medium flex items-center justify-center gap-2 transition shadow-sm">
        <span class="iconify" data-icon="lucide:plus"></span> Tambah User
    </a>
</div>

<div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden text-sm">
    <div class="overflow-x-auto">
        <table class="w-full text-left">
            <thead class="bg-gray-50 border-b border-gray-200 text-gray-600">
                <tr>
                    <th class="px-4 py-3 font-semibold uppercase text-xs tracking-wider">User</th>
                    <th class="px-4 py-3 font-semibold uppercase text-xs tracking-wider">Username</th>
                    <th class="px-4 py-3 font-semibold uppercase text-xs tracking-wider text-center">Role</th>
                    <th class="px-4 py-3 font-semibold uppercase text-xs tracking-wider text-center">Status</th>
                    <th class="px-4 py-3 font-semibold uppercase text-xs tracking-wider text-center">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                <?php foreach($users as $u): ?>
                <tr class="hover:bg-gray-50/50 transition user-row" data-username="<?= strtolower($u->username) ?>">
                    <td class="px-4 py-3">
                        <div class="flex items-center gap-3">
                            <div class="w-9 h-9 <?= $u->role=='admin'?'bg-purple-100':'bg-blue-100' ?> rounded-full flex items-center justify-center">
                                <span class="<?= $u->role=='admin'?'text-purple-600':'text-blue-600' ?> text-sm font-bold"><?= strtoupper(substr($u->nama_lengkap, 0, 1)) ?></span>
                            </div>
                            <span class="font-bold text-gray-800 user-name"><?= $u->nama_lengkap ?></span>
                        </div>
                    </td>
                    <td class="px-4 py-3 text-gray-500 font-medium"><?= $u->username ?></td>
                    <td class="px-4 py-3 text-center">
                        <span class="px-2.5 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider <?= $u->role=='admin'?'bg-purple-100 text-purple-700 border border-purple-200':'bg-blue-100 text-blue-700 border border-blue-200' ?>"><?= $u->role ?></span>
                    </td>
                    <td class="px-4 py-3 text-center">
                        <span class="px-2.5 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider <?= $u->is_active ? 'bg-green-100 text-green-700 border border-green-200' : 'bg-gray-100 text-gray-500 border border-gray-200' ?>"><?= $u->is_active ? 'Aktif' : 'Nonaktif' ?></span>
                    </td>
                    <td class="px-4 py-3 text-center">
                        <div class="flex items-center justify-center gap-1">
                            <a href="<?= site_url('admin/users/edit/'.$u->id) ?>" class="p-2 text-blue-600 hover:bg-blue-50 rounded-lg transition" title="Edit"><span class="iconify" data-icon="lucide:pencil"></span></a>
                            <?php if($u->id != $this->session->userdata('admin_id')): ?>
                            <a href="<?= site_url('admin/users/delete/'.$u->id) ?>" class="p-2 text-red-600 hover:bg-red-50 rounded-lg transition" onclick="return confirm('Hapus user ini?')" title="Hapus"><span class="iconify" data-icon="lucide:trash-2"></span></a>
                            <?php endif; ?>
                        </div>
                    </td>
                </tr>
                <?php endforeach; ?>
                <?php if(empty($users)): ?>
                <tr id="emptyStateRow" style="display:none;"><td colspan="5" class="text-center py-12 text-gray-400">Data user tidak ditemukan</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
    <div id="paginationContainer" class="px-4 py-4 border-t border-gray-100 flex flex-col md:flex-row items-center justify-between bg-white text-sm"></div>
</div>

<script>
let rowsPerPage = 10;
let currentPage = 1;
const allRows = Array.from(document.querySelectorAll('.user-row'));

function renderPagination(totalRows, page) {
    const container = document.getElementById('paginationContainer');
    const totalPages = Math.ceil(totalRows / rowsPerPage);
    
    if (totalRows === 0) {
        container.innerHTML = '<span class="text-sm font-medium text-gray-500">Menampilkan 0 data</span><div></div>';
        return;
    }
    
    const startIdx = (page - 1) * rowsPerPage + 1;
    const endIdx = Math.min(page * rowsPerPage, totalRows);
    
    let html = '<span class="text-xs font-semibold text-gray-500 uppercase tracking-tight">Menampilkan <span class="text-gray-900">' + startIdx + ' - ' + endIdx + '</span> dari <span class="text-gray-900">' + totalRows + '</span> user</span>';
    
    if (totalPages > 1) {
        html += '<div class="flex items-center gap-1 mt-4 md:mt-0">';
        html += `<button onclick="goToPage(${page - 1})" class="px-3 py-1.5 text-xs border border-gray-200 rounded-lg font-bold hover:bg-primary-50 hover:text-primary-600 transition ${page === 1 ? 'opacity-50 cursor-not-allowed text-gray-300' : 'text-gray-600'}" ${page === 1 ? 'disabled' : ''}>PREV</button>`;
        
        for (let i = 1; i <= totalPages; i++) {
            if (i === 1 || i === totalPages || (i >= page - 1 && i <= page + 1)) {
                html += `<button onclick="goToPage(${i})" class="px-3 py-1.5 text-xs border rounded-lg font-bold transition ${i === page ? 'bg-primary-600 text-white border-primary-600 shadow-sm' : 'border-gray-200 text-gray-600 hover:bg-gray-50 hover:text-primary-600'}">${i}</button>`;
            } else if (i === page - 2 || i === page + 2) {
                html += `<span class="px-1 text-gray-400">...</span>`;
            }
        }
        
        html += `<button onclick="goToPage(${page + 1})" class="px-3 py-1.5 text-xs border border-gray-200 rounded-lg font-bold hover:bg-primary-50 hover:text-primary-600 transition ${page === totalPages ? 'opacity-50 cursor-not-allowed text-gray-300' : 'text-gray-600'}" ${page === totalPages ? 'disabled' : ''}>NEXT</button>`;
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
    
    let filtered = [];
    allRows.forEach(row => {
        const name = row.querySelector('.user-name').textContent.toLowerCase();
        const username = row.dataset.username;
        if (name.includes(q) || username.includes(q)) {
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
document.getElementById('filterLimit')?.addEventListener('change', (e) => { rowsPerPage = parseInt(e.target.value); currentPage = 1; applyFilters(); });

applyFilters();
</script>

<?= $this->load->view('admin/layout/footer', [], TRUE) ?>