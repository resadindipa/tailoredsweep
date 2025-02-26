// var owl2 = $('#owl-carousel-2');
// var owl = $('#owl-carousel-1');

// let hasAllShown = false;

// let autoWidthValue = true;
// if (parseInt($(window).width()) < 768) {
//     autoWidthValue = false;
// }
// console.log(parseInt($(window).width()).toString() + "--" + autoWidthValue);

// owl2.owlCarousel({
//     margin: 18,
//     loop: false,
//     autoWidth: autoWidthValue,
//     responsiveClass: true,
//     responsive: {
//         0: {
//             items: 1,
//             nav: false
//         },

//         650: {
//             items: 2,
//             nav: false
//         },
//         1000: {
//             items: 4,
//             nav: true,
//             loop: false
//         }
//     }
// })

// owl.owlCarousel({
//     margin: 18,
//     loop: false,
//     autoWidth: autoWidthValue,
//     responsiveClass: true,
//     responsive: {
//         0: {
//             items: 1,
//             nav: false
//         },
//         650: {
//             items: 2,
//             nav: false
//         },
//         1000: {
//             items: 4,
//             nav: false,
//             loop: false
//         }
//     }
// })

// $('#nextbtn').click(function() {
//     console.log("edsdsds")
//     owl.trigger('next.owl.carousel');
// })

// $('#prevbtn').click(function() {
//     owl.trigger('prev.owl.carousel');
// })


// changing the name of the title in main section periodically
// let names = ['New Customers', 'More Sales', 'Valuable Leads', 'Business Growth'];

// let i = 0;
// setInterval(function() {

//     if (i < names.length - 1) {
//         i++;
//     } else {
//         i = 0;
//     }

//     document.getElementById("main-section-change").innerHTML = names[i];

