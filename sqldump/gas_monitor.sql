-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 20, 2025 at 09:18 AM
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
-- Database: `gas_monitor-fix1`
--

-- --------------------------------------------------------

--
-- Table structure for table `devices`
--

CREATE TABLE `devices` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `room_name` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `sensors` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `devices`
--

INSERT INTO `devices` (`id`, `room_name`, `name`, `token`, `created_at`, `updated_at`, `sensors`) VALUES
(2, 'Laboratorium UPT Plasma-Catalysis', 'Raspberry Pi 4B (Propane Only)', 'Se55D97JrVNSVoqA0ZbYOhpI0NSC8g19', '2025-05-13 05:14:05', '2025-05-13 05:14:05', '\"[\\\"mq6\\\"]\"'),
(3, 'Laboratorium UPT Plasma-Catalysis', 'Raspberry Pi 4B', '5pH2Zavrfu6y0RUXjYILjKkVKpyVVole', '2025-05-13 05:46:15', '2025-05-13 05:46:15', '\"[\\\"mq6\\\",\\\"mq8\\\"]\"');

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
(1, '2014_10_12_000000_create_users_table', 1),
(2, '2014_10_12_100000_create_password_reset_tokens_table', 1),
(3, '2019_08_19_000000_create_failed_jobs_table', 1),
(4, '2019_12_14_000001_create_personal_access_tokens_table', 1),
(5, '2025_02_18_085116_add_status_to_users_table', 1),
(6, '2025_02_20_060358_create_verifications_table', 1),
(7, '2025_02_20_111746_add_whatsapp_number_to_users', 1),
(8, '2025_02_21_150003_create_whats_app_notifications_table', 1),
(9, '2025_02_22_161448_create_devices_table', 1),
(10, '2025_02_22_162621_sensor_data', 1),
(11, '2025_02_22_173707_add_sensors_to_devices_table', 1),
(12, '2025_02_25_113647_add_role_to_users_table', 1),
(13, '2025_04_29_224010_add_room_name_to_devices_table', 1),
(14, '2025_04_29_235111_create_rooms_table', 1),
(15, '2025_05_06_134907_add_gas_alert_to_sensor_data_table', 1),
(16, '2025_05_07_151912_add_dismissed_alerts_to_sensor_data_table', 1),
(17, '2025_05_13_121235_modify_nullable_columns_in_sensor_data_table', 2);

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
-- Table structure for table `personal_access_tokens`
--

CREATE TABLE `personal_access_tokens` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `tokenable_type` varchar(255) NOT NULL,
  `tokenable_id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `token` varchar(64) NOT NULL,
  `abilities` text DEFAULT NULL,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `expires_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `sensor_data`
--

CREATE TABLE `sensor_data` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `device_id` bigint(20) UNSIGNED NOT NULL,
  `mq6_value` double(8,2) DEFAULT NULL,
  `mq8_value` double(8,2) DEFAULT NULL,
  `gas_alert` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `dismissed_alerts` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT '[]' CHECK (json_valid(`dismissed_alerts`))
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `sensor_data`
--

INSERT INTO `sensor_data` (`id`, `device_id`, `mq6_value`, `mq8_value`, `gas_alert`, `created_at`, `updated_at`, `deleted_at`, `dismissed_alerts`) VALUES
(7, 2, 200.00, NULL, NULL, '2025-05-13 05:17:07', '2025-05-13 05:17:07', NULL, '[]'),
(8, 2, 250.00, NULL, NULL, '2025-05-13 05:17:17', '2025-05-13 05:17:17', NULL, '[]'),
(9, 2, 400.00, NULL, 'Gas Propane/Butane Gas Terdeteksi.', '2025-05-13 05:17:24', '2025-05-13 05:17:27', NULL, '[]'),
(13, 3, 450.00, 450.00, 'Gas Propane/Butane Gas Terdeteksi.\n\nGas Hydrogen Gas Terdeteksi.', '2025-05-13 05:46:26', '2025-05-13 05:46:29', NULL, '[]'),
(14, 3, 400.00, 400.00, 'Gas Propane/Butane Gas Terdeteksi.\n\nGas Hydrogen Gas Terdeteksi.', '2025-05-13 05:52:38', '2025-05-13 05:52:41', NULL, '[]'),
(15, 3, 300.00, 200.00, 'Gas Propane/Butane Gas Terdeteksi.', '2025-05-13 06:05:57', '2025-05-13 06:06:00', NULL, '[]'),
(16, 3, 100.00, 150.00, NULL, '2025-05-13 06:43:51', '2025-05-13 06:43:51', NULL, '[]'),
(17, 3, 250.00, 300.00, 'Gas Hydrogen Gas Terdeteksi.', '2025-05-13 07:31:23', '2025-05-13 07:31:28', NULL, '[]'),
(18, 3, 200.00, 150.00, NULL, '2025-05-13 07:38:27', '2025-05-13 07:38:27', NULL, '[]'),
(19, 3, 143.00, 231.00, NULL, '2025-05-13 07:39:06', '2025-05-13 07:39:06', NULL, '[]'),
(20, 3, 443.00, 654.00, 'Gas Propane/Butane Gas Terdeteksi.\n\nGas Hydrogen Gas Terdeteksi.', '2025-05-13 07:42:16', '2025-05-13 07:42:18', NULL, '[]'),
(21, 3, 323.00, 545.00, 'Gas Propane/Butane Gas Terdeteksi.\n\nGas Hydrogen Gas Terdeteksi.', '2025-05-13 08:00:12', '2025-05-13 08:00:17', NULL, '[]'),
(22, 3, 323.00, 545.00, 'Gas Propane/Butane Gas Terdeteksi.\n\nGas Hydrogen Gas Terdeteksi.', '2025-05-13 15:02:31', '2025-05-13 15:02:33', NULL, '[]'),
(23, 3, 10000.00, 10000.00, 'Gas Propane/Butane Gas Terdeteksi.\n\nGas Hydrogen Gas Terdeteksi.', '2025-05-20 06:56:35', '2025-05-20 06:56:39', NULL, '[]');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `phone_number` varchar(255) DEFAULT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `status` enum('verify','active','banned') NOT NULL,
  `password` varchar(255) NOT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `role` varchar(255) NOT NULL DEFAULT 'user'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `phone_number`, `email_verified_at`, `status`, `password`, `remember_token`, `created_at`, `updated_at`, `role`) VALUES
