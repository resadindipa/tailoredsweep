<?php
header('Content-Type: application/json');
include '../php/config.php';
$update_status = "errord";

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['profile_picture']) && isset($_POST['id']) && isset($_POST['current_profile_picture'])) {
    $file = $_FILES['profile_picture'];
    $user_id = $_POST['id'];
    $current_profile_picture = $_POST['current_profile_picture'];

    // $uploadDir = __DIR__ . '/uploads/'; // Absolute path for reliability
    $uploadDir = dirname(__DIR__) . '/uploads/profile_pictures/';
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

    $newFileName = uniqid('profile_', true) . '.' . $fileExt;
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
        $dimensions = min($imagick->getImageWidth(), $imagick->getImageHeight());
        $imagick->cropImage($dimensions, $dimensions, 0, 0);
        $imagick->resizeImage(150, 150, Imagick::FILTER_LANCZOS, 1);

        // Reduce quality
        $imagick->setImageCompression(Imagick::COMPRESSION_JPEG);
        $imagick->setImageCompressionQuality(80);

        $imagick->writeImage($filePath);
        $imagick->clear();
        $imagick->destroy();


        //update the data entry's records
        $query = "UPDATE reviews SET profilepicture = ? WHERE id = ?";
        $stmt = mysqli_prepare($link, $query);

        if ($stmt) {
            mysqli_stmt_bind_param($stmt, "ss", $newFileName, $user_id);
            mysqli_stmt_execute($stmt);

            if (mysqli_stmt_affected_rows($stmt) > 0) {
                
                //Delete the old picture if the user has one
                if($current_profile_picture != ""){
                    $old_profile_picture_link = $uploadDir . $current_profile_picture; 
                    if (file_exists($old_profile_picture_link)) {
                        unlink($old_profile_picture_link);
                    } else {
                        //the current profile picture is deleted or missing in uploads/profile_pictures section
                        // print_update_status(false, "error22");
                    }
                } 

                print_update_status(true, "success", $newFileName, $PROFILE_PICTURE_LINK_BASE);
                
                // $update_status = "success";
            } else {
                print_update_status(false, "aftrowerror");
                // $update_status = "affectedrowserror";
            }

            mysqli_stmt_close($stmt);
        } else {
            print_update_status(false, "error");
        }
    } catch (Exception $e) {
        print_update_status(false, "error");
    }
} else {
    print_update_status(false, "missingparams");
}


function print_update_status($success_status, $update_status, $image_name = '', $picturelinkbase = '')
{

    echo json_encode(['success' => $success_status, 'message' => $update_status, 'image_link' => $picturelinkbase . $image_name, 'image_name' => $image_name]);
    exit();
}
