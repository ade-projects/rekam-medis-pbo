<?php
require_once '../abstract/pasien.php'; 

class PasienUmum extends Pasien {
    // Atribut tambahan khusus untuk PasienUmum
    private $nik;
    private $metodePembayaran;
    private $biayaAdmin = 150000; 

    public function __construct($id_pasien, $nama, $usia, $tanggal_masuk, $biayaKamarPerHari, $nik, $metodePembayaran) {
        parent::__construct($id_pasien, $nama, $usia, $tanggal_masuk, $biayaKamarPerHari);
        
        // Inisialisasi properti spesifik subclass
        $this->nik = $nik;
        $this->metodePembayaran = $metodePembayaran;
    }

    // Getter untuk atribut baru (opsional, tapi disarankan dalam PBO)
    public function getNik() {
        return $this->nik;
    }

    public function getMetodePembayaran() {
        return $this->metodePembayaran;
    }

    // Tugas 4: Perbaikan Rumus Polimorfisme hitungTotalBiaya()
    // Tugas 3: Menggunakan getter resmi dari kelas induk
    public function hitungTotalBiaya() {
        $total = ($this->getLamaRawat() * $this->getBiayaKamarPerHari()) + $this->biayaAdmin;
        return $total;
    }

    // Fungsi cetak nota struk
    public function cetakKlaimLayanan() {
        echo "=== NOTA PEMBAYARAN PASIEN UMUM ===" . PHP_EOL;
        echo "ID Pasien : " . $this->getIdPasien() . PHP_EOL;
        echo "NIK       : " . $this->nik . PHP_EOL;
        echo "Nama      : " . $this->getNama() . PHP_EOL;
        echo "Lama Rawat: " . $this->getLamaRawat() . " Hari" . PHP_EOL;
        echo "Total     : Rp " . number_format($this->hitungTotalBiaya(), 0, ',', '.') . PHP_EOL;
        echo "Metode    : " . $this->metodePembayaran . PHP_EOL;
        echo "===================================" . PHP_EOL;
    }
}