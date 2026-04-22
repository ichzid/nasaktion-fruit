-- =====================================================
-- Database: db_nasaktion
-- CRM Toko Buah Nasaktion Fruit (With Robust Dummy Data)
-- =====================================================

CREATE DATABASE IF NOT EXISTS `db_nasaktion` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE `db_nasaktion`;

-- =====================================================
-- Tabel: admins (Autentikasi Admin & Kasir)
-- =====================================================
CREATE TABLE `admins` (
    `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
    `username` VARCHAR(50) NOT NULL UNIQUE,
    `password` VARCHAR(255) NOT NULL,
    `nama_lengkap` VARCHAR(100) NOT NULL,
    `role` ENUM('admin', 'kasir') NOT NULL DEFAULT 'kasir',
    `is_active` TINYINT(1) NOT NULL DEFAULT 1,
    `created_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO `admins` (`username`, `password`, `nama_lengkap`, `role`) VALUES
('admin', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Administrator', 'admin');

-- =====================================================
-- Tabel: categories (Kategori Buah)
-- =====================================================
CREATE TABLE `categories` (
    `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
    `nama_kategori` VARCHAR(50) NOT NULL,
    `deskripsi` TEXT NULL,
    `created_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO `categories` (`id`, `nama_kategori`, `deskripsi`) VALUES
(1, 'Buah Tropis', 'Buah-buahan segar asli iklim tropis'),
(2, 'Buah Berry', 'Keluarga buah berry yang kaya antioksidan'),
(3, 'Buah Premium (Eksotis)', 'Buah-buahan impor atau organik kualitas premium');

-- =====================================================
-- Tabel: customers (Pelanggan)
-- =====================================================
CREATE TABLE `customers` (
    `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
    `nama` VARCHAR(100) NOT NULL,
    `no_hp` VARCHAR(20) NOT NULL UNIQUE,
    `password` VARCHAR(255) NULL,
    `alamat` TEXT NULL,
    `foto` VARCHAR(255) NULL,
    `tipe_customer` ENUM('Online', 'Offline', 'Unified') NOT NULL DEFAULT 'Offline',
    `point_loyalitas` INT NOT NULL DEFAULT 0,
    `total_belanja` DECIMAL(15,2) NOT NULL DEFAULT 0.00,
    `total_transaksi` INT NOT NULL DEFAULT 0,
    `segment` ENUM('Baru', 'Silver', 'Gold', 'Platinum') NOT NULL DEFAULT 'Baru',
    `last_transaction_at` TIMESTAMP NULL DEFAULT NULL,
    `cart_data` TEXT NULL,
    `created_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    INDEX `idx_segment` (`segment`),
    INDEX `idx_no_hp` (`no_hp`),
    INDEX `idx_tipe` (`tipe_customer`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO `customers` (`id`, `nama`, `no_hp`, `password`, `tipe_customer`, `point_loyalitas`, `total_belanja`, `total_transaksi`, `segment`) VALUES
(1, 'John Doe', '081234567890', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Unified', 1500, 1500000.00, 5, 'Silver'),
(2, 'Sarah Smith', '081987654321', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Unified', 5500, 5500000.00, 12, 'Gold'),
(3, 'Bapak Budi (Toko Rejeki)', '08122334455', NULL, 'Offline', 12000, 12000000.00, 30, 'Platinum'),
(4, 'Andi Wijaya', '085712341234', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Unified', 100, 100000.00, 1, 'Baru');

-- =====================================================
-- Tabel: products (Produk Buah)
-- =====================================================
CREATE TABLE `products` (
    `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
    `category_id` INT UNSIGNED NOT NULL,
    `nama_buah` VARCHAR(100) NOT NULL,
    `jenis` ENUM('Impor', 'Ekspor', 'Lokal') NOT NULL DEFAULT 'Lokal',
    `foto` VARCHAR(255) NULL,
    `deskripsi` TEXT NULL,
    `harga_beli` DECIMAL(15,2) NOT NULL DEFAULT 0.00,
    `harga_jual` DECIMAL(15,2) NOT NULL DEFAULT 0.00,
    `stok` INT NOT NULL DEFAULT 0,
    `satuan` VARCHAR(20) NOT NULL DEFAULT 'kg',
    `is_active` TINYINT(1) NOT NULL DEFAULT 1,
    `created_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    INDEX `idx_category` (`category_id`),
    INDEX `idx_jenis` (`jenis`),
    INDEX `idx_active` (`is_active`),
    CONSTRAINT `fk_products_category` FOREIGN KEY (`category_id`) REFERENCES `categories`(`id`) ON DELETE RESTRICT ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO `products` (`id`, `category_id`, `nama_buah`, `jenis`, `deskripsi`, `harga_beli`, `harga_jual`, `stok`, `satuan`, `is_active`) VALUES
(1, 1, 'Mangga Harum Manis', 'Lokal', 'Mangga Harum Manis kualitas super dengan rasa manis alami tanpa serat kasar. Dipetik langsung dari perkebunan pilihan.', 15000, 25000, 50, 'kg', 1),
(2, 1, 'Pisang Cavendish Sunpride', 'Lokal', 'Pisang Cavendish yang kaya akan kalium. Sangat cocok dinikmati segar atau dipadukan dengan sereal dan oatmeal.', 10000, 18000, 100, 'kg', 1),
(3, 1, 'Rambutan Binjai', 'Lokal', 'Rambutan Binjai asli dengan daging tebal manis dan kulit ngelotok (mudah lepas dari biji), super segar.', 8000, 15000, 40, 'ikat', 1),
(4, 1, 'Nanas Subang (Madu)', 'Lokal', 'Nanas madu asli Subang, ukuran besar dengan kadar air banyak dan rasa manis tanpa efek gatal di lidah.', 7000, 12000, 30, 'buah', 1),
(5, 1, 'Pepaya California', 'Lokal', 'Pepaya jenis California dengan daging kenyal berwarna merah cerah, tidak lembek, dan rasanya manis menyegarkan.', 6000, 10000, 25, 'buah', 1),
(6, 1, 'Salak Pondoh Super', 'Lokal', 'Salak Pondoh asli Sleman yang manis, garing, dan tidak sepat. Kualitas ekspor.', 12000, 20000, 60, 'kg', 1),
(7, 1, 'Durian Musang King', 'Ekspor', 'Raja segala buah. Durian Musang King dengan tekstur creamy tiada tara, manis pahit lezat.', 200000, 350000, 10, 'buah', 1),
(8, 1, 'Manggis Ekspor', 'Ekspor', 'Ratu buah dengan daging putih bersih dan rasa asam manis yang sempurna.', 25000, 45000, 15, 'kg', 1),
(9, 1, 'Semangka Merah Tanpa Biji', 'Lokal', 'Semangka besar ranum tanpa biji, sangat renyah dan berair, cocok pelepas dahaga.', 4000, 8000, 80, 'kg', 1),
(10, 1, 'Melon Sky Rocket', 'Lokal', 'Melon jenis Sky Rocket yang terkenal hijau, lembut, sangat harum dan super manis.', 10000, 18000, 40, 'kg', 1),
(11, 2, 'Strawberry Ciwidey', 'Lokal', 'Strawberry hidroponik segar dari Ciwidey berukuran jumbo dengan perpaduan rasa asam segar dan manis.', 25000, 35000, 20, 'pack', 1),
(12, 2, 'Blueberry Import Peru', 'Impor', 'Blueberry manis berukuran besar, kaya antioksidan, diimpor langsung dari pertanian premium di Peru.', 60000, 85000, 15, 'pack', 1),
(13, 2, 'Blackberry', 'Impor', 'Blackberry segar dengan warna hitam pekat yang kaya akan vitamin C dan sangat baik untuk kekebalan tubuh.', 70000, 95000, 10, 'pack', 1),
(14, 2, 'Raspberry Organik', 'Impor', 'Raspberry merah merona yang ditanam secara organik. Sangat cocok dijadikan smoothie bowl atau dessert.', 80000, 110000, 5, 'pack', 1),
(15, 2, 'Cranberry Fresh', 'Impor', 'Buah Cranberry utuh, sangat disarankan bagi kebugaran tubuh dan perawatan saluran pencernaan.', 55000, 75000, 8, 'pack', 1),
(16, 3, 'Apel Fuji Super', 'Impor', 'Apel Fuji grade A dengan warna cerah merata, daging buah garing, dan kandungan madu di bagian tengah.', 35000, 50000, 45, 'kg', 1),
(17, 3, 'Anggur Shine Muscat Korea', 'Impor', 'Anggur hijau tanpa biji nan viral, teksturnya renyah (crunchy) asalkan digigit utuh, dan serasa memakan permen manis.', 90000, 145000, 20, 'pack', 1),
(18, 3, 'Jeruk Mandarin Ponkam', 'Impor', 'Jeruk mandarin khas perayaan dengan kulit yang mudah dikupas, air melimpah, dan asam manis seimbang.', 28000, 40000, 55, 'kg', 1),
(19, 3, 'Kiwi Zespri Green', 'Impor', 'Kiwi Zespri Green asal New Zealand yang tinggi serat dan melancarkan pencernaan.', 40000, 60000, 30, 'pack', 1),
(20, 3, 'Pear Singo', 'Impor', 'Pear khas Korea yang berukuran jumbo bentuk bulat, sangat kaya air dan crunchy hingga gigitan terakhir.', 22000, 35000, 50, 'kg', 1),
(21, 3, 'Cherry Merah USA', 'Impor', 'Cherry utuh super gelap yang diimpor dari USA, menandakan tingkat kematangan dan kemanisan maksimal.', 120000, 180000, 12, 'kg', 1),
(22, 3, 'Peach (Persik)', 'Impor', 'Buah persik dengan wangi khas yang lembut, tekstur empuk pas untuk diolah maupun dimakan langsung.', 50000, 75000, 18, 'kg', 1),
(23, 1, 'Alpukat Mentega Super', 'Lokal', 'Alpukat berdaging tebal berwarna kuning layaknya mentega, pulen, tidak berserat, dan gampang lumat.', 20000, 35000, 60, 'kg', 1),
(24, 1, 'Buah Naga Merah', 'Lokal', 'Buah naga yang bermanfaat untuk mencegah kolesterol, manis, menyegarkan, dengan daging buah merah ungu merona.', 12000, 20000, 35, 'kg', 1),
(25, 1, 'Jambu Kristal Tanpa Biji', 'Lokal', 'Jambu kristal yang nyaris tidak memiliki biji, 100% rennyah seperti memakan apel.', 10000, 17000, 45, 'kg', 1),
(26, 3, 'Delima Merah (Pomegranate)', 'Impor', 'Delima kaya manfaat pelindung kehamilan, diimpor dengan bulir yang besar dan merah.', 45000, 70000, 15, 'kg', 1),
(27, 3, 'Lemon California', 'Impor', 'Lemon berukuran besar, air perasannya melimpah dan bagus untuk detox atau diet air hangat.', 30000, 45000, 25, 'kg', 1),
(28, 1, 'Kelapa Muda Hijau', 'Lokal', 'Kelapa muda asli yang disajikan utuh. Sangat baik untuk menetralisir racun dan menyeimbangkan elektrolit ginjal.', 6000, 12000, 50, 'buah', 1),
(29, 3, 'Anggur Hitam Autumn', 'Impor', 'Anggur hitam panjang manis tanpa biji, garing kulitnya dan lembut dagingnya.', 60000, 90000, 18, 'kg', 1),
(30, 3, 'Apel Washington', 'Impor', 'Apel ikonik yang senantiasa berwarna merah menggoda, awet segar dengan perpaduan rasa apel klasik.', 30000, 45000, 40, 'kg', 1);

-- =====================================================
-- Tabel: discounts (Voucher & Promo)
-- =====================================================
CREATE TABLE `discounts` (
    `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
    `kode_voucher` VARCHAR(30) NULL UNIQUE,
    `nama_promo` VARCHAR(100) NOT NULL,
    `tipe` ENUM('persen', 'nominal') NOT NULL DEFAULT 'persen',
    `nilai` DECIMAL(15,2) NOT NULL DEFAULT 0.00,
    `min_belanja` DECIMAL(15,2) NOT NULL DEFAULT 0.00,
    `target` ENUM('semua', 'online', 'offline', 'loyal') NOT NULL DEFAULT 'semua',
    `segment_target` VARCHAR(50) NULL,
    `tanggal_mulai` DATE NULL,
    `tanggal_selesai` DATE NULL,
    `is_active` TINYINT(1) NOT NULL DEFAULT 1,
    `created_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    INDEX `idx_kode` (`kode_voucher`),
    INDEX `idx_active` (`is_active`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO `discounts` (`kode_voucher`, `nama_promo`, `tipe`, `nilai`, `min_belanja`, `target`, `is_active`) VALUES
('WELCOME10', 'Promo Pengguna Baru 10%', 'persen', 10.00, 50000.00, 'online', 1),
('GRANDOPEN', 'Potongan Grand Opening', 'nominal', 20000.00, 100000.00, 'semua', 1),
('VIPONLY', 'Khusus Pelanggan Prioritas', 'persen', 15.00, 250000.00, 'loyal', 1);

-- =====================================================
-- Tabel: transactions (Transaksi Online & Offline)
-- =====================================================
CREATE TABLE `transactions` (
    `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
    `invoice_no` VARCHAR(30) NOT NULL UNIQUE,
    `customer_id` INT UNSIGNED NULL,
    `admin_id` INT UNSIGNED NULL,
    `discount_id` INT UNSIGNED NULL,
    `tgl` DATETIME NOT NULL,
    `subtotal` DECIMAL(15,2) NOT NULL DEFAULT 0.00,
    `diskon_amount` DECIMAL(15,2) NOT NULL DEFAULT 0.00,
    `total` DECIMAL(15,2) NOT NULL DEFAULT 0.00,
    `jenis_order` ENUM('Online', 'Offline') NOT NULL DEFAULT 'Online',
    `status` ENUM('pending', 'paid', 'verified', 'shipped', 'completed', 'cancelled') NOT NULL DEFAULT 'pending',
    `alamat_pengiriman` TEXT NULL,
    `bukti_tf` VARCHAR(255) NULL,
    `catatan` TEXT NULL,
    `created_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    INDEX `idx_invoice` (`invoice_no`),
    INDEX `idx_customer` (`customer_id`),
    INDEX `idx_admin` (`admin_id`),
    INDEX `idx_status` (`status`),
    INDEX `idx_jenis` (`jenis_order`),
    INDEX `idx_tgl` (`tgl`),
    CONSTRAINT `fk_transactions_customer` FOREIGN KEY (`customer_id`) REFERENCES `customers`(`id`) ON DELETE SET NULL ON UPDATE CASCADE,
    CONSTRAINT `fk_transactions_admin` FOREIGN KEY (`admin_id`) REFERENCES `admins`(`id`) ON DELETE SET NULL ON UPDATE CASCADE,
    CONSTRAINT `fk_transactions_discount` FOREIGN KEY (`discount_id`) REFERENCES `discounts`(`id`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Generate realistic recent dates by using DATE_SUB(NOW(), INTERVAL x DAY) but since it's static we will just use hardcoded dates representing recent activity
INSERT INTO `transactions` (`id`, `invoice_no`, `customer_id`, `admin_id`, `discount_id`, `tgl`, `subtotal`, `diskon_amount`, `total`, `jenis_order`, `status`, `catatan`) VALUES
(1, 'INV-20231001-001', 1, NULL, 1, DATE_SUB(NOW(), INTERVAL 5 DAY), 125000.00, 12500.00, 112500.00, 'Online', 'verified', 'Tolong pilihkan mangga yang tidak terlalu matang ya'),
(2, 'INV-20231002-002', 3, 1, NULL, DATE_SUB(NOW(), INTERVAL 4 DAY), 450000.00, 0, 450000.00, 'Offline', 'paid', ''),
(3, 'INV-20231003-003', 2, NULL, 2, DATE_SUB(NOW(), INTERVAL 3 DAY), 145000.00, 20000.00, 125000.00, 'Online', 'verified', 'Tolong jangan ditulis harganya di paket pengiriman'),
(4, 'INV-20231004-004', 4, NULL, NULL, DATE_SUB(NOW(), INTERVAL 1 DAY), 50000.00, 0, 50000.00, 'Online', 'paid', ''),
(5, 'INV-20231005-005', 1, NULL, NULL, NOW(), 75000.00, 0, 75000.00, 'Online', 'pending', ''),
(6, 'INV-20231006-006', 2, NULL, 1, DATE_SUB(NOW(), INTERVAL 8 HOUR), 290000.00, 29000.00, 261000.00, 'Online', 'verified', 'Saya titip keamanan anggur shine muscat nya pakek buble wrap extra');

-- =====================================================
-- Tabel: transaction_items (Detail Item Transaksi)
-- =====================================================
CREATE TABLE `transaction_items` (
    `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
    `transaction_id` INT UNSIGNED NOT NULL,
    `product_id` INT UNSIGNED NOT NULL,
    `qty` INT NOT NULL DEFAULT 0,
    `harga` DECIMAL(15,2) NOT NULL DEFAULT 0.00,
    `subtotal` DECIMAL(15,2) NOT NULL DEFAULT 0.00,
    PRIMARY KEY (`id`),
    INDEX `idx_transaction` (`transaction_id`),
    INDEX `idx_product` (`product_id`),
    CONSTRAINT `fk_items_transaction` FOREIGN KEY (`transaction_id`) REFERENCES `transactions`(`id`) ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT `fk_items_product` FOREIGN KEY (`product_id`) REFERENCES `products`(`id`) ON DELETE RESTRICT ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO `transaction_items` (`id`, `transaction_id`, `product_id`, `qty`, `harga`, `subtotal`) VALUES
(1, 1, 1, 2, 25000.00, 50000.00),
(2, 1, 22, 1, 75000.00, 75000.00),
(3, 2, 7, 1, 350000.00, 350000.00),
(4, 2, 16, 2, 50000.00, 100000.00),
(5, 3, 17, 1, 145000.00, 145000.00),
(6, 4, 1, 2, 25000.00, 50000.00),
(7, 5, 23, 1, 35000.00, 35000.00),
(8, 5, 18, 1, 40000.00, 40000.00),
(9, 6, 17, 2, 145000.00, 290000.00);

-- =====================================================
-- Tabel: feedback (Ulasan & Rating)
-- =====================================================
CREATE TABLE `feedback` (
    `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
    `customer_id` INT UNSIGNED NULL,
    `transaction_id` INT UNSIGNED NULL,
    `isi_ulasan` TEXT NULL,
    `rating` TINYINT UNSIGNED NOT NULL DEFAULT 5,
    `balasan_admin` TEXT NULL,
    `balasan_at` TIMESTAMP NULL DEFAULT NULL,
    `is_read` TINYINT(1) NOT NULL DEFAULT 0,
    `created_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    INDEX `idx_customer` (`customer_id`),
    INDEX `idx_transaction` (`transaction_id`),
    INDEX `idx_read` (`is_read`),
    INDEX `idx_rating` (`rating`),
    CONSTRAINT `fk_feedback_customer` FOREIGN KEY (`customer_id`) REFERENCES `customers`(`id`) ON DELETE SET NULL ON UPDATE CASCADE,
    CONSTRAINT `fk_feedback_transaction` FOREIGN KEY (`transaction_id`) REFERENCES `transactions`(`id`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO `feedback` (`id`, `customer_id`, `transaction_id`, `isi_ulasan`, `rating`, `balasan_admin`, `is_read`, `created_at`) VALUES
(1, 1, 1, 'Mantap mangga arum manis nya super segar tanpa serat kasar! Keluarga pada suka.', 5, 'Terima kasih telah berbelanja, ditunggu pesanan selanjutnya!', 1, DATE_SUB(NOW(), INTERVAL 4 DAY)),
(2, 2, 3, 'Anggur Shine Muscat nya luar biasa kriuk, aseli ga boong. Kayaknya abis ini order lagi deh!', 5, 'Silakan Kak, siap kirim stok fresh tiap hari.', 1, DATE_SUB(NOW(), INTERVAL 2 DAY)),
(3, 4, 4, 'Biasa aja mangganya ada yang kematangan sedikit.', 3, NULL, 0, NOW()),
(4, 1, 1, 'Persiknya juga wangi banget, empuknya pas.', 5, NULL, 0, DATE_SUB(NOW(), INTERVAL 3 DAY)),
(5, 2, 6, 'Wah pengiriman sangat rapih pakingnya bubble tebal aman!', 5, NULL, 0, DATE_SUB(NOW(), INTERVAL 1 HOUR));

-- =====================================================
-- Tabel: ci_sessions (Session Driver)
-- =====================================================
CREATE TABLE `ci_sessions` (
    `id` VARCHAR(128) NOT NULL,
    `ip_address` VARCHAR(45) NOT NULL,
    `timestamp` INT UNSIGNED DEFAULT 0,
    `data` BLOB NOT NULL,
    PRIMARY KEY (`id`),
    KEY `ci_sessions_timestamp` (`timestamp`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;