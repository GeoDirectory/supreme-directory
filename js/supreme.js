jQuery(document).ready(function () {



    if ( jQuery( "a.sd-my-account-link" ).length ) {
        jQuery('a.sd-my-account-link').click(function (e) {
            e.preventDefault();
            e.stopPropagation();
            jQuery('.sd-my-account-dd').toggle();
        });

        jQuery(document).click(function (e) {
            if (e.target.class != 'sd-my-account-dd' && !jQuery('.sd-my-account-dd').find(e.target).length) {
                jQuery('.sd-my-account-dd').hide();
            }
        });
    }

    jQuery(".search_by_post").change(function () {
        if (jQuery(".geodir-cat-list-tax").length) {
            var postType = jQuery(this).val()
            jQuery(".geodir-cat-list-tax").val(postType + "category");
            jQuery(".geodir-cat-list-tax").change();
        }
    });

    jQuery("#showMap").click(function () {
        jQuery(".sd.archive.geodir-page aside#gd-sidebar-wrapper,.sd.search.geodir-page aside#gd-sidebar-wrapper").css("visibility", "visible");
        jQuery("#showMap").css("display", "none");
        jQuery("#showSearch").css("display", "none");
        jQuery("#hideMap").css("display", "block");
        jQuery( "#hideMap" ).appendTo( ".gd_listing_map_TopLeft" );

    });

    jQuery("#hideMap").click(function () {
        jQuery(".sd.archive.geodir-page aside#gd-sidebar-wrapper,.sd.search.geodir-page aside#gd-sidebar-wrapper").css("visibility", "hidden");
        jQuery("#showMap").css("display", "block");
        jQuery("#showSearch").css("display", "block");
        jQuery("#hideMap").css("display", "none");
        jQuery( "#hideMap" ).appendTo( ".sd-mobile-search-controls" );

    });

    jQuery("#showSearch").click(function () {
        jQuery(".sd.archive.geodir-page .geodir_advanced_search_widget,.sd.search.geodir-page .geodir_advanced_search_widget").toggle(0,function() {
            // Animation complete.
            if ( typeof geodir_reposition_compass == 'function' ) {
                geodir_reposition_compass();
            }
        });
    });

    // fix the advanced search autocompleater results
    sd_set_search_pos();

    if ( jQuery( ".sd-detail-cta a.dt-btn" ).length ) {
        jQuery(".sd-detail-cta a.dt-btn").click(function () {
            jQuery('.geodir-tab-head [data-tab="#reviews"]').closest('dd').trigger('click');
            setTimeout(function(){jQuery('html,body').animate({scrollTop:jQuery('#respond').offset().top}, 'slow');console.log('scroll')}, 200);

        });
    }

    // if ( jQuery( "div.sd-my-account-dd" ).length ) {
    // setTimeout(function(){
    //
    //     var $myAccount = jQuery('div.sd-my-account-dd').clone();
    //     jQuery('#mm-primary-nav .sd-my-account.menu-item').html($myAccount );
    //     console.log($myAccount);
    // }, 100);
    //
    // }



});


function sd_adjust_head(){
    var headHeight = jQuery('#site-header').height();


    if(headHeight>0){headHeight = headHeight-1;}
    jQuery("#geodir_wrapper").css({
        'margin-top': headHeight+"px"
    });

    jQuery("#geodir_content").css({
        height: "calc(100vh - "+headHeight+"px)",
       // 'margin-top': headHeight+"px",
        'overflow-y': "scroll",
        '-webkit-overflow-scrolling': "touch"
    });

    jQuery("#gd-sidebar-wrapper").css({
        height: "calc(100vh - "+headHeight+"px)",
       // 'margin-top': headHeight+"px",
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


    // fix the advanced search near me dropdown
    sd_set_search_pos();

}

(function(){

    // if header is fixed adjest the content to push it down and make it 100vh
    if(jQuery('#site-header').css("position") == "fixed") {
        sd_adjust_head();

        jQuery( window ).resize(function() {
            sd_adjust_head();
        });
    }


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

    jQuery("#sd-home-scroll").click(function(event) {
        event.preventDefault();
        jQuery('html, body').animate({
            scrollTop: jQuery(".featured-area").outerHeight()
        }, 1000);
    });

})();

function sd_set_search_pos(){

    var headHeight = jQuery('#site-header').height();
    var ddHeadHeight = headHeight;
    var hedPos = jQuery('#site-header').css('position');
    if(hedPos=='absolute'){
        ddHeadHeight = 0;
    }

    if ( jQuery( ".gd-near-me-dropdown" ).length ) {
        jQuery(".gd-near-me-dropdown").css({
            'margin-top': -ddHeadHeight+"px"
        });
    }

    // fix the advanced search autocompleater results
    if ( jQuery( ".ac_results" ).length ) {
        jQuery(".ac_results").css({
            'margin-top': -ddHeadHeight+"px"
        });
    }
}