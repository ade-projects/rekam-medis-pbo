<?php

require_once __DIR__ . '/../config/Database.php';
require_once __DIR__ . '/PasienUmum.php';

$database = new Database();
$db = $database->getConnection();

$search = isset($_GET['search']) ? $_GET['search'] : '';

$query = "SELECT p.*, pu.kelas_kamar, tk.tarif_kamar 
          FROM pasien p
          JOIN pasien_umum pu ON p.id_pasien = pu.id_pasien
          JOIN tarif_kamar tk ON pu.kelas_kamar = tk.kelas_kamar";

if (!empty($search)) {
    $query .= " WHERE p.nama LIKE :search";
}

$stmt = $db->prepare($query);

if (!empty($search)) {
    $searchTerm = "%$search%";
    $stmt->bindParam(':search', $searchTerm);
}

$stmt->execute();
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Monitor Kasir - Pasien Umum</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-light">

    <div class="container my-5">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h2 class="text-danger"><i class="fas fa-cash-register"></i> Monitor Kasir: Pasien Umum</h2>
                <p class="text-muted mb-0">Manajemen data dan estimasi tagihan pasien kategori Non-Asuransi (Umum)</p>
            </div>
            <a href="../index.php" class="btn btn-secondary"><i class="fas fa-arrow-left"></i> Kembali ke Dashboard</a>
        </div>

        <div class="card mb-4 shadow-sm">
            <div class="card-body">
                <form method="GET" action="" class="row g-3">
                    <div class="col-md-9">
                        <input type="text" name="search" class="form-control" placeholder="Masukkan nama pasien umum yang dicari..." value="<?= htmlspecialchars($search) ?>">
                    </div>
                    <div class="col-md-3 d-grid">
                        <button type="submit" class="btn btn-danger"><i class="fas fa-search"></i> Cari Pasien</button>
                    </div>
                </form>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-8 mb-4">
                <div class="card shadow-sm">
                    <div class="card-header bg-dark text-white d-flex justify-content-between align-items-center">
                        <h5 class="mb-0"><i class="fas fa-list"></i> Daftar Pasien Aktif</h5>
                        <span class="badge bg-danger"><?= $stmt->rowCount() ?> Orang</span>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>No. RM</th>
                                    <th>Nama Pasien</th>
                                    <th>Tgl Masuk</th>
                                    <th>Kelas</th>
                                    <th>Estimasi Tagihan</th>
                                    <th class="text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if ($stmt->rowCount() > 0): ?>
                                    <?php while ($row = $stmt->fetch(PDO::FETCH_ASSOC)): 
                                        // Instansiasi Objek Polimorfisme PasienUmum berdasarkan baris data dari database
                                        $pasien = new PasienUmum(
                                            $row['no_rm'],
                                            $row['nama'],
                                            $row['tanggal_masuk'],
                                            $row['kelas_kamar'],
                                            $row['tarif_kamar']
                                        );
                                    ?>
                                        <tr>
                                            <td><strong><?= method_exists($pasien, 'getNoRm') ? $pasien->getNoRm() : $row['no_rm'] ?></strong></td>
                                            <td><?= method_exists($pasien, 'getNama') ? $pasien->getNama() : $row['nama'] ?></td>
                                            <td><?= method_exists($pasien, 'getTanggalMasuk') ? $pasien->getTanggalMasuk() : $row['tanggal_masuk'] ?></td>
                                            <td><span class="badge bg-secondary">Kelas <?= method_exists($pasien, 'getKelasKamar') ? $pasien->getKelasKamar() : $row['kelas_kamar'] ?></span></td>
                                            <td><strong>Rp <?= number_format($pasien->hitungTotalBiaya(), 0, ',', '.') ?></strong></td>
                                            <td class="text-center">
                                                <a href="?search=<?= urlencode($search) ?>&cetak=<?= method_exists($pasien, 'getNoRm') ? $pasien->getNoRm() : $row['no_rm'] ?>" class="btn btn-sm btn-outline-danger">
                                                    <i class="fas fa-print"></i> Hitung & Cetak
                                                </a>
                                            </td>
                                        </tr>
                                    <?php endwhile; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="6" class="text-center text-muted py-4">
                                            <i class="fas fa-folder-open fa-2x mb-2 d-block"></i>
                                            Data pasien umum tidak ditemukan atau database kosong.
                                        </td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                <?php 
                if (isset($_GET['cetak'])) {
                    $noRmCetak = $_GET['cetak'];
                    
                    $queryCetak = "SELECT p.*, pu.kelas_kamar, tk.tarif_kamar 
                                   FROM pasien p
                                   JOIN pasien_umum pu ON p.id_pasien = pu.id_pasien
                                   JOIN tarif_kamar tk ON pu.kelas_kamar = tk.kelas_kamar
                                   WHERE p.no_rm = :no_rm LIMIT 1";
                    
                    $stmtCetak = $db->prepare($queryCetak);
                    $stmtCetak->bindParam(':no_rm', $noRmCetak);
                    $stmtCetak->execute();

                    if ($rowCetak = $stmtCetak->fetch(PDO::FETCH_ASSOC)) {
                        $pasienCetak = new PasienUmum(
                            $rowCetak['no_rm'],
                            $rowCetak['nama'],
                            $rowCetak['tanggal_masuk'],
                            $rowCetak['kelas_kamar'],
                            $rowCetak['tarif_kamar']
                        );
                        
                        $pasienCetak->cetakKlaimLayanan();
                    }
                } else {
                    echo "
                    <div class='alert alert-info text-center shadow-sm py-4' role='alert'>
                        <i class='fas fa-receipt fa-3x mb-3 text-info animate__animated animate__pulse animate__infinite'></i>
                        <h5>Rincian Nota Struk</h5>
                        <p class='mb-0 small text-muted'>Silakan klik tombol <strong><i class='fas fa-print'></i> Hitung & Cetak</strong> pada salah satu pasien untuk memunculkan rincian struk pembayaran tunai disini.</p>
                    </div>";
                }
                ?>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>