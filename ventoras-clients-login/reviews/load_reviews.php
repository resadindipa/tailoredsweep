<?php
// Database connection
include '../php/config.php';

// Initialize the session
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$session_user_id = $_SESSION['ui'];

$review_userid = $session_user_id;
// Get page number from AJAX request
if (isset($_POST['page'])) {
    $page = (int) $_POST['page'];
} else {
    echo 'missingparams';
    exit;
}
// $page = isset($_POST['page']) ? (int)$_POST['page'] : 0;
$offset = ($page - 1) * $DEFAULT_ITEMS_PER_LOAD_MORE_FOR_REVIEWS; // Load 3 reviews per request

// if($page == 0){
//     $offset = 0;
// }
// Secure SQL query using prepared statements
$query = "SELECT id, review_name, review_desc, review_profilepicture, review_date FROM reviews WHERE review_userid = ? ORDER BY review_date DESC LIMIT " . $DEFAULT_ITEMS_PER_LOAD_MORE_FOR_REVIEWS . " OFFSET ?";
$stmt = mysqli_prepare($link, $query);
mysqli_stmt_bind_param($stmt, "si", $review_userid, $offset);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

$number_of_items = mysqli_num_rows($result);

if ($number_of_items > 0) {

    // Generate HTML for the new reviews
    while ($row = mysqli_fetch_assoc($result)): ?>

        <div class="review-card">
            <div class="review-header">
                <div class="profile-picture" style="background-image: url(' <?php if ($row['review_profilepicture'] == '') {
                                                            echo  $PROFILE_PICTURE_LINK_BASE . $DEFAULT_PROFILE_PICTURE_BG_IMAGE;
                                                        } else {
                                                            echo $PROFILE_PICTURE_LINK_BASE . $row['review_profilepicture'];
                                                        } ?>');">
                </div>
                <p><b><?php echo htmlspecialchars($row['review_name']); ?></b></p>
            </div>
            <p class="text-muted">
                <?php
                $timestamp = strtotime($row['review_date']);
                $day = date('j', $timestamp);
                $suffix = date('S', $timestamp);
                $month = date('F', $timestamp);
                $year = date('Y', $timestamp);
                echo "{$day}" . getDaySuffix($day) . " {$month}, {$year}";
                ?>
            </p>
            <p><?php echo htmlspecialchars($row['review_desc']); ?></p>


            <a href="edit.php?id=<?php echo $row['id']; ?>" class="btn btn-secondary">Edit</a>

            <hr>
        </div>
<?php endwhile;
} else {
    //if the query results show 0 rows, then we've delivered all the results
    echo "endofresults";
}
?>
<?php

// Function for date suffix (st, nd, rd, th)
function getDaySuffix($day)
{
    if ($day >= 11 && $day <= 13) {
        return "th";
    }
    switch ($day % 10) {
        case 1:
            return "st";
        case 2:
            return "nd";
        case 3:
            return "rd";
        default:
            return "th";
    }
}
?>