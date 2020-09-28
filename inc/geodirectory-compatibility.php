<?php
/**
 * Functions for the GeoDirectory plugin if installed
 *
 * @since 1.0.0
 * @package Supreme_Directory
 */


/**
 * Add body classes to the HTML where needed.
 *
 * @since 0.0.1
 *
*@param array $classes The array of body classes.
 *
*@return array The array of body classes.
 */
function sd_custom_body_class_gd($classes)
{
    if (geodir_is_page('location')) {
        $classes[] = 'sd-location';
    } elseif (geodir_is_page('preview')) {
        $classes[] = 'sd-preview';
    } elseif (geodir_is_page('listing')) {
        $classes[] = 'sd-archive';
    } elseif (geodir_is_page('add-listing')) {
        $classes[] = 'sd-add';
    }

    if (sd_is_non_location_cpt()) {
        $classes[] = 'sd-loc-less';
    }

    return $classes;
}

add_filter('body_class', 'sd_custom_body_class_gd',11,1);




/**
 * Output the listing map widget.
 *
 * @since 1.0.0
 */
function sd_map_show()
{
    $shortcode_args = array(
        'width' => '100%',
        'autozoom' => 'true'
    );

    $shortcode_args = apply_filters('sd_map_shortcode_args', $shortcode_args);

    $arg_string = array();
    foreach ($shortcode_args as $key => $value) {
        $arg_string[] = $key.'='.$value;
    }
    $imploded_args = implode(' ', $arg_string);

    $shortcode_string = '[gd_listing_map '.$imploded_args.']';
    echo do_shortcode($shortcode_string);
}



/*################################
      DETAIL PAGE FUNCTIONS
##################################*/


add_action('sd_details_featured_area_text','sd_add_event_dates_featured_area');
function sd_add_event_dates_featured_area(){
    global $post,$geodir_date_format,$geodir_date_time_format;

    $schedules = sd_event_get_schedules( $post );

    ?>
    <div class="header-wrap sd-event-dates-head">
        <?php do_action('sd_detail_header_wrap_inner'); ?>
        <?php
        $output = '';
        if ( !empty( $schedules ) ) {
            foreach ( $schedules as $schedule ) {
                $output .= '<p class="gde-recurring-cont">';
                $output .= '<span class="geodir_schedule_start"><i class="fas fa-caret-right"></i> ' . $schedule['start'] . '</span>';
                if ( ! empty( $schedule['end'] ) && $schedule['start'] != $schedule['end'] ) {
                    $output .= '<br />';
                    $output .= '<span class="geodir_schedule_end"><i class="fas fa-caret-left"></i> ' . $schedule['end'] . '</span>';
                }
                $output .= '</p>';
            }
        }
        echo $output;
        ?>
    </div>
    <?php
}

function sd_theme_deactivation($newname, $newtheme) {


}
add_action("switch_theme", "sd_theme_deactivation", 10 , 2);

function sd_add_gd_home_class($classes) {
    if (geodir_is_page('home') || geodir_is_page('location')) {
        $classes[] = 'sd-homepage';
    }
    return $classes;
}
add_filter( 'body_class', 'sd_add_gd_home_class' );




/**
 * This function fixes scroll bar issue by resizing window.
 *
 * In safari scroll bar are not working properly when the user click back button.
 * This function fixes that issue by resizing window.
 * Refer this thread https://wpgeodirectory.com/support/topic/possible-bug/
 *
 * @since 1.0.3
 */
function sd_safari_back_button_scroll_fix() {
    if (geodir_is_page('listing') || geodir_is_page('search') || geodir_is_page('author')) {
    ?>
    <script type="text/javascript">
        jQuery( document ).ready(function() {
            var is_chrome = navigator.userAgent.indexOf('Chrome') > -1;
            var is_safari = navigator.userAgent.indexOf("Safari") > -1 && !is_chrome;
            if (is_safari) {
                window.onpageshow = function(event) {
                    if (event.persisted) {
                        jQuery(window).trigger('resize');
                    }
                };
            }
        });

    </script>
    <?php
    }
}
add_filter('wp_footer', 'sd_safari_back_button_scroll_fix');

