$(document).ready(function() {
    $('.modal').modal();
    $('#trigger-sidebar').on('click', function() {
        $('#sidebar-left').toggleClass('sidebar-show');
        $('#menu-icon').toggleClass('rot');
        $('#logout').toggleClass('sidebar-show');
        $('#box-content').toggleClass('box-content-mini');
        // $('#footer').toggleClass('footer-mini');

        togle_sidebar();
    });
    $('#btn-logout').on('click', function() {
        logout();
    });
    $("form").on('submit', function(event) {
        event.preventDefault();
    });
    $("form.form_test").on('submit', function(event) {
        event.preventDefault();
        submit_test(this.id);
        this.reset();
    });
});

function show_status(json_data) {
    if (json_data.status) {
        $('#status').addClass('success');
        $('#status').removeClass('failed');
    } else {
        $('#status').addClass('failed');
        $('#status').removeClass('success');
    }
    $('#status').html(json_data.status_value);
    $('#status').animate({
        'height': '65',
        'line-height': '65px',
        'opacity': '1'
    }, 500);
    $('#status').delay(1000).animate({
        'opacity': '0',
        'height': '0',
        'line-height': '0px'
    }, 500);
}

function logout() {
    var url = "index.php?action=logout";
    var data = {
        confirm: true
    };
    var success = function(result) {
        var json_data = $.parseJSON(result);
        show_status(json_data);
        if (json_data.status) {
            setTimeout(function() {
                window.location.replace("index.php");
            }, 1500);
        }
    };
    $.post(url, data, success);
}

function valid_email_on_profiles(data) {
    var new_email = $('#profiles-new-email').val();
    var current_email = $('#profiles-current-email').val();
    var url = "index.php?action=valid_email_on_profiles";
    var data1 = {
        new_email: new_email,
        current_email: current_email
    };
    var success = function(result) {
        var json_data = $.parseJSON(result);
        if (json_data.status) {
            $('#valid-email-true').removeClass('hidden');
            $('#valid-email-false').addClass('hidden');
        } else {
            $('#valid-email-false').removeClass('hidden');
            $('#valid-email-true').addClass('hidden');
        }
    };
    $.post(url, data1, success);
}

function submit_test(id) {
    $('#preload').removeClass('hidden');
    var data = $('#'+id).serialize();
    var url = "index.php?action=check_password";
    var success = function(result) {
        var json_data = $.parseJSON(result);
        show_status(json_data);
        if (json_data.status) {
            setTimeout(function() {
                location.reload();
            }, 1500);
        }
        $('#preload').addClass('hidden');
    };
    $.post(url, data, success);
}

function togle_sidebar() {
    var url = "index.php?action=toggle_sidebar";
    var success = function(result) {};
    $.get(url, success);
}

function check_password() {
    var password = $("input[name='password']").val();
    var test_code = $("input[name='test_code']").val();
    var url = "index.php?action=check_password";
    var data = {
        password: password,
        test_code: test_code
    };
    var success = function(result) {
        var json_data = $.parseJSON(result);
        if (json_data.status == 1) {
            window.location.href = "lam-bai-thi";
        } else {
            // Kiểm tra nếu lỗi do hết lượt thi
            if (json_data.status_value.includes("hết lượt thi")) {
                // Hiển thị modal popup thay vì toast
                if ($('#no-attempts-modal').length) {
                    $('#no-attempts-modal').modal('open');
                } else {
                    // Nếu modal chưa có, tạo modal động
                    var modalHTML = `
                        <div id="no-attempts-modal" class="modal">
                            <div class="modal-content">
                                <h4><i class="material-icons left red-text">warning</i>Hết lượt thi</h4>
                                <p>Bạn đã hết lượt thi. Vui lòng mua thêm gói thi để tiếp tục làm bài.</p>
                                <div class="row">
                                    <div class="col s12">
                                        <div class="card orange lighten-4">
                                            <div class="card-content">
                                                <span class="card-title"><i class="material-icons left">info</i>Thông tin</span>
                                                <p>• Mỗi lần làm bài thi sẽ tiêu tốn 1 lượt thi</p>
                                                <p>• Bạn có thể mua thêm gói thi với giá ưu đãi</p>
                                                <p>• Lượt thi không bị mất khi thoát giữa chừng</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <a href="javascript:;" class="modal-close waves-effect waves-red btn-flat">Để sau</a>
                                <a href="danh-sach-goi" class="waves-effect waves-green btn green">
                                    <i class="material-icons left">shopping_cart</i>Mua Gói Thi
                                </a>
                            </div>
                        </div>
                    `;
                    $('body').append(modalHTML);
                    $('#no-attempts-modal').modal();
                    $('#no-attempts-modal').modal('open');
                }
            } else {
                M.toast({html: json_data.status_value, classes: 'red'});
            }
        }
        $("#password_checking").fadeOut();
        $('.wave').removeClass('show-wave');
    };
    var error = function() {
        $("#password_checking").fadeOut();
        $('.wave').removeClass('show-wave');
        M.toast({html: 'Lỗi kết nối!', classes: 'red'});
    };
    $("#password_checking").fadeIn();
    $('.wave').addClass('show-wave');
    $.post(url, data, success).fail(error);
}