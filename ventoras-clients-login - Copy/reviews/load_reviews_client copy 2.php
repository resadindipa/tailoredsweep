<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>

    <!-- bootstrap cdn -->
    <link rel="stylesheet" href="../../bootstrap/css/bootstrap.min.css">

    <!-- owl carousel css files -->
    <link rel="stylesheet" href="../../owlcarousel/assets/owl.carousel.min.css">
    <link rel="stylesheet" href="../../owlcarousel/assets/owl.theme.default.min.css">

    <!-- home page style css file -->
    <link rel="stylesheet" href="../../styles/home.css">
    <!-- <link rel="stylesheet" href="projects/sydneybuilds.com/styles/home.css"> -->
    <!-- <link rel="stylesheet" href="styles/home2.css"> -->
    <!-- <link rel="stylesheet" href="styles/home3.css"> -->
    <link rel="stylesheet" href="../../styles/home4.css">

    <link rel="stylesheet" href="../../styles/home5.css">
    <link rel="stylesheet" href="../../styles/home6.css">
    <link rel="stylesheet" href="../styles/popup2.css">
</head>

<body>
    <div class="section-item-main" id="reviews-section-item">
        <div class="main-section">

            <div class="main-section-title-inline">
                <p class="section-sub-title section-heading">client testimonial</p>
                <h2 class="section-title section-title-inline">Stories of <span>Satisfaction and Success</span></h2>
                <div class="car-control-btns">
                    <button class="car-control-btn car-control-btn-prev" href="#carouselExampleIndicators2" role="button" data-bs-slide="prev"></button>
                    <button class="car-control-btn car-control-btn-next" href="#carouselExampleIndicators2" role="button" data-bs-slide="next"></button>
                </div>
            </div>
            <div class="row">
                <div class="col-12">
                    <div id="carouselExampleIndicators2" class="carousel slide" data-bs-interval="false">

                        <div class="carousel-inner">




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

                                        $final_profile_picture_link = $PROFILE_PICTURE_LINK_BASE . $DEFAULT_PROFILE_PICTURE_BG_IMAGE;
                                        //print the two col-md-6
                                        if ($row['project_highlighted_image'] != '') {
                                            $final_profile_picture_link =  $PROFILE_PICTURE_LINK_BASE . $all_results[$i]['review_profilepicture'];
                                        }
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

                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>


    <style>
        .limited-text {
            overflow: hidden;
            display: block;
            line-height: 1.5em;
            /* Adjust based on font size */
            max-height: 6em;
            /* 4 lines × 1.5em line-height */
            position: relative;
        }
    </style>

    <script src="../../owlcarousel/jquery-3.7.1.min.js"></script>
    <script src="../../owlcarousel/owl.carousel.min.js"></script>
    <!-- <script src="projects/sydneybuilds.com/js/main.js"></script> -->
    <script src="../../js/main.js"></script>
    <script src="../../js/navbar.js"></script>
    <script src="../../bootstrap/js/bootstrap.min.js"></script>


    <script>
        // document.addEventListener("DOMContentLoaded", function() {
        //     const paragraph = document.querySelector(".limited-text");
        //     const lineHeight = parseFloat(getComputedStyle(paragraph).lineHeight);
        //     const maxHeight = lineHeight * 4; // Limit to 4 lines

        //     if (paragraph.scrollHeight > maxHeight) {
        //         let text = paragraph.textContent.trim();

        //         while (paragraph.scrollHeight > maxHeight && text.length > 0) {
        //             text = text.slice(0, -1).trim(); // Remove characters one by one
        //             paragraph.innerHTML = text + "...<b class='review-view-more' style='color:blue; font-weight: 400;'>Read More</b>"; // Add ellipsis
        //         }
        //     }
        // });

        $(document).ready(function() {

            // let numOfReviews = $(".limited-text").length;
            // let defaultMaxChars = 0;

            // for (let index = 0; index < numOfReviews; index++) {
            //     const paragraph = $(".limited-text")[index];

            //     let fullText = paragraph.text().trim();
            //     let lineHeight = parseFloat(paragraph.css("line-height"));
            //     let maxHeight = lineHeight * 4; // Limit to 4 lines

            //     paragraph.data("full-text", fullText); // Store full text for later use

            //     if (paragraph[0].scrollHeight > maxHeight) {
            //         let text = fullText;

            //         while (paragraph[0].scrollHeight > maxHeight && text.length > 0) {
            //             text = text.slice(0, -1).trim(); // Remove characters one by one
            //             paragraph.html(text + "... <b class='review-view-more' style='color:blue; font-weight: 400;'>Read More</b>");
            //         }

            //         if(defaultMaxChars == 0){
            //             defaultMaxChars = paragraph.innerText;
            //         }
            //     } else {
            //         console.log("11");
            //     }
            // }

            // $(".active .limited-text").each(function() {

            //     let paragraph = $(this);
            //     let fullText = paragraph.text().trim();
            //     let lineHeight = parseFloat(paragraph.css("line-height"));
            //     let fontSize = parseFloat(paragraph.css("font-size"));
            //     let maxHeight = lineHeight * 4; // Limit to 4 lines

            //     // Approximate the max character count based on element width
            //     let avgCharWidth = fontSize * 0.6;  // Adjust based on font size
            //     let maxChars = Math.floor(paragraph.width() / avgCharWidth) * 4; // Estimating characters for 4 lines

            //     paragraph.data("full-text", fullText); // Store full text for later use

            //     if (paragraph[0].scrollHeight > maxHeight && fullText.length > maxChars) {
            //         let truncatedText = fullText.substring(0, maxChars).trim() + "... ";
            //         // paragraph.html(truncatedText + "<b class='review-view-more'>Read More</b>");
            //         paragraph.html(truncatedText + "<b class='review-view-more' style='color:blue; font-weight: 400;'>Read More</b>");

            //     }


            //     // let paragraph = $(this);
            //     // let fullText = paragraph.text().trim();
            //     // let lineHeight = parseFloat(paragraph.css("line-height"));
            //     // let maxHeight = lineHeight * 4; // Limit to 4 lines

            //     // paragraph.data("full-text", fullText); // Store full text for later use

            //     // if (paragraph[0].scrollHeight > maxHeight) {
            //     //     let text = fullText;

            //     //     while (paragraph[0].scrollHeight > maxHeight && text.length > 0) {
            //     //         text = text.slice(0, -1).trim(); // Remove characters one by one
            //     //         paragraph.html(text + "... <b class='review-view-more' style='color:blue; font-weight: 400;'>Read More</b>");
            //     //     }
            //     // } 
            // });

            // $(".car-control-btn").click(function(e) {
            //     $(".active").next(".carousel-item").find(".limited-text").each(function() {
            //         let paragraph = $(this);
            //         let fullText = paragraph.text().trim();
            //         let lineHeight = parseFloat(paragraph.css("line-height"));
            //         let maxHeight = lineHeight * 4; // Limit to 4 lines

            //         paragraph.data("full-text", fullText); // Store full text for later use

            //         if (paragraph[0].scrollHeight > maxHeight) {
            //             let text = fullText;

            //             while (paragraph[0].scrollHeight > maxHeight && text.length > 0) {
            //                 text = text.slice(0, -1).trim(); // Remove characters one by one
            //                 paragraph.html(text + "... <b class='review-view-more' style='color:blue; font-weight: 400;'>Read More</b>");
            //             }
            //             // console.log("00");
            //         } else {
            //             // console.log(paragraph[0].scrollHeight + "---" + maxHeight);

            //         }
            //     });
            // });



            $(document).on("click", ".review-view-more", function() {
                // console.log($(this).closest(".limited-text").data("full-review"));
                showPopup("success", $(this).closest(".limited-text").data("full-review"), false);
            });
        });

        function showPopup(type, message, redirectionToHome = false) {
            let popupClass, btnClass;

            if (type === "success") {
                popupClass = "alert-success";
                btnClass = "btn-secondary";
            } else {
                popupClass = "alert-danger";
                btnClass = "btn-danger";
            }

            // Create overlay and popup HTML
            let popupHtml = `
            <div id="popupOverlay">
                <div id="popupMessage" class="${popupClass}">
                    <p>${message}</p>
                    <button id="closePopup" class="btn ${btnClass}">Cancel</button>
                </div>
            </div>
        `;

            // Append to body
            $("body").append(popupHtml);

            // Close popup when "Cancel" is clicked
            $("#closePopup").click(function() {
                $("#popupOverlay").remove();
                if (redirectionToHome) {
                    window.location.href = "index.php";
                }
            });

            // Close popup when clicking on overlay background (not the popup itself)
            $("#popupOverlay").click(function(e) {
                if (e.target.id === "popupOverlay") {
                    $("#popupOverlay").remove();
                    if (redirectionToHome) {
                        window.location.href = "index.php";
                    }
                }
            });
        }
    </script>

</body>

</html>