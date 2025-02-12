$(document).ready(function() {
    $('#profilePictureUpload').change(function() {
        var file = this.files[0];
        if (file) {
            var formData = new FormData();
            formData.append('profile_picture', file);

            $.ajax({
                url: '../php/upload_profile_picture.php',
                type: 'POST',
                data: formData,
                contentType: false,
                processData: false,
                dataType: 'json',
                success: function(response) {
                    if (response.success) {
                        $('#profilePictureURL').val(response.file_url);
                        alert('Profile picture updated successfully!');
                    } else {
                        alert('Error: ' + response.error);
                    }
                },
                error: function() {
                    alert('File upload failed!');
                }
            });
        }
    });
    $("#saveChanges").click(function(e) {
        e.preventDefault(); // Prevent form submission

        // Get form values
        let id = $("input[name='id']").val();
        let name = $("input[name='name']").val().trim();
        let review = $("textarea[name='review']").val().trim();
        let review_date = $("input[name='review_date']").val().trim();
        let profile_picture = $("input[name='profile_picture']").val().trim(); // Get profile picture URL

        // Validation: Ensure fields are not empty
        if (name === "" || review === "" || review_date === "") {
            showPopup("error", "Changes not saved. All fields are required.");
            return;
        }

        // Send AJAX request
        $.post("update_review.php", {
            id: id,
            name: name,
            review: review,
            review_date: review_date,
            profile_picture: profile_picture // Include profile picture
        }, function(response) {
            if (response.trim() === "success") {
                showPopup("success", "Changes saved successfully.");
            } else {
                showPopup("error", "Changes not saved. Please try again.");
            }
        });
    });

    $("#deleteReview").click(function(e) {
        e.preventDefault(); // Prevent form submission

        console.log("DLT BTN");
        // Get form values
        let id = $("input[name='id']").val();


        // Send AJAX request
        $.post("delete_review.php", {
            id: id
        }, function(response) {
            console.log(response);
            if (response.trim() === "success") {
                showPopup("success", "Review Deleted successfully.", true);
            } else {
                showPopup("error", "Review not Deleted. Please try again.");
            }
        });
    });


    // Function to display a popup message


    function showPopup(type, message, redirectionToHome = false) {
        let popupClass, btnClass;

        if (type === "success") {
            popupClass = "alert-success";
            btnClass = "btn-success";
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