$(document).ready(function() {

    $("#submitbtn").click(function(e) {
        e.preventDefault();



        //hide the errors initially
        $("#form-error").hide();
        $("#form-error").text("");

        let usernameEmail = $("#usernameemail").val();
        let password = $("#password").val();

        if (usernameEmail == "" || password == "") {
            showFormError("Both Username/Email and Password Fields Should be Completed.");
        } else {
            //disable the Form Submit button, so user won't click it again till the response comes from .php 
            $("#submitbtn").prop("disabled", true);
            
            $.ajax({
                type: "POST",
                async: true,
                dataType: 'json',
                url: "login/login.php",
                data: {
                    usernameemail: usernameEmail,
                    password: password
                },
                success: function(data) {
                    //enable the submit button back on
                    $("#submitbtn").prop("disabled", false);
                    if (data.success == true) {
                        window.location.href = "home.php";
                    } else {
                        showFormError(data.message);
                    }
                }
            });
        }

    });

    function showFormError(formError = "") {
        $("#form-error").text(formError);
        $("#form-error").show();
    }
});