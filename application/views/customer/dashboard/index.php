<?= $this->load->view('customer/layout/header', ['title'=>'Dashboard'], TRUE) ?>

<?php
    $nama      = $this->session->userdata('customer_nama');
    $poin      = $loyalty_points ?? 0;
    $trx       = $total_transactions ?? 0;
    $seg       = $segment ?? 'Baru';
    $hour      = (int)date('H');
    $greeting  = $hour < 12 ? 'Selamat Pagi' : ($hour < 17 ? 'Selamat Siang' : 'Selamat Malam');
    
    $seg_colors = [
        'Baru'     => ['bg'=>'bg-gray-100',    'text'=>'text-gray-600',   'icon'=>'🌱'],
        'Silver'   => ['bg'=>'bg-slate-100',   'text'=>'text-slate-600',  'icon'=>'🥈'],
        'Gold'     => ['bg'=>'bg-yellow-100',  'text'=>'text-yellow-700', 'icon'=>'🥇'],
        'Platinum' => ['bg'=>'bg-purple-100',  'text'=>'text-purple-700', 'icon'=>'💎'],
    ];
    $sc = $seg_colors[$seg] ?? $seg_colors['Baru'];
?>

<!-- ===== WELCOME BANNER ===== -->
<div class="relative bg-gradient-to-br from-primary-700 via-primary-600 to-primary-500 rounded-3xl p-6 sm:p-8 mb-8 overflow-hidden text-white shadow-xl shadow-primary-200">
    <!-- Decorative blobs -->
    <div class="absolute -top-10 -right-10 w-60 h-60 bg-white opacity-5 rounded-full pointer-events-none"></div>
    <div class="absolute bottom-0 right-0 w-40 h-40 bg-primary-400 opacity-20 rounded-full blur-2xl pointer-events-none"></div>

    <div class="relative z-10 flex flex-col sm:flex-row sm:items-center justify-between gap-6">
        <div>
            <p class="text-primary-200 text-sm font-medium mb-1"><?= $greeting ?> 👋</p>
            <h2 class="font-display font-black text-3xl sm:text-4xl mb-2"><?= htmlspecialchars($nama) ?>!</h2>
            <p class="text-primary-100 text-sm">Selamat datang kembali di Nasaktion Fruit</p>
            <div class="mt-4">
                <span class="inline-flex items-center gap-1.5 <?= $sc['bg'] ?> <?= $sc['text'] ?> text-sm font-bold px-3 py-1.5 rounded-xl">
                    <?= $sc['icon'] ?> Member <?= $seg ?>
                </span>
            </div>
        </div>

        <!-- Stats pills -->
        <div class="flex gap-3 sm:flex-col sm:items-end">
            <div class="bg-white/15 backdrop-blur-sm rounded-2xl px-4 py-3 text-center min-w-[100px]">
                <p class="text-2xl font-black font-display"><?= number_format($poin) ?></p>
                <p class="text-xs text-primary-200 font-medium">Poin Loyalitas</p>
            </div>
            <div class="bg-white/15 backdrop-blur-sm rounded-2xl px-4 py-3 text-center min-w-[100px]">
                <p class="text-2xl font-black font-display"><?= $trx ?></p>
                <p class="text-xs text-primary-200 font-medium">Total Transaksi</p>
            </div>
        </div>
    </div>
</div>

<!-- ===== QUICK ACTIONS ===== -->
<div class="grid grid-cols-3 gap-4 mb-8">
    <?php
    $actions = [
        ['href'=>site_url('customer/shop'),       'icon'=>'lucide:shopping-bag',    'label'=>'Belanja',       'bg'=>'bg-primary-50',  'ic'=>'text-primary-600'],
        ['href'=>site_url('customer/cart'),       'icon'=>'lucide:shopping-cart',   'label'=>'Keranjang',     'bg'=>'bg-blue-50',     'ic'=>'text-blue-600'],
        ['href'=>site_url('customer/feedback'),   'icon'=>'lucide:message-square',  'label'=>'Feedback',      'bg'=>'bg-purple-50',   'ic'=>'text-purple-600'],
    ];
    foreach($actions as $a):
    ?>
    <a href="<?= $a['href'] ?>"
       class="card-hover flex flex-col items-center gap-2 <?= $a['bg'] ?> rounded-2xl p-4 border border-transparent hover:border-primary-100 text-center group">
        <div class="w-12 h-12 bg-white rounded-xl flex items-center justify-center shadow-sm group-hover:scale-110 transition-transform">
            <span class="iconify text-2xl <?= $a['ic'] ?>" data-icon="<?= $a['icon'] ?>"></span>
        </div>
        <span class="text-xs font-semibold text-gray-700"><?= $a['label'] ?></span>
    </a>
    <?php endforeach; ?>
</div>

