<?php
// Tugas 6: Memanggil objek Controller di baris paling atas
require_once __DIR__ . '/../config/koneksi.php';
require_once __DIR__ . '/../controllers/ManajemenRumahSakit.php';

$database = new Database();
$controller = new ManajemenRumahSakit($database->getConnection());

$search = isset($_GET['search']) ? $_GET['search'] : '';
$daftar_pasien = $controller->getPasienUmum($search); // Mengambil array of objects

// Tugas 8: Logika Fitur "Cetak Nota" tanpa query ulang ke MySQL
$pasienTerpilih = null;
if (isset($_GET['cetak'])) {
    $idCetak = $_GET['cetak'];
    // Mencari objek pasien yang cocok di dalam array $daftar_pasien
    foreach ($daftar_pasien as $pasien) {
        if ($pasien->getIdPasien() == $idCetak) {
            $pasienTerpilih = $pasien;
            break;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Manajemen Pasien Umum</title>
    <!-- Tambahkan CSS/Bootstrap Anda di sini -->
</head>
<body>

    <!-- Contoh Form Search Bar -->
    <form method="GET" action="">
        <input type="text" name="search" value="<?= htmlspecialchars($search) ?>" placeholder="Cari nama atau ID pasien...">
        <button type="submit">Cari</button>
    </form>

    <!-- Tempat Menampilkan Cetak Nota (Jika tombol cetak diklik) -->
    <?php if ($pasienTerpilih): ?>
        <div class="nota-container" style="border: 1px dashed #000; padding: 15px; margin-bottom: 20px;">
            <?php $pasienTerpilih->cetakKlaimLayanan(); ?>
        </div>
    <?php endif; ?>

    <!-- Tabel Data Pasien -->
    <table border="1" cellpadding="10" cellspacing="0">
        <thead>
            <tr>
                <th>ID Pasien</th>
                <th>Nama Pasien</th>
                <th>Tanggal Masuk</th>
                <th>Total Biaya</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            <!-- Tugas 7: Mengubah cara looping menggunakan data controller -->
            <?php if (!empty($daftar_pasien)): ?>
                <?php foreach ($daftar_pasien as $pasien): ?>
                    <tr>
                        <td><?= htmlspecialchars($pasien->getIdPasien()) ?></td>
                        <td><?= htmlspecialchars($pasien->getNama()) ?></td>
                        <td><?= htmlspecialchars($pasien->getTanggalMasuk()) ?></td>
                        <td><strong>Rp <?= number_format($pasien->hitungTotalBiaya(), 0, ',', '.') ?></strong></td>
                        <td>
                            <!-- Tombol cetak mengarah ke halaman ini lagi dengan parameter 'cetak' -->
                            <a href="?search=<?= urlencode($search) ?>&cetak=<?= $pasien->getIdPasien() ?>">Cetak Nota</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="5" style="text-align: center;">Data pasien tidak ditemukan.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>

</body>
</html>