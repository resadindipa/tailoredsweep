<?php
// Database connection
include '../php/config.php';

// Get page number from AJAX request
if (isset($_POST['page'])) {
    $page = (int) $_POST['page'];
} else {
    echo 'missingparams';
    exit;
}
// $page = isset($_POST['page']) ? (int)$_POST['page'] : 0;
$offset = ($page - 1) * 3; // Load 3 reviews per request

// if($page == 0){
//     $offset = 0;
// }
// Secure SQL query using prepared statements
$query = "SELECT id, name, review, profilepicture, review_date FROM reviews ORDER BY review_date DESC LIMIT 3 OFFSET ?";
$stmt = mysqli_prepare($link, $query);
mysqli_stmt_bind_param($stmt, "i", $offset);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

// Generate HTML for the new reviews
while ($row = mysqli_fetch_assoc($result)):?>

    <div class="review-card">
        <div class="review-header">
            <div class="profile-picture" style="background-image: url('<?php echo $row['profilepicture'] ? $row['profilepicture'] : 'default.png'; ?>');"></div>
            <p><b><?php echo htmlspecialchars($row['name']); ?></b></p>
        </div>
        <p class="text-muted">
            <?php
            $timestamp = strtotime($row['review_date']);
            $day = date('j', $timestamp);
            $suffix = date('S', $timestamp); // PHP date doesn't provide 'st', 'nd', 'rd', 'th' suffixes directly
            $month = date('F', $timestamp);
            $year = date('Y', $timestamp);
            echo "{$day}" . getDaySuffix($day) . " {$month}, {$year}";
            ?>
        </p>
        <p><?php echo nl2br(htmlspecialchars($row['review'])); ?></p>


        <a href="edit.php?id=<?php echo $row['id']; ?>" class="btn btn-secondary">Edit</a>

        <hr>
    </div>
<?php endwhile;

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