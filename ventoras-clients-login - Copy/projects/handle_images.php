<?php

header('Content-Type: application/json');
include '../php/config.php';
$update_status = "error_default";

if (isset($_POST['image_name']) && isset($_POST['project_id']) && isset($_POST['action'])) {
    if ($_POST['action'] == "delete" || $_POST['action'] == "highlight") {

        $action = $_POST['action'];
        $image_name = $_POST['image_name'];
        $project_id = $_POST['project_id'];
        $project_highlighted_image = '';

        //verify first if a project actually exists with the given project_id

        //get the current project_images entry from the 'projects' table
        $query = "SELECT project_images,project_highlighted_image FROM projects WHERE id = ?";

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
                    mysqli_stmt_bind_result($stmt, $project_images_from_db, $project_highlighted_image);
                    if (mysqli_stmt_fetch($stmt)) {
                        $current_images_per_project = 0;
                        $project_images_array = [];

                        if ($project_images_from_db != '') {
                            $project_images_array = explode(",", $project_images_from_db);
                            $current_images_per_project = sizeof($project_images_array);
                        }

                        if ($action == "highlight") {
                            $query = "UPDATE projects SET project_highlighted_image = ? WHERE id = ?";
                            
                            if ($stmt = mysqli_prepare($link, $query)) {
                                // Bind variables to the prepared statement as parameters
                                mysqli_stmt_bind_param($stmt, "ss", $image_name, $project_id);
                                
                                // Attempt to execute the prepared statement
                                if (mysqli_stmt_execute($stmt)) {
                                    // Store result
                                    // mysqli_stmt_store_result($stmt);
                                    

                                    if (mysqli_stmt_affected_rows($stmt) == 1) {

                                        print_update_status(true, "success");
                                    }
                                }
                            }
                        }

                        if ($action == "delete") {

                            //if there's no images in the project, then there's nothing to delete
                            if (sizeof($project_images_array) > 0) {

                                $resulting_array_string = removeImageFile($image_name, $project_images_array);
                                if ($resulting_array_string != "notinarray") {

                                    //if the photo client wants to delete is the project_highlighted_image, 
                                    //then set the first image from the updated image array as the project_highlighted_image
                                    //if there are no images for the project, set the project_highlighted_image as '' 
                                    $new_project_highlighted_image = "";
                                    $resulting_array = explode(",", $resulting_array_string);

                                    if(sizeof($resulting_array) > 0){
                                        $new_project_highlighted_image = $resulting_array[0];
                                    }

                                    $query = "UPDATE projects SET project_images = ? WHERE id = ?";
                                    if($project_highlighted_image == $image_name){
                                        $query = "UPDATE projects SET project_images = ? , project_highlighted_image = ? WHERE id = ?";
                                    }

                                    if ($stmt = mysqli_prepare($link, $query)) {

                                        if($project_highlighted_image == $image_name){
                                            // Bind variables to the prepared statement as parameters
                                            mysqli_stmt_bind_param($stmt, "sss", $resulting_array_string, $new_project_highlighted_image, $project_id);
                                        } else {
                                            // Bind variables to the prepared statement as parameters
                                            mysqli_stmt_bind_param($stmt, "ss", $resulting_array_string, $project_id);
                                        }

                                        // Attempt to execute the prepared statement
                                        if (mysqli_stmt_execute($stmt)) {
                                            // Store result
                                            // mysqli_stmt_store_result($stmt);
                                            if (mysqli_stmt_affected_rows($stmt) == 1) {

                                                print_update_status(true, "success");
                                            }
                                        }
                                    }
                                }
                            }
                        }
                    }
                } else {
                    //commented to avoid giving clues to anyone who's manually sending requests to the server without using the user interface
                    // print_update_status(false, "invalidprojectid");
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

function removeImageFile($filename, $imageArray)
{
    // Check if the filename exists in the array
    if (!in_array($filename, $imageArray)) {
        return "notinarray";
    }

    // Remove the file from the array
    $imageArray = array_diff($imageArray, [$filename]);

    // Convert the array into a comma-separated string
    return implode(",", $imageArray);
}

function print_update_status($success_status, $update_status)
{

    echo json_encode(['success' => $success_status, 'message' => $update_status]);
    exit();
}
