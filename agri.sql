-- phpMyAdmin SQL Dump
-- version 4.9.2
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: Apr 27, 2022 at 12:11 PM
-- Server version: 10.4.10-MariaDB
-- PHP Version: 7.3.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `agri`
--

-- --------------------------------------------------------

--
-- Table structure for table `address`
--

DROP TABLE IF EXISTS `address`;
CREATE TABLE IF NOT EXISTS `address` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `customer_id` bigint(20) NOT NULL,
  `company` varchar(50) DEFAULT NULL,
  `address1` varchar(150) NOT NULL,
  `address2` varchar(150) DEFAULT NULL,
  `city` varchar(50) NOT NULL,
  `postcode` int(10) NOT NULL,
  `country` varchar(20) NOT NULL,
  `state` varchar(20) NOT NULL,
  `isactive` tinyint(1) NOT NULL DEFAULT 1,
  `created` timestamp NOT NULL DEFAULT current_timestamp(),
  `isupdated` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `customer_id` (`customer_id`)
) ENGINE=MyISAM AUTO_INCREMENT=21 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `address`
--

INSERT INTO `address` (`id`, `customer_id`, `company`, `address1`, `address2`, `city`, `postcode`, `country`, `state`, `isactive`, `created`, `isupdated`) VALUES
(15, 1000001, 'Webgrity shipping', 'Garia', 'Bazar', 'Kolkata', 700005, 'India', 'Delhi', 1, '2022-04-25 06:36:19', '2022-04-25 06:36:19'),
(14, 1000001, 'Webgrity', 'kalighat', '', 'Kolkata', 700005, 'India', 'Delhi', 1, '2022-04-25 06:03:47', '2022-04-25 06:03:47'),
(13, 1000001, 'Webgrity', 'kalighat', '', 'Kolkata', 700005, 'India', 'Jharkhand', 1, '2022-04-25 06:02:34', '2022-04-25 06:02:34'),
(12, 1000001, 'Webgrity', 'tuyju', '', 'Kolkata', 743373, 'India', 'West Bengal', 1, '2022-04-25 06:02:14', '2022-04-25 06:02:14'),
(11, 1000001, 'Webgrity', 'kalighat', '', 'Kolkata', 700005, 'India', 'West Bengal', 1, '2022-04-25 05:57:31', '2022-04-25 05:57:31'),
(10, 1000001, '', 'tuyju', '', 'cgf', 1111, 'India', 'Jharkhand', 1, '2022-04-25 05:54:15', '2022-04-25 05:54:15'),
(8, 1000001, 'Webgrity', 'Lake Avenue', '', 'Kolkata', 700001, 'India', 'West Bengal', 1, '2022-04-23 07:32:14', '2022-04-23 07:32:14'),
(16, 1000001, 'Webgrity', 'Narayani Abad', 'Narayani Abad', 'South 24 Parganas', 743373, 'India', 'West Bengal', 1, '2022-04-25 10:06:33', '2022-04-25 10:06:33'),
(17, 1000003, '', 'Narayani Abad', 'Narayani Abad', 'Gangasagar', 743373, 'India', 'West Bengal', 1, '2022-04-25 11:06:00', '2022-04-25 11:06:00'),
(18, 1000001, 'Webgrity', 'Lake Avenue', 'Bazar', 'Kolkata', 400404, 'India', 'UP', 1, '2022-04-27 09:54:17', '2022-04-27 09:54:17'),
(19, 1000001, 'Webgrity', 'yjg', '', 'gyb j', 78454, 'India', 'Bihar', 1, '2022-04-27 09:55:00', '2022-04-27 09:55:00'),
(20, 1000001, 'yubgy', ' gbhghghgh', '', 'fghhgh', 78544, 'India', 'Maharastra', 1, '2022-04-27 09:56:07', '2022-04-27 09:56:07');

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

DROP TABLE IF EXISTS `admin`;
CREATE TABLE IF NOT EXISTS `admin` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `Name` varchar(50) NOT NULL,
  `email` varchar(25) NOT NULL,
  `password` varchar(64) NOT NULL,
  `isactive` tinyint(1) NOT NULL DEFAULT 1,
  `created` timestamp NOT NULL DEFAULT current_timestamp(),
  `isupdated` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `admin`
--

