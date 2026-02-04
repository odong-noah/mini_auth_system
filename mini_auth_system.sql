-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Feb 04, 2026 at 10:27 PM
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
-- Database: `mini_auth_system`
--

-- --------------------------------------------------------

--
-- Table structure for table `mini_auth_users`
--

CREATE TABLE `mini_auth_users` (
  `mini_auth_id` int(11) NOT NULL,
  `mini_auth_username` varchar(50) NOT NULL,
  `mini_auth_email` varchar(100) NOT NULL,
  `mini_auth_password` varchar(255) NOT NULL,
  `mini_auth_role` varchar(20) NOT NULL DEFAULT 'user',
  `mini_auth_is_active` tinyint(1) NOT NULL DEFAULT 1,
  `mini_auth_created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `mini_auth_users`
--

INSERT INTO `mini_auth_users` (`mini_auth_id`, `mini_auth_username`, `mini_auth_email`, `mini_auth_password`, `mini_auth_role`, `mini_auth_is_active`, `mini_auth_created_at`) VALUES
(1, 'alice', 'alice@gmail.com', '$2y$10$/US97zdl3g21bh4trihWgOQ3IvvusRKN6Oa9y6UBXwEfW2vKJlE3G', 'user', 1, '2026-02-04 20:44:19'),
(2, 'Whitney', 'whitney@gmail.com', '$2y$10$VtP44b2Xh.1TdxvK9p4URO/5Qy6KsYYDmw0eiLK7UQ6iy5Rr4/2NK', 'admin', 1, '2026-02-04 20:45:47');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `mini_auth_users`
--
ALTER TABLE `mini_auth_users`
  ADD PRIMARY KEY (`mini_auth_id`),
  ADD UNIQUE KEY `mini_auth_username` (`mini_auth_username`),
  ADD UNIQUE KEY `mini_auth_email` (`mini_auth_email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `mini_auth_users`
--
ALTER TABLE `mini_auth_users`
  MODIFY `mini_auth_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
