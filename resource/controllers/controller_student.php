<?php

/**
 * HỆ THỐNG TRẮC NGHIỆM ONLINE
 * Controller Student
 * @author: Nong Van Du (Dzu)
 * Mail: dzu6996@gmail.com
 * @link https://github.com/meesudzu/trac-nghiem-online
 */

require_once 'config/config.php';

require_once 'models/model_student.php';
require_once 'views/view_student.php';

require_once 'models/model_package.php';
require_once 'views/view_package.php';

class Controller_Student
{
    public $info =  array();
    public $user_info;
    
    public function __construct()
    {
        $user_info = $this->profiles();
        $this->user_info = $user_info;
        $this->info['ID'] = $user_info->ID;
        $this->update_last_login($this->info['ID']);
        $this->info['username'] = $user_info->username;
        $this->info['name'] = $user_info->name;
        $this->info['avatar'] = $user_info->avatar;
        $this->info['class_id'] = $user_info->class_id;
        $this->info['grade_id'] = $user_info->grade_id;
        $this->info['doing_exam'] = $user_info->doing_exam;
        $this->info['time_remaining'] = $user_info->time_remaining;
    }
    private function profiles()
    {
        $model = new Model_Student();
        return $model->get_profiles($_SESSION['username']);
    }
    private function get_question($ID)
    {
        $model = new Model_Student();
        return $model->get_question($ID);
    }
    private function get_doing_exam()
    {
        return $this->info['doing_exam'];
    }
    private function update_last_login()
    {
        $model = new Model_Student();
        $model->update_last_login($this->info['ID']);
    }
    private function update_doing_exam($exam, $time)
    {
        $model = new Model_Student();
        $model->update_doing_exam($exam, $time, $this->info['ID']);
    }
    public function update_answer()
    {
        $model = new Model_Student();
        $question_id = $_POST['id'];
        $answer = 'answer_'.$_POST['answer'];
        $student_answer = $model->get_student_quest_of_testcode(
            $this->info['ID'],
            $this->info['doing_exam'],
            $question_id
        )->$answer;
        $model->update_answer($this->info['ID'], $this->info['doing_exam'], $question_id, $student_answer);
        $time = $_POST['min'].':'.$_POST['sec'];
        $model->update_timing($this->info['ID'], $time);
    }
    private function update_timing()
    {
        $model = new Model_Student();
        $time = $_POST['min'].':'.$_POST['sec'];
        $model->update_timing($this->info['ID'], $time);
    }
    private function reset_doing_exam()
    {
        $model = new Model_Student();
        $model->reset_doing_exam($this->info['ID']);
    }
    public function get_profiles()
    {
        $model = new Model_Student();
        echo json_encode($model->get_profiles($this->info['username']));
    }
    public function get_notifications()
    {
        $model = new Model_Student();
        echo json_encode($model->get_notifications($this->info['class_id']));
    }
    public function get_chats()
    {
        $model = new Model_Student();
        echo json_encode($model->get_chats($this->info['class_id']));
    }
    public function get_chat_all()
    {
        $model = new Model_Student();
        echo json_encode($model->get_chat_all($this->info['class_id']));
    }
    public function valid_email_on_profiles()
    {
        $result = array();
        $model = new Model_Student();
        $new_email = isset($_POST['new_email']) ? htmlspecialchars($_POST['new_email']) : '';
        $curren_email = isset($_POST['curren_email']) ? htmlspecialchars($_POST['curren_email']) : '';
        if (empty($new_email)) {
            $result['status'] = 0;
        } else {
            if ($model->valid_email_on_profiles($curren_email, $new_email)) {
                $result['status'] = 1;
            } else {
                $result['status'] = 0;
            }
        }
        echo json_encode($result);
    }
    public function update_avatar($avatar, $username)
    {
        $model = new Model_Student();
        return $model->update_avatar($avatar, $username);
    }
    public function submit_update_avatar()
    {
        if (isset($_FILES['file'])) {
            $duoi = explode('.', $_FILES['file']['name']);
            $duoi = $duoi[(count($duoi)-1)];
            if ($duoi === 'jpg' || $duoi === 'png') {
                if (move_uploaded_file($_FILES['file']['tmp_name'], 'upload/avatar/'.$this->info['username'].'_' . $_FILES['file']['name'])) {
                    $avatar = $this->info['username'] .'_' . $_FILES['file']['name'];
                    $update = $this->update_avatar($avatar, $this->info['username']);
                }
            }
        }
    }
    public function check_password()
    {
        $result = array();
        $model = new Model_Student();
        
        // Kiểm tra lượt thi trước
        require_once 'models/model_package.php';
        $modelPackage = new Model_Package();

        $remaining_tests = $modelPackage->get_total_remaining_tests($this->info['ID']);
        $remaining_tests += $this->user_info->remaining_number;
        
        if (!$remaining_tests) {
            $result['status_value'] = "Bạn đã hết lượt thi. <a href='danh-sach-goi'>Mua thêm gói thi</a>";
            $result['status'] = 0;
            echo json_encode($result);
            return;
        }
        
        $test_code = isset($_POST['test_code']) ? $_POST['test_code'] : '493205';
        $password = isset($_POST['password']) ? md5($_POST['password']) : 'e10adc3949ba59abbe56e057f20f883e';
        if ($password != $model->get_test($test_code)->password) {
            $result['status_value'] = "Sai mật khẩu";
            $result['status'] = 0;
        } else {
            // Trừ lượt thi khi bắt đầu làm bài
            $minusPackage = $modelPackage->use_test_attempt($this->info['ID']);
            if (!$minusPackage) {
                $model->minusRemaining($this->info['ID']);
            }

            $list_quest = $model->get_quest_of_test($test_code);
            foreach ($list_quest as $quest) {
                $array = array();
                $array[0] = $quest->answer_a;
                $array[1] = $quest->answer_b;
                $array[2] = $quest->answer_c;
                $array[3] = $quest->answer_d;
                shuffle($array);
                $ID = rand(1, time())+rand(100000, 999999);
                $time = $model->get_test($test_code)->time_to_do.':00';
                $model->add_student_quest(
                    $this->info['ID'],
                    $ID,
                    $test_code,
                    $quest->question_id,
                    $array[0],
                    $array[1],
                    $array[2],
                    $array[3]
                );
                $model->update_doing_exam($test_code, $time, $this->info['ID']);
            }
            $result['status_value'] = "Thành công. Chuẩn bị chuyển trang!";
            $result['status'] = 1;
        }
        echo json_encode($result);
    }
    public function send_chat()
    {
        $result = array();
        $content = isset($_POST['content']) ? htmlspecialchars($_POST['content']) : '';
        if (empty($content)) {
            $result['status_value'] = "Nội dung trống";
            $result['status'] = 0;
        } else {
            $model = new Model_Student();
            $model->chat($this->info['username'], $this->info['name'], $this->info['class_id'], $content);
            $result['status_value'] = "Thành công";
            $result['status'] = 1;
        }
        echo json_encode($result);
    }
    public function update_profiles($username, $name, $email, $password, $gender, $birthday)
    {
        $model = new Model_Student();
        return $model->update_profiles($username, $name, $email, $password, $gender, $birthday);
    }
    public function submit_update_profiles()
    {
        $result = array();
        $name = isset($_POST['name']) ? Htmlspecialchars(addslashes($_POST['name'])) : '';
        $email = isset($_POST['email']) ? Htmlspecialchars(addslashes($_POST['email'])) : '';
        $username = isset($_POST['username']) ? Htmlspecialchars(addslashes($_POST['username'])) : '';
        $gender = isset($_POST['gender']) ? Htmlspecialchars(addslashes($_POST['gender'])) : '';
        $birthday = isset($_POST['birthday']) ? Htmlspecialchars(addslashes($_POST['birthday'])) : '';
        $password = isset($_POST['password']) ? md5($_POST['password']) : '';
        if (empty($name)||empty($gender)||empty($birthday)||empty($password)||empty($email)) {
            $result['status_value'] = "Không được bỏ trống các trường nhập!";
            $result['status'] = 0;
        } else {
            $update = $this->update_profiles($username, $name, $email, $password, $gender, $birthday);
            if (!$update) {
                $result['status_value'] = "Tài khoản không tồn tại!";
                $result['status'] = 0;
            } else {
                $result = json_decode(json_encode($this->profiles($username)), true);
                $result['status_value'] = "Sửa thành công!";
                $result['status'] = 1;
            }
        }
        echo json_encode($result);
    }
    public function accept_test()
    {
        $model = new Model_Student();
        $test = $model->get_result_quest($this->info['doing_exam'], $this->info['ID']);
        $test_code = $test[0]->test_code;
        $total_questions = $test[0]->total_questions;
        $correct = 0;
        $c = 10/$total_questions;
        foreach ($test as $t) {
            if (trim($t->student_answer) == trim($t->correct_answer)) {
                $correct++;
            }
        }
        $score = $correct * $c;
        $score_detail = $correct.'/'.$total_questions;
        $model->insert_score($this->info['ID'], $test_code, $score, $score_detail);
        $model->reset_doing_exam($this->info['ID']);
        header("Location: xem-ket-qua-".$test_code);
    }
    public function toggle_sidebar()
    {
        if ($_SESSION['sidebar-logout'] != '') {
            $_SESSION['sidebar-logout'] = '';
            $_SESSION['menu-icon'] = 'rot';
            $_SESSION['box-content'] = '';
        } else {
            $_SESSION['sidebar-logout'] = 'sidebar-show';
            $_SESSION['menu-icon'] = '';
            $_SESSION['box-content'] = 'box-content-mini';
        }
    }
    public function logout()
    {
        $result = array();
        $confirm = isset($_POST['confirm']) ? $_POST['confirm'] : true;
        if ($confirm) {
            $result['status_value'] = "Đăng xuất thành công!";
            $result['status'] = 1;
            session_destroy();
        }
        echo json_encode($result);
    }
    public function show_dashboard()
    {
        $view = new View_Student();
        if ($this->info['doing_exam'] == '') {
            $view->show_head_left($this->info);
            $model = new Model_Student();
            $modelPackage = new Model_Package();
            $scores = $model->get_scores($this->info['ID']);
            $tests = $model->get_list_tests();
            $remaining_tests = $modelPackage->get_total_remaining_tests($this->info['ID']);
            $remaining_tests += $this->user_info->remaining_number;
            $view->show_dashboard($tests, $scores, $remaining_tests);
            $view->show_foot();
        } else {
            $model = new Model_Student();
            $test = $model->get_doing_quest($this->info['doing_exam'], $this->info['ID']);
            $time_string[] = explode(":", $this->info['time_remaining']);
            $min = $time_string[0][0];
            $sec = $time_string[0][1];
            $view->show_exam($test, $min, $sec);
        }
    }
    public function show_chat()
    {
        $view = new View_Student();
        if ($this->info['doing_exam'] == '') {
            $view->show_head_left($this->info);
            $view->show_chat();
            $view->show_foot();
        } else {
            $model = new Model_Student();
            $test = $model->get_doing_quest($this->info['doing_exam'], $this->info['ID']);
            $time_string[] = explode(":", $this->info['time_remaining']);
            $min = $time_string[0][0];
            $sec = $time_string[0][1];
            $view->show_exam($test, $min, $sec);
        }
    }
    public function show_all_chat()
    {
        $view = new View_Student();
        $view->show_head_left($this->info);
        $view->show_all_chat();
        $view->show_foot();
    }
    public function show_notifications()
    {
        $view = new View_Student();
        $view->show_head_left($this->info);
        $view->show_notifications();
        $view->show_foot();
    }
    public function show_result()
    {
        $view = new View_Student();
        if ($this->info['doing_exam'] == '') {
            $model = new Model_Student();
            $test_code = htmlspecialchars($_GET['test_code']);
            $score = $model->get_score($this->info['ID'], $test_code);
            $test_status = $model->get_test($test_code)->status_id;
            if ($test_status == 1) {
                $result = $model->get_result_quest($test_code, $this->info['ID']);
            } else {
                $result = null;
            }
            if ($score) {
                $view->show_head_left($this->info);
                $view->show_result($test_code, $score, $result);
                $view->show_foot();
            } else {
                $this->show_404();
            }
        } else {
            $model = new Model_Student();
            $test = $model->get_doing_quest($this->info['doing_exam'], $this->info['ID']);
            $time_string[] = explode(":", $this->info['time_remaining']);
            $min = $time_string[0][0];
            $sec = $time_string[0][1];
            $view->show_exam($test, $min, $sec);
        }
    }

