-- Database dan Tabel SIPAP
-- Jalankan script ini di MySQL Workbench atau phpMyAdmin

CREATE DATABASE IF NOT EXISTS sipap_db;
USE sipap_db;

-- Tabel Pengguna
CREATE TABLE users (
    id INT PRIMARY KEY AUTO_INCREMENT,
    username VARCHAR(50) UNIQUE NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    role ENUM('admin', 'receptionist', 'resident') NOT NULL,
    nama_lengkap VARCHAR(100) NOT NULL,
    is_active TINYINT(1) DEFAULT 1,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_username (username),
    INDEX idx_role (role)
);

-- Tabel Penghuni
CREATE TABLE penghuni (
    id INT PRIMARY KEY AUTO_INCREMENT,
    user_id INT NOT NULL,
    nomor_unit VARCHAR(10) NOT NULL UNIQUE,
    nomor_hp VARCHAR(15) NOT NULL,
    blok VARCHAR(5),
    lantai INT,
    nama_kontak_darurat VARCHAR(100),
    nomor_kontak_darurat VARCHAR(15),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    INDEX idx_nomor_unit (nomor_unit)
);

-- Tabel Paket
CREATE TABLE paket (
    id INT PRIMARY KEY AUTO_INCREMENT,
    nomor_paket VARCHAR(20) UNIQUE NOT NULL,
    penghuni_id INT NOT NULL,
    nama_pengirim VARCHAR(100) NOT NULL,
    nama_kurir VARCHAR(100) NOT NULL,
    nama_ekspedisi VARCHAR(100) NOT NULL,
    jenis_paket ENUM('makanan_minuman', 'barang_umum', 'barang_khusus') NOT NULL,
    deskripsi TEXT,
    nomor_loker VARCHAR(10) NOT NULL,
    status ENUM('diterima', 'disimpan', 'diambil') DEFAULT 'diterima',
    resepsionis_id INT NOT NULL,
    tanggal_terima TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    tanggal_diambil DATETIME,
    catatan TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (penghuni_id) REFERENCES penghuni(id) ON DELETE CASCADE,
    FOREIGN KEY (resepsionis_id) REFERENCES users(id),
    INDEX idx_nomor_paket (nomor_paket),
    INDEX idx_penghuni_id (penghuni_id),
    INDEX idx_status (status),
    INDEX idx_tanggal_terima (tanggal_terima)
);

-- Tabel Notifikasi
CREATE TABLE notifikasi (
    id INT PRIMARY KEY AUTO_INCREMENT,
    penghuni_id INT NOT NULL,
    paket_id INT NOT NULL,
    pesan TEXT NOT NULL,
    is_read TINYINT(1) DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (penghuni_id) REFERENCES penghuni(id) ON DELETE CASCADE,
    FOREIGN KEY (paket_id) REFERENCES paket(id) ON DELETE CASCADE,
    INDEX idx_penghuni_id (penghuni_id),
    INDEX idx_is_read (is_read),
    INDEX idx_created_at (created_at)
);

-- Insert data default (password hash untuk 'password')
INSERT INTO users (username, email, password, role, nama_lengkap, is_active) 
VALUES 
('admin', 'admin@sipap.local', '$2y$10$N9qo8uLOickgx2ZMRZoMyeIjZAgcg7b3XeKeUxWdeS86E36grzD34', 'admin', 'Administrator', 1),
('receptionist', 'receptionist@sipap.local', '$2y$10$N9qo8uLOickgx2ZMRZoMyeIjZAgcg7b3XeKeUxWdeS86E36grzD34', 'receptionist', 'Receptionist', 1);

-- Verifikasi struktur database
SHOW TABLES;
