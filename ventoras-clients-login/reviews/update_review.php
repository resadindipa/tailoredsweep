<?php
// Database connection
include '../php/config.php';

$form_status = "";

// Check if required POST variables are set and not empty
if (!isset($_POST['id']) || !isset($_POST['name']) || !isset($_POST['review']) || !isset($_POST['review_date']) || !isset($_POST['profile_picture'])) {
    $form_status = "error";
    echo $form_status;
    exit;
}

$id = $_POST['id'];
$name = $_POST['name'];
$review = $_POST['review'];
$review_date = $_POST['review_date'];
$profile_picture = "";

//resad
if (isset($_POST['profile_picture'])) {
    $profile_picture = $_POST['profile_picture'];
}

// Validate review_date format (expects YYYY-MM-DD)
if (!preg_match("/^\d{4}-\d{2}-\d{2}$/", $review_date)) {
    $form_status = "dateformatwrong";
    echo $form_status;
    exit;
}

// Prepare the update query
$query = "UPDATE reviews SET name = ?, review = ?, review_date = ?, profilepicture = ? WHERE id = ?";
$stmt = mysqli_prepare($link, $query);

if ($stmt) {
    mysqli_stmt_bind_param($stmt, "sssss", $name, $review, $review_date, $profile_picture, $id);
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
