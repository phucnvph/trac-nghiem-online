
<?php

/**
 * HỆ THỐNG TRẮC NGHIỆM ONLINE
 * Controller Controller_Sepay_Callback
 * @author: System
 */

require_once 'config/config.php';
require_once 'config/database.php';
require_once 'models/model_package.php';
date_default_timezone_set(Config::TIMEZONE);

class Controller_Callback
{
    public function __construct() {}

    public function callback()
    {
        // Set content type for response
        header('Content-Type: application/json');

        // Lấy dữ liệu từ webhook
        $input = file_get_contents('php://input');
        $data = json_decode($input);

        if (!is_object($data)) {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => 'No data']);
            exit();
        }

        // Khởi tạo các biến từ webhook
        $gateway = $data->gateway ?? '';
        $transaction_date = $data->transactionDate ?? '';
        $account_number = $data->accountNumber ?? '';
        $sub_account = $data->subAccount ?? null;
        $transfer_type = $data->transferType ?? '';
        $transfer_amount = $data->transferAmount ?? 0;
        $accumulated = $data->accumulated ?? 0;
        $code = $data->code ?? '';
        $transaction_content = $data->content ?? '';
        $reference_number = $data->referenceCode ?? '';
        $body = $data->description ?? '';

        // Kiểm tra giao dịch tiền vào
        if ($transfer_type !== 'in') {
            echo json_encode(['success' => false, 'message' => 'Not an incoming transaction']);
            exit();
        }

        // Tạo connection database
        $database = new Database();

        try {
            // Lưu giao dịch vào bảng transactions (nếu có bảng này)
            $sql = "INSERT INTO sepay_transactions 
            (gateway, transaction_date, account_number, sub_account, amount_in, accumulated, code, transaction_content, reference_number, body, created_at) 
            VALUES 
            (:gateway, :transaction_date, :account_number, :sub_account, :amount_in, :accumulated, :code, :transaction_content, :reference_number, :body, NOW())";

            $params = [
                ':gateway' => $gateway,
                ':transaction_date' => $transaction_date,
                ':account_number' => $account_number,
                ':sub_account' => $sub_account,
                ':amount_in' => $transfer_amount,
                ':accumulated' => $accumulated,
                ':code' => $code,
                ':transaction_content' => $transaction_content,
                ':reference_number' => $reference_number,
                ':body' => $body
            ];

            $database->set_query($sql, $params);
            $database->execute_return_status();
        } catch (Exception $e) {
            // Nếu bảng transactions chưa tồn tại, bỏ qua lỗi này
        }

        // Tách mã đơn hàng từ nội dung giao dịch
        // Mã đơn hàng thường có dạng PKGxxxxxxxxx
        $order_code = '';
        if (preg_match('/PKG\d+/', $transaction_content, $matches)) {
            $order_code = $matches[0];
        }

        if (empty($order_code)) {
            echo json_encode(['success' => false, 'message' => 'Order code not found in transaction content']);
            exit();
        }

        // Khởi tạo model package
        $model = new Model_Package();

        // Lấy thông tin đơn hàng
        $order = $model->get_order_by_code($order_code);

        if (!$order) {
            echo json_encode(['success' => false, 'message' => 'Order not found: ' . $order_code]);
            exit();
        }

        // Kiểm tra số tiền có khớp không
        if ($transfer_amount != $order->amount) {
            echo json_encode([
                'success' => false,
                'message' => 'Amount mismatch. Expected: ' . $order->amount . ', Received: ' . $transfer_amount
            ]);
            exit();
        }

        // Kiểm tra đơn hàng đã được xử lý chưa
        if ($order->payment_status === 'completed') {
            echo json_encode(['success' => true, 'message' => 'Order already processed']);
            exit();
        }

        try {
            // Cập nhật trạng thái đơn hàng thành completed
            $updated = $model->update_order_status($order_code, 'completed', $reference_number);

            if ($updated) {
                // Thêm gói thi cho học sinh
                $package_added = $model->add_student_package($order->student_id, $order->package_id, $order->test_count);

                if ($package_added) {
                    // Đánh dấu đã cộng gói để tránh trùng lặp
                    $model->mark_package_added($order_code);

                    echo json_encode([
                        'success' => true,
                        'message' => 'Payment processed successfully',
                        'order_code' => $order_code,
                        'amount' => $transfer_amount
                    ]);
                    exit();
                } else {
                    // Rollback trạng thái đơn hàng nếu không thể cộng gói
                    $model->update_order_status($order_code, 'pending', null);
                    echo json_encode(['success' => false, 'message' => 'Failed to add package to student']);
                    exit();
                }
            } else {
                echo json_encode(['success' => false, 'message' => 'Failed to update order status']);
                exit();
            }
        } catch (Exception $e) {
            echo json_encode(['success' => false, 'message' => 'Database error: ' . $e->getMessage()]);
            exit();
        }
    }
}
