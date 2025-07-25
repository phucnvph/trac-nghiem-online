$(document).ready(function () {
    $("#register_form").on("submit", function (event) {
        event.preventDefault();

        $("#loading").removeClass("hidden");
        $('#btn-register').prop('disabled', true);

        var url = "index.php?action=submit_register";
        var data = {
            name: $("#name").val(),
            username: $("#username").val(),
            email: $("#email").val(),
            birthday: $("#birthday").val(),
        };

        $.post(url, data, function (result) {
            var json_data = $.parseJSON(result);
            console.log(json_data);

            if (json_data.result == 'NG') {

                let textError = ``;
                json_data.errors.forEach(function (error) {
                    textError += error;
                    textError += '<br>';
                });
                $('#show-error').html(textError);
            } else {
                $('#box-register_form').hide();
                $('#box-register-success').show();
            }

            $('#btn-register').prop('disabled', false);
            $("#loading").addClass("hidden");
        });
    });
});

function reload() {
    $("#reload").css("display", "none");
    $("#field_username").css("display", "inline");
    $("#hi").css("display", "none");
    $("#lbl_pw").addClass("hidden");
    $("#password").addClass("hidden");
    $("#btn-login")
        .html("Đăng Nhập")
        .css("width", "49%")
        .attr("onclick", "submit_login()");
    $("#btn-forgot").css("display", "inline");
}

function validateEmail(email) {
    return String(email)
        .toLowerCase()
        .match(
            /^(([^<>()[\]\\.,;:\s@"]+(\.[^<>()[\]\\.,;:\s@"]+)*)|.(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/
        );
};

function valid_username(value) {
    if (value.trim() != '') {
        $('#valid-username-true').removeClass('hidden');
        $('#valid-username-false').addClass('hidden');
    } else {
        $('#valid-username-false').removeClass('hidden');
        $('#valid-username-true').addClass('hidden');
    }
}

function valid_email(value) {
    let valid = validateEmail(value);
    if (valid) {
        $('#valid-email-true').removeClass('hidden');
        $('#valid-email-false').addClass('hidden');
    } else {
        $('#valid-email-false').removeClass('hidden');
        $('#valid-email-true').addClass('hidden');
    }
}