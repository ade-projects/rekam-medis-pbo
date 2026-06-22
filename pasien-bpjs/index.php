<?php
/**
 * Halaman Utama - Data Pasien BPJS
 * 
 * Menampilkan daftar pasien BPJS dalam bentuk tabel dengan fitur pencarian
 * dan tombol cetak nota untuk setiap pasien.
 */

// Include file yang diperlukan
require_once '../config/koneksi.php';
require_once '../abstract/pasien.php';
require_once '../pasien-bpjs/PasienBPJS.php';
require_once '../controllers/ManajemenRumahSakit.php';

// Inisialisasi koneksi database
$database = new Database();
$dbConnection = $database->getConnection();

// Inisialisasi controller
$controller = new ManajemenRumahSakit($dbConnection);

// Ambil parameter pencarian dari URL (jika ada)
$search = isset($_GET['search']) ? trim($_GET['search']) : '';

// Ambil data pasien BPJS dari controller (dengan atau tanpa filter pencarian)
$daftar_pasien = $controller->getPasienBPJS($search);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Pasien BPJS - REMEDIS</title>
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css" rel="stylesheet">
    
    <style>
        /* Custom styling tambahan */
        .table-hover tbody tr:hover {
            background-color: #e3f2fd !important;
            cursor: pointer;
        }
        
        .badge-bpjs {
            background-color: #0d6efd;
            color: white;
        }
        
        .card-header-custom {
            background: linear-gradient(135deg, #0d6efd, #0a58ca);
            color: white;
        }
        
        .table-container {
            max-height: 550px;
            overflow-y: auto;
        }
        
        .search-form {
            max-width: 400px;
        }
        
        .nota-container {
            background: #f8f9fa;
            border-radius: 8px;
            padding: 15px;
            margin-top: 20px;
        }
        
        /* Styling untuk hasil pencarian */
        .search-info {
            color: #6c757d;
            font-size: 0.9rem;
        }
        
        /* Animasi fade untuk nota */
        .nota-fade {
            animation: fadeIn 0.5s ease-in;
        }
        
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }
        
        /* Styling tombol cetak */
        .btn-cetak {
            transition: all 0.3s ease;
        }
        
        .btn-cetak:hover {
            transform: scale(1.05);
        }
        
        /* Styling status kosong */
        .empty-state {
            padding: 40px 20px;
            text-align: center;
        }
        
        .empty-state i {
            font-size: 4rem;
            color: #dee2e6;
        }
    </style>
