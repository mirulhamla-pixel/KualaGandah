-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Apr 18, 2026 at 07:09 AM
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
-- Database: `kuala_gandah`
--

-- --------------------------------------------------------

--
-- Table structure for table `bookings`
--

CREATE TABLE `bookings` (
  `booking_id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `booking_date` date DEFAULT NULL,
  `adult_qty` int(11) DEFAULT NULL,
  `child_qty` int(11) DEFAULT NULL,
  `total_price` double DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `status` varchar(20) NOT NULL DEFAULT 'Pending',
  `receipt_file` varchar(255) DEFAULT NULL,
  `ticket_category` varchar(50) NOT NULL DEFAULT 'General'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `bookings`
--

INSERT INTO `bookings` (`booking_id`, `user_id`, `booking_date`, `adult_qty`, `child_qty`, `total_price`, `created_at`, `status`, `receipt_file`, `ticket_category`) VALUES
(45, 5, '2026-03-19', 2, 1, 75, '2026-03-16 06:36:10', 'Pending', NULL, 'General'),
(46, 1, '2026-03-24', 1, 1, 45, '2026-03-16 06:45:00', 'Pending', NULL, 'General'),
(47, 1, '2026-03-27', 1, 0, 30, '2026-03-16 18:53:33', 'Pending', NULL, 'General'),
(48, 1, '2026-03-27', 1, 2, 60, '2026-03-16 18:54:03', 'Pending', NULL, 'General'),
(49, 1, '2026-03-19', 1, 0, 30, '2026-03-17 07:40:44', 'Pending', NULL, 'General'),
(50, 1, '2026-03-17', 0, 1, 15, '2026-03-17 07:41:49', 'Pending', NULL, 'General'),
(51, 1, '2026-03-17', 1, 0, 30, '2026-03-17 07:57:43', 'Pending', NULL, 'General'),
(52, 5, '2026-04-29', 0, 3, 45, '2026-04-18 04:28:23', 'Pending', NULL, 'General'),
(53, 5, '2026-04-22', 6, 7, 285, '2026-04-18 04:28:40', 'Pending', NULL, 'General'),
(54, 2, '2026-04-20', 6, 0, 180, '2026-04-18 04:29:34', 'Pending', NULL, 'General'),
(55, 2, '2026-04-26', 0, 7, 105, '2026-04-18 04:29:49', 'Pending', NULL, 'General'),
(56, 5, '2026-04-24', 1, 3, 75, '2026-04-18 04:47:43', 'Paid', 'receipt_56_1776488769.jpg', 'Family');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `fullname` varchar(100) DEFAULT NULL,
  `username` varchar(50) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `regdate` date DEFAULT NULL,
  `role` enum('admin','customer') DEFAULT 'customer'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `fullname`, `username`, `password`, `phone`, `regdate`, `role`) VALUES
(1, 'Muhammad Faqrulddin Bin Roselan', 'faqrul', '$2y$10$ACrii3WAPwA5ts5yuEbJj.a/kmLZFq.v0VYubMzFmf3mqEkjKLUH2', '01124411041', '2026-03-14', 'admin'),
(2, 'Muhammad Akmal Haziq bin Azmi', 'Akmal', '$2y$10$UzwB5grh/0fa8FnJsoGeCOjZaCaQirS6Hy2M621QSRkuvtlydC5VK', '0123456789', '2026-03-14', 'customer'),
(3, 'Muhammad Faqrul Zaim Bin Roselan', 'Zaim', '$2y$10$qBqRyvihCHtQS/HNdlt1B.XFxhpVcaMGn8HUzj.tPP5Jt3EONRNE6', '0179397941', '2026-03-15', 'customer'),
(5, 'Muhammad Faqrul Afif Bin Roselan', 'Afif', '$2y$10$WLDhlmEfHDfztfXT/8Pw4OOd75ekOOKNtoI5M.iDmp.eMVF.C93SW', '014567890', '2026-03-16', 'customer');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `bookings`
--
ALTER TABLE `bookings`
  ADD PRIMARY KEY (`booking_id`);

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
  MODIFY `booking_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=57;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
