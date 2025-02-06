// var owl2 = $('#owl-carousel-2');
// var owl = $('#owl-carousel-1');



// let autoWidthValue = true;
// if (parseInt($(window).width()) < 768) {
//     autoWidthValue = false;
// }
// // console.log(parseInt($(window).width()).toString() + "--" + autoWidthValue);

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

$(document).ready(function() {
    let screenWidth = screen.width;
    if (screenWidth <= 868) {
        $('.carousel-items-3').remove();
        if (screenWidth <= 768) {
            $('.full-width-container .section-title:first-child()').find('br').remove();
            $('#main-section-change').find('br').remove();
        }


    } else {
        $('.carousel-items-2').remove();

    }

    // Scroll to the specific sections when clicked on a button or link(text)
    $(".action-btn").click(function() {
        $('#nav-bar-toggle-close').click();

        var targetId = $(this).attr('data-scroll');
        $('html, body').animate({
            scrollTop: $("#" + targetId).offset().top
        }, 2000);
    });




    // check_for_nav();
    // $(window).on('scroll', function() {
    //     check_for_nav();
    // });

    // function check_for_nav() {
    //     var y_scroll_pos = window.pageYOffset;
    //     // console.log(y_scroll_pos)
    //     var scroll_pos_test = 250; // set to whatever you want it to be

    //     if (y_scroll_pos > scroll_pos_test) {
    //         //do stuff
    //         $('.nav-bar-1').attr('class', 'nav-bar nav-bar-1 nav-bar-white')
    //         $('#nav-bar-toggle').attr('src', 'contents/images/navbaricon.svg')
    //     } else {
    //         $('.nav-bar-1').attr('class', 'nav-bar nav-bar-1')
    //         $('#nav-bar-toggle').attr('src', 'contents/images/navbaricon.svg')
    //     }
    // }


    $('#nav-bar-toggle').click(function() {
        $('.nav-bar-toggled').css('top', '0%');
    });

    $('#nav-bar-toggle-close').click(function() {
        $('.nav-bar-toggled').css('top', '100%');
    });



    //     $("#submit-form").click(function(e) {
    //         e.preventDefault();


    //         let name = $('#name').val()
    //         let email = $('#email').val()
    //         let message = $('#message').val()

    //         if (name == "" || email == "" || message == "") {
    //             $('#form-error').text('- Complete all the fields');
    //             $('#form-error').show();
    //             if (name == "") {
    //                 $('#name').focus();
    //             } else if (email == "") {
    //                 $('#email').focus();
    //             } else {
    //                 $('#message').focus();
    //             }
    //         } else {
    //             if (IsEmail(email) == false) {
    //                 $('#form-error').show();
    //                 $('#form-error').text("- Enter an valid email");
    //             } else {
    //                 $.post("form/submit.php", {
    //                         name: name,
    //                         email: email,
    //                         message: message
    //                     },
    //                     function(data, status) {
    //                         console.log("Data: " + data + "\nStatus: " + status);
    //                         if (data == "success") {
    //                             $('#form-error').hide();
    //                             $('.contact-text-form').hide();
    //                             $('#contact-section-success').show();
    //                         }
    //                     });
    //             }

    //         }

    //     });


    //     function IsEmail(email) {
    //         const regex =
    //             /^([a-zA-Z0-9_\.\-\+])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/;
    //         if (!regex.test(email)) {
    //             return false;
    //         } else {
    //             return true;
    //         }
    //     }
});