INSERT INTO `admin` (`id`, `Name`, `email`, `password`, `isactive`, `created`, `isupdated`) VALUES
(1, 'Admin', 'admin@gmail.com', '$2y$10$WElG7atBGC3doI9S15.TteULd6m4ZxM/FX32GPMGnNxAbYDUHawPK', 1, '2022-04-13 06:56:51', '2022-04-22 09:45:30');

-- --------------------------------------------------------

--
-- Table structure for table `category`
--

DROP TABLE IF EXISTS `category`;
CREATE TABLE IF NOT EXISTS `category` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  `parent` smallint(6) NOT NULL,
  `categoryorder` smallint(6) NOT NULL DEFAULT 0,
  `extension` varchar(11) NOT NULL,
  `isactive` smallint(1) NOT NULL DEFAULT 1,
  `created` timestamp NOT NULL DEFAULT current_timestamp(),
  `isupdated` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=25 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `category`
--

INSERT INTO `category` (`id`, `name`, `parent`, `categoryorder`, `extension`, `isactive`, `created`, `isupdated`) VALUES
(1, 'Animal Dosing', 0, 1, 'png', 1, '2022-04-14 09:16:08', '2022-04-22 09:48:06'),
(2, 'Sheep', 0, 3, 'png', 1, '2022-04-14 09:33:36', '2022-04-19 04:33:15'),
(3, 'Cattle', 0, 2, 'png', 1, '2022-04-14 09:36:06', '2022-04-19 04:33:04'),
(4, 'Clothing & Footwear', 0, 7, 'png', 1, '2022-04-14 09:53:47', '2022-04-19 04:34:18'),
(5, 'Pets', 0, 8, 'png', 1, '2022-04-14 11:06:58', '2022-04-19 04:34:33'),
(6, 'Sub Pets 1', 5, 1, '', 1, '2022-04-14 12:16:56', '2022-04-19 04:35:05'),
(7, 'Sub Category 2', 1, 1, '', 1, '2022-04-14 13:03:28', '2022-04-18 08:42:58'),
(8, 'Grooming', 2, 1, '', 1, '2022-04-16 05:32:12', '2022-04-19 10:47:24'),
(9, 'Anand', 0, 3, 'png', -1, '2022-04-18 05:40:07', '2022-04-18 08:44:14'),
(10, 'Hoofcare', 2, 2, '', 1, '2022-04-18 05:41:17', '2022-04-19 10:47:54'),
(11, 'Ladies ware', 4, 2, '', 1, '2022-04-18 08:58:42', '2022-04-19 05:22:03'),
(12, 'Horses', 0, 4, 'png', 1, '2022-04-18 10:24:06', '2022-04-19 04:33:35'),
(13, 'Fencing', 0, 5, 'png', 1, '2022-04-18 10:26:41', '2022-04-18 10:26:41'),
(14, 'Hardware', 0, 6, 'png', 1, '2022-04-18 10:27:25', '2022-04-18 10:27:25'),
(22, 'Lambing', 2, 3, '', 1, '2022-04-19 10:48:29', '2022-04-19 10:48:29'),
(15, 'Sub Cattle', 3, 1, '', 1, '2022-04-19 04:58:35', '2022-04-19 04:58:35'),
(16, 'Sub Fencing', 13, 0, '', 1, '2022-04-19 05:03:19', '2022-04-19 05:03:19'),
(17, 'Sub Horces', 12, 1, '', 1, '2022-04-19 05:03:46', '2022-04-19 05:03:46'),
(18, 'Sub Pets 2', 5, 0, '', 1, '2022-04-19 05:12:27', '2022-04-19 05:12:27'),
(19, 'Sub Hardware', 14, 5, '', 1, '2022-04-19 05:13:27', '2022-04-19 05:13:27'),
(20, 'Kids wear', 4, 1, '', 1, '2022-04-19 05:21:16', '2022-04-19 05:21:16'),
(21, 'Gents wear', 4, 3, '', 1, '2022-04-19 05:22:33', '2022-04-19 05:22:33'),
(23, 'Handling', 2, 4, '', 1, '2022-04-19 10:49:09', '2022-04-19 10:49:09'),
(24, 'Identification', 2, 5, '', 1, '2022-04-19 10:49:33', '2022-04-19 10:49:33');

-- --------------------------------------------------------

--
-- Table structure for table `customer`
--