<!-- ===== VOUCHERS ===== -->
<?php if(!empty($vouchers)): ?>
<div class="mb-8">
    <div class="flex items-center justify-between mb-4">
        <h3 class="font-display font-bold text-lg text-gray-900 flex items-center gap-2">
            <span class="iconify text-primary-600" data-icon="lucide:ticket-percent"></span>
            Voucher Tersedia
        </h3>
        <span class="text-xs font-semibold bg-primary-100 text-primary-700 px-2.5 py-1 rounded-full"><?= count($vouchers) ?> voucher</span>
    </div>
    <div class="flex gap-4 overflow-x-auto pb-2 -mx-1 px-1">
        <?php foreach($vouchers as $v): ?>
        <div class="min-w-[220px] flex-shrink-0 relative">
            <!-- Dashed ticket border -->
            <div class="bg-gradient-to-br from-primary-600 to-primary-800 rounded-2xl p-5 text-white shadow-lg shadow-primary-200 overflow-hidden">
                <div class="absolute -top-6 -right-6 w-20 h-20 bg-white opacity-10 rounded-full"></div>
                <p class="text-xs font-bold text-primary-200 uppercase tracking-widest mb-1">Voucher</p>
                <p class="text-2xl font-black font-display">
                    <?= $v->tipe_diskon=='persen' ? $v->nilai_diskon.'%' : 'Rp '.number_format($v->nilai_diskon,0,',','.') ?>
                </p>
                <p class="text-xs text-primary-200 mt-1">Min. Rp <?= number_format($v->min_belanja,0,',','.') ?></p>
                <div class="mt-3 pt-3 border-t border-white/20">
                    <code class="text-sm font-bold tracking-widest"><?= $v->kode_voucher ?></code>
                </div>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
</div>
<?php endif; ?>

<!-- ===== RECOMMENDATIONS ===== -->
<?php if(!empty($recommendations)): ?>
<div class="mb-8">
    <div class="flex items-center justify-between mb-4">
        <h3 class="font-display font-bold text-lg text-gray-900 flex items-center gap-2">
            <span class="iconify text-yellow-500" data-icon="lucide:sparkles"></span>
            Rekomendasi untuk Anda
        </h3>
        <a href="<?= site_url('customer/shop') ?>" class="text-xs text-primary-600 font-semibold hover:underline">Lihat Semua →</a>
    </div>
    <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-4">
        <?php foreach($recommendations as $r): ?>
        <a href="<?= site_url('customer/shop/product/'.$r->id) ?>"
           class="card-hover bg-white rounded-2xl border border-gray-100 overflow-hidden group shadow-sm">
            <div class="w-full h-32 bg-primary-50 flex items-center justify-center overflow-hidden">
                <?php if($r->foto): ?>
                <img src="<?= base_url('uploads/products/'.$r->foto) ?>" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-300" alt="<?= $r->nama_buah ?>">
                <?php else: ?>
                <span class="text-5xl group-hover:scale-110 transition-transform">🍎</span>
                <?php endif; ?>
            </div>
            <div class="p-3">
                <span class="text-[10px] font-semibold px-1.5 py-0.5 rounded <?= $r->jenis=='Impor'?'bg-blue-100 text-blue-700':($r->jenis=='Ekspor'?'bg-purple-100 text-purple-700':'bg-green-100 text-green-700') ?>">
                    <?= $r->jenis ?>
                </span>
                <p class="text-sm font-semibold text-gray-800 mt-1 truncate"><?= $r->nama_buah ?></p>
                <p class="text-sm font-bold text-primary-600 mt-0.5">Rp <?= number_format($r->harga_jual,0,',','.') ?></p>
            </div>
        </a>
        <?php endforeach; ?>
    </div>
</div>
<?php endif; ?>

<!-- ===== RECENT ORDERS ===== -->
<div class="mb-8">
    <div class="flex items-center justify-between mb-4">
        <h3 class="font-display font-bold text-lg text-gray-900 flex items-center gap-2">
            <span class="iconify text-primary-600" data-icon="lucide:receipt"></span>
            Pesanan Terakhir
        </h3>
    </div>
    <?php if(!empty($recent_orders)): ?>
    <div class="space-y-3">
        <?php foreach($recent_orders as $o): ?>
        <div class="bg-white rounded-2xl border border-gray-100 p-4 flex items-center justify-between gap-4 hover:border-primary-200 hover:shadow-sm transition-all group">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 bg-primary-50 rounded-xl flex items-center justify-center flex-shrink-0 group-hover:bg-primary-100 transition-colors">
                    <span class="iconify text-primary-600" data-icon="lucide:package-2"></span>
                </div>
                <div>
                    <p class="text-sm font-bold text-gray-800"><?= $o->invoice_no ?></p>
                    <p class="text-xs text-gray-400"><?= date('d M Y', strtotime($o->tgl)) ?></p>
                </div>
            </div>
            <div class="text-right flex-shrink-0">
                <p class="text-sm font-bold text-gray-900">Rp <?= number_format($o->total,0,',','.') ?></p>
                <span class="text-xs px-2 py-0.5 rounded-full font-semibold <?= $o->status=='completed'?'bg-green-100 text-green-700':'bg-yellow-100 text-yellow-700' ?>">
                    <?= ucfirst($o->status) ?>
                </span>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
    <?php else: ?>
    <div class="bg-white rounded-2xl border border-gray-100 p-12 text-center">
        <span class="text-5xl block mb-3">🛒</span>
        <p class="text-gray-500 font-medium mb-4">Belum ada pesanan</p>
        <a href="<?= site_url('customer/shop') ?>"
           class="inline-flex items-center gap-2 bg-primary-600 hover:bg-primary-700 text-white px-6 py-3 rounded-xl text-sm font-semibold transition-all shadow-md hover:shadow-lg">
            <span class="iconify" data-icon="lucide:shopping-bag"></span>
            Mulai Belanja
        </a>
    </div>
    <?php endif; ?>
</div>

<?= $this->load->view('customer/layout/footer', [], TRUE) ?>