(1, 'Ridwan Firdaus', 'ridwanistadi22@gmail.com', '+6287832061627', NULL, 'active', '$2y$10$VSNWJeseKzDgYEhxcRxmLeLWnEcqd3ql/0gY57rzfHjzFzf6qhPeK', NULL, '2025-05-13 04:31:38', '2025-05-13 04:31:38', 'admin');

-- --------------------------------------------------------

--
-- Table structure for table `verifications`
--

CREATE TABLE `verifications` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `unique_id` varchar(255) NOT NULL,
  `otp` varchar(255) NOT NULL,
  `type` enum('register','reset_password') NOT NULL,
  `send_via` enum('email','sms','wa') NOT NULL,
  `resend` int(11) NOT NULL DEFAULT 0,
  `status` enum('active','valid','invalid') NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `whats_app_notifications`
--

CREATE TABLE `whats_app_notifications` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `recipient_number` varchar(255) NOT NULL,
  `message` text NOT NULL,
  `status` enum('pending','sent','failed') NOT NULL DEFAULT 'pending',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `whats_app_notifications`
--

INSERT INTO `whats_app_notifications` (`id`, `user_id`, `recipient_number`, `message`, `status`, `created_at`, `updated_at`) VALUES
(1, 1, '+6287832061627', '👋 Halo, *Ridwan Firdaus*! \n\nUntuk menerima notifikasi WhatsApp dari sistem kami, silakan lakukan langkah berikut:\n\n1️⃣ Buka WhatsApp Anda.\n2️⃣ Kirim pesan *JOIN gain-basic* ke *+1 415 523 8886*.\n\nSetelah itu, Anda akan mulai menerima notifikasi otomatis dari sistem kami. ✅', 'failed', '2025-05-13 04:31:40', '2025-05-13 04:31:40'),
(2, 1, '+6287832061627', '👋 Selamat datang, Ridwan Firdaus!\n\nAkun Anda telah berhasil terdaftar.\nKami akan mengirimkan notifikasi jika ada bahaya gas.\n\n🔥 Tetap aman dan waspada!', 'failed', '2025-05-13 04:31:40', '2025-05-13 04:31:41'),
(3, 1, '+6287832061627', '👋 Halo, *Ridwan Firdaus*! \n\nUntuk menerima notifikasi WhatsApp dari sistem kami, silakan lakukan langkah berikut:\n\n1️⃣ Buka WhatsApp Anda.\n2️⃣ Kirim pesan *JOIN gain-basic* ke *+1 415 523 8886*.\n\nSetelah itu, Anda akan mulai menerima notifikasi otomatis dari sistem kami. ✅', 'failed', '2025-05-13 04:31:41', '2025-05-13 04:31:41'),
(4, 1, '+6287832061627', '👋 Selamat datang, Ridwan Firdaus!\n\nAkun Anda telah berhasil terdaftar.\nKami akan mengirimkan notifikasi jika ada bahaya gas.\n\n🔥 Tetap aman dan waspada!', 'failed', '2025-05-13 04:31:41', '2025-05-13 04:31:41');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `devices`
--
ALTER TABLE `devices`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `devices_token_unique` (`token`);

--
-- Indexes for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

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
-- Indexes for table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  ADD KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`);

--
-- Indexes for table `sensor_data`
--
ALTER TABLE `sensor_data`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sensor_data_device_id_foreign` (`device_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`);

--
-- Indexes for table `verifications`
--
ALTER TABLE `verifications`
  ADD PRIMARY KEY (`id`),
  ADD KEY `verifications_user_id_foreign` (`user_id`);

--
-- Indexes for table `whats_app_notifications`
--
ALTER TABLE `whats_app_notifications`
  ADD PRIMARY KEY (`id`),
  ADD KEY `whats_app_notifications_user_id_foreign` (`user_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `devices`
--
ALTER TABLE `devices`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT for table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `sensor_data`
--
ALTER TABLE `sensor_data`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `verifications`
--
ALTER TABLE `verifications`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `whats_app_notifications`
--
ALTER TABLE `whats_app_notifications`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `sensor_data`
--
ALTER TABLE `sensor_data`
  ADD CONSTRAINT `sensor_data_device_id_foreign` FOREIGN KEY (`device_id`) REFERENCES `devices` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `verifications`
--
ALTER TABLE `verifications`
  ADD CONSTRAINT `verifications_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `whats_app_notifications`
--
ALTER TABLE `whats_app_notifications`
  ADD CONSTRAINT `whats_app_notifications_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