/**
 * Add the search and category widgets to the GD home page feature area.
 *
 * @since 1.0.4
 */
function sd_feature_area_gd(){
    if (is_front_page() || geodir_is_page('location')) {
        global $post;
        $shortcode_content = '';
        if(!empty($post->ID)){
            $shortcode_content = get_post_meta($post->ID,'sd_featured_area_content',true);
        }

        if(!$shortcode_content){
//            $shortcode_content = apply_filters('sd_featured_area_content','[gd_search][gd_categories title_tag="hide" post_type="0" cpt_ajax="1" hide_count="1" sort_by="count" max_level="0" max_count="6" hide_empty="1"]');
            $shortcode_content = apply_filters('sd_featured_area_content','[gd_search][gd_categories post_type="0" max_level="0" max_count="6" max_count_child="all" title_tag="h4" design_type="icon-top" icon_size="box-small" sort_by="count" mb="3" row_items="6" hide_empty="true" card_padding_inside="1"  hide_count="true" card_color=\'outline-light\']');
        }
        echo do_shortcode($shortcode_content);
        if(!is_front_page()){
            echo '<div class="home-more  h2"  id="sd-home-scroll" ><a href="#sd-home-scroll" class="text-white"><i class="fa fa-chevron-down"></i></a></div>';
        }
    }
}
add_action('sd_feature_area','sd_feature_area_gd',15);



function sd_is_non_location_cpt() {
    if (geodir_is_page('listing') || geodir_is_page('search') || geodir_is_page('author')) {
        $post_types = get_option( 'geodir_cpt_disable_location' );
        $cur_post_type = geodir_get_current_posttype();
        if (is_array($post_types) && in_array($cur_post_type, $post_types)) {
            return true;
        }
    }
    return false;
}

function sd_add_location_less_style() {
    if ( sd_is_non_location_cpt() && ( geodir_is_page( 'listing' ) || geodir_is_page( 'search' ) || geodir_is_page( 'author' ) ) ) {
    ?>
    .sd.search.geodir-page.sd-loc-less .geodir-common,
    .sd.archive.geodir-page.sd-loc-less .geodir-common {
        padding-right: <?php echo esc_attr(get_theme_mod('dt_container_padding_right', DT_CONTAINER_PADDING_RIGHT)); ?>;
        padding-left: <?php echo esc_attr(get_theme_mod('dt_container_padding_left', DT_CONTAINER_PADDING_LEFT)); ?>;
        margin-right: <?php echo esc_attr(get_theme_mod('dt_container_margin_right', DT_CONTAINER_MARGIN_RIGHT)); ?>;
        margin-left: <?php echo esc_attr(get_theme_mod('dt_container_margin_left', DT_CONTAINER_MARGIN_LEFT)); ?>;
    }
    .sd.search.geodir-page.sd-loc-less #geodir_content,
    .sd.archive.geodir-page.sd-loc-less #geodir_content {
        flex-basis: inherit;
        width: 67% !important;
        padding: 0;
    }

    .sd.geodir-page.sd-loc-less #gd-sidebar-wrapper {
        width: 28% !important;
        flex-basis: inherit !important;
    }
    .sd.geodir-page.sd-loc-less #gd-sidebar-wrapper.geodir-sidebar-left {
        margin-right: 9%!important
    }
    .sd.geodir-page.sd-loc-less #gd-sidebar-wrapper.geodir-sidebar-right {
        margin-left: 9%!important
    }
    .sd.search.geodir-page.sd-loc-less .site-footer,
    .sd.archive.geodir-page.sd-loc-less .site-footer {
        display: block;
    }
    @media (min-width: 1200px) {
        .sd.search.geodir-page.sd-loc-less .geodir-common,
        .sd.archive.geodir-page.sd-loc-less .geodir-common {
            width: <?php echo esc_attr(get_theme_mod('dt_container_width', DT_CONTAINER_WIDTH)); ?>;
            margin-top: 20px;
        }
    }
    @media (max-width: 992px) {
        .sd.search.geodir-page.sd-loc-less #geodir_content,
        .sd.archive.geodir-page.sd-loc-less #geodir_content {
            flex-basis: 100%;
            width: 100% !important;
        }
        #wpadminbar {
            position: fixed
        }
    }
    <?php
    }
}
add_action( 'sd_theme_customize_css', 'sd_add_location_less_style' );