    public function show_about()
    {
        $view = new View_Student();
        $view->show_head_left($this->info);
        $view->show_about();
        $view->show_foot();
    }
    public function show_profiles()
    {
        $view = new View_Student();
        $view->show_head_left($this->info);
        $view->show_profiles($this->profiles());
        $view->show_foot();
    }
    public function show_404()
    {
        $view = new View_Student();
        $view->show_head_left($this->info);
        $view->show_404();
        $view->show_foot();
    }

    // Hiển thị trang mua gói
    public function show_packages()
    {
        $view = new View_Package();
        $model = new Model_Package();
        
        $packages = $model->get_all_packages();
        $remaining_tests = $model->get_total_remaining_tests($this->info['ID']);
        $remaining_tests += $this->user_info->remaining_number;
        
        $view->show_head_left($this->info);
        $view->show_packages($packages, $remaining_tests);
        $view->show_foot();
    }

    // Hiển thị lịch sử mua gói
    public function show_order_history()
    {
        $view = new View_Package();
        $model = new Model_Package();
        
        $orders = $model->get_student_order_history($this->info['ID']);
        $student_packages = $model->get_student_packages($this->info['ID']);
        $remaining_tests = $model->get_total_remaining_tests($this->info['ID']);
        
        $view->show_head_left($this->info);
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

        // var_dump($this->info); die;
        
        // Tạo mã đơn hàng
        $order_code = 'PKG' . time() . $this->info['ID'];
        
        // Tạo đơn hàng
        if ($model->create_order($this->info['ID'], $package_id, $order_code, $package->price)) {
            // Tạo thông tin thanh toán SePay
            $sepay_data = $this->create_sepay_payment($order_code, $package->price, $package->package_name);
            
            $result['status'] = 1;
            $result['message'] = 'Tạo đơn hàng thành công';
            $result['order_code'] = $order_code;
            $result['amount'] = $package->price;
            $result['package_name'] = $package->package_name;
            $result['payment_data'] = $sepay_data;
        } else {
            $result['status'] = 0;
            $result['message'] = 'Lỗi tạo đơn hàng';
        }
        
        echo json_encode($result);
    }