// }, 1500);
//second commit
const scrollTime = 1200;
$(document).ready(function() {
    let screenWidth = screen.width;

    // Retrieve and log the message passed in the URL, if any
    const urlParams = new URLSearchParams(window.location.search);
    const message = urlParams.get('scroll');
    if (message) {
        $('html, body').animate({
            scrollTop: $("#" + message).offset().top
        }, scrollTime);
    }


    function removeQueryParameter(param) {
        const url = new URL(window.location.href);

        if (url.searchParams.has(param)) {
            // Remove the specific parameter
            url.searchParams.delete(param);

            // Update the URL without reloading the page
            window.history.replaceState({}, document.title, url.toString());
        }
    }

    // Detect if the page is refreshed
    if (performance.getEntriesByType('navigation')[0].type === 'reload') {
        // Remove the `scroll` parameter on refresh
        removeQueryParameter('scroll');

        //Scroll back to the top
        // $('html, body').animate({
        //     scrollTop: 0
        // }, 0);
    }

    $(document).on("click", ".review-view-more", function() {
        // console.log($(this).closest(".limited-text").data("full-review"));
        showPopup("success", $(this).closest(".limited-text").data("full-review"), false);
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

    // $(document).ready(function() {
    $('.img-bna-div-container').each(function(index) {
        let container = $(this);
        let totalPairs = parseInt(container.data("total-pairs")) || 1;
        let BnASectionNumber = index + 1; // Assign section number by order
        let currentIndex = 1;

        function switchImages() {
            // console.log("switchingimages", container);
            let nextIndex;
            if (currentIndex >= totalPairs) {
                nextIndex = 1;
            } else {
                nextIndex = currentIndex + 1;
            }

            container.find(".img-bna-1").css("background-image", `url('https://www.ventoras.com/ventoras-clients-login/uploads/beforeafter/tailoredsweep.com/${BnASectionNumber}/${nextIndex}/1.jpg')`);
            container.find(".img-bna-2").css("background-image", `url('https://www.ventoras.com/ventoras-clients-login/uploads/beforeafter/tailoredsweep.com/${BnASectionNumber}/${nextIndex}/2.jpg')`);

            currentIndex = nextIndex;
        }

        switchImages(); // Initialize with first images
        setInterval(switchImages, 3000);
    });
    // });

    $.ajax({
        type: "POST",
        async: false,
        url: "https://www.ventoras.com/ventoras-clients-login/reviews/load_reviews_client.php",
        data: { page: "s" },
        success: function(data) {
            if (data.trim() !== "endofresults") {
                $("#reviews-carousel-inner").append(data);
            } else {
                console.log("Something Happened", data);
                // $("#load-more").hide();
            }
        }
    });

    $.ajax({
        type: "POST",
        async: false,
        url: "https://www.ventoras.com/ventoras-clients-login/projects/load_projects_client.php",
        data: { page: "s" },
        success: function(data) {
            if (data.trim() !== "endofresults") {
                $("#projects-carousel-inner").append(data);
            } else {
                console.log("Something Happened", data);
                // $("#load-more").hide();
            }
        }
    });

    // Attach click event to all parent-div elements
    $('.parent-div').click(function() {
        // Get the data-target attribute value (child div ID)
        var targetId = $(this).attr('data-target');
        var clicked = $(this).attr('data-clicked').toString();

        if (clicked == "false") {
            $(this).attr('class', "faq-item parent-div faq-item-expanded")
            $(this).attr('data-clicked', "true");
            // $(this).find('img').css('transform', "rotateX(180deg)");
            $(this).find('img').attr('src', 'contents/images/arrowupwhite.svg')
            $('#' + targetId).show();
            $(this).find('.faq-item-arrow').css('background-color', '#6464642a')
            $(this).find('.faq-item-content').css('background-color', '#3871C1')
            $(this).find('.faq-item-quest').css('color', 'white')
            $(this).find('.faq-item-content').css('border-bottom-left-radius', '0')
            $(this).find('.faq-item-content').css('border-bottom-right-radius', '0')
        } else {
            $(this).attr('class', "faq-item parent-div")
            $('#' + targetId).hide();
            $(this).find('img').attr('src', 'contents/images/arrow-down.png')
            $(this).find('img').css('transform', "rotateX(0deg)");
            $(this).attr('data-clicked', "false");
            $(this).find('.faq-item-arrow').css('background-color', 'transparent')
            $(this).find('.faq-item-content').css('background-color', '#F4F4F4')
            $(this).find('.faq-item-quest').css('color', '#404452')

            let borderRadius = '5px'
            if (screenWidth <= 768) {
                borderRadius = '0px'
            }
            $(this).find('.faq-item-content').css('border-bottom-left-radius', borderRadius)
            $(this).find('.faq-item-content').css('border-bottom-right-radius', borderRadius)
        }
    });


    // check_for_nav();
    $(window).on('scroll', function() {
        // check_for_nav();
    });





    $("#submit-form").click(function(e) {
        e.preventDefault();


        let name = $('#name').val()
        let phonenumber = $('#phonenumber').val()

        let message = $('#message').val()

        if (name == "" || phonenumber == "" || message == "") {
            $('#form-error').text('- Complete all the fields');
            $('#form-error').show();
            if (name == "") {
                $('#name').focus();
            } else if (phonenumber == "") {
                $('#phonenumber').focus();
            } else {
                $('#message').focus();
            }
        } else {

            $.post("https://www.ventoras.com/form/submit.php", {
                    name: name,
                    phonenumber: phonenumber,
                    message: message
                },
                function(data, status) {
                    // console.log("Data: " + data + "\nStatus: " + status);
                    if (data == "success") {
                        $('#form-error').hide();
                        $('.contact-text-form').hide();
                        $('#contact-section-success').show();
                    }
                });


        }

    });


    function IsEmail(email) {
        const regex =
            /^([a-zA-Z0-9_\.\-\+])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/;
        if (!regex.test(email)) {
            return false;
        } else {
            return true;
        }
    }
});