</head>
<body>
    <div class="container-fluid py-4">
        <!-- Header Utama -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="card shadow-sm">
                    <div class="card-header card-header-custom py-3">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h4 class="mb-0 fw-bold">
                                    <i class="bi bi-hospital"></i> Data Pasien BPJS
                                </h4>
                                <small class="text-light">Sistem Manajemen Layanan Medis (REMEDIS)</small>
                            </div>
                            <div>
                                <span class="badge bg-light text-dark">
                                    <i class="bi bi-people"></i> Total: <?= count($daftar_pasien) ?> pasien
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <!-- Form Pencarian -->
                        <div class="row g-3 align-items-center">
                            <div class="col-md-6">
                                <form method="GET" action="" class="search-form">
                                    <div class="input-group">
                                        <span class="input-group-text bg-light">
                                            <i class="bi bi-search"></i>
                                        </span>
                                        <input 
                                            type="text" 
                                            class="form-control" 
                                            name="search" 
                                            placeholder="Cari pasien berdasarkan nama..."
                                            value="<?= htmlspecialchars($search) ?>"
                                            aria-label="Cari pasien"
                                        >
                                        <button class="btn btn-primary" type="submit">
                                            <i class="bi bi-search"></i> Cari
                                        </button>
                                        <?php if (!empty($search)): ?>
                                            <a href="?" class="btn btn-outline-secondary">
                                                <i class="bi bi-x-circle"></i> Reset
                                            </a>
                                        <?php endif; ?>
                                    </div>
                                </form>
                            </div>
                            <div class="col-md-6 text-md-end">
                                <?php if (!empty($search)): ?>
                                    <span class="search-info">
                                        <i class="bi bi-info-circle"></i> 
                                        Menampilkan hasil untuk: <strong>"<?= htmlspecialchars($search) ?>"</strong>
                                    </span>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- ============================================ -->
        <!-- LOGIKA CETAK NOTA (ARRAY FILTERING) - Sesuai Guideline -->
        <!-- ============================================ -->
        <?php
        // Cek apakah kasir menekan tombol cetak (mengirim parameter 'cetak' berisi ID pasien)
        if (isset($_GET['cetak'])) {
            $idCetak = intval($_GET['cetak']);
            $pasienTerpilih = null;

            // Cari pasien dari memori array yang ditarik oleh Controller tadi
            foreach ($daftar_pasien as $p) {
                if ($p->getIdPasien() == $idCetak) {
                    $pasienTerpilih = $p;
                    break;
                }
            }

            // Jika ketemu, eksekusi method cetakKlaimLayanan()
            if ($pasienTerpilih !== null) {
                echo '<div class="row mb-4">';
                echo '<div class="col-12">';
                echo '<div class="nota-container nota-fade">';
                // Tampilkan HTML Nota melalui method cetakKlaimLayanan()
                $pasienTerpilih->cetakKlaimLayanan();
                echo '<div class="text-center mt-3">';
                echo '<a href="?" class="btn btn-secondary btn-sm">';
                echo '<i class="bi bi-arrow-left"></i> Kembali ke Daftar';
                echo '</a>';
                echo '<button class="btn btn-success btn-sm ms-2" onclick="window.print()">';
                echo '<i class="bi bi-printer"></i> Cetak Nota';
                echo '</button>';
                echo '</div>';
                echo '</div>';
                echo '</div>';
                echo '</div>';
            }
        }
        ?>

        <!-- Tabel Data Pasien -->
        <div class="row">
            <div class="col-12">
                <div class="card shadow-sm">
                    <div class="card-body p-0">
                        <div class="table-container">
                            <table class="table table-striped table-hover mb-0">
                                <thead class="table-primary sticky-top">
                                    <tr>
                                        <th class="text-center" style="width: 60px;">No</th>
                                        <th>ID Pasien</th>
                                        <th>Nama Pasien</th>
                                        <th>Usia</th>
                                        <th>Kelas Kamar</th>
                                        <th>Biaya/Hari</th>
                                        <th>Lama Rawat</th>
                                        <th>Total Biaya</th>
                                        <th class="text-center" style="width: 150px;">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (count($daftar_pasien) > 0): ?>
                                        <?php $no = 1; ?>
                                        <?php foreach ($daftar_pasien as $pasien): ?>
                                            <tr>
                                                <td class="text-center fw-bold"><?= $no++ ?></td>
                                                <td>
                                                    <span class="badge bg-secondary"><?= $pasien->getIdPasien() ?></span>
                                                </td>
                                                <td>
                                                    <strong><?= htmlspecialchars($pasien->getNama()) ?></strong>
                                                    <br>
                                                    <small class="text-muted">
                                                        <i class="bi bi-credit-card"></i> <?= htmlspecialchars($pasien->getNomorPBI()) ?>
                                                    </small>
                                                </td>
                                                <td><?= $pasien->getUsia() ?> tahun</td>
                                                <td>
                                                    <?php
                                                    $kelas = $pasien->getKelasKamar();
                                                    $badgeClass = match($kelas) {
                                                        'VIP' => 'danger',
                                                        'KELAS 1' => 'warning',
                                                        'KELAS 2' => 'info',
                                                        default => 'success'
                                                    };
                                                    ?>
                                                    <span class="badge bg-<?= $badgeClass ?>">
                                                        <?= $kelas ?>
                                                    </span>
                                                </td>
                                                <td>Rp <?= number_format($pasien->getBiayaKamarPerHari(), 0, ',', '.') ?></td>
                                                <td>
                                                    <span class="badge bg-primary">
                                                        <?= $pasien->getLamaRawat() ?> hari
                                                    </span>
                                                </td>
                                                <td>
                                                    <strong class="text-success">
                                                        Rp <?= number_format($pasien->hitungTotalBiaya(), 0, ',', '.') ?>
                                                    </strong>
                                                    <br>
                                                    <small class="text-muted">
                                                        (10% dari biaya)
                                                    </small>
                                                </td>
                                                <td class="text-center">
                                                    <a href="?cetak=<?= $pasien->getIdPasien() ?><?= !empty($search) ? '&search=' . urlencode($search) : '' ?>" 
                                                       class="btn btn-sm btn-success btn-cetak" 
                                                       title="Cetak Nota Klaim">
                                                        <i class="bi bi-file-earmark-text"></i> Cetak
                                                    </a>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <tr>
                                            <td colspan="9">
                                                <div class="empty-state">
                                                    <i class="bi bi-inbox"></i>
                                                    <h5 class="mt-3">Tidak ada data pasien BPJS</h5>
                                                    <p class="text-muted">
                                                        <?= !empty($search) ? 'Tidak ditemukan pasien dengan nama "' . htmlspecialchars($search) . '"' : 'Belum ada data pasien BPJS di database' ?>
                                                    </p>
                                                    <?php if (!empty($search)): ?>
                                                        <a href="?" class="btn btn-outline-primary btn-sm">
                                                            <i class="bi bi-arrow-left"></i> Lihat semua data
                                                        </a>
                                                    <?php endif; ?>
                                                </div>
                                            </td>
                                        </tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="card-footer bg-light">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <small class="text-muted">
                                    <i class="bi bi-info-circle"></i> 
                                    <strong>Informasi:</strong> Klik tombol "Cetak" untuk melihat rincian nota klaim
                                </small>
                            </div>
                            <div>
                                <small class="text-muted">
                                    Total data: <strong><?= count($daftar_pasien) ?></strong> pasien
                                </small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Footer -->
        <div class="row mt-4">
            <div class="col-12 text-center">
                <hr>
                <small class="text-muted">
                    <i class="bi bi-cpu"></i> Sistem Manajemen Layanan Medis (REMEDIS) &bull; 
                    Data bersifat <strong>Read-Only</strong> &bull; 
                    <?= date('Y') ?>
                </small>
            </div>
        </div>
    </div>

    <!-- Bootstrap 5 JS Bundle dengan Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
        // Tambahan JavaScript untuk interaktivitas (opsional)
        document.addEventListener('DOMContentLoaded', function() {
            // Auto-hide alert setelah 5 detik jika ada
            const alerts = document.querySelectorAll('.alert');
            alerts.forEach(alert => {
                setTimeout(() => {
                    alert.style.transition = 'opacity 0.5s ease';
                    alert.style.opacity = '0';
                    setTimeout(() => alert.remove(), 500);
                }, 5000);
            });
            
            // Highlight baris jika ada parameter cetak
            if (window.location.search.includes('cetak=')) {
                const rows = document.querySelectorAll('tbody tr');
                rows.forEach(row => {
                    const btn = row.querySelector('.btn-cetak');
                    if (btn && btn.href.includes(window.location.search)) {
                        row.style.backgroundColor = '#e3f2fd';
                        row.style.transition = 'background-color 0.5s ease';
                        setTimeout(() => {
                            row.style.backgroundColor = '';
                        }, 3000);
                    }
                });
            }
        });
    </script>
</body>
</html>