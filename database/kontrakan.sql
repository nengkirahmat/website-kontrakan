-- phpMyAdmin SQL Dump
-- version 4.2.11
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: Oct 09, 2017 at 09:07 PM
-- Server version: 5.6.21
-- PHP Version: 5.6.3

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `kontrakan`
--

-- --------------------------------------------------------

--
-- Table structure for table `tbl_fasilitas`
--

CREATE TABLE IF NOT EXISTS `tbl_fasilitas` (
`id_fasilitas` int(10) NOT NULL,
  `id_kontrakan` int(10) NOT NULL,
  `fasilitas` varchar(30) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_gambar`
--

CREATE TABLE IF NOT EXISTS `tbl_gambar` (
`id_gambar` int(10) NOT NULL,
  `id_kontrakan` int(10) NOT NULL,
  `gambar` varchar(200) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_kontrakan`
--

CREATE TABLE IF NOT EXISTS `tbl_kontrakan` (
`id_kontrakan` int(10) NOT NULL,
  `kontrakan` varchar(50) NOT NULL,
  `luas_tanah` varchar(15) NOT NULL,
  `luas_kontrakan` varchar(15) NOT NULL,
  `lokasi` text NOT NULL,
  `harga` int(10) NOT NULL,
  `status` varchar(15) NOT NULL,
  `keterangan` text NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_pembayaran`
--

CREATE TABLE IF NOT EXISTS `tbl_pembayaran` (
`id_pembayaran` int(10) NOT NULL,
  `id_penyewa` int(10) NOT NULL,
  `id_kontrakan` int(10) NOT NULL,
  `harga_sewa` int(10) NOT NULL,
  `periode` int(2) NOT NULL,
  `total_biaya` int(10) NOT NULL,
  `jml_bayar` int(10) NOT NULL,
  `sisa` int(10) NOT NULL,
  `status_bayar` varchar(20) NOT NULL,
  `tgl_mulai` varchar(15) NOT NULL,
  `tgl_akhir` varchar(15) NOT NULL,
  `status_sewa` varchar(20) NOT NULL,
  `date_bayar` date NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_penyewa`
--

CREATE TABLE IF NOT EXISTS `tbl_penyewa` (
`id_penyewa` int(10) NOT NULL,
  `no_ktp` varchar(50) NOT NULL,
  `nama_penyewa` varchar(30) NOT NULL,
  `hp` varchar(20) NOT NULL,
  `alamat` text NOT NULL,
  `pekerjaan` varchar(30) NOT NULL,
  `status_hubungan` varchar(15) NOT NULL,
  `umur` int(3) NOT NULL,
  `foto` text NOT NULL,
  `date` datetime NOT NULL,
  `id_user` int(10) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_user`
--

CREATE TABLE IF NOT EXISTS `tbl_user` (
`id_user` int(10) NOT NULL,
  `nama_lengkap` varchar(30) NOT NULL,
  `username` varchar(30) NOT NULL,
  `password` varchar(30) NOT NULL,
  `level` varchar(10) NOT NULL,
  `tgl_daftar` datetime NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tbl_user`
--

INSERT INTO `tbl_user` (`id_user`, `nama_lengkap`, `username`, `password`, `level`, `tgl_daftar`) VALUES
(4, 'admin', 'admin', '1', 'admin', '2017-08-19 03:32:26');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `tbl_fasilitas`
--
ALTER TABLE `tbl_fasilitas`
 ADD PRIMARY KEY (`id_fasilitas`);

--
-- Indexes for table `tbl_gambar`
--
ALTER TABLE `tbl_gambar`
 ADD PRIMARY KEY (`id_gambar`);

--
-- Indexes for table `tbl_kontrakan`
--
ALTER TABLE `tbl_kontrakan`
 ADD PRIMARY KEY (`id_kontrakan`);

--
-- Indexes for table `tbl_pembayaran`
--
ALTER TABLE `tbl_pembayaran`
 ADD PRIMARY KEY (`id_pembayaran`);

--
-- Indexes for table `tbl_penyewa`
--
ALTER TABLE `tbl_penyewa`
 ADD PRIMARY KEY (`id_penyewa`);

--
-- Indexes for table `tbl_user`
--
ALTER TABLE `tbl_user`
 ADD PRIMARY KEY (`id_user`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `tbl_fasilitas`
--
ALTER TABLE `tbl_fasilitas`
MODIFY `id_fasilitas` int(10) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `tbl_gambar`
--
ALTER TABLE `tbl_gambar`
MODIFY `id_gambar` int(10) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=13;
--
-- AUTO_INCREMENT for table `tbl_kontrakan`
--
ALTER TABLE `tbl_kontrakan`
MODIFY `id_kontrakan` int(10) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=4;
--
-- AUTO_INCREMENT for table `tbl_pembayaran`
--
ALTER TABLE `tbl_pembayaran`
MODIFY `id_pembayaran` int(10) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT for table `tbl_penyewa`
--
ALTER TABLE `tbl_penyewa`
MODIFY `id_penyewa` int(10) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT for table `tbl_user`
--
ALTER TABLE `tbl_user`
MODIFY `id_user` int(10) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=5;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
