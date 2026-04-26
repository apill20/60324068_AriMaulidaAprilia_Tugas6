# Tugas 1: Eksplorasi Database dengan Query

**Nama:** Ari Maulida Aprilia
**NIM:** 60324068

---

## A. Statistik Buku

### 1. Total Buku Seluruhnya
Query ini berfungsi untuk menghitung total judul buku dan total keseluruhan stok buku di perpustakaan.

```sql
SELECT COUNT(*) AS total_judul_buku, SUM(stok) AS total_keseluruhan_fisik
FROM buku;
```
![Gambar 1](screenshot/A-1.1.png)

### 2. Total nilai inventaris (sum harga × stok)
Query ini berfungsi untuk menghitung total keseluruhan nilai aset atau inventaris perpustakaan (harga dikali stok).

```sql
SELECT SUM(harga * stok) AS total_nilai_inventaris 
FROM buku;
```
![Gambar 2](screenshot/A-1.2.png)

### 3. Rata-rata harga buku
Query ini berfungsi untuk menghitung rata-rata harga dari seluruh buku yang ada di perpustakaan.

```sql
SELECT AVG(harga) AS rata_rata_harga 
FROM buku;
```
![Gambar 3](screenshot/A-1.3.png)

### 4. Buku termahal (tampilkan judul dan harga)
Query ini berfungsi untuk menampilkan judul dan harga dari satu buku yang harganya paling mahal.

```sql
SELECT judul, harga 
FROM buku 
ORDER BY harga DESC 
LIMIT 1;
```
![Gambar 4](screenshot/A-1.4.png)

### 5. Buku dengan stok terbanyak
Query ini berfungsi untuk menampilkan judul dan jumlah stok dari satu buku yang memiliki fisik stok paling banyak.

```sql
SELECT judul, stok 
FROM buku 
ORDER BY stok DESC 
LIMIT 1;
```
![Gambar 5](screenshot/A-1.5.png)



## B. Filter dan Pencarian

### 1. Semua buku kategori Programming yang harga < 100.000
Query ini memfilter buku yang masuk kategori Programming dengan batasan harga di bawah Rp 100.000.

```sql
SELECT * FROM buku 
WHERE kategori = 'Programming' AND harga < 100000;
```
![Gambar 6](screenshot/B-2.1.png)

### 2. Buku yang judulnya mengandung kata "PHP" atau "MySQL"
Query ini berfungsi untuk mencari dan menampilkan buku yang pada judulnya terdapat kata "PHP" atau "MySQL".

```sql
SELECT * FROM buku 
WHERE judul LIKE '%PHP%' OR judul LIKE '%MySQL%';
```
![Gambar 7](screenshot/B-2.2.png)

### 3. Buku yang terbit tahun 2024
Query ini berfungsi untuk menampilkan daftar buku yang diterbitkan secara spesifik pada tahun 2024.

```sql
SELECT * FROM buku 
WHERE tahun_terbit = 2024;
```
![Gambar 8](screenshot/B-2.3.png)

### 4. Buku yang stoknya antara 5-10
Query ini berfungsi untuk memfilter dan menampilkan buku-buku yang jumlah stoknya berada di antara rentang 5 hingga 10 buah.

```sql
SELECT * FROM buku 
WHERE stok BETWEEN 5 AND 10;
```
![Gambar 9](screenshot/B-2.4.png)

### 5. Buku yang pengarangnya "Budi Raharjo"
Query ini berfungsi untuk menampilkan semua buku yang ditulis secara spesifik oleh pengarang bernama "Budi Raharjo".

```sql
SELECT * FROM buku 
WHERE pengarang = 'Budi Raharjo';
```
![Gambar 10](screenshot/B-2.5.png)


## C. Grouping dan Agregasi

### 1. Jumlah buku per kategori (dengan total stok per kategori)
Query ini berfungsi untuk menghitung total judul buku dan total fisik stok untuk masing-masing kategori secara terpisah.

```sql
SELECT kategori, COUNT(*) AS jumlah_judul, SUM(stok) AS total_stok 
FROM buku 
GROUP BY kategori;
```
![Gambar 11](screenshot/C-3.1.png)

### 2. Rata-rata harga per kategori
Query ini berfungsi untuk menghitung dan menampilkan nilai rata-rata harga buku di setiap kategorinya.

