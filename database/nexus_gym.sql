-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Oct 11, 2025 at 06:05 PM
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
-- Database: `nexus_gym`
--

-- --------------------------------------------------------

--
-- Table structure for table `admins`
--

CREATE TABLE `admins` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `email` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admins`
--

INSERT INTO `admins` (`id`, `username`, `password`, `email`) VALUES
(2, 'admin', '$2y$10$2IS/WSMoNVyU4bCsMqnm4eb.wfV0l74y9YfBsJqXaMeCAlf5wBW/y', 'admin@gmail.com');

-- --------------------------------------------------------

--
-- Table structure for table `class_bookings`
--

CREATE TABLE `class_bookings` (
  `id` int(11) NOT NULL,
  `class_id` int(11) NOT NULL,
  `member_id` int(11) NOT NULL,
  `booking_date` date NOT NULL,
  `status` enum('booked','cancelled','attended') DEFAULT 'booked',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `class_bookings`
--

INSERT INTO `class_bookings` (`id`, `class_id`, `member_id`, `booking_date`, `status`, `created_at`) VALUES
(0, 1, 38, '2025-10-10', 'booked', '2025-10-10 08:22:49'),
(0, 1, 40, '2025-10-10', 'booked', '2025-10-10 08:22:49'),
(0, 1, 3, '2025-10-10', 'booked', '2025-10-10 08:22:49'),
(0, 1, 36, '2025-10-10', 'booked', '2025-10-10 08:22:49'),
(0, 1, 5, '2025-10-10', 'booked', '2025-10-10 08:22:49'),
(0, 1, 4, '2025-10-10', 'booked', '2025-10-10 08:22:49'),
(0, 1, 39, '2025-10-10', 'booked', '2025-10-10 08:22:49'),
(0, 1, 37, '2025-10-10', 'booked', '2025-10-10 08:22:49'),
(0, 1, 1, '2025-10-10', 'booked', '2025-10-10 08:22:49'),
(0, 1, 2, '2025-10-11', 'booked', '2025-10-10 08:22:49'),
(0, 2, 38, '2025-10-10', 'booked', '2025-10-10 08:22:49'),
(0, 2, 2, '2025-10-10', 'booked', '2025-10-10 08:22:49'),
(0, 2, 3, '2025-10-10', 'booked', '2025-10-10 08:22:49'),
(0, 2, 39, '2025-10-10', 'booked', '2025-10-10 08:22:49'),
(0, 2, 37, '2025-10-10', 'booked', '2025-10-10 08:22:49'),
(0, 2, 5, '2025-10-10', 'booked', '2025-10-10 08:22:49'),
(0, 2, 40, '2025-10-10', 'booked', '2025-10-10 08:22:49'),
(0, 2, 1, '2025-10-10', 'booked', '2025-10-10 08:22:49'),
(0, 2, 4, '2025-10-10', 'booked', '2025-10-10 08:22:49'),
(0, 2, 36, '2025-10-10', 'booked', '2025-10-10 08:22:49'),
(0, 3, 36, '2025-10-10', 'booked', '2025-10-10 08:22:49'),
(0, 3, 39, '2025-10-11', 'booked', '2025-10-10 08:22:49'),
(0, 3, 3, '2025-10-11', 'booked', '2025-10-10 08:22:49'),
(0, 3, 4, '2025-10-11', 'booked', '2025-10-10 08:22:49'),
(0, 3, 5, '2025-10-11', 'booked', '2025-10-10 08:22:49'),
(0, 3, 38, '2025-10-11', 'booked', '2025-10-10 08:22:49'),
(0, 3, 1, '2025-10-11', 'booked', '2025-10-10 08:22:49'),
(0, 3, 2, '2025-10-11', 'booked', '2025-10-10 08:22:49'),
(0, 3, 40, '2025-10-11', 'booked', '2025-10-10 08:22:49'),
(0, 3, 37, '2025-10-11', 'booked', '2025-10-10 08:22:49'),
(0, 4, 36, '2025-10-10', 'booked', '2025-10-10 08:22:49'),
(0, 4, 39, '2025-10-10', 'booked', '2025-10-10 08:22:49'),
(0, 4, 37, '2025-10-10', 'booked', '2025-10-10 08:22:49'),
(0, 4, 2, '2025-10-10', 'booked', '2025-10-10 08:22:49'),
(0, 4, 4, '2025-10-11', 'booked', '2025-10-10 08:22:49'),
(0, 4, 38, '2025-10-11', 'booked', '2025-10-10 08:22:49'),
(0, 4, 3, '2025-10-11', 'booked', '2025-10-10 08:22:49'),
(0, 4, 40, '2025-10-11', 'booked', '2025-10-10 08:22:49'),
(0, 4, 1, '2025-10-11', 'booked', '2025-10-10 08:22:49'),
(0, 4, 5, '2025-10-11', 'booked', '2025-10-10 08:22:49'),
(0, 5, 39, '2025-10-10', 'booked', '2025-10-10 08:22:49'),
(0, 5, 37, '2025-10-10', 'booked', '2025-10-10 08:22:49'),
(0, 5, 5, '2025-10-10', 'booked', '2025-10-10 08:22:49'),
(0, 5, 38, '2025-10-10', 'booked', '2025-10-10 08:22:49'),
(0, 5, 3, '2025-10-10', 'booked', '2025-10-10 08:22:49'),
(0, 5, 4, '2025-10-10', 'booked', '2025-10-10 08:22:49'),
(0, 5, 36, '2025-10-10', 'booked', '2025-10-10 08:22:49'),
(0, 5, 2, '2025-10-10', 'booked', '2025-10-10 08:22:49'),
(0, 5, 40, '2025-10-11', 'booked', '2025-10-10 08:22:49'),
(0, 5, 1, '2025-10-12', 'booked', '2025-10-10 08:22:49'),
(0, 6, 3, '2025-10-10', 'booked', '2025-10-10 08:22:49'),
(0, 6, 39, '2025-10-10', 'booked', '2025-10-10 08:22:49'),
(0, 6, 36, '2025-10-10', 'booked', '2025-10-10 08:22:49'),
(0, 6, 40, '2025-10-10', 'booked', '2025-10-10 08:22:49'),
(0, 6, 37, '2025-10-10', 'booked', '2025-10-10 08:22:49'),
(0, 6, 1, '2025-10-10', 'booked', '2025-10-10 08:22:49'),
(0, 6, 2, '2025-10-10', 'booked', '2025-10-10 08:22:49'),
(0, 6, 38, '2025-10-10', 'booked', '2025-10-10 08:22:49'),
(0, 6, 4, '2025-10-10', 'booked', '2025-10-10 08:22:49'),
(0, 6, 5, '2025-10-10', 'booked', '2025-10-10 08:22:49'),
(0, 7, 3, '2025-10-10', 'booked', '2025-10-10 08:22:49'),
(0, 7, 40, '2025-10-10', 'booked', '2025-10-10 08:22:49'),
(0, 7, 4, '2025-10-10', 'booked', '2025-10-10 08:22:49'),
(0, 7, 1, '2025-10-10', 'booked', '2025-10-10 08:22:49'),
(0, 7, 2, '2025-10-10', 'booked', '2025-10-10 08:22:49'),
(0, 7, 5, '2025-10-10', 'booked', '2025-10-10 08:22:49'),
(0, 7, 39, '2025-10-10', 'booked', '2025-10-10 08:22:49'),
(0, 7, 36, '2025-10-10', 'booked', '2025-10-10 08:22:49'),
(0, 7, 38, '2025-10-11', 'booked', '2025-10-10 08:22:49'),
(0, 7, 37, '2025-10-11', 'booked', '2025-10-10 08:22:49'),
(0, 8, 39, '2025-10-10', 'booked', '2025-10-10 08:22:49'),
(0, 8, 38, '2025-10-10', 'booked', '2025-10-10 08:22:49'),
(0, 8, 1, '2025-10-10', 'booked', '2025-10-10 08:22:49'),
(0, 8, 37, '2025-10-10', 'booked', '2025-10-10 08:22:49'),
(0, 8, 3, '2025-10-11', 'booked', '2025-10-10 08:22:49'),
(0, 8, 36, '2025-10-11', 'booked', '2025-10-10 08:22:49'),
(0, 8, 40, '2025-10-11', 'booked', '2025-10-10 08:22:49'),
(0, 8, 5, '2025-10-11', 'booked', '2025-10-10 08:22:49'),
(0, 8, 2, '2025-10-11', 'booked', '2025-10-10 08:22:49'),
(0, 8, 4, '2025-10-11', 'booked', '2025-10-10 08:22:49'),
(0, 9, 5, '2025-10-10', 'booked', '2025-10-10 08:22:49'),
(0, 9, 38, '2025-10-10', 'booked', '2025-10-10 08:22:49'),
(0, 9, 39, '2025-10-10', 'booked', '2025-10-10 08:22:49'),
(0, 9, 4, '2025-10-10', 'booked', '2025-10-10 08:22:49'),
(0, 9, 40, '2025-10-10', 'booked', '2025-10-10 08:22:49'),
(0, 9, 3, '2025-10-10', 'booked', '2025-10-10 08:22:49'),
(0, 9, 2, '2025-10-10', 'booked', '2025-10-10 08:22:49'),
(0, 9, 36, '2025-10-10', 'booked', '2025-10-10 08:22:49'),
(0, 9, 1, '2025-10-10', 'booked', '2025-10-10 08:22:49'),
(0, 9, 37, '2025-10-10', 'booked', '2025-10-10 08:22:49'),
(0, 10, 4, '2025-10-10', 'booked', '2025-10-10 08:22:49'),
(0, 10, 5, '2025-10-10', 'booked', '2025-10-10 08:22:49'),
(0, 10, 2, '2025-10-10', 'booked', '2025-10-10 08:22:49'),
(0, 10, 36, '2025-10-11', 'booked', '2025-10-10 08:22:49'),
(0, 10, 39, '2025-10-11', 'booked', '2025-10-10 08:22:49'),
(0, 10, 37, '2025-10-11', 'booked', '2025-10-10 08:22:49'),
(0, 10, 40, '2025-10-11', 'booked', '2025-10-10 08:22:49'),
(0, 10, 1, '2025-10-11', 'booked', '2025-10-10 08:22:49'),
(0, 10, 38, '2025-10-12', 'booked', '2025-10-10 08:22:49'),
(0, 10, 3, '2025-10-12', 'booked', '2025-10-10 08:22:49'),
(0, 11, 36, '2025-10-10', 'booked', '2025-10-10 08:22:49'),
(0, 11, 37, '2025-10-10', 'booked', '2025-10-10 08:22:49'),
(0, 11, 39, '2025-10-10', 'booked', '2025-10-10 08:22:49'),
(0, 11, 2, '2025-10-10', 'booked', '2025-10-10 08:22:49'),
(0, 11, 38, '2025-10-10', 'booked', '2025-10-10 08:22:49'),
(0, 11, 5, '2025-10-10', 'booked', '2025-10-10 08:22:49'),
(0, 11, 3, '2025-10-10', 'booked', '2025-10-10 08:22:49'),
(0, 11, 4, '2025-10-10', 'booked', '2025-10-10 08:22:49'),
(0, 11, 40, '2025-10-10', 'booked', '2025-10-10 08:22:49'),
(0, 11, 1, '2025-10-10', 'booked', '2025-10-10 08:22:49'),
(0, 12, 2, '2025-10-10', 'booked', '2025-10-10 08:22:49'),
(0, 12, 36, '2025-10-10', 'booked', '2025-10-10 08:22:49'),
(0, 12, 1, '2025-10-10', 'booked', '2025-10-10 08:22:49'),
(0, 12, 3, '2025-10-10', 'booked', '2025-10-10 08:22:49'),
(0, 12, 40, '2025-10-10', 'booked', '2025-10-10 08:22:49'),
(0, 12, 37, '2025-10-10', 'booked', '2025-10-10 08:22:49'),
(0, 12, 4, '2025-10-10', 'booked', '2025-10-10 08:22:49'),
(0, 12, 38, '2025-10-10', 'booked', '2025-10-10 08:22:49'),
(0, 12, 5, '2025-10-11', 'booked', '2025-10-10 08:22:49'),
(0, 12, 39, '2025-10-11', 'booked', '2025-10-10 08:22:49'),
(0, 13, 5, '2025-10-10', 'booked', '2025-10-10 08:22:49'),
(0, 13, 2, '2025-10-10', 'booked', '2025-10-10 08:22:49'),
(0, 13, 1, '2025-10-10', 'booked', '2025-10-10 08:22:49'),
(0, 13, 3, '2025-10-10', 'booked', '2025-10-10 08:22:49'),
(0, 13, 39, '2025-10-10', 'booked', '2025-10-10 08:22:49'),
(0, 13, 37, '2025-10-10', 'booked', '2025-10-10 08:22:49'),
(0, 13, 40, '2025-10-11', 'booked', '2025-10-10 08:22:49'),
(0, 13, 36, '2025-10-11', 'booked', '2025-10-10 08:22:49'),
(0, 13, 4, '2025-10-12', 'booked', '2025-10-10 08:22:49'),
(0, 13, 38, '2025-10-12', 'booked', '2025-10-10 08:22:49'),
(0, 14, 40, '2025-10-10', 'booked', '2025-10-10 08:22:49'),
(0, 14, 39, '2025-10-10', 'booked', '2025-10-10 08:22:49'),
(0, 14, 2, '2025-10-10', 'booked', '2025-10-10 08:22:49'),
(0, 14, 4, '2025-10-11', 'booked', '2025-10-10 08:22:49'),
(0, 14, 38, '2025-10-11', 'booked', '2025-10-10 08:22:49'),
(0, 14, 37, '2025-10-11', 'booked', '2025-10-10 08:22:49'),
(0, 14, 3, '2025-10-11', 'booked', '2025-10-10 08:22:49'),
(0, 14, 1, '2025-10-11', 'booked', '2025-10-10 08:22:49'),
(0, 14, 36, '2025-10-11', 'booked', '2025-10-10 08:22:49'),
(0, 14, 5, '2025-10-12', 'booked', '2025-10-10 08:22:49'),
(0, 15, 39, '2025-10-10', 'booked', '2025-10-10 08:22:49'),
(0, 15, 36, '2025-10-10', 'booked', '2025-10-10 08:22:49'),
(0, 15, 40, '2025-10-10', 'booked', '2025-10-10 08:22:49'),
(0, 15, 1, '2025-10-10', 'booked', '2025-10-10 08:22:49'),
(0, 15, 4, '2025-10-11', 'booked', '2025-10-10 08:22:49'),
(0, 15, 37, '2025-10-11', 'booked', '2025-10-10 08:22:49'),
(0, 15, 3, '2025-10-11', 'booked', '2025-10-10 08:22:49'),
(0, 15, 38, '2025-10-11', 'booked', '2025-10-10 08:22:49'),
(0, 15, 2, '2025-10-11', 'booked', '2025-10-10 08:22:49'),
(0, 15, 5, '2025-10-11', 'booked', '2025-10-10 08:22:49'),
(0, 16, 2, '2025-10-10', 'booked', '2025-10-10 08:22:49'),
(0, 16, 36, '2025-10-10', 'booked', '2025-10-10 08:22:49'),
(0, 16, 1, '2025-10-10', 'booked', '2025-10-10 08:22:49'),
(0, 16, 37, '2025-10-11', 'booked', '2025-10-10 08:22:49'),
(0, 16, 4, '2025-10-11', 'booked', '2025-10-10 08:22:49'),
(0, 16, 39, '2025-10-11', 'booked', '2025-10-10 08:22:49'),
(0, 16, 38, '2025-10-11', 'booked', '2025-10-10 08:22:49'),
(0, 16, 3, '2025-10-11', 'booked', '2025-10-10 08:22:49'),
(0, 16, 5, '2025-10-11', 'booked', '2025-10-10 08:22:49'),
(0, 16, 40, '2025-10-11', 'booked', '2025-10-10 08:22:49'),
(0, 17, 37, '2025-10-10', 'booked', '2025-10-10 08:22:49'),
(0, 17, 3, '2025-10-11', 'booked', '2025-10-10 08:22:49'),
(0, 17, 5, '2025-10-11', 'booked', '2025-10-10 08:22:49'),
(0, 17, 38, '2025-10-11', 'booked', '2025-10-10 08:22:49'),
(0, 17, 40, '2025-10-11', 'booked', '2025-10-10 08:22:49'),
(0, 17, 36, '2025-10-11', 'booked', '2025-10-10 08:22:49'),
(0, 17, 1, '2025-10-11', 'booked', '2025-10-10 08:22:49'),
(0, 17, 39, '2025-10-11', 'booked', '2025-10-10 08:22:49'),
(0, 17, 2, '2025-10-12', 'booked', '2025-10-10 08:22:49'),
(0, 17, 4, '2025-10-12', 'booked', '2025-10-10 08:22:49'),
(0, 18, 40, '2025-10-10', 'booked', '2025-10-10 08:22:49'),
(0, 18, 1, '2025-10-10', 'booked', '2025-10-10 08:22:49'),
(0, 18, 2, '2025-10-10', 'booked', '2025-10-10 08:22:49'),
(0, 18, 3, '2025-10-10', 'booked', '2025-10-10 08:22:49'),
(0, 18, 4, '2025-10-10', 'booked', '2025-10-10 08:22:49'),
(0, 18, 5, '2025-10-10', 'booked', '2025-10-10 08:22:49'),
(0, 18, 36, '2025-10-10', 'booked', '2025-10-10 08:22:49'),
(0, 18, 37, '2025-10-10', 'booked', '2025-10-10 08:22:49'),
(0, 18, 38, '2025-10-10', 'booked', '2025-10-10 08:22:49'),
(0, 18, 39, '2025-10-10', 'booked', '2025-10-10 08:22:49'),
(0, 19, 5, '2025-10-10', 'booked', '2025-10-10 08:22:49'),
(0, 19, 37, '2025-10-10', 'booked', '2025-10-10 08:22:49'),
(0, 19, 2, '2025-10-11', 'booked', '2025-10-10 08:22:49'),
(0, 19, 39, '2025-10-11', 'booked', '2025-10-10 08:22:49'),
(0, 19, 40, '2025-10-11', 'booked', '2025-10-10 08:22:49'),
(0, 19, 38, '2025-10-11', 'booked', '2025-10-10 08:22:49'),
(0, 19, 4, '2025-10-11', 'booked', '2025-10-10 08:22:49'),
(0, 19, 1, '2025-10-11', 'booked', '2025-10-10 08:22:49'),
(0, 19, 36, '2025-10-11', 'booked', '2025-10-10 08:22:49'),
(0, 19, 3, '2025-10-12', 'booked', '2025-10-10 08:22:49');

-- --------------------------------------------------------

--
-- Table structure for table `coach_availability`
--

CREATE TABLE `coach_availability` (
  `id` int(11) NOT NULL,
  `employee_id` int(11) NOT NULL,
  `available_day` varchar(50) NOT NULL,
  `available_time` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `coach_availability`
--

INSERT INTO `coach_availability` (`id`, `employee_id`, `available_day`, `available_time`) VALUES
(85, 2, 'Monday', '2:00 PM - 10:00 PM'),
(86, 2, 'Wednesday', '6:00 AM - 2:00 PM'),
(87, 2, 'Thursday', '10:00 AM - 6:00 PM'),
(88, 2, 'Saturday', '8:00 AM - 4:00 PM'),
(101, 6, 'Monday', '6:00 AM - 2:00 PM'),
(102, 6, 'Wednesday', '2:00 PM - 10:00 PM'),
(103, 6, 'Friday', '10:00 AM - 6:00 PM'),
(104, 6, 'Sunday', '8:00 AM - 4:00 PM'),
(107, 9, '', ''),
(109, 1, '', ''),
(110, 4, '', ''),
(111, 3, '', '');

-- --------------------------------------------------------

--
-- Table structure for table `duty_roster`
--

CREATE TABLE `duty_roster` (
  `id` int(11) NOT NULL,
  `employee_id` int(11) NOT NULL,
  `duty_date` date NOT NULL,
  `shift` varchar(50) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `employees`
--

CREATE TABLE `employees` (
  `id` int(11) NOT NULL,
  `first_name` varchar(50) NOT NULL,
  `last_name` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `position` varchar(50) DEFAULT NULL,
  `date_hired` date DEFAULT NULL,
  `status` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `employees`
--

INSERT INTO `employees` (`id`, `first_name`, `last_name`, `email`, `password`, `phone`, `position`, `date_hired`, `status`) VALUES
(1, 'Chris', 'Ledama', 'chris.ledama@nexusgym.com', '$2y$10$WnRSFnIdoN7DMAp6iEUAKeOvJ1HW32SAzHUQoNn0e3F6rC1JlDJnG', '09612099217', 'Head Coach', '2025-01-15', 'Active'),
(2, 'Cris John', 'Resonable', 'crisjohn.r@nexusgym.com', '$2y$10$WnRSFnIdoN7DMAp6iEUAKeOvJ1HW32SAzHUQoNn0e3F6rC1JlDJnG', '09234567890', 'Fitness Coach', '2025-02-01', 'Active'),
(3, 'Gian', 'Opsirc', 'gian.o@nexusgym.com', '$2y$10$WnRSFnIdoN7DMAp6iEUAKeOvJ1HW32SAzHUQoNn0e3F6rC1JlDJnG', '09345678901', 'Strength Coach', '2025-02-15', 'Active'),
(4, 'John Rique', 'Barnachea', 'johnrique.b@nexusgym.com', '$2y$10$WnRSFnIdoN7DMAp6iEUAKeOvJ1HW32SAzHUQoNn0e3F6rC1JlDJnG', '09456789012', 'Yoga Coach', '2025-03-01', 'Active'),
(5, 'Charles Selwyn', 'Lim', 'charles.lim@nexusgym.com', '$2y$10$WnRSFnIdoN7DMAp6iEUAKeOvJ1HW32SAzHUQoNn0e3F6rC1JlDJnG', '09567890123', 'CrossFit Coach', '2025-03-15', 'Active'),
(6, 'Marc Jorem', 'Luchavez', 'marcjorem.l@nexusgym.com', '$2y$10$WnRSFnIdoN7DMAp6iEUAKeOvJ1HW32SAzHUQoNn0e3F6rC1JlDJnG', '09678901234', 'Senior Coach', '2025-04-01', 'Active'),
(9, 'Selwyn', 'Lim', 'charleslim@gmail.com', '$2y$10$KhywMGoGBnoWkemveGCfVu38/airejOWI/8IddBJK4gS6sAz08BCK', '0949494949', 'Coach', '2025-10-10', 'Active'),
(10, 'employee', '1', 'employee1@gmail.com', '$2y$10$lk848ObEu5mTNbpLpFJvwOiA3Em1u262emF9LiZaHqF8IjPRDYVI.', '1231231', 'Trainer', '2024-10-10', 'Active'),
(11, '1', 'employee', '1employee@gmail.com', '$2y$10$O4PlljeCAmKnlf5RLro1pOXjlUGXQXzmqs07zMi.R158E8H1N/dmu', '12903812073', 'Trainer', '2025-08-08', 'Active');

-- --------------------------------------------------------

--
-- Table structure for table `fitness_classes`
--

CREATE TABLE `fitness_classes` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `description` text DEFAULT NULL,
  `trainer_id` int(11) NOT NULL,
  `day_of_week` enum('Monday','Tuesday','Wednesday','Thursday','Friday','Saturday','Sunday') NOT NULL,
  `time_slot` enum('Morning','Afternoon','Evening') NOT NULL,
  `capacity` int(11) NOT NULL DEFAULT 15
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `fitness_classes`
--

INSERT INTO `fitness_classes` (`id`, `name`, `description`, `trainer_id`, `day_of_week`, `time_slot`, `capacity`) VALUES
(1, 'Morning Yoga', 'Start your week with relaxing yoga session', 1, 'Monday', 'Morning', 15),
(2, 'HIIT Workout', 'High-intensity interval training for maximum results', 2, 'Monday', 'Afternoon', 12),
(3, 'Strength Training', 'Build muscle and strength with guided training', 3, 'Monday', 'Evening', 10),
(4, 'Spin Class', 'High-energy indoor cycling session', 4, 'Tuesday', 'Morning', 12),
(5, 'Core Focus', 'Core strengthening and stability exercises', 5, 'Tuesday', 'Afternoon', 15),
(6, 'Boxing Fitness', 'Learn boxing techniques and get fit', 6, 'Tuesday', 'Evening', 10),
(7, 'Power Yoga', 'Energetic yoga flow for strength and flexibility', 1, 'Wednesday', 'Morning', 15),
(8, 'Circuit Training', 'Full-body workout with various exercises', 2, 'Wednesday', 'Afternoon', 12),
(9, 'Cardio Blast', 'High-energy cardio workout', 3, 'Wednesday', 'Evening', 15),
(10, 'Morning Stretch', 'Start your day with gentle stretching', 4, 'Thursday', 'Morning', 15),
(11, 'Muscle Pump', 'Weight training for all fitness levels', 5, 'Thursday', 'Afternoon', 12),
(12, 'CrossFit Style', 'Varied functional movements at high intensity', 6, 'Thursday', 'Evening', 10),
(13, 'Yoga Flow', 'End your week with relaxing yoga', 1, 'Friday', 'Morning', 15),
(14, 'HIIT Express', 'Quick but intense workout session', 2, 'Friday', 'Afternoon', 12),
(15, 'Strength & Core', 'Focus on building strength and core stability', 3, 'Friday', 'Evening', 10),
(16, 'Weekend Warrior', 'Challenge yourself with this intense workout', 4, 'Saturday', 'Morning', 12),
(17, 'Mixed Martial Arts', 'Learn MMA techniques and get fit', 5, 'Saturday', 'Afternoon', 10),
(18, 'Sunday Yoga', 'Relaxing yoga to prepare for the week', 6, 'Sunday', 'Morning', 15),
(19, 'Full Body Workout', 'Complete body conditioning', 1, 'Sunday', 'Afternoon', 12);

-- --------------------------------------------------------

--
-- Table structure for table `members`
--

CREATE TABLE `members` (
  `id` int(11) NOT NULL,
  `first_name` varchar(50) NOT NULL,
  `last_name` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `membership_type` varchar(20) DEFAULT NULL,
  `status` varchar(20) DEFAULT NULL,
  `join_date` date DEFAULT NULL,
  `membership_end_date` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `members`
--

INSERT INTO `members` (`id`, `first_name`, `last_name`, `email`, `password`, `phone`, `membership_type`, `status`, `join_date`, `membership_end_date`) VALUES
(1, 'Juan', 'Dela Cruz', 'juan.dc@email.com', '$2y$10$WnRSFnIdoN7DMAp6iEUAKeOvJ1HW32SAzHUQoNn0e3F6rC1JlDJnG', '09111222333', 'Premium', 'Active', '2025-01-01', '2026-01-01'),
(2, 'Maria', 'Santos', 'maria.s@email.com', '$2y$10$WnRSFnIdoN7DMAp6iEUAKeOvJ1HW32SAzHUQoNn0e3F6rC1JlDJnG', '09222333444', 'Standard', 'Active', '2025-02-01', '2025-11-01'),
(3, 'Pedro', 'Reyes', 'pedro.r@email.com', '$2y$10$WnRSFnIdoN7DMAp6iEUAKeOvJ1HW32SAzHUQoNn0e3F6rC1JlDJnG', '09333444555', 'Premium', 'Active', '2025-03-01', '2026-03-01'),
(4, 'Ana', 'Lopez', 'ana.l@email.com', '$2y$10$WnRSFnIdoN7DMAp6iEUAKeOvJ1HW32SAzHUQoNn0e3F6rC1JlDJnG', '09444555666', 'Standard', 'Expired', '2025-04-01', '2025-10-01'),
(5, 'Miguel', 'Garcia', 'miguel.g@email.com', '$2y$10$WnRSFnIdoN7DMAp6iEUAKeOvJ1HW32SAzHUQoNn0e3F6rC1JlDJnG', '09555666777', 'Premium', 'Active', '2025-05-01', '2026-05-01'),
(36, 'Selwyn', 'Lim', 'selwyn@gmail.com', '$2y$10$HJENoeNL4ep7jy3v4jTWcuNtdA/1yDbQjZ7.vmSWlIGyi1g69nN32', '0949494949', 'Premium', 'Active', '2025-10-10', '2025-11-10'),
(37, 'Lim', 'Charles', 'limlim@gmail.com', '$2y$10$bZZ2bi1w5QtfQLpuWSbu2ODEkVCr.yGANHioglKlYq6WeWmiD6ONa', '0949494949', 'Premium', 'Active', '2025-10-10', '2025-11-10'),
(38, 'member', '1', 'member1@gmail.com', '$2y$10$glHXpweZtjGgLf/izLR8Tuobqv1j/qJiruh0agBUzPAppgNRxWqjK', '12312646', 'Standard', 'Active', '2025-08-08', '2026-10-10'),
(39, 'member', '2', 'member2@gmail.com', '$2y$10$4UAM5Z1sgSCfl3SkgC/Y1eGaym4ENszzXOpkjiXbrhB/mSmBY310y', '123712983', 'Standard', 'Active', '2023-05-05', '2025-11-11'),
(40, 'member', '3', 'member3@gmail.com', '$2y$10$Vu3.9chJnXgeyODieoK8xOm4KfDp9W9h7DnJ7/cnI6stufKyXQsWK', '12875912', 'Standard', 'Expired', '2024-05-05', '2025-10-10'),
(41, '1', 'member', '1member@gmail.com', '$2y$10$AxyGJAnlV27oQfdDeqr4luR3iYN7Ib3Q5Me87z5tNo.7J0MtbUS1u', '12371298371', 'Standard', 'Active', '2025-02-18', '2026-02-18'),
(42, '2', 'members', '2members@gmail.com', '$2y$10$lnLlnCclt//lRvnpAN5hYuc/dJ08i5MT4ya6g41ygNYTPL9HxKVgm', '654206846', 'Premium', 'Active', '2025-05-05', '2026-01-11');

-- --------------------------------------------------------

--
-- Table structure for table `membership_plans`
--

CREATE TABLE `membership_plans` (
  `id` int(11) NOT NULL,
  `name` varchar(50) NOT NULL,
  `description` text DEFAULT NULL,
  `duration_months` int(11) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `membership_plans`
--

INSERT INTO `membership_plans` (`id`, `name`, `description`, `duration_months`, `price`, `created_at`) VALUES
(1, 'Monthly', 'Basic monthly membership with access to all facilities', 1, 50.00, '2025-09-27 15:35:20'),
(2, 'Quarterly', '3-month membership with access to all facilities', 3, 140.00, '2025-09-27 15:35:20'),
(3, 'Semi-Annual', '6-month membership with access to all facilities', 6, 270.00, '2025-09-27 15:35:20'),
(4, 'Annual', 'Full year membership with access to all facilities', 12, 500.00, '2025-09-27 15:35:20');

-- --------------------------------------------------------

--
-- Table structure for table `member_attendance`
--

CREATE TABLE `member_attendance` (
  `id` int(11) NOT NULL,
  `member_id` int(11) NOT NULL,
  `session_date` date NOT NULL DEFAULT curdate(),
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `member_attendance`
--

INSERT INTO `member_attendance` (`id`, `member_id`, `session_date`, `created_at`) VALUES
(1, 36, '2025-10-10', '2025-10-09 20:30:52'),
(2, 36, '2025-10-10', '2025-10-09 20:30:57'),
(3, 36, '2025-10-09', '2025-10-09 20:34:11'),
(4, 37, '2025-10-10', '2025-10-10 03:24:22'),
(5, 38, '2025-10-10', '2025-10-10 06:59:44'),
(6, 38, '2025-10-11', '2025-10-11 15:40:40');

-- --------------------------------------------------------

--
-- Table structure for table `member_progress`
--

CREATE TABLE `member_progress` (
  `id` int(11) NOT NULL,
  `member_id` int(11) NOT NULL,
  `bench_press` varchar(50) DEFAULT NULL,
  `incline_press` varchar(50) DEFAULT NULL,
  `decline_press` varchar(50) DEFAULT NULL,
  `chest_fly` varchar(50) DEFAULT NULL,
  `overhead_press` varchar(50) DEFAULT NULL,
  `lateral_raises` varchar(50) DEFAULT NULL,
  `deadlift` decimal(6,2) DEFAULT 0.00,
  `lat_pulldown` decimal(6,2) DEFAULT 0.00,
  `weight_now` decimal(6,2) DEFAULT 0.00,
  `weight_before` decimal(6,2) DEFAULT 0.00,
  `squat` varchar(50) DEFAULT NULL,
  `leg_press` varchar(50) DEFAULT NULL,
  `romanian_deadlift` varchar(50) DEFAULT NULL,
  `rdl` varchar(50) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `member_progress`
--

INSERT INTO `member_progress` (`id`, `member_id`, `bench_press`, `incline_press`, `decline_press`, `chest_fly`, `overhead_press`, `lateral_raises`, `deadlift`, `lat_pulldown`, `weight_now`, `weight_before`, `squat`, `leg_press`, `romanian_deadlift`, `rdl`, `created_at`) VALUES
(1, 36, '50', '5', '5', '5', '5', '5', 5.00, 5.00, 5.00, 5.00, '5', '5', '5', '5', '2025-10-09 20:11:08');

-- --------------------------------------------------------

--
-- Table structure for table `member_subscriptions`
--

CREATE TABLE `member_subscriptions` (
  `id` int(11) NOT NULL,
  `member_id` int(11) NOT NULL,
  `plan_id` int(11) NOT NULL,
  `start_date` date NOT NULL,
  `end_date` date NOT NULL,
  `status` enum('active','expired','cancelled') DEFAULT 'active',
  `payment_status` enum('paid','pending','failed') DEFAULT 'pending',
  `amount_paid` decimal(10,2) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `member_subscriptions`
--

INSERT INTO `member_subscriptions` (`id`, `member_id`, `plan_id`, `start_date`, `end_date`, `status`, `payment_status`, `amount_paid`, `created_at`) VALUES
(1, 1, 4, '2025-01-01', '2026-01-01', 'active', 'paid', 500.00, '2025-01-01 09:00:00'),
(2, 2, 1, '2025-02-01', '2025-11-01', 'active', 'paid', 50.00, '2025-02-01 09:00:00'),
(3, 3, 4, '2025-03-01', '2026-03-01', 'active', 'paid', 500.00, '2025-03-01 09:00:00'),
(4, 4, 1, '2025-04-01', '2025-10-01', 'active', 'paid', 50.00, '2025-04-01 09:00:00'),
(5, 5, 4, '2025-05-01', '2026-05-01', 'active', 'paid', 500.00, '2025-05-01 09:00:00'),
(37, 40, 4, '2025-10-10', '2026-10-10', 'active', 'paid', 3800.00, '2025-10-10 07:13:32'),
(38, 38, 4, '2025-10-10', '2026-10-10', 'active', 'paid', 3800.00, '2025-10-10 07:25:54'),
(39, 38, 4, '2025-10-10', '2026-10-10', 'active', 'paid', 3800.00, '2025-10-10 07:41:35'),
(40, 39, 1, '2025-10-11', '2025-11-11', 'active', 'paid', 350.00, '2025-10-11 15:56:15'),
(41, 42, 2, '2025-10-11', '2026-01-11', 'active', 'paid', 1250.00, '2025-10-11 15:57:18');

--
-- Triggers `member_subscriptions`
--
DELIMITER $$
CREATE TRIGGER `update_member_subscription_status` AFTER UPDATE ON `member_subscriptions` FOR EACH ROW BEGIN
    
    IF NEW.status IN ('expired', 'cancelled') AND OLD.status = 'active' THEN
        
        IF NOT EXISTS (
            SELECT 1 FROM member_subscriptions 
            WHERE member_id = NEW.member_id 
            AND status = 'active' 
            AND id != NEW.id
        ) THEN
            
            UPDATE members 
            SET status = 'inactive' 
            WHERE id = NEW.member_id;
        END IF;
    
    ELSEIF NEW.status = 'active' AND OLD.status != 'active' THEN
        
        UPDATE members 
        SET status = 'active' 
        WHERE id = NEW.member_id;
    END IF;
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `notifications`
--

CREATE TABLE `notifications` (
  `id` int(11) NOT NULL,
  `employee_id` int(11) NOT NULL,
  `member_id` int(11) NOT NULL,
  `message` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `notifications`
--

INSERT INTO `notifications` (`id`, `employee_id`, `member_id`, `message`, `created_at`) VALUES
(34, 1, 1, 'Congratulations! You hit a new PR on bench press: 185 lbs', '2025-10-09 09:15:00'),
(35, 2, 2, 'Your next training session is scheduled for tomorrow at 3:00 PM', '2025-10-09 10:00:00'),
(36, 3, 3, 'Great progress on your deadlift form today!', '2025-10-09 11:30:00'),
(37, 4, 4, 'Your yoga session for tomorrow is confirmed', '2025-10-09 12:00:00'),
(38, 5, 5, 'Time to update your fitness progress - book a measurement session', '2025-10-09 13:00:00'),
(39, 6, 1, 'Your membership renewal is due in 30 days', '2025-10-09 14:00:00'),
(40, 1, 2, 'Don\'t forget your protein intake after today\'s intense session', '2025-10-09 15:00:00'),
(41, 2, 3, 'Your cardio endurance is improving! Keep it up!', '2025-10-09 16:00:00'),
(42, 3, 4, 'New personal best in squats - 125 lbs!', '2025-10-09 17:00:00'),
(43, 4, 5, 'Your flexibility has improved significantly this month', '2025-10-09 18:00:00'),
(44, 1, 38, 'New booking for Morning Yoga', '2025-10-10 07:42:14'),
(45, 2, 38, 'New booking for HIIT Workout', '2025-10-10 07:50:21'),
(46, 1, 38, 'New booking for Yoga Flow', '2025-10-10 08:03:36'),
(47, 6, 38, 'New booking for Sunday Yoga', '2025-10-10 08:06:45'),
(48, 1, 38, 'New booking for Full Body Workout', '2025-10-10 08:06:46');

-- --------------------------------------------------------

--
-- Table structure for table `roster_assignments`
--

CREATE TABLE `roster_assignments` (
  `id` int(11) NOT NULL,
  `employee_id` int(11) NOT NULL,
  `schedule_date` date NOT NULL,
  `shift` enum('Morning','Afternoon','Night') NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `schedules`
--

CREATE TABLE `schedules` (
  `id` int(11) NOT NULL,
  `employee_id` int(11) NOT NULL,
  `date` date NOT NULL,
  `shift_time` varchar(50) NOT NULL,
  `job_role` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `schedules`
--

INSERT INTO `schedules` (`id`, `employee_id`, `date`, `shift_time`, `job_role`) VALUES
(1, 5, '2025-10-09', 'Afternoon', 'Trainer'),
(2, 1, '2025-10-10', 'Morning', 'Receptionist'),
(3, 3, '2025-10-10', 'Night', 'Receptionist');

-- --------------------------------------------------------

--
-- Table structure for table `trainer_assignments`
--

CREATE TABLE `trainer_assignments` (
  `id` int(11) NOT NULL,
  `trainer_id` int(11) NOT NULL,
  `plan_day` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `trainer_assignments`
--

INSERT INTO `trainer_assignments` (`id`, `trainer_id`, `plan_day`) VALUES
(3, 1, 'Monday - Strength Training'),
(4, 1, 'Thursday - HIIT'),
(5, 2, 'Tuesday - Cardio'),
(6, 2, 'Friday - Endurance'),
(7, 3, 'Wednesday - Power Lifting'),
(8, 3, 'Saturday - Olympic Lifts'),
(9, 4, 'Monday - Morning Yoga'),
(10, 4, 'Thursday - Evening Yoga'),
(11, 5, 'Tuesday - CrossFit WOD'),
(12, 5, 'Friday - Team Training'),
(13, 6, 'Wednesday - Advanced Training'),
(14, 6, 'Saturday - Specialized Programs');

-- --------------------------------------------------------

--
-- Table structure for table `training_sessions`
--

CREATE TABLE `training_sessions` (
  `id` int(11) NOT NULL,
  `member_id` int(11) NOT NULL,
  `trainer_id` int(11) NOT NULL,
  `session_date` date NOT NULL,
  `session_time` time NOT NULL,
  `status` enum('scheduled','completed','cancelled') DEFAULT 'scheduled',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `training_sessions`
--

INSERT INTO `training_sessions` (`id`, `member_id`, `trainer_id`, `session_date`, `session_time`, `status`, `created_at`) VALUES
(4, 1, 1, '2025-10-09', '09:00:00', 'completed', '2025-10-08 15:00:00'),
(5, 2, 2, '2025-10-09', '15:00:00', 'completed', '2025-10-08 15:00:00'),
(6, 3, 3, '2025-10-09', '11:00:00', 'completed', '2025-10-08 15:00:00'),
(7, 4, 4, '2025-10-09', '16:00:00', 'completed', '2025-10-08 15:00:00'),
(8, 5, 5, '2025-10-09', '08:00:00', 'completed', '2025-10-08 15:00:00'),
(9, 1, 6, '2025-10-10', '14:00:00', 'scheduled', '2025-10-09 10:00:00'),
(10, 2, 1, '2025-10-10', '10:00:00', 'scheduled', '2025-10-09 10:00:00'),
(11, 3, 2, '2025-10-10', '13:00:00', 'scheduled', '2025-10-09 10:00:00'),
(12, 4, 3, '2025-10-10', '17:00:00', 'scheduled', '2025-10-09 10:00:00'),
(13, 5, 4, '2025-10-10', '11:00:00', 'scheduled', '2025-10-09 10:00:00');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admins`
--
ALTER TABLE `admins`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- Indexes for table `coach_availability`
--
ALTER TABLE `coach_availability`
  ADD PRIMARY KEY (`id`),
  ADD KEY `employee_id` (`employee_id`);

