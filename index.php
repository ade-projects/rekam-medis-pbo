<?php
require_once __DIR__ . '/config/koneksi.php';
require_once __DIR__ . '/abstract/pasien.php';
require_once __DIR__ . '/pasien-bpjs/PasienBPJS.php';
require_once __DIR__ . '/pasien-asuransi/PasienAsuransi.php';
require_once __DIR__ . '/pasien-umum/PasienUmum.php';
require_once __DIR__ . '/controllers/ManajemenRumahSakit.php';

$database = new Database();
$controller = new ManajemenRumahSakit($database->getConnection());

// Mengambil grand total polimorfisme
$grandTotal = $controller->hitungTotalEstimasiPendapatanHariIni();
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Utama - REMEDIS</title>
    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <style>
        body { background-color: #f8f9fa; font-family: 'Segoe UI', sans-serif; }
        .navbar-custom { background-color: #0d6efd; }
        .menu-card { transition: transform 0.2s, box-shadow 0.2s; border-radius: 12px; }
        .menu-card:hover { transform: translateY(-5px); box-shadow: 0 10px 20px rgba(0,0,0,0.1)!important; }
    </style>
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-dark navbar-custom mb-5 shadow-sm">
    <div class="container">
        <a class="navbar-brand fw-bold" href="index.php"><i class="bi bi-hospital me-2"></i>REMEDIS</a>
    </div>
</nav>

<div class="container">
    <div class="row mb-5">
        <div class="col-12">
            <div class="card bg-primary text-white text-center p-5 shadow border-0" style="border-radius: 15px;">
                <h3 class="fw-light mb-3">Estimasi Pendapatan Total Hari Ini</h3>
                <h1 class="display-3 fw-bold mb-3">Rp <?= number_format($grandTotal, 0, ',', '.') ?></h1>
            </div>
        </div>
    </div>

    <h4 class="mb-4 text-secondary fw-bold border-bottom pb-2">Menu Layanan Kassa</h4>
    
    <div class="row g-4">
        <!-- BPJS Card -->
        <div class="col-md-4">
            <a href="pasien-bpjs/index.php" class="text-decoration-none">
                <div class="card menu-card shadow-sm h-100 border-0 border-bottom border-success border-5">
                    <div class="card-body text-center p-4">
                        <div class="bg-success text-white rounded-circle d-inline-flex justify-content-center align-items-center mb-3" style="width: 70px; height: 70px;">
                            <i class="bi bi-shield-plus fs-1"></i>
                        </div>
                        <h4 class="text-dark fw-bold">Pasien BPJS</h4>
                        <p class="text-muted">Kelola tagihan dan cetak nota klaim pasien subsidi BPJS (10%).</p>
                    </div>
                </div>
            </a>
        </div>
        
        <!-- Asuransi Card -->
        <div class="col-md-4">
            <a href="pasien-asuransi/index.php" class="text-decoration-none">
                <div class="card menu-card shadow-sm h-100 border-0 border-bottom border-warning border-5">
                    <div class="card-body text-center p-4">
                        <div class="bg-warning text-dark rounded-circle d-inline-flex justify-content-center align-items-center mb-3" style="width: 70px; height: 70px;">
                            <i class="bi bi-shield-check fs-1"></i>
                        </div>
                        <h4 class="text-dark fw-bold">Asuransi Swasta</h4>
                        <p class="text-muted">Kelola tagihan dan cetak nota pasien dengan proteksi limit asuransi.</p>
                    </div>
                </div>
            </a>
        </div>
        
        <!-- Umum Card -->
        <div class="col-md-4">
            <a href="pasien-umum/index.php" class="text-decoration-none">
                <div class="card menu-card shadow-sm h-100 border-0 border-bottom border-danger border-5">
                    <div class="card-body text-center p-4">
                        <div class="bg-danger text-white rounded-circle d-inline-flex justify-content-center align-items-center mb-3" style="width: 70px; height: 70px;">
                            <i class="bi bi-person-hearts fs-1"></i>
                        </div>
                        <h4 class="text-dark fw-bold">Pasien Umum</h4>
                        <p class="text-muted">Kelola tagihan dan cetak nota pembayaran mandiri pasien umum.</p>
                    </div>
                </div>
            </a>
        </div>
    </div>
</div>
</body>
</html>
