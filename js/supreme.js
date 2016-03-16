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
        jQuery( "#hideMap" ).appendTo( ".gd_listing_map_TopLeft" );
    });

    jQuery("#hideMap").click(function () {
        jQuery(".sd.archive.geodir-page aside#gd-sidebar-wrapper").css("visibility", "hidden");
        jQuery("#showMap").css("display", "block");
        jQuery("#showSearch").css("display", "block");
        jQuery("#hideMap").css("display", "none");
        jQuery( "#hideMap" ).appendTo( ".sd-mobile-search-controls" );
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

});


function sd_adjust_head(){
    var headHeight = jQuery('#site-header').height();

    jQuery("#geodir_content").css({
        height: "calc(100vh - "+headHeight+"px)",
        'margin-top': headHeight+"px",
        'overflow-y': "scroll",
        '-webkit-overflow-scrolling': "touch"
    });

    jQuery("#gd-sidebar-wrapper").css({
        height: "calc(100vh - "+headHeight+"px)",
        'margin-top': headHeight+"px",
        'overflow': "hidden"
    });

    jQuery(".sd.search.geodir-page #sticky_map_gd_listing_map, .sd.archive.geodir-page #sticky_map_gd_listing_map").css({
        height: "calc(100vh - "+headHeight+"px)",
       // 'margin-top': headHeight+"px",
        'overflow': "hidden"
    });

    jQuery(".sd.search.geodir-page #gd_listing_map_wrapper, .sd.search.geodir-page #gd_listing_map, .sd.search.geodir-page #gd_listing_map_loading_div, .sd.archive.geodir-page #gd_listing_map_wrapper, .sd.archive.geodir-page #gd_listing_map, .sd.archive.geodir-page #gd_listing_map_loading_div").css({
        height: "calc(100vh - "+headHeight+"px)",
        // 'margin-top': headHeight+"px",
        'overflow': "hidden"
    });




}

(function(){

    // if header is fixed adjest the content to push it down and make it 100vh
    if(jQuery('#site-header').css("position") == "fixed") {
        sd_adjust_head();

        jQuery( window ).resize(function() {
            sd_adjust_head();
        });
    }

    console.log(screen.height);
    console.log(jQuery(window).height());


    if ( jQuery( ".featured-img" ).length ) {

        var windowHeight = screen.height;


        var parallax = document.querySelectorAll(".featured-img"),
            speed = 0.6;
        var bPos = jQuery( ".featured-img").css("background-position");
        var arrBpos= bPos.split(' ');
        var originalBpos = arrBpos[1];
        var fetHeight = parseInt(jQuery( ".featured-area").css("height"));
        var fetAreHeight = jQuery( ".featured-area").offset().top + fetHeight;


        window.onscroll = function () {
            [].slice.call(parallax).forEach(function (el, i) {

                var windowYOffset = window.pageYOffset;

                originalBpos = parseInt(originalBpos);

                var perc =  windowYOffset / fetAreHeight + (originalBpos / 100);

                //"50% calc("+originalBpos+" - " + (windowYOffset * speed) + "px)"

                parallaxPercent = 100*perc;
                if(parallaxPercent>100){parallaxPercent=100;}

                jQuery(el).css("background-position","50% "+parallaxPercent+"%" );

            });
        };
    }

})();