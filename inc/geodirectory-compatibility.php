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
            $shortcode_content = apply_filters('sd_featured_area_content','[gd_search][gd_categories title_tag="hide" post_type="0" cpt_ajax="1" hide_count="1" sort_by="count" max_level="0" max_count="6" hide_empty="1"]');
        }
        echo do_shortcode($shortcode_content);
        echo '<div class="home-more"  id="sd-home-scroll" ><a href="#sd-home-scroll"><i class="fa fa-chevron-down"></i></a></div>';
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