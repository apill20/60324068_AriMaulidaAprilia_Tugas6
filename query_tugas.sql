-- Tugas 1 - Eksplorasi Database

-- A. STATISTIK BUKU (5 Query)

-- 1. Total buku seluruhnya (Menghitung total judul buku dan total fisik/stok buku)
SELECT COUNT(*) AS total_judul_buku, SUM(stok) AS total_keseluruhan_fisik 
FROM buku;

-- 2. Total nilai inventaris (sum harga x stok)
SELECT SUM(harga * stok) AS total_nilai_inventaris 
FROM buku;

-- 3. Rata-rata harga buku
SELECT AVG(harga) AS rata_rata_harga 
FROM buku;

-- 4. Buku termahal (tampilkan judul dan harga)
SELECT judul, harga 
FROM buku 
ORDER BY harga DESC 
LIMIT 1;

-- 5. Buku dengan stok terbanyak
SELECT judul, stok 
FROM buku 
ORDER BY stok DESC 
LIMIT 1;


-- B. FILTER DAN PENCARIAN (5 Query)

-- 1. Semua buku kategori Programming yang harga < 100.000
SELECT * FROM buku 
WHERE kategori = 'Programming' AND harga < 100000;

-- 2. Buku yang judulnya mengandung kata "PHP" atau "MySQL"
SELECT * FROM buku 
WHERE judul LIKE '%PHP%' OR judul LIKE '%MySQL%';

-- 3. Buku yang terbit tahun 2024
SELECT * FROM buku 
WHERE tahun_terbit = 2024;

-- 4. Buku yang stoknya antara 5-10
SELECT * FROM buku 
WHERE stok BETWEEN 5 AND 10;

-- 5. Buku yang pengarangnya "Budi Raharjo"
SELECT * FROM buku 
WHERE pengarang = 'Budi Raharjo';


-- C. GROUPING DAN AGREGASI (3 Query)

-- 1. Jumlah buku per kategori (dengan total stok per kategori)
SELECT kategori, COUNT(*) AS jumlah_judul, SUM(stok) AS total_stok 
FROM buku 
GROUP BY kategori;

-- 2. Rata-rata harga per kategori
SELECT kategori, AVG(harga) AS rata_rata_harga_kategori 
FROM buku 
GROUP BY kategori;

-- 3. Kategori dengan total nilai inventaris terbesar
SELECT kategori, SUM(harga * stok) AS total_inventaris 
FROM buku 
GROUP BY kategori 
ORDER BY total_inventaris DESC 
LIMIT 1;


-- D. UPDATE DATA (2 Query)
-- Catatan: Jika ada error 'safe update mode' di MySQL, 
-- pastikan eksekusi query update ini menggunakan primary key atau matikan safe mode sementara.

-- 1. Naikkan harga semua buku kategori Programming sebesar 5%
UPDATE buku 
SET harga = harga + (harga * 0.05) 
WHERE kategori = 'Programming';

-- 2. Tambah stok 10 untuk semua buku yang stoknya < 5
UPDATE buku 
SET stok = stok + 10 
WHERE stok < 5;


-- E. LAPORAN KHUSUS (2 Query)

-- 1. Daftar buku yang perlu restocking (stok < 5)
SELECT judul, stok, kategori 
FROM buku 
WHERE stok < 5;

-- 2. Top 5 buku termahal
SELECT judul, harga, pengarang, kategori 
FROM buku 
ORDER BY harga DESC 
LIMIT 5;
