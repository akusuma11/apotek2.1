-- =====================================================
-- Database: db_apotek
-- Aplikasi Penjualan Obat - Sistem Apotek
-- =====================================================

CREATE DATABASE IF NOT EXISTS db_apotek
  DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE db_apotek;

-- ---------- USERS (Admin, Apoteker, Pelanggan) ----------
CREATE TABLE IF NOT EXISTS users (
  id INT AUTO_INCREMENT PRIMARY KEY,
  username VARCHAR(50) NOT NULL UNIQUE,
  password VARCHAR(255) NOT NULL,
  nama_lengkap VARCHAR(100) NOT NULL,
  email VARCHAR(100),
  no_telp VARCHAR(20),
  alamat TEXT,
  role ENUM('admin','apoteker','pelanggan') NOT NULL,
  status ENUM('aktif','nonaktif') DEFAULT 'aktif',
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- ---------- SUPPLIER ----------
CREATE TABLE IF NOT EXISTS supplier (
  id INT AUTO_INCREMENT PRIMARY KEY,
  nama_supplier VARCHAR(100) NOT NULL,
  alamat TEXT,
  no_telp VARCHAR(20),
  email VARCHAR(100),
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- ---------- OBAT ----------
CREATE TABLE IF NOT EXISTS obat (
  id INT AUTO_INCREMENT PRIMARY KEY,
  kode_obat VARCHAR(30) NOT NULL UNIQUE,
  nama_obat VARCHAR(150) NOT NULL,
  kategori VARCHAR(50),
  satuan VARCHAR(30),
  harga_beli DECIMAL(12,2) DEFAULT 0,
  harga_jual DECIMAL(12,2) NOT NULL DEFAULT 0,
  stok INT NOT NULL DEFAULT 0,
  tgl_kadaluarsa DATE,
  supplier_id INT,
  deskripsi TEXT,
  status ENUM('tersedia','habis','ditarik') DEFAULT 'tersedia',
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (supplier_id) REFERENCES supplier(id) ON DELETE SET NULL
) ENGINE=InnoDB;

-- ---------- PEMBELIAN (dari supplier) ----------
CREATE TABLE IF NOT EXISTS pembelian (
  id INT AUTO_INCREMENT PRIMARY KEY,
  no_faktur VARCHAR(50) NOT NULL UNIQUE,
  supplier_id INT,
  tgl_pembelian DATE NOT NULL,
  total DECIMAL(14,2) DEFAULT 0,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (supplier_id) REFERENCES supplier(id) ON DELETE SET NULL
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS pembelian_detail (
  id INT AUTO_INCREMENT PRIMARY KEY,
  pembelian_id INT NOT NULL,
  obat_id INT NOT NULL,
  jumlah INT NOT NULL,
  harga DECIMAL(12,2) NOT NULL,
  subtotal DECIMAL(14,2) NOT NULL,
  FOREIGN KEY (pembelian_id) REFERENCES pembelian(id) ON DELETE CASCADE,
  FOREIGN KEY (obat_id) REFERENCES obat(id) ON DELETE CASCADE
) ENGINE=InnoDB;

-- ---------- PENJUALAN ----------
CREATE TABLE IF NOT EXISTS penjualan (
  id INT AUTO_INCREMENT PRIMARY KEY,
  no_nota VARCHAR(50) NOT NULL UNIQUE,
  tgl_penjualan DATETIME NOT NULL,
  apoteker_id INT,
  pelanggan_id INT,
  total DECIMAL(14,2) DEFAULT 0,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (apoteker_id) REFERENCES users(id) ON DELETE SET NULL,
  FOREIGN KEY (pelanggan_id) REFERENCES users(id) ON DELETE SET NULL
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS penjualan_detail (
  id INT AUTO_INCREMENT PRIMARY KEY,
  penjualan_id INT NOT NULL,
  obat_id INT NOT NULL,
  jumlah INT NOT NULL,
  harga DECIMAL(12,2) NOT NULL,
  subtotal DECIMAL(14,2) NOT NULL,
  FOREIGN KEY (penjualan_id) REFERENCES penjualan(id) ON DELETE CASCADE,
  FOREIGN KEY (obat_id) REFERENCES obat(id) ON DELETE CASCADE
) ENGINE=InnoDB;

-- =====================================================
-- DATA AWAL
-- Password default semua user: "password123"
-- Hash dihasilkan dengan password_hash('password123', PASSWORD_DEFAULT)
-- =====================================================
INSERT INTO users (username, password, nama_lengkap, email, role) VALUES
('admin',    '$2y$12$fLN82xLCT1H7NoVphNN/EOQHN43Sqx6gr55w0kJslK/H4uxQ9N1zC', 'Administrator',  'admin@apotek.com',    'admin'),
('apoteker', '$2y$12$fLN82xLCT1H7NoVphNN/EOQHN43Sqx6gr55w0kJslK/H4uxQ9N1zC', 'Budi Apoteker',  'apoteker@apotek.com', 'apoteker'),
('pelanggan','$2y$12$fLN82xLCT1H7NoVphNN/EOQHN43Sqx6gr55w0kJslK/H4uxQ9N1zC', 'Siti Pelanggan', 'siti@mail.com',       'pelanggan');

INSERT INTO supplier (nama_supplier, alamat, no_telp, email) VALUES
('PT Kimia Farma',     'Jl. Veteran No.9 Jakarta', '021-3456789', 'sales@kimiafarma.co.id'),
('PT Kalbe Farma',     'Jl. Letjen Suprapto Jakarta', '021-4222333', 'cs@kalbe.co.id'),
('PT Sanbe Farma',     'Jl. Industri Bandung',   '022-6033777', 'info@sanbe.co.id');

INSERT INTO obat (kode_obat, nama_obat, kategori, satuan, harga_beli, harga_jual, stok, tgl_kadaluarsa, supplier_id, deskripsi) VALUES
('OBT001','Paracetamol 500mg','Analgesik','Tablet',  500, 1000, 200, '2026-12-31', 1, 'Obat penurun demam dan pereda nyeri.'),
('OBT002','Amoxicillin 500mg','Antibiotik','Kapsul', 1500, 3000, 150, '2026-08-15', 2, 'Antibiotik golongan penisilin.'),
('OBT003','Vitamin C 1000mg','Vitamin','Tablet',     800, 2000, 300, '2027-03-20', 2, 'Suplemen daya tahan tubuh.'),
('OBT004','OBH Combi 100ml','Obat Batuk','Botol',   8000,15000,  60, '2025-11-30', 3, 'Obat batuk dewasa.'),
('OBT005','Antasida Doen','Lambung','Tablet',        300,  800, 180, '2026-05-10', 1, 'Mengurangi asam lambung.');

-- ---------- PEMESANAN (Pesanan Online oleh Pelanggan) ----------
CREATE TABLE IF NOT EXISTS pemesanan (
  id INT AUTO_INCREMENT PRIMARY KEY,
  no_pesanan VARCHAR(50) NOT NULL UNIQUE,
  pelanggan_id INT,
  tgl_pesanan DATETIME NOT NULL,
  nama_penerima VARCHAR(100) NOT NULL,
  no_telp VARCHAR(20) NOT NULL,
  alamat_kirim TEXT NOT NULL,
  catatan TEXT,
  metode_bayar ENUM('qris','cod') NOT NULL,
  status_bayar ENUM('menunggu','dibayar','gagal') DEFAULT 'menunggu',
  status_pesanan ENUM('diproses','selesai','dibatalkan') DEFAULT 'diproses',
  total DECIMAL(14,2) DEFAULT 0,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (pelanggan_id) REFERENCES users(id) ON DELETE SET NULL
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS pemesanan_detail (
  id INT AUTO_INCREMENT PRIMARY KEY,
  pemesanan_id INT NOT NULL,
  obat_id INT NOT NULL,
  jumlah INT NOT NULL,
  harga DECIMAL(12,2) NOT NULL,
  subtotal DECIMAL(14,2) NOT NULL,
  FOREIGN KEY (pemesanan_id) REFERENCES pemesanan(id) ON DELETE CASCADE,
  FOREIGN KEY (obat_id) REFERENCES obat(id) ON DELETE CASCADE
) ENGINE=InnoDB;
