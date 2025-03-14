<?php
// Database connection
include '../php/config.php';

// if (!isset($_POST['client'])) {
//     print_update_status_basic_layout(false, "missingparams");
// } else {
// $client_id = $_GET['client'];
$client_id = "8upzqc0c8zacsk4wg5ua";


// Secure SQL query using prepared statements
$query = "SELECT id, review_name, review_desc, review_profilepicture, review_date FROM reviews WHERE review_userid = ? ORDER BY review_date DESC";
$stmt = mysqli_prepare($link, $query);
mysqli_stmt_bind_param($stmt, "s", $client_id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

$all_results = mysqli_fetch_all($result, MYSQLI_ASSOC);
$number_of_items = mysqli_num_rows($result);

if ($number_of_items > 0) {
    for ($i = 0; $i < $number_of_items; $i++) {

        $final_profile_picture_link = $PROFILE_PICTURE_LINK_BASE . $DEFAULT_PROFILE_PICTURE_BG_IMAGE;
        //print the two col-md-6
        if ($all_results[$i]['review_profilepicture'] != '') {
            $final_profile_picture_link =  $PROFILE_PICTURE_LINK_BASE . $all_results[$i]['review_profilepicture'];
        }

        // var_dump($all_results[$i]);
        //Select every other element
        if ($i % 2 == 0) {
            //make the first carousel-item "active"
            if ($i == 0) {
                echo "<div class='carousel-item active'>";
            } else {
                echo "<div class='carousel-item'>";
            }

            echo "<div class='row'>";


            //print the two col-md-6
        }
?>
        <div class="col-md-6">
            <div class="num-item-cell">
                <div class="num-item-cell-div">
                    <div class="num-item-cell-top-sec">
                        <div class="display-flex">
                            <div class="review-profile-pic" style="background-image: url(' <?php echo $final_profile_picture_link; ?>');">
                            </div>
                            <div class="review-profile-name">
                                <h4><?php echo $all_results[$i]['review_name']; ?></h4>
                                <p>Written on <?php $timestamp = strtotime($all_results[$i]['review_date']);
                                                $day = date('j', $timestamp);
                                                $suffix = date('S', $timestamp);
                                                $month = date('F', $timestamp);
                                                $year = date('Y', $timestamp);
                                                echo "{$day}" . getDaySuffix($day) . " {$month}, {$year}"; ?></p>
                            </div>
                        </div>
                    </div>
                    <p class="reviews-desc limited-text" data-full-review="<?php echo $all_results[$i]['review_desc']; ?>"><?php echo $all_results[$i]['review_desc']; ?>
                        <!-- <b class='review-view-more' style="color:blue; font-weight: 400;">Read More</b> -->
                    </p>
                </div>
            </div>
        </div>


    <?php

        if ($i % 2 == 1) {
            echo "</div></div>";
        }
    }
    ?>



<?php
} else {
    //if the query results show 0 rows, then we've delivered all the results
    echo "endofresults";
}

// }

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