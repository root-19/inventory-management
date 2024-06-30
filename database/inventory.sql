-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jun 30, 2024 at 03:32 PM
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
-- Database: `inventory`
--

-- --------------------------------------------------------

--
-- Table structure for table `add_product`
--

CREATE TABLE `add_product` (
  `id` int(11) NOT NULL,
  `name` varchar(250) NOT NULL,
  `category` varchar(250) NOT NULL,
  `image` varchar(250) NOT NULL,
  `quantity` int(11) NOT NULL,
  `description` text NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `expiration` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `original_quantity` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `add_product`
--

INSERT INTO `add_product` (`id`, `name`, `category`, `image`, `quantity`, `description`, `price`, `expiration`, `original_quantity`) VALUES
(46, 'wasie', 'vegetable', 'uploads/Screenshot 2024-03-05 234927.png', 100, 'ffssf', 20.00, '2024-06-28 16:00:00', 100),
(47, 'wasie', 'vegetable', 'uploads/Screenshot 2024-03-05 234927.png', 100, 'ffssf', 20.00, '2024-06-28 16:00:00', 100),
(48, 'wash', 'vegetable', 'uploads/ss.png', 98, 'fasf', 20.00, '2024-06-30 13:27:11', 100);

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

CREATE TABLE `admin` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(100) NOT NULL,
  `type` enum('admin','staff') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admin`
--

INSERT INTO `admin` (`id`, `name`, `email`, `password`, `type`) VALUES
(1, 'staff', 'staff@gmail.com', 'staff', 'staff'),
(2, 'admin', 'admin@gmail.com', 'admin', 'admin');

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `id` int(11) NOT NULL,
  `name` varchar(250) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`id`, `name`) VALUES
(1, 'vegetable'),
(2, 'drinks');

-- --------------------------------------------------------

--
-- Table structure for table `product_reports`
--

CREATE TABLE `product_reports` (
  `id` int(11) NOT NULL,
  `product_name` varchar(250) NOT NULL,
  `message` text NOT NULL,
  `report_date` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `product_reports`
--

INSERT INTO `product_reports` (`id`, `product_name`, `message`, `report_date`) VALUES
(6, 'hello', 'thsi last last test', '2024-06-23 15:29:55'),
(9, 'this a test', 'this a test', '2024-06-25 12:46:30'),
(10, 'this a test', 'test', '2024-06-25 12:50:33'),
(11, 'this a test', 'test', '2024-06-25 12:55:46');

-- --------------------------------------------------------

--
-- Table structure for table `sold_products`
--

CREATE TABLE `sold_products` (
  `id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `total_price` decimal(10,2) NOT NULL,
  `sale_date` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `quantity_sold` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `sold_products`
--

INSERT INTO `sold_products` (`id`, `product_id`, `total_price`, `sale_date`, `quantity_sold`) VALUES
(4, 36, 11.00, '2024-06-23 10:40:31', 1),
(5, 36, 44.00, '2024-06-23 10:41:12', 4),
(6, 36, 44.00, '2024-06-23 10:41:44', 4),
(7, 36, 22.00, '2024-06-25 12:23:00', 2),
(8, 36, 22.00, '2024-06-25 12:23:37', 2),
(9, 36, 0.00, '2024-06-25 12:34:19', 0),
(10, 23, 88.00, '2024-06-25 12:34:26', 2),
(11, 23, 88.00, '2024-06-25 12:34:44', 2),
(12, 24, 132.00, '2024-06-25 12:34:48', 2),
(13, 24, 198.00, '2024-06-25 12:34:53', 3),
(14, 36, 33.00, '2024-06-25 12:35:00', 3),
(15, 36, 55.00, '2024-06-25 12:35:07', 5),
(16, 36, 55.00, '2024-06-25 12:37:32', 5),
(17, 36, 22.00, '2024-06-25 12:37:37', 2),
(18, 37, 200.00, '2024-06-25 15:06:43', 2),
(19, 37, 200.00, '2024-06-25 15:06:56', 2),
(20, 37, 200.00, '2024-06-25 17:02:23', 2),
(21, 38, 7500.00, '2024-06-25 17:02:39', 75),
(22, 37, 7500.00, '2024-06-25 17:04:54', 75),
(23, 40, 7500.00, '2024-06-25 17:07:43', 75),
(24, 37, 200.00, '2024-06-26 13:04:23', 2),
(25, 37, 200.00, '2024-06-26 13:04:51', 2),
(26, 37, 200.00, '2024-06-27 12:15:48', 2),
(27, 48, 20.00, '2024-06-30 07:57:06', 1),
(28, 48, 20.00, '2024-06-30 07:57:15', 1);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `add_product`
--
ALTER TABLE `add_product`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `product_reports`
--
ALTER TABLE `product_reports`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `sold_products`
--
ALTER TABLE `sold_products`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `add_product`
--
ALTER TABLE `add_product`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=49;

--
-- AUTO_INCREMENT for table `admin`
--
ALTER TABLE `admin`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `product_reports`
--
ALTER TABLE `product_reports`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `sold_products`
--
ALTER TABLE `sold_products`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=29;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
