<?php
// Database connection
include '../php/config.php';

$form_status = "";

// Check if required POST variables are set and not empty
if (!isset($_POST['id'])) {
    $form_status = "error";
    echo $form_status;
    exit;
}

$id = $_POST['id'];


// Prepare the update query
$query = "DELETE FROM reviews WHERE id = ?";
$stmt = mysqli_prepare($link, $query);

if ($stmt) {
    mysqli_stmt_bind_param($stmt, "s", $id);
    mysqli_stmt_execute($stmt);

    if (mysqli_stmt_affected_rows($stmt) > 0) {
        $form_status = "success";
    } else {
        $form_status = "mysqlerror";
    }

    mysqli_stmt_close($stmt);
} else {
    $form_status = "stmterror";
}

echo $form_status;
?>
