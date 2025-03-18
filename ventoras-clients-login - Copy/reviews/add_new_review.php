<?php

// Database connection
include '../php/config.php';

$profile_pictures_folder = dirname(__DIR__) . '/uploads/profile_pictures/';
$tmp_profile_pictures_folder = dirname(__DIR__) . '/uploads/tmp_profile_pictures/';

// Check if required POST variables are set and not empty
if (!isset($_POST['review_name']) || !isset($_POST['review_desc']) || !isset($_POST['review_date']) || !isset($_POST['review_profilepicture'])) {
    print_update_status_basic_layout(false, "formerror");
}

$review_id = generateRandomString();
$review_name = $_POST['review_name'];
$review_desc = $_POST['review_desc'];
$review_date = $_POST['review_date'];
$review_profilepicture = $_POST['review_profilepicture'];

// Initialize the session
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$session_user_id = $_SESSION['ui'];

$review_userid = $session_user_id;

if($review_name == "" || $review_date == "" || $review_desc == ""){
    print_update_status_basic_layout(false, "emptyforms");
}

// Validate review_date format (expects YYYY-MM-DD)
if (!preg_match("/^\d{4}-\d{2}-\d{2}$/", $review_date)) {
    print_update_status_basic_layout(false, "dateerror");
}

if ($review_profilepicture != "") {
    //File paths for the temporarily and standard profile picture images
    $sourceDir = $tmp_profile_pictures_folder . $review_profilepicture;
    $destinationDir = $profile_pictures_folder . $review_profilepicture;

    //Check if the temporarily profile picture file actually exists
    //if not, return an error and exit
    if (!file_exists($sourceDir)) {
        print_update_status_basic_layout(false, "tmpfilemissing");
    }
    
//     error_reporting(E_ALL);
// ini_set('display_errors', 1);

    if (!rename($sourceDir, $destinationDir)) {
        // echo $sourceDir . "---" . $destinationDir;
        // print_r(error_get_last());
        //File has not been moved successfully, so show an error and exit
        print_update_status_basic_layout(false, "tmpfilemoveerror");
    }
}


// Prepare the update query
$query = "INSERT INTO reviews (id, review_userid, review_name, review_date, review_desc, review_profilepicture) VALUES (?,?,?,?,?,?)";
$stmt = mysqli_prepare($link, $query);


if ($stmt) {
    mysqli_stmt_bind_param($stmt, "ssssss", $review_id, $review_userid, $review_name, $review_date, $review_desc, $review_profilepicture);
    mysqli_stmt_execute($stmt);

    //mysqli_stmt_affected_rows($stmt) returns 0 if there are no changes done to the form and the user clicked the 'save changes'
    if (mysqli_stmt_affected_rows($stmt) > 0) {
        print_update_status_basic_layout(true, "success");
    } else {
        print_update_status_basic_layout(false, "error");
    }

    mysqli_stmt_close($stmt);
}

print_update_status_basic_layout(false, "error");