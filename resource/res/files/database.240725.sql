-- --------------------------------------------------------
-- Host:                         127.0.0.1
-- Server version:               8.0.42 - MySQL Community Server - GPL
-- Server OS:                    Win64
-- HeidiSQL Version:             12.10.0.7000
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

-- Dumping structure for table quizz_onl.admins
CREATE TABLE IF NOT EXISTS `admins` (
  `admin_id` int NOT NULL AUTO_INCREMENT,
  `username` varchar(16) NOT NULL,
  `email` varchar(50) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `password` varchar(32) NOT NULL,
  `name` varchar(50) NOT NULL,
  `permission` int DEFAULT '1',
  `last_login` datetime NOT NULL,
  `gender_id` int NOT NULL DEFAULT '1',
  `avatar` varchar(255) DEFAULT 'avatar-default.jpg',
  `birthday` date NOT NULL,
  PRIMARY KEY (`admin_id`),
  UNIQUE KEY `username` (`username`),
  UNIQUE KEY `email` (`email`),
  KEY `n4` (`permission`),
  KEY `admins_gender_id` (`gender_id`),
  CONSTRAINT `admins_gender_id` FOREIGN KEY (`gender_id`) REFERENCES `genders` (`gender_id`),
  CONSTRAINT `n4` FOREIGN KEY (`permission`) REFERENCES `permissions` (`permission`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb3;

-- Dumping data for table quizz_onl.admins: ~0 rows (approximately)
DELETE FROM `admins`;
INSERT INTO `admins` (`admin_id`, `username`, `email`, `password`, `name`, `permission`, `last_login`, `gender_id`, `avatar`, `birthday`) VALUES
	(1, 'admin', 'admin@ikun.org', 'e10adc3949ba59abbe56e057f20f883e', 'ADMIN', 1, '2025-07-24 03:09:04', 1, 'avatar-default.jpg', '1997-01-01');

-- Dumping structure for table quizz_onl.chats
CREATE TABLE IF NOT EXISTS `chats` (
  `ID` int NOT NULL AUTO_INCREMENT,
  `username` varchar(16) COLLATE utf8mb3_unicode_ci NOT NULL,
  `name` varchar(50) COLLATE utf8mb3_unicode_ci NOT NULL,
  `time_sent` datetime NOT NULL,
  `chat_content` text COLLATE utf8mb3_unicode_ci NOT NULL,
  `class_id` int NOT NULL,
  PRIMARY KEY (`ID`),
  KEY `class_id` (`class_id`),
  CONSTRAINT `chat_classes_ibfk_1` FOREIGN KEY (`class_id`) REFERENCES `classes` (`class_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

-- Dumping data for table quizz_onl.chats: ~0 rows (approximately)
DELETE FROM `chats`;

-- Dumping structure for table quizz_onl.classes
CREATE TABLE IF NOT EXISTS `classes` (
  `class_id` int NOT NULL AUTO_INCREMENT,
  `grade_id` int NOT NULL,
  `class_name` varchar(50) NOT NULL,
  `teacher_id` int NOT NULL,
  PRIMARY KEY (`class_id`),
  UNIQUE KEY `class_name` (`class_name`),
  KEY `n7` (`teacher_id`),
  KEY `k4` (`grade_id`),
  CONSTRAINT `classes_ibfk_1` FOREIGN KEY (`teacher_id`) REFERENCES `teachers` (`teacher_id`),
  CONSTRAINT `classes_ibfk_2` FOREIGN KEY (`grade_id`) REFERENCES `grades` (`grade_id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb3;

-- Dumping data for table quizz_onl.classes: ~0 rows (approximately)
DELETE FROM `classes`;
INSERT INTO `classes` (`class_id`, `grade_id`, `class_name`, `teacher_id`) VALUES
	(1, 1, 'Lớp 1A', 2);

-- Dumping structure for table quizz_onl.genders
CREATE TABLE IF NOT EXISTS `genders` (
  `gender_id` int NOT NULL AUTO_INCREMENT,
  `gender_detail` varchar(20) COLLATE utf8mb3_unicode_ci NOT NULL,
  PRIMARY KEY (`gender_id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

-- Dumping data for table quizz_onl.genders: ~3 rows (approximately)
DELETE FROM `genders`;
INSERT INTO `genders` (`gender_id`, `gender_detail`) VALUES
	(1, 'Chưa Xác Định'),
	(2, 'Nam'),
	(3, 'Nữ');

-- Dumping structure for table quizz_onl.grades
CREATE TABLE IF NOT EXISTS `grades` (
  `grade_id` int NOT NULL AUTO_INCREMENT,
  `detail` varchar(30) NOT NULL,
  PRIMARY KEY (`grade_id`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8mb3;

-- Dumping data for table quizz_onl.grades: ~12 rows (approximately)
DELETE FROM `grades`;
INSERT INTO `grades` (`grade_id`, `detail`) VALUES
	(1, 'Khối 1'),
	(2, 'Khối 2'),
	(3, 'Khối 3'),
	(4, 'Khối 4'),
	(5, 'Khối 5'),
	(6, 'Khối 6'),
	(7, 'Khối 7'),
	(8, 'Khối 8'),
	(9, 'Khối 9'),
	(10, 'Khối 10'),
	(11, 'Khối 11'),
	(12, 'Khối 12');

-- Dumping structure for table quizz_onl.levels
CREATE TABLE IF NOT EXISTS `levels` (
  `level_id` int NOT NULL AUTO_INCREMENT,
  `level_detail` varchar(255) COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`level_id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

-- Dumping data for table quizz_onl.levels: ~3 rows (approximately)
DELETE FROM `levels`;
INSERT INTO `levels` (`level_id`, `level_detail`) VALUES
	(1, 'Dễ'),
	(2, 'Trung Bình'),
	(3, 'Khó');

-- Dumping structure for table quizz_onl.notifications
CREATE TABLE IF NOT EXISTS `notifications` (
  `notification_id` int NOT NULL AUTO_INCREMENT,
  `username` varchar(16) COLLATE utf8mb3_unicode_ci NOT NULL,
  `name` varchar(50) COLLATE utf8mb3_unicode_ci NOT NULL,
  `notification_title` text COLLATE utf8mb3_unicode_ci NOT NULL,
  `notification_content` text COLLATE utf8mb3_unicode_ci NOT NULL,
  `time_sent` datetime NOT NULL,
  PRIMARY KEY (`notification_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

-- Dumping data for table quizz_onl.notifications: ~0 rows (approximately)
DELETE FROM `notifications`;

-- Dumping structure for table quizz_onl.package_orders
CREATE TABLE IF NOT EXISTS `package_orders` (
  `order_id` int NOT NULL AUTO_INCREMENT,
  `student_id` int NOT NULL,
  `package_id` int NOT NULL,
  `order_code` varchar(50) NOT NULL,
  `amount` decimal(10,0) NOT NULL,
  `payment_method` varchar(50) DEFAULT 'sepay',
  `payment_status` enum('pending','completed','failed','cancelled') DEFAULT 'pending',
  `sepay_transaction_id` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `package_added` tinyint(1) DEFAULT '0',
  PRIMARY KEY (`order_id`),
  UNIQUE KEY `order_code` (`order_code`),
  KEY `student_id` (`student_id`),
  KEY `package_id` (`package_id`),
  CONSTRAINT `package_orders_ibfk_1` FOREIGN KEY (`student_id`) REFERENCES `students` (`student_id`) ON DELETE CASCADE,
  CONSTRAINT `package_orders_ibfk_2` FOREIGN KEY (`package_id`) REFERENCES `test_packages` (`package_id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=35 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Dumping data for table quizz_onl.package_orders: ~0 rows (approximately)
DELETE FROM `package_orders`;
INSERT INTO `package_orders` (`order_id`, `student_id`, `package_id`, `order_code`, `amount`, `payment_method`, `payment_status`, `sepay_transaction_id`, `created_at`, `updated_at`, `package_added`) VALUES
	(4, 1, 1, 'PKG17532891701', 50000, 'sepay', 'completed', 'MOCK_TXN_1753289172', '2025-07-23 16:46:10', '2025-07-23 16:46:12', 0),
	(5, 1, 2, 'PKG17532891851', 100000, 'sepay', 'completed', 'MOCK_TXN_1753289186', '2025-07-23 16:46:25', '2025-07-23 16:46:26', 0),
	(6, 1, 1, 'PKG17532893921', 50000, 'sepay', 'pending', NULL, '2025-07-23 16:49:52', '2025-07-23 16:49:52', 0),
	(7, 1, 1, 'PKG17532898021', 50000, 'sepay', 'pending', NULL, '2025-07-23 16:56:42', '2025-07-23 16:56:42', 0),
	(8, 1, 1, 'PKG17532902731', 50000, 'sepay', 'pending', NULL, '2025-07-23 17:04:33', '2025-07-23 17:04:33', 0),
	(9, 1, 1, 'PKG17532902801', 50000, 'sepay', 'pending', NULL, '2025-07-23 17:04:40', '2025-07-23 17:04:40', 0),
	(10, 1, 1, 'PKG17532903731', 50000, 'sepay', 'pending', NULL, '2025-07-23 17:06:13', '2025-07-23 17:06:13', 0),
	(11, 1, 1, 'PKG17532903991', 50000, 'sepay', 'pending', NULL, '2025-07-23 17:06:39', '2025-07-23 17:06:39', 0),
	(12, 1, 1, 'PKG17532905371', 200, 'sepay', 'pending', NULL, '2025-07-23 17:08:57', '2025-07-23 17:08:57', 0),
	(13, 1, 2, 'PKG17532919931', 250, 'sepay', 'completed', 'MOCK_TXN_1753291998', '2025-07-23 17:33:13', '2025-07-23 17:33:18', 0),
	(14, 1, 2, 'PKG17532920101', 250, 'sepay', 'pending', NULL, '2025-07-23 17:33:30', '2025-07-23 17:33:30', 0),
	(15, 1, 1, 'PKG17532920581', 200, 'sepay', 'completed', NULL, '2025-07-23 17:34:18', '2025-07-23 17:34:49', 0),
	(16, 1, 1, 'PKG17532920951', 200, 'sepay', 'pending', NULL, '2025-07-23 17:34:55', '2025-07-23 17:34:55', 0),
	(17, 1, 2, 'PKG17532921751', 250, 'sepay', 'pending', NULL, '2025-07-23 17:36:15', '2025-07-23 17:36:15', 0),
	(18, 1, 1, 'PKG17532922081', 200, 'sepay', 'pending', NULL, '2025-07-23 17:36:48', '2025-07-23 17:36:48', 0),
	(19, 1, 2, 'PKG17532925241', 250, 'sepay', 'pending', NULL, '2025-07-23 17:42:04', '2025-07-23 17:42:04', 0),
	(20, 1, 1, 'PKG17532934951', 200, 'sepay', 'completed', NULL, '2025-07-23 17:58:15', '2025-07-23 17:58:42', 0),
	(21, 1, 1, 'PKG17532936451', 200, 'sepay', 'completed', 'PKG17532936451', '2025-07-23 18:00:45', '2025-07-23 18:58:37', 1),
	(22, 1, 1, 'PKG17532942721', 200, 'sepay', 'completed', NULL, '2025-07-23 18:11:12', '2025-07-23 18:11:36', 1),
	(23, 1, 2, 'PKG17532971601', 250, 'sepay', 'completed', 'PKG17532971601', '2025-07-23 18:59:20', '2025-07-23 19:00:13', 1),
	(24, 1, 3, 'PKG17532972301', 260, 'sepay', 'completed', 'PKG17532972301', '2025-07-23 19:00:30', '2025-07-23 19:00:56', 1),
	(25, 1, 1, 'PKG17533006791', 200, 'sepay', 'completed', 'MOCK_TXN_1753300686', '2025-07-23 19:57:59', '2025-07-23 19:58:07', 1),
	(26, 1, 1, 'PKG17533006961', 200, 'sepay', 'pending', NULL, '2025-07-23 19:58:16', '2025-07-23 19:58:16', 0),
	(27, 1, 1, 'PKG17533008181', 200, 'sepay', 'pending', NULL, '2025-07-23 20:00:18', '2025-07-23 20:00:18', 0),
	(28, 1, 2, 'PKG17533008681', 250, 'sepay', 'pending', NULL, '2025-07-23 20:01:08', '2025-07-23 20:01:08', 0),
	(29, 1, 2, 'PKG17533008931', 250, 'sepay', 'pending', NULL, '2025-07-23 20:01:33', '2025-07-23 20:01:33', 0),
	(30, 1, 2, 'PKG17533009341', 250, 'sepay', 'pending', NULL, '2025-07-23 20:02:14', '2025-07-23 20:02:14', 0),
	(31, 1, 2, 'PKG17533010011', 250, 'sepay', 'pending', NULL, '2025-07-23 20:03:21', '2025-07-23 20:03:21', 0),
	(32, 1, 2, 'PKG17533010101', 250, 'sepay', 'pending', NULL, '2025-07-23 20:03:30', '2025-07-23 20:03:30', 0),
	(33, 1, 2, 'PKG17533010241', 250, 'sepay', 'completed', 'PKG17533010241', '2025-07-23 20:03:44', '2025-07-23 20:04:32', 1),
	(34, 1, 3, 'PKG17533010901', 260, 'sepay', 'completed', 'PKG17533010901', '2025-07-23 20:04:50', '2025-07-23 20:05:10', 1);

-- Dumping structure for table quizz_onl.permissions
CREATE TABLE IF NOT EXISTS `permissions` (
  `permission` int NOT NULL AUTO_INCREMENT,
  `permission_detail` varchar(20) NOT NULL,
  PRIMARY KEY (`permission`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb3;

-- Dumping data for table quizz_onl.permissions: ~3 rows (approximately)
DELETE FROM `permissions`;
INSERT INTO `permissions` (`permission`, `permission_detail`) VALUES
	(1, 'Admin'),
	(2, 'Giáo Viên'),
	(3, 'Học Sinh');

-- Dumping structure for table quizz_onl.questions
CREATE TABLE IF NOT EXISTS `questions` (
  `grade_id` int NOT NULL,
  `unit` int NOT NULL,
  `level_id` int NOT NULL,
  `question_content` text CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `answer_a` text CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `answer_b` text CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `answer_c` text CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `answer_d` text CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `correct_answer` text CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci NOT NULL,
  `question_id` int NOT NULL AUTO_INCREMENT,
  `subject_id` int NOT NULL DEFAULT '1',
  `sent_by` varchar(255) NOT NULL,
  `status_id` int NOT NULL,
  PRIMARY KEY (`question_id`),
  KEY `k9` (`grade_id`),
  KEY `unit` (`unit`),
  KEY `subjects_key` (`subject_id`),
  KEY `level_id` (`level_id`),
  KEY `status_id` (`status_id`),
  CONSTRAINT `k9` FOREIGN KEY (`grade_id`) REFERENCES `grades` (`grade_id`),
  CONSTRAINT `questions_ibfk_1` FOREIGN KEY (`level_id`) REFERENCES `levels` (`level_id`),
  CONSTRAINT `questions_ibfk_2` FOREIGN KEY (`status_id`) REFERENCES `statuses` (`status_id`),
  CONSTRAINT `subjects_key` FOREIGN KEY (`subject_id`) REFERENCES `subjects` (`subject_id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb3;

-- Dumping data for table quizz_onl.questions: ~2 rows (approximately)
DELETE FROM `questions`;
INSERT INTO `questions` (`grade_id`, `unit`, `level_id`, `question_content`, `answer_a`, `answer_b`, `answer_c`, `answer_d`, `correct_answer`, `question_id`, `subject_id`, `sent_by`, `status_id`) VALUES
	(1, 1, 1, '1 + 1 = ?', '2', '1', '3', '4', '2', 1, 1, 'admin', 4),
	(1, 2, 3, 'Cành cây có 5 con chim, bắn mất 1 con, hỏi còn mấy con chim?', '5', '4', '6', '3', '4', 2, 1, 'admin', 4);

-- Dumping structure for table quizz_onl.quest_of_test
CREATE TABLE IF NOT EXISTS `quest_of_test` (
  `test_code` int NOT NULL,
  `question_id` int NOT NULL,
  `timest` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`test_code`,`question_id`),
  KEY `question_id` (`question_id`),
  KEY `test_code` (`test_code`),
  CONSTRAINT `quest_of_test_ibfk_1` FOREIGN KEY (`question_id`) REFERENCES `questions` (`question_id`),
  CONSTRAINT `quest_of_test_ibfk_2` FOREIGN KEY (`test_code`) REFERENCES `tests` (`test_code`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

-- Dumping data for table quizz_onl.quest_of_test: ~0 rows (approximately)
DELETE FROM `quest_of_test`;
INSERT INTO `quest_of_test` (`test_code`, `question_id`, `timest`) VALUES
	(285938, 1, '2025-07-23 15:47:02'),
	(285938, 2, '2025-07-23 15:47:02'),
	(655756, 1, '2025-07-23 15:58:24'),
	(655756, 2, '2025-07-23 15:58:24');

-- Dumping structure for table quizz_onl.scores
CREATE TABLE IF NOT EXISTS `scores` (
  `student_id` int NOT NULL,
  `test_code` int NOT NULL,
  `score_number` varchar(10) DEFAULT NULL,
  `score_detail` varchar(50) NOT NULL,
  `completion_time` datetime DEFAULT NULL,
  PRIMARY KEY (`student_id`,`test_code`),
  KEY `student_id` (`student_id`),
  KEY `test_code` (`test_code`),
  CONSTRAINT `scores_ibfk_1` FOREIGN KEY (`student_id`) REFERENCES `students` (`student_id`),
  CONSTRAINT `scores_ibfk_2` FOREIGN KEY (`test_code`) REFERENCES `tests` (`test_code`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- Dumping data for table quizz_onl.scores: ~0 rows (approximately)
DELETE FROM `scores`;

-- Dumping structure for table quizz_onl.sepay_transactions
CREATE TABLE IF NOT EXISTS `sepay_transactions` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `gateway` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `transaction_date` timestamp NULL DEFAULT NULL,
  `account_number` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `sub_account` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `amount_in` decimal(15,2) DEFAULT '0.00',
  `amount_out` decimal(15,2) DEFAULT '0.00',
  `accumulated` decimal(15,2) DEFAULT '0.00',
  `code` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `transaction_content` text COLLATE utf8mb4_unicode_ci,
  `reference_number` varchar(200) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `body` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `id` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table quizz_onl.sepay_transactions: ~0 rows (approximately)
DELETE FROM `sepay_transactions`;
INSERT INTO `sepay_transactions` (`id`, `gateway`, `transaction_date`, `account_number`, `sub_account`, `amount_in`, `amount_out`, `accumulated`, `code`, `transaction_content`, `reference_number`, `body`, `created_at`) VALUES
	(1, 'Vietcombank', '2023-03-25 07:02:37', '0123499999', NULL, 2277000.00, 0.00, 19077000.00, '', 'chuyen tien mua iphone', 'MBVCB.3278907687', '', '2025-07-23 18:54:40'),
	(2, 'Vietcombank', '2023-03-25 07:02:37', '0123499999', NULL, 2277000.00, 0.00, 19077000.00, '', 'chuyen tien mua iphone', 'PKG17532936451', '', '2025-07-23 18:55:12'),
	(3, 'Vietcombank', '2023-03-25 07:02:37', '0123499999', NULL, 2277000.00, 0.00, 19077000.00, '', 'PKG17532936451', 'PKG17532936451', '', '2025-07-23 18:56:04'),
	(4, 'Vietcombank', '2023-03-25 07:02:37', '0123499999', NULL, 2277000.00, 0.00, 19077000.00, '', 'PKG17532936451', 'PKG17532936451', '', '2025-07-23 18:57:49'),
	(5, 'Vietcombank', '2023-03-25 07:02:37', '0123499999', NULL, 2277000.00, 0.00, 19077000.00, '', 'PKG17532936451', 'PKG17532936451', '', '2025-07-23 18:58:19'),
	(6, 'Vietcombank', '2023-03-25 07:02:37', '0123499999', NULL, 200.00, 0.00, 19077000.00, '', 'PKG17532936451', 'PKG17532936451', '', '2025-07-23 18:58:37'),
	(7, 'Vietcombank', '2023-03-25 07:02:37', '0123499999', NULL, 200.00, 0.00, 19077000.00, '', 'PKG17532971601', 'PKG17532971601', '', '2025-07-23 19:00:06'),
	(8, 'Vietcombank', '2023-03-25 07:02:37', '0123499999', NULL, 250.00, 0.00, 19077000.00, '', 'PKG17532971601', 'PKG17532971601', '', '2025-07-23 19:00:13'),
	(9, 'Vietcombank', '2023-03-25 07:02:37', '0123499999', NULL, 260.00, 0.00, 19077000.00, '', 'PKG17532972301', 'PKG17532972301', '', '2025-07-23 19:00:56'),
	(10, 'Vietcombank', '2023-03-25 07:02:37', '0123499999', NULL, 260.00, 0.00, 19077000.00, '', 'PKG17533010241', 'PKG17533010241', '', '2025-07-23 20:04:25'),
	(11, 'Vietcombank', '2023-03-25 07:02:37', '0123499999', NULL, 250.00, 0.00, 19077000.00, '', 'PKG17533010241', 'PKG17533010241', '', '2025-07-23 20:04:32'),
	(12, 'Vietcombank', '2023-03-25 07:02:37', '0123499999', NULL, 260.00, 0.00, 19077000.00, '', 'PKG17533010901', 'PKG17533010901', '', '2025-07-23 20:05:10');

-- Dumping structure for table quizz_onl.statuses
CREATE TABLE IF NOT EXISTS `statuses` (
  `status_id` int NOT NULL AUTO_INCREMENT,
  `detail` varchar(50) COLLATE utf8mb3_unicode_ci NOT NULL,
  PRIMARY KEY (`status_id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

-- Dumping data for table quizz_onl.statuses: ~7 rows (approximately)
DELETE FROM `statuses`;
INSERT INTO `statuses` (`status_id`, `detail`) VALUES
	(1, 'Mở'),
	(2, 'Đóng'),
	(3, 'Chờ Duyệt'),
	(4, 'Đã Duyệt'),
	(5, 'Cho Phép Xem Đáp Án'),
	(6, 'Kết Thúc'),
	(7, 'Ẩn');

-- Dumping structure for table quizz_onl.students
CREATE TABLE IF NOT EXISTS `students` (
  `student_id` int NOT NULL AUTO_INCREMENT,
  `username` varchar(16) NOT NULL,
  `email` varchar(50) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `password` varchar(32) NOT NULL,
  `name` varchar(50) NOT NULL,
  `permission` int DEFAULT '3',
  `class_id` int NOT NULL,
  `last_login` datetime DEFAULT (now()) ON UPDATE CURRENT_TIMESTAMP,
  `gender_id` int NOT NULL DEFAULT '1',
  `avatar` varchar(255) DEFAULT 'avatar-default.jpg',
  `birthday` date NOT NULL,
  `doing_exam` int DEFAULT NULL,
  `starting_time` datetime DEFAULT NULL,
  `time_remaining` varchar(11) DEFAULT NULL,
  PRIMARY KEY (`student_id`),
  UNIQUE KEY `username` (`username`),
  UNIQUE KEY `email` (`email`),
  KEY `n9` (`class_id`),
  KEY `n11` (`permission`),
  KEY `students_gender_id` (`gender_id`),
  CONSTRAINT `n11` FOREIGN KEY (`permission`) REFERENCES `permissions` (`permission`),
  CONSTRAINT `n9` FOREIGN KEY (`class_id`) REFERENCES `classes` (`class_id`),
  CONSTRAINT `students_gender_id` FOREIGN KEY (`gender_id`) REFERENCES `genders` (`gender_id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb3;

-- Dumping data for table quizz_onl.students: ~0 rows (approximately)
DELETE FROM `students`;
INSERT INTO `students` (`student_id`, `username`, `email`, `password`, `name`, `permission`, `class_id`, `last_login`, `gender_id`, `avatar`, `birthday`, `doing_exam`, `starting_time`, `time_remaining`) VALUES
	(1, '2018hs40', '2018hs40@gmail.com', '827ccb0eea8a706c4c34a16891f84e7b', 'Nguyễn Văn A', 3, 1, '2025-07-24 03:05:47', 1, 'avatar-default.jpg', '1997-01-19', NULL, NULL, NULL),
	(2, '2018hs41', '2018hs41@gmail.com', 'e10adc3949ba59abbe56e057f20f883e', 'Nguyễn Thị B', 3, 1, '2025-07-23 22:57:21', 3, 'avatar-default.jpg', '1997-01-20', NULL, NULL, NULL);

-- Dumping structure for table quizz_onl.student_notifications
CREATE TABLE IF NOT EXISTS `student_notifications` (
  `ID` int NOT NULL AUTO_INCREMENT,
  `notification_id` int NOT NULL,
  `class_id` int NOT NULL,
  PRIMARY KEY (`ID`),
  KEY `notification_id` (`notification_id`),
  KEY `class_id` (`class_id`),
  CONSTRAINT `student_notifications_ibfk_1` FOREIGN KEY (`notification_id`) REFERENCES `notifications` (`notification_id`),
  CONSTRAINT `student_notifications_ibfk_2` FOREIGN KEY (`class_id`) REFERENCES `classes` (`class_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

-- Dumping data for table quizz_onl.student_notifications: ~0 rows (approximately)
DELETE FROM `student_notifications`;

-- Dumping structure for table quizz_onl.student_packages
CREATE TABLE IF NOT EXISTS `student_packages` (
  `id` int NOT NULL AUTO_INCREMENT,
  `student_id` int NOT NULL,
  `package_id` int NOT NULL,
  `remaining_tests` int NOT NULL,
  `total_tests` int NOT NULL,
  `purchase_date` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `status` tinyint(1) DEFAULT '1',
  PRIMARY KEY (`id`),
  KEY `student_id` (`student_id`),
  KEY `package_id` (`package_id`),
  CONSTRAINT `student_packages_ibfk_1` FOREIGN KEY (`student_id`) REFERENCES `students` (`student_id`) ON DELETE CASCADE,
  CONSTRAINT `student_packages_ibfk_2` FOREIGN KEY (`package_id`) REFERENCES `test_packages` (`package_id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Dumping data for table quizz_onl.student_packages: ~0 rows (approximately)
DELETE FROM `student_packages`;
INSERT INTO `student_packages` (`id`, `student_id`, `package_id`, `remaining_tests`, `total_tests`, `purchase_date`, `status`) VALUES
	(1, 1, 1, 10, 40, '2025-07-23 16:46:12', 1),
	(2, 1, 2, 25, 100, '2025-07-23 16:46:26', 1),
	(3, 1, 3, 60, 100, '2025-07-23 19:00:56', 1);

-- Dumping structure for table quizz_onl.student_test_detail
CREATE TABLE IF NOT EXISTS `student_test_detail` (
  `ID` int NOT NULL,
  `student_id` int NOT NULL,
  `test_code` int NOT NULL,
  `question_id` int NOT NULL,
  `answer_a` text COLLATE utf8mb3_unicode_ci,
  `answer_b` text COLLATE utf8mb3_unicode_ci,
  `answer_c` text COLLATE utf8mb3_unicode_ci,
  `answer_d` text COLLATE utf8mb3_unicode_ci,
  `student_answer` text COLLATE utf8mb3_unicode_ci,
  `timest` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`student_id`,`test_code`,`question_id`),
  KEY `fk4` (`test_code`),
  KEY `fk6` (`question_id`),
  CONSTRAINT `fk4` FOREIGN KEY (`test_code`) REFERENCES `tests` (`test_code`),
  CONSTRAINT `fk6` FOREIGN KEY (`question_id`) REFERENCES `questions` (`question_id`),
  CONSTRAINT `fk9` FOREIGN KEY (`student_id`) REFERENCES `students` (`student_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

-- Dumping data for table quizz_onl.student_test_detail: ~0 rows (approximately)
DELETE FROM `student_test_detail`;
INSERT INTO `student_test_detail` (`ID`, `student_id`, `test_code`, `question_id`, `answer_a`, `answer_b`, `answer_c`, `answer_d`, `student_answer`, `timest`) VALUES
	(100039105, 1, 655756, 1, '4', '3', '1', '2', NULL, '2025-07-23 19:15:26'),
	(1624059645, 1, 655756, 2, '4', '3', '5', '6', NULL, '2025-07-23 19:15:26');

-- Dumping structure for table quizz_onl.subjects
CREATE TABLE IF NOT EXISTS `subjects` (
  `subject_id` int NOT NULL AUTO_INCREMENT,
  `subject_detail` varchar(255) COLLATE utf8mb3_unicode_ci NOT NULL,
  PRIMARY KEY (`subject_id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

-- Dumping data for table quizz_onl.subjects: ~2 rows (approximately)
DELETE FROM `subjects`;
INSERT INTO `subjects` (`subject_id`, `subject_detail`) VALUES
	(1, 'Môn Toán'),
	(2, 'Môn Anh');

-- Dumping structure for table quizz_onl.teachers
CREATE TABLE IF NOT EXISTS `teachers` (
  `teacher_id` int NOT NULL AUTO_INCREMENT,
  `username` varchar(16) NOT NULL,
  `email` varchar(50) CHARACTER SET utf8mb3 COLLATE utf8mb3_unicode_ci DEFAULT NULL,
  `password` varchar(32) NOT NULL,
  `name` varchar(50) NOT NULL,
  `permission` int DEFAULT '2',
  `last_login` datetime NOT NULL DEFAULT (now()),
  `gender_id` int NOT NULL DEFAULT '1',
  `avatar` varchar(255) DEFAULT 'avatar-default.jpg',
  `birthday` date NOT NULL,
  PRIMARY KEY (`teacher_id`),
  UNIQUE KEY `username` (`username`),
  UNIQUE KEY `email` (`email`),
  KEY `n2` (`permission`),
  KEY `teachers_gender_id` (`gender_id`),
  CONSTRAINT `n2` FOREIGN KEY (`permission`) REFERENCES `permissions` (`permission`),
  CONSTRAINT `teachers_gender_id` FOREIGN KEY (`gender_id`) REFERENCES `genders` (`gender_id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb3;

-- Dumping data for table quizz_onl.teachers: ~0 rows (approximately)
DELETE FROM `teachers`;
INSERT INTO `teachers` (`teacher_id`, `username`, `email`, `password`, `name`, `permission`, `last_login`, `gender_id`, `avatar`, `birthday`) VALUES
	(2, 'giaovien1', 'giaovien1@gmail.com', 'e10adc3949ba59abbe56e057f20f883e', 'Nguyễn Văn A', 2, '2025-07-23 22:56:05', 2, 'avatar-default.jpg', '1997-01-19'),
	(3, 'giaovien2', 'giaovien2@gmail.com', 'e10adc3949ba59abbe56e057f20f883e', 'Nguyễn Thị B', 2, '2025-07-23 22:56:05', 3, 'avatar-default.jpg', '1997-01-20');

-- Dumping structure for table quizz_onl.teacher_notifications
CREATE TABLE IF NOT EXISTS `teacher_notifications` (
  `ID` int NOT NULL AUTO_INCREMENT,
  `notification_id` int NOT NULL,
  `teacher_id` int NOT NULL,
  PRIMARY KEY (`ID`),
  KEY `notification_id` (`notification_id`),
  KEY `teacher_id` (`teacher_id`),
  CONSTRAINT `teacher_notifications_ibfk_1` FOREIGN KEY (`notification_id`) REFERENCES `notifications` (`notification_id`),
  CONSTRAINT `teacher_notifications_ibfk_2` FOREIGN KEY (`teacher_id`) REFERENCES `teachers` (`teacher_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

-- Dumping data for table quizz_onl.teacher_notifications: ~0 rows (approximately)
DELETE FROM `teacher_notifications`;

-- Dumping structure for table quizz_onl.tests
CREATE TABLE IF NOT EXISTS `tests` (
  `test_code` int NOT NULL AUTO_INCREMENT,
  `test_name` varchar(255) COLLATE utf8mb3_unicode_ci NOT NULL,
  `password` varchar(32) COLLATE utf8mb3_unicode_ci NOT NULL,
  `subject_id` int DEFAULT NULL,
  `grade_id` int NOT NULL,
  `total_questions` int NOT NULL,
  `time_to_do` int NOT NULL,
  `note` text COLLATE utf8mb3_unicode_ci NOT NULL,
  `status_id` int DEFAULT NULL,
  `timest` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`test_code`),
  KEY `fk1` (`subject_id`),
  KEY `fk2` (`status_id`),
  KEY `grade_id` (`grade_id`),
  CONSTRAINT `fk1` FOREIGN KEY (`subject_id`) REFERENCES `subjects` (`subject_id`),
  CONSTRAINT `fk2` FOREIGN KEY (`status_id`) REFERENCES `statuses` (`status_id`),
  CONSTRAINT `tests_ibfk_1` FOREIGN KEY (`grade_id`) REFERENCES `grades` (`grade_id`)
) ENGINE=InnoDB AUTO_INCREMENT=655757 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

-- Dumping data for table quizz_onl.tests: ~0 rows (approximately)
DELETE FROM `tests`;
INSERT INTO `tests` (`test_code`, `test_name`, `password`, `subject_id`, `grade_id`, `total_questions`, `time_to_do`, `note`, `status_id`, `timest`) VALUES
	(285938, 'toan 1', 'e10adc3949ba59abbe56e057f20f883e', 1, 1, 2, 23, 'ghi chu', 1, '2025-07-23 16:02:09'),
	(655756, 'Môn Toán nâng cao', 'e10adc3949ba59abbe56e057f20f883e', 1, 1, 2, 23, '12', 1, '2025-07-23 16:05:21');

-- Dumping structure for table quizz_onl.test_packages
CREATE TABLE IF NOT EXISTS `test_packages` (
  `package_id` int NOT NULL AUTO_INCREMENT,
  `package_name` varchar(255) NOT NULL,
  `package_description` text,
  `test_count` int NOT NULL,
  `price` decimal(10,0) NOT NULL,
  `status` tinyint(1) DEFAULT '1',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`package_id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Dumping data for table quizz_onl.test_packages: ~0 rows (approximately)
DELETE FROM `test_packages`;
INSERT INTO `test_packages` (`package_id`, `package_name`, `package_description`, `test_count`, `price`, `status`, `created_at`) VALUES
	(1, 'Gói Cơ Bản', 'Gói 10 lượt thi cho học sinh', 10, 200, 1, '2025-07-23 16:29:30'),
	(2, 'Gói Tiêu Chuẩn', 'Gói 25 lượt thi với giá ưu đãi', 25, 250, 1, '2025-07-23 16:29:30'),
	(3, 'Gói Premium', 'Gói 50 lượt thi tiết kiệm nhất', 50, 260, 1, '2025-07-23 16:29:30');

/*!40103 SET TIME_ZONE=IFNULL(@OLD_TIME_ZONE, 'system') */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;
