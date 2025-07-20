<div class="title-content">
    <span class="title">Quản Lý Gói Thi</span>
</div>
<div class="block-content overflow scrollbar">
    <div class="content">
        <!-- Tab Navigation -->
        <ul class="tabs">
            <li class="tab col s3"><a style="color: #02796e;" href="#orders-tab" class="active"><b>Lịch Sử Đơn Hàng</b></a></li>
            <li class="tab col s3"><a style="color: #02796e;" href="#packages-tab"><b>Gói Thi Học Sinh</b></a></li>
        </ul>

        <!-- Lịch sử đơn hàng -->
        <div id="orders-tab" class="col s12">
            <div class="card">
                <div class="card-content">
                    <span class="card-title">Lịch Sử Đơn Hàng</span>
                    <table class="striped responsive-table" id="orders-table">
                        <thead>
                            <tr>
                                <th>Mã Đơn</th>
                                <th>Học Sinh</th>
                                <th>Gói Thi</th>
                                <th>Lượt Thi</th>
                                <th>Giá Tiền</th>
                                <th>Trạng Thái</th>
                                <th>Ngày Mua</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($orders as $order): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($order->order_code); ?></td>
                                    <td>
                                        <strong><?php echo htmlspecialchars($order->student_name); ?></strong><br>
                                        <small><?php echo htmlspecialchars($order->student_username); ?></small>
                                    </td>
                                    <td><?php echo htmlspecialchars($order->package_name); ?></td>
                                    <td><?php echo $order->test_count; ?> lượt</td>
                                    <td><?php echo number_format($order->amount); ?> VNĐ</td>
                                    <td>
                                        <?php if ($order->payment_status == 'completed'): ?>
                                            <span class="green-text">Hoàn thành</span>
                                        <?php elseif ($order->payment_status == 'pending'): ?>
                                            <span class="orange-text">Chờ thanh toán</span>
                                        <?php else: ?>
                                            <span class="red-text">Đã hủy</span>
                                        <?php endif; ?>
                                    </td>
                                    <td><?php echo date('d/m/Y H:i:s', strtotime($order->created_at)); ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Gói thi học sinh -->
        <div id="packages-tab" class="col s12">
            <div class="card">
                <div class="card-content">
                    <span class="card-title">Gói Thi Của Học Sinh</span>
                    <table class="striped responsive-table" id="packages-table">
                        <thead>
                            <tr>
                                <th>Học Sinh</th>
                                <th>Gói Thi</th>
                                <th>Lượt Còn Lại</th>
                                <th>Ngày Mua</th>
                                <th>Thao Tác</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($student_packages as $package): ?>
                                <tr id="package-<?php echo $package->id; ?>">
                                    <td>
                                        <strong><?php echo htmlspecialchars($package->student_name); ?></strong><br>
                                        <small><?php echo htmlspecialchars($package->student_username); ?></small>
                                    </td>
                                    <td><?php echo htmlspecialchars($package->package_name); ?></td>
                                    <td class="remaining-tests"><?php echo $package->remaining_tests; ?></td>
                                    <td><?php echo date('d/m/Y H:i:s', strtotime($package->purchase_date)); ?></td>
                                    <td>
                                        <a href="javascript:;" class="btn-small blue edit-tests-btn"
                                            data-package-id="<?php echo $package->id; ?>"
                                            data-remaining="<?php echo $package->remaining_tests; ?>">
                                            <i class="material-icons left">edit</i>
                                        </a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Cấp phát thủ công -->
        <div id="grant-tab" class="col s12">
            <div class="card">
                <div class="card-content">
                    <span class="card-title">Cấp Phát Lượt Thi Thủ Công</span>
                    <form id="grant-form">
                        <div class="row">
                            <div class="input-field col s12 m4">
                                <select name="student_id" required>
                                    <option value="">Chọn học sinh</option>
                                    <?php foreach ($students as $student): ?>
                                        <option value="<?php echo $student->student_id; ?>"><?php echo htmlspecialchars($student->username . ' (' . $student->email . ')'); ?></option>
                                    <?php endforeach; ?>
                                </select>
                                <label for="student_id">Học Sinh</label>
                            </div>
                            <div class="input-field col s12 m5">
                                <select name="package_id" required>
                                    <option value="">Chọn gói</option>
                                    <?php foreach ($packageMaster as $package): ?>
                                        <option value="<?php echo $package->package_id; ?>"><?php echo htmlspecialchars($package->package_name); ?></option>
                                    <?php endforeach; ?>
                                </select>
                                <label for="package_id">Gói</label>
                            </div>
                            <div class="input-field col s12 m3">
                                <input type="number" name="test_count" min="1" required>
                                <label for="test-count">Số Lượt Thi</label>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col s12">
                                <button type="submit" class="btn green"><i class="material-icons left">add</i>Cấp Phát</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal sửa lượt thi -->
