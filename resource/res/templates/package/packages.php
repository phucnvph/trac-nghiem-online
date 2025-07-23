
<div class="row">
    <div class="col s12">
        <div class="card">
            <div class="card-content">
                <span class="card-title">
                    <i class="material-icons left">shopping_cart</i>
                    Mua Gói Lượt Thi
                </span>
                <div class="row">
                    <div class="col s12">
                        <div class="card blue lighten-4">
                            <div class="card-content">
                                <h5><i class="material-icons left">assessment</i>Lượt thi hiện tại: <span class="badge green white-text"><?php echo $remaining_tests; ?> lượt</span></h5>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <?php foreach($packages as $package): ?>
    <div class="col s12 m6 l4">
        <div class="card">
            <div class="card-content">
                <span class="card-title"><?php echo htmlspecialchars($package->package_name); ?></span>
                <p><?php echo htmlspecialchars($package->package_description); ?></p>
                <br>
                <div class="center-align">
                    <h4 class="green-text"><?php echo number_format($package->price); ?> VNĐ</h4>
                    <p><strong><?php echo $package->test_count; ?> lượt thi</strong></p>
                    <p class="grey-text">≈ <?php echo number_format($package->price / $package->test_count); ?> VNĐ/lượt</p>
                </div>
            </div>
            <div class="card-action center-align">
                <button class="btn green waves-effect waves-light btn-buy-package" 
                        data-package-id="<?php echo $package->package_id; ?>"
                        data-package-name="<?php echo htmlspecialchars($package->package_name); ?>"
                        data-price="<?php echo $package->price; ?>">
                    <i class="material-icons left">payment</i>Mua Ngay
                </button>
            </div>
        </div>
    </div>
    <?php endforeach; ?>
</div>

<!-- Modal thanh toán -->
<div id="payment-modal" class="modal">
    <div class="modal-content">
        <h4>Xác nhận mua gói</h4>
        <div id="payment-info">
            <p><strong>Gói:</strong> <span id="modal-package-name"></span></p>
            <p><strong>Giá:</strong> <span id="modal-price"></span> VNĐ</p>
            <p><strong>Mã đơn hàng:</strong> <span id="modal-order-code"></span></p>
        </div>
        <div id="payment-status" class="center-align" style="display: none;">
            <div class="preloader-wrapper small active">
                <div class="spinner-layer spinner-green-only">
                    <div class="circle-clipper left">
                        <div class="circle"></div>
                    </div>
                    <div class="gap-patch">
                        <div class="circle"></div>
                    </div>
                    <div class="circle-clipper right">
                        <div class="circle"></div>
                    </div>
                </div>
            </div>
            <p>Đang xử lý thanh toán...</p>
        </div>
    </div>
    <div class="modal-footer">
        <a href="#!" class="modal-close waves-effect waves-red btn-flat">Hủy</a>
        <a href="#!" id="btn-confirm-payment" class="waves-effect waves-green btn green">
            <i class="material-icons left">check</i>Xác nhận thanh toán
        </a>
    </div>
</div>

<script>
$(document).ready(function(){
    $('.modal').modal();
    
    let currentOrderCode = '';
    
    // Xử lý mua gói
    $('.btn-buy-package').click(function(){
        const packageId = $(this).data('package-id');
        const packageName = $(this).data('package-name');
        const price = $(this).data('price');
        
        // Tạo đơn hàng
        $.ajax({
            url: 'index.php?action=create_order',
            method: 'POST',
            data: {
                package_id: packageId
            },
            dataType: 'json',
            success: function(response) {
                if (response.status === 1) {
                    currentOrderCode = response.order_code;
                    $('#modal-package-name').text(packageName);
                    $('#modal-price').text(new Intl.NumberFormat().format(price));
                    $('#modal-order-code').text(response.order_code);
                    $('#payment-modal').modal('open');
                } else {
                    M.toast({html: response.message, classes: 'red'});
                }
            },
            error: function() {
                M.toast({html: 'Lỗi kết nối', classes: 'red'});
            }
        });
    });
    
    // Xử lý xác nhận thanh toán (Mock)
    $('#btn-confirm-payment').click(function(){
        if (!currentOrderCode) return;
        
        $('#payment-status').show();
        $(this).hide();
        
        // Mock xác nhận thanh toán
        $.ajax({
            url: 'index.php?action=confirm_payment',
            method: 'POST',
            data: {
                order_code: currentOrderCode
            },
            dataType: 'json',
            success: function(response) {
                $('#payment-status').hide();
                if (response.status === 1) {
                    M.toast({html: 'Thanh toán thành công! Đang tải lại trang...', classes: 'green'});
                    setTimeout(function(){
                        location.reload();
                    }, 2000);
                } else {
                    M.toast({html: response.message, classes: 'red'});
                    $('#btn-confirm-payment').show();
                }
            },
            error: function() {
                $('#payment-status').hide();
                $('#btn-confirm-payment').show();
                M.toast({html: 'Lỗi kết nối', classes: 'red'});
            }
        });
    });
});
</script>
