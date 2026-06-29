-- phpMyAdmin SQL Dump
-- version 5.2.3
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: May 31, 2026 at 07:22 AM
-- Server version: 8.4.3
-- PHP Version: 8.4.14

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `metinca_db2`
--

-- --------------------------------------------------------

--
-- Table structure for table `account_table`
--

CREATE TABLE `account_table` (
  `id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED NOT NULL,
  `phone` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `company` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `position` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `address` text COLLATE utf8mb4_unicode_ci,
  `city` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `zip` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `fax` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `account_table`
--

INSERT INTO `account_table` (`id`, `user_id`, `phone`, `company`, `position`, `address`, `city`, `zip`, `fax`, `created_at`, `updated_at`) VALUES
(1, 17, '088973678351', 'PT. ABCDE', 'Manager Sales', 'Jl. new york 32', 'New York', '12345', '0214603489', '2026-03-08 06:32:13', '2026-03-10 08:47:23'),
(2, 18, '088808528826', 'PT. PINDAD', 'Manager PPIC', 'Indonesia', 'Jakarta', '1234', '1234', '2026-05-30 19:04:52', '2026-05-30 19:05:42');

-- --------------------------------------------------------

--
-- Table structure for table `articles`
--

CREATE TABLE `articles` (
  `id` bigint UNSIGNED NOT NULL,
  `part_number` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `article_no` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `part_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `customer_id` bigint UNSIGNED DEFAULT NULL,
  `die_no` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `material` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `drawing_no` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `drawing_rev` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `effective_date` date DEFAULT NULL,
  `lokasi_pengerjaan` int DEFAULT NULL,
  `remark` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `articles`
--

INSERT INTO `articles` (`id`, `part_number`, `article_no`, `part_name`, `customer_id`, `die_no`, `material`, `drawing_no`, `drawing_rev`, `effective_date`, `lokasi_pengerjaan`, `remark`, `created_at`, `updated_at`) VALUES
(1, 'S6-201.04', '3441.0104', 'GLRD-Gehaeuse (Casing mechanical seal) ME270', NULL, NULL, '1.4581', '32.441.026', 'd', '2008-05-28', 1, NULL, '2026-02-02 05:40:16', '2026-02-02 05:40:16'),
(2, 'S6-201.03', '3441.0103', 'Pump Bowl Leitschaufelgehause PL08-0095/1', NULL, NULL, '1.4536', '32.441.027', 'c', '2008-05-28', 1, NULL, '2026-02-12 03:50:41', '2026-02-12 04:02:04'),
(3, 'S6-201.02', '3441.0102', 'Pump Bowl PL08-0095/1', NULL, NULL, '1.4536', '32.441.027', 'c', '2008-05-28', 1, NULL, '2026-02-12 03:59:44', '2026-02-12 03:59:44'),
(4, 'S6-201.01', '3441.0101', 'Pump Bowl PL08-0095/4', NULL, NULL, '1.4535', '32.441.027', 'c', '2008-05-28', 1, NULL, '2026-02-12 04:00:42', '2026-02-12 04:01:48'),
(5, 'S6-201.05', '3441.0105', 'Bowl Leitschaufelgehause PL08-0095/1', NULL, NULL, '1.4531', '32.441.029', 'a', '2008-05-28', 1, NULL, '2026-02-12 04:02:56', '2026-02-27 04:22:54');

-- --------------------------------------------------------

--
-- Table structure for table `cache`
--

CREATE TABLE `cache` (
  `key` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` mediumtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `cache`
--

INSERT INTO `cache` (`key`, `value`, `expiration`) VALUES
('laravel-cache-adminsales@example.com|127.0.0.1', 'i:2;', 1780198383),
('laravel-cache-adminsales@example.com|127.0.0.1:timer', 'i:1780198383;', 1780198383),
('laravel-cache-zaqy@example.com|127.0.0.1', 'i:3;', 1780167659),
('laravel-cache-zaqy@example.com|127.0.0.1:timer', 'i:1780167659;', 1780167659);

-- --------------------------------------------------------

--
-- Table structure for table `cache_locks`
--

CREATE TABLE `cache_locks` (
  `key` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `owner` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `contracts`
--

CREATE TABLE `contracts` (
  `id` bigint UNSIGNED NOT NULL,
  `customer_id` bigint UNSIGNED NOT NULL,
  `quotation_id` bigint UNSIGNED NOT NULL,
  `article_id` bigint UNSIGNED DEFAULT NULL,
  `order_no` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `amandment_no` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `location` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `part_no` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `part_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `contract_no` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` enum('created','revision','approved','done') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'created',
  `sales_approver` bigint UNSIGNED DEFAULT NULL,
  `sales_approved_at` timestamp NULL DEFAULT NULL,
  `ppc_approver` bigint UNSIGNED DEFAULT NULL,
  `ppc_approved_at` timestamp NULL DEFAULT NULL,
  `dev_engineering_approver` bigint UNSIGNED DEFAULT NULL,
  `dev_engineering_approved_at` timestamp NULL DEFAULT NULL,
  `quality_approver` bigint UNSIGNED DEFAULT NULL,
  `quality_approved_at` timestamp NULL DEFAULT NULL,
  `others_comment` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `contracts`
--

INSERT INTO `contracts` (`id`, `customer_id`, `quotation_id`, `article_id`, `order_no`, `amandment_no`, `location`, `part_no`, `part_name`, `contract_no`, `status`, `sales_approver`, `sales_approved_at`, `ppc_approver`, `ppc_approved_at`, `dev_engineering_approver`, `dev_engineering_approved_at`, `quality_approver`, `quality_approved_at`, `others_comment`, `created_at`, `updated_at`) VALUES
(1, 2, 1, 1, '88908442', NULL, 'PT.Metinca (Jakarta)', 'S6-201.04', 'GLRD-Gehaeuse (Casing mechanical seal) ME270', 'CT-2026-001', 'created', 1, NULL, 1, NULL, 1, NULL, 1, NULL, NULL, '2026-02-02 06:08:30', '2026-02-02 06:08:30'),
(2, 9, 2, 1, 'OR-2026', NULL, NULL, 'S6-201.04', 'GLRD-Gehaeuse (Casing mechanical seal) ME270', 'CT-2026-002', 'created', 4, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2026-02-02 18:34:04', '2026-02-02 18:34:26'),
(3, 10, 3, NULL, 'OR-2026-QT003', NULL, NULL, 'S6-201.04', 'GLRD-Gehaeuse (Casing mechanical seal) ME270', 'CT-2026-003', 'revision', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2026-02-02 18:39:57', '2026-02-03 11:03:27'),
(4, 2, 4, 1, '34', NULL, NULL, 'S6-201.04', 'GLRD-Gehaeuse (Casing mechanical seal) ME270', 'CT-2026-004', 'created', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2026-02-03 06:57:56', '2026-02-03 06:57:56'),
(5, 11, 5, 1, '123424', NULL, NULL, 'S6-201.04', 'GLRD-Gehaeuse (Casing mechanical seal) ME270', 'CT-2026-005', 'done', 4, NULL, 6, NULL, 7, NULL, 5, NULL, NULL, '2026-02-03 08:36:03', '2026-02-03 08:39:25'),
(6, 12, 6, 1, '123424', NULL, NULL, 'S6-201.04', 'GLRD-Gehaeuse (Casing mechanical seal) ME270', 'CT-2026-006', 'created', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2026-02-04 03:25:12', '2026-02-04 03:25:12'),
(7, 12, 6, 1, '123424', NULL, NULL, 'S6-201.04', 'GLRD-Gehaeuse (Casing mechanical seal) ME270', 'CT-2026-007', 'done', 4, '2026-04-03 03:35:59', 6, '2026-04-03 03:36:57', 7, '2026-04-03 03:36:33', 5, '2026-04-03 03:35:09', NULL, '2026-02-04 03:25:19', '2026-04-03 03:36:57'),
(9, 12, 9, 5, 'PO-2026-007', NULL, NULL, 'S6-201.05', 'Bowl Leitschaufelgehause PL08-0095/1', 'CT-2026-008', 'done', 4, NULL, 6, NULL, 7, NULL, 5, NULL, NULL, '2026-02-13 02:09:40', '2026-02-13 02:13:58'),
(16, 17, 14, 1, 'PO-2026-04-0001', NULL, NULL, 'S6-201.04', 'GLRD-Gehaeuse (Casing mechanical seal) ME270', 'CT-2026-009', 'created', 4, '2026-05-31 06:51:57', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2026-04-16 14:54:51', '2026-05-31 06:51:57');

-- --------------------------------------------------------

--
-- Table structure for table `contract_requirements`
--

CREATE TABLE `contract_requirements` (
  `id` bigint UNSIGNED NOT NULL,
  `contract_id` bigint UNSIGNED NOT NULL,
  `requirement_from` enum('sales','quality','ppc','design engineering') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'sales',
  `requirement` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `requirement_value` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `contract_requirements`
--

INSERT INTO `contract_requirements` (`id`, `contract_id`, `requirement_from`, `requirement`, `requirement_value`, `created_at`, `updated_at`) VALUES
(1, 1, 'sales', 'Price', 'ok', '2026-02-02 06:08:30', '2026-02-02 06:08:30'),
(2, 1, 'sales', 'Quantity', '50 pcs', '2026-02-02 06:08:30', '2026-02-02 06:08:30'),
(3, 1, 'sales', 'Delivery Required', '01/07/2026', '2026-02-02 06:08:30', '2026-02-02 06:08:30'),
(4, 1, 'sales', 'Supply Condition', 'ada', '2026-02-02 06:08:30', '2026-02-02 06:08:30'),
(5, 1, 'sales', 'Special / Customer Requirement', 'material : 1.4581', '2026-02-02 06:08:30', '2026-02-02 06:08:30'),
(6, 1, 'quality', 'Drawing', '32.441.026', '2026-02-02 06:08:30', '2026-02-02 06:08:30'),
(7, 1, 'quality', 'Standard / Spec', 'DIN EN 10213', '2026-02-02 06:08:30', '2026-02-02 06:08:30'),
(8, 1, 'quality', 'Inspection', '-', '2026-02-02 06:08:30', '2026-02-02 06:08:30'),
(9, 1, 'ppc', 'Material Requirement', '1.4581', '2026-02-02 06:08:30', '2026-02-02 06:08:30'),
(10, 1, 'ppc', 'Pattern Wax', '-', '2026-02-02 06:08:30', '2026-02-02 06:08:30'),
(11, 1, 'ppc', 'Purchasing', '1.4581 = 149,5 kg', '2026-02-02 06:08:30', '2026-02-02 06:08:30'),
(12, 1, 'ppc', 'Sub Contracting', '-', '2026-02-02 06:08:30', '2026-02-02 06:08:30'),
(13, 1, 'design engineering', 'Master Job Card', 'ada', '2026-02-02 06:08:30', '2026-02-02 06:08:30'),
(14, 1, 'design engineering', 'WRA / WI', 'ada', '2026-02-02 06:08:30', '2026-02-02 06:08:30'),
(15, 1, 'design engineering', 'Dies', 'ada', '2026-02-02 06:08:30', '2026-02-02 06:08:30'),
(16, 1, 'design engineering', 'Tool', 'ada', '2026-02-02 06:08:30', '2026-02-02 06:08:30'),
(17, 1, 'design engineering', 'Fixtures', 'ada', '2026-02-02 06:08:30', '2026-02-02 06:08:30'),
(18, 2, 'sales', 'Price', 'ok', '2026-02-02 18:34:04', '2026-02-02 18:34:04'),
(19, 2, 'sales', 'Quantity', '50', '2026-02-02 18:34:04', '2026-02-02 18:34:04'),
(20, 2, 'sales', 'Delivery Required', '05/09/26', '2026-02-02 18:34:04', '2026-03-31 18:06:36'),
(21, 2, 'sales', 'Supply Condition', 'sd', '2026-02-02 18:34:04', '2026-02-02 18:34:04'),
(22, 2, 'sales', 'Special / Customer Requirement', 'sda', '2026-02-02 18:34:04', '2026-02-02 18:34:04'),
(23, 2, 'quality', 'Drawing', '32.441.026', '2026-02-02 18:34:04', '2026-02-02 18:34:04'),
(24, 2, 'quality', 'Standard / Spec', 'DIN EN 10213', '2026-02-02 18:34:04', '2026-02-02 18:34:04'),
(25, 2, 'quality', 'Inspection', 'pengiriman dgn adasd', '2026-02-02 18:34:04', '2026-02-02 18:34:04'),
(26, 2, 'ppc', 'Material Requirement', '163', '2026-02-02 18:34:04', '2026-02-02 18:34:04'),
(27, 2, 'ppc', 'Pattern Wax', '1.4581 = 149,5 kg', '2026-02-02 18:34:04', '2026-02-02 18:34:04'),
(28, 2, 'ppc', 'Purchasing', '-', '2026-02-02 18:34:04', '2026-02-02 18:34:04'),
(29, 2, 'ppc', 'Sub Contracting', '-', '2026-02-02 18:34:04', '2026-02-02 18:34:04'),
(30, 2, 'design engineering', 'Master Job Card', 'ada', '2026-02-02 18:34:04', '2026-02-02 18:34:04'),
(31, 2, 'design engineering', 'WRA / WI', 'ada', '2026-02-02 18:34:04', '2026-02-02 18:34:04'),
(32, 2, 'design engineering', 'Dies', 'ada', '2026-02-02 18:34:04', '2026-02-02 18:34:04'),
(33, 2, 'design engineering', 'Tool', 'ada', '2026-02-02 18:34:04', '2026-02-02 18:34:04'),
(34, 2, 'design engineering', 'Fixtures', '-', '2026-02-02 18:34:04', '2026-02-02 18:34:04'),
(35, 3, 'sales', 'Price', 'valid 50', '2026-02-02 18:39:57', '2026-02-03 11:03:27'),
(36, 3, 'sales', 'Quantity', 'harga ok', '2026-02-02 18:39:57', '2026-02-02 18:39:57'),
(37, 3, 'sales', 'Delivery Required', '20 pcs', '2026-02-02 18:39:57', '2026-02-02 18:39:57'),
(38, 3, 'sales', 'Supply Condition', '01/07/2025', '2026-02-02 18:39:57', '2026-02-02 18:39:57'),
(39, 3, 'sales', 'Special / Customer Requirement', '-', '2026-02-02 18:39:57', '2026-02-02 18:39:57'),
(40, 3, 'quality', 'Drawing', '32.441.026', '2026-02-02 18:39:57', '2026-02-02 18:39:57'),
(41, 3, 'quality', 'Standard / Spec', 'DIN EN 10213', '2026-02-02 18:39:57', '2026-02-02 18:39:57'),
(42, 3, 'quality', 'Inspection', 'pengiriman dgn adasd', '2026-02-02 18:39:57', '2026-02-02 18:39:57'),
(43, 3, 'ppc', 'Material Requirement', '163', '2026-02-02 18:39:57', '2026-02-02 18:39:57'),
(44, 3, 'ppc', 'Pattern Wax', '1.4581 = 149,5 kg', '2026-02-02 18:39:57', '2026-02-02 18:39:57'),
(45, 3, 'ppc', 'Purchasing', '-', '2026-02-02 18:39:57', '2026-02-02 18:39:57'),
(46, 3, 'ppc', 'Sub Contracting', '-', '2026-02-02 18:39:57', '2026-02-02 18:39:57'),
(47, 3, 'design engineering', 'Master Job Card', 'ada', '2026-02-02 18:39:57', '2026-02-02 18:39:57'),
(48, 3, 'design engineering', 'WRA / WI', 'ada', '2026-02-02 18:39:57', '2026-02-02 18:39:57'),
(49, 3, 'design engineering', 'Dies', 'ada', '2026-02-02 18:39:57', '2026-02-02 18:39:57'),
(50, 3, 'design engineering', 'Tool', 'ada', '2026-02-02 18:39:57', '2026-02-02 18:39:57'),
(51, 3, 'design engineering', 'Fixtures', '-', '2026-02-02 18:39:57', '2026-02-02 18:39:57'),
(52, 4, 'sales', 'Price', 'oke', '2026-02-03 06:57:56', '2026-02-03 06:57:56'),
(53, 4, 'sales', 'Quantity', '34', '2026-02-03 06:57:56', '2026-02-03 06:57:56'),
(54, 4, 'sales', 'Delivery Required', '17/01/2027', '2026-02-03 06:57:56', '2026-02-03 06:57:56'),
(55, 4, 'sales', 'Supply Condition', 'good', '2026-02-03 06:57:56', '2026-02-03 06:57:56'),
(56, 4, 'sales', 'Special / Customer Requirement', 'pin', '2026-02-03 06:57:56', '2026-02-03 06:57:56'),
(57, 4, 'quality', 'Drawing', '32.441.026', '2026-02-03 06:57:56', '2026-02-03 06:57:56'),
(58, 4, 'quality', 'Standard / Spec', 'g', '2026-02-03 06:57:56', '2026-02-03 06:57:56'),
(59, 4, 'quality', 'Inspection', '-', '2026-02-03 06:57:56', '2026-02-03 06:57:56'),
(60, 4, 'ppc', 'Material Requirement', '1.4581', '2026-02-03 06:57:56', '2026-02-03 06:57:56'),
(61, 4, 'ppc', 'Pattern Wax', 'G4e', '2026-02-03 06:57:56', '2026-02-03 06:57:56'),
(62, 4, 'ppc', 'Purchasing', '-', '2026-02-03 06:57:56', '2026-02-03 06:57:56'),
(63, 4, 'ppc', 'Sub Contracting', '-', '2026-02-03 06:57:56', '2026-02-03 06:57:56'),
(64, 4, 'design engineering', 'Master Job Card', 'ada', '2026-02-03 06:57:56', '2026-02-03 06:57:56'),
(65, 4, 'design engineering', 'WRA / WI', 'ada', '2026-02-03 06:57:56', '2026-02-03 06:57:56'),
(66, 4, 'design engineering', 'Dies', 'ada', '2026-02-03 06:57:56', '2026-02-03 06:57:56'),
(67, 4, 'design engineering', 'Tool', 'ada', '2026-02-03 06:57:56', '2026-02-03 06:57:56'),
(68, 4, 'design engineering', 'Fixtures', '-', '2026-02-03 06:57:56', '2026-02-03 06:57:56'),
(69, 5, 'sales', 'Price', 'ok', '2026-02-03 08:36:03', '2026-02-03 08:36:03'),
(70, 5, 'sales', 'Quantity', '60 pcs', '2026-02-03 08:36:03', '2026-02-03 08:36:03'),
(71, 5, 'sales', 'Delivery Required', 'ok', '2026-02-03 08:36:03', '2026-02-03 08:36:03'),
(72, 5, 'sales', 'Supply Condition', 'ok', '2026-02-03 08:36:03', '2026-02-03 08:36:03'),
(73, 5, 'sales', 'Special / Customer Requirement', 'material :12313', '2026-02-03 08:36:03', '2026-02-03 08:36:03'),
(74, 5, 'quality', 'Drawing', '32.441.026', '2026-02-03 08:36:03', '2026-02-03 08:36:03'),
(75, 5, 'quality', 'Standard / Spec', 'sfwf', '2026-02-03 08:36:03', '2026-02-03 08:36:03'),
(76, 5, 'quality', 'Inspection', 'wefwefwe', '2026-02-03 08:36:03', '2026-02-03 08:36:03'),
(77, 5, 'ppc', 'Material Requirement', '1231231', '2026-02-03 08:36:03', '2026-02-03 08:36:03'),
(78, 5, 'ppc', 'Pattern Wax', '221321', '2026-02-03 08:36:03', '2026-02-03 08:36:03'),
(79, 5, 'ppc', 'Purchasing', '213123', '2026-02-03 08:36:03', '2026-02-03 08:36:03'),
(80, 5, 'ppc', 'Sub Contracting', '-', '2026-02-03 08:36:03', '2026-02-03 08:36:03'),
(81, 5, 'design engineering', 'Master Job Card', 'ada', '2026-02-03 08:36:03', '2026-02-03 08:36:03'),
(82, 5, 'design engineering', 'WRA / WI', 'ada', '2026-02-03 08:36:03', '2026-02-03 08:36:03'),
(83, 5, 'design engineering', 'Dies', 'ada', '2026-02-03 08:36:03', '2026-02-03 08:36:03'),
(84, 5, 'design engineering', 'Tool', 'ada', '2026-02-03 08:36:03', '2026-02-03 08:36:03'),
(85, 5, 'design engineering', 'Fixtures', 'ada', '2026-02-03 08:36:03', '2026-02-03 08:36:03'),
(86, 6, 'sales', 'Price', '503$', '2026-02-04 03:25:13', '2026-03-31 18:15:16'),
(87, 6, 'sales', 'Quantity', '60', '2026-02-04 03:25:13', '2026-02-04 03:25:13'),
(88, 6, 'sales', 'Delivery Required', '01/03/2026', '2026-02-04 03:25:13', '2026-02-04 03:25:13'),
(89, 6, 'sales', 'Supply Condition', 'ok', '2026-02-04 03:25:13', '2026-02-04 03:25:13'),
(90, 6, 'sales', 'Special / Customer Requirement', 'material :12313', '2026-02-04 03:25:13', '2026-02-04 03:25:13'),
(91, 6, 'quality', 'Drawing', '32.441.026', '2026-02-04 03:25:13', '2026-02-04 03:25:13'),
(92, 6, 'quality', 'Standard / Spec', 'din e', '2026-02-04 03:25:13', '2026-02-04 03:25:13'),
(93, 6, 'quality', 'Inspection', '-', '2026-02-04 03:25:13', '2026-02-04 03:25:13'),
(94, 6, 'ppc', 'Material Requirement', 'Material : 1456', '2026-02-04 03:25:13', '2026-02-04 03:25:13'),
(95, 6, 'ppc', 'Pattern Wax', 'Ceramic : -', '2026-02-04 03:25:13', '2026-02-04 03:25:13'),
(96, 6, 'ppc', 'Purchasing', 'Oke', '2026-02-04 03:25:13', '2026-02-04 03:25:13'),
(97, 6, 'ppc', 'Sub Contracting', '-', '2026-02-04 03:25:13', '2026-02-04 03:25:13'),
(98, 6, 'design engineering', 'Master Job Card', 'ada', '2026-02-04 03:25:13', '2026-02-04 03:25:13'),
(99, 6, 'design engineering', 'WRA / WI', 'ada', '2026-02-04 03:25:13', '2026-02-04 03:25:13'),
(100, 6, 'design engineering', 'Dies', 'ada', '2026-02-04 03:25:13', '2026-02-04 03:25:13'),
(101, 6, 'design engineering', 'Tool', 'ada', '2026-02-04 03:25:13', '2026-02-04 03:25:13'),
(102, 6, 'design engineering', 'Fixtures', 'ada', '2026-02-04 03:25:13', '2026-02-04 03:25:13'),
(103, 7, 'sales', 'Price', '50$', '2026-02-04 03:25:19', '2026-02-04 03:25:19'),
(104, 7, 'sales', 'Quantity', '60', '2026-02-04 03:25:19', '2026-02-04 03:25:19'),
(105, 7, 'sales', 'Delivery Required', '01/03/2026', '2026-02-04 03:25:19', '2026-02-04 03:25:19'),
(106, 7, 'sales', 'Supply Condition', 'ok', '2026-02-04 03:25:19', '2026-02-04 03:25:19'),
(107, 7, 'sales', 'Special / Customer Requirement', 'material :12313', '2026-02-04 03:25:19', '2026-02-04 03:25:19'),
(108, 7, 'quality', 'Drawing', '32.441.026', '2026-02-04 03:25:19', '2026-02-04 03:25:19'),
(109, 7, 'quality', 'Standard / Spec', 'din e', '2026-02-04 03:25:19', '2026-02-04 03:25:19'),
(110, 7, 'quality', 'Inspection', '-', '2026-02-04 03:25:19', '2026-02-04 03:25:19'),
(111, 7, 'ppc', 'Material Requirement', 'Material : 1456', '2026-02-04 03:25:19', '2026-02-04 03:25:19'),
(112, 7, 'ppc', 'Pattern Wax', 'Ceramic : -', '2026-02-04 03:25:19', '2026-02-04 03:25:19'),
(113, 7, 'ppc', 'Purchasing', 'Oke', '2026-02-04 03:25:19', '2026-02-04 03:25:19'),
(114, 7, 'ppc', 'Sub Contracting', '-', '2026-02-04 03:25:19', '2026-02-04 03:25:19'),
(115, 7, 'design engineering', 'Master Job Card', 'ada', '2026-02-04 03:25:19', '2026-02-04 03:25:19'),
(116, 7, 'design engineering', 'WRA / WI', 'ada', '2026-02-04 03:25:19', '2026-02-04 03:25:19'),
(117, 7, 'design engineering', 'Dies', 'ada', '2026-02-04 03:25:19', '2026-02-04 03:25:19'),
(118, 7, 'design engineering', 'Tool', 'ada', '2026-02-04 03:25:19', '2026-02-04 03:25:19'),
(119, 7, 'design engineering', 'Fixtures', 'ada', '2026-02-04 03:25:19', '2026-02-04 03:25:19'),
(120, 9, 'sales', 'Price', '50$', '2026-02-13 02:09:40', '2026-02-13 02:09:40'),
(121, 9, 'sales', 'Quantity', '2', '2026-02-13 02:09:40', '2026-02-13 02:09:40'),
(122, 9, 'sales', 'Delivery Required', '20/03/26', '2026-02-13 02:09:40', '2026-02-13 02:09:40'),
(123, 9, 'sales', 'Supply Condition', 'as a supplied', '2026-02-13 02:09:40', '2026-02-13 02:09:40'),
(124, 9, 'sales', 'Special / Customer Requirement', 'material 1.432', '2026-02-13 02:09:40', '2026-02-13 02:09:40'),
(125, 9, 'quality', 'Drawing', '32.441.029', '2026-02-13 02:09:40', '2026-02-13 02:09:40'),
(126, 9, 'quality', 'Standard / Spec', '-', '2026-02-13 02:09:40', '2026-02-13 02:09:40'),
(127, 9, 'quality', 'Inspection', '-', '2026-02-13 02:09:40', '2026-02-13 02:09:40'),
(128, 9, 'ppc', 'Material Requirement', 'material 1.432', '2026-02-13 02:09:40', '2026-02-13 02:09:40'),
(129, 9, 'ppc', 'Pattern Wax', '-', '2026-02-13 02:09:40', '2026-02-13 02:09:40'),
(130, 9, 'ppc', 'Purchasing', '-', '2026-02-13 02:09:40', '2026-02-13 02:09:40'),
(131, 9, 'ppc', 'Sub Contracting', '-', '2026-02-13 02:09:40', '2026-02-13 02:09:40'),
(132, 9, 'design engineering', 'Master Job Card', 'ada', '2026-02-13 02:09:40', '2026-02-13 02:09:40'),
(133, 9, 'design engineering', 'WRA / WI', 'ada', '2026-02-13 02:09:40', '2026-02-13 02:09:40'),
(134, 9, 'design engineering', 'Dies', 'ada', '2026-02-13 02:09:40', '2026-02-13 02:09:40'),
(135, 9, 'design engineering', 'Tool', 'ada', '2026-02-13 02:09:40', '2026-02-13 02:09:40'),
(136, 9, 'design engineering', 'Fixtures', 'ada', '2026-02-13 02:09:40', '2026-02-13 02:09:40'),
(137, 16, 'sales', 'Price', '50', '2026-04-16 14:54:51', '2026-04-16 14:54:51'),
(138, 16, 'sales', 'Quantity', '2', '2026-04-16 14:54:51', '2026-04-16 14:54:51'),
(139, 16, 'sales', 'Delivery Required', '16/04/26', '2026-04-16 14:54:51', '2026-04-16 14:54:51'),
(140, 16, 'sales', 'Supply Condition', 'as supplied', '2026-04-16 14:54:51', '2026-04-16 14:54:51'),
(141, 16, 'sales', 'Special / Customer Requirement', '-', '2026-04-16 14:54:51', '2026-04-16 14:54:51'),
(142, 16, 'quality', 'Drawing', '32.441.026', '2026-04-16 14:54:51', '2026-04-16 14:54:51'),
(143, 16, 'quality', 'Standard / Spec', 'DIN EN 10213', '2026-04-16 14:54:51', '2026-04-16 14:54:51'),
(144, 16, 'quality', 'Inspection', 'Pengiriman dengan d.cert 2.2', '2026-04-16 14:54:51', '2026-04-16 14:54:51'),
(145, 16, 'ppc', 'Material Requirement', '1.4581', '2026-04-16 14:54:51', '2026-04-16 14:54:51'),
(146, 16, 'ppc', 'Pattern Wax', '1.4581 = 149,5 kg', '2026-04-16 14:54:51', '2026-04-16 14:54:51'),
(147, 16, 'ppc', 'Purchasing', '-', '2026-04-16 14:54:51', '2026-04-16 14:54:51'),
(148, 16, 'ppc', 'Sub Contracting', '-', '2026-04-16 14:54:51', '2026-04-16 14:54:51'),
(149, 16, 'design engineering', 'Master Job Card', 'ada', '2026-04-16 14:54:51', '2026-04-16 14:54:51'),
(150, 16, 'design engineering', 'WRA / WI', 'ada', '2026-04-16 14:54:51', '2026-04-16 14:54:51'),
(151, 16, 'design engineering', 'Dies', 'ada', '2026-04-16 14:54:51', '2026-04-16 14:54:51'),
(152, 16, 'design engineering', 'Tool', 'ada', '2026-04-16 14:54:51', '2026-04-16 14:54:51'),
(153, 16, 'design engineering', 'Fixtures', 'ada', '2026-04-16 14:54:51', '2026-04-16 14:54:51');

-- --------------------------------------------------------

--
-- Table structure for table `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint UNSIGNED NOT NULL,
  `uuid` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `connection` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `history_activities`
--

CREATE TABLE `history_activities` (
  `id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED NOT NULL,
  `activity` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `activity_time` datetime NOT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `history_activities`
--

INSERT INTO `history_activities` (`id`, `user_id`, `activity`, `activity_time`, `deleted_at`, `created_at`, `updated_at`) VALUES
(1, 1, 'Membuat user', '2026-02-02 11:22:56', NULL, '2026-02-02 04:22:56', '2026-02-02 04:22:56'),
(2, 1, 'Membuat user', '2026-02-02 11:26:16', NULL, '2026-02-02 04:26:16', '2026-02-02 04:26:16'),
(3, 1, 'Membuat quotation', '2026-02-02 11:28:34', NULL, '2026-02-02 04:28:34', '2026-02-02 04:28:34'),
(4, 1, 'Mengirim quotation', '2026-02-02 11:28:43', NULL, '2026-02-02 04:28:43', '2026-02-02 04:28:43'),
(5, 2, 'Membuat PO', '2026-02-02 11:29:21', NULL, '2026-02-02 04:29:21', '2026-02-02 04:29:21'),
(6, 1, 'Membuat kontrak', '2026-02-02 13:08:30', NULL, '2026-02-02 06:08:30', '2026-02-02 06:08:30'),
(7, 1, 'Membuat user', '2026-02-02 22:24:05', NULL, '2026-02-02 15:24:05', '2026-02-02 15:24:05'),
(8, 1, 'Membuat user', '2026-02-02 22:25:31', NULL, '2026-02-02 15:25:31', '2026-02-02 15:25:31'),
(9, 1, 'Membuat user', '2026-02-02 22:26:20', NULL, '2026-02-02 15:26:20', '2026-02-02 15:26:20'),
(10, 1, 'Membuat user', '2026-02-02 22:27:05', NULL, '2026-02-02 15:27:05', '2026-02-02 15:27:05'),
(11, 3, 'Membuat user', '2026-02-03 01:09:34', NULL, '2026-02-02 18:09:34', '2026-02-02 18:09:34'),
(12, 1, 'Menghapus quotation', '2026-02-03 01:22:00', NULL, '2026-02-02 18:22:00', '2026-02-02 18:22:00'),
(13, 3, 'Membuat user', '2026-02-03 01:22:49', NULL, '2026-02-02 18:22:49', '2026-02-02 18:22:49'),
(14, 4, 'Membuat quotation', '2026-02-03 01:28:50', NULL, '2026-02-02 18:28:50', '2026-02-02 18:28:50'),
(15, 4, 'Mengirim quotation', '2026-02-03 01:28:59', NULL, '2026-02-02 18:28:59', '2026-02-02 18:28:59'),
(16, 9, 'Membuat PO', '2026-02-03 01:30:51', NULL, '2026-02-02 18:30:51', '2026-02-02 18:30:51'),
(17, 3, 'Membuat kontrak', '2026-02-03 01:34:04', NULL, '2026-02-02 18:34:04', '2026-02-02 18:34:04'),
(18, 4, 'Approve kontrak CT-2026-002', '2026-02-03 01:34:26', NULL, '2026-02-02 18:34:26', '2026-02-02 18:34:26'),
(19, 3, 'Membuat user', '2026-02-03 01:37:26', NULL, '2026-02-02 18:37:26', '2026-02-02 18:37:26'),
(20, 3, 'Membuat quotation', '2026-02-03 01:37:53', NULL, '2026-02-02 18:37:53', '2026-02-02 18:37:53'),
(21, 3, 'Mengirim quotation', '2026-02-03 01:38:00', NULL, '2026-02-02 18:38:00', '2026-02-02 18:38:00'),
(22, 10, 'Membuat PO', '2026-02-03 01:38:52', NULL, '2026-02-02 18:38:52', '2026-02-02 18:38:52'),
(23, 3, 'Membuat kontrak', '2026-02-03 01:39:57', NULL, '2026-02-02 18:39:57', '2026-02-02 18:39:57'),
(24, 1, 'Mengupdate user', '2026-02-03 02:02:57', NULL, '2026-02-02 19:02:57', '2026-02-02 19:02:57'),
(25, 3, 'Membuat quotation', '2026-02-03 13:24:41', NULL, '2026-02-03 06:24:41', '2026-02-03 06:24:41'),
(26, 3, 'Mengirim quotation', '2026-02-03 13:47:51', NULL, '2026-02-03 06:47:51', '2026-02-03 06:47:51'),
(27, 2, 'Membuat PO', '2026-02-03 13:51:15', NULL, '2026-02-03 06:51:15', '2026-02-03 06:51:15'),
(28, 3, 'Membuat kontrak', '2026-02-03 13:57:56', NULL, '2026-02-03 06:57:56', '2026-02-03 06:57:56'),
(29, 1, 'Mengupdate user', '2026-02-03 15:17:26', NULL, '2026-02-03 08:17:26', '2026-02-03 08:17:26'),
(30, 1, 'Mengupdate user', '2026-02-03 15:17:57', NULL, '2026-02-03 08:17:57', '2026-02-03 08:17:57'),
(31, 1, 'Mengupdate user', '2026-02-03 15:18:17', NULL, '2026-02-03 08:18:17', '2026-02-03 08:18:17'),
(32, 1, 'Mengupdate user', '2026-02-03 15:18:26', NULL, '2026-02-03 08:18:26', '2026-02-03 08:18:26'),
(33, 1, 'Mengupdate user', '2026-02-03 15:18:42', NULL, '2026-02-03 08:18:42', '2026-02-03 08:18:42'),
(34, 1, 'Mengupdate user', '2026-02-03 15:19:04', NULL, '2026-02-03 08:19:04', '2026-02-03 08:19:04'),
(35, 1, 'Mengupdate user', '2026-02-03 15:19:25', NULL, '2026-02-03 08:19:25', '2026-02-03 08:19:25'),
(36, 1, 'Mengupdate user', '2026-02-03 15:19:58', NULL, '2026-02-03 08:19:58', '2026-02-03 08:19:58'),
(37, 1, 'Mengupdate user', '2026-02-03 15:20:17', NULL, '2026-02-03 08:20:17', '2026-02-03 08:20:17'),
(38, 1, 'Mengupdate user', '2026-02-03 15:20:29', NULL, '2026-02-03 08:20:29', '2026-02-03 08:20:29'),
(39, 1, 'Mengupdate user', '2026-02-03 15:20:43', NULL, '2026-02-03 08:20:43', '2026-02-03 08:20:43'),
(40, 3, 'Membuat user', '2026-02-03 15:27:07', NULL, '2026-02-03 08:27:07', '2026-02-03 08:27:07'),
(41, 3, 'Membuat quotation', '2026-02-03 15:27:55', NULL, '2026-02-03 08:27:55', '2026-02-03 08:27:55'),
(42, 3, 'Mengirim quotation', '2026-02-03 15:33:11', NULL, '2026-02-03 08:33:11', '2026-02-03 08:33:11'),
(43, 11, 'Membuat PO', '2026-02-03 15:33:42', NULL, '2026-02-03 08:33:42', '2026-02-03 08:33:42'),
(44, 3, 'Membuat kontrak', '2026-02-03 15:36:03', NULL, '2026-02-03 08:36:03', '2026-02-03 08:36:03'),
(45, 4, 'Approve kontrak CT-2026-005', '2026-02-03 15:36:49', NULL, '2026-02-03 08:36:49', '2026-02-03 08:36:49'),
(46, 5, 'Approve kontrak CT-2026-005', '2026-02-03 15:38:10', NULL, '2026-02-03 08:38:10', '2026-02-03 08:38:10'),
(47, 6, 'Approve kontrak CT-2026-005', '2026-02-03 15:38:51', NULL, '2026-02-03 08:38:51', '2026-02-03 08:38:51'),
(48, 7, 'Approve kontrak CT-2026-005', '2026-02-03 15:39:25', NULL, '2026-02-03 08:39:25', '2026-02-03 08:39:25'),
(49, 3, 'Update kontrak CT-2026-003', '2026-02-03 18:03:27', NULL, '2026-02-03 11:03:27', '2026-02-03 11:03:27'),
(50, 3, 'Membuat user', '2026-02-04 09:57:57', NULL, '2026-02-04 02:57:57', '2026-02-04 02:57:57'),
(51, 3, 'Membuat quotation', '2026-02-04 10:02:53', NULL, '2026-02-04 03:02:53', '2026-02-04 03:02:53'),
(52, 3, 'Mengirim quotation', '2026-02-04 10:04:40', NULL, '2026-02-04 03:04:40', '2026-02-04 03:04:40'),
(53, 1, 'Mengupdate user', '2026-02-04 10:05:56', NULL, '2026-02-04 03:05:56', '2026-02-04 03:05:56'),
(54, 12, 'Membuat PO', '2026-02-04 10:08:48', NULL, '2026-02-04 03:08:48', '2026-02-04 03:08:48'),
(55, 3, 'Membuat kontrak', '2026-02-04 10:25:13', NULL, '2026-02-04 03:25:13', '2026-02-04 03:25:13'),
(56, 3, 'Membuat kontrak', '2026-02-04 10:25:19', NULL, '2026-02-04 03:25:19', '2026-02-04 03:25:19'),
(57, 3, 'Membuat user', '2026-02-06 14:50:29', NULL, '2026-02-06 07:50:29', '2026-02-06 07:50:29'),
(58, 1, 'Membuat user', '2026-02-06 15:00:30', NULL, '2026-02-06 08:00:30', '2026-02-06 08:00:30'),
(59, 3, 'Membuat quotation', '2026-02-11 16:23:29', NULL, '2026-02-11 09:23:29', '2026-02-11 09:23:29'),
(60, 3, 'Mengirim quotation', '2026-02-11 16:48:42', NULL, '2026-02-11 09:48:42', '2026-02-11 09:48:42'),
(61, 3, 'Membuat quotation', '2026-02-13 08:58:46', NULL, '2026-02-13 01:58:46', '2026-02-13 01:58:46'),
(62, 3, 'Membuat quotation', '2026-02-13 08:58:47', NULL, '2026-02-13 01:58:47', '2026-02-13 01:58:47'),
(63, 3, 'Mengirim quotation', '2026-02-13 08:59:08', NULL, '2026-02-13 01:59:08', '2026-02-13 01:59:08'),
(64, 12, 'Membuat PO', '2026-02-13 09:04:13', NULL, '2026-02-13 02:04:13', '2026-02-13 02:04:13'),
(65, 3, 'Membuat kontrak', '2026-02-13 09:09:40', NULL, '2026-02-13 02:09:40', '2026-02-13 02:09:40'),
(66, 4, 'Approve kontrak CT-2026-008', '2026-02-13 09:11:59', NULL, '2026-02-13 02:11:59', '2026-02-13 02:11:59'),
(67, 5, 'Approve kontrak CT-2026-008', '2026-02-13 09:13:24', NULL, '2026-02-13 02:13:24', '2026-02-13 02:13:24'),
(68, 6, 'Approve kontrak CT-2026-008', '2026-02-13 09:13:37', NULL, '2026-02-13 02:13:37', '2026-02-13 02:13:37'),
(69, 7, 'Approve kontrak CT-2026-008', '2026-02-13 09:13:58', NULL, '2026-02-13 02:13:58', '2026-02-13 02:13:58'),
(70, 3, 'Membuat quotation', '2026-03-07 13:33:17', NULL, '2026-03-07 06:33:17', '2026-03-07 06:33:17'),
(71, 3, 'Membuat quotation', '2026-03-07 13:34:08', NULL, '2026-03-07 06:34:08', '2026-03-07 06:34:08'),
(72, 3, 'Mengirim quotation', '2026-03-07 13:34:46', NULL, '2026-03-07 06:34:46', '2026-03-07 06:34:46'),
(73, 3, 'Membuat quotation', '2026-03-10 12:04:01', NULL, '2026-03-10 05:04:01', '2026-03-10 05:04:01'),
(74, 3, 'Mengirim quotation', '2026-03-10 12:04:18', NULL, '2026-03-10 05:04:18', '2026-03-10 05:04:18'),
(75, 3, 'Membuat quotation', '2026-03-10 13:39:19', NULL, '2026-03-10 06:39:19', '2026-03-10 06:39:19'),
(76, 17, 'Membuat PO', '2026-03-12 05:34:54', NULL, '2026-03-11 22:34:54', '2026-03-11 22:34:54'),
(77, 17, 'Membuat PO', '2026-03-12 06:27:39', NULL, '2026-03-11 23:27:39', '2026-03-11 23:27:39'),
(78, 17, 'Membuat PO', '2026-03-12 06:28:26', NULL, '2026-03-11 23:28:26', '2026-03-11 23:28:26'),
(79, 1, 'Input PO Internal untuk PO QT-2026-03-0002', '2026-03-19 15:39:57', NULL, '2026-03-19 08:39:57', '2026-03-19 08:39:57'),
(80, 1, 'Input PO Internal untuk PO QT-2026-03-0002', '2026-03-19 15:40:59', NULL, '2026-03-19 08:40:59', '2026-03-19 08:40:59'),
(81, 1, 'Input PO Internal untuk PO PO-2026-03-0003', '2026-03-20 11:35:31', NULL, '2026-03-20 04:35:31', '2026-03-20 04:35:31'),
(82, 1, 'Input PO Internal untuk PO QT-2026-03-0002', '2026-03-20 14:49:28', NULL, '2026-03-20 07:49:28', '2026-03-20 07:49:28'),
(83, 1, 'Input PO Internal untuk PO PO-2026-008', '2026-03-20 14:53:59', NULL, '2026-03-20 07:53:59', '2026-03-20 07:53:59'),
(84, 1, 'Input PO Internal untuk PO PO-2026-001', '2026-03-20 15:10:46', NULL, '2026-03-20 08:10:46', '2026-03-20 08:10:46'),
(85, 1, 'Input PO Internal untuk PO PO-2026-002', '2026-03-20 15:16:34', NULL, '2026-03-20 08:16:34', '2026-03-20 08:16:34'),
(86, 1, 'Input PO Internal untuk PO PO-2026-004', '2026-03-20 15:30:12', NULL, '2026-03-20 08:30:12', '2026-03-20 08:30:12'),
(87, 1, 'Input PO Internal untuk PO PO-2026-004', '2026-03-20 15:41:39', NULL, '2026-03-20 08:41:39', '2026-03-20 08:41:39'),
(88, 1, 'Input PO Internal untuk PO PO-2026-007', '2026-03-29 14:09:13', NULL, '2026-03-29 07:09:13', '2026-03-29 07:09:13'),
(89, 1, 'Input PO Internal untuk PO PO-2026-007', '2026-03-29 14:14:46', NULL, '2026-03-29 07:14:46', '2026-03-29 07:14:46'),
(90, 1, 'Update kontrak CT-2026-002', '2026-04-01 01:06:36', NULL, '2026-03-31 18:06:36', '2026-03-31 18:06:36'),
(91, 1, 'Update kontrak CT-2026-006', '2026-04-01 01:15:16', NULL, '2026-03-31 18:15:16', '2026-03-31 18:15:16'),
(92, 5, 'Approve kontrak CT-2026-007', '2026-04-03 10:35:12', NULL, '2026-04-03 03:35:12', '2026-04-03 03:35:12'),
(93, 4, 'Approve kontrak CT-2026-007', '2026-04-03 10:35:59', NULL, '2026-04-03 03:35:59', '2026-04-03 03:35:59'),
(94, 7, 'Approve kontrak CT-2026-007', '2026-04-03 10:36:33', NULL, '2026-04-03 03:36:33', '2026-04-03 03:36:33'),
(95, 6, 'Approve kontrak CT-2026-007', '2026-04-03 10:36:57', NULL, '2026-04-03 03:36:57', '2026-04-03 03:36:57'),
(96, 1, 'Input PO Internal untuk PO PO-2026-007', '2026-04-08 00:20:28', NULL, '2026-04-07 17:20:28', '2026-04-07 17:20:28'),
(97, 1, 'Membuat quotation', '2026-04-10 12:02:12', NULL, '2026-04-10 05:02:12', '2026-04-10 05:02:12'),
(98, 3, 'Mengirim quotation', '2026-04-11 08:24:33', NULL, '2026-04-11 01:24:33', '2026-04-11 01:24:33'),
(99, 7, 'Menerima negosiasi quotation QT-2026-04-0001', '2026-04-11 08:34:27', NULL, '2026-04-11 01:34:27', '2026-04-11 01:34:27'),
(100, 17, 'Mengajukan negosiasi quotation QT-2026-04-0001', '2026-04-11 09:21:05', NULL, '2026-04-11 02:21:05', '2026-04-11 02:21:05'),
(101, 1, 'Menerima negosiasi quotation QT-2026-04-0001', '2026-04-12 01:01:41', NULL, '2026-04-11 18:01:41', '2026-04-11 18:01:41'),
(102, 1, 'Menerima & finalisasi negosiasi quotation QT-2026-04-0001', '2026-04-12 01:52:56', NULL, '2026-04-11 18:52:56', '2026-04-11 18:52:56'),
(103, 17, 'Membuat PO', '2026-04-12 02:53:56', NULL, '2026-04-11 19:53:56', '2026-04-11 19:53:56'),
(104, 1, 'Membuat quotation', '2026-04-12 10:30:42', NULL, '2026-04-12 03:30:42', '2026-04-12 03:30:42'),
(105, 1, 'Membuat quotation', '2026-04-12 11:06:37', NULL, '2026-04-12 04:06:37', '2026-04-12 04:06:37'),
(106, 14, 'Mengirim quotation', '2026-04-12 11:11:05', NULL, '2026-04-12 04:11:05', '2026-04-12 04:11:05'),
(107, 14, 'Mengirim quotation', '2026-04-12 11:11:08', NULL, '2026-04-12 04:11:08', '2026-04-12 04:11:08'),
(108, 14, 'Mengirim quotation', '2026-04-12 11:11:12', NULL, '2026-04-12 04:11:12', '2026-04-12 04:11:12'),
(109, 17, 'Customer mengajukan negosiasi quotation QT-2026-04-0003', '2026-04-12 11:17:08', NULL, '2026-04-12 04:17:08', '2026-04-12 04:17:08'),
(110, 1, 'Membuat quotation', '2026-04-16 21:37:09', NULL, '2026-04-16 14:37:09', '2026-04-16 14:37:09'),
(111, 17, 'Customer mengajukan negosiasi quotation QT-2026-04-0002', '2026-04-16 21:41:40', NULL, '2026-04-16 14:41:40', '2026-04-16 14:41:40'),
(112, 17, 'Customer mengajukan negosiasi quotation QT-2026-04-0002', '2026-04-16 21:42:04', NULL, '2026-04-16 14:42:04', '2026-04-16 14:42:04'),
(113, 1, 'Menerima & finalisasi negosiasi quotation QT-2026-04-0002', '2026-04-16 21:43:37', NULL, '2026-04-16 14:43:37', '2026-04-16 14:43:37'),
(114, 1, 'Input PO Internal untuk PO PO-2026-04-0001', '2026-04-16 21:47:16', NULL, '2026-04-16 14:47:16', '2026-04-16 14:47:16'),
(115, 1, 'Membuat kontrak', '2026-04-16 21:54:51', NULL, '2026-04-16 14:54:51', '2026-04-16 14:54:51'),
(116, 3, 'Membuat quotation', '2026-05-31 02:21:29', NULL, '2026-05-30 19:21:29', '2026-05-30 19:21:29'),
(117, 3, 'Mengirim quotation', '2026-05-31 02:22:19', NULL, '2026-05-30 19:22:19', '2026-05-30 19:22:19'),
(118, 18, 'Customer mengajukan negosiasi quotation QT-2026-05-0001', '2026-05-31 02:28:36', NULL, '2026-05-30 19:28:36', '2026-05-30 19:28:36'),
(119, 3, 'Staff membalas negosiasi quotation QT-2026-05-0001', '2026-05-31 02:33:25', NULL, '2026-05-30 19:33:25', '2026-05-30 19:33:25'),
(120, 18, 'Membuat PO', '2026-05-31 02:42:09', NULL, '2026-05-30 19:42:09', '2026-05-30 19:42:09'),
(121, 4, 'Membuat user', '2026-05-31 11:38:23', NULL, '2026-05-31 04:38:23', '2026-05-31 04:38:23'),
(122, 14, 'Membuat quotation', '2026-05-31 13:31:14', NULL, '2026-05-31 06:31:14', '2026-05-31 06:31:14'),
(123, 14, 'Mengirim quotation', '2026-05-31 13:32:52', NULL, '2026-05-31 06:32:52', '2026-05-31 06:32:52'),
(124, 1, 'Membuat quotation', '2026-05-31 13:40:30', NULL, '2026-05-31 06:40:30', '2026-05-31 06:40:30'),
(125, 4, 'Approve kontrak CT-2026-009', '2026-05-31 13:51:58', NULL, '2026-05-31 06:51:58', '2026-05-31 06:51:58');

-- --------------------------------------------------------

--
-- Table structure for table `jobs`
--

CREATE TABLE `jobs` (
  `id` bigint UNSIGNED NOT NULL,
  `queue` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `attempts` tinyint UNSIGNED NOT NULL,
  `reserved_at` int UNSIGNED DEFAULT NULL,
  `available_at` int UNSIGNED NOT NULL,
  `created_at` int UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `job_batches`
--

CREATE TABLE `job_batches` (
  `id` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `total_jobs` int NOT NULL,
  `pending_jobs` int NOT NULL,
  `failed_jobs` int NOT NULL,
  `failed_job_ids` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `options` mediumtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `cancelled_at` int DEFAULT NULL,
  `created_at` int NOT NULL,
  `finished_at` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` int UNSIGNED NOT NULL,
  `migration` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '0001_01_01_000000_create_users_table', 1),
(2, '0001_01_01_000001_create_cache_table', 1),
(3, '0001_01_01_000002_create_jobs_table', 1),
(4, '2025_12_19_022744_create_quotations_table', 1),
(5, '2025_12_22_135214_create_purchase_orders_table', 1),
(6, '2025_12_23_021210_create_contracts_table', 1),
(7, '2025_12_30_091240_create_notifications_table', 1),
(8, '2026_01_08_102435_add_role_to_users_table', 1),
(9, '2026_01_12_142740_create_contract_requirements_table', 1),
(10, '2026_01_12_171126_add_sent_date_to_quotation', 1),
(11, '2026_01_13_144937_create_history_activities_table', 1),
(12, '2026_01_23_152352_create_request_projects_table', 1),
(13, '2026_01_29_043052_create_request_attachments_table', 1),
(14, '2026_01_29_210832_add_request_id_to_quotations', 1),
(15, '2026_01_30_193633_create_articles_table', 1),
(16, '2026_02_02_110917_add_article_id_to_contracts', 1),
(17, '2026_03_04_115737_drop_sales_id_from_request_projects_table', 2),
(18, '2026_03_04_223205_create_request_project_assignments_table', 3),
(19, '2026_03_06_131554_add_item_qty_to_quotations_table', 4),
(20, '2026_03_06_134804_add_price_to_quotations_table', 5),
(21, '2026_03_06_135640_create_quotation_items_table', 6),
(22, '2026_03_08_111525_account_table', 7),
(23, '2026_03_12_082129_create_purchase_order_items_table', 8),
(24, '2026_03_18_204003_add_columns_to_purchase_order_items_table', 9),
(25, '2026_03_19_112356_create_purchase_order_internals_table', 10),
(26, '2026_03_23_135237_rename_customer_part_no_to_article_no_in_articles_table', 11),
(27, '2026_03_29_134955_rename_satuan_to_article_in_purchase_order_internals_table', 12),
(28, '2026_04_03_101107_add_approver_dates_to_contracts_table', 13),
(29, '2026_04_10_133323_create_negotiate_table', 14),
(30, '2026_04_11_091136_update_status_column_in_quotations_table', 15);

-- --------------------------------------------------------

--
-- Table structure for table `negotiate`
--

CREATE TABLE `negotiate` (
  `id` bigint UNSIGNED NOT NULL,
  `quotation_id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED NOT NULL,
  `from_customer` tinyint(1) NOT NULL DEFAULT '0',
  `message` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `negotiated_total` decimal(15,2) DEFAULT NULL,
  `payment_terms` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `target_delivery_date` date DEFAULT NULL,
  `support_document` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `action` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'negotiate',
  `negotiated_items` json DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `negotiate`
--

INSERT INTO `negotiate` (`id`, `quotation_id`, `user_id`, `from_customer`, `message`, `negotiated_total`, `payment_terms`, `target_delivery_date`, `support_document`, `action`, `negotiated_items`, `created_at`, `updated_at`) VALUES
(1, 14, 17, 1, 'kita mengalami kesulitan keuangan tapi kita butuh.', 383.00, 'dp_50', '2026-04-30', NULL, 'negotiate', '[{\"id\": 11, \"qty\": 2, \"item\": \"Produk A\", \"subtotal\": 98, \"original_price\": \"50.00\", \"negotiated_price\": \"49\"}, {\"id\": 12, \"qty\": 5, \"item\": \"Produk B\", \"subtotal\": 285, \"original_price\": \"60.00\", \"negotiated_price\": \"57\"}]', '2026-04-10 07:09:50', '2026-04-10 07:09:50'),
(2, 14, 7, 0, 'oke', 400.00, NULL, NULL, NULL, 'accept', '[{\"id\": 11, \"qty\": 2, \"item\": \"Produk A\", \"subtotal\": 100, \"original_price\": \"50.00\", \"negotiated_price\": \"50.00\"}, {\"id\": 12, \"qty\": 5, \"item\": \"Produk B\", \"subtotal\": 300, \"original_price\": \"60.00\", \"negotiated_price\": \"60.00\"}]', '2026-04-11 01:34:27', '2026-04-11 01:34:27'),
(3, 14, 17, 1, 'please', 336.00, 'cash', '2026-04-30', NULL, 'negotiate', '[{\"id\": 11, \"qty\": 2, \"item\": \"Produk A\", \"subtotal\": 96, \"original_price\": \"50.00\", \"negotiated_price\": \"48\"}, {\"id\": 12, \"qty\": 5, \"item\": \"Produk B\", \"subtotal\": 240, \"original_price\": \"60.00\", \"negotiated_price\": \"48\"}]', '2026-04-11 01:40:09', '2026-04-11 01:40:09'),
(4, 14, 17, 1, 'tolong', 370.00, 'cash', '2026-04-30', 'negotiates/1775871657_S6 - 201.pdf', 'negotiate', '[{\"id\": 11, \"qty\": 2, \"item\": \"Produk A\", \"subtotal\": 90, \"original_price\": \"50.00\", \"negotiated_price\": \"45\"}, {\"id\": 12, \"qty\": 5, \"item\": \"Produk B\", \"subtotal\": 280, \"original_price\": \"60.00\", \"negotiated_price\": \"56\"}]', '2026-04-11 01:40:58', '2026-04-11 01:40:58'),
(5, 14, 17, 1, 'nego', 372.00, 'cash', '2026-04-30', NULL, 'negotiate', '[{\"id\": 11, \"qty\": 2, \"item\": \"Produk A\", \"subtotal\": 92, \"original_price\": \"50.00\", \"negotiated_price\": \"46\"}, {\"id\": 12, \"qty\": 5, \"item\": \"Produk B\", \"subtotal\": 280, \"original_price\": \"60.00\", \"negotiated_price\": \"56\"}]', '2026-04-11 02:21:05', '2026-04-11 02:21:05'),
(6, 14, 1, 0, 'nego', 372.00, 'cash', '2026-04-30', NULL, 'accept', '[{\"id\": 11, \"qty\": 2, \"item\": \"Produk A\", \"subtotal\": 92, \"original_price\": \"46.00\", \"negotiated_price\": \"46\"}, {\"id\": 12, \"qty\": 5, \"item\": \"Produk B\", \"subtotal\": 280, \"original_price\": \"56.00\", \"negotiated_price\": \"56\"}]', '2026-04-11 18:01:41', '2026-04-11 18:52:56'),
(7, 16, 17, 1, 'please', 3.00, 'cash', '2026-04-30', 'negotiates/1775967427_Struktur CTPAT.pdf', 'negotiate', '[{\"id\": 15, \"qty\": 1, \"item\": \"Produk C\", \"subtotal\": 3, \"original_price\": \"0.03\", \"negotiated_price\": \"3\"}]', '2026-04-12 04:17:08', '2026-04-12 04:17:08'),
(8, 15, 17, 1, 'please help.', 130.00, 'dp_50', '2026-04-30', NULL, 'negotiate', '[{\"id\": 13, \"qty\": 1, \"item\": \"Produk A\", \"subtotal\": 50, \"original_price\": \"0.08\", \"negotiated_price\": \"50\"}, {\"id\": 14, \"qty\": 2, \"item\": \"Produk C\", \"subtotal\": 80, \"original_price\": \"0.04\", \"negotiated_price\": \"40\"}]', '2026-04-16 14:41:40', '2026-04-16 14:41:40'),
(9, 15, 17, 1, 'please help.', 130.00, 'dp_50', '2026-04-30', NULL, 'accept', '[{\"id\": 13, \"qty\": 1, \"item\": \"Produk A\", \"subtotal\": 50, \"original_price\": \"0.08\", \"negotiated_price\": \"50\"}, {\"id\": 14, \"qty\": 2, \"item\": \"Produk C\", \"subtotal\": 80, \"original_price\": \"0.04\", \"negotiated_price\": \"40\"}]', '2026-04-16 14:42:04', '2026-04-16 14:43:37'),
(10, 18, 18, 1, 'we are developing product and we want to order for the next project.', 105.00, 'dp_50', '2026-06-06', NULL, 'negotiate', '[{\"id\": 19, \"qty\": 2, \"item\": \"Produk A\", \"subtotal\": 30, \"original_price\": \"20.00\", \"negotiated_price\": \"15.00\"}, {\"id\": 20, \"qty\": 3, \"item\": \"Produk B\", \"subtotal\": 75, \"original_price\": \"30.00\", \"negotiated_price\": \"25.00\"}]', '2026-05-30 19:28:36', '2026-05-30 19:28:36'),
(11, 18, 3, 0, 'OKE', 105.00, 'dp_50', '2026-06-06', NULL, 'negotiate', '[{\"id\": 19, \"qty\": 2, \"item\": \"Produk A\", \"subtotal\": 30, \"original_price\": \"20.00\", \"negotiated_price\": \"15.00\"}, {\"id\": 20, \"qty\": 3, \"item\": \"Produk B\", \"subtotal\": 75, \"original_price\": \"30.00\", \"negotiated_price\": \"25.00\"}]', '2026-05-30 19:33:25', '2026-05-30 19:33:25');

-- --------------------------------------------------------

--
-- Table structure for table `notifications`
--

CREATE TABLE `notifications` (
  `id` char(36) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `type` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `notifiable_type` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `notifiable_id` bigint UNSIGNED NOT NULL,
  `data` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `read_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `notifications`
--

INSERT INTO `notifications` (`id`, `type`, `notifiable_type`, `notifiable_id`, `data`, `read_at`, `created_at`, `updated_at`) VALUES
('035b0d12-5b43-4579-8f4a-dd46ba3fc5dc', 'App\\Notifications\\ContractApprovedNotification', 'App\\Models\\User', 14, '{\"contract_id\":9,\"order_no\":\"PO-2026-007\",\"customer_id\":12,\"message\":\"Contract di approve oleh manager Design Engineering\",\"url\":\"http:\\/\\/sales_metinca.test\\/contracts\\/9\"}', NULL, '2026-02-13 02:13:58', '2026-02-13 02:13:58'),
('03fb3e17-8c23-4649-961d-c1d4cd8ebc76', 'App\\Notifications\\ContractApprovedNotification', 'App\\Models\\User', 14, '{\"contract_id\":7,\"order_no\":\"123424\",\"customer_id\":12,\"message\":\"Contract di approve oleh manager Sales\",\"url\":\"http:\\/\\/sales_metinca.test\\/contracts\\/7\"}', NULL, '2026-04-03 03:35:59', '2026-04-03 03:35:59'),
('0637898d-403a-4fca-82d8-b8c956c87f9c', 'App\\Notifications\\QuotationSendNotification', 'App\\Models\\User', 17, '{\"quotation_id\":15,\"quotation_no\":\"QT-2026-04-0002\",\"status\":\"sent\",\"message\":\"Quotation telah dikirim dan menunggu konfirmasi Anda.\",\"url\":\"http:\\/\\/sales_metinca.test\\/quotations\\/15\"}', '2026-04-16 14:39:01', '2026-04-12 04:11:12', '2026-04-16 14:39:01'),
('072e4231-99bd-4e42-a98d-1af8f244c07f', 'App\\Notifications\\ContractApprovedNotification', 'App\\Models\\User', 14, '{\"contract_id\":9,\"order_no\":\"PO-2026-007\",\"customer_id\":12,\"message\":\"Contract di approve oleh manager PPC\",\"url\":\"http:\\/\\/sales_metinca.test\\/contracts\\/9\"}', NULL, '2026-02-13 02:13:37', '2026-02-13 02:13:37'),
('0a8c921b-ed5a-4704-86a0-2306bf58be24', 'App\\Notifications\\ContractNotification', 'App\\Models\\User', 6, '{\"contract_id\":16,\"order_no\":\"PO-2026-04-0001\",\"customer_id\":\"17\",\"message\":\"Contract membutuhkan approval manager.\",\"url\":\"http:\\/\\/sales_metinca.test\\/contracts\\/16\"}', NULL, '2026-04-16 14:54:53', '2026-04-16 14:54:53'),
('0d848bf7-f77b-47b6-83c2-3f15b6313957', 'App\\Notifications\\ContractApprovedNotification', 'App\\Models\\User', 3, '{\"contract_id\":9,\"order_no\":\"PO-2026-007\",\"customer_id\":12,\"message\":\"Contract di approve oleh manager PPC\",\"url\":\"http:\\/\\/sales_metinca.test\\/contracts\\/9\"}', '2026-05-30 19:44:53', '2026-02-13 02:13:37', '2026-05-30 19:44:53'),
('13ea431b-aea9-4aff-a5f6-4c369605a616', 'App\\Notifications\\ContractNotification', 'App\\Models\\User', 4, '{\"contract_id\":5,\"order_no\":\"123424\",\"customer_id\":\"11\",\"message\":\"Contract membutuhkan approval manager.\",\"url\":\"http:\\/\\/sales_metinca.test\\/contracts\\/5\"}', '2026-02-03 08:36:42', '2026-02-03 08:36:03', '2026-02-03 08:36:42'),
('144af49f-1976-4628-8ffa-036a79230df6', 'App\\Notifications\\ContractNotification', 'App\\Models\\User', 5, '{\"contract_id\":16,\"order_no\":\"PO-2026-04-0001\",\"customer_id\":\"17\",\"message\":\"Contract membutuhkan approval manager.\",\"url\":\"http:\\/\\/sales_metinca.test\\/contracts\\/16\"}', NULL, '2026-04-16 14:54:53', '2026-04-16 14:54:53'),
('17ac99a5-7062-4774-bd12-8d8479efca92', 'App\\Notifications\\ContractNotification', 'App\\Models\\User', 4, '{\"contract_id\":6,\"order_no\":\"123424\",\"customer_id\":\"12\",\"message\":\"Contract membutuhkan approval manager.\",\"url\":\"http:\\/\\/sales_metinca.test\\/contracts\\/6\"}', NULL, '2026-02-04 03:25:16', '2026-02-04 03:25:16'),
('17b0c1aa-0739-47f3-ad15-6428a5221141', 'App\\Notifications\\ContractApprovedNotification', 'App\\Models\\User', 3, '{\"contract_id\":7,\"order_no\":\"123424\",\"customer_id\":12,\"message\":\"Contract di approve oleh manager Quality\",\"url\":\"http:\\/\\/sales_metinca.test\\/contracts\\/7\"}', '2026-05-30 19:44:53', '2026-04-03 03:35:12', '2026-05-30 19:44:53'),
('26d935e5-571d-4575-b303-e7cd55736b67', 'App\\Notifications\\QuotationSendNotification', 'App\\Models\\User', 2, '{\"quotation_id\":4,\"quotation_no\":\"QT-2026-004\",\"status\":\"sent\",\"message\":\"Quotation telah dikirim dan menunggu konfirmasi Anda.\",\"url\":\"http:\\/\\/sales_metinca.test\\/quotations\\/4\"}', '2026-02-03 06:48:06', '2026-02-03 06:47:51', '2026-02-03 06:48:06'),
('311d7d84-0a24-498c-a1e7-52078308af77', 'App\\Notifications\\ContractApprovedNotification', 'App\\Models\\User', 14, '{\"contract_id\":9,\"order_no\":\"PO-2026-007\",\"customer_id\":12,\"message\":\"Contract di approve oleh manager Quality\",\"url\":\"http:\\/\\/sales_metinca.test\\/contracts\\/9\"}', NULL, '2026-02-13 02:13:24', '2026-02-13 02:13:24'),
('36da2f79-0206-465d-849f-4053ab322614', 'App\\Notifications\\ContractApprovedNotification', 'App\\Models\\User', 3, '{\"contract_id\":7,\"order_no\":\"123424\",\"customer_id\":12,\"message\":\"Contract di approve oleh manager Design Engineering\",\"url\":\"http:\\/\\/sales_metinca.test\\/contracts\\/7\"}', '2026-05-30 19:44:53', '2026-04-03 03:36:33', '2026-05-30 19:44:53'),
('3b5cb7c7-0448-4e2f-9704-3b84871cc280', 'App\\Notifications\\ContractNotification', 'App\\Models\\User', 6, '{\"contract_id\":4,\"order_no\":\"34\",\"customer_id\":\"2\",\"message\":\"Contract membutuhkan approval manager.\",\"url\":\"http:\\/\\/sales_metinca.test\\/contracts\\/4\"}', NULL, '2026-02-03 06:57:56', '2026-02-03 06:57:56'),
('3ea13485-4e74-44a9-a396-434461bb0d60', 'App\\Notifications\\ContractNotification', 'App\\Models\\User', 4, '{\"contract_id\":4,\"order_no\":\"34\",\"customer_id\":\"2\",\"message\":\"Contract membutuhkan approval manager.\",\"url\":\"http:\\/\\/sales_metinca.test\\/contracts\\/4\"}', NULL, '2026-02-03 06:57:56', '2026-02-03 06:57:56'),
('41476031-7b6b-4da7-9312-2a4bb17382bb', 'App\\Notifications\\QuotationSendNotification', 'App\\Models\\User', 12, '{\"quotation_id\":9,\"quotation_no\":\"QT-2026-009\",\"status\":\"sent\",\"message\":\"Quotation telah dikirim dan menunggu konfirmasi Anda.\",\"url\":\"http:\\/\\/sales_metinca.test\\/quotations\\/9\"}', NULL, '2026-02-13 01:59:08', '2026-02-13 01:59:08'),
('41794c97-587a-4b69-abf2-763d3c66b89b', 'App\\Notifications\\ContractNotification', 'App\\Models\\User', 7, '{\"contract_id\":5,\"order_no\":\"123424\",\"customer_id\":\"11\",\"message\":\"Contract membutuhkan approval manager.\",\"url\":\"http:\\/\\/sales_metinca.test\\/contracts\\/5\"}', NULL, '2026-02-03 08:36:03', '2026-02-03 08:36:03'),
('4577aa51-2fea-4ee0-875d-5063ceca8534', 'App\\Notifications\\ContractApprovedNotification', 'App\\Models\\User', 3, '{\"contract_id\":5,\"order_no\":\"123424\",\"customer_id\":11,\"message\":\"Contract di approve oleh manager Sales\",\"url\":\"http:\\/\\/sales_metinca.test\\/contracts\\/5\"}', '2026-05-30 19:44:53', '2026-02-03 08:36:49', '2026-05-30 19:44:53'),
('53166c67-b9fd-498d-b41c-cc0a0cb6ca32', 'App\\Notifications\\ContractApprovedNotification', 'App\\Models\\User', 3, '{\"contract_id\":7,\"order_no\":\"123424\",\"customer_id\":12,\"message\":\"Contract di approve oleh manager Sales\",\"url\":\"http:\\/\\/sales_metinca.test\\/contracts\\/7\"}', '2026-05-30 19:44:53', '2026-04-03 03:35:59', '2026-05-30 19:44:53'),
('54b7cd94-c993-491b-8234-f1bbe48ad2da', 'App\\Notifications\\ContractNotification', 'App\\Models\\User', 4, '{\"contract_id\":2,\"order_no\":\"OR-2026\",\"customer_id\":\"9\",\"message\":\"Contract membutuhkan approval manager.\",\"url\":\"http:\\/\\/sales_metinca.test\\/contracts\\/2\"}', NULL, '2026-02-02 18:34:04', '2026-02-02 18:34:04'),
('550c4a6b-f707-4e87-86cd-78ceef12824f', 'App\\Notifications\\QuotationSendNotification', 'App\\Models\\User', 18, '{\"quotation_id\":18,\"quotation_no\":\"QT-2026-05-0001\",\"status\":\"sent\",\"message\":\"Quotation telah dikirim dan menunggu konfirmasi Anda.\",\"url\":\"http:\\/\\/sales_metinca.test\\/quotations\\/18\"}', '2026-05-30 19:22:28', '2026-05-30 19:22:19', '2026-05-30 19:22:28'),
('551977a0-0ae1-4788-90de-f289a723b8ed', 'App\\Notifications\\QuotationSendNotification', 'App\\Models\\User', 12, '{\"quotation_id\":6,\"quotation_no\":\"QT-2026-006\",\"status\":\"sent\",\"message\":\"Quotation telah dikirim dan menunggu konfirmasi Anda.\",\"url\":\"http:\\/\\/sales_metinca.test\\/quotations\\/6\"}', '2026-02-06 07:38:51', '2026-02-04 03:04:40', '2026-02-06 07:38:51'),
('5aacc286-559d-46d8-b583-dcaa56793314', 'App\\Notifications\\ContractApprovedNotification', 'App\\Models\\User', 14, '{\"contract_id\":16,\"order_no\":\"PO-2026-04-0001\",\"customer_id\":17,\"message\":\"Contract di approve oleh manager Sales\",\"url\":\"http:\\/\\/sales_metinca.test\\/contracts\\/16\"}', NULL, '2026-05-31 06:51:58', '2026-05-31 06:51:58'),
('5cf6ba4e-022a-4f9f-82ec-4bf1219b5eac', 'App\\Notifications\\ContractApprovedNotification', 'App\\Models\\User', 3, '{\"contract_id\":9,\"order_no\":\"PO-2026-007\",\"customer_id\":12,\"message\":\"Contract di approve oleh manager Sales\",\"url\":\"http:\\/\\/sales_metinca.test\\/contracts\\/9\"}', '2026-05-30 19:44:53', '2026-02-13 02:11:59', '2026-05-30 19:44:53'),
('5d424412-46de-4ef9-aef7-0938450e8d90', 'App\\Notifications\\QuotationSendNotification', 'App\\Models\\User', 11, '{\"quotation_id\":5,\"quotation_no\":\"QT-2026-005\",\"status\":\"sent\",\"message\":\"Quotation telah dikirim dan menunggu konfirmasi Anda.\",\"url\":\"http:\\/\\/sales_metinca.test\\/quotations\\/5\"}', NULL, '2026-02-03 08:33:11', '2026-02-03 08:33:11'),
('617d75fa-a8be-42a3-8566-61be4b51ae0b', 'App\\Notifications\\ContractApprovedNotification', 'App\\Models\\User', 3, '{\"contract_id\":5,\"order_no\":\"123424\",\"customer_id\":11,\"message\":\"Contract di approve oleh manager PPC\",\"url\":\"http:\\/\\/sales_metinca.test\\/contracts\\/5\"}', '2026-02-06 07:48:44', '2026-02-03 08:38:51', '2026-02-06 07:48:44'),
('62488f67-a832-4a67-92bf-27a8590a6c4c', 'App\\Notifications\\ContractNotification', 'App\\Models\\User', 6, '{\"contract_id\":6,\"order_no\":\"123424\",\"customer_id\":\"12\",\"message\":\"Contract membutuhkan approval manager.\",\"url\":\"http:\\/\\/sales_metinca.test\\/contracts\\/6\"}', NULL, '2026-02-04 03:25:16', '2026-02-04 03:25:16'),
('65a49107-7f98-40b7-a02e-acbff78d6ceb', 'App\\Notifications\\ContractApprovedNotification', 'App\\Models\\User', 14, '{\"contract_id\":7,\"order_no\":\"123424\",\"customer_id\":12,\"message\":\"Contract di approve oleh manager Quality\",\"url\":\"http:\\/\\/sales_metinca.test\\/contracts\\/7\"}', NULL, '2026-04-03 03:35:12', '2026-04-03 03:35:12'),
('7040662e-96c9-4614-bb8b-aca47ab35860', 'App\\Notifications\\ContractNotification', 'App\\Models\\User', 6, '{\"contract_id\":2,\"order_no\":\"OR-2026\",\"customer_id\":\"9\",\"message\":\"Contract membutuhkan approval manager.\",\"url\":\"http:\\/\\/sales_metinca.test\\/contracts\\/2\"}', NULL, '2026-02-02 18:34:04', '2026-02-02 18:34:04'),
('79383389-d982-45fb-9aa6-8c306e54d4c8', 'App\\Notifications\\ContractApprovedNotification', 'App\\Models\\User', 3, '{\"contract_id\":16,\"order_no\":\"PO-2026-04-0001\",\"customer_id\":17,\"message\":\"Contract di approve oleh manager Sales\",\"url\":\"http:\\/\\/sales_metinca.test\\/contracts\\/16\"}', NULL, '2026-05-31 06:51:58', '2026-05-31 06:51:58'),
('7bcb9265-c6fd-4965-8591-11c3079f0af2', 'App\\Notifications\\ContractNotification', 'App\\Models\\User', 7, '{\"contract_id\":3,\"order_no\":\"OR-2026-QT003\",\"customer_id\":\"10\",\"message\":\"Contract membutuhkan approval manager.\",\"url\":\"http:\\/\\/sales_metinca.test\\/contracts\\/3\"}', NULL, '2026-02-02 18:39:57', '2026-02-02 18:39:57'),
('7fcfdaf0-1952-4f00-bfac-0a97824b4908', 'App\\Notifications\\ContractNotification', 'App\\Models\\User', 7, '{\"contract_id\":16,\"order_no\":\"PO-2026-04-0001\",\"customer_id\":\"17\",\"message\":\"Contract membutuhkan approval manager.\",\"url\":\"http:\\/\\/sales_metinca.test\\/contracts\\/16\"}', NULL, '2026-04-16 14:54:53', '2026-04-16 14:54:53'),
('7fd50189-9064-49aa-9718-bf2d6969d2ce', 'App\\Notifications\\QuotationSendNotification', 'App\\Models\\User', 2, '{\"quotation_id\":1,\"quotation_no\":\"QT-2026-001\",\"status\":\"sent\",\"message\":\"Quotation telah dikirim dan menunggu konfirmasi Anda.\",\"url\":\"http:\\/\\/sales_metinca.test\\/quotations\\/1\"}', NULL, '2026-02-02 04:28:43', '2026-02-02 04:28:43'),
('83cc37f2-326a-4a06-9dc0-8bd6d51a2e3c', 'App\\Notifications\\ContractApprovedNotification', 'App\\Models\\User', 3, '{\"contract_id\":9,\"order_no\":\"PO-2026-007\",\"customer_id\":12,\"message\":\"Contract di approve oleh manager Design Engineering\",\"url\":\"http:\\/\\/sales_metinca.test\\/contracts\\/9\"}', '2026-05-30 19:44:53', '2026-02-13 02:13:58', '2026-05-30 19:44:53'),
('899ea0c6-fd1b-405d-ada8-36aed5b7f8c9', 'App\\Notifications\\ContractNotification', 'App\\Models\\User', 7, '{\"contract_id\":7,\"order_no\":\"123424\",\"customer_id\":\"12\",\"message\":\"Contract membutuhkan approval manager.\",\"url\":\"http:\\/\\/sales_metinca.test\\/contracts\\/7\"}', NULL, '2026-02-04 03:25:19', '2026-02-04 03:25:19'),
('8b015b16-26a1-46d5-a518-9b4663701305', 'App\\Notifications\\ContractNotification', 'App\\Models\\User', 5, '{\"contract_id\":9,\"order_no\":\"PO-2026-007\",\"customer_id\":\"12\",\"message\":\"Contract membutuhkan approval manager.\",\"url\":\"http:\\/\\/sales_metinca.test\\/contracts\\/9\"}', NULL, '2026-02-13 02:09:41', '2026-02-13 02:09:41'),
('8b239d5e-737a-4ee3-a993-fc12ef09747b', 'App\\Notifications\\ContractNotification', 'App\\Models\\User', 7, '{\"contract_id\":6,\"order_no\":\"123424\",\"customer_id\":\"12\",\"message\":\"Contract membutuhkan approval manager.\",\"url\":\"http:\\/\\/sales_metinca.test\\/contracts\\/6\"}', NULL, '2026-02-04 03:25:16', '2026-02-04 03:25:16'),
('8ea871c6-4e12-4469-a71b-deccacddfad8', 'App\\Notifications\\QuotationSendNotification', 'App\\Models\\User', 17, '{\"quotation_id\":16,\"quotation_no\":\"QT-2026-04-0003\",\"status\":\"sent\",\"message\":\"Quotation telah dikirim dan menunggu konfirmasi Anda.\",\"url\":\"http:\\/\\/sales_metinca.test\\/quotations\\/16\"}', NULL, '2026-04-12 04:11:05', '2026-04-12 04:11:05'),
('9192122b-55f5-4638-9e6e-3181224aa244', 'App\\Notifications\\ContractApprovedNotification', 'App\\Models\\User', 14, '{\"contract_id\":9,\"order_no\":\"PO-2026-007\",\"customer_id\":12,\"message\":\"Contract di approve oleh manager Sales\",\"url\":\"http:\\/\\/sales_metinca.test\\/contracts\\/9\"}', NULL, '2026-02-13 02:11:59', '2026-02-13 02:11:59'),
('99d525c5-412b-47cf-96f2-c18fb021408e', 'App\\Notifications\\ContractNotification', 'App\\Models\\User', 4, '{\"contract_id\":7,\"order_no\":\"123424\",\"customer_id\":\"12\",\"message\":\"Contract membutuhkan approval manager.\",\"url\":\"http:\\/\\/sales_metinca.test\\/contracts\\/7\"}', NULL, '2026-02-04 03:25:19', '2026-02-04 03:25:19'),
('9cc40f3e-f30c-4d44-95ba-824f46746076', 'App\\Notifications\\ContractNotification', 'App\\Models\\User', 5, '{\"contract_id\":2,\"order_no\":\"OR-2026\",\"customer_id\":\"9\",\"message\":\"Contract membutuhkan approval manager.\",\"url\":\"http:\\/\\/sales_metinca.test\\/contracts\\/2\"}', NULL, '2026-02-02 18:34:04', '2026-02-02 18:34:04'),
('a88a6b8c-94cb-4502-8cb7-1a72e193323c', 'App\\Notifications\\ContractNotification', 'App\\Models\\User', 4, '{\"contract_id\":9,\"order_no\":\"PO-2026-007\",\"customer_id\":\"12\",\"message\":\"Contract membutuhkan approval manager.\",\"url\":\"http:\\/\\/sales_metinca.test\\/contracts\\/9\"}', NULL, '2026-02-13 02:09:41', '2026-02-13 02:09:41'),
('ac8d07e9-6160-4b77-9bf2-0481f119db22', 'App\\Notifications\\QuotationSendNotification', 'App\\Models\\User', 17, '{\"quotation_id\":14,\"quotation_no\":\"QT-2026-04-0001\",\"status\":\"sent\",\"message\":\"Quotation telah dikirim dan menunggu konfirmasi Anda.\",\"url\":\"http:\\/\\/sales_metinca.test\\/quotations\\/14\"}', NULL, '2026-04-11 01:24:33', '2026-04-11 01:24:33'),
('b1408fa8-c8e9-41ea-b634-e59f63464a70', 'App\\Notifications\\QuotationSendNotification', 'App\\Models\\User', 17, '{\"quotation_id\":16,\"quotation_no\":\"QT-2026-04-0003\",\"status\":\"sent\",\"message\":\"Quotation telah dikirim dan menunggu konfirmasi Anda.\",\"url\":\"http:\\/\\/sales_metinca.test\\/quotations\\/16\"}', NULL, '2026-04-12 04:11:08', '2026-04-12 04:11:08'),
('b82e32c6-c811-4665-be3f-517e35b37f8e', 'App\\Notifications\\ContractNotification', 'App\\Models\\User', 6, '{\"contract_id\":3,\"order_no\":\"OR-2026-QT003\",\"customer_id\":\"10\",\"message\":\"Contract membutuhkan approval manager.\",\"url\":\"http:\\/\\/sales_metinca.test\\/contracts\\/3\"}', NULL, '2026-02-02 18:39:57', '2026-02-02 18:39:57'),
('b83692ae-1a82-4a27-bb6d-ae17dba0fd01', 'App\\Notifications\\ContractApprovedNotification', 'App\\Models\\User', 3, '{\"contract_id\":9,\"order_no\":\"PO-2026-007\",\"customer_id\":12,\"message\":\"Contract di approve oleh manager Quality\",\"url\":\"http:\\/\\/sales_metinca.test\\/contracts\\/9\"}', '2026-05-30 19:44:53', '2026-02-13 02:13:24', '2026-05-30 19:44:53'),
('b93f7bd8-d37a-44e9-8f92-664b23121e67', 'App\\Notifications\\ContractNotification', 'App\\Models\\User', 7, '{\"contract_id\":4,\"order_no\":\"34\",\"customer_id\":\"2\",\"message\":\"Contract membutuhkan approval manager.\",\"url\":\"http:\\/\\/sales_metinca.test\\/contracts\\/4\"}', NULL, '2026-02-03 06:57:56', '2026-02-03 06:57:56'),
('bc302923-4a25-41ed-be84-d01ee59ed9da', 'App\\Notifications\\QuotationSendNotification', 'App\\Models\\User', 9, '{\"quotation_id\":2,\"quotation_no\":\"QT-2026-002\",\"status\":\"sent\",\"message\":\"Quotation telah dikirim dan menunggu konfirmasi Anda.\",\"url\":\"http:\\/\\/sales_metinca.test\\/quotations\\/2\"}', '2026-02-02 18:30:25', '2026-02-02 18:28:59', '2026-02-02 18:30:25'),
('bc7aef13-78f3-4f91-992a-908a13f5c794', 'App\\Notifications\\ContractNotification', 'App\\Models\\User', 7, '{\"contract_id\":2,\"order_no\":\"OR-2026\",\"customer_id\":\"9\",\"message\":\"Contract membutuhkan approval manager.\",\"url\":\"http:\\/\\/sales_metinca.test\\/contracts\\/2\"}', NULL, '2026-02-02 18:34:04', '2026-02-02 18:34:04'),
('c275a4db-6a65-4a7c-a989-ea914bd55e10', 'App\\Notifications\\QuotationSendNotification', 'App\\Models\\User', 10, '{\"quotation_id\":3,\"quotation_no\":\"QT-2026-003\",\"status\":\"sent\",\"message\":\"Quotation telah dikirim dan menunggu konfirmasi Anda.\",\"url\":\"http:\\/\\/sales_metinca.test\\/quotations\\/3\"}', NULL, '2026-02-02 18:38:00', '2026-02-02 18:38:00'),
('c30ad298-79c0-4096-8649-0386df2e67a5', 'App\\Notifications\\ContractNotification', 'App\\Models\\User', 4, '{\"contract_id\":3,\"order_no\":\"OR-2026-QT003\",\"customer_id\":\"10\",\"message\":\"Contract membutuhkan approval manager.\",\"url\":\"http:\\/\\/sales_metinca.test\\/contracts\\/3\"}', NULL, '2026-02-02 18:39:57', '2026-02-02 18:39:57'),
('c52e49e4-44d1-4d3e-bed8-4391f7aa7b5e', 'App\\Notifications\\ContractNotification', 'App\\Models\\User', 5, '{\"contract_id\":6,\"order_no\":\"123424\",\"customer_id\":\"12\",\"message\":\"Contract membutuhkan approval manager.\",\"url\":\"http:\\/\\/sales_metinca.test\\/contracts\\/6\"}', NULL, '2026-02-04 03:25:16', '2026-02-04 03:25:16'),
('c6da5795-2b00-48f3-9cae-e7ec7593570b', 'App\\Notifications\\ContractApprovedNotification', 'App\\Models\\User', 3, '{\"contract_id\":5,\"order_no\":\"123424\",\"customer_id\":11,\"message\":\"Contract di approve oleh manager Design Engineering\",\"url\":\"http:\\/\\/sales_metinca.test\\/contracts\\/5\"}', '2026-02-06 07:48:33', '2026-02-03 08:39:25', '2026-02-06 07:48:33'),
('c9084ff7-1063-4985-bd43-2ac4d38172cf', 'App\\Notifications\\ContractNotification', 'App\\Models\\User', 5, '{\"contract_id\":5,\"order_no\":\"123424\",\"customer_id\":\"11\",\"message\":\"Contract membutuhkan approval manager.\",\"url\":\"http:\\/\\/sales_metinca.test\\/contracts\\/5\"}', '2026-02-03 08:37:39', '2026-02-03 08:36:03', '2026-02-03 08:37:39'),
('c9abfd28-471f-416a-8ecc-5b092e3eeafd', 'App\\Notifications\\ContractApprovedNotification', 'App\\Models\\User', 3, '{\"contract_id\":7,\"order_no\":\"123424\",\"customer_id\":12,\"message\":\"Contract di approve oleh manager PPC\",\"url\":\"http:\\/\\/sales_metinca.test\\/contracts\\/7\"}', '2026-05-30 19:44:53', '2026-04-03 03:36:57', '2026-05-30 19:44:53'),
('cb79b1ce-4232-45be-bd78-70120929f4c0', 'App\\Notifications\\ContractApprovedNotification', 'App\\Models\\User', 14, '{\"contract_id\":7,\"order_no\":\"123424\",\"customer_id\":12,\"message\":\"Contract di approve oleh manager Design Engineering\",\"url\":\"http:\\/\\/sales_metinca.test\\/contracts\\/7\"}', NULL, '2026-04-03 03:36:33', '2026-04-03 03:36:33'),
('d504deee-7210-49d9-b6ee-c88a3d63edd2', 'App\\Notifications\\ContractNotification', 'App\\Models\\User', 5, '{\"contract_id\":7,\"order_no\":\"123424\",\"customer_id\":\"12\",\"message\":\"Contract membutuhkan approval manager.\",\"url\":\"http:\\/\\/sales_metinca.test\\/contracts\\/7\"}', NULL, '2026-02-04 03:25:19', '2026-02-04 03:25:19'),
('d7788360-98e0-4f47-939b-314cf751ab75', 'App\\Notifications\\ContractApprovedNotification', 'App\\Models\\User', 14, '{\"contract_id\":7,\"order_no\":\"123424\",\"customer_id\":12,\"message\":\"Contract di approve oleh manager PPC\",\"url\":\"http:\\/\\/sales_metinca.test\\/contracts\\/7\"}', NULL, '2026-04-03 03:36:57', '2026-04-03 03:36:57'),
('dd79b6b8-6d2a-4607-8139-127c35e42135', 'App\\Notifications\\ContractNotification', 'App\\Models\\User', 6, '{\"contract_id\":7,\"order_no\":\"123424\",\"customer_id\":\"12\",\"message\":\"Contract membutuhkan approval manager.\",\"url\":\"http:\\/\\/sales_metinca.test\\/contracts\\/7\"}', NULL, '2026-02-04 03:25:19', '2026-02-04 03:25:19'),
('e10e955a-20e7-407b-8a35-8142d1814505', 'App\\Notifications\\QuotationSendNotification', 'App\\Models\\User', 18, '{\"quotation_id\":19,\"quotation_no\":\"QT-2026-05-0002\",\"status\":\"sent\",\"message\":\"Quotation telah dikirim dan menunggu konfirmasi Anda.\",\"url\":\"http:\\/\\/sales_metinca.test\\/quotations\\/19\"}', NULL, '2026-05-31 06:32:52', '2026-05-31 06:32:52'),
('e11dd2af-2eca-4e4e-803d-98b86a6df788', 'App\\Notifications\\ContractNotification', 'App\\Models\\User', 7, '{\"contract_id\":9,\"order_no\":\"PO-2026-007\",\"customer_id\":\"12\",\"message\":\"Contract membutuhkan approval manager.\",\"url\":\"http:\\/\\/sales_metinca.test\\/contracts\\/9\"}', NULL, '2026-02-13 02:09:41', '2026-02-13 02:09:41'),
('e217ee0e-28b2-454a-8657-559002c45ee0', 'App\\Notifications\\ContractApprovedNotification', 'App\\Models\\User', 3, '{\"contract_id\":2,\"order_no\":\"OR-2026\",\"customer_id\":9,\"message\":\"Contract di approve oleh manager Sales\",\"url\":\"http:\\/\\/sales_metinca.test\\/contracts\\/2\"}', '2026-02-03 08:26:03', '2026-02-02 18:34:26', '2026-02-03 08:26:03'),
('e519cba1-c4af-49ae-970d-bf819f915999', 'App\\Notifications\\QuotationSendNotification', 'App\\Models\\User', 12, '{\"quotation_id\":7,\"quotation_no\":\"QT-2026-007\",\"status\":\"sent\",\"message\":\"Quotation telah dikirim dan menunggu konfirmasi Anda.\",\"url\":\"http:\\/\\/sales_metinca.test\\/quotations\\/7\"}', '2026-02-11 10:05:03', '2026-02-11 09:48:42', '2026-02-11 10:05:03'),
('e6d3636c-9d7e-4ac1-be57-704892729acc', 'App\\Notifications\\QuotationSendNotification', 'App\\Models\\User', 17, '{\"quotation_id\":11,\"quotation_no\":\"QT-2026-03-0002\",\"status\":\"sent\",\"message\":\"Quotation telah dikirim dan menunggu konfirmasi Anda.\",\"url\":\"http:\\/\\/sales_metinca.test\\/quotations\\/11\"}', '2026-03-07 06:37:14', '2026-03-07 06:34:46', '2026-03-07 06:37:14'),
('eaf10f7f-9a63-48f7-95e9-440afea1d9ac', 'App\\Notifications\\ContractNotification', 'App\\Models\\User', 6, '{\"contract_id\":9,\"order_no\":\"PO-2026-007\",\"customer_id\":\"12\",\"message\":\"Contract membutuhkan approval manager.\",\"url\":\"http:\\/\\/sales_metinca.test\\/contracts\\/9\"}', NULL, '2026-02-13 02:09:41', '2026-02-13 02:09:41'),
('f08c4dd5-120f-42fc-a338-a7545abe919f', 'App\\Notifications\\ContractNotification', 'App\\Models\\User', 5, '{\"contract_id\":4,\"order_no\":\"34\",\"customer_id\":\"2\",\"message\":\"Contract membutuhkan approval manager.\",\"url\":\"http:\\/\\/sales_metinca.test\\/contracts\\/4\"}', NULL, '2026-02-03 06:57:56', '2026-02-03 06:57:56'),
('f08d4e5c-62f2-4e90-8647-80266fef89d3', 'App\\Notifications\\ContractNotification', 'App\\Models\\User', 4, '{\"contract_id\":16,\"order_no\":\"PO-2026-04-0001\",\"customer_id\":\"17\",\"message\":\"Contract membutuhkan approval manager.\",\"url\":\"http:\\/\\/sales_metinca.test\\/contracts\\/16\"}', NULL, '2026-04-16 14:54:53', '2026-04-16 14:54:53'),
('f2fb6516-0bd4-4cc4-9d3f-4c74e3d9c1a1', 'App\\Notifications\\ContractApprovedNotification', 'App\\Models\\User', 3, '{\"contract_id\":5,\"order_no\":\"123424\",\"customer_id\":11,\"message\":\"Contract di approve oleh manager Quality\",\"url\":\"http:\\/\\/sales_metinca.test\\/contracts\\/5\"}', '2026-05-30 19:44:53', '2026-02-03 08:38:10', '2026-05-30 19:44:53'),
('fc2197d0-c1e6-4a79-8df4-a0e3e2889e62', 'App\\Notifications\\ContractNotification', 'App\\Models\\User', 6, '{\"contract_id\":5,\"order_no\":\"123424\",\"customer_id\":\"11\",\"message\":\"Contract membutuhkan approval manager.\",\"url\":\"http:\\/\\/sales_metinca.test\\/contracts\\/5\"}', NULL, '2026-02-03 08:36:03', '2026-02-03 08:36:03'),
('fcd02f6c-87a5-42aa-bcf0-4ec34f54f08b', 'App\\Notifications\\ContractNotification', 'App\\Models\\User', 5, '{\"contract_id\":3,\"order_no\":\"OR-2026-QT003\",\"customer_id\":\"10\",\"message\":\"Contract membutuhkan approval manager.\",\"url\":\"http:\\/\\/sales_metinca.test\\/contracts\\/3\"}', NULL, '2026-02-02 18:39:57', '2026-02-02 18:39:57'),
('fe4b13db-475b-4fe3-bce1-534a9da16481', 'App\\Notifications\\QuotationSendNotification', 'App\\Models\\User', 17, '{\"quotation_id\":12,\"quotation_no\":\"QT-2026-03-0003\",\"status\":\"sent\",\"message\":\"Quotation telah dikirim dan menunggu konfirmasi Anda.\",\"url\":\"http:\\/\\/sales_metinca.test\\/quotations\\/12\"}', '2026-03-10 05:04:31', '2026-03-10 05:04:18', '2026-03-10 05:04:31');

-- --------------------------------------------------------

--
-- Table structure for table `password_reset_tokens`
--

CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `purchase_orders`
--

CREATE TABLE `purchase_orders` (
  `id` bigint UNSIGNED NOT NULL,
  `po_no` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `quotation_id` bigint UNSIGNED NOT NULL,
  `customer_id` bigint UNSIGNED NOT NULL,
  `attachment` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `delivery_request` date DEFAULT NULL,
  `status_order` enum('normal','urgent') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'normal',
  `amandement_no` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` enum('sent','amandement','review','contract','production','ship') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'sent',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `purchase_orders`
--

INSERT INTO `purchase_orders` (`id`, `po_no`, `quotation_id`, `customer_id`, `attachment`, `delivery_request`, `status_order`, `amandement_no`, `status`, `created_at`, `updated_at`) VALUES
(1, 'PO-2026-001', 1, 2, '1770006561_Laporan Kartu Ujian.pdf', '2026-04-18', 'urgent', NULL, 'production', '2026-02-02 04:29:21', '2026-04-07 17:44:15'),
(2, 'PO-2026-002', 2, 9, '1770057051_cover.pdf', '2026-04-08', 'normal', NULL, 'production', '2026-02-02 18:30:51', '2026-02-03 17:46:48'),
(3, 'PO-2026-003', 3, 10, '1770057532_cover.pdf', '2026-04-29', 'normal', NULL, 'contract', '2026-02-02 18:38:52', '2026-02-02 18:39:57'),
(4, 'PO-2026-004', 4, 2, '1770101475_Kertas Jawaban.pdf', '2026-04-04', 'normal', NULL, 'ship', '2026-02-03 06:51:15', '2026-02-03 08:34:03'),
(5, 'PO-2026-005', 5, 11, '1770107622_240718 Struktur Organisasi PT Metinca Prima Industrial Works.pdf', '2026-02-04', 'normal', NULL, 'production', '2026-02-03 08:33:42', '2026-02-11 10:05:53'),
(6, 'PO-2026-006', 6, 12, '1770174528_Struktur CTPAT.pdf', '2026-03-13', 'urgent', NULL, 'production', '2026-02-04 03:08:48', '2026-03-06 04:09:02'),
(7, 'PO-2026-007', 9, 12, '1770948253_S6 - 201.pdf', '2026-02-27', 'normal', NULL, 'ship', '2026-02-13 02:04:13', '2026-02-13 02:16:08'),
(8, 'PO-2026-008', 13, 17, '1773268492_quotation-QT-2026-03-0004.pdf', '2026-04-30', 'normal', NULL, 'sent', '2026-03-11 22:34:54', '2026-03-11 22:34:54'),
(9, 'PO-2026-03-0003', 12, 17, '1773271658_quotation-QT-2026-03-0004 (9).pdf', '2026-04-30', 'normal', NULL, 'sent', '2026-03-11 23:27:38', '2026-03-11 23:27:38'),
(10, 'QT-2026-03-0002', 11, 17, '1773271706_quotation-QT-2026-03-0004 (2).pdf', '2026-05-01', 'normal', NULL, 'sent', '2026-03-11 23:28:26', '2026-03-11 23:28:26'),
(11, 'PO-2026-04-0001', 14, 17, '1775937236_240718 Struktur Organisasi PT Metinca Prima Industrial Works.pdf', '2026-04-30', 'normal', NULL, 'review', '2026-04-11 19:53:56', '2026-04-16 14:54:51'),
(12, 'PO001', 18, 18, '1780170129_S6 - 201.pdf', '2026-07-11', 'normal', NULL, 'sent', '2026-05-30 19:42:09', '2026-05-30 19:42:09');

-- --------------------------------------------------------

--
-- Table structure for table `purchase_order_internals`
--

CREATE TABLE `purchase_order_internals` (
  `id` bigint UNSIGNED NOT NULL,
  `purchase_order_id` bigint UNSIGNED NOT NULL,
  `item` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `material` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `spesifikasi` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `article` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `qty` int NOT NULL DEFAULT '1',
  `unit_price` decimal(15,2) NOT NULL DEFAULT '0.00',
  `subtotal` decimal(15,2) NOT NULL DEFAULT '0.00',
  `delivery_date` date DEFAULT NULL,
  `supplier` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `po_no` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `pic_buyer` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `company_buyer` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `notes` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `purchase_order_internals`
--

INSERT INTO `purchase_order_internals` (`id`, `purchase_order_id`, `item`, `material`, `spesifikasi`, `article`, `qty`, `unit_price`, `subtotal`, `delivery_date`, `supplier`, `po_no`, `pic_buyer`, `company_buyer`, `notes`, `created_at`, `updated_at`) VALUES
(3, 9, 'Produk A', '123333', 'dsvsd', 'pcs', 2, 50.00, 100.00, NULL, NULL, 'PO123', NULL, NULL, NULL, '2026-03-20 04:35:30', '2026-03-20 04:35:30'),
(4, 10, 'Produk A', '123333', 'dsvsd', 'pcs', 1, 67.00, 67.00, '2026-05-01', 'pt.BC', 'PO123', 'ZAQY', 'PT.ABC', NULL, '2026-03-20 07:49:28', '2026-03-20 07:49:28'),
(5, 10, 'Produk C', '12333334', 'dsvsde', 'pcs', 1, 78.00, 78.00, '2026-03-31', 'pt.BC', 'PO123', 'ZAQY', 'PT.ABC', NULL, '2026-03-20 07:49:28', '2026-03-20 07:49:28'),
(6, 8, 'Produk A', '123333s', 'dsvsd', 'pcs', 15, 78.00, 1170.00, NULL, NULL, 'PO-2026-008', NULL, NULL, NULL, '2026-03-20 07:53:59', '2026-03-20 07:53:59'),
(7, 1, 'Produk B', '123333', NULL, 'pcs', 1, 0.00, 0.00, NULL, NULL, 'PO-2026-001', NULL, NULL, NULL, '2026-03-20 08:10:46', '2026-03-20 08:10:46'),
(8, 1, 'Produk A', '123333s', NULL, 'pcs', 1, 0.00, 0.00, NULL, NULL, 'PO-2026-001', NULL, NULL, NULL, '2026-03-20 08:10:46', '2026-03-20 08:10:46'),
(9, 2, 'Produk C', '123333sd', NULL, 'pcs', 1, 67.00, 67.00, NULL, NULL, 'PO-2026-002', NULL, NULL, NULL, '2026-03-20 08:16:34', '2026-03-20 08:16:34'),
(10, 2, 'ss', 'sds', NULL, 'pcs', 15, 67.00, 1005.00, NULL, NULL, 'PO-2026-002', NULL, NULL, NULL, '2026-03-20 08:16:34', '2026-03-20 08:16:34'),
(13, 4, 'Produk B', NULL, 'dsvsd', 'pcs', 1, 5.00, 5.00, NULL, NULL, 'PO-2026-004', NULL, NULL, NULL, '2026-03-20 08:41:39', '2026-03-20 08:41:39'),
(14, 4, 'Produk A', NULL, NULL, 'pcs', 18, 54.00, 972.00, NULL, NULL, 'PO-2026-004', NULL, NULL, NULL, '2026-03-20 08:41:39', '2026-03-20 08:41:39'),
(17, 7, 'Produk B', '123333sd', 'DIN EN 123', NULL, 6, 590.00, 3540.00, '2026-04-11', 'PT.abcd', 'PO-2026-007', 'ZAQY', 'PT.ABCD', '-', '2026-04-07 17:20:28', '2026-04-07 17:20:28'),
(18, 11, 'Produk A', NULL, 'dsvsd', NULL, 1, 50.00, 50.00, '2026-04-30', NULL, 'PO-2026-04-0001', NULL, NULL, NULL, '2026-04-16 14:47:16', '2026-04-16 14:47:16'),
(19, 11, 'Produk B', NULL, NULL, NULL, 1, 40.00, 40.00, '2026-05-01', NULL, 'PO-2026-04-0001', NULL, NULL, NULL, '2026-04-16 14:47:16', '2026-04-16 14:47:16');

-- --------------------------------------------------------

--
-- Table structure for table `purchase_order_items`
--

CREATE TABLE `purchase_order_items` (
  `id` bigint UNSIGNED NOT NULL,
  `purchase_order_id` bigint UNSIGNED NOT NULL,
  `item` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `material` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `satuan` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `qty` int NOT NULL DEFAULT '1',
  `unit_price` decimal(15,2) NOT NULL DEFAULT '0.00',
  `subtotal` decimal(15,2) NOT NULL DEFAULT '0.00',
  `delivery_date` date DEFAULT NULL,
  `notes` text COLLATE utf8mb4_unicode_ci,
  `company` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `pic_buyer` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `supplier` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `quotations`
--

CREATE TABLE `quotations` (
  `id` bigint UNSIGNED NOT NULL,
  `quotation_no` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `date_expired` date NOT NULL,
  `customer_id` bigint UNSIGNED NOT NULL,
  `request_id` bigint UNSIGNED DEFAULT NULL,
  `description` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `material` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `quantity_required_pcs` int DEFAULT NULL,
  `die_cavities` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `grade_type` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `form_of_supply` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `qty_per_mould_pcs` int DEFAULT NULL,
  `pattern_wax` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `metal` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `soluble_wax` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `ceramic` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `runner_wax` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `total_raw_material_cost` int DEFAULT NULL,
  `injection` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `cut_off` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `cleaning` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `scut_off` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `assembly` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `finishing` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `dipping` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `heat_treatment` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `dewaxing` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `straight` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `burnout` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `repair` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `melting` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `blasting` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `knockout` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `inspect` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `w_blast` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `casting_weight` int DEFAULT NULL,
  `total_minutes_per_mould` int DEFAULT NULL,
  `total_minutes_mould` int DEFAULT NULL,
  `machining_add` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `x_ray` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `crack_det` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `polish` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `total_minutes_mould_add` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `total_add` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `wax` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `fixed_overheads_usd` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `straightening` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `scrap_usd` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `machining_fix` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `total_mould_cost` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `piece_price_usd` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `sub_contracting` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `director_comment_approval` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` enum('created','sent','accepted','po','negotiating') COLLATE utf8mb4_unicode_ci DEFAULT 'created',
  `notes` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `item` json DEFAULT NULL,
  `qty` json DEFAULT NULL,
  `attachments` json DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `sent_date` date DEFAULT NULL,
  `accepted_date` date DEFAULT NULL,
  `po_date` date DEFAULT NULL,
  `price` decimal(8,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `quotations`
--

INSERT INTO `quotations` (`id`, `quotation_no`, `date_expired`, `customer_id`, `request_id`, `description`, `material`, `quantity_required_pcs`, `die_cavities`, `grade_type`, `form_of_supply`, `qty_per_mould_pcs`, `pattern_wax`, `metal`, `soluble_wax`, `ceramic`, `runner_wax`, `total_raw_material_cost`, `injection`, `cut_off`, `cleaning`, `scut_off`, `assembly`, `finishing`, `dipping`, `heat_treatment`, `dewaxing`, `straight`, `burnout`, `repair`, `melting`, `blasting`, `knockout`, `inspect`, `w_blast`, `casting_weight`, `total_minutes_per_mould`, `total_minutes_mould`, `machining_add`, `x_ray`, `crack_det`, `polish`, `total_minutes_mould_add`, `total_add`, `wax`, `fixed_overheads_usd`, `straightening`, `scrap_usd`, `machining_fix`, `total_mould_cost`, `piece_price_usd`, `sub_contracting`, `director_comment_approval`, `status`, `notes`, `item`, `qty`, `attachments`, `created_at`, `updated_at`, `sent_date`, `accepted_date`, `po_date`, `price`) VALUES
(1, 'QT-2026-001', '2026-02-28', 2, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'po', 'dsds', NULL, NULL, 'null', '2026-02-02 04:28:34', '2026-02-02 04:29:21', '2026-02-02', NULL, '2026-02-02', NULL),
(2, 'QT-2026-002', '2026-03-13', 9, 2, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'po', 'sdas', NULL, NULL, 'null', '2026-02-02 18:28:50', '2026-02-02 18:30:51', '2026-02-03', NULL, '2026-02-03', NULL),
(3, 'QT-2026-003', '2026-04-07', 10, 3, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'po', NULL, NULL, NULL, 'null', '2026-02-02 18:37:53', '2026-02-02 18:38:52', '2026-02-03', NULL, '2026-02-03', NULL),
(4, 'QT-2026-004', '2026-03-13', 2, 5, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'po', 'ds', NULL, NULL, 'null', '2026-02-03 06:24:41', '2026-02-03 06:51:15', '2026-02-03', NULL, '2026-02-03', NULL),
(5, 'QT-2026-005', '2026-02-07', 11, 6, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'po', 'the price is 400 $ offering until 10 february 2026', NULL, NULL, 'null', '2026-02-03 08:27:55', '2026-02-03 08:33:42', '2026-02-03', NULL, '2026-02-03', NULL),
(6, 'QT-2026-006', '2026-02-07', 12, 7, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'po', 'Yoyr quotation price is 50', NULL, NULL, 'null', '2026-02-04 03:02:53', '2026-02-04 03:08:48', '2026-02-04', NULL, '2026-02-04', NULL),
(7, 'QT-2026-007', '2026-02-13', 12, 10, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'sent', 'your quotation is 5$', NULL, NULL, 'null', '2026-02-11 09:23:29', '2026-02-11 09:48:39', '2026-02-11', NULL, NULL, NULL),
(8, 'QT-2026-008', '2026-03-01', 12, 11, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'created', 'For the spesification u sent to us', NULL, NULL, 'null', '2026-02-13 01:58:46', '2026-02-13 01:58:46', NULL, NULL, NULL, NULL),
(9, 'QT-2026-009', '2026-03-01', 12, 11, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'po', 'For the spesification u sent to us', NULL, NULL, 'null', '2026-02-13 01:58:47', '2026-02-13 02:04:13', '2026-02-13', NULL, '2026-02-13', NULL),
(10, 'QT-2026-03-0001', '2026-03-18', 2, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'created', 'this quotation will be expired.', NULL, NULL, 'null', '2026-03-07 06:33:17', '2026-03-07 06:33:17', NULL, NULL, NULL, NULL),
(11, 'QT-2026-03-0002', '2026-03-30', 17, 14, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'po', 'this quotation will be expired.', NULL, NULL, 'null', '2026-03-07 06:34:08', '2026-03-11 23:28:26', '2026-03-07', NULL, '2026-03-12', NULL),
(12, 'QT-2026-03-0003', '2026-03-31', 17, 15, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'po', 'this quotation will be expired', NULL, NULL, 'null', '2026-03-10 05:04:01', '2026-03-11 23:27:38', '2026-03-10', NULL, '2026-03-12', NULL),
(13, 'QT-2026-03-0004', '2026-03-31', 17, 19, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'po', 'this quotation will be expired so u have to ask request again if u want to get new quotation.', NULL, NULL, 'null', '2026-03-10 06:39:19', '2026-03-11 22:34:54', NULL, NULL, '2026-03-12', NULL),
(14, 'QT-2026-04-0001', '2026-04-30', 17, 32, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'po', 'this price until tomorrow.', NULL, NULL, 'null', '2026-04-10 05:02:12', '2026-04-11 19:53:56', '2026-04-11', '2026-04-12', '2026-04-12', NULL),
(15, 'QT-2026-04-0002', '2026-04-30', 17, 31, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'accepted', 'please concern to the date expired for the quotation price', NULL, NULL, 'null', '2026-04-12 03:30:42', '2026-04-16 14:43:37', '2026-04-12', '2026-04-16', NULL, NULL),
(16, 'QT-2026-04-0003', '2026-04-30', 17, 23, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'negotiating', 'hurry', NULL, NULL, 'null', '2026-04-12 04:06:37', '2026-04-12 04:17:08', '2026-04-12', NULL, NULL, NULL),
(17, 'QT-2026-04-0004', '2026-04-16', 17, 37, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'created', 'it will be expire soon', NULL, NULL, 'null', '2026-04-16 14:37:09', '2026-04-16 14:37:09', NULL, NULL, NULL, NULL),
(18, 'QT-2026-05-0001', '2026-06-30', 18, 38, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'po', 'Please pay attention to the expiration date', NULL, NULL, 'null', '2026-05-30 19:21:29', '2026-05-30 19:42:09', '2026-05-31', NULL, '2026-05-31', NULL),
(19, 'QT-2026-05-0002', '2026-06-30', 18, 39, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'sent', 'please pay attention the date', NULL, NULL, 'null', '2026-05-31 06:31:14', '2026-05-31 06:32:51', '2026-05-31', NULL, NULL, NULL),
(20, 'QT-2026-05-0003', '2026-06-06', 18, 40, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'created', 'pay att', NULL, NULL, 'null', '2026-05-31 06:40:30', '2026-05-31 06:40:30', NULL, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `quotation_items`
--

CREATE TABLE `quotation_items` (
  `id` bigint UNSIGNED NOT NULL,
  `quotation_id` bigint UNSIGNED NOT NULL,
  `item` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `qty` int NOT NULL,
  `price` decimal(15,2) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `quotation_items`
--

INSERT INTO `quotation_items` (`id`, `quotation_id`, `item`, `qty`, `price`, `created_at`, `updated_at`) VALUES
(1, 10, 'Produk AA', 1, 0.09, '2026-03-07 06:33:17', '2026-03-07 06:33:17'),
(2, 10, 'Produk AB', 1, 0.09, '2026-03-07 06:33:17', '2026-03-07 06:33:17'),
(3, 11, 'Produk C', 1, 0.09, '2026-03-07 06:34:08', '2026-03-07 06:34:08'),
(4, 11, 'Produk D', 1, 0.09, '2026-03-07 06:34:08', '2026-03-07 06:34:08'),
(5, 12, 'Produk A', 2, 0.09, '2026-03-10 05:04:01', '2026-03-10 05:04:01'),
(6, 12, 'Produk C', 7, 0.03, '2026-03-10 05:04:01', '2026-03-10 05:04:01'),
(7, 12, 'Produk B', 1, 0.08, '2026-03-10 05:04:01', '2026-03-10 05:04:01'),
(8, 13, 'Produk A', 1, 10.00, '2026-03-10 06:39:19', '2026-03-10 06:39:19'),
(9, 13, 'Produk B', 2, 20.00, '2026-03-10 06:39:19', '2026-03-10 06:39:19'),
(10, 13, 'Produk C', 3, 30.00, '2026-03-10 06:39:19', '2026-03-10 06:39:19'),
(11, 14, 'Produk A', 2, 46.00, '2026-04-10 05:02:12', '2026-04-11 18:52:56'),
(12, 14, 'Produk B', 5, 56.00, '2026-04-10 05:02:12', '2026-04-11 18:52:56'),
(13, 15, 'Produk A', 1, 50.00, '2026-04-12 03:30:42', '2026-04-16 14:43:37'),
(14, 15, 'Produk C', 2, 40.00, '2026-04-12 03:30:42', '2026-04-16 14:43:37'),
(15, 16, 'Produk C', 1, 0.03, '2026-04-12 04:06:37', '2026-04-12 04:06:37'),
(16, 17, 'Produk A', 100, 50.00, '2026-04-16 14:37:09', '2026-04-16 14:37:09'),
(17, 17, 'Produk B', 100, 50.00, '2026-04-16 14:37:09', '2026-04-16 14:37:09'),
(18, 17, 'Produk C', 50, 10.00, '2026-04-16 14:37:09', '2026-04-16 14:37:09'),
(19, 18, 'Produk A', 2, 20.00, '2026-05-30 19:21:29', '2026-05-30 19:21:29'),
(20, 18, 'Produk B', 3, 30.00, '2026-05-30 19:21:29', '2026-05-30 19:21:29'),
(21, 19, 'Produk A', 2, 100.00, '2026-05-31 06:31:14', '2026-05-31 06:31:14'),
(22, 20, 'Produk A', 4, 20.00, '2026-05-31 06:40:30', '2026-05-31 06:40:30');

-- --------------------------------------------------------

--
-- Table structure for table `request_attachments`
--

CREATE TABLE `request_attachments` (
  `id` bigint UNSIGNED NOT NULL,
  `request_id` bigint UNSIGNED NOT NULL,
  `document_name` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `prefix_path` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `file_path` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `request_attachments`
--

INSERT INTO `request_attachments` (`id`, `request_id`, `document_name`, `prefix_path`, `file_path`, `deleted_at`, `created_at`, `updated_at`) VALUES
(1, 1, 'BILL OF MATERIALS D2-49.pdf', NULL, 'project-requests/attachment/1770006420_BILL OF MATERIALS D2-49.pdf', NULL, '2026-02-02 04:27:01', '2026-02-02 04:27:01'),
(2, 2, 'cover.pdf', NULL, 'project-requests/attachment/1770055321_cover.pdf', NULL, '2026-02-02 18:02:01', '2026-02-02 18:02:01'),
(3, 3, 'cover.pdf', NULL, 'project-requests/attachment/1770057399_cover.pdf', NULL, '2026-02-02 18:36:39', '2026-02-02 18:36:39'),
(4, 4, 'BILL OF MATERIALS D2-49.pdf', NULL, 'project-requests/attachment/1770084067_BILL OF MATERIALS D2-49.pdf', NULL, '2026-02-03 02:01:09', '2026-02-03 02:01:09'),
(5, 5, 'cover.pdf', NULL, 'project-requests/attachment/1770099730_cover.pdf', NULL, '2026-02-03 06:22:11', '2026-02-03 06:22:11'),
(6, 6, '240718 Struktur Organisasi PT Metinca Prima Industrial Works.pdf', NULL, 'project-requests/attachment/1770106931_240718 Struktur Organisasi PT Metinca Prima Industrial Works.pdf', NULL, '2026-02-03 08:22:12', '2026-02-03 08:22:12'),
(7, 7, 'S6 - 201.pdf', NULL, 'project-requests/attachment/1770173312_S6 - 201.pdf', NULL, '2026-02-04 02:48:32', '2026-02-04 02:48:32'),
(8, 8, '15277-Article Text-63832-1-10-20250728 (2).pdf', NULL, 'project-requests/attachment/1770363913_15277-Article Text-63832-1-10-20250728 (2).pdf', NULL, '2026-02-06 07:45:14', '2026-02-06 07:45:14'),
(9, 9, 'frame ukuran kartu.pdf', NULL, 'project-requests/attachment/1770364933_frame ukuran kartu.pdf', NULL, '2026-02-06 08:02:13', '2026-02-06 08:02:13'),
(10, 10, 'kartu perpus zaqy.pdf', NULL, 'project-requests/attachment/1770801672_kartu perpus zaqy.pdf', NULL, '2026-02-11 09:21:14', '2026-02-11 09:21:14'),
(11, 11, 'S6 - 201.pdf', NULL, 'project-requests/attachment/1770947656_S6 - 201.pdf', NULL, '2026-02-13 01:54:18', '2026-02-13 01:54:18'),
(12, 12, 'card.pdf', NULL, 'project-requests/attachment/1772524716_card.pdf', NULL, '2026-03-03 07:58:38', '2026-03-03 07:58:38'),
(13, 13, 'card.pdf', NULL, 'project-requests/attachment/1772525303_card.pdf', NULL, '2026-03-03 08:08:23', '2026-03-03 08:08:23'),
(14, 14, 'card.pdf', NULL, 'project-requests/attachment/1772596828_card.pdf', NULL, '2026-03-04 04:00:29', '2026-03-04 04:00:29'),
(15, 15, 'SKCK_1771991929715.pdf', NULL, 'project-requests/attachment/1772602220_SKCK_1771991929715.pdf', NULL, '2026-03-04 05:30:20', '2026-03-04 05:30:20'),
(16, 17, 'SKCK_1771991929715.pdf', NULL, 'project-requests/attachment/1772635355_SKCK_1771991929715.pdf', NULL, '2026-03-04 14:42:35', '2026-03-04 14:42:35'),
(17, 19, 'SKCK_1771991929715.pdf', NULL, 'project-requests/attachment/1773124599_SKCK_1771991929715.pdf', NULL, '2026-03-10 06:36:39', '2026-03-10 06:36:39'),
(18, 21, 'SKCK_1771991929715.pdf', NULL, 'project-requests/attachment/1773159768_SKCK_1771991929715.pdf', NULL, '2026-03-10 16:22:49', '2026-03-10 16:22:49'),
(19, 23, 'quotation-QT-2026-03-0004 (7).pdf', NULL, 'project-requests/attachment/1774224573_quotation-QT-2026-03-0004 (7).pdf', NULL, '2026-03-23 00:09:35', '2026-03-23 00:09:35'),
(20, 25, 'quotation-QT-2026-03-0004.pdf', NULL, 'project-requests/attachment/1774322160_quotation-QT-2026-03-0004.pdf', NULL, '2026-03-24 03:16:01', '2026-03-24 03:16:01'),
(21, 27, 'S6 - 201.pdf', NULL, 'project-requests/attachment/1775792454_S6 - 201.pdf', NULL, '2026-04-10 03:40:55', '2026-04-10 03:40:55'),
(22, 29, 'S6 - 201.pdf', NULL, 'project-requests/attachment/1775794038_S6 - 201.pdf', NULL, '2026-04-10 04:07:18', '2026-04-10 04:07:18'),
(23, 31, 'Peta Bisnis Multisite (On Progress).pdf', NULL, 'project-requests/attachment/1775794548_Peta Bisnis Multisite (On Progress).pdf', NULL, '2026-04-10 04:15:48', '2026-04-10 04:15:48'),
(24, 32, 'S6 - 201.pdf', NULL, 'project-requests/attachment/1775796648_S6 - 201.pdf', NULL, '2026-04-10 04:50:48', '2026-04-10 04:50:48'),
(25, 38, 'S6 - 201.pdf', NULL, 'project-requests/attachment/1780168425_S6 - 201.pdf', NULL, '2026-05-30 19:13:46', '2026-05-30 19:13:46'),
(26, 39, 'S6 - 201.pdf', NULL, 'project-requests/attachment/1780208743_S6 - 201.pdf', NULL, '2026-05-31 06:25:44', '2026-05-31 06:25:44'),
(27, 40, 'S6 - 201.pdf', NULL, 'project-requests/attachment/1780209518_S6 - 201.pdf', NULL, '2026-05-31 06:38:38', '2026-05-31 06:38:38');

-- --------------------------------------------------------

--
-- Table structure for table `request_projects`
--

CREATE TABLE `request_projects` (
  `id` bigint UNSIGNED NOT NULL,
  `customer_id` bigint UNSIGNED DEFAULT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `subject` enum('quotation','other project','meet and greet') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'quotation',
  `message` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `phone` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `company` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `request_projects`
--

INSERT INTO `request_projects` (`id`, `customer_id`, `name`, `email`, `subject`, `message`, `phone`, `company`, `deleted_at`, `created_at`, `updated_at`) VALUES
(1, 2, 'Customer 1', 'customer1@example.com', 'quotation', 'dsds', NULL, 'PT. Maju Mundur', NULL, '2026-02-02 04:27:00', '2026-02-02 04:27:00'),
(2, 9, 'Rayyanda', 'rayyanda5113@gmail.com', 'quotation', 'G4-A1', '082111572238', 'PT. Kanan Kiri', NULL, '2026-02-02 18:02:01', '2026-02-02 18:22:49'),
(3, 10, 'Agus salim', 'agussalim@unsada.ac.id', 'quotation', 'derfgbhjn', '08111815580', 'Universitas Darma Persasda', NULL, '2026-02-02 18:36:39', '2026-02-02 18:37:26'),
(4, NULL, 'Dhihya Rayyanda', 'rayyanda5113@gmail.com', 'quotation', 'vbhj', '082111572238', 'PT. Maju Mundur', NULL, '2026-02-03 02:01:07', '2026-02-03 02:01:07'),
(5, 2, 'Customer 1', 'customer1@example.com', 'quotation', 'sdsdasd', NULL, 'PT. Maju Mundur', NULL, '2026-02-03 06:22:10', '2026-02-03 06:22:10'),
(6, 11, 'Customer 2', 'cust2@example.com', 'quotation', 'quote this', '088973678351', 'PT. AB', NULL, '2026-02-03 08:22:11', '2026-02-03 08:27:07'),
(7, 12, 'Bella', 'bella@exaxmple.com', 'quotation', 'Please quote this drawing', '+62812345', 'PT. Menembus Batas', NULL, '2026-02-04 02:48:32', '2026-02-04 02:57:57'),
(8, 13, 'customer 5', 'cust5@example.com', 'quotation', 'please quote this drawing', '088808528826', 'pt.abcde', NULL, '2026-02-06 07:45:13', '2026-02-06 07:50:29'),
(9, NULL, 'customer 6', 'cust6@example.com', 'quotation', 'quote this', '088808528826', 'pt.abcde', NULL, '2026-02-06 08:02:13', '2026-02-06 08:02:13'),
(10, 12, 'Bella', 'bella@exaxmple.com', 'quotation', 'quote this', '088973678351', 'PT. Menembus Batas', NULL, '2026-02-11 09:21:12', '2026-02-11 09:21:12'),
(11, 12, 'Bella', 'bella@exaxmple.com', 'quotation', 'Please quote this drawing', '088973678351', 'PT. Menembus Batas', NULL, '2026-02-13 01:54:16', '2026-02-13 01:54:16'),
(12, 16, 'fajar', 'fajar@gmail.com', 'quotation', 'sadadadas', '088973678351', 'PT. Maju Mundur', NULL, '2026-03-03 07:58:36', '2026-03-03 07:58:36'),
(13, 16, 'fajar', 'fajar@gmail.com', 'quotation', 'sajdbsajbdjad', '088973678351', 'PT. Maju Mundur', NULL, '2026-03-03 08:08:23', '2026-03-03 08:08:23'),
(14, 17, 'zaqy', 'zaqy@gmail.com', 'quotation', 'as', '088973678351', 'PT. AB', NULL, '2026-03-04 04:00:28', '2026-03-04 04:00:28'),
(15, 17, 'zaqy', 'zaqy@gmail.com', 'quotation', 'sad', '088973678351', 'PT. AB', NULL, '2026-03-04 05:30:20', '2026-03-04 05:30:20'),
(16, NULL, 'zaqy', 'zaqy@gmail.com', 'quotation', 'sdfs', '088973678351', 'PT. AB', NULL, '2026-03-04 14:42:35', '2026-03-04 14:42:35'),
(17, 17, 'zaqy', 'zaqy@gmail.com', 'quotation', 'sdfs', '088973678351', 'PT. AB', NULL, '2026-03-04 14:42:35', '2026-03-04 14:42:35'),
(18, NULL, 'zaqysalsa', 'zaqy@gmail.com', 'quotation', 'quote this', '088973678351', 'PT. ABCDEF', NULL, '2026-03-10 06:36:39', '2026-03-10 06:36:39'),
(19, 17, 'zaqysalsa', 'zaqy@gmail.com', 'quotation', 'quote this', '088973678351', 'PT. ABCDEF', NULL, '2026-03-10 06:36:39', '2026-03-10 06:36:39'),
(20, NULL, 'bagus', 'bagus@gmail.com', 'quotation', 'quote this', '088973678351', 'PT. ABCD', NULL, '2026-03-10 16:22:48', '2026-03-10 16:22:48'),
(21, 17, 'bagus', 'bagus@gmail.com', 'quotation', 'quote this', '088973678351', 'PT. ABCD', NULL, '2026-03-10 16:22:48', '2026-03-10 16:22:48'),
(22, NULL, 'zaqy', 'zaqy@gmail.com', 'quotation', 'how many?', '088973678351', 'PT. AB', NULL, '2026-03-23 00:09:33', '2026-03-23 00:09:33'),
(23, 17, 'zaqy', 'zaqy@gmail.com', 'quotation', 'how many?', '088973678351', 'PT. AB', NULL, '2026-03-23 00:09:33', '2026-03-23 00:09:33'),
(24, NULL, 'zaqy', 'zaqy@gmail.com', 'quotation', 'quote this', '088973678351', 'PT. AB', NULL, '2026-03-24 03:16:00', '2026-03-24 03:16:00'),
(25, 17, 'zaqy', 'zaqy@gmail.com', 'quotation', 'quote this', '088973678351', 'PT. AB', NULL, '2026-03-24 03:16:00', '2026-03-24 03:16:00'),
(26, NULL, 'zaqy', 'zaqy@gmail.com', 'quotation', 'Please quote this drawing for the price. thank u', '088973678351', 'PT. 123', NULL, '2026-04-10 03:40:54', '2026-04-10 03:40:54'),
(27, 17, 'zaqy', 'zaqy@gmail.com', 'quotation', 'Please quote this drawing for the price. thank u', '088973678351', 'PT. 123', NULL, '2026-04-10 03:40:54', '2026-04-10 03:40:54'),
(28, NULL, 'zaqy', 'zaqy@gmail.com', 'quotation', 'quote this drawing', '088973678351', 'PT. 123456', NULL, '2026-04-10 04:07:18', '2026-04-10 04:07:18'),
(29, 17, 'zaqy', 'zaqy@gmail.com', 'quotation', 'quote this drawing', '088973678351', 'PT. 123456', NULL, '2026-04-10 04:07:18', '2026-04-10 04:07:18'),
(30, NULL, 'zaqy', 'zaqy@gmail.com', 'quotation', 'quote this', '(+62) 889 7367 8351', 'PT. AB', NULL, '2026-04-10 04:15:48', '2026-04-10 04:15:48'),
(31, 17, 'zaqy', 'zaqy@gmail.com', 'quotation', 'quote this', '(+62) 889 7367 8351', 'PT. AB', NULL, '2026-04-10 04:15:48', '2026-04-10 04:15:48'),
(32, 17, 'zaqy', 'zaqy@gmail.com', 'quotation', 'QUOTE THE DRAWING', '088973678351', 'PT. AB12CD', NULL, '2026-04-10 04:50:48', '2026-04-10 04:50:48'),
(33, 17, 'zaqy', 'zaqy@gmail.com', 'quotation', 'quote', '088973678351', 'PT. AB', NULL, '2026-04-16 14:24:54', '2026-04-16 14:24:54'),
(34, 17, 'zaqy', 'zaqy@gmail.com', 'quotation', 'quote', '088973678351', 'PT. AB', NULL, '2026-04-16 14:24:58', '2026-04-16 14:24:58'),
(35, 17, 'zaqy', 'zaqy@gmail.com', 'quotation', 'please quote this quotation', '088973678351', 'PT. AB', NULL, '2026-04-16 14:27:15', '2026-04-16 14:27:15'),
(36, 17, 'zaqy', 'zaqy@gmail.com', 'quotation', 'please quote this quotation', '088973678351', 'PT. AB', NULL, '2026-04-16 14:27:30', '2026-04-16 14:27:30'),
(37, 17, 'zaqy', 'zaqy@gmail.com', 'quotation', 'please quote this quotation', '088973678351', 'PT. AB', NULL, '2026-04-16 14:27:53', '2026-04-16 14:27:53'),
(38, 18, 'sabila', 'sabila@example.com', 'quotation', 'I want to know how much for this drawing', '088973678351', 'PT. PINDAD', NULL, '2026-05-30 19:13:45', '2026-05-30 19:13:45'),
(39, 18, 'sabila', 'sabila@example.com', 'quotation', 'please quote this drawing', '088973678351', 'PT. PINDAD', NULL, '2026-05-31 06:25:43', '2026-05-31 06:25:43'),
(40, 18, 'sabila', 'sabila@example.com', 'quotation', 'please pay att', '+6288808528826', 'PT. PINDAD', NULL, '2026-05-31 06:38:38', '2026-05-31 06:38:38');

-- --------------------------------------------------------

--
-- Table structure for table `request_project_assignments`
--

CREATE TABLE `request_project_assignments` (
  `id` bigint UNSIGNED NOT NULL,
  `request_project_id` bigint UNSIGNED NOT NULL,
  `sales_id` bigint UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `request_project_assignments`
--

INSERT INTO `request_project_assignments` (`id`, `request_project_id`, `sales_id`, `created_at`, `updated_at`) VALUES
(1, 1, 3, '2026-03-05 05:21:04', '2026-03-05 05:21:04'),
(2, 2, 14, '2026-03-05 05:23:02', '2026-03-05 05:23:02'),
(3, 3, 14, '2026-03-05 05:32:51', '2026-03-05 05:32:51'),
(4, 4, 14, '2026-03-05 06:01:29', '2026-03-05 06:01:29'),
(5, 5, 14, '2026-03-05 06:01:52', '2026-03-05 06:01:52'),
(6, 6, 3, '2026-03-05 06:02:02', '2026-03-05 06:02:02'),
(7, 7, 3, '2026-03-05 06:02:24', '2026-03-05 06:02:24'),
(8, 8, 3, '2026-03-06 02:35:13', '2026-03-06 02:35:13'),
(9, 14, 3, '2026-03-07 03:00:37', '2026-03-07 03:00:37'),
(10, 15, 3, '2026-03-10 05:03:09', '2026-03-10 05:03:09'),
(11, 16, 3, '2026-03-10 06:28:05', '2026-03-10 06:28:05'),
(12, 19, 3, '2026-03-10 06:38:00', '2026-03-10 06:38:00'),
(13, 23, 1, '2026-03-23 00:13:11', '2026-03-23 00:13:11'),
(14, 24, 3, '2026-03-24 03:29:10', '2026-03-24 03:29:10'),
(15, 26, 14, '2026-04-10 03:41:44', '2026-04-10 03:41:44'),
(16, 32, 1, '2026-04-10 05:01:16', '2026-04-10 05:01:16'),
(17, 31, 1, '2026-04-12 03:29:31', '2026-04-12 03:29:31'),
(18, 30, 14, '2026-04-12 03:34:35', '2026-04-12 03:34:35'),
(19, 28, 14, '2026-04-12 03:34:39', '2026-04-12 03:34:39'),
(20, 29, 14, '2026-04-12 03:34:59', '2026-04-12 03:34:59'),
(21, 27, 14, '2026-04-12 03:35:04', '2026-04-12 03:35:04'),
(22, 25, 14, '2026-04-12 03:35:08', '2026-04-12 03:35:08'),
(23, 22, 14, '2026-04-12 03:35:21', '2026-04-12 03:35:21'),
(24, 20, 14, '2026-04-12 03:35:45', '2026-04-12 03:35:45'),
(25, 18, 14, '2026-04-12 03:35:52', '2026-04-12 03:35:52'),
(26, 21, 1, '2026-04-12 03:36:31', '2026-04-12 03:36:31'),
(27, 17, 1, '2026-04-12 03:36:40', '2026-04-12 03:36:40'),
(28, 37, 1, '2026-05-11 13:46:48', '2026-05-11 13:46:48'),
(29, 38, 3, '2026-05-30 19:18:05', '2026-05-30 19:18:05'),
(30, 39, 14, '2026-05-31 06:28:55', '2026-05-31 06:28:55'),
(31, 40, 1, '2026-05-31 06:39:04', '2026-05-31 06:39:04');

-- --------------------------------------------------------

--
-- Table structure for table `sessions`
--

CREATE TABLE `sessions` (
  `id` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` bigint UNSIGNED DEFAULT NULL,
  `ip_address` varchar(45) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_agent` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `payload` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `last_activity` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `sessions`
--

INSERT INTO `sessions` (`id`, `user_id`, `ip_address`, `user_agent`, `payload`, `last_activity`) VALUES
('19JeRrtw2DTvH77haXKuxTbLC3pqeajQ98eZLyVb', 18, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36 Edg/148.0.0.0', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoiWFR5TVU4YWZId1VoSjZPaHlXaEk0bzJrU2laUkhNRVdQbktneThVTiI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJuZXciO2E6MDp7fXM6Mzoib2xkIjthOjA6e319czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MzU6Imh0dHA6Ly9zYWxlc19tZXRpbmNhLnRlc3QvZGFzaGJvYXJkIjtzOjU6InJvdXRlIjtzOjk6ImRhc2hib2FyZCI7fXM6NTA6ImxvZ2luX3dlYl81OWJhMzZhZGRjMmIyZjk0MDE1ODBmMDE0YzdmNThlYTRlMzA5ODlkIjtpOjE4O30=', 1780210680),
('HZ8JWlEh08ngv5CJvivdcLFjmuaJ2tNNfiEVdoO4', 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoiZlV4Tm9uSVVKa1NRRE9abkJPQkFPeVVLWXZiWmtEaVdYZVRuTkFhaiI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MzU6Imh0dHA6Ly9zYWxlc19tZXRpbmNhLnRlc3QvY29udHJhY3RzIjtzOjU6InJvdXRlIjtzOjE1OiJjb250cmFjdHMuaW5kZXgiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX1zOjUwOiJsb2dpbl93ZWJfNTliYTM2YWRkYzJiMmY5NDAxNTgwZjAxNGM3ZjU4ZWE0ZTMwOTg5ZCI7aToxO30=', 1780210147),
('oHLWNHbWwQMoKlrcsXxke9mr46nYw3NQKPmdlW1s', 3, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoiSU83a3E5Z21jcjlycVNJclFuRmQ3TnFURVhURjdyaHJoajNnclBoZSI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJuZXciO2E6MDp7fXM6Mzoib2xkIjthOjA6e319czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6NDE6Imh0dHA6Ly9zYWxlc19tZXRpbmNhLnRlc3QvcHVyY2hhc2Utb3JkZXJzIjtzOjU6InJvdXRlIjtzOjIxOiJwdXJjaGFzZS1vcmRlcnMuaW5kZXgiO31zOjUwOiJsb2dpbl93ZWJfNTliYTM2YWRkYzJiMmY5NDAxNTgwZjAxNGM3ZjU4ZWE0ZTMwOTg5ZCI7aTozO30=', 1780209328),
('qq8VSViJPanjPRHYpkYcEozqYTIXINv7hu4kIdqf', 5, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoib3c2RnFzVms4MVNBVXltUTUzOHFSOXcxSkszbjJVQTVtR1B3MFJ0MiI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJuZXciO2E6MDp7fXM6Mzoib2xkIjthOjA6e319czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MzU6Imh0dHA6Ly9zYWxlc19tZXRpbmNhLnRlc3QvZGFzaGJvYXJkIjtzOjU6InJvdXRlIjtzOjk6ImRhc2hib2FyZCI7fXM6NTA6ImxvZ2luX3dlYl81OWJhMzZhZGRjMmIyZjk0MDE1ODBmMDE0YzdmNThlYTRlMzA5ODlkIjtpOjU7fQ==', 1780211499),
('y2rXftKtvil6nMTwa50L5Rser1JITRaNMTJgCUMJ', 14, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoiajU0a21pYVpkcVpkY2dGbTR1WGZQWG11eXdrVkh5enB4NEptZmczOSI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJuZXciO2E6MDp7fXM6Mzoib2xkIjthOjA6e319czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6Mzc6Imh0dHA6Ly9zYWxlc19tZXRpbmNhLnRlc3QvcG8tc2NoZWR1bGUiO3M6NToicm91dGUiO3M6MTc6InBvLXNjaGVkdWxlLmluZGV4Ijt9czo1MDoibG9naW5fd2ViXzU5YmEzNmFkZGMyYjJmOTQwMTU4MGYwMTRjN2Y1OGVhNGUzMDk4OWQiO2k6MTQ7fQ==', 1780210798);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `company` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `remember_token` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `role` enum('staff','admin','customer','manager') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT 'staff',
  `divisi` enum('sales','design engineering','quality','ppc') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `email_verified_at`, `password`, `company`, `remember_token`, `created_at`, `updated_at`, `role`, `divisi`) VALUES
(1, 'Admin', 'admin@example.com', NULL, '$2y$12$oqHb8e24/bZxZEJfANvkHeAo1MfSBebx7JfPyp2y4ST4R.8ZlD06.', 'Jakarta', NULL, '2026-02-02 04:22:07', '2026-02-03 08:19:04', 'admin', 'sales'),
(2, 'Customer 1', 'customer1@example.com', NULL, '$2y$12$JdwH2lzDGu.PFJ4fy.zs/eVW5tQUyo3/DGFZeWMiC5j1kdJYfmXWO', 'PT. Maju Mundur', NULL, '2026-02-02 04:22:56', '2026-02-02 04:22:56', 'customer', 'sales'),
(3, 'Sales 1', 'ssales1@example.com', NULL, '$2y$12$5TTCIwTxcbPryUMi4sXYK.8UC1olmlGmcMFsG4qXeRwHxD1z/d81C', 'Jakarta', NULL, '2026-02-02 04:26:16', '2026-02-03 08:19:25', 'staff', 'sales'),
(4, 'Manager Sales', 'msales@example.com', NULL, '$2y$12$yhkzRf2gp2vHP5XjhZWnN.7iTb8hUPGwQMXSSevXpUQ9rIN99zNB2', 'Jakarta', NULL, '2026-02-02 15:24:05', '2026-02-03 08:19:58', 'manager', 'sales'),
(5, 'Manager Quality', 'mquality@example.com', NULL, '$2y$12$onZfHknfvpb1z7XggZtC1uBKLkpgFtsBhc0n2x5VIoxphT93YLnde', 'Jakarta', NULL, '2026-02-02 15:25:31', '2026-02-03 08:20:17', 'manager', 'quality'),
(6, 'Manager PPC', 'mppc@example.com', NULL, '$2y$12$f8ANYzwL15EGQrtQyQSawu0ROmCLUTT1sorVIU8titXGfHLGQqmUG', 'Jakarta', NULL, '2026-02-02 15:26:20', '2026-02-03 08:20:29', 'manager', 'ppc'),
(7, 'Manager Development Engineering', 'mde@example.com', NULL, '$2y$12$QdKWTS33WHU8rYvJigj4U.tjYXJjDxU4.twIKP178qzsA9XSYoRF2', 'Jakarta', NULL, '2026-02-02 15:27:05', '2026-02-03 08:20:43', 'manager', 'design engineering'),
(9, 'Rayyanda', 'rayyanda5113@gmail.com', NULL, '$2y$12$lD3WFO55nnVSuRAb.dmwOu2h5KDQT/Xab2y98tEjiilcE.Lxj5y/C', 'PT. Kanan Kiri', NULL, '2026-02-02 18:22:49', '2026-02-02 18:22:49', 'customer', NULL),
(10, 'Agus salim Jaya Kusuma', 'agussalim@unsada.ac.id', NULL, '$2y$12$j7QBfDNQPnFxzX193umjHOhwVZ6qCL/BLWahIKSfXIAmy.2sQNIRO', 'Universitas Darma Persasda', NULL, '2026-02-02 18:37:26', '2026-02-02 19:02:57', 'customer', NULL),
(11, 'Customer 2', 'cust2@example.com', NULL, '$2y$12$2sJIB0LCxoHw8NzMovE8Z.nYoAARraKOt6Zs5u1mtas6GOk1xm/kO', 'PT. AB', NULL, '2026-02-03 08:27:07', '2026-02-03 08:27:07', 'customer', NULL),
(12, 'Bella', 'bella@exaxmple.com', NULL, '$2y$12$bk5TWgfHOow9bE4npyyWIesFh2nJYJLHqmIF/gno7Q0B84B59yvBK', 'PT. Menembus Batas', NULL, '2026-02-04 02:57:57', '2026-02-04 03:05:56', 'customer', NULL),
(13, 'customer 5', 'cust5@example.com', NULL, '$2y$12$.UbS3EmIiEYR9opwW9oNyOFVclgQoaqCFWuc8Zyi74uwi3lfRLrKi', 'pt.abcde', NULL, '2026-02-06 07:50:29', '2026-02-06 07:50:29', 'customer', NULL),
(14, 'Sales 2', 'ssales2@example.com', NULL, '$2y$12$CmfGau.dnr/Yqg2JA7awte7DLKp/9VsPuyWytVSYeKpIl/4ltTED.', 'Jakarta', NULL, '2026-02-06 08:00:30', '2026-02-06 08:00:30', 'staff', 'sales'),
(15, 'zaqy salsabilla', 'zsalsabilla129@gmail.com', NULL, '$2y$12$bU5cg8Uy2irGjVQmD7NQ9OP86UIKioKCNWG05PXrdh0H4.3kobkpa', 'PT. ABCD', NULL, '2026-03-03 04:40:43', '2026-03-03 04:40:43', 'customer', NULL),
(16, 'fajar', 'fajar@gmail.com', NULL, '$2y$12$fMBMaZj0T5Miylfv6kLQFeEX6dZZqtGiSFtwB8Zupxhk7ov/tQ.tm', 'PT. Maju Mundur', NULL, '2026-03-03 07:03:20', '2026-03-03 07:03:20', 'customer', NULL),
(17, 'zaqy', 'zaqy@gmail.com', NULL, '$2y$12$s2H9WzdqH7bqwgONe95h/eUE5QIKgQVRqsk9/17JTkIaEtx.xVj7i', 'PT. AB', NULL, '2026-03-04 03:59:35', '2026-03-04 03:59:35', 'customer', NULL),
(18, 'sabila', 'sabila@example.com', NULL, '$2y$12$rTA048LYtTpNnZ6ld0hYbO8EuhK564JWPfLJvKQqT6lnym8.Osw6i', 'PT. PINDAD', NULL, '2026-05-30 19:01:38', '2026-05-30 19:01:38', 'customer', NULL),
(19, 'Bagus', 'bagus11@gmail.com', NULL, '$2y$12$uO6jbqBvuuNVjJRgwuM0XuCdbfTEAyTcf3ro.CHByIT1c2UVDb396', 'PT. EPSON', NULL, '2026-05-31 04:38:23', '2026-05-31 04:38:23', 'customer', NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `account_table`
--
ALTER TABLE `account_table`
  ADD PRIMARY KEY (`id`),
  ADD KEY `account_table_user_id_foreign` (`user_id`);

--
-- Indexes for table `articles`
--
ALTER TABLE `articles`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `articles_part_number_unique` (`part_number`),
  ADD UNIQUE KEY `articles_customer_part_no_unique` (`article_no`),
  ADD KEY `customer_id_foreign` (`customer_id`);

--
-- Indexes for table `cache`
--
ALTER TABLE `cache`
  ADD PRIMARY KEY (`key`);

--
-- Indexes for table `cache_locks`
--
ALTER TABLE `cache_locks`
  ADD PRIMARY KEY (`key`);

--
-- Indexes for table `contracts`
--
ALTER TABLE `contracts`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `contracts_contract_no_unique` (`contract_no`),
  ADD KEY `contracts_customer_id_foreign` (`customer_id`),
  ADD KEY `contracts_quotation_id_foreign` (`quotation_id`),
  ADD KEY `contracts_sales_approver_foreign` (`sales_approver`),
  ADD KEY `contracts_ppc_approver_foreign` (`ppc_approver`),
  ADD KEY `contracts_dev_engineering_approver_foreign` (`dev_engineering_approver`),
  ADD KEY `contracts_quality_approver_foreign` (`quality_approver`),
  ADD KEY `contracts_order_no_index` (`order_no`),
  ADD KEY `contracts_article_id_foreign` (`article_id`);

--
-- Indexes for table `contract_requirements`
--
ALTER TABLE `contract_requirements`
  ADD PRIMARY KEY (`id`),
  ADD KEY `contract_requirements_contract_id_foreign` (`contract_id`);

--
-- Indexes for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Indexes for table `history_activities`
--
ALTER TABLE `history_activities`
  ADD PRIMARY KEY (`id`),
  ADD KEY `history_activities_user_id_foreign` (`user_id`);

--
-- Indexes for table `jobs`
--
ALTER TABLE `jobs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `jobs_queue_index` (`queue`);

--
-- Indexes for table `job_batches`
--
ALTER TABLE `job_batches`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `negotiate`
--
ALTER TABLE `negotiate`
  ADD PRIMARY KEY (`id`),
  ADD KEY `negotiate_quotation_id_foreign` (`quotation_id`),
  ADD KEY `negotiate_user_id_foreign` (`user_id`);

--
-- Indexes for table `notifications`
--
ALTER TABLE `notifications`
  ADD PRIMARY KEY (`id`),
  ADD KEY `notifications_notifiable_type_notifiable_id_index` (`notifiable_type`,`notifiable_id`);

--
-- Indexes for table `password_reset_tokens`
--
ALTER TABLE `password_reset_tokens`
  ADD PRIMARY KEY (`email`);

--
-- Indexes for table `purchase_orders`
--
ALTER TABLE `purchase_orders`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `purchase_orders_po_no_unique` (`po_no`),
  ADD KEY `purchase_orders_quotation_id_foreign` (`quotation_id`),
  ADD KEY `purchase_orders_customer_id_foreign` (`customer_id`);

--
-- Indexes for table `purchase_order_internals`
--
ALTER TABLE `purchase_order_internals`
  ADD PRIMARY KEY (`id`),
  ADD KEY `purchase_order_internals_purchase_order_id_foreign` (`purchase_order_id`);

--
-- Indexes for table `purchase_order_items`
--
ALTER TABLE `purchase_order_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `purchase_order_items_purchase_order_id_foreign` (`purchase_order_id`);

--
-- Indexes for table `quotations`
--
ALTER TABLE `quotations`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `quotations_quotation_no_unique` (`quotation_no`),
  ADD KEY `quotations_customer_id_foreign` (`customer_id`),
  ADD KEY `quotations_request_id_foreign` (`request_id`);

--
-- Indexes for table `quotation_items`
--
ALTER TABLE `quotation_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `quotation_items_quotation_id_foreign` (`quotation_id`);

--
-- Indexes for table `request_attachments`
--
ALTER TABLE `request_attachments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `request_attachments_request_id_foreign` (`request_id`);

--
-- Indexes for table `request_projects`
--
ALTER TABLE `request_projects`
  ADD PRIMARY KEY (`id`),
  ADD KEY `request_projects_customer_id_foreign` (`customer_id`);

--
-- Indexes for table `request_project_assignments`
--
ALTER TABLE `request_project_assignments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `request_project_assignments_request_project_id_foreign` (`request_project_id`),
  ADD KEY `request_project_assignments_sales_id_foreign` (`sales_id`);

--
-- Indexes for table `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sessions_user_id_index` (`user_id`),
  ADD KEY `sessions_last_activity_index` (`last_activity`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `account_table`
--
ALTER TABLE `account_table`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `articles`
--
ALTER TABLE `articles`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `contracts`
--
ALTER TABLE `contracts`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `contract_requirements`
--
ALTER TABLE `contract_requirements`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=154;

--
-- AUTO_INCREMENT for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `history_activities`
--
ALTER TABLE `history_activities`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=126;

--
-- AUTO_INCREMENT for table `jobs`
--
ALTER TABLE `jobs`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

--
-- AUTO_INCREMENT for table `negotiate`
--
ALTER TABLE `negotiate`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `purchase_orders`
--
ALTER TABLE `purchase_orders`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `purchase_order_internals`
--
ALTER TABLE `purchase_order_internals`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT for table `purchase_order_items`
--
ALTER TABLE `purchase_order_items`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `quotations`
--
ALTER TABLE `quotations`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `quotation_items`
--
ALTER TABLE `quotation_items`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT for table `request_attachments`
--
ALTER TABLE `request_attachments`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=28;

--
-- AUTO_INCREMENT for table `request_projects`
--
ALTER TABLE `request_projects`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=41;

--
-- AUTO_INCREMENT for table `request_project_assignments`
--
ALTER TABLE `request_project_assignments`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=32;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `account_table`
--
ALTER TABLE `account_table`
  ADD CONSTRAINT `account_table_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `articles`
--
ALTER TABLE `articles`
  ADD CONSTRAINT `customer_id_foreign` FOREIGN KEY (`customer_id`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE SET NULL;

--
-- Constraints for table `contracts`
--
ALTER TABLE `contracts`
  ADD CONSTRAINT `contracts_article_id_foreign` FOREIGN KEY (`article_id`) REFERENCES `articles` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `contracts_customer_id_foreign` FOREIGN KEY (`customer_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `contracts_dev_engineering_approver_foreign` FOREIGN KEY (`dev_engineering_approver`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `contracts_ppc_approver_foreign` FOREIGN KEY (`ppc_approver`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `contracts_quality_approver_foreign` FOREIGN KEY (`quality_approver`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `contracts_quotation_id_foreign` FOREIGN KEY (`quotation_id`) REFERENCES `quotations` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `contracts_sales_approver_foreign` FOREIGN KEY (`sales_approver`) REFERENCES `users` (`id`);

--
-- Constraints for table `contract_requirements`
--
ALTER TABLE `contract_requirements`
  ADD CONSTRAINT `contract_requirements_contract_id_foreign` FOREIGN KEY (`contract_id`) REFERENCES `contracts` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `history_activities`
--
ALTER TABLE `history_activities`
  ADD CONSTRAINT `history_activities_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `negotiate`
--
ALTER TABLE `negotiate`
  ADD CONSTRAINT `negotiate_quotation_id_foreign` FOREIGN KEY (`quotation_id`) REFERENCES `quotations` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `negotiate_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `purchase_orders`
--
ALTER TABLE `purchase_orders`
  ADD CONSTRAINT `purchase_orders_customer_id_foreign` FOREIGN KEY (`customer_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `purchase_orders_quotation_id_foreign` FOREIGN KEY (`quotation_id`) REFERENCES `quotations` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `purchase_order_internals`
--
ALTER TABLE `purchase_order_internals`
  ADD CONSTRAINT `purchase_order_internals_purchase_order_id_foreign` FOREIGN KEY (`purchase_order_id`) REFERENCES `purchase_orders` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `purchase_order_items`
--
ALTER TABLE `purchase_order_items`
  ADD CONSTRAINT `purchase_order_items_purchase_order_id_foreign` FOREIGN KEY (`purchase_order_id`) REFERENCES `purchase_orders` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `quotations`
--
ALTER TABLE `quotations`
  ADD CONSTRAINT `quotations_customer_id_foreign` FOREIGN KEY (`customer_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `quotations_request_id_foreign` FOREIGN KEY (`request_id`) REFERENCES `request_projects` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `quotation_items`
--
ALTER TABLE `quotation_items`
  ADD CONSTRAINT `quotation_items_quotation_id_foreign` FOREIGN KEY (`quotation_id`) REFERENCES `quotations` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `request_attachments`
--
ALTER TABLE `request_attachments`
  ADD CONSTRAINT `request_attachments_request_id_foreign` FOREIGN KEY (`request_id`) REFERENCES `request_projects` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `request_projects`
--
ALTER TABLE `request_projects`
  ADD CONSTRAINT `request_projects_customer_id_foreign` FOREIGN KEY (`customer_id`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `request_project_assignments`
--
ALTER TABLE `request_project_assignments`
  ADD CONSTRAINT `request_project_assignments_request_project_id_foreign` FOREIGN KEY (`request_project_id`) REFERENCES `request_projects` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `request_project_assignments_sales_id_foreign` FOREIGN KEY (`sales_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
