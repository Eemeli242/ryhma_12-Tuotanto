-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Sep 25, 2025 at 02:48 PM
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
  `cabin_id` int(11) NOT NULL,
  `customer_name` varchar(150) NOT NULL,
  `customer_email` varchar(150) NOT NULL,
  `start_date` date NOT NULL,
  `end_date` date NOT NULL,
  `guests` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `paid` decimal(10,2) DEFAULT 0.00,
  `status` enum('pending','paid','cancelled') DEFAULT 'pending'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `bookings`
--

INSERT INTO `bookings` (`id`, `cabin_id`, `customer_name`, `customer_email`, `start_date`, `end_date`, `guests`, `created_at`, `paid`, `status`) VALUES
(9, 1, 'Eemeli Määttä', 'maatta.eemeli@sähköposti.fi', '2025-09-15', '2025-09-27', 1, '2025-09-15 15:24:30', 1440.00, 'paid'),
(10, 3, 'Eemeli Määttä', 'dawdwad@gmail.com', '2025-09-15', '2025-09-27', 2, '2025-09-15 15:29:34', 1800.00, 'paid'),
(11, 6, 'Eemeli Määttä', 'maatta.eemeli@sähköposti.fi', '2025-09-15', '2025-09-20', 1, '2025-09-15 15:29:52', 500.00, 'paid'),
(13, 4, 'Eemeli Määttä', 'maatta.eemeli@sähköposti.fi', '2025-09-15', '2025-09-16', 1, '2025-09-15 15:34:27', 200.00, 'paid'),
(14, 4, 'Eemeli Määttä', 'dawdwad@gmail.com', '2025-09-21', '2025-09-22', 1, '2025-09-15 15:34:45', 200.00, 'paid'),
(15, 18, 'awdwad wad', 'wjdajwd2@gmail.com', '2025-09-21', '2025-09-27', 1, '2025-09-21 14:35:41', 66.00, 'paid');

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
  `user_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `cabins`
--

INSERT INTO `cabins` (`id`, `owner_id`, `name`, `description`, `price_per_night`, `max_guests`, `image`, `location`, `user_id`) VALUES
(1, 0, 'Mäntymetsän Kallio', 'Mäntymetsän Kallio – Korkealla kallion laella seisova mökki, jota ympäröivät vanhat, tuoksuvat männyt.', 120.00, 4, 'images/cabin1.jpg', 'Helsinki', NULL),
(2, 0, 'Kuunsilta', 'Kuunsilta – Rauhallinen rantamökki, jossa kuu heijastuu iltaisin veteen luoden hopeisen “sillan” laiturilta horisonttiin.', 180.00, 6, 'images/cabin2.jpg', 'Turku', NULL),
(3, 0, 'Tuulensuo', 'Tuulensuo – Metsän keskellä sijaitseva suojaisa paikka, jossa tuulen humina kuuluu kaukaa mutta piha pysyy tyynenä.', 150.00, 5, 'images/cabin3.jpg', 'Lahti', NULL),
(4, 0, 'Siniranta', 'Siniranta – Kirkasvetisen järven rannalla, jossa vesi heijastaa taivaan syvänsinisen sävyn kesäpäivinä.adaadadddd', 200.00, 6, 'images/cabin4.jpg', 'Jyväskylä', NULL),
(5, 0, 'Karhunpesä', 'Karhunpesä – Vankka ja lämmin hirsimökki, joka huokuu erämaan voimaa ja kutsuu talvi-iltoina takkatulen äärelle.', 130.00, 2, 'images/cabin5.jpg', 'Turku', NULL),
(6, 0, 'Lumilinna', 'Lumilinna – Talven taikaa rakastavalle: mökki, joka peittyy kauniiseen lumipeitteeseen ja loistaa kynttilälyhtyjen valossa.', 100.00, 1, 'images/cabin6.jpg', 'Jyväskylä', 1),
(14, 1, 'Kesäheinä', 'Kesäheinä – Avoimella niityllä sijaitseva mökki, jonka ympärillä tuoksuvat luonnonkukat ja heinäladot.', 11.00, 11, 'images/cabin7.jpg', 'Hämeenlinna', NULL),
(15, 1, 'Kallioranta', 'Kallioranta – Jyrkän kalliorannan päällä lepäävä paikka, josta voi hypätä suoraan kirkkaaseen veteen.', 111.00, 1, 'images/cabin8.jpg', 'Hämeenlinna', NULL),
(16, 1, 'Varjokallio', 'Varjokallio – Jylhä kivikko ja varjoisat puut luovat viileän ja salaperäisen tunnelman kuumimpinakin kesäpäivinä.', 1111.00, 12, 'images/cabin9.jpg', 'Hämeenlinna', NULL),
(17, 1, 'Eemeli', '112321', 111.00, 1, 'uploads/1758464072_123.jpeg', 'Kemi', NULL),
(18, 1, 'awda', 'aw223aaw', 11.00, 11, 'uploads/1758465256_123.jpeg', 'Kajaani', NULL);

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
  `balance` decimal(10,2) DEFAULT 0.00
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `email`, `phone`, `profile_image`, `password`, `created_at`, `balance`) VALUES
(1, 'eemeli', 'eemeli@gmail.com1', '04000000', 'uploads/1758465740_1200x630cw-removebg-preview.png', '$2y$10$F0B8XZPqtzZYT6S0.F.B3./MGP3TcsuoM3oG7XAMEjQrJDX06WoaG', '2025-09-15 10:43:23', 200950.00);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `bookings`
--
ALTER TABLE `bookings`
  ADD PRIMARY KEY (`id`),
  ADD KEY `cabin_id` (`cabin_id`);

--
-- Indexes for table `cabins`
--
ALTER TABLE `cabins`
  ADD PRIMARY KEY (`id`),
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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `cabins`
--
ALTER TABLE `cabins`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `bookings`
--
ALTER TABLE `bookings`
  ADD CONSTRAINT `bookings_ibfk_1` FOREIGN KEY (`cabin_id`) REFERENCES `cabins` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `cabins`
--
ALTER TABLE `cabins`
  ADD CONSTRAINT `cabins_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
