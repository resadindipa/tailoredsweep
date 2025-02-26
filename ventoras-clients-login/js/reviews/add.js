$(document).ready(function() {

    // Set the review date's default value to current date

    // Get today's date in YYYY-MM-DD format
    let today = new Date().toISOString().split('T')[0];
    $("#review_date_input").val(today);

    $('#profilePictureUploadFrontBtn').click(function(e) {
        $('#profilePictureUpload').click();
    });


    $('#profilePictureUpload').change(function() {
        var file = this.files[0];
        if (file) {
            var formData = new FormData();

            formData.append('profile_picture', file);

            $.ajax({
                url: 'tmp_add_profile_picture.php',
                type: 'POST',
                data: formData,
                contentType: false,
                processData: false,
                dataType: 'json',
                success: function(response) {

                    if (response.success) {
                        //update the profilePicture with the newly uploaded image
                        $('#profilepictureimg').attr('src', response.image_link);
                        $("input[name='review_profilepicture']").attr('value', response.image_name);

                    } else if (response.message == "invalidtype") {
                        showPopup("error", "Wrong File Type. Profile Picture not Added.");
                    } else {
                        showPopup("error", "Something's wrong. Profile Picture not Added.");
                    }
                },
                error: function() {
                    showPopup("error", "Profile Picture not Added.");
                }
            });
        }

        if ($('#profilePictureUpload')[0].files[0] != undefined) {
            $('#profilePictureUploadFrontBtn').text("Change Picture");
            $('#profilePictureRemoveBtn').show();
        }
    });

    $("#profilePictureRemoveBtn").click(function(e) {
        $('#profilePictureUploadFrontBtn').text("Add Picture");
        $('#profilePictureUpload').val('');
        $("#profilePictureRemoveBtn").hide();

        //remove the preview photo
        $("#profilepictureimg").attr('src', "../uploads/profile_pictures/placeholder.svg");
    });

    $("#submitbtn").click(function(e) {
        e.preventDefault(); // Prevent form submission

        //Disable the 'Add Review' button while the request is being processed
        //Otherwise, user may click the 'Add Review' button multiple times
        $("#submitbtn").prop("disabled", true);

        //clear any errors shown for previous form submissions
        $("#form-error").hide();

        // Get form values
        let review_name = $("input[name='review_name']").val().trim();
        let review_desc = $("textarea[name='review_desc']").val().trim();
        let review_date = $("input[name='review_date']").val().trim();
        let profile_picture = $("input[name='review_profilepicture']").val().trim();


        // Validation: Ensure fields are not empty
        if (review_name === "" || review_desc === "" || review_date === "") {
            //show the form error
            updateFormError("Complete all Fields.");
            return;
        }

        var formData = new FormData();

        formData.append('review_name', review_name);
        formData.append('review_desc', review_desc);
        formData.append('review_date', review_date);
        formData.append('review_profilepicture', profile_picture);

        $.ajax({
            url: 'add_new_review.php',
            type: 'POST',
            data: formData,
            contentType: false,
            processData: false,
            dataType: 'json',
            success: function(response) {

                if (response.success) {
                    //clear all the form fields
                    $("input[name='review_name']").val('');
                    $("textarea[name='review_desc']").val('');

                    //remove the preview of profile picture and hide the 'change image' button
                    $("#profilePictureRemoveBtn").click();

                    showPopup("success", "Review successfully Added.", true);
                } else {
                    //Show the error
                    showPopup("error", "Something's wrong. Review not Added.");

                    //enable the 'Add Review' button, so the user may try again
                    $("#submitbtn").prop("disabled", true);
                }
            },
            error: function() {
                //Show the error
                showPopup("error", "Error, Review Not Added.");

                //enable the 'Add Review' button, so the user may try again
                $("#submitbtn").prop("disabled", true);
            }
        });

    });

    
    function updateFormError(errorMessage) {

        $("#form-error").attr("class", "alert alert-danger");
        $("#form-error").text(errorMessage);
        $("#form-error").show();
    }


    // Function to display a popup message


    function showPopup(type, message, redirectionToHome = false) {
        let popupClass, btnClass;

        if (type === "success") {
            popupClass = "alert-success";
            btnClass = "btn-secondary";
        } else {
            popupClass = "alert-danger";
            btnClass = "btn-danger";
        }

        // Create overlay and popup HTML
        let popupHtml = `
            <div id="popupOverlay">
                <div id="popupMessage" class="${popupClass}">
                    <p>${message}</p>
                    <button id="closePopup" class="btn ${btnClass}">Cancel</button>
                </div>
            </div>
        `;

        // Append to body
        $("body").append(popupHtml);

        // Close popup when "Cancel" is clicked
        $("#closePopup").click(function() {
            $("#popupOverlay").remove();
            if (redirectionToHome) {
                window.location.href = "index.php";
            }
        });

        // Close popup when clicking on overlay background (not the popup itself)
        $("#popupOverlay").click(function(e) {
            if (e.target.id === "popupOverlay") {
                $("#popupOverlay").remove();
                if (redirectionToHome) {
                    window.location.href = "index.php";
                }
            }
        });
    }

});