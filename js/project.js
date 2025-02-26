$(document).ready(function() {

    let id = 0;
    var $_GET = {};

    document.location.search.replace(/\??(?:([^=]+)=([^&]*)&?)/g, function() {
        function decode(s) {
            return decodeURIComponent(s.split("+").join(" "));
        }

        $_GET[decode(arguments[1])] = decode(arguments[2]);
    });

    //Already a 'page' variable exists in the URL
    if ($_GET['id'] != null) {
        id = $_GET['id'];
        // console.log(id);
    } else {
        // console.log(id);
    }


    $.ajax({
        type: "POST",
        async: true,
        url: "https://www.ventoras.com/ventoras-clients-login/projects/load_ind_project.php",
        data: { id: id },
        // contentType: false,
        // processData: false,
        dataType: 'json',
        success: function(response) {
            if (response.status == "success") {
                let photoArray = response.images;
                $(".photos").append(photoArray);

                let projectTitle = response.title;
                let projectDate = response.date;
                let projectDesc = response.desc;

                $("#project-date").text(projectDate);
                $("#project-desc").html(projectDesc);
                $("#project-title").text(projectTitle);
            } else {
                $("#project-not-found").show();
            }

        }
    });

});