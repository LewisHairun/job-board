$(document).ready(function () {
    $(".btn-add-prof-experience").on("click", function(e) {
        e.preventDefault();
        const counter_prof_exp = +$("#widget-counter-prof-exp").val();
        const prototype = $("#update_profile_profExperiences").data("prototype");
        const new_widget = prototype.replace(/__name__/g, counter_prof_exp);

        $("#update_profile_profExperiences").append(new_widget);
        $("#widget-counter-prof-exp").val(counter_prof_exp + 1);

        deleteProfExp();
    }); 

    function deleteProfExp() {
        $(".btn-delete-prof-exp").on("click", function() { 
            const target = $(this).attr("data-target");
            $(target).remove();
        });
    }

    deleteProfExp();
});
