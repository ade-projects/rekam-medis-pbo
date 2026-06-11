CREATE DATABASE IF NOT EXISTS `db_remedis`;
USE `db_remedis`;

/*M!999999\- enable the sandbox mode */ 
-- MariaDB dump 10.19-12.3.2-MariaDB, for Linux (x86_64)
--
-- Host: localhost    Database: db_remedis
-- ------------------------------------------------------
-- Server version	12.2.2-MariaDB

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*M!100616 SET @OLD_NOTE_VERBOSITY=@@NOTE_VERBOSITY, NOTE_VERBOSITY=0 */;

--
-- Table structure for table `pasien`
--

DROP TABLE IF EXISTS `pasien`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `pasien` (
  `id_pasien` int(11) NOT NULL AUTO_INCREMENT,
  `nama` varchar(100) NOT NULL,
  `usia` int(11) NOT NULL,
  `tanggal_masuk` date NOT NULL,
  `id_kamar` int(11) NOT NULL,
  PRIMARY KEY (`id_pasien`),
  KEY `id_kamar` (`id_kamar`),
  CONSTRAINT `fk_pasien_kamar` FOREIGN KEY (`id_kamar`) REFERENCES `tarif_kamar` (`id_kamar`)
) ENGINE=InnoDB AUTO_INCREMENT=31 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `pasien`
--

