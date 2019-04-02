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
                            <span class="fa fa-stack sd-verified-badge" title="<?php _e('Verified Owner', 'supreme-directory'); ?>">
                                <i class="fa fa-circle fa-inverse"></i>
                                <i class="fa fa-check-circle"></i>
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
                            class="fa fa-edit"></i> <?php echo __('Edit', 'supreme-directory'); ?></a>
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

                                echo '<a href="' . esc_url($current_url) . '" class="supreme-btn supreme-btn-small supreme-edit-btn"><i class="fa fa-question-circle"></i> ' . __('Claim', 'supreme-directory') . '</a>';
                            } else {
                                if (is_user_logged_in()) {
                                    echo '<div class="geodir-company_info">';
                                    echo '<div class="geodir_display_claim_popup_forms"></div>';
                                    echo '<a href="javascript:void(0);" class="supreme-btn supreme-btn-small supreme-edit-btn geodir_claim_enable"><i class="fa fa-question-circle"></i> ' . __('Claim', 'supreme-directory') . '</a>';
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

                                    echo '<a href="' . esc_url($login_to_claim_url) . '" class="supreme-btn supreme-btn-small supreme-edit-btn"><i class="fa fa-question-circle"></i> ' . __('Claim', 'supreme-directory') . '</a>';

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
                    $sd_social .= '<a rel="nofollow" target="_blank" href="' . esc_url($post->geodir_website) . '"><i class="fa fa-external-link-square"></i></a>';
                }
                if (isset($post->geodir_facebook) && $post->geodir_facebook && !empty($available_fields) && in_array('geodir_facebook', $available_fields)) {
                   $sd_social .='<a rel="nofollow" target="_blank" href="' . esc_url($post->geodir_facebook) . '"><i class="fa fa-facebook-official"></i></a>';
                }
                if (isset($post->geodir_twitter) && $post->geodir_twitter && !empty($available_fields) && in_array('geodir_twitter', $available_fields)) {
                    $sd_social .='<a rel="nofollow" target="_blank" href="' . esc_url($post->geodir_twitter) . '"><i class="fa fa-twitter-square"></i></a>';
                }
                if (isset($post->geodir_contact) && $post->geodir_contact && !empty($available_fields) && in_array('geodir_contact', $available_fields)) {
                    $sd_social .='<a href="tel:' . esc_attr($post->geodir_contact) . '"><i class="fa fa-phone-square"></i>&nbsp;:&nbsp;' . esc_attr($post->geodir_contact) . '</a>';
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
                    $send_buttons .= '<i class="fa fa-envelope"></i> ';
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
                                    class="fa fa-facebook"></i></a></li>
                        <li><a rel="nofollow" target="_blank" title="<?php echo __('Share on Twitter', 'supreme-directory'); ?>"
                               href="http://twitter.com/share?text=<?php echo urlencode(html_entity_decode(get_the_title(), ENT_COMPAT, 'UTF-8')); ?>&url=<?php echo urlencode(get_the_permalink()); ?>"><i
                                    class="fa fa-twitter"></i></a></li>
                        <li><a rel="nofollow" target="_blank" title="<?php echo __('Share on Google Plus', 'supreme-directory'); ?>"
                               href="https://plus.google.com/share?url=<?php echo urlencode(get_the_permalink()); ?>"><i
                                    class="fa fa-google-plus"></i></a></li>
                    <?php } else { ?>
                        <li><a rel="nofollow" target="_blank" title="<?php echo __('Share on Facebook', 'supreme-directory'); ?>"
                               href=""><i class="fa fa-facebook"></i></a></li>
                        <li><a rel="nofollow" target="_blank" title="<?php echo __('Share on Twitter', 'supreme-directory'); ?>"
                               href=""><i class="fa fa-twitter"></i></a></li>
                        <li><a rel="nofollow" target="_blank" title="<?php echo __('Share on Google Plus', 'supreme-directory'); ?>"
                               href=""><i class="fa fa-google-plus"></i></a></li>
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

    echo '<div class="home-more" id="sd-home-scroll"><a href="#sd-home-scroll" ><i class="fa fa-chevron-down"></i></a></div>';
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

add_filter('sd_featured_image','sd_location_manager_image');
function sd_location_manager_image($image){

    if(class_exists('GeoDir_Location_SEO') && geodir_is_page('location')){
        $location_seo = GeoDir_Location_SEO::get_location_seo();
        //print_r($location_seo);
        if(!empty($location_seo->image)){
            $full_image_url = wp_get_attachment_image_src($location_seo->image, 'full');
            $image = $full_image_url[0];
        }
    }

    return $image;
}

add_action('wp','sd_compatibility_action',15);
function sd_compatibility_action(){
    // remove the actions disabling the featured image
    remove_filter( "get_post_metadata", array('GeoDir_Template_Loader','filter_thumbnail_id'), 10 );
}