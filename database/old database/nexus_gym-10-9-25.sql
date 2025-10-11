-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Oct 09, 2025 at 01:11 PM
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
(81, 1, 'Monday', '6:00 AM - 2:00 PM'),
(82, 1, 'Tuesday', '10:00 AM - 6:00 PM'),
(83, 1, 'Thursday', '2:00 PM - 10:00 PM'),
(84, 1, 'Friday', '6:00 AM - 2:00 PM'),
(85, 2, 'Monday', '2:00 PM - 10:00 PM'),
(86, 2, 'Wednesday', '6:00 AM - 2:00 PM'),
(87, 2, 'Thursday', '10:00 AM - 6:00 PM'),
(88, 2, 'Saturday', '8:00 AM - 4:00 PM'),
(89, 3, 'Tuesday', '2:00 PM - 10:00 PM'),
(90, 3, 'Wednesday', '10:00 AM - 6:00 PM'),
(91, 3, 'Friday', '2:00 PM - 10:00 PM'),
(92, 3, 'Sunday', '8:00 AM - 4:00 PM'),
(93, 4, 'Monday', '10:00 AM - 6:00 PM'),
(94, 4, 'Wednesday', '2:00 PM - 10:00 PM'),
(95, 4, 'Saturday', '6:00 AM - 2:00 PM'),
(96, 4, 'Sunday', '2:00 PM - 10:00 PM'),
(97, 5, 'Tuesday', '6:00 AM - 2:00 PM'),
(98, 5, 'Thursday', '6:00 AM - 2:00 PM'),
(99, 5, 'Friday', '10:00 AM - 6:00 PM'),
(100, 5, 'Saturday', '2:00 PM - 10:00 PM'),
(101, 6, 'Monday', '6:00 AM - 2:00 PM'),
(102, 6, 'Wednesday', '2:00 PM - 10:00 PM'),
(103, 6, 'Friday', '10:00 AM - 6:00 PM'),
(104, 6, 'Sunday', '8:00 AM - 4:00 PM');

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
(6, 'Marc Jorem', 'Luchavez', 'marcjorem.l@nexusgym.com', '$2y$10$WnRSFnIdoN7DMAp6iEUAKeOvJ1HW32SAzHUQoNn0e3F6rC1JlDJnG', '09678901234', 'Senior Coach', '2025-04-01', 'Active');

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
(4, 'Ana', 'Lopez', 'ana.l@email.com', '$2y$10$WnRSFnIdoN7DMAp6iEUAKeOvJ1HW32SAzHUQoNn0e3F6rC1JlDJnG', '09444555666', 'Standard', 'Active', '2025-04-01', '2025-10-01'),
(5, 'Miguel', 'Garcia', 'miguel.g@email.com', '$2y$10$WnRSFnIdoN7DMAp6iEUAKeOvJ1HW32SAzHUQoNn0e3F6rC1JlDJnG', '09555666777', 'Premium', 'Active', '2025-05-01', '2026-05-01');

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
(2, 1, '185 lbs', '135 lbs', '155 lbs', '30 lbs', '95 lbs', '20 lbs', 225.00, 160.00, 75.00, 78.00, '225 lbs', '400 lbs', '185 lbs', '185 lbs', '2025-10-09 08:00:00'),
(3, 2, '95 lbs', '75 lbs', '85 lbs', '15 lbs', '45 lbs', '10 lbs', 135.00, 100.00, 55.00, 58.00, '135 lbs', '250 lbs', '115 lbs', '115 lbs', '2025-10-09 08:00:00'),
(4, 3, '225 lbs', '185 lbs', '205 lbs', '40 lbs', '135 lbs', '25 lbs', 315.00, 200.00, 85.00, 88.00, '315 lbs', '500 lbs', '275 lbs', '275 lbs', '2025-10-09 08:00:00'),
(5, 4, '85 lbs', '65 lbs', '75 lbs', '12 lbs', '35 lbs', '8 lbs', 115.00, 80.00, 52.00, 55.00, '125 lbs', '225 lbs', '95 lbs', '95 lbs', '2025-10-09 08:00:00'),
(6, 5, '205 lbs', '165 lbs', '185 lbs', '35 lbs', '115 lbs', '22 lbs', 275.00, 180.00, 82.00, 85.00, '275 lbs', '450 lbs', '225 lbs', '225 lbs', '2025-10-09 08:00:00');

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
(5, 5, 4, '2025-05-01', '2026-05-01', 'active', 'paid', 500.00, '2025-05-01 09:00:00');

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
(43, 4, 5, 'Your flexibility has improved significantly this month', '2025-10-09 18:00:00');

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
(1, 1, '2025-10-09', '6:00 AM - 2:00 PM', 'Head Coach - General Training'),
(2, 2, '2025-10-09', '2:00 PM - 10:00 PM', 'Fitness Coach - Cardio Classes'),
(3, 3, '2025-10-09', '10:00 AM - 6:00 PM', 'Strength Coach - Weight Training'),
(4, 4, '2025-10-09', '2:00 PM - 10:00 PM', 'Yoga Coach - Evening Classes'),
(5, 5, '2025-10-09', '6:00 AM - 2:00 PM', 'CrossFit Coach - Morning WOD'),
(6, 6, '2025-10-09', '10:00 AM - 6:00 PM', 'Senior Coach - Personal Training'),
(7, 1, '2025-10-10', '10:00 AM - 6:00 PM', 'Head Coach - Staff Training'),
(8, 2, '2025-10-10', '6:00 AM - 2:00 PM', 'Fitness Coach - Morning Classes'),
(9, 3, '2025-10-10', '2:00 PM - 10:00 PM', 'Strength Coach - Evening Sessions'),
(10, 4, '2025-10-10', '10:00 AM - 6:00 PM', 'Yoga Coach - Midday Classes'),
(11, 5, '2025-10-10', '6:00 AM - 2:00 PM', 'CrossFit Coach - Group Training'),
(12, 6, '2025-10-10', '2:00 PM - 10:00 PM', 'Senior Coach - Advanced Training');

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
-- Indexes for table `member_progress`
--
ALTER TABLE `member_progress`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_member` (`member_id`);

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=105;

--
-- AUTO_INCREMENT for table `employees`
--
ALTER TABLE `employees`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `members`
--
ALTER TABLE `members`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=36;

--
-- AUTO_INCREMENT for table `membership_plans`
--
ALTER TABLE `membership_plans`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `member_progress`
--
ALTER TABLE `member_progress`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `member_subscriptions`
--
ALTER TABLE `member_subscriptions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=37;

--
-- AUTO_INCREMENT for table `notifications`
--
ALTER TABLE `notifications`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=44;

--
-- AUTO_INCREMENT for table `schedules`
--
ALTER TABLE `schedules`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

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
-- Constraints for table `member_progress`
--
ALTER TABLE `member_progress`
  ADD CONSTRAINT `fk_member` FOREIGN KEY (`member_id`) REFERENCES `members` (`id`) ON DELETE CASCADE;

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
