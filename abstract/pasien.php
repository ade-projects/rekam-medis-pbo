<?php

abstract class Pasien {
    protected $id_pasien;
    protected $nama;
    protected $usia;
    protected $lamaRawat;
    protected $biayaKamarPerHari;

    public function __construct(
        $id_pasien,
        $nama,
        $usia,
        $lamaRawat,
        $biayaKamarPerHari
    ) {
        $this->id_pasien = $id_pasien;
        $this->nama = $nama;
        $this->usia = $usia;
        $this->lamaRawat = $lamaRawat;
        $this->biayaKamarPerHari = $biayaKamarPerHari;
    }

    public function getIdPasien() {
        return $this->id_pasien;
    }

    public function getNama() {
        return $this->nama;
    }

    public function getUsia() {
        return $this->usia;
    }

    public function getLamaRawat() {
        return $this->lamaRawat;
    }

    public function getBiayaKamarPerHari() {
        return $this->biayaKamarPerHari;
    }

    abstract public function hitungTotalBiaya();

    abstract public function cetakKlaimLayanan();
}

?>