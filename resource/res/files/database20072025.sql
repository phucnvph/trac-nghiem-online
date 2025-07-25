ALTER TABLE students ADD remaining_number int(1) NOT NULL DEFAULT 5;

ALTER TABLE students DROP CONSTRAINT n9;

ALTER TABLE `questions` MODIFY `grade_id` INT (10) NULL;
ALTER TABLE `questions` MODIFY `unit` INT (10) NULL;
ALTER TABLE `questions` MODIFY `level_id` INT (10) NULL;


-- Bảng mới `package_master`
CREATE TABLE IF NOT EXISTS `package_master` (
  `package_id` int NOT NULL AUTO_INCREMENT,
  `package_name` varchar(255) NOT NULL,
  `package_description` text,
  `test_count` int NOT NULL,
  `price` decimal(10,0) NOT NULL,
  `status` tinyint(1) DEFAULT 1,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`package_id`)
) ENGINE=InnoDB;

-- Dữ liệu cho bảng `package_master`
INSERT INTO `package_master` (`package_id`, `package_name`, `package_description`, `test_count`, `price`, `status`) VALUES
(1, 'Gói Cơ Bản',     'Gói 10 lượt thi cho thành viên', 10, 2000, 1),
(2, 'Gói Tiêu Chuẩn', 'Gói 25 lượt thi với giá ưu đãi', 25, 3000, 1),
(3, 'Gói Premium',    'Gói 50 lượt thi tiết kiệm nhất', 50, 5000, 1);

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
  KEY `package_id` (`package_id`)
) ENGINE=InnoDB;

-- Bảng mới `sepay_transactions`
CREATE TABLE IF NOT EXISTS `sepay_transactions` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `gateway` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `transaction_date` timestamp NULL DEFAULT NULL,
  `account_number` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `sub_account` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `amount_in` decimal(15,2) DEFAULT '0.00',
  `amount_out` decimal(15,2) DEFAULT '0.00',
  `accumulated` decimal(15,2) DEFAULT '0.00',
  `code` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `transaction_content` text COLLATE utf8_unicode_ci,
  `reference_number` varchar(200) COLLATE utf8_unicode_ci DEFAULT NULL,
  `body` text COLLATE utf8_unicode_ci,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `id` (`id`)
) ENGINE=InnoDB;

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
  KEY `package_id` (`package_id`)
) ENGINE=InnoDB;
