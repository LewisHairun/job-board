$(document).ready(function () {
    $(".select-two-branch").select2({
        placeholder: placeholder_branch,
        allowClear: true
    });

    $(".select-two-ordering-city").select2({
        placeholder: placeholder_ordering_city,
        allowClear: true
    });

    $(".select-two-ordering-job-offer").select2({
        placeholder: placeholder_ordering_job_offer,
        allowClear: true
    });
});