add_filter('sd_featured_area_subtitle','sd_location_subtitle');

function sd_location_subtitle($subtitle){

    if(class_exists('GeoDir_Location_SEO') && geodir_is_page('location')){
        $location_seo = GeoDir_Location_SEO::get_location_seo();
        if(isset($location_seo->image_tagline)){
            $subtitle = $location_seo->image_tagline;
        }
    }

    return $subtitle;
}

function sd_location_manager_image( $image ) {
	global $gd_post;

	if ( !$image && class_exists( 'GeoDir_Location_SEO' ) && ( geodir_is_page( 'location' ) || geodir_is_page( 'archive' ) ) ) {
		$location_seo = GeoDir_Location_SEO::get_location_seo();

		if ( ! empty( $location_seo->image ) ) {
			$full_image_url = wp_get_attachment_image_src( $location_seo->image, 'full' );
			$image = $full_image_url[0];
		}
	} elseif ( !$image && ! empty( $gd_post ) && ! empty( $gd_post->featured_image ) && ! has_post_thumbnail() && ( geodir_is_page( 'detail' ) || geodir_is_page( 'preview' ) ) ) {
		$image = geodir_file_relative_url( $gd_post->featured_image, true ); // Use featured image when post thumbnail is not set.
	}

	return $image;
}
add_filter( 'sd_featured_image', 'sd_location_manager_image' );

add_action('wp','sd_compatibility_action',15);
function sd_compatibility_action(){
    // remove the actions disabling the featured image
    remove_filter( "get_post_metadata", array('GeoDir_Template_Loader','filter_thumbnail_id'), 10 );
}

/**
 * Filter archive page map shortcode.
 *
 * @since 2.0.0.9
 *
 * @param string $shortcode Archive page map shortcode.
 *
 * @return string Filtered shortcode.
 */
function sd_archive_gd_map_shortcode( $shortcode ) {
	$extra_args = '';

	// Marker cluster
	if ( defined( 'GEODIR_MARKERCLUSTER_VERSION' ) ) {
		$extra_args .= ' marker_cluster=1';
	}

	if ( $extra_args != '' ) {
		$shortcode = str_replace( "]", $extra_args . "]", $shortcode );
	}

	return $shortcode;
}
add_filter( 'sd_archive_gd_map_shortcode', 'sd_archive_gd_map_shortcode', 10, 1 );

/**
 * Filter search page title on GD search page.
 *
 * @since 2.0.0.11
 *
 * @param string $title Search page title.
 * @return string Filtered title.
 */
function sd_geodir_search_page_featured_area_title( $title ) {
	if ( geodir_is_page( 'search' ) ) {
		$title = the_title( '', '', false );
	}

	return $title;
}
add_filter( 'sd_featured_area_search_page_title', 'sd_geodir_search_page_featured_area_title', 10, 1 );

/**
 * Set the featured image on archive and post type pages if set.
 * 
 * @param $image
 *
 * @return mixed
 */
