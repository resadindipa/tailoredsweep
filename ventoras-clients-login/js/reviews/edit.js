$(document).ready(function() {
    $('#profilePictureUploadFrontBtn').click(function(e) {
        $('#profilePictureUpload').click();
    });

    $('#profilePictureUpload').change(function() {
        var file = this.files[0];
        if (file && file.size < 10485760) {


            $("#profilePictureUploadFrontBtn").html("Uploading...");
            $("#profilePictureUploadFrontBtn").prop("disabled", true);
            var formData = new FormData();
            // let review_id = $("input[name='review_id']").val();
            // let current_profile_picture = $("input[name='profile_picture']").val().trim(); // Get profile picture URL


            // formData.append('current_profile_picture', current_profile_picture)
            formData.append('profile_picture', file);
            // formData.append('review_id', review_id);

            $.ajax({
                url: 'tmp_add_profile_picture.php',
                type: 'POST',
                data: formData,
                contentType: false,
                processData: false,
                dataType: 'json',
                success: function(response) {

                    $("#profilePictureUploadFrontBtn").html("Change Picture");
                    $("#profilePictureUploadFrontBtn").prop("disabled", false);

                    if (response.success) {
                        //update the profilePicture with the newly uploaded image
                        $('#profilepictureimg').attr('src', response.image_link);
                        $("input[name='review_profilepicture']").attr('value', response.image_name);

                        // showPopup("success", "Profile Picture successfully Changed.");
                    } else if (response.message == "invalidtype") {
                        showPopup("error", "Wrong File Type. Profile Picture not Changed.");
                    } else {
                        showPopup("error", "Something's wrong. Profile Picture not Changed.");
                    }
                },
                error: function() {
                    showPopup("error", "Profile Picture not Changed.");
                }
            });
        } else {
            showPopup("error", "Image file can't be larger than 10MB.");
        }
    });
    $("#saveChanges").click(function(e) {
        e.preventDefault(); // Prevent form submission

        $("#saveChanges").html("Saving Changes...");
        $("#saveChanges").prop("disabled", true);
        $("#deleteReview").prop("disabled", true);

        // Get form values
        let review_id = $("input[name='review_id']").val();
        let review_name = $("input[name='review_name']").val().trim();
        let review_desc = $("textarea[name='review_desc']").val().trim();
        let review_date = $("input[name='review_date']").val().trim();
        let review_profilepicture = $("input[name='review_profilepicture']").val().trim();

        // Validation: Ensure fields are not empty
        if (review_name === "" || review_desc === "" || review_date === "") {
            showPopup("error", "Changes not saved. All fields are required.");
            return;
        }



        var formData = new FormData();
        // let review_id = $("input[name='review_id']").val();
        // let current_profile_picture = $("input[name='profile_picture']").val().trim(); // Get profile picture URL


        // formData.append('current_profile_picture', current_profile_picture)
        formData.append('review_id', review_id);
        formData.append('review_name', review_name);
        formData.append('review_desc', review_desc);
        formData.append('review_date', review_date);
        formData.append('review_profilepicture', review_profilepicture);

        // formData.append('review_id', review_id);


        $.ajax({
            url: 'update_review.php',
            type: 'POST',
            data: formData,
            contentType: false,
            processData: false,
            dataType: 'json',
            success: function(response) {
                $("#saveChanges").html("Save Changes");
                $("#saveChanges").prop("disabled", false);
                $("#deleteReview").prop("disabled", false);

                if (response.success) {
                    showPopup("success", "Changes saved successfully.");
                } else {
                    showPopup("error", "Changes not saved. Please try again.");
                }
            },
            error: function() {
                showPopup("error", "Changes not saved. Please try again.");
            }
        });

    });

    $("#deleteReview").click(function(e) {
        e.preventDefault(); // Prevent form submission

        showConfirmationPopup();
    });




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

    function deleteReview() {
        // Get form values
        let review_id = $("input[name='review_id']").val();


        var formData = new FormData();

        $("#deleteReview").html("Deleting...");
        $("#saveChanges").prop("disabled", true);
        $("#cancelEdit").prop("disabled", true);
        $("#deleteReview").prop("disabled", true);

        formData.append('review_id', review_id);

        $.ajax({
            url: 'delete_review.php',
            type: 'POST',
            data: formData,
            contentType: false,
            processData: false,
            dataType: 'json',
            success: function(response) {

                $("#deleteReview").prop("disabled", false);
                if (response.success) {
                    showPopup("success", "Review Deleted successfully.", true);
                } else {
                    showPopup("error", "Review not Deleted. Please try again.");
                }
            },
            error: function() {
                showPopup("error", "Review not Deleted. Please try again.");
            }
        });


    }

    function showConfirmationPopup() {
        // Create overlay and confirmation popup HTML
        let popupHtml = `
            <div id="popupOverlay">
                <div id="popupMessage" class="danger">
                    <p>Are you sure you want to delete this review?</p>
                    <button id="confirmDelete" class="btn btn-danger">Delete</button>
                    <button id="cancelDelete" class="btn btn-secondary">Cancel</button>
                </div>
            </div>
        `;

        // Append to body
        $("body").append(popupHtml);

        // Delete action
        $("#confirmDelete").click(function() {
            deleteReview();
            $("#popupOverlay").remove(); // Remove popup after execution
        });

        // Cancel action
        $("#cancelDelete").click(function() {
            $("#popupOverlay").remove();
        });

        // Close popup when clicking on overlay background (not the popup itself)
        $("#popupOverlay").click(function(e) {
            if (e.target.id === "popupOverlay") {
                $("#popupOverlay").remove();
            }
        });
    }



});