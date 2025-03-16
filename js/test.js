// $(document).ready(function() {
$('.img-bna-div-container').each(function(index) {
    let container = $(this);
    let totalPairs = parseInt(container.data("total-pairs")) || 1;
    let BnASectionNumber = index + 1; // Assign section number by order
    let currentIndex = 1;


    //i is starting from 2, because 1st initial div with image is already in HTML code
    for (var i = 2; i <= totalPairs; i++) {
        for (let g = 1; g <= 2; g++) {


            let classString = "";
            if (g == 1) {
                classString = "section-item-side-com-img-list-item section-item-side-com-img-list-item-before";
            } else {
                classString = "section-item-side-com-img-list-item section-item-side-com-img-list-item-after";
            }
            let beforeorAfterImagesDiv = container.find('.img-bna-' + g);
            var div = $('<div></div>')
                .addClass(classString)
                .css("display", "none")
                .css('background-image', `url('https://www.ventoras.com/ventoras-clients-login/uploads/beforeafter/image.php?domain=tailoredsweep.com&section=${BnASectionNumber}&pair=${i}&side=${g}&testerror')`)
                // .css('background-image', `url('https://www.ventoras.com/ventoras-clients-login/uploads/beforeafter/tailoredsweep.com/${BnASectionNumber}/${i}/${g}.webp')`)
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
            // setInterval(showNextDiv, 3000); // Repeat every 3 seconds

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


    function getSubstringAfterWord(text, word) {
        let index = text.indexOf(word);
        if (index !== -1) {
            return text.substring(index + word.length); // Get substring after the word
        }
        return ""; // Return empty if word is not found
    }
    let word = "https://www.ventoras.com/ventoras-clients-login/uploads/beforeafter/image.php?domain=tailoredsweep.com&";

    setTimeout(() => {


        function loadImage(attempt = 0, maxAttempts = 6) {
            // for (let g = 0; g < 2; g++) {
            g = 0;
            let element = $(itemsAllArray[g][index - 1]);
            let backgroundImage = element.css("background-image")
                // let backgroundImage = element.css('background-image');

            // Extract URL from background-image: url("...")

            let imageUrl = backgroundImage.replace(/^url\(["']?/, '').replace(/["']?\)$/, '');
            let img = new Image();

            // Append a cache-buster query param to force reload
            let newImageUrl = imageUrl + (imageUrl.includes("?") ? "&" : "?") + "cache_buster=" + new Date().getTime();

            // let img = new Image();
            img.src = newImageUrl; // Use new URL with cache buster



            // img.src = imageUrl;

            if (attempt == 0) {
                console.log("First Time Loading Image: " + getSubstringAfterWord(imageUrl, word));
            } else {
                console.log(`Re-loading image ${attempt}` + getSubstringAfterWord(imageUrl, word));
            }

            img.onload = function() {
                console.warn(`Background image loaded after ${attempt} attempts (Index:${index}): ${getSubstringAfterWord(newImageUrl, word)}`);

                element.css('background-image', `url(${newImageUrl})`); // Update background-image with successfully loaded URL
                element.css('display', 'block'); // Show the element

                if (attempt > 0) {
                    element.attr("data-loaded-tries", attempt);
                }
            };

            img.onerror = function() {
                console.error(`Failed to load background image (Index ${index}, Attempt ${attempt + 1} of ${maxAttempts}): ${getSubstringAfterWord(newImageUrl, word)}`);

                if (attempt < maxAttempts) {
                    setTimeout(() => {
                        loadImage(attempt + 1, maxAttempts); // Retry with an increased attempt count
                    }, 1000); // Retry after 1 second
                } else {
                    console.error(`Max retries reached for: ${getSubstringAfterWord(newImageUrl, word)}`);
                }
            };
            // }
        }
        loadImage(0, 6);

    }, index * 500);
}