
-- NAMA FILE: 60324068_AriMaulidaAprilia_database.sql
-- DESKRIPSI: Tugas 2 - Desain Database Lengkap (Include Bonus)

-- 1. CREATE DATABASE
CREATE DATABASE IF NOT EXISTS db_perpustakaan_lengkap;
USE db_perpustakaan_lengkap;

-- 2. CREATE TABEL & IMPLEMENTASI SOFT DELETE
-- (Bonus: Soft Delete menggunakan kolom deleted_at)

-- Tabel Kategori Buku
CREATE TABLE kategori_buku (
    id_kategori INT AUTO_INCREMENT PRIMARY KEY,
    nama_kategori VARCHAR(50) NOT NULL UNIQUE,
    deskripsi TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    deleted_at TIMESTAMP NULL DEFAULT NULL
);

-- Tabel Penerbit
CREATE TABLE penerbit (
    id_penerbit INT AUTO_INCREMENT PRIMARY KEY,
    nama_penerbit VARCHAR(100) NOT NULL,
    alamat TEXT,
    telepon VARCHAR(15),
    email VARCHAR(100),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    deleted_at TIMESTAMP NULL DEFAULT NULL
);

-- BONUS: Tabel Rak
CREATE TABLE rak (
    id_rak INT AUTO_INCREMENT PRIMARY KEY,
    nama_rak VARCHAR(50) NOT NULL,
    lokasi VARCHAR(50),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    deleted_at TIMESTAMP NULL DEFAULT NULL
);

-- Tabel Buku (Modifikasi dengan Foreign Key ke Kategori, Penerbit, dan Rak)
CREATE TABLE buku (
    id_buku INT AUTO_INCREMENT PRIMARY KEY,
    judul VARCHAR(255) NOT NULL,
    id_kategori INT,
    id_penerbit INT,
    id_rak INT,
    pengarang VARCHAR(100),
    tahun_terbit INT,
    harga DECIMAL(10,2),
    stok INT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    deleted_at TIMESTAMP NULL DEFAULT NULL,
    FOREIGN KEY (id_kategori) REFERENCES kategori_buku(id_kategori) ON DELETE RESTRICT ON UPDATE CASCADE,
    FOREIGN KEY (id_penerbit) REFERENCES penerbit(id_penerbit) ON DELETE RESTRICT ON UPDATE CASCADE,
    FOREIGN KEY (id_rak) REFERENCES rak(id_rak) ON DELETE SET NULL ON UPDATE CASCADE
);

-- 3. INSERT DATA SAMPLE

-- Insert 5 Kategori
INSERT INTO kategori_buku (nama_kategori, deskripsi) VALUES
('Programming', 'Buku tentang bahasa pemrograman dan coding'),
('Database', 'Buku tentang perancangan dan manajemen basis data'),
('Jaringan', 'Buku tentang infrastruktur dan keamanan jaringan'),
('Kecerdasan Buatan', 'Buku tentang AI, Machine Learning, dan Fuzzy Logic'),
('Desain UI/UX', 'Buku tentang antarmuka dan pengalaman pengguna');

-- Insert 5 Penerbit
INSERT INTO penerbit (nama_penerbit, alamat, telepon, email) VALUES
('Informatika Bandung', 'Jl. Buah Batu, Bandung', '022-123456', 'info@informatika.com'),
('Penerbit Andi', 'Jl. Beo, Yogyakarta', '0274-654321', 'cs@andipublisher.com'),
('Elex Media Komputindo', 'Jl. Palmerah Barat, Jakarta', '021-987654', 'redaksi@elexmedia.id'),
('Gramedia Pustaka', 'Jl. Matraman Raya, Jakarta', '021-112233', 'halo@gramedia.com'),
('Lokomedia', 'Jl. Cempaka, Sleman', '0274-556677', 'kontak@lokomedia.co.id');

-- Insert 5 Rak (Bonus)
INSERT INTO rak (nama_rak, lokasi) VALUES
('Rak A1', 'Lantai 1 Sayap Kiri'),
('Rak A2', 'Lantai 1 Sayap Kanan'),
('Rak B1', 'Lantai 2 Lorong A'),
('Rak B2', 'Lantai 2 Lorong B'),
('Rak C1', 'Lantai 3 Khusus Referensi');

