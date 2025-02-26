<?php
// Database connection
include '../php/config.php';


// Check if required POST variables are set and not empty
if (!isset($_POST['review_id'])) {
    print_update_status(false, "paramerror");
}

$id = $_POST['review_id'];

//Check if the project actually belongs to the logged-in user
// Initialize the session
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['ui']) || !isset($_SESSION['si'])) {
    print_update_status_basic_layout(false, "notlogged");
}

$session_user_id = $_SESSION['ui'];

$query = "SELECT id from reviews where id = ? and review_userid = ?";
$stmt = mysqli_prepare($link, $query);

if ($stmt) {
    mysqli_stmt_bind_param($stmt, "ss", $id, $session_user_id);
    mysqli_stmt_execute($stmt);

    mysqli_stmt_store_result($stmt);
    if (mysqli_stmt_num_rows($stmt) == 1) {
        // Prepare the update query
        $query = "DELETE FROM reviews WHERE id = ? LIMIT 1";
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
    }
}



print_update_status_basic_layout(false, "error");

