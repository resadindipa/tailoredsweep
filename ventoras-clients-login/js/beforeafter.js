$(document).ready(function() {

    $.ajax({
        type: "POST",
        async: false,
        // dataType: 'json',
        url: "../beforeafter/load_beforeafter.php",
        data: { page: "d" },
        success: function(data) {

            if (data.message != "nobeforeaftersections") {

                $("#beforeafter-container").append(data);

            } else {
                // if (data.message == "nobeforeaftersections") {
                $("#form-error").show();
                $("#beforeafter-container").hide();
            }

        }
    });

});