DROP TABLE IF EXISTS `customer`;
CREATE TABLE IF NOT EXISTS `customer` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `firstname` varchar(20) NOT NULL,
  `lastname` varchar(20) NOT NULL,
  `email` varchar(50) NOT NULL,
  `phone` varchar(11) NOT NULL,
  `fax` varchar(11) DEFAULT NULL,
  `password` varchar(64) NOT NULL,
  `newsletter` tinyint(1) NOT NULL DEFAULT 0 COMMENT '1=Subscribed,0=not subscribed',
  `isactive` tinyint(1) NOT NULL DEFAULT 1,
  `created` timestamp NOT NULL DEFAULT current_timestamp(),
  `isupdated` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`)
) ENGINE=MyISAM AUTO_INCREMENT=1000004 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `customer`
--

INSERT INTO `customer` (`id`, `firstname`, `lastname`, `email`, `phone`, `fax`, `password`, `newsletter`, `isactive`, `created`, `isupdated`) VALUES
(1000001, 'Uttam', 'Khuntia', 'kh.kali.1324@gmail.com', '8001720048', '8001720048', '$2y$10$7NciaggFgiTJYaXQzodKGe5DrTgh5cGbTzxLiMu5ek.IVMKViW.6y', 0, 1, '2022-04-23 07:32:14', '2022-04-23 07:32:14'),
(1000003, 'Tarun', 'Khuntia', 'tarun@gmail.com', '8001720048', '8001720048', '$2y$10$zY6SeK3f1qByaYBh/fEc7O.2InEvq4wETyarSM4HRmwNNLAwQaO5W', 0, 1, '2022-04-25 11:06:00', '2022-04-25 11:06:00');

-- --------------------------------------------------------

--
-- Table structure for table `discount`
--

DROP TABLE IF EXISTS `discount`;
CREATE TABLE IF NOT EXISTS `discount` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `validfrom` date DEFAULT NULL,
  `validtill` date DEFAULT NULL,
  `type` tinyint(1) NOT NULL COMMENT '1=fixed,2=percentage',
  `amount` decimal(10,0) NOT NULL,
  `isactive` tinyint(1) NOT NULL DEFAULT 1,
  `created` timestamp NOT NULL DEFAULT current_timestamp(),
  `isupdated` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=7 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `discount`
--

INSERT INTO `discount` (`id`, `name`, `validfrom`, `validtill`, `type`, `amount`, `isactive`, `created`, `isupdated`) VALUES
(1, 'ABCDEF', '2022-04-16', '2022-04-30', 2, '25', 1, '2022-04-16 09:02:50', '2022-04-22 07:15:58'),
(2, 'test Discount', '2022-04-18', '2022-04-21', 2, '55', -1, '2022-04-16 09:12:05', '2022-04-18 07:59:47'),
(3, 'Discount 3', '2022-04-18', '2022-04-30', 1, '500', -1, '2022-04-18 04:29:40', '2022-04-18 07:59:50'),
(4, 'Category 1', NULL, NULL, 1, '55', -1, '2022-04-18 06:39:27', '2022-04-18 07:59:52'),
(5, 'Discount 1', '2022-04-29', '2022-04-29', 2, '5', -1, '2022-04-19 10:03:30', '2022-04-19 10:08:42'),
(6, 'ABC', '2022-04-21', '2022-04-22', 1, '25', 1, '2022-04-21 10:05:20', '2022-04-21 10:05:20');

-- --------------------------------------------------------

--
-- Table structure for table `orderinfo`
--

DROP TABLE IF EXISTS `orderinfo`;
CREATE TABLE IF NOT EXISTS `orderinfo` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `ordersummery_id` bigint(20) NOT NULL,
  `product_id` int(11) NOT NULL,
  `product_price` int(11) NOT NULL COMMENT 'Purchase time product price',
  `quantity` tinyint(4) NOT NULL,
  `isactive` tinyint(1) NOT NULL DEFAULT 1,
  `created` timestamp NOT NULL DEFAULT current_timestamp(),
  `isupdated` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `ordersummery_id` (`ordersummery_id`)
) ENGINE=MyISAM AUTO_INCREMENT=28 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `orderinfo`
--

INSERT INTO `orderinfo` (`id`, `ordersummery_id`, `product_id`, `product_price`, `quantity`, `isactive`, `created`, `isupdated`) VALUES
(1, 1000001, 2, 99, 10, 1, '2022-04-25 10:52:26', '2022-04-25 10:52:26'),
(2, 1000002, 3, 10, 1, 1, '2022-04-25 11:07:17', '2022-04-25 11:07:17'),
(3, 1000002, 12, 116, 2, 1, '2022-04-25 11:07:17', '2022-04-25 11:07:17'),
(4, 1000003, 1, 50, 1, 1, '2022-04-26 09:50:55', '2022-04-26 09:50:55'),
(5, 1000003, 3, 10, 1, 1, '2022-04-26 09:50:55', '2022-04-26 09:50:55'),
(6, 1000003, 8, 119, 1, 1, '2022-04-26 09:50:55', '2022-04-26 09:50:55'),
(7, 1000003, 13, 10, 1, 1, '2022-04-26 09:50:55', '2022-04-26 09:50:55'),
(8, 1000003, 4, 199, 1, 1, '2022-04-26 09:50:55', '2022-04-26 09:50:55'),
(9, 1000004, 10, 128, 1, 1, '2022-04-26 09:53:25', '2022-04-26 09:53:25'),
(10, 1000004, 9, 11, 1, 1, '2022-04-26 09:53:25', '2022-04-26 09:53:25'),
(11, 1000005, 2, 99, 1, 1, '2022-04-26 10:20:41', '2022-04-26 10:20:41'),
(12, 1000006, 13, 10, 5, 1, '2022-04-27 07:42:21', '2022-04-27 07:42:21'),
(13, 1000007, 2, 99, 1, 1, '2022-04-27 07:47:17', '2022-04-27 07:47:17'),
(14, 1000008, 3, 10, 1, 1, '2022-04-27 07:49:36', '2022-04-27 07:49:36'),
(15, 1000009, 2, 99, 1, 1, '2022-04-27 07:51:42', '2022-04-27 07:51:42'),
(16, 1000010, 1, 50, 1, 1, '2022-04-27 07:54:58', '2022-04-27 07:54:58'),
(17, 1000011, 2, 99, 1, 1, '2022-04-27 07:58:12', '2022-04-27 07:58:12'),
(18, 1000012, 9, 11, 1, 1, '2022-04-27 09:21:55', '2022-04-27 09:21:55'),
(19, 1000013, 1, 50, 1, 1, '2022-04-27 09:41:20', '2022-04-27 09:41:20'),
(20, 1000014, 5, 5, 1, 1, '2022-04-27 09:56:52', '2022-04-27 09:56:52'),
(21, 1000015, 12, 116, 1, 1, '2022-04-27 10:01:04', '2022-04-27 10:01:04'),
(22, 1000016, 5, 5, 1, 1, '2022-04-27 10:03:11', '2022-04-27 10:03:11'),
(23, 1000017, 3, 10, 1, 1, '2022-04-27 10:04:16', '2022-04-27 10:04:16'),
(24, 1000018, 2, 99, 1, 1, '2022-04-27 10:22:28', '2022-04-27 10:22:28'),
(25, 1000019, 2, 99, 1, 1, '2022-04-27 10:25:37', '2022-04-27 10:25:37'),
(26, 1000020, 3, 10, 1, 1, '2022-04-27 11:02:51', '2022-04-27 11:02:51'),
(27, 1000021, 1, 50, 1, 1, '2022-04-27 11:11:25', '2022-04-27 11:11:25');

-- --------------------------------------------------------

--
-- Table structure for table `ordersummery`
--

DROP TABLE IF EXISTS `ordersummery`;
CREATE TABLE IF NOT EXISTS `ordersummery` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `customer_id` bigint(20) NOT NULL,
  `billing_id` bigint(20) NOT NULL,
  `shipping_id` bigint(20) NOT NULL,
  `subtotal` decimal(10,2) NOT NULL,
  `discount` decimal(10,2) NOT NULL,
  `ecotax` decimal(10,2) NOT NULL,
  `vat` decimal(10,2) NOT NULL,
  `total` decimal(10,2) NOT NULL,
  `payment` tinyint(1) NOT NULL COMMENT '1=cash-on-delivery',
  `order_status` tinyint(1) NOT NULL DEFAULT 1 COMMENT '-2=canceled by customer,-1=canceled by seller,1=processing,2=in transit,3=delivered',
  `payment_status` tinyint(1) NOT NULL DEFAULT 0 COMMENT '1=sucessful,0=unsuccessful',
  `isactive` tinyint(1) NOT NULL DEFAULT 1,
  `created` timestamp NOT NULL DEFAULT current_timestamp(),
  `isupdated` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `customer_id` (`customer_id`),
  KEY `billing_id` (`billing_id`),
  KEY `shiping_id` (`shipping_id`)
) ENGINE=MyISAM AUTO_INCREMENT=1000022 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `ordersummery`
--

INSERT INTO `ordersummery` (`id`, `customer_id`, `billing_id`, `shipping_id`, `subtotal`, `discount`, `ecotax`, `vat`, `total`, `payment`, `order_status`, `payment_status`, `isactive`, `created`, `isupdated`) VALUES
(1000001, 1000001, 16, 8, '990.00', '247.50', '2.00', '148.50', '893.00', 1, 1, 0, 1, '2022-04-25 10:52:26', '2022-04-25 10:52:26'),
(1000002, 1000003, 17, 17, '242.00', '0.00', '4.00', '48.40', '294.40', 1, 1, 0, 1, '2022-04-25 11:07:17', '2022-04-25 11:07:17'),
(1000003, 1000001, 15, 10, '388.00', '0.00', '10.00', '77.60', '475.60', 1, 1, 0, 1, '2022-04-26 09:50:55', '2022-04-26 09:50:55'),
(1000004, 1000001, 14, 10, '139.00', '0.00', '4.00', '27.80', '170.80', 1, 1, 0, 1, '2022-04-26 09:53:25', '2022-04-26 09:53:25'),
(1000005, 1000001, 13, 13, '99.00', '0.00', '2.00', '19.80', '120.80', 1, 1, 1, 1, '2022-04-26 10:20:41', '2022-04-26 10:20:41'),
(1000006, 1000001, 15, 15, '50.00', '0.00', '2.00', '10.00', '62.00', 1, 1, 0, 1, '2022-04-27 07:42:21', '2022-04-27 07:42:21'),
(1000007, 1000001, 10, 14, '99.00', '0.00', '2.00', '19.80', '120.80', 1, 1, 0, 1, '2022-04-27 07:47:17', '2022-04-27 07:47:17'),
(1000008, 1000001, 14, 14, '10.00', '0.00', '2.00', '2.00', '14.00', 1, 1, 0, 1, '2022-04-27 07:49:36', '2022-04-27 07:49:36'),
(1000009, 1000001, 13, 8, '99.00', '0.00', '2.00', '19.80', '120.80', 1, 1, 0, 1, '2022-04-27 07:51:42', '2022-04-27 07:51:42'),
(1000010, 1000001, 8, 13, '50.00', '0.00', '2.00', '10.00', '62.00', 1, 1, 0, 1, '2022-04-27 07:54:58', '2022-04-27 07:54:58'),
(1000011, 1000001, 15, 13, '99.00', '0.00', '2.00', '19.80', '120.80', 1, 1, 0, 1, '2022-04-27 07:58:12', '2022-04-27 07:58:12'),
(1000012, 1000001, 14, 14, '11.00', '0.00', '2.00', '2.20', '15.20', 1, 1, 0, 1, '2022-04-27 09:21:55', '2022-04-27 09:21:55'),
(1000013, 1000001, 14, 14, '50.00', '0.00', '2.00', '10.00', '62.00', 1, 1, 0, 1, '2022-04-27 09:41:20', '2022-04-27 09:41:20'),
(1000014, 1000001, 14, 14, '5.00', '0.00', '2.00', '1.00', '8.00', 1, 1, 0, 1, '2022-04-27 09:56:52', '2022-04-27 09:56:52'),
(1000015, 1000001, 14, 14, '116.00', '0.00', '2.00', '23.20', '141.20', 1, 1, 0, 1, '2022-04-27 10:01:04', '2022-04-27 10:01:04'),
(1000016, 1000001, 15, 15, '5.00', '0.00', '2.00', '1.00', '8.00', 1, 1, 0, 1, '2022-04-27 10:03:11', '2022-04-27 10:03:11'),
(1000017, 1000001, 11, 15, '10.00', '0.00', '2.00', '2.00', '14.00', 1, 1, 0, 1, '2022-04-27 10:04:16', '2022-04-27 10:04:16'),
(1000018, 1000001, 15, 15, '99.00', '0.00', '2.00', '19.80', '120.80', 1, 1, 0, 1, '2022-04-27 10:22:28', '2022-04-27 10:22:28'),
(1000019, 1000001, 15, 15, '99.00', '0.00', '2.00', '19.80', '120.80', 1, 1, 0, 1, '2022-04-27 10:25:37', '2022-04-27 10:25:37'),
(1000020, 1000001, 14, 10, '10.00', '0.00', '2.00', '2.00', '14.00', 1, 1, 0, 1, '2022-04-27 11:02:51', '2022-04-27 11:02:51'),
(1000021, 1000001, 15, 11, '50.00', '0.00', '2.00', '10.00', '62.00', 1, 1, 0, 1, '2022-04-27 11:11:25', '2022-04-27 11:11:25');

-- --------------------------------------------------------

--
-- Table structure for table `order_status`
--

DROP TABLE IF EXISTS `order_status`;
CREATE TABLE IF NOT EXISTS `order_status` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `Name` varchar(50) NOT NULL,
  `isactive` tinyint(1) NOT NULL DEFAULT 1,
  `created` timestamp NOT NULL DEFAULT current_timestamp(),
  `isupdated` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `order_status`
--

INSERT INTO `order_status` (`id`, `Name`, `isactive`, `created`, `isupdated`) VALUES
(-2, 'Canceled  By Customer', 1, '2022-04-26 09:31:06', '2022-04-26 09:31:06'),
(-1, 'Canceled  By Seller', 1, '2022-04-26 09:31:34', '2022-04-26 09:31:34'),
(1, 'Processing', 1, '2022-04-26 09:32:17', '2022-04-26 09:32:17'),
(2, 'In Transit', 1, '2022-04-26 09:32:43', '2022-04-26 09:32:43'),
(3, 'Delivered', 1, '2022-04-26 09:33:03', '2022-04-26 09:33:03');

-- --------------------------------------------------------

--
-- Table structure for table `product`
--

DROP TABLE IF EXISTS `product`;
CREATE TABLE IF NOT EXISTS `product` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `description` text DEFAULT NULL,
  `category` int(11) NOT NULL,
  `subcategory` int(11) DEFAULT NULL,
  `price` decimal(10,0) NOT NULL,
  `image_extension` varchar(11) NOT NULL,
  `availability` tinyint(1) NOT NULL DEFAULT 1,
  `special` tinyint(1) NOT NULL DEFAULT 0,
  `featured` tinyint(1) NOT NULL DEFAULT 0,
  `isactive` tinyint(1) NOT NULL DEFAULT 1,
  `created` timestamp NOT NULL DEFAULT current_timestamp(),
  `isupdated` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `category` (`category`),
  KEY `subcategory` (`subcategory`)
) ENGINE=MyISAM AUTO_INCREMENT=14 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `product`
--

INSERT INTO `product` (`id`, `name`, `description`, `category`, `subcategory`, `price`, `image_extension`, `availability`, `special`, `featured`, `isactive`, `created`, `isupdated`) VALUES
(1, 'Product 1', '', 1, 7, '50', 'jpg', 1, 1, 0, 1, '2022-04-18 09:35:11', '2022-04-20 07:26:31'),
(2, 'Western Dress', '', 4, 11, '99', 'jpg', 1, 1, 1, 1, '2022-04-18 10:12:53', '2022-04-21 06:31:10'),
(3, 'Closamectin Solution for Injection for Cattle', '', 1, 7, '10', 'jpg', 1, 1, 0, 1, '2022-04-19 04:38:48', '2022-04-19 04:38:48'),
(4, 'Â¿QuÃ© es Lorem Ipsum?', 'Lorem Ipsum es simplemente el texto de relleno de las imprentas y archivos de texto. Lorem Ipsum ha sido el texto de relleno estÃ¡ndar de las industrias desde el aÃ±o 1500, cuando un impresor (N. del T. persona que se dedica a la imprenta) desconocido usÃ³ una galerÃ­a de textos y los mezclÃ³ de tal manera que logrÃ³ hacer un libro de textos especimen. No sÃ³lo sobreviviÃ³ 500 aÃ±os, sino que tambien ingresÃ³ como texto de relleno en documentos electrÃ³nicos, quedando esencialmente igual al original. Fue popularizado en los 60s con la creaciÃ³n de las hojas \"Letraset\", las cuales contenian pasajes de Lorem Ipsum, y mÃ¡s recientemente con software de autoediciÃ³n, como por ejemplo Aldus PageMaker, el cual incluye versiones de Lorem Ipsum.', 5, 6, '199', 'jpg', 1, 1, 1, 1, '2022-04-19 04:54:57', '2022-04-19 04:59:32'),
(5, 'Â¿De dÃ³nde viene?', 'Hay muchas variaciones de los pasajes de Lorem Ipsum disponibles, pero la mayorÃ­a sufriÃ³ alteraciones en alguna manera, ya sea porque se le agregÃ³ humor, o palabras aleatorias que no parecen ni un poco creÃ­bles. Si vas a utilizar un pasaje de Lorem Ipsum, necesitÃ¡s estar seguro de que no hay nada avergonzante escondido en el medio del texto. Todos los generadores de Lorem Ipsum que se encuentran en Interne', 3, 15, '5', 'jpg', 1, 1, 1, 1, '2022-04-19 05:00:29', '2022-04-19 05:00:29'),
(6, 'Lorem ipsum dolor sit amet,', 'abitasse platea dictumst. In dignissim congue arcu, vitae tincidunt ipsum facilisis consequat. Donec gravida dolor a magna lacinia, sit amet hendrerit purus accumsan. Fusce et libero eget leo pellentesque molestie. Quisque sodales feugiat dui, in efficitur arcu vehicula quis. Donec aliquam, mi quis accumsan fringilla, purus mauris elementum purus, sed varius dolor felis ut magna. Curabitur at libero quis magna aliquam volutpat. Nam pulvinar risus risus, efficitur tristique dui blandit ac. Donec at lectus et mi congue iaculis. Vestibulum malesuada quis lacus vitae vulputate.', 4, 11, '59', 'jpg', 1, 1, 0, 1, '2022-04-19 05:02:09', '2022-04-19 05:02:09'),
(7, 'Solar fencing', ' Maecenas purus nibh, pretium vel consequat ac, interdum nec nisi. Vivamus aliquam neque mauris. Sed mollis urna eget tellus accumsan porta. Maecenas eleifend libero urna, eget consequat tellus mollis eu. Pellentesque habitant morbi tristique senectus et netus et malesuada fames ac turpis egestas. Nulla lacinia consectetur luctus. Nulla quis nisl nec nisl facilisis imperdiet condimentum vel neque.', 13, 16, '19', 'jpg', 0, 1, 1, 1, '2022-04-19 05:04:54', '2022-04-19 08:35:38'),
(8, 'Vestibulum ante ipsum primis in faucibus orc', 'Vestibulum eu posuere dolor, id hendrerit mauris. Nullam ut congue velit. Morbi id mollis neque, id mattis mi. Class aptent taciti sociosqu ad litora torquent per conubia nostra, per inceptos himenaeos. Mauris sit amet tincidunt quam. Aenean laoreet rutrum condimentum. Mauris congue varius pharetra. Suspendisse aliquet justo sed venenatis gravida. Suspendisse euismod ut turpis vel rutrum. Cras lorem dolor, facilisis pretium sollicitudin nec, accumsan sed tortor. Sed id nisi in orci dapibus consequat. Nulla eu aliquet elit, et semper dolor. Vestibulum convallis ac felis id vehicula.', 12, 17, '119', 'jpg', 1, 1, 1, 1, '2022-04-19 05:11:03', '2022-04-19 05:11:03'),
(9, ' consectetur adipiscing elit. Nunc finibus eget', 'Vestibulum eu posuere dolor, id hendrerit mauris. Nullam ut congue velit. Morbi id mollis neque, id mattis mi. Class aptent taciti sociosqu ad litora torquent per conubia nostra, per inceptos himenaeos. Mauris sit amet tincidunt quam. Aenean laoreet rutrum condimentum. Mauris congue varius pharetra. Suspendisse aliquet justo sed venenatis gravida. Suspendisse euismod ut turpis vel rutrum. Cras lorem dolor, facilisis pretium sollicitudin nec', 2, 8, '11', 'jpg', 1, 1, 1, 1, '2022-04-19 05:15:51', '2022-04-19 05:15:51'),
(10, 'Lorem Ipsum Nedir?', 'Lorem Ipsum, dizgi ve baskÄ± endÃ¼strisinde kullanÄ±lan mÄ±gÄ±r metinlerdir. Lorem Ipsum, adÄ± bilinmeyen bir matbaacÄ±nÄ±n bir hurufat numune kitabÄ± oluÅŸturmak Ã¼zere bir yazÄ± galerisini alarak karÄ±ÅŸtÄ±rdÄ±ÄŸÄ± 1500\'lerden beri endÃ¼stri standardÄ± sahte metinler olarak kullanÄ±lmÄ±ÅŸtÄ±r. BeÅŸyÃ¼z yÄ±l boyunca varlÄ±ÄŸÄ±nÄ± sÃ¼rdÃ¼rmekle kalmamÄ±ÅŸ, aynÄ± zamanda pek deÄŸiÅŸmeden', 2, 10, '128', 'jpg', 1, 1, 1, 1, '2022-04-19 05:19:51', '2022-04-19 05:19:51'),
(11, 'Ã¼rÃ¼mleri iÃ§eren masaÃ¼stÃ¼ yayÄ±ncÄ±lÄ±k yazÄ±lÄ±mlarÄ± ile popÃ¼ler olmuÅŸtur.', 'a fost macheta standard a industriei Ã®ncÄƒ din secolul al XVI-lea, cÃ¢nd un tipograf anonim a luat o planÅŸetÄƒ de litere ÅŸi le-a amestecat pentru a crea o carte demonstrativÄƒ pentru literele respective.', 4, 20, '49', 'jpg', 0, 1, 1, 1, '2022-04-19 05:25:21', '2022-04-27 06:03:40'),
(12, 'De unde pot sa-l iau ÅŸi eu', ' prin infiltrare de elemente de umor, sau de cuvinte luate aleator, care nu sunt cÃ¢tuÅŸi de puÅ£in credibile. Daca vreÅ£i sÄƒ folosiÅ£i un pasaj de Lorem Ipsum, trebuie sÄƒ vÄƒ asiguraÅ£i cÄƒ nu conÅ£ine nimic stÃ¢njenitor ascun', 4, 21, '116', 'jpg', 1, 1, 1, 1, '2022-04-19 05:33:00', '2022-04-19 05:33:00'),
(13, 'Anand', 'Test', 3, 15, '10', 'jpg', 1, 0, 0, 1, '2022-04-21 10:04:03', '2022-04-21 10:04:03');

-- --------------------------------------------------------

--
-- Table structure for table `productgallery`
--

DROP TABLE IF EXISTS `productgallery`;
CREATE TABLE IF NOT EXISTS `productgallery` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `product_id` bigint(20) NOT NULL,
  `extension` varchar(10) NOT NULL,
  `isactive` tinyint(1) NOT NULL DEFAULT 1,
  `created` timestamp NOT NULL DEFAULT current_timestamp(),
  `isupdated` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `product_id` (`product_id`)
) ENGINE=MyISAM AUTO_INCREMENT=75 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `productgallery`
--

INSERT INTO `productgallery` (`id`, `product_id`, `extension`, `isactive`, `created`, `isupdated`) VALUES
(67, 9, '9_2.jpg', 1, '2022-04-21 06:44:00', '2022-04-21 06:44:00'),
(66, 9, '9_1.jpg', 1, '2022-04-21 06:43:54', '2022-04-21 06:43:54'),
(64, 6, '6_0.jpg', 1, '2022-04-21 06:41:38', '2022-04-21 06:41:38'),
(62, 4, '4_2.jpg', 1, '2022-04-21 06:40:10', '2022-04-21 06:40:10'),
(61, 4, '4_1.jpg', 1, '2022-04-21 06:40:04', '2022-04-21 06:40:04'),
(60, 4, '4_0.jpg', 1, '2022-04-21 06:40:00', '2022-04-21 06:40:00'),
(59, 3, '3_1.jpg', 1, '2022-04-21 06:39:12', '2022-04-21 06:39:12'),
(58, 3, '3_0.jpg', 1, '2022-04-21 06:39:02', '2022-04-21 06:39:02'),
(65, 9, '9_0.jpg', 1, '2022-04-21 06:43:47', '2022-04-21 06:43:47'),
(69, 11, '11_1.jpg', 1, '2022-04-21 06:46:06', '2022-04-21 06:46:06'),
(68, 11, '11_0.jpg', 1, '2022-04-21 06:45:55', '2022-04-21 06:45:55'),
(72, 12, '12_2.jpg', 1, '2022-04-21 06:47:08', '2022-04-21 06:47:08'),
(71, 12, '12_1.jpg', 1, '2022-04-21 06:47:05', '2022-04-21 06:47:05'),
(70, 12, '12_0.jpg', 1, '2022-04-21 06:46:50', '2022-04-21 06:46:50'),
(73, 13, '13_0.jpg', 1, '2022-04-21 10:04:04', '2022-04-21 10:04:04'),
(74, 2, '2_0.jpg', 1, '2022-04-26 10:19:21', '2022-04-26 10:19:21'),
(55, 1, '1_0.jpg', 1, '2022-04-21 06:20:57', '2022-04-21 06:20:57');
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
