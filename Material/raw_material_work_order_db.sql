-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Waktu pembuatan: 27 Okt 2024 pada 17.14
-- Versi server: 10.4.32-MariaDB
-- Versi PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `raw_material_work_order_db`
--

-- --------------------------------------------------------

--
-- Struktur dari tabel `material`
--

CREATE TABLE `material` (
  `id` int(11) NOT NULL,
  `material_name` varchar(100) NOT NULL,
  `days` int(11) NOT NULL,
  `percentage` float NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `material`
--

INSERT INTO `material` (`id`, `material_name`, `days`, `percentage`) VALUES
(1, 'Cable', 2, 0),
(2, 'Bolt', 1, 0),
(3, 'Cap', 3, 0);

-- --------------------------------------------------------

--
-- Struktur dari tabel `work_order`
--

CREATE TABLE `work_order` (
  `id` int(11) NOT NULL,
  `work_order_no` varchar(50) NOT NULL,
  `client` varchar(100) NOT NULL,
  `work_date` date NOT NULL,
  `finish_date` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `work_order`
--

INSERT INTO `work_order` (`id`, `work_order_no`, `client`, `work_date`, `finish_date`) VALUES
(7, 'WO001', 'PT XYZ', '2024-10-27', '2024-10-30');

-- --------------------------------------------------------

--
-- Struktur dari tabel `work_order_material`
--

CREATE TABLE `work_order_material` (
  `id` int(11) NOT NULL,
  `work_order_id` int(11) NOT NULL,
  `material_id` int(11) NOT NULL,
  `days` int(11) NOT NULL,
  `percentage` float NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `work_order_material`
--

INSERT INTO `work_order_material` (`id`, `work_order_id`, `material_id`, `days`, `percentage`) VALUES
(6, 7, 1, 2, 50),
(7, 7, 2, 1, 50);

--
-- Indexes for dumped tables
--

--
-- Indeks untuk tabel `material`
--
ALTER TABLE `material`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `work_order`
--
ALTER TABLE `work_order`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `work_order_no` (`work_order_no`);

--
-- Indeks untuk tabel `work_order_material`
--
ALTER TABLE `work_order_material`
  ADD PRIMARY KEY (`id`),
  ADD KEY `work_order_id` (`work_order_id`),
  ADD KEY `material_id` (`material_id`);

--
-- AUTO_INCREMENT untuk tabel yang dibuang
--

--
-- AUTO_INCREMENT untuk tabel `material`
--
ALTER TABLE `material`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT untuk tabel `work_order`
--
ALTER TABLE `work_order`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT untuk tabel `work_order_material`
--
ALTER TABLE `work_order_material`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- Ketidakleluasaan untuk tabel pelimpahan (Dumped Tables)
--

--
-- Ketidakleluasaan untuk tabel `work_order_material`
--
ALTER TABLE `work_order_material`
  ADD CONSTRAINT `work_order_material_ibfk_1` FOREIGN KEY (`work_order_id`) REFERENCES `work_order` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `work_order_material_ibfk_2` FOREIGN KEY (`material_id`) REFERENCES `material` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
