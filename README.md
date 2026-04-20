# Nasaktion Fruit - Toko Online Buah Segar & CRM

Nasaktion Fruit adalah platform e-commerce dan manajemen hubungan pelanggan (CRM) yang dirancang khusus untuk bisnis penjualan buah-buahan segar. Sistem ini mengintegrasikan pengalaman belanja pelanggan yang modern dengan panel administrasi backend yang komprehensif.

Sistem ini mendukung kebutuhan operasional mulai dari katalog produk yang menarik, fitur keranjang belanja persisten, hingga pengelolaan loyalitas pelanggan melalui poin dan voucher diskon.

---

## Fitur Utama

- **Storefront Modern & Responsif**: Desain antarmuka pelanggan yang premium menggunakan Tailwind CSS dengan efek Glassmorphism.
- **Persistent Cart**: Keranjang belanja yang tersinkronisasi otomatis dengan database, sehingga item tidak hilang saat refresh atau relogin.
- **Sistem Loyalitas Pelanggan**: Perhitungan poin belanja otomatis dan segmentasi loyalitas (Baru, Silver, Gold, Platinum).
- **Manajemen Diskon & Voucher**: Fitur klaim voucher diskon secara real-time pada halaman checkout.
- **Katalog Produk Dinamis**: Pencarian produk, filter berdasarkan kategori (Lokal/Impor), dan detail produk yang menyertakan informasi gizi.
- **Feedback & Ulasan**: Pelanggan dapat memberikan rating dan ulasan untuk setiap produk yang telah dibeli.
- **WhatsApp Integration**: Alur transaksi yang memudahkan komunikasi pesanan langsung ke nomor WhatsApp.
- **Dashboard Admin CRM**: Monitoring transaksi, pengelolaan stok produk, dan manajemen feedback dalam satu panel.

---

## Modul Sistem

- **Customer Side**:
  - Halaman Beranda & Rekomendasi
  - Katalog Belanja (Shop)
  - Detail Produk (Nutrisi & Ulasan)
  - Keranjang Belanja (Persistent Cart)
  - Checkout & Kalkulasi Voucher
  - Dashboard Pelanggan (Poin & Riwayat)
  
- **Admin Side**:
  - Dashboard Statistik & Penjualan
  - Kelola Produk & Inventori
  - Kelola Kategori & Diskon
  - Kelola Transaksi (Invoice & Status)
  - Kelola Feedback Pelanggan
  - Manajemen Akun & Customer Service

---

## Tech Stack

- **Framework**: CodeIgniter 3.1.13
- **Frontend**: Tailwind CSS 3 (Play CDN) & Iconify
- **Backend Logic**: PHP 7.4+
- **Database**: MySQL / MariaDB
- **UI Library**: SweetAlert2 (Notifikasi Toast), Lucide Icons
- **Auth**: Native Session dengan integrasi Database Sync

---

## Cara Menjalankan Secara Lokal

### 1. Clone repositori ini

```bash
git clone https://github.com/ichzid/nasaktion-fruit.git
cd nasaktion-fruit
```

### 2. Persiapkan Database

1. Buat database baru di MySQL dengan nama `db_nasaktion`.
2. Impor file `database.sql` ke dalam database tersebut.

### 3. Konfigurasi Database

Buka file `application/config/database.php` dan sesuaikan pengaturan database Anda:

```php
$db['default'] = array(
	'hostname' => 'localhost',
	'username' => 'root',
	'password' => '',
	'database' => 'db_nasaktion',
    // ...
);
```

### 4. Konfigurasi Base URL

Buka file `application/config/config.php` dan sesuaikan `base_url`:

```php
$config['base_url'] = 'http://localhost/toko-online/';
```

### 5. Jalankan Aplikasi

Pindahkan folder ke direktori web server Anda (misal `htdocs` di XAMPP) dan akses melalui browser:
`http://localhost/toko-online/`

---

## Akun Demo Default

Akun berikut tersedia setelah mengimpor `database.sql`:

**Admin:**
```txt
Username : admin
Password : admin
```

**Customer (Demo):**
```txt
No. HP   : 081234567890
Password : user123
```

---

## Catatan Penggunaan

- Fitur **Google Login** memerlukan konfigurasi Client ID di `application/config/google.php`.
- Semua data transaksi dan poin loyalitas disimpan secara persisten di database.
- Untuk mengubah deskripsi produk atau ulasan, dapat dilakukan melalui panel admin.
- Gambar produk disimpan di folder `uploads/` (folder ini diabaikan oleh Git, silakan tambahkan gambar secara manual).

---

## Pengembangan Lanjutan

Aplikasi ini terbuka untuk pengembangan lebih lanjut, seperti:
- Integrasi Payment Gateway (Midtrans/Xendit).
- Fitur Tracking pengiriman melalui API kurir.
- Otomatisasi pesan WhatsApp menggunakan API bot.
- Laporan penjualan dalam format PDF/Excel yang lebih detail.

---

## Dukung Pengembangan

Jika proyek ini bermanfaat bagi Anda, dukung pengembang melalui Saweria:
[https://saweria.co/ichzid](https://saweria.co/ichzid)

---

Dibuat dengan ❤️ untuk pengalaman belanja buah yang lebih segar dan modern.
