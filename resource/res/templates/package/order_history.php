
<div class="row">
    <div class="col s12">
        <div class="card">
            <div class="card-content">
                <span class="card-title">
                    <i class="material-icons left">history</i>
                    Quản Lý Gói Thi
                </span>
                <div class="row">
                    <div class="col s12 m6">
                        <div class="card blue lighten-4">
                            <div class="card-content center-align">
                                <h4 class="blue-text"><?php echo $remaining_tests; ?></h4>
                                <p>Lượt thi còn lại</p>
                            </div>
                        </div>
                    </div>
                    <div class="col s12 m6">
                        <div class="card green lighten-4">
                            <div class="card-content center-align">
                                <h4 class="green-text"><?php echo count($student_packages); ?></h4>
                                <p>Gói đang sở hữu</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Gói hiện tại -->
<div class="row">
    <div class="col s12">
        <div class="card">
            <div class="card-content">
                <span class="card-title">
                    <i class="material-icons left">inventory</i>
                    Gói Thi Hiện Tại
                </span>
                <?php if(empty($student_packages)): ?>
                    <p>Bạn chưa có gói thi nào. <a href="index.php?action=show_packages">Mua gói thi ngay!</a></p>
                <?php else: ?>
                    <table class="striped responsive-table">
                        <thead>
                            <tr>
                                <th>Tên Gói</th>
                                <th>Mô Tả</th>
                                <th>Lượt Thi Còn Lại</th>
                                <th>Tổng Lượt Thi</th>
                                <th>Ngày Mua</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach($student_packages as $pkg): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($pkg->package_name); ?></td>
                                <td><?php echo htmlspecialchars($pkg->package_description); ?></td>
                                <td>
                                    <span class="badge <?php echo $pkg->remaining_tests > 0 ? 'green' : 'red'; ?> white-text">
                                        <?php echo $pkg->remaining_tests; ?>
                                    </span>
                                </td>
                                <td><?php echo $pkg->total_tests; ?></td>
                                <td><?php echo date('d/m/Y H:i', strtotime($pkg->purchase_date)); ?></td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<!-- Lịch sử đơn hàng -->
<div class="row">
    <div class="col s12">
        <div class="card">
            <div class="card-content">
                <span class="card-title">
                    <i class="material-icons left">receipt</i>
                    Lịch Sử Đơn Hàng
                </span>
                <?php if(empty($orders)): ?>
                    <p>Chưa có đơn hàng nào.</p>
                <?php else: ?>
                    <table class="striped responsive-table">
                        <thead>
                            <tr>
                                <th>Mã Đơn Hàng</th>
                                <th>Gói Thi</th>
                                <th>Số Lượt</th>
                                <th>Giá Tiền</th>
                                <th>Trạng Thái</th>
                                <th>Ngày Đặt</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach($orders as $order): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($order->order_code); ?></td>
                                <td><?php echo htmlspecialchars($order->package_name); ?></td>
                                <td><?php echo $order->test_count; ?> lượt</td>
                                <td><?php echo number_format($order->amount); ?> VNĐ</td>
                                <td>
                                    <?php
                                    $status_class = '';
                                    $status_text = '';
                                    switch($order->payment_status) {
                                        case 'pending':
                                            $status_class = 'orange';
                                            $status_text = 'Chờ thanh toán';
                                            break;
                                        case 'completed':
                                            $status_class = 'green';
                                            $status_text = 'Đã thanh toán';
                                            break;
                                        case 'failed':
                                            $status_class = 'red';
                                            $status_text = 'Thất bại';
                                            break;
                                        case 'cancelled':
                                            $status_class = 'grey';
                                            $status_text = 'Đã hủy';
                                            break;
                                    }
                                    ?>
                                    <span class="badge <?php echo $status_class; ?> white-text">
                                        <?php echo $status_text; ?>
                                    </span>
                                </td>
                                <td><?php echo date('d/m/Y H:i', strtotime($order->created_at)); ?></td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<div class="fixed-action-btn">
    <a href="index.php?action=show_packages" class="btn-floating btn-large green">
        <i class="large material-icons">add_shopping_cart</i>
    </a>
</div>
