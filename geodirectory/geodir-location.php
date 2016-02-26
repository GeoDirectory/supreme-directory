<?php
/**
 * Template for the locations page
 *
 * You can make most changes via hooks or see the link below for info on how to replace the template in your theme.
 *
 * @link http://docs.wpgeodirectory.com/customizing-geodirectory-templates/
 * @since 1.0.0
 * @package GeoDirectory
 */
// get header
get_header(); ?>
    <div class="featured-area">
        <div class="featured-img" <?php
        if (has_post_thumbnail()) { // check if the post has a Post Thumbnail assigned to it.
            $full_image_url = wp_get_attachment_image_src(get_post_thumbnail_id(), 'full');
            ?> style="background-image: url(<?php echo $full_image_url[0]; ?>);" <?php }
        ?>>

        </div>
        <div class="header-wrap">
            <?php
            if (is_singular()) {
                ?>
                <h1 class="entry-title"><?php echo do_shortcode('[gd_current_location_name]'); ?></h1>
            <?php
            } else {
                ?>
                <h2 class="entry-title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
            <?php
            }
            if (get_post_meta(get_the_ID(), 'subtitle', true)) {
                echo '<div class="entry-subtitle">' . get_post_meta(get_the_ID(), 'subtitle', true) . '</div>';
            }
            ?>

            <?php
            echo do_shortcode('[gd_advanced_search]');
            echo do_shortcode('[gd_popular_post_category category_limit=5]');
            echo '<div class="home-more"><a href="#geodir_content"><i class="fa fa-chevron-down"></i></a></div>';
            ?>
        </div>
    </div>
<?php
###### WRAPPER OPEN ######
/** This action is documented in geodirectory-templates/add-listing.php */
do_action('geodir_wrapper_open', 'home-page', 'geodir-wrapper', '');

###### TOP CONTENT ######
/** This action is documented in geodirectory-templates/add-listing.php */
do_action('geodir_top_content', 'home-page');
/**
 * Calls the top section widget area and the breadcrumbs on the locations page.
 *
 * @since 1.1.0
 */
do_action('geodir_location_before_main_content');
/** This action is documented in geodirectory-templates/add-listing.php */
do_action('geodir_before_main_content', 'home-page');

###### SIDEBAR ######
/**
 * Adds the location page left sidebar to the location template page if active.
 *
 * @since 1.1.0
 */
do_action('geodir_location_sidebar_left');

###### MAIN CONTENT WRAPPERS OPEN ######
/** This action is documented in geodirectory-templates/add-listing.php */
do_action('geodir_wrapper_content_open', 'home-page', 'geodir-wrapper-content', '');

###### MAIN CONTENT ######
/**
 * Calls the locations page main content area on the locations template page.
 *
 * @since 1.1.0
 */
do_action('geodir_location_content');

###### MAIN CONTENT WRAPPERS CLOSE ######
/** This action is documented in geodirectory-templates/add-listing.php */
do_action('geodir_wrapper_content_close', 'home-page');

###### SIDEBAR ######
/**
 * Adds the location page right sidebar to the location template page if active.
 *
 * @since 1.1.0
 */
do_action('geodir_location_sidebar_right');

# WRAPPER CLOSE ######	
/** This action is documented in geodirectory-templates/add-listing.php */
do_action('geodir_wrapper_close', 'home-page');

###### BOTTOM SECTION WIDGET AREA ######
/**
 * Adds the location page bottom widget area to the location template page if active.
 *
 * @since 1.1.0
 */
do_action('geodir_sidebar_location_bottom_section');

//get footer
get_footer();    