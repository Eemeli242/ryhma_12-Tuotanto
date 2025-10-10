-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Oct 10, 2025 at 12:21 PM
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
(26, 14, 1, 'Samu', 'samumail@gmail.com', '2025-10-10', '2025-10-18', 1, '2025-10-10 10:15:51', 960.00, 'paid', NULL, NULL),
(27, 14, 15, 'Samu', 'samumail@gmail.com', '2025-10-10', '2025-10-21', 1, '2025-10-10 10:16:02', 1221.00, 'paid', NULL, NULL),
(28, 14, 22, 'Samu', 'samumail@gmail.com', '2025-10-12', '2025-10-23', 5, '2025-10-10 10:16:19', 121.00, 'paid', NULL, NULL),
(29, 16, 1, 'Topi', 'topimail@gmail.com', '2025-11-01', '2025-11-08', 1, '2025-10-10 10:17:57', 840.00, 'paid', NULL, NULL),
(30, 16, 3, 'Topi', 'topimail@gmail.com', '2025-11-13', '2025-11-28', 2, '2025-10-10 10:18:14', 2250.00, 'paid', NULL, NULL),
(31, 16, 6, 'Topi', 'topimail@gmail.com', '2025-10-10', '2025-10-31', 1, '2025-10-10 10:18:37', 2100.00, 'paid', NULL, NULL),
(32, 1, 3, 'Eemeli Määttä', 'eemelimail@gmail.com', '2025-10-10', '2025-10-31', 1, '2025-10-10 10:20:39', 3150.00, 'paid', NULL, NULL),
(33, 1, 5, 'Eemeli Määttä', 'eemelimail@gmail.com', '2025-10-10', '2025-10-31', 1, '2025-10-10 10:21:17', 2730.00, 'paid', NULL, NULL);

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
(1, 0, 'Mäntymetsän Kallio', 'Mäntymetsän Kallio – Korkealla kallion laella seisova mökki, jota ympäröivät vanhat, tuoksuvat männyt.', 120.00, 4, '/uploads/1760091206_0529d9df-huvila_peippi_37_1.jpg', 'Helsinki', NULL, 1),
(2, 0, 'Kuunsilta', 'Kuunsilta – Rauhallinen rantamökki, jossa kuu heijastuu iltaisin veteen luoden hopeisen “sillan” laiturilta horisonttiin.', 180.00, 6, 'images/cabin2.jpg', 'Turku', NULL, 1),
(3, 0, 'Tuulensuo', 'Tuulensuo – Metsän keskellä sijaitseva suojaisa paikka, jossa tuulen humina kuuluu kaukaa mutta piha pysyy tyynenä.', 150.00, 5, 'images/cabin3.jpg', 'Lahti', NULL, 1),
(4, 0, 'Siniranta', 'Siniranta – Kirkasvetisen järven rannalla, jossa vesi heijastaa taivaan syvänsinisen sävyn kesäpäivinä.adaadadddd', 200.00, 6, '/uploads/1760091197_hero-huvilakategoria1-min.jpg', 'Jyväskylä', NULL, 1),
(5, 0, 'Karhunpesä', 'Karhunpesä – Vankka ja lämmin hirsimökki, joka huokuu erämaan voimaa ja kutsuu talvi-iltoina takkatulen äärelle.', 130.00, 4, 'images/cabin5.jpg', 'Turku', NULL, 1),
(6, 0, 'Lumilinna', 'Lumilinna – Talven taikaa rakastavalle: mökki, joka peittyy kauniiseen lumipeitteeseen ja loistaa kynttilälyhtyjen valossa.', 100.00, 4, 'images/cabin6.jpg', 'Jyväskylä', 1, 1),
(14, 1, 'Kesäheinä', 'Kesäheinä – Avoimella niityllä sijaitseva mökki, jonka ympärillä tuoksuvat luonnonkukat ja heinäladot.', 50.00, 11, 'images/cabin7.jpg', 'Hämeenlinna', NULL, 1),
(15, 1, 'Kallioranta', 'Kallioranta – Jyrkän kalliorannan päällä lepäävä paikka, josta voi hypätä suoraan kirkkaaseen veteen. Lämmin järvivesi.', 111.00, 2, 'images/cabin8.jpg', 'Hämeenlinna', NULL, 1),
(22, 1, 'Kalakukko', 'Villa Kalakukko on tunnelmallinen ja modernisti varusteltu hirsimökki, joka sijaitsee rauhallisella paikalla kirkasvetisen järven rannalla. ', 11.00, 11, '/uploads/1760091280_Mökki-110-kesäulko-2-scaled-e1588589079801.jpg', 'Kuopio', NULL, 1);

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
(24, 28, 22, 14, 5, 'Hyvä mökki kalakukko maistuu hyvältä.', '2025-10-10 10:16:38', 1),
(25, 26, 1, 14, 1, 'Todella likainen sisältä. En voi suositella.', '2025-10-10 10:16:49', 1),
(26, 27, 15, 14, 4, 'Hyvä mökki Kallionranalla kalastus ei onnistunut.', '2025-10-10 10:17:06', 1),
(27, 30, 3, 16, 4, 'Hyvä mökki voin suositella.', '2025-10-10 10:19:09', 1),
(28, 29, 1, 16, 1, 'AAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAA\r\n\r\nHUONO MÖKKI', '2025-10-10 10:19:16', 1),
(29, 31, 6, 16, 2, 'Siisti mökki mutta kallis', '2025-10-10 10:19:23', 1),
(30, 32, 3, 1, 5, 'Hieno mökki. En voi muutakuin suositella.', '2025-10-10 10:20:53', 1);

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
(1, 'Eemeli', 'eemelimail@gmail.com', '04049027931', 'uploads/1760091145_1200x630cw-removebg-preview.png', '$2y$10$F0B8XZPqtzZYT6S0.F.B3./MGP3TcsuoM3oG7XAMEjQrJDX06WoaG', '2025-09-15 10:43:23', 5462.00, 'admin'),
(14, 'Samu', 'samumail@gmail.com', '0450002085', 'images/avatar.jpg', '$2y$10$ZpWLP4euK4sySvwiZoGiFuxIsWIWAfIcfJTAE.0tRlnXDyNjd/yZW', '2025-10-10 10:00:53', 698.00, 'user'),
(15, 'Kim', 'kimmail@gmail.com', '04400295972', 'images/avatar.jpg', '$2y$10$b19bkCTQ2ZtcqU0.Inz7Fe5UCeKl2jyKpe/1c.UTCJBY.YoknoaZe', '2025-10-10 10:01:08', 400.00, 'user'),
(16, 'Topi', 'topimail@gmail.com', '04003997881', 'images/avatar.jpg', '$2y$10$tAlUi5vKmjZLEAuhSZXWn.EnrBu.v5JG6FLZSso8He.LSrhJw8ORK', '2025-10-10 10:01:40', 14810.00, 'user');

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=34;

--
-- AUTO_INCREMENT for table `cabins`
--
ALTER TABLE `cabins`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=30;

--
-- AUTO_INCREMENT for table `reviews`
--
ALTER TABLE `reviews`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

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
