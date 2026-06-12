-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Jun 12, 2026 at 03:12 AM
-- Server version: 8.0.30
-- PHP Version: 8.1.10

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `db_latihan_pbo_trpl1a_almas`
--

-- --------------------------------------------------------

--
-- Table structure for table `tabel_tiket`
--

CREATE TABLE `tabel_tiket` (
  `id_tiket` varchar(20) NOT NULL,
  `nama_film` varchar(100) NOT NULL,
  `jadwal_tayang` datetime NOT NULL,
  `jumlah_kursi` int NOT NULL,
  `harga_dasar_tiket` decimal(10,2) NOT NULL,
  `jenis_studio` enum('reguler','IMAX','velvet') NOT NULL,
  `tipe_audio` varchar(50) DEFAULT NULL,
  `lokasi_baris` varchar(10) DEFAULT NULL,
  `kacamata_3d_id` varchar(20) DEFAULT NULL,
  `efek_gerak_fitur` tinyint(1) DEFAULT NULL,
  `bantal_selimut_pack` tinyint(1) DEFAULT NULL,
  `layanan_butler` tinyint(1) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `tabel_tiket`
--

INSERT INTO `tabel_tiket` (`id_tiket`, `nama_film`, `jadwal_tayang`, `jumlah_kursi`, `harga_dasar_tiket`, `jenis_studio`, `tipe_audio`, `lokasi_baris`, `kacamata_3d_id`, `efek_gerak_fitur`, `bantal_selimut_pack`, `layanan_butler`) VALUES
('TKT-IMX-001', 'Avatar 3: The Seed Bearer', '2026-06-20 14:00:00', 2, '85000.00', 'IMAX', NULL, NULL, 'GLS-3D-101', 1, NULL, NULL),
('TKT-IMX-002', 'Avengers: Secret Wars', '2026-06-20 19:30:00', 4, '90000.00', 'IMAX', NULL, NULL, 'GLS-3D-102', 1, NULL, NULL),
('TKT-IMX-003', 'Godzilla x Kong', '2026-06-21 13:15:00', 3, '85000.00', 'IMAX', NULL, NULL, 'GLS-3D-103', 0, NULL, NULL),
('TKT-IMX-004', 'Transformers: Unicron', '2026-06-21 16:45:00', 2, '85000.00', 'IMAX', NULL, NULL, 'GLS-3D-104', 1, NULL, NULL),
('TKT-IMX-005', 'Dune: Messiah', '2026-06-22 20:00:00', 2, '90000.00', 'IMAX', NULL, NULL, 'GLS-3D-105', 0, NULL, NULL),
('TKT-IMX-006', 'Interstellar (Re-release)', '2026-06-23 18:30:00', 1, '100000.00', 'IMAX', NULL, NULL, 'GLS-3D-106', 0, NULL, NULL),
('TKT-REG-001', 'Avengers: Secret Wars', '2026-06-20 13:00:00', 2, '45000.00', 'reguler', 'Dolby 7.1', 'F', NULL, NULL, NULL, NULL),
('TKT-REG-002', 'Spider-Man: Beyond', '2026-06-20 15:30:00', 3, '45000.00', 'reguler', 'Dolby 7.1', 'H', NULL, NULL, NULL, NULL),
('TKT-REG-003', 'Dune: Messiah', '2026-06-21 12:15:00', 1, '50000.00', 'reguler', 'Dolby Atmos', 'E', NULL, NULL, NULL, NULL),
('TKT-REG-004', 'Fast X: Part 2', '2026-06-21 18:45:00', 4, '50000.00', 'reguler', 'Dolby Atmos', 'C', NULL, NULL, NULL, NULL),
('TKT-REG-005', 'Mission: Impossible 8', '2026-06-22 14:00:00', 2, '45000.00', 'reguler', 'Dolby 7.1', 'G', NULL, NULL, NULL, NULL),
('TKT-REG-006', 'Kung Fu Panda 5', '2026-06-22 10:30:00', 5, '40000.00', 'reguler', 'Dolby 7.1', 'A', NULL, NULL, NULL, NULL),
('TKT-REG-007', 'The Batman II', '2026-06-23 20:00:00', 2, '55000.00', 'reguler', 'Dolby Atmos', 'D', NULL, NULL, NULL, NULL),
('TKT-REG-008', 'Joker: Folie', '2026-06-23 21:15:00', 2, '55000.00', 'reguler', 'Dolby Atmos', 'B', NULL, NULL, NULL, NULL),
('TKT-VLV-001', 'The Great Gatsby', '2026-06-20 19:00:00', 2, '150000.00', 'velvet', NULL, NULL, NULL, NULL, 1, 1),
('TKT-VLV-002', 'Oppenheimer', '2026-06-20 21:30:00', 2, '150000.00', 'velvet', NULL, NULL, NULL, NULL, 1, 0),
('TKT-VLV-003', 'Dune: Messiah', '2026-06-21 20:15:00', 4, '160000.00', 'velvet', NULL, NULL, NULL, NULL, 1, 1),
('TKT-VLV-004', 'A Quiet Place: Day One', '2026-06-21 22:45:00', 2, '140000.00', 'velvet', NULL, NULL, NULL, NULL, 0, 1),
('TKT-VLV-005', 'Titanic (Remastered)', '2026-06-22 18:00:00', 2, '175000.00', 'velvet', NULL, NULL, NULL, NULL, 1, 1),
('TKT-VLV-006', 'Joker: Folie', '2026-06-23 19:30:00', 2, '150000.00', 'velvet', NULL, NULL, NULL, NULL, 1, 0);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `tabel_tiket`
--
ALTER TABLE `tabel_tiket`
  ADD PRIMARY KEY (`id_tiket`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
