<?php
/**
 * Functions for the GeoDirectory plugin if installed
 *
 * @since 1.0.0
 * @package Supreme_Directory
 */

/*
 * remove breadcrumb from search, listings and detail page.
 */
remove_action('geodir_search_before_main_content', 'geodir_breadcrumb', 20);
remove_action('geodir_listings_before_main_content', 'geodir_breadcrumb', 20);
remove_action('geodir_detail_before_main_content', 'geodir_breadcrumb', 20);
remove_action('geodir_author_before_main_content', 'geodir_breadcrumb', 20);

/*
 * add search widget on top of search results and in listings page.
 */
function sd_before_listing_content_search()
{
    if (sd_is_non_location_cpt()) {
        add_action('geodir_search_content', 'sd_search_form_on_search_page', 4);
    }else{
        add_action('geodir_search_content', 'sd_search_form_on_search_page', 4);
        add_action('geodir_listings_content', 'sd_search_form_on_search_page', 4);
    }
}

add_action('wp', 'sd_before_listing_content_search');


/**
 * Outputs the search form.
 *
 * @since 1.0.0
 */
function sd_search_form_shortcode()
{
    $shortcode_args = array(
        
    );

    $shortcode_args = apply_filters('sd_search_shortcode_args', $shortcode_args);

    $arg_string = array();
    foreach ($shortcode_args as $key => $value) {
        $arg_string[] = $key.'='.$value;
    }
    $imploded_args = implode(' ', $arg_string);

    $shortcode_string = '[gd_advanced_search '.$imploded_args.']';
    echo do_shortcode($shortcode_string);
}

/**
 * Outputs the search widget.
 *
 * @since 1.0.0
 */
function sd_search_form_on_search_page()
{
    sd_search_form_shortcode();
}


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
        if (get_option('geodir_show_listing_right_section', true)) {
            $classes[] = 'sd-right-sidebar';
        } else {
            $classes[] = 'sd-left-sidebar';
        }
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
 * Remove and change some standard GeoDirectory widget areas.
 *
 * This function disables the listings pages sidebars and uses the GeoDirectory design setting to select map left/right
 * on listings pages.
 *
 * @since 1.0.0
 */
function sd_theme_actions()
{

    if (sd_is_non_location_cpt()) {
        return;
    }

    unregister_sidebar('geodir_listing_left_sidebar');
    unregister_sidebar('geodir_listing_right_sidebar');

    unregister_sidebar('geodir_search_left_sidebar');
    unregister_sidebar('geodir_search_right_sidebar');

    if(isset($_REQUEST['geodir_dashbord'])){
	    unregister_sidebar('geodir_author_left_sidebar');
	    unregister_sidebar('geodir_author_right_sidebar');
    }

    // listings page
    if (get_option('geodir_show_listing_right_section', true)) {
        add_action('geodir_listings_sidebar_right_inside', 'sd_map_show');
        remove_action('geodir_listings_sidebar_left', 'geodir_action_listings_sidebar_left', 10);
    } else {
        add_action('geodir_listings_sidebar_left_inside', 'sd_map_show');
        remove_action('geodir_listings_sidebar_right', 'geodir_action_listings_sidebar_right', 10);
    }

    // search page
    if (get_option('geodir_show_search_right_section', true)) {
        add_action('geodir_search_sidebar_right_inside', 'sd_map_show');
        remove_action('geodir_search_sidebar_left', 'geodir_action_search_sidebar_left', 10);
    } else {
        add_action('geodir_search_sidebar_left_inside', 'sd_map_show');
        remove_action('geodir_search_sidebar_right', 'geodir_action_search_sidebar_right', 10);
    }

    // author page
    if (get_option('geodir_show_author_right_section', true)) {
        if(isset($_REQUEST['geodir_dashbord'])){
            add_action('geodir_author_sidebar_right_inside', 'sd_map_show');
        }
        remove_action('geodir_author_sidebar_left', 'geodir_action_author_sidebar_left', 10);
    } else {
        if(isset($_REQUEST['geodir_dashbord'])){
            add_action('geodir_author_sidebar_left_inside', 'sd_map_show');
        }
        remove_action('geodir_author_sidebar_right', 'geodir_action_author_sidebar_right', 10);
    }

}

add_action('wp', 'sd_theme_actions', 15);


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


/**
 * Output the mobile map buttons HTML.
 *
 * @since 1.0.0
 */
function sd_mobile_map_buttons()
{
    echo '<div class="sd-mobile-search-controls">
			<a class="dt-btn" id="showSearch" href="#">
				<i class="fas fa-search"></i> ' . __('SEARCH LISTINGS', 'supreme-directory') . '</a>
			<a class="dt-btn" id="hideMap" href="#"><i class="fas fa-th-large">
				</i> ' . __('SHOW LISTINGS', 'supreme-directory') . '</a>
			<a class="dt-btn" id="showMap" href="#"><i class="far fa-map">
				</i> ' . __('SHOW MAP', 'supreme-directory') . '</a>
			</div>';
}

add_action('geodir_listings_content', 'sd_mobile_map_buttons', 5);
add_action('geodir_search_content', 'sd_mobile_map_buttons', 5);


/*################################
      DETAIL PAGE FUNCTIONS
##################################*/

// remove the preview page code to move it inside the featured area
remove_action('geodir_detail_before_main_content', 'geodir_action_geodir_preview_code', 9);


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

add_action('geodir_wrapper_open', 'sup_add_feat_img_head', 4, 1);

//remove title from listing detail page
remove_action('geodir_details_main_content', 'geodir_action_page_title', 20);
//remove slider from listing detail page
remove_action('geodir_details_main_content', 'geodir_action_details_slider', 30);



/**
 * Remove details info from sidebar.
 *
 * @since 1.0.0
 * @return array
 */
function my_change_sidebar_content_order($arr)
{

    $arr = array_diff($arr, array('geodir_social_sharing_buttons','geodir_share_this_button','geodir_detail_page_review_rating'));

    return $arr;
}

add_filter('geodir_detail_page_sidebar_content', 'my_change_sidebar_content_order',10,1);

// Remove taxonomies from detail page content
remove_action('geodir_details_main_content', 'geodir_action_details_taxonomies', 40);




/**
 * Output the listings images as a gallery.
 *
 * Used to add the listing images to the sidebar.
 *
 * @since 1.0.0
 */
