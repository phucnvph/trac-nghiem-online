-- =====================================================================================
-- SCRIPT TỰ ĐỘNG CẬP NHẬT HOẶC TẠO MỚI CSDL
-- Script này sẽ:
-- 1. Tạo các bảng mới nếu chúng chưa tồn tại.
-- 2. Chỉnh sửa các bảng đã có để cập nhật cấu trúc.
-- 3. Thêm hoặc cập nhật dữ liệu một cách an toàn.
-- =====================================================================================

-- --------------------------------------------------------
-- Phần 1: TẠO CÁC BẢNG HOÀN TOÀN MỚI
-- --------------------------------------------------------

-- Bảng: test_packages
CREATE TABLE IF NOT EXISTS `test_packages` (
  `package_id` int NOT NULL AUTO_INCREMENT,
  `package_name` varchar(255) NOT NULL,
  `package_description` text,
  `test_count` int NOT NULL,
  `price` decimal(10,0) NOT NULL,
  `status` tinyint(1) DEFAULT '1',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`package_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Bảng: student_packages
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
  -- Ràng buộc (CONSTRAINT) sẽ được thêm ở cuối nếu cần
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Bảng: package_orders
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Bảng: sepay_transactions
CREATE TABLE IF NOT EXISTS `sepay_transactions` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `gateway` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `transaction_date` timestamp NULL DEFAULT NULL,
  `account_number` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `sub_account` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `amount_in` decimal(15,0) DEFAULT '0',
  `amount_out` decimal(15,0) DEFAULT '0',
  `accumulated` decimal(15,0) DEFAULT '0',
  `code` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `transaction_content` text COLLATE utf8mb4_unicode_ci,
  `reference_number` varchar(200) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `body` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `id` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


-- --------------------------------------------------------
-- Phần 2: CHỈNH SỬA CÁC BẢNG ĐÃ TỒN TẠI
-- --------------------------------------------------------

-- Cập nhật bảng: students - Sửa đổi cột `last_login`
ALTER TABLE `students`
MODIFY COLUMN `last_login` datetime DEFAULT (now()) ON UPDATE CURRENT_TIMESTAMP;

-- Cập nhật bảng: teachers - Sửa đổi cột `last_login`
ALTER TABLE `teachers`
MODIFY COLUMN `last_login` datetime NOT NULL DEFAULT (now());

-- --------------------------------------------------------
-- Phần 3: THÊM HOẶC CẬP NHẬT DỮ LIỆU
-- --------------------------------------------------------

-- Dữ liệu cho bảng `test_packages`
INSERT INTO `test_packages` (`package_id`, `package_name`, `package_description`, `test_count`, `price`, `status`) VALUES
(1, 'Gói Cơ Bản', 'Gói 10 lượt thi cho học sinh', 10, 2000, 1),
(2, 'Gói Tiêu Chuẩn', 'Gói 25 lượt thi với giá ưu đãi', 25, 2500, 1),
(3, 'Gói Premium', 'Gói 50 lượt thi tiết kiệm nhất', 50, 2600, 1)
ON DUPLICATE KEY UPDATE
`package_name` = VALUES(`package_name`),
`package_description` = VALUES(`package_description`),
`test_count` = VALUES(`test_count`),
`price` = VALUES(`price`),
`status` = VALUES(`status`);

-- Ví dụ: Thêm một giáo viên mới với teacher_id = 2
INSERT INTO `teachers` (`username`, `name`)
VALUES ('giaovien_moi_2', 'Nguyễn Văn A');

-- Thêm một lớp học với class_id = 1 trước khi thêm học sinh
INSERT INTO `classes` (`grade_id`, `class_name`, `teacher_id`) VALUES
(1, 'Lớp 1A', 1);

-- Dữ liệu cho bảng `students` (ví dụ, nếu bạn muốn cập nhật)
-- Giả sử `student_id` là khóa chính
INSERT INTO `students` (`student_id`, `username`, `email`, `password`, `name`, `permission`, `class_id`, `gender_id`, `avatar`, `birthday`) VALUES
(1, '2018hs40', '2018hs40@gmail.com', '827ccb0eea8a706c4c34a16891f84e7b', 'Nguyễn Văn A', 3, 1, 1, 'avatar-default.jpg', '1997-01-19'),
(2, '2018hs41', '2018hs41@gmail.com', 'e10adc3949ba59abbe56e057f20f883e', 'Nguyễn Thị B', 3, 1, 3, 'avatar-default.jpg', '1997-01-20')
ON DUPLICATE KEY UPDATE
`username` = VALUES(`username`),
`email` = VALUES(`email`),
`password` = VALUES(`password`),
`name` = VALUES(`name`);

-- Thêm dữ liệu cho các bảng mới khác nếu có...
-- (Dữ liệu cho `package_orders`, `sepay_transactions`, `student_packages` có tính giao dịch cao,
-- thường được tạo ra bởi ứng dụng thay vì chèn thủ công như thế này)