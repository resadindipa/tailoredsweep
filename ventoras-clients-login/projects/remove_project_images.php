<?php

header('Content-Type: application/json');

include '../php/config.php';


if (isset($_POST['image_name'])) {
    
    $image_name = $_POST['image_name'];
    if(isValidFilename($image_name)){

        $uploadDir = dirname(__DIR__) . '/uploads/project_images/';
        $uploadDirTemp = dirname(__DIR__) . '/uploads/tmp_project_images/';

        $image_to_be_deleted_file_path = $uploadDir . $image_name;
        $image_to_be_deleted_file_path_tmp = $uploadDirTemp . $image_name;
    
        //first try deleting the file set to project_images path
        if (file_exists($image_to_be_deleted_file_path)) {
            if (unlink($image_to_be_deleted_file_path)) {
                print_update_status(true, "success");
            }
        } else {

            //try with the file path set to tmp_project_images
            if (file_exists($image_to_be_deleted_file_path_tmp)) {
                if (unlink($image_to_be_deleted_file_path_tmp)) {
                    print_update_status(true, "success");
                }
            }
        }
    }

} else {
    //commented to avoid giving clues to anyone who's manually sending requests to the server without using the user interface
    print_update_status(false, "missingparams");
}


//default error message, if no other message prints this is set to print as default, 
//if the $stmt based if conditions are failed, this will get print at the end
//if the 'if' statements are true, something else prints and the code stops because of the exit() in the print_update_status()
//DOWNSIDE - YOU MAY HAVE TO ADD ELSE AND INDIVIDUAL PRINT STATEMENTS IF YOU HAVE TO DEBUG SOME ERROR TO SEE WHERE THINGS WENT WRONG
print_update_status(false, "error");


//No user input from $_POST['ímage_name'] can access (delete) files that are outside the tmp_project_images
function isValidFilename($filename) {
    $allowedExts = ['jpg', 'jpeg', 'png'];
    // Ensure only letters, numbers, and a single dot
    if (!preg_match('/^[a-zA-Z0-9]+(\.[a-zA-Z0-9]+)?$/', $filename)) {
        // return "Invalid filename format!";
        return false;
    }

    // Extract the file extension
    $ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));

    // Check if the extension is in the allowed list
    if (!in_array($ext, $allowedExts)) {
        // return "Invalid file format!";
        return false;
    }

    // return "Valid filename and file format!";
    return true;
}


function print_update_status($success_status, $update_status)
{

    echo json_encode(['success' => $success_status, 'message' => $update_status]);
    exit();
}
