-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 05, 2025 at 03:30 PM
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
-- Database: `stafify_hirs`
--

-- --------------------------------------------------------

--
-- Table structure for table `cache`
--

CREATE TABLE `cache` (
  `key` varchar(255) NOT NULL,
  `value` mediumtext NOT NULL,
  `expiration` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `cache_locks`
--

CREATE TABLE `cache_locks` (
  `key` varchar(255) NOT NULL,
  `owner` varchar(255) NOT NULL,
  `expiration` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `company_branches`
--

CREATE TABLE `company_branches` (
  `branch_id` bigint(20) UNSIGNED NOT NULL,
  `company_id` bigint(20) UNSIGNED NOT NULL,
  `branch_location` varchar(255) NOT NULL,
  `branch_address` text NOT NULL,
  `branch_phone` varchar(50) NOT NULL,
  `is_headquarters` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `company_branches`
--

INSERT INTO `company_branches` (`branch_id`, `company_id`, `branch_location`, `branch_address`, `branch_phone`, `is_headquarters`, `created_at`, `updated_at`) VALUES
(1, 1, 'Headquarters', 'Greenwoods Park, 2F, Lot 17, Parcel 4, Rizal Hwy, CBD Area, Subic Bay Freeport Zone, 2200 Zambales', '(047) 603 0032', 1, '2025-04-23 23:27:34', '2025-04-23 23:27:34'),
(2, 1, 'BGC Office', 'Unit 88, High Street, Bonifacio Global City, Taguig City, Philippines', '+639177022610', 0, '2025-05-04 00:58:17', '2025-05-04 00:58:17'),
(3, 2, 'Headquarters', '23', '12345', 1, '2025-10-06 03:22:55', '2025-10-06 03:22:55');

-- --------------------------------------------------------

--
-- Table structure for table `company_profiles`
--

CREATE TABLE `company_profiles` (
  `company_id` bigint(20) UNSIGNED NOT NULL,
  `company_name` varchar(255) NOT NULL,
  `company_address` varchar(255) NOT NULL,
  `company_phone` varchar(255) NOT NULL,
  `company_email` varchar(255) NOT NULL,
  `company_logo` varchar(255) NOT NULL DEFAULT 'default-company-logo.png',
  `timezone` varchar(255) NOT NULL DEFAULT 'UTC',
  `week_start` int(11) NOT NULL DEFAULT 0 COMMENT '0=Sunday, 1=Monday, etc.',
  `year_type` varchar(255) NOT NULL DEFAULT 'calendar' COMMENT 'calendar or fiscal',
  `fiscal_start_month` int(11) DEFAULT NULL,
  `fiscal_start_day` int(11) DEFAULT NULL,
  `fiscal_end_month` int(11) DEFAULT NULL,
  `fiscal_end_day` int(11) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `company_profiles`
--

INSERT INTO `company_profiles` (`company_id`, `company_name`, `company_address`, `company_phone`, `company_email`, `company_logo`, `timezone`, `week_start`, `year_type`, `fiscal_start_month`, `fiscal_start_day`, `fiscal_end_month`, `fiscal_end_day`, `created_at`, `updated_at`) VALUES
(1, 'Stafify', 'Greenwoods Park, 2F, Lot 17, Parcel 4, Rizal Hwy, CBD Area, Subic Bay Freeport Zone, 2200 Zambales', '(047) 603 0032', 'stafify@gmail.com', 'default-company-logo.png', 'Asia/Manila', 1, 'fiscal', 1, 18, 12, 18, '2025-11-03 02:55:57', '2025-11-03 02:55:57'),
(2, 'CompanyTest153', '23', '12345', 'test153@gmail.com', 'default-company-logo.png', 'UTC', 0, 'calendar', NULL, NULL, NULL, NULL, '2025-11-03 02:55:57', '2025-11-03 02:55:57');

-- --------------------------------------------------------

--
-- Table structure for table `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `uuid` varchar(255) NOT NULL,
  `connection` text NOT NULL,
  `queue` text NOT NULL,
  `payload` longtext NOT NULL,
  `exception` longtext NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `hr_toolkit`
--

CREATE TABLE `hr_toolkit` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `sales_title` varchar(255) NOT NULL,
  `form_url` text DEFAULT NULL,
  `response_url` text DEFAULT NULL,
  `icon` varchar(255) NOT NULL DEFAULT 'communication.gif',
  `type` enum('Form','Sheet','Video','Slides','Folder','Form+Sheet','Website') NOT NULL,
  `is_approved` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `hr_toolkit`
--

INSERT INTO `hr_toolkit` (`id`, `user_id`, `sales_title`, `form_url`, `response_url`, `icon`, `type`, `is_approved`, `created_at`, `updated_at`) VALUES
(1, 1, 'E-Settlement Account Enrollment', NULL, NULL, 'Settlement.gif', 'Form', 1, '2025-11-03 02:55:57', '2025-11-03 02:55:57'),
(2, 1, 'E-DISC Personality Test', NULL, NULL, 'Personality.gif', 'Form', 1, '2025-11-03 02:55:57', '2025-11-03 02:55:57'),
(3, 1, 'E-Acknowledgement of Company Policy', NULL, NULL, 'Policy.gif', 'Form', 1, '2025-11-03 02:55:57', '2025-11-03 02:55:57'),
(4, 1, 'E-ID Enrollment', NULL, NULL, 'Register.gif', 'Form', 1, '2025-11-03 02:55:57', '2025-11-03 02:55:57'),
(5, 1, 'E-Incident Report', NULL, NULL, 'Write.gif', 'Form', 1, '2025-11-03 02:55:57', '2025-11-03 02:55:57'),
(6, 1, 'E-NTE Request', NULL, NULL, 'Request.gif', 'Form', 1, '2025-11-03 02:55:57', '2025-11-03 02:55:57'),
(7, 1, 'E-NTE Submission', NULL, NULL, 'Write.gif', 'Form', 1, '2025-11-03 02:55:57', '2025-11-03 02:55:57'),
(8, 1, 'E-Admin Hearing', NULL, NULL, 'consultation.gif', 'Form', 1, '2025-11-03 02:55:57', '2025-11-03 02:55:57'),
(9, 1, 'E-Notice of Decision', NULL, NULL, 'Decision.gif', 'Form', 1, '2025-11-03 02:55:57', '2025-11-03 02:55:57'),
(10, 1, 'E-Certification', NULL, NULL, 'Star.gif', 'Form', 1, '2025-11-03 02:55:57', '2025-11-03 02:55:57');

-- --------------------------------------------------------

--
-- Table structure for table `jobs`
--

CREATE TABLE `jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `queue` varchar(255) NOT NULL,
  `payload` longtext NOT NULL,
  `attempts` tinyint(3) UNSIGNED NOT NULL,
  `reserved_at` int(10) UNSIGNED DEFAULT NULL,
  `available_at` int(10) UNSIGNED NOT NULL,
  `created_at` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `job_batches`
--

CREATE TABLE `job_batches` (
  `id` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `total_jobs` int(11) NOT NULL,
  `pending_jobs` int(11) NOT NULL,
  `failed_jobs` int(11) NOT NULL,
  `failed_job_ids` longtext NOT NULL,
  `options` mediumtext DEFAULT NULL,
  `cancelled_at` int(11) DEFAULT NULL,
  `created_at` int(11) NOT NULL,
  `finished_at` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `leave_requests`
--

CREATE TABLE `leave_requests` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `employee_id` varchar(50) DEFAULT NULL,
  `employee_email` varchar(100) NOT NULL,
  `employee_name` varchar(100) NOT NULL,
  `leave_type` varchar(50) NOT NULL,
  `start_date` date NOT NULL,
  `end_date` date NOT NULL,
  `reason` text DEFAULT NULL,
  `status` varchar(20) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `legal_toolkit`
--

CREATE TABLE `legal_toolkit` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `sales_title` varchar(255) NOT NULL,
  `form_url` text DEFAULT NULL,
  `response_url` text DEFAULT NULL,
  `icon` varchar(255) NOT NULL,
  `type` varchar(255) NOT NULL,
  `is_approved` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `legal_toolkit`
--

INSERT INTO `legal_toolkit` (`id`, `user_id`, `sales_title`, `form_url`, `response_url`, `icon`, `type`, `is_approved`, `created_at`, `updated_at`) VALUES
(1, 1, 'E-Employment Contract', '#', '#', 'Write.gif', 'Form+Sheet', 1, '2025-11-03 02:55:57', '2025-11-03 02:55:57'),
(2, 1, 'E-Service Agreement', '#', '#', 'Settlement.gif', 'Form+Sheet', 1, '2025-11-03 02:55:57', '2025-11-03 02:55:57'),
(3, 1, 'E-NDA', '#', '#', 'Policy.gif', 'Form+Sheet', 1, '2025-11-03 02:55:57', '2025-11-03 02:55:57');

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(255) NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '0001_01_01_000000_create_users_table', 1),
(2, '0001_01_01_000001_create_cache_table', 1),
(3, '0001_01_01_000002_create_jobs_table', 1),
(4, '2025_10_20_095141_create_company_profiles_table', 1),
(5, '2025_10_20_095146_create_company_branches_table', 1),
(6, '2025_10_20_095153_create_stafify_users_table', 1),
(7, '2025_10_20_095157_create_stafify_departments_table', 1),
(8, '2025_10_20_095201_create_stafify_positions_table', 1),
(9, '2025_10_20_095204_create_stafify_shifts_table', 1),
(10, '2025_10_20_095210_create_stafify_time_tracking_table', 1),
(11, '2025_10_20_095215_create_stafify_overtime_table', 1),
(12, '2025_10_20_095219_create_stafify_user_rates_table', 1),
(13, '2025_10_20_095223_create_stafify_deminimis_benefits_table', 1),
(14, '2025_10_20_095231_create_stafify_fringe_benefits_table', 1),
(15, '2025_10_20_095235_create_stafify_settings_table', 1),
(16, '2025_10_20_095238_create_stafify_events_table', 1),
(17, '2025_10_20_095242_create_sales_toolkit_table', 1),
(18, '2025_10_20_095246_create_performance_evaluations_table', 1),
(19, '2025_10_20_095250_create_leave_requests_table', 1),
(20, '2025_10_20_095253_create_payroll_settings_table', 1),
(21, '2025_10_20_095257_create_stafify_payrolls_table', 1),
(22, '2025_10_20_095301_create_stafify_cmd_settings_table', 1),
(23, '2025_10_20_150323_add_company_to_stafify_settings_table', 1),
(24, '2025_10_20_204433_create_talent_management_resources_table', 1),
(25, '2025_10_20_205108_create_hr_toolkit_table', 1),
(26, '2025_10_23_112755_create_user_deminimis_benefits_table', 1),
(27, '2025_10_23_142730_create_legal_toolkit_table', 1),
(28, '2025_10_25_135428_create_talent_toolkit_table', 1),
(29, '2025_11_03_150744_add_break_fields_to_stafify_shifts_table', 2),
(30, '2025_11_05_141041_add_multiple_forms_sheets_type_to_talent_toolkit', 3);

-- --------------------------------------------------------

--
-- Table structure for table `password_reset_tokens`
--

CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `payroll_settings`
--

CREATE TABLE `payroll_settings` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `company_id` bigint(20) UNSIGNED NOT NULL,
  `frequency` int(11) NOT NULL,
  `disbursement` int(11) NOT NULL,
  `deduction_schedule` varchar(20) NOT NULL,
  `benefits_url` text NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `payroll_settings`
--

INSERT INTO `payroll_settings` (`id`, `company_id`, `frequency`, `disbursement`, `deduction_schedule`, `benefits_url`, `created_at`, `updated_at`) VALUES
(1, 1, 2, 1, 'weekly', 'https://docs.google.com/spreadsheets/d/1l3n2bxlc3SUx-zlhN6VfwLQgyBWM2q6ccFBntWu5dkU/edit?gid=0#gid=0', '2025-11-03 02:55:57', '2025-10-08 23:20:27');

-- --------------------------------------------------------

--
-- Table structure for table `performance_evaluations`
--

CREATE TABLE `performance_evaluations` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `evaluation_date` date NOT NULL,
  `evaluator_name` varchar(255) NOT NULL,
  `evaluation_type` varchar(100) NOT NULL,
  `remarks` text DEFAULT NULL,
  `overall_score` int(11) NOT NULL DEFAULT 0,
  `job_knowledge` int(11) NOT NULL DEFAULT 0,
  `productivity` int(11) NOT NULL DEFAULT 0,
  `work_quality` int(11) NOT NULL DEFAULT 0,
  `technical_skills` int(11) NOT NULL DEFAULT 0,
  `work_consistency` int(11) NOT NULL DEFAULT 0,
  `enthusiasm` int(11) NOT NULL DEFAULT 0,
  `cooperation` int(11) NOT NULL DEFAULT 0,
  `attitude` int(11) NOT NULL DEFAULT 0,
  `initiative` int(11) NOT NULL DEFAULT 0,
  `work_relations` int(11) NOT NULL DEFAULT 0,
  `creativity` int(11) NOT NULL DEFAULT 0,
  `punctuality` int(11) NOT NULL DEFAULT 0,
  `attendance` int(11) NOT NULL DEFAULT 0,
  `dependability` int(11) NOT NULL DEFAULT 0,
  `written_comm` int(11) NOT NULL DEFAULT 0,
  `verbal_comm` int(11) NOT NULL DEFAULT 0,
  `appearance` int(11) NOT NULL DEFAULT 0,
  `uniform` int(11) NOT NULL DEFAULT 0,
  `personal_hygiene` int(11) NOT NULL DEFAULT 0,
  `tidiness` int(11) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `sales_toolkit`
--

CREATE TABLE `sales_toolkit` (
  `sales_id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `sales_title` varchar(255) NOT NULL,
  `form_url` text DEFAULT NULL,
  `response_url` text DEFAULT NULL,
  `icon` varchar(255) NOT NULL DEFAULT 'communication.gif',
  `type` enum('Form','Sheet','Video','Slides','Folder','Form+Sheet','Website') NOT NULL,
  `is_approved` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `sales_toolkit`
--

INSERT INTO `sales_toolkit` (`sales_id`, `user_id`, `sales_title`, `form_url`, `response_url`, `icon`, `type`, `is_approved`, `created_at`, `updated_at`) VALUES
(1, 2, 'Withholding Tax Table', '', 'https://docs.google.com/spreadsheets/d/1jnz_eMobmVgGYMCAnamhdxZ4GTQIO1yfBeVYV8WmQ3E/edit?gid=0#gid=0', 'Write.gif', 'Sheet', 1, '2025-08-18 04:57:39', '2025-08-18 05:04:39'),
(2, 2, 'Benefits and Tax Table', '', 'https://docs.google.com/spreadsheets/d/1ZCBUSXqKLLVGq2TETu7rKVavjoDszXfS-bW2Zk69tEU/edit?usp=sharing', 'Write.gif', 'Sheet', 1, '2025-08-18 05:02:28', '2025-08-18 05:04:41'),
(7, 2, 'SSS Website', 'https://www.sss.gov.ph/', '', 'Globe.gif', 'Website', 1, '2025-10-08 18:41:34', '2025-10-08 18:55:28'),
(8, 2, 'Philhealth Website', 'https://www.philhealth.gov.ph/', '', 'Globe.gif', 'Website', 1, '2025-10-08 18:59:10', '2025-10-08 18:59:51'),
(10, 2, 'Pag-IBIG Website', 'https://www.pagibigfund.gov.ph/', '', 'Cart.gif', 'Website', 1, '2025-10-08 19:04:14', '2025-10-08 21:09:44'),
(11, 2, 'BIR Excel Uploader', 'https://bir-excel-uploader.com/', '', 'Globe.gif', 'Website', 1, '2025-10-08 21:01:58', '2025-10-08 21:09:46'),
(12, 2, 'BIR ORUS', 'https://orus.bir.gov.ph/home', '', 'Register.gif', 'Website', 1, '2025-10-08 21:03:36', '2025-10-08 21:09:49'),
(13, 2, 'BIR eFPS', 'https://efps.bir.gov.ph/', '', 'Write.gif', 'Website', 1, '2025-10-08 21:05:21', '2025-10-08 21:09:51'),
(14, 2, 'BIR ePAY', 'https://www.bir.gov.ph/ePay', '', 'Settlement.gif', 'Website', 1, '2025-10-08 21:06:30', '2025-10-08 21:09:53'),
(15, 2, 'BIR eAFS', 'https://eafs.bir.gov.ph/eafs/', '', 'Request.gif', 'Website', 1, '2025-10-08 21:07:50', '2025-10-08 21:09:55'),
(16, 2, 'BIR Downloadables', 'https://www.bir.gov.ph/Downloadables', '', 'Folder.gif', 'Website', 1, '2025-10-08 21:09:06', '2025-10-08 21:09:56');

-- --------------------------------------------------------

--
-- Table structure for table `sessions`
--

CREATE TABLE `sessions` (
  `id` varchar(255) NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `payload` longtext NOT NULL,
  `last_activity` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `sessions`
--

INSERT INTO `sessions` (`id`, `user_id`, `ip_address`, `user_agent`, `payload`, `last_activity`) VALUES
('4EJcgX0YaCImOK1mqLd5ALuYPynce6I00ifXJ2Vw', 19, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoiRVUyWnlIZWcxVERQandtUUdyN2F3NThOamhPNlh6M1dQcTRaTFJURCI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MzY6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9wYXlvdXQtcmVwb3J0cyI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fXM6NTA6ImxvZ2luX3dlYl81OWJhMzZhZGRjMmIyZjk0MDE1ODBmMDE0YzdmNThlYTRlMzA5ODlkIjtpOjE5O30=', 1762185732),
('4uUUnqFiHNztjM4q5rGnk9BhVgpT5Oxo3T0xDmGR', 19, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoiSmRsU3gxdktoOEFuZDFtRTFHaUt2a3Q5TVBXMXVRYnhrbFp6RWgwZCI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MzU6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC90aW1lLXRyYWNraW5nIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo1MDoibG9naW5fd2ViXzU5YmEzNmFkZGMyYjJmOTQwMTU4MGYwMTRjN2Y1OGVhNGUzMDk4OWQiO2k6MTk7fQ==', 1762180266),
('seMJ6TtwBzyTMlDG3s5X129CsbFfL3SCCN4LNqm3', 19, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoiTEY1R3RsTTM4TG9EYWRESWZxdDJOTXJPMWVQVEI2R293N01PeE1OUyI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MzI6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9oci10b29sa2l0Ijt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo1MDoibG9naW5fd2ViXzU5YmEzNmFkZGMyYjJmOTQwMTU4MGYwMTRjN2Y1OGVhNGUzMDk4OWQiO2k6MTk7fQ==', 1762352995);

-- --------------------------------------------------------

--
-- Table structure for table `stafify_cmd_settings`
--

CREATE TABLE `stafify_cmd_settings` (
  `setting_id` bigint(20) UNSIGNED NOT NULL,
  `setting_key` varchar(50) NOT NULL,
  `setting_value` text DEFAULT NULL,
  `updated_by` bigint(20) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `stafify_deminimis_benefits`
--

CREATE TABLE `stafify_deminimis_benefits` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `cola` decimal(10,2) DEFAULT NULL,
  `rice_subsidy` decimal(10,2) DEFAULT NULL,
  `meal_allowance` decimal(10,2) DEFAULT NULL,
  `uniform_clothing` decimal(10,2) DEFAULT NULL,
  `laundry_allowance` decimal(10,2) DEFAULT NULL,
  `medical_allowance` decimal(10,2) DEFAULT NULL,
  `collective_bargaining_agreement` decimal(10,2) DEFAULT NULL,
  `total_non_taxable_13` decimal(10,2) DEFAULT NULL,
  `service_incentive_leave` decimal(10,2) DEFAULT NULL,
  `paid_time_off` decimal(10,2) DEFAULT NULL,
  `other_allowances` decimal(10,2) DEFAULT NULL,
  `total_non_taxable_benefits` decimal(10,2) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `stafify_departments`
--

CREATE TABLE `stafify_departments` (
  `department_id` bigint(20) UNSIGNED NOT NULL,
  `department_name` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `stafify_departments`
--

INSERT INTO `stafify_departments` (`department_id`, `department_name`) VALUES
(1, 'Management'),
(2, 'IT Department'),
(3, 'Sales');

-- --------------------------------------------------------

--
-- Table structure for table `stafify_events`
--

CREATE TABLE `stafify_events` (
  `event_id` bigint(20) UNSIGNED NOT NULL,
  `created_by` bigint(20) UNSIGNED NOT NULL,
  `event_title` varchar(255) NOT NULL,
  `event_date` date NOT NULL,
  `start_time` time NOT NULL,
  `end_time` time NOT NULL,
  `event_location` varchar(100) DEFAULT NULL,
  `event_type` varchar(50) DEFAULT NULL,
  `event_visibility` varchar(20) NOT NULL DEFAULT 'all',
  `event_description` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `stafify_fringe_benefits`
--

CREATE TABLE `stafify_fringe_benefits` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `hazard_pay` decimal(15,2) NOT NULL DEFAULT 0.00,
  `fixed_representation_allowance` decimal(15,2) NOT NULL DEFAULT 0.00,
  `fixed_transportation_allowance` decimal(15,2) NOT NULL DEFAULT 0.00,
  `fixed_housing_allowance` decimal(15,2) NOT NULL DEFAULT 0.00,
  `vehicle_allowance` decimal(15,2) NOT NULL DEFAULT 0.00,
  `educational_assistance` decimal(15,2) NOT NULL DEFAULT 0.00,
  `medical_assistance` decimal(15,2) NOT NULL DEFAULT 0.00,
  `insurance` decimal(15,2) NOT NULL DEFAULT 0.00,
  `membership` decimal(15,2) NOT NULL DEFAULT 0.00,
  `household_personnel` decimal(15,2) NOT NULL DEFAULT 0.00,
  `vacation_expense` decimal(15,2) NOT NULL DEFAULT 0.00,
  `travel_expense` decimal(15,2) NOT NULL DEFAULT 0.00,
  `commissions` decimal(15,2) NOT NULL DEFAULT 0.00,
  `profit_sharing` decimal(15,2) NOT NULL DEFAULT 0.00,
  `fees` decimal(15,2) NOT NULL DEFAULT 0.00,
  `total_taxable_13` decimal(15,2) NOT NULL DEFAULT 0.00,
  `other_taxable` decimal(15,2) NOT NULL DEFAULT 0.00,
  `total_taxable_benefits` decimal(15,2) NOT NULL DEFAULT 0.00,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `stafify_overtime`
--

CREATE TABLE `stafify_overtime` (
  `ot_id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `shift_id` bigint(20) UNSIGNED DEFAULT NULL,
  `requested_date` datetime NOT NULL DEFAULT current_timestamp(),
  `ot_date` date NOT NULL,
  `start_time` time NOT NULL,
  `end_time` time NOT NULL,
  `duration` decimal(5,2) NOT NULL,
  `reason` text DEFAULT NULL,
  `status` enum('pending','approved','rejected') NOT NULL DEFAULT 'pending',
  `approved_by` bigint(20) UNSIGNED DEFAULT NULL,
  `approved_date` datetime DEFAULT NULL,
  `approval_notes` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `stafify_payrolls`
--

CREATE TABLE `stafify_payrolls` (
  `id` varchar(50) NOT NULL,
  `template_id` varchar(50) NOT NULL,
  `name` varchar(40) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `stafify_payrolls`
--

INSERT INTO `stafify_payrolls` (`id`, `template_id`, `name`) VALUES
('1rQ08O3zw6vAXzrsP3SNyt1Vk-ByKlfYo', '13FWVbkg9VVuL4ZimN8istUAGPfdHOcxe', 'ISP'),
('1rQkUqepDGHCFVW0R05KB_j-OPkAkaSCb', '1XX7y4asO3_o45ACzon5wI1xfQ86DTcYR', 'Hybrid'),
('1tKGME3ZLE1ajLX0AY8zSoNoIhx-xc0M9', '12sQe2DN_GMIxHEMBjahhGNGd0OomNFf5', 'EE');

-- --------------------------------------------------------

--
-- Table structure for table `stafify_positions`
--

CREATE TABLE `stafify_positions` (
  `position_id` bigint(20) UNSIGNED NOT NULL,
  `position_name` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `stafify_positions`
--

INSERT INTO `stafify_positions` (`position_id`, `position_name`) VALUES
(1, 'CEO'),
(2, 'Developer'),
(3, 'Project Manager'),
(4, 'Relationship Manager');

-- --------------------------------------------------------

--
-- Table structure for table `stafify_settings`
--

CREATE TABLE `stafify_settings` (
  `setting_id` bigint(20) UNSIGNED NOT NULL,
  `company` varchar(255) DEFAULT NULL,
  `setting_key` varchar(50) NOT NULL,
  `setting_value` text DEFAULT NULL,
  `updated_by` bigint(20) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `stafify_settings`
--

INSERT INTO `stafify_settings` (`setting_id`, `company`, `setting_key`, `setting_value`, `updated_by`, `created_at`, `updated_at`) VALUES
(1, NULL, 'early_clock_in_minutes', '15', 2, '2025-11-03 02:55:57', '2025-11-03 02:55:57'),
(2, NULL, 'on_time_late_minutes', '5', 2, '2025-11-03 02:55:57', '2025-11-03 02:55:57');

-- --------------------------------------------------------

--
-- Table structure for table `stafify_shifts`
--

CREATE TABLE `stafify_shifts` (
  `shift_id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `assigned_by` bigint(20) UNSIGNED NOT NULL,
  `shift_date` date NOT NULL,
  `start_time` time NOT NULL,
  `end_time` time NOT NULL,
  `shift_type` varchar(50) DEFAULT NULL,
  `location` varchar(100) DEFAULT NULL,
  `notes` text DEFAULT NULL,
  `include_break` tinyint(1) NOT NULL DEFAULT 0,
  `break_duration_minutes` int(11) DEFAULT NULL,
  `ot_modified` tinyint(1) NOT NULL DEFAULT 0,
  `ot_modified_by` bigint(20) UNSIGNED DEFAULT NULL,
  `ot_modified_at` datetime DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `stafify_shifts`
--

INSERT INTO `stafify_shifts` (`shift_id`, `user_id`, `assigned_by`, `shift_date`, `start_time`, `end_time`, `shift_type`, `location`, `notes`, `include_break`, `break_duration_minutes`, `ot_modified`, `ot_modified_by`, `ot_modified_at`, `created_at`, `updated_at`) VALUES
(1, 4, 5, '2025-04-23', '08:00:00', '17:00:00', 'Morning', '', '', 0, NULL, 0, NULL, NULL, '2025-04-22 01:19:32', NULL),
(2, 4, 5, '2025-04-24', '08:00:00', '17:00:00', 'Morning', '', '', 0, NULL, 0, NULL, NULL, '2025-04-22 01:19:32', NULL),
(3, 4, 5, '2025-04-25', '08:00:00', '17:00:00', 'Morning', '', '', 0, NULL, 0, NULL, NULL, '2025-04-22 01:19:32', NULL),
(4, 6, 5, '2025-04-23', '08:00:00', '17:00:00', 'Morning', '', '', 0, NULL, 0, NULL, NULL, '2025-04-22 01:19:32', NULL),
(5, 6, 5, '2025-04-24', '08:00:00', '17:00:00', 'Morning', '', '', 0, NULL, 0, NULL, NULL, '2025-04-22 01:19:32', NULL),
(6, 6, 5, '2025-04-25', '08:00:00', '17:00:00', 'Morning', '', '', 0, NULL, 0, NULL, NULL, '2025-04-22 01:19:32', NULL),
(7, 5, 5, '2025-04-23', '08:00:00', '17:00:00', 'Morning', '', '', 0, NULL, 0, NULL, NULL, '2025-04-22 01:19:32', NULL),
(8, 5, 5, '2025-04-24', '08:00:00', '17:00:00', 'Morning', '', '', 0, NULL, 0, NULL, NULL, '2025-04-22 01:19:32', NULL),
(9, 5, 5, '2025-04-25', '08:00:00', '17:00:00', 'Morning', '', '', 0, NULL, 0, NULL, NULL, '2025-04-22 01:19:32', NULL),
(10, 8, 5, '2025-04-23', '08:00:00', '17:00:00', 'Morning', '', '', 0, NULL, 0, NULL, NULL, '2025-04-22 01:19:32', NULL),
(11, 8, 5, '2025-04-24', '08:00:00', '17:00:00', 'Morning', '', '', 0, NULL, 0, NULL, NULL, '2025-04-22 01:19:32', NULL),
(12, 8, 5, '2025-04-25', '08:00:00', '17:00:00', 'Morning', '', '', 0, NULL, 0, NULL, NULL, '2025-04-22 01:19:32', NULL),
(13, 9, 5, '2025-04-23', '08:00:00', '17:00:00', 'Morning', '', '', 0, NULL, 0, NULL, NULL, '2025-04-22 01:19:32', NULL),
(14, 9, 5, '2025-04-24', '08:00:00', '17:00:00', 'Morning', '', '', 0, NULL, 0, NULL, NULL, '2025-04-22 01:19:32', NULL),
(15, 9, 5, '2025-04-25', '08:00:00', '17:00:00', 'Morning', '', '', 0, NULL, 0, NULL, NULL, '2025-04-22 01:19:32', NULL),
(16, 7, 5, '2025-04-23', '08:00:00', '17:00:00', 'Morning', '', '', 0, NULL, 0, NULL, NULL, '2025-04-22 01:19:32', NULL),
(17, 7, 5, '2025-04-24', '08:00:00', '17:00:00', 'Morning', '', '', 0, NULL, 0, NULL, NULL, '2025-04-22 01:19:32', NULL),
(18, 7, 5, '2025-04-25', '08:00:00', '17:00:00', 'Morning', '', '', 0, NULL, 0, NULL, NULL, '2025-04-22 01:19:32', NULL),
(19, 3, 5, '2025-04-23', '08:00:00', '17:00:00', 'Morning', '', '', 0, NULL, 0, NULL, NULL, '2025-04-22 01:19:32', NULL),
(20, 3, 5, '2025-04-24', '08:00:00', '17:00:00', 'Morning', '', '', 0, NULL, 0, NULL, NULL, '2025-04-22 01:19:32', NULL),
(21, 3, 5, '2025-04-25', '08:00:00', '17:00:00', 'Morning', '', '', 0, NULL, 0, NULL, NULL, '2025-04-22 01:19:32', NULL),
(22, 2, 2, '2025-04-21', '06:00:00', '14:00:00', 'Morning', 'Main Office', '', 0, NULL, 0, NULL, NULL, '2025-04-22 10:24:34', NULL),
(23, 2, 2, '2025-04-22', '06:00:00', '14:00:00', 'Morning', 'Main Office', '', 0, NULL, 0, NULL, NULL, '2025-04-22 10:24:34', NULL),
(24, 2, 2, '2025-04-23', '06:00:00', '14:00:00', 'Morning', 'Main Office', '', 0, NULL, 0, NULL, NULL, '2025-04-22 10:24:34', NULL),
(25, 2, 2, '2025-04-24', '06:00:00', '14:00:00', 'Morning', 'Main Office', '', 0, NULL, 0, NULL, NULL, '2025-04-22 10:24:34', NULL),
(26, 2, 2, '2025-04-25', '06:00:00', '14:00:00', 'Morning', 'Main Office', '', 0, NULL, 0, NULL, NULL, '2025-04-22 10:24:34', NULL),
(27, 2, 2, '2025-04-26', '06:00:00', '14:00:00', 'Morning', 'Main Office', '', 0, NULL, 0, NULL, NULL, '2025-04-22 10:24:34', NULL),
(28, 2, 2, '2025-04-27', '06:00:00', '14:00:00', 'Morning', 'Main Office', '', 0, NULL, 0, NULL, NULL, '2025-04-22 10:24:34', NULL),
(29, 2, 2, '2025-05-05', '13:00:00', '21:00:00', 'Afternoon', '', '', 0, NULL, 0, NULL, NULL, '2025-05-04 01:03:08', NULL),
(30, 2, 2, '2025-05-06', '13:00:00', '21:00:00', 'Afternoon', '', '', 0, NULL, 0, NULL, NULL, '2025-05-04 01:03:08', NULL),
(31, 2, 2, '2025-05-07', '13:00:00', '21:00:00', 'Afternoon', '', '', 0, NULL, 0, NULL, NULL, '2025-05-04 01:03:08', NULL),
(32, 2, 2, '2025-05-08', '13:00:00', '21:00:00', 'Afternoon', '', '', 0, NULL, 0, NULL, NULL, '2025-05-04 01:03:08', NULL),
(33, 2, 2, '2025-05-09', '13:00:00', '21:00:00', 'Afternoon', '', '', 0, NULL, 0, NULL, NULL, '2025-05-04 01:03:08', NULL),
(34, 24, 2, '2025-05-05', '13:00:00', '21:00:00', 'Afternoon', '', '', 0, NULL, 0, NULL, NULL, '2025-05-04 01:03:08', NULL),
(35, 24, 2, '2025-05-06', '13:00:00', '21:00:00', 'Afternoon', '', '', 0, NULL, 0, NULL, NULL, '2025-05-04 01:03:08', NULL),
(36, 24, 2, '2025-05-07', '13:00:00', '21:00:00', 'Afternoon', '', '', 0, NULL, 0, NULL, NULL, '2025-05-04 01:03:08', NULL),
(37, 24, 2, '2025-05-08', '13:00:00', '21:00:00', 'Afternoon', '', '', 0, NULL, 0, NULL, NULL, '2025-05-04 01:03:08', NULL),
(38, 24, 2, '2025-05-09', '13:00:00', '21:00:00', 'Afternoon', '', '', 0, NULL, 0, NULL, NULL, '2025-05-04 01:03:08', NULL),
(39, 4, 2, '2025-05-05', '13:00:00', '21:00:00', 'Afternoon', '', '', 0, NULL, 0, NULL, NULL, '2025-05-04 01:03:08', NULL),
(40, 4, 2, '2025-05-06', '13:00:00', '21:00:00', 'Afternoon', '', '', 0, NULL, 0, NULL, NULL, '2025-05-04 01:03:08', NULL),
(41, 4, 2, '2025-05-07', '13:00:00', '21:00:00', 'Afternoon', '', '', 0, NULL, 0, NULL, NULL, '2025-05-04 01:03:08', NULL),
(42, 4, 2, '2025-05-08', '13:00:00', '21:00:00', 'Afternoon', '', '', 0, NULL, 0, NULL, NULL, '2025-05-04 01:03:08', NULL),
(43, 4, 2, '2025-05-09', '13:00:00', '21:00:00', 'Afternoon', '', '', 0, NULL, 0, NULL, NULL, '2025-05-04 01:03:08', NULL),
(44, 6, 2, '2025-05-05', '13:00:00', '21:00:00', 'Afternoon', '', '', 0, NULL, 0, NULL, NULL, '2025-05-04 01:03:08', NULL),
(45, 6, 2, '2025-05-06', '13:00:00', '21:00:00', 'Afternoon', '', '', 0, NULL, 0, NULL, NULL, '2025-05-04 01:03:08', NULL),
(46, 6, 2, '2025-05-07', '13:00:00', '21:00:00', 'Afternoon', '', '', 0, NULL, 0, NULL, NULL, '2025-05-04 01:03:08', NULL),
(47, 6, 2, '2025-05-08', '13:00:00', '21:00:00', 'Afternoon', '', '', 0, NULL, 0, NULL, NULL, '2025-05-04 01:03:08', NULL),
(48, 6, 2, '2025-05-09', '13:00:00', '21:00:00', 'Afternoon', '', '', 0, NULL, 0, NULL, NULL, '2025-05-04 01:03:08', NULL),
(49, 5, 2, '2025-05-05', '13:00:00', '21:00:00', 'Afternoon', '', '', 0, NULL, 0, NULL, NULL, '2025-05-04 01:03:08', NULL),
(50, 5, 2, '2025-05-06', '13:00:00', '21:00:00', 'Afternoon', '', '', 0, NULL, 0, NULL, NULL, '2025-05-04 01:03:08', NULL),
(51, 5, 2, '2025-05-07', '13:00:00', '21:00:00', 'Afternoon', '', '', 0, NULL, 0, NULL, NULL, '2025-05-04 01:03:08', NULL),
(52, 5, 2, '2025-05-08', '13:00:00', '21:00:00', 'Afternoon', '', '', 0, NULL, 0, NULL, NULL, '2025-05-04 01:03:08', NULL),
(53, 5, 2, '2025-05-09', '13:00:00', '21:00:00', 'Afternoon', '', '', 0, NULL, 0, NULL, NULL, '2025-05-04 01:03:08', NULL),
(54, 8, 2, '2025-05-05', '13:00:00', '21:00:00', 'Afternoon', '', '', 0, NULL, 0, NULL, NULL, '2025-05-04 01:03:08', NULL),
(55, 8, 2, '2025-05-06', '13:00:00', '21:00:00', 'Afternoon', '', '', 0, NULL, 0, NULL, NULL, '2025-05-04 01:03:08', NULL),
(56, 8, 2, '2025-05-07', '13:00:00', '21:00:00', 'Afternoon', '', '', 0, NULL, 0, NULL, NULL, '2025-05-04 01:03:08', NULL),
(57, 8, 2, '2025-05-08', '13:00:00', '21:00:00', 'Afternoon', '', '', 0, NULL, 0, NULL, NULL, '2025-05-04 01:03:08', NULL),
(58, 8, 2, '2025-05-09', '13:00:00', '21:00:00', 'Afternoon', '', '', 0, NULL, 0, NULL, NULL, '2025-05-04 01:03:08', NULL),
(59, 11, 2, '2025-05-05', '13:00:00', '21:00:00', 'Afternoon', '', '', 0, NULL, 0, NULL, NULL, '2025-05-04 01:03:08', NULL),
(60, 11, 2, '2025-05-06', '13:00:00', '21:00:00', 'Afternoon', '', '', 0, NULL, 0, NULL, NULL, '2025-05-04 01:03:08', NULL),
(61, 11, 2, '2025-05-07', '13:00:00', '21:00:00', 'Afternoon', '', '', 0, NULL, 0, NULL, NULL, '2025-05-04 01:03:08', NULL),
(62, 11, 2, '2025-05-08', '13:00:00', '21:00:00', 'Afternoon', '', '', 0, NULL, 0, NULL, NULL, '2025-05-04 01:03:08', NULL),
(63, 11, 2, '2025-05-09', '13:00:00', '21:00:00', 'Afternoon', '', '', 0, NULL, 0, NULL, NULL, '2025-05-04 01:03:08', NULL),
(64, 9, 2, '2025-05-05', '13:00:00', '21:00:00', 'Afternoon', '', '', 0, NULL, 0, NULL, NULL, '2025-05-04 01:03:08', NULL),
(65, 9, 2, '2025-05-06', '13:00:00', '21:00:00', 'Afternoon', '', '', 0, NULL, 0, NULL, NULL, '2025-05-04 01:03:08', NULL),
(66, 9, 2, '2025-05-07', '13:00:00', '21:00:00', 'Afternoon', '', '', 0, NULL, 0, NULL, NULL, '2025-05-04 01:03:08', NULL),
(67, 9, 2, '2025-05-08', '13:00:00', '21:00:00', 'Afternoon', '', '', 0, NULL, 0, NULL, NULL, '2025-05-04 01:03:08', NULL),
(68, 9, 2, '2025-05-09', '13:00:00', '21:00:00', 'Afternoon', '', '', 0, NULL, 0, NULL, NULL, '2025-05-04 01:03:08', NULL),
(69, 14, 2, '2025-05-05', '13:00:00', '21:00:00', 'Afternoon', '', '', 0, NULL, 0, NULL, NULL, '2025-05-04 01:03:08', NULL),
(70, 14, 2, '2025-05-06', '13:00:00', '21:00:00', 'Afternoon', '', '', 0, NULL, 0, NULL, NULL, '2025-05-04 01:03:08', NULL),
(71, 14, 2, '2025-05-07', '13:00:00', '21:00:00', 'Afternoon', '', '', 0, NULL, 0, NULL, NULL, '2025-05-04 01:03:08', NULL),
(72, 14, 2, '2025-05-08', '13:00:00', '21:00:00', 'Afternoon', '', '', 0, NULL, 0, NULL, NULL, '2025-05-04 01:03:08', NULL),
(73, 14, 2, '2025-05-09', '13:00:00', '21:00:00', 'Afternoon', '', '', 0, NULL, 0, NULL, NULL, '2025-05-04 01:03:08', NULL),
(74, 7, 2, '2025-05-05', '13:00:00', '21:00:00', 'Afternoon', '', '', 0, NULL, 0, NULL, NULL, '2025-05-04 01:03:08', NULL),
(75, 7, 2, '2025-05-06', '13:00:00', '21:00:00', 'Afternoon', '', '', 0, NULL, 0, NULL, NULL, '2025-05-04 01:03:08', NULL),
(76, 7, 2, '2025-05-07', '13:00:00', '21:00:00', 'Afternoon', '', '', 0, NULL, 0, NULL, NULL, '2025-05-04 01:03:08', NULL),
(77, 7, 2, '2025-05-08', '13:00:00', '21:00:00', 'Afternoon', '', '', 0, NULL, 0, NULL, NULL, '2025-05-04 01:03:08', NULL),
(78, 7, 2, '2025-05-09', '13:00:00', '21:00:00', 'Afternoon', '', '', 0, NULL, 0, NULL, NULL, '2025-05-04 01:03:08', NULL),
(79, 3, 2, '2025-05-05', '13:00:00', '21:00:00', 'Afternoon', '', '', 0, NULL, 0, NULL, NULL, '2025-05-04 01:03:08', NULL),
(80, 3, 2, '2025-05-06', '13:00:00', '21:00:00', 'Afternoon', '', '', 0, NULL, 0, NULL, NULL, '2025-05-04 01:03:08', NULL),
(81, 3, 2, '2025-05-07', '13:00:00', '21:00:00', 'Afternoon', '', '', 0, NULL, 0, NULL, NULL, '2025-05-04 01:03:08', NULL),
(82, 3, 2, '2025-05-08', '13:00:00', '21:00:00', 'Afternoon', '', '', 0, NULL, 0, NULL, NULL, '2025-05-04 01:03:08', NULL),
(83, 3, 2, '2025-05-09', '13:00:00', '21:00:00', 'Afternoon', '', '', 0, NULL, 0, NULL, NULL, '2025-05-04 01:03:08', NULL),
(84, 24, 5, '2025-08-15', '11:35:00', '13:35:00', 'Morning', '', '', 0, NULL, 0, NULL, NULL, '2025-08-11 17:35:29', NULL),
(85, 19, 19, '2025-11-03', '20:00:00', '10:00:00', 'Night', 'Main Office', '', 0, NULL, 0, NULL, NULL, '2025-11-03 04:41:42', '2025-11-03 04:41:42');

-- --------------------------------------------------------

--
-- Table structure for table `stafify_time_tracking`
--

CREATE TABLE `stafify_time_tracking` (
  `record_id` bigint(20) UNSIGNED NOT NULL,
  `shift_id` bigint(20) UNSIGNED DEFAULT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `clock_in_time` datetime DEFAULT NULL,
  `clock_out_time` datetime DEFAULT NULL,
  `record_date` date NOT NULL,
  `total_hours` decimal(5,2) DEFAULT NULL,
  `status` enum('pending','completed','incomplete','absent') NOT NULL DEFAULT 'pending',
  `location` varchar(100) DEFAULT NULL,
  `notes` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `stafify_time_tracking`
--

INSERT INTO `stafify_time_tracking` (`record_id`, `shift_id`, `user_id`, `clock_in_time`, `clock_out_time`, `record_date`, `total_hours`, `status`, `location`, `notes`, `created_at`, `updated_at`) VALUES
(1, 1, 4, NULL, NULL, '2025-04-23', NULL, 'incomplete', NULL, NULL, '2025-04-22 01:19:32', NULL),
(2, 2, 4, NULL, NULL, '2025-04-24', NULL, 'pending', NULL, NULL, '2025-04-22 01:19:32', NULL),
(3, 3, 4, NULL, NULL, '2025-04-25', NULL, 'pending', NULL, NULL, '2025-04-22 01:19:32', NULL),
(22, 22, 2, NULL, NULL, '2025-04-21', NULL, 'pending', NULL, NULL, '2025-04-22 10:24:34', NULL),
(23, 23, 2, NULL, NULL, '2025-04-22', NULL, 'pending', NULL, NULL, '2025-04-22 10:24:34', NULL),
(24, 24, 2, NULL, NULL, '2025-04-23', NULL, 'pending', NULL, NULL, '2025-04-22 10:24:34', NULL),
(25, 25, 2, NULL, NULL, '2025-04-24', NULL, 'pending', NULL, NULL, '2025-04-22 10:24:34', NULL),
(26, 26, 2, NULL, NULL, '2025-04-25', NULL, 'pending', NULL, NULL, '2025-04-22 10:24:34', NULL),
(27, 27, 2, NULL, NULL, '2025-04-26', NULL, 'pending', NULL, NULL, '2025-04-22 10:24:34', NULL),
(28, 28, 2, NULL, NULL, '2025-04-27', NULL, 'pending', NULL, NULL, '2025-04-22 10:24:34', NULL),
(29, 1, 4, '2025-04-23 10:07:09', '2025-04-23 17:00:00', '2025-04-23', 6.88, 'incomplete', '', '\nSystem auto clock-out: shift ended', '2025-04-22 18:07:09', NULL),
(30, 29, 2, NULL, NULL, '2025-05-05', NULL, 'incomplete', NULL, NULL, '2025-05-04 01:03:08', NULL),
(40, 39, 4, NULL, NULL, '2025-05-05', NULL, 'completed', NULL, NULL, '2025-05-04 01:03:08', NULL),
(50, 49, 5, NULL, NULL, '2025-05-05', NULL, 'completed', NULL, NULL, '2025-05-04 01:03:08', NULL),
(80, 79, 3, NULL, NULL, '2025-05-05', NULL, 'completed', NULL, NULL, '2025-05-04 01:03:08', NULL),
(85, 39, 4, '2025-05-05 12:58:50', '2025-05-05 21:00:00', '2025-05-05', 8.02, 'completed', '', '\nSystem auto clock-out: shift ended', '2025-05-04 20:58:50', NULL),
(86, 49, 5, '2025-05-05 12:59:41', '2025-05-05 21:00:00', '2025-05-05', 8.01, 'completed', '', '\nSystem auto clock-out: shift ended', '2025-05-04 20:59:41', NULL),
(87, 79, 3, '2025-05-05 12:59:53', '2025-05-05 21:00:00', '2025-05-05', 8.00, 'completed', '', '\nSystem auto clock-out: shift ended', '2025-05-04 20:59:53', NULL),
(88, 29, 2, '2025-05-05 13:01:03', '2025-05-05 21:00:00', '2025-05-05', 7.98, 'incomplete', '', '\nSystem auto clock-out: shift ended', '2025-05-04 21:01:03', NULL),
(92, 60, 11, '2025-05-06 12:56:29', '2025-05-06 21:03:57', '2025-05-06', 8.12, 'completed', '', '', '2025-05-05 20:56:29', NULL),
(93, 75, 7, '2025-05-06 12:58:17', '2025-05-06 21:02:44', '2025-05-06', 8.07, 'completed', '', '', '2025-05-05 20:58:17', NULL),
(105, 84, 24, NULL, NULL, '2025-08-15', NULL, 'pending', NULL, NULL, '2025-08-11 17:35:29', NULL),
(106, 85, 19, '2025-11-03 12:41:56', '2025-11-03 13:43:09', '2025-11-03', 1.02, 'completed', NULL, 'wala', '2025-11-03 04:41:56', '2025-11-03 05:43:09'),
(107, 85, 19, '2025-11-03 13:43:14', '2025-11-03 14:31:04', '2025-11-03', 0.80, 'completed', NULL, NULL, '2025-11-03 05:43:14', '2025-11-03 06:31:04');

-- --------------------------------------------------------

--
-- Table structure for table `stafify_users`
--

CREATE TABLE `stafify_users` (
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `user_name` varchar(255) DEFAULT NULL,
  `full_name` varchar(255) DEFAULT NULL,
  `user_email` varchar(255) DEFAULT NULL,
  `phone_number` varchar(20) DEFAULT NULL,
  `user_pin` int(11) DEFAULT NULL,
  `company` varchar(255) DEFAULT NULL,
  `user_dept` varchar(255) DEFAULT NULL,
  `user_position` varchar(255) DEFAULT NULL,
  `access_level` int(11) NOT NULL DEFAULT 1,
  `is_admin` int(11) NOT NULL DEFAULT 0,
  `address` varchar(255) DEFAULT NULL,
  `country` varchar(100) DEFAULT NULL,
  `country_code` varchar(10) DEFAULT NULL,
  `employment_date` date DEFAULT NULL,
  `branch_location` varchar(100) DEFAULT NULL,
  `engagement_status` varchar(50) DEFAULT NULL,
  `user_status` varchar(50) DEFAULT NULL,
  `user_type` varchar(50) DEFAULT NULL,
  `wage_type` varchar(50) DEFAULT NULL,
  `sil_status` tinyint(1) NOT NULL DEFAULT 0,
  `statutory_benefits` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `stafify_users`
--

INSERT INTO `stafify_users` (`user_id`, `user_name`, `full_name`, `user_email`, `phone_number`, `user_pin`, `company`, `user_dept`, `user_position`, `access_level`, `is_admin`, `address`, `country`, `country_code`, `employment_date`, `branch_location`, `engagement_status`, `user_status`, `user_type`, `wage_type`, `sil_status`, `statutory_benefits`, `created_at`, `updated_at`) VALUES
(2, 'allenlim', 'Allen Lim', 'stafify@gmail.com', '09164933599', 924, 'Stafify', 'Management', 'CEO', 2, 1, 'Greenwoods Park, 2F, Lot 17, Parcel 4, Rizal Hwy, CBD Area, Subic Bay Freeport Zone, 2200 Zambales', 'Philippines', '', '1990-09-24', 'Headquarters', 'full_time', 'engaged', 'isp', 'non_mwe', 1, 0, '2025-11-03 02:55:57', '2025-11-03 02:55:57'),
(3, 'siahbautista', 'Siah Bautista', 'bautistamahasiah@gmail.com', '09684382598', 90602, 'Stafify', 'IT Department', 'Project Manager', 2, 0, '#9 Aguinaldo St. New Asinan Olongapo City, Zambales', 'Philippines', 'PH', '2025-02-10', 'Headquarters', NULL, NULL, NULL, NULL, 0, 0, '2025-11-03 02:55:57', '2025-11-03 02:55:57'),
(4, 'aaorcs', 'Andrea Anne Orca', 'annedreaorca@gmail.com', '09984092760', 102703, 'Stafify', 'IT Department', 'Developer', 2, 0, '163-D Lower Kalaklan Olongapo City', 'Philippines', 'PH', '2025-02-10', 'Headquarters', NULL, NULL, NULL, NULL, 0, 0, '2025-11-03 02:55:57', '2025-11-03 02:55:57'),
(5, 'cjaycuya', 'Christian Jay Cuya', 'cjapaycuya16@gmail.com', '09763498472', 51603, 'Stafify', 'IT Department', 'Full-Stack Developer', 2, 0, '22 Rizal St. Brgy. Barretto, Olongapo City', 'Philippines', 'PH', '2025-02-10', 'Headquarters', 'full_time', 'active', 'employee', 'mwe', 1, 0, '2025-11-03 02:55:57', '2025-11-03 02:55:57'),
(6, 'chaws', 'Charles John Malit', 'charlesjohnmalit4@gmail.com', '', 0, 'Stafify', 'IT Department', 'Payroll', 2, 0, '', 'Philippines', 'PH', '2025-02-24', 'Headquarters', NULL, NULL, NULL, NULL, 0, 0, '2025-11-03 02:55:57', '2025-11-03 02:55:57'),
(7, 'marielle', 'Marielle Corpuz', 'mariellecorpuz02@gmail.com', '09397263021', 0, 'Stafify', 'IT Department', 'Developer', 2, 0, 'Baloy Long Beach Rd., Brgy. Barretto, Olongapo City, Zambales, 2200', 'Philippines', '', '2025-02-25', 'Headquarters', NULL, NULL, NULL, NULL, 0, 0, '2025-11-03 02:55:57', '2025-11-03 02:55:57'),
(8, 'jambe', 'Jay-vee Ubaldo', 'jayveeubaldo110@gmail.com', '09983666903', 0, 'Stafify', 'HRIS', 'Talent Acquisition', 2, 0, 'Blk 21 santol St.', 'Philippines', '', '2025-02-24', 'Headquarters', NULL, NULL, NULL, NULL, 0, 0, '2025-11-03 02:55:57', '2025-11-03 02:55:57'),
(9, 'renz', 'John Laurenze Leguidleguid', 'Johnlaurenzel@gmail.com', '09273227055', 0, 'Stafify', 'IT Department', 'Developer', 2, 0, 'Blk 23 Ubas Street Gordon Heights Olongapo City', 'Philippines', 'PH', '2025-02-24', 'Headquarters', NULL, NULL, NULL, NULL, 0, 0, '2025-11-03 02:55:57', '2025-11-03 02:55:57'),
(11, 'gabriel', 'John Gabriel Orbeta', 'gabrielorbeta11@gmail.com', '09665915026', 0, 'Stafify', 'IT Department', 'Developer', 2, 0, 'purok 4 banaba st. brgy barretto.', '', '', '2025-02-24', 'Headquarters', NULL, NULL, NULL, NULL, 0, 0, '2025-11-03 02:55:57', '2025-11-03 02:55:57'),
(14, 'tintin21', 'Justine Dimalanta', 'dimalantajustine8@gmail.com', '', 0, 'Stafify', 'AFIS', 'Project Manager', 2, 0, '', '', '', '2025-02-24', 'Headquarters', NULL, NULL, NULL, NULL, 0, 0, '2025-11-03 02:55:57', '2025-11-03 02:55:57'),
(15, 'Aulwen', 'Aulwen Dizon', 'aulwen126@gmail.com', '09650636585', 0, 'Stafify', 'Sales', 'Relationship Manager', 2, 0, '#50 18th Street East Bajac-Bajac, Olongapo City', '', '', '2025-04-16', 'Headquarters', NULL, NULL, NULL, NULL, 0, 0, '2025-11-03 02:55:57', '2025-11-03 02:55:57'),
(16, 'Karu', 'Carl Barrera', '202011828@gordoncollege.edu.ph', '+639468291705', 30801, 'Stafify', 'IT Department', 'Developer', 2, 0, '#8 - 21st Place West Bajac - Bajac, Olongapo City', '', '', '2025-03-07', 'Headquarters', NULL, NULL, NULL, NULL, 0, 0, '2025-11-03 02:55:57', '2025-11-03 02:55:57'),
(17, 'Lanpher Garcia', 'Lanpher Garcia', 'garcia.lanpher13@gmail.com', '+63 956 618 2903', 41103, 'Stafify', 'Unassigned', 'Unassigned', 2, 0, '31 14th Street, East Tapinac, Olongapo city', '', '', '2025-03-10', 'Headquarters', NULL, NULL, NULL, NULL, 0, 0, '2025-11-03 02:55:57', '2025-11-03 02:55:57'),
(19, 'amilmusa', 'Amil Musa', 'amilmusa03@gmail.com', '09198119666', 406, 'Stafify', 'IT Department', 'Developer', 2, 0, '20 fontaine East Bajac Bajac Olongapo City', '', '', '2025-04-23', 'Headquarters', 'full_time', 'active', 'isp', 'mwe', 0, 0, '2025-11-03 02:55:57', '2025-11-03 02:55:57'),
(24, 'alsheretteramos', 'Alsherette Ramos', 'alsheretteramos@gmail.com', '09647691126', 1234, 'Stafify', 'IT Department', 'Developer', 2, 0, 'Olongapo City', '', '', '2025-02-24', 'Headquarters', NULL, NULL, NULL, NULL, 0, 0, '2025-11-03 02:55:57', '2025-11-03 02:55:57'),
(26, 'test2', 'test', 'tes@gmail.com', '123456789', 123456, 'Stafify', 'IT Department', 'Developer', 3, 0, '123 Anyway street', '', '', '2003-05-05', 'Headquarters', NULL, NULL, NULL, NULL, 0, 0, '2025-11-03 02:55:57', '2025-11-03 02:55:57'),
(27, 'basicuser', 'Basic User', 'basicuser@gmail.com', '01234567890', 123456, 'Stafify', 'IT Department', 'Developer', 3, 0, '123 Anyway street', '', '', '2025-05-05', 'Headquarters', 'full_time', 'active', 'isp', 'govt_services', 0, 0, '2025-11-03 02:55:57', '2025-11-03 02:55:57');

-- --------------------------------------------------------

--
-- Table structure for table `stafify_user_rates`
--

CREATE TABLE `stafify_user_rates` (
  `rate_id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `hourly_rate` decimal(10,2) NOT NULL DEFAULT 0.00,
  `daily_rate` decimal(10,2) NOT NULL DEFAULT 0.00,
  `monthly_rate` decimal(10,2) NOT NULL DEFAULT 0.00,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `stafify_user_rates`
--

INSERT INTO `stafify_user_rates` (`rate_id`, `user_id`, `hourly_rate`, `daily_rate`, `monthly_rate`, `created_at`, `updated_at`) VALUES
(1, 3, 150.00, 1200.00, 26400.00, '2025-08-15 01:21:17', '2025-08-15 01:21:17'),
(2, 2, 200.00, 1600.00, 35200.00, NULL, '2025-11-03 06:26:42'),
(3, 19, 100.00, 800.00, 17600.00, NULL, '2025-11-03 05:08:42');

-- --------------------------------------------------------

--
-- Table structure for table `talent_management_resources`
--

CREATE TABLE `talent_management_resources` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `resource_key` varchar(255) NOT NULL,
  `title` varchar(255) NOT NULL,
  `type` varchar(255) NOT NULL,
  `url` text NOT NULL,
  `form_url` text DEFAULT NULL,
  `icon_path` varchar(255) DEFAULT NULL,
  `icon_lordicon` varchar(255) DEFAULT NULL,
  `display_order` int(11) NOT NULL DEFAULT 0,
  `active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `talent_toolkit`
--

CREATE TABLE `talent_toolkit` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `sales_title` varchar(255) NOT NULL,
  `form_url` text DEFAULT NULL,
  `response_url` text DEFAULT NULL,
  `icon` varchar(255) NOT NULL DEFAULT 'communication.gif',
  `type` varchar(50) NOT NULL,
  `is_approved` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `talent_toolkit`
--

INSERT INTO `talent_toolkit` (`id`, `user_id`, `sales_title`, `form_url`, `response_url`, `icon`, `type`, `is_approved`, `created_at`, `updated_at`) VALUES
(1, 1, 'E-Interview', 'https://docs.google.com/forms/d/example1/viewform', 'https://docs.google.com/spreadsheets/d/example1/edit', 'interview.gif', 'Form+Sheet', 1, '2025-11-03 02:55:57', '2025-11-03 02:55:57'),
(2, 1, 'Quicklinks of Reviewer', 'https://drive.google.com/folders/example2', NULL, 'reviewer.gif', 'Folder', 1, '2025-11-03 02:55:57', '2025-11-03 02:55:57'),
(3, 1, 'Final Exam', 'https://docs.google.com/forms/d/example3/viewform', NULL, 'exam.gif', 'Form', 1, '2025-11-03 02:55:57', '2025-11-03 02:55:57'),
(4, 1, 'Training Guide', NULL, 'https://docs.google.com/spreadsheets/d/example4/edit', 'guide.gif', 'Sheet', 1, '2025-11-03 02:55:57', '2025-11-03 02:55:57'),
(5, 1, 'Media Assets', 'https://drive.google.com/folders/example5', NULL, 'media.gif', 'Folder', 1, '2025-11-03 02:55:57', '2025-11-03 02:55:57'),
(6, 19, 'Multiple Form', '[\"https:\\/\\/docs.google.com\\/forms\\/d\\/e\\/1FAIpQLSeNRl70PCT4rSTJmOVHEoxsaZkNrstVXaqS7jRQ7kwAqpak9A\\/viewform?usp=dialog\",\"https:\\/\\/docs.google.com\\/forms\\/d\\/e\\/1FAIpQLSeNRl70PCT4rSTJmOVHEoxsaZkNrstVXaqS7jRQ7kwAqpak9A\\/viewform?usp=dialog\",\"https:\\/\\/docs.google.com\\/forms\\/d\\/e\\/1FAIpQLSeNRl70PCT4rSTJmOVHEoxsaZkNrstVXaqS7jRQ7kwAqpak9A\\/viewform?usp=dialog\",\"https:\\/\\/docs.google.com\\/forms\\/d\\/e\\/1FAIpQLSeNRl70PCT4rSTJmOVHEoxsaZkNrstVXaqS7jRQ7kwAqpak9A\\/viewform?usp=dialog\",\"https:\\/\\/docs.google.com\\/forms\\/d\\/e\\/1FAIpQLSeNRl70PCT4rSTJmOVHEoxsaZkNrstVXaqS7jRQ7kwAqpak9A\\/viewform?usp=dialog\",\"https:\\/\\/docs.google.com\\/forms\\/d\\/e\\/1FAIpQLSeNRl70PCT4rSTJmOVHEoxsaZkNrstVXaqS7jRQ7kwAqpak9A\\/viewform?usp=dialog\"]', '[\"https:\\/\\/docs.google.com\\/spreadsheets\\/d\\/1QJORvLD6KuHR5BZJTGKIaNMpGKRZWITq-DIrt_zb1dw\\/edit?resourcekey&usp=forms_web_b&urp=initialLink#gid=1826478195\",\"https:\\/\\/docs.google.com\\/spreadsheets\\/d\\/1QJORvLD6KuHR5BZJTGKIaNMpGKRZWITq-DIrt_zb1dw\\/edit?resourcekey&usp=forms_web_b&urp=initialLink#gid=1826478195\",\"https:\\/\\/docs.google.com\\/spreadsheets\\/d\\/1QJORvLD6KuHR5BZJTGKIaNMpGKRZWITq-DIrt_zb1dw\\/edit?resourcekey&usp=forms_web_b&urp=initialLink#gid=1826478195\",\"https:\\/\\/docs.google.com\\/spreadsheets\\/d\\/1QJORvLD6KuHR5BZJTGKIaNMpGKRZWITq-DIrt_zb1dw\\/edit?resourcekey&usp=forms_web_b&urp=initialLink#gid=1826478195\",\"https:\\/\\/docs.google.com\\/spreadsheets\\/d\\/1QJORvLD6KuHR5BZJTGKIaNMpGKRZWITq-DIrt_zb1dw\\/edit?resourcekey&usp=forms_web_b&urp=initialLink#gid=1826478195\",\"https:\\/\\/docs.google.com\\/spreadsheets\\/d\\/1QJORvLD6KuHR5BZJTGKIaNMpGKRZWITq-DIrt_zb1dw\\/edit?resourcekey&usp=forms_web_b&urp=initialLink#gid=1826478195\"]', 'Clock.gif', 'Multiple Forms + Sheets', 1, '2025-11-05 06:16:49', '2025-11-05 06:16:49');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `user_name` varchar(255) NOT NULL,
  `first_name` varchar(255) DEFAULT NULL,
  `middle_name` varchar(255) DEFAULT NULL,
  `last_name` varchar(255) DEFAULT NULL,
  `full_name` varchar(255) NOT NULL,
  `user_email` varchar(255) NOT NULL,
  `phone_number` varchar(255) DEFAULT NULL,
  `address` text DEFAULT NULL,
  `country` varchar(255) DEFAULT NULL,
  `country_code` varchar(2) DEFAULT NULL,
  `user_pin` varchar(255) DEFAULT NULL,
  `company` varchar(255) DEFAULT NULL,
  `user_dept` varchar(255) NOT NULL DEFAULT 'Unassigned',
  `user_position` varchar(255) NOT NULL DEFAULT 'Unassigned',
  `user_password` varchar(255) NOT NULL,
  `is_archived` tinyint(4) NOT NULL DEFAULT 0,
  `access_level` tinyint(4) NOT NULL DEFAULT 0,
  `profile_picture` varchar(255) NOT NULL DEFAULT 'default.png',
  `temp_name` varchar(255) DEFAULT NULL,
  `employment_date` date DEFAULT NULL,
  `branch_location` varchar(255) DEFAULT NULL,
  `engagement_status` varchar(255) DEFAULT NULL,
  `user_status` varchar(255) DEFAULT NULL,
  `user_type` varchar(255) DEFAULT NULL,
  `wage_type` varchar(255) DEFAULT NULL,
  `sil_status` tinyint(4) NOT NULL DEFAULT 0,
  `statutory_benefits` tinyint(4) NOT NULL DEFAULT 0,
  `drive_folder_id` varchar(255) DEFAULT NULL,
  `drive_folder_link` text DEFAULT NULL,
  `is_verified` tinyint(4) NOT NULL DEFAULT 0,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `user_name`, `first_name`, `middle_name`, `last_name`, `full_name`, `user_email`, `phone_number`, `address`, `country`, `country_code`, `user_pin`, `company`, `user_dept`, `user_position`, `user_password`, `is_archived`, `access_level`, `profile_picture`, `temp_name`, `employment_date`, `branch_location`, `engagement_status`, `user_status`, `user_type`, `wage_type`, `sil_status`, `statutory_benefits`, `drive_folder_id`, `drive_folder_link`, `is_verified`, `email_verified_at`, `remember_token`, `created_at`, `updated_at`) VALUES
(1, 'stafifycreator', 'Stafify', '', 'Creator', 'Stafify Creator', 'stafifycreator@gmail.com', '0924', 'Greenwoods Park, 2F, Lot 17, Parcel 4, Rizal Hwy, CBD Area, Subic Bay Freeport Zone, 2200 Zambales', 'Philippines', '', '924', 'Stafify CMD', 'CMD', 'Creator', 'e31b21acfd768e201a5237b089587e7df8aa4c4388979c6491d9db909aaae6a5', 0, 1, 'default.png', '', '2025-02-10', '', 'Full-time', 'Active', 'Employee', 'Salary', 0, 0, NULL, NULL, 0, NULL, NULL, '2025-11-03 02:55:57', '2025-11-03 02:55:57'),
(2, 'allenlim', '', '', '', 'Allen Lim', 'stafify@gmail.com', '09164933599', 'Greenwoods Park, 2F, Lot 17, Parcel 4, Rizal Hwy, CBD Area, Subic Bay Freeport Zone, 2200 Zambales', 'Philippines', '', '924', 'Stafify', 'Management', 'CEO', 'e31b21acfd768e201a5237b089587e7df8aa4c4388979c6491d9db909aaae6a5', 0, 2, '4e9a6c340a0d5be36f4026b904cc51f32fda2ac37b0f744782d3a28e0ba2f3ed.jpg', '', '1990-09-24', 'Headquarters', 'part_time', 'active', 'employee', 'non_mwe', 0, 0, NULL, NULL, 0, NULL, NULL, '2025-11-03 02:55:57', '2025-11-03 06:24:04'),
(3, 'siahbautista', '', '', '', 'Siah Bautista', 'bautistamahasiah@gmail.com', '09684382598', '#9 Aguinaldo St. New Asinan Olongapo City, Zambales', 'Philippines', 'PH', '90602', 'Stafify', 'IT Department', 'Project Manager', '5994471abb01112afcc18159f6cc74b4f511b99806da59b3caf5a9c173cacfc5', 0, 2, '2e0a6da744e77379b73eb1c1f1db8a44da0c0264e55406d0d764dc05baa6b3fb.jfif', '', '2025-02-10', 'Headquarters', NULL, NULL, NULL, NULL, 0, 0, NULL, NULL, 0, NULL, NULL, '2025-11-03 02:55:57', '2025-11-03 02:55:57'),
(4, 'aaorcs', '', '', '', 'Andrea Anne Orca', 'annedreaorca@gmail.com', '09984092760', '163-D Lower Kalaklan Olongapo City', 'Philippines', 'PH', '102703', 'Stafify', 'IT Department', 'Developer', '5994471abb01112afcc18159f6cc74b4f511b99806da59b3caf5a9c173cacfc5', 0, 2, 'default.png', '', '2025-02-10', 'Headquarters', NULL, NULL, NULL, NULL, 0, 0, NULL, NULL, 0, NULL, NULL, '2025-11-03 02:55:57', '2025-11-03 02:55:57'),
(5, 'cjaycuya', '', '', '', 'Christian Jay Cuya', 'cjapaycuya16@gmail.com', '09763498472', '22 Rizal St. Brgy. Barretto, Olongapo City', 'Philippines', 'PH', '51603', 'Stafify', 'IT Department', 'Full-Stack Developer', '5994471abb01112afcc18159f6cc74b4f511b99806da59b3caf5a9c173cacfc5', 0, 2, '0013bdcf32506e12bb0035634f51f3eed4f73fd64e5281c8d6a4daaf68264c5e.jpg', '', '2025-02-10', 'Headquarters', NULL, NULL, NULL, NULL, 0, 0, NULL, NULL, 0, NULL, NULL, '2025-11-03 02:55:57', '2025-11-03 02:55:57'),
(6, 'chaws', '', '', '', 'Charles John Malit', 'charlesjohnmalit4@gmail.com', '', '', 'Philippines', 'PH', '0', 'Stafify', 'IT Department', 'Payroll', '5994471abb01112afcc18159f6cc74b4f511b99806da59b3caf5a9c173cacfc5', 0, 2, 'default.png', '', '2025-02-24', 'Headquarters', NULL, NULL, NULL, NULL, 0, 0, NULL, NULL, 0, NULL, NULL, '2025-11-03 02:55:57', '2025-11-03 02:55:57'),
(7, 'marielle', '', '', '', 'Marielle Corpuz', 'mariellecorpuz02@gmail.com', '09397263021', 'Baloy Long Beach Rd., Brgy. Barretto, Olongapo City, Zambales, 2200', 'Philippines', '', '0', 'Stafify', 'IT Department', 'Developer', 'fcfd401e2ca1051a792ddd112819f706e16c4b0c05f37aeff57229849d3cb8ab', 0, 2, 'de07a7f1521fcbd8a9cd757869a032ae55b7d1f0075685186c86042922ad4325.jpg', '', '2025-02-25', 'Headquarters', NULL, NULL, NULL, NULL, 0, 0, NULL, NULL, 0, NULL, NULL, '2025-11-03 02:55:57', '2025-11-03 02:55:57'),
(8, 'jambe', '', '', '', 'Jay-vee Ubaldo', 'jayveeubaldo110@gmail.com', '09983666903', 'Blk 21 santol St.', 'Philippines', '', '0', 'Stafify', 'HRIS', 'Talent Acquisition', '2ab6507d792dcead06a7ea8a8a54bc462a32a725833ae0f4d236ff7b811e16de', 0, 2, 'cdddec93c75e13471babab3b9ee7a66709e9fe59a0d283d7f644c50bc9f5e523.jfif', '', '2025-02-24', 'Headquarters', NULL, NULL, NULL, NULL, 0, 0, NULL, NULL, 0, NULL, NULL, '2025-11-03 02:55:57', '2025-11-03 02:55:57'),
(9, 'renz', '', '', '', 'John Laurenze Leguidleguid', 'Johnlaurenzel@gmail.com', '09273227055', 'Blk 23 Ubas Street Gordon Heights Olongapo City', 'Philippines', 'PH', '0', 'Stafify', 'IT Department', 'Developer', '5994471abb01112afcc18159f6cc74b4f511b99806da59b3caf5a9c173cacfc5', 0, 2, 'b2eb527e465d6268b839a9986e4300b790669a59bfbdf9b18c33c17732b08849.png', '', '2025-02-24', 'Headquarters', NULL, NULL, NULL, NULL, 0, 0, NULL, NULL, 0, NULL, NULL, '2025-11-03 02:55:57', '2025-11-03 02:55:57'),
(11, 'gabriel', '', '', '', 'John Gabriel Orbeta', 'gabrielorbeta11@gmail.com', '09665915026', 'purok 4 banaba st. brgy barretto.', '', '', '0', 'Stafify', 'IT Department', 'Developer', '5994471abb01112afcc18159f6cc74b4f511b99806da59b3caf5a9c173cacfc5', 0, 2, '61c1756b61595dafacb0591833021b175a039ea7501caa00a1639094d393de80.png', '', '2025-02-24', 'Headquarters', NULL, NULL, NULL, NULL, 0, 0, NULL, NULL, 0, NULL, NULL, '2025-11-03 02:55:57', '2025-11-03 02:55:57'),
(14, 'tintin21', '', '', '', 'Justine Dimalanta', 'dimalantajustine8@gmail.com', '', '', '', '', '0', 'Stafify', 'AFIS', 'Project Manager', '5994471abb01112afcc18159f6cc74b4f511b99806da59b3caf5a9c173cacfc5', 0, 2, 'default.png', '', '2025-02-24', 'Headquarters', NULL, NULL, NULL, NULL, 0, 0, NULL, NULL, 0, NULL, NULL, '2025-11-03 02:55:57', '2025-11-03 02:55:57'),
(15, 'Aulwen', '', '', '', 'Aulwen Dizon', 'aulwen126@gmail.com', '09650636585', '#50 18th Street East Bajac-Bajac, Olongapo City', '', '', '0', 'Stafify', 'Sales', 'Relationship Manager', '85143cec233c1bf14db2327de443f23732b7f71086a7558130bb11bf09d6cf4d', 0, 2, 'default.png', '', '2025-04-16', 'Headquarters', NULL, NULL, NULL, NULL, 0, 0, NULL, NULL, 0, NULL, NULL, '2025-11-03 02:55:57', '2025-11-03 02:55:57'),
(16, 'Karu', '', '', '', 'Carl Barrera', '202011828@gordoncollege.edu.ph', '+639468291705', '#8 - 21st Place West Bajac - Bajac, Olongapo City', '', '', '30801', 'Stafify', 'IT Department', 'Developer', '8da3d23dae8c6b9f0ec3593dfdcd4cbe0c3caade0489e816d1cd77c551b0835d', 0, 2, 'default.png', '', '2025-03-07', 'Headquarters', NULL, NULL, NULL, NULL, 0, 0, NULL, NULL, 0, NULL, NULL, '2025-11-03 02:55:57', '2025-11-03 02:55:57'),
(17, 'Lanpher Garcia', '', '', '', 'Lanpher Garcia', 'garcia.lanpher13@gmail.com', '+63 956 618 2903', '31 14th Street, East Tapinac, Olongapo city', '', '', '41103', 'Stafify', 'Unassigned', 'Unassigned', '7a7d9875abeabc56799fa9791a203872c403b2a5ee278e5cf1f76acf3c0d9657', 0, 2, 'default.png', '', '2025-03-10', 'Headquarters', NULL, NULL, NULL, NULL, 0, 0, NULL, NULL, 0, NULL, NULL, '2025-11-03 02:55:57', '2025-11-03 02:55:57'),
(19, 'amilmusa', '', '', '', 'Amil Musa', 'amilmusa03@gmail.com', '09198119666', '20 fontaine East Bajac Bajac Olongapo City', '', '', '406', 'Stafify', 'IT Department', 'Developer', 'ef92b778bafe771e89245b89ecbc08a44a4e166c06659911881f383d4473e94f', 0, 2, 'default.png', '', '2025-04-23', 'Headquarters', NULL, NULL, NULL, NULL, 0, 0, NULL, NULL, 0, NULL, NULL, '2025-11-03 02:55:57', '2025-11-03 02:55:57'),
(24, 'alsheretteramos', '', '', '', 'Alsherette Ramos', 'alsheretteramos@gmail.com', '09647691126', 'Olongapo City', '', '', '1234', 'Stafify', 'IT Department', 'Developer', 'e31b21acfd768e201a5237b089587e7df8aa4c4388979c6491d9db909aaae6a5', 0, 2, 'default.png', '', '2025-02-24', 'Headquarters', NULL, NULL, NULL, NULL, 0, 0, NULL, NULL, 0, NULL, NULL, '2025-11-03 02:55:57', '2025-11-03 02:55:57'),
(26, 'test2', '', '', '', 'test', 'tes@gmail.com', '123456789', '123 Anyway street', '', '', '123456', 'Stafify', 'IT Department', 'Developer', '5994471abb01112afcc18159f6cc74b4f511b99806da59b3caf5a9c173cacfc5', 0, 3, 'default.png', '', '2003-05-05', 'Headquarters', NULL, NULL, NULL, NULL, 0, 0, NULL, NULL, 0, NULL, NULL, '2025-11-03 02:55:57', '2025-11-03 02:55:57'),
(27, 'basicuser', '', '', '', 'Basic User', 'basicuser@gmail.com', '01234567890', '123 Anyway street', '', '', '123456', 'Stafify', 'IT Department', 'Developer', '5994471abb01112afcc18159f6cc74b4f511b99806da59b3caf5a9c173cacfc5', 0, 3, 'default.png', '', '2025-05-05', 'Headquarters', NULL, NULL, NULL, NULL, 0, 0, NULL, NULL, 0, NULL, NULL, '2025-11-03 02:55:57', '2025-11-03 02:55:57'),
(31, 'test153', '', '', '', 'test test', 'test153@gmail.com', '12345', 'test153, Speightstown, Saint Peter, Barbados', 'Barbados', '', '0', 'CompanyTest153', 'Unassigned', 'Unassigned', '5994471abb01112afcc18159f6cc74b4f511b99806da59b3caf5a9c173cacfc5', 0, 2, 'default.png', '', NULL, NULL, NULL, NULL, NULL, NULL, 0, 0, NULL, NULL, 0, NULL, NULL, '2025-11-03 02:55:57', '2025-11-03 02:55:57');

-- --------------------------------------------------------

--
-- Table structure for table `user_deminimis_benefits`
--

CREATE TABLE `user_deminimis_benefits` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Indexes for dumped tables
--

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
-- Indexes for table `company_branches`
--
ALTER TABLE `company_branches`
  ADD PRIMARY KEY (`branch_id`),
  ADD KEY `company_branches_company_id_foreign` (`company_id`);

--
-- Indexes for table `company_profiles`
--
ALTER TABLE `company_profiles`
  ADD PRIMARY KEY (`company_id`);

--
-- Indexes for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Indexes for table `hr_toolkit`
--
ALTER TABLE `hr_toolkit`
  ADD PRIMARY KEY (`id`),
  ADD KEY `hr_toolkit_user_id_foreign` (`user_id`);

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
-- Indexes for table `leave_requests`
--
ALTER TABLE `leave_requests`
  ADD PRIMARY KEY (`id`),
  ADD KEY `leave_requests_employee_email_index` (`employee_email`),
  ADD KEY `leave_requests_status_index` (`status`);

--
-- Indexes for table `legal_toolkit`
--
ALTER TABLE `legal_toolkit`
  ADD PRIMARY KEY (`id`),
  ADD KEY `legal_toolkit_user_id_foreign` (`user_id`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `password_reset_tokens`
--
ALTER TABLE `password_reset_tokens`
  ADD PRIMARY KEY (`email`);

--
-- Indexes for table `payroll_settings`
--
ALTER TABLE `payroll_settings`
  ADD PRIMARY KEY (`id`),
  ADD KEY `payroll_settings_company_id_foreign` (`company_id`);

--
-- Indexes for table `performance_evaluations`
--
ALTER TABLE `performance_evaluations`
  ADD PRIMARY KEY (`id`),
  ADD KEY `performance_evaluations_user_id_foreign` (`user_id`);

--
-- Indexes for table `sales_toolkit`
--
ALTER TABLE `sales_toolkit`
  ADD PRIMARY KEY (`sales_id`),
  ADD KEY `sales_toolkit_user_id_foreign` (`user_id`);

--
-- Indexes for table `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sessions_user_id_index` (`user_id`),
  ADD KEY `sessions_last_activity_index` (`last_activity`);

--
-- Indexes for table `stafify_cmd_settings`
--
ALTER TABLE `stafify_cmd_settings`
  ADD PRIMARY KEY (`setting_id`),
  ADD UNIQUE KEY `stafify_cmd_settings_setting_key_unique` (`setting_key`);

--
-- Indexes for table `stafify_deminimis_benefits`
--
ALTER TABLE `stafify_deminimis_benefits`
  ADD PRIMARY KEY (`id`),
  ADD KEY `stafify_deminimis_benefits_user_id_foreign` (`user_id`);

--
-- Indexes for table `stafify_departments`
--
ALTER TABLE `stafify_departments`
  ADD PRIMARY KEY (`department_id`);

--
-- Indexes for table `stafify_events`
--
ALTER TABLE `stafify_events`
  ADD PRIMARY KEY (`event_id`),
  ADD KEY `stafify_events_created_by_foreign` (`created_by`);

--
-- Indexes for table `stafify_fringe_benefits`
--
ALTER TABLE `stafify_fringe_benefits`
  ADD PRIMARY KEY (`id`),
  ADD KEY `stafify_fringe_benefits_user_id_foreign` (`user_id`);

--
-- Indexes for table `stafify_overtime`
--
ALTER TABLE `stafify_overtime`
  ADD PRIMARY KEY (`ot_id`),
  ADD KEY `stafify_overtime_user_id_foreign` (`user_id`),
  ADD KEY `stafify_overtime_shift_id_foreign` (`shift_id`),
  ADD KEY `stafify_overtime_approved_by_foreign` (`approved_by`);

--
-- Indexes for table `stafify_payrolls`
--
ALTER TABLE `stafify_payrolls`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `stafify_positions`
--
ALTER TABLE `stafify_positions`
  ADD PRIMARY KEY (`position_id`),
  ADD UNIQUE KEY `stafify_positions_position_name_unique` (`position_name`);

--
-- Indexes for table `stafify_settings`
--
ALTER TABLE `stafify_settings`
  ADD PRIMARY KEY (`setting_id`),
  ADD UNIQUE KEY `stafify_settings_company_setting_key_unique` (`company`,`setting_key`);

--
-- Indexes for table `stafify_shifts`
--
ALTER TABLE `stafify_shifts`
  ADD PRIMARY KEY (`shift_id`),
  ADD KEY `stafify_shifts_user_id_foreign` (`user_id`),
  ADD KEY `stafify_shifts_assigned_by_foreign` (`assigned_by`);

--
-- Indexes for table `stafify_time_tracking`
--
ALTER TABLE `stafify_time_tracking`
  ADD PRIMARY KEY (`record_id`),
  ADD KEY `stafify_time_tracking_shift_id_foreign` (`shift_id`),
  ADD KEY `stafify_time_tracking_user_id_foreign` (`user_id`);

--
-- Indexes for table `stafify_users`
--
ALTER TABLE `stafify_users`
  ADD PRIMARY KEY (`user_id`);

--
-- Indexes for table `stafify_user_rates`
--
ALTER TABLE `stafify_user_rates`
  ADD PRIMARY KEY (`rate_id`),
  ADD KEY `stafify_user_rates_user_id_foreign` (`user_id`);

--
-- Indexes for table `talent_management_resources`
--
ALTER TABLE `talent_management_resources`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `talent_management_resources_resource_key_unique` (`resource_key`);

--
-- Indexes for table `talent_toolkit`
--
ALTER TABLE `talent_toolkit`
  ADD PRIMARY KEY (`id`),
  ADD KEY `talent_toolkit_user_id_foreign` (`user_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`),
  ADD UNIQUE KEY `users_user_name_unique` (`user_name`),
  ADD UNIQUE KEY `users_user_email_unique` (`user_email`);

--
-- Indexes for table `user_deminimis_benefits`
--
ALTER TABLE `user_deminimis_benefits`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `company_branches`
--
ALTER TABLE `company_branches`
  MODIFY `branch_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `company_profiles`
--
ALTER TABLE `company_profiles`
  MODIFY `company_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `hr_toolkit`
--
ALTER TABLE `hr_toolkit`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `jobs`
--
ALTER TABLE `jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `leave_requests`
--
ALTER TABLE `leave_requests`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `legal_toolkit`
--
ALTER TABLE `legal_toolkit`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

--
-- AUTO_INCREMENT for table `payroll_settings`
--
ALTER TABLE `payroll_settings`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `performance_evaluations`
--
ALTER TABLE `performance_evaluations`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `sales_toolkit`
--
ALTER TABLE `sales_toolkit`
  MODIFY `sales_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `stafify_cmd_settings`
--
ALTER TABLE `stafify_cmd_settings`
  MODIFY `setting_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `stafify_deminimis_benefits`
--
ALTER TABLE `stafify_deminimis_benefits`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `stafify_departments`
--
ALTER TABLE `stafify_departments`
  MODIFY `department_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `stafify_events`
--
ALTER TABLE `stafify_events`
  MODIFY `event_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `stafify_fringe_benefits`
--
ALTER TABLE `stafify_fringe_benefits`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `stafify_overtime`
--
ALTER TABLE `stafify_overtime`
  MODIFY `ot_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `stafify_positions`
--
ALTER TABLE `stafify_positions`
  MODIFY `position_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `stafify_settings`
--
ALTER TABLE `stafify_settings`
  MODIFY `setting_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `stafify_shifts`
--
ALTER TABLE `stafify_shifts`
  MODIFY `shift_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=86;

--
-- AUTO_INCREMENT for table `stafify_time_tracking`
--
ALTER TABLE `stafify_time_tracking`
  MODIFY `record_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=108;

--
-- AUTO_INCREMENT for table `stafify_users`
--
ALTER TABLE `stafify_users`
  MODIFY `user_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=28;

--
-- AUTO_INCREMENT for table `stafify_user_rates`
--
ALTER TABLE `stafify_user_rates`
  MODIFY `rate_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `talent_management_resources`
--
ALTER TABLE `talent_management_resources`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `talent_toolkit`
--
ALTER TABLE `talent_toolkit`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=32;

--
-- AUTO_INCREMENT for table `user_deminimis_benefits`
--
ALTER TABLE `user_deminimis_benefits`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `company_branches`
--
ALTER TABLE `company_branches`
  ADD CONSTRAINT `company_branches_company_id_foreign` FOREIGN KEY (`company_id`) REFERENCES `company_profiles` (`company_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `hr_toolkit`
--
ALTER TABLE `hr_toolkit`
  ADD CONSTRAINT `hr_toolkit_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE;

--
-- Constraints for table `legal_toolkit`
--
ALTER TABLE `legal_toolkit`
  ADD CONSTRAINT `legal_toolkit_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE;

--
-- Constraints for table `payroll_settings`
--
ALTER TABLE `payroll_settings`
  ADD CONSTRAINT `payroll_settings_company_id_foreign` FOREIGN KEY (`company_id`) REFERENCES `company_profiles` (`company_id`);

--
-- Constraints for table `performance_evaluations`
--
ALTER TABLE `performance_evaluations`
  ADD CONSTRAINT `performance_evaluations_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `stafify_users` (`user_id`);

--
-- Constraints for table `sales_toolkit`
--
ALTER TABLE `sales_toolkit`
  ADD CONSTRAINT `sales_toolkit_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE;

--
-- Constraints for table `stafify_deminimis_benefits`
--
ALTER TABLE `stafify_deminimis_benefits`
  ADD CONSTRAINT `stafify_deminimis_benefits_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `stafify_users` (`user_id`);

--
-- Constraints for table `stafify_events`
--
ALTER TABLE `stafify_events`
  ADD CONSTRAINT `stafify_events_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `stafify_users` (`user_id`);

--
-- Constraints for table `stafify_fringe_benefits`
--
ALTER TABLE `stafify_fringe_benefits`
  ADD CONSTRAINT `stafify_fringe_benefits_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `stafify_users` (`user_id`);

--
-- Constraints for table `stafify_overtime`
--
ALTER TABLE `stafify_overtime`
  ADD CONSTRAINT `stafify_overtime_approved_by_foreign` FOREIGN KEY (`approved_by`) REFERENCES `stafify_users` (`user_id`),
  ADD CONSTRAINT `stafify_overtime_shift_id_foreign` FOREIGN KEY (`shift_id`) REFERENCES `stafify_shifts` (`shift_id`),
  ADD CONSTRAINT `stafify_overtime_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `stafify_users` (`user_id`);

--
-- Constraints for table `stafify_shifts`
--
ALTER TABLE `stafify_shifts`
  ADD CONSTRAINT `stafify_shifts_assigned_by_foreign` FOREIGN KEY (`assigned_by`) REFERENCES `stafify_users` (`user_id`),
  ADD CONSTRAINT `stafify_shifts_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `stafify_users` (`user_id`);

--
-- Constraints for table `stafify_time_tracking`
--
ALTER TABLE `stafify_time_tracking`
  ADD CONSTRAINT `stafify_time_tracking_shift_id_foreign` FOREIGN KEY (`shift_id`) REFERENCES `stafify_shifts` (`shift_id`),
  ADD CONSTRAINT `stafify_time_tracking_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `stafify_users` (`user_id`);

--
-- Constraints for table `stafify_user_rates`
--
ALTER TABLE `stafify_user_rates`
  ADD CONSTRAINT `stafify_user_rates_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `stafify_users` (`user_id`) ON DELETE CASCADE;

--
-- Constraints for table `talent_toolkit`
--
ALTER TABLE `talent_toolkit`
  ADD CONSTRAINT `talent_toolkit_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
