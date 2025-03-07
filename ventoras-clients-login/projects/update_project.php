<?php
// Database connection
include '../php/config.php';

$form_status = "";

// Check if required POST variables are set and not empty
if (!isset($_POST['project_title']) || !isset($_POST['project_desc']) || !isset($_POST['project_date']) || !isset($_POST['project_images']) || !isset($_POST['project_highlighted_image']) || !isset($_POST['action_method'])) {
    print_update_status_basic_layout(false, "missingparamerror");
}

if (isset($_POST['project_id'])) {
    $project_id = $_POST['project_id'];
} else {
    $project_id = generateRandomString();
}

$project_title = $_POST['project_title'];
$project_desc = $_POST['project_desc'];
$project_date = $_POST['project_date'];
$project_images = $_POST['project_images'];
$project_highlighted_image = $_POST['project_highlighted_image'];
$action_method = $_POST['action_method'];

$someone_logged_in = is_someone_logged_in();
if ($someone_logged_in == false) {
    print_update_status(false, "notlogged");
}


$project_userid = $_SESSION['ui'];


$project_images_folder = dirname(__DIR__) . '/uploads/project_images/';
$tmp_project_images_folder = dirname(__DIR__) . '/uploads/tmp_project_images/';

// Validate review_date format (expects YYYY-MM-DD)
if (!preg_match("/^\d{4}-\d{2}-\d{2}$/", $project_date)) {
    print_update_status_basic_layout(false, "invaliddate");
}

//if action_method is neither "update" nor "insert", show an error and exit
if ($action_method != "update" && $action_method != "insert") {
    print_update_status_basic_layout(false, "wrongaction");
}

//if action_method is "update", then project_id is needed
if ($action_method == "update" && !isset($_POST['project_id'])) {
    print_update_status_basic_layout(false, "paramerrors");
}

//Check if the newly uploaded project_images contain more images than allowed per a single project
$new_images_per_project = 0;
$project_images_array_new = [];
if ($project_images != '') {
    $project_images_array_new = explode(",", $project_images);
    $new_images_per_project = sizeof($project_images_array_new);
}

if ($new_images_per_project > $MAXIMUM_IMAGES_PER_PROJECT) {
    print_update_status_basic_layout(false, "maximages");
}

//Check if the project_highlighted_image is in the project_images array
if (!in_array($project_highlighted_image, $project_images_array_new)) {
    print_update_status_basic_layout(false, "nohighlightedimg");
}


$current_images_per_project = 0;
$project_images_array_from_db = [];

//get info about project, if it's already an existing project
if ($action_method == "update") {

    $query = "SELECT project_images FROM projects WHERE id = ?";

    if ($stmt = mysqli_prepare($link, $query)) {
        // Bind variables to the prepared statement as parameters
        mysqli_stmt_bind_param($stmt, "s", $project_id);


        // Attempt to execute the prepared statement
        if (mysqli_stmt_execute($stmt)) {
            // Store result
            mysqli_stmt_store_result($stmt);

            // Check if the project actually exists
            if (mysqli_stmt_num_rows($stmt) == 1) {
                // Bind result variables
                mysqli_stmt_bind_result($stmt, $project_images_from_db);
                if (mysqli_stmt_fetch($stmt)) {


                    if ($project_images_from_db != '') {
                        $project_images_array_from_db = explode(",", $project_images_from_db);
                        $current_images_per_project = sizeof($project_images_array_from_db);
                    }

                    //compare the project_images_array from db and the newly uploaded project_images_array
                    $newly_uploaded_images = arrayDifference($project_images_array_from_db, $project_images_array_new);
                    $newly_deleted_images = arrayDifference($project_images_array_new, $project_images_array_from_db);

                    //for newly uploaded images, move them from tmp_project_images to project_images
                    for ($i = 0; $i < sizeof($newly_uploaded_images); $i++) {
                        //File paths for the temporarily and standard profile picture images
                        $sourceDir = $tmp_project_images_folder . $newly_uploaded_images[$i];
                        $destinationDir = $project_images_folder . $newly_uploaded_images[$i];

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

                    //for newly deleted images, delete them from project_images
                    for ($i = 0; $i < sizeof($newly_deleted_images); $i++) {


                        $image_to_be_deleted_file_path = $project_images_folder . $newly_deleted_images[$i];
                        if (file_exists($image_to_be_deleted_file_path)) {
                            if (unlink($image_to_be_deleted_file_path)) {
                                // print_update_status_basic_layout(true, "success");
                            }
                        }
                    }

                    // Prepare the update query
                    $query = "UPDATE projects SET project_title = ?, project_desc = ?, project_date = ?, project_highlighted_image = ?, project_images = ? WHERE id = ?";
                    $stmt = mysqli_prepare($link, $query);

                    if ($stmt) {
                        mysqli_stmt_bind_param($stmt, "ssssss", $project_title, $project_desc, $project_date, $project_highlighted_image, $project_images, $project_id);

                        // echo $project_highlighted_image;
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
} else if ($action_method == "insert") {
    //move the image files from tmp_project_images to project_images

    //for newly uploaded images, move them from tmp_project_images to project_images
    for ($i = 0; $i < sizeof($project_images_array_new); $i++) {
        //File paths for the temporarily and standard profile picture images
        $sourceDir = $tmp_project_images_folder . $project_images_array_new[$i];
        $destinationDir = $project_images_folder . $project_images_array_new[$i];

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

    // echo "wereachedhere";
    // Prepare the update query
    $query = "INSERT INTO projects (id,project_userid,project_title,project_desc,project_date,project_highlighted_image,project_images) VALUES (?,?,?,?,?,?,?)";
    $stmt = mysqli_prepare($link, $query);

    if ($stmt) {
        mysqli_stmt_bind_param($stmt, "sssssss", $project_id, $project_userid, $project_title, $project_desc, $project_date, $project_highlighted_image, $project_images);
        // mysqli_stmt_execute($stmt);

        //works even when no column is getting updated, checks for any errors of the statement execution instead of affected rows
        if (mysqli_stmt_execute($stmt) == true) {
            print_update_status_basic_layout(true, "success");
        } else {
            print_update_status_basic_layout(false, mysqli_stmt_error($stmt));
        }

        mysqli_stmt_close($stmt);
    }
}
//             }
//         } else {
//             //no project with the given id
//             print_update_status_basic_layout(false, "error");
//         }
//     }
// }



//things found in b, but not in a -> newly added images
function arrayDifference(array $a, array $b): array
{
    return array_values(array_diff($b, $a));
}








print_update_status_basic_layout(false, "error");
