-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 27-06-2025 a las 19:15:29
-- Versión del servidor: 10.4.32-MariaDB
-- Versión de PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `clinic`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `appointments`
--

CREATE TABLE `appointments` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `patient_id` bigint(20) UNSIGNED NOT NULL,
  `doctor_id` bigint(20) UNSIGNED NOT NULL,
  `treatment_id` bigint(20) UNSIGNED NOT NULL,
  `date` date NOT NULL,
  `time` time NOT NULL,
  `notes` text DEFAULT NULL,
  `status` varchar(50) DEFAULT NULL,
  `cost` decimal(8,2) DEFAULT NULL,
  `paid` decimal(10,2) DEFAULT 0.00,
  `diagnosis` varchar(255) DEFAULT NULL,
  `is_active` tinyint(1) DEFAULT 1,
  `is_deleted` tinyint(1) DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `appointments`
--

INSERT INTO `appointments` (`id`, `patient_id`, `doctor_id`, `treatment_id`, `date`, `time`, `notes`, `status`, `cost`, `paid`, `diagnosis`, `is_active`, `is_deleted`, `created_at`, `updated_at`) VALUES
(1, 4, 3, 2, '2025-12-12', '12:12:00', '', 'atendido', 120.00, 20.00, 'Caries', 0, 1, NULL, '2025-06-26 20:11:55'),
(2, 4, 3, 2, '2025-12-12', '12:02:00', '', 'asignado', 100.00, 20.00, 'Caries', 0, 1, '2025-06-26 20:32:43', '2025-06-26 20:32:52'),
(3, 4, 3, 2, '2025-12-02', '12:02:00', '', 'asignado', 200.00, 20.00, 'Caries', 0, 1, '2025-06-26 20:33:24', NULL),
(4, 4, 3, 2, '2025-02-01', '20:18:00', '', 'asignado', 100.00, 20.00, 'Caries', 0, 1, '2025-06-26 20:41:01', '2025-06-26 20:43:30'),
(5, 4, 3, 2, '2025-12-12', '12:02:00', '', 'atendido', 100.00, 100.00, 'Caries', 1, 0, '2025-06-26 20:43:58', '2025-06-27 03:57:13'),
(6, 4, 3, 2, '2025-11-02', '22:02:00', '', 'atendido', 199.99, 20.00, 'Caries', 0, 1, '2025-06-26 23:01:54', NULL),
(8, 4, 6, 2, '2025-01-12', '12:12:00', '', 'asignado', 100.00, 0.00, 'Caries', 1, 0, '2025-06-27 16:08:55', NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `appointment_history`
--

CREATE TABLE `appointment_history` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `patient_id` bigint(20) UNSIGNED NOT NULL,
  `appointment_id` bigint(20) UNSIGNED NOT NULL,
  `details` text DEFAULT NULL,
  `is_active` tinyint(1) DEFAULT 1,
  `is_deleted` tinyint(1) DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `appointment_history`
--

INSERT INTO `appointment_history` (`id`, `patient_id`, `appointment_id`, `details`, `is_active`, `is_deleted`, `created_at`, `updated_at`) VALUES
(1, 4, 1, 'Caries', 1, 0, '2025-06-26 20:06:43', NULL),
(2, 4, 5, 'Caries', 1, 0, '2025-06-27 03:57:13', NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `calendars`
--

CREATE TABLE `calendars` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `title` varchar(255) NOT NULL,
  `start_date` date NOT NULL,
  `end_date` date NOT NULL,
  `description` text DEFAULT NULL,
  `is_active` tinyint(1) DEFAULT 1,
  `is_deleted` tinyint(1) DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `clinic_settings`
--

CREATE TABLE `clinic_settings` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `clinic_name` varchar(255) NOT NULL,
  `address` varchar(255) DEFAULT NULL,
  `phone` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `logo` varchar(255) DEFAULT NULL,
  `opening_time` time DEFAULT NULL,
  `closing_time` time DEFAULT NULL,
  `is_active` tinyint(1) DEFAULT 1,
  `is_deleted` tinyint(1) DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `doctors`
--

CREATE TABLE `doctors` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `first_name` varchar(255) NOT NULL,
  `last_name` varchar(255) NOT NULL,
  `dni` varchar(8) NOT NULL,
  `email` varchar(255) NOT NULL,
  `phone` varchar(255) DEFAULT NULL,
  `address` varchar(255) DEFAULT NULL,
  `specialty_id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `is_active` tinyint(1) DEFAULT 1,
  `is_deleted` tinyint(1) DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `doctors`
--

INSERT INTO `doctors` (`id`, `first_name`, `last_name`, `dni`, `email`, `phone`, `address`, `specialty_id`, `user_id`, `is_active`, `is_deleted`, `created_at`, `updated_at`) VALUES
(2, 'Jaime', 'NBK', '123456', 'jaime7936824@gmail.com', '922920517', '7936', 5, NULL, 0, 1, '2025-06-26 18:47:35', NULL),
(3, 'Juan', 'Ramirez', '7070702', 'jramirez@gmail.com', '1233444', '7936', 5, NULL, 1, 0, '2025-06-26 19:08:45', NULL),
(5, 'Jaime', 'NBK', '3213123', 'jaim312312e7936824@gmail.com', '31231231', '7936222', 5, NULL, 0, 1, '2025-06-27 03:09:40', NULL),
(6, 'Jaime', 'Guti', '56453456', 'jgutii@gmail.com', '31231231', '7936', 5, 15, 1, 0, '2025-06-27 15:09:11', NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `odontograms`
--

CREATE TABLE `odontograms` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `patient_id` bigint(20) UNSIGNED NOT NULL,
  `piece` varchar(255) NOT NULL,
  `zone` varchar(20) DEFAULT NULL,
  `treatment_id` bigint(20) UNSIGNED DEFAULT NULL,
  `action` char(1) DEFAULT NULL,
  `status` varchar(255) NOT NULL,
  `observations` text DEFAULT NULL,
  `background_color` varchar(20) DEFAULT NULL,
  `symbol` varchar(5) DEFAULT NULL,
  `symbol_color` varchar(20) DEFAULT NULL,
  `is_active` tinyint(1) DEFAULT 1,
  `is_deleted` tinyint(1) DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `patients`
--

CREATE TABLE `patients` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `document_type` varchar(20) DEFAULT NULL,
  `first_name` varchar(255) NOT NULL,
  `last_name` varchar(100) DEFAULT NULL,
  `dni` varchar(255) NOT NULL,
  `email` varchar(255) DEFAULT NULL,
  `phone` varchar(255) DEFAULT NULL,
  `medical_history` varchar(255) DEFAULT NULL,
  `under_treatment` tinyint(1) NOT NULL DEFAULT 0,
  `bleeding` tinyint(1) NOT NULL DEFAULT 0,
  `allergy` tinyint(1) NOT NULL DEFAULT 0,
  `hypertensive` tinyint(1) NOT NULL DEFAULT 0,
  `diabetic` tinyint(1) NOT NULL DEFAULT 0,
  `pregnant` tinyint(1) NOT NULL DEFAULT 0,
  `reason` text DEFAULT NULL,
  `diagnosis` text DEFAULT NULL,
  `observations` text DEFAULT NULL,
  `referred_by` varchar(255) DEFAULT NULL,
  `gender` enum('M','F') DEFAULT NULL,
  `birth_date` date DEFAULT NULL,
  `address` varchar(255) DEFAULT NULL,
  `is_active` tinyint(1) DEFAULT 1,
  `is_deleted` tinyint(1) DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `patients`
--

INSERT INTO `patients` (`id`, `user_id`, `document_type`, `first_name`, `last_name`, `dni`, `email`, `phone`, `medical_history`, `under_treatment`, `bleeding`, `allergy`, `hypertensive`, `diabetic`, `pregnant`, `reason`, `diagnosis`, `observations`, `referred_by`, `gender`, `birth_date`, `address`, `is_active`, `is_deleted`, `created_at`, `updated_at`) VALUES
(4, NULL, 'DNI', 'Luis', NULL, '123456', 'jramrre@gmail.com', '7936824', '', 0, 0, 0, 0, 0, 0, '', '', '', '', NULL, NULL, NULL, 1, 0, NULL, NULL),
(5, NULL, 'DNI', 'Juan', NULL, '12345678', 'jm@gmail.com', '999444555', '', 0, 0, 1, 0, 1, 0, '', '', '', 'Jaime', NULL, NULL, NULL, 0, 1, NULL, NULL),
(6, NULL, 'DNI', 'Martin', NULL, '21312312', 'jaime7936824@gmail.com', '999444555', '', 0, 1, 1, 1, 0, 0, '', '', '', '', NULL, NULL, NULL, 0, 1, NULL, NULL),
(7, NULL, 'DNI', 'Jaime', NULL, '872777777', 'jaime7936824@gmail.com', '7936824', '', 1, 0, 0, 0, 0, 0, '', '', '', '', NULL, NULL, NULL, 0, 1, NULL, NULL),
(8, NULL, 'DNI', 'Luisss', NULL, '1213123', 'jaim2e7936824@gmail.com', '999444555', '', 0, 0, 0, 0, 0, 0, '', '', '', '', NULL, NULL, NULL, 0, 1, NULL, NULL),
(9, NULL, 'DNI', 'Luisss', NULL, '222322', 'jaime7ss936824@gmail.com', '999444555', '', 0, 0, 0, 0, 0, 1, '', '', '', '', NULL, NULL, NULL, 0, 1, NULL, NULL),
(10, NULL, 'DNI', 'Juan', NULL, '87777777', 'juanr@gmail.com', '123213', '', 0, 0, 1, 0, 0, 0, '', '', '', '', NULL, NULL, NULL, 1, 0, NULL, NULL),
(11, NULL, 'DNI', 'Juan Miguel', NULL, '222222', 'mieguelitojr@gmail.com', '222222', '', 0, 0, 1, 1, 0, 0, '', '', '', '', NULL, NULL, NULL, 1, 0, NULL, NULL),
(12, 12, 'DNI', 'Juan', 'Pérez', '123456738', 'juan.perez2@example.com', '987654321', 'Sin antecedentes', 0, 0, 0, 0, 0, 0, 'Dolor dental', 'Caries dental', 'Se observó caries en molar derecho', 'Dr. María', 'M', '1995-03-12', 'Av. Salud 123', 1, 0, '2025-06-27 14:51:34', NULL),
(13, 13, 'DNI', 'Lucas Ramirez', '', '2121312312', 'wwww@gmail.com', '123212312312', NULL, 0, 0, 0, 0, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, 0, '2025-06-27 14:56:10', NULL),
(14, 14, 'DNI', 'Jose Manuel', NULL, '2323434', 'manuelsaenza@gmail.com', '4535345', '', 0, 1, 0, 1, 1, 0, '', '', '', '', NULL, NULL, NULL, 1, 0, NULL, NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `payments`
--

CREATE TABLE `payments` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `appointment_id` bigint(20) UNSIGNED NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `payment_method` varchar(50) DEFAULT 'cash',
  `payment_date` datetime DEFAULT current_timestamp(),
  `payment_status` varchar(20) DEFAULT 'pending',
  `details` text DEFAULT NULL,
  `is_active` tinyint(1) DEFAULT 1,
  `is_deleted` tinyint(1) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `payments`
--

INSERT INTO `payments` (`id`, `appointment_id`, `amount`, `payment_method`, `payment_date`, `payment_status`, `details`, `is_active`, `is_deleted`, `created_at`, `updated_at`) VALUES
(1, 5, 100.00, 'efectivo', '2025-06-26 22:57:13', 'pagado', NULL, 1, 0, '2025-06-26 20:43:58', '2025-06-27 03:57:13'),
(2, 6, 20.00, 'efectivo', '2025-06-26 18:01:54', 'completado', NULL, 1, 0, '2025-06-26 23:01:54', '2025-06-26 23:01:54');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `specialties`
--

CREATE TABLE `specialties` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `is_active` tinyint(1) DEFAULT 1,
  `is_deleted` tinyint(1) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `specialties`
--

INSERT INTO `specialties` (`id`, `name`, `description`, `is_active`, `is_deleted`, `created_at`, `updated_at`) VALUES
(5, 'Prueba', '', 1, 0, '2025-06-26 18:41:46', '2025-06-26 18:41:46'),
(6, 'Prueba 2', '', 0, 1, '2025-06-26 18:48:49', '2025-06-26 18:48:53'),
(9, 'ASDASDe', '', 0, 1, '2025-06-27 02:00:18', '2025-06-27 02:06:49'),
(10, '1231321313123', '', 0, 1, '2025-06-27 02:20:28', '2025-06-27 02:20:38'),
(11, 'asdasddasd', '', 0, 1, '2025-06-27 09:55:56', '2025-06-27 09:56:07'),
(12, 'Limpieza General', '', 1, 0, '2025-06-27 16:43:03', '2025-06-27 16:43:15');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `teeth`
--

CREATE TABLE `teeth` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `patient_id` bigint(20) UNSIGNED NOT NULL,
  `appointment_id` bigint(20) UNSIGNED DEFAULT NULL,
  `piece` varchar(10) NOT NULL,
  `zone` varchar(10) DEFAULT NULL,
  `treatment_id` bigint(20) UNSIGNED DEFAULT NULL,
  `action` varchar(50) DEFAULT NULL,
  `observations` text DEFAULT NULL,
  `status` varchar(50) DEFAULT NULL,
  `background_color` varchar(20) DEFAULT NULL,
  `symbol` varchar(10) DEFAULT NULL,
  `symbol_color` varchar(20) DEFAULT NULL,
  `is_active` tinyint(1) DEFAULT 1,
  `is_deleted` tinyint(1) DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `teeth`
--

INSERT INTO `teeth` (`id`, `patient_id`, `appointment_id`, `piece`, `zone`, `treatment_id`, `action`, `observations`, `status`, `background_color`, `symbol`, `symbol_color`, `is_active`, `is_deleted`, `created_at`, `updated_at`) VALUES
(1, 4, 5, '17', 'centro', 1, 'X', '', NULL, 'red', '', '', 1, 0, '2025-06-26 22:25:41', '2025-06-26 22:25:41'),
(2, 4, 5, '16', 'superior', 1, 'E', '', NULL, 'blue', '', '', 1, 0, '2025-06-26 22:25:44', '2025-06-26 22:25:44'),
(3, 4, 6, '61', 'centro', 1, 'X', '', NULL, 'red', '', '', 1, 0, '2025-06-27 06:06:18', '2025-06-27 06:11:34'),
(5, 4, 6, '62', 'centro', 1, 'X', '', NULL, '', 'X', 'blue', 1, 0, '2025-06-27 06:11:41', '2025-06-27 06:14:02'),
(7, 4, 6, '65', 'centro', 1, 'E', '', NULL, 'blue', 'S', 'red', 1, 0, '2025-06-27 06:17:30', '2025-06-27 06:17:30'),
(8, 4, 6, '12', 'centro', 1, 'C', '', NULL, 'yellow', 'X', 'red', 1, 0, '2025-06-27 09:22:02', '2025-06-27 09:22:02'),
(9, 4, 6, '21', 'centro', 1, 'X', '', NULL, 'red', 'O', 'blue', 1, 0, '2025-06-27 09:26:54', '2025-06-27 09:26:54'),
(10, 4, 6, '22', 'superior', 1, 'C', '', NULL, 'yellow', '', '', 1, 0, '2025-06-27 09:28:05', '2025-06-27 09:28:05'),
(11, 4, 8, '17', 'superior', 1, 'X', '', NULL, 'red', '', '', 1, 0, '2025-06-27 16:43:34', '2025-06-27 16:43:34'),
(12, 4, 8, '14', 'centro', 1, 'E', '', NULL, 'blue', 'O', 'red', 1, 0, '2025-06-27 16:43:39', '2025-06-27 16:43:39');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `treatments`
--

CREATE TABLE `treatments` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `price` decimal(8,2) NOT NULL,
  `is_active` tinyint(1) DEFAULT 1,
  `is_deleted` tinyint(1) DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `treatments`
--

INSERT INTO `treatments` (`id`, `name`, `description`, `price`, `is_active`, `is_deleted`, `created_at`, `updated_at`) VALUES
(1, 'Pruebas', NULL, 100.00, 1, 1, '2025-06-26 18:24:15', '2025-06-26 18:24:26'),
(2, 'Probando', NULL, 120.00, 1, 0, '2025-06-26 19:24:00', NULL),
(3, 'PRUEBAS', NULL, 120.00, 1, 1, '2025-06-27 03:30:38', '2025-06-27 03:32:26'),
(4, 'adasdas', NULL, 110.00, 1, 1, '2025-06-27 03:32:43', '2025-06-27 03:32:52'),
(5, 'siuuuu', NULL, 100.00, 1, 1, '2025-06-27 03:44:05', '2025-06-27 03:44:16');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `role` varchar(255) NOT NULL DEFAULT 'patient',
  `document_type` varchar(255) DEFAULT NULL,
  `document_number` varchar(255) DEFAULT NULL,
  `phone` varchar(255) DEFAULT NULL,
  `photo` varchar(255) DEFAULT NULL,
  `is_active` tinyint(1) DEFAULT 1,
  `is_deleted` tinyint(1) DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `email_verified_at`, `password`, `remember_token`, `role`, `document_type`, `document_number`, `phone`, `photo`, `is_active`, `is_deleted`, `created_at`, `updated_at`) VALUES
(1, 'Admin Principale', 'admin@clinic.com', NULL, '$2y$10$M9aFmAw1Pxu1O0IINdJ70e1y1e0aOkHFXz3wEYa9Flc2hFlkkVayq', NULL, 'admin', 'DNI', '00000000', '999999999', NULL, 1, 0, '2025-06-26 15:48:56', '2025-06-26 15:48:56'),
(3, 'Admin Prueba', 'nuevoadmin@clinic.com', NULL, '$2y$10$ix6ss5HZ07OD1WhMxQgUPO6WXFQvcURuF8kUwMp3w/knLgVr9TQRS', NULL, 'admin', 'DNI', '12345678', '987654321', NULL, 1, 0, '2025-06-26 23:37:50', '2025-06-26 23:37:50'),
(4, 'Jaime NBK', 'jaime7936824@gmail.com', NULL, '', NULL, 'patient', 'DNI', '2131231312', '31231231', NULL, 1, 0, NULL, NULL),
(6, 'Admin Test', 'admintest@clinic.com', NULL, '$2y$10$L0ErT3K2bLknGsbKkl0gFuh3Fc.qBvMokA9Hx9OyZmXUpAvA2Seae', NULL, 'admin', 'DNI', '11223344', '987654321', '1751016571_cueva.jpg', 0, 1, '2025-06-27 09:08:21', '2025-06-27 09:08:21'),
(7, 'Usuario Prueba', 'ee936824@gmail.com', NULL, '$2y$10$pPBNiPwp80No9Zk24BMOc.f6RvhI6vHqNN6Z1cL0Zw3fe98oOcz5S', NULL, 'admin', 'DNI', '7654321', '79368242', NULL, 1, 0, NULL, NULL),
(9, 'Juan Pérez', 'juan@example.com', NULL, 'hashed_password_aqui', NULL, 'patient', 'DNI', '2224444', '987654321', NULL, 1, 0, '2025-06-27 14:40:28', NULL),
(12, 'Juan Pérez', 'juan.perez2@example.com', NULL, 'hashed123', NULL, 'patient', 'DNI', '123456738', '987654321', NULL, 1, 0, '2025-06-27 14:50:21', NULL),
(13, 'Lucas Ramirez', 'wwww@gmail.com', NULL, '$2y$10$RvfEcabYu/cK4E1s.E0L5u485OZE5pKR9eQiAJvcDbL5eTMtbXah.', NULL, 'patient', 'DNI', '2121312312', '123212312312', NULL, 1, 0, NULL, NULL),
(14, 'Jose Manuel', 'manuelsaenza@gmail.com', NULL, '$2y$10$n/Q5MbW5v/nHNK.t6zIW6.QvH3L6zPGNF1iM6JQYqIRyBqCyEV4XS', NULL, 'patient', 'DNI', '2323434', '4535345', NULL, 1, 0, '2025-06-27 15:03:16', NULL),
(15, 'Jaime Guti', 'jgutii@gmail.com', NULL, '$2y$10$.1SaC3.kQQyhk1C2IDtANuR6bMmVyhnv6Wb/vX6BjLXlhb.rf9s02', NULL, 'doctor', NULL, NULL, NULL, NULL, 1, 0, '2025-06-27 15:09:11', NULL),
(16, 'Juanito Perez', 'juantip@gmail.com', NULL, '$2y$10$RbvSvDg03tP4c.cbceFQyeKRnTK5g1GeUoBpJgdENNEzqDM5lKIH.', NULL, 'admin', 'DNI', '43434343', '967132569', NULL, 0, 1, NULL, NULL);

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `appointments`
--
ALTER TABLE `appointments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `appointments_patient_id_foreign` (`patient_id`),
  ADD KEY `appointments_doctor_id_foreign` (`doctor_id`),
  ADD KEY `appointments_treatment_id_foreign` (`treatment_id`);

--
-- Indices de la tabla `appointment_history`
--
ALTER TABLE `appointment_history`
  ADD PRIMARY KEY (`id`),
  ADD KEY `appointment_history_patient_id_foreign` (`patient_id`),
  ADD KEY `appointment_history_appointment_id_foreign` (`appointment_id`);

--
-- Indices de la tabla `calendars`
--
ALTER TABLE `calendars`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `clinic_settings`
--
ALTER TABLE `clinic_settings`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `doctors`
--
ALTER TABLE `doctors`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `doctors_email_unique` (`email`),
  ADD UNIQUE KEY `doctors_dni_unique` (`dni`),
  ADD KEY `doctors_specialty_id_foreign` (`specialty_id`),
  ADD KEY `doctors_user_id_foreign` (`user_id`);

--
-- Indices de la tabla `odontograms`
--
ALTER TABLE `odontograms`
  ADD PRIMARY KEY (`id`),
  ADD KEY `odontograms_patient_id_foreign` (`patient_id`);

--
-- Indices de la tabla `patients`
--
ALTER TABLE `patients`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `patients_dni_unique` (`dni`),
  ADD KEY `patients_user_id_foreign` (`user_id`);

--
-- Indices de la tabla `payments`
--
ALTER TABLE `payments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `payments_appointment_id_foreign` (`appointment_id`);

--
-- Indices de la tabla `specialties`
--
ALTER TABLE `specialties`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `specialties_name_unique` (`name`);

--
-- Indices de la tabla `teeth`
--
ALTER TABLE `teeth`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_tooth` (`patient_id`,`appointment_id`,`piece`,`zone`),
  ADD KEY `appointment_id` (`appointment_id`),
  ADD KEY `treatment_id` (`treatment_id`);

--
-- Indices de la tabla `treatments`
--
ALTER TABLE `treatments`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`),
  ADD UNIQUE KEY `users_document_number_unique` (`document_number`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `appointments`
--
ALTER TABLE `appointments`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT de la tabla `appointment_history`
--
ALTER TABLE `appointment_history`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `calendars`
--
ALTER TABLE `calendars`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `clinic_settings`
--
ALTER TABLE `clinic_settings`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `doctors`
--
ALTER TABLE `doctors`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT de la tabla `odontograms`
--
ALTER TABLE `odontograms`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `patients`
--
ALTER TABLE `patients`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT de la tabla `payments`
--
ALTER TABLE `payments`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `specialties`
--
ALTER TABLE `specialties`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT de la tabla `teeth`
--
ALTER TABLE `teeth`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT de la tabla `treatments`
--
ALTER TABLE `treatments`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de la tabla `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `appointments`
--
ALTER TABLE `appointments`
  ADD CONSTRAINT `appointments_doctor_id_foreign` FOREIGN KEY (`doctor_id`) REFERENCES `doctors` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `appointments_patient_id_foreign` FOREIGN KEY (`patient_id`) REFERENCES `patients` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `appointments_treatment_id_foreign` FOREIGN KEY (`treatment_id`) REFERENCES `treatments` (`id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `appointment_history`
--
ALTER TABLE `appointment_history`
  ADD CONSTRAINT `appointment_history_appointment_id_foreign` FOREIGN KEY (`appointment_id`) REFERENCES `appointments` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `appointment_history_patient_id_foreign` FOREIGN KEY (`patient_id`) REFERENCES `patients` (`id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `doctors`
--
ALTER TABLE `doctors`
  ADD CONSTRAINT `doctors_specialty_id_foreign` FOREIGN KEY (`specialty_id`) REFERENCES `specialties` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_doctors_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Filtros para la tabla `odontograms`
--
ALTER TABLE `odontograms`
  ADD CONSTRAINT `odontograms_patient_id_foreign` FOREIGN KEY (`patient_id`) REFERENCES `patients` (`id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `patients`
--
ALTER TABLE `patients`
  ADD CONSTRAINT `fk_patients_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Filtros para la tabla `payments`
--
ALTER TABLE `payments`
  ADD CONSTRAINT `fk_payments_appointment` FOREIGN KEY (`appointment_id`) REFERENCES `appointments` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `payments_appointment_id_foreign` FOREIGN KEY (`appointment_id`) REFERENCES `appointments` (`id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `teeth`
--
ALTER TABLE `teeth`
  ADD CONSTRAINT `teeth_ibfk_1` FOREIGN KEY (`patient_id`) REFERENCES `patients` (`id`),
  ADD CONSTRAINT `teeth_ibfk_2` FOREIGN KEY (`appointment_id`) REFERENCES `appointments` (`id`),
  ADD CONSTRAINT `teeth_ibfk_3` FOREIGN KEY (`treatment_id`) REFERENCES `treatments` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
