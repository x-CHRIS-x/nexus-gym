-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Sep 27, 2025 at 04:34 PM
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
(1, 'ADMIN', '$2y$10$2UxIjQ7rsWsksF/UQ39sDOGA.UUAztAqeRjELxfCO6nqyKduMuutS', 'admin@gmail.com');

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
(27, 1, 'Monday', '7am-5pm'),
(40, 1, 'Monday', '7-5pm'),
(41, 1, 'Monday', '7-5pm'),
(42, 1, 'Monday', '7-5pm'),
(69, 5, 'Any Day', 'Any Time');

-- --------------------------------------------------------

--
-- Table structure for table `employees`
--

CREATE TABLE `employees` (
  `id` int(11) NOT NULL,
  `full_name` varchar(100) NOT NULL,
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

INSERT INTO `employees` (`id`, `full_name`, `email`, `password`, `phone`, `position`, `date_hired`, `status`) VALUES
(1, 'John Chris P. Ledama', 'johnchrisledama83@gmail.com', '$2y$10$WnRSFnIdoN7DMAp6iEUAKeOvJ1HW32SAzHUQoNn0e3F6rC1JlDJnG', '09612099217', 'Trainer', '2024-08-30', 'Active'),
(2, 'employee2', 'employee@gmail.com', '$2y$10$UHhdOMXwWyVANifp6DHMq.LiPP1hXhhA4QdWT.O065ho0hzTfuEM2', '1234567890', 'Trainer', '2024-05-20', 'Active'),
(3, 'Charles Selwyn Lim', 'charles@gmail.com', '$2y$10$CI/jTlQSgXSTJrKuJJtLiuxV8eZMdO0w0PH.pASap9pp9hhdWQuc2', '049494994', 'Trainer', '2025-09-04', 'Active'),
(4, 'Coach Gian', 'gian@gmail.com', '$2y$10$3fueGAeWyLQX3xjIsrjDDuC2mwrekHnBeGiqwzJ1qjVasMjRO6ADq', '094484884', 'Coach', '2025-09-05', 'Active'),
(5, 'Coach Lim', 'lim4@gmail.com', '$2y$10$j9exL2c5ypzsrNS9j0mZ1uAV89YW/g/59opw1TX9VJ9DSiF66A88O', '09494994', 'Trainer', '2025-09-02', 'Active'),
(6, 'Coach Chris', 'coachchris@gmail.com', '$2y$10$6ToC.vdhgWvoPCw0beCBA.Cglt6DUQD0OejnuQx8AJGu0V/mj2RCW', '09292929', 'Trainer', '2025-09-11', 'Active'),
(7, 'Coach Jorem', 'jorem@gmail.com', '$2y$10$D5wb0rBaxDBjwLm51iRCwO7uvMQFa.wnpjHyrjdad4SgXcHV90vJK', '095959', 'Coach', '2025-09-17', 'Active');

-- --------------------------------------------------------

--
-- Table structure for table `members`
--

CREATE TABLE `members` (
  `id` int(11) NOT NULL,
  `full_name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `membership_type` varchar(20) DEFAULT NULL,
  `status` varchar(20) DEFAULT NULL,
  `join_date` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `members`
--

INSERT INTO `members` (`id`, `full_name`, `email`, `password`, `phone`, `membership_type`, `status`, `join_date`) VALUES
(1, 'gian-crispo', 'gian-crispo@gmail.com', '$2y$10$V5aKTGFwH9uQg6XQI7q1wuDv.cDHcnNhPOAOb78jQZvY2oYrf6POW', '09612099217', 'Premium', 'Active', '2025-09-12'),
(2, 'gngn', 'gngn@gmail.com', '$2y$10$hd2pGFc8lZt7KN/d.FW0PuGoukEsr1p9aQ3/gPJS9Kgp6g9hRdjdG', '1234', 'Standard', 'Active', '2000-08-30'),
(3, 'member', 'member@gmail.com', '$2y$10$a9B8AWeUmZl7JCupgd8Hl...GruTveiycVjN/aoCH28eBFro7f.iO', '1234567890', 'Premium', 'Active', '0000-00-00'),
(4, 'selwyn', 'selwyn@gmail.com', '$2y$10$5Ae3YGyQ3qdhggXl1ve1O.sgFS4HtkvgHt5lK0bH7bBMd9rkIpy2C', '0929292992', 'Premium', 'Active', '0000-00-00'),
(5, 'Coach Jorem', 'jorem@gmail.com', '$2y$10$XnAduvAyAIwbq9qUFfjPzeMZ0rhnb/XBEvUIxceZ9TWSVSMKo7v/.', '094484848', 'Standard', 'Active', '2025-09-17'),
(6, 'roy', 'roy@gmail.com', '$2y$10$.X8i.559RrZ1tSBYt7/zAeoJBx5MpYU6T5TX8ln.YCZVFdT2Ry6cK', '09494949', 'Standard', 'Active', '0000-00-00'),
(7, 'test', 'test@gmail.com', '$2y$10$zf/nzE9qmUNj3fe8Jg9/2.A0l0AEdpvFz8vpO1WDk7NOGGldOcLLG', '1234', 'Standard', 'Active', '0000-00-00');

-- --------------------------------------------------------

--
-- Table structure for table `member_progress`
--

CREATE TABLE `member_progress` (
  `id` int(11) NOT NULL,
  `member_id` int(11) NOT NULL,
  `bench_press` varchar(50) DEFAULT '',
  `incline_press` varchar(50) DEFAULT '',
  `decline_press` varchar(50) DEFAULT '',
  `chest_fly` varchar(50) DEFAULT '',
  `overhead_press` varchar(50) DEFAULT '',
  `lateral_raises` varchar(50) DEFAULT '',
  `deadlift` decimal(6,2) DEFAULT 0.00,
  `lat_pulldown` decimal(6,2) DEFAULT 0.00,
  `weight_now` decimal(6,2) DEFAULT 0.00,
  `weight_before` decimal(6,2) DEFAULT 0.00,
  `squat` varchar(50) DEFAULT '',
  `leg_press` varchar(50) DEFAULT '',
  `romanian_deadlift` varchar(50) DEFAULT '',
  `rdl` varchar(50) DEFAULT '',
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `member_progress`
--

INSERT INTO `member_progress` (`id`, `member_id`, `bench_press`, `incline_press`, `decline_press`, `chest_fly`, `overhead_press`, `lateral_raises`, `deadlift`, `lat_pulldown`, `weight_now`, `weight_before`, `squat`, `leg_press`, `romanian_deadlift`, `rdl`, `updated_at`) VALUES
(1, 4, '100', '100', '100', '100', '100', '100', 100.00, 100.00, 100.00, 100.00, '100', '100', '100', '100', '2025-09-19 19:47:25');

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
(7, 6, 1, 'hired', '2025-09-16 21:07:54'),
(8, 4, 1, 'hired', '2025-09-16 21:13:32'),
(9, 6, 1, 'hired', '2025-09-16 21:15:49'),
(10, 6, 1, 'hired', '2025-09-17 04:06:07');

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
(1, 4, 'Monday'),
(2, 4, 'Monday');

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
-- Indexes for table `member_progress`
--
ALTER TABLE `member_progress`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_member` (`member_id`);

--
-- Indexes for table `notifications`
--
ALTER TABLE `notifications`
  ADD PRIMARY KEY (`id`),
  ADD KEY `employee_id` (`employee_id`),
  ADD KEY `member_id` (`member_id`);

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
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admins`
--
ALTER TABLE `admins`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `coach_availability`
--
ALTER TABLE `coach_availability`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=70;

--
-- AUTO_INCREMENT for table `employees`
--
ALTER TABLE `employees`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `members`
--
ALTER TABLE `members`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `member_progress`
--
ALTER TABLE `member_progress`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `notifications`
--
ALTER TABLE `notifications`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `schedules`
--
ALTER TABLE `schedules`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `trainer_assignments`
--
ALTER TABLE `trainer_assignments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `coach_availability`
--
ALTER TABLE `coach_availability`
  ADD CONSTRAINT `coach_availability_ibfk_1` FOREIGN KEY (`employee_id`) REFERENCES `employees` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `member_progress`
--
ALTER TABLE `member_progress`
  ADD CONSTRAINT `fk_member` FOREIGN KEY (`member_id`) REFERENCES `members` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `notifications`
--
ALTER TABLE `notifications`
  ADD CONSTRAINT `notifications_ibfk_1` FOREIGN KEY (`employee_id`) REFERENCES `employees` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `notifications_ibfk_2` FOREIGN KEY (`member_id`) REFERENCES `members` (`id`) ON DELETE CASCADE;

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
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
