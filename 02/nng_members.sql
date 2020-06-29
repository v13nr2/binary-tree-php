-- phpMyAdmin SQL Dump
-- version 4.9.4
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Jun 29, 2020 at 12:19 PM
-- Server version: 10.2.31-MariaDB
-- PHP Version: 7.3.6

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `u7154823_api`
--

-- --------------------------------------------------------

--
-- Table structure for table `nng_members`
--

DROP TABLE IF EXISTS `nng_members`;
CREATE TABLE `nng_members` (
  `id` int(11) NOT NULL,
  `nomor` varchar(10) NOT NULL,
  `username` varchar(200) NOT NULL DEFAULT '',
  `nama` varchar(150) NOT NULL,
  `induk_id` int(11) DEFAULT NULL,
  `foto` varchar(150) NOT NULL,
  `sisi` varchar(5) DEFAULT NULL,
  `omset` int(11) NOT NULL,
  `date_created` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `nng_members`
--

INSERT INTO `nng_members` (`id`, `nomor`, `username`, `nama`, `induk_id`, `foto`, `sisi`, `omset`, `date_created`) VALUES
(1, '11001', '', 'Sylvia', 0, 'person.png', NULL, 0, '2020-06-28 05:30:16'),
(2, '11002', '', 'Arman Maulana', 1, 'person.png', 'kiri', 100, '2020-06-28 05:30:16'),
(3, '11003', '', 'Zahira', 1, 'person.png', 'kanan', 100, '2020-06-28 05:30:16'),
(4, '11004', '', 'pidi baiq', 2, 'person.png', 'kiri', 100, '2020-06-28 05:30:16'),
(5, '11005', '', 'raditya dika', 4, 'person.png', 'kiri', 100, '2020-06-28 05:30:16'),
(6, '11006', '', 'Andi Syamsul', 3, 'person.png', 'kiri', 100, '2020-06-28 05:30:16'),
(7, '11007', '', 'sudirman', 2, 'person.png', 'kanan', 100, '2020-06-28 05:30:16'),
(8, '11008', '', 'tereliye', 3, 'person.png', 'kanan', 100, '2020-06-28 05:30:16'),
(17, '11009', '', 'Nanang Rustianto', 8, 'person.png', 'kiri', 100, '2020-06-28 05:30:16'),
(18, '11010', '', 'Arif Rahman Wijaya', 17, 'person.png', 'kiri', 200, '2020-06-28 05:30:16');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `nng_members`
--
ALTER TABLE `nng_members`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `nng_members`
--
ALTER TABLE `nng_members`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