function sd_img_gallery_output()
{
    $excluded_tabs = get_option('geodir_detail_page_tabs_excluded',true);
    if(is_array($excluded_tabs) && in_array('post_images',$excluded_tabs)){
        global $post, $post_images, $video, $special_offers, $related_listing, $geodir_post_detail_fields;

        $post_id = !empty($post) && isset($post->ID) ? (int)$post->ID : 0;
        $request_post_id = !empty($_REQUEST['p']) ? (int)$_REQUEST['p'] : 0;
        $is_backend_preview = (is_single() && !empty($_REQUEST['post_type']) && !empty($_REQUEST['preview']) && !empty($_REQUEST['p'])) && is_super_admin() ? true : false; // skip if preview from backend

        if ($is_backend_preview && !$post_id > 0 && $request_post_id > 0) {
            $post = geodir_get_post_info($request_post_id);
            setup_postdata($post);
        }

        $geodir_post_detail_fields = geodir_show_listing_info('detail');

        $thumb_image = '';

        if (geodir_is_page('detail')) {

            $post_images = geodir_get_images($post->ID, 'thumbnail');
            if (!empty($post_images)) {
                foreach ($post_images as $image) {
                    $thumb_image .= '<a href="' . esc_url($image->src) . '">';
                    $thumb_image .= geodir_show_image($image, 'thumbnail', true, false);
                    $thumb_image .= '</a>';
                }
            }

        } elseif (geodir_is_page('preview')) {

            if (isset($post->post_images))
                {$post->post_images = trim($post->post_images, ",");}

            if (isset($post->post_images) && !empty($post->post_images))
                {$post_images = explode(",", $post->post_images);}

            if (!empty($post_images)) {
                foreach ($post_images as $image) {
                    if ($image != '') {
                        $thumb_image .= '<a href="' . esc_url($image) . '">';
                        $thumb_image .= geodir_show_image(array('src' => $image), 'thumbnail', true, false);
                        $thumb_image .= '</a>';
                    }
                }
            }

        }

        ?>
        <?php if (geodir_is_page('detail') || geodir_is_page('preview')) { ?>
            <div id="geodir-post-gallery" class="clearfix"><?php echo $thumb_image; ?></div>
        <?php }
    }
}

add_action('geodir_detail_sidebar_inside', 'sd_img_gallery_output', 1);

// add recurring dates to sidebar if events installed
if(function_exists('geodir_event_show_schedule_date')){
    add_action('geodir_detail_sidebar_inside', 'geodir_event_show_schedule_date', '1.5');
}

/**
 * Output the details page map HTML.
 *
 * @since 1.0.0
 */
function sd_map_in_detail_page_sidebar()
{

    $excluded_tabs = get_option('geodir_detail_page_tabs_excluded',true);
    if(is_array($excluded_tabs) && in_array('post_map',$excluded_tabs)){
        global $post, $post_images, $video, $special_offers, $related_listing, $geodir_post_detail_fields;

        $post_id = !empty($post) && isset($post->ID) ? (int)$post->ID : 0;
        $request_post_id = !empty($_REQUEST['p']) ? (int)$_REQUEST['p'] : 0;
        $is_backend_preview = (is_single() && !empty($_REQUEST['post_type']) && !empty($_REQUEST['preview']) && !empty($_REQUEST['p'])) && is_super_admin() ? true : false; // skip if preview from backend

        if ($is_backend_preview && !$post_id > 0 && $request_post_id > 0) {
            $post = geodir_get_post_info($request_post_id);
            setup_postdata($post);
        }

        if(!isset($post->post_latitude) || $post->post_latitude==''){
            return '';// if not address, bail.
        }
        $geodir_post_detail_fields = geodir_show_listing_info('detail');

        if (geodir_is_page('detail')) {

            $map_args = array();
            $map_args['map_canvas_name'] = 'detail_page_map_canvas';
            $map_args['width'] = '300';
            $map_args['height'] = '400';
            if ($post->post_mapzoom) {
                $map_args['zoom'] = '' . $post->post_mapzoom . '';
            }
            $map_args['autozoom'] = false;
            $map_args['child_collapse'] = '0';
            $map_args['enable_cat_filters'] = false;
            $map_args['enable_text_search'] = false;
            $map_args['enable_post_type_filters'] = false;
            $map_args['enable_location_filters'] = false;
            $map_args['enable_jason_on_load'] = true;
            $map_args['enable_map_direction'] = true;
            $map_args['map_class_name'] = 'geodir-map-detail-page';

        } elseif (geodir_is_page('preview')) {

            global $map_jason;
            $map_jason[] = $post->marker_json;

            $address_latitude = isset($post->post_latitude) ? $post->post_latitude : '';
            $address_longitude = isset($post->post_longitude) ? $post->post_longitude : '';
            $mapview = isset($post->post_mapview) ? $post->post_mapview : '';
            $mapzoom = isset($post->post_mapzoom) ? $post->post_mapzoom : '';
            if (!$mapzoom) {
                $mapzoom = 12;
            }

            $map_args = array();
            $map_args['map_canvas_name'] = 'preview_map_canvas';
            $map_args['width'] = '300';
            $map_args['height'] = '400';
            $map_args['child_collapse'] = '0';
            $map_args['maptype'] = $mapview;
            $map_args['autozoom'] = false;
            $map_args['zoom'] = "$mapzoom";
            $map_args['latitude'] = $address_latitude;
            $map_args['longitude'] = $address_longitude;
            $map_args['enable_cat_filters'] = false;
            $map_args['enable_text_search'] = false;
            $map_args['enable_post_type_filters'] = false;
            $map_args['enable_location_filters'] = false;
            $map_args['enable_jason_on_load'] = true;
            $map_args['enable_map_direction'] = true;
            $map_args['map_class_name'] = 'geodir-map-preview-page';

        }
        if (geodir_is_page('detail') || geodir_is_page('preview')) { ?>
            <div class="sd-map-in-sidebar-detail"><?php geodir_draw_map($map_args); ?>

            </div>
        <?php }
    }
}

add_action('geodir_detail_sidebar_inside', 'sd_map_in_detail_page_sidebar', 2);


/**
 * Fire the signup functions from GeoDirectory so the SD login form works.
 *
 * @since 1.0.0
 */
function sd_header_login_handler()
{
    if (!geodir_is_page('login') && isset($_REQUEST['log'])) {
        geodir_user_signup();
    }
}

add_action('init', 'sd_header_login_handler');

// add paging html to top of listings
function sd_before_listing_pagination()
{
    if (sd_is_non_location_cpt()) {
        return;
    }
    add_action('geodir_before_listing', 'geodir_pagination', 100);
}

add_action('wp', 'sd_before_listing_pagination');

/**
 * Add fav html to listing page image.
 *
 * @since 1.0.0
 *
*@param object $post The post object.
 */
function sd_listing_img_fav($post)
{
    if (isset($post->ID)) {
        geodir_favourite_html($post->post_author, $post->ID);
    }
}

add_action('geodir_after_badge_on_image', 'sd_listing_img_fav', 10, 1);


// remove pinpoint and normal fav html from listings
remove_action('geodir_after_favorite_html', 'geodir_output_favourite_html_listings', 1);
remove_action('geodir_listing_after_pinpoint', 'geodir_output_pinpoint_html_listings', 1);


// hide toolbar in frontend
// add_filter('show_admin_bar', '__return_false'); // not allowed if submitting to wp.org

// remove core term description from listins pages

if (!defined('GEODIRLOCATION_VERSION')) {
	remove_action('geodir_listings_page_description', 'geodir_action_listings_description', 10);
	add_action('geodir_listings_content', 'geodir_action_listings_description', 2);

}else{
    remove_action('geodir_listings_page_description', 'geodir_action_listings_description', 10);
	remove_action('wp_print_scripts', 'geodir_location_remove_action_listings_description', 100);
}

