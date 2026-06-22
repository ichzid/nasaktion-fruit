-- MySQL dump 10.13  Distrib 8.0.27, for macos11 (arm64)
--
-- Host: 127.0.0.1    Database: db_nasaktion
-- ------------------------------------------------------
-- Server version	9.6.0

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;
SET @MYSQLDUMP_TEMP_LOG_BIN = @@SESSION.SQL_LOG_BIN;
SET @@SESSION.SQL_LOG_BIN= 0;

--
-- GTID state at the beginning of the backup 
--

SET @@GLOBAL.GTID_PURGED=/*!80000 '+'*/ 'e059fd58-5b35-11f1-912e-8d5d2abc31bf:1-130222';

--
-- Table structure for table `admins`
--

DROP TABLE IF EXISTS `admins`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `admins` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `nama_lengkap` varchar(100) NOT NULL,
  `role` enum('admin','kasir') NOT NULL DEFAULT 'kasir',
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `admins`
--

LOCK TABLES `admins` WRITE;
/*!40000 ALTER TABLE `admins` DISABLE KEYS */;
INSERT INTO `admins` VALUES (1,'admin','$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi','Administrator','admin',1,'2026-04-22 12:45:35','2026-04-22 12:45:35'),(2,'kasir','$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi','Kasir Ganteng','kasir',1,'2026-04-22 12:45:35','2026-04-22 12:45:35');
/*!40000 ALTER TABLE `admins` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `categories`
--

DROP TABLE IF EXISTS `categories`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `categories` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `nama_kategori` varchar(50) NOT NULL,
  `deskripsi` text,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `categories`
--

LOCK TABLES `categories` WRITE;
/*!40000 ALTER TABLE `categories` DISABLE KEYS */;
INSERT INTO `categories` VALUES (1,'Buah Tropis','Buah-buahan segar asli iklim tropis','2026-04-22 12:45:35','2026-04-22 12:45:35'),(2,'Buah Berry','Keluarga buah berry yang kaya antioksidan','2026-04-22 12:45:35','2026-04-22 12:45:35'),(3,'Buah Premium (Eksotis)','Buah-buahan impor atau organik kualitas premium','2026-04-22 12:45:35','2026-04-22 12:45:35');
/*!40000 ALTER TABLE `categories` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ci_sessions`
--

DROP TABLE IF EXISTS `ci_sessions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `ci_sessions` (
  `id` varchar(128) NOT NULL,
  `ip_address` varchar(45) NOT NULL,
  `timestamp` int unsigned DEFAULT '0',
  `data` blob NOT NULL,
  PRIMARY KEY (`id`),
  KEY `ci_sessions_timestamp` (`timestamp`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ci_sessions`
--

LOCK TABLES `ci_sessions` WRITE;
/*!40000 ALTER TABLE `ci_sessions` DISABLE KEYS */;
/*!40000 ALTER TABLE `ci_sessions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `customers`
--

DROP TABLE IF EXISTS `customers`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `customers` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
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
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `no_hp` (`no_hp`),
  KEY `idx_segment` (`segment`),
  KEY `idx_no_hp` (`no_hp`),
  KEY `idx_tipe` (`tipe_customer`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `customers`
--

LOCK TABLES `customers` WRITE;
/*!40000 ALTER TABLE `customers` DISABLE KEYS */;
INSERT INTO `customers` VALUES (1,'John Doe','081234567890','$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi',NULL,NULL,'Unified',0,0.00,0,'Baru',NULL,NULL,'2026-04-22 12:45:35','2026-06-22 06:59:30');
/*!40000 ALTER TABLE `customers` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `discounts`
--

DROP TABLE IF EXISTS `discounts`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `discounts` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `kode_voucher` varchar(30) DEFAULT NULL,
  `nama_promo` varchar(100) NOT NULL,
  `tipe` enum('persen','nominal') NOT NULL DEFAULT 'persen',
  `nilai` decimal(15,2) NOT NULL DEFAULT '0.00',
  `min_belanja` decimal(15,2) NOT NULL DEFAULT '0.00',
  `target` enum('semua','online','offline','loyal') NOT NULL DEFAULT 'semua',
  `segment_target` varchar(50) DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `kode_voucher` (`kode_voucher`),
  KEY `idx_kode` (`kode_voucher`),
  KEY `idx_active` (`is_active`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `discounts`
--

LOCK TABLES `discounts` WRITE;
/*!40000 ALTER TABLE `discounts` DISABLE KEYS */;
INSERT INTO `discounts` VALUES (1,'WELCOME10','Promo Pengguna Baru 10%','persen',10.00,50000.00,'online',NULL,1,'2026-04-22 12:45:36','2026-04-22 12:45:36'),(2,'GRANDOPEN','Potongan Grand Opening','nominal',20000.00,100000.00,'semua',NULL,1,'2026-04-22 12:45:36','2026-04-22 12:45:36'),(3,'VIPONLY','Khusus Pelanggan Prioritas','persen',15.00,250000.00,'loyal',NULL,1,'2026-04-22 12:45:36','2026-04-22 12:45:36');
/*!40000 ALTER TABLE `discounts` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `feedback`
--

DROP TABLE IF EXISTS `feedback`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `feedback` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `customer_id` int unsigned DEFAULT NULL,
  `transaction_id` int unsigned DEFAULT NULL,
  `isi_ulasan` text,
  `rating` tinyint unsigned NOT NULL DEFAULT '5',
  `balasan_admin` text,
  `balasan_at` timestamp NULL DEFAULT NULL,
  `is_read` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_customer` (`customer_id`),
  KEY `idx_transaction` (`transaction_id`),
  KEY `idx_read` (`is_read`),
  KEY `idx_rating` (`rating`),
  CONSTRAINT `fk_feedback_customer` FOREIGN KEY (`customer_id`) REFERENCES `customers` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `fk_feedback_transaction` FOREIGN KEY (`transaction_id`) REFERENCES `transactions` (`id`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `feedback`
--

LOCK TABLES `feedback` WRITE;
/*!40000 ALTER TABLE `feedback` DISABLE KEYS */;
/*!40000 ALTER TABLE `feedback` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `products`
--

DROP TABLE IF EXISTS `products`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `products` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `category_id` int unsigned NOT NULL,
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
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_category` (`category_id`),
  KEY `idx_jenis` (`jenis`),
  KEY `idx_active` (`is_active`),
  CONSTRAINT `fk_products_category` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=21 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `products`
--

LOCK TABLES `products` WRITE;
/*!40000 ALTER TABLE `products` DISABLE KEYS */;
INSERT INTO `products` VALUES (1,3,'Buah Apel','Impor',NULL,NULL,0.00,35000.00,49,'kg',1,'2026-06-22 07:06:03','2026-06-22 07:20:18'),(2,3,'Buah Anggur','Impor',NULL,NULL,0.00,35000.00,49,'kg',1,'2026-06-22 07:06:10','2026-06-22 07:20:18'),(3,3,'Buah Pir','Impor',NULL,NULL,0.00,30000.00,50,'kg',1,'2026-06-22 07:06:10','2026-06-22 07:06:10'),(4,3,'Buah Jeruk','Impor',NULL,NULL,0.00,25000.00,50,'kg',1,'2026-06-22 07:06:10','2026-06-22 07:06:10'),(5,3,'Buah Kurma','Impor',NULL,NULL,0.00,70000.00,50,'kg',1,'2026-06-22 07:06:10','2026-06-22 07:06:10'),(7,3,'Buah Lengkeng ( Longan )','Impor',NULL,NULL,0.00,50000.00,50,'kg',1,'2026-06-22 07:06:10','2026-06-22 07:06:10'),(8,3,'Buah Stroberi','Impor',NULL,NULL,0.00,65000.00,50,'kg',1,'2026-06-22 07:06:10','2026-06-22 07:06:10'),(9,3,'Buah Bluberry','Impor',NULL,NULL,0.00,70000.00,50,'kg',1,'2026-06-22 07:06:10','2026-06-22 07:06:10'),(10,3,'Buah Ceri','Impor',NULL,NULL,0.00,85000.00,50,'kg',1,'2026-06-22 07:06:10','2026-06-22 07:06:10'),(11,1,'Buah Manggis','Ekspor',NULL,NULL,0.00,40000.00,50,'kg',1,'2026-06-22 07:06:15','2026-06-22 07:06:15'),(12,1,'Buah Salak','Ekspor',NULL,NULL,0.00,15000.00,50,'kg',1,'2026-06-22 07:06:15','2026-06-22 07:06:15'),(13,1,'Buah Mangga','Ekspor',NULL,NULL,0.00,30000.00,50,'kg',1,'2026-06-22 07:06:15','2026-06-22 07:06:15'),(14,1,'Buah Durian','Ekspor',NULL,NULL,0.00,35000.00,50,'kg',1,'2026-06-22 07:06:15','2026-06-22 07:06:15'),(15,1,'Buah Rambutan','Ekspor',NULL,NULL,0.00,15000.00,50,'kg',1,'2026-06-22 07:06:15','2026-06-22 07:06:15'),(16,1,'Buah Jambu Kristal','Lokal',NULL,NULL,0.00,25000.00,50,'kg',1,'2026-06-22 07:06:21','2026-06-22 07:06:21'),(17,1,'Buah Belimbing','Lokal',NULL,NULL,0.00,25000.00,49,'kg',1,'2026-06-22 07:06:21','2026-06-22 07:20:18'),(18,1,'Buah Nanas','Lokal',NULL,NULL,0.00,15000.00,50,'kg',1,'2026-06-22 07:06:21','2026-06-22 07:06:21'),(19,1,'Buah Buah Naga','Lokal',NULL,NULL,0.00,20000.00,50,'kg',1,'2026-06-22 07:06:21','2026-06-22 07:06:21'),(20,1,'Buah Pepaya','Lokal',NULL,NULL,0.00,10000.00,50,'kg',1,'2026-06-22 07:06:21','2026-06-22 07:06:21');
/*!40000 ALTER TABLE `products` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `transaction_items`
--

DROP TABLE IF EXISTS `transaction_items`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `transaction_items` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `transaction_id` int unsigned NOT NULL,
  `product_id` int unsigned NOT NULL,
  `qty` int NOT NULL DEFAULT '0',
  `harga` decimal(15,2) NOT NULL DEFAULT '0.00',
  `subtotal` decimal(15,2) NOT NULL DEFAULT '0.00',
  PRIMARY KEY (`id`),
  KEY `idx_transaction` (`transaction_id`),
  KEY `idx_product` (`product_id`),
  CONSTRAINT `fk_items_product` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON UPDATE CASCADE,
  CONSTRAINT `fk_items_transaction` FOREIGN KEY (`transaction_id`) REFERENCES `transactions` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `transaction_items`
--

LOCK TABLES `transaction_items` WRITE;
/*!40000 ALTER TABLE `transaction_items` DISABLE KEYS */;
INSERT INTO `transaction_items` VALUES (1,1,1,1,35000.00,35000.00),(2,1,2,1,35000.00,35000.00),(3,1,17,1,25000.00,25000.00);
/*!40000 ALTER TABLE `transaction_items` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `transactions`
--

DROP TABLE IF EXISTS `transactions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `transactions` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `invoice_no` varchar(30) NOT NULL,
  `customer_id` int unsigned DEFAULT NULL,
  `admin_id` int unsigned DEFAULT NULL,
  `discount_id` int unsigned DEFAULT NULL,
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
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `invoice_no` (`invoice_no`),
  KEY `idx_invoice` (`invoice_no`),
  KEY `idx_customer` (`customer_id`),
  KEY `idx_admin` (`admin_id`),
  KEY `idx_status` (`status`),
  KEY `idx_jenis` (`jenis_order`),
  KEY `idx_tgl` (`tgl`),
  KEY `fk_transactions_discount` (`discount_id`),
  CONSTRAINT `fk_transactions_admin` FOREIGN KEY (`admin_id`) REFERENCES `admins` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `fk_transactions_customer` FOREIGN KEY (`customer_id`) REFERENCES `customers` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `fk_transactions_discount` FOREIGN KEY (`discount_id`) REFERENCES `discounts` (`id`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `transactions`
--

LOCK TABLES `transactions` WRITE;
/*!40000 ALTER TABLE `transactions` DISABLE KEYS */;
INSERT INTO `transactions` VALUES (1,'NKF202606220001',NULL,2,NULL,'2026-06-22 07:20:18',95000.00,0.00,95000.00,'Offline','completed',NULL,NULL,'','2026-06-22 07:20:18','2026-06-22 07:20:18');
/*!40000 ALTER TABLE `transactions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping routines for database 'db_nasaktion'
--
SET @@SESSION.SQL_LOG_BIN = @MYSQLDUMP_TEMP_LOG_BIN;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2026-06-22 14:24:23
