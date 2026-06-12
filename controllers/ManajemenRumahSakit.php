<?php
class ManajemenRumahSakit {
    private $db;
    private $daftar_pasien = [];

    /**
     * Constructor Controller
     * Menerima koneksi database yang aktif (diambil dari kelas Database)
     */
    public function __construct($dbConnection) {
        $this->db = $dbConnection;
    }

    /**
     * Membersihkan koleksi pasien di memori sebelum melakukan pemrosesan baru
     */
    public function resetKoleksi() {
        $this->daftar_pasien = [];
    }

    /**
     * Method untuk mengambil data Pasien BPJS dari database (Read-Only)
     * Mendukung fitur Pencarian (Search Bar) berdasarkan nama pasien.
     */
    public function getPasienBPJS($search = '') {
        $this->resetKoleksi();

        // Query SQL JOIN lengkap sesuai dengan struktur tabel db_remedis.sql
        $sql = "SELECT p.id_pasien, p.nama, p.usia, p.tanggal_masuk, 
                       k.nama_kelas, k.tarif_per_hari, 
                       pb.nomor_pbi, pb.faskes_asal
                FROM pasien p
                INNER JOIN pasien_bpjs pb ON p.id_pasien = pb.id_pasien
                INNER JOIN tarif_kamar k ON p.id_kamar = k.id_kamar";

        // Tambahkan kondisi jika ada kata kunci pencarian
        if (!empty($search)) {
            $sql .= " WHERE p.nama LIKE :search";
        }

        $stmt = $this->db->prepare($sql);

        if (!empty($search)) {
            $stmt->bindValue(':search', '%' . $search . '%', PDO::PARAM_STR);
        }

        $stmt->execute();
        $rows = $stmt->fetchAll();

        // PROSES HYDRATION: Mengubah data mentah array menjadi objek konkrit PasienBPJS
        foreach ($rows as $row) {
            // Membuat objek PasienBPJS (Anggota kelompok 2 yang menyusun definisi kelas ini)
            $pasien = new PasienBPJS(
                $row['id_pasien'],
                $row['nama'],
                $row['usia'],
                $row['tanggal_masuk'],
                $row['tarif_per_hari'], // dipetakan ke biayaKamarPerHari di kelas induk
                $row['nomor_pbi'],
                $row['faskes_asal'],
                $row['nama_kelas']      // dipetakan ke kelasKamar
            );
            
            // Memasukkan objek ke dalam Polymorphic Collection
            $this->daftar_pasien[] = $pasien;
        }

        return $this->daftar_pasien;
    }

    /**
     * Method untuk mengambil data Pasien Asuransi Swasta dari database (Read-Only)
     * Mendukung fitur Pencarian (Search Bar) berdasarkan nama pasien.
     */
    public function getPasienAsuransi($search = '') {
        $this->resetKoleksi();

        $sql = "SELECT p.id_pasien, p.nama, p.usia, p.tanggal_masuk, 
                       k.nama_kelas, k.tarif_per_hari, 
                       pa.nama_provider, pa.nomor_polis, pa.limit_cover
                FROM pasien p
                INNER JOIN pasien_asuransi pa ON p.id_pasien = pa.id_pasien
                INNER JOIN tarif_kamar k ON p.id_kamar = k.id_kamar";

        if (!empty($search)) {
            $sql .= " WHERE p.nama LIKE :search";
        }

        $stmt = $this->db->prepare($sql);

        if (!empty($search)) {
            $stmt->bindValue(':search', '%' . $search . '%', PDO::PARAM_STR);
        }

        $stmt->execute();
        $rows = $stmt->fetchAll();

        // PROSES HYDRATION: Mengubah data mentah menjadi objek PasienAsuransiSwasta
        foreach ($rows as $row) {
            // Membuat objek PasienAsuransiSwasta (Anggota kelompok 3 yang menyusun kelas ini)
            $pasien = new PasienAsuransiSwasta(
                $row['id_pasien'],
                $row['nama'],
                $row['usia'],
                $row['tanggal_masuk'],
                $row['tarif_per_hari'],
                $row['nama_provider'],
                $row['nomor_polis'],
                $row['limit_cover']
            );
            
            $this->daftar_pasien[] = $pasien;
        }

        return $this->daftar_pasien;
    }

