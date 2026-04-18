<?php
// Memulai session untuk fitur bonus (Recent Searches)
session_start();

// Jika ada perintah hapus_history di URL, kosongkan session
if (isset($_GET['hapus_history'])) {
    $_SESSION['recent_searches'] = [];
    // Redirect (arahkan ulang) agar URL-nya bersih kembali
    header("Location: search_advanced.php");
    exit;
}

// Data buku 
$buku_list = [
    ['kode' => 'B001', 'judul' => 'Belajar PHP Dasar', 'kategori' => 'Teknologi', 'pengarang' => 'Budi Santoso', 'penerbit' => 'Informatika', 'tahun' => 2020, 'harga' => 85000, 'stok' => 12],
    ['kode' => 'B002', 'judul' => 'Algoritma dan Struktur Data', 'kategori' => 'Teknologi', 'pengarang' => 'Rina Wijaya', 'penerbit' => 'Andi Publisher', 'tahun' => 2019, 'harga' => 95000, 'stok' => 5],
    ['kode' => 'B003', 'judul' => 'Basis Data Relasional', 'kategori' => 'Teknologi', 'pengarang' => 'Hendra Cipta', 'penerbit' => 'Informatika', 'tahun' => 2021, 'harga' => 110000, 'stok' => 0],
    ['kode' => 'B004', 'judul' => 'Sejarah Nusantara', 'kategori' => 'Sejarah', 'pengarang' => 'Ahmad Tohari', 'penerbit' => 'Balai Pustaka', 'tahun' => 2015, 'harga' => 65000, 'stok' => 20],
    ['kode' => 'B005', 'judul' => 'Novel Senja di Jakarta', 'kategori' => 'Fiksi', 'pengarang' => 'Mochtar Lubis', 'penerbit' => 'Gramedia', 'tahun' => 2018, 'harga' => 55000, 'stok' => 8],
    ['kode' => 'B006', 'judul' => 'Panduan Investasi Saham', 'kategori' => 'Keuangan', 'pengarang' => 'Djoko Susanto', 'penerbit' => 'Elex Media', 'tahun' => 2022, 'harga' => 125000, 'stok' => 15],
    ['kode' => 'B007', 'judul' => 'Matematika Diskrit', 'kategori' => 'Akademik', 'pengarang' => 'Rinaldi Munir', 'penerbit' => 'Informatika', 'tahun' => 2017, 'harga' => 105000, 'stok' => 3],
    ['kode' => 'B008', 'judul' => 'Kecerdasan Buatan (AI)', 'kategori' => 'Teknologi', 'pengarang' => 'Prof. Suyanto', 'penerbit' => 'Informatika', 'tahun' => 2023, 'harga' => 150000, 'stok' => 10],
    ['kode' => 'B009', 'judul' => 'Manajemen Proyek IT', 'kategori' => 'Teknologi', 'pengarang' => 'Siti Aminah', 'penerbit' => 'Andi Publisher', 'tahun' => 2020, 'harga' => 85000, 'stok' => 0],
    ['kode' => 'B010', 'judul' => 'Seni Berpikir Positif', 'kategori' => 'Pengembangan Diri', 'pengarang' => 'Ibrahim Elfiky', 'penerbit' => 'Zaman', 'tahun' => 2016, 'harga' => 45000, 'stok' => 25],
    ['kode' => 'B011', 'judul' => 'React JS untuk Pemula', 'kategori' => 'Teknologi', 'pengarang' => 'Budi Santoso', 'penerbit' => 'Codepolitan', 'tahun' => 2024, 'harga' => 99000, 'stok' => 30],
    ['kode' => 'B012', 'judul' => 'Perang Dunia II', 'kategori' => 'Sejarah', 'pengarang' => 'P.K. Ojong', 'penerbit' => 'Kompas', 'tahun' => 2010, 'harga' => 140000, 'stok' => 2],
];

// Dapatkan daftar kategori unik untuk dropdown
$kategori_list = array_unique(array_column($buku_list, 'kategori'));

// Ambil parameter GET
$keyword = trim($_GET['keyword'] ?? '');
$kategori = $_GET['kategori'] ?? '';
$min_harga = $_GET['min_harga'] ?? '';
$max_harga = $_GET['max_harga'] ?? '';
$tahun = $_GET['tahun'] ?? '';
$status = $_GET['status'] ?? 'semua';
$page = (int)($_GET['page'] ?? 1);
$export = $_GET['export'] ?? '';

