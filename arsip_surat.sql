-- arsip_surat.sql
-- Struktur database dan data awal untuk aplikasi Arsip Surat Kepegawaian

CREATE DATABASE IF NOT EXISTS arsip_surat;
USE arsip_surat;

-- Tabel users
CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    nama VARCHAR(100) NOT NULL,
    level ENUM('admin','pegawai') NOT NULL
);

-- Tabel surat
CREATE TABLE IF NOT EXISTS surat (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nomor_surat VARCHAR(100) NOT NULL,
    nama_kegiatan VARCHAR(255) NOT NULL,
    nomor_laci VARCHAR(20) NOT NULL,
    nomor_lemari VARCHAR(20) NOT NULL,
    kode_surat VARCHAR(50) NOT NULL,
    kategori ENUM('SPT','LPJ','SPPD','Izin','Sakit','Cuti') NOT NULL,
    keterangan TEXT,
    file_path VARCHAR(255) NOT NULL,
    rincian_biaya VARCHAR(255) NULL,
    bukti VARCHAR(255) NULL,
    uploaded_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    uploaded_by INT,
    FOREIGN KEY (uploaded_by) REFERENCES users(id)
);

-- Admin default
INSERT INTO users (username, password, nama, level) VALUES
('admin', 'bgtk', 'Administrator', 'admin');
