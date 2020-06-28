-- phpMyAdmin SQL Dump
-- version 4.8.5
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Jun 27, 2020 at 02:42 PM
-- Server version: 10.1.38-MariaDB
-- PHP Version: 7.3.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `projects_binarytree`
--

-- --------------------------------------------------------

--
-- Table structure for table `members`
--

CREATE TABLE `members` (
  `id` int(11) NOT NULL,
  `nomor` varchar(10) NOT NULL,
  `nama` varchar(150) NOT NULL,
  `induk_id` int(11) DEFAULT NULL,
  `foto` varchar(150) NOT NULL,
  `sisi` varchar(5) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `members`
--

INSERT INTO `members` (`id`, `nomor`, `nama`, `induk_id`, `foto`, `sisi`) VALUES
(1, '11001', 'bjhabibie', 0, 'bjhabibie.jpg', NULL),
(2, '11002', 'bung tomo', 1, 'bung tomo.jpeg', 'kiri'),
(3, '11003', 'muhammad hatta', 1, 'muhammad hatta.jpeg', 'kanan'),
(4, '11004', 'pidi baiq', 2, 'pidi baiq.jpeg', 'kiri'),
(5, '11005', 'raditya dika', 4, 'raditya dika.jpeg', 'kanan'),
(6, '11006', 'soekarno', 3, 'soekarno.jpg', 'kiri'),
(7, '11007', 'sudirman', 2, 'sudirman.jpg', 'kanan'),
(8, '11008', 'tereliye', 3, 'tereliye.jpeg', 'kanan');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `members`
--
ALTER TABLE `members`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `members`
--
ALTER TABLE `members`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