--
-- Indexes for table `duty_roster`
--
ALTER TABLE `duty_roster`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `employees`
--
ALTER TABLE `employees`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `members`
--
ALTER TABLE `members`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `membership_plans`
--
ALTER TABLE `membership_plans`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `member_attendance`
--
ALTER TABLE `member_attendance`
  ADD PRIMARY KEY (`id`),
  ADD KEY `member_id` (`member_id`);

--
-- Indexes for table `member_progress`
--
ALTER TABLE `member_progress`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `member_subscriptions`
--
ALTER TABLE `member_subscriptions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `member_id` (`member_id`),
  ADD KEY `plan_id` (`plan_id`);

--
-- Indexes for table `notifications`
--
ALTER TABLE `notifications`
  ADD PRIMARY KEY (`id`),
  ADD KEY `employee_id` (`employee_id`),
  ADD KEY `member_id` (`member_id`);

--
-- Indexes for table `roster_assignments`
--
ALTER TABLE `roster_assignments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `employee_id` (`employee_id`);

--
-- Indexes for table `schedules`
--
ALTER TABLE `schedules`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_employee` (`employee_id`);

--
-- Indexes for table `trainer_assignments`
--
ALTER TABLE `trainer_assignments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `trainer_id` (`trainer_id`);

--
-- Indexes for table `training_sessions`
--
ALTER TABLE `training_sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `member_id` (`member_id`),
  ADD KEY `trainer_id` (`trainer_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admins`
--
ALTER TABLE `admins`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `coach_availability`
--
ALTER TABLE `coach_availability`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=112;

--
-- AUTO_INCREMENT for table `duty_roster`
--
ALTER TABLE `duty_roster`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `employees`
--
ALTER TABLE `employees`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `members`
--
ALTER TABLE `members`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=43;

--
-- AUTO_INCREMENT for table `membership_plans`
--
ALTER TABLE `membership_plans`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `member_attendance`
--
ALTER TABLE `member_attendance`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `member_progress`
--
ALTER TABLE `member_progress`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `member_subscriptions`
--
ALTER TABLE `member_subscriptions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=42;

