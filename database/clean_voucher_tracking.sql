-- phpMyAdmin SQL Dump
-- version 4.7.9
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Aug 26, 2019 at 03:20 PM
-- Server version: 10.1.31-MariaDB
-- PHP Version: 7.2.3

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `voucher_tracking`
--

-- --------------------------------------------------------

--
-- Table structure for table `action_log`
--

CREATE TABLE `action_log` (
  `id` int(11) NOT NULL,
  `action` varchar(255) NOT NULL COMMENT 'update.insert/delete',
  `summary` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `created_by` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `customer`
--

CREATE TABLE `customer` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `ic` varchar(25) NOT NULL,
  `status` int(11) NOT NULL DEFAULT '1',
  `accumulated_amt` double DEFAULT '0',
  `used_amt` double DEFAULT '0',
  `remaining_amt` double DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `customer_package`
--

CREATE TABLE `customer_package` (
  `id` int(11) NOT NULL,
  `ic` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL COMMENT 'no important',
  `package_amt` double NOT NULL,
  `package_id` int(11) NOT NULL,
  `expiry_date` date DEFAULT NULL,
  `cancel` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `created_by` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `lists_branch`
--

CREATE TABLE `lists_branch` (
  `id` int(11) NOT NULL,
  `code` varchar(25) NOT NULL,
  `name` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `lists_branch`
--

INSERT INTO `lists_branch` (`id`, `code`, `name`) VALUES
(1, 'JHR', 'Johor Bharu'),
(2, 'KDH', 'Kedah'),
(3, 'KTN', 'Kelantan'),
(4, 'MLK', 'Melacca'),
(5, 'NSN', 'Negeri Sembilan'),
(6, 'PHG', 'Pahang'),
(7, 'PNG', 'Penang'),
(8, 'PRK', 'Perak'),
(9, 'PLS', 'Perlis'),
(10, 'SBH', 'Sabah'),
(11, 'SWK', 'Sarawak'),
(12, 'SGR', 'Selangor'),
(13, 'TRG', 'Terengganu');

-- --------------------------------------------------------

--
-- Table structure for table `lists_package`
--

CREATE TABLE `lists_package` (
  `id` int(11) NOT NULL,
  `package_name` varchar(255) NOT NULL,
  `price` double NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `lists_package`
--

INSERT INTO `lists_package` (`id`, `package_name`, `price`) VALUES
(1, 'Package A', 2500),
(2, 'Package B', 5000),
(3, 'Package C', 7500),
(4, 'Package D', 10000),
(5, 'Package E', 12500),
(6, 'Package F', 15000),
(7, 'Package G', 17500),
(8, 'Package H', 20000),
(9, 'Package I', 22500),
(10, 'Package J', 25000);

-- --------------------------------------------------------

--
-- Table structure for table `lists_role`
--

CREATE TABLE `lists_role` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `lists_role`
--

INSERT INTO `lists_role` (`id`, `name`, `description`) VALUES
(1, 'Admin', 'Basic Function'),
(99, 'Master Admin', 'Can add other admin');

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '2014_10_12_000000_create_users_table', 1),
(2, '2014_10_12_100000_create_password_resets_table', 1);

-- --------------------------------------------------------

--
-- Table structure for table `password_resets`
--

CREATE TABLE `password_resets` (
  `email` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `payment`
--

CREATE TABLE `payment` (
  `id` int(11) NOT NULL,
  `ic` varchar(255) NOT NULL,
  `invoice_no` varchar(255) NOT NULL,
  `invoice_date` date NOT NULL,
  `amount` double NOT NULL,
  `cancel` int(11) NOT NULL DEFAULT '0',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `created_by` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `username` varchar(25) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` int(11) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `username`, `email`, `email_verified_at`, `password`, `remember_token`, `status`, `created_at`, `updated_at`) VALUES
(1, 'admin', 'admin', 'admin', NULL, '$2y$10$O2nz8KXNpyPshjPDZfVGBe4bdYm9QwqAzjp9xsajxgcPDDmqQ2322', 'iPInfrmDTx6dGMK7H1hiybi2rLnGg9Os5jdUOXmYoT73qfCYXMVi85KGeh2x', 1, '2019-08-22 00:00:21', '2019-08-22 00:00:21');

-- --------------------------------------------------------

--
-- Table structure for table `user_admin`
--

CREATE TABLE `user_admin` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `role` varchar(25) NOT NULL,
  `branch` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `user_admin`
--

INSERT INTO `user_admin` (`id`, `user_id`, `role`, `branch`) VALUES
(1, 1, '99', 'PNG');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `action_log`
--
ALTER TABLE `action_log`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `customer`
--
ALTER TABLE `customer`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `customer_package`
--
ALTER TABLE `customer_package`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `lists_branch`
--
ALTER TABLE `lists_branch`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `lists_package`
--
ALTER TABLE `lists_package`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `lists_role`
--
ALTER TABLE `lists_role`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `password_resets`
--
ALTER TABLE `password_resets`
  ADD KEY `password_resets_email_index` (`email`);

--
-- Indexes for table `payment`
--
ALTER TABLE `payment`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`);

--
-- Indexes for table `user_admin`
--
ALTER TABLE `user_admin`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `action_log`
--
ALTER TABLE `action_log`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `customer`
--
ALTER TABLE `customer`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `customer_package`
--
ALTER TABLE `customer_package`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `lists_branch`
--
ALTER TABLE `lists_branch`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `lists_package`
--
ALTER TABLE `lists_package`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `lists_role`
--
ALTER TABLE `lists_role`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=100;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `payment`
--
ALTER TABLE `payment`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `user_admin`
--
ALTER TABLE `user_admin`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