SET @OLD_AUTOCOMMIT=@@AUTOCOMMIT, @@AUTOCOMMIT=0;
LOCK TABLES `pasien` WRITE;
/*!40000 ALTER TABLE `pasien` DISABLE KEYS */;
INSERT INTO `pasien` VALUES
(1,'Ahmad Subarjo',45,'2026-06-01',1),
(2,'Siti Aminah',34,'2026-06-03',1),
(3,'Budi Utomo',29,'2026-05-28',2),
(4,'Dewi Lestari',52,'2026-06-05',2),
(5,'Eko Prasetyo',41,'2026-06-02',3),
(6,'Farida Utami',23,'2026-06-07',1),
(7,'Gunawan Wibisono',60,'2026-05-25',2),
(8,'Hany Rahmawati',19,'2026-06-04',3),
(9,'Irfan Hakim',38,'2026-06-01',1),
(10,'Joko Widodo',50,'2026-05-30',2),
(11,'Aris Merdeka',37,'2026-06-01',4),
(12,'Bella Citra',25,'2026-06-04',3),
(13,'Candra Wijaya',43,'2026-05-29',4),
(14,'Dina Mariana',30,'2026-06-06',2),
(15,'Edi Brokoli',48,'2026-06-02',4),
(16,'Fanny Fadillah',35,'2026-06-05',3),
(17,'Gilbert Lumoindong',51,'2026-05-27',4),
(18,'Helena Ayu',24,'2026-06-07',2),
(19,'Indra Herlambang',42,'2026-06-01',4),
(20,'Julia Perez',39,'2026-06-03',3),
(21,'Vino G Bastian',40,'2026-06-01',1),
(22,'Wulan Guritno',44,'2026-06-03',2),
(23,'Xavi Hernan',28,'2026-06-05',3),
(24,'Yuni Shara',50,'2026-05-29',4),
(25,'Zack Lee',42,'2026-06-02',1),
(26,'Amiruddin',58,'2026-06-06',2),
(27,'Basuki Tjahaja',55,'2026-05-27',3),
(28,'Chintami Atmanagara',61,'2026-06-04',4),
(29,'Dedy Corbuzier',46,'2026-06-01',1),
(30,'Elvy Sukaesih',68,'2026-05-30',2);
/*!40000 ALTER TABLE `pasien` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;
SET AUTOCOMMIT=@OLD_AUTOCOMMIT;

--
-- Table structure for table `pasien_asuransi`
--

DROP TABLE IF EXISTS `pasien_asuransi`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `pasien_asuransi` (
  `id_pasien` int(11) NOT NULL,
  `nama_provider` varchar(50) NOT NULL,
  `nomor_polis` varchar(30) NOT NULL,
  `limit_cover` int(11) NOT NULL,
  PRIMARY KEY (`id_pasien`),
  CONSTRAINT `fk_asuransi_pasien` FOREIGN KEY (`id_pasien`) REFERENCES `pasien` (`id_pasien`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `pasien_asuransi`
--

SET @OLD_AUTOCOMMIT=@@AUTOCOMMIT, @@AUTOCOMMIT=0;
LOCK TABLES `pasien_asuransi` WRITE;
/*!40000 ALTER TABLE `pasien_asuransi` DISABLE KEYS */;
INSERT INTO `pasien_asuransi` VALUES
(11,'Prudential','PRU-1002931',15000000),
(12,'Allianz','ALZ-8830192',5000000),
(13,'AIA','AIA-7730192',20000000),
(14,'AXA Mandiri','AXA-9920132',4000000),
(15,'Prudential','PRU-1002932',25000000),
(16,'Manulife','MNL-5521039',8000000),
(17,'Allianz','ALZ-8830193',30000000),
(18,'Sinarmas','SRM-4410293',3500000),
(19,'AIA','AIA-7730194',18000000),
(20,'AXA Mandiri','AXA-9920135',7000000);
/*!40000 ALTER TABLE `pasien_asuransi` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;
SET AUTOCOMMIT=@OLD_AUTOCOMMIT;

--
-- Table structure for table `pasien_bpjs`
--

DROP TABLE IF EXISTS `pasien_bpjs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `pasien_bpjs` (
  `id_pasien` int(11) NOT NULL,
  `nomor_pbi` varchar(30) NOT NULL,
  `faskes_asal` varchar(100) NOT NULL,
  PRIMARY KEY (`id_pasien`),
  CONSTRAINT `fk_bpjs_pasien` FOREIGN KEY (`id_pasien`) REFERENCES `pasien` (`id_pasien`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `pasien_bpjs`
--

SET @OLD_AUTOCOMMIT=@@AUTOCOMMIT, @@AUTOCOMMIT=0;
LOCK TABLES `pasien_bpjs` WRITE;
/*!40000 ALTER TABLE `pasien_bpjs` DISABLE KEYS */;
INSERT INTO `pasien_bpjs` VALUES
(1,'0001234567891','Puskesmas Cilacap Utara'),
(2,'0001234567892','Puskesmas Cilacap Tengah'),
(3,'0001234567893','Klinik Sehat Bersama'),
(4,'0001234567894','Puskesmas Jeruklegi'),
(5,'0001234567895','Klinik Utama As-Syifa'),
(6,'0001234567896','Puskesmas Kesugihan'),
(7,'0001234567897','Puskesmas Kroya'),
(8,'0001234567898','Klinik Muhammadiyah'),
(9,'0001234567899','Puskesmas Adipala'),
(10,'0001234567900','Klinik Pratama Medika');
/*!40000 ALTER TABLE `pasien_bpjs` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;
SET AUTOCOMMIT=@OLD_AUTOCOMMIT;

--
-- Table structure for table `pasien_umum`
--

DROP TABLE IF EXISTS `pasien_umum`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `pasien_umum` (
  `id_pasien` int(11) NOT NULL,
  `nik` varchar(16) NOT NULL,
  `metode_pembayaran` varchar(20) NOT NULL,
  PRIMARY KEY (`id_pasien`),
  CONSTRAINT `fk_umum_pasien` FOREIGN KEY (`id_pasien`) REFERENCES `pasien` (`id_pasien`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `pasien_umum`
--

SET @OLD_AUTOCOMMIT=@@AUTOCOMMIT, @@AUTOCOMMIT=0;
LOCK TABLES `pasien_umum` WRITE;
/*!40000 ALTER TABLE `pasien_umum` DISABLE KEYS */;
INSERT INTO `pasien_umum` VALUES
(21,'3301011203850001','Tunai'),
(22,'3301021507820002','Debit'),
(23,'3301032211950003','QRIS'),
(24,'3301040805720004','Tunai'),
(25,'3301051909800005','Debit'),
(26,'3301062502650006','Tunai'),
(27,'3301071206680007','QRIS'),
(28,'3301080410620008','Debit'),
(29,'3301093012780009','Tunai'),
(30,'3301101103550010','Tunai');
/*!40000 ALTER TABLE `pasien_umum` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;
SET AUTOCOMMIT=@OLD_AUTOCOMMIT;

--
-- Table structure for table `tarif_kamar`
--

DROP TABLE IF EXISTS `tarif_kamar`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `tarif_kamar` (
  `id_kamar` int(11) NOT NULL AUTO_INCREMENT,
  `nama_kelas` varchar(20) NOT NULL,
  `tarif_per_hari` int(11) NOT NULL,
  PRIMARY KEY (`id_kamar`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tarif_kamar`
--

SET @OLD_AUTOCOMMIT=@@AUTOCOMMIT, @@AUTOCOMMIT=0;
LOCK TABLES `tarif_kamar` WRITE;
/*!40000 ALTER TABLE `tarif_kamar` DISABLE KEYS */;
INSERT INTO `tarif_kamar` VALUES
(1,'KELAS 3',140000),
(2,'KELAS 2',250000),
(3,'KELAS 1',350000),
(4,'VIP',750000);
/*!40000 ALTER TABLE `tarif_kamar` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;
SET AUTOCOMMIT=@OLD_AUTOCOMMIT;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*M!100616 SET NOTE_VERBOSITY=@OLD_NOTE_VERBOSITY */;

-- Dump completed on 2026-06-10 22:49:45
