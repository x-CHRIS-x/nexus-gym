-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Oct 09, 2025 at 08:35 PM
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
(1, 10, 36, '2025-10-16', 'booked', '2025-10-09 12:47:50'),
(2, 18, 36, '2025-10-12', 'booked', '2025-10-09 12:50:57'),
(3, 19, 36, '2025-10-12', 'booked', '2025-10-09 12:51:02');

-- --------------------------------------------------------

--
-- Table structure for table `employees`
--

CREATE TABLE `employees` (
  `id` int(11) NOT NULL,
  `first_name` varchar(50) NOT NULL,
  `last_name` varchar(50) DEFAULT NULL,
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
(1, 'John Chris', 'Ledama', 'johnchrisledama83@gmail.com', '$2y$10$WnRSFnIdoN7DMAp6iEUAKeOvJ1HW32SAzHUQoNn0e3F6rC1JlDJnG', '09612099217', 'Trainer', '2024-08-30', 'Active'),
(2, 'employee', '1', 'employee1@gmail.com', '$2y$10$dHguEVgWSKZqVDrn7rES3.Jvqs0PfFoRSPmJ.fGllJ134Lf7euLCe', '1234567890', 'Trainer', '2024-05-20', 'Active'),
(3, 'Charles Selwyn', 'Lim', 'charles@gmail.com', '$2y$10$CI/jTlQSgXSTJrKuJJtLiuxV8eZMdO0w0PH.pASap9pp9hhdWQuc2', '049494994', 'Trainer', '2025-09-04', 'Active'),
(4, 'Coach Gian', 'Crispo', 'gian@gmail.com', '$2y$10$3fueGAeWyLQX3xjIsrjDDuC2mwrekHnBeGiqwzJ1qjVasMjRO6ADq', '094484884', 'Coach', '2025-09-05', 'Active'),
(5, 'Coach Charles Selwyn', 'Lim', 'lim4@gmail.com', '$2y$10$j9exL2c5ypzsrNS9j0mZ1uAV89YW/g/59opw1TX9VJ9DSiF66A88O', '09494994', 'Trainer', '2025-09-02', 'Active'),
(6, 'Coach Chris', 'Ledama', 'coachchris@gmail.com', '$2y$10$6ToC.vdhgWvoPCw0beCBA.Cglt6DUQD0OejnuQx8AJGu0V/mj2RCW', '09292929', 'Trainer', '2025-09-11', 'Active'),
(7, 'Coach Jorem', 'Legaspi', 'jorem@gmail.com', '$2y$10$D5wb0rBaxDBjwLm51iRCwO7uvMQFa.wnpjHyrjdad4SgXcHV90vJK', '095959', 'Coach', '2025-09-17', 'Active'),
(9, 'employee', '2', 'employee2@gmail.com', '$2y$10$B7G.fzBWLQGKK.eO2Ad4.e9nfx.Xjo.m912tZXIJSM3Fpj/ZY6jTi', '12321731', 'Trainer', '2022-10-22', 'Active');

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
  `last_name` varchar(50) DEFAULT NULL,
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
(1, 'John Chris', 'Ledama', 'chris.l@nexusgym.com', '$2y$10$WnRSFnIdoN7DMAp6iEUAKeOvJ1HW32SAzHUQoNn0e3F6rC1JlDJnG', '09111222333', 'Premium', 'Active', '2025-01-01', '2026-01-01'),
(2, 'Cris John M.', 'Resonable', 'crisjohn.r@nexusgym.com', '$2y$10$OZKAqxruR/BsB/iYyfQzMOAdGcduV5o/fbqx2FVKBTrPuIV3yxNOm', '09222333444', 'Standard', 'Active', '2025-02-01', '2025-11-01'),
(3, 'Gian', 'Opsirc', 'gian.o@nexusgym.com', '$2y$10$WnRSFnIdoN7DMAp6iEUAKeOvJ1HW32SAzHUQoNn0e3F6rC1JlDJnG', '09333444555', 'Premium', 'Active', '2025-03-01', '2026-03-01'),
(4, 'John Rique', 'Barnachea', 'johnrique.b@nexusgym.com', '$2y$10$gre0yKHE57bkT2FgBWRRSu5DcK4mcvo1j.pdI1GZfee/cC.QTo6k2', '09444555666', 'Standard', 'Expired', '2025-04-01', '2025-10-01'),
(5, 'Charles Selwyn', 'Lim', 'charles.l@nexusgym.com', '$2y$10$WnRSFnIdoN7DMAp6iEUAKeOvJ1HW32SAzHUQoNn0e3F6rC1JlDJnG', '09555666777', 'Premium', 'Active', '2025-05-01', '2026-05-01'),
(6, 'Marc Jorem Caadan', 'Luchavez', 'marcjorem.l@nexusgym.com', '$2y$10$WnRSFnIdoN7DMAp6iEUAKeOvJ1HW32SAzHUQoNn0e3F6rC1JlDJnG', '09666777888', 'Standard', 'Active', '2025-06-01', '2025-12-01'),
(36, 'member', '1', 'member1@gmail.com', '$2y$10$lwqp3Yx7Mc0pf9btMw4umel74Qmgx0NkkS6vInjgI7bjb0uy.6Kau', '12973612', 'Standard', 'Active', '2024-10-10', '2026-10-09'),
(37, 'member', '2', 'member2@gmail.com', '$2y$10$WvpCwEzt7VD0EiVJ7jfHH.2jJAu7NI.iYDM99zFm8iY6KjbJZflNu', '123912739812', 'Standard', 'Active', '2024-10-10', '2026-10-09');

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
(5, 5, 4, '2025-05-01', '2026-05-01', 'active', 'paid', 500.00, '2025-05-01 09:00:00'),
(37, 36, 4, '2025-10-09', '2026-10-09', 'active', 'paid', 3800.00, '2025-10-09 11:31:16'),
(38, 37, 4, '2025-10-09', '2026-10-09', 'active', 'paid', 3800.00, '2025-10-09 14:01:39');

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
(44, 4, 36, 'hired', '2025-10-09 11:33:11'),
(45, 5, 36, 'hired', '2025-10-09 11:33:15'),
(46, 4, 36, 'New booking for Morning Stretch', '2025-10-09 12:47:50'),
(47, 6, 36, 'New booking for Sunday Yoga', '2025-10-09 12:50:57'),
(48, 1, 36, 'New booking for Full Body Workout', '2025-10-09 12:51:02');

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
(13, 5, 4, '2025-10-10', '11:00:00', 'scheduled', '2025-10-09 10:00:00'),
(14, 36, 4, '2025-10-11', '10:00:00', 'scheduled', '2025-10-09 11:33:11'),
(15, 36, 5, '2025-10-17', '10:00:00', 'scheduled', '2025-10-09 11:33:15');

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
-- Indexes for table `class_bookings`
--
ALTER TABLE `class_bookings`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_booking` (`class_id`,`member_id`,`booking_date`),
  ADD KEY `member_id` (`member_id`);

--
-- Indexes for table `employees`
--
ALTER TABLE `employees`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `fitness_classes`
--
ALTER TABLE `fitness_classes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `trainer_id` (`trainer_id`);

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
-- AUTO_INCREMENT for table `class_bookings`
--
ALTER TABLE `class_bookings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `employees`
--
ALTER TABLE `employees`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `fitness_classes`
--
ALTER TABLE `fitness_classes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT for table `members`
--
ALTER TABLE `members`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=38;

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=39;

--
-- AUTO_INCREMENT for table `notifications`
--
ALTER TABLE `notifications`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=49;

--
-- AUTO_INCREMENT for table `schedules`
--
ALTER TABLE `schedules`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `training_sessions`
--
ALTER TABLE `training_sessions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `class_bookings`
--
ALTER TABLE `class_bookings`
  ADD CONSTRAINT `class_bookings_ibfk_1` FOREIGN KEY (`class_id`) REFERENCES `fitness_classes` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `class_bookings_ibfk_2` FOREIGN KEY (`member_id`) REFERENCES `members` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `fitness_classes`
--
ALTER TABLE `fitness_classes`
  ADD CONSTRAINT `fitness_classes_ibfk_1` FOREIGN KEY (`trainer_id`) REFERENCES `employees` (`id`) ON DELETE CASCADE;

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
-- Constraints for table `training_sessions`
--
ALTER TABLE `training_sessions`
  ADD CONSTRAINT `training_sessions_ibfk_1` FOREIGN KEY (`member_id`) REFERENCES `members` (`id`),
  ADD CONSTRAINT `training_sessions_ibfk_2` FOREIGN KEY (`trainer_id`) REFERENCES `employees` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
