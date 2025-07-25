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
                                <h5><i class="material-icons left">assessment</i>Lượt thi hiện tại: <span class="green white-text"><?php echo $remaining_tests; ?> lượt</span></h5>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <?php foreach ($packages as $package): ?>
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
<div id="payment-modal" class="modal modal-fixed-footer" style="width: 60%; max-width: 600px;">
    <div class="modal-content">
        <h4 class="center">Thanh toán QR Code</h4>

        <!-- Thông tin đơn hàng -->
        <div id="payment-info" class="row">
            <div class="col s12">
                <div class="card blue lighten-5">
                    <div class="card-content">
                        <p><strong>Gói:</strong> <span id="modal-package-name"></span></p>
                        <p><strong>Số tiền:</strong> <span id="modal-price" class="red-text text-darken-2"></span> VNĐ</p>
                        <p><strong>Mã đơn hàng:</strong> <span id="modal-order-code" class="blue-text"></span></p>
                    </div>
                </div>
            </div>
        </div>

        <!-- QR Code -->
        <div id="qr-code-section" class="center-align" style="display: none;">
            <div class="card">
                <div class="card-content">
                    <h5 class="green-text"><i class="material-icons left">smartphone</i>Quét mã QR để thanh toán</h5>
                    <div id="qr-code-container" class="center-align" style="margin: 20px 0;">
                        <img id="qr-code-image" src="" alt="QR Code" style="max-width: 300px; border: 2px solid #ddd; border-radius: 8px;">
                    </div>
                    <div class="row">
                        <div class="col s12 m6">
                            <p><strong>Ngân hàng:</strong> <?= Config::BANK_CODE ?></p>
                            <p><strong>Số tài khoản:</strong> <?= Config::BANK_STK ?></p>
                        </div>
                        <div class="col s12 m6">
                            <p><strong>Số tiền:</strong> <span id="qr-amount" class="red-text"></span> VNĐ</p>
                            <p><strong>Nội dung:</strong> <span id="qr-content" class="blue-text"></span></p>
                        </div>
                    </div>
                    <div class="divider"></div>
                    <p style="margin-top: 15px;">
                        <i class="material-icons tiny">info</i>
                        <b style="color: red;">Sau khi chuyển khoản thành công, đơn hàng sẽ được tự động cập nhật.</b>
                    </p>
                </div>
            </div>
        </div>

        <!-- Trạng thái thanh toán -->
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
            <p>Đang kiểm tra thanh toán...</p>
        </div>

        <!-- Thanh toán thành công -->
        <div id="payment-success" class="center-align" style="display: none;">
            <i class="material-icons large green-text">check_circle</i>
            <h5 class="green-text">Thanh toán thành công!</h5>
            <p>Gói thi đã được thêm vào tài khoản của bạn.</p>
        </div>
    </div>

    <div class="modal-footer">
        <a href="javascript:;" class="btn modal-close waves-effect waves-red btn-flat" id="btn-cancel">Đóng</a>
        <a href="javascript:;" id="btn-show-qr" class="waves-effect waves-blue btn blue" style="display: none;">
            <i class="material-icons left">qr_code</i>Thanh toán
        </a>
        <a href="javascript:;" id="btn-close-success" class="waves-effect waves-green btn green modal-close" style="display: none;">
            <i class="material-icons left">done</i>Hoàn tất
        </a>
    </div>
</div>
</div>

