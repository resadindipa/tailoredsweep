<?php
// Database connection
include '../php/config.php';


// Check if required POST variables are set and not empty
if (!isset($_POST['review_id']) || !isset($_POST['review_name']) || !isset($_POST['review_desc']) || !isset($_POST['review_date']) || !isset($_POST['review_profilepicture'])) {
    print_update_status_basic_layout(false, "missingparams");
}

$profile_pictures_folder = dirname(__DIR__) . '/uploads/profile_pictures/';
$tmp_profile_pictures_folder = dirname(__DIR__) . '/uploads/tmp_profile_pictures/';

$review_id = $_POST['review_id'];
$review_name = $_POST['review_name'];
$review_desc = $_POST['review_desc'];
$review_date = $_POST['review_date'];
$review_profilepicture = $_POST['review_profilepicture'];


// Validate review_date format (expects YYYY-MM-DD)
if (!preg_match("/^\d{4}-\d{2}-\d{2}$/", $review_date)) {
    print_update_status_basic_layout(false, "datewrongformat");
}

//Check if there's an actual review with such review_id
$query = "SELECT review_profilepicture FROM reviews WHERE id = ?";

if ($stmt = mysqli_prepare($link, $query)) {
    // Bind variables to the prepared statement as parameters
    mysqli_stmt_bind_param($stmt, "s", $review_id);


    // Attempt to execute the prepared statement
    if (mysqli_stmt_execute($stmt)) {
        // Store result
        mysqli_stmt_store_result($stmt);

        // Check if the project actually exists
        if (mysqli_stmt_num_rows($stmt) == 1) {
            // Bind result variables
            mysqli_stmt_bind_result($stmt, $review_profilepicture_from_db);
            if (mysqli_stmt_fetch($stmt)) {

                //Check if the profile picture is updated or not
                if ($review_profilepicture != $review_profilepicture_from_db) {
                    //Move the profile picture from tmp_profile_pictures to profile_pictures
                    $sourceDir = $tmp_profile_pictures_folder . $review_profilepicture;
                    $destinationDir = $profile_pictures_folder . $review_profilepicture;

                    //Check if the temporarily profile picture file actually exists
                    //if not, return an error and exit
                    if (!file_exists($sourceDir)) {
                        print_update_status_basic_layout(false, "tmpfilemissing");
                    }


                    if (!rename($sourceDir, $destinationDir)) {
                        //File has not been moved successfully, so show an error and exit
                        print_update_status_basic_layout(false, "tmpfilemoveerror");
                    }
                }

                // Prepare the update query
                $query = "UPDATE reviews SET review_name = ?, review_desc = ?, review_date = ?, review_profilepicture = ? WHERE id = ?";
                $stmt = mysqli_prepare($link, $query);

                if ($stmt) {
                    mysqli_stmt_bind_param($stmt, "sssss", $review_name, $review_desc, $review_date, $review_profilepicture, $review_id);
                    // mysqli_stmt_execute($stmt);


                    //works even when no column is getting updated, checks for any errors of the statement execution instead of affected rows
                    if (mysqli_stmt_execute($stmt) == true) {
                        print_update_status_basic_layout(true, "success");
                    }

                    mysqli_stmt_close($stmt);
                }
            }
        }
    }
}

print_update_status_basic_layout(false, "error");
