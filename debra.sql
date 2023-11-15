-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Oct 12, 2023 at 05:58 PM
-- Server version: 10.4.28-MariaDB
-- PHP Version: 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `debra`
--

-- --------------------------------------------------------

--
-- Table structure for table `bookings`
--

CREATE TABLE `bookings` (
  `booking_id` int(11) NOT NULL,
  `event_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `address` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `contactNo` varchar(20) NOT NULL,
  `numTickets` int(11) NOT NULL,
  `booking_date` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `bookings`
--

INSERT INTO `bookings` (`booking_id`, `event_id`, `name`, `address`, `email`, `contactNo`, `numTickets`, `booking_date`) VALUES
(2, 3, 'Ajith', 'Colombu', 'ajith@gmail.com', '0776688111', 5, '2023-10-06 14:57:48'),
(3, 1, 'Kamal', 'Kandy', 'kamal@gmail.com', '0754326789', 3, '2023-10-09 15:58:05'),
(4, 1, 'Tharshikan', 'jaffna', 'X@gmail.com', '110', 2, '2023-10-11 04:25:28'),
(5, 6, 'Sujeevan', 'Nallur', 'sujee@gmail.com', '0762224444', 4, '2023-10-11 05:53:11'),
(6, 8, 'Gobi', 'Jaffna', 'gobi@gmail.com', '0765591033', 3, '2023-10-11 05:54:30'),
(7, 9, 'Dinojan', 'Kokuvil', 'dinojan@gmail.com', '0753306780', 5, '2023-10-11 05:55:26');

-- --------------------------------------------------------

--
-- Table structure for table `events`
--

CREATE TABLE `events` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `date` date NOT NULL,
  `location` varchar(255) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `available_tickets` int(11) NOT NULL,
  `user_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `events`
--

INSERT INTO `events` (`id`, `title`, `description`, `date`, `location`, `price`, `available_tickets`, `user_id`) VALUES
(1, 'Black Pink', 'K-POP', '2023-10-20', 'Colombo', 8500.00, 1000, 1),
(2, 'BTS', 'K-POP', '2023-10-22', 'Colombo', 8000.00, 800, 1),
(3, 'Tamil Hits', 'Anirudh', '2023-10-18', 'Jaffna', 6000.00, 500, 4),
(4, 'LISA', 'K-POP', '2023-10-19', 'Colombo', 7500.00, 900, 1),
(5, 'RedVelvet', 'K-POP', '2023-10-28', 'Colombo', 8000.00, 700, 1),
(6, 'A. R. Rahman Hits', 'A. R. Rahman', '2023-11-10', 'Colombo', 10000.00, 1500, 1),
(7, 'Hip Hop Thamizha', 'Meesaya Murukku', '2023-11-05', 'Jaffna', 7000.00, 1200, 4),
(8, 'GV Mash', '#G. V. Prakash Kumar#\r\n', '2023-11-04', 'Jaffna', 7000.00, 1000, 4),
(9, 'D. Imman Musical Night', 'D. Imman', '2023-11-12', 'Colombo', 8000.00, 1100, 4);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('admin','partner','customer') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `email`, `password`, `role`) VALUES
(1, 'admin', 'admin@gmail.com', '$2y$10$d.5SjeEaWz2iaPvatLGf/OosRUj7J6XjD0D5CwC/OJQorqwoeYHPC', 'admin'),
(2, 'Test', 'test@gmail.com', '$2y$10$ltD2NEdJ7VLgTw.fMfTgH.hTEhOGbp2CNsotY1vHzGXBU33HAfqnK', 'customer'),
(4, 'user', 'user@gmail.com', '$2y$10$QCclqgDuJAwfx4RXfp1zk.fvdGaRC7N2GorZ1PZ0DMbqsHqgFobmy', 'partner'),
(6, 'vijay', 'vijay@gmail.com', '$2y$10$hO.o1tnUydedTO6TFxHiOOxswjQQIg4kMqstRsGOfBZ5iegyjNTRa', 'customer'),
(8, 'grooveMaster', 'groovemaster@gmail.com', '$2y$10$Q./3xvMwPLRQO.kwNgMZvOed14ypFVsZFPB0ssXOYtW/HcabBlmqq', 'partner'),
(9, 'starEventPlanner', 'stareventplanner@gmail.com', '$2y$10$WEXYo6fnZJ5i2O6YLVnhx.OXI92vRUD29O/N9aXiMI8GfA4mUnUqG', 'partner'),
(10, 'liveMusicPro', 'livemusicpro@gmail.com', '$2y$10$jW19riWUSnZutnTBIYvYyOaNWdp6J13pAVoy617NevaJQ9IvZTjaG', 'partner'),
(11, 'melodyMaker', 'melodymaker@gmail.com', '$2y$10$T9XF29t.gFZI3XrUFN.PoOsBSrQMRgV7zS8YpjYUxH5L8wEOS0LXK', 'partner'),
(12, 'eventHarmony', 'eventharmony@gmail.com', '$2y$10$H/ChGs9fxaOjsHaQqsOqUOGHllun0T561JvhYrsqj3ed1zME2DYC.', 'partner'),
(13, 'encoreEntertainer', 'encoreentertainer@gmail.com', '$2y$10$G5Qqo/gNmlCe83hvNGRJb.Euq9GwISHEpsSV6A.7bSR1ipIe.h5ya', 'partner'),
(14, 'soundWaveOrganizer', 'soundwaveorganizer@gmail.com', '$2y$10$LdP3j6TjNIcZqlSeFrRb/uwijHix4219C9kLro5yKevH/5eq0kk/m', 'partner'),
(15, 'rhythmRider', 'rhythmrider@gmail.com', '$2y$10$a9TriTVhO7FChNxs94hOI.s396NRQLofSxX4KSLUpJtHL75OsH6nG', 'partner'),
(16, 'beatMixer', 'beatmixer@gmail.com', '$2y$10$OrzHpKKJrXfhSixyt/aNwOsroxtSMHVFzT7lPBQ783CJqKg8sAYIK', 'partner'),
(17, 'showstopperEvents', 'showstopperevents@gmail.com', '$2y$10$p6mGWeUlnwX1jyGe.GHPye46L9N3i7EfMmSbhq1npvkzsmb4pwHSG', 'partner'),
(18, 'Jasi', 'jasi@gmail.com', '$2y$10$RQY.tnxJRdsS570IhCHKxeTwiBGmi7J/iBd0sMMhPi3RsEqJxRp9O', 'customer');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `bookings`
--
ALTER TABLE `bookings`
  ADD PRIMARY KEY (`booking_id`),
  ADD KEY `event_id` (`event_id`);

--
-- Indexes for table `events`
--
ALTER TABLE `events`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

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
-- AUTO_INCREMENT for table `bookings`
--
ALTER TABLE `bookings`
  MODIFY `booking_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `events`
--
ALTER TABLE `events`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `bookings`
--
ALTER TABLE `bookings`
  ADD CONSTRAINT `bookings_ibfk_1` FOREIGN KEY (`event_id`) REFERENCES `events` (`id`);

--
-- Constraints for table `events`
--
ALTER TABLE `events`
  ADD CONSTRAINT `events_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
