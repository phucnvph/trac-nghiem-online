
<?php

/**
 * HỆ THỐNG TRẮC NGHIỆM ONLINE
 * Controller Package
 * @author: Package System
 */

require_once 'models/model_package.php';
require_once 'views/view_package.php';

class Controller_Package
{
    public $student_info = array();
    
    public function __construct()
    {
        if (!isset($_SESSION['username']) || !isset($_SESSION['role']) || $_SESSION['role'] != 'student') {
            header("Location: index.php");
            exit;
        }
        
        require_once 'models/model_student.php';
        $student_model = new Model_Student();
        $user_info = $student_model->get_profiles($_SESSION['username']);
        $this->student_info['ID'] = $user_info->ID;
        $this->student_info['username'] = $user_info->username;
        $this->student_info['name'] = $user_info->name;
        $this->student_info['avatar'] = $user_info->avatar;
        $this->student_info['class_id'] = $user_info->class_id;
        $this->student_info['grade_id'] = $user_info->grade_id;
    }

    // Hiển thị trang mua gói
    public function show_packages()
    {
        $view = new View_Package();
        $model = new Model_Package();
        
        $packages = $model->get_all_packages();
        $remaining_tests = $model->get_total_remaining_tests($this->student_info['ID']);
        
        $view->show_head_left($this->student_info);
        $view->show_packages($packages, $remaining_tests);
        $view->show_foot();
    }

    // Hiển thị lịch sử mua gói
    public function show_order_history()
    {
        $view = new View_Package();
        $model = new Model_Package();
        
        $orders = $model->get_student_order_history($this->student_info['ID']);
        $student_packages = $model->get_student_packages($this->student_info['ID']);
        $remaining_tests = $model->get_total_remaining_tests($this->student_info['ID']);
        
        $view->show_head_left($this->student_info);
        $view->show_order_history($orders, $student_packages, $remaining_tests);
        $view->show_foot();
    }

    // Tạo đơn hàng
    public function create_order()
    {
        $result = array();
        
        $package_id = isset($_POST['package_id']) ? (int)$_POST['package_id'] : 0;
        
        if ($package_id <= 0) {
            $result['status'] = 0;
            $result['message'] = 'Gói thi không hợp lệ';
            echo json_encode($result);
            return;
        }
        
        $model = new Model_Package();
        $package = $model->get_package_by_id($package_id);
        
        if (!$package) {
            $result['status'] = 0;
            $result['message'] = 'Gói thi không tồn tại';
            echo json_encode($result);
            return;
        }
        
        // Tạo mã đơn hàng
        $order_code = 'PKG' . time() . $this->student_info['ID'];
        
        // Tạo đơn hàng
        if ($model->create_order($this->student_info['ID'], $package_id, $order_code, $package->price)) {
            $result['status'] = 1;
            $result['message'] = 'Tạo đơn hàng thành công';
            $result['order_code'] = $order_code;
            $result['amount'] = $package->price;
            $result['package_name'] = $package->package_name;
        } else {
            $result['status'] = 0;
            $result['message'] = 'Lỗi tạo đơn hàng';
        }
        
        echo json_encode($result);
    }

    // Xử lý callback từ SePay
    public function sepay_callback()
    {
        $model = new Model_Package();
        
        // Lấy dữ liệu từ SePay (thường là POST)
        $order_code = isset($_POST['order_code']) ? $_POST['order_code'] : '';
        $status = isset($_POST['status']) ? $_POST['status'] : '';
        $transaction_id = isset($_POST['transaction_id']) ? $_POST['transaction_id'] : '';
        
        if (empty($order_code)) {
            echo json_encode(['status' => 0, 'message' => 'Thiếu mã đơn hàng']);
            return;
        }
        
        $order = $model->get_order_by_code($order_code);
        if (!$order) {
            echo json_encode(['status' => 0, 'message' => 'Đơn hàng không tồn tại']);
            return;
        }
        
        if ($status === 'success' || $status === 'completed') {
            // Cập nhật trạng thái đơn hàng
            $model->update_order_status($order_code, 'completed', $transaction_id);
            
            // Thêm gói thi cho học sinh
            $model->add_student_package($order->student_id, $order->package_id, $order->test_count);
            
            echo json_encode(['status' => 1, 'message' => 'Thanh toán thành công']);
        } else {
            // Cập nhật trạng thái thất bại
            $model->update_order_status($order_code, 'failed', $transaction_id);
            echo json_encode(['status' => 0, 'message' => 'Thanh toán thất bại']);
        }
    }

    // Kiểm tra trạng thái đơn hàng
    public function check_order_status()
    {
        $order_code = isset($_GET['order_code']) ? $_GET['order_code'] : '';
        
        if (empty($order_code)) {
            echo json_encode(['status' => 0, 'message' => 'Thiếu mã đơn hàng']);
            return;
        }
        
        $model = new Model_Package();
        $order = $model->get_order_by_code($order_code);
        
        if (!$order) {
            echo json_encode(['status' => 0, 'message' => 'Đơn hàng không tồn tại']);
            return;
        }
        
        echo json_encode([
            'status' => 1,
            'payment_status' => $order->payment_status,
            'message' => 'Lấy trạng thái thành công'
        ]);
    }

    // Mock xác nhận thanh toán thành công (để test)
    public function confirm_payment()
    {
        $order_code = isset($_POST['order_code']) ? $_POST['order_code'] : '';
        
        if (empty($order_code)) {
            echo json_encode(['status' => 0, 'message' => 'Thiếu mã đơn hàng']);
            return;
        }
        
        $model = new Model_Package();
        $order = $model->get_order_by_code($order_code);
        
        if (!$order) {
            echo json_encode(['status' => 0, 'message' => 'Đơn hàng không tồn tại']);
            return;
        }
        
        if ($order->payment_status === 'completed') {
            echo json_encode(['status' => 1, 'message' => 'Đơn hàng đã được thanh toán']);
            return;
        }
        
        // Cập nhật trạng thái đơn hàng
        $model->update_order_status($order_code, 'completed', 'MOCK_TXN_' . time());
        
        // Thêm gói thi cho học sinh
        $model->add_student_package($order->student_id, $order->package_id, $order->test_count);
        
        echo json_encode(['status' => 1, 'message' => 'Xác nhận thanh toán thành công']);
    }
}
