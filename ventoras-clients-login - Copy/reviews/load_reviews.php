<?php
// Database connection
include '../php/config.php';

//ctype_digit($_POST['page']) checks if the page value is a positive integer or not
if (isset($_POST['page']) && filter_var($_POST['page'], FILTER_VALIDATE_INT) !== false && ctype_digit($_POST['page'])) {
    $someone_logged_in = is_someone_logged_in();
    if ($someone_logged_in) {


        // Initialize the session
        //NOTE - no need to start the session, it was already started when is_someone_logged_in was called in
        // if (session_status() === PHP_SESSION_NONE) {
        //     session_start();
        // }

        $review_userid = $_SESSION['ui'];

        // Get page number from AJAX request
        $page = (int) $_POST['page'];

        // $page = isset($_POST['page']) ? (int)$_POST['page'] : 0;
        $offset = ($page - 1) * $DEFAULT_ITEMS_PER_LOAD_MORE_FOR_REVIEWS; // Load 3 reviews per request

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

                <div class="review-item">
                    <div class="review-divider">
                        <div class="review-profile-picture" style="<?php if ($row['review_profilepicture'] != '') {
                                                                        echo "background-image: url('" . $PROFILE_PICTURE_LINK_BASE . $row['review_profilepicture'] . "');";
                                                                    } ?>');">
                        </div>
                        <div class="review-profile-desc">
                            <div class="review-profile-desc-texts">
                                <p class="review-name"><b><?php echo htmlspecialchars($row['review_name']); ?></b></p>
                                <p class="review-date"><?php
                                                        $timestamp = strtotime($row['review_date']);
                                                        $day = date('j', $timestamp);
                                                        $suffix = date('S', $timestamp);
                                                        $month = date('F', $timestamp);
                                                        $year = date('Y', $timestamp);
                                                        echo "{$day}" . getDaySuffix($day) . " {$month}, {$year}";
                                                        ?></p>
                            </div>
                            <div class="review-profile-desc-btn">
                                <button class="btn btn-secondary edit-btn">
                                    <a href="edit.php?id=<?php echo $row['id']; ?>">Edit Review</a>
                                </button>
                            </div>
                        </div>
                    </div>
                    <p class="review-desc"><?php echo htmlspecialchars($row['review_desc']); ?></p>
                </div>


<?php endwhile;
        } else {
            //if the query results show 0 rows, then we've delivered all the results
            print_update_status_basic_layout(false, "endofresults");
        }
    } else {
        //not logged in
        print_update_status_basic_layout(false, "loginrequired");
    }
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