    /**
     * Method untuk mengambil data Pasien Umum dari database 
     * Mendukung fitur Pencarian berdasarkan nama pasien.
     */
    public function getPasienUmum($search = '') {
        $this->resetKoleksi();

        $sql = "SELECT p.id_pasien, p.nama, p.usia, p.tanggal_masuk, 
                       k.nama_kelas, k.tarif_per_hari, 
                       pu.nik, pu.metode_pembayaran
                FROM pasien p
                INNER JOIN pasien_umum pu ON p.id_pasien = pu.id_pasien
                INNER JOIN tarif_kamar k ON p.id_kamar = k.id_kamar";

        if (!empty($search)) {
            $sql .= " WHERE p.nama LIKE :search";
        }

        $stmt = $this->db->prepare($sql);

        if (!empty($search)) {
            $stmt->bindValue(':search', '%' . $search . '%', PDO::PARAM_STR);
        }

        $stmt->execute();
        $rows = $stmt->fetchAll();

        // PROSES HYDRATION: Mengubah data mentah menjadi objek PasienUmum
        foreach ($rows as $row) {
            $pasien = new PasienUmum(
                $row['id_pasien'],
                $row['nama'],
                $row['usia'],
                $row['tanggal_masuk'],
                $row['tarif_per_hari'],
                $row['nik'],
                $row['metode_pembayaran']
            );
            
            $this->daftar_pasien[] = $pasien;
        }

        return $this->daftar_pasien;
    }

    // Polimorfisme
    public function loadAllPasienForDashboard() {
        $this->resetKoleksi();

        // 1. Ambil data BPJS dan masukkan ke array koleksi
        $sqlBPJS = "SELECT p.id_pasien, p.nama, p.usia, p.tanggal_masuk, k.tarif_per_hari, pb.nomor_pbi, pb.faskes_asal, k.nama_kelas FROM pasien p INNER JOIN pasien_bpjs pb ON p.id_pasien = pb.id_pasien INNER JOIN tarif_kamar k ON p.id_kamar = k.id_kamar";
        $stmt = $this->db->query($sqlBPJS);
        foreach ($stmt->fetchAll() as $row) {
            $this->daftar_pasien[] = new PasienBPJS($row['id_pasien'], $row['nama'], $row['usia'], $row['tanggal_masuk'], $row['tarif_per_hari'], $row['nomor_pbi'], $row['faskes_asal'], $row['nama_kelas']);
        }

        // 2. Ambil data Asuransi Swasta dan gabungkan ke array koleksi yang sama
        $sqlAsuransi = "SELECT p.id_pasien, p.nama, p.usia, p.tanggal_masuk, k.tarif_per_hari, pa.nama_provider, pa.nomor_polis, pa.limit_cover FROM pasien p INNER JOIN pasien_asuransi pa ON p.id_pasien = pa.id_pasien INNER JOIN tarif_kamar k ON p.id_kamar = k.id_kamar";
        $stmt = $this->db->query($sqlAsuransi);
        foreach ($stmt->fetchAll() as $row) {
            $this->daftar_pasien[] = new PasienAsuransiSwasta($row['id_pasien'], $row['nama'], $row['usia'], $row['tanggal_masuk'], $row['tarif_per_hari'], $row['nama_provider'], $row['nomor_polis'], $row['limit_cover']);
        }

        // 3. Ambil data Umum dan gabungkan ke array koleksi yang sama
        $sqlUmum = "SELECT p.id_pasien, p.nama, p.usia, p.tanggal_masuk, k.tarif_per_hari, pu.nik, pu.metode_pembayaran FROM pasien p INNER JOIN pasien_umum pu ON p.id_pasien = pu.id_pasien INNER JOIN tarif_kamar k ON p.id_kamar = k.id_kamar";
        $stmt = $this->db->query($sqlUmum);
        foreach ($stmt->fetchAll() as $row) {
            $this->daftar_pasien[] = new PasienUmum($row['id_pasien'], $row['nama'], $row['usia'], $row['tanggal_masuk'], $row['tarif_per_hari'], $row['nik'], $row['metode_pembayaran']);
        }

        return $this->daftar_pasien;
    }

    // dynamic binding
    public function hitungTotalEstimasiPendapatanHariIni() {
        // Pastikan seluruh data dari 3 kategori sudah masuk ke wadah koleksi
        $this->loadAllPasienForDashboard();
        
        $grandTotal = 0;

        foreach ($this->daftar_pasien as $pasien) {
            $grandTotal += $pasien->hitungTotalBiaya();
        }

        return $grandTotal;
    }
}
?>