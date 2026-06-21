<?php

// Class induk
class Pasien
{
    protected $nama;
    protected $lamaRawat;
    protected $biayaKamar;

    public function __construct($nama, $lamaRawat, $biayaKamar)
    {
        $this->nama = $nama;
        $this->lamaRawat = $lamaRawat;
        $this->biayaKamar = $biayaKamar;
    }

    // Method default
    public function hitungTotalBiaya()
    {
        return $this->lamaRawat * $this->biayaKamar;
    }
}

// Subclass Pasien BPJS
class PasienBPJS extends Pasien
{
    private $nomorPBI;
    private $faskesAsal;
    private $kelasKamar;

    public function __construct(
        $nama,
        $lamaRawat,
        $biayaKamar,
        $nomorPBI,
        $faskesAsal,
        $kelasKamar
    ) {
        parent::__construct($nama, $lamaRawat, $biayaKamar);

        $this->nomorPBI = $nomorPBI;
        $this->faskesAsal = $faskesAsal;
        $this->kelasKamar = $kelasKamar;
    }

    // Override method dari class Pasien
    public function hitungTotalBiaya()
    {
        $tarifDasar = $this->lamaRawat * $this->biayaKamar;

        // BPJS menanggung 90%, pasien membayar 10%
        return $tarifDasar * 0.10;
    }

    public function tampilData()
    {
        echo "<h2>Data Pasien BPJS</h2>";
        echo "Nama Pasien : " . $this->nama . "<br>";
        echo "Nomor PBI : " . $this->nomorPBI . "<br>";
        echo "Faskes Asal : " . $this->faskesAsal . "<br>";
        echo "Kelas Kamar : " . $this->kelasKamar . "<br>";
        echo "Lama Rawat : " . $this->lamaRawat . " hari<br>";
        echo "Biaya Kamar : Rp " . number_format($this->biayaKamar, 0, ',', '.') . "<br>";
        echo "Total Biaya yang Dibayar Pasien : Rp " .
             number_format($this->hitungTotalBiaya(), 0, ',', '.');
    }
}

// Membuat objek Pasien BPJS
$pasien = new PasienBPJS(
    "Irma Siti Wahyuni",
    5,
    500000,
    "PBI123456789",
    "Puskesmas Pasarkemis",
    "Kelas II"
);

// Menampilkan data
$pasien->tampilData();

?>