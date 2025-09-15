-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Sep 15, 2025 at 03:47 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `umetnickadela`
--

-- --------------------------------------------------------

--
-- Table structure for table `galerija`
--

CREATE TABLE `galerija` (
  `id_galerije` int(11) NOT NULL,
  `naziv_galerije` varchar(100) NOT NULL DEFAULT '',
  `adresa` varchar(150) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `galerija`
--

INSERT INTO `galerija` (`id_galerije`, `naziv_galerije`, `adresa`) VALUES
(1, 'Louvre', 'Rue de Rivoli 75001, Paris, Francuska'),
(2, 'MOMA', '11 W 53rd St, New York, USA'),
(3, 'Tate Modern', 'Bankside, London, UK'),
(4, 'Galerija Umetnosti Beograd', 'Kralja Milana 36, Beograd, Srbija'),
(5, 'Prado', 'Calle de Ruiz de Alarcón 23, Madrid, Španija');

-- --------------------------------------------------------

--
-- Table structure for table `korisnik`
--

CREATE TABLE `korisnik` (
  `id_korisnika` int(11) NOT NULL,
  `korisnicko_ime` varchar(50) NOT NULL,
  `lozinka` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `korisnik`
--

INSERT INTO `korisnik` (`id_korisnika`, `korisnicko_ime`, `lozinka`) VALUES
(1, 'saraStevanovic', '1234'),
(2, 'markoMarkovic', '1234abvg'),
(3, 'sara', '1234567');

-- --------------------------------------------------------

--
-- Table structure for table `prodaja`
--

CREATE TABLE `prodaja` (
  `id_prodaje` int(11) NOT NULL,
  `umetnicko_delo_id` int(11) NOT NULL,
  `datum` date NOT NULL,
  `kupac` varchar(200) NOT NULL,
  `cena` decimal(10,2) NOT NULL,
  `galerija_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `prodaja`
--

INSERT INTO `prodaja` (`id_prodaje`, `umetnicko_delo_id`, `datum`, `kupac`, `cena`, `galerija_id`) VALUES
(1, 4, '2017-09-12', 'John Smith', 1200000.00, 2);

-- --------------------------------------------------------

--
-- Table structure for table `umetnicka_dela`
--

CREATE TABLE `umetnicka_dela` (
  `id_umetnickogDela` int(11) NOT NULL,
  `naziv_dela` varchar(100) NOT NULL,
  `godina` int(11) NOT NULL,
  `tip` varchar(50) NOT NULL,
  `cena` decimal(10,2) NOT NULL,
  `id_umetnik` int(11) NOT NULL,
  `galerija_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `umetnicka_dela`
--

INSERT INTO `umetnicka_dela` (`id_umetnickogDela`, `naziv_dela`, `godina`, `tip`, `cena`, `id_umetnik`, `galerija_id`) VALUES
(1, 'Guernica', 1937, 'slika', 200000.00, 1, 1),
(2, 'Plava perioda - Stara gitara', 1903, 'slika', 1200000.00, 1, 4),
(3, 'Upornost pamćenja', 1931, 'slika', 180000.00, 5, 3),
(4, 'San', 1937, 'slika', 1400000.00, 5, 2),
(5, 'Zvezdana noć', 1889, 'slika', 1500000.00, 2, 1),
(6, 'Suncokreti', 1888, 'slika', 1800000.00, 2, 5),
(7, 'Autoportret sa trnovom krunom', 1940, 'slika', 1500000.00, 3, 4),
(8, 'Dvostruki portret', 1939, 'slika', 14000000.00, 3, 3),
(9, 'Vodeni ljiljani', 1916, 'slika', 1500000.00, 4, 2),
(10, 'Impresija, izlazak sunca', 1872, 'slika', 1200000.00, 4, 5);

-- --------------------------------------------------------

--
-- Table structure for table `umetnik`
--

CREATE TABLE `umetnik` (
  `id_umetnika` int(11) NOT NULL,
  `ime` varchar(50) NOT NULL,
  `prezime` varchar(50) NOT NULL,
  `datum_rodjenja` date NOT NULL,
  `biografija` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `umetnik`
--

INSERT INTO `umetnik` (`id_umetnika`, `ime`, `prezime`, `datum_rodjenja`, `biografija`) VALUES
(1, 'Pablo', 'Picasso', '1881-10-25', 'Пабло Руиз Пикасо (шп. Pablo Ruiz Picasso,[2] Малага, 25. октобар 1881 — Мужин, 8. април 1973) био је свестрани шпански уметник. Пикасо је један од водећих и најпознатијих сликара, вајара, цртача и графичара 20. века, који је највећи део своје каријере провео живећи и радећи у Француској.[3] Био је доминантна личност у ликовним уметностима прве половине 20. века и покренуо је иницијативе за многе револуционарне промене у модерној уметности. Мада се његово дело обично дели на више периода, те поделе су донекле арбитрарне, пошто је његова стваралачка енергија и машта била таква да је често истовремено радио на богатом репертоару тема и у различитим стиловима'),
(2, 'Vincent', 'van Gogh', '1853-03-30', 'Винсент Вилем ван Гог био је сликар холандског порекла. Један је од тројице највећих сликара постимпресионизма и један од најцењенијих сликара уопште. Његова дела су запажена по својој грубој лепоти, емотивној искрености и храбрим бојама, те је захваљујући томе постао један од водећих уметника 19. века.'),
(3, 'Frida', 'Kahlo', '1907-07-06', 'Фрида Кало је била мексичка сликарка. Рођена је као Магдалена Кармен Фрида Кало и Калдерон у родитељској кући у Којоакану, који је у то време био мало предграђе Мексико Ситија. Њен отац је био сликар и фотограф немачко-јеврејског порекла, чија је породица потекла из Орадее у Румунији.'),
(4, 'Claude', 'Monet', '1840-11-14', 'Моне се родио 14. новембра 1840. Био је други син Клода Адолфа и Луис Жастин Моне.[3] 20. маја 1841, крштен је у локалној цркви, као Клод Оскар.[3] Године 1845. његова породица се преселила у Авр у Нормандији. Ту је као младић почео да ради карикатуре које је излагао по излозима дућана. Убрзо их је запазио локални сликар Ежен Буден, заинтересовао се за младог Монеа и почео да га води са собом кад год би ишао да слика пејзаже.'),
(5, 'Salvador', 'Dali', '1904-05-11', 'Салвадор Фелипе Хасинто Дали, 1. маркиз од Пубола био је каталонски и шпански надреалистички уметник, један од најзначајнијих уметника 20. века. Бавио се сликањем, писањем, вајањем, сценографијом и глумом. Он је један од најзначајнијих уметника 20. века, а често га називају и великим мајстором надреализма.');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `galerija`
--
ALTER TABLE `galerija`
  ADD PRIMARY KEY (`id_galerije`);

--
-- Indexes for table `korisnik`
--
ALTER TABLE `korisnik`
  ADD PRIMARY KEY (`id_korisnika`),
  ADD UNIQUE KEY `korisnicko_ime` (`korisnicko_ime`);

--
-- Indexes for table `prodaja`
--
ALTER TABLE `prodaja`
  ADD PRIMARY KEY (`id_prodaje`),
  ADD KEY `umetnicko_delo_id` (`umetnicko_delo_id`),
  ADD KEY `galerija_id` (`galerija_id`);

--
-- Indexes for table `umetnicka_dela`
--
ALTER TABLE `umetnicka_dela`
  ADD PRIMARY KEY (`id_umetnickogDela`),
  ADD KEY `id_umetnik` (`id_umetnik`),
  ADD KEY `galerija_id` (`galerija_id`);

--
-- Indexes for table `umetnik`
--
ALTER TABLE `umetnik`
  ADD PRIMARY KEY (`id_umetnika`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `galerija`
--
ALTER TABLE `galerija`
  MODIFY `id_galerije` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `korisnik`
--
ALTER TABLE `korisnik`
  MODIFY `id_korisnika` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `prodaja`
--
ALTER TABLE `prodaja`
  MODIFY `id_prodaje` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `umetnicka_dela`
--
ALTER TABLE `umetnicka_dela`
  MODIFY `id_umetnickogDela` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `umetnik`
--
ALTER TABLE `umetnik`
  MODIFY `id_umetnika` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `prodaja`
--
ALTER TABLE `prodaja`
  ADD CONSTRAINT `prodaja_ibfk_1` FOREIGN KEY (`umetnicko_delo_id`) REFERENCES `umetnicka_dela` (`id_umetnickogDela`),
  ADD CONSTRAINT `prodaja_ibfk_2` FOREIGN KEY (`galerija_id`) REFERENCES `galerija` (`id_galerije`);

--
-- Constraints for table `umetnicka_dela`
--
ALTER TABLE `umetnicka_dela`
  ADD CONSTRAINT `umetnicka_dela_ibfk_1` FOREIGN KEY (`id_umetnik`) REFERENCES `umetnik` (`id_umetnika`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `umetnicka_dela_ibfk_2` FOREIGN KEY (`galerija_id`) REFERENCES `galerija` (`id_galerije`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
