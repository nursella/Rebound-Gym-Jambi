-- Database: rebound_gym_jambi
CREATE DATABASE IF NOT EXISTS rebound_gym_jambi;
USE rebound_gym_jambi;

-- Tabel Owner/Admin
CREATE TABLE IF NOT EXISTS owner (
    id_owner INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(100) NOT NULL,
    password VARCHAR(255) NOT NULL,
    role ENUM('admin', 'super_admin') DEFAULT 'admin',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Tabel Member
CREATE TABLE IF NOT EXISTS member (
    id_member INT AUTO_INCREMENT PRIMARY KEY,
    nama VARCHAR(150) NOT NULL,
    email VARCHAR(150) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    alamat TEXT,
    nomor_whatsapp VARCHAR(20),
    tanggal_daftar DATE NOT NULL,
    status_aktif ENUM('aktif', 'nonaktif', 'expired') DEFAULT 'nonaktif',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Tabel Paket
CREATE TABLE IF NOT EXISTS paket (
    id_paket INT AUTO_INCREMENT PRIMARY KEY,
    nama_paket VARCHAR(100) NOT NULL,
    harga DECIMAL(15,2) NOT NULL,
    durasi_hari INT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Tabel Transaksi
CREATE TABLE IF NOT EXISTS transaksi (
    id_transaksi INT AUTO_INCREMENT PRIMARY KEY,
    id_member INT NOT NULL,
    id_paket INT NOT NULL,
    tanggal_mulai DATE NOT NULL,
    tanggal_expired DATE NOT NULL,
    total_harga DECIMAL(15,2) NOT NULL,
    tanggal_bayar DATE,
    metode_pembayaran ENUM('transfer_bank', 'cash', 'qris') DEFAULT 'transfer_bank',
    status_pembayaran ENUM('pending', 'lunas') DEFAULT 'pending',
    status_membership ENUM('aktif', 'hampir_habis', 'expired') DEFAULT 'aktif',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (id_member) REFERENCES member(id_member) ON DELETE CASCADE,
    FOREIGN KEY (id_paket) REFERENCES paket(id_paket) ON DELETE CASCADE
);

-- Tabel Notifikasi
CREATE TABLE IF NOT EXISTS notifikasi (
    id_notifikasi INT AUTO_INCREMENT PRIMARY KEY,
    id_transaksi INT NOT NULL,
    jenis ENUM('h-7', 'h-3', 'expired', 'followup') NOT NULL,
    pesan TEXT NOT NULL,
    tanggal_kirim DATETIME NOT NULL,
    status ENUM('terkirim', 'belum', 'dibaca') DEFAULT 'belum',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (id_transaksi) REFERENCES transaksi(id_transaksi) ON DELETE CASCADE
);

-- Tabel Check-in
CREATE TABLE IF NOT EXISTS checkin (
    id_checkin INT AUTO_INCREMENT PRIMARY KEY,
    id_member INT NOT NULL,
    tanggal_checkin DATETIME NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (id_member) REFERENCES member(id_member) ON DELETE CASCADE
);

-- Insert data awal
INSERT INTO owner (username, password, role) VALUES 
('admin', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'super_admin');

INSERT INTO paket (nama_paket, harga, durasi_hari) VALUES
('Premium 1 Bulan', 350000, 30),
('Gold 1 Bulan', 250000, 30),
('Basic 1 Bulan', 150000, 30),
('Premium 3 Bulan', 900000, 90),
('Gold 3 Bulan', 650000, 90),
('Basic 3 Bulan', 400000, 90);