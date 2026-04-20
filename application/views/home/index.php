<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Nasaktion Fruit — Toko buah segar premium. Belanja online, kumpulkan poin loyalitas, dan nikmati buah pilihan langsung dari petani terbaik.">
    <title>Nasaktion Fruit — Toko Buah Segar Premium</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://code.iconify.design/3/3.1.0/iconify.min.js"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:ital,wght@0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,400&family=Sora:wght@700;800&display=swap" rel="stylesheet">
    <script>
    tailwind.config = {
        theme: { extend: {
            colors: {
                primary: { 50:'#f0fdf4',100:'#dcfce7',200:'#bbf7d0',300:'#86efac',400:'#4ade80',500:'#22c55e',600:'#16a34a',700:'#15803d',800:'#166534',900:'#14532d' },
                lime: { 400:'#a3e635', 500:'#84cc16' }
            },
            fontFamily: {
                sans: ['Inter','system-ui','sans-serif'],
                display: ['Sora','system-ui','sans-serif']
            },
            animation: {
                'float': 'float 6s ease-in-out infinite',
                'float-delayed': 'float 6s ease-in-out 2s infinite',
                'float-slow': 'float 8s ease-in-out 1s infinite',
                'pulse-slow': 'pulse 4s ease-in-out infinite',
                'fade-in-up': 'fadeInUp 0.8s ease-out forwards',
                'count-up': 'countUp 1.5s ease-out forwards',
            },
            keyframes: {
                float: { '0%,100%': {transform:'translateY(0px)'}, '50%': {transform:'translateY(-18px)'} },
                fadeInUp: { '0%': {opacity:'0',transform:'translateY(30px)'}, '100%': {opacity:'1',transform:'translateY(0)'} },
            }
        }}
    }
    </script>
    <style>
        body { font-family: 'Inter', sans-serif; }
        .font-display { font-family: 'Sora', sans-serif; }
        
        /* Smooth scroll */
        html { scroll-behavior: smooth; }
        
        /* Glassmorphism */
        .glass {
            background: rgba(255,255,255,0.12);
            backdrop-filter: blur(20px);
            border: 1px solid rgba(255,255,255,0.2);
        }
        .glass-light {
            background: rgba(255,255,255,0.75);
            backdrop-filter: blur(16px);
            border: 1px solid rgba(255,255,255,0.4);
        }
        
        /* Gradient text */
        .text-gradient {
            background: linear-gradient(135deg, #16a34a 0%, #22c55e 50%, #84cc16 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }
        
        /* Hero bg mesh */
        .hero-mesh {
            background-color: #f0fdf4;
            background-image:
                radial-gradient(at 20% 30%, rgba(34,197,94,0.18) 0px, transparent 60%),
                radial-gradient(at 80% 10%, rgba(163,230,53,0.15) 0px, transparent 50%),
                radial-gradient(at 60% 80%, rgba(22,163,74,0.12) 0px, transparent 55%),
                radial-gradient(at 10% 80%, rgba(255,237,213,0.3) 0px, transparent 50%);
        }
        
        /* Float animation */
        @keyframes float {
            0%, 100% { transform: translateY(0px) rotate(0deg); }
            33% { transform: translateY(-12px) rotate(2deg); }
            66% { transform: translateY(-6px) rotate(-1deg); }
        }
        .animate-float { animation: float 5s ease-in-out infinite; }
        .animate-float-d1 { animation: float 6s ease-in-out 0.5s infinite; }
        .animate-float-d2 { animation: float 7s ease-in-out 1.2s infinite; }
        .animate-float-d3 { animation: float 5.5s ease-in-out 2s infinite; }
        
        /* Shine on cards */
        .card-shine {
            position: relative;
            overflow: hidden;
        }
        .card-shine::before {
            content: '';
            position: absolute;
            top: -50%;
            left: -75%;
            width: 50%;
            height: 200%;
            background: linear-gradient(to right, transparent 0%, rgba(255,255,255,0.4) 50%, transparent 100%);
            transform: skewX(-20deg);
            transition: left 0.7s ease;
        }
        .card-shine:hover::before { left: 130%; }
        
        /* Nav active link */
        .nav-link { position: relative; }
        .nav-link::after {
            content: '';
            position: absolute;
            bottom: -2px;
            left: 0;
            width: 0;
            height: 2px;
            background: #16a34a;
            transition: width 0.3s ease;
        }
        .nav-link:hover::after { width: 100%; }
        
        /* Segment badge glow */
        .glow-silver { box-shadow: 0 0 20px rgba(148,163,184,0.4); }
        .glow-gold { box-shadow: 0 0 20px rgba(251,191,36,0.5); }
        .glow-platinum { box-shadow: 0 0 20px rgba(167,139,250,0.5); }
        
        /* Scroll reveal utility */
        .reveal { opacity: 0; transform: translateY(30px); transition: all 0.8s ease; }
        .reveal.visible { opacity: 1; transform: translateY(0); }
        
        /* Product card */
        .product-card {
            transition: all 0.35s cubic-bezier(0.34,1.56,0.64,1);
        }
        .product-card:hover {
            transform: translateY(-8px) scale(1.02);
            box-shadow: 0 20px 40px rgba(22,163,74,0.15);
        }
        
        /* Blob shape */
        .blob {
            border-radius: 60% 40% 30% 70% / 60% 30% 70% 40%;
        }
        
        /* Number counter */
        .counter-number {
            font-variant-numeric: tabular-nums;
        }

        /* CTA wave */
        .wave-top {
            position: absolute;
            top: -2px;
            left: 0;
            width: 100%;
            overflow: hidden;
            line-height: 0;
        }
    </style>
</head>
<body class="bg-white overflow-x-hidden">

    <!-- ===== NAVBAR ===== -->
    <nav id="navbar" class="fixed top-0 w-full z-50 transition-all duration-300" style="background:rgba(240,253,244,0.7);backdrop-filter:blur(20px);border-bottom:1px solid rgba(22,163,74,0.1);">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 py-3.5 flex items-center justify-between">
            <a href="<?= site_url() ?>" class="flex items-center gap-2.5 group">
                <div class="w-9 h-9 bg-gradient-to-br from-primary-500 to-primary-700 rounded-xl flex items-center justify-center shadow-md group-hover:scale-110 transition-transform">
                    <span class="text-lg">🍎</span>
                </div>
                <span class="font-display font-bold text-lg text-gray-800">Nasaktion<span class="text-primary-600">Fruit</span></span>
            </a>
            
            <div class="hidden md:flex items-center gap-8">
                <a href="#products" class="nav-link text-sm text-gray-600 hover:text-primary-700 font-medium transition-colors">Produk</a>
                <a href="#membership" class="nav-link text-sm text-gray-600 hover:text-primary-700 font-medium transition-colors">Membership</a>
                <a href="#about" class="nav-link text-sm text-gray-600 hover:text-primary-700 font-medium transition-colors">Tentang</a>
            </div>
            
            <div class="flex items-center gap-3">
                <a href="<?= site_url('customer/auth/login') ?>" id="nav-cta" class="bg-primary-600 hover:bg-primary-700 text-white px-5 py-2 rounded-xl text-sm font-semibold transition-all shadow-md hover:shadow-primary-300 hover:shadow-lg active:scale-95 flex items-center gap-1.5">
                    <span class="iconify text-sm" data-icon="lucide:shopping-bag"></span>
                    Belanja
                </a>
            </div>
        </div>
    </nav>

    <!-- ===== HERO ===== -->
    <?php
        // =====================================================
        // GANTI URL GAMBAR HERO DI SINI
        // Ukuran ideal: minimum 1920x1080px (landscape)
        // =====================================================
        $hero_image_url = 'https://images.unsplash.com/photo-1610832958506-aa56368176cf?w=1920&q=85';
    ?>
    <section class="relative min-h-screen flex items-center overflow-hidden pt-16"
             style="background-image: url('<?= $hero_image_url ?>'); background-size: cover; background-position: center; background-repeat: no-repeat;">

        <!-- Dark gradient overlay -->
        <div class="absolute inset-0 bg-gradient-to-r from-black/70 via-black/50 to-black/20 pointer-events-none"></div>

        <!-- Content -->
        <div class="relative z-10 max-w-7xl mx-auto px-4 sm:px-6 py-24 w-full">
            <div class="max-w-2xl">

                <!-- Badge -->
                <div class="inline-flex items-center gap-2 bg-white/15 backdrop-blur-sm border border-white/30 rounded-full px-4 py-1.5 mb-6">
                    <span class="w-2 h-2 bg-green-400 rounded-full animate-pulse"></span>
                    <span class="text-xs font-semibold text-white tracking-wide uppercase">🌿 100% Fresh From Farm</span>
                </div>

                <!-- Heading -->
                <h1 class="font-display text-5xl md:text-6xl xl:text-7xl font-black text-white leading-[1.05] mb-6 drop-shadow-xl">
                    Buah Segar<br>
                    <span style="background: linear-gradient(135deg,#4ade80,#86efac); -webkit-background-clip:text; -webkit-text-fill-color:transparent; background-clip:text;">Berkualitas</span><br>
                    Premium
                </h1>

                <!-- Subtext -->
                <p class="text-lg text-white/80 mb-8 leading-relaxed max-w-lg">
                    Nikmati buah pilihan langsung dari petani terbaik. Kumpulkan poin loyalitas setiap belanja — online <em>maupun</em> di kasir toko kami.
                </p>

                <!-- CTAs -->
                <div class="flex flex-wrap items-center gap-4 mb-10">
                    <a href="<?= site_url('customer/auth/login') ?>"
                       class="group inline-flex items-center gap-2.5 bg-primary-600 hover:bg-primary-500 text-white px-7 py-4 rounded-2xl font-semibold text-base transition-all shadow-xl hover:shadow-primary-500/40 hover:-translate-y-0.5 active:scale-95">
                        <span class="iconify text-lg" data-icon="lucide:shopping-bag"></span>
                        Belanja Sekarang
                        <span class="iconify text-sm transition-transform group-hover:translate-x-1" data-icon="lucide:arrow-right"></span>
                    </a>
                    <a href="#membership"
                       class="inline-flex items-center gap-2 bg-white/15 backdrop-blur-sm hover:bg-white/25 border border-white/40 text-white px-7 py-4 rounded-2xl font-semibold text-base transition-all">
                        <span class="iconify" data-icon="lucide:star"></span>
                        Program Member
                    </a>
                </div>

                <!-- Trust indicators -->
                <div class="flex flex-wrap items-center gap-6">
                    <div class="flex items-center gap-2">
                        <div class="flex -space-x-2">
                            <div class="w-8 h-8 rounded-full bg-orange-400 border-2 border-white flex items-center justify-center text-xs text-white font-bold">A</div>
                            <div class="w-8 h-8 rounded-full bg-blue-400 border-2 border-white flex items-center justify-center text-xs text-white font-bold">B</div>
                            <div class="w-8 h-8 rounded-full bg-purple-400 border-2 border-white flex items-center justify-center text-xs text-white font-bold">C</div>
                        </div>
                        <span class="text-sm text-white/80 font-medium">500+ Pelanggan Puas</span>
                    </div>
                    <div class="flex items-center gap-1.5 text-sm text-white/80 font-medium">
                        <div class="flex gap-0.5">
                            <?php for($i=0;$i<5;$i++): ?>
                            <span class="text-yellow-400">★</span>
                            <?php endfor; ?>
                        </div>
                        4.9 / 5.0
                    </div>
                </div>

            </div>
        </div>

        <!-- Floating stat cards (bottom right) -->
        <div class="absolute bottom-12 right-6 lg:right-16 z-10 flex flex-col gap-3 hidden md:flex">
            <div class="glass rounded-2xl px-4 py-3 shadow-xl animate-float-d1">
                <div class="flex items-center gap-2">
                    <span class="text-2xl">⭐</span>
                    <div>
                        <p class="text-xs text-white/70 font-medium">Poin Loyalitas</p>
                        <p class="text-sm font-bold text-white">Tiap Rp 10.000 = 1 Poin</p>
                    </div>
                </div>
            </div>
            <div class="glass rounded-2xl px-4 py-3 shadow-xl animate-float-d2">
                <div class="flex items-center gap-2">
                    <span class="text-2xl">🚀</span>
                    <div>
                        <p class="text-xs text-white/70 font-medium">Status Member</p>
                        <p class="text-sm font-bold text-white">Baru → Silver → Platinum</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Bottom wave -->
        <div class="absolute bottom-0 left-0 w-full overflow-hidden leading-none pointer-events-none">
            <svg viewBox="0 0 1440 80" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M0,40 C360,80 1080,0 1440,40 L1440,80 L0,80 Z" fill="white"/>
            </svg>
        </div>
    </section>


    <!-- ===== STATS ===== -->
    <section class="py-12 bg-white">
        <div class="max-w-5xl mx-auto px-4">
            <div class="grid grid-cols-2 md:grid-cols-4 gap-6 reveal">
                <?php
                $stats = [
                    ['icon'=>'🍎','number'=>'50+','label'=>'Jenis Buah'],
                    ['icon'=>'👥','number'=>'500+','label'=>'Member Aktif'],
                    ['icon'=>'⭐','number'=>'4.9','label'=>'Rating Kepuasan'],
                    ['icon'=>'📦','number'=>'1000+','label'=>'Order Selesai'],
                ];
                foreach($stats as $s):
                ?>
                <div class="text-center p-6 rounded-2xl border border-gray-100 hover:border-primary-200 hover:bg-primary-50/50 transition-all group">
                    <div class="text-3xl mb-2 group-hover:scale-110 transition-transform"><?= $s['icon'] ?></div>
                    <div class="font-display font-black text-3xl text-gray-900 mb-1"><?= $s['number'] ?></div>
                    <div class="text-sm text-gray-500 font-medium"><?= $s['label'] ?></div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
    </section>

    <!-- ===== WHY US ===== -->
    <section class="py-20 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6">
            <div class="text-center mb-14 reveal">
                <span class="inline-block bg-primary-100 text-primary-700 text-xs font-bold uppercase tracking-widest px-4 py-1.5 rounded-full mb-4">Keunggulan Kami</span>
                <h2 class="font-display text-4xl font-black text-gray-900 mb-3">Kenapa Nasaktion Fruit?</h2>
                <p class="text-gray-500 max-w-lg mx-auto">Kami hadir untuk menghadirkan pengalaman berbelanja buah yang menyenangkan dan terpercaya</p>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <?php
                $features = [
                    ['icon'=>'🌿','title'=>'Segar Langsung Petani','desc'=>'Buah dipetik pada waktu yang tepat, langsung dari kebun pilihan di seluruh Indonesia. Kesegaran terjamin sampai ke tangan Anda.','color'=>'from-green-400 to-primary-500','bg'=>'bg-green-50','border'=>'border-green-200'],
                    ['icon'=>'🏆','title'=>'Kualitas Premium Terstandar','desc'=>'Setiap buah melewati seleksi ketat. Hanya yang terbaik yang sampai ke meja makan Anda.','color'=>'from-yellow-400 to-orange-500','bg'=>'bg-yellow-50','border'=>'border-yellow-200'],
                    ['icon'=>'💎','title'=>'Poin Loyalitas Terintegrasi','desc'=>'Belanja di toko maupun online, poinmu tetap terhitung. Nikmati reward eksklusif setiap bulannya.','color'=>'from-purple-400 to-blue-500','bg'=>'bg-purple-50','border'=>'border-purple-200'],
                ];
                foreach($features as $f):
                ?>
                <div class="reveal card-shine group bg-white rounded-2xl p-8 border border-gray-100 hover:border-gray-200 shadow-sm hover:shadow-xl transition-all duration-300">
                    <div class="w-14 h-14 rounded-2xl bg-gradient-to-br <?= $f['color'] ?> flex items-center justify-center text-2xl mb-5 shadow-lg group-hover:scale-110 transition-transform">
                        <?= $f['icon'] ?>
                    </div>
                    <h3 class="font-display font-bold text-gray-900 text-lg mb-2"><?= $f['title'] ?></h3>
                    <p class="text-sm text-gray-500 leading-relaxed"><?= $f['desc'] ?></p>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
    </section>

    <!-- ===== PRODUCTS ===== -->
    <section id="products" class="py-20" style="background: linear-gradient(180deg, #f8fffe 0%, #f0fdf4 100%);">
        <div class="max-w-7xl mx-auto px-4 sm:px-6">
            <div class="flex flex-col md:flex-row md:items-end justify-between mb-12 reveal gap-4">
                <div>
                    <span class="inline-block bg-primary-100 text-primary-700 text-xs font-bold uppercase tracking-widest px-4 py-1.5 rounded-full mb-3">Pilihan Hari Ini</span>
                    <h2 class="font-display text-4xl font-black text-gray-900">Produk Unggulan</h2>
                    <p class="text-gray-500 mt-1">Buah terpopuler yang paling banyak diminati pelanggan</p>
                </div>
                <a href="<?= site_url('customer/auth/login') ?>" class="inline-flex items-center gap-2 text-primary-600 hover:text-primary-800 font-semibold text-sm transition-colors shrink-0">
                    Lihat Semua <span class="iconify" data-icon="lucide:arrow-right"></span>
                </a>
            </div>
            
            <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-5">
                <?php foreach($featured_products as $p): ?>
                <a href="<?= site_url('customer/auth/login') ?>" class="product-card bg-white rounded-2xl border border-gray-100 overflow-hidden group shadow-sm">
                    <div class="relative w-full h-44 bg-gradient-to-br from-primary-50 to-primary-100 flex items-center justify-center overflow-hidden">
                        <?php if($p->foto): ?>
                        <img src="<?= base_url('uploads/products/'.$p->foto) ?>" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500" alt="<?= $p->nama_buah ?>">
                        <?php else: ?>
                        <span class="text-6xl group-hover:scale-110 transition-transform duration-300">🍎</span>
                        <?php endif; ?>
                        <div class="absolute top-2.5 left-2.5">
                            <span class="text-xs px-2.5 py-1 rounded-full font-semibold <?= $p->jenis=='Impor' ? 'bg-blue-100 text-blue-700' : 'bg-green-100 text-green-700' ?>">
                                <?= $p->jenis ?>
                            </span>
                        </div>
                        <?php if(!empty($p->stok) && $p->stok < 5): ?>
                        <div class="absolute top-2.5 right-2.5">
                            <span class="text-xs px-2 py-1 rounded-full font-semibold bg-red-100 text-red-600">Sisa <?= $p->stok ?></span>
                        </div>
                        <?php endif; ?>
                    </div>
                    <div class="p-4">
                        <h3 class="font-semibold text-gray-800 text-sm mb-0.5 truncate"><?= $p->nama_buah ?></h3>
                        <p class="text-xs text-gray-400 mb-2"><?= $p->satuan ?? 'per kg' ?></p>
                        <div class="flex items-center justify-between">
                            <p class="font-bold text-primary-600 text-base">Rp <?= number_format($p->harga_jual,0,',','.') ?></p>
                            <div class="w-8 h-8 bg-primary-600 rounded-xl flex items-center justify-center group-hover:bg-primary-700 transition-colors shadow-sm">
                                <span class="iconify text-white text-sm" data-icon="lucide:plus"></span>
                            </div>
                        </div>
                    </div>
                </a>
                <?php endforeach; ?>
                <?php if(empty($featured_products)): ?>
                <div class="col-span-4 text-center py-16">
                    <span class="text-5xl mb-3 block">🌱</span>
                    <p class="text-gray-400 font-medium">Produk segera hadir!</p>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </section>

    <!-- ===== MEMBERSHIP ===== -->
    <section id="membership" class="py-24 bg-white relative overflow-hidden">
        <div class="absolute inset-0 opacity-[0.03]" style="background-image: radial-gradient(circle, #16a34a 1px, transparent 1px); background-size: 30px 30px;"></div>
        <div class="max-w-7xl mx-auto px-4 sm:px-6 relative z-10">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-16 items-center">
                <!-- Left -->
                <div class="reveal">
                    <span class="inline-block bg-primary-100 text-primary-700 text-xs font-bold uppercase tracking-widest px-4 py-1.5 rounded-full mb-4">Program Membership</span>
                    <h2 class="font-display text-4xl font-black text-gray-900 mb-4">Belanja Lebih,<br>Dapat Lebih! 🎁</h2>
                    <p class="text-gray-500 mb-8 leading-relaxed">Daftar sebagai member dan nikmati ekosistem reward yang terintegrasi antara toko fisik dan belanja online.</p>
                    
                    <div class="space-y-4">
                        <?php
                        $benefits = [
                            ['icon'=>'lucide:star','title'=>'Poin Loyalitas Otomatis','desc'=>'Setiap Rp 10.000 = 1 Poin. Berlaku di kasir toko dan website.','color'=>'text-yellow-500'],
                            ['icon'=>'lucide:ticket-percent','title'=>'Voucher Diskon Eksklusif','desc'=>'Dapatkan voucher promo khusus pelanggan setia setiap bulan.','color'=>'text-primary-600'],
                            ['icon'=>'lucide:trending-up','title'=>'Naik Level Otomatis','desc'=>'Semakin banyak belanja, semakin tinggi segmen dan keuntungan Anda.','color'=>'text-purple-600'],
                            ['icon'=>'lucide:smartphone','title'=>'Sinkron Online & Offline','desc'=>'Poin dan riwayat belanja di toko fisik langsung tercatat di akun online Anda.','color'=>'text-blue-600'],
                        ];
                        foreach($benefits as $b):
                        ?>
                        <div class="flex items-start gap-4 p-4 rounded-xl hover:bg-gray-50 transition-colors group">
                            <div class="w-10 h-10 rounded-xl bg-gray-100 group-hover:bg-primary-100 flex items-center justify-center flex-shrink-0 transition-colors">
                                <span class="iconify <?= $b['color'] ?> text-xl" data-icon="<?= $b['icon'] ?>"></span>
                            </div>
                            <div>
                                <h4 class="font-semibold text-gray-800 mb-0.5"><?= $b['title'] ?></h4>
                                <p class="text-sm text-gray-500"><?= $b['desc'] ?></p>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </div>
                    
                    <a href="<?= site_url('customer/auth/login') ?>" class="mt-8 inline-flex items-center gap-2.5 bg-primary-600 hover:bg-primary-700 text-white px-7 py-4 rounded-2xl font-semibold text-sm transition-all shadow-lg shadow-primary-300 hover:shadow-xl hover:-translate-y-0.5 active:scale-95">
                        <span class="iconify" data-icon="lucide:user-plus"></span>
                        Daftar Member Gratis
                    </a>
                </div>
                
                <!-- Right: Segment cards -->
                <div class="reveal grid grid-cols-2 gap-4">
                    <div class="glow-silver bg-gradient-to-br from-slate-50 to-slate-100 rounded-2xl p-6 border border-slate-200 text-center hover:-translate-y-1 transition-transform">
                        <div class="text-5xl mb-3">🥈</div>
                        <h3 class="font-display font-bold text-slate-700 text-lg mb-1">Silver</h3>
                        <p class="text-xs text-slate-500 mb-3">3+ transaksi atau<br>Rp 500K+ belanja</p>
                        <div class="bg-slate-200 text-slate-700 text-xs font-semibold px-3 py-1 rounded-full inline-block">Bonus 5% Voucher</div>
                    </div>
                    <div class="glow-gold bg-gradient-to-br from-yellow-50 to-amber-100 rounded-2xl p-6 border border-yellow-200 text-center hover:-translate-y-1 transition-transform">
                        <div class="text-5xl mb-3">🥇</div>
                        <h3 class="font-display font-bold text-yellow-700 text-lg mb-1">Gold</h3>
                        <p class="text-xs text-yellow-600 mb-3">6+ transaksi atau<br>Rp 2 Juta+ belanja</p>
                        <div class="bg-yellow-200 text-yellow-800 text-xs font-semibold px-3 py-1 rounded-full inline-block">Bonus 10% Voucher</div>
                    </div>
                    <div class="glow-platinum col-span-2 bg-gradient-to-br from-purple-50 to-indigo-100 rounded-2xl p-6 border border-purple-200 text-center hover:-translate-y-1 transition-transform relative overflow-hidden">
                        <div class="absolute -top-4 -right-4 w-20 h-20 bg-purple-200 opacity-30 rounded-full blur-xl"></div>
                        <div class="relative z-10">
                            <div class="text-5xl mb-2">💎</div>
                            <h3 class="font-display font-bold text-purple-800 text-xl mb-1">Platinum</h3>
                            <p class="text-xs text-purple-600 mb-3">10+ transaksi atau Rp 5 Juta+ belanja</p>
                            <div class="flex flex-wrap gap-2 justify-center">
                                <span class="bg-purple-200 text-purple-800 text-xs font-semibold px-3 py-1 rounded-full">Voucher 15%</span>
                                <span class="bg-purple-200 text-purple-800 text-xs font-semibold px-3 py-1 rounded-full">Priority Service</span>
                                <span class="bg-purple-200 text-purple-800 text-xs font-semibold px-3 py-1 rounded-full">Early Access</span>
                            </div>
                        </div>
                    </div>
                    <div class="col-span-2 bg-gradient-to-r from-primary-600 to-primary-700 rounded-2xl p-5 text-center text-white">
                        <p class="text-sm font-medium text-primary-100 mb-1">Mulai dari Level</p>
                        <p class="font-display font-black text-2xl">BARU</p>
                        <p class="text-xs text-primary-200 mt-1">Daftar gratis, langsung aktif!</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- ===== TESTIMONIAL ===== -->
    <section id="about" class="py-20" style="background: linear-gradient(135deg, #14532d 0%, #15803d 50%, #16a34a 100%);">
        <div class="max-w-7xl mx-auto px-4 sm:px-6">
            <div class="text-center mb-12 reveal">
                <span class="inline-block bg-white/20 text-white text-xs font-bold uppercase tracking-widest px-4 py-1.5 rounded-full mb-4">Kata Pelanggan</span>
                <h2 class="font-display text-4xl font-black text-white mb-3">Mereka Sudah Merasakan</h2>
                <p class="text-primary-200">Ribuan pelanggan puas berbelanja di Nasaktion Fruit</p>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 reveal">
                <?php
                $testimonials = [
                    ['name'=>'Budi Santoso','role'=>'Member Silver','avatar'=>'B','text'=>'"Buahnya segar banget! Sering belanja langsung di toko, terus daftarin akun online. Poin langsung ke-sync dan dapat voucher diskon. Recommended banget!"','stars'=>5],
                    ['name'=>'Siti Rahayu','role'=>'Member Gold ⭐','avatar'=>'S','text'=>'"Yang bikin saya loyal itu program poin-nya. Udah 3 bulan belanja rutin dan sekarang sudah Gold. Buahnya selalu fresh!"','stars'=>5],
                    ['name'=>'Andi Pratama','role'=>'Member Platinum 💎','avatar'=>'A','text'=>'"Nasaktion Fruit terbaik! Deliverinya cepat, packing rapi, dan buah selalu segar. Sudah jadi langganan tetap sejak setahun lalu."','stars'=>5],
                ];
                foreach($testimonials as $t):
                ?>
                <div class="glass rounded-2xl p-6 hover:bg-white/20 transition-colors">
                    <div class="flex gap-0.5 mb-4">
                        <?php for($i=0;$i<$t['stars'];$i++): ?>
                        <span class="text-yellow-400 text-lg">★</span>
                        <?php endfor; ?>
                    </div>
                    <p class="text-white/90 text-sm leading-relaxed mb-5"><?= $t['text'] ?></p>
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-full bg-white/20 flex items-center justify-center text-white font-bold text-sm flex-shrink-0">
                            <?= $t['avatar'] ?>
                        </div>
                        <div>
                            <p class="text-white font-semibold text-sm"><?= $t['name'] ?></p>
                            <p class="text-primary-200 text-xs"><?= $t['role'] ?></p>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
    </section>

    <!-- ===== CTA FINAL ===== -->
    <section class="py-24 bg-white relative overflow-hidden">
        <div class="absolute inset-0 hero-mesh opacity-60 pointer-events-none"></div>
        <div class="max-w-4xl mx-auto px-4 sm:px-6 text-center relative z-10 reveal">
            <span class="text-5xl block mb-4">🛒</span>
            <h2 class="font-display text-4xl md:text-5xl font-black text-gray-900 mb-4">Siap Belanja<br>Buah Segar?</h2>
            <p class="text-gray-500 text-lg mb-8 max-w-xl mx-auto">Daftar gratis sekarang, kumpulkan poin, dan nikmati reward eksklusif. Member lama toko kami? Gunakan nomor HP yang sama untuk langsung terhubung!</p>
            <div class="flex flex-col sm:flex-row items-center justify-center gap-4">
                <a href="<?= site_url('customer/auth/login') ?>" class="group w-full sm:w-auto inline-flex items-center justify-center gap-2.5 bg-primary-600 hover:bg-primary-700 text-white px-8 py-4 rounded-2xl font-semibold text-base transition-all shadow-xl shadow-primary-200 hover:shadow-2xl hover:-translate-y-1 active:scale-95">
                    <span class="iconify text-lg" data-icon="lucide:user-plus"></span>
                    Daftar / Masuk Sekarang
                </a>
                <a href="#products" class="w-full sm:w-auto inline-flex items-center justify-center gap-2 border-2 border-gray-200 text-gray-700 hover:border-primary-400 hover:text-primary-700 px-8 py-4 rounded-2xl font-semibold text-base transition-all">
                    <span class="iconify" data-icon="lucide:package-search"></span>
                    Lihat Produk Dulu
                </a>
            </div>
        </div>
    </section>

    <!-- ===== FOOTER ===== -->
    <footer class="bg-gray-950 text-gray-400 pt-16 pb-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-10 mb-12">
                <div class="md:col-span-2">
                    <div class="flex items-center gap-2.5 mb-4">
                        <div class="w-9 h-9 bg-gradient-to-br from-primary-500 to-primary-700 rounded-xl flex items-center justify-center">
                            <span class="text-lg">🍎</span>
                        </div>
                        <span class="font-display font-bold text-lg text-white">Nasaktion<span class="text-primary-400">Fruit</span></span>
                    </div>
                    <p class="text-sm leading-relaxed max-w-sm">Toko buah segar berkualitas premium dengan program loyalitas terintegrasi antara online dan offline. Kesegaran terjamin, reward tidak pernah berhenti.</p>
                    <div class="flex gap-3 mt-5">
                        <a href="#" class="w-9 h-9 bg-gray-800 hover:bg-primary-700 rounded-xl flex items-center justify-center transition-colors">
                            <span class="iconify text-white text-sm" data-icon="lucide:instagram"></span>
                        </a>
                        <a href="#" class="w-9 h-9 bg-gray-800 hover:bg-green-700 rounded-xl flex items-center justify-center transition-colors">
                            <span class="iconify text-white text-sm" data-icon="lucide:message-circle"></span>
                        </a>
                    </div>
                </div>
                <div>
                    <h4 class="font-semibold text-white mb-4">Navigasi</h4>
                    <ul class="space-y-2.5 text-sm">
                        <li><a href="<?= site_url() ?>" class="hover:text-white hover:pl-1 transition-all">Beranda</a></li>
                        <li><a href="#products" class="hover:text-white hover:pl-1 transition-all">Produk Kami</a></li>
                        <li><a href="#membership" class="hover:text-white hover:pl-1 transition-all">Program Member</a></li>
                        <li><a href="<?= site_url('customer/auth/login') ?>" class="hover:text-white hover:pl-1 transition-all">Login / Daftar</a></li>
                        <li><a href="<?= site_url('admin/login') ?>" class="hover:text-white hover:pl-1 transition-all text-gray-600">Admin</a></li>
                    </ul>
                </div>
                <div>
                    <h4 class="font-semibold text-white mb-4">Kontak</h4>
                    <ul class="space-y-3 text-sm">
                        <li class="flex items-start gap-2.5">
                            <span class="iconify mt-0.5 flex-shrink-0" data-icon="lucide:map-pin"></span>
                            <span>Jl. Buah Segar No. 1,<br>Kota Nasaktion</span>
                        </li>
                        <li class="flex items-center gap-2.5">
                            <span class="iconify flex-shrink-0" data-icon="lucide:phone"></span>
                            <a href="https://wa.me/6281234567890" class="hover:text-white transition-colors">+62 812-3456-7890</a>
                        </li>
                        <li class="flex items-center gap-2.5">
                            <span class="iconify flex-shrink-0" data-icon="lucide:clock"></span>
                            <span>Sen–Sab, 07.00–20.00</span>
                        </li>
                    </ul>
                </div>
            </div>
            <div class="border-t border-gray-800 pt-6 flex flex-col sm:flex-row items-center justify-between gap-3">
                <p class="text-sm">&copy; <?= date('Y') ?> Nasaktion Fruit. Hak cipta dilindungi.</p>
                <p class="text-xs text-gray-600">Dibuat dengan ❤️ untuk pelanggan setia kami</p>
            </div>
        </div>
    </footer>

    <!-- ===== SCRIPTS ===== -->
    <script>
        // Navbar scroll effect
        const navbar = document.getElementById('navbar');
        window.addEventListener('scroll', () => {
            if(window.scrollY > 60) {
                navbar.style.background = 'rgba(255,255,255,0.95)';
                navbar.style.boxShadow = '0 1px 20px rgba(0,0,0,0.08)';
            } else {
                navbar.style.background = 'rgba(240,253,244,0.7)';
                navbar.style.boxShadow = 'none';
            }
        });

        // Scroll reveal
        const observer = new IntersectionObserver((entries) => {
            entries.forEach((entry, i) => {
                if(entry.isIntersecting) {
                    setTimeout(() => {
                        entry.target.classList.add('visible');
                    }, 100);
                }
            });
        }, { threshold: 0.12 });

        document.querySelectorAll('.reveal').forEach(el => observer.observe(el));
    </script>
</body>
</html>