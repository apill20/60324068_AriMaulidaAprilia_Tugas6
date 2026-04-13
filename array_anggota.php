<?php
// Array anggota
$anggota_list = [
    [
        "id" => "AGT-001",
        "nama" => "Budi Santoso",
        "email" => "budi@email.com",
        "telepon" => "081234567890",
        "alamat" => "Jakarta",
        "tanggal_daftar" => "2024-01-15",
        "status" => "Aktif",
        "total_pinjaman" => 5
    ],
    [
        "id" => "AGT-002",
        "nama" => "Siti Aminah",
        "email" => "siti@email.com",
        "telepon" => "082345678901",
        "alamat" => "Bandung",
        "tanggal_daftar" => "2024-02-10",
        "status" => "Aktif",
        "total_pinjaman" => 8
    ],
    [
        "id" => "AGT-003",
        "nama" => "Andi Wijaya",
        "email" => "andi@email.com",
        "telepon" => "083456789012",
        "alamat" => "Surabaya",
        "tanggal_daftar" => "2024-03-05",
        "status" => "Non-Aktif",
        "total_pinjaman" => 2
    ],
    [
        "id" => "AGT-004",
        "nama" => "Rina Kartika",
        "email" => "rina@email.com",
        "telepon" => "084567890123",
        "alamat" => "Yogyakarta",
        "tanggal_daftar" => "2024-04-12",
        "status" => "Aktif",
        "total_pinjaman" => 10
    ],
    [
        "id" => "AGT-005",
        "nama" => "Dedi Saputra",
        "email" => "dedi@email.com",
        "telepon" => "085678901234",
        "alamat" => "Semarang",
        "tanggal_daftar" => "2024-05-20",
        "status" => "Non-Aktif",
        "total_pinjaman" => 3
    ]
];

// HITUNG STATISTIK
$total_anggota = count($anggota_list);
$aktif = 0;
$nonaktif = 0;
$total_pinjaman = 0;
$anggota_teraktif = $anggota_list[0];

foreach ($anggota_list as $anggota) {
    if ($anggota["status"] == "Aktif") {
        $aktif++;
    } else {
        $nonaktif++;
    }

    $total_pinjaman += $anggota["total_pinjaman"];

    if ($anggota["total_pinjaman"] > $anggota_teraktif["total_pinjaman"]) {
        $anggota_teraktif = $anggota;
    }
}

// Mencegah error pembagian jika array kosong
$persen_aktif = ($total_anggota > 0) ? ($aktif / $total_anggota) * 100 : 0;
$persen_nonaktif = ($total_anggota > 0) ? ($nonaktif / $total_anggota) * 100 : 0;
$rata_pinjaman = ($total_anggota > 0) ? $total_pinjaman / $total_anggota : 0;

// FILTER STATUS
$status_filter = $_GET['status'] ?? "Semua";
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <title>Data Anggota Perpustakaan</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<div class="container mt-4">

    <h2>Data Anggota Perpustakaan</h2>

    <form method="GET" class="mb-3">
        <select name="status" class="form-select w-25 d-inline">
            <option value="Semua" <?= $status_filter == 'Semua' ? 'selected' : '' ?>>Semua</option>
            <option value="Aktif" <?= $status_filter == 'Aktif' ? 'selected' : '' ?>>Aktif</option>
            <option value="Non-Aktif" <?= $status_filter == 'Non-Aktif' ? 'selected' : '' ?>>Non-Aktif</option>
        </select>
        <button class="btn btn-primary">Filter</button>
    </form>

    <div class="row">
        <div class="col-md-3">
            <div class="card bg-primary text-white">
                <div class="card-body">
                    Total Anggota
                    <h4><?= $total_anggota ?></h4>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card bg-success text-white">
                <div class="card-body">
                    Anggota Aktif
                    <h4><?= round($persen_aktif, 2) ?>%</h4>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card bg-danger text-white">
                <div class="card-body">
                    Non Aktif
                    <h4><?= round($persen_nonaktif, 2) ?>%</h4>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card bg-warning text-dark">
                <div class="card-body">
                    Rata-rata Pinjaman
                    <h4><?= round($rata_pinjaman, 2) ?></h4>
                </div>
            </div>
        </div>
    </div>

    <div class="alert alert-info mt-3">
        Anggota Teraktif: 
        <b><?= $anggota_teraktif["nama"] ?></b> 
        (<?= $anggota_teraktif["total_pinjaman"] ?> pinjaman)
    </div>

    <table class="table table-bordered table-striped">
        <tr>
            <th>ID</th>
            <th>Nama</th>
            <th>Email</th>
            <th>Telepon</th>
            <th>Alamat</th>
            <th>Tanggal Daftar</th>
            <th>Status</th>
            <th>Total Pinjaman</th>
        </tr>

        <?php
        foreach ($anggota_list as $anggota) {
            if ($status_filter == "Semua" || $anggota["status"] == $status_filter) {
                echo "<tr>
                    <td>{$anggota['id']}</td>
                    <td>{$anggota['nama']}</td>
                    <td>{$anggota['email']}</td>
                    <td>{$anggota['telepon']}</td>
                    <td>{$anggota['alamat']}</td>
                    <td>{$anggota['tanggal_daftar']}</td>
                    <td>{$anggota['status']}</td>
                    <td>{$anggota['total_pinjaman']}</td>
                </tr>";
            }
        }
        ?>
    </table>

</div>

</body>
</html>