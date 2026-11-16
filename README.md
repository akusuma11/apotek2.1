# Apotek Sehat - Aplikasi Penjualan Obat (PHP + MySQL)

Aplikasi sistem penjualan obat berbasis web dengan PHP murni + MySQL + Bootstrap 5.
Memiliki 3 role: **Admin**, **Apoteker**, dan **Pelanggan**.

## 🧰 Kebutuhan
- PHP 7.4+ (PDO MySQL aktif)
- MySQL / MariaDB
- Web server: **XAMPP** / **Laragon** / **WAMP** (rekomendasi)

## 🚀 Cara Instalasi

### 1. Ekstrak project
Ekstrak `apotek.zip` ke folder `htdocs` (XAMPP) atau `www` (Laragon).
Contoh: `C:\xampp\htdocs\apotek\`

### 2. Buat database
- Buka **phpMyAdmin** → http://localhost/phpmyadmin
- Buat database baru: `db_apotek`
- Import file `db_apotek.sql`

### 3. Sesuaikan koneksi (jika perlu)
Edit `config/db.php`:
```php
$DB_HOST = 'localhost';
$DB_USER = 'root';
$DB_PASS = '';      // sesuaikan dengan MySQL Anda
$DB_NAME = 'db_apotek';
```

### 4. Jalankan
Buka browser: **http://localhost/apotek/**

## 👥 Akun Demo
| Role      | Username   | Password      |
|-----------|------------|---------------|
| Admin     | admin      | password123   |
| Apoteker  | apoteker   | password123   |
| Pelanggan | pelanggan  | password123   |

## 📂 Struktur Folder
```
apotek/
├── config/db.php           # Koneksi database
├── includes/               # auth, header, sidebar, footer
├── assets/css/style.css
├── admin/                  # Halaman role Admin
│   ├── dashboard.php
│   ├── apoteker.php + apoteker_form.php
│   ├── obat.php + obat_form.php
│   ├── supplier.php
│   ├── pelanggan.php
│   ├── pembelian.php
│   ├── kadaluarsa.php
│   └── report.php
├── apoteker/               # Halaman role Apoteker
│   ├── dashboard.php
│   ├── obat.php + obat_detail.php
│   ├── penjualan.php + penjualan_form.php + penjualan_detail.php
│   └── kadaluarsa.php
├── pelanggan/              # Halaman role Pelanggan
│   ├── dashboard.php
│   └── obat.php
├── index.php  login.php  signup.php  logout.php
└── db_apotek.sql           # Skema + data awal
```

## ✅ Fitur

### Admin
- Dashboard ringkasan statistik
- CRUD Apoteker (daftarkan / edit / hapus)
- CRUD Obat (tambah, edit, hapus, ubah status)
- CRUD Supplier
- Daftarkan Pelanggan
- Daftar Pembelian Obat
- Cari obat akan kadaluarsa
- Report Penjualan Obat (filter tanggal)

### Apoteker
- Dashboard penjualan harian
- Lihat & cari data obat
- Tambah / edit / hapus obat
- Detail obat + histori penjualan obat tersebut
- Transaksi penjualan (multi-item, otomatis update stok)
- Histori penjualan + detail nota (bisa dicetak)
- Hapus obat yang sudah kadaluarsa

### Pelanggan
- Sign up sendiri di halaman `signup.php`
- Lihat katalog obat yang dijual
- Pencarian obat

## 🔒 Keamanan
- Password di-hash dengan `password_hash()` (bcrypt)
- Query menggunakan **PDO prepared statements** (anti SQL injection)
- Output di-escape dengan `htmlspecialchars()` (anti XSS)
- Role-based access control via `require_role()`

## 📝 Catatan
Data awal sudah berisi 5 obat contoh, 3 supplier, dan 3 user demo.
Untuk produksi, ganti semua password default dan buat user baru.
