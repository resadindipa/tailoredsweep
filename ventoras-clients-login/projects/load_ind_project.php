<?php
// Include database connection
include '../php/config.php';

// Check if an ID is provided
if (isset($_POST['id']) || isset($_GET['id'])) {
    
    if(isset($_GET['id'])){
        $id = $_GET['id'];
    }

    if(isset($_POST['id'])){
        $id = $_POST['id']; // Convert to integer for security
    }

    
    // Fetch review details using prepared statements
    $query = "SELECT id, project_title, project_date, project_desc, project_highlighted_image, project_images FROM projects WHERE id = ? LIMIT 1";
    $stmt = mysqli_prepare($link, $query);
    mysqli_stmt_bind_param($stmt, "s", $id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if ($row = mysqli_fetch_assoc($result)) {
        // Convert date format for the input field
        $date_value = date("Y-m-d", strtotime($row['project_date']));
        $project_images_string_from_db = $row['project_images'];

        $project_highlighted_image = $row['project_highlighted_image'];
        $project_images_array = [];
        if ($project_images_string_from_db != '') {
            $project_images_array = explode(",", $project_images_string_from_db);
        }

        //preparing the final response array
        // $final_array = [];
        // $final_array['title'] = $row['project_title'];
        // $final_array['desc'] = "<p>" . $row['project_desc'] . "</p>";

        $project_image_html = "";
        // var_dump($project_images_array);
        if(sizeof($project_images_array) > 0){
            for ($i = 0; $i < sizeof($project_images_array); $i++) {
                $project_image_html .= "<div class='col-sm-6 col-md-4 col-lg-3 item'>
                    <a href='" . $PROJECT_IMAGES_LINK_BASE . $project_images_array[$i] . "' data-lightbox='photos'><img class='img-fluid' src='" . $PROJECT_IMAGES_LINK_BASE . $project_images_array[$i] . "'></a>
                    </div>
                ";
            }
        }
        // $final_array['images'] = $project_image_html;
        // $final_array['date'] = $row['project_date'];

        // $final_array['status'] = "success";
        $final_array = [
            'title' => $row['project_title'],
            'desc' => "<p>" . $row['project_desc'] . "</p>",
            'images' => $project_image_html,
            'date' => $row['project_date'],
            'status' => "success"
        ];
        echo json_encode($final_array);
    } else {
        $final_array = [];
        $final_array['status'] = "nosuchproject";
        echo json_encode($final_array);
        // Redirect if no review found
        // header("Location: index.php");
        // exit();
    }
} else {
    // Redirect if no ID is provided
    header("Location: index.php");
    exit();
}
