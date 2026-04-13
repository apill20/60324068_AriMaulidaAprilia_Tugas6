<?php
// Include functions
require_once 'functions_anggota.php';

// Data anggota
$anggota_list = [
    ["id" => "AGT001", "nama" => "Budi Santoso", "email" => "budi@email.com", "telepon" => "081234567890", "alamat" => "Jakarta", "tanggal_daftar" => "2024-01-15", "status" => "Aktif", "total_pinjaman" => 5],
    ["id" => "AGT002", "nama" => "Siti Aminah", "email" => "siti@email.com", "telepon" => "082345678901", "alamat" => "Bandung", "tanggal_daftar" => "2024-02-10", "status" => "Aktif", "total_pinjaman" => 8],
    ["id" => "AGT003", "nama" => "Andi Wijaya", "email" => "andi@email.com", "telepon" => "083456789012", "alamat" => "Surabaya", "tanggal_daftar" => "2024-03-05", "status" => "Non-Aktif", "total_pinjaman" => 2],
    ["id" => "AGT004", "nama" => "Rina Kartika", "email" => "rina@email.com", "telepon" => "084567890123", "alamat" => "Yogyakarta", "tanggal_daftar" => "2024-04-20", "status" => "Aktif", "total_pinjaman" => 10],
    ["id" => "AGT005", "nama" => "Dedi Saputra", "email" => "dedi@email.com", "telepon" => "085678901234", "alamat" => "Semarang", "tanggal_daftar" => "2024-05-20", "status" => "Non-Aktif", "total_pinjaman" => 3]
];

// Fitur Bonus: Search & Sort
$keyword = isset($_GET['search']) ? $_GET['search'] : '';
if ($keyword != '') {
    $anggota_list = search_by_nama($anggota_list, $keyword);
}

$is_sorted = isset($_GET['sort']) && $_GET['sort'] == 'az';
if ($is_sorted) {
    $anggota_list = sort_by_nama($anggota_list);
}

// Eksekusi Functions
$total = hitung_total_anggota($anggota_list);
$total_aktif = hitung_anggota_aktif($anggota_list);
$rata = hitung_rata_rata_pinjaman($anggota_list);
$teraktif = cari_anggota_teraktif($anggota_list);