// CPT description
if (defined('GEODIR_CP_TEXTDOMAIN')) {
remove_action('geodir_listings_page_description', 'geodir_cpt_pt_desc', 10);
add_action('geodir_listings_content', 'geodir_cpt_pt_desc', 2);
}

if (defined('GEODIRLOCATION_VERSION')) {
    // remove location manager term description from listings pages
    remove_action('wp_print_scripts', 'geodir_location_remove_action_listings_description', 100);
    add_action('geodir_listings_content', 'geodir_location_action_listings_description', 2);
}

// remove claim link from sidebar as we have it in top bar
remove_action('geodir_after_edit_post_link', 'geodir_display_post_claim_link', 2);



/*
 * Move listings page title into the main wrapper content.
 */
// move page titles
remove_action('geodir_listings_page_title', 'geodir_action_listings_title', 10);
add_action('geodir_listings_content', 'geodir_action_listings_title', 1);
// search page tile
remove_action('geodir_search_page_title', 'geodir_action_search_page_title', 10);
add_action('geodir_search_content', 'geodir_action_search_page_title', 1);
// author page tile
remove_action('geodir_author_page_title', 'geodir_action_author_page_title', 10);
add_action('geodir_author_content', 'geodir_action_author_page_title', 1);


function sd_theme_deactivation($newname, $newtheme) {
    // undo set the details page to use list and not tabs
    update_option('geodir_disable_tabs', '0');
    // undo disable some details page tabs that we show in the sidebar
    update_option('geodir_detail_page_tabs_excluded', array());
    // undo Set the installed flag
    update_option('sd-installed', false);

}
add_action("switch_theme", "sd_theme_deactivation", 10 , 2);



//remove send to enquiry from details page
add_filter("geodir_show_geodir_email", '__return_false');

function sd_detail_display_notices() {
    if (geodir_is_page('detail')) {
        if (isset($_GET['geodir_claim_request']) && $_GET['geodir_claim_request'] == 'success') {
            ?>
            <div class="alert alert-success" style="text-align: center">
                <?php echo CLAIM_LISTING_SUCCESS; ?>
            </div>
            <?php
        }

        if (isset($_GET['send_inquiry']) && $_GET['send_inquiry'] == 'success') {
            ?>
            <div class="alert alert-success" style="text-align: center">
                <?php echo SEND_INQUIRY_SUCCESS; ?>
            </div>
            <?php
        }
    }
}
add_action('sd-detail-details-before', 'sd_detail_display_notices');

/**
 * Output the header featured area image HTML.
 *
 * Add featured banner and listing details above wrapper.
 *
 * @since 1.0.0
 *
*@param string $page The GeoDirectory page being called.
 */
