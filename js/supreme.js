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

    jQuery("body").on("geodir_setup_search_form", function(){
        if (jQuery(".geodir-cat-list-tax").length) {
            var postType = jQuery('.featured-area .search_by_post').val()
            jQuery(".geodir-cat-list-tax").val(postType + "category");
            jQuery(".geodir-cat-list-tax").change();
        }
    });

    jQuery("#showMap").click(function () {
        jQuery('body').addClass('sd-map-only').removeClass('sd-listings-only');
        jQuery( "#hideMap" ).appendTo( ".gd_listing_map_TopLeft" );

    });

    jQuery("#hideMap").click(function () {
        jQuery('body').addClass('sd-listings-only').removeClass('sd-map-only');
        jQuery( "#hideMap" ).appendTo( ".sd-mobile-search-controls" );

    });

    jQuery("#showSearch").click(function () {
        jQuery("body").toggleClass('sd-show-search');

        if ( typeof geodir_reposition_compass == 'function' ) {
                    geodir_reposition_compass();
        }

    });
    

    if ( jQuery( ".sd-detail-cta a.dt-btn" ).length ) {
        jQuery(".sd-detail-cta a.dt-btn").click(function () {
            sd_scroll_to_reviews();
        });
    }

    if ( jQuery( ".sd-ratings a.geodir-pcomments" ).length ) {
        jQuery(".sd-ratings a.geodir-pcomments").click(function () {
            sd_scroll_to_reviews();
        });
    }

});

function sd_scroll_to_reviews(){
    jQuery('.geodir-tab-head [data-tab="#reviews"]').closest('dd').trigger('click');
    setTimeout(function(){jQuery('html,body').animate({scrollTop:jQuery('#respond').offset().top}, 'slow');console.log('scroll')}, 200);
}


function sd_adjust_head(){
    var headHeight = jQuery('#site-header').height();

    if ( jQuery( "body").hasClass('admin-bar') ) {

        // if admin bar present then set margin top to 0 so we can adjust things later
        if(jQuery('html').css("margin-top")!='0px'){
            jQuery('html').attr('style', jQuery('html').attr('style') + '; ' + 'margin-top: 0 !important');
        }


        var winWidth = jQuery( window ).width();

        if(winWidth>782){
            headHeight = headHeight + 32;
        }else{
            headHeight = headHeight + 46;
        }

    }

    if(headHeight>0){headHeight = headHeight-1;}
    jQuery("#geodir_wrapper").css({
        'margin-top': headHeight+"px"
    });
    
    if (jQuery("body").hasClass('sd-loc-less')) {
        return;
    }

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
            var f =0;
            [].slice.call(parallax).forEach(function (el, i) {
                if(f>1){return;}
                var windowYOffset = window.pageYOffset;

                originalBpos = parseInt(originalBpos);

                var perc =  windowYOffset / fetAreHeight + (originalBpos / 100);

                //"50% calc("+originalBpos+" - " + (windowYOffset * speed) + "px)"

                parallaxPercent = 100*perc;
                if(parallaxPercent>100){parallaxPercent=100;}

                jQuery(el).css("background-position","50% "+parallaxPercent+"%" );
                f++;

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
