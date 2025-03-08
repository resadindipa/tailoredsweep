$(document).ready(function() {

    $(window).on('scroll', function() {
        check_for_nav();
    });

    // Scroll to the specific sections when clicked on a button or link(text)
    $(".navbar-navigate-link").click(function() {
        $('#nav-bar-toggle-close').click();

        var targetId = $(this).attr('data-scroll');
        var targetPage = $(this).attr('data-page');
        var currentPage = window.location.pathname.split('/').pop();

        if (currentPage == "") {
            currentPage = "index.html"
        }

        // console.log(currentPage + "--" + targetPage);

        if (currentPage == targetPage && targetId != "") {
            $('html, body').animate({
                scrollTop: $("#" + targetId).offset().top
            }, 1200);
        } else {
            // e.preventDefault(); // Prevent default anchor behavior
            // console.log("Ext Link");

            // Redirect only if the target page is not the current page
            if (targetPage !== currentPage) {
                if (targetId != "") {
                    window.location.href = `${targetPage}?scroll=${encodeURIComponent(targetId)}`;
                } else {
                    window.location.href = `${targetPage}`;
                }
            } else {
                // console.log(targetId); // Log a message if trying to navigate to the same page
            }
        }
    });


    function check_for_nav() {
        var y_scroll_pos = window.pageYOffset;
        // console.log(y_scroll_pos)
        var scroll_pos_test = 250; // set to whatever you want it to be

        if (y_scroll_pos > scroll_pos_test) {
            //do stuff
            $('.nav-bar-1').attr('class', 'nav-bar nav-bar-1 nav-bar-white')
                // $('#nav-bar-toggle').attr('src', 'contents/images/navbaricon.svg')
        } else {
            $('.nav-bar-1').attr('class', 'nav-bar nav-bar-1')
                // $('#nav-bar-toggle').attr('src', 'contents/images/navbaricon.svg')
        }
    }


    $('#nav-bar-toggle').click(function() {
        $('#indipa').css('display', "block");
        $('.nav-bar-toggled').css('top', '20%');
    });

    $('#nav-bar-toggle-close').click(function() {
        $('#indipa').css('display', "none");
        $('.nav-bar-toggled').css('top', '100%');
    });


    $('#indipa').click(function() {
        $('#indipa').css('display', "none");
        $('.nav-bar-toggled').css('top', '100%');
    });

});