function sup_add_feat_img_head($page)
{
    if ($page == 'details-page') {

        global $preview, $post;
        $default_img_url = SD_DEFAULT_FEATURED_IMAGE;
        $full_image_url = '';
        if ($preview) {
            geodir_action_geodir_set_preview_post();//Set the $post value if previewing a post.
            $post_images = array();
            if (isset($post->post_images) && !empty($post->post_images)) {
                $post->post_images = trim($post->post_images, ",");
                $post_images = explode(",", $post->post_images);
            }
            $full_image_url = (isset($post_images[0])) ? $post_images[0] : $default_img_url;
        } else {
            if (has_post_thumbnail()) {
                $full_image_urls = wp_get_attachment_image_src(get_post_thumbnail_id(), 'full');
                $full_image_url = $full_image_urls[0];
            } else {
                if (isset($post->default_category) && $post->default_category) {
                    $default_cat = $post->default_category;
                } else {
                    $default_cat = geodir_get_post_meta($post->ID, 'default_category', true);
                }

                if ($default_catimg = geodir_get_default_catimage($default_cat, $post->post_type)) {
                    $full_image_url = $default_catimg['src'];
                }

                if (empty($full_image_url)) {
                    $full_image_url = $default_img_url;
                }

            }
        }
        ?>
        <div class="featured-area">

            <div class="featured-img" style="background-image: url('<?php echo esc_url($full_image_url); ?>');"></div>

            <?php if ($preview) {
                echo geodir_action_geodir_preview_code();
            }else{
            do_action('sd_details_featured_area_text');
            }


             ?>
        </div>
        <?php
        $user_id = get_current_user_id();
        $post_avgratings = geodir_get_post_rating($post->ID);
        $post_ratings = geodir_get_rating_stars($post_avgratings, $post->ID);
        ob_start();
        if (!$preview) {
            geodir_comments_number($post->rating_count);
        } else {

        }
        $n_comments = ob_get_clean();
        if (!$preview) {
            $author_id = $post->post_author;
            $author_name = get_the_author_meta('display_name', $author_id);
            $entry_author = get_avatar(get_the_author_meta('email', $author_id), 100);
            $author_link = get_author_posts_url($author_id);
            $post_type = $post->post_type;
            $post_tax = $post_type . "category";
            $post_cats = $post->{$post_tax};
        } else {
            $author_name = get_the_author_meta('display_name', $user_id);
            $entry_author = get_avatar(get_the_author_meta('email', $user_id), 100);
            $author_link = get_author_posts_url($user_id);
            $post_type = $post->listing_type;
            $post_tax = $post_type . "category";
            $post_cats = isset($post->post_category) ? $post->post_category[$post_tax] : $post->{$post_tax};
        }

        $package_info = (array)geodir_post_package_info( array(), $post, $post_type );
        $package_fields = geodir_post_custom_fields( (!empty($package_info['pid']) ? $package_info['pid'] : ''), 'all', $post_type );
        $available_fields = array();
        if ( ! empty( $package_fields ) ) {
            foreach ( $package_fields as $package_field ) {
                if ( ! empty( $package_field['name'] ) ) {
                    $available_fields[] = $package_field['name'];
                }
            }
        }

        $postlink = get_permalink(geodir_add_listing_page_id());
        $editlink = geodir_getlink($postlink, array('pid' => $post->ID), false);

        $extra_class = apply_filters('sd_detail_details_extra_class', "");

        if (is_array($post_cats)) {
            $post_cats = implode(',', $post_cats);
        }

        $cats_arr = array_filter(explode(",", $post_cats));
		if (!empty($cats_arr)) {
			$cats_arr = array_unique($cats_arr);
		}
        $cat_icons = geodir_get_term_icon();
        
        $post_id = $post->ID;
        
        // WPML
        $duplicate_of = geodir_wpml_is_post_type_translated($post_type) ? get_post_meta((int)$post_id, '_icl_lang_duplicate_of', true) : NULL;
        // WPML
        ?>
        <?php do_action('sd-detail-details-before'); ?>
        <div class="sd-detail-details  <?php echo $extra_class; ?>">
        <div class="container">
            <div class="sd-detail-author">
                <?php
                $is_owned = false;
                if (!$preview && function_exists('geodir_load_translation_geodirclaim')) {
                    $geodir_post_type = get_option('geodir_post_types_claim_listing', array());
                    
                    if (in_array($post_type, $geodir_post_type)) {
                        $is_owned = !$duplicate_of ? (int)geodir_get_post_meta($post_id, 'claimed', true) : (int)geodir_get_post_meta($duplicate_of, 'claimed', true);
                        
                        if ($is_owned) {
                            ?>
                            <span class="fa-stack sd-verified-badge" title="<?php _e('Verified Owner', 'supreme-directory'); ?>">
                                <i class="fas fa-circle fa-inverse"></i>
                                <i class="fas fa-check-circle"></i>
                            </span>
                            <?php
                        } else {
                            $author_link = '#';
                            $author_name = __('Claim Me', 'supreme-directory');
                            $entry_author = '<img src="'.get_stylesheet_directory_uri() . "/images/gravatar2.png".'"  height="100" width="100">';
                        }
                    }
                }
                
                $author_name = apply_filters('sd_detail_author_name', $author_name);
                $entry_author = apply_filters('sd_detail_entry_author', $entry_author);
                $author_link = apply_filters('sd_detail_author_link', $author_link);

                printf('<div class="author-avatar"><a href="%s">%s</a></div>', esc_url($author_link), $entry_author);

                if (!defined('GEODIRCLAIM_VERSION') || $is_owned == '1') {
                    printf('<div class="author-link"><span class="vcard author author_name"><span class="fn"><a href="%s">%s</a></span></span></div>', esc_url($author_link), esc_attr($author_name));
                    do_action('sd_detail_author_extra', $post, $author_link, $author_name);
                } else {
                    do_action('sd_detail_default_author', $post, $author_link, $author_name);
                }

                if (is_user_logged_in() && geodir_listing_belong_to_current_user()) {
                global $preview;
                if( $preview ){
                $editlink = '#';
                }
                    ?>
                    <a href="<?php echo esc_url($editlink); ?>" class="supreme-btn supreme-btn-small supreme-edit-btn"><i
                            class="fas fa-edit"></i> <?php echo __('Edit', 'supreme-directory'); ?></a>
                <?php }

                if (function_exists('geodir_load_translation_geodirclaim')) {
                    $geodir_post_type = array();
                    if (get_option('geodir_post_types_claim_listing')) {
                        $geodir_post_type = get_option('geodir_post_types_claim_listing');
                    }
    
                    $posttype = (isset($post->post_type)) ? $post->post_type : '';
                    
                    if (in_array($posttype, $geodir_post_type) && !$preview) {
                        $is_owned = !$duplicate_of ? (int)geodir_get_post_meta($post_id, 'claimed', true) : (int)geodir_get_post_meta($duplicate_of, 'claimed', true);
        
                        if (get_option('geodir_claim_enable') == 'yes' && !$is_owned ) {
                            if ($duplicate_of) {
                                $current_url = get_permalink($duplicate_of);
                                $current_url = add_query_arg(array('gd_go' => 'claim'), $current_url);
                                
                                if (!is_user_logged_in()) {
                                    $current_url = geodir_login_url(array('redirect_to' => urlencode_deep($current_url)));
                                    $current_url = apply_filters('geodir_claim_login_to_claim_url', $current_url, $duplicate_of);
                                }
                                    
                                echo '<a href="' . esc_url($current_url) . '" class="supreme-btn supreme-btn-small supreme-edit-btn"><i class="fas fa-question-circle"></i> ' . __('Claim', 'supreme-directory') . '</a>';
                            } else {
                                if (is_user_logged_in()) {
                                    echo '<div class="geodir-company_info">';
                                    echo '<div class="geodir_display_claim_popup_forms"></div>';
                                    echo '<a href="javascript:void(0);" class="supreme-btn supreme-btn-small supreme-edit-btn geodir_claim_enable"><i class="fas fa-question-circle"></i> ' . __('Claim', 'supreme-directory') . '</a>';
                                    echo '</div>';
                                    echo '<input type="hidden" name="geodir_claim_popup_post_id" value="' . $post->ID . '" />';
                                    if (!empty($_REQUEST['gd_go']) && $_REQUEST['gd_go'] == 'claim' && !isset($_REQUEST['geodir_claim_request'])) {
                                        echo '<script type="text/javascript">jQuery(function(){jQuery(".supreme-btn.geodir_claim_enable").trigger("click");});</script>';
                                    }
                                } else {
                                    $current_url = remove_query_arg(array('gd_go'), geodir_curPageURL());
                                    $current_url = add_query_arg(array('gd_go' => 'claim'), $current_url);
                                    $login_to_claim_url = geodir_login_url(array('redirect_to' => urlencode_deep($current_url)));
                                    $login_to_claim_url = apply_filters('geodir_claim_login_to_claim_url', $login_to_claim_url, $post->ID);
                                    
                                    echo '<a href="' . esc_url($login_to_claim_url) . '" class="supreme-btn supreme-btn-small supreme-edit-btn"><i class="fas fa-question-circle"></i> ' . __('Claim', 'supreme-directory') . '</a>';

                                }
                            }
                        }
                    }
                }
                ?>
            </div>
            <!-- sd-detail-author end -->
            <div class="sd-detail-info">
                <?php
                $title_extra_class = apply_filters('sd_detail_title_extra_class', "");
                echo '<h1 class="sd-entry-title '.$title_extra_class.'">' .  stripslashes(get_the_title());
                ?>
                <?php
                echo '</h1>';
                $sd_address = '<div class="sd-address">';
                if (isset($post->post_city) && $post->post_city) {
                    $sd_address .= apply_filters('sd_detail_city_name', stripslashes($post->post_city), $post);
                }
                if (isset($post->post_region) && $post->post_region) {
                    $sd_address .= ', ' . apply_filters('sd_detail_region_name', stripslashes($post->post_region), $post);
                }
                if (isset($post->post_country) && $post->post_country) {
                    $sd_address .= ', ' . apply_filters('sd_detail_country_name', __($post->post_country, 'geodirectory'), $post);
                }
                $sd_address .= '</div>';

                echo apply_filters('sd_details_output_address',$sd_address);

                $sd_raitings = '<div class="sd-ratings">' . $post_ratings . ' <a href="' . get_comments_link() . '" class="geodir-pcomments">' . $n_comments . '</a></div>';
                echo apply_filters('sd_details_output_ratings',$sd_raitings);
                $sd_social = '<div class="sd-contacts">';
                if (isset($post->geodir_website) && $post->geodir_website && !empty($available_fields) && in_array('geodir_website', $available_fields)) {
                    $sd_social .= '<a rel="nofollow" target="_blank" href="' . esc_url($post->geodir_website) . '"><i class="fas fa-external-link-square-alt"></i></a>';
                }
                if (isset($post->geodir_facebook) && $post->geodir_facebook && !empty($available_fields) && in_array('geodir_facebook', $available_fields)) {
                   $sd_social .='<a rel="nofollow" target="_blank" href="' . esc_url($post->geodir_facebook) . '"><i class="fab fa-facebook"></i></a>';
                }
                if (isset($post->geodir_twitter) && $post->geodir_twitter && !empty($available_fields) && in_array('geodir_twitter', $available_fields)) {
                    $sd_social .='<a rel="nofollow" target="_blank" href="' . esc_url($post->geodir_twitter) . '"><i class="fab fa-twitter-square"></i></a>';
                }
                if (isset($post->geodir_contact) && $post->geodir_contact && !empty($available_fields) && in_array('geodir_contact', $available_fields)) {
                    $sd_social .='<a href="tel:' . esc_attr($post->geodir_contact) . '"><i class="fas fa-phone-square"></i>&nbsp;:&nbsp;' . esc_attr($post->geodir_contact) . '</a>';
                }
                $sd_social .= '</div>';

                echo apply_filters('sd_details_output_social',$sd_social);

                do_action('sd_detail_before_cat_links');

                $cat_links = '<div class="sd-detail-cat-links"><ul>';
                foreach ($cats_arr as $cat) {
                    $term_arr = get_term($cat, $post_tax);
                    $term_icon = isset($cat_icons[$cat]) ? $cat_icons[$cat] : '';
                    $term_url = get_term_link(intval($cat), $post_tax);
                    $cat_links .=  '<li><a href="' . esc_url($term_url) . '"><img src="' . esc_url($term_icon) . '">';
                    $cat_links .= '<span class="cat-link">' . esc_attr($term_arr->name) . '</span>';
                    $cat_links .= '</a></li>';
                }
                $cat_links .= '</ul></div> <!-- sd-detail-cat-links end --> </div> <!-- sd-detail-info end -->';
                echo apply_filters('sd_details_output_cat_links',$cat_links);

                echo '<div class="sd-detail-cta">';
                $review_button = '<a class="dt-btn" href="' . get_the_permalink() . '#reviews">' . __('Write a Review', 'supreme-directory') . '</a>';
                echo apply_filters('sd_details_output_review_button',$review_button);

                $send_buttons = '<div class="geodir_more_info geodir-company_info geodir_email" style="padding: 0;border: none">';

                if (!$preview) {
                    $html = '<input type="hidden" name="geodir_popup_post_id" value="' . $post->ID . '" />
                    <div class="geodir_display_popup_forms"></div>';
	                $send_buttons .= $html;
                }

                $share_actions = array();
                if ( ! empty( $post->geodir_email ) && ! empty( $available_fields ) && in_array( 'geodir_email', $available_fields ) ) {
                    $share_actions[] = '<a href="javascript:void(0);" class="b_send_inquiry">' . SEND_INQUIRY . '</a>';
                }
                if ( ! empty( $share_actions ) ) {
                    $send_buttons .= '<span style="" class="geodir-i-email">';
                    $send_buttons .= '<i class="fas fa-envelope"></i> ';
                    $send_buttons .= implode( ' | ', $share_actions );
                    $send_buttons .= '</span>';
                }
                $send_buttons .= '</div>';

                echo apply_filters('sd_details_output_send_buttons',$send_buttons);

				ob_start();
                geodir_favourite_html($post->post_author, $post->ID);
                $fav_html = ob_get_clean();
                echo apply_filters('sd_details_output_fav',$fav_html);

				ob_start();
                ?>
                <ul class="sd-cta-favsandshare">
                    <?php if (!$preview) { ?>
                        <li><a rel="nofollow" target="_blank" title="<?php echo __('Share on Facebook', 'supreme-directory'); ?>"
                               href="http://www.facebook.com/sharer.php?u=<?php the_permalink(); ?>&t=<?php urlencode(the_title()); ?>"><i
                                    class="fab fa-facebook"></i></a></li>
                        <li><a rel="nofollow" target="_blank" title="<?php echo __('Share on Twitter', 'supreme-directory'); ?>"
                               href="http://twitter.com/share?text=<?php echo urlencode(html_entity_decode(get_the_title(), ENT_COMPAT, 'UTF-8')); ?>&url=<?php echo urlencode(get_the_permalink()); ?>"><i
                                    class="fab fa-twitter"></i></a></li>
                        <li><a rel="nofollow" target="_blank" title="<?php echo __('Share on Google Plus', 'supreme-directory'); ?>"
                               href="https://plus.google.com/share?url=<?php echo urlencode(get_the_permalink()); ?>"><i
                                    class="fab fa-google-plus-g"></i></a></li>
                    <?php } else { ?>
                        <li><a rel="nofollow" target="_blank" title="<?php echo __('Share on Facebook', 'supreme-directory'); ?>"
                               href=""><i class="fab fa-facebook"></i></a></li>
                        <li><a rel="nofollow" target="_blank" title="<?php echo __('Share on Twitter', 'supreme-directory'); ?>"
                               href=""><i class="fab fa-twitter"></i></a></li>
                        <li><a rel="nofollow" target="_blank" title="<?php echo __('Share on Google Plus', 'supreme-directory'); ?>"
                               href=""><i class="fab fa-google-plus-g"></i></a></li>
                    <?php } ?>
                </ul>
                <?php

                $share_html = ob_get_clean();
                echo apply_filters('sd_details_output_share_links',$share_html);
                echo '</div><!-- sd-detail-cta end -->'; ?>
                <?php do_action('sd-detail-details-container-inner-after'); ?>
            </div>
            <!-- container end -->
            <?php do_action('sd-detail-details-container-after'); ?>
        </div><!-- sd-detail-details end -->
        <?php do_action('sd-detail-details-after'); ?>


    <?php } elseif ($page == 'home-page') {

        if (function_exists('geodir_get_location_seo')) {
            $seo = geodir_get_location_seo();
            if (isset($seo->seo_image_tagline) && $seo->seo_image_tagline) {
                $sub_title = __($seo->seo_image_tagline, 'geodirlocation');
            }
            if (isset($seo->seo_image) && $seo->seo_image) {
                $full_image_url = wp_get_attachment_image_src($seo->seo_image, 'full');
            }
        }

        if (isset($full_image_url)) {

        } elseif (has_post_thumbnail()) {
            $full_image_url = wp_get_attachment_image_src(get_post_thumbnail_id(), 'full');
        } else {
            $full_image_url[0] = SD_DEFAULT_FEATURED_IMAGE;
        }

        if (!isset($sub_title) && get_post_meta(get_the_ID(), 'subtitle', true)) {
            $sub_title = get_post_meta(get_the_ID(), 'subtitle', true);
        }


        $full_image_url = apply_filters('sd_featured_image_url', esc_url($full_image_url[0]));

        ?>
        <div class="featured-area">
            <div class="featured-img" style="background-image: url('<?php echo $full_image_url; ?>');">
            </div>
            <div class="header-wrap">
            <?php do_action('sd_homepage_content');?>

            </div>
        </div>
    <?php
    }

}

