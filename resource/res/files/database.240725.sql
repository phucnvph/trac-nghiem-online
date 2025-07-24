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

-- Cấu trúc mới cho bảng `students` (cập nhật cột `last_login`)
-- LƯU Ý: Đây là cấu trúc CREATE TABLE đầy đủ. Trong thực tế, bạn có thể dùng ALTER TABLE.
DROP TABLE IF EXISTS `students`;
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

-- Bảng mới `test_packages`
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

-- Dữ liệu cho bảng `test_packages`
INSERT INTO `test_packages` (`package_id`, `package_name`, `package_description`, `test_count`, `price`, `status`, `created_at`) VALUES
	(1, 'Gói Cơ Bản', 'Gói 10 lượt thi cho học sinh', 10, 2000, 1, '2025-07-23 16:29:30'),
	(2, 'Gói Tiêu Chuẩn', 'Gói 25 lượt thi với giá ưu đãi', 25, 3000, 1, '2025-07-23 16:29:30'),
	(3, 'Gói Premium', 'Gói 50 lượt thi tiết kiệm nhất', 50, 5000, 1, '2025-07-23 16:29:30');

-- Bảng mới `package_orders`
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

-- Bảng mới `sepay_transactions`
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

-- Bảng mới `student_packages`
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

-- Cấu trúc mới cho bảng `student_test_detail` (thêm cột `ID`)
DROP TABLE IF EXISTS `student_test_detail`;
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


/*!40103 SET TIME_ZONE=IFNULL(@OLD_TIME_ZONE, 'system') */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;