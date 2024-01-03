-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Waktu pembuatan: 03 Jan 2024 pada 10.41
-- Versi server: 10.4.27-MariaDB
-- Versi PHP: 8.2.0

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `kas_rt`
--

-- --------------------------------------------------------

--
-- Struktur dari tabel `iuran`
--

CREATE TABLE `iuran` (
  `id` int(11) NOT NULL,
  `tanggal` date DEFAULT NULL,
  `warga_id` int(11) NOT NULL,
  `nominal` decimal(10,2) DEFAULT NULL,
  `keterangan` tinytext DEFAULT NULL,
  `jenis_iuran` tinyint(1) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data untuk tabel `iuran`
--

INSERT INTO `iuran` (`id`, `tanggal`, `warga_id`, `nominal`, `keterangan`, `jenis_iuran`) VALUES
(1, '2024-01-01', 2, '50000.00', 'Lunas', 1),
(2, '2024-01-02', 4, '25000.00', 'Belum Lunas', 2);

-- --------------------------------------------------------

--
-- Struktur dari tabel `pengeluaran`
--

CREATE TABLE `pengeluaran` (
  `id` int(11) NOT NULL,
  `tanggal` date DEFAULT NULL,
  `nominal` decimal(10,2) DEFAULT NULL,
  `keterangan` tinytext DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(100) DEFAULT NULL,
  `password` varchar(100) DEFAULT NULL,
  `nama` varchar(200) DEFAULT NULL,
  `email` varchar(200) DEFAULT NULL,
  `status` tinyint(1) DEFAULT NULL,
  `role` tinyint(1) DEFAULT 2 COMMENT '1:Admin\n2:User'
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data untuk tabel `users`
--

INSERT INTO `users` (`id`, `username`, `password`, `nama`, `email`, `status`, `role`) VALUES
(1, 'maulkuadrat', '$2y$10$8MoB/PcUri12DMRlr6pxkO2zXTlpcjR.8C9UXo68qCEzEA.tccZVu', 'maulkuadrat', 'maulkuadrat@gmail.com', 1, 2),
(8, 'riyad', '$2y$10$WoxPNsIqo0cq1F/rXEcVwOdvdA4aT3thw7zweqbiW9I1vYw3wpBiy', 'riyad', 'riyad@gmail.com', 1, 2),
(9, 'rizqi', '$2y$10$BbimCfdgkd.ERKuXHkIi7eCMVtkuv6K3s9ssMfz0uF4ivKTfw3LmK', 'Muhammad Rizqi Maulana', 'mrizqim01@gmail.com', 1, 1),
(10, 'rama', '$2y$10$Wk3.K26kR.ysRkbapcuLMOwqgoWQoyTCTJEi6MHDpvWtxfWE9AYb.', 'Sandy Ramadhan', 'rama@gmail.com', 1, 2);

-- --------------------------------------------------------

--
-- Struktur dari tabel `warga`
--

CREATE TABLE `warga` (
  `id` int(11) NOT NULL,
  `nik` varchar(50) DEFAULT NULL,
  `nama` varchar(200) DEFAULT NULL,
  `jenis_kelamin` enum('L','P') DEFAULT NULL,
  `no_hp` varchar(20) DEFAULT NULL,
  `alamat` tinytext DEFAULT NULL,
  `no_rumah` varchar(10) DEFAULT NULL,
  `status` tinyint(1) DEFAULT NULL,
  `users_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data untuk tabel `warga`
--

INSERT INTO `warga` (`id`, `nik`, `nama`, `jenis_kelamin`, `no_hp`, `alamat`, `no_rumah`, `status`, `users_id`) VALUES
(2, '3216070410010013', 'Muhammad Rizqi Maulana', 'L', '089643485685', 'Taman Wanasari Indah', '8', 0, 1),
(3, '3214589235620097', 'Anryan Catur Wiryana', 'L', '085642197560', 'Taman Wanasari Indah', '12', 0, 1),
(4, '3219529531600021', 'Reza Yudha Ardhana', 'L', '082143446712', 'Taman Wanasari Indah', '6', 0, 1),
(5, '3211153005360010', 'Muhammad Riyadus Solihin', 'L', '085778913375', 'Taman Wanasari Indah', '1', 0, 1),
(6, '3210069111808033', 'Sandy Ramadhan', 'L', '081213929179', 'Taman Wanasari Indah', '5', 0, 9);

--
-- Indexes for dumped tables
--

--
-- Indeks untuk tabel `iuran`
--
ALTER TABLE `iuran`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_iuran_warga1_idx` (`warga_id`);

--
-- Indeks untuk tabel `pengeluaran`
--
ALTER TABLE `pengeluaran`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username_UNIQUE` (`username`),
  ADD UNIQUE KEY `email_UNIQUE` (`email`);

--
-- Indeks untuk tabel `warga`
--
ALTER TABLE `warga`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `nik_UNIQUE` (`nik`),
  ADD KEY `fk_warga_users1_idx` (`users_id`);

--
-- AUTO_INCREMENT untuk tabel yang dibuang
--

--
-- AUTO_INCREMENT untuk tabel `iuran`
--
ALTER TABLE `iuran`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT untuk tabel `pengeluaran`
--
ALTER TABLE `pengeluaran`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT untuk tabel `warga`
--
ALTER TABLE `warga`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- Ketidakleluasaan untuk tabel pelimpahan (Dumped Tables)
--

--
-- Ketidakleluasaan untuk tabel `iuran`
--
ALTER TABLE `iuran`
  ADD CONSTRAINT `fk_iuran_warga1` FOREIGN KEY (`warga_id`) REFERENCES `warga` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Ketidakleluasaan untuk tabel `warga`
--
ALTER TABLE `warga`
  ADD CONSTRAINT `fk_warga_users1` FOREIGN KEY (`users_id`) REFERENCES `users` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
