<?php
// Inisialisasi variabel untuk menampung nilai input dan error
$nama = $email = $telepon = $alamat = $jk = $tgl_lahir = $pekerjaan = "";
$errors = [];
$is_success = false;

// Proses data jika form disubmit
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    // 1. Validasi Nama Lengkap
    if (empty(trim($_POST["nama"]))) {
        $errors['nama'] = "Nama Lengkap wajib diisi.";
    } else {
        $nama = trim($_POST["nama"]);
        if (strlen($nama) < 3) {
            $errors['nama'] = "Nama Lengkap minimal 3 karakter.";
        }
    }

    // 2. Validasi Email
    if (empty(trim($_POST["email"]))) {
        $errors['email'] = "Email wajib diisi.";
    } else {
        $email = trim($_POST["email"]);
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors['email'] = "Format email tidak valid.";
        }
    }

    // 3. Validasi Telepon (08xxxxxxxxxx, 10-13 digit total)
    if (empty(trim($_POST["telepon"]))) {
        $errors['telepon'] = "Nomor Telepon wajib diisi.";
    } else {
        $telepon = trim($_POST["telepon"]);
        // Regex: Diawali 08, diikuti 8-11 angka (total 10-13 digit)
        if (!preg_match("/^08[0-9]{8,11}$/", $telepon)) {
            $errors['telepon'] = "Format telepon harus diawali 08 dengan panjang 10-13 digit.";
        }
    }

    // 4. Validasi Alamat
    if (empty(trim($_POST["alamat"]))) {
        $errors['alamat'] = "Alamat wajib diisi.";
    } else {
        $alamat = trim($_POST["alamat"]);
        if (strlen($alamat) < 10) {
            $errors['alamat'] = "Alamat minimal 10 karakter.";
        }
    }

    // 5. Validasi Jenis Kelamin
    if (empty($_POST["jk"])) {
        $errors['jk'] = "Jenis Kelamin wajib dipilih.";
    } else {
        $jk = $_POST["jk"];
    }

    // 6. Validasi Tanggal Lahir (Umur min 10 tahun)
    if (empty(trim($_POST["tgl_lahir"]))) {
        $errors['tgl_lahir'] = "Tanggal Lahir wajib diisi.";
    } else {
        $tgl_lahir = trim($_POST["tgl_lahir"]);
        $dob = new DateTime($tgl_lahir);
        $today = new DateTime('today');
        $age = $dob->diff($today)->y;
        
        if ($age < 10) {
            $errors['tgl_lahir'] = "Umur pendaftar minimal 10 tahun.";
        }
    }

    // 7. Validasi Pekerjaan
    if (empty($_POST["pekerjaan"])) {
        $errors['pekerjaan'] = "Pekerjaan wajib dipilih.";
    } else {
        $pekerjaan = $_POST["pekerjaan"];
    }

    // Jika array errors kosong, berarti semua validasi berhasil
    if (empty($errors)) {
        $is_success = true;
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrasi Anggota Perpustakaan</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container mt-5 mb-5" style="max-width: 800px;">
    <h2 class="mb-4 text-center">Registrasi Anggota Perpustakaan</h2>

    <?php if ($is_success): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <strong>Berhasil!</strong> Data registrasi Anda telah tersimpan.
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>

        <div class="card mb-4 shadow-sm border-success">
            <div class="card-header bg-success text-white">
                <h5 class="mb-0">Data Anggota Terdaftar</h5>
            </div>
            <div class="card-body">
                <table class="table table-borderless mb-0">
                    <tr><th width="150">Nama Lengkap</th><td>: <?= htmlspecialchars($nama) ?></td></tr>
                    <tr><th>Email</th><td>: <?= htmlspecialchars($email) ?></td></tr>
                    <tr><th>No. Telepon</th><td>: <?= htmlspecialchars($telepon) ?></td></tr>
                    <tr><th>Alamat</th><td>: <?= nl2br(htmlspecialchars($alamat)) ?></td></tr>
                    <tr><th>Jenis Kelamin</th><td>: <?= htmlspecialchars($jk) ?></td></tr>
                    <tr><th>Tanggal Lahir</th><td>: <?= htmlspecialchars($tgl_lahir) ?></td></tr>
                    <tr><th>Pekerjaan</th><td>: <?= htmlspecialchars($pekerjaan) ?></td></tr>
                </table>
            </div>
        </div>
    <?php endif; ?>

    <div class="card shadow-sm">
        <div class="card-body">
            <form method="POST" action="<?= htmlspecialchars($_SERVER["PHP_SELF"]); ?>" novalidate>
                
                <div class="mb-3">
                    <label for="nama" class="form-label">Nama Lengkap <span class="text-danger">*</span></label>
                    <input type="text" class="form-control <?= isset($errors['nama']) ? 'is-invalid' : '' ?>" id="nama" name="nama" value="<?= htmlspecialchars($nama) ?>">
                    <?php if (isset($errors['nama'])): ?>
                        <div class="invalid-feedback"><?= $errors['nama'] ?></div>
                    <?php endif; ?>
                </div>

                <div class="mb-3">
                    <label for="email" class="form-label">Alamat Email <span class="text-danger">*</span></label>
                    <input type="email" class="form-control <?= isset($errors['email']) ? 'is-invalid' : '' ?>" id="email" name="email" value="<?= htmlspecialchars($email) ?>">
                    <?php if (isset($errors['email'])): ?>
                        <div class="invalid-feedback"><?= $errors['email'] ?></div>
                    <?php endif; ?>
                </div>

                <div class="mb-3">
                    <label for="telepon" class="form-label">No. Telepon <span class="text-danger">*</span></label>
                    <input type="text" class="form-control <?= isset($errors['telepon']) ? 'is-invalid' : '' ?>" id="telepon" name="telepon" value="<?= htmlspecialchars($telepon) ?>" placeholder="Contoh: 081234567890">
                    <?php if (isset($errors['telepon'])): ?>
                        <div class="invalid-feedback"><?= $errors['telepon'] ?></div>
                    <?php endif; ?>
                </div>

                <div class="mb-3">
                    <label for="alamat" class="form-label">Alamat <span class="text-danger">*</span></label>
                    <textarea class="form-control <?= isset($errors['alamat']) ? 'is-invalid' : '' ?>" id="alamat" name="alamat" rows="3"><?= htmlspecialchars($alamat) ?></textarea>
                    <?php if (isset($errors['alamat'])): ?>
                        <div class="invalid-feedback"><?= $errors['alamat'] ?></div>
                    <?php endif; ?>
                </div>

                <div class="mb-3">
                    <label class="form-label d-block">Jenis Kelamin <span class="text-danger">*</span></label>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input <?= isset($errors['jk']) ? 'is-invalid' : '' ?>" type="radio" name="jk" id="jk_l" value="Laki-laki" <?= ($jk == 'Laki-laki') ? 'checked' : '' ?>>
                        <label class="form-check-label" for="jk_l">Laki-laki</label>
                    </div>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input <?= isset($errors['jk']) ? 'is-invalid' : '' ?>" type="radio" name="jk" id="jk_p" value="Perempuan" <?= ($jk == 'Perempuan') ? 'checked' : '' ?>>
                        <label class="form-check-label" for="jk_p">Perempuan</label>
                    </div>
                    <?php if (isset($errors['jk'])): ?>
                        <div class="invalid-feedback d-block"><?= $errors['jk'] ?></div>
                    <?php endif; ?>
                </div>

                <div class="mb-3">
                    <label for="tgl_lahir" class="form-label">Tanggal Lahir <span class="text-danger">*</span></label>
                    <input type="date" class="form-control <?= isset($errors['tgl_lahir']) ? 'is-invalid' : '' ?>" id="tgl_lahir" name="tgl_lahir" value="<?= htmlspecialchars($tgl_lahir) ?>">
                    <?php if (isset($errors['tgl_lahir'])): ?>
                        <div class="invalid-feedback"><?= $errors['tgl_lahir'] ?></div>
                    <?php endif; ?>
                </div>

                <div class="mb-4">
                    <label for="pekerjaan" class="form-label">Pekerjaan <span class="text-danger">*</span></label>
                    <select class="form-select <?= isset($errors['pekerjaan']) ? 'is-invalid' : '' ?>" id="pekerjaan" name="pekerjaan">
                        <option value="">-- Pilih Pekerjaan --</option>
                        <option value="Pelajar" <?= ($pekerjaan == 'Pelajar') ? 'selected' : '' ?>>Pelajar</option>
                        <option value="Mahasiswa" <?= ($pekerjaan == 'Mahasiswa') ? 'selected' : '' ?>>Mahasiswa</option>
                        <option value="Pegawai" <?= ($pekerjaan == 'Pegawai') ? 'selected' : '' ?>>Pegawai</option>
                        <option value="Lainnya" <?= ($pekerjaan == 'Lainnya') ? 'selected' : '' ?>>Lainnya</option>
                    </select>
                    <?php if (isset($errors['pekerjaan'])): ?>
                        <div class="invalid-feedback"><?= $errors['pekerjaan'] ?></div>
                    <?php endif; ?>
                </div>

                <div class="d-grid">
                    <button type="submit" class="btn btn-primary">Daftar Sekarang</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>