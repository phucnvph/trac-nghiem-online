
<div class="title-content">
    <span class="title">Quản Lý Gói Thi</span>
</div>
<div class="block-content overflow scrollbar">
    <div class="content">
        <!-- Tab Navigation -->
        <ul class="tabs">
            <li class="tab col s3"><a class="active" href="#orders-tab">Lịch Sử Đơn Hàng</a></li>
            <li class="tab col s3"><a href="#packages-tab">Gói Thi Học Sinh</a></li>
            <!-- <li class="tab col s3"><a href="#grant-tab">Cấp Phát Thủ Công</a></li> -->
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
                                <th>Ngày Đặt</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach($orders as $order): ?>
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
                                    <?php if($order->payment_status == 'completed'): ?>
                                        <span class="green-text">Hoàn thành</span>
                                    <?php elseif($order->payment_status == 'pending'): ?>
                                        <span class="orange-text">Chờ thanh toán</span>
                                    <?php else: ?>
                                        <span class="red-text">Đã hủy</span>
                                    <?php endif; ?>
                                </td>
                                <td><?php echo date('d/m/Y H:i', strtotime($order->created_at)); ?></td>
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
                                <!-- <th>Lượt Ban Đầu</th> -->
                                <th>Lượt Còn Lại</th>
                                <th>Ngày Mua</th>
                                <!-- <th>Ghi Chú</th> -->
                                <th>Thao Tác</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach($student_packages as $package): ?>
                            <tr id="package-<?php echo $package->id; ?>">
                                <td>
                                    <strong><?php echo htmlspecialchars($package->student_name); ?></strong><br>
                                    <small><?php echo htmlspecialchars($package->student_username); ?></small>
                                </td>
                                <td><?php echo $package->package_id == 0 ? 'Admin cấp phát' : htmlspecialchars($package->package_name); ?></td>
                                <!-- <td><?php echo $package->original_tests; ?></td> -->
                                <td class="remaining-tests"><?php echo $package->remaining_tests; ?></td>
                                <td><?php echo date('d/m/Y H:i', strtotime($package->purchase_date)); ?></td>
                                <!-- <td><?php echo htmlspecialchars($package->note ?? ''); ?></td> -->
                                <td>
                                    <a href="#!" class="btn-small blue edit-tests-btn" 
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
                            <div class="input-field col s12 m6">
                                <select id="student-select" name="student_id" required>
                                    <option value="">Chọn học sinh</option>
                                    <?php foreach($students as $student): ?>
                                    <option value="<?php echo $student->student_id; ?>">
                                        <?php echo htmlspecialchars($student->name . ' (' . $student->username . ')'); ?>
                                    </option>
                                    <?php endforeach; ?>
                                </select>
                                <label>Học Sinh</label>
                            </div>
                            <div class="input-field col s12 m6">
                                <input type="number" id="test-count" name="test_count" min="1" required>
                                <label for="test-count">Số Lượt Thi</label>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col s12">
                                <button type="submit" class="btn green waves-effect waves-light">
                                    <i class="material-icons left">add</i>Cấp Phát
                                </button>
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
        <a href="#!" class="modal-close btn-flat">Hủy</a>
        <a href="#!" id="save-tests-btn" class="btn green">Lưu</a>
    </div>
</div>

<!-- <script src="res/js/materialize.min.js"></script> -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/js/materialize.min.js"></script>
<!-- <script src="js/jquery.dataTables.min.js" type="text/javascript"></script> -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/datatables/1.10.21/js/jquery.dataTables.min.js" integrity="sha512-BkpSL20WETFylMrcirBahHfSnY++H2O1W+UnEEO4yNIl+jI2+zowyoGJpbtk6bx97fBXf++WJHSSK2MV4ghPcg==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script>
$(document).ready(function(){
    // M.AutoInit();
    $('.tabs').tabs();
    // $('select').formSelect();
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
    $('#grant-form').submit(function(e){
        e.preventDefault();
        $.ajax({
            url: 'index.php?action=manual_grant_tests',
            method: 'POST',
            data: $(this).serialize(),
            dataType: 'json',
            success: function(response) {
                if (response.status === 1) {
                    M.toast({html: response.status_value, classes: 'green'});
                    $('#grant-form')[0].reset();
                    // $('select').formSelect();
                    setTimeout(() => location.reload(), 1500);
                } else {
                    M.toast({html: response.status_value, classes: 'red'});
                }
            },
            error: function() {
                M.toast({html: 'Lỗi kết nối', classes: 'red'});
            }
        });
    });
    
    // Xử lý sửa lượt thi
    $('.edit-tests-btn').click(function(){
        const packageId = $(this).data('package-id');
        const remaining = $(this).data('remaining');
        
        $('#edit-package-id').val(packageId);
        $('#edit-remaining-tests').val(remaining);
        M.updateTextFields();
        $('#edit-tests-modal').modal('open');
    });
    
    $('#save-tests-btn').click(function(){
        $.ajax({
            url: 'index.php?action=update_student_tests',
            method: 'POST',
            data: $('#edit-tests-form').serialize(),
            dataType: 'json',
            success: function(response) {
                if (response.status === 1) {
                    M.toast({html: response.status_value, classes: 'green'});
                    $('#edit-tests-modal').modal('close');
                    setTimeout(() => location.reload(), 1500);
                } else {
                    M.toast({html: response.status_value, classes: 'red'});
                }
            },
            error: function() {
                M.toast({html: 'Lỗi kết nối', classes: 'red'});
            }
        });
    });
});
</script>
