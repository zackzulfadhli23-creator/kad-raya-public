CREATE DATABASE IF NOT EXISTS db_kad_raya;
USE db_kad_raya;

CREATE TABLE IF NOT EXISTS ucapan_raya (
    id INT AUTO_INCREMENT PRIMARY KEY,
    unique_id VARCHAR(10) NOT NULL UNIQUE,
    nama_pengirim VARCHAR(100) NOT NULL,
    nama_penerima VARCHAR(100) NOT NULL,
    mesej TEXT NOT NULL,
    tema_warna VARCHAR(50) NOT NULL,
    image_path VARCHAR(255) DEFAULT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