--
-- AUTO_INCREMENT for table `notifications`
--
ALTER TABLE `notifications`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=49;

--
-- AUTO_INCREMENT for table `roster_assignments`
--
ALTER TABLE `roster_assignments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `schedules`
--
ALTER TABLE `schedules`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `trainer_assignments`
--
ALTER TABLE `trainer_assignments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `training_sessions`
--
ALTER TABLE `training_sessions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `coach_availability`
--
ALTER TABLE `coach_availability`
  ADD CONSTRAINT `coach_availability_ibfk_1` FOREIGN KEY (`employee_id`) REFERENCES `employees` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `member_attendance`
--
ALTER TABLE `member_attendance`
  ADD CONSTRAINT `member_attendance_ibfk_1` FOREIGN KEY (`member_id`) REFERENCES `members` (`id`);

--
-- Constraints for table `member_subscriptions`
--
ALTER TABLE `member_subscriptions`
  ADD CONSTRAINT `member_subscriptions_ibfk_1` FOREIGN KEY (`member_id`) REFERENCES `members` (`id`),
  ADD CONSTRAINT `member_subscriptions_ibfk_2` FOREIGN KEY (`plan_id`) REFERENCES `membership_plans` (`id`);

--
-- Constraints for table `notifications`
--
ALTER TABLE `notifications`
  ADD CONSTRAINT `notifications_ibfk_1` FOREIGN KEY (`employee_id`) REFERENCES `employees` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `notifications_ibfk_2` FOREIGN KEY (`member_id`) REFERENCES `members` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `roster_assignments`
--
ALTER TABLE `roster_assignments`
  ADD CONSTRAINT `roster_assignments_ibfk_1` FOREIGN KEY (`employee_id`) REFERENCES `employees` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `schedules`
--
ALTER TABLE `schedules`
  ADD CONSTRAINT `fk_employee` FOREIGN KEY (`employee_id`) REFERENCES `employees` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `trainer_assignments`
--
ALTER TABLE `trainer_assignments`
  ADD CONSTRAINT `trainer_assignments_ibfk_1` FOREIGN KEY (`trainer_id`) REFERENCES `employees` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `training_sessions`
--
ALTER TABLE `training_sessions`
  ADD CONSTRAINT `training_sessions_ibfk_1` FOREIGN KEY (`member_id`) REFERENCES `members` (`id`),
  ADD CONSTRAINT `training_sessions_ibfk_2` FOREIGN KEY (`trainer_id`) REFERENCES `employees` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