function sd_gd_current_location_name(){

	/*
	 * If location manager not installed then display the default location.
	 */
	if (!function_exists('geodir_current_loc_shortcode')) {
	    global $gd_session;
	    $output = geodir_get_default_location();

	    $output = $output->city;

	    if (($gd_session->get('my_location') || ($gd_session->get('user_lat') && $gd_session->get('user_lon')))) {
	        $output = __('Near Me', 'supreme-directory');
	    }

	}else{
		$output = do_shortcode('[gd_current_location_name]');
	}

	return $output;
}

function sd_homepage_featured_content() {
    if (is_singular() && geodir_is_page('location') && $location = sd_gd_current_location_name() ) { ?>
        <h1 class="entry-title"><?php echo esc_attr(__($location, 'geodirectory')); ?></h1>
    <?php } else { ?>
        <h1 class="entry-title"><?php the_title(); ?></h1>
    <?php }

    $sub_title = get_post_meta(get_the_ID(), 'subtitle', true);

    if (geodir_is_page('location') && defined('GEODIRLOCATION_VERSION')) {
        $loc = geodir_get_current_location_terms();
        $location_type = geodir_what_is_current_location();
        $country_slug = '';
        $region_slug = '';
        if ($location_type == 'city') {
            $slug = $loc['gd_city'];
            $region_slug = isset($loc['gd_region']) ? $loc['gd_region'] : '';
            $country_slug = isset($loc['gd_country']) ? $loc['gd_country'] : '';
        } else if ($location_type == 'region') {
            $slug = $loc['gd_region'];
            $country_slug = isset($loc['gd_country']) ? $loc['gd_country'] : '';
        } elseif($location_type == 'country') {
            $slug = $loc['gd_country'];
            $country_slug = isset($loc['gd_country']) ? $loc['gd_country'] : '';
        }
        else {
            $slug = '';
        }
        $seo = geodir_location_seo_by_slug($slug, $location_type, $country_slug, $region_slug);
        $tagline = (isset($seo->seo_image_tagline)) ? __($seo->seo_image_tagline, 'geodirlocation') : '';
        if ($tagline) {
            $sub_title = stripslashes($tagline);
        }

    }
    if (isset($sub_title)) {
        echo '<div class="entry-subtitle">' . $sub_title . '</div>';
    }

    sd_search_form_shortcode();
    echo do_shortcode('[gd_popular_post_category category_limit=5 category_restrict=1]');
    
    echo '<div class="home-more" id="sd-home-scroll"><a href="#sd-home-scroll" ><i class="fas fa-chevron-down"></i></a></div>';
}
add_action('sd_homepage_content','sd_homepage_featured_content');

