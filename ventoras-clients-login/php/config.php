<?php

//hides all the error reporting, do this when going into production mode 
// error_reporting(0);


define('TIMEZONE', 'Asia/Colombo');
date_default_timezone_set(TIMEZONE);

$PROFILE_PICTURE_LINK_BASE = "http://localhost/tailoredsweep/ventoras-clients-login/uploads/profile_pictures/";
$TMP_PROFILE_PICTURE_LINK_BASE = "http://localhost/tailoredsweep/ventoras-clients-login/uploads/tmp_profile_pictures/";
$MAXIMUM_IMAGES_PER_PROJECT = 3;
$PROJECT_IMAGES_LINK_BASE = "http://localhost/tailoredsweep/ventoras-clients-login/uploads/project_images/";
$TMP_PROJECT_IMAGES_LINK_BASE = "http://localhost/tailoredsweep/ventoras-clients-login/uploads/tmp_project_images/";
$DEFAULT_PROFILE_PICTURE_BG_COLOR = "#CCC";
$DEFAULT_PROFILE_PICTURE_BG_IMAGE = "placeholder.svg";
$DEFAULT_PROJECT_BG_COLOR = "#CCC";
$DEFAULT_PROJECT_BG_IMAGE = "placeholder.svg";


// loading items
$DEFAULT_ITEMS_PER_LOAD_MORE_FOR_REVIEWS = 3;
$DEFAULT_ITEMS_PER_LOAD_MORE_FOR_PROJECTS = 3;
/* Database credentials. Assuming you are running MySQL
server with default setting (user 'root' with no password) */
define('DB_SERVER', '143.198.148.168');
define('DB_USERNAME', 'resad');
define('DB_PASSWORD', 'W3L1v1ngL1fe3!MySQL');
define('DB_NAME', 'demo');



/* Attempt to connect to MySQL database */
$link = mysqli_connect(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);

// Check connection
if ($link === false) {
    // die("ERROR: Could not connect. " . mysqli_connect_error());
    die("Error with database connection.");
}


function generateRandomString($length = 20)
{
    $characters = 'abcdefghijklmnopqrstuvwxyz0123456789';
    $randomString = '';

    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[random_int(0, strlen($characters) - 1)];
    }

    return $randomString;
}


function print_update_status_basic_layout($success_status, $update_status)
{

    echo json_encode(['success' => $success_status, 'message' => $update_status]);
    exit();
}