    // Tạo thông tin thanh toán SePay
    private function create_sepay_payment($order_code, $amount, $package_name)
    {
        // Thông tin tài khoản SePay (cần cấu hình trong config)
        $bank_id = '';
        $account_number = Config::BANK_STK; // Số tài khoản nhận
        $account_name = Config::BANK_CODE;
        
        // Nội dung chuyển khoản
        $transfer_content = $order_code;
        
        // Tạo link QR SePay
        $qr_url = "https://img.vietqr.io/image/{$bank_id}-{$account_number}-compact2.jpg?amount={$amount}&addInfo={$transfer_content}&accountName=" . urlencode($account_name);
        
        return array(
            'qr_url' => $qr_url,
            'bank_name' => 'VPBank',
            'account_number' => $account_number,
            'account_name' => $account_name,
            'amount' => $amount,
            'transfer_content' => $transfer_content,
            'order_code' => $order_code
        );
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

        // Nếu đơn hàng đã thanh toán thành công, cộng gói cho khách hàng
        if ($order->payment_status === 'completed' && !$order->package_added) {
            // Nếu chưa có gói hoặc số lượt thi chưa được cộng đủ, thực hiện cộng gói
            $model->add_student_package($order->student_id, $order->package_id, $order->test_count);

            // Đánh dấu đã cộng gói để tránh cộng trùng lặp
            $model->mark_package_added($order_code);
        }
        
        echo json_encode([
            'status' => 1,
            'payment_status' => $order->payment_status === 'completed' ? 'Paid' : 'Unpaid',
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
        } else {
            echo json_encode(['status' => 0, 'message' => 'Đơn hàng sẽ được tự động cập nhật']);
            return;
        }

        // Cập nhật trạng thái đơn hàng
        $model->update_order_status($order_code, 'completed', 'MOCK_TXN_' . time());

        // Nếu đơn hàng đã thanh toán thành công và chưa cộng gói, cộng gói cho khách hàng
        if ($order->payment_status === 'completed' && !$order->package_added) {
            // Cộng gói cho khách hàng
            $model->add_student_package($order->student_id, $order->package_id, $order->test_count);

            // Đánh dấu đã cộng gói để tránh cộng trùng lặp
            $model->mark_package_added($order_code);
        }
        
        echo json_encode(['status' => 1, 'message' => 'Xác nhận thanh toán thành công']);
    }
}
