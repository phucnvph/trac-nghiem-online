
<?php

/**
 * HỆ THỐNG TRẮC NGHIỆM ONLINE
 * Model Package
 * @author: Package System
 * Mail: package@example.com
 */

require_once('config/database.php');

class Model_Package extends Database
{
    // Lấy danh sách tất cả gói thi
    public function get_all_packages()
    {
        $sql = "SELECT * FROM test_packages WHERE status = 1 ORDER BY price ASC";
        $this->set_query($sql);
        return $this->load_rows();
    }

    // Lấy thông tin gói thi theo ID
    public function get_package_by_id($package_id)
    {
        $sql = "SELECT * FROM test_packages WHERE package_id = :package_id AND status = 1";
        $param = [':package_id' => $package_id];
        $this->set_query($sql, $param);
        return $this->load_row();
    }

    // Tạo đơn hàng mới
    public function create_order($student_id, $package_id, $order_code, $amount)
    {
        $sql = "INSERT INTO package_orders (student_id, package_id, order_code, amount, payment_status) 
                VALUES (:student_id, :package_id, :order_code, :amount, 'pending')";
        $param = [
            ':student_id' => $student_id,
            ':package_id' => $package_id,
            ':order_code' => $order_code,
            ':amount' => $amount
        ];
        $this->set_query($sql, $param);
        return $this->execute_return_status();
    }

    // Cập nhật trạng thái đơn hàng
    public function update_order_status($order_code, $status, $transaction_id = null)
    {
        $sql = "UPDATE package_orders SET payment_status = :status";
        $param = [':status' => $status, ':order_code' => $order_code];
        
        if ($transaction_id) {
            $sql .= ", sepay_transaction_id = :transaction_id";
            $param[':transaction_id'] = $transaction_id;
        }
        
        $sql .= " WHERE order_code = :order_code";
        $this->set_query($sql, $param);
        return $this->execute_return_status();
    }

    // Lấy thông tin đơn hàng
    public function get_order_by_code($order_code)
    {
        $sql = "SELECT po.*, tp.package_name, tp.test_count 
                FROM package_orders po 
                INNER JOIN test_packages tp ON po.package_id = tp.package_id 
                WHERE po.order_code = :order_code";
        $param = [':order_code' => $order_code];
        $this->set_query($sql, $param);
        return $this->load_row();
    }

    // Thêm gói thi cho học sinh
    public function add_student_package($student_id, $package_id, $test_count)
    {
        // Kiểm tra xem học sinh đã có gói này chưa
        $existing = $this->get_student_package($student_id, $package_id);
        
        if ($existing) {
            // Cộng thêm lượt thi vào gói hiện tại
            $sql = "UPDATE student_packages SET remaining_tests = remaining_tests + :test_count,
                    total_tests = total_tests + :test_count WHERE student_id = :student_id AND package_id = :package_id";
        } else {
            // Tạo gói mới cho học sinh
            $sql = "INSERT INTO student_packages (student_id, package_id, remaining_tests, total_tests) 
                    VALUES (:student_id, :package_id, :test_count, :test_count)";
        }
        
        $param = [
            ':student_id' => $student_id,
            ':package_id' => $package_id,
            ':test_count' => $test_count
        ];
        $this->set_query($sql, $param);
        return $this->execute_return_status();
    }

    // Lấy gói thi của học sinh
    public function get_student_package($student_id, $package_id)
    {
        $sql = "SELECT * FROM student_packages WHERE student_id = :student_id AND package_id = :package_id AND status = 1";
        $param = [':student_id' => $student_id, ':package_id' => $package_id];
        $this->set_query($sql, $param);
        return $this->load_row();
    }

    // Lấy tất cả gói thi của học sinh
    public function get_student_packages($student_id)
    {
        $sql = "SELECT sp.*, tp.package_name, tp.package_description 
                FROM student_packages sp 
                INNER JOIN test_packages tp ON sp.package_id = tp.package_id 
                WHERE sp.student_id = :student_id AND sp.status = 1 AND sp.remaining_tests > 0
                ORDER BY sp.purchase_date DESC";
        $param = [':student_id' => $student_id];
        $this->set_query($sql, $param);
        return $this->load_rows();
    }

    // Lấy tổng số lượt thi còn lại của học sinh
    public function get_total_remaining_tests($student_id)
    {
        $sql = "SELECT SUM(remaining_tests) as total FROM student_packages 
                WHERE student_id = :student_id AND status = 1";
        $param = [':student_id' => $student_id];
        $this->set_query($sql, $param);
        $result = $this->load_row();
        return $result ? (int)$result->total : 0;
    }

    // Trừ lượt thi khi làm bài
    public function use_test_attempt($student_id)
    {
        // Lấy gói có lượt thi còn lại (ưu tiên gói mua sớm nhất)
        $sql = "SELECT * FROM student_packages 
                WHERE student_id = :student_id AND remaining_tests > 0 AND status = 1 
                ORDER BY purchase_date ASC LIMIT 1";
        $param = [':student_id' => $student_id];
        $this->set_query($sql, $param);
        $package = $this->load_row();
        
        if ($package) {
            // Trừ 1 lượt thi
            $sql = "UPDATE student_packages SET remaining_tests = remaining_tests - 1 
                    WHERE id = :id";
            $param = [':id' => $package->id];
            $this->set_query($sql, $param);
            return $this->execute_return_status();
        }
        
        return false;
    }

    // Lấy lịch sử mua gói của học sinh
    public function get_student_order_history($student_id)
    {
        $sql = "SELECT po.*, tp.package_name, tp.test_count 
                FROM package_orders po 
                INNER JOIN test_packages tp ON po.package_id = tp.package_id 
                WHERE po.student_id = :student_id 
                ORDER BY po.created_at DESC";
        $param = [':student_id' => $student_id];
        $this->set_query($sql, $param);
        return $this->load_rows();
    }

    // Kiểm tra học sinh có đủ lượt thi không
    public function has_test_attempts($student_id)
    {
        $total = $this->get_total_remaining_tests($student_id);
        return $total > 0;
    }

    // Đánh dấu đã cộng gói để tránh trùng lặp
    public function mark_package_added($order_code)
    {
        $sql = "UPDATE package_orders SET package_added = 1 WHERE order_code = :order_code";
        $param = [':order_code' => $order_code];
        $this->set_query($sql, $param);
        return $this->execute_return_status();
    }
}