// LOGIKA SORTING PINTAR (Otomatis menyesuaikan dengan kondisi)
$sort = $_GET['sort'] ?? 'otomatis'; 
if ($sort === 'otomatis') {
    // Jika user melakukan pencarian (keyword / kategori diisi), urutkan Judul A-Z
    // Jika baru pertama kali buka web (belum cari apa-apa), urutkan Kode Buku
    $is_filtering = (!empty($keyword) || !empty($kategori));
    $active_sort = $is_filtering ? 'judul_asc' : 'kode_asc';
} else {
    // Jika user manual memilih dari dropdown, gunakan pilihan manualnya
    $active_sort = $sort;
}

$current_year = date('Y');
$errors = [];

// VALIDASI
if ($min_harga !== '' && $max_harga !== '') {
    if ((int)$min_harga > (int)$max_harga) {
        $errors[] = "Harga minimum tidak boleh lebih besar dari harga maksimum.";
    }
}
if ($tahun !== '') {
    if ((int)$tahun < 1900 || (int)$tahun > $current_year) {
        $errors[] = "Tahun terbit harus antara 1900 dan $current_year.";
    }
}

// BONUS: Save pencarian ke session (Recent Searches)
if (!empty($keyword) && empty($errors) && empty($export)) {
    if (!isset($_SESSION['recent_searches'])) {
        $_SESSION['recent_searches'] = [];
    }
    if (!in_array($keyword, $_SESSION['recent_searches'])) {
        array_unshift($_SESSION['recent_searches'], $keyword);
        $_SESSION['recent_searches'] = array_slice($_SESSION['recent_searches'], 0, 5);
    }
}

// FILTERING LOGIC
$hasil = [];
if (empty($errors)) {
    foreach ($buku_list as $buku) {
        // Filter Keyword
        $match_keyword = true;
        if ($keyword !== '') {
            $match_keyword = (stripos($buku['judul'], $keyword) !== false || stripos($buku['pengarang'], $keyword) !== false || stripos($buku['penerbit'], $keyword) !== false);
        }
        // Filter Lainnya
        $match_kategori = ($kategori === '') || ($buku['kategori'] === $kategori);
        $match_min_harga = ($min_harga === '') || ($buku['harga'] >= (int)$min_harga);
        $match_max_harga = ($max_harga === '') || ($buku['harga'] <= (int)$max_harga);
        $match_tahun = ($tahun === '') || ($buku['tahun'] == (int)$tahun);
        
        $match_status = true;
        if ($status === 'tersedia') $match_status = ($buku['stok'] > 0);
        if ($status === 'habis') $match_status = ($buku['stok'] == 0);

        if ($match_keyword && $match_kategori && $match_min_harga && $match_max_harga && $match_tahun && $match_status) {
            $hasil[] = $buku;
        }
    }
} else {
    $hasil = $buku_list; 
}

// SORTING LOGIC (Sudah diperbaiki, tidak ada duplikat)
usort($hasil, function($a, $b) use ($active_sort) {
    switch ($active_sort) {
        case 'kode_desc': return strcmp($b['kode'], $a['kode']);
        case 'judul_asc': return strcmp($a['judul'], $b['judul']);
        case 'judul_desc': return strcmp($b['judul'], $a['judul']);
        case 'harga_asc': return $a['harga'] <=> $b['harga'];
        case 'harga_desc': return $b['harga'] <=> $a['harga'];
        case 'tahun_asc': return $a['tahun'] <=> $b['tahun'];
        case 'tahun_desc': return $b['tahun'] <=> $a['tahun'];
        case 'kode_asc': 
        default: 
            return strcmp($a['kode'], $b['kode']); 
    }
});

// BONUS: Export ke CSV
if ($export === 'csv' && empty($errors)) {
    header('Content-Type: text/csv; charset=utf-8');
    header('Content-Disposition: attachment; filename="data_buku_'.date('YmdHis').'.csv"');
    $output = fopen('php://output', 'w');
    fputcsv($output, ['Kode', 'Judul', 'Kategori', 'Pengarang', 'Penerbit', 'Tahun', 'Harga', 'Stok']);
    foreach ($hasil as $buku) {
        fputcsv($output, $buku);
    }
    fclose($output);
    exit;
}

// PAGINATION LOGIC (10 item per page)
$total_items = count($hasil);
$per_page = 10;
$total_pages = ceil($total_items / $per_page);

