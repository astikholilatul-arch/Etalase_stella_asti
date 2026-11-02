CREATE DATABASE IF NOT EXISTS etalase_stella;
USE etalase_stella;

-- Tabel Users
CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nama VARCHAR(100) NOT NULL,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    role ENUM('admin', 'siswa') DEFAULT 'siswa',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Tabel Projects
CREATE TABLE projects (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    judul VARCHAR(150) NOT NULL,
    deskripsi TEXT NOT NULL,
    gambar VARCHAR(255) NOT NULL,
    link_pendukung VARCHAR(255) NULL,
    link_demo VARCHAR(255) NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

-- Data Awal (Password default: 654321)
INSERT INTO users (nama, username, password, role) VALUES
('Administrator Stella', 'admin', '$2y$10$e0MYzXyjpJS7Pd0RVvHwHe1e88zS55e/hC4AedZJp3v9qjK0O0C2G', 'admin'),
('Siswa Contoh', 'siswa', '$2y$10$e0MYzXyjpJS7Pd0RVvHwHe1e88zS55e/hC4AedZJp3v9qjK0O0C2G', 'siswa');