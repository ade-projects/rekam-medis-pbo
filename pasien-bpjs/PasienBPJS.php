<?php 
class PasienBPJS extends Pasien {
    /**
     * @var string 
     */
    private $nomorPBI;
    
    /**
     * @var string 
     */
    private $faskesAsal;
    
    /**
     * @var string Kelas kamar yang ditempati pasien (KELAS 1, 2, 3, atau VIP)
     */
    private $kelasKamar;

    /**
     * Konstruktor PasienBPJS
     * 
     * @param int $id_pasien ID unik pasien
     * @param string $nama Nama lengkap pasien
     * @param int $usia Usia pasien dalam tahun
     * @param string $tanggal_masuk Tanggal masuk pasien (format YYYY-MM-DD)
     * @param int $biayaKamarPerHari Tarif kamar per hari
     * @param string $nomorPBI Nomor PBI (Penerima Bantuan Iuran) pasien
     * @param string $faskesAsal Fasilitas Kesehatan asal rujukan
     * @param string $kelasKamar Kelas kamar yang ditempati
     */
    public function __construct(
        $id_pasien, 
        $nama, 
        $usia, 
        $tanggal_masuk, 
        $biayaKamarPerHari, 
        $nomorPBI, 
        $faskesAsal, 
        $kelasKamar
    ) {
        // WAJIB: Memanggil konstruktor induk di baris pertama
        parent::__construct($id_pasien, $nama, $usia, $tanggal_masuk, $biayaKamarPerHari);
        
        $this->nomorPBI = $nomorPBI;
        $this->faskesAsal = $faskesAsal;
        $this->kelasKamar = $kelasKamar;
    }

    /**
     * Mendapatkan nomor PBI pasien
     * 
     * @return string Nomor PBI
     */
    public function getNomorPBI() {
        return $this->nomorPBI;
    }

    /**
     * Mendapatkan faskes asal rujukan
     * 
     * @return string Nama faskes asal
     */
    public function getFaskesAsal() {
        return $this->faskesAsal;
    }

    /**
     * Mendapatkan kelas kamar
     * 
     * @return string Kelas kamar
     */
    public function getKelasKamar() {
        return $this->kelasKamar;
    }

    /**
     * Menghitung total biaya rawat untuk pasien BPJS
     * 
     * OVERRIDING POLIMORFISME:
     * Subsidi BPJS = 90%, pasien hanya dibebankan 10% dari total biaya
     * 
     * @return float Total biaya yang harus dibayar pasien (10% dari biaya dasar)
     */
    public function hitungTotalBiaya() {
        $totalDasar = $this->getLamaRawat() * $this->getBiayaKamarPerHari();
        return $totalDasar * 0.10; // 10% dari total biaya
    }

    /**
     * Mencetak klaim layanan dalam format nota/struk
     * 
     * Menampilkan detail lengkap pasien BPJS termasuk:
     * - Informasi identitas pasien
     * - Detail BPJS (nomor PBI, faskes asal, kelas kamar)
     * - Perhitungan biaya dengan subsidi 90%
     */
    public function cetakKlaimLayanan() {
        $lamaRawat = $this->getLamaRawat();
        $biayaPerHari = $this->getBiayaKamarPerHari();
        $totalDasar = $lamaRawat * $biayaPerHari;
        $totalBayar = $this->hitungTotalBiaya();
        $subsidi = $totalDasar - $totalBayar;
        
        echo "<div style='border: 2px solid #2196F3; padding: 20px; margin: 20px 0; border-radius: 8px; font-family: Arial, sans-serif;'>";
        echo "<h2 style='color: #2196F3; margin-top: 0;'>📋 NOTA KLAIM BPJS</h2>";
        echo "<hr style='border: 1px solid #2196F3;'>";
        
        echo "<div style='display: grid; grid-template-columns: 1fr 1fr; gap: 10px;'>";
        echo "<div><strong>ID Pasien:</strong> " . $this->getIdPasien() . "</div>";
        echo "<div><strong>Nama:</strong> " . $this->getNama() . "</div>";
        echo "<div><strong>Usia:</strong> " . $this->getUsia() . " tahun</div>";
        echo "<div><strong>Kelas Kamar:</strong> " . $this->getKelasKamar() . "</div>";
        echo "<div><strong>Tanggal Masuk:</strong> " . $this->getTanggalMasuk() . "</div>";
        echo "<div><strong>Lama Rawat:</strong> " . $lamaRawat . " hari</div>";
        echo "<div><strong>Nomor PBI:</strong> " . $this->getNomorPBI() . "</div>";
        echo "<div><strong>Faskes Asal:</strong> " . $this->getFaskesAsal() . "</div>";
        echo "</div>";
        
        echo "<hr style='border: 1px solid #ddd;'>";
        
        echo "<div style='display: grid; grid-template-columns: 1fr 1fr; gap: 10px;'>";
        echo "<div><strong>Biaya Kamar/Hari:</strong> Rp " . number_format($biayaPerHari, 0, ',', '.') . "</div>";
        echo "<div><strong>Total Biaya Dasar:</strong> Rp " . number_format($totalDasar, 0, ',', '.') . "</div>";
        echo "<div style='color: green;'><strong>Subsidi BPJS (90%):</strong> Rp " . number_format($subsidi, 0, ',', '.') . "</div>";
        echo "<div style='color: #2196F3; font-weight: bold; font-size: 1.1em;'><strong>Total Dibayar Pasien (10%):</strong> Rp " . number_format($totalBayar, 0, ',', '.') . "</div>";
        echo "</div>";
        
        echo "<hr style='border: 1px solid #2196F3;'>";
        echo "<div style='text-align: center; color: #666; font-size: 12px;'>";
        echo "Terima kasih telah menggunakan layanan BPJS<br>";
        echo "Dicetak pada: " . date('d-m-Y H:i:s');
        echo "</div>";
        echo "</div>";
    }
}
?>