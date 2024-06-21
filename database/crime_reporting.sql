-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: Jun 21, 2024 at 10:52 AM
-- Server version: 8.2.0
-- PHP Version: 8.2.13

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `crime_reporting`
--

-- --------------------------------------------------------

--
-- Table structure for table `police_stations`
--

DROP TABLE IF EXISTS `police_stations`;
CREATE TABLE IF NOT EXISTS `police_stations` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `address` varchar(255) NOT NULL,
  `location` varchar(250) DEFAULT NULL,
  `longitude` float DEFAULT NULL,
  `latitude` float DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `police_stations`
--

INSERT INTO `police_stations` (`id`, `name`, `address`, `location`, `longitude`, `latitude`) VALUES
(1, 'Kyanja Police', 'Kyanja', 'Kyanja', 0.621126, 22.5911),
(2, 'Tuba Police station', 'Tuba Ring Road', 'Tuba Ring Road', 0.321126, 32.5911),
(3, 'Nasana Police Station', 'Nansana', 'Nansana', 8.08888, 0.321126),
(4, 'Nasana Police Station', 'Nansana', 'Nansana', 8.08888, 0.321126);

-- --------------------------------------------------------

--
-- Table structure for table `reports`
--

DROP TABLE IF EXISTS `reports`;
CREATE TABLE IF NOT EXISTS `reports` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `phone` varchar(15) NOT NULL,
  `description` text,
  `latitude` float NOT NULL,
  `longitude` float NOT NULL,
  `image_path` varchar(255) DEFAULT NULL,
  `police_station_id` int DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `id` (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `reports`
--

INSERT INTO `reports` (`id`, `name`, `phone`, `description`, `latitude`, `longitude`, `image_path`, `police_station_id`) VALUES
(1, 'Kaddu Livingstone', '0701512709', 'muhoozi is here', 0.321126, 32.5911, 'uploads/WhatsApp Image 2024-06-18 at 13.26.24_c2cc1b4f.jpg', 2),
(2, 'Kaddu Livingstone', '0701512709', 'fight', 0.321126, 32.5911, 'uploads/66752f247f85c-WhatsApp Image 2024-06-17 at 19.16.08_7f25f430.jpg', 1);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
CREATE TABLE IF NOT EXISTS `users` (
  `id` int NOT NULL AUTO_INCREMENT,
  `names` varchar(50) DEFAULT NULL,
  `phone` varchar(100) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `police_station_id` int DEFAULT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('admin','police') NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `names`, `phone`, `email`, `police_station_id`, `username`, `password`, `role`) VALUES
(1, 'Admin', '0752067815', 'admin@gmail.com', NULL, 'admin', 'e10adc3949ba59abbe56e057f20f883e', 'admin'),
(2, 'Kaddu Livingstone', '0701512709', 'kaddulivingston@gmail.com', 2, 'kaddulivingston', 'e10adc3949ba59abbe56e057f20f883e', 'police');
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
