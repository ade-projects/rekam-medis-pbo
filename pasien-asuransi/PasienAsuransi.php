<?php

class PasienAsuransiSwasta extends Pasien {
    // Atribut tambahan khusus untuk Pasien Asuransi Swasta
    private $namaProvider;
    private $nomorPolis;
    private $limitCover;

    // Konstruktor disesuaikan dengan Pasien induk terbaru kelompok
    public function __construct(
        $id_pasien,
        $nama,
        $usia,
        $tanggal_masuk,
        $biayaKamarPerHari,
        $namaProvider,
        $nomorPolis,
        $limitCover
    ) {
        // WAJIB mengeksekusi parent::__construct di baris pertama
        parent::__construct($id_pasien, $nama, $usia, $tanggal_masuk, $biayaKamarPerHari);
        
        $this->namaProvider = $namaProvider;
        $this->nomorPolis = $nomorPolis;
        $this->limitCover = $limitCover;
    }

    // Getter untuk enkapsulasi data
    public function getNamaProvider() {
        return $this->namaProvider;
    }

    public function getNomorPolis() {
        return $this->nomorPolis;
    }

    public function getLimitCover() {
        return $this->limitCover;
    }

    /**
     * Overriding Polimorfisme: hitungTotalBiaya()
     * Aturan: Jika (Lama Rawat * Biaya Kamar) > limitCover, total = sisa biaya; jika tidak = 0.
     */
    public function hitungTotalBiaya() {
        $biayaTotal = $this->getLamaRawat() * $this->getBiayaKamarPerHari();
        
        if ($biayaTotal > $this->limitCover) {
            return $biayaTotal - $this->limitCover; // Sisa biaya yang dibayar mandiri oleh pasien
        }
        return 0; // Aman tercover penuh oleh asuransi
    }

    /**
     * Overriding Polimorfisme: cetakKlaimLayanan()
     * Menghasilkan struktur cetak nota berbentuk komponen Card Bootstrap
     */
    public function cetakKlaimLayanan() {
        $lamaRawat = $this->getLamaRawat();
        $tarifDasarKamar = $lamaRawat * $this->getBiayaKamarPerHari();
        $biayaDibayarPasien = $this->hitungTotalBiaya();
        $biayaDitanggungAsuransi = $tarifDasarKamar - $biayaDibayarPasien;

        echo "
        <div class='card my-4 shadow' style='border: 2px dashed #ffc107; max-width: 500px; width: 100%; margin: 0 auto;'>
            <div class='card-header bg-warning text-dark text-center fw-bold'>
                <i class='fa-solid fa-receipt me-2'></i>NOTA KLAIM ASURANSI SWASTA (REMEDIS)
            </div>
            <div class='card-body text-monospace' style='font-size: 14px; background-color: #fff;'>
                <p class='mb-1'><strong>ID Pasien:</strong> {$this->getIdPasien()}</p>
                <p class='mb-1'><strong>Nama Pasien:</strong> {$this->getNama()}</p>
                <p class='mb-1'><strong>Usia:</strong> {$this->getUsia()} Tahun</p>
                <p class='mb-1'><strong>Tanggal Masuk:</strong> {$this->getTanggalMasuk()}</p>
                <p class='mb-1'><strong>Lama Rawat:</strong> {$lamaRawat} Hari</p>
                <p class='mb-1'><strong>Provider Asuransi:</strong> {$this->namaProvider}</p>
                <p class='mb-3'><strong>No. Polis:</strong> {$this->nomorPolis}</p>
                
                <hr style='border-top: 1px dashed #000;'>
                
                <div class='d-flex justify-content-between mb-1'>
                    <span>Tarif Kamar ({$lamaRawat} Hari):</span>
                    <span>Rp " . number_format($tarifDasarKamar, 0, ',', '.') . "</span>
                </div>
                <div class='d-flex justify-content-between mb-3'>
                    <span>Limit Cover Asuransi:</span>
                    <span>Rp " . number_format($this->limitCover, 0, ',', '.') . "</span>
                </div>
                
                <hr style='border-top: 1px dashed #000;'>
                
                <div class='d-flex justify-content-between mb-1 text-success fw-bold'>
                    <span>Ditanggung Asuransi:</span>
                    <span>Rp " . number_format($biayaDitanggungAsuransi, 0, ',', '.') . "</span>
                </div>
                <div class='d-flex justify-content-between text-danger fw-bold' style='font-size: 16px;'>
                    <span>Biaya Mandiri Pasien:</span>
                    <span>Rp " . number_format($biayaDibayarPasien, 0, ',', '.') . "</span>
                </div>
            </div>
            <div class='card-footer text-center text-muted' style='font-size: 11px;'>
                Harap simpan bukti cetak tagihan digital ini. Terima kasih.
            </div>
        </div>
        ";
    }
}
?>