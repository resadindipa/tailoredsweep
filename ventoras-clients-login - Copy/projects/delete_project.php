<?php
// Database connection
include '../php/config.php';

$form_status = "";

// Check if required POST variables are set and not empty
if (!isset($_POST['project_id'])) {
    print_update_status(false, "paramerror");
}

$id = $_POST['project_id'];

//Check if the project actually belongs to the logged-in user
// Initialize the session
$someone_logged_in = is_someone_logged_in();
if ($someone_logged_in == false) {
    print_update_status(false, "notlogged");
}

$session_user_id = $_SESSION['ui'];

$query = "SELECT id FROM projects where id = ? AND project_userid = ?";
$stmt = mysqli_prepare($link, $query);

if ($stmt) {
    mysqli_stmt_bind_param($stmt, "ss", $id, $session_user_id);
    mysqli_stmt_execute($stmt);

    mysqli_stmt_store_result($stmt);
    if (mysqli_stmt_num_rows($stmt) == 1) {
        // Prepare the update query
        $query = "DELETE FROM projects WHERE id = ? LIMIT 1";
        $stmt = mysqli_prepare($link, $query);

        if ($stmt) {
            mysqli_stmt_bind_param($stmt, "s", $id);
            mysqli_stmt_execute($stmt);

            if (mysqli_stmt_affected_rows($stmt) > 0) {
                print_update_status_basic_layout(true, "success");
            }

            mysqli_stmt_close($stmt);
        }
    } else {
        print_update_status_basic_layout(false, "notowner");
        // print_update_status_basic_layout(false, "SELECT id from projects where id='".$id."' AND project_userid = '".$session_user_id."'" . "---" . mysqli_stmt_num_rows($stmt));
    }
}



print_update_status_basic_layout(false, "error");