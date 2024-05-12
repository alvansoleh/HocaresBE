-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: Apr 30, 2024 at 06:59 AM
-- Server version: 10.11.7-MariaDB-cll-lve
-- PHP Version: 7.2.34

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `u718878629_sim_rs`
--

-- --------------------------------------------------------

--
-- Table structure for table `m_aset`
--

CREATE TABLE `m_aset` (
  `id` int(10) NOT NULL,
  `nama_aset` varchar(255) NOT NULL,
  `jenis_aset` varchar(50) NOT NULL,
  `nomor_seri` varchar(50) NOT NULL,
  `kondisi` enum('tersedia','habis') NOT NULL DEFAULT 'tersedia',
  `harga` int(10) NOT NULL DEFAULT 0,
  `jumlah` int(5) NOT NULL DEFAULT 0,
  `is_delete` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `m_aset`
--

INSERT INTO `m_aset` (`id`, `nama_aset`, `jenis_aset`, `nomor_seri`, `kondisi`, `harga`, `jumlah`, `is_delete`) VALUES
(1, 'Meja Pegawai', 'Meja', '000/000/001', 'tersedia', 4000, 9, 1),
(2, 'Meja Pegawai', 'Meja', '000/000/001', 'tersedia', 1000, 28, 1),
(3, '', 'Meja 1', '000/000/0012', 'tersedia', 2000, 11, 1),
(4, 'Meja Pegawai', 'Meja', '000/000/001', 'tersedia', 1000, 127, 0),
(5, 'Kusri Pegawai', 'Kursi', '000/000/007', 'tersedia', 1000, 10, 0),
(6, '', 'Kursi', '000/000/007', 'tersedia', 1000, 10, 1),
(7, '', 'Penggaris', '000/000/004', 'tersedia', 1000, 10, 1),
(8, 'Meja kaca', '', '000/000/009', 'tersedia', 1000, 10, 1),
(9, '', '', '000/000/0091', 'tersedia', 1000, 10, 1),
(10, 'Komputer', 'Electronic', '000/000/002', 'tersedia', 5000000, 20, 0),
(11, 'Kursi Direktur', 'Kursi', '1', 'tersedia', 5000, 25, 0),
(12, 'Meja Operasi', 'Meja', '111/888/000', 'tersedia', 25000000, 2, 0);

-- --------------------------------------------------------

--
-- Table structure for table `m_backup_recovery`
--

CREATE TABLE `m_backup_recovery` (
  `id` int(5) NOT NULL,
  `activity` enum('backup','recovery') NOT NULL DEFAULT 'backup',
  `tanggal_waktu` varchar(20) NOT NULL,
  `is_delete` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `m_gedung`
--

CREATE TABLE `m_gedung` (
  `id` int(2) NOT NULL,
  `nama_gedung` varchar(50) NOT NULL,
  `is_delete` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `m_gedung`
--

INSERT INTO `m_gedung` (`id`, `nama_gedung`, `is_delete`) VALUES
(1, 'Gedung SINAR', 1),
(2, 'Gedung SINAR mas', 1),
(3, 'Gedung Sinar Mentari', 0),
(4, 'Gedung Tokong Nanas', 1),
(5, 'Gedung Belakang 1', 0),
(6, 'Gedung Belakang 2', 0),
(7, 'Gedung IGD23', 0),
(8, 'Building283', 0),
(9, 'awdawd', 0),
(10, 'hahahaham', 0);

-- --------------------------------------------------------

--
-- Table structure for table `m_lantai`
--

CREATE TABLE `m_lantai` (
  `id` int(3) NOT NULL,
  `nama_lantai` varchar(50) NOT NULL,
  `id_gedung` int(2) NOT NULL,
  `is_delete` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `m_lantai`
--

INSERT INTO `m_lantai` (`id`, `nama_lantai`, `id_gedung`, `is_delete`) VALUES
(1, 'Lantai 2 (Ruang Restroom)', 1, 1),
(2, 'Lantai 2 (Ruang Rapat)', 1, 1),
(3, 'Lantai Basement', 3, 0),
(4, 'Gedung Terhapus', 3, 1),
(5, 'Lantai Darurat', 7, 0);

-- --------------------------------------------------------

--
-- Table structure for table `m_menus`
--

CREATE TABLE `m_menus` (
  `id` int(2) NOT NULL,
  `nama_menu` varchar(50) NOT NULL,
  `path` varchar(50) NOT NULL,
  `icon` varchar(50) DEFAULT NULL,
  `is_delete` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `m_menus`
--

INSERT INTO `m_menus` (`id`, `nama_menu`, `path`, `icon`, `is_delete`) VALUES
(1, 'Laporan Jadwal Pemeliharaan', '/dashboard/report/maintenance', NULL, 0),
(2, 'Profil', '/dashboard/profile', NULL, 0),
(3, 'Kelola Tempat', '/dashboard/places/building', NULL, 0),
(4, 'Kelola Pengguna', '/dashboard/users', NULL, 0),
(5, 'Kelola Aset', '/dashboard/assets/general', NULL, 0),
(6, 'Jadwal Pemeliharaan', '/dashboard/maintenance', NULL, 0),
(7, 'Laporan Gedung', '/dashboard/report/place/building', NULL, 0),
(8, 'Laporan Lantai', '/dashboard/report/place/floor', NULL, 0),
(9, 'Laporan Ruangan', '/dashboard/report/place/room', NULL, 0),
(10, 'Laporan Aset', '/dashboard/report/asset', NULL, 0);

-- --------------------------------------------------------

--
-- Table structure for table `m_ruangan`
--

CREATE TABLE `m_ruangan` (
  `id` int(4) NOT NULL,
  `nama_ruangan` varchar(50) NOT NULL,
  `id_lantai` int(3) NOT NULL,
  `is_delete` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `m_ruangan`
--

INSERT INTO `m_ruangan` (`id`, `nama_ruangan`, `id_lantai`, `is_delete`) VALUES
(1, 'Loket Radiology', 1, 1),
(3, 'Ruangan Lab', 1, 0),
(5, 'Ruang D4', 3, 0),
(6, 'Ruang B5', 3, 1),
(7, 'Ruang B5', 3, 0),
(8, 'IGD A.01', 5, 0),
(9, 'IGD B.01', 5, 0);

-- --------------------------------------------------------

--
-- Table structure for table `m_sub_ruangan`
--

CREATE TABLE `m_sub_ruangan` (
  `id` int(5) NOT NULL,
  `kode_sub_ruangan` varchar(10) DEFAULT NULL,
  `nama_sub_ruangan` varchar(255) NOT NULL,
  `id_ruangan` int(4) NOT NULL,
  `is_delete` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `m_users`
--

CREATE TABLE `m_users` (
  `id` int(5) NOT NULL,
  `full_name` varchar(255) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(50) NOT NULL,
  `email` varchar(50) DEFAULT NULL,
  `no_hp` varchar(15) DEFAULT NULL,
  `is_delete` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `m_users`
--

INSERT INTO `m_users` (`id`, `full_name`, `username`, `password`, `email`, `no_hp`, `is_delete`) VALUES
(1, 'Andy Maulana', 'andy', 'programer', 'goingtoprofandy@gmail.com', '085173258992', 1),
(2, 'Andy Maulana', 'Rinceu', 'programer', 'goingtoprofandy@gmail.com', '085173258992', 1),
(3, 'Andy Maulana Yusuf', 'andy2', 'programer', 'goingtoprofandy@gmail.com', '085173258992', 0),
(4, 'Andy Maulana', 'andyh', 'programer', 'goingtoprofandy@gmail.com', '085173258992', 0),
(5, 'Andy Maulana Yusuf', 'andyA', 'programer', 'goingtoprofandy@gmail.com', '085173258992', 0),
(6, 'Andy Maulana', '', 'programer', 'goingtoprofandy@gmail.com', '085173258992', 1),
(7, 'Andy Maulana Yusuf', 'andyaA', '', 'goingtoprofandy@gmail.com', '085173258992', 0),
(8, 'Andy Maulana', 'andyaAd', 'programer', 'goingtoprofandy@gmail.com', '085173258992', 0),
(9, 'Andy Maulana Yusuf', 'andyaaa', 'programer', 'goingtoprofandy@gmail.com', '085173258992', 0),
(10, 'Andy Maulana Yusuf', 'andyaAk', 'programer', 'goingtoprofandy@gmail.com', '085173258992', 0),
(11, 'Andy Maulana Yusuf rinakkaa', 'Rina', 'programer', 'goingtoprofandy@gmail.com', '085173258992', 0),
(12, 'Andy Maulana Yusuf', 'andy1', 'programer', 'goingtoprofandy@gmail.com', '085173258992', 0),
(13, 'Andy Maulana Yusuf', 'iCHA', 'programer', 'goingtoprofandy@gmail.com', '085173258992', 0),
(14, 'Add', 'hhh', 'ddf', 'fff@gmail.com', '000', 0),
(15, 'Addd', 'jkkjkk', '', 'fffddd@gmail.com', '00009', 0),
(16, '', 'jkkjkks', 'sfsfsf', 'fffddd@gssmail.com', '0000569', 0),
(17, 'jhjgjgj', 'jkkws', 'sfdsf', '', '00005d69', 0),
(18, 'jhwjgjgj', 'jkkwts', 'sfsfw', 'dddsdw', '', 0),
(19, 'Andy Maulana Yusuf', 'andy maul', 'programer', 'goingtoprofandy@gmail.com', '085173258992', 0),
(20, 'Andy Maulana Yusuf sih', 'Andy siap', '', 'goingtoprofandy1@gmail.com', '0851732589922', 0),
(21, '', 'Andy siap maju', 'programer54', 'akugoingtoprofandy2@gmail.com', '08851732589922', 1),
(22, 'Alvan Alfiansyah', 'Taccoess', 'Zecxeed24', 'alvansoleh@gmail.com', '087775624635', 0),
(23, 'AGI', 'AGI', '12345', 'alghifary0812@gmail.com', '081122334455', 0);

-- --------------------------------------------------------

--
-- Table structure for table `u_aset_mapping`
--

CREATE TABLE `u_aset_mapping` (
  `id` int(10) NOT NULL,
  `id_aset` int(10) NOT NULL,
  `id_gedung` int(10) DEFAULT NULL,
  `id_lantai` int(10) DEFAULT NULL,
  `id_ruangan` int(10) DEFAULT NULL,
  `is_delete` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `u_aset_mapping`
--

INSERT INTO `u_aset_mapping` (`id`, `id_aset`, `id_gedung`, `id_lantai`, `id_ruangan`, `is_delete`) VALUES
(1, 10, 3, 3, 7, 0),
(2, 10, 3, NULL, NULL, 1),
(3, 10, 3, 3, 5, 0),
(4, 4, 3, 3, 5, 0),
(5, 4, 7, 5, 8, 0),
(6, 4, 3, 3, NULL, 0);

-- --------------------------------------------------------

--
-- Table structure for table `u_maintenance_schedule_aset`
--

CREATE TABLE `u_maintenance_schedule_aset` (
  `id` int(10) NOT NULL,
  `id_aset` int(10) NOT NULL,
  `id_pencatat` int(10) NOT NULL,
  `tanggal_waktu` varchar(20) NOT NULL,
  `catatan` text DEFAULT NULL,
  `status` enum('terjadwal','berlangsung','selesai') NOT NULL DEFAULT 'terjadwal',
  `is_delete` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `u_maintenance_schedule_aset`
--

INSERT INTO `u_maintenance_schedule_aset` (`id`, `id_aset`, `id_pencatat`, `tanggal_waktu`, `catatan`, `status`, `is_delete`) VALUES
(1, 1, 1, '30/03/2024 09:30', 'Testing Schedule', 'berlangsung', 1),
(2, 1, 1, '30/03/2024 09:30', 'Testing', 'terjadwal', 1),
(3, 4, 3, '15/04/2024 13:00', 'Mohon dibantu oleh Aslab', 'selesai', 1),
(4, 4, 3, '10/07/2024', 'Pengecekan kondisi meja', 'selesai', 0),
(5, 10, 3, '21/04/2024', '5', 'terjadwal', 0),
(6, 11, 3, '15/07/2024', '', 'selesai', 0);

-- --------------------------------------------------------

--
-- Table structure for table `u_riwayat_aset`
--

CREATE TABLE `u_riwayat_aset` (
  `id` int(10) NOT NULL,
  `id_aset` int(10) NOT NULL,
  `id_pencatat` int(10) NOT NULL,
  `jenis_transaksi` enum('masuk','keluar') NOT NULL,
  `jumlah` int(5) NOT NULL DEFAULT 0,
  `status_keluar` enum('rusak','pinjam','dijual') DEFAULT NULL,
  `is_closed` tinyint(1) NOT NULL DEFAULT 1,
  `is_from_change` tinyint(1) NOT NULL DEFAULT 0,
  `is_delete` tinyint(1) NOT NULL DEFAULT 0,
  `keterangan` text DEFAULT NULL,
  `tgl_transaksi` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `u_riwayat_aset`
--

INSERT INTO `u_riwayat_aset` (`id`, `id_aset`, `id_pencatat`, `jenis_transaksi`, `jumlah`, `status_keluar`, `is_closed`, `is_from_change`, `is_delete`, `keterangan`, `tgl_transaksi`) VALUES
(1, 1, 1, 'masuk', 10, NULL, 1, 0, 0, 'Meja masuk 10 pcs', '2024-02-28 17:27:42'),
(2, 1, 1, 'keluar', 1, 'rusak', 1, 0, 0, 'Ada 1 meja yang rusak', '2024-02-28 17:45:21'),
(3, 1, 1, 'keluar', 1, 'pinjam', 1, 0, 0, 'Poli paru pinjam 1 meja', '2024-02-28 17:46:18'),
(4, 1, 1, 'masuk', 10, NULL, 1, 0, 0, 'Meja masuk 10 pcs', '2024-03-10 13:10:32'),
(5, 2, 1, 'masuk', 5, NULL, 1, 0, 0, 'Kasur masuk 10 pcs', '2024-03-10 13:11:04'),
(6, 2, 1, 'masuk', 5, NULL, 1, 0, 0, 'Kasur masuk 5 pcs', '2024-03-10 13:11:10'),
(7, 2, 2, 'masuk', 4, NULL, 1, 0, 0, 'Kasur masuk 5 pcs', '2024-03-10 13:15:08'),
(8, 2, 2, 'masuk', 4, NULL, 1, 0, 0, '', '2024-03-10 13:16:24'),
(9, 1, 1, 'keluar', 1, 'pinjam', 0, 0, 0, 'Poli paru pinjam 1 meja', '2024-03-10 13:19:23'),
(10, 4, 1, 'keluar', 1, 'pinjam', 1, 0, 0, 'Poli paru pinjam 1 meja', '2024-03-10 13:21:06'),
(11, 4, 5, 'keluar', 4, 'pinjam', 0, 0, 0, 'Poli paru pinjam 1 meja', '2024-03-10 13:22:46'),
(12, 4, 5, 'keluar', 4, 'rusak', 1, 0, 0, '', '2024-03-10 13:24:03'),
(13, 4, 3, 'masuk', 100, NULL, 1, 0, 0, 'Pengadaan untuk 10 Gedung Baru', '2024-04-12 08:54:05'),
(14, 4, 3, 'masuk', 10, NULL, 1, 0, 0, 'perhatikan kembali apakah ada kecacatan ', '2024-04-19 21:24:56'),
(15, 4, 3, 'masuk', 5, NULL, 1, 0, 0, 'perhatikan kembali apakah ada kecacatan ', '2024-04-19 21:26:11'),
(16, 4, 3, 'masuk', 5, NULL, 1, 0, 0, 'Kebutuhan ruangan poli gigi', '2024-04-20 11:10:09'),
(17, 4, 3, 'masuk', 2, NULL, 1, 0, 0, 'Kebutuhan ruangan cadangan', '2024-04-20 11:20:50'),
(18, 4, 3, 'masuk', 12, NULL, 1, 0, 0, '', '2024-04-20 13:23:49'),
(19, 11, 3, 'masuk', 10, NULL, 1, 0, 0, '', '2024-04-21 10:36:25'),
(20, 11, 3, 'masuk', 20, NULL, 1, 0, 0, '', '2024-04-21 17:41:33'),
(21, 11, 3, 'keluar', 1, NULL, 1, 1, 0, 'User melakukan update jumlah pada data master aset', '2024-04-21 17:51:00'),
(22, 11, 3, 'keluar', 5, 'rusak', 1, 0, 0, '', '2024-04-21 18:48:36'),
(23, 4, 3, 'keluar', 12, 'dijual', 1, 0, 0, 'Sudah tidak memenuhi standar', '2024-04-22 20:52:29'),
(24, 4, 3, 'keluar', 10, 'dijual', 1, 0, 0, 'Sudah tidak memenuhi standar', '2024-04-22 20:52:49');

-- --------------------------------------------------------

--
-- Table structure for table `u_user_access_menu`
--

CREATE TABLE `u_user_access_menu` (
  `id` int(5) NOT NULL,
  `id_user` int(5) NOT NULL,
  `id_menu` int(2) NOT NULL,
  `lihat` tinyint(1) NOT NULL DEFAULT 0,
  `tambah` tinyint(1) NOT NULL DEFAULT 0,
  `ubah` tinyint(1) NOT NULL DEFAULT 0,
  `hapus` tinyint(1) NOT NULL DEFAULT 0,
  `is_delete` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `u_user_access_menu`
--

INSERT INTO `u_user_access_menu` (`id`, `id_user`, `id_menu`, `lihat`, `tambah`, `ubah`, `hapus`, `is_delete`) VALUES
(1, 3, 1, 1, 0, 0, 1, 1),
(2, 3, 3, 1, 0, 0, 0, 1),
(3, 3, 5, 1, 1, 1, 0, 1),
(4, 3, 3, 1, 1, 1, 1, 1),
(5, 3, 1, 1, 0, 0, 0, 0),
(6, 3, 2, 1, 0, 0, 0, 0),
(7, 3, 4, 1, 1, 1, 1, 0),
(9, 3, 7, 1, 0, 0, 0, 1),
(10, 3, 10, 1, 0, 0, 0, 0),
(11, 3, 5, 1, 0, 1, 0, 0),
(12, 3, 6, 1, 1, 1, 1, 0),
(13, 3, 9, 0, 0, 0, 0, 0),
(14, 3, 8, 0, 0, 0, 0, 0),
(15, 13, 8, 1, 0, 0, 0, 0),
(16, 13, 10, 1, 0, 0, 0, 0),
(17, 13, 9, 1, 0, 0, 0, 0),
(19, 13, 6, 1, 0, 0, 0, 0),
(20, 13, 7, 1, 0, 0, 0, 0),
(21, 13, 3, 1, 0, 0, 0, 0),
(22, 13, 4, 1, 0, 0, 0, 0),
(23, 13, 2, 1, 0, 0, 0, 0),
(24, 13, 5, 1, 0, 0, 0, 0),
(25, 23, 7, 1, 0, 0, 0, 0),
(26, 23, 2, 1, 0, 0, 0, 0),
(27, 23, 10, 1, 0, 0, 0, 0),
(28, 23, 9, 1, 0, 0, 0, 0),
(29, 23, 5, 1, 0, 0, 0, 0),
(30, 23, 8, 1, 0, 0, 0, 0),
(31, 23, 4, 1, 0, 0, 0, 0),
(32, 23, 1, 1, 0, 0, 0, 0),
(33, 23, 3, 1, 0, 0, 0, 0),
(34, 23, 6, 1, 0, 0, 0, 0),
(35, 3, 7, 1, 0, 0, 0, 0);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `m_aset`
--
ALTER TABLE `m_aset`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `m_backup_recovery`
--
ALTER TABLE `m_backup_recovery`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `m_gedung`
--
ALTER TABLE `m_gedung`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `m_lantai`
--
ALTER TABLE `m_lantai`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_gedung` (`id_gedung`);

--
-- Indexes for table `m_menus`
--
ALTER TABLE `m_menus`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `m_ruangan`
--
ALTER TABLE `m_ruangan`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_lantai` (`id_lantai`);

--
-- Indexes for table `m_sub_ruangan`
--
ALTER TABLE `m_sub_ruangan`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_ruangan` (`id_ruangan`);

--
-- Indexes for table `m_users`
--
ALTER TABLE `m_users`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `u_aset_mapping`
--
ALTER TABLE `u_aset_mapping`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_aset` (`id_aset`),
  ADD KEY `id_building` (`id_gedung`),
  ADD KEY `id_lantai` (`id_lantai`),
  ADD KEY `id_ruangan` (`id_ruangan`);

--
-- Indexes for table `u_maintenance_schedule_aset`
--
ALTER TABLE `u_maintenance_schedule_aset`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_aset` (`id_aset`),
  ADD KEY `id_pencatat` (`id_pencatat`);

--
-- Indexes for table `u_riwayat_aset`
--
ALTER TABLE `u_riwayat_aset`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_aset` (`id_aset`),
  ADD KEY `id_pencatat` (`id_pencatat`);

--
-- Indexes for table `u_user_access_menu`
--
ALTER TABLE `u_user_access_menu`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_user` (`id_user`),
  ADD KEY `id_menu` (`id_menu`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `m_aset`
--
ALTER TABLE `m_aset`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `m_backup_recovery`
--
ALTER TABLE `m_backup_recovery`
  MODIFY `id` int(5) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `m_gedung`
--
ALTER TABLE `m_gedung`
  MODIFY `id` int(2) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `m_lantai`
--
ALTER TABLE `m_lantai`
  MODIFY `id` int(3) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `m_menus`
--
ALTER TABLE `m_menus`
  MODIFY `id` int(2) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `m_ruangan`
--
ALTER TABLE `m_ruangan`
  MODIFY `id` int(4) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `m_sub_ruangan`
--
ALTER TABLE `m_sub_ruangan`
  MODIFY `id` int(5) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `m_users`
--
ALTER TABLE `m_users`
  MODIFY `id` int(5) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- AUTO_INCREMENT for table `u_aset_mapping`
--
ALTER TABLE `u_aset_mapping`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `u_maintenance_schedule_aset`
--
ALTER TABLE `u_maintenance_schedule_aset`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `u_riwayat_aset`
--
ALTER TABLE `u_riwayat_aset`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT for table `u_user_access_menu`
--
ALTER TABLE `u_user_access_menu`
  MODIFY `id` int(5) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=36;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `m_lantai`
--
ALTER TABLE `m_lantai`
  ADD CONSTRAINT `r_gedung_dari_lantai` FOREIGN KEY (`id_gedung`) REFERENCES `m_gedung` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `m_ruangan`
--
ALTER TABLE `m_ruangan`
  ADD CONSTRAINT `r_lantai_tempat_ruangan` FOREIGN KEY (`id_lantai`) REFERENCES `m_lantai` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `m_sub_ruangan`
--
ALTER TABLE `m_sub_ruangan`
  ADD CONSTRAINT `r_ruangan_induk` FOREIGN KEY (`id_ruangan`) REFERENCES `m_ruangan` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `u_aset_mapping`
--
ALTER TABLE `u_aset_mapping`
  ADD CONSTRAINT `r_aset_yang_di_mapping` FOREIGN KEY (`id_aset`) REFERENCES `m_aset` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `r_dimapping_ke_gedung` FOREIGN KEY (`id_gedung`) REFERENCES `m_gedung` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `r_dimapping_ke_lantai` FOREIGN KEY (`id_lantai`) REFERENCES `m_lantai` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `r_dimapping_ke_ruangan` FOREIGN KEY (`id_ruangan`) REFERENCES `m_ruangan` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `u_maintenance_schedule_aset`
--
ALTER TABLE `u_maintenance_schedule_aset`
  ADD CONSTRAINT `r_aset_yang_di_maintenance` FOREIGN KEY (`id_aset`) REFERENCES `m_aset` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `r_user_yang_me_maintenance` FOREIGN KEY (`id_pencatat`) REFERENCES `m_users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `u_riwayat_aset`
--
ALTER TABLE `u_riwayat_aset`
  ADD CONSTRAINT `r_transaksi_dari_aset` FOREIGN KEY (`id_aset`) REFERENCES `m_aset` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `r_user_yang_melakukan_transaksi_aset` FOREIGN KEY (`id_pencatat`) REFERENCES `m_users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `u_user_access_menu`
--
ALTER TABLE `u_user_access_menu`
  ADD CONSTRAINT `r_menu_yang_bisa_diakses` FOREIGN KEY (`id_menu`) REFERENCES `m_menus` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `r_user_yang_punya_akses` FOREIGN KEY (`id_user`) REFERENCES `m_users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
