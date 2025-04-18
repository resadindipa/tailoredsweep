const scrollTime = 1200;
$(document).ready(function() {

    // Scroll to the specific sections when clicked on a button or link(text)
    $(".action-btn").click(function() {
        $('#nav-bar-toggle-close').click();

        var targetId = $(this).attr('data-scroll');
        if (targetId != "") {
            $('html, body').animate({
                scrollTop: $("#" + targetId).offset().top
            }, 1200);
        }
    });

    let pairsArray = [3, 3];

    // let pairsArray = [];

    // function sendBeforeAfterPairsCount(attempt = 0, maxAttempts = 3) {
    //     $.ajax({
    //         type: "POST",
    //         async: false,
    //         dataType: "json",
    //         // url: "https://www.ventoras.com/ventoras-clients-login/beforeafter/pairs_client.php",
    //         url: "http://localhost/tailoredsweep/ventoras-clients-login/beforeafter/pairs_client.php",
    //         data: { domain: "tailoredsweep.com" },
    //         success: function(data) {
    //             if (data.error) {
    //                 console.error("Error:", data.error);
    //                 return;
    //             }

    //             // Get the number of sections
    //             let numSections = data.length;
    //             console.log("Number of Sections:", numSections);

    //             // Create an array of pairs count
    //             pairsArray = data.map(section => section.pairs);
    //             console.log("Pairs Array:", pairsArray);
    //         },
    //         error: function(data) {
    //             if (attempt < maxAttempts) {
    //                 let retryDelay = 2000;
    //                 // console.log(`Retrying in ${retryDelay / 1000} seconds...`);

    //                 setTimeout(() => {
    //                     sendBeforeAfterPairsCount(attempt + 1, maxAttempts);
    //                 }, retryDelay);
    //             } else {
    //                 // console.error("Max retry attempts reached. Request failed.");
    //             }
    //         }
    //     });
    // }

    // sendBeforeAfterPairsCount();

    $(document).on("click", ".projects-item-img", function() {
        // console.log("clicked " + $(this).find('a').attr("href"));
        window.location = $(this).find('a').attr("href");
    });


    // $(document).ready(function() {
    $('.img-bna-div-container').each(function(index) {
        let container = $(this);
        let totalPairs = parseInt(container.data("total-pairs")) || 1;
        let BnASectionNumber = index + 1; // Assign section number by order
        let currentIndex = 1;


        //i is starting from 2, because 1st initial div with image is already in HTML code
        // for (var i = 2; i <= pairsArray[index]; i++) {
        for (var i = 2; i <= totalPairs; i++) {

            for (let g = 1; g <= 2; g++) {


                let classString = "";
                if (g == 1) {
                    classString = "section-item-side-com-img-list-item section-item-side-com-img-list-item-before";
                } else {
                    classString = "section-item-side-com-img-list-item section-item-side-com-img-list-item-after";
                }
                let beforeorAfterImagesDiv = container.find('.img-bna-' + g);
                let timestamp = new Date().getTime();
                var div = $('<div></div>')
                    .addClass(classString)
                    .css("display", "none")
                    // .css('background-image', `url('https://www.ventoras.com/ventoras-clients-login/uploads/beforeafter/image.php?domain=tailoredsweep.com&section=${BnASectionNumber}&pair=${i}&side=${g}')`)
                    .css('background-image', `url('https://www.ventoras.com/ventoras-clients-login/uploads/beforeafter/tailoredsweep.com/${BnASectionNumber}/${i}/${g}.webp?v=${timestamp}')`)
                    .css('height', '100%');

                // await new Promise(resolve => setTimeout(resolve, 1000));

                beforeorAfterImagesDiv.append(div);


                let index2 = 0;
                let divs = beforeorAfterImagesDiv.find('div'); // Select all divs inside .img-bna-1
                let totalDivs = divs.length;

                function showNextDiv() {
                    // divs.hide(); // Hide all divs
                    // divs.eq(index2).show(); // Show the current div
                    divs.css("height", "0%");
                    // divs.eq(index2).css("display", "block");
                    divs.eq(index2).css("height", "100%");
                    index2 = (index2 + 1) % totalDivs; // Move to the next index, cycling back to 0
                }

                // divs.hide(); // Initially hide all divs
                divs.css("height", "0%");
                showNextDiv(); // Show the first div
                setInterval(showNextDiv, 3000); // Repeat every 3 seconds

                $("#main-section-btn-2").click(function(e) {
                    showNextDiv();
                });
            }

        }

    });


    let itemsbefore = $('.section-item-side-com-img-list-item-before');
    let itemsafter = $('.section-item-side-com-img-list-item-after');

    let itemsbeforeArray = [];
    let itemsafterArray = [];

    let itemsAllArray = [itemsbeforeArray, itemsafterArray];
    //index is starting from +1 because 0 (the first image) is alreayd being loaded from the HTML code
    for (let index = 1; index < ($('.section-item-side-com-img-list-item').length) / 2; index++) {
        // const element = array[index];


        itemsAllArray[0].push(itemsbefore[index]);
        itemsAllArray[1].push(itemsafter[index]);


        // function getSubstringAfterWord(text, word) {
        //     let index = text.indexOf(word);
        //     if (index !== -1) {
        //         return text.substring(index + word.length); // Get substring after the word
        //     }
        //     return ""; // Return empty if word is not found
        // }
        // let word = "https://www.ventoras.com/ventoras-clients-login/uploads/beforeafter/image.php?domain=tailoredsweep.com&";

        // setTimeout(() => {


        // function loadImage(attempt = 0, maxAttempts = 6) {
        //     // for (let g = 0; g < 2; g++) {
        //     g = 0;
        //     let backgroundImage = $(itemsAllArray[g][index - 1]).css("background-image")
        //         // let backgroundImage = element.css('background-image');

        //     // Extract URL from background-image: url("...")

        //     let imageUrl = backgroundImage.replace(/^url\(["']?/, '').replace(/["']?\)$/, '');
        //     let img = new Image();
        //     img.src = imageUrl;

        //     if (attempt == 0) {
        //         console.log("Trying to load the image: " + getSubstringAfterWord(imageUrl, word));
        //     } else {
        //         console.log(`Re-loading image ${attempt}` + getSubstringAfterWord(imageUrl, word));
        //     }

        //     img.onload = function() {
        //         console.warn(`Background image loaded with ${attempt}: ${getSubstringAfterWord(imageUrl, word)} `);
        //         if (attempt == 0) {
        //             $(itemsAllArray[g][index - 1]).css('display', 'block'); // Show the element after successful load
        //         } else {

        //         }
        //     };

        //     img.onerror = function() {
        //         console.error(`Failed to load background image: ${getSubstringAfterWord(imageUrl, word)}, Index ${index}, Attempt ${attempt + 1} of ${maxAttempts}`);

        //         if (attempt < maxAttempts) {
        //             setTimeout(() => {
        //                 loadImage(attempt + 1, maxAttempts);
        //             }, 1000); // Retry after 2 seconds
        //         } else {
        //             console.error(`Max retries reached for: ${getSubstringAfterWord(imageUrl, word)}`);
        //         }
        //     };
        //     // }
        // }
        // loadImage(0, 6, index);
        // }, index * 500);

        setTimeout(() => {
            console.log("Before Image Loading - " + index + "---" + $(itemsbefore[index]).css("background-image"));
            $(itemsbefore[index]).css('display', 'block');
            console.log("After Image Loading - " + index + "---" + $(itemsafter[index]).css("background-image"));
            $(itemsafter[index]).css('display', 'block');
        }, index * 500);
    }


    // items.each(function(index) {
    //     if (index > 0) {
    //         itemsbeforeArray.push(itemsbefore[index]);
    //         setTimeout(() => {
    //             console.log("Before Image Loading - " + index + "---" + $(this).css("background-image"));
    //             // $(this).css('display', 'block');
    //         }, index * 500);
    //     }
    // });

    // items = $('.section-item-side-com-img-list-item-after');

    // items.each(function(index) {
    //     if (index > 0) {
    //         setTimeout(() => {
    //             console.log("After Image Loading - " + index + "---" + $(this).css("background-image"));
    //             // $(this).css('display', 'block');
    //         }, index * 500);
    //     }
    // });


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


    // });

    function sendPostReviewsRequest(attempt = 0, maxAttempts = 3) {
        $.ajax({
            type: "POST",
            async: false,
            url: "https://www.ventoras.com/ventoras-clients-login/reviews/load_reviews_client.php",
            // url: "http://localhost/tailoredsweep/ventoras-clients-login/reviews/load_reviews_client.php",
            data: { website: "tailoredsweep.com" },
            success: function(data) {
                if (!isValidJSON(data)) {
                    if (data.trim() !== "endofresults") {
                        // console.log(data);
                        $("#reviews-carousel-inner").append(data);
                    } else {
                        console.log("Something Happened", data);
                        // $("#load-more").hide();
                    }
                } else {
                    $("#reviews-section-item").hide();
                }
            },
            error: function(data) {
                if (attempt < maxAttempts) {
                    let retryDelay = 2000;
                    console.log(`Retrying in ${retryDelay / 1000} seconds...`);

                    setTimeout(() => {
                        sendPostReviewsRequest(attempt + 1, maxAttempts);
                    }, retryDelay);
                } else {
                    console.error("Max retry attempts reached. Request failed.");
                }
            }
        });
    }


    function sendPostProjectsRequest(attempt = 0, maxAttempts = 3) {
        $.ajax({
            type: "POST",
            async: false,
            url: "https://www.ventoras.com/ventoras-clients-login/projects/load_projects_client.php",
            // url: "http://localhost/tailoredsweep/ventoras-clients-login/projects/load_projects_client.php",
            data: { website: "tailoredsweep.com" },
            success: function(data) {
                if (!isValidJSON(data)) {
                    if (data.trim() !== "endofresults") {
                        // console.log(data);
                        $("#projects-carousel-inner").append(data);
                    } else {
                        console.log("Something Happened", data);
                        // $("#load-more").hide();
                    }
                } else {
                    $("#projects-section-item").hide();
                }
            },
            error: function(data) {
                if (attempt < maxAttempts) {
                    let retryDelay = 2000;
                    console.log(`Retrying in ${retryDelay / 1000} seconds...`);

                    setTimeout(() => {
                        sendPostProjectsRequest(attempt + 1, maxAttempts);
                    }, retryDelay);
                } else {
                    console.error("Max retry attempts reached. Request failed.");
                }
            }
        });

    }

    sendPostReviewsRequest();
    // sendPostProjectsRequest();

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
    // $(window).on('scroll', function() {
    // check_for_nav();
    // });





    $("#submit-form").click(function(e) {
        e.preventDefault();


        let name = $('#name').val();
        let phonenumber = $('#phonenumber').val();
        let email = $("#emailaddress").val();
        let message = $('#message').val();

        if (name == "" || phonenumber == "" || email == "" || message == "") {
            if (name == "") {
                $('#form-error').text('Name should be Entered.');
                $('#name').focus();
            } else if (phonenumber == "") {
                $('#form-error').text('Phone number should be Entered.');
                $("#phonenumber").focus();
            } else if (email == "") {
                $('#form-error').text('Email Address should be Entered.');
                $("#emailaddress").focus();
            } else {
                $('#form-error').text('Message should be Entered.');
                $('#message').focus();
            }
            $('#form-error').show();
        } else {

            $("#submit-form").val("Sending....");
            $("#submit-form").prop("disabled", true);

            $.post("https://www.ventoras.com/ventoras-clients-login/client-messages/form/submit.php", {
                    name: name,
                    phonenumber: phonenumber,
                    emailaddress: email,
                    message: message,
                    website: "tailoredsweep.com"
                },
                function(data, status) {

                    $("#submit-form").val("Send Message");
                    $("#submit-form").prop("disabled", false);
                    console.log("Data: " + data + "\nStatus: " + status);
                    if (data == "success") {
                        $('#form-error').hide();
                        $('.contact-text-form').hide();
                        $('#contact-section-success').show();
                    }
                });


        }

    });

    function isValidJSON(str) {
        try {
            JSON.parse(str);
            return true;
        } catch (e) {
            return false;
        }
    }


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