// Pemisahan Daftar Aktif dan Non-Aktif (Sesuai Instruksi)
$list_aktif = filter_by_status($anggota_list, "Aktif");
$list_nonaktif = filter_by_status($anggota_list, "Non-Aktif");
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistem Anggota Perpustakaan</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
</head>
<body class="bg-light">
    
    <div class="container mt-5 mb-5">
        <h1 class="mb-4"><i class="bi bi-people"></i> Sistem Anggota Perpustakaan</h1>
        
        <div class="row mb-4 g-3">
            <div class="col-md-4">
                <div class="card bg-primary text-white h-100 shadow-sm">
                    <div class="card-body">
                        <h5>Total Anggota</h5>
                        <h3><?= $total; ?></h3>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card bg-success text-white h-100 shadow-sm">
                    <div class="card-body">
                        <h5>Anggota Aktif</h5>
                        <h3><?= $total_aktif; ?></h3>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card bg-warning text-dark h-100 shadow-sm">
                    <div class="card-body">
                        <h5>Rata-rata Pinjaman</h5>
                        <h3><?= round($rata, 1); ?></h3>
                    </div>
                </div>
            </div>
        </div>

        <div class="card mb-4 shadow-sm border-0">
            <div class="card-body">
                <form method="GET" class="row g-2 align-items-center">
                    <div class="col-md-6">
                        <input type="text" name="search" class="form-control" placeholder="Cari nama anggota..." value="<?= htmlspecialchars($keyword); ?>">
                    </div>
                    <div class="col-md-4">
                        <div class="form-check pt-2">
                            <input class="form-check-input" type="checkbox" name="sort" value="az" id="sortAz" <?= $is_sorted ? 'checked' : '' ?>>
                            <label class="form-check-label" for="sortAz">Urutkan Nama A-Z</label>
                        </div>
                    </div>
                    <div class="col-md-2 text-end">
                        <button type="submit" class="btn btn-primary w-100"><i class="bi bi-search"></i> Cari</button>
                    </div>
                </form>
            </div>
        </div>
        
        <div class="card mb-4 shadow-sm border-0">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0"><i class="bi bi-table"></i> Daftar Semua Anggota</h5>
            </div>
            <div class="card-body p-0 table-responsive">
                <table class="table table-striped table-hover mb-0 align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>ID</th>
                            <th>Nama</th>
                            <th>Email</th>
                            <th>Status</th>
                            <th>Tanggal Daftar</th>
                            <th>Pinjaman</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if ($total > 0): ?>
                            <?php foreach($anggota_list as $anggota): ?>
                                <tr>
                                    <td><?= $anggota["id"]; ?></td>
                                    <td><?= $anggota["nama"]; ?></td>
                                    <td>
                                        <?= $anggota["email"]; ?> 
                                        <?= validasi_email($anggota['email']) ? '<i class="bi bi-check text-success"></i>' : '<i class="bi bi-x text-danger"></i>' ?>
                                    </td>
                                    <td>
                                        <?php if ($anggota["status"] == "Aktif"): ?>
                                            <span class="badge bg-success">Aktif</span>
                                        <?php else: ?>
                                            <span class="badge bg-danger">Non-Aktif</span>
                                        <?php endif; ?>
                                    </td>
                                    <td><?= format_tanggal_indo($anggota["tanggal_daftar"]); ?></td>
                                    <td><?= $anggota["total_pinjaman"]; ?></td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr><td colspan="6" class="text-center py-3">Tidak ada data</td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
        
        <?php if ($teraktif): ?>
        <div class="card mb-4 shadow-sm border-0">
            <div class="card-header bg-info text-white">
                <h5 class="mb-0"><i class="bi bi-trophy"></i> Anggota Teraktif</h5>
            </div>
            <div class="card-body">
                <h4 class="text-info"><?= $teraktif["nama"]; ?></h4>
                <p class="mb-0">Memiliki total pinjaman terbanyak: <strong><?= $teraktif["total_pinjaman"]; ?> buku</strong>.</p>
            </div>
        </div>
        <?php endif; ?>

        <div class="row g-4 mb-4">
            <div class="col-md-6">
                <div class="card shadow-sm border-0 h-100">
                    <div class="card-header bg-success text-white">
                        <h6 class="mb-0">Daftar Anggota Aktif</h6>
                    </div>
                    <ul class="list-group list-group-flush">
                        <?php foreach($list_aktif as $anggota): ?>
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <?= $anggota['nama'] ?>
                                <span class="badge bg-primary rounded-pill"><?= $anggota['total_pinjaman'] ?> Pinjaman</span>
                            </li>
                        <?php endforeach; ?>
                        <?php if(empty($list_aktif)) echo "<li class='list-group-item text-center'>Kosong</li>"; ?>
                    </ul>
                </div>
            </div>
            
            <div class="col-md-6">
                <div class="card shadow-sm border-0 h-100">
                    <div class="card-header bg-secondary text-white">
                        <h6 class="mb-0">Daftar Anggota Non-Aktif</h6>
                    </div>
                    <ul class="list-group list-group-flush">
                        <?php foreach($list_nonaktif as $anggota): ?>
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <?= $anggota['nama'] ?>
                                <span class="badge bg-danger rounded-pill">Non-Aktif</span>
                            </li>
                        <?php endforeach; ?>
                        <?php if(empty($list_nonaktif)) echo "<li class='list-group-item text-center'>Kosong</li>"; ?>
                    </ul>
                </div>
            </div>
        </div>

    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>