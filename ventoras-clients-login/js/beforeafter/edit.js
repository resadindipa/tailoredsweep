$(document).ready(function() {
    let maximumNumberOfImagePairsPerProject = $("#max_beforeafter_image_pairs_allowed").val();
    let beforeAfterImageSectionID = $("#beforeafter_section_id").val();
    let currentlyUpdatingImagePair = 0;
    let currentlyUpdatingImageBeforeOrAfter = 0;
    let lastClickedReplaceButton;

    let uploadedImagesStatus = [false, false];

    updateNumOfPairsBtn();


    $(document).on("click", ".beforeafter-pair-item-delete", function() {
        let pairNumber = $(this).closest("[data-pair-number]").attr("data-pair-number");

        //Check if the pair is the only pair available, if it is, it cannot be deleted
        //This doesn't need to be checked here in JS, as there's a separate process ensuring that no "Delete" button appears when there's only one pair of images per the section

        //Show the popup asking for confirmation
        showConfirmationPopup("Do you want to remove this pair?", pairNumber, $(this));

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

    $("#beforeafter-pairs-row .replace-pair-image-btn").click(function(e) {
        currentlyUpdatingImagePair = $(this).closest("[data-pair-number]").attr("data-pair-number");
        currentlyUpdatingImageBeforeOrAfter = $(this).attr("data-pair-image-before-after");
        lastClickedReplaceButton = $(this);
        $("#addnewpairimage").click();
        console.log(currentlyUpdatingImagePair + "--" + currentlyUpdatingImageBeforeOrAfter);
    });


    function removeImagePair(imagePairID) {
        console.log("Image Pair ID: " + imagePairID);

        //remove that image's html element from the 'body'
        $('[data-pair-number="' + imagePairID + '"]').closest('.beforeafter-image-pair-div').remove();

        //update the "You've added x out of X images allowed per a project."
        updateNumOfPairsBtn();
    }

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

    $("#beforeafter-image-add-new-pair-btn").click(function(e) {
        $("#beforeafter-image-add-new-pair-btn").hide();
        $("#beforeafter-image-add-new-pair-div").show();
    });

    $("#cancelAddingImagePair").click(function(e) {
        $("#beforeafter-image-add-new-pair-div").hide();
        $("#beforeafter-image-add-new-pair-btn").show();
    });

    $("#pair-new-image-after").click(function(e) {
        $("#addnewpairimage_2").click();
    });


    $("#pair-new-image-before").click(function(e) {
        $("#addnewpairimage_1").click();
    });

    $("#addnewpairimage_1").change(function() {
        var file = this.files[0];
        if (file) {
            uploadTempImage("1", file, "#pair-new-image-before", "#addnewpairimage_1");
        }
    });

    $("#addnewpairimage_2").change(function() {
        var file = this.files[0];
        if (file) {
            uploadTempImage("2", file, "#pair-new-image-after", "#addnewpairimage_2");
        }
    });

    function uploadTempImage(beforeOrAfterImage, file, buttonID, fileinputID) {
        //hide the errors initially
        $("#photo-error-2").hide();

        var formData = new FormData();
        formData.append('pair_image', file);
        formData.append('pair_beforeorafter', beforeOrAfterImage);
        formData.append('pair_section', beforeAfterImageSectionID);


        $(buttonID).html("Uploading...");
        $(buttonID).prop("disabled", true);

        $.ajax({
            url: 'add_new_beforeafter_image.php',
            type: 'POST',
            data: formData,
            contentType: false,
            processData: false,
            dataType: 'json',
            success: function(response) {
                console.log(response);

                //clear the file input first for next file upload
                $(fileinputID).val('');


                $(buttonID).prop("disabled", false);
                $(buttonID).html("Replace");

                if (response.success) {
                    let image_link = response.image_link;
                    let image_name = response.image_name;

                    console.log(image_link);
                    uploadedImagesStatus[parseInt(beforeOrAfterImage) - 1] = true;
                    // console.log(lastClickedReplaceButton.parents('.beforeafter-image-image-div'));
                    //As we're not updating the image's file name, we have to add something new to refresh the background-image -> "?" + new Date().getTime() 
                    $(buttonID).parent().siblings('.beforeafter-image-image-div').css('background-image', "url('" + image_link + "?" + new Date().getTime() + "')");
                    $(buttonID).parent().siblings('.beforeafter-image-image-div').attr('data-image-name', image_name);


                    updateNumOfPairsBtn();
                    updateNewPairButton();

                } else if (response.message == "invalidtype") {
                    updateAddNewImageError("Invalid File Type, Only JPG/JPEG/PNG files are accepted.");
                    return;
                } else {
                    updateAddNewImageError("Something's wrong, Image not added");
                    return;
                }
            },
            error: function() {

                $(buttonID).prop("disabled", false);
                $(buttonID).html("Replace");

                updateAddNewImageError("Something's wrong, Image not added");
                return;
            }
        });

        updateNewPairButton();
    }

    $("#addImagePair").click(function(e) {
        //Check if the user has uploaded both before & after images
        let beforeImageName = $("#beforeafter-image-image-div-before").attr("data-image-name");
        let afterImageName = $("#beforeafter-image-image-div-after").attr("data-image-name");

        if ((beforeImageName != undefined && afterImageName != undefined) && (beforeImageName.length > 0 && afterImageName.length > 0)) {
            // if (updateNewPairButton() == true) {
            var formData = new FormData();
            formData.append('pair_section', beforeAfterImageSectionID);
            formData.append('pair_before', beforeImageName);
            formData.append('pair_after', afterImageName);

            $("#addImagePair").html("Adding...");
            $("#addImagePair").prop("disabled", true);

            $.ajax({
                url: 'save_new_pair.php',
                type: 'POST',
                data: formData,
                contentType: false,
                processData: false,
                // dataType: 'json',
                success: function(response) {
                    console.log(response);


                    $("#addImagePair").prop("disabled", false);
                    $("#addImagePair").html("Add Image Pair");

                    if (isValidJSON(response)) {
                        if (response.message == "invalidtype") {
                            updateAddNewImageError("Invalid File Type, Only JPG/JPEG/PNG files are accepted.");
                            return;
                        } else {
                            updateAddNewImageError("Something's wrong, Image not added");
                            return;
                        }
                    } else {
                        //Response is(may be) in HTML format
                        //Clear the <div>'s background-images
                        $("#beforeafter-image-image-div-before").attr("data-image-name", "");
                        $("#beforeafter-image-image-div-before").css("background-image", "");
                        $("#beforeafter-image-image-div-after").attr("data-image-name", "");
                        $("#beforeafter-image-image-div-after").css("background-image", "");


                        $('#beforeafter-pairs-row').append(response);


                        $("#cancelAddingImagePair").click();
                        updateNumOfPairsBtn();
                        //check if the user reached the maximum number of image pairs per section
                        //and hide the "add a new image pair" button and its two inputs

                    }

                    // if (response.success) {


                    //     let image_link_1 = response.image_link_1;
                    //     let image_link_2 = response.image_link_2;

                    //     console.log(image_link_1 + "----" + image_link_2);

                    // console.log(lastClickedReplaceButton.parents('.beforeafter-image-image-div'));
                    // // As we're not updating the image's file name, we have to add something new to refresh the background-image -> "?" + new Date().getTime() 
                    // lastClickedReplaceButtonForThisRequest.parent().siblings('.beforeafter-image-image-div').css('background-image', "url('" + image_link + "?" + new Date().getTime() + "')");
                    // lastClickedReplaceButtonForThisRequest.parent().siblings('.beforeafter-image-image-div').css('background-image', "url('" + image_link + "?" + new Date().getTime() + "')");


                    // updateNumOfPairsBtn();

                    // }
                },
                error: function() {

                    $("#addImagePair").prop("disabled", false);
                    $("#addImagePair").html("Add Image Pair");
                    updateAddNewImageError("Something's wrong, Image not added");
                    return;
                }
            });
        } else {
            //Show the error saying both files needs to be uploaded
            updateAddNewImageError("Both Before & After Images need to be uploaded.");
        }

    });

    function updateNewPairButton() {
        let beforeImageName = $("#beforeafter-image-image-div-before").attr("data-image-name");
        let afterImageName = $("#beforeafter-image-image-div-after").attr("data-image-name");

        if ((beforeImageName != undefined && afterImageName != undefined) && (beforeImageName.length > 0 && afterImageName.length > 0)) {
            $("#addImagePair").prop("disabled", false);
            // return true;
        } else {
            $("#addImagePair").prop("disabled", true);
            // return false;
        }
    }


    function isValidJSON(str) {
        try {
            JSON.parse(str);
            return true;
        } catch (e) {
            return false;
        }
    }

    $('#addnewpairimage').change(function() {

        //check if the user has reached the maximum allowed number of photos per project
        if ($('#beforeafter-pairs-row .beforeafter-image-pair-div').length + 1 > maximumNumberOfImagePairsPerProject) {

        } else {
            var file = this.files[0];
            if (file) {
                var formData = new FormData();
                formData.append('pair_image', file);
                formData.append('pair_section', beforeAfterImageSectionID);
                formData.append('pair_num', currentlyUpdatingImagePair);
                formData.append('pair_image_ba', currentlyUpdatingImageBeforeOrAfter);


                let lastClickedReplaceButtonForThisRequest = lastClickedReplaceButton;

                lastClickedReplaceButton.html("Uploading...");
                lastClickedReplaceButton.prop("disabled", true);

                $.ajax({
                    url: 'tmp_add_beforeafter_images.php',
                    type: 'POST',
                    data: formData,
                    contentType: false,
                    processData: false,
                    dataType: 'json',
                    success: function(response) {
                        console.log(response);
                        $('#addnewpairimage').val('');
                        lastClickedReplaceButtonForThisRequest.prop("disabled", false);
                        lastClickedReplaceButtonForThisRequest.html("Replace");

                        if (response.success) {
                            let image_link = response.image_link;
                            console.log(image_link);

                            // console.log(lastClickedReplaceButton.parents('.beforeafter-image-image-div'));
                            //As we're not updating the image's file name, we have to add something new to refresh the background-image -> "?" + new Date().getTime() 
                            lastClickedReplaceButtonForThisRequest.parent().siblings('.beforeafter-image-image-div').css('background-image', "url('" + image_link + "?" + new Date().getTime() + "')");

                            updateNumOfPairsBtn();

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
            }

        }

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

        $("#photo-error-1").attr("class", "alert alert-danger");
        //update the image counter's text
        $("#photo-error-1").text(errorMessage);
        $("#photo-error-1").show();
    }

    function updateAddNewImageError(errorMessage) {

        $("#photo-error-2").attr("class", "alert alert-danger");
        //update the image counter's text
        $("#photo-error-2").text(errorMessage);
        $("#photo-error-2").show();
    }

    function updateNumOfPairsBtn() {
        let numOfPairsAdded = $("#beforeafter-pairs-row .beforeafter-image-pair-div").length;

        if (numOfPairsAdded >= maximumNumberOfImagePairsPerProject) {
            $("#photo-error").attr("class", "alert alert-danger");

            //Show or Hide the "Add a new Image Pair" Button
            $("#beforeafter-image-add-new-pair-btn").hide();
            // $("#beforeafter-image-add-new-pair-div").hide();
            // $("#projectImagesUploadFrontBtn").prop("disabled", true);

        } else {
            $("#photo-error").attr("class", "alert alert-primary");
            //Show or Hide the "Add a new Image Pair" Button
            $("#beforeafter-image-add-new-pair-btn").show();
            // $("#beforeafter-image-add-new-pair-div").show();
            // $("#projectImagesUploadFrontBtn").prop("disabled", false);
        }

        //Hide all the "Delete Pair" Buttons if there's only a one Image Pair for the entire section
        if (numOfPairsAdded <= 1) {
            $(".beforeafter-pair-item-delete").hide();
        }

        //Update the numbers on each pair - Pair - 0X 
        //Update the [data-pair-number] value
        $("#beforeafter-pairs-row .beforeafter-image-pair-div").each(function(index) {

            $(this).find(".main-container-sub-title").text("Pair - 0" + (index + 1));
            $(this).attr('data-pair-number', (index + 1));
        });

        //update the image counter's text
        $("#photo-error").text(`You've added ${numOfPairsAdded} out of ${maximumNumberOfImagePairsPerProject} Pairs of Images allowed per a Section.`);
        $("#photo-error").show();



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

    function deletePair(imagePairNumber, button) {


        button.prop("disabled", true);
        button.html("Deleting Review...");

        var formData = new FormData();
        formData.append('pair_section', beforeAfterImageSectionID);
        formData.append('pair_num', imagePairNumber);


        $.ajax({
            url: 'delete_image_pair.php',
            type: 'POST',
            data: formData,
            contentType: false,
            processData: false,
            dataType: 'json',
            success: function(response) {


                button.prop("disabled", true);
                button.html("Project Deleted");
                console.log(response);

                if (response.success) {
                    showPopup("success", "Image Pair Deleted.", false);

                    //Remove the Pair from HTML code
                    removeImagePair(imagePairNumber);
                } else {
                    $("#deleteReview").prop("disabled", false);
                    showPopup("error", "Something's wrong. Project not Deleted.");
                }
            },
            error: function() {

                button.prop("disabled", true);
                button.html("Project Deleted");
                showPopup("error", "Error. Project not Deleted");
            }
        });
    }


    function showConfirmationPopup(message, imagePairNumber, button) {
        // let popupHtml = `
        //     <div id="popupOverlay">
        //         <div id="popupMessage" class="danger">
        //             <p>Are you sure you want to delete this review?</p>
        //             <button id="confirmDelete" class="btn btn-danger">Delete</button>
        //             <button id="cancelDelete" class="btn btn-secondary">Cancel</button>
        //         </div>
        //     </div>
        // `;
        // Create overlay and confirmation popup HTML
        let popupHtml = `
            <div id="popupOverlay">
                <div id="popupMessage" class="danger">
                    <p>${message}</p>
                    <button id="confirmDelete" class="btn btn-danger">Delete</button>
                    <button id="cancelDelete" class="btn btn-secondary">Cancel</button>
                </div>
            </div>
        `;

        // Append to body
        $("body").append(popupHtml);

        // Delete action
        $("#confirmDelete").click(function() {
            deletePair(imagePairNumber, button);


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