```sql
SELECT kategori, AVG(harga) AS rata_rata_harga_kategori 
FROM buku 
GROUP BY kategori;
```
![Gambar 12](screenshot/C-3.2.png)

### 3. Kategori dengan total nilai inventaris terbesar
Query ini berfungsi untuk mencari satu kategori yang memiliki total nilai inventaris (total aset) yang paling besar.

```sql
SELECT kategori, SUM(harga * stok) AS total_inventaris 
FROM buku 
GROUP BY kategori 
ORDER BY total_inventaris DESC 
LIMIT 1;
```
![Gambar 13](screenshot/C-3.3.png)


## D. Update Data

### 1. Naikkan harga semua buku kategori Programming sebesar 5%
Query ini berfungsi untuk memodifikasi data dengan menaikkan harga sebesar 5% khusus untuk buku-buku berkategori Programming.

```sql
UPDATE buku 
SET harga = harga + (harga * 0.05) 
WHERE kategori = 'Programming';
```
![Gambar 14](screenshot/D-4.1.png)

### 2. Tambah stok 10 untuk semua buku yang stoknya < 5
Query ini berfungsi untuk menambahkan 10 buah stok baru pada buku-buku yang stok saat ininya terpantau kurang dari 5.

```sql
UPDATE buku 
SET stok = stok + 10 
WHERE stok < 5;
```
![Gambar 15](screenshot/D-4.2.png)

## E. Laporan Khusus
### 1. Daftar buku yang perlu restocking (stok < 5)
Query ini berfungsi untuk menampilkan daftar buku yang perlu segera di-restock karena stok fisiknya kurang dari 5.
Di gambar tidak muncul hasilnya karna di pperintah sebelumnya sudah di update jika ada yang stok nya kurang dari 5, artinya sekarang sudah tidak ada lagi yang stoknya kurang dari 5.

```sql
SELECT judul, stok, kategori 
FROM buku 
WHERE stok < 5;
```
![Gambar 16](screenshot/E-5.1.png)

### 2. Tambah stok 10 untuk semua buku yang stoknya < 5
Query ini berfungsi untuk menampilkan peringkat 5 buku dengan harga jual yang paling mahal.

```sql
SELECT judul, harga, pengarang, kategori 
FROM buku 
ORDER BY harga DESC 
LIMIT 5;
```
![Gambar 17](screenshot/E-5.2.png)


<br>
<br>
--------------------------------------------------------------------------------------------

# Tugas 2: Desain Database Lengkap

### 1. Struktur Tabel (Screenshot)
Berikut adalah screenshot struktur masing-masing tabel di dalam database:

a. Struktur Database
![Struktur Database](screenshot/struktur-database.png)

b. Struktur Tabel Buku:
![Struktur Tabel Buku](screenshot/struktur-buku.png)

c. Struktur Tabel Kategori Buku:
![Struktur Tabel Kategori Buku](screenshot/struktur-kategori.png)

d. Struktur Tabel Penerbit 
![Struktur Tabel Penerbit](screenshot/struktur-penerbit.png)

e. Struktur Tabel Rak
![Struktur Tabel Rak](screenshot/struktur-rak.png)

<br>

### 2. Data di setiap tabel
Berikut adalah screenshot data masing-masing tabel di dalam database:

a. Tabel Buku:
![Tabel Buku](screenshot/tabel-buku.png)

b. Tabel Kategori Buku:
![Tabel Kategori Buku](screenshot/tabel-kategori.png)

c. Tabel Penerbit:
![Tabel Penerbit](screenshot/tabel-penerbit.png)

d. Tabel Rak:
![Tabel Rak](screenshot/tabel-rak.png)


<br>

### 3. Hasil query JOIN
Berikut adalah screenshot Hasil query JOIN

A. JOIN untuk tampilkan buku dengan nama kategori dan penerbit
![Hasil JOIN A](screenshot/joinA.png)

B. Jumlah buku per kategori
![Hasil JOIN B](screenshot/joinB.png)

C. Jumlah buku per penerbit
![Hasil JOIN C](screenshot/joinC.png)

D. Buku beserta detail lengkap (kategori + penerbit)
![Hasil JOIN D](screenshot/joinD.png)


<br>

### 4. Gambar ERD
Berikut adalah gambar ERD antar tabel
![ERD](screenshot/erd.png)