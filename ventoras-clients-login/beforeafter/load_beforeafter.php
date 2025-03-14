<?php
// Database connection
include '../php/config.php';

//ctype_digit($_POST['page']) checks if the page value is a positive integer or not
$someone_logged_in = is_someone_logged_in();
if ($someone_logged_in) {


    // Initialize the session
    //NOTE - no need to start the session, it was already started when is_someone_logged_in was called in
    // if (session_status() === PHP_SESSION_NONE) {
    //     session_start();
    // }

    $userid_session = $_SESSION['ui'];


    // Secure SQL query using prepared statements
    $query = "SELECT beforeaftersections,website FROM users WHERE id = ? LIMIT 1 ";
    $stmt = mysqli_prepare($link, $query);
    mysqli_stmt_bind_param($stmt, "s", $userid_session);

    mysqli_stmt_execute($stmt);
    // Store result to get the number of rows
    mysqli_stmt_store_result($stmt);

    if (mysqli_stmt_num_rows($stmt) > 0) {
        mysqli_stmt_bind_result($stmt, $beforeaftersectionsnumber, $websitedomain);

        mysqli_stmt_fetch($stmt);
    } else {
        print_update_status_basic_layout(false, "error");
    }
    
    if ($beforeaftersectionsnumber > 0) {
        for ($i = 1; $i <= $beforeaftersectionsnumber; $i++) { ?>
            <div class="project-item">

                <div class="row">
                    <div class="col-lg-3 col-md-4 col-sm-5 project-item-img-sec">
                        <div class="project-item-img" style="<?php echo "background-image: url('{$BEFORE_AFTER_IMAGES_LINK_BASE}{$websitedomain}/{$i}/1/1.webp')"; ?>"></div>
                    </div>
                    <div class="col-lg-9 col-md-8 col-sm-7 project-item-desc-sec">
                        <div class="project-item-desc-sec-div">
                            <h3 class="project-title">Section <?php echo $i; ?></h3>
                            <br>
                            <button class="btn btn-secondary edit-btn">
                                <a href="edit.php?id=<?php echo $i; ?>">Edit Section</a>
                            </button>
                        </div>
                    </div>
                </div>

            </div>
<?php
        }
    } else {
        //client's website doesn't have any before & after sections
        print_update_status_basic_layout(false, "nobeforeaftersections");
    }
} else {
    //not logged in
    print_update_status_basic_layout(false, "loginrequired");
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
