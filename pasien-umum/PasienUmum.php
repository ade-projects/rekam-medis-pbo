<?php
// File: pasien-umum/PasienUmum.php

class PasienUmum extends Pasien {
    private $nik;
    private $metodePembayaran;
    private $biayaAdmin = 150000;

    public function __construct($id_pasien, $nama, $usia, $tanggal_masuk, $biayaKamarPerHari, $nik, $metodePembayaran) {
        parent::__construct($id_pasien, $nama, $usia, $tanggal_masuk, $biayaKamarPerHari);
        $this->nik = $nik;
        $this->metodePembayaran = $metodePembayaran;
    }

    public function getNik() {
        return $this->nik;
    }

    public function getMetodePembayaran() {
        return $this->metodePembayaran;
    }

    public function hitungTotalBiaya() {
        return ($this->getLamaRawat() * $this->getBiayaKamarPerHari()) + $this->biayaAdmin;
    }

    public function cetakKlaimLayanan() {
        $lamaRawat = $this->getLamaRawat();
        $tarifKamar = $this->getBiayaKamarPerHari();
        $biayaDasar = $lamaRawat * $tarifKamar;
        $totalBiaya = $this->hitungTotalBiaya();

        echo "
        <div class='card my-4 shadow' style='border: 2px dashed #dc3545; max-width: 500px; width: 100%; margin: 0 auto;'>
            <div class='card-header bg-danger text-white text-center fw-bold'>
                <i class='bi bi-receipt me-2'></i>NOTA PEMBAYARAN PASIEN UMUM
            </div>
            <div class='card-body' style='font-size: 14px;'>
                <p class='mb-1'><strong>ID Pasien:</strong> {$this->getIdPasien()}</p>
                <p class='mb-1'><strong>NIK:</strong> {$this->nik}</p>
                <p class='mb-1'><strong>Nama Pasien:</strong> {$this->getNama()}</p>
                <p class='mb-1'><strong>Usia:</strong> {$this->getUsia()} Tahun</p>
                <p class='mb-1'><strong>Tanggal Masuk:</strong> {$this->getTanggalMasuk()}</p>
                <p class='mb-3'><strong>Lama Rawat:</strong> {$lamaRawat} Hari</p>
                
                <hr>
                
                <div class='d-flex justify-content-between mb-1 text-muted'>
                    <span>Tarif Kamar ({$lamaRawat} Hari):</span>
                    <span>Rp " . number_format($biayaDasar, 0, ',', '.') . "</span>
                </div>
                <div class='d-flex justify-content-between mb-3 text-muted'>
                    <span>Biaya Administrasi:</span>
                    <span>Rp " . number_format($this->biayaAdmin, 0, ',', '.') . "</span>
                </div>
                
                <hr>
                
                <div class='d-flex justify-content-between text-danger fw-bold' style='font-size: 16px;'>
                    <span>Total Tagihan ({$this->metodePembayaran}):</span>
                    <span>Rp " . number_format($totalBiaya, 0, ',', '.') . "</span>
                </div>
            </div>
        </div>
        ";
    }
}
?>