$("#submit-form").click(function(e) {
    e.preventDefault();

    $("#form-error-div").hide();
    $("#form-success-div").hide();


    let password1 = $('#password1').val();
    let password2 = $('#password2').val();

    if (password1 == "" || password2 == "") {
        showFormError("Complete all the fields");
    } else {
        if (password1 == password2) {
            if (password1.length < 8) {
                showFormError("Password should be longer than 8 characters");
            } else {

                //Getting the reset key from URL
                var $_GET = {};

                document.location.search.replace(/\??(?:([^=]+)=([^&]*)&?)/g, function() {
                    function decode(s) {
                        return decodeURIComponent(s.split("+").join(" "));
                    }

                    $_GET[decode(arguments[1])] = decode(arguments[2]);
                });
                let resetKey = $_GET["key"];
                if (resetKey == undefined) {
                    showFormError("Password Reset Key is missing!");
                } else {
                    //Submit the form
                    $.ajax({
                        type: 'POST',
                        dataType: 'json',
                        url: "../password-reset/submit-password-form.php",
                        data: {
                            password1: password1,
                            password2: password2,
                            reset_key: resetKey
                        },
                        success: function(data) {
                            if (data.success) {
                                $('#form-error').hide();
                                //show success div
                                $('#form-success').text(data.message);
                                $('#form-success').show();
                                $('#form-success-div').show();
                                //hide form and show the 'go to login' page
                                $('#password-form').hide();
                                $('#form-success-next').show();
                            } else {
                                showFormError(data.message);
                            }

                        }
                    });

                }


            }
        } else {
            showFormError("Passwords don't match eachother.");
        }
    }

});

function showFormError(formErrorMessage){
    if (formErrorMessage != "") {
        $('#form-error-div').show();
        $('#form-error').text(formErrorMessage);
        $('#form-error').show();
    }
}