function sd_add_gd_home_class($classes) {
    if (geodir_is_page('home') || geodir_is_page('location')) {
        $classes[] = 'sd-homepage';
    }
    return $classes;
}
add_filter( 'body_class', 'sd_add_gd_home_class' );


/**
 * This function removes date section added by event manager in sidebar.
 *
 * @since 1.0.3
 */
function sd_geodir_event_date_remove($template) {

    if(geodir_get_current_posttype() == 'gd_event' && defined('GDEVENTS_VERSION')){

        remove_filter('geodir_detail_page_sidebar_content', 'geodir_event_detail_page_sitebar_content', 2);

    }

    return $template;
}
add_filter( 'template_include', 'sd_geodir_event_date_remove',0);


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

    if (is_front_page() && !geodir_is_page('home')) {
        sd_search_form_shortcode();
        echo do_shortcode('[gd_popular_post_category category_limit=5]');
        echo '<div class="home-more"  id="sd-home-scroll" ><a href="#sd-home-scroll"><i class="fas fa-chevron-down"></i></a></div>';
    }
}
add_action('sd_feature_area','sd_feature_area_gd',15);

// remove original featured area
function sd_remove_sd_feature_area(){
remove_action('sd_feature_area','sd_feature_area',15);
}
add_action('get_template_part_content','sd_remove_sd_feature_area');

/**
 * Change the author page content if GeoDirectory Installed
 *
 * @since 1.0.82
 * @param Object $author The author object.
 */
function sd_gd_author_content_output($author){

	// user listings
    echo "<h3>".__("Listings", "supreme-directory")."</h3>";
    geodir_user_show_listings($author->ID,'link');

	// user favs
	$fav_count = geodir_user_favourite_listing_count($author->ID);
	if(!empty($fav_count )){
	    echo "<h3>".__("Favorites", "supreme-directory")."</h3>";
	    geodir_user_show_favourites($author->ID,'link');
	}

}


add_action('sd_author_content','sd_gd_author_content_output',10,1);

/**
 * Fires after theme setup to be able to remove actions set prior.
 *
 * @since 1.0.82
 */
function sd_gd_remove_theme_functions(){
	remove_action('sd_author_content','sd_author_content_output',10);
}
add_action('after_setup_theme','sd_gd_remove_theme_functions');

add_action('geodir_before_detail_page_more_info','sd_tags_content');
function sd_tags_content()
{
    global $preview, $post;?>
    <?php
    $taxonomies = array();

    $is_backend_preview = (is_single() && !empty($_REQUEST['post_type']) && !empty($_REQUEST['preview']) && !empty($_REQUEST['p'])) && is_super_admin() ? true : false; // skip if preview from backend

    if ($preview && !$is_backend_preview) {
        $post_type = $post->listing_type;
        $post_taxonomy = $post_type . 'category';
        $post->{$post_taxonomy} = $post->post_category[$post_taxonomy];
    } else {
        $post_type = $post->post_type;
    }

    $post_type_info = get_post_type_object($post_type);
    $listing_label = __($post_type_info->labels->singular_name, 'geodirectory');

    if (!empty($post->post_tags)) {

        if (taxonomy_exists($post_type . '_tags')):
            $links = array();
            $terms = array();
            // to limit post tags
            $post_tags = trim($post->post_tags, ",");
            $post_id = isset($post->ID) ? $post->ID : '';


            $post_tags = apply_filters('geodir_action_details_post_tags', $post_tags, $post_id);

            $post->post_tags = $post_tags;
            $post_tags = explode(",", trim($post->post_tags, ","));


            foreach ($post_tags as $post_term) {

                // fix slug creation order for tags & location
                $post_term = trim($post_term);

                $priority_location = false;
                if ($insert_term = term_exists($post_term, $post_type . '_tags')) {
                    $term = get_term_by('id', $insert_term['term_id'], $post_type . '_tags');
                } else {
                    $post_country = isset($_REQUEST['post_country']) && $_REQUEST['post_country'] != '' ? sanitize_text_field($_REQUEST['post_country']) : NULL;
                    $post_region = isset($_REQUEST['post_region']) && $_REQUEST['post_region'] != '' ? sanitize_text_field($_REQUEST['post_region']) : NULL;
                    $post_city = isset($_REQUEST['post_city']) && $_REQUEST['post_city'] != '' ? sanitize_text_field($_REQUEST['post_city']) : NULL;
                    $match_country = $post_country && sanitize_title($post_term) == sanitize_title($post_country) ? true : false;
                    if ($post_country && !$match_country) {
                        $match_country = sanitize_title($post_term) == sanitize_title(__($post_country, 'geodirectory')) ? true : false;
                    }
                    $match_region = $post_region && sanitize_title($post_term) == sanitize_title($post_region) ? true : false;
                    $match_city = $post_city && sanitize_title($post_term) == sanitize_title($post_city) ? true : false;
                    if ($match_country || $match_region || $match_city) {
                        $priority_location = true;
                        $term = get_term_by('name', $post_term, $post_type . '_tags');
                    } else {
                        $insert_term = wp_insert_term($post_term, $post_type . '_tags');
                        $term = get_term_by('name', $post_term, $post_type . '_tags');
                    }
                }

                if (!is_wp_error($term) && is_object($term)) {

                    // fix tag link on detail page
                    if ($priority_location) {

                        $tag_link = "<a href=''>$post_term</a>";

                        $tag_link = apply_filters('geodir_details_taxonomies_tag_link',$tag_link,$term);
                        $links[] = $tag_link;
                    } else {
                        $tag_link = "<a href='" . esc_attr(get_term_link($term->term_id, $term->taxonomy)) . "'>$term->name</a>";
                        /** This action is documented in geodirectory-template_actions.php */
                        $tag_link = apply_filters('geodir_details_taxonomies_tag_link',$tag_link,$term);
                        $links[] = $tag_link;
                    }
                    $terms[] = $term;
                }
                //
            }
            if (!isset($listing_label)) {
                $listing_label = '';
            }
            $taxonomies[$post_type . '_tags'] = wp_sprintf(__('%s Tags: %l', 'geodirectory'), geodir_ucwords($listing_label), $links, (object)$terms);
        endif;

    }

    $taxonomies = apply_filters('geodir_details_taxonomies_output',$taxonomies,$post_type,$listing_label,geodir_ucwords($listing_label));


    if (isset($taxonomies[$post_type . '_tags'])) {
        echo '<div class="geodir_more_info">';
        echo '<span class="">' . $taxonomies[$post_type . '_tags'] . '</span>';
        echo '</div>';
    }
    ?>
    <?php
}

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

