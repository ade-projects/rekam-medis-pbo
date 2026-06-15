<?php

require_once __DIR__ . '/../abstract/Pasien.php';

class PasienUmum extends Pasien {
    private $biayaAdmin = 150000; 

    public function hitungTotalBiaya() {
        $tarif = method_exists($this, 'getTarifKamar') ? $this->getTarifKamar() : $this->tarifKamar;
        $lamaRawat = $this->hitungLamaRawat();
        
        $total = ($tarif * $lamaRawat) + $this->biayaAdmin;
        return $total;
    }

    public function cetakKlaimLayanan() {
        $noRm = method_exists($this, 'getNoRm') ? $this->getNoRm() : $this->noRm;
        $nama = method_exists($this, 'getNama') ? $this->getNama() : $this->nama;
        $tanggalMasuk = method_exists($this, 'getTanggalMasuk') ? $this->getTanggalMasuk() : $this->tanggalMasuk;
        $kelasKamar = method_exists($this, 'getKelasKamar') ? $this->getKelasKamar() : $this->kelasKamar;
        $tarifKamar = method_exists($this, 'getTarifKamar') ? $this->getTarifKamar() : $this->tarifKamar;

        $lamaRawat = $this->hitungLamaRawat();
        $biayaKamar = $tarifKamar * $lamaRawat;
        $total = $this->hitungTotalBiaya();

        echo "
        <div class='card border-danger mb-3 shadow-sm'>
            <div class='card-header bg-danger text-white'>
                <h5 class='card-title mb-0'><i class='fas fa-file-invoice-dollar'></i> NOTA PEMBAYARAN PASIEN UMUM</h5>
            </div>
            <div class='card-body'>
                <table class='table table-sm table-borderless mb-0'>
                    <tr><td width='40%'><strong>No. RM</strong></td><td>: {$noRm}</td></tr>
                    <tr><td><strong>Nama Pasien</strong></td><td>: {$nama}</td></tr>
                    <tr><td><strong>Tanggal Masuk</strong></td><td>: {$tanggalMasuk}</td></tr>
                    <tr><td><strong>Lama Rawat</strong></td><td>: {$lamaRawat} Hari</td></tr>
                    <tr><td colspan='2'><hr class='my-2'></td></tr>
                    <tr><td>Tarif Kamar Kelas {$kelasKamar}</td><td>: Rp " . number_format($tarifKamar, 0, ',', '.') . " /hari</td></tr>
                    <tr><td>Total Biaya Kamar</td><td>: Rp " . number_format($biayaKamar, 0, ',', '.') . "</td></tr>
                    <tr><td>Biaya Administrasi</td><td>: Rp " . number_format($this->biayaAdmin, 0, ',', '.') . "</td></tr>
                    <tr><td colspan='2'><hr class='my-2'></td></tr>
                    <tr class='table-danger text-danger fs-5 fw-bold'>
                        <td>Total Tagihan</td>
                        <td>: Rp " . number_format($total, 0, ',', '.') . "</td>
                    </tr>
                </table>
                <p class='text-muted small text-end mt-3 mb-0'>*Pembayaran dilakukan secara tunai/debit di kasir utama.</p>
            </div>
        </div>";
    }
}