-- Insert 15 Buku
INSERT INTO buku (judul, id_kategori, id_penerbit, id_rak, pengarang, tahun_terbit, harga, stok) VALUES
('Belajar Laravel 10', 1, 1, 1, 'Budi Raharjo', 2024, 120000, 10),
('Mahir PHP & MySQL', 1, 5, 1, 'Lukman Shodik', 2023, 95000, 5),
('Konsep Sistem Basis Data', 2, 2, 2, 'Fathansyah', 2022, 85000, 15),
('Optimasi Query SQL', 2, 1, 2, 'Ari Maulida', 2024, 110000, 8),
('Jaringan Komputer Lanjut', 3, 2, 3, 'Agung Doni', 2021, 150000, 3),
('Keamanan Cyber BSSN', 3, 3, 3, 'Tim Keamanan', 2024, 175000, 7),
('Implementasi Fuzzy Sugeno', 4, 1, 4, 'Nailah Dhina', 2023, 130000, 6),
('Pengantar Intelligent Agents', 4, 4, 4, 'Fatkhunihadh', 2022, 140000, 4),
('Evaluasi Heuristik UI/UX', 5, 2, 5, 'Jakob Nielsen', 2024, 160000, 9),
('Desain DANA App', 5, 3, 5, 'Tim Desain', 2023, 90000, 12),
('Struktur Data AVL Tree', 1, 1, 1, 'Budi Raharjo', 2023, 105000, 11),
('Mastering Bootstrap 5', 1, 4, 1, 'Eko Kurniawan', 2022, 85000, 20),
('Data Mining dengan Python', 4, 1, 4, 'Budi Santoso', 2024, 155000, 5),
('Manajemen Proyek TI', 2, 2, 2, 'Rinaldi Munir', 2021, 135000, 8),
('Cara Mudah Paham UML', 1, 5, 2, 'Rosa A.S', 2022, 75000, 14);


-- 4. QUERY WAJIB (Menggunakan JOIN)
-- Catatan: Menggunakan WHERE deleted_at IS NULL untuk mengabaikan data yang terkena soft delete

-- A. JOIN untuk tampilkan buku dengan nama kategori dan penerbit
SELECT b.judul, k.nama_kategori, p.nama_penerbit 
FROM buku b
JOIN kategori_buku k ON b.id_kategori = k.id_kategori
JOIN penerbit p ON b.id_penerbit = p.id_penerbit
WHERE b.deleted_at IS NULL;

-- B. Jumlah buku per kategori
SELECT k.nama_kategori, COUNT(b.id_buku) AS jumlah_buku
FROM kategori_buku k
LEFT JOIN buku b ON k.id_kategori = b.id_kategori AND b.deleted_at IS NULL
WHERE k.deleted_at IS NULL
GROUP BY k.id_kategori;

-- C. Jumlah buku per penerbit
SELECT p.nama_penerbit, COUNT(b.id_buku) AS jumlah_buku
FROM penerbit p
LEFT JOIN buku b ON p.id_penerbit = b.id_penerbit AND b.deleted_at IS NULL
WHERE p.deleted_at IS NULL
GROUP BY p.id_penerbit;

-- D. Buku beserta detail lengkap (kategori + penerbit + rak)
SELECT b.judul, b.pengarang, b.tahun_terbit, k.nama_kategori, p.nama_penerbit, r.nama_rak, b.harga, b.stok
FROM buku b
JOIN kategori_buku k ON b.id_kategori = k.id_kategori
JOIN penerbit p ON b.id_penerbit = p.id_penerbit
LEFT JOIN rak r ON b.id_rak = r.id_rak
WHERE b.deleted_at IS NULL;


-- 5. BONUS: STORED PROCEDURE

DELIMITER //

-- Procedure untuk melakukan Soft Delete pada Buku
CREATE PROCEDURE SoftDeleteBuku(IN p_id_buku INT)
BEGIN
    UPDATE buku 
    SET deleted_at = CURRENT_TIMESTAMP 
    WHERE id_buku = p_id_buku;
END //

-- Procedure untuk meminjam buku (mengurangi stok)
CREATE PROCEDURE PinjamBuku(IN p_id_buku INT)
BEGIN
    UPDATE buku 
    SET stok = stok - 1 
    WHERE id_buku = p_id_buku AND stok > 0 AND deleted_at IS NULL;
END //

DELIMITER ;