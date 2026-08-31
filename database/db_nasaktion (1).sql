-- phpMyAdmin SQL Dump
-- version 5.2.3
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Aug 10, 2026 at 02:27 PM
-- Server version: 9.6.0
-- PHP Version: 8.4.23

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `db_nasaktion`
--

-- --------------------------------------------------------

--
-- Table structure for table `admins`
--

CREATE TABLE `admins` (
  `id` int UNSIGNED NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `nama_lengkap` varchar(100) NOT NULL,
  `role` enum('admin','kasir') NOT NULL DEFAULT 'kasir',
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `admins`
--

INSERT INTO `admins` (`id`, `username`, `password`, `nama_lengkap`, `role`, `is_active`, `created_at`, `updated_at`) VALUES
(1, 'admin', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Administrator', 'admin', 1, '2026-04-22 12:45:35', '2026-04-22 12:45:35'),
(2, 'kasir', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Kasir Ganteng', 'kasir', 1, '2026-04-22 12:45:35', '2026-04-22 12:45:35'),
(3, 'owner', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Owner', 'owner', 1, '2026-08-31 00:00:00', '2026-08-31 00:00:00');

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `id` int UNSIGNED NOT NULL,
  `nama_kategori` varchar(50) NOT NULL,
  `deskripsi` text,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`id`, `nama_kategori`, `deskripsi`, `created_at`, `updated_at`) VALUES
(1, 'Buah Tropis', 'Buah-buahan segar asli iklim tropis', '2026-04-22 12:45:35', '2026-04-22 12:45:35'),
(2, 'Buah Berry', 'Keluarga buah berry yang kaya antioksidan', '2026-04-22 12:45:35', '2026-04-22 12:45:35'),
(3, 'Buah Premium (Eksotis)', 'Buah-buahan impor atau organik kualitas premium', '2026-04-22 12:45:35', '2026-04-22 12:45:35');

-- --------------------------------------------------------

--
-- Table structure for table `ci_sessions`
--

CREATE TABLE `ci_sessions` (
  `id` varchar(128) NOT NULL,
  `ip_address` varchar(45) NOT NULL,
  `timestamp` int UNSIGNED DEFAULT '0',
  `data` blob NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `customers`
--

CREATE TABLE `customers` (
  `id` int UNSIGNED NOT NULL,
  `nama` varchar(100) NOT NULL,
  `no_hp` varchar(20) NOT NULL,
  `password` varchar(255) DEFAULT NULL,
  `alamat` text,
  `foto` varchar(255) DEFAULT NULL,
  `tipe_customer` enum('Online','Offline','Unified') NOT NULL DEFAULT 'Offline',
  `point_loyalitas` int NOT NULL DEFAULT '0',
  `total_belanja` decimal(15,2) NOT NULL DEFAULT '0.00',
  `total_transaksi` int NOT NULL DEFAULT '0',
  `segment` enum('Baru','Silver','Gold','Platinum') NOT NULL DEFAULT 'Baru',
  `last_transaction_at` timestamp NULL DEFAULT NULL,
  `cart_data` text,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `customers`
--

INSERT INTO `customers` (`id`, `nama`, `no_hp`, `password`, `alamat`, `foto`, `tipe_customer`, `point_loyalitas`, `total_belanja`, `total_transaksi`, `segment`, `last_transaction_at`, `cart_data`, `created_at`, `updated_at`) VALUES
(1, 'John Doe', '081234567890', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', NULL, NULL, 'Unified', 0, 0.00, 0, 'Baru', NULL, '{\"18\":{\"product_id\":\"18\",\"nama_buah\":\"Buah Nanas\",\"harga\":\"15000.00\",\"qty\":\"1\",\"subtotal\":15000,\"foto\":\"nanas.jpg\",\"satuan\":\"kg\",\"stok\":\"55\"},\"1\":{\"product_id\":\"1\",\"nama_buah\":\"Buah Apel\",\"harga\":\"35000.00\",\"qty\":1,\"subtotal\":35000,\"foto\":\"apel.jpg\",\"satuan\":\"kg\",\"stok\":\"51\"}}', '2026-04-22 12:45:35', '2026-08-04 16:57:09');

-- --------------------------------------------------------

--
-- Table structure for table `discounts`
--

CREATE TABLE `discounts` (
  `id` int UNSIGNED NOT NULL,
  `kode_voucher` varchar(30) DEFAULT NULL,
  `nama_promo` varchar(100) NOT NULL,
  `tipe` enum('persen','nominal') NOT NULL DEFAULT 'persen',
  `nilai` decimal(15,2) NOT NULL DEFAULT '0.00',
  `min_belanja` decimal(15,2) NOT NULL DEFAULT '0.00',
  `target` enum('semua','online','offline','loyal') NOT NULL DEFAULT 'semua',
  `segment_target` varchar(50) DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `discounts`
--

INSERT INTO `discounts` (`id`, `kode_voucher`, `nama_promo`, `tipe`, `nilai`, `min_belanja`, `target`, `segment_target`, `is_active`, `created_at`, `updated_at`) VALUES
(1, 'WELCOME10', 'Promo Pengguna Baru 10%', 'persen', 10.00, 50000.00, 'online', NULL, 1, '2026-04-22 12:45:36', '2026-04-22 12:45:36'),
(2, 'GRANDOPEN', 'Potongan Grand Opening', 'nominal', 20000.00, 100000.00, 'semua', NULL, 1, '2026-04-22 12:45:36', '2026-04-22 12:45:36'),
(3, 'VIPONLY', 'Khusus Pelanggan Prioritas', 'persen', 15.00, 250000.00, 'loyal', NULL, 1, '2026-04-22 12:45:36', '2026-04-22 12:45:36');

-- --------------------------------------------------------

--
-- Table structure for table `feedback`
--

CREATE TABLE `feedback` (
  `id` int UNSIGNED NOT NULL,
  `customer_id` int UNSIGNED DEFAULT NULL,
  `transaction_id` int UNSIGNED DEFAULT NULL,
  `isi_ulasan` text,
  `rating` tinyint UNSIGNED NOT NULL DEFAULT '5',
  `balasan_admin` text,
  `balasan_at` timestamp NULL DEFAULT NULL,
  `is_read` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `id` int UNSIGNED NOT NULL,
  `category_id` int UNSIGNED NOT NULL,
  `nama_buah` varchar(100) NOT NULL,
  `jenis` enum('Impor','Ekspor','Lokal') NOT NULL DEFAULT 'Lokal',
  `foto` varchar(255) DEFAULT NULL,
  `deskripsi` text,
  `harga_beli` decimal(15,2) NOT NULL DEFAULT '0.00',
  `harga_jual` decimal(15,2) NOT NULL DEFAULT '0.00',
  `stok` int NOT NULL DEFAULT '0',
  `satuan` varchar(20) NOT NULL DEFAULT 'kg',
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `category_id`, `nama_buah`, `jenis`, `foto`, `deskripsi`, `harga_beli`, `harga_jual`, `stok`, `satuan`, `is_active`, `created_at`, `updated_at`) VALUES
(1, 3, 'Buah Apel', 'Impor', 'apel.jpg', NULL, 0.00, 35000.00, 51, 'kg', 1, '2026-06-22 07:06:03', '2026-08-04 14:53:28'),
(2, 3, 'Buah Anggur', 'Impor', 'anggur.jpg', NULL, 0.00, 35000.00, 87, 'kg', 1, '2026-06-22 07:06:10', '2026-08-04 14:53:28'),
(3, 3, 'Buah Pir', 'Impor', 'pir.jpg', NULL, 0.00, 30000.00, 76, 'kg', 1, '2026-06-22 07:06:10', '2026-08-04 14:53:28'),
(4, 3, 'Buah Jeruk', 'Impor', 'jeruk.jpg', NULL, 0.00, 25000.00, 71, 'kg', 1, '2026-06-22 07:06:10', '2026-08-04 14:53:28'),
(5, 3, 'Buah Kurma', 'Impor', 'kurma.jpg', NULL, 0.00, 70000.00, 78, 'kg', 1, '2026-06-22 07:06:10', '2026-08-04 14:53:28'),
(7, 3, 'Buah Lengkeng ( Longan )', 'Impor', 'lengkeng.jpg', NULL, 0.00, 50000.00, 75, 'kg', 1, '2026-06-22 07:06:10', '2026-08-04 14:53:28'),
(8, 3, 'Buah Stroberi', 'Impor', 'stroberi.jpg', NULL, 0.00, 65000.00, 94, 'kg', 1, '2026-06-22 07:06:10', '2026-08-04 14:53:28'),
(9, 3, 'Buah Bluberry', 'Impor', 'blueberry.jpg', NULL, 0.00, 70000.00, 92, 'kg', 1, '2026-06-22 07:06:10', '2026-08-04 14:53:28'),
(10, 3, 'Buah Ceri', 'Impor', 'ceri.jpg', NULL, 0.00, 85000.00, 78, 'kg', 1, '2026-06-22 07:06:10', '2026-08-04 14:53:28'),
(11, 1, 'Buah Manggis', 'Ekspor', 'manggis.jpg', NULL, 0.00, 40000.00, 63, 'kg', 1, '2026-06-22 07:06:15', '2026-08-04 14:53:28'),
(12, 1, 'Buah Salak', 'Ekspor', 'salak.jpg', NULL, 0.00, 15000.00, 83, 'kg', 1, '2026-06-22 07:06:15', '2026-08-04 14:53:28'),
(13, 1, 'Buah Mangga', 'Ekspor', 'mangga.jpg', NULL, 0.00, 30000.00, 75, 'kg', 1, '2026-06-22 07:06:15', '2026-08-04 14:53:28'),
(14, 1, 'Buah Durian', 'Ekspor', 'durian.jpg', NULL, 0.00, 35000.00, 76, 'kg', 1, '2026-06-22 07:06:15', '2026-08-04 14:53:28'),
(15, 1, 'Buah Rambutan', 'Ekspor', 'rambutan.jpg', NULL, 0.00, 15000.00, 53, 'kg', 1, '2026-06-22 07:06:15', '2026-08-04 14:53:28'),
(16, 1, 'Buah Jambu Kristal', 'Lokal', 'jambu-kristal.jpg', NULL, 0.00, 25000.00, 89, 'kg', 1, '2026-06-22 07:06:21', '2026-08-04 14:53:28'),
(17, 1, 'Buah Belimbing', 'Lokal', 'belimbing.jpg', NULL, 0.00, 25000.00, 85, 'kg', 1, '2026-06-22 07:06:21', '2026-08-04 14:53:28'),
(18, 1, 'Buah Nanas', 'Lokal', 'nanas.jpg', NULL, 0.00, 15000.00, 55, 'kg', 1, '2026-06-22 07:06:21', '2026-08-04 14:53:28'),
(19, 1, 'Buah Buah Naga', 'Lokal', 'buah-naga.jpg', NULL, 0.00, 20000.00, 76, 'kg', 1, '2026-06-22 07:06:21', '2026-08-04 14:53:28'),
(20, 1, 'Buah Pepaya', 'Lokal', 'pepaya.jpg', NULL, 0.00, 10000.00, 62, 'kg', 1, '2026-06-22 07:06:21', '2026-08-04 14:53:28');

-- --------------------------------------------------------

--
-- Table structure for table `transactions`
--

CREATE TABLE `transactions` (
  `id` int UNSIGNED NOT NULL,
  `invoice_no` varchar(30) NOT NULL,
  `customer_id` int UNSIGNED DEFAULT NULL,
  `admin_id` int UNSIGNED DEFAULT NULL,
  `discount_id` int UNSIGNED DEFAULT NULL,
  `tgl` datetime NOT NULL,
  `subtotal` decimal(15,2) NOT NULL DEFAULT '0.00',
  `diskon_amount` decimal(15,2) NOT NULL DEFAULT '0.00',
  `total` decimal(15,2) NOT NULL DEFAULT '0.00',
  `jenis_order` enum('Online','Offline') NOT NULL DEFAULT 'Online',
  `status` enum('pending','paid','verified','shipped','completed','cancelled') NOT NULL DEFAULT 'pending',
  `alamat_pengiriman` text,
  `bukti_tf` varchar(255) DEFAULT NULL,
  `catatan` text,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `transactions`
--

INSERT INTO `transactions` (`id`, `invoice_no`, `customer_id`, `admin_id`, `discount_id`, `tgl`, `subtotal`, `diskon_amount`, `total`, `jenis_order`, `status`, `alamat_pengiriman`, `bukti_tf`, `catatan`, `created_at`, `updated_at`) VALUES
(1, 'NKF202606220001', NULL, 2, NULL, '2026-06-22 07:20:18', 95000.00, 0.00, 95000.00, 'Offline', 'completed', NULL, NULL, '', '2026-06-22 07:20:18', '2026-06-22 07:20:18');

-- --------------------------------------------------------

--
-- Table structure for table `transaction_items`
--

CREATE TABLE `transaction_items` (
  `id` int UNSIGNED NOT NULL,
  `transaction_id` int UNSIGNED NOT NULL,
  `product_id` int UNSIGNED NOT NULL,
  `qty` int NOT NULL DEFAULT '0',
  `harga` decimal(15,2) NOT NULL DEFAULT '0.00',
  `subtotal` decimal(15,2) NOT NULL DEFAULT '0.00'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `transaction_items`
--

INSERT INTO `transaction_items` (`id`, `transaction_id`, `product_id`, `qty`, `harga`, `subtotal`) VALUES
(1, 1, 1, 1, 35000.00, 35000.00),
(2, 1, 2, 1, 35000.00, 35000.00),
(3, 1, 17, 1, 25000.00, 25000.00);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admins`
--
ALTER TABLE `admins`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `ci_sessions`
--
ALTER TABLE `ci_sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `ci_sessions_timestamp` (`timestamp`);

--
-- Indexes for table `customers`
--
ALTER TABLE `customers`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `no_hp` (`no_hp`),
  ADD KEY `idx_segment` (`segment`),
  ADD KEY `idx_no_hp` (`no_hp`),
  ADD KEY `idx_tipe` (`tipe_customer`);

--
-- Indexes for table `discounts`
--
ALTER TABLE `discounts`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `kode_voucher` (`kode_voucher`),
  ADD KEY `idx_kode` (`kode_voucher`),
  ADD KEY `idx_active` (`is_active`);

--
-- Indexes for table `feedback`
--
ALTER TABLE `feedback`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_customer` (`customer_id`),
  ADD KEY `idx_transaction` (`transaction_id`),
  ADD KEY `idx_read` (`is_read`),
  ADD KEY `idx_rating` (`rating`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_category` (`category_id`),
  ADD KEY `idx_jenis` (`jenis`),
  ADD KEY `idx_active` (`is_active`);

--
-- Indexes for table `transactions`
--
ALTER TABLE `transactions`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `invoice_no` (`invoice_no`),
  ADD KEY `idx_invoice` (`invoice_no`),
  ADD KEY `idx_customer` (`customer_id`),
  ADD KEY `idx_admin` (`admin_id`),
  ADD KEY `idx_status` (`status`),
  ADD KEY `idx_jenis` (`jenis_order`),
  ADD KEY `idx_tgl` (`tgl`),
  ADD KEY `fk_transactions_discount` (`discount_id`);

--
-- Indexes for table `transaction_items`
--
ALTER TABLE `transaction_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_transaction` (`transaction_id`),
  ADD KEY `idx_product` (`product_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admins`
--
ALTER TABLE `admins`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `customers`
--
ALTER TABLE `customers`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `discounts`
--
ALTER TABLE `discounts`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `feedback`
--
ALTER TABLE `feedback`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `transactions`
--
ALTER TABLE `transactions`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `transaction_items`
--
ALTER TABLE `transaction_items`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `feedback`
--
ALTER TABLE `feedback`
  ADD CONSTRAINT `fk_feedback_customer` FOREIGN KEY (`customer_id`) REFERENCES `customers` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_feedback_transaction` FOREIGN KEY (`transaction_id`) REFERENCES `transactions` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Constraints for table `products`
--
ALTER TABLE `products`
  ADD CONSTRAINT `fk_products_category` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON UPDATE CASCADE;

--
-- Constraints for table `transactions`
--
ALTER TABLE `transactions`
  ADD CONSTRAINT `fk_transactions_admin` FOREIGN KEY (`admin_id`) REFERENCES `admins` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_transactions_customer` FOREIGN KEY (`customer_id`) REFERENCES `customers` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_transactions_discount` FOREIGN KEY (`discount_id`) REFERENCES `discounts` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Constraints for table `transaction_items`
--
ALTER TABLE `transaction_items`
  ADD CONSTRAINT `fk_items_product` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_items_transaction` FOREIGN KEY (`transaction_id`) REFERENCES `transactions` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
