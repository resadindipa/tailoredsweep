$("#submit-form").click(function(e) {
    e.preventDefault();

    $("#form-error-div").hide();
    $("#form-success-div").hide();


    let password1 = $('#password1').val();
    let password2 = $('#password2').val();

    let formError = "";
    if (password1 == "" || password2 == "") {
        formError = "Complete all the fields";
    } else {
        if (password1 == password2) {
            if (password1.length < 8) {
                formError = "Password should be longer than 8 characters";
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
                //Submit the form
                $.post("../password-reset/submit-password-form.php", {
                        password1: password1,
                        password2: password2,
                        reset_key: resetKey
                    },
                    function(data, status) {
                        console.log("Data: " + data + "\nStatus: " + status);
                        if (data == "Password Changed Successfully.") {
                            $('#form-error').hide();
                            //show success div
                            $('#form-success').text(data);
                            $('#form-success').show();
                            $('#form-success-div').show();
                            //hide form and show the 'go to login' page
                            $('#password-form').hide();
                            $('#form-success-next').show();
                        } else {
                            formError = data;
                        }
                    });

            }
        } else {
            formError = "Passwords don't match eachother.";
        }
    }

    if (formError != "") {
        $('#form-error-div').show();
        $('#form-error').text(formError);
        $('#form-error').show();
    }

});