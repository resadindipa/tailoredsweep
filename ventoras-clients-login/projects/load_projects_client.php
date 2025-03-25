<?php
// Database connection
include '../php/config.php';

// if (!isset($_POST['client'])) {
//     print_update_status_basic_layout(false, "missingparams");
// } else {
// $client_id = $_GET['client'];
if (!isset($_POST['website'])) {
    print_update_status_basic_layout(false, "missingparams");
}

$client_website = $_POST['website'];
$query = "SELECT id FROM users WHERE website = ? LIMIT 1";
$stmt = mysqli_prepare($link, $query);

$client_id = "";
if ($stmt) {
    mysqli_stmt_bind_param($stmt, "s", $client_website); // Assuming $website is the variable holding the website value
    mysqli_stmt_execute($stmt);

    $result = mysqli_stmt_get_result($stmt);

    if ($result && mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_row($result);
        $client_id = $row[0]; //mysqli_fetch_row returns an numerically indexed array, not an associative array
    }
} 

if($client_id == ""){
    print_update_status_basic_layout(false, "error");
}

// $client_id = "8upzqc0c8zacsk4wg5ua";


// Secure SQL query using prepared statements
$query = "SELECT id,project_highlighted_image FROM projects WHERE project_userid = ? ORDER BY project_date DESC";
$stmt = mysqli_prepare($link, $query);
mysqli_stmt_bind_param($stmt, "s", $client_id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

$all_results = mysqli_fetch_all($result, MYSQLI_ASSOC);
$number_of_items = mysqli_num_rows($result);

if ($number_of_items > 0) {
    for ($i = 0; $i < $number_of_items; $i++) {

        // var_dump($all_results[$i]);
        //Select every other element
        if ($i % 3 == 0) {
            //make the first carousel-item "active"
            if ($i == 0) {
                echo "<div class='carousel-item active'>";
            } else {
                echo "<div class='carousel-item'>";
            }

            echo "<div class='row'>";
        }
        $final_image_link = $PROJECT_IMAGES_LINK_BASE . $DEFAULT_PROJECT_BG_IMAGE;
        //print the two col-md-6
        if ($all_results[$i]['project_highlighted_image'] != '') {
            $final_image_link =  $HIGHLIGHTED_PROJECT_IMAGES_LINK_BASE . $all_results[$i]['project_highlighted_image'];
        }
?>
        <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12">
            <div class="num-item-cell">
                <div class="num-item-cell-div projects-item-div">
                    <div class="projects-item-img" style="background-image: url('<?php echo $final_image_link; ?>');">
                        <a href="project.html?id=<?php echo $all_results[$i]['id']; ?>" class="projects-item-desc-btn osc-btn-container"></a>
                    </div>
                </div>
            </div>
        </div>


<?php
        if ($i % 3 == 2) {
            echo "</div></div>";
        }
    }
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