<script>
    const BANK_CODE = "<?php echo Config::BANK_CODE ?>";
    const BANK_STK = "<?php echo Config::BANK_STK ?>";

    $(document).ready(function() {
        $('.modal').modal({
            dismissible: false,
            onCloseEnd: function() {
                resetPaymentModal();
            }
        });

        let currentOrderCode = '';
        let currentAmount = 0;
        let pay_status = 'Unpaid';
        let paymentCheckInterval = null;

        function resetPaymentModal() {
            $('#payment-info').show();
            $('#qr-code-section').hide();
            $('#payment-status').hide();
            $('#payment-success').hide();
            $('#btn-show-qr').hide();
            $('#btn-confirm-payment').hide();
            $('#btn-close-success').hide();
            $('#btn-cancel').show();
            currentOrderCode = '';
            currentAmount = 0;

            pay_status = 'Unpaid';

            // Dừng kiểm tra payment status
            if (paymentCheckInterval) {
                clearInterval(paymentCheckInterval);
                paymentCheckInterval = null;
            }
        }

        // Hàm kiểm tra trạng thái đơn hàng tự động
        function check_payment_status() {
            if (pay_status === 'Unpaid' && currentOrderCode) {
                $.ajax({
                    type: "POST",
                    data: {
                        order_code: currentOrderCode
                    },
                    url: "/check-order-status",
                    dataType: "json",
                    success: function(data) {
                        if (data.payment_status === "Paid") {
                            // Dừng kiểm tra
                            if (paymentCheckInterval) {
                                clearInterval(paymentCheckInterval);
                                paymentCheckInterval = null;
                            }

                            // Ẩn tất cả và hiển thị thành công
                            $('#payment-info').hide();
                            $('#qr-code-section').hide();
                            $('#payment-status').hide();
                            $('#payment-success').show();

                            // Hiển thị nút hoàn tất
                            $('#btn-show-qr').hide();
                            $('#btn-confirm-payment').hide();
                            $('#btn-cancel').hide();
                            $('#btn-close-success').show();

                            pay_status = 'Paid';
                            M.toast({
                                html: 'Thanh toán thành công! Tự động phát hiện.',
                                classes: 'green'
                            });
                        }
                    },
                    error: function() {
                        console.log('Lỗi kiểm tra trạng thái thanh toán');
                    }
                });
            }
        }

        function generateQRCode(orderCode, amount) {
            const content = orderCode;
            const template = 'compact';
            const qrUrl = `https://qr.sepay.vn/img?bank=${BANK_CODE}&acc=${BANK_STK}&template=${template}&amount=${amount}&des=${content}`;

            $('#qr-code-image').attr('src', qrUrl);
            $('#qr-amount').text(new Intl.NumberFormat().format(amount));
            $('#qr-content').text(content);

            // Bắt đầu kiểm tra trạng thái thanh toán tự động mỗi 3 giây
            pay_status = 'Unpaid';
            paymentCheckInterval = setInterval(check_payment_status, 3000);
        }

        // Xử lý mua gói
        $('.btn-buy-package').click(function() {
            const packageId = $(this).data('package-id');
            const packageName = $(this).data('package-name');
            const price = $(this).data('price');

            // Reset modal
            resetPaymentModal();

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
                        currentAmount = price;

                        $('#modal-package-name').text(packageName);
                        $('#modal-price').text(new Intl.NumberFormat().format(price));
                        $('#modal-order-code').text(response.order_code);

                        // Hiển thị nút "Hiển thị QR Code"
                        generateQRCode(currentOrderCode, currentAmount);

                        $('#payment-info').hide();
                        $('#qr-code-section').show();
                        $('#btn-confirm-payment').show();

                        $('#payment-modal').modal('open');
                    } else {
                        M.toast({
                            html: response.message,
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

        // Hiển thị QR Code
        $('#btn-show-qr').click(function() {
            if (!currentOrderCode || !currentAmount) return;

            generateQRCode(currentOrderCode, currentAmount);

            $('#payment-info').hide();
            $('#qr-code-section').show();
            $('#btn-show-qr').hide();
            $('#btn-confirm-payment').show();
        });

        // Xử lý xác nhận thanh toán
        $('#btn-confirm-payment').click(function() {
            if (!currentOrderCode) return;

            $('#qr-code-section').hide();
            $('#payment-status').show();
            $(this).hide();
            $('#btn-cancel').hide();

            // Kiểm tra thanh toán (Mock - trong thực tế sẽ gọi API SePay)
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
                        $('#payment-success').show();
                        $('#btn-close-success').show();
                        M.toast({
                            html: 'Thanh toán thành công!',
                            classes: 'green'
                        });
                    } else {
                        M.toast({
                            html: response.message,
                            classes: 'red'
                        });
                        $('#qr-code-section').show();
                        $('#btn-confirm-payment').show();
                        $('#btn-cancel').show();
                    }
                },
                error: function() {
                    $('#payment-status').hide();
                    $('#qr-code-section').show();
                    $('#btn-confirm-payment').show();
                    $('#btn-cancel').show();
                    M.toast({
                        html: 'Lỗi kết nối',
                        classes: 'red'
                    });
                }
            });
        });

        // Đóng modal sau khi thanh toán thành công
        $('#btn-close-success').click(function() {
            setTimeout(function() {
                location.reload();
            }, 500);
        });
    });
</script>