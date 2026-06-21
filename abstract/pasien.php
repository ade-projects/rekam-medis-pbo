<?php

abstract class Pasien {
    protected $id_pasien;
    protected $nama;
    protected $usia;
    protected $tanggal_masuk; 
    protected $lamaRawat;     
    protected $biayaKamarPerHari;

    public function __construct(
        $id_pasien,
        $nama,
        $usia,
        $tanggal_masuk,       
        $biayaKamarPerHari
    ) {
        $this->id_pasien = $id_pasien;
        $this->nama = $nama;
        $this->usia = $usia;
        $this->tanggal_masuk = $tanggal_masuk;
        $this->biayaKamarPerHari = $biayaKamarPerHari;

        $this->hitungDurasiHari();
    }

    protected function hitungDurasiHari() {
        $tgl_masuk = new DateTime($this->tanggal_masuk);
        $tgl_sekarang = new DateTime(); 
        
        $selisih = $tgl_sekarang->diff($tgl_masuk);
        $hari = $selisih->days;

        $this->lamaRawat = ($hari == 0) ? 1 : $hari;
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

    public function getTanggalMasuk() {
        return $this->tanggal_masuk;
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