if ($page < 1) $page = 1;
if ($page > $total_pages && $total_pages > 0) $page = $total_pages;

$offset = ($page - 1) * $per_page;
$hasil_paged = array_slice($hasil, $offset, $per_page);

// Helper Highlight Text
function highlight_keyword($text, $keyword) {
    if (empty($keyword)) return htmlspecialchars($text);
    $escaped_keyword = preg_quote($keyword, '/');
    return preg_replace('/(' . $escaped_keyword . ')/i', '<mark class="p-0 bg-warning">\1</mark>', htmlspecialchars($text));
}

// Helper URL Query
function build_url($params_to_merge) {
    $query = array_merge($_GET, $params_to_merge);
    unset($query['export']); 
    return '?' . http_build_query($query);
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistem Pencarian Buku Lanjutan</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
</head>
<body class="bg-light pb-5">

<div class="container mt-4">
    <h2 class="mb-4"><i class="bi bi-search"></i> Sistem Pencarian Buku</h2>

    <?php if (!empty($errors)): ?>
        <div class="alert alert-danger">
            <ul class="mb-0">
                <?php foreach ($errors as $err): ?>
                    <li><?= $err ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>

    <div class="row">
        <div class="col-lg-3 col-md-4 mb-4">
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white">
                    <h5 class="card-title mb-0">Filter Pencarian</h5>
                </div>
                <div class="card-body">
                    <form method="GET" action="<?= htmlspecialchars($_SERVER['PHP_SELF']) ?>">
                        
                        <div class="mb-3">
                            <label class="form-label fw-bold">Kata Kunci</label>
                            <input type="text" name="keyword" class="form-control" value="<?= htmlspecialchars($keyword) ?>" placeholder="Judul / Pengarang / Penerbit...">
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">Kategori</label>
                            <select name="kategori" class="form-select">
                                <option value="">Semua Kategori</option>
                                <?php foreach ($kategori_list as $kat): ?>
                                    <option value="<?= $kat ?>" <?= ($kategori === $kat) ? 'selected' : '' ?>><?= $kat ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">Range Harga (Rp)</label>
                            <div class="input-group mb-2">
                                <span class="input-group-text">Min</span>
                                <input type="number" name="min_harga" class="form-control" value="<?= htmlspecialchars($min_harga) ?>" min="0">
                            </div>
                            <div class="input-group">
                                <span class="input-group-text">Max</span>
                                <input type="number" name="max_harga" class="form-control" value="<?= htmlspecialchars($max_harga) ?>" min="0">
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">Tahun Terbit</label>
                            <input type="number" name="tahun" class="form-control" value="<?= htmlspecialchars($tahun) ?>" min="1900" max="<?= $current_year ?>">
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold d-block">Status Ketersediaan</label>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="status" id="status1" value="semua" <?= ($status === 'semua') ? 'checked' : '' ?>>
                                <label class="form-check-label" for="status1">Semua</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="status" id="status2" value="tersedia" <?= ($status === 'tersedia') ? 'checked' : '' ?>>
                                <label class="form-check-label" for="status2">Tersedia</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="status" id="status3" value="habis" <?= ($status === 'habis') ? 'checked' : '' ?>>
                                <label class="form-check-label" for="status3">Habis</label>
                            </div>
                        </div>

                        <div class="mb-4">
                            <label class="form-label fw-bold">Urutkan Berdasarkan</label>
                            <select name="sort" class="form-select border-primary">
                                <option value="otomatis" <?= ($sort === 'otomatis') ? 'selected' : '' ?>>Otomatis (Rekomendasi)</option>
                                <option value="kode_asc" <?= ($sort === 'kode_asc') ? 'selected' : '' ?>>Kode Buku (Kecil ke Besar)</option>
                                <option value="kode_desc" <?= ($sort === 'kode_desc') ? 'selected' : '' ?>>Kode Buku (Besar ke Kecil)</option>
                                <option value="judul_asc" <?= ($sort === 'judul_asc') ? 'selected' : '' ?>>Judul (A-Z)</option>
                                <option value="judul_desc" <?= ($sort === 'judul_desc') ? 'selected' : '' ?>>Judul (Z-A)</option>
                                <option value="harga_asc" <?= ($sort === 'harga_asc') ? 'selected' : '' ?>>Harga (Termurah)</option>
                                <option value="harga_desc" <?= ($sort === 'harga_desc') ? 'selected' : '' ?>>Harga (Termahal)</option>
                                <option value="tahun_desc" <?= ($sort === 'tahun_desc') ? 'selected' : '' ?>>Tahun (Terbaru)</option>
                                <option value="tahun_asc" <?= ($sort === 'tahun_asc') ? 'selected' : '' ?>>Tahun (Terlama)</option>
                            </select>
                        </div>

                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary"><i class="bi bi-funnel"></i> Terapkan Filter</button>
                            <a href="search_advanced.php" class="btn btn-outline-secondary"><i class="bi bi-arrow-counterclockwise"></i> Reset</a>
                        </div>
                    </form>
                </div>
            </div>

            <?php if (!empty($_SESSION['recent_searches'])): ?>
                <div class="card mt-3 shadow-sm">
                    <div class="card-header bg-light d-flex justify-content-between align-items-center">
                        <h6 class="mb-0 text-muted"><i class="bi bi-clock-history"></i> Pencarian Terakhir</h6>
                        <a href="?hapus_history=1" class="text-danger text-decoration-none" title="Hapus Riwayat">
                            <i class="bi bi-trash"></i>
                        </a>
                    </div>
                    <ul class="list-group list-group-flush">
                        <?php foreach ($_SESSION['recent_searches'] as $rs): ?>
                            <li class="list-group-item p-2">
                                <a href="?keyword=<?= urlencode($rs) ?>&status=semua&sort=otomatis" class="text-decoration-none">
                                    <?= htmlspecialchars($rs) ?>
                                </a>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            <?php endif; ?>

        </div>

        <div class="col-lg-9 col-md-8">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h5 class="mb-0">Ditemukan: <span class="badge bg-success"><?= $total_items ?></span> buku</h5>
                
                <a href="<?= build_url(['export' => 'csv']) ?>" class="btn btn-success btn-sm <?= ($total_items == 0 || !empty($errors)) ? 'disabled' : '' ?>">
                    <i class="bi bi-file-earmark-excel"></i> Export CSV
                </a>
            </div>

            <div class="card shadow-sm">
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover table-striped mb-0 text-nowrap">
                            <thead class="table-primary">
                                <tr>
                                    <th>Kode</th>
                                    <th>Judul Buku</th>
                                    <th>Kategori</th>
                                    <th>Pengarang</th>
                                    <th>Penerbit</th>
                                    <th>Tahun</th>
                                    <th>Harga</th>
                                    <th>Stok</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (count($hasil_paged) > 0): ?>
                                    <?php foreach ($hasil_paged as $buku): ?>
                                        <tr>
                                            <td><span class="badge bg-warning text-dark"><?= $buku['kode'] ?></span></td>
                                            <td><strong><?= highlight_keyword($buku['judul'], $keyword) ?></strong></td>
                                            <td><?= $buku['kategori'] ?></td>
                                            <td><?= highlight_keyword($buku['pengarang'], $keyword) ?></td>
                                            <td><?= $buku['penerbit'] ?></td>
                                            <td><?= $buku['tahun'] ?></td>
                                            <td>Rp <?= number_format($buku['harga'], 0, ',', '.') ?></td>
                                            <td>
                                                <?php if ($buku['stok'] > 0): ?>
                                                    <span class="text-success fw-bold"><?= $buku['stok'] ?></span>
                                                <?php else: ?>
                                                    <span class="badge bg-danger">Habis</span>
                                                <?php endif; ?>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="8" class="text-center py-4 text-muted">
                                            <i class="bi bi-emoji-frown fs-1"></i><br>
                                            Maaf, tidak ada buku yang sesuai dengan kriteria pencarian Anda.
                                        </td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <?php if ($total_pages > 1): ?>
                <nav class="mt-4">
                    <ul class="pagination justify-content-center">
                        <li class="page-item <?= ($page <= 1) ? 'disabled' : '' ?>">
                            <a class="page-link" href="<?= build_url(['page' => $page - 1]) ?>">Sebelumnya</a>
                        </li>
                        
                        <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                            <li class="page-item <?= ($page == $i) ? 'active' : '' ?>">
                                <a class="page-link" href="<?= build_url(['page' => $i]) ?>"><?= $i ?></a>
                            </li>
                        <?php endfor; ?>

                        <li class="page-item <?= ($page >= $total_pages) ? 'disabled' : '' ?>">
                            <a class="page-link" href="<?= build_url(['page' => $page + 1]) ?>">Selanjutnya</a>
                        </li>
                    </ul>
                </nav>
            <?php endif; ?>

        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>