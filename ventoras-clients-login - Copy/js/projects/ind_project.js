$(document).ready(function() {
    $.ajax({
        type: "POST",
        async: false,
        url: "../project/load_reviews.php",
        data: { page: page2 },
        success: function(data) {
            if (data.trim() !== "endofresults") {
                $("#reviews-container").append(data);


                //count how many individual reviews are in the response
                var countOfReviews = $(data).find('.review-header').length;

                // console.log(countOfReviews);
                //If it's less than 3, that means there isn't going to another 3-pack to receive
                //So hide load more button and set nomorereview=true;
                if (countOfReviews != numberOfItemsPerLoad) {
                    nomorereviews = true;
                    $("#load-more").hide();
                }

                if (initialLoad == false && allAtOnce == false) {
                    page++; // Increment page number for next load
                    history.pushState(null, "", "?page=" + page); // Update URL without reload
                    // console.log(page);
                }

            } else {
                nomorereviews = true;
                $("#load-more").hide();
            }
        }
    });
});