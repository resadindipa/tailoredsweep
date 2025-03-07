let numberOfItemsPerLoad = 3;
$(document).ready(function() {
    let page = 1; // Start with the first page
    // let page2 = 1;
    let nomorereviews = false;

    //Check if there's already an existing page GET variable in the url
    var $_GET = {};

    document.location.search.replace(/\??(?:([^=]+)=([^&]*)&?)/g, function() {
        function decode(s) {
            return decodeURIComponent(s.split("+").join(" "));
        }

        $_GET[decode(arguments[1])] = decode(arguments[2]);
    });

    //Already a 'page' variable exists in the URL
    if ($_GET['page'] != null) {
        page = parseInt($_GET['page']);
        // console.log(page);
    }
    // else {
    // console.log(page);
    // }



    //If the page = 2, load the review items chunks accordingly
    if (page != 1) {
        for (let index = 1; index < page + 1; index++) {
            if (!nomorereviews) {
                loadReviews(true, true, index);
            }
        }
    } else {
        //page=1
        loadReviews(true, false, page);
    }

    $("#load-more").click(function() {
        //+1 because we're loading the NEXT badge of three reviews
        loadReviews(false, false, page + 1);
    });
});

function loadReviews(initialLoad = false, allAtOnce = false, page2 = page) {
    $.ajax({
        type: "POST",
        async: true,
        url: "../projects/load_projects.php",
        data: { page: page2 },
        success: function(data) {
            if (data.trim() !== "endofresults") {
                //if there isn't a need to load more items, the code below will hide this button
                $("#load-more").show();


                $("#projects-container").append(data);


                //count how many individual reviews are in the response
                var countOfReviews = $(data).find('.project-title').length;

                //If it's less than 3, that means there isn't going to another 3-pack to receive
                //So hide load more button and set nomorereview=true;
                if (countOfReviews != numberOfItemsPerLoad) {
                    nomorereviews = true;
                    $("#load-more").hide();
                }

                if (initialLoad == false && allAtOnce == false) {
                    page++; // Increment page number for next load
                    history.pushState(null, "", "?page=" + page); // Update URL without reload
                    console.log(page);
                }

            } else {
                nomorereviews = true;
                $("#load-more").hide();
            }
        }
    });

}