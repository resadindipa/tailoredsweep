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
$offset = ($page - 1) * $DEFAULT_ITEMS_PER_LOAD_MORE_FOR_PROJECTS; // Load 3 reviews per request

// Initialize the session
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$session_user_id = $_SESSION['ui'];

$project_userid = $session_user_id;

// if($page == 0){
//     $offset = 0;
// }
// Secure SQL query using prepared statements
$query = "SELECT id, project_title, project_desc, project_highlighted_image, project_date FROM projects WHERE project_userid = ? ORDER BY project_date DESC LIMIT " . $DEFAULT_ITEMS_PER_LOAD_MORE_FOR_PROJECTS . " OFFSET ?";
$stmt = mysqli_prepare($link, $query);
mysqli_stmt_bind_param($stmt, "si", $project_userid, $offset);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

$number_of_items = mysqli_num_rows($result);

if ($number_of_items > 0) {
    // Generate HTML for the new reviews
    while ($row = mysqli_fetch_assoc($result)): ?>

        <div class="project-item">

            <div class="row">
                <div class="col-lg-3 col-md-4 col-sm-5 project-item-img-sec">
                    <div class="project-item-img" style="<?php if ($row['project_highlighted_image'] != '') {
                                                                echo "background-image: url('" . $HIGHLIGHTED_PROJECT_IMAGES_LINK_BASE . $row['project_highlighted_image'] . "');";
                                                            } ?>');"></div>
                </div>
                <div class="col-lg-9 col-md-8 col-sm-7 project-item-desc-sec">
                    <div class="project-item-desc-sec-div">
                        <h3 class="project-title"><?php echo htmlspecialchars($row['project_title']); ?></h3>
                        <p class="project-date"><?php
                                                $timestamp = strtotime($row['project_date']);
                                                $day = date('j', $timestamp);
                                                $suffix = date('S', $timestamp); // PHP date doesn't provide 'st', 'nd', 'rd', 'th' suffixes directly
                                                $month = date('F', $timestamp);
                                                $year = date('Y', $timestamp);
                                                echo "{$day}" . getDaySuffix($day) . " {$month}, {$year}";
                                                ?></p>
                        <p class="project-desc"><?php echo htmlspecialchars($row['project_desc']); ?></p>
                        <button class="btn btn-secondary edit-btn">
                            <a href="edit.php?id=<?php echo $row['id']; ?>">Edit Project</a>
                        </button>
                    </div>
                </div>
            </div>

        </div>


<?php endwhile;
} else {
    echo "endofresults";
}
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