<div id="edit-tests-modal" class="modal">
    <div class="modal-content">
        <h4>Sửa Lượt Thi</h4>
        <form id="edit-tests-form">
            <input type="hidden" id="edit-package-id" name="package_id">
            <div class="input-field">
                <input type="number" id="edit-remaining-tests" name="remaining_tests" min="0" required>
                <label for="edit-remaining-tests">Lượt Thi Còn Lại</label>
            </div>
        </form>
    </div>
    <div class="modal-footer">
        <a href="javascript:;" class="modal-close btn-flat">Hủy</a>
        <a href="javascript:;" id="save-tests-btn" class="btn green">Lưu</a>
    </div>
</div>

<script src="res/libs/DataTables/js/jquery.dataTables.js"></script>
<script>
    $(document).ready(function() {
        $('.tabs').tabs();
        $('.modal').modal();

        // DataTable cho bảng đơn hàng
        $('#orders-table').DataTable({
            "language": {
                "lengthMenu": "Hiển thị _MENU_ dòng",
                "zeroRecords": "Không tìm thấy dữ liệu",
                "info": "Hiển thị trang _PAGE_/_PAGES_",
                "infoEmpty": "Không có dữ liệu",
                "search": "Tìm kiếm:",
                "paginate": {
                    "first": "Đầu",
                    "last": "Cuối",
                    "next": "Tiếp",
                    "previous": "Trước"
                }
            }
        });

        // DataTable cho bảng gói thi
        $('#packages-table').DataTable({
            "language": {
                "lengthMenu": "Hiển thị _MENU_ dòng",
                "zeroRecords": "Không tìm thấy dữ liệu",
                "info": "Hiển thị trang _PAGE_/_PAGES_",
                "infoEmpty": "Không có dữ liệu",
                "search": "Tìm kiếm:",
                "paginate": {
                    "first": "Đầu",
                    "last": "Cuối",
                    "next": "Tiếp",
                    "previous": "Trước"
                }
            }
        });

        // Xử lý cấp phát thủ công
        $('#grant-form').submit(function(e) {
            e.preventDefault();
            $.ajax({
                url: 'index.php?action=manual_grant_tests',
                method: 'POST',
                data: $(this).serialize(),
                dataType: 'json',
                success: function(response) {
                    if (response.status === 1) {
                        M.toast({
                            html: response.status_value,
                            classes: 'green'
                        });
                        $('#grant-form')[0].reset();
                        setTimeout(() => location.reload(), 1000);
                    } else {
                        M.toast({
                            html: response.status_value,
                            classes: 'red'
                        });
                    }
                },
                error: function() {
                    M.toast({
                        html: 'Lỗi kết nối',
                        classes: 'red'
                    });
                }
            });
        });

        // Xử lý sửa lượt thi
        $('.edit-tests-btn').click(function() {
            const packageId = $(this).data('package-id');
            const remaining = $(this).data('remaining');

            $('#edit-package-id').val(packageId);
            $('#edit-remaining-tests').val(remaining);
            M.updateTextFields();
            $('#edit-tests-modal').modal('open');
        });

        $('#save-tests-btn').click(function() {
            $.ajax({
                url: 'index.php?action=update_student_tests',
                method: 'POST',
                data: $('#edit-tests-form').serialize(),
                dataType: 'json',
                success: function(response) {
                    if (response.status === 1) {
                        M.toast({
                            html: response.status_value,
                            classes: 'green'
                        });
                        $('#edit-tests-modal').modal('close');
                        setTimeout(() => location.reload(), 1000);
                    } else {
                        M.toast({
                            html: response.status_value,
                            classes: 'red'
                        });
                    }
                },
                error: function() {
                    M.toast({
                        html: 'Lỗi kết nối',
                        classes: 'red'
                    });
                }
            });
        });
    });
</script>