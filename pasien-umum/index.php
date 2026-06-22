<?php
// File: pasien-umum/index.php

require_once '../config/koneksi.php';
require_once '../abstract/pasien.php';
require_once 'PasienUmum.php'; // SEKARANG CLASS INI SUDAH ADA LAGI!
require_once '../controllers/ManajemenRumahSakit.php';

$database = new Database();
$controller = new ManajemenRumahSakit($database->getConnection());
$search = isset($_GET['search']) ? trim($_GET['search']) : '';
$daftar_pasien = $controller->getPasienUmum($search);

// Logika Cetak Nota (Tab Baru & Terisolasi)
if (isset($_GET['cetak'])) {
    $idCetak = $_GET['cetak'];
    foreach ($daftar_pasien as $p) {
        if ($p->getIdPasien() == $idCetak) {
            echo "<!DOCTYPE html><html lang='id'><head><title>Cetak Nota</title>";
            echo "<link href='https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css' rel='stylesheet'>";
            echo "<link rel='stylesheet' href='https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css'>";
            echo "</head><body class='bg-light p-4 d-flex justify-content-center align-items-center flex-column'>";
            
            $p->cetakKlaimLayanan(); // Memicu polimorfisme cetak
            
            echo "<div class='mt-4 d-print-none'>";
            echo "<button class='btn btn-danger me-2' onclick='window.print()'><i class='bi bi-printer'></i> Cetak File</button>";
            echo "<button class='btn btn-secondary' onclick='window.close()'>Tutup Tab</button>";
            echo "</div>";
            echo "</body></html>";
            exit; // Hentikan render tabel di bawah
        }
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Kasir Pasien Umum - REMEDIS</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <style>
        body { background-color: #f8f9fa; font-family: 'Segoe UI', sans-serif; }
        .navbar-custom { background-color: #0d6efd; }
        .card { border: none; border-radius: 10px; }
        .table-hover tbody tr:hover { background-color: rgba(0,0,0,0.03); }
    </style>
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-dark navbar-custom shadow-sm mb-4">
    <div class="container">
        <a class="navbar-brand fw-bold" href="../index.php"><i class="bi bi-hospital me-2"></i>REMEDIS</a>
        <div class="collapse navbar-collapse">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item"><a class="nav-link" href="../index.php">Dashboard</a></li>
                <li class="nav-item"><a class="nav-link" href="../pasien-bpjs/index.php">BPJS</a></li>
                <li class="nav-item"><a class="nav-link" href="../pasien-asuransi/index.php">Asuransi</a></li>
                <li class="nav-item"><a class="nav-link active fw-bold" href="../pasien-umum/index.php">Umum</a></li>
            </ul>
        </div>
    </div>
</nav>

<div class="container">
    <a href="../index.php" class="btn btn-outline-secondary mb-3 rounded-pill px-3">
        <i class="bi bi-arrow-left me-1"></i> Kembali ke Menu Utama
    </a>
    
    <div class="card shadow-sm p-4 mb-4 border-top border-danger border-4">
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
            <h4 class="fw-bold text-danger m-0"><i class="bi bi-person-hearts me-2"></i>Kasir Pasien Umum</h4>
            <form method="GET" action="" class="d-flex" style="min-width: 300px;">
                <div class="input-group">
                    <input type="text" name="search" class="form-control" placeholder="Cari nama pasien..." value="<?= htmlspecialchars($search) ?>">
                    <button class="btn btn-danger" type="submit"><i class="bi bi-search"></i> Cari</button>
                    <?php if($search): ?><a href="index.php" class="btn btn-secondary"><i class="bi bi-x-circle"></i></a><?php endif; ?>
                </div>
            </form>
        </div>
    </div>

    <div class="card shadow-sm overflow-hidden mb-5">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-danger">
                    <tr>
                        <th class="ps-3">ID</th>
                        <th>Nama Pasien</th>
                        <th>Tanggal Masuk</th>
                        <th>NIK</th>
                        <th>Metode Bayar</th>
                        <th>Total Tagihan</th>
                        <th class="text-center pe-3">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($daftar_pasien)): ?>
                        <?php foreach ($daftar_pasien as $pasien): ?>
                            <tr>
                                <td class="ps-3 fw-bold text-secondary">#<?= $pasien->getIdPasien() ?></td>
                                <td class="fw-bold"><?= htmlspecialchars($pasien->getNama()) ?></td>
                                <td><?= htmlspecialchars($pasien->getTanggalMasuk()) ?></td>
                                <td><code><?= htmlspecialchars($pasien->getNik()) ?></code></td>
                                <td><span class='badge bg-dark'><?= htmlspecialchars($pasien->getMetodePembayaran()) ?></span></td>
                                <td class="text-danger fw-bold">Rp <?= number_format($pasien->hitungTotalBiaya(), 0, ',', '.') ?></td>
                                <td class="text-center pe-3">
                                    <a href="?search=<?= urlencode($search) ?>&cetak=<?= $pasien->getIdPasien() ?>" target="_blank" class="btn btn-sm btn-danger fw-semibold shadow-sm">
                                        <i class="bi bi-printer me-1"></i> Cetak Nota
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr><td colspan="7" class="text-center py-5 text-muted"><i class="bi bi-folder-x fs-1 d-block mb-2"></i>Data pasien tidak ditemukan.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
</body>
</html>