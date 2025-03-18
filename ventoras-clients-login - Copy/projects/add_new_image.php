<?php

header('Content-Type: application/json');
include '../php/config.php';
$update_status = "errord";

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['new_image']) && isset($_POST['project_id'])) {
    $file = $_FILES['new_image'];
    $project_id = $_POST['project_id'];

    //verify first if a project actually exists with the given project_id

    //get the current project_images entry from the 'projects' table
    $query = "SELECT project_images FROM projects WHERE id = ?";

    if ($stmt = mysqli_prepare($link, $query)) {
        // Bind variables to the prepared statement as parameters
        mysqli_stmt_bind_param($stmt, "s", $project_id);


        // Attempt to execute the prepared statement
        if (mysqli_stmt_execute($stmt)) {
            // Store result
            mysqli_stmt_store_result($stmt);

            // Check if username exists, if yes then verify password
            if (mysqli_stmt_num_rows($stmt) == 1) {
                // Bind result variables
                mysqli_stmt_bind_result($stmt, $project_images_from_db);
                if (mysqli_stmt_fetch($stmt)) {

                    $current_images_per_project = 0;
                    $project_images_array = [];

                    if ($project_images_from_db != '') {
                        $project_images_array = explode(",", $project_images_from_db);
                        $current_images_per_project = sizeof($project_images_array);
                    }

                    // $uploadDir = __DIR__ . '/uploads/'; // Absolute path for reliability
                    $uploadDir = dirname(__DIR__) . '/uploads/project_images/';
                    // $uploadDir = '../uploads/profile_pictures/';

                    // Ensure the directory exists
                    if (!file_exists($uploadDir)) {
                        mkdir($uploadDir, 0777, true);
                    }

                    $fileExt = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
                    $allowedExts = ['jpg', 'jpeg', 'png'];

                    if (!in_array($fileExt, $allowedExts)) {
                        print_update_status(false, "invalidtype");
                    }

                    $newFileName = uniqid('project_', true) . '.' . $fileExt;
                    $filePath = $uploadDir . $newFileName;

                    // Move the uploaded file first
                    if (!move_uploaded_file($file['tmp_name'], $filePath)) {
                        print_update_status(false, "filemoveerror");
                    }

                    try {
                        // Now process the file with Imagick
                        $imagick = new Imagick($filePath);
                        $imagick->setImageFormat('jpeg');

                        // Resize and crop to square
                        // $dimensions = min($imagick->getImageWidth(), $imagick->getImageHeight());
                        // $imagick->cropImage($dimensions, $dimensions, 0, 0);
                        $imagick->resizeImage(1000, 1000, Imagick::FILTER_LANCZOS, 1, true);

                        // Reduce quality
                        $imagick->setImageCompression(Imagick::COMPRESSION_JPEG);
                        $imagick->setImageCompressionQuality(80);

                        $imagick->writeImage($filePath);
                        $imagick->clear();
                        $imagick->destroy();
                    } catch (Exception $e) {
                        print_update_status(false, "error");
                    }

                    //make sure the client hasn't exceeded the maximum num of images per project
                    if ($current_images_per_project < $MAXIMUM_IMAGES_PER_PROJECT) {

                        //if there are no images assigned to the project
                        $updated_project_images_text = trim($newFileName);

                        if ($current_images_per_project > 0) {
                            $updated_project_images_text = trim($project_images_from_db) . "," . trim($newFileName);

                            $query = "UPDATE projects SET project_images = ? WHERE id = ?";
                        } else {
                            //if this is the first image being uploaded by the client, set is as the project_highlighted_image
                            $query = "UPDATE projects SET project_images = ? , project_highlighted_image = ? WHERE id = ?";
                        }

                        //update the data entry's records
                        // $query = "UPDATE projects SET project_images='" . $updated_project_images_text . "' WHERE id='" . $project_id . "'";


                        if ($stmt = mysqli_prepare($link, $query)) {

                            if($current_images_per_project > 0){

                                mysqli_stmt_bind_param($stmt, "ss", $updated_project_images_text, $project_id);
                            } else {
                                // Bind variables to the prepared statement as parameters
                                mysqli_stmt_bind_param($stmt, "sss", $updated_project_images_text, $newFileName, $project_id);
                            }

                            // Attempt to execute the prepared statement
                            if (mysqli_stmt_execute($stmt)) {
                                // Store result
                                // mysqli_stmt_store_result($stmt);


                                if (mysqli_stmt_affected_rows($stmt) == 1) {

                                    //report back to JS, if the maximum number of images are uploaded by the client
                                    $maximum_image_num_reached = $current_images_per_project + 1 >= $MAXIMUM_IMAGES_PER_PROJECT;

                                    if($current_images_per_project > 0){
                                        print_update_status(true, "success", $newFileName, $PROJECT_IMAGE_LINK_BASE, $maximum_image_num_reached, false);
                                    } else {
                                        print_update_status(true, "success", $newFileName, $PROJECT_IMAGE_LINK_BASE, $maximum_image_num_reached, true);
                                    }
                                }
                            }
                        }
                    } else {
                        //commented to avoid giving clues to anyone who's manually sending requests to the server without using the user interface
                        // print_update_status(false, "maximageslimit");
                    }
                }
            } else {
                //commented to avoid giving clues to anyone who's manually sending requests to the server without using the user interface
                // print_update_status(false, "invalidprojectid");
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


function print_update_status($success_status, $update_status, $image_name = '', $picturelinkbase = '', $maximum_image_num_reached = '', $new_image_should_be_highlighted = false)
{

    echo json_encode(['success' => $success_status, 'message' => $update_status, 'image_link' => $picturelinkbase . $image_name, 'image_name' => $image_name, 'maximum_image_num_reached' => $maximum_image_num_reached, 'image_highlighted' => $new_image_should_be_highlighted]);
    exit();
}
