-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Jun 22, 2025 at 04:09 AM
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
-- Database: `cashiary2`
--

-- --------------------------------------------------------

--
-- Table structure for table `kategori`
--

CREATE TABLE `kategori` (
  `kategori_id` int NOT NULL,
  `user_id` int DEFAULT NULL,
  `nama_kategori` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `jenis` enum('pemasukan','pengeluaran') NOT NULL DEFAULT 'pengeluaran'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `kategori`
--

INSERT INTO `kategori` (`kategori_id`, `user_id`, `nama_kategori`, `jenis`) VALUES
(1, NULL, 'Makanan & Minuman', 'pengeluaran'),
(2, NULL, 'Transportasi', 'pengeluaran'),
(3, NULL, 'Hiburan', 'pengeluaran'),
(5, NULL, 'Kesehatan & Obat-obatan', 'pengeluaran'),
(6, NULL, 'Pendidikan', 'pengeluaran'),
(7, NULL, 'Gaji', 'pemasukan'),
(9, NULL, 'Hadiah', 'pemasukan'),
(15, 5, 'Gaji', 'pemasukan'),
(17, 5, 'Hadiah', 'pemasukan'),
(19, 5, 'Lainnya (Pemasukan)', 'pemasukan'),
(21, 5, 'Makanan & Minuman', 'pengeluaran'),
(23, 5, 'Transportasi', 'pengeluaran'),
(25, 5, 'Tagihan & Utilitas', 'pengeluaran'),
(27, 5, 'Hiburan', 'pengeluaran'),
(29, 5, 'Kesehatan', 'pengeluaran'),
(31, 5, 'Pendidikan', 'pengeluaran'),
(33, 5, 'Lainnya (Pengeluaran)', 'pengeluaran'),
(35, 7, 'Makanan & Minuman', 'pengeluaran'),
(37, 7, 'Transportasi', 'pengeluaran'),
(39, 7, 'Hiburan', 'pengeluaran'),
(41, 7, 'Kesehatan & Obat-obatan', 'pengeluaran'),
(43, 7, 'Pendidikan', 'pengeluaran'),
(45, 7, 'Gaji', 'pemasukan'),
(47, 7, 'Hadiah', 'pemasukan'),
(49, 2, 'Makanan & Minuman', 'pengeluaran'),
(51, 2, 'Transportasi', 'pengeluaran'),
(53, 2, 'Hiburan', 'pengeluaran'),
(55, 2, 'Kesehatan & Obat-obatan', 'pengeluaran'),
(57, 2, 'Pendidikan', 'pengeluaran'),
(59, 2, 'Gaji', 'pemasukan'),
(61, 2, 'Hadiah', 'pemasukan'),
(63, 3, 'Makanan & Minuman', 'pengeluaran'),
(65, 3, 'Transportasi', 'pengeluaran'),
(67, 3, 'Hiburan', 'pengeluaran'),
(69, 3, 'Kesehatan & Obat-obatan', 'pengeluaran'),
(71, 3, 'Pendidikan', 'pengeluaran'),
(73, 3, 'Gaji', 'pemasukan'),
(75, 3, 'Hadiah', 'pemasukan');

-- --------------------------------------------------------

--
-- Table structure for table `pemasukan`
--

CREATE TABLE `pemasukan` (
  `id` int NOT NULL,
  `user_id` int NOT NULL,
  `kategori_id` int NOT NULL,
  `tanggal` date NOT NULL,
  `deskripsi` text,
  `jumlah` decimal(15,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `pemasukan`
--

INSERT INTO `pemasukan` (`id`, `user_id`, `kategori_id`, `tanggal`, `deskripsi`, `jumlah`) VALUES
(3, 0, 9, '2025-06-09', 'ALHAMDULILLAH DAPET THR', '250000.00'),
(7, 0, 7, '2025-06-10', 'gaji bulan juni', '100000.00'),
(9, 2, 9, '2025-06-12', 'menang undian', '100000000.00'),
(11, 2, 7, '2025-06-21', 'Gaji bulan Juni', '3000000.00');

-- --------------------------------------------------------

--
-- Table structure for table `pengeluaran`
--

CREATE TABLE `pengeluaran` (
  `id` int NOT NULL,
  `user_id` int NOT NULL,
  `kategori_id` int NOT NULL,
  `tanggal` date NOT NULL,
  `deskripsi` text,
  `jumlah` decimal(15,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `pengeluaran`
--

INSERT INTO `pengeluaran` (`id`, `user_id`, `kategori_id`, `tanggal`, `deskripsi`, `jumlah`) VALUES
(1, 0, 3, '2025-06-10', 'hiburan asik', '100000.00'),
(2, 0, 1, '2025-06-10', 'Nasi Goreng Katsu', '25000.00'),
(3, 0, 5, '2025-06-09', 'Obat Gerd', '100000.00'),
(5, 0, 6, '2025-06-12', 'ukt', '7000000.00'),
(7, 2, 3, '2025-06-21', 'nonton konser enhypen bkk', '2500000.00');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int NOT NULL,
  `username` varchar(50) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `email`, `password`, `created_at`) VALUES
(1, 'egista', 'egista_12@gmail.com', '$2y$10$0hOqaV/tbq0f1skiPu4pIeC8CukLkl36tL.p0LqM/C8KyPGPwWK.a', '2025-06-10 09:24:29'),
(2, 'citra', 'citra@gmail.com', '$2y$10$/h41bxyGsWycWPjI1Gl/Dup2JzmilwvlPwWwCV.Wu0RVljMj01frS', '2025-06-10 17:00:00'),
(3, 'cit', 'citraa@gmail.com', '$2y$10$hgZipP3QY2KwyOqNlzxbleVP664NjscpoStswXUBXWfjJ5mMgC036', '2025-06-21 17:15:14'),
(5, 'fardiani', 'fardiani@gmail.com', '$2y$10$2GKcCWBlxM0MlKDt/u6c3udFlv.HTjIpwAqz8x5XhCQEgoM1XIT0e', '2025-06-22 03:12:26'),
(7, 'cicit', 'cicit@gmail.com', '$2y$10$7PyNVUUCZcgKKNA0n5Eu3eIOvgKNng2l3oiaRBn7AkSZD1Efnqxdi', '2025-06-22 03:16:27');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `kategori`
--
ALTER TABLE `kategori`
  ADD PRIMARY KEY (`kategori_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `pemasukan`
--
ALTER TABLE `pemasukan`
  ADD PRIMARY KEY (`id`),
  ADD KEY `kategori_id` (`kategori_id`);

--
-- Indexes for table `pengeluaran`
--
ALTER TABLE `pengeluaran`
  ADD PRIMARY KEY (`id`),
  ADD KEY `kategori_id` (`kategori_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `kategori`
--
ALTER TABLE `kategori`
  MODIFY `kategori_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=76;

--
-- AUTO_INCREMENT for table `pemasukan`
--
ALTER TABLE `pemasukan`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `pengeluaran`
--
ALTER TABLE `pengeluaran`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `kategori`
--
ALTER TABLE `kategori`
  ADD CONSTRAINT `kategori_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Constraints for table `pemasukan`
--
ALTER TABLE `pemasukan`
  ADD CONSTRAINT `pemasukan_ibfk_1` FOREIGN KEY (`kategori_id`) REFERENCES `kategori` (`kategori_id`);

--
-- Constraints for table `pengeluaran`
--
ALTER TABLE `pengeluaran`
  ADD CONSTRAINT `pengeluaran_ibfk_1` FOREIGN KEY (`kategori_id`) REFERENCES `kategori` (`kategori_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
