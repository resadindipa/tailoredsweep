<?php



// Check if required POST variables are set and not empty
if (!(isset($_POST['pair_section'])) && isset($_POST['pair_num'])) {
    print_update_status(false, "error1");
}

// Database connection
include '../php/config.php';


//check if the values are only postive integers
if (!(ctype_digit($_POST['pair_section']) && ctype_digit($_POST['pair_num']))) {
    print_update_status(false, "error2");
}


if (is_someone_logged_in() != true) {
    print_update_status(false, "error");
}


//get the user's website domain and check if the B&A Section actually exists for him
$userid_session = $_SESSION['ui'];

$pair_section_id = $_POST['pair_section'];
$pair_num = $_POST['pair_num'];
$pair_section_id_int = (int) $pair_section_id;
$pair_num_int = (int) $pair_num;

// Secure SQL query using prepared statements
$query = "SELECT beforeaftersections,beforeafterimagepairs,website FROM users WHERE id = ? LIMIT 1 ";
$stmt = mysqli_prepare($link, $query);
mysqli_stmt_bind_param($stmt, "s", $userid_session);

mysqli_stmt_execute($stmt);
// Store result to get the number of rows
mysqli_stmt_store_result($stmt);

if (mysqli_stmt_num_rows($stmt) > 0) {
    //ventoras.com/v-c-l/uploads/beforeafter/{$websitedomain}/{$beforeaftersectionsnumber}/{$beforeafterimagepairs}/{$pair_image_ba}.webp
    mysqli_stmt_bind_result($stmt, $beforeaftersectionsnumber, $beforeafterimagepairs_string, $websitedomain);

    mysqli_stmt_fetch($stmt);

    //check if the Before&After Image section actually exists for the user
    if ($pair_section_id_int <= $beforeaftersectionsnumber) {

        //Check in that section, if there already exists a image pair
        $beforeafter_pairs_count_array = [];
        if ($beforeafterimagepairs_string != '') {
            $beforeafter_pairs_count_array = explode(",", $beforeafterimagepairs_string);
        }

        //(3), 3, 3
        $current_sections_num_pairs = (int) $beforeafter_pairs_count_array[$pair_section_id_int - 1];

        //(3) -> 3 - 1 = 2 --> (2), 3, 3
        $new_sections_pair_count = $current_sections_num_pairs - 1;

        // Check if the newly updated pair count of the specific section is greater than zero, 
        //meaning atleast one pair of images exists for the section
        if ($new_sections_pair_count > 0) {

            //Prepare the updated 'beforeafterimagepairs' string value to be updated in MySQL Database
            //array[0] = (4)
            $beforeafter_pairs_count_array[$pair_section_id_int - 1] = strval($new_sections_pair_count);
            $final_beforeafterimagepairs_updated_string = implode(",", $beforeafter_pairs_count_array);
        } else {
            print_update_status(false, "error6");
        }
    } else {
        print_update_status(false, "error5");
    }
} else {
    print_update_status(false, "error4");
}



$tmp_upload_dir_base = dirname(__DIR__) . '/uploads/tmp_beforeafter_images/';
$upload_dir_base = dirname(__DIR__) . '/uploads/beforeafter/';
//tailoredsweep.com/1/1/
$uploadDir = $websitedomain . "/" . $pair_section_id_int . "/" . $pair_num_int . "/";

//Proceed to update the mysql database first, 
//If the file deletion afterwards fails, there's only going to be an extra folder with images that doesn't show up in the client's website
//If the mySQL doesn't work after deleting the files first, there's going to be 404 error for images that doesn't exist in the folders as mentioned in the mySQL database
if (!(renumberFolders($upload_dir_base . $websitedomain . "/" . $pair_section_id, $pair_num_int))) {
    print_update_status_basic_layout(false, "error9");
}

//Update the mySQL database's beforeafterimagepairs value
$query = "UPDATE users SET beforeafterimagepairs = ? WHERE id = ?";
$stmt = mysqli_prepare($link, $query);

if ($stmt) {
    mysqli_stmt_bind_param($stmt, "ss", $final_beforeafterimagepairs_updated_string, $userid_session);

    // echo $project_highlighted_image;
    //works even when no column is getting updated, checks for any errors of the statement execution instead of affected rows
    if (mysqli_stmt_execute($stmt) != true) {
        print_update_status(false, "error12");
        // print_update_status_basic_layout(true, "success");
    }

    mysqli_stmt_close($stmt);
}



function renumberFolders($baseDir, $deleteFolder)
{
    // Ensure the base directory exists
    if (!is_dir($baseDir)) {
        // echo "Error: Base directory does not exist.\n";
        print_update_status_basic_layout(false, "error9");
        return;
    }

    // Get all numerical folders
    $folders = array_filter(scandir($baseDir), function ($folder) use ($baseDir) {
        return is_dir($baseDir . DIRECTORY_SEPARATOR . $folder) && is_numeric($folder);
    });

    // Convert folder names to integers and sort them in ascending order
    $folders = array_map('intval', $folders);
    sort($folders);

    
    // Check if the folder to delete exists
    if (!in_array($deleteFolder, $folders)) {
        // echo "Error: Folder $deleteFolder does not exist.\n";
        print_update_status_basic_layout(false, "error10");
        // return;
    }

    // Delete the specified folder
    $folderToDelete = $baseDir . DIRECTORY_SEPARATOR . $deleteFolder;
    if (!deleteFolder($folderToDelete)) {
        // echo "Error: Failed to delete folder $deleteFolder.\n";
        print_update_status_basic_layout(false, "error11");
        return;
    }

    // echo "Folder $deleteFolder deleted successfully.\n";

    // Re-index the remaining folders
    $newIndex = 1;
    foreach ($folders as $folder) {
        if ($folder == $deleteFolder) continue; // Skip deleted folder

        $oldPath = $baseDir . DIRECTORY_SEPARATOR . $folder;
        $newPath = $baseDir . DIRECTORY_SEPARATOR . $newIndex;

        if ($oldPath !== $newPath && rename($oldPath, $newPath)) {
            // echo "Renamed folder $folder to $newIndex.\n";
        }

        $newIndex++;
    }

    //Means the process executed successfully
    return true;
}

function deleteFolder($folderPath)
{
    if (!is_dir($folderPath)) {
        return false;
    }

    $files = array_diff(scandir($folderPath), array('.', '..'));
    foreach ($files as $file) {
        $filePath = $folderPath . DIRECTORY_SEPARATOR . $file;
        is_dir($filePath) ? deleteFolder($filePath) : unlink($filePath);
    }

    return rmdir($folderPath);
}

//echo the final uploaded image url back to add.php
print_update_status_basic_layout(true, "success");