function sd_event_get_schedules( $post, $hide_past_dates = true, $limit = 1 ) {
    global $geodir_date_time_format, $geodir_date_format, $geodir_time_format;

    if ( geodir_is_page( 'preview' ) ) {
        $recuring_data = (array)$post;
        $input_format = geodir_event_field_date_format();

        if (isset($recuring_data['event_start']) && $recuring_data['event_start']) {
            $recuring_data['event_start'] = geodir_date($recuring_data['event_start'], 'Y-m-d', $input_format);
        }

        if (isset($recuring_data['event_end']) && $recuring_data['event_end']) {
            $recuring_data['event_end'] = geodir_date($recuring_data['event_end'], 'Y-m-d', $input_format);
        }

        if (isset($recuring_data['repeat_end']) && $recuring_data['repeat_end']) {
            $recuring_data['repeat_end'] = geodir_date($recuring_data['repeat_end'], 'Y-m-d', $input_format);
        }
    } else {
        $recuring_data = !empty( $post->recurring_dates ) ? maybe_unserialize( $post->recurring_dates ) : NULL;
    }

    $schedules = array();

    if ( !empty( $recuring_data ) && ( isset( $recuring_data['event_recurring_dates'] ) && $recuring_data['event_recurring_dates'] != '' ) || ( isset( $post->is_recurring ) && !empty( $post->is_recurring ) ) ) {
        $starttimes = '';
        $endtimes = '';
        $astarttimes = array();
        $aendtimes = array();

        // Check recurring enabled
        $recurring_pkg = geodir_event_recurring_pkg( $post );

        if ( $post->is_recurring && $recurring_pkg ) {
            if ( !isset( $recuring_data['repeat_type'] ) ) {
                $recuring_data['repeat_type'] = 'custom';
            }

            $repeat_type = isset( $recuring_data['repeat_type'] ) && in_array( $recuring_data['repeat_type'], array( 'day', 'week', 'month', 'year', 'custom' ) ) ? $recuring_data['repeat_type'] : 'year'; // day, week, month, year, custom

            $different_times = isset( $recuring_data['different_times'] ) && !empty( $recuring_data['different_times'] ) ? true : false;

            if ( geodir_is_page( 'preview' ) ) {
                $start_date = geodir_event_is_date( $recuring_data['event_start'] ) ? $recuring_data['event_start'] : date_i18n( 'Y-m-d', current_time( 'timestamp' ) );
                $end_date = isset( $recuring_data['event_end'] ) ? trim( $recuring_data['event_end'] ) : '';
                $all_day = isset( $recuring_data['all_day'] ) && !empty( $recuring_data['all_day'] ) ? true : false;
                $starttime = isset( $recuring_data['starttime'] ) && !$all_day ? trim( $recuring_data['starttime'] ) : '';
                $endtime = isset( $recuring_data['endtime'] ) && !$all_day ? trim( $recuring_data['endtime'] ) : '';

                $starttimes = isset( $recuring_data['starttimes'] ) && !$all_day ? $recuring_data['starttimes'] : '';
                $endtimes = isset( $recuring_data['endtimes'] ) && !$all_day ? $recuring_data['endtimes'] : '';

                $repeat_x = isset( $recuring_data['repeat_x'] ) ? trim( $recuring_data['repeat_x'] ) : '';
                $duration_x = isset( $recuring_data['duration_x'] ) ? trim( $recuring_data['duration_x'] ) : 1;
                $repeat_end_type = isset( $recuring_data['repeat_end_type'] ) ? trim( $recuring_data['repeat_end_type'] ) : 0;

                $max_repeat = $repeat_end_type != 1 && isset( $recuring_data['max_repeat'] ) ? (int)$recuring_data['max_repeat'] : 0;
                $repeat_end = $repeat_end_type == 1 && isset( $recuring_data['repeat_end'] ) ? $recuring_data['repeat_end'] : '';

                if ( geodir_event_is_date( $end_date ) && strtotime( $end_date ) < strtotime( $start_date ) ) {
                    $end_date = $start_date;
                }

                $repeat_x = $repeat_x > 0 ? (int)$repeat_x : 1;
                $duration_x = $duration_x > 0 ? (int)$duration_x : 1;
                $max_repeat = $max_repeat > 0 ? (int)$max_repeat : 1;

                if ( $repeat_end_type == 1 && !geodir_event_is_date( $repeat_end ) ) {
                    $repeat_end = '';
                }

                if ( $repeat_type == 'custom' ) {
                    $event_recurring_dates = explode( ',', $recuring_data['event_recurring_dates'] );
                } else {
                    // week days
                    $repeat_days = array();
                    if ( $repeat_type == 'week' || $repeat_type == 'month' ) {
                        $repeat_days = isset( $recuring_data['repeat_days'] ) ? $recuring_data['repeat_days'] : $repeat_days;
                    }

                    // by week
                    $repeat_weeks = array();
                    if ( $repeat_type == 'month' ) {
                        $repeat_weeks = isset( $recuring_data['repeat_weeks'] ) ? $recuring_data['repeat_weeks'] : $repeat_weeks;
                    }

                    $event_recurring_dates = geodir_event_date_occurrences( $repeat_type, $start_date, $end_date, $repeat_x, $max_repeat, $repeat_end, $repeat_days, $repeat_weeks );
                }
            } else {
                $event_recurring_dates = explode( ',', $recuring_data['event_recurring_dates'] );
            }

            if ( empty( $recuring_data['all_day'] ) ) {
                if ( $repeat_type == 'custom' && $different_times ) {
                    $astarttimes = isset( $recuring_data['starttimes'] ) ? $recuring_data['starttimes'] : array();
                    $aendtimes = isset( $recuring_data['endtimes'] ) ? $recuring_data['endtimes'] : array();
                } else {
                    $starttimes = isset( $recuring_data['starttime'] ) ? $recuring_data['starttime'] : '';
                    $endtimes = isset( $recuring_data['endtime'] ) ? $recuring_data['endtime'] : '';
                }
            }

            if ( ! empty( $_REQUEST['gde'] )) {
                if ( in_array( $_REQUEST['gde'], $event_recurring_dates ) ){
                    $event_recurring_dates = array( esc_html( $_REQUEST['gde'] ) );
                }
            }

            foreach( $event_recurring_dates as $key => $date ) {
                $schedule = array();

                if ( $repeat_type == 'custom' && $different_times ) {
                    if ( !empty( $astarttimes ) && isset( $astarttimes[$key] ) ) {
                        $starttimes = $astarttimes[$key];
                        $endtimes = $aendtimes[$key];
                    } else {
                        $starttimes = '';
                        $endtimes = '';
                    }
                }

                $duration = isset( $recuring_data['duration_x'] ) && (int)$recuring_data['duration_x'] > 0 ? (int)$recuring_data['duration_x'] : 1;
                $duration--;
                $enddate = date_i18n( 'Y-m-d', strtotime( $date . ' + ' . $duration . ' day' ) );

                // Hide past dates
                if ( $hide_past_dates && strtotime( $enddate ) < strtotime( date_i18n( 'Y-m-d', current_time( 'timestamp' ) ) ) ) {
                    continue;
                }

                $sdate = strtotime( $date . ' ' . $starttimes );
                $edate = strtotime( $enddate . ' ' . $endtimes );

                $start_date = date_i18n( $geodir_date_time_format, $sdate );
                $end_date = date_i18n( $geodir_date_time_format, $edate );

                $full_day = false;
                $same_datetime = false;

                if ( $starttimes == $endtimes && ( $starttimes == '' || $starttimes == '00:00:00' || $starttimes == '00:00' ) ) {
                    $full_day = true;
                }

                if ( $start_date == $end_date && $full_day ) {
                    $same_datetime = true;
                }

                $title_date = date_i18n( $geodir_date_format, $sdate );
                if ( $full_day ) {
                    $start_date = $title_date;
                    $end_date = date_i18n( $geodir_date_format, $edate );
                }

                $schedule['start'] = $start_date;
                if ( !$same_datetime ) {
                    $schedule['end'] = $end_date;
                }

                $schedules[] = $schedule;
                if ( !empty( $limit ) && count( $schedules ) >= $limit ) {
                    break;
                }
            }
        } else {
            if ( isset( $recuring_data['is_recurring'] ) ) {
                $start_date = isset( $recuring_data['event_start'] ) ? $recuring_data['event_start'] : '';
                $end_date = isset( $recuring_data['event_end'] ) ? $recuring_data['event_end'] : $start_date;
                $all_day = isset( $recuring_data['all_day'] ) && !empty( $recuring_data['all_day'] ) ? true : false;
                $starttime = isset( $recuring_data['starttime'] ) ? $recuring_data['starttime'] : '';
                $endtime = isset( $recuring_data['endtime'] ) ? $recuring_data['endtime'] : '';

                $event_recurring_dates = explode( ',', $recuring_data['event_recurring_dates'] );
                $starttimes = isset( $recuring_data['starttimes'] ) && !empty( $recuring_data['starttimes'] ) ? $recuring_data['starttimes'] : array();
                $endtimes = isset( $recuring_data['endtimes'] ) && !empty( $recuring_data['endtimes'] ) ? $recuring_data['endtimes'] : array();

                if ( ! empty( $_REQUEST['gde'] )) {
                    if ( in_array( $_REQUEST['gde'], $event_recurring_dates ) ){
                        $event_recurring_dates = array( esc_html( $_REQUEST['gde'] ) );
                    }
                }

                if ( !geodir_event_is_date( $start_date ) && !empty( $event_recurring_dates ) ) {
                    $start_date = $event_recurring_dates[0];
                }

                if ( strtotime( $end_date ) < strtotime( $start_date ) ) {
                    $end_date = $start_date;
                }

                if ( $starttime == '' && !empty( $starttimes ) ) {
                    $starttime = $starttimes[0];
                    $endtime = $endtimes[0];
                }

                $one_day = false;
                if ( $start_date == $end_date && $all_day ) {
                    $one_day = true;
                }

                if ( $all_day ) {
                    $start_datetime = strtotime( $start_date );
                    $end_datetime = strtotime( $end_date );

                    $start_date = date_i18n( $geodir_date_format, $start_datetime );
                    $end_date = date_i18n( $geodir_date_format, $end_datetime );
                    if ( $start_date == $end_date ) {
                        $one_day = true;
                    }
                } else {
                    if ( $start_date == $end_date && $starttime == $endtime ) {
                        $end_date = date_i18n( 'Y-m-d', strtotime( $start_date . ' ' . $starttime . ' +1 day' ) );
                        $one_day = false;
                    }
                    $start_datetime = strtotime( $start_date . ' ' . $starttime );
                    $end_datetime = strtotime( $end_date . ' ' . $endtime );

                    $start_date = date_i18n( $geodir_date_time_format, $start_datetime );
                    $end_date = date_i18n( $geodir_date_time_format, $end_datetime );
                }

                $schedule['start'] = $start_date;
                if ( !$one_day ) {
                    $schedule['end'] = $end_date;
                }

                $schedules[] = $schedule;
            } else { // older event dates
                $event_recurring_dates = explode( ',', $recuring_data['event_recurring_dates'] );
                $starttimes = isset( $recuring_data['starttime'] ) ? $recuring_data['starttime'] : '';
                $endtimes = isset( $recuring_data['endtime'] ) ? $recuring_data['endtime'] : '';
                
                if ( ! empty( $_REQUEST['gde'] )) {
                    if ( in_array( $_REQUEST['gde'], $event_recurring_dates ) ){
                        $event_recurring_dates = array( esc_html( $_REQUEST['gde'] ) );
                    }
                }

                foreach( $event_recurring_dates as $key => $date ) {
                    $schedule = array();

                    if ( isset( $recuring_data['different_times'] ) && $recuring_data['different_times'] == '1' ) {
                        $starttimes = isset( $recuring_data['starttimes'][$key] ) ? $recuring_data['starttimes'][$key] : '';
                        $endtimes = isset( $recuring_data['endtimes'][$key] ) ? $recuring_data['endtimes'][$key] : '';
                    }

                    $sdate = strtotime( $date . ' ' . $starttimes );
                    $edate = strtotime( $date . ' ' . $endtimes );

                    if ( $starttimes > $endtimes ) {
                        $edate = strtotime( $date . ' ' . $endtimes . " +1 day" );
                    }

                    // Hide past dates
                    if ( $hide_past_dates && strtotime( date_i18n( 'Y-m-d', $edate ) ) < strtotime( date_i18n( 'Y-m-d', current_time( 'timestamp' ) ) ) ) {
                        continue;
                    }

                    $schedule['start'] = date_i18n( $geodir_date_time_format, $sdate );
                    if ( $sdate != $edate ) {
                        $schedule['end'] = date_i18n( $geodir_date_time_format, $edate );
                    }

                    $schedules[] = $schedule;

                    if ( !empty( $limit ) && count( $schedules ) >= $limit ) {
                        break;
                    }
                }
            }
        }
    }

    return $schedules;
}