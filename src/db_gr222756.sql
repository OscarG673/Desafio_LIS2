-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Mar 30, 2025 at 11:49 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.1.25

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `db_gr222756`
--

-- --------------------------------------------------------

--
-- Table structure for table `entradas`
--

CREATE TABLE `entradas` (
  `id` int(100) NOT NULL,
  `type` varchar(255) NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `date` date NOT NULL,
  `bill` varchar(255) NOT NULL,
  `user_id` int(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `entradas`
--

INSERT INTO `entradas` (`id`, `type`, `amount`, `date`, `bill`, `user_id`) VALUES
(16, 'salario', 1000.00, '2025-03-30', '5b543096-c4af-49d2-9956-7ea39b799b37.jpg', 8),
(17, 'salario', 1000.00, '2024-12-12', 'tweet2-burkov.png', 1),
(18, 'salario', 100.00, '2024-12-21', '2.png', 1);

-- --------------------------------------------------------

--
-- Table structure for table `salidas`
--

CREATE TABLE `salidas` (
  `id` int(100) NOT NULL,
  `type` varchar(255) NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `date` date NOT NULL,
  `bill` varchar(255) NOT NULL,
  `user_id` int(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `salidas`
--

INSERT INTO `salidas` (`id`, `type`, `amount`, `date`, `bill`, `user_id`) VALUES
(16, 'comida', 1000.00, '2024-12-21', '2.png', 1);

-- --------------------------------------------------------

--
-- Table structure for table `usuarios`
--

CREATE TABLE `usuarios` (
  `id` int(100) NOT NULL,
  `fullname` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `usuarios`
--

INSERT INTO `usuarios` (`id`, `fullname`, `email`, `password`) VALUES
(1, 'Oscar Guevara', 'oguevara1969@gmail.com', '$2y$10$YYHbPSxR4wenCtu3NFANL.jwSSQ4tk/1kgIcMD7wvUqJ/JcstQCc2'),
(2, 'Don quijote', 'sancho@gmail.com', '$2y$10$8w1lR1j7El3MsVudLV3MieAJ6gTZpve77.ygZdLJ6t.BxTY4A9ZZ2'),
(4, 'quijote', 'quijote@gmail.com', '$2y$10$UAR8gu7YarKKOkaVadC.QOJ5zoIzRMtryeZX8D4Ma5BN/bf5xC.Am'),
(5, 'PUPUSAS', 'pupusas@gmail.com', '$2y$10$2bCEWitdzNzXkLOyh644Re22dbfs3yT.tXvIczSMBlII05pAjjMqi'),
(6, 'pupusas', 'pupusas@gmail.com', '$2y$10$7qRYaaIW9u6jAh6CwVTmxuhmBhU870/mWusI.WXVn2iaNTGadmfRK'),
(7, 'Jonathan', 'jona@gmail.com', '$2y$10$/ZLS6o0typZbioIzqd8L.u3p6/XJXRdxmi5p0RMRdFq2GWuQcjUUe'),
(8, 'jona', 'jona@gmail.com', '$2y$10$zGBV19YBLxBfs1ADbgYbd.Zgd0wjnuZHKoxivkbP62B6C2dBHM8wy'),
(9, 'sapo', 'sapo@gmail.com', '$2y$10$jtRMmwaXMqjVm0cpV4WJnehQWDEDS7WOSrsHCNLzJ96Q754ApPpL.');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `entradas`
--
ALTER TABLE `entradas`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `salidas`
--
ALTER TABLE `salidas`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `entradas`
--
ALTER TABLE `entradas`
  MODIFY `id` int(100) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `salidas`
--
ALTER TABLE `salidas`
  MODIFY `id` int(100) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id` int(100) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `entradas`
--
ALTER TABLE `entradas`
  ADD CONSTRAINT `entradas_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `usuarios` (`id`);

--
-- Constraints for table `salidas`
--
ALTER TABLE `salidas`
  ADD CONSTRAINT `salidas_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `usuarios` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
