-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Sep 24, 2025 at 11:50 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `hotel_rezervacije`
--

-- --------------------------------------------------------

--
-- Table structure for table `galerija`
--

CREATE TABLE `galerija` (
  `id` int(11) NOT NULL,
  `naziv` varchar(255) NOT NULL,
  `slika` varchar(255) NOT NULL,
  `datum` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `galerija`
--

INSERT INTO `galerija` (`id`, `naziv`, `slika`, `datum`) VALUES
(2, 'Hilton', '1758654672_2023-06-12.jpg', '2025-09-23 19:11:12'),
(3, 'Bazen', '1758668438_3.jpg', '2025-09-23 23:00:38'),
(4, 'Enterijer', '1758668466_2.jpg', '2025-09-23 23:01:06'),
(5, 'Bazen noć', '1758668486_1.jpg', '2025-09-23 23:01:26'),
(6, 'Moskva', '1758705463_moskva.jpg', '2025-09-24 09:17:43');

-- --------------------------------------------------------

--
-- Table structure for table `hoteli`
--

CREATE TABLE `hoteli` (
  `hotel_id` int(11) NOT NULL,
  `naziv` varchar(100) NOT NULL,
  `opis` text NOT NULL,
  `slika` varchar(255) NOT NULL,
  `lokacija` varchar(100) NOT NULL,
  `zvezdice` int(11) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `hoteli`
--

INSERT INTO `hoteli` (`hotel_id`, `naziv`, `opis`, `slika`, `lokacija`, `zvezdice`) VALUES
(11, 'Hilton', 'Kralja Milana 35, Beograd 11000', '1758651809_2023_06_12.jpg', 'Kralja Milana 35, Beograd 11000', 5),
(12, 'Hotel Moskva', 'Hotel Moskva smešten je u zgradi izgrađenoj u ampir stilu, koja predstavlja gradsku znamenitost.', '1758702200_moskva.jpg', 'Balkanska 1, Beograd 11000', 4);

-- --------------------------------------------------------

--
-- Table structure for table `kontakt`
--

CREATE TABLE `kontakt` (
  `id` int(11) NOT NULL,
  `email` varchar(150) NOT NULL,
  `naslov` varchar(100) NOT NULL,
  `poruka` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `kontakt`
--

INSERT INTO `kontakt` (`id`, `email`, `naslov`, `poruka`) VALUES
(1, 'provera@gmail.com', 'provera', 'provera'),
(2, 'help@domain.com', 'Pomoc', 'pomoc');

-- --------------------------------------------------------

--
-- Table structure for table `korisnik`
--

CREATE TABLE `korisnik` (
  `korisnik_id` int(11) NOT NULL,
  `ime` varchar(50) NOT NULL,
  `prezime` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `lozinka` varchar(255) NOT NULL,
  `telefon` varchar(20) NOT NULL,
  `uloga` enum('korisnik','admin') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `korisnik`
--

INSERT INTO `korisnik` (`korisnik_id`, `ime`, `prezime`, `email`, `lozinka`, `telefon`, `uloga`) VALUES
(3, 'Admin', 'Admin', 'admin@domen.com', '$2y$10$cJV7eL2C7ipsOSLk/p7XXuAhiWxwjWgJPFQx8VqC.B7gxVPmljMCG', '000000000', 'admin'),
(4, 'Petar', 'Peric', 'petar@gmail.com', '$2y$10$qSv1If4YrSNsl8356x3PB.X2.Uy3rP6i6t2Fcc.XmjGle1wF1YKfu', '064222444', 'korisnik');

-- --------------------------------------------------------

--
-- Table structure for table `rezervacija`
--

CREATE TABLE `rezervacija` (
  `rezervacija_id` int(11) NOT NULL,
  `korisnik_id` int(11) NOT NULL,
  `soba_id` int(11) NOT NULL,
  `hotel_id` int(11) NOT NULL,
  `datum_od` date NOT NULL,
  `datum_do` date NOT NULL,
  `broj_gostiju` int(11) NOT NULL,
  `status` enum('na_cekanju','odobrena','odbijena') DEFAULT 'na_cekanju',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `rezervacija`
--

INSERT INTO `rezervacija` (`rezervacija_id`, `korisnik_id`, `soba_id`, `hotel_id`, `datum_od`, `datum_do`, `broj_gostiju`, `status`, `created_at`) VALUES
(3, 4, 6, 12, '2025-09-24', '2025-09-27', 1, 'odobrena', '2025-09-24 09:48:15');

-- --------------------------------------------------------

--
-- Table structure for table `soba`
--

CREATE TABLE `soba` (
  `soba_id` int(11) NOT NULL,
  `broj_sobe` varchar(10) NOT NULL,
  `tip_sobe` varchar(50) NOT NULL,
  `cena` decimal(10,2) NOT NULL,
  `hotel_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `soba`
--

INSERT INTO `soba` (`soba_id`, `broj_sobe`, `tip_sobe`, `cena`, `hotel_id`) VALUES
(5, '9', 'jednokrevetna', 5000.00, 11),
(6, '5', 'dvokrevetna', 6000.00, 12);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `galerija`
--
ALTER TABLE `galerija`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `hoteli`
--
ALTER TABLE `hoteli`
  ADD PRIMARY KEY (`hotel_id`);

--
-- Indexes for table `kontakt`
--
ALTER TABLE `kontakt`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `korisnik`
--
ALTER TABLE `korisnik`
  ADD PRIMARY KEY (`korisnik_id`);

--
-- Indexes for table `rezervacija`
--
ALTER TABLE `rezervacija`
  ADD PRIMARY KEY (`rezervacija_id`),
  ADD UNIQUE KEY `hotel_id` (`hotel_id`),
  ADD KEY `korisnik_id` (`korisnik_id`),
  ADD KEY `soba_id` (`soba_id`);

--
-- Indexes for table `soba`
--
ALTER TABLE `soba`
  ADD PRIMARY KEY (`soba_id`),
  ADD KEY `fk_hotel` (`hotel_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `galerija`
--
ALTER TABLE `galerija`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `hoteli`
--
ALTER TABLE `hoteli`
  MODIFY `hotel_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `kontakt`
--
ALTER TABLE `kontakt`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `korisnik`
--
ALTER TABLE `korisnik`
  MODIFY `korisnik_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `rezervacija`
--
ALTER TABLE `rezervacija`
  MODIFY `rezervacija_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `soba`
--
ALTER TABLE `soba`
  MODIFY `soba_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `rezervacija`
--
ALTER TABLE `rezervacija`
  ADD CONSTRAINT `rezervacija_ibfk_1` FOREIGN KEY (`korisnik_id`) REFERENCES `korisnik` (`korisnik_id`),
  ADD CONSTRAINT `rezervacija_ibfk_2` FOREIGN KEY (`soba_id`) REFERENCES `soba` (`soba_id`);

--
-- Constraints for table `soba`
--
ALTER TABLE `soba`
  ADD CONSTRAINT `fk_hotel` FOREIGN KEY (`hotel_id`) REFERENCES `hoteli` (`hotel_id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
