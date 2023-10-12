$(document).ready(function () {
    $(".select-two").select2({
        placeholder: $(this).attr("data-placeholder") ?? "",
        allowClear: true
    });
});