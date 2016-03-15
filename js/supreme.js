jQuery(document).ready(function () {

    jQuery(".search_by_post").change(function () {
        if (jQuery(".geodir-cat-list-tax").length) {
            var postType = jQuery(this).val()
            jQuery(".geodir-cat-list-tax").val(postType + "category");
            jQuery(".geodir-cat-list-tax").change();
        }
    });

    jQuery("#showMap").click(function () {
        jQuery(".sd.archive.geodir-page aside#gd-sidebar-wrapper").css("visibility", "visible");
        jQuery("#showMap").css("display", "none");
        jQuery("#showSearch").css("display", "none");
        jQuery("#hideMap").css("display", "block");
    });

    jQuery("#hideMap").click(function () {
        jQuery(".sd.archive.geodir-page aside#gd-sidebar-wrapper").css("visibility", "hidden");
        jQuery("#showMap").css("display", "block");
        jQuery("#showSearch").css("display", "block");
        jQuery("#hideMap").css("display", "none");
    });

    jQuery("#showSearch").click(function () {
        jQuery(".sd.archive.geodir-page .geodir_advanced_search_widget").toggle();
    });

    jQuery("#showMap").click(function () {
        jQuery(".sd.search.geodir-page aside#gd-sidebar-wrapper").css("visibility", "visible");
        jQuery("#showMap").css("display", "none");
        jQuery("#showSearch").css("display", "none");
        jQuery("#hideMap").css("display", "block");
    });

    jQuery("#hideMap").click(function () {
        jQuery(".sd.search.geodir-page aside#gd-sidebar-wrapper").css("visibility", "hidden");
        jQuery("#showMap").css("display", "block");
        jQuery("#showSearch").css("display", "block");
        jQuery("#hideMap").css("display", "none");
    });

    jQuery("#showSearch").click(function () {
        jQuery(".sd.search.geodir-page .geodir_advanced_search_widget").toggle();
    });


    // if header is fixed adjest the content to push it down and make it 100vh
    if(jQuery('#site-header').css("position") == "fixed") {
        sd_adjust_head();

        jQuery( window ).resize(function() {
            sd_adjust_head();
        });
    }

});


function sd_adjust_head(){
    var headHeight = jQuery('#site-header').height();

    jQuery("#geodir_content").css({
        height: "calc(100vh - "+headHeight+"px)",
        'margin-top': headHeight+"px",
        'overflow-y': "scroll"
    });

    jQuery("#gd-sidebar-wrapper").css({
        height: "calc(100vh - "+headHeight+"px)",
        'margin-top': headHeight+"px",
        'overflow': "hidden"
    });

}

(function(){
    if ( jQuery( ".featured-img" ).length ) {

        var parallax = document.querySelectorAll(".featured-img"),
            speed = 0.6;
        var bPos = jQuery( ".featured-img").css("background-position");
        var arrBpos= bPos.split(' ');
        var originalBpos = arrBpos[1];
        window.onscroll = function () {
            [].slice.call(parallax).forEach(function (el, i) {

                var windowYOffset = window.pageYOffset;
                jQuery(el).css("background-position", "50% calc("+originalBpos+" - " + (windowYOffset * speed) + "px)");

            });
        };
    }





})();