function sd_archive_feature_image( $image ) {

    $attachment_id = 0;
    if ( !$image && geodir_is_page( 'post_type' ) ) {
        $post_type = geodir_get_current_posttype();
        if( $post_type ){

            $cpts = geodir_get_posttypes('array');
            if ( ! empty( $cpts[$post_type]['default_image'] ) ) {
                $attachment_id = absint( $cpts[$post_type]['default_image'] );
            }
        }

    } elseif ( !$image && geodir_is_page( 'archive' ) ) {
        $term_id = get_queried_object_id();

        if( $term_id ){
            $term_image = get_term_meta( $term_id, 'ct_cat_default_img', true );
            if(!empty($term_image['id'])){
                $attachment_id = absint($term_image['id']);
            }
        }

    }


    if( !$image && !empty($attachment_id )){
        $full_image_url = wp_get_attachment_image_src( $attachment_id , 'full' );
        $image = $full_image_url[0];
    }


    return $image;
}
add_filter( 'sd_featured_image', 'sd_archive_feature_image', 9, 1 );

/**
 * Add rating to single page featured area.
 *
 * @param $subtitle
 *
 * @return string
 */
function gd_sd_featured_area_subtitle($subtitle){
    
    if(empty($subtitle) && geodir_is_page('single')){
        $subtitle = do_shortcode("[gd_post_rating show='stars'  size='h2'  alignment='center'  list_hide=''  list_hide_secondary='' ]");
    }
    
    return $subtitle;
}
add_filter('sd_featured_area_subtitle','gd_sd_featured_area_subtitle');

/**
 * Make header transparent on certain pages.
 *
 * @param $class
 *
 * @return string
 */
function gd_sd_header_extra_class( $class ){

    if(geodir_is_page('location')){
        $class .= ' z-index-1 position-absolute w-100 bg-transparent ';
    }

    return $class;
}
add_filter('dt_header_extra_class','gd_sd_header_extra_class');


// JS
function gd_sd_enqueue_script(){
    
    wp_add_inline_script( 'geodir', gd_sd_script() ); 
}
add_action('wp_enqueue_scripts', 'gd_sd_enqueue_script');

