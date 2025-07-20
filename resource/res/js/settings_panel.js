$(function () {
    $('#form_settings').on('submit', function (event) {
        event.preventDefault();
        $('#preload').removeClass('hidden');
        let url = "index.php?action=save_settings";

        let formData = $(this).serializeArray().reduce(function (obj, item) {
            obj[item.name] = item.value;
            return obj;
        }, {});

        $.post(url, formData, function (result) {
            let json_data = $.parseJSON(result);
            show_status(json_data);

            $('#preload').addClass('hidden');
        });
    });
});
