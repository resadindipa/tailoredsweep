$(document).ready(function() {
    let maximumNumberOfImagesPerProject = $("#max_images_allowed_per_project").val();

    updateNumImagesBtn();

    // form-text-input-char-count
    $('.form-text-char-count-input').each(function() {
        let $input = $(this);
        let maxCount = $input.attr('maxlength');
        let $charCount = $input.next('.form-text-input-char-count'); // Select existing <p> element

        function updateCharCount() {
            let currentLength = $input.val().length;
            $charCount.text(`${currentLength}/${maxCount}`);

            if (currentLength >= maxCount) {
                $charCount.addClass('red');
            } else {
                $charCount.removeClass('red');
            }
        }

        $input.on('input', updateCharCount);

        $input.on('focus', function() {
            $charCount.show();
            updateCharCount();
        });

        $input.on('blur', function() {
            $charCount.hide();
        });
    });

    $(document).on("click", ".delete-photo-btn", function() {
        let photoLink = $(this).closest("[data-photo-link]").attr("data-photo-link");



        //if the deleted photo is the highlighted image
        //then highlight the 2nd image in the array before the deleting
        let highlightedImgSrc = $(this).closest('.item').find('.highlight-photo-btn-img').attr('src');

        //after grabbing the status of being highlighted of the image that's being deleted, delete the whole image  
        $('[data-photo-link="' + photoLink + '"]').closest('.item').remove();

        if (highlightedImgSrc == "../content/starfilled.svg") {
            //remove the highlights of all the images, and now no image is highlighted
            //highlight the second image that's plaed after the deleted image
            $('.item').first().find('.highlight-photo-btn-img').attr('src', '../content/starfilled.svg');

            //set the hidden highlighted_image's value to the newly highlighted image's filename
            //update the form's hidden input's value
            console.log("Changing the project_highlighted_image - > " + $('.item').first().find('.dataphotolinkclassitem').attr("data-photo-link"));
            $("input[name='project_highlighted_image']").val($('.item').first().find('.dataphotolinkclassitem').attr("data-photo-link"));

        } else {
            //nothing to worry, the image being deleted is not the highlighted one
        }



        //update the "You've added x out of X images allowed per a project."
        updateNumImagesBtn();

        //remove that image's html element from the 'body'
        $('[data-photo-link="' + photoLink + '"]').closest('.item').remove();

        //remove the image from project_images form input value and update its value
        // getImageList();


        // var formData = new FormData();


        // formData.append('image_name', photoLink);

        // $.ajax({
        //     url: 'remove_project_images.php',
        //     type: 'POST',
        //     data: formData,
        //     contentType: false,
        //     processData: false,
        //     dataType: 'json',
        //     success: function(response) {
        //         // console.log(response);
        //         if (response.success) {
        //             updateNumImagesBtn();
        //             //remove that image's html element from the 'body'
        //             // $('[data-photo-link="' + photoLink + '"]').closest('.item').remove();

        //             //This isn't necessary because the image visually disappears from the 'body'
        //             // showPopup("success", "Image Successfully Deleted.");
        //         } else {
        //             showPopup("error", "Something's wrong. Image not Deleted");
        //         }
        //     },
        //     error: function() {
        //         showPopup("error", "Image not Deleted.");
        //     }
        // });
        // console.log("Deleting - " + photoLink);
    });


    $(document).on("click", ".highlight-photo-btn", function() {



        let photoLink = $(this).closest("[data-photo-link]").attr("data-photo-link");

        //update the form's hidden input's value
        $("input[name='project_highlighted_image']").val(photoLink);

        //remove the highlights of all the images
        $('.highlight-photo-btn-img').attr('src', '../content/star.svg');

        //highlight the clicked image's star
        $('[data-photo-link="' + photoLink + '"]').find('.highlight-photo-btn-img').attr('src', '../content/starfilled.svg');


        // console.log("Highlighting - " + photoLink);
    });



    $('#addnewimagebtn').click(function(e) {
        $('#addnewimageinput').click();
    });

    $('#addnewimageinput').change(function() {

        //check if the user has reached the maximum allowed number of photos per project
        if ($('.item').length + 1 > maximumNumberOfImagesPerProject) {

        } else {
            var file = this.files[0];
            //file size should be smaller than 8mb
            if (file && file.size < 8388608) {
                // if (file) {
                var formData = new FormData();

                formData.append('project_image', file);


                $('#addnewimagebtn').html("Uploading...");
                $("#addnewimagebtn").prop("disabled", true);

                $.ajax({
                    url: 'tmp_add_project_images.php',
                    type: 'POST',
                    data: formData,
                    contentType: false,
                    processData: false,
                    dataType: 'json',
                    success: function(response) {

                        $('#addnewimageinput').val('');
                        $("#addnewimagebtn").prop("disabled", false);
                        $('#addnewimagebtn').html("Add Images");
                        if (response.success) {
                            let image_link = response.image_link;
                            let image_name = response.image_name;

                            //if this is the first image, then highlight it
                            let highlightImageSrc = "../content/star.svg";
                            if ($('.item').length == 0) {
                                highlightImageSrc = "../content/starfilled.svg";
                                $("input[name='project_highlighted_image']").val(image_name);
                            }

                            //Add newly uploaded tmp images to the view
                            let popupHtml = `
                            <div class="col-sm-6 col-md-4 col-lg-3 item">
                                <a href="${image_link}" data-lightbox="photos">
                                <img class="img-fluid" src="${image_link}">
                                </a>
                                <br><br>
                                <div class="d-flex justify-content-between dataphotolinkclassitem" data-photo-link="${image_name}">
                                    <button type="button" class="btn btn-danger delete-photo-btn">Delete</button>
                                    <div class="d-flex gap-2">
                                        <button type="button" class="highlight-photo-btn">
                                            <img src="${highlightImageSrc}" class="highlight-photo-btn-img">
                                        </button>
                                    </div>
                                </div>
                            </div>`;

                            // Append to body
                            $("#project-photos-row").append(popupHtml);

                            updateNumImagesBtn();

                        } else if (response.message == "invalidtype") {
                            updatePhotoError("Invalid File Type, Only JPG/JPEG/PNG files are accepted.");
                            return;
                        } else {

                            updatePhotoError("Something's wrong, Image not added");
                            return;
                        }
                    },
                    error: function() {

                        updatePhotoError("Something's wrong, Image not added");
                        return;
                    }
                });
            } else {
                updatePhotoError("Image File can't be larger than 8MB");
                return;
            }

        }

    });

    $("#saveChanges").click(function(e) {
        e.preventDefault(); // Prevent form submission

        $("#saveChanges").prop("disabled", true);
        $('#saveChanges').html("Saving Changes...");

        //clear any errors shown for previous form submissions
        $("#form-error").hide();

        // Get form values
        let project_id = $("input[name='project_id']").val();
        let project_title = $("input[name='project_title']").val().trim();
        let project_desc = $("textarea[name='project_desc']").val().trim();
        let project_date = $("input[name='project_date']").val().trim();
        let project_highlighted_image = $("input[name='project_highlighted_image']").val().trim();


        let resultImageString = getImageList();

        // Validation: Ensure fields are not empty
        if (project_title === "" || project_desc === "" || project_date === "") {
            updateFormError("Changes not saved. All fields are required.");
            return;
        }


        var formData = new FormData();


        formData.append('project_id', project_id);
        formData.append('project_title', project_title);
        formData.append('project_date', project_date);
        formData.append('project_desc', project_desc);
        formData.append('project_images', resultImageString);
        formData.append('project_highlighted_image', project_highlighted_image);
        formData.append('action_method', "update");

        $.ajax({
            url: 'update_project.php',
            type: 'POST',
            data: formData,
            contentType: false,
            processData: false,
            dataType: 'json',
            success: function(response) {


                $("#saveChanges").prop("disabled", false);
                $('#saveChanges').html("Save Changes");
                if (response.success) {

                    showPopup("success", "Changes Saved.");
                } else {
                    showPopup("error", "Something's wrong. Changes not Saved.");
                }
            },
            error: function() {
                showPopup("error", "Changes not Saved.");
            }
        });

    });

    $("#deleteReview").click(function(e) {
        e.preventDefault(); // Prevent form submission

        showConfirmationPopup();
    });

    function getImageList() {
        //Collect all the individual file names from the html elements and make a string out of them
        let photoLinksArray = [];

        // Loop through each '.item' div
        $('.item').each(function() {
            // Find the inner div with 'data-photo-link' and get its value
            let photoLink = $(this).find('[data-photo-link]').attr('data-photo-link');

            // Add value to the array if it's not undefined
            if (photoLink) {
                photoLinksArray.push(photoLink);
            }
        });

        let resultImageString = "";
        if (photoLinksArray.length > 0) {
            // Convert array to a comma-separated string
            resultImageString = photoLinksArray.join(',');
        }

        // console.log("updating input's value - " + resultImageString);
        //update the input[name='project_images'] value
        // $("input[name='project_images']").val(resultImageString);

        return resultImageString;
    }

    function updateFormError(errorMessage) {

        $("#form-error").attr("class", "alert alert-danger");
        $("#form-error").text(errorMessage);
        $("#form-error").show();
    }

    function updatePhotoError(errorMessage) {

        $("#photo-error").attr("class", "alert alert-danger");
        //update the image counter's text
        $("#photo-error").text(errorMessage);
        $("#photo-error").show();
    }

    function updateNumImagesBtn() {
        console.log("updateNumImagesBtn();");
        let numOfImagesAdded = $(".item").length;

        if (numOfImagesAdded >= maximumNumberOfImagesPerProject) {
            $("#photo-error").attr("class", "alert alert-danger");

            //Show or Hide the "Upload New Images" Button
            $("#addnewimagediv").hide();
            // $("#projectImagesUploadFrontBtn").prop("disabled", true);

        } else {
            $("#photo-error").attr("class", "alert alert-primary");
            //Show or Hide the "Upload New Images" Button
            $("#addnewimagediv").show();
            // $("#projectImagesUploadFrontBtn").prop("disabled", false);
        }

        //update the image counter's text
        $("#photo-error").text(`You've added ${numOfImagesAdded} out of ${maximumNumberOfImagesPerProject} images allowed per a project.`);
        $("#photo-error").show();



    }

    function showOrHideUploadImagesBtn() {

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

    function deleteProject() {
        // Get form values
        let project_id = $("input[name='project_id']").val();



        $("#deleteReview").prop("disabled", true);
        $("#deleteReview").html("Deleting Review...");

        var formData = new FormData();


        formData.append('project_id', project_id);

        $.ajax({
            url: 'delete_project.php',
            type: 'POST',
            data: formData,
            contentType: false,
            processData: false,
            dataType: 'json',
            success: function(response) {



                if (response.success) {
                    $("#deleteReview").prop("disabled", true);
                    $("#saveChanges").prop("disabled", true);
                    $("#cancelEdit").prop("disabled", true);
                    $("#deleteReview").html("Project Deleted");
                    showPopup("success", "Project Deleted.", true);
                } else {
                    $("#deleteReview").prop("disabled", false);
                    showPopup("error", "Something's wrong. Project not Deleted.");
                }
            },
            error: function() {
                showPopup("error", "Error. Project not Deleted");
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
            deleteProject();
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