-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Waktu pembuatan: 14 Nov 2024 pada 03.03
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
-- Database: `razekpekajangan`
--

-- --------------------------------------------------------

--
-- Struktur dari tabel `admins`
--

CREATE TABLE `admins` (
  `id` int(11) NOT NULL,
  `nama` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `admins`
--

INSERT INTO `admins` (`id`, `nama`, `password`) VALUES
(1, 'admin1', '$2y$10$.WeIuADFwiZ42/OtsSqlG.3N.aBS5mEB5J/N8AP30FKd1wrfozofW'),
(2, 'admin2', '$2y$10$Jlcabvas.Ey6EwnOSh7efeEKnXU2ZWXBuckGWaQQviBrlK0mJb73C');

-- --------------------------------------------------------

--
-- Struktur dari tabel `jamaah`
--

CREATE TABLE `jamaah` (
  `id` int(11) NOT NULL,
  `nama_jamaah` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `jamaah`
--

INSERT INTO `jamaah` (`id`, `nama_jamaah`) VALUES
(1, 'Jamaah Tes 1'),
(2, 'Jamaah Tes 2'),
(3, 'Jamaah Contoh');

-- --------------------------------------------------------

--
-- Struktur dari tabel `program`
--

CREATE TABLE `program` (
  `id` int(11) NOT NULL,
  `nama_program` varchar(255) NOT NULL,
  `tanggal_program` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `program`
--

INSERT INTO `program` (`id`, `nama_program`, `tanggal_program`) VALUES
(1, 'Tes Program 1', '2024-11-05'),
(2, 'Tes Program 2', '2024-11-14'),
(3, 'Program Contoh', '2024-12-01');

-- --------------------------------------------------------

--
-- Struktur dari tabel `transaksi`
--

CREATE TABLE `transaksi` (
  `id` int(11) NOT NULL,
  `id_jamaah` int(11) NOT NULL,
  `id_program` int(11) NOT NULL,
  `harga` decimal(10,2) NOT NULL,
  `dp1` decimal(10,2) NOT NULL,
  `dp2` decimal(10,2) NOT NULL,
  `dp3` decimal(10,2) NOT NULL,
  `kekurangan` decimal(10,2) GENERATED ALWAYS AS (`harga` - `dp1` - `dp2` - `dp3`) STORED,
  `dp1_time_edit` timestamp NULL DEFAULT NULL,
  `dp2_time_edit` timestamp NULL DEFAULT NULL,
  `dp3_time_edit` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `transaksi`
--

INSERT INTO `transaksi` (`id`, `id_jamaah`, `id_program`, `harga`, `dp1`, `dp2`, `dp3`, `dp1_time_edit`, `dp2_time_edit`, `dp3_time_edit`) VALUES
(1, 1, 1, 500000.00, 200000.00, 100000.00, 50000.00, '2024-11-06 01:15:07', NULL, NULL),
(2, 2, 2, 1000000.00, 300000.00, 200000.00, 100000.00, NULL, '2024-11-06 01:40:59', NULL),
(3, 3, 3, 750000.00, 250000.00, 0.00, 200000.00, '2024-11-06 01:15:07', '2024-11-06 01:40:59', '2024-11-06 01:15:28');

--
-- Indexes for dumped tables
--

--
-- Indeks untuk tabel `admins`
--
ALTER TABLE `admins`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `jamaah`
--
ALTER TABLE `jamaah`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `program`
--
ALTER TABLE `program`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `transaksi`
--
ALTER TABLE `transaksi`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_jamaah` (`id_jamaah`),
  ADD KEY `id_program` (`id_program`);

--
-- AUTO_INCREMENT untuk tabel yang dibuang
--

--
-- AUTO_INCREMENT untuk tabel `admins`
--
ALTER TABLE `admins`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT untuk tabel `jamaah`
--
ALTER TABLE `jamaah`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT untuk tabel `program`
--
ALTER TABLE `program`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT untuk tabel `transaksi`
--
ALTER TABLE `transaksi`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- Ketidakleluasaan untuk tabel pelimpahan (Dumped Tables)
--

--
-- Ketidakleluasaan untuk tabel `transaksi`
--
ALTER TABLE `transaksi`
  ADD CONSTRAINT `transaksi_ibfk_1` FOREIGN KEY (`id_jamaah`) REFERENCES `jamaah` (`id`),
  ADD CONSTRAINT `transaksi_ibfk_2` FOREIGN KEY (`id_program`) REFERENCES `program` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
