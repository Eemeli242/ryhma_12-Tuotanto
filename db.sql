-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Oct 08, 2025 at 11:04 AM
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
-- Database: `lomamokit`
--

-- --------------------------------------------------------

--
-- Table structure for table `bookings`
--

CREATE TABLE `bookings` (
  `id` int(11) NOT NULL,
  `customer_id` int(11) DEFAULT NULL,
  `cabin_id` int(11) NOT NULL,
  `customer_name` varchar(150) NOT NULL,
  `customer_email` varchar(150) NOT NULL,
  `start_date` date NOT NULL,
  `end_date` date NOT NULL,
  `guests` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `paid` decimal(10,2) DEFAULT 0.00,
  `status` enum('pending','paid','cancelled') DEFAULT 'pending',
  `user_id` int(11) DEFAULT NULL,
  `admin_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `bookings`
--

INSERT INTO `bookings` (`id`, `customer_id`, `cabin_id`, `customer_name`, `customer_email`, `start_date`, `end_date`, `guests`, `created_at`, `paid`, `status`, `user_id`, `admin_id`) VALUES
(9, NULL, 1, 'Eemeli Määttä', 'maatta.eemeli@sähköposti.fi', '2025-09-15', '2025-09-27', 1, '2025-09-15 15:24:30', 1440.00, 'paid', NULL, NULL),
(10, NULL, 3, 'Eemeli Määttä', 'dawdwad@gmail.com', '2025-09-15', '2025-09-27', 2, '2025-09-15 15:29:34', 1800.00, 'paid', NULL, NULL),
(11, NULL, 6, 'Eemeli Määttä', 'maatta.eemeli@sähköposti.fi', '2025-09-15', '2025-09-20', 1, '2025-09-15 15:29:52', 500.00, 'paid', NULL, NULL),
(13, NULL, 4, 'Eemeli Määttä', 'maatta.eemeli@sähköposti.fi', '2025-09-15', '2025-09-16', 1, '2025-09-15 15:34:27', 200.00, 'paid', NULL, NULL),
(14, NULL, 4, 'Eemeli Määttä', 'dawdwad@gmail.com', '2025-09-21', '2025-09-22', 1, '2025-09-15 15:34:45', 200.00, 'paid', NULL, NULL),
(17, NULL, 1, 'awdwad wad', 'eemeli@gmail.com1', '2026-02-12', '2026-07-23', 1, '2025-09-26 12:22:23', 19320.00, 'paid', 1, NULL),
(18, NULL, 4, 'Eemeli Määttä', 'eemeli@gmail.com1', '2026-02-04', '2026-06-04', 1, '2025-09-26 12:22:49', 24000.00, 'paid', 1, NULL),
(19, 1, 3, 'Eemeli Määttä', 'eemeli@gmail.com1', '2026-02-07', '2026-07-11', 1, '2025-09-26 12:24:38', 23100.00, 'paid', NULL, NULL),
(20, 1, 15, 'Eemeli Määttä', 'eemeli@gmail.com1', '2026-02-01', '2026-07-01', 1, '2025-09-26 12:25:31', 16650.00, 'paid', NULL, NULL),
(21, 1, 6, 'Eemeli Määttä', 'dawdwad@gmail.com', '2025-09-24', '2025-09-25', 1, '2025-09-26 13:54:00', 100.00, 'paid', NULL, NULL),
(22, 1, 22, 'Eemeli Määttä', 'eemeli@gmail.com1', '2025-10-06', '2025-10-11', 1, '2025-10-06 15:46:29', 55.00, 'paid', NULL, NULL),
(23, 1, 22, 'Eemeli Määttä', 'dawdwad@gmail.com', '2025-10-19', '2025-10-31', 2, '2025-10-06 15:46:54', 132.00, 'paid', NULL, NULL),
(24, 1, 22, 'awdwad wad', 'dawdwad@gmail.com', '2026-01-01', '2026-03-06', 1, '2025-10-06 15:47:23', 704.00, 'cancelled', NULL, NULL),
(25, 1, 1, 'wae', 'maatta.eemeli@sähköposti.fi', '2025-10-06', '2025-10-29', 1, '2025-10-06 15:59:26', 2760.00, 'pending', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `cabins`
--

CREATE TABLE `cabins` (
  `id` int(11) NOT NULL,
  `owner_id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `description` text DEFAULT NULL,
  `price_per_night` decimal(8,2) NOT NULL,
  `max_guests` int(11) NOT NULL DEFAULT 4,
  `image` varchar(255) DEFAULT NULL,
  `location` varchar(100) DEFAULT '',
  `user_id` int(11) DEFAULT NULL,
  `availability` tinyint(1) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `cabins`
--

INSERT INTO `cabins` (`id`, `owner_id`, `name`, `description`, `price_per_night`, `max_guests`, `image`, `location`, `user_id`, `availability`) VALUES
(1, 0, 'Mäntymetsän Kallio', 'Mäntymetsän Kallio – Korkealla kallion laella seisova mökki, jota ympäröivät vanhat, tuoksuvat männyt.', 120.00, 4, 'images/cabin1.jpg', 'Helsinki', NULL, 1),
(2, 0, 'Kuunsilta', 'Kuunsilta – Rauhallinen rantamökki, jossa kuu heijastuu iltaisin veteen luoden hopeisen “sillan” laiturilta horisonttiin.', 180.00, 6, 'images/cabin2.jpg', 'Turku', NULL, 1),
(3, 0, 'Tuulensuo', 'Tuulensuo – Metsän keskellä sijaitseva suojaisa paikka, jossa tuulen humina kuuluu kaukaa mutta piha pysyy tyynenä.', 150.00, 5, 'images/cabin3.jpg', 'Lahti', NULL, 1),
(4, 0, 'Siniranta', 'Siniranta – Kirkasvetisen järven rannalla, jossa vesi heijastaa taivaan syvänsinisen sävyn kesäpäivinä.adaadadddd', 200.00, 6, 'images/cabin4.jpg', 'Jyväskylä', NULL, 1),
(5, 0, 'Karhunpesä', 'Karhunpesä – Vankka ja lämmin hirsimökki, joka huokuu erämaan voimaa ja kutsuu talvi-iltoina takkatulen äärelle.', 130.00, 2, 'images/cabin5.jpg', 'Turku', NULL, 1),
(6, 0, 'Lumilinna', 'Lumilinna – Talven taikaa rakastavalle: mökki, joka peittyy kauniiseen lumipeitteeseen ja loistaa kynttilälyhtyjen valossa.', 100.00, 1, 'images/cabin6.jpg', 'Jyväskylä', 1, 1),
(14, 1, 'Kesäheinä', 'Kesäheinä – Avoimella niityllä sijaitseva mökki, jonka ympärillä tuoksuvat luonnonkukat ja heinäladot.', 11.00, 11, 'images/cabin7.jpg', 'Hämeenlinna', NULL, 1),
(15, 1, 'Kallioranta', 'Kallioranta – Jyrkän kalliorannan päällä lepäävä paikka, josta voi hypätä suoraan kirkkaaseen veteen. Lämmin järvivesi.', 111.00, 1, 'images/cabin8.jpg', 'Hämeenlinna', NULL, 1),
(16, 1, 'Varjokallio', 'Varjokallio – Jylhä kivikko ja varjoisat puut luovat viileän ja salaperäisen tunnelman kuumimpinakin kesäpäivinä.', 1111.00, 12, 'images/cabin9.jpg', 'Hämeenlinna', NULL, 1),
(22, 1, 'Kalakukko', 'Villa Kalakukko on tunnelmallinen ja modernisti varusteltu hirsimökki, joka sijaitsee rauhallisella paikalla kirkasvetisen järven rannalla. ', 11.00, 11, 'uploads/1759765485_0529d9df-huvila_peippi_37_1.jpg', 'Kuopio', NULL, 1);

-- --------------------------------------------------------

--
-- Table structure for table `reviews`
--

CREATE TABLE `reviews` (
  `id` int(11) NOT NULL,
  `booking_id` int(11) NOT NULL,
  `cabin_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `rating` tinyint(4) NOT NULL CHECK (`rating` between 1 and 5),
  `comment` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `approved` tinyint(1) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `reviews`
--

INSERT INTO `reviews` (`id`, `booking_id`, `cabin_id`, `user_id`, `rating`, `comment`, `created_at`, `approved`) VALUES
(16, 25, 1, 1, 5, 'ON HIENO MÖKKI', '2025-10-06 16:00:18', 1),
(17, 25, 1, 1, 3, 'Hieman likaista', '2025-10-06 16:00:26', 1),
(18, 25, 1, 1, 5, 'Todella mahtava viikonloppu reissu.', '2025-10-06 16:00:40', 1),
(19, 23, 22, 1, 2, 'Oli hieman tylsä sisustus', '2025-10-06 16:01:13', 1),
(20, 23, 22, 1, 4, 'Hyvä mökki 4/5', '2025-10-06 16:01:22', 1),
(21, 19, 3, 1, 5, 'Paras mökki mitä rahalla saa', '2025-10-06 16:01:36', 1),
(22, 19, 3, 1, 1, 'Heikko tarjoilu. Autotie oli todella huonossa kunnossa.', '2025-10-06 16:01:51', 1),
(23, 19, 3, 1, 4, 'Kalastus mahdollisuudet olivat erittäin hyvät. Mutta itse mökki likainen.', '2025-10-06 16:02:03', 1);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(100) NOT NULL,
  `email` varchar(255) NOT NULL,
  `phone` varchar(30) NOT NULL,
  `profile_image` varchar(255) DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `balance` decimal(10,2) DEFAULT 0.00,
  `role` enum('admin','user') NOT NULL DEFAULT 'user'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `email`, `phone`, `profile_image`, `password`, `created_at`, `balance`, `role`) VALUES
(1, 'eemeli', 'eemeli@gmail.com11', '040000002', 'uploads/1758465740_1200x630cw-removebg-preview.png', '$2y$10$F0B8XZPqtzZYT6S0.F.B3./MGP3TcsuoM3oG7XAMEjQrJDX06WoaG', '2025-09-15 10:43:23', 255257.00, 'admin'),
(9, 'Eemeli2', 'awedawf@gmail.com', '949492', NULL, '$2y$10$BBPflEYFmzxkJHYnrWuSG.QSGCiNiC./M2KqU928KcuBCom9eWSqi', '2025-09-26 11:07:07', 999999.00, 'user');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `bookings`
--
ALTER TABLE `bookings`
  ADD PRIMARY KEY (`id`),
  ADD KEY `cabin_id` (`cabin_id`),
  ADD KEY `fk_customer` (`customer_id`),
  ADD KEY `fk_user` (`user_id`),
  ADD KEY `fk_admin` (`admin_id`);

--
-- Indexes for table `cabins`
--
ALTER TABLE `cabins`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `reviews`
--
ALTER TABLE `reviews`
  ADD PRIMARY KEY (`id`),
  ADD KEY `booking_id` (`booking_id`),
  ADD KEY `cabin_id` (`cabin_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `bookings`
--
ALTER TABLE `bookings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- AUTO_INCREMENT for table `cabins`
--
ALTER TABLE `cabins`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=30;

--
-- AUTO_INCREMENT for table `reviews`
--
ALTER TABLE `reviews`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `bookings`
--
ALTER TABLE `bookings`
  ADD CONSTRAINT `bookings_ibfk_1` FOREIGN KEY (`cabin_id`) REFERENCES `cabins` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_admin` FOREIGN KEY (`admin_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `fk_customer` FOREIGN KEY (`customer_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `fk_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `cabins`
--
ALTER TABLE `cabins`
  ADD CONSTRAINT `cabins_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `reviews`
--
ALTER TABLE `reviews`
  ADD CONSTRAINT `reviews_ibfk_1` FOREIGN KEY (`booking_id`) REFERENCES `bookings` (`id`),
  ADD CONSTRAINT `reviews_ibfk_2` FOREIGN KEY (`cabin_id`) REFERENCES `cabins` (`id`),
  ADD CONSTRAINT `reviews_ibfk_3` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
