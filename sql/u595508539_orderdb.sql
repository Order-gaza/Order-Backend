-- phpMyAdmin SQL Dump
-- version 5.2.2
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: Apr 21, 2026 at 06:03 PM
-- Server version: 11.8.6-MariaDB-log
-- PHP Version: 7.2.34

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `u595508539_orderdb`
--

-- --------------------------------------------------------

--
-- Table structure for table `activity_logs`
--

CREATE TABLE `activity_logs` (
  `id` char(36) NOT NULL DEFAULT uuid(),
  `user_id` char(36) DEFAULT NULL,
  `action` varchar(50) NOT NULL,
  `entity_type` varchar(50) NOT NULL,
  `entity_id` varchar(36) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `activity_logs`
--

INSERT INTO `activity_logs` (`id`, `user_id`, `action`, `entity_type`, `entity_id`, `description`, `ip_address`, `created_at`) VALUES
('002fae5a-3474-11f1-9448-e66239f31cb0', '8f266f22-1d9d-11f1-b82d-97044e7d8834', 'login', 'user', '8f266f22-1d9d-11f1-b82d-97044e7d8834', 'Super_admin logged in', '45.109.112.29', '2026-04-10 00:27:04'),
('017f3bf7-3d99-11f1-9448-e66239f31cb0', 'e64dd274-3d20-11f1-9448-e66239f31cb0', 'dispatch', 'order', 'f5d055ec-9300-445f-af99-77077d8cc519', 'Dispatched order ORD-2026-000015 for delivery', '154.143.246.144', '2026-04-21 18:44:38'),
('024dfbd6-351d-11f1-9448-e66239f31cb0', '4f792c75-4a65-45a7-a0ef-d282acd83d87', 'logout', 'user', '4f792c75-4a65-45a7-a0ef-d282acd83d87', 'Vendor logged out', '196.136.12.153', '2026-04-10 20:36:53'),
('0250c6ab-238d-11f1-b82d-97044e7d8834', '8f266f22-1d9d-11f1-b82d-97044e7d8834', 'change_password', 'user', '8f266f22-1d9d-11f1-b82d-97044e7d8834', 'Password changed via profile', '156.217.99.167', '2026-03-19 12:11:50'),
('02cd7698-3509-11f1-9448-e66239f31cb0', '4f792c75-4a65-45a7-a0ef-d282acd83d87', 'login', 'user', '4f792c75-4a65-45a7-a0ef-d282acd83d87', 'Vendor logged in', '197.223.152.7', '2026-04-10 18:13:44'),
('02ecacdf-2f0c-11f1-9448-e66239f31cb0', '8f266f22-1d9d-11f1-b82d-97044e7d8834', 'logout', 'user', '8f266f22-1d9d-11f1-b82d-97044e7d8834', 'Super_admin logged out', '197.223.138.29', '2026-04-03 03:20:06'),
('02f73047-35de-11f1-9448-e66239f31cb0', 'd8e13ad8-2382-11f1-b82d-97044e7d8834', 'login', 'user', 'd8e13ad8-2382-11f1-b82d-97044e7d8834', 'Admin logged in', '197.150.164.48', '2026-04-11 19:38:27'),
('0329aff0-26f5-11f1-abf9-e1b0d104a220', '8f266f22-1d9d-11f1-b82d-97044e7d8834', 'approve', 'vendor', 'e4a9a3cd-26f4-11f1-abf9-e1b0d104a220', 'Approved vendor: gaza store', '45.109.19.74', '2026-03-23 20:15:18'),
('0366a4bf-2394-11f1-b82d-97044e7d8834', '8f266f22-1d9d-11f1-b82d-97044e7d8834', 'login', 'user', '8f266f22-1d9d-11f1-b82d-97044e7d8834', 'Super_admin logged in', '156.217.99.167', '2026-03-19 13:01:58'),
('037fafdc-3526-11f1-9448-e66239f31cb0', 'd8e13ad8-2382-11f1-b82d-97044e7d8834', 'login', 'user', 'd8e13ad8-2382-11f1-b82d-97044e7d8834', 'Admin logged in', '196.136.46.60', '2026-04-10 21:41:20'),
('041934fd-3d90-11f1-9448-e66239f31cb0', '68d947ef-fcec-426a-8316-155666848619', 'mark_preparing', 'order', '8f957033-af0e-4c09-a600-74344e7b1485', 'Order being prepared', '154.143.246.144', '2026-04-21 17:40:17'),
('041a850c-294a-11f1-9448-e66239f31cb0', '8f266f22-1d9d-11f1-b82d-97044e7d8834', 'login', 'user', '8f266f22-1d9d-11f1-b82d-97044e7d8834', 'Super_admin logged in', '45.109.220.74', '2026-03-26 19:28:49'),
('04315f3d-35ce-11f1-9448-e66239f31cb0', '4f792c75-4a65-45a7-a0ef-d282acd83d87', 'login', 'user', '4f792c75-4a65-45a7-a0ef-d282acd83d87', 'Vendor logged in', '197.150.164.48', '2026-04-11 17:43:57'),
('04776550-2f83-11f1-9448-e66239f31cb0', '4f792c75-4a65-45a7-a0ef-d282acd83d87', 'update_profile', 'vendor', '57e1966b-294a-11f1-9448-e66239f31cb0', 'Updated vendor profile', '197.222.117.240', '2026-04-03 17:31:58'),
('04b5b8b8-3697-11f1-9448-e66239f31cb0', 'd8e13ad8-2382-11f1-b82d-97044e7d8834', 'dispatch', 'order', '7175c222-70ed-49bf-929d-11fb434f107a', 'Dispatched order ORD-2026-000005 for delivery', '154.143.40.184', '2026-04-12 20:42:47'),
('04c78653-303a-11f1-9448-e66239f31cb0', '8f266f22-1d9d-11f1-b82d-97044e7d8834', 'login', 'user', '8f266f22-1d9d-11f1-b82d-97044e7d8834', 'Super_admin logged in', '154.143.143.60', '2026-04-04 15:21:57'),
('05150223-3d99-11f1-9448-e66239f31cb0', 'e64dd274-3d20-11f1-9448-e66239f31cb0', 'deliver', 'order', 'f5d055ec-9300-445f-af99-77077d8cc519', 'Marked order ORD-2026-000015 as delivered', '154.143.246.144', '2026-04-21 18:44:44'),
('0560360d-3d15-11f1-9448-e66239f31cb0', 'd8e13ad8-2382-11f1-b82d-97044e7d8834', 'login', 'user', 'd8e13ad8-2382-11f1-b82d-97044e7d8834', 'Admin logged in', '154.143.246.144', '2026-04-21 02:59:51'),
('0575e1cb-3345-11f1-9448-e66239f31cb0', '4f792c75-4a65-45a7-a0ef-d282acd83d87', 'logout', 'user', '4f792c75-4a65-45a7-a0ef-d282acd83d87', 'Vendor logged out', '196.151.0.29', '2026-04-08 12:18:16'),
('05a4854a-2fc5-11f1-9448-e66239f31cb0', '8f266f22-1d9d-11f1-b82d-97044e7d8834', 'login', 'user', '8f266f22-1d9d-11f1-b82d-97044e7d8834', 'Super_admin logged in', '197.150.173.176', '2026-04-04 01:24:27'),
('05cf5fc6-3d90-11f1-9448-e66239f31cb0', '68d947ef-fcec-426a-8316-155666848619', 'mark_ready', 'order', '8f957033-af0e-4c09-a600-74344e7b1485', 'All vendors ready for pickup', '154.143.246.144', '2026-04-21 17:40:20'),
('06004039-3d21-11f1-9448-e66239f31cb0', 'e64dd274-3d20-11f1-9448-e66239f31cb0', 'login', 'user', 'e64dd274-3d20-11f1-9448-e66239f31cb0', 'Admin logged in', '154.143.246.144', '2026-04-21 04:25:46'),
('06251ef7-1e86-11f1-b82d-97044e7d8834', '8f266f22-1d9d-11f1-b82d-97044e7d8834', 'logout', 'user', '8f266f22-1d9d-11f1-b82d-97044e7d8834', 'Super_admin logged out', '62.114.227.105', '2026-03-13 02:39:14'),
('063279f5-2eff-11f1-9448-e66239f31cb0', 'be9db6e5-2efe-11f1-9448-e66239f31cb0', 'login', 'user', 'be9db6e5-2efe-11f1-9448-e66239f31cb0', 'Vendor logged in', '197.223.138.29', '2026-04-03 01:47:08'),
('0660c097-231b-11f1-b82d-97044e7d8834', '8f266f22-1d9d-11f1-b82d-97044e7d8834', 'logout', 'user', '8f266f22-1d9d-11f1-b82d-97044e7d8834', 'Super_admin logged out', '45.104.153.29', '2026-03-18 22:35:54'),
('06803643-35cc-11f1-9448-e66239f31cb0', '8f266f22-1d9d-11f1-b82d-97044e7d8834', 'update', 'advertisement', 'f5e9cc4e-2ef6-11f1-9448-e66239f31cb0', 'Updated advertisement: add one', '197.150.164.48', '2026-04-11 17:29:42'),
('069e1b1e-2ef7-11f1-9448-e66239f31cb0', '8f266f22-1d9d-11f1-b82d-97044e7d8834', 'update', 'advertisement', 'f5e9cc4e-2ef6-11f1-9448-e66239f31cb0', 'Uploaded image for advertisement', '197.223.138.29', '2026-04-03 00:49:52'),
('06c08791-33bb-11f1-9448-e66239f31cb0', '4f792c75-4a65-45a7-a0ef-d282acd83d87', 'logout', 'user', '4f792c75-4a65-45a7-a0ef-d282acd83d87', 'Vendor logged out', '154.143.143.60', '2026-04-09 02:22:59'),
('06c78d67-3697-11f1-9448-e66239f31cb0', 'd8e13ad8-2382-11f1-b82d-97044e7d8834', 'deliver', 'order', '7175c222-70ed-49bf-929d-11fb434f107a', 'Marked order ORD-2026-000005 as delivered', '154.143.40.184', '2026-04-12 20:42:50'),
('06d6cdb0-35cc-11f1-9448-e66239f31cb0', '8f266f22-1d9d-11f1-b82d-97044e7d8834', 'update', 'advertisement', 'f5e9cc4e-2ef6-11f1-9448-e66239f31cb0', 'Uploaded image for advertisement', '197.150.164.48', '2026-04-11 17:29:42'),
('0716ffa1-35de-11f1-9448-e66239f31cb0', 'd8e13ad8-2382-11f1-b82d-97044e7d8834', 'confirm', 'order', '1e8023a6-a8d0-42d5-b4bb-4c5317631166', 'Confirmed and sent order ORD-2026-000004 to vendor(s)', '197.150.164.48', '2026-04-11 19:38:34'),
('072db0e6-26f5-11f1-abf9-e1b0d104a220', '8f266f22-1d9d-11f1-b82d-97044e7d8834', 'logout', 'user', '8f266f22-1d9d-11f1-b82d-97044e7d8834', 'Super_admin logged out', '45.109.19.74', '2026-03-23 20:15:25'),
('073bba47-350f-11f1-9448-e66239f31cb0', '8f266f22-1d9d-11f1-b82d-97044e7d8834', 'suspend', 'vendor', 'be9dd1ee-2efe-11f1-9448-e66239f31cb0', 'Suspended vendor: gaza grill', '197.223.152.7', '2026-04-10 18:56:48'),
('07bf9ad0-3457-11f1-9448-e66239f31cb0', '8f266f22-1d9d-11f1-b82d-97044e7d8834', 'logout', 'user', '8f266f22-1d9d-11f1-b82d-97044e7d8834', 'Super_admin logged out', '45.109.112.29', '2026-04-09 20:59:42'),
('07d7f92c-3b30-11f1-9448-e66239f31cb0', 'd8e13ad8-2382-11f1-b82d-97044e7d8834', 'confirm', 'order', '937c9853-526f-41e1-9be0-06a69ff2b437', 'Confirmed and sent order ORD-2026-000007 to vendor(s)', '158.140.85.78', '2026-04-18 17:08:10'),
('080522c3-35ce-11f1-9448-e66239f31cb0', '4f792c75-4a65-45a7-a0ef-d282acd83d87', 'mark_preparing', 'order', 'a1c23979-4844-4910-b6c7-d96f1d5807af', 'Order being prepared', '197.150.164.48', '2026-04-11 17:44:03'),
('0816165c-2314-11f1-b82d-97044e7d8834', NULL, 'logout', 'user', 'cddc797e-1e85-11f1-b82d-97044e7d8834', 'Admin logged out', '45.104.153.29', '2026-03-18 21:45:50'),
('087c4912-351d-11f1-9448-e66239f31cb0', 'd8e13ad8-2382-11f1-b82d-97044e7d8834', 'login', 'user', 'd8e13ad8-2382-11f1-b82d-97044e7d8834', 'Admin logged in', '196.136.12.153', '2026-04-10 20:37:03'),
('0902910d-3039-11f1-9448-e66239f31cb0', '8f266f22-1d9d-11f1-b82d-97044e7d8834', 'update', 'advertisement', '5c703dba-2ef9-11f1-9448-e66239f31cb0', 'Updated advertisement: test add', '154.143.143.60', '2026-04-04 15:14:54'),
('09040690-2f67-11f1-9448-e66239f31cb0', '4f792c75-4a65-45a7-a0ef-d282acd83d87', 'update_profile', 'vendor', '57e1966b-294a-11f1-9448-e66239f31cb0', 'Updated vendor profile', '197.223.138.29', '2026-04-03 14:11:40'),
('096216e1-3039-11f1-9448-e66239f31cb0', '8f266f22-1d9d-11f1-b82d-97044e7d8834', 'update', 'advertisement', '5c703dba-2ef9-11f1-9448-e66239f31cb0', 'Uploaded image for advertisement', '154.143.143.60', '2026-04-04 15:14:55'),
('097871d6-33ab-11f1-9448-e66239f31cb0', 'd8e13ad8-2382-11f1-b82d-97044e7d8834', 'logout', 'user', 'd8e13ad8-2382-11f1-b82d-97044e7d8834', 'Admin logged out', '154.143.143.60', '2026-04-09 00:28:31'),
('09d4ac7c-303a-11f1-9448-e66239f31cb0', '8f266f22-1d9d-11f1-b82d-97044e7d8834', 'delete', 'admin', '5920fb61-26f4-11f1-abf9-e1b0d104a220', 'Deleted admin: loay (loay@gmail.com)', '154.143.143.60', '2026-04-04 15:22:05'),
('0afdc5c5-35ce-11f1-9448-e66239f31cb0', '4f792c75-4a65-45a7-a0ef-d282acd83d87', 'mark_ready', 'order', 'a1c23979-4844-4910-b6c7-d96f1d5807af', 'فتح الله ماركت ready, waiting for other vendors', '197.150.164.48', '2026-04-11 17:44:08'),
('0b16161f-35cb-11f1-9448-e66239f31cb0', 'be9db6e5-2efe-11f1-9448-e66239f31cb0', 'login', 'user', 'be9db6e5-2efe-11f1-9448-e66239f31cb0', 'Vendor logged in', '197.150.164.48', '2026-04-11 17:22:40'),
('0b7d41b3-35de-11f1-9448-e66239f31cb0', 'd8e13ad8-2382-11f1-b82d-97044e7d8834', 'logout', 'user', 'd8e13ad8-2382-11f1-b82d-97044e7d8834', 'Admin logged out', '197.150.164.48', '2026-04-11 19:38:41'),
('0c6b3f52-238e-11f1-b82d-97044e7d8834', '8f266f22-1d9d-11f1-b82d-97044e7d8834', 'login', 'user', '8f266f22-1d9d-11f1-b82d-97044e7d8834', 'Super_admin logged in', '156.217.99.167', '2026-03-19 12:19:16'),
('0dacaaa4-2385-11f1-b82d-97044e7d8834', '8f266f22-1d9d-11f1-b82d-97044e7d8834', 'deactivated', 'admin', 'd8e13ad8-2382-11f1-b82d-97044e7d8834', 'Deactivated admin: admin1', '156.217.99.167', '2026-03-19 11:14:53'),
('0e67af3d-3457-11f1-9448-e66239f31cb0', '4f792c75-4a65-45a7-a0ef-d282acd83d87', 'login', 'user', '4f792c75-4a65-45a7-a0ef-d282acd83d87', 'Vendor logged in', '45.109.112.29', '2026-04-09 20:59:53'),
('0e6b7798-3512-11f1-9448-e66239f31cb0', 'cf2239d2-2dd7-4116-abd3-1f24d48e8fe7', 'logout', 'user', 'cf2239d2-2dd7-4116-abd3-1f24d48e8fe7', 'Vendor logged out', '197.223.152.7', '2026-04-10 19:18:29'),
('0e91cfec-3b32-11f1-9448-e66239f31cb0', '8ba11278-3b31-11f1-9448-e66239f31cb0', 'activated', 'delivery_zone', '8f2829db-1d9d-11f1-b82d-97044e7d8834', 'Activated delivery zone: Zone 2 - Close (2 to 5 km)', '158.140.85.78', '2026-04-18 17:22:40'),
('0f10412a-3d90-11f1-9448-e66239f31cb0', '68d947ef-fcec-426a-8316-155666848619', 'logout', 'user', '68d947ef-fcec-426a-8316-155666848619', 'Vendor logged out', '154.143.246.144', '2026-04-21 17:40:36'),
('0f2945e6-26f5-11f1-abf9-e1b0d104a220', 'b8175b08-34e6-4f5d-87d3-4c777677db33', 'login', 'user', 'b8175b08-34e6-4f5d-87d3-4c777677db33', 'Vendor logged in', '45.109.19.74', '2026-03-23 20:15:38'),
('0f41d538-2f60-11f1-9448-e66239f31cb0', '4f792c75-4a65-45a7-a0ef-d282acd83d87', 'closed', 'vendor', '57e1966b-294a-11f1-9448-e66239f31cb0', 'Closed store: gaza', '197.223.138.29', '2026-04-03 13:21:44'),
('0f5e1f5f-2f0c-11f1-9448-e66239f31cb0', '4f792c75-4a65-45a7-a0ef-d282acd83d87', 'login', 'user', '4f792c75-4a65-45a7-a0ef-d282acd83d87', 'Vendor logged in', '197.223.138.29', '2026-04-03 03:20:26'),
('0f602162-1e86-11f1-b82d-97044e7d8834', NULL, 'login', 'user', 'cddc797e-1e85-11f1-b82d-97044e7d8834', 'Admin logged in', '62.114.227.105', '2026-03-13 02:39:29'),
('0f6c4325-2f0a-11f1-9448-e66239f31cb0', 'd8e13ad8-2382-11f1-b82d-97044e7d8834', 'update', 'delivery_zone', '8f2829db-1d9d-11f1-b82d-97044e7d8834', 'Updated delivery zone: Zone 2 - Close (2 to 5 km)', '197.223.138.29', '2026-04-03 03:06:08'),
('0faa8c63-3039-11f1-9448-e66239f31cb0', '8f266f22-1d9d-11f1-b82d-97044e7d8834', 'update', 'advertisement', 'f5e9cc4e-2ef6-11f1-9448-e66239f31cb0', 'Updated advertisement: add one', '154.143.143.60', '2026-04-04 15:15:05'),
('1009c265-3039-11f1-9448-e66239f31cb0', '8f266f22-1d9d-11f1-b82d-97044e7d8834', 'update', 'advertisement', 'f5e9cc4e-2ef6-11f1-9448-e66239f31cb0', 'Uploaded image for advertisement', '154.143.143.60', '2026-04-04 15:15:06'),
('1059fb4a-350f-11f1-9448-e66239f31cb0', '8f266f22-1d9d-11f1-b82d-97044e7d8834', 'reactivate', 'vendor', 'be9dd1ee-2efe-11f1-9448-e66239f31cb0', 'Reactivated vendor: gaza grill', '197.223.152.7', '2026-04-10 18:57:04'),
('10d10546-2fcb-11f1-9448-e66239f31cb0', 'd8e13ad8-2382-11f1-b82d-97044e7d8834', 'login', 'user', 'd8e13ad8-2382-11f1-b82d-97044e7d8834', 'Admin logged in', '197.150.173.176', '2026-04-04 02:07:43'),
('1134da36-3d21-11f1-9448-e66239f31cb0', 'e64dd274-3d20-11f1-9448-e66239f31cb0', 'logout', 'user', 'e64dd274-3d20-11f1-9448-e66239f31cb0', 'Admin logged out', '154.143.246.144', '2026-04-21 04:26:05'),
('117c474c-2f92-11f1-9448-e66239f31cb0', '8f266f22-1d9d-11f1-b82d-97044e7d8834', 'deactivated', 'customer', 'a093003a-6eaf-42cf-92e0-f4660c0550e2', 'Deactivated customer: Test User', '197.222.117.240', '2026-04-03 19:19:43'),
('11d3aec9-238e-11f1-b82d-97044e7d8834', '8f266f22-1d9d-11f1-b82d-97044e7d8834', 'logout', 'user', '8f266f22-1d9d-11f1-b82d-97044e7d8834', 'Super_admin logged out', '156.217.99.167', '2026-03-19 12:19:25'),
('120e2939-3351-11f1-9448-e66239f31cb0', '4f792c75-4a65-45a7-a0ef-d282acd83d87', 'logout', 'user', '4f792c75-4a65-45a7-a0ef-d282acd83d87', 'Vendor logged out', '196.151.0.29', '2026-04-08 13:44:31'),
('13283e45-2f92-11f1-9448-e66239f31cb0', '8f266f22-1d9d-11f1-b82d-97044e7d8834', 'deactivated', 'customer', 'ad30e781-9ca7-4f1f-825f-40b0d4d4b8c0', 'Deactivated customer: Test User', '197.222.117.240', '2026-04-03 19:19:45'),
('1364e076-2eff-11f1-9448-e66239f31cb0', 'be9db6e5-2efe-11f1-9448-e66239f31cb0', 'logout', 'user', 'be9db6e5-2efe-11f1-9448-e66239f31cb0', 'Vendor logged out', '197.223.138.29', '2026-04-03 01:47:30'),
('13fa2af5-3d16-11f1-9448-e66239f31cb0', '4f792c75-4a65-45a7-a0ef-d282acd83d87', 'login', 'user', '4f792c75-4a65-45a7-a0ef-d282acd83d87', 'Vendor logged in', '154.143.246.144', '2026-04-21 03:07:25'),
('14185ed2-2385-11f1-b82d-97044e7d8834', '8f266f22-1d9d-11f1-b82d-97044e7d8834', 'logout', 'user', '8f266f22-1d9d-11f1-b82d-97044e7d8834', 'Super_admin logged out', '156.217.99.167', '2026-03-19 11:15:04'),
('143c5ca1-3512-11f1-9448-e66239f31cb0', '4f792c75-4a65-45a7-a0ef-d282acd83d87', 'login', 'user', '4f792c75-4a65-45a7-a0ef-d282acd83d87', 'Vendor logged in', '197.223.152.7', '2026-04-10 19:18:39'),
('14454438-3513-11f1-9448-e66239f31cb0', '4f792c75-4a65-45a7-a0ef-d282acd83d87', 'logout', 'user', '4f792c75-4a65-45a7-a0ef-d282acd83d87', 'Vendor logged out', '197.223.152.7', '2026-04-10 19:25:48'),
('144cc3ee-35fb-11f1-9448-e66239f31cb0', '8f266f22-1d9d-11f1-b82d-97044e7d8834', 'logout', 'user', '8f266f22-1d9d-11f1-b82d-97044e7d8834', 'Super_admin logged out', '154.143.40.184', '2026-04-11 23:06:31'),
('15157f3f-2f67-11f1-9448-e66239f31cb0', '4f792c75-4a65-45a7-a0ef-d282acd83d87', 'logout', 'user', '4f792c75-4a65-45a7-a0ef-d282acd83d87', 'Vendor logged out', '197.223.138.29', '2026-04-03 14:12:00'),
('1526565d-2f60-11f1-9448-e66239f31cb0', '4f792c75-4a65-45a7-a0ef-d282acd83d87', 'update_profile', 'vendor', '57e1966b-294a-11f1-9448-e66239f31cb0', 'Updated vendor profile', '197.223.138.29', '2026-04-03 13:21:54'),
('15a7b9fb-35cc-11f1-9448-e66239f31cb0', '8f266f22-1d9d-11f1-b82d-97044e7d8834', 'update', 'advertisement', '5c703dba-2ef9-11f1-9448-e66239f31cb0', 'Updated advertisement: gaza ad', '197.150.164.48', '2026-04-11 17:30:07'),
('15b100a3-31fe-11f1-9448-e66239f31cb0', 'cf2239d2-2dd7-4116-abd3-1f24d48e8fe7', 'create', 'menu_category', '15b0e0e6-31fe-11f1-9448-e66239f31cb0', 'Created category: food', '154.143.143.60', '2026-04-06 21:17:58'),
('15b8c404-3b31-11f1-9448-e66239f31cb0', '4f792c75-4a65-45a7-a0ef-d282acd83d87', 'logout', 'user', '4f792c75-4a65-45a7-a0ef-d282acd83d87', 'Vendor logged out', '158.140.85.78', '2026-04-18 17:15:42'),
('15e3b1ca-339f-11f1-9448-e66239f31cb0', '8f266f22-1d9d-11f1-b82d-97044e7d8834', 'login', 'user', '8f266f22-1d9d-11f1-b82d-97044e7d8834', 'Super_admin logged in', '154.143.143.60', '2026-04-08 23:02:58'),
('15fd0f55-35cc-11f1-9448-e66239f31cb0', '8f266f22-1d9d-11f1-b82d-97044e7d8834', 'update', 'advertisement', '5c703dba-2ef9-11f1-9448-e66239f31cb0', 'Uploaded image for advertisement', '197.150.164.48', '2026-04-11 17:30:08'),
('15ff7afc-2fcb-11f1-9448-e66239f31cb0', 'd8e13ad8-2382-11f1-b82d-97044e7d8834', 'logout', 'user', 'd8e13ad8-2382-11f1-b82d-97044e7d8834', 'Admin logged out', '197.150.173.176', '2026-04-04 02:07:51'),
('162de860-3345-11f1-9448-e66239f31cb0', '8f266f22-1d9d-11f1-b82d-97044e7d8834', 'login', 'user', '8f266f22-1d9d-11f1-b82d-97044e7d8834', 'Super_admin logged in', '196.151.0.29', '2026-04-08 12:18:44'),
('169463a7-3071-11f1-9448-e66239f31cb0', '8f266f22-1d9d-11f1-b82d-97044e7d8834', 'suspend', 'vendor', '57e1966b-294a-11f1-9448-e66239f31cb0', 'Suspended vendor: gaza', '176.65.2.214', '2026-04-04 21:56:09'),
('16e536e1-1e86-11f1-b82d-97044e7d8834', NULL, 'logout', 'user', 'cddc797e-1e85-11f1-b82d-97044e7d8834', 'Admin logged out', '62.114.227.105', '2026-03-13 02:39:42'),
('171bec50-3d90-11f1-9448-e66239f31cb0', 'e64dd274-3d20-11f1-9448-e66239f31cb0', 'login', 'user', 'e64dd274-3d20-11f1-9448-e66239f31cb0', 'Admin logged in', '154.143.246.144', '2026-04-21 17:40:49'),
('1787a14f-3202-11f1-9448-e66239f31cb0', 'cf2239d2-2dd7-4116-abd3-1f24d48e8fe7', 'logout', 'user', 'cf2239d2-2dd7-4116-abd3-1f24d48e8fe7', 'Vendor logged out', '154.143.143.60', '2026-04-06 21:46:39'),
('179577fc-3509-11f1-9448-e66239f31cb0', '4f792c75-4a65-45a7-a0ef-d282acd83d87', 'logout', 'user', '4f792c75-4a65-45a7-a0ef-d282acd83d87', 'Vendor logged out', '197.223.152.7', '2026-04-10 18:14:19'),
('17f8c5ab-3583-11f1-9448-e66239f31cb0', '4f792c75-4a65-45a7-a0ef-d282acd83d87', 'update_profile', 'vendor', '57e1966b-294a-11f1-9448-e66239f31cb0', 'Updated vendor profile', '196.136.158.162', '2026-04-11 08:47:38'),
('18126f8a-3071-11f1-9448-e66239f31cb0', '8f266f22-1d9d-11f1-b82d-97044e7d8834', 'reactivate', 'vendor', '57e1966b-294a-11f1-9448-e66239f31cb0', 'Reactivated vendor: gaza', '176.65.2.214', '2026-04-04 21:56:11'),
('183ddb67-3d21-11f1-9448-e66239f31cb0', 'f52cc98d-3d20-11f1-9448-e66239f31cb0', 'login', 'user', 'f52cc98d-3d20-11f1-9448-e66239f31cb0', 'Admin logged in', '154.143.246.144', '2026-04-21 04:26:17'),
('18a001f2-2394-11f1-b82d-97044e7d8834', '8f266f22-1d9d-11f1-b82d-97044e7d8834', 'change_password', 'user', '8f266f22-1d9d-11f1-b82d-97044e7d8834', 'Password changed via profile', '156.217.99.167', '2026-03-19 13:02:34'),
('18dd3a1c-35ce-11f1-9448-e66239f31cb0', '4f792c75-4a65-45a7-a0ef-d282acd83d87', 'logout', 'user', '4f792c75-4a65-45a7-a0ef-d282acd83d87', 'Vendor logged out', '197.150.164.48', '2026-04-11 17:44:32'),
('191708f4-35cc-11f1-9448-e66239f31cb0', '8f266f22-1d9d-11f1-b82d-97044e7d8834', 'update', 'advertisement', 'f5e9cc4e-2ef6-11f1-9448-e66239f31cb0', 'Updated advertisement: add one', '197.150.164.48', '2026-04-11 17:30:13'),
('1a9cdcf8-294a-11f1-9448-e66239f31cb0', '8f266f22-1d9d-11f1-b82d-97044e7d8834', 'logout', 'user', '8f266f22-1d9d-11f1-b82d-97044e7d8834', 'Super_admin logged out', '45.109.220.74', '2026-03-26 19:29:27'),
('1aa8b8de-3d15-11f1-9448-e66239f31cb0', 'd8e13ad8-2382-11f1-b82d-97044e7d8834', 'update', 'delivery_zone_pricing', NULL, 'Updated zone pricing: north_south = 30', '154.143.246.144', '2026-04-21 03:00:27'),
('1aea6086-350f-11f1-9448-e66239f31cb0', '8f266f22-1d9d-11f1-b82d-97044e7d8834', 'logout', 'user', '8f266f22-1d9d-11f1-b82d-97044e7d8834', 'Super_admin logged out', '197.223.152.7', '2026-04-10 18:57:21'),
('1b066c9b-3526-11f1-9448-e66239f31cb0', 'd8e13ad8-2382-11f1-b82d-97044e7d8834', 'logout', 'user', 'd8e13ad8-2382-11f1-b82d-97044e7d8834', 'Admin logged out', '196.136.46.60', '2026-04-10 21:42:00'),
('1b9b4fd9-3d21-11f1-9448-e66239f31cb0', 'f52cc98d-3d20-11f1-9448-e66239f31cb0', 'logout', 'user', 'f52cc98d-3d20-11f1-9448-e66239f31cb0', 'Admin logged out', '154.143.246.144', '2026-04-21 04:26:23'),
('1beda29c-2f67-11f1-9448-e66239f31cb0', '8f266f22-1d9d-11f1-b82d-97044e7d8834', 'login', 'user', '8f266f22-1d9d-11f1-b82d-97044e7d8834', 'Super_admin logged in', '197.223.138.29', '2026-04-03 14:12:12'),
('1cb9b5c4-2eff-11f1-9448-e66239f31cb0', '8f266f22-1d9d-11f1-b82d-97044e7d8834', 'login', 'user', '8f266f22-1d9d-11f1-b82d-97044e7d8834', 'Super_admin logged in', '197.223.138.29', '2026-04-03 01:47:45'),
('1d7a46fd-3d1c-11f1-9448-e66239f31cb0', '4f792c75-4a65-45a7-a0ef-d282acd83d87', 'logout', 'user', '4f792c75-4a65-45a7-a0ef-d282acd83d87', 'Vendor logged out', '154.143.246.144', '2026-04-21 03:50:38'),
('1d8518e8-2f0a-11f1-9448-e66239f31cb0', 'd8e13ad8-2382-11f1-b82d-97044e7d8834', 'update', 'delivery_zone', '8f2829db-1d9d-11f1-b82d-97044e7d8834', 'Updated delivery zone: Zone 2 - Close (2 to 5 km)', '197.223.138.29', '2026-04-03 03:06:31'),
('1de045a4-33ab-11f1-9448-e66239f31cb0', '4f792c75-4a65-45a7-a0ef-d282acd83d87', 'login', 'user', '4f792c75-4a65-45a7-a0ef-d282acd83d87', 'Vendor logged in', '154.143.143.60', '2026-04-09 00:29:05'),
('1e599684-238d-11f1-b82d-97044e7d8834', '8f266f22-1d9d-11f1-b82d-97044e7d8834', 'change_password', 'user', '8f266f22-1d9d-11f1-b82d-97044e7d8834', 'Password changed via profile', '156.217.99.167', '2026-03-19 12:12:37'),
('1ea1cbea-3351-11f1-9448-e66239f31cb0', '8f266f22-1d9d-11f1-b82d-97044e7d8834', 'login', 'user', '8f266f22-1d9d-11f1-b82d-97044e7d8834', 'Super_admin logged in', '196.151.0.29', '2026-04-08 13:44:52'),
('1ea37c3a-3513-11f1-9448-e66239f31cb0', '8f266f22-1d9d-11f1-b82d-97044e7d8834', 'login', 'user', '8f266f22-1d9d-11f1-b82d-97044e7d8834', 'Super_admin logged in', '197.223.152.7', '2026-04-10 19:26:05'),
('1eae7b1e-3d25-11f1-9448-e66239f31cb0', '68d947ef-fcec-426a-8316-155666848619', 'login', 'user', '68d947ef-fcec-426a-8316-155666848619', 'Vendor logged in', '154.143.246.144', '2026-04-21 04:55:06'),
('1eb98af4-3d90-11f1-9448-e66239f31cb0', 'e64dd274-3d20-11f1-9448-e66239f31cb0', 'dispatch', 'order', '8f957033-af0e-4c09-a600-74344e7b1485', 'Dispatched order ORD-2026-000014 for delivery', '154.143.246.144', '2026-04-21 17:41:02'),
('1f144432-343f-11f1-9448-e66239f31cb0', 'd8e13ad8-2382-11f1-b82d-97044e7d8834', 'login', 'user', 'd8e13ad8-2382-11f1-b82d-97044e7d8834', 'Admin logged in', '154.143.86.111', '2026-04-09 18:08:33'),
('20715b72-3475-11f1-9448-e66239f31cb0', '4f792c75-4a65-45a7-a0ef-d282acd83d87', 'login', 'user', '4f792c75-4a65-45a7-a0ef-d282acd83d87', 'Vendor logged in', '45.109.112.29', '2026-04-10 00:35:08'),
('209ee7e3-3d21-11f1-9448-e66239f31cb0', '4f792c75-4a65-45a7-a0ef-d282acd83d87', 'login', 'user', '4f792c75-4a65-45a7-a0ef-d282acd83d87', 'Vendor logged in', '154.143.246.144', '2026-04-21 04:26:31'),
('20a2294e-2f7d-11f1-9448-e66239f31cb0', '8f266f22-1d9d-11f1-b82d-97044e7d8834', 'logout', 'user', '8f266f22-1d9d-11f1-b82d-97044e7d8834', 'Super_admin logged out', '197.222.117.240', '2026-04-03 16:49:49'),
('20ebb6ab-2f92-11f1-9448-e66239f31cb0', '8f266f22-1d9d-11f1-b82d-97044e7d8834', 'activated', 'customer', 'a093003a-6eaf-42cf-92e0-f4660c0550e2', 'Activated customer: Test User', '197.222.117.240', '2026-04-03 19:20:08'),
('212720f8-35ce-11f1-9448-e66239f31cb0', 'cf2239d2-2dd7-4116-abd3-1f24d48e8fe7', 'login', 'user', 'cf2239d2-2dd7-4116-abd3-1f24d48e8fe7', 'Vendor logged in', '197.150.164.48', '2026-04-11 17:44:46'),
('2141d1e2-3509-11f1-9448-e66239f31cb0', 'd8e13ad8-2382-11f1-b82d-97044e7d8834', 'login', 'user', 'd8e13ad8-2382-11f1-b82d-97044e7d8834', 'Admin logged in', '197.223.152.7', '2026-04-10 18:14:35'),
('21666342-3b55-11f1-9448-e66239f31cb0', '8f266f22-1d9d-11f1-b82d-97044e7d8834', 'logout', 'user', '8f266f22-1d9d-11f1-b82d-97044e7d8834', 'Super_admin logged out', '45.109.196.109', '2026-04-18 21:33:44'),
('21b4e956-3d90-11f1-9448-e66239f31cb0', 'e64dd274-3d20-11f1-9448-e66239f31cb0', 'deliver', 'order', '8f957033-af0e-4c09-a600-74344e7b1485', 'Marked order ORD-2026-000014 as delivered', '154.143.246.144', '2026-04-21 17:41:07'),
('21d3947a-2f92-11f1-9448-e66239f31cb0', '8f266f22-1d9d-11f1-b82d-97044e7d8834', 'activated', 'customer', 'ad30e781-9ca7-4f1f-825f-40b0d4d4b8c0', 'Activated customer: Test User', '197.222.117.240', '2026-04-03 19:20:10'),
('21f27f13-32c0-11f1-9448-e66239f31cb0', '4f792c75-4a65-45a7-a0ef-d282acd83d87', 'logout', 'user', '4f792c75-4a65-45a7-a0ef-d282acd83d87', 'Vendor logged out', '154.143.143.60', '2026-04-07 20:27:00'),
('22499e15-35cb-11f1-9448-e66239f31cb0', 'be9db6e5-2efe-11f1-9448-e66239f31cb0', 'logout', 'user', 'be9db6e5-2efe-11f1-9448-e66239f31cb0', 'Vendor logged out', '197.150.164.48', '2026-04-11 17:23:19'),
('22cc2c6b-238e-11f1-b82d-97044e7d8834', 'd8e13ad8-2382-11f1-b82d-97044e7d8834', 'login', 'user', 'd8e13ad8-2382-11f1-b82d-97044e7d8834', 'Admin logged in', '156.217.99.167', '2026-03-19 12:19:54'),
('236acc5a-339f-11f1-9448-e66239f31cb0', '8f266f22-1d9d-11f1-b82d-97044e7d8834', 'logout', 'user', '8f266f22-1d9d-11f1-b82d-97044e7d8834', 'Super_admin logged out', '154.143.143.60', '2026-04-08 23:03:21'),
('23c06f10-2f0b-11f1-9448-e66239f31cb0', '8f266f22-1d9d-11f1-b82d-97044e7d8834', 'logout', 'user', '8f266f22-1d9d-11f1-b82d-97044e7d8834', 'Super_admin logged out', '197.223.138.29', '2026-04-03 03:13:51'),
('244e39a4-3d15-11f1-9448-e66239f31cb0', 'd8e13ad8-2382-11f1-b82d-97044e7d8834', 'update', 'delivery_zone_pricing', NULL, 'Updated zone pricing: same_zone = 10', '154.143.246.144', '2026-04-21 03:00:43'),
('2494d6c8-3d16-11f1-9448-e66239f31cb0', '4f792c75-4a65-45a7-a0ef-d282acd83d87', 'accept', 'order', 'e39e531f-02d7-402b-bfa1-4bc7911fdca0', 'Vendor فتح الله ماركت accepted order', '154.143.246.144', '2026-04-21 03:07:53'),
('24c334a2-3d15-11f1-9448-e66239f31cb0', 'd8e13ad8-2382-11f1-b82d-97044e7d8834', 'update', 'delivery_zone_pricing', NULL, 'Updated zone pricing: north_central = 20', '154.143.246.144', '2026-04-21 03:00:44'),
('24cccbe1-34ec-11f1-9448-e66239f31cb0', 'd8e13ad8-2382-11f1-b82d-97044e7d8834', 'login', 'user', 'd8e13ad8-2382-11f1-b82d-97044e7d8834', 'Admin logged in', '45.109.226.75', '2026-04-10 14:47:05'),
('253d94ef-3d15-11f1-9448-e66239f31cb0', 'd8e13ad8-2382-11f1-b82d-97044e7d8834', 'update', 'delivery_zone_pricing', NULL, 'Updated zone pricing: central_south = 20', '154.143.246.144', '2026-04-21 03:00:45'),
('25aa627e-3d15-11f1-9448-e66239f31cb0', 'd8e13ad8-2382-11f1-b82d-97044e7d8834', 'update', 'delivery_zone_pricing', NULL, 'Updated zone pricing: north_south = 25', '154.143.246.144', '2026-04-21 03:00:46'),
('261624d8-238e-11f1-b82d-97044e7d8834', 'd8e13ad8-2382-11f1-b82d-97044e7d8834', 'logout', 'user', 'd8e13ad8-2382-11f1-b82d-97044e7d8834', 'Admin logged out', '156.217.99.167', '2026-03-19 12:19:59'),
('261f5104-35ce-11f1-9448-e66239f31cb0', 'cf2239d2-2dd7-4116-abd3-1f24d48e8fe7', 'mark_ready', 'order', 'a1c23979-4844-4910-b6c7-d96f1d5807af', 'All vendors ready for pickup', '197.150.164.48', '2026-04-11 17:44:54'),
('26d58a65-3202-11f1-9448-e66239f31cb0', '8f266f22-1d9d-11f1-b82d-97044e7d8834', 'login', 'user', '8f266f22-1d9d-11f1-b82d-97044e7d8834', 'Super_admin logged in', '154.143.143.60', '2026-04-06 21:47:04'),
('276d6d3d-2f7d-11f1-9448-e66239f31cb0', 'd8e13ad8-2382-11f1-b82d-97044e7d8834', 'login', 'user', 'd8e13ad8-2382-11f1-b82d-97044e7d8834', 'Admin logged in', '197.222.117.240', '2026-04-03 16:50:00'),
('279b34dd-3526-11f1-9448-e66239f31cb0', '4f792c75-4a65-45a7-a0ef-d282acd83d87', 'login', 'user', '4f792c75-4a65-45a7-a0ef-d282acd83d87', 'Vendor logged in', '196.136.46.60', '2026-04-10 21:42:21'),
('27d54927-2fcb-11f1-9448-e66239f31cb0', '4f792c75-4a65-45a7-a0ef-d282acd83d87', 'login', 'user', '4f792c75-4a65-45a7-a0ef-d282acd83d87', 'Vendor logged in', '197.150.173.176', '2026-04-04 02:08:21'),
('2814ba94-3364-11f1-9448-e66239f31cb0', '8f266f22-1d9d-11f1-b82d-97044e7d8834', 'logout', 'user', '8f266f22-1d9d-11f1-b82d-97044e7d8834', 'Super_admin logged out', '196.151.0.29', '2026-04-08 16:01:08'),
('2911eb9e-2f60-11f1-9448-e66239f31cb0', '4f792c75-4a65-45a7-a0ef-d282acd83d87', 'logout', 'user', '4f792c75-4a65-45a7-a0ef-d282acd83d87', 'Vendor logged out', '197.223.138.29', '2026-04-03 13:22:27'),
('29614507-2f01-11f1-9448-e66239f31cb0', '8f266f22-1d9d-11f1-b82d-97044e7d8834', 'logout', 'user', '8f266f22-1d9d-11f1-b82d-97044e7d8834', 'Super_admin logged out', '197.223.138.29', '2026-04-03 02:02:26'),
('29bd8c54-3d15-11f1-9448-e66239f31cb0', 'd8e13ad8-2382-11f1-b82d-97044e7d8834', 'logout', 'user', 'd8e13ad8-2382-11f1-b82d-97044e7d8834', 'Admin logged out', '154.143.246.144', '2026-04-21 03:00:52'),
('2a508a61-31fe-11f1-9448-e66239f31cb0', 'cf2239d2-2dd7-4116-abd3-1f24d48e8fe7', 'create', 'menu_item', '2a5064d0-31fe-11f1-9448-e66239f31cb0', 'Created item: duck', '154.143.143.60', '2026-04-06 21:18:32'),
('2a54b8f3-31fd-11f1-9448-e66239f31cb0', '8f266f22-1d9d-11f1-b82d-97044e7d8834', 'login', 'user', '8f266f22-1d9d-11f1-b82d-97044e7d8834', 'Super_admin logged in', '154.143.143.60', '2026-04-06 21:11:23'),
('2ab8361f-3d25-11f1-9448-e66239f31cb0', '68d947ef-fcec-426a-8316-155666848619', 'logout', 'user', '68d947ef-fcec-426a-8316-155666848619', 'Vendor logged out', '154.143.246.144', '2026-04-21 04:55:26'),
('2ac1e14a-35de-11f1-9448-e66239f31cb0', '4f792c75-4a65-45a7-a0ef-d282acd83d87', 'login', 'user', '4f792c75-4a65-45a7-a0ef-d282acd83d87', 'Vendor logged in', '197.150.164.48', '2026-04-11 19:39:34'),
('2b3071ba-2394-11f1-b82d-97044e7d8834', '8f266f22-1d9d-11f1-b82d-97044e7d8834', 'login', 'user', '8f266f22-1d9d-11f1-b82d-97044e7d8834', 'Super_admin logged in', '156.217.99.167', '2026-03-19 13:03:05'),
('2b75179b-339f-11f1-9448-e66239f31cb0', 'd8e13ad8-2382-11f1-b82d-97044e7d8834', 'login', 'user', 'd8e13ad8-2382-11f1-b82d-97044e7d8834', 'Admin logged in', '154.143.143.60', '2026-04-08 23:03:34'),
('2c3eb0b8-35fa-11f1-9448-e66239f31cb0', '8f266f22-1d9d-11f1-b82d-97044e7d8834', 'logout', 'user', '8f266f22-1d9d-11f1-b82d-97044e7d8834', 'Super_admin logged out', '154.143.40.184', '2026-04-11 23:00:02'),
('2c6e089a-231b-11f1-b82d-97044e7d8834', '80c0bb8b-082d-4810-806f-0e1901d9d787', 'login', 'user', '80c0bb8b-082d-4810-806f-0e1901d9d787', 'Vendor logged in', '45.104.153.29', '2026-03-18 22:36:58'),
('2cafc853-2fcb-11f1-9448-e66239f31cb0', '4f792c75-4a65-45a7-a0ef-d282acd83d87', 'logout', 'user', '4f792c75-4a65-45a7-a0ef-d282acd83d87', 'Vendor logged out', '197.150.173.176', '2026-04-04 02:08:29'),
('2ce96690-2f0b-11f1-9448-e66239f31cb0', 'd8e13ad8-2382-11f1-b82d-97044e7d8834', 'login', 'user', 'd8e13ad8-2382-11f1-b82d-97044e7d8834', 'Admin logged in', '197.223.138.29', '2026-04-03 03:14:06'),
('2df424c2-35ce-11f1-9448-e66239f31cb0', 'cf2239d2-2dd7-4116-abd3-1f24d48e8fe7', 'logout', 'user', 'cf2239d2-2dd7-4116-abd3-1f24d48e8fe7', 'Vendor logged out', '197.150.164.48', '2026-04-11 17:45:07'),
('2ec64cf9-2f01-11f1-9448-e66239f31cb0', 'd8e13ad8-2382-11f1-b82d-97044e7d8834', 'login', 'user', 'd8e13ad8-2382-11f1-b82d-97044e7d8834', 'Admin logged in', '197.223.138.29', '2026-04-03 02:02:35'),
('2f485052-35de-11f1-9448-e66239f31cb0', '4f792c75-4a65-45a7-a0ef-d282acd83d87', 'accept', 'order', '1e8023a6-a8d0-42d5-b4bb-4c5317631166', 'Vendor فتح الله ماركت accepted order', '197.150.164.48', '2026-04-11 19:39:41'),
('2f8af9bf-2394-11f1-b82d-97044e7d8834', '8f266f22-1d9d-11f1-b82d-97044e7d8834', 'logout', 'user', '8f266f22-1d9d-11f1-b82d-97044e7d8834', 'Super_admin logged out', '156.217.99.167', '2026-03-19 13:03:12'),
('30c0fc01-3d15-11f1-9448-e66239f31cb0', '4f792c75-4a65-45a7-a0ef-d282acd83d87', 'login', 'user', '4f792c75-4a65-45a7-a0ef-d282acd83d87', 'Vendor logged in', '154.143.246.144', '2026-04-21 03:01:04'),
('30d3bda7-2f7d-11f1-9448-e66239f31cb0', 'd8e13ad8-2382-11f1-b82d-97044e7d8834', 'update', 'delivery_zone', 'b7880d6b-2f0b-11f1-9448-e66239f31cb0', 'Updated delivery zone: test1', '197.222.117.240', '2026-04-03 16:50:16'),
('3136b47a-1db4-11f1-b82d-97044e7d8834', '8f266f22-1d9d-11f1-b82d-97044e7d8834', 'login', 'user', '8f266f22-1d9d-11f1-b82d-97044e7d8834', 'Super_admin logged in', '2a02:4780:8:5::a', '2026-03-12 01:37:12'),
('315e945b-1db4-11f1-b82d-97044e7d8834', '8f266f22-1d9d-11f1-b82d-97044e7d8834', 'refresh_token', 'user', '8f266f22-1d9d-11f1-b82d-97044e7d8834', 'Token refreshed', '2a02:4780:8:5::a', '2026-03-12 01:37:12'),
('317e35cb-238e-11f1-b82d-97044e7d8834', '8f266f22-1d9d-11f1-b82d-97044e7d8834', 'login', 'user', '8f266f22-1d9d-11f1-b82d-97044e7d8834', 'Super_admin logged in', '156.217.99.167', '2026-03-19 12:20:18'),
('31f6cdc5-35cb-11f1-9448-e66239f31cb0', '8f266f22-1d9d-11f1-b82d-97044e7d8834', 'login', 'user', '8f266f22-1d9d-11f1-b82d-97044e7d8834', 'Super_admin logged in', '197.150.164.48', '2026-04-11 17:23:45'),
('3245cff1-2f60-11f1-9448-e66239f31cb0', '8f266f22-1d9d-11f1-b82d-97044e7d8834', 'login', 'user', '8f266f22-1d9d-11f1-b82d-97044e7d8834', 'Super_admin logged in', '197.223.138.29', '2026-04-03 13:22:43'),
('32bb0fd6-2392-11f1-b82d-97044e7d8834', '8f266f22-1d9d-11f1-b82d-97044e7d8834', 'logout', 'user', '8f266f22-1d9d-11f1-b82d-97044e7d8834', 'Super_admin logged out', '156.217.99.167', '2026-03-19 12:48:58'),
('32c45ddd-35ce-11f1-9448-e66239f31cb0', '4f792c75-4a65-45a7-a0ef-d282acd83d87', 'login', 'user', '4f792c75-4a65-45a7-a0ef-d282acd83d87', 'Vendor logged in', '197.150.164.48', '2026-04-11 17:45:15'),
('33335c6d-3d25-11f1-9448-e66239f31cb0', 'e64dd274-3d20-11f1-9448-e66239f31cb0', 'login', 'user', 'e64dd274-3d20-11f1-9448-e66239f31cb0', 'Admin logged in', '154.143.246.144', '2026-04-21 04:55:40'),
('33aae271-3d8f-11f1-9448-e66239f31cb0', '68d947ef-fcec-426a-8316-155666848619', 'login', 'user', '68d947ef-fcec-426a-8316-155666848619', 'Vendor logged in', '154.143.246.144', '2026-04-21 17:34:28'),
('33f8f8e0-3d21-11f1-9448-e66239f31cb0', '4f792c75-4a65-45a7-a0ef-d282acd83d87', 'logout', 'user', '4f792c75-4a65-45a7-a0ef-d282acd83d87', 'Vendor logged out', '154.143.246.144', '2026-04-21 04:27:04'),
('33fa9629-3364-11f1-9448-e66239f31cb0', '8f266f22-1d9d-11f1-b82d-97044e7d8834', 'login', 'user', '8f266f22-1d9d-11f1-b82d-97044e7d8834', 'Super_admin logged in', '196.151.0.29', '2026-04-08 16:01:28'),
('342928ea-35de-11f1-9448-e66239f31cb0', '4f792c75-4a65-45a7-a0ef-d282acd83d87', 'mark_preparing', 'order', '1e8023a6-a8d0-42d5-b4bb-4c5317631166', 'Order being prepared', '197.150.164.48', '2026-04-11 19:39:49'),
('34758c9a-2390-11f1-b82d-97044e7d8834', '8f266f22-1d9d-11f1-b82d-97044e7d8834', 'login', 'user', '8f266f22-1d9d-11f1-b82d-97044e7d8834', 'Super_admin logged in', '156.217.99.167', '2026-03-19 12:34:42'),
('351893ce-3b55-11f1-9448-e66239f31cb0', '8f266f22-1d9d-11f1-b82d-97044e7d8834', 'login', 'user', '8f266f22-1d9d-11f1-b82d-97044e7d8834', 'Super_admin logged in', '45.109.196.109', '2026-04-18 21:34:17'),
('3588030b-35fa-11f1-9448-e66239f31cb0', 'cf2239d2-2dd7-4116-abd3-1f24d48e8fe7', 'login', 'user', 'cf2239d2-2dd7-4116-abd3-1f24d48e8fe7', 'Vendor logged in', '154.143.40.184', '2026-04-11 23:00:18'),
('35ee0600-34a6-11f1-9448-e66239f31cb0', 'd8e13ad8-2382-11f1-b82d-97044e7d8834', 'login', 'user', 'd8e13ad8-2382-11f1-b82d-97044e7d8834', 'Admin logged in', '45.109.226.75', '2026-04-10 06:26:29'),
('37068a2d-35de-11f1-9448-e66239f31cb0', '4f792c75-4a65-45a7-a0ef-d282acd83d87', 'mark_ready', 'order', '1e8023a6-a8d0-42d5-b4bb-4c5317631166', 'فتح الله ماركت ready, waiting for other vendors', '197.150.164.48', '2026-04-11 19:39:54'),
('3803eeed-35ce-11f1-9448-e66239f31cb0', '4f792c75-4a65-45a7-a0ef-d282acd83d87', 'logout', 'user', '4f792c75-4a65-45a7-a0ef-d282acd83d87', 'Vendor logged out', '197.150.164.48', '2026-04-11 17:45:24'),
('38599c0c-2f53-11f1-9448-e66239f31cb0', '8f266f22-1d9d-11f1-b82d-97044e7d8834', 'login', 'user', '8f266f22-1d9d-11f1-b82d-97044e7d8834', 'Super_admin logged in', '197.223.138.29', '2026-04-03 11:49:49'),
('38f178dd-2f7d-11f1-9448-e66239f31cb0', 'd8e13ad8-2382-11f1-b82d-97044e7d8834', 'logout', 'user', 'd8e13ad8-2382-11f1-b82d-97044e7d8834', 'Admin logged out', '197.222.117.240', '2026-04-03 16:50:29'),
('3977ee54-2f04-11f1-9448-e66239f31cb0', '8f266f22-1d9d-11f1-b82d-97044e7d8834', 'update', 'delivery_zone', '8f282a59-1d9d-11f1-b82d-97044e7d8834', 'SA updated delivery zone: Zone 3 - Medium (5 to 10 km)', '197.223.138.29', '2026-04-03 02:24:21'),
('3a3d81cb-26f4-11f1-abf9-e1b0d104a220', '8f266f22-1d9d-11f1-b82d-97044e7d8834', 'login', 'user', '8f266f22-1d9d-11f1-b82d-97044e7d8834', 'Super_admin logged in', '45.109.19.74', '2026-03-23 20:09:41'),
('3ac2a79a-351d-11f1-9448-e66239f31cb0', 'd8e13ad8-2382-11f1-b82d-97044e7d8834', 'logout', 'user', 'd8e13ad8-2382-11f1-b82d-97044e7d8834', 'Admin logged out', '196.136.12.153', '2026-04-10 20:38:28'),
('3bc460f7-35de-11f1-9448-e66239f31cb0', '4f792c75-4a65-45a7-a0ef-d282acd83d87', 'logout', 'user', '4f792c75-4a65-45a7-a0ef-d282acd83d87', 'Vendor logged out', '197.150.164.48', '2026-04-11 19:40:02'),
('3be1268f-2fcb-11f1-9448-e66239f31cb0', '8f266f22-1d9d-11f1-b82d-97044e7d8834', 'login', 'user', '8f266f22-1d9d-11f1-b82d-97044e7d8834', 'Super_admin logged in', '197.150.173.176', '2026-04-04 02:08:55'),
('3be81422-2f7b-11f1-9448-e66239f31cb0', '4f792c75-4a65-45a7-a0ef-d282acd83d87', 'update_profile', 'vendor', '57e1966b-294a-11f1-9448-e66239f31cb0', 'Updated vendor profile', '197.222.117.240', '2026-04-03 16:36:15'),
('3c111f21-2f82-11f1-9448-e66239f31cb0', '8f266f22-1d9d-11f1-b82d-97044e7d8834', 'logout', 'user', '8f266f22-1d9d-11f1-b82d-97044e7d8834', 'Super_admin logged out', '197.222.117.240', '2026-04-03 17:26:22'),
('3c57252f-3d25-11f1-9448-e66239f31cb0', 'e64dd274-3d20-11f1-9448-e66239f31cb0', 'logout', 'user', 'e64dd274-3d20-11f1-9448-e66239f31cb0', 'Admin logged out', '154.143.246.144', '2026-04-21 04:55:56'),
('3c9ec936-2390-11f1-b82d-97044e7d8834', '8f266f22-1d9d-11f1-b82d-97044e7d8834', 'update', 'fee_config', '8f27444b-1d9d-11f1-b82d-97044e7d8834', 'Updated fee configuration', '156.217.99.167', '2026-03-19 12:34:56'),
('3cd29ef3-35ce-11f1-9448-e66239f31cb0', 'd8e13ad8-2382-11f1-b82d-97044e7d8834', 'login', 'user', 'd8e13ad8-2382-11f1-b82d-97044e7d8834', 'Admin logged in', '197.150.164.48', '2026-04-11 17:45:32'),
('3d6eda73-3b31-11f1-9448-e66239f31cb0', '8f266f22-1d9d-11f1-b82d-97044e7d8834', 'login', 'user', '8f266f22-1d9d-11f1-b82d-97044e7d8834', 'Super_admin logged in', '158.140.85.78', '2026-04-18 17:16:49'),
('3f27b798-3513-11f1-9448-e66239f31cb0', '8f266f22-1d9d-11f1-b82d-97044e7d8834', 'update', 'advertisement', '5c703dba-2ef9-11f1-9448-e66239f31cb0', 'Updated advertisement: gaza ad', '197.223.152.7', '2026-04-10 19:27:00'),
('3f4a7aeb-2f7d-11f1-9448-e66239f31cb0', '8f266f22-1d9d-11f1-b82d-97044e7d8834', 'login', 'user', '8f266f22-1d9d-11f1-b82d-97044e7d8834', 'Super_admin logged in', '197.222.117.240', '2026-04-03 16:50:40'),
('3f76e612-347f-11f1-9448-e66239f31cb0', '8f266f22-1d9d-11f1-b82d-97044e7d8834', 'logout', 'user', '8f266f22-1d9d-11f1-b82d-97044e7d8834', 'Super_admin logged out', '45.109.112.29', '2026-04-10 01:47:35'),
('3f78f3ba-3513-11f1-9448-e66239f31cb0', '8f266f22-1d9d-11f1-b82d-97044e7d8834', 'update', 'advertisement', '5c703dba-2ef9-11f1-9448-e66239f31cb0', 'Uploaded image for advertisement', '197.223.152.7', '2026-04-10 19:27:01'),
('3ffdeb9a-2385-11f1-b82d-97044e7d8834', '8f266f22-1d9d-11f1-b82d-97044e7d8834', 'login', 'user', '8f266f22-1d9d-11f1-b82d-97044e7d8834', 'Super_admin logged in', '156.217.99.167', '2026-03-19 11:16:17'),
('4000dacd-231a-11f1-b82d-97044e7d8834', '8f266f22-1d9d-11f1-b82d-97044e7d8834', 'approve', 'vendor', '0f38e4e9-2316-11f1-b82d-97044e7d8834', 'Approved vendor: vendor', '156.217.99.167', '2026-03-18 22:30:21'),
('408161fc-303d-11f1-9448-e66239f31cb0', '8f266f22-1d9d-11f1-b82d-97044e7d8834', 'login', 'user', '8f266f22-1d9d-11f1-b82d-97044e7d8834', 'Super_admin logged in', '154.143.143.60', '2026-04-04 15:45:05'),
('40d7bc1a-35cb-11f1-9448-e66239f31cb0', '8f266f22-1d9d-11f1-b82d-97044e7d8834', 'logout', 'user', '8f266f22-1d9d-11f1-b82d-97044e7d8834', 'Super_admin logged out', '197.150.164.48', '2026-04-11 17:24:10'),
('412c6017-2ef2-11f1-9448-e66239f31cb0', '8f266f22-1d9d-11f1-b82d-97044e7d8834', 'logout', 'user', '8f266f22-1d9d-11f1-b82d-97044e7d8834', 'Super_admin logged out', '197.223.138.29', '2026-04-03 00:15:43'),
('412fbc3a-2f82-11f1-9448-e66239f31cb0', '4f792c75-4a65-45a7-a0ef-d282acd83d87', 'login', 'user', '4f792c75-4a65-45a7-a0ef-d282acd83d87', 'Vendor logged in', '197.222.117.240', '2026-04-03 17:26:31'),
('41598118-34a6-11f1-9448-e66239f31cb0', 'd8e13ad8-2382-11f1-b82d-97044e7d8834', 'update', 'fee_config', '8f27444b-1d9d-11f1-b82d-97044e7d8834', 'Updated delivery fee: base=30', '45.109.226.75', '2026-04-10 06:26:49'),
('416077ff-35de-11f1-9448-e66239f31cb0', 'd8e13ad8-2382-11f1-b82d-97044e7d8834', 'login', 'user', 'd8e13ad8-2382-11f1-b82d-97044e7d8834', 'Admin logged in', '197.150.164.48', '2026-04-11 19:40:12'),
('41a087d4-34a5-11f1-9448-e66239f31cb0', 'd8e13ad8-2382-11f1-b82d-97044e7d8834', 'logout', 'user', 'd8e13ad8-2382-11f1-b82d-97044e7d8834', 'Admin logged out', '45.109.226.75', '2026-04-10 06:19:40'),
('422af15c-2f0c-11f1-9448-e66239f31cb0', '4f792c75-4a65-45a7-a0ef-d282acd83d87', 'logout', 'user', '4f792c75-4a65-45a7-a0ef-d282acd83d87', 'Vendor logged out', '197.223.138.29', '2026-04-03 03:21:52'),
('423b2b10-3d25-11f1-9448-e66239f31cb0', 'f52cc98d-3d20-11f1-9448-e66239f31cb0', 'login', 'user', 'f52cc98d-3d20-11f1-9448-e66239f31cb0', 'Admin logged in', '154.143.246.144', '2026-04-21 04:56:05'),
('4242d155-3345-11f1-9448-e66239f31cb0', '8f266f22-1d9d-11f1-b82d-97044e7d8834', 'create', 'global_category', '4242b965-3345-11f1-9448-e66239f31cb0', 'Created global category: test', '196.151.0.29', '2026-04-08 12:19:58'),
('42bd9657-3d15-11f1-9448-e66239f31cb0', '4f792c75-4a65-45a7-a0ef-d282acd83d87', 'logout', 'user', '4f792c75-4a65-45a7-a0ef-d282acd83d87', 'Vendor logged out', '154.143.246.144', '2026-04-21 03:01:34'),
('43175021-231b-11f1-b82d-97044e7d8834', '80c0bb8b-082d-4810-806f-0e1901d9d787', 'logout', 'user', '80c0bb8b-082d-4810-806f-0e1901d9d787', 'Vendor logged out', '45.104.153.29', '2026-03-18 22:37:36'),
('43713a04-2385-11f1-b82d-97044e7d8834', '8f266f22-1d9d-11f1-b82d-97044e7d8834', 'activated', 'admin', 'd8e13ad8-2382-11f1-b82d-97044e7d8834', 'Activated admin: admin1', '156.217.99.167', '2026-03-19 11:16:23'),
('44294eb0-351d-11f1-9448-e66239f31cb0', '8f266f22-1d9d-11f1-b82d-97044e7d8834', 'login', 'user', '8f266f22-1d9d-11f1-b82d-97044e7d8834', 'Super_admin logged in', '196.136.12.153', '2026-04-10 20:38:43'),
('44707788-3512-11f1-9448-e66239f31cb0', '4f792c75-4a65-45a7-a0ef-d282acd83d87', 'create', 'menu_category', '44705fe1-3512-11f1-9448-e66239f31cb0', 'Subscribed to category: Desserts', '197.223.152.7', '2026-04-10 19:19:59'),
('447515c0-345f-11f1-9448-e66239f31cb0', 'd8e13ad8-2382-11f1-b82d-97044e7d8834', 'logout', 'user', 'd8e13ad8-2382-11f1-b82d-97044e7d8834', 'Admin logged out', '45.109.112.29', '2026-04-09 21:58:40'),
('44e3099d-34a6-11f1-9448-e66239f31cb0', 'd8e13ad8-2382-11f1-b82d-97044e7d8834', 'update', 'fee_config', '8f27444b-1d9d-11f1-b82d-97044e7d8834', 'Updated delivery fee: base=20', '45.109.226.75', '2026-04-10 06:26:55'),
('44f29a0b-3526-11f1-9448-e66239f31cb0', '4f792c75-4a65-45a7-a0ef-d282acd83d87', 'logout', 'user', '4f792c75-4a65-45a7-a0ef-d282acd83d87', 'Vendor logged out', '196.136.46.60', '2026-04-10 21:43:10'),
('4560cfb1-3d22-11f1-9448-e66239f31cb0', '68d947ef-fcec-426a-8316-155666848619', 'accept', 'order', 'f6203869-9831-43d0-a8c7-b814f55b5de8', 'Vendor North food accepted order', '154.143.246.144', '2026-04-21 04:34:42'),
('45ee1995-3d16-11f1-9448-e66239f31cb0', '4f792c75-4a65-45a7-a0ef-d282acd83d87', 'logout', 'user', '4f792c75-4a65-45a7-a0ef-d282acd83d87', 'Vendor logged out', '154.143.246.144', '2026-04-21 03:08:49'),
('4600e462-3457-11f1-9448-e66239f31cb0', '4f792c75-4a65-45a7-a0ef-d282acd83d87', 'logout', 'user', '4f792c75-4a65-45a7-a0ef-d282acd83d87', 'Vendor logged out', '45.109.112.29', '2026-04-09 21:01:26'),
('461312a3-2f7b-11f1-9448-e66239f31cb0', '4f792c75-4a65-45a7-a0ef-d282acd83d87', 'logout', 'user', '4f792c75-4a65-45a7-a0ef-d282acd83d87', 'Vendor logged out', '197.222.117.240', '2026-04-03 16:36:32'),
('4659be7e-3512-11f1-9448-e66239f31cb0', '4f792c75-4a65-45a7-a0ef-d282acd83d87', 'create', 'menu_category', '465996e2-3512-11f1-9448-e66239f31cb0', 'Subscribed to category: test', '197.223.152.7', '2026-04-10 19:20:03'),
('467ffbcc-2f7d-11f1-9448-e66239f31cb0', '8f266f22-1d9d-11f1-b82d-97044e7d8834', 'update', 'delivery_zone', '8f2827b0-1d9d-11f1-b82d-97044e7d8834', 'SA updated delivery zone: Zone 1 - Very Close (0 to 2 km)', '197.222.117.240', '2026-04-03 16:50:52'),
('46d346ef-35fb-11f1-9448-e66239f31cb0', '8f266f22-1d9d-11f1-b82d-97044e7d8834', 'login', 'user', '8f266f22-1d9d-11f1-b82d-97044e7d8834', 'Super_admin logged in', '154.143.40.184', '2026-04-11 23:07:56'),
('4701539d-347f-11f1-9448-e66239f31cb0', 'd8e13ad8-2382-11f1-b82d-97044e7d8834', 'login', 'user', 'd8e13ad8-2382-11f1-b82d-97044e7d8834', 'Admin logged in', '45.109.112.29', '2026-04-10 01:47:48'),
('470a5ec4-2f01-11f1-9448-e66239f31cb0', 'd8e13ad8-2382-11f1-b82d-97044e7d8834', 'logout', 'user', 'd8e13ad8-2382-11f1-b82d-97044e7d8834', 'Admin logged out', '197.223.138.29', '2026-04-03 02:03:15'),
('47e9719d-2385-11f1-b82d-97044e7d8834', '8f266f22-1d9d-11f1-b82d-97044e7d8834', 'logout', 'user', '8f266f22-1d9d-11f1-b82d-97044e7d8834', 'Super_admin logged out', '156.217.99.167', '2026-03-19 11:16:31'),
('47f78f6b-26d8-11f1-abf9-e1b0d104a220', '8f266f22-1d9d-11f1-b82d-97044e7d8834', 'login', 'user', '8f266f22-1d9d-11f1-b82d-97044e7d8834', 'Super_admin logged in', '45.109.19.74', '2026-03-23 16:49:38'),
('4870f85e-35fa-11f1-9448-e66239f31cb0', 'cf2239d2-2dd7-4116-abd3-1f24d48e8fe7', 'update_profile', 'vendor', '95a07fca-231c-11f1-b82d-97044e7d8834', 'Updated vendor profile', '154.143.40.184', '2026-04-11 23:00:49'),
('492e10ea-2f87-11f1-9448-e66239f31cb0', '4f792c75-4a65-45a7-a0ef-d282acd83d87', 'update_profile', 'vendor', '57e1966b-294a-11f1-9448-e66239f31cb0', 'Updated vendor profile', '197.222.117.240', '2026-04-03 18:02:32'),
('49c6f3ba-3512-11f1-9448-e66239f31cb0', '4f792c75-4a65-45a7-a0ef-d282acd83d87', 'delete', 'menu_category', '44705fe1-3512-11f1-9448-e66239f31cb0', 'Deleted category: Desserts', '197.223.152.7', '2026-04-10 19:20:08'),
('4a9a476d-3345-11f1-9448-e66239f31cb0', '8f266f22-1d9d-11f1-b82d-97044e7d8834', 'logout', 'user', '8f266f22-1d9d-11f1-b82d-97044e7d8834', 'Super_admin logged out', '196.151.0.29', '2026-04-08 12:20:12'),
('4c396597-2f7d-11f1-9448-e66239f31cb0', '8f266f22-1d9d-11f1-b82d-97044e7d8834', 'logout', 'user', '8f266f22-1d9d-11f1-b82d-97044e7d8834', 'Super_admin logged out', '197.222.117.240', '2026-04-03 16:51:02'),
('4c467829-303d-11f1-9448-e66239f31cb0', '8f266f22-1d9d-11f1-b82d-97044e7d8834', 'logout', 'user', '8f266f22-1d9d-11f1-b82d-97044e7d8834', 'Super_admin logged out', '154.143.143.60', '2026-04-04 15:45:25'),
('4d3eb383-35fa-11f1-9448-e66239f31cb0', 'cf2239d2-2dd7-4116-abd3-1f24d48e8fe7', 'logout', 'user', 'cf2239d2-2dd7-4116-abd3-1f24d48e8fe7', 'Vendor logged out', '154.143.40.184', '2026-04-11 23:00:57'),
('4dca1aca-3512-11f1-9448-e66239f31cb0', '4f792c75-4a65-45a7-a0ef-d282acd83d87', 'delete', 'menu_category', '465996e2-3512-11f1-9448-e66239f31cb0', 'Deleted category: test', '197.223.152.7', '2026-04-10 19:20:15'),
('4ddf74ad-2f7b-11f1-9448-e66239f31cb0', 'd8e13ad8-2382-11f1-b82d-97044e7d8834', 'login', 'user', 'd8e13ad8-2382-11f1-b82d-97044e7d8834', 'Admin logged in', '197.222.117.240', '2026-04-03 16:36:45'),
('4de5e554-3475-11f1-9448-e66239f31cb0', '4f792c75-4a65-45a7-a0ef-d282acd83d87', 'logout', 'user', '4f792c75-4a65-45a7-a0ef-d282acd83d87', 'Vendor logged out', '45.109.112.29', '2026-04-10 00:36:24'),
('4eb3d8f4-35fb-11f1-9448-e66239f31cb0', '8f266f22-1d9d-11f1-b82d-97044e7d8834', 'logout', 'user', '8f266f22-1d9d-11f1-b82d-97044e7d8834', 'Super_admin logged out', '154.143.40.184', '2026-04-11 23:08:09'),
('4ec0ad7c-343f-11f1-9448-e66239f31cb0', 'd8e13ad8-2382-11f1-b82d-97044e7d8834', 'logout', 'user', 'd8e13ad8-2382-11f1-b82d-97044e7d8834', 'Admin logged out', '154.143.86.111', '2026-04-09 18:09:53'),
('4f29d2be-35cb-11f1-9448-e66239f31cb0', '80c0bb8b-082d-4810-806f-0e1901d9d787', 'login', 'user', '80c0bb8b-082d-4810-806f-0e1901d9d787', 'Vendor logged in', '197.150.164.48', '2026-04-11 17:24:34'),
('4f4cc4c1-3498-11f1-9448-e66239f31cb0', '4f792c75-4a65-45a7-a0ef-d282acd83d87', 'logout', 'user', '4f792c75-4a65-45a7-a0ef-d282acd83d87', 'Vendor logged out', '45.109.226.75', '2026-04-10 04:46:59'),
('4fdd5ae2-1db4-11f1-b82d-97044e7d8834', '8f266f22-1d9d-11f1-b82d-97044e7d8834', 'login', 'user', '8f266f22-1d9d-11f1-b82d-97044e7d8834', 'Super_admin logged in', '2a02:4780:8:5::a', '2026-03-12 01:38:03'),
('4feb7587-1db4-11f1-b82d-97044e7d8834', '8f266f22-1d9d-11f1-b82d-97044e7d8834', 'login', 'user', '8f266f22-1d9d-11f1-b82d-97044e7d8834', 'Super_admin logged in', '2a02:4780:8:5::a', '2026-03-12 01:38:03'),
('500ddfd8-1db4-11f1-b82d-97044e7d8834', '8f266f22-1d9d-11f1-b82d-97044e7d8834', 'login', 'user', '8f266f22-1d9d-11f1-b82d-97044e7d8834', 'Super_admin logged in', '2a02:4780:8:5::a', '2026-03-12 01:38:04'),
('5046401c-1db4-11f1-b82d-97044e7d8834', '8f266f22-1d9d-11f1-b82d-97044e7d8834', 'logout', 'user', '8f266f22-1d9d-11f1-b82d-97044e7d8834', 'Super_admin logged out', '2a02:4780:8:5::a', '2026-03-12 01:38:04'),
('505c1e22-1db4-11f1-b82d-97044e7d8834', '8f266f22-1d9d-11f1-b82d-97044e7d8834', 'logout', 'user', '8f266f22-1d9d-11f1-b82d-97044e7d8834', 'Super_admin logged out', '2a02:4780:8:5::a', '2026-03-12 01:38:04'),
('505cf957-1db4-11f1-b82d-97044e7d8834', '8f266f22-1d9d-11f1-b82d-97044e7d8834', 'logout', 'user', '8f266f22-1d9d-11f1-b82d-97044e7d8834', 'Super_admin logged out', '2a02:4780:8:5::a', '2026-03-12 01:38:04'),
('5072ab3a-26d8-11f1-abf9-e1b0d104a220', '8f266f22-1d9d-11f1-b82d-97044e7d8834', 'logout', 'user', '8f266f22-1d9d-11f1-b82d-97044e7d8834', 'Super_admin logged out', '45.109.19.74', '2026-03-23 16:49:52');
INSERT INTO `activity_logs` (`id`, `user_id`, `action`, `entity_type`, `entity_id`, `description`, `ip_address`, `created_at`) VALUES
('50ac3d87-3d22-11f1-9448-e66239f31cb0', '68d947ef-fcec-426a-8316-155666848619', 'mark_preparing', 'order', 'f6203869-9831-43d0-a8c7-b814f55b5de8', 'Order being prepared', '154.143.246.144', '2026-04-21 04:35:01'),
('50ad5cf6-3048-11f1-9448-e66239f31cb0', '8f266f22-1d9d-11f1-b82d-97044e7d8834', 'logout', 'user', '8f266f22-1d9d-11f1-b82d-97044e7d8834', 'Super_admin logged out', '196.151.42.18', '2026-04-04 17:04:17'),
('50b29d2c-1db4-11f1-b82d-97044e7d8834', '8f266f22-1d9d-11f1-b82d-97044e7d8834', 'login', 'user', '8f266f22-1d9d-11f1-b82d-97044e7d8834', 'Super_admin logged in', '2a02:4780:8:5::a', '2026-03-12 01:38:05'),
('50d18973-2f04-11f1-9448-e66239f31cb0', '8f266f22-1d9d-11f1-b82d-97044e7d8834', 'logout', 'user', '8f266f22-1d9d-11f1-b82d-97044e7d8834', 'Super_admin logged out', '197.223.138.29', '2026-04-03 02:25:00'),
('50e90f07-1db4-11f1-b82d-97044e7d8834', '8f266f22-1d9d-11f1-b82d-97044e7d8834', 'logout', 'user', '8f266f22-1d9d-11f1-b82d-97044e7d8834', 'Super_admin logged out', '2a02:4780:8:5::a', '2026-03-12 01:38:05'),
('5155cfdf-303d-11f1-9448-e66239f31cb0', '4f792c75-4a65-45a7-a0ef-d282acd83d87', 'login', 'user', '4f792c75-4a65-45a7-a0ef-d282acd83d87', 'Vendor logged in', '154.143.143.60', '2026-04-04 15:45:34'),
('51dc0ac9-2f01-11f1-9448-e66239f31cb0', '8f266f22-1d9d-11f1-b82d-97044e7d8834', 'login', 'user', '8f266f22-1d9d-11f1-b82d-97044e7d8834', 'Super_admin logged in', '197.223.138.29', '2026-04-03 02:03:34'),
('52391564-3087-11f1-9448-e66239f31cb0', '8f266f22-1d9d-11f1-b82d-97044e7d8834', 'login', 'user', '8f266f22-1d9d-11f1-b82d-97044e7d8834', 'Super_admin logged in', '154.143.143.60', '2026-04-05 00:35:18'),
('52507de0-3526-11f1-9448-e66239f31cb0', 'd8e13ad8-2382-11f1-b82d-97044e7d8834', 'login', 'user', 'd8e13ad8-2382-11f1-b82d-97044e7d8834', 'Admin logged in', '196.136.46.60', '2026-04-10 21:43:33'),
('52552517-2f81-11f1-9448-e66239f31cb0', '4f792c75-4a65-45a7-a0ef-d282acd83d87', 'update_profile', 'vendor', '57e1966b-294a-11f1-9448-e66239f31cb0', 'Updated vendor profile', '197.222.117.240', '2026-04-03 17:19:50'),
('525a96c1-2316-11f1-b82d-97044e7d8834', '8f266f22-1d9d-11f1-b82d-97044e7d8834', 'login', 'user', '8f266f22-1d9d-11f1-b82d-97044e7d8834', 'Super_admin logged in', '45.104.153.29', '2026-03-18 22:02:14'),
('52e2fc6a-3d22-11f1-9448-e66239f31cb0', '68d947ef-fcec-426a-8316-155666848619', 'mark_ready', 'order', 'f6203869-9831-43d0-a8c7-b814f55b5de8', 'All vendors ready for pickup', '154.143.246.144', '2026-04-21 04:35:05'),
('531bab46-35cc-11f1-9448-e66239f31cb0', '8f266f22-1d9d-11f1-b82d-97044e7d8834', 'update', 'advertisement', '5c703dba-2ef9-11f1-9448-e66239f31cb0', 'Updated advertisement: gaza ad', '197.150.164.48', '2026-04-11 17:31:50'),
('5380ad38-35cc-11f1-9448-e66239f31cb0', '8f266f22-1d9d-11f1-b82d-97044e7d8834', 'update', 'advertisement', '5c703dba-2ef9-11f1-9448-e66239f31cb0', 'Uploaded image for advertisement', '197.150.164.48', '2026-04-11 17:31:51'),
('538af35c-395d-11f1-9448-e66239f31cb0', '8f266f22-1d9d-11f1-b82d-97044e7d8834', 'login', 'user', '8f266f22-1d9d-11f1-b82d-97044e7d8834', 'Super_admin logged in', '197.223.100.82', '2026-04-16 09:27:22'),
('53ce3225-3513-11f1-9448-e66239f31cb0', '8f266f22-1d9d-11f1-b82d-97044e7d8834', 'update', 'advertisement', 'f5e9cc4e-2ef6-11f1-9448-e66239f31cb0', 'Updated advertisement: add one', '197.223.152.7', '2026-04-10 19:27:35'),
('53d6f1c0-320d-11f1-9448-e66239f31cb0', '8f266f22-1d9d-11f1-b82d-97044e7d8834', 'logout', 'user', '8f266f22-1d9d-11f1-b82d-97044e7d8834', 'Super_admin logged out', '154.143.143.60', '2026-04-06 23:07:04'),
('541ac325-3513-11f1-9448-e66239f31cb0', '8f266f22-1d9d-11f1-b82d-97044e7d8834', 'update', 'advertisement', 'f5e9cc4e-2ef6-11f1-9448-e66239f31cb0', 'Uploaded image for advertisement', '197.223.152.7', '2026-04-10 19:27:35'),
('55ad8d66-34a4-11f1-9448-e66239f31cb0', 'd8e13ad8-2382-11f1-b82d-97044e7d8834', 'logout', 'user', 'd8e13ad8-2382-11f1-b82d-97044e7d8834', 'Admin logged out', '45.109.226.75', '2026-04-10 06:13:04'),
('55cff9ea-2fc9-11f1-9448-e66239f31cb0', '8f266f22-1d9d-11f1-b82d-97044e7d8834', 'logout', 'user', '8f266f22-1d9d-11f1-b82d-97044e7d8834', 'Super_admin logged out', '197.150.173.176', '2026-04-04 01:55:19'),
('5635b171-35de-11f1-9448-e66239f31cb0', 'd8e13ad8-2382-11f1-b82d-97044e7d8834', 'logout', 'user', 'd8e13ad8-2382-11f1-b82d-97044e7d8834', 'Admin logged out', '197.150.164.48', '2026-04-11 19:40:47'),
('567d0d36-3475-11f1-9448-e66239f31cb0', 'd8e13ad8-2382-11f1-b82d-97044e7d8834', 'login', 'user', 'd8e13ad8-2382-11f1-b82d-97044e7d8834', 'Admin logged in', '45.109.112.29', '2026-04-10 00:36:39'),
('56c16480-2385-11f1-b82d-97044e7d8834', 'd8e13ad8-2382-11f1-b82d-97044e7d8834', 'login', 'user', 'd8e13ad8-2382-11f1-b82d-97044e7d8834', 'Admin logged in', '156.217.99.167', '2026-03-19 11:16:55'),
('56f118a6-2952-11f1-9448-e66239f31cb0', '4f792c75-4a65-45a7-a0ef-d282acd83d87', 'create', 'menu_item', '56f059f5-2952-11f1-9448-e66239f31cb0', 'Created item: medicine', '45.109.220.74', '2026-03-26 20:28:24'),
('57148070-36b9-11f1-9448-e66239f31cb0', 'd8e13ad8-2382-11f1-b82d-97044e7d8834', 'logout', 'user', 'd8e13ad8-2382-11f1-b82d-97044e7d8834', 'Admin logged out', '154.143.40.184', '2026-04-13 00:48:28'),
('577d589d-2f7d-11f1-9448-e66239f31cb0', '4f792c75-4a65-45a7-a0ef-d282acd83d87', 'login', 'user', '4f792c75-4a65-45a7-a0ef-d282acd83d87', 'Vendor logged in', '197.222.117.240', '2026-04-03 16:51:21'),
('58049ef5-3070-11f1-9448-e66239f31cb0', '8f266f22-1d9d-11f1-b82d-97044e7d8834', 'login', 'user', '8f266f22-1d9d-11f1-b82d-97044e7d8834', 'Super_admin logged in', '154.143.143.60', '2026-04-04 21:50:49'),
('582266b6-320d-11f1-9448-e66239f31cb0', 'd8e13ad8-2382-11f1-b82d-97044e7d8834', 'login', 'user', 'd8e13ad8-2382-11f1-b82d-97044e7d8834', 'Admin logged in', '154.143.143.60', '2026-04-06 23:07:11'),
('592131e5-26f4-11f1-abf9-e1b0d104a220', '8f266f22-1d9d-11f1-b82d-97044e7d8834', 'create', 'admin', '5920fb61-26f4-11f1-abf9-e1b0d104a220', 'Created new admin: loay (loay@gmail.com)', '45.109.19.74', '2026-03-23 20:10:33'),
('592ac5ee-3345-11f1-9448-e66239f31cb0', '4f792c75-4a65-45a7-a0ef-d282acd83d87', 'login', 'user', '4f792c75-4a65-45a7-a0ef-d282acd83d87', 'Vendor logged in', '196.151.0.29', '2026-04-08 12:20:36'),
('5932879a-3457-11f1-9448-e66239f31cb0', 'd8e13ad8-2382-11f1-b82d-97044e7d8834', 'login', 'user', 'd8e13ad8-2382-11f1-b82d-97044e7d8834', 'Admin logged in', '45.109.112.29', '2026-04-09 21:01:58'),
('595ea0f8-2958-11f1-9448-e66239f31cb0', '4f792c75-4a65-45a7-a0ef-d282acd83d87', 'logout', 'user', '4f792c75-4a65-45a7-a0ef-d282acd83d87', 'Vendor logged out', '45.109.220.74', '2026-03-26 21:11:25'),
('59af9ec7-350e-11f1-9448-e66239f31cb0', '4f792c75-4a65-45a7-a0ef-d282acd83d87', 'logout', 'user', '4f792c75-4a65-45a7-a0ef-d282acd83d87', 'Vendor logged out', '197.223.152.7', '2026-04-10 18:51:57'),
('59b77690-35fa-11f1-9448-e66239f31cb0', '80c0bb8b-082d-4810-806f-0e1901d9d787', 'login', 'user', '80c0bb8b-082d-4810-806f-0e1901d9d787', 'Vendor logged in', '154.143.40.184', '2026-04-11 23:01:18'),
('59c4bfe0-2fc9-11f1-9448-e66239f31cb0', 'd8e13ad8-2382-11f1-b82d-97044e7d8834', 'login', 'user', 'd8e13ad8-2382-11f1-b82d-97044e7d8834', 'Admin logged in', '197.150.173.176', '2026-04-04 01:55:26'),
('5a803abb-3070-11f1-9448-e66239f31cb0', '8f266f22-1d9d-11f1-b82d-97044e7d8834', 'logout', 'user', '8f266f22-1d9d-11f1-b82d-97044e7d8834', 'Super_admin logged out', '154.143.143.60', '2026-04-04 21:50:53'),
('5b6bbc93-3456-11f1-9448-e66239f31cb0', '4f792c75-4a65-45a7-a0ef-d282acd83d87', 'logout', 'user', '4f792c75-4a65-45a7-a0ef-d282acd83d87', 'Vendor logged out', '45.109.112.29', '2026-04-09 20:54:53'),
('5c2bdc1f-35fb-11f1-9448-e66239f31cb0', 'b8175b08-34e6-4f5d-87d3-4c777677db33', 'login', 'user', 'b8175b08-34e6-4f5d-87d3-4c777677db33', 'Vendor logged in', '154.143.40.184', '2026-04-11 23:08:32'),
('5c705a81-2ef9-11f1-9448-e66239f31cb0', '8f266f22-1d9d-11f1-b82d-97044e7d8834', 'create', 'advertisement', '5c703dba-2ef9-11f1-9448-e66239f31cb0', 'Created advertisement: test add', '197.223.138.29', '2026-04-03 01:06:35'),
('5c9493d7-231c-11f1-b82d-97044e7d8834', '8f266f22-1d9d-11f1-b82d-97044e7d8834', 'logout', 'user', '8f266f22-1d9d-11f1-b82d-97044e7d8834', 'Super_admin logged out', '45.104.153.29', '2026-03-18 22:45:28'),
('5ceb4ba9-2ef9-11f1-9448-e66239f31cb0', '8f266f22-1d9d-11f1-b82d-97044e7d8834', 'update', 'advertisement', '5c703dba-2ef9-11f1-9448-e66239f31cb0', 'Uploaded image for advertisement', '197.223.138.29', '2026-04-03 01:06:36'),
('5d326e5b-320d-11f1-9448-e66239f31cb0', 'd8e13ad8-2382-11f1-b82d-97044e7d8834', 'logout', 'user', 'd8e13ad8-2382-11f1-b82d-97044e7d8834', 'Admin logged out', '154.143.143.60', '2026-04-06 23:07:20'),
('5d42ff84-1db4-11f1-b82d-97044e7d8834', '8f266f22-1d9d-11f1-b82d-97044e7d8834', 'login', 'user', '8f266f22-1d9d-11f1-b82d-97044e7d8834', 'Super_admin logged in', '2a02:4780:8:5::a', '2026-03-12 01:38:26'),
('5d86a772-1db4-11f1-b82d-97044e7d8834', '8f266f22-1d9d-11f1-b82d-97044e7d8834', 'logout', 'user', '8f266f22-1d9d-11f1-b82d-97044e7d8834', 'Super_admin logged out', '2a02:4780:8:5::a', '2026-03-12 01:38:26'),
('5e052cc5-303d-11f1-9448-e66239f31cb0', '4f792c75-4a65-45a7-a0ef-d282acd83d87', 'update_profile', 'vendor', '57e1966b-294a-11f1-9448-e66239f31cb0', 'Updated vendor profile', '154.143.143.60', '2026-04-04 15:45:55'),
('5e366440-26f4-11f1-abf9-e1b0d104a220', '8f266f22-1d9d-11f1-b82d-97044e7d8834', 'logout', 'user', '8f266f22-1d9d-11f1-b82d-97044e7d8834', 'Super_admin logged out', '45.109.19.74', '2026-03-23 20:10:41'),
('5e6f0fdf-230f-11f1-b82d-97044e7d8834', '8f266f22-1d9d-11f1-b82d-97044e7d8834', 'logout', 'user', '8f266f22-1d9d-11f1-b82d-97044e7d8834', 'Super_admin logged out', '156.217.125.91', '2026-03-18 21:12:28'),
('5e9ce809-34ec-11f1-9448-e66239f31cb0', 'd8e13ad8-2382-11f1-b82d-97044e7d8834', 'logout', 'user', 'd8e13ad8-2382-11f1-b82d-97044e7d8834', 'Admin logged out', '45.109.226.75', '2026-04-10 14:48:42'),
('5eab19d2-2fc5-11f1-9448-e66239f31cb0', '8f266f22-1d9d-11f1-b82d-97044e7d8834', 'deactivated', 'admin', '5920fb61-26f4-11f1-abf9-e1b0d104a220', 'Deactivated admin: loay', '197.150.173.176', '2026-04-04 01:26:56'),
('5ebac195-3d22-11f1-9448-e66239f31cb0', '68d947ef-fcec-426a-8316-155666848619', 'logout', 'user', '68d947ef-fcec-426a-8316-155666848619', 'Vendor logged out', '154.143.246.144', '2026-04-21 04:35:25'),
('5ee54395-26f3-11f1-abf9-e1b0d104a220', '8f266f22-1d9d-11f1-b82d-97044e7d8834', 'login', 'user', '8f266f22-1d9d-11f1-b82d-97044e7d8834', 'Super_admin logged in', '45.109.19.74', '2026-03-23 20:03:33'),
('5f9c3756-3498-11f1-9448-e66239f31cb0', '8f266f22-1d9d-11f1-b82d-97044e7d8834', 'login', 'user', '8f266f22-1d9d-11f1-b82d-97044e7d8834', 'Super_admin logged in', '45.109.226.75', '2026-04-10 04:47:26'),
('6094acf3-2fc5-11f1-9448-e66239f31cb0', '8f266f22-1d9d-11f1-b82d-97044e7d8834', 'activated', 'admin', '5920fb61-26f4-11f1-abf9-e1b0d104a220', 'Activated admin: loay', '197.150.173.176', '2026-04-04 01:27:00'),
('6104f781-35de-11f1-9448-e66239f31cb0', 'cf2239d2-2dd7-4116-abd3-1f24d48e8fe7', 'login', 'user', 'cf2239d2-2dd7-4116-abd3-1f24d48e8fe7', 'Vendor logged in', '197.150.164.48', '2026-04-11 19:41:05'),
('6113d042-2f7d-11f1-9448-e66239f31cb0', '4f792c75-4a65-45a7-a0ef-d282acd83d87', 'update_profile', 'vendor', '57e1966b-294a-11f1-9448-e66239f31cb0', 'Updated vendor profile', '197.222.117.240', '2026-04-03 16:51:37'),
('613ad592-294a-11f1-9448-e66239f31cb0', '8f266f22-1d9d-11f1-b82d-97044e7d8834', 'login', 'user', '8f266f22-1d9d-11f1-b82d-97044e7d8834', 'Super_admin logged in', '45.109.220.74', '2026-03-26 19:31:26'),
('614095ef-303d-11f1-9448-e66239f31cb0', '4f792c75-4a65-45a7-a0ef-d282acd83d87', 'logout', 'user', '4f792c75-4a65-45a7-a0ef-d282acd83d87', 'Vendor logged out', '154.143.143.60', '2026-04-04 15:46:00'),
('616e39af-2fc8-11f1-9448-e66239f31cb0', '8f266f22-1d9d-11f1-b82d-97044e7d8834', 'logout', 'user', '8f266f22-1d9d-11f1-b82d-97044e7d8834', 'Super_admin logged out', '197.150.173.176', '2026-04-04 01:48:29'),
('61701551-3526-11f1-9448-e66239f31cb0', 'd8e13ad8-2382-11f1-b82d-97044e7d8834', 'logout', 'user', 'd8e13ad8-2382-11f1-b82d-97044e7d8834', 'Admin logged out', '196.136.46.60', '2026-04-10 21:43:58'),
('61ddaa8c-3d25-11f1-9448-e66239f31cb0', 'f52cc98d-3d20-11f1-9448-e66239f31cb0', 'logout', 'user', 'f52cc98d-3d20-11f1-9448-e66239f31cb0', 'Admin logged out', '154.143.246.144', '2026-04-21 04:56:59'),
('62539de4-2f04-11f1-9448-e66239f31cb0', 'be9db6e5-2efe-11f1-9448-e66239f31cb0', 'login', 'user', 'be9db6e5-2efe-11f1-9448-e66239f31cb0', 'Vendor logged in', '197.223.138.29', '2026-04-03 02:25:30'),
('629b1ae4-361b-11f1-9448-e66239f31cb0', 'b8175b08-34e6-4f5d-87d3-4c777677db33', 'logout', 'user', 'b8175b08-34e6-4f5d-87d3-4c777677db33', 'Vendor logged out', '154.143.40.184', '2026-04-12 05:57:47'),
('62ab9c32-303a-11f1-9448-e66239f31cb0', '8f266f22-1d9d-11f1-b82d-97044e7d8834', 'create', 'admin', '62ab7c1e-303a-11f1-9448-e66239f31cb0', 'Created new admin: loay (loay@gmail.com)', '154.143.143.60', '2026-04-04 15:24:34'),
('6376a78f-2f7b-11f1-9448-e66239f31cb0', 'd8e13ad8-2382-11f1-b82d-97044e7d8834', 'logout', 'user', 'd8e13ad8-2382-11f1-b82d-97044e7d8834', 'Admin logged out', '197.222.117.240', '2026-04-03 16:37:22'),
('63d2d938-1db4-11f1-b82d-97044e7d8834', '8f266f22-1d9d-11f1-b82d-97044e7d8834', 'login', 'user', '8f266f22-1d9d-11f1-b82d-97044e7d8834', 'Super_admin logged in', '2a02:4780:8:5::a', '2026-03-12 01:38:37'),
('63f1e918-3513-11f1-9448-e66239f31cb0', '8f266f22-1d9d-11f1-b82d-97044e7d8834', 'update', 'advertisement', 'f5e9cc4e-2ef6-11f1-9448-e66239f31cb0', 'Updated advertisement: add one', '197.223.152.7', '2026-04-10 19:28:02'),
('64148d59-1db4-11f1-b82d-97044e7d8834', '8f266f22-1d9d-11f1-b82d-97044e7d8834', 'logout', 'user', '8f266f22-1d9d-11f1-b82d-97044e7d8834', 'Super_admin logged out', '2a02:4780:8:5::a', '2026-03-12 01:38:37'),
('644b04f4-3c54-11f1-9448-e66239f31cb0', '8f266f22-1d9d-11f1-b82d-97044e7d8834', 'login', 'user', '8f266f22-1d9d-11f1-b82d-97044e7d8834', 'Super_admin logged in', '197.223.177.198', '2026-04-20 04:00:58'),
('649fd0f1-2385-11f1-b82d-97044e7d8834', 'd8e13ad8-2382-11f1-b82d-97044e7d8834', 'logout', 'user', 'd8e13ad8-2382-11f1-b82d-97044e7d8834', 'Admin logged out', '156.217.99.167', '2026-03-19 11:17:19'),
('64be4542-2fc5-11f1-9448-e66239f31cb0', '8f266f22-1d9d-11f1-b82d-97044e7d8834', 'suspend', 'vendor', 'be9dd1ee-2efe-11f1-9448-e66239f31cb0', 'Suspended vendor: gaza grill', '197.150.173.176', '2026-04-04 01:27:07'),
('64c52921-32e9-11f1-9448-e66239f31cb0', '8f266f22-1d9d-11f1-b82d-97044e7d8834', 'login', 'user', '8f266f22-1d9d-11f1-b82d-97044e7d8834', 'Super_admin logged in', '154.143.143.60', '2026-04-08 01:22:22'),
('64ca79a7-294c-11f1-9448-e66239f31cb0', '4f792c75-4a65-45a7-a0ef-d282acd83d87', 'create', 'menu_category', '64ca6336-294c-11f1-9448-e66239f31cb0', 'Created category: Food', '45.109.220.74', '2026-03-26 19:45:51'),
('64db913b-35de-11f1-9448-e66239f31cb0', 'cf2239d2-2dd7-4116-abd3-1f24d48e8fe7', 'accept', 'order', '1e8023a6-a8d0-42d5-b4bb-4c5317631166', 'Vendor سوق accepted order', '197.150.164.48', '2026-04-11 19:41:11'),
('64e15c85-294a-11f1-9448-e66239f31cb0', '8f266f22-1d9d-11f1-b82d-97044e7d8834', 'approve', 'vendor', '57e1966b-294a-11f1-9448-e66239f31cb0', 'Approved vendor: gaza', '45.109.220.74', '2026-03-26 19:31:32'),
('65044514-2f53-11f1-9448-e66239f31cb0', '8f266f22-1d9d-11f1-b82d-97044e7d8834', 'logout', 'user', '8f266f22-1d9d-11f1-b82d-97044e7d8834', 'Super_admin logged out', '197.223.138.29', '2026-04-03 11:51:04'),
('6509b920-2f79-11f1-9448-e66239f31cb0', '8f266f22-1d9d-11f1-b82d-97044e7d8834', 'logout', 'user', '8f266f22-1d9d-11f1-b82d-97044e7d8834', 'Super_admin logged out', '197.222.117.240', '2026-04-03 16:23:05'),
('6533ae1c-2fc9-11f1-9448-e66239f31cb0', 'd8e13ad8-2382-11f1-b82d-97044e7d8834', 'logout', 'user', 'd8e13ad8-2382-11f1-b82d-97044e7d8834', 'Admin logged out', '197.150.173.176', '2026-04-04 01:55:45'),
('65ef9cba-2fc5-11f1-9448-e66239f31cb0', '8f266f22-1d9d-11f1-b82d-97044e7d8834', 'reactivate', 'vendor', 'be9dd1ee-2efe-11f1-9448-e66239f31cb0', 'Reactivated vendor: gaza grill', '197.150.173.176', '2026-04-04 01:27:09'),
('661cbd4f-34a6-11f1-9448-e66239f31cb0', 'd8e13ad8-2382-11f1-b82d-97044e7d8834', 'logout', 'user', 'd8e13ad8-2382-11f1-b82d-97044e7d8834', 'Admin logged out', '45.109.226.75', '2026-04-10 06:27:50'),
('662e75ec-26f4-11f1-abf9-e1b0d104a220', NULL, 'login', 'user', '5920fb61-26f4-11f1-abf9-e1b0d104a220', 'Admin logged in', '45.109.19.74', '2026-03-23 20:10:55'),
('6665500a-2ef9-11f1-9448-e66239f31cb0', '8f266f22-1d9d-11f1-b82d-97044e7d8834', 'update', 'advertisement', 'f5e9cc4e-2ef6-11f1-9448-e66239f31cb0', 'Updated advertisement: add one', '197.223.138.29', '2026-04-03 01:06:52'),
('669e4a36-303a-11f1-9448-e66239f31cb0', '8f266f22-1d9d-11f1-b82d-97044e7d8834', 'logout', 'user', '8f266f22-1d9d-11f1-b82d-97044e7d8834', 'Super_admin logged out', '154.143.143.60', '2026-04-04 15:24:41'),
('66d77413-35ce-11f1-9448-e66239f31cb0', 'd8e13ad8-2382-11f1-b82d-97044e7d8834', 'logout', 'user', 'd8e13ad8-2382-11f1-b82d-97044e7d8834', 'Admin logged out', '197.150.164.48', '2026-04-11 17:46:43'),
('66e8985d-34ec-11f1-9448-e66239f31cb0', '8f266f22-1d9d-11f1-b82d-97044e7d8834', 'login', 'user', '8f266f22-1d9d-11f1-b82d-97044e7d8834', 'Super_admin logged in', '45.109.226.75', '2026-04-10 14:48:56'),
('66f77ec9-238f-11f1-b82d-97044e7d8834', '8f266f22-1d9d-11f1-b82d-97044e7d8834', 'logout', 'user', '8f266f22-1d9d-11f1-b82d-97044e7d8834', 'Super_admin logged out', '156.217.99.167', '2026-03-19 12:28:58'),
('67193412-2f04-11f1-9448-e66239f31cb0', 'be9db6e5-2efe-11f1-9448-e66239f31cb0', 'logout', 'user', 'be9db6e5-2efe-11f1-9448-e66239f31cb0', 'Vendor logged out', '197.223.138.29', '2026-04-03 02:25:38'),
('6760cf76-374c-11f1-9448-e66239f31cb0', '8f266f22-1d9d-11f1-b82d-97044e7d8834', 'cancel', 'order', 'ade4e928-3973-4f5a-8b9b-42b7608ddb22', 'Cancelled order: ORD-2026-000009 - Reason: عميل بيهزر ونا قدوتي استاذ محمد قموصة', '154.143.40.184', '2026-04-13 18:21:11'),
('677a938a-345f-11f1-9448-e66239f31cb0', '8f266f22-1d9d-11f1-b82d-97044e7d8834', 'login', 'user', '8f266f22-1d9d-11f1-b82d-97044e7d8834', 'Super_admin logged in', '45.109.112.29', '2026-04-09 21:59:38'),
('678e023e-3456-11f1-9448-e66239f31cb0', 'd8e13ad8-2382-11f1-b82d-97044e7d8834', 'login', 'user', 'd8e13ad8-2382-11f1-b82d-97044e7d8834', 'Admin logged in', '45.109.112.29', '2026-04-09 20:55:13'),
('6898df56-294a-11f1-9448-e66239f31cb0', '8f266f22-1d9d-11f1-b82d-97044e7d8834', 'logout', 'user', '8f266f22-1d9d-11f1-b82d-97044e7d8834', 'Super_admin logged out', '45.109.220.74', '2026-03-26 19:31:38'),
('68fd0e14-36b9-11f1-9448-e66239f31cb0', '8f266f22-1d9d-11f1-b82d-97044e7d8834', 'login', 'user', '8f266f22-1d9d-11f1-b82d-97044e7d8834', 'Super_admin logged in', '154.143.40.184', '2026-04-13 00:48:58'),
('692157da-2f0a-11f1-9448-e66239f31cb0', 'd8e13ad8-2382-11f1-b82d-97044e7d8834', 'update', 'delivery_zone', 'bc140619-2f03-11f1-9448-e66239f31cb0', 'Updated delivery zone: test zone', '197.223.138.29', '2026-04-03 03:08:38'),
('694ef8c7-35cc-11f1-9448-e66239f31cb0', '8f266f22-1d9d-11f1-b82d-97044e7d8834', 'update', 'advertisement', 'f5e9cc4e-2ef6-11f1-9448-e66239f31cb0', 'Updated advertisement: add one', '197.150.164.48', '2026-04-11 17:32:28'),
('696d4356-3509-11f1-9448-e66239f31cb0', 'd8e13ad8-2382-11f1-b82d-97044e7d8834', 'logout', 'user', 'd8e13ad8-2382-11f1-b82d-97044e7d8834', 'Admin logged out', '197.223.152.7', '2026-04-10 18:16:36'),
('69b0d2a1-35fa-11f1-9448-e66239f31cb0', '80c0bb8b-082d-4810-806f-0e1901d9d787', 'update_profile', 'vendor', '0f38e4e9-2316-11f1-b82d-97044e7d8834', 'Updated vendor profile', '154.143.40.184', '2026-04-11 23:01:45'),
('69b5cbd0-3430-11f1-9448-e66239f31cb0', '4f792c75-4a65-45a7-a0ef-d282acd83d87', 'login', 'user', '4f792c75-4a65-45a7-a0ef-d282acd83d87', 'Vendor logged in', '197.222.207.2', '2026-04-09 16:23:16'),
('69b68a9c-35cc-11f1-9448-e66239f31cb0', '8f266f22-1d9d-11f1-b82d-97044e7d8834', 'update', 'advertisement', 'f5e9cc4e-2ef6-11f1-9448-e66239f31cb0', 'Uploaded image for advertisement', '197.150.164.48', '2026-04-11 17:32:28'),
('69b9c39c-3d21-11f1-9448-e66239f31cb0', '8f266f22-1d9d-11f1-b82d-97044e7d8834', 'login', 'user', '8f266f22-1d9d-11f1-b82d-97044e7d8834', 'Super_admin logged in', '154.143.246.144', '2026-04-21 04:28:34'),
('6a7e1f8c-2fc5-11f1-9448-e66239f31cb0', '8f266f22-1d9d-11f1-b82d-97044e7d8834', 'deactivated', 'customer', 'ad30e781-9ca7-4f1f-825f-40b0d4d4b8c0', 'Deactivated customer: User', '197.150.173.176', '2026-04-04 01:27:16'),
('6ac2f419-35de-11f1-9448-e66239f31cb0', 'cf2239d2-2dd7-4116-abd3-1f24d48e8fe7', 'mark_preparing', 'order', '1e8023a6-a8d0-42d5-b4bb-4c5317631166', 'Order being prepared', '197.150.164.48', '2026-04-11 19:41:21'),
('6b9e64bc-2392-11f1-b82d-97044e7d8834', '8f266f22-1d9d-11f1-b82d-97044e7d8834', 'login', 'user', '8f266f22-1d9d-11f1-b82d-97044e7d8834', 'Super_admin logged in', '156.217.99.167', '2026-03-19 12:50:34'),
('6bc8a495-2f7b-11f1-9448-e66239f31cb0', '8f266f22-1d9d-11f1-b82d-97044e7d8834', 'login', 'user', '8f266f22-1d9d-11f1-b82d-97044e7d8834', 'Super_admin logged in', '197.222.117.240', '2026-04-03 16:37:36'),
('6c4c6e62-2f92-11f1-9448-e66239f31cb0', '8f266f22-1d9d-11f1-b82d-97044e7d8834', 'deactivated', 'customer', 'a093003a-6eaf-42cf-92e0-f4660c0550e2', 'Deactivated customer: Test User', '197.222.117.240', '2026-04-03 19:22:15'),
('6c504242-231b-11f1-b82d-97044e7d8834', '8f266f22-1d9d-11f1-b82d-97044e7d8834', 'login', 'user', '8f266f22-1d9d-11f1-b82d-97044e7d8834', 'Super_admin logged in', '45.104.153.29', '2026-03-18 22:38:45'),
('6c7c0b95-2fc5-11f1-9448-e66239f31cb0', '8f266f22-1d9d-11f1-b82d-97044e7d8834', 'activated', 'customer', 'ad30e781-9ca7-4f1f-825f-40b0d4d4b8c0', 'Activated customer: User', '197.150.173.176', '2026-04-04 01:27:20'),
('6ce3db0e-26d8-11f1-abf9-e1b0d104a220', '8f266f22-1d9d-11f1-b82d-97044e7d8834', 'login', 'user', '8f266f22-1d9d-11f1-b82d-97044e7d8834', 'Super_admin logged in', '45.109.19.74', '2026-03-23 16:50:40'),
('6cf625d4-3511-11f1-9448-e66239f31cb0', 'b8175b08-34e6-4f5d-87d3-4c777677db33', 'logout', 'user', 'b8175b08-34e6-4f5d-87d3-4c777677db33', 'Vendor logged out', '197.223.152.7', '2026-04-10 19:13:58'),
('6d18d81d-35cb-11f1-9448-e66239f31cb0', '80c0bb8b-082d-4810-806f-0e1901d9d787', 'logout', 'user', '80c0bb8b-082d-4810-806f-0e1901d9d787', 'Vendor logged out', '197.150.164.48', '2026-04-11 17:25:25'),
('6d484a9f-3510-11f1-9448-e66239f31cb0', '80c0bb8b-082d-4810-806f-0e1901d9d787', 'login', 'user', '80c0bb8b-082d-4810-806f-0e1901d9d787', 'Vendor logged in', '197.223.152.7', '2026-04-10 19:06:49'),
('6d607681-3499-11f1-9448-e66239f31cb0', 'd8e13ad8-2382-11f1-b82d-97044e7d8834', 'logout', 'user', 'd8e13ad8-2382-11f1-b82d-97044e7d8834', 'Admin logged out', '45.109.226.75', '2026-04-10 04:54:59'),
('6d64d9e9-3513-11f1-9448-e66239f31cb0', '8f266f22-1d9d-11f1-b82d-97044e7d8834', 'update', 'advertisement', 'f5e9cc4e-2ef6-11f1-9448-e66239f31cb0', 'Updated advertisement: add one', '197.223.152.7', '2026-04-10 19:28:18'),
('6d7a137a-2f79-11f1-9448-e66239f31cb0', '4f792c75-4a65-45a7-a0ef-d282acd83d87', 'login', 'user', '4f792c75-4a65-45a7-a0ef-d282acd83d87', 'Vendor logged in', '197.222.117.240', '2026-04-03 16:23:19'),
('6dbc36f6-3513-11f1-9448-e66239f31cb0', '8f266f22-1d9d-11f1-b82d-97044e7d8834', 'update', 'advertisement', 'f5e9cc4e-2ef6-11f1-9448-e66239f31cb0', 'Uploaded image for advertisement', '197.223.152.7', '2026-04-10 19:28:18'),
('6de5a944-303d-11f1-9448-e66239f31cb0', '8f266f22-1d9d-11f1-b82d-97044e7d8834', 'login', 'user', '8f266f22-1d9d-11f1-b82d-97044e7d8834', 'Super_admin logged in', '154.143.143.60', '2026-04-04 15:46:22'),
('6e1ab871-35fa-11f1-9448-e66239f31cb0', '80c0bb8b-082d-4810-806f-0e1901d9d787', 'logout', 'user', '80c0bb8b-082d-4810-806f-0e1901d9d787', 'Vendor logged out', '154.143.40.184', '2026-04-11 23:01:53'),
('6e1c4038-35fb-11f1-9448-e66239f31cb0', 'b8175b08-34e6-4f5d-87d3-4c777677db33', 'update_profile', 'vendor', 'e4a9a3cd-26f4-11f1-abf9-e1b0d104a220', 'Updated vendor profile', '154.143.40.184', '2026-04-11 23:09:02'),
('6e59e65b-35de-11f1-9448-e66239f31cb0', 'cf2239d2-2dd7-4116-abd3-1f24d48e8fe7', 'mark_ready', 'order', '1e8023a6-a8d0-42d5-b4bb-4c5317631166', 'All vendors ready for pickup', '197.150.164.48', '2026-04-11 19:41:27'),
('6e682303-294a-11f1-9448-e66239f31cb0', '4f792c75-4a65-45a7-a0ef-d282acd83d87', 'login', 'user', '4f792c75-4a65-45a7-a0ef-d282acd83d87', 'Vendor logged in', '45.109.220.74', '2026-03-26 19:31:48'),
('6ed3f8a6-2f92-11f1-9448-e66239f31cb0', '8f266f22-1d9d-11f1-b82d-97044e7d8834', 'deactivated', 'customer', 'ad30e781-9ca7-4f1f-825f-40b0d4d4b8c0', 'Deactivated customer: Test User', '197.222.117.240', '2026-04-03 19:22:19'),
('6f000001-3d21-11f1-9448-e66239f31cb0', '8f266f22-1d9d-11f1-b82d-97044e7d8834', 'approve', 'vendor', '5de1f9b7-3d21-11f1-9448-e66239f31cb0', 'Approved vendor: North food', '154.143.246.144', '2026-04-21 04:28:43'),
('6f9f2a7d-238f-11f1-b82d-97044e7d8834', 'd8e13ad8-2382-11f1-b82d-97044e7d8834', 'login', 'user', 'd8e13ad8-2382-11f1-b82d-97044e7d8834', 'Admin logged in', '156.217.99.167', '2026-03-19 12:29:12'),
('700d31b1-2fc3-11f1-9448-e66239f31cb0', '8f266f22-1d9d-11f1-b82d-97044e7d8834', 'logout', 'user', '8f266f22-1d9d-11f1-b82d-97044e7d8834', 'Super_admin logged out', '197.150.173.176', '2026-04-04 01:13:07'),
('70327c8c-35c9-11f1-9448-e66239f31cb0', '4f792c75-4a65-45a7-a0ef-d282acd83d87', 'logout', 'user', '4f792c75-4a65-45a7-a0ef-d282acd83d87', 'Vendor logged out', '197.150.164.48', '2026-04-11 17:11:11'),
('70c8763a-2fc9-11f1-9448-e66239f31cb0', '4f792c75-4a65-45a7-a0ef-d282acd83d87', 'login', 'user', '4f792c75-4a65-45a7-a0ef-d282acd83d87', 'Vendor logged in', '197.150.173.176', '2026-04-04 01:56:05'),
('71161a77-3d22-11f1-9448-e66239f31cb0', 'e64dd274-3d20-11f1-9448-e66239f31cb0', 'login', 'user', 'e64dd274-3d20-11f1-9448-e66239f31cb0', 'Admin logged in', '154.143.246.144', '2026-04-21 04:35:56'),
('714020cd-34a4-11f1-9448-e66239f31cb0', '4f792c75-4a65-45a7-a0ef-d282acd83d87', 'login', 'user', '4f792c75-4a65-45a7-a0ef-d282acd83d87', 'Vendor logged in', '45.109.226.75', '2026-04-10 06:13:50'),
('7144bfa3-3741-11f1-9448-e66239f31cb0', 'd8e13ad8-2382-11f1-b82d-97044e7d8834', 'logout', 'user', 'd8e13ad8-2382-11f1-b82d-97044e7d8834', 'Admin logged out', '154.143.40.184', '2026-04-13 17:02:43'),
('71b645e0-361b-11f1-9448-e66239f31cb0', '8f266f22-1d9d-11f1-b82d-97044e7d8834', 'login', 'user', '8f266f22-1d9d-11f1-b82d-97044e7d8834', 'Super_admin logged in', '154.143.40.184', '2026-04-12 05:58:12'),
('71c94ac4-2fc8-11f1-9448-e66239f31cb0', 'd8e13ad8-2382-11f1-b82d-97044e7d8834', 'login', 'user', 'd8e13ad8-2382-11f1-b82d-97044e7d8834', 'Admin logged in', '197.150.173.176', '2026-04-04 01:48:57'),
('71f73305-3526-11f1-9448-e66239f31cb0', 'be9db6e5-2efe-11f1-9448-e66239f31cb0', 'login', 'user', 'be9db6e5-2efe-11f1-9448-e66239f31cb0', 'Vendor logged in', '196.136.46.60', '2026-04-10 21:44:26'),
('72d484b3-2385-11f1-b82d-97044e7d8834', '8f266f22-1d9d-11f1-b82d-97044e7d8834', 'login', 'user', '8f266f22-1d9d-11f1-b82d-97044e7d8834', 'Super_admin logged in', '156.217.99.167', '2026-03-19 11:17:43'),
('73159e1e-238c-11f1-b82d-97044e7d8834', '8f266f22-1d9d-11f1-b82d-97044e7d8834', 'change_password', 'user', '8f266f22-1d9d-11f1-b82d-97044e7d8834', 'Password changed via profile', '156.217.99.167', '2026-03-19 12:07:49'),
('73287602-350e-11f1-9448-e66239f31cb0', '4f792c75-4a65-45a7-a0ef-d282acd83d87', 'login', 'user', '4f792c75-4a65-45a7-a0ef-d282acd83d87', 'Vendor logged in', '197.223.152.7', '2026-04-10 18:52:40'),
('73980fc3-26f4-11f1-abf9-e1b0d104a220', NULL, 'logout', 'user', '5920fb61-26f4-11f1-abf9-e1b0d104a220', 'Admin logged out', '45.109.19.74', '2026-03-23 20:11:17'),
('73e6144c-35cb-11f1-9448-e66239f31cb0', 'b8175b08-34e6-4f5d-87d3-4c777677db33', 'login', 'user', 'b8175b08-34e6-4f5d-87d3-4c777677db33', 'Vendor logged in', '197.150.164.48', '2026-04-11 17:25:36'),
('74a61e26-2f92-11f1-9448-e66239f31cb0', '8f266f22-1d9d-11f1-b82d-97044e7d8834', 'activated', 'customer', 'a093003a-6eaf-42cf-92e0-f4660c0550e2', 'Activated customer: Test User', '197.222.117.240', '2026-04-03 19:22:29'),
('750bec69-2f53-11f1-9448-e66239f31cb0', 'be9db6e5-2efe-11f1-9448-e66239f31cb0', 'login', 'user', 'be9db6e5-2efe-11f1-9448-e66239f31cb0', 'Vendor logged in', '197.223.138.29', '2026-04-03 11:51:31'),
('76499784-3b30-11f1-9448-e66239f31cb0', 'd8e13ad8-2382-11f1-b82d-97044e7d8834', 'logout', 'user', 'd8e13ad8-2382-11f1-b82d-97044e7d8834', 'Admin logged out', '158.140.85.78', '2026-04-18 17:11:15'),
('772c8315-238f-11f1-b82d-97044e7d8834', 'd8e13ad8-2382-11f1-b82d-97044e7d8834', 'logout', 'user', 'd8e13ad8-2382-11f1-b82d-97044e7d8834', 'Admin logged out', '156.217.99.167', '2026-03-19 12:29:25'),
('772dff44-35de-11f1-9448-e66239f31cb0', 'cf2239d2-2dd7-4116-abd3-1f24d48e8fe7', 'logout', 'user', 'cf2239d2-2dd7-4116-abd3-1f24d48e8fe7', 'Vendor logged out', '197.150.164.48', '2026-04-11 19:41:42'),
('77505f58-303a-11f1-9448-e66239f31cb0', '62ab7c1e-303a-11f1-9448-e66239f31cb0', 'login', 'user', '62ab7c1e-303a-11f1-9448-e66239f31cb0', 'Admin logged in', '154.143.143.60', '2026-04-04 15:25:09'),
('775101f7-345f-11f1-9448-e66239f31cb0', '8f266f22-1d9d-11f1-b82d-97044e7d8834', 'logout', 'user', '8f266f22-1d9d-11f1-b82d-97044e7d8834', 'Super_admin logged out', '45.109.112.29', '2026-04-09 22:00:05'),
('7753cd16-2f7d-11f1-9448-e66239f31cb0', '4f792c75-4a65-45a7-a0ef-d282acd83d87', 'update_profile', 'vendor', '57e1966b-294a-11f1-9448-e66239f31cb0', 'Updated vendor profile', '197.222.117.240', '2026-04-03 16:52:14'),
('775c4dae-2fbf-11f1-9448-e66239f31cb0', '8f266f22-1d9d-11f1-b82d-97044e7d8834', 'logout', 'user', '8f266f22-1d9d-11f1-b82d-97044e7d8834', 'Super_admin logged out', '197.150.173.176', '2026-04-04 00:44:41'),
('7805beb5-2f92-11f1-9448-e66239f31cb0', '8f266f22-1d9d-11f1-b82d-97044e7d8834', 'activated', 'customer', 'ad30e781-9ca7-4f1f-825f-40b0d4d4b8c0', 'Activated customer: Test User', '197.222.117.240', '2026-04-03 19:22:35'),
('78400a8e-3526-11f1-9448-e66239f31cb0', 'be9db6e5-2efe-11f1-9448-e66239f31cb0', 'logout', 'user', 'be9db6e5-2efe-11f1-9448-e66239f31cb0', 'Vendor logged out', '196.136.46.60', '2026-04-10 21:44:36'),
('7858f7af-34a6-11f1-9448-e66239f31cb0', '8f266f22-1d9d-11f1-b82d-97044e7d8834', 'login', 'user', '8f266f22-1d9d-11f1-b82d-97044e7d8834', 'Super_admin logged in', '45.109.226.75', '2026-04-10 06:28:21'),
('7861e879-3469-11f1-9448-e66239f31cb0', 'd8e13ad8-2382-11f1-b82d-97044e7d8834', 'logout', 'user', 'd8e13ad8-2382-11f1-b82d-97044e7d8834', 'Admin logged out', '45.109.112.29', '2026-04-09 23:11:42'),
('788855d6-34a3-11f1-9448-e66239f31cb0', 'd8e13ad8-2382-11f1-b82d-97044e7d8834', 'login', 'user', 'd8e13ad8-2382-11f1-b82d-97044e7d8834', 'Admin logged in', '45.109.226.75', '2026-04-10 06:06:53'),
('790abc48-2fc9-11f1-9448-e66239f31cb0', '4f792c75-4a65-45a7-a0ef-d282acd83d87', 'logout', 'user', '4f792c75-4a65-45a7-a0ef-d282acd83d87', 'Vendor logged out', '197.150.173.176', '2026-04-04 01:56:19'),
('7910be6a-2fc8-11f1-9448-e66239f31cb0', 'd8e13ad8-2382-11f1-b82d-97044e7d8834', 'logout', 'user', 'd8e13ad8-2382-11f1-b82d-97044e7d8834', 'Admin logged out', '197.150.173.176', '2026-04-04 01:49:09'),
('79afecdd-35c9-11f1-9448-e66239f31cb0', 'cf2239d2-2dd7-4116-abd3-1f24d48e8fe7', 'login', 'user', 'cf2239d2-2dd7-4116-abd3-1f24d48e8fe7', 'Vendor logged in', '197.150.164.48', '2026-04-11 17:11:27'),
('79c605bb-2fc3-11f1-9448-e66239f31cb0', '4f792c75-4a65-45a7-a0ef-d282acd83d87', 'login', 'user', '4f792c75-4a65-45a7-a0ef-d282acd83d87', 'Vendor logged in', '197.150.173.176', '2026-04-04 01:13:23'),
('79c999ff-3d21-11f1-9448-e66239f31cb0', '8f266f22-1d9d-11f1-b82d-97044e7d8834', 'logout', 'user', '8f266f22-1d9d-11f1-b82d-97044e7d8834', 'Super_admin logged out', '154.143.246.144', '2026-04-21 04:29:01'),
('79e1f8b3-26d8-11f1-abf9-e1b0d104a220', '8f266f22-1d9d-11f1-b82d-97044e7d8834', 'logout', 'user', '8f266f22-1d9d-11f1-b82d-97044e7d8834', 'Super_admin logged out', '45.109.19.74', '2026-03-23 16:51:02'),
('79eaa488-294c-11f1-9448-e66239f31cb0', '4f792c75-4a65-45a7-a0ef-d282acd83d87', 'create', 'menu_category', '79ea51e9-294c-11f1-9448-e66239f31cb0', 'Created category: Pharamacy', '45.109.220.74', '2026-03-26 19:46:26'),
('7a725c2d-36b9-11f1-9448-e66239f31cb0', '8f266f22-1d9d-11f1-b82d-97044e7d8834', 'deactivated', 'customer', '33ba298c-7bd8-4338-b16b-3817a328e8ed', 'Deactivated customer: Kareem Aboshady', '154.143.40.184', '2026-04-13 00:49:27'),
('7a87a0ed-3d98-11f1-9448-e66239f31cb0', '6255a20b-3d97-11f1-9448-e66239f31cb0', 'logout', 'user', '6255a20b-3d97-11f1-9448-e66239f31cb0', 'Vendor logged out', '154.143.246.144', '2026-04-21 18:40:52'),
('7aa682a1-238c-11f1-b82d-97044e7d8834', '8f266f22-1d9d-11f1-b82d-97044e7d8834', 'logout', 'user', '8f266f22-1d9d-11f1-b82d-97044e7d8834', 'Super_admin logged out', '156.217.99.167', '2026-03-19 12:08:02'),
('7b3c464c-30f3-11f1-9448-e66239f31cb0', '4f792c75-4a65-45a7-a0ef-d282acd83d87', 'logout', 'user', '4f792c75-4a65-45a7-a0ef-d282acd83d87', 'Vendor logged out', '158.140.100.32', '2026-04-05 13:29:32'),
('7b74bec3-2f79-11f1-9448-e66239f31cb0', '4f792c75-4a65-45a7-a0ef-d282acd83d87', 'logout', 'user', '4f792c75-4a65-45a7-a0ef-d282acd83d87', 'Vendor logged out', '197.222.117.240', '2026-04-03 16:23:43'),
('7bf65ce9-361b-11f1-9448-e66239f31cb0', '8f266f22-1d9d-11f1-b82d-97044e7d8834', 'collect_earning', 'platform_earnings', 'cec2627e-35e0-11f1-9448-e66239f31cb0', 'Collected earning: 60.00 NIS', '154.143.40.184', '2026-04-12 05:58:29'),
('7c1dc0e2-36b9-11f1-9448-e66239f31cb0', '8f266f22-1d9d-11f1-b82d-97044e7d8834', 'activated', 'customer', '33ba298c-7bd8-4338-b16b-3817a328e8ed', 'Activated customer: Kareem Aboshady', '154.143.40.184', '2026-04-13 00:49:30'),
('7d74c557-26f3-11f1-abf9-e1b0d104a220', '8f266f22-1d9d-11f1-b82d-97044e7d8834', 'logout', 'user', '8f266f22-1d9d-11f1-b82d-97044e7d8834', 'Super_admin logged out', '45.109.19.74', '2026-03-23 20:04:24'),
('7eaae4ad-35fa-11f1-9448-e66239f31cb0', 'be9db6e5-2efe-11f1-9448-e66239f31cb0', 'login', 'user', 'be9db6e5-2efe-11f1-9448-e66239f31cb0', 'Vendor logged in', '154.143.40.184', '2026-04-11 23:02:20'),
('7fade163-2f92-11f1-9448-e66239f31cb0', '8f266f22-1d9d-11f1-b82d-97044e7d8834', 'deactivated', 'customer', 'ad30e781-9ca7-4f1f-825f-40b0d4d4b8c0', 'Deactivated customer: Test User', '197.222.117.240', '2026-04-03 19:22:47'),
('7fff82a7-3b30-11f1-9448-e66239f31cb0', '4f792c75-4a65-45a7-a0ef-d282acd83d87', 'login', 'user', '4f792c75-4a65-45a7-a0ef-d282acd83d87', 'Vendor logged in', '158.140.85.78', '2026-04-18 17:11:31'),
('80b38747-303a-11f1-9448-e66239f31cb0', '62ab7c1e-303a-11f1-9448-e66239f31cb0', 'logout', 'user', '62ab7c1e-303a-11f1-9448-e66239f31cb0', 'Admin logged out', '154.143.143.60', '2026-04-04 15:25:25'),
('8103f7c3-26d8-11f1-abf9-e1b0d104a220', '80c0bb8b-082d-4810-806f-0e1901d9d787', 'login', 'user', '80c0bb8b-082d-4810-806f-0e1901d9d787', 'Vendor logged in', '45.109.19.74', '2026-03-23 16:51:14'),
('815113be-35de-11f1-9448-e66239f31cb0', 'd8e13ad8-2382-11f1-b82d-97044e7d8834', 'login', 'user', 'd8e13ad8-2382-11f1-b82d-97044e7d8834', 'Admin logged in', '197.150.164.48', '2026-04-11 19:41:59'),
('81a4f169-2fc2-11f1-9448-e66239f31cb0', 'd8e13ad8-2382-11f1-b82d-97044e7d8834', 'logout', 'user', 'd8e13ad8-2382-11f1-b82d-97044e7d8834', 'Admin logged out', '197.150.173.176', '2026-04-04 01:06:27'),
('81cc7a33-2fbf-11f1-9448-e66239f31cb0', 'd8e13ad8-2382-11f1-b82d-97044e7d8834', 'login', 'user', 'd8e13ad8-2382-11f1-b82d-97044e7d8834', 'Admin logged in', '197.150.173.176', '2026-04-04 00:44:58'),
('8321c1c3-3741-11f1-9448-e66239f31cb0', '8f266f22-1d9d-11f1-b82d-97044e7d8834', 'login', 'user', '8f266f22-1d9d-11f1-b82d-97044e7d8834', 'Super_admin logged in', '154.143.40.184', '2026-04-13 17:03:13'),
('83226a32-3511-11f1-9448-e66239f31cb0', 'be9db6e5-2efe-11f1-9448-e66239f31cb0', 'login', 'user', 'be9db6e5-2efe-11f1-9448-e66239f31cb0', 'Vendor logged in', '197.223.152.7', '2026-04-10 19:14:35'),
('83927e24-3d21-11f1-9448-e66239f31cb0', '68d947ef-fcec-426a-8316-155666848619', 'login', 'user', '68d947ef-fcec-426a-8316-155666848619', 'Vendor logged in', '154.143.246.144', '2026-04-21 04:29:17'),
('83951bed-35cd-11f1-9448-e66239f31cb0', '8f266f22-1d9d-11f1-b82d-97044e7d8834', 'logout', 'user', '8f266f22-1d9d-11f1-b82d-97044e7d8834', 'Super_admin logged out', '197.150.164.48', '2026-04-11 17:40:21'),
('84371da1-3469-11f1-9448-e66239f31cb0', '8f266f22-1d9d-11f1-b82d-97044e7d8834', 'login', 'user', '8f266f22-1d9d-11f1-b82d-97044e7d8834', 'Super_admin logged in', '45.109.112.29', '2026-04-09 23:12:01'),
('847295f1-30f3-11f1-9448-e66239f31cb0', '8f266f22-1d9d-11f1-b82d-97044e7d8834', 'login', 'user', '8f266f22-1d9d-11f1-b82d-97044e7d8834', 'Super_admin logged in', '158.140.100.32', '2026-04-05 13:29:48'),
('84803bd1-347f-11f1-9448-e66239f31cb0', 'd8e13ad8-2382-11f1-b82d-97044e7d8834', 'logout', 'user', 'd8e13ad8-2382-11f1-b82d-97044e7d8834', 'Admin logged out', '45.109.112.29', '2026-04-10 01:49:31'),
('84ba0dd3-35cb-11f1-9448-e66239f31cb0', 'b8175b08-34e6-4f5d-87d3-4c777677db33', 'logout', 'user', 'b8175b08-34e6-4f5d-87d3-4c777677db33', 'Vendor logged out', '197.150.164.48', '2026-04-11 17:26:04'),
('850bb61d-33ab-11f1-9448-e66239f31cb0', '4f792c75-4a65-45a7-a0ef-d282acd83d87', 'logout', 'user', '4f792c75-4a65-45a7-a0ef-d282acd83d87', 'Vendor logged out', '154.143.143.60', '2026-04-09 00:31:58'),
('85217dcd-238c-11f1-b82d-97044e7d8834', '8f266f22-1d9d-11f1-b82d-97044e7d8834', 'login', 'user', '8f266f22-1d9d-11f1-b82d-97044e7d8834', 'Super_admin logged in', '156.217.99.167', '2026-03-19 12:08:20'),
('852be780-3509-11f1-9448-e66239f31cb0', '4f792c75-4a65-45a7-a0ef-d282acd83d87', 'login', 'user', '4f792c75-4a65-45a7-a0ef-d282acd83d87', 'Vendor logged in', '197.223.152.7', '2026-04-10 18:17:23'),
('8541a441-3b55-11f1-9448-e66239f31cb0', '8f266f22-1d9d-11f1-b82d-97044e7d8834', 'logout', 'user', '8f266f22-1d9d-11f1-b82d-97044e7d8834', 'Super_admin logged out', '45.109.196.109', '2026-04-18 21:36:31'),
('87110e15-3b30-11f1-9448-e66239f31cb0', '4f792c75-4a65-45a7-a0ef-d282acd83d87', 'accept', 'order', '1fa6fc17-b531-490c-a327-775013af98db', 'Vendor فتح الله ماركت accepted order', '158.140.85.78', '2026-04-18 17:11:43'),
('875042c8-1db5-11f1-b82d-97044e7d8834', '8f266f22-1d9d-11f1-b82d-97044e7d8834', 'login', 'user', '8f266f22-1d9d-11f1-b82d-97044e7d8834', 'Super_admin logged in', '2a02:4780:8:5::a', '2026-03-12 01:46:46'),
('87741c1f-1db5-11f1-b82d-97044e7d8834', '8f266f22-1d9d-11f1-b82d-97044e7d8834', 'refresh_token', 'user', '8f266f22-1d9d-11f1-b82d-97044e7d8834', 'Token refreshed', '2a02:4780:8:5::a', '2026-03-12 01:46:46'),
('87d6902a-2f92-11f1-9448-e66239f31cb0', '8f266f22-1d9d-11f1-b82d-97044e7d8834', 'activated', 'customer', 'ad30e781-9ca7-4f1f-825f-40b0d4d4b8c0', 'Activated customer: Test User', '197.222.117.240', '2026-04-03 19:23:01'),
('88089fd7-35de-11f1-9448-e66239f31cb0', 'd8e13ad8-2382-11f1-b82d-97044e7d8834', 'dispatch', 'order', '1e8023a6-a8d0-42d5-b4bb-4c5317631166', 'Dispatched order ORD-2026-000004 for delivery', '197.150.164.48', '2026-04-11 19:42:10'),
('8868dbca-36cf-11f1-9448-e66239f31cb0', '4f792c75-4a65-45a7-a0ef-d282acd83d87', 'accept', 'order', 'fefb970d-6c86-42fa-ac39-d0c6c17fe37c', 'Vendor فتح الله ماركت accepted order', '154.143.40.184', '2026-04-13 03:27:19'),
('8876ffa4-2f81-11f1-9448-e66239f31cb0', '4f792c75-4a65-45a7-a0ef-d282acd83d87', 'update_profile', 'vendor', '57e1966b-294a-11f1-9448-e66239f31cb0', 'Updated vendor profile', '197.222.117.240', '2026-04-03 17:21:21'),
('88ba72df-2fc8-11f1-9448-e66239f31cb0', '8f266f22-1d9d-11f1-b82d-97044e7d8834', 'login', 'user', '8f266f22-1d9d-11f1-b82d-97044e7d8834', 'Super_admin logged in', '197.150.173.176', '2026-04-04 01:49:35'),
('88d75f6a-3039-11f1-9448-e66239f31cb0', '8f266f22-1d9d-11f1-b82d-97044e7d8834', 'delete', 'delivery_zone', 'b7880d6b-2f0b-11f1-9448-e66239f31cb0', 'SA deleted delivery zone: test1', '154.143.143.60', '2026-04-04 15:18:29'),
('891e5c18-35cd-11f1-9448-e66239f31cb0', '4f792c75-4a65-45a7-a0ef-d282acd83d87', 'login', 'user', '4f792c75-4a65-45a7-a0ef-d282acd83d87', 'Vendor logged in', '197.150.164.48', '2026-04-11 17:40:31'),
('89aa1e2c-3526-11f1-9448-e66239f31cb0', '80c0bb8b-082d-4810-806f-0e1901d9d787', 'login', 'user', '80c0bb8b-082d-4810-806f-0e1901d9d787', 'Vendor logged in', '196.136.46.60', '2026-04-10 21:45:05'),
('8a27ee8b-1db5-11f1-b82d-97044e7d8834', '8f266f22-1d9d-11f1-b82d-97044e7d8834', 'login', 'user', '8f266f22-1d9d-11f1-b82d-97044e7d8834', 'Super_admin logged in', '2a02:4780:8:5::a', '2026-03-12 01:46:51'),
('8a5bf74d-1db5-11f1-b82d-97044e7d8834', '8f266f22-1d9d-11f1-b82d-97044e7d8834', 'refresh_token', 'user', '8f266f22-1d9d-11f1-b82d-97044e7d8834', 'Token refreshed', '2a02:4780:8:5::a', '2026-03-12 01:46:51'),
('8a81fa0e-26f2-11f1-abf9-e1b0d104a220', '8f266f22-1d9d-11f1-b82d-97044e7d8834', 'login', 'user', '8f266f22-1d9d-11f1-b82d-97044e7d8834', 'Super_admin logged in', '45.109.19.74', '2026-03-23 19:57:37'),
('8b53698b-3b55-11f1-9448-e66239f31cb0', 'd8e13ad8-2382-11f1-b82d-97044e7d8834', 'login', 'user', 'd8e13ad8-2382-11f1-b82d-97044e7d8834', 'Admin logged in', '45.109.196.109', '2026-04-18 21:36:42'),
('8b5dbc9e-3d22-11f1-9448-e66239f31cb0', 'e64dd274-3d20-11f1-9448-e66239f31cb0', 'logout', 'user', 'e64dd274-3d20-11f1-9448-e66239f31cb0', 'Admin logged out', '154.143.246.144', '2026-04-21 04:36:40'),
('8ba14c76-3b31-11f1-9448-e66239f31cb0', '8f266f22-1d9d-11f1-b82d-97044e7d8834', 'create', 'admin', '8ba11278-3b31-11f1-9448-e66239f31cb0', 'Created new admin: إبراهيم نعمان (ibrahim187shmi@gmail.com)', '158.140.85.78', '2026-04-18 17:19:00'),
('8bad1be7-238c-11f1-b82d-97044e7d8834', '8f266f22-1d9d-11f1-b82d-97044e7d8834', 'logout', 'user', '8f266f22-1d9d-11f1-b82d-97044e7d8834', 'Super_admin logged out', '156.217.99.167', '2026-03-19 12:08:31'),
('8baedbe5-343f-11f1-9448-e66239f31cb0', '8f266f22-1d9d-11f1-b82d-97044e7d8834', 'login', 'user', '8f266f22-1d9d-11f1-b82d-97044e7d8834', 'Super_admin logged in', '154.143.86.111', '2026-04-09 18:11:35'),
('8c163a51-1db5-11f1-b82d-97044e7d8834', '8f266f22-1d9d-11f1-b82d-97044e7d8834', 'login', 'user', '8f266f22-1d9d-11f1-b82d-97044e7d8834', 'Super_admin logged in', '2a02:4780:8:5::a', '2026-03-12 01:46:54'),
('8c43d8e0-1db5-11f1-b82d-97044e7d8834', '8f266f22-1d9d-11f1-b82d-97044e7d8834', 'logout', 'user', '8f266f22-1d9d-11f1-b82d-97044e7d8834', 'Super_admin logged out', '2a02:4780:8:5::a', '2026-03-12 01:46:54'),
('8cfe6739-3d98-11f1-9448-e66239f31cb0', 'e64dd274-3d20-11f1-9448-e66239f31cb0', 'login', 'user', 'e64dd274-3d20-11f1-9448-e66239f31cb0', 'Admin logged in', '154.143.246.144', '2026-04-21 18:41:23'),
('8d09ef2e-35cb-11f1-9448-e66239f31cb0', '8f266f22-1d9d-11f1-b82d-97044e7d8834', 'login', 'user', '8f266f22-1d9d-11f1-b82d-97044e7d8834', 'Super_admin logged in', '197.150.164.48', '2026-04-11 17:26:18'),
('8d5466b2-3202-11f1-9448-e66239f31cb0', '8f266f22-1d9d-11f1-b82d-97044e7d8834', 'deactivated', 'admin', '62ab7c1e-303a-11f1-9448-e66239f31cb0', 'Deactivated admin: loay', '154.143.143.60', '2026-04-06 21:49:56'),
('8dbd553f-3509-11f1-9448-e66239f31cb0', '4f792c75-4a65-45a7-a0ef-d282acd83d87', 'closed', 'vendor', '57e1966b-294a-11f1-9448-e66239f31cb0', 'Closed store: gaza', '197.223.152.7', '2026-04-10 18:17:37'),
('8dfc85c8-3d8f-11f1-9448-e66239f31cb0', '68d947ef-fcec-426a-8316-155666848619', 'logout', 'user', '68d947ef-fcec-426a-8316-155666848619', 'Vendor logged out', '154.143.246.144', '2026-04-21 17:36:59'),
('8e0190d6-3526-11f1-9448-e66239f31cb0', '80c0bb8b-082d-4810-806f-0e1901d9d787', 'logout', 'user', '80c0bb8b-082d-4810-806f-0e1901d9d787', 'Vendor logged out', '196.136.46.60', '2026-04-10 21:45:13'),
('8e37df1a-3071-11f1-9448-e66239f31cb0', '8f266f22-1d9d-11f1-b82d-97044e7d8834', 'logout', 'user', '8f266f22-1d9d-11f1-b82d-97044e7d8834', 'Super_admin logged out', '176.65.2.214', '2026-04-04 21:59:30'),
('8e657688-2f79-11f1-9448-e66239f31cb0', 'd8e13ad8-2382-11f1-b82d-97044e7d8834', 'login', 'user', 'd8e13ad8-2382-11f1-b82d-97044e7d8834', 'Admin logged in', '197.222.117.240', '2026-04-03 16:24:15'),
('8ffd6e3f-2f54-11f1-9448-e66239f31cb0', 'be9db6e5-2efe-11f1-9448-e66239f31cb0', 'logout', 'user', 'be9db6e5-2efe-11f1-9448-e66239f31cb0', 'Vendor logged out', '197.223.138.29', '2026-04-03 11:59:26'),
('9061de15-306f-11f1-9448-e66239f31cb0', '8f266f22-1d9d-11f1-b82d-97044e7d8834', 'login', 'user', '8f266f22-1d9d-11f1-b82d-97044e7d8834', 'Super_admin logged in', '154.143.143.60', '2026-04-04 21:45:14'),
('918440c3-303a-11f1-9448-e66239f31cb0', '8f266f22-1d9d-11f1-b82d-97044e7d8834', 'login', 'user', '8f266f22-1d9d-11f1-b82d-97044e7d8834', 'Super_admin logged in', '154.143.143.60', '2026-04-04 15:25:53'),
('91fbec46-2f94-11f1-9448-e66239f31cb0', '8f266f22-1d9d-11f1-b82d-97044e7d8834', 'deactivated', 'customer', 'a093003a-6eaf-42cf-92e0-f4660c0550e2', 'Deactivated customer: Test User', '197.222.117.240', '2026-04-03 19:37:37'),
('9378f934-2f94-11f1-9448-e66239f31cb0', '8f266f22-1d9d-11f1-b82d-97044e7d8834', 'activated', 'customer', 'a093003a-6eaf-42cf-92e0-f4660c0550e2', 'Activated customer: Test User', '197.222.117.240', '2026-04-03 19:37:40'),
('937f362e-3d21-11f1-9448-e66239f31cb0', '68d947ef-fcec-426a-8316-155666848619', 'logout', 'user', '68d947ef-fcec-426a-8316-155666848619', 'Vendor logged out', '154.143.246.144', '2026-04-21 04:29:44'),
('9383da38-3475-11f1-9448-e66239f31cb0', 'd8e13ad8-2382-11f1-b82d-97044e7d8834', 'logout', 'user', 'd8e13ad8-2382-11f1-b82d-97044e7d8834', 'Admin logged out', '45.109.112.29', '2026-04-10 00:38:21'),
('938ce154-294f-11f1-9448-e66239f31cb0', '4f792c75-4a65-45a7-a0ef-d282acd83d87', 'create', 'menu_item', '9341a204-294f-11f1-9448-e66239f31cb0', 'Created item: chicken', '45.109.220.74', '2026-03-26 20:08:37'),
('93af80f0-1db5-11f1-b82d-97044e7d8834', '8f266f22-1d9d-11f1-b82d-97044e7d8834', 'login', 'user', '8f266f22-1d9d-11f1-b82d-97044e7d8834', 'Super_admin logged in', '2a02:4780:8:5::a', '2026-03-12 01:47:07'),
('93ba4d17-3b31-11f1-9448-e66239f31cb0', '8f266f22-1d9d-11f1-b82d-97044e7d8834', 'deactivated', 'admin', '8ba11278-3b31-11f1-9448-e66239f31cb0', 'Deactivated admin: إبراهيم نعمان', '158.140.85.78', '2026-04-18 17:19:14'),
('93c2bf9e-1db5-11f1-b82d-97044e7d8834', '8f266f22-1d9d-11f1-b82d-97044e7d8834', 'create', 'admin', '93c29e9c-1db5-11f1-b82d-97044e7d8834', 'Created new admin: Test Admin (test_admin_014706@test.com)', '2a02:4780:8:5::a', '2026-03-12 01:47:07'),
('93c9369f-35ea-11f1-9448-e66239f31cb0', '4f792c75-4a65-45a7-a0ef-d282acd83d87', 'logout', 'user', '4f792c75-4a65-45a7-a0ef-d282acd83d87', 'Vendor logged out', '154.143.40.184', '2026-04-11 21:08:24'),
('940be309-1db5-11f1-b82d-97044e7d8834', '8f266f22-1d9d-11f1-b82d-97044e7d8834', 'logout', 'user', '8f266f22-1d9d-11f1-b82d-97044e7d8834', 'Super_admin logged out', '2a02:4780:8:5::a', '2026-03-12 01:47:07'),
('94f17779-238c-11f1-b82d-97044e7d8834', 'd8e13ad8-2382-11f1-b82d-97044e7d8834', 'login', 'user', 'd8e13ad8-2382-11f1-b82d-97044e7d8834', 'Admin logged in', '156.217.99.167', '2026-03-19 12:08:46'),
('94fb0a5d-32e5-11f1-9448-e66239f31cb0', '8f266f22-1d9d-11f1-b82d-97044e7d8834', 'login', 'user', '8f266f22-1d9d-11f1-b82d-97044e7d8834', 'Super_admin logged in', '154.143.143.60', '2026-04-08 00:55:05'),
('95031f78-303a-11f1-9448-e66239f31cb0', '8f266f22-1d9d-11f1-b82d-97044e7d8834', 'suspend', 'vendor', 'be9dd1ee-2efe-11f1-9448-e66239f31cb0', 'Suspended vendor: gaza grill', '154.143.143.60', '2026-04-04 15:25:59'),
('95c96b3b-2f04-11f1-9448-e66239f31cb0', 'd8e13ad8-2382-11f1-b82d-97044e7d8834', 'login', 'user', 'd8e13ad8-2382-11f1-b82d-97044e7d8834', 'Admin logged in', '197.223.138.29', '2026-04-03 02:26:56'),
('961d7f0e-36ce-11f1-9448-e66239f31cb0', '8f266f22-1d9d-11f1-b82d-97044e7d8834', 'logout', 'user', '8f266f22-1d9d-11f1-b82d-97044e7d8834', 'Super_admin logged out', '154.143.40.184', '2026-04-13 03:20:33'),
('9691d66b-3071-11f1-9448-e66239f31cb0', '62ab7c1e-303a-11f1-9448-e66239f31cb0', 'login', 'user', '62ab7c1e-303a-11f1-9448-e66239f31cb0', 'Admin logged in', '176.65.2.214', '2026-04-04 21:59:44'),
('96c47721-2f94-11f1-9448-e66239f31cb0', '8f266f22-1d9d-11f1-b82d-97044e7d8834', 'deactivated', 'customer', 'a093003a-6eaf-42cf-92e0-f4660c0550e2', 'Deactivated customer: Test User', '197.222.117.240', '2026-04-03 19:37:45'),
('96ff06d4-35c8-11f1-9448-e66239f31cb0', '4f792c75-4a65-45a7-a0ef-d282acd83d87', 'login', 'user', '4f792c75-4a65-45a7-a0ef-d282acd83d87', 'Vendor logged in', '197.150.164.48', '2026-04-11 17:05:06'),
('9713bacb-2f69-11f1-9448-e66239f31cb0', '8f266f22-1d9d-11f1-b82d-97044e7d8834', 'logout', 'user', '8f266f22-1d9d-11f1-b82d-97044e7d8834', 'Super_admin logged out', '197.223.138.29', '2026-04-03 14:29:57'),
('97210642-3498-11f1-9448-e66239f31cb0', '8f266f22-1d9d-11f1-b82d-97044e7d8834', 'logout', 'user', '8f266f22-1d9d-11f1-b82d-97044e7d8834', 'Super_admin logged out', '45.109.226.75', '2026-04-10 04:49:00'),
('97500c69-2392-11f1-b82d-97044e7d8834', '8f266f22-1d9d-11f1-b82d-97044e7d8834', 'logout', 'user', '8f266f22-1d9d-11f1-b82d-97044e7d8834', 'Super_admin logged out', '156.217.99.167', '2026-03-19 12:51:47'),
('97752a08-3526-11f1-9448-e66239f31cb0', '8f266f22-1d9d-11f1-b82d-97044e7d8834', 'login', 'user', '8f266f22-1d9d-11f1-b82d-97044e7d8834', 'Super_admin logged in', '196.136.46.60', '2026-04-10 21:45:29'),
('97d27dfc-2ef2-11f1-9448-e66239f31cb0', 'd8e13ad8-2382-11f1-b82d-97044e7d8834', 'login', 'user', 'd8e13ad8-2382-11f1-b82d-97044e7d8834', 'Admin logged in', '197.223.138.29', '2026-04-03 00:18:08'),
('982bdfde-2f54-11f1-9448-e66239f31cb0', '8f266f22-1d9d-11f1-b82d-97044e7d8834', 'login', 'user', '8f266f22-1d9d-11f1-b82d-97044e7d8834', 'Super_admin logged in', '197.223.138.29', '2026-04-03 11:59:40'),
('98336dad-2f94-11f1-9448-e66239f31cb0', '8f266f22-1d9d-11f1-b82d-97044e7d8834', 'activated', 'customer', 'a093003a-6eaf-42cf-92e0-f4660c0550e2', 'Activated customer: Test User', '197.222.117.240', '2026-04-03 19:37:48'),
('9857abb6-3365-11f1-9448-e66239f31cb0', '8f266f22-1d9d-11f1-b82d-97044e7d8834', 'logout', 'user', '8f266f22-1d9d-11f1-b82d-97044e7d8834', 'Super_admin logged out', '196.151.0.29', '2026-04-08 16:11:26');
INSERT INTO `activity_logs` (`id`, `user_id`, `action`, `entity_type`, `entity_id`, `description`, `ip_address`, `created_at`) VALUES
('98d03dfe-3d97-11f1-9448-e66239f31cb0', '8f266f22-1d9d-11f1-b82d-97044e7d8834', 'logout', 'user', '8f266f22-1d9d-11f1-b82d-97044e7d8834', 'Super_admin logged out', '154.143.246.144', '2026-04-21 18:34:33'),
('99089f9e-3d8f-11f1-9448-e66239f31cb0', 'e64dd274-3d20-11f1-9448-e66239f31cb0', 'login', 'user', 'e64dd274-3d20-11f1-9448-e66239f31cb0', 'Admin logged in', '154.143.246.144', '2026-04-21 17:37:18'),
('99b705c2-33ab-11f1-9448-e66239f31cb0', '8f266f22-1d9d-11f1-b82d-97044e7d8834', 'login', 'user', '8f266f22-1d9d-11f1-b82d-97044e7d8834', 'Super_admin logged in', '154.143.143.60', '2026-04-09 00:32:33'),
('99eff1fa-3d98-11f1-9448-e66239f31cb0', 'e64dd274-3d20-11f1-9448-e66239f31cb0', 'logout', 'user', 'e64dd274-3d20-11f1-9448-e66239f31cb0', 'Admin logged out', '154.143.246.144', '2026-04-21 18:41:45'),
('9a62393c-2f79-11f1-9448-e66239f31cb0', 'd8e13ad8-2382-11f1-b82d-97044e7d8834', 'update', 'delivery_zone', '8f2827b0-1d9d-11f1-b82d-97044e7d8834', 'Updated delivery zone: Zone 1 - Very Close (0 to 2 km)', '197.222.117.240', '2026-04-03 16:24:35'),
('9afbc039-3475-11f1-9448-e66239f31cb0', '4f792c75-4a65-45a7-a0ef-d282acd83d87', 'login', 'user', '4f792c75-4a65-45a7-a0ef-d282acd83d87', 'Vendor logged in', '45.109.112.29', '2026-04-10 00:38:34'),
('9b0dbbc8-345f-11f1-9448-e66239f31cb0', '4f792c75-4a65-45a7-a0ef-d282acd83d87', 'login', 'user', '4f792c75-4a65-45a7-a0ef-d282acd83d87', 'Vendor logged in', '45.109.112.29', '2026-04-09 22:01:05'),
('9b43a915-34a4-11f1-9448-e66239f31cb0', '4f792c75-4a65-45a7-a0ef-d282acd83d87', 'logout', 'user', '4f792c75-4a65-45a7-a0ef-d282acd83d87', 'Vendor logged out', '45.109.226.75', '2026-04-10 06:15:00'),
('9bb5113d-3b31-11f1-9448-e66239f31cb0', '8f266f22-1d9d-11f1-b82d-97044e7d8834', 'activated', 'admin', '8ba11278-3b31-11f1-9448-e66239f31cb0', 'Activated admin: إبراهيم نعمان', '158.140.85.78', '2026-04-18 17:19:27'),
('9c0f6e33-34a7-11f1-9448-e66239f31cb0', '8f266f22-1d9d-11f1-b82d-97044e7d8834', 'logout', 'user', '8f266f22-1d9d-11f1-b82d-97044e7d8834', 'Super_admin logged out', '45.109.226.75', '2026-04-10 06:36:30'),
('9c138036-35fa-11f1-9448-e66239f31cb0', 'be9db6e5-2efe-11f1-9448-e66239f31cb0', 'update_profile', 'vendor', 'be9dd1ee-2efe-11f1-9448-e66239f31cb0', 'Updated vendor profile', '154.143.40.184', '2026-04-11 23:03:10'),
('9c1a21c6-238c-11f1-b82d-97044e7d8834', 'd8e13ad8-2382-11f1-b82d-97044e7d8834', 'logout', 'user', 'd8e13ad8-2382-11f1-b82d-97044e7d8834', 'Admin logged out', '156.217.99.167', '2026-03-19 12:08:58'),
('9cfaee69-36ce-11f1-9448-e66239f31cb0', '4f792c75-4a65-45a7-a0ef-d282acd83d87', 'login', 'user', '4f792c75-4a65-45a7-a0ef-d282acd83d87', 'Vendor logged in', '154.143.40.184', '2026-04-13 03:20:45'),
('9db324bc-2ef2-11f1-9448-e66239f31cb0', 'd8e13ad8-2382-11f1-b82d-97044e7d8834', 'logout', 'user', 'd8e13ad8-2382-11f1-b82d-97044e7d8834', 'Admin logged out', '197.223.138.29', '2026-04-03 00:18:18'),
('9dbbc335-35cd-11f1-9448-e66239f31cb0', '4f792c75-4a65-45a7-a0ef-d282acd83d87', 'logout', 'user', '4f792c75-4a65-45a7-a0ef-d282acd83d87', 'Vendor logged out', '197.150.164.48', '2026-04-11 17:41:05'),
('9e5c62cc-35e1-11f1-9448-e66239f31cb0', 'd8e13ad8-2382-11f1-b82d-97044e7d8834', 'logout', 'user', 'd8e13ad8-2382-11f1-b82d-97044e7d8834', 'Admin logged out', '197.150.164.48', '2026-04-11 20:04:16'),
('9eb236c4-3b31-11f1-9448-e66239f31cb0', '8ba11278-3b31-11f1-9448-e66239f31cb0', 'login', 'user', '8ba11278-3b31-11f1-9448-e66239f31cb0', 'Admin logged in', '158.140.85.78', '2026-04-18 17:19:32'),
('9ee6ed53-2f94-11f1-9448-e66239f31cb0', '8f266f22-1d9d-11f1-b82d-97044e7d8834', 'deactivated', 'customer', 'ad30e781-9ca7-4f1f-825f-40b0d4d4b8c0', 'Deactivated customer: Test User', '197.222.117.240', '2026-04-03 19:37:59'),
('9eef4e60-2f69-11f1-9448-e66239f31cb0', '8f266f22-1d9d-11f1-b82d-97044e7d8834', 'login', 'user', '8f266f22-1d9d-11f1-b82d-97044e7d8834', 'Super_admin logged in', '197.223.138.29', '2026-04-03 14:30:10'),
('9f30202f-35fa-11f1-9448-e66239f31cb0', 'be9db6e5-2efe-11f1-9448-e66239f31cb0', 'update_profile', 'vendor', 'be9dd1ee-2efe-11f1-9448-e66239f31cb0', 'Updated vendor profile', '154.143.40.184', '2026-04-11 23:03:15'),
('9f3cf84a-35ea-11f1-9448-e66239f31cb0', '8f266f22-1d9d-11f1-b82d-97044e7d8834', 'login', 'user', '8f266f22-1d9d-11f1-b82d-97044e7d8834', 'Super_admin logged in', '154.143.40.184', '2026-04-11 21:08:43'),
('9f65c296-3d21-11f1-9448-e66239f31cb0', '68d947ef-fcec-426a-8316-155666848619', 'login', 'user', '68d947ef-fcec-426a-8316-155666848619', 'Vendor logged in', '154.143.246.144', '2026-04-21 04:30:04'),
('9f78e83d-303a-11f1-9448-e66239f31cb0', '8f266f22-1d9d-11f1-b82d-97044e7d8834', 'reactivate', 'vendor', '57e1966b-294a-11f1-9448-e66239f31cb0', 'Reactivated vendor: gaza', '154.143.143.60', '2026-04-04 15:26:16'),
('a0059955-2f94-11f1-9448-e66239f31cb0', '8f266f22-1d9d-11f1-b82d-97044e7d8834', 'deactivated', 'customer', 'a093003a-6eaf-42cf-92e0-f4660c0550e2', 'Deactivated customer: Test User', '197.222.117.240', '2026-04-03 19:38:01'),
('a067fc55-3d97-11f1-9448-e66239f31cb0', '6255a20b-3d97-11f1-9448-e66239f31cb0', 'login', 'user', '6255a20b-3d97-11f1-9448-e66239f31cb0', 'Vendor logged in', '154.143.246.144', '2026-04-21 18:34:46'),
('a0fc2741-2f94-11f1-9448-e66239f31cb0', '8f266f22-1d9d-11f1-b82d-97044e7d8834', 'activated', 'customer', 'a093003a-6eaf-42cf-92e0-f4660c0550e2', 'Activated customer: Test User', '197.222.117.240', '2026-04-03 19:38:02'),
('a118f7a1-303a-11f1-9448-e66239f31cb0', '8f266f22-1d9d-11f1-b82d-97044e7d8834', 'reactivate', 'vendor', 'be9dd1ee-2efe-11f1-9448-e66239f31cb0', 'Reactivated vendor: gaza grill', '154.143.143.60', '2026-04-04 15:26:19'),
('a121d2d9-36cf-11f1-9448-e66239f31cb0', '4f792c75-4a65-45a7-a0ef-d282acd83d87', 'mark_preparing', 'order', 'fefb970d-6c86-42fa-ac39-d0c6c17fe37c', 'Order being prepared', '154.143.40.184', '2026-04-13 03:28:01'),
('a1350d63-2f0b-11f1-9448-e66239f31cb0', 'd8e13ad8-2382-11f1-b82d-97044e7d8834', 'update', 'delivery_zone', 'bc140619-2f03-11f1-9448-e66239f31cb0', 'Updated delivery zone: test zone', '197.223.138.29', '2026-04-03 03:17:22'),
('a138e6af-1db5-11f1-b82d-97044e7d8834', '8f266f22-1d9d-11f1-b82d-97044e7d8834', 'login', 'user', '8f266f22-1d9d-11f1-b82d-97044e7d8834', 'Super_admin logged in', '2a02:4780:8:5::a', '2026-03-12 01:47:29'),
('a16bca4e-1db5-11f1-b82d-97044e7d8834', '8f266f22-1d9d-11f1-b82d-97044e7d8834', 'logout', 'user', '8f266f22-1d9d-11f1-b82d-97044e7d8834', 'Super_admin logged out', '2a02:4780:8:5::a', '2026-03-12 01:47:30'),
('a18b1ff1-231b-11f1-b82d-97044e7d8834', '8f266f22-1d9d-11f1-b82d-97044e7d8834', 'deactivated', 'delivery_zone', '8f2829db-1d9d-11f1-b82d-97044e7d8834', 'Deactivated delivery zone: Zone 2 - Close (2 to 5 km)', '45.104.153.29', '2026-03-18 22:40:14'),
('a1e4892e-3d98-11f1-9448-e66239f31cb0', 'f52cc98d-3d20-11f1-9448-e66239f31cb0', 'login', 'user', 'f52cc98d-3d20-11f1-9448-e66239f31cb0', 'Admin logged in', '154.143.246.144', '2026-04-21 18:41:58'),
('a1ec517a-2f94-11f1-9448-e66239f31cb0', '8f266f22-1d9d-11f1-b82d-97044e7d8834', 'activated', 'customer', 'ad30e781-9ca7-4f1f-825f-40b0d4d4b8c0', 'Activated customer: Test User', '197.222.117.240', '2026-04-03 19:38:04'),
('a1ff3511-2392-11f1-b82d-97044e7d8834', 'd8e13ad8-2382-11f1-b82d-97044e7d8834', 'login', 'user', 'd8e13ad8-2382-11f1-b82d-97044e7d8834', 'Admin logged in', '156.217.99.167', '2026-03-19 12:52:05'),
('a200a171-36ce-11f1-9448-e66239f31cb0', '4f792c75-4a65-45a7-a0ef-d282acd83d87', 'closed', 'vendor', '57e1966b-294a-11f1-9448-e66239f31cb0', 'Closed store: فتح الله ماركت', '154.143.40.184', '2026-04-13 03:20:53'),
('a2a5f7f0-3498-11f1-9448-e66239f31cb0', 'd8e13ad8-2382-11f1-b82d-97044e7d8834', 'login', 'user', 'd8e13ad8-2382-11f1-b82d-97044e7d8834', 'Admin logged in', '45.109.226.75', '2026-04-10 04:49:19'),
('a2e8e464-34a4-11f1-9448-e66239f31cb0', 'd8e13ad8-2382-11f1-b82d-97044e7d8834', 'login', 'user', 'd8e13ad8-2382-11f1-b82d-97044e7d8834', 'Admin logged in', '45.109.226.75', '2026-04-10 06:15:13'),
('a2f39dc7-36cf-11f1-9448-e66239f31cb0', '4f792c75-4a65-45a7-a0ef-d282acd83d87', 'mark_ready', 'order', 'fefb970d-6c86-42fa-ac39-d0c6c17fe37c', 'All vendors ready for pickup', '154.143.40.184', '2026-04-13 03:28:04'),
('a320fae2-2f79-11f1-9448-e66239f31cb0', 'd8e13ad8-2382-11f1-b82d-97044e7d8834', 'logout', 'user', 'd8e13ad8-2382-11f1-b82d-97044e7d8834', 'Admin logged out', '197.222.117.240', '2026-04-03 16:24:49'),
('a34b4f60-35e1-11f1-9448-e66239f31cb0', '4f792c75-4a65-45a7-a0ef-d282acd83d87', 'login', 'user', '4f792c75-4a65-45a7-a0ef-d282acd83d87', 'Vendor logged in', '197.150.164.48', '2026-04-11 20:04:24'),
('a375ae5d-35cd-11f1-9448-e66239f31cb0', 'd8e13ad8-2382-11f1-b82d-97044e7d8834', 'login', 'user', 'd8e13ad8-2382-11f1-b82d-97044e7d8834', 'Admin logged in', '197.150.164.48', '2026-04-11 17:41:15'),
('a4cd7cbe-2f81-11f1-9448-e66239f31cb0', '4f792c75-4a65-45a7-a0ef-d282acd83d87', 'logout', 'user', '4f792c75-4a65-45a7-a0ef-d282acd83d87', 'Vendor logged out', '197.222.117.240', '2026-04-03 17:22:08'),
('a59d03a2-3365-11f1-9448-e66239f31cb0', '4f792c75-4a65-45a7-a0ef-d282acd83d87', 'login', 'user', '4f792c75-4a65-45a7-a0ef-d282acd83d87', 'Vendor logged in', '196.151.0.29', '2026-04-08 16:11:48'),
('a6180d58-3d98-11f1-9448-e66239f31cb0', 'f52cc98d-3d20-11f1-9448-e66239f31cb0', 'logout', 'user', 'f52cc98d-3d20-11f1-9448-e66239f31cb0', 'Admin logged out', '154.143.246.144', '2026-04-21 18:42:05'),
('a67718f1-35cd-11f1-9448-e66239f31cb0', 'd8e13ad8-2382-11f1-b82d-97044e7d8834', 'confirm', 'order', 'a1c23979-4844-4910-b6c7-d96f1d5807af', 'Confirmed and sent order ORD-2026-000002 to vendor(s)', '197.150.164.48', '2026-04-11 17:41:20'),
('a6cb0883-31fd-11f1-9448-e66239f31cb0', '8f266f22-1d9d-11f1-b82d-97044e7d8834', 'logout', 'user', '8f266f22-1d9d-11f1-b82d-97044e7d8834', 'Super_admin logged out', '154.143.143.60', '2026-04-06 21:14:52'),
('a7ac91d7-1db5-11f1-b82d-97044e7d8834', '8f266f22-1d9d-11f1-b82d-97044e7d8834', 'login', 'user', '8f266f22-1d9d-11f1-b82d-97044e7d8834', 'Super_admin logged in', '2a02:4780:8:5::a', '2026-03-12 01:47:40'),
('a7eb0b42-1db5-11f1-b82d-97044e7d8834', '8f266f22-1d9d-11f1-b82d-97044e7d8834', 'logout', 'user', '8f266f22-1d9d-11f1-b82d-97044e7d8834', 'Super_admin logged out', '2a02:4780:8:5::a', '2026-03-12 01:47:40'),
('a7ebc6cb-3d8f-11f1-9448-e66239f31cb0', 'e64dd274-3d20-11f1-9448-e66239f31cb0', 'logout', 'user', 'e64dd274-3d20-11f1-9448-e66239f31cb0', 'Admin logged out', '154.143.246.144', '2026-04-21 17:37:43'),
('a82cbd7a-2f69-11f1-9448-e66239f31cb0', '8f266f22-1d9d-11f1-b82d-97044e7d8834', 'logout', 'user', '8f266f22-1d9d-11f1-b82d-97044e7d8834', 'Super_admin logged out', '197.223.138.29', '2026-04-03 14:30:26'),
('a8d4bf8c-2f79-11f1-9448-e66239f31cb0', '4f792c75-4a65-45a7-a0ef-d282acd83d87', 'login', 'user', '4f792c75-4a65-45a7-a0ef-d282acd83d87', 'Vendor logged in', '197.222.117.240', '2026-04-03 16:24:59'),
('a8ed31f7-347f-11f1-9448-e66239f31cb0', '4f792c75-4a65-45a7-a0ef-d282acd83d87', 'login', 'user', '4f792c75-4a65-45a7-a0ef-d282acd83d87', 'Vendor logged in', '45.109.112.29', '2026-04-10 01:50:32'),
('a994e2ba-32e5-11f1-9448-e66239f31cb0', '8f266f22-1d9d-11f1-b82d-97044e7d8834', 'logout', 'user', '8f266f22-1d9d-11f1-b82d-97044e7d8834', 'Super_admin logged out', '154.143.143.60', '2026-04-08 00:55:39'),
('a9e17b6a-36cf-11f1-9448-e66239f31cb0', '4f792c75-4a65-45a7-a0ef-d282acd83d87', 'logout', 'user', '4f792c75-4a65-45a7-a0ef-d282acd83d87', 'Vendor logged out', '154.143.40.184', '2026-04-13 03:28:16'),
('ab7fc101-31fd-11f1-9448-e66239f31cb0', '4f792c75-4a65-45a7-a0ef-d282acd83d87', 'login', 'user', '4f792c75-4a65-45a7-a0ef-d282acd83d87', 'Vendor logged in', '154.143.143.60', '2026-04-06 21:14:59'),
('ab9575e3-351c-11f1-9448-e66239f31cb0', '8f266f22-1d9d-11f1-b82d-97044e7d8834', 'logout', 'user', '8f266f22-1d9d-11f1-b82d-97044e7d8834', 'Super_admin logged out', '196.136.12.153', '2026-04-10 20:34:27'),
('abad4d40-2f8d-11f1-9448-e66239f31cb0', '4f792c75-4a65-45a7-a0ef-d282acd83d87', 'logout', 'user', '4f792c75-4a65-45a7-a0ef-d282acd83d87', 'Vendor logged out', '197.222.117.240', '2026-04-03 18:48:14'),
('abc8a20a-3d97-11f1-9448-e66239f31cb0', '6255a20b-3d97-11f1-9448-e66239f31cb0', 'create', 'menu_category', 'abc87f45-3d97-11f1-9448-e66239f31cb0', 'Subscribed to category: Food', '154.143.246.144', '2026-04-21 18:35:05'),
('abe19a6a-34a7-11f1-9448-e66239f31cb0', 'd8e13ad8-2382-11f1-b82d-97044e7d8834', 'login', 'user', 'd8e13ad8-2382-11f1-b82d-97044e7d8834', 'Admin logged in', '45.109.226.75', '2026-04-10 06:36:57'),
('ac628ee4-3d91-11f1-9448-e66239f31cb0', '8f266f22-1d9d-11f1-b82d-97044e7d8834', 'logout', 'user', '8f266f22-1d9d-11f1-b82d-97044e7d8834', 'Super_admin logged out', '154.143.246.144', '2026-04-21 17:52:09'),
('ac718d12-3071-11f1-9448-e66239f31cb0', '62ab7c1e-303a-11f1-9448-e66239f31cb0', 'logout', 'user', '62ab7c1e-303a-11f1-9448-e66239f31cb0', 'Admin logged out', '176.65.2.214', '2026-04-04 22:00:20'),
('ad2c60d2-35ea-11f1-9448-e66239f31cb0', '8f266f22-1d9d-11f1-b82d-97044e7d8834', 'update', 'advertisement', '5c703dba-2ef9-11f1-9448-e66239f31cb0', 'Updated advertisement: gaza ad', '154.143.40.184', '2026-04-11 21:09:06'),
('ad4229e4-3d98-11f1-9448-e66239f31cb0', 'e64dd274-3d20-11f1-9448-e66239f31cb0', 'login', 'user', 'e64dd274-3d20-11f1-9448-e66239f31cb0', 'Admin logged in', '154.143.246.144', '2026-04-21 18:42:17'),
('ad4869f5-3341-11f1-9448-e66239f31cb0', '8f266f22-1d9d-11f1-b82d-97044e7d8834', 'logout', 'user', '8f266f22-1d9d-11f1-b82d-97044e7d8834', 'Super_admin logged out', '196.151.0.29', '2026-04-08 11:54:19'),
('ad49f7f1-303a-11f1-9448-e66239f31cb0', '8f266f22-1d9d-11f1-b82d-97044e7d8834', 'logout', 'user', '8f266f22-1d9d-11f1-b82d-97044e7d8834', 'Super_admin logged out', '154.143.143.60', '2026-04-04 15:26:39'),
('ad66597f-3b55-11f1-9448-e66239f31cb0', '8f266f22-1d9d-11f1-b82d-97044e7d8834', 'logout', 'user', '8f266f22-1d9d-11f1-b82d-97044e7d8834', 'Super_admin logged out', '158.140.100.80', '2026-04-18 21:37:39'),
('ad80477a-35ea-11f1-9448-e66239f31cb0', '8f266f22-1d9d-11f1-b82d-97044e7d8834', 'update', 'advertisement', '5c703dba-2ef9-11f1-9448-e66239f31cb0', 'Uploaded image for advertisement', '154.143.40.184', '2026-04-11 21:09:07'),
('ad975163-3d8f-11f1-9448-e66239f31cb0', 'f52cc98d-3d20-11f1-9448-e66239f31cb0', 'login', 'user', 'f52cc98d-3d20-11f1-9448-e66239f31cb0', 'Admin logged in', '154.143.246.144', '2026-04-21 17:37:52'),
('addf6645-3d93-11f1-9448-e66239f31cb0', '8f266f22-1d9d-11f1-b82d-97044e7d8834', 'login', 'user', '8f266f22-1d9d-11f1-b82d-97044e7d8834', 'Super_admin logged in', '154.143.246.144', '2026-04-21 18:06:31'),
('ae068621-2f69-11f1-9448-e66239f31cb0', '4f792c75-4a65-45a7-a0ef-d282acd83d87', 'login', 'user', '4f792c75-4a65-45a7-a0ef-d282acd83d87', 'Vendor logged in', '197.223.138.29', '2026-04-03 14:30:36'),
('ae69067c-3509-11f1-9448-e66239f31cb0', '4f792c75-4a65-45a7-a0ef-d282acd83d87', 'logout', 'user', '4f792c75-4a65-45a7-a0ef-d282acd83d87', 'Vendor logged out', '197.223.152.7', '2026-04-10 18:18:32'),
('af188d1a-3476-11f1-9448-e66239f31cb0', 'd8e13ad8-2382-11f1-b82d-97044e7d8834', 'logout', 'user', 'd8e13ad8-2382-11f1-b82d-97044e7d8834', 'Admin logged out', '45.109.112.29', '2026-04-10 00:46:17'),
('b0099701-3d98-11f1-9448-e66239f31cb0', 'e64dd274-3d20-11f1-9448-e66239f31cb0', 'confirm', 'order', 'f5d055ec-9300-445f-af99-77077d8cc519', 'Confirmed and sent order ORD-2026-000015 to vendor(s)', '154.143.246.144', '2026-04-21 18:42:22'),
('b0340c04-38f0-11f1-9448-e66239f31cb0', '8f266f22-1d9d-11f1-b82d-97044e7d8834', 'login', 'user', '8f266f22-1d9d-11f1-b82d-97044e7d8834', 'Super_admin logged in', '197.223.100.82', '2026-04-15 20:29:42'),
('b03f58f2-3696-11f1-9448-e66239f31cb0', '8f266f22-1d9d-11f1-b82d-97044e7d8834', 'logout', 'user', '8f266f22-1d9d-11f1-b82d-97044e7d8834', 'Super_admin logged out', '154.143.40.184', '2026-04-12 20:40:25'),
('b09620f1-2f79-11f1-9448-e66239f31cb0', '4f792c75-4a65-45a7-a0ef-d282acd83d87', 'update_profile', 'vendor', '57e1966b-294a-11f1-9448-e66239f31cb0', 'Updated vendor profile', '197.222.117.240', '2026-04-03 16:25:12'),
('b0d361c0-2382-11f1-b82d-97044e7d8834', '8f266f22-1d9d-11f1-b82d-97044e7d8834', 'delete', 'admin', 'cddc797e-1e85-11f1-b82d-97044e7d8834', 'Deleted admin: admin1 (yasserashraf3142@gmail.com)', '156.217.99.167', '2026-03-19 10:57:58'),
('b0f47e5c-351c-11f1-9448-e66239f31cb0', 'd8e13ad8-2382-11f1-b82d-97044e7d8834', 'login', 'user', 'd8e13ad8-2382-11f1-b82d-97044e7d8834', 'Admin logged in', '196.136.12.153', '2026-04-10 20:34:36'),
('b1b042a4-3510-11f1-9448-e66239f31cb0', '80c0bb8b-082d-4810-806f-0e1901d9d787', 'logout', 'user', '80c0bb8b-082d-4810-806f-0e1901d9d787', 'Vendor logged out', '197.223.152.7', '2026-04-10 19:08:44'),
('b1ee05b3-36ce-11f1-9448-e66239f31cb0', '4f792c75-4a65-45a7-a0ef-d282acd83d87', 'opened', 'vendor', '57e1966b-294a-11f1-9448-e66239f31cb0', 'Opened store: فتح الله ماركت', '154.143.40.184', '2026-04-13 03:21:20'),
('b2597e89-2f81-11f1-9448-e66239f31cb0', '8f266f22-1d9d-11f1-b82d-97044e7d8834', 'login', 'user', '8f266f22-1d9d-11f1-b82d-97044e7d8834', 'Super_admin logged in', '197.222.117.240', '2026-04-03 17:22:31'),
('b2709db7-2f7a-11f1-9448-e66239f31cb0', '8f266f22-1d9d-11f1-b82d-97044e7d8834', 'logout', 'user', '8f266f22-1d9d-11f1-b82d-97044e7d8834', 'Super_admin logged out', '197.222.117.240', '2026-04-03 16:32:25'),
('b283ffb3-2f8d-11f1-9448-e66239f31cb0', '8f266f22-1d9d-11f1-b82d-97044e7d8834', 'login', 'user', '8f266f22-1d9d-11f1-b82d-97044e7d8834', 'Super_admin logged in', '197.222.117.240', '2026-04-03 18:48:25'),
('b2a5eb12-303a-11f1-9448-e66239f31cb0', '4f792c75-4a65-45a7-a0ef-d282acd83d87', 'login', 'user', '4f792c75-4a65-45a7-a0ef-d282acd83d87', 'Vendor logged in', '154.143.143.60', '2026-04-04 15:26:48'),
('b2b1b527-231c-11f1-b82d-97044e7d8834', '8f266f22-1d9d-11f1-b82d-97044e7d8834', 'login', 'user', '8f266f22-1d9d-11f1-b82d-97044e7d8834', 'Super_admin logged in', '45.104.153.29', '2026-03-18 22:47:53'),
('b2b55212-3b2f-11f1-9448-e66239f31cb0', 'd8e13ad8-2382-11f1-b82d-97044e7d8834', 'login', 'user', 'd8e13ad8-2382-11f1-b82d-97044e7d8834', 'Admin logged in', '197.222.6.108', '2026-04-18 17:05:47'),
('b2bf1125-1e85-11f1-b82d-97044e7d8834', '8f266f22-1d9d-11f1-b82d-97044e7d8834', 'login', 'user', '8f266f22-1d9d-11f1-b82d-97044e7d8834', 'Super_admin logged in', '62.114.227.105', '2026-03-13 02:36:54'),
('b2d31ebb-35ea-11f1-9448-e66239f31cb0', '8f266f22-1d9d-11f1-b82d-97044e7d8834', 'update', 'advertisement', 'f5e9cc4e-2ef6-11f1-9448-e66239f31cb0', 'Updated advertisement: add one', '154.143.40.184', '2026-04-11 21:09:16'),
('b2d85330-2fc3-11f1-9448-e66239f31cb0', '4f792c75-4a65-45a7-a0ef-d282acd83d87', 'logout', 'user', '4f792c75-4a65-45a7-a0ef-d282acd83d87', 'Vendor logged out', '197.150.173.176', '2026-04-04 01:14:59'),
('b3335715-35ea-11f1-9448-e66239f31cb0', '8f266f22-1d9d-11f1-b82d-97044e7d8834', 'update', 'advertisement', 'f5e9cc4e-2ef6-11f1-9448-e66239f31cb0', 'Uploaded image for advertisement', '154.143.40.184', '2026-04-11 21:09:17'),
('b3f66fc2-3414-11f1-9448-e66239f31cb0', '4f792c75-4a65-45a7-a0ef-d282acd83d87', 'login', 'user', '4f792c75-4a65-45a7-a0ef-d282acd83d87', 'Vendor logged in', '197.222.207.2', '2026-04-09 13:04:54'),
('b40ebbc9-36cf-11f1-9448-e66239f31cb0', 'd8e13ad8-2382-11f1-b82d-97044e7d8834', 'login', 'user', 'd8e13ad8-2382-11f1-b82d-97044e7d8834', 'Admin logged in', '154.143.40.184', '2026-04-13 03:28:33'),
('b41b466e-26f3-11f1-abf9-e1b0d104a220', '80c0bb8b-082d-4810-806f-0e1901d9d787', 'login', 'user', '80c0bb8b-082d-4810-806f-0e1901d9d787', 'Vendor logged in', '45.109.19.74', '2026-03-23 20:05:56'),
('b4a6ab46-2961-11f1-9448-e66239f31cb0', '4f792c75-4a65-45a7-a0ef-d282acd83d87', 'login', 'user', '4f792c75-4a65-45a7-a0ef-d282acd83d87', 'Vendor logged in', '45.109.220.74', '2026-03-26 22:18:24'),
('b581d043-3d98-11f1-9448-e66239f31cb0', 'e64dd274-3d20-11f1-9448-e66239f31cb0', 'logout', 'user', 'e64dd274-3d20-11f1-9448-e66239f31cb0', 'Admin logged out', '154.143.246.144', '2026-04-21 18:42:31'),
('b58e1111-3d8f-11f1-9448-e66239f31cb0', 'f52cc98d-3d20-11f1-9448-e66239f31cb0', 'logout', 'user', 'f52cc98d-3d20-11f1-9448-e66239f31cb0', 'Admin logged out', '154.143.246.144', '2026-04-21 17:38:06'),
('b6e1d524-2fc2-11f1-9448-e66239f31cb0', '8f266f22-1d9d-11f1-b82d-97044e7d8834', 'login', 'user', '8f266f22-1d9d-11f1-b82d-97044e7d8834', 'Super_admin logged in', '197.150.173.176', '2026-04-04 01:07:56'),
('b6f15601-3475-11f1-9448-e66239f31cb0', '4f792c75-4a65-45a7-a0ef-d282acd83d87', 'logout', 'user', '4f792c75-4a65-45a7-a0ef-d282acd83d87', 'Vendor logged out', '45.109.112.29', '2026-04-10 00:39:21'),
('b7882f22-2f0b-11f1-9448-e66239f31cb0', 'd8e13ad8-2382-11f1-b82d-97044e7d8834', 'create', 'delivery_zone', 'b7880d6b-2f0b-11f1-9448-e66239f31cb0', 'Created delivery zone: test1', '197.223.138.29', '2026-04-03 03:17:59'),
('b8356d87-231c-11f1-b82d-97044e7d8834', '8f266f22-1d9d-11f1-b82d-97044e7d8834', 'approve', 'vendor', '95a07fca-231c-11f1-b82d-97044e7d8834', 'Approved vendor: mystore', '45.104.153.29', '2026-03-18 22:48:02'),
('b8bc6ad3-3d21-11f1-9448-e66239f31cb0', '68d947ef-fcec-426a-8316-155666848619', 'create', 'menu_category', 'b8bc560a-3d21-11f1-9448-e66239f31cb0', 'Subscribed to category: Food', '154.143.246.144', '2026-04-21 04:30:46'),
('b8e52d12-3341-11f1-9448-e66239f31cb0', '4f792c75-4a65-45a7-a0ef-d282acd83d87', 'login', 'user', '4f792c75-4a65-45a7-a0ef-d282acd83d87', 'Vendor logged in', '196.151.0.29', '2026-04-08 11:54:39'),
('b90f007d-2ef2-11f1-9448-e66239f31cb0', '8f266f22-1d9d-11f1-b82d-97044e7d8834', 'login', 'user', '8f266f22-1d9d-11f1-b82d-97044e7d8834', 'Super_admin logged in', '197.223.138.29', '2026-04-03 00:19:04'),
('b98c5205-3456-11f1-9448-e66239f31cb0', 'd8e13ad8-2382-11f1-b82d-97044e7d8834', 'logout', 'user', 'd8e13ad8-2382-11f1-b82d-97044e7d8834', 'Admin logged out', '45.109.112.29', '2026-04-09 20:57:31'),
('b99c7f07-3b55-11f1-9448-e66239f31cb0', 'd8e13ad8-2382-11f1-b82d-97044e7d8834', 'login', 'user', 'd8e13ad8-2382-11f1-b82d-97044e7d8834', 'Admin logged in', '158.140.100.80', '2026-04-18 21:37:59'),
('b9ed4c56-32e5-11f1-9448-e66239f31cb0', '4f792c75-4a65-45a7-a0ef-d282acd83d87', 'login', 'user', '4f792c75-4a65-45a7-a0ef-d282acd83d87', 'Vendor logged in', '154.143.143.60', '2026-04-08 00:56:07'),
('baca94ca-2f69-11f1-9448-e66239f31cb0', '4f792c75-4a65-45a7-a0ef-d282acd83d87', 'opened', 'vendor', '57e1966b-294a-11f1-9448-e66239f31cb0', 'Opened store: gaza', '197.223.138.29', '2026-04-03 14:30:57'),
('bb3a3b5a-3d8f-11f1-9448-e66239f31cb0', 'e64dd274-3d20-11f1-9448-e66239f31cb0', 'login', 'user', 'e64dd274-3d20-11f1-9448-e66239f31cb0', 'Admin logged in', '154.143.246.144', '2026-04-21 17:38:15'),
('bb4e1972-35cd-11f1-9448-e66239f31cb0', 'd8e13ad8-2382-11f1-b82d-97044e7d8834', 'logout', 'user', 'd8e13ad8-2382-11f1-b82d-97044e7d8834', 'Admin logged out', '197.150.164.48', '2026-04-11 17:41:55'),
('bc141de2-2f03-11f1-9448-e66239f31cb0', '8f266f22-1d9d-11f1-b82d-97044e7d8834', 'create', 'delivery_zone', 'bc140619-2f03-11f1-9448-e66239f31cb0', 'SA created delivery zone: test zone', '197.223.138.29', '2026-04-03 02:20:51'),
('bc244554-2fc4-11f1-9448-e66239f31cb0', 'd8e13ad8-2382-11f1-b82d-97044e7d8834', 'logout', 'user', 'd8e13ad8-2382-11f1-b82d-97044e7d8834', 'Admin logged out', '197.150.173.176', '2026-04-04 01:22:24'),
('bc56dbe8-26f2-11f1-abf9-e1b0d104a220', '8f266f22-1d9d-11f1-b82d-97044e7d8834', 'logout', 'user', '8f266f22-1d9d-11f1-b82d-97044e7d8834', 'Super_admin logged out', '45.109.19.74', '2026-03-23 19:59:00'),
('bd142764-38f0-11f1-9448-e66239f31cb0', '8f266f22-1d9d-11f1-b82d-97044e7d8834', 'logout', 'user', '8f266f22-1d9d-11f1-b82d-97044e7d8834', 'Super_admin logged out', '197.223.100.82', '2026-04-15 20:30:04'),
('bd7c73a6-2fc3-11f1-9448-e66239f31cb0', 'd8e13ad8-2382-11f1-b82d-97044e7d8834', 'login', 'user', 'd8e13ad8-2382-11f1-b82d-97044e7d8834', 'Admin logged in', '197.150.173.176', '2026-04-04 01:15:16'),
('bdb786a4-32bf-11f1-9448-e66239f31cb0', '8f266f22-1d9d-11f1-b82d-97044e7d8834', 'login', 'user', '8f266f22-1d9d-11f1-b82d-97044e7d8834', 'Super_admin logged in', '154.143.143.60', '2026-04-07 20:24:12'),
('be087e57-3510-11f1-9448-e66239f31cb0', 'b8175b08-34e6-4f5d-87d3-4c777677db33', 'login', 'user', 'b8175b08-34e6-4f5d-87d3-4c777677db33', 'Vendor logged in', '197.223.152.7', '2026-04-10 19:09:04'),
('be411e50-3475-11f1-9448-e66239f31cb0', 'd8e13ad8-2382-11f1-b82d-97044e7d8834', 'login', 'user', 'd8e13ad8-2382-11f1-b82d-97044e7d8834', 'Admin logged in', '45.109.112.29', '2026-04-10 00:39:33'),
('bef394cb-306a-11f1-9448-e66239f31cb0', '8f266f22-1d9d-11f1-b82d-97044e7d8834', 'login', 'user', '8f266f22-1d9d-11f1-b82d-97044e7d8834', 'Super_admin logged in', '154.143.143.60', '2026-04-04 21:10:45'),
('bf164d72-3476-11f1-9448-e66239f31cb0', '8f266f22-1d9d-11f1-b82d-97044e7d8834', 'login', 'user', '8f266f22-1d9d-11f1-b82d-97044e7d8834', 'Super_admin logged in', '45.109.112.29', '2026-04-10 00:46:44'),
('bf20bfe6-3d20-11f1-9448-e66239f31cb0', '8f266f22-1d9d-11f1-b82d-97044e7d8834', 'login', 'user', '8f266f22-1d9d-11f1-b82d-97044e7d8834', 'Super_admin logged in', '154.143.246.144', '2026-04-21 04:23:48'),
('bf2346b8-3696-11f1-9448-e66239f31cb0', 'd8e13ad8-2382-11f1-b82d-97044e7d8834', 'login', 'user', 'd8e13ad8-2382-11f1-b82d-97044e7d8834', 'Admin logged in', '154.143.40.184', '2026-04-12 20:40:50'),
('bf6719e3-3d8f-11f1-9448-e66239f31cb0', 'e64dd274-3d20-11f1-9448-e66239f31cb0', 'confirm', 'order', '8f957033-af0e-4c09-a600-74344e7b1485', 'Confirmed and sent order ORD-2026-000014 to vendor(s)', '154.143.246.144', '2026-04-21 17:38:22'),
('bfd73d7f-2f7a-11f1-9448-e66239f31cb0', 'd8e13ad8-2382-11f1-b82d-97044e7d8834', 'login', 'user', 'd8e13ad8-2382-11f1-b82d-97044e7d8834', 'Admin logged in', '197.222.117.240', '2026-04-03 16:32:47'),
('c07c7450-35cd-11f1-9448-e66239f31cb0', '4f792c75-4a65-45a7-a0ef-d282acd83d87', 'login', 'user', '4f792c75-4a65-45a7-a0ef-d282acd83d87', 'Vendor logged in', '197.150.164.48', '2026-04-11 17:42:03'),
('c1a23875-3d98-11f1-9448-e66239f31cb0', '68d947ef-fcec-426a-8316-155666848619', 'login', 'user', '68d947ef-fcec-426a-8316-155666848619', 'Vendor logged in', '154.143.246.144', '2026-04-21 18:42:51'),
('c202e0d5-32e5-11f1-9448-e66239f31cb0', '4f792c75-4a65-45a7-a0ef-d282acd83d87', 'logout', 'user', '4f792c75-4a65-45a7-a0ef-d282acd83d87', 'Vendor logged out', '154.143.143.60', '2026-04-08 00:56:20'),
('c20eed93-32bf-11f1-9448-e66239f31cb0', '8f266f22-1d9d-11f1-b82d-97044e7d8834', 'logout', 'user', '8f266f22-1d9d-11f1-b82d-97044e7d8834', 'Super_admin logged out', '154.143.143.60', '2026-04-07 20:24:20'),
('c28b5b9f-3071-11f1-9448-e66239f31cb0', '4f792c75-4a65-45a7-a0ef-d282acd83d87', 'login', 'user', '4f792c75-4a65-45a7-a0ef-d282acd83d87', 'Vendor logged in', '176.65.2.214', '2026-04-04 22:00:57'),
('c2d6bc20-2f96-11f1-9448-e66239f31cb0', '8f266f22-1d9d-11f1-b82d-97044e7d8834', 'deactivated', 'customer', 'a093003a-6eaf-42cf-92e0-f4660c0550e2', 'Deactivated customer: Test User', '197.222.117.240', '2026-04-03 19:53:18'),
('c2e0a957-2ef1-11f1-9448-e66239f31cb0', '8f266f22-1d9d-11f1-b82d-97044e7d8834', 'login', 'user', '8f266f22-1d9d-11f1-b82d-97044e7d8834', 'Super_admin logged in', '197.223.138.29', '2026-04-03 00:12:11'),
('c2e4bffa-3d8f-11f1-9448-e66239f31cb0', 'e64dd274-3d20-11f1-9448-e66239f31cb0', 'logout', 'user', 'e64dd274-3d20-11f1-9448-e66239f31cb0', 'Admin logged out', '154.143.246.144', '2026-04-21 17:38:28'),
('c36632f2-3526-11f1-9448-e66239f31cb0', '8f266f22-1d9d-11f1-b82d-97044e7d8834', 'logout', 'user', '8f266f22-1d9d-11f1-b82d-97044e7d8834', 'Super_admin logged out', '196.136.46.60', '2026-04-10 21:46:42'),
('c3afc0d4-3509-11f1-9448-e66239f31cb0', '8f266f22-1d9d-11f1-b82d-97044e7d8834', 'login', 'user', '8f266f22-1d9d-11f1-b82d-97044e7d8834', 'Super_admin logged in', '197.223.152.7', '2026-04-10 18:19:07'),
('c418b1d9-34eb-11f1-9448-e66239f31cb0', 'd8e13ad8-2382-11f1-b82d-97044e7d8834', 'logout', 'user', 'd8e13ad8-2382-11f1-b82d-97044e7d8834', 'Admin logged out', '45.109.226.75', '2026-04-10 14:44:23'),
('c4bb3150-3d98-11f1-9448-e66239f31cb0', '68d947ef-fcec-426a-8316-155666848619', 'accept', 'order', 'f5d055ec-9300-445f-af99-77077d8cc519', 'Vendor North food accepted order', '154.143.246.144', '2026-04-21 18:42:57'),
('c52c4cfb-35cb-11f1-9448-e66239f31cb0', '8f266f22-1d9d-11f1-b82d-97044e7d8834', 'update', 'advertisement', 'f5e9cc4e-2ef6-11f1-9448-e66239f31cb0', 'Updated advertisement: add one', '197.150.164.48', '2026-04-11 17:27:52'),
('c52f1dd0-2f96-11f1-9448-e66239f31cb0', '8f266f22-1d9d-11f1-b82d-97044e7d8834', 'activated', 'customer', 'a093003a-6eaf-42cf-92e0-f4660c0550e2', 'Activated customer: Test User', '197.222.117.240', '2026-04-03 19:53:22'),
('c556de7d-2efa-11f1-9448-e66239f31cb0', '8f266f22-1d9d-11f1-b82d-97044e7d8834', 'suspend', 'vendor', '57e1966b-294a-11f1-9448-e66239f31cb0', 'Suspended vendor: gaza', '197.223.138.29', '2026-04-03 01:16:41'),
('c586b7b6-35cb-11f1-9448-e66239f31cb0', '8f266f22-1d9d-11f1-b82d-97044e7d8834', 'update', 'advertisement', 'f5e9cc4e-2ef6-11f1-9448-e66239f31cb0', 'Uploaded image for advertisement', '197.150.164.48', '2026-04-11 17:27:53'),
('c5d8b63b-31fd-11f1-9448-e66239f31cb0', '4f792c75-4a65-45a7-a0ef-d282acd83d87', 'logout', 'user', '4f792c75-4a65-45a7-a0ef-d282acd83d87', 'Vendor logged out', '154.143.143.60', '2026-04-06 21:15:44'),
('c6233d6e-35ca-11f1-9448-e66239f31cb0', 'cf2239d2-2dd7-4116-abd3-1f24d48e8fe7', 'delete', 'menu_item', 'f1060001-0001-4000-a000-000000000001', 'Deleted item: Gift Wrapping Service', '197.150.164.48', '2026-04-11 17:20:44'),
('c630f7f8-2efa-11f1-9448-e66239f31cb0', '8f266f22-1d9d-11f1-b82d-97044e7d8834', 'reactivate', 'vendor', '57e1966b-294a-11f1-9448-e66239f31cb0', 'Reactivated vendor: gaza', '197.223.138.29', '2026-04-03 01:16:42'),
('c68f40f6-2f69-11f1-9448-e66239f31cb0', '4f792c75-4a65-45a7-a0ef-d282acd83d87', 'logout', 'user', '4f792c75-4a65-45a7-a0ef-d282acd83d87', 'Vendor logged out', '197.223.138.29', '2026-04-03 14:31:17'),
('c722e6c9-351c-11f1-9448-e66239f31cb0', 'd8e13ad8-2382-11f1-b82d-97044e7d8834', 'logout', 'user', 'd8e13ad8-2382-11f1-b82d-97044e7d8834', 'Admin logged out', '196.136.12.153', '2026-04-10 20:35:14'),
('c745580d-3466-11f1-9448-e66239f31cb0', 'd8e13ad8-2382-11f1-b82d-97044e7d8834', 'login', 'user', 'd8e13ad8-2382-11f1-b82d-97044e7d8834', 'Admin logged in', '45.109.112.29', '2026-04-09 22:52:25'),
('c76716c5-3b2f-11f1-9448-e66239f31cb0', 'd8e13ad8-2382-11f1-b82d-97044e7d8834', 'logout', 'user', 'd8e13ad8-2382-11f1-b82d-97044e7d8834', 'Admin logged out', '197.222.6.108', '2026-04-18 17:06:22'),
('c7f2e8f9-26d8-11f1-abf9-e1b0d104a220', '80c0bb8b-082d-4810-806f-0e1901d9d787', 'login', 'user', '80c0bb8b-082d-4810-806f-0e1901d9d787', 'Vendor logged in', '45.109.19.74', '2026-03-23 16:53:13'),
('c80a2035-3d97-11f1-9448-e66239f31cb0', '6255a20b-3d97-11f1-9448-e66239f31cb0', 'create', 'menu_item', 'c809eec0-3d97-11f1-9448-e66239f31cb0', 'Created item: meat', '154.143.246.144', '2026-04-21 18:35:53'),
('c8478bc1-2f7c-11f1-9448-e66239f31cb0', '8f266f22-1d9d-11f1-b82d-97044e7d8834', 'update', 'delivery_zone', 'b7880d6b-2f0b-11f1-9448-e66239f31cb0', 'SA updated delivery zone: test1', '197.222.117.240', '2026-04-03 16:47:20'),
('c86b3754-2f7a-11f1-9448-e66239f31cb0', 'd8e13ad8-2382-11f1-b82d-97044e7d8834', 'update', 'delivery_zone', '8f2827b0-1d9d-11f1-b82d-97044e7d8834', 'Updated delivery zone: Zone 1 - Very Close (0 to 2 km)', '197.222.117.240', '2026-04-03 16:33:02'),
('c86fc54b-2f81-11f1-9448-e66239f31cb0', '8f266f22-1d9d-11f1-b82d-97044e7d8834', 'update', 'delivery_zone', 'b7880d6b-2f0b-11f1-9448-e66239f31cb0', 'SA updated delivery zone: test1', '197.222.117.240', '2026-04-03 17:23:08'),
('c8d660db-35cd-11f1-9448-e66239f31cb0', '4f792c75-4a65-45a7-a0ef-d282acd83d87', 'accept', 'order', 'a1c23979-4844-4910-b6c7-d96f1d5807af', 'Vendor فتح الله ماركت accepted order', '197.150.164.48', '2026-04-11 17:42:17'),
('c9e14b49-3d98-11f1-9448-e66239f31cb0', '68d947ef-fcec-426a-8316-155666848619', 'mark_preparing', 'order', 'f5d055ec-9300-445f-af99-77077d8cc519', 'Order being prepared', '154.143.246.144', '2026-04-21 18:43:05'),
('ca632589-343f-11f1-9448-e66239f31cb0', '8f266f22-1d9d-11f1-b82d-97044e7d8834', 'logout', 'user', '8f266f22-1d9d-11f1-b82d-97044e7d8834', 'Super_admin logged out', '154.143.86.111', '2026-04-09 18:13:20'),
('caaa645f-35e0-11f1-9448-e66239f31cb0', 'd8e13ad8-2382-11f1-b82d-97044e7d8834', 'deliver', 'order', '1e8023a6-a8d0-42d5-b4bb-4c5317631166', 'Marked order ORD-2026-000004 as delivered', '197.150.164.48', '2026-04-11 19:58:21'),
('cac4ccbc-2efe-11f1-9448-e66239f31cb0', '8f266f22-1d9d-11f1-b82d-97044e7d8834', 'logout', 'user', '8f266f22-1d9d-11f1-b82d-97044e7d8834', 'Super_admin logged out', '197.223.138.29', '2026-04-03 01:45:28'),
('caee2214-238d-11f1-b82d-97044e7d8834', '8f266f22-1d9d-11f1-b82d-97044e7d8834', 'change_password', 'user', '8f266f22-1d9d-11f1-b82d-97044e7d8834', 'Password changed via profile', '156.217.99.167', '2026-03-19 12:17:26'),
('cb5ab6ca-36cf-11f1-9448-e66239f31cb0', 'd8e13ad8-2382-11f1-b82d-97044e7d8834', 'dispatch', 'order', 'fefb970d-6c86-42fa-ac39-d0c6c17fe37c', 'Dispatched order ORD-2026-000008 for delivery', '154.143.40.184', '2026-04-13 03:29:12'),
('cb719abb-3d98-11f1-9448-e66239f31cb0', '68d947ef-fcec-426a-8316-155666848619', 'mark_ready', 'order', 'f5d055ec-9300-445f-af99-77077d8cc519', 'North food ready, waiting for other vendors', '154.143.246.144', '2026-04-21 18:43:08'),
('cb965834-3474-11f1-9448-e66239f31cb0', '8f266f22-1d9d-11f1-b82d-97044e7d8834', 'logout', 'user', '8f266f22-1d9d-11f1-b82d-97044e7d8834', 'Super_admin logged out', '45.109.112.29', '2026-04-10 00:32:46'),
('cba3dc18-3526-11f1-9448-e66239f31cb0', 'cf2239d2-2dd7-4116-abd3-1f24d48e8fe7', 'login', 'user', 'cf2239d2-2dd7-4116-abd3-1f24d48e8fe7', 'Vendor logged in', '196.136.46.60', '2026-04-10 21:46:56'),
('cc488975-3511-11f1-9448-e66239f31cb0', 'be9db6e5-2efe-11f1-9448-e66239f31cb0', 'logout', 'user', 'be9db6e5-2efe-11f1-9448-e66239f31cb0', 'Vendor logged out', '197.223.152.7', '2026-04-10 19:16:38'),
('cdb487a0-339e-11f1-9448-e66239f31cb0', '4f792c75-4a65-45a7-a0ef-d282acd83d87', 'logout', 'user', '4f792c75-4a65-45a7-a0ef-d282acd83d87', 'Vendor logged out', '154.143.143.60', '2026-04-08 23:00:57'),
('cdc417de-2ef1-11f1-9448-e66239f31cb0', '8f266f22-1d9d-11f1-b82d-97044e7d8834', 'deactivated', 'admin', '5920fb61-26f4-11f1-abf9-e1b0d104a220', 'Deactivated admin: loay', '197.223.138.29', '2026-04-03 00:12:29'),
('cddcb73c-1e85-11f1-b82d-97044e7d8834', '8f266f22-1d9d-11f1-b82d-97044e7d8834', 'create', 'admin', 'cddc797e-1e85-11f1-b82d-97044e7d8834', 'Created new admin: admin1 (yasserashraf3142@gmail.com)', '62.114.227.105', '2026-03-13 02:37:39'),
('cdeedb82-36cf-11f1-9448-e66239f31cb0', 'd8e13ad8-2382-11f1-b82d-97044e7d8834', 'deliver', 'order', 'fefb970d-6c86-42fa-ac39-d0c6c17fe37c', 'Marked order ORD-2026-000008 as delivered', '154.143.40.184', '2026-04-13 03:29:16'),
('cdf7c350-35de-11f1-9448-e66239f31cb0', 'd8e13ad8-2382-11f1-b82d-97044e7d8834', 'dispatch', 'order', 'a1c23979-4844-4910-b6c7-d96f1d5807af', 'Dispatched order ORD-2026-000002 for delivery', '197.150.164.48', '2026-04-11 19:44:07'),
('ce39fe4a-34eb-11f1-9448-e66239f31cb0', '4f792c75-4a65-45a7-a0ef-d282acd83d87', 'login', 'user', '4f792c75-4a65-45a7-a0ef-d282acd83d87', 'Vendor logged in', '45.109.226.75', '2026-04-10 14:44:40'),
('ce66354d-3038-11f1-9448-e66239f31cb0', '8f266f22-1d9d-11f1-b82d-97044e7d8834', 'suspend', 'vendor', '57e1966b-294a-11f1-9448-e66239f31cb0', 'Suspended vendor: gaza', '154.143.143.60', '2026-04-04 15:13:16'),
('ce9e3c72-2f69-11f1-9448-e66239f31cb0', '8f266f22-1d9d-11f1-b82d-97044e7d8834', 'login', 'user', '8f266f22-1d9d-11f1-b82d-97044e7d8834', 'Super_admin logged in', '197.223.138.29', '2026-04-03 14:31:30'),
('ceb0fce1-2f0a-11f1-9448-e66239f31cb0', 'd8e13ad8-2382-11f1-b82d-97044e7d8834', 'logout', 'user', 'd8e13ad8-2382-11f1-b82d-97044e7d8834', 'Admin logged out', '197.223.138.29', '2026-04-03 03:11:28'),
('cec29d90-35e0-11f1-9448-e66239f31cb0', 'd8e13ad8-2382-11f1-b82d-97044e7d8834', 'deliver', 'order', 'a1c23979-4844-4910-b6c7-d96f1d5807af', 'Marked order ORD-2026-000002 as delivered', '197.150.164.48', '2026-04-11 19:58:28'),
('cf05c165-2f81-11f1-9448-e66239f31cb0', '8f266f22-1d9d-11f1-b82d-97044e7d8834', 'update', 'delivery_zone', '8f2829db-1d9d-11f1-b82d-97044e7d8834', 'SA updated delivery zone: Zone 2 - Close (2 to 5 km)', '197.222.117.240', '2026-04-03 17:23:19'),
('cf2a1f68-2ef1-11f1-9448-e66239f31cb0', '8f266f22-1d9d-11f1-b82d-97044e7d8834', 'activated', 'admin', '5920fb61-26f4-11f1-abf9-e1b0d104a220', 'Activated admin: loay', '197.223.138.29', '2026-04-03 00:12:32'),
('cffae9ce-2f79-11f1-9448-e66239f31cb0', '4f792c75-4a65-45a7-a0ef-d282acd83d87', 'logout', 'user', '4f792c75-4a65-45a7-a0ef-d282acd83d87', 'Vendor logged out', '197.222.117.240', '2026-04-03 16:26:05'),
('d01c2307-2ef1-11f1-9448-e66239f31cb0', '8f266f22-1d9d-11f1-b82d-97044e7d8834', 'deactivated', 'admin', 'd8e13ad8-2382-11f1-b82d-97044e7d8834', 'Deactivated admin: admin1', '197.223.138.29', '2026-04-03 00:12:33'),
('d10270aa-36ce-11f1-9448-e66239f31cb0', '4f792c75-4a65-45a7-a0ef-d282acd83d87', 'update_profile', 'vendor', '57e1966b-294a-11f1-9448-e66239f31cb0', 'Updated vendor profile', '154.143.40.184', '2026-04-13 03:22:12'),
('d10ce724-35cb-11f1-9448-e66239f31cb0', '8f266f22-1d9d-11f1-b82d-97044e7d8834', 'update', 'advertisement', '5c703dba-2ef9-11f1-9448-e66239f31cb0', 'Updated advertisement: gaza ad', '197.150.164.48', '2026-04-11 17:28:12'),
('d10fa976-2ef1-11f1-9448-e66239f31cb0', '8f266f22-1d9d-11f1-b82d-97044e7d8834', 'activated', 'admin', 'd8e13ad8-2382-11f1-b82d-97044e7d8834', 'Activated admin: admin1', '197.223.138.29', '2026-04-03 00:12:35'),
('d134ad21-33ab-11f1-9448-e66239f31cb0', '8f266f22-1d9d-11f1-b82d-97044e7d8834', 'logout', 'user', '8f266f22-1d9d-11f1-b82d-97044e7d8834', 'Super_admin logged out', '154.143.143.60', '2026-04-09 00:34:06'),
('d152a91d-31fd-11f1-9448-e66239f31cb0', '8f266f22-1d9d-11f1-b82d-97044e7d8834', 'login', 'user', '8f266f22-1d9d-11f1-b82d-97044e7d8834', 'Super_admin logged in', '154.143.143.60', '2026-04-06 21:16:03'),
('d170594f-35cb-11f1-9448-e66239f31cb0', '8f266f22-1d9d-11f1-b82d-97044e7d8834', 'update', 'advertisement', '5c703dba-2ef9-11f1-9448-e66239f31cb0', 'Uploaded image for advertisement', '197.150.164.48', '2026-04-11 17:28:13'),
('d1731984-3d98-11f1-9448-e66239f31cb0', '68d947ef-fcec-426a-8316-155666848619', 'logout', 'user', '68d947ef-fcec-426a-8316-155666848619', 'Vendor logged out', '154.143.246.144', '2026-04-21 18:43:18'),
('d18e08b6-343f-11f1-9448-e66239f31cb0', 'd8e13ad8-2382-11f1-b82d-97044e7d8834', 'login', 'user', 'd8e13ad8-2382-11f1-b82d-97044e7d8834', 'Admin logged in', '154.143.86.111', '2026-04-09 18:13:32'),
('d2536b34-3d22-11f1-9448-e66239f31cb0', '8f266f22-1d9d-11f1-b82d-97044e7d8834', 'login', 'user', '8f266f22-1d9d-11f1-b82d-97044e7d8834', 'Super_admin logged in', '154.143.246.144', '2026-04-21 04:38:39'),
('d26e0d90-343c-11f1-9448-e66239f31cb0', '4f792c75-4a65-45a7-a0ef-d282acd83d87', 'logout', 'user', '4f792c75-4a65-45a7-a0ef-d282acd83d87', 'Vendor logged out', '154.143.86.111', '2026-04-09 17:52:05'),
('d2838162-3474-11f1-9448-e66239f31cb0', 'd8e13ad8-2382-11f1-b82d-97044e7d8834', 'login', 'user', 'd8e13ad8-2382-11f1-b82d-97044e7d8834', 'Admin logged in', '45.109.112.29', '2026-04-10 00:32:57'),
('d3634f6e-3b2f-11f1-9448-e66239f31cb0', '4f792c75-4a65-45a7-a0ef-d282acd83d87', 'login', 'user', '4f792c75-4a65-45a7-a0ef-d282acd83d87', 'Vendor logged in', '197.222.6.108', '2026-04-18 17:06:42'),
('d4368135-3511-11f1-9448-e66239f31cb0', 'cf2239d2-2dd7-4116-abd3-1f24d48e8fe7', 'login', 'user', 'cf2239d2-2dd7-4116-abd3-1f24d48e8fe7', 'Vendor logged in', '197.223.152.7', '2026-04-10 19:16:51'),
('d4580df2-3696-11f1-9448-e66239f31cb0', 'd8e13ad8-2382-11f1-b82d-97044e7d8834', 'confirm', 'order', '7175c222-70ed-49bf-929d-11fb434f107a', 'Confirmed and sent order ORD-2026-000005 to vendor(s)', '154.143.40.184', '2026-04-12 20:41:26'),
('d6846c7f-2f54-11f1-9448-e66239f31cb0', '8f266f22-1d9d-11f1-b82d-97044e7d8834', 'logout', 'user', '8f266f22-1d9d-11f1-b82d-97044e7d8834', 'Super_admin logged out', '197.223.138.29', '2026-04-03 12:01:24'),
('d6e83be2-3456-11f1-9448-e66239f31cb0', '8f266f22-1d9d-11f1-b82d-97044e7d8834', 'login', 'user', '8f266f22-1d9d-11f1-b82d-97044e7d8834', 'Super_admin logged in', '45.109.112.29', '2026-04-09 20:58:20'),
('d70780ec-2f0b-11f1-9448-e66239f31cb0', 'd8e13ad8-2382-11f1-b82d-97044e7d8834', 'logout', 'user', 'd8e13ad8-2382-11f1-b82d-97044e7d8834', 'Admin logged out', '197.223.138.29', '2026-04-03 03:18:52'),
('d745154d-32bf-11f1-9448-e66239f31cb0', '4f792c75-4a65-45a7-a0ef-d282acd83d87', 'login', 'user', '4f792c75-4a65-45a7-a0ef-d282acd83d87', 'Vendor logged in', '154.143.143.60', '2026-04-07 20:24:55'),
('d79be641-2f79-11f1-9448-e66239f31cb0', '8f266f22-1d9d-11f1-b82d-97044e7d8834', 'login', 'user', '8f266f22-1d9d-11f1-b82d-97044e7d8834', 'Super_admin logged in', '197.222.117.240', '2026-04-03 16:26:18'),
('d7dddf85-2392-11f1-b82d-97044e7d8834', 'd8e13ad8-2382-11f1-b82d-97044e7d8834', 'logout', 'user', 'd8e13ad8-2382-11f1-b82d-97044e7d8834', 'Admin logged out', '156.217.99.167', '2026-03-19 12:53:35'),
('d8192588-2f0a-11f1-9448-e66239f31cb0', '8f266f22-1d9d-11f1-b82d-97044e7d8834', 'login', 'user', '8f266f22-1d9d-11f1-b82d-97044e7d8834', 'Super_admin logged in', '197.223.138.29', '2026-04-03 03:11:44'),
('d8e15e03-2382-11f1-b82d-97044e7d8834', '8f266f22-1d9d-11f1-b82d-97044e7d8834', 'create', 'admin', 'd8e13ad8-2382-11f1-b82d-97044e7d8834', 'Created new admin: admin1 (gaberyasser555@gmail.com)', '156.217.99.167', '2026-03-19 10:59:05'),
('d9517bef-238c-11f1-b82d-97044e7d8834', '8f266f22-1d9d-11f1-b82d-97044e7d8834', 'login', 'user', '8f266f22-1d9d-11f1-b82d-97044e7d8834', 'Super_admin logged in', '156.217.99.167', '2026-03-19 12:10:41'),
('d97ed2b2-2ef1-11f1-9448-e66239f31cb0', '8f266f22-1d9d-11f1-b82d-97044e7d8834', 'delete', 'admin', '93c29e9c-1db5-11f1-b82d-97044e7d8834', 'Deleted admin: Test Admin (test_admin_014706@test.com)', '197.223.138.29', '2026-04-03 00:12:49'),
('d9bcfd28-2f7a-11f1-9448-e66239f31cb0', 'd8e13ad8-2382-11f1-b82d-97044e7d8834', 'logout', 'user', 'd8e13ad8-2382-11f1-b82d-97044e7d8834', 'Admin logged out', '197.222.117.240', '2026-04-03 16:33:31'),
('d9d41454-3d21-11f1-9448-e66239f31cb0', '68d947ef-fcec-426a-8316-155666848619', 'create', 'menu_item', 'd9d3d650-3d21-11f1-9448-e66239f31cb0', 'Created item: grild chicken', '154.143.246.144', '2026-04-21 04:31:42'),
('d9f54955-350e-11f1-9448-e66239f31cb0', '4f792c75-4a65-45a7-a0ef-d282acd83d87', 'logout', 'user', '4f792c75-4a65-45a7-a0ef-d282acd83d87', 'Vendor logged out', '197.223.152.7', '2026-04-10 18:55:32'),
('da35c1c8-3d98-11f1-9448-e66239f31cb0', 'f52cc98d-3d20-11f1-9448-e66239f31cb0', 'login', 'user', 'f52cc98d-3d20-11f1-9448-e66239f31cb0', 'Admin logged in', '154.143.246.144', '2026-04-21 18:43:33'),
('da49f915-3582-11f1-9448-e66239f31cb0', 'd8e13ad8-2382-11f1-b82d-97044e7d8834', 'logout', 'user', 'd8e13ad8-2382-11f1-b82d-97044e7d8834', 'Admin logged out', '196.136.158.162', '2026-04-11 08:45:54'),
('dad839fe-3696-11f1-9448-e66239f31cb0', 'd8e13ad8-2382-11f1-b82d-97044e7d8834', 'logout', 'user', 'd8e13ad8-2382-11f1-b82d-97044e7d8834', 'Admin logged out', '154.143.40.184', '2026-04-12 20:41:36'),
('db47b403-2f96-11f1-9448-e66239f31cb0', '8f266f22-1d9d-11f1-b82d-97044e7d8834', 'deactivated', 'admin', '5920fb61-26f4-11f1-abf9-e1b0d104a220', 'Deactivated admin: loay', '197.222.117.240', '2026-04-03 19:53:59'),
('db585cf9-3592-11f1-9448-e66239f31cb0', '4f792c75-4a65-45a7-a0ef-d282acd83d87', 'closed', 'vendor', '57e1966b-294a-11f1-9448-e66239f31cb0', 'Closed store: فتح الله ماركت', '197.150.164.48', '2026-04-11 10:40:28'),
('db5b8ea3-3508-11f1-9448-e66239f31cb0', '8f266f22-1d9d-11f1-b82d-97044e7d8834', 'logout', 'user', '8f266f22-1d9d-11f1-b82d-97044e7d8834', 'Super_admin logged out', '197.223.152.7', '2026-04-10 18:12:38'),
('dc1f7119-33ab-11f1-9448-e66239f31cb0', '4f792c75-4a65-45a7-a0ef-d282acd83d87', 'login', 'user', '4f792c75-4a65-45a7-a0ef-d282acd83d87', 'Vendor logged in', '154.143.143.60', '2026-04-09 00:34:25'),
('dc300a95-350b-11f1-9448-e66239f31cb0', '8f266f22-1d9d-11f1-b82d-97044e7d8834', 'logout', 'user', '8f266f22-1d9d-11f1-b82d-97044e7d8834', 'Super_admin logged out', '197.223.152.7', '2026-04-10 18:34:08'),
('dd2b1840-303a-11f1-9448-e66239f31cb0', '4f792c75-4a65-45a7-a0ef-d282acd83d87', 'logout', 'user', '4f792c75-4a65-45a7-a0ef-d282acd83d87', 'Vendor logged out', '154.143.143.60', '2026-04-04 15:28:00'),
('df0d8b85-3d98-11f1-9448-e66239f31cb0', 'f52cc98d-3d20-11f1-9448-e66239f31cb0', 'logout', 'user', 'f52cc98d-3d20-11f1-9448-e66239f31cb0', 'Admin logged out', '154.143.246.144', '2026-04-21 18:43:41'),
('df5038ac-2f0b-11f1-9448-e66239f31cb0', '8f266f22-1d9d-11f1-b82d-97044e7d8834', 'login', 'user', '8f266f22-1d9d-11f1-b82d-97044e7d8834', 'Super_admin logged in', '197.223.138.29', '2026-04-03 03:19:06'),
('df8614f3-3696-11f1-9448-e66239f31cb0', '4f792c75-4a65-45a7-a0ef-d282acd83d87', 'login', 'user', '4f792c75-4a65-45a7-a0ef-d282acd83d87', 'Vendor logged in', '154.143.40.184', '2026-04-12 20:41:44'),
('e0a56331-35cc-11f1-9448-e66239f31cb0', '8f266f22-1d9d-11f1-b82d-97044e7d8834', 'update', 'advertisement', '5c703dba-2ef9-11f1-9448-e66239f31cb0', 'Updated advertisement: gaza ad', '197.150.164.48', '2026-04-11 17:35:48'),
('e112b227-35cc-11f1-9448-e66239f31cb0', '8f266f22-1d9d-11f1-b82d-97044e7d8834', 'update', 'advertisement', '5c703dba-2ef9-11f1-9448-e66239f31cb0', 'Uploaded image for advertisement', '197.150.164.48', '2026-04-11 17:35:49'),
('e1286f04-2f96-11f1-9448-e66239f31cb0', '8f266f22-1d9d-11f1-b82d-97044e7d8834', 'activated', 'admin', '5920fb61-26f4-11f1-abf9-e1b0d104a220', 'Activated admin: loay', '197.222.117.240', '2026-04-03 19:54:09'),
('e1394d9f-3d1b-11f1-9448-e66239f31cb0', '4f792c75-4a65-45a7-a0ef-d282acd83d87', 'login', 'user', '4f792c75-4a65-45a7-a0ef-d282acd83d87', 'Vendor logged in', '154.143.246.144', '2026-04-21 03:48:57'),
('e1472be5-350e-11f1-9448-e66239f31cb0', '8f266f22-1d9d-11f1-b82d-97044e7d8834', 'login', 'user', '8f266f22-1d9d-11f1-b82d-97044e7d8834', 'Super_admin logged in', '197.223.152.7', '2026-04-10 18:55:45'),
('e1d3e229-2f7a-11f1-9448-e66239f31cb0', '4f792c75-4a65-45a7-a0ef-d282acd83d87', 'login', 'user', '4f792c75-4a65-45a7-a0ef-d282acd83d87', 'Vendor logged in', '197.222.117.240', '2026-04-03 16:33:44'),
('e1f9db98-2f0a-11f1-9448-e66239f31cb0', '8f266f22-1d9d-11f1-b82d-97044e7d8834', 'update', 'delivery_zone', 'bc140619-2f03-11f1-9448-e66239f31cb0', 'SA updated delivery zone: test zone', '197.223.138.29', '2026-04-03 03:12:01'),
('e2661a6e-35cd-11f1-9448-e66239f31cb0', '4f792c75-4a65-45a7-a0ef-d282acd83d87', 'logout', 'user', '4f792c75-4a65-45a7-a0ef-d282acd83d87', 'Vendor logged out', '197.150.164.48', '2026-04-11 17:43:00'),
('e2aaf9c4-3582-11f1-9448-e66239f31cb0', '4f792c75-4a65-45a7-a0ef-d282acd83d87', 'login', 'user', '4f792c75-4a65-45a7-a0ef-d282acd83d87', 'Vendor logged in', '196.136.158.162', '2026-04-11 08:46:09'),
('e2e9cef9-2f5f-11f1-9448-e66239f31cb0', '4f792c75-4a65-45a7-a0ef-d282acd83d87', 'login', 'user', '4f792c75-4a65-45a7-a0ef-d282acd83d87', 'Vendor logged in', '197.223.138.29', '2026-04-03 13:20:30'),
('e3100c5f-238d-11f1-b82d-97044e7d8834', 'd8e13ad8-2382-11f1-b82d-97044e7d8834', 'login', 'user', 'd8e13ad8-2382-11f1-b82d-97044e7d8834', 'Admin logged in', '156.217.99.167', '2026-03-19 12:18:07'),
('e4119a17-3b2f-11f1-9448-e66239f31cb0', '4f792c75-4a65-45a7-a0ef-d282acd83d87', 'logout', 'user', '4f792c75-4a65-45a7-a0ef-d282acd83d87', 'Vendor logged out', '197.222.6.108', '2026-04-18 17:07:10'),
('e56744e3-350b-11f1-9448-e66239f31cb0', '4f792c75-4a65-45a7-a0ef-d282acd83d87', 'login', 'user', '4f792c75-4a65-45a7-a0ef-d282acd83d87', 'Vendor logged in', '197.223.152.7', '2026-04-10 18:34:23'),
('e58a42e2-3526-11f1-9448-e66239f31cb0', 'cf2239d2-2dd7-4116-abd3-1f24d48e8fe7', 'logout', 'user', 'cf2239d2-2dd7-4116-abd3-1f24d48e8fe7', 'Vendor logged out', '196.136.46.60', '2026-04-10 21:47:40'),
('e5ca2cda-3696-11f1-9448-e66239f31cb0', '4f792c75-4a65-45a7-a0ef-d282acd83d87', 'accept', 'order', '7175c222-70ed-49bf-929d-11fb434f107a', 'Vendor فتح الله ماركت accepted order', '154.143.40.184', '2026-04-12 20:41:55'),
('e5efb03e-343f-11f1-9448-e66239f31cb0', 'd8e13ad8-2382-11f1-b82d-97044e7d8834', 'logout', 'user', 'd8e13ad8-2382-11f1-b82d-97044e7d8834', 'Admin logged out', '154.143.86.111', '2026-04-09 18:14:07'),
('e626b55d-2ef1-11f1-9448-e66239f31cb0', '8f266f22-1d9d-11f1-b82d-97044e7d8834', 'suspend', 'vendor', '57e1966b-294a-11f1-9448-e66239f31cb0', 'Suspended vendor: gaza', '197.223.138.29', '2026-04-03 00:13:10'),
('e6420943-3508-11f1-9448-e66239f31cb0', 'd8e13ad8-2382-11f1-b82d-97044e7d8834', 'login', 'user', 'd8e13ad8-2382-11f1-b82d-97044e7d8834', 'Admin logged in', '197.223.152.7', '2026-04-10 18:12:56'),
('e64def19-3d20-11f1-9448-e66239f31cb0', '8f266f22-1d9d-11f1-b82d-97044e7d8834', 'create', 'admin', 'e64dd274-3d20-11f1-9448-e66239f31cb0', 'Created new admin: north (north@gmail.com)', '154.143.246.144', '2026-04-21 04:24:53'),
('e672485e-230d-11f1-b82d-97044e7d8834', '8f266f22-1d9d-11f1-b82d-97044e7d8834', 'login', 'user', '8f266f22-1d9d-11f1-b82d-97044e7d8834', 'Super_admin logged in', '156.217.125.91', '2026-03-18 21:01:57'),
('e6fa6d77-351c-11f1-9448-e66239f31cb0', '4f792c75-4a65-45a7-a0ef-d282acd83d87', 'login', 'user', '4f792c75-4a65-45a7-a0ef-d282acd83d87', 'Vendor logged in', '196.136.12.153', '2026-04-10 20:36:07'),
('e754189e-2ef1-11f1-9448-e66239f31cb0', '8f266f22-1d9d-11f1-b82d-97044e7d8834', 'reactivate', 'vendor', '57e1966b-294a-11f1-9448-e66239f31cb0', 'Reactivated vendor: gaza', '197.223.138.29', '2026-04-03 00:13:12'),
('e806ed4c-34eb-11f1-9448-e66239f31cb0', '4f792c75-4a65-45a7-a0ef-d282acd83d87', 'logout', 'user', '4f792c75-4a65-45a7-a0ef-d282acd83d87', 'Vendor logged out', '45.109.226.75', '2026-04-10 14:45:23'),
('e83cb37e-3d98-11f1-9448-e66239f31cb0', '6255a20b-3d97-11f1-9448-e66239f31cb0', 'login', 'user', '6255a20b-3d97-11f1-9448-e66239f31cb0', 'Vendor logged in', '154.143.246.144', '2026-04-21 18:43:56');
INSERT INTO `activity_logs` (`id`, `user_id`, `action`, `entity_type`, `entity_id`, `description`, `ip_address`, `created_at`) VALUES
('e8c14110-3039-11f1-9448-e66239f31cb0', '8f266f22-1d9d-11f1-b82d-97044e7d8834', 'logout', 'user', '8f266f22-1d9d-11f1-b82d-97044e7d8834', 'Super_admin logged out', '154.143.143.60', '2026-04-04 15:21:10'),
('e8cf8f9c-35fa-11f1-9448-e66239f31cb0', 'be9db6e5-2efe-11f1-9448-e66239f31cb0', 'logout', 'user', 'be9db6e5-2efe-11f1-9448-e66239f31cb0', 'Vendor logged out', '154.143.40.184', '2026-04-11 23:05:18'),
('e8d94c43-3696-11f1-9448-e66239f31cb0', '4f792c75-4a65-45a7-a0ef-d282acd83d87', 'mark_preparing', 'order', '7175c222-70ed-49bf-929d-11fb434f107a', 'Order being prepared', '154.143.40.184', '2026-04-12 20:42:00'),
('e95d9358-3592-11f1-9448-e66239f31cb0', '4f792c75-4a65-45a7-a0ef-d282acd83d87', 'opened', 'vendor', '57e1966b-294a-11f1-9448-e66239f31cb0', 'Opened store: فتح الله ماركت', '197.150.164.48', '2026-04-11 10:40:52'),
('e9ae56b4-2ef1-11f1-9448-e66239f31cb0', '8f266f22-1d9d-11f1-b82d-97044e7d8834', 'suspend', 'vendor', '57e1966b-294a-11f1-9448-e66239f31cb0', 'Suspended vendor: gaza', '197.223.138.29', '2026-04-03 00:13:16'),
('e9ba01fd-3526-11f1-9448-e66239f31cb0', 'd8e13ad8-2382-11f1-b82d-97044e7d8834', 'login', 'user', 'd8e13ad8-2382-11f1-b82d-97044e7d8834', 'Admin logged in', '196.136.46.60', '2026-04-10 21:47:47'),
('ea4ecabb-3696-11f1-9448-e66239f31cb0', '4f792c75-4a65-45a7-a0ef-d282acd83d87', 'mark_ready', 'order', '7175c222-70ed-49bf-929d-11fb434f107a', 'All vendors ready for pickup', '154.143.40.184', '2026-04-12 20:42:02'),
('ea800cc2-3d22-11f1-9448-e66239f31cb0', '8f266f22-1d9d-11f1-b82d-97044e7d8834', 'logout', 'user', '8f266f22-1d9d-11f1-b82d-97044e7d8834', 'Super_admin logged out', '154.143.246.144', '2026-04-21 04:39:19'),
('eaf4d48d-3d98-11f1-9448-e66239f31cb0', '6255a20b-3d97-11f1-9448-e66239f31cb0', 'accept', 'order', 'f5d055ec-9300-445f-af99-77077d8cc519', 'Vendor south accepted order', '154.143.246.144', '2026-04-21 18:44:01'),
('eb3cb1e2-2ef1-11f1-9448-e66239f31cb0', '8f266f22-1d9d-11f1-b82d-97044e7d8834', 'reactivate', 'vendor', '57e1966b-294a-11f1-9448-e66239f31cb0', 'Reactivated vendor: gaza', '197.223.138.29', '2026-04-03 00:13:19'),
('eb58fd3a-2f7a-11f1-9448-e66239f31cb0', '4f792c75-4a65-45a7-a0ef-d282acd83d87', 'update_profile', 'vendor', '57e1966b-294a-11f1-9448-e66239f31cb0', 'Updated vendor profile', '197.222.117.240', '2026-04-03 16:34:00'),
('eb947cde-3d8f-11f1-9448-e66239f31cb0', '68d947ef-fcec-426a-8316-155666848619', 'login', 'user', '68d947ef-fcec-426a-8316-155666848619', 'Vendor logged in', '154.143.246.144', '2026-04-21 17:39:36'),
('ec753da1-2f66-11f1-9448-e66239f31cb0', '8f266f22-1d9d-11f1-b82d-97044e7d8834', 'logout', 'user', '8f266f22-1d9d-11f1-b82d-97044e7d8834', 'Super_admin logged out', '197.223.138.29', '2026-04-03 14:10:52'),
('ecbe2354-31fd-11f1-9448-e66239f31cb0', '8f266f22-1d9d-11f1-b82d-97044e7d8834', 'logout', 'user', '8f266f22-1d9d-11f1-b82d-97044e7d8834', 'Super_admin logged out', '154.143.143.60', '2026-04-06 21:16:49'),
('ecef7769-3525-11f1-9448-e66239f31cb0', '8f266f22-1d9d-11f1-b82d-97044e7d8834', 'logout', 'user', '8f266f22-1d9d-11f1-b82d-97044e7d8834', 'Super_admin logged out', '196.136.46.60', '2026-04-10 21:40:43'),
('ed93eaed-3b2f-11f1-9448-e66239f31cb0', 'd8e13ad8-2382-11f1-b82d-97044e7d8834', 'login', 'user', 'd8e13ad8-2382-11f1-b82d-97044e7d8834', 'Admin logged in', '158.140.85.78', '2026-04-18 17:07:26'),
('ee807746-3d98-11f1-9448-e66239f31cb0', '6255a20b-3d97-11f1-9448-e66239f31cb0', 'mark_preparing', 'order', 'f5d055ec-9300-445f-af99-77077d8cc519', 'Order being prepared', '154.143.246.144', '2026-04-21 18:44:07'),
('eeb1d627-3d8f-11f1-9448-e66239f31cb0', '68d947ef-fcec-426a-8316-155666848619', 'accept', 'order', '8f957033-af0e-4c09-a600-74344e7b1485', 'Vendor North food accepted order', '154.143.246.144', '2026-04-21 17:39:41'),
('ef660c11-3d98-11f1-9448-e66239f31cb0', '6255a20b-3d97-11f1-9448-e66239f31cb0', 'mark_ready', 'order', 'f5d055ec-9300-445f-af99-77077d8cc519', 'All vendors ready for pickup', '154.143.246.144', '2026-04-21 18:44:08'),
('ef73a8e5-343f-11f1-9448-e66239f31cb0', '4f792c75-4a65-45a7-a0ef-d282acd83d87', 'login', 'user', '4f792c75-4a65-45a7-a0ef-d282acd83d87', 'Vendor logged in', '154.143.86.111', '2026-04-09 18:14:23'),
('efc3130a-35cd-11f1-9448-e66239f31cb0', 'cf2239d2-2dd7-4116-abd3-1f24d48e8fe7', 'login', 'user', 'cf2239d2-2dd7-4116-abd3-1f24d48e8fe7', 'Vendor logged in', '197.150.164.48', '2026-04-11 17:43:23'),
('f0073198-3b2f-11f1-9448-e66239f31cb0', '8f266f22-1d9d-11f1-b82d-97044e7d8834', 'login', 'user', '8f266f22-1d9d-11f1-b82d-97044e7d8834', 'Super_admin logged in', '197.222.6.108', '2026-04-18 17:07:30'),
('f0c2a4f2-3d90-11f1-9448-e66239f31cb0', 'e64dd274-3d20-11f1-9448-e66239f31cb0', 'logout', 'user', 'e64dd274-3d20-11f1-9448-e66239f31cb0', 'Admin logged out', '154.143.246.144', '2026-04-21 17:46:54'),
('f0c66250-3696-11f1-9448-e66239f31cb0', '4f792c75-4a65-45a7-a0ef-d282acd83d87', 'logout', 'user', '4f792c75-4a65-45a7-a0ef-d282acd83d87', 'Vendor logged out', '154.143.40.184', '2026-04-12 20:42:13'),
('f1fb57a3-2f82-11f1-9448-e66239f31cb0', '4f792c75-4a65-45a7-a0ef-d282acd83d87', 'update_profile', 'vendor', '57e1966b-294a-11f1-9448-e66239f31cb0', 'Updated vendor profile', '197.222.117.240', '2026-04-03 17:31:27'),
('f21d70c5-35cd-11f1-9448-e66239f31cb0', 'cf2239d2-2dd7-4116-abd3-1f24d48e8fe7', 'accept', 'order', 'a1c23979-4844-4910-b6c7-d96f1d5807af', 'Vendor سوق accepted order', '197.150.164.48', '2026-04-11 17:43:27'),
('f351135b-238d-11f1-b82d-97044e7d8834', 'd8e13ad8-2382-11f1-b82d-97044e7d8834', 'change_password', 'user', 'd8e13ad8-2382-11f1-b82d-97044e7d8834', 'Password changed via profile', '156.217.99.167', '2026-03-19 12:18:34'),
('f467cbb7-2f66-11f1-9448-e66239f31cb0', '4f792c75-4a65-45a7-a0ef-d282acd83d87', 'login', 'user', '4f792c75-4a65-45a7-a0ef-d282acd83d87', 'Vendor logged in', '197.223.138.29', '2026-04-03 14:11:05'),
('f4693256-3582-11f1-9448-e66239f31cb0', '4f792c75-4a65-45a7-a0ef-d282acd83d87', 'closed', 'vendor', '57e1966b-294a-11f1-9448-e66239f31cb0', 'Closed store: فتح الله ماركت', '196.136.158.162', '2026-04-11 08:46:38'),
('f4718984-35fa-11f1-9448-e66239f31cb0', '8f266f22-1d9d-11f1-b82d-97044e7d8834', 'login', 'user', '8f266f22-1d9d-11f1-b82d-97044e7d8834', 'Super_admin logged in', '154.143.40.184', '2026-04-11 23:05:38'),
('f4a9c8b1-31fd-11f1-9448-e66239f31cb0', 'cf2239d2-2dd7-4116-abd3-1f24d48e8fe7', 'login', 'user', 'cf2239d2-2dd7-4116-abd3-1f24d48e8fe7', 'Vendor logged in', '154.143.143.60', '2026-04-06 21:17:02'),
('f524ce07-3525-11f1-9448-e66239f31cb0', '4f792c75-4a65-45a7-a0ef-d282acd83d87', 'login', 'user', '4f792c75-4a65-45a7-a0ef-d282acd83d87', 'Vendor logged in', '196.136.46.60', '2026-04-10 21:40:56'),
('f52cee24-3d20-11f1-9448-e66239f31cb0', '8f266f22-1d9d-11f1-b82d-97044e7d8834', 'create', 'admin', 'f52cc98d-3d20-11f1-9448-e66239f31cb0', 'Created new admin: south (south@gmail.com)', '154.143.246.144', '2026-04-21 04:25:18'),
('f5be5924-3d98-11f1-9448-e66239f31cb0', '6255a20b-3d97-11f1-9448-e66239f31cb0', 'logout', 'user', '6255a20b-3d97-11f1-9448-e66239f31cb0', 'Vendor logged out', '154.143.246.144', '2026-04-21 18:44:19'),
('f5ea14c5-2ef6-11f1-9448-e66239f31cb0', '8f266f22-1d9d-11f1-b82d-97044e7d8834', 'create', 'advertisement', 'f5e9cc4e-2ef6-11f1-9448-e66239f31cb0', 'Created advertisement: add one', '197.223.138.29', '2026-04-03 00:49:24'),
('f6368b91-2313-11f1-b82d-97044e7d8834', NULL, 'login', 'user', 'cddc797e-1e85-11f1-b82d-97044e7d8834', 'Admin logged in', '45.104.153.29', '2026-03-18 21:45:20'),
('f64acf5d-3582-11f1-9448-e66239f31cb0', '4f792c75-4a65-45a7-a0ef-d282acd83d87', 'opened', 'vendor', '57e1966b-294a-11f1-9448-e66239f31cb0', 'Opened store: فتح الله ماركت', '196.136.158.162', '2026-04-11 08:46:41'),
('f6fbdf8d-35cd-11f1-9448-e66239f31cb0', 'cf2239d2-2dd7-4116-abd3-1f24d48e8fe7', 'mark_preparing', 'order', 'a1c23979-4844-4910-b6c7-d96f1d5807af', 'Order being prepared', '197.150.164.48', '2026-04-11 17:43:35'),
('f7cbce51-3ce3-11f1-9448-e66239f31cb0', '8ba11278-3b31-11f1-9448-e66239f31cb0', 'login', 'user', '8ba11278-3b31-11f1-9448-e66239f31cb0', 'Admin logged in', '212.14.248.217', '2026-04-20 21:08:43'),
('f8b768a3-35cb-11f1-9448-e66239f31cb0', '8f266f22-1d9d-11f1-b82d-97044e7d8834', 'update', 'advertisement', '5c703dba-2ef9-11f1-9448-e66239f31cb0', 'Updated advertisement: gaza ad', '197.150.164.48', '2026-04-11 17:29:19'),
('f911d7f7-3474-11f1-9448-e66239f31cb0', 'd8e13ad8-2382-11f1-b82d-97044e7d8834', 'logout', 'user', 'd8e13ad8-2382-11f1-b82d-97044e7d8834', 'Admin logged out', '45.109.112.29', '2026-04-10 00:34:02'),
('f93b3227-3344-11f1-9448-e66239f31cb0', '4f792c75-4a65-45a7-a0ef-d282acd83d87', 'create', 'menu_category', 'f93b120a-3344-11f1-9448-e66239f31cb0', 'Subscribed to category: Pharmacy', '196.151.0.29', '2026-04-08 12:17:55'),
('f9a005db-3508-11f1-9448-e66239f31cb0', 'd8e13ad8-2382-11f1-b82d-97044e7d8834', 'logout', 'user', 'd8e13ad8-2382-11f1-b82d-97044e7d8834', 'Admin logged out', '197.223.152.7', '2026-04-10 18:13:28'),
('fa29273b-3696-11f1-9448-e66239f31cb0', 'd8e13ad8-2382-11f1-b82d-97044e7d8834', 'login', 'user', 'd8e13ad8-2382-11f1-b82d-97044e7d8834', 'Admin logged in', '154.143.40.184', '2026-04-12 20:42:29'),
('fa2a41b0-350b-11f1-9448-e66239f31cb0', '4f792c75-4a65-45a7-a0ef-d282acd83d87', 'opened', 'vendor', '57e1966b-294a-11f1-9448-e66239f31cb0', 'Opened store: gaza', '197.223.152.7', '2026-04-10 18:34:58'),
('fb301f3f-3525-11f1-9448-e66239f31cb0', '4f792c75-4a65-45a7-a0ef-d282acd83d87', 'logout', 'user', '4f792c75-4a65-45a7-a0ef-d282acd83d87', 'Vendor logged out', '196.136.46.60', '2026-04-10 21:41:06'),
('fb9518be-2951-11f1-9448-e66239f31cb0', '4f792c75-4a65-45a7-a0ef-d282acd83d87', 'create', 'menu_item', 'fb94e9c4-2951-11f1-9448-e66239f31cb0', 'Created item: pizza', '45.109.220.74', '2026-03-26 20:25:51'),
('fbe98d92-3d98-11f1-9448-e66239f31cb0', 'e64dd274-3d20-11f1-9448-e66239f31cb0', 'login', 'user', 'e64dd274-3d20-11f1-9448-e66239f31cb0', 'Admin logged in', '154.143.246.144', '2026-04-21 18:44:29'),
('fc3fd59b-3d20-11f1-9448-e66239f31cb0', '8f266f22-1d9d-11f1-b82d-97044e7d8834', 'logout', 'user', '8f266f22-1d9d-11f1-b82d-97044e7d8834', 'Super_admin logged out', '154.143.246.144', '2026-04-21 04:25:30'),
('fcc154ea-35ca-11f1-9448-e66239f31cb0', 'cf2239d2-2dd7-4116-abd3-1f24d48e8fe7', 'logout', 'user', 'cf2239d2-2dd7-4116-abd3-1f24d48e8fe7', 'Vendor logged out', '197.150.164.48', '2026-04-11 17:22:16'),
('fd7cd4a7-35cd-11f1-9448-e66239f31cb0', 'cf2239d2-2dd7-4116-abd3-1f24d48e8fe7', 'logout', 'user', 'cf2239d2-2dd7-4116-abd3-1f24d48e8fe7', 'Vendor logged out', '197.150.164.48', '2026-04-11 17:43:46'),
('fdcf7e39-26f4-11f1-abf9-e1b0d104a220', '8f266f22-1d9d-11f1-b82d-97044e7d8834', 'login', 'user', '8f266f22-1d9d-11f1-b82d-97044e7d8834', 'Super_admin logged in', '45.109.19.74', '2026-03-23 20:15:09'),
('fde0bb52-2ef6-11f1-9448-e66239f31cb0', '8f266f22-1d9d-11f1-b82d-97044e7d8834', 'update', 'advertisement', 'f5e9cc4e-2ef6-11f1-9448-e66239f31cb0', 'Uploaded image for advertisement', '197.223.138.29', '2026-04-03 00:49:38'),
('fe0bf2d1-3344-11f1-9448-e66239f31cb0', '4f792c75-4a65-45a7-a0ef-d282acd83d87', 'delete', 'menu_category', 'f93b120a-3344-11f1-9448-e66239f31cb0', 'Deleted category: Pharmacy', '196.151.0.29', '2026-04-08 12:18:03'),
('fe229885-3070-11f1-9448-e66239f31cb0', '8f266f22-1d9d-11f1-b82d-97044e7d8834', 'login', 'user', '8f266f22-1d9d-11f1-b82d-97044e7d8834', 'Super_admin logged in', '176.65.2.214', '2026-04-04 21:55:28'),
('feb7379a-3038-11f1-9448-e66239f31cb0', '8f266f22-1d9d-11f1-b82d-97044e7d8834', 'update', 'advertisement', '5c703dba-2ef9-11f1-9448-e66239f31cb0', 'Updated advertisement: test add', '154.143.143.60', '2026-04-04 15:14:37'),
('ff008728-3d90-11f1-9448-e66239f31cb0', '8f266f22-1d9d-11f1-b82d-97044e7d8834', 'login', 'user', '8f266f22-1d9d-11f1-b82d-97044e7d8834', 'Super_admin logged in', '154.143.246.144', '2026-04-21 17:47:18');

-- --------------------------------------------------------

--
-- Table structure for table `admin_sessions`
--

CREATE TABLE `admin_sessions` (
  `id` char(36) NOT NULL DEFAULT uuid(),
  `user_id` char(36) NOT NULL,
  `token` varchar(64) NOT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `expires_at` datetime NOT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `admin_sessions`
--

INSERT INTO `admin_sessions` (`id`, `user_id`, `token`, `ip_address`, `user_agent`, `expires_at`, `created_at`) VALUES
('2582eb64-3c4f-11f1-9448-e66239f31cb0', '568753fd-bfa7-4920-a12a-36bd71277519', '63257734e28dbdc4d486a55e77f436d1781f5c8c7337539d9c2748b8c512ad9d', '197.223.177.198', 'Dart/3.11 (dart:io)', '2036-04-17 00:23:25', '2026-04-20 03:23:25'),
('42e79fa1-3b2e-11f1-9448-e66239f31cb0', '5732093a-a1d5-46ba-9432-ac9f560ba03c', '92a6c15ed8fe64122f44826036b6dcfb6462ac4da0bce5b74023b5e391153506', '158.140.85.78', 'Dart/3.11 (dart:io)', '2026-04-25 13:55:30', '2026-04-18 16:55:30'),
('71f39bcb-38f1-11f1-9448-e66239f31cb0', '43d5d6a2-e09d-4581-8636-557cd150b0f1', 'f5c9781596fb8157ca8cdb664e33d4f450960338d110c563cf50361aa76f5065', '197.223.100.82', 'Dart/3.11 (dart:io)', '2026-04-22 17:35:07', '2026-04-15 20:35:07'),
('793a395e-3d15-11f1-9448-e66239f31cb0', '1d8557e5-8a7d-4c32-8181-ad785ea979a0', '3e47b0c8f636a81f7fd8595fe7ed6cfb720175b88fccd5ec9d465ece854ce3df', '154.143.246.144', 'Dart/3.11 (dart:io)', '2036-04-18 00:03:06', '2026-04-21 03:03:06'),
('9e138655-3d8e-11f1-9448-e66239f31cb0', '0717139f-9fbc-4c8c-be98-cf1361e0036c', '5435e338d4a80737a92b3ce521dbfdda925e3c2a052f2b402cf16fb88caf9c14', '102.47.85.34', 'Dart/3.11 (dart:io)', '2036-04-18 14:30:17', '2026-04-21 17:30:17'),
('a0859c3d-3a5e-11f1-9448-e66239f31cb0', '5732093a-a1d5-46ba-9432-ac9f560ba03c', '3e9cd4b2ba587e5c33e3d9f03f230bc0644ee5608429381371ba1135bfb96c26', '188.161.75.165', 'Dart/3.11 (dart:io)', '2026-04-24 13:09:11', '2026-04-17 16:09:11'),
('b7fd360e-39be-11f1-9448-e66239f31cb0', '5732093a-a1d5-46ba-9432-ac9f560ba03c', '73b51dcee93f7cad477462151f1cad070058ce43f21c632bad99985bc08b0c70', '37.8.3.176', 'Dart/3.11 (dart:io)', '2026-04-23 18:04:31', '2026-04-16 21:04:31'),
('fbe92aed-3d98-11f1-9448-e66239f31cb0', 'e64dd274-3d20-11f1-9448-e66239f31cb0', '78ec9ce0714ff4516d854a5fa1dc873c39edc68ca2768b1db924f274105c90f1', '154.143.246.144', 'Dart/3.11 (dart:io)', '2026-04-22 15:44:29', '2026-04-21 18:44:29');

-- --------------------------------------------------------

--
-- Table structure for table `advertisements`
--

CREATE TABLE `advertisements` (
  `id` char(36) NOT NULL DEFAULT uuid(),
  `title` varchar(150) NOT NULL,
  `description` text DEFAULT NULL,
  `image_url` text NOT NULL,
  `vendor_id` char(36) DEFAULT NULL,
  `target_url` text DEFAULT NULL,
  `sort_order` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `starts_at` datetime DEFAULT NULL,
  `ends_at` datetime DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `advertisements`
--

INSERT INTO `advertisements` (`id`, `title`, `description`, `image_url`, `vendor_id`, `target_url`, `sort_order`, `is_active`, `starts_at`, `ends_at`, `created_at`) VALUES
('5c703dba-2ef9-11f1-9448-e66239f31cb0', 'gaza ad', 'gaza ad', 'https://appforyou.store/backendOrder/upload/advertisements/69dab87371fe1_1775941747.jpg', '95a07fca-231c-11f1-b82d-97044e7d8834', 'https://www.google.com/search?q=Scented+Candle+Set&amp;amp;amp;client=ms-android-samsung-ss&amp;amp;amp;hs=hpgp&amp;amp;amp;sca_esv=b1359834a2735cce&amp;amp;amp;udm=2&amp;amp;amp;biw=411&amp;amp;amp;bih=810&amp;amp;amp;sxsrf=ANbL-n64pxBWM-MvOxOPkRU78fJxp7Wkrw%3A1775928055676&amp;amp;amp;ei=94LaaZ33KP669u8P9_azoQ0&amp;amp;amp;oq=Scented+Candle+Set&amp;amp;amp;gs_lp=EhJtb2JpbGUtZ3dzLXdpei1pbWciElNjZW50ZWQgQ2FuZGxlIFNldDIKECMYJxjJAhjqAjIKECMYJxjJAhjqAjIKECMYJxjJAhjqAjIKECMYJxjJAhjqAjIKECMYJxjJAhjqAkirCFD8Blj8BnACeACQAQCYAQCgAQCqAQC4AQPIAQD4AQGYAgKgAhioAgWYAw-SBwEyoAcAsgcAuAcAwgcDMy0yyAcWgAgA&amp;amp;amp;sclient=mobile-gws-wiz-img', 1, 1, '2026-04-03 00:00:00', '2026-05-03 23:59:59', '2026-04-03 01:06:35'),
('f5e9cc4e-2ef6-11f1-9448-e66239f31cb0', 'add one', 'this my first add', 'https://appforyou.store/backendOrder/upload/advertisements/69dab87d070f0_1775941757.jpg', '57e1966b-294a-11f1-9448-e66239f31cb0', NULL, 2, 1, '2026-04-03 00:00:00', '2026-05-03 23:59:59', '2026-04-03 00:49:24');

-- --------------------------------------------------------

--
-- Table structure for table `ad_clicks`
--

CREATE TABLE `ad_clicks` (
  `id` char(36) NOT NULL DEFAULT uuid(),
  `ad_id` char(36) NOT NULL,
  `user_id` char(36) DEFAULT NULL,
  `clicked_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `ad_clicks`
--

INSERT INTO `ad_clicks` (`id`, `ad_id`, `user_id`, `clicked_at`) VALUES
('1009961b-3368-11f1-9448-e66239f31cb0', '5c703dba-2ef9-11f1-9448-e66239f31cb0', '43d5d6a2-e09d-4581-8636-557cd150b0f1', '2026-04-08 16:29:06'),
('1e37fc77-361c-11f1-9448-e66239f31cb0', '5c703dba-2ef9-11f1-9448-e66239f31cb0', '43d5d6a2-e09d-4581-8636-557cd150b0f1', '2026-04-12 06:03:01'),
('250f6322-361c-11f1-9448-e66239f31cb0', '5c703dba-2ef9-11f1-9448-e66239f31cb0', '43d5d6a2-e09d-4581-8636-557cd150b0f1', '2026-04-12 06:03:13'),
('2d1e9a63-361c-11f1-9448-e66239f31cb0', 'f5e9cc4e-2ef6-11f1-9448-e66239f31cb0', '43d5d6a2-e09d-4581-8636-557cd150b0f1', '2026-04-12 06:03:26'),
('325a30bd-361c-11f1-9448-e66239f31cb0', 'f5e9cc4e-2ef6-11f1-9448-e66239f31cb0', '43d5d6a2-e09d-4581-8636-557cd150b0f1', '2026-04-12 06:03:35'),
('3426d56c-361c-11f1-9448-e66239f31cb0', '5c703dba-2ef9-11f1-9448-e66239f31cb0', '43d5d6a2-e09d-4581-8636-557cd150b0f1', '2026-04-12 06:03:38'),
('4de2f9e4-32e9-11f1-9448-e66239f31cb0', '5c703dba-2ef9-11f1-9448-e66239f31cb0', '43d5d6a2-e09d-4581-8636-557cd150b0f1', '2026-04-08 01:21:44'),
('508a085b-32e9-11f1-9448-e66239f31cb0', 'f5e9cc4e-2ef6-11f1-9448-e66239f31cb0', '43d5d6a2-e09d-4581-8636-557cd150b0f1', '2026-04-08 01:21:48'),
('59f32acc-3513-11f1-9448-e66239f31cb0', '5c703dba-2ef9-11f1-9448-e66239f31cb0', '43d5d6a2-e09d-4581-8636-557cd150b0f1', '2026-04-10 19:27:45'),
('5c416567-3513-11f1-9448-e66239f31cb0', 'f5e9cc4e-2ef6-11f1-9448-e66239f31cb0', '43d5d6a2-e09d-4581-8636-557cd150b0f1', '2026-04-10 19:27:49'),
('70b13bda-3513-11f1-9448-e66239f31cb0', 'f5e9cc4e-2ef6-11f1-9448-e66239f31cb0', '43d5d6a2-e09d-4581-8636-557cd150b0f1', '2026-04-10 19:28:23'),
('7572963d-3513-11f1-9448-e66239f31cb0', 'f5e9cc4e-2ef6-11f1-9448-e66239f31cb0', '43d5d6a2-e09d-4581-8636-557cd150b0f1', '2026-04-10 19:28:31'),
('77946442-3509-11f1-9448-e66239f31cb0', '5c703dba-2ef9-11f1-9448-e66239f31cb0', '43d5d6a2-e09d-4581-8636-557cd150b0f1', '2026-04-10 18:17:00'),
('7a0eb1c6-3513-11f1-9448-e66239f31cb0', 'f5e9cc4e-2ef6-11f1-9448-e66239f31cb0', '43d5d6a2-e09d-4581-8636-557cd150b0f1', '2026-04-10 19:28:39'),
('7ede8aae-32e9-11f1-9448-e66239f31cb0', '5c703dba-2ef9-11f1-9448-e66239f31cb0', '43d5d6a2-e09d-4581-8636-557cd150b0f1', '2026-04-08 01:23:06'),
('80989348-32e9-11f1-9448-e66239f31cb0', 'f5e9cc4e-2ef6-11f1-9448-e66239f31cb0', '43d5d6a2-e09d-4581-8636-557cd150b0f1', '2026-04-08 01:23:09'),
('82285dcb-32e9-11f1-9448-e66239f31cb0', 'f5e9cc4e-2ef6-11f1-9448-e66239f31cb0', '43d5d6a2-e09d-4581-8636-557cd150b0f1', '2026-04-08 01:23:11'),
('843716eb-3423-11f1-9448-e66239f31cb0', '5c703dba-2ef9-11f1-9448-e66239f31cb0', '43d5d6a2-e09d-4581-8636-557cd150b0f1', '2026-04-09 14:50:57'),
('86af1e52-32e9-11f1-9448-e66239f31cb0', '5c703dba-2ef9-11f1-9448-e66239f31cb0', '43d5d6a2-e09d-4581-8636-557cd150b0f1', '2026-04-08 01:23:19'),
('88aed0b2-32e9-11f1-9448-e66239f31cb0', '5c703dba-2ef9-11f1-9448-e66239f31cb0', '43d5d6a2-e09d-4581-8636-557cd150b0f1', '2026-04-08 01:23:22'),
('95589def-35cc-11f1-9448-e66239f31cb0', '5c703dba-2ef9-11f1-9448-e66239f31cb0', '43d5d6a2-e09d-4581-8636-557cd150b0f1', '2026-04-11 17:33:42'),
('9b418790-33ba-11f1-9448-e66239f31cb0', '5c703dba-2ef9-11f1-9448-e66239f31cb0', '43d5d6a2-e09d-4581-8636-557cd150b0f1', '2026-04-09 02:19:58'),
('9bc45b0b-35cc-11f1-9448-e66239f31cb0', 'f5e9cc4e-2ef6-11f1-9448-e66239f31cb0', '43d5d6a2-e09d-4581-8636-557cd150b0f1', '2026-04-11 17:33:52'),
('9ebaa424-33ba-11f1-9448-e66239f31cb0', 'f5e9cc4e-2ef6-11f1-9448-e66239f31cb0', '43d5d6a2-e09d-4581-8636-557cd150b0f1', '2026-04-09 02:20:04'),
('a94878bd-35cc-11f1-9448-e66239f31cb0', '5c703dba-2ef9-11f1-9448-e66239f31cb0', '43d5d6a2-e09d-4581-8636-557cd150b0f1', '2026-04-11 17:34:15'),
('b2172cd0-35f4-11f1-9448-e66239f31cb0', '5c703dba-2ef9-11f1-9448-e66239f31cb0', '43d5d6a2-e09d-4581-8636-557cd150b0f1', '2026-04-11 22:20:50'),
('b31280e0-3625-11f1-9448-e66239f31cb0', 'f5e9cc4e-2ef6-11f1-9448-e66239f31cb0', '0717139f-9fbc-4c8c-be98-cf1361e0036c', '2026-04-12 07:11:37'),
('b7fda575-35f4-11f1-9448-e66239f31cb0', 'f5e9cc4e-2ef6-11f1-9448-e66239f31cb0', '43d5d6a2-e09d-4581-8636-557cd150b0f1', '2026-04-11 22:21:00'),
('c1ddb6eb-35e9-11f1-9448-e66239f31cb0', 'f5e9cc4e-2ef6-11f1-9448-e66239f31cb0', '43d5d6a2-e09d-4581-8636-557cd150b0f1', '2026-04-11 21:02:32'),
('c380ee73-35e9-11f1-9448-e66239f31cb0', '5c703dba-2ef9-11f1-9448-e66239f31cb0', '43d5d6a2-e09d-4581-8636-557cd150b0f1', '2026-04-11 21:02:34'),
('c7c6e719-351b-11f1-9448-e66239f31cb0', '5c703dba-2ef9-11f1-9448-e66239f31cb0', '568753fd-bfa7-4920-a12a-36bd71277519', '2026-04-10 20:28:05'),
('cb2abd50-35cc-11f1-9448-e66239f31cb0', 'f5e9cc4e-2ef6-11f1-9448-e66239f31cb0', '43d5d6a2-e09d-4581-8636-557cd150b0f1', '2026-04-11 17:35:12'),
('cd21a9bc-35cc-11f1-9448-e66239f31cb0', '5c703dba-2ef9-11f1-9448-e66239f31cb0', '43d5d6a2-e09d-4581-8636-557cd150b0f1', '2026-04-11 17:35:15'),
('d27345ef-3625-11f1-9448-e66239f31cb0', '5c703dba-2ef9-11f1-9448-e66239f31cb0', '0717139f-9fbc-4c8c-be98-cf1361e0036c', '2026-04-12 07:12:29'),
('d8a013e1-3625-11f1-9448-e66239f31cb0', '5c703dba-2ef9-11f1-9448-e66239f31cb0', '0717139f-9fbc-4c8c-be98-cf1361e0036c', '2026-04-12 07:12:40'),
('e776bfa5-35cc-11f1-9448-e66239f31cb0', '5c703dba-2ef9-11f1-9448-e66239f31cb0', '43d5d6a2-e09d-4581-8636-557cd150b0f1', '2026-04-11 17:35:59'),
('ec0383ea-343e-11f1-9448-e66239f31cb0', 'f5e9cc4e-2ef6-11f1-9448-e66239f31cb0', '43d5d6a2-e09d-4581-8636-557cd150b0f1', '2026-04-09 18:07:07'),
('ee0d0b3f-35cc-11f1-9448-e66239f31cb0', 'f5e9cc4e-2ef6-11f1-9448-e66239f31cb0', '43d5d6a2-e09d-4581-8636-557cd150b0f1', '2026-04-11 17:36:10'),
('ee6912e7-343e-11f1-9448-e66239f31cb0', '5c703dba-2ef9-11f1-9448-e66239f31cb0', '43d5d6a2-e09d-4581-8636-557cd150b0f1', '2026-04-09 18:07:11'),
('ef079aa9-35cb-11f1-9448-e66239f31cb0', '5c703dba-2ef9-11f1-9448-e66239f31cb0', '43d5d6a2-e09d-4581-8636-557cd150b0f1', '2026-04-11 17:29:03'),
('f903a6d9-3338-11f1-9448-e66239f31cb0', 'f5e9cc4e-2ef6-11f1-9448-e66239f31cb0', '43d5d6a2-e09d-4581-8636-557cd150b0f1', '2026-04-08 10:52:01');

-- --------------------------------------------------------

--
-- Table structure for table `cart_items`
--

CREATE TABLE `cart_items` (
  `id` char(36) NOT NULL DEFAULT uuid(),
  `user_id` char(36) NOT NULL,
  `menu_item_id` char(36) NOT NULL,
  `quantity` int(10) UNSIGNED NOT NULL DEFAULT 1,
  `notes` text DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `cart_items`
--

INSERT INTO `cart_items` (`id`, `user_id`, `menu_item_id`, `quantity`, `notes`, `created_at`, `updated_at`) VALUES
('4bb4aa34-3659-11f1-9448-e66239f31cb0', '37c0e9a4-a2d3-4654-b58c-726f1cd4576b', 'f1010001-0001-4000-a000-000000000003', 1, NULL, '2026-04-12 13:20:57', '2026-04-12 13:20:57'),
('5253111e-3b2e-11f1-9448-e66239f31cb0', '5732093a-a1d5-46ba-9432-ac9f560ba03c', 'f2010001-0001-4000-b000-000000000001', 4, NULL, '2026-04-18 16:55:56', '2026-04-18 16:56:01'),
('55f9fee3-3b2e-11f1-9448-e66239f31cb0', '5732093a-a1d5-46ba-9432-ac9f560ba03c', 'f2010001-0001-4000-b000-000000000002', 1, NULL, '2026-04-18 16:56:02', '2026-04-18 16:56:02'),
('5c6d0586-3659-11f1-9448-e66239f31cb0', '37c0e9a4-a2d3-4654-b58c-726f1cd4576b', 'f2060001-0001-4000-b000-000000000003', 1, NULL, '2026-04-12 13:21:25', '2026-04-12 13:21:25'),
('5ce3fa42-3b2e-11f1-9448-e66239f31cb0', '5732093a-a1d5-46ba-9432-ac9f560ba03c', 'f2050001-0001-4000-b000-000000000001', 4, NULL, '2026-04-18 16:56:13', '2026-04-18 16:56:16'),
('61cbd939-3d8f-11f1-9448-e66239f31cb0', '0717139f-9fbc-4c8c-be98-cf1361e0036c', 'f2080001-0001-4000-b000-000000000001', 1, NULL, '2026-04-21 17:35:45', '2026-04-21 17:35:45'),
('afd163d5-3694-11f1-9448-e66239f31cb0', 'c547494a-e800-4b60-a42d-3109d2d5ba7f', 'f1030001-0001-4000-a000-000000000001', 1, NULL, '2026-04-12 20:26:05', '2026-04-12 20:26:05'),
('b6147b1a-3694-11f1-9448-e66239f31cb0', 'c547494a-e800-4b60-a42d-3109d2d5ba7f', 'f2010001-0001-4000-b000-000000000001', 2, NULL, '2026-04-12 20:26:16', '2026-04-12 22:22:56'),
('b82d2fd7-3694-11f1-9448-e66239f31cb0', 'c547494a-e800-4b60-a42d-3109d2d5ba7f', 'f1050001-0001-4000-a000-000000000001', 1, NULL, '2026-04-12 20:26:19', '2026-04-12 20:26:19'),
('bbf68b61-3694-11f1-9448-e66239f31cb0', 'c547494a-e800-4b60-a42d-3109d2d5ba7f', 'f2010001-0001-4000-b000-000000000003', 1, NULL, '2026-04-12 20:26:26', '2026-04-12 20:26:26');

-- --------------------------------------------------------

--
-- Table structure for table `customer_addresses`
--

CREATE TABLE `customer_addresses` (
  `id` char(36) NOT NULL DEFAULT uuid(),
  `user_id` char(36) NOT NULL,
  `label` varchar(50) NOT NULL COMMENT 'e.g. Home, Work, Other',
  `city` varchar(100) DEFAULT NULL,
  `neighborhood` varchar(100) DEFAULT NULL,
  `address_line` text NOT NULL,
  `latitude` decimal(10,8) DEFAULT NULL,
  `longitude` decimal(11,8) DEFAULT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `notes` text DEFAULT NULL,
  `is_default` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `customer_addresses`
--

INSERT INTO `customer_addresses` (`id`, `user_id`, `label`, `city`, `neighborhood`, `address_line`, `latitude`, `longitude`, `phone`, `notes`, `is_default`, `created_at`, `updated_at`) VALUES
('0304bc77-3d16-11f1-9448-e66239f31cb0', '1d8557e5-8a7d-4c32-8181-ad785ea979a0', 'مدينة دير البلح - منطقة حكر الجامع', 'مدينة دير البلح', 'منطقة حكر الجامع', 'الشروق', NULL, NULL, '01003640091', NULL, 1, '2026-04-21 03:06:57', '2026-04-21 04:37:36'),
('1be8995a-3d14-11f1-9448-e66239f31cb0', '568753fd-bfa7-4920-a12a-36bd71277519', 'مدينة دير البلح - منطقة حكر الجامع', 'مدينة دير البلح', 'منطقة حكر الجامع', 'برج الشروق', NULL, NULL, '01003640081', NULL, 0, '2026-04-21 02:53:20', '2026-04-21 02:53:20'),
('6d4e5235-36cf-11f1-9448-e66239f31cb0', '0717139f-9fbc-4c8c-be98-cf1361e0036c', 'ها', NULL, NULL, 'هنا', NULL, NULL, '01551130045', 'ماكو', 1, '2026-04-13 03:26:34', '2026-04-13 03:26:34'),
('71b95b50-32b8-11f1-9448-e66239f31cb0', '43d5d6a2-e09d-4581-8636-557cd150b0f1', 'دفع', NULL, NULL, 'غزة', NULL, NULL, '01003640080', 'است', 0, '2026-04-07 19:31:58', '2026-04-07 19:31:58'),
('9bdaa44d-3696-11f1-9448-e66239f31cb0', '5732093a-a1d5-46ba-9432-ac9f560ba03c', 'منزل', NULL, NULL, 'شارع النفق بجوار ستي ماركت', NULL, NULL, '0599261116', NULL, 1, '2026-04-12 20:39:51', '2026-04-12 20:39:51'),
('bd5f3a39-3d22-11f1-9448-e66239f31cb0', '1d8557e5-8a7d-4c32-8181-ad785ea979a0', 'مدينة دير البلح - منطقة حكر الجامع', 'مدينة دير البلح', 'منطقة حكر الجامع', 'غزة', NULL, NULL, '01003690096', NULL, 0, '2026-04-21 04:38:04', '2026-04-21 04:38:04'),
('d61e0134-31f9-11f1-9448-e66239f31cb0', '43d5d6a2-e09d-4581-8636-557cd150b0f1', 'منزل', NULL, NULL, 'غزة، شمال غزة', NULL, NULL, '01003640091', NULL, 1, '2026-04-06 20:47:33', '2026-04-07 19:29:08'),
('f3750a00-351b-11f1-9448-e66239f31cb0', '568753fd-bfa7-4920-a12a-36bd71277519', 'شمال غزة', NULL, NULL, 'شمال غزة', NULL, NULL, '01003640081', NULL, 1, '2026-04-10 20:29:19', '2026-04-10 20:29:19'),
('f4934cc7-3d24-11f1-9448-e66239f31cb0', '1d8557e5-8a7d-4c32-8181-ad785ea979a0', 'مدينة غزة - حي الرمال', 'مدينة غزة', 'حي الرمال', 'شمال', NULL, NULL, '01003690090', NULL, 0, '2026-04-21 04:53:55', '2026-04-21 04:53:55'),
('fcae03fa-3694-11f1-9448-e66239f31cb0', 'c547494a-e800-4b60-a42d-3109d2d5ba7f', 'حسنات', NULL, NULL, 'مفترق الصحابة مع النفق باتجاه السدرة اول شارع على ايدك الشمال على اول مفترق', NULL, NULL, '0592995063', '.', 0, '2026-04-12 20:28:14', '2026-04-12 20:28:14'),
('fdd42bc4-33a1-11f1-9448-e66239f31cb0', '43d5d6a2-e09d-4581-8636-557cd150b0f1', 'test', NULL, NULL, 'test', NULL, NULL, '01003690091', 'gs', 0, '2026-04-08 23:23:46', '2026-04-08 23:23:46');

-- --------------------------------------------------------

--
-- Table structure for table `customer_profiles`
--

CREATE TABLE `customer_profiles` (
  `id` char(36) NOT NULL DEFAULT uuid(),
  `user_id` char(36) NOT NULL,
  `profile_image_url` text DEFAULT NULL,
  `default_address_id` char(36) DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `delivery_zones`
--

CREATE TABLE `delivery_zones` (
  `id` char(36) NOT NULL DEFAULT uuid(),
  `name` varchar(100) NOT NULL,
  `min_distance_km` decimal(6,2) NOT NULL,
  `max_distance_km` decimal(6,2) NOT NULL,
  `base_fee` decimal(8,2) NOT NULL,
  `nearby_extra_fee` decimal(8,2) NOT NULL,
  `far_extra_fee` decimal(8,2) NOT NULL,
  `estimated_delivery_time` varchar(50) DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `delivery_zones`
--

INSERT INTO `delivery_zones` (`id`, `name`, `min_distance_km`, `max_distance_km`, `base_fee`, `nearby_extra_fee`, `far_extra_fee`, `estimated_delivery_time`, `is_active`, `created_at`) VALUES
('8f2827b0-1d9d-11f1-b82d-97044e7d8834', 'Zone 1 - Very Close (0 to 2 km)', 0.00, 2.00, 10.00, 5.00, 10.00, '10', 1, '2026-03-11 22:55:11'),
('8f2829db-1d9d-11f1-b82d-97044e7d8834', 'Zone 2 - Close (2 to 5 km)', 2.01, 5.00, 15.00, 5.00, 10.00, '30', 1, '2026-03-11 22:55:11'),
('8f282a59-1d9d-11f1-b82d-97044e7d8834', 'Zone 3 - Medium (5 to 10 km)', 5.01, 15.00, 20.00, 5.00, 10.00, NULL, 1, '2026-03-11 22:55:11'),
('8f282a83-1d9d-11f1-b82d-97044e7d8834', 'Zone 4 - Far (10 to 20 km)', 10.01, 20.00, 30.00, 5.00, 10.00, NULL, 1, '2026-03-11 22:55:11'),
('bc140619-2f03-11f1-9448-e66239f31cb0', 'test zone', 10.00, 25.00, 20.00, 5.00, 10.00, NULL, 1, '2026-04-03 02:20:51');

-- --------------------------------------------------------

--
-- Table structure for table `delivery_zone_pricing`
--

CREATE TABLE `delivery_zone_pricing` (
  `id` char(36) NOT NULL DEFAULT uuid(),
  `from_zone` enum('north','central','south') NOT NULL,
  `to_zone` enum('north','central','south') NOT NULL,
  `fee` decimal(8,2) NOT NULL DEFAULT 10.00,
  `updated_by` char(36) DEFAULT NULL,
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `delivery_zone_pricing`
--

INSERT INTO `delivery_zone_pricing` (`id`, `from_zone`, `to_zone`, `fee`, `updated_by`, `updated_at`) VALUES
('e996a762-3d13-11f1-9448-e66239f31cb0', 'north', 'north', 10.00, 'd8e13ad8-2382-11f1-b82d-97044e7d8834', '2026-04-21 03:00:43'),
('e996aa1d-3d13-11f1-9448-e66239f31cb0', 'north', 'central', 20.00, 'd8e13ad8-2382-11f1-b82d-97044e7d8834', '2026-04-21 03:00:44'),
('e996aac6-3d13-11f1-9448-e66239f31cb0', 'north', 'south', 25.00, 'd8e13ad8-2382-11f1-b82d-97044e7d8834', '2026-04-21 03:00:46'),
('e996ab04-3d13-11f1-9448-e66239f31cb0', 'central', 'north', 20.00, 'd8e13ad8-2382-11f1-b82d-97044e7d8834', '2026-04-21 03:00:44'),
('e996ab3d-3d13-11f1-9448-e66239f31cb0', 'central', 'central', 10.00, 'd8e13ad8-2382-11f1-b82d-97044e7d8834', '2026-04-21 03:00:43'),
('e996ab73-3d13-11f1-9448-e66239f31cb0', 'central', 'south', 20.00, 'd8e13ad8-2382-11f1-b82d-97044e7d8834', '2026-04-21 03:00:45'),
('e996aba7-3d13-11f1-9448-e66239f31cb0', 'south', 'north', 25.00, 'd8e13ad8-2382-11f1-b82d-97044e7d8834', '2026-04-21 03:00:46'),
('e996abd8-3d13-11f1-9448-e66239f31cb0', 'south', 'central', 20.00, 'd8e13ad8-2382-11f1-b82d-97044e7d8834', '2026-04-21 03:00:45'),
('e996ac10-3d13-11f1-9448-e66239f31cb0', 'south', 'south', 10.00, 'd8e13ad8-2382-11f1-b82d-97044e7d8834', '2026-04-21 03:00:43');

-- --------------------------------------------------------

--
-- Table structure for table `email_verifications`
--

CREATE TABLE `email_verifications` (
  `id` char(36) NOT NULL DEFAULT uuid(),
  `email` varchar(150) NOT NULL,
  `otp` varchar(10) NOT NULL,
  `type` enum('signup','reset_password') NOT NULL,
  `expires_at` datetime NOT NULL,
  `verified` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `email_verifications`
--

INSERT INTO `email_verifications` (`id`, `email`, `otp`, `type`, `expires_at`, `verified`, `created_at`) VALUES
('0425d483-368c-11f1-9448-e66239f31cb0', 'lwymdwkh@gmail.com', '897969', 'signup', '2026-04-12 19:54:01', 0, '2026-04-12 19:24:01'),
('07b1b784-31cc-11f1-9448-e66239f31cb0', 'flutter1developer1@gmail.com', '910247', 'reset_password', '2026-04-06 15:49:39', 0, '2026-04-06 15:19:39'),
('08319428-33a3-11f1-9448-e66239f31cb0', 'flutter1developer1@gmail.com', '649661', 'reset_password', '2026-04-09 00:01:13', 0, '2026-04-08 23:31:13'),
('12188d59-3609-11f1-9448-e66239f31cb0', 'mosutafaashraf809@gmail.com', '790626', 'signup', '2026-04-12 01:16:41', 0, '2026-04-12 03:46:41'),
('23bd90b0-3612-11f1-9448-e66239f31cb0', 'ashrafyasser7770@gmail.com', '709502', 'signup', '2026-04-12 05:21:36', 1, '2026-04-12 04:51:36'),
('312925ff-369c-11f1-9448-e66239f31cb0', 'samahadas66@gmail.com', '565848', 'signup', '2026-04-12 21:49:49', 1, '2026-04-12 21:19:49'),
('36af335e-368c-11f1-9448-e66239f31cb0', 'lwymdwkh@gmail.com', '500112', 'signup', '2026-04-12 19:55:26', 1, '2026-04-12 19:25:26'),
('37251c87-31f4-11f1-9448-e66239f31cb0', 'flutter1developer1@gmail.com', '457015', 'reset_password', '2026-04-06 20:37:19', 0, '2026-04-06 20:07:19'),
('3e6ff2e9-3649-11f1-9448-e66239f31cb0', 'abdurhmanhishaam@gmail.com', '253697', 'signup', '2026-04-12 11:56:03', 1, '2026-04-12 11:26:03'),
('4a6b3d62-3609-11f1-9448-e66239f31cb0', 'moustafaashraf809@gmail.com', '557954', 'signup', '2026-04-12 01:18:15', 0, '2026-04-12 03:48:15'),
('4ac192dc-33a3-11f1-9448-e66239f31cb0', 'mofo650@gmail.com', '680236', 'signup', '2026-04-09 00:03:05', 0, '2026-04-08 23:33:05'),
('4b593db7-31cb-11f1-9448-e66239f31cb0', 'flutter1developer1@gmail.com', '498159', 'signup', '2026-04-06 15:44:23', 1, '2026-04-06 15:14:23'),
('50282958-1db4-11f1-b82d-97044e7d8834', 'test_user_013803@test.com', '740125', 'signup', '2026-03-12 02:08:04', 0, '2026-03-12 01:38:04'),
('5076e305-1db4-11f1-b82d-97044e7d8834', 'test_user_013803@test.com', '166767', 'signup', '2026-03-12 02:08:04', 0, '2026-03-12 01:38:04'),
('5384938b-368f-11f1-9448-e66239f31cb0', 'samayazan33@gmail.com', '395724', 'signup', '2026-04-12 20:17:43', 1, '2026-04-12 19:47:43'),
('627c3523-3d15-11f1-9448-e66239f31cb0', 'sehamhamdy909@gmail.com', '969136', 'signup', '2026-04-21 03:32:28', 1, '2026-04-21 03:02:28'),
('69f2d38b-33a3-11f1-9448-e66239f31cb0', 'mofo6050@gmail.com', '689592', 'signup', '2026-04-09 00:03:57', 1, '2026-04-08 23:33:57'),
('7309cc5c-3696-11f1-9448-e66239f31cb0', 'Kareemaboshady949@gmail.com', '654577', 'signup', '2026-04-12 21:08:42', 1, '2026-04-12 20:38:42'),
('7a61cc16-2ef2-11f1-9448-e66239f31cb0', 'gaberyasser555@gmail.com', '649784', 'reset_password', '2026-04-03 00:47:19', 0, '2026-04-03 00:17:19'),
('7d36ec15-3609-11f1-9448-e66239f31cb0', 'sehamhamdy809@gmail.com', '176664', 'signup', '2026-04-12 01:19:40', 0, '2026-04-12 03:49:40'),
('7d8d0c9d-3625-11f1-9448-e66239f31cb0', 'hamza127444@gmail.com', '497353', 'signup', '2026-04-12 07:40:07', 1, '2026-04-12 07:10:07'),
('84b4fdbd-3d8e-11f1-9448-e66239f31cb0', 'hamza127444@gmail.com', '375750', 'reset_password', '2026-04-21 17:59:34', 0, '2026-04-21 17:29:34'),
('8b5b2b1c-26f3-11f1-abf9-e1b0d104a220', 'yasergaber143210@gmail.com', '918299', 'reset_password', '2026-03-23 20:34:48', 0, '2026-03-23 20:04:48'),
('8f907b26-351b-11f1-9448-e66239f31cb0', 'yasserashraf3142@gmail.com', '527834', 'signup', '2026-04-10 20:56:31', 1, '2026-04-10 20:26:31'),
('9d543a16-3609-11f1-9448-e66239f31cb0', 'ashrafyasser7770@gmail.com', '405763', 'signup', '2026-04-12 01:20:34', 0, '2026-04-12 03:50:34'),
('a0b39525-1db5-11f1-b82d-97044e7d8834', 'test_user_014728@test.com', '738226', 'signup', '2026-03-12 02:17:28', 0, '2026-03-12 01:47:28'),
('a0fbe16e-1db5-11f1-b82d-97044e7d8834', 'test_user_014728@test.com', '719438', 'signup', '2026-03-12 02:17:29', 0, '2026-03-12 01:47:29'),
('b28c48da-2313-11f1-b82d-97044e7d8834', 'yasserashraf3142@gmail.com', '880769', 'reset_password', '2026-03-18 22:13:27', 0, '2026-03-18 21:43:27'),
('b2b18691-31ca-11f1-9448-e66239f31cb0', 'yassergaber1432@gmail.com', '632746', 'signup', '2026-04-06 15:40:07', 0, '2026-04-06 15:10:07'),
('ba575521-1dac-11f1-b82d-97044e7d8834', 'test_user_004346@test.com', '106146', 'signup', '2026-03-12 01:13:46', 0, '2026-03-12 00:43:46'),
('ba9f8712-1dac-11f1-b82d-97044e7d8834', 'test_user_004346@test.com', '951563', 'signup', '2026-03-12 01:13:46', 0, '2026-03-12 00:43:46'),
('cfc0b983-3693-11f1-9448-e66239f31cb0', 'mhmwdhsnat51@gmail.com', '473062', 'signup', '2026-04-12 20:49:49', 0, '2026-04-12 20:19:49'),
('e169bd19-3c4e-11f1-9448-e66239f31cb0', 'yasserashraf3142@gmail.com', '566600', 'reset_password', '2026-04-20 03:51:31', 0, '2026-04-20 03:21:31'),
('ee32c8e2-3754-11f1-9448-e66239f31cb0', 'waeeel989@gmail.com', '863698', 'signup', '2026-04-13 19:52:13', 1, '2026-04-13 19:22:13'),
('f3f97f34-31ca-11f1-9448-e66239f31cb0', 'yasergaber1432@gmail.com', '771274', 'signup', '2026-04-06 15:41:57', 0, '2026-04-06 15:11:57'),
('f6584e2e-3693-11f1-9448-e66239f31cb0', 'mhmwdhsnat51@gmail.com', '221150', 'signup', '2026-04-12 20:50:54', 1, '2026-04-12 20:20:54');

-- --------------------------------------------------------

--
-- Table structure for table `favorites`
--

CREATE TABLE `favorites` (
  `id` char(36) NOT NULL DEFAULT uuid(),
  `user_id` char(36) NOT NULL,
  `type` enum('vendor','item') NOT NULL,
  `target_id` char(36) NOT NULL COMMENT 'vendor_id or menu_item_id',
  `created_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `favorites`
--

INSERT INTO `favorites` (`id`, `user_id`, `type`, `target_id`, `created_at`) VALUES
('7424707f-347b-11f1-9448-e66239f31cb0', '43d5d6a2-e09d-4581-8636-557cd150b0f1', 'vendor', 'e4a9a3cd-26f4-11f1-abf9-e1b0d104a220', '2026-04-10 01:20:25'),
('74ff56d3-36d0-11f1-9448-e66239f31cb0', '0717139f-9fbc-4c8c-be98-cf1361e0036c', 'item', 'f2040001-0001-4000-b000-000000000003', '2026-04-13 03:33:56');

-- --------------------------------------------------------

--
-- Table structure for table `fcm_tokens`
--

CREATE TABLE `fcm_tokens` (
  `id` char(36) NOT NULL DEFAULT uuid(),
  `user_id` char(36) NOT NULL,
  `token` text NOT NULL,
  `device_type` enum('android','ios','web') DEFAULT 'android',
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `fcm_tokens`
--

INSERT INTO `fcm_tokens` (`id`, `user_id`, `token`, `device_type`, `created_at`, `updated_at`) VALUES
('00b99864-3474-11f1-9448-e66239f31cb0', '8f266f22-1d9d-11f1-b82d-97044e7d8834', 'd5Fj6R21Sr6ExrYoY_AeLu:APA91bFUmDzxhQQ626ywGkVsABnIwESkGwwE3ZaaK3W6Gm_aEmyj_kIEavwjbCZ9B0qt4fD_y8nV0ImiLcGavrhv52EP7nFimMOb8xB-aCHFX-4JW0coDM4', 'android', '2026-04-10 00:27:05', '2026-04-10 04:47:27'),
('06661fee-3d21-11f1-9448-e66239f31cb0', 'e64dd274-3d20-11f1-9448-e66239f31cb0', 'dJQwyCfFQziYEip4j5ftLs:APA91bEBES0e-6kFqGw1uq3TRWrBarSTM95q074aG3XHfymJLaRpTanF-Tg7hYV6NHBM2vfO0KnMR0EVfeBWm_qs7F5bpXefKemrugH4nVlzefX6M7QwQUM', 'android', '2026-04-21 04:25:47', '2026-04-21 18:44:30'),
('06b01c9e-360d-11f1-9448-e66239f31cb0', '43d5d6a2-e09d-4581-8636-557cd150b0f1', 'dXBAx3qAQYiliSV4k8oYuC:APA91bHomjOgO4KVZs6urZBuYE2FoJMqdsoAGqcap8Jn_0kyTRCqu7PbBRXP3s7aGrJ8gEIAv4DPwOchXClkMo9TqMewrPj3OxKPE2CsKTjDfjvEBsdjYjg', 'android', '2026-04-12 04:14:59', '2026-04-12 04:49:51'),
('0b6a9801-35cb-11f1-9448-e66239f31cb0', 'be9db6e5-2efe-11f1-9448-e66239f31cb0', 'dJQwyCfFQziYEip4j5ftLs:APA91bEBES0e-6kFqGw1uq3TRWrBarSTM95q074aG3XHfymJLaRpTanF-Tg7hYV6NHBM2vfO0KnMR0EVfeBWm_qs7F5bpXefKemrugH4nVlzefX6M7QwQUM', 'android', '2026-04-11 17:22:41', '2026-04-11 23:05:10'),
('110e8f93-361c-11f1-9448-e66239f31cb0', '43d5d6a2-e09d-4581-8636-557cd150b0f1', 'cSiL5wNWQH-l_V493uvyST:APA91bFqgxKxlQQxVRYDIfXn1WVV2gRrZGLuXnN4a0Fg74Z7_RP0EkBf3ux5ZAVNT5PSuvnzKNVz6aL2MAv2-RL1WIPXKwCpPnL0kpAXaKIP2NRYG4GPp5w', 'android', '2026-04-12 06:02:39', '2026-04-12 22:33:27'),
('1895a146-3d21-11f1-9448-e66239f31cb0', 'f52cc98d-3d20-11f1-9448-e66239f31cb0', 'dJQwyCfFQziYEip4j5ftLs:APA91bEBES0e-6kFqGw1uq3TRWrBarSTM95q074aG3XHfymJLaRpTanF-Tg7hYV6NHBM2vfO0KnMR0EVfeBWm_qs7F5bpXefKemrugH4nVlzefX6M7QwQUM', 'android', '2026-04-21 04:26:18', '2026-04-21 18:43:33'),
('19d81174-3755-11f1-9448-e66239f31cb0', '56b3aab6-d530-419b-9e84-afb3c9db0063', 'faWhp2LjTzmlGHeyNL9C3U:APA91bEerYek-XEwVkn8jrjLcZSD3f_zRv9boPluvvr8IBfFzk8qEhLdAmhbXgg1DjEv2oTRDsxOB32juJDIh9UAOOM5p51kP5_NwaftEcixYNC8efAebAw', 'android', '2026-04-13 19:23:27', '2026-04-13 19:23:27'),
('1f95aa64-343f-11f1-9448-e66239f31cb0', 'd8e13ad8-2382-11f1-b82d-97044e7d8834', 'exHldDxSSlifWMzAs4sdei:APA91bErwuO2GpCLCYFtq3ni42vitKoQ50pr9D3AZ8I-fhYdyL7gNdt5Kld-5Z5JU60swdHyhhDtnTh7srwijXAvVwYUxqdUT3XQxCeIz5UbsiVyN4TbOQE', 'android', '2026-04-09 18:08:34', '2026-04-09 21:58:05'),
('20c599f1-3475-11f1-9448-e66239f31cb0', '4f792c75-4a65-45a7-a0ef-d282acd83d87', 'd5Fj6R21Sr6ExrYoY_AeLu:APA91bFUmDzxhQQ626ywGkVsABnIwESkGwwE3ZaaK3W6Gm_aEmyj_kIEavwjbCZ9B0qt4fD_y8nV0ImiLcGavrhv52EP7nFimMOb8xB-aCHFX-4JW0coDM4', 'android', '2026-04-10 00:35:09', '2026-04-10 04:46:51'),
('23776c64-34a4-11f1-9448-e66239f31cb0', '43d5d6a2-e09d-4581-8636-557cd150b0f1', 'd3FZpzTUT9SvhME4Ij2irm:APA91bGnJdy1oD-enYFzjjSsJzKqyANkBwK6dxhODINDxoogYYVvpC-ElsoMs3kGg0FoVtUZ_gHyEv6cwu9z7aBz5HVsTi10bWKnrYwO5xl1LMAGVIxVV2A', 'android', '2026-04-10 06:11:39', '2026-04-10 19:57:00'),
('260c56cd-3c4f-11f1-9448-e66239f31cb0', '568753fd-bfa7-4920-a12a-36bd71277519', 'dMMSeM3dQ0eEaJpwMZbikl:APA91bELJJKJJvTtVpt915axIuAEULjS-HUBU0HYfVO8PhQPmMRp0dA9MlJSp5bbIzL_rJzYvJzUujxeo1MnF6FSJTGx-7c--pSyHnVrceiw3gXE9kYJZ7o', 'android', '2026-04-20 03:23:26', '2026-04-21 02:52:05'),
('265c83fa-3694-11f1-9448-e66239f31cb0', 'c547494a-e800-4b60-a42d-3109d2d5ba7f', 'cA1TkIu9SAOvTr1Vr-GSps:APA91bFXj8qRiqewB76GWTdcvrpCikc-sRk_CY75hZ2SFiuPcCURNKkD7whvkP7mzb-UVECy3rbm1ObqM48iE2zsd46hdQRf5-6Hz_1s0hTLd0QNu6vNgI8', 'android', '2026-04-12 20:22:15', '2026-04-12 22:20:38'),
('30ac1bc8-3583-11f1-9448-e66239f31cb0', '43d5d6a2-e09d-4581-8636-557cd150b0f1', 'd1oj0oKSSs2TnlZNPhKcBe:APA91bFHzzsqxTfXjxn1Vuey7TrUM1z8-82-BZkX2oazgcpguH4N8L__CSUpwuGiKh0RMSHfmUw0me5sc7nvsx3f8dEJsNulrmbi0ybmfTI3c1zMo-89qEc', 'android', '2026-04-11 08:48:19', '2026-04-11 16:18:13'),
('32589655-35cb-11f1-9448-e66239f31cb0', '8f266f22-1d9d-11f1-b82d-97044e7d8834', 'dJQwyCfFQziYEip4j5ftLs:APA91bEBES0e-6kFqGw1uq3TRWrBarSTM95q074aG3XHfymJLaRpTanF-Tg7hYV6NHBM2vfO0KnMR0EVfeBWm_qs7F5bpXefKemrugH4nVlzefX6M7QwQUM', 'android', '2026-04-11 17:23:46', '2026-04-21 18:31:55'),
('47210cb4-3473-11f1-9448-e66239f31cb0', '43d5d6a2-e09d-4581-8636-557cd150b0f1', 'cnS9lJPOSTiKvxmbJWDYRQ:APA91bHxCLM8S_Z1BmvDQmXULkh-Jw5c1TUJkJ8hjiSejsdArGbXkQAHHr8ufGUc_ir3FgxaNhVXqyZqRaRmUreYZRtZgKvuTcWhDsSzhjorhDbHX1Htfxg', 'android', '2026-04-10 00:21:54', '2026-04-10 02:14:17'),
('4997ae31-368c-11f1-9448-e66239f31cb0', '5732093a-a1d5-46ba-9432-ac9f560ba03c', 'fz0uh9L1TP2f5yGPcgnYHu:APA91bHxprGY36MwLplwX3CPTNdfNSz2JuQtwOe6TgYGU0_-4VCwtBCVaprSokuH9HzFF16vNnw2J1dkYYTTZlerXWpU8F8q8gG8VwBTJONgKml-qT5vRio', 'android', '2026-04-12 19:25:58', '2026-04-18 16:55:35'),
('4e85b80f-3612-11f1-9448-e66239f31cb0', '9be6c6f3-9ffa-4d9e-b4d7-2c48fff4fa6f', 'dXBAx3qAQYiliSV4k8oYuC:APA91bHomjOgO4KVZs6urZBuYE2FoJMqdsoAGqcap8Jn_0kyTRCqu7PbBRXP3s7aGrJ8gEIAv4DPwOchXClkMo9TqMewrPj3OxKPE2CsKTjDfjvEBsdjYjg', 'android', '2026-04-12 04:52:47', '2026-04-12 05:17:20'),
('4f74a5d7-35cb-11f1-9448-e66239f31cb0', '80c0bb8b-082d-4810-806f-0e1901d9d787', 'dJQwyCfFQziYEip4j5ftLs:APA91bEBES0e-6kFqGw1uq3TRWrBarSTM95q074aG3XHfymJLaRpTanF-Tg7hYV6NHBM2vfO0KnMR0EVfeBWm_qs7F5bpXefKemrugH4nVlzefX6M7QwQUM', 'android', '2026-04-11 17:24:35', '2026-04-11 23:01:19'),
('5491ad98-3649-11f1-9448-e66239f31cb0', '37c0e9a4-a2d3-4654-b58c-726f1cd4576b', 'fikyKhCGQkyU4p7BZNUmrl:APA91bGm6katGK7Z1USV_Tg85WQsm0G6oLBoEJkbjF0tZ0PpMdbcAo-EPFunrhJowrxZIHAaxiYS3evDwgdfSS4zyF_dnp89GPrGcz9p_6qsSIN377Y82us', 'android', '2026-04-12 11:26:40', '2026-04-18 12:40:19'),
('5da54882-342d-11f1-9448-e66239f31cb0', '43d5d6a2-e09d-4581-8636-557cd150b0f1', 'deBYh-OpRJu8-q9yXkXJps:APA91bF8tZ4anjwf4rgQ1ztZP8Pv5PCUWntYyBoNx_-DT2FFuV1PvVKyPZW4av-yivsnO1bLbo8v8bvo-rKaN3cF87JUQn90BGiRPwGo5QQOZrZD-xhn4go', 'android', '2026-04-09 16:01:27', '2026-04-09 21:32:24'),
('5da6317f-369c-11f1-9448-e66239f31cb0', '47ed0b4b-e41f-49d7-8044-087a7200111b', 'dQpXkJoQRfuciVRowC4otv:APA91bE-DiPYnd4fIYqBMZ3oAZ6jeP23iBjqRPPR9e4YMBX2c6WhapSb-mvlE2wxVs1iYTgTTOChOYRuMm7i1JC9LjphYP_td0YiiaIlMbVhI-Y0WWVOYDw', 'android', '2026-04-12 21:21:03', '2026-04-18 16:04:33'),
('6d5f679d-368f-11f1-9448-e66239f31cb0', '912da94f-052d-4315-ac62-913792533a89', 'eSziuPrESVKkz_6covtCeq:APA91bFHN0v4aKjEq0OF-eeF7vY445g9pSHVfTJLG8spdHrb3uhkEcxrywBh5_WWtRP9_MchQJwLC_GZTecwTWL3JNsQYw1FNMR8ZbrMf9drz31uauOcjNc', 'android', '2026-04-12 19:48:26', '2026-04-12 19:48:26'),
('6da18f01-3510-11f1-9448-e66239f31cb0', '80c0bb8b-082d-4810-806f-0e1901d9d787', 'dBkQcMWvSESZjLHcq9117b:APA91bHwNVJaS1mtnWeGrsiCUCJybA7qyzFUvg_8WDFF8lYfmHJ1OjQxE_KmSUwTk2iNO186fyVCMKCmtXRIM5Oy-puuk2Ol4wvxPVBVA1bEOLlRHikGRM4', 'android', '2026-04-10 19:06:50', '2026-04-10 21:45:06'),
('71abe4d6-34a4-11f1-9448-e66239f31cb0', '4f792c75-4a65-45a7-a0ef-d282acd83d87', 'dBkQcMWvSESZjLHcq9117b:APA91bHwNVJaS1mtnWeGrsiCUCJybA7qyzFUvg_8WDFF8lYfmHJ1OjQxE_KmSUwTk2iNO186fyVCMKCmtXRIM5Oy-puuk2Ol4wvxPVBVA1bEOLlRHikGRM4', 'android', '2026-04-10 06:13:51', '2026-04-11 10:40:21'),
('72dd9290-38f1-11f1-9448-e66239f31cb0', '43d5d6a2-e09d-4581-8636-557cd150b0f1', 'dMMSeM3dQ0eEaJpwMZbikl:APA91bELJJKJJvTtVpt915axIuAEULjS-HUBU0HYfVO8PhQPmMRp0dA9MlJSp5bbIzL_rJzYvJzUujxeo1MnF6FSJTGx-7c--pSyHnVrceiw3gXE9kYJZ7o', 'android', '2026-04-15 20:35:09', '2026-04-20 03:15:38'),
('744e04cb-35cb-11f1-9448-e66239f31cb0', 'b8175b08-34e6-4f5d-87d3-4c777677db33', 'dJQwyCfFQziYEip4j5ftLs:APA91bEBES0e-6kFqGw1uq3TRWrBarSTM95q074aG3XHfymJLaRpTanF-Tg7hYV6NHBM2vfO0KnMR0EVfeBWm_qs7F5bpXefKemrugH4nVlzefX6M7QwQUM', 'android', '2026-04-11 17:25:37', '2026-04-12 05:57:43'),
('78c4c3ca-34a6-11f1-9448-e66239f31cb0', '8f266f22-1d9d-11f1-b82d-97044e7d8834', 'dBkQcMWvSESZjLHcq9117b:APA91bHwNVJaS1mtnWeGrsiCUCJybA7qyzFUvg_8WDFF8lYfmHJ1OjQxE_KmSUwTk2iNO186fyVCMKCmtXRIM5Oy-puuk2Ol4wvxPVBVA1bEOLlRHikGRM4', 'android', '2026-04-10 06:28:22', '2026-04-10 21:45:29'),
('790af345-34a3-11f1-9448-e66239f31cb0', 'd8e13ad8-2382-11f1-b82d-97044e7d8834', 'dBkQcMWvSESZjLHcq9117b:APA91bHwNVJaS1mtnWeGrsiCUCJybA7qyzFUvg_8WDFF8lYfmHJ1OjQxE_KmSUwTk2iNO186fyVCMKCmtXRIM5Oy-puuk2Ol4wvxPVBVA1bEOLlRHikGRM4', 'android', '2026-04-10 06:06:54', '2026-04-11 08:45:50'),
('7a01b7ac-35c9-11f1-9448-e66239f31cb0', 'cf2239d2-2dd7-4116-abd3-1f24d48e8fe7', 'dJQwyCfFQziYEip4j5ftLs:APA91bEBES0e-6kFqGw1uq3TRWrBarSTM95q074aG3XHfymJLaRpTanF-Tg7hYV6NHBM2vfO0KnMR0EVfeBWm_qs7F5bpXefKemrugH4nVlzefX6M7QwQUM', 'android', '2026-04-11 17:11:27', '2026-04-11 23:00:18'),
('7a5a54c6-3d15-11f1-9448-e66239f31cb0', '1d8557e5-8a7d-4c32-8181-ad785ea979a0', 'cM8mwkiBSS6Qh8wktcYz0u:APA91bFh8sLD2QikzcOWVKbB9wekxy3SKGcF7rlTpw0Td9Dep5lxb8h4ZyDc8SzYJLvF5nofgMmj7J5hsFTB2E-tMm0cc9cAZGnCp_ctQZ26idz6pspzilA', 'android', '2026-04-21 03:03:08', '2026-04-21 18:56:24'),
('83e92511-3d21-11f1-9448-e66239f31cb0', '68d947ef-fcec-426a-8316-155666848619', 'dJQwyCfFQziYEip4j5ftLs:APA91bEBES0e-6kFqGw1uq3TRWrBarSTM95q074aG3XHfymJLaRpTanF-Tg7hYV6NHBM2vfO0KnMR0EVfeBWm_qs7F5bpXefKemrugH4nVlzefX6M7QwQUM', 'android', '2026-04-21 04:29:18', '2026-04-21 18:42:52'),
('842e35c7-3511-11f1-9448-e66239f31cb0', 'be9db6e5-2efe-11f1-9448-e66239f31cb0', 'dBkQcMWvSESZjLHcq9117b:APA91bHwNVJaS1mtnWeGrsiCUCJybA7qyzFUvg_8WDFF8lYfmHJ1OjQxE_KmSUwTk2iNO186fyVCMKCmtXRIM5Oy-puuk2Ol4wvxPVBVA1bEOLlRHikGRM4', 'android', '2026-04-10 19:14:37', '2026-04-10 21:44:26'),
('849510c4-3469-11f1-9448-e66239f31cb0', '8f266f22-1d9d-11f1-b82d-97044e7d8834', 'drr0eeSBRX-PiTcHL__cDR:APA91bEF5Fzi3q34syFuKH9pSZAC0i-e_a9c9r6TgBlBAjY6-Fg5s7ig3unVu9ohdIk5mXO7AJlUzV9AN9TAhS1qCrk0QEHyUHXG-5XJUVLfPmq6ylRhtJI', 'android', '2026-04-09 23:12:02', '2026-04-09 23:12:02'),
('8c57ba3d-343f-11f1-9448-e66239f31cb0', '8f266f22-1d9d-11f1-b82d-97044e7d8834', 'exHldDxSSlifWMzAs4sdei:APA91bErwuO2GpCLCYFtq3ni42vitKoQ50pr9D3AZ8I-fhYdyL7gNdt5Kld-5Z5JU60swdHyhhDtnTh7srwijXAvVwYUxqdUT3XQxCeIz5UbsiVyN4TbOQE', 'android', '2026-04-09 18:11:36', '2026-04-09 21:59:39'),
('8f1aee5c-3696-11f1-9448-e66239f31cb0', '33ba298c-7bd8-4338-b16b-3817a328e8ed', 'd8HDvVgvRNWdcnVnuuJo8n:APA91bGjEq6FxF0lvI4qx9mtpR0XP9bckLkbTzx5BYNHJMVfvneYadndte1q6SVQ2bukIhauC1WxRdfsDcweJ2BdUJVtF3YJ6t_fx2KgpgIE1hQPhIg9RzU', 'android', '2026-04-12 20:39:29', '2026-04-14 02:04:09'),
('97999a8f-35c8-11f1-9448-e66239f31cb0', '4f792c75-4a65-45a7-a0ef-d282acd83d87', 'dJQwyCfFQziYEip4j5ftLs:APA91bEBES0e-6kFqGw1uq3TRWrBarSTM95q074aG3XHfymJLaRpTanF-Tg7hYV6NHBM2vfO0KnMR0EVfeBWm_qs7F5bpXefKemrugH4nVlzefX6M7QwQUM', 'android', '2026-04-11 17:05:07', '2026-04-21 04:26:32'),
('a0d540b2-3d97-11f1-9448-e66239f31cb0', '6255a20b-3d97-11f1-9448-e66239f31cb0', 'dJQwyCfFQziYEip4j5ftLs:APA91bEBES0e-6kFqGw1uq3TRWrBarSTM95q074aG3XHfymJLaRpTanF-Tg7hYV6NHBM2vfO0KnMR0EVfeBWm_qs7F5bpXefKemrugH4nVlzefX6M7QwQUM', 'android', '2026-04-21 18:34:47', '2026-04-21 18:43:57'),
('a3d22071-35cd-11f1-9448-e66239f31cb0', 'd8e13ad8-2382-11f1-b82d-97044e7d8834', 'dJQwyCfFQziYEip4j5ftLs:APA91bEBES0e-6kFqGw1uq3TRWrBarSTM95q074aG3XHfymJLaRpTanF-Tg7hYV6NHBM2vfO0KnMR0EVfeBWm_qs7F5bpXefKemrugH4nVlzefX6M7QwQUM', 'android', '2026-04-11 17:41:15', '2026-04-21 02:59:54'),
('a85b2580-35e7-11f1-9448-e66239f31cb0', '43d5d6a2-e09d-4581-8636-557cd150b0f1', 'eH4tGbyjR-ivRu6s45IDLy:APA91bFveGQgyFeDJdnH_RY64oVeTMh3RYFS4_G6bh0_9VWnqmxCjsZL5vr7DjArTNxi82ynIwAM5-QHExwSNtqcPMoeRP9pnxdHJkbJq08weLtgw7EZPmw', 'android', '2026-04-11 20:47:30', '2026-04-12 03:44:25'),
('aaa76c66-351b-11f1-9448-e66239f31cb0', '568753fd-bfa7-4920-a12a-36bd71277519', 'd1oj0oKSSs2TnlZNPhKcBe:APA91bFHzzsqxTfXjxn1Vuey7TrUM1z8-82-BZkX2oazgcpguH4N8L__CSUpwuGiKh0RMSHfmUw0me5sc7nvsx3f8dEJsNulrmbi0ybmfTI3c1zMo-89qEc', 'android', '2026-04-10 20:27:16', '2026-04-11 08:47:55'),
('ade7c1d0-35c2-11f1-9448-e66239f31cb0', '43d5d6a2-e09d-4581-8636-557cd150b0f1', 'dJItEZtMTpqICpJA4OVfnW:APA91bFdqVO5kejJqE76dCJ15YObnSrAzL62jrmE7rQo92ySWscTJ5LH_j-PYLoxNKHIgeeRc37M5HFeRGfMiwrA0stnDhfaBgDqJvo06s7Ulbsm74Kw7B0', 'android', '2026-04-11 16:22:48', '2026-04-11 20:25:00'),
('b0109b94-3625-11f1-9448-e66239f31cb0', '0717139f-9fbc-4c8c-be98-cf1361e0036c', 'dUqACr5-SlOEjHQ4k-Xxd-:APA91bHNlaUy2aF6A2v_GlsO_R_BVm8ihicqAzZBUow3IpcNjWmlAC2KEK0HpCyzt3Q07PhqOW37q40dMv6zdpevQNAyTpLVII9ifuCJUzvOWy_gKkktofo', 'android', '2026-04-12 07:11:32', '2026-04-21 17:30:17'),
('b16d5b49-3466-11f1-9448-e66239f31cb0', '43d5d6a2-e09d-4581-8636-557cd150b0f1', 'eywKrojdTNOHBOuoTeYjYe:APA91bE4Xsq2c0_vnYJTH4jVSxSTU0lWI3ugvprOTxcwy9QTMSqA-GaTV0JHdIPXuku0-x8fB0j4QF7_WsYmrDSfwQ8DOhI53FfUhb3AHFkUjV0HcJS83f0', 'android', '2026-04-09 22:51:49', '2026-04-09 22:54:28'),
('be8ba8d9-3510-11f1-9448-e66239f31cb0', 'b8175b08-34e6-4f5d-87d3-4c777677db33', 'dBkQcMWvSESZjLHcq9117b:APA91bHwNVJaS1mtnWeGrsiCUCJybA7qyzFUvg_8WDFF8lYfmHJ1OjQxE_KmSUwTk2iNO186fyVCMKCmtXRIM5Oy-puuk2Ol4wvxPVBVA1bEOLlRHikGRM4', 'android', '2026-04-10 19:09:05', '2026-04-10 19:09:05'),
('c7db84ab-3466-11f1-9448-e66239f31cb0', 'd8e13ad8-2382-11f1-b82d-97044e7d8834', 'drr0eeSBRX-PiTcHL__cDR:APA91bEF5Fzi3q34syFuKH9pSZAC0i-e_a9c9r6TgBlBAjY6-Fg5s7ig3unVu9ohdIk5mXO7AJlUzV9AN9TAhS1qCrk0QEHyUHXG-5XJUVLfPmq6ylRhtJI', 'android', '2026-04-09 22:52:26', '2026-04-09 23:11:27'),
('c7de4574-3d14-11f1-9448-e66239f31cb0', '568753fd-bfa7-4920-a12a-36bd71277519', 'cM8mwkiBSS6Qh8wktcYz0u:APA91bFh8sLD2QikzcOWVKbB9wekxy3SKGcF7rlTpw0Td9Dep5lxb8h4ZyDc8SzYJLvF5nofgMmj7J5hsFTB2E-tMm0cc9cAZGnCp_ctQZ26idz6pspzilA', 'android', '2026-04-21 02:58:08', '2026-04-21 02:58:08'),
('c9d932e3-343c-11f1-9448-e66239f31cb0', '4f792c75-4a65-45a7-a0ef-d282acd83d87', 'exHldDxSSlifWMzAs4sdei:APA91bErwuO2GpCLCYFtq3ni42vitKoQ50pr9D3AZ8I-fhYdyL7gNdt5Kld-5Z5JU60swdHyhhDtnTh7srwijXAvVwYUxqdUT3XQxCeIz5UbsiVyN4TbOQE', 'android', '2026-04-09 17:51:51', '2026-04-09 22:01:05'),
('d2e74888-3474-11f1-9448-e66239f31cb0', 'd8e13ad8-2382-11f1-b82d-97044e7d8834', 'd5Fj6R21Sr6ExrYoY_AeLu:APA91bFUmDzxhQQ626ywGkVsABnIwESkGwwE3ZaaK3W6Gm_aEmyj_kIEavwjbCZ9B0qt4fD_y8nV0ImiLcGavrhv52EP7nFimMOb8xB-aCHFX-4JW0coDM4', 'android', '2026-04-10 00:32:58', '2026-04-10 04:49:19'),
('d494936a-3511-11f1-9448-e66239f31cb0', 'cf2239d2-2dd7-4116-abd3-1f24d48e8fe7', 'dBkQcMWvSESZjLHcq9117b:APA91bHwNVJaS1mtnWeGrsiCUCJybA7qyzFUvg_8WDFF8lYfmHJ1OjQxE_KmSUwTk2iNO186fyVCMKCmtXRIM5Oy-puuk2Ol4wvxPVBVA1bEOLlRHikGRM4', 'android', '2026-04-10 19:16:52', '2026-04-10 21:46:57'),
('f668ba02-36aa-11f1-9448-e66239f31cb0', '43d5d6a2-e09d-4581-8636-557cd150b0f1', 'dgStUz5cQaumPpYGhw7RSS:APA91bHi5iBdCJosysZjybBgsncGFuw2YFl4GdS5hIna1P8_FxrososhrjUNVbpplyUmi57EZxeGZL5ySiVQi5Tm8xY8_BM_Dvwj4QCBC_UvIWzVrB0997k', 'android', '2026-04-12 23:05:33', '2026-04-13 03:21:02');

-- --------------------------------------------------------

--
-- Table structure for table `fee_config`
--

CREATE TABLE `fee_config` (
  `id` char(36) NOT NULL DEFAULT uuid(),
  `active_model` enum('flat_per_order','percentage') NOT NULL,
  `flat_fee_amount` decimal(8,2) NOT NULL DEFAULT 2.00,
  `percentage_value` decimal(5,2) NOT NULL DEFAULT 12.00,
  `base_delivery_fee` decimal(8,2) NOT NULL DEFAULT 20.00,
  `multi_vendor_extra_fee` decimal(8,2) NOT NULL DEFAULT 5.00,
  `model_switch_date` date DEFAULT NULL,
  `updated_by` char(36) DEFAULT NULL,
  `updated_at` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `fee_config`
--

INSERT INTO `fee_config` (`id`, `active_model`, `flat_fee_amount`, `percentage_value`, `base_delivery_fee`, `multi_vendor_extra_fee`, `model_switch_date`, `updated_by`, `updated_at`) VALUES
('8f27444b-1d9d-11f1-b82d-97044e7d8834', 'flat_per_order', 3.00, 12.00, 20.00, 5.00, NULL, 'd8e13ad8-2382-11f1-b82d-97044e7d8834', '2026-04-10 06:26:55');

-- --------------------------------------------------------

--
-- Table structure for table `global_categories`
--

CREATE TABLE `global_categories` (
  `id` char(36) NOT NULL DEFAULT uuid(),
  `name` varchar(100) NOT NULL,
  `name_ar` varchar(100) DEFAULT NULL,
  `icon_name` varchar(50) NOT NULL DEFAULT 'category',
  `sort_order` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `global_categories`
--

INSERT INTO `global_categories` (`id`, `name`, `name_ar`, `icon_name`, `sort_order`, `is_active`, `created_at`) VALUES
('3a17f073-3342-11f1-9448-e66239f31cb0', 'Food', 'طعام', 'restaurant', 1, 1, '2026-04-08 11:58:16'),
('3a17f230-3342-11f1-9448-e66239f31cb0', 'Pharmacy', 'صيدلية', 'local_pharmacy', 2, 1, '2026-04-08 11:58:16'),
('3a17f2f5-3342-11f1-9448-e66239f31cb0', 'Grocery', 'بقالة', 'local_grocery_store', 3, 1, '2026-04-08 11:58:16'),
('3a17f32c-3342-11f1-9448-e66239f31cb0', 'Drinks', 'مشروبات', 'local_drink', 4, 1, '2026-04-08 11:58:16'),
('3a17f35b-3342-11f1-9448-e66239f31cb0', 'Desserts', 'حلويات', 'cake', 5, 1, '2026-04-08 11:58:16'),
('3a17f388-3342-11f1-9448-e66239f31cb0', 'Electronics', 'إلكترونيات', 'devices', 6, 1, '2026-04-08 11:58:16'),
('3a17f3b4-3342-11f1-9448-e66239f31cb0', 'Fashion', 'أزياء', 'checkroom', 7, 1, '2026-04-08 11:58:16'),
('3a17f3dd-3342-11f1-9448-e66239f31cb0', 'Health', 'صحة', 'health_and_safety', 8, 1, '2026-04-08 11:58:16'),
('3a17f408-3342-11f1-9448-e66239f31cb0', 'Other', 'أخرى', 'category', 9, 1, '2026-04-08 11:58:16'),
('4242b965-3345-11f1-9448-e66239f31cb0', 'test', 'اختبار', 'category', 10, 1, '2026-04-08 12:19:58');

-- --------------------------------------------------------

--
-- Table structure for table `menu_categories`
--

CREATE TABLE `menu_categories` (
  `id` char(36) NOT NULL DEFAULT uuid(),
  `vendor_id` char(36) NOT NULL,
  `global_category_id` char(36) DEFAULT NULL,
  `name` varchar(100) NOT NULL,
  `sort_order` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `menu_categories`
--

INSERT INTO `menu_categories` (`id`, `vendor_id`, `global_category_id`, `name`, `sort_order`, `is_active`, `created_at`) VALUES
('aa000001-0001-4000-a000-000000000001', '95a07fca-231c-11f1-b82d-97044e7d8834', '3a17f073-3342-11f1-9448-e66239f31cb0', 'Food', 1, 1, '2026-04-08 16:09:27'),
('aa000001-0001-4000-a000-000000000002', '95a07fca-231c-11f1-b82d-97044e7d8834', '3a17f230-3342-11f1-9448-e66239f31cb0', 'Pharmacy', 2, 1, '2026-04-08 16:09:27'),
('aa000001-0001-4000-a000-000000000003', '95a07fca-231c-11f1-b82d-97044e7d8834', '3a17f2f5-3342-11f1-9448-e66239f31cb0', 'Grocery', 3, 1, '2026-04-08 16:09:27'),
('aa000001-0001-4000-a000-000000000004', '95a07fca-231c-11f1-b82d-97044e7d8834', '3a17f32c-3342-11f1-9448-e66239f31cb0', 'Drinks', 4, 1, '2026-04-08 16:09:27'),
('aa000001-0001-4000-a000-000000000005', '95a07fca-231c-11f1-b82d-97044e7d8834', '3a17f35b-3342-11f1-9448-e66239f31cb0', 'Desserts', 5, 1, '2026-04-08 16:09:27'),
('aa000001-0001-4000-a000-000000000006', '95a07fca-231c-11f1-b82d-97044e7d8834', '3a17f408-3342-11f1-9448-e66239f31cb0', 'Other', 6, 1, '2026-04-08 16:09:27'),
('abc87f45-3d97-11f1-9448-e66239f31cb0', '6255f135-3d97-11f1-9448-e66239f31cb0', '3a17f073-3342-11f1-9448-e66239f31cb0', 'Food', 1, 1, '2026-04-21 18:35:05'),
('b8bc560a-3d21-11f1-9448-e66239f31cb0', '5de1f9b7-3d21-11f1-9448-e66239f31cb0', '3a17f073-3342-11f1-9448-e66239f31cb0', 'Food', 1, 1, '2026-04-21 04:30:46'),
('bb000002-0001-4000-b000-000000000001', '57e1966b-294a-11f1-9448-e66239f31cb0', '3a17f073-3342-11f1-9448-e66239f31cb0', 'Food', 1, 1, '2026-04-08 16:09:27'),
('bb000002-0001-4000-b000-000000000002', '57e1966b-294a-11f1-9448-e66239f31cb0', '3a17f230-3342-11f1-9448-e66239f31cb0', 'Pharmacy', 2, 1, '2026-04-08 16:09:27'),
('bb000002-0001-4000-b000-000000000003', '57e1966b-294a-11f1-9448-e66239f31cb0', '3a17f2f5-3342-11f1-9448-e66239f31cb0', 'Grocery', 3, 1, '2026-04-08 16:09:27'),
('bb000002-0001-4000-b000-000000000004', '57e1966b-294a-11f1-9448-e66239f31cb0', '3a17f32c-3342-11f1-9448-e66239f31cb0', 'Drinks', 4, 1, '2026-04-08 16:09:27'),
('bb000002-0001-4000-b000-000000000005', '57e1966b-294a-11f1-9448-e66239f31cb0', '3a17f388-3342-11f1-9448-e66239f31cb0', 'Electronics', 5, 1, '2026-04-08 16:09:27'),
('bb000002-0001-4000-b000-000000000006', '57e1966b-294a-11f1-9448-e66239f31cb0', '3a17f3b4-3342-11f1-9448-e66239f31cb0', 'Fashion', 6, 1, '2026-04-08 16:09:27'),
('bb000002-0001-4000-b000-000000000007', '57e1966b-294a-11f1-9448-e66239f31cb0', '3a17f3dd-3342-11f1-9448-e66239f31cb0', 'Health', 7, 1, '2026-04-08 16:09:27'),
('bb000002-0001-4000-b000-000000000008', '57e1966b-294a-11f1-9448-e66239f31cb0', '3a17f408-3342-11f1-9448-e66239f31cb0', 'Other', 8, 1, '2026-04-08 16:09:27');

-- --------------------------------------------------------

--
-- Table structure for table `menu_items`
--

CREATE TABLE `menu_items` (
  `id` char(36) NOT NULL DEFAULT uuid(),
  `category_id` char(36) NOT NULL,
  `vendor_id` char(36) NOT NULL,
  `name` varchar(150) NOT NULL,
  `name_ar` varchar(200) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `description_ar` text DEFAULT NULL,
  `base_price` decimal(10,2) NOT NULL,
  `discount_price` decimal(10,2) DEFAULT NULL,
  `preparation_time` varchar(50) DEFAULT NULL,
  `image_url` text DEFAULT NULL,
  `is_available` tinyint(1) NOT NULL DEFAULT 1,
  `sort_order` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `menu_items`
--

INSERT INTO `menu_items` (`id`, `category_id`, `vendor_id`, `name`, `name_ar`, `description`, `description_ar`, `base_price`, `discount_price`, `preparation_time`, `image_url`, `is_available`, `sort_order`, `created_at`, `updated_at`) VALUES
('c809eec0-3d97-11f1-9448-e66239f31cb0', 'abc87f45-3d97-11f1-9448-e66239f31cb0', '6255f135-3d97-11f1-9448-e66239f31cb0', 'meat', 'لحم', 'meat', 'لحم', 1000.00, 999.00, '30', NULL, 1, 0, '2026-04-21 18:35:53', '2026-04-21 18:35:53'),
('d9d3d650-3d21-11f1-9448-e66239f31cb0', 'b8bc560a-3d21-11f1-9448-e66239f31cb0', '5de1f9b7-3d21-11f1-9448-e66239f31cb0', 'grild chicken', 'دجاج مشوي', 'grill', 'مشوي', 100.00, 90.00, NULL, NULL, 1, 0, '2026-04-21 04:31:42', '2026-04-21 04:31:42'),
('f1010001-0001-4000-a000-000000000001', 'aa000001-0001-4000-a000-000000000001', '95a07fca-231c-11f1-b82d-97044e7d8834', 'Grilled Chicken Platter', 'طبق دجاج مشوي', 'Tender grilled chicken breast served with rice and salad', 'صدر دجاج مشوي طري يقدم مع الأرز والسلطة', 850.00, 750.00, '25', 'https://appforyou.store/backendOrder/upload/items/69da813fb7626_1775927615.jpg', 1, 1, '2026-04-08 16:09:27', '2026-04-11 17:13:35'),
('f1010001-0001-4000-a000-000000000002', 'aa000001-0001-4000-a000-000000000001', '95a07fca-231c-11f1-b82d-97044e7d8834', 'Beef Shawarma Wrap', 'شاورما لحم بالخبز', 'Juicy beef shawarma in warm pita with garlic sauce', 'شاورما لحم بقري في خبز دافئ مع صلصة الثوم', 450.00, NULL, '15', 'https://appforyou.store/backendOrder/upload/items/69da80fa27f5c_1775927546.jpg', 1, 2, '2026-04-08 16:09:27', '2026-04-11 17:12:26'),
('f1010001-0001-4000-a000-000000000003', 'aa000001-0001-4000-a000-000000000001', '95a07fca-231c-11f1-b82d-97044e7d8834', 'Margherita Pizza', 'بيتزا مارغريتا', 'Classic pizza with fresh mozzarella, tomato sauce &amp; basil', 'بيتزا كلاسيكية بالموزاريلا الطازجة وصلصة الطماطم والريحان', 600.00, 520.00, '20', 'https://appforyou.store/backendOrder/upload/items/69da8172be7d3_1775927666.jpg', 1, 3, '2026-04-08 16:09:27', '2026-04-11 17:14:26'),
('f1010001-0001-4000-a000-000000000004', 'aa000001-0001-4000-a000-000000000001', '95a07fca-231c-11f1-b82d-97044e7d8834', 'Lamb Kebab Plate', 'طبق كباب لحم غنم', 'Authentic lamb kebab with grilled vegetables and hummus', 'كباب لحم غنم أصيل مع خضروات مشوية وحمص', 950.00, NULL, '30', 'https://appforyou.store/backendOrder/upload/items/69da8158660fc_1775927640.jpg', 1, 4, '2026-04-08 16:09:27', '2026-04-11 17:14:00'),
('f1020001-0001-4000-a000-000000000001', 'aa000001-0001-4000-a000-000000000002', '95a07fca-231c-11f1-b82d-97044e7d8834', 'Paracetamol 500mg (20 Tablets)', 'باراسيتامول 500 مجم (20 قرص)', 'Pain reliever and fever reducer', 'مسكن للألم وخافض للحرارة', 120.00, NULL, '5', 'https://appforyou.store/backendOrder/upload/items/69da81a3b04ed_1775927715.jpg', 1, 1, '2026-04-08 16:09:27', '2026-04-11 17:15:15'),
('f1020001-0001-4000-a000-000000000002', 'aa000001-0001-4000-a000-000000000002', '95a07fca-231c-11f1-b82d-97044e7d8834', 'Vitamin C 1000mg (30 Tabs)', 'فيتامين سي 1000 مجم (30 قرص)', 'Immune system support supplement', 'مكمل غذائي لدعم جهاز المناعة', 250.00, 210.00, '5', 'https://appforyou.store/backendOrder/upload/items/69da81bf78da9_1775927743.jpg', 1, 2, '2026-04-08 16:09:27', '2026-04-11 17:15:43'),
('f1020001-0001-4000-a000-000000000003', 'aa000001-0001-4000-a000-000000000002', '95a07fca-231c-11f1-b82d-97044e7d8834', 'First Aid Kit – Standard', 'حقيبة إسعافات أولية – قياسية', 'Complete first aid kit with bandages, antiseptic &amp; more', 'حقيبة إسعافات أولية كاملة مع ضمادات ومطهر والمزيد', 450.00, NULL, '5', 'https://appforyou.store/backendOrder/upload/items/69da818ae8fee_1775927690.jpg', 1, 3, '2026-04-08 16:09:27', '2026-04-11 17:14:50'),
('f1030001-0001-4000-a000-000000000001', 'aa000001-0001-4000-a000-000000000003', '95a07fca-231c-11f1-b82d-97044e7d8834', 'Organic Olive Oil 1L', 'زيت زيتون عضوي 1 لتر', 'Cold-pressed extra virgin olive oil', 'زيت زيتون بكر ممتاز معصور على البارد', 680.00, 600.00, '5', 'https://appforyou.store/backendOrder/upload/items/69da82102c265_1775927824.jpg', 1, 1, '2026-04-08 16:09:27', '2026-04-11 17:17:04'),
('f1030001-0001-4000-a000-000000000002', 'aa000001-0001-4000-a000-000000000003', '95a07fca-231c-11f1-b82d-97044e7d8834', 'Basmati Rice 5kg', 'أرز بسمتي 5 كجم', 'Premium long-grain basmati rice', 'أرز بسمتي فاخر طويل الحبة', 350.00, NULL, '5', 'https://appforyou.store/backendOrder/upload/items/69da81d99a147_1775927769.jpg', 1, 2, '2026-04-08 16:09:27', '2026-04-11 17:16:09'),
('f1030001-0001-4000-a000-000000000003', 'aa000001-0001-4000-a000-000000000003', '95a07fca-231c-11f1-b82d-97044e7d8834', 'Fresh Eggs (30 Pack)', 'بيض طازج (30 حبة)', 'Farm fresh eggs, naturally sourced', 'بيض طازج من المزرعة، مصدر طبيعي', 280.00, 250.00, '5', 'https://appforyou.store/backendOrder/upload/items/69da81f64ee0d_1775927798.jpg', 1, 3, '2026-04-08 16:09:27', '2026-04-11 17:16:38'),
('f1040001-0001-4000-a000-000000000001', 'aa000001-0001-4000-a000-000000000004', '95a07fca-231c-11f1-b82d-97044e7d8834', 'Fresh Orange Juice 500ml', 'عصير برتقال طازج 500 مل', 'Freshly squeezed orange juice with no added sugar', 'عصير برتقال طازج بدون سكر مضاف', 180.00, 150.00, '10', 'https://appforyou.store/backendOrder/upload/items/69da822896eee_1775927848.jpg', 1, 1, '2026-04-08 16:09:27', '2026-04-11 17:17:28'),
('f1040001-0001-4000-a000-000000000002', 'aa000001-0001-4000-a000-000000000004', '95a07fca-231c-11f1-b82d-97044e7d8834', 'Mango Smoothie', 'سموذي مانجو', 'Creamy mango smoothie blended with milk and honey', 'سموذي مانجو كريمي ممزوج بالحليب والعسل', 220.00, NULL, '10', 'https://appforyou.store/backendOrder/upload/items/69da8263ef4f7_1775927907.jpg', 1, 2, '2026-04-08 16:09:27', '2026-04-11 17:18:27'),
('f1040001-0001-4000-a000-000000000003', 'aa000001-0001-4000-a000-000000000004', '95a07fca-231c-11f1-b82d-97044e7d8834', 'Iced Caramel Latte', 'لاتيه كراميل مثلج', 'Espresso with caramel syrup, milk and ice', 'إسبريسو مع شراب الكراميل والحليب والثلج', 280.00, 240.00, '8', 'https://appforyou.store/backendOrder/upload/items/69da824a18429_1775927882.jpg', 1, 3, '2026-04-08 16:09:27', '2026-04-11 17:18:02'),
('f1050001-0001-4000-a000-000000000001', 'aa000001-0001-4000-a000-000000000005', '95a07fca-231c-11f1-b82d-97044e7d8834', 'Chocolate Lava Cake', 'كيك لافا بالشوكولاتة', 'Warm chocolate cake with molten center, served with ice cream', 'كيك شوكولاتة دافئ بقلب سائل يقدم مع الآيس كريم', 350.00, 300.00, '15', 'https://appforyou.store/backendOrder/upload/items/69da827c2fe80_1775927932.jpg', 1, 1, '2026-04-08 16:09:27', '2026-04-11 17:18:52'),
('f1050001-0001-4000-a000-000000000002', 'aa000001-0001-4000-a000-000000000005', '95a07fca-231c-11f1-b82d-97044e7d8834', 'Kunafa with Cream', 'كنافة بالقشطة', 'Traditional kunafa filled with sweet cream and pistachios', 'كنافة تقليدية محشوة بالقشطة الحلوة والفستق', 400.00, NULL, '20', 'https://appforyou.store/backendOrder/upload/items/69da8295ced76_1775927957.jpg', 1, 2, '2026-04-08 16:09:27', '2026-04-11 17:19:17'),
('f1050001-0001-4000-a000-000000000003', 'aa000001-0001-4000-a000-000000000005', '95a07fca-231c-11f1-b82d-97044e7d8834', 'Tiramisu Cup', 'كوب تيراميسو', 'Classic Italian tiramisu with mascarpone and espresso', 'تيراميسو إيطالي كلاسيكي بالماسكاربوني والإسبريسو', 300.00, 260.00, '5', 'https://appforyou.store/backendOrder/upload/items/69da82bba580a_1775927995.jpg', 1, 3, '2026-04-08 16:09:27', '2026-04-11 17:19:55'),
('f1060001-0001-4000-a000-000000000002', 'aa000001-0001-4000-a000-000000000006', '95a07fca-231c-11f1-b82d-97044e7d8834', 'Scented Candle Set', 'طقم شموع معطرة', 'Set of 3 luxury scented candles in a gift box', 'طقم من 3 شموع فاخرة معطرة في علبة هدايا', 320.00, 280.00, '5', 'https://appforyou.store/backendOrder/upload/items/69da831c37aea_1775928092.jpg', 1, 2, '2026-04-08 16:09:27', '2026-04-11 17:21:32'),
('f1060001-0001-4000-a000-000000000003', 'aa000001-0001-4000-a000-000000000006', '95a07fca-231c-11f1-b82d-97044e7d8834', 'Reusable Shopping Bag', 'حقيبة تسوق قابلة لإعادة الاستخدام', 'Eco-friendly foldable shopping bag', 'حقيبة تسوق صديقة للبيئة قابلة للطي', 80.00, NULL, '5', 'https://appforyou.store/backendOrder/upload/items/69da83040d73d_1775928068.jpg', 1, 3, '2026-04-08 16:09:27', '2026-04-11 17:21:08'),
('f2010001-0001-4000-b000-000000000001', 'bb000002-0001-4000-b000-000000000001', '57e1966b-294a-11f1-9448-e66239f31cb0', 'Classic Cheese Burger', 'برجر جبنة كلاسيكي', 'Angus beef patty with cheddar, lettuce, tomato &amp;amp; special sauce', 'قطعة لحم أنجوس مع شيدر وخس وطماطم وصلصة خاصة', 500.00, 420.00, '15', 'https://appforyou.store/backendOrder/upload/items/69da7fa14347e_1775927201.jpg', 1, 1, '2026-04-08 16:09:27', '2026-04-11 17:06:41'),
('f2010001-0001-4000-b000-000000000002', 'bb000002-0001-4000-b000-000000000001', '57e1966b-294a-11f1-9448-e66239f31cb0', 'Chicken Caesar Salad', 'سلطة سيزر بالدجاج', 'Crispy romaine, grilled chicken, parmesan &amp;amp;amp; caesar dressing', 'خس روماني مقرمش ودجاج مشوي وبارميزان وصلصة سيزر', 380.00, NULL, '10', 'https://appforyou.store/backendOrder/upload/items/69da7f97b17ec_1775927191.jpg', 1, 2, '2026-04-08 16:09:27', '2026-04-11 17:06:31'),
('f2010001-0001-4000-b000-000000000003', 'bb000002-0001-4000-b000-000000000001', '57e1966b-294a-11f1-9448-e66239f31cb0', 'Falafel Wrap', 'راب فلافل', 'Crispy falafel with tahini, pickles and fresh veggies', 'فلافل مقرمشة مع طحينة ومخللات وخضروات طازجة', 300.00, 260.00, '12', 'https://appforyou.store/backendOrder/upload/items/69da7faa1b307_1775927210.jpg', 1, 3, '2026-04-08 16:09:27', '2026-04-11 17:06:50'),
('f2010001-0001-4000-b000-000000000004', 'bb000002-0001-4000-b000-000000000001', '57e1966b-294a-11f1-9448-e66239f31cb0', 'Pasta Alfredo', 'باستا ألفريدو', 'Fettuccine with creamy alfredo sauce and grilled chicken', 'فيتوتشيني مع صلصة ألفريدو الكريمية ودجاج مشوي', 550.00, NULL, '20', 'https://appforyou.store/backendOrder/upload/items/69da7fb559e9c_1775927221.jpg', 1, 4, '2026-04-08 16:09:27', '2026-04-11 17:07:01'),
('f2020001-0001-4000-b000-000000000001', 'bb000002-0001-4000-b000-000000000002', '57e1966b-294a-11f1-9448-e66239f31cb0', 'Ibuprofen 400mg (24 Tabs)', 'إيبوبروفين 400 مجم (24 قرص)', 'Anti-inflammatory pain reliever', 'مسكن ألم مضاد للالتهابات', 150.00, 130.00, '5', 'https://appforyou.store/backendOrder/upload/items/69da7fd630569_1775927254.jpg', 1, 1, '2026-04-08 16:09:27', '2026-04-11 17:07:34'),
('f2020001-0001-4000-b000-000000000002', 'bb000002-0001-4000-b000-000000000002', '57e1966b-294a-11f1-9448-e66239f31cb0', 'Digital Thermometer', 'ميزان حرارة رقمي', 'Fast and accurate digital thermometer', 'ميزان حرارة رقمي سريع ودقيق', 200.00, NULL, '5', 'https://appforyou.store/backendOrder/upload/items/69da7fc05098b_1775927232.jpg', 1, 2, '2026-04-08 16:09:27', '2026-04-11 17:07:12'),
('f2020001-0001-4000-b000-000000000003', 'bb000002-0001-4000-b000-000000000002', '57e1966b-294a-11f1-9448-e66239f31cb0', 'Hand Sanitizer 500ml', 'معقم يدين 500 مل', 'Antibacterial sanitizer with moisturizing formula', 'معقم مضاد للبكتيريا بتركيبة مرطبة', 95.00, 80.00, '5', 'https://appforyou.store/backendOrder/upload/items/69da7fca5b442_1775927242.jpg', 1, 3, '2026-04-08 16:09:27', '2026-04-11 17:07:22'),
('f2030001-0001-4000-b000-000000000001', 'bb000002-0001-4000-b000-000000000003', '57e1966b-294a-11f1-9448-e66239f31cb0', 'Whole Wheat Bread', 'خبز قمح كامل', 'Freshly baked whole wheat loaf', 'رغيف خبز قمح كامل طازج', 120.00, NULL, '5', 'https://appforyou.store/backendOrder/upload/items/69da7ff54fa24_1775927285.jpg', 1, 1, '2026-04-08 16:09:27', '2026-04-11 17:08:05'),
('f2030001-0001-4000-b000-000000000002', 'bb000002-0001-4000-b000-000000000003', '57e1966b-294a-11f1-9448-e66239f31cb0', 'Natural Honey 500g', 'عسل طبيعي 500 جم', 'Pure natural honey from local beekeepers', 'عسل طبيعي نقي من مربي النحل المحليين', 480.00, 420.00, '5', 'https://appforyou.store/backendOrder/upload/items/69da7feb2367e_1775927275.jpg', 1, 2, '2026-04-08 16:09:27', '2026-04-11 17:07:55'),
('f2030001-0001-4000-b000-000000000003', 'bb000002-0001-4000-b000-000000000003', '57e1966b-294a-11f1-9448-e66239f31cb0', 'Mixed Nuts 1kg', 'مكسرات مشكلة 1 كجم', 'Premium assorted nuts: almonds, cashews, walnuts &amp;amp; pistachios', 'مكسرات فاخرة متنوعة: لوز وكاجو وجوز وفستق', 750.00, 680.00, '5', 'https://appforyou.store/backendOrder/upload/items/69da7fe02fff8_1775927264.jpg', 1, 3, '2026-04-08 16:09:27', '2026-04-11 17:07:44'),
('f2040001-0001-4000-b000-000000000001', 'bb000002-0001-4000-b000-000000000004', '57e1966b-294a-11f1-9448-e66239f31cb0', 'Green Detox Smoothie', 'سموذي ديتوكس أخضر', 'Spinach, apple, ginger and lemon blend', 'مزيج السبانخ والتفاح والزنجبيل والليمون', 250.00, 210.00, '8', 'https://appforyou.store/backendOrder/upload/items/69da80066412c_1775927302.jpg', 1, 1, '2026-04-08 16:09:27', '2026-04-11 17:08:22'),
('f2040001-0001-4000-b000-000000000002', 'bb000002-0001-4000-b000-000000000004', '57e1966b-294a-11f1-9448-e66239f31cb0', 'Turkish Coffee', 'قهوة تركية', 'Traditional Turkish coffee with cardamom', 'قهوة تركية تقليدية بالهيل', 150.00, NULL, '5', 'https://appforyou.store/backendOrder/upload/items/69da80201f8e2_1775927328.jpg', 1, 2, '2026-04-08 16:09:27', '2026-04-11 17:08:48'),
('f2040001-0001-4000-b000-000000000003', 'bb000002-0001-4000-b000-000000000004', '57e1966b-294a-11f1-9448-e66239f31cb0', 'Strawberry Milkshake', 'ميلك شيك فراولة', 'Thick and creamy strawberry milkshake', 'ميلك شيك فراولة سميك وكريمي', 200.00, 170.00, '7', 'https://appforyou.store/backendOrder/upload/items/69da80113f8ab_1775927313.jpg', 1, 3, '2026-04-08 16:09:27', '2026-04-11 17:08:33'),
('f2050001-0001-4000-b000-000000000001', 'bb000002-0001-4000-b000-000000000005', '57e1966b-294a-11f1-9448-e66239f31cb0', 'Wireless Bluetooth Earbuds', 'سماعات بلوتوث لاسلكية', 'Noise-cancelling earbuds with 24h battery life', 'سماعات عازلة للضوضاء مع بطارية تدوم 24 ساعة', 1200.00, 980.00, '5', 'https://appforyou.store/backendOrder/upload/items/69da804843075_1775927368.jpg', 1, 1, '2026-04-08 16:09:27', '2026-04-11 17:09:28'),
('f2050001-0001-4000-b000-000000000002', 'bb000002-0001-4000-b000-000000000005', '57e1966b-294a-11f1-9448-e66239f31cb0', 'USB-C Fast Charger 65W', 'شاحن سريع USB-C 65 واط', 'Universal fast charger compatible with phones &amp;amp; laptops', 'شاحن سريع عالمي متوافق مع الهواتف والأجهزة المحمولة', 350.00, 300.00, '5', 'https://appforyou.store/backendOrder/upload/items/69da803ad2bd7_1775927354.jpg', 1, 2, '2026-04-08 16:09:27', '2026-04-11 17:09:14'),
('f2050001-0001-4000-b000-000000000003', 'bb000002-0001-4000-b000-000000000005', '57e1966b-294a-11f1-9448-e66239f31cb0', 'Portable Power Bank 20000mAh', 'بطارية متنقلة 20000 مللي أمبير', 'Slim design, dual USB output with LED display', 'تصميم نحيف، منفذ USB مزدوج مع شاشة LED', 500.00, NULL, '5', 'https://appforyou.store/backendOrder/upload/items/69da802ea4d77_1775927342.jpg', 1, 3, '2026-04-08 16:09:27', '2026-04-11 17:09:02'),
('f2060001-0001-4000-b000-000000000001', 'bb000002-0001-4000-b000-000000000006', '57e1966b-294a-11f1-9448-e66239f31cb0', 'Classic Cotton T-Shirt', 'تيشيرت قطن كلاسيكي', 'Premium cotton t-shirt, available in multiple colors', 'تيشيرت قطن فاخر، متوفر بعدة ألوان', 250.00, 200.00, '5', 'https://appforyou.store/backendOrder/upload/items/69da805498389_1775927380.jpg', 1, 1, '2026-04-08 16:09:27', '2026-04-11 17:09:40'),
('f2060001-0001-4000-b000-000000000002', 'bb000002-0001-4000-b000-000000000006', '57e1966b-294a-11f1-9448-e66239f31cb0', 'Leather Wallet', 'محفظة جلدية', 'Genuine leather wallet with RFID protection', 'محفظة جلد طبيعي مع حماية RFID', 450.00, NULL, '5', 'https://appforyou.store/backendOrder/upload/items/69da8060080ec_1775927392.jpg', 1, 2, '2026-04-08 16:09:27', '2026-04-11 17:09:52'),
('f2060001-0001-4000-b000-000000000003', 'bb000002-0001-4000-b000-000000000006', '57e1966b-294a-11f1-9448-e66239f31cb0', 'Sports Running Shoes', 'حذاء رياضي للجري', 'Lightweight breathable running shoes with cushioned sole', 'حذاء جري خفيف الوزن ومسامي بنعل مبطن', 890.00, 750.00, '5', 'https://appforyou.store/backendOrder/upload/items/69da807ee70f8_1775927422.jpg', 1, 3, '2026-04-08 16:09:27', '2026-04-11 17:10:22'),
('f2070001-0001-4000-b000-000000000001', 'bb000002-0001-4000-b000-000000000007', '57e1966b-294a-11f1-9448-e66239f31cb0', 'Whey Protein Powder 1kg', 'بروتين مصل اللبن 1 كجم', 'High-quality whey protein isolate, chocolate flavor', 'بروتين مصل لبن عالي الجودة بنكهة الشوكولاتة', 1500.00, 1300.00, '5', 'https://appforyou.store/backendOrder/upload/items/69dab69b61fc1_1775941275.webp', 1, 1, '2026-04-08 16:09:27', '2026-04-11 21:01:15'),
('f2070001-0001-4000-b000-000000000002', 'bb000002-0001-4000-b000-000000000007', '57e1966b-294a-11f1-9448-e66239f31cb0', 'Yoga Mat – Premium', 'سجادة يوغا – فاخرة', 'Non-slip eco-friendly yoga mat, 6mm thick', 'سجادة يوغا صديقة للبيئة مانعة للانزلاق، سمك 6 مم', 350.00, NULL, '5', 'https://appforyou.store/backendOrder/upload/items/69dab5edc26f8_1775941101.jpg', 1, 2, '2026-04-08 16:09:27', '2026-04-11 20:58:21'),
('f2070001-0001-4000-b000-000000000003', 'bb000002-0001-4000-b000-000000000007', '57e1966b-294a-11f1-9448-e66239f31cb0', 'Multivitamin Complex (60 Caps)', 'مركب فيتامينات متعددة (60 كبسولة)', 'Daily multivitamin with minerals for overall wellness', 'فيتامينات يومية متعددة مع معادن للصحة العامة', 280.00, 240.00, '5', 'https://appforyou.store/backendOrder/upload/items/69da8088d3b30_1775927432.jpg', 1, 3, '2026-04-08 16:09:27', '2026-04-11 17:10:32'),
('f2080001-0001-4000-b000-000000000001', 'bb000002-0001-4000-b000-000000000008', '57e1966b-294a-11f1-9448-e66239f31cb0', 'Notebook – Premium Hardcover', 'دفتر – غلاف صلب فاخر', 'A5 premium notebook, 200 pages, lined paper', 'دفتر فاخر A5، 200 صفحة، ورق مسطر', 180.00, 150.00, '5', 'https://appforyou.store/backendOrder/upload/items/69dab62f03362_1775941167.jpg', 1, 1, '2026-04-08 16:09:27', '2026-04-11 20:59:27'),
('f2080001-0001-4000-b000-000000000002', 'bb000002-0001-4000-b000-000000000008', '57e1966b-294a-11f1-9448-e66239f31cb0', 'Stainless Steel Water Bottle', 'زجاجة مياه ستانلس ستيل', 'Insulated bottle keeps drinks cold 24h or hot 12h', 'زجاجة معزولة تحافظ على البرودة 24 ساعة والحرارة 12 ساعة', 220.00, NULL, '5', 'https://appforyou.store/backendOrder/upload/items/69dab650eb3fa_1775941200.jpg', 1, 2, '2026-04-08 16:09:27', '2026-04-11 21:00:00'),
('f2080001-0001-4000-b000-000000000003', 'bb000002-0001-4000-b000-000000000008', '57e1966b-294a-11f1-9448-e66239f31cb0', 'Desk Organizer Set', 'طقم منظم مكتب', 'Wooden desk organizer with pen holder and phone stand', 'منظم مكتب خشبي مع حامل أقلام وحامل هاتف', 350.00, 290.00, '5', 'https://appforyou.store/backendOrder/upload/items/69dab611a1812_1775941137.jpg', 1, 3, '2026-04-08 16:09:27', '2026-04-11 20:58:57');

-- --------------------------------------------------------

--
-- Table structure for table `notifications`
--

CREATE TABLE `notifications` (
  `id` char(36) NOT NULL DEFAULT uuid(),
  `user_id` char(36) NOT NULL,
  `title` varchar(200) NOT NULL,
  `body` text NOT NULL,
  `type` varchar(50) DEFAULT NULL COMMENT 'order_update, promo, system, etc.',
  `data` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL COMMENT 'Extra payload (order_id, etc.)' CHECK (json_valid(`data`)),
  `is_read` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `notifications`
--

INSERT INTO `notifications` (`id`, `user_id`, `title`, `body`, `type`, `data`, `is_read`, `created_at`) VALUES
('00cfe2b2-3d25-11f1-9448-e66239f31cb0', '8ba11278-3b31-11f1-9448-e66239f31cb0', '🔔 New Order #ORD-2026-000013', 'New order placed — ₪112.00', 'new_order', '{\"order_id\":\"d29a86e5-2e14-4e19-8884-0f2db2b8d8d5\",\"order_number\":\"ORD-2026-000013\",\"total_amount\":\"112\",\"status\":\"pending\"}', 0, '2026-04-21 04:54:16'),
('00d007a7-3d25-11f1-9448-e66239f31cb0', 'd8e13ad8-2382-11f1-b82d-97044e7d8834', '🔔 New Order #ORD-2026-000013', 'New order placed — ₪112.00', 'new_order', '{\"order_id\":\"d29a86e5-2e14-4e19-8884-0f2db2b8d8d5\",\"order_number\":\"ORD-2026-000013\",\"total_amount\":\"112\",\"status\":\"pending\"}', 0, '2026-04-21 04:54:16'),
('017f591c-3d99-11f1-9448-e66239f31cb0', '1d8557e5-8a7d-4c32-8181-ad785ea979a0', '🚚 Your Order is On the Way!', 'Order #ORD-2026-000015 is out for delivery.', 'order_status', '{\"order_id\":\"f5d055ec-9300-445f-af99-77077d8cc519\",\"order_number\":\"ORD-2026-000015\",\"status\":\"out_for_delivery\"}', 1, '2026-04-21 18:44:38'),
('0182f834-3d25-11f1-9448-e66239f31cb0', 'e64dd274-3d20-11f1-9448-e66239f31cb0', '🔔 New Order #ORD-2026-000013', 'New order placed — ₪112.00', 'new_order', '{\"order_id\":\"d29a86e5-2e14-4e19-8884-0f2db2b8d8d5\",\"order_number\":\"ORD-2026-000013\",\"total_amount\":\"112\",\"status\":\"pending\"}', 0, '2026-04-21 04:54:17'),
('019273e5-3d25-11f1-9448-e66239f31cb0', 'f52cc98d-3d20-11f1-9448-e66239f31cb0', '🔔 New Order #ORD-2026-000013', 'New order placed — ₪112.00', 'new_order', '{\"order_id\":\"d29a86e5-2e14-4e19-8884-0f2db2b8d8d5\",\"order_number\":\"ORD-2026-000013\",\"total_amount\":\"112\",\"status\":\"pending\"}', 1, '2026-04-21 04:54:17'),
('0193eb08-3d99-11f1-9448-e66239f31cb0', '68d947ef-fcec-426a-8316-155666848619', 'Order Dispatched #ORD-2026-000015', 'Order has been dispatched for delivery.', 'order_status', '{\"order_id\":\"f5d055ec-9300-445f-af99-77077d8cc519\",\"order_number\":\"ORD-2026-000015\",\"status\":\"out_for_delivery\"}', 0, '2026-04-21 18:44:39'),
('019e59b6-3d25-11f1-9448-e66239f31cb0', '8f266f22-1d9d-11f1-b82d-97044e7d8834', '🔔 New Order #ORD-2026-000013', 'New order placed — ₪112.00', 'new_order', '{\"order_id\":\"d29a86e5-2e14-4e19-8884-0f2db2b8d8d5\",\"order_number\":\"ORD-2026-000013\",\"total_amount\":\"112\",\"status\":\"pending\"}', 0, '2026-04-21 04:54:17'),
('019f5790-3d99-11f1-9448-e66239f31cb0', '6255a20b-3d97-11f1-9448-e66239f31cb0', 'Order Dispatched #ORD-2026-000015', 'Order has been dispatched for delivery.', 'order_status', '{\"order_id\":\"f5d055ec-9300-445f-af99-77077d8cc519\",\"order_number\":\"ORD-2026-000015\",\"status\":\"out_for_delivery\"}', 0, '2026-04-21 18:44:39'),
('0216d6a7-3d25-11f1-9448-e66239f31cb0', '68d947ef-fcec-426a-8316-155666848619', '📦 New Order Received', 'Order #ORD-2026-000013 — ₪112.00', 'new_order', '{\"order_id\":\"d29a86e5-2e14-4e19-8884-0f2db2b8d8d5\",\"order_number\":\"ORD-2026-000013\",\"total_amount\":\"112\",\"status\":\"pending\"}', 1, '2026-04-21 04:54:18'),
('04194eb2-3d90-11f1-9448-e66239f31cb0', '1d8557e5-8a7d-4c32-8181-ad785ea979a0', '👨‍🍳 Order Being Prepared', 'Your order #ORD-2026-000014 is now being prepared!', 'order_status', '{\"order_id\":\"8f957033-af0e-4c09-a600-74344e7b1485\",\"order_number\":\"ORD-2026-000014\",\"status\":\"preparing\"}', 1, '2026-04-21 17:40:17'),
('04b5e163-3697-11f1-9448-e66239f31cb0', '5732093a-a1d5-46ba-9432-ac9f560ba03c', '🚚 Your Order is On the Way!', 'Order #ORD-2026-000005 is out for delivery.', 'order_status', '{\"order_id\":\"7175c222-70ed-49bf-929d-11fb434f107a\",\"order_number\":\"ORD-2026-000005\",\"status\":\"out_for_delivery\"}', 0, '2026-04-12 20:42:47'),
('04ca1584-3697-11f1-9448-e66239f31cb0', '4f792c75-4a65-45a7-a0ef-d282acd83d87', 'Order Dispatched #ORD-2026-000005', 'Order has been dispatched for delivery.', 'order_status', '{\"order_id\":\"7175c222-70ed-49bf-929d-11fb434f107a\",\"order_number\":\"ORD-2026-000005\",\"status\":\"out_for_delivery\"}', 0, '2026-04-12 20:42:47'),
('05151670-3d99-11f1-9448-e66239f31cb0', '1d8557e5-8a7d-4c32-8181-ad785ea979a0', '🎉 Order Delivered!', 'Your order #ORD-2026-000015 has been delivered. Enjoy and rate your experience!', 'order_status', '{\"order_id\":\"f5d055ec-9300-445f-af99-77077d8cc519\",\"order_number\":\"ORD-2026-000015\",\"status\":\"delivered\"}', 1, '2026-04-21 18:44:44'),
('052a676a-3d99-11f1-9448-e66239f31cb0', '8f266f22-1d9d-11f1-b82d-97044e7d8834', 'Order Delivered #ORD-2026-000015', 'Order has been successfully delivered.', 'order_status', '{\"order_id\":\"f5d055ec-9300-445f-af99-77077d8cc519\",\"order_number\":\"ORD-2026-000015\",\"status\":\"delivered\"}', 0, '2026-04-21 18:44:45'),
('05cf7490-3d90-11f1-9448-e66239f31cb0', '1d8557e5-8a7d-4c32-8181-ad785ea979a0', '✅ Order Ready!', 'Your order #ORD-2026-000014 is ready for pickup!', 'order_status', '{\"order_id\":\"8f957033-af0e-4c09-a600-74344e7b1485\",\"order_number\":\"ORD-2026-000014\",\"status\":\"ready_for_pickup\"}', 1, '2026-04-21 17:40:20'),
('05e86d5a-3d90-11f1-9448-e66239f31cb0', '8ba11278-3b31-11f1-9448-e66239f31cb0', '🚚 Ready for Pickup #ORD-2026-000014', 'All vendors ready — dispatch delivery now!', 'order_status', '{\"order_id\":\"8f957033-af0e-4c09-a600-74344e7b1485\",\"order_number\":\"ORD-2026-000014\",\"status\":\"ready_for_pickup\"}', 0, '2026-04-21 17:40:20'),
('05e8881a-3d90-11f1-9448-e66239f31cb0', 'd8e13ad8-2382-11f1-b82d-97044e7d8834', '🚚 Ready for Pickup #ORD-2026-000014', 'All vendors ready — dispatch delivery now!', 'order_status', '{\"order_id\":\"8f957033-af0e-4c09-a600-74344e7b1485\",\"order_number\":\"ORD-2026-000014\",\"status\":\"ready_for_pickup\"}', 0, '2026-04-21 17:40:20'),
('06708dca-3d90-11f1-9448-e66239f31cb0', 'e64dd274-3d20-11f1-9448-e66239f31cb0', '🚚 Ready for Pickup #ORD-2026-000014', 'All vendors ready — dispatch delivery now!', 'order_status', '{\"order_id\":\"8f957033-af0e-4c09-a600-74344e7b1485\",\"order_number\":\"ORD-2026-000014\",\"status\":\"ready_for_pickup\"}', 0, '2026-04-21 17:40:21'),
('0681d9d2-3d90-11f1-9448-e66239f31cb0', 'f52cc98d-3d20-11f1-9448-e66239f31cb0', '🚚 Ready for Pickup #ORD-2026-000014', 'All vendors ready — dispatch delivery now!', 'order_status', '{\"order_id\":\"8f957033-af0e-4c09-a600-74344e7b1485\",\"order_number\":\"ORD-2026-000014\",\"status\":\"ready_for_pickup\"}', 0, '2026-04-21 17:40:21'),
('06b0a1d7-3d16-11f1-9448-e66239f31cb0', '8ba11278-3b31-11f1-9448-e66239f31cb0', '🔔 New Order #ORD-2026-000011', 'New order placed — ₪1,222.00', 'new_order', '{\"order_id\":\"e39e531f-02d7-402b-bfa1-4bc7911fdca0\",\"order_number\":\"ORD-2026-000011\",\"total_amount\":\"1222\",\"status\":\"pending\"}', 0, '2026-04-21 03:07:03'),
('06b0bb57-3d16-11f1-9448-e66239f31cb0', 'd8e13ad8-2382-11f1-b82d-97044e7d8834', '🔔 New Order #ORD-2026-000011', 'New order placed — ₪1,222.00', 'new_order', '{\"order_id\":\"e39e531f-02d7-402b-bfa1-4bc7911fdca0\",\"order_number\":\"ORD-2026-000011\",\"total_amount\":\"1222\",\"status\":\"pending\"}', 0, '2026-04-21 03:07:03'),
('06c7ae69-3697-11f1-9448-e66239f31cb0', '5732093a-a1d5-46ba-9432-ac9f560ba03c', '🎉 Order Delivered!', 'Your order #ORD-2026-000005 has been delivered. Enjoy and rate your experience!', 'order_status', '{\"order_id\":\"7175c222-70ed-49bf-929d-11fb434f107a\",\"order_number\":\"ORD-2026-000005\",\"status\":\"delivered\"}', 0, '2026-04-12 20:42:50'),
('06ddf6fb-3697-11f1-9448-e66239f31cb0', '8f266f22-1d9d-11f1-b82d-97044e7d8834', 'Order Delivered #ORD-2026-000005', 'Order has been successfully delivered.', 'order_status', '{\"order_id\":\"7175c222-70ed-49bf-929d-11fb434f107a\",\"order_number\":\"ORD-2026-000005\",\"status\":\"delivered\"}', 0, '2026-04-12 20:42:50'),
('07171db9-35de-11f1-9448-e66239f31cb0', '43d5d6a2-e09d-4581-8636-557cd150b0f1', '✅ Order Confirmed!', 'Your order #ORD-2026-000004 has been confirmed and sent to the vendor.', 'order_status', '{\"order_id\":\"1e8023a6-a8d0-42d5-b4bb-4c5317631166\",\"order_number\":\"ORD-2026-000004\",\"status\":\"sent_to_vendor\"}', 1, '2026-04-11 19:38:34'),
('073f47af-3d16-11f1-9448-e66239f31cb0', '8f266f22-1d9d-11f1-b82d-97044e7d8834', '🔔 New Order #ORD-2026-000011', 'New order placed — ₪1,222.00', 'new_order', '{\"order_id\":\"e39e531f-02d7-402b-bfa1-4bc7911fdca0\",\"order_number\":\"ORD-2026-000011\",\"total_amount\":\"1222\",\"status\":\"pending\"}', 0, '2026-04-21 03:07:04'),
('07c5115b-3d16-11f1-9448-e66239f31cb0', '4f792c75-4a65-45a7-a0ef-d282acd83d87', '📦 New Order Received', 'Order #ORD-2026-000011 — ₪1,222.00', 'new_order', '{\"order_id\":\"e39e531f-02d7-402b-bfa1-4bc7911fdca0\",\"order_number\":\"ORD-2026-000011\",\"total_amount\":\"1222\",\"status\":\"pending\"}', 0, '2026-04-21 03:07:05'),
('07d813f9-3b30-11f1-9448-e66239f31cb0', '43d5d6a2-e09d-4581-8636-557cd150b0f1', '✅ Order Confirmed!', 'Your order #ORD-2026-000007 has been confirmed and sent to the vendor.', 'order_status', '{\"order_id\":\"937c9853-526f-41e1-9be0-06a69ff2b437\",\"order_number\":\"ORD-2026-000007\",\"status\":\"sent_to_vendor\"}', 0, '2026-04-18 17:08:10'),
('07fabd76-35de-11f1-9448-e66239f31cb0', '4f792c75-4a65-45a7-a0ef-d282acd83d87', '📦 New Order to Prepare', 'Order #ORD-2026-000004 — Please accept and start preparing.', 'new_order', '{\"order_id\":\"1e8023a6-a8d0-42d5-b4bb-4c5317631166\",\"order_number\":\"ORD-2026-000004\",\"status\":\"sent_to_vendor\"}', 0, '2026-04-11 19:38:35'),
('08053b71-35ce-11f1-9448-e66239f31cb0', '43d5d6a2-e09d-4581-8636-557cd150b0f1', '👨‍🍳 Order Being Prepared', 'Your order #ORD-2026-000002 is now being prepared!', 'order_status', '{\"order_id\":\"a1c23979-4844-4910-b6c7-d96f1d5807af\",\"order_number\":\"ORD-2026-000002\",\"status\":\"preparing\"}', 1, '2026-04-11 17:44:03'),
('08457a2f-35cf-11f1-9448-e66239f31cb0', 'd8e13ad8-2382-11f1-b82d-97044e7d8834', '🔔 New Order #ORD-2026-000003', 'New order placed — ₪1,377.00', 'new_order', '{\"order_id\":\"7a40fa48-aadc-4a85-80fc-57599d58a973\",\"order_number\":\"ORD-2026-000003\",\"total_amount\":\"1377\",\"status\":\"pending\"}', 0, '2026-04-11 17:51:13'),
('08608ac7-35de-11f1-9448-e66239f31cb0', 'cf2239d2-2dd7-4116-abd3-1f24d48e8fe7', '📦 New Order to Prepare', 'Order #ORD-2026-000004 — Please accept and start preparing.', 'new_order', '{\"order_id\":\"1e8023a6-a8d0-42d5-b4bb-4c5317631166\",\"order_number\":\"ORD-2026-000004\",\"status\":\"sent_to_vendor\"}', 0, '2026-04-11 19:38:36'),
('0893edb4-35de-11f1-9448-e66239f31cb0', '8f266f22-1d9d-11f1-b82d-97044e7d8834', 'Order #ORD-2026-000004 Confirmed', 'Confirmed by admin and sent to vendor(s)', 'order_status', '{\"order_id\":\"1e8023a6-a8d0-42d5-b4bb-4c5317631166\",\"order_number\":\"ORD-2026-000004\",\"status\":\"sent_to_vendor\"}', 0, '2026-04-11 19:38:36'),
('08d56c19-35cf-11f1-9448-e66239f31cb0', '8f266f22-1d9d-11f1-b82d-97044e7d8834', '🔔 New Order #ORD-2026-000003', 'New order placed — ₪1,377.00', 'new_order', '{\"order_id\":\"7a40fa48-aadc-4a85-80fc-57599d58a973\",\"order_number\":\"ORD-2026-000003\",\"total_amount\":\"1377\",\"status\":\"pending\"}', 0, '2026-04-11 17:51:14'),
('0921c059-3b30-11f1-9448-e66239f31cb0', '4f792c75-4a65-45a7-a0ef-d282acd83d87', '📦 New Order to Prepare', 'Order #ORD-2026-000007 — Please accept and start preparing.', 'new_order', '{\"order_id\":\"937c9853-526f-41e1-9be0-06a69ff2b437\",\"order_number\":\"ORD-2026-000007\",\"status\":\"sent_to_vendor\"}', 0, '2026-04-18 17:08:12'),
('095be71f-35cf-11f1-9448-e66239f31cb0', '4f792c75-4a65-45a7-a0ef-d282acd83d87', '📦 New Order Received', 'Order #ORD-2026-000003 — ₪1,377.00', 'new_order', '{\"order_id\":\"7a40fa48-aadc-4a85-80fc-57599d58a973\",\"order_number\":\"ORD-2026-000003\",\"total_amount\":\"1377\",\"status\":\"pending\"}', 0, '2026-04-11 17:51:15'),
('098c5cdb-3b30-11f1-9448-e66239f31cb0', '8f266f22-1d9d-11f1-b82d-97044e7d8834', 'Order #ORD-2026-000007 Confirmed', 'Confirmed by admin and sent to vendor(s)', 'order_status', '{\"order_id\":\"937c9853-526f-41e1-9be0-06a69ff2b437\",\"order_number\":\"ORD-2026-000007\",\"status\":\"sent_to_vendor\"}', 0, '2026-04-18 17:08:12'),
('09bc8d56-35cf-11f1-9448-e66239f31cb0', 'cf2239d2-2dd7-4116-abd3-1f24d48e8fe7', '📦 New Order Received', 'Order #ORD-2026-000003 — ₪1,377.00', 'new_order', '{\"order_id\":\"7a40fa48-aadc-4a85-80fc-57599d58a973\",\"order_number\":\"ORD-2026-000003\",\"total_amount\":\"1377\",\"status\":\"pending\"}', 0, '2026-04-11 17:51:16'),
('0afdddb6-35ce-11f1-9448-e66239f31cb0', 'd8e13ad8-2382-11f1-b82d-97044e7d8834', 'Vendor Ready #ORD-2026-000002', 'فتح الله ماركت is ready (1 vendor(s) still preparing)', 'order_status', '{\"order_id\":\"a1c23979-4844-4910-b6c7-d96f1d5807af\",\"order_number\":\"ORD-2026-000002\",\"status\":\"ready_for_pickup\"}', 0, '2026-04-11 17:44:08'),
('1a5d3bca-3d22-11f1-9448-e66239f31cb0', '8ba11278-3b31-11f1-9448-e66239f31cb0', '🔔 New Order #ORD-2026-000012', 'New order placed — ₪122.00', 'new_order', '{\"order_id\":\"f6203869-9831-43d0-a8c7-b814f55b5de8\",\"order_number\":\"ORD-2026-000012\",\"total_amount\":\"122\",\"status\":\"pending\"}', 0, '2026-04-21 04:33:30'),
('1a5d4e46-3d22-11f1-9448-e66239f31cb0', 'd8e13ad8-2382-11f1-b82d-97044e7d8834', '🔔 New Order #ORD-2026-000012', 'New order placed — ₪122.00', 'new_order', '{\"order_id\":\"f6203869-9831-43d0-a8c7-b814f55b5de8\",\"order_number\":\"ORD-2026-000012\",\"total_amount\":\"122\",\"status\":\"pending\"}', 0, '2026-04-21 04:33:30'),
('1aeb2b13-3d22-11f1-9448-e66239f31cb0', 'e64dd274-3d20-11f1-9448-e66239f31cb0', '🔔 New Order #ORD-2026-000012', 'New order placed — ₪122.00', 'new_order', '{\"order_id\":\"f6203869-9831-43d0-a8c7-b814f55b5de8\",\"order_number\":\"ORD-2026-000012\",\"total_amount\":\"122\",\"status\":\"pending\"}', 0, '2026-04-21 04:33:31'),
('1af9ab32-3d22-11f1-9448-e66239f31cb0', 'f52cc98d-3d20-11f1-9448-e66239f31cb0', '🔔 New Order #ORD-2026-000012', 'New order placed — ₪122.00', 'new_order', '{\"order_id\":\"f6203869-9831-43d0-a8c7-b814f55b5de8\",\"order_number\":\"ORD-2026-000012\",\"total_amount\":\"122\",\"status\":\"pending\"}', 0, '2026-04-21 04:33:31'),
('1b09255a-3d22-11f1-9448-e66239f31cb0', '8f266f22-1d9d-11f1-b82d-97044e7d8834', '🔔 New Order #ORD-2026-000012', 'New order placed — ₪122.00', 'new_order', '{\"order_id\":\"f6203869-9831-43d0-a8c7-b814f55b5de8\",\"order_number\":\"ORD-2026-000012\",\"total_amount\":\"122\",\"status\":\"pending\"}', 0, '2026-04-21 04:33:31'),
('1b879433-3d22-11f1-9448-e66239f31cb0', '68d947ef-fcec-426a-8316-155666848619', '📦 New Order Received', 'Order #ORD-2026-000012 — ₪122.00', 'new_order', '{\"order_id\":\"f6203869-9831-43d0-a8c7-b814f55b5de8\",\"order_number\":\"ORD-2026-000012\",\"total_amount\":\"122\",\"status\":\"pending\"}', 1, '2026-04-21 04:33:32'),
('1eb9a46b-3d90-11f1-9448-e66239f31cb0', '1d8557e5-8a7d-4c32-8181-ad785ea979a0', '🚚 Your Order is On the Way!', 'Order #ORD-2026-000014 is out for delivery.', 'order_status', '{\"order_id\":\"8f957033-af0e-4c09-a600-74344e7b1485\",\"order_number\":\"ORD-2026-000014\",\"status\":\"out_for_delivery\"}', 1, '2026-04-21 17:41:02'),
('1ecfceb1-3d90-11f1-9448-e66239f31cb0', '68d947ef-fcec-426a-8316-155666848619', 'Order Dispatched #ORD-2026-000014', 'Order has been dispatched for delivery.', 'order_status', '{\"order_id\":\"8f957033-af0e-4c09-a600-74344e7b1485\",\"order_number\":\"ORD-2026-000014\",\"status\":\"out_for_delivery\"}', 0, '2026-04-21 17:41:02'),
('21b501ce-3d90-11f1-9448-e66239f31cb0', '1d8557e5-8a7d-4c32-8181-ad785ea979a0', '🎉 Order Delivered!', 'Your order #ORD-2026-000014 has been delivered. Enjoy and rate your experience!', 'order_status', '{\"order_id\":\"8f957033-af0e-4c09-a600-74344e7b1485\",\"order_number\":\"ORD-2026-000014\",\"status\":\"delivered\"}', 1, '2026-04-21 17:41:07'),
('21cc997d-3d90-11f1-9448-e66239f31cb0', '8f266f22-1d9d-11f1-b82d-97044e7d8834', 'Order Delivered #ORD-2026-000014', 'Order has been successfully delivered.', 'order_status', '{\"order_id\":\"8f957033-af0e-4c09-a600-74344e7b1485\",\"order_number\":\"ORD-2026-000014\",\"status\":\"delivered\"}', 0, '2026-04-21 17:41:07'),
('2494fb36-3d16-11f1-9448-e66239f31cb0', '1d8557e5-8a7d-4c32-8181-ad785ea979a0', '🍳 Order Accepted!', 'All vendors accepted your order #ORD-2026-000011', 'order_status', '{\"order_id\":\"e39e531f-02d7-402b-bfa1-4bc7911fdca0\",\"order_number\":\"ORD-2026-000011\",\"status\":\"vendor_accepted\"}', 1, '2026-04-21 03:07:53'),
('24ab3624-3d16-11f1-9448-e66239f31cb0', '8ba11278-3b31-11f1-9448-e66239f31cb0', 'All Vendors Accepted #ORD-2026-000011', 'All vendors have accepted order #ORD-2026-000011', 'order_status', '{\"order_id\":\"e39e531f-02d7-402b-bfa1-4bc7911fdca0\",\"order_number\":\"ORD-2026-000011\",\"status\":\"vendor_accepted\"}', 0, '2026-04-21 03:07:53'),
('24ab4b8c-3d16-11f1-9448-e66239f31cb0', 'd8e13ad8-2382-11f1-b82d-97044e7d8834', 'All Vendors Accepted #ORD-2026-000011', 'All vendors have accepted order #ORD-2026-000011', 'order_status', '{\"order_id\":\"e39e531f-02d7-402b-bfa1-4bc7911fdca0\",\"order_number\":\"ORD-2026-000011\",\"status\":\"vendor_accepted\"}', 0, '2026-04-21 03:07:53'),
('261f6429-35ce-11f1-9448-e66239f31cb0', '43d5d6a2-e09d-4581-8636-557cd150b0f1', '✅ Order Ready!', 'Your order #ORD-2026-000002 is ready for pickup!', 'order_status', '{\"order_id\":\"a1c23979-4844-4910-b6c7-d96f1d5807af\",\"order_number\":\"ORD-2026-000002\",\"status\":\"ready_for_pickup\"}', 1, '2026-04-11 17:44:54'),
('26cff7fd-35ce-11f1-9448-e66239f31cb0', 'd8e13ad8-2382-11f1-b82d-97044e7d8834', '🚚 Ready for Pickup #ORD-2026-000002', 'All vendors ready — dispatch delivery now!', 'order_status', '{\"order_id\":\"a1c23979-4844-4910-b6c7-d96f1d5807af\",\"order_number\":\"ORD-2026-000002\",\"status\":\"ready_for_pickup\"}', 0, '2026-04-11 17:44:55'),
('2e711253-3698-11f1-9448-e66239f31cb0', 'd8e13ad8-2382-11f1-b82d-97044e7d8834', '🔔 New Order #ORD-2026-000007', 'New order placed — ₪522.00', 'new_order', '{\"order_id\":\"937c9853-526f-41e1-9be0-06a69ff2b437\",\"order_number\":\"ORD-2026-000007\",\"total_amount\":\"522\",\"status\":\"pending\"}', 0, '2026-04-12 20:51:06'),
('2f097562-3698-11f1-9448-e66239f31cb0', '8f266f22-1d9d-11f1-b82d-97044e7d8834', '🔔 New Order #ORD-2026-000007', 'New order placed — ₪522.00', 'new_order', '{\"order_id\":\"937c9853-526f-41e1-9be0-06a69ff2b437\",\"order_number\":\"ORD-2026-000007\",\"total_amount\":\"522\",\"status\":\"pending\"}', 0, '2026-04-12 20:51:07'),
('2f4863d2-35de-11f1-9448-e66239f31cb0', 'd8e13ad8-2382-11f1-b82d-97044e7d8834', 'Vendor Accepted #ORD-2026-000004', 'فتح الله ماركت accepted the order (1 vendor(s) still pending)', 'order_status', '{\"order_id\":\"1e8023a6-a8d0-42d5-b4bb-4c5317631166\",\"order_number\":\"ORD-2026-000004\",\"status\":\"vendor_accepted\"}', 0, '2026-04-11 19:39:41'),
('2f8f0daa-3698-11f1-9448-e66239f31cb0', '4f792c75-4a65-45a7-a0ef-d282acd83d87', '📦 New Order Received', 'Order #ORD-2026-000007 — ₪522.00', 'new_order', '{\"order_id\":\"937c9853-526f-41e1-9be0-06a69ff2b437\",\"order_number\":\"ORD-2026-000007\",\"total_amount\":\"522\",\"status\":\"pending\"}', 0, '2026-04-12 20:51:08'),
('37069d06-35de-11f1-9448-e66239f31cb0', 'd8e13ad8-2382-11f1-b82d-97044e7d8834', 'Vendor Ready #ORD-2026-000004', 'فتح الله ماركت is ready (1 vendor(s) still preparing)', 'order_status', '{\"order_id\":\"1e8023a6-a8d0-42d5-b4bb-4c5317631166\",\"order_number\":\"ORD-2026-000004\",\"status\":\"ready_for_pickup\"}', 0, '2026-04-11 19:39:54'),
('382708af-374c-11f1-9448-e66239f31cb0', 'd8e13ad8-2382-11f1-b82d-97044e7d8834', '🔔 New Order #ORD-2026-000009', 'New order placed — ₪1,822.00', 'new_order', '{\"order_id\":\"ade4e928-3973-4f5a-8b9b-42b7608ddb22\",\"order_number\":\"ORD-2026-000009\",\"total_amount\":\"1822\",\"status\":\"pending\"}', 0, '2026-04-13 18:19:52'),
('38b46a19-374c-11f1-9448-e66239f31cb0', '8f266f22-1d9d-11f1-b82d-97044e7d8834', '🔔 New Order #ORD-2026-000009', 'New order placed — ₪1,822.00', 'new_order', '{\"order_id\":\"ade4e928-3973-4f5a-8b9b-42b7608ddb22\",\"order_number\":\"ORD-2026-000009\",\"total_amount\":\"1822\",\"status\":\"pending\"}', 0, '2026-04-13 18:19:53'),
('394391bc-374c-11f1-9448-e66239f31cb0', 'cf2239d2-2dd7-4116-abd3-1f24d48e8fe7', '📦 New Order Received', 'Order #ORD-2026-000009 — ₪1,822.00', 'new_order', '{\"order_id\":\"ade4e928-3973-4f5a-8b9b-42b7608ddb22\",\"order_number\":\"ORD-2026-000009\",\"total_amount\":\"1822\",\"status\":\"pending\"}', 0, '2026-04-13 18:19:54'),
('4560e235-3d22-11f1-9448-e66239f31cb0', '1d8557e5-8a7d-4c32-8181-ad785ea979a0', '🍳 Order Accepted!', 'All vendors accepted your order #ORD-2026-000012', 'order_status', '{\"order_id\":\"f6203869-9831-43d0-a8c7-b814f55b5de8\",\"order_number\":\"ORD-2026-000012\",\"status\":\"vendor_accepted\"}', 1, '2026-04-21 04:34:42'),
('457b50be-3d22-11f1-9448-e66239f31cb0', '8ba11278-3b31-11f1-9448-e66239f31cb0', 'All Vendors Accepted #ORD-2026-000012', 'All vendors have accepted order #ORD-2026-000012', 'order_status', '{\"order_id\":\"f6203869-9831-43d0-a8c7-b814f55b5de8\",\"order_number\":\"ORD-2026-000012\",\"status\":\"vendor_accepted\"}', 0, '2026-04-21 04:34:42'),
('457b69c1-3d22-11f1-9448-e66239f31cb0', 'd8e13ad8-2382-11f1-b82d-97044e7d8834', 'All Vendors Accepted #ORD-2026-000012', 'All vendors have accepted order #ORD-2026-000012', 'order_status', '{\"order_id\":\"f6203869-9831-43d0-a8c7-b814f55b5de8\",\"order_number\":\"ORD-2026-000012\",\"status\":\"vendor_accepted\"}', 0, '2026-04-21 04:34:42'),
('45f255f9-3d22-11f1-9448-e66239f31cb0', 'e64dd274-3d20-11f1-9448-e66239f31cb0', 'All Vendors Accepted #ORD-2026-000012', 'All vendors have accepted order #ORD-2026-000012', 'order_status', '{\"order_id\":\"f6203869-9831-43d0-a8c7-b814f55b5de8\",\"order_number\":\"ORD-2026-000012\",\"status\":\"vendor_accepted\"}', 0, '2026-04-21 04:34:43'),
('45fdd6d1-3d22-11f1-9448-e66239f31cb0', 'f52cc98d-3d20-11f1-9448-e66239f31cb0', 'All Vendors Accepted #ORD-2026-000012', 'All vendors have accepted order #ORD-2026-000012', 'order_status', '{\"order_id\":\"f6203869-9831-43d0-a8c7-b814f55b5de8\",\"order_number\":\"ORD-2026-000012\",\"status\":\"vendor_accepted\"}', 0, '2026-04-21 04:34:43'),
('50ac69f4-3d22-11f1-9448-e66239f31cb0', '1d8557e5-8a7d-4c32-8181-ad785ea979a0', '👨‍🍳 Order Being Prepared', 'Your order #ORD-2026-000012 is now being prepared!', 'order_status', '{\"order_id\":\"f6203869-9831-43d0-a8c7-b814f55b5de8\",\"order_number\":\"ORD-2026-000012\",\"status\":\"preparing\"}', 1, '2026-04-21 04:35:01'),
('5211973a-35cd-11f1-9448-e66239f31cb0', 'd8e13ad8-2382-11f1-b82d-97044e7d8834', '🔔 New Order #ORD-2026-000001', 'New order placed — ₪402.00', 'new_order', '{\"order_id\":\"1fa6fc17-b531-490c-a327-775013af98db\",\"order_number\":\"ORD-2026-000001\",\"total_amount\":\"402\",\"status\":\"pending\"}', 0, '2026-04-11 17:38:58'),
('529f6487-35cd-11f1-9448-e66239f31cb0', '8f266f22-1d9d-11f1-b82d-97044e7d8834', '🔔 New Order #ORD-2026-000001', 'New order placed — ₪402.00', 'new_order', '{\"order_id\":\"1fa6fc17-b531-490c-a327-775013af98db\",\"order_number\":\"ORD-2026-000001\",\"total_amount\":\"402\",\"status\":\"pending\"}', 0, '2026-04-11 17:38:59'),
('52e31312-3d22-11f1-9448-e66239f31cb0', '1d8557e5-8a7d-4c32-8181-ad785ea979a0', '✅ Order Ready!', 'Your order #ORD-2026-000012 is ready for pickup!', 'order_status', '{\"order_id\":\"f6203869-9831-43d0-a8c7-b814f55b5de8\",\"order_number\":\"ORD-2026-000012\",\"status\":\"ready_for_pickup\"}', 1, '2026-04-21 04:35:05'),
('52f5ee68-3d22-11f1-9448-e66239f31cb0', '8ba11278-3b31-11f1-9448-e66239f31cb0', '🚚 Ready for Pickup #ORD-2026-000012', 'All vendors ready — dispatch delivery now!', 'order_status', '{\"order_id\":\"f6203869-9831-43d0-a8c7-b814f55b5de8\",\"order_number\":\"ORD-2026-000012\",\"status\":\"ready_for_pickup\"}', 0, '2026-04-21 04:35:05'),
('52f60686-3d22-11f1-9448-e66239f31cb0', 'd8e13ad8-2382-11f1-b82d-97044e7d8834', '🚚 Ready for Pickup #ORD-2026-000012', 'All vendors ready — dispatch delivery now!', 'order_status', '{\"order_id\":\"f6203869-9831-43d0-a8c7-b814f55b5de8\",\"order_number\":\"ORD-2026-000012\",\"status\":\"ready_for_pickup\"}', 0, '2026-04-21 04:35:05'),
('53217bc5-35cd-11f1-9448-e66239f31cb0', '4f792c75-4a65-45a7-a0ef-d282acd83d87', '📦 New Order Received', 'Order #ORD-2026-000001 — ₪402.00', 'new_order', '{\"order_id\":\"1fa6fc17-b531-490c-a327-775013af98db\",\"order_number\":\"ORD-2026-000001\",\"total_amount\":\"402\",\"status\":\"pending\"}', 0, '2026-04-11 17:39:00'),
('5370a862-3d22-11f1-9448-e66239f31cb0', 'e64dd274-3d20-11f1-9448-e66239f31cb0', '🚚 Ready for Pickup #ORD-2026-000012', 'All vendors ready — dispatch delivery now!', 'order_status', '{\"order_id\":\"f6203869-9831-43d0-a8c7-b814f55b5de8\",\"order_number\":\"ORD-2026-000012\",\"status\":\"ready_for_pickup\"}', 0, '2026-04-21 04:35:06'),
('537ef081-3d22-11f1-9448-e66239f31cb0', 'f52cc98d-3d20-11f1-9448-e66239f31cb0', '🚚 Ready for Pickup #ORD-2026-000012', 'All vendors ready — dispatch delivery now!', 'order_status', '{\"order_id\":\"f6203869-9831-43d0-a8c7-b814f55b5de8\",\"order_number\":\"ORD-2026-000012\",\"status\":\"ready_for_pickup\"}', 0, '2026-04-21 04:35:06'),
('59324a48-3d8f-11f1-9448-e66239f31cb0', '8ba11278-3b31-11f1-9448-e66239f31cb0', '🔔 New Order #ORD-2026-000014', 'New order placed — ₪112.00', 'new_order', '{\"order_id\":\"8f957033-af0e-4c09-a600-74344e7b1485\",\"order_number\":\"ORD-2026-000014\",\"total_amount\":\"112\",\"status\":\"pending\"}', 0, '2026-04-21 17:35:31'),
('593276ee-3d8f-11f1-9448-e66239f31cb0', 'd8e13ad8-2382-11f1-b82d-97044e7d8834', '🔔 New Order #ORD-2026-000014', 'New order placed — ₪112.00', 'new_order', '{\"order_id\":\"8f957033-af0e-4c09-a600-74344e7b1485\",\"order_number\":\"ORD-2026-000014\",\"total_amount\":\"112\",\"status\":\"pending\"}', 0, '2026-04-21 17:35:31'),
('59c7f5c8-3d8f-11f1-9448-e66239f31cb0', 'e64dd274-3d20-11f1-9448-e66239f31cb0', '🔔 New Order #ORD-2026-000014', 'New order placed — ₪112.00', 'new_order', '{\"order_id\":\"8f957033-af0e-4c09-a600-74344e7b1485\",\"order_number\":\"ORD-2026-000014\",\"total_amount\":\"112\",\"status\":\"pending\"}', 1, '2026-04-21 17:35:32'),
('59d8e522-3d8f-11f1-9448-e66239f31cb0', 'f52cc98d-3d20-11f1-9448-e66239f31cb0', '🔔 New Order #ORD-2026-000014', 'New order placed — ₪112.00', 'new_order', '{\"order_id\":\"8f957033-af0e-4c09-a600-74344e7b1485\",\"order_number\":\"ORD-2026-000014\",\"total_amount\":\"112\",\"status\":\"pending\"}', 0, '2026-04-21 17:35:32'),
('59e559f6-3d8f-11f1-9448-e66239f31cb0', '8f266f22-1d9d-11f1-b82d-97044e7d8834', '🔔 New Order #ORD-2026-000014', 'New order placed — ₪112.00', 'new_order', '{\"order_id\":\"8f957033-af0e-4c09-a600-74344e7b1485\",\"order_number\":\"ORD-2026-000014\",\"total_amount\":\"112\",\"status\":\"pending\"}', 0, '2026-04-21 17:35:32'),
('5a6fbccc-3d8f-11f1-9448-e66239f31cb0', '68d947ef-fcec-426a-8316-155666848619', '📦 New Order Received', 'Order #ORD-2026-000014 — ₪112.00', 'new_order', '{\"order_id\":\"8f957033-af0e-4c09-a600-74344e7b1485\",\"order_number\":\"ORD-2026-000014\",\"total_amount\":\"112\",\"status\":\"pending\"}', 0, '2026-04-21 17:35:33'),
('5e75c0d2-3c4f-11f1-9448-e66239f31cb0', '8ba11278-3b31-11f1-9448-e66239f31cb0', '🔔 New Order #ORD-2026-000010', 'New order placed — ₪522.00', 'new_order', '{\"order_id\":\"d697bc38-0e26-476d-9790-85e0972c75c1\",\"order_number\":\"ORD-2026-000010\",\"total_amount\":\"522\",\"status\":\"pending\"}', 0, '2026-04-20 03:25:01'),
('5e760504-3c4f-11f1-9448-e66239f31cb0', 'd8e13ad8-2382-11f1-b82d-97044e7d8834', '🔔 New Order #ORD-2026-000010', 'New order placed — ₪522.00', 'new_order', '{\"order_id\":\"d697bc38-0e26-476d-9790-85e0972c75c1\",\"order_number\":\"ORD-2026-000010\",\"total_amount\":\"522\",\"status\":\"pending\"}', 0, '2026-04-20 03:25:01'),
('5f14e3eb-3c4f-11f1-9448-e66239f31cb0', '8f266f22-1d9d-11f1-b82d-97044e7d8834', '🔔 New Order #ORD-2026-000010', 'New order placed — ₪522.00', 'new_order', '{\"order_id\":\"d697bc38-0e26-476d-9790-85e0972c75c1\",\"order_number\":\"ORD-2026-000010\",\"total_amount\":\"522\",\"status\":\"pending\"}', 0, '2026-04-20 03:25:02'),
('5f9d0da6-3c4f-11f1-9448-e66239f31cb0', '4f792c75-4a65-45a7-a0ef-d282acd83d87', '📦 New Order Received', 'Order #ORD-2026-000010 — ₪522.00', 'new_order', '{\"order_id\":\"d697bc38-0e26-476d-9790-85e0972c75c1\",\"order_number\":\"ORD-2026-000010\",\"total_amount\":\"522\",\"status\":\"pending\"}', 0, '2026-04-20 03:25:02'),
('6170e15c-35cd-11f1-9448-e66239f31cb0', 'd8e13ad8-2382-11f1-b82d-97044e7d8834', '🔔 New Order #ORD-2026-000002', 'New order placed — ₪1,377.00', 'new_order', '{\"order_id\":\"a1c23979-4844-4910-b6c7-d96f1d5807af\",\"order_number\":\"ORD-2026-000002\",\"total_amount\":\"1377\",\"status\":\"pending\"}', 0, '2026-04-11 17:39:24'),
('6180863e-3d98-11f1-9448-e66239f31cb0', '8ba11278-3b31-11f1-9448-e66239f31cb0', '🔔 New Order #ORD-2026-000015', 'New order placed — ₪1,132.00', 'new_order', '{\"order_id\":\"f5d055ec-9300-445f-af99-77077d8cc519\",\"order_number\":\"ORD-2026-000015\",\"total_amount\":\"1132\",\"status\":\"pending\"}', 0, '2026-04-21 18:40:10'),
('61809821-3d98-11f1-9448-e66239f31cb0', 'd8e13ad8-2382-11f1-b82d-97044e7d8834', '🔔 New Order #ORD-2026-000015', 'New order placed — ₪1,132.00', 'new_order', '{\"order_id\":\"f5d055ec-9300-445f-af99-77077d8cc519\",\"order_number\":\"ORD-2026-000015\",\"total_amount\":\"1132\",\"status\":\"pending\"}', 0, '2026-04-21 18:40:10'),
('61e84ed7-35cd-11f1-9448-e66239f31cb0', '8f266f22-1d9d-11f1-b82d-97044e7d8834', '🔔 New Order #ORD-2026-000002', 'New order placed — ₪1,377.00', 'new_order', '{\"order_id\":\"a1c23979-4844-4910-b6c7-d96f1d5807af\",\"order_number\":\"ORD-2026-000002\",\"total_amount\":\"1377\",\"status\":\"pending\"}', 0, '2026-04-11 17:39:25'),
('62100c2a-3d98-11f1-9448-e66239f31cb0', 'e64dd274-3d20-11f1-9448-e66239f31cb0', '🔔 New Order #ORD-2026-000015', 'New order placed — ₪1,132.00', 'new_order', '{\"order_id\":\"f5d055ec-9300-445f-af99-77077d8cc519\",\"order_number\":\"ORD-2026-000015\",\"total_amount\":\"1132\",\"status\":\"pending\"}', 0, '2026-04-21 18:40:11'),
('621fa0c5-3d98-11f1-9448-e66239f31cb0', 'f52cc98d-3d20-11f1-9448-e66239f31cb0', '🔔 New Order #ORD-2026-000015', 'New order placed — ₪1,132.00', 'new_order', '{\"order_id\":\"f5d055ec-9300-445f-af99-77077d8cc519\",\"order_number\":\"ORD-2026-000015\",\"total_amount\":\"1132\",\"status\":\"pending\"}', 0, '2026-04-21 18:40:11'),
('622ebad2-3d98-11f1-9448-e66239f31cb0', '8f266f22-1d9d-11f1-b82d-97044e7d8834', '🔔 New Order #ORD-2026-000015', 'New order placed — ₪1,132.00', 'new_order', '{\"order_id\":\"f5d055ec-9300-445f-af99-77077d8cc519\",\"order_number\":\"ORD-2026-000015\",\"total_amount\":\"1132\",\"status\":\"pending\"}', 0, '2026-04-21 18:40:11'),
('626e1e06-35cd-11f1-9448-e66239f31cb0', '4f792c75-4a65-45a7-a0ef-d282acd83d87', '📦 New Order Received', 'Order #ORD-2026-000002 — ₪1,377.00', 'new_order', '{\"order_id\":\"a1c23979-4844-4910-b6c7-d96f1d5807af\",\"order_number\":\"ORD-2026-000002\",\"total_amount\":\"1377\",\"status\":\"pending\"}', 1, '2026-04-11 17:39:26'),
('62be3b81-3d98-11f1-9448-e66239f31cb0', '68d947ef-fcec-426a-8316-155666848619', '📦 New Order Received', 'Order #ORD-2026-000015 — ₪1,132.00', 'new_order', '{\"order_id\":\"f5d055ec-9300-445f-af99-77077d8cc519\",\"order_number\":\"ORD-2026-000015\",\"total_amount\":\"1132\",\"status\":\"pending\"}', 0, '2026-04-21 18:40:12'),
('62cc502c-3d98-11f1-9448-e66239f31cb0', '6255a20b-3d97-11f1-9448-e66239f31cb0', '📦 New Order Received', 'Order #ORD-2026-000015 — ₪1,132.00', 'new_order', '{\"order_id\":\"f5d055ec-9300-445f-af99-77077d8cc519\",\"order_number\":\"ORD-2026-000015\",\"total_amount\":\"1132\",\"status\":\"pending\"}', 1, '2026-04-21 18:40:12'),
('62cf2870-35cd-11f1-9448-e66239f31cb0', 'cf2239d2-2dd7-4116-abd3-1f24d48e8fe7', '📦 New Order Received', 'Order #ORD-2026-000002 — ₪1,377.00', 'new_order', '{\"order_id\":\"a1c23979-4844-4910-b6c7-d96f1d5807af\",\"order_number\":\"ORD-2026-000002\",\"total_amount\":\"1377\",\"status\":\"pending\"}', 0, '2026-04-11 17:39:26'),
('64dbb276-35de-11f1-9448-e66239f31cb0', 'd8e13ad8-2382-11f1-b82d-97044e7d8834', 'Vendor Accepted #ORD-2026-000004', 'سوق accepted the order (1 vendor(s) still pending)', 'order_status', '{\"order_id\":\"1e8023a6-a8d0-42d5-b4bb-4c5317631166\",\"order_number\":\"ORD-2026-000004\",\"status\":\"vendor_accepted\"}', 0, '2026-04-11 19:41:11'),
('6e59f777-35de-11f1-9448-e66239f31cb0', '43d5d6a2-e09d-4581-8636-557cd150b0f1', '✅ Order Ready!', 'Your order #ORD-2026-000004 is ready for pickup!', 'order_status', '{\"order_id\":\"1e8023a6-a8d0-42d5-b4bb-4c5317631166\",\"order_number\":\"ORD-2026-000004\",\"status\":\"ready_for_pickup\"}', 1, '2026-04-11 19:41:27'),
('6f0e9ef8-35de-11f1-9448-e66239f31cb0', 'd8e13ad8-2382-11f1-b82d-97044e7d8834', '🚚 Ready for Pickup #ORD-2026-000004', 'All vendors ready — dispatch delivery now!', 'order_status', '{\"order_id\":\"1e8023a6-a8d0-42d5-b4bb-4c5317631166\",\"order_number\":\"ORD-2026-000004\",\"status\":\"ready_for_pickup\"}', 0, '2026-04-11 19:41:28'),
('7a51b887-36cf-11f1-9448-e66239f31cb0', 'd8e13ad8-2382-11f1-b82d-97044e7d8834', '🔔 New Order #ORD-2026-000008', 'New order placed — ₪2,082.00', 'new_order', '{\"order_id\":\"fefb970d-6c86-42fa-ac39-d0c6c17fe37c\",\"order_number\":\"ORD-2026-000008\",\"total_amount\":\"2082\",\"status\":\"pending\"}', 0, '2026-04-13 03:26:56'),
('7add00c6-36cf-11f1-9448-e66239f31cb0', '8f266f22-1d9d-11f1-b82d-97044e7d8834', '🔔 New Order #ORD-2026-000008', 'New order placed — ₪2,082.00', 'new_order', '{\"order_id\":\"fefb970d-6c86-42fa-ac39-d0c6c17fe37c\",\"order_number\":\"ORD-2026-000008\",\"total_amount\":\"2082\",\"status\":\"pending\"}', 0, '2026-04-13 03:26:57'),
('7b5cc520-36cf-11f1-9448-e66239f31cb0', '4f792c75-4a65-45a7-a0ef-d282acd83d87', '📦 New Order Received', 'Order #ORD-2026-000008 — ₪2,082.00', 'new_order', '{\"order_id\":\"fefb970d-6c86-42fa-ac39-d0c6c17fe37c\",\"order_number\":\"ORD-2026-000008\",\"total_amount\":\"2082\",\"status\":\"pending\"}', 0, '2026-04-13 03:26:58'),
('871120da-3b30-11f1-9448-e66239f31cb0', '43d5d6a2-e09d-4581-8636-557cd150b0f1', '🍳 Order Accepted!', 'All vendors accepted your order #ORD-2026-000001', 'order_status', '{\"order_id\":\"1fa6fc17-b531-490c-a327-775013af98db\",\"order_number\":\"ORD-2026-000001\",\"status\":\"vendor_accepted\"}', 0, '2026-04-18 17:11:43'),
('8808b54b-35de-11f1-9448-e66239f31cb0', '43d5d6a2-e09d-4581-8636-557cd150b0f1', '🚚 Your Order is On the Way!', 'Order #ORD-2026-000004 is out for delivery.', 'order_status', '{\"order_id\":\"1e8023a6-a8d0-42d5-b4bb-4c5317631166\",\"order_number\":\"ORD-2026-000004\",\"status\":\"out_for_delivery\"}', 1, '2026-04-11 19:42:10'),
('885241f0-3b30-11f1-9448-e66239f31cb0', 'd8e13ad8-2382-11f1-b82d-97044e7d8834', 'All Vendors Accepted #ORD-2026-000001', 'All vendors have accepted order #ORD-2026-000001', 'order_status', '{\"order_id\":\"1fa6fc17-b531-490c-a327-775013af98db\",\"order_number\":\"ORD-2026-000001\",\"status\":\"vendor_accepted\"}', 0, '2026-04-18 17:11:45'),
('8868f188-36cf-11f1-9448-e66239f31cb0', '0717139f-9fbc-4c8c-be98-cf1361e0036c', '🍳 Order Accepted!', 'All vendors accepted your order #ORD-2026-000008', 'order_status', '{\"order_id\":\"fefb970d-6c86-42fa-ac39-d0c6c17fe37c\",\"order_number\":\"ORD-2026-000008\",\"status\":\"vendor_accepted\"}', 0, '2026-04-13 03:27:19'),
('887b6240-36cf-11f1-9448-e66239f31cb0', 'd8e13ad8-2382-11f1-b82d-97044e7d8834', 'All Vendors Accepted #ORD-2026-000008', 'All vendors have accepted order #ORD-2026-000008', 'order_status', '{\"order_id\":\"fefb970d-6c86-42fa-ac39-d0c6c17fe37c\",\"order_number\":\"ORD-2026-000008\",\"status\":\"vendor_accepted\"}', 0, '2026-04-13 03:27:20'),
('88b752bd-35de-11f1-9448-e66239f31cb0', '4f792c75-4a65-45a7-a0ef-d282acd83d87', 'Order Dispatched #ORD-2026-000004', 'Order has been dispatched for delivery.', 'order_status', '{\"order_id\":\"1e8023a6-a8d0-42d5-b4bb-4c5317631166\",\"order_number\":\"ORD-2026-000004\",\"status\":\"out_for_delivery\"}', 0, '2026-04-11 19:42:11'),
('8922d1fc-35de-11f1-9448-e66239f31cb0', 'cf2239d2-2dd7-4116-abd3-1f24d48e8fe7', 'Order Dispatched #ORD-2026-000004', 'Order has been dispatched for delivery.', 'order_status', '{\"order_id\":\"1e8023a6-a8d0-42d5-b4bb-4c5317631166\",\"order_number\":\"ORD-2026-000004\",\"status\":\"out_for_delivery\"}', 0, '2026-04-11 19:42:12'),
('a121f458-36cf-11f1-9448-e66239f31cb0', '0717139f-9fbc-4c8c-be98-cf1361e0036c', '👨‍🍳 Order Being Prepared', 'Your order #ORD-2026-000008 is now being prepared!', 'order_status', '{\"order_id\":\"fefb970d-6c86-42fa-ac39-d0c6c17fe37c\",\"order_number\":\"ORD-2026-000008\",\"status\":\"preparing\"}', 0, '2026-04-13 03:28:01'),
('a1877ae4-3696-11f1-9448-e66239f31cb0', 'd8e13ad8-2382-11f1-b82d-97044e7d8834', '🔔 New Order #ORD-2026-000005', 'New order placed — ₪702.00', 'new_order', '{\"order_id\":\"7175c222-70ed-49bf-929d-11fb434f107a\",\"order_number\":\"ORD-2026-000005\",\"total_amount\":\"702\",\"status\":\"pending\"}', 0, '2026-04-12 20:40:00'),
('a20d87e9-3696-11f1-9448-e66239f31cb0', '8f266f22-1d9d-11f1-b82d-97044e7d8834', '🔔 New Order #ORD-2026-000005', 'New order placed — ₪702.00', 'new_order', '{\"order_id\":\"7175c222-70ed-49bf-929d-11fb434f107a\",\"order_number\":\"ORD-2026-000005\",\"total_amount\":\"702\",\"status\":\"pending\"}', 0, '2026-04-12 20:40:01'),
('a29a323b-3696-11f1-9448-e66239f31cb0', '4f792c75-4a65-45a7-a0ef-d282acd83d87', '📦 New Order Received', 'Order #ORD-2026-000005 — ₪702.00', 'new_order', '{\"order_id\":\"7175c222-70ed-49bf-929d-11fb434f107a\",\"order_number\":\"ORD-2026-000005\",\"total_amount\":\"702\",\"status\":\"pending\"}', 0, '2026-04-12 20:40:02'),
('a2f3b2cd-36cf-11f1-9448-e66239f31cb0', '0717139f-9fbc-4c8c-be98-cf1361e0036c', '✅ Order Ready!', 'Your order #ORD-2026-000008 is ready for pickup!', 'order_status', '{\"order_id\":\"fefb970d-6c86-42fa-ac39-d0c6c17fe37c\",\"order_number\":\"ORD-2026-000008\",\"status\":\"ready_for_pickup\"}', 0, '2026-04-13 03:28:04'),
('a3064a2d-36cf-11f1-9448-e66239f31cb0', 'd8e13ad8-2382-11f1-b82d-97044e7d8834', '🚚 Ready for Pickup #ORD-2026-000008', 'All vendors ready — dispatch delivery now!', 'order_status', '{\"order_id\":\"fefb970d-6c86-42fa-ac39-d0c6c17fe37c\",\"order_number\":\"ORD-2026-000008\",\"status\":\"ready_for_pickup\"}', 0, '2026-04-13 03:28:04'),
('a6772ff7-35cd-11f1-9448-e66239f31cb0', '43d5d6a2-e09d-4581-8636-557cd150b0f1', '✅ Order Confirmed!', 'Your order #ORD-2026-000002 has been confirmed and sent to the vendor.', 'order_status', '{\"order_id\":\"a1c23979-4844-4910-b6c7-d96f1d5807af\",\"order_number\":\"ORD-2026-000002\",\"status\":\"sent_to_vendor\"}', 1, '2026-04-11 17:41:20'),
('a736ab2a-35cd-11f1-9448-e66239f31cb0', '4f792c75-4a65-45a7-a0ef-d282acd83d87', '📦 New Order to Prepare', 'Order #ORD-2026-000002 — Please accept and start preparing.', 'new_order', '{\"order_id\":\"a1c23979-4844-4910-b6c7-d96f1d5807af\",\"order_number\":\"ORD-2026-000002\",\"status\":\"sent_to_vendor\"}', 0, '2026-04-11 17:41:21'),
('a79c9f76-35cd-11f1-9448-e66239f31cb0', 'cf2239d2-2dd7-4116-abd3-1f24d48e8fe7', '📦 New Order to Prepare', 'Order #ORD-2026-000002 — Please accept and start preparing.', 'new_order', '{\"order_id\":\"a1c23979-4844-4910-b6c7-d96f1d5807af\",\"order_number\":\"ORD-2026-000002\",\"status\":\"sent_to_vendor\"}', 0, '2026-04-11 17:41:22'),
('a7d52fdd-35cd-11f1-9448-e66239f31cb0', '8f266f22-1d9d-11f1-b82d-97044e7d8834', 'Order #ORD-2026-000002 Confirmed', 'Confirmed by admin and sent to vendor(s)', 'order_status', '{\"order_id\":\"a1c23979-4844-4910-b6c7-d96f1d5807af\",\"order_number\":\"ORD-2026-000002\",\"status\":\"sent_to_vendor\"}', 0, '2026-04-11 17:41:22'),
('b009acd7-3d98-11f1-9448-e66239f31cb0', '1d8557e5-8a7d-4c32-8181-ad785ea979a0', '✅ Order Confirmed!', 'Your order #ORD-2026-000015 has been confirmed and sent to the vendor.', 'order_status', '{\"order_id\":\"f5d055ec-9300-445f-af99-77077d8cc519\",\"order_number\":\"ORD-2026-000015\",\"status\":\"sent_to_vendor\"}', 1, '2026-04-21 18:42:22'),
('b01d8df6-3d98-11f1-9448-e66239f31cb0', '68d947ef-fcec-426a-8316-155666848619', '📦 New Order to Prepare', 'Order #ORD-2026-000015 — Please accept and start preparing.', 'new_order', '{\"order_id\":\"f5d055ec-9300-445f-af99-77077d8cc519\",\"order_number\":\"ORD-2026-000015\",\"status\":\"sent_to_vendor\"}', 0, '2026-04-21 18:42:22'),
('b029928a-3d98-11f1-9448-e66239f31cb0', '6255a20b-3d97-11f1-9448-e66239f31cb0', '📦 New Order to Prepare', 'Order #ORD-2026-000015 — Please accept and start preparing.', 'new_order', '{\"order_id\":\"f5d055ec-9300-445f-af99-77077d8cc519\",\"order_number\":\"ORD-2026-000015\",\"status\":\"sent_to_vendor\"}', 0, '2026-04-21 18:42:22'),
('b03a7eec-3d98-11f1-9448-e66239f31cb0', '8f266f22-1d9d-11f1-b82d-97044e7d8834', 'Order #ORD-2026-000015 Confirmed', 'Confirmed by admin and sent to vendor(s)', 'order_status', '{\"order_id\":\"f5d055ec-9300-445f-af99-77077d8cc519\",\"order_number\":\"ORD-2026-000015\",\"status\":\"sent_to_vendor\"}', 0, '2026-04-21 18:42:22'),
('bf6733eb-3d8f-11f1-9448-e66239f31cb0', '1d8557e5-8a7d-4c32-8181-ad785ea979a0', '✅ Order Confirmed!', 'Your order #ORD-2026-000014 has been confirmed and sent to the vendor.', 'order_status', '{\"order_id\":\"8f957033-af0e-4c09-a600-74344e7b1485\",\"order_number\":\"ORD-2026-000014\",\"status\":\"sent_to_vendor\"}', 1, '2026-04-21 17:38:22'),
('bf7fe36e-3d8f-11f1-9448-e66239f31cb0', '68d947ef-fcec-426a-8316-155666848619', '📦 New Order to Prepare', 'Order #ORD-2026-000014 — Please accept and start preparing.', 'new_order', '{\"order_id\":\"8f957033-af0e-4c09-a600-74344e7b1485\",\"order_number\":\"ORD-2026-000014\",\"status\":\"sent_to_vendor\"}', 0, '2026-04-21 17:38:22'),
('bf900898-3d8f-11f1-9448-e66239f31cb0', '8f266f22-1d9d-11f1-b82d-97044e7d8834', 'Order #ORD-2026-000014 Confirmed', 'Confirmed by admin and sent to vendor(s)', 'order_status', '{\"order_id\":\"8f957033-af0e-4c09-a600-74344e7b1485\",\"order_number\":\"ORD-2026-000014\",\"status\":\"sent_to_vendor\"}', 0, '2026-04-21 17:38:22'),
('c4bb4c44-3d98-11f1-9448-e66239f31cb0', '8ba11278-3b31-11f1-9448-e66239f31cb0', 'Vendor Accepted #ORD-2026-000015', 'North food accepted the order (1 vendor(s) still pending)', 'order_status', '{\"order_id\":\"f5d055ec-9300-445f-af99-77077d8cc519\",\"order_number\":\"ORD-2026-000015\",\"status\":\"vendor_accepted\"}', 0, '2026-04-21 18:42:57'),
('c4bb7017-3d98-11f1-9448-e66239f31cb0', 'd8e13ad8-2382-11f1-b82d-97044e7d8834', 'Vendor Accepted #ORD-2026-000015', 'North food accepted the order (1 vendor(s) still pending)', 'order_status', '{\"order_id\":\"f5d055ec-9300-445f-af99-77077d8cc519\",\"order_number\":\"ORD-2026-000015\",\"status\":\"vendor_accepted\"}', 0, '2026-04-21 18:42:57'),
('c53e8fa2-3d98-11f1-9448-e66239f31cb0', 'e64dd274-3d20-11f1-9448-e66239f31cb0', 'Vendor Accepted #ORD-2026-000015', 'North food accepted the order (1 vendor(s) still pending)', 'order_status', '{\"order_id\":\"f5d055ec-9300-445f-af99-77077d8cc519\",\"order_number\":\"ORD-2026-000015\",\"status\":\"vendor_accepted\"}', 0, '2026-04-21 18:42:57'),
('c54a6ec1-3d98-11f1-9448-e66239f31cb0', 'f52cc98d-3d20-11f1-9448-e66239f31cb0', 'Vendor Accepted #ORD-2026-000015', 'North food accepted the order (1 vendor(s) still pending)', 'order_status', '{\"order_id\":\"f5d055ec-9300-445f-af99-77077d8cc519\",\"order_number\":\"ORD-2026-000015\",\"status\":\"vendor_accepted\"}', 0, '2026-04-21 18:42:57'),
('c8d67af0-35cd-11f1-9448-e66239f31cb0', 'd8e13ad8-2382-11f1-b82d-97044e7d8834', 'Vendor Accepted #ORD-2026-000002', 'فتح الله ماركت accepted the order (1 vendor(s) still pending)', 'order_status', '{\"order_id\":\"a1c23979-4844-4910-b6c7-d96f1d5807af\",\"order_number\":\"ORD-2026-000002\",\"status\":\"vendor_accepted\"}', 0, '2026-04-11 17:42:17'),
('caaa77f9-35e0-11f1-9448-e66239f31cb0', '43d5d6a2-e09d-4581-8636-557cd150b0f1', '🎉 Order Delivered!', 'Your order #ORD-2026-000004 has been delivered. Enjoy and rate your experience!', 'order_status', '{\"order_id\":\"1e8023a6-a8d0-42d5-b4bb-4c5317631166\",\"order_number\":\"ORD-2026-000004\",\"status\":\"delivered\"}', 1, '2026-04-11 19:58:21'),
('cb5ac8f7-36cf-11f1-9448-e66239f31cb0', '0717139f-9fbc-4c8c-be98-cf1361e0036c', '🚚 Your Order is On the Way!', 'Order #ORD-2026-000008 is out for delivery.', 'order_status', '{\"order_id\":\"fefb970d-6c86-42fa-ac39-d0c6c17fe37c\",\"order_number\":\"ORD-2026-000008\",\"status\":\"out_for_delivery\"}', 0, '2026-04-13 03:29:12'),
('cb637b7f-35e0-11f1-9448-e66239f31cb0', '8f266f22-1d9d-11f1-b82d-97044e7d8834', 'Order Delivered #ORD-2026-000004', 'Order has been successfully delivered.', 'order_status', '{\"order_id\":\"1e8023a6-a8d0-42d5-b4bb-4c5317631166\",\"order_number\":\"ORD-2026-000004\",\"status\":\"delivered\"}', 0, '2026-04-11 19:58:22'),
('cb71ad66-3d98-11f1-9448-e66239f31cb0', '8ba11278-3b31-11f1-9448-e66239f31cb0', 'Vendor Ready #ORD-2026-000015', 'North food is ready (1 vendor(s) still preparing)', 'order_status', '{\"order_id\":\"f5d055ec-9300-445f-af99-77077d8cc519\",\"order_number\":\"ORD-2026-000015\",\"status\":\"ready_for_pickup\"}', 0, '2026-04-21 18:43:08'),
('cb71bd5d-3d98-11f1-9448-e66239f31cb0', 'd8e13ad8-2382-11f1-b82d-97044e7d8834', 'Vendor Ready #ORD-2026-000015', 'North food is ready (1 vendor(s) still preparing)', 'order_status', '{\"order_id\":\"f5d055ec-9300-445f-af99-77077d8cc519\",\"order_number\":\"ORD-2026-000015\",\"status\":\"ready_for_pickup\"}', 0, '2026-04-21 18:43:08'),
('cb729ae8-36cf-11f1-9448-e66239f31cb0', '4f792c75-4a65-45a7-a0ef-d282acd83d87', 'Order Dispatched #ORD-2026-000008', 'Order has been dispatched for delivery.', 'order_status', '{\"order_id\":\"fefb970d-6c86-42fa-ac39-d0c6c17fe37c\",\"order_number\":\"ORD-2026-000008\",\"status\":\"out_for_delivery\"}', 0, '2026-04-13 03:29:12'),
('cbf57e73-3d98-11f1-9448-e66239f31cb0', 'e64dd274-3d20-11f1-9448-e66239f31cb0', 'Vendor Ready #ORD-2026-000015', 'North food is ready (1 vendor(s) still preparing)', 'order_status', '{\"order_id\":\"f5d055ec-9300-445f-af99-77077d8cc519\",\"order_number\":\"ORD-2026-000015\",\"status\":\"ready_for_pickup\"}', 0, '2026-04-21 18:43:09'),
('cc046858-3d98-11f1-9448-e66239f31cb0', 'f52cc98d-3d20-11f1-9448-e66239f31cb0', 'Vendor Ready #ORD-2026-000015', 'North food is ready (1 vendor(s) still preparing)', 'order_status', '{\"order_id\":\"f5d055ec-9300-445f-af99-77077d8cc519\",\"order_number\":\"ORD-2026-000015\",\"status\":\"ready_for_pickup\"}', 0, '2026-04-21 18:43:09'),
('cdeef32b-36cf-11f1-9448-e66239f31cb0', '0717139f-9fbc-4c8c-be98-cf1361e0036c', '🎉 Order Delivered!', 'Your order #ORD-2026-000008 has been delivered. Enjoy and rate your experience!', 'order_status', '{\"order_id\":\"fefb970d-6c86-42fa-ac39-d0c6c17fe37c\",\"order_number\":\"ORD-2026-000008\",\"status\":\"delivered\"}', 0, '2026-04-13 03:29:16'),
('cdf7db5e-35de-11f1-9448-e66239f31cb0', '43d5d6a2-e09d-4581-8636-557cd150b0f1', '🚚 Your Order is On the Way!', 'Order #ORD-2026-000002 is out for delivery.', 'order_status', '{\"order_id\":\"a1c23979-4844-4910-b6c7-d96f1d5807af\",\"order_number\":\"ORD-2026-000002\",\"status\":\"out_for_delivery\"}', 1, '2026-04-11 19:44:07'),
('ce00291a-36cf-11f1-9448-e66239f31cb0', '8f266f22-1d9d-11f1-b82d-97044e7d8834', 'Order Delivered #ORD-2026-000008', 'Order has been successfully delivered.', 'order_status', '{\"order_id\":\"fefb970d-6c86-42fa-ac39-d0c6c17fe37c\",\"order_number\":\"ORD-2026-000008\",\"status\":\"delivered\"}', 0, '2026-04-13 03:29:16'),
('ce9e9f92-35de-11f1-9448-e66239f31cb0', '4f792c75-4a65-45a7-a0ef-d282acd83d87', 'Order Dispatched #ORD-2026-000002', 'Order has been dispatched for delivery.', 'order_status', '{\"order_id\":\"a1c23979-4844-4910-b6c7-d96f1d5807af\",\"order_number\":\"ORD-2026-000002\",\"status\":\"out_for_delivery\"}', 0, '2026-04-11 19:44:09'),
('cec2b7ff-35e0-11f1-9448-e66239f31cb0', '43d5d6a2-e09d-4581-8636-557cd150b0f1', '🎉 Order Delivered!', 'Your order #ORD-2026-000002 has been delivered. Enjoy and rate your experience!', 'order_status', '{\"order_id\":\"a1c23979-4844-4910-b6c7-d96f1d5807af\",\"order_number\":\"ORD-2026-000002\",\"status\":\"delivered\"}', 1, '2026-04-11 19:58:28'),
('cf146512-35de-11f1-9448-e66239f31cb0', 'cf2239d2-2dd7-4116-abd3-1f24d48e8fe7', 'Order Dispatched #ORD-2026-000002', 'Order has been dispatched for delivery.', 'order_status', '{\"order_id\":\"a1c23979-4844-4910-b6c7-d96f1d5807af\",\"order_number\":\"ORD-2026-000002\",\"status\":\"out_for_delivery\"}', 0, '2026-04-11 19:44:09'),
('cfb4de60-35e0-11f1-9448-e66239f31cb0', '8f266f22-1d9d-11f1-b82d-97044e7d8834', 'Order Delivered #ORD-2026-000002', 'Order has been successfully delivered.', 'order_status', '{\"order_id\":\"a1c23979-4844-4910-b6c7-d96f1d5807af\",\"order_number\":\"ORD-2026-000002\",\"status\":\"delivered\"}', 0, '2026-04-11 19:58:29'),
('d45838ee-3696-11f1-9448-e66239f31cb0', '5732093a-a1d5-46ba-9432-ac9f560ba03c', '✅ Order Confirmed!', 'Your order #ORD-2026-000005 has been confirmed and sent to the vendor.', 'order_status', '{\"order_id\":\"7175c222-70ed-49bf-929d-11fb434f107a\",\"order_number\":\"ORD-2026-000005\",\"status\":\"sent_to_vendor\"}', 0, '2026-04-12 20:41:26'),
('d4798a55-3696-11f1-9448-e66239f31cb0', '4f792c75-4a65-45a7-a0ef-d282acd83d87', '📦 New Order to Prepare', 'Order #ORD-2026-000005 — Please accept and start preparing.', 'new_order', '{\"order_id\":\"7175c222-70ed-49bf-929d-11fb434f107a\",\"order_number\":\"ORD-2026-000005\",\"status\":\"sent_to_vendor\"}', 0, '2026-04-12 20:41:26'),
('d4e42942-3696-11f1-9448-e66239f31cb0', '8f266f22-1d9d-11f1-b82d-97044e7d8834', 'Order #ORD-2026-000005 Confirmed', 'Confirmed by admin and sent to vendor(s)', 'order_status', '{\"order_id\":\"7175c222-70ed-49bf-929d-11fb434f107a\",\"order_number\":\"ORD-2026-000005\",\"status\":\"sent_to_vendor\"}', 0, '2026-04-12 20:41:26'),
('e5ca4d07-3696-11f1-9448-e66239f31cb0', '5732093a-a1d5-46ba-9432-ac9f560ba03c', '🍳 Order Accepted!', 'All vendors accepted your order #ORD-2026-000005', 'order_status', '{\"order_id\":\"7175c222-70ed-49bf-929d-11fb434f107a\",\"order_number\":\"ORD-2026-000005\",\"status\":\"vendor_accepted\"}', 0, '2026-04-12 20:41:55');
INSERT INTO `notifications` (`id`, `user_id`, `title`, `body`, `type`, `data`, `is_read`, `created_at`) VALUES
('e5e3d0ad-3696-11f1-9448-e66239f31cb0', 'd8e13ad8-2382-11f1-b82d-97044e7d8834', 'All Vendors Accepted #ORD-2026-000005', 'All vendors have accepted order #ORD-2026-000005', 'order_status', '{\"order_id\":\"7175c222-70ed-49bf-929d-11fb434f107a\",\"order_number\":\"ORD-2026-000005\",\"status\":\"vendor_accepted\"}', 0, '2026-04-12 20:41:55'),
('e8d95f6e-3696-11f1-9448-e66239f31cb0', '5732093a-a1d5-46ba-9432-ac9f560ba03c', '👨‍🍳 Order Being Prepared', 'Your order #ORD-2026-000005 is now being prepared!', 'order_status', '{\"order_id\":\"7175c222-70ed-49bf-929d-11fb434f107a\",\"order_number\":\"ORD-2026-000005\",\"status\":\"preparing\"}', 0, '2026-04-12 20:42:00'),
('ea4ee292-3696-11f1-9448-e66239f31cb0', '5732093a-a1d5-46ba-9432-ac9f560ba03c', '✅ Order Ready!', 'Your order #ORD-2026-000005 is ready for pickup!', 'order_status', '{\"order_id\":\"7175c222-70ed-49bf-929d-11fb434f107a\",\"order_number\":\"ORD-2026-000005\",\"status\":\"ready_for_pickup\"}', 0, '2026-04-12 20:42:02'),
('ea67e335-3696-11f1-9448-e66239f31cb0', 'd8e13ad8-2382-11f1-b82d-97044e7d8834', '🚚 Ready for Pickup #ORD-2026-000005', 'All vendors ready — dispatch delivery now!', 'order_status', '{\"order_id\":\"7175c222-70ed-49bf-929d-11fb434f107a\",\"order_number\":\"ORD-2026-000005\",\"status\":\"ready_for_pickup\"}', 0, '2026-04-12 20:42:03'),
('eaf4edcb-3d98-11f1-9448-e66239f31cb0', '8ba11278-3b31-11f1-9448-e66239f31cb0', 'Vendor Accepted #ORD-2026-000015', 'south accepted the order (1 vendor(s) still pending)', 'order_status', '{\"order_id\":\"f5d055ec-9300-445f-af99-77077d8cc519\",\"order_number\":\"ORD-2026-000015\",\"status\":\"vendor_accepted\"}', 0, '2026-04-21 18:44:01'),
('eaf503e9-3d98-11f1-9448-e66239f31cb0', 'd8e13ad8-2382-11f1-b82d-97044e7d8834', 'Vendor Accepted #ORD-2026-000015', 'south accepted the order (1 vendor(s) still pending)', 'order_status', '{\"order_id\":\"f5d055ec-9300-445f-af99-77077d8cc519\",\"order_number\":\"ORD-2026-000015\",\"status\":\"vendor_accepted\"}', 0, '2026-04-21 18:44:01'),
('eb7c75ee-3d98-11f1-9448-e66239f31cb0', 'e64dd274-3d20-11f1-9448-e66239f31cb0', 'Vendor Accepted #ORD-2026-000015', 'south accepted the order (1 vendor(s) still pending)', 'order_status', '{\"order_id\":\"f5d055ec-9300-445f-af99-77077d8cc519\",\"order_number\":\"ORD-2026-000015\",\"status\":\"vendor_accepted\"}', 0, '2026-04-21 18:44:02'),
('eb8a3397-3d98-11f1-9448-e66239f31cb0', 'f52cc98d-3d20-11f1-9448-e66239f31cb0', 'Vendor Accepted #ORD-2026-000015', 'south accepted the order (1 vendor(s) still pending)', 'order_status', '{\"order_id\":\"f5d055ec-9300-445f-af99-77077d8cc519\",\"order_number\":\"ORD-2026-000015\",\"status\":\"vendor_accepted\"}', 0, '2026-04-21 18:44:02'),
('ec6f76f4-35dd-11f1-9448-e66239f31cb0', 'd8e13ad8-2382-11f1-b82d-97044e7d8834', '🔔 New Order #ORD-2026-000004', 'New order placed — ₪1,377.00', 'new_order', '{\"order_id\":\"1e8023a6-a8d0-42d5-b4bb-4c5317631166\",\"order_number\":\"ORD-2026-000004\",\"total_amount\":\"1377\",\"status\":\"pending\"}', 0, '2026-04-11 19:37:49'),
('ecf8bebb-35dd-11f1-9448-e66239f31cb0', '8f266f22-1d9d-11f1-b82d-97044e7d8834', '🔔 New Order #ORD-2026-000004', 'New order placed — ₪1,377.00', 'new_order', '{\"order_id\":\"1e8023a6-a8d0-42d5-b4bb-4c5317631166\",\"order_number\":\"ORD-2026-000004\",\"total_amount\":\"1377\",\"status\":\"pending\"}', 0, '2026-04-11 19:37:50'),
('ed784151-35dd-11f1-9448-e66239f31cb0', '4f792c75-4a65-45a7-a0ef-d282acd83d87', '📦 New Order Received', 'Order #ORD-2026-000004 — ₪1,377.00', 'new_order', '{\"order_id\":\"1e8023a6-a8d0-42d5-b4bb-4c5317631166\",\"order_number\":\"ORD-2026-000004\",\"total_amount\":\"1377\",\"status\":\"pending\"}', 0, '2026-04-11 19:37:51'),
('ede1c570-35dd-11f1-9448-e66239f31cb0', 'cf2239d2-2dd7-4116-abd3-1f24d48e8fe7', '📦 New Order Received', 'Order #ORD-2026-000004 — ₪1,377.00', 'new_order', '{\"order_id\":\"1e8023a6-a8d0-42d5-b4bb-4c5317631166\",\"order_number\":\"ORD-2026-000004\",\"total_amount\":\"1377\",\"status\":\"pending\"}', 0, '2026-04-11 19:37:52'),
('eeb1ef82-3d8f-11f1-9448-e66239f31cb0', '1d8557e5-8a7d-4c32-8181-ad785ea979a0', '🍳 Order Accepted!', 'All vendors accepted your order #ORD-2026-000014', 'order_status', '{\"order_id\":\"8f957033-af0e-4c09-a600-74344e7b1485\",\"order_number\":\"ORD-2026-000014\",\"status\":\"vendor_accepted\"}', 1, '2026-04-21 17:39:41'),
('eecc582f-3d8f-11f1-9448-e66239f31cb0', '8ba11278-3b31-11f1-9448-e66239f31cb0', 'All Vendors Accepted #ORD-2026-000014', 'All vendors have accepted order #ORD-2026-000014', 'order_status', '{\"order_id\":\"8f957033-af0e-4c09-a600-74344e7b1485\",\"order_number\":\"ORD-2026-000014\",\"status\":\"vendor_accepted\"}', 0, '2026-04-21 17:39:42'),
('eecc7158-3d8f-11f1-9448-e66239f31cb0', 'd8e13ad8-2382-11f1-b82d-97044e7d8834', 'All Vendors Accepted #ORD-2026-000014', 'All vendors have accepted order #ORD-2026-000014', 'order_status', '{\"order_id\":\"8f957033-af0e-4c09-a600-74344e7b1485\",\"order_number\":\"ORD-2026-000014\",\"status\":\"vendor_accepted\"}', 0, '2026-04-21 17:39:42'),
('ef4cdfa5-3d8f-11f1-9448-e66239f31cb0', 'e64dd274-3d20-11f1-9448-e66239f31cb0', 'All Vendors Accepted #ORD-2026-000014', 'All vendors have accepted order #ORD-2026-000014', 'order_status', '{\"order_id\":\"8f957033-af0e-4c09-a600-74344e7b1485\",\"order_number\":\"ORD-2026-000014\",\"status\":\"vendor_accepted\"}', 0, '2026-04-21 17:39:42'),
('ef598b10-3d8f-11f1-9448-e66239f31cb0', 'f52cc98d-3d20-11f1-9448-e66239f31cb0', 'All Vendors Accepted #ORD-2026-000014', 'All vendors have accepted order #ORD-2026-000014', 'order_status', '{\"order_id\":\"8f957033-af0e-4c09-a600-74344e7b1485\",\"order_number\":\"ORD-2026-000014\",\"status\":\"vendor_accepted\"}', 0, '2026-04-21 17:39:43'),
('ef6624a2-3d98-11f1-9448-e66239f31cb0', '1d8557e5-8a7d-4c32-8181-ad785ea979a0', '✅ Order Ready!', 'Your order #ORD-2026-000015 is ready for pickup!', 'order_status', '{\"order_id\":\"f5d055ec-9300-445f-af99-77077d8cc519\",\"order_number\":\"ORD-2026-000015\",\"status\":\"ready_for_pickup\"}', 1, '2026-04-21 18:44:08'),
('ef7fc916-3d98-11f1-9448-e66239f31cb0', '8ba11278-3b31-11f1-9448-e66239f31cb0', '🚚 Ready for Pickup #ORD-2026-000015', 'All vendors ready — dispatch delivery now!', 'order_status', '{\"order_id\":\"f5d055ec-9300-445f-af99-77077d8cc519\",\"order_number\":\"ORD-2026-000015\",\"status\":\"ready_for_pickup\"}', 0, '2026-04-21 18:44:08'),
('ef809751-3d98-11f1-9448-e66239f31cb0', 'd8e13ad8-2382-11f1-b82d-97044e7d8834', '🚚 Ready for Pickup #ORD-2026-000015', 'All vendors ready — dispatch delivery now!', 'order_status', '{\"order_id\":\"f5d055ec-9300-445f-af99-77077d8cc519\",\"order_number\":\"ORD-2026-000015\",\"status\":\"ready_for_pickup\"}', 0, '2026-04-21 18:44:08'),
('efffe7de-3d98-11f1-9448-e66239f31cb0', 'e64dd274-3d20-11f1-9448-e66239f31cb0', '🚚 Ready for Pickup #ORD-2026-000015', 'All vendors ready — dispatch delivery now!', 'order_status', '{\"order_id\":\"f5d055ec-9300-445f-af99-77077d8cc519\",\"order_number\":\"ORD-2026-000015\",\"status\":\"ready_for_pickup\"}', 0, '2026-04-21 18:44:09'),
('f00cfce9-3d98-11f1-9448-e66239f31cb0', 'f52cc98d-3d20-11f1-9448-e66239f31cb0', '🚚 Ready for Pickup #ORD-2026-000015', 'All vendors ready — dispatch delivery now!', 'order_status', '{\"order_id\":\"f5d055ec-9300-445f-af99-77077d8cc519\",\"order_number\":\"ORD-2026-000015\",\"status\":\"ready_for_pickup\"}', 0, '2026-04-21 18:44:09'),
('f21d8633-35cd-11f1-9448-e66239f31cb0', '43d5d6a2-e09d-4581-8636-557cd150b0f1', '🍳 Order Accepted!', 'All vendors accepted your order #ORD-2026-000002', 'order_status', '{\"order_id\":\"a1c23979-4844-4910-b6c7-d96f1d5807af\",\"order_number\":\"ORD-2026-000002\",\"status\":\"vendor_accepted\"}', 1, '2026-04-11 17:43:27'),
('f2e0fadf-35cd-11f1-9448-e66239f31cb0', 'd8e13ad8-2382-11f1-b82d-97044e7d8834', 'All Vendors Accepted #ORD-2026-000002', 'All vendors have accepted order #ORD-2026-000002', 'order_status', '{\"order_id\":\"a1c23979-4844-4910-b6c7-d96f1d5807af\",\"order_number\":\"ORD-2026-000002\",\"status\":\"vendor_accepted\"}', 0, '2026-04-11 17:43:28'),
('fdcec35e-3697-11f1-9448-e66239f31cb0', 'd8e13ad8-2382-11f1-b82d-97044e7d8834', '🔔 New Order #ORD-2026-000006', 'New order placed — ₪1,377.00', 'new_order', '{\"order_id\":\"fb6fb2f8-1d1d-428c-98f9-0e3cfffe49a2\",\"order_number\":\"ORD-2026-000006\",\"total_amount\":\"1377\",\"status\":\"pending\"}', 0, '2026-04-12 20:49:45'),
('fe4e26d6-3697-11f1-9448-e66239f31cb0', '8f266f22-1d9d-11f1-b82d-97044e7d8834', '🔔 New Order #ORD-2026-000006', 'New order placed — ₪1,377.00', 'new_order', '{\"order_id\":\"fb6fb2f8-1d1d-428c-98f9-0e3cfffe49a2\",\"order_number\":\"ORD-2026-000006\",\"total_amount\":\"1377\",\"status\":\"pending\"}', 0, '2026-04-12 20:49:45'),
('fed20be2-3697-11f1-9448-e66239f31cb0', '4f792c75-4a65-45a7-a0ef-d282acd83d87', '📦 New Order Received', 'Order #ORD-2026-000006 — ₪1,377.00', 'new_order', '{\"order_id\":\"fb6fb2f8-1d1d-428c-98f9-0e3cfffe49a2\",\"order_number\":\"ORD-2026-000006\",\"total_amount\":\"1377\",\"status\":\"pending\"}', 0, '2026-04-12 20:49:46'),
('ff4a8b5d-3697-11f1-9448-e66239f31cb0', 'cf2239d2-2dd7-4116-abd3-1f24d48e8fe7', '📦 New Order Received', 'Order #ORD-2026-000006 — ₪1,377.00', 'new_order', '{\"order_id\":\"fb6fb2f8-1d1d-428c-98f9-0e3cfffe49a2\",\"order_number\":\"ORD-2026-000006\",\"total_amount\":\"1377\",\"status\":\"pending\"}', 0, '2026-04-12 20:49:47');

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `id` char(36) NOT NULL DEFAULT uuid(),
  `order_number` varchar(20) NOT NULL,
  `customer_id` char(36) NOT NULL,
  `admin_id` char(36) DEFAULT NULL,
  `branch_region` enum('north','south') DEFAULT NULL,
  `delivery_address` text NOT NULL,
  `delivery_latitude` decimal(10,8) DEFAULT NULL,
  `delivery_longitude` decimal(11,8) DEFAULT NULL,
  `delivery_phone` varchar(20) DEFAULT NULL,
  `delivery_city` varchar(100) DEFAULT NULL,
  `delivery_neighborhood` varchar(100) DEFAULT NULL,
  `delivery_zone_from` varchar(20) DEFAULT NULL,
  `delivery_zone_to` varchar(20) DEFAULT NULL,
  `subtotal` decimal(10,2) NOT NULL,
  `delivery_fee` decimal(8,2) NOT NULL DEFAULT 0.00,
  `multi_vendor_surcharge` decimal(8,2) NOT NULL DEFAULT 0.00,
  `total_amount` decimal(10,2) NOT NULL,
  `platform_fee` decimal(8,2) NOT NULL DEFAULT 0.00,
  `fee_model_used` enum('flat_per_order','percentage') NOT NULL,
  `payment_method` enum('cash_on_delivery','bank_transfer') NOT NULL,
  `payment_status` enum('pending','collected','failed') NOT NULL DEFAULT 'pending',
  `status` enum('pending','admin_confirmed','sent_to_vendor','vendor_accepted','preparing','ready_for_pickup','out_for_delivery','delivered','cancelled') NOT NULL DEFAULT 'pending',
  `customer_notes` text DEFAULT NULL,
  `cancellation_reason` text DEFAULT NULL,
  `confirmed_at` datetime DEFAULT NULL,
  `delivered_at` datetime DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `service_fee` decimal(8,2) NOT NULL DEFAULT 2.00
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`id`, `order_number`, `customer_id`, `admin_id`, `branch_region`, `delivery_address`, `delivery_latitude`, `delivery_longitude`, `delivery_phone`, `delivery_city`, `delivery_neighborhood`, `delivery_zone_from`, `delivery_zone_to`, `subtotal`, `delivery_fee`, `multi_vendor_surcharge`, `total_amount`, `platform_fee`, `fee_model_used`, `payment_method`, `payment_status`, `status`, `customer_notes`, `cancellation_reason`, `confirmed_at`, `delivered_at`, `created_at`, `updated_at`, `service_fee`) VALUES
('1e8023a6-a8d0-42d5-b4bb-4c5317631166', 'ORD-2026-000004', '43d5d6a2-e09d-4581-8636-557cd150b0f1', NULL, NULL, 'غزة، شمال غزة', NULL, NULL, '01003640091', NULL, NULL, NULL, NULL, 1350.00, 25.00, 0.00, 1377.00, 3.00, 'flat_per_order', 'cash_on_delivery', 'collected', 'delivered', NULL, NULL, NULL, '2026-04-11 19:58:21', '2026-04-11 19:37:49', '2026-04-11 19:58:21', 2.00),
('1fa6fc17-b531-490c-a327-775013af98db', 'ORD-2026-000001', '43d5d6a2-e09d-4581-8636-557cd150b0f1', NULL, NULL, 'غزة، شمال غزة', NULL, NULL, '01003640091', NULL, NULL, NULL, NULL, 380.00, 20.00, 0.00, 402.00, 3.00, 'flat_per_order', 'cash_on_delivery', 'pending', 'vendor_accepted', NULL, NULL, NULL, NULL, '2026-04-11 17:38:58', '2026-04-18 17:11:43', 2.00),
('7175c222-70ed-49bf-929d-11fb434f107a', 'ORD-2026-000005', '5732093a-a1d5-46ba-9432-ac9f560ba03c', NULL, NULL, 'شارع النفق بجوار ستي ماركت', NULL, NULL, '0599261116', NULL, NULL, NULL, NULL, 680.00, 20.00, 0.00, 702.00, 3.00, 'flat_per_order', 'cash_on_delivery', 'collected', 'delivered', NULL, NULL, NULL, '2026-04-12 20:42:50', '2026-04-12 20:40:00', '2026-04-12 20:42:50', 2.00),
('7a40fa48-aadc-4a85-80fc-57599d58a973', 'ORD-2026-000003', '43d5d6a2-e09d-4581-8636-557cd150b0f1', NULL, NULL, 'غزة، شمال غزة', NULL, NULL, '01003640091', NULL, NULL, NULL, NULL, 1350.00, 25.00, 0.00, 1377.00, 3.00, 'flat_per_order', 'cash_on_delivery', 'pending', 'pending', NULL, NULL, NULL, NULL, '2026-04-11 17:51:13', '2026-04-11 17:51:13', 2.00),
('8f957033-af0e-4c09-a600-74344e7b1485', 'ORD-2026-000014', '1d8557e5-8a7d-4c32-8181-ad785ea979a0', NULL, NULL, 'شمال', NULL, NULL, '01003690090', 'مدينة غزة', 'حي الرمال', 'north', 'north', 100.00, 10.00, 0.00, 112.00, 3.00, 'flat_per_order', 'cash_on_delivery', 'collected', 'delivered', NULL, NULL, NULL, '2026-04-21 17:41:07', '2026-04-21 17:35:31', '2026-04-21 17:41:07', 2.00),
('937c9853-526f-41e1-9be0-06a69ff2b437', 'ORD-2026-000007', '43d5d6a2-e09d-4581-8636-557cd150b0f1', NULL, NULL, 'غزة، شمال غزة', NULL, NULL, '01003640091', NULL, NULL, NULL, NULL, 500.00, 20.00, 0.00, 522.00, 3.00, 'flat_per_order', 'cash_on_delivery', 'pending', 'sent_to_vendor', 'اوردر', NULL, NULL, NULL, '2026-04-12 20:51:06', '2026-04-18 17:08:10', 2.00),
('a1c23979-4844-4910-b6c7-d96f1d5807af', 'ORD-2026-000002', '43d5d6a2-e09d-4581-8636-557cd150b0f1', NULL, NULL, 'غزة، شمال غزة', NULL, NULL, '01003640091', NULL, NULL, NULL, NULL, 1350.00, 25.00, 0.00, 1377.00, 3.00, 'flat_per_order', 'cash_on_delivery', 'collected', 'delivered', NULL, NULL, NULL, '2026-04-11 19:58:28', '2026-04-11 17:39:24', '2026-04-11 19:58:28', 2.00),
('ade4e928-3973-4f5a-8b9b-42b7608ddb22', 'ORD-2026-000009', '0717139f-9fbc-4c8c-be98-cf1361e0036c', NULL, NULL, 'هنا', NULL, NULL, '01551130045', NULL, NULL, NULL, NULL, 1800.00, 20.00, 0.00, 1822.00, 3.00, 'flat_per_order', 'cash_on_delivery', 'pending', 'cancelled', 'ممكن قبل ما توصل تفتحها وتشوف كم نق البيتزا وتبعتلي \nعشان اذا هلغيه بدل ما تتعب (-؛', NULL, NULL, NULL, '2026-04-13 18:19:52', '2026-04-13 18:21:11', 2.00),
('d29a86e5-2e14-4e19-8884-0f2db2b8d8d5', 'ORD-2026-000013', '1d8557e5-8a7d-4c32-8181-ad785ea979a0', NULL, NULL, 'شمال', NULL, NULL, '01003690090', 'مدينة غزة', 'حي الرمال', 'north', 'north', 100.00, 10.00, 0.00, 112.00, 3.00, 'flat_per_order', 'cash_on_delivery', 'pending', 'pending', NULL, NULL, NULL, NULL, '2026-04-21 04:54:16', '2026-04-21 04:54:16', 2.00),
('d697bc38-0e26-476d-9790-85e0972c75c1', 'ORD-2026-000010', '568753fd-bfa7-4920-a12a-36bd71277519', NULL, NULL, 'شمال غزة', NULL, NULL, '01003640081', NULL, NULL, NULL, NULL, 500.00, 20.00, 0.00, 522.00, 3.00, 'flat_per_order', 'cash_on_delivery', 'pending', 'pending', NULL, NULL, NULL, NULL, '2026-04-20 03:25:00', '2026-04-20 03:25:00', 2.00),
('e39e531f-02d7-402b-bfa1-4bc7911fdca0', 'ORD-2026-000011', '1d8557e5-8a7d-4c32-8181-ad785ea979a0', NULL, NULL, 'الشروق', NULL, NULL, '01003640091', 'مدينة دير البلح', 'منطقة حكر الجامع', 'north', 'central', 1200.00, 20.00, 0.00, 1222.00, 3.00, 'flat_per_order', 'cash_on_delivery', 'pending', 'vendor_accepted', NULL, NULL, NULL, NULL, '2026-04-21 03:07:03', '2026-04-21 03:07:53', 2.00),
('f5d055ec-9300-445f-af99-77077d8cc519', 'ORD-2026-000015', '1d8557e5-8a7d-4c32-8181-ad785ea979a0', NULL, NULL, 'شمال', NULL, NULL, '01003690090', 'مدينة غزة', 'حي الرمال', 'south', 'north', 1100.00, 25.00, 5.00, 1132.00, 3.00, 'flat_per_order', 'cash_on_delivery', 'collected', 'delivered', NULL, NULL, NULL, '2026-04-21 18:44:44', '2026-04-21 18:40:10', '2026-04-21 18:44:44', 2.00),
('f6203869-9831-43d0-a8c7-b814f55b5de8', 'ORD-2026-000012', '1d8557e5-8a7d-4c32-8181-ad785ea979a0', NULL, NULL, 'الشروق', NULL, NULL, '01003640091', 'مدينة دير البلح', 'منطقة حكر الجامع', 'north', 'central', 100.00, 20.00, 0.00, 122.00, 3.00, 'flat_per_order', 'cash_on_delivery', 'pending', 'ready_for_pickup', NULL, NULL, NULL, NULL, '2026-04-21 04:33:30', '2026-04-21 04:35:05', 2.00),
('fb6fb2f8-1d1d-428c-98f9-0e3cfffe49a2', 'ORD-2026-000006', '43d5d6a2-e09d-4581-8636-557cd150b0f1', NULL, NULL, 'غزة، شمال غزة', NULL, NULL, '01003640091', NULL, NULL, NULL, NULL, 1350.00, 25.00, 0.00, 1377.00, 3.00, 'flat_per_order', 'cash_on_delivery', 'pending', 'pending', NULL, NULL, NULL, NULL, '2026-04-12 20:49:45', '2026-04-12 20:49:45', 2.00),
('fefb970d-6c86-42fa-ac39-d0c6c17fe37c', 'ORD-2026-000008', '0717139f-9fbc-4c8c-be98-cf1361e0036c', NULL, NULL, 'هنا', NULL, NULL, '01551130045', NULL, NULL, NULL, NULL, 2060.00, 20.00, 0.00, 2082.00, 3.00, 'flat_per_order', 'cash_on_delivery', 'collected', 'delivered', 'ابقا اتصل ويوصل ثخن او بلاها', NULL, NULL, '2026-04-13 03:29:16', '2026-04-13 03:26:56', '2026-04-13 03:29:16', 2.00);

--
-- Triggers `orders`
--
DELIMITER $$
CREATE TRIGGER `trg_create_earning_on_delivery` AFTER UPDATE ON `orders` FOR EACH ROW BEGIN
    IF NEW.status = 'delivered' AND OLD.status != 'delivered' THEN
        INSERT INTO platform_earnings (
            id,
            order_id,
            order_number,
            vendor_id,
            order_total,
            vendor_subtotal,
            platform_fee,
            service_fee_earned,
            commission_earned,
            commission_rate_snapshot,
            delivery_fee_earned,
            vendor_payout,
            fee_model,
            fee_value_snapshot,
            is_collected,
            collected_at,
            earned_at
        )
        SELECT
            UUID(),
            NEW.id,
            NEW.order_number,
            ov.vendor_id,
            NEW.total_amount,
            ov.subtotal,
            CASE
                WHEN ov.id = (
                    SELECT MIN(ov2.id) FROM order_vendors ov2 WHERE ov2.order_id = NEW.id
                )
                THEN NEW.service_fee + ROUND(ov.subtotal * v.commission_rate / 100, 2)
                ELSE ROUND(ov.subtotal * v.commission_rate / 100, 2)
            END,
            CASE
                WHEN ov.id = (
                    SELECT MIN(ov2.id) FROM order_vendors ov2 WHERE ov2.order_id = NEW.id
                )
                THEN NEW.service_fee
                ELSE 0
            END,
            ROUND(ov.subtotal * v.commission_rate / 100, 2),
            v.commission_rate,
            ov.vendor_delivery_fee,
            ROUND(ov.subtotal - (ov.subtotal * v.commission_rate / 100), 2),
            NEW.fee_model_used,
            v.commission_rate,
            0,
            NULL,
            NOW()
        FROM order_vendors ov
        JOIN vendors v ON ov.vendor_id = v.id
        WHERE ov.order_id = NEW.id
          AND NOT EXISTS (
              SELECT 1 FROM platform_earnings pe 
              WHERE pe.order_id = NEW.id AND pe.vendor_id = ov.vendor_id
          );
    END IF;
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `trg_generate_order_number` BEFORE INSERT ON `orders` FOR EACH ROW BEGIN
    DECLARE next_num INT;
    
    -- Count ALL orders in this year (not just today) to avoid duplicate order_number
    SELECT COUNT(*) + 1
    INTO next_num
    FROM orders
    WHERE YEAR(created_at) = YEAR(NOW());
    
    -- Format: ORD-YYYY-NNNNNN (zero-padded to 6 digits)
    SET NEW.order_number = CONCAT(
        'ORD-',
        YEAR(NOW()),
        '-',
        LPAD(next_num, 6, '0')
    );
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `trg_log_order_status_change` AFTER UPDATE ON `orders` FOR EACH ROW BEGIN
    IF NEW.status != OLD.status THEN
        INSERT INTO order_status_history (
            id,
            order_id,
            from_status,
            to_status,
            changed_by,
            note,
            created_at
        ) VALUES (
            UUID(),
            NEW.id,
            OLD.status,
            NEW.status,
            NEW.admin_id,   -- last admin who touched the order
            NULL,
            NOW()
        );
    END IF;
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `order_items`
--

CREATE TABLE `order_items` (
  `id` char(36) NOT NULL DEFAULT uuid(),
  `order_vendor_id` char(36) NOT NULL,
  `menu_item_id` char(36) NOT NULL,
  `item_name` varchar(150) NOT NULL COMMENT 'Snapshot of item name at order time',
  `unit_price` decimal(10,2) NOT NULL COMMENT 'Snapshot of price at order time',
  `quantity` int(10) UNSIGNED NOT NULL,
  `line_total` decimal(10,2) NOT NULL COMMENT 'unit_price * quantity',
  `special_instructions` text DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `order_items`
--

INSERT INTO `order_items` (`id`, `order_vendor_id`, `menu_item_id`, `item_name`, `unit_price`, `quantity`, `line_total`, `special_instructions`, `created_at`) VALUES
('0483528c-9805-45fc-b7de-44b735076c4c', '1e6300e4-f85d-4474-8867-d2d6fb9a387c', 'd9d3d650-3d21-11f1-9448-e66239f31cb0', 'grild chicken', 100.00, 1, 100.00, NULL, '2026-04-21 17:35:31'),
('1a77d903-e1ec-4de6-9576-d49bf8b8026f', '47453639-d63e-4053-bc41-a4cc1abf0c90', 'f2010001-0001-4000-b000-000000000001', 'Classic Cheese Burger', 500.00, 1, 500.00, NULL, '2026-04-11 17:39:24'),
('2ae9a3a5-a72a-42f9-a75b-932005ea309d', '1c062906-ef02-41dd-81d3-22031361ad2e', 'f2060001-0001-4000-b000-000000000003', 'Sports Running Shoes', 890.00, 2, 1780.00, NULL, '2026-04-13 03:26:56'),
('3b877fe0-65df-488b-a281-a61bca5085d6', '7a05da77-a1c5-420e-8c7b-02a399cca2cf', 'f1010001-0001-4000-a000-000000000001', 'Grilled Chicken Platter', 850.00, 1, 850.00, NULL, '2026-04-11 19:37:49'),
('3ef9e38b-ccfc-4d60-beb4-621d82cbd886', '6e538937-0bf4-4a6a-9925-266c6026fc1b', 'd9d3d650-3d21-11f1-9448-e66239f31cb0', 'grild chicken', 100.00, 1, 100.00, NULL, '2026-04-21 04:54:16'),
('4f2d50ec-c73d-47c4-8895-3bf0bacb8413', 'f88997dc-314c-4ab1-8f82-40957eecbecf', 'd9d3d650-3d21-11f1-9448-e66239f31cb0', 'grild chicken', 100.00, 1, 100.00, NULL, '2026-04-21 18:40:10'),
('50ad63b0-89bd-421f-a20a-f67ce5efee53', '1b9d7318-b413-4265-8753-c7b66a2f4906', 'f2010001-0001-4000-b000-000000000003', 'Falafel Wrap', 300.00, 1, 300.00, NULL, '2026-04-12 20:40:00'),
('564bf4b9-9755-4a03-a923-6ed1ac49a3a2', 'aeac8ae0-df4c-4451-bd80-abc807b2f862', 'f2050001-0001-4000-b000-000000000001', 'Wireless Bluetooth Earbuds', 1200.00, 1, 1200.00, NULL, '2026-04-21 03:07:03'),
('5b8b6b26-3808-49c6-a40c-f4cd73e5d8e6', '1c062906-ef02-41dd-81d3-22031361ad2e', 'f2070001-0001-4000-b000-000000000003', 'Multivitamin Complex (60 Caps)', 280.00, 1, 280.00, NULL, '2026-04-13 03:26:56'),
('7896dc3d-5378-4d93-8d80-00e650666a4d', '131b2413-8ff6-4583-b6fd-5527b0834b6b', 'f2010001-0001-4000-b000-000000000001', 'Classic Cheese Burger', 500.00, 1, 500.00, NULL, '2026-04-12 20:49:45'),
('823215f7-9991-43c7-b6a1-7d5be8367c97', '37cc36bc-8c56-4823-951d-64a1d1f5482e', 'f1010001-0001-4000-a000-000000000003', 'Margherita Pizza', 600.00, 3, 1800.00, NULL, '2026-04-13 18:19:52'),
('8a95035d-3e95-460d-a817-870fc5c339ea', '614bcd4d-c31a-4f56-b8da-396f597e32f9', 'f1010001-0001-4000-a000-000000000001', 'Grilled Chicken Platter', 850.00, 1, 850.00, NULL, '2026-04-11 17:51:13'),
('8d229fe6-157f-4f40-a7ba-cf71e25d7cf6', '8a6ab146-de5d-4e35-af67-f25cfad6e736', 'f2010001-0001-4000-b000-000000000001', 'Classic Cheese Burger', 500.00, 1, 500.00, NULL, '2026-04-12 20:51:06'),
('9e334e3c-7bab-4f0a-b988-130bdba3ad48', 'a7aee875-b17d-48c6-81d0-0766c09e118a', 'f2010001-0001-4000-b000-000000000002', 'Chicken Caesar Salad', 380.00, 1, 380.00, NULL, '2026-04-11 17:38:58'),
('b289a63f-d64c-460d-a335-79f725f4e884', '1b9d7318-b413-4265-8753-c7b66a2f4906', 'f2010001-0001-4000-b000-000000000002', 'Chicken Caesar Salad', 380.00, 1, 380.00, NULL, '2026-04-12 20:40:00'),
('b548ec8d-8dc6-4ff2-8b71-bb7d62907d0d', '50970209-97d1-41a1-beb3-134688ad7b28', 'f2010001-0001-4000-b000-000000000001', 'Classic Cheese Burger', 500.00, 1, 500.00, NULL, '2026-04-20 03:25:01'),
('b570404a-5763-4ce0-ba9d-ff43f11d8e60', '1432b89a-9da1-4331-88b7-026fd0014f1f', 'f1010001-0001-4000-a000-000000000001', 'Grilled Chicken Platter', 850.00, 1, 850.00, NULL, '2026-04-11 17:39:24'),
('c6450bb1-4b24-44b3-849d-6dac675e78a7', 'f2cb77a3-76f6-49db-b56d-9e4454016623', 'f1010001-0001-4000-a000-000000000001', 'Grilled Chicken Platter', 850.00, 1, 850.00, NULL, '2026-04-12 20:49:45'),
('cb271be8-5dcf-4967-a519-b5e3604d647e', '79bd29a4-d0dd-4e76-b823-b60c1f70c80e', 'c809eec0-3d97-11f1-9448-e66239f31cb0', 'meat', 1000.00, 1, 1000.00, NULL, '2026-04-21 18:40:10'),
('e2eb2ba1-8b57-4b4f-96b7-3d0f31185467', '8c2ddff8-d2e9-4aa5-ad4a-171fafece7ce', 'd9d3d650-3d21-11f1-9448-e66239f31cb0', 'grild chicken', 100.00, 1, 100.00, NULL, '2026-04-21 04:33:30'),
('f3ff8e2a-a8c4-4e77-8b78-6f0a4dd9b297', '9390384c-5464-4350-af12-a1911efe695f', 'f2010001-0001-4000-b000-000000000001', 'Classic Cheese Burger', 500.00, 1, 500.00, NULL, '2026-04-11 17:51:13'),
('fa088bf7-f01c-4b3c-9bfd-c83cba8d0840', '33426336-2b1a-498b-8ecc-a751fab745d4', 'f2010001-0001-4000-b000-000000000001', 'Classic Cheese Burger', 500.00, 1, 500.00, NULL, '2026-04-11 19:37:49');

-- --------------------------------------------------------

--
-- Table structure for table `order_status_history`
--

CREATE TABLE `order_status_history` (
  `id` char(36) NOT NULL DEFAULT uuid(),
  `order_id` char(36) NOT NULL,
  `from_status` varchar(30) DEFAULT NULL,
  `to_status` varchar(30) NOT NULL,
  `changed_by` char(36) DEFAULT NULL COMMENT 'NULL = system triggered',
  `note` text DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `order_status_history`
--

INSERT INTO `order_status_history` (`id`, `order_id`, `from_status`, `to_status`, `changed_by`, `note`, `created_at`) VALUES
('017f1238-3d99-11f1-9448-e66239f31cb0', 'f5d055ec-9300-445f-af99-77077d8cc519', 'ready_for_pickup', 'out_for_delivery', NULL, NULL, '2026-04-21 18:44:38'),
('04191fb7-3d90-11f1-9448-e66239f31cb0', '8f957033-af0e-4c09-a600-74344e7b1485', 'vendor_accepted', 'preparing', NULL, NULL, '2026-04-21 17:40:17'),
('04b5943c-3697-11f1-9448-e66239f31cb0', '7175c222-70ed-49bf-929d-11fb434f107a', 'ready_for_pickup', 'out_for_delivery', NULL, NULL, '2026-04-12 20:42:47'),
('0514a672-3d99-11f1-9448-e66239f31cb0', 'f5d055ec-9300-445f-af99-77077d8cc519', 'out_for_delivery', 'delivered', NULL, NULL, '2026-04-21 18:44:44'),
('05cf4cfa-3d90-11f1-9448-e66239f31cb0', '8f957033-af0e-4c09-a600-74344e7b1485', 'preparing', 'ready_for_pickup', NULL, NULL, '2026-04-21 17:40:20'),
('06c6cf4c-3697-11f1-9448-e66239f31cb0', '7175c222-70ed-49bf-929d-11fb434f107a', 'out_for_delivery', 'delivered', NULL, NULL, '2026-04-12 20:42:50'),
('07168167-35de-11f1-9448-e66239f31cb0', '1e8023a6-a8d0-42d5-b4bb-4c5317631166', 'pending', 'admin_confirmed', NULL, NULL, '2026-04-11 19:38:34'),
('0716db05-35de-11f1-9448-e66239f31cb0', '1e8023a6-a8d0-42d5-b4bb-4c5317631166', 'admin_confirmed', 'sent_to_vendor', NULL, NULL, '2026-04-11 19:38:34'),
('07d7a240-3b30-11f1-9448-e66239f31cb0', '937c9853-526f-41e1-9be0-06a69ff2b437', 'pending', 'admin_confirmed', NULL, NULL, '2026-04-18 17:08:10'),
('07d7daed-3b30-11f1-9448-e66239f31cb0', '937c9853-526f-41e1-9be0-06a69ff2b437', 'admin_confirmed', 'sent_to_vendor', NULL, NULL, '2026-04-18 17:08:10'),
('0805114d-35ce-11f1-9448-e66239f31cb0', 'a1c23979-4844-4910-b6c7-d96f1d5807af', 'vendor_accepted', 'preparing', NULL, NULL, '2026-04-11 17:44:03'),
('1eb974bf-3d90-11f1-9448-e66239f31cb0', '8f957033-af0e-4c09-a600-74344e7b1485', 'ready_for_pickup', 'out_for_delivery', NULL, NULL, '2026-04-21 17:41:02'),
('21b456dd-3d90-11f1-9448-e66239f31cb0', '8f957033-af0e-4c09-a600-74344e7b1485', 'out_for_delivery', 'delivered', NULL, NULL, '2026-04-21 17:41:07'),
('2494bd1a-3d16-11f1-9448-e66239f31cb0', 'e39e531f-02d7-402b-bfa1-4bc7911fdca0', 'pending', 'vendor_accepted', NULL, NULL, '2026-04-21 03:07:53'),
('261f42a7-35ce-11f1-9448-e66239f31cb0', 'a1c23979-4844-4910-b6c7-d96f1d5807af', 'preparing', 'ready_for_pickup', NULL, NULL, '2026-04-11 17:44:54'),
('4560be42-3d22-11f1-9448-e66239f31cb0', 'f6203869-9831-43d0-a8c7-b814f55b5de8', 'pending', 'vendor_accepted', NULL, NULL, '2026-04-21 04:34:42'),
('50ac2190-3d22-11f1-9448-e66239f31cb0', 'f6203869-9831-43d0-a8c7-b814f55b5de8', 'vendor_accepted', 'preparing', NULL, NULL, '2026-04-21 04:35:01'),
('52e2e925-3d22-11f1-9448-e66239f31cb0', 'f6203869-9831-43d0-a8c7-b814f55b5de8', 'preparing', 'ready_for_pickup', NULL, NULL, '2026-04-21 04:35:05'),
('6760906c-374c-11f1-9448-e66239f31cb0', 'ade4e928-3973-4f5a-8b9b-42b7608ddb22', 'pending', 'cancelled', NULL, NULL, '2026-04-13 18:21:11'),
('6e59d57c-35de-11f1-9448-e66239f31cb0', '1e8023a6-a8d0-42d5-b4bb-4c5317631166', 'sent_to_vendor', 'ready_for_pickup', NULL, NULL, '2026-04-11 19:41:27'),
('8710fffb-3b30-11f1-9448-e66239f31cb0', '1fa6fc17-b531-490c-a327-775013af98db', 'pending', 'vendor_accepted', NULL, NULL, '2026-04-18 17:11:43'),
('880873fb-35de-11f1-9448-e66239f31cb0', '1e8023a6-a8d0-42d5-b4bb-4c5317631166', 'ready_for_pickup', 'out_for_delivery', NULL, NULL, '2026-04-11 19:42:10'),
('8868c629-36cf-11f1-9448-e66239f31cb0', 'fefb970d-6c86-42fa-ac39-d0c6c17fe37c', 'pending', 'vendor_accepted', NULL, NULL, '2026-04-13 03:27:19'),
('a121bf4f-36cf-11f1-9448-e66239f31cb0', 'fefb970d-6c86-42fa-ac39-d0c6c17fe37c', 'vendor_accepted', 'preparing', NULL, NULL, '2026-04-13 03:28:01'),
('a2f385f9-36cf-11f1-9448-e66239f31cb0', 'fefb970d-6c86-42fa-ac39-d0c6c17fe37c', 'preparing', 'ready_for_pickup', NULL, NULL, '2026-04-13 03:28:04'),
('a676daea-35cd-11f1-9448-e66239f31cb0', 'a1c23979-4844-4910-b6c7-d96f1d5807af', 'pending', 'admin_confirmed', NULL, NULL, '2026-04-11 17:41:20'),
('a676ed8d-35cd-11f1-9448-e66239f31cb0', 'a1c23979-4844-4910-b6c7-d96f1d5807af', 'admin_confirmed', 'sent_to_vendor', NULL, NULL, '2026-04-11 17:41:20'),
('b0092ced-3d98-11f1-9448-e66239f31cb0', 'f5d055ec-9300-445f-af99-77077d8cc519', 'pending', 'admin_confirmed', NULL, NULL, '2026-04-21 18:42:22'),
('b0094502-3d98-11f1-9448-e66239f31cb0', 'f5d055ec-9300-445f-af99-77077d8cc519', 'admin_confirmed', 'sent_to_vendor', NULL, NULL, '2026-04-21 18:42:22'),
('bf6695dd-3d8f-11f1-9448-e66239f31cb0', '8f957033-af0e-4c09-a600-74344e7b1485', 'pending', 'admin_confirmed', NULL, NULL, '2026-04-21 17:38:22'),
('bf66bc75-3d8f-11f1-9448-e66239f31cb0', '8f957033-af0e-4c09-a600-74344e7b1485', 'admin_confirmed', 'sent_to_vendor', NULL, NULL, '2026-04-21 17:38:22'),
('caa9c65d-35e0-11f1-9448-e66239f31cb0', '1e8023a6-a8d0-42d5-b4bb-4c5317631166', 'out_for_delivery', 'delivered', NULL, NULL, '2026-04-11 19:58:21'),
('cb5a9f61-36cf-11f1-9448-e66239f31cb0', 'fefb970d-6c86-42fa-ac39-d0c6c17fe37c', 'ready_for_pickup', 'out_for_delivery', NULL, NULL, '2026-04-13 03:29:12'),
('cdee5c11-36cf-11f1-9448-e66239f31cb0', 'fefb970d-6c86-42fa-ac39-d0c6c17fe37c', 'out_for_delivery', 'delivered', NULL, NULL, '2026-04-13 03:29:16'),
('cdf79fcb-35de-11f1-9448-e66239f31cb0', 'a1c23979-4844-4910-b6c7-d96f1d5807af', 'ready_for_pickup', 'out_for_delivery', NULL, NULL, '2026-04-11 19:44:07'),
('cec2519b-35e0-11f1-9448-e66239f31cb0', 'a1c23979-4844-4910-b6c7-d96f1d5807af', 'out_for_delivery', 'delivered', NULL, NULL, '2026-04-11 19:58:28'),
('d4578924-3696-11f1-9448-e66239f31cb0', '7175c222-70ed-49bf-929d-11fb434f107a', 'pending', 'admin_confirmed', NULL, NULL, '2026-04-12 20:41:26'),
('d457c1fe-3696-11f1-9448-e66239f31cb0', '7175c222-70ed-49bf-929d-11fb434f107a', 'admin_confirmed', 'sent_to_vendor', NULL, NULL, '2026-04-12 20:41:26'),
('e5ca1d58-3696-11f1-9448-e66239f31cb0', '7175c222-70ed-49bf-929d-11fb434f107a', 'sent_to_vendor', 'vendor_accepted', NULL, NULL, '2026-04-12 20:41:55'),
('e8d93c2a-3696-11f1-9448-e66239f31cb0', '7175c222-70ed-49bf-929d-11fb434f107a', 'vendor_accepted', 'preparing', NULL, NULL, '2026-04-12 20:42:00'),
('ea4ebad8-3696-11f1-9448-e66239f31cb0', '7175c222-70ed-49bf-929d-11fb434f107a', 'preparing', 'ready_for_pickup', NULL, NULL, '2026-04-12 20:42:02'),
('eeb1c4d5-3d8f-11f1-9448-e66239f31cb0', '8f957033-af0e-4c09-a600-74344e7b1485', 'sent_to_vendor', 'vendor_accepted', NULL, NULL, '2026-04-21 17:39:41'),
('ef65f899-3d98-11f1-9448-e66239f31cb0', 'f5d055ec-9300-445f-af99-77077d8cc519', 'sent_to_vendor', 'ready_for_pickup', NULL, NULL, '2026-04-21 18:44:08'),
('f21d61bb-35cd-11f1-9448-e66239f31cb0', 'a1c23979-4844-4910-b6c7-d96f1d5807af', 'sent_to_vendor', 'vendor_accepted', NULL, NULL, '2026-04-11 17:43:27');

-- --------------------------------------------------------

--
-- Table structure for table `order_vendors`
--

CREATE TABLE `order_vendors` (
  `id` char(36) NOT NULL DEFAULT uuid(),
  `order_id` char(36) NOT NULL,
  `vendor_id` char(36) NOT NULL,
  `subtotal` decimal(10,2) NOT NULL,
  `vendor_notes` text DEFAULT NULL,
  `vendor_delivery_fee` decimal(8,2) NOT NULL DEFAULT 0.00,
  `vendor_net_amount` decimal(10,2) NOT NULL,
  `status` enum('sent_to_vendor','vendor_accepted','preparing','ready_for_pickup','out_for_delivery','delivered','cancelled') NOT NULL DEFAULT 'sent_to_vendor',
  `accepted_at` datetime DEFAULT NULL,
  `ready_at` datetime DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `order_vendors`
--

INSERT INTO `order_vendors` (`id`, `order_id`, `vendor_id`, `subtotal`, `vendor_notes`, `vendor_delivery_fee`, `vendor_net_amount`, `status`, `accepted_at`, `ready_at`, `created_at`) VALUES
('131b2413-8ff6-4583-b6fd-5527b0834b6b', 'fb6fb2f8-1d1d-428c-98f9-0e3cfffe49a2', '57e1966b-294a-11f1-9448-e66239f31cb0', 500.00, NULL, 5.00, 440.00, 'sent_to_vendor', NULL, NULL, '2026-04-12 20:49:45'),
('1432b89a-9da1-4331-88b7-026fd0014f1f', 'a1c23979-4844-4910-b6c7-d96f1d5807af', '95a07fca-231c-11f1-b82d-97044e7d8834', 850.00, NULL, 20.00, 748.00, 'delivered', '2026-04-11 17:43:27', '2026-04-11 17:44:54', '2026-04-11 17:39:24'),
('1b9d7318-b413-4265-8753-c7b66a2f4906', '7175c222-70ed-49bf-929d-11fb434f107a', '57e1966b-294a-11f1-9448-e66239f31cb0', 680.00, NULL, 20.00, 598.40, 'delivered', '2026-04-12 20:41:55', '2026-04-12 20:42:02', '2026-04-12 20:40:00'),
('1c062906-ef02-41dd-81d3-22031361ad2e', 'fefb970d-6c86-42fa-ac39-d0c6c17fe37c', '57e1966b-294a-11f1-9448-e66239f31cb0', 2060.00, NULL, 20.00, 1812.80, 'delivered', '2026-04-13 03:27:19', '2026-04-13 03:28:04', '2026-04-13 03:26:56'),
('1e6300e4-f85d-4474-8867-d2d6fb9a387c', '8f957033-af0e-4c09-a600-74344e7b1485', '5de1f9b7-3d21-11f1-9448-e66239f31cb0', 100.00, NULL, 10.00, 88.00, 'delivered', '2026-04-21 17:39:41', '2026-04-21 17:40:20', '2026-04-21 17:35:31'),
('33426336-2b1a-498b-8ecc-a751fab745d4', '1e8023a6-a8d0-42d5-b4bb-4c5317631166', '57e1966b-294a-11f1-9448-e66239f31cb0', 500.00, NULL, 5.00, 440.00, 'delivered', '2026-04-11 19:39:41', '2026-04-11 19:39:54', '2026-04-11 19:37:49'),
('37cc36bc-8c56-4823-951d-64a1d1f5482e', 'ade4e928-3973-4f5a-8b9b-42b7608ddb22', '95a07fca-231c-11f1-b82d-97044e7d8834', 1800.00, NULL, 20.00, 1584.00, 'cancelled', NULL, NULL, '2026-04-13 18:19:52'),
('47453639-d63e-4053-bc41-a4cc1abf0c90', 'a1c23979-4844-4910-b6c7-d96f1d5807af', '57e1966b-294a-11f1-9448-e66239f31cb0', 500.00, NULL, 5.00, 440.00, 'delivered', '2026-04-11 17:42:17', '2026-04-11 17:44:08', '2026-04-11 17:39:24'),
('50970209-97d1-41a1-beb3-134688ad7b28', 'd697bc38-0e26-476d-9790-85e0972c75c1', '57e1966b-294a-11f1-9448-e66239f31cb0', 500.00, NULL, 20.00, 440.00, 'sent_to_vendor', NULL, NULL, '2026-04-20 03:25:01'),
('614bcd4d-c31a-4f56-b8da-396f597e32f9', '7a40fa48-aadc-4a85-80fc-57599d58a973', '95a07fca-231c-11f1-b82d-97044e7d8834', 850.00, NULL, 20.00, 748.00, 'sent_to_vendor', NULL, NULL, '2026-04-11 17:51:13'),
('6e538937-0bf4-4a6a-9925-266c6026fc1b', 'd29a86e5-2e14-4e19-8884-0f2db2b8d8d5', '5de1f9b7-3d21-11f1-9448-e66239f31cb0', 100.00, NULL, 10.00, 88.00, '', NULL, NULL, '2026-04-21 04:54:16'),
('79bd29a4-d0dd-4e76-b823-b60c1f70c80e', 'f5d055ec-9300-445f-af99-77077d8cc519', '6255f135-3d97-11f1-9448-e66239f31cb0', 1000.00, NULL, 25.00, 880.00, 'delivered', '2026-04-21 18:44:01', '2026-04-21 18:44:08', '2026-04-21 18:40:10'),
('7a05da77-a1c5-420e-8c7b-02a399cca2cf', '1e8023a6-a8d0-42d5-b4bb-4c5317631166', '95a07fca-231c-11f1-b82d-97044e7d8834', 850.00, NULL, 20.00, 748.00, 'delivered', '2026-04-11 19:41:11', '2026-04-11 19:41:27', '2026-04-11 19:37:49'),
('8a6ab146-de5d-4e35-af67-f25cfad6e736', '937c9853-526f-41e1-9be0-06a69ff2b437', '57e1966b-294a-11f1-9448-e66239f31cb0', 500.00, NULL, 20.00, 440.00, 'sent_to_vendor', NULL, NULL, '2026-04-12 20:51:06'),
('8c2ddff8-d2e9-4aa5-ad4a-171fafece7ce', 'f6203869-9831-43d0-a8c7-b814f55b5de8', '5de1f9b7-3d21-11f1-9448-e66239f31cb0', 100.00, NULL, 20.00, 88.00, 'ready_for_pickup', '2026-04-21 04:34:42', '2026-04-21 04:35:05', '2026-04-21 04:33:30'),
('9390384c-5464-4350-af12-a1911efe695f', '7a40fa48-aadc-4a85-80fc-57599d58a973', '57e1966b-294a-11f1-9448-e66239f31cb0', 500.00, NULL, 5.00, 440.00, 'sent_to_vendor', NULL, NULL, '2026-04-11 17:51:13'),
('a7aee875-b17d-48c6-81d0-0766c09e118a', '1fa6fc17-b531-490c-a327-775013af98db', '57e1966b-294a-11f1-9448-e66239f31cb0', 380.00, NULL, 20.00, 334.40, 'vendor_accepted', '2026-04-18 17:11:43', NULL, '2026-04-11 17:38:58'),
('aeac8ae0-df4c-4451-bd80-abc807b2f862', 'e39e531f-02d7-402b-bfa1-4bc7911fdca0', '57e1966b-294a-11f1-9448-e66239f31cb0', 1200.00, NULL, 20.00, 1056.00, 'vendor_accepted', '2026-04-21 03:07:53', NULL, '2026-04-21 03:07:03'),
('f2cb77a3-76f6-49db-b56d-9e4454016623', 'fb6fb2f8-1d1d-428c-98f9-0e3cfffe49a2', '95a07fca-231c-11f1-b82d-97044e7d8834', 850.00, NULL, 20.00, 748.00, 'sent_to_vendor', NULL, NULL, '2026-04-12 20:49:45'),
('f88997dc-314c-4ab1-8f82-40957eecbecf', 'f5d055ec-9300-445f-af99-77077d8cc519', '5de1f9b7-3d21-11f1-9448-e66239f31cb0', 100.00, NULL, 0.00, 88.00, 'delivered', '2026-04-21 18:42:57', '2026-04-21 18:43:08', '2026-04-21 18:40:10');

-- --------------------------------------------------------

--
-- Table structure for table `platform_earnings`
--

CREATE TABLE `platform_earnings` (
  `id` char(36) NOT NULL DEFAULT uuid(),
  `order_id` char(36) NOT NULL,
  `order_number` varchar(20) NOT NULL,
  `vendor_id` char(36) DEFAULT NULL,
  `order_total` decimal(10,2) NOT NULL,
  `vendor_subtotal` decimal(10,2) NOT NULL DEFAULT 0.00,
  `platform_fee` decimal(8,2) NOT NULL,
  `service_fee_earned` decimal(8,2) NOT NULL DEFAULT 0.00,
  `commission_earned` decimal(8,2) NOT NULL DEFAULT 0.00,
  `commission_rate_snapshot` decimal(5,2) NOT NULL DEFAULT 0.00,
  `delivery_fee_earned` decimal(8,2) NOT NULL DEFAULT 0.00,
  `vendor_payout` decimal(10,2) NOT NULL DEFAULT 0.00,
  `fee_model` enum('flat_per_order','percentage') NOT NULL,
  `fee_value_snapshot` decimal(8,2) NOT NULL COMMENT 'Exact fee value at time of order',
  `is_collected` tinyint(1) NOT NULL DEFAULT 0,
  `collected_at` datetime DEFAULT NULL,
  `earned_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `platform_earnings`
--

INSERT INTO `platform_earnings` (`id`, `order_id`, `order_number`, `vendor_id`, `order_total`, `vendor_subtotal`, `platform_fee`, `service_fee_earned`, `commission_earned`, `commission_rate_snapshot`, `delivery_fee_earned`, `vendor_payout`, `fee_model`, `fee_value_snapshot`, `is_collected`, `collected_at`, `earned_at`) VALUES
('0514bf42-3d99-11f1-9448-e66239f31cb0', 'f5d055ec-9300-445f-af99-77077d8cc519', 'ORD-2026-000015', '5de1f9b7-3d21-11f1-9448-e66239f31cb0', 1132.00, 100.00, 12.00, 0.00, 12.00, 12.00, 0.00, 88.00, 'flat_per_order', 12.00, 0, NULL, '2026-04-21 18:44:44'),
('0514c045-3d99-11f1-9448-e66239f31cb0', 'f5d055ec-9300-445f-af99-77077d8cc519', 'ORD-2026-000015', '6255f135-3d97-11f1-9448-e66239f31cb0', 1132.00, 1000.00, 122.00, 2.00, 120.00, 12.00, 25.00, 880.00, 'flat_per_order', 12.00, 0, NULL, '2026-04-21 18:44:44'),
('06c6ff57-3697-11f1-9448-e66239f31cb0', '7175c222-70ed-49bf-929d-11fb434f107a', 'ORD-2026-000005', '57e1966b-294a-11f1-9448-e66239f31cb0', 702.00, 680.00, 83.60, 2.00, 81.60, 12.00, 20.00, 598.40, 'flat_per_order', 12.00, 0, NULL, '2026-04-12 20:42:50'),
('21b48b29-3d90-11f1-9448-e66239f31cb0', '8f957033-af0e-4c09-a600-74344e7b1485', 'ORD-2026-000014', '5de1f9b7-3d21-11f1-9448-e66239f31cb0', 112.00, 100.00, 14.00, 2.00, 12.00, 12.00, 10.00, 88.00, 'flat_per_order', 12.00, 0, NULL, '2026-04-21 17:41:07'),
('caa9d5ed-35e0-11f1-9448-e66239f31cb0', '1e8023a6-a8d0-42d5-b4bb-4c5317631166', 'ORD-2026-000004', '57e1966b-294a-11f1-9448-e66239f31cb0', 1377.00, 500.00, 62.00, 2.00, 60.00, 12.00, 5.00, 440.00, 'flat_per_order', 12.00, 0, NULL, '2026-04-11 19:58:21'),
('caa9d6db-35e0-11f1-9448-e66239f31cb0', '1e8023a6-a8d0-42d5-b4bb-4c5317631166', 'ORD-2026-000004', '95a07fca-231c-11f1-b82d-97044e7d8834', 1377.00, 850.00, 102.00, 0.00, 102.00, 12.00, 20.00, 748.00, 'flat_per_order', 12.00, 0, NULL, '2026-04-11 19:58:21'),
('cdee8af2-36cf-11f1-9448-e66239f31cb0', 'fefb970d-6c86-42fa-ac39-d0c6c17fe37c', 'ORD-2026-000008', '57e1966b-294a-11f1-9448-e66239f31cb0', 2082.00, 2060.00, 249.20, 2.00, 247.20, 12.00, 20.00, 1812.80, 'flat_per_order', 12.00, 0, NULL, '2026-04-13 03:29:16'),
('cec2627e-35e0-11f1-9448-e66239f31cb0', 'a1c23979-4844-4910-b6c7-d96f1d5807af', 'ORD-2026-000002', '57e1966b-294a-11f1-9448-e66239f31cb0', 1377.00, 500.00, 60.00, 0.00, 60.00, 12.00, 5.00, 440.00, 'flat_per_order', 12.00, 1, '2026-04-12 05:58:29', '2026-04-11 19:58:28'),
('cec2635b-35e0-11f1-9448-e66239f31cb0', 'a1c23979-4844-4910-b6c7-d96f1d5807af', 'ORD-2026-000002', '95a07fca-231c-11f1-b82d-97044e7d8834', 1377.00, 850.00, 104.00, 2.00, 102.00, 12.00, 20.00, 748.00, 'flat_per_order', 12.00, 0, NULL, '2026-04-11 19:58:28');

-- --------------------------------------------------------

--
-- Table structure for table `ratings`
--

CREATE TABLE `ratings` (
  `id` char(36) NOT NULL DEFAULT uuid(),
  `order_id` char(36) NOT NULL,
  `customer_id` char(36) NOT NULL,
  `vendor_id` char(36) NOT NULL,
  `score` tinyint(1) NOT NULL COMMENT '1 to 5',
  `comment` text DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp()
) ;

--
-- Dumping data for table `ratings`
--

INSERT INTO `ratings` (`id`, `order_id`, `customer_id`, `vendor_id`, `score`, `comment`, `created_at`) VALUES
('1e2ce715-3d99-11f1-9448-e66239f31cb0', 'f5d055ec-9300-445f-af99-77077d8cc519', '1d8557e5-8a7d-4c32-8181-ad785ea979a0', '5de1f9b7-3d21-11f1-9448-e66239f31cb0', 5, NULL, '2026-04-21 18:45:27'),
('35d3a0ab-3d99-11f1-9448-e66239f31cb0', '8f957033-af0e-4c09-a600-74344e7b1485', '1d8557e5-8a7d-4c32-8181-ad785ea979a0', '5de1f9b7-3d21-11f1-9448-e66239f31cb0', 5, 'بيرفكت', '2026-04-21 18:46:06'),
('40fa4b30-36d0-11f1-9448-e66239f31cb0', 'fefb970d-6c86-42fa-ac39-d0c6c17fe37c', '0717139f-9fbc-4c8c-be98-cf1361e0036c', '57e1966b-294a-11f1-9448-e66239f31cb0', 5, 'روعة, و الله يباركله بشمهندس ياسر علي هالعمل الممتاز', '2026-04-13 03:32:29'),
('925ceda2-3677-11f1-9448-e66239f31cb0', '1e8023a6-a8d0-42d5-b4bb-4c5317631166', '43d5d6a2-e09d-4581-8636-557cd150b0f1', '57e1966b-294a-11f1-9448-e66239f31cb0', 5, 'خدمة ممتازة', '2026-04-12 16:57:40');

--
-- Triggers `ratings`
--
DELIMITER $$
CREATE TRIGGER `trg_update_vendor_rating_on_insert` AFTER INSERT ON `ratings` FOR EACH ROW BEGIN
    UPDATE vendors
    SET
        rating       = (
            SELECT ROUND(AVG(score), 2)
            FROM ratings
            WHERE vendor_id = NEW.vendor_id
        ),
        total_ratings = (
            SELECT COUNT(*)
            FROM ratings
            WHERE vendor_id = NEW.vendor_id
        )
    WHERE id = NEW.vendor_id;
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `trg_update_vendor_rating_on_update` AFTER UPDATE ON `ratings` FOR EACH ROW BEGIN
    UPDATE vendors
    SET
        rating        = (
            SELECT ROUND(AVG(score), 2)
            FROM ratings
            WHERE vendor_id = NEW.vendor_id
        ),
        total_ratings = (
            SELECT COUNT(*)
            FROM ratings
            WHERE vendor_id = NEW.vendor_id
        )
    WHERE id = NEW.vendor_id;
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` char(36) NOT NULL DEFAULT uuid(),
  `name` varchar(100) NOT NULL,
  `phone` varchar(20) NOT NULL,
  `email` varchar(150) DEFAULT NULL,
  `password_hash` varchar(255) NOT NULL,
  `role` enum('customer','vendor','admin','super_admin') NOT NULL,
  `zone_coverage` varchar(50) DEFAULT NULL,
  `address` text DEFAULT NULL,
  `latitude` decimal(10,8) DEFAULT NULL,
  `longitude` decimal(11,8) DEFAULT NULL,
  `fcm_token` text DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `email_verification_token` varchar(100) DEFAULT NULL,
  `email_verified_at` datetime DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `phone`, `email`, `password_hash`, `role`, `zone_coverage`, `address`, `latitude`, `longitude`, `fcm_token`, `is_active`, `email_verification_token`, `email_verified_at`, `created_at`, `updated_at`) VALUES
('0717139f-9fbc-4c8c-be98-cf1361e0036c', 'Hamza Omran', '01551130045', 'hamza127444@gmail.com', '$2y$10$lW.1u9CisleEuuz0u3Xlm.deyPlirEytGC1SZIz9S7MRSr66L47FO', 'customer', NULL, NULL, NULL, NULL, NULL, 1, NULL, '2026-04-12 07:10:59', '2026-04-12 07:10:07', '2026-04-21 17:30:05'),
('1d8557e5-8a7d-4c32-8181-ad785ea979a0', 'seham', '01003690090', 'sehamhamdy909@gmail.com', '$2y$10$YkDWCP1HETZ0LvQ2hc9CDuuEcwVxOcDXFSuTu8vsXaRz1rhGzVkvy', 'customer', NULL, NULL, NULL, NULL, NULL, 1, NULL, '2026-04-21 03:02:48', '2026-04-21 03:02:28', '2026-04-21 03:02:48'),
('33ba298c-7bd8-4338-b16b-3817a328e8ed', 'Kareem Aboshady', '01508315828', 'Kareemaboshady949@gmail.com', '$2y$10$bAJ034sWpLEiWwikD75JuewRW5MCVv.pv7c01dy95zvRFh5MWuSne', 'customer', NULL, NULL, NULL, NULL, NULL, 1, NULL, '2026-04-12 20:39:17', '2026-04-12 20:38:42', '2026-04-13 00:49:30'),
('37c0e9a4-a2d3-4654-b58c-726f1cd4576b', 'Abdurhman hesham', '01277092203', 'abdurhmanhishaam@gmail.com', '$2y$10$WQbleTWLog580DvkfatgCud3d6uYxbKqVPUQL3LuwEjwVgCXirICa', 'customer', NULL, NULL, NULL, NULL, NULL, 1, NULL, '2026-04-12 11:26:28', '2026-04-12 11:26:03', '2026-04-12 11:26:28'),
('43d5d6a2-e09d-4581-8636-557cd150b0f1', 'Yasser Ashraf', '01003660061', 'flutter1developer1@gmail.com', '$2y$10$OZdbocv9aJo3fTevJzm8C.qDvbU85.otwzPCGUKt9WdrR1EpAXtge', 'customer', NULL, NULL, NULL, NULL, NULL, 1, NULL, '2026-04-06 15:14:47', '2026-04-06 15:14:23', '2026-04-08 23:32:09'),
('47ed0b4b-e41f-49d7-8044-087a7200111b', 'سماح', '0599488857', 'samahadas66@gmail.com', '$2y$10$hxr7JzzcPey/a1tAqVhJWu6iXX91GHNVmi7BkAt/ksxNcEK9NQWvC', 'customer', NULL, NULL, NULL, NULL, NULL, 1, NULL, '2026-04-12 21:20:48', '2026-04-12 21:19:49', '2026-04-12 21:20:48'),
('4f792c75-4a65-45a7-a0ef-d282acd83d87', 'فتح الله ماركت', '01003600000', 'vendor_yasser@gmail.com', '$2y$10$kMiAa4BQ/A1QJat5VvM7j.G9fCA.YhJ.bCRcoOo2OzkamzdDR8LyS', 'vendor', NULL, NULL, NULL, NULL, NULL, 1, NULL, NULL, '2026-03-26 19:31:08', '2026-04-21 04:26:31'),
('568753fd-bfa7-4920-a12a-36bd71277519', 'ياسر', '01009080031', 'yasserashraf3142@gmail.com', '$2y$10$zNNjgQWdQvyVd.nZyVVIEOpN.b4aMXmzB3Tw7MhT255FYtDUdu392', 'customer', NULL, NULL, NULL, NULL, NULL, 1, NULL, '2026-04-10 20:27:02', '2026-04-10 20:26:31', '2026-04-20 03:23:15'),
('56b3aab6-d530-419b-9e84-afb3c9db0063', 'wael', '01030420605', 'waeeel989@gmail.com', '$2y$10$uS3XPRX8xgb1CdWqsU.5nu30BeoekkrqNKMxBlBl7F5OUF4NXccHu', 'customer', NULL, NULL, NULL, NULL, NULL, 1, NULL, '2026-04-13 19:23:08', '2026-04-13 19:22:13', '2026-04-13 19:23:08'),
('5732093a-a1d5-46ba-9432-ac9f560ba03c', 'loay_mdohk', '0599261116', 'lwymdwkh@gmail.com', '$2y$10$aCsJxEA/JjdWRP9u18CmhuV.r1RmTk/SVHFlBzke62kg6vSh36fyi', 'customer', NULL, NULL, NULL, NULL, NULL, 1, NULL, '2026-04-12 19:25:44', '2026-04-12 19:24:01', '2026-04-12 19:25:44'),
('6255a20b-3d97-11f1-9448-e66239f31cb0', 'south', '0123640081', 'southvendor@gmail.com', '$2y$10$KBye4z16OZki3.SS3wz56OE2NFOybNYRK6Aip1KsxpnOYxhvlQl0C', 'vendor', NULL, NULL, NULL, NULL, NULL, 1, NULL, '2026-04-21 18:33:02', '2026-04-21 18:33:02', '2026-04-21 18:43:56'),
('62ab7c1e-303a-11f1-9448-e66239f31cb0', 'loay', '+970 599 261 116', 'loay@gmail.com', '$2y$10$DW.mnfymw2GdlwVgoUddbu.owYUSMtkYNxJRxixJB4s2vPkn4ZQAe', 'admin', NULL, NULL, NULL, NULL, NULL, 0, NULL, '2026-04-04 15:24:34', '2026-04-04 15:24:34', '2026-04-06 21:49:56'),
('68d947ef-fcec-426a-8316-155666848619', 'notch vendor', '01003680081', 'northvendor@gmail.com', '$2y$10$6oYyuAJbmw2vbx9x7XTKeOuO7FSXBoOvVjhyKnEyhj/pXkEVHb/Km', 'vendor', NULL, NULL, NULL, NULL, NULL, 1, NULL, NULL, '2026-04-21 04:28:14', '2026-04-21 18:42:51'),
('76770782-1da5-4210-9612-b4dd65636c8b', 'yasser', '01003690031', 'yasergaber1432@gmail.com', '$2y$10$FAutKNtf84AmCxFCKQ.2BOAlvcIgG1pnyhWkHn1CyBZCd1mhYO6na', 'customer', NULL, NULL, NULL, NULL, NULL, 1, NULL, NULL, '2026-04-06 15:11:57', '2026-04-06 15:11:57'),
('80c0bb8b-082d-4810-806f-0e1901d9d787', 'سوق مصر', '01003640091', 'yasergaber143210@gmail.com', '$2y$10$kMiAa4BQ/A1QJat5VvM7j.G9fCA.YhJ.bCRcoOo2OzkamzdDR8LyS', 'vendor', NULL, NULL, NULL, NULL, NULL, 1, NULL, NULL, '2026-03-18 22:00:21', '2026-04-11 23:01:45'),
('88c31ecc-afbb-4271-9bef-c2a90725e3e7', 'ياسر', '01003696031', 'mofo650@gmail.com', '$2y$10$1.XLtIreA/EWWeAeFEI8lOY7Y4YKP9kXLIi5B2ljPfsnSZeEnxBum', 'customer', NULL, NULL, NULL, NULL, NULL, 1, NULL, NULL, '2026-04-08 23:33:05', '2026-04-08 23:33:05'),
('8ba11278-3b31-11f1-9448-e66239f31cb0', 'إبراهيم نعمان', '+972595098966', 'ibrahim187shmi@gmail.com', '$2y$10$VinfeF5uCMV9K9cF0zm7..XcTnKnKTj8LogF8tucRM.ol4ybpLrXa', 'admin', NULL, NULL, NULL, NULL, NULL, 1, NULL, '2026-04-18 17:19:00', '2026-04-18 17:19:00', '2026-04-20 21:08:43'),
('8f266f22-1d9d-11f1-b82d-97044e7d8834', 'Super Admin', '01003640090', 'super@gmail.com', '$2y$10$LX10/k6k0cPj9o01vaP0V.gmZw1LZ/h0yGF60/rU31DJBuazxI1u2', 'super_admin', NULL, NULL, NULL, NULL, NULL, 1, NULL, '2026-03-11 22:55:11', '2026-03-11 22:55:11', '2026-04-21 18:06:31'),
('912da94f-052d-4315-ac62-913792533a89', 'يزن', '0593492004', 'samayazan33@gmail.com', '$2y$10$5vdFexBRkhHXE7cyZBJrnuPzDUicFYQq24l6ZR4Z41nriRim6Hsrm', 'customer', NULL, NULL, NULL, NULL, NULL, 1, NULL, '2026-04-12 19:48:10', '2026-04-12 19:47:43', '2026-04-12 19:48:10'),
('9be6c6f3-9ffa-4d9e-b4d7-2c48fff4fa6f', 'test', '01003690081', 'test-app@gmail.com', '$2y$10$eRaAuwdxXwloF3KwqiqBBeXXMRd6AmPmwudJ/J1lc54HOejbEQ62O', 'customer', NULL, NULL, NULL, NULL, NULL, 1, NULL, '2026-04-12 04:51:59', '2026-04-12 04:51:36', '2026-04-12 01:52:29'),
('a093003a-6eaf-42cf-92e0-f4660c0550e2', 'Test User', '0599082512', 'test_user_004346@test.com', '$2y$10$oPGdCPtfCmdQtMWWsGhvC.ffV6J2p.52q01ql8Bo.9lHQRqbAXwHy', 'customer', NULL, NULL, NULL, NULL, NULL, 1, NULL, NULL, '2026-03-12 00:43:46', '2026-04-03 19:53:22'),
('a36c8e67-3d01-489b-a1ef-742e4b470bbe', 'ياسر', '01003699031', 'mofo6050@gmail.com', '$2y$10$dfhn/To4tBvuA/UxDUlQLehUN4uJqxNv6LE9mFBubPeHoomq4SWIy', 'customer', NULL, NULL, NULL, NULL, NULL, 1, NULL, '2026-04-08 23:34:17', '2026-04-08 23:33:57', '2026-04-08 23:34:17'),
('ad30e781-9ca7-4f1f-825f-40b0d4d4b8c0', 'User', '0591326232', 'test_user_013803@test.com', '$2y$10$C4Nya1LpWcC9WvqV7V15u.uw35hw5nDNAV.BapmtTNhm12VfRy47.', 'customer', NULL, NULL, NULL, NULL, NULL, 1, NULL, NULL, '2026-03-12 01:38:04', '2026-04-04 01:27:20'),
('b8175b08-34e6-4f5d-87d3-4c777677db33', 'سوق غزة', '01003680040', 'ashrafgaber3142@gmail.com', '$2y$10$kMiAa4BQ/A1QJat5VvM7j.G9fCA.YhJ.bCRcoOo2OzkamzdDR8LyS', 'vendor', NULL, NULL, NULL, NULL, NULL, 1, NULL, NULL, '2026-03-23 20:14:27', '2026-04-11 23:09:02'),
('be9db6e5-2efe-11f1-9448-e66239f31cb0', 'كرم الشام', '01003681141', 'vendoradmin@gmail.com', '$2y$10$kMiAa4BQ/A1QJat5VvM7j.G9fCA.YhJ.bCRcoOo2OzkamzdDR8LyS', 'vendor', NULL, NULL, NULL, NULL, NULL, 1, NULL, '2026-04-03 01:45:07', '2026-04-03 01:45:07', '2026-04-11 23:03:15'),
('c547494a-e800-4b60-a42d-3109d2d5ba7f', 'Mahmoud', '0592995063', 'mhmwdhsnat51@gmail.com', '$2y$10$4NkYL0rx21.trveyFiCNrug3OPk01I0yJx.TEcOh6TTjroCzrbf4C', 'customer', NULL, NULL, NULL, NULL, NULL, 1, NULL, '2026-04-12 20:21:39', '2026-04-12 20:19:49', '2026-04-12 20:21:39'),
('cf2239d2-2dd7-4116-abd3-1f24d48e8fe7', 'سوق', '01003640061', 'yassermohammed3142@gmail.com', '$2y$10$kMiAa4BQ/A1QJat5VvM7j.G9fCA.YhJ.bCRcoOo2OzkamzdDR8LyS', 'vendor', NULL, NULL, NULL, NULL, NULL, 1, NULL, NULL, '2026-03-18 22:47:04', '2026-04-11 23:00:49'),
('d1dc6815-112d-4645-997a-ce0a96eb59f2', 'yasser', '01003690091', 'yassergaber1432@gmail.com', '$2y$10$4cyyNLOKd6W2OOrKYU4vN.oNZmLeHWEorg1Yqz38HmSliBS9QfYs.', 'customer', NULL, NULL, NULL, NULL, NULL, 1, NULL, NULL, '2026-04-06 15:10:07', '2026-04-06 15:10:07'),
('d8e13ad8-2382-11f1-b82d-97044e7d8834', 'admin1', '01003680091', 'gaberyasser555@gmail.com', '$2y$10$Jgk90msBdYc1smF8Zr6Bcu2mt74cvbNdbydTiAyinl0QSe2M.ibUS', 'admin', NULL, NULL, NULL, NULL, NULL, 1, NULL, '2026-03-19 10:59:05', '2026-03-19 10:59:05', '2026-04-21 02:59:51'),
('e64dd274-3d20-11f1-9448-e66239f31cb0', 'north', '01003640180', 'north@gmail.com', '$2y$10$WwFIQ2smxMbUFcLzLIw4M.AInPOi/uLKH19KtW43rLWbWZntp8.IC', 'admin', 'north', NULL, NULL, NULL, NULL, 1, NULL, '2026-04-21 04:24:53', '2026-04-21 04:24:53', '2026-04-21 18:44:29'),
('f52cc98d-3d20-11f1-9448-e66239f31cb0', 'south', '01003690021', 'south@gmail.com', '$2y$10$uHSFwbLgJuLvG3zbfSL4Dulu2BA21nbeu8Zi0bxTgXroUvHWRFCp2', 'admin', 'south', NULL, NULL, NULL, NULL, 1, NULL, '2026-04-21 04:25:18', '2026-04-21 04:25:18', '2026-04-21 18:43:33');

-- --------------------------------------------------------

--
-- Table structure for table `vendors`
--

CREATE TABLE `vendors` (
  `id` char(36) NOT NULL DEFAULT uuid(),
  `user_id` char(36) NOT NULL,
  `name` varchar(150) NOT NULL,
  `type` enum('restaurant','store','cafe') NOT NULL,
  `description` text DEFAULT NULL,
  `logo_url` text DEFAULT NULL,
  `cover_url` text DEFAULT NULL,
  `phone` varchar(20) NOT NULL,
  `address` text NOT NULL,
  `city` varchar(100) DEFAULT NULL,
  `neighborhood` varchar(100) DEFAULT NULL,
  `latitude` decimal(10,8) NOT NULL,
  `longitude` decimal(11,8) NOT NULL,
  `opening_time` time DEFAULT NULL,
  `closing_time` time DEFAULT NULL,
  `delivery_zone` enum('north','central','south') NOT NULL DEFAULT 'north',
  `is_open` tinyint(1) NOT NULL DEFAULT 1,
  `min_order_amount` decimal(10,2) NOT NULL DEFAULT 0.00,
  `estimated_delivery_time` varchar(50) DEFAULT NULL,
  `operating_hours` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`operating_hours`)),
  `status` enum('pending','approved','suspended','rejected') NOT NULL DEFAULT 'pending',
  `rating` decimal(3,2) NOT NULL DEFAULT 0.00,
  `total_ratings` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `commission_rate` decimal(5,2) NOT NULL DEFAULT 12.00,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `vendors`
--

INSERT INTO `vendors` (`id`, `user_id`, `name`, `type`, `description`, `logo_url`, `cover_url`, `phone`, `address`, `city`, `neighborhood`, `latitude`, `longitude`, `opening_time`, `closing_time`, `delivery_zone`, `is_open`, `min_order_amount`, `estimated_delivery_time`, `operating_hours`, `status`, `rating`, `total_ratings`, `commission_rate`, `created_at`, `updated_at`) VALUES
('0f38e4e9-2316-11f1-b82d-97044e7d8834', '80c0bb8b-082d-4810-806f-0e1901d9d787', 'سوق مصر', 'store', NULL, 'https://appforyou.store/backendOrder/upload/vendors/69da83f5880ab_1775928309.png', 'https://appforyou.store/backendOrder/upload/vendors/69da83fcbf517_1775928316.jpg', '01003640090', 'gaza', 'مدينة غزة', 'حي الرمال', 31.50000000, 34.47000000, '09:00:00', '23:00:00', 'north', 1, 0.00, NULL, NULL, 'approved', 0.00, 0, 12.00, '2026-03-18 22:00:21', '2026-04-21 01:07:20'),
('57e1966b-294a-11f1-9448-e66239f31cb0', '4f792c75-4a65-45a7-a0ef-d282acd83d87', 'فتح الله ماركت', 'store', NULL, 'https://appforyou.store/backendOrder/upload/vendors/69da809780c01_1775927447.png', 'https://appforyou.store/backendOrder/upload/vendors/69da80a1b235c_1775927457.png', '01003600090', 'gaza', 'مدينة غزة', 'حي الرمال', 31.50000000, 34.47000000, '09:00:00', '08:55:00', 'north', 1, 0.00, '30', NULL, 'approved', 5.00, 2, 12.00, '2026-03-26 19:31:10', '2026-04-21 01:07:20'),
('5de1f9b7-3d21-11f1-9448-e66239f31cb0', '68d947ef-fcec-426a-8316-155666848619', 'North food', 'restaurant', NULL, NULL, NULL, '01003649991', 'مدينة غزة - حي الزيتون', 'مدينة غزة', 'حي الزيتون', 31.50000000, 34.47000000, NULL, NULL, 'north', 1, 0.00, NULL, NULL, 'approved', 5.00, 2, 12.00, '2026-04-21 04:28:14', '2026-04-21 18:46:06'),
('6255f135-3d97-11f1-9448-e66239f31cb0', '6255a20b-3d97-11f1-9448-e66239f31cb0', 'south', 'restaurant', NULL, NULL, NULL, '01208903650', 'مدينة خانيونس - حي الكتيبة', 'مدينة خانيونس', 'حي الكتيبة', 31.50000000, 34.47000000, NULL, NULL, 'south', 1, 0.00, NULL, NULL, 'approved', 0.00, 0, 12.00, '2026-04-21 18:33:02', '2026-04-21 18:33:02'),
('95a07fca-231c-11f1-b82d-97044e7d8834', 'cf2239d2-2dd7-4116-abd3-1f24d48e8fe7', 'سوق', 'store', NULL, 'https://appforyou.store/backendOrder/upload/vendors/69da8340b1087_1775928128.jpg', 'https://appforyou.store/backendOrder/upload/vendors/69da833bd3d53_1775928123.jpg', '01003640060', 'gaza', 'مدينة غزة', 'حي الرمال', 31.50000000, 34.47000000, '09:00:00', '23:00:00', 'north', 1, 0.00, NULL, NULL, 'approved', 0.00, 0, 12.00, '2026-03-18 22:47:04', '2026-04-21 01:07:20'),
('be9dd1ee-2efe-11f1-9448-e66239f31cb0', 'be9db6e5-2efe-11f1-9448-e66239f31cb0', 'كرم الشام', 'restaurant', NULL, 'https://appforyou.store/backendOrder/upload/vendors/69da836f250b5_1775928175.jpg', 'https://appforyou.store/backendOrder/upload/vendors/69da8379c7858_1775928185.jpg', '01003690083', 'North of gaza', 'مدينة غزة', 'حي الرمال', 31.50000000, 34.47000000, '09:00:00', '23:00:00', 'north', 1, 0.00, '15', NULL, 'approved', 0.00, 0, 12.00, '2026-04-03 01:45:07', '2026-04-21 01:07:20'),
('e4a9a3cd-26f4-11f1-abf9-e1b0d104a220', 'b8175b08-34e6-4f5d-87d3-4c777677db33', 'سوق غزة', 'store', NULL, 'https://appforyou.store/backendOrder/upload/vendors/69da842881144_1775928360.jpg', 'https://appforyou.store/backendOrder/upload/vendors/69da842348fc1_1775928355.jpg', '01003690030', 'North of gaza', 'مدينة غزة', 'حي الرمال', 31.50000000, 34.47000000, '09:00:00', '23:00:00', 'north', 1, 0.00, NULL, NULL, 'approved', 0.00, 0, 12.00, '2026-03-23 20:14:27', '2026-04-21 01:07:20');

-- --------------------------------------------------------

--
-- Stand-in structure for view `view_admin_orders`
-- (See below for the actual view)
--
CREATE TABLE `view_admin_orders` (
`id` char(36)
,`order_number` varchar(20)
,`status` enum('pending','admin_confirmed','sent_to_vendor','vendor_accepted','preparing','ready_for_pickup','out_for_delivery','delivered','cancelled')
,`branch_region` enum('north','south')
,`delivery_address` text
,`delivery_city` varchar(100)
,`delivery_neighborhood` varchar(100)
,`delivery_zone_from` varchar(20)
,`delivery_zone_to` varchar(20)
,`subtotal` decimal(10,2)
,`delivery_fee` decimal(8,2)
,`multi_vendor_surcharge` decimal(8,2)
,`total_amount` decimal(10,2)
,`platform_fee` decimal(8,2)
,`fee_model_used` enum('flat_per_order','percentage')
,`payment_method` enum('cash_on_delivery','bank_transfer')
,`payment_status` enum('pending','collected','failed')
,`customer_notes` text
,`cancellation_reason` text
,`confirmed_at` datetime
,`delivered_at` datetime
,`created_at` datetime
,`customer_name` varchar(100)
,`customer_phone` varchar(20)
,`admin_name` varchar(100)
,`vendor_count` bigint(21)
);

-- --------------------------------------------------------

--
-- Stand-in structure for view `view_earnings_daily_summary`
-- (See below for the actual view)
--
CREATE TABLE `view_earnings_daily_summary` (
`earning_date` date
,`total_orders` bigint(21)
,`total_sa_earned` decimal(31,2)
,`total_service_fees` decimal(30,2)
,`total_commissions` decimal(30,2)
,`total_delivery_fees` decimal(30,2)
,`total_vendor_payouts` decimal(32,2)
,`total_collected` decimal(31,2)
,`total_pending_collection` decimal(31,2)
,`first_order_at` datetime
,`last_order_at` datetime
);

-- --------------------------------------------------------

--
-- Stand-in structure for view `view_earnings_ledger`
-- (See below for the actual view)
--
CREATE TABLE `view_earnings_ledger` (
`id` char(36)
,`order_id` char(36)
,`order_number` varchar(20)
,`vendor_id` char(36)
,`order_total` decimal(10,2)
,`vendor_subtotal` decimal(10,2)
,`platform_fee` decimal(8,2)
,`service_fee_earned` decimal(8,2)
,`commission_earned` decimal(8,2)
,`commission_rate_snapshot` decimal(5,2)
,`delivery_fee_earned` decimal(8,2)
,`vendor_payout` decimal(10,2)
,`fee_model` enum('flat_per_order','percentage')
,`fee_value_snapshot` decimal(8,2)
,`is_collected` tinyint(1)
,`collected_at` datetime
,`earned_at` datetime
,`payment_method` enum('cash_on_delivery','bank_transfer')
,`branch_region` enum('north','south')
,`order_delivery_fee` decimal(8,2)
,`order_service_fee` decimal(8,2)
,`delivered_at` datetime
,`customer_name` varchar(100)
,`customer_phone` varchar(20)
,`vendor_name` varchar(150)
,`vendor_count` bigint(21)
);

-- --------------------------------------------------------

--
-- Stand-in structure for view `view_order_detail`
-- (See below for the actual view)
--
CREATE TABLE `view_order_detail` (
`order_id` char(36)
,`order_number` varchar(20)
,`order_status` enum('pending','admin_confirmed','sent_to_vendor','vendor_accepted','preparing','ready_for_pickup','out_for_delivery','delivered','cancelled')
,`branch_region` enum('north','south')
,`delivery_address` text
,`delivery_latitude` decimal(10,8)
,`delivery_longitude` decimal(11,8)
,`delivery_city` varchar(100)
,`delivery_neighborhood` varchar(100)
,`delivery_zone_from` varchar(20)
,`delivery_zone_to` varchar(20)
,`subtotal` decimal(10,2)
,`delivery_fee` decimal(8,2)
,`multi_vendor_surcharge` decimal(8,2)
,`service_fee` decimal(8,2)
,`total_amount` decimal(10,2)
,`platform_fee` decimal(8,2)
,`fee_model_used` enum('flat_per_order','percentage')
,`payment_method` enum('cash_on_delivery','bank_transfer')
,`payment_status` enum('pending','collected','failed')
,`customer_notes` text
,`cancellation_reason` text
,`confirmed_at` datetime
,`delivered_at` datetime
,`created_at` datetime
,`customer_id` char(36)
,`customer_name` varchar(100)
,`customer_phone` varchar(20)
,`customer_saved_address` text
,`order_vendor_id` char(36)
,`vendor_id` char(36)
,`vendor_subtotal` decimal(10,2)
,`vendor_notes` text
,`vendor_delivery_fee` decimal(8,2)
,`vendor_net_amount` decimal(10,2)
,`vendor_status` enum('sent_to_vendor','vendor_accepted','preparing','ready_for_pickup','out_for_delivery','delivered','cancelled')
,`accepted_at` datetime
,`ready_at` datetime
,`vendor_name` varchar(150)
,`vendor_phone` varchar(20)
,`vendor_address` text
,`vendor_type` enum('restaurant','store','cafe')
);

-- --------------------------------------------------------

--
-- Stand-in structure for view `view_order_stats`
-- (See below for the actual view)
--
CREATE TABLE `view_order_stats` (
`id` char(36)
,`status` enum('pending','admin_confirmed','sent_to_vendor','vendor_accepted','preparing','ready_for_pickup','out_for_delivery','delivered','cancelled')
,`total_amount` decimal(10,2)
,`platform_fee` decimal(8,2)
,`branch_region` enum('north','south')
,`payment_method` enum('cash_on_delivery','bank_transfer')
,`payment_status` enum('pending','collected','failed')
,`created_at` datetime
,`delivered_at` datetime
,`order_date` date
);

-- --------------------------------------------------------

--
-- Stand-in structure for view `view_vendor_list`
-- (See below for the actual view)
--
CREATE TABLE `view_vendor_list` (
`id` char(36)
,`vendor_name` varchar(150)
,`type` enum('restaurant','store','cafe')
,`status` enum('pending','approved','suspended','rejected')
,`vendor_phone` varchar(20)
,`address` text
,`rating` decimal(3,2)
,`total_ratings` int(10) unsigned
,`commission_rate` decimal(5,2)
,`opening_time` time
,`closing_time` time
,`logo_url` text
,`cover_url` text
,`registered_at` datetime
,`user_id` char(36)
,`owner_name` varchar(100)
,`owner_email` varchar(150)
,`owner_phone` varchar(20)
,`account_active` tinyint(1)
,`completed_orders` bigint(21)
);

-- --------------------------------------------------------

--
-- Stand-in structure for view `view_vendor_orders`
-- (See below for the actual view)
--
CREATE TABLE `view_vendor_orders` (
`order_vendor_id` char(36)
,`order_id` char(36)
,`vendor_id` char(36)
,`subtotal` decimal(10,2)
,`vendor_notes` text
,`vendor_net_amount` decimal(10,2)
,`vendor_status` enum('sent_to_vendor','vendor_accepted','preparing','ready_for_pickup','out_for_delivery','delivered','cancelled')
,`accepted_at` datetime
,`ready_at` datetime
,`created_at` datetime
,`order_number` varchar(20)
,`order_status` enum('pending','admin_confirmed','sent_to_vendor','vendor_accepted','preparing','ready_for_pickup','out_for_delivery','delivered','cancelled')
,`payment_method` enum('cash_on_delivery','bank_transfer')
,`delivery_address` text
,`order_placed_at` datetime
,`customer_name` varchar(100)
,`customer_phone` varchar(20)
);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `activity_logs`
--
ALTER TABLE `activity_logs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `activity_logs_user_id_idx` (`user_id`),
  ADD KEY `activity_logs_action_idx` (`action`),
  ADD KEY `activity_logs_created_idx` (`created_at`);

--
-- Indexes for table `admin_sessions`
--
ALTER TABLE `admin_sessions`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `admin_sessions_token_unique` (`token`),
  ADD KEY `admin_sessions_user_id_idx` (`user_id`),
  ADD KEY `admin_sessions_expires_idx` (`expires_at`);

--
-- Indexes for table `advertisements`
--
ALTER TABLE `advertisements`
  ADD PRIMARY KEY (`id`),
  ADD KEY `advertisements_active_sort_idx` (`is_active`,`sort_order`),
  ADD KEY `fk_advertisements_vendor` (`vendor_id`),
  ADD KEY `idx_ads_active_sort` (`is_active`,`sort_order`),
  ADD KEY `idx_ads_dates` (`starts_at`,`ends_at`);

--
-- Indexes for table `ad_clicks`
--
ALTER TABLE `ad_clicks`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_ad_clicks_ad` (`ad_id`),
  ADD KEY `idx_ad_clicks_time` (`clicked_at`),
  ADD KEY `idx_ad_clicks_user` (`user_id`);

--
-- Indexes for table `cart_items`
--
ALTER TABLE `cart_items`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `cart_items_user_item_unique` (`user_id`,`menu_item_id`),
  ADD KEY `cart_items_user_id_idx` (`user_id`),
  ADD KEY `fk_cart_items_menu_item` (`menu_item_id`);

--
-- Indexes for table `customer_addresses`
--
ALTER TABLE `customer_addresses`
  ADD PRIMARY KEY (`id`),
  ADD KEY `customer_addresses_user_id_idx` (`user_id`);

--
-- Indexes for table `customer_profiles`
--
ALTER TABLE `customer_profiles`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `customer_profiles_user_id_unique` (`user_id`);

--
-- Indexes for table `delivery_zones`
--
ALTER TABLE `delivery_zones`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `delivery_zone_pricing`
--
ALTER TABLE `delivery_zone_pricing`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_route` (`from_zone`,`to_zone`);

--
-- Indexes for table `email_verifications`
--
ALTER TABLE `email_verifications`
  ADD PRIMARY KEY (`id`),
  ADD KEY `email_verifications_email_type_idx` (`email`,`type`),
  ADD KEY `email_verifications_expires_idx` (`expires_at`);

--
-- Indexes for table `favorites`
--
ALTER TABLE `favorites`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_user_favorite` (`user_id`,`type`,`target_id`),
  ADD KEY `idx_user_favorites` (`user_id`,`type`);

--
-- Indexes for table `fcm_tokens`
--
ALTER TABLE `fcm_tokens`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uk_user_token` (`user_id`,`token`(255));

--
-- Indexes for table `fee_config`
--
ALTER TABLE `fee_config`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_fee_config_updated_by` (`updated_by`);

--
-- Indexes for table `global_categories`
--
ALTER TABLE `global_categories`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uk_global_cat_name` (`name`);

--
-- Indexes for table `menu_categories`
--
ALTER TABLE `menu_categories`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uk_vendor_global_cat` (`vendor_id`,`global_category_id`),
  ADD KEY `menu_categories_vendor_id_idx` (`vendor_id`),
  ADD KEY `idx_menu_categories_vendor_active` (`vendor_id`,`is_active`,`sort_order`),
  ADD KEY `fk_menu_cat_global` (`global_category_id`);

--
-- Indexes for table `menu_items`
--
ALTER TABLE `menu_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `menu_items_vendor_available_idx` (`vendor_id`,`is_available`),
  ADD KEY `menu_items_category_id_idx` (`category_id`),
  ADD KEY `idx_menu_items_vendor_available` (`vendor_id`,`is_available`),
  ADD KEY `idx_menu_items_category_sort` (`category_id`,`sort_order`),
  ADD KEY `idx_menu_items_vendor_category` (`vendor_id`,`category_id`);

--
-- Indexes for table `notifications`
--
ALTER TABLE `notifications`
  ADD PRIMARY KEY (`id`),
  ADD KEY `notifications_user_read_idx` (`user_id`,`is_read`),
  ADD KEY `notifications_created_idx` (`created_at`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `orders_order_number_unique` (`order_number`),
  ADD KEY `orders_customer_status_idx` (`customer_id`,`status`),
  ADD KEY `orders_status_created_idx` (`status`,`created_at`),
  ADD KEY `orders_branch_region_idx` (`branch_region`),
  ADD KEY `idx_orders_customer_created` (`customer_id`,`created_at` DESC),
  ADD KEY `idx_orders_status_created` (`status`,`created_at` DESC),
  ADD KEY `idx_orders_branch_status` (`branch_region`,`status`),
  ADD KEY `idx_orders_admin_id` (`admin_id`),
  ADD KEY `idx_orders_payment_status` (`payment_status`),
  ADD KEY `idx_orders_delivered_at` (`delivered_at`);

--
-- Indexes for table `order_items`
--
ALTER TABLE `order_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `order_items_order_vendor_id_idx` (`order_vendor_id`),
  ADD KEY `idx_order_items_order_vendor` (`order_vendor_id`),
  ADD KEY `idx_order_items_menu_item` (`menu_item_id`);

--
-- Indexes for table `order_status_history`
--
ALTER TABLE `order_status_history`
  ADD PRIMARY KEY (`id`),
  ADD KEY `order_status_history_order_created_idx` (`order_id`,`created_at`),
  ADD KEY `fk_status_history_changed_by` (`changed_by`),
  ADD KEY `idx_status_history_order_created` (`order_id`,`created_at`);

--
-- Indexes for table `order_vendors`
--
ALTER TABLE `order_vendors`
  ADD PRIMARY KEY (`id`),
  ADD KEY `order_vendors_order_vendor_idx` (`order_id`,`vendor_id`),
  ADD KEY `order_vendors_vendor_status_idx` (`vendor_id`,`status`),
  ADD KEY `idx_order_vendors_vendor_status` (`vendor_id`,`status`),
  ADD KEY `idx_order_vendors_order_id` (`order_id`);

--
-- Indexes for table `platform_earnings`
--
ALTER TABLE `platform_earnings`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `platform_earnings_order_vendor_unique` (`order_id`,`vendor_id`),
  ADD KEY `platform_earnings_earned_collected_idx` (`earned_at`,`is_collected`),
  ADD KEY `idx_earnings_earned_at` (`earned_at` DESC),
  ADD KEY `idx_earnings_collected` (`is_collected`,`earned_at`),
  ADD KEY `idx_earnings_vendor` (`vendor_id`);

--
-- Indexes for table `ratings`
--
ALTER TABLE `ratings`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `ratings_order_customer_vendor_unique` (`order_id`,`customer_id`,`vendor_id`),
  ADD KEY `ratings_vendor_id_idx` (`vendor_id`),
  ADD KEY `fk_ratings_customer` (`customer_id`),
  ADD KEY `idx_ratings_vendor_created` (`vendor_id`,`created_at` DESC),
  ADD KEY `idx_ratings_order_customer` (`order_id`,`customer_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_phone_unique` (`phone`),
  ADD UNIQUE KEY `users_email_unique` (`email`),
  ADD KEY `idx_users_phone` (`phone`),
  ADD KEY `idx_users_email` (`email`),
  ADD KEY `idx_users_role_active` (`role`,`is_active`),
  ADD KEY `idx_users_verification_token` (`email_verification_token`);

--
-- Indexes for table `vendors`
--
ALTER TABLE `vendors`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `vendors_user_id_unique` (`user_id`),
  ADD KEY `vendors_status_type_idx` (`status`,`type`),
  ADD KEY `idx_vendors_type_status` (`type`,`status`),
  ADD KEY `idx_vendors_status` (`status`),
  ADD KEY `idx_vendors_rating` (`rating` DESC);

-- --------------------------------------------------------

--
-- Structure for view `view_admin_orders`
--
DROP TABLE IF EXISTS `view_admin_orders`;

CREATE ALGORITHM=UNDEFINED DEFINER=`u595508539_orderuser`@`127.0.0.1` SQL SECURITY DEFINER VIEW `view_admin_orders`  AS SELECT `o`.`id` AS `id`, `o`.`order_number` AS `order_number`, `o`.`status` AS `status`, `o`.`branch_region` AS `branch_region`, `o`.`delivery_address` AS `delivery_address`, `o`.`delivery_city` AS `delivery_city`, `o`.`delivery_neighborhood` AS `delivery_neighborhood`, `o`.`delivery_zone_from` AS `delivery_zone_from`, `o`.`delivery_zone_to` AS `delivery_zone_to`, `o`.`subtotal` AS `subtotal`, `o`.`delivery_fee` AS `delivery_fee`, `o`.`multi_vendor_surcharge` AS `multi_vendor_surcharge`, `o`.`total_amount` AS `total_amount`, `o`.`platform_fee` AS `platform_fee`, `o`.`fee_model_used` AS `fee_model_used`, `o`.`payment_method` AS `payment_method`, `o`.`payment_status` AS `payment_status`, `o`.`customer_notes` AS `customer_notes`, `o`.`cancellation_reason` AS `cancellation_reason`, `o`.`confirmed_at` AS `confirmed_at`, `o`.`delivered_at` AS `delivered_at`, `o`.`created_at` AS `created_at`, `u`.`name` AS `customer_name`, `u`.`phone` AS `customer_phone`, `a`.`name` AS `admin_name`, (select count(0) from `order_vendors` `ov` where `ov`.`order_id` = `o`.`id`) AS `vendor_count` FROM ((`orders` `o` join `users` `u` on(`o`.`customer_id` = `u`.`id`)) left join `users` `a` on(`o`.`admin_id` = `a`.`id`)) ;

-- --------------------------------------------------------

--
-- Structure for view `view_earnings_daily_summary`
--
DROP TABLE IF EXISTS `view_earnings_daily_summary`;

CREATE ALGORITHM=UNDEFINED DEFINER=`u595508539_orderuser`@`127.0.0.1` SQL SECURITY DEFINER VIEW `view_earnings_daily_summary`  AS SELECT cast(`pe`.`earned_at` as date) AS `earning_date`, count(distinct `pe`.`order_id`) AS `total_orders`, sum(`pe`.`service_fee_earned` + `pe`.`commission_earned`) AS `total_sa_earned`, sum(`pe`.`service_fee_earned`) AS `total_service_fees`, sum(`pe`.`commission_earned`) AS `total_commissions`, sum(`pe`.`delivery_fee_earned`) AS `total_delivery_fees`, sum(`pe`.`vendor_payout`) AS `total_vendor_payouts`, sum(case when `pe`.`is_collected` = 1 then `pe`.`service_fee_earned` + `pe`.`commission_earned` else 0 end) AS `total_collected`, sum(case when `pe`.`is_collected` = 0 then `pe`.`service_fee_earned` + `pe`.`commission_earned` else 0 end) AS `total_pending_collection`, min(`pe`.`earned_at`) AS `first_order_at`, max(`pe`.`earned_at`) AS `last_order_at` FROM `platform_earnings` AS `pe` GROUP BY cast(`pe`.`earned_at` as date) ORDER BY cast(`pe`.`earned_at` as date) DESC ;

-- --------------------------------------------------------

--
-- Structure for view `view_earnings_ledger`
--
DROP TABLE IF EXISTS `view_earnings_ledger`;

CREATE ALGORITHM=UNDEFINED DEFINER=`u595508539_orderuser`@`127.0.0.1` SQL SECURITY DEFINER VIEW `view_earnings_ledger`  AS SELECT `pe`.`id` AS `id`, `pe`.`order_id` AS `order_id`, `pe`.`order_number` AS `order_number`, `pe`.`vendor_id` AS `vendor_id`, `pe`.`order_total` AS `order_total`, `pe`.`vendor_subtotal` AS `vendor_subtotal`, `pe`.`platform_fee` AS `platform_fee`, `pe`.`service_fee_earned` AS `service_fee_earned`, `pe`.`commission_earned` AS `commission_earned`, `pe`.`commission_rate_snapshot` AS `commission_rate_snapshot`, `pe`.`delivery_fee_earned` AS `delivery_fee_earned`, `pe`.`vendor_payout` AS `vendor_payout`, `pe`.`fee_model` AS `fee_model`, `pe`.`fee_value_snapshot` AS `fee_value_snapshot`, `pe`.`is_collected` AS `is_collected`, `pe`.`collected_at` AS `collected_at`, `pe`.`earned_at` AS `earned_at`, `o`.`payment_method` AS `payment_method`, `o`.`branch_region` AS `branch_region`, `o`.`delivery_fee` AS `order_delivery_fee`, `o`.`service_fee` AS `order_service_fee`, `o`.`delivered_at` AS `delivered_at`, `u`.`name` AS `customer_name`, `u`.`phone` AS `customer_phone`, `v`.`name` AS `vendor_name`, (select count(0) from `order_vendors` `ov` where `ov`.`order_id` = `o`.`id`) AS `vendor_count` FROM (((`platform_earnings` `pe` join `orders` `o` on(`pe`.`order_id` = `o`.`id`)) join `users` `u` on(`o`.`customer_id` = `u`.`id`)) left join `vendors` `v` on(`pe`.`vendor_id` = `v`.`id`)) ;

-- --------------------------------------------------------

--
-- Structure for view `view_order_detail`
--
DROP TABLE IF EXISTS `view_order_detail`;

CREATE ALGORITHM=UNDEFINED DEFINER=`u595508539_orderuser`@`127.0.0.1` SQL SECURITY DEFINER VIEW `view_order_detail`  AS SELECT `o`.`id` AS `order_id`, `o`.`order_number` AS `order_number`, `o`.`status` AS `order_status`, `o`.`branch_region` AS `branch_region`, `o`.`delivery_address` AS `delivery_address`, `o`.`delivery_latitude` AS `delivery_latitude`, `o`.`delivery_longitude` AS `delivery_longitude`, `o`.`delivery_city` AS `delivery_city`, `o`.`delivery_neighborhood` AS `delivery_neighborhood`, `o`.`delivery_zone_from` AS `delivery_zone_from`, `o`.`delivery_zone_to` AS `delivery_zone_to`, `o`.`subtotal` AS `subtotal`, `o`.`delivery_fee` AS `delivery_fee`, `o`.`multi_vendor_surcharge` AS `multi_vendor_surcharge`, `o`.`service_fee` AS `service_fee`, `o`.`total_amount` AS `total_amount`, `o`.`platform_fee` AS `platform_fee`, `o`.`fee_model_used` AS `fee_model_used`, `o`.`payment_method` AS `payment_method`, `o`.`payment_status` AS `payment_status`, `o`.`customer_notes` AS `customer_notes`, `o`.`cancellation_reason` AS `cancellation_reason`, `o`.`confirmed_at` AS `confirmed_at`, `o`.`delivered_at` AS `delivered_at`, `o`.`created_at` AS `created_at`, `u`.`id` AS `customer_id`, `u`.`name` AS `customer_name`, `u`.`phone` AS `customer_phone`, `u`.`address` AS `customer_saved_address`, `ov`.`id` AS `order_vendor_id`, `ov`.`vendor_id` AS `vendor_id`, `ov`.`subtotal` AS `vendor_subtotal`, `ov`.`vendor_notes` AS `vendor_notes`, `ov`.`vendor_delivery_fee` AS `vendor_delivery_fee`, `ov`.`vendor_net_amount` AS `vendor_net_amount`, `ov`.`status` AS `vendor_status`, `ov`.`accepted_at` AS `accepted_at`, `ov`.`ready_at` AS `ready_at`, `v`.`name` AS `vendor_name`, `v`.`phone` AS `vendor_phone`, `v`.`address` AS `vendor_address`, `v`.`type` AS `vendor_type` FROM (((`orders` `o` join `users` `u` on(`o`.`customer_id` = `u`.`id`)) join `order_vendors` `ov` on(`ov`.`order_id` = `o`.`id`)) join `vendors` `v` on(`ov`.`vendor_id` = `v`.`id`)) ;

-- --------------------------------------------------------

--
-- Structure for view `view_order_stats`
--
DROP TABLE IF EXISTS `view_order_stats`;

CREATE ALGORITHM=UNDEFINED DEFINER=`u595508539_orderuser`@`127.0.0.1` SQL SECURITY DEFINER VIEW `view_order_stats`  AS SELECT `o`.`id` AS `id`, `o`.`status` AS `status`, `o`.`total_amount` AS `total_amount`, `o`.`platform_fee` AS `platform_fee`, `o`.`branch_region` AS `branch_region`, `o`.`payment_method` AS `payment_method`, `o`.`payment_status` AS `payment_status`, `o`.`created_at` AS `created_at`, `o`.`delivered_at` AS `delivered_at`, cast(`o`.`created_at` as date) AS `order_date` FROM `orders` AS `o` ;

-- --------------------------------------------------------

--
-- Structure for view `view_vendor_list`
--
DROP TABLE IF EXISTS `view_vendor_list`;

CREATE ALGORITHM=UNDEFINED DEFINER=`u595508539_orderuser`@`127.0.0.1` SQL SECURITY DEFINER VIEW `view_vendor_list`  AS SELECT `v`.`id` AS `id`, `v`.`name` AS `vendor_name`, `v`.`type` AS `type`, `v`.`status` AS `status`, `v`.`phone` AS `vendor_phone`, `v`.`address` AS `address`, `v`.`rating` AS `rating`, `v`.`total_ratings` AS `total_ratings`, `v`.`commission_rate` AS `commission_rate`, `v`.`opening_time` AS `opening_time`, `v`.`closing_time` AS `closing_time`, `v`.`logo_url` AS `logo_url`, `v`.`cover_url` AS `cover_url`, `v`.`created_at` AS `registered_at`, `u`.`id` AS `user_id`, `u`.`name` AS `owner_name`, `u`.`email` AS `owner_email`, `u`.`phone` AS `owner_phone`, `u`.`is_active` AS `account_active`, (select count(0) from (`orders` `o` join `order_vendors` `ov` on(`ov`.`order_id` = `o`.`id`)) where `ov`.`vendor_id` = `v`.`id` and `o`.`status` = 'delivered') AS `completed_orders` FROM (`vendors` `v` join `users` `u` on(`v`.`user_id` = `u`.`id`)) ;

-- --------------------------------------------------------

--
-- Structure for view `view_vendor_orders`
--
DROP TABLE IF EXISTS `view_vendor_orders`;

CREATE ALGORITHM=UNDEFINED DEFINER=`u595508539_orderuser`@`127.0.0.1` SQL SECURITY DEFINER VIEW `view_vendor_orders`  AS SELECT `ov`.`id` AS `order_vendor_id`, `ov`.`order_id` AS `order_id`, `ov`.`vendor_id` AS `vendor_id`, `ov`.`subtotal` AS `subtotal`, `ov`.`vendor_notes` AS `vendor_notes`, `ov`.`vendor_net_amount` AS `vendor_net_amount`, `ov`.`status` AS `vendor_status`, `ov`.`accepted_at` AS `accepted_at`, `ov`.`ready_at` AS `ready_at`, `ov`.`created_at` AS `created_at`, `o`.`order_number` AS `order_number`, `o`.`status` AS `order_status`, `o`.`payment_method` AS `payment_method`, `o`.`delivery_address` AS `delivery_address`, `o`.`created_at` AS `order_placed_at`, `u`.`name` AS `customer_name`, `u`.`phone` AS `customer_phone` FROM ((`order_vendors` `ov` join `orders` `o` on(`ov`.`order_id` = `o`.`id`)) join `users` `u` on(`o`.`customer_id` = `u`.`id`)) ;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `activity_logs`
--
ALTER TABLE `activity_logs`
  ADD CONSTRAINT `fk_activity_logs_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `admin_sessions`
--
ALTER TABLE `admin_sessions`
  ADD CONSTRAINT `fk_admin_sessions_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `advertisements`
--
ALTER TABLE `advertisements`
  ADD CONSTRAINT `fk_advertisements_vendor` FOREIGN KEY (`vendor_id`) REFERENCES `vendors` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `ad_clicks`
--
ALTER TABLE `ad_clicks`
  ADD CONSTRAINT `fk_ad_clicks_ad` FOREIGN KEY (`ad_id`) REFERENCES `advertisements` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `cart_items`
--
ALTER TABLE `cart_items`
  ADD CONSTRAINT `fk_cart_items_menu_item` FOREIGN KEY (`menu_item_id`) REFERENCES `menu_items` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_cart_items_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `customer_addresses`
--
ALTER TABLE `customer_addresses`
  ADD CONSTRAINT `fk_customer_addresses_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `customer_profiles`
--
ALTER TABLE `customer_profiles`
  ADD CONSTRAINT `fk_customer_profiles_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `favorites`
--
ALTER TABLE `favorites`
  ADD CONSTRAINT `fk_favorites_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `fcm_tokens`
--
ALTER TABLE `fcm_tokens`
  ADD CONSTRAINT `fcm_tokens_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `fee_config`
--
ALTER TABLE `fee_config`
  ADD CONSTRAINT `fk_fee_config_updated_by` FOREIGN KEY (`updated_by`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `menu_categories`
--
ALTER TABLE `menu_categories`
  ADD CONSTRAINT `fk_menu_cat_global` FOREIGN KEY (`global_category_id`) REFERENCES `global_categories` (`id`),
  ADD CONSTRAINT `fk_menu_categories_vendor` FOREIGN KEY (`vendor_id`) REFERENCES `vendors` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `menu_items`
--
ALTER TABLE `menu_items`
  ADD CONSTRAINT `fk_menu_items_category` FOREIGN KEY (`category_id`) REFERENCES `menu_categories` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_menu_items_vendor` FOREIGN KEY (`vendor_id`) REFERENCES `vendors` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `notifications`
--
ALTER TABLE `notifications`
  ADD CONSTRAINT `fk_notifications_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `fk_orders_admin` FOREIGN KEY (`admin_id`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `fk_orders_customer` FOREIGN KEY (`customer_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `order_items`
--
ALTER TABLE `order_items`
  ADD CONSTRAINT `fk_order_items_menu_item` FOREIGN KEY (`menu_item_id`) REFERENCES `menu_items` (`id`),
  ADD CONSTRAINT `fk_order_items_order_vendor` FOREIGN KEY (`order_vendor_id`) REFERENCES `order_vendors` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `order_status_history`
--
ALTER TABLE `order_status_history`
  ADD CONSTRAINT `fk_status_history_changed_by` FOREIGN KEY (`changed_by`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `fk_status_history_order` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `order_vendors`
--
ALTER TABLE `order_vendors`
  ADD CONSTRAINT `fk_order_vendors_order` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_order_vendors_vendor` FOREIGN KEY (`vendor_id`) REFERENCES `vendors` (`id`);

--
-- Constraints for table `platform_earnings`
--
ALTER TABLE `platform_earnings`
  ADD CONSTRAINT `fk_platform_earnings_order` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`);

--
-- Constraints for table `ratings`
--
ALTER TABLE `ratings`
  ADD CONSTRAINT `fk_ratings_customer` FOREIGN KEY (`customer_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `fk_ratings_order` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`),
  ADD CONSTRAINT `fk_ratings_vendor` FOREIGN KEY (`vendor_id`) REFERENCES `vendors` (`id`);

--
-- Constraints for table `vendors`
--
ALTER TABLE `vendors`
  ADD CONSTRAINT `fk_vendors_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
