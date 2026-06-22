<?php
// 1. Panggil semua file pondasi yang dibutuhkan
require_once '../config/koneksi.php';
require_once '../abstract/pasien.php'; 
require_once 'PasienAsuransi.php'; 
require_once '../controllers/ManajemenRumahSakit.php';

// 2. Buat objek koneksi database
$database = new Database();

// 3. Masukkan koneksi database tersebut ke dalam Controller (INI SOLUSI ERROR-NYA!)
$controller = new ManajemenRumahSakit($database->getConnection());

// Ambil keyword pencarian jika ada
$search = isset($_GET['search']) ? $_GET['search'] : '';

// Ambil daftar pasien asuransi dari controller
$daftar_pasien = $controller->getPasienAsuransi($search);

// Hitung statistik kecil untuk dashboard pemanis
$totalPasien = count($daftar_pasien);
$totalLimit = 0;
foreach ($daftar_pasien as $p) {
    $totalLimit += $p->getLimitCover();
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistem Kasir Asuransi Swasta - REMEDIS</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700&display=swap" rel="stylesheet">
    
    <style>
        body {
            font-family: 'Inter', sans-serif;
            background-color: #f4f7f6;
        }
        .navbar-custom {
            background: linear-gradient(135deg, #2c3e50, #1e272e);
        }
        .card-stats {
            border: none;
            border-radius: 12px;
            transition: transform 0.2s;
        }
        .card-stats:hover {
            transform: translateY(-5px);
        }
        .main-table-card {
            border: none;
            border-radius: 16px;
            box-shadow: 0 8px 24px rgba(0,0,0,0.05);
            overflow: hidden;
        }
        .table thead {
            background-color: #2c3e50;
            color: #ffffff;
        }
        .btn-action {
            border-radius: 8px;
            transition: all 0.3s;
        }
        .btn-action:hover {
            box-shadow: 0 4px 12px rgba(241, 196, 15, 0.4);
        }
    </style>
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-dark navbar-custom shadow-sm mb-5">
    <div class="container">
        <a class="navbar-brand fw-bold" href="#">
            <i class="fa-solid fa-square-h text-warning me-2"></i>REMEDIS <span class="fw-light text-muted">| Swasta</span>
        </a>
        <span class="navbar-text text-white-50 small">
            <i class="fa-solid fa-user-tie me-1"></i> Kasir Panel (Anggota 3)
        </span>
    </div>
</nav>

<div class="container">
    <div class="row mb-4">
        <div class="col-md-6 mb-3 mb-md-0">
            <div class="card card-stats bg-white text-dark p-3 shadow-sm d-flex flex-row align-items-center justify-content-between">
                <div>
                    <h6 class="text-muted fw-light mb-1">Pasien Terdata</h6>
                    <h3 class="fw-bold mb-0"><?php echo $totalPasien; ?> <span class="fs-6 text-muted fw-normal">Orang</span></h3>
                </div>
                <div class="bg-light p-3 rounded-circle text-primary">
                    <i class="fa-solid fa-users-hospital fa-2x"></i>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card card-stats bg-white text-dark p-3 shadow-sm d-flex flex-row align-items-center justify-content-between">
                <div>
                    <h6 class="text-muted fw-light mb-1">Total Limit Terdaftar</h6>
                    <h3 class="fw-bold text-success mb-0">Rp <?php echo number_format($totalLimit, 0, ',', '.'); ?></h3>
                </div>
                <div class="bg-light p-3 rounded-circle text-success">
                    <i class="fa-solid fa-shield-halved fa-2x"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="card border-0 shadow-sm rounded-4 mb-4">
        <div class="card-body p-4">
            <form method="GET" action="">
                <div class="row g-2">
                    <div class="col-md-9">
                        <div class="input-group">
                            <span class="input-group-text bg-light border-end-0 text-muted"><i class="fa-solid fa-magnifying-glass"></i></span>
                            <input type="text" name="search" class="form-control bg-light border-start-0" placeholder="Ketik nama pasien atau ID untuk memfilter data..." value="<?php echo htmlspecialchars($search); ?>">
                        </div>
                    </div>
                    <div class="col-md-3 d-grid">
                        <div class="btn-group">
                            <button class="btn btn-primary fw-bold" type="submit">Cari</button>
                            <?php if ($search): ?>
                                <a href="index.php" class="btn btn-outline-secondary"><i class="fa-solid fa-rotate-left"></i></a>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <div class="card main-table-card">
        <div class="card-header bg-dark text-white p-3 d-flex justify-content-between align-items-center">
            <h5 class="mb-0 fw-bold"><i class="fa-solid fa-table-list me-2"></i>Manajemen Billing Asuransi</h5>
            <span class="badge bg-warning text-dark px-3 py-2 rounded-pill fw-bold">Live Data</span>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead>
                        <tr class="text-uppercase small" style="font-size: 12px; letter-spacing: 0.5px;">
                            <th class="ps-4">ID Pasien</th>
                            <th>Nama Pasien</th>
                            <th>Usia</th>
                            <th>Tgl Masuk</th>
                            <th>Durasi</th>
                            <th>Provider</th>
                            <th>No. Polis</th>
                            <th>Limit Cover</th>
                            <th class="text-center pe-4">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($daftar_pasien)): ?>
                            <?php foreach ($daftar_pasien as $pasien): ?>
                                <tr>
                                    <td class="ps-4 fw-bold text-secondary">#<?php echo $pasien->getIdPasien(); ?></td>
                                    <td class="fw-semibold text-dark"><?php echo $pasien->getNama(); ?></td>
                                    <td><?php echo $pasien->getUsia(); ?> Thn</td>
                                    <td><small class="text-muted"><i class="fa-regular fa-calendar me-1"></i><?php echo $pasien->getTanggalMasuk(); ?></small></td>
                                    <td><span class="badge bg-light text-dark border"><i class="fa-regular fa-clock me-1 text-primary"></i><?php echo $pasien->getLamaRawat(); ?> Hari</span></td>
                                    <td><span class="fw-bold text-primary"><?php echo $pasien->getNamaProvider(); ?></span></td>
                                    <td><code><?php echo $pasien->getNomorPolis(); ?></code></td>
                                    <td class="text-success fw-bold">Rp <?php echo number_format($pasien->getLimitCover(), 0, ',', '.'); ?></td>
                                    <td class="text-center pe-4">
                                        <a href="?cetak=<?php echo $pasien->getIdPasien(); ?>&search=<?php echo urlencode($search); ?>" class="btn btn-warning btn-sm btn-action fw-bold px-3">
                                            <i class="fa-solid fa-print me-1"></i> Cetak Nota
                                        </a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="9" class="text-center py-5 text-muted">
                                    <i class="fa-solid fa-folder-open fa-3x mb-3 text-light"></i>
                                    <p class="mb-0">Data pasien asuransi swasta tidak ditemukan.</p>
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <?php
    if (isset($_GET['cetak'])) {
        $idCetak = $_GET['cetak'];
        $pasienTerpilih = null;

        // Cari pasien dari memori tanpa SELECT kueri ulang ke MySQL
        foreach ($daftar_pasien as $p) {
            if ($p->getIdPasien() == $idCetak) {
                $pasienTerpilih = $p;
                break;
            }
        }

        // Jika ketemu, panggil fungsi cetak HTML dari kelas PasienAsuransiSwasta
        if ($pasienTerpilih !== null) {
            echo "<div class='mt-5 d-flex justify-content-center'>";
            $pasienTerpilih->cetakKlaimLayanan();
            echo "</div>";
        } else {
            echo "<div class='alert alert-danger mt-4 text-center border-0 shadow-sm'><i class='fa-solid fa-circle-exclamation me-2'></i>Data pasien untuk nota tidak ditemukan!</div>";
        }
    }
    ?>
</div>

<footer class="text-center text-muted small my-5">
    &copy; 2026 REMEDIS Kelompok 2 Kasus B. All rights reserved.
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>