function gd_sd_script(){
ob_start();
if(0){ ?><script><?php }?>
        jQuery(document).ready(function() {
            jQuery("body").on("geodir_setup_search_form", function() {
                if (jQuery(".featured-area .geodir-cat-list-tax").length) {
                    var postType = jQuery('.featured-area .search_by_post').val();
                    jQuery(".geodir-cat-list-tax").val(postType);
                    jQuery(".geodir-cat-list-tax").change();
                }
            });

            jQuery("#showMap").click(function() {
                jQuery('#sd-sidebar-left,#sd-sidebar-right,#hideMap').removeClass('d-none');
                jQuery('#sd-archive-map,#showMap').addClass('d-none');
            });

            jQuery("#hideMap").click(function() {
                jQuery('#sd-sidebar-left,#sd-sidebar-right,#hideMap').addClass('d-none');
                jQuery('#sd-archive-map,#showMap').removeClass('d-none');
            });

            jQuery("#showSearch").click(function() {
                jQuery("body").toggleClass('sd-show-search');
                if (typeof geodir_reposition_compass == 'function') {
                    geodir_reposition_compass();
                }
            });

            if (jQuery(".sd-detail-cta a.dt-btn").length) {
                jQuery(".sd-detail-cta a.dt-btn").click(function() {
                    sd_scroll_to_reviews();
                });
            }

            jQuery(".sd-detail-cta .gd-write-a-review-badge, .sd-ratings .geodir-post-rating .gd-list-rating-link").on('click', function(e) {
                e.preventDefault();
                sd_scroll_to_reviews();
            });


        });

        function sd_scroll_to_reviews() {
            jQuery(".geodir-tab-head [href='#reviews']")[0].click();

            setTimeout(function() {
                jQuery('html,body').animate({
                    scrollTop: jQuery('#comments #respond').offset().top
                }, 'slow');
                jQuery('#comments #respond #comment').focus();
            }, 200);
        }

        var $sd_sidebar_position = '';
        (function() {
            // set the sidebar position var
            if (jQuery('body.sd-right-sidebar').length) {
                $sd_sidebar_position = 'right';
            } else {
                $sd_sidebar_position = 'left';
            }

            if (jQuery(".featured-img").length) {
                var windowHeight = screen.height;
                var parallax = document.querySelectorAll(".featured-img"),
                    speed = 0.6;
                var bPos = jQuery(".featured-img").css("background-position");
                var arrBpos = bPos.split(' ');
                var originalBpos = arrBpos[1];
                var fetHeight = parseInt(jQuery(".featured-area").css("height"));
                var fetAreHeight = jQuery(".featured-area").offset().top + fetHeight;

                window.onscroll = function() {
                    var f = 0;
                    [].slice.call(parallax).forEach(function(el, i) {
                        if (f > 1) {
                            return;
                        }
                        var windowYOffset = window.pageYOffset;
                        originalBpos = parseInt(originalBpos);
                        var perc = windowYOffset / fetAreHeight + (originalBpos / 100);
                        //"50% calc("+originalBpos+" - " + (windowYOffset * speed) + "px)"
                        parallaxPercent = 100 * perc;
                        if (parallaxPercent > 100) {
                            parallaxPercent = 100;
                        }
                        jQuery(el).css("background-position", "50% " + parallaxPercent + "%");
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

            sd_insert_archive_resizer('.sd-archive-listings','.sd-archive-map');
        })();

        // insert archive page size adjuster
        function sd_insert_archive_resizer($listings_container,$map_container) {
            $screen_width = screen.width;
            if (jQuery($map_container).length && $screen_width > 992) {
                $offset = 'mr-n5';
                if ($sd_sidebar_position == 'left') {
                    $offset = 'ml-n5';
                }
                jQuery($listings_container).prepend('<span class="sd-archive-resizer '+$offset+' iconbox iconsmall fill rounded-circle bg-primary text-white shadow border-0 c-pointer" title="Drag to resize" data-toggle="tooltip"  style="position: sticky;top: 50vh;z-index: 1;width: 30px;height: 30px;line-height: 30px;"><i class="fas fa-arrows-alt-h"></i></span>');
                sd_position_archive_resizer($listings_container);
            }

            sd_reposition_archive($listings_container,$map_container);
        }

        function sd_position_archive_resizer($container) {
            var $offset = 21;
            if ($sd_sidebar_position == 'left') {
                $container = '.sd-sidebar';
                $offset = 13;
            }
            $width = jQuery($container).outerWidth() - $offset;
            jQuery('.sd-archive-resizer').css('left', $width);
        }

        function sd_reposition_archive($container,$map_container) {

            var $sd_set_archive_width = false;
// function to adjust width of archive elements
            jQuery('body.geodir-fixed-archive .sd-archive-resizer').mousedown(function(e) {console.log('down');
                e.preventDefault();
                $left_container = $container;
                $rigth_container = $map_container;
                // var $container = '.entry-content';
                if ($sd_sidebar_position == 'left') {
                    $left_container = $map_container;
                    $rigth_container = $container;
                }
                jQuery(document).mousemove(function(e) {
                    jQuery($left_container).removeClass('col-md-7 col-12').addClass('col').css("width", e.pageX + 2).css("max-width", e.pageX + 2).css("flex-basis", 'auto');
                    jQuery($rigth_container).removeClass('col-md-5 col-12').addClass('col');
                    sd_position_archive_resizer($container);
                    $sd_set_archive_width = true;
                });
            });

            jQuery(document).mouseup(function(e) {
                jQuery(document).unbind('mousemove');

                // set the value if we have localstorage
                if ($sd_set_archive_width && geodir_is_localstorage()) {console.log('up');
                    // var $container = '.entry-content';
                    var $offset = 21;
                    if ($sd_sidebar_position == 'left') {
                        $container = '.sd-sidebar';
                        $offset = 13;
                    }
                    $width = jQuery('body.geodir-fixed-archive ' + $container).outerWidth() - $offset;
                    localStorage.setItem('sd_archive_width', $width);
                    window.dispatchEvent(new Event('resize')); // so map tiles fill in
                }
            });
        }

        <?php if(0){ ?></script><?php }

    return ob_get_clean();
    
}
