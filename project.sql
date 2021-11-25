-- phpMyAdmin SQL Dump
-- version 5.1.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 25, 2021 at 05:01 AM
-- Server version: 10.4.19-MariaDB
-- PHP Version: 7.4.19

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `project`
--

-- --------------------------------------------------------

--
-- Table structure for table `message`
--

CREATE TABLE `message` (
  `id` int(11) NOT NULL,
  `sender` int(11) NOT NULL,
  `recv_id` int(11) NOT NULL,
  `body` text COLLATE utf8mb4_swedish_ci NOT NULL,
  `time` varchar(120) COLLATE utf8mb4_swedish_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_swedish_ci;

--
-- Dumping data for table `message`
--

INSERT INTO `message` (`id`, `sender`, `recv_id`, `body`, `time`) VALUES
(10, 10, 11, 'hallo b nama saya adalah a', '1637744731'),
(11, 11, 10, 'hallo a nama saya adalah b', '1637746642'),
(12, 10, 11, 'hallo b kamu tinggal dimana?', '1637748349'),
(13, 12, 11, 'hallo b kamu tinggal dimana?', '1637752325'),
(15, 12, 11, 'hallo b kamu tinggal dimana?', '1637760259'),
(16, 12, 11, 'hello b where is your address?', '1637760292'),
(17, 12, 10, 'hello a where is your address?', '1637811992'),
(18, 12, 10, 'hello a where is your address?', '1637812004'),
(19, 12, 10, 'hello a where is your address?', '1637812014');

-- --------------------------------------------------------

--
-- Table structure for table `token`
--

CREATE TABLE `token` (
  `id` int(11) NOT NULL,
  `username` varchar(120) COLLATE utf8mb4_swedish_ci NOT NULL,
  `token` varchar(120) COLLATE utf8mb4_swedish_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_swedish_ci;

--
-- Dumping data for table `token`
--

INSERT INTO `token` (`id`, `username`, `token`) VALUES
(11, 'c', '5df6fe764554a07bf30ad79a7c40a793');

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `id` int(11) NOT NULL,
  `username` varchar(120) COLLATE utf8mb4_swedish_ci NOT NULL,
  `phone` varchar(120) COLLATE utf8mb4_swedish_ci NOT NULL,
  `password` varchar(120) COLLATE utf8mb4_swedish_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_swedish_ci;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`id`, `username`, `phone`, `password`) VALUES
(10, 'a', '085925103675', '$2y$10$CB5Ge5avfNLN6xOoMJJ8fe9Mf09jfagIqTgODiPXSR./roHB1KvZW'),
(11, 'b', '085925103875', '$2y$10$g0Riu1K9eLgomHfy8tafDO0ITk2BdhoN.aMVoMUxiDNdA6VAQECru'),
(12, 'c', '085925103975', '$2y$10$aS1oi3ZLZlh1dGl8Uk93sumVu99WtGsL/R9HllR8CtMMY6WEQJf6a');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `message`
--
ALTER TABLE `message`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `token`
--
ALTER TABLE `token`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `message`
--
ALTER TABLE `message`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT for table `token`